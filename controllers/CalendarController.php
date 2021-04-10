<?php

namespace app\controllers;

use app\models\CalendarGoogleModel;
use app\models\query\EventsQuery;
use app\models\UsersModel;
use app\rest\ActiveController;
use Exception;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
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
    $token='1236954789';
    $client = new Google_Client();
    $client->setApplicationName('Google Calendar API PHP Richard Test Software');
    $client->setScopes(Google_Service_Calendar::CALENDAR);
    $client->setAuthConfig('credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');
    $extra=$client->createAuthUrl()."&user=$token";
    print_r($client->createAuthUrl());
    print_r("&user=$token"); die();
    return $extra;
  }

  public function actionCreate()
  {
    $calendarGoogle = CalendarGoogleModel::find()
      ->where(['condition' => 1, 'usuario_id' => 2])
      ->one();
    $accessToken = json_decode($calendarGoogle->token, true);

    $client = new Google_Client();
    $client->setAuthConfig('credentials.json');
    $client->setAccessToken($accessToken);
    if ($client->isAccessTokenExpired()) {
      if ($client->getRefreshToken()) {
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
      }
    }

    $service = new Google_Service_Calendar($client);

    $id = 14;
    $user = UsersModel::find()
      ->where(['condition' => 1, 'id' => $id])->one();
    if (!$user) {
      throw new Exception("El usuairo no existe");
    }

    $ids = EventsQuery::getEventsByUser($user->token);

    $evento = EventsQuery::getEventById($ids);

    $data = EventsQuery::getEventsByIds($evento);



    $infoEvent = [];
    $resultEvent = '';
    foreach ($data as $key => $row) {
      foreach ($row['events'] as $key => $value) {
        $speakers = array_column($value['speaker'], 'name');
        
        $arraySpeakers = [];
        foreach ($speakers as $key => $valuex) {
          $arraySpeakers[] = [
            'name' => $valuex
          ];
        }

        $start = [
          'dateTime' => $value['date'],
          'timeZone' => ''
        ];
        $end = [
          'dateTime' => $value['date'],
          'timeZone' => ''
        ];
        $infoEvent = [
          'summary' => $value['title'],
          'location' => $value['city'],
          'description' => $value['description'],
          'start' => $start,
          'end' => $end,
          'attendees' => $arraySpeakers,
          'reminders' => [
            'overrides' => ['method' => 'popup', 'minutes' => 10]
          ]
        ];
        $event = new Google_Service_Calendar_Event($infoEvent);
        $calendarId = 'primary';
        $resultEvent = $service->events->insert($calendarId, $event);
      }
    }
    return $resultEvent->htmlLink;
    print_r($infoEvent);
    die();

    // $event = new Google_Service_Calendar_Event(array(
    //   'summary' => 'Google I/O 2015',
    //   'location' => '800 Howard St., San Francisco, CA 94103',
    //   'description' => 'A chance to hear more about Google\'s developer products.',
    //   'start' => array(
    //     'dateTime' => '2021-05-10T09:00:00-07:00',
    //     'timeZone' => 'America/Los_Angeles',
    //   ),
    //   'end' => array(
    //     'dateTime' => '2021-05-10T17:00:00-07:00',
    //     'timeZone' => 'America/Los_Angeles',
    //   ),
    //   'recurrence' => array(
    //     'RRULE:FREQ=DAILY;COUNT=2'
    //   ),
    //   'attendees' => array(
    //     array('email' => 'richard21hg92@gmail.com'),
    //     array('email' => 'richard@cuborojo.pe'),
    //   ),
    //   'reminders' => array(
    //     'useDefault' => FALSE,
    //     'overrides' => array(
    //       array('method' => 'email', 'minutes' => 24 * 60),
    //       array('method' => 'popup', 'minutes' => 10),
    //     ),
    //   ),
    // ));

    // $calendarId = 'primary';
    // $event = $service->events->insert($calendarId, $event);
    // //   printf('Event created: %s\n', $event->htmlLink);
    // return '';
  }
}
