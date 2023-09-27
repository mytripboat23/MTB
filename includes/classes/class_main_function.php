<?php
class main_function extends db_connect 
{
	var $sqlQuery="";
	var $mysqlKeyword=array();
	var $numRows;
	var $link;
	//error_reporting(0);
	function main_function()
	{
		$this->link = parent::db_connect();
		return $this->link;
	}
	function close_mysql_connection()
	{
		parent::db_close($this->link);
	}	
	function setQuery($sql="")
	{
		if($sql=="")
		{
			$errMsg="<b><font color='red' face='Verdana, Arial, Helvetica, sans-serif' size='2'><center>ERROR!!<br>QUERY EMPTY<br></center></font></b>";
			echo "<br>".$errMsg;
		}
		$this->sqlQuery=$sql;
	}
	function getQuery()
	{
		if($this->sqlQuery=="")
		{
			$errMsg="<b><font color='red' face='Verdana, Arial, Helvetica, sans-serif' size='2'><center>ERROR!!<br>QUERY EMPTY<br></center></font></b>";
			echo "<br>".$errMsg;
		}
		return $this->sqlQuery;
	}
	function data_prepare($string,$qto=1)
	{
		if(is_string($string))
		{
			//htmlspecialchars(addslashes(stripslashes(trim($string))));
			$string = trim($string);
			if(!$qto || $this->isMysqlKeyword($string))
			{
				return $string;
			}
			$string="'$string'";
			return $string;
		}
		else if(is_numeric($string))
		{
			return $string;
		}
		else
		{
			if(!$qto)
			{
				return $string;
			}
			$string="''";
			return $string;
		}
	}
	function getFields($table,$datagiven)
	{
		/*$fields = @mysql_list_fields(DBNAME, $table) or $this->mysqlError($sql);
		$column_num = @mysql_num_fields($fields) or $this->mysqlError($sql);
		for ($i = 0; $i < $column_num; $i++)
		 {
			$field_name=@mysql_field_name($fields, $i) or $this->mysqlError($sql);
			foreach ($datagiven as $key=>$value)
				{ 
					if($key==$field_name)
					$data[$key]=$value;
				}		
		}
		return $data;*/
		
		
		$sql = "SHOW COLUMNS FROM  ".$table;
		$res = mysqli_query($this->link,$sql);
		while($row = mysqli_fetch_assoc($res))
		{
	    	$fields[] = $row['Field'];
		}
		//print_r($fields);
		//print_r($datagiven);
		//exit;
		$count = count($fields);
		for ($i = 0; $i < $count; $i++)
		 {
			foreach ($datagiven as $key=>$value)
				{ 
					if($key==$fields[$i])
					$data[$key]=$value;
				}		
		}
		//print_r($data);
		//exit;
		
		return $data;
	}
	/*function getFieldsOnly($table,$toreturn="",$alise="",$extra="")
	{
		$fields = @mysql_list_fields(DBNAME, $table) or $this->mysqlError($sql);
		$column_num = @mysql_num_fields($fields) or $this->mysqlError($sql);
		if(empty($toreturn))
		{
			$toreturn=array();
		}
		for ($i = 0; $i < $column_num; $i++)
		 {
			$field_name=@mysql_field_name($fields, $i) or $this->mysqlError($sql);
			if(!in_array($field_name,$toreturn))
			{
				$field_name_al=$alise.$field_name.$extra;
				$field_name_arr[]=$field_name_al;
			}
		}
		return $field_name_arr;
	}*/
	//INSERT FUNCTION
	function insertData($table,$datagiven)
	{
		
		$data=$this->getFields($table,$datagiven);
		$cols = '(';
		$values = '(';
		foreach ($data as $key=>$value) 
			{     
				$value=$this->data_prepare($value);
				$cols .= "$key,";  
				$values .= "$value,"; 
			}
		$cols = rtrim($cols, ',').')';
		$values = rtrim($values, ',').')';     
		 $sql = "INSERT INTO ".$table." ".$cols." VALUES ".$values;		
		
		$this->setQuery($sql);
		
		$result = @mysqli_query($this->link,$sql);
		if (!$result) $this->mysqlError($sql);
		$id = mysqli_insert_id($this->link);
		
		if ($id == 0) return true;
		else return $id;  // $sql
	}
	
	//UPDATE FUNCTION
	
	function updateData($table, $datagiven, $condition) 
	{
		
		$data=$this->getFields($table,$datagiven);
		$sql = "UPDATE ".$table." SET";
		foreach ($data as $key=>$value) 
		{    
			$value=$this->data_prepare($value);
			$sql .= " $key=";  
			$sql .= "$value,"; 
		}
		$sql = rtrim($sql, ','); 
		if (!empty($condition)) $sql .= " WHERE ".$condition;
		
		$sql .= " limit 1";
		$this->setQuery($sql);
		$result = @mysqli_query($this->link,$sql);
		if (!$result) $this->mysqlError($sql);		
		$rows = mysqli_affected_rows($this->link);
		
		if ($rows == 0) return true; 
		else return $rows;
	}
	
	function updateDataAll($table, $datagiven, $condition) 
	{
		
		$data=$this->getFields($table,$datagiven);
		$sql = "UPDATE ".$table." SET";
		foreach ($data as $key=>$value) 
		{    
			$value=$this->data_prepare($value);
			$sql .= " $key=";  
			$sql .= "$value,"; 
		}
		$sql = rtrim($sql, ','); 
		if (!empty($condition)) $sql .= " WHERE ".$condition;
		
		//$sql .= " limit 1";
		
		$this->setQuery($sql);
		$result = @mysqli_query($this->link,$sql);
		if (!$result) $this->mysqlError($sql);		
		$rows = mysqli_affected_rows($this->link);
		
		if ($rows == 0) return true; 
		else return $rows;
	}
		
