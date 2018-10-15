<?php
define('INCLUDE_CHECK',true);

include '../config.php';
include '../functions.php';
#$_GET['metric'] = 'Temperature';
#$_GET['value'] = '26.812';
if (isset($_GET['metric']) && isset($_GET['value'])) {
  $metric = $conn->real_escape_string($_GET['metric']);
  $value = $conn->real_escape_string($_GET['value']);
  $query = $conn->query("SELECT id FROM parameter_types WHERE eventName='".$metric."'");
  $typeID = $query->fetch_array();
  $insertData = $conn->query("INSERT INTO parameter_entries (type_id,value) VALUES (".$typeID['id'].", ".$value.")");
  echo("Metric Added");
} elseif (isset($_GET['status'])) {
  $status = $conn->real_escape_string($_GET['status']);
  $insertData = $conn->query("UPDATE settings SET weatherStatus = '".$status."'");
  echo("Status Updated");
} else {
  echo("missing something");
}
?>
