<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "events_speakers".
 *
 * @property int $id
 * @property int $event_id
 * @property int $participant_id
 * @property int|null $condition
 */
class EventsSpeakersModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'events_speakers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_id', 'participant_id'], 'required'],
            [['event_id', 'participant_id', 'condition'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event_id' => 'Event ID',
            'participant_id' => 'Participant ID',
            'condition' => 'Condition',
        ];
    }
}
