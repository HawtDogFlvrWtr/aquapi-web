<?php
date_default_timezone_set($site_settings['tz']);
# Username Info
session_start();
if (isset($_GET['debug'])) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
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
function correctTZgchart($dateString, $tz) {
  $real_date = new DateTime($dateString, new DateTimeZone('UTC'));
  $real_date->setTimeZone(new DateTimeZone($tz));
  $real_date->modify('-1 month');
  return $real_date->format('Y,m,d,H,i,s');
}
function correctTZInsert($dateString, $tz) {
  $real_date = new DateTime($dateString, new DateTimeZone($tz));
  $real_date->setTimeZone(new DateTimeZone('UTC'));
  return $real_date->format('y/m/d H:i:s');
}
function correctTZQuery($dateString, $tz) {
  $real_date = new DateTime($dateString, new DateTimeZone($tz));
  $real_date->setTimeZone(new DateTimeZone('UTC'));
  return $real_date->format('Y-m-d H:i');
}
function correctTZEpoch($dateString, $tz) {
  $real_date = new DateTime($dateString, new DateTimeZone('UTC'));
  $real_date->setTimeZone(new DateTimeZone('UTC'));
  return $real_date->format('U');
}
function correctAttTZ($dateString, $tz) {
  $real_date = new DateTime($dateString, new DateTimeZone('UTC'));
  $real_date->setTimeZone(new DateTimeZone($tz));
  return $real_date->format('Y-m-d H:i');
}
$calendarDate = correctAttTZ(date('Y-m-d h:m'), $site_settings['tz']);
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
$parameterList = $conn->query("SELECT id, eventName from parameter_types WHERE dontGraph = 0 ORDER BY eventName");
$parameterListModule = $conn->query("SELECT id, eventName from parameter_types ORDER BY eventName");

# Only run on dashboard.php 
 if (strpos($_SERVER['PHP_SELF'], 'dashboard') !== false || strpos($_SERVER['PHP_SELF'], 'guest') !== false) {
	$singleMetric = $conn->query("SELECT parameter_entries.type_id, parameter_types.max, parameter_types.lineColor, parameter_types.eventName FROM parameter_entries LEFT JOIN parameter_types ON parameter_entries.type_id = parameter_types.id WHERE parameter_types.dontGraph = 0 AND parameter_entries.id IN (SELECT MIN(parameter_entries.id) FROM parameter_entries GROUP BY parameter_entries.type_id DESC) ORDER BY parameter_entries.type_id DESC");
	$graphs = $conn->query("SELECT parameter_entries.type_id, parameter_types.lineColor, parameter_types.eventName FROM parameter_entries LEFT JOIN parameter_types ON parameter_entries.type_id = parameter_types.id WHERE parameter_types.dontGraph = 0 AND parameter_entries.id IN (SELECT MIN(parameter_entries.id) FROM parameter_entries GROUP BY parameter_entries.type_id DESC) ORDER BY parameter_entries.type_id DESC");
	$maintenanceItems = $conn->query("SELECT * FROM tankkeeping_types");
	$maintenanceList = $conn->query("SELECT tankkeeping_entries.id, type_id, timestamp, note, type, icon, word_color FROM tankkeeping_entries, tankkeeping_types WHERE tankkeeping_entries.type_id = tankkeeping_types.id GROUP BY tankkeeping_entries.timestamp DESC");
 }

