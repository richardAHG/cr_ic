<?php

namespace app\controllers\diary;

use app\models\DiaryModel;
use app\models\EventsModel;
use app\models\query\DiaryQuery;
use app\rest\Action;
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
        $agenda = DiaryQuery::getByUser($requestParams['token']);
        // print_r($agenda); die();
        $data = [];
        $events = [];
        
        foreach ($agenda as $key => $value) {
            // print_r($value); die();
            $data[$key] = [
                "id" => $value['diary_id'],
                "date" => $value['diary_date'],
                "date_string" => $value['date_string'],
                "date_string_en" => $value['date_string_en']
            ];

            $speaker = DiaryQuery::getSpeaker($value['id']);
            $moderator = DiaryQuery::getModerator($value['id']);
            $presentations = DiaryQuery::getPresentations($value['id']);

            $events[] = [
                'id' => $value['id'],
                'title' => $value['title'],
                'title_en' => $value['title_en'],
                'description' => $value['description'],
                'date' => $value['date'],
                'city' => $value['city'],
                'city' => $value['city'],
                'type_id' => $value['type_id'],
                'diary_id' => $value['diary_id'],
                'presentations' => $presentations,
                'speakers' => $speaker,
                'moderator' => $moderator
            ];

            // $event = EventsModel::findOne([
            //     'condition' => 1,
            //     'id' => $value['event_id']
            // ]);
            // $events[]=$event;
            $data[$key]['event'] = $events;
            $events = [];
        }
        return $data;
    }
}
