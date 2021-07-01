<?php

namespace app\models\custom;

use app\models\ParticipantsModel;

class ParticipantsCustomModel extends ParticipantsModel
{
    public function extraFields()
    {
        return ['file'];
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
}
