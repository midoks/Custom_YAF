<?php

/**
 * 缓存测试
 */

class CacheTestController extends Yaf_Controller_Abstract{

	public function init(){
		//关闭缓存
		Yaf_Dispatcher::getInstance()->disableView();
	}


	public function indexAction(){
		echo "12123";
	}
}
?>
