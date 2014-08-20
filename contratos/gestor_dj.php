<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

if ($action==ADD)
{

//1.- Actualizo la numeracion
$sql="UPDATE sys_bd_numera_dependencia SET n_dj='".$_POST['n_dj']."' WHERE cod='".$_POST['cod_numera']."'";
$result=mysql_query($sql) or die (mysql_error());

//2.- Ingreso la declaracion
$sql="INSERT INTO epd_dj_evento VALUES('','".$_POST['n_dj']."','".$_POST['f_inicio']."','".$_POST['f_termino']."','".$_POST['f_presentacion']."','".$row['cod_tipo_doc']."','".$row['n_documento']."','".$row['cod_dependencia']."')";
$result=mysql_query($sql) or die (mysql_error());

//3.- Busco la numeracion
$sql="SELECT * FROM epd_dj_evento ORDER BY cod_dj_evento DESC LIMIT 0,1";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$codigo=$r1['cod_dj_evento'];

//4.- ingreso el detalle
for($i=0; $i<=30;$i++)
{

if ($_POST['fecha'][$i]<>NULL)
{
$sql="INSERT INTO epd_detalle_dj_evento VALUES('','".$_POST['fecha'][$i]."','".$_POST['concepto'][$i]."','".$_POST['descripcion'][$i]."','".$_POST['monto'][$i]."','$codigo')";
$result=mysql_query($sql) or die (mysql_error());
}

}
//5.- rEDIRECCIONO
echo "<script>window.location ='../print/print_dj.php?SES=$SES&anio=$anio&cod=$codigo'</script>";

}
elseif($action==UPDATE)
{
//1.- Actualizo
$sql="UPDATE epd_dj_evento SET f_inicio='".$_POST['f_inicio']."',f_termino='".$_POST['f_termino']."',f_presentacion='".$_POST['f_presentacion']."' WHERE cod_dj_evento='".$_POST['codigo']."'";
$result=mysql_query($sql) or die (mysql_error());

$codigo=$_POST['codigo'];

//2.- Foreach
foreach( $fechas as $cod => $a1) 
{
$sql="UPDATE epd_detalle_dj_evento SET f_declaracion='$a1',cod_tipo_gasto='".$_POST['conceptos'][$cod]."',concepto=UPPER('".$_POST['descripcions'][$cod]."'),monto='".$_POST['montos'][$cod]."' WHERE cod_detalle_dj='$cod'";
$result=mysql_query($sql) or die (mysql_error);
}

//3.- Inserto
for($i=0; $i<=20;$i++)
{

if ($_POST['fecha'][$i]<>NULL)
{
$sql="INSERT INTO epd_detalle_dj_evento VALUES('','".$_POST['fecha'][$i]."','".$_POST['concepto'][$i]."',UPPER('".$_POST['descripcion'][$i]."'),'".$_POST['monto'][$i]."','$codigo')";
$result=mysql_query($sql) or die (mysql_error());
}
}

//4.- redirecciono
echo "<script>window.location ='../print/print_dj.php?SES=$SES&anio=$anio&cod=$codigo'</script>";

}



?>