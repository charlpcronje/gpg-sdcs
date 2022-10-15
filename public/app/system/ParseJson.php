<?php

class ParseJson {
    private $controller;
    private $object;
    private $json;
    private $status;

    function __construct($endpoint) {
        $this->model = $file;
        $this->loadController();
        $this->parseJson();
    }

    private function parseJson() {
        $this->json = file_get_contents
    }

    private function loadController() {
        $this->object = new $this->controller();
    }

    public static function parse($file) {
        $json = new static($file);
    }
}
