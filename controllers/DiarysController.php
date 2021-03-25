<?php

namespace app\controllers;

use app\rest\ActiveController;

class DiarysController extends ActiveController
{
    public $modelClass = 'app\models\custom\DiarycustomModel';

    // public function behaviors()
    // {
    //     $behaviors = parent::behaviors();
    //     unset($behaviors['authenticator']);
    //     // add CORS filter
    //     $behaviors['corsFilter'] = [
    //         'class' => '\yii\filters\Cors',
    //         'cors' => [
    //             'Origin' => ['*'],
    //             'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
    //             'Access-Control-Request-Headers' => ['*'],
    //         ],
    //     ];
    //     return $behaviors;
    // }

    public function actions()
    {
        $actions = parent::actions();
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
