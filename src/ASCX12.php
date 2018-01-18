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

class ASCX12 {

    private $_LOOPS = [];
    private $_XMLHEAD = '<?xml version="1.0"?><ascx:message xmlns:ascx="http://www.vermonster.com/LIB/xml-ascx12-01/ascx.rdf" xmlns:loop="http://www.vermonster.com/LIB/xml-ascx12-01/loop.rdf">';

    private $ST;
    private $DES;
    private $SBS;
    private $CATALOGS;
    private $SEGMENTS;
    private $ELEMENTS;
    private $lastloop;


    /*
    --------------------
    Public Methods
    --------------------

    object = new([$segment_terminator], [$data_element_separator], [$subelement_separator])

    The new method is the OO constructor.  The default for the segment terminator
    is ASCII C<85> hex. The default for the data element separator is ASCII C<1D> hex.  The default
    for the sub-element separator is ASCII C<1F> hex.

    my $xmlrpc = new ASCX12();

    The defaults can be overridden by passing them into the constructor.

    my $xmlrpc = new ASCX12('\x0D', '\x2A', '\x3A');

    The object that returns is now ready to transform EDI files.
    */
    public function __construct($st='\x85', $des='\x1D', $sbs='\x1F') {
        $this->ST = $st;
        $this->DES = $des;
        $this->SBS = $sbs;
        $this->CATALOGS = new Catalogs();
        $this->SEGMENTS = Segments::$SEGMENTS;
        $this->ELEMENTS = Segments::$ELEMENTS;
    }
    /*
        boolean = $obj->convertfile($input, $output)

        This method will transform and EDI file to XML using the configuration information
        passed in from the constructor.

        my $xmlrpc = new ASCX12();
        $xmlrpc->convertfile('/path/to/EDI.dat', '/path/to/EDI.xml');

        You may also pass filehandles (or references to filehandles):

        $xmlrpc->convertfile(\*INFILE, \*OUTFILE);
    */
    public function convertfile($in, $out) {
        $inhandle = null;
        $outhandle = null;
        $bisinfile = null;
        $bisoutfile = null;
        $st_check=0;
        $des_check=0;

        $this->_unload_catalog();

        $outhandle = $out;

        file_put_contents($out, $this->_XMLHEAD);
        $inhandle = file_get_contents($in);

        foreach (explode("\n", $inhandle) as $row) {
            file_put_contents($outhandle, $this->_proc_segment($row), FILE_APPEND);
        }
        file_put_contents($outhandle, $this->_proc_segment(''), FILE_APPEND);
        file_put_contents($outhandle, '</ascx:message>', FILE_APPEND);
        return 1;
    }

/*
        string = $obj->convertdata($input)

        This method will transform an EDI data stream, returning wellformed XML.

        my $xmlrpc = new ASCX12();
        my $xml = $xmlrpc->convertdata($binary_edi_data);
*/
        public function convertdata($in) {
            //croak "EDI Parsing Error:  Segment Terminator \"$self->{ST}\" not found" unless ($in =~ m/$self->{ST}/);
            if (!(strpos($this->ST, $in) !== false)) {
                die("EDI Parsing Error:  Segment Terminator '".$this->ST."' not found");
            }
            //croak "EDI Parsing Error:  Data Element Seperator \"$self->{DES}\" not found" unless ($in =~ m/$self->{DES}/);
            if (!(strpos($this->DES, $in) !== false)) {
                die("EDI Parsing Error:  Data Element Seperator '".$this->DES."' not found");
            }

            $out = $this->_XMLHEAD;
            $matches = [];
            preg_replace('/^\\/', 0, $this->ST, $matches);
            $eos = $matches[0];
            $data = explode(pack("C*", ord($eos)), $in);
            foreach($data as $key => $value) {
                $out .= $this->_proc_segment($key);
            }
            $out .= $this->_proc_segment('');

            return $out;
        }

