<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header("Location: login.php?page=" . $_SERVER['REQUEST_URI']);
    exit();
}

include 'functions.php';

$username = $_SESSION['username'];
$password = $_SESSION['password'];
$config = $_SESSION['config'];

$data = $_SESSION['data'];
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title><?php echo $config->title ?></title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.min.css">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  </head>
  <body>
    <nav>
	<?php nav($config);?>
    </nav>
    <h1>Etudiant</h1>
    <table>
    <tr><th colspan="2">Infos profil</th></tr>
	<?php
  $info_profil = $data[0];
	echo "<tr><td>Date MAJ</td><td>" . $info_profil->relevé->date . "</td></tr>";
	foreach($info_profil->relevé->etudiant as $key => $value) {
	echo "<tr><td>" . $key . "</td><td>" . $value . "</td></tr>";
	}
	?>
    </table>
    <table>
    <tr><th colspan="2">Formation</th></tr>
	<?php
	foreach($info_profil->relevé->formation as $key => $value) {
	echo "<tr><td>" . $key . "</td><td>" . $value . "</td></tr>";
	}
	?>
    </table>
    <footer><?php footer() ?></footer>
  </body>
  <script src="main.js"></script>
  <script>colormode(<?php echo $_SESSION['colormode']?>)</script>
</html>
