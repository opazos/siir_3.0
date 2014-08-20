<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$organizacion=$_POST['org'];
$dato=explode(",",$organizacion);

$tipo_documento=$dato[0];
$n_documento=$dato[1];

if ($action==ADD)
{
	if ($_POST['org']==NULL or $_POST['poa']==NULL or $_POST['fte_fto']==NULL)
	{
	//Redirecciono
	echo "<script>window.location ='n_contrato_ruta.php?SES=$SES&anio=$anio&error=vacio'</script>"; 
	}
	else
	{
	//1.- Obtengo la numeración
	$sql="SELECT sys_bd_numera_dependencia.cod, 
	sys_bd_numera_dependencia.n_contrato_ra, 
	sys_bd_numera_dependencia.n_solicitud_ra, 
	sys_bd_numera_dependencia.n_atf_ra
	FROM sys_bd_numera_dependencia
	WHERE sys_bd_numera_dependencia.cod_dependencia='".$row['cod_dependencia']."' AND
	sys_bd_numera_dependencia.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);

	$n_contrato=$r1['n_contrato_ra']+1;
	$n_solicitud=$r1['n_solicitud_ra']+1;
	$n_atf=$r1['n_atf_ra']+1;


	//2.- Actualizo la numeración
	$sql="UPDATE sys_bd_numera_dependencia SET n_contrato_ra='$n_contrato',n_solicitud_ra='$n_solicitud',n_atf_ra='$n_atf' WHERE cod='".$r1['cod']."'";
	$result=mysql_query($sql) or die (mysql_error());

	//3.-Ingreso los datos a la tabla
	$sql="INSERT INTO gcac_bd_ruta VALUES('','11','".$_POST['tipo_ruta']."',UPPER('".$_POST['otro']."'),UPPER('".$_POST['evento']."'),'".$_POST['f_inicio']."','".$_POST['f_termino']."',UPPER('".$_POST['objetivo']."'),'$tipo_documento','$n_documento','008','','$n_contrato','".$_POST['f_contrato']."','$n_atf','$n_solicitud','".$_POST['aporte_pdss']."','".$_POST['aporte_org']."','".$_POST['aporte_otro']."','".$_POST['poa']."','".$_POST['fte_fto']."','','','','','','','','','','','','','','','','005')";
	$result=mysql_query($sql) or die (mysql_error());

	//4.- obtengo el ultimo registro generado
	$sql="SELECT * FROM gcac_bd_ruta ORDER BY cod_ruta DESC LIMIT 0,1";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);

	$codigo=$r1['cod_ruta'];

	//5.- Procedemos a registrar al itinerario de la ruta
	for ($i=0;$i<=10;$i++)
	{
	if ($_POST['lugar'][$i]<>NULL)
	{
	$sql="INSERT INTO gcac_bd_itinerario_ruta VALUES('','".$_POST['inicio'][$i]."','".$_POST['termino'][$i]."',UPPER('".$_POST['lugar'][$i]."'),'$codigo')";
	$result=mysql_query($sql) or die (mysql_error());
	}
	}

	//6.- Redirecciono
	echo "<script>window.location ='n_contrato_ruta.php?SES=$SES&anio=$anio&modo=rutero&cod=$codigo'</script>"; 
}

}
elseif($action==ADD_RUTERO)
{
if ($_POST['rutero']==NULL)
{
//Redirecciono
echo "<script>window.location ='n_contrato_ruta.php?SES=$SES&anio=$anio&cod=$cod&modo=rutero&error=vacio'</script>"; 
}
else
{
//1.- Actualizo la informacion
$sql="UPDATE gcac_bd_ruta SET cod_tipo_doc='008',n_documento='".$_POST['rutero']."' WHERE cod_ruta='$cod'";
$result=mysql_query($sql) or die (mysql_error());

//2.- redirecciono
echo "<script>window.location ='../print/print_contrato_ruta.php?SES=$SES&anio=$anio&cod=$cod'</script>"; 
}
}
elseif($action==UPDATE)
{
//1.- Actualizamos la informacion de la ruta de aprendizaje

	$sql="UPDATE gcac_bd_ruta SET cod_tipo_ruta='".$_POST['tipo_ruta']."',otro_ruta=UPPER('".$_POST['otro']."'),nombre=UPPER('".$_POST['evento']."'),f_inicio='".$_POST['f_inicio']."',f_termino='".$_POST['f_termino']."',objetivo=UPPER('".$_POST['objetivo']."'),f_contrato='".$_POST['f_contrato']."',aporte_pdss='".$_POST['aporte_pdss']."',aporte_org='".$_POST['aporte_org']."',aporte_otro='".$_POST['aporte_otro']."',cod_poa='".$_POST['poa']."',cod_fte_fto='".$_POST['fte_fto']."' WHERE cod_ruta='".$_POST['codigo']."'";
	$result=mysql_query($sql) or die (mysql_error());

//2.- Actualizamos el itinerario si fuera el caso
foreach($lugars as $cod=>$a1)
{
	$sql="UPDATE gcac_bd_itinerario_ruta SET f_inicio='".$_POST['inicios'][$cod]."',f_termino='".$_POST['terminos'][$cod]."',lugar=UPPER('".$_POST['lugars'][$cod]."') WHERE cod_itinerario='$a1'";
	$result=mysql_query($sql) or die (mysql_error());
}

//3.- Registro los nuevos sitios
for($i=0;$i<=5;$i++)
{
if ($_POST['lugar'][$i]<>NULL)
{
$sql="INSERT INTO gcac_bd_itinerario_ruta VALUES('','".$_POST['inicio'][$i]."','".$_POST['termino'][$i]."',UPPER('".$_POST['lugar'][$i]."'),'".$_POST['codigo']."')";
$result=mysql_query($sql) or die (mysql_error());
}
}

//4.- POR ULTIMO REDIRECCIONAMOS
$codigo=$_POST['codigo'];
echo "<script>window.location ='../print/print_contrato_ruta.php?SES=$SES&anio=$anio&cod=$codigo'</script>"; 

}
elseif($action==LIQUIDA)
{
	//1.- Actualizo la informacion
	$sql="UPDATE gcac_bd_ruta SET f_liquidacion='".$_POST['f_liquidacion']."',resultado=UPPER('".$_POST['resultado']."'),b1_fav='".$_POST['b1_fav']."',b1_limit='".$_POST['b1_lim']."',b2_fav='".$_POST['b2_fav']."',b2_limit='".$_POST['b2_lim']."',b3_fav='".$_POST['b3_fav']."',b3_limit='".$_POST['b3_lim']."',b4_fav='".$_POST['b4_fav']."',b4_limit='".$_POST['b4_lim']."',recomendaciones=UPPER('".$_POST['recomendaciones']."'),ejec_pdss='".$_POST['ejec_pdss']."',ejec_org='".$_POST['ejec_org']."',ejec_otro='".$_POST['ejec_otro']."',observaciones='".$_POST['comentario']."',cod_estado_iniciativa='004' WHERE cod_ruta='$id'";
	$result=mysql_query($sql) or die (mysql_error());

	echo "<script>window.location ='contrato_ruta.php?SES=$SES&anio=$anio&modo=liquida'</script>"; 
}

?>