<?php
define('INCLUDE_CHECK',true);

include '../config.php';
include '../functions.php';
$devices = array('light','pump','weather');
if (isset($_GET['check'])) {
  if ($_GET['check'] != "") {
	$check = $conn->real_escape_string($_GET['check']);
	$getInfo = $conn->query("SELECT id FROM parameter_types WHERE eventName = '".$check."'");
	$getReturn = $getInfo->fetch_assoc();
  	$returnValueQuery = $conn->query("SELECT parameter_entries.type_id, parameter_entries.value, parameter_types.decimals, parameter_types.step, parameter_types.eventName FROM parameter_entries INNER JOIN parameter_types ON parameter_types.id=parameter_entries.type_id WHERE parameter_entries.id IN (SELECT MAX(parameter_entries.id) FROM parameter_entries GROUP BY parameter_entries.type_id) AND parameter_entries.type_id = ".$getReturn['id']);
  } else {
  	$returnValueQuery = $conn->query("SELECT parameter_entries.type_id, parameter_entries.timestamp,parameter_entries.value, parameter_types.decimals, parameter_types.step, parameter_types.eventName FROM parameter_entries INNER JOIN parameter_types ON parameter_types.id=parameter_entries.type_id WHERE parameter_entries.id IN (SELECT MAX(parameter_entries.id) FROM parameter_entries GROUP BY parameter_entries.type_id)");
  }
  $rows = array();
  while($r = $returnValueQuery->fetch_assoc()) {
  	if ($r['type_id'] == 23) {
	    $r['value'] = round($r['value'], 1);
	  }
	$r['timestamp'] = correctTZ($r['timestamp'], $site_settings['tz']);
	$rows[] = $r;
  }
  echo json_encode($rows);
} elseif(isset($_GET['devices'])) {
  if (isset($_GET['devices']) && in_array($_GET['devices'], $devices)) {
    $newDeviceName = $_GET['devices']."Status";
    $device = $conn->real_escape_string($newDeviceName);
    echo $site_settings[$device];
  }
} else {
  echo "You're missing something";
}
?>
