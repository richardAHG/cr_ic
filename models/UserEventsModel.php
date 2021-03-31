<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_events".
 *
 * @property int $id
 * @property int $user_id
 * @property int $event_id
 * @property int|null $condition
 * @property int|null $language
 */
class UserEventsModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_events';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'event_id'], 'required'],
            [['user_id', 'event_id', 'condition', 'language'], 'integer'],
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
            'condition' => 'Condition',
            'language' => 'Language',
        ];
    }
}
