<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "evento_expositor".
 *
 * @property int $id
 * @property int $persona_id
 * @property int $evento_id
 */
class EventoExpositorModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'evento_expositor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['persona_id', 'evento_id'], 'required'],
            [['persona_id', 'evento_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'persona_id' => 'Persona ID',
            'evento_id' => 'Evento ID',
        ];
    }
}
