<?
session_start();
extract($_POST);
extract($_GET);

//Verificamos la sesion
$usuario=$_SESSION['usuario']; 
if (!isset($usuario))
{ 
header("Location:../index.php");
}

?>