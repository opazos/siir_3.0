<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
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
  
  <script src="../javascripts/modernizr.foundation.js"></script>
  <script src="../rtables/javascripts/jquery.min.js"></script>
  <script src="../rtables/responsive-tables.js"></script>
    <!-- Included CSS Files (Compressed) -->



</head>
<body>
<? include("menu.php");?>


<!-- Iniciamos el contenido -->

<div class="row">
<div class="three panel columns">
 <ul class="nav-bar vertical">
  <li class="has-flyout">
    <a href="">PIT</a>
    <ul class="flyout">
      <li><a href="calif_clar_primero.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=pit_campo">Calificar Campo</a></li>
      <li><a href="calif_clar_primero.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=pit_clar">Calificar CLAR</a></li>
    </ul>
  </li>
  
  <li class="has-flyout"><a href="">PDN</a>
  <ul class="flyout">
	  <li><a href="calif_clar_primero.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=pdn_campo">Calificar Campo</a></li>
	  <li><a href="calif_clar_primero.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=pdn_clar">Calificar CLAR</a></li>
  </ul>
  </li>
  
  <li class="has-flyout"><a href="">PDN Independiente</a>
  <ul class="flyout">
	  <li><a href="calif_clar_primero.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=pdn_suelto_campo">Calificar Campo</a></li>
	  <li><a href="calif_clar_primero.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=pdn_suelto_clar">Calificar CLAR</a></li>
  </ul>
  </li>

  <li class="has-flyout"><a href="">PGRN</a>
  <ul class="flyout">
	  <li><a href="calif_clar_primero.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=mrn_campo">Calificar Campo</a></li>
	  <li><a href="calif_clar_primero.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=mrn_clar">Calificar CLAR</a></li>
  </ul>
  </li>
  <li><a href="calif_clar_primero.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=idl">IDL</a></li>
  <li><a href="calif_clar_primero.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=pdn_joven">PDN Jovenes</a></li>


  <li class="active">
    <a href="calif_clar_primero.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir Resultados</a>
  </li>
</ul>
 
 
 <hr>


    </div>

    <div class="nine columns">
      <div class="panel">
        <div class="row">
        
        <div class="twelve columns">
	        <!-- aca pego el contenido -->
	        <? include("../plugins/buscar/buscador.html");?> 
	        <table id="lista" class="responsive">
		        <thead>
			        <tr>
				        <th>Nº</th>
				        <th>Nombre del evento</th>
				        <th>Fecha</th>
				        <th>Oficina</th>
				        <th>Estado</th>
				        <th><br/></th>
			        </tr>
		        </thead>
		        
		        <tbody>
		        <?
		        $sql="SELECT clar_bd_evento_clar.cod_clar, 
		        clar_bd_evento_clar.n_contrato, 
		        clar_bd_evento_clar.nombre, 
		        clar_bd_evento_clar.f_evento, 
		        sys_bd_dependencia.nombre AS oficina, 
		        clar_bd_evento_clar.estado, 
		        clar_bd_evento_clar.cod_dependencia
		        FROM sys_bd_dependencia INNER JOIN clar_bd_evento_clar ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
		        WHERE clar_bd_evento_clar.f_evento BETWEEN '$anio-01-01' AND '$anio-12-31'
		        ORDER BY clar_bd_evento_clar.f_evento ASC";
		        $result=mysql_query($sql) or die (mysql_error());
		        while($fila=mysql_fetch_array($result))
		        {
		        ?>
			        <tr>
				        <td><? echo $fila['cod_clar'];?></td>
				        <td><? echo $fila['nombre'];?></td>
				        <td><? echo fecha_normal($fila['f_evento']);?></td>
				        <td><? echo $fila['oficina'];?></td>
				        <td><? if ($fila['estado']==1) echo "RENDIDO"; else echo "POR RENDIR";?></td>
				        <td>
					        <?
					        if ($modo==pit_campo)
					        {
					        ?>
					        <a href="calif_campo_pit_prim.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_clar'];?>" class="small primary button">Calificar</a>
					        <?
					        }
					        elseif($modo==pit_clar and $row['cod_dependencia']==$fila['cod_dependencia'])
					        {
					        ?>
					        <a href="calif_clar_pit_prim.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_clar'];?>" class="small primary button">Calificar</a>
					        <?
					        }
					        elseif($modo==pdn_campo)
					        {
					        ?>
					        <a href="calif_campo_pdn_prim.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_clar'];?>" class="small primary button">Calificar</a>
					        <?
					        }
					        elseif($modo==pdn_clar and $row['cod_dependencia']==$fila['cod_dependencia'])
					        {
					        ?>
					        <a href="calif_clar_pdn_prim.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_clar'];?>" class="small primary button">Calificar</a>
					        <?
					        }
					        elseif($modo==pdn_suelto_campo)
					        {
					        ?>
					        <a href="calif_campo_pdn_suelto_prim.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_clar'];?>" class="small primary button">Calificar</a>
					        <?
					        }
					        elseif($modo==pdn_suelto_clar and $row['cod_dependencia']==$fila['cod_dependencia'])
					        {
					        ?>
					        <a href="calif_clar_pdn_suelto_prim.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_clar'];?>" class="small primary button">Calificar</a>
					        <?
					        }
					        elseif($modo==mrn_campo)
					        {
					        ?>
					        <a href="calif_campo_mrn_prim.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_clar'];?>" class="small primary button">Calificar</a>
					        <?
					        }
					        elseif($modo==mrn_clar and $row['cod_dependencia']==$fila['cod_dependencia'])
					        {
					        ?>
					        <a href="calif_clar_mrn_prim.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_clar'];?>" class="small primary button">Calificar</a>
					        <?
					        }
					        elseif($modo==idl and $row['cod_dependencia']==$fila['cod_dependencia'])
					        {
					        ?>
					        <a href="calif_idl_prim.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_clar'];?>" class="small primary button">Calificar</a>
					        <?
					        }
					        elseif($modo==pdn_joven and $row['cod_dependencia']==$fila['cod_dependencia'])
					        {
					        ?>
					        <a href="calif_pdn_joven_prim.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_clar'];?>" class="small primary button">Calificar</a>
					        <?
					        }
					        elseif($modo==imprime and $row['cod_dependencia']==$fila['cod_dependencia'])
					        {
					        ?>
					        <a href="../print/print_calif_prim.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod_clar'];?>" class="small success button">Imprimir</a>
					        <?
					        }
					        ?>					        
				        </td>
			        </tr>
			     <?
			     }
			     ?>   
		        </tbody>
		        
	        </table>

	        <!-- fin del contenido -->
        </div>
        </div>
      </div>

    </div>

  </div>

  <!-- Footer -->
<? include("../footer.php");?>


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
  <script type="text/javascript" src="../plugins/buscar/js/buscar-en-tabla.js"></script>
</body>
</html>
