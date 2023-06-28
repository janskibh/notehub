<?php
session_start();
if (isset($_SESSION['status'])) {
	$loggedin = 1;
}
include '../include/config.php';
include '../include/functions.php';
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>Politique des données</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.min.css">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  </head>
  <body>
    <?php
	if ($loggedin) {
		echo "<nav>";
		nav($_SESSION['config']);
		echo "</nav>";
	}
    ?>
    <h1>utilisation des données</h1>
    <p style="text-align: left;">Toutes les données sensibles (MDP utilisateur, identifiants CAS) sont chiffrées dans la Base de données. Si vous voulez récupérer vos données : <a href="mailto:club@e59.fr">club@e59.fr</a></p>
    <?php
	if ($loggedin) {
		echo "<footer>";
		footer();
		echo "</footer>";
	}
    ?>
  </body>
  <?php
	if ($loggedin) {
		echo "<script src='main.js'></script>";
		echo "<script>colormode(" . $_SESSION['colormode'] .  ")</script>";
	};
  ?>
</html>
