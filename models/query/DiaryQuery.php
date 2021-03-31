<?php

namespace app\models\query;

use Yii;

class DiaryQuery
{
    public static function getAllComplete()
    {
        // $sql = "SELECT d.id,d.date,d.date_string ,d.date_string_en ,
        // e2.id as event_id,e2.title ,e2.title_en ,e2.description ,e2.`date`as dateevent,e2.city,e2.diary_id ,e2.type_id ,
        // p4.name as typemeet,
        // es.id as speaker_id ,p2.name as speaker,p2.photo as photospeaker,
        // em.id as moderator_id ,p3.name as moderator,p3.photo as photomoderator
        // FROM diary d
        // inner join events e2 on d.id = e2.diary_id 
        // inner join events_speakers es on e2.id =es.event_id 
        // inner join participants p2 on p2.id =es.participant_id 
        // inner join events_moderators em on e2.id =em.event_id
        // inner join participants p3 on p3.id =em.participant_id
        // inner join parameters p4 on e2.type_id =p4.value and p4.`group` ='TYPE_MEET'
        // ORDER by d.id ,e2.id";
        $sql = "SELECT d.id,d.date,d.date_string ,d.date_string_en ,date_string_large,date_string_large_en,
                e2.id as event_id,e2.title ,e2.title_en ,e2.description ,e2.`date`as dateevent,e2.city,e2.diary_id ,e2.type_id ,
                p4.name as type
                FROM diary d
                inner join events e2 on d.id = e2.diary_id 
                inner join parameters p4 on e2.type_id =p4.value and p4.`group` ='TYPE_MEET'
                ORDER by d.id ,e2.id";
        return Yii::$app->db->createCommand($sql)->queryAll();
    }

    public static function getSpeaker_bk($event_id)
    {
        return (new \yii\db\Query())
            ->select(['', 'p.name', 'photo', 'type_id', 'p2.name as typepartipant'])
            ->from('events_speakers es')
            ->join(
                'INNER JOIN',
                'participants p',
                'es.participant_id =p.id'
            )
            ->join(
                'INNER JOIN',
                'parameters p2',
                "p2.value =p.type_id and p2.`group` ='TYPE_PARTICIPANT'"
            )
            ->where(['es.condition' => 1])
            ->andWhere(['es.event_id' => $event_id])
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

    public static function getModerator_bk($event_id)
    {
        return (new \yii\db\Query())
            ->select(['p.name', 'photo'])
            ->from('events_moderators em')
            ->join(
                'INNER JOIN',
                'participants p',
                'em.participant_id =p.id'
            )
            ->join(
                'INNER JOIN',
                'parameters p2',
                "p2.value =p.type_id and p2.`group` ='TYPE_PARTICIPANT'"
            )
            ->where(['em.condition' => 1])
            ->andWhere(['em.event_id' => $event_id])
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

    public static function getByUser($token)
    {
        $sql = "SELECT e.*,d.date as diary_date,d.date_string,d.date_string_en,d.date_string_large,d.date_string_large_en, 
            p2.name as type
            from user_events ue
            inner join events e on ue.event_id =e.id 
            inner join diary d on d.id =e.diary_id 
            inner join users u on u.id =ue.user_id 
            inner join parameters p2 on p2.value =e.type_id and p2.`group` ='TYPE_MEET'
            where u.token = :token and token<>''";
        return Yii::$app->db->createCommand($sql)->bindParam(':token', $token)->queryAll();
    }

    public static function getDiaryByUser($token)
    {
        //agenda del usuario
        $agenda = self::getByUser($token);
        $data = [];
        $events = [];

        foreach ($agenda as $key => $value) {
            // print_r($value); die();
            [$type, $type_en] = explode('|', $value['type']);
            $data[$key] = [
                "id" => $value['diary_id'],
                "date" => $value['diary_date'],
                "date_string" => $value['date_string'],
                "date_string_en" => $value['date_string_en'],
                "date_string_large" => $value['date_string_large'],
                "date_string_large_en" => $value['date_string_large_en']
            ];

            $speaker = self::getSpeaker($value['id']);
            $moderator = self::getModerator($value['id']);
            $presentations = self::getPresentations($value['id']);

            $events[] = [
                'id' => $value['id'],
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
