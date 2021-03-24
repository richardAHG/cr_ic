<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "diary".
 *
 * @property int $id
 * @property string $date
 * @property string $date_string
 * @property string $date_string_en
 * @property int|null $condition
 */
class DiaryModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'diary';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'date_string', 'date_string_en'], 'required'],
            [['date'], 'safe'],
            [['condition'], 'integer'],
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
            'date' => 'Date',
            'date_string' => 'Date String',
            'date_string_en' => 'Date String En',
            'condition' => 'Condition',
        ];
    }
}
