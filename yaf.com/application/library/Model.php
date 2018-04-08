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
			$method = substr($method, 0, strlen($method)-strlen($suffix));
			return $this->cacheWithMc($method, $args);
		}
		return false;
	}

	/**
	 *	
	 */
	private function callUserFunc($method, $args){
		if(empty($args)){

		} else {

		}

	}

	/**
	 * @func (缓存在memcahed)解决雪崩问题
	 * @
	 * @explain https://timyang.net/programming/memcache-mutex/
	 */
	public function cacheWithMc($method, $args){

		$real_func = $method;
		$key = isset($args[0])? $args[0]:1;
		$cache_time = isset($args[1])? $args[1]:1;
		array_shift($args);
		array_shift($args);

		if (!$this->mem){
			$data = call_user_func_array(array($this, $real_func), $args);
			return $data;
		}

		$data = $this->mem->get($key);

		var_dump($data);

		//调试时使用
		if($data && !isset($_GET['_refresh'])){
			return $data;
		}else{
			$this->mem->delete($key);
		}

		$key_lock = $key.'_lock';
		if($result = $this->mem->add($key_lock, null, 1)){
			var_dump($result);
			$data = call_user_func_array(array($this, $real_func), $args);

			var_dump($data);


			$this->mem->delete($key_lock);

			// if(!empty($data)){
			// 	$this->mem->set($key, $data, $cache_time);
			// }
		} else {
			for($i=0; $i<20; $i++) { //4秒没有反应，就出白页吧，系统貌似已经不行了
				sleep(0.2);
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
