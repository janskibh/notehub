<?php
session_start();
if (!isset($_SESSION['colormode']) || !isset($_GET['source'])) {
	http_response_code(403);
	exit();
}
if (!isset($_GET['mode'])) {
	header("Location: " . $_GET['source']);
	exit();
}
if (in_array($_GET['mode'], [0,1,2])){
	$_SESSION['colormode'] = $_GET['mode'];
	header("Location: " . $_GET['source']);
} else {
	http_response_code(403);
	exit();
}
?>
