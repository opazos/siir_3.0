<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

if ($action==ADD)
{
	//Ubico el codigo POA
	$sql="SELECT sys_bd_subactividad_poa.cod
	FROM sys_bd_subactividad_poa
	WHERE sys_bd_subactividad_poa.codigo LIKE '3.1.2.2.' AND
	sys_bd_subactividad_poa.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r3=mysql_fetch_array($result);
	
	$poa=$r3['cod'];
	
	
	
	$sql="INSERT INTO clar_bd_evento_clar VALUES('','7','','',UPPER('".$_POST['nombre']."'),'".$_POST['f_inicio']."','".$_POST['f_termino']."','".$_POST['f_evento']."','".$_POST['select1']."','".$_POST['select2']."','".$_POST['select3']."',UPPER('".$_POST['lugar']."'),'".$row['cod_dependencia']."','".$_POST['tipo']."','3','$poa',UPPER('".$_POST['objetivo']."'),UPPER('".$_POST['resultado']."'),'".$_POST['premio']."','".$_POST['n_pit']."','$fecha_hoy','".$_POST['tipo_clar']."','".$_POST['concurso_1']."','".$_POST['concurso_2']."','".$_POST['concurso_3']."','".$_POST['concurso_4']."','0','".$_POST['fte_fida']."','".$_POST['fte_ro']."')";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Ubico el registro generado
	$sql="SELECT * FROM clar_bd_evento_clar ORDER BY cod_clar DESC LIMIT 0,1";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$codigo=$r1['cod_clar'];
	
	//3.- Obtengo los datos de la Organizacion
	$organizacion=$_POST['contratante'];
	$dato=explode(",",$organizacion);
	$tipo_documento=$dato[0];
	$n_documento=$dato[1];	
	
	
	//4.- Si el caso amerita se genera un contrato
	if($organizacion<>NULL)
	{
		//4.1.- Ubicamos el codigo de oficina
		$sql="SELECT sys_bd_numera_dependencia.cod
		FROM sys_bd_numera_dependencia
		WHERE sys_bd_numera_dependencia.cod_dependencia='".$row['cod_dependencia']."' AND
		sys_bd_numera_dependencia.periodo='$anio'";
		$result=mysql_query($sql) or die (mysql_error());
		$r2=mysql_fetch_array($result);
		
		//4.2.- Actualizamos numeracion
		$sql="UPDATE sys_bd_numera_dependencia SET n_contrato_clar='".$_POST['n_contrato']."',n_atf_clar='".$_POST['n_atf']."' WHERE cod='".$r2['cod']."'";
		$result=mysql_query($sql) or die (mysql_error());
		
		//4.3.- Ingresamos la informacion del contrato
		$sql="INSERT INTO clar_bd_ficha_contratante VALUES('','$tipo_documento','$n_documento','".$_POST['dni']."',UPPER('".$_POST['nombres']."'),UPPER('".$_POST['cargo']."'),'','','','1','".$_POST['n_cuenta']."','".$_POST['ifi']."','1','$codigo')";
		$result=mysql_query($sql) or die (mysql_error());
		
		//4.4.- actualizo la informacion del CLAR
		$sql="UPDATE clar_bd_evento_clar SET n_contrato='".$_POST['n_contrato']."',n_atf='".$_POST['n_atf']."' WHERE cod_clar='$codigo'";
		$result=mysql_query($sql) or die (mysql_error());
	}
	
	//5.- Redirecciono
	echo "<script>window.location ='n_clar.php?SES=$SES&anio=$anio&id=$codigo&modo=jurado'</script>";
}
elseif($action==ADD_JURADO)
{
	for($i=0;$i<=10;$i++)
	{
		if($_POST['jurado'][$i])
		{
			$sql="INSERT INTO clar_bd_jurado_evento_clar VALUES('','008','".$_POST['jurado'][$i]."','".$_POST['cargo'][$i]."','".$_POST['calif_campo'][$i]."','".$_POST['calif_clar'][$i]."','$id')";
			$result=mysql_query($sql) or die (mysql_error());
		}
	}
	//2.- Redirecciono
	echo "<script>window.location ='n_clar.php?SES=$SES&anio=$anio&id=$id&modo=jurado'</script>";
}
elseif($action==DELETE_JURADO)
{
	$sql="DELETE FROM clar_bd_jurado_evento_clar WHERE cod_jurado='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Redirecciono
	echo "<script>window.location ='n_clar.php?SES=$SES&anio=$anio&id=$id&modo=jurado'</script>";
}

