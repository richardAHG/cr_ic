<?php

namespace app\models\query;

use DateTime;
use Yii;

class EventsQuery
{
    public static function getEventsByUser($token)
    {
        $sql = "SELECT DISTINCT event_id from users u 
                inner join user_events ue on u.id=ue.user_id 
                where token =:token";
        return Yii::$app->db->createCommand($sql)->bindParam(':token', $token)->queryColumn();
    }

    public static function getEventsBySpeaker($speaker_id)
    {
        $sql = "SELECT DISTINCT event_id from events_speakers es 
                where participant_id =:speaker ";
        return Yii::$app->db->createCommand($sql)->bindParam(':speaker', $speaker_id)->queryColumn();
    }

    public static function getEventsByIds($events = [])
    {
        $eventos = [];
        foreach ($events as $key => $val) {
            $date = new DateTime($val["date"]);
            $presentations = DiaryQuery::getPresentations($val['id']);
            $moderator = DiaryQuery::getModerator($val['id']);
            $speaker = DiaryQuery::getSpeaker($val['id']);
            [$type,$type_en]=explode("|",$val['type_event']);
            $eventoformateado = [
                "id" => $val["id"],
                "title" => $val["title"],
                "date_string" => $val['date_string'],
                "date_string_en" => $val['date_string_en'],
                "date_string_large" => $val['date_string_large'],
                "date_string_large_en" => $val['date_string_large_en'],
                "title_en" => $val['title_en'],
                "description" => $val['description'],
                "date" => $val['date'],
                "city" => $val['city'],
                // "diary_id" => $val['diary_id'],
                "type_id" => $val['type_event'],
                "type" => $type,
                "type_en" => $type_en,
                "presentations" => $presentations,
                "speaker" => $speaker,
                "moderator" => $moderator,
            ];
            $eventos[$date->format("Y-m-d")][] = $eventoformateado;
        }
        // print_r($eventos); die();
        $data = [];
        foreach ($eventos as $fecha => $items) {
            // print_r($items); die();
            $item = end($items);
            $data[] = [
                "date" => $fecha,
                "date_string" => $item['date_string'],
                "date_string_en" => $item['date_string_en'],
                "date_string_large" => $item['date_string_large'],
                "date_string_large_en" => $item['date_string_large_en'],
                "events" => $items
            ];
        }
        return $data;
    }

    public static function getEventById($ids = false)
    {
        return (new \yii\db\Query())
            ->select([
                'e.id', 'e.title', 'e.title_en', 'e.description', 'e.`date`', 'e.city', 'e.type_id',
                'e.date_string', 'e.date_string_en', 'e.date_string_large', 'e.date_string_large_en', 'p.name as type_event'
            ])
            ->from('events e')
            ->join(
                'INNER JOIN',
                'parameters p',
                "p.value =e.type_id and p.`group` ='TYPE_MEET'"
            )
            ->where(['e.condition' => 1])
            ->andWhere(['in', 'e.id', $ids])
            ->all();
    }

    public static function getEvent()
    {
        return (new \yii\db\Query())
            ->select([
                'e.id', 'e.title', 'e.title_en', 'e.description', 'e.`date`', 'e.city', 'e.type_id',
                'e.date_string', 'e.date_string_en', 'e.date_string_large', 'e.date_string_large_en', 'p.name as type_event'
            ])
            ->from('events e')
            ->join(
                'INNER JOIN',
                'parameters p',
                "p.value =e.type_id and p.`group` ='TYPE_MEET'"
            )
            ->where(['e.condition' => 1])
            ->all();
    }
}
