<?php

namespace app\models\clases;

use app\models\FilesModel;
use yii\web\BadRequestHttpException;

class FilesClass extends FilesModel
{
    public static function insertFile($file, $ruta)
    {
        $ext = explode('.', $file->name);

        $model = new FilesModel();
        $model->route = $ruta;
        $model->type = $file->type;
        $model->weight = $file->size;
        $model->extension = end($ext);
        $model->name = $file->getBaseName();

        if (!$model->save()){
            throw new BadRequestHttpException("Error al guardar el archivo");
        }
            

        return $model;
    }

    public static function updateFile($id, $file, $ruta)
    {
        $ext = explode('.', $file->name);

        $model = self::getFile($id);
        $model->route = $ruta;
        $model->type = $file->type;
        $model->weight = $file->size;
        $model->extension = end($ext);
        $model->name = $file->getBaseName();
        
        if (!$model->save()){
            throw new BadRequestHttpException("Error al actualizar el archivo");
        }
            
        return $model;
    }

    public static function getFile($id)
    {
        $model = parent::findOne([
            'id' => $id,
            'status' => true
        ]);

        if (!isset($model) || !file_exists($model->route)) {
            throw new BadRequestHttpException("No se encontro el archivo");
        }

        return $model;
    }

    public static function deleteFile($id)
    {
        $model = self::getFile($id);

        $model->status = 0;

        if (!$model->save()) {
            throw new BadRequestHttpException("Error al eliminar el archivo");
        }
    }
}