# Only run on modules.php 
if (strpos($_SERVER['PHP_SELF'], 'modules') !== false) {
	# Updating port configuration
	if (isset($_POST['triggerParam']) && isset($_POST['triggerTest']) && isset($_POST['triggerValue']) && isset($_POST['triggerCommand']) && isset($_POST['outletType']) && isset($_POST['outletNote']) && isset($_POST['outletId'])){
		$triggerParam = $conn->real_escape_string($_POST['triggerParam']);
		$triggerTest = $conn->real_escape_string($_POST['triggerTest']);
		$triggerValue = $conn->real_escape_string($_POST['triggerValue']);
		$triggerCommand = $conn->real_escape_string($_POST['triggerCommand']);
		if ($triggerParam == '') {
			$triggerParam = 'NULL';
			$triggerTest = '';
			$triggerValue = 'NULL';
			$triggerCommand = '';
		}
		$cleanCommand = $conn->real_escape_string($_POST['cleanCommand']);
		if ($cleanCommand == '') {
			$cleanCommand = 0;
		}
		$feedCommand = $conn->real_escape_string($_POST['feedCommand']);
		if ($feedCommand == '') {
			$feedCommand = 0;
		}
		$AOCommand = $conn->real_escape_string($_POST['AOCommand']);
		$outletType = $conn->real_escape_string($_POST['outletType']);
		if ($outletType == '') {
			$outletType = '0';
		}
		$outletNote = $conn->real_escape_string($_POST['outletNote']);
		$outletId = $conn->real_escape_string($_POST['outletId']);
		$onTime = $conn->real_escape_string($_POST['on-time']);
		$offTime = $conn->real_escape_string($_POST['off-time']);
		if ($AOCommand == '') {
			$AOCommand = '0';
		}
		if ($onTime == '') {
			$onTime = 'NULL';
			$offTime = 'NULL';
		} else {
			$onTime = '"'.$onTime.'"';
			$offTime = '"'.$offTime.'"';
			$AOCommand = '0'; #Disable always on
		}
		$outletIdSplit = explode("-",$outletId);
		$connection = "UPDATE outlet_entries SET on_time = $onTime, off_time = $offTime,  outletTriggerValue = $triggerValue, outletTriggerTest = '$triggerTest', outletTriggerCommand = '$triggerCommand', offAtCleaning = $cleanCommand, offAtFeeding = $feedCommand, alwaysOn = '".$AOCommand."', outletTriggerParam = $triggerParam, outletNote = '".$outletNote."', outletType= $outletType WHERE moduleId = $outletIdSplit[0] AND portNumber = $outletIdSplit[1]";
		if ($conn->query("UPDATE outlet_entries SET on_time = $onTime, off_time = $offTime,  outletTriggerValue = $triggerValue, outletTriggerTest = '$triggerTest', outletTriggerCommand = '$triggerCommand', offAtCleaning = $cleanCommand, offAtFeeding = $feedCommand, alwaysOn = '".$AOCommand."', outletTriggerParam = $triggerParam, outletNote = '".$outletNote."', outletType= $outletType WHERE moduleId = $outletIdSplit[0] AND portNumber = $outletIdSplit[1]")) {
			msgBox("Outlet configuration was saved successfully. $connection", "success");
			header("Location: modules.php");
			exit();
		} else {
			msgBox("Failed to save outlet configuration. $connection", "danger");
			header("Location: modules.php");
			exit();
		}



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
  if ($type == 6) { // Start feeding if we enter a feeding entry.
  	$insertFeedingParamData = $conn->query("INSERT INTO `parameter_entries` (`type_id`, `value`) VALUES (26, 1)");
  }
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
    # Convert PhosP to Phos
    if ($type == 14) {
	    $newValue = $value * 3.066 / 1000;
    	    $insertData = $conn->query("INSERT INTO `parameter_entries` (`id`, `type_id`, `timestamp`, `value`) VALUES (NULL, 10, '".$date."', '".$newValue."')");
    }
    $insertData = $conn->query("INSERT INTO `parameter_entries` (`id`, `type_id`, `timestamp`, `value`) VALUES (NULL, '".intval($type)."', '".$date."', '".$value."')");
  }
  msgBox("Entry has been added", "success");
  header("Location: dashboard.php");
  exit();
}

# Updating the settings
if ( isset($_POST['feed']) && isset($_POST['clean']) && isset($_POST['graph']) && isset($_POST['tz']) && isset($_POST['username']) && isset($_POST['password']) ) {
		$feed = $conn->real_escape_string($_POST['feed']) * 60;
		$clean = $conn->real_escape_string($_POST['clean']) * 60 ;
		$graph = $conn->real_escape_string($_POST['graph']);
		$newTZ = $conn->real_escape_string($_POST['tz']);
		$username = $conn->real_escape_string($_POST['username']);
		$password = md5($conn->real_escape_string($_POST['password']));
		$conn->query("UPDATE settings SET tz = '".$newTZ."', defaultGraphLimit = '".$graph."', cleanTime = '".$clean."', username = '".$username."', user_password = '".$password."', feedTime = '".$feed."'");
		msgBox("Site settings have been updated successfully.", "success");	
 		header("Location: ".basename($_SERVER['PHP_SELF']));
  		exit();
}

?>
