<?php

namespace app\controllers;

use app\rest\ActiveController;
use Google_Client;
use Google_Service_Calendar;

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
        $client->setScopes(Google_Service_Calendar::CALENDAR_READONLY);
        $client->setAuthConfig('credentials.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        return $client->createAuthUrl();
    }
}
