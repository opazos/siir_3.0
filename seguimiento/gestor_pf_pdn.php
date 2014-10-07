<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ADD)
{
	if($_POST['pdn']==NULL and $_POST['calificacion']==NULL)
	{
		echo "<script>window.location ='n_pf_pdn.php?SES=$SES&anio=$anio&error=vacio'</script>";	
	}
	else
	{
		//1.- Guardo la informacion de la feria
		$sql="INSERT INTO ficha_pf VALUES('','4','".$_POST['pdn']."',UPPER('".$_POST['evento']."'),UPPER('".$_POST['objetivo']."'),'".$_POST['f_inicio']."','".$_POST['f_termino']."',UPPER('".$_POST['departamento']."'),UPPER('".$_POST['provincia']."'),UPPER('".$_POST['distrito']."'),'".$_POST['aporte_pdss']."','".$_POST['aporte_org']."','".$_POST['aporte_otro']."','".$_POST['calificacion']."')";
		$result=mysql_query($sql) or die (mysql_error());
		
		//2.- obtengo el ultimo registro generado
		$sql="SELECT * FROM ficha_pf ORDER BY cod_feria DESC LIMIT 0,1";
		$result=mysql_query($sql) or die (mysql_error());
		$r1=mysql_fetch_array($result);
		
		$codigo=$r1['cod_feria'];
		
		//3.- Realizo el registro de las ventas
		for($i=0;$i<=10;$i++)
		{
			if ($_POST['producto'][$i]<>NULL)
			{
				$total=$_POST['cantidad'][$i]*$_POST['precio'][$i];
				$sql="INSERT INTO ficha_ventas_pf VALUES('',UPPER('".$_POST['producto'][$i]."'),UPPER('".$_POST['unidad'][$i]."'),'".$_POST['cantidad'][$i]."','".$_POST['precio'][$i]."','$total','$codigo')";
				$result=mysql_query($sql) or die (mysql_error());	
			}
		}
		//4.- Realizo el registro de los contactos comerciales
		for ($i=0;$i<=5;$i++)
		{
			if ($_POST['contacto'][$i]<>NULL)
			{
				$sql="INSERT INTO ficha_contacto_pf VALUES('',UPPER('".$_POST['contacto'][$i]."'),'".$_POST['mercado'][$i]."',UPPER('".$_POST['acuerdo'][$i]."'),UPPER('".$_POST['producto_final'][$i]."'),'$codigo')";
				$result=mysql_query($sql) or die (mysql_error());
			}
		}
		
		//5.- Redirecciono
		echo "<script>window.location ='n_pf_pdn.php?SES=$SES&anio=$anio&cod=$codigo&modo=asistente'</script>";
	}

}
elseif($action==ADD_ASISTENTE)
{
	for($i=0;$i<count($_POST['campos']);$i++) 
	{
		$sql="INSERT IGNORE INTO ficha_participante_pf VALUES('008','".$_POST['campos'][$i]."','$cod')";
		$result=mysql_query($sql) or die (mysql_error());
	}
	
	echo "<script>window.location ='n_pf_pdn.php?SES=$SES&anio=$anio&cod=$cod&modo=asistente'</script>";
}
elseif($action==DELETE)
{
$sql="DELETE FROM ficha_pf WHERE cod_feria='$id'";
$result=mysql_query($sql) or die (mysql_error());

echo "<script>window.location ='pf_pdn.php?SES=$SES&anio=$anio&cod=$cod&modo=edit'</script>";
}
elseif($action==UPDATE)
{
//1.- Procedo a actualizar la feria
$sql="UPDATE ficha_pf SET denominacion=UPPER('".$_POST['evento']."'),objetivo=UPPER('".$_POST['objetivo']."'),f_inicio='".$_POST['f_inicio']."',f_termino='".$_POST['f_termino']."',departamento=UPPER('".$_POST['departamento']."'),provincia=UPPER('".$_POST['provincia']."'),distrito=UPPER('".$_POST['distrito']."'),aporte_pdss='".$_POST['aporte_pdss']."',aporte_org='".$_POST['aporte_org']."',aporte_otro='".$_POST['aporte_otro']."',cod_calificacion='".$_POST['calificacion']."' WHERE cod_feria='".$_POST['codigo']."'";
$result=mysql_query($sql) or die (mysql_error());

$codigo=$_POST['codigo'];

//2.- Actualizo la info de ventas y contactos
foreach($productos as $cad=>$a1)
{
	$totales=$_POST['cantidads'][$cad]*$_POST['precios'][$cad];
	
	$sql="UPDATE ficha_ventas_pf SET producto=UPPER('".$_POST['productos'][$cad]."'),unidad=UPPER('".$_POST['unidads'][$cad]."'),cantidad='".$_POST['cantidads'][$cad]."',precio='".$_POST['precios'][$cad]."',total='$totales' WHERE cod_venta='$cad'";
	$result=mysql_query($sql) or die (mysql_error());
}

foreach($contactos as $cud=>$b1)
{
	$sql="UPDATE ficha_contacto_pf SET nombre=UPPER('".$_POST['contactos'][$cud]."'),cod_mercado='".$_POST['mercados'][$cud]."',acuerdos=UPPER('".$_POST['acuerdos'][$cud]."'),producto=UPPER('".$_POST['producto_finals'][$cud]."') WHERE cod_contacto='$cud'";
	$result=mysql_query($sql) or die (mysql_error());
}

//3.- Ingreso la nueva info de ventas y contactos
		for($i=0;$i<=5;$i++)
		{
			if ($_POST['producto'][$i]<>NULL)
			{
				$total=$_POST['cantidad'][$i]*$_POST['precio'][$i];
				$sql="INSERT INTO ficha_ventas_pf VALUES('',UPPER('".$_POST['producto'][$i]."'),UPPER('".$_POST['unidad'][$i]."'),'".$_POST['cantidad'][$i]."','".$_POST['precio'][$i]."','$total','$codigo')";
				$result=mysql_query($sql) or die (mysql_error());	
			}
		}
		//4.- Realizo el registro de los contactos comerciales
		for ($i=0;$i<=5;$i++)
		{
			if ($_POST['contacto'][$i]<>NULL)
			{
				$sql="INSERT INTO ficha_contacto_pf VALUES('',UPPER('".$_POST['contacto'][$i]."'),'".$_POST['mercado'][$i]."',UPPER('".$_POST['acuerdo'][$i]."'),UPPER('".$_POST['producto_final'][$i]."'),'$codigo')";
				$result=mysql_query($sql) or die (mysql_error());
			}
		}
		
		//5.- Redirecciono
		echo "<script>window.location ='n_pf_pdn.php?SES=$SES&anio=$anio&cod=$codigo&modo=asistente'</script>";

}
elseif($action==DELETE_ASISTENTE)
{
	$sql="DELETE FROM ficha_participante_pf WHERE n_documento='$id' AND cod_feria='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	
	echo "<script>window.location ='n_pf_pdn.php?SES=$SES&anio=$anio&cod=$cod&modo=asistente'</script>";
}
?>