<?php

namespace app\controllers\event;

use app\rest\Action;
use Yii;
use yii\base\Model;
use yii\web\ServerErrorHttpException;

/**
 * @author Richard Huaman <richard21hg92@gmail.com>
 */
class UactiveeventstartAction extends Action
{
    /**
     * @var string the scenario to be assigned to the model before it is validated and updated.
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * Updates an existing model.
     * @param string $id the primary key of the model.
     * @return \yii\db\ActiveRecordInterface the model being updated
     * @throws ServerErrorHttpException if there is any error when updating the model
     */
    public function run($id)
    {
        /* @var $model ActiveRecord */
        $model = $this->findModel($id);

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }
        $requestParams = Yii::$app->getRequest()->getBodyParams();

        $model->scenario = $this->scenario;
        $model->active=1;
        
        if (!$model->save()) {
            throw new ServerErrorHttpException('Error al activar el evento ');
        }
        return $model;
    }
}