	//DELETE FUNCTION
	function deleteData($table, $condition) 
	{
		
		$sql = "Delete from ".$table;
		if (!empty($condition)) $sql .= " WHERE ".$condition;
		$this->setQuery($sql);
		$result = @mysqli_query($this->link,$sql);
		if (!$result) $this->mysqlError($sql);		
		$rows = mysqli_affected_rows($this->link);
		
		if ($rows == 0) return true; 
		else return $rows;
	}
	//SELECT FUNCTION
	function selectData($table,$parameter="",$condition="",$record="",$orderby="",$groupby="",$lmt="")
	{
		
		if(empty($parameter))
		{
			$parameter="*";
		}
		$sql="select ".$parameter." from ".$table;
		if(!empty($condition))
		 {
			$sql.=" where ".$condition;
		 }
		if(!empty($groupby))
		 {
			$sql.=" group by ".$groupby;
		 }
		if(!empty($orderby))
		 {
			$sql.=" order by ".$orderby; 
		 }
		if(!empty($lmt))
		 {
			$sql.=" limit ".$lmt;
		 }
		if($record==1)
		{
			$sql.= " limit 1";
			$this->setQuery($sql);
			$res = @mysqli_query($this->link,$sql) or $this->mysqlError($sql);
			$numres=@mysqli_num_rows($res);
			$this->numRows=$numres;
			if($numres==0)
			{
				$res=0;
			}
			else
			{
				$res=@mysqli_fetch_array($res);
			}
		}
		else if($record==2)
		{
			$res=$sql;
		}
		else
		{
			$this->setQuery($sql);
			$res = @mysqli_query($this->link,$sql) or $this->mysqlError($sql);
		}
		
		return $res;
	}
	function qUpdate($table,$parameter,$condition="")
	{
		
		if(empty($condition))
		$condition=1;
		$sql="update ".$table." set ".$parameter." where ".$condition;
		$this->setQuery($sql);
		$res = @mysqli_query($this->link,$sql) or $this->mysqlError($sql);
		$affRows = mysqli_affected_rows($this->link);
		
		return $affRows;
	}
	function qDelete($table,$condition)
	{
		
		if(!empty($condition))
		{
			$sql="delete from ".$table." where ".$condition;
			$this->setQuery($this->link,$sql);
			$res = @mysqli_query($sql) or $this->mysqlError($sql);
			$affRows = mysqli_affected_rows($this->link);
			
			return $affRows;
		}
		else
		{
			
			return 0;
		}
	}
	//Counting Number of elements
	function countRows($sql)
	{
	  
	  $result= @mysqli_query($this->link,$sql) or $this->mysqlError($sql);
	  $num_rows = @mysqli_num_rows($result);
	  
	  return $num_rows ;

	}
	//Query Run
	function query_run($query)
	{
		
		$sql = 	$query;
		
		$this->setQuery($sql);
		
		$result = @mysqli_query($this->link,$sql);
		
		if (!$result) $this->mysqlError($sql);
		else return $result;

	}
	
	
	//MYSQL Error
	function mysqlError($sql="")
	{
		
		if(!DEBUGMODE)
		{
			$qry="";
			if(!empty($sql)) $qry="<br>QUERY<br>".$sql;
			$errorNo=mysqli_errno($this->link);
			$errMsg=mysqli_error($this->link);
			
			$errMsg="<b><font color='red' face='Verdana, Arial, Helvetica, sans-serif' size='2'><center>ERROR!!".$errorNo."<br>".$errMsg.$qry."</center></font></b>";
		}
		else
		{
			
			$errMsg="<b><font color='red' face='Verdana, Arial, Helvetica, sans-serif' size='2'><center>It seems something went wrong. ".SITE_TITLE." will be back soon.</center></font></b>";
		}
		echo "<br>".$errMsg;
	}
	
	function setMysqlKeyword($str="",$sep=",")
	{
		$this->mysqlKeyword=explode($sep,$str);
	}
	function isMysqlKeyword($string)
	{
		if(in_array($string,$this->mysqlKeyword))
		{
			return true;
		}
		return false;
	}

	function quickIncrement($table,$column,$condition) {
		$sql = "UPDATE ".$table." SET";
		$sql .= " ".$column."=";
		$sql .= $column."+1";
		if (!empty($condition)) $sql .= " WHERE ".$condition;
		if (!empty($condition) && strpos($condition, " IN (") === false) $sql .= " LIMIT 1";
		$this->setQuery($sql);
		$result = @mysqli_query($this->link,$sql);
		if (!$result) $this->mysqlError($sql);
		$rows = mysqli_affected_rows($this->link);
		if ($rows == 0) return true;
		else return $rows;
	}

	function moreIncrement($table,$column,$condition,$count) {
		if ($count > 0) {
			$sql = "UPDATE ".$table." SET";
			$sql .= " ".$column."=";  
			$sql .= $column."+".$count;
			if (!empty($condition)) $sql .= " WHERE ".$condition;		
			$sql .= " LIMIT 1";
			$this->setQuery($sql);
			$result = @mysqli_query($this->link,$sql);
			if (!$result) $this->mysqlError($sql);		
			$rows = mysqli_affected_rows($this->link);
			if ($rows == 0) return true;
			else return $rows;
		}
	}
}
?>