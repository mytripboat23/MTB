<?php include("../../includes/connection.php");
adminSecure();

if (! empty($_POST["keyword"])) {
    $sqlP = $obj->selectData(TABLE_USER." u, ".TABLE_USER_LOGIN." ul","","u.user_full_name like '%".$_POST["keyword"]."%' and u.user_status='Active' and ul.u_login_status='Active' and u.u_login_id=ul.u_login_id");
	if (mysqli_num_rows($sqlP)>0) {
        ?>
<ul id="country-list">
<?php
	while($resP = mysqli_fetch_array($sqlP))
	{
?>
   <li
        onClick="selectCountry('<?php echo $resP["user_full_name"]; ?>','<?php echo $resP["user_id"]; ?>');">
      <?php echo $resP["user_full_name"]; ?>
    </li>
<?php
        } // end for
        ?>
</ul>
<?php
    } // end if not empty
}
?>
