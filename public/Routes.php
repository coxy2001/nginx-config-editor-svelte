<?php

class Routes
{
    public static function index()
    {
        include "index.html";
    }

    public static function restart()
    {
        echo ConfigEditor::restart_nginx();
    }

    public static function available()
    {
        echo ConfigEditor::getSitesAvailable();
    }

    public static function enabled()
    {
        echo ConfigEditor::getSitesEnabled();
    }

    public static function file($filename)
    {
        if ($_SERVER["REQUEST_METHOD"] === "GET") {
            echo ConfigEditor::read_config_raw($filename);
        } elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
            ConfigEditor::write_config_raw($filename, file_get_contents("php://input"));
        } elseif ($_SERVER["REQUEST_METHOD"] === "DELETE") {
            ConfigEditor::delete_config($filename);
        }
    }

    public static function enable($filename)
    {
        ConfigEditor::enable_config($filename);
    }

    public static function disable($filename)
    {
        ConfigEditor::disable_config($filename);
    }
}
