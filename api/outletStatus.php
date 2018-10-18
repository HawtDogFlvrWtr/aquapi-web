<?php
define('INCLUDE_CHECK',true);

include '../config.php';
include '../functions.php';
if (isset($_GET['module'])) {
  $module = explode("-",$conn->real_escape_string($_GET['module']));
  $returnValueQuery = $conn->query("SELECT * FROM outlet_entries WHERE moduleId = $module[0] AND portNumber = $module[1]");
  $returnValue = $returnValueQuery->fetch_array();
  echo $returnValue['outletStatus'];
} else {
  echo "You're missing something";
}
?>
