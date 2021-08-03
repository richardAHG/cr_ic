<?php

namespace app\controllers\users;

use app\rest\Action;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * IndexAction implementa el punto final de la API para enumerar varios modelos
 * 
 * @author Richard Huaman <richard21hg92@gmail.com>
 */
class IndexAction extends Action
{
    /**
     * @return ActiveDataProvider
     */
    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        return $this->prepareDataProvider();
    }

    /**
     * Prepares the data provider that should return the requested collection of the models.
     * @return ActiveDataProvider
     */
    protected function prepareDataProvider()
    {
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }

        /* @var $modelClass \yii\db\BaseActiveRecord */
        $modelClass = $this->modelClass;

        $data = $modelClass::find()
            ->select(['name', 'last_name', 'email', 'sent'])
            ->andWhere([
                "condition" => 1
                // "sent" => 1
            ])->all();

        foreach ($data as $key => $value) {
            $rpta = ($value['sent'] == 1 ? 'Si' : 'No');
            $data[$key]['sent'] = $rpta;
        }
        return $data;
        // return Yii::createObject([
        //     'class' => ActiveDataProvider::className(),
        //     'query' => $query,
        //     'pagination' => [
        //         'params' => $requestParams,
        //     ],
        //     'sort' => [
        //         'params' => $requestParams,
        //     ],
        // ]);
    }
}
