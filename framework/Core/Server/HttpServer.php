<?php

namespace Wang\Core\Server;

use Wang\Core\Bean\BeanFactory;
use Wang\Core\Route\Annotation\Mapping\RequestMapping;
use Wang\Core\Route\Annotation\Parser\RequestMappingParser;
use Wang\Core\Route\Route;

class HttpServer
{

    public $server;

    public function run()
    {
        $config = BeanFactory::make("config")->get("http-server");
        $this->server = new \Swoole\Http\Server($config['host'], $config['port']);
        $this->server->set($config['setting']);
        $this->server->on("request", [$this, 'httpRequest']);
        $this->server->on("workerstart", [$this, "workerStart"]);
        $this->server->on("start", [$this, "start"]);

        $this->server->start();
    }

    public function start($server)
    {
        echo "main worker  启动" . PHP_EOL;
        $dirs = BeanFactory::make("config")->get('reload_dirs');
        $reload = bean("reload");
        \Swoole\Timer::Tick(3000, function () use ($reload, $dirs) {
            //echo "定时器" . PHP_EOL;
            $newMd5 = $reload->md5Dirs($dirs);
            if ($reload->needReload($newMd5)) {
                $this->server->reload();
            }
        });
    }


    /**
     * onworker start 回调
     * @param $server
     * @param $workerId
     * author: brady
     * date: 2020/7/23 16:03
     */
    public function workerStart($server, $workerId)
    {
        echo "worker " . $workerId . " 启动" . PHP_EOL;
        $this->loadAnnotations();
    }

    /**
     * http 请求接受
     * @param $request
     * @param $response
     * author: brady
     * date: 2020/7/23 16:03
     */
    public function httpRequest($request, $response)
    {
        if ($request->server['request_uri'] != "/favicon.ico") {
            echo "client connect fd:" . $request->fd . PHP_EOL;
            $path_info = $request->server['path_info'];
            $method = $request->server['request_method'];

            $content = Route::dispatch($method, $path_info);

            $response->header("Content-Type", "text/html; charset=utf-8");
            $response->end($content);
        }

    }

    /**
     * 获取 apppath的路由 下面所有文件，得到注解路由 放到路由属性$routes 对象里面
     */
    public function loadAnnotations()
    {
        get_files_by_tree(APP_PATH, $dirs, $filter = "controller");
        if (!empty($dirs)) {
            foreach ($dirs as $file) {
                // 根据绝对路径文件名 获取带命名空间的类
                $className = getClassNameByFilePath($file);
                $obj = new $className;
                $reflect = new \ReflectionObject($obj);
                $methods = $reflect->getMethods();
                if (!empty($methods)) {
                    foreach ($methods as $method) {
                        if ($method->getName() != '__construct') {
                            // 注解对象 设置注解路由相关信息
                            $annotation = new RequestMapping($reflect, $method);

                            // 解析 将上一步收集到的信息组装后添加到路由数组里面 $routes
                            (new RequestMappingParser())->parse($annotation);
                        }
                    }
                }
            }
        }
    }
}