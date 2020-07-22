<?php


namespace App \Api \Controller;

/**
 * Class HomeController
 * @Controller(prefix="/api/home")
 */
class HomeController
{
    public function __construct()
    {

    }

    /**
     * @RequestMapping(route="index")
     *
     */
    public function index()
    {
        return "你好";
    }

    /**
     * @RequestMapping(route="hello")
     *
     */
    public function hello()
    {
        return  "hello";
    }
}