<?php

namespace app\helpers;

use Yii;

class Response
{
    public static function JSON($status = 500, $message = null, $data = [])
    {
        // $headers = Yii::$app->response->headers;
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];
        $response->send();
        Yii::$app->end();
    }
}
