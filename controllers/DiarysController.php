<?php

namespace app\controllers;

use app\rest\ActiveController;

class DiarysController extends ActiveController
{
    public $modelClass ='app\models\custom\DiarycustomModel';

    public function actions()
    {
        $actions=parent::actions();
        $actions['update']['class'] = 'app\controllers\diary\UpdateAction';
        $actions['create']['class'] = 'app\controllers\diary\CreateAction';

        $actions['complete'] = [
            'class' => 'app\controllers\diary\IndexcompleteAction',
            'modelClass' => 'app\models\DiaryModel'
        ];
        $actions['byUser'] = [
            'class' => 'app\controllers\diary\ViewCompleteAction',
            'modelClass' => 'app\models\DiaryModel'
        ];
        
        return $actions;
    }
}
