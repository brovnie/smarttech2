<?php

const SETTINGS = [
    "db" => [
        "user" => "root",
        "password" => "",
        "host" => "localhost",
        "db" => "smarttech-checkin"
    ]
];

abstract class Db
{
    private static $conn;



    public static function getInstance()
    {



        if (self::$conn == null) {
            self::$conn = new PDO('mysql:host=' . SETTINGS['db']['host'] . ';dbname=' . SETTINGS['db']['db'], SETTINGS['db']['user'], SETTINGS['db']['password']);
            return self::$conn;
        } else {
            return self::$conn;
        }
    }
}
