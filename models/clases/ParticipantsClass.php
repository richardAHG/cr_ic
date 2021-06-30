<?php

namespace app\models\clases;

use app\models\custom\ParticipantsCustomModel;
use app\models\ParticipantsModel;
use yii\web\BadRequestHttpException;

class ParticipantsClass extends ParticipantsCustomModel
{
    public static function listar($type = false)
    {
        $query = parent::find()
            ->where([
                "condition" => true
            ]);
        if ($type) {
            $query->andWhere('type_id = :type_id', [':type_id' => $type]);
        }

        return $query->all();
    }

    public static function obtener($id)
    {
        $result = parent::find()
            ->where(['condition' => true, 'id' => $id])
            ->one();

        if (!$result) {
            throw new BadRequestHttpException("No existe el registro solicitado");
        }

        return $result;
    }

    public static function insertar($params)
    {

        $model = new ParticipantsModel();

        $model->load($params, '');
        
        if (!$model->save()) {
            throw new BadRequestHttpException("Error al registrar", 400);
        }

        return $model->id;
    }

    public static function actualizar($params, $id)
    {
        $model = self::obtener($id);

        $model->load($params, '');

        if (!$model->save()) {
            throw new BadRequestHttpException("Error al actualizar", 400);
        }

        return $model->id;
    }

    public static function eliminar($id)
    {
        $model = self::obtener($id);

        $model->condition = 0;

        if (!$model->save()) {

            throw new BadRequestHttpException("Error al eliminar", 400);
        }

        return $model->id;
    }
}
