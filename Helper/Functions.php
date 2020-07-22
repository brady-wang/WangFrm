<?php

// 根据文件的绝对路径，获取下面的所有路径带有controller的文件数组
if(!function_exists("get_files_by_tree")){
	function get_files_by_tree($path,&$files=[],$filter="controller"){
		$dirs = glob($path."/*");
		if(!empty($dirs)){
			foreach($dirs as $dir){
				if(is_dir($dir)){
					get_files_by_tree($dir,$files,$filter);
				} else {
					if(stristr(strtolower($dir),$filter)){
						$files[] = $dir;
					}
				}
			}
		}
		return $files;
	}
}

// 去除字符最后一个 /

if(!function_exists("filter_lean_line")){
	function filter_lean_line($str){
		if($str[-1] == "/"){
			$str = substr($str,0,strlen($str)-1);
		}

		return $str;
	}
}
// 根据文件的绝对路径 获取类名
function getClassNameByFilePath($file)
{
	$fileArr = explode("/",$file);
	$short_file_name = end($fileArr);
	$controller   = explode('.',$short_file_name)[0];

	$content = file_get_contents($file,false,null,0,500);

	preg_match('/namespace\s(.*)/i',$content,$nameSpace);
	$nameSpace = str_replace([' ',';','"'],'',$nameSpace);
	$nameSpace = trim($nameSpace[1]);
	return $className = $nameSpace."\\".$controller;

}