<?
session_start();

$_SESSION["usuario"]=$_POST['usuario'];

/**********************************************************************/

extract($_POST);

extract($_GET);



include("funciones/funciones.php");
conectarte();

if ($_POST['periodo']==NULL)
{
echo "<script>window.location ='index.php?error=not_period'</script>"; 
}
//PASO 1: BUSCAMOS EL USUARIO
$sql="SELECT * FROM sys_bd_personal WHERE usuario='".$_POST['usuario']."' AND clave='".md5($_POST['clave'])."'";
$result=mysql_query($sql) or die (mysql_error());
$total=mysql_num_rows($result);

//creamos una condicion si es que existe o si es que no
if ($total==0)
{
echo "<script>window.location ='index.php?error=loginfailed'</script>"; 
}
else
{
//SI TODO VA BIEN PASAMOS AL SIGUIENTE PASO

//PASO 2: CREAMOS VARIABLE
$fila=mysql_fetch_array($result);
$SES=md5($fila['n_documento']);
$anio=$_POST['periodo'];
$codp=$fila['n_documento'];

//PASO 3: ACTUALIZAMOS LA SESION
$sql="UPDATE sys_bd_personal SET sesion='".$_POST['fecha']."' WHERE cod_tipo_doc='008' AND n_documento='$codp'";
$result=mysql_query($sql) or die (mysql_error());


//PASO 4: REDIRIGIMOS A LA PAGINA PRINCIPAL
echo "<script>window.location ='principal.php?SES=$SES&anio=$anio'</script>"; 

}
?>