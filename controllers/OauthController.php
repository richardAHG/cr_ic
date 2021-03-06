<?php

namespace app\controllers;

use app\helpers\CalendarGoogle;
use app\models\CalendarGoogleModel;
use app\rest\ActiveController;
use DateTime;
use Exception;
use Google_Client;
use Yii;
use yii\web\BadRequestHttpException;

class OauthController extends ActiveController
{
    public $modelClass = 'app\models\CalendarGoogleModel';

    public function actions()
    {
        return [];
    }

    public function actionOutlook_bk()
    {
        
        $code = Yii::$app->getRequest()->get('code', false);
        $tenantId = "consumers";
        $clientId = "d9b054b3-5380-49a5-a93b-5186f9e7b8cb";
        $clientSecret = "O.1-J9-d.gw4KhlMs1-5Mo6rgV0As41zZm";
        $guzzle = new \GuzzleHttp\Client();
        $url = 'https://login.microsoftonline.com/' . $tenantId . '/oauth2/v2.0/token';
        $token = json_decode($guzzle->post($url, [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'form_params' => [
                'client_id' => $clientId,
                'scope' => 'user.read Calendars.ReadWrite',
                'redirect_uri' => 'https://api.v2.credicorpcapitalconference.com/oauth/microsoft',
                'code' => $code,
                'client_secret' => $clientSecret,
                'grant_type' => 'authorization_code',
            ],
        ])->getBody()->getContents());
        $accessToken = $token->access_token;

        // $lists = $guzzle->get('https://graph.microsoft.com/v1.0/me/events', [
        //     'headers' => [
        //         'Authorization' => "Bearer {$accessToken}"
        //     ]
        // ]);

        $create = $guzzle->post('https://graph.microsoft.com/v1.0/me/events', [
            'headers' => [
                'Authorization' => "Bearer {$accessToken}"
            ],
            'form_params' => [
                "subject" => "Evento desde integracion",
                "body" => [
                    "contentType" => "HTML",
                    "content" => "<h1>Eco</<h2>"
                ],
                "start" => [
                    "dateTime" => "2021-04-25T12:00:00",
                    "timeZone" => "America/Bogota"
                ],
                "end" => [
                    "dateTime" => "2021-04-25T14:00:00",
                    "timeZone" => "America/Bogota"
                ],
            ]
        ]);

        $response = json_decode($create->getBody()->getContents());

        return compact("response");
    }

    public function actionOutlook()
    {
        $code = Yii::$app->getRequest()->get('code', false);
        $tenantId = "consumers";
        $clientId = "61b2eee2-4d96-47d7-8903-f2dcbdd31940";
        $clientSecret = "N0o..9ZrCZ9ir1q_CDdw71z~huWpba2_OI";
        $guzzle = new \GuzzleHttp\Client();
        $url = 'https://login.microsoftonline.com/' . $tenantId . '/oauth2/v2.0/token';
        $token = json_decode($guzzle->post($url, [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'form_params' => [
                'client_id' => $clientId,
                'scope' => 'user.read Calendars.ReadWrite',
                // 'redirect_uri' => 'https://api.v2.credicorpcapitalconference.com/oauth/microsoft',
                'redirect_uri' => 'https://credicorpcapitalconference.web.app/oauth',
                'code' => $code,
                'client_secret' => $clientSecret,
                'grant_type' => 'authorization_code',
            ],
        ])->getBody()->getContents());
        $accessToken = $token->access_token;

        // $lists = $guzzle->get('https://graph.microsoft.com/v1.0/me/events', [
        //     'headers' => [
        //         'Authorization' => "Bearer {$accessToken}"
        //     ]
        // ]);

        $create = $guzzle->post('https://graph.microsoft.com/v1.0/me/events', [
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Content-Type' => 'application/json',
            ],
            'json' => [
                "subject" => "Evento desde integracion",
                "body" => [
                    "contentType" => "HTML",
                    "content" => "<h1>Eco</<h2>"
                ],
                "start" => [
                    "dateTime" => "2021-04-25T12:00:00",
                    "timeZone" => "America/Bogota"
                ],
                "end" => [
                    "dateTime" => "2021-04-25T14:00:00",
                    "timeZone" => "America/Bogota"
                ],
            ]
        ]);

        $response = json_decode($create->getBody()->getContents());

        return compact("response");
    }

    // public function actionCreate()
    // {
    //     $code = Yii::$app->getRequest()->get('code', false);

    //     $client = new Google_Client();
    //     $client->setAuthConfig('credentials.json');

    //     if ($client->isAccessTokenExpired()) {
    //         if ($client->getRefreshToken()) {
    //             $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
    //         } else {
    //             // Exchange authorization code for an access token.
    //             $accessToken = $client->fetchAccessTokenWithAuthCode($code);
    //             $client->setAccessToken($accessToken);

    //             // Check to see if there was an error.
    //             if (array_key_exists('error', $accessToken)) {
    //                 throw new Exception(join(', ', $accessToken));
    //             }
    //         }
    //     }

    //     $user = 14;
    //     $today = new DateTime('now');
    //     $callGoogle = new CalendarGoogleModel();
    //     $callGoogle->usuario_id = $user;
    //     $callGoogle->token = json_encode($accessToken);
    //     $callGoogle->date_created = $today->format('Y-m-d');

    //     if (!$callGoogle->save()) {
    //         throw new BadRequestHttpException("error al guardar los datos");
    //     }

    //     return true;
    //     //iniciamos proceso de registro en el calendario

    //     $linkResult = CalendarGoogle::crearEvento($user);
    //     echo $linkResult;

    //     //echo "<pre>";print_r($accessToken);die();
    //     //$client->setAccessToken($accessToken);
    //     //print_r($client->getAccessToken());

    // }
}
