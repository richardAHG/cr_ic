<?php

namespace app\models\query;

use app\helpers\File;
use app\models\clases\FilesClass;
use app\models\ParticipantsModel;
use Yii;
use yii\web\BadRequestHttpException;

class ParticipantsQuery
{
    public static function validateEmailDuplicate($email, $id = null)
    {
        $usuarioModel = ParticipantsModel::find()
            ->where([
                'condition' => 1,
                'upper(email)' => mb_strtoupper($email)
            ]);

        if (isset($id)) {
            $usuarioModel->andWhere(['NOT', ['id' => $id]]);
        }

        $rpta = $usuarioModel->one();

        if ($rpta) {
            throw new BadRequestHttpException('El email ya existe, ingrese otros datos');
        }
    }

    // public static function getEventsBySpeaker($speaker_id)
    // {
    //     $sql = "SELECT DISTINCT event_id from events_speakers es 
    //             where participant_id =:speaker ";
    //     return Yii::$app->db->createCommand($sql)->bindParam(':speaker', $speaker_id)->queryColumn();
    // }

    // public static function getEventsByModerator($moderator_id)
    // {
    //     $sql = "SELECT DISTINCT event_id from events_moderators es 
    //             where participant_id =:moderator_id ";
    //     return Yii::$app->db->createCommand($sql)->bindParam(':moderator_id', $moderator_id)->queryColumn();
    // }

    public static function loadFile($archivo, $id = false)
    {
        //valida el documento
        File::validate($archivo);

        $extension = File::getExtension($archivo->name);
        if ($extension != 'jpg' && $extension != 'png') {
            throw new BadRequestHttpException("Error de extension de archivo, solo se permite .jpg o .png");
        }

        //obtiene ruta + nombre del archivo
        $nuevoNombre = File::getPath() . "/" . File::generateNameFile();

        //mueve el documento de temp al directorio destino
        File::upload($archivo, $nuevoNombre);

        if ($id) {
            //registra datos del documento en tabla archivos
            $data = FilesClass::updateFile($id, $archivo, $nuevoNombre);
        } else {
            //registra datos del documento en tabla archivos
            $data = FilesClass::insertFile($archivo, $nuevoNombre);
        }

        return $data;
    }
}
