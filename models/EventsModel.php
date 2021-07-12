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
 * @property int $type_id
 * @property int|null $condition
 * @property string|null $date_string
 * @property string|null $date_string_en
 * @property string|null $date_string_large
 * @property string|null $date_string_large_en
 * @property int $active
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
            [['title', 'title_en', 'date', 'city', 'type_id'], 'required'],
            [['description'], 'string'],
            [['date'], 'safe'],
            [['type_id', 'condition', 'active'], 'integer'],
            [['title', 'title_en'], 'string', 'max' => 150],
            [['city', 'date_string_large', 'date_string_large_en'], 'string', 'max' => 100],
            [['date_string', 'date_string_en'], 'string', 'max' => 60],
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
            'type_id' => 'Type ID',
            'condition' => 'Condition',
            'date_string' => 'Date String',
            'date_string_en' => 'Date String En',
            'date_string_large' => 'Date String Large',
            'date_string_large_en' => 'Date String Large En',
            'active' => 'Active',
        ];
    }
}
