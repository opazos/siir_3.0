<?
require("funciones/sesion.php");
include("funciones/funciones.php");
conectarte();


$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);


if ($action==ADD)
{
$organizacion=$_POST['territorio'];
$dato=explode(",",$organizacion);
$tipo_documento=$dato[0];
$n_documento=$dato[1];

//1.- Ingresamos la info de la organizacion
$sql="INSERT INTO org_ficha_organizacion VALUES('".$_POST['tipo_doc']."','".$_POST['n_documento']."',UPPER('".$_POST['organizacion']."'),'".$_POST['tipo']."','','".$_POST['f_registro']."','".$_POST['select1']."','".$_POST['select2']."','".$_POST['select3']."','".$_POST['select4']."',UPPER('".$_POST['direccion']."'),'','','".$row['cod_dependencia']."','0','$tipo_documento','$n_documento')";
$result=mysql_query($sql) or die (mysql_error());

$tipo=$_POST['tipo_doc'];
$numero=$_POST['n_documento'];

//2.- Redirecciono
echo "<script>window.location ='n_organizacion.php?SES=$SES&anio=$anio&tipo=$tipo&numero=$numero&modo=familia'</script>";
}

elseif($action==ADD_FAM)
{
//1.- Verifico que no falten datos
if ($_POST['titular']==NULL or $_POST['socio']==NULL)
{
echo "<script>window.location ='n_organizacion.php?SES=$SES&anio=$anio&tipo=$tipo&numero=$numero&modo=familia&error=vacio'</script>";
}
elseif ($_POST['titular']==1)
{
$sql="INSERT INTO org_ficha_usuario  VALUES('008','".$_POST['dni']."',UPPER('".$_POST['nombre']."'),UPPER('".$_POST['paterno']."'),UPPER('".$_POST['materno']."'),'".$_POST['f_nacimiento']."','".$_POST['sexo']."','".$_POST['ubigeo']."',UPPER('".$_POST['direccion']."'),'".$_POST['titular']."','".$_POST['socio']."','".$_POST['n_hijos']."','','','','1','$tipo','$numero')";
$result=mysql_query($sql) or die (mysql_error());
}
elseif($_POST['titular']==0 and $_POST['pareja']==NULL)
{
$sql="INSERT INTO org_ficha_usuario  VALUES('008','".$_POST['dni']."',UPPER('".$_POST['nombre']."'),UPPER('".$_POST['paterno']."'),UPPER('".$_POST['materno']."'),'".$_POST['f_nacimiento']."','".$_POST['sexo']."','".$_POST['ubigeo']."',UPPER('".$_POST['direccion']."'),'".$_POST['titular']."','".$_POST['socio']."','0','','','','1','$tipo','$numero')";
$result=mysql_query($sql) or die (mysql_error());
}
elseif($_POST['titular']==0 and $_POST['pareja']<>NULL)
{
$sql="INSERT INTO org_ficha_usuario  VALUES('008','".$_POST['dni']."',UPPER('".$_POST['nombre']."'),UPPER('".$_POST['paterno']."'),UPPER('".$_POST['materno']."'),'".$_POST['f_nacimiento']."','".$_POST['sexo']."','".$_POST['ubigeo']."',UPPER('".$_POST['direccion']."'),'".$_POST['titular']."','".$_POST['socio']."','0','','008','".$_POST['pareja']."','1','$tipo','$numero')";
$result=mysql_query($sql) or die (mysql_error());

$sql="UPDATE org_ficha_usuario SET cod_tipo_doc_conyuge='008',n_documento_conyuge='".$_POST['dni']."' WHERE n_documento='".$_POST['pareja']."'";
$result=mysql_query($sql) or die (mysql_error());
}

echo "<script>window.location ='n_organizacion.php?SES=$SES&anio=$anio&tipo=$tipo&numero=$numero&modo=familia'</script>";
}

