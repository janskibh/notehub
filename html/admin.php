<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../include/config.php';

session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header("Location: login.php");
    exit();
}
if ($_SESSION['userdata']['admin'] != 1){
	http_response_code(403);
	exit();
}

include '../include/connect.php';

if (isset($_POST['popadmin']) && isset($_POST['adminid']) && !empty($_POST['adminid'])) {
	mysqli_query($con, "UPDATE utilisateurs SET admin = 0 WHERE ID = " . $_POST['adminid']);
	$erreur = "Utilisateur retiré des admins";
}

if (isset($_POST['addadmin']) && isset($_POST['username']) && !empty($_POST['username'])) {
	mysqli_query($con, "UPDATE utilisateurs SET admin = 1 WHERE username = '" . $_POST['username'] ."'");
	if(mysqli_affected_rows($con) > 0 ) {
		$erreur = $_POST['username'] . " a rejoint le groupe des admins";
	} else {
		$erreur = "Aucun admin ajouté";
	}
}

if (isset($_POST['popuser']) && isset($_POST['userid']) && !empty($_POST['userid'])) {
	mysqli_query($con, "DELETE FROM utilisateurs WHERE ID = " . $_POST['userid']);
	$erreur = "Utilisateur supprimé";
}

if (isset($_POST['prof']) && isset($_POST['ressource']) && isset($_POST['contenu']) && isset($_POST['date']) && isset($_POST['submit'])) {
	if(mysqli_query($con, "INSERT INTO devoirs (`prof`, `contenu`, `ressource`, `date`) VALUES ('" . $_POST['prof'] . "','" . $_POST['contenu'] . "','" . $_POST['ressource'] . "', '" . $_POST['date'] . "')" )) {
		$erreur = "Devoir ajouté";
		$log = "A => " . sprintf("%02d", $now['mday']) . "/" . sprintf("%02d", $now['mon']) . "/" . $now['year'] . " " . sprintf("%02d", $now['hours']) . ":" . sprintf("%02d", $now['minutes']) . ":" . sprintf("%02d", $now['seconds']) . " -> " . $_SESSION['username'] . " added a homework (" . $_POST['ressource'] . ")\n";
		addlog();
	} else {
		$erreur = "Erreur : " . mysqli_error($con);
	}
}

include '../include/functions.php';
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title><?php echo $title?></title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.min.css">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
	table td, table th {
		width: 0;
	}
    </style>
  </head>
  <body>
    <nav>
	<?php nav($pages);?>
    </nav>
    <h1>Admin</h1>
	<?php echo isset($erreur) ? $erreur : "" ?>
	<table>
	<tr><th>Active Sessions</th></tr>
	<?php
		foreach (array_slice(scandir(ini_get("session.save_path")), 2) as $session_name) {
			echo "<tr><td>" . $session_name . "</td></tr>";
		}
	?>
	</table>
	<table style="width: 60%">
	<tr><th>Logs</th></tr>
    	<?php
		$logs = file($log_dir . "/notehub.log", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		$logs_lines = array_slice($logs, -10);
		for (end($logs_lines); key($logs_lines)!==null; prev($logs_lines)) {
			echo "<tr><td>" . current($logs_lines) . "</td></tr>";
		};
	?>
	</table>
	<table>
		<tr><th colspan="2">Gestion des utilisateurs</th></tr>
		<tr><th>Utilisateurs</th><th></th></tr>
		<?php
			$result = mysqli_query($con, "SELECT * FROM utilisateurs WHERE admin = 0");
			if (mysqli_num_rows($result) > 0) {
				foreach ($result as $user) {
					echo "<tr><form action='' method='post'><td>" . $user['username'] . "</td><td><input type='submit' name='popuser' value='supprimer'><input type='hidden' name='userid' value='" . $user['ID'] . "'</td></form></tr>";
				}
			}
		?>
	</table>
	<table>
		<tr><th colspan="2">Gestion des admins</th></tr>
		<tr><th>Admins</th><th></th></tr>
		<?php
			$result = mysqli_query($con, "SELECT * FROM utilisateurs WHERE admin = 1");
			if (mysqli_num_rows($result) > 0) {
				foreach ($result as $user) {
					echo "<tr><form action='' method='post'><td>" . $user['username'] . "</td>";
					if ($user['username'] != $_SESSION['username']) {
						echo "<td><input type='submit' name='popadmin' value='retirer'><input type='hidden' name='adminid' value='" . $user['ID'] . "'</td>";
					} else {
						echo "<td><input type='submit' name='popadmin' value='Cet utilisateur' disabled ></td>";
					}
					echo "</form></tr>";
				}
			}
		?>
		<tr><th>Ajouter un admin</th><th></th></tr>
		<tr><form action="" method="post"><td><input type='text' name='username' placeholder='username' style='font-size: 20px;'></td><td><input type='submit' name='addadmin' value='ajouter'></td></form></tr>
	</table>

	<table>
	<form action="" method="post">
		<?php
			$profs = mysqli_query($con, "SELECT * FROM profs");
			$ressources = mysqli_query($con, "SELECT * FROM ressources");
		?>
		<tr><th colspan="3">Devoirs</th></tr>
		<tr><th>
			<select name="prof">
			<?php if (mysqli_num_rows($profs) > 0) { foreach($profs as $prof) { echo "<option value='" . $prof['ID'] . "'>" . $prof['nom'] . "</option>"; }}?>
			</select>
		</th></tr>
		<tr><th>
			<select name="ressource">
			<?php if (mysqli_num_rows($ressources) > 0) { foreach($ressources as $ressource) { echo "<option value='" . $ressource['ID'] . "'>R " . $ressource['code'] . " - " . $ressource['nom'] . "</option>"; }}?>
			</select>
		</th></tr>
		<tr><th><input type="date" name="date"></th></tr>
		<tr><th><input type="text" name="contenu" placeholder="contenu"/></th></tr>
		<tr><th><input type="submit" name="submit" value="valider"></th></tr>
	</form>
	</table>
  <footer><?php footer()?></footer>
  </body>
  <script src="main.js"></script>
  <script>colormode(<?php echo $_SESSION['colormode']?>)</script>
</html>
<?php mysqli_close($con); ?>
