
package ASCX12::850;
use strict;
use Carp qw(croak);
use vars qw(@ISA @EXPORT $VERSION $temp_SEGMENTS $temp_ELEMENTS);

BEGIN
{
    @ISA = ('Exporter');
    @EXPORT = qw($temp_SEGMENTS $temp_ELEMENTS);
    $VERSION = '0.1';
}


$temp_SEGMENTS = {
   #
   # This is Catalog 850 Specific
   #
   # CODE   => [Description, (M)andatory|(O)ptional, MxUse, LoopID, LoopRepeat]
   #,'ST' => ['Transaction Set Header','M',1,'ST']
   'BEG' => ['Beginning Segment for Purchase Order','M',1,'ST',0]
   ,'REF' => ['Reference Identification','O',2,'ST',0]
   ,'PER' => ['Administrative Communications Contact','O',3,'ST',0]
   ,'DTM' => ['Date/Time Reference','O',10,'ST',0]
   ,'TD5' => ['Carrier Details (Routing Sequence/Transit Time)','O',12,'ST',0]
   ,'N9' => ['Reference Identification','O',1,'N9',1]
   ,'MSG' => ['Message Text','O',1000,'N9',0]
   ,'N1' => ['Name','O',1,'N1',1]
   ,'N2' => ['Additional Name Information','O',2,'N1',0]
   ,'N3' => ['Address Information','O',2,'N1',0]
   ,'N4' => ['Geographic Location','O',999,'N1',0]
   ,'PO1' => ['Baseline Item Data','M',1,'100000','PO1',1]
   ,'PID' => ['Product/Item Description','O',1,'1000','PO1',1]
   ,'CTT' => ['Transaction Totals','O',1,'CTT',0]
   #,'SE' => ['Transaction Set Trailer','M',1,''] 
   
};

