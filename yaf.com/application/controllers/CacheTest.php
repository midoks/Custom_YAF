<?php

/**
 *
 */

class CacheTestController extends Yaf_Controller_Abstract{



	public function indexAction(){
		$this->getView()->assign('content', 'Hello world');
	}
}
?>
