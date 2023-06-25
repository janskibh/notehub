<?php
$dsn = "mysql:host=127.0.0.1;dbname=notehub";
$username = "root";
$options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);

try {
    $pdo = new PDO($dsn, $username, $dbpass, $options);
} catch (PDOException $e) {
    die("Erreur BDD : " . $e->getMessage());
}
?>