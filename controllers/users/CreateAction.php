<?php

namespace app\controllers\users;

use app\helpers\Constants;
use app\helpers\Mailer;
use app\helpers\Response;
use app\helpers\Utils;
use app\models\query\UsuarioQuery;
use app\models\UsersModel;
use app\rest\Action;
use Yii;
use yii\base\Model;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;

/**
 * @author Richard Huaman <richard21hg92@gmail.com>
 */
class CreateAction extends Action
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
        $lang = Yii::$app->getRequest()->get('lang','');
        $requestParams = Yii::$app->getRequest()->getBodyParams();

        if (!$lang) {
            throw new BadRequestHttpException("El parametro de idioma no puede estar vacio", 400);
        }

        // validacion de nombre usuario y email unico
        $exists = UsuarioQuery::validateEmailDuplicate(
            $requestParams['email']
        );
        if ($exists) {
            $user = UsersModel::find()->where(['upper(email)' => mb_strtoupper($requestParams['email'])])->one();
            $user->sent = 1;

            if (empty($user->token)) {
                $token = Utils::generateToken();
                $user->token = $token;
                self::envioCorreo($requestParams, 'Gracias por confirmar su asistencia',$lang);
                if (!$user->save()) {
                    throw new ServerErrorHttpException('Error al actualizar token');
                }
                Response::JSON(200, "Usted se ha registrado correctamente", $user);
            }

            if (!$user->save()) {
                throw new ServerErrorHttpException('Error al actualizar estado de envio de correo');
            }
            Response::JSON(201, "Usted ya se encuentra registrado", $user);
        }
        $token = Utils::generateToken();
        $model->token = $token;
        $model->sent = 1;
        $model->load($requestParams, '');
        if (!$model->save()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        self::envioCorreo($requestParams, 'Gracias por confirmar su asistencia',$lang);
        Response::JSON(200, "Usted se ha registrado correctamente", $model);
    }

    public static function envioCorreo($params, $subject,$lang)
    {
        $mail = new Mailer();
        $param = [
            "ruta" => 'www.investor-conference/eventos-inscritos',
            'nombreUsuario' => $params['name']
        ];

        if ($lang == Constants::LANGUAGE_ES) {
            $body = Yii::$app->view->renderFile("{$mail->path}/confirmar-registro.php", compact("param"));    
        }else{
            $body = Yii::$app->view->renderFile("{$mail->path}/confirmar-registro_en.php", compact("param"));
        }
        $mail->send($params['email'], $subject, $body);
    }
}
