<?php
final class db_mysqli{
	
	/**
	 * mysql object
	 */
	protected $mysqli;
	
	/**
	 * the last query resource handle
	 */
	protected $lastqueryid=null;
	
	/**
	 * the count of database query
	 */
	protected $querycount=0;
	function __construct(){
		$this->connect();
	}
	private function connect(){
		$this->mysqli=new mysqli(WxPayConfig::MYSQL_HOST,WxPayConfig::SQL_USER,WxPayConfig::SQL_WD,WxPayConfig::DB_NAME,WxPayConfig::MYSQL_PORT);
		
		if (mysqli_connect_errno()){
			error_log("数据库没连接上");
			//echo "Can't connect:".(($this->mysqli)?$this->mysqli->error:mysqli_error());
			return false;
		}
		
		$this->mysqli->query("set names utf8");
		return $this->mysqli;
	}
	
	public function execute($sql){
		if (empty($this->mysqli)){
			$this->connect();
		}
		
		$this->querycount++;
		$this->lastqueryid=$this->mysqli->query($sql) or die("Can't query:".$this->mysqli->error);
		return $this->lastqueryid;
	}
	private function query_next(){
		/*
		 * MYSQL_ASSOC - 关联数组
		 * MYSQL_NUM - 数字数组
		 * MYSQL_BOTH - 默认。同时产生关联和数字数组
		 */
		$resourec=$this->lastqueryid->fetch_array(MYSQL_ASSOC);
		
		// $resourec = mysqli_fetch_array($this->lastqueryid, MYSQLI_ASSOC);
		if (!$resourec){
			$this->free_result();
		}
		return $resourec;
	}
	private function free_result(){
		if (is_resource($this->lastqueryid)){
			// mysqli_free_result($this->lastqueryid);
			$this->lastqueryid->free();
			$this->lastqueryid=null;
		}
	}
	
	/**
	 * form the database ---- select
	 *
	 * @param string $table
	 *        	The database table name
	 * @param string $data
	 *        	is string. for example "name, password, birthday"
	 * @param string $where        	
	 * @param string $order        	
	 * @param string $group        	
	 * @param string $limit        	
	 * @return array list
	 */
	public function select($table,$data="",$where="",$order="",$group="",$limit=""){
		if ($table==''){
			return exit("<b style='color:red;'>ERROR</b> : mysql select function table name is null!");
		}
		$data=($data=="")?"*":$data;
		$where=($where=="")?"":" WHERE ".$where;
		$order=($order=="")?"":" ORDER BY ".$order;
		$group=($group=="")?"":" GROUP BY ".$group;
		$limit=($limit=="")?"":" LIMIT ".$limit;
		
		$sql="SELECT ".$data." FROM `".$table."`".$where.$group.$order.$limit;
		// echo "<b style='color:green;'>select :</b>".$sql."<br>";
		$this->execute($sql);
		
		$list=array();
		
		// 这里要释放内存，不然要引发内存溢出的错误 -----我去年买了个表，本来以为可以少些一些代码
		// there should release of resources, or it will out of memory
		while($row=$this->query_next()){
			$list[]=$row;
		}
		
		$this->free_result();
		return $list;
	}
	
	/**
	 * sql select database
	 *
	 * @param string $sql        	
	 * @return array list
	 */
	public function query_select($sql){
		if ($sql==''){
			return exit("<b style='color:red;'>ERROR</b> : mysql select function sql is null!");
		}
		
		// echo "<b style='color:green;'>select :</b>".$sql."<br>";
		$this->execute($sql);
		$list=array();
		while($row=$this->query_next()){
			$list[]=$row;
		}
		
		$this->free_result();
		return $list;
	}
	
	/**
	 * get database one resource
	 *
	 * @param string $table        	
	 * @param string $data
	 *        	is string. for example "name, password, birthday"
	 * @param string $where        	
	 * @param string $order        	
	 * @param string $group        	
	 * @return array list or one resource
	 */
	public function get_one($table,$data="",$where="",$order="",$group=""){
		if ($table==''){
			return exit("<b style='color:red;'>ERROR</b> : mysql select function table name is null!");
		}
		$data=($data=="")?"*":$data;
		$where=($where=="")?"":" WHERE ".$where;
		$order=($order=="")?"":" ORDER BY ".$order;
		$group=($group=="")?"":" GROUP BY ".$group;
		$limit=" LIMIT 1";
		$sql="SELECT ".$data." FROM `".$table."`".$where.$group.$order.$limit;
		// echo "<b style='color:green;'>get_one :</b>".$sql."<br>";
		//$this->dgLog($this->ip().'::::'.$sql,'get_one');
		//error_log("查询sql::".$sql);
		$this->execute($sql);
		$row=$this->query_next();
		$this->free_result();
		return $row;
	}
	
