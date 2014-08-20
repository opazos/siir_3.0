<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ADD)
{
	
	//1.- Busco las adendas que tiene este PIT
	$sql="SELECT COUNT(pit_bd_ficha_adenda_pit.cod_adenda) as adenda
	FROM pit_bd_ficha_adenda_pit
	WHERE pit_bd_ficha_adenda_pit.cod_pit='".$_POST['pit']."'";
	$result=mysql_query($sql) or die (mysql_error());
	$row=mysql_fetch_array($result);
	
	$n_adenda=$row['adenda']+1;
	
	
	
	

	
	//2.- Genero la adenda
	$sql="INSERT INTO pit_bd_ficha_adenda_pit VALUES('','$n_adenda','".$_POST['f_adenda']."','".$_POST['pit']."','".$_POST['referencia']."','".$_POST['adenda_plazo']."','".$_POST['adenda_monto']."','".$_POST['cif']."','".$_POST['n_meses']."','".$_POST['f_termino']."','','0','0','005')";
	$result=mysql_query($sql) or die (mysql_error());
	
	//3.- Busco el ultimo registro generado
	$sql="SELECT * FROM pit_bd_ficha_adenda_pit ORDER BY cod_adenda DESC LIMIT 0,1";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$codigo=$r1['cod_adenda'];
	
	$monto=$_POST['adenda_monto'];
	
	
	//4.- Redireccionamos segun sea el caso
	if ($monto<>0)
	{
	echo "<script>window.location ='iniciativa_adenda_cif.php?SES=$SES&anio=$anio&id=$codigo'</script>";	
	}
	else
	{
	echo "<script>window.location ='edit_adenda_pit_cif.php?SES=$SES&anio=$anio&id=$codigo'</script>";
	}
}

elseif($action==ADD_PIT)
{
	$sql="INSERT INTO pit_bd_iniciativa_adenda VALUES('','3','".$_POST['pit']."','".$_POST['aporte_pdss']."','".$_POST['aporte_org']."','$id')";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- redirecciono
	echo "<script>window.location ='iniciativa_adenda_cif.php?SES=$SES&anio=$anio&id=$id'</script>";
}
elseif($action==ADD_MRN)
{
	$sql="INSERT INTO pit_bd_iniciativa_adenda VALUES('','5','".$_POST['pgrn']."','".$_POST['aporte_pdss']."','".$_POST['aporte_org']."','$id')";
	$result=mysql_query($sql) or die (mysql_error());

	//2.- redirecciono
	echo "<script>window.location ='iniciativa_adenda_cif.php?SES=$SES&anio=$anio&id=$id'</script>";
}
elseif($action==ADD_PDN)
{
	$sql="INSERT INTO pit_bd_iniciativa_adenda VALUES('','4','".$_POST['pdn']."','".$_POST['aporte_pdss']."','".$_POST['aporte_org']."','$id')";
	$result=mysql_query($sql) or die (mysql_error());

	//2.- redirecciono
	echo "<script>window.location ='iniciativa_adenda_cif.php?SES=$SES&anio=$anio&id=$id'</script>";	
}



elseif($action==EDIT)
{
	//Actualizo la adenda
	$sql="UPDATE pit_bd_ficha_adenda_pit SET contenido='".$_POST['contenido']."' WHERE cod_adenda='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//Redirecciono a impresion
	echo "<script>window.location ='../print/print_adenda_pit_cif.php?SES=$SES&anio=$anio&id=$id'</script>";
}


?>