<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "files".
 *
 * @property int $id
 * @property string $name
 * @property string $extension
 * @property string $route
 * @property string $type
 * @property int $weight
 * @property int|null $status
 */
class FilesModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'extension', 'route', 'type', 'weight'], 'required'],
            [['route'], 'string'],
            [['weight', 'status'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['extension'], 'string', 'max' => 5],
            [['type'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'extension' => 'Extension',
            'route' => 'Route',
            'type' => 'Type',
            'weight' => 'Weight',
            'status' => 'Status',
        ];
    }
}
