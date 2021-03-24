<?php

namespace app\rest;

use Yii;
use yii\base\Model;
use yii\web\ServerErrorHttpException;

/**
 * @author Richard Huaman <richard21hg92@gmail.com>
 */
class DeleteAction extends Action
{
    /**
     * @var string el escenario que se asignará al modelo antes de que sea validado y actualizado.
     */
    public $scenario = Model::SCENARIO_DEFAULT;
    
    /**
     * Eliminar un registro
     * 
     * @param mixed $id id of the model to be deleted.
     * @throws ServerErrorHttpException on failure.
     */
    public function run($id)
    {
        $model = $this->findModel($id);

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        $model->scenario = $this->scenario;

        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }

        $requestParams["condition"] = 0;
        $model->load($requestParams, '');
        if ($model->save() === false && !$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        }

        return $model;
    }
}