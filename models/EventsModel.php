<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "events".
 *
 * @property int $id
 * @property string $title
 * @property string $title_en
 * @property string|null $description
 * @property string $date
 * @property string $city
 * @property int $diary_id
 * @property int $type_id
 * @property int|null $condition
 */
class EventsModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'events';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'title_en', 'date', 'city', 'diary_id', 'type_id'], 'required'],
            [['description'], 'string'],
            [['date'], 'safe'],
            [['diary_id', 'type_id', 'condition'], 'integer'],
            [['title', 'title_en'], 'string', 'max' => 150],
            [['city'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'title_en' => 'Title En',
            'description' => 'Description',
            'date' => 'Date',
            'city' => 'City',
            'diary_id' => 'Diary ID',
            'type_id' => 'Type ID',
            'condition' => 'Condition',
        ];
    }
}
