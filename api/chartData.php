<?php
define('INCLUDE_CHECK',true);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '/var/www/html/config.php';
include '/var/www/html/functions.php';
$_GET['limit'] = $argv[1];
if (isset($_GET['limit'])) {
	$limit = $conn->real_escape_string($_GET['limit']);
	if (strpos($limit, '-') !== false) {
		$limit = strtoupper(str_replace('-', ' ', $limit));
	} else {
		$limit = "3 HOUR";
	}
} else {
	$limit = "3 HOUR";
}
$newDiv = explode("-", $limit);
$newHour = $newDiv[0];
$newLimit = $newDiv[1];
if (stripos($limit, 'WEEK')) {
	$div = 'hour';
} elseif (stripos($limit, 'MONTH')) {
	$div = 'hour';
} elseif (stripos($limit, 'YEAR')){
	$div = 'hour';
} elseif (stripos($limit, 'DAY')){
	if ($newHour == 1) { 
		$div = 'hour';
	} else {
		$div = 'hour';
	}
} elseif (stripos($limit, 'HOUR')){
	$div = 'minute';
} else {
	$div = 'minute';
}
$annArr = array();
$annoArray = array();
$query = $conn->query("SELECT id,annoColor,decimals FROM parameter_types");
while ($typeInfo = $query->fetch_array()) {
	  $arr = [];
	  $typeID = $typeInfo['id'];
	  if (false) { # Don't show annotations on graphs over a week.
		  $returnTriggerValue = $conn->query("SELECT id, value, timestamp, paramId FROM outlet_trigger_entries WHERE value = 'on' AND timestamp >= now() - INTERVAL ".$limit." ORDER BY timestamp ASC");
		  $rotate = 0;
		  while ($value = $returnTriggerValue->fetch_array()) {
			echo("SELECT id, value, timestamp, paramId FROM outlet_trigger_entries WHERE paramId = ".$value['paramId']." AND value = 'off' AND id > ".$value['id']." ORDER BY timestamp ASC LIMIT 1\n");
			$returnTriggerValue2 = $conn->query("SELECT id, value, timestamp, paramId FROM outlet_trigger_entries WHERE paramId = ".$value['paramId']." AND value = 'off' AND id > ".$value['id']." ORDER BY timestamp ASC LIMIT 1");
			$triggerVal2 = $returnTriggerValue2->fetch_array();
			$annoQuery = $conn->query("SELECT outlet_entries.outletType FROM outlet_trigger_entries LEFT JOIN outlet_entries ON outlet_trigger_entries.moduleId = outlet_entries.moduleId AND outlet_trigger_entries.outletId = outlet_entries.portNumber WHERE outlet_trigger_entries.id=".$value['id']);
			$annoColor = $annoQuery->fetch_array();
			$annoQuery2 = $conn->query("SELECT * FROM outlet_types WHERE id = ".$annoColor['outletType']);
			$annoColor2 = $annoQuery2->fetch_array();
			list($r, $g, $b) = sscanf($annoColor2['typeColor'], "#%02x%02x%02x");
			$workedValue = $annoColor2['typeColor'].":".$annoColor2['outletType'].":".$annoColor2['typeIcon'];
			if (!in_array($workedValue, $annoArray)) {
				$annoArray[] = $workedValue;
			}
			$real_date = correctTZ($value['timestamp'], $site_settings['tz']);
			$endValue = correctTZ($triggerVal2['timestamp'], $site_settings['tz']);
			if ($real_date != $endValue) {
				$trans = "0.2";
			} else {
				$trans = "4";
			}
			$annArr['annotations'][] = array(
				"drawTime" => "beforeDatasetsDraw",
				"type" => "box",
				"mode" => "vertical",
				"xScaleID" => "x-axis-0",
				"yScaleID" => "y-axis-0",
				"xMin" => $real_date,
				"xMax" => $endValue,
				"backgroundColor" => "rgba($r, $g, $b,$trans)",
				"value" => $real_date,
				"endValue" => $endValue,
				"borderColor" => "rgba($r, $g, $b,$trans)", 
				"borderWidth" => 1,
			);
		  }
	  }
	  echo("SELECT value, timestamp FROM parameter_entries WHERE type_id=".$typeID." AND timestamp BETWEEN DATE_ADD(NOW(), INTERVAL - $newHour $newLimit) AND NOW() GROUP BY $div(timestamp), day(timestamp) ORDER BY timestamp ASC\n");
	  $returnValue = $conn->query("SELECT value, timestamp FROM parameter_entries WHERE type_id=".$typeID." AND timestamp BETWEEN DATE_ADD(NOW(), INTERVAL - $newHour $newLimit) AND NOW() GROUP BY $div(timestamp), day(timestamp) ORDER BY timestamp ASC");
	  while ($value = $returnValue->fetch_array()) {
	    $real_date = correctTZ($value['timestamp'], $site_settings['tz']);
	    # Correct for temperature
	    $value['value'] = round($value['value'], $typeInfo['decimals']);
	    $arr['jsonarray'][] = array(
		"label" => $real_date,
		"value" => $value['value'],
	    );
	  }
	  # Get dosing information
	  if (array_key_exists('jsonarray', $arr)) {
		  $firstEntry =  correctTZQuery($arr['jsonarray'][0]['label'],$site_settings['tz']);
		  $lastEntry = correctTZQuery(end($arr['jsonarray'])['label'],$site_settings['tz']);
		  $findMaintenance = $conn->query("SELECT timestamp from tankkeeping_entries where type_id = 3 and timestamp >= '$firstEntry' AND timestamp <= '$lastEntry'");
		  while ($value = $findMaintenance->fetch_array()) {
			$real_date = correctTZ($value['timestamp'], $site_settings['tz']);
			$trans = "0.2";
			$doseColor = '#1b1b1b';
			list($r, $g, $b) = sscanf($doseColor, "#%02x%02x%02x");
			$workedValue = $doseColor.":Dosing:mdi-test-tube";
			if (!in_array($workedValue, $annoArray)) {
				$annoArray[] = $workedValue;
			}
			$annArr['annotations'][] = array(
				"drawTime" => "beforeDatasetsDraw",
				"type" => "box",
				"mode" => "vertical",
				"xScaleID" => "x-axis-0",
				"yScaleID" => "y-axis-0",
				"xMin" => $real_date,
				"xMax" => $real_date,
				"backgroundColor" => "rgba($r, $g, $b, $trans)",
				"value" => $real_date,
				"endValue" => $real_date,
				"borderColor" => "rgba($r, $g, $b, $trans)", 
				"borderWidth" => 4,
			);
			
		  }
		  $arr['annoList'] = $annoArray;
		  $arr['annotations'] =  $annArr['annotations'];
		  echo "Loading $typeID\n";
		  echo strlen(json_encode($arr))."\n";
		  echo "Redis key ".$typeID."-".str_replace(" ", "_", $newHour)."-".$newLimit."-".$div."\n";
		  $redis->set($typeID."-".str_replace(" ", "_", $newHour)."-".$newLimit."-".$div, json_encode($arr));
		  echo "Finished Loading $typeID\n";
	  }
} 
?>
