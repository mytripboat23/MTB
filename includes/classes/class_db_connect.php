<?php
class db_connect
{
	function db_connect()
	{
		$this->host = DBHOST;
		$this->user = DBUSER;
		$this->pass = DBPASS;
		$this->db = DBNAME;
		$this->link = '';
		
		return $this->connect($this->host, $this->user, $this->pass, $this->db, false);
	}
	
	
	function connect($host='', $user='', $pass='', $db='', $persistant=false) 
	{
		if (!empty($host)) $this->host = $host; 
		if (!empty($user)) $this->user = $user; 
		if (!empty($pass)) $this->pass = $pass;

		if ($persistant) 
			$this->link = mysqli_pconnect($this->host, $this->user, $this->pass) or $this->connectionError(mysqli_connect_error());
		else 
			$this->link = mysqli_connect($this->host, $this->user, $this->pass) or $this->connectionError(mysqli_connect_error());
		if (!$this->link) 
		{
			$this->connectionError(mysqli_connect_error());
			return false;
		} 
		
		// Select the database
		if (!$this->db_select($this->link,$db))
		{
			return false;
		}
		else
		{			
			return $this->link;  // success
		}
	}
	
	function db_select($link,$db='')
	{
		if (!empty($db)) $this->db = $db; 
		if (!mysqli_select_db($link,$this->db)) 
		{
			$this->connectionError(mysqli_connect_error());
			return false;
		}
		return true;
	}
	function connectionError($err)
	{
		if(!DEBUGMODE)
		{
			$errMsg="<b><font color='red' face='Verdana, Arial, Helvetica, sans-serif' size='2'><center>ERROR!!".$err."</center></font></b>";
		}
		else
		{
			$errMsg="<b><font color='red' face='Verdana, Arial, Helvetica, sans-serif' size='2'><center>It seems something went wrong. ".SITE_TITLE." will be back soon.</center></font></b>";
		}
		echo "<br>".$errMsg;
		exit;
	}
	
	function db_close($link)
	{
		mysqli_close($link);
		//mysqli_close($this->link);
	}
}
?>