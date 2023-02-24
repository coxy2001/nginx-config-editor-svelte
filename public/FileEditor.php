<?php

class FileEditor
{
    public static function read_text(string $filename): string
    {
        $text = "";
        if (file_exists($filename)) {
            $file = fopen($filename, "r");
            if ($file && filesize($filename)) {
                $text = fread($file, filesize($filename));
                fclose($file);
            }
        }
        return $text;
    }

    public static function write_text(string $filename, string $text): bool
    {
        $file = fopen($filename, "w");
        if ($file) {
            fwrite($file, $text);
            return fclose($file);
        }
        return false;
    }

    public static function read_lines(string $filename): array
    {
        $lines = [];
        if (file_exists($filename)) {
            $file = fopen($filename, "r");
            if ($file) {
                while (!feof($file)) {
                    array_push($lines, fgets($file));
                }
                fclose($file);
            }
        }
        return $lines;
    }

    public static function write_lines(string $filename, array $lines): bool
    {
        $file = fopen($filename, "w");
        if ($file) {
            foreach ($lines as $line) {
                fwrite($file, $line);
            }
            return fclose($file);
        }
        return false;
    }

    public static function delete_file(string $filename): bool
    {
        if (file_exists($filename))
            return unlink($filename);
        else
            return false;
    }

    public static function link_file(string $source, string $destination): bool
    {
        if (file_exists($source) && !file_exists($destination))
            return symlink($source, $destination);
        else
            return false;
    }
}
