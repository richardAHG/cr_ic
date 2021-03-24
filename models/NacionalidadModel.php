<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "nacionalidad".
 *
 * @property int $id
 * @property string $nombre
 * @property string $pais
 * @property int $estado
 */
class NacionalidadModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'nacionalidad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'pais', 'estado'], 'required'],
            [['estado'], 'integer'],
            [['nombre', 'pais'], 'string', 'max' => 80],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'pais' => 'Pais',
            'estado' => 'Estado',
        ];
    }
}
