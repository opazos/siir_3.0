<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$organizacion=$_POST['org'];
$dato=explode(",",$organizacion);
$tipo_documento=$dato[0];
$n_documento=$dato[1];

if ($action==ADD)
{
	if($_POST['org']==NULL or $_POST['linea']==NULL or $_POST['ifi']==NULL)
	{
		//Redirecciono
		echo "<script>window.location ='n_pdn.php?SES=$SES&anio=$anio&modo=$modo&error=vacio'</script>";
	}
	else
	{
		//1.- Para el caso de ampliaciones genero un pit con los datos que se me brindan para pdn con PIT busco el pir generado
		if ($_POST['pit']<>NULL and $modo==amp)
		{
			//a.- Busco los datos de la organizacion
			$sql="SELECT pit_bd_ficha_pit.cod_tipo_doc_taz, 
			pit_bd_ficha_pit.n_documento_taz
			FROM pit_bd_ficha_pit
			WHERE pit_bd_ficha_pit.cod_pit='".$_POST['pit']."'";
			$result=mysql_query($sql) or die (mysql_error());
			$r1=mysql_fetch_array($result);
			
			//b.- Inserto el PIT
			$sql="INSERT INTO pit_bd_ficha_pit VALUES('','3','".$r1['cod_tipo_doc_taz']."','".$r1['n_documento_taz']."','$fecha_hoy','','','0','','','0','','0','0','0','0','0','0','0','13','0','0','0','','','001','','0','','','0','1','1')";
			$result=mysql_query($sql) or die (mysql_error());
			
			//c.- Busco el registro generado
			$sql="SELECT * FROM pit_bd_ficha_pit ORDER BY cod_pit DESC LIMIT 0,1";
			$result=mysql_query($sql) or die (mysql_error());
			$r2=mysql_fetch_array($result);
			$pit=$r2['cod_pit'];
		}
		elseif($_POST['pit']==NULL and $modo==pit)
		{
			//si no es asi busco el PIT generado
			$sql="SELECT pit_bd_ficha_pit.cod_pit
			FROM pit_bd_ficha_pit INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pit.cod_tipo_doc_taz = org_ficha_organizacion.cod_tipo_doc_taz AND pit_bd_ficha_pit.n_documento_taz = org_ficha_organizacion.n_documento_taz
			WHERE org_ficha_organizacion.cod_tipo_doc='$tipo_documento' AND
			org_ficha_organizacion.n_documento='$n_documento'";
			$result=mysql_query($sql) or die (mysql_error());
			$r1=mysql_fetch_array($result);
			
			$pit=$r1['cod_pit'];
		}
		elseif($modo==ind or $modo==jov)
		{
			$pit= 0;
		}
		
		//2.- Realizo los calculos del PDN
		if ($modo==jov)
		{
			$n_apoyo=3;
			$monto=70;
			$apoyo=210;
		}
		else
		{
			$n_apoyo=2;
			$monto=280;
			$apoyo=560;
		}
		//3.- Clasifico el tipo de pdn
		if ($modo==jov)
		{
			$tipo=2;
		}
		elseif($modo==ind)
		{
			$tipo=1;
		}
		else
		{
			$tipo=0;
		}
		
		//4.- Ingreso la informacion del PDN
		$sql="INSERT INTO pit_bd_ficha_pdn VALUES('','4','$tipo_documento','$n_documento',UPPER('".$_POST['denominacion']."'),'".$_POST['linea']."','$fecha_hoy','','".$_POST['duracion']."','','$n_apoyo','$monto','$apoyo','".$_POST['fida']."','".$_POST['ro']."','1','".$_POST['n_cuenta']."','".$_POST['ifi']."','','','','','','','','','001','','','".$_POST['n_voucher']."','".$_POST['monto_org']."','','','','$tipo','0','$pit')";
		$result=mysql_query($sql) or die (mysql_error());
		
		//5.- obtengo el ultimo registro generado
		$sql="SELECT * FROM pit_bd_ficha_pdn ORDER BY cod_pdn DESC LIMIT 0,1";
		$result=mysql_query($sql) or die (mysql_error());
		$r3=mysql_fetch_array($result);
		
		$codigo=$r3['cod_pdn'];
		
		//6.- Ingresamos el dato de asistencia tecnica
		for($i=0;$i<=5;$i++)
		{
			if ($_POST['rubro'][$i]<>NULL)
			{	
				$aporte_pdss=(($_POST['dia'][$i]*$_POST['costo'][$i]*$_POST['semana'][$i]*$_POST['mes'][$i])*$_POST['ppdss'][$i])/100;
				$aporte_org=(($_POST['dia'][$i]*$_POST['costo'][$i]*$_POST['semana'][$i]*$_POST['mes'][$i])*$_POST['porg'][$i])/100;
				$total=$aporte_pdss+$aporte_org;
				
				$sql="INSERT INTO pit_bd_at_pdn VALUES('',UPPER('".$_POST['rubro'][$i]."'),UPPER('".$_POST['resultado'][$i]."'),UPPER('".$_POST['especialista'][$i]."'),'".$_POST['dia'][$i]."','".$_POST['costo'][$i]."','".$_POST['semana'][$i]."','".$_POST['mes'][$i]."','$total','$aporte_pdss','$aporte_org','".$_POST['ma'][$i]."','".$_POST['mb'][$i]."','".$_POST['mc'][$i]."','".$_POST['md'][$i]."','".$_POST['me'][$i]."','".$_POST['mf'][$i]."','".$_POST['mg'][$i]."','".$_POST['mh'][$i]."','".$_POST['mi'][$i]."','".$_POST['mj'][$i]."','".$_POST['mk'][$i]."','".$_POST['ml'][$i]."','$codigo')";
				$result=mysql_query($sql) or die (mysql_error());
			}
		}
		
		//7.- Guardamos la informacion de la visita guiada
		$total_visita=$_POST['aporte_pdss']+$_POST['aporte_org'];
		
		if ($_POST['f_visita']<>NULL)
		{
		$sql="INSERT INTO pit_bd_visita_pdn VALUES('','".$_POST['f_visita']."',UPPER('".$_POST['lugar_visita']."'),UPPER('".$_POST['resultado_visita']."'),'".$_POST['n_participantes']."','".$_POST['aporte_pdss']."','".$_POST['aporte_org']."','$total_visita','$codigo')";
		$result=mysql_query($sql) or die (mysql_error());
		}
		
		//8.- Ingreso la informacion de participacion en ferias
		
		$total_feria_1=$_POST['aporte_pdss_1']+$_POST['aporte_org_1'];
		$total_feria_2=$_POST['aporte_pdss_2']+$_POST['aporte_org_2'];
		
		if ($_POST['evento_1']<>NULL)
		{
		$sql="INSERT INTO pit_bd_feria_pdn VALUES('',UPPER('".$_POST['evento_1']."'),'".$_POST['fecha_fer_1']."',UPPER('".$_POST['lugar_1']."'),'".$_POST['n_part_1']."','".$_POST['aporte_pdss_1']."','".$_POST['aporte_org_1']."','$total_feria_1','$codigo')";
		$result=mysql_query($sql) or die (mysql_error());
		}
		
		if ($_POST['evento_2']<>NULL)
		{
		$sql="INSERT INTO pit_bd_feria_pdn VALUES('',UPPER('".$_POST['evento_2']."'),'".$_POST['fecha_fer_2']."',UPPER('".$_POST['lugar_2']."'),'".$_POST['n_part_2']."','".$_POST['aporte_pdss_2']."','".$_POST['aporte_org_2']."','$total_feria_2','$codigo')";
		$result=mysql_query($sql) or die (mysql_error());
		}
		
		//9.- Obtengo los datos que acabo de generar por cada rubro
		
		$sql="SELECT SUM(pit_bd_at_pdn.aporte_pdss) AS aporte_pdss, 
		SUM(pit_bd_at_pdn.aporte_org) AS aporte_org
		FROM pit_bd_at_pdn
		WHERE pit_bd_at_pdn.cod_pdn='$codigo'";
		$result=mysql_query($sql) or die (mysql_error());
		$f1=mysql_fetch_array($result);
		
		$fer_pdss=$_POST['aporte_pdss_1']+$_POST['aporte_pdss_2'];			
		$fer_org=$_POST['aporte_org_1']+$_POST['aporte_org_2'];			
		
		$sql="UPDATE pit_bd_ficha_pdn SET at_pdss='".$f1['aporte_pdss']."',at_org='".$f1['aporte_org']."',vg_pdss='".$_POST['aporte_pdss']."',vg_org='".$_POST['aporte_org']."',fer_pdss='$fer_pdss',fer_org='$fer_org' WHERE cod_pdn='$codigo'";
		$result=mysql_query($sql) or die (mysql_error());
		
		//10.- Obtengo las entidades cofinanciadoras
		for($i=0;$i<=3;$i++)
		{
			if($_POST['ente'][$i]<>NULL)
			{
				$sql="INSERT INTO pit_bd_cofi_pdn VALUES('',UPPER('".$_POST['ente'][$i]."'),'".$_POST['tipo_ente'][$i]."','".$_POST['aporte_ente'][$i]."','$codigo')";
				$result=mysql_query($sql) or die (mysql_error());
			}
			
		}
		
		//11.- Redireccionamos
		echo "<script>window.location ='n_pdn.php?SES=$SES&anio=$anio&modo=familia&cod=$codigo'</script>";
	}
}

