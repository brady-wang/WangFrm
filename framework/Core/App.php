<?php


namespace Wang\Core;


use App\Api\Controller\HomeController;
use Wang\Core\Route\Route;

class App
{

    public $http_port = 9501;

    public function run()
    {

        $this->init();
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

    public function init()
    {
        define("ROOT_PATH",dirname(dirname(__DIR__)));
        define("APP_PATH",ROOT_PATH."/app");
    }

    public function loadAnnotations()
    {
        $dirs = [];
        $this->tree(APP_PATH,$dirs,$filter="controller");
        if(!empty($dirs)){
            foreach($dirs as $file){
            	$fileArr = explode("/",$file);
            	$short_file_name = end($fileArr);
            	$controller   = explode('.',$short_file_name)[0];

            	$content = file_get_contents($file,false,null,0,500);

            	preg_match('/namespace\s(.*)/i',$content,$nameSpace);
            	$nameSpace = str_replace([' ',';','"'],'',$nameSpace);
				$nameSpace = trim($nameSpace[1]);
				$className = $nameSpace."\\".$controller;

				$obj = new $className;
                $reflect = new \ReflectionObject($obj);

                //匹配前缀
                $classDoc = $reflect->getDocComment();
                preg_match('/@Controller\((.*)\)/i',$classDoc,$prefix);
                $prefix = str_replace('"','',explode("=",$prefix[1])[1]);
                $methods = $reflect->getMethods();
                if(!empty($methods)){
                    foreach($methods as $method){

                        if ($method->getName() !=  '__construct'){
                            preg_match('/@RequestMapping\((.*)\)/i',$method->getDocComment(),$suffix);
                            $suffix = str_replace('"','',explode("=",$suffix[1])[1]);

                            $routeInfo = [
                                'routePath' => $prefix."/".$suffix,
                                'handle'=>$reflect->getName()."@".$method->getName()
                            ];
                            Route::addRoute("GET",$routeInfo);
                        }
                    }
                }

            }
        }

    }

    public function tree($path,&$files=[],$filter="controller"){
        $dirs = glob($path."/*");
        if(!empty($dirs)){
            foreach($dirs as $dir){
                if(is_dir($dir)){
                    $this->tree($dir,$files,$filter);
                } else {
                    if(stristr(strtolower($dir),$filter)){
                        $files[] = $dir;
                    }
                }
            }
        }
        return $files;
    }
}