<?php
/*
NAME
    ASCX12 - ASCX12 EDI to XML Module

SYNOPSIS
    use ASCX12;

    my $ascx12 = new ASCX12();
    $ascx12->convertfile("/path/to/edi_input", "/path/to/xml_output");

--------------------
INFORMATION
Module Description
--------------------

ASCX12 started as a project to process X12 EDI files from shipping
vendors (i.e. transaction sets 110, 820 and 997).  However, this module can be
extended to support any valid transaction set (catalog).

--------------------
Why are you doing this?
--------------------

If you've ever taken a look at an ASCX12 document you'll see why.  The EDI format is
very compact, which makes is great for transmission.  However this comes at a cost.

The main challenge when dealing with EDI data is parsing through the structure.
Here we find loops within loops within loops.  In this non-extensible, flat format,
human parsing is nearly impossible and machine parsing is a task at best.

A quick background of how a typical EDI is formed:


+---->  ISA - Interchange Control Header
|          GS - Functional Group Header       <--------+
|              ST - Transaction Set Header             |
Envelope              [transaction set specific]  Functional Group
|              SE - Transaction Set Trailer            |
|          GE - Functional Group Trailer      <--------+
+---->  ISE - Interchange Control Trailer



The Transmission Envelope can have one or more Functional Group.  A Functional Group
can have one or more Transaction Set.  Then each specific catalog (transaction set)
can have it's own hierarchical rules.

This sort of structure really lends itself to XML.  So using the power of Perl,
this module was created to make accessing EDI information easier.

To learn more, the official ASC X12 group has a website L<http://www.x12.org>.

--------------------
Module Limitations
--------------------

This is a new module and has a few limitations.

    * EDI -> XML
      This module converts from EDI to XML.  If you want to go in the other direction, suggest creating an XSL stylesheet and use L<XSLT|XSLT> or similar to preform a transformation.

    * Adding Transaction Sets
      Adding new catalogs is a manual process.  The L<ASCX12::Segments|ASCX12::Segments> and the L<ASCX12::Catalogs|ASCX12::Catalogs> need to be manually updated.  A future development effort could store this information in dbm files with an import script if demand exists.

    Style Guide

    You will (hopefully) find consistent coding style throughout this module.
    Any private variable or method if prefixed with an underscore (C<_>).  Any
    static method or variable is named in C<ALL_CAPS>.

        The tabs are set at 4 spaces and the POD is physically close to the stuff it
        is describing to promote fantastic ongoing documentation.


        REQUIREMENTS
        We use the L<Carp|Carp> module to handle errors.  Some day there may be a better
        error handler and maybe an error object to reference, but for now it croaks
        when there is a problem.

        L<ASCX12::Catalogs|ASCX12::Catalogs> module is required and probably part of this package, as is the L<ASCX12::Segments|ASCX12::Segments>.

--------------------
VARIABLE AND METHODS
--------------------

Private Variables

    These variables are not exported and not intended to be accessed externally.
    They are listed here for documentation purposes only.

    C<@_LOOPS>
    Dynamic and keeps track of which loop we are on.

    C<%_XMLREP>
    Static variable used to lookup bad XML characters.

    C<$_XMLHEAD>
    Static variable containing the XML header for the output.
*/

namespace Coedition\EDI;
use Coedition\EDI\Catalogs\Catalogs;
use Coedition\EDI\Catalogs\Segments;
use Coedition\EDI\Catalogs\Catalog832;
use Coedition\EDI\Catalogs\Catalog846;
use Coedition\EDI\Catalogs\Catalog850;
use Coedition\EDI\Catalogs\Catalog855;
use Coedition\EDI\Catalogs\Catalog856;
use Coedition\EDI\Catalogs\Catalog997;

class ASCX12 {
    const CATALOGS = [832, 846, 850, 855, 856, 997];
    private $_LOOPS = [];
    private $_XMLHEAD = '<?xml version="1.0"?><ascx:message xmlns:ascx="http://www.vermonster.com/LIB/xml-ascx12-01/ascx.rdf" xmlns:loop="http://www.vermonster.com/LIB/xml-ascx12-01/loop.rdf">';

    private $ST;
    private $DES;
    private $SBS;
    private $CATALOGS;
    private $SEGMENTS;
    private $ELEMENTS;
    private $lastloop;

