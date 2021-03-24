<?php

namespace app\controllers;

use yii\rest\ActiveController;

class CorreosController extends ActiveController
{
    public $modelClass ='app\models\UsuariosModel';

    public function actions()
    {
        $actions=parent::actions();
        $actions['index']['class'] = '';
        $actions['create']['class'] = '';
        $actions['update']['class'] = '';
        $actions['view']['class'] = '';
        $actions['delete']['class'] = '';

        $actions['proximosEventos']=[
            'class'=>'app\controllers\correo\ProximosEventosAction',
            'modelClass'=>'app\models\UsuariosModel'
        ];
        $actions['consultarParticipacion']=[
            'class'=>'app\controllers\correo\ConsultarParticipacionAction',
            'modelClass'=>'app\models\UsuariosModel'
        ];
        $actions['asistenciaCancelada']=[
            'class'=>'app\controllers\correo\AsistenciaCanceladaAction',
            'modelClass'=>'app\models\UsuariosModel'
        ];
        $actions['asistenciaConfirmada']=[
            'class'=>'app\controllers\correo\AsistenciaConfirmadaAction',
            'modelClass'=>'app\models\UsuariosModel'
        ];
        return $actions;
    }
}
