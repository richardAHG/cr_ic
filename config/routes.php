<?php

return [
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => [
            'email' => 'emails'
        ],
        'extraPatterns' => [
            'GET proximos-eventos' => 'proximosEventos',
            'GET consultar-participacion' => 'consultarParticipacion',
            'GET asistencia-cancelada' => 'asistenciaCancelada',
            'GET asistencia-confirmada' => 'asistenciaConfirmada',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => [
            'user' => 'users'
        ],
        'extraPatterns' => [
            // 'POST validate' => 'validate',
            'POST save-event' => 'saveEvent',
            'POST validate-token' => 'validateToken',
            'GET download' => 'download',
            'POST question' => 'question',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => [
            'calendar' => 'calendar'
        ],
        'extraPatterns' => [
            // 'POST validate' => 'validate',
            'GET google' => 'gmail',
            'GET office365' => 'outlook',
            'POST google-save'=> 'gmailsave',
            'POST office365-save'=> 'outlooksave'
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => [
            'oauth' => 'oauth'
        ],
        'extraPatterns' => [
            // 'POST validate' => 'validate',
            'GET microsoft' => 'outlook',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => [
            'event' => 'events'
        ],
        'extraPatterns' => [
            'GET complete' => 'complete',
            'GET user' => 'byUser',
            'POST view' => 'eviews',
            'PUT view/{id}' => 'eviewsup',
            'PUT {id}/active' => 'eactive',
            'GET list-viewers' => 'elistviewers',
            'GET list-viewers/download' => 'elistviewdownload',
        ],
        'tokens' => [
            '{id}' => '<id:\\d+>',
            '{idv}' => '<idv:\\d+>',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => [
            'participant' => 'participants'
        ],
        'extraPatterns' => [
            'POST {id}' => 'update_',
        ],
    ]
];
