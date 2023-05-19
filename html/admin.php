<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header("Location: login.php");
    exit();
}
if ($_SESSION['userdata']['statut'] < 30){
	http_response_code(403);
	exit();
}

$config = $_SESSION['config'];
include '../include/functions.php';
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title><?php echo $config->title?></title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.min.css">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
	table td, table th {
		width: 700px;
	}
    </style>
  </head>
  <body>
    <nav>
	<?php nav($config);?>
    </nav>
    <h1>Admin</h1>
	<table>
	<tr><th>Active Sessions</th></tr>
	<?php
		foreach (array_slice(scandir(ini_get("session.save_path")), 2) as $session_name) {
			echo "<tr><td>" . $session_name . "</td></tr>";
		}
	?>
	</table>
	<table style="width: 60%">
	<tr><th>Logs</th></tr>
    	<?php
		$logs = file($config->log_dir . "/notehub.log", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		$logs_lines = array_slice($logs, -10);
		for (end($logs_lines); key($logs_lines)!==null; prev($logs_lines)) {
			echo "<tr><td>" . current($logs_lines) . "</td></tr>";
		};
	?>
	</table>
  <footer><?php footer()?></footer>
  </body>
  <script src="main.js"></script>
  <script>colormode(<?php echo $_SESSION['colormode']?>)</script>
</html>
