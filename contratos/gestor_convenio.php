<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

if ($action==ADD)
{

$organizacion=$_POST['org'];
$dato=explode(",",$organizacion);
$tipo_documento=$dato[0];
$n_documento=$dato[1];

//1.- actualizamos la numeracion
$sql="UPDATE sys_bd_numera_dependencia SET n_convenio='".$_POST['n_convenio']."' WHERE cod='".$_POST['cod_numera']."'";
$result=mysql_query($sql) or die (mysql_error());

//2.- Verificamos que la organizacion no sea una nueva, de ser asi la registramos
if ($_POST['org']==0)
{
$oficina=$row['cod_dependencia'];
$documento="0000-0".$oficina;


$sql="INSERT INTO org_ficha_organizacion VALUES('".$_POST['tipo_doc']."','".$_POST['n_documento']."',UPPER('".$_POST['nombre']."'),'".$_POST['tipo_org']."','','','".$_POST['select1']."','".$_POST['select2']."','".$_POST['select3']."',UPPER('".$_POST['direccion']."'),'','','".$row['cod_dependencia']."','0','000','$documento')";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);


$sql="INSERT INTO gcac_bd_ficha_convenio VALUES('','".$_POST['n_convenio']."','".$_POST['f_convenio']."','".$_POST['f_termino']."','".$_POST['objetivo_1']."','".$_POST['objetivo_2']."','$fecha_hoy','".$row['cod_dependencia']."','".$_POST['tipo_doc']."','".$_POST['n_documento']."','".$_POST['dni1']."','".$_POST['nombre1']."','".$_POST['cargo1']."')";
$result=mysql_query($sql) or die (mysql_error());

$sql="SELECT * FROM gcac_bd_ficha_convenio ORDER BY cod_ficha DESC LIMIT 0,1";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$codigo=$r1['cod_ficha'];
}
elseif($_POST['org']<>0)
{
$sql="INSERT INTO gcac_bd_ficha_convenio VALUES('','".$_POST['n_convenio']."','".$_POST['f_convenio']."','".$_POST['f_termino']."','".$_POST['objetivo_1']."','".$_POST['objetivo_2']."','$fecha_hoy','".$row['cod_dependencia']."','$tipo_documento','$n_documento','".$_POST['dni1']."','".$_POST['nombre1']."','".$_POST['cargo1']."')";
$result=mysql_query($sql) or die (mysql_error());

$sql="SELECT * FROM gcac_bd_ficha_convenio ORDER BY cod_ficha DESC LIMIT 0,1";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$codigo=$r1['cod_ficha'];
}

echo "<script>window.location ='../print/print_convenio.php?SES=$SES&anio=$anio&cod=$codigo'</script>"; 	

}
elseif($action==UPDATE)
{
//1.- actualizo la informacion del convenio
$sql="UPDATE gcac_bd_ficha_convenio SET n_convenio='".$_POST['n_convenio']."',f_inicio='".$_POST['f_convenio']."',f_termino='".$_POST['f_termino']."',objetivo_1='".$_POST['objetivo_1']."',objetivo_2='".$_POST['objetivo_2']."',dni='".$_POST['dni1']."',representante='".$_POST['nombre1']."',cargo='".$_POST['cargo1']."' WHERE cod_ficha='".$_POST['codigo']."'";
$result=mysql_query($sql) or die (mysql_error());

$codigo=$_POST['codigo'];

echo "<script>window.location ='../print/print_convenio.php?SES=$SES&anio=$anio&cod=$codigo'</script>"; 
}


?>