<?php 
include("../includes/connection.php");

$ef_id  =  $obj->filter_mysql($obj->filter_numeric($_REQUEST['ef_id'])); 
$dataEF = $obj->selectData(TABLE_EMAIL_FACILITY,"","ef_id='".$ef_id."'",1);
//pre($dataEF); exit;
?>
 
<div class="form-group" id="email_format_existing">
	<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Email Format </label>
	<div class="col-sm-9">
	<? 	
		require_once(FCKPATH.'fckeditor.php');
		$oFCKeditor = new FCKeditor('email_msg') ;
		$oFCKeditor->BasePath	=FCKPATH.'' ;
		//$oFCKeditor->Config['SkinPath'] = FURL.'includes/Office2007Real/' ;
		$oFCKeditor->Height	= 300 ;
		$oFCKeditor->Width	= 700 ;	
		$oFCKeditor->Value	=html_entity_decode($dataEF['ef_email_formet']);								
		$oFCKeditor->Create(); 
	?>
	</div>
</div>	