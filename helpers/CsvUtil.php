<?php

namespace app\helpers;

use app\modules\v1\constants\Globals;
use app\modules\v1\models\AreasModel;
use app\modules\v1\models\CargosModel;
use app\modules\v1\models\clases\AreasClass;
use app\modules\v1\models\clases\CargosClass;
use app\modules\v1\models\DepartamentosModel;
use app\modules\v1\models\DistritosModel;
use app\modules\v1\models\JefesCargoModel;
use app\modules\v1\models\PersonalModel;
use app\modules\v1\models\ProvinciasModel;
use DateTime;
use DateTimeZone;
use Exception;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Exception as PhpSpreadsheetException;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use sye\yii2\lib\helpers\File;

class CsvUtil
{
    const RUTA_FINAL = '../files/temp/';

    public static function getStructure()
    {
        return [
            'ESTADO_EMPRESA',
            'REGIMEN_LABORAL',
            'TIPO_DOCUMENTO',
            'NRO_DOC',
            'APEPAT',
            'APEMAT',
            'NOMBRES',
            'TURNO',
            'REMUNERACION_BASICA',
            'ASIG_FAMILIAR',
            'CARGO',
            'SUB_AREA',
            'AREA',
            'CENTRO_COSTO',
            'FECHA_INGRESO',
            'FECHA_CESE',
            'GENERO',
            'NACIONALIDAD',
            'FECHA_NACIMIENTO',
            'ESTADO_CIVIL',
            'CELULAR',
            'PERSONA_EMERGENCIA',
            'TELEFONO_EMERGENCIA',
            'EMAIL',
            'DIRECCION',
            'DEPARTAMENTO',
            'PROVINCIA',
            'DISTRITO',
            'NIVEL_EDUCATIVO',
            'SISTEMA_PENSION',
            'CUSPP',
            'BANCO_SUELDO',
            'CUENTA_SUELDO',
            'INTERBANCARIO_SUELDO',
            'BANCO_CTS',
            'CUENTA_CTS',
            'CUENTA_INTERBANCARIA_CTS',
            'TIPO_CONTRATO',
            'ACTIVIDAD',
            'GRUPO_SANGUINEO',
            'TALLA_ZAPATOS',
            'TALLA_CAMISA',
            'TALLA_PANTALON',
            'EPS_PLAN',
            'EPS',
            'DOC_JEFE',
            'NOMBRE_JEFE'
        ];
    }

    public static function fieldsRequired()
    {
        return  [
            'ESTADO_EMPRESA',
            'REGIMEN_LABORAL',
            'TIPO_DOCUMENTO',
            'ASIG_FAMILIAR',
            'CENTRO_COSTO',
            'GENERO',
            'ESTADO_CIVIL',
            'NIVEL_EDUCATIVO',
            'SISTEMA_PENSION',
            'TIPO_CONTRATO',
            'GRUPO_SANGUINEO',
            'BANCO_SUELDO',
            'BANCO_CTS',
            'NACIONALIDAD',
            'EPS',
            'EPS_PLAN',
            'ACTIVIDAD',
            'TURNO',
            'TALLA_CAMISA'
        ];
    }

