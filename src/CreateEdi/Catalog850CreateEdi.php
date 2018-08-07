<?php

namespace Coedition\EDI\Creator;
use Coedition\EDI\CreateEdi;
use Coedition\EDI\Catalogs\Catalog850;
/**
 * Class Catalog850CreateEdi
 * Create EDI 850 from Mage2 body resp
 * @package Coedition\EDI\Creator
 */

class Catalog850CreateEdi implements CreateEdi{
    const CATALOG = 850;
    private $order_body;
    private $sender_id;
    private $receiver_id;
    private $sections;
    private $interchange_control_number;
    private $transaction_control_number;

    public function __construct($sender_id = self::SENDERID, $receiver_id = self::RECEIVERID) {
        $this->receiver_id = $receiver_id;
        $this->sender_id = $sender_id;
    }

    /**
     * create_edi() main function
     * @return string
     */
    public function __invoke($order_id, array $order_body, int $interchange_control_num, int $transaction_control_num) : string {
        $this->order_id = $order_id;
        $this->order_body = $order_body;
        $this->interchange_control_number = $interchange_control_num;
        $this->transaction_control_number = $transaction_control_num;
        $this->sections = $this->prepareSections();

        $this->sections = $this->padSections($this->sections);
        $edi_out = '';
        foreach($this->sections as $key => $section) {
            $section_string = '';
            if (is_array($section[0])){
                foreach ($section as $section_item) {
                    //dump($section_item);
                    $section_string .= implode('|',$section_item);
                    $section_string .= "\n";
                }
            }
            else {
                $section_string = implode('|',$section);
                $section_string .= "\n";
            }
            $edi_out .= $section_string;
        }
        return $edi_out;
    }

    /**
     * @return mixed
     */
    public function getOrderBody()
    {
        return $this->order_body;
    }

    /**
     * @return string
     */
    public function getSenderId()
    {
        return $this->sender_id;
    }

    /**
     * @return string
     */
    public function getReceiverId()
    {
        return $this->receiver_id;
    }

    /**
     * @return array
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * @return mixed
     */
    public function getInterchangeControlNumber()
    {
        return $this->interchange_control_number;
    }

    /**
     * @return mixed
     */
    public function getTransactionControlNumber()
    {
        return $this->transaction_control_number;
    }

    private function prepareSections()
    {
        $date = new \DateTime();
        $edi_fields = [
            'senderId' => $this->sender_id,
            'receiverId' => $this->receiver_id,
            'billing_address' => $this->order_body['billing_address'],
            'shipping_addresses' => [],
            'items' => $this->order_body['items'],
            'date' => $date->format('ymd'),   // yymmdd
            'longdate' => $date->format('Ymd'),   // yyyymmdd
            'time' => $date->format('hm')    // hhmm

        ];

        $ISA = [
            'ISA',
            '00',
            '',
            '00',
            '',
            'ZZ',
            $edi_fields['senderId'],
            '01',
            $edi_fields['receiverId'],
            $edi_fields['date'],
            $edi_fields['time'],
            'U',     // Interchange Control Standards Id
            '00401', // Interchange Control Version Number
            $this->interchange_control_number,
            '0',     // Ack Requested
            'T',     // Usage Indicator
            '>'      // Component Element Separator
        ];
        $this->padElements($ISA);

        $GS = [
            "GS",
            "PO",
            $edi_fields['senderId'],
            $edi_fields['receiverId'],
            $edi_fields['longdate'],
            $edi_fields['time'],
            $this->interchange_control_number, // Group control number @TODO this may need to increment for each item?
            'X', // Responsible Agency Code
            '004010' // Release/Version/IndustryId
        ];

        $ST = [
            "ST",
            self::CATALOG,
            $this->transaction_control_number
        ];

        $BEG = [
            'BEG',
            "00",
            "DS",
            "PO NUM", // @TODO need to get this from somewhere
            '',
            "20000101" // @TODO need to get PO date also -- I *think* this is just current date
        ];

        // @TODO check these may need to set dynamically?
        $REF = [
            'REF',
            'ZZ', // Reference Identification Qualifier
            'MUTUALLY DEFINED' // Reference Identification
        ];

        // @TODO check these may need to set dynamically?
        $TD5 = [
            'TD5',
            '1',  // Routing Sequence Code
            '01', // Identification Code Qualifier
            'UG'  // Identification Code

        ];

        $N1 = [
            // Entity Identifier Code
            'N1',
            'ST',
            $edi_fields['billing_address']['firstname'] . ' ' . $edi_fields['billing_address']['lastname'],
            // Company Name
        ];

        $N3 = [
            'N3',
            $edi_fields['billing_address']['street'][0]    // Address
        ];

        $N4 = [
            'N4',
            $edi_fields['billing_address']['city'],        // City
            $edi_fields['billing_address']['region_code'], // State
            $edi_fields['billing_address']['postcode']     // Zip
        ];

        $PER = [
            'PER',
            'IC', // Information Contact
            $edi_fields['billing_address']['telephone'],   // Phone Number
        ];

        $PO1 = $this->PO1Func($edi_fields['items']);

        /* @TODO may need to figure CTT with a func
         * $CTT_func = function($PO1) {
         * $ctt = [];
         * foreach ($PO1 as $count => $item) {
         * $ctt[] = [
         *
         * ];
         * }
         * };
         */

        $CTT = [
            'CTT',
            count($PO1) // line count (items) in this PO
        ];

        $SE = [
            'SE',
            (string)(10 + count($PO1)), // number of included segments @TODO check make dynamic value
            $this->transaction_control_number
        ];

        $GE = [
            'GE',
            '1',           // number of transaction sets included @TODO make dynamic value
            $this->interchange_control_number // group control number

        ];

        $IEA = [
            'IEA',
            '1',
            $this->interchange_control_number
        ];

        $sections = [$ISA, $GS, $ST, $BEG, $REF, $TD5, $N1, $N3, $N4, $PER, $PO1, $CTT, $SE, $GE, $IEA];
        return $sections;
    }

