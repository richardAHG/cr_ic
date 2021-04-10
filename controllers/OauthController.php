<?php

namespace app\controllers;

use app\helpers\CalendarGoogle;
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
        
        $user = 14;
        $today = new DateTime('now');
        $callGoogle = new CalendarGoogleModel();
        $callGoogle->usuario_id = $user;
        $callGoogle->token = json_encode($accessToken);
        $callGoogle->date_created = $today->format('Y-m-d');

        if (!$callGoogle->save()) {
            throw new BadRequestHttpException("error al guardar los datos");
        }

        //iniciamos proceso de registro en el calendario

        $linkResult = CalendarGoogle::crearEvento($user);
        echo $linkResult;

        //echo "<pre>";print_r($accessToken);die();
        //$client->setAccessToken($accessToken);
        //print_r($client->getAccessToken());

    }
}
