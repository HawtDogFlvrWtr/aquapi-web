<?php

if(!defined('INCLUDE_CHECK')) die('You are not allowed to execute this file directly');


/* Database config */

$db_host		= 'localhost';
$db_user		= 'aquapi';
$db_pass		= 'aquapi';
$db_database		= 'aquapi'; 

/* End config */



$conn = new mysqli($db_host,$db_user,$db_pass, $db_database) or die('Unable to establish a DB connection');

$conn->query("SET names UTF8");

// Get Settings Data
$setSql = "
          SELECT
                woeid,
                python_update,
                make_cloudy,
                log_file,
                on_time,
                off_time,
                dashboard_update,
                tz,
		graphLimit,
		api_key,
		performAction,
		weatherStatus,
		pumpStatus,
		lightStatus,
		username,
		user_password,
		sessionId,
		override,
		tempScale
          FROM
                settings
";
$result = $conn->query($setSql);
$site_settings = $result->fetch_assoc() or die('-99'.mysqli_error());
$sessionId = $site_settings['sessionId'];
$currentPage = end(explode("/", $_SERVER['REQUEST_URI']));
?>