    private function PO1Func($items) {
        $po1 = [];
        foreach ($items as $count => $item) {
            $po1[] = [
                'PO1',
                ($count+1),           // Assigned Identification (increment value)
                $item['qty_ordered'], // Qty Ordered
                'EA',                 // Unit or Basis For Measurement
                $item['price'], // Unit Price
                '',
                'UP',                 // Vendors Part Number Qualifier
                $item['sku'],         // Vendors Part Number
                'BP',                 // CreativeComputersPartNumber (name desc)
                $item['name']
            ];
        }
        return $po1;
    }

    private function getElement($element, $index)
    {
        if ($element == 'PO' && $index >=5) {
            $index++;
        }
        $elements = Catalog850::$temp_ELEMENTS;
        // element_code eg: PO07
        $element_code = $element . str_pad($index, 2, '0', STR_PAD_LEFT);
        if (isset($elements[$element_code])) {
            return $elements[$element_code];
        }
        //dump("$element_code did not exist");
        return null;
    }

    private function getElementType($element, $index) {
        $element = $this->getElement($element,$index);
        return $element[2];
    }

    private function getElementMinMax($element, $index) {
        $element = $this->getElement($element,$index);
        return [$element[3],$element[4]];
    }

    // Handle for an array of Sections, handing off to padElements
    private function padSections(array $sections) {
        foreach ($sections as $index => $section) {
            $sections[$index] = $this->padElements($section);
        }
        return $sections;
    }
    // Add padding to the elements to match the Creator::ELEMENTS rules
    private function padElements(array $elements) {
        $element_name = null;
        $number_segments = ['NO', 'ID'];
        foreach($elements as $index => $element) {
            // if sub-array then do recursion..
            if (is_array($element)) {
                $elements[$index] = $this->padElements($element);
                //$elements[$index] = $element;
                continue;
            }
            if($index == 0) {
                $element_name = $element;
                continue;
            }
            list($min, $max) = $this->getElementMinMax($element_name, $index);
            $type = $this->getElementType($element_name, $index);

            //dump([$element_name, $index, $min, $max, $element]);
            if (strlen($element) < $min ) {
                $pad_char = in_array($type, $number_segments) || is_numeric($element) ? 0 : ' ';
                $pad_side = in_array($type, $number_segments) || is_numeric($element) ? STR_PAD_LEFT : STR_PAD_RIGHT;
                echo "ELEMENT: $element_name $index $type ->$element<- has been padded to $min chars\n";
                $elements[$index] = str_pad($element, $min, $pad_char, $pad_side);
            }
            if (($min == $max) && strlen($element) < $min ) {
                $pad_char = in_array($type, $number_segments) || is_numeric($element) ? 0 : ' ';
                $pad_side = in_array($type, $number_segments) || is_numeric($element) ? STR_PAD_LEFT : STR_PAD_RIGHT;
                echo "ELEMENT: $element_name $index $type ->$element<- has been padded to $min chars\n";
                $elements[$index] = str_pad($element, $max, $pad_char, $pad_side);
            }
            if(strlen($element) > $max) {
                echo "ELEMENT: $element_name $index $type ->$element<- has been truncated to $max chars\n";
                $elements[$index] = substr($element, 0, $max);
            }
        }
        return $elements;
    }
}
