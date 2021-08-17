<?php

namespace app\controllers\event;

use app\models\EventViewModel;
use app\rest\Action;
use Yii;
use yii\base\Model;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;

/**
 * @author Richard Huaman <richard21hg92@gmail.com>
 */
class EventwiewsupAction extends Action
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
    public function run()
    {
        $id = Yii::$app->getRequest()->get('id','');
        $requestParams = Yii::$app->getRequest()->getBodyParams();

        $model=EventViewModel::findOne(['id'=>$id,'status'=>1]);
        if (!$model) {
            throw new BadRequestHttpException("No se encuentra el registro solicitado", 400);
        }
        
        $model->final_hour=$requestParams['final_hour'];
        if (!$model->save()) {
            throw new ServerErrorHttpException('Error al actualizar el evento visto');
        }

        return ['status'=>200,'id'=>$model->id,'message'=>'Datos actualizados'];
    }
}
