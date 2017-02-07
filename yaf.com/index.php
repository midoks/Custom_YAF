<?php

//http://www.laruence.com/manual/tutorial.firstpage.html (官方帮助地址)

set_error_handler('catchErr');
set_exception_handler('catchErr');

function catchErr(){
    $list = func_get_args();
    array_pop($list);
    var_dump($list);
}

//ini_set ('memory_limit', '1024M');
define("APP_PATH", realpath(dirname(__FILE__) . '/'));
$app  = new Yaf_Application(APP_PATH."/config/application.ini");

if (php_sapi_name() == 'cli'){

	//exp: cmd --- php index.php request_uri="/robot/"
	$app->getDispatcher()->throwException(true);  
	$app->getDispatcher()->catchException(true);

	Yaf_Dispatcher::getInstance()->disableView();
	$app->getDispatcher()->dispatch(new Yaf_Request_Simple());
	$app->bootstrap();

} else {
	$app->getDispatcher()->throwException(true);
	$app->getDispatcher()->catchException(true);
	$app->bootstrap() //可选的调用
		->run();
}
?>
