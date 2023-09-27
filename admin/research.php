<?php 
include("../includes/connection.php");
?>


<?php
  //$rsql=$obj->selectData(TABLE_USER,"","user_id<>0 and user_status='Active' and (user_first_name like '".$_GET['key']."%' or user_email like '".$_GET['key']."%') order by user_id asc"); 
	
	$rsql=$obj->selectData(TABLE_BECOME_AFFILIATE,"","baff_id<>0 and baff_status='Active' and (baff_username like '".$obj->filter_mysql($_GET['key'])."%') order by baff_id asc"); 
					
	$data = array();
	while ($row = mysqli_fetch_array($rsql)) {
		$userD = $obj->selectData(TABLE_USER,"","u_login_id='".$row['user_id']."' and user_status='Active'",1);
		array_push($data, $row['baff_username'].' ('.$userD['user_email'].')');	
	}	
	echo json_encode($data);		
 
 


 


?>