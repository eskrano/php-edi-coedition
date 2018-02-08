<?php
#
# $Id: Catalogs.pm,v 1.8 2004/08/25 21:49:38 brian.kaney Exp $
#
# ASCX12::Catalogs
#
# Copyright (c) Vermonster LLC <http://www.vermonster.com>
#
# This library is free software; you can redistribute it and/or
# modify it under the terms of the GNU Lesser General Public
# License as published by the Free Software Foundation; either
# version 2.1 of the License, or (at your option) any later version.
#
# This library is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
# Lesser General Public License for more details.
#
# For questions, comments, contributions and/or commercial support
# please contact:
#
#    Vermonster LLC <http://www.vermonster.com>
#    312 Stuart St.  2nd Floor
#    Boston, MA 02116  US
#
# vim: set expandtab tabstop=4 shiftwidth=4
#
#
# NAME
#
# ASCX12::Catalogs - Catalog Looping Rules for ASCX12 EDI Data

/*
DESCRIPTION

This defines how the loops are constructed per catalog.  By catalog
we mean EDI transaction set.

The C<0> catalog is the general relationship for ASCX12.  It say
                                                 that the parent loop C<ISA> can have C<GS> child loops.  Also, C<GS> child
loops can have C<ST> child loops.  This one shouldn't have to change.

To include additional catalogs, use the pattern and enclose in the
conditional structure.


PUBLIC STATIC VARIABLES
    item $LOOPNEST
    This is a reference to an array hash.  The array contains the looping rules on a per-catalog basis.

$IS_CHILD

This is a reference to a hash of hashes. It is not used with all Catalogs.
The hash contains all the the parent (current loop) and child (next segment)
loop rules on a per-catalog basis. Returns one of three possible values:

  Undef - exit current loop (next segment not valid child)
  True (1) - segment is valid child for current loop
  false (0) - segment begins new loop within current loop

The false response corresponds to the $LOOPNEST functionality. But some
Catalogs can create loop patterns that $LOOPNEST alone was unable to
unravel. Leaving $IS_CHILD undefined will default to using just $LOOPNEST.

PUBLIC STATIC METHODS
    void = loadCatalog($catalog_number)

    This is a static public method that loads the C<$LOOPNEST> reference with
    the appropiate catalog relationship data.  It is called by L<XML-ASCX12|ASCX12>.

    To add additional catalogs, follow the same pattern.  If you do add catalogs,
    please submit this file and the Segments.pl to the author(s) so we can make
    this library grow.
*/
namespace Coedition\EDI\Catalogs;

class Catalogs
{
    public $VERSION = 0.1;
	public $ISA;
	public $EXPORT;
	public $LOOPNEST = [];
	public $IS_CHILD = [];
	public $catalog_hash = [
        832 => null,
        846 => null,
        850 => null,
        855 => null,
        856 => null,
        997 => null
	];

