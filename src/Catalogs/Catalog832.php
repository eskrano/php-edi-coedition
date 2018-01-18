<?php
namespace Coedition\EDI\Catalogs;

final class Catalog832 {
    public $VERSION = 0.1;

   #
   # This is Catalog 832 Specific
   #
   #  CODE   => [Description, (M)andatory|(O)ptional, MxUse, LoopID, LoopRepeat]
   # ,'ST'   => ['Transaction Set Header','M',1,'',0]
    public $temp_SEGMENTS = [
        'BCT'  => ['Beginning Segment for Price/Sales Catalog','M',1,'ST',0],
        'DTM'  => ['Date/Time reference','O',10,'ST',0],
        'CUR'  => ['Currency','O',5,'ST',0],
        'LIN'  => ['Item Identification','O',1,'LIN',1],
        'G53'  => ['Maintenance Type','O',1,'LIN',0],
        'DTM'  => ['Date/Time reference','O',10,'LIN',0],
        'REF'  => ['Reference Numbers','O', 1,'LIN', 0],
        'PID'  => ['Product/Item Description','O',200,'LIN',0],
        'PO4'  => ['Item Physical Details','O',1,'LIN',0],
        'CTP'  => ['Pricing Information','O',1,'LIN',100],
        'DTM'  => ['Date/Time reference','O',10,'CTP',],
        'CTT'  => ['Transaction Totals','O',1,'CTT',0]
    ];

    #
    # This is Catalog 832 Specific
    #
    public $temp_ELEMENTS = [
         'ST01' => ['Transaction Set Identifier Code','M','ID',3,3],
         'ST02' => ['Transaction Set Control Number','M','AN',4,9],
         'BCT01' => ['Catalog Purpose Code','M','ID',2,2],
         'BCT02' => ['Catalog Number','O','AN',1,15],
         'BCT10' => ['Transaction Set Purpose Code','O','ID',2,2],
         'DTM01' => ['Date/Time Qualifier','M','ID',3,3],
         'DTM02' => ['Date','X','DT',8,8],
         'DTM03' => ['Time','X','TM',4,8],
         'DTM04' => ['Time Code','O','ID',2,2],
         'CUR01' => ['Entity Identifier Code','M','ID',2,3],
         'CUR02' => ['Currency Code','M','ID',3,3],
         'N101' => ['Entity Identifier Code','M','ID',2,3],
         'N102' => ['Name','X','AN',1,60],
         'N103' => ['Identification Code Qualifier','X','ID',1,2],
         'N104' => ['Identification Code','X','AN',2,80],
         'LIN01' => ['Assigned Identification','O','AN',1,20],
         'LIN02' => ['Product/Service ID Qualifier','M','ID',2,2],
         'LIN03' => ['Product/Service ID ','M','AN',1,48],
         'LIN04' => ['Product/Service ID Qualifier','O','ID',2,2],
         'LIN05' => ['Product/Service ID ','O','AN',1,48],
         'LIN06' => ['Product/Service ID Qualifier','O','ID',2,2],
         'LIN07' => ['Product/Service ID ','O','AN',1,48],
         'LIN08' => ['Product/Service ID Qualifier','O','ID',2,2],
         'LIN09' => ['Product/Service ID','O','AN',1,48],
         'LIN10' => ['Product/Service ID Qualifier','O','ID',2,2],
         'LIN11' => ['Product/Service ID','O','AN',1,48],
         'LIN12' => ['Product/Service ID Qualifier','O','ID',2,2],
         'LIN13' => ['Product/Service ID','O','AN',1,48],
         'LIN14' => ['Product/Service ID Qualifier','O','ID',2,2],
         'LIN15' => ['Product/Service ID','O','AN',1,48],
         'LIN16' => ['Product/Service ID Qualifier','O','ID',2,2],
         'LIN17' => ['Product/Service ID','O','AN',1,48],
         'LIN18' => ['Product/Service ID Qualifier','O','ID',2,2],
         'LIN19' => ['Product/Service ID','O','AN',1,48],
         'LIN20' => ['Product/Service ID Qualifier','O','ID',2,2],
         'LIN21' => ['Product/Service ID','O','AN',1,48],
         'LIN22' => ['Product/Service ID Qualifier','O','ID',2,2],
         'LIN23' => ['Product/Service ID','O','AN',1,48],
         'LIN24' => ['Product/Service ID Qualifier','O','ID',2,2],
         'LIN25' => ['Product/Service ID','O','AN',1,48],
         'LIN26' => ['Product/Service ID Qualifier','O','ID',2,2],
         'LIN27' => ['Product/Service ID','O','AN',1,48],
         'LIN28' => ['Product/Service ID Qualifier','O','ID',2,2],
         'LIN29' => ['Product/Service ID','O','AN',1,48],
         'LIN30' => ['Product/Service ID Qualifier','O','ID',2,2],
         'LIN31' => ['Product/Service ID','O','AN',1,48],
         'G53' => ['Maintenance Type Code','M','ID',3,3],
         'DTM01' => ['Date/Time Qualifier','M','ID',3,3],
         'DTM02' => ['Date','X','DT',8,8],
         'DTM03' => ['Time','X','TM',4,8],
         'DTM04' => ['Time Code','O','ID',2,2],
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
