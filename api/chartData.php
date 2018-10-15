<?php
define('INCLUDE_CHECK',true);

include '../config.php';
include '../functions.php';
if (isset($_GET['check'])) {
  if (isset($_GET['limit'])) {
    $limit = $conn->real_escape_string($_GET['limit']);
    if (strpos($limit, '-') !== false) {
      $limit = str_replace('-', ' ', $limit);
    } else {
      $limit = "1 WEEK";
    }
  } else {
    $limit = "1 WEEK";
  }
  if (stripos($limit, 'WEEK')) {
    $div = 3600;
  } elseif (stripos($limit, 'MONTH')) {
    $div = 7200;
  } elseif (stripos($limit, 'YEAR')){
    $div = 1209600;
  } elseif (stripos($limit, 'HOUR')){
    $div = 300;
  } else {
    $div = 1800;
  }
  $arr = array();
  $check = $conn->real_escape_string($_GET['check']);
  $query = $conn->query("SELECT id FROM parameter_types WHERE eventName='".$check."'");
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
	"value" => $value['value']
    );
  }
  echo json_encode($arr);
  
} else {
  echo "You're missing something";
}
?>
