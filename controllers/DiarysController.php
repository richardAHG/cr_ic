<?php

namespace app\controllers;

use app\rest\ActiveController;

class DiarysController extends ActiveController
{
    public $modelClass ='app\models\DiaryModel';

    public function actions()
    {
        $actions=parent::actions();
        $actions['update']['class'] = 'app\controllers\diary\UpdateAction';
        $actions['create']['class'] = 'app\controllers\diary\CreateAction';

         $actions['completex'] = [
            'class' => 'app\controllers\diary\IndexcompleteAction',
            'modelClass' => 'app\models\DiaryModel'
        ];
        
        return $actions;
    }
}
