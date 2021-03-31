<?php

namespace app\controllers\event;

use app\models\EventsModel;
use app\models\query\DiaryQuery;
use app\models\query\EventsQuery;
use app\rest\Action;
use DateTime;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * IndexAction implementa el punto final de la API para enumerar varios modelos
 * 
 * @author Richard Huaman <richard21hg92@gmail.com>
 */
class ViewCompleteAction extends Action
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

        // $query = $modelClass::find()
        //     ->andWhere([
        //         "condition" => 1
        //     ]);

        //agenda del usuario
        $ids = EventsQuery::getEventsByUser($requestParams['token']);
        // $evento = EventsModel::find()
        //     ->where(['condition' => 1])
        //     ->andWhere(['in', 'id', $ids])
        //     ->all();
        $evento=EventsQuery::getEvent($ids);
        return EventsQuery::getEventsByIds($evento);
    }
}
