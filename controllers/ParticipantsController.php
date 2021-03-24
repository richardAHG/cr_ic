<?php

namespace app\controllers;

use app\rest\ActiveController;

class ParticipantsController extends ActiveController
{
    public $modelClass ='app\models\ParticipantsModel';

    public function actions()
    {
        $actions=parent::actions();
        $actions['update']['class'] = 'app\controllers\participants\UpdateAction';
        $actions['create']['class'] = 'app\controllers\participants\CreateAction';

        return $actions;
    }
}
