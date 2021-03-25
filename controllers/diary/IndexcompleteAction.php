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
class IndexcompleteAction extends Action
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

        //traer todas agendas
        $agenda = DiaryQuery::getAllComplete();

        $data = [];
        $events = [];
        foreach ($agenda as $key => $value) {
            [$type, $type_en] = explode('|', $value['type']);
            $data[$key] = [
                "id" => $value['id'],
                "date" => $value['date'],
                "date_string" => $value['date_string'],
                "date_string_en" => $value['date_string_en'],
                "date_string_large" => $value['date_string_large'],
                "date_string_large_en" => $value['date_string_large_en']
            ];
            $speaker = DiaryQuery::getSpeaker($value['event_id']);
            $moderator = DiaryQuery::getModerator($value['event_id']);
            $presentations = DiaryQuery::getPresentations($value['event_id']);

            $events[] = [
                'id' => $value['event_id'],
                'title' => $value['title'],
                'title_en' => $value['title_en'],
                'description' => $value['description'],
                'date' => $value['date'],
                'city' => $value['city'],
                'city' => $value['city'],
                'type_id' => $value['type_id'],
                'type' => $type,
                'type_en' => $type_en,
                'diary_id' => $value['diary_id'],
                'presentations' => $presentations,
                'speakers' => $speaker,
                'moderator' => $moderator
            ];
            $data[$key]['event'] = $events;
            $events = [];
        }
        return $data;

        // //traer todas agendas
        // $agenda = DiaryModel::find()
        //     ->where(['condition' => 1])
        //     ->all();

        // $data = [];
        // $events = [];
        // foreach ($agenda as $key => $value) {
        //     $data[$key] = [
        //         "id" => $value['id'],
        //         "date" => $value['date'],
        //         "date_string" => $value['date_string'],
        //         "date_string_en" => $value['date_string_en'],
        //         "date_string_large" => $value['date_string_large'],
        //         "date_string_large_en" => $value['date_string_large_en']
        //     ];
        //     //obtengo evento por id agenda
        //     $evento = EventsModel::find()
        //         ->where(['condition' => 1, 'diary_id' => $value['id']])
        //         ->all();
        //     $events = [];
        //     foreach ($evento as $key2 => $item) {
        //         //optengo speaker y moderador por evento
        //         $speaker = DiaryQuery::getSpeaker($item['id']);
        //         $moderator = DiaryQuery::getModerator($item['id']);
        //         $presentations = DiaryQuery::getPresentations($item['id']);

        //         $events[] = [
        //             'id' => $item['id'],
        //             'title' => $item['title'],
        //             'title_en' => $item['title_en'],
        //             'description' => $item['description'],
        //             'date' => $item['date'],
        //             'city' => $item['city'],
        //             'city' => $item['city'],
        //             'type_id' => $item['type_id'],
        //             'diary_id' => $item['diary_id'],
        //             'presentations' => $presentations,
        //             'speakers' => $speaker,
        //             'moderator' => $moderator
        //         ];
        //     }
        //     $data[$key]['events'] = $events;
        // }
        // return $data;
    }
}
