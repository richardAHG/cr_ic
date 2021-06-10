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
            'GET asistencia-cancelada/{id}' => 'asistenciaCancelada',
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
            'POST gmail-save'=> 'gmailsave',
            'POST outlook-save'=> 'outlooksave'
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
        ],
    ]
];
