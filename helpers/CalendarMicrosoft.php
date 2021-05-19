<?php

namespace app\helpers;

use app\models\query\EventsQuery;
use app\models\UsersModel;
use DateTime;
use DateTimeZone;
use Exception;
use Yii;

class CalendarMicrosoft
{
    public static function getTokenAutorize($codeOutlook)
    {
        // $codeOutlook = Yii::$app->getRequest()->get('code', false);
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
                'redirect_uri' => 'https://credicorpcapitalconference.web.app/oauth',
                'code' => $codeOutlook,
                'client_secret' => $clientSecret,
                'grant_type' => 'authorization_code',
            ],
        ])->getBody()->getContents());

        $accessToken = $token->access_token;
        return $accessToken;
    }

    public static function crearEvento($params)
    {
        $userId=$params['userId'];
        $code=$params['code'];
        $token=$params['tokenUser'];
        $user = UsersModel::find()
            ->where(['condition' => 1, 'id' => $userId])
            ->one();
        if (!$user) {
            throw new Exception("El usuairo no existe");
        }

        $ids = EventsQuery::getEventsByUser($user->token);

        $evento = EventsQuery::getEventById($ids);

        $data = EventsQuery::getEventsByIds($evento);

        $guzzle = new \GuzzleHttp\Client();
        $accessToken=self::getTokenAutorize($code);

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
                
                $titulo=$value['title'];
                $ubicación = $value['city'];
                $descripcion=$value['description'];

                $create = $guzzle->post('https://graph.microsoft.com/v1.0/me/events', [
                    'headers' => [
                        'Authorization' => "Bearer {$accessToken}",
                        'Content-Type' => 'application/json'
                    ],
                    'json' => [
                        "subject" => "Eventos - CREDICORP CAPITAL",
                        "body" => [
                            "contentType" => "HTML",
                            "content" => "<h1>$titulo</<h2>
                            <p>$ubicación</p>
                            <p>Descripción: $descripcion</p>
                            <p><a href='https://credicorpcapitalconference.web.app/event'>Acceder</a></p>
                            "
                        ],
                        "start" => [
                            "dateTime" => $date,
                            "timeZone" => "America/Bogota"
                        ],
                        "end" => [
                            "dateTime" => $date,
                            "timeZone" => "America/Bogota"
                        ],
                    ]
                ]);
        
                $response = json_decode($create->getBody()->getContents());
            }
        }

        return compact("response");
    }

    public function testSaveCalendarOutlook()
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
                'redirect_uri' => 'https://rhg-sandbox.com/oauth/microsoft',
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
}
