<?php

namespace app\controllers\users;

use app\helpers\CsvUtil;
use app\rest\Action;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * IndexAction implementa el punto final de la API para enumerar varios modelos
 * 
 * @author Richard Huaman <richard21hg92@gmail.com>
 */
class IndexdowloadAction extends Action
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

        $query = $modelClass::find()
            ->select(['name', 'last_name', 'email', 'sent','type_user'])
            ->andWhere([
                "condition" => 1
            ])->all();

        try {
            $header = ['Nombre', 'Apellido', 'email', 'Acepto','Tipo'];
            $structure = ['name', 'last_name', 'email', 'sent','type_user'];


            $lista = $query;

            $data[] = $header;
            foreach ($lista as $key => $reg) {
                foreach ($structure as $item) {
                    if (in_array($item, $structure)) {
                        $data[$key + 1][$item] = $reg[$item];
                    }
                }
            }

            $worksheet = CsvUtil::createSheet("CREDICORP");
            $hoja = $worksheet->getActiveSheet();

            CsvUtil::writeSheet($hoja, $data);

            $sheet = CsvUtil::saveSheet($worksheet, 'descarga_plantilla');

            return ['ruta' => $sheet];
        } catch (\Exception $e) {
            throw new $e->getMessage();
        }
    }
}
