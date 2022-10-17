<?php
include __DIR__.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'init.php';

echo json_encode(filter_list());
$app = new App();

