<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ADD and $modo==PRIMERO_CAMPO)
{
	if($_POST['iniciativa']==NULL or $_POST['jurado']==NULL)
	{
		echo "<script>window.location ='calif_campo_mrn_prim.php?SES=$SES&anio=$anio&id=$id&error=vacio'</script>";
	}
	else
	{
		//1.- Guardar cambios
		$total=$_POST['p1']+$_POST['p2']+$_POST['p3']+$_POST['p4']+$_POST['p5']+$_POST['p6']+$_POST['p7'];
		
		$sql="INSERT INTO clar_calif_ficha_mrn VALUES('','".$_POST['jurado']."','".$_POST['p1']."','".$_POST['p2']."','".$_POST['p3']."','".$_POST['p4']."','".$_POST['p5']."','".$_POST['p6']."','".$_POST['p7']."','$total','".$_POST['iniciativa']."')";
		$result=mysql_query($sql) or die (mysql_error());
		
		//2.- Ubico el PGRN
		$sql="SELECT clar_bd_ficha_mrn.cod_mrn
		FROM clar_bd_ficha_mrn
		WHERE clar_bd_ficha_mrn.cod_ficha_mrn_clar='".$_POST['iniciativa']."'";
		$result=mysql_query($sql) or die (mysql_error());
		$r1=mysql_fetch_array($result);
		
		//3.-PROMEDIO
		//3.1 Campo
		$sql="SELECT AVG(clar_calif_ficha_mrn.total_puntaje) AS puntaje
		FROM clar_calif_ficha_mrn
		WHERE clar_calif_ficha_mrn.cod_ficha_mrn_clar='".$_POST['iniciativa']."'";
		$result=mysql_query($sql) or die (mysql_error());
		$r2=mysql_fetch_array($result);
		
		//3.2 CLAR
		$sql="SELECT AVG(clar_calif_ficha_mrn_clar.total_puntaje) AS puntaje
		FROM clar_calif_ficha_mrn_clar
		WHERE clar_calif_ficha_mrn_clar.cod_ficha_mrn_clar='".$_POST['iniciativa']."'";
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
		//4.- Actualizo el Plan de Gestion
		$sql="UPDATE pit_bd_ficha_mrn SET calificacion='$puntaje',cod_estado_iniciativa='$estado' WHERE cod_mrn='".$r1['cod_mrn']."'";
		$result=mysql_query($sql) or die (mysql_error());
		
		//5.- redirecciono
		echo "<script>window.location ='calif_campo_mrn_prim.php?SES=$SES&anio=$anio&id=$id'</script>";
	}
}
elseif($action==ADD and $modo==PRIMERO_CLAR)
{
	if($_POST['iniciativa']==NULL or $_POST['jurado']==NULL)
	{
		echo "<script>window.location ='calif_clar_mrn_prim.php?SES=$SES&anio=$anio&id=$id&error=vacio'</script>";
	}
	else
	{
		//1.- Ingresos los puntajes
		$total=$_POST['p1']+$_POST['p2']+$_POST['p3']+$_POST['p4'];
		
		$sql="INSERT INTO clar_calif_ficha_mrn_clar VALUES('','".$_POST['jurado']."','".$_POST['p1']."','".$_POST['p2']."','".$_POST['p3']."','".$_POST['p4']."','$total','".$_POST['iniciativa']."')";
		$result=mysql_query($sql) or die (mysql_error());
		
		//2.- Ubico el PGRN
		$sql="SELECT clar_bd_ficha_mrn.cod_mrn
		FROM clar_bd_ficha_mrn
		WHERE clar_bd_ficha_mrn.cod_ficha_mrn_clar='".$_POST['iniciativa']."'";
		$result=mysql_query($sql) or die (mysql_error());
		$r1=mysql_fetch_array($result);
		
		//3.-PROMEDIO
		//3.1 Campo
		$sql="SELECT AVG(clar_calif_ficha_mrn.total_puntaje) AS puntaje
		FROM clar_calif_ficha_mrn
		WHERE clar_calif_ficha_mrn.cod_ficha_mrn_clar='".$_POST['iniciativa']."'";
		$result=mysql_query($sql) or die (mysql_error());
		$r2=mysql_fetch_array($result);
		
		//3.2 CLAR
		$sql="SELECT AVG(clar_calif_ficha_mrn_clar.total_puntaje) AS puntaje
		FROM clar_calif_ficha_mrn_clar
		WHERE clar_calif_ficha_mrn_clar.cod_ficha_mrn_clar='".$_POST['iniciativa']."'";
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
		//4.- Actualizo el Plan de Gestion
		$sql="UPDATE pit_bd_ficha_mrn SET calificacion='$puntaje',cod_estado_iniciativa='$estado' WHERE cod_mrn='".$r1['cod_mrn']."'";
		$result=mysql_query($sql) or die (mysql_error());
		
		//5.- redirecciono
		echo "<script>window.location ='calif_clar_mrn_prim.php?SES=$SES&anio=$anio&id=$id'</script>";

	}
}
elseif($action==ADD and $modo==SEGUNDO_CAMPO)
{
	if($_POST['iniciativa']==NULL or $_POST['jurado']==NULL)
	{
		echo "<script>window.location ='calif_campo_mrn_seg.php?SES=$SES&anio=$anio&id=$id&error=vacio'</script>";
	}
	else
	{
		//1.- realizo el calculo de premios
		$total=($_POST['p1']+$_POST['p2']+$_POST['p3']+$_POST['p4']+$_POST['p5']+$_POST['p6']+$_POST['p7']+$_POST['p8']+$_POST['p9']+$_POST['p10'])*2;
		
		$sql="INSERT INTO clar_calif_ficha_mrn_2 VALUES('','".$_POST['jurado']."','".$_POST['p1']."','".$_POST['p2']."','".$_POST['p3']."','".$_POST['p4']."','".$_POST['p5']."','".$_POST['p6']."','".$_POST['p7']."','".$_POST['p8']."','".$_POST['p9']."','".$_POST['p10']."','$total','".$_POST['iniciativa']."')";
		$result=mysql_query($sql) or die (mysql_error());
		
		//2.-Busco el PGRN
		$sql="SELECT clar_bd_ficha_mrn_2.cod_mrn
		FROM clar_bd_ficha_mrn_2
		WHERE clar_bd_ficha_mrn_2.cod_ficha_mrn_clar='".$_POST['iniciativa']."'";
		$result=mysql_query($sql) or die (mysql_error());
		$r1=mysql_fetch_array($result);
		
		//3.-Promedio
		//campo
		$sql="SELECT AVG(clar_calif_ficha_mrn_2.total_puntaje) AS puntaje
		FROM clar_calif_ficha_mrn_2
		WHERE clar_calif_ficha_mrn_2.cod_ficha_mrn_clar='".$_POST['iniciativa']."'";
		$result=mysql_query($sql) or die (mysql_error());
		$r2=mysql_fetch_array($result);
		
		//PDN
		$sql="SELECT AVG(clar_calif_ficha_mrn_clar_2.total_puntaje) AS puntaje
		FROM clar_calif_ficha_mrn_clar_2
		WHERE clar_calif_ficha_mrn_clar_2.cod_ficha_mrn_clar='".$_POST['iniciativa']."'";
		$result=mysql_query($sql) or die (mysql_error());
		$r3=mysql_fetch_array($result);
		
		$puntaje=$r2['puntaje']+$r3['puntaje'];
		
		if ($puntaje>=70)
		{
		$estado="007";
		}
		else
		{
		$estado="005";
		}
		//4.- Actualizo el Plan de Gestion
		$sql="UPDATE pit_bd_ficha_mrn SET calificacion_2='$puntaje',cod_estado_iniciativa='$estado' WHERE cod_mrn='".$r1['cod_mrn']."'";
		$result=mysql_query($sql) or die (mysql_error());
		
		//5.- redirecciono
		echo "<script>window.location ='calif_campo_mrn_seg.php?SES=$SES&anio=$anio&id=$id'</script>";
		
	}
}
elseif($action==ADD and $modo==SEGUNDO_CLAR)
{
	if($_POST['iniciativa']==NULL or $_POST['jurado']==NULL)
	{
		echo "<script>window.location ='calif_clar_mrn_seg.php?SES=$SES&anio=$anio&id=$id&error=vacio'</script>";
	}
	else
	{
		//1.- Verifico el puntaje
		$total=$_POST['p1']+$_POST['p2']+$_POST['p3']+$_POST['p4']+$_POST['p5']+$_POST['p6'];
		
		$sql="INSERT INTO clar_calif_ficha_mrn_clar_2 VALUES('','".$_POST['jurado']."','".$_POST['p1']."','".$_POST['p2']."','".$_POST['p3']."','".$_POST['p4']."','".$_POST['p5']."','".$_POST['p6']."','$total','".$_POST['iniciativa']."')";
		$result=mysql_query($sql) or die (mysql_error());
		
		//2.-Busco el PGRN
		$sql="SELECT clar_bd_ficha_mrn_2.cod_mrn
		FROM clar_bd_ficha_mrn_2
		WHERE clar_bd_ficha_mrn_2.cod_ficha_mrn_clar='".$_POST['iniciativa']."'";
		$result=mysql_query($sql) or die (mysql_error());
		$r1=mysql_fetch_array($result);
		
		//3.-Promedio
		//campo
		$sql="SELECT AVG(clar_calif_ficha_mrn_2.total_puntaje) AS puntaje
		FROM clar_calif_ficha_mrn_2
		WHERE clar_calif_ficha_mrn_2.cod_ficha_mrn_clar='".$_POST['iniciativa']."'";
		$result=mysql_query($sql) or die (mysql_error());
		$r2=mysql_fetch_array($result);
		
		//PDN
		$sql="SELECT AVG(clar_calif_ficha_mrn_clar_2.total_puntaje) AS puntaje
		FROM clar_calif_ficha_mrn_clar_2
		WHERE clar_calif_ficha_mrn_clar_2.cod_ficha_mrn_clar='".$_POST['iniciativa']."'";
		$result=mysql_query($sql) or die (mysql_error());
		$r3=mysql_fetch_array($result);
		
		$puntaje=$r2['puntaje']+$r3['puntaje'];
		
		if ($puntaje>=70)
		{
		$estado="007";
		}
		else
		{
		$estado="005";
		}
		//4.- Actualizo el Plan de Gestion
		$sql="UPDATE pit_bd_ficha_mrn SET calificacion_2='$puntaje',cod_estado_iniciativa='$estado' WHERE cod_mrn='".$r1['cod_mrn']."'";
		$result=mysql_query($sql) or die (mysql_error());
		
		//5.- redirecciono
		echo "<script>window.location ='calif_clar_mrn_seg.php?SES=$SES&anio=$anio&id=$id'</script>";
	}
}

