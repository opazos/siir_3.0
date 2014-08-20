<?
session_start();
session_destroy(); // destruyo la sesion
header("Location: index.php");
?>