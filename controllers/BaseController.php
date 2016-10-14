<?php

namespace app\controllers;

use yii\web\Controller;
use Yii;

class BaseController extends Controller
{
    public function init()
    {
        if(empty( Yii::$app->session->get("user")))
        {
           if(isset($_GET['user']))
           {
                Yii::$app->session->set("user", $_GET['user']);
           }
        }
        
    }
}
