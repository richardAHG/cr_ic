<?php

namespace app\controllers\event;

use app\models\EventsModeratorsModel;
use app\models\EventsSpeakersModel;
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
        $archivo = UploadedFile::getInstancesByName('presentations');
        
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model->load($requestParams, '');
            if (!$model->save()) {
                throw new BadRequestHttpException('Error al registrar el evento');
            }

            foreach ($archivo as $key => $value) {
                $presentations = new PresentationsModel();
                $presentations->name = $value->name;
                $presentations->event_id = $model->id;
                if (!$presentations->save()) {
                    throw new BadRequestHttpException('Error al registrar la presentacion');
                }
            }

            foreach ($requestParams['speakers'] as $key => $value) {
                $speaker = new EventsSpeakersModel();
                $speaker->event_id = $model->id;
                $speaker->participant_id = $value;

                if (!$speaker->save()) {
                    throw new BadRequestHttpException('Error al registrar al expositor');
                }
            }
            foreach ($requestParams['moderators'] as $key => $value) {
                $speaker = new EventsModeratorsModel();
                $speaker->event_id = $model->id;
                $speaker->participant_id = $value;

                if (!$speaker->save()) {
                    throw new BadRequestHttpException('Error al registrar al moderador');
                }
            }

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
        }

        return $model;
    }
}
