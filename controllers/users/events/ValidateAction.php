<?php

namespace app\controllers\users\events;

use app\helpers\Constants;
use app\helpers\Mailer;
use app\helpers\Response;
use app\models\EventsModel;
use app\models\query\DiaryQuery;
use app\models\query\EventsQuery;
use app\models\query\UserEventsQuery;
use app\models\query\UsuarioQuery;
use app\models\UserEventsModel;
use app\models\UsersModel;
use app\rest\Action;
use DateTime;
use DateTimeZone;
use Exception;
use Yii;
use yii\base\Model;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;

/**
 * @author Richard Huaman <richard21hg92@gmail.com>
 */
class ValidateAction extends Action
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

        //validar usuario
        return UsuarioQuery::userExistByToken($requestParams['token']);

    }
}