	/**
	 * form the database ---- insert
	 *
	 * @param array $data
	 *        	is insert database array.for example array("name"=>"fuck", "password"=>"sdfasdfasdf", "type"=>46814352)
	 * @param string $table
	 *        	The database table name
	 * @return boolean
	 */
	public function insert($data,$table,$return_insert_id=false,$replace=false){
		if (!is_array($data)||$table==''||count($data)==0){
			error_log("插入有误::".$table);
			return;
		}
		
		$field=$this->intercept_array_keys($data);
		$value=$this->interecpt_array_values($data);
		
		$cmd=$replace?'REPLACE INTO':'INSERT INTO';
		
		$sql=$cmd.' `'.$table.'`('.$field.') VALUES ('.$value.')';
		//error_log("插入数据::".$sql);
		// echo "<b style='color:green;'>insert :</b>".$sql."<br>";
		// $this->dgLog($this->ip().'::::'.$sql,'insert');
		$return=$this->execute($sql);
		return $return_insert_id?$this->mysqli->insert_id:$return;
	}
	
	/**
	 * form the database ---- update
	 *
	 * @param array $data
	 *        	is insert database array.for example array("name"=>"fuck", "password"=>"sdfasdfasdf", "type"=>46814352)
	 * @param string $table
	 *        	The database table name
	 * @param string $where        	
	 */
	public function update($data,$table,$where){
		if (!is_array($data)||count($data)==0||$table==''||$where==''){
			return exit("<b style='color:red;'>ERROR</b> : mysql update function table name is null or data is not array or data count is 0 or where is null");
		}
		
		$where=($where=="")?"":" WHERE ".$where;
		
		$field=$this->get_array_keys($data);
		$values=$this->get_array_values($data);
		
		$temp="";
		for ($i=0; $i<count($field); $i++){
			if ($i==count($field)-1){
				$temp.=$this->add_special_char($field[$i])."=".$this->escape_string($values[$i]);
			}else{
				$temp.=$this->add_special_char($field[$i])."=".$this->escape_string($values[$i]).",";
			}
		}
		
		$sql="UPDATE `".$table."` SET ".$temp.$where;
	//	error_log($sql);
		// $this->dgLog($this->ip().'::::'.$sql, 'update');
		// echo "<b style='color:green;'>update :</b>".$sql."<br>";
		return $this->execute($sql);
	}
	
	/**
	 * form the database ---- delete
	 *
	 * @param string $table
	 *        	is database table name
	 * @param string $where        	
	 */
	public function delete($table,$where){
		if ($table==''||$where==''){
			return exit("<b style='color:red;'>ERROR</b> : mysql remove function table name is null or where is null");
		}
		
		$where=($where=="")?"":" WHERE ".$where;
		
		$sql="DELETE FROM `".$table."` ".$where;
		// echo "<b style='color:green;'>delete :</b>".$sql."<br>";
		return $this->execute($sql);
	}
	
	/**
	 * get last number of affected rows
	 *
	 * @return int
	 */
	public function affected_rows(){
		return mysqli_affected_rows($this->link);
	}
	
	/**
	 * execute sql
	 *
	 * @param
	 *        	$sql
	 * @return boolean/query resource 如果为查询语句，返回资源句柄，否则返回true/false
	 */
	public function query($sql){
		return $this->execute($sql);
	}
	
	/**
	 * transaction auto commit
	 *
	 * @param string $mode        	
	 * @return boolean
	 */
	public function autocommit($mode=true){
		$this->mysqli->autocommit($mode);
	}
	
	/**
	 * transaction commit
	 */
	public function commit(){
		$this->mysqli->commit();
	}
	
	/**
	 * transaction rollback
	 */
	public function rollback(){
		$this->mysqli->rollback();
	}
	
	/**
	 * begin transaction
	 * MYSQLI_TRANS_START_READ_ONLY
	 * MYSQLI_TRANS_START_READ_WRITE
	 * MYSQLI_TRANS_START_WITH_CONSISTENT_SNAPSHOT
	 *
	 * @param string $name        	
	 */
	public function begin_transaction($name=MYSQLI_TRANS_START_READ_WRITE){
		$this->mysqli->begin_transaction();
	}
	
