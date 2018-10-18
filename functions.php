<?php
# Username Info
session_start();
# This "if" must be first so login happens the first time
if (isset($_POST['email']) && isset($_POST['password'])){
  if ($_POST['email'] == $site_settings['username'] ){
    if (md5($_POST['password']) == $site_settings['user_password']) {
      session_start();
      $_SESSION[$sessionId]['email'] = $site_settings['username'];
      $_SESSION[$sessionId]['msgBox'] = "";
      $_SESSION[$sessionId]['limit'] = "1-week";
    }
  }
}
$noLogin = array('login.php', 'guest.php', 'chartData.php', 'singleValue.php');
$needLogin = array('dashboard.php');
if (in_array($currentPage, $needLogin)) {
  if (isset($_SESSION[$sessionId]['email'])) {
    if (!in_array($currentPage, $needLogin)) {
      header("Location: dashboard.php");
      exit();
    }
  } else if (!isset($_SESSION[$sessionId]['email']))  {
    header("Location: index.php");
    exit();
  }
}
if (isset($_GET['logout'])) {
  session_destroy();
  unset($_SESSION[$sessionId]['email']);
  unset($_SESSION[$sessionId]['msgBox']);
  msgBox("You've been logged out", "success");
  header("Location: index.php");
  exit();
}
if (isset($_SESSION[$sessionId]['email']) && ($currentPage == 'index.php' || $currentPage == "")) {
  header("Location: dashboard.php");
  exit();
}


# Change timezone
function correctTZ($dateString, $tz) {
  $real_date = new DateTime($dateString, new DateTimeZone('UTC'));
  $real_date->setTimeZone(new DateTimeZone($tz));
  return $real_date->format('m/d/y H:i');
}
$graphLimit = explode(",", $site_settings['graphLimit']);

# Adding a maintenance note
if (isset($_POST['maintenance-note-textarea']) && isset($_POST['maintenance-type'])) {
  $note = $conn->real_escape_string($_POST['maintenance-note-textarea']);
  $type = $conn->real_escape_string($_POST['maintenance-type']);
  $insertData = $conn->query("INSERT INTO `tankkeeping_entries` (`id`, `type_id`, `timestamp`, `note`) VALUES (NULL, '".intval($type)."', CURRENT_TIMESTAMP, '".$note."')");
  msgBox("Entry has been added", "success");
  header("Location: dashboard.php");
  exit();
}

# Adding an existing single entry
if (isset($_POST['single-metric-value']) && isset($_POST['smetric-type'])) {
  foreach ($_POST['smetric-type'] as $selectedOption) {
    $value = $conn->real_escape_string($_POST['single-metric-value']);
    $type = $conn->real_escape_string($selectedOption);
    $insertData = $conn->query("INSERT INTO `parameter_entries` (`id`, `type_id`, `timestamp`, `value`) VALUES (NULL, '".intval($type)."', CURRENT_TIMESTAMP, '".$value."')");
  }
  msgBox("Entry has been added", "success");
  header("Location: dashboard.php");
  exit();
}
$singleMetric = $conn->query("SELECT type_id FROM parameter_entries WHERE id IN (SELECT MIN(id) FROM parameter_entries GROUP BY type_id DESC) ORDER BY `parameter_entries`.`type_id` DESC");
$parameterList = $conn->query("SELECT id, eventName from parameter_types ORDER BY eventName");
$graphs = $conn->query("SELECT type_id FROM parameter_entries WHERE id IN (SELECT MIN(id) FROM parameter_entries GROUP BY type_id DESC) ORDER BY `parameter_entries`.`type_id` DESC");
$maintenanceItems = $conn->query("SELECT * FROM tankkeeping_types");
$maintenanceList = $conn->query("SELECT `type_id`, `timestamp`, `note`, `type`, `icon`, `text-color`  FROM `tankkeeping_entries`, `tankkeeping_types`  WHERE tankkeeping_entries.type_id = tankkeeping_types.id GROUP BY tankkeeping_entries.timestamp DESC");
$lightMenuOverrideList = $conn->query("SELECT * from light_override ORDER BY type");
$pumpMenuOverrideList = $conn->query("SELECT * from pump_override ORDER BY type");
$moduleEntries = $conn->query("SELECT module_entries.id, moduleSerial, moduleFirmware, moduleNote, epoch, moduleTypeName, featureCount, moduleAddress, moduleColor from module_entries LEFT JOIN module_types on module_entries.moduleType = module_types.id");
#$outletTypes = $conn->query("SELECT * from outlet_types");
#$outletEntries = $conn->query("SELECT * from outlet_entries");
#$outletEntries = $conn->query("SELECT moduleId, outletType, offDuringFeeding, outletStatus, alwaysOn, outletNote, outletTriggerValue, outletTriggerParam, outletTriggerTest, moduleType, moduleSerial, moduleTypeName, moduleNote FROM outlet_entries oe LEFT JOIN module_entries me on oe.moduleId = me.id LEFT JOIN module_types mt on me.moduleType = mt.id");

# Store alets
function msgBoxDisplay() {
  echo $_SESSION[$sessionId]['msgBox'];
  $_SESSION[$sessionId]['msgBox'] = ""; 
}
function msgBox($message = "", $type = "") {
  $_SESSION[$sessionId]['msgBox'] = "<div class='mt-2 alert alert-$type' role='alert'><strong>$message</strong></div>";
}

$getParameterList = $_SERVER['QUERY_STRING'];

if (isset($_SESSION[$sessionId]['email']) && isset($_POST['limit'])) {
  $_SESSION[$sessionId]['limit'] = $conn->real_escape_string($_POST['limit']);
  header("Location: dashboard.php");
  exit();
}
if (isset($_GET['override'])) {
  $override = $conn->real_escape_string($_GET['override']);
  $query = $conn->query("UPDATE settings SET override = '".$override."'");
  header("Location: dashboard.php");
  exit();
}
# Changing port status
if (isset($_GET['outlet-change']))  {
	$outletChange = $conn->real_escape_string($_GET['outlet-change']);
	$splitVal = explode("-", $outletChange);
	$module = $splitVal[0];
	$outlet = $splitVal[1];
	$command = $splitVal[2];
	if ($command == 1) {
		$command = 'on';
	} else {
		$command = 'off';
	}
	$getIP = $conn->query("SELECT moduleSerial, moduleAddress FROM module_entries WHERE id = ".$module);
	$ipAddress = $getIP->fetch_array();
	if (file_get_contents("http://".$ipAddress['moduleAddress']."/".$outlet."/".$command) !== FALSE) {
		msgBox("Outlet ".$outlet." on ".$ipAddress['moduleSerial']." was turned ".$command." successfully.", "success");	
	} else {
		msgBox("Outlet ".$outlet." on ".$ipAddress['moduleSerial']." failed to turn ".$command." successfully.", "danger");
	}
	header("Location: outlets.php");
	exit();
}
?>
