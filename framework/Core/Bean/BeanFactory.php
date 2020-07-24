<?php


namespace Wang\Core\Bean;

/**
 * bean 容器
 * Class BeanFactory
 * @package Wang\Core\Bean
 */
class BeanFactory
{


    private static $instances = [];

    private static $bindings = [];

    public static function getClousre($concrete)
    {
        return function ($params = []) use ($concrete) {
            $reflect = new \ReflectionClass($concrete);
            if (!$reflect->isInstantiable()) {
                throw new \Exception("{$concrete} 无法被实例化");
            }

            $construct = $reflect->getConstructor();

            $newParams = [];
            if (!empty($construct)) {
                $parameters = $construct->getParameters();
                foreach ($parameters as $_parameter) {
                    if (isset($params[$_parameter->name])) {
                        $newParams[] = $params[$_parameter->name];
                        continue;
                    }
                    if (!$_parameter->isDefaultValueAvailable()) {
                        throw new \Exception("{$concrete} 无法被实例化 缺少参数");
                    }

                    $newParams[] = $_parameter->getDefaultValue();
                }
            }

            return $reflect->newInstanceArgs($newParams);
        };
    }

    /**
     * @param $abstract 实例的别名 比如 route=> Route对象
     * @param $concrete 实例，可以是字符串 或者回调函数
     * @param bool $shared
     * author: brady
     * date: 2020/7/23 10:27
     */
    public static function bind($abstract, $concrete, $shared = true)
    {
        $abstract = strtolower($abstract);
        // 如果为空 表示第二个参数没有传递，那么就使用第一个作为类名
        if (is_null($concrete)) {
            $concrete = $abstract;
        }

        // 如果不是闭包回调函数，要通过反射 实例化后返回
        if (!$concrete instanceof \Closure) {
            $concrete = self::getClousre($concrete);
        }

        // 降得到的实例 放到统一数组
        self::$bindings[$abstract] = [
            "concrete" => $concrete,
            "shared" => $shared
        ];

    }

    /**
     * 通过类名 到容器获取对象
     * @param $abstract
     * @param array $params
     * @return  \stdClass $object
     * author: brady
     * date: 2020/7/23 10:31
     */
    public static function make($abstract, $params = [])
    {

        $abstract = strtolower($abstract);

        if (!isset(self::$bindings[$abstract])) {
            return false;
        }

        if (isset(self::$instances[$abstract])) {
            return self::$instances[$abstract];
        }

        $concrete = self::$bindings[$abstract]["concrete"];
        $object = $concrete($params);
        if (self::$bindings[$abstract]['shared']) {
            self::$instances[$abstract] = $object;
        }

        return $object;
    }

    public static function getInstance()
    {
        return new self();
    }

}