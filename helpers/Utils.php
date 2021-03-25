<?php

namespace app\helpers;

class Utils
{
    public static function generateToken()
    {
        $time = microtime(true);
        $token = hash("sha256", $time);
        return $token;
    }
}
