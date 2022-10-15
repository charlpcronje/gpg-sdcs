<?php

trait DataTraits {
    /*
    |--------------------------------------------------------------------------
    | Set Data to Singleton $this->output->data
    |--------------------------------------------------------------------------
    |
    | The data to iterate through can also be specified by adding a 3rd param
    | @param string $dotName is dot notation string, each '.' separates an
    | object but in this notation you can also select arrays by adding a ':'
    | So the string may look something like:
    | -------------------------------------------------------------------------
    | "person.hobbies:0.hobbyName" Equal to "$person->hobbies[0]->hobbyName"
    |
    | @param vector $value is the value to set equal to the object
    | specified by $dotName.
    */
    public function setData($dotName,$value,$data = null): App {
        $checkResult      = $this->checkForEscapedDots($dotName);
        if ($checkResult->strEscaped) {
            $escapedDots      = explode('.',$checkResult->dotName);
            $dots             = [];
            foreach($escapedDots as $escapedDot) {
                $dots[] = str_replace('#$#','.',$escapedDot);
            }
        } else {
            $dots = explode('.',$dotName);
        }

        if ($data === null && isset($this->output)) {
            $data = $this->output->data;
        }

        // Repeat until the second last dot
        while (count($dots) > 1) {
            $dot   = array_shift($dots);
            $dot   = str_replace('#$#','.',$dot);

            // Check if array is specified by using the ':' operator
            $array = explode(':',$dot,2);
            $dot   = $array[0];

            /* If sizeof($array) > 1 it means that an array was found in this dot,
             * so the type is being set to array here so that I'm not trying to handle an
             * object like an array */
            if (count($array) > 1) {
                // Add Array to Object
                if (!isset($data->{$dot}) || !is_array($data->{$dot})) {
                    $data->{$dot} = [];
                }
                // Set $data to combined object and Array
            } else {
                // Add Object to Object
                if (!isset($data->{$dot}) || !is_object($data->{$dot})) {
                    if (!isset($data)) $data = (object)[];
                    $data->{$dot} = (object)[];
                }
                // Set $data to combined object
                $data = $data->{$dot};
            }

            // Add Array keys to object
            if (count($array) > 1) {
                $data = $this->setArray($data->{$dot},$array[1]);
            }
        }
        // Assign the last dot
        $dot = array_shift($dots);

        // Check if there are any array is the last dot
        $array = explode(':',$dot,2);
        $dot = $array[0];

        if (count($array) > 1) {
            $this->setArray($data->{$dot},$array[1],$value);
        } else {
            if (!empty($dot) && isset($data)) {
                $data->{$dot} = $value;
            }
        }
        return $this;
    }

    private function checkForEscapedDots($dotName): object {
        $dots             = explode('.',$dotName);
        $escapedDots      = [];
        $escapeBeginFound = false;
        $escapeEndFound   = false;
        $escapedDotsFound = false;
        $stingsToEscape   = [];
        $escapeChar       = '"';

        foreach($dots as $dot) {
            if (strpos($dot,'"') === 0 || strpos($dot,"'") === 0) {
                if (strpos($dot,"'") === 0) {
                    $escapeChar = "'";
                }
                $dot = substr($dot,1);
                $escapeBeginFound = true;
            }

            if (substr($dot,-1,1) === '"' || substr($dot,-1,1) === "'") {
                $dot = str_replace($escapeChar,'',$dot);
                $escapeEndFound = true;
            }

            if ($escapeBeginFound) {
                $escapedDots[] = $dot;
                if ($escapeEndFound) {
                    $escapeEndFound   = false;
                    $escapeBeginFound = false;
                    $escapedDotsFound = true;
                    if (count($escapedDots) === 1) {
                        $stingsToEscape[] = $escapedDots[0];
                    } else {
                        $stingsToEscape[] = implode('.',$escapedDots);
                    }
                    $escapedDots      = [];
                }
            }
        }

        if ($escapedDotsFound) {
            foreach($stingsToEscape as $stringToEscape) {
                $newString      = str_replace('.','#$#',$stringToEscape);
                $dotName        = str_replace([$stringToEscape,$escapeChar],[$newString,''],$dotName);
            }
        }

        return (object)[
            'strEscaped' => $escapedDotsFound,
            'dotName'    => $dotName
        ];
    }

