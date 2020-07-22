<?php

namespace Wang\Core\Route\Annotation\Parser;

use Wang\Core\Route\Annotation\Mapping\RequestMapping;
use Wang\Core\Route\Route;

/**
 * 解析添加到路由
 * Class RequestMappingParser
 * @package Swoft\Http\Server\Annotation\Parser
 */
class RequestMappingParser
{

	/**
	 * @param RequestMapping $annotation
	 */
    public function parse($annotation)
    {

	    $routeInfo = [
		    'routePath' => $annotation->getRoutePath(),
		    'handle'=>$annotation->getHandle(),
		    'method'=>$annotation->getMethod()
	    ];

	    Route::addRoute("GET",$routeInfo);
    }
}
