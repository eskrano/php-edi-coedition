<?php
namespace Coedition\ASCX12\Catalogs;

final class Catalog856
{
    public $VERSION = 0.1;
    #
    # This is Catalog 856 Specific
    #
    # CODE   => [Description, (M)andatory|(O)ptional, MxUse, LoopID, LoopRepeat]
    public $temp_SEGMENTS = [
         'ST' => ['Transaction Set Header', 'M', 1, 'ST', 999],
         'BSN' => ['Beginning Segment for Ship Notice', 'M', 1, 'ST', 999],
  #   ,'HL' => ['Hierarchical Level','M',1,'HL',200000],
         'TD1' => ['Carrier Details (Quantity and Weight)', 'O', 20, 'HL', 999],
         'TD5' => ['Carrier Details (Routing Sequence/Transit Time)', 'O', 12, 'HL', 999],
         'REF' => ['Reference Identification', 'O', 2, 'HL', 999],
         'DTM' => ['Date/Time Reference', 'O', 10, 'HL', 999],
  #   ,'N1' => ['Name','O',1,'N1',200],
  #   ,'N2' => ['Additional Name Information','O',2,'N1',999],
  #   ,'N3' => ['Address Information','O',2,'N1',999],
         'N4' => ['Geographic Location', 'O', 1, 'N1', 999],
         'PRF' => ['Purchase Order Reference', 'O', 1, 'HL', 999],
         'MAN' => ['Marks and Numbers', 'O', 2, 'HL', 999],
         'LIN' => ['Item Identification', 'O', 1, 'HL', 999],
         'SN1' => ['Item Detail (Shipment)', 'O', 1, 'HL', 999],
         'CTT' => ['Transaction Totals', 'O', 1, 'ST', 999],
         'SE' => ['Transaction Set Trailer', 'M', 1, 'ST', 999],
     ];