elseif($action==ADD_FAM)
{
//1.- genero un array y guardo los campos
for($i=0;$i<count($_POST['campos']);$i++) 
{
$sql="INSERT IGNORE INTO pit_bd_user_iniciativa VALUES('008','".$_POST['campos'][$i]."','1','1','4','$cod')";
$result=mysql_query($sql) or die (mysql_error());
}
//2.- Redirecciono
echo "<script>window.location ='n_pdn.php?SES=$SES&anio=$anio&modo=familia&cod=$cod'</script>";
}

elseif($action==ADD_IND)
{
//1.- Situacion actual y perspectivas
for($i=0;$i<=5;$i++)
{
if ($_POST['situaciona'][$i]<>NULL)
{
$sql="INSERT INTO pdn_situacion VALUES('',UPPER('".$_POST['situaciona'][$i]."'),UPPER('".$_POST['situacionb'][$i]."'),'$cod')";
$result=mysql_query($sql) or die (mysql_error());
}
}
//2.- Apoyo recibido en los ultimos 3 años
for($i=0;$i<=5;$i++)
{
if($_POST['institucion'][$i]<>NULL)
{
$sql="INSERT INTO pdn_apoyo VALUES('',UPPER('".$_POST['institucion'][$i]."'),UPPER('".$_POST['mes'][$i]."'),UPPER('".$_POST['apoyo'][$i]."'),'$cod')";
$result=mysql_query($sql) or die (mysql_error());
}
}
//3.-	Eventos en los que a participado
for($i=0;$i<=5;$i++)
{
if ($_POST['evento'][$i]<>NULL)
{
$sql="INSERT INTO pdn_evento VALUES('',UPPER('".$_POST['lugar'][$i]."'),UPPER('".$_POST['evento'][$i]."'),'$cod')";
$result=mysql_query($sql) or die (mysql_error());
}
}
//4.- Situacion patrimonial
for($i=0;$i<=5;$i++)
{
if($_POST['tipo'][$i]<>NULL)
{
$total=$_POST['cantidad'][$i]*$_POST['costo'][$i];
$sql="INSERT INTO pdn_patrimonio VALUES('','".$_POST['tipo'][$i]."',UPPER('".$_POST['describepatrimonio'][$i]."'),UPPER('".$_POST['unidad'][$i]."'),'".$_POST['cantidad'][$i]."','".$_POST['costo'][$i]."','$total','$cod')";
$result=mysql_query($sql) or die (mysql_error());
}
}
//5.-Problemas y soluciones
for($i=0;$i<=5;$i++)
{
if ($_POST['tipoproblem'][$i]<>NULL)
{
$sql="INSERT INTO pdn_problema VALUES('','".$_POST['tipoproblem'][$i]."',UPPER('".$_POST['pro'][$i]."'),UPPER('".$_POST['sol'][$i]."'),'$cod')";
$result=mysql_query($sql) or die (mysql_error());
}
}


//6.- medio ambientes
if ($_POST['codigo']==NULL)
{
$sql="INSERT INTO pdn_ambiente VALUES('','".$_POST['opcion1']."','".$_POST['opcion2']."','".$_POST['opcion3']."','".$_POST['opcion4']."',UPPER('".$_POST['describe1']."'),UPPER('".$_POST['describe2']."'),UPPER('".$_POST['describe3']."'),UPPER('".$_POST['describe4']."'),'$cod')";
$result=mysql_query($sql) or die (mysql_error());
}
else
{
$sql="UPDATE pdn_ambiente SET opcion_1='".$_POST['opcion1']."',opcion_2='".$_POST['opcion2']."',opcion_3='".$_POST['opcion3']."',opcion_4='".$_POST['opcion4']."',descripcion_1='".$_POST['describe1']."',descripcion_2='".$_POST['describe2']."',descripcion_3='".$_POST['describe3']."',descripcion_4='".$_POST['descibe4']."' WHERE cod='".$_POST['codigo']."'";
$result=mysql_query($sql) or die (mysql_error());
}

//7.- redirecciono
echo "<script>window.location ='n_pdn.php?SES=$SES&anio=$anio&modo=indicadores&cod=$cod'</script>";

}

