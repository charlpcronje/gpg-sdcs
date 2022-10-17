<?php

function env($var) {
    $env = new DotEnv();
    return $_ENV[$var];
}

function isClosure($var): bool {
    return ($var instanceof Closure);
}

// Find Class Ancestors (Parents and Parents of Parents)
function getAncestors($class): array {
    for ($classes[] = $class; $class = get_parent_class ($class); $classes[] = $class);
    return $classes;
}

function dotNotation($string) {
    return str_replace(['-','\\','/'],'.',$string);
}

/** Check if $object is valid $class instance
 * @access public
 * @param mixed $object Variable that need to be checked against className
 * @param string $class ClassName
 * @return null
 */
function isInstanceOf($object, $class): ?bool {
    return $object instanceof $class;
}

/** This function will return clean variable info
 * @param mixed $var
 * @param string $indent Indent is used when dumping arrays recursively
 * @param string $indent_close_bracet Indent close bracket param is used
 * Internally for array output. It is shorter that var indent for 2 spaces
 * @return null
 */
function cleanVarInfo($var, string $indent = '&nbsp;&nbsp;', string $indent_close_bracet = ''): ?string {
    if (is_object($var)) {
        return 'Object (class: '.get_class($var).')';
    } elseif (is_resource($var)) {
        return 'Resource (type: '.get_resource_type($var).')';
    } elseif (is_array($var)) {
        $result = 'Array (';
        if (count($var)) {
            foreach ($var as $k => $v) {
                $k_for_display = is_integer($k) ? $k : "'" . clean($k) . "'";
                $result .= "\n".$indent.'['.$k_for_display.'] => ' .cleanVarInfo($v,$indent.'&nbsp;&nbsp;',$indent_close_bracet.$indent);
            }
        }
        return $result."\n$indent_close_bracet)";
    } elseif (is_int($var)) {
        return '(int)'.$var;
    } elseif (is_float($var)) {
        return '(float)'.$var;
    } elseif (is_bool($var)) {
        return $var ? 'true' : 'false';
    } elseif (is_null($var)) {
        return 'NULL';
    } else {
        return "(string) '".clean($var)."'";
    }
}

/**
 * Equivalent to htmlspecialchars(), but allows &#[0-9]+ (for unicode)
 * This function was taken from punBB codebase <http://www.punbb.org/>
 *
 * @param string $str
 * @return string
 */
function clean(string $str): string {
    $str = preg_replace('/&(?!#[0-9]+;)/s','&amp;',$str);
    return str_replace(array('<', '>', '"'),array('&lt;', '&gt;', '&quot;'),$str);
}

/**
 * This function will return true if $str is valid function name (made out of alpha numeric characters + underscore)
 *
 * @param string $str
 * @return boolean
 */
function isValidFunctionName($str): bool {
    $check_str = trim($str);
    if ($check_str == '') {
        return false; // empty string
    }

    $first_char = substr($check_str,0,1);
    if (is_numeric($first_char)) {
        return false; // first char can't be number
    }
    return (boolean) preg_match("/^([a-zA-Z0-9_]*)$/",$check_str);
}

/**
 * Check if specific string is valid sha1() hash
 *
 * @param string $hash
 * @return boolean
 */
function isValidHash(string $hash): bool {
    return ((strlen($hash) == 32) || (strlen($hash) == 40)) && (boolean) preg_match("/^([a-f0-9]*)$/", $hash);
}

/**
 * Return variable from hash (associative array). If value does not exists
 * return default value
 *
 * @access public
 * @param array $from Hash
 * @param string $name
 * @param mixed $default
 * @return mixed
 */
function arrayVAR(array &$from, string $name, $default = null) {
    if (is_array($from)) {
        return isset($from[$name]) ? $from[$name] : $default;
    }
    return $default;
}

/**
 * This function will return ID from array variables. Default settings will get 'id'
 * variable from $_GET. If ID is not found function will return NULL
 *
 * @param string $varName Variable name. Default is 'id'
 * @param array|null $from Extract ID from this array. If NULL $_GET will be used
 * @param mixed $default Default value is returned in case of any error
 * @return integer
 */
function getID(string $varName = 'id', array $from = null, $default = null): int {
    $varName = trim($varName);
    if ($varName == '') {
        return $default; // empty var name?
    }
    if (is_null($from)) {
        $from = $_GET;
    }
    if (!is_array($from)) {
        return $default; // $from is array?
    }
    if (!isValidFunctionName($varName)) {
        return $default; // $var_name is valid?
    }

    $value = arrayVar($from, $varName, $default);
    return is_numeric($value) ? (integer) $value : $default;
}

/**
 * Flattens the array. This function does not preserve keys, it just returns
 * array indexed form 0 .. count - 1
 *
 * @access public
 * @param array $array If this value is not array it will be returned as one
 * @return array
 */
function arrayFlat($array): array {
    if (!is_array($array)) {                // Not an array
        return array($array);
    }
    $result = array();                      // Prepare result

    foreach ($array as $value) {            // Loop elements
        if (is_array($value)) {             // Sub-Element is array? Flat it
            $value = arrayFlat($value);
            foreach ($value as $subValue) {
                $result[] = $subValue;
            }
        } else {
            $result[] = $value;
        }
    }
    return $result;
}

/**
 * This function will return max upload size in bytes
 *
 * @param void
 * @return integer
 */
function getMaxUploadSize(): int {
    return min(
        phpConfigValueToBytes(ini_get('upload_max_filesize')),phpConfigValueToBytes(ini_get('post_max_size'))
    );
}

/**
 * This function will return max execution time in seconds.
 *
 * @param void
 * @return integer
 */
function getMaxExecution_time(): int {
    $max = ini_get("max_execution_time");
    if (!$max) {
        $max = 0;
    }
    return $max;
}

/**
 * Convert PHP config value (2M, 8M, 200K...) to bytes
 *
 * This function was taken from PHP documentation
 *
 * @param string $val
 * @return integer
 */
function phpConfigValueToBytes(string $val): int {
    $val = trim($val);
    if ($val == "") {
        return 0;
    }
    $last = strtolower($val[strlen($val) - 1]);
    switch ($last) {
        // The 'G' modifier is available since PHP 5.1.0
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }
    return $val;
}

/**
 * This function will walk recursively through array and strip slashes from scalar values
 *
 * @param array $array
 * @return null
 */
function arrayStripSlashes(array &$array): ?array {
    foreach ($array as $k => $v) {
        if (is_array($v)) {
            arrayStripSlashes($array[$k]);
        } else {
            $array[$k] = stripslashes($v);
        }
    }
    return $array;
}

/**
 * Generates a random id to be used as id of HTML elements.
 * It does not guarantee the uniqueness of the id, but the probability
 * of generating a duplicate id is very small.
 *
 */
function genId(): string {
    static $ids = array();
    do {
        $time = time();
        $rand = rand(0, 1000000);
        $id = "og_" . $time . "_" . $rand;
    } while (arrayVar($ids, $id, false));
    $ids[$id] = true;
    return $id;
}

function zipSupported(): bool {
    return class_exists('ZipArchive',false);
}

function pluginSort($a,$b): int  {
    if (isset($a ['order']) && isset($b ['order'])) {
        if ($a ['order'] == $b ['order']) {
            return 0;
        }
        return ($a ['order'] < $b ['order']) ? - 1 : 1;
    } elseif (isset($a ['order'])) {
        return - 1;
    } elseif (isset($b ['order'])) {
        return 1;
    } else {
        return strcasecmp($a ['name'], $b ['name']);
    }
}