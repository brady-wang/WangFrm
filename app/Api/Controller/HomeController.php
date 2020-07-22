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
     * @RequestMapping()
     *
     */
    public function index()
    {
        return "sdfsdf";
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