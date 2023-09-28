<?php include("../includes/connection.php");
userSecure();

if (! empty($_POST["keyword"])) {
    $sqlP = $obj->selectData(TABLE_CITIES,"","city_title like '%".$_POST["keyword"]."%' and city_status='Active'");
	if (mysqli_num_rows($sqlP)>0) {
        ?>
<ul id="country-list">
<?php
	while($resP = mysqli_fetch_array($sqlP))
	{
		if(!empty($_POST["type"]) && $_POST["type"]=='live_city')
		{
?>
   <li
        onClick="selectCity('<?php echo $resP["city_title"]; ?>','<?php echo $resP["city_id"]; ?>');">
      <?php echo $resP["city_title"]; ?>
    </li>
<?php 
		}
		else
		{
?>
 <li
        onClick="selectFromCity('<?php echo $resP["city_title"]; ?>','<?php echo $resP["city_id"]; ?>');">
      <?php echo $resP["city_title"]; ?>
    </li>
<?php 
		 }
     } 
		// end for
?>
</ul>
<?php
    } // end if not empty
}
?>
