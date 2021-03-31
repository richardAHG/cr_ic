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

        $actions['complete'] = [
            'class' => 'app\controllers\event\IndexcompleteAction',
            'modelClass' => 'app\models\EventsModel'
        ];

        $actions['byUser'] = [
            'class' => 'app\controllers\event\ViewCompleteAction',
            'modelClass' => 'app\models\EventsModel'
        ];

        return $actions;
    }
}
