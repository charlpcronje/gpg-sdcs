<?php

class Input Extends App {
    function __construct() {
        note("Input->__construct");
        parent::__construct();
    }

    public function isset($inputKey): bool {
        if (is_array($inputKey) && count($inputKey) == 1) {
            $inputKey = (string)$inputKey[0];
        }
        if (isset($_GET[$inputKey]) && !empty($_GET[$inputKey])) {
            return true;
        }
        if (isset($_POST[$inputKey]) && !empty($_POST[$inputKey])) {
            return true;
        }
        return false;
    }

    /* Input keys will get unset in the order specified in the $methodsToForget array.
    If a string is specified with just once method only the once method's key will be unset.
    If an empty string or array is sent for the $methodsToForget all the methods will be unset
    starting with GET, POST. The last Method unset, old value will be returned */

    /* $methodsToForget can be an array, string or null. */
    /**
     * @param $inputKey string
     * @param $methodsToForget array|null
     * @return mixed|null
     */
    public function forgetInput(string $inputKey, $methodsToForget = ['get', 'post']) {
        $oldValue = null;
        if (is_string($methodsToForget)) {
            if (strtolower($methodsToForget) === 'get' && isset($_GET[$inputKey])) {
                $oldValue = $_GET[$inputKey];
                unset($_GET[$inputKey]);
            }
        } elseif (is_array($methodsToForget) && count($methodsToForget) >= 1) {
            foreach($methodsToForget as $method) {
                if (strtolower($method) === 'get' && isset($_GET[$inputKey])) {
                    $oldValue = $_GET[$inputKey];
                    unset($_GET[$inputKey]);
                }
                if (strtolower($method) === 'post' && isset($_POST[$inputKey])) {
                    $oldValue = $_POST[$inputKey];
                    unset($_POST[$inputKey]);
                }
            }
        } elseif((is_array($methodsToForget) && count($methodsToForget)) == 0 || empty($methodsToForget)) {
            $this->forget($inputKey,['get','post']);
        }
        return $oldValue;
    }

    public function forgetEmptyInput() {
        foreach($this->request() as $inputKey => $inputValue) {
            if (empty($inputValue)) {
                if (isset($_GET[$inputKey])) {
                    unset($_GET[$inputKey]);
                }
                if (isset($_POST[$inputKey])) {
                    unset($_POST[$inputKey]);
                }
            }
        }
    }

    /** This is only a reference to the function called `val`
     * @param $inputKey
     * @param $default
     * @return array|mixed|null
     */
    public static function var($inputKey, $default) {
        return static::val($inputKey, $default);
    }

    /** Instantiate the Input Class and call the Request
     * @param $inputKey
     * @param $default
     * @return array|mixed|null
     */
    public static function val($inputKey, $default) {
        return (new Input())->request($inputKey,$default);
    }

    public function request($inputKey = null,$default = null) {
        if (is_array($inputKey) && count($inputKey) == 1) {
            $inputKey = (string)$inputKey[0];
        }
        $value = null;
        // There is a $_GET and a POST with the same key the $_POST key will take precedence
        if (isset($_GET[$inputKey])) {
            $value = $_GET[$inputKey];
        }

        if (isset($_POST[$inputKey])) {
            $value = $_POST[$inputKey];
        }

        if (!isset($_GET[$inputKey]) && isset($default)) {
            $_GET[$inputKey] = $default;
        }

        if (!isset($_POST[$inputKey]) && isset($default)) {
            $_POST[$inputKey] = $default;
        }

        if (!isset($_POST[$inputKey]) && !isset($_GET[$inputKey]) && isset($default)) {
            $value = $default;
        }

        if (!isset($_POST[$inputKey]) && !isset($_GET[$inputKey]) && !isset($default)) {
            unset($_GET['controller']);
            unset($_GET['params']);
            if (isset($_GET) && !empty($_GET)) {
                $value = $_GET;
            }

            if (isset($_POST) && !empty($_POST)) {
                $value = array_merge($_GET,$_POST);
            }
        }
        return $value;
    }
}