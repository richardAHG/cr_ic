<?php

namespace app\controllers;

use app\helpers\CalendarGoogle;
use app\helpers\CalendarMicrosoft;
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
    // $actions = parent::actions();
    // $actions['calendar.saludo']['prepareDataProvider'] = [$this, 'actionSaludo'];
    return [];
  }

  public function actionGmail()
  {
    $client = new Google_Client();
    $client->setApplicationName('Google Calendar API PHP Richard Test Software');
    $client->setScopes(Google_Service_Calendar::CALENDAR);
    $client->setAuthConfig('credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');
    return $client->createAuthUrl();
  }

  public function actionOutlook_bk()
  {
    $client_id = 'd9b054b3-5380-49a5-a93b-5186f9e7b8cb';
    $response_type = 'code';
    // $redirect_uri='https%3A%2F%2Frhg-sandbox.com%2Foauth%2Fmicrosoft';
    // $redirect_uri = 'https%3A%2F%2Fcredicorpcapitalconference.web.app%2Foauth';
    $redirect_uri = 'https%3A%2F%2Fapi.v2.credicorpcapitalconference.com%2Foauth%2Fmicrosoft';
    $response_mode = 'query';
    $scope = 'offline_access%20user.read%20Calendars.ReadWrite';
    $state = '12345';

    $url = "https://login.microsoftonline.com/consumers/oauth2/v2.0/authorize?client_id=$client_id&response_type=$response_type&redirect_uri=$redirect_uri&response_mode=$response_mode&scope=$scope&state=$state";

    return $url;
  }

  public function actionOutlook()
  {
    $client_id = '61b2eee2-4d96-47d7-8903-f2dcbdd31940';
    $response_type = 'code';
    // $redirect_uri='https%3A%2F%2Frhg-sandbox.com%2Foauth%2Fmicrosoft';
    $redirect_uri = 'https%3A%2F%2Fcredicorpcapitalconference.web.app%2Foauth';
    // $redirect_uri = 'https%3A%2F%2Fapi.v2.credicorpcapitalconference.com%2Foauth%2Fmicrosoft';
    $response_mode = 'query';
    $scope = 'offline_access%20user.read%20Calendars.ReadWrite';
    $state = '12345';
    // consumers
    $url = "https://login.microsoftonline.com/common/oauth2/v2.0/authorize?client_id=$client_id&response_type=$response_type&redirect_uri=$redirect_uri&response_mode=$response_mode&scope=$scope&state=$state";

    return $url;
  }

  public function actionGmailsave()
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

    //Prcoeso para registrar en el calndario del usuario
    return CalendarGoogle::crearEvento($requestParams['userId']);
  }

  public function actionOutlooksave()
  {
    $requestParams = Yii::$app->getRequest()->getBodyParams();

    //Solitar Access token a outlook
    // $accessToken = CalendarMicrosoft::getTokenAutorize($requestParams['code']);
    // print_r($accessToken);
    // die();
    $url = CalendarMicrosoft::crearEvento($requestParams);
    return $url['result'];
    }
}
