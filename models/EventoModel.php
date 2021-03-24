<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "evento".
 *
 * @property int $id
 * @property string $nombre
 * @property string $lugar
 * @property string $fecha
 * @property string $hora
 * @property int $tipo_id
 * @property int $moderador_id
 * @property int $evento_expositor_id
 * @property int $estado
 */
class EventoModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'evento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'lugar', 'fecha', 'hora', 'tipo_id', 'moderador_id', 'evento_expositor_id', 'estado'], 'required'],
            [['fecha'], 'safe'],
            [['tipo_id', 'moderador_id', 'evento_expositor_id', 'estado'], 'integer'],
            [['nombre'], 'string', 'max' => 80],
            [['lugar'], 'string', 'max' => 100],
            [['hora'], 'string', 'max' => 10],
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
            'lugar' => 'Lugar',
            'fecha' => 'Fecha',
            'hora' => 'Hora',
            'tipo_id' => 'Tipo ID',
            'moderador_id' => 'Moderador ID',
            'evento_expositor_id' => 'Evento Expositor ID',
            'estado' => 'Estado',
        ];
    }
}
