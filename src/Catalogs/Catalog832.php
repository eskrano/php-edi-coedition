<?php
namespace Coedition\EDI\Catalogs;

final class Catalog832 {
    public $VERSION = 0.1;

   #
   # This is Catalog 832 Specific
   #
   #  CODE   => [Description, (M)andatory|(O)ptional, MxUse, LoopID, LoopRepeat]
   # ,'ST'   => ['Transaction Set Header','M',1,'',0]
    public static $temp_SEGMENTS = [
        'ST' => ['Transaction Set Header', 'M', 1, '', 1],
        'BCT'  => ['Beginning Segment','M',1,'ST',0],
        'LIN'  => ['Item Identification','M',1,'ST',1],
        'PID'  => ['Product/Item Description','M',1,'LIN',0],
        'PO4'  => ['Item Physical Details','M',1,'LIN',0],
        'CTP'  => ['Pricing Information','M',1,'LIN',25],
        'CTT'  => ['Transaction Totals','M',1,'',1],
    ];

    #
    # This is Catalog 832 Specific
    #
    public static $temp_ELEMENTS = [
         'ST01' => ['Transaction Set Identifier Code','M','ID',3,3],
         'ST02' => ['Transaction Set Control Number','M','AN',4,9],

         'BCT01' => ['Catalog Purpose Code','M','ID',2,2],
         'BCT02' => ['Catalog Number','O','AN',1,15],
         'BCT09' => ['Catalog Title','O','AN',1,80],
         'BCT10' => ['Transaction Set Purpose Code','O','ID',2,2],

         'LIN01' => ['Assigned Identification','O','AN',1,20],
         'LIN02' => ['Product/Service ID Qualifier','M','ID',2,2],
         'LIN03' => ['Product/Service ID','M','AN',1,48],
         'LIN04' => ['Product/Service ID Qualifier','M','AN',2,2],
         'LIN05' => ['Product/Service ID','M','AN',1,20],


         'G53'   => ['Maintenance Type Code','M','ID',3,3],
         'PID01' => ['Item Description Type','M','ID',1,1],
         'PID02' => ['Product/Process Characteristic Code','O','ID',2,3],
         'PID04' => ['Product Description Code','X','AN',1,12],
         'PID05' => ['Description','X','AN',1,80],
         'PO403' => ['Unit or Basis for Measurement Code','X','ID',2,2],
         'PO404' => ['Packing Code','X','AN',3,5],
         'PO405' => ['Weight Qualifier','O','ID',1,2],
         'PO406' => ['Gross Weight per Pack','X','R',1,9],
         'PO407' => ['Unit or Basis for Measurement Code','X','ID',2,2],
         'CTP02' => ['Price Identifier Code','X','ID',3,3],
         'CTP03' => ['Unit Price','X','R',1,17],
         'CTT01' => ['Number of Line Items','M','NO',1,6],
         'SE01' => ['Number of Included Segments','M','NO',1,10],
         'SE02' => ['Transaction Set Control Number','M','AN',4,9],
    ];

}
