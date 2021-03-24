<?php

namespace app\controllers;

use app\rest\ActiveController;

class EventsController extends ActiveController
{
    public $modelClass ='app\models\custom\EventsCustomModel';

    public function actions()
    {
        $actions=parent::actions();
        // $actions['update']['class'] = 'app\controllers\usuario\UpdateAction';
        $actions['create']['class'] = 'app\controllers\event\CreateAction';

        return $actions;
    }
}
