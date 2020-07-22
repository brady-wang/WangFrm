<?php


namespace Wang\Core;



use Wang\Core\Log\Helper\CLog;
use Wang\Core\Route\Annotation\Mapping\RequestMapping;
use Wang\Core\Route\Annotation\Parser\RequestMappingParser;
use Wang\Core\Route\Route;

class App
{

    public $http_port = 9501;

    public function run()
    {

	   $config =  [
		    'name'    => 'swoft',
		    'enable'  => true,
		    'output'  => true,
		    'levels'  => '',
		    'logFile' => ''
	    ];
	   CLog::init($config);

    	\Wang\Core\Log\Helper\CLog::info("hello");
    	// 注解路由实现
        $this->loadAnnotations();

        $http = new \Swoole\Http\Server("0.0.0.0", $this->http_port);

        $http->set(array(
            'reactor_num'   => 4,     // reactor thread num
            'worker_num'    => 8,     // worker process num
            'backlog'       => 128,   // listen backlog
            'max_request'   => 50,
            'dispatch_mode' => 1,
        ));

        $http->on("request",function($request,$response){
            $this->myRequest($request,$response);
        });
        $http->start();
    }

    public function myRequest($request, $response)
    {
        if($request->server['request_uri'] != "/favicon.ico"){

            echo "client connect fd:".$request->fd.PHP_EOL;
            $path_info = $request->server['path_info'];
            $method = $request->server['request_method'];

            $content = Route::dispatch($method,$path_info);

            $response->header("Content-Type", "text/html; charset=utf-8");
            $response->end($content);
        }

    }


	/**
	 * 获取 apppath的路由 下面所有文件，得到注解路由 放到路由属性$routes 对象里面
	 */
    public function loadAnnotations()
    {
	    get_files_by_tree(APP_PATH,$dirs,$filter="controller");

        if(!empty($dirs)){
            foreach($dirs as $file){
            	// 根据绝对路径文件名 获取带命名空间的类
	            $className = getClassNameByFilePath($file);

				$obj = new $className;
                $reflect = new \ReflectionObject($obj);
                $methods = $reflect->getMethods();
                if(!empty($methods)){
                    foreach($methods as $method){
                        if ($method->getName() !=  '__construct'){
                        	// 注解对象 设置注解路由相关信息
	                        $annotation = new RequestMapping($reflect,$method);

	                        // 解析 将上一步收集到的信息组装后添加到路由数组里面 $routes
	                        (new RequestMappingParser())->parse($annotation);
                        }
                    }
                }

            }
        }

    }

}