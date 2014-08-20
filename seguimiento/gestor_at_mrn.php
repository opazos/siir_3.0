<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ADD)
{

if ($_POST['tipo_oferente']==NULL or $_POST['pgrn']==NULL or $_POST['actividad']==NULL or $_POST['asignacion']==NULL or $_POST['calificacion']==NULL or $_POST['estado']==NULL)
{
	echo "<script>window.location ='n_at_mrn.php?SES=$SES&anio=$anio&error=vacio'</script>";
}
else
{
	//1.-Guardo la informacion de la persona
$sql="INSERT IGNORE INTO ficha_ag_oferente VALUES('008','".$_POST['dni']."',UPPER('".$_POST['nombre']."'),UPPER('".$_POST['paterno']."'),UPPER('".$_POST['materno']."'),'".$_POST['f_nacimiento']."','".$_POST['sexo']."','','','','".$_POST['ubigeo']."',UPPER('".$_POST['direccion']."'),'".$_POST['tipo_oferente']."',UPPER('".$_POST['especialidad']."'))";
$result=mysql_query($sql) or die (mysql_error());

	//2.- Generamos la informacion del asistente tecnico
$sql="INSERT INTO ficha_sat VALUES('','008','".$_POST['dni']."','5','".$_POST['pgrn']."','".$_POST['actividad']."','".$_POST['f_inicio']."','".$_POST['f_termino']."','".$_POST['mujeres']."','".$_POST['varones']."','".$_POST['aporte_pdss']."','".$_POST['aporte_org']."','".$_POST['aporte_otro']."',UPPER('".$_POST['resultado']."'),'".$_POST['asignacion']."','".$_POST['calificacion']."','".$_POST['estado']."')";
$result=mysql_query($sql) or die (mysql_error());

	//3.- Redireccionamos
echo "<script>window.location ='at_mrn.php?SES=$SES&anio=$anio&modo=edit'</script>";	
}
}
elseif($action==DELETE)
{
$sql="DELETE FROM ficha_sat WHERE cod_sat='$id'";
$result=mysql_query($sql) or die (mysql_error());

echo "<script>window.location ='at_mrn.php?SES=$SES&anio=$anio&modo=edit'</script>";	
}
elseif($action==UPDATE)
{
//1.- Guardamos la info de Usuario
$sql="UPDATE ficha_ag_oferente SET nombre=UPPER('".$_POST['nombre']."'),paterno=UPPER('".$_POST['paterno']."'),materno=UPPER('".$_POST['materno']."'),f_nacimiento='".$_POST['f_nacimiento']."',sexo='".$_POST['sexo']."',ubigeo='".$_POST['ubigeo']."',direccion=UPPER('".$_POST['direccion']."'),cod_tipo_oferente='".$_POST['tipo_oferente']."',especialidad=UPPER('".$_POST['especialidad']."') WHERE n_documento='".$_POST['dni']."'";
$result=mysql_query($sql) or die (mysql_error());

//2.- Guardo la informacion de asistencia tecnica
$sql="UPDATE ficha_sat SET tema='".$_POST['actividad']."',f_inicio='".$_POST['f_inicio']."',f_termino='".$_POST['f_termino']."',n_mujeres='".$_POST['mujeres']."',n_varones='".$_POST['varones']."',aporte_pdss='".$_POST['aporte_pdss']."',aporte_org='".$_POST['aporte_org']."',aporte_otro='".$_POST['aporte_otro']."',resultado=UPPER('".$_POST['resultado']."'),cod_tipo_designacion='".$_POST['asignacion']."',cod_calificacion='".$_POST['calificacion']."',cod_estado_iniciativa='".$_POST['estado']."' WHERE cod_sat='".$_POST['codigo']."'";
$result=mysql_query($sql) or die (mysql_error());

echo "<script>window.location ='at_mrn.php?SES=$SES&anio=$anio&modo=edit'</script>";
}

?>