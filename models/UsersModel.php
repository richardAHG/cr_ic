<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $name
 * @property string $last_name
 * @property string $email
 * @property int|null $nationality_id
 * @property int|null $condition
 * @property int|null $sent
 * @property string $token
 */
class UsersModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'last_name', 'email', 'token'], 'required'],
            [['nationality_id', 'condition', 'sent'], 'integer'],
            [['token'], 'string'],
            [['name', 'last_name', 'email'], 'string', 'max' => 80],
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
            'condition' => 'Condition',
            'sent' => 'Sent',
            'token' => 'Token',
        ];
    }
}