    public function __construct($st='\x85', $des='\x1D', $sbs='\x1F') {
        $this->ST = $st;
        $this->DES = $des;
        $this->SBS = $sbs;
        $this->CATALOGS = new Catalogs();
        $this->SEGMENTS = Segments::SEGMENTS;
        $this->ELEMENTS = Segments::ELEMENTS;
    }

    public function loadCatalog($catalog) {
        $this->CATALOGS->loadCatalog($catalog);
    }

    /*
     *   boolean = $obj->convertfile($input, $output)
     *
     *   This method will transform and EDI file to XML using the configuration information
     *   passed in from the constructor.
     */
    public function convertfile($infile, $outfile, $pretty_print = true) {
        $out = $this->convertdata(file_get_contents($infile), $pretty_print);
        file_put_contents($outfile,$out);
        return 1;
    }

    /*
     * string = $obj->convertdata($input)
     * This method will transform an EDI data stream, returning wellformed XML.
     */
    public function convertdata($in, $pretty_print = true) {
        // replace some commonly used delimiters with the ones we parse for..
        $in = str_replace('*', '|', $in);
        $in = str_replace('~', "\n", $in);

        if (!preg_match("/$this->ST/", $in)) {
            throw new \Exception("EDI Parsing Error:  Segment Terminator '".$this->ST."' not found");
        }
        if (!preg_match("/$this->DES/", $in)) {
            throw new \Exception("EDI Parsing Error:  Data Element Seperator '".$this->DES."' not found");
        }

        $this->_unloadCatalog();
        $out = $this->_XMLHEAD;

        foreach (explode($this->ST, $in) as $row) {
            $out .= $this->_procSegment($row);
        }
        $out .= $this->_procSegment('');
        $out .=  '</ascx:message>';

        return $pretty_print ? $this->formatXml($out) : $out;
    }

    /*
     * string = XMLENC($string)
     *
     * Static public method used to encode and return data suitable for ASCII XML CDATA
     * $xml_ready_string = ASCX12::XMLENC($raw_data);
    */
    public function XMLENC($str = null) {
        if (!is_null($str)) {
            $xml_search  = ['&','<','>','"'];
            $xml_replace = [ '&amp;', '&lt;', '&gt;', '&quot;'];
            # relace any &<>" characters
            $str = str_replace($xml_search, $xml_replace, $str);
            # get rid on any non-ASCII characters
            $str = preg_replace('/[\x80-\xff]/u','',$str);
            # get rid on any non-ASCII characters
            $str = preg_replace('/[\x01-\x1f]/u','',$str);
        }
        return $str;
    }

    /*
     * string = _procSegment($segment_data);
     * This is an internal private method that processes a segment using $LOOPNEST.
     * It is called by C<convertfile()> or C<convertdata()> while looping per-segment.
     */
    private function _procSegment($segment) {
        if (isset($this->CATALOGS->IS_CHILD)) {
            return $this->_procSegment_in_child($segment);
        }
        $segment = str_replace("\n",'',$segment);
        if (preg_match('/[0-9A-Za-z]*/', $segment, $matches)) {
            $segcode = null;
            $elements = [];
            foreach (explode($this->DES,$segment) as $key => $value) {
                if ($key == 0) {
                    $segcode = $value;
                }
                else {
                    $elements[] = $value;
                }
            }

            if ($elements) {
                $catalog_name = 'Catalog' . $elements[0];
                //$file_name = 'Catalogs/' . $catalog_name . '.php';
                if (in_array($elements[0], self::CATALOGS)) {
                    //include_once($file_name);
                    $catalog_name = "Coedition\\EDI\\Catalogs\\" . $catalog_name;
                    $catalog = new $catalog_name();
                    # add the temp_SEGMENTS to the $SEGMENTS
                    $this->SEGMENTS = array_merge(($catalog_name)::$temp_SEGMENTS, $this->SEGMENTS);
                    # add the temp_ELEMENTS to the $ELEMENTS
                    $this->ELEMENTS = array_merge(($catalog_name)::$temp_ELEMENTS, $this->ELEMENTS);
                    # END load additional segments/elements
                }

                if ($segcode and $segcode == "ST") {
                    $this->_unloadCatalog();
                    $this->CATALOGS->loadCatalog($elements[0]);
                    ## IS_CHILD not defined until after Catalog loaded
                    ## Use alternate parsing starting with "ST" segment
                    if (isset($this->CATALOGS->IS_CHILD)) {
                        return $this->_procSegment_in_child($segment);
                    }
                }
            }

            # check to see if we need to close a loop
            $curloop = $segcode ? $this->SEGMENTS[$segcode][3] : null;
            $xml = '';
            $tmp = $this->_closeloop($curloop,$this->lastloop, $segcode);
            if ($tmp) {
                $xml .= $tmp;
            }
            if (count($elements)) {
                # check to see if we need to open a loop
                $tmp = $this->_openloop($curloop, $this->lastloop);
                if ($tmp) {
                    $xml .= $tmp;
                }

                # now the standard segment (and elements)
                $xml .= '<segm code="'. $this->XMLENC($segcode) . '"';
                if($this->SEGMENTS[$segcode]) {
                    $xml .= ' desc="'. $this->XMLENC($this->SEGMENTS[$segcode][0]) . '"';
                }
                $xml .= '>';

                # make our elements
                $xml .= $this->_procElement($segcode, $elements);

                # close the segment
                $xml .= '</segm>';

                # keep track
                $this->lastloop = $curloop;
            }
            return $xml;
        }
        return null;
    }

