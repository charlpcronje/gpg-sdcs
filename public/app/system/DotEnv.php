<?php
include(__DIR__.DS.'DotEnv'.DS.'Exception.php');
include(__DIR__.DS.'DotEnv'.DS.'InvalidPathException.php');

class DotEnv extends \ArrayObject {

    private $_path;
    private $_file;
    private $_dir;
    private $_data;

    public function __construct($path = PATH_ROOT, $setEnvironmentVariables = true, bool $processSections = true, int $scannerMode = INI_SCANNER_TYPED)  {
        $this->setPath($path);
        $data = self::parseFile($this->_file, $processSections, $scannerMode);
        $this->setData($data);
        if ($setEnvironmentVariables){
            self::setEnvironmentVariables($this->_data);
        }
    }

    public function setPath($path) {
        if (is_dir($path)){
            $path = rtrim($path, '/') . '/';
            $this->_path = $path;
            $this->setDir($path);
        } else if (file_exists($path)) {
            $this->_path = $path;
            $this->setFile($path);
        }
    }

    public function setDir(string $dir){
        $this->_dir = $dir;
        if (!is_dir($this->_dir)){
            throw new InvalidPathException("$this->_file is not an existing directory.");
        }
        $this->_file = $dir . ".env";
        if (!file_exists($this->_file)){
            throw new InvalidPathException("$this->_file is not an existing file.");
        }
    }

    public function setFile(string $file){
        $this->_dir = dirname($file);
        $this->_file = $file;
        if (!file_exists($this->_file)){
            throw new InvalidPathException("$this->_file is not an existing file.");
        }
    }

    public function loadArray(array $array, bool $setEnvironmentVariables, int $scannerMode = INI_SCANNER_TYPED){
        if ($scannerMode == INI_SCANNER_TYPED){
            $array = self::scanArrayTypes($array);
        }
        $this->_data = $array;
        if ($setEnvironmentVariables){
            self::setEnvironmentVariables($this->_data);
        }
    }

    public static function scanArrayTypes(array $array){
        foreach ($array as $property => $value){
            if (is_array($value)){
                $value = self::scanArrayTypes($array);
            } else {
                if (is_string($value)){
                    switch ($value) {
                        case 'true':
                        case 'yes':
                        case '1':
                            $value = true;
                        break;
                        case 'false':
                        case 'no':
                        case '0':
                            $value = false;
                        break;
                    }
                }
            }
            $array[$property] = $value;
        }
    }

    public function loadString(string $string, bool $setEnvironmentVariables = true, bool $processSections = true, int $scannerMode = INI_SCANNER_TYPED){
        $data = self::parseString($string, $processSections, $scannerMode);
        $this->setData($data);
        if ($setEnvironmentVariables){
            self::setEnvironmentVariables($this->_data);
        }
    }

    public static function parseFile(string $file, bool $processSections = true, int $scannerMode = INI_SCANNER_TYPED):array{
        return parse_ini_file($file, $processSections, $scannerMode);
    }

    public static function parseString(string $string, bool $processSections = true, int $scannerMode = INI_SCANNER_TYPED):array{
        return parse_ini_string($string, $processSections, $scannerMode);
    }

    public static function setEnvironmentVariables($iterable, string $accessName=""){
        foreach ($iterable as $variable => $value){
            if (is_array($value) || is_object($value)){
                if (empty($accessName)){
                    $accessName = $variable;
                } else {
                    $accessName .= "_" . $variable;
                }
                self::setEnvironmentVariables($value, $accessName);
            } else {
                if (!empty($accessName)){
                    $variable = $accessName . "_" . $variable;
                }
                self::setEnv($variable, $value);
            }
        }
    }

    public static function setEnv($variable, $value){
        if (is_array($value) || is_object($value)){
            self::setEnvironmentVariables($value, $variable);
        } else {
            putenv("$variable=$value");
            $_ENV[$variable] = $value;
        }
    }

    public static function arrayToObject($array){
        $object = (object) $array;
        foreach ($object as $variable => $value){
            if (is_array($value)){
                $object->$variable = (object) self::arrayToObject($value);
            }
        }
        return((object) $object);
    }

    public static function objectToArray($object){
        $array = [];
        foreach ($object as $variable => $value){
            if (is_object($value)){
                $array[$variable] = (array) self::objectToArray($value);
            }
        }
        return((array) $array);
    }

    public function setData($data){
        //parent::__construct($data, \ArrayObject::ARRAY_AS_PROPS);
        parent::__construct($data);
        $this->_data = self::arrayToObject($data);
    }

    public function data(){
        return $this->_data;
    }

    public function serialize()
    {
        return(serialize($this->_data));
    }

    public function unserialize($serialized)
    {
        $this->setData(unserialize($serialized));
    }

    public function __get($name)
    {
        if ($name[0] == "_") {
            return $this->$name;
        }
        if (is_array($this->_data)){
            if (array_key_exists($name, $this->_data)){
                if (is_array($this->_data[$name]) || is_object($this->_data[$name])){
                    return self::arrayToObject($this->_data[$name]);
                } else {
                    return $this->_data[$name];
                }
            } else {
                return "";
            }
        } else {
            if (isset($this->_data->$name)){
                if (is_array($this->_data->$name) || is_object($this->_data->$name)){
                    return self::arrayToObject($this->_data->$name);
                } else {
                    return $this->_data->$name;
                }
            } else {
                return "";
            }
        }
    }

    public function __set($name, $value)
    {
        if ($name[0] == "_") {
            $this->$name = $value;
        } else {
            if (is_object($this->_data)){
                $this->_data->$name = $value;
            } else {
                $this->_data[$name] = $value;
            }
            //$this[$name] = $value;
            self::setData($this->_data);
            parent::offsetSet($name, $value);
            self::setEnv($name, $value);
        }
    }

    public function offsetSet($index, $newval)
    {
        $this->__set($index, $newval);
        parent::offsetSet($index, $newval);
    }

    public function offsetGet($index)
    {
        //return $this->__get($index);
        /*if (is_object($this->_data)){
            if (is_object($this->_data->$index)){
                return $this->__get(self::objectToArray($this->_data->$index));
            } else {
                return $this->_data->$index;
            }
        } else {
            if (is_object($this->_data[$index])){
                return $this->__get(self::objectToArray($this->_data[$index]));
            } else {
                return $this->_data[$index];
            }
        }*/
        return parent::offsetGet($index);
    }

    public function offsetExists($index)
    {
        /*if (is_array($this->_data) || is_a($this->_data, \ArrayObject::class)){
            return array_key_exists($index, $this->_data);
        } else {
            return (isset($this->_data->$index) || property_exists($this->_data, $index));
        }*/
        return parent::offsetExists($index);
    }

}