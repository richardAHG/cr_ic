<?php

namespace app\models\custom;

use app\models\EventsModel;
use app\models\query\EventsQuery;
use Yii;

class EventsCustomModel extends EventsModel
{
    public function extraFields()
    {
        return ['speaker', 'moderator', 'details','speakerComplete','moderatorComplete'];
    }

    public function getSpeaker()
    {
        return (new \yii\db\Query())
            ->select(['p.name', 'photo_id', 'type_id', "substr(f.route,position('media/' in f.route)) as route",'p2.name as typepartipant'])
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
            ->join(
                'INNER JOIN',
                'files f',
                'f.id =p.photo_id and f.status =1'
            )
            ->where(['es.condition' => 1])
            ->andWhere(['es.event_id' => $this->id])
            ->all();
    }
    public function getSpeakerComplete()
    {
        return EventsQuery::getSpeakerComplete($this->id);
    }

    public function getModerator()
    {
        return (new \yii\db\Query())
            ->select(['p.name', 'photo_id', "substr(f.route,position('media/' in f.route)) as route",'type_id', 'p2.name as typepartipant'])
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
            ->join(
                'INNER JOIN',
                'files f',
                'f.id =p.photo_id and f.status =1'
            )
            ->where(['em.condition' => 1])
            ->andWhere(['em.event_id' => $this->id])
            ->all();
    }

    public function getModeratorComplete()
    {
        return EventsQuery::getModeratorComplete($this->id);
    }

    public function getDetails()
    {
        $requestParams = Yii::$app->getRequest()->getQueryParams();

        // $eventIds = UsuarioQuery::getEventsByModerator($requestParams['id']);
        $evento = EventsQuery::getEventById($this->id);
        // print_r($this->id);
        // print_r($requestParams['id']);
        // print_r($eventIds);die();
        // // $evento = EventsQuery::getEventById($eventIds);
        // print_r($evento); die();
        return EventsQuery::getEventsByIds($evento);
    }
}
