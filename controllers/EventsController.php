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

        $actions['eviews'] = [
            'class' => 'app\controllers\event\EventwiewsAction',
            'modelClass' => 'app\models\EventViewModel'
        ];

        $actions['eviewsup'] = [
            'class' => 'app\controllers\event\EventwiewsupAction',
            'modelClass' => 'app\models\EventViewModel'
        ];

        $actions['eactive'] = [
            'class' => 'app\controllers\event\UactiveeventstartAction',
            'modelClass' => 'app\models\EventsModel'
        ];

        $actions['elistviewers'] = [
            'class' => 'app\controllers\event\IlistviewersAction',
            'modelClass' => 'app\models\EventViewModel'
        ];

        $actions['elistviewdownload'] = [
            'class' => 'app\controllers\event\IlistviewersdownloadAction',
            'modelClass' => 'app\models\EventViewModel'
        ];
        return $actions;
    }
}
