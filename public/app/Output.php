
<?php
class Output {
    public static Output $instance;

    public object $data;
    public object $ref;

    private function __construct($init = false) {
        if ($init) {
            $this->setPropertyDefaults();
            $this->initModel();
        }
    }

    private function setPropertyDefaults() {
        // Setting properties as objects
        $this->ref = (object)[];
        $this->data = (object)[];

        // Set Input Data
        if (isset($_REQUEST)) {
            $this->data->input = (object)$_REQUEST;
        } else {
            $this->data->input = (object)[];
        }
        if (isset($_POST)) {
            $this->data->input->post = (object)$_POST;
        }
        if (isset($_GET)) {
            $this->data->input->post = (object)$_GET;
        }
        if (isset($_FILES)) {
            $this->data->input->files = (object)$_FILES;
        }
        if (isset($_COOKIE)) {
            $this->data->cookie = (object)$_COOKIE;
        }
        if (isset($_SERVER)) {
            $this->data->server = (object)$_SERVER;
        }
        if (isset($_SESSION)) {
            $this->data->session = (object)$_SESSION;
        }
        if (isset($_ENV)) {
            $this->data->env = (object)$_ENV;
        }

        $this->data->request = (object)[];
        $this->data->request->method = $_SERVER['REQUEST_METHOD'];
        if (isset($_GET['endpoint'])) {
            $this->data->request->endpoint = $_GET['endpoint'];
        } else {
            $this->data->request->endpoint = ucfirst(strtolower($_SERVER['REQUEST_METHOD']));
        }
        App::data('request.endpoint',Input::var('endpoint','Post'));
    }

    /**
     * @return void
     */
    private function initModel() {
        log('Output->initModel');
        $file = PATH_MODELS.DS.App::data('request.endpoint').'.json';
        if (App::dataKeyExists('model') && file_exists($file)) {
            App::data('model',(object)[
                'endpoint' => App::data('request.endpoint'),
                'file' => $file,
                'json' => json_decode(file_get_contents($file))
            ]);
        }
    }

    /**
     * @return Output
     */
    public static function getInstance(): Output {
        note("Output::getInstance");
        if (!isset(static::$instance)) {
            static::$instance = new static(true);
        }
        return static::$instance;
    }
}
