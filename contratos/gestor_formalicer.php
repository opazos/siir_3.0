<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);


if($action==ADD)
{
	//1.- Obtengo el numero de solicitud y atf
	$sql="SELECT sys_bd_numera_dependencia.cod, 
	sys_bd_numera_dependencia.n_solicitud_iniciativa, 
	sys_bd_numera_dependencia.n_atf_iniciativa
	FROM sys_bd_numera_dependencia
	WHERE sys_bd_numera_dependencia.periodo='$anio' AND
	sys_bd_numera_dependencia.cod_dependencia='".$row['cod_dependencia']."'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);

	$n_solicitud_1=$r1['n_solicitud_iniciativa']+1;
	$n_atf_1=$r1['n_atf_iniciativa']+1;
	
	$n_solicitud_2=$r1['n_solicitud_iniciativa']+2;
	$n_atf_2=$r1['n_atf_iniciativa']+2;
	
	//2.- Guardo la información de numeracion
	$sql="UPDATE sys_bd_numera_dependencia SET n_atf_iniciativa='$n_atf_2',n_solicitud_iniciativa='$n_solicitud_2' WHERE cod='".$r1['cod']."'";
	$result=mysql_query($sql) or die (mysql_error());


	//3.- Ubico el codigo POA
	$sql="SELECT sys_bd_subactividad_poa.cod AS poa, 
	sys_bd_componente_poa.cod AS componente
FROM sys_bd_actividad_poa INNER JOIN sys_bd_subactividad_poa ON sys_bd_actividad_poa.cod = sys_bd_subactividad_poa.relacion
	 INNER JOIN sys_bd_subcomponente_poa ON sys_bd_subcomponente_poa.cod = sys_bd_actividad_poa.relacion
	 INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = sys_bd_subcomponente_poa.relacion
WHERE sys_bd_subactividad_poa.codigo LIKE '3.1.1.3.' AND
sys_bd_subactividad_poa.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r2=mysql_fetch_array($result);
	
	//4.- Ingreso la info a la tabla
	$sql="INSERT INTO fm_formalizacion VALUES ('','".$_POST['n_contrato']."','".$_POST['f_contrato']."','".$_POST['f_demanda']."','$n_solicitud_1','$n_atf_1','$n_solicitud_2','$n_atf_2','".$_POST['n_requerimiento']."','".$r2['poa']."','".$_POST['monto1']."','".$_POST['monto2']."','".$_POST['monto3']."','".$_POST['objeto']."',UPPER('".$_POST['contratante']."'),'".$_POST['n_cuenta']."','".$_POST['ifi']."','".$row['cod_dependencia']."','005')";
	$result=mysql_query($sql) or die (mysql_error());

	//5.-ubico el ultimo registro generado
	$sql="SELECT * FROM fm_formalizacion ORDER BY cod DESC LIMIT 0,1";
	$result=mysql_query($sql) or die (mysql_error());	
	$r3=mysql_fetch_array($result);
	
	$codigo=$r3['cod'];
	
	//4.- Redirecciono
	echo "<script>window.location ='m_formalicer.php?SES=$SES&anio=$anio&cod=$codigo'</script>"; 
}
elseif($action==ADD_ORG)
{
	$organizacion=$_POST['org'];
	$dato=explode(",",$organizacion);
	$tipo_documento=$dato[0];
	$n_documento=$dato[1];
	
	//1.- Guardo la información de la organizacion
	$sql="INSERT INTO fm_org_formalizada VALUES('$tipo_documento','$n_documento',UPPER('".$_POST['observacion']."'),'".$_POST['costo']."','$cod')";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Redirecciono
	echo "<script>window.location ='m_formalicer.php?SES=$SES&anio=$anio&cod=$cod'</script>"; 	
}

?>