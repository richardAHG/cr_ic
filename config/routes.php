<?php

return [
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => [
            'user' => 'users',
            'diary'=> 'diary',
            'participant'=> 'participants',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => [
            'email' => 'emails'
        ],
        'extraPatterns' => [
            'POST proximos-eventos' => 'proximosEventos',
            'POST consultar-participacion' => 'consultarParticipacion',
            'POST asistencia-cancelada' => 'asistenciaCancelada',
            'POST asistencia-confirmada' => 'asistenciaConfirmada',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => [
            'event' => 'events'
        ],
        'extraPatterns' => [
            'POST proximos-eventos' => 'proximosEventos',
            'POST consultar-participacion' => 'consultarParticipacion',
            'POST asistencia-cancelada' => 'asistenciaCancelada',
            'POST asistencia-confirmada' => 'asistenciaConfirmada',
        ],
    ]
];
