<?php

namespace app\controllers;

use yii\web\Controller;
use Yii;
use app\server\XmlServer;

class WeixinController extends Controller
{
    public $enableCsrfValidation = false;
    public function actionIndex()
    {
        $strParam = file_get_contents("php://input");
        $aryResult = XmlServer::xmlToArray($strParam);

        $strUrl = "<a href='http://www.baidu.com'>菜单1</a>\r\n";
        $strUrl .= "<a href='http://www.baidu.com'>菜单2</a>\r\n";
        $strUrl .= "<a href='http://www.baidu.com'>菜单3</a>\r\n";
        $strUrl .= "<a href='http://www.baidu.com'>菜单4</a>";

        //Yii::$app->cache->set("aaaa", "测试存储", 3);
        //sleep(1);
        //echo Yii::$app->cache->get("aaaa");  exit();

       echo "<xml>
       <ToUserName><![CDATA[".$_GET['openid']."]]></ToUserName>
       <FromUserName><![CDATA[gh_5a05cce1d0d7]]></FromUserName>
       <CreateTime>".time()."</CreateTime>
       <MsgType><![CDATA[text]]></MsgType>
       <Content><![CDATA[".$strUrl."]]></Content>
       </xml>";exit();
    }
}
