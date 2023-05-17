<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
    header("Location: index.php");
    exit();
}

include 'functions.php';

if (!isset($_SESSION['colormode'])) {
	$_SESSION['colormode'] = 0;
}

if (!isset($_SESSION['config'])) {
	$config_location = "/etc/notehub.json";
	$config_file = fopen($config_location, "r") or die("Config Error");
	$config = json_decode(fread($config_file,filesize($config_location)));
	fclose($config_file);
	$_SESSION['config'] = $config;
} else {
	$config = $_SESSION['config'];
}

if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if (is_null($username) || is_null($password) || $_POST['submit'] != "valider") {
        echo "Les champs ne doivent pas être vides";
    } else if (!preg_match('/^\d{8}$/', $username)) {
        echo "Le nom d'utilisateur doit être un numéro de 8 chiffres";
    } else {
		$auth = authentification($username, $password);
        if ($auth == 1) {
            die("Reponse de scodoc vide");
        } else if ($auth == 2) {
		die("Mauvais identifiant ou mot de passe");
	} else if ($auth == 3) {
		die("Problème de redirection sur scodoc");
	} else if ($auth == 4) {
		die("Erreur 500 Scodoc");
	}
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        $_SESSION['data'] = $auth;

		if (in_array($username, $config->admins)) {
			$_SESSION['status'] = "a";
	    } else {
			$_SESSION['status'] = "e";
	    }
	    if ($username != "22200239") {
	    	$log_file = fopen("$config->log_dir/notehub.log", "a") or die("Log Error");
	    	$now = getdate();
	    	$log = "C => " . sprintf("%02d", $now['mday']) . "/" . sprintf("%02d", $now['mon']) . "/" . $now['year'] . " " . sprintf("%02d", $now['hours']) . ":" . sprintf("%02d", $now['minutes']) . ":" . sprintf("%02d", $now['seconds']) . " -> " . $username . " logged in from " . $_SERVER['REMOTE_ADDR'] . " with session : " . session_id() . "\n";
	    	fwrite($log_file, $log);
	    	fclose($log_file);
	    }
	    if (isset($_GET["page"])) {
		header("Location: " . $_GET["page"]);
		exit();
	    } else {
     		header("Location: index.php");
           	exit();
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
    <h1>Connexion</h1>
    <form action="" method="post">
        <input type="text" placeholder="Identifiant CAS" name="username" required>
        <input type="password" placeholder="Mot de passe" name="password" required>
	<input type="submit" value="valider" name="submit">
    </form>
  <footer><?php footer()?></footer>
  </body>
  <script src="main.js"></script>
  <script>colormode(<?php echo $_SESSION['colormode']?>)</script>
</html>