elseif($action==UPDATE)
{
//1.- Procedemos a actualizar la informacion del PDN
$sql="UPDATE pit_bd_ficha_pdn SET denominacion=UPPER('".$_POST['denominacion']."'),cod_linea_pdn='".$_POST['linea']."',mes='".$_POST['duracion']."',fuente_fida='".$_POST['fida']."',fuente_ro='".$_POST['ro']."',n_cuenta='".$_POST['n_cuenta']."',cod_ifi='".$_POST['ifi']."',n_voucher='".$_POST['n_voucher']."',monto_organizacion='".$_POST['monto_org']."' WHERE cod_pdn='".$_POST['codigo']."'";
$result=mysql_query($sql) or die (mysql_error());

$codigo=$_POST['codigo'];

//2.- Actualizo la informacion de la visita guiada
if ($_POST['cod_visita']==NULL)
{
$total=$_POST['aporte_pdss']+$_POST['aporte_org'];

$sql="INSERT INTO pit_bd_visita_pdn VALUES('','".$_POST['f_visita']."',UPPER('".$_POST['lugar_visita']."'),UPPER('".$_POST['resultado_visita']."'),'".$_POST['n_participantes']."','".$_POST['aporte_pdss']."','".$_POST['aporte_org']."','$total','".$_POST['codigo']."')";
}
else
{
$total=$_POST['aporte_pdss']+$_POST['aporte_org'];

$sql="UPDATE pit_bd_visita_pdn SET f_visita='".$_POST['f_visita']."',itinerario=UPPER('".$_POST['lugar_visita']."'),resultados=UPPER('".$_POST['resultado_visita']."'),participantes='".$_POST['n_participantes']."',aporte_pdss='".$_POST['aporte_pdss']."',aporte_org='".$_POST['aporte_org']."',aporte_total='$total' WHERE cod_visita_pdn='".$_POST['cod_visita']."'";
}
$result=mysql_query($sql) or die (mysql_error());


//3.- Actualizamos la informacion de la participacion en ferias
foreach($fecha_fer as $cad=>$a1)
{
$at=$_POST['aporte_necs'][$cad]+$_POST['aporte_pdns'][$cad];

$sql="UPDATE pit_bd_feria_pdn SET nombre=UPPER('".$_POST['evento'][$cad]."'),f_realizacion='".$_POST['fecha_fer'][$cad]."',lugar=UPPER('".$_POST['lugar'][$cad]."'),participantes='".$_POST['n_part'][$cad]."',aporte_pdss='".$_POST['aporte_necs'][$cad]."',aporte_org='".$_POST['aporte_pdns'][$cad]."',aporte_total='$at' WHERE cod_feria_pdn='$cad'";
$result=mysql_query($sql) or die (mysql_error());
}

//4.- Actualizamos la informacion de asistencia tecnica
foreach($rubros as $cod=>$b1)
{

				$aporte_pdssa=(($_POST['dias'][$cod]*$_POST['costos'][$cod]*$_POST['semanas'][$cod]*$_POST['mess'][$cod])*$_POST['ppdsss'][$cod])/100;
				$aporte_orga=(($_POST['dias'][$cod]*$_POST['costos'][$cod]*$_POST['semanas'][$cod]*$_POST['mess'][$cod])*$_POST['porgs'][$cod])/100;
				$total_ata=$aporte_pdss+$aporte_org;

				$sql="UPDATE pit_bd_at_pdn SET rubro=UPPER('".$_POST['rubros'][$cod]."'),resultado=UPPER('".$_POST['resultados'][$cod]."'),rubro_especialista=UPPER('".$_POST['especialistas'][$cod]."'),n_dia='".$_POST['dias'][$cod]."',costo_dia='".$_POST['costos'][$cod]."',n_semana='".$_POST['semanas'][$cod]."',n_mes='".$_POST['mess'][$cod]."',aporte_total='$total_ata',aporte_pdss='$aporte_pdssa',aporte_org='$aporte_orga',ene='".$_POST['mas'][$cod]."',feb='".$_POST['mbs'][$cod]."',mar='".$_POST['mcs'][$cod]."',abr='".$_POST['mds'][$cod]."',may='".$_POST['mes'][$cod]."',jun='".$_POST['mfs'][$cod]."',jul='".$_POST['mgs'][$cod]."',ago='".$_POST['mhs'][$cod]."',sep='".$_POST['mis'][$cod]."',oct='".$_POST['mjs'][$cod]."',nov='".$_POST['mks'][$cod]."',dic='".$_POST['mls'][$cod]."' WHERE cod_at='$cod'";
				$result=mysql_query($sql) or die (mysql_error());
}
//5.- Generamos informacion de asistencia tecnica
		for($i=0;$i<=1;$i++)
		{
			if ($_POST['rubro'][$i]<>NULL)
			{	
				$aporte_pdss=(($_POST['dia'][$i]*$_POST['costo'][$i]*$_POST['semana'][$i]*$_POST['mes'][$i])*$_POST['ppdss'][$i])/100;
				$aporte_org=(($_POST['dia'][$i]*$_POST['costo'][$i]*$_POST['semana'][$i]*$_POST['mes'][$i])*$_POST['porg'][$i])/100;
				$total=$aporte_pdss+$aporte_org;
				
				$sql="INSERT INTO pit_bd_at_pdn VALUES('',UPPER('".$_POST['rubro'][$i]."'),UPPER('".$_POST['resultado'][$i]."'),UPPER('".$_POST['especialista'][$i]."'),'".$_POST['dia'][$i]."','".$_POST['costo'][$i]."','".$_POST['semana'][$i]."','".$_POST['mes'][$i]."','$total','$aporte_pdss','$aporte_org','".$_POST['ma'][$i]."','".$_POST['mb'][$i]."','".$_POST['mc'][$i]."','".$_POST['md'][$i]."','".$_POST['me'][$i]."','".$_POST['mf'][$i]."','".$_POST['mg'][$i]."','".$_POST['mh'][$i]."','".$_POST['mi'][$i]."','".$_POST['mj'][$i]."','".$_POST['mk'][$i]."','".$_POST['ml'][$i]."','$codigo')";
				$result=mysql_query($sql) or die (mysql_error());
			}
		}

//6.- Obtengo los datos que acabo de generar por cada rubro
		
		$sql="SELECT SUM(pit_bd_at_pdn.aporte_pdss) AS aporte_pdss, 
		SUM(pit_bd_at_pdn.aporte_org) AS aporte_org
		FROM pit_bd_at_pdn
		WHERE pit_bd_at_pdn.cod_pdn='$codigo'";
		$result=mysql_query($sql) or die (mysql_error());
		$f1=mysql_fetch_array($result);
		
		$sql="SELECT SUM(pit_bd_feria_pdn.aporte_pdss) AS aporte_pdss, 
		SUM(pit_bd_feria_pdn.aporte_org) AS aporte_org
		FROM pit_bd_feria_pdn
		WHERE pit_bd_feria_pdn.cod_pdn='$codigo'";
		$result=mysql_query($sql) or die (mysql_error());
		$f2=mysql_fetch_array($result);
	
		
		$sql="UPDATE pit_bd_ficha_pdn SET at_pdss='".$f1['aporte_pdss']."',at_org='".$f1['aporte_org']."',vg_pdss='".$_POST['aporte_pdss']."',vg_org='".$_POST['aporte_org']."',fer_pdss='".$f2['aporte_pdss']."',fer_org='".$f2['aporte_org']."' WHERE cod_pdn='$codigo'";
		$result=mysql_query($sql) or die (mysql_error());
		
//7.- Guardamos la informacion de  cofinanciadores

for($i=0;$i<=3;$i++)
{
	if($_POST['ente'][$i]<>NULL)
	{
		$sql="INSERT INTO pit_bd_cofi_pdn VALUES('',UPPER('".$_POST['ente'][$i]."'),'".$_POST['tipo_ente'][$i]."','".$_POST['aporte_ente'][$i]."','$codigo')";
		$result=mysql_query($sql) or die (mysql_error());
	}

}

foreach($entes as $cad=>$a)
{
	$sql="UPDATE pit_bd_cofi_pdn SET nombre=UPPER('".$_POST['entes'][$cad]."'),cod_tipo_ente='".$_POST['tipo_entes'][$cad]."',aporte='".$_POST['aporte_entes'][$cad]."' WHERE cod_cofinanciador='$cad'";
	$result=mysql_query($sql) or die (mysql_error());
}


		
		
//.- Redireccionamos
		echo "<script>window.location ='m_pdn.php?SES=$SES&anio=$anio&id=$codigo'</script>";
}
elseif($action==DELETE)
{
	$sql="DELETE FROM pit_bd_ficha_pdn WHERE cod_pdn='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
//7.- Redireccionamos
		echo "<script>window.location ='pdn.php?SES=$SES&anio=$anio&modo=delete'</script>";	
}


