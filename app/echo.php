<?php
class EchoProxy{
    function __construct($host = "127.0.0.1", $port= "9900") {
        $this->port = $port;
    }
    function run() {
        $loop = React\EventLoop\Factory::create();
        $socket = new React\Socket\Server($loop);
        $http_server = new React\Http\Server($socket);

        $http_server->on('request', function ($input, $output) {
            sleep(5);
            $output->writeHead(200);
            $output->end("echo\r\n");
        });

        $socket->listen($this->port);
        $loop->run();
    }
}
