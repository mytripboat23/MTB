	<?php
include("../../includes/connection.php");
$orderId  = $obj->filter_numeric($_REQUEST['apply_order_id']);
$order_status  = $obj->filter_mysql($_REQUEST['order_status']);

$uLogU['mp_status'] = $order_status;
$obj->updateData(TABLE_MANUAL_PAYMENT,$uLogU,"mp_id='".$orderId."'");
echo "Status changed.";
$obj->close_mysql();
?>