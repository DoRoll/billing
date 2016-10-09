<?php

namespace app\controllers;

use yii\web\Controller;

class WeixinController extends Controller
{
    public $enableCsrfValidation = false;
    public function actionIndex()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = \Yii::$app->params['weixin_token'];
        $tmpArr = array($token, $timestamp, $nonce);
        
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( "", $tmpArr );
        $tmpStr = sha1( $tmpStr );

        $objFile = fopen("a.txt", "a");
        fwrite($objFile, $tmpStr."\r\n-------".$signature);
        fclose($objFile);
        
        if( $tmpStr == $signature ){
            echo $_GET["echostr"];exit();
        }else{
            return false;
        }
    }
}
