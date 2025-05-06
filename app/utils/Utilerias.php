<?php
namespace App\Utils;
class Utilerias
{
    public static function jsonToArray(string $json)
    {
        return json_decode($json,true);
    }

    public static function arrayToJson(array $array)
    {
        return json_encode($array);
    }
}