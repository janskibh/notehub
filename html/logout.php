<?php
session_start();
if ($_SESSION['username'] != "22200239") {
	$log_file = fopen($_SESSION['config']->log_dir . "/notehub.log", "a") or die("Log Error");
	$now = getdate();
	$log = "D => " . sprintf("%02d", $now['mday']) . "/" . sprintf("%02d", $now['mon']) . "/" . $now['year'] . " " .sprintf("%02d", $now['hours']) . ":" . sprintf("%02d", $now['minutes']) . ":" . sprintf("%02d", $now['seconds']) . " -> " . $_SESSION['username'] . " disconnected from " . $_SERVER['REMOTE_ADDR'] . "\n"; 
	fwrite($log_file, $log);
	fclose($log_file);
}
session_destroy();
header('Location: login.php');
exit();
?>
