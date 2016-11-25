<?php

class SystemPlugin extends Yaf_Plugin_Abstract{

    public function routerStartup(Yaf_Request_Abstract $request,Yaf_Response_Abstract $response){
    	$url_suffix = Yaf_Registry::get('config')->application->url_suffix;
    	//var_dump($url_suffix);
        if ($url_suffix) {
            if(strtolower(substr($_SERVER['REQUEST_URI'],-strlen($url_suffix))) == strtolower($url_suffix)) {
                $request->setRequestUri(substr($_SERVER['REQUEST_URI'], 0 , -strlen($url_suffix)));
            }
        }
    }
}

?>