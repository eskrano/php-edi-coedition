<?php
namespace Coedition\EDI\Catalogs;

final class Catalog846
{
    public $VERSION = 0.1;
    #
    # This is Catalog 846 Specific
    #
    # CODE   => [Description, (M)andatory|(O)ptional, MxUse, LoopID, LoopRepeat]
    public $temp_SEGMENTS = [
        'ST' => ['Transaction Set Header', 'M', 1, 'ST', 1],
        'BIA' => ['Beginning Segment for Inventory Inquiry/Advice', 'M', 1, 'ST', 1],
        'DTM' => ['Date/Time Reference', 'O', 10, 'ST', 1],
        'REF' => ['Reference Identification', 'O', 12, 'ST', 1],
        'N1' => ['Name', 'O', 1, 'ST', 5],
        'LIN' => ['Item Identification', 'M', 1, 'N1', 10000],
        'PID' => ['Product/Item Description', 'O', 200, 'LIN', 1],
        'QTY' => ['Quantity', 'O', 1, 'LIN', 99],
        'SCH' => ['Line Item Schedule', 'O', 1, 'LIN', 25],
           #,'PER' => ['Administrative Communications Contact','O',3,'N1',1]
    ];

    public $temp_ELEMENTS = [
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
           ,'DTM01' => ['Date/Time Qualifier', 'M', 'ID', 3, 3]
           ,'DTM02' => ['Date', 'X', 'DT', 8, 8]
           ,'DTM03' => ['Date/Time Qualifier', 'M', 'ID', 3, 3]
           ,'DTM04' => ['Date', 'X', 'DT', 8, 8]
           ,'DTM05' => ['Date/Time Qualifier', 'M', 'ID', 3, 3]
           ,'DTM06' => ['Date', 'X', 'DT', 8, 8]
           ,'REF01' => ['Reference Identification Qualifier', 'M', 'ID', 2, 3]
           ,'REF02' => ['Description', 'X', 'AN', 1, 80]
           ,'REF03' => ['Description', 'X', 'AN', 1, 80]
           ,'REF04' => ['Description', 'X', 'AN', 1, 80]
           ,'PER01' => ['Contact Function Code', ' M', 'ID', 2, 2]
           ,'PER02' => ['Name', 'O', 'AN', 1, 60]
           ,'PER03' => ['Communication Number Qualifier', 'X', 'ID', 2, 2]
           ,'PER04' => ['Communication Number', 'X', 'AN', 1, 80]
           ,'PER05' => ['Communication Number Qualifier', 'X', 'ID', 2, 2]
           ,'PER06' => ['Communication Number', 'X', 'AN', 1, 80]
           ,'PER07' => ['Communication Number Qualifier', 'X', 'ID', 2, 2]
           ,'PER08' => ['Communication Number', 'X', 'AN', 1, 80]
           ,'PER09' => ['Contact Inquiry Reference', 'O', 'AN', 1, 20]
           ,'N101' => ['Entity Identifier Code', 'M', 'ID', 2, 3]
           ,'N102' => ['Name', 'X', 'AN', 1, 60]
           ,'N103' => ['Identification Code Qualifier', 'X', 'ID', 1, 2]
           ,'N104' => ['Identification Code', 'X', 'AN', 2, 80]
           ,'N105' => ['Identification Code', 'X', 'AN', 2, 80]
           ,'N106' => ['Identification Code', 'X', 'AN', 2, 80]
           ,'LIN01' => ['Assigned Identification', 'O', 'AN', 1, 20]
           ,'LIN02' => ['Product/Service ID Qualifier', 'M', 'ID', 2, 2]
           ,'LIN03' => ['Product/Service ID', 'M', 'AN', 1, 48]
           ,'LIN04' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2]
           ,'LIN05' => ['Product/Service ID', 'X', 'AN', 1, 48]
           ,'LIN06' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2]
           ,'LIN07' => ['Product/Service ID', 'X', 'AN', 1, 48]
           ,'LIN08' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2]
           ,'LIN09' => ['Product/Service ID', 'X', 'AN', 1, 48]
           ,'LIN10' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2]
           ,'LIN11' => ['Product/Service ID', 'X', 'AN', 1, 48]
           ,'LIN12' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2]
           ,'LIN13' => ['Product/Service ID', 'X', 'AN', 1, 48]
           ,'LIN14' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2]
           ,'LIN15' => ['Product/Service ID', 'X', 'AN', 1, 48]
           ,'LIN16' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2]
           ,'LIN17' => ['Product/Service ID', 'X', 'AN', 1, 48]
           ,'LIN18' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2]
           ,'LIN19' => ['Product/Service ID', 'X', 'AN', 1, 48]
           ,'LIN20' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2]
           ,'LIN21' => ['Product/Service ID', 'X', 'AN', 1, 48]
           ,'LIN22' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2]
           ,'LIN23' => ['Product/Service ID', 'X', 'AN', 1, 48]
           ,'LIN24' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2]
           ,'LIN25' => ['Product/Service ID', 'X', 'AN', 1, 48]
           ,'LIN26' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2]
           ,'LIN27' => ['Product/Service ID', 'X', 'AN', 1, 48]
           ,'LIN28' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2]
           ,'LIN29' => ['Product/Service ID', 'X', 'AN', 1, 48]
           ,'LIN30' => ['Product/Service ID Qualifier', 'X', 'ID', 2, 2]
           ,'LIN31' => ['Product/Service ID', 'X', 'AN', 1, 48]
           ,'PID01' => ['Item Description Type', 'M', 'ID', 1, 1]
           ,'PID02' => ['Description', 'X', 'AN', 1, 80]
           ,'PID03' => ['Description', 'X', 'AN', 1, 80]
           ,'PID04' => ['Description', 'X', 'AN', 1, 80]
           ,'PID05' => ['Description', 'X', 'AN', 1, 80]
           ,'PID06' => ['Description', 'X', 'AN', 1, 80]
           ,'PID07' => ['Description', 'X', 'AN', 1, 80]
           ,'PID08' => ['Description', 'X', 'AN', 1, 80]
           ,'PID09' => ['Description', 'X', 'AN', 1, 80]
           ,'QTY01' => ['Quantity Qualifier', 'M', 'ID', 2, 2]
           ,'QTY02' => ['Quantity', 'X', 'R', 1, 15]
           ,'QTY03' => ['Quantity', 'X', 'R', 1, 15]
           ,'QTY04' => ['Quantity', 'X', 'R', 1, 15]
           ,'SCH01' => ['Quantity', 'M', 'R', 1, 15]
           ,'SCH02' => ['Unit or Basis for Measurement Code', 'M', 'ID', 2, 2]
           ,'SCH03' => ['Entity Identifier Code', 'O', 'ID', 2, 3]
           ,'SCH04' => ['Name', 'X', 'AN', 1, 60]
           ,'SCH05' => ['Date/Time Qualifier', 'M', 'ID', 3, 3]
           ,'SCH06' => ['Date', 'M', 'DT', 8, 8]
           ,'SCH07' => ['Name', 'O', 'AN', 1, 60]
           ,'SCH08' => ['Date/Time Qualifier', 'O', 'ID', 3, 3]
           ,'SCH09' => ['Date', 'O', 'DT', 8, 8]
           ,'SCH10' => ['Name', 'O', 'AN', 1, 60]
           ,'SCH11' => ['Date/Time Qualifier', 'O', 'ID', 3, 3]
           ,'SCH12' => ['Date', 'O', 'DT', 8, 8]
           ,'CTT01' => ['Number of Line Items', 'M', 'NO', 1, 6]
           ,'CTT02' => ['Number of Line Items', 'X', 'NO', 1, 6]
           ,'CTT03' => ['Number of Line Items', 'X', 'NO', 1, 6]
           ,'CTT04' => ['Number of Line Items', 'X', 'NO', 1, 6]
           ,'CTT05' => ['Number of Line Items', 'X', 'NO', 1, 6]
           ,'CTT06' => ['Number of Line Items', 'X', 'NO', 1, 6]
           ,'SE01' => ['Number of Included Segments', 'M', 'NO', 1, 10]
           ,'SE02' => ['Transaction Set Control Number', 'M', 'AN', 4, 9]
        ];
}
