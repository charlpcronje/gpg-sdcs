<?php
// All application classes should extend this one
class App {
    use DataTraits;
    public Output $output;

    public function __construct($endPoint = 'PostParser') {
        $this->output = Output::getInstance($endPoint);
        $this->data('model',$this->model('json'));
        $this->setData('request.method',$_SERVER['REQUEST_METHOD']);
    }

    public function model($context = null): object {
        if (isset($context)) {
            return $this->output->model->$context;
        }
        return $this->output->model;
    }

    public function __set($name,$value) {
        if (!isset($this->output->data)) {
            $this->output->data = (object)[];
        }
        $this->output->data->$name = $value;
        return $this;
    }

    public function __get($name) {
        if (isset($this->output->data) && isset($this->output->data->$name)) {
            return $this->output->data->$name;
        } else {
            return null;
        }
    }

    public static function parseTemplate($buffer) {
        return $buffer;
    }

    public function render() {
        ob_start(array('self', 'parseTemplate'));
    }
}