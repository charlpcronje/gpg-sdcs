<?php

/**
 * @param mixed $var
 * This function does a var_dump and then die;
 *
 * @return void
 */
function dd($var) {
    $callingClass = getCallingClass();
    if (empty($callingClass)) {
        $callingClass = 'Global Scope';
    }
    echo 'Dumping and Die Called From: <strong>'.$callingClass."</strong> \r\n\r\n <br/><br/>";
    var_dump($var);
    die;
}

/**
 * @param mixed $var
 * This function does a
 *
 * @return void
 */
function ddc($var) {
    echo 'Dumping and Continue Called From: <strong>'.getCallingClass()."</strong> \r\n\r\n <br/><br/>";
    var_dump($var);
    echo '</pre>';
}

/**
 * @param mixed $var
 * @return void
 */
function pd($var) {
    $callingClass = getCallingClass();
    if (empty($callingClass)) {
        $trace = debug_backtrace();
        $callingClass = 'File: "'.$trace[0]['file'].'" | Line: "'.$trace[0]['line'].'""';
    }
    echo 'Print and Die Called From: <strong>'.$callingClass."</strong> \r\n\r\n <br/><br/> <pre>";
    print_r($var);
    echo '</pre>';
    die;
}

/**
 * @return mixed|void
 */
function getCallingClass() {
    //get the trace
    $trace = debug_backtrace();

    // Get the class that is asking for who awoke it
    $class = $trace[1]['class'] ?? ($trace[1]['class'] = 'Static');

    // +1 to i cos we have to account for calling this function
    for ($i=1,$iMax = count($trace); $i< $iMax; $i++ ) {
        if (isset($trace[$i])) { // is it set?
            if ($class != @$trace[$i]['class']) {// is it a different class
                return @$trace[$i]['class'];
            }
        }
    }
}

/**
 * @param string $message
 * @return void
 */
function note(string $message) {
    Log::getInstance()->info($message);
}

/**
 * @param string $message
 * @return void
 */
function debug(string $message) {
    Log::getInstance()->debug($message);
}

function errorLog(string $message) {
    Log::getInstance()->error($message);
}

function warning(string $message) {
    Log::getInstance()->warning($message);
}

function critical(string $message) {
    Log::getInstance()->critical($message);
}

function notice(string $message) {
    Log::getInstance()->notice($message);
}

function alert(string $message) {
    Log::getInstance()->alert($message);
}

function emergency(string $message) {
    Log::getInstance()->emergency($message);
}
