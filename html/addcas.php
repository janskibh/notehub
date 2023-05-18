<?php
session_start();
if (!isset($_SESSION['userdata'])) {
    die("Casse toi de la !!");
}
if (isset($_POST['usercas']) && isset($_POST['passcas']) && isset($_POST['submit'])) {
    if (!empty($_POST['usercas']) && !empty($_POST['passcas'])) {
        $con = mysqli_connect("127.0.0.1","root",$_SESSION['config']->bdd,"notehub");
		// Check connection
		if (mysqli_connect_errno()) {
			die("Erreur BDD : " . mysqli_connect_error());
		}
		mysqli_query($con, "UPDATE utilisateurs SET usercas = '" . $_POST['usercas'] . "' WHERE ID = " . $_SESSION['userdata']['ID']);
        mysqli_query($con, "UPDATE utilisateurs SET passcas = '" . $_POST['passcas'] . "' WHERE ID = " . $_SESSION['userdata']['ID']);
        mysqli_close($con);
        header("Location: profil.php");
    }
}
?>