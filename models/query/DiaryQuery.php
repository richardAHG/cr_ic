<?php

namespace app\models\query;

use Yii;

class DiaryQuery
{
    public static function getAllComplete()
    {
        $sql = "SELECT d.id,d.date,d.date_string ,d.date_string_en ,
        e2.id as event_id,e2.title ,e2.title_en ,e2.description ,e2.`date`as dateevent,e2.city,e2.diary_id ,e2.type_id ,
        p4.name as typemeet,
        es.id as speaker_id ,p2.name as speaker,p2.photo as photospeaker,
        em.id as moderator_id ,p3.name as moderator,p3.photo as photomoderator
        FROM diary d
        inner join events e2 on d.id = e2.diary_id 
        inner join events_speakers es on e2.id =es.event_id 
        inner join participants p2 on p2.id =es.participant_id 
        inner join events_moderators em on e2.id =em.event_id
        inner join participants p3 on p3.id =em.participant_id
        inner join parameters p4 on e2.type_id =p4.value and p4.`group` ='TYPE_MEET'
        ORDER by d.id ,e2.id";
        return Yii::$app->db->createCommand($sql)->queryAll();
    }

    public static function getSpeaker($event_id)
    {
        return (new \yii\db\Query())
            ->select(['p.name', 'photo', 'type_id', 'p2.name as typepartipant'])
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

    public static function getModerator($event_id)
    {
        return (new \yii\db\Query())
            ->select(['p.name', 'photo', 'type_id', 'p2.name as typepartipant'])
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
}
