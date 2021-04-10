<?php

namespace app\models\query;

use app\models\UserEventsModel;

class UserEventsQuery
{
    public static function eventUnique($params)
    {
        //eventos del usuario
        $userEvents = UserEventsModel::find()
            ->select(['event_id'])
            ->where([
                'user_id' => $params['user_id']
            ])
            ->column();
        //retornar eventos no registrados en la Db asigando al usuario
        return array_diff($params['event'], $userEvents);
    }
}
