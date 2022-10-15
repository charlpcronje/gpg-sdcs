
<?php
class Output {
    public static Output $instance;
    public $model;
    public $data;

    public function __construct($endpoint) {
        $this->initModel($endpoint);
        // Preparing the `data` property by making it an object so that I can use it like ->
        $this->data = (object)[];
    }

    /**
     * @param $endpoint
     * @return void
     */
    private function initModel($endpoint) {
        $file = PATH_MODELS.DS.$endpoint.'.json';
        if (!isset($this->model) && file_exists($file)) {
            $this->model = (object)[
                'endpoint' => $endpoint,
                'file' => PATH_MODELS.DS.$endpoint.'.json',
                'json' => json_decode(file_get_contents($file))
            ];
        }
        print_r($this->model);
    }

    /**
     * @param $endpoint
     * @return Output
     */
    public static function getInstance($endpoint): Output {
        if (!isset(static::$instance)) {
            static::$instance = new static($endpoint);
        }
        return static::$instance;
    }
}
