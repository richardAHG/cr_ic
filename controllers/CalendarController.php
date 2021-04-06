<?php

namespace app\controllers;

use app\models\CalendarGoogleModel;
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
        // $calendarId = 'primary';
        // $optParams = array(
        //     'maxResults' => 10,
        //     'orderBy' => 'startTime',
        //     'singleEvents' => true,
        //     'timeMin' => date('c'),
        // );
        // $event=new Google_Service_Calendar_Event();
        // $event->maxAttendees = 10;
        // $event->sendUpdates='all';
        // $event->end='12:00';
        // $event->start='10:00';
        // $event->conferenceData='info de la conferencia';
        // $event->description='descripcion del evento';
        // $event->location='lima - Peru';

        // $results = $service->events->listEvents($calendarId, $optParams);

        $event = new Google_Service_Calendar_Event(array(
            'summary' => 'Google I/O 2015',
            'location' => '800 Howard St., San Francisco, CA 94103',
            'description' => 'A chance to hear more about Google\'s developer products.',
            'start' => array(
              'dateTime' => '2015-05-28T09:00:00-07:00',
              'timeZone' => 'America/Los_Angeles',
            ),
            'end' => array(
              'dateTime' => '2015-05-28T17:00:00-07:00',
              'timeZone' => 'America/Los_Angeles',
            ),
            'recurrence' => array(
              'RRULE:FREQ=DAILY;COUNT=2'
            ),
            'attendees' => array(
              array('email' => 'lpage@example.com'),
              array('email' => 'sbrin@example.com'),
            ),
            'reminders' => array(
              'useDefault' => FALSE,
              'overrides' => array(
                array('method' => 'email', 'minutes' => 24 * 60),
                array('method' => 'popup', 'minutes' => 10),
              ),
            ),
          ));
          
          $calendarId = 'primary';
          $event = $service->events->insert($calendarId, $event);
          printf('Event created: %s\n', $event->htmlLink);
        
    }
}
