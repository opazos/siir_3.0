<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ADD)
{

//1.- Actualizo la numeracion
$sql="UPDATE sys_bd_numera_dependencia SET n_adenda='".$_POST['n_adenda']."' WHERE cod='".$_POST['cod_numera']."'";
$result=mysql_query($sql) or die (mysql_error());

//2.- Guardo la informacion de la Addenda
	$sql="INSERT INTO pit_bd_ficha_adenda VALUES('','".$_POST['n_adenda']."','".$_POST['f_adenda']."','".$_POST['pit']."','".$_POST['tipo_iniciativa']."','','1',UPPER('".$_POST['referencia']."'),'".$_POST['adenda_plazo']."','".$_POST['adenda_monto']."','".$_POST['n_meses']."','','','".$_POST['f_termino']."','','','','','','','','','','','','','','',UPPER('".$_POST['comentario']."'),'','005')";
	$result=mysql_query($sql) or die (mysql_error());

//3.- Busco el ultimo registro generado
$sql="SELECT * FROM pit_bd_ficha_adenda ORDER BY cod_ficha_adenda DESC LIMIT 0,1";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$codigo=$r1['cod_ficha_adenda'];

//4.- Verifico que tipo de adenda es y redirecciono

echo "<script>window.location ='n_adenda_pit.php?SES=$SES&anio=$anio&cod=$codigo&modo=atf'</script>";	
}

elseif($action==ADD_ATF)
{
if ($_POST['iniciativa']==NULL)
{
	echo "<script>window.location ='n_adenda_pit.php?SES=$SES&anio=$anio&cod=$cod&modo=atf&error=vacio'</script>";
}
else
{
if ($_POST['tipo_monto']<>0)
{
//1.- Segun el tipo de iniciativa determino montos
	if ($_POST['tipo_iniciativa']==3)
	{
		$an_pdss=$_POST['pm1'];
		$an_org=$_POST['om1'];
		$cif=0;
		$at_pdss=0;
		$at_org=0;
		$vg_pdss=0;
		$vg_org=0;
		$pf_pdss=0;
		$pf_org=0;
		$idl_pdss=0;
		$idl_org=0;
	}
	elseif($_POST['tipo_iniciativa']==4)
	{
		$an_pdss=0;
		$an_org=0;
		$cif=0;
		$at_pdss=$_POST['pm3'];
		$at_org=$_POST['om3'];;
		$vg_pdss=$_POST['pm4'];
		$vg_org=$_POST['om4'];
		$pf_pdss=$_POST['pm5'];
		$pf_org=$_POST['om5'];
		$idl_pdss=0;
		$idl_org=0;
	}
	elseif($_POST['tipo_iniciativa']==5)
	{
		$an_pdss=0;
		$an_org=0;
		$cif=$_POST['pm2'];
		$at_pdss=$_POST['pm3'];
		$at_org=$_POST['om3'];;
		$vg_pdss=$_POST['pm4'];
		$vg_org=$_POST['om4'];
		$pf_pdss=0;
		$pf_org=0;
		$idl_pdss=0;
		$idl_org=0;
	}

	$total_pdss=$an_pdss+$cif+$at_pdss+$vg_pdss+$pf_pdss+$idl_pdss;
	$total_org=$an_org+$at_org+$vg_org+$pf_org+$idl_org;


//2.-Actualizo la numeracion
$sql="UPDATE sys_bd_numera_dependencia SET n_atf_iniciativa='".$_POST['n_atf']."',n_solicitud_iniciativa='".$_POST['n_solicitud']."' WHERE cod='".$_POST['cod_numera']."'";
$result=mysql_query($sql) or die (mysql_error());

//3.-Actualizo la ADDENDA
$sql="UPDATE pit_bd_ficha_adenda SET cod_iniciativa='".$_POST['iniciativa']."',total_pdss='$total_pdss',total_org='$total_org',n_atf='".$_POST['n_atf']."',n_solicitud='".$_POST['n_solicitud']."',f_desembolso='".$_POST['f_solicitud']."',an_pdss='$an_pdss',an_org='$an_org',cif_pdss='$cif',at_pdss='$at_pdss',at_org='$at_org',vg_pdss='$vg_pdss',vg_org='$vg_org',pf_pdss='$pf_pdss',pf_org='$pf_org',idl_pdss='$idl_pdss',idl_org='$idl_org' WHERE cod_ficha_adenda='$cod'";
$result=mysql_query($sql) or die (mysql_error());
}
elseif($_POST['tipo_monto']==0)
{
$sql="UPDATE pit_bd_ficha_adenda SET cod_iniciativa='".$_POST['iniciativa']."' WHERE cod_ficha_adenda='$cod'";
$result=mysql_query($sql) or die (mysql_error());
}

//4.-Redirecciono
echo "<script>window.location ='n_adenda_pit.php?SES=$SES&anio=$anio&cod=$cod&modo=edit'</script>";
}
}
elseif($action==ADD_CONTENIDO)
{
//1.- Guardo la informacion del contenido
$sql="UPDATE pit_bd_ficha_adenda SET contenido='".$_POST['contenido']."' WHERE cod_ficha_adenda='$cod'";
$result=mysql_query($sql) or die (mysql_error());
//2.- Redirecciono
echo "<script>window.location ='../print/print_adenda_pit.php?SES=$SES&anio=$anio&cod=$cod'</script>";

}

elseif($action==UPDATE)
{
//1.- Actualizo la adenda
$sql="UPDATE pit_bd_ficha_adenda SET f_adenda='".$_POST['f_adenda']."',referencia='".$_POST['referencia']."',tipo_plazo='".$_POST['adenda_plazo']."',tipo_monto='".$_POST['adenda_monto']."',n_meses='".$_POST['n_meses']."',f_termino='".$_POST['f_termino']."',comentario='".$_POST['comentario']."' WHERE cod_ficha_adenda='$id'";
$result=mysql_query($sql) or die (mysql_error());


$codigo=$id;

//4.- Verifico que tipo de adenda es y redirecciono

echo "<script>window.location ='n_adenda_pit.php?SES=$SES&anio=$anio&cod=$codigo&modo=atf'</script>";	

}


?>