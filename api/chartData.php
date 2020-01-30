<?php
define('INCLUDE_CHECK',true);

include '../config.php';
include '../functions.php';
if (isset($_GET['check'])) {
  if (isset($_GET['limit'])) {
    $limit = $conn->real_escape_string($_GET['limit']);
    if (strpos($limit, '-') !== false) {
      $limit = strtoupper(str_replace('-', ' ', $limit));
    } else {
      $limit = "3 MONTH";
    }
  } else {
    $limit = "3 MONTH";
  }
  if (stripos($limit, 'WEEK')) {
    $div = 3600;
  } elseif (stripos($limit, 'MONTH')) {
    $div = 7200;
  } elseif (stripos($limit, 'YEAR')){
    $div = 1209600;
  } elseif (stripos($limit, 'DAY')){
    $div = 300;
  } elseif (stripos($limit, 'HOUR')){
    $div = 60;
  } else {
    $div = 1800;
  }
  $arr = array();
  $annArr = array();
  $annoArray = array();
  $check = $conn->real_escape_string($_GET['check']);
  $query = $conn->query("SELECT id,annoColor,decimals FROM parameter_types WHERE eventName='".$check."'");
  $typeID = $query->fetch_array();
  if ($div < 300) { # Don't show annotations on graphs over a week.
	  $returnTriggerValue = $conn->query("SELECT id, value, timestamp, paramId FROM outlet_trigger_entries WHERE value = 'on' AND timestamp >= now() - INTERVAL ".$limit." ORDER BY timestamp ASC");
	  $rotate = 0;
	  while ($value = $returnTriggerValue->fetch_array()) {
		$returnTriggerValue2 = $conn->query("SELECT id, value, timestamp, paramId FROM outlet_trigger_entries WHERE paramId = ".$value['paramId']." AND value = 'off' AND id > ".$value['id']." AND timestamp >= now() - INTERVAL ".$limit." ORDER BY timestamp ASC LIMIT 1");
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
  $returnValue = $conn->query("SELECT value, timestamp FROM parameter_entries WHERE type_id=".$typeID['id']." AND timestamp >= now() - INTERVAL ".$limit." GROUP BY UNIX_TIMESTAMP(timestamp) DIV ".$div." ORDER BY timestamp ASC");
  while ($value = $returnValue->fetch_array()) {
    $real_date = correctTZ($value['timestamp'], $site_settings['tz']);
    # Correct for temperature
    $value['value'] = round($value['value'], $typeID['decimals']);
    $arr['jsonarray'][] = array(
	"label" => $real_date,
	"value" => $value['value'],
    );
  }
  # Get dosing information
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
  echo json_encode($arr);
  
} else {
  echo "You're missing something";
}
?>
