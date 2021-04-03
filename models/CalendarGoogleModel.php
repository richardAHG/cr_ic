<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "calendar_google".
 *
 * @property int $id
 * @property int $usuario_id
 * @property string $token
 * @property string $date_created
 * @property int|null $condition
 */
class CalendarGoogleModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'calendar_google';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usuario_id', 'token', 'date_created'], 'required'],
            [['usuario_id', 'condition'], 'integer'],
            [['token'], 'string'],
            [['date_created'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'usuario_id' => 'Usuario ID',
            'token' => 'Token',
            'date_created' => 'Date Created',
            'condition' => 'Condition',
        ];
    }
}
