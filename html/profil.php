<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header("Location: login.php?page=" . $_SERVER['REQUEST_URI']);
    exit();
}

include '../include/config.php';
include '../include/functions.php';

$username = $_SESSION['username'];
$password = $_SESSION['password'];

$data = $_SESSION['userdata']
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title><?php echo $title ?></title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.min.css">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  </head>
  <body>
    <nav>
	<?php nav($pages);?>
    </nav>
    <h1><?php echo $_SESSION['username']; echo $_SESSION['userdata']['verified'] == 1 ? '<img src="https://upload.wikimedia.org/wikipedia/commons/e/e4/Twitter_Verified_Badge.svg" height="50px" style="margin-left: 20px;"/>' : ""?></h1>
    <form action="addcas.php" method="post">
      <input type="text" name="usercas" value="<?php echo isset($_SESSION['usercas']) ? $_SESSION['usercas'] : "";?>" placeholder="Identifiant CAS" style="grid-column: 1 / 3; grid-row: 1"></input>
      <input type="password" name="passcas" value="<?php echo isset($_SESSION['passcas']) ? $_SESSION['passcas'] : "";?>" placeholder="Mot de passe CAS" style="grid-column: 1 / 3; grid-row: 2"></input>
      <input type="submit" name="submit" value="Valider" style="grid-column: 2; grid-row: 3">
    </form>
    <p style="text-align: left;">
    <?php 
      //foreach($_SESSION['userdata'] as $key=>$value) {
      //  echo $key . " => " . $value . "<br>";
      //}
    ?>
    </p>
    <footer><?php footer() ?></footer>
  </body>
  <script src="main.js"></script>
  <script>colormode(<?php echo $_SESSION['colormode']?>)</script>
</html>
