<?php
define('INCLUDE_CHECK',true);
include '../config.php';
if (isset($_GET['apikey']) && $_GET['apikey'] == $site_settings['api_key']) {
  echo(json_encode($site_settings));
} else {
  echo("You're missing something");
}

?>
