
package ASCX12::856;
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
   # This is Catalog 997 Specific
   #
   # CODE   => [Description, (M)andatory|(O)ptional, MxUse, LoopID, LoopRepeat]
    'ST ' => ['Transaction Set Header','M ',1,'ST',999]
    ,'AK1 ' => ['Functional Group Response Header','M ',1,'ST',999]
    ,'AK2 ' => ['Transaction Set Response Header','O ',1,'ST',999]
    ,'AK3 ' => ['Data Segment Note','O ',1,'ST',999]
    ,'AK4 ' => ['Date Element Note','O ',99,'ST',999]
    ,'AK5 ' => ['Transaction Set Response Trailer','C ',1,'ST',999]
    ,'AK9 ' => ['Functional Group Response Trailer','M ',1,'ST',999]
    ,'SE ' => ['Transaction Set Trailer','M ',1,'ST',999]
   
    

   
};

$temp_ELEMENTS = {
   #
   # This is Catalog 997 Specific
   #
    'ST'    => ['Transaction Set Header','M','ID',1,1]
   ,'ST01'  => ['Transaction Set ID','M','ID',3,3]
   ,'ST02'  => ['Transaction Set Control Number','M','AN',4,9]
   ,'AK1'   => ['Functional Group Response Header','M','ID',1,1]
   ,'AK101' => ['Functional Identifier','M','ID',2,2]
   ,'AK102' => ['Group Control Number','M','N0',1,9]
   ,'AK2'   => ['Transaction Set Response Header','M','ID',1,1]
   ,'AK201' => ['Transaction Set Identifier Code','M','ID',3,3]
   ,'AK202' => ['Transaction Set Control Number','M','AN',4,9]
   ,'AK3'   => ['Data Segment Note','O','ID',1,1]
   ,'AK301' => ['Segment ID Code','M','ID',2,3]
   ,'AK302' => ['Segment Position in Transaction Set','M','N0',1,6]
   ,'AK303' => ['Loop Identifier Code','O','AN',1,6]
   ,'AK304' => ['Segment Syntax Error Code','O','ID',1,3]
   ,'AK4'   => ['Data Element Note','O','AK4',0,99]
   ,'AK401' => ['Element Position in Segment Code','M','N0',1,2]
   ,'AK402' => ['Component Data Element Position in Composite','M','N0',1,2]
   ,'AK403' => ['Date Element Reference Number','O','N0',1,4]
   ,'AK404' => ['Data Element Syntax Error Code','M','ID',1,3]
   ,'AK405' => ['Copy of Bad Data Element','O','AN',1,1]
   ,'AK5'   => ['Transaction Set Response Trailer','C','ID',1,1]
   ,'AK501' => ['Transaction Set Acknowledge Code','M','ID',1,1]
   ,'AK502' => ['Transaction Set Syntax','O','ID',1,3]
   ,'AK503' => ['Transaction Set Syntax','O','ID',1,3]
   ,'AK504' => ['Transaction Set Syntax','O','ID',1,3]
   ,'AK505' => ['Transaction Set Syntax','O','ID',1,3]
   ,'AK506' => ['Transaction Set Syntax','O','ID',1,3]
   ,'AK9'   => ['Functional Group Response Trailer','M','ID',1,1]
   ,'AK901' => ['Functional Group Acknowledge Code','M','ID',1,1]
   ,'AK902' => ['Number of Transaction Sets Included','M','N0',1,6]
   ,'AK903' => ['Number of Received Transactions Sets','M','N0',1,6]
   ,'AK904' => ['Number of Accepted Transaction Sets','M','N0',1,6]
   ,'AK905' => ['Functional Group Syntax Error Code','O','ID',1,3]
   ,'AK906' => ['Functional Group Syntax Error Code','O','ID',1,3]
   ,'AK907' => ['Functional Group Syntax Error Code','O','ID',1,3]
   ,'AK908' => ['Functional Group Syntax Error Code','O','ID',1,3]
   ,'AK909' => ['Functional Group Syntax Error Code','O','ID',1,3]
   ,'SE'    => ['Transaction Set Trailer','M','ID',1,1]
   ,'SE01'  => ['Number of Included Segments','M','N0',1,10]
   ,'SE02'  => ['Transaction Set Control Number','M','AN',4,9]
};


1;
