<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
  header("Location: login.php");
  exit();
}

$config = $_SESSION['config'];
include 'functions.php';

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
		<?php nav($config)?>
		</nav>
		<h1>Bienvenue sur Notehub</h1>
		<p>Le site est en construction mais les pages <a href="notes.php?sem_id=0">Notes</a> et <a href="profil.php" >Profil</a> sont accessibles.<br><br>
		En cas de bug ou d'erreurs, veuillez les signaler à <b>JAN#4701</b> sur Discord</p>
		<footer><?php footer()?></footer>
  	</body>
	<script src="main.js"></script>
	<script>colormode(<?php echo $_SESSION['colormode']?>)</script>
</html>