    /*
        string = XMLENC($string)

        Static public method used to encode and return data suitable for ASCII XML CDATA

        $xml_ready_string = ASCX12::XMLENC($raw_data);
    */
        public function XMLENC($str = null) {
            if (!is_null($str)) {
                /*
                $str =~ s/([&<>"])/$_XMLREP{$1}/ge;    # relace any &<>" characters
                $str =~ s/[\x80-\xff]//ge;             # get rid on any non-ASCII characters
                $str =~ s/[\x01-\x1f]//ge;             # get rid on any non-ASCII characters
                */
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
        ----------------
        Private Methods
        ----------------
        string = _proc_segment($segment_data);

        This is an internal private method that processes a segment using $LOOPNEST.
        It is called by C<convertfile()> or C<convertdata()> while looping per-segment.
    */
        private function _proc_segment($segment) {
            if (isset($this->CATALOGS->IS_CHILD)) {
                return $this->_proc_segment_in_child($segment);
            }
            $segment = str_replace("\n",'',$segment);
            if (preg_match('/[0-9A-Za-z]*/', $segment, $matches)) {
                // @TODO AD not sure about this `$segcode` ATM, need to revisit in debugging
                //my ($segcode, @elements) = split(/$self->{DES}/, $segment);
                //list($segcode, $elements) = preg_split('/'.$this->DES.'/', $segment);
                //list($segcode, $elements) = preg_split('/\|/', $segment);
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

                $catalog_name = 'Catalog' . $elements[0];
                $file_name = 'Catalogs/'.$catalog_name.'.php';
                //if (class_exists($catalog_name)) {
                if (file_exists($file_name)) {
                    include_once($file_name);
                    $catalog_name = "Coedition\\EDI\\Catalogs\\" . $catalog_name;
                    $catalog = new $catalog_name();
                    # add the temp_SEGMENTS to the $SEGMENTS
                    $this->SEGMENTS = array_merge($catalog->temp_SEGMENTS, $this->SEGMENTS);
                    # add the temp_ELEMENTS to the $ELEMENTS
                    $this->ELEMENTS = array_merge($catalog->temp_ELEMENTS, $this->ELEMENTS);
                    # END load additional segments/elements
                }
                if ($segcode and $segcode == "ST")
                {
                    $this->_unload_catalog();
                    $this->CATALOGS->load_catalog($elements[0]);
                    ## IS_CHILD not defined until after Catalog loaded
                    ## Use alternate parsing starting with "ST" segment
                    if (isset($this->CATALOGS->IS_CHILD)) {
                        return $this->_proc_segment_in_child($segment);
                    }
                }

                # check to see if we need to close a loop
                //my $curloop = $ASCX12::Segments::SEGMENTS->{$segcode}[3] if $segcode;
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
                    $xml .= $this->_proc_element($segcode, $elements);

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
        string = _proc_segment_in_child($segment_data);
        This is an internal private method that processes a segment using $IN_CHILD.
        It is called by C<_proc_segment()> when $IN_CHILD is defined.
*/
        private function _proc_segment_in_child($segment) {
            $segment = str_replace("\n",'',$segment);
            $this->lastloop = (strlen($this->lastloop)) ? $this->lastloop : '';
            if (preg_match('/[0-9A-Za-z]*/', $segment, $matches)) {
                //my ($segcode, @elements) = split(/$self->{DES}/, $segment);
                list($segcode, $elements) = preg_split('/'.$this->DES.'/', $segment);
                if ($segcode and $segcode == "ST") {
                    ## 	\@_LOOPS, $ASCX12::Catalogs::IS_CHILD;
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
                    $xml .= $this->_proc_element($segcode, $elements);

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
        string = _proc_element($segment_code, @elements)

        This is a private method called by C<_proc_segment()>.  Each segment consists of
        elements, this is where they are processed.
*/
        private function _proc_element($segcode, $elements) {
            //my ($self, $segcode, @elements) = @_;
            $i = 1;
            $xml = '';
            foreach ($elements as $element) {
                if (preg_match('/[0-9A-Za-z]/',$element,$matches)) {
                    $elename = null;
                    $elename = ($i >= 10) ? $segcode . $i : $segcode . '0' . $i;
                    $xml .= '<elem code="'. $this->XMLENC($elename) . '"';
                    if ($this->ELEMENTS[$elename]) {
                        $xml .= ' desc="' . $this->XMLENC($this->ELEMENTS[$elename][0]) . '"';
                    }
                    $xml .= '>' . $this->XMLENC($element) . '</elem>';
                }
                $i++;
            }
            return $xml;
        }


        /*
        string = _openloop($loop_to_open, $last_opened_loop)


        This is an internal private method.  It will either open a loop if we can
        or return nothing.
        */
        private function _openloop($newloop, $lastloop) {
            if ($this->_CANHAVE($lastloop, $newloop)) {
                array_push($this->_LOOPS, $newloop);
                return '<'.$this->XMLENC($newloop).'>';
            }
            return null;
        }

/*
        void = _closeloop($loop_to_close, $last_opened_loop, $current_segment, $trigger)

        This routine is a private method.  It will recurse to close any open loops.
*/
        private function _closeloop($newloop, $lastloop, $currentseg, $once = 0) {
            //$lastloop ||= '';
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
        string = _execclose($loop_to_close)

        Private internal method to actually return the XML that signifies a closed
        loop.  It is called by C<_closeloop()>.
        */

        private function _execclose($loop = null) {
            if (is_null($loop)) {
                return null;
            }

            if (preg_match('/[A-Za-z0-9]*/', $loop, $matches))
            {
                array_pop($this->_LOOPS);
                //$this->lastloop = $this->_LOOPS[-1];
                $this->lastloop = $this->_LOOPS[count($this->_LOOPS) - 1];
                return ($this->XMLENC($loop)) ? '</'.$this->XMLENC($loop).'>' : '';
            }
            return null;
        }

/*
    void = _unload_catalog()

    Private method that clears out catalog data and loads standard ASCX12 structure.
    Also initializes ISA and GS data common to all Catalogs.
*/
    private function _unload_catalog() {
    //$ASCX12::Catalogs::LOOPNEST = ();
    //$ASCX12::Catalogs::IS_CHILD = undef;
    $this->CATALOGS->LOOPNEST = [];
    $this->CATALOGS->IS_CHILD = null;
    $this->CATALOGS->load_catalog(0);
    }

/*
    boolean = _CANHAVE($parent_loop, $child_loop)

    This is a private static method.  It uses the rules in the L<ASCX12::Catalogs|ASCX12::Catalogs>
    to determine if a parent is allowed to have the child loop. Returns C<0> or C<1>.
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

    public function load_catalog($catalog) {
        $this->CATALOGS->load_catalog($catalog);
    }

    public function dump_catalog() {
        $this->CATALOGS->dump_catalog();
    }

    /*
    @TODO:
    Here are some things that would make this module even better.  They are in no particular order:

    * Error Handling

    Maybe throw in an error object to keep track of things

    * Encoding Support

    Anyone that could review to make sure we are using the correct encodings
    We basically read in the EDI file in binary and use the ASCII HEX-equivalent for the
    separators.  Many EDI-producing systems use EBCDIC and not UTF-8 so be careful when
    specifying the values.

    * B<Live> Transaction Set (Catalog) Library

        Make a live repository of transaction set data (catalogs).  I'd really like use XML to describe
        each catalog and import them to local dbm files or tied hashes during install and via an update
        script.  This project will be driven if there is adaquate demand.

        According to the ASC X12 website (L<http://www.x12.org>), there are 315 transaction sets.  This module has 4, so
        there are 311 that could be added.

        Documentation for Catalog 175 (Court Notice Transaction Set) is available from
        the US Bankruptcy Courts (L<http://www.ebnuscourts.com/documents/edi.adp>).


        * XML Documentation

        Create a DTD and maybe even an XML Schema for the XML output.  There ought to be better
        documentation here.
    */
}
