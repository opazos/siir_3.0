<?php
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

if($action==ADD)
{
	$organizacion=$_POST['org'];
	$dato=explode(",",$organizacion);

	$tipo_documento=$dato[0];
	$n_documento=$dato[1];

	//1.- Buscamos la numeración correspondiente
	$sql="SELECT sys_bd_numera_dependencia.n_contrato_iniciativa, 
	sys_bd_numera_dependencia.n_atf_iniciativa, 
	sys_bd_numera_dependencia.n_solicitud_iniciativa,
	sys_bd_numera_dependencia.cod
	FROM sys_bd_numera_dependencia
	WHERE sys_bd_numera_dependencia.cod_dependencia='".$row['cod_dependencia']."' AND
	sys_bd_numera_dependencia.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);

	$n_contrato=$r1['n_contrato_iniciativa']+1;
	$n_solicitud=$r1['n_solicitud_iniciativa']+1;
	$n_atf=$r1['n_atf_iniciativa']+1;

	//2.- Actualizo la numeracion
	$sql="UPDATE sys_bd_numera_dependencia SET n_contrato_iniciativa='$n_contrato',n_atf_iniciativa='$n_atf',n_solicitud_iniciativa='$n_solicitud' WHERE cod='".$r1['cod']."'";
	$result=mysql_query($sql) or die (mysql_error());

	//3.- Realizamos el ingreso de la informacion
	$sql="INSERT INTO ml_bd_contrato_vg VALUES('','9','$tipo_documento','$n_documento','".$_POST['objeto']."','".$_POST['f_inicio']."','".$_POST['f_termino']."','".$_POST['f_aprobacion']."','$n_contrato','".$_POST['f_contrato']."','45','$n_solicitud','$n_atf','".$_POST['poa']."','".$_POST['fte_fto']."','".$_POST['n_cuenta']."','".$_POST['ifi']."','".$_POST['n_participantes']."','".$_POST['aporte_pdss']."','".$_POST['aporte_org']."','".$_POST['aporte_otro']."','".$_POST['valorizacion']."','".$_POST['valor_aporte']."','005','".$_POST['f_presentacion']."')";
	$result=mysql_query($sql) or die (mysql_error());

	//4.- Buscamos el ultimo registro generado
	$sql="SELECT * FROM ml_bd_contrato_vg ORDER BY cod_contrato DESC LIMIT 0,1";
	$result=mysql_query($sql) or die (mysql_error());
	$r2=mysql_fetch_array($result);

	$codigo=$r2['cod_contrato'];

	//5.- Guardo el itinerario
	for ($i=0;$i<=5 ;$i++) 
	{ 
		if($_POST['entidad'][$i]<>NULL)
		{
			$sql="INSERT INTO ml_bd_itinerario_vg VALUES('',UPPER('".$_POST['entidad'][$i]."'),UPPER('".$_POST['dep'][$i]."'),UPPER('".$_POST['prov'][$i]."'),UPPER('".$_POST['dist'][$i]."'),'$codigo')";
			$result=mysql_query($sql) or die (mysql_error());
		}
	}

	//6.- Redirecciono
	echo "<script>window.location ='../print/print_contrato_vg.php?SES=$SES&anio=$anio&cod=$codigo'</script>"; 
}
elseif($action==UPDATE)
{
	$organizacion=$_POST['org'];
	$dato=explode(",",$organizacion);

	$tipo_documento=$dato[0];
	$n_documento=$dato[1];

	//1.- Actualizamos la información del contrato
	$sql="UPDATE ml_bd_contrato_vg SET cod_tipo_doc='$tipo_documento',n_documento='$n_documento',objeto='".$_POST['objeto']."',f_inicio='".$_POST['f_inicio']."',f_termino='".$_POST['f_termino']."',f_aprobacion='".$_POST['f_aprobacion']."',f_contrato='".$_POST['f_contrato']."',cod_poa='".$_POST['poa']."',fte_fto='".$_POST['fte_fto']."',n_cuenta='".$_POST['n_cuenta']."',cod_ifi='".$_POST['ifi']."',n_participantes='".$_POST['n_participantes']."',aporte_pdss='".$_POST['aporte_pdss']."',aporte_org='".$_POST['aporte_org']."',aporte_otro='".$_POST['aporte_otro']."',valorizacion='".$_POST['valorizacion']."',aporte_valorizacion='".$_POST['valor_aporte']."',f_presentacion='".$_POST['f_presentacion']."' WHERE cod_contrato='".$_POST['codigo']."'";
	$result=mysql_query($sql) or die (mysql_error());

	$codigo=$_POST['codigo'];

	//2.- Actualizamos el itinerario con un foreach
	foreach ($entidads as $cad => $a) 
	{
		$sql="UPDATE ml_bd_itinerario_vg SET entidad=UPPER('".$_POST['entidads'][$cad]."'),dep=UPPER('".$_POST['deps'][$cad]."'),prov=UPPER('".$_POST['provs'][$cad]."'),dist=UPPER('".$_POST['dists'][$cad]."') WHERE cod_itinerario='$cad'";
		$result=mysql_query($sql) or die (mysql_error());
	}
	//3.- Añadimos los nuevos campos
	for ($i=0;$i<=5 ;$i++) 
	{ 
		if($_POST['entidad'][$i]<>NULL)
		{
			$sql="INSERT INTO ml_bd_itinerario_vg VALUES('',UPPER('".$_POST['entidad'][$i]."'),UPPER('".$_POST['dep'][$i]."'),UPPER('".$_POST['prov'][$i]."'),UPPER('".$_POST['dist'][$i]."'),'$codigo')";
			$result=mysql_query($sql) or die (mysql_error());
		}
	}	
	//6.- Redirecciono
	echo "<script>window.location ='../print/print_contrato_vg.php?SES=$SES&anio=$anio&cod=$codigo'</script>"; 	
}
elseif($action==DELETE_ITINERARIO)
{
	$sql="DELETE FROM ml_bd_itinerario_vg WHERE cod_itinerario='$cod'";
	$result=mysql_query($sql) or die (mysql_error());

	echo "<script>window.location ='m_contrato_vg.php?SES=$SES&anio=$anio&id=$id'</script>"; 		
}

?>