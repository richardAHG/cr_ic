<?php

namespace app\models\query;

use app\models\EventsModel;
use DateTime;
use Yii;
use yii\web\BadRequestHttpException;

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

    public static function getEventsCheck($events = [], $idEventByUser)
    {
        $eventos = [];
        foreach ($events as $key => $val) {
            $exist = in_array($val['id'], $idEventByUser);
            $date = new DateTime($val["date"]);
            $presentations = self::getPresentations($val['id']);
            $moderator = self::getModerator($val['id']);
            $speaker = self::getSpeaker($val['id']);
            [$type, $type_en] = explode("|", $val['type_event']);
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
                "exist" => $exist
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

    public static function getEventsByIds($events = [])
    {
        $eventos = [];
        foreach ($events as $key => $val) {
            $date = new DateTime($val["date"]);
            $presentations = self::getPresentations($val['id']);
            $moderator = self::getModerator($val['id']);
            $speaker = self::getSpeaker($val['id']);
            [$type, $type_en] = explode("|", $val['type_event']);
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

    public static function getEventById($ids)
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

    public static function getModerator($event_id)
    {
        return (new \yii\db\Query())
            ->select(['u2.id', 'u2.name', 'photo'])
            ->from('events_moderators em')
            ->join(
                'INNER JOIN',
                'users u2',
                'em.participant_id =u2.id'
            )
            ->where(['em.condition' => 1])
            ->andWhere(['em.event_id' => $event_id])
            ->all();
    }

    public static function getModeratorComplete($event_id)
    {
        return (new \yii\db\Query())
            ->select([
                'u2.id', 'u2.name', 'last_name', 'company', 'nationality_id', 'photo', 'position', 'position_en',
                'description', 'description_en', 'p2.name as nationality'
            ])
            ->from('events_moderators em')
            ->join(
                'INNER JOIN',
                'users u2',
                'em.participant_id =u2.id'
            )
            ->join(
                'INNER JOIN',
                'parameters p2',
                "p2.value =u2.nationality_id and p2.`group` ='NATIONALITY'"
            )
            ->where(['em.condition' => 1])
            ->andWhere(['em.event_id' => $event_id])
            ->all();
    }

    public static function getSpeaker($event_id)
    {
        return (new \yii\db\Query())
            ->select(['u2.id', 'u2.name', 'photo'])
            ->from('events_speakers es')
            ->join(
                'INNER JOIN',
                'users u2',
                'es.participant_id =u2.id'
            )
            ->where(['es.condition' => 1])
            ->andWhere(['es.event_id' => $event_id])
            ->all();
    }
    public static function getSpeakerComplete($event_id)
    {
        return (new \yii\db\Query())
            ->select([
                'u2.id', 'u2.name', 'last_name', 'company', 'nationality_id', 'photo', 'position', 'position_en',
                'description', 'description_en', 'p2.name as nationality'
            ])
            ->from('events_speakers es')
            ->join(
                'INNER JOIN',
                'users u2',
                'es.participant_id =u2.id'
            )
            ->join(
                'INNER JOIN',
                'parameters p2',
                "p2.value =u2.nationality_id and p2.`group` ='NATIONALITY'"
            )
            ->where(['es.condition' => 1])
            ->andWhere(['es.event_id' => $event_id])
            ->all();
    }
    public static function getPresentations($event_id)
    {
        return (new \yii\db\Query())
            ->select(['p.id', 'p.name'])
            ->from('presentations p')
            ->join(
                'INNER JOIN',
                'events e',
                'p.event_id =e.id'
            )
            ->where(['e.condition' => 1])
            ->andWhere(['e.id' => $event_id])
            ->all();
    }

    public static function eventExist($event)
    {
        $eventIds = EventsModel::find()
            ->select(['id'])
            ->where(['condition' => 1])
            ->column();

        //validar existencia de ids
        $eventIncorrect = array_diff($event, $eventIds);
        if (!empty($eventIncorrect)) {
            throw new BadRequestHttpException("Eventos enviados no existen en la Base de Datos,verificar datos");
        }
    }
}
