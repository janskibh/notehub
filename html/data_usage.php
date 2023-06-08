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
    <h1>Données Utilisateur</h1>
    <p style="text-align: left;">
	Lorsque vous vous connectez sur notehub.e59.fr, le site utilise votre identifiant et mot de passe UVSQ pour se connecter à bulletins.iut-velizy.uvsq.fr et récupérer les notes ainsi que les données utilisateur renvoyées par Scodoc.<br><br>Vos identifiants UVSQ ainsi que les données renvoyées par bulletins.iut-velizy.uvsq.fr sont stockées dans une session qui se détruit lorsque vous vous déconnectez.<br><br>Pour toute question, veuillez vous adresser à JAN#4701 sur Discord ou envoyer un mail à <a href="mailto:club@e59.fr">club@e59.fr</a>.
    </p>
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
