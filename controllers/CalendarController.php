<?php

namespace app\controllers;

use app\models\CalendarGoogleModel;
use app\rest\ActiveController;
use Exception;
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
        $calendarId = 'primary';
        $optParams = array(
            'maxResults' => 10,
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => date('c'),
        );
        $results = $service->events->listEvents($calendarId, $optParams);
        $events = $results->getItems();

        if (empty($events)) {
            echo "No upcoming events found.\n";
        } else {
            echo "Upcoming events:\n";
            foreach ($events as $event) {
                $start = $event->start->dateTime;
                if (empty($start)) {
                    $start = $event->start->date;
                }
                print_r($event->getSummary());
            }
        }
    }
}
