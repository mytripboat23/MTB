<?php
session_start();
include( "../includes/connection.php" );
adminSecure();
$return_url = urldecode( $_REQUEST[ 'return_url' ] );

$redirect_url = "show_package.php";
if ( $return_url ) {
  $redirect_url = $return_url;
}

$id = $obj->filter_mysql( $obj->filter_numeric( $_REQUEST[ 'uId' ] ) );

$mode = "add";
$table_caption = "Add New Tour Package";
$data = $obj->selectData( TABLE_PACKAGE, "", "pck_id='" . $id . "' and pck_status<>'Deleted'", 1 );
if ( $data ) {
  $mode = "edit";
  $table_caption = "Edit Tour Package Details";
}

if ( $_POST ) {

  switch ( $mode ) {
    case 'add':

      if ( $_FILES[ 'pphoto' ][ 'tmp_name' ] ) {
        list( $fileName, $error ) = $obj->uploadFile( 'pphoto', "../" . PACKAGE, 'jpg,png,jpeg' );
        if ( $error ) {
          $msg = $error;
          $err = 1;
        } else {
          $pacArr[ 'pck_photo' ] = $fileName;
        }
      }

      if ( !$err ) {
        $pacArr[ 'user_id' ] = $obj->filter_mysql( $_POST[ 'user_id' ] );

        $pacArr[ 'pck_title' ] = $obj->filter_mysql( htmlentities( $_POST[ 'pck_title' ] ) );
        $pacArr[ 'pck_dest' ] = $obj->filter_mysql( htmlentities( $_POST[ 'pck_dest' ] ) );

        $pacArr[ 'pck_month' ] = $obj->filter_mysql( htmlentities( $_POST[ 'pck_month' ] ) );

        $pacArr[ 'pck_start_dt' ] = date( "Y-m-d", strtotime( $_POST[ 'pck_start_dt' ] ) );
        $pacArr[ 'pck_start_tm' ] = $obj->filter_mysql( htmlentities( $_POST[ 'pck_start_tm' ] ) );
        $pacArr[ 'pck_end_dt' ] = date( "Y-m-d", strtotime( $_POST[ 'pck_end_dt' ] ) );

        $pacArr[ 'pck_start_pt' ] = $obj->filter_mysql( htmlentities( $_POST[ 'pck_start_pt' ] ) );
        $pacArr[ 'pck_end_pt' ] = $obj->filter_mysql( htmlentities( $_POST[ 'pck_end_pt' ] ) );

        $pacArr[ 'pck_capacity' ] = $obj->filter_numeric( $_POST[ 'pck_capacity' ] );
		
	    $pacArr['pck_curr']  = $obj->filter_numeric($_POST['pck_curr']);
        $pacArr['pck_price'] = $obj->filter_numeric($_POST['pck_price']);
		
		$pacArr['pck_foreign_curr']  = $obj->filter_numeric($_POST['pck_foreign_curr']);
        $pacArr['pck_foreign_price'] = $obj->filter_numeric($_POST['pck_foreign_price']);


        $pacArr[ 'pck_inc' ] = "," . implode( ",", $_POST[ 'faci' ] ) . ",";
        //$pacArr['pck_not_inc'] 			= ",".implode(",",$_POST['face']).",";

        $pacArr[ 'pck_desc' ] = htmlentities( $_POST[ 'pck_desc' ] );
        $pacArr[ 'pck_hotel_veh' ] = htmlentities( $_POST[ 'pck_hotel_veh' ] );
        $pacArr[ 'pck_terms' ] = htmlentities( $_POST[ 'pck_terms' ] );

        if ( isset( $_REQUEST[ 'pck_tags_list' ][ 0 ] ) && $_REQUEST[ 'pck_tags_list' ][ 0 ] != '' ) {
          for ( $tagCounter = 0; $tagCounter < count( $_REQUEST[ 'pck_tags_list' ] ); $tagCounter++ ) {
            $searchTags = $obj->selectData( TABLE_TAGS, "", "tag_title = '" . $_REQUEST[ 'pck_tags_list' ][ $tagCounter ] . "'", 1 );
            if ( !isset( $searchTags[ 'tag_id' ] ) ) {
              $tagA = array();
              $tagA[ 'tag_title' ] = $_REQUEST[ 'pck_tags_list' ][ $tagCounter ];
              $tagA[ 'tag_status' ] = 'Active';
              $tagAdd = $obj->insertData( TABLE_TAGS, $tagA );
            }
          }

          $pacArr[ 'pck_tags' ] = implode( ",", $_POST[ 'pck_tags_list' ] );
        }

        $pacArr[ 'pck_status' ] = $obj->filter_mysql( $_POST[ 'pck_status' ] );
        $pacArr[ 'pck_added' ] = CURRENT_DATE_TIME;
        $pacArr[ 'pck_updated' ] = CURRENT_DATE_TIME;

        $com_id = $obj->insertData( TABLE_PACKAGE, $pacArr );
        $obj->add_message( "message", ADD_SUCCESSFULL );
        $_SESSION[ 'messageClass' ] = 'successClass';
        $obj->reDirect( $redirect_url );
      }
      break;

    case 'edit':

      if ( $_FILES[ 'pphoto' ][ 'tmp_name' ] ) {
        list( $fileName, $error ) = $obj->uploadFile( 'pphoto', "../" . PACKAGE, 'jpg,png,jpeg' );
        if ( $error ) {
          $msg = $error;
          $err = 1;
        } else {
          $pacArr[ 'pck_photo' ] = $fileName;
        }
      }
      if ( !$err ) {


        $pacArr[ 'user_id' ] = $obj->filter_mysql( $_POST[ 'user_id' ] );
        $pacArr[ 'pck_title' ] = $obj->filter_mysql( htmlentities( $_POST[ 'pck_title' ] ) );
        $pacArr[ 'pck_dest' ] = $obj->filter_mysql( htmlentities( $_POST[ 'pck_dest' ] ) );
        $pacArr[ 'pck_hotel_veh' ] = htmlentities( $_POST[ 'pck_hotel_veh' ] );

        $pacArr[ 'pck_month' ] = $obj->filter_mysql( htmlentities( $_POST[ 'pck_month' ] ) );

        $pacArr[ 'pck_start_dt' ] = date( "Y-m-d", strtotime( $_POST[ 'pck_start_dt' ] ) );
        $pacArr[ 'pck_start_tm' ] = $obj->filter_mysql( htmlentities( $_POST[ 'pck_start_tm' ] ) );
        $pacArr[ 'pck_end_dt' ] = date( "Y-m-d", strtotime( $_POST[ 'pck_end_dt' ] ) );

        $pacArr[ 'pck_start_pt' ] = $obj->filter_mysql( htmlentities( $_POST[ 'pck_start_pt' ] ) );
        $pacArr[ 'pck_end_pt' ] = $obj->filter_mysql( htmlentities( $_POST[ 'pck_end_pt' ] ) );

        $pacArr[ 'pck_capacity' ] = $obj->filter_numeric( $_POST[ 'pck_capacity' ] );
		
        $pacArr['pck_curr']  = $obj->filter_numeric($_POST['pck_curr']);
        $pacArr['pck_price'] = $obj->filter_numeric($_POST['pck_price']);
		
		$pacArr['pck_foreign_curr']  = $obj->filter_numeric($_POST['pck_foreign_curr']);
        $pacArr['pck_foreign_price'] = $obj->filter_numeric($_POST['pck_foreign_price']);

        $pacArr[ 'pck_inc' ] = "," . implode( ",", $_POST[ 'faci' ] ) . ",";
        //$pacArr['pck_not_inc'] 			= ",".implode(",",$_POST['face']).",";

        if ( isset( $_REQUEST[ 'pck_tags_list' ][ 0 ] ) && $_REQUEST[ 'pck_tags_list' ][ 0 ] != '' ) {
          for ( $tagCounter = 0; $tagCounter < count( $_REQUEST[ 'pck_tags_list' ] ); $tagCounter++ ) {
            $searchTags = $obj->selectData( TABLE_TAGS, "", "tag_title = '" . $_REQUEST[ 'pck_tags_list' ][ $tagCounter ] . "'", 1 );
            if ( !isset( $searchTags[ 'tag_id' ] ) ) {
              $tagA = array();
              $tagA[ 'tag_title' ] = $_REQUEST[ 'pck_tags_list' ][ $tagCounter ];
              $tagA[ 'tag_status' ] = 'Active';
              $tagAdd = $obj->insertData( TABLE_TAGS, $tagA );
            }
          }

          $pacArr[ 'pck_tags' ] = implode( ",", $_POST[ 'pck_tags_list' ] );
        }

        $pacArr[ 'pck_desc' ] = htmlentities( $_POST[ 'pck_desc' ] );
        $pacArr[ 'pck_terms' ] = htmlentities( $_POST[ 'pck_terms' ] );

        $pacArr[ 'pck_status' ] = $obj->filter_mysql( $_POST[ 'pck_status' ] );

        $pacArr[ 'pck_updated' ] = CURRENT_DATE_TIME;


        $obj->updateData( TABLE_PACKAGE, $pacArr, "pck_id='" . $id . "'" );
        $obj->add_message( "message", UPDATE_SUCCESSFULL );
        $_SESSION[ 'messageClass' ] = 'successClass';
        $obj->reDirect( $redirect_url );
      }
      break;

  }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include("page_includes/common.php");?>
</head>
<body class="no-skin">
<?php include("page_includes/top_navbar.php");?>
<div class="main-container" id="main-container">
  <?php include("page_includes/sidebar.php");?>
  <div class="main-content">
    <div class="breadcrumbs" id="breadcrumbs"> 
      <script type="text/javascript">
						try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
	  </script>
      <ul class="breadcrumb">
        <li> <i class="ace-icon fa fa-home home-icon"></i> <a href="dashboard.php">Home</a> </li>
        <li> <a href="show_package.php">Package</a></li>
        <li class="active">
          <?=$data['pck_title'];?>
        </li>
      </ul>
      <!-- /.breadcrumb -->
      <?php /*?>
<div class="nav-search" id="nav-search">
  <form class="form-search" action="" method="get">
    <span class="input-icon">
      <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
      <i class="ace-icon fa fa-search nav-search-icon"></i> </span>
  </form>
</div>
      <?php */?>
      <!-- /.nav-search --> 
    </div>
    <div class="page-content">
      <div class="page-content-area">
        <div class="page-header">
          <h1> Package <small> <i class="ace-icon fa fa-angle-double-right"></i>
            <?=$table_caption;?>
            </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <?=$obj->display_message("message");?>
            <div class="hr hr32 hr-dotted"></div>
            <div class="row">
              <div class="col-xs-12">
                <form class="form-horizontal" role="form" name="packEdit" id="packEdit" method="post" action="" enctype="multipart/form-data">
                  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Title :</label>
                    <div class="col-sm-9">
                      <input type="text" id="pck_title" name="pck_title" placeholder="Package Title" class="col-xs-10 col-sm-5" value="<?=htmlentities($data['pck_title']);?>" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Owner/Agent:</label>
                    <div class="col-sm-9">
                      <select name="user_id" id="user_id" class="col-xs-10 col-sm-5">
                        <?=$obj->getUserSelected($data['user_id']);?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Destination :</label>
                    <div class="col-sm-9">
                      <input type="text" id="pck_dest" name="pck_dest" placeholder="Destination" class="col-xs-10 col-sm-5" value="<?=htmlentities($data['pck_dest']);?>" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Month Of Journey :</label>
                    <div class="col-sm-9">
                      <select class="form-select form-select-lg mb-3" name="pck_month" id="pck_month" >
                        <option value="">Select journey month </option>
                        <?php
                        $year = date( "Y" );
                        for ( $iM = 1; $iM <= 12; $iM++ ) {
                          ?>
                        <option value="<?php echo date("n", strtotime("$iM/12/$year"));?>" <?php if($data['pck_month'] == date("n", strtotime("$iM/12/$year"))){?>selected<?php }?>><?php echo date("F", strtotime("$iM/12/$year"));?></option>
                        <?php
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Start Date :</label>
                    <div class="col-sm-9">
                      <input type="text" id="pck_start_dt" name="pck_start_dt" placeholder="Start Date" class="col-xs-10 col-sm-5" value="<?php if(isset($data['pck_start_dt'])){?><?=date(FLD_DATE_FORMAT,strtotime($data['pck_start_dt']));?><?php }?>"  readonly="true"/>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Start Time :</label>
                    <div class="col-sm-9">
                      <input type="text" id="pck_start_tm" name="pck_start_tm" placeholder="Start Time" class="col-xs-10 col-sm-5" value="<?=htmlentities($data['pck_start_tm']);?>"  readonly="true"/>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> End Date :</label>
                    <div class="col-sm-9">
                      <input type="text" id="pck_end_dt" name="pck_end_dt" placeholder="End Date" class="col-xs-10 col-sm-5" value="<?php if(isset($data['pck_end_dt'])){?><?=date(FLD_DATE_FORMAT,strtotime($data['pck_end_dt']));?><?php }?>"  readonly="true"/>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Start Point :</label>
                    <div class="col-sm-9">
                      <input type="text" id="pck_start_pt" name="pck_start_pt" placeholder="Start Point" class="col-xs-10 col-sm-5" value="<?=htmlentities($data['pck_start_pt']);?>" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> End Point :</label>
                    <div class="col-sm-9">
                      <input type="text" id="pck_end_pt" name="pck_end_pt" placeholder="End Point" class="col-xs-10 col-sm-5" value="<?=htmlentities($data['pck_end_pt']);?>" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Capacity :</label>
                    <div class="col-sm-9">
                      <input type="number" id="pck_capacity" name="pck_capacity" placeholder="Capacity" class="col-xs-10 col-sm-5" value="<?=htmlentities($data['pck_capacity']);?>" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Price (
                      <?=CURRENCY;?>
                      ) :</label>
                    <div class="col-sm-1">
                     <select class="form-select" name="pck_curr" id="pck_curr">
                        <?php echo $obj->currencySelect($data['pck_curr']);?> 
                      </select>
                    </div>
                    <div class="col-sm-6">
                      <input type="text" id="pck_price" name="pck_price" placeholder="Price" class="col-xs-10 col-sm-5" value="<?=htmlentities($data['pck_price']);?>" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> foreign Price (
                      <?=CURRENCY;?>
                      ) :</label>
                    <div class="col-sm-1">
                    
                     <select class="form-select" name="pck_foreign_curr" id="pck_foreign_curr">
                        <?php echo $obj->currencySelect($data['pck_foreign_curr']);?> 
                      </select>
						
						
                    </div>
                    <div class="col-sm-6">
                      <input type="text" id="pck_foreign_price" name="pck_foreign_price" placeholder="Price" class="col-xs-10 col-sm-5" value="<?=htmlentities($data['pck_foreign_price']);?>" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Description </label>
                    <div class="col-sm-9">
                      <textarea id="pck_desc"  name="pck_desc" placeholder="Description" class="col-xs-10 col-sm-5 editor" style="width: 466px; height: 277px;"><?=html_entity_decode($data['pck_desc']);?>
</textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Hotel and Vehicle Details </label>
                    <div class="col-sm-9">
                      <textarea id="pck_hotel_veh"  name="pck_hotel_veh" placeholder="Hotel And Vehicle" class="col-xs-10 col-sm-5 editor" style="width: 466px; height: 277px;"><?=html_entity_decode($data['pck_hotel_veh']);?>
</textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Included </label>
                    <div class="col-sm-9">
                      <?php
                      $dataI = explode( ",", trim( $data[ 'pck_inc' ], "," ) );
                      $sqlF = $obj->selectData( TABLE_FACILITY_INC, "", "faci_status='Active'" );
                      while ( $resF = mysqli_fetch_array( $sqlF ) ) {
                        ?>
                      <input type="checkbox" name="faci[]" id="facil" value="<?=$resF['faci_id'];?>" <?php if(in_array($resF['faci_id'],$dataI)){?> checked="checked"<?php }?>>
                      <?=$resF['faci_title'];?>
                      &nbsp;&nbsp;
                      <?php }?>
                    </div>
                  </div>
                  <?php /*?>
<div class="form-group">
  <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Not Included </label>
  <div class="col-sm-9">
    <?php 
					$dataNI = explode(",",trim($data['pck_not_inc'],","));
					$sqlNF = $obj->selectData(TABLE_FACILITY_EXC,"","face_status='Active'");
					while($resNF = mysqli_fetch_array($sqlNF))
					{
					?>
    <input type="checkbox" name="face[]" id="facel" value="<?=$resNF['face_id'];?>" <?php if(in_array($resNF['face_id'],$dataNI)){?> checked="checked" <?php }?>> <?=$resNF['face_title'];?> &nbsp;&nbsp;
    <?php }?>

  </div>
</div>
                  <?php */?>
                  <div class="form-group" >
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Tags </label>
                    <div class="col-sm-9" id="tags">
                      <select class="select2 form-select lang col-xs-10 col-sm-10" name="pck_tags_list[]" id="pck_tag" multiple="multiple" data-tags="true">
                        <?php echo $obj->tagsSelect($data['pck_tags']);?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Terms </label>
                    <div class="col-sm-9">
                      <textarea id="pck_terms"  name="pck_terms" placeholder="Terms" class="col-xs-10 col-sm-5 editor" style="width: 466px; height: 277px;"><?=html_entity_decode($data['pck_terms']);?>
</textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Photo </label>
                    <div class="col-sm-9">
                      <input type="file" id="pphoto" class="col-xs-10 col-sm-5" name="pphoto"/>
                    </div>
                    <div class="col-sm-9">
                      <?php if($data['pck_photo']){?>
                      <img src="<?="../".PACKAGE.$data['pck_photo'];?>" width="200">
                      <?php }?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Status </label>
                    <div class="col-sm-9">
                      <select name="pck_status" class="col-xs-10 col-sm-5">
                        <option value="Active"  <?= htmlentities($data['pck_status'])=="Active"?'selected="selected"':'';?>>Active</option>
                        <option value="Inactive"  <?= htmlentities($data['pck_status'])=="Inactive"?'selected="selected"':'';?>>Inactive</option>
                      </select>
                    </div>
                  </div>
                  <div class="clearfix form-actions">
                    <div class="col-md-offset-3 col-md-9">
                      <input type="submit" name="submit" value="Save" class="btn btn-info">
                      &nbsp; &nbsp; &nbsp; </div>
                  </div>
                </form>
              </div>
              <!-- /.span --> 
            </div>
            <!-- /.row --> 
            <!-- /.row --> 
            <!-- PAGE CONTENT ENDS --> 
          </div>
          <!-- /.col --> 
        </div>
        <!-- /.row --> 
      </div>
      <!-- /.page-content-area --> 
    </div>
    <!-- /.page-content --> 
  </div>
  <!-- /.main-content -->
  <?php include("page_includes/footer.php");?>
</div>
<?php include("page_includes/dashboard_footer_script.php");?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script language="javascript" type="text/javascript">	
		$().ready(function() {
			$("#packEdit").validate({
					rules: {
						 
						pck_title: "required",						
						pck_validityy: 
							{
								required:true,
								number:true
							}
					},
					messages: {
						 
						pck_title: "Please enter Package title",
						pck_validityy: {
							required:"Please enter Valid Days",
							number:"Please enter a proper Valid Days!"
						}
					}
				});
		});			
</script> 
</script> 
<script src="https://cdn.tiny.cloud/1/gev1v6mh2xux4097lf3x9pzhj2o0yjo8bvbmedccl5w9z05w/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script> 
<script>
    tinymce.init({
      selector: '.editor',
		toolbar:false,
		menubar:false,
      //plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss',
      //toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
		height:'200px',
      tinycomments_mode: 'embedded',
      tinycomments_author: 'Author name',
      mergetags_list: [
        { value: 'First.Name', title: 'First Name' },
        { value: 'Email', title: 'Email' },
      ],
    });
	
	</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script> 
<script src="js/jquery-ui-timepicker-addon.js"></script>
<link rel="stylesheet" href="css/jquery-ui-timepicker-addon.css">
<script>
$(function() {
$("#pck_start_dt").datepicker({
        dateFormat: 'dd-mm-yy',
        onSelect: function(selected) {
          $("#pck_end_dt").datepicker("option","minDate", selected)
        }
    });

    $("#pck_end_dt").datepicker({
        dateFormat: 'dd-mm-yy',
        onSelect: function(selected) {
           $("#pck_start_dt").datepicker("option","maxDate", selected)
        }
    });
	
	$("#pck_start_tm").timepicker({
	 	  timeFormat: 'h:mm tt'
});

});

   $(document).ready(function() {



function hideSelected(value) {

  if (value && !value.selected) {
    return $('<span>' + value.text + '</span>');
  }
}

/*$('#jobseeker_skilldata').select2({
  maximumSelectionLength : 4 ,
  allowClear: false,
  templateResult: hideSelected,
});*/

$('#pck_tag').select2().on('select2:open', function() {
    $('.select2-search__field').attr('maxlength', 15);
});


$('#pck_tag').select2({
  dropdownParent: $('#tags'),
  maximumSelectionLength :10,
  language: {
        // You can find all of the options in the language files provided in the
        // build. They all must be functions that return the string that should be
        // displayed.
        maximumSelected: function (e) {
           var t = "Maximum of " + e.maximum + " Tags Allowed";
            //e.maximum != 1 && (t += "s");
            return t;
        }
    },
  allowClear: false,
  templateResult: hideSelected,
});

});

</script>
</body>
</html>