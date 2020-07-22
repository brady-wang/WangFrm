<?php


namespace Wang\Core\Route;


class Route
{

    private static $routes;

    /**
     * 路由添加
     * author: brady
     * date: 2020/7/22 18:10
     */
    public static function addRoute($method,$routeInfo)
    {
        self::$routes[$method][] = $routeInfo;
    }

    /**
     * 路由分发
     * author: brady
     * date: 2020/7/22 18:10
     */
    public static function dispatch($method,$path_info)
    {

	    $path_info = filter_lean_line($path_info);

        switch($method){
            case "GET":
            {
                foreach(self::$routes[$method] as $v){
                    if($path_info == $v['routePath']) {
                        $handle = explode("@",$v['handle']);
                        $class = $handle[0];
                        $method = $handle[1];
                        return (new $class)->$method();
                    }
                }
            }
            case "POST":
            {

            }
        }
    }
}