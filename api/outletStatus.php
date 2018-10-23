<?php
define('INCLUDE_CHECK',true);

include '../config.php';
include '../functions.php';
$module = explode("-",$conn->real_escape_string($_GET['module']));
$returnValueQuery = $conn->query("SELECT moduleId, portNumber, outletStatus FROM outlet_entries");
$rows = array();
while($r = $returnValueQuery->fetch_assoc()) {
	$rows[] = $r;
}
echo json_encode($rows);
?>
