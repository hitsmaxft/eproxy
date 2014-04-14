<?php
class Proxy{
    protected $pool;
    protected $capacity = 100;
    function __construct() {
        $this->port = 9900;
    }

    function run() {
        $loop = React\EventLoop\Factory::create();
        $socket = new React\Socket\Server($loop);
        $http_server = new React\Http\Server($socket);

        $dnsResolverFactory = new React\Dns\Resolver\Factory();
        $dnsResolver = $dnsResolverFactory->createCached('192.168.2.1', $loop);
        $factory = new React\HttpClient\Factory();
        $client = $factory->create($loop, $dnsResolver);

        $http_server->on('request', function ($input, $output) use(&$client) {
            echo "start request\n";
            $query = $input->getQuery();
            if (empty($query['url']) || 0 !== strpos($query['url'], 'http://')) {
                return $output->end();
            }
            $url = $query['url'];

            $request = $client->request('GET', $url);
            $buffer = '';
            $request->on('response', function ($response)use(&$output, &$buffer) {
                $response->on('data', function ($data)use(&$buffer) {
                    $buffer .= $data;
                });

            });
            $request->on('end', function ()use(&$output, &$buffer) {
                echo "end data\n";
                $output->writeHead(200);
                $output->write($buffer);
                $output->end();
            });
            $request->end();
            //$request2 = $client->request('GET', 'http://127.0.0.1:9901/');
            //$request2->on('end', function() {
                //echo "enddata2\n";
            //});
            //$request2->end();
        });

        $socket->listen($this->port);
        $loop->run();
    }
}
