<?php
define('INCLUDE_CHECK',true);
if (isset($_GET['debug'])) {
	define('INCLUDE_CHECK',true);
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}

include '/var/www/html/config.php';
include '/var/www/html/functions.php';

  try {
	# Requesting initial pin
	if ($site_settings['ecobeeAPI'] != "" && $site_settings['ecobeePIN'] == "") {
		$getPin = json_decode(file_get_contents('https://api.ecobee.com/authorize?response_type=ecobeePin&client_id='.$site_settings['ecobeeAPI'].'&scope=smartRead'), true);
		var_dump($getPin);
		if ($getPin['ecobeePin'] != "" && $getPin['code'] != "" ) {
			$conn->query("UPDATE settings SET ecobeePIN = '".$getPin['ecobeePin']."', ecobeeAuthCode = '".$getPin['code']."'");
		}
	}
	# Pin was requested, check if they've entered it.
	if ($site_settings['ecobeeAPI'] != "" && $site_settings['ecobeePIN'] != "" && $site_settings['ecobeeAuthCode'] != "" && $site_settings['ecobeeRefresh'] == "") {
		$data = array('grant_type' => 'ecobeePin', 'code' => $site_settings['ecobeeAuthCode'], 'client_id' => $site_settings['ecobeeAPI']);
		$options = array(
			'http' => array(
				'header' => "Content-type: application/x-www-form-urlencoded\r\n",
				'method' => 'POST',
				'content' => http_build_query($data)
			)
		);
		$context = stream_context_create($options);
		$getAdditionals = json_decode(file_get_contents('https://api.ecobee.com/token', false, $context), true);
		var_dump($getAdditionals);
		if ($getAdditionals['access_token'] != "" && $getAdditionals['token_type'] != "" && $getAdditionals['refresh_token'] != "") {

			$conn->query("UPDATE settings SET ecobeeRefresh = '".$getAdditionals['refresh_token']."', ecobeeAccess = '".$getAdditionals['access_token']."', ecobeeTokenType = '".$getAdditionals['token_type']."'");
		}
	}
	# Get METRIC info
	if ($site_settings['ecobeeRefresh'] != "" && $site_settings['ecobeeAccess'] != "" && $site_settings['ecobeeTokenType'] != "") {
		$options = array(
			'http' => array(
				'header' => "Content-Type: text/json\r\nAuthorization: Bearer ".$site_settings['ecobeeAccess']."\r\n",
			)
		);
		$context = stream_context_create($options);
		$getAdditionals = json_decode(file_get_contents('https://api.ecobee.com/1/thermostat?format=json&body=\{"selection":\{"selectionType":"registered","selectionMatch":"","includeRuntime":true\}\}', false, $context), true);
		var_dump($getAdditionals);
		
	}
  } catch (Exception $e) {
    echo "Waiting on db and apache to be alive";
  }
?>
