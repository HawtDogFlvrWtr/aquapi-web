<?php
define('INCLUDE_CHECK',true);

include '../config.php';
include '../functions.php';
$devices = array('light','pump','weather');
if (isset($_GET['check'])) {
  $check = $conn->real_escape_string($_GET['check']);
  $returnValueQuery = $conn->query("SELECT parameter_entries.value, parameter_entries.timestamp, parameter_types.id, parameter_types.decimals, parameter_types.step, parameter_types.eventName FROM parameter_entries INNER JOIN parameter_types ON parameter_types.id=parameter_entries.type_id WHERE eventName='".$check."' ORDER BY timestamp DESC LIMIT 1");
  $returnValue = $returnValueQuery->fetch_array();
  $real_date = correctTZ($returnValue['timestamp'], $site_settings['tz']);
  # Correct for temperature
  if ($returnValue['id'] == 23) {
    $returnValue['value'] = round($returnValue['value'], 1);
  }
  echo $returnValue['value'].",".$real_date.",".$returnValue['decimals'].",".$returnValue['step'];
} elseif(isset($_GET['devices'])) {
  if (isset($_GET['devices']) && in_array($_GET['devices'], $devices)) {
    $newDeviceName = $_GET['devices']."Status";
    $device = $conn->real_escape_string($newDeviceName);
    echo $site_settings[$device];
  }
} else {
  echo "You're missing something";
}
?>
