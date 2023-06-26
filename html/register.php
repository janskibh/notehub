<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../include/config.php';

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
		$checkuser = $pdo->query("SELECT * FROM utilisateurs WHERE username = '" . $username . "'");
		if ($checkuser->rowCount() == 0) {
			$stmt = $pdo->prepare("INSERT INTO utilisateurs (username, password, verified, admin) VALUES (:username, :password, :verified, :admin)");
        	$stmt->bindParam(':username', $username);
        	$stmt->bindParam(':password', md5($password));
        	$stmt->bindParam(':verified', 0);
        	$stmt->bindParam(':admin', 0);
        	if($stmt->execute()) {
				$now = getdate();
				$log = "C => " . sprintf("%02d", $now['mday']) . "/" . sprintf("%02d", $now['mon']) . "/" . $now['year'] . " " . sprintf("%02d", $now['hours']) . ":" . sprintf("%02d", $now['minutes']) . ":" . sprintf("%02d", $now['seconds']) . " -> " . $username . " registered from " . $_SERVER['REMOTE_ADDR'] . "\n";
				addlog($log, $log_dir);

				$_SESSION['password'] = $password;
				$_SESSION['username'] = $username;

				header("Location: logout.php");
				exit();
			} else {
				echo "Erreur : " . $stmt->errorInfo()[2];
			}
			
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
    <title><?php echo $title?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.min.css">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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