	/**
	 * for value add ` symbol
	 *
	 * @param unknown $value        	
	 * @return Ambigous <string, mixed>
	 */
	private function add_special_char(&$value){
		// strpos() 函数返回字符串在另一个字符串中第一次出现的位置。
		if ('*'==$value||false!==strpos($value,'(')||false!==strpos($value,'.')||false!==strpos($value,'`')){
			// 不处理包含* 或者 使用了sql方法。
		}else{
			$value='`'.trim($value).'`';
		}
		if (preg_match("/\b(select|insert|update|delete)\b/i",$value)){
			$value=preg_replace("/\b(select|insert|update|delete)\b/i",'',$value);
		}
		return $value;
	}
	
	/**
	 * for value add ' symbol
	 *
	 * @param unknown $value
	 *        	is update or insert data array
	 * @return string
	 */
	private function escape_string(&$value){
		if (!is_numeric($value)){
			if (strpos($value,"'"))
				$q='"';
			else
				$q='\'';
		}else{
			$q='';
		}
		$value=$q.$value.$q;
		return $value;
	}
	
	/**
	 * get array keys
	 *
	 * @param unknown $data
	 *        	is array
	 * @return multitype: format array
	 */
	private function get_array_keys($data){
		// array_keys() 函数返回包含数组中所有键名的一个新数组。
		return array_keys($data);
	}
	
	/**
	 * get array values
	 *
	 * @param unknown $data
	 *        	is array
	 * @return multitype: format array
	 */
	private function get_array_values($data){
		// array_values() 函数返回一个包含给定数组中所有键值的数组，但不保留键名。
		return array_values($data);
	}
	
	/**
	 * intercept keys format string
	 *
	 * @param unknown $data
	 *        	is array
	 * @return string
	 */
	private function intercept_array_keys($data){
		$field="";
		$fielddata=$this->get_array_keys($data);
		// array_walk() 函数对数组中的每个元素应用回调函数。如果成功则返回 TRUE，否则返回 FALSE。
		array_walk($fielddata,array($this,'add_special_char'));
		// implode() 函数把数组元素组合为一个字符串。
		$field=implode(',',$fielddata);
		return $field;
	}
	
	/**
	 * intercept values format string
	 *
	 * @param unknown $data
	 *        	is array
	 * @return string
	 */
	private function interecpt_array_values($data){
		$value="";
		$valuedata=$this->get_array_values($data);
		// array_walk() 函数对数组中的每个元素应用回调函数。如果成功则返回 TRUE，否则返回 FALSE。
		array_walk($valuedata,array($this,'escape_string'));
		// implode() 函数把数组元素组合为一个字符串。
		$value=implode(',',$valuedata);
		return $value;
	}
	function dgLog($word="",$tag=""){
		$path="logs/";
		$path.=($tag==''?"log":$tag)."_";
		$path.=strftime("%Y_%m_%d",time());
		$path.=".log";
		
		$module=isset($GLOBALS['module'])?($GLOBALS['module']?$GLOBALS['module']:'module is null'):"module havn't set";
		$action=isset($GLOBALS['action'])?($GLOBALS['action']?$GLOBALS['action']:'action is null'):"action havn't set";
		
		$fp=fopen($path,"a");
		flock($fp,LOCK_EX);
		fwrite($fp,"[".strftime("%Y/%m/%d %H:%M:%S",time())."]>> [module >> $module] [action >> $action] ".$word."\r\n");
		flock($fp,LOCK_UN);
		fclose($fp);
	}
	function ip(){
		if (getenv('HTTP_CLIENT_IP')&&strcasecmp(getenv('HTTP_CLIENT_IP'),'unknown')){
			$ip=getenv('HTTP_CLIENT_IP');
		}elseif (getenv('HTTP_X_FORWARDED_FOR')&&strcasecmp(getenv('HTTP_X_FORWARDED_FOR'),'unknown')){
			$ip=getenv('HTTP_X_FORWARDED_FOR');
		}elseif (getenv('REMOTE_ADDR')&&strcasecmp(getenv('REMOTE_ADDR'),'unknown')){
			$ip=getenv('REMOTE_ADDR');
		}elseif (isset($_SERVER['REMOTE_ADDR'])&&$_SERVER['REMOTE_ADDR']&&strcasecmp($_SERVER['REMOTE_ADDR'],'unknown')){
			$ip=$_SERVER['REMOTE_ADDR'];
		}
		return preg_match('/[\d\.]{7,15}/',$ip,$matches)?$matches[0]:'';
	}
}