<?php
namespace Coedition\EDI\Catalogs;

final class Catalog855 {
    public $VERSION = 0.1;

    #
    # This is Catalog 855 Specific
    #
    # CODE   => [Description, (M)andatory|(O)ptional, MxUse, LoopID, LoopRepeat]
    public static $temp_SEGMENTS = [
       'ST' => ['Transaction Set Header','M',1,'ST',1],
       'BAK' => ['Beginning Segment for PO Acknowledgment','M',1,'ST',],
       'PO1' => ['Baseline Item Data','M',1,'PO1',999],
       'ACK' => ['Line Item Acknowledgment','O',1,'ACK',104],
       'CTT' => ['Transaction Totals','O',1,'CTT',1],
       'SE' => ['Transaction Set Trailer','M',1,'ST',],
    ];

    #
    # This is Catalog 855 Specific
    #
    # CODE   => [Description, (M)andatory|(O)ptional, Type, Min, Max]
    public static $temp_ELEMENTS = [
        'ST01' => ['Transaction Set Identifier Code', 'M', 'ID', 3, 3],
        'ST02' => ['Transaction Set Control Number', 'M', 'AN', 4, 9],
        'BAK01' => ['Transaction Set Purpose Code', 'M', 'ID', 2, 2],
        'BAK02' => ['Acknowledgment Type', 'M', 'ID', 2, 2],
        'BAK03' => ['Purchase Order Number', 'M', 'AN', 1, 20],
        'BAK04' => ['Date', 'M', 'DT', 8, 8],
        'BAK05' => ['Release Number', 'O', 'AN', 1, 30],
        'BAK06' => ['unknown', 'O', 'AN', 1, 30],
        'BAK07' => ['unknown', 'O', 'AN', 1, 30],
        'BAK08' => ['Seller Purchase Order Number', 'M', 'AN', 1, 22],
        'BAK09' => ['Seller PO acknowledgment Date', 'M', 'DT', 8, 8],

        'PO101' => ['Assigned Identification', 'M', 'NO', 1, 4],
        'PO102' => ['Quantity Ordered', 'M', 'NO', 1, 10],
        'PO103' => ['Unit or Basis for Measurement Code', 'M', 'ID', 2, 2],
        'PO104' => ['Unit Price', 'M', 'N2', 4, 10],
        'PO105' => ['Basis of Unit Price Code', 'O', 'ID', 2, 2],
        'PO106' => ['Product/Service ID Qualifier', 'M', 'ID', 2, 2],
        'PO107' => ['Product/Service ID', 'M', 'AN', 1, 18],
        // below PO1 elements unused but leaving jic...
        'PO108' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2],
        'PO109' => ['Product/Service ID', 'X', 'AN', 1, 48],
        'PO110' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2],
        'PO111' => ['Product/Service ID', 'X', 'AN', 1, 48],
        'PO112' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2],
        'PO113' => ['Product/Service ID', 'X', 'AN', 1, 48],
        'PO114' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2],
        'PO115' => ['Product/Service ID', 'X', 'AN', 1, 48],
        'PO116' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2],
        'PO117' => ['Product/Service ID', 'X', 'AN', 1, 48],
        'PO118' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2],
        'PO119' => ['Product/Service ID', 'X', 'AN', 1, 48],
        'PO120' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2],
        'PO121' => ['Product/Service ID', 'X', 'AN', 1, 48],
        'PO122' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2],
        'PO123' => ['Product/Service ID', 'X', 'AN', 1, 48],
        'PO124' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2],
        'PO125' => ['Product/Service ID', 'X', 'AN', 1, 48],

        'ACK01' => ['Line Item Status Code', 'M', 'ID', 2, 2],
        'ACK02' => ['Quantity', 'M', 'NO', 1, 10],
        'ACK03' => ['Unit or Basis for Measurement Code', 'M', 'ID', 2, 2],
        'ACK04' => ['Date/Time Qualifier', 'O', 'ID', 3, 3],
        'ACK05' => ['Date', 'M', 'DT', 8, 8],
        // below ACK elements unused but leaving jic...
        'ACK06' => ['Unit or Basis for Measurement Code', 'X', 'ID', 2, 2],
        'ACK07' => ['Date/Time Qualifier', 'O', 'ID', 3, 3],
        'ACK08' => ['Date', 'X', 'DT', 8, 8],
        'ACK09' => ['Unit or Basis for Measurement Code', 'X', 'ID', 2, 2],
        'ACK10' => ['Date/Time Qualifier', 'O', 'ID', 3, 3],
        'ACK11' => ['Date', 'X', 'DT', 8, 8],
        'ACK12' => ['Unit or Basis for Measurement Code', 'X', 'ID', 2, 2],
        'ACK13' => ['Date/Time Qualifier', 'O', 'ID', 3, 3],
        'ACK14' => ['Date', 'X', 'DT', 8, 8],
        'ACK15' => ['Unit or Basis for Measurement Code', 'X', 'ID', 2, 2],
        'ACK16' => ['Date/Time Qualifier', 'O', 'ID', 3, 3],
        'ACK17' => ['Date', 'X', 'DT', 8, 8],
        'ACK18' => ['Unit or Basis for Measurement Code', 'X', 'ID', 2, 2],
        'ACK19' => ['Date/Time Qualifier', 'O', 'ID', 3, 3],
        'ACK20' => ['Date', 'X', 'DT', 8, 8],
        'ACK21' => ['Unit or Basis for Measurement Code', 'X', 'ID', 2, 2],
        'ACK22' => ['Date/Time Qualifier', 'O', 'ID', 3, 3],
        'ACK23' => ['Date', 'X', 'DT', 8, 8],
        'ACK24' => ['Unit or Basis for Measurement Code', 'X', 'ID', 2, 2],
        'ACK25' => ['Date/Time Qualifier', 'O', 'ID', 3, 3],
        'ACK26' => ['Date', 'X', 'DT', 8, 8],
        'ACK27' => ['Unit or Basis for Measurement Code', 'X', 'ID', 2, 2],
        'ACK28' => ['Date/Time Qualifier', 'O', 'ID', 3, 3],
        'ACK29' => ['Date', 'X', 'DT', 8, 8],

        'CTT01' => ['Number of Line Items', 'M', 'NO', 1, 6],
        // below CTT elements unused but leaving jic...
        'CTT02' => ['Hash Total', 'O', ' R', 1, 10],
        'CTT03' => ['Weight', 'X', 'R', 1, 10],
        'CTT04' => ['Unit or Basis for Measurement Code', 'X', 'ID', 2, 2],
        'CTT05' => ['Volume', 'X', 'R', 1, 8],
        'CTT06' => ['Unit or Basis for Measurement Code', 'X', 'ID', 2, 2],
        'CTT07' => ['Description', 'O', 'AN', 1, 80],

        'SE01' => ['Number of Included Segments', 'M', 'NO', 1, 10],
        'SE02' => ['Transaction Set Control Number', 'M', 'AN', 4, 9],
    ];
}
