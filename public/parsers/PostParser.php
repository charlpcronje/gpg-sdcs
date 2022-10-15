<?php

class PostParser extends App {
    function __construct() {
        parent::__construct();
        if ($this->data('request.method') === "GET") {
            $this->render('wiki',$this->model('json'));
        }
    }

    public function metaData() {

    }
}