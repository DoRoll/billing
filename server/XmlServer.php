<?php
namespace app\server;

class XmlServer
{
    public static function xmlToArray($_strXml="")
    {
        $objXmlparser = xml_parser_create();
        xml_parse_into_struct($objXmlparser, $_strXml, $aryValues);
        xml_parser_free($objXmlparser);

        // 如果转换结果为空
        if(empty($aryValues))
            return [];

        $aryTemp =[];
        foreach($aryValues as $aryInfo)
        {
            if($aryInfo['tag'] == "XML")
                continue;

            $aryTemp[strtolower($aryInfo['tag'])] = $aryInfo['value'];
        }

        return $aryTemp;
    }
}

?>
