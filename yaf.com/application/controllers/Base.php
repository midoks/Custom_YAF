<?php

class BaseController extends Yaf_Controller_Abstract{


	public function charset($data){
		if( !empty($data) ){
		    $fileType = mb_detect_encoding($data , array('UTF-8','GBK','LATIN1','BIG5')) ;
		    if( $fileType != 'UTF-8'){
		      $data = mb_convert_encoding($data ,'utf-8' , $fileType);
		    }
		}
		return $data;
	}

	public function retJson($array){
		echo json_encode($array);return;
	}

	public function request($key, $value){
		return $this->getRequest()->getParam($key, $this->getRequest()->getPost($key, $value));
	}

}
?>
