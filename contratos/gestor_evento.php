<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

if ($action==ADD)
{
	
	//1.- Buscamos el evento generado
	if($_POST['tipo_codigo']==1)
	{
		$sql="SELECT epd_bd_demanda.cod_evento AS cod_evento, 
	epd_bd_demanda.cod_tipo_iniciativa
	FROM epd_bd_demanda
	WHERE
	epd_bd_demanda.n_evento='".$_POST['codigo_evento']."' AND
	epd_bd_demanda.cod_dependencia='".$_POST['olp']."' AND
	epd_bd_demanda.f_presentacion BETWEEN '$anio-01-01' AND '$anio-12-31'";
	}
	else
	{
		$sql="SELECT clar_bd_evento_clar.cod_clar AS cod_evento, 
	clar_bd_evento_clar.cod_tipo_iniciativa
	FROM clar_bd_evento_clar
	WHERE clar_bd_evento_clar.cod_clar='".$_POST['codigo_evento']."' AND
	clar_bd_evento_clar.cod_dependencia='".$_POST['olp']."' AND
	clar_bd_evento_clar.f_presentacion BETWEEN '$anio-01-01' AND '$anio-12-31'";
	}
	$result=mysql_query($sql) or die (mysql_error());
	$l1=mysql_fetch_array($result);

	$total_registro=mysql_num_rows($result);

	//1.- Actualizo la numeracion segun sea el caso
	if ($_POST['donacion']==1)
	{
		$sql="UPDATE sys_bd_numera_dependencia SET n_evento_don='".$_POST['n_evento']."' WHERE cod='".$_POST['cod_numeracion']."'";
	}
	else
	{
		$sql="UPDATE sys_bd_numera_dependencia SET n_epd='".$_POST['n_evento']."' WHERE cod='".$_POST['cod_numeracion']."'";
	}
	$result=mysql_query($sql) or die (mysql_error());
	
	
	//2.- Busco el POA
	$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.cod_categoria_poa AS categoria, 
	sys_bd_subcomponente_poa.relacion AS componente
	FROM sys_bd_actividad_poa INNER JOIN sys_bd_subactividad_poa ON sys_bd_actividad_poa.cod = sys_bd_subactividad_poa.relacion
	INNER JOIN sys_bd_subcomponente_poa ON sys_bd_subcomponente_poa.cod = sys_bd_actividad_poa.relacion
	WHERE sys_bd_subactividad_poa.cod='".$_POST['poa']."'";
	$result=mysql_query($sql) or die (mysql_error());
	$r3=mysql_fetch_array($result);
	
	$poa=$r3['cod'];
	$componente=$r3['componente'];
	$categoria=$r3['categoria'];

	
	//3.- guardo la informacion del evento
	if($total_registro<>0)
	{
	$sql="INSERT INTO epd_bd_demanda VALUES('','".$_POST['n_evento']."','1',UPPER('".$_POST['nombre']."'),'".$_POST['tipo_evento']."',UPPER('".$_POST['otro']."'),'".$_POST['f_presentacion']."','".$_POST['f_evento']."',UPPER('".$_POST['objetivo']."'),UPPER('".$_POST['resultado']."'),'".$_POST['n_participante']."','".$_POST['vehiculo']."','".$_POST['select1']."','".$_POST['select2']."','".$_POST['select3']."','".$row['cod_dependencia']."',UPPER('".$_POST['direccion']."'),'$componente','$poa','$categoria','".$_POST['financiamiento']."','0','008','".$_POST['dni']."','".$l1['cod_tipo_iniciativa']."','".$l1['cod_evento']."','".$_POST['olp']."')";
	}
	else
	{
	$sql="INSERT INTO epd_bd_demanda VALUES('','".$_POST['n_evento']."','1',UPPER('".$_POST['nombre']."'),'".$_POST['tipo_evento']."',UPPER('".$_POST['otro']."'),'".$_POST['f_presentacion']."','".$_POST['f_evento']."',UPPER('".$_POST['objetivo']."'),UPPER('".$_POST['resultado']."'),'".$_POST['n_participante']."','".$_POST['vehiculo']."','".$_POST['select1']."','".$_POST['select2']."','".$_POST['select3']."','".$row['cod_dependencia']."',UPPER('".$_POST['direccion']."'),'$componente','$poa','$categoria','".$_POST['financiamiento']."','0','008','".$_POST['dni']."','','','')";		
	}
	$result=mysql_query($sql) or die (mysql_error());
	
	//4.- busco el ultimo registro generado
	$sql="SELECT * FROM epd_bd_demanda ORDER BY cod_evento DESC LIMIT 0,1";
	$result=mysql_query($sql) or die (mysql_error());
	$r2=mysql_fetch_array($result);
	
	$codigo=$r2['cod_evento'];
	
	//4.-Ingreso la informacion de el presupuesto
	for ($i = 0; $i <= 30; $i++) 
	{
	if ($_POST['concepto'][$i]<>NULL)
	{
		$sql="INSERT INTO epd_bd_presupuesto VALUES('','".$_POST['concepto'][$i]."','".$_POST['describe'][$i]."','".$_POST['monto'][$i]."','".$_POST['requerido'][$i]."','$codigo')";
		$result=mysql_query($sql) or die (mysql_error());
	}
	}

	//5.- redirecciono
	echo "<script>window.location ='../print/print_demanda_evento.php?SES=$SES&anio=$anio&cod=$codigo'</script>";	
}






