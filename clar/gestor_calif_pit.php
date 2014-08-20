<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ADD and $modo==PRIMERO_CAMPO)
{
	if($_POST['iniciativa']==NULL or $_POST['jurado']==NULL)
	{
		echo "<script>window.location ='calif_campo_pit_prim.php?SES=$SES&anio=$anio&id=$id&error=vacio'</script>";
	}
	else
	{
	//1.- Ingreso la Información
	$total=$_POST['p1']+$_POST['p2']+$_POST['p3']+$_POST['p4']+$_POST['p5']+$_POST['p6']+$_POST['p7']+$_POST['p8'];
	
	$sql="INSERT INTO clar_calif_ficha_pit VALUES('','".$_POST['jurado']."','".$_POST['p1']."','".$_POST['p2']."','".$_POST['p3']."','".$_POST['p4']."','".$_POST['p5']."','".$_POST['p6']."','".$_POST['p7']."','".$_POST['p8']."','$total','".$_POST['iniciativa']."')";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Ubico el PIT
	$sql="SELECT pit_bd_ficha_pit.cod_pit
	FROM clar_bd_ficha_pit INNER JOIN pit_bd_ficha_pit ON clar_bd_ficha_pit.cod_pit = pit_bd_ficha_pit.cod_pit
	WHERE clar_bd_ficha_pit.cod_ficha_pit_clar='".$_POST['iniciativa']."'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	//3.- Promedio los puntajes	
	//3.1.- Campo
	$sql="SELECT AVG (clar_calif_ficha_pit.total_puntaje) AS puntaje
	FROM clar_calif_ficha_pit
	WHERE clar_calif_ficha_pit.cod_ficha_pit_clar='".$_POST['iniciativa']."'";
	$result=mysql_query($sql) or die (mysql_error());
	$r2=mysql_fetch_array($result);
	
	//3.2.- CLAR
	$sql="SELECT AVG(clar_calif_ficha_pit_clar.total_puntaje) AS puntaje
	FROM clar_calif_ficha_pit_clar
	WHERE clar_calif_ficha_pit_clar.cod_ficha_pit_clar='".$_POST['iniciativa']."'";
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
	
	//4.- Actualizo el PIT
	$sql="UPDATE pit_bd_ficha_pit SET calificacion='$puntaje',cod_estado_iniciativa='$estado' WHERE cod_pit='".$r1['cod_pit']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//5.- Redirecciono
	echo "<script>window.location ='calif_campo_pit_prim.php?SES=$SES&anio=$anio&id=$id'</script>";	
	}
}
elseif($action==ADD and $modo==PRIMERO_CLAR)
{
	if($_POST['iniciativa']==NULL or $_POST['jurado']==NULL)
	{
		echo "<script>window.location ='calif_clar_pit_prim.php?SES=$SES&anio=$anio&id=$id&error=vacio'</script>";
	}
	else
	{
		//1.- Ingreso los puntajes
		$total=$_POST['p1']+$_POST['p2']+$_POST['p3']+$_POST['p4']+$_POST['p5'];
		
		$sql="INSERT INTO clar_calif_ficha_pit_clar VALUES('','".$_POST['jurado']."','".$_POST['p1']."','".$_POST['p2']."','".$_POST['p3']."','".$_POST['p4']."','".$_POST['p5']."','$total','".$_POST['iniciativa']."')";
		$result=mysql_query($sql) or die (mysql_error());
		
		//2.- Ubico el PIT
		$sql="SELECT pit_bd_ficha_pit.cod_pit
		FROM clar_bd_ficha_pit INNER JOIN pit_bd_ficha_pit ON clar_bd_ficha_pit.cod_pit = pit_bd_ficha_pit.cod_pit
		WHERE clar_bd_ficha_pit.cod_ficha_pit_clar='".$_POST['iniciativa']."'";
		$result=mysql_query($sql) or die (mysql_error());
		$r1=mysql_fetch_array($result);
		
	//3.- Promedio los puntajes	
	//3.1.- Campo
	$sql="SELECT AVG (clar_calif_ficha_pit.total_puntaje) AS puntaje
	FROM clar_calif_ficha_pit
	WHERE clar_calif_ficha_pit.cod_ficha_pit_clar='".$_POST['iniciativa']."'";
	$result=mysql_query($sql) or die (mysql_error());
	$r2=mysql_fetch_array($result);
	
	//3.2.- CLAR
	$sql="SELECT AVG(clar_calif_ficha_pit_clar.total_puntaje) AS puntaje
	FROM clar_calif_ficha_pit_clar
	WHERE clar_calif_ficha_pit_clar.cod_ficha_pit_clar='".$_POST['iniciativa']."'";
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
	
	//4.- Actualizo el PIT
	$sql="UPDATE pit_bd_ficha_pit SET calificacion='$puntaje',cod_estado_iniciativa='$estado' WHERE cod_pit='".$r1['cod_pit']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	
	//5.- Redirecciono
	echo "<script>window.location ='calif_clar_pit_prim.php?SES=$SES&anio=$anio&id=$id'</script>";			
		
	}

}
elseif($action==ADD and $modo==SEGUNDO_CAMPO)
{
	if($_POST['iniciativa']==NULL or $_POST['jurado']==NULL)
	{
		echo "<script>window.location ='calif_campo_pit_seg.php?SES=$SES&anio=$anio&id=$id&error=vacio'</script>";
	}
	else
	{
		//1.- Registro las calificaciones
		$total=($_POST['p1']+$_POST['p2']+$_POST['p3']+$_POST['p4']+$_POST['p5']+$_POST['p6']+$_POST['p7']+$_POST['p8']+$_POST['p9'])*2;
		
		$sql="INSERT INTO clar_calif_ficha_pit_2 VALUES('','".$_POST['jurado']."','".$_POST['p1']."','".$_POST['p2']."','".$_POST['p3']."','".$_POST['p4']."','".$_POST['p5']."','".$_POST['p6']."','".$_POST['p7']."','".$_POST['p8']."','".$_POST['p9']."','$total','".$_POST['iniciativa']."')";
		$result=mysql_query($sql) or die (mysql_error());
		
		//2.- Buscamos el PIT
		$sql="SELECT clar_bd_ficha_pit_2.cod_pit
		FROM clar_bd_ficha_pit_2
		WHERE clar_bd_ficha_pit_2.cod_ficha_pit_clar='".$_POST['iniciativa']."'";
		$result=mysql_query($sql) or die (mysql_error());
		$r1=mysql_fetch_array($result);
		
		//3.- Promediamos
		//Campo
		$sql="SELECT AVG(clar_calif_ficha_pit_2.total_puntaje) AS puntaje
		FROM clar_calif_ficha_pit_2
		WHERE clar_calif_ficha_pit_2.cod_ficha_pit_clar='".$_POST['iniciativa']."'";
		$result=mysql_query($sql) or die (mysql_error());
		$r2=mysql_fetch_array($result);
		
		//CLAR
		$sql="SELECT AVG(clar_calif_ficha_pit_clar_2.total_puntaje) AS puntaje
		FROM clar_calif_ficha_pit_clar_2
		WHERE clar_calif_ficha_pit_clar_2.cod_ficha_pit_clar='".$_POST['iniciativa']."'";
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
		
		//4.- Actualizo el PIT
		$sql="UPDATE pit_bd_ficha_pit SET calificacion_2='$puntaje',cod_estado_iniciativa='$estado' WHERE cod_pit='".$r1['cod_pit']."'";
		$result=mysql_query($sql) or die (mysql_error());
	
	
		//5.- Redirecciono
		echo "<script>window.location ='calif_campo_pit_seg.php?SES=$SES&anio=$anio&id=$id'</script>";		
	}
}
elseif($action==ADD and $modo==SEGUNDO_CLAR)
{
	if($_POST['iniciativa']==NULL or $_POST['jurado']==NULL)
	{
		echo "<script>window.location ='calif_clar_pit_seg.php?SES=$SES&anio=$anio&id=$id&error=vacio'</script>";
	}
	else
	{
		//1.- Ingreso puntajes
		$total=$_POST['p1']+$_POST['p2']+$_POST['p3']+$_POST['p4']+$_POST['p5']+$_POST['p6'];
		
		$sql="INSERT INTO clar_calif_ficha_pit_clar_2 VALUES('','".$_POST['jurado']."','".$_POST['p1']."','".$_POST['p2']."','".$_POST['p3']."','".$_POST['p4']."','".$_POST['p5']."','".$_POST['p6']."','$total','".$_POST['iniciativa']."')";
		$result=mysql_query($sql) or die (mysql_error());
		
		//2.- Buscamos el PIT
		$sql="SELECT clar_bd_ficha_pit_2.cod_pit
		FROM clar_bd_ficha_pit_2
		WHERE clar_bd_ficha_pit_2.cod_ficha_pit_clar='".$_POST['iniciativa']."'";
		$result=mysql_query($sql) or die (mysql_error());
		$r1=mysql_fetch_array($result);
		
		//3.- Promediamos
		//Campo
		$sql="SELECT AVG(clar_calif_ficha_pit_2.total_puntaje) AS puntaje
		FROM clar_calif_ficha_pit_2
		WHERE clar_calif_ficha_pit_2.cod_ficha_pit_clar='".$_POST['iniciativa']."'";
		$result=mysql_query($sql) or die (mysql_error());
		$r2=mysql_fetch_array($result);
		
		//CLAR
		$sql="SELECT AVG(clar_calif_ficha_pit_clar_2.total_puntaje) AS puntaje
		FROM clar_calif_ficha_pit_clar_2
		WHERE clar_calif_ficha_pit_clar_2.cod_ficha_pit_clar='".$_POST['iniciativa']."'";
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
		
		//4.- Actualizo el PIT
		$sql="UPDATE pit_bd_ficha_pit SET calificacion_2='$puntaje',cod_estado_iniciativa='$estado' WHERE cod_pit='".$r1['cod_pit']."'";
		$result=mysql_query($sql) or die (mysql_error());
	
	
		//5.- Redirecciono
		echo "<script>window.location ='calif_clar_pit_seg.php?SES=$SES&anio=$anio&id=$id'</script>";		

	}
}
elseif($action==DELETE and $modo==PRIMERO_CAMPO)
{
	//1.- Busco
	$sql="SELECT clar_calif_ficha_pit.cod_ficha_pit_clar, 
	clar_bd_ficha_pit.cod_pit
	FROM clar_bd_ficha_pit INNER JOIN clar_calif_ficha_pit ON clar_bd_ficha_pit.cod_ficha_pit_clar = clar_calif_ficha_pit.cod_ficha_pit_clar
	WHERE clar_calif_ficha_pit.cod_ficha_calif_pit='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$iniciativa=$r1['cod_ficha_pit_clar'];

	//2.- Elimino
	$sql="DELETE FROM clar_calif_ficha_pit WHERE cod_ficha_calif_pit='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//3.- Promedio los puntajes	
	//3.1.- Campo
	$sql="SELECT AVG (clar_calif_ficha_pit.total_puntaje) AS puntaje
	FROM clar_calif_ficha_pit
	WHERE clar_calif_ficha_pit.cod_ficha_pit_clar='$iniciativa'";
	$result=mysql_query($sql) or die (mysql_error());
	$r2=mysql_fetch_array($result);
	
	//3.2.- CLAR
	$sql="SELECT AVG(clar_calif_ficha_pit_clar.total_puntaje) AS puntaje
	FROM clar_calif_ficha_pit_clar
	WHERE clar_calif_ficha_pit_clar.cod_ficha_pit_clar='$iniciativa'";
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
	
	//4.- Actualizo el PIT
	$sql="UPDATE pit_bd_ficha_pit SET calificacion='$puntaje',cod_estado_iniciativa='$estado' WHERE cod_pit='".$r1['cod_pit']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	
	//5.- Redirecciono
	echo "<script>window.location ='calif_campo_pit_prim.php?SES=$SES&anio=$anio&id=$id'</script>";

}
elseif($action==DELETE and $modo==PRIMERO_CLAR)
{
	$sql="SELECT clar_calif_ficha_pit_clar.cod_ficha_pit_clar, 
	clar_bd_ficha_pit.cod_pit
	FROM clar_bd_ficha_pit INNER JOIN clar_calif_ficha_pit_clar ON clar_bd_ficha_pit.cod_ficha_pit_clar = clar_calif_ficha_pit_clar.cod_ficha_pit_clar
	WHERE clar_calif_ficha_pit_clar.cod_ficha_calif_pit='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$iniciativa=$r1['cod_ficha_pit_clar'];
	
	//2.- elimino
	$sql="DELETE FROM clar_calif_ficha_pit_clar WHERE cod_ficha_calif_pit='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//3.- Promedio los puntajes	
	//3.1.- Campo
	$sql="SELECT AVG (clar_calif_ficha_pit.total_puntaje) AS puntaje
	FROM clar_calif_ficha_pit
	WHERE clar_calif_ficha_pit.cod_ficha_pit_clar='$iniciativa'";
	$result=mysql_query($sql) or die (mysql_error());
	$r2=mysql_fetch_array($result);
	
	//3.2.- CLAR
	$sql="SELECT AVG(clar_calif_ficha_pit_clar.total_puntaje) AS puntaje
	FROM clar_calif_ficha_pit_clar
	WHERE clar_calif_ficha_pit_clar.cod_ficha_pit_clar='$iniciativa'";
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
	
	//4.- Actualizo el PIT
	$sql="UPDATE pit_bd_ficha_pit SET calificacion='$puntaje',cod_estado_iniciativa='$estado' WHERE cod_pit='".$r1['cod_pit']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	
	//5.- Redirecciono
	echo "<script>window.location ='calif_clar_pit_prim.php?SES=$SES&anio=$anio&id=$id'</script>";

}
elseif($action==DELETE and $modo==SEGUNDO_CAMPO)
{
	$sql="SELECT clar_calif_ficha_pit_2.cod_ficha_pit_clar, 
	clar_bd_ficha_pit_2.cod_pit
	FROM clar_bd_ficha_pit_2 INNER JOIN clar_calif_ficha_pit_2 ON clar_bd_ficha_pit_2.cod_ficha_pit_clar = clar_calif_ficha_pit_2.cod_ficha_pit_clar
	WHERE clar_calif_ficha_pit_2.cod_ficha_calif_pit='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$iniciativa=$r1['cod_ficha_pit_clar'];
	
	
		//2.- Borrar
		$sql="DELETE FROM clar_calif_ficha_pit_2 WHERE cod_ficha_calif_pit='$cod'";
		$result=mysql_query($sql) or die (mysql_error());
	
		//3.- Promediamos
		//Campo
		$sql="SELECT AVG(clar_calif_ficha_pit_2.total_puntaje) AS puntaje
		FROM clar_calif_ficha_pit_2
		WHERE clar_calif_ficha_pit_2.cod_ficha_pit_clar='$iniciativa'";
		$result=mysql_query($sql) or die (mysql_error());
		$r2=mysql_fetch_array($result);
		
		//CLAR
		$sql="SELECT AVG(clar_calif_ficha_pit_clar_2.total_puntaje) AS puntaje
		FROM clar_calif_ficha_pit_clar_2
		WHERE clar_calif_ficha_pit_clar_2.cod_ficha_pit_clar='$iniciativa'";
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
		
		//4.- Actualizo el PIT
		$sql="UPDATE pit_bd_ficha_pit SET calificacion_2='$puntaje',cod_estado_iniciativa='$estado' WHERE cod_pit='".$r1['cod_pit']."'";
		$result=mysql_query($sql) or die (mysql_error());
	
	
		//5.- Redirecciono
		echo "<script>window.location ='calif_campo_pit_seg.php?SES=$SES&anio=$anio&id=$id'</script>";		
}
elseif($action==DELETE and $modo==SEGUNDO_CLAR)
{
	$sql="SELECT clar_calif_ficha_pit_2.cod_ficha_pit_clar, 
	clar_bd_ficha_pit_2.cod_pit
	FROM clar_bd_ficha_pit_2 INNER JOIN clar_calif_ficha_pit_2 ON clar_bd_ficha_pit_2.cod_ficha_pit_clar = clar_calif_ficha_pit_2.cod_ficha_pit_clar
	WHERE clar_calif_ficha_pit_2.cod_ficha_calif_pit='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$iniciativa=$r1['cod_ficha_pit_clar'];
	
	//2.- Borrar
	$sql="DELETE FROM clar_calif_ficha_pit_2 WHERE cod_ficha_calif_pit='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	
		//3.- Promediamos
		//Campo
		$sql="SELECT AVG(clar_calif_ficha_pit_2.total_puntaje) AS puntaje
		FROM clar_calif_ficha_pit_2
		WHERE clar_calif_ficha_pit_2.cod_ficha_pit_clar='$iniciativa'";
		$result=mysql_query($sql) or die (mysql_error());
		$r2=mysql_fetch_array($result);
		
		//CLAR
		$sql="SELECT AVG(clar_calif_ficha_pit_clar_2.total_puntaje) AS puntaje
		FROM clar_calif_ficha_pit_clar_2
		WHERE clar_calif_ficha_pit_clar_2.cod_ficha_pit_clar='$iniciativa'";
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
		
		//4.- Actualizo el PIT
		$sql="UPDATE pit_bd_ficha_pit SET calificacion_2='$puntaje',cod_estado_iniciativa='$estado' WHERE cod_pit='".$r1['cod_pit']."'";
		$result=mysql_query($sql) or die (mysql_error());
	
	
		//5.- Redirecciono
		echo "<script>window.location ='calif_clar_pit_seg.php?SES=$SES&anio=$anio&id=$id'</script>";		
}
?>