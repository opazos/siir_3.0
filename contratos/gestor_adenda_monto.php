<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

if($action==ADD)
{
	//1.- Buscar la numeracion
	$sql="SELECT pit_bd_ficha_adenda.cod_adenda
	FROM pit_bd_ficha_adenda
	WHERE pit_bd_ficha_adenda.cod_pit='".$_POST['pit']."'";
	$result=mysql_query($sql) or die (mysql_error());
	$total=mysql_num_rows($result);
	
	$n_adenda=$total+1;
	
	//2.- Calculo fecha de termino
	$fecha=$_POST['f_adenda'];
	$mes=$_POST['mes'];

	$fecha_actualizada = dateadd1($fecha,7,0,0,0,0,0); // suma 7 dias a la fecha dada
	$f_termino=dateadd1($fecha,5,$mes,0,0,0,0);
	
	if ($f_termino>='2014-09-15')
	{
		$f_termino='2014-09-15';
	}
	else
	{
		$f_termino=$f_termino;
	}
	
	//3.- Procedo a ingresar la información al sistema
	$sql="INSERT INTO pit_bd_ficha_adenda VALUES('','$n_adenda','".$_POST['f_adenda']."','".$_POST['pit']."',UPPER('".$_POST['referencia']."'),'2','".$_POST['f_inicio']."','".$_POST['mes']."','$f_termino','','','','','','002' )";
	$result=mysql_query($sql) or die (mysql_error());

	//4.- Procedo a buscar el ultimo registro generado
	$sql="SELECT * FROM pit_bd_ficha_adenda ORDER BY cod_adenda DESC LIMIT 0,1";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$codigo=$r1['cod_adenda'];
	
	if ($_POST['mes']<4)
	{
		$mes=$_POST['mes'];
	}
	else
	{
		$mes=4;
	}
	
	$total_animador=$_POST['n_animador']*$_POST['monto_animador']*$mes;
	
	
	//5.- Busco el componente y POA
	$sql="SELECT sys_bd_subactividad_poa.cod AS poa, 
	sys_bd_componente_poa.cod AS componente
FROM sys_bd_actividad_poa INNER JOIN sys_bd_subactividad_poa ON sys_bd_actividad_poa.cod = sys_bd_subactividad_poa.relacion
	 INNER JOIN sys_bd_subcomponente_poa ON sys_bd_subcomponente_poa.cod = sys_bd_actividad_poa.relacion
	 INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = sys_bd_subcomponente_poa.relacion
WHERE sys_bd_subactividad_poa.codigo LIKE '3.1.2.7.' AND
sys_bd_subactividad_poa.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r2=mysql_fetch_array($result);
	
	//6.- Procedo a Registrar el PIT
	$sql="INSERT INTO pit_adenda_pit VALUES('','".$_POST['pit']."','".$_POST['n_animador']."','".$_POST['monto_animador']."','".$_POST['mes']."','$total_animador','$total_animador','0','','".$r2['componente']."','".$r2['poa']."','002','$codigo')";
	$result=mysql_query($sql) or die (mysql_error());
	
	//7.- Redirecciono a la Informacion de Recursos Naturales
	echo "<script>window.location ='adenda_mrn.php?SES=$SES&anio=$anio&cod=$codigo'</script>";
}
elseif($action==ADD_MRN)
{
	//1.- Ubico los codigos POA
	$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo, 
	sys_bd_subactividad_poa.nombre
	FROM sys_bd_subactividad_poa
	WHERE sys_bd_subactividad_poa.codigo LIKE '1.1.1.1.' AND
	sys_bd_subactividad_poa.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);

	$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo, 
	sys_bd_subactividad_poa.nombre
	FROM sys_bd_subactividad_poa
	WHERE sys_bd_subactividad_poa.codigo LIKE '1.2.1.1.' AND
	sys_bd_subactividad_poa.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r2=mysql_fetch_array($result);

	$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo, 
	sys_bd_subactividad_poa.nombre
	FROM sys_bd_subactividad_poa
	WHERE sys_bd_subactividad_poa.codigo LIKE '1.2.2.1.' AND
	sys_bd_subactividad_poa.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r3=mysql_fetch_array($result);

	$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo, 
	sys_bd_subactividad_poa.nombre
	FROM sys_bd_subactividad_poa
	WHERE sys_bd_subactividad_poa.codigo LIKE '1.3.2.1.' AND
	sys_bd_subactividad_poa.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r4=mysql_fetch_array($result);

	$poa_1=$r1['cod'];
	$poa_2=$r2['cod'];
	$poa_3=$r3['cod'];
	$poa_4=$r4['cod'];

	//2.- Calculo la fecha de termino
	$fecha=$_POST['f_inicio'];
	$mes=$_POST['mes'];

	$fecha_actualizada = dateadd1($fecha,7,0,0,0,0,0); // suma 7 dias a la fecha dada
	$f_termino=dateadd1($fecha,5,$mes,0,0,0,0);
	
	if ($f_termino>='2014-09-15')
	{
		$f_termino='2014-09-15';
	}
	else
	{
		$f_termino=$f_termino;
	}

	//3.- Calculo el monto de concursos
	$sql="SELECT org_ficha_usuario.n_documento
	FROM pit_bd_user_iniciativa INNER JOIN pit_bd_ficha_mrn ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org
	 WHERE org_ficha_usuario.titular=1 AND
	 pit_bd_user_iniciativa.estado=1 AND
	 pit_bd_ficha_mrn.cod_mrn='".$_POST['mrn']."'";
	 $result=mysql_query($sql) or die (mysql_error());
	 $total_familias=mysql_num_rows($result);
	
	 
	 $total_cif=$total_familias*60;
	 
	 if ($total_cif>9000)
	 {
		 $total_cif=9000;
	 }
	 else
	 {
		 $total_cif=$total_cif;
	 }
	 
	 echo $total_cif;
	 echo "<br/>";
	
	 //4.- Ingreso la información a la tabla
	 $sql="INSERT INTO pit_adenda_mrn VALUES('','".$_POST['mrn']."','".$_POST['f_inicio']."','".$_POST['mes']."','$f_termino','$total_cif','".$_POST['at_pdss']."','".$_POST['at_org']."','".$_POST['ag_pdss']."','','0','0','1','$poa_1','$poa_2','$poa_3','$poa_4','005','$cod')";
	 $result=mysql_query($sql) or die (mysql_error());	 
	 
	 //5.- Redireccionamos
	 echo "<script>window.location ='adenda_mrn.php?SES=$SES&anio=$anio&cod=$cod'</script>";
}
elseif($action==DELETE_MRN)
{
	$sql="DELETE FROM pit_adenda_mrn WHERE cod_iniciativa='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	 echo "<script>window.location ='adenda_mrn.php?SES=$SES&anio=$anio&cod=$cod'</script>";	
}
elseif($action==EDIT)
{
/*
	//1.- Determino el numero de solicitud
	$sql="SELECT sys_bd_numera_dependencia.n_solicitud_iniciativa,
	sys_bd_numera_dependencia.cod
	FROM sys_bd_numera_dependencia
	WHERE sys_bd_numera_dependencia.cod_dependencia='".$row['cod_dependencia']."' AND
sys_bd_numera_dependencia.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$n_solicitud=$r1['n_solicitud_iniciativa']+1;
	
	//2.- Actualizo
	$sql="UPDATE pit_bd_ficha_adenda SET n_solicitud='$n_solicitud' WHERE cod_adenda='$cod'";
	$result=mysql_query($sql) or die (mysql_error());

	//3.- Actualizo la numeracion	
	$sql="UPDATE sys_bd_numera_dependencia SET n_solicitud_iniciativa='$n_solicitud' WHERE cod='".$r1['cod']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//4.- Determino el codigo del animador
	$sql="SELECT pit_adenda_pit.cod_iniciativa
	FROM pit_adenda_pit
	WHERE pit_adenda_pit.cod_adenda='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	$r2=mysql_fetch_array($result);
	
	//5.- Determino el numero de ATF de el animador territorial
	$sql="SELECT sys_bd_numera_dependencia.cod, 
	sys_bd_numera_dependencia.n_atf_iniciativa
	FROM sys_bd_numera_dependencia
	WHERE sys_bd_numera_dependencia.cod_dependencia='".$row['cod_dependencia']."' AND
	sys_bd_numera_dependencia.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r3=mysql_fetch_array($result);
	
	$n_atf_animador=$r3['n_atf_iniciativa']+1;
	
	//6.- Actualizo el atf del animador
	$sql="UPDATE pit_adenda_pit SET n_atf='$n_atf_animador' WHERE cod_iniciativa='".$r2['cod_iniciativa']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//7.- Actualizo la numeracion
	$sql="UPDATE sys_bd_numera_dependencia SET n_atf_iniciativa='$n_atf_animador' WHERE cod='".$r3['cod']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//8.- Determino el codigo del Plan de Gestión
	$sql="SELECT pit_adenda_mrn.cod_iniciativa
	FROM pit_adenda_mrn
	WHERE pit_adenda_mrn.cod_adenda='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	$r4=mysql_fetch_array($result);
	
	//9.- Determino el numero de ATF del plan de gestion
	$sql="SELECT sys_bd_numera_dependencia.cod, 
	sys_bd_numera_dependencia.n_atf_iniciativa
	FROM sys_bd_numera_dependencia
	WHERE sys_bd_numera_dependencia.cod_dependencia='".$row['cod_dependencia']."' AND
	sys_bd_numera_dependencia.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r5=mysql_fetch_array($result);
	
	$n_atf_mrn=$r5['n_atf_iniciativa']+1;

	//10.- Actualizo el PGRN
	$sql="UPDATE pit_adenda_mrn SET n_atf='$n_atf_mrn' WHERE cod_iniciativa='".$r4['cod_iniciativa']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//11.- Actualizo la numeracion
	$sql="UPDATE sys_bd_numera_dependencia SET n_atf_iniciativa='$n_atf_mrn' WHERE cod='".$r5['cod']."'";
	$result=mysql_query($sql) or die (mysql_error());
*/	
	//12.- Actualizo el contenido
	$sql="UPDATE pit_bd_ficha_adenda SET contenido='".$_POST['contenido']."' WHERE cod_adenda='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//13.- redirecciono
	echo "<script>window.location ='../print/print_adenda_monto.php?SES=$SES&anio=$anio&cod=$cod'</script>";
}
elseif($action==UPDATE)
{
	//1.- Calculo fecha de termino
	$fecha=$_POST['f_adenda'];
	$mes=$_POST['mes'];

	$fecha_actualizada = dateadd1($fecha,7,0,0,0,0,0); // suma 7 dias a la fecha dada
	$f_termino=dateadd1($fecha,5,$mes,0,0,0,0);
	
	if ($f_termino>='2014-09-15')
	{
		$f_termino='2014-09-15';
	}
	else
	{
		$f_termino=$f_termino;
	}
	
	//2.- Actualizo la ficha de adenda
	$sql="UPDATE pit_bd_ficha_adenda SET f_adenda='".$_POST['f_adenda']."',referencia=UPPER('".$_POST['referencia']."'),f_inicio='".$_POST['f_inicio']."',meses='".$_POST['mes']."',f_termino='$f_termino' WHERE cod_adenda='".$_POST['codigo']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	$codigo=$_POST['codigo'];
	
	//3.- Busco el componente y POA
	$sql="SELECT sys_bd_subactividad_poa.cod AS poa, 
	sys_bd_componente_poa.cod AS componente
FROM sys_bd_actividad_poa INNER JOIN sys_bd_subactividad_poa ON sys_bd_actividad_poa.cod = sys_bd_subactividad_poa.relacion
	 INNER JOIN sys_bd_subcomponente_poa ON sys_bd_subcomponente_poa.cod = sys_bd_actividad_poa.relacion
	 INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = sys_bd_subcomponente_poa.relacion
WHERE sys_bd_subactividad_poa.codigo LIKE '3.1.2.7.' AND
sys_bd_subactividad_poa.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r2=mysql_fetch_array($result);


	if ($_POST['mes']<4)
	{
		$mes=$_POST['mes'];
	}
	else
	{
		$mes=4;
	}
	
	$total_animador=$_POST['n_animador']*$_POST['monto_animador']*$mes;

	
	//4.- Actualizo la informacion del AT
	$sql="UPDATE pit_adenda_pit SET n_animador='".$_POST['n_animador']."',monto_animador='".$_POST['monto_animador']."',n_mes='$mes',total_animador='$total_animador',aporte_pdss='$total_animador',cod_componente='".$r2['componente']."',cod_poa='".$r2['poa']."' WHERE cod_iniciativa='$codigo_animador'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//5.- Redirecciono
	echo "<script>window.location ='adenda_mrn.php?SES=$SES&anio=$anio&cod=$codigo'</script>";	
}
elseif($action==DELETE)
{
	//1.- 
	$sql="DELETE FROM pit_bd_ficha_adenda WHERE cod_adenda='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Redirecciono
	echo "<script>window.location ='adenda_monto.php?SES=$SES&anio=$anio&modo=delete'</script>";
}
elseif($action==ADD_PAGO)
{
	//1.- Actualizamos la numeracion
	$sql="UPDATE sys_bd_numera_dependencia SET n_atf_iniciativa='".$_POST['n_atf']."',n_solicitud_iniciativa='".$_POST['n_solicitud']."' WHERE cod='".$_POST['codigo']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Actualizamos la adenda
	$sql="UPDATE pit_bd_ficha_adenda SET n_solicitud='".$_POST['n_solicitud']."',f_solicitud='".$_POST['f_solicitud']."',fte_fida='".$_POST['fte_fida']."',fte_ro='".$_POST['fte_ro']."',cod_estado_iniciativa='005' WHERE cod_adenda='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//3.- ATF PIT
	foreach($n_atf_pit as $cad=>$a1)
	{
		$sql="UPDATE pit_adenda_pit SET n_atf='".$_POST['n_atf_pit'][$cad]."',cod_estado_iniciativa='005' WHERE cod_iniciativa='$cad'";
		$result=mysql_query($sql) or die (mysql_error());
	}
	//4.- ATF PGRN
	foreach($n_atf_mrn as $cid=>$b1)
	{
		$sql="UPDATE pit_adenda_mrn SET n_voucher='".$_POST['n_voucher'][$cid]."',deposito_org='".$_POST['monto_aporte'][$cid]."',n_atf='".$_POST['n_atf_mrn'][$cid]."',cod_estado_iniciativa='005' WHERE cod_iniciativa='$cid'";
		$result=mysql_query($sql) or die (mysql_error());
	}
	
	//5.- Redireccionamos a impresion
	echo "<script>window.location ='../print/print_pago_adenda.php?SES=$SES&anio=$anio&cod=$id'</script>";	
}
elseif($action==UPDATE_PAGO)
{
	//1.- Actualizamos la numeracion
	$sql="UPDATE pit_bd_ficha_adenda SET f_solicitud='".$_POST['f_solicitud']."',fte_fida='".$_POST['fte_fida']."',fte_ro='".$_POST['fte_ro']."' WHERE cod_adenda='$id'";
	$result=mysql_query($sql) or die (mysql_error());

	foreach($n_atf_mrn as $cid=>$b1)
	{
		$sql="UPDATE pit_adenda_mrn SET n_voucher='".$_POST['n_voucher'][$cid]."',deposito_org='".$_POST['monto_aporte'][$cid]."',cod_estado_iniciativa='005' WHERE cod_iniciativa='$cid'";
		$result=mysql_query($sql) or die (mysql_error());
	}	

	//2.- Redireccionamos a impresion
	echo "<script>window.location ='../print/print_pago_adenda.php?SES=$SES&anio=$anio&cod=$id'</script>";	
}

?>