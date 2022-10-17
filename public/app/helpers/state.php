<?php
function session($key,$value = null,$default = null) {
    if (isset($value)) {
        App::data('session.'.$key,$value);
    }
    if (App::dataKeyExists('session.'.$key)) {
        return App::data('session.' . $key);
    }
    return $default;
}

function cookie($key,$value = null,$default = null) {
    if (isset($value)) {
        App::data('cookie.'.$key,$value);
    }
    if (App::dataKeyExists('cookie.'.$key)) {
        return App::data('cookie.' . $key);
    }
    return $default;
}