    public static function LoadFileCreateJson_bk($name_archivo)
    {
        $archivo = self::load($name_archivo);
        // Separamos el string ruta, para obtener el nombre en microtime 
        [$nombre, $ext] = explode('.', $name_archivo);
        //creamos el archivo JSON
        $rutaArchivo = self::createFileJSON($nombre, json_encode($archivo));
        return $rutaArchivo;
    }
    public static function load($name_archivo)
    {
        $rutaArchivo = self::RUTA_FINAL . $name_archivo;
        $documento = IOFactory::load($rutaArchivo);
        $worksheet = $documento->getActiveSheet();
        $highestRow = $worksheet->getHighestRow(); // total de filas
        $highestColumn = $worksheet->getHighestColumn(); // total de columnas en letras
        $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn); // total de columnas ne numeros
        $data = [];
        $headers = self::getHeaders($worksheet, $highestColumnIndex);
        for ($row = 1; $row <= $highestRow; ++$row) {
            // for ($col = 1; $col <= $highestColumnIndex; $col++) {
            //     $valuex = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
            //     $data[$row][$col] = trim($valuex);
            // }
            foreach ($headers as $col => $value) {
                // if (\PhpOffice\PhpSpreadsheet\Shared\Date::isDateTime($value)) {
                //     echo $value->getCoordinate().',';
                //     die();
                // }

                // if ($value == 'FECHA_INGRESO') {
                //     print_r(gettype($value));
                //     // $valuex = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                //     if (is_numeric($value)) {
                //         $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);    
                //         echo 'soy numeor '; 
                //     }else{
                //         $valuex = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                //         echo 'soy strign '; 
                //     }
                // }else{
                //     $valuex = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                // }
                $valuex = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                $data[$row][$value] = trim($valuex);
            }
        }
        $header = $data[1];
        //eliminamos la cabezera
        $data = array_splice($data, 1);
        return [
            'header' => $header,
            'data' => $data
        ];
    }

    public static function getHeaders($hojaActual, $totalColumnas)
    {
        $estructura = [];
        for ($col = 1; $col <= $totalColumnas; $col++) {
            //posicion
            $celda = $hojaActual->getCellByColumnAndRow($col, 1);
            $estructura[$col] = utf8_decode(trim($celda->getValue()));
        }
        return $estructura;
    }

    public static function createFileJSON($nameJSON, $data)
    {
        //obtiene ruta + nombre del archivo
        $nuevoNombre = self::RUTA_FINAL . $nameJSON . '.json';
        $fh = fopen($nuevoNombre, 'w');
        if (!$fh) {
            return ['error' => 'Se produjo un error al crear el archivo'];
        }
        // fwrite($fh, $data);
        if (!fwrite($fh, $data)) {
            return ['error' => 'No se pudo escribir en el archivo'];
        }
        fclose($fh);
        return $nameJSON;
    }

    public static function validateTotalHeaders($headers, $atributos)
    {
        // print_r($headers); die();
        $errors['estado'] = true;
        if (count($atributos) != count($headers)) {
            $plantilla = array_diff($atributos, $headers);
            $archivo = array_diff($headers, $atributos);
            $errors['mensaje'] = 'Cantidad de columnas incorrecta';
            $errors['error'] = [
                'plantilla' => $plantilla,
                'cargado' => $archivo
            ];
            $errors['tipo'] = 1;
            $errors['estado'] = false;
        } else {
            foreach ($headers as $key => $value) {
                $exist = in_array($value, $atributos);
                if (!$exist) {
                    $errors['mensaje'] = 'Nombre de Columnas incorrectas';
                    $errors['error'][] = [
                        'columna' => $value,
                    ];
                    $errors['tipo'] = 2;
                    $errors['estado'] = false;
                }
            }
        }
        return $errors;
    }

    public static function validateIdparams(&$data, $params)
    {
        //columans requeridas
        $required = self::fieldsRequired();

        $errors = [];
        $errors['estado'] = true;
        foreach ($required as $key => $value) {
            //obtenemos los ids de params por grupo
            $ids = $params[$value];
            if (empty($ids)) {
                return ['error' => 'columna no encontrada ' . $value];
            }
            //obtenemos un array solo de una columna especifica
            $tabla = array_column($data, $value);
            //FIXME: AGREGAR VALIDACION, CUANDO SE ENVIE ATRIBUTOS COMPLEMENTARIOS COMO DATOS VACIOS
            foreach ($tabla as $key => $valuex) {
                //obtenemos el id de valor buscado
                if (!in_array($valuex, $ids)) {
                    $errors['mensaje'] = 'No existe los siguientes valores';
                    $errors['error'][] = [
                        $value => $valuex
                    ];
                    $errors['tipo'] = 3;
                    $errors['estado'] = false;
                } else {
                    //obtenemos el id de valor buscado
                    $id = array_search($valuex, $ids);
                    //sobre escribir el id en la columna correspondinet del array principal
                    $data[$key][$value] = $id;
                }
            }
        }

        return $errors;
    }

    public static function validateIdTables(&$data)
    {

        //columans requeridas
        $tablas = ['AREA', 'SUB_AREA', 'CARGO', 'DEPARTAMENTO', 'PROVINCIA', 'DISTRITO'];
        // $tablas = ['AREA', 'SUB_AREA', 'CARGO'];

        $errors = [];
        $errors['estado'] = true;

        // foreach ($data as $fil => $row) {
        foreach ($tablas as $col => $value) {
            switch ($value) {
                case 'AREA':
                    $dataT = self::obtenerDataArea(Globals::TIPO_AREA);
                    break;
                case 'SUB_AREA':
                    $dataT = self::obtenerDataArea(Globals::TIPO_SUBAREA);
                    break;
                case 'CARGO':
                    $dataT = self::obtenerDataCargo();
                    break;
                case 'DEPARTAMENTO':
                    $dataT = self::obtenerDataDepartamentos();
                    break;
                case 'PROVINCIA':
                    $dataT = self::obtenerDataProvincias();
                    break;
                case 'DISTRITO':
                    $dataT = self::obtenerDataDistritos();
                    break;
                default:
                    # code...
                    break;
            }

            //obtenemos un array solo de una columna especifica
            $datos = array_column($data, $value);

            //obtengo solo datos unicos
            foreach ($datos as $key => $valuex) {
                $id = array_search($valuex, $dataT);
                if ($id) {
                    $data[$key][$value] = $id;
                } else {
                    $errors['mensaje'] = 'No existe los siguientes valores';
                    $errors['error'][] = [
                        'fila' => $key + 2,
                        'columna' => $value,
                        'valor' => $valuex
                    ];
                    $errors['tipo'] = 4;
                    $errors['estado'] = false;
                }
            }
        }

        return $errors;
    }

    private static function prepareArray($data)
    {
        $result = [];
        foreach ($data as $key => $value) {
            $result[$value['id']] = $value['nombre'];
        }
        return $result;
    }

    public static function obtenerDataCargo()
    {
        $data = CargosModel::find()
            ->select('id,nombre')
            ->where(['estado' => true])
            ->all();

        return self::prepareArray($data);
    }

    public static function obtenerDataArea($tipo = false)
    {
        $data = AreasModel::find()
            ->select('id,nombre')
            ->where(['estado' => true, 'tipo_id' => $tipo])
            ->asArray()
            ->all();

        return self::prepareArray($data);
    }

    public static function obtenerDataDepartamentos()
    {
        $data = DepartamentosModel::find()
            ->select('id,nombre')
            ->where(['estado' => true])
            ->asArray()
            ->all();

        return self::prepareArray($data);
    }

    public static function obtenerDataProvincias()
    {
        $data = ProvinciasModel::find()
            ->select('id,nombre')
            ->where(['estado' => true])
            ->asArray()
            ->all();

        return self::prepareArray($data);
    }

    public static function obtenerDataDistritos()
    {
        $data = DistritosModel::find()
            ->select('id,nombre')
            ->where(['estado' => true])
            ->asArray()
            ->all();

        return self::prepareArray($data);
    }

    public static function destroyFileTemp()
    {
        //obtenemos todos los nombres de los ficheros
        $path = self::RUTA_FINAL;
        $files = glob($path . '*');
        foreach ($files as $file) {
            if (is_file($file))
                //elimino el fichero    
                unlink($file);
        }
    }

    public static function createSheet()
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet
            ->getProperties()
            ->setCreator("sye")
            ->setLastModifiedBy('sye') // última vez modificado por
            ->setTitle('Plantilla de carga masiva')
            ->setSubject('Plantilla')
            ->setDescription('Esta plantilla permite subir información ordenada al sistema')
            ->setCategory('Carga Masiva');
        return $spreadsheet;
    }

    public static function writeSheet($sheet, $header = [])
    {
        // print_r($header); die();
        try {
            $fila = 0;
            foreach ($header as $fil => $row) {
                $fila = $fil + 1;
                $columna = 1;
                foreach ($row as $col => $value) {
                    // $columna=
                    // print_r($columna); 
                    // print_r($fila); 
                    // print_r($value); die();
                    $sheet->setCellValueByColumnAndRow($columna, $fila, $value);
                    $columna++;
                }

                // $fila = 1;
                // $columna = ($key + 1);
                // $sheet->setCellValueByColumnAndRow($columna, $fila, $value);
            }
        } catch (PhpSpreadsheetException $ex) {
            throw $ex->getMessage();
        }
        return true;
    }

    public static function saveSheet($spreadsheet, $nameSheet)
    {
        try {
            // $writer = new Xlsx($spreadsheet);
            // $writer->save($ruta);
            self::download_($spreadsheet, $nameSheet);
        } catch (PhpSpreadsheetException $ex) {
            throw $ex->getMessage();
        }
        return  $nameSheet;
    }

    public static function download_($spreadsheet, $nameSheet)
    {
        $nameSheet = $nameSheet . '.xlsx';
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $nameSheet . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public static function LoadFileCreateJson($archivoModel, $delimiter = ';', $codificacion = 'CP1252')
    {
        //leer csv
        $filecsv = self::RUTA_FINAL . $archivoModel;
        // print_r($filecsv); die();
        $data = self::readCsv($filecsv, $delimiter, $codificacion);

        [$nombre, $ext] = explode('.', $archivoModel);
        //creamos el archivo JSON
        $rutaArchivojson = self::createFileJSON(
            $nombre,
            json_encode($data)
        );
        return $rutaArchivojson;
    }

    public static function readCsv($path, $delimiter, $codificacion)
    {
        try {
            //leer archivo
            $reader = new Csv();
            $reader->setInputEncoding($codificacion); // codificacion correcta de entrada
            // $reader->setInputEncoding('utf-8');
            $reader->setDelimiter("{$delimiter}"); //delimitador
            $reader->setEnclosure('"');
            $reader->setSheetIndex(0); //establece indice de la hoja a leer
            $spreadsheet = $reader->load($path);
            $worksheet = $spreadsheet->getActiveSheet();
            // obteniendo numero de filas y columas de la hoja actual
            $highestRow = $worksheet->getHighestRow(); // total de filas
            $highestColumn = $worksheet->getHighestColumn(); // total de columnas en letras
            $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn); // total de columnas ne numeros
            $data = [];
            $headers = self::getHeaders($worksheet, $highestColumnIndex);
            // print_r($headers); die();
            for ($row = 1; $row <= $highestRow; ++$row) {
                foreach ($headers as $col => $value) {
                    $valuex = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                    $data[$row][$value] = trim($valuex);
                }
            }
        } catch (Exception $e) {
            throw  $e->getMessage();
        }
        $header = $data[1];
        //eliminamos la cabezera
        $data = array_splice($data, 1);
        return [
            'data' => $data,
            'headers' => $header
        ];
    }

    public static function validateTypeDate($data)
    {
        $errors = [];
        $errors['estado'] = true;
        $fechas = ['FECHA_INGRESO', 'FECHA_NACIMIENTO', 'FECHA_CESE'];
        foreach ($data as $index => $row) {
            foreach ($row as $key => $value) {
                $date = in_array($key, $fechas);
                if ($date) {
                    if (
                        $key == "FECHA_INGRESO" ||
                        ($key == "FECHA_NACIMIENTO" && !empty($value)) ||
                        ($key == "FECHA_CESE" && !empty($value))
                    ) {
                        $valores = explode('/', $value);
                        if (count($valores) == 3 && checkdate($valores[1], $valores[0], $valores[2])) {
                            continue;
                        }
                        $errors['estado'] = false;
                        $errors['tipo'] = 5;
                        $errors['data']['mensaje'] = 'no es una fecha valida';
                        $errors['data']['response'][] = [
                            $key => $index + 2
                        ];
                    }
                }
            }
        }
        return $errors;
    }

    public static function setFormatterDateInFileJson(&$data)
    {
        $fechas = ['FECHA_INGRESO', 'FECHA_NACIMIENTO', 'FECHA_CESE'];
        foreach ($data as $fil => $row) {
            foreach ($row as $col => $value) {
                $date = in_array($col, $fechas);
                if ($date && !empty($value)) {
                    $fecha = str_replace('/', '-', $value);
                    $fecha_ = self::format($fecha, 'Y-m-d');
                    $data[$fil][$col] = $fecha_;
                }
            }
        }
    }

    public static function validateJefeCargo(&$data)
    {

        $personal = self::obtenerDataPersonal();
        $jefes = self::obtenerDataJefeCargo();
        $jefes_excel = array_column($data, 'DOC_JEFE');
        $errors = [];
        $errors['estado'] = true;

        foreach ($jefes_excel as $key => $dni) {
            if ($dni) {
                $persoanId = array_search($dni, $personal);
                $jefeId = array_search($persoanId, $jefes);
                if ($jefeId) {
                    $data[$key]['DOC_JEFE'] = $jefeId;
                } else {
                    $errors['estado'] = false;
                    $errors['tipo'] = 6;
                    $errors['data']['mensaje'] = 'Estas personas no son jefes';
                    $errors['data']['response'][] = [
                        'fila' => $key + 2,
                        'valor' => $dni
                    ];
                }
            }
        }
        return $errors;
    }

    public static function obtenerDataJefeCargo()
    {
        $data = JefesCargoModel::find()
            ->select('id,personal_id')
            ->where(['estado' => true])
            ->asArray()
            ->all();

        $result = [];
        foreach ($data as $key => $value) {
            $result[$value['id']] = $value['personal_id'];
        }
        return $result;
    }

    public static function obtenerDataPersonal()
    {
        $data = PersonalModel::find()
            ->select('id,numero_documento')
            ->where(['estado' => true])
            ->asArray()
            ->all();

        $result = [];
        foreach ($data as $key => $value) {
            $result[$value['id']] = $value['numero_documento'];
        }
        return $result;
    }

    public static function format($fecha = null, $nuevoFormato = null)
    {
        // $fecha = new DateTime($fecha);
        $fecha = new DateTime($fecha, new DateTimeZone('America/Lima'));
        return $fecha->format($nuevoFormato);
    }


    // FUNCIONES APRA CARGA MODULAR

    public static function validateTotalHeadersMod($headers)
    {
        $structura = self::getStructure();
        $errors['estado'] = true;

        foreach ($headers as $key => $value) {
            $dato = in_array($value, $structura);
            if (!$dato) {
                $errors['mensaje'] = 'columnas incorrecta';
                $errors['error'][] = [
                    'columna' => $value,
                ];
                $errors['tipo'] = 2;
                $errors['estado'] = false;
            }
        }
        return $errors;
    }
}