elseif($action==ADD_PRESUPUESTO)
{
	$total=$_POST['cantidad']*$_POST['costo'];
	
	$sql="INSERT INTO clar_bd_ficha_presupuesto VALUES('','".$_POST['concepto']."','".$_POST['financia']."',UPPER('".$_POST['detalle']."'),UPPER('".$_POST['unidad']."'),'".$_POST['cantidad']."','".$_POST['costo']."','$total','".$_POST['requerido']."','$id')";
	$result=mysql_query($sql) or die (mysql_error());
	
	echo "<script>window.location ='n_clar.php?SES=$SES&anio=$anio&id=$id&modo=costo'</script>";
	
}
elseif($action==UPDATE)
{
	//Ubico el codigo POA
	$sql="SELECT sys_bd_subactividad_poa.cod
	FROM sys_bd_subactividad_poa
	WHERE sys_bd_subactividad_poa.codigo LIKE '3.1.2.2.' AND
	sys_bd_subactividad_poa.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r3=mysql_fetch_array($result);
	
	$poa=$r3['cod'];
	
	$sql="UPDATE clar_bd_evento_clar SET nombre=UPPER('".$_POST['nombre']."'),f_campo1='".$_POST['f_inicio']."',f_campo2='".$_POST['f_termino']."',f_evento='".$_POST['f_evento']."',cod_dep='".$_POST['select1']."',cod_prov='".$_POST['select2']."',cod_dist='".$_POST['select3']."',lugar=UPPER('".$_POST['lugar']."'),cod_tipo_clar='".$_POST['tipo']."',cod_componente='3',cod_subatividad='$poa',objetivo=UPPER('".$_POST['objetivo']."'),resultado=UPPER('".$_POST['resultado']."'),premio='".$_POST['premio']."',ganadores='".$_POST['n_pit']."',tipo_evento='".$_POST['tipo_clar']."',concurso_danza='".$_POST['concurso_1']."',concurso_comida='".$_POST['concurso_2']."',concurso_pdn='".$_POST['concurso_3']."',concurso_mapa='".$_POST['concurso_4']."',fte_fida='".$_POST['fte_fida']."',fte_ro='".$_POST['fte_ro']."' WHERE cod_clar='".$_POST['codigo']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	$codigo=$_POST['codigo'];
	
	//2.- Verificamos si existe contratante, si es asi actualizamos
	if ($_POST['codigo_contratante']<>NULL)
	{
		$sql="UPDATE clar_bd_ficha_contratante SET dni_1='".$_POST['dni']."',representante_1=UPPER('".$_POST['nombres']."'),cargo_1=UPPER('".$_POST['cargo']."'),n_cuenta='".$_POST['n_cuenta']."',cod_ifi='".$_POST['ifi']."' WHERE cod_ficha_contratante='".$_POST['codigo_contratante']."'";
		$result=mysql_query($sql) or die (mysql_error());
	}
	
	echo "<script>window.location ='n_clar.php?SES=$SES&anio=$anio&id=$codigo&modo=jurado'</script>";
	
}
elseif($action==DELETE_PRESUPUESTO)
{
	$sql="DELETE FROM clar_bd_ficha_presupuesto WHERE cod_presupuesto='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	
	echo "<script>window.location ='n_clar.php?SES=$SES&anio=$anio&id=$id&modo=costo'</script>";
}

?>