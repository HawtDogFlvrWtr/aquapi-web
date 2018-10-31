<?php
# Username Info
session_start();
if (isset($_GET['debug'])) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$calendarDate = date("Y-m-d h:i");
# This "if" must be first so login happens the first time
if (isset($_POST['email']) && isset($_POST['password'])){
  if ($_POST['email'] == $site_settings['username'] ){
    if (md5($_POST['password']) == $site_settings['user_password']) {
      session_start();
      $_SESSION[$sessionId]['email'] = $site_settings['username'];
      $_SESSION[$sessionId]['msgBox'] = "";
      $_SESSION[$sessionId]['limit'] = $site_settings['defaultGraphLimit'];
    }
  }
}
$noLogin = array('login.php', 'guest.php', 'chartData.php', 'singleValue.php');
$needLogin = array('dashboard.php', 'modules.php', 'calendar.php');
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
function correctTZInsert($dateString, $tz) {
  $real_date = new DateTime($dateString, new DateTimeZone($tz));
  $real_date->setTimeZone(new DateTimeZone('UTC'));
  return $real_date->format('y/m/d H:i:s');
}
$graphLimit = explode(",", $site_settings['graphLimit']);

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

# Run for all pages
$lightMenuOverrideList = $conn->query("SELECT * from light_override ORDER BY type");
$pumpMenuOverrideList = $conn->query("SELECT * from pump_override ORDER BY type");
$parameterList = $conn->query("SELECT id, eventName from parameter_types ORDER BY eventName");

# Only run on dashboard.php 
 if (strpos($_SERVER['PHP_SELF'], 'dashboard') !== false || strpos($_SERVER['PHP_SELF'], 'guest') !== false) {
	$singleMetric = $conn->query("SELECT type_id FROM parameter_entries WHERE id IN (SELECT MIN(id) FROM parameter_entries GROUP BY type_id DESC) ORDER BY `parameter_entries`.`type_id` DESC");
	$graphs = $conn->query("SELECT type_id FROM parameter_entries WHERE id IN (SELECT MIN(id) FROM parameter_entries GROUP BY type_id DESC) ORDER BY `parameter_entries`.`type_id` DESC");
	$maintenanceItems = $conn->query("SELECT * FROM tankkeeping_types");
	$maintenanceList = $conn->query("SELECT `type_id`, `timestamp`, `note`, `type`, `icon`, `text-color`  FROM `tankkeeping_entries`, `tankkeeping_types`  WHERE tankkeeping_entries.type_id = tankkeeping_types.id GROUP BY tankkeeping_entries.timestamp DESC");
 }

# Only run on modules.php 
if (strpos($_SERVER['PHP_SELF'], 'modules') !== false) {
	# Updating port configuration
	if (isset($_POST['triggerParam']) && isset($_POST['triggerTest']) && isset($_POST['triggerValue']) && isset($_POST['triggerCommand']) && isset($_POST['outletType']) && isset($_POST['outletNote']) && isset($_POST['outletId'])){
		$triggerParam = $conn->real_escape_string($_POST['triggerParam']);
		$triggerTest = $conn->real_escape_string($_POST['triggerTest']);
		$triggerValue = $conn->real_escape_string($_POST['triggerValue']);
		$triggerCommand = $conn->real_escape_string($_POST['triggerCommand']);
		$outletType = $conn->real_escape_string($_POST['outletType']);
		$outletNote = $conn->real_escape_string($_POST['outletNote']);
		$outletId = $conn->real_escape_string($_POST['outletId']);
		$outletIdSplit = explode("-",$outletId);
		$pushUpdate = $conn->query("UPDATE outlet_entries SET outletTriggerValue = '".$triggerValue."', outletTriggerTest = '".$triggerTest."', outletTriggerCommand = '".$triggerCommand."', outletTriggerParam = '".$triggerParam."', outletNote = '".$outletNote."', outletType= '".$outletType."' WHERE moduleId = '".$outletIdSplit[0]."' AND portNumber = '".$outletIdSplit[1]."'");
		msgBox("Outlet configuration was saved successfully.", "success");
		header("Location: modules.php");
		exit();


	}
	# Changing port status
	$moduleEntries = $conn->query("SELECT module_entries.id, moduleSerial, moduleFirmware, moduleNote, epoch, moduleTypeName, featureCount, moduleAddress, moduleColor from module_entries LEFT JOIN module_types on module_entries.moduleType = module_types.id");
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
			msgBox("Command successful. It may take a moment to register with the site.", "success");	
		} else {
			msgBox("Outlet ".$outlet." on ".$ipAddress['moduleSerial']." failed to turn ".$command." successfully.", "danger");
		}
		header("Location: modules.php");
		exit();
	}
}

# ENTRY ADDITIONS
# Adding a maintenance note
if (isset($_POST['maintenance-note-textarea']) && isset($_POST['maintenance-type']) && isset($_POST['maintenance-date'])) {
  $note = $conn->real_escape_string($_POST['maintenance-note-textarea']);
  $type = $conn->real_escape_string($_POST['maintenance-type']);
  $date = correctTZInsert($conn->real_escape_string($_POST['maintenance-date'].":00"), $site_settings['tz']);
  $insertData = $conn->query("INSERT INTO `tankkeeping_entries` (`id`, `type_id`, `timestamp`, `note`) VALUES (NULL, '".intval($type)."', '".$date."', '".$note."')");
  msgBox("Entry has been added", "success");
  header("Location: dashboard.php");
  exit();
}

# Adding an existing single entry
if (isset($_POST['single-metric-value']) && isset($_POST['single-metric-date']) && isset($_POST['smetric-type'])) {
  foreach ($_POST['smetric-type'] as $selectedOption) {
    $value = $conn->real_escape_string($_POST['single-metric-value']);
    $type = $conn->real_escape_string($selectedOption);
    $date = correctTZInsert($conn->real_escape_string($_POST['single-metric-date'].":00"), $site_settings['tz']);
    $insertData = $conn->query("INSERT INTO `parameter_entries` (`id`, `type_id`, `timestamp`, `value`) VALUES (NULL, '".intval($type)."', '".$date."', '".$value."')");
  }
  msgBox("Entry has been added", "success");
  header("Location: dashboard.php");
  exit();
}
#
?>
