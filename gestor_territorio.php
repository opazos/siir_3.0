<?
require("funciones/sesion.php");
include("funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);


if ($action==ADD)
{
//1.- Registramos la organizacion territorial
$sql="INSERT INTO org_ficha_taz VALUES('".$_POST['tipo_doc']."','".$_POST['n_documento']."',UPPER('".$_POST['organizacion']."'),'','".$_POST['f_registro']."','".$_POST['tipo']."','004','004','','','','','".$_POST['select1']."','".$_POST['select2']."','".$_POST['select3']."','".$_POST['select4']."',UPPER('".$_POST['direccion']."'),'".$row['cod_dependencia']."','','','0')";
$result=mysql_query($sql) or die (mysql_error());

//2.- Registro la junta directiva
for ($i = 0; $i <= 10; $i++) 
{
if($_POST['dni'][$i]<>NULL)
{
$sql="INSERT INTO org_ficha_directiva_taz VALUES('008','".$_POST['dni'][$i]."',UPPER('".$_POST['nombre'][$i]."'),UPPER('".$_POST['apellidop'][$i]."'),UPPER('".$_POST['apellidom'][$i]."'),'".$_POST['fecha'][$i]."','".$_POST['sexo'][$i]."','".$_POST['cargo'][$i]."','','".$_POST['vigencia'][$i]."','1','1','".$_POST['tipo_doc']."','".$_POST['n_documento']."')";
$result=mysql_query($sql) or die (mysql_error());
}
}

//3.- Obtenemos los cargos y actualizamos
$sql="SELECT org_ficha_directiva_taz.n_documento
FROM org_ficha_directiva_taz
WHERE org_ficha_directiva_taz.cod_cargo_directivo=1 AND
org_ficha_directiva_taz.cod_tipo_doc_taz='".$_POST['tipo_doc']."' AND 
org_ficha_directiva_taz.n_documento_taz='".$_POST['n_documento']."'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$sql="UPDATE org_ficha_taz SET presidente='".$r1['n_documento']."' WHERE cod_tipo_doc='".$_POST['tipo_doc']."' AND n_documento='".$_POST['n_documento']."'";
$result=mysql_query($sql) or die (mysql_error());

$sql="SELECT org_ficha_directiva_taz.n_documento
FROM org_ficha_directiva_taz
WHERE org_ficha_directiva_taz.cod_cargo_directivo=3 AND
org_ficha_directiva_taz.cod_tipo_doc_taz='".$_POST['tipo_doc']."' AND 
org_ficha_directiva_taz.n_documento_taz='".$_POST['n_documento']."'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

$sql="UPDATE org_ficha_taz SET tesorero='".$r2['n_documento']."' WHERE cod_tipo_doc='".$_POST['tipo_doc']."' AND n_documento='".$_POST['n_documento']."'";
$result=mysql_query($sql) or die (mysql_error());

//4.- Procedo a ingresar la info a la tabla organizaciones
$sql="INSERT INTO org_ficha_organizacion VALUES('".$_POST['tipo_doc']."','".$_POST['n_documento']."',UPPER('".$_POST['organizacion']."'),'".$_POST['tipo']."','','".$_POST['f_registro']."','".$_POST['select1']."','".$_POST['select2']."','".$_POST['select3']."','".$_POST['select4']."',UPPER('".$_POST['direccion']."'),'','','".$row['cod_dependencia']."','0','".$_POST['tipo_doc']."','".$_POST['n_documento']."')";
$result=mysql_query($sql) or die (mysql_error());

//5.- Registro familias
for ($i = 0; $i <= 10; $i++) 
{
if($_POST['dni'][$i]<>NULL)
{
$sql="INSERT INTO org_ficha_usuario VALUES('008','".$_POST['dni'][$i]."',UPPER('".$_POST['nombre'][$i]."'),UPPER('".$_POST['apellidop'][$i]."'),UPPER('".$_POST['apellidom'][$i]."'),'".$_POST['fecha'][$i]."','".$_POST['sexo'][$i]."','','','1','1','0','','','','1','".$_POST['tipo_doc']."','".$_POST['n_documento']."')";
$result=mysql_query($sql) or die (mysql_error());
}
}

//6.- Registro la junta directiva
for ($i = 0; $i <= 10; $i++) 
{
if($_POST['dni'][$i]<>NULL)
{
$sql="INSERT INTO org_ficha_directivo VALUES('','008','".$_POST['dni'][$i]."','".$_POST['cargo'][$i]."','','".$_POST['vigencia'][$i]."','1','1','".$_POST['tipo_doc']."','".$_POST['n_documento']."')";
$result=mysql_query($sql) or die(mysql_error());
}
}

//7.- Obtengo cargos y actualizo
$sql="SELECT org_ficha_directivo.n_documento
FROM org_ficha_directivo
WHERE org_ficha_directivo.cod_cargo=1 AND
org_ficha_directivo.cod_tipo_doc_org='".$_POST['tipo_doc']."' AND
org_ficha_directivo.n_documento_org='".$_POST['n_documento']."'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);

$sql="UPDATE org_ficha_organizacion SET presidente='".$r3['n_documento']."' WHERE cod_tipo_doc='".$_POST['tipo_doc']."' AND n_documento='".$_POST['n_documento']."'";
$result=mysql_query($sql) or die (mysql_error());

$sql="SELECT org_ficha_directivo.n_documento
FROM org_ficha_directivo
WHERE org_ficha_directivo.cod_cargo=3 AND
org_ficha_directivo.cod_tipo_doc_org='".$_POST['tipo_doc']."' AND
org_ficha_directivo.n_documento_org='".$_POST['n_documento']."'";
$result=mysql_query($sql) or die (mysql_error());
$r4=mysql_fetch_array($result);

$sql="UPDATE org_ficha_organizacion SET tesorero='".$r4['n_documento']."' WHERE cod_tipo_doc='".$_POST['tipo_doc']."' AND n_documento='".$_POST['n_documento']."'";
$result=mysql_query($sql) or die (mysql_error());

//Redirecciono
echo "<script>window.location ='territorio.php?SES=$SES&anio=$anio&modo=edit'</script>";
}
/*****************************************************************************************************************************************************************************************************/
elseif($action==DELETE)
{
//1.- Dividimos la cadena
$organizacion=$_GET['id'];
$dato=explode(",",$organizacion);
$tipo_documento=$dato[0];
$n_documento=$dato[1];

//2.- Eliminamos
$sql="DELETE FROM org_ficha_taz WHERE cod_tipo_doc='$tipo_documento' AND n_documento='$n_documento'";
$result=mysql_query($sql) or die (mysql_error());

//Redirecciono
echo "<script>window.location ='territorio.php?SES=$SES&anio=$anio&modo=edit'</script>";
}
/*****************************************************************************************************************************************************************************************************/
elseif($action==UPDATE)
{
$organizacion=$_POST['codigo'];
$dato=explode(",",$organizacion);
$tipo_documento=$dato[0];
$n_documento=$dato[1];


//1.- Actualizamos la info
$sql="UPDATE org_ficha_taz SET cod_tipo_doc='".$_POST['tipo_doc']."',n_documento='".$_POST['n_documento']."',nombre=UPPER('".$_POST['organizacion']."'),f_constitucion='".$_POST['f_registro']."',cod_tipo_org='".$_POST['tipo']."',cod_dep='".$_POST['select1']."',cod_prov='".$_POST['select2']."',cod_dist='".$_POST['select3']."',cod_cp='".$_POST['select4']."',sector=UPPER('".$_POST['direccion']."') WHERE cod_tipo_doc='$tipo_documento' AND n_documento='$n_documento'";
$result=mysql_query($sql) or die (mysql_error());

//2.- Actualizo la info de la organizacion hija
$sql="UPDATE org_ficha_organizacion SET cod_tipo_doc='".$_POST['tipo_doc']."',n_documento='".$_POST['n_documento']."',nombre=UPPER('".$_POST['organizacion']."'),cod_tipo_org='".$_POST['tipo']."',f_formalizacion='".$_POST['f_registro']."',cod_dep='".$_POST['select1']."',cod_prov='".$_POST['select2']."',cod_dist='".$_POST['select3']."',sector=UPPER('".$_POST['direccion']."') WHERE cod_tipo_doc='$tipo_documento' AND n_documento='$n_documento'";
$result=mysql_query($sql) or die (mysql_error());


//3.- Actualizo la junta directiva del territorio
foreach($dni as $cad=>$a)
{
	$sql="UPDATE org_ficha_directiva_taz SET n_documento='".$_POST['dni'][$cad]."',nombre=UPPER('".$_POST['nombre'][$cad]."'),paterno=UPPER('".$_POST['apellidop'][$cad]."'),materno=UPPER('".$_POST['apellidom'][$cad]."'),f_nacimiento='".$_POST['fecha'][$cad]."',sexo='".$_POST['sexo'][$cad]."',cod_cargo_directivo='".$_POST['cargo'][$cad]."',f_termino='".$_POST['vigencia'][$cad]."' WHERE n_documento='$cad'";
	$result=mysql_query($sql) or die (mysql_error());

}

//5.- Añado los nuevos directivos al territorio
for ($i = 0; $i <= 5; $i++) 
{
if($_POST['dnia'][$i]<>NULL)
{
$sql="INSERT INTO org_ficha_directiva_taz VALUES('008','".$_POST['dnia'][$i]."',UPPER('".$_POST['nombrea'][$i]."'),UPPER('".$_POST['apellidopa'][$i]."'),UPPER('".$_POST['apellidoma'][$i]."'),'".$_POST['fechaa'][$i]."','".$_POST['sexoa'][$i]."','".$_POST['cargoa'][$i]."','','".$_POST['vigenciaa'][$i]."','1','1','$tipo_documento','$n_documento')";
$result=mysql_query($sql) or die (mysql_error());
}
}
//6.- Añado los nuevos directivos a la organizacion como usuarios
for($i=0; $i <=5; $i++)
{
if ($_POST['dnia'][$i]<>NULL)
{
$sql="INSERT IGNORE INTO org_ficha_usuario VALUES('008','".$_POST['dnia'][$i]."',UPPER('".$_POST['nombrea'][$i]."'),UPPER('".$_POST['apellidopa'][$i]."'),UPPER('".$_POST['apellidoma'][$i]."'),'".$_POST['fechaa'][$i]."','".$_POST['sexoa'][$i]."','','','1','1','0','','','1','$tipo_documento','$n_documento')";
$result=mysql_query($sql) or die (mysql_error());
}
}
//7.- Añado la junta directiva a la organizacion
for($i=0; $i <=5; $i++)
{
if ($_POST['dnia'][$i]<>NULL)
{
$sql="INSERT INTO org_ficha_directivo VALUES('','008','".$_POST['dnia'][$i]."','".$_POST['cargoa'][$i]."','','".$_POST['fechaa'][$i]."','1','1','$tipo_documento','$n_documento')";
$result=mysql_query($sql) or die (mysql_error());
}
}

//8.- Obtenemos los cargos del territorio y actualizamos
$sql="SELECT org_ficha_directiva_taz.n_documento
FROM org_ficha_directiva_taz
WHERE org_ficha_directiva_taz.cod_cargo_directivo=1 AND
org_ficha_directiva_taz.cod_tipo_doc_taz='$tipo_documento' AND 
org_ficha_directiva_taz.n_documento_taz='$n_documento'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$sql="UPDATE org_ficha_taz SET presidente='".$r1['n_documento']."' WHERE cod_tipo_doc='$tipo_documento' AND n_documento='$n_documento'";
$result=mysql_query($sql) or die (mysql_error());

$sql="SELECT org_ficha_directiva_taz.n_documento
FROM org_ficha_directiva_taz
WHERE org_ficha_directiva_taz.cod_cargo_directivo=3 AND
org_ficha_directiva_taz.cod_tipo_doc_taz='$tipo_documento' AND 
org_ficha_directiva_taz.n_documento_taz='$n_documento'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

$sql="UPDATE org_ficha_taz SET tesorero='".$r2['n_documento']."' WHERE cod_tipo_doc='$tipo_documento' AND n_documento='$n_documento'";
$result=mysql_query($sql) or die (mysql_error());

//9.- Obtenemos los cargos y actualizamos la organizacion
$sql="SELECT org_ficha_directivo.n_documento
FROM org_ficha_directivo
WHERE org_ficha_directivo.cod_cargo=1 AND
org_ficha_directivo.cod_tipo_doc_org='$tipo_documento' AND
org_ficha_directivo.n_documento_org='$n_documento'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);

$sql="UPDATE org_ficha_organizacion SET presidente='".$r3['n_documento']."' WHERE cod_tipo_doc='$tipo_documento' AND n_documento='$n_documento'";
$result=mysql_query($sql) or die (mysql_error());

$sql="SELECT org_ficha_directivo.n_documento
FROM org_ficha_directivo
WHERE org_ficha_directivo.cod_cargo=3 AND
org_ficha_directivo.cod_tipo_doc_org='$tipo_documento' AND
org_ficha_directivo.n_documento_org='$n_documento'";
$result=mysql_query($sql) or die (mysql_error());
$r4=mysql_fetch_array($result);

$sql="UPDATE org_ficha_organizacion SET tesorero='".$r4['n_documento']."' WHERE cod_tipo_doc='$tipo_documento' AND n_documento='$n_documento'";
$result=mysql_query($sql) or die (mysql_error());


//10.- Redirecciono
echo "<script>window.location ='territorio.php?SES=$SES&anio=$anio&modo=edit'</script>";
}


?>