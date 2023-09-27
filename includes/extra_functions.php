<?php

function profilePicture($profile_id,$width="",$height="",$extra="")
{
	$noimage=USER_IMAGE."noimage.gif";
	global $obj;
	$width=$width?$width:50;
	$height=$height?$height:50;
	$data=$obj->selectData(TABLE_USER,"user_image","user_id='".$profile_id."'",1);
	if($data&&$data['user_image'])
	{
		$image_path=USER_IMAGE.$data['user_image'];
		if(file_exists($image_path)&&(!is_dir($image_path)))
		{
			$image=$image_path;
		}
		else
		{
			$image=$noimage;
		}
	}
	else
	{
		$image=$noimage;
	}
	return showThumb($image,$width,$height,$extra);
}
function getCurrentUsers()
{
	global $obj;
	$out=array();
	$res=$obj->selectData(TABLE_USER,"","user_status='Active'");
	while($data=mysqli_fetch_assoc($res))
	{
		$out[$data['user_id']]=$data['user_name'];
	}
	return $out;
}

function loadMailTemplate($functionality_name)
{
	global $obj;
	$tpl=$obj->selectData(TABLE_EMAIL_TEMPLATE,"","template_title='".$functionality_name."'",1);
	return $tpl;
}



function getProductList($cat_id)
{
	global $obj;
	$data=$obj->selectData(TABLE_PRODUCT,"count(product_id) as total_product","product_category='".$cat_id."' and product_status <>'Deleted'",1);
	return $data['total_product']?$data['total_product']:0;
}

/********* AM PM Calculation ****************/
function timeFormat($timegiven)
{
if($timegiven!='')
{
	$times=explode(":",$timegiven);
				if($times[0] > 12)
					{
						$hr=$times[0]-12;
						$format="PM";
					}
					if($times[0] == 12)
					{
						$hr=$times[0];
						$format="PM";
					}
					if($times[0] < 12)
					{
						$hr=$times[0];
						$format="AM";
					}
				 $main_time=$hr.':'.$times[1].' '.$format;
	
		return $main_time;
}
	else
	{
		echo " ";
	}
}
/********************************************/
?>