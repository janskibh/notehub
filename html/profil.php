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

if (isset($_POST['groupe']) && !empty($_POST['groupe'])) {
  if(mysqli_query($con, "UPDATE utilisateurs SET groupe = " . $_POST['groupe'] . " WHERE ID = '" . $_SESSION['userdata']['ID'] ."'" ) != false) {
    $_SESSION['userdata']['groupe'] = $_POST['groupe'];
		$erreur = "Groupe modifié";
	} else {
		$erreur = "Erreur : " . mysqli_error($con);
	}
}

if (isset($_POST['ppurl'])) {
  if(mysqli_query($con, "UPDATE utilisateurs SET pp_url = '" . $_POST['ppurl'] . "' WHERE ID = '" . $_SESSION['userdata']['ID'] ."'" ) != false) {
    $_SESSION['userdata']['pp_url'] = $_POST['ppurl'];
		$erreur = "PP modifiée";
	} else {
		$erreur = "Erreur : " . mysqli_error($con);
	}
}

$username = $_SESSION['username'];
$password = $_SESSION['password'];
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
	<?php nav($pages);?>
    </nav>
    <h1><?php echo "<img src='"; echo $_SESSION['userdata']['pp_url'] != NULL ? $_SESSION['userdata']['pp_url'] : "img/default_pp.jpg"; echo "' height='100px' style='margin-right: 100px; border-radius: 50px'/>"; echo $_SESSION['username']; echo $_SESSION['userdata']['verified'] == 1 ? '<img src="https://upload.wikimedia.org/wikipedia/commons/e/e4/Twitter_Verified_Badge.svg" height="50px" style="margin-left: 20px;"/>' : ""?></h1>
    <?php echo isset($erreur) ? $erreur : "" ?>
    <table>
    <tr><th colspan="2">Identifiants CAS</th></tr>
    <tr><td>
    <form action="addcas.php" method="post">
      <input type="text" name="usercas" value="<?php echo isset($_SESSION['usercas']) ? $_SESSION['usercas'] : "";?>" placeholder="Identifiant CAS" style="grid-column: 1 / 3; grid-row: 1"></input></td><td></td></tr>
      <tr><td><input type="password" name="passcas" value="<?php echo isset($_SESSION['passcas']) ? $_SESSION['passcas'] : "";?>" placeholder="Mot de passe CAS" style="grid-column: 1 / 3; grid-row: 2"></input></td>
      <td><input type="submit" name="submit" value="Valider" style="grid-column: 2; grid-row: 3"></td></tr>
    </form>
    </table>
    <table>
    <tr><th colspan="2">Groupe</th></tr>
    <tr><td>
    <form action="" method="post">
      <select name="groupe">
        <?php 
          $groupes = mysqli_query($con, "SELECT * FROM groupes");
          if (mysqli_num_rows($groupes) > 0) { 
            foreach($groupes as $groupe) { 
              if ($groupe['ID'] == $_SESSION['userdata']['groupe']) {
                echo "<option value='" . $groupe['ID'] . "' selected='selected'>". $groupe['nom'] . "</option>"; 
              } else {
                echo "<option value='" . $groupe['ID'] . "'>". $groupe['nom'] . "</option>"; 
              }
              
            }
          }
        ?>
      </select>
      </td><td><input type="submit" value="Valider"></input></td></tr>
    </form>
    </table>

    <table>
    <tr><th colspan="2">Photo de profil</th></tr>
    <tr><td>
    <form action="" method="post">
      <input type="text" value=<?php echo '"'; echo $_SESSION['userdata']['pp_url'] != NULL ? $_SESSION['userdata']['pp_url'] : ''; echo '"'?> placeholder="URL de l'image" name="ppurl"></input>
      </td><td><input type="submit" value="Valider"></input>
    </form></td></tr>
    </table>
    <footer><?php footer() ?></footer>
  </body>
  <script src="main.js"></script>
  <script>colormode(<?php echo $_SESSION['colormode']?>)</script>
</html>
