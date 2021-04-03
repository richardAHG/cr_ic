<?php

namespace app\controllers;

use yii\web\Controller;
use Google_Client;
use Google_Service_Calendar;
use Yii;

class OauthController extends Controller
{

    public function actionIndex()
    {
        // $requestParams = Yii::$app->getRequest()->getQueryParams();
        $code = Yii::$app->getRequest()->get('code', false);
        $client = new Google_Client();
        $accessToken = $client->fetchAccessTokenWithAuthCode($code);
        
        echo $accessToken;
    }
}
