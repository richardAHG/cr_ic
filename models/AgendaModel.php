<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "agenda".
 *
 * @property int $id
 * @property int $persona_id
 * @property int $evento_id
 * @property int $estado
 */
class AgendaModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'agenda';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['persona_id', 'evento_id', 'estado'], 'required'],
            [['persona_id', 'evento_id', 'estado'], 'integer'],
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
            'estado' => 'Estado',
        ];
    }
}
