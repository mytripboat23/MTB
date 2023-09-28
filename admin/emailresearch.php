<?php 
include("../includes/connection.php");

	 
	$rsql = $obj->selectData(TABLE_USER_LOGIN." as ul, ".TABLE_USER." as u","","ul.u_login_id=u.u_login_id and ul.u_suspend_status='n' and ul.user_suspend_comm_status='n' and (ul.u_login_status='Active' and u.user_status='Active') and (u.user_first_name like '".$obj->filter_mysql($_GET['key'])."%' or u.user_email like '".$obj->filter_mysql($_GET['key'])."%') order by u.u_login_id asc"); 

				
	$data = array();
	while ($row = mysqli_fetch_array($rsql)) 
	{	 
		array_push($data, $row['user_first_name'].' '.$row['user_last_name'].' ('.$row['user_email'].')');	
	}	
	echo json_encode($data);		
 
  

?>