elseif($action==UPDATE)
{

//1.- Buscamos el evento generado
	if($_POST['tipo_codigo']==1)
	{
		$sql="SELECT epd_bd_demanda.cod_evento AS cod_evento, 
	epd_bd_demanda.cod_tipo_iniciativa
	FROM epd_bd_demanda
	WHERE
	epd_bd_demanda.n_evento='".$_POST['codigo_evento']."' AND
	epd_bd_demanda.cod_dependencia='".$_POST['olp']."' AND
	epd_bd_demanda.f_presentacion BETWEEN '$anio-01-01' AND '$anio-12-31'";
	}
	else
	{
		$sql="SELECT clar_bd_evento_clar.cod_clar AS cod_evento, 
	clar_bd_evento_clar.cod_tipo_iniciativa
	FROM clar_bd_evento_clar
	WHERE clar_bd_evento_clar.cod_clar='".$_POST['codigo_evento']."' AND
	clar_bd_evento_clar.cod_dependencia='".$_POST['olp']."' AND
	clar_bd_evento_clar.f_presentacion BETWEEN '$anio-01-01' AND '$anio-12-31'";
	}
	$result=mysql_query($sql) or die (mysql_error());
	$l1=mysql_fetch_array($result);

	$total_registro=mysql_num_rows($result);


	//2.- Busco el POA
	$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.cod_categoria_poa AS categoria, 
	sys_bd_subcomponente_poa.relacion AS componente
	FROM sys_bd_actividad_poa INNER JOIN sys_bd_subactividad_poa ON sys_bd_actividad_poa.cod = sys_bd_subactividad_poa.relacion
	INNER JOIN sys_bd_subcomponente_poa ON sys_bd_subcomponente_poa.cod = sys_bd_actividad_poa.relacion
	WHERE sys_bd_subactividad_poa.cod='".$_POST['poa']."'";
	$result=mysql_query($sql) or die (mysql_error());
	$r3=mysql_fetch_array($result);
	
	$poa=$r3['cod'];
	$componente=$r3['componente'];
	$categoria=$r3['categoria'];



//1.- Actualizo la informacion
if($total_registro<>0)
{
$sql="UPDATE epd_bd_demanda SET n_evento='".$_POST['n_evento']."',nombre=UPPER('".$_POST['nombre']."'),cod_tipo_evento='".$_POST['tipo_evento']."',especificar=UPPER('".$_POST['otro']."'),f_presentacion='".$_POST['f_presentacion']."',f_evento='".$_POST['f_evento']."',objetivo=UPPER('".$_POST['objetivo']."'),resultado=UPPER('".$_POST['resultado']."'),participantes='".$_POST['n_participante']."',oficial='".$_POST['vehiculo']."',cod_dep='".$_POST['select1']."',cod_prov='".$_POST['select2']."',cod_dist='".$_POST['select3']."',direccion=UPPER('".$_POST['direccion']."'),cod_componente='$componente',cod_poa='$poa',cod_categoria='$categoria',fte_fto='".$_POST['financiamiento']."',n_doc_solicitante='".$_POST['dni']."',cod_tipo_iniciativa_evento='".$l1['cod_tipo_iniciativa']."',codigo_evento='".$l1['cod_evento']."',olp_evento='".$_POST['olp']."' WHERE cod_evento='".$_POST['codigo']."'";
}	
else
{
$sql="UPDATE epd_bd_demanda SET n_evento='".$_POST['n_evento']."',nombre=UPPER('".$_POST['nombre']."'),cod_tipo_evento='".$_POST['tipo_evento']."',especificar=UPPER('".$_POST['otro']."'),f_presentacion='".$_POST['f_presentacion']."',f_evento='".$_POST['f_evento']."',objetivo=UPPER('".$_POST['objetivo']."'),resultado=UPPER('".$_POST['resultado']."'),participantes='".$_POST['n_participante']."',oficial='".$_POST['vehiculo']."',cod_dep='".$_POST['select1']."',cod_prov='".$_POST['select2']."',cod_dist='".$_POST['select3']."',direccion=UPPER('".$_POST['direccion']."'),cod_componente='$componente',cod_poa='$poa',cod_categoria='$categoria',fte_fto='".$_POST['financiamiento']."',n_doc_solicitante='".$_POST['dni']."' WHERE cod_evento='".$_POST['codigo']."'";
}
$result=mysql_query($sql) or die (mysql_error());

$codigo=$_POST['codigo'];

//2.- foreach
foreach($conceptos as $id=>$a1)
{
$sql="UPDATE epd_bd_presupuesto SET cod_tipo_gasto='$a1' WHERE cod_presupuesto='$id'";
$result=mysql_query($sql) or die (mysql_error());
}

foreach($describes as $id=>$b1)
{
$sql="UPDATE epd_bd_presupuesto SET descripcion='$b1' WHERE cod_presupuesto='$id'";
$result=mysql_query($sql) or die (mysql_error());
}

foreach($montos as $id=>$c1)
{
$sql="UPDATE epd_bd_presupuesto SET monto='$c1' WHERE cod_presupuesto='$id'";
$result=mysql_query($sql) or die (mysql_error());
}

foreach($requeridos as $id=>$d1)
{
$sql="UPDATE epd_bd_presupuesto SET monto_solicitado='$d1' WHERE cod_presupuesto='$id'";
$result=mysql_query($sql) or die (mysql_error());
}

//3.- insert
	for ($i = 0; $i <= 20; $i++) 
	{
	if ($_POST['concepto'][$i]<>NULL)
	{
		$sql="INSERT INTO epd_bd_presupuesto VALUES('','".$_POST['concepto'][$i]."','".$_POST['describe'][$i]."','".$_POST['monto'][$i]."','".$_POST['requerido'][$i]."','$codigo')";
		$result=mysql_query($sql) or die (mysql_error());
	}
	}

	//5.- redirecciono
	echo "<script>window.location ='../print/print_demanda_evento.php?SES=$SES&anio=$anio&cod=$codigo'</script>";	

}
elseif($action==RINDE_EVENTO)
{
//1.- Registro la informacion de rendicion0
$sql="INSERT INTO epd_informe_evento VALUES('','$id','".$_POST['p1']."','".$_POST['p2']."','".$_POST['p3']."','".$_POST['p4']."','".$_POST['p7']."','".$_POST['p8']."','".$_POST['p5']."','".$_POST['p6']."','".$_POST['n_dj']."','".$_POST['monto_desembolsado']."','".$_POST['monto_devuelto']."','".$_POST['resultado']."','".$_POST['problema']."','".$_POST['dni']."','".$_POST['f_rendicion']."')";
$result=mysql_query($sql) or die (mysql_error());

//2.- Obtengo el numero de rendicion
$sql="SELECT * FROM epd_informe_evento ORDER BY cod_rendicion DESC LIMIT 0,1";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$codigo=$r1['cod_rendicion'];

//2.1 Actualizo el estado situacional del evento
$sql="UPDATE epd_bd_demanda SET estado='1' WHERE cod_evento='$id'";
$result=mysql_query($sql) or die (mysql_error());

//3.- Guardamos la informacion de participantes al evento
for ($i=0;$i<=3;$i++)
{
if ($_POST['participante'][$i]<>NULL)
{
$sql="INSERT INTO epd_participante_evento VALUES('',UPPER('".$_POST['participante'][$i]."'),'".$_POST['tipo'][$i]."','".$_POST['monto'][$i]."','$codigo')";
$result=mysql_query($sql) or die (mysql_error());
}
}

//4.- Guardo la rendicion del evento
for($i=0;$i<=20;$i++)
{
if ($_POST['fecha'][$i]<>NULL)
{
$sql="INSERT INTO epd_rendicion_evento VALUES('','".$_POST['fecha'][$i]."','".$_POST['concepto'][$i]."',UPPER('".$_POST['proveedor'][$i]."'),'".$_POST['ruc'][$i]."','','".$_POST['n_doc'][$i]."',UPPER('".$_POST['detalle'][$i]."'),'".$_POST['tipo_doc'][$i]."','".$_POST['precio'][$i]."','$codigo')";
$result=mysql_query($sql) or die (mysql_error());
}
}

//5.- Redirecciono
	echo "<script>window.location ='../print/print_informe_evento.php?SES=$SES&anio=$anio&cod=$codigo'</script>";	

}
elseif($action==UPDATE_RINDE)
{
//1.- Guardamos la info de la rendiciion
$sql="UPDATE epd_informe_evento SET aut_var='".$_POST['p1']."',aut_muj='".$_POST['p2']."',dir_var='".$_POST['p3']."',dir_muj='".$_POST['p4']."',otr_var='".$_POST['p7']."',otr_muj='".$_POST['p8']."',pdss_var='".$_POST['p5']."',pdss_muj='".$_POST['p6']."',cod_dj_evento='".$_POST['n_dj']."',aporte_recibido='".$_POST['monto_desembolsado']."',monto_devuelto='".$_POST['monto_devuelto']."',resultado=UPPER('".$_POST['resultado']."'),comentario=UPPER('".$_POST['problema']."'),persona_dirigido='".$_POST['dni']."',f_informe='".$_POST['f_rendicion']."' WHERE cod_rendicion='".$_POST['codigo']."'";
$result=mysql_query($sql) or die (mysql_error());

$codigo=$_POST['codigo'];

//2.- Actualizamos los participantes
foreach($participantes as $cod=>$a1)
{
	$sql="UPDATE epd_participante_evento SET nombre='$a1' WHERE cod_participante='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
}
foreach($tipos as $cod=>$b1)
{
	$sql="UPDATE epd_participante_evento SET tipo='$b1' WHERE cod_participante='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
}
foreach($montos as $cod=>$c1)
{
	$sql="UPDATE epd_participante_evento SET aporte='$c1' WHERE cod_participante='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
}

for ($i=0;$i<=3;$i++)
{
if ($_POST['participante'][$i]<>NULL)
{
$sql="INSERT INTO epd_participante_evento VALUES('',UPPER('".$_POST['participante'][$i]."'),'".$_POST['tipo'][$i]."','".$_POST['monto'][$i]."','$codigo')";
$result=mysql_query($sql) or die (mysql_error());
}
}
//3.- Actualizo la rendicion del presupuesto
foreach($fechas as $id=>$a2)
{
	$sql="UPDATE epd_rendicion_evento SET f_detalle='$a2' WHERE cod_detalle='$id'";
	$result=mysql_query($sql) or die (mysql_error());
}
foreach($conceptos as $id=>$b2)
{
	$sql="UPDATE epd_rendicion_evento SET cod_tipo_gasto='$b2' WHERE cod_detalle='$id'";
	$result=mysql_query($sql) or die (mysql_error());
}
foreach($proveedors as $id=>$c2)
{
	$sql="UPDATE epd_rendicion_evento SET proveedor='$c2' WHERE cod_detalle='$id'";
	$result=mysql_query($sql) or die (mysql_error());
}
foreach($rucs as $id=>$d2)
{
	$sql="UPDATE epd_rendicion_evento SET ruc='$d2' WHERE cod_detalle='$id'";
	$result=mysql_query($sql) or die (mysql_error());
}
foreach($n_docs as $id=>$e2)
{
	$sql="UPDATE epd_rendicion_evento SET numero='$e2' WHERE cod_detalle='$id'";
	$result=mysql_query($sql) or die (mysql_error());
}
foreach($detalles as $id=>$f2)
{
	$sql="UPDATE epd_rendicion_evento SET concepto='$f2' WHERE cod_detalle='$id'";
	$result=mysql_query($sql) or die (mysql_error());
}
foreach($tipo_docs as $id=>$g2)
{
	$sql="UPDATE epd_rendicion_evento SET cod_tipo_importe='$g2' WHERE cod_detalle='$id'";
	$result=mysql_query($sql) or die (mysql_error());
}
foreach($precios as $id=>$h2)
{
	$sql="UPDATE epd_rendicion_evento SET monto='$h2' WHERE cod_detalle='$id'";
	$result=mysql_query($sql) or die (mysql_error());
}

for($i=0;$i<=10;$i++)
{
if ($_POST['fecha'][$i]<>NULL)
{
$sql="INSERT INTO epd_rendicion_evento VALUES('','".$_POST['fecha'][$i]."','".$_POST['concepto'][$i]."',UPPER('".$_POST['proveedor'][$i]."'),'".$_POST['ruc'][$i]."','','".$_POST['n_doc'][$i]."',UPPER('".$_POST['detalle'][$i]."'),'".$_POST['tipo_doc'][$i]."','".$_POST['precio'][$i]."','$codigo')";
$result=mysql_query($sql) or die (mysql_error());
}
}

//5.- Redirecciono
	echo "<script>window.location ='../print/print_informe_evento.php?SES=$SES&anio=$anio&cod=$codigo'</script>";	

}
elseif($action==QUITA_DETALLE)
{
$sql="DELETE FROM epd_rendicion_evento WHERE cod_detalle='$cod'";
$result=mysql_query($sql) or die (mysql_error());

//5.- Redirecciono
$id=$_GET['id'];
	echo "<script>window.location ='m_rinde_evento.php?SES=$SES&anio=$anio&id=$id'</script>";	

}

elseif($action==ANULA)
{
	$sql="UPDATE epd_bd_demanda SET estado='2' WHERE cod_evento='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	echo "<script>window.location ='evento.php?SES=$SES&anio=$anio&modo=anula'</script>";
}


?>