<?php

namespace app\controllers\event;

use app\models\EventsModeratorsModel;
use app\models\EventsSpeakersModel;
use app\models\EventViewModel;
use app\models\PresentationsModel;
use app\rest\Action;
use Yii;
use yii\base\Model;
use yii\db\Exception;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

/**
 * @author Richard Huaman <richard21hg92@gmail.com>
 */
class EventwiewsAction extends Action
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

        try {
            if ($requestParams['id'] > 0) {
                $exist = EventViewModel::find()
                    ->where(['id' => $requestParams['id'], 'status' => 1])->one();
                if ($exist) {
                    $exist->final_hour = $requestParams['final_hour'];
                    if (!$exist->save()) {
                        throw new BadRequestHttpException("error al momento de actualzia rla hora de salida", 400);
                    }
                    return ['status' => 200, 'id' => $exist->id, 'message' => 'Datos actualizados'];            
                }
            } else {
                $model->load($requestParams, '');
                if (!$model->save()) {
                    throw new BadRequestHttpException('Error al registrar el evento');
                }
            }
        } catch (Exception $e) {
            throw new $e->getMessage();
        }

        return ['status' => 200, 'id' => $model->id, 'message' => 'Datos Registrados'];
    }
}
