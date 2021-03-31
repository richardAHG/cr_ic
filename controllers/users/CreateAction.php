<?php

namespace app\controllers\users;

use app\helpers\Response;
use app\helpers\Utils;
use app\models\query\UsuarioQuery;
use app\models\UsersModel;
use app\rest\Action;
use Yii;
use yii\base\Model;
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
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        // validacion de nombre usuario y email unico
        $exists = UsuarioQuery::validateEmailDuplicate(
            $requestParams['email']
        );
        if ($exists) {
            $user=UsersModel::find()->where(['email' => $requestParams['email']])->one();
            Response::JSON(201,"Ud. ya se encuentra registrado",$user);
        }
        $token = Utils::generateToken();
        $model->token = $token;

        $model->load($requestParams, '');
        if (!$model->save()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        Response::JSON(200,"Registro exitoso",$model);
    }
}
