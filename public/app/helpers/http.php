<?php
// Method: POST, PUT, GET etc
// Data: array("param" => "value") ==> index.php?param=value

/**
 * CallAPI
 *
 * @param string $method - POST, PUT, GET etc
 * @param string $url
 * @param array $data
 * @param array $options
 * @return bool|string
 */
function CallAPI(string $method, string $url, array $data = [], array $options = []) {
    $defaults = [
        "auth" => false,
        "auth_user" => "",
        "auth_pwd" => ""
    ];
    $params = array_merge($defaults,$options);
    $curl = curl_init();

    switch ($method) {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if (count($data)) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }
        break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
        break;
        default:
            if (count($data)) {
                $url = sprintf("%s?%s", $url, http_build_query($data));
            }
        break;
    }
    // Optional Authentication:
    if ($params['auth']) {
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $params['auth_user'].':'.$params['auth_pwd']);
    }
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
}

function get($key = null,$default = null) {
    // If no key is specified but there are get inputs then return them all
    if (!isset($key) && App::dataKeyExists('input.get')) {
        return App::data('input.get');
    }
    if (App::dataKeyExists('input.get.'.$key)) {
        return App::data('input.get.' . $key);
    }
    return $default;
}

function post($key = null,$default = null) {
    // If no key is specified but there are post inputs then return them all
    if (!isset($key) && App::dataKeyExists('input.post')) {
        return App::data('input.post');
    }
    if (App::dataKeyExists('input.post.'.$key)) {
        return App::data('input.post.' . $key);
    }
    return $default;
}

/**
 * @param $key
 * @param $default
 * @return mixed|null
 */
function request($key = null,$default = null) {
    // If no key is specified but there are inputs then return them all
    if (!isset($key) && App::dataKeyExists('input')) {
        return App::data('input');
    }
    if (App::dataKeyExists('input.'.$key)) {
        return App::data('input.' . $key);
    }
    return $default;
}

/**
 * @param $key
 * @return mixed|null
 */
function files($key = null) {
    // If no key is specified but there are files then return them all
    if (!isset($key) && App::dataKeyExists('input.files')) {
        return App::data('input.files');
    }
    if (App::dataKeyExists('input.files.'.$key)) {
        return App::data('input.files.' . $key);
    }
    return null;
}

function input($key,$default = null) {
    Input::val($key,$default);
}

