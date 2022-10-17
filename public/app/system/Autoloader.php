<?php
class Autoloader {
    public static array $places = [
        'PATH_VIEWS',
        'PATH_MODELS',
        'PATH_PARSERS',
        'PATH_SYSTEM',
        'PATH_HELPERS',
        'PATH_APP',
        'PATH_UPLOAD_EXCEPTIONS',
        'PATH_SYSTEM_TRAITS'
    ];
    public static function register() {
        spl_autoload_register(function ($class) {
            foreach(self::$places as $place) {
                $file = constant($place).DS.$class.'.php';
                if ($place == 'PATH_PARSERS') {
                    $file = constant($place).DS.$class.'Parser.php';
                }
                if (file_exists($file)) {
                    require $file;
                    return true;
                }
            }
            return false;
        });
    }
}
Autoloader::register();