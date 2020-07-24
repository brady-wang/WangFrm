<?php


namespace Wang\Core\Config;


class Config
{
    private $configMaps = [];

    /**
     * 加载config 文件夹下的配置
     * @throws \Exception
     * author: brady
     * date: 2020/7/23 15:00
     */
    public function load()
    {
        $files = glob(CONFIG_PATH . "/*.php");
        if (!empty($files)) {
            foreach ($files as $file) {
                $arr = include "{$file}";
                if (!is_array($arr)) {
                    throw new \Exception("{$file} 文件 配置不合法");
                } else {
                    $this->configMaps += $arr;
                }
            }
        }

    }

    /**
     * 获取配置
     * @param $key
     * @return array
     * author: brady
     * date: 2020/7/23 15:00
     */
    public function get($key)
    {
        if (isset($this->configMaps[$key])) {
            return $this->configMaps[$key];
        } else {
            return [];
        }
    }
}