<?php


namespace App\Api\Controller;

/**
 * Class UserController
 * @Controller(prefix="/home")
 */
class UserController
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