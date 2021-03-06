<?php

namespace app\helpers;

use app\models\CalendarGoogleModel;
use app\models\query\EventsQuery;
use app\models\UsersModel;
use DateTime;
use DateTimeZone;
use Exception;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use yii\web\BadRequestHttpException;

class CalendarGoogle
{

  public static function getTokenAutorize($codeGoogle)
  {

    $client = new Google_Client();
    $client->setAuthConfig('credentials.json');

    if ($client->isAccessTokenExpired()) {
      if ($client->getRefreshToken()) {
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
      } else {
        // Exchange authorization code for an access token.
        $accessToken = $client->fetchAccessTokenWithAuthCode($codeGoogle);
        $client->setAccessToken($accessToken);

        // Check to see if there was an error.
        if (array_key_exists('error', $accessToken)) {
          throw new Exception(join(', ', $accessToken));
        }
      }
    }
    return $accessToken;
  }

  public static function crearEvento($userId)
  {
    $calendarGoogle = CalendarGoogleModel::find()
      ->where(['condition' => 1, 'usuario_id' => $userId])
      ->orderBy(['id' => SORT_DESC])
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


    $user = UsersModel::find()
      ->where(['condition' => 1, 'id' => $userId])
      ->one();
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
            'email' => 'prueba@gmail.com'
          ];
        }
        $date = $fecha = new DateTime($value['date'], new DateTimeZone('America/Lima'));
        $a = $fecha->format('Y-m-d H:i:sP');
        $date = str_replace(' ', 'T', $a);

        // $start = [
        //   'dateTime' => '2021-05-10T09:00:00-07:00',
        //   'timeZone' => 'America/Los_Angeles'
        // ];
        // $end = [
        //   'dateTime' => '2021-05-10T09:00:00-07:00',
        //   'timeZone' => 'America/Los_Angeles'
        // ];
        $start = [
          'dateTime' =>  $date,
          'timeZone' => 'America/Lima'
        ];
        $end = [
          'dateTime' =>  $date,
          'timeZone' => 'America/Lima'
        ];
        $infoEvent = [
          'summary' => $value['title'],
          'location' => $value['city'],
          'description' => $value['description'] ."<a href='https://credicorpcapitalconference.web.app/event'>Acceder</a>",
          'start' => $start,
          'end' => $end,
          // 'attendees' => $arraySpeakers,
          'reminders' => [
            'useDefault' => FALSE,
            'overrides' => ['method' => 'popup', 'minutes' => 10]
          ]
        ];

        $event = new Google_Service_Calendar_Event($infoEvent);
        $calendarId = 'primary';
        $resultEvent = $service->events->insert($calendarId, $event);
        // echo $resultEvent->htmlLink;
      }
    }

    //deshabilitamos el token de usuario de la table user_events
    $calendarGoogle->condition = 0;
    if (!$calendarGoogle->save()) {
      throw new BadRequestHttpException("Error al dar de baja el token de usuario");
    }

    return $resultEvent->htmlLink;

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
