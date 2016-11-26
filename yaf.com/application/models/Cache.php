<?php 
/**
 * @func 方法测试
 */

class CacheModel extends Model{


	public function test(){



		$data = $this->db->get_result('select * from md_test where id=1');

		return $data;
		//echo "test";
	}


}



?>