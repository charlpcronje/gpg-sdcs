<?php
// All application classes should extend this one
class App {
    public Output $output;

    public function __construct() {
        $this->output = Output::getInstance();
    }

    public function __set($name,$value) {
        if (!isset($this->output->data)) {
            $this->output->data = new \stdClass();
        }

        if (isset($this->output->ui->className)) {
            $this->output->{$this->output->endpoint}->$name = $value;
        }
    }

    public function __get($name) {
        if (isset($this->output->$name)) {
            return $this->output->$name;
        }
    }
}