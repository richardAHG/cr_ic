<?php

namespace app\controllers;

use yii\rest\ActiveController;

class EmailsController extends ActiveController
{
    public $modelClass ='app\models\UsersModel';

    public function actions()
    {
        $actions=parent::actions();
        $actions['index']['class'] = '';
        $actions['create']['class'] = '';
        $actions['update']['class'] = '';
        $actions['view']['class'] = '';
        $actions['delete']['class'] = '';

        $actions['proximosEventos']=[
            'class'=>'app\controllers\email\ProximosEventosAction',
            'modelClass'=>'app\models\UsersModel'
        ];
        $actions['consultarParticipacion']=[
            'class'=>'app\controllers\email\ConsultarParticipacionAction',
            'modelClass'=>'app\models\UsersModel'
        ];
        $actions['asistenciaCancelada']=[
            'class'=>'app\controllers\email\AsistenciaCanceladaAction',
            'modelClass'=>'app\models\UsersModel'
        ];
        $actions['asistenciaConfirmada']=[
            'class'=>'app\controllers\email\AsistenciaConfirmadaAction',
            'modelClass'=>'app\models\UsersModel'
        ];
        return $actions;
    }
}