elseif($action==ADD_FAM_SAVE)
{
//1.- Verifico que no falten datos
if ($_POST['n_titular']==NULL or $_POST['n_socio']==NULL)
{
echo "<script>window.location ='m_organizacion.php?SES=$SES&anio=$anio&tipo=$tipo&numero=$numero&modo=familia&error=vacio'</script>";
}
elseif ($_POST['n_titular']==1)
{
$sql="INSERT INTO org_ficha_usuario  VALUES('008','".$_POST['n_dni']."',UPPER('".$_POST['n_nombre']."'),UPPER('".$_POST['n_paterno']."'),UPPER('".$_POST['n_materno']."'),'".$_POST['n_f_nacimiento']."','".$_POST['n_sexo']."','".$_POST['n_ubigeo']."',UPPER('".$_POST['n_direccion']."'),'".$_POST['n_titular']."','".$_POST['n_socio']."','".$_POST['n_hijos']."','','','','1','$tipo','$numero')";
$result=mysql_query($sql) or die (mysql_error());
}
elseif($_POST['n_titular']==0 and $_POST['n_pareja']==NULL)
{
$sql="INSERT INTO org_ficha_usuario  VALUES('008','".$_POST['n_dni']."',UPPER('".$_POST['n_nombre']."'),UPPER('".$_POST['n_paterno']."'),UPPER('".$_POST['n_materno']."'),'".$_POST['n_f_nacimiento']."','".$_POST['n_sexo']."','".$_POST['n_ubigeo']."',UPPER('".$_POST['n_direccion']."'),'".$_POST['n_titular']."','".$_POST['n_socio']."','0','','','','1','$tipo','$numero')";
$result=mysql_query($sql) or die (mysql_error());
}
elseif($_POST['n_titular']==0 and $_POST['n_pareja']<>NULL)
{
$sql="INSERT INTO org_ficha_usuario  VALUES('008','".$_POST['n_dni']."',UPPER('".$_POST['n_nombre']."'),UPPER('".$_POST['n_paterno']."'),UPPER('".$_POST['n_materno']."'),'".$_POST['n_f_nacimiento']."','".$_POST['n_sexo']."','".$_POST['n_ubigeo']."',UPPER('".$_POST['n_direccion']."'),'".$_POST['n_titular']."','".$_POST['n_socio']."','0','','008','".$_POST['n_pareja']."','1','$tipo','$numero')";
$result=mysql_query($sql) or die (mysql_error());

$sql="UPDATE org_ficha_usuario SET cod_tipo_doc_conyuge='008',n_documento_conyuge='".$_POST['n_dni']."' WHERE n_documento='".$_POST['n_pareja']."'";
$result=mysql_query($sql) or die (mysql_error());
}
echo "<script>window.location ='m_organizacion.php?SES=$SES&anio=$anio&tipo=$tipo&numero=$numero&modo=familia'</script>";
}

elseif($action==ADD_DIR)
{
//1.- Ingreso los directivos
for ($i = 0; $i <= 10; $i++) 
{
if ($_POST['dir'][$i]<>NULL)
{
$sql="INSERT INTO org_ficha_directivo VALUES('','008','".$_POST['dir'][$i]."','".$_POST['cargo'][$i]."','','".$_POST['vigencia'][$i]."','1','1','$tipo','$numero')";
$result=mysql_query($sql) or die (mysql_error());
}
}
//2.- Buscamos cargos y actualizamos
$sql="SELECT org_ficha_directivo.n_documento
FROM org_ficha_directivo
WHERE org_ficha_directivo.cod_cargo=1 AND
org_ficha_directivo.cod_tipo_doc_org='$tipo' AND
org_ficha_directivo.n_documento_org='$numero'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$sql="UPDATE org_ficha_organizacion SET presidente='".$r1['n_documento']."' WHERE cod_tipo_doc='$tipo' AND n_documento='$numero'";
$result=mysql_query($sql) or die (mysql_error());

$sql="SELECT org_ficha_directivo.n_documento
FROM org_ficha_directivo
WHERE org_ficha_directivo.cod_cargo=3 AND
org_ficha_directivo.cod_tipo_doc_org='$tipo' AND
org_ficha_directivo.n_documento_org='$numero'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

$sql="UPDATE org_ficha_organizacion SET tesorero='".$r2['n_documento']."' WHERE cod_tipo_doc='$tipo' AND n_documento='$numero'";
$result=mysql_query($sql) or die (mysql_error());

$sql="SELECT org_ficha_directivo.n_documento
FROM org_ficha_directivo
WHERE org_ficha_directivo.cod_cargo=7 AND
org_ficha_directivo.cod_tipo_doc_org='$tipo' AND
org_ficha_directivo.n_documento_org='$numero'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);

$sql="UPDATE org_ficha_organizacion SET presidente='".$r3['n_documento']."' WHERE cod_tipo_doc='$tipo' AND n_documento='$numero'";
$result=mysql_query($sql) or die (mysql_error());


echo "<script>window.location ='organizacion.php?SES=$SES&anio=$anio&modo=edit'</script>";
}
elseif($action==UPDATE)
{
$organizacion=$_POST['codigo'];
$dato=explode(",",$organizacion);
$tipo_documento=$dato[0];
$n_documento=$dato[1];

$territorio=$_POST['territorio'];
$dat=explode(",",$territorio);
$tipo_doc=$dat[0];
$n_doc=$dat[1];

$sql="UPDATE org_ficha_organizacion SET cod_tipo_doc='".$_POST['tipo_doc']."',n_documento='".$_POST['n_documento']."',nombre=UPPER('".$_POST['organizacion']."'),cod_tipo_org='".$_POST['tipo']."',f_formalizacion='".$_POST['f_registro']."',cod_dep='".$_POST['select1']."',cod_prov='".$_POST['select2']."',cod_dist='".$_POST['select3']."',cod_cp='".$_POST['select4']."',sector=UPPER('".$_POST['direccion']."'),cod_tipo_doc_taz='$tipo_doc',n_documento_taz='$n_doc' WHERE cod_tipo_doc='$tipo_documento' AND n_documento='$n_documento'";
$result=mysql_query($sql) or die (mysql_error());

//2.- Redirecciono
echo "<script>window.location ='m_organizacion.php?SES=$SES&anio=$anio&tipo=$tipo_documento&numero=$n_documento&modo=familia'</script>";
}

