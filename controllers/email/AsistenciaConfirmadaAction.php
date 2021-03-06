<?php

namespace app\controllers\email;

use app\helpers\Mailer;
use app\helpers\Response;
use app\models\UsersModel;
use app\rest\Action;
use Yii;
use yii\base\Model;
use yii\web\ServerErrorHttpException;
use yii\web\BadRequestHttpException;

/**
 * @author Richard Huaman <richard21hg92@gmail.com>
 */
class AsistenciaConfirmadaAction extends Action
{
    /**
     * @var string the scenario to be assigned to the new model before it is validated and saved.
     */
    public $scenario = Model::SCENARIO_DEFAULT;
    /**
     * @var string the name of the view action. This property is needed to create the URL when the model is successfully created.
     */
    public $viewAction = 'view';


    /**
     * Creates a new model.
     * @return \yii\db\ActiveRecordInterface the model newly created
     * @throws ServerErrorHttpException if there is any error when creating the model
     */
    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /* @var $model \yii\db\ActiveRecord */
        $model = new $this->modelClass([
            'scenario' => $this->scenario,
        ]);

        // $requestParams = Yii::$app->getRequest()->getBodyParams();

        $users = UsersModel::find()
            ->where(['condition' => 1])
            ->all();

            // print_r($users); die();
        if (!$users) {
            throw new BadRequestHttpException("No existe usuarios");
        }

        foreach ($users as $user) {
            
            //enviar email, contiene nueva clave y link de logueo
        self::envioCorreo($user['email'], $user['name'],'Participación Confirmada');

        }

        //enviar email, contiene nueva clave y link de logue
        // self::envioCorreo($userModel->email, $token, $userModel->nombre);
        Response::JSON(200, 'Correo enviado');
    }

    public static function envioCorreo($email, $nombreUsuairo,$subject)
    {
        $mail = new Mailer();
        $params = [
            "ruta" => 'www.investor-conference/asistencia-confirmada',
            'nombreUsuario' => $nombreUsuairo
        ];
        $body = Yii::$app->view->renderFile("{$mail->path}/asistencia-confirmada.php", compact("params"));
        $mail->send($email, $subject, $body);
    }
}
