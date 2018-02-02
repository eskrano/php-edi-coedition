# PHP EDI

This is a php library for EDI management.

## Testing

```
make test
```

## Documentation

Docs are done via [couscous](http://couscous.io/docs/getting-started.html). So make sure you have it installed globally.

```
make doc-preview
```

## Schedule For EDI Data Exchange
* Inbound  832 - Price/Sales Catalog 
  -A complete load of 832 file is sent daily between 10:00 PM and 11:30 PM EST.
  -No delta/change 832 files are sent.

* Inbound 846 - Inventory Inquiry/Advice
  -A delta/change 846 file at top of the hour
  -A complete load of 846 file is sent daily between 10:00 PM and 11:30 PM EST

* Inbound 855 – Purchase Order Acknowledgement
  -855 is sent within 2 hours of 850 

* Inbound 856 – Advance Shipment Notice
  -856 is sent within 2 hours of post shipment

* Inbound 810 – Invoice 
  -810 is sent within 24 hours of post shipment

* Outbound / Outbound 997 – Functional Acknowledgement
  -A functional acknowledgement will be exchanged for every I/O within 2 hours of transaction 

## EDI Specifications and Id's
### FTP

 Test (FTP)
 
 ISA Qualifier & ID : 12 &  86643873224
 GS ID: 86643873224
 
 Production (FTP)
 
 ISA Qualifier & ID : 12 &  86643873224
 GS ID: 86643873224

  
## EDI Specifications (Outbound-832 - ASC X12 004010) 
### Envelope:

|SegID|Ref. Des.|Elem.|Name|Req.|Type|Max Usage|
|-----|---------|-----|----|----|----|---------|
|**ISA** |||**Interchange Control Header**|**M**||**1**|
||01 |I01  |Authorization Information Qual|M   |ID |2/2|
||02 |I02  |Authorization Information|M|AN|10/10|
| |03 |I03  |Security Information Qualifier|M   |ID |2/2|
| |04 |I04  |Security Information  |M   |AN |10/10|
||05 |I05  |Interchange ID Qualifier|M   |ID |2/2|
||06 |I06  |Interchange Sender ID |M   |AN |15/15|
||07 |I05  |Interchange ID Qualifier|M   |ID |2/2|
||08 |I07  |Interchange Receiver ID   |M   |AN |15/15|
||09 |I08  |Interchange Date|M   |DT |6/6|
||10 |I09  |Interchange Time|M   |TM |4/4|
| |11 |I10  |Interchange Control Standards Id|M   |ID |1/1|
| |12 |I11  |Interchange Control Version Num|M   |ID |5/5|
| |13 |I12  |Interchange Control Number |M   |N0 |9/9|
| |14 |I13  |Acknowledgment Requested|M   |ID |1/1|
| |15 |I14  |Usage Indicator            |M   |ID |1/1|
| |16 |I15  |Component Element Separator|M      ||1/1|
||||||||
|**GS**|||**Functional Group Header**   |**M**  ||**1**|
||01 |479  |Functional Identifier Code            |M   |ID |2/2|
||2|142  |Application Sender's Code            |M   |AN |2/15|
||03 |124  |Application Receiver's Code        |M   |AN |2/15|
||04 |373  |Date                                                |M   |DT |8/8|
||05 |337  |Time                                               |M   |TM |4/8|
||06 |  28   |Group Control Number                 |M   |N0 |1/9|
||07 |455  |Responsible Agency Code            |M   |ID |1/2|
||08 |480  |Version / Release / Industry Id      |M   |AN |1/12|

	
**Heading:**

|SegID|Ref. Des.|Elem.|Name|Req.|Type|Max Usage|
|-----|---------|-----|----|----|----|---------|
|**ST**|||**Transaction Set Header**|**M**||**1**|
||01|143|Transaction Set Identifier Code|M|ID|3/3|
| |02|329|Transaction Set Control Number|M|AN|4/9|
|
|**BCT**|||**Beginning Segment**|**M**||**1**|
||01|683|Catalog Purpose|M|ID|2/2|
||||*SC – Sales Catalog*||||
||02|684|Catalog Number|M|AN|1/15|
||09|352|Catalog Title|O|AN|1/80|
||10|353|Transaction set purpose|O|ID|2/2|
||||*00 – Original – Complete*||||

				
**Detail:**

|SegID|Ref. Des.|Elem.|Name|Req.|Type|Max Usage|
|-----|---------|-----|----|----|----|---------|
|**LIN**  |         |     |**Line Item Description**|**M**||**1**|
|     |01       |350  |Assigned Identification|M|AN|1/20|
||02|235|Product/Service ID Qualifier|M|ID|2|
||03|234|Product Service ID||||
||||*VP - Vendor’s (Seller’s) Part Number*|*M*|*AN*|*1/18*|
||||*MG - Manufacturer’s Part Number*|*M*|*AN*|*1/20*|
||||*UP - UPC Consumer Package Code*|*M*|*AN*|*1/20  No dashes*|
|**PID**|||**Product/Item Description**|**M**|**1**||
||01|349|Item Description Type|M|ID|1/1
||||*F – Free Form*
||05|352|Description|M|AN|1/40
|**PO4**|||**Item Physical Details**|**M**|**1**
||03|355|Unit or Basis for Measurement |M|AN|2/2
||||*EA - each*||||
||06|384|Gross Weight (in lbs)|O|N2|4/9   (6.2)
||10| 82|Length (in inches)|O|N2|4/8   (5.2)
||11|189|Width (in inches)|O|N2|4/8   (5.2)
||12| 65|Height (in inches)|O|N2|4/8   (6.2)
|**CTP**|||**Pricing Information**|**M**|**1**|**25**||
||01|687|Class of Trade Code |M|ID|2/2 
||||*DI - Distributor*
||02|236|Price Identifier Code|M|ID|3/3
||||*‘CON’,’MSR’,’MNC’*||||
||03|212|Price|M|N2|1/10 (8.2)

**Summary:**

|SegID|Ref. Des.|Elem.|Name|Req.|Type|Max Usage|
|----|----|----|----|----|----|----|
|**CTT**|||**Transaction Totals**|**M**        ||**1**|
||01 |354  |Number of Line Items                   |M   |N0 |1/6|
|**SE**|||**Transaction Set Trailer**|**M**||**1**|
||1|  96   |Number of Included Segments|M   |N0 |1/10|
||02 |329  |Transaction Set Control Number|M   |AN |4/9|





**Envelope:**
	
|SegID|Ref. Des.|Elem.|Name|Req.|Type|Max Usage|
|----|----|----|----|----|----|----|
|**GE**|||**Functional Group Trailer**|**M** |       |1||
||01 | 97   |Number of Transaction Sets Incl|M   |N0 |1/6|
||02 | 28   |Group Control Number                  |M   |N0 |1/9|
|**IEA**|||**Interchange Control Trailer**|**M**||**1**|
||01 |I16  |Num of Included Functional Grps|M   |N0 |1/5|
||02 |I12  |Interchange Control Number        |M   |N0 |9/9|




**EDI-832 Sample data**

```
ISA~00~          ~00~          ~ZZ~PEACHDIRECT     ~ZZ~PARTNER          ~000613~0046~X~00401~000000141~0~T~
GS~SC~PEACHDIRECT~PARTNER~20080211~0046~146~X~004010
ST~832~0031
BCT~SC~1470279260~~~~~~~~00
LIN~2~VP~ATSATC15~MF~ATC-15-50
PID~F~~~~AMERICAN TERMINAL 15 AMP FUSE
PO4~~~EA~~~0001
CTP~DI~CON~9.99
LIN~3~VP~PET10-6003~MF~SEI#200-990
PID~F~~~~~PLASTIC COMPASS
PO4~~~EA~~~0001
CTP~DI~CON~0.79
CTT^1562
SE^12^0001
GE^1^361
IEA^1^169200002
```

###EDI Specifications (Outbound-846 - ASC X12 004010)
	
**Envelope:**

|SegID|Ref. Des.|Elem.|Name|Req.|Type|Max Usage|
|----|----|----|----|----|----|----|
|**ISA** |||**Interchange Control Header**|**M**||**1**|
||01 |I01  |Authorization Information Qual|M   |ID |2/2|
||02 |I02  |Authorization Information|M|AN|10/10|
| |03 |I03  |Security Information Qualifier|M   |ID |2/2|
| |04 |I04  |Security Information  |M   |AN |10/10|
||05 |I05  |Interchange ID Qualifier|M   |ID |2/2|
||06 |I06  |Interchange Sender ID |M   |AN |15/15|
||07 |I05  |Interchange ID Qualifier|M   |ID |2/2|
||08 |I07  |Interchange Receiver ID   |M   |AN |15/15|
||09 |I08  |Interchange Date|M   |DT |6/6|
||10 |I09  |Interchange Time|M   |TM |4/4|
| |11 |I10  |Interchange Control Standards Id|M   |ID |1/1|
| |12 |I11  |Interchange Control Version Num|M   |ID |5/5|
| |13 |I12  |Interchange Control Number |M   |N0 |9/9|
| |14 |I13  |Acknowledgment Requested|M   |ID |1/1|
| |15 |I14  |Usage Indicator            |M   |ID |1/1|
| |16 |I15  |Component Element Separator|M      ||1/1|
|**GS** |||**Functional Group Header**            |**M**       ||**1**|
||01 |479  |Functional Identifier Code            |M   |ID |2/2|
||2|142  |Application Sender's Code            |M   |AN |2/15|
||03 |124  |Application Receiver's Code        |M   |AN |2/15|
||04 |373  |Date                                                |M   |DT |8/8|
||05 |337  |Time                                               |M   |TM |4/8|
||06 |  28   |Group Control Number                 |M   |N0 |1/9|
||07 |455  |Responsible Agency Code            |M   |ID |1/2|
||08 |480  |Version / Release / Industry Id      |M   |AN |1/12|



	
**Heading:**

|SegID|Ref. Des.|Elem.|Name|Req.|Type|Max Usage|
|----|----|----|----|----|----|----|
|**ST**|||**Transaction Set Header**|**M**||**1**|
||1|143|Transaction Set Identifier Code|M|ID|3/3|
| |2|329|Transaction Set Control Number|M|AN|4/9|
|**BIA**|||**Beg. Segment for Inv. Inquiry**|**M**||**1**|
|    |01 |353  |Transaction Set Purpose Code       |M   |ID |2/2|
||||*00 – Original*||||
||||*25 - Incremental*||||
|02 |755  |Report Type Code                       |M   |ID |2/2||
||||*DD – Distributor Inventory Report*||||
||03 |127  |Reference Identification               |M   |AN |1/30|
| |04 |373  |Date                                   |M   |DT |8/8|

      	 	
**Detail:**

|SegID|Ref. Des.|Elem.|Name|Req.|Type|Max Usage|
|----|----|----|----|----|----|----|
|**LIN**|||**Line Item Description**|**M**||**1**|
||01 |350  |Assigned Identification|M   |AN |1/20|
||2|235|Product/Service ID Qualifier|M|ID|2|
||3|234|Product Service ID||||
||||*VP - Vendors Part Number*|*M*|*AN*|*1/18*|
|**CTP**|||**Pricing Information**|**M**||**1**|
||01 |687  |Class of Trade Code|M   |ID |2/2|
||2|236|Price Identifier (CON or MSR)|M|ID|3/3|
||3|212|Unit Price|M|R|1/17|
||||*CTP is Optional if you are sending an 832, if no 832, CTP is mandatory*||||
|**QTY**|||**Quantity**|**M**||**10**|
||01 |673  |Quantity Qualifier |M  |ID |2|
||||**33 – Quantity Available for sale**||||
||02 |380   |Quantity         |M   |ID |1/15|
||03 |C001   |Composite Unit of Measure                   |O  |||
|||  |**EA – Each**||||


**Summary:**

|SegID|Ref. Des.|Elem.|Name|Req.|Type|Max Usage|
|----|----|----|----|----|----|----|
|**CTT** |||**Transaction Totals**|**M** ||**1**|
||01 |354  |Number of Line Items                   |M   |N0 |1/6|
|**SE** |||**Transaction Set Trailer**|**M**       ||**1**            |
||1|  96   |Number of Included Segments|M   |N0 |1/10|
||02 |329  |Transaction Set Control Number|M   |AN |4/9|

	
**Envelope:**

|SegID|Ref. Des.|Elem.|Name|Req.|Type|Max Usage|
|----|----|----|----|----|----|----|
|**GE**|||**Functional Group Trailer** | **M**||**1**||
||01 | 97   |Number of Transaction Sets Incl|M   |N0 |1/6|
||02 | 28   |Group Control Number                  |M   |N0 |1/9|
|**IEA**|||**Interchange Control Trailer** |**M** ||**1**|
||01 |I16  |Num of Included Functional Grps|M   |N0 |1/5|
||02 |I12  |Interchange Control Number        |M   |N0 |9/9|




**EDI-846 Sample data**
```
ISA~00~          ~00~          ~ZZ~PEACHDIRECT       ~ZZ~PARTNER          ~000301~2106~U~00401~169600037~1~T~|
GS~IB~PEACHDIRECT~PARTNER~20000301~2106~39~X~004010
ST~846~0001
BIA~00~DD~0001~20080211
LIN~1~VP~PMXMLLT1
CTP~DI~CON~0.79
CTP~DI~MSR~1.79
QTY~33~7~EA
CTT~1
SE~06~0001
GE~1~39
IEA~1~169600037
```	

###EDI Specifications (Inbound-850 - ASC X12 004010)
**Envelope:**

|SegID|Ref. Des.|Elem.|Name|Req.|Type|Max Usage|
|----|----|----|----|----|----|----|
|**ISA** |||**Interchange Control Header**|**M**||**1**|
||01 |I01  |Authorization Information Qual|M   |ID |2/2|
||02 |I02  |Authorization Information|M|AN|10/10|
| |03 |I03  |Security Information Qualifier|M   |ID |2/2|
| |04 |I04  |Security Information  |M   |AN |10/10|
||05 |I05  |Interchange ID Qualifier|M   |ID |2/2|
||06 |I06  |Interchange Sender ID |M   |AN |15/15|
||07 |I05  |Interchange ID Qualifier|M   |ID |2/2|
||08 |I07  |Interchange Receiver ID   |M   |AN |15/15|
||09 |I08  |Interchange Date|M   |DT |6/6|
||10 |I09  |Interchange Time|M   |TM |4/4|
| |11 |I10  |Interchange Control Standards Id|M   |ID |1/1|
| |12 |I11  |Interchange Control Version Num|M   |ID |5/5|
| |13 |I12  |Interchange Control Number |M   |N0 |9/9|
| |14 |I13  |Acknowledgment Requested|M   |ID |1/1|
| |15 |I14  |Usage Indicator            |M   |ID |1/1|
| |16 |I15  |Component Element Separator|M      ||1/1|
|**GS** |||**Functional Group Header**|**M** ||**1**|
||01 |479  |Functional Identifier Code            |M   |ID |2/2|
||2|142  |Application Sender's Code            |M   |AN |2/15|
||03 |124  |Application Receiver's Code        |M   |AN |2/15|
||04 |373  |Date                                                |M   |DT |8/8|
||05 |337  |Time                                               |M   |TM |4/8|
||06 |  28   |Group Control Number                 |M   |N0 |1/9|
||07 |455  |Responsible Agency Code            |M   |ID |1/2|
||08 |480  |Version / Release / Industry Id      |M   |AN |1/12|

	
**Heading:**

|SegID|Ref. Des.|Elem.|Name|Req.|Type|Max Usage|
|----|----|----|----|----|----|----|
|**ST**|||**Transaction Set Header**|**M**||**1**|
||1|143|Transaction Set Identifier Code|M|ID|3/3|
| |2|329|Transaction Set Control Number|M|AN|4/9|
|**BEG**|||**Beginning Segment for PO**|**M**||**1**|
||01 |353|Transaction Set Purpose Code|M|ID|2/2|
||||*00 - Original*||||
||02 |92|Purchase Order Type Code|M|ID |2/2|
||||*DS - Dropship*||||
||03 |324|Purchase Order Number|M|AN |1/20|
||05 |373|Date|M|DT |8/8|
|**REF**|||**Reference Identification**|**O**||**5**|
||1|128|Reference Identification Qualifier|M|ID|2/3|
||2|127|Reference Identification||||
||||*ZZ – Mutually Defined*|*O*|*AN*|*1/30*|
|**TD5**|||**Carrier Details (Routing Seq)**|**O**||**1**|
||1|133|Routing Sequence Code|M|ID|1/2|
||2|66|Identification Code Qualifier|M|ID|1/2 |
||3|67|Identification Code|M|AN|1/4|
|**N1**|||**Name**|**M**||**1**|
||1|98|Entity Identifier Code|M|ID|2/3    |
||||*ST - Ship To*||||
||2|93|Company Name|M|AN|1/36|
|**N2**|||**Additional Name Information**|**O**||**2**|
||1|93|Name|O|AN|1/36|
||2|93|Address (REF2)|O|AN|1/36|
|**N3**|||**Address Information**|**M**||**2**|
||1|166|Street Address |M|AN|1/36|
||2|166|Addl. Address (REF1)|O|AN|1/36|
|**N4**|||**Geographic Location**|**M**||**1**|
||1|19|City Name|M|AN|2/20|
||2|156|State or Province Code|M|ID|2/2|
||3|116|Postal Code|M|ID|5/10|
|**PER**|||**Administrative Com Contact**|**O**||**1**|
||1|366|Contact Function Code|M|ID|2/2 |
||||*IC – Information Contact*||||
||2|93|Name|M|AN|1/36|


**Detail:**

|SegID|Ref. Des.|Elem.|Name|Req.|Type|Max Usage|
|----|----|----|----|----|----|----|
|PO1|||Baseline Item Data|M||1|
||1|350|Assigned Identification|M|N0|1/4|
||2|330|Quantity Ordered|M|N0|1/10|
||3|355|Unit or Basis for Measurement |M|ID|2/2 |
||||*EA - Each*||||
||4|212|Unit Price|M|N2|4/10   (8.2)|
||6|235|Product/Service ID Qualifier|M|ID|2/2|
||7|234|Product/Service ID||||
||||*BP - Creative Computers Part Number*|*M*|*AN*|*1/20*|
||||*VP - Vendors Part Number*|*M*|*AN*|*1/18*|

				
**Summary:**

|SegID|Ref. Des.|Elem.|Name|Req.|Type|Max Usage|
|----|----|----|----|----|----|----|
|**CTT**|||**Transaction Totals**|**M**||**1**|
||01 |354  |Number of Line Items                   |M   |N0 |1/6|
||||||||
|**SE**|||**Transaction Set Trailer**|**M**||**1**|
||1|  96   |Number of Included Segments|M   |N0 |1/10|
||02 |329  |Transaction Set Control Number|M   |AN |4/9|

	
**Envelope:**

|SegID|Ref. Des.|Elem.|Name|Req.|Type|Max Usage|
|----|----|----|----|----|----|----|
|**GE**|||**Functional Group Trailer**|**M**||**1**|
||01 | 97   |Number of Transaction Sets Incl|M   |N0 |1/6|
||02 | 28   |Group Control Number                  |M   |N0 |1/9|
|**IEA**|||**Interchange Control Trailer**|**M**||**1**|
||01 |I16  |Num of Included Functional Grps|M   |N0 |1/5|
||02 |I12  |Interchange Control Number        |M   |N0 |9/9|




**EDI-850 Sample data**
```
ISA~00~          ~00~          ~ZZ~PARTNER     ~ZZ~PEACHDIRECT ~000301~0936~U~00401~000000055~0~T~~
GS~PO~PARTNER~PEACHDIRECT~20000301~0936~55~X~004010
ST~850~550001
BEG~00~DS~P51276290001~~20000301
REF~ZZ~MUTUALLY DEFINED
TD5~1~01~UG
N1~ST~STEVE HATFIELD
N3~126 MELBY DRIVE
N4~BRAINERD~MN~56401
PER~IC~2188294767
PO1~0001~2~EA~0.79~~VP~PET10-6003~BP~COMPASS
CTT~1
SE~11~550001
GE~2~55
IEA~1~000000055
```

###EDI Specifications (Outbound-855 - ASC X12 004010)
**Envelope:**

|SegID|Ref. Des.|Elem.|Name|Req.|Type|Max Usage|
|----|----|----|----|----|----|----|
|**ISA**|||**Interchange Control Header**|**M**||**1**|
||01 |I01  |Authorization Information Qual|M   |ID |2/2|
||02 |I02  |Authorization Information|M|AN|10/10|
| |03 |I03  |Security Information Qualifier|M   |ID |2/2|
| |04 |I04  |Security Information  |M   |AN |10/10|
||05 |I05  |Interchange ID Qualifier|M   |ID |2/2|
||06 |I06  |Interchange Sender ID |M   |AN |15/15|
||07 |I05  |Interchange ID Qualifier|M   |ID |2/2|
||08 |I07  |Interchange Receiver ID   |M   |AN |15/15|
||09 |I08  |Interchange Date|M   |DT |6/6|
||10 |I09  |Interchange Time|M   |TM |4/4|
| |11 |I10  |Interchange Control Standards Id|M   |ID |1/1|
| |12 |I11  |Interchange Control Version Num|M   |ID |5/5|
| |13 |I12  |Interchange Control Number |M   |N0 |9/9|
| |14 |I13  |Acknowledgment Requested|M   |ID |1/1|
| |15 |I14  |Usage Indicator            |M   |ID |1/1|
| |16 |I15  |Component Element Separator|M      ||1/1|
|**GS**|||**Functional Group Header**|**M**||**1**|
||01 |479  |Functional Identifier Code            |M   |ID |2/2|
||2|142  |Application Sender's Code            |M   |AN |2/15|
||03 |124  |Application Receiver's Code        |M   |AN |2/15|
||04 |373  |Date                                                |M   |DT |8/8|
||05 |337  |Time                                               |M   |TM |4/8|
||06 |  28   |Group Control Number                 |M   |N0 |1/9|
||07 |455  |Responsible Agency Code            |M   |ID |1/2|
||08 |480  |Version / Release / Industry Id      |M   |AN |1/12|

	
**Heading:**

|SegID|Ref. Des.|Elem.|Name|Req.|Type|Max Usage|
|----|----|----|----|----|----|----|
|**ST**|||**Transaction Set Header**|**M**||**1**|
||1|143|Transaction Set Identifier Code|M|ID|3/3|
||2|329|Transaction Set Control Number|M|AN|4/9|
|**BAK**|||**Begin Segment for PO Acknowledge**|**M**||**1**|
||01 |353  |Transaction Set Purpose Code |M   |ID |2/2|
||||00 – Original||||
||02 |587  |Acknowledgment Type          |M   |ID |2/2|
|||*AC       Acknowledge - With Detail and Change*|||||
|||*AD       Acknowledge - With Detail, No Change*|||||
|||*AK       Acknowledge - No Detail or Change*|||||
|||*AT       Accepted*|||||
|||*RD       Reject with Detail*|||||
|||*RJ       Rejected - No Detail*|||||
||03 |324  |Purchase Order Number        |M   |AN |1/20|
||4|373|Date|M|DT|8/8|


**Detail:**

|SegID|Ref. Des.|Elem.|Name|Req.|Type|Max Usage|
|----|----|----|----|----|----|----|
|**PO1**|||**Baseline Item Data**|**M**|**1**||
||01 |350  |Assigned Identification                |M   |N0|1/4|
||02 |330  |Quantity Ordered                  |M   |N0  |1/10|
||03 |355  |Unit or Basis for Measurement |M   |ID |2/2|
||||*EA - each*||||
||04 |212  |Unit Price  |M   |N2  |4/10 (8.2)  |
||06 |235  |Product/Service ID Qualifier    |M   |ID |2/2|
||7|234|Product/Service ID||||
||||*VP - Vendors Part Number*|*M*|*AN*|*1/18*|
|**ACK**|||**Line Item Acknowledgment**|**M**|**1**||
||01 |668  |Line Item Status Code                 |M   |ID |2/2|
|||***|Allowable Acceptance Codes||||
||||*AC – Item Accepted and Shipped*||||
||||*IA – Item Accepted*||||
|||***|Allowable Reject Codes||||
||||*IR – Item Rejected*||||
||02 |380  |Quantity                                       |M   |N0|1/10|
||03 |355  |Unit or Basis for Measurement |M   |ID |2/2|
||||*EA - each*||||
||05 |373  |Date                                               |M   |DT |8/8|


	

**Summary:**

|SegID|Ref. Des.|Elem.|Name|Req.|Type|Max Usage|
|----|----|----|----|----|----|----|
|**CTT**|||**Transaction Totals**|**M**||**1**|
||01 |354  |Number of Line Items                   |M   |N0 |1/6|
|**SE**|||**Transaction Set Trailer**|**M**||**1**|
||1|  96   |Number of Included Segments|M   |N0 |1/10|
||02 |329  |Transaction Set Control Number|M   |AN |4/9|


**Envelope:**

|SegID|Ref. Des.|Elem.|Name|Req.|Type|Max Usage|
|----|----|----|----|----|----|----|
|**GE**|||**Functional Group Trailer**|**M**||**1**||
||01 | 97   |Number of Transaction Sets Incl|M   |N0 |1/6|
||02 | 28   |Group Control Number                  |M   |N0 |1/9|
|**IEA**|||**Interchange Control Trailer**|**M**||**1**|
||01 |I16  |Num of Included Functional Grps|M   |N0 |1/5|
||02 |I12  |Interchange Control Number        |M   |N0 |9/9|




**EDI-855 Sample data**

```
ISA~00~          ~00~          ~ZZ~PEACHDIRECT ~ZZ~PEACHDIRECT          ~000218~0726~U~00401~000001117~0~P~>
GS~PR~xxxxxxxx~PEACHDIRECT~20000218~0726~1117~X~004010
ST~855~1493
BAK~00~AD~CUSTPO~20000218
PO1~0001~3~EA~0.79~~VP~PET10-6003
ACK~IA~3~EA~~20000218
CTT~1
SE~6~1493
GE~1~1117
IEA~1~000001117
```

###EDI Specifications (Outbound-856 - ASC X12 004010)
**Envelope:**

|SegID|Ref. Des.|Elem.|Name|Req.|Type|Max Usage|
|----|----|----|----|----|----|----|
|**ISA**|||**Interchange Control Header**|**M**||**1**|
||01 |I01  |Authorization Information Qual|M   |ID |2/2|
||02 |I02  |Authorization Information|M|AN|10/10|
| |03 |I03  |Security Information Qualifier|M   |ID |2/2|
| |04 |I04  |Security Information  |M   |AN |10/10|
||05 |I05  |Interchange ID Qualifier|M   |ID |2/2|
||06 |I06  |Interchange Sender ID |M   |AN |15/15|
||07 |I05  |Interchange ID Qualifier|M   |ID |2/2|
||08 |I07  |Interchange Receiver ID   |M   |AN |15/15|
||09 |I08  |Interchange Date|M   |DT |6/6|
||10 |I09  |Interchange Time|M   |TM |4/4|
| |11 |I10  |Interchange Control Standards Id|M   |ID |1/1|
| |12 |I11  |Interchange Control Version Num|M   |ID |5/5|
| |13 |I12  |Interchange Control Number |M   |N0 |9/9|
| |14 |I13  |Acknowledgment Requested|M   |ID |1/1|
| |15 |I14  |Usage Indicator            |M   |ID |1/1|
| |16 |I15  |Component Element Separator|M      ||1/1|
|**GS**|||**Functional Group Header**|**M**||1|
||01 |479  |Functional Identifier Code            |M   |ID |2/2|
||2|142  |Application Sender's Code            |M   |AN |2/15|
||03 |124  |Application Receiver's Code        |M   |AN |2/15|
||04 |373  |Date                                                |M   |DT |8/8|
||05 |337  |Time                                               |M   |TM |4/8|
||06 |28   |Group Control Number                 |M   |N0 |1/9|
||07 |455  |Responsible Agency Code            |M   |ID |1/2|
||08 |480  |Version / Release / Industry Id      |M   |AN |1/12|


**Heading:**

|SegID|Ref. Des.|Elem.|Name|Req.|Type|Max Usage|
|----|----|----|----|----|----|----|
|**ST**|||**Transaction Set Header**|**M**||**1**|
||1|143|Transaction Set Identifier Code|M|ID|3/3|
| |2|329|Transaction Set Control Number|M|AN|4/9|
|**BSN**|||**Beginning Segment for Ship Notice**|**M**||**1**|
||1|353|Transaction Set Purpose Code|M|ID|2/2|
||2|396|Shipment Identification|M|AN|2/30|
||3|373|Date|M|DT|8/8|
||4|337|Time|M|TM|4/8    |


**Detail:**

|SegID|Ref. Des.|Elem.|Name|Req.|Type|Max Usage|
|----|----|----|----|----|----|----|
|**HL**|||**Hierarchical Level**|**M**|**1**||
||1|628|Hierarchical ID Number|M|AN|1/12|
||3|735|Hierarchical Level Code|M|ID|1/2|
||||*S - Shipped*||||
|**TD5**|||**Carrier Details (Routing Seq/Time)**|**O**||**1**|
||2|66|Identification Code Qualifier|C|ID|1/2|
||3|67|Shipping Method Code|C|ID|2/80|
|**REF**|||**Reference Identification**|**O**||**1**|
||1|128|Reference Identification Qualifier|M|ID|2/2|
||||*SI – Shipper’s Identifying Number*||||
||2|127|Reference Identification (Tracking #)|C|AN|1/30|
|**DTM**|||**Date/Time Qualifier**|**O**||**1**|
||1|374|Date/Time Qualifier|M|ID|3/3|
||||*011 - Shipped*||||
||2|373|Date|C|DT|8/8|
|**HL**|||**Hierarchical Level**|**M**|**1**||
||1|628|Hierarchical ID Number|M|AN|1/12|
||3|735|Hierarchical Level Code|M|ID|1/2|
||||*O – Order Level*||||
|**PRF**|||**Purchase Order Reference**|**O**||**1**|
||1|324|Purchase Order Number|M|AN|1/22|
|**HL**|||**Hierarchical Level**|**M**|**1**||
||1|628|Hierarchical ID Number|M|AN|1/12|
||3|735|Hierarchical Level Code|M|ID|1/2|
||||*I – Item Level*||||
|**LIN**|||**Line Identification**|**O**||**1**|
||1|350|Assigned Identification|O|AN|1/20|
||2|235|Product/Service ID Qualifier|M|ID|2/2|
||||*VP – Vendor’s(Seller’s) Part Number*||||
||3|234|Product/Service ID|M|AN|1/48|
|**SN1**|||**Item Detail (Shipment)**|**O**||**1**|
||2|382|Number of Units Shipped|M|R|1/10|
||3|355|Unit or Basis for Measurement Code|M|ID|2/2|

		
		
	
	
**Summary:**

|SegID|Ref. Des.|Elem.|Name|Req.|Type|Max Usage|
|----|----|----|----|----|----|----|
|**SAC**|||**Service, Promotion, Allowance**|**O**||**1**|
||1|248|Allowance or Charge Indicator|M|ID|1/1|
||||*C – Charge*||||
||2|1300|Service, Promotion, Allowance|M|ID| 4/4|
||||*G830 – Shipping and Handling*||||
||5|610|Amount|O| N2|1/15     (8.2)|
||||||||
|**CTT**|||**Transaction Totals**|**M**||**1**|
||01 |354  |Number of Line Items                   |M   |N0 |1/6|
|**SE**|||**Transaction Set Trailer**|**M**||**1**|
||1|96   |Number of Included Segments|M   |N0 |1/10|
||02 |329  |Transaction Set Control Number|M   |AN |4/9|


**Envelope:**

|SegID|Ref. Des.|Elem.|Name|Req.|Type|Max Usage|
|----|----|----|----|----|----|----|
|**GE**|||**Functional Group Trailer**|**M**||**1**||
||01 |97   |Number of Transaction Sets Incl|M   |N0 |1/6|
||02 |28   |Group Control Number                  |M   |N0 |1/9|
|**IEA**|||**Interchange Control Trailer**|**M**||**1**|
||01 |I16  |Num of Included Functional Grps|M   |N0 |1/5|
||02 |I12  |Interchange Control Number        |M   |N0 |9/9|



**EDI-856 Sample data**
```
ISA~00~          ~00~          ~ZZ~PEACHDIRECT~ZZ~PARTNER          ~000131~0326~U~00401~000022429~0~T~>
GS~SH~ PEACHDIRECT~PARTNER~000131~0326~22429~X~004010
ST~856~316079
BSN~00~0104779658001~20080401~010356
HL~1~~S
TD5~~01~UPG
REF~SI~1Z462E560365230530
DTM~011~20000209
HL~2~~O
PRF~CUSTPONUMBER
HL~3~~I
LIN~0001~VP~PET10-6003
SN1~~1~EA
CTT~1
SE~13~316079
GE~1~23319
IEA~1~000023319
```

###EDI Specifications (Outbound-810 - ASC X12 004010)
**Envelope:**

|SegID|Ref. Des.|Elem.|Name|Req.|Type|Max Usage|
|----|----|----|----|----|----|----|
|**ISA**|||**Interchange Control Header**|**M**||**1**|
||01 |I01  |Authorization Information Qual|M   |ID |2/2|
||02 |I02  |Authorization Information|M|AN|10/10|
| |03 |I03  |Security Information Qualifier|M   |ID |2/2|
| |04 |I04  |Security Information  |M   |AN |10/10|
||05 |I05  |Interchange ID Qualifier|M   |ID |2/2|
||06 |I06  |Interchange Sender ID |M   |AN |15/15|
||07 |I05  |Interchange ID Qualifier|M   |ID |2/2|
||08 |I07  |Interchange Receiver ID   |M   |AN |15/15|
||09 |I08  |Interchange Date|M   |DT |6/6|
||10 |I09  |Interchange Time|M   |TM |4/4|
| |11 |I10  |Interchange Control Standards Id|M   |ID |1/1|
| |12 |I11  |Interchange Control Version Num|M   |ID |5/5|
| |13 |I12  |Interchange Control Number |M   |N0 |9/9|
| |14 |I13  |Acknowledgment Requested|M   |ID |1/1|
| |15 |I14  |Usage Indicator            |M   |ID |1/1|
| |16 |I15  |Component Element Separator|M      ||1/1|
|**GS**|||**Functional Group Header**|**M**||**1**|
||01 |479  |Functional Identifier Code            |M   |ID |2/2|
||2|142  |Application Sender's Code            |M   |AN |2/15|
||03 |124  |Application Receiver's Code        |M   |AN |2/15|
||04 |373  |Date                                                |M   |DT |8/8|
||05 |337  |Time                                               |M   |TM |4/8|
||06 |28   |Group Control Number                 |M   |N0 |1/9|
||07 |455  |Responsible Agency Code            |M   |ID |1/2|
||08 |480  |Version / Release / Industry Id      |M   |AN |1/12|


**Heading:**

|SegID|Ref. Des.|Elem.|Name|Req.|Type|Max Usage|
|----|----|----|----|----|----|----|
|**ST**|||**Transaction Set Header**|**M**||**1**|
||1|143|Transaction Set Identifier Code|M|ID|3/3|
| |2|329|Transaction Set Control Number|M|AN|4/9|
|**BIG**|||**Beginning Segment for Invoice**|**M**||**1**|
||1|373|Date|M|DT|8/8|
||2|76|Invoice Number|M|AN|1/10|
||4|324|Purchase Order Number|M|AN|1/20|
|**DTM**|||**Date/Time Qualifier**|**O**||**1**|
||1|374|Date/Time Qualifier|M|ID|3/3|
||||*011 - Shipped*||||
||2|373|Date|M|DT|8/8|


**Detail:**

|SegID|Ref. Des.|Elem.|Name|Req.|Type|Max Usage|
|----|----|----|----|----|----|----|
|**IT1**|||**Baseline Item Data (Invoice)**|**O**||**1**|
||1|350|Assigned Identification|M|N0|1/4|
||2|358|Quantity Invoiced|M|N0|1/10|
||3|355|Unit or Basis for Measurement Code|M|ID|2/2|
||||*EA – Each*||||
||4|212|Unit Price|M|N2|4/11    (8.2)|
||6|235|Product/Service ID Qualifier|M|ID|2/2|
||||*VP – Vendor’s(Seller’s) Part Number*||||
||7|234|Product/Service ID|M|AN|1/30|

**Summary:**

|SegID|Ref. Des.|Elem.|Name|Req.|Type|Max Usage|
|----|----|----|----|----|----|----|
|**TDS**|||**Total Monetary Value Summ**|**M**|**1**| |
||1|610|Amount|M|N2|1/15|
||||||||
|**SAC**|||**Service, Promotion, Allowance**|**O**||**1**|
||1|248|Allowance or Charge Indicator|M|ID|1/1|
||||*C – Charge*|||||
||2|1300|Service, Promotion, Allowance|M|ID| 4/4|
||||*G830 – Shipping and Handling*||||
||5|610|Amount|M| N2|1/11     (8.2)|
||||||||
|**CTT**|||**Transaction Totals**|**M**||**1**|
||01 |354  |Number of Line Items                   |M   |N0 |1/6|
|**SE**|||**Transaction Set Trailer**|**M**||**1**|
||1|96   |Number of Included Segments|M   |N0 |1/10|
||02 |329  |Transaction Set Control Number|M   |AN |4/9|


**Envelope:**

|SegID|Ref. Des.|Elem.|Name|Req.|Type|Max Usage|
|----|----|----|----|----|----|----|
|**GE**|||**Functional Group Trailer**|**M**|        |**1**||
||01 |97   |Number of Transaction Sets Incl|M   |N0 |1/6|
||02 |28   |Group Control Number                  |M   |N0 |1/9|
|**IEA**|||**Interchange Control Trailer**|**M**||**1**|
||01 |I16  |Num of Included Functional Grps|M   |N0 |1/5|
||02 |I12  |Interchange Control Number        |M   |N0 |9/9|



**EDI-810 Sample data**
```
ISA~00~          ~00~          ~ ZZ~PEACHDIRECT ~ZZ~PARTNER          ~000301~0213~U~00401~000001115~0~T~>
GS~IN~ PEACHDIRECT ~PARTNER~20000301~0213~1115~X~004010
ST~810~1489
BIG~20000301~A04148028~CUSTPONO
DTM~011~20000301
IT1~1~5~EA~0.79~~VP~PET10-6003
TDS~395
SAC~C~G850~~~0
CTT~1
SE~8~1489
GE~3~1115
IEA~1~000001115
```