elseif($action==SAVE_FAM)
{
//1.- Realizo un UPDATE mediante un foreach de la información de las familias, para actualizar
foreach($dni as $cod=>$a)
{	
	if ($_POST['pareja'][$cod]<>NULL)
	{
		$dni=008;
	}
	
	$sql="UPDATE org_ficha_usuario SET n_documento='".$_POST['dni'][$cod]."',nombre=UPPER('".$_POST['nombres'][$cod]."'),paterno=UPPER('".$_POST['paterno'][$cod]."'),materno=UPPER('".$_POST['materno'][$cod]."'),f_nacimiento='".$_POST['fecha'][$cod]."',sexo='".$_POST['sexo'][$cod]."',ubigeo='".$_POST['ubigeo'][$cod]."',direccion=UPPER('".$_POST['address'][$cod]."'),titular='".$_POST['titular'][$cod]."',socio='".$_POST['socio'][$cod]."',n_hijo='".$_POST['hijo'][$cod]."',cod_tipo_doc_conyuge='$dni',n_documento_conyuge='".$_POST['pareja'][$cod]."' WHERE n_documento='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
}

//2.- Redirecciono
echo "<script>window.location ='m_organizacion.php?SES=$SES&anio=$anio&tipo=$tipo&numero=$numero&modo=familia'</script>";
}

elseif($action==SAVE_DIR)
{
//1.- Actualizamos la junta directiva con un array
foreach($dir as $cod=>$a)
{
	$sql="UPDATE org_ficha_directivo SET n_documento='".$_POST['dir'][$cod]."',cod_cargo='".$_POST['cargo'][$cod]."',f_termino='".$_POST['vigencia'][$cod]."' WHERE cod_directivo='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
}
//2.- Agregamos los nuevos directivos
for ($i = 0; $i <= 10; $i++) 
{
if ($_POST['dira'][$i]<>NULL)
{
	$sql="INSERT INTO org_ficha_directivo VALUES('','008','".$_POST['dira'][$i]."','".$_POST['cargoa'][$i]."','','".$_POST['vigenciaa'][$i]."','1','1','$tipo','$numero')";
	$result=mysql_query($sql) or die (mysql_error());
}
}
//3.- Buscamos al presidente
$sql="SELECT org_ficha_directivo.n_documento
FROM org_ficha_directivo
WHERE org_ficha_directivo.cod_tipo_doc_org='$tipo' AND
org_ficha_directivo.n_documento_org='$numero' AND
org_ficha_directivo.cod_cargo=1";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

//4.- Buscamos al tesorero
$sql="SELECT org_ficha_directivo.n_documento
FROM org_ficha_directivo
WHERE org_ficha_directivo.cod_tipo_doc_org='$tipo' AND
org_ficha_directivo.n_documento_org='$numero' AND
org_ficha_directivo.cod_cargo=3";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

//5.- Buscamos al Gerente
$sql="SELECT org_ficha_directivo.n_documento
FROM org_ficha_directivo
WHERE org_ficha_directivo.cod_tipo_doc_org='$tipo' AND
org_ficha_directivo.n_documento_org='$numero' AND
org_ficha_directivo.cod_cargo=7";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);

//6.- Actualizamos Junta Directiva
if($r3['n_documento']==NULL)
{
	$presidente=$r1['n_documento'];
}
else
{
	$presidente=$r3['n_documento'];
}

$sql="UPDATE org_ficha_organizacion SET presidente='$presidente',tesorero='".$r2['n_documento']."' WHERE cod_tipo_doc='$tipo' AND n_documento='$numero'";
$result=mysql_query($sql) or die (mysql_error());

echo "<script>window.location ='organizacion.php?SES=$SES&anio=$anio&modo=edit'</script>";

}

elseif($action==DELETE_DIR)
{
//1.- Eliminamos el directivo
$sql="DELETE FROM org_ficha_directivo WHERE cod_directivo='$id'";
$result=mysql_query($sql) or die (mysql_error());

echo "<script>window.location ='m_organizacion.php?SES=$SES&anio=$anio&tipo=$tipo&numero=$numero&modo=directiva'</script>";

}

elseif($action==DELETE)
{
	$sql="DELETE FROM org_ficha_organizacion WHERE cod_tipo_doc='$tipo' AND n_documento='$numero'";
	$result=mysql_query($sql) or die (mysql_error());
	
	echo "<script>window.location ='organizacion.php?SES=$SES&anio=$anio&modo=edit&alert=success_delete'</script>";
}
elseif($action==DELETE_FAM)
{
	$sql="DELETE FROM org_ficha_usuario WHERE n_documento='$dni' AND cod_tipo_doc_org='$tipo' AND n_documento_org='$numero'";
	$result=mysql_query($sql) or die (mysql_error());
	
	echo "<script>window.location ='m_organizacion.php?SES=$SES&anio=$anio&tipo=$tipo&numero=$numero&modo=familia&alert=success_delete'</script>";	
}


?>