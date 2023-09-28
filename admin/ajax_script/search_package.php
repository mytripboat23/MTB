<?php include("../../includes/connection.php");
adminSecure();

if (! empty($_POST["keyword"])) {
    $sqlP = $obj->selectData(TABLE_PACKAGE,"","pck_title like '%".$_POST["keyword"]."%' and pck_status='Active'");
	if (mysqli_num_rows($sqlP)>0) {
        ?>
<ul id="country-list">
<?php
	while($resP = mysqli_fetch_array($sqlP))
	{
?>
   <li
        onClick="selectCountry('<?php echo $resP["pck_title"]; ?>','<?php echo $resP["pck_id"]; ?>');">
      <?php echo $resP["pck_title"]; ?>
    </li>
<?php
        } // end for
        ?>
</ul>
<?php
    } // end if not empty
}
?>
