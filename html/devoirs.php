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
include '../include/connect.php';

?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title><?php echo $title?></title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet"  href="https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.min.css">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  </head>
  <body>
    <nav>
	<?php nav($pages)?>
    </nav>
    <h1>Liste des devoirs</h1>

<?php
// Affichage des devoirs
$stmt = $pdo->prepare("SELECT id_pub FROM publications WHERE groupe = :groupe AND type = 1");
$stmt->bindParam(':groupe', $_SESSION['userdata']['groupe']);
if(!$stmt->execute()){
  die("Erreur : " . $stmt->errorInfo()[2]);
}
$idPubs = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Récupération des devoirs correspondants aux id_pub
$devoirs = array();

if (!empty($idPubs)) {
    $placeholders = implode(',', array_fill(0, count($idPubs), '?'));

    $stmt = $pdo->prepare("SELECT d.date as date, d.contenu as contenu, p.nom as nomProf, r.nom as nomRessource FROM devoirs d JOIN profs p ON d.prof = p.ID JOIN ressources r ON d.ressource = r.ID WHERE d.id IN ($placeholders) ORDER BY d.date ASC");
    if(!$stmt->execute($idPubs)){
      die("Erreur : " . $stmt->errorInfo()[2]);
    }
    $devoirs = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

foreach($devoirs as $devoir) {
    echo '<table>';
    echo '<tr><th colspan="2">' . $devoir['nomRessource'] . '</th></tr>';
    echo '<tr><td>Date</td><td>' . $devoir['date'] . '</td></tr>';
    echo '<tr><td>Contenu</td><td>' . $devoir['contenu'] . '</td></tr>';
    echo '<tr><td>Prof</td><td>' . $devoir['nomProf'] . '</td></tr>';
    echo '</table>';
}

// Fermeture de la connexion à la base de données
$pdo = null;
?>


    <footer><?php footer()?></footer>
  </body>
  <script src="main.js"></script>
  <script>colormode(<?php echo $_SESSION['colormode']?>)</script>
</html>