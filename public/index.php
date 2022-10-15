<?php
include './app/system/Constants.php';
include PATH_SYSTEM.DS.'Autoloader.php';

$app = new App(Input::val('endpoint','Post'));

