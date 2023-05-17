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
		$con = mysqli_connect("127.0.0.1","root","","notehub");
		// Check connection
		if (!mysqli_connect_errno()) {
			mysqli_query($con, "INSERT INTO utilisateurs (username, password, statut) VALUES ('" . $username . "', '" . md5($password) . "', 10)");

			$now = getdate();
			$log_data = "C => " . sprintf("%02d", $now['mday']) . "/" . sprintf("%02d", $now['mon']) . "/" . $now['year'] . " " . sprintf("%02d", $now['hours']) . ":" . sprintf("%02d", $now['minutes']) . ":" . sprintf("%02d", $now['seconds']) . " -> " . $username . " registered from " . $_SERVER['REMOTE_ADDR'] . "\n";
			addlog($log_data);

			$_SESSION['password'] = $password;
			$_SESSION['username'] = $username;

			header("Location: index.php");
			exit();
		} else {
			$error =  "Erreur connexion à la BDD : " . mysqli_connect_error();
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
   <style>
	input[type="text"],
	input[type="password"] {
    	    background-color: var(--table-bg);
    	    color: var(--text-color);
	    border: 0;
    	    padding: 10px;
    	    margin: 20px;
    	    border-radius: 5px;
    	    font-size: 30px;
    	    outline: none;
	    width: 400px;
	}
	input[type="submit"] {
    	    background-color: var(--table-bg);
    	    color: var(--text-colo2);
    	    border: 0;
    	    padding: 10px 20px;
    	    margin: 0;
	    margin-left: 270px;
	    margin-top: 20px;
    	    border-radius: 5px;
    	    font-size: 30px;
    	    cursor: pointer;
    	    outline: none;
	    width: 150px;
	}
	input[type="submit"]:hover {
	    border-bottom: 1px solid var(--link-hover-bg);
	}
	form {
	    margin: 0 auto;
	    width: 500px;
	    display: block;
            align-items: center;
	}
	@media only screen and (max-device-width: 600px){
		form {
			width: 100%;
			margin: 0;
		}
		input[type="text"],
		input[type="password"] {
			font-size: 2em;
			width: 80%;
			margin: 40px;
		}
		input[type="submit"] {
			font-size: 2em;
			width: 250px;
			margin-left: 542px;
			margin-top: 40px;
		}
	}
   </style>
</head>
  <body>
    <h1>Créer un compte</h1>
    <form action="" method="post">
		<?php echo $error; ?>
        <input type="text" placeholder="Identifiant" name="username" required>
        <input type="password" placeholder="Mot de passe" name="password" required>
		<input type="password" placeholder="Confirmer le mot de passe" name="password2" required>
		<input type="submit" value="valider" name="submit">
    </form>
  <footer><?php footer()?></footer>
  </body>
  <script src="main.js"></script>
  <script>colormode(<?php echo $_SESSION['colormode']?>)</script>
</html>
