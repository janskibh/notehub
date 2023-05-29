<?php
$config = $_SESSION['config'];
$con = mysqli_connect("localhost","root",$config->bdd,"notehub");
		// Check connection
		if (mysqli_connect_errno()) {
			die("Erreur BDD : " . mysqli_connect_error());
		}
?>