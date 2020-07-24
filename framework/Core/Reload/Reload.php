<?php


namespace Wang\Core\Reload;


use Wang\Core\Bean\BeanFactory;

class Reload
{

    protected $oldMd5 = '';

    public function __construct()
    {
        $dirs = BeanFactory::make("config")->get('reload_dirs');
        $this->oldMd5 = $this->md5Dirs($dirs);
    }

    /**
     * 是否需要重启
     * author: brady
     * date: 2020/7/23 16:42
     */
    public function needReload($newMd5)
    {
       if($newMd5 != $this->oldMd5){
           $this->oldMd5 = $newMd5;
           return true;
       } else {
           return false;
       }
    }

    /**
     * 对传入的目录所有文件进行递归加密
     * @param array $dirs
     * author: brady
     * date: 2020/7/23 16:42
     */
    public function md5Dirs($dirs)
    {
        $files = [];
        foreach($dirs as $dir){
            $files += get_files_by_tree($dir);
        }

        $md5File = '';
        foreach($files as $file){
            $md5File .= md5_file($file);
        }

        return  md5($md5File);
    }


}