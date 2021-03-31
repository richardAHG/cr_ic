<?php

namespace app\models\query;

use app\models\UsersModel;
use Yii;
use yii\web\BadRequestHttpException;

class UsuarioQuery
{
    // public static function validateUserDuplicate($usuario, $id = null)
    // {
    //     $usuarioModel = UsersModel::find()
    //         ->where([
    //             'condition' => 1,
    //             'upper(usuario)' => mb_strtoupper($usuario)
    //         ]);

    //     if (isset($id)) {
    //         $usuarioModel->andWhere(['NOT', ['id' => $id]]);
    //     }
    //     // print_r($usuarioModel->createCommand()->getRawSql());die();
    //     $rpta = $usuarioModel->one();

    //     if ($rpta) {
    //         throw new BadRequestHttpException('El usuario ya existe, ingrese otros datos');
    //     }
    // }

    public static function validateEmailDuplicate($email, $id = null)
    {
        $usuarioModel = UsersModel::find()
            ->where([
                'condition' => 1,
                'upper(email)' => mb_strtoupper($email)
            ]);

        if (isset($id)) {
            $usuarioModel->andWhere(['NOT', ['id' => $id]]);
        }
        // print_r($usuarioModel->createCommand()->getRawSql());die();
        $rpta = $usuarioModel->one();

        if ($rpta) {
            throw new BadRequestHttpException('El email ya existe, ingrese otros datos');
        }
    }

    public static function getEventsBySpeaker($speaker_id)
    {
        $sql = "SELECT DISTINCT event_id from events_speakers es 
                where participant_id =:speaker ";
        return Yii::$app->db->createCommand($sql)->bindParam(':speaker', $speaker_id)->queryColumn();
    }

    public static function getEventsByModerator($moderator_id)
    {
        $sql = "SELECT DISTINCT event_id from events_speakers es 
                where participant_id =:moderator_id ";
        return Yii::$app->db->createCommand($sql)->bindParam(':moderator_id', $moderator_id)->queryColumn();
    }
}
