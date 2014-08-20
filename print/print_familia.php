<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($modo==excell)
{
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=REPORTE_FAMILIAS.xls");
header("Pragma: no-cache");
}

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$sql="SELECT * FROM org_ficha_organizacion WHERE cod_tipo_doc='$cod1' AND n_documento='$cod2'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);



?>
<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8" />

  <!-- Set the viewport width to device width for mobile -->
  <meta name="viewport" content="width=device-width" />

  <title>::SIIR - Sistema de Informacion de Iniciativas Rurales::</title>
   <link type="image/x-icon" href="../images/favicon.ico" rel="shortcut icon" />
   
   
  <link rel="stylesheet" href="../stylesheets/foundation.css">
  <link rel="stylesheet" href="../stylesheets/general_foundicons.css">
 
  <link rel="stylesheet" href="../stylesheets/app.css">
  <link rel="stylesheet" href="../rtables/responsive-tables.css">
  
  <script src="../javascripts/modernizr.foundation.js"></script>
  <script src="../rtables/javascripts/jquery.min.js"></script>
  <script src="../rtables/responsive-tables.js"></script>
    <!-- Included CSS Files (Compressed) -->
  <!--
  <link rel="stylesheet" href="stylesheets/foundation.min.css">
-->  
<!-- cargamos el estilo de la pagina -->
<link href="stylesheets/print.css" rel="stylesheet" type="text/css">
<style type="text/css">

  @media print 
  {
    .oculto {display:none;background-color: #333;color:#FFF; font: bold 84% 'trebuchet ms',helvetica,sans-serif;  }
  }
</style>
<!-- Fin -->
</head>
<body>


<!-- Iniciamos el contenido -->

<div class="row">

    <div class="twelve columns centrado"><h6>REPORTE DE FAMILIAS DE LA ORGANIZACION "<? echo $r1['nombre'];?>"</h6></div>
    
    
    <table>
	    
	    <thead>
	    <tr>
		    <th colspan="5">JEFE DE FAMILIA</th>
		    <th colspan="5">PAREJA</th>
	    </tr>
	    
		    <tr>
			    <th>Nº</th>
			    <th>DNI</th>
			    <th>Nombres y Apellidos</th>
			    <th>Fecha de Nacimiento</th>
			    <th>Sexo</th>
			    <th>DNI</th>
			    <th>Nombres y Apellidos</th>
			    <th>Fecha de Nacimiento</th>
			    <th>Sexo</th>
			    <th>Nº Hijos menores de 5 años</th>
		    </tr>
	    </thead>
	    
	    <tbody>
	    <?
	    $num=0;
	    $sql="SELECT org_ficha_usuario.n_documento, 
	    org_ficha_usuario.nombre, 
	    org_ficha_usuario.paterno, 
	    org_ficha_usuario.materno, 
	    org_ficha_usuario.n_documento_conyuge, 
	    org_ficha_usuario.titular, 
		org_ficha_usuario.f_nacimiento, 
		org_ficha_usuario.sexo, 
		org_ficha_usuario.n_hijo
		FROM org_ficha_usuario
		WHERE org_ficha_usuario.cod_tipo_doc_org='$cod1' AND
		org_ficha_usuario.n_documento_org='$cod2' AND
		org_ficha_usuario.titular=1
		ORDER BY org_ficha_usuario.nombre ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($fila=mysql_fetch_array($result))
		{
			$num++
		
	    ?>
		    <tr>
			    <td><? echo $num;?></td>
			    <td><? echo $fila['n_documento'];?></td>
			    <td><? echo $fila['nombre']." ".$fila['paterno']." ".$fila['materno'];?></td>
			    <td><? echo fecha_normal($fila['f_nacimiento']);?></td>
			    <td><? if ($fila['sexo']==1) echo "M"; else echo "F";?></td>
			    <td>

			<?
			$sql="SELECT org_ficha_usuario.n_documento, 
			org_ficha_usuario.nombre, 
			org_ficha_usuario.paterno, 
			org_ficha_usuario.materno,
			org_ficha_usuario.sexo
			FROM org_ficha_usuario
			WHERE org_ficha_usuario.cod_tipo_doc_org='$cod1' AND
			org_ficha_usuario.n_documento_org='$cod2'";
			$result1=mysql_query($sql) or die (mysql_error());
			while($f2=mysql_fetch_array($result1))
			{
			?>
			<? if ($f2['n_documento']==$fila['n_documento_conyuge'] and $fila['titular']==1)  echo $f2['n_documento'];?>
			<?
			}
			?>
			    </td>
			    <td>
			<?
			$sql="SELECT org_ficha_usuario.n_documento, 
			org_ficha_usuario.nombre, 
			org_ficha_usuario.paterno, 
			org_ficha_usuario.materno
			FROM org_ficha_usuario
			WHERE org_ficha_usuario.cod_tipo_doc_org='$cod1' AND
			org_ficha_usuario.n_documento_org='$cod2'";
			$result2=mysql_query($sql) or die (mysql_error());
			while($f3=mysql_fetch_array($result2))
			{
			?>
			<? if ($f3['n_documento']==$fila['n_documento_conyuge'] and $fila['titular']==1)  echo $f3['nombre']." ".$f3['paterno']." ".$f3['materno'];?>
			<?
			}
			?>
			    </td>
			    <td>
			<?
			$sql="SELECT org_ficha_usuario.n_documento, 
			org_ficha_usuario.nombre, 
			org_ficha_usuario.paterno, 
			org_ficha_usuario.f_nacimiento
			FROM org_ficha_usuario
			WHERE org_ficha_usuario.cod_tipo_doc_org='$cod1' AND
			org_ficha_usuario.n_documento_org='$cod2'";
			$result3=mysql_query($sql) or die (mysql_error());
			while($f4=mysql_fetch_array($result3))
			{
			?>
			<? if ($f4['n_documento']==$fila['n_documento_conyuge'] and $fila['titular']==1)  echo fecha_normal($f4['f_nacimiento']);?>
			<?
			}
			?>

			    </td>
			    
			    <td>

			<?
			$sql="SELECT org_ficha_usuario.n_documento, 
			org_ficha_usuario.nombre, 
			org_ficha_usuario.paterno, 
			org_ficha_usuario.materno,
			org_ficha_usuario.sexo
			FROM org_ficha_usuario
			WHERE org_ficha_usuario.cod_tipo_doc_org='$cod1' AND
			org_ficha_usuario.n_documento_org='$cod2'";
			$result4=mysql_query($sql) or die (mysql_error());
			while($f5=mysql_fetch_array($result4))
			{
			?>
			<? if ($f5['n_documento']==$fila['n_documento_conyuge'] and $fila['titular']==1 and $f5['sexo']==1) echo "M"; elseif ($f5['n_documento']==$fila['n_documento_conyuge'] and $fila['titular']==1 and $f5['sexo']==0) echo "F";?>
			<?
			}
			?>
			    </td>
			    
			    <td class="centrado"><? echo $fila['n_hijo'];?></td>
		    </tr>
		 <?
		 }
		 ?>   
	    </tbody>
	    
	    
    </table>
   <br/> 
<div class="twelve columns">
    
    <button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
    <a href="print_familia.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod1=<? echo $cod1;?>&cod2=<? echo $cod2;?>&modo=excell" class="success button oculto">Exportar a Excell</a>
    <a href="../familias.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>

</div>

</div>





  <!-- Footer -->


  <!-- Included JS Files (Uncompressed) -->
  <!--
  
  <script src="javascripts/jquery.js"></script>
  
  <script src="javascripts/jquery.foundation.mediaQueryToggle.js"></script>
  
  <script src="javascripts/jquery.foundation.forms.js"></script>
  
  <script src="javascripts/jquery.event.move.js"></script>
  
  <script src="javascripts/jquery.event.swipe.js"></script>
  
  <script src="javascripts/jquery.foundation.reveal.js"></script>
  
  <script src="javascripts/jquery.foundation.orbit.js"></script>
  
  <script src="javascripts/jquery.foundation.navigation.js"></script>
  
  <script src="javascripts/jquery.foundation.buttons.js"></script>
  
  <script src="javascripts/jquery.foundation.tabs.js"></script>
  
  <script src="javascripts/jquery.foundation.tooltips.js"></script>
  
  <script src="javascripts/jquery.foundation.accordion.js"></script>
  
  <script src="javascripts/jquery.placeholder.js"></script>
  
  <script src="javascripts/jquery.foundation.alerts.js"></script>
  
  <script src="javascripts/jquery.foundation.topbar.js"></script>
  
  <script src="javascripts/jquery.foundation.joyride.js"></script>
  
  <script src="javascripts/jquery.foundation.clearing.js"></script>
  
  <script src="javascripts/jquery.foundation.magellan.js"></script>
  
  -->
  
  <!-- Included JS Files (Compressed) -->
  <script src="../javascripts/jquery.js"></script>
  <script src="../javascripts/foundation.min.js"></script>
  
  <!-- Initialize JS Plugins -->
  <script src="../javascripts/app.js"></script>
</body>
</html>
