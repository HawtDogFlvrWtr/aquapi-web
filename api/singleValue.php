<?php
define('INCLUDE_CHECK',true);

include '../config.php';
include '../functions.php';
$devices = array('light','pump','weather', 'heater');
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
	#$returnValueQueryAvg = $conn->query("SELECT AVG(parameter_entries.value) AS avg_value FROM parameter_entries INNER JOIN parameter_types ON parameter_types.id=parameter_entries.type_id WHERE parameter_entries.type_id = ".$r['type_id']);
	#$returnValueAssoc = $returnValueQueryAvg->fetch_assoc();
	#$r['avg_value'] = round($returnValueAssoc['avg_value'], $r['decimals']);
#  	if ($r['type_id'] == 23) {
	    $r['value'] = round($r['value'], $r['decimals']);
#	  }
	$r['timestamp'] = correctTZ($r['timestamp'], $site_settings['tz']);
	$rows[] = $r;
  }
  echo json_encode($rows);
} elseif(isset($_GET['devices'])) {
	$outletArray = [];
	$outletTypes = $conn->query("SELECT id, outletType, typeIcon, typeColor FROM outlet_types");
	while ($r = $outletTypes->fetch_assoc()) {
		$id = $r['id'];
		$type = $r['outletType'];
		$icon = $r['typeIcon'];
		$iconColor = $r['typeColor'];
		$queryOutlets = $conn->query("SELECT count(id) as count FROM outlet_entries WHERE outletType = $id AND outletStatus = 1");
		$count = $queryOutlets->fetch_assoc();
		$count = $count['count'];
		if ($count > 0) {
			$outletArray[] = "<i title='$type On' id='$type' style='color: $iconColor' class='ml-1 mdi mdi-24px $icon noti-icon'></i>";
		}
	}
	echo implode(" ", $outletArray);
} else {
  echo "You're missing something";
}
?>
