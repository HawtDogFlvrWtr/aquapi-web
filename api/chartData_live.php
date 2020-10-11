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
  $arr = array();
  $annArr = array();
  $annoArray = array();
  $check = $conn->real_escape_string($_GET['check']);
  $query = $conn->query("SELECT id,annoColor,decimals FROM parameter_types WHERE eventName='".$check."'");
  $typeID = $query->fetch_array();
  $chartData = $redis->get($typeID['id']."-".str_replace(" ", "_", $newHour)."-".$newLimit."-".$div);
  if (!$chartData) {
	  $chartData = '[]';
  } else {
	  echo $chartData;
  }
  
} else {
  echo "You're missing something";
}
?>
