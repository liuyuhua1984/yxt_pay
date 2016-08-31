<?php
class db_factory{
	private static $db_factory;
	private static $mysql;
	private function __construct(){
	}
	private function __clone(){
		trigger_error("Clone is not allow",E_USER_ERROR);
	}
	public final static function get_instance(){
		if (self::$db_factory==''){
			self::$db_factory=new db_factory();
			self::$mysql=new db_mysqli(); // 生成mysqli
		}
		return self::$db_factory;
	}
	
	/**
	 *
	 * @param string $db_name        	
	 * @return Ambigous <db_mysqli, boolean, mysql, NULL>
	 */
	public function content($db_name){
		$object=null;
		switch ($db_name) {
			case 'mysql' :
				$object=dj_base::load_class("mysql");
				break;
			case 'db_mysqli' :
				$object=dj_base::load_class("db_mysqli");
				break;
			case 'task' :
				$object=dj_base::load_class("task");
				break;
			default :
				$object=dj_base::load_class("db_mysqli");
		}
		
		return $object;
	}
	
	/**
	 * get mysqli class
	 *
	 * @return db_mysqli
	 */
	public static function get_mysql(){
		if (!self::$mysql){
			
			self::$mysql=new db_mysqli();
		}
		return self::$mysql;
	}
	
	/**
	 * 获得redis
	 */
	public static function getRedis(){
		if (!self::$redis){
			self::$redis=new RedisCluster(); // 生成redis
			self::$redis->connect(array('host'=>REDIS_MASTER_HOST,'port'=>REDIS_MASTER_PORT));
		}
		return self::$redis;
	}
	/**
	 * get model class
	 *
	 * @param string $table        	
	 */
	public static function get_model($table){
		if (!(self::$modelinstanceofmodel)){
			
			self::$model=new model($table);
		}
		return self::$model;
	}
	
	/**
	 * get task class
	 *
	 * @return task
	 */
	public static function get_task(){
		if (!(self::$taskinstanceoftask)){
			
			self::$task=new task();
		}
		return self::$task;
	}
}

?>
