<?php


namespace Wang\Core;


class App
{

    public function run()
    {
        $port = 9501;
        $http = new \Swoole\Http\Server("0.0.0.0", $port);

        $http->on('request', function ($request, $response) use($port) {
            var_dump($request->get, $request->post);
            echo "http 服务器启动 端口:".$port.PHP_EOL;
            $response->header("Content-Type", "text/html; charset=utf-8");
            $response->end("<h1>Hello Swoole. #".rand(1000, 9999)."</h1>");
        });

        $http->start();
    }
}