    #
    # This is Catalog 856 Specific
    #
    public $temp_ELEMENTS = [
         'ST01' => ['Transaction Set Identifier Code', 'M', 'ID', 3, 3],
         'ST02' => ['Transaction Set Control Number', 'M', 'AN', 4, 9],
         'BSN01' => ['Transaction Set Purpose Code', 'M', 'ID', 2, 2],
         'BSN02' => ['Shipment Identification', 'M', 'AN', 2, 30],
         'BSN03' => ['Date', 'M', 'DT', 8, 8],
         'BSN04' => ['Time', 'M', 'TM', 4, 8],
         'BSN05' => ['Hierarchical Structure code', 'O', 'ID', 4, 4],
         'BSN06' => ['Hierarchical Structure code', 'O', 'ID', 4, 4],
         'BSN07' => ['Hierarchical Structure code', 'O', 'ID', 4, 4],
         'HL01' => ['Hierarchical ID Number', 'M', 'AN', 1, 12],
         'HL02' => ['Hierarchical ID Number', 'M', 'AN', 1, 12],
         'HL03' => ['Hierarchical Level Code', 'M', 'ID', 1, 2],
         'HL04' => ['Hierarchical Level Code', 'M', 'ID', 1, 2],
         'TD101' => ['Packaging Code', 'O', 'AN', 3, 5],
         'TD102' => ['Lading Quantity', 'X', 'NO', 1, 7],
         'TD103' => ['Packaging Code', 'O', 'AN', 3, 5],
         'TD104' => ['Lading Quantity', 'O', 'NO', 1, 7],
         'TD105' => ['Unused', 'O', 'AN', 1, 12],
         'TD106' => ['Weight Qualifier', 'O', 'ID', 1, 2],
         'TD107' => ['Weight', 'X', 'R', 1, 10],
         'TD108' => ['Unit or Basis for Measurement Code', 'X', 'ID', 2, 2],
         'TD109' => ['Weight', 'X', 'R', 1, 10],
         'TD110' => ['Unit or Basis for Measurement Code', 'X', 'ID', 2, 2],
         'TD501' => ['Routing Sequence Code', 'O', 'ID', 1, 2],
         'TD502' => ['Identification Code Qualifier', 'X', 'ID', 1, 2],
         'TD503' => ['Identification Code', 'X', 'AN', 2, 80],
         'TD504' => ['Transportation Method/Type Code', 'X', 'ID', 1, 2],
         'TD505' => ['Routing', 'X', 'AN', 1, 35],
         'TD506' => ['Identification Code Qualifier', 'X', 'ID', 1, 2],
         'TD507' => ['Routing', 'X', 'AN', 1, 35],
         'TD508' => ['Identification Code Qualifier', 'X', 'ID', 1, 2],
         'TD509' => ['Routing', 'X', 'AN', 1, 35],
         'TD510' => ['Identification Code Qualifier', 'X', 'ID', 1, 2],
         'TD511' => ['Routing', 'X', 'AN', 1, 35],
         'TD512' => ['Identification Code Qualifier', 'X', 'ID', 1, 2],
         'TD513' => ['Routing', 'X', 'AN', 1, 35],
         'TD514' => ['Identification Code Qualifier', 'X', 'ID', 1, 2],
         'TD515' => ['Country Where Service Performed', 'X', 'AN', 1, 35],
         'REF01' => ['Reference Identification Qualifier', 'M', 'ID', 2, 3],
         'REF02' => ['Reference Identification', 'X', 'AN', 1, 30],
         'REF03' => ['Reference Identification Qualifier', 'M', 'ID', 2, 3],
         'REF04' => ['Reference Identification', 'X', 'AN', 1, 30],
         'DTM01' => ['Date/Time Qualifier', 'M', 'ID', 3, 3],
         'DTM02' => ['Date', 'X', 'DT', 8, 8],
         'DTM03' => ['Time', 'X', 'TM', 4, 8],
         'DTM04' => ['Time Code', 'O', 'ID', 2, 2],
         'DTM05' => ['Time', 'X', 'TM', 4, 8],
         'DTM06' => ['Time Code', 'O', 'ID', 2, 2],
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
         'HL01' => ['Hierarchical ID Number', 'M', 'AN', 1, 12],
         'HL02' => ['Hierarchical Parent ID Number', 'O', 'AN', 1, 12],
         'HL03' => ['Hierarchical Level Code', 'M', 'ID', 1, 2],
         'HL04' => ['Subordinate/Child HL Segments', 'M', 'ID', 1, 2],
         'PRF01' => ['Purchase Order Number', 'M', 'AN', 1, 22],
         'PRF02' => ['Unused', 'O', 'AN', 1, 12],
         'PRF03' => ['Unused', 'O', 'AN', 1, 12],
         'PRF04' => ['Date', 'O', 'DT', 8, 8],
         'PRF05' => ['Unused', 'O', 'AN', 1, 12],
         'PRF06' => ['Unused', 'O', 'AN', 1, 12],
         'PRF07' => ['Purchase Order Type Code', 'O', 'ID', 2, 2],
         'MAN01' => ['Marks and Numbers Qualifier', 'M', 'ID', 1, 2],
         'MAN02' => ['Marks and Number', 'M', 'AN', 1, 48],
         'MAN03' => ['Marks and Number', 'O', 'AN', 1, 48],
         'MAN04' => ['Marks and Number', 'O', 'AN', 1, 48],
         'MAN05' => ['Marks and Number', 'O', 'AN', 1, 48],
         'LIN01' => ['Assigned Identification', 'O', 'AN', 1, 20],
         'LIN02' => ['Product/Service ID Qualifier', 'M', 'ID', 2, 2],
         'LIN03' => ['Product/Service ID', 'M', 'AN', 1, 48],
         'LIN04' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2],
         'LIN05' => ['Product/Service ID', 'X', 'AN', 1, 48],
         'LIN06' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2],
         'LIN07' => ['Product/Service ID', 'X', 'AN', 1, 48],
         'LIN08' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2],
         'LIN09' => ['Product/Service ID', 'X', 'AN', 1, 48],
         'LIN10' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2],
         'LIN11' => ['Product/Service ID', 'X', 'AN', 1, 48],
         'LIN12' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2],
         'LIN13' => ['Product/Service ID', 'X', 'AN', 1, 48],
         'LIN14' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2],
         'LIN15' => ['Product/Service ID', 'X', 'AN', 1, 48],
         'LIN16' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2],
         'LIN17' => ['Product/Service ID', 'X', 'AN', 1, 48],
         'LIN18' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2],
         'LIN19' => ['Product/Service ID', 'X', 'AN', 1, 48],
         'LIN20' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2],
         'LIN21' => ['Product/Service ID', 'X', 'AN', 1, 48],
         'LIN22' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2],
         'LIN23' => ['Product/Service ID', 'X', 'AN', 1, 48],
         'LIN24' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2],
         'LIN25' => ['Product/Service ID', 'X', 'AN', 1, 48],
         'LIN26' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2],
         'LIN27' => ['Product/Service ID', 'X', 'AN', 1, 48],
         'LIN28' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2],
         'LIN29' => ['Product/Service ID', 'X', 'AN', 1, 48],
         'LIN30' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2],
         'LIN31' => ['Product/Service ID', 'X', 'AN', 1, 48],
         'SN101' => ['Assigned Identification', 'O', 'AN', 1, 20],
         'SN102' => ['Number of Units Shipped', 'M', 'R', 1, 10],
         'SN103' => ['Unit or Basis for Measurement Code', 'M', 'ID', 2, 2],
         'SN104' => ['Unused', 'O', 'AN', 1, 20],
         'SN105' => ['Number of Units Shipped', 'M', 'R', 1, 10],
         'SN106' => ['Unit or Basis for Measurement Code', 'M', 'ID', 2, 2],
         'CTT01' => ['Number of Line Items', 'M', 'NO', 1, 6],
         'CTT02' => ['Hash Total', 'O', 'R', 1, 10],
         'CTT03' => ['Number of Line Items', 'O', 'NO', 1, 6],
         'CTT04' => ['Hash Total', 'O', 'R', 1, 10],
         'CTT05' => ['Number of Line Items', 'O', 'NO', 1, 6],
         'CTT06' => ['Hash Total', 'O', 'R', 1, 10],
         'SE01' => ['Number of Included Segments', 'M', 'NO', 1, 10],
         'SE02' => ['Transaction Set Control Number', 'M', 'AN', 4, 9],
     ];
}
