<?php


namespace Wang\Core;


use Wang\Core\Bean\BeanFactory;
use Wang\Core\Server\HttpServer;

class App
{

    protected $beanFile = "bean.php";

    public function run()
    {

        $this->init();

        (new HttpServer())->run();
    }

    public function init()
    {
        define("ROOT_PATH",dirname(dirname(__DIR__)));
        define("APP_PATH",ROOT_PATH."/app");
        define("CONFIG_PATH",ROOT_PATH."/config");
        define("BIN_PATH",ROOT_PATH."/bin");
        define("FRAME_WORK_PATH",ROOT_PATH."/framework");

        $bean = require APP_PATH."/bean.php";
        foreach($bean as $k=>$v){
            BeanFactory::bind($k,$v);
        }

        BeanFactory::make("config")->load();
    }


}