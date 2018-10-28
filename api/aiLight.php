<?php
define('INCLUDE_CHECK',true);
include '../config.php';
include '../functions.php';


function deviceDetails($host) {
	try {
		$response = file_get_contents($host."/identity");
		#var_dump($response);
		#$r_data = json_decode($response);
	} catch (Exception $e)  {
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
	return $response;

}

if (isset($_GET['aiHosts'])) {
	$url = $conn->real_escape_string($_GET['aiHosts']);
	$host = 'http://'.$url.'/api';
	var_dump($host);
	$details = deviceDetails($host);
	var_dump($details);
}
?>
