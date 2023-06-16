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


// Requête SQL pour récupérer les devoirs triés par date croissante
$sql = "SELECT d.date as date, d.contenu as contenu, p.nom as nomProf, r.nom as nomRessource FROM devoirs d, profs p, ressources r WHERE d.prof = p.ID AND d.ressource = r.ID ORDER BY date ASC";

// Exécution de la requête
$result = mysqli_query($con, $sql);

// Vérification des résultats de la requête
if (!$result) {
    die('Erreur lors de l\'exécution de la requête : ' . mysqli_error($con));
}

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
while ($row = mysqli_fetch_assoc($result)) {
    echo '<p>Date : ' . $row['date'] . '</p>';
    echo '<p>Contenu : ' . $row['contenu'] . '</p>';
    echo '<p>Prof: ' . $row['nomProf'] . '</p>';
    echo '<p>Ressource: ' . $row['nomRessource'] . '</p>';
    echo '<hr>';
}

// Fermeture de la connexion à la base de données
mysqli_close($con);
?>


    <footer><?php footer()?></footer>
  </body>
  <script src="main.js"></script>
  <script>colormode(<?php echo $_SESSION['colormode']?>)</script>
</html>
