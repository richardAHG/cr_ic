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
class CquestionAction extends Action
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

        $requestParams = Yii::$app->getRequest()->getBodyParams();

        // validacion de nombre usuario y email unico
        $exists = UsersModel::findOne(['token' => $requestParams['token'], 'condition' => 1]);
        if (!$exists) {
            throw new BadRequestHttpException("El usuario no existe", 400);
        }
        $requestParams['user_id']=$exists->id;
        $model->load($requestParams, '');
        if (!$model->save()) {

            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        $requestParams['name'] = $exists->name;
        self::envioCorreo($requestParams, 'Preguntas sobre el evento', $requestParams['lang']);
        Response::JSON(200, "Correo enviado con exito", $model);
    }

    public static function envioCorreo($params, $subject, $lang)
    {
        $mail = new Mailer();
        $param = [
            "ruta" => 'www.investor-conference/eventos-inscritos',
            'user' => $params['name'],
            'question' => $params['question'],
        ];

        if ($lang == Constants::LANGUAGE_ES) {
            $body = Yii::$app->view->renderFile("{$mail->path}/preguntas_evento.php", compact("param"));
        } else {
            $subject = 'Questions about the event';
            $body = Yii::$app->view->renderFile("{$mail->path}/preguntas_evento_en.php", compact("param"));
        }
        $email = 'richard@cuborojo.pe';
        $mail->send($email, $subject, $body);
    }
}