    /*
     * string = _procSegment_in_child($segment_data);
     * This is an internal private method that processes a segment using $IN_CHILD.
     * It is called by C<_procSegment()> when $IN_CHILD is defined.
    */
    private function _procSegment_in_child($segment) {
        $segment = str_replace("\n",'',$segment);
        $this->lastloop = (strlen($this->lastloop)) ? $this->lastloop : '';
        if (preg_match('/[0-9A-Za-z]*/', $segment, $matches)) {
            //my ($segcode, @elements) = split(/$self->{DES}/, $segment);
            //list($segcode, $elements) = preg_split('/'.$this->DES.'/', $segment);
            $elements = explode($this->DES, $segment);
            $segcode = array_shift($elements);

            if ($segcode and $segcode == "ST") {
                ##     \@_LOOPS, $ASCX12::Catalogs::IS_CHILD;
            }
            else if ($segcode) {
                ## warn "segcode = $segcode\n";
            }
            else {
                ## warn "no segcode\n";
                ## final loop close
                return $this->_closeloop('', $this->lastloop, '');
            }
            $xml = '';
            $is_child = null;
            // get the last element
            $curloop = $this->_LOOPS[count($this->_LOOPS) - 1];
            while(is_null($is_child)) {
                if (!isset($this->CATALOGS->IS_CHILD[$curloop][$segcode])) {
                    echo "Catalog configuration problem";
                    // DEBUGGING
                    /*
                    echo "is_child array\n";
                    print_r($this->CATALOGS->IS_CHILD);
                    echo "segment: $segment\n";
                    echo "is_child curloop: $curloop\n";
                    echo "is_child segcode: $segcode\n";
                    */
                    exit;
                }
                $is_child = $this->CATALOGS->IS_CHILD[$curloop][$segcode];
                if (is_null($is_child)) {
                    $xml .= $this->_execclose($curloop);
                    $curloop = $this->_LOOPS[count($this->_LOOPS) - 1];
                }
            }

            if ($elements) {
                # check to see if we need to open a loop
                if ($is_child == 0) {
                    array_push($this->_LOOPS, $segcode);
                    $xml .= '<'.$this->XMLENC($segcode).'>';
                }

                # now the standard segment (and elements)
                $xml .= '<segm code="'.$this->XMLENC($segcode).'"';
                $xml .= $this->SEGMENTS[$segcode] ? ' desc="'.$this->XMLENC($this->SEGMENTS[$segcode][0]).'"' : '';
                $xml .= '>';

                # make our elements
                $xml .= $this->_procElement($segcode, $elements);

                # close the segment
                $xml .= '</segm>';

                # keep track
                $this->lastloop = $curloop;
            }
            return $xml;
        }
        return null;
    }

    /*
     * string = _procElement($segment_code, @elements)
     *
     * This is a private method called by C<_procSegment()>.  Each segment consists of
     * elements, this is where they are processed.
    */
    private function _procElement($segcode, $elements) {
        $i = 1;
        $xml = '';
        foreach ($elements as $element) {
            if (preg_match('/[0-9A-Za-z]/',$element,$matches)) {
                $elename = null;
                $elename = ($i >= 10) ? $segcode . $i : $segcode . '0' . $i;
                $xml .= '<elem code="'. $this->XMLENC($elename) . '"';
                if (isset($this->ELEMENTS[$elename]) && $this->ELEMENTS[$elename]) {
                //if ($this->ELEMENTS[$elename]) {
                    $xml .= ' desc="' . $this->XMLENC($this->ELEMENTS[$elename][0]) . '"';
                }
                $xml .= '>' . $this->XMLENC($element) . '</elem>';
            }
            $i++;
        }
        return $xml;
    }


