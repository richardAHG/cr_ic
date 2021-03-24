<?php

namespace app\models\custom;

use app\models\DiaryModel;
use app\models\EventsModel;

class DiaryCustomModel extends DiaryModel
{
    public function extraFields()
    {
        return ['events'];
    }

    public function getEvents()
    {
        return EventsModel::find()
            ->where([
                'condition' => 1,
                'diary_id' => $this->id
            ])
            ->all();
    }
}
