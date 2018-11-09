<?php
define('INCLUDE_CHECK',true);
if (isset($_GET['debug'])) {
	define('INCLUDE_CHECK',true);
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}

include '/var/www/html/config.php';
include '/var/www/html/functions.php';

while(true) {
  try {	
	# Handle feed and always on
	$outletEntriesAO = $conn->query("SELECT * FROM outlet_entries WHERE alwaysOn = 1");
	while ($row = $outletEntriesAO->fetch_assoc()) {
		$returnValueQuery = $conn->query("SELECT * FROM parameter_entries WHERE type_id=26 ORDER BY id DESC LIMIT 1"); // Check for feeding.
		$returnValue = $returnValueQuery->fetch_array();
		$outletTriggerParam = $row['outletTriggerParam'];
		$moduleInfoAO = $conn->query("SELECT * FROM module_entries WHERE id = ".$row['moduleId']);
		$moduleInfoReturnAO = $moduleInfoAO->fetch_assoc();
		if ($row['offAtFeeding'] == 1 && $returnValue['value'] == 0) { // turn it back on if feeding is over but the outlet is off.
			if ($row['outletStatus'] == 0 && $row['alwaysOn'] == 1 ) {
				# Turn outlet on that's supposed to be on but isn't. This will help on first boot.
				file_get_contents("http://".$moduleInfoReturnAO['moduleAddress']."/".$row['portNumber']."/on");
				echo("Turning on ".$moduleInfoReturnAO['moduleAddress']."/".$row['portNumber']."\n");
			}
		} else if ($row['offAtFeeding'] == 1 && $returnValue['value'] == 1) {
			$currentTime = time();
			$recordTime = correctTZEpoch($returnValue['timestamp'], $site_settings['tz']);
			echo "recordTime: ".$recordTime." ".$currentTime."\n";
			if ($recordTime + $site_settings['feedTime'] < $currentTime) {	// Check if feeding time is over.
				$conn->query("INSERT INTO parameter_entries (type_id, value) VALUES (26, 0)"); // Set that feeding is over.
				echo("feeding ending");
			}
		} else if ($row['offAtFeeding'] == 0) {
			if ($row['outletStatus'] == 0 && $row['alwaysOn'] == 1 ) {
				# Turn outlet on that's supposed to be on but isn't. This will help on first boot.
				file_get_contents("http://".$moduleInfoReturnAO['moduleAddress']."/".$row['portNumber']."/on");
				echo("Turning on ".$moduleInfoReturnAO['moduleAddress']."/".$row['portNumber']."\n");
			}

		}
	}

	# Handle trigger outlets
	$outletEntries = $conn->query("SELECT * FROM outlet_entries WHERE outletTriggerTest != '' AND outletTriggerCommand != '' AND outletTriggerParam != 0");
	while ($row = $outletEntries->fetch_assoc()) {
		if ($row['outletStatus'] == 0) {
			$row['outletStatus'] = 'off';
		} else {
			$row['outletStatus'] = 'on';
		}
		$outletTriggerTest = $row['outletTriggerTest'];
		$outletTriggerCommand = strtolower($row['outletTriggerCommand']);
		if ($outletTriggerCommand == 'on') {
			$outletTriggerCommandOpposite = 'off';
		} else {
			$outletTriggerCommandOpposite = 'on';
		}
		$outletTriggerParam = $row['outletTriggerParam'];
		$outletTriggerValue = $row['outletTriggerValue'];
		$moduleInfo = $conn->query("SELECT * FROM module_entries WHERE id = ".$row['moduleId']);
		$moduleInfoReturn = $moduleInfo->fetch_assoc();
		$returnValueQuery = $conn->query("SELECT parameter_entries.value FROM parameter_entries WHERE type_id=$outletTriggerParam ORDER BY id DESC LIMIT 1");
		$returnValue = $returnValueQuery->fetch_array();
		if ($outletTriggerTest == ">") {
			if ($returnValue['value'] > $outletTriggerValue) {
				echo $returnValue['value']." is ".$outletTriggerTest." ".$outletTriggerValue."\n";
				if ($row['outletStatus'] != $outletTriggerCommand) {
					file_get_contents("http://".$moduleInfoReturn['moduleAddress']."/".$row['portNumber']."/".$outletTriggerCommand);
					$conn->query("INSERT INTO outlet_trigger_entries (moduleId, outletId, paramId, value) VALUES (".$moduleInfoReturn['id'].",".$row['portNumber'].", ".$outletTriggerParam.", '".$outletTriggerCommand."')");
				}
			} else {
				echo $returnValue['value']." is not ".$outletTriggerTest." ".$outletTriggerValue."\n"; 
				if ($row['outletStatus'] != $outletTriggerCommandOpposite) {
					file_get_contents("http://".$moduleInfoReturn['moduleAddress']."/".$row['portNumber']."/".$outletTriggerCommandOpposite);
					$conn->query("INSERT INTO outlet_trigger_entries (moduleId, outletId, paramId, value) VALUES (".$moduleInfoReturn['id'].",".$row['portNumber'].", ".$outletTriggerParam.", '".$outletTriggerCommandOpposite."')");
				}
			}
		} elseif ($outletTriggerTest == "<") {
			if ($returnValue['value'] < $outletTriggerValue) {
				echo $returnValue['value']." is ".$outletTriggerTest." ".$outletTriggerValue."\n"; 
				if ($row['outletStatus'] != $outletTriggerCommand) {
					file_get_contents("http://".$moduleInfoReturn['moduleAddress']."/".$row['portNumber']."/".$outletTriggerCommand);
					$conn->query("INSERT INTO outlet_trigger_entries (moduleId, outletId, paramId, value) VALUES (".$moduleInfoReturn['id'].",".$row['portNumber'].", ".$outletTriggerParam.", '".$outletTriggerCommand."')");
				}
			} else {
				echo $returnValue['value']." is not ".$outletTriggerTest." ".$outletTriggerValue."\n"; 
				if ($row['outletStatus'] != $outletTriggerCommandOpposite) {
					file_get_contents("http://".$moduleInfoReturn['moduleAddress']."/".$row['portNumber']."/".$outletTriggerCommandOpposite);
					$conn->query("INSERT INTO outlet_trigger_entries (moduleId, outletId, paramId, value) VALUES (".$moduleInfoReturn['id'].",".$row['portNumber'].", ".$outletTriggerParam.", '".$outletTriggerCommandOpposite."')");
				}
			}

		} elseif ($outletTriggerTest == ">=") {
			if ($returnValue['value'] >= $outletTriggerValue) {
				echo $returnValue['value']." is ".$outletTriggerTest." ".$outletTriggerValue."\n"; 
				echo "INSERT INTO outlet_trigger_entries (moduleId, outletId, paramId, value) VALUES (".$moduleInfoReturn['id'].",".$row['portNumber'].", ".$outletTriggerParam.", ".$outletTriggerCommand.")\n";
				if ($row['outletStatus'] != $outletTriggerCommand) {
					file_get_contents("http://".$moduleInfoReturn['moduleAddress']."/".$row['portNumber']."/".$outletTriggerCommand);
					$conn->query("INSERT INTO outlet_trigger_entries (moduleId, outletId, paramId, value) VALUES (".$moduleInfoReturn['id'].",".$row['portNumber'].", ".$outletTriggerParam.", '".$outletTriggerCommand."')");
				}
			} else {
				echo $returnValue['value']." is not ".$outletTriggerTest." ".$outletTriggerValue."\n"; 
				echo "INSERT INTO outlet_trigger_entries (moduleId, outletId, paramId, value) VALUES (".$moduleInfoReturn['id'].",".$row['portNumber'].", ".$outletTriggerParam.", ".$outletTriggerCommandOpposite.")\n";
				if ($row['outletStatus'] != $outletTriggerCommandOpposite) {
					file_get_contents("http://".$moduleInfoReturn['moduleAddress']."/".$row['portNumber']."/".$outletTriggerCommandOpposite);
					$conn->query("INSERT INTO outlet_trigger_entries (moduleId, outletId, paramId, value) VALUES (".$moduleInfoReturn['id'].",".$row['portNumber'].", ".$outletTriggerParam.", '".$outletTriggerCommandOpposite."')");
				}
			}

		} elseif ($outletTriggerTest == "<=") {
			if ($returnValue['value'] <= $outletTriggerValue) {
				echo $returnValue['value']." is ".$outletTriggerTest." ".$outletTriggerValue."\n"; 
				if ($row['outletStatus'] != $outletTriggerCommand) {
					file_get_contents("http://".$moduleInfoReturn['moduleAddress']."/".$row['portNumber']."/".$outletTriggerCommand);
					$conn->query("INSERT INTO outlet_trigger_entries (moduleId, outletId, paramId, value) VALUES (".$moduleInfoReturn['id'].",".$row['portNumber'].", ".$outletTriggerParam.", '".$outletTriggerCommand."')");
				}
			} else {
				echo $returnValue['value']." is not ".$outletTriggerTest." ".$outletTriggerValue."\n"; 
				if ($row['outletStatus'] != $outletTriggerCommandOpposite) {
					file_get_contents("http://".$moduleInfoReturn['moduleAddress']."/".$row['portNumber']."/".$outletTriggerCommandOpposite);
					$conn->query("INSERT INTO outlet_trigger_entries (moduleId, outletId, paramId, value) VALUES (".$moduleInfoReturn['id'].",".$row['portNumber'].", ".$outletTriggerParam.", '".$outletTriggerCommandOpposite."')");
				}
			}

		} elseif ($outletTriggerTest == "=") {
			if ($returnValue['value'] == $outletTriggerValue) {
				echo $returnValue['value']." is ".$outletTriggerTest." ".$outletTriggerValue."\n"; 
				if ($row['outletStatus'] != $outletTriggerCommand) {
					file_get_contents("http://".$moduleInfoReturn['moduleAddress']."/".$row['portNumber']."/".$outletTriggerCommand);
					$conn->query("INSERT INTO outlet_trigger_entries (moduleId, outletId, paramId, value) VALUES (".$moduleInfoReturn['id'].",".$row['portNumber'].", ".$outletTriggerParam.", '".$outletTriggerCommand."')");
				}
			} else {
				echo $returnValue['value']." is not ".$outletTriggerTest." ".$outletTriggerValue."\n"; 
				if ($row['outletStatus'] != $outletTriggerCommandOpposite) {
					file_get_contents("http://".$moduleInfoReturn['moduleAddress']."/".$row['portNumber']."/".$outletTriggerCommandOpposite);
					$conn->query("INSERT INTO outlet_trigger_entries (moduleId, outletId, paramId, value) VALUES (".$moduleInfoReturn['id'].",".$row['portNumber'].", ".$outletTriggerParam.", '".$outletTriggerCommandOpposite."')");
				}
			}

		}
	}
	sleep(1);
  } catch (Exception $e) {
    echo "Waiting on db and apache to be alive";
  }
}
?>
