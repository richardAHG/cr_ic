<?php

namespace app\models\custom;

use app\models\query\EventsQuery;
use app\models\query\UsuarioQuery;
use app\models\UsersModel;
use Yii;

class UsersCustomModel extends UsersModel
{
    public function extraFields()
    {
        return ['EventsbySpeaker', 'EventsbyModerator'];
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
