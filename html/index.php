<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
  header("Location: login.php");
  exit();
}

include '../include/config.php';
include '../include/connect.php';
include '../include/functions.php';

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
		<?php nav($pages)?>
		</nav>
		<h1>Bienvenue sur Notehub</h1>
		<?php
		$stmt = $pdo->prepare("SELECT id_pub FROM publications WHERE groupe = :groupe AND type = 2");
		$stmt->bindParam(':groupe', $_SESSION['userdata']['groupe']);
		if(!$stmt->execute()){
			die("Erreur : " . $stmt->errorInfo()[2]);
		}
		$idPubs = $stmt->fetchAll(PDO::FETCH_COLUMN);

		// Récupération des devoirs correspondants aux id_pub
		$annonces = array();

		if (!empty($idPubs)) {
			$placeholders = implode(',', array_fill(0, count($idPubs), '?'));
			//ANNONCES(ID, #IDEMETTEUR, COULEUR, DATE, VISIBILITE, TITRE, MESSAGE)
			$stmt = $pdo->prepare("SELECT a.date as date, a.message as message, a.titre as titre, u.username as emetteur, u.verified as verified, u.pp_url as pp_url, a.couleur as couleur, a.visible as visible FROM annonces a JOIN utilisateurs u ON a.emetteur = u.ID WHERE a.id IN ($placeholders) ORDER BY a.date ASC");
			if(!$stmt->execute($idPubs)){
			die("Erreur : " . $stmt->errorInfo()[2]);
			}
			$annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}

		foreach($annonces as $annonce) {
			if ($annonce['visible']){
				echo '<table style="border: 1px solid ' . $annonce['couleur'] . '">';
				echo '<tr><th>' . $annonce['emetteur'];
				echo $annonce['verified'] ? '<img src="https://upload.wikimedia.org/wikipedia/commons/e/e4/Twitter_Verified_Badge.svg" height="20px" style="margin-left: 5px;"/>' : '';
				echo ' <span style="font-size: 0.7em; opacity: 0.5; float: right;">' . $annonce['date'] . '<span></th></tr>';
				echo '<tr><td>' . $annonce['titre'] . '</td></tr>';
				echo '<tr><td>' . $annonce['message'] . '</td></tr>';
				echo '</table>';
			}
		}
		$pdo = null;
		?>
		<footer><?php footer()?></footer>
  	</body>
	<script src="main.js"></script>
	<script>colormode(<?php echo $_SESSION['colormode']?>)</script>
</html>
