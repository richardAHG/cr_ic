<?php

namespace app\helpers;

use app\models\query\EventsQuery;
use app\models\UsersModel;
use DateTime;
use DateTimeZone;
use Exception;

class CalendarMicrosoft
{
    public function getEvents($userId)
    {
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
                    'description' => $value['description'] . "<a href='https://credicorpcapitalconference.web.app/event'>Acceder</a>",
                    'start' => $start,
                    'end' => $end,
                    // 'attendees' => $arraySpeakers,
                    'reminders' => [
                        'useDefault' => FALSE,
                        'overrides' => ['method' => 'popup', 'minutes' => 10]
                    ]
                ];

                // $event = new Google_Service_Calendar_Event($infoEvent);
                // $calendarId = 'primary';
                // $resultEvent = $service->events->insert($calendarId, $event);


                // echo $resultEvent->htmlLink;
            }
        }

        //deshabilitamos el token de usuario de la table user_events
        $calendarGoogle->condition = 0;
        if (!$calendarGoogle->save()) {
            throw new BadRequestHttpException("Error al dar de baja el token de usuario");
        }

        return $resultEvent->htmlLink;
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
