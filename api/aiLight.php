<?php
define('INCLUDE_CHECK',true);
include '../config.php';
include '../functions.php';


function deviceDetails($host) {
	try {
		$response = file_get_contents("http://192.168.1.242/api/identity");
		#$response = file_get_contents($host."/identity");
		#var_dump($response);
		#$r_data = json_decode($response);
	} catch (Exception $e)  {
		echo 'Caught exception: ', $e->getMessage(), "\n";
	}
	return $response;

}
$options = stream_context_create(array('http'=>
	array(
		'timeout' => 1 
	)
));
	$response = file_get_contents('http://192.168.1.242/api/colors', false, $options);
	echo $response;
?>
