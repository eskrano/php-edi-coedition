<?php
# namespace

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

namespace Coedition\ASCX12;
use Coedition\ASCX12\Catalogs\Catalogs;
use Coedition\ASCX12\Catalogs\Segments;

class ASCX12 {

    public $_LOOPS = [];

    public $_XMLREP = [
        '&' => '&amp;'
        ,'<' => '&lt;'
        ,'>' => '&gt;'
        ,'"' => '&quot;'
        ];

    private $ST;
    private $DES;
    private $SBS;

    public $_XMLHEAD = '<?xml version="1.0"?><ascx:message xmlns:ascx="http://www.vermonster.com/LIB/xml-ascx12-01/ascx.rdf" xmlns:loop="http://www.vermonster.com/LIB/xml-ascx12-01/loop.rdf">';

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
        /* @TODO AD error checking if file can be writted or includes wildcards?
        if (ref($out) eq "GLOB" or ref(\$out) eq "GLOB"
        or ref($out) eq 'FileHandle' or ref($out) eq 'IO::Handle')
        {
        $outhandle = $out;
        }
        else
        {
        local(*XMLOUT);
        open (XMLOUT, "> $out") || croak "Cannot open file \"$out\" for writing: $!";
        $outhandle = *XMLOUT;
        $bisoutfile = 1;
        }
        */

        file_put_contents($outhandle,$this->_XMLHEAD);

        $inhandle = $in;
        /* @TODO AD error checking if file can be written or includes wildcards?
        print {$outhandle} $ASCX12::_XMLHEAD;
        {
        if (ref($in) eq "GLOB" or ref(\$in) eq "GLOB"
        or ref($in) eq 'FileHandle' or ref($in) eq 'IO::Handle')
        {
        $inhandle = $in;
        }
        else
        {
        local(*EDIIN);
        open (EDIIN, "< $in") || croak "Cannot open file \"$in\" file for reading: $!";
        $inhandle = *EDIIN;
        $bisinfile = 1;
        }

        binmode($inhandle);
        */

        //(my $eos = $self->{ST}) =~ s/^\\/0/;
        $eos = preg_replace('/^\\/', '0', $this->ST);

        //local $/ = pack("C*", oct($eos));
        $_packed = pack("C*", ord($eos));

