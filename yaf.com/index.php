<?php
//http://www.laruence.com/manual/tutorial.firstpage.html (官方帮助地址)
define("APP_PATH", realpath(dirname(__FILE__) . '/'));
$app  = new Yaf_Application(APP_PATH."/conf/application.ini");
$app->run();
?>
