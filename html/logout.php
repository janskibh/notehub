<?php
session_start();
include '../include/config.php';
include '../include/functions.php';
if ($_SESSION['username'] != "22200239") {
	$now = getdate();
	$log = "D => " . sprintf("%02d", $now['mday']) . "/" . sprintf("%02d", $now['mon']) . "/" . $now['year'] . " " .sprintf("%02d", $now['hours']) . ":" . sprintf("%02d", $now['minutes']) . ":" . sprintf("%02d", $now['seconds']) . " -> " . $_SESSION['username'] . " disconnected from " . $_SERVER['REMOTE_ADDR'] . "\n"; 
	addlog($log, $log_dir);
}
session_destroy();
header('Location: login.php');
exit();
?>
