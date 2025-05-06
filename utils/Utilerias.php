<?php

class Utilerias
{
    public static function jsonToArray($json)
    {
        return json_decode($json,true);
    }
}