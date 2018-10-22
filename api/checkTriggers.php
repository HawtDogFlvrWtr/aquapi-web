<?php
define('INCLUDE_CHECK',true);
if (isset($_GET['debug'])) {
	define('INCLUDE_CHECK',true);
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}

include '/var/www/html/config.php';
include '/var/www/html/functions.php';

$outletEntries = $conn->query("SELECT * FROM outlet_entries WHERE outletTriggerTest != '' AND outletTriggerCommand != '' AND outletTriggerParam != 0 AND outletTriggerValue != 0");
while ($row = $outletEntries->fetch_assoc()) {
	if ($row['outletStatus'] == 0) {
		$row['outletStatus'] = 'off';
		$outletStatusInt = 0;
	} else {
		$row['outletStatus'] = 'on';
		$outletStatusInt = 1;
	}
	$outletTriggerTest = $row['outletTriggerTest'];
	$outletTriggerCommand = strtolower($row['outletTriggerCommand']);
	if ($outletTriggerCommand == 'on') {
		$outletTriggerCommandOpposite = 'off';
		$outletStatusOppositeInt = 0;
	} else {
		$outletTriggerCommandOpposite = 'on';
		$outletStatusOppositeInt = 1;
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
			}
		} else {
			echo $returnValue['value']." is not ".$outletTriggerTest." ".$outletTriggerValue."\n"; 
			if ($row['outletStatus'] != $outletTriggerCommandOpposite) {
				file_get_contents("http://".$moduleInfoReturn['moduleAddress']."/".$row['portNumber']."/".$outletTriggerCommandOpposite);
			}
		}
	} elseif ($outletTriggerTest == "<") {
		if ($returnValue['value'] < $outletTriggerValue) {
			echo $returnValue['value']." is ".$outletTriggerTest." ".$outletTriggerValue."\n"; 
			if ($row['outletStatus'] != $outletTriggerCommand) {
				file_get_contents("http://".$moduleInfoReturn['moduleAddress']."/".$row['portNumber']."/".$outletTriggerCommand);
			}
		} else {
			echo $returnValue['value']." is not ".$outletTriggerTest." ".$outletTriggerValue."\n"; 
			if ($row['outletStatus'] != $outletTriggerCommandOpposite) {
				file_get_contents("http://".$moduleInfoReturn['moduleAddress']."/".$row['portNumber']."/".$outletTriggerCommandOpposite);
			}
		}

	} elseif ($outletTriggerTest == ">=") {
		if ($returnValue['value'] >= $outletTriggerValue) {
			echo $returnValue['value']." is ".$outletTriggerTest." ".$outletTriggerValue."\n"; 
			if ($row['outletStatus'] != $outletTriggerCommand) {
				file_get_contents("http://".$moduleInfoReturn['moduleAddress']."/".$row['portNumber']."/".$outletTriggerCommand);
			}
		} else {
			echo $returnValue['value']." is not ".$outletTriggerTest." ".$outletTriggerValue."\n"; 
			if ($row['outletStatus'] != $outletTriggerCommandOpposite) {
				file_get_contents("http://".$moduleInfoReturn['moduleAddress']."/".$row['portNumber']."/".$outletTriggerCommandOpposite);
			}
		}

	} elseif ($outletTriggerTest == "<=") {
		if ($returnValue['value'] <= $outletTriggerValue) {
			echo $returnValue['value']." is ".$outletTriggerTest." ".$outletTriggerValue."\n"; 
			if ($row['outletStatus'] != $outletTriggerCommand) {
				file_get_contents("http://".$moduleInfoReturn['moduleAddress']."/".$row['portNumber']."/".$outletTriggerCommand);
			}
		} else {
			echo $returnValue['value']." is not ".$outletTriggerTest." ".$outletTriggerValue."\n"; 
			if ($row['outletStatus'] != $outletTriggerCommandOpposite) {
				file_get_contents("http://".$moduleInfoReturn['moduleAddress']."/".$row['portNumber']."/".$outletTriggerCommandOpposite);
			}
		}

	} elseif ($outletTriggerTest == "=") {
		if ($returnValue['value'] == $outletTriggerValue) {
			echo $returnValue['value']." is ".$outletTriggerTest." ".$outletTriggerValue."\n"; 
			if ($row['outletStatus'] != $outletTriggerCommand) {
				file_get_contents("http://".$moduleInfoReturn['moduleAddress']."/".$row['portNumber']."/".$outletTriggerCommand);
			}
		} else {
			echo $returnValue['value']." is not ".$outletTriggerTest." ".$outletTriggerValue."\n"; 
			if ($row['outletStatus'] != $outletTriggerCommandOpposite) {
				file_get_contents("http://".$moduleInfoReturn['moduleAddress']."/".$row['portNumber']."/".$outletTriggerCommandOpposite);
			}
		}

	} 	
}
?>
