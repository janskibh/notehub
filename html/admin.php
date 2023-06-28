<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../include/config.php';
include '../include/functions.php';

session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header("Location: login.php");
    exit();
}
if ($_SESSION['userdata']['admin'] != 1) {
    http_response_code(403);
    exit();
}

include '../include/connect.php';

if (isset($_POST['submit'])) {
	if ($_POST['submit'] == "devoir"){
		//#####################
		// DEVOIRS
		//#####################
		if (isset($_POST['prof']) && isset($_POST['ressource']) && isset($_POST['contenu']) && isset($_POST['date'])) {
			$stmt = $pdo->prepare("INSERT INTO devoirs (`prof`, `contenu`, `ressource`, `date`) VALUES (:prof, :contenu, :ressource, :date)");
			$stmt->bindParam(':prof', $_POST['prof']);
			$stmt->bindParam(':contenu', $_POST['contenu']);
			$stmt->bindParam(':ressource', $_POST['ressource']);
			$stmt->bindParam(':date', $_POST['date']);
			if ($stmt->execute()) {
		
			// Récupération de l'ID du devoir ajouté précédemment
				$idDevoir = $pdo->lastInsertId();
		
				// Ajout de la publication associée au devoir
				
				foreach($_POST['groupe'] as $groupe) {
					$stmt = $pdo->prepare("INSERT INTO publications (`type`, `id_pub`, `groupe`) VALUES (1, :idpub, :groupe)");
					$stmt->bindParam(':idpub', $idDevoir);
					$stmt->bindParam(':groupe', $groupe);
					if(!$stmt->execute()) {
						die("Erreur SQL" . $stmt->errorInfo()[2]);
					}
				}
		
				$erreur = "Devoir ajouté";
				$now = getdate();
				$log = "A => " . sprintf("%02d", $now['mday']) . "/" . sprintf("%02d", $now['mon']) . "/" . $now['year'] . " " . sprintf("%02d", $now['hours']) . ":" . sprintf("%02d", $now['minutes']) . ":" . sprintf("%02d", $now['seconds']) . " -> " . $_SESSION['username'] . " a ajouté un devoir (ID ressource : " . $_POST['ressource'] . ")\n";
				addlog($log, $log_dir);
			} else {
				$erreur = "Erreur : " . $stmt->errorInfo()[2];
			}
		}
	} else if ($_POST['submit'] == "annonce"){
		//#####################
		// ANNONCES
		//#####################
		if (isset($_POST['message']) && isset($_POST['couleur'])) {
			$stmt = $pdo->prepare("INSERT INTO annonces (`emetteur`, `couleur`, `titre`, `message`, `visible`) VALUES (:emetteur, :couleur, :titre, :message, 1)");
			$stmt->bindParam(':emetteur', $_SESSION['userdata']['ID']);
			$stmt->bindParam(':couleur', $_POST['couleur']);
			$stmt->bindParam(':titre', $_POST['titre']);
			$stmt->bindParam(':message', $_POST['message']);
			if ($stmt->execute()) {
		
			// Récupération de l'ID de l'annonce ajoutée précédemment
				$idAnnonce = $pdo->lastInsertId();
		
				// Ajout de la publication associée à l'annonce
				
				foreach($_POST['groupe'] as $groupe) {
					$stmt = $pdo->prepare("INSERT INTO publications (`type`, `id_pub`, `groupe`) VALUES (2, :idpub, :groupe)");
					$stmt->bindParam(':idpub', $idAnnonce);
					$stmt->bindParam(':groupe', $groupe);
					if(!$stmt->execute()) {
						die("Erreur SQL" . $stmt->errorInfo()[2]);
					}
				}
		
				$erreur = "Annonce publiée";
				$now = getdate();
				$log = "A => " . sprintf("%02d", $now['mday']) . "/" . sprintf("%02d", $now['mon']) . "/" . $now['year'] . " " . sprintf("%02d", $now['hours']) . ":" . sprintf("%02d", $now['minutes']) . ":" . sprintf("%02d", $now['seconds']) . " -> " . $_SESSION['username'] . " a ajouté une annonce (" . $_POST['titre'] . ")\n";
				addlog($log, $log_dir);
			} else {
				$erreur = "Erreur : " . $stmt->errorInfo()[2];
			}
		}
	} else if ($_POST['submit'] == "popadmin") {
		if (isset($_POST['id']) && !empty($_POST['id'])) {
			$stmt = $pdo->prepare("UPDATE utilisateurs SET admin = 0 WHERE ID = :adminid");
			$stmt->bindParam(':adminid', $_POST['id']);
			$stmt->execute();
			$erreur = "Utilisateur retiré des admins";
		}
	} else if ($_POST['submit'] == "addadmin") {
		if (isset($_POST['username']) && !empty($_POST['username'])) {
			$stmt = $pdo->prepare("UPDATE utilisateurs SET admin = 1 WHERE username = :username");
			$stmt->bindParam(':username', $_POST['username']);
			$stmt->execute();
		
			if ($stmt->rowCount() > 0) {
				$erreur = $_POST['username'] . " a rejoint le groupe des admins";
			} else {
				$erreur = "Aucun admin ajouté";
			}
		}
	} else if ($_POST['submit'] == "deluser") {
		if (isset($_POST['id']) && !empty($_POST['id'])) {
			$stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE ID = :userid");
			$stmt->bindParam(':userid', $_POST['id']);
			$stmt->execute();
			$erreur = "Utilisateur supprimé";
		}
	}
}


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
	<tr><th>Sessions</th></tr>
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
			$stmt = $pdo->query("SELECT * FROM utilisateurs WHERE admin = 0");
			if ($stmt->rowCount() > 0) {
				foreach ($stmt as $user) {
					echo "<tr><form action='' method='post'><td>" . $user['username'] . "</td><td><input type='hidden' value='" . $user['ID'] . "' name='id'><button type='submit' name='submit' value='deluser'>Supprimer</button></form></tr>";
				}
			}
		?>
	</table>
	<table>
		<tr><th colspan="2">Gestion des admins</th></tr>
		<tr><th>Admins</th><th></th></tr>
		<?php
			$stmt = $pdo->query("SELECT * FROM utilisateurs WHERE admin = 1");
			if ($stmt->rowCount() > 0) {
				foreach ($stmt as $user) {
					echo "<tr><form action='' method='post'><td>" . $user['username'] . "</td>";
					if ($user['username'] != $_SESSION['username']) {
						echo "<td><input type='hidden' name='id' value='" . $user['ID'] . "'><button type='submit' name='submit' value='popadmin'>Virer</button>";
					} else {
						echo "<td><button type='submit' name='submit' value='none' disabled>Cet utilisateur</button></td>";
					}
					echo "</form></tr>";
				}
			}
		?>
		<tr><th>Ajouter un admin</th><th></th></tr>
		<tr><form action="" method="post"><td><input type='text' name='username' placeholder='username' style='font-size: 20px;'></td><td><button type="submit" name="submit" value="addadmin">Valider</button></td></form></tr>
	</table>

	<table>
	<form action="" method="post">
		<?php
			$profs = $pdo->query("SELECT * FROM profs");
			$ressources = $pdo->query("SELECT * FROM ressources");
			$groupes = $pdo->query("SELECT * FROM groupes")
		?>
		<tr><th colspan="3">Devoirs</th></tr>
		<tr><th>
			<select name="prof">
			<?php if ($profs->rowCount() > 0) { foreach($profs as $prof) { echo "<option value='" . $prof['ID'] . "'>" . $prof['nom'] . "</option>"; }}?>
			</select>
		</th></tr>
		<tr><th>
			<select name="ressource">
			<?php if ($ressources->rowCount() > 0) { foreach($ressources as $ressource) { echo "<option value='" . $ressource['ID'] . "'>R " . $ressource['code'] . " - " . $ressource['nom'] . "</option>"; }}?>
			</select>
		</th></tr>
		<tr><th>
			<select name="groupe[]" multiple>
			<?php if ($groupes->rowCount() > 0) { foreach($groupes as $groupe) { echo "<option value='" . $groupe['ID'] . "'>" . $groupe['nom'] . "</option>"; }}?>
			</select>
		</th></tr>
		<tr><th><input type="date" name="date"></th></tr>
		<tr><th><input type="text" name="contenu" placeholder="contenu"/></th></tr>
		<tr><th><button type="submit" name="submit" value="devoir">Valider</button></th></tr>
	</form>
	</table>

	<table>
	<form action="" method="post">
		<?php
			$groupes = $pdo->query("SELECT * FROM groupes")
		?>
		<tr><th colspan="3">Annonces</th></tr>
		<tr><th>
			<select name="groupe[]" multiple>
			<?php if ($groupes->rowCount() > 0) { foreach($groupes as $groupe) { echo "<option value='" . $groupe['ID'] . "'>" . $groupe['nom'] . "</option>"; }}?>
			</select>
		</th></tr>
		<tr><th><input type="text" name="titre" placeholder="titre"/></th></tr>
		<tr><th><input type="text" name="message" placeholder="message"/></th></tr>
		<tr><th><input type="color" name="couleur"/></th></tr>
		<tr><th><button type="submit" name="submit" value="annonce">Valider</button></th></tr>
	</form>
	</table>
  <footer><?php footer()?></footer>
  </body>
  <script src="main.js"></script>
  <script>colormode(<?php echo $_SESSION['colormode']?>)</script>
</html>
<?php $pdo = null; ?>