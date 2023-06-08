<?php
$con = mysqli_connect("localhost","root",$bdd,"notehub");
		// Check connection
		if (mysqli_connect_errno()) {
			die("Erreur BDD : " . mysqli_connect_error());
		}
?>