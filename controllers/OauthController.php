<?php

namespace app\controllers;

use app\models\CalendarGoogleModel;
use DateTime;
use Exception;
use yii\web\Controller;
use Google_Client;
use Google_Service_Calendar;
use Yii;
use yii\web\BadRequestHttpException;

class OauthController extends Controller
{

    public function actionIndex()
    {
        // $requestParams = Yii::$app->getRequest()->getQueryParams();
        $code = Yii::$app->getRequest()->get('code', false);
        $client = new Google_Client();
        $client->setAuthConfig('credentials.json');
        // echo $code;die();

        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($code);
                $client->setAccessToken($accessToken);
    
                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }
        }

        $today = new DateTime('now');
        $callGoogle= new CalendarGoogleModel();
        $callGoogle->usuario_id=1;
        $callGoogle->token = json_encode($accessToken);
        $callGoogle->date_created = $today->format('Y-m-d');
        print_r($callGoogle); die();
        if ($callGoogle->save()) {
            throw new BadRequestHttpException("error al guardar los datos");
        }

        // echo "<pre>";print_r($accessToken);die();
        $client->setAccessToken($accessToken);
        print_r($client->getAccessToken());
        die();
    }
}
