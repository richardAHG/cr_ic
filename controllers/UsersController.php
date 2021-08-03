<?php

namespace app\controllers;

use app\rest\ActiveController;

class UsersController extends ActiveController
{
    public $modelClass ='app\models\custom\UsersCustomModel';

    public function actions()
    {
        $actions=parent::actions();
        $actions['update']['class'] = 'app\controllers\users\UpdateAction';
        $actions['create']['class'] = 'app\controllers\users\CreateAction';
        $actions['index']['class'] = 'app\controllers\users\IndexAction';
        
        // $actions['validate'] = [
        //     'class' => 'app\controllers\users\ValidateAction',
        //     'modelClass' => 'app\models\UsersModel'
        // ];

        $actions['saveEvent'] = [
            'class' => 'app\controllers\users\events\CreateAction',
            'modelClass' => 'app\models\UserEventsModel'
        ];

        $actions['validateToken'] = [
            'class' => 'app\controllers\users\events\ValidateAction',
            'modelClass' => 'app\models\UserEventsModel'
        ];

        $actions['download'] = [
            'class' => 'app\controllers\users\IndexdowloadAction',
            'modelClass' => 'app\models\UserModel'
        ];

        return $actions;
    }
}
