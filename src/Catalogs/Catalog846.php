<?php
namespace Coedition\EDI\Catalogs;

final class Catalog846
{
    public $VERSION = 0.1;
    #
    # This is Catalog 846 Specific
    #
    # CODE   => [Description, (M)andatory|(O)ptional, MxUse, LoopID, LoopRepeat]
    public static $temp_SEGMENTS = [
        'ST' => ['Transaction Set Header', 'M', 1, 'ST', 1],
        'BIA' => ['Beginning Segment for Inventory Inquiry/Advice', 'M', 1, 'ST', 1],
        'LIN' => ['Item Identification', 'M', 1, 'BIA', 999],
        'CTP' => ['Pricing Information', 'O', 1, 'BIA', 999],
        'QTY' => ['Quantity', 'M', 10, 'BIA', 99],
        'CTT' => ['Transaction Totals', 'M', 1, 'CTT', 999],
        'SE' => ['Transaction Set Trailer', 'M', 1, 'SE', 999],
    ];

    public static $temp_ELEMENTS = [
       #
       # This is Catalog 846 Specific
       #
        'ST01' => ['Transaction Set Identifier Code', 'M', 'ID', 3, 3]
       ,'ST02' => ['Transaction Set Control Number', 'M', 'AN', 4, 9]

       ,'BIA01' => ['Transaction Set Purpose Code', 'M', 'ID', 2, 2]
       ,'BIA02' => ['Report Type Code', 'M', 'ID', 2, 2]
       ,'BIA03' => ['Reference Identification', 'M', 'AN', 1, 30]
       ,'BIA04' => ['Date', 'M', 'DT', 8, 8]
       ,'BIA05' => ['Time', 'O', 'TM', 4, 8]

       ,'LIN01' => ['Assigned Identification', 'M', 'AN', 1, 20]
       ,'LIN02' => ['Product/Service ID Qualifier', 'M', 'ID', 2, 2]
       ,'LIN03' => ['Product/Service ID', 'M', 'AN', 1, 18]

       ,'QTY01' => ['Quantity Qualifier', 'M', 'ID', 2, 2]
       ,'QTY02' => ['Quantity', 'M', 'ID', 1, 15]
       ,'QTY03' => ['Quantity', 'O', 'ID', 1, 15]
       //,'QTY04' => ['Quantity', 'X', 'R', 1, 15]

       ,'CTT01' => ['Number of Line Items', 'M', 'NO', 1, 6]

       ,'SE01' => ['Number of Included Segments', 'M', 'NO', 1, 10]
       ,'SE02' => ['Transaction Set Control Number', 'M', 'AN', 4, 9]
    ];
}