elseif($action==DELETE1)
{
$sql="DELETE FROM pdn_situacion WHERE cod='$id'";
$result=mysql_query($sql) or die (mysql_error());

echo "<script>window.location ='n_pdn.php?SES=$SES&anio=$anio&modo=$modo&cod=$cod'</script>";
}
elseif($action==DELETE3)
{
$sql="DELETE FROM pdn_patrimonio WHERE cod='$id'";
$result=mysql_query($sql) or die (mysql_error());

echo "<script>window.location ='n_pdn.php?SES=$SES&anio=$anio&modo=$modo&cod=$cod'</script>";
}
elseif($action==DELETE_PROBLEM)
{
$sql="DELETE FROM pdn_problema WHERE cod='$id'";
$result=mysql_query($sql) or die (mysql_error());

echo "<script>window.location ='n_pdn.php?SES=$SES&anio=$anio&modo=$modo&cod=$cod'</script>";
}
elseif($action==DELETE_USER)
{
	$sql="DELETE FROM pit_bd_user_iniciativa WHERE n_documento='$id' AND cod_tipo_iniciativa='4' AND cod_iniciativa='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	
echo "<script>window.location ='n_pdn.php?SES=$SES&anio=$anio&modo=familia&cod=$cod'</script>";	
}
elseif($action==DELETE_EC)
{
	$sql="DELETE FROM pdn_evento WHERE cod='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	echo "<script>window.location ='n_pdn.php?SES=$SES&anio=$anio&modo=indicadores&cod=$cod'</script>";
}
elseif($action==DELETE_APOYO)
{
	$sql="DELETE FROM pdn_apoyo WHERE cod='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	echo "<script>window.location ='n_pdn.php?SES=$SES&anio=$anio&modo=indicadores&cod=$cod'</script>";	
}

?>