<?php

namespace app\controllers;

use app\rest\ActiveController;
use Google_Client;
use Google_Service_Calendar;
use Yii;

class CalendarController extends ActiveController
{
    public $modelClass = 'app\models\CalendarGoogleModel';

    public function actions()
    {
        return [];
    }

    public function actionIndex()
    {
        // $requestParams = Yii::$app->getRequest()->getQueryParams();
        $code = Yii::$app->getRequest()->get('code', false);
        print_r($code);
    }
}
