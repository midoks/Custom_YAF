<?php

/**
 * 缓存测试
 */

class CacheTestController extends Yaf_Controller_Abstract{

	public function init(){
		//关闭模版引用
		Yaf_Dispatcher::getInstance()->disableView();
	}


	public function indexAction(){
		echo "12123";

		$obj = new CacheModel();

		$data = $obj->test_with_mc('key_1', 5);
		var_dump($data);

	}
}
?>