	public function loadCatalog($catalog) {
		switch($catalog) {
			case 0:
                #
                # CATALOG 0 - Fake catalog used to load up general ASCX12 relationship
                #
				$this->push(['ISA'], ['GS']);
				$this->push(['GS'], ['ST']);
				break;

            case 110:
                #
                # CATALOG 110 - Airfreight Details & Invoice
                #
                $this->push(['ST'], ['N1','LX','SE','L3']);
                $this->push(['LX'], ['N1','L5']);
                $this->push(['L5'], ['L1']);
        		break;

            case 820:
                #
                # CATALOG 820 - Payment Order / Remittance Advice
                #
                $this->push(['ST'], ['N1','ENT']);
                $this->push(['ENT'], ['NM1','RMR']);
                $this->push(['RMR'], ['REF','ADX']);
        		break;

            case 997:
                #
                # CATALOG 997 - Functional Acknowledgement
                #
				$this->push(['GS'], ['ST']);
                $this->push(['ST'], ['AK1','SE']);
				$this->push(['AK1'],['AK2','AK3','AK4','AK5','AK6','AK7','AK8','AK9']);
                $this->push(['SE'], ['GE']);
				$this->push(['GE'], ['IEA']);
        		break;
            #
            # XXX Add your catalogs here following the pattern.
            # XXX Remember to update ASCX12::Segments as well.
            #
            # XXX Please submit additional catalogs to the authors
            # XXX so they can become part of the library for everyone's
            # XXX benefit!
            #
            case 175:
                #
                # CATALOG 175 - Court Notice
                #
                $this->push(['ST'], ['CDS']);
            	$this->push(['CDS'], ['CED']);
            	$this->push(['CED'], ['LM','NM1']);
            	$this->push(['CTP'],['DTM']);
            	#
            	# Close loop unless next seqment is a legal loop or child
            	# $IS_CHILD->{parent}->{child} = value;
            	$this->IS_CHILD['ISA']['ISA'] = 0;
            	$this->IS_CHILD['ISA']['GS'] = 0;
            	$this->IS_CHILD['ISA']['IEA'] = 1;
            	$this->IS_CHILD['GS']['ST'] = 0;
            	$this->IS_CHILD['GS']['GE'] = 1;
            	$this->IS_CHILD['ST']['BGN'] = 1;
            	$this->IS_CHILD['ST']['SE'] = 1;
            	$this->IS_CHILD['ST']['CDS'] = 0;
            	$this->IS_CHILD['CDS']['LS'] = 1;
            	$this->IS_CHILD['CDS']['LE'] = 1;
            	$this->IS_CHILD['CDS']['CED'] = 0;
            	$this->IS_CHILD['CED']['DTM'] = 1;
            	$this->IS_CHILD['CED']['REF'] = 1;
            	$this->IS_CHILD['CED']['CDS'] = 1;
            	$this->IS_CHILD['CED']['MSG'] = 1;
            	$this->IS_CHILD['CED']['LM'] = 0;
            	$this->IS_CHILD['LM']['LQ'] = 1;
            	$this->IS_CHILD['CED']['NM1'] = 0;
            	$this->IS_CHILD['NM1']['N2'] = 1;
            	$this->IS_CHILD['NM1']['N3'] = 1;
            	$this->IS_CHILD['NM1']['N4'] = 1;
            	$this->IS_CHILD['NM1']['REF'] = 1;
            	$this->IS_CHILD['NM1']['PER'] = 1;
        		break;
            case 832:
                #
                # CATALOG 832
                #
                $this->push(['ST'], ['LIN']);
            	$this->push(['LIN'],['PID', 'PO4', 'CTP']);
        		break;

            case 850:
                #
                # CATALOG 850
                #
                $this->push(['GS'], ['ST']);
            	$this->push(['ST'], ['BEG','REF','TD5','N1']);
            	$this->push(['N1'], ['N2','N3','N4','PO1']);
            	$this->push(['PO1'], ['PID','CTT']);
        		break;

            case 855:
                #
                # CATALOG 855
                #
                $this->push(['GS'], ['ST']);
            	$this->push(['ST'], ['BAK']);
            	$this->push(['BAK'], ['PO1','ACK']);
            	$this->push(['ACK'], ['CTT']);
            	$this->push(['CTT'], ['SE']);
        		break;

            case 846:
                #
                # CATALOG 846
                #
            	$this->push(['ST'], ['BIA','CTT','SE']);
            	$this->push(['BIA'], ['LIN']);
            	$this->push(['LIN'], ['CTP','QTY']);
				$this->push(['SE'], ['GE']);
				$this->push(['GE'], ['IEA']);

                break;
            case 856:
                #
                # CATALOG 856
                #
            	$this->push(['ST'], ['BSN']);
            	$this->push(['BSN'], ['HL','SE']);
				$this->push(['HL'], ['TD5','REF','DTM','PRF','LIN','SN1','CTT']);
				$this->push(['ST'], ['SE']);
				$this->push(['SE'], ['GE']);
				$this->push(['GE'], ['IEA']);
        		break;

            default:
                die("Catalog '$catalog' has not been defined!");
				break;
    	}
	}

	public function hasCatalog($catalog) {
		return isset($this->catalog_hash[$catalog]);
	}

    /*
     * builds the LOOPNEST datastructure adding elements/values
     * does some checking and array initialization
     */
    private function push($segment_array, $new_value) {
        foreach ($segment_array as $segment){
            if(!isset($this->LOOPNEST[$segment])) {
                $this->LOOPNEST[$segment] = [];
                foreach ($new_value as $value){
                    $this->LOOPNEST[$segment][] = $value;
                }
            }
        }
    }
}
