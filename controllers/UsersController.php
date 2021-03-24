<?php

namespace app\controllers;

use app\rest\ActiveController;

class UsersController extends ActiveController
{
    public $modelClass ='app\models\UsersModel';

    public function actions()
    {
        $actions=parent::actions();
        $actions['update']['class'] = 'app\controllers\users\UpdateAction';
        $actions['create']['class'] = 'app\controllers\users\CreateAction';

        return $actions;
    }
}
