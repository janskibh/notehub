<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
    header("Location: index.php");
    exit();
}

include '../include/functions.php';

$error = "";

if (!isset($_SESSION['colormode'])) {
	$_SESSION['colormode'] = 0;
}

if (!isset($_SESSION['config'])) {
	$config_location = "../config/notehub.json";
	$config_file = fopen($config_location, "r") or die("Config Error");
	$config = json_decode(fread($config_file,filesize($config_location)));
	fclose($config_file);
	$_SESSION['config'] = $config;
} else {
	$config = $_SESSION['config'];
}

include '../include/connect.php';

if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['submit'])) {
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
	$password2 = htmlspecialchars($_POST['password2'], ENT_QUOTES, 'UTF-8');
    if (is_null($username) || is_null($password) || $_POST['submit'] != "valider") {
        $error =  "Les champs ne doivent pas être vides";
	} else if (strlen($username) > 30){
		$error = "Nom d'utilisateur trop long";
	} else if ($password != $password2){
		$error = "Les mots de passe ne correspondent pas";
    } else {
		$check = mysqli_query($con, "SELECT * FROM utilisateurs WHERE username = '" . $username . "'");
		if (mysqli_num_rows($check) == 0) {
			mysqli_query($con, "INSERT INTO utilisateurs (username, password, verified, admin) VALUES ('" . $username . "', '" . md5($password) . "', 0, 0)");

			$now = getdate();
			$log_data = "C => " . sprintf("%02d", $now['mday']) . "/" . sprintf("%02d", $now['mon']) . "/" . $now['year'] . " " . sprintf("%02d", $now['hours']) . ":" . sprintf("%02d", $now['minutes']) . ":" . sprintf("%02d", $now['seconds']) . " -> " . $username . " registered from " . $_SERVER['REMOTE_ADDR'] . "\n";
			addlog($log_data);

			$_SESSION['password'] = $password;
			$_SESSION['username'] = $username;

			header("Location: login.php");
			exit();
		} else {
			$error = "Le nom d'utilisateur existe déja";
		}

	}
}
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title><?php echo $config->title?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.min.css">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <meta property="og:image" content="https://notehub.e59.fr/img/notehub.png"/>
    <meta property="og:description" content=<?php echo "'$config->description'";?>/>
    <meta property="og:url" content="https://notehub.e59.fr/"/>
    <meta property="og:title" content=<?php echo "'$config->title'";?>/>
    <meta name="theme-color" data-react-helmet="true" content="#000000"/>
</head>
  <body>
    <h1>Créer un compte</h1>
    <form action="" method="post">
		<?php echo $error; ?>
        <input type="text" placeholder="Identifiant" name="username" style="grid-column: 1 / 3; grid-row: 1" required>
        <input type="password" placeholder="Mot de passe" name="password" style="grid-column: 1 / 3; grid-row: 2" required>
		<input type="password" placeholder="Confirmer le mot de passe" name="password2" style="grid-column: 1 / 3; grid-row: 3" required>
		<a href="login.php" class="form_link" style="grid-column: 1; grid-row: 4">Connexion</a>
		<input type="submit" value="valider" name="submit" style="grid-column: 2; grid-row: 4">
    </form>
  <footer><?php footer()?></footer>
  </body>
  <script src="main.js"></script>
  <script>colormode(<?php echo $_SESSION['colormode']?>)</script>
</html>
