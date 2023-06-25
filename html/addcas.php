<?php
session_start();
if (!isset($_SESSION['userdata'])) {
    die("Casse toi de là !!");
}

include '../include/config.php';
include '../include/connect.php';

if (isset($_POST['usercas']) && isset($_POST['passcas']) && isset($_POST['submit'])) {
    if (!empty($_POST['usercas']) && !empty($_POST['passcas'])) {

        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $passcaschiffre = openssl_encrypt($_POST['passcas'], 'aes-256-cbc', $_SESSION['password'], 0, $iv);
        $usercaschiffre = openssl_encrypt($_POST['usercas'], 'aes-256-cbc', $_SESSION['password'], 0, $iv);

        $stmt = $pdo->prepare("UPDATE utilisateurs SET usercas = :usercas, passcas = :passcas, iv = :iv, verified = 1 WHERE ID = :id");
        $stmt->bindParam(':usercas', base64_encode($usercaschiffre));
        $stmt->bindParam(':passcas', base64_encode($passcaschiffre));
        $stmt->bindParam(':iv', bin2hex($iv));
        $stmt->bindParam(':id', $_SESSION['userdata']['ID']);
        $stmt->execute();

        $_SESSION['usercas'] = $_POST['usercas'];
        $_SESSION['passcas'] = $_POST['passcas'];
        $_SESSION['userdata']['verified'] = 1;
    } else {
        $stmt = $pdo->prepare("UPDATE utilisateurs SET usercas = '', passcas = '', verified = 0 WHERE ID = :id");
        $stmt->bindParam(':id', $_SESSION['userdata']['ID']);
        $stmt->execute();

        $_SESSION['usercas'] = "";
        $_SESSION['passcas'] = "";
        $_SESSION['userdata']['verified'] = 0;
    }
}

header("Location: profil.php");
?>