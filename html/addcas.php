<?php
session_start();
if (!isset($_SESSION['userdata'])) {
    die("Casse toi de la !!");
}

include '../include/config.php';
include '../include/connect.php';

if (isset($_POST['usercas']) && isset($_POST['passcas']) && isset($_POST['submit'])) {
    if (!empty($_POST['usercas']) && !empty($_POST['passcas'])) {

        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $passcaschiffre = openssl_encrypt($_POST['passcas'], 'aes-256-cbc', $_SESSION['password'], 0, $iv);
        $usercaschiffre = openssl_encrypt($_POST['usercas'], 'aes-256-cbc', $_SESSION['password'], 0, $iv);

		mysqli_query($con, "UPDATE utilisateurs SET usercas = '" . base64_encode($usercaschiffre) . "' WHERE ID = " . $_SESSION['userdata']['ID']);
        mysqli_query($con, "UPDATE utilisateurs SET passcas = '" . base64_encode($passcaschiffre) . "' WHERE ID = " . $_SESSION['userdata']['ID']);
        mysqli_query($con, "UPDATE utilisateurs SET iv = '" . bin2hex($iv) . "' WHERE ID = " . $_SESSION['userdata']['ID']);
        mysqli_query($con, "UPDATE utilisateurs SET verified = 1 WHERE ID = " . $_SESSION['userdata']['ID']);
        $_SESSION['usercas'] = $_POST['usercas'];
        $_SESSION['passcas'] = $_POST['passcas'];
        $_SESSION['userdata']['verified'] = 1;
        mysqli_close($con);
    } else {
        mysqli_query($con, "UPDATE utilisateurs SET usercas = '' WHERE ID = " . $_SESSION['userdata']['ID']);
        mysqli_query($con, "UPDATE utilisateurs SET passcas = '' WHERE ID = " . $_SESSION['userdata']['ID']);
        mysqli_query($con, "UPDATE utilisateurs SET verified = 0 WHERE ID = " . $_SESSION['userdata']['ID']);
        $_SESSION['usercas'] = "";
        $_SESSION['passcas'] = "";
        $_SESSION['userdata']['verified'] = 0;
        mysqli_close($con);
    }
}
header("Location: profil.php");
?>