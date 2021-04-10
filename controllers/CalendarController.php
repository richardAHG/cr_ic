<?php

namespace app\controllers;

use app\helpers\CalendarGoogle;
use app\models\CalendarGoogleModel;
use app\rest\ActiveController;
use DateTime;
use Google_Client;
use Google_Service_Calendar;
use Yii;
use yii\web\BadRequestHttpException;

class CalendarController extends ActiveController
{
  public $modelClass = 'app\models\CalendarGoogleModel';

  public function actions()
  {
    return [];
  }

  public function actionIndex()
  {
    $client = new Google_Client();
    $client->setApplicationName('Google Calendar API PHP Richard Test Software');
    $client->setScopes(Google_Service_Calendar::CALENDAR);
    $client->setAuthConfig('credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');
    return $client->createAuthUrl();
  }

  public function actionCreate()
  {
    $requestParams = Yii::$app->getRequest()->getBodyParams();

    //Solitar Access token a google
    $accessToken = CalendarGoogle::getTokenAutorize($requestParams['code']);

    //registro del access token asignado al usuario
    $today = new DateTime('now');
    $callGoogle = new CalendarGoogleModel();
    $callGoogle->usuario_id = $requestParams['userId'];
    $callGoogle->token = json_encode($accessToken);
    $callGoogle->date_created = $today->format('Y-m-d');

    if (!$callGoogle->save()) {
      throw new BadRequestHttpException("error al guardar los datos");
    }

    // $calendarGoogle = CalendarGoogleModel::find()
    //   ->where(['condition' => 1, 'usuario_id' => 2])
    //   ->one();

    // $accessToken = json_decode($callGoogle->token, true);

    //Prcoeso para registrar en el calndario del usuario
    return CalendarGoogle::crearEvento($requestParams['userId']);
  }
}
