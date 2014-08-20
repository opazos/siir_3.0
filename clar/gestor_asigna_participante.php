<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ADD_PIT)
{
	if ($_POST['iniciativa']==NULL)
	{
		
	}
	else
	{
	//1.- Ingreso la informacion
	$sql="INSERT INTO clar_bd_ficha_pit VALUES('','".$_POST['iniciativa']."','$id')";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Redirecciono
	echo "<script>window.location ='n_asigna_participante.php?SES=$SES&anio=$anio&id=$id&modo=pit'</script>";
	}
}
elseif($action==ADD_PGRN)
{
	if($_POST['iniciativa']==NULL)
	{
		
	}
	else
	{
		//1.- Busco el dato del PIT
		$sql="SELECT clar_bd_ficha_pit.cod_ficha_pit_clar
		FROM clar_bd_ficha_pit INNER JOIN pit_bd_ficha_mrn ON clar_bd_ficha_pit.cod_pit = pit_bd_ficha_mrn.cod_pit
		WHERE pit_bd_ficha_mrn.cod_mrn='".$_POST['iniciativa']."' AND
		clar_bd_ficha_pit.cod_clar='$id'";
		$result=mysql_query($sql) or die (mysql_error());
		$r1=mysql_fetch_array($result);
		
		//2.- Ingreso la información
		$sql="INSERT INTO clar_bd_ficha_mrn VALUES('','".$_POST['iniciativa']."','$id','".$r1['cod_ficha_pit_clar']."')";
		$result=mysql_query($sql) or die (mysql_error());
		
		//3.- Redirecciono
		echo "<script>window.location ='n_asigna_participante.php?SES=$SES&anio=$anio&id=$id&modo=pgrn'</script>";
		
	}
}
elseif($action==ADD_PDN)
{
	if($_POST['iniciativa']==NULL)
	{
		echo "<script>window.location ='n_asigna_participante.php?SES=$SES&anio=$anio&id=$id&modo=pdn&error=vacio'</script>";
	}
	else
	{
		
		//1.- Realizamos una busqueda para determinar el tipo de plan de negocio
		$sql="SELECT pit_bd_ficha_pdn.tipo, 
		pit_bd_ficha_pdn.cod_pit
		FROM pit_bd_ficha_pdn
		WHERE pit_bd_ficha_pdn.cod_pdn='".$_POST['iniciativa']."'";
		$result=mysql_query($sql) or die (mysql_error());
		$r1=mysql_fetch_array($result);
		
		$tipo_pdn=$r1['tipo'];

		
		$sql="SELECT clar_bd_ficha_pit.cod_ficha_pit_clar
		FROM clar_bd_ficha_pit INNER JOIN pit_bd_ficha_pdn ON clar_bd_ficha_pit.cod_pit = pit_bd_ficha_pdn.cod_pit
		WHERE pit_bd_ficha_pdn.cod_pdn='".$_POST['iniciativa']."' AND
		clar_bd_ficha_pit.cod_clar='$id'";
		$result=mysql_query($sql) or die (mysql_error());
		$r2=mysql_fetch_array($result);
		
		//2.- Segun el tipo de plan de negocio realizamos el guardado de la informacion
		if($tipo_pdn==0)
		{
			$sql="INSERT INTO clar_bd_ficha_pdn VALUES('','".$_POST['iniciativa']."','$id','".$r2['cod_ficha_pit_clar']."')";
			$result=mysql_query($sql) or die (mysql_error());
		}
		else
		{
			$sql="INSERT INTO clar_bd_ficha_pdn_suelto VALUES('','".$_POST['iniciativa']."','$id')";
			$result=mysql_query($sql) or die (mysql_error());
		}
		
		//3.- Redirecciono
		echo "<script>window.location ='n_asigna_participante.php?SES=$SES&anio=$anio&id=$id&modo=pdn'</script>";	
	}
}
elseif($action==ADD_PDN_IND)
{
		if($_POST['iniciativa']==NULL)
	{
		echo "<script>window.location ='n_asigna_participante.php?SES=$SES&anio=$anio&id=$id&modo=pdn_ind&error=vacio'</script>";
	}
	else
	{
		//1.- Realizamos una busqueda para determinar el tipo de plan de negocio
		$sql="SELECT pit_bd_ficha_pdn.tipo, 
		pit_bd_ficha_pdn.cod_pit
		FROM pit_bd_ficha_pdn
		WHERE pit_bd_ficha_pdn.cod_pdn='".$_POST['iniciativa']."'";
		$result=mysql_query($sql) or die (mysql_error());
		$r1=mysql_fetch_array($result);
		
		$tipo_pdn=$r1['tipo'];
		
		echo $tipo_pdn;
		
		$sql="SELECT clar_bd_ficha_pit.cod_ficha_pit_clar
		FROM clar_bd_ficha_pit INNER JOIN pit_bd_ficha_pdn ON clar_bd_ficha_pit.cod_pit = pit_bd_ficha_pdn.cod_pit
		WHERE pit_bd_ficha_pdn.cod_pdn='".$_POST['iniciativa']."' AND
		clar_bd_ficha_pit.cod_clar='$id'";
		$result=mysql_query($sql) or die (mysql_error());
		$r2=mysql_fetch_array($result);
		
		//2.- Segun el tipo de plan de negocio realizamos el guardado de la informacion
		if($tipo_pdn==0)
		{
			$sql="INSERT INTO clar_bd_ficha_pdn VALUES('','".$_POST['iniciativa']."','$id','".$r2['cod_ficha_pit_clar']."')";
			$result=mysql_query($sql) or die (mysql_error());
		}
		else
		{
			$sql="INSERT INTO clar_bd_ficha_pdn_suelto VALUES('','".$_POST['iniciativa']."','$id')";
			$result=mysql_query($sql) or die (mysql_error());
		}
		
		//3.- Redirecciono
		echo "<script>window.location ='n_asigna_participante.php?SES=$SES&anio=$anio&id=$id&modo=pdn_ind'</script>";	
	}
}
elseif($action==ADD_IDL)
{
	if($_POST['iniciativa']==NULL)
	{
		echo "<script>window.location ='n_asigna_participante.php?SES=$SES&anio=$anio&id=$id&modo=idl&error=vacio'</script>";
	}
	else
	{
		//1.- realizamos el registro
		$sql="INSERT INTO clar_bd_ficha_idl VALUES('','".$_POST['iniciativa']."','$id')";
		$result=mysql_query($sql) or die (mysql_error());
		
		//2.- Redireccionamos
		echo "<script>window.location ='n_asigna_participante.php?SES=$SES&anio=$anio&id=$id&modo=idl'</script>";
	}
}
elseif($action==ADD_PIT_2)
{
	if($_POST['iniciativa']==NULL)
	{
		echo "<script>window.location ='n_asigna_participante.php?SES=$SES&anio=$anio&id=$id&modo=pit_2&error=vacio'</script>";
	}
	else
	{
		//1.- Registramos el registro de la info
		$sql="INSERT INTO clar_bd_ficha_pit_2 VALUES('','".$_POST['iniciativa']."','$id')";
		$result=mysql_query($sql) or die (mysql_error());
		
		//2.-Redireccionamos
		echo "<script>window.location ='n_asigna_participante.php?SES=$SES&anio=$anio&id=$id&modo=pit_2'</script>";
	}
}
elseif($action==ADD_PGRN_2)
{
	if($_POST['iniciativa']==NULL)
	{
		echo "<script>window.location ='n_asigna_participante.php?SES=$SES&anio=$anio&id=$id&modo=pgrn_2&error=vacio'</script>";
	}
	else
	{
		//1.- Registramos la info
		$sql="INSERT INTO clar_bd_ficha_mrn_2 VALUES('','".$_POST['iniciativa']."','$id')";
		$result=mysql_query($sql) or die (mysql_error());
		
		//2.- Redirecciono
		echo "<script>window.location ='n_asigna_participante.php?SES=$SES&anio=$anio&id=$id&modo=pgrn_2'</script>";
	}
}
elseif($action==ADD_PDN_2)
{
	if($_POST['iniciativa']==NULL)
	{
		echo "<script>window.location ='n_asigna_participante.php?SES=$SES&anio=$anio&id=$id&modo=pdn_2&error=vacio'</script>";
	}
	else
	{
		//1.- Registramos la info
		$sql="INSERT INTO clar_bd_ficha_pdn_2 VALUES('','".$_POST['iniciativa']."','$id')";
		$result=mysql_query($sql) or die (mysql_error());
		
		//2.- Redirecciono
		echo "<script>window.location ='n_asigna_participante.php?SES=$SES&anio=$anio&id=$id&modo=pdn_2'</script>";		
	}
}
elseif($action==ADD_IDL_2)
{
	if($_POST['iniciativa']==NULL)
	{
		echo "<script>window.location ='n_asigna_participante.php?SES=$SES&anio=$anio&id=$id&modo=idl_2&error=vacio'</script>";
	}
	else
	{
		//1.- Registro la info
		$sql="INSERT INTO clar_bd_ficha_idl_2 VALUES('','".$_POST['iniciativa']."','$id')";
		$result=mysql_query($sql) or die (mysql_error());
		
		//2.- Redireccionamos
		echo "<script>window.location ='n_asigna_participante.php?SES=$SES&anio=$anio&id=$id&modo=idl_2'</script>";			
	}
}

