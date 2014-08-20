<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//Calculo de dias entre 2 fechas
//Defino la fecha 1
$fecha1=$_POST['f_inicio'];
$ano1 = substr("$fecha1",0,4);
$mes1 = substr("$fecha1",5,2);
$dia1 = substr("$fecha1",8,2);

//Defino fecha 2
$fecha2=$_POST['f_termino'];
$ano2 = substr("$fecha2",0,4);
$mes2 = substr("$fecha2",5,2);
$dia2 = substr("$fecha2",8,2);

//calculo timestam de las dos fechas
$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1);
$timestamp2 = mktime(4,12,0,$mes2,$dia2,$ano2);

//resto a una fecha la otra
$segundos_diferencia = $timestamp1 - $timestamp2;
//echo $segundos_diferencia;

//convierto segundos en días
$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);

//obtengo el valor absoulto de los días (quito el posible signo negativo)
$dias_diferencia = abs($dias_diferencia);

//quito los decimales a los días de diferencia
$dias_diferencia = floor($dias_diferencia);

//ahora calculo los meses
$dias=number_format($dias_diferencia+1);

if ($action==ADD)
{
//1.- Actualizamos la numeracion
$sql="UPDATE sys_bd_numera_dependencia SET n_demanda_gm='".$_POST['n_contrato']."' WHERE cod='".$_POST['codigo_numera']."'";
$result=mysql_query($sql) or die (mysql_error());

//2.- Verificamos si la organizacion es nueva o no
if ($_POST['org']==0)
{
$oficina=$row['cod_dependencia'];
$documento="0000-0".$oficina;

//si es nueva la registramos
$sql="INSERT INTO org_ficha_organizacion VALUES('".$_POST['tipo_doc']."','".$_POST['n_documento']."',UPPER('".$_POST['nombre']."'),'".$_POST['tipo_org']."','','','".$_POST['select1']."','".$_POST['select2']."','".$_POST['select3']."',UPPER('".$_POST['direccion']."'),'','','$oficina','0','000','$documento')";
$result=mysql_query($sql) or die (mysql_error());

$tipo_documento=$_POST['tipo_doc'];
$n_documento=$_POST['n_documento'];
}
//Si no es nueva entonces jalamos los datos
elseif($_POST['org']<>0)
{
$organizacion=$_POST['org'];
$dato=explode(",",$organizacion);

$tipo_documento=$dato[0];
$n_documento=$dato[1];
}

//3.- Buscamos el codigo POA
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




//3.- Procedemos a registrar los datos del evento
$sql="INSERT INTO gm_ficha_evento VALUES('','".$_POST['n_contrato']."','2',UPPER('".$_POST['tema']."'),'".$_POST['f_inicio']."','".$_POST['f_termino']."','$dias','".$_POST['f_contrato']."','".$_POST['f_contrato']."',UPPER('".$_POST['objetivo']."'),UPPER('".$_POST['resultado']."'),'".$_POST['participantes']."','$componente','$poa','$categoria','".$_POST['n_contrato']."','".$_POST['n_contrato']."','".$_POST['fida']."','".$_POST['ro']."','".$row['cod_dependencia']."','005','".$_POST['f_contrato']."')";
$result=mysql_query($sql) or die (mysql_error());

//4.- Buscamos el ultimo registro generado
$sql="SELECT * FROM gm_ficha_evento ORDER BY cod_ficha_gm DESC LIMIT 0,1	";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$codigo=$r1['cod_ficha_gm'];

//5.- Generamos el itinerario
for ($i=0;$i<=5;$i++)
{
	if($_POST['fecha'][$i]<>NULL)
	{
		
		$ubicacion=$_POST['lugar'][$i];
		$dato=explode("/",$ubicacion);
		$dep=$dato[0];
		$prov=$dato[1];
		$dist=$dato[2];
		
		$sql="INSERT INTO gm_ficha_itinerario VALUES('','".$_POST['fecha'][$i]."',UPPER('$dep'),UPPER('$prov'),UPPER('$dist'),UPPER('".$_POST['institucion'][$i]."'),UPPER('".$_POST['tematica'][$i]."'),UPPER('".$_POST['actividades'][$i]."'),'$codigo')";
		$result=mysql_query($sql) or die (mysql_error());
	}
}

//6.- Genero la ficha de presupuesto
for ($a=0;$a<=20;$a++)
{
if ($_POST['detalle'][$a]<>NULL)
{
$total_costo=$_POST['cantidad'][$a]*$_POST['precio'][$a];
$sql="INSERT INTO gm_ficha_presupuesto VALUES('','".$_POST['tipo_gasto'][$a]."','".$_POST['cof'][$a]."',UPPER('".$_POST['detalle'][$a]."'),UPPER('".$_POST['unidad'][$a]."'),'".$_POST['cantidad'][$a]."','".$_POST['precio'][$a]."','$total_costo','$codigo')";
$result=mysql_query($sql) or die (mysql_error());
}
}

//7.- Genero la ficha de entidad contratante
$sql="INSERT INTO gm_ficha_contratante VALUES('','$tipo_documento','$n_documento','".$_POST['dni1']."',UPPER('".$_POST['nombre1']."'),UPPER('".$_POST['cargo1']."'),'".$_POST['dni2']."',UPPER('".$_POST['nombre2']."'),UPPER('".$_POST['cargo2']."'),'1','1','".$_POST['n_cuenta']."','".$_POST['ifi']."','$codigo')";
$result=mysql_query($sql) or die (mysql_error());

//8.- Redirecciono
	echo "<script>window.location ='../print/print_contrato_gm.php?SES=$SES&anio=$anio&cod=$codigo'</script>";	

}
elseif($action==UPDATE)
{
//1.- Buscamos el codigo POA
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


//2.- Actualizo la info de demanda
$sql="UPDATE gm_ficha_evento SET n_ficha_gm='".$_POST['n_contrato']."',tema=UPPER('".$_POST['tema']."'),f_inicio='".$_POST['f_inicio']."',f_termino='".$_POST['f_termino']."',dias='$dias',f_propuesta='".$_POST['f_contrato']."',f_conformidad='".$_POST['f_contrato']."',objetivo=UPPER('".$_POST['objetivo']."'),resultado=UPPER('".$_POST['resultado']."'),participantes='".$_POST['participantes']."',cod_componente='$componente',cod_subactividad='$poa',cod_categoria='$categoria',n_contrato='".$_POST['n_contrato']."',n_atf='".$_POST['n_contrato']."',cof_fida='".$_POST['fida']."',cof_ro='".$_POST['ro']."',f_presentacion='".$_POST['f_contrato']."' WHERE cod_ficha_gm='".$_POST['codigo']."'";
$result=mysql_query($sql) or die (mysql_error());

$codigo=$_POST['codigo'];


//3.- Verifico que exista entidad contratante
$sql="SELECT * FROM gm_ficha_contratante WHERE cod_ficha_gm='".$_POST['codigo']."'";
$result=mysql_query($sql) or die (mysql_error());
$contratante=mysql_num_rows($result);


if ($contratante==0)
{
//Si no existe lo creo

$organizacion=$_POST['org'];
$dato=explode(",",$organizacion);

$tipo_documento=$dato[0];
$n_documento=$dato[1];


$sql="INSERT INTO gm_ficha_contratante VALUES('','$tipo_documento','$n_documento','".$_POST['dni1']."',UPPER('".$_POST['nombre1']."'),UPPER('".$_POST['cargo1']."'),'".$_POST['dni2']."',UPPER('".$_POST['nombre2']."'),UPPER('".$_POST['cargo2']."'),'1','1','".$_POST['n_cuenta']."','".$_POST['ifi']."','".$_POST['codigo']."')";
$result=mysql_query($sql) or die (mysql_error());

}
else
{
//Si existe lo actualizo
$sql="UPDATE gm_ficha_contratante SET dni_1='".$_POST['dni1']."',representante_1=UPPER('".$_POST['nombre1']."'),cargo_1=UPPER('".$_POST['cargo1']."'),dni_2='".$_POST['dni2']."',representante_2=UPPER('".$_POST['nombre2']."'),cargo_2=UPPER('".$_POST['cargo2']."'),n_cuenta='".$_POST['n_cuenta']."',cod_ifi='".$_POST['ifi']."' WHERE cod_ficha_contratante='".$_POST['codigo_entidad']."'";
$result=mysql_query($sql) or die (mysql_error());
}







//4.- actualizo el itinerario
foreach($fechas as $ca=>$a1)
{
$sql="UPDATE gm_ficha_itinerario SET f_itinerario='$a1' WHERE cod_itinerario='$ca'";
$result=mysql_query($sql) or die (mysql_error());
}
foreach($lugars as $ca=>$b1)
{

		$ubicacion=$b1;
		$dato=explode("/",$ubicacion);
		$dep=$dato[0];
		$prov=$dato[1];
		$dist=$dato[2];

$sql="UPDATE gm_ficha_itinerario SET departamento='$dep',provincia='$prov',distrito='$dist' WHERE cod_itinerario='$ca'";
$result=mysql_query($sql) or die (mysql_error());
}
foreach($institucions as $ca=>$c1)
{
$sql="UPDATE gm_ficha_itinerario SET lugar=UPPER('$c1') WHERE cod_itinerario='$ca'";
$result=mysql_query($sql) or die (mysql_error());
}
foreach($tematicas as $ca=>$d1)
{
$sql="UPDATE gm_ficha_itinerario SET tematica=UPPER('$d1') WHERE cod_itinerario='$ca'";
$result=mysql_query($sql) or die (mysql_error());
}
foreach($actividadess as $ca=>$e1)
{
$sql="UPDATE gm_ficha_itinerario SET actividades=UPPER('$e1') WHERE cod_itinerario='$ca'";
$result=mysql_query($sql) or die (mysql_error());
}
//3.1 procedemos a registrar los nuevos registros
for ($i=0;$i<=5;$i++)
{
	if($_POST['fecha'][$i]<>NULL)
	{
		
		$ubicacion=$_POST['lugar'][$i];
		$dato=explode("/",$ubicacion);
		$dep=$dato[0];
		$prov=$dato[1];
		$dist=$dato[2];
		
		$sql="INSERT INTO gm_ficha_itinerario VALUES('','".$_POST['fecha'][$i]."',UPPER('$dep'),UPPER('$prov'),UPPER('$dist'),UPPER('".$_POST['institucion'][$i]."'),UPPER('".$_POST['tematica'][$i]."'),UPPER('".$_POST['actividades'][$i]."'),'$codigo')";
		$result=mysql_query($sql) or die (mysql_error());
	}
}

//4.- Actualizo el presupuesto
foreach($cofs as $cb=>$a2)
{
	$sql="UPDATE gm_ficha_presupuesto SET cod_entidad='$a2' WHERE cod_presupuesto='$cb'";
	$result=mysql_query($sql) or die (mysql_error());
}
foreach($tipo_gastos as $cb=>$b2)
{
	$sql="UPDATE gm_ficha_presupuesto SET cod_tipo_gasto='$b2' WHERE cod_presupuesto='$cb'";
	$result=mysql_query($sql) or die (mysql_error());
}
foreach($detalles as $cb=>$c2)
{
	$sql="UPDATE gm_ficha_presupuesto SET detalle='$c2' WHERE cod_presupuesto='$cb'";
	$result=mysql_query($sql) or die (mysql_error());
}
foreach($unidads as $cb=>$d2)
{
	$sql="UPDATE gm_ficha_presupuesto SET unidad='$d2' WHERE cod_presupuesto='$cb'";
	$result=mysql_query($sql) or die (mysql_error());
}
foreach($precios as $cb=>$e2)
{
	$sql="UPDATE gm_ficha_presupuesto SET costo_unitario='$e2' WHERE cod_presupuesto='$cb'";
	$result=mysql_query($sql) or die (mysql_error());
}
foreach($cantidads as $cb=>$f2)
{
	$sql="UPDATE gm_ficha_presupuesto SET cantidad='$f2' WHERE cod_presupuesto='$cb'";
	$result=mysql_query($sql) or die (mysql_error());
}

foreach( $precios as $cb => $g2 ) 
{
   $total=$g2*$cantidads[$cb];

   $sql="UPDATE gm_ficha_presupuesto SET costo_total='$total' WHERE cod_presupuesto='$cb'";
   $result=mysql_query($sql) or die (mysql_error());
}

//5.- Añado los nuevos campos
for ($a=0;$a<=20;$a++)
{
if ($_POST['detalle'][$a]<>NULL)
{
$total_costo=$_POST['cantidad'][$a]*$_POST['precio'][$a];
$sql="INSERT INTO gm_ficha_presupuesto VALUES('','".$_POST['tipo_gasto'][$a]."','".$_POST['cof'][$a]."',UPPER('".$_POST['detalle'][$a]."'),UPPER('".$_POST['unidad'][$a]."'),'".$_POST['cantidad'][$a]."','".$_POST['precio'][$a]."','$total_costo','$codigo')";
$result=mysql_query($sql) or die (mysql_error());
}
}

//6.- redirecciono
	echo "<script>window.location ='../print/print_contrato_gm.php?SES=$SES&anio=$anio&cod=$codigo'</script>";	
}
elseif($action==ANULA)
{
$sql="UPDATE gm_ficha_evento SET cod_estado_iniciativa='000' WHERE cod_ficha_gm='$id'";
$result=mysql_query($sql) or die (mysql_error());

//8.- Redirecciono
echo "<script>window.location ='contrato_gira.php?SES=$SES&anio=$anio&modo=anula&alert=success_change'</script>";	
}
elseif($action==LIQUIDA)
{
//1.- obtengo el numero de la rendicion
$sql="SELECT sys_bd_numera_dependencia.cod, 
	sys_bd_numera_dependencia.n_informe_pd
FROM sys_bd_numera_dependencia
WHERE sys_bd_numera_dependencia.cod_dependencia='".$row['cod_dependencia']."' AND
sys_bd_numera_dependencia.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$n_rendicion=$r1['n_informe_pd']+1;

//2.- Actualizo la numeracion
$sql="UPDATE sys_bd_numera_dependencia SET n_informe_pd='$n_rendicion' WHERE cod='".$r1['cod']."'";
$result=mysql_query($sql) or die (mysql_error());

//3.- Ingreso la informacion
$sql="INSERT INTO gm_ficha_cierre VALUES('','$n_rendicion','$id','".$_POST['dirigido']."',UPPER('".$_POST['resultado']."'),UPPER('".$_POST['problema']."'),'".$_POST['a1']."','".$_POST['a2']."','".$_POST['a3']."','".$_POST['b1']."','".$_POST['b2']."','".$_POST['b3']."','".$_POST['c1']."','".$_POST['c2']."','".$_POST['c3']."','".$_POST['ejec_pdss']."','".$_POST['ejec_org']."','".$_POST['ejec_mun']."','".$_POST['ejec_otr']."','".$_POST['f_rendicion']."')";
$result=mysql_query($sql) or die (mysql_error());

//4.- busca la info generada
$sql="SELECT * FROM gm_ficha_cierre ORDER BY cod_informe_gm DESC LIMIT 0,1";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

$codigo=$r2['cod_informe_gm'];

//5.- actualizo el estdo
$sql="UPDATE gm_ficha_evento SET cod_estado_iniciativa='004' WHERE cod_ficha_gm='$id'";
$result=mysql_query($sql) or die (mysql_error());

//6.- redirecciono
	echo "<script>window.location ='../print/print_liquida_gm.php?SES=$SES&anio=$anio&cod=$codigo'</script>";	
}
elseif($action==UPDATE_LIQUIDA)
{
	//1.- Actualizo la info
	$sql="UPDATE gm_ficha_cierre SET persona_dirigido='".$_POST['dirigido']."',resultados='".$_POST['resultado']."',problemas='".$_POST['problema']."',aut_var='".$_POST['a1']."',aut_muj='".$_POST['a2']."',aut_jov='".$_POST['a3']."',dir_var='".$_POST['b1']."',dir_muj='".$_POST['b2']."',dir_jov='".$_POST['b3']."',otr_var='".$_POST['c1']."',otr_muj='".$_POST['c2']."',otr_jov='".$_POST['c3']."',ejec_pdss='".$_POST['ejec_pdss']."',ejec_org='".$_POST['ejec_org']."',ejec_mun='".$_POST['ejec_mun']."',ejec_otro='".$_POST['ejec_otr']."',f_informe='".$_POST['f_rendicion']."' WHERE cod_informe_gm='".$_POST['codigo']."'";
	$result=mysql_query($sql) or die (msyql_error());
	
	$codigo=$_POST['codigo'];
	
	//6.- redirecciono
	echo "<script>window.location ='../print/print_liquida_gm.php?SES=$SES&anio=$anio&cod=$codigo'</script>";	
	
}


?>