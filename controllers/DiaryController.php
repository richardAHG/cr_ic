<?php

namespace app\controllers;

use app\rest\ActiveController;

class DiaryController extends ActiveController
{
    public $modelClass ='app\models\DiaryModel';

    public function actions()
    {
        $actions=parent::actions();
        $actions['update']['class'] = 'app\controllers\diary\UpdateAction';
        $actions['create']['class'] = 'app\controllers\diary\CreateAction';
        $actions['index']['class'] = 'app\controllers\diary\IndexAction';
        
        return $actions;
    }
}
