<?php

namespace app\helpers;

use Yii;
use Exception;
use yii\helpers\FileHelper;
use yii\web\HttpException;

class File
{
    const FILE_LENGTH = 100;

    public static function generateNameFile()
    {
        return uniqid(rand(), false);
    }

    public static function getPath()
    {
        $ruta = Yii::$app->basePath . '/media';
        return self::createDirectory($ruta);
    }

    public static function getPathTemp()
    {
        $ruta = Yii::$app->basePath . '/media/temp';
        return self::createDirectory($ruta);
    }

    /**
     * Crea un nuevo directorio
     *
     * @param string $directorio
     * @return string
     */
    public static function createDirectory($directorio)
    {
        if (!file_exists($directorio)) {
            $creado = FileHelper::createDirectory($directorio, 0777, true);

            if (!$creado) {
                throw new Exception("Error al crear directorio");
            }
        }

        return $directorio;
    }

    /**
     * Sube archivo al servidor
     *
     * @param [file] $file, debe ser instanciado con UploadedFile::getInstanceByName();
     * @param [string] $ruta
     * @return void
     */
    public static function upload($file, $ruta)
    {
        $dataFile = $file->saveAs($ruta);

        if (!$dataFile) {
            throw new HttpException(400, "Error al momento de subir el archivo al servidor");
        }
    }

    public static function delete($file)
    {
        if (is_file($file)) {
            return unlink($file);
        }
        return false;
    }

    public static function rename($oldNameFile, $newNameFile)
    {
        if (is_file($oldNameFile)) {
            return rename($oldNameFile, $newNameFile);
        }
        return false;
    }

    public static function move($origin, $destination)
    {
        return self::rename($origin, $destination);
    }

    public static function allowedExtensions($extension)
    {
        $extensiones = ['pdf', 'jpg', 'png', 'doc', 'docx', 'xlsx', 'csv'];

        if (!in_array($extension, $extensiones)) {
            throw new HttpException(400, "Archivo no pemitido");
        }

        return true;
    }

    public static function validate($file)
    {
        if (empty($file)) {
            throw new HttpException(400, "Debe cargar un archivo");
        }
        if ($file->error != 0) {
            throw new HttpException(400, "Error al leer el archivo");
        }

        self::validaNameLength($file->name);

        $extension = self::getExtension($file->name);
        self::allowedExtensions($extension);

        return true;
    }

    public static function validaNameLength($fileName, $length = self::FILE_LENGTH)
    {
        if (strlen($fileName) > $length) {
            throw new HttpException(400, "Nombre de archivo debe ser menor a {$length} caracteres");
        }

        return true;
    }

    public static function getExtension($fileName)
    {
        $extension = explode('.', $fileName);
        return end($extension);
    }

}
