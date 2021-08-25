<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_question".
 *
 * @property int $id
 * @property int $user_id
 * @property string $question
 * @property int|null $event_id
 * @property string|null $date_
 * @property string|null $hour_
 * @property int|null $status
 */
class UserQuestionModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_question';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'question'], 'required'],
            [['user_id', 'event_id', 'status'], 'integer'],
            [['question'], 'string'],
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
            'question' => 'Question',
            'event_id' => 'Event ID',
            'date_' => 'Date',
            'hour_' => 'Hour',
            'status' => 'Status',
        ];
    }
}
