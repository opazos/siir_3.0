<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);

$sql="SELECT pit_bd_ficha_mrn.sector, 
	pit_bd_ficha_mrn.lema, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_mrn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
WHERE pit_bd_ficha_mrn.cod_mrn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);
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
  <link rel="stylesheet" href="../stylesheets/printer.css" type="text/css" media="print" charset="utf-8"> 
  
  <script src="../javascripts/modernizr.foundation.js"></script>
  <script src="../rtables/javascripts/jquery.min.js"></script>
  <script src="../rtables/responsive-tables.js"></script>
    <!-- Included CSS Files (Compressed) -->
  <!--
  <link rel="stylesheet" href="stylesheets/foundation.min.css">
-->  
    <style type="text/css">

  @media print 
  {
    .oculto {display:none;background-color: #333;color:#FFF; font: bold 84% 'trebuchet ms',helvetica,sans-serif;  }
  }
</style>
</head>
<body>


<!-- Iniciamos el contenido -->




    <div class="twelve columns">
    <div class="row">

	    <div class="twelve columns" align="center"><h5>RELACION DE PARTICIPANTES DEL PLAN DE GESTIÓN DE RECURSOS NATURALES - SEGUNDO DESEMBOLSO</h5></div>
	    <div class="twelve columns"><h6>Nombre de la Organizacion de Plan de Gestión de Recursos Naturales</h6></div>
	    <div class="twelve columns"><? echo $row['nombre'];?></div>
	    <div class="twelve columns"><br/></div>
	    <div class="twelve columns"><h6>Lema</h6></div>
	    <div class="twelve columns"><? echo $row['lema'];?></div>	    
	    <div class="twelve columns"><hr/></div>
	    
	    
	    <table>
		    <thead>
			    <tr>
				    <th>Nº</th>
				    <th>DNI</th>
				    <th>Nombres y apellidos completos</th>
				    <th>Edad</th>
				    <th>Sexo</th>
				    <th>Departamento</th>
				    <th>Provincia</th>
				    <th>Distrito</th>
				    <th>Direccion</th>
				    <th>Es jefe de familia?</th>
				    <th>Momento</th>
				    <th>Vigente</th>
			    </tr>
		    </thead>
		    
		    <tbody>
		    <?
		    $num=0;
		    $sql="SELECT pit_bd_user_iniciativa.n_documento, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno, 
	org_ficha_usuario.f_nacimiento, 
	org_ficha_usuario.sexo, 
	org_ficha_usuario.direccion, 
	org_ficha_usuario.titular, 
	sys_bd_ubigeo_dist.descripcion AS distrito, 
	sys_bd_ubigeo_prov.descripcion AS provincia, 
	sys_bd_ubigeo_dep.descripcion AS departamento, 
	pit_bd_user_iniciativa.momento, 
	pit_bd_user_iniciativa.estado
FROM pit_bd_user_iniciativa INNER JOIN pit_bd_ficha_mrn ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org
	 LEFT JOIN sys_bd_ubigeo_dist ON sys_bd_ubigeo_dist.cod = org_ficha_usuario.ubigeo
	 LEFT JOIN sys_bd_ubigeo_prov ON sys_bd_ubigeo_prov.cod = sys_bd_ubigeo_dist.relacion
	 LEFT JOIN sys_bd_ubigeo_dep ON sys_bd_ubigeo_dep.cod = sys_bd_ubigeo_prov.relacion
WHERE pit_bd_ficha_mrn.cod_mrn='$cod'
ORDER BY org_ficha_usuario.nombre ASC, org_ficha_usuario.titular ASC";
			$result=mysql_query($sql) or die (mysql_error());
			while($fila=mysql_fetch_array($result))
			{
				$num++
			
			?>
			    <tr>
				    <td><? echo $num;?></td>
				    <td><? echo $fila['n_documento'];?></td>
				    <td><? echo $fila['nombre']." ".$fila['paterno']." ".$fila['materno'];?></td>
				    <td><? $edad=$fecha_hoy-$fila['f_nacimiento']; echo $edad;?></td>
				    <td><? if ($fila['sexo']==1) echo "M"; else echo "F";?></td>
				    <td><? echo $fila['departamento'];?></td>
				    <td><? echo $fila['provincia'];?></td>
				    <td><? echo $fila['distrito'];?></td>
				    <td><? echo $fila['direccion'];?></td>
				    <td><? if ($fila['titular']==1) echo "SI"; else echo "NO";?></td>
				    <td><? if ($fila['momento']==1) echo "M 1"; else echo "M 2";?></td>
				    <td><? if ($fila['estado']==1) echo "Vigente"; else echo "Retirado";?></td>
			    </tr>
			 <?
			 }
			 ?>   
		    </tbody>
	    </table>
	    
	    
	

    <form name="form1" method="post" action="">
    <button type="submit" class="button secondary oculto" onclick="window.print()">Imprimir</button>
<?
if ($tipo==1)
{
?>
<a href="../seguimiento/mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime_familia_seg" class="button secondary oculto">Retornar al menu principal</a>
<?
}
else
{
?>
<a href="../pit/pgrn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime_familia" class="button secondary oculto">Retornar al menu principal</a>
<?
}
?>




    </form>


    </div>
  </div>




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
