<?php
namespace App\library;

class OcoderHelper {

    public static function getFileName($url) {
        $path = parse_url($url, PHP_URL_PATH);
        $pathFragments = explode('/', $path);
        $end = end($pathFragments);
        return $end;
    }
    
    public static function getFileAndFolderName($url) {
        $path = parse_url($url, PHP_URL_PATH);
        $pathFragments = explode('/', $path);
        if(sizeof($pathFragments) < 2) return "";        
        $folder = $pathFragments[sizeof($pathFragments) - 2];
        $end = end($pathFragments);
        return $folder.'/'.$end;
    }
}
