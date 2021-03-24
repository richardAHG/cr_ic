<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "presentations".
 *
 * @property int $id
 * @property string $name
 * @property int $event_id
 * @property int|null $condition
 */
class PresentationsModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'presentations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'event_id'], 'required'],
            [['event_id', 'condition'], 'integer'],
            [['name'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'event_id' => 'Event ID',
            'condition' => 'Condition',
        ];
    }
}
