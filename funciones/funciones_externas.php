<?
//1.- Funcion para conectarse a la base de datos
function conectarte_externo()
{
mysql_connect("www.sierrasur.gob.pe","psierras_masters","rumpeltinsky") or die("Error:Servidor sin conexion");
mysql_select_db("psierras_usuario") or die("Error:Base de datos sin conexion & No disponible");
}
?>