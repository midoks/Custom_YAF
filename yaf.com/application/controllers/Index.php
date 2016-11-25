<?php

//第一次,亲密接触

class IndexController extends Yaf_Controller_Abstract {

	public function indexAction(){

		//echo "123";//exit;
		$this->getView()->assign('content', 'Hello world');
	}
}
?>
