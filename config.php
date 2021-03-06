<?php

if(!defined('INCLUDE_CHECK')) die('You are not allowed to execute this file directly');

/* Database config */
$redis = new Redis();
$redis->connect('localhost', 6379);
$db_host		= 'localhost';
$db_user		= 'aquapi';
$db_pass		= 'eUIw77DpznwJxNYE';
$db_database		= 'aquapi'; 

/* End config */



$conn = new mysqli($db_host,$db_user,$db_pass, $db_database) or die('Unable to establish a DB connection');

$conn->query("SET names UTF8");
$conn->query("SET time_zone='+00:00';");  # Use the system settings that mysql is using

// Get Settings Data
$setSql = "
          SELECT
                dashboard_update,
                tz,
		graphLimit,
		defaultGraphLimit,
		performAction,
		pumpStatus,
		lightStatus,
		username,
		user_password,
		sessionId,
		light_override,
		pump_override,
		tempScale,
		feedTime,
		cleanTime,
		version
          FROM
                settings
";
$result = $conn->query($setSql);
$site_settings = $result->fetch_assoc() or die('-99'.mysqli_error());
date_default_timezone_set($site_settings['tz']);
$sessionId = $site_settings['sessionId'];
$currentPage = end(explode("/", $_SERVER['REQUEST_URI']));
?>
