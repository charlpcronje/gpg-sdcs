
<?php
class Output {
    public static Output $instance;
    public $endpoint;
    public object $data;
    public $model;
    public $json;

    public function __construct() {
        // Get endpoint from URL (The string after the first '/')
        if (isset($_GET['endpoint'])) {
            $this->endpoint = $_GET['endpoint'];
        }
        // Check to see of there is a corresponding model.json
        if (isset($this->endpoint) && file_exists(PATH_MODELS.DS.$this->endpoint.'json')) {
            $this->model = file_get_contents(PATH_MODELS.DS.$this->endpoint.'json');
            $this->json = json_decode($this->model);
        }
        // Preparing the `data` property by making it an object so that I can use it like ->
        $this->data = (object)[];

    }

    public static function getInstance(): Output {
        if (!isset(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }
}
