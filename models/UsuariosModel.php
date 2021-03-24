<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuarios".
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $apellido
 * @property string $email
 * @property int $nacionalidad_id
 * @property int $tipo_id
 * @property int|null $estado
 */
class UsuariosModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuarios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'email', 'nacionalidad_id', 'tipo_id'], 'required'],
            [['nacionalidad_id', 'tipo_id', 'estado'], 'integer'],
            [['nombre', 'apellido', 'email'], 'string', 'max' => 80],
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
            'apellido' => 'Apellido',
            'email' => 'Email',
            'nacionalidad_id' => 'Nacionalidad ID',
            'tipo_id' => 'Tipo ID',
            'estado' => 'Estado',
        ];
    }
}
