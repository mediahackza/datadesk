<?php

class encryption {
    static private $key = "mhcmembers";
    static private $ciphering = "AES-256-CTR";
    static private $options = 0;
    static private $encryption_iv = '135d72g46ss81t35';


    static function set_key($key) {
        self::$key = $key;
    }

    static function set_ciphering($ciphering) {
        self::$ciphering = $ciphering;
    }

    static function set_options($options) {
        self::$options = $options;
    }

    static function set_iv($iv) {
        self::$encryption_iv = $iv;
    }

    static function encrypt($to_encrypt) {
        $encryption_key = self::$key;
        $encryption = openssl_encrypt($to_encrypt, self::$ciphering, self::$key, self::$options, self::$encryption_iv);
        return $encryption;
    }

    static function decrypt($to_decrypt) {
        $decryption_key = self::$key;
        $decryption = openssl_decrypt($to_decrypt, self::$ciphering, self::$key, self::$options, self::$encryption_iv);
        return $decryption;
    }

}

?>