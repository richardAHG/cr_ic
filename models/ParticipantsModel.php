<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "participants".
 *
 * @property int $id
 * @property string $name
 * @property string $last_name
 * @property string $email
 * @property int|null $nationality_id
 * @property string|null $company
 * @property int|null $photo_id
 * @property string|null $position
 * @property string|null $position_en
 * @property string|null $description
 * @property string|null $description_en
 * @property int $type_id
 * @property int|null $condition
 */
class ParticipantsModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'participants';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'last_name', 'email', 'type_id'], 'required'],
            [['nationality_id', 'photo_id', 'type_id', 'condition'], 'integer'],
            [['position', 'position_en', 'description', 'description_en'], 'string'],
            [['name', 'last_name', 'email'], 'string', 'max' => 80],
            [['company'], 'string', 'max' => 50],
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
            'last_name' => 'Last Name',
            'email' => 'Email',
            'nationality_id' => 'Nationality ID',
            'company' => 'Company',
            'photo_id' => 'Photo ID',
            'position' => 'Position',
            'position_en' => 'Position En',
            'description' => 'Description',
            'description_en' => 'Description En',
            'type_id' => 'Type ID',
            'condition' => 'Condition',
        ];
    }
}
