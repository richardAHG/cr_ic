<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "event_view".
 *
 * @property int $id
 * @property int $user_id
 * @property int $event_id
 * @property string $date_
 * @property string $hour_
 * @property int|null $type_hour
 * @property int|null $status
 */
class EventViewModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event_view';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'date_', 'hour_'], 'required'],
            [['user_id', 'event_id', 'type_hour', 'status'], 'integer'],
            [['date_', 'hour_'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'event_id' => 'Event ID',
            'date_' => 'Date',
            'hour_' => 'Hour',
            'type_hour' => 'Type Hour',
            'status' => 'Status',
        ];
    }
}
