<?php

namespace app\models\custom;

use app\models\ParticipantsModel;
use app\models\query\EventsQuery;
use app\models\query\UsuarioQuery;
use Yii;

class ParticipantsCustomModel extends ParticipantsModel
{
    public function extraFields()
    {
        return ['file', 'EventsbySpeaker', 'EventsbyModerator'];
    }

    public function getFile()
    {
        return (new \Yii\db\Query())
            ->select(["substr(f.route,position('media/' in f.route)) as route"])
            ->from('participants p')
            ->join('INNER JOIN', 'files f', 'p.photo_id =f.id and f.`status` =1')
            ->where('p.condition = 1 and p.id = :id', [':id' => $this->id])
            ->scalar();
    }

    public function getEventsbySpeaker()
    {
        $requestParams = Yii::$app->getRequest()->getQueryParams();

        $eventIds = UsuarioQuery::getEventsBySpeaker($requestParams['id']);

        $evento = EventsQuery::getEventById($eventIds);
        return EventsQuery::getEventsByIds($evento);
    }
    public function getEventsbyModerator()
    {
        $requestParams = Yii::$app->getRequest()->getQueryParams();

        $eventIds = UsuarioQuery::getEventsByModerator($requestParams['id']);

        $evento = EventsQuery::getEventById($eventIds);
        return EventsQuery::getEventsByIds($evento);
    }
}
