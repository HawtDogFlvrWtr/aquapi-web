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
  $query = $conn->query("SELECT id,annoColor FROM parameter_types WHERE eventName='".$check."'");
  $typeID = $query->fetch_array();
  #$returnTriggerValue = $conn->query("SELECT id, value, timestamp FROM outlet_trigger_entries WHERE paramId=".$typeID['id']." AND timestamp >= now() - INTERVAL ".$limit." GROUP BY UNIX_TIMESTAMP(timestamp) DIV ".$div." ORDER BY timestamp ASC");
  if ($div < 300) { # Don't show annotations on graphs over a week.
	  $returnTriggerValue = $conn->query("SELECT id, value, timestamp, paramId FROM outlet_trigger_entries WHERE value = 'on' AND timestamp >= now() - INTERVAL ".$limit." ORDER BY timestamp ASC");
	  #$returnTriggerValue = $conn->query("SELECT id, value, timestamp FROM outlet_trigger_entries WHERE paramId=".$typeID['id']." AND timestamp >= now() - INTERVAL ".$limit." ORDER BY timestamp ASC");
	  $rotate = 0;
	  while ($value = $returnTriggerValue->fetch_array()) {
		$returnTriggerValue2 = $conn->query("SELECT id, value, timestamp, paramId FROM outlet_trigger_entries WHERE value = 'off' AND id > ".$value['id']." AND timestamp >= now() - INTERVAL ".$limit." ORDER BY timestamp ASC LIMIT 1");
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
			$trans = "2";
		}
		$annArr['annotations'][] = array(
			#"drawTime" => "afterDatasetsDraw",
			"drawTime" => "beforeDatasetsDraw",
			#"id" => $value['id'],
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
			#"borderDash" => $annoColor2['typeLine'],
			#"label" => [ "content" => $annoColor2['outletType'], "fontColor" => "#000", "backgroundColor" => $annoColor2['typeColor'], "xPadding" => "6", "fontStyle" => "bold", "fontSize" => "8", "position" => $position, "enabled" => true,],
			#"onMouseover" => [function(e) { var element = this; element.options.borderWidth=7; element.options.label.enabled = true; element.options.label.content = e.type; element.chartInstance.update(); element.chartInstance.chart.canvas.style.cursor = 'pointer'; };],
		);
	  }
  }
  $query = $conn->query("SELECT id,annoColor FROM parameter_types WHERE eventName='".$check."'");
  $typeID = $query->fetch_array();
  $returnValue = $conn->query("SELECT value, timestamp FROM parameter_entries WHERE type_id=".$typeID['id']." AND timestamp >= now() - INTERVAL ".$limit." GROUP BY UNIX_TIMESTAMP(timestamp) DIV ".$div." ORDER BY timestamp ASC");
  #$returnValue = mysql_query("SELECT value, timestamp FROM parameter_entries WHERE type_id=".$typeID['id']." AND timestamp >= now() - INTERVAL ".$limit." ORDER BY timestamp ASC");
  while ($value = $returnValue->fetch_array()) {
    $real_date = correctTZ($value['timestamp'], $site_settings['tz']);
    # Correct for temperature
    if ($typeID['id'] == 23) {
      $value['value'] = round($value['value'], 2);
    }
    $arr['jsonarray'][] = array(
	"label" => $real_date,
	"value" => $value['value'],
    );
  }
  $arr['annoList'] = $annoArray;
  $arr['annotations'] =  $annArr['annotations'];
  echo json_encode($arr);
  
} else {
  echo "You're missing something";
}
?>
