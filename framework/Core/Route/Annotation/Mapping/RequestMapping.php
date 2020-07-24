<?php

namespace Wang\Core\Route\Annotation\Mapping;


/**
 * 路由注解收集
 * Class RequestMapping
 * @package Swoft\Http\Server\Annotation\Mapping
 */
class RequestMapping
{

    /** @var string */
    private $routePath;

    /** @var string */
    private $handle;

    /** @var string */
    private $method;


    /**
     * RequestMapping constructor.
     * @param $classDoc
     * @param \ReflectionMethod $method
     * @param \ReflectionObject $reflect
     */
    public function __construct($reflect, $method)
    {
        preg_match('/@Controller\((.*)\)/i', $reflect->getDocComment(), $prefix);
        $prefix = str_replace('"', '', explode("=", $prefix[1])[1]);


        preg_match('/@RequestMapping\((.*)\)/i', $method->getDocComment(), $suffix);
        $suffix = str_replace('"', '', explode("=", $suffix[1])[1]);

        if (preg_match('/\/.*/', $suffix)) {
            $this->routePath = $suffix;
        } else {
            if (!empty($prefix)) {
                $this->routePath = empty($suffix) ? $prefix : $prefix . "/" . $suffix;
            } else {
                if (preg_match('/\/.*/', $suffix)) {
                    $this->routePath = $suffix;
                } else {
                    $this->routePath = "/" . $suffix;
                }

            }

        }

        $this->handle = $reflect->getName() . "@" . $method->getName();

    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * @return string
     */
    public function getRoutePath()
    {
        return $this->routePath;
    }


}
