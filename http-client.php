<?php

// http client making a request to github api

require __DIR__.'/vendor/autoload.php';

$loop = React\EventLoop\Factory::create();

$dnsResolverFactory = new React\Dns\Resolver\Factory();
$dnsResolver = $dnsResolverFactory->createCached('8.8.8.8', $loop);

$factory = new React\HttpClient\Factory();
$client = $factory->create($loop, $dnsResolver);

$request = $client->request('GET', 'http://s.taobao.com');
$request->on('response', function ($response) {
    $buffer = '';

    $response->on('data', function ($data) use (&$buffer) {
        $buffer .= $data;
        echo ".";
    });

    $response->on('end', function () use (&$buffer) {
        echo substr($buffer, 0 , 100);
    });
});
$request->on('end', function ($error, $response) {
});
$request->end();

$loop->run();
