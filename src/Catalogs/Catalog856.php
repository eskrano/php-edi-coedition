<?php
namespace Coedition\EDI\Catalogs;

final class Catalog856
{
    public $VERSION = 0.1;
    #
    # This is Catalog 856 Specific
    #
    # CODE   => [Description, (M)andatory|(O)ptional, MxUse, LoopID, LoopRepeat]
    public static $temp_SEGMENTS = [
         'BSN' => ['Beginning Segment for Ship Notice', 'M', 1, 'ST', 999],
         'HL' => ['Hierarchical Level', 'M', 1, 'BSN', 999],
         'TD5' => ['Carrier Details (Routing Sequence/Transit Time)', 'O', 1, 'HL', 999],
         'REF' => ['Reference Identification', 'O', 1, 'HL', 999],
         'DTM' => ['Date/Time Reference', 'O', 1, 'HL', 999],
         'N4' => ['Geographic Location', 'O', 1, 'N1', 999],
         'PRF' => ['Purchase Order Reference', 'O', 1, 'HL', 999],
         'LIN' => ['Item Identification', 'O', 1, 'HL', 999],
         'SN1' => ['Item Detail (Shipment)', 'O', 1, 'HL', 999],
         'SAC' => ['Service, Promotion, Allowance', 'O', 1, 'ST', 999],
         'CTT' => ['Transaction Totals', 'M', 1, 'ST', 999],
         'SE' => ['Transaction Set Trailer', 'M', 1, 'ST', 999],
     ];

    #
    # This is Catalog 856 Specific
    #
    public static $temp_ELEMENTS = [
         'ST01' => ['Transaction Set Identifier Code', 'M', 'ID', 3, 3],
         'ST02' => ['Transaction Set Control Number', 'M', 'AN', 4, 9],
         'BSN01' => ['Transaction Set Purpose Code', 'M', 'ID', 2, 2],
         'BSN02' => ['Shipment Identification', 'M', 'AN', 2, 30],
         'BSN03' => ['Date', 'M', 'DT', 8, 8],
         'BSN04' => ['Time', 'M', 'TM', 4, 8],
         'HL01' => ['Hierarchical ID Number', 'M', 'AN', 1, 12],
         'HL03' => ['Hierarchical Level Code', 'M', 'ID', 1, 2],
         'TD502' => ['Identification Code Qualifier', 'X', 'ID', 1, 2],
         'TD503' => ['Identification Code', 'X', 'ID', 2, 80],
         'REF01' => ['Reference Identification Qualifier', 'M', 'ID', 2, 2],
         'REF02' => ['Reference Identification TrackingNumber', 'X', 'AN', 1, 30],
         'DTM01' => ['Date/Time Qualifier', 'M', 'ID', 3, 3],
         'DTM02' => ['Date', 'X', 'DT', 8, 8],
         'N101' => ['Entity Identifier Code', 'M', 'ID', 2, 3],
         'N102' => ['Name', 'X', 'AN', 1, 60],
         'N103' => ['Identification Code Qualifier', 'X', 'ID', 1, 2],
         'N104' => ['Identification Code', 'X', 'AN', 2, 80],
         'N105' => ['Additional Details Type of Entity', 'X', 'AN', 1, 60],
         'N106' => ['Additional Details Type of Entity', 'X', 'AN', 1, 60],
         'N201' => ['Name', 'M', 'AN', 1, 60],
         'N202' => ['Name', 'O', 'AN', 1, 60],
         'N301' => ['Address Information', 'M', 'AN', 1, 55],
         'N302' => ['Address Information', 'O', 'AN', 1, 55],
         'N401' => ['City Name', 'O', 'AN', 2, 30],
         'N402' => ['State or Province Code', 'O', 'ID', 2, 2],
         'N403' => ['Postal Code', 'O', 'ID', 3, 15],
         'N404' => ['Country Code', 'O', 'ID', 2, 3],
         'N405' => ['Additional Location Details', 'O', 'AN', 2, 30],
         'N406' => ['Additional Location Details', 'O', 'AN', 2, 30],
         'PRF01' => ['Purchase Order Number', 'M', 'AN', 1, 22],
         'LIN01' => ['Assigned Identification', 'O', 'AN', 1, 20],
         'LIN02' => ['Product/Service ID Qualifier', 'M', 'ID', 2, 2],
         'LIN03' => ['Product/Service ID', 'M', 'AN', 1, 48],
         'SN102' => ['Number of Units Shipped', 'M', 'R', 1, 10],
         'SN103' => ['Unit or Basis for Measurement Code', 'M', 'ID', 2, 2],
         'SAC01' => ['Allowance or Charge Indicator', 'M', 'ID', 1, 1],
         'SAC02' => ['Service, Promotion, Allowance', 'M', 'ID', 4, 4],
         'SAC05' => ['Amount', 'O', 'N2', 1, 15],
         'CTT01' => ['Number of Line Items', 'M', 'NO', 1, 6],
         'SE01' => ['Number of Included Segments', 'M', 'NO', 1, 10],
         'SE02' => ['Transaction Set Control Number', 'M', 'AN', 4, 9],
     ];
}
