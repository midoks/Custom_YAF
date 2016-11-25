<?php


class IndexController extends Yaf_Controller_Abstract{
	//访问地址:api/index/index
	public function indexAction(){
		echo "admin";
		$this->getView()->assign('content', 'Hello world');
	}
}
?>
