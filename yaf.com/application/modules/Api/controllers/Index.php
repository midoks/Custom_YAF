<?php
/**
 * 后台模块
 *
 */

class IndexController extends Yaf_Controller_Abstract{

	//访问地址:api/index/index
	public function indexAction(){

		$this->getView()->assign('content', 'Hello world');
	}
}
?>
