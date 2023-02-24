<?php

class ConfigEditor
{
    const SITES_AVAILABLE = "/etc/nginx/sites-available";
    const SITES_ENABLED = "/etc/nginx/sites-enabled";

    public static function read_config(string $filename)
    {
        $source = self::SITES_AVAILABLE . DIRECTORY_SEPARATOR . $filename;
        return FileEditor::read_lines($source);
    }

    public static function write_config(string $filename, array $lines)
    {
        $source = self::SITES_AVAILABLE . DIRECTORY_SEPARATOR . $filename;
        return FileEditor::write_lines($source, $lines);
    }

    public static function read_config_raw(string $filename): string
    {
        $source = self::SITES_AVAILABLE . DIRECTORY_SEPARATOR . $filename;
        return FileEditor::read_text($source);
    }

    public static function write_config_raw(string $filename, string $text)
    {
        $source = self::SITES_AVAILABLE . DIRECTORY_SEPARATOR . $filename;
        return FileEditor::write_text($source, $text);
    }

    public static function delete_config(string $filename)
    {
        $source = self::SITES_AVAILABLE . DIRECTORY_SEPARATOR . $filename;
        $destination = self::SITES_ENABLED . DIRECTORY_SEPARATOR . $filename;
        FileEditor::delete_file($source);
        FileEditor::delete_file($destination);
    }

    public static function enable_config(string $filename)
    {
        $source = self::SITES_AVAILABLE . DIRECTORY_SEPARATOR . $filename;
        $destination = self::SITES_ENABLED . DIRECTORY_SEPARATOR . $filename;
        FileEditor::link_file($source, $destination);
    }

    public static function disable_config(string $filename)
    {
        $destination = self::SITES_ENABLED . DIRECTORY_SEPARATOR . $filename;
        FileEditor::delete_file($destination);
    }

    public static function restart_nginx()
    {
        $out = shell_exec("sudo nginx -t 2>&1");
        if (strpos($out, "test is successful")) {
            shell_exec("sudo nginx -s reload >/dev/null 2>/dev/null &");
            $status = "ok";
        } else {
            $status = "failed";
        }
        return json_encode([
            "status" => $status,
            "message" => $out,
        ]);
    }

    public static function getSitesAvailable()
    {
        $files = array_filter(scandir(self::SITES_AVAILABLE), function ($item) {
            return is_file(self::SITES_AVAILABLE . DIRECTORY_SEPARATOR . $item);
        });
        return json_encode(array_values($files));
    }

    public static function getSitesEnabled()
    {
        $files = array_filter(scandir(self::SITES_ENABLED), function ($item) {
            return is_file(self::SITES_ENABLED . DIRECTORY_SEPARATOR . $item);
        });
        return json_encode(array_values($files));
    }
}
