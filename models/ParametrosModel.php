<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "parametros".
 *
 * @property int $id
 * @property string $grupo
 * @property string $nombre
 * @property int $valor
 */
class ParametrosModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'parametros';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['grupo', 'nombre', 'valor'], 'required'],
            [['valor'], 'integer'],
            [['grupo', 'nombre'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'grupo' => 'Grupo',
            'nombre' => 'Nombre',
            'valor' => 'Valor',
        ];
    }
}