        # Looping per-segment for processing
        while ($inhandle)
        {
        if (!$st_check) { $st_check = 1 if m/$self->{ST}/; }
        if (!$des_check) { $des_check = 1 if m/$self->{DES}/; }

        chomp;
        print {$outhandle} $self->_proc_segment($_);
        }
        # This is done to close any open loops
        # XXX Is there a better way to "run on more time"?
        print {$outhandle} $self->_proc_segment('');
        }
        print {$outhandle} '</ascx:message>';

    (close($inhandle) || croak "Cannot close output file \"$out\": $!") if $bisinfile;
    (close($outhandle)|| croak "Cannot close input file \"$in\": $!") if $bisoutfile;

    croak "EDI Parsing Error:  Segment Terminator \"$self->{ST}\" not found" unless $st_check;
    croak "EDI Parsing Error:  Data Element Seperator \"$self->{DES}\" not found" unless $des_check;

    return 1;
    }

        =item string = $obj->convertdata($input)


        This method will transform an EDI data stream, returning wellformed XML.

        my $xmlrpc = new ASCX12();
        my $xml = $xmlrpc->convertdata($binary_edi_data);


        =cut
        sub convertdata
        {
        my ($self, $in) = @_;

        croak "EDI Parsing Error:  Segment Terminator \"$self->{ST}\" not found" unless ($in =~ m/$self->{ST}/);
        croak "EDI Parsing Error:  Data Element Seperator \"$self->{DES}\" not found" unless ($in =~ m/$self->{DES}/);

        my $out = $ASCX12::_XMLHEAD;
        (my $eos = $self->{ST}) =~ s/^\\/0/;
        my @data = split(pack("C*", oct($eos)), $in);
        foreach(@data)
        {
        $out .= $self->_proc_segment($_);
        }
        $out .= $self->_proc_segment('');

        return $out;
        }

        =item string = XMLENC($string)


        Static public method used to encode and return data suitable for ASCII XML CDATA

        $xml_ready_string = ASCX12::XMLENC($raw_data);

        =cut
        sub XMLENC
        {
        my $str = $_[0];
        if ($str)
        {
        $str =~ s/([&<>"])/$_XMLREP{$1}/ge;    # relace any &<>" characters
        $str =~ s/[\x80-\xff]//ge;             # get rid on any non-ASCII characters
        $str =~ s/[\x01-\x1f]//ge;             # get rid on any non-ASCII characters
        }
        return $str;
        }

        =back

        =head2 Private Methods

        =over 4

        =item string = _proc_segment($segment_data);


        This is an internal private method that processes a segment using $LOOPNEST.
        It is called by C<convertfile()> or C<convertdata()> while looping per-segment.

        =cut
        sub _proc_segment
        {
        my ($self, $segment) = @_;
        if (defined	 $ASCX12::Catalogs::IS_CHILD) {
        return $self->_proc_segment_in_child($segment);
        }
        $segment =~ s/\n//g;
        if ($segment =~ m/[0-9A-Za-z]*/)
        {
        my ($segcode, @elements) = split(/$self->{DES}/, $segment);

        # BEGIN load additional segments/elements
        no strict;
        my $filename = './ASCX12/' . $elements[0] . '.pm';
        my $modname = 'ASCX12::' . $elements[0];
        if (-e $filename)
        {
        require $filename;
        $modname->import(qw($temp_SEGMENTS $temp_ELEMENTS));

        # add the temp_SEGMENTS to the $SEGMENTS
        foreach my $key (keys(%$temp_SEGMENTS))
        {
        $SEGMENTS->{$key} = $temp_SEGMENTS->{$key};
        }
        # add the temp_SEGMENTS to the $ELEMENTS
        foreach my $key (keys(%$temp_ELEMENTS))
        {
        $ELEMENTS->{$key} = $temp_ELEMENTS->{$key};
        }
        #            use Data::Dumper;
        #            print Dumper($ELEMENTS);
        #            exit;
        }
        use strict;
        # END load additional segments/elements

        if ($segcode and $segcode eq "ST")
        {
        $self->_unload_catalog();
        $self->load_catalog($elements[0]);
        ## IS_CHILD not defined until after Catalog loaded
        ## Use alternate parsing starting with "ST" segment
        if (defined	 $ASCX12::Catalogs::IS_CHILD) {
        return $self->_proc_segment_in_child($segment);
        }
        }

        # check to see if we need to close a loop
        my $curloop = $ASCX12::Segments::SEGMENTS->{$segcode}[3] if $segcode;
        my $xml = '';
        if (my $tmp = $self->_closeloop($curloop, $self->{lastloop}, $segcode)) { $xml .= $tmp; }
        if (@elements)
        {
        # check to see if we need to open a loop
        if (my $tmp = $self->_openloop($curloop, $self->{lastloop})) { $xml .= $tmp; }

        # now the standard segment (and elements)
        $xml .= '<segm code="'.ASCX12::XMLENC($segcode).'"';
        $xml .= ' desc="'.ASCX12::XMLENC($ASCX12::Segments::SEGMENTS->{$segcode}[0]).'"' if $ASCX12::Segments::SEGMENTS->{$segcode};
        $xml .= '>';

        # make our elements
        $xml .= $self->_proc_element($segcode, @elements);

        # close the segment
        $xml .= '</segm>';

        # keep track
        $self->{lastloop} = $curloop;
        }
        return $xml;
        }
        }

        =item string = _proc_segment_in_child($segment_data);


        This is an internal private method that processes a segment using $IN_CHILD.
        It is called by C<_proc_segment()> when $IN_CHILD is defined.

        =cut
        sub _proc_segment_in_child
        {
        my ($self, $segment) = @_;
        $segment =~ s/\n//g;
        $self->{lastloop} ||= '';
        if ($segment =~ m/[0-9A-Za-z]*/)
        {
        my ($segcode, @elements) = split(/$self->{DES}/, $segment);
        if ($segcode and $segcode eq "ST")
        {

        ## warn "segcode = $segcode\n";
        ## warn Dumper $self, $ASCX12::Catalogs::LOOPNEST,
        ## 	\@_LOOPS, $ASCX12::Catalogs::IS_CHILD;
        }
        elsif ($segcode) {
        ## warn "segcode = $segcode\n";
        }
        else {
        ## warn "no segcode\n";
        ## final loop close
        return $self->_closeloop('', $self->{lastloop}, '');
        }
        my $xml = '';
        my $is_child;
        my $curloop = $_LOOPS[-1];
        until ( defined ($is_child =
        $ASCX12::Catalogs::IS_CHILD->{$curloop}->{$segcode}) ) {
        $xml .= $self->_execclose($curloop);
        warn "WCB close tag: $xml\n";
        warn "segcode: $segcode\n";
        ## warn Dumper \@_LOOPS;
        $curloop = $_LOOPS[-1];
        }
        ## warn "WCB IS_CHILD = $is_child, $curloop, $segcode, $_LOOPS[-1]\n";

        if (@elements)
        {
        # check to see if we need to open a loop
        if ($is_child eq '0') {
        push (@_LOOPS, $segcode);
        ## warn 'WCB open tag: <'.ASCX12::XMLENC($segcode).">\n";
        $xml .= '<'.ASCX12::XMLENC($segcode).'>';
        }

        # now the standard segment (and elements)
        $xml .= '<segm code="'.ASCX12::XMLENC($segcode).'"';
        $xml .= ' desc="'.ASCX12::XMLENC($ASCX12::Segments::SEGMENTS->{$segcode}[0]).'"' if $ASCX12::Segments::SEGMENTS->{$segcode};
        $xml .= '>';

        # make our elements
        $xml .= $self->_proc_element($segcode, @elements);

        # close the segment
        $xml .= '</segm>';

        # keep track
        $self->{lastloop} = $curloop;
        }
        return $xml;
        }
        }

        =item string = _proc_element($segment_code, @elements)


        This is a private method called by C<_proc_segment()>.  Each segment consists of
        elements, this is where they are processed.

        =cut
        sub _proc_element
        {
        my ($self, $segcode, @elements) = @_;
        my $i = 1;
        my $xml = '';
        foreach (@elements)
        {
        if ($_ =~ /[0-9A-Za-z]/)
        {
        my $elename;
        $elename = $segcode.$i if $i >= 10;
        $elename = $segcode.'0'.$i if $i < 10;
        $xml .= '<elem code="'.ASCX12::XMLENC($elename).'"';
        $xml .= ' desc="'.ASCX12::XMLENC($ASCX12::Segments::ELEMENTS->{$elename}[0]).'"' if $ASCX12::Segments::ELEMENTS->{$elename};
        $xml .= '>'.ASCX12::XMLENC($_).'</elem>';
        }
        $i++;
        }
        return $xml;
        }


        =item string = _openloop($loop_to_open, $last_opened_loop)


        This is an internal private method.  It will either open a loop if we can
        or return nothing.

        =cut
        sub _openloop
        {
        my ($self, $newloop, $lastloop) = @_;
        if (ASCX12::_CANHAVE($lastloop, $newloop))
        {
        push (@_LOOPS, $newloop);
        return '<'.ASCX12::XMLENC($newloop).'>';
        }
        return;
        }

        =item void = _closeloop($loop_to_close, $last_opened_loop, $current_segment, $trigger)


        This routine is a private method.  It will recurse to close any open loops.

        =cut
        sub _closeloop
        {
        my ($self, $newloop, $lastloop, $currentseg, $once) = @_;
        $lastloop ||= '';
        $once = 0 unless $once;
        my $xml;
        # Case when there are two consecutive loops
        if ($newloop and $lastloop and $currentseg eq $lastloop and ($currentseg ne ""))
        {
        $xml = $self->_execclose($lastloop);
        return $xml;
        }
        # "Standard Case"
        elsif (ASCX12::_CANHAVE($newloop, $lastloop))
        {
        $xml = $self->_execclose($lastloop);
        return $xml;
        }
        # Recusrively close loops
        else
        {
        my @parent_loops_to_close = ();
        if (@_LOOPS)
        {
        foreach my $testloop (reverse @_LOOPS) #Close in reverse order
        {
        # found a loop, see which ones we ough to close
        if ($testloop eq $newloop)
        {
        if (@parent_loops_to_close)
        {
        foreach my $closeme (@parent_loops_to_close)
        {
        $xml .= $self->_execclose($closeme) if $closeme;
        }
        # See if the current loop ought to be closed
        if ($once != 1)
        {
        if (my $tmp = $self->_closeloop($newloop, $self->{lastloop}, $currentseg, 1))
        {
        $xml .= $tmp;
        }
        }
        return $xml;
        }
        }
        # Push into the loops to close
        else
        {
        if ($testloop) { push (@parent_loops_to_close, $testloop); }
        }
        }
        }
        }
        return;
        }

        =item string = _execclose($loop_to_close)


        Private internal method to actually return the XML that signifies a closed
        loop.  It is called by C<_closeloop()>.

        =cut
        sub _execclose
        {
        my ($self, $loop) = @_;
        return unless $loop;
        if ($loop =~ /[A-Za-z0-9]*/)
        {
        pop @_LOOPS;
        $self->{lastloop} = $_LOOPS[-1];
        return '</'.ASCX12::XMLENC($loop).'>' if ASCX12::XMLENC($loop);
    }
    }

    =item void = _unload_catalog()


    Private method that clears out catalog data and loads standard ASCX12 structure.
    Also initializes ISA and GS data common to all Catalogs.

    =cut
    sub _unload_catalog
    {
    my $self = shift;
    $ASCX12::Catalogs::LOOPNEST = ();
    $ASCX12::Catalogs::IS_CHILD = undef;
    $self->load_catalog(0);
    }

    =item boolean = _CANHAVE($parent_loop, $child_loop)


    This is a private static method.  It uses the rules in the L<ASCX12::Catalogs|ASCX12::Catalogs>
    to determine if a parent is allowed to have the child loop. Returns C<0> or C<1>.

    =cut

    sub _CANHAVE
    {
    my ($parent, $child) = @_;
    if (!$parent) { return 1; } # root-level can have anything
    return 0 unless $child;
    foreach (@{$ASCX12::Catalogs::LOOPNEST->{$parent}}) { if ($_ eq $child) { return 1; } }
    return 0;
    }

    =back

    =head1 TODO

    Here are some things that would make this module even better.  They are in no particular order:

    =over 4

    =item * Error Handling

    Maybe throw in an error object to keep track of things

    =item * Encoding Support

    Anyone that could review to make sure we are using the correct encodings
    We basically read in the EDI file in binary and use the ASCII HEX-equivalent for the
    separators.  Many EDI-producing systems use EBCDIC and not UTF-8 so be careful when
    specifying the values.

    =item * B<Live> Transaction Set (Catalog) Library

        Make a live repository of transaction set data (catalogs).  I'd really like use XML to describe
        each catalog and import them to local dbm files or tied hashes during install and via an update
        script.  This project will be driven if there is adaquate demand.

        According to the ASC X12 website (L<http://www.x12.org>), there are 315 transaction sets.  This module has 4, so
        there are 311 that could be added.

        Documentation for Catalog 175 (Court Notice Transaction Set) is available from
        the US Bankruptcy Courts (L<http://www.ebnuscourts.com/documents/edi.adp>).


        =item * XML Documentation

        Create a DTD and maybe even an XML Schema for the XML output.  There ought to be better
        documentation here.

        =back


        =head1 AUTHORS

        Brian Kaney <F<brian@vermonster.com>>, Jay Powers <F<jpowers@cpan.org>>

        L<http://www.vermonster.com/>

        Copyright (c) 2004 Vermonster LLC.  All rights reserved.

        This library is free software. You can redistribute it and/or modify
        it under the terms of the GNU Lesser General Public License as
        published by the Free Software Foundation; either version 2 of the
        License, or (at your option) any later version.

        Basically you may use this library in commercial or non-commercial applications.
        However, If you make any changes directly to any files in this library, you are
        obligated to submit your modifications back to the authors and/or copyright holder.
        If the modification is suitable, it will be added to the library and released to
        the back to public.  This way we can all benefit from each other's hard work!

        If you have any questions, comments or suggestions please contact the author.

        =head1 SEE ALSO

        L<Carp>, L<ASCX12::Catalogs> and L<ASCX12::Segments>

                    =cut
                    1;

}
