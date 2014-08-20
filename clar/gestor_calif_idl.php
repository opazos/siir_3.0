<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ADD and $modo==PRIMERO_CLAR)
{

	if($_POST['iniciativa']==NULL or $_POST['jurado']==NULL)
	{
		echo "<script>window.location ='calif_idl_prim.php?SES=$SES&anio=$anio&id=$id&error=vacio'</script>";
	}
	else
	{
		//1.- Guardo la informacion
		$total=$_POST['p1']+$_POST['p2']+$_POST['p3']+$_POST['p4']+$_POST['p5']+$_POST['p6']+$_POST['p7']+$_POST['p8']+$_POST['p9']+$_POST['p10']+$_POST['p11'];
		
		$sql="INSERT INTO clar_calif_ficha_idl_clar VALUES('','".$_POST['jurado']."','".$_POST['p1']."','".$_POST['p2']."','".$_POST['p3']."','".$_POST['p4']."','".$_POST['p5']."','".$_POST['p6']."','".$_POST['p7']."','".$_POST['p8']."','".$_POST['p9']."','".$_POST['p10']."','".$_POST['p11']."','$total','".$_POST['iniciativa']."')";
		$result=mysql_query($sql) or die (mysql_error());
		
		//2.- Busco la idl
		$sql="SELECT clar_bd_ficha_idl.cod_idl
		FROM clar_bd_ficha_idl
		WHERE clar_bd_ficha_idl.cod_ficha_idl_clar='".$_POST['iniciativa']."'";
		$result=mysql_query($sql) or die (mysql_error());
		$r1=mysql_fetch_array($result);
		
		//3.-PROMEDIO
		$sql="SELECT AVG(clar_calif_ficha_idl_clar.total_puntaje) AS puntaje
		FROM clar_calif_ficha_idl_clar
		WHERE clar_calif_ficha_idl_clar.cod_ficha_idl_clar='".$_POST['iniciativa']."'";
		$result=mysql_query($sql) or die (mysql_error());
		$r2=mysql_fetch_array($result);
		
		$puntaje=$r2['puntaje'];
		
		if ($puntaje>=70)
		{
			$estado="002";
		}
		else
		{
			$estado="003";
		}
		
		//4.- Actualizo
		$sql="UPDATE pit_bd_ficha_idl SET calificacion='$puntaje',cod_estado_iniciativa='$estado' WHERE cod_ficha_idl='".$r1['cod_idl']."'";
		$result=mysql_query($sql) or die (mysql_error());
		
		//5.-Redirecciono
		echo "<script>window.location ='calif_idl_prim.php?SES=$SES&anio=$anio&id=$id'</script>";
			
	}

}
elseif($action==ADD and $modo==SEGUNDO_CLAR)
{

	if($_POST['iniciativa']==NULL or $_POST['jurado']==NULL)
	{
		echo "<script>window.location ='calif_idl_seg.php?SES=$SES&anio=$anio&id=$id&error=vacio'</script>";
	}
	else
	{
	
	//1.- Genero los puntajes
	$total=$_POST['p1']+$_POST['p2']+$_POST['p3']+$_POST['p4']+$_POST['p5']+$_POST['p6']+$_POST['p7']+$_POST['p8']+$_POST['p9']+$_POST['p10']+$_POST['p11'];
	
	$sql="INSERT INTO clar_calif_ficha_idl_clar_2 VALUES('','".$_POST['jurado']."','".$_POST['p1']."','".$_POST['p2']."','".$_POST['p3']."','".$_POST['p4']."','".$_POST['p5']."','".$_POST['p6']."','".$_POST['p7']."','".$_POST['p8']."','".$_POST['p9']."','".$_POST['p10']."','".$_POST['p11']."','$total','".$_POST['iniciativa']."')";
	$result=mysql_query($sql) or die (mysql_error());

	//2.- Busco la idl
	$sql="SELECT clar_bd_ficha_idl_2.cod_idl
	FROM clar_bd_ficha_idl_2
	WHERE clar_bd_ficha_idl_2.cod_ficha_idl_clar='".$_POST['iniciativa']."'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	//3.- Promedio
		$sql="SELECT AVG(clar_calif_ficha_idl_clar_2.total_puntaje) AS puntaje
		FROM clar_calif_ficha_idl_clar_2
		WHERE clar_calif_ficha_idl_clar_2.cod_ficha_idl_clar='".$_POST['iniciativa']."'";
		$result=mysql_query($sql) or die (mysql_error());
		$r2=mysql_fetch_array($result);
		
		$puntaje=$r2['puntaje'];
		
		if ($puntaje>=70)
		{
			$estado="007";
		}
		else
		{
			$estado="005";
		}
		
		//4.- Actualizo
		$sql="UPDATE pit_bd_ficha_idl SET calificacion_2='$puntaje',cod_estado_iniciativa='$estado' WHERE cod_ficha_idl='".$r1['cod_idl']."'";
		$result=mysql_query($sql) or die (mysql_error());
		
		//5.-Redirecciono
		echo "<script>window.location ='calif_idl_seg.php?SES=$SES&anio=$anio&id=$id'</script>";
	
	
	}

}
elseif($action==DELETE and $modo==SEGUNDO_CLAR)
{
	//1.- Busco el codigo de ficha
	$sql="SELECT clar_calif_ficha_idl_clar_2.cod_ficha_idl_clar, 
	clar_bd_ficha_idl_2.cod_idl
	FROM clar_bd_ficha_idl_2 INNER JOIN clar_calif_ficha_idl_clar_2 ON clar_bd_ficha_idl_2.cod_ficha_idl_clar = clar_calif_ficha_idl_clar_2.cod_ficha_idl_clar
	WHERE clar_calif_ficha_idl_clar_2.cod_ficha_calif_idl='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$codigo_ficha=$r1['cod_ficha_idl_clar'];
	$idl=$r1['cod_idl'];
	

	//2.- Elimino el registro
	$sql="DELETE FROM clar_calif_ficha_idl_clar_2 WHERE cod_ficha_calif_idl='$cod'";
	$result=mysql_query($sql) or die (mysql_error());




	//3.- Promedio
	$sql="SELECT AVG(clar_calif_ficha_idl_clar_2.total_puntaje) AS puntaje
	FROM clar_calif_ficha_idl_clar_2
	WHERE clar_calif_ficha_idl_clar_2.cod_ficha_idl_clar='$codigo_ficha'";
	$result=mysql_query($sql) or die (mysql_error());
	$r2=mysql_fetch_array($result);
	
	if ($puntaje==NULL)
	{
		$puntaje=0;
	}
	else
	{
		$puntaje=$r2['puntaje'];
	}
	echo $puntaje;
	
		if ($puntaje>=70)
		{
			$estado="007";
		}
		else
		{
			$estado="005";
		}
		
		echo $puntaje;
		
	//4.- Actualizo
	$sql="UPDATE pit_bd_ficha_idl SET calificacion_2='$puntaje',cod_estado_iniciativa='$estado' WHERE cod_ficha_idl='$idl'";
	$result=mysql_query($sql) or die (mysql_error());
	
	echo "<script>window.location ='calif_idl_seg.php?SES=$SES&anio=$anio&id=$id'</script>";


}
?>