    public static function dataKeyExists($dotName,$data = null): bool {
        $app = new App();
        return $app->dataKeyExist($dotName,$data);
    }

    public function setArray(&$array, $key, $value = null) {
        if ($key === null) {
            $array = $value;
            return $array;
        }
        $keys = explode(':', $key);
        while (count($keys) > 1) {
            $key = array_shift($keys);
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = array();
            }
            $array = &$array[$key];
        }
        if ($value !== null) {
            if (is_array($array)) {
                return $array[array_shift($keys)] = $value;
            }
        }
        if (is_array($array)) {
            return $array[array_shift($keys)] = (object)[];
        }
    }

    public function getArray(&$array,$key,$default = null,$forget = false) {
        if ($key === null) {
            return $array;
        }
        if (isset($array[$key])) {
            return $array[$key];
        }

        foreach (explode(':', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return $default;
            }
            $array = $array[$segment];
            if ($forget) {
                unset($array[$segment]);
            }
        }
        return $array;
    }

    /*
   |--------------------------------------------------------------------------
   | Get Data to Singleton $this->output->data
   |--------------------------------------------------------------------------
   |
   | The data to iterate through can also be specified by adding a 2nd param
   | @param string $dotName is dot notation string, each '.' separates an
   | object but in this notation you can also select arrays by adding a ':'
   | So the string may look something like:
   | -------------------------------------------------------------------------
   | "person.hobbies:0.hobbyName" Equal to "$person->hobbies[0]->hobbyName"
   |
   | @param boolean (optional) $forget, if $forget is set to true the value
   | will be deleted from the output singleton as soon as it is retrieved one
   | last time
   */
    public function getData($dotName,$default = null,$data = null,$forget = false) {
        $checkResult          = $this->checkForEscapedDots($dotName);
        if ($checkResult->strEscaped) {
            $escapedDots      = explode('.',$checkResult->dotName);
            $dots             = [];
            foreach($escapedDots as $escapedDot) {
                $dots[] = str_replace('#$#','.',$escapedDot);
            }
        } else {
            $dots = explode('.',$dotName);
        }

        if ($data === null) {
            $data = $this->output->data;
        }
        // Repeat until the second last dot
        while (count($dots) > 1) {
            $dot = array_shift($dots);
            $dot  = str_replace('#$#','.',$dot);
            $array = explode(':',$dot,2);
            $dot = $array[0];
            if (count($array) > 1) {
                $data = $this->getArray($data->{$dot},$array[1]);
            } else {
                if (isset($data->{$dot})) {
                    $data = $data->{$dot};
                }
            }
        }
        $dot = array_shift($dots);
        $array = explode(':',$dot,2);
        $dot = $array[0];

        if (count($array) > 1) {
            if ($forget) {
                $response = $this->getArray($data->{$dot},$array[1],$default,true);
            } else {
                $response = $this->getArray($data->{$dot},$array[1],$default);
            }
            return $response;
        } elseif(isset($data->{$dot})) {
            $response = $data->{$dot};
            if ($forget) {
                unset($data->{$dot});
            }
            if (isClosure($response)) {
                $closureResponse = $response->__invoke();
                $this->forget($dotName);
                $this->setData($dotName,$closureResponse);
                return $closureResponse;
            }
            return $response;
        }
        return null;
    }

    public static function escapedDotName($dotName) {
        $app = new App();
        return $app->checkForEscapedDots($dotName)->dotName;
    }

    public static function data($dotName,$value = null,$data = null) {
        $app = new App();
        if (isset($value)) {
            return $app->setData($dotName,$value,$data);
        }
        return $app->getData($dotName,null,$data);
    }

    public static function forgetKey($dotName,$data = null) {
        return (new App())->getData($dotName,null,$data,true);
    }

    public function dataKeyExist($dotName,$data = null): bool {
        if ($this->getData($dotName,null,$data) !== null) {
            return true;
        }
        return false;
    }

    public function forget($dotName,$data = null) {
        return $this->getData($dotName,null,$data,true);
    }

    // @alias for setData but only sets values to $this->output-data singleton
    public function set($dotName,$value) {
        $this->setData($dotName,$value);
    }
}
