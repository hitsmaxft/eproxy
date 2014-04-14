<?php

require(__DIR__ . DIRECTORY_SEPARATOR . "vendor/autoload.php");

$server = new EchoProxy("127.0.0.1", "9901");
$server->run();
