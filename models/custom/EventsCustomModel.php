<?php

namespace app\models\custom;

use app\models\EventsModel;

class EventsCustomModel extends EventsModel
{
    public function extraFields()
    {
        return ['speaker','moderator'];
    }

    public function getSpeaker()
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
            ->andWhere(['es.event_id' => $this->id])
            ->all();
    }

    public function getModerator()
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
            ->andWhere(['em.event_id' => $this->id])
            ->all();
    }
}