//MODO DELETE

if ($action==DELETE_PIT)
{
	//1.- Busco el codigo del PIT
	$sql="SELECT clar_bd_ficha_pit.cod_pit
	FROM clar_bd_ficha_pit
	WHERE clar_bd_ficha_pit.cod_ficha_pit_clar='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$iniciativa=$r1['cod_pit'];
	
	//2.- Elimino el registro
	$sql="DELETE FROM clar_bd_ficha_pit WHERE cod_ficha_pit_clar='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//3.- Actualizo el estado situacional
	$sql="UPDATE pit_bd_ficha_pit SET cod_estado_iniciativa='001' WHERE cod_pit='$iniciativa'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//4.- Redirecciono
	echo "<script>window.location ='n_asigna_participante.php?SES=$SES&anio=$anio&id=$id&modo=pit'</script>";	
}
elseif($action==DELETE_PGRN)
{
	//1.- Busco el codigo del PGRN
	$sql="SELECT clar_bd_ficha_mrn.cod_mrn
	FROM clar_bd_ficha_mrn
	WHERE clar_bd_ficha_mrn.cod_ficha_mrn_clar='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$iniciativa=$r1['cod_mrn'];
	
	//2.- Elimino el registro
	$sql="DELETE FROM clar_bd_ficha_mrn WHERE cod_ficha_mrn_clar='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//3.- Actualizo el estado situacional
	$sql="UPDATE pit_bd_ficha_mrn SET cod_estado_iniciativa='001' WHERE cod_mrn='$iniciativa'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//4.- Redirecciono
	echo "<script>window.location ='n_asigna_participante.php?SES=$SES&anio=$anio&id=$id&modo=pgrn'</script>";		
}
elseif($action==DELETE_PDN)
{
	//1.- Busco el codigo del PDN
	$sql="SELECT clar_bd_ficha_pdn.cod_pdn
	FROM clar_bd_ficha_pdn
	WHERE clar_bd_ficha_pdn.cod_ficha_pdn_clar='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$iniciativa=$r1['cod_pdn'];
	
	//2.- Elimino el registro
	$sql="DELETE FROM clar_bd_ficha_pdn WHERE cod_ficha_pdn_clar='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//3.- Actualizo el estado situacional
	$sql="UPDATE pit_bd_ficha_pdn SET cod_estado_iniciativa='001' WHERE cod_pdn='$iniciativa'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//4.- Redirecciono
	echo "<script>window.location ='n_asigna_participante.php?SES=$SES&anio=$anio&id=$id&modo=pdn'</script>";	
}
elseif($action==DELETE_PDN_IND)
{
	//1.- Busco el codigo del PDN
	$sql="SELECT clar_bd_ficha_pdn_suelto.cod_pdn
	FROM clar_bd_ficha_pdn_suelto
	WHERE clar_bd_ficha_pdn_suelto.cod_ficha_pdn_clar='$cod'";
	$result=mysql_query($sql) or die(mysql_error());
	$r1=mysql_fetch_array($result);
	
	$iniciativa=$r1['cod_pdn'];

	//2.- Elimino el registro
	$sql="DELETE FROM clar_bd_ficha_pdn_suelto WHERE cod_ficha_pdn_clar='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//3.- Actualizo el estado situacional
	$sql="UPDATE pit_bd_ficha_pdn SET cod_estado_iniciativa='001' WHERE cod_pdn='$iniciativa'";	
	$result=mysql_query($sql) or die (mysql_error());
	
	//4.- Redirecciono
	echo "<script>window.location ='n_asigna_participante.php?SES=$SES&anio=$anio&id=$id&modo=pdn_ind'</script>";		
}
elseif($action==DELETE_IDL)
{
	//1.- Busco el codigo de la IDL
	$sql="SELECT clar_bd_ficha_idl.cod_idl
	FROM clar_bd_ficha_idl
	WHERE clar_bd_ficha_idl.cod_ficha_idl_clar='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$iniciativa=$r1['cod_idl'];
	
	//2.- Eliminar el registro
	$sql="DELETE FROM clar_bd_ficha_idl WHERE cod_ficha_idl_clar='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//3.- Actualizo el estado situacional
	$sql="UPDATE pit_bd_ficha_idl SET cod_estado_iniciativa='001' WHERE cod_ficha_idl='$iniciativa'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//4.- Redirecciono
	echo "<script>window.location ='n_asigna_participante.php?SES=$SES&anio=$anio&id=$id&modo=idl'</script>";		
}
elseif($action==DELETE_PIT_2)
{
	//1.- Busco el codigo del PIT
	$sql="SELECT clar_bd_ficha_pit_2.cod_pit
	FROM clar_bd_ficha_pit_2
	WHERE clar_bd_ficha_pit_2.cod_ficha_pit_clar='$cod'";
	$result=mysql_query($sql) or die(mysql_error());
	$r1=mysql_fetch_array($result);
	
	$iniciativa=$r1['cod_pit'];
	
	//2.- Elimino el registro
	$sql="DELETE FROM clar_bd_ficha_pit_2 WHERE cod_ficha_pit_clar='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//3.- Actualizo el estado situacional
	$sql="UPDATE pit_bd_ficha_pit SET cod_estado_iniciativa='006' WHERE cod_pit='$iniciativa'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//4.- Redirecciono
	echo "<script>window.location ='n_asigna_participante.php?SES=$SES&anio=$anio&id=$id&modo=pit_2'</script>";	
}
elseif($action==DELETE_PGRN_2)
{
	//1.- Busco el codigo del MRN
	$sql="SELECT clar_bd_ficha_mrn_2.cod_mrn
	FROM clar_bd_ficha_mrn_2
	WHERE clar_bd_ficha_mrn_2.cod_ficha_mrn_clar='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$iniciativa=$r1['cod_mrn'];
	
	//2.- Elimino el registro
	$sql="DELETE FROM clar_bd_ficha_mrn_2 WHERE cod_ficha_mrn_clar='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//3.- Actualizo el estado situacional
	$sql="UPDATE pit_bd_ficha_mrn SET cod_estado_iniciativa='006' WHERE cod_mrn='$iniciativa'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//4.- Redirecciono
	echo "<script>window.location ='n_asigna_participante.php?SES=$SES&anio=$anio&id=$id&modo=pgrn_2'</script>";		
}
elseif($action==DELETE_PDN_2)
{
	//1.- Busco el codigo del PDN
	$sql="SELECT clar_bd_ficha_pdn_2.cod_pdn
	FROM clar_bd_ficha_pdn_2
	WHERE clar_bd_ficha_pdn_2.cod_ficha_pdn_clar='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$iniciativa=$r1['cod_pdn'];
	
	//2.- Elimino el registro
	$sql="DELETE FROM clar_bd_ficha_pdn_2 WHERE cod_ficha_pdn_clar='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//3.- Actualizo el estado situacional
	$sql="UPDATE pit_bd_ficha_pdn SET cod_estado_iniciativa='006' WHERE cod_pdn='$iniciativa'";
	$result=mysql_query($sql) or die (mysql_error());
	
	
	//4.- Redirecciono
	echo "<script>window.location ='n_asigna_participante.php?SES=$SES&anio=$anio&id=$id&modo=pdn_2'</script>";		
}
elseif($action==DELETE_IDL_2)
{
	//1.- Busco el codigo del PDN
	$sql="SELECT clar_bd_ficha_idl_2.cod_idl
	FROM clar_bd_ficha_idl_2
	WHERE clar_bd_ficha_idl_2.cod_ficha_idl_clar='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$iniciativa=$r1['cod_idl'];
	
	//2.- Elimino el registro
	$sql="DELETE FROM clar_bd_ficha_idl_2 WHERE cod_ficha_idl_clar='$cod'";
	$result=mysql_query($sql) or die(mysql_error());
	
	//3.- Actualizo el estado situacional
	$sql="UPDATE pit_bd_ficha_idl SET cod_estado_iniciativa='006' WHERE cod_ficha_idl='$iniciativa'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//4.- Redirecciono
	echo "<script>window.location ='n_asigna_participante.php?SES=$SES&anio=$anio&id=$id&modo=idl_2'</script>";	
	
}



?>