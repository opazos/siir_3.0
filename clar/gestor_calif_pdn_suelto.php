<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ADD and $modo==PRIMERO_CAMPO)
{
	
	if($_POST['iniciativa']==NULL or $_POST['jurado']==NULL)
	{
		echo "<script>window.location ='calif_campo_pdn_suelto_prim.php?SES=$SES&anio=$anio&id=$id&error=vacio'</script>";
	}
	else
	{
		//1.- Ingreso la informacion
		$total=$_POST['p1']+$_POST['p2']+$_POST['p3']+$_POST['p4']+$_POST['p5']+$_POST['p6']+$_POST['p7']+$_POST['p8'];
		
		$sql="INSERT INTO clar_calif_ficha_pdn_suelto VALUES('','".$_POST['jurado']."','".$_POST['p1']."','".$_POST['p2']."','".$_POST['p3']."','".$_POST['p4']."','".$_POST['p5']."','".$_POST['p6']."','".$_POST['p7']."','".$_POST['p8']."','".$_POST['p9']."','$total','".$_POST['iniciativa']."')";	
		$result=mysql_query($sql) or die (mysql_error());
		
		//2.-Busco la info del PDN
		$sql="SELECT clar_bd_ficha_pdn_suelto.cod_pdn
		FROM clar_bd_ficha_pdn_suelto
		WHERE clar_bd_ficha_pdn_suelto.cod_ficha_pdn_clar='".$_POST['iniciativa']."'";
		$result=mysql_query($sql) or die (mysql_error());
		$r1=mysql_fetch_array($result);
		
		//3.- Promedio los puntajes
		
		//3.1 Campo
		$sql="SELECT AVG(clar_calif_ficha_pdn_suelto.total_puntaje) AS puntaje
		FROM clar_calif_ficha_pdn_suelto
		WHERE clar_calif_ficha_pdn_suelto.cod_ficha_pdn_clar='".$_POST['iniciativa']."'";
		$result=mysql_query($sql) or die (mysql_error());
		$r2=mysql_fetch_array($result);
		
		//3.2 CLAR
		$sql="SELECT AVG(clar_calif_ficha_pdn_clar_suelto.total_puntaje) AS puntaje
		FROM clar_calif_ficha_pdn_clar_suelto
		WHERE clar_calif_ficha_pdn_clar_suelto.cod_ficha_pdn_clar='".$_POST['iniciativa']."'";
		$result=mysql_query($sql) or die (mysql_error());
		$r3=mysql_fetch_array($result);
		
		$puntaje=$r2['puntaje']+$r3['puntaje'];
		
	
		if ($puntaje>=70)
		{
			$estado="002";
		}
		else
		{
			$estado="003";
		}
		
		//4.- Actualizo el Plan de negocio
		$sql="UPDATE pit_bd_ficha_pdn SET calificacion='$puntaje',cod_estado_iniciativa='$estado' WHERE cod_pdn='".$r1['cod_pdn']."'";
		$result=mysql_query($sql) or die (mysql_error());
	
		//5.- Redirecciono
		echo "<script>window.location ='calif_campo_pdn_suelto_prim.php?SES=$SES&anio=$anio&id=$id'</script>";		
	}
}
elseif($action==ADD and $modo==PRIMERO_CLAR)
{

	if($_POST['iniciativa']==NULL or $_POST['jurado']==NULL)
	{
		echo "<script>window.location ='calif_clar_pdn_suelto_prim.php?SES=$SES&anio=$anio&id=$id&error=vacio'</script>";
	}
	else
	{
	//1.-Ingreso la info de calificacion
	$total=$_POST['p1']+$_POST['p2']+$_POST['p3']+$_POST['p4']+$_POST['p5'];
	
	$sql="INSERT INTO clar_calif_ficha_pdn_clar_suelto VALUES('','".$_POST['jurado']."','".$_POST['p1']."','".$_POST['p2']."','".$_POST['p3']."','".$_POST['p4']."','".$_POST['p5']."','$total','".$_POST['iniciativa']."')";
	$result=mysql_query($sql) or die(mysql_error());

		//2.-Busco la info del PDN
		$sql="SELECT clar_bd_ficha_pdn_suelto.cod_pdn
		FROM clar_bd_ficha_pdn_suelto
		WHERE clar_bd_ficha_pdn_suelto.cod_ficha_pdn_clar='".$_POST['iniciativa']."'";
		$result=mysql_query($sql) or die (mysql_error());
		$r1=mysql_fetch_array($result);
		
		//3.- Promedio los puntajes
		
		//3.1 Campo
		$sql="SELECT AVG(clar_calif_ficha_pdn_suelto.total_puntaje) AS puntaje
		FROM clar_calif_ficha_pdn_suelto
		WHERE clar_calif_ficha_pdn_suelto.cod_ficha_pdn_clar='".$_POST['iniciativa']."'";
		$result=mysql_query($sql) or die (mysql_error());
		$r2=mysql_fetch_array($result);
		
		//3.2 CLAR
		$sql="SELECT AVG(clar_calif_ficha_pdn_clar_suelto.total_puntaje) AS puntaje
		FROM clar_calif_ficha_pdn_clar_suelto
		WHERE clar_calif_ficha_pdn_clar_suelto.cod_ficha_pdn_clar='".$_POST['iniciativa']."'";
		$result=mysql_query($sql) or die (mysql_error());
		$r3=mysql_fetch_array($result);
		
		$puntaje=$r2['puntaje']+$r3['puntaje'];
		
	
		if ($puntaje>=70)
		{
			$estado="002";
		}
		else
		{
			$estado="003";
		}
		
		//4.- Actualizo el Plan de negocio
		$sql="UPDATE pit_bd_ficha_pdn SET calificacion='$puntaje',cod_estado_iniciativa='$estado' WHERE cod_pdn='".$r1['cod_pdn']."'";
		$result=mysql_query($sql) or die (mysql_error());
	
		//5.- Redirecciono
		echo "<script>window.location ='calif_clar_pdn_suelto_prim.php?SES=$SES&anio=$anio&id=$id'</script>";				
	}
	
}


?>