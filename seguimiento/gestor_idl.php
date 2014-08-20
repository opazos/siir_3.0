<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==UPDATE_META)
{
	//Actualizo las metas mediante un Foreach
	foreach($actividad as $cad=>$a1)
	{
		$sql="UPDATE idl_meta_fisica SET avance_fn='".$_POST['ejec'][$cad]."' WHERE cod_meta='$cad'";
		$result=mysql_query($sql) or die (mysql_error());
	}

	//2.- Ingreso los nuevos
	for($i=0;$i<=10;$i++)
	{
		if($_POST['actividads'][$i]<>NULL)
		{
			$sql="INSERT INTO idl_meta_fisica VALUES('','$id',UPPER('".$_POST['actividads'][$i]."'),UPPER('".$_POST['unidads'][$i]."'),'".$_POST['metas'][$i]."','".$_POST['ejecs'][$i]."')";
			$result=mysql_query($sql) or die (mysql_error());
		}
	}

	//envio un mensaje de confirmacion
	echo "<script>window.location ='m_meta.php?SES=$SES&anio=$anio&id=$id&alert=success_change'</script>";
}
elseif($action==UPDATE_ACTIVIDAD)
{
	//Actualizo las actividades mediante un Foreach
	foreach($estado as $cad=>$a1)
	{
		$sql="UPDATE idl_actividad_idl SET cod_estado='".$_POST['estado'][$cad]."',cumple_plazo='".$_POST['cumplimiento'][$cad]."',avance='".$_POST['avance'][$cad]."',comentario=UPPER('".$_POST['comentario'][$cad]."') WHERE cod_actividad='$cad'";
		$result=mysql_query($sql) or die (mysql_error());
	}

	//Ingreso los nuevos registros
	for($i=0;$i<=20;$i++)
	{
		if($_POST['descripcion'][$i]<>NULL)
		{
			if($_POST['mes'][$i]==1)
			{
				$m1=1;
				$m2=0;
				$m3=0;
				$m4=0;
				$m5=0;
				$m6=0;
				$m7=0;
				$m8=0;
				$m9=0;
				$m10=0;
				$m11=0;
				$m12=0;
			}
			elseif($_POST['mes'][$i]==2)
			{
				$m1=1;
				$m2=1;
				$m3=0;
				$m4=0;
				$m5=0;
				$m6=0;
				$m7=0;
				$m8=0;
				$m9=0;
				$m10=0;
				$m11=0;
				$m12=0;
			}
			elseif($_POST['mes'][$i]==3)
			{
				$m1=1;
				$m2=1;
				$m3=1;
				$m4=0;
				$m5=0;
				$m6=0;
				$m7=0;
				$m8=0;
				$m9=0;
				$m10=0;
				$m11=0;
				$m12=0;
			}
			elseif($_POST['mes'][$i]==4)
			{
				$m1=1;
				$m2=1;
				$m3=1;
				$m4=1;
				$m5=0;
				$m6=0;
				$m7=0;
				$m8=0;
				$m9=0;
				$m10=0;
				$m11=0;
				$m12=0;
			}
			elseif($_POST['mes'][$i]==5)
			{
				$m1=1;
				$m2=1;
				$m3=1;
				$m4=1;
				$m5=1;
				$m6=0;
				$m7=0;
				$m8=0;
				$m9=0;
				$m10=0;
				$m11=0;
				$m12=0;				
			}
			elseif($_POST['mes'][$i]==6)
			{
				$m1=1;
				$m2=1;
				$m3=1;
				$m4=1;
				$m5=1;
				$m6=1;
				$m7=0;
				$m8=0;
				$m9=0;
				$m10=0;
				$m11=0;
				$m12=0;
			}
			elseif($_POST['mes'][$i]==7)
			{
				$m1=1;
				$m2=1;
				$m3=1;
				$m4=1;
				$m5=1;
				$m6=1;
				$m7=1;
				$m8=0;
				$m9=0;
				$m10=0;
				$m11=0;
				$m12=0;				
			}
			elseif($_POST['mes'][$i]==8)
			{
				$m1=1;
				$m2=1;
				$m3=1;
				$m4=1;
				$m5=1;
				$m6=1;
				$m7=1;
				$m8=1;
				$m9=0;
				$m10=0;
				$m11=0;
				$m12=0;				
			}
			elseif($_POST['mes'][$i]==9)
			{
				$m1=1;
				$m2=1;
				$m3=1;
				$m4=1;
				$m5=1;
				$m6=1;
				$m7=1;
				$m8=1;
				$m9=1;
				$m10=0;
				$m11=0;
				$m12=0;				
			}
			elseif($_POST['mes'][$i]==10)
			{
				$m1=1;
				$m2=1;
				$m3=1;
				$m4=1;
				$m5=1;
				$m6=1;
				$m7=1;
				$m8=1;
				$m9=1;
				$m10=1;
				$m11=0;
				$m12=0;				
			}	
			elseif($_POST['mes'][$i]==11)
			{
				$m1=1;
				$m2=1;
				$m3=1;
				$m4=1;
				$m5=1;
				$m6=1;
				$m7=1;
				$m8=1;
				$m9=1;
				$m10=1;
				$m11=1;
				$m12=0;				
			}	
			elseif($_POST['mes'][$i]==12)
			{
				$m1=1;
				$m2=1;
				$m3=1;
				$m4=1;
				$m5=1;
				$m6=1;
				$m7=1;
				$m8=1;
				$m9=1;
				$m10=1;
				$m11=1;
				$m12=1;
			}	


			$sql="INSERT INTO idl_actividad_idl VALUES ('','$id',UPPER('".$_POST['descripcion'][$i]."'),'".$_POST['estados'][$i]."','".$_POST['cumplimientos'][$i]."','".$_POST['avances'][$i]."',UPPER('".$_POST['comentarios'][$i]."'),'$m1','$m2','$m3','$m4','$m5','$m6','$m7','$m8','$m9','$m10','$m11','$m12')";
			$result=mysql_query($sql) or die (mysql_error());
		}
	}

	//envio un mensaje de confirmacion
	echo "<script>window.location ='m_actividad.php?SES=$SES&anio=$anio&id=$id&alert=success_change'</script>";	
}
elseif($action==UPDATE_EJEC)
{
	//Actualizo la informacion antigua
	foreach($conceptos as $cad=>$a1)
	{
		$total_1=$_POST['costo_unitario_1'][$cad]*$_POST['cantidad_1s'][$cad];
		$total_2=$_POST['costo_unitario_2'][$cad]*$_POST['cantidad_2s'][$cad];
		
		$sql="UPDATE idl_detalle_financiamiento SET descripcion=UPPER('".$_POST['conceptos'][$cad]."'),unidad=UPPER('".$_POST['unidads'][$cad]."'),cantidad_1='".$_POST['cantidad_1s'][$cad]."',cantidad_2='".$_POST['cantidad_2s'][$cad]."',costo_unitario_1='".$_POST['precio_1s'][$cad]."',costo_unitario_2='".$_POST['precio_2s']."',costo_total_1='$total_1',costo_total_2='$total_2' WHERE cod_df='$cad'";
		$result=mysql_query($sql) or die (mysql_error());
	}
	
	//Ingreso los nuevos registros
	for($i=0;$i<=15;$i++)
	{
		if($_POST['concepto'][$i]<>NULL)
		{
			$total_3=$_POST['precio_1'][$i]*$_POST['cantidad_1'][$i];
			$total_4=$_POST['precio_2'][$i]*$_POST['cantidad_2'][$i];
			
			$sql="INSERT INTO idl_detalle_financiamiento VALUES('','$id',UPPER('".$_POST['concepto'][$i]."'),UPPER('".$_POST['unidad'][$i]."'),'".$_POST['cantidad_1'][$i]."','".$_POST['cantidad_2'][$i]."','".$_POST['precio_1'][$i]."','".$_POST['precio_2'][$i]."','$total_3','$total_4')";
			$result=mysql_query($sql) or die (mysql_error());
		}	
	}
	
	//Redirecciono
	echo "<script>window.location ='m_finanza.php?SES=$SES&anio=$anio&id=$id&alert=success_change'</script>";		
}
elseif($action==DELETE_EJEC)
{
	$sql="DELETE FROM idl_detalle_financiamiento WHERE cod_df='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//Redirecciono
	echo "<script>window.location ='m_finanza.php?SES=$SES&anio=$anio&id=$id&alert=success_delete'</script>";	
}
elseif($action==DELETE_META)
{
	$sql="DELETE FROM idl_meta_fisica WHERE cod_meta='$cod'";
	$result=mysql_query($sql) or die (mysql_error());

	
	//Redirecciono
	echo "<script>window.location ='m_meta.php?SES=$SES&anio=$anio&id=$id&alert=success_delete'</script>";		
}
elseif($action==DELETE_ACT)
{
	$sql="DELETE FROM idl_actividad_idl WHERE cod_actividad='$cod'";
	$result=mysql_query($sql) or die (mysql_error());

	//Redirecciono
	echo "<script>window.location ='m_actividad.php?SES=$SES&anio=$anio&id=$id&alert=success_delete'</script>";	
}
?>