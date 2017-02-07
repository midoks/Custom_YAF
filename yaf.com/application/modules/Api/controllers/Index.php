<?php
/**
 * Api模块
 * 访问地址:/api/index/index
 */
class IndexController extends Yaf_Controller_Abstract{
	public function indexAction(){
		$this->getView()->assign('content', 'Hello world');
	}
}
?>
