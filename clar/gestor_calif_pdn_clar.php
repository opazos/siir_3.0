<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ADD and $modo==PRIMERO_CAMPO)
{
	if($_POST['iniciativa']==NULL or $_POST['jurado']==NULL)
	{
		echo "<script>window.location ='calif_campo_pdn_prim.php?SES=$SES&anio=$anio&id=$id&error=vacio'</script>";
	}
	else
	{		
	//1.-Ingreso la información de Plan de negocio	
	$total=($_POST['p1']+$_POST['p2']+$_POST['p3']+$_POST['p4']+$_POST['p5']+$_POST['p6']+$_POST['p7']+$_POST['p8']+$_POST['p9'])*2;
	
	$sql="INSERT INTO clar_calif_ficha_pdn VALUES('','".$_POST['jurado']."','".$_POST['p1']."','".$_POST['p2']."','".$_POST['p3']."','".$_POST['p4']."','".$_POST['p5']."','".$_POST['p6']."','".$_POST['p7']."','".$_POST['p8']."','".$_POST['p9']."','$total','".$_POST['iniciativa']."')";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Ubico el Plan de Negocio
	$sql="SELECT clar_bd_ficha_pdn.cod_pdn
	FROM clar_bd_ficha_pdn
	WHERE clar_bd_ficha_pdn.cod_ficha_pdn_clar='".$_POST['iniciativa']."'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	//3.- Promedio los puntajes
	//3.1 Campo
	$sql="SELECT AVG(clar_calif_ficha_pdn.total_puntaje) AS puntaje
	FROM clar_calif_ficha_pdn
	WHERE clar_calif_ficha_pdn.cod_ficha_pdn_clar='".$_POST['iniciativa']."'";
	$result=mysql_query($sql) or die (mysql_error());
	$r2=mysql_fetch_array($result);
	
	//3.2 CLAR
	$sql="SELECT AVG(clar_calif_ficha_pdn_clar.total_puntaje) AS puntaje
	FROM clar_calif_ficha_pdn_clar
	WHERE clar_calif_ficha_pdn_clar.cod_ficha_pdn_clar='".$_POST['iniciativa']."'";
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
	echo "<script>window.location ='calif_campo_pdn_prim.php?SES=$SES&anio=$anio&id=$id'</script>";
	}
}
elseif($action==ADD and $modo==PRIMERO_CLAR)
{
	if($_POST['iniciativa']==NULL or $_POST['jurado']==NULL)
	{
		echo "<script>window.location ='calif_clar_pdn_prim.php?SES=$SES&anio=$anio&id=$id&error=vacio'</script>";
	}
	else
	{
		//1.- Registro la información de Puntajes
		
		$total=$_POST['p1']+$_POST['p2']+$_POST['p3']+$_POST['p4']+$_POST['p5'];
		
		$sql="INSERT INTO clar_calif_ficha_pdn_clar VALUES('','".$_POST['jurado']."','".$_POST['p1']."','".$_POST['p2']."','".$_POST['p3']."','".$_POST['p4']."','".$_POST['p5']."','$total','".$_POST['iniciativa']."')";
		$result=mysql_query($sql) or die (mysql_error());
		
	//2.- Ubico el Plan de Negocio
	$sql="SELECT clar_bd_ficha_pdn.cod_pdn
	FROM clar_bd_ficha_pdn
	WHERE clar_bd_ficha_pdn.cod_ficha_pdn_clar='".$_POST['iniciativa']."'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	//3.- Promedio los puntajes
	//3.1 Campo
	$sql="SELECT AVG(clar_calif_ficha_pdn.total_puntaje) AS puntaje
	FROM clar_calif_ficha_pdn
	WHERE clar_calif_ficha_pdn.cod_ficha_pdn_clar='".$_POST['iniciativa']."'";
	$result=mysql_query($sql) or die (mysql_error());
	$r2=mysql_fetch_array($result);
	
	//3.2 CLAR
	$sql="SELECT AVG(clar_calif_ficha_pdn_clar.total_puntaje) AS puntaje
	FROM clar_calif_ficha_pdn_clar
	WHERE clar_calif_ficha_pdn_clar.cod_ficha_pdn_clar='".$_POST['iniciativa']."'";
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
	echo "<script>window.location ='calif_clar_pdn_prim.php?SES=$SES&anio=$anio&id=$id'</script>";

	}
	
}
elseif($action==ADD and $modo==SEGUNDO_CAMPO)
{
	if($_POST['iniciativa']==NULL or $_POST['jurado']==NULL)
	{
		echo "<script>window.location ='calif_campo_pdn_seg.php?SES=$SES&anio=$anio&id=$id&error=vacio'</script>";
	}
	else
	{
		//1.- Ingreso los puntajes
		$total=($_POST['p1']+$_POST['p2']+$_POST['p3']+$_POST['p4']+$_POST['p5']+$_POST['p6']+$_POST['p7']+$_POST['p8']+$_POST['p9']+$_POST['p10']+$_POST['p11'])*2;
		
		$sql="INSERT INTO clar_calif_ficha_pdn_2 VALUES('','".$_POST['jurado']."','".$_POST['p1']."','".$_POST['p2']."','".$_POST['p3']."','".$_POST['p4']."','".$_POST['p5']."','".$_POST['p6']."','".$_POST['p7']."','".$_POST['p8']."','".$_POST['p9']."','".$_POST['p10']."','".$_POST['p11']."','$total','".$_POST['iniciativa']."')";
		$result=mysql_query($sql) or die (mysql_error());
		
		//2.- Busco el PDN
		$sql="SELECT clar_bd_ficha_pdn_2.cod_pdn
		FROM clar_bd_ficha_pdn_2		
		WHERE clar_bd_ficha_pdn_2.cod_ficha_pdn_clar='".$_POST['iniciativa']."'";
		$result=mysql_query($sql) or die (mysql_error());
		$r1=mysql_fetch_array($result);
		
		//3.- Promediamos
		//Campo
		$sql="SELECT AVG(clar_calif_ficha_pdn_2.total_puntaje) AS puntaje
		FROM clar_calif_ficha_pdn_2
		WHERE clar_calif_ficha_pdn_2.cod_ficha_pdn_clar='".$_POST['iniciativa']."'";
		$result=mysql_query($sql) or die (mysql_error());
		$r2=mysql_fetch_array($result);
		
		//CLAR
		$sql="SELECT AVG(clar_calif_ficha_pdn_clar_2.total_puntaje) AS puntaje
		FROM clar_calif_ficha_pdn_clar_2
		WHERE clar_calif_ficha_pdn_clar_2.cod_ficha_pdn_clar='".$_POST['iniciativa']."'";
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
		
	//4.- Actualizo el Plan de negocio
	$sql="UPDATE pit_bd_ficha_pdn SET calificacion_2='$puntaje',cod_estado_iniciativa='$estado' WHERE cod_pdn='".$r1['cod_pdn']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//5.- Redirecciono
	echo "<script>window.location ='calif_campo_pdn_seg.php?SES=$SES&anio=$anio&id=$id'</script>";
		
	}
}
elseif($action==ADD and $modo==SEGUNDO_CLAR)
{
	if($_POST['iniciativa']==NULL or $_POST['jurado']==NULL)
	{
		echo "<script>window.location ='calif_clar_pdn_seg.php?SES=$SES&anio=$anio&id=$id&error=vacio'</script>";
	}
	else
	{
		//1.- Verifico Puntajes
		$total=$_POST['p1']+$_POST['p2']+$_POST['p3']+$_POST['p4']+$_POST['p5']+$_POST['p6'];
		
		$sql="INSERT INTO clar_calif_ficha_pdn_clar_2 VALUES('','".$_POST['jurado']."','".$_POST['p1']."','".$_POST['p2']."','".$_POST['p3']."','".$_POST['p4']."','".$_POST['p5']."','".$_POST['p6']."','$total','".$_POST['iniciativa']."')";
		$result=mysql_query($sql) or die (mysql_error());
		
		//2.- Busco el PDN
		$sql="SELECT clar_bd_ficha_pdn_2.cod_pdn
		FROM clar_bd_ficha_pdn_2		
		WHERE clar_bd_ficha_pdn_2.cod_ficha_pdn_clar='".$_POST['iniciativa']."'";
		$result=mysql_query($sql) or die (mysql_error());
		$r1=mysql_fetch_array($result);
		
		//3.- Promediamos
		//Campo
		$sql="SELECT AVG(clar_calif_ficha_pdn_2.total_puntaje) AS puntaje
		FROM clar_calif_ficha_pdn_2
		WHERE clar_calif_ficha_pdn_2.cod_ficha_pdn_clar='".$_POST['iniciativa']."'";
		$result=mysql_query($sql) or die (mysql_error());
		$r2=mysql_fetch_array($result);
		
		//CLAR
		$sql="SELECT AVG(clar_calif_ficha_pdn_clar_2.total_puntaje) AS puntaje
		FROM clar_calif_ficha_pdn_clar_2
		WHERE clar_calif_ficha_pdn_clar_2.cod_ficha_pdn_clar='".$_POST['iniciativa']."'";
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
		
	//4.- Actualizo el Plan de negocio
	$sql="UPDATE pit_bd_ficha_pdn SET calificacion_2='$puntaje',cod_estado_iniciativa='$estado' WHERE cod_pdn='".$r1['cod_pdn']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//5.- Redirecciono
	echo "<script>window.location ='calif_clar_pdn_seg.php?SES=$SES&anio=$anio&id=$id'</script>";

	}
}
elseif($action==DELETE and $modo==PRIMERO_CAMPO)
{
	$sql="SELECT clar_calif_ficha_pdn.cod_ficha_pdn_clar, 
	clar_bd_ficha_pdn.cod_pdn
	FROM clar_bd_ficha_pdn INNER JOIN clar_calif_ficha_pdn ON clar_bd_ficha_pdn.cod_ficha_pdn_clar = clar_calif_ficha_pdn.cod_ficha_pdn_clar
	WHERE clar_calif_ficha_pdn.cod_ficha_calif_pdn='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$iniciativa=$r1['cod_ficha_pdn_clar'];
	
	//2.- Elimino
	$sql="DELETE FROM clar_calif_ficha_pdn WHERE cod_ficha_calif_pdn='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//3.- Promedio los puntajes
	//3.1 Campo
	$sql="SELECT AVG(clar_calif_ficha_pdn.total_puntaje) AS puntaje
	FROM clar_calif_ficha_pdn
	WHERE clar_calif_ficha_pdn.cod_ficha_pdn_clar='$iniciativa'";
	$result=mysql_query($sql) or die (mysql_error());
	$r2=mysql_fetch_array($result);
	
	//3.2 CLAR
	$sql="SELECT AVG(clar_calif_ficha_pdn_clar.total_puntaje) AS puntaje
	FROM clar_calif_ficha_pdn_clar
	WHERE clar_calif_ficha_pdn_clar.cod_ficha_pdn_clar='$iniciativa'";
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
	echo "<script>window.location ='calif_campo_pdn_prim.php?SES=$SES&anio=$anio&id=$id'</script>";
}
elseif($action==DELETE and $modo==PRIMERO_CLAR)
{
	$sql="SELECT clar_calif_ficha_pdn_clar.cod_ficha_pdn_clar, 
	clar_bd_ficha_pdn.cod_pdn
	FROM clar_bd_ficha_pdn INNER JOIN clar_calif_ficha_pdn_clar ON clar_bd_ficha_pdn.cod_ficha_pdn_clar = clar_calif_ficha_pdn_clar.cod_ficha_pdn_clar
	WHERE clar_calif_ficha_pdn_clar.cod_ficha_calif_pdn='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);

	$iniciativa=$r1['cod_ficha_pdn_clar'];

	//2.- elimino
	$sql="DELETE FROM clar_calif_ficha_pdn_clar WHERE cod_ficha_calif_pdn='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//3.- Promedio los puntajes
	//3.1 Campo
	$sql="SELECT AVG(clar_calif_ficha_pdn.total_puntaje) AS puntaje
	FROM clar_calif_ficha_pdn
	WHERE clar_calif_ficha_pdn.cod_ficha_pdn_clar='$iniciativa'";
	$result=mysql_query($sql) or die (mysql_error());
	$r2=mysql_fetch_array($result);
	
	//3.2 CLAR
	$sql="SELECT AVG(clar_calif_ficha_pdn_clar.total_puntaje) AS puntaje
	FROM clar_calif_ficha_pdn_clar
	WHERE clar_calif_ficha_pdn_clar.cod_ficha_pdn_clar='$iniciativa'";
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
	echo "<script>window.location ='calif_clar_pdn_prim.php?SES=$SES&anio=$anio&id=$id'</script>";
}
elseif($action==DELETE and $modo==SEGUNDO_CAMPO)
{
	$sql="SELECT clar_calif_ficha_pdn_2.cod_ficha_pdn_clar, 
	clar_bd_ficha_pdn_2.cod_pdn
	FROM clar_bd_ficha_pdn_2 INNER JOIN clar_calif_ficha_pdn_2 ON clar_bd_ficha_pdn_2.cod_ficha_pdn_clar = clar_calif_ficha_pdn_2.cod_ficha_pdn_clar
	WHERE clar_calif_ficha_pdn_2.cod_ficha_calif_pdn='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$iniciativa=$r1['cod_ficha_pdn_clar'];
	
	//2.- Borrar
	$sql="DELETE FROM clar_calif_ficha_pdn_2 WHERE cod_ficha_calif_pdn='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//3.- Promedio los puntajes
	//3.1 Campo
	$sql="SELECT AVG(clar_calif_ficha_pdn.total_puntaje) AS puntaje
	FROM clar_calif_ficha_pdn
	WHERE clar_calif_ficha_pdn.cod_ficha_pdn_clar='$iniciativa'";
	$result=mysql_query($sql) or die (mysql_error());
	$r2=mysql_fetch_array($result);
	
	//3.2 CLAR
	$sql="SELECT AVG(clar_calif_ficha_pdn_clar.total_puntaje) AS puntaje
	FROM clar_calif_ficha_pdn_clar
	WHERE clar_calif_ficha_pdn_clar.cod_ficha_pdn_clar='$iniciativa'";
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
		
	//4.- Actualizo el Plan de negocio
	$sql="UPDATE pit_bd_ficha_pdn SET calificacion_2='$puntaje',cod_estado_iniciativa='$estado' WHERE cod_pdn='".$r1['cod_pdn']."'";
	$result=mysql_query($sql) or die (mysql_error());

	//5.- Redirecciono
	echo "<script>window.location ='calif_campo_pdn_seg.php?SES=$SES&anio=$anio&id=$id'</script>";	
}
elseif($action==DELETE and $modo==SEGUNDO_CLAR)
{
	$sql="SELECT clar_calif_ficha_pdn_clar_2.cod_ficha_pdn_clar, 
	clar_bd_ficha_pdn_2.cod_pdn
	FROM clar_bd_ficha_pdn_2 INNER JOIN clar_calif_ficha_pdn_clar_2 ON clar_bd_ficha_pdn_2.cod_ficha_pdn_clar = clar_calif_ficha_pdn_clar_2.cod_ficha_pdn_clar
	WHERE clar_calif_ficha_pdn_clar_2.cod_ficha_calif_pdn='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$iniciativa=$r1['cod_ficha_pdn_clar'];
	
	//2.- Borrar
	$sql="DELETE FROM clar_calif_ficha_pdn_clar_2 WHERE cod_ficha_calif_pdn='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//3.- Promedio los puntajes
	//3.1 Campo
	$sql="SELECT AVG(clar_calif_ficha_pdn.total_puntaje) AS puntaje
	FROM clar_calif_ficha_pdn
	WHERE clar_calif_ficha_pdn.cod_ficha_pdn_clar='$iniciativa'";
	$result=mysql_query($sql) or die (mysql_error());
	$r2=mysql_fetch_array($result);
	
	//3.2 CLAR
	$sql="SELECT AVG(clar_calif_ficha_pdn_clar.total_puntaje) AS puntaje
	FROM clar_calif_ficha_pdn_clar
	WHERE clar_calif_ficha_pdn_clar.cod_ficha_pdn_clar='$iniciativa'";
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
		
	//4.- Actualizo el Plan de negocio
	$sql="UPDATE pit_bd_ficha_pdn SET calificacion_2='$puntaje',cod_estado_iniciativa='$estado' WHERE cod_pdn='".$r1['cod_pdn']."'";
	$result=mysql_query($sql) or die (mysql_error());

	//5.- Redirecciono
	echo "<script>window.location ='calif_clar_pdn_seg.php?SES=$SES&anio=$anio&id=$id'</script>";		
}


?>