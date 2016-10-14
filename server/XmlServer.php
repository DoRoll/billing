<?php
class XML2Array {

        private static $xml = null;
        private static $encoding = 'UTF-8';

        /**
         * Initialize the root XML node [optional]
         * @param $version
         * @param $encoding
         * @param $format_output
         */
        public static function init($version = '1.0', $encoding = 'UTF-8', $format_output = true) {
                self::$xml = new \DOMDocument($version, $encoding);
                self::$xml->formatOutput = $format_output;
                self::$encoding = $encoding;
        }

        /**
         * Convert an XML to Array
         * @param string $node_name - name of the root node to be converted
         * @param array $arr - aray to be converterd
         * @return DOMDocument
         */
        public static function &createArray($input_xml) {
                $xml = self::getXMLRoot();
                if(is_string($input_xml)) {
                        $parsed = $xml->loadXML($input_xml);
                        if(!$parsed) {
                                throw new Exception('[XML2Array] Error parsing the XML string.');
                        }
                } else {
                        if(get_class($input_xml) != 'DOMDocument') {
                                throw new Exception('[XML2Array] The input XML object should be of type: DOMDocument.');
                        }
                        $xml = self::$xml = $input_xml;
                }
                $array[$xml->documentElement->tagName] = self::convert($xml->documentElement);
                self::$xml = null;    // clear the xml node in the class for 2nd time use.
                return $array;
        }

        /**
         * Convert an Array to XML
         * @param mixed $node - XML as a string or as an object of DOMDocument
         * @return mixed
         */
        private static function &convert($node) {
                $output = array();

                switch ($node->nodeType) {
                        case XML_CDATA_SECTION_NODE:
                                $output['@cdata'] = trim($node->textContent);
                                break;

                        case XML_TEXT_NODE:
                                $output = trim($node->textContent);
                                break;

