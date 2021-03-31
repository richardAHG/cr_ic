<?php

namespace app\controllers\users\events;

use app\helpers\Mailer;
use app\helpers\Response;
use app\models\EventsModel;
use app\models\query\DiaryQuery;
use app\models\query\EventsQuery;
use app\models\UserEventsModel;
use app\models\UsersModel;
use app\rest\Action;
use Exception;
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
        $requestParams = Yii::$app->getRequest()->getBodyParams();

        $user = UsersModel::find()
            ->where([
                'condition' => 1,
                'id' => $requestParams['user_id']
            ])
            ->one();

        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($requestParams['event'] as $key => $value) {
                $userEvent = new UserEventsModel();
                $userEvent->user_id = $requestParams['user_id'];
                $userEvent->event_id = $value;

                if (!$userEvent->save()) {
                    throw new BadRequestHttpException("Error al guardar el evento");
                }
            }
            $transaction->commit();
        } catch (Exception $ex) {
            $transaction->rollBack();
            throw $ex->getMessage();
        }

        self::envioCorreo($user['email'], $user['name'], 'Eventos Inscritos', $user['token']);
        // return $model;
        Response::JSON(200, 'Datos insertados con exito');
    }

    public static function envioCorreo($email, $nombreUsuairo, $subject, $token)
    {
        $ids = EventsQuery::getEventsByUser($token);
        // $evento = EventsModel::find()
        //     ->where(['condition' => 1])
        //     ->andWhere(['in', 'id', $ids])
        //     ->all();
            $evento=EventsQuery::getEvent($ids);
        $data = EventsQuery::getEventsByIds($evento);
        $mail = new Mailer();
        $params = [
            "ruta" => 'www.investor-conference/eventos-inscritos',
            'nombreUsuario' => $nombreUsuairo,
            'data' => $data
        ];
        $body = Yii::$app->view->renderFile("{$mail->path}/eventos-inscritos.php", compact("params"));
        $mail->send($email, $subject, $body);
    }
}
