<?php
define('INCLUDE_CHECK',true);

include '../config.php';
include '../functions.php';
if (isset($_GET['metric']) && isset($_GET['value'])) {
	$metric = $conn->real_escape_string($_GET['metric']);
	$value = $conn->real_escape_string($_GET['value']);
	$query = $conn->query("SELECT id FROM parameter_types WHERE eventName='".$metric."'");
	if ($metric == "Temperature") {
		$value = $value * 9.0 / 5.0 + 32.0; # Convert to F
	}
	$typeID = $query->fetch_array();
	$insertData = $conn->query("INSERT INTO parameter_entries (type_id,value) VALUES (".$typeID['id'].", ".$value.")");
	echo("Metric Added");
} elseif (isset($_GET['aquapip']) && isset($_GET['serial']) && isset($_GET['firmware']) && isset($_GET['address']) && isset($_GET['module']) && isset($_GET['status'])) {
	$ipAddress = $conn->real_escape_string($_GET['address']);
	$serial = $conn->real_escape_string($_GET['serial']);
	$firmware = $conn->real_escape_string($_GET['firmware']);
	$moduleType = $conn->real_escape_string($_GET['module']);
	$status = explode(",",$conn->real_escape_string($_GET['status']));
	$epoch = time();
	$checkQuery = $conn->query("SELECT moduleSerial, id from module_entries where moduleSerial = '".$serial."'");
	if (mysqli_num_rows($checkQuery) > 0) {
		$moduleId = $checkQuery->fetch_array();
		var_dump($moduleId);
		$updateModule = $conn->query("UPDATE module_entries SET moduleType = '$moduleType', moduleSerial = '$serial', moduleAddress = '$ipAddress', moduleFirmware = '$firmware', epoch = '$epoch' WHERE moduleSerial = '$serial'");
		if (strpos($_GET['status'], ',') !== false) { # Check if the original string had a comma
			foreach ($status as $key => $value) {
				$realKey = $key + 1;
				$updatePort = $conn->query("UPDATE outlet_entries SET outletStatus = '$value' WHERE moduleId = '".$moduleId['id']."' and portNumber = '$realKey'");
			}
		} 
	  } else {
		$insertModule = $conn->query("INSERT INTO module_entries (moduleType, moduleSerial, moduleAddress, moduleFirmware, epoch) VALUES ('$moduleType', '$serial', '$ipAddress', '$firmware', '$epoch')");
		$last_id = $conn->insert_id;
		# Setup the ports for configuration later
		$getPortCount = $conn->query("SELECT featureCount from module_types WHERE id = 2")->fetch_assoc();
		for ($x = 1; $x <= $getPortCount['featureCount']; $x++) {
			$insertPorts = $conn->query("INSERT INTO outlet_entries (moduleId, portNumber) VALUES ($last_id, $x)"); 
		}
	}
} else {
  echo("missing something");
}
?>
