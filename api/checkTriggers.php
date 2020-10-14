<?php
define('INCLUDE_CHECK',true);
if (isset($_GET['debug'])) {
	define('INCLUDE_CHECK',true);
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}

include '/var/www/html/config.php';
include '/var/www/html/functions.php';
$feedTimer = $site_settings['feedTime'];
function updateDB($conn, $moduleId, $outletId, $paramId, $value) {
	# Check if we already inserted this less than a minute ago.
	$result = $conn->query("SELECT count(id) as count FROM outlet_trigger_entries WHERE moduleId = $moduleId AND outletId = $outletId AND paramId = $paramId AND value = '$value' AND timestamp > date_sub(now(), interval 1 minute)");
	$count = $result->fetch_assoc() or die('-99'.mysqli_error());
	if ($count['count'] < 1) {
		$conn->query("INSERT INTO outlet_trigger_entries (moduleId, outletId, paramId, value) VALUES (".$moduleId.", ".$outletId.", ".$paramId.", '".$value."')");
	}
}

function changePort($ip, $portNum, $value) {
	$realPortNum = $portNum;
	$portNum = $portNum - 1;
	echo "Telling $ip/$realPortNum to turn $value \n";
	$url = 'http://admin:seven8910@'.$ip.'/restapi/relay/outlets/'.$portNum.'/state/';
	if ($value == 'ON') {
		$value = 'true';
	} else {
		$value = 'false';
	}
	$data = array("value" => $value);
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Requested-With: XMLHttpRequest"));
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
	$response = curl_exec($ch);
}

while(true) {
  try {	
	// Get Settings Data
	$setSql = "SELECT dashboard_update, tz, graphLimit, defaultGraphLimit, performAction, pumpStatus, lightStatus, username, user_password, sessionId, light_override, pump_override, tempScale, feedTime, cleanTime, version FROM settings";
	$currentTimeTimer = date('H:i');
	$result = $conn->query($setSql);
	$site_settings = $result->fetch_assoc() or die('-99'.mysqli_error());
	if ($feedTimer != $site_settings['feedTime']) {
		echo "Feed timer change from $feedTimer to ".$site_settings['feedTime']."\n";
	}
	$feedTimer = $site_settings['feedTime'];
	$outletEntries = $conn->query("SELECT * FROM outlet_entries");
	while ($row = $outletEntries->fetch_assoc()) {
		$onTime = $row['on_time'];
		$offTime = $row['off_time'];
		$offFeeding = $row['offAtFeeding'];
		$outletStatus = strtoupper($row['outletStatus']); # Ensure uppercase for logic
		if ($outletStatus == 0) {
			$outletStatus = 'OFF';
		} else {
			$outletStatus = 'ON';
		}
		$outletTriggerTest = $row['outletTriggerTest'];
		$outletTriggerCommand = strtoupper($row['outletTriggerCommand']); # Ensure uppercase for logic
		$outletTriggerParam = $row['outletTriggerParam'];
		$outletTriggerValue = $row['outletTriggerValue'];
		$portNumber = $row['portNumber'];
		$moduleId = $row['moduleId'];
		$moduleInfo = $conn->query("SELECT * FROM module_entries WHERE id = $moduleId");
		$moduleInfoReturn = $moduleInfo->fetch_assoc();
		$moduleAddress = $moduleInfoReturn['moduleAddress'];
		$moduleId = $moduleInfoReturn['id'];
		$alwaysOn = $row['alwaysOn'];
		$triggered = 0;
		$offOutlet = 0;
		$supOn = FALSE; # Setting loop default
		$supOff = FALSE; # Setting loop default
		if ($outletTriggerParam != 0) {
			# Handle unconfigured ports (Make sure off)
			if ($row['outletStatus'] == 1 && is_null($outletTriggerTest) && is_null($outletTriggerCommand) && is_null($outletTriggerParam) && is_null($alwaysOn) && is_null($onTime) && is_null($offTime)) {
				$outletTriggerCommand = 'OFF';
				$outletTriggerParam = 0;
				$offOutlet = 1;
				echo "HERE 6\n";
				changePort($moduleAddress, $portNumber, $outletTriggerCommand);
				updateDB($conn, $moduleId, $portNumber, $outletTriggerParam, $outletTriggerCommand);
			}

			# Handle trigger outlets
			if (!is_null($outletTriggerTest) && !is_null($outletTriggerCommand) && !is_null($outletTriggerParam)) { # Ensure not disabled port
				$returnValueQuery = $conn->query("SELECT AVG(parameter_entries.value) AS value FROM parameter_entries WHERE type_id=$outletTriggerParam AND timestamp > NOW() - INTERVAL 60 SECOND ORDER BY id DESC LIMIT 1");
				$returnValue = $returnValueQuery->fetch_array();
				$paramValue = $returnValue['value'];
				if (!is_null($paramValue)) { # Only if value returns.
					if ($outletTriggerTest == ">") {
						if ($paramValue > $outletTriggerValue) {
							$triggered = 1;
						}
					} elseif ($outletTriggerTest == "<") {
						if ($paramValue < $outletTriggerValue) {
							$triggered = 1;
						}

					} elseif ($outletTriggerTest == ">=") {
						if ($paramValue >= $outletTriggerValue) {
							$triggered = 1;
						}

					} elseif ($outletTriggerTest == "<=") {
						if ($paramValue <= $outletTriggerValue) {
							$triggered = 1;
						}

					} elseif ($outletTriggerTest == "=") {
						if ($paramValue == $outletTriggerValue) {
							$triggered = 1;
						}

					}
				} else {
					echo("Nothing received for $outletTriggerParam type in the last 60 seconds\n");
				}
			}
		}
		# Handle trigger outcome
		if ($triggered == 1) {
			$outletState = $outletTriggerCommand;
			if ($outletStatus != $outletTriggerCommand) {
				changePort($moduleAddress, $portNumber, $outletTriggerCommand);
				updateDB($conn, $moduleId, $portNumber, $outletTriggerParam, $outletTriggerCommand);
			}
		} else { # NO longer in trigger
			# Calculate Opposite
			if (!is_null($onTime) && $currentTimeTimer >= $onTime && $currentTimeTimer < $offTime) {
				#echo "Current Time: $currentTimeTimer On Time: $onTime\n";
				$supOn = TRUE;
			}	
			if (!is_null($offTime) && ($currentTimeTimer >= $offTime || $currentTimeTimer < $onTime)) {
				#echo "Current Time: $currentTimeTimer Off Time: $offTime\n";
				$supOff = TRUE;
			}
			if ($alwaysOn == 1 || $supOn) {
				$outletState = 'ON';
			} elseif ($supOff) {
				$outletState = 'OFF';
			} else {

				$outletState = 'OFF';
			}
			if ($outletState != $outletStatus) {
				changePort($moduleAddress, $portNumber, $outletState);
				#updateDB($conn, $moduleId, $portNumber, $outletTriggerParam, $outletState);
			}
		}
		#echo "$triggered Param: $outletTriggerParam - Trigger Command: $outletTriggerCommand - True State: $outletState - Outlet Status: $outletStatus - Trigger Val: $outletTriggerTest $outletTriggerValue - Param Value: $paramValue\n";
	}
	sleep(1);
  } catch (Exception $e) {
	echo "Waiting on db and apache to be alive";
  }
}
?>
