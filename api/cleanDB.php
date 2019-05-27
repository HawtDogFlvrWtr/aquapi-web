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
  # Clean the graphs beyond 3 months
  $conn->query("DELETE FROM parameter_entries WHERE timestamp < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 90 DAY))");
} catch (Exception $e) {
    echo "Waiting on db and apache to be alive";
}
?>
