<?php
define('INCLUDE_CHECK',true);
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../config.php';
include '../functions.php';
if (isset($_GET['metric']) && isset($_GET['value'])) {
	$metric = $conn->real_escape_string($_GET['metric']);
	$value = $conn->real_escape_string($_GET['value']);
	$query = $conn->query("SELECT id FROM parameter_types WHERE eventName='".$metric."'");
	$typeID = $query->fetch_array();
	$insertData = $conn->query("INSERT INTO parameter_entries (type_id,value) VALUES (".$typeID['id'].", ".$value.")");
	  echo("Metric Added");
} elseif (isset($_GET['aquapip']) && isset($_GET['serial']) && isset($_GET['firmware']) && isset($_GET['address'])) {
	$ipAddress = $conn->real_escape_string($_GET['address']);
	$serial = $conn->real_escape_string($_GET['serial']);
	$firmware = $conn->real_escape_string($_GET['firmware']);
	#$status = $conn->real_escape_string($_GET['status']);
	$epoch = time();
	$checkQuery = $conn->query("SELECT moduleSerial from module_entries where moduleSerial = '".$serial."'");
	if (mysqli_num_rows($checkQuery) > 0) {
		$insertModule = $conn->query("UPDATE module_entries SET moduleType = 2, moduleSerial = '$serial', moduleAddress = '$ipAddress', moduleFirmware = '$firmware', epoch = '$epoch' WHERE moduleSerial = '$serial'");
	  } else {
		$insertModule = $conn->query("INSERT INTO module_entries (moduleType, moduleSerial, moduleAddress, moduleFirmware, epoch) VALUES (2, '$serial', '$ipAddress', '$firmware', '$epoch')");
		$last_id = $conn->insert_id;
		# Setup the ports for configuration later
		$getPortCount = $conn->query("SELECT featureCount from module_types WHERE id = 2")->fetch_assoc();
		for ($x = 1; $x <= $getPortCount['featureCount']; $x++) {
			$insertPorts = $conn->query("INSERT INTO outlet_entries (moduleId) VALUES ($last_id)"); 
		}
	}
} else {
  echo("missing something");
}
?>