    /*
     * string = _openloop($loop_to_open, $last_opened_loop)
     * This is an internal private method.  It will either open a loop if we can
     * or return nothing.
     */
    private function _openloop($newloop, $lastloop) {
        if ($this->_CANHAVE($lastloop, $newloop)) {
            array_push($this->_LOOPS, $newloop);
            return '<'.$this->XMLENC($newloop).'>';
        }
        return null;
    }

    /*
     * void = _closeloop($loop_to_close, $last_opened_loop, $current_segment, $trigger)
     * This routine is a private method.  It will recurse to close any open loops.
     */
    private function _closeloop($newloop, $lastloop, $currentseg, $once = 0) {
        $this->lastloop = (strlen($this->lastloop)) ? $this->lastloop : '';
        $xml = null;
        # Case when there are two consecutive loops
        if ($newloop && $lastloop && $currentseg == $lastloop && ($currentseg != '')) {
            $xml = $this->_execclose($lastloop);
            return $xml;
        }
        # "Standard Case"
        else if ($this->_CANHAVE($newloop, $lastloop)) {
            $xml = $this->_execclose($lastloop);
            return $xml;
        }
        # Recusrively close loops
        else {
            $parent_loops_to_close = [];
            if (count($this->_LOOPS)) {
                #Close in reverse order
                foreach (array_reverse($this->_LOOPS) as $testloop) {
                    # found a loop, see which ones we ough to close
                    if ($testloop == $newloop) {
                        if ($parent_loops_to_close) {
                            foreach ($parent_loops_to_close as $closeme) {
                                if ($closeme) {
                                    $xml .= $this->_execclose($closeme);
                                }
                            }
                            # See if the current loop ought to be closed
                            if ($once != 1) {
                                if ($tmp = $this->_closeloop($newloop, $this->lastloop, $currentseg, 1)) {
                                    $xml .= $tmp;
                                }
                            }
                            return $xml;
                        }
                    }
                    # Push into the loops to close
                    else {
                        if ($testloop) {
                            array_push($parent_loops_to_close, $testloop);
                        }
                    }
                }
            }
        }
        return null;
    }

    /*
     * string = _execclose($loop_to_close)
     *
     * Private internal method to actually return the XML that signifies
     * a closed loop.  It is called by C<_closeloop()>.
     */

    private function _execclose($loop = null) {
        if (is_null($loop)) {
            return null;
        }

        if (preg_match('/[A-Za-z0-9]*/', $loop, $matches))
        {
            array_pop($this->_LOOPS);
            //$this->lastloop = $this->_LOOPS[-1];
            if (isset($this->_LOOPS[count($this->_LOOPS) - 1])) {
                $this->lastloop = $this->_LOOPS[count($this->_LOOPS) - 1];
            }
            else {
                $this->lastloop = null;
            }
            return ($this->XMLENC($loop)) ? '</'.$this->XMLENC($loop).'>' : '';
        }
        return null;
    }

    /*
     * void = _unloadCatalog()
     *
     *  Private method that clears out catalog data and loads standard ASCX12 structure.
     *  Also initializes ISA and GS data common to all Catalogs.
     */
    private function _unloadCatalog() {
        $this->CATALOGS->LOOPNEST = [];
        $this->CATALOGS->IS_CHILD = null;
        $this->CATALOGS->loadCatalog(0);
    }

    /*
     * boolean = _CANHAVE($parent_loop, $child_loop)
     * This is a private static method.  It uses the rules in the ASCX12::Catalogs|ASCX12::Catalogs
     * to determine if a parent is allowed to have the child loop. Returns 0 or 1.
     */
    private function _CANHAVE($parent, $child = null) {
        # root-level can have anything
        if (!$parent) {
            return 1;
        }

        if(is_null($child) || !$child) {
            return 0;
        }

        foreach ($this->CATALOGS->LOOPNEST[$parent] as $value) {
            if ($value == $child) {
                return 1;
            }
        }
        return 0;
    }

    private function formatXml($xml) {
        $dom = dom_import_simplexml(simplexml_load_string($xml))->ownerDocument;
        $dom->formatOutput = true;
        return $dom->saveXML();
    }
}