$temp_ELEMENTS = {
    #
    # This is Catalog 850 Specific
    #
    'ST01' => ['Transaction Set Identifier Code','M','ID',3,3]
    ,'ST02' => ['Transaction Set Control Number','M','AN',4,9]
    ,'BEG01' => ['Transaction Set Purpose Code','M','ID',2,3]
    ,'BEG02' => ['Purchase Order Type Code','M','ID',2,2]
    ,'BEG03' => ['Purchase Order Number','M','AN',1,22]
    ,'BEG04' => ['Duplicate order to be processed','M','ID',2,3]
    ,'BEG05' => ['Date','M','DT',8,8]
    ,'REF01' => ['Reference Identification Qualifier','M','ID',2,3]
    ,'REF02' => ['Reference Identification','X','AN',1,30]
    ,'REF03' => ['Description','X','AN',1,80]
    ,'REF04' => ['Reference Identifier','0','Comp',,]
    ,'REF04-01' => ['Reference Identification Qualifier','M','ID',2,3]
    ,'REF04-02' => ['Reference Identification','M','AN',1,30]
    ,'REF04-03' => ['Reference Identification Qualifier','X','ID',2,3]
    ,'REF04-04' => ['Reference Identification','X','AN',1,30]
    ,'REF04-05' => ['Reference Identification Qualifier','X','ID',2,3]
    ,'REF04-06' => ['Reference Identification','X','AN',1,30]
    ,'PER01' => ['Contact Function Code','M','ID',2,2]
    ,'PER03' => ['Communication Number Qualifier','X','ID',2,2]
    ,'PER04' => ['Communication Number ','X','AN',1,80]
    ,'DTM01' => ['Date/Time Qualifier','M','ID',3,3]
    ,'DTM02' => ['Date','X','DT',8,8]
    ,'DTM03' => ['Date/Time Qualifier','M','ID',3,3]
    ,'DTM04' => ['Date','X','DT',8,8]
    ,'DTM05' => ['Date/Time Qualifier','M','ID',3,3]
    ,'DTM06' => ['Date','X','DT',8,8]
    ,'TD503' => ['Identification Code','X','AN',2,80]
    ,'TD512' => ['Service Level Code','X','ID',2,2]
    ,'N901' => ['Reference Identification Qualifier','M','ID',2,3]
    ,'N902' => ['Reference Identification','X','AN',1,30]
    ,'N903' => ['Reference Identification Qualifier','M','ID',2,3]
    ,'N904' => ['Reference Identification','X','AN',1,30]
    ,'N905' => ['Reference Identification Qualifier','M','ID',2,3]
    ,'N906' => ['Reference Identification','X','AN',1,30]
    ,'N907' => ['Reference Identification Qualifier','M','ID',2,3]
    ,'MSG01' => ['Free-Form Message Text','M','AN',1,264]
    ,'MSG02' => ['Free-Form Message Text','M','AN',1,264]
    ,'MSG03' => ['Number Lines To Advance Pre-Print','M','AN',1,264]
    ,'N101' => ['Entity Identifier Code','M','ID',2,3]
    ,'N102' => ['Name','X','AN',1,60]
    ,'N103' => ['Identification Code Qualifier','X','ID',1,2]
    ,'N104' => ['Identification Code','X','AN',2,80]
    ,'N105' => ['Entity Identifier Code','M','ID',2,3]
    ,'N106' => ['Entity Identifier Code','M','ID',2,3]
    ,'N201' => ['Name','M','AN',1,60]
    ,'N301' => ['Address Information','M','AN',1,55]
    ,'N302' => ['Address Information','O','AN',1,55]
    ,'N401' => ['City Name','O','AN',2,30]
    ,'N402' => ['State or Province Code','O','ID',2,2]
    ,'N403' => ['Postal Code','O','ID',3,15]
    ,'N404' => ['Country Code','O','ID',2,3]
    ,'N405' => ['Extra Location Code','O','ID',2,3]
    ,'N406' => ['Extra Location Code','O','ID',2,3]
    ,'P101' => ['Assigned Identification','O','AN',1,20]
    ,'P102' => ['Quantity Ordered','X','R',1,15]
    ,'P103' => ['Unit or Basis for Measurement Code','O','ID',2,2]
    ,'P104' => ['Unit Price','X','R',1,17]
    ,'P105' => ['Basis of Unit Price Code','O','ID',2,2]
    ,'P106' => ['Product/Service ID Qualifier','X','ID',2,2]
    ,'P107' => ['Product/Service ID','X','AN',1,48]
    ,'P108' => ['Product/Service ID Qualifier','X','ID',2,2]
    ,'P109' => ['Product/Service ID','X','AN',1,48]
    ,'P110' => ['Product/Service ID Qualifier','X','AN',2,2]
    ,'P111' => ['Product/Service ID','X','AN',1,48]
    ,'P112' => ['Product/Service ID Qualifier','X','AN',2,2]
    ,'P113' => ['Product/Service ID','X','AN',1,48]
    ,'P114' => ['Product/Service ID Qualifier','X','AN',2,2]
    ,'P115' => ['Product/Service ID','X','AN',1,48]
    ,'P116' => ['Product/Service ID Qualifier','X','AN',2,2]
    ,'P117' => ['Product/Service ID','X','AN',1,48]
    ,'P118' => ['Product/Service ID Qualifier','X','AN',2,2]
    ,'P119' => ['Product/Service ID','X','AN',1,48]
    ,'P120' => ['Product/Service ID Qualifier','X','AN',2,2]
    ,'P121' => ['Product/Service ID','X','AN',1,48]
    ,'P122' => ['Product/Service ID Qualifier','X','AN',2,2]
    ,'P123' => ['Product/Service ID','X','AN',1,48]
    ,'P124' => ['Product/Service ID Qualifier','X','AN',2,2]
    ,'P125' => ['Product/Service ID','X','AN',1,48]
    ,'PID01' => ['Item Description Type','M','ID',1,1]
    ,'PID02' => ['Item Description Type','M','ID',1,1]
    ,'PID03' => ['Item Description Type','M','ID',1,1]
    ,'PID04' => ['Item Description Type','M','ID',1,1]
    ,'PID05' => ['Description','X','AN',1,80]
    ,'PID06' => ['Description','X','AN',1,80]
    ,'PID07' => ['Description','X','AN',1,80]
    ,'PID08' => ['Description','X','AN',1,80]
    ,'PID09' => ['Description','X','AN',1,80]
    ,'CTT01' => ['Number of Line Items','M','NO',1,6]
    ,'CTT02' => ['Number of Line Items','M','NO',1,6]
    ,'CTT03' => ['Number of Line Items','M','NO',1,6]
    ,'CTT04' => ['Number of Line Items','M','NO',1,6]
    ,'CTT05' => ['Number of Line Items','M','NO',1,6]
    ,'CTT06' => ['Number of Line Items','M','NO',1,6]
    ,'SE01' => ['Number of Included Segments','M','NO',1,10]
    ,'SE02' => ['Transaction Set Control Number','M','AN',4,9] 
};


1;
