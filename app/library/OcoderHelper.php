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
        if (sizeof($pathFragments) < 2)
            return "";
        $folder = $pathFragments[sizeof($pathFragments) - 2];
        $end = end($pathFragments);
        return $folder . '/' . $end;
    }

    private $iv = '9f8d6ba7543c2e10'; #Same as in JAVA
    private $key = 'b0192c3a74f65e8d'; #Same as in JAVA

    /**
     * @param string $str
     * @param bool $isBinary whether to encrypt as binary or not. Default is: false
     * @return string Encrypted data
     */

    function encrypt($str, $isBinary = false) {
        if (!$str) {
            return;
        }
        $iv = $this->iv;
        $str = $isBinary ? $str : ($str);

        $td = mcrypt_module_open('rijndael-128', ' ', 'cbc', $iv);

        mcrypt_generic_init($td, $this->key, $iv);
        $encrypted = mcrypt_generic($td, $str);

        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return $isBinary ? $encrypted : bin2hex($encrypted);
    }

    /**
     * @param string $code
     * @param bool $isBinary whether to decrypt as binary or not. Default is: false
     * @return string Decrypted data
     */
    function decrypt($code, $isBinary = false) {
        $code = $isBinary ? $code : $this->hex2bin($code);
        $iv = $this->iv;

        $td = mcrypt_module_open('rijndael-128', ' ', 'cbc', $iv);

        mcrypt_generic_init($td, $this->key, $iv);
        $decrypted = mdecrypt_generic($td, $code);

        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return $isBinary ? trim($decrypted) : (trim($decrypted));
    }

    protected function hex2bin($hexdata) {
        $bindata = '';

        for ($i = 0; $i < strlen($hexdata); $i += 2) {
            $bindata .= chr(hexdec(substr($hexdata, $i, 2)));
        }

        return $bindata;
    }

    public static function GenerateIcon($value, $id, $url) {
        $generate = '<div class="generate_input" style="position: relative;">
			<input class="form-control" type="text" name="' . $id . '" value="' . $value . '" id="' . $id . '" style="padding: 0 122px 0 45px;">
			<i class="fa fa-eye" aria-hidden="true" title="" id="preview_image" onmouseover="PreviewImage(\'preview_image\',\'' . $id . '\')" style="position: absolute;top: 0;font-size: 20px;line-height: 32px;padding: 0 10px;background: #eeeeee; border: 1px solid #cccccc; cursor: pointer;"></i>
			<button type="button" class="btn btn-primary" onclick="BrowseServer(\'' . $id . '\',\'' . $url . ' \')" style="position: absolute; top: 0; right: 55px; border-radius: 0; background: #f2f2f2; border: 1px solid #cccccc; color: black;">Select</button>
			<span onclick="ResetValue(\'' . $id . '\')" style="display: inline-block; position: absolute; top: 0; right: 0; line-height: 32px; font-size: 25px; font-weight: bold; padding: 0px 20px; cursor: pointer; background: #f2f2f2;    border: 1px solid #cccccc; color: #000;">x</span></div>';
        return $generate;
    }

    
    public static function HTMLAudio($value, $id, $url) {
        $generate = '<div class="generate_input" style="position: relative;">
			<input class="form-control" type="text" name="' . $id . '" value="' . $value . '" id="' . $id . '" style="padding: 0 122px 0 12px;">
			<button type="button" class="btn btn-primary" onclick="BrowseServer(\'' . $id . '\',\'' . $url . ' \')" style="position: absolute; top: 0; right: 55px; border-radius: 0; background: #f2f2f2; border: 1px solid #cccccc; color: black;">Select</button>
			<span onclick="ResetValue(\'' . $id . '\')" style="display: inline-block; position: absolute; top: 0; right: 0; line-height: 32px; font-size: 25px; font-weight: bold; padding: 0px 20px; cursor: pointer; background: #f2f2f2;    border: 1px solid #cccccc; color: #000;">x</span></div>';
        return $generate;
    }
}
