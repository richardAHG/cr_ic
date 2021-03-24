<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "parameters".
 *
 * @property int $id
 * @property string $group
 * @property string $name
 * @property int $value
 * @property int|null $condition
 */
class ParametersModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'parameters';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['group', 'name', 'value'], 'required'],
            [['value', 'condition'], 'integer'],
            [['group'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 80],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group' => 'Group',
            'name' => 'Name',
            'value' => 'Value',
            'condition' => 'Condition',
        ];
    }
}
