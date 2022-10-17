<?php
trait SetterTraits {
    public static function setHeader($param,$setting) {
        header($param.': '.$setting);
    }

    public static function contentType($type) {
        self::setHeader('Content-Type',$type);
    }

    public function setClassName($className = null) {
        if (isset($className)) {
            if (!empty($className)) {
                $this->output->className = $className;
            }
        }
    }

    public function setOutputDefaults() {
        if (!isset($this->output)) {
            $this->output = new \stdClass();
        }
        if (!isset($this->output->data)) {
            $this->output->data = new \stdClass();
        }
        if (!isset($this->output->model)) {
            $this->output->model = new \stdClass();
        }
        if (!isset($this->output->{$this->output->className})) {
            if (!isset($this->output->{$this->output->className})) {
                if (!isset($this->output->className) || empty($this->output->className)) {
                    $this->output->className = 'App';
                }
                $this->output->{$this->output->className} = 'App';
            }
            $this->output->{$this->output->className} = __CLASS__;
        }
        if (!isset($this->output->constant)) {
            $this->output->constant = (object)get_defined_constants(true)['user'];
        }
        if (!isset($this->output->session)) {
            // Encoding and then decoding to json converts a variable to an stdClass object
            if (!isset($_SESSION[''])) {
                $_SESSION[env('SESSION_NAME')] = new \stdClass();
            }
            $this->output->session = $_SESSION[env('SESSION_NAME')];
        }
        if (isset($_SESSION)) {
            $this->output->data->session = $_SESSION[env('SESSION_NAME')];
            $this->output->session       = $_SESSION[env('SESSION_NAME')];
        }
    }
}
