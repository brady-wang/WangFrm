<?php


namespace App\Admin\Controller;

/**
 * Class TestController
 * @Controller(prefix="/admin/test")
 */
class TestController
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
        return "admin/test/indexd";
    }

    /**
     * @RequestMapping(route="hello")
     *
     */
    public function hello()
    {
        return "hello";
    }
}