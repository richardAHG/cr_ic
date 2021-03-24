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

        // $query = $modelClass::find()
        //     ->andWhere([
        //         "condition" => 1
        //     ]);
        // $data = DiaryQuery::getAllComplete();
        // print_r($data); die();
        $info = [];
        $agenda_id = 0;
        $evento_id = 0;
        //traer todas agendas
        //obtengo evento por id agenda
        //optengo speaker y moderador por evento

        $agenda = DiaryModel::find()
            ->where(['condition' => 1])
            ->all();

        // print_r($agenda); die();
        $data = [];
        $events = [];
        foreach ($agenda as $key => $value) {
            // print_r($value); die();
            $data[$key] = [
                "id" => $value['id'],
                "date" => $value['date'],
                "date_string" => $value['date_string'],
                "date_string_en" => $value['date_string_en']
            ];
            $evento = EventsModel::find()
                ->where(['condition' => 1, 'diary_id' => $value['id']])
                ->all();
            // print_r($evento); die();
            $events = [];
            foreach ($evento as $key2 => $item) {
                // print_r($value); die();
                $speaker = DiaryQuery::getSpeaker($item['id']);
                $moderator = DiaryQuery::getModerator($item['id']);
                $presentations = DiaryQuery::getPresentations($item['id']);

                $events[] = [
                    'id' => $item['id'],
                    'title' => $item['title'],
                    'title_en' => $item['title_en'],
                    'description' => $item['description'],
                    'date' => $item['date'],
                    'city' => $item['city'],
                    'city' => $item['city'],
                    'type_id' => $item['type_id'],
                    'diary_id' => $item['diary_id'],
                    'presentations' => $presentations,
                    'speakers' => $speaker,
                    'moderator' => $moderator
                ];
            }
            $data[$key]['events'] = $events;
        }
        return $data;
        print_r(json_encode($data));
        die();

        // foreach ($data as $key => $value) {
        //     // print_r($value);
        //     // die();
        //     // if ($value['id'] != $agenda_id) {
        //     //     $agenda_id = $value['id'];
        //     // }
        //     $info[$value['id']] = [
        //         "id" => $value['id'],
        //         "date" => $value['date'],
        //         "date_string" => $value['date_string'],
        //         "date_string_en" => $value['date_string_en'],
        //     ];
        //     $info[$value['id']]['events'][$value['event_id']][] = [
        //         "id" => $value['event_id'],
        //         "title" => $value['title'],
        //         "title_en" => $value['title_en'],
        //         "description" => $value['description'],
        //         "type" => $value['typemeet'],
        //         "date" => $value['dateevent'],
        //         "city" => $value['city'],
        //     ];
        //     // $info[$value['id']][$value['event_id']]['speakers'][] = [
        //     //     "id" => $value['speaker_id'],
        //     //     "name" => $value['speaker'],
        //     //     "photo" => $value['photospeaker']
        //     // ];
        //     // $info[$value['id']][$value['event_id']]['moderators'][] = [
        //     //     "id" => $value['moderator_id'],
        //     //     "name" => $value['moderator'],
        //     //     "photo" => $value['photomoderator']
        //     // ];
        // }
        // print_r(json_encode($info));
        // die();
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
