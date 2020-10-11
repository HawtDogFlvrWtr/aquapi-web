<?php
define('INCLUDE_CHECK',true);

include '../config.php';
include '../functions.php';
if (isset($_GET['start']) && isset($_GET['end'])) {
  $start = $conn->real_escape_string($_GET['start']);
  $end = $conn->real_escape_string($_GET['end']);
  $arr = array();
  $returnCal = $conn->query("SELECT tankkeeping_entries.timestamp, tankkeeping_entries.note, tankkeeping_types.`cal_color`, tankkeeping_types.type FROM `tankkeeping_entries` INNER JOIN tankkeeping_types ON tankkeeping_entries.type_id = tankkeeping_types.id WHERE tankkeeping_entries.timestamp >= '".$start."' AND tankkeeping_entries.timestamp <= '".$end."'");
  #echo("SELECT tankkeeping_entries.timestamp, tankkeeping_entries.note, tankkeeping_types.`text-color` FROM `tankkeeping_entries` INNER JOIN tankkeeping_types ON tankkeeping_entries.type_id  = tankkeeping_types.id WHERE tankkeeping_entries.timestamp >= '".$start."' AND tankkeeping_entries.timestamp <= '".$end."'");
  while ($value = $returnCal->fetch_array()) {
	  $timestamp = correctTZ($value['timestamp'], $site_settings['tz']);
	  $arr[] = array(
		"title" => str_replace("_", " ", $value['type']),
		"note" => $value['note'],
		"start" => $timestamp,
		"end" => $timestamp,
		"color" => $value['cal_color']
	  ); 
  }
  echo json_encode($arr);
} else {
  echo "You're missing something";
}
?>
