<?php
class Autoloader {
    private array $places = [
        'PATH_VIEWS',
        'PATH_MODELS',
        'PATH_CONTROLLERS',
        'PATH_SYSTEM',
        'PATH_HELPERS',
        'PATH_APP',
        'PATH_UPLOAD_EXCEPTIONS'
    ];
    public static function register() {
        spl_autoload_register(function ($class) {
            foreach($this->places as $place) {
                $file = $place.DS.$class.'.php';
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