<?php 
include("../includes/connection.php");

 $member_type = $obj->filter_mysql($_REQUEST['member_type']);

if($member_type=='all')
{ 
	$res=$obj->selectData(TABLE_EMAIL_FACILITY,"","email_type='a' and ef_status='Active'","");
}
else
{
	$res=$obj->selectData(TABLE_EMAIL_FACILITY,"","(email_type='a' || email_type='s') and ef_status='Active'","");
}
$str = "<option value=''>Select Email Format</option>";
while($row=mysqli_fetch_array($res))
{
	$str.="<option value='".$row['ef_id']."'";
	if($row['ef_id']==$selval)
	{
		$str.=' selected';
	}
	$str.=">".$row['ef_title']."</option>";
}
echo $str;

?>