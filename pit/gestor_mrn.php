<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

if ($action==ADD)
{
/*Validacion de combos */
if ($_POST['mrn']==NULL)
{
echo "<script>window.location ='n_mrn.php?SES=$SES&anio=$anio&modo=mrn&error=vacio'</script>";
}

if ($_POST['ifi']==NULL)
{
echo "<script>window.location ='n_mrn.php?SES=$SES&anio=$anio&modo=mrn&error=vacio'</script>";
}

$fecha=$_POST['f_inicio'];
$mes=$_POST['duracion'];

$fecha_actualizada = dateadd1($fecha,7,0,0,0,0,0); // suma 7 dias a la fecha dada
$f_termino=dateadd1($fecha,5,$mes,0,0,0,0);

	if ($f_termino>='2014-09-15')
	{
		$f_termino='2014-09-30';
	}
	else
	{
		$f_termino=$f_termino;
	}

//1.- Verificamos que esta organizacion tenga un pit que la respalde
$organizacion=$_POST['mrn'];
$dato=explode(",",$organizacion);
$tipo_documento=$dato[0];
$n_documento=$dato[1];

$sql="SELECT pit_bd_ficha_pit.cod_pit
FROM pit_bd_ficha_pit INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pit.cod_tipo_doc_taz = org_ficha_organizacion.cod_tipo_doc_taz AND pit_bd_ficha_pit.n_documento_taz = org_ficha_organizacion.n_documento_taz
WHERE org_ficha_organizacion.cod_tipo_doc='$tipo_documento' AND
org_ficha_organizacion.n_documento='$n_documento' AND
pit_bd_ficha_pit.cod_estado_iniciativa=001";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);
$total_pit=mysql_num_rows($result);

if ($total_pit==0)
{
echo "<script>window.location ='n_mrn.php?SES=$SES&anio=$anio&modo=mrn&error=pit_null'</script>";
}
else
{
//2.- procedemos a registrar el PGRN
$pit=$r1['cod_pit'];

$sql="INSERT INTO pit_bd_ficha_mrn VALUES('','5','$tipo_documento','$n_documento',UPPER('".$_POST['sector']."'),UPPER('".$_POST['lema']."'),'$fecha_hoy','".$_POST['f_inicio']."','".$_POST['duracion']."','$f_termino','".$_POST['cif']."','".$_POST['at_pdss']."','".$_POST['at_org']."','".$_POST['vg_pdss']."','".$_POST['vg_org']."','".$_POST['ag']."','0','0','1','".$_POST['n_cuenta']."','".$_POST['ifi']."','0','0','001','".$_POST['n_voucher']."','".$_POST['aporte_org']."','','','0','0','$pit')";
$result=mysql_query($sql) or die (mysql_error());

//3.- Buscamos el registro generado
$sql="SELECT * FROM pit_bd_ficha_mrn ORDER BY cod_mrn DESC LIMIT 0,1";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

$codigo=$r2['cod_mrn'];

//4.- Registramos los entes cofinanciadores de PGRN
for($i=0;$i<=3;$i++)
{
	if($_POST['ente'][$i]<>NULL)
	{
		$sql="INSERT INTO pit_bd_cofi_mrn VALUES('',UPPER('".$_POST['ente'][$i]."'),'".$_POST['tipo_ente'][$i]."','".$_POST['aporte_ente'][$i]."','$codigo')";
		$result=mysql_query($sql) or die (mysql_error());
	}
}

//5.- Redireccionamos
echo "<script>window.location ='n_mrn.php?SES=$SES&anio=$anio&modo=familia&cod=$codigo'</script>";
}

}
elseif($action==UPDATE)
{

$fecha=$_POST['f_inicio'];
$mes=$_POST['duracion'];

$fecha_actualizada = dateadd1($fecha,7,0,0,0,0,0); // suma 7 dias a la fecha dada
$f_termino=dateadd1($fecha,5,$mes,0,0,0,0);

	if ($f_termino>='2014-09-15')
	{
		$f_termino='2014-09-30';
	}
	else
	{
		$f_termino=$f_termino;
	}

//1.- Guardo la info
$sql="UPDATE pit_bd_ficha_mrn SET sector=UPPER('".$_POST['sector']."'),lema=UPPER('".$_POST['lema']."'),f_inicio='".$_POST['f_inicio']."',mes='".$_POST['duracion']."',f_termino='$f_termino',cif_pdss='".$_POST['cif']."',at_pdss='".$_POST['at_pdss']."',at_org='".$_POST['at_org']."',vg_pdss='".$_POST['vg_pdss']."',vg_org='".$_POST['vg_org']."',ag_pdss='".$_POST['ag']."',n_cuenta='".$_POST['n_cuenta']."',cod_ifi='".$_POST['ifi']."',n_voucher='".$_POST['n_voucher']."',monto_organizacion='".$_POST['aporte_org']."' WHERE cod_mrn='".$_POST['codigo']."'";
$result=mysql_query($sql) or die (mysql_error());

$codigo=$_POST['codigo'];


//2.- Guardo Entes cofinanciadores
for($i=0;$i<=3;$i++)
{
	if($_POST['ente'][$i]<>NULL)
	{
		$sql="INSERT INTO pit_bd_cofi_mrn VALUES('',UPPER('".$_POST['ente'][$i]."'),'".$_POST['tipo_ente'][$i]."','".$_POST['aporte_ente'][$i]."','$codigo')";
		$result=mysql_query($sql) or die (mysql_error());
	}
}

foreach($entes as $cad=>$a)
{
	$sql="UPDATE pit_bd_cofi_mrn SET nombre=UPPER('".$_POST['entes'][$cad]."'),cod_tipo_ente='".$_POST['tipo_entes'][$cad]."',aporte='".$_POST['aporte_entes'][$cad]."' WHERE cod_cofinanciador='$cad'";
	$result=mysql_query($sql) or die (mysql_error());
}

//3.- Redirecciono
echo "<script>window.location ='m_mrn.php?SES=$SES&anio=$anio&modo=familia&cod=$codigo'</script>";
}
elseif($action==ADD_FAM)
{
//1.- Generamos un array
for($i=0;$i<count($_POST['campos']);$i++) 
{
$sql="INSERT IGNORE INTO pit_bd_user_iniciativa VALUES('008','".$_POST['campos'][$i]."','1','1','5','$cod')";
$result=mysql_query($sql) or die (mysql_error());
}
//2.- Procedo a redireccionar
echo "<script>window.location ='n_mrn.php?SES=$SES&anio=$anio&modo=familia&cod=$cod'</script>";

}
elseif($action==ADD_INFO)
{
//1.- Actualizamos e ingresamos la info de actividades actuales
foreach($actividad_presente as $cad=>$a1)
{
	$sql="UPDATE mrn_actividad_actual SET cod_actividad='".$_POST['actividad_presente'][$cad]."',nivel='".$_POST['nivel_presente'][$cad]."',n_familia='".$_POST['familias_presente'][$cad]."' WHERE cod='$cad'";
	$result=mysql_query($sql) or die (mysql_error());
}


for ($i=0; $i<=5; $i++) 
{
	if ($_POST['actividad_presentes'][$i]<>NULL)
	{
		$sql="INSERT INTO mrn_actividad_actual VALUES('','".$_POST['actividad_presentes'][$i]."','".$_POST['nivel_presentes'][$i]."','".$_POST['familias_presentes'][$i]."','$cod')";
		$result=mysql_query($sql) or die (mysql_error());
	}
}


//2.- Actualizamos la informacion de actividades futuras
foreach($actividad_futura as $ced=>$a2)
{
	$sql="UPDATE mrn_actividad_futuro SET cod_actividad='".$_POST['actividad_futura'][$ced]."',nivel='".$_POST['nivel_futuro'][$ced]."',n_familia='".$_POST['familia_futuro'][$ced]."' WHERE cod='$ced'";
	$result=mysql_query($sql) or die (mysql_error());

}




for ($i = 0; $i <=5; $i++) 
{
	if ($_POST['actividad_futuras'][$i]<>NULL)
	{
		$sql="INSERT INTO mrn_actividad_futuro VALUES('','".$_POST['actividad_futuras'][$i]."','".$_POST['nivel_futuros'][$i]."','".$_POST['familia_futuros'][$i]."','$cod')";
		$result=mysql_query($sql) or die (mysql_error());
	}
}


//3.- Guardamos la info del area
$sql="SELECT * FROM mrn_area WHERE cod_mrn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$total=mysql_num_rows($result);

if ($total==0)
{
$sql="INSERT INTO mrn_area VALUES('','".$_POST['area_a']."','".$_POST['area_b']."','".$_POST['area_c']."','".$_POST['area_d']."','".$_POST['area_e']."','".$_POST['area_f']."','$cod')";
$result=mysql_query($sql) or die (mysql_error());
}
else
{
$sql="UPDATE mrn_area SET a1='".$_POST['area_a']."',a2='".$_POST['area_b']."',a3='".$_POST['area_c']."',a4='".$_POST['area_d']."',a5='".$_POST['area_e']."',a6='".$_POST['area_f']."' WHERE cod_mrn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
}


//4.- Guardo la info de concurso
foreach($mes as $cid=>$a3)
{
	$sql="UPDATE mrn_concurso SET mes='".$_POST['mes'][$cid]."',anio='".$_POST['anio'][$cid]."',linea='".$_POST['linea'][$cid]."' WHERE cod='$cid'";
	$result=mysql_query($sql) or die (mysql_error());
}


//4.1.- Si hubiera nuevos registros, procedo a ingresarlos
for($i=0; $i<=3; $i++)
{
	if($_POST['mess'][$i]<>NULL)
	{
		$sql="INSERT INTO mrn_concurso VALUES('','".$_POST['mess'][$i]."','".$_POST['anios'][$i]."',UPPER('".$_POST['lineas'][$i]."'),'$cod')";
		$result=mysql_query($sql) or die (mysql_error());
	}
}

//5.- redirecciono
echo "<script>window.location ='n_mrn.php?SES=$SES&anio=$anio&modo=inform&cod=$cod'</script>";
}
elseif($action==DELETE_USER)
{
$sql="DELETE FROM pit_bd_user_iniciativa WHERE n_documento='$id' AND cod_tipo_iniciativa='5' AND cod_iniciativa='$cod'";
$result=mysql_query($sql) or die (mysql_error());

echo "<script>window.location ='m_mrn.php?SES=$SES&anio=$anio&modo=familia&cod=$cod'</script>";
}
elseif($action==ACT_FAM)
{
//1.- Generamos un array
for($i=0;$i<count($_POST['campos']);$i++) 
{
$sql="INSERT IGNORE INTO pit_bd_user_iniciativa VALUES('008','".$_POST['campos'][$i]."','1','1','5','$cod')";
$result=mysql_query($sql) or die (mysql_error());
}
//2.- Procedo a redireccionar
echo "<script>window.location ='m_mrn.php?SES=$SES&anio=$anio&modo=familia&cod=$cod'</script>";

}
elseif($action==UPDATE_INFO)
{
$codigo=$_GET['cod'];

//1.- Actualizamos e ingresamos la info de actividades actuales
foreach($actividad_presente as $cod=>$a1)
{
$sql="UPDATE mrn_actividad_actual SET cod_actividad='$a1' WHERE cod='$cod'";
$result=mysql_query($sql) or die (mysql_error());
}
foreach($nivel_presente as $cod=>$b1)
{
$sql="UPDATE mrn_actividad_actual SET nivel='$b1' WHERE cod='$cod'";
$result=mysql_query($sql) or die (mysql_error());
}
foreach($familias_presente as $cod=>$c1)
{
$sql="UPDATE mrn_actividad_actual SET n_familia='$c1' WHERE cod='$cod'";
$result=mysql_query($sql) or die (mysql_error());
}

for ($i = 0; $i <=5; $i++) 
{
	if ($_POST['actividad_presentes'][$i]<>NULL)
	{
		$sql="INSERT INTO mrn_actividad_actual VALUES('','".$_POST['actividad_presentes'][$i]."','".$_POST['nivel_presentes'][$i]."','".$_POST['familias_presentes'][$i]."','$codigo')";
		$result=mysql_query($sql) or die (mysql_error());
	}
}

//2.- Actualizamos la informacion de actividades futuras
foreach($actividad_futura as $cod=>$a2)
{
$sql="UPDATE mrn_actividad_futuro SET cod_actividad='$a2' WHERE cod='$cod'";
$result=mysql_query($sql) or die (mysql_error());
}
foreach($nivel_futuro as $cod=>$b2)
{
$sql="UPDATE mrn_actividad_futuro SET nivel='$b2' WHERE cod='$cod'";
$result=mysql_query($sql) or die (mysql_error());
}
foreach($familia_futuro as $cod=>$c2)
{
$sql="UPDATE mrn_actividad_futuro SET n_familia='$c2' WHERE cod='$cod'";
$result=mysql_query($sql) or die (mysql_error());
}

for ($i = 0; $i <=5; $i++) 
{
	if ($_POST['actividad_futuras'][$i]<>NULL)
	{
		$sql="INSERT INTO mrn_actividad_futuro VALUES('','".$_POST['actividad_futuras'][$i]."','".$_POST['nivel_futuros'][$i]."','".$_POST['familia_futuros'][$i]."','$codigo')";
		$result=mysql_query($sql) or die (mysql_error());
	}
}

//3.- Guardamos la info del area
$sql="SELECT * FROM mrn_area WHERE cod_mrn='$codigo'";
$result=mysql_query($sql) or die (mysql_error());
$total=mysql_num_rows($result);

if ($total==0)
{
$sql="INSERT INTO mrn_area VALUES('','".$_POST['area_a']."','".$_POST['area_b']."','".$_POST['area_c']."','".$_POST['area_d']."','".$_POST['area_e']."','".$_POST['area_f']."','$codigo')";
$result=mysql_query($sql) or die (mysql_error());
}
elseif($total<>0)
{
$sql="UPDATE mrn_area SET a1='".$_POST['area_a']."',a2='".$_POST['area_b']."',a3='".$_POST['area_c']."',a4='".$_POST['area_d']."',a5='".$_POST['area_e']."',a6='".$_POST['area_f']."' WHERE cod_mrn='$codigo'";
$result=mysql_query($sql) or die (mysql_error());
}


//4.- Guardo la info de concurso
foreach($mes as $cod=>$a3)
{
	$sql="UPDATE mrn_concurso SET mes='$a3' WHERE cod='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
}
foreach($anio as $cod=>$b3)
{
	$sql="UPDATE mrn_concurso SET anio='$b3' WHERE cod='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
}
foreach($linea as $cod=>$c3)
{
	$sql="UPDATE mrn_concurso SET linea=upper('$c3') WHERE cod='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
}
for($i=0; $i<=3; $i++)
{
	if($_POST['mess'][$i]<>NULL)
	{
		$sql="INSERT INTO mrn_concurso VALUES('','".$_POST['mess'][$i]."','".$_POST['anios'][$i]."',UPPER('".$_POST['lineas'][$i]."'),'$codigo')";
		$result=mysql_query($sql) or die (mysql_error());
	}
}

//5.- redirecciono
echo "<script>window.location ='m_mrn.php?SES=$SES&anio=$anio&modo=inform&cod=$codigo'</script>";
}
elseif($action==DELETE)
{
$sql="DELETE FROM pit_bd_ficha_mrn WHERE cod_mrn='$id'";
$result=mysql_query($sql) or die (mysql_error());

echo "<script>window.location ='mrn.php?SES=$SES&anio=$anio&modo=delete'</script>";
}

?>