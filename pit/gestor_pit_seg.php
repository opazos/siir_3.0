<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ADD)
{
//1.- Actualizamos el PIT
$sql="UPDATE pit_bd_ficha_pit SET cod_estado_iniciativa='006',f_presentacion_2='$fecha_hoy',n_voucher_2='".$_POST['n_voucher']."',monto_organizacion_2='".$_POST['monto_org']."' WHERE cod_pit='".$_POST['pit']."'";
$result=mysql_query($sql) or die (mysql_error());

//2.-Generamos la ficha de segundo desembolso
$sql="INSERT INTO pit_bd_pit_sd VALUES('','1','".$_POST['pit']."','".$_POST['f_desembolso']."','".$_POST['n_cheque']."','".$_POST['total1']."','','')";
$result=mysql_query($sql) or die (mysql_error());

$codigo=$_POST['pit'];

//3.- redireccionamos
echo "<script>window.location ='n_pit_seg.php?SES=$SES&anio=$anio&cod=$codigo&modo=directiva'</script>";
}
elseif($action==ADD_DIR)
{
//1.- Actualizamos la ficha
$sql="UPDATE pit_bd_pit_sd SET hc_dir='".$_POST['hc_dir']."',just_dir=UPPER('".$_POST['mc_dir']."') WHERE cod_pit='$cod'";
$result=mysql_query($sql) or die (mysql_error());

//2.- Ubico la info del PIT
$sql="SELECT pit_bd_ficha_pit.cod_tipo_doc_taz, 
	pit_bd_ficha_pit.n_documento_taz
FROM pit_bd_ficha_pit
WHERE pit_bd_ficha_pit.cod_pit='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

//3.- Generamos nuevos directivos
for($i=0;$i<=5;$i++)
{
if ($_POST['dni'][$i]<>NULL)
{
$sql="INSERT IGNORE INTO org_ficha_directiva_taz VALUES('008','".$_POST['dni'][$i]."',UPPER('".$_POST['nombre'][$i]."'),UPPER('".$_POST['paterno'][$i]."'),UPPER('".$_POST['materno'][$i]."'),'".$_POST['f_nacimiento'][$i]."','".$_POST['sexo'][$i]."','".$_POST['cargo'][$i]."','','".$_POST['f_vigencia'][$i]."','2','1','".$r1['cod_tipo_doc_taz']."','".$r1['n_documento_taz']."')";
$result=mysql_query($sql) or die (mysql_error());
}
}


//4.- Actualizo los cargos y vigencia
foreach($cargo as $cad=>$a1)
{
$sql="UPDATE org_ficha_directiva_taz SET cod_cargo_directivo='".$_POST['cargo'][$cad]."',vigente='".$_POST['vigente'][$cad]."' WHERE n_documento='$cad' AND cod_tipo_doc_taz='".$r1['cod_tipo_doc_taz']."' AND n_documento_taz='".$r1['n_documento_taz']."'";
$result=mysql_query($sql) or die (mysql_error());
}
//5.- redirecciono
echo "<script>window.location ='n_pit_seg.php?SES=$SES&anio=$anio&cod=$cod&modo=directiva'</script>";
}
elseif($action==UPDATE)
{
//1.- Actualizo la informacion del PIT
$sql="UPDATE pit_bd_ficha_pit SET n_voucher_2='".$_POST['n_voucher']."',monto_organizacion_2='".$_POST['monto_org']."' WHERE cod_pit='$cod'";
$result=mysql_query($sql) or die (mysql_error());

//2.- Actualizo la informacion de la ficha de segundo desembolso
if ($_POST['codigo']<>NULL)
{
$sql="UPDATE pit_bd_pit_sd SET f_desembolso='".$_POST['f_desembolso']."',n_cheque='".$_POST['n_cheque']."',ejec_an='".$_POST['total1']."' WHERE cod_ficha_sd='".$_POST['codigo']."'";
$result=mysql_query($sql) or die (mysql_error());
}
else
{
$sql="INSERT INTO pit_bd_pit_sd VALUES('','1','$cod','".$_POST['f_desembolso']."','".$_POST['n_cheque']."','".$_POST['total1']."','','')";
$result=mysql_query($sql) or die (mysql_error());
}
//3.- redirecciono
echo "<script>window.location ='n_pit_seg.php?SES=$SES&anio=$anio&cod=$cod&modo=directiva'</script>";

}


?>