elseif($action==DELETE and $modo==PRIMERO_CAMPO)
{
	$sql="SELECT clar_calif_ficha_mrn.cod_ficha_mrn_clar, 
	clar_bd_ficha_mrn.cod_mrn
	FROM clar_bd_ficha_mrn INNER JOIN clar_calif_ficha_mrn ON clar_bd_ficha_mrn.cod_ficha_mrn_clar = clar_calif_ficha_mrn.cod_ficha_mrn_clar
	WHERE clar_calif_ficha_mrn.cod_ficha_calif_mrn='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
		$iniciativa=$r1['cod_ficha_mrn_clar'];
	
		//2.- Borrar
		$sql="DELETE FROM clar_calif_ficha_mrn WHERE cod_ficha_calif_mrn='$cod'";
		$result=mysql_query($sql) or die (mysql_error);
	
		//3.-PROMEDIO
		//3.1 Campo
		$sql="SELECT AVG(clar_calif_ficha_mrn.total_puntaje) AS puntaje
		FROM clar_calif_ficha_mrn
		WHERE clar_calif_ficha_mrn.cod_ficha_mrn_clar='$iniciativa'";
		$result=mysql_query($sql) or die (mysql_error());
		$r2=mysql_fetch_array($result);
		
		//3.2 CLAR
		$sql="SELECT AVG(clar_calif_ficha_mrn_clar.total_puntaje) AS puntaje
		FROM clar_calif_ficha_mrn_clar
		WHERE clar_calif_ficha_mrn_clar.cod_ficha_mrn_clar='$iniciativa'";
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
		//4.- Actualizo el Plan de Gestion
		$sql="UPDATE pit_bd_ficha_mrn SET calificacion='$puntaje',cod_estado_iniciativa='$estado' WHERE cod_mrn='".$r1['cod_mrn']."'";
		$result=mysql_query($sql) or die (mysql_error());
		
		//5.- redirecciono
		echo "<script>window.location ='calif_campo_mrn_prim.php?SES=$SES&anio=$anio&id=$id'</script>";

}
elseif($action==DELETE and $modo==PRIMERO_CLAR)
{
	$sql="SELECT clar_calif_ficha_mrn_clar.cod_ficha_mrn_clar, 
	clar_bd_ficha_mrn.cod_mrn
	FROM clar_bd_ficha_mrn INNER JOIN clar_calif_ficha_mrn_clar ON clar_bd_ficha_mrn.cod_ficha_mrn_clar = clar_calif_ficha_mrn_clar.cod_ficha_mrn_clar
	WHERE clar_calif_ficha_mrn_clar.cod_ficha_calif_mrn='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
		$iniciativa=$r1['cod_ficha_mrn_clar'];
	
		//2.- Borrar
		$sql="DELETE FROM clar_calif_ficha_mrn_clar WHERE cod_ficha_calif_mrn='$cod'";
		$result=mysql_query($sql) or die (mysql_error());
	
		//3.-PROMEDIO
		//3.1 Campo
		$sql="SELECT AVG(clar_calif_ficha_mrn.total_puntaje) AS puntaje
		FROM clar_calif_ficha_mrn
		WHERE clar_calif_ficha_mrn.cod_ficha_mrn_clar='$iniciativa'";
		$result=mysql_query($sql) or die (mysql_error());
		$r2=mysql_fetch_array($result);
		
		//3.2 CLAR
		$sql="SELECT AVG(clar_calif_ficha_mrn_clar.total_puntaje) AS puntaje
		FROM clar_calif_ficha_mrn_clar
		WHERE clar_calif_ficha_mrn_clar.cod_ficha_mrn_clar='$iniciativa'";
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
		//4.- Actualizo el Plan de Gestion
		$sql="UPDATE pit_bd_ficha_mrn SET calificacion='$puntaje',cod_estado_iniciativa='$estado' WHERE cod_mrn='".$r1['cod_mrn']."'";
		$result=mysql_query($sql) or die (mysql_error());
		
		//5.- redirecciono
		echo "<script>window.location ='calif_clar_mrn_prim.php?SES=$SES&anio=$anio&id=$id'</script>";	
	
}
elseif($action==DELETE and $modo==SEGUNDO_CAMPO)
{
	$sql="SELECT clar_calif_ficha_mrn_clar_2.cod_ficha_mrn_clar, 
	clar_bd_ficha_mrn_2.cod_mrn
	FROM clar_bd_ficha_mrn_2 INNER JOIN clar_calif_ficha_mrn_clar_2 ON clar_bd_ficha_mrn_2.cod_ficha_mrn_clar = clar_calif_ficha_mrn_clar_2.cod_ficha_mrn_clar
	WHERE clar_calif_ficha_mrn_clar_2.cod_ficha_calif_mrn='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);

	$iniciativa=$r1['cod_ficha_mrn_clar'];
	
	//2.- Borrar
	$sql="DELETE FROM clar_calif_ficha_mrn_clar_2 WHERE cod_ficha_calif_mrn='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	
		//3.-PROMEDIO
		//3.1 Campo
		$sql="SELECT AVG(clar_calif_ficha_mrn.total_puntaje) AS puntaje
		FROM clar_calif_ficha_mrn
		WHERE clar_calif_ficha_mrn.cod_ficha_mrn_clar='$iniciativa'";
		$result=mysql_query($sql) or die (mysql_error());
		$r2=mysql_fetch_array($result);
		
		//3.2 CLAR
		$sql="SELECT AVG(clar_calif_ficha_mrn_clar.total_puntaje) AS puntaje
		FROM clar_calif_ficha_mrn_clar
		WHERE clar_calif_ficha_mrn_clar.cod_ficha_mrn_clar='$iniciativa'";
		$result=mysql_query($sql) or die (mysql_error());
		$r3=mysql_fetch_array($result);
		
		$puntaje=$r2['puntaje']+$r3['puntaje'];
		
		if ($puntaje>=70)
		{
		$estado="007";
		}
		else
		{
		$estado="005";
		}
		//4.- Actualizo el Plan de Gestion
		$sql="UPDATE pit_bd_ficha_mrn SET calificacion_2='$puntaje',cod_estado_iniciativa='$estado' WHERE cod_mrn='".$r1['cod_mrn']."'";
		$result=mysql_query($sql) or die (mysql_error());
		
		//5.- redirecciono
		echo "<script>window.location ='calif_campo_mrn_seg.php?SES=$SES&anio=$anio&id=$id'</script>";		
		
}
elseif($action==DELETE and $modo==SEGUNDO_CLAR)
{
	$sql="SELECT clar_calif_ficha_mrn_clar_2.cod_ficha_mrn_clar, 
	clar_bd_ficha_mrn_2.cod_mrn
	FROM clar_bd_ficha_mrn_2 INNER JOIN clar_calif_ficha_mrn_clar_2 ON clar_bd_ficha_mrn_2.cod_ficha_mrn_clar = clar_calif_ficha_mrn_clar_2.cod_ficha_mrn_clar
	WHERE clar_calif_ficha_mrn_clar_2.cod_ficha_calif_mrn='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$iniciativa=$r1['cod_ficha_mrn_clar'];
	
	//2.- Eliminar
	$sql="DELETE FROM clar_calif_ficha_mrn_clar_2 WHERE cod_ficha_calif_mrn='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	
		//3.-PROMEDIO
		//3.1 Campo
		$sql="SELECT AVG(clar_calif_ficha_mrn.total_puntaje) AS puntaje
		FROM clar_calif_ficha_mrn
		WHERE clar_calif_ficha_mrn.cod_ficha_mrn_clar='$iniciativa'";
		$result=mysql_query($sql) or die (mysql_error());
		$r2=mysql_fetch_array($result);
		
		//3.2 CLAR
		$sql="SELECT AVG(clar_calif_ficha_mrn_clar.total_puntaje) AS puntaje
		FROM clar_calif_ficha_mrn_clar
		WHERE clar_calif_ficha_mrn_clar.cod_ficha_mrn_clar='$iniciativa'";
		$result=mysql_query($sql) or die (mysql_error());
		$r3=mysql_fetch_array($result);
		
		$puntaje=$r2['puntaje']+$r3['puntaje'];
		
		if ($puntaje>=70)
		{
		$estado="007";
		}
		else
		{
		$estado="005";
		}
		//4.- Actualizo el Plan de Gestion
		$sql="UPDATE pit_bd_ficha_mrn SET calificacion_2='$puntaje',cod_estado_iniciativa='$estado' WHERE cod_mrn='".$r1['cod_mrn']."'";
		$result=mysql_query($sql) or die (mysql_error());
		
		//5.- redirecciono
		echo "<script>window.location ='calif_clar_mrn_seg.php?SES=$SES&anio=$anio&id=$id'</script>";				
}
?>