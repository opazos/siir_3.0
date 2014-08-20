<?php
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$organizacion=$_POST['org'];
$dato=explode(",",$organizacion);

$tipo_documento=$dato[0];
$n_documento=$dato[1];


if ($action==ADD)
{
	//1.- Actualizo la numeracion
	$sql="UPDATE sys_bd_numera_dependencia SET n_contrato_iniciativa='".$_POST['n_contrato']."',n_atf_iniciativa='".$_POST['n_atf']."',n_solicitud_iniciativa='".$_POST['n_solicitud']."' WHERE cod='".$_POST['codigo']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	
	//2.- Busco el codigo poa y componente
	$sql="SELECT sys_bd_componente_poa.cod
FROM sys_bd_actividad_poa INNER JOIN sys_bd_subactividad_poa ON sys_bd_actividad_poa.cod = sys_bd_subactividad_poa.relacion
	 INNER JOIN sys_bd_subcomponente_poa ON sys_bd_subcomponente_poa.cod = sys_bd_actividad_poa.relacion
	 INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = sys_bd_subcomponente_poa.relacion
WHERE sys_bd_subactividad_poa.cod='".$_POST['poa']."'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	//3.- Procedo a registrar los nuevos campos
	$sql="INSERT INTO ml_pf VALUES('','10',UPPER('".$_POST['evento']."'),'".$_POST['f_inicio']."','".$_POST['dia']."',UPPER('".$_POST['lugar']."'),'".$_POST['participantes']."','".$_POST['objetivo']."','".$_POST['resultado']."',n_contrato='".$_POST['n_contrato']."','".$_POST['f_contrato']."','".$_POST['n_atf']."','".$_POST['n_solicitud']."','".$_POST['n_cuenta']."','".$_POST['ifi']."','".$r1['cod']."','".$_POST['poa']."','".$_POST['aporte_pdss']."','".$_POST['aporte_org']."','".$_POST['aporte_otro']."','$fecha_hoy','005','$tipo_documento','$n_documento','')";
	$result=mysql_query($sql) or die (mysql_error());
	
	//4.- obtengo el ultimo registro generado
	$sql="SELECT * FROM ml_pf ORDER BY cod_evento DESC LIMIT 0,1";
	$result=mysql_query($sql) or die (mysql_error());
	$r2=mysql_fetch_array($result);
	
	$codigo=$r2['cod_evento'];
	
	//5.- redirecciono
	echo "<script>window.location ='../print/print_contrato_pf.php?SES=$SES&anio=$anio&cod=$codigo'</script>";
}
elseif($action==UPDATE)
{
	//1.- Busco el codigo poa y componente
	$sql="SELECT sys_bd_componente_poa.cod
FROM sys_bd_actividad_poa INNER JOIN sys_bd_subactividad_poa ON sys_bd_actividad_poa.cod = sys_bd_subactividad_poa.relacion
	 INNER JOIN sys_bd_subcomponente_poa ON sys_bd_subcomponente_poa.cod = sys_bd_actividad_poa.relacion
	 INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = sys_bd_subcomponente_poa.relacion
WHERE sys_bd_subactividad_poa.cod='".$_POST['poa']."'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);

	//2.- Actualizo
	$sql="UPDATE ml_pf SET nombre=UPPER('".$_POST['evento']."'),f_evento='".$_POST['f_inicio']."',dia='".$_POST['dia']."',lugar=UPPER('".$_POST['lugar']."'),n_participante='".$_POST['participantes']."',objetivo='".$_POST['objetivo']."',resultados='".$_POST['resultado']."',n_contrato='".$_POST['n_contrato']."',f_contrato='".$_POST['f_contrato']."',n_atf='".$_POST['n_atf']."',n_solicitud='".$_POST['n_solicitud']."',n_cuenta='".$_POST['n_cuenta']."',cod_ifi='".$_POST['ifi']."',cod_componente='".$r1['cod']."',cod_subactividad='".$_POST['poa']."',aporte_pdss='".$_POST['aporte_pdss']."',aporte_org='".$_POST['aporte_org']."',aporte_otro='".$_POST['aporte_otro']."',f_presentacion='".$_POST['f_presentacion']."' WHERE cod_evento='".$_POST['codigo']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//3.- obtengo el codigo
	$codigo=$_POST['codigo'];
	
	//4.- Redirecciono
		echo "<script>window.location ='../print/print_contrato_pf.php?SES=$SES&anio=$anio&cod=$codigo'</script>";
}
elseif($action==ANULA)
{
	$sql="UPDATE ml_pf SET cod_estado_iniciativa='000' WHERE cod_evento='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Redirecciono
	echo "<script>window.location ='contrato_pf.php?SES=$SES&anio=$anio&modo=anula'</script>";
}
elseif($action==ADD_LIQUIDA)
{	
	//1.- Ingresamos el dato
	$sql="INSERT INTO ml_liquida_pf VALUES('','$id','".$_POST['f_liquidacion']."','".$_POST['resultados']."','".$_POST['ejec_pdss']."','".$_POST['ejec_org']."','".$_POST['ejec_otro']."','".$_POST['observaciones']."')";
	$result=mysql_query($sql) or die (mysql_error());

	//2.- Actualizamos el estado del evento
	$sql="UPDATE ml_pf SET cod_estado_iniciativa='004' WHERE cod_evento='$id'";
	$result=mysql_query($sql) or die (mysql_error());	
	
	//3.- Obtenemos el ultimo registro generado
	$sql="SELECT * FROM ml_liquida_pf ORDER BY cod DESC LIMIT 0,1";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$codigo=$r1['cod'];
	
	//4.- Realizamos el ingreso de los campos varios
	//a.- Exhibicion
	for($i=0;$i<=10;$i++)
	{
		if($_POST['producto'][$i]<>NULL)
		{
			$sql="INSERT INTO ml_pf_muestra VALUES('','$codigo',UPPER('".$_POST['producto'][$i]."'),UPPER('".$_POST['unidad'][$i]."'),'".$_POST['cantidad'][$i]."',UPPER('".$_POST['detalle'][$i]."'))";
			$result=mysql_query($sql) or die (mysql_error());
		}
	}
	//b.- Ventas
	for($i=0;$i<=10;$i++)
	{			
		if($_POST['productov'][$i]<>NULL)
		{
			$total_v=$_POST['cantidadv'][$i]*$_POST['preciov'][$i];
			
			$sql="INSERT INTO ml_pf_venta VALUES('','$codigo',UPPER('".$_POST['productov'][$i]."'),UPPER('".$_POST['unidadv'][$i]."'),'".$_POST['cantidadv'][$i]."','".$_POST['preciov'][$i]."','$total_v')";
			$result=mysql_query($sql) or die (mysql_error());
		}
	}
	//c.- Contactos
	for($i=0;$i<=10;$i++)
	{
		if ($_POST['empresa'][$i]<>NULL)
		{
			$sql="INSERT INTO ml_pf_contacto VALUES('','$codigo',UPPER('".$_POST['empresa'][$i]."'),UPPER('".$_POST['mercado'][$i]."'),UPPER('".$_POST['acuerdos'][$i]."'),UPPER('".$_POST['productos'][$i]."'))";
			$result=mysql_query($sql) or die (mysql_error());
		}
	}
	//d.- Eventos
	for($i=0;$i<=5;$i++)
	{
		if($_POST['evento'][$i]<>NULL)
		{
			$sql="INSERT INTO ml_pf_evento VALUES('','$codigo',UPPER('".$_POST['evento'][$i]."'),UPPER('".$_POST['tema'][$i]."'),'".$_POST['n_participante'][$i]."')";
			$result=mysql_query($sql) or die (mysql_error());
		}
	}
	//e.- Concursos
	for($i=0;$i<=5;$i++)
	{
		if($_POST['concurso'][$i]<>NULL)
		{
			$sql="INSERT INTO ml_pf_concurso VALUES('','$codigo',UPPER('".$_POST['concurso'][$i]."'),UPPER('".$_POST['resultado'][$i]."'))";
			$result=mysql_query($sql) or die (mysql_error());
		}
	}
	//5.- Redireccionamos
	echo "<script>window.location ='m_liquida_pf.php?SES=$SES&anio=$anio&id=$codigo'</script>";
}
elseif($action==UPDATE_LIQUIDA)
{
//1.- Actualizo la ficha de liquidacion
$sql="UPDATE ml_liquida_pf SET f_liquidacion='".$_POST['f_liquidacion']."',resultado='".$_POST['resultados']."',ejec_pdss='".$_POST['ejec_pdss']."',ejec_org='".$_POST['ejec_org']."',ejec_otro='".$_POST['ejec_otro']."',problemas='".$_POST['observaciones']."' WHERE cod='$id'";
$result=mysql_query($sql) or die (mysql_error());

$codigo=$id;

//2.- Procedo a generar foreach
foreach($productos as $cad=>$a1)
{
	$sql="UPDATE ml_pf_muestra SET producto='".$_POST['productos'][$cad]."',unidad='".$_POST['unidads'][$cad]."',cantidad='".$_POST['cantidads'][$cad]."',caracteristica='".$_POST['detalles'][$cad]."' WHERE cod='$cad'";
	$result=mysql_query($sql) or die (mysql_error());
}
foreach($productovs as $ced=>$b1)
{
	$totalvs=$_POST['cantidadvs'][$ced]*$_POST['preciovs'][$ced];
	
	$sql="UPDATE ml_pf_venta SET producto='".$_POST['productovs'][$ced]."',unidad='".$_POST['unidadvs'][$ced]."',cantidad='".$_POST['cantidadvs'][$ced]."',precio='".$_POST['preciovs'][$ced]."',total='$totalvs' WHERE cod='$ced'";
	$result=mysql_query($sql) or die (mysql_error());
}
foreach($empresas as $cid=>$c1)
{
	$sql="UPDATE ml_pf_contacto SET contacto='".$_POST['empresas'][$cid]."',cod_mercado='".$_POST['mercados'][$cid]."',acuerdo='".$_POST['acuerdoss'][$cid]."',producto='".$_POST['productoss'][$cid]."' WHERE cod='$cid'";
	$result=mysql_query($sql) or die (mysql_error());
}
foreach($eventos as $cud=>$d1)
{
	$sql="UPDATE ml_pf_evento SET nombre=UPPER('".$_POST['eventos'][$cud]."'),tema=UPPER('".$_POST['temas'][$cud]."'),n_participante='".$_POST['n_participantes'][$cud]."' WHERE cod='$cud'";
	$result=mysql_query($sql) or die (mysql_error());
}
foreach($concursos as $cae=>$e1)
{
	$sql="UPDATE ml_pf_concurso SET concurso='".$_POST['concursos'][$cae]."',calificacion='".$_POST['resultadoss'][$cae]."' WHERE cod='$cae'";
	$result=mysql_query($sql) or die (mysql_error());
}

//3.- Genero nuevos registrsos
	//a.- Exhibicion
	for($i=0;$i<=5;$i++)
	{
		if($_POST['producto'][$i]<>NULL)
		{
			$sql="INSERT INTO ml_pf_muestra VALUES('','$codigo',UPPER('".$_POST['producto'][$i]."'),UPPER('".$_POST['unidad'][$i]."'),'".$_POST['cantidad'][$i]."',UPPER('".$_POST['detalle'][$i]."'))";
			$result=mysql_query($sql) or die (mysql_error());
		}
	}
	//b.- Ventas
	for($i=0;$i<=5;$i++)
	{
				
		if($_POST['productov'][$i]<>NULL)
		{
			$total_v=$_POST['cantidadv'][$i]*$_POST['preciov'][$i];
			$sql="INSERT INTO ml_pf_venta VALUES('','$codigo',UPPER('".$_POST['productov'][$i]."'),UPPER('".$_POST['unidadv'][$i]."'),'".$_POST['cantidadv'][$i]."','".$_POST['preciov'][$i]."','$total_v')";
			$result=mysql_query($sql) or die (mysql_error());
		}
	}
	//c.- Contactos
	for($i=0;$i<=5;$i++)
	{
		if ($_POST['empresa'][$i]<>NULL)
		{
			$sql="INSERT INTO ml_pf_contacto VALUES('','$codigo',UPPER('".$_POST['empresa'][$i]."'),UPPER('".$_POST['mercado'][$i]."'),UPPER('".$_POST['acuerdos'][$i]."'),UPPER('".$_POST['productos'][$i]."'))";
			$result=mysql_query($sql) or die (mysql_error());
		}
	}
	//d.- Eventos
	for($i=0;$i<=3;$i++)
	{
		if($_POST['evento'][$i]<>NULL)
		{
			$sql="INSERT INTO ml_pf_evento VALUES('','$codigo',UPPER('".$_POST['evento'][$i]."'),UPPER('".$_POST['tema'][$i]."'),'".$_POST['n_participante'][$i]."')";
			$result=mysql_query($sql) or die (mysql_error());
		}
	}
	//e.- Concursos
	for($i=0;$i<=2;$i++)
	{
		if($_POST['concurso'][$i]<>NULL)
		{
			$sql="INSERT INTO ml_pf_concurso VALUES('','$codigo',UPPER('".$_POST['concurso'][$i]."'),UPPER('".$_POST['resultado'][$i]."'))";
			$result=mysql_query($sql) or die (mysql_error());
		}
	}

echo "<script>window.location ='../print/print_liquida_pf.php?SES=$SES&anio=$anio&cod=$codigo'</script>";
}
elseif($action==DELETE_MUESTRA)
{
	$sql="DELETE FROM ml_pf_muestra WHERE cod='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	echo "<script>window.location ='m_liquida_pf.php?SES=$SES&anio=$anio&id=$cod'</script>";
}
elseif($action==DELETE_VENTA)
{
	$sql="DELETE FROM ml_pf_venta WHERE cod='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	echo "<script>window.location ='m_liquida_pf.php?SES=$SES&anio=$anio&id=$cod'</script>";	
}
elseif($action==DELETE_CONTACTO)
{
	$sql="DELETE FROM ml_pf_contacto WHERE cod='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	echo "<script>window.location ='m_liquida_pf.php?SES=$SES&anio=$anio&id=$cod'</script>";
}
elseif($action==DELETE_EVENTO)
{
	$sql="DELETE FROM ml_pf_evento WHERE cod='$id'";
	$result=mysql_query($sql) or die (mysql_error());

	echo "<script>window.location ='m_liquida_pf.php?SES=$SES&anio=$anio&id=$cod'</script>";	
}
elseif($action==DELETE_CONCURSO)
{
	$sql="DELETE FROM ml_pf_concurso WHERE cod='$id'";
	$result=mysql_query($sql) or die (mysql_error());

	echo "<script>window.location ='m_liquida_pf.php?SES=$SES&anio=$anio&id=$cod'</script>";	
}

?>