<?php

namespace app\controllers;

use app\helpers\Response;
use app\models\query\ParticipantsQuery;
use app\models\clases\ParticipantsClass;
use app\rest\ActiveController;
use Exception;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\UploadedFile;

class ParticipantsController extends ActiveController
{
    public $modelClass = 'app\models\ParticipantsModel';

    public function actions()
    {
        return [];
    }

    public function actionIndex()
    {
        try {
            $id = Yii::$app->getRequest()->get('type', '');

            return ParticipantsClass::listar($id);
        } catch (Exception $e) {
            $msg = $e->getMessage();
            throw new BadRequestHttpException($msg, 400);
        }
    }

    public function actionView()
    {
        try {
            $id = Yii::$app->getRequest()->get('id', '');

            return ParticipantsClass::obtener($id);
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e->getCode());
        }
        Response::JSON(200, 'Data obtenida');
    }

    public function actionCreate()
    {
        try {
            $params = Yii::$app->getRequest()->getBodyParams();

            $file = UploadedFile::getInstanceByName('photo');
            if (!empty($file)) {
                $archivo = ParticipantsQuery::loadFile($file);
                $params['photo_id'] = $archivo->id;
            }
            //TODO: VALIDAR DATOS DUPLICADO,P  PENDIENTE
            $id = ParticipantsClass::insertar($params);
            Response::JSON(200, 'Datos Registrados', ['id' => $id]);
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e->getCode());
        }
    }

    public function actionUpdate_()
    {
        $id = null;
        try {
            $params = Yii::$app->getRequest()->getBodyParams();
            $id = Yii::$app->getRequest()->get('id', '');

            $file = UploadedFile::getInstanceByName('photo');

            if (!empty($file)) {
                $mfile = ParticipantsClass::obtener($id);
                // print_r($mfile->photo_id);die();
                ParticipantsQuery::loadFile($file, $mfile->photo_id);
            }

            $id = ParticipantsClass::actualizar($params, $id);
            Response::JSON(200, 'Datos Actualizados', ['id' => $id]);
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e->getCode());
        }
    }

    public function actionDelete()
    {
        try {
            $id = Yii::$app->getRequest()->get('id', '');

            ParticipantsClass::eliminar($id);
            Response::JSON(200, 'Registro elminado', ['id' => $id]);
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e->getCode());
        }
    }
}
