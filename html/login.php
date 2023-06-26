<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
    header("Location: index.php");
    exit();
}

$error = "";

if (!isset($_SESSION['colormode'])) {
	$_SESSION['colormode'] = 0;
}

include '../include/config.php';
include '../include/functions.php';
include '../include/connect.php';

if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['submit'])) {
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
    if (empty($username) || empty($password) || $_POST['submit'] != "valider") {
        $error =  "Les champs ne doivent pas être vides";
	} else if (strlen($username) > 30){
		$error = "Nom d'utilisateur trop long";
    } else {
		$stmt = $pdo->query("SELECT * FROM utilisateurs WHERE username = '" . $username . "' AND password = '" . md5($password) . "'");
		if ($stmt->rowCount() > 0) {
			$_SESSION['password'] = $password;
			$_SESSION['username'] = $username;
			foreach($stmt as $user) {
        		foreach ($user as $key => $value) {
					$_SESSION['userdata'][$key] = $value;
				}
			}

			$iv = hex2bin($_SESSION['userdata']['iv']);

			$usercaschiffre = base64_decode($_SESSION['userdata']['usercas']);
			$passcaschiffre = base64_decode($_SESSION['userdata']['passcas']);

			if (isset($iv) && isset($usercaschiffre) && isset($passcaschiffre)) {
				$_SESSION['usercas'] = openssl_decrypt($usercaschiffre, 'aes-256-cbc', $password, 0, $iv);
				$_SESSION['passcas'] = openssl_decrypt($passcaschiffre, 'aes-256-cbc', $password, 0, $iv);
			} else {
				$_SESSION['usercas'] = "";
				$_SESSION['passcas'] = "";
			}

			$now = getdate();
			$log = "C => " . sprintf("%02d", $now['mday']) . "/" . sprintf("%02d", $now['mon']) . "/" . $now['year'] . " " . sprintf("%02d", $now['hours']) . ":" . sprintf("%02d", $now['minutes']) . ":" . sprintf("%02d", $now['seconds']) . " -> " . $username . " logged in from " . $_SERVER['REMOTE_ADDR'] . " with session : " . session_id() . "\n";
			addlog($log, $log_dir);

			$pdo = null;

			if (isset($_GET["page"])) {
				header("Location: " . $_GET["page"]);
				exit();
			} else {
				header("Location: index.php");
				exit();
			}
		} else {
			$now = getdate();
			$log_data = "F => " . sprintf("%02d", $now['mday']) . "/" . sprintf("%02d", $now['mon']) . "/" . $now['year'] . " " . sprintf("%02d", $now['hours']) . ":" . sprintf("%02d", $now['minutes']) . ":" . sprintf("%02d", $now['seconds']) . " -> " . $username . " tried to log in from " . $_SERVER['REMOTE_ADDR'] . " wrong password\n";
			addlog($log_data, $log_dir);
			$error = "Nom d'utilisateur ou mot de passe incorrect";
		}
	}
}
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title><?php echo $title?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.min.css">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <meta property="og:image" content="https://notehub2.e59.fr/img/notehub.png"/>
    <meta property="og:description" content="<?php echo $description;?>"/>
    <meta property="og:url" content="https://notehub2.e59.fr/"/>
    <meta property="og:title" content="<?php echo $title;?>"/>
    <meta name="theme-color" data-react-helmet="true" content="#000000"/>
</head>
  <body>
    <h1>Connexion</h1>
	<?php echo $error; ?>
    <form action="" method="post">
        <input type="text" placeholder="Identifiant" name="username" style="grid-column: 1 / 3; grid-row: 1" required>
        <input type="password" placeholder="Mot de passe" name="password" style="grid-column: 1 / 3; grid-row: 2" required>
		<input type="submit" value="valider" name="submit" style="grid-column: 2; grid-row: 3">
    </form>
	<p>Vous n'avez pas encore de compte ? <a href="register.php" class="form_link" style="grid-column: 1; grid-row: 3">Créer un compte</a></p>
  <footer><?php footer()?></footer>
  </body>
  <script src="main.js"></script>
  <script>colormode(<?php echo $_SESSION['colormode']?>)</script>
</html>