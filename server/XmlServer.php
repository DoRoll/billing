<?php
use yii\base\ErrorException;
use yii\base\Object;
class XML2Array
{
    private static $xml = null;
    private static $encoding = 'UTF-8';

    /**
     * 载入
     * 
     * @param string $version 版本
     * @param string $encoding 字符编码
     * @param string $format
     *
     * @author xiaoyi
     * @date 2016年10月14日
     */
    public static function init($_strVersion = '1.0', $_strEncoding = 'UTF-8', $_strFormat = true)
    {
        self::$xml = new \DOMDocument($_strVersion, $_strEncoding);
        self::$xml->formatOutput = $_strFormat;
        self::$encoding = $_strEncoding;
    }

    /**
     * 将一个xml 抓换为数据
     * @param unknown $input_xml 待转换xml
     * @throws Exception
     *
     * @author xiaoyi
     * @date 2016年10月14日
     */
    public static function &createArray($_strXml="")
    {
        $objXml = self::getXMLObject();    
        if(is_string($_strXml) && !($objXml->loadXML($_strXml)))
        {
            throw new ErrorException("xml 解析失败");
        }
        else
        {
            if(get_class($_strXml) != "DOMDocument")
                throw new ErrorException("xml 解析失败");
            $objXml = self::$xml = $_strXml;
        }
        
        $aryTemp[$objXml->documentElement->tagName] = self::convert($objXml->documentElement);
        self::$xml = null;
        return $aryTemp;
    }

    /**
     * 转换xml实现
     * 
     * @param Object $_objNode
     *
     * @author xiaoyi
     * @date 2016年10月14日
     */
    private static function &convert($_objNode)
    {
        $aryOutput = [];

        switch ($_objNode->nodeType) {
            case XML_CDATA_SECTION_NODE:
                $aryOutput['@cdata'] = trim($_objNode->textContent);
                break;

            case XML_TEXT_NODE:
                $aryOutput = trim($_objNode->textContent);
                break;

            case XML_ELEMENT_NODE:
                for ($i=0, $m=$_objNode->childNodes->length; $i<$m; $i++)
                {
                    $objChild = $_objNode->childNodes->item($i);
                    $strResult = self::convert($objChild);
                    if(isset($objChild->tagName))
                    {
                        $strTagName = $objChild->tagName;

                        // assume more nodes of same kind are coming
                        if(!isset($aryOutput[$strTagName]))
                        {
                            $output[$strTagName] = [];
                        }
                        $aryOutput[$strTagName][] = $strResult;
                    }
                    else
                   {
                        if($strResult !== '')
                        {
                            $aryOutput = $strResult;
                        }
                    }
                }

                if(is_array($aryOutput))
                {
                    foreach ($aryOutput as $strTagName => $strValue)
                    {
                        if(is_array($strValue) && count($strValue)==1)
                            $aryOutput[$strTagName] = $strValue[0];
                    }
                    if(empty($aryOutput))
                        $aryOutput = '';
                }

                if($_objNode->attributes->length)
                {
                    $aryTemp = [];
                    foreach($_objNode->attributes as $attrName => $attrNode)
                    {
                        $aryTemp[$attrName] = (string) $attrNode->value;
                    }
                    if(!is_array($aryOutput)) {
                        $aryOutput = array('@value' => $aryOutput);
                    }
                    $aryOutput['@attributes'] = $aryTemp;
                }
                break;
        }
        return $aryOutput;
    }

    /**
     * 返回xml单例对象
     *
     * @author xiaoyi
     * @date 2016年10月14日
     */
    private static function getXMLObject(){
        if(empty(self::$xml)) {
            self::init();
        }
        return self::$xml;
    }
}

?>