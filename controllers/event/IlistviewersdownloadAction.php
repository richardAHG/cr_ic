<?php

namespace app\controllers\event;

use app\helpers\CsvUtil;
use app\models\EventsModel;
use app\models\query\DiaryQuery;
use app\models\query\EventsQuery;
use app\rest\Action;
use DateTime;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * IndexAction implementa el punto final de la API para enumerar varios modelos
 * 
 * @author Richard Huaman <richard21hg92@gmail.com>
 */
class IlistviewersdownloadAction extends Action
{
    /**
     * @return ActiveDataProvider
     */
    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        return $this->prepareDataProvider();
    }

    /**
     * Prepares the data provider that should return the requested collection of the models.
     * @return ActiveDataProvider
     */
    protected function prepareDataProvider()
    {
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }

        /* @var $modelClass \yii\db\BaseActiveRecord */
        $modelClass = $this->modelClass;

        
        //Listado de personas que estan viendo el evento
        // return EventsQuery::getViewers();

        try {
            $structure = CsvUtil::getStructure();

            $data[] = $structure;

            $worksheet = CsvUtil::createSheet("CREDICORP");
            $hoja = $worksheet->getActiveSheet();

            CsvUtil::writeSheet($hoja, $data);

            $sheet = CsvUtil::saveSheet($worksheet, 'descarga_plantilla');

            return ['ruta' => $sheet];
        } catch (\Exception $e) {
            $this->_mensaje = $e->getMessage();
            $this->_status = $e->getCode();
        }

    }
}
