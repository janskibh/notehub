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

if (isset($_POST['statut-update'])) {
	if (isset($_POST['userid']) && isset($_POST['isadmin'])) {
		$statut = $_POST['isadmin'];
		$erreur = isset($_POST['admin']) ? $_POST['admin'] : "none";
		if (isset($_POST['admin'])) {
			if ($statut == 0) {
				$statut = 1;
			}
		} else {
			if ($statut == 1) {
				$statut = 0;
			}
		}
		$con = mysqli_connect("127.0.0.1","root",$_SESSION['config']->bdd,"notehub");
		// Check connection
		if (mysqli_connect_errno()) {
			die("Erreur BDD : " . mysqli_connect_error());
		}
		mysqli_query($con, "UPDATE utilisateurs SET admin = '" . $statut . "' WHERE ID = " . $_POST['userid']);
		mysqli_close($con);
	} else {
		$erreur = "Erreur mise a jour statut";
	}
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
	<?php echo isset($erreur) ? $erreur : "" ?>
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
	</table><table>
	<tr><th colspan="2">Utilisateurs</th></tr>
	<tr><th>username</th><th>statut</th></tr>
	<?php
		$con = mysqli_connect("127.0.0.1","root",$config->bdd,"notehub");
		// Check connection
		if (mysqli_connect_errno()) {
			die("Erreur BDD : " . mysqli_connect_error());
		}
		$result = mysqli_query($con, "SELECT * FROM utilisateurs");
		if (mysqli_num_rows($result) > 0) {
			foreach ($result as $user) {
				echo "<tr><td>" . $user['username'] . "</td><td><form action='' method='post'><input type='checkbox' name='admin'";
				echo $user['admin'] == 1 ? "checked" : "";
				echo " style='grid-column: 2; grid-row: 1'><label for='admin' style='grid-column: 1; grid-row: 1'>Admin</label><input type='hidden' name='userid' value='" . $user['ID'] . "'><input type='hidden' name='isadmin' value='" . $user['admin'] . "'><input type='submit' name='statut-update' value='Valider' style='grid-column: 2; grid-row: 2'></form></td></tr>";
			}
		}
	?>
	</table>
  <footer><?php footer()?></footer>
  </body>
  <script src="main.js"></script>
  <script>colormode(<?php echo $_SESSION['colormode']?>)</script>
</html>
