<?php

namespace App;

class FileReader
{
    public function getGapsFromFile($filename)
    {
        if (!file_exists($filename)) {
            echo "Arquivo não encontrado: $filename\n";
            return [];
        }

        $gaps = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        return $gaps;
    }
}
