<?php


namespace App\Admin\Controller;

/**
 * Class UserController
 * @Controller(prefix="/admin/user")
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