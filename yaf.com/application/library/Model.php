<?php 

class Model{

	public $db = null;
	public $mongo = null;
	public $redis = null;
	public $mem = null;

	public $logs = null;

	public function __construct(){
		$this->_initDb();
		//$this->_initMongo();
		//$this->_initRedis();
		$this->_initMem();

		$this->logs = new Logs();
	}

	public function _initDb(){

		$this->db = mySqlDb::instance();
		$this->db->register(array(
			'master' => [
				['db_host' 			=> '127.0.0.1',
	 			'db_name' 			=> 'mdread',
	 			'db_user' 			=> 'root',
	 			'db_pwd'  			=> 'root',
	 			'db_charset' 		=> 'utf8',
	 			'db_table_prefix' 	=> 'md_'],
			],
		));
	}

	public function table($tableName){
		return $this->db->table($tableName);
	}

	public function _initMongo(){

		$this->mongo = mongoSvc::instance();
		$this->mongo->register([
				'conn' 	=> 'mongodb://127.0.0.1:27017',
				'db'	=> 'mdread']);
	}

	public function _initRedis(){

	}

	public function _initMem(){
		
		if (class_exists('memcached')){
			$this->mem = new Memcached();
		} else {
			$this->mem = new Memcache();
		}

		$mem_list = array(
			array('127.0.0.1', '11211'),
		);

		$this->mem->addServers($mem_list);
	}


	public function __call($method, $args){
		$suffix = '_with_mc';
		if (substr($method, -strlen($suffix)) == $suffix) {
			return $this->cache_with_mc($method, $args);
		}
		return false;
	}

	/**
	 * @func (缓存在memcahed)解决雪崩事件
	 */
	public function cache_with_mc($method, $args){

		var_dump($method, $args);
		
		$real_func = substr($method, 0, strlen($method)-strlen('_with_mc'));
		$key = isset($args[0])? $args[0]:1;
		$cache_time = isset($args[1])? $args[1]:1;
		
		array_shift($args);

		if (!$this->mem){
			$data = call_user_func_array(array($this, $real_func), $args);
			return $data;
		}

		$data = $this->mem->get($key);

		var_dump($data);exit;

		//调试时使用
		if($data && !isset($_GET['_refresh'])){
			return $data;
		}else{
			$this->mem->delete($key);
		}

		//sleep(4);
		if($result = $this->mem->add($key, null)){
			$data = call_user_func_array(array($this, $real_func), $args);
			if(!empty($data)){
				$this->mem->set($key, $data, $cache_time);
			}
		} else {
			for($i=0; $i<20; $i++) { //4秒没有反应，就出白页吧，系统貌似已经不行了
				sleep(0.2);
				$this->logs->w($key.'_'.$i);
				$data = $this->mem->get($key);
				if ($data !== false){
					break;
				}
			}
		}
		return $data;
	}

	/**
	 *	@func 析构函数
	 */
	public function __destruct(){}
}

?>
