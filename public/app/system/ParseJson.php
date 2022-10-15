<?php

class ParseJson extends App {
    private $controller;
    private $object;
    private $json;
    private $status;

    function __construct($endpoint) {
        $this->model = $this->output->model->endpoint;
        $this->loadController();
    }

    private function loadParser() {
        $this->object = new $this->output->model->endpoint();
    }

    public static function parse($file) {
        $json = new static($file);
    }
}
