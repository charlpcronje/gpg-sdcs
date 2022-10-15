<?php
const DS = DIRECTORY_SEPARATOR;
define('PATH_ROOT',getcwd());
const PATH_APP = PATH_ROOT . DS . 'app';
const PATH_HELPERS = PATH_APP . DS . 'helpers';
const PATH_SYSTEM = PATH_APP . DS . 'system';
const PATH_CONFIG = PATH_APP . DS . 'config';
const PATH_PARSERS = PATH_ROOT . DS . 'parsers';
const PATH_MODELS = PATH_ROOT . DS . 'models';
const PATH_VIEWS = PATH_ROOT . DS . 'views';
const PATH_UPLOAD_EXCEPTIONS = PATH_SYSTEM . DS . 'Upload';