<?php
require_once __DIR__ . '/vendor/autoload.php';

$application = new Application($_SERVER, $_POST);

$application->process();

echo $application->getJsonResponse();