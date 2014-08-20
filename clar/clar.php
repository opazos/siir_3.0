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
  <li class="has-flyout active"><a href="">Evento CLAR</a>
  <ul class="flyout">
	  <li><a href="n_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Registrar evento</a></li>
	  <li class="divider"></li>
	  <li><a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir propuesta</a></li>
	  <li class="divider"></li>
	  <li><a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar evento</a></li>
	  <li class="divider"></li>
	  <li><a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=delete">Eliminar evento</a></li>
	  
  </ul>
  </li>
  
  <li class="has-flyout"><a href="">Iniciativas Participantes</a>
  <ul class="flyout">
	  <li><a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=asignacion">Asignar participantes</a></li>
	  <li><a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime_consistencia">Imprimir Consistencia</a></li>
  </ul>
  </li>
  
  <li class="has-flyout"><a href="">Fichas de calificacion</a>
	  <ul class="flyout">
		  <li><a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=pit_campo">Ficha PIT - Campo</a></li>
		  <li><a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=pit_clar">Ficha PIT - CLAR</a></li>
		  <li><a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=mrn_campo">Ficha PGRN - Campo</a></li>
		  <li><a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=mrn_clar">Ficha PGRN - CLAR</a></li>
		  <li><a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=pdn_campo">Ficha PDN - Campo</a></li>
		  <li><a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=pdn_clar">Ficha PDN - CLAR</a></li>
		  <li><a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=pdn_joven">Ficha PDN Jovenes</a></li>
		  <li><a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=idl">Ficha IDL</a></li>
		  
	  </ul>
  </li>

  <li class="has-flyout"><a href="">Acta CLAR</a>
  
  <ul class="flyout">
	  <li><a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=new_acta">Generar Acta</a>
	  <li><a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit_acta">Modificar Contenido</a></li>
	  <li><a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=reset_acta">Resetear Acta</a></li>
	  <li><a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=print_acta">Imprimir Acta</a></li>
  </ul>
  
  </li>  
  
  <li class="has-flyout"><a href="">Rendir Evento CLAR</a>
	  <ul class="flyout">
		  <li><a href="n_rinde_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Rendir evento</a></li>
		  <li class="divider"></li>
		  <li><a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit_rinde">Modificar rendicion</a></li>
		  <li class="divider"></li>
		  <li><a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=print_rinde">Imprimir rendicion</a></li>
		  
	  </ul>
  </li>
  

  
  <li class="has-flyout"><a href="">Liquidar contrato CLAR</a>
  <ul class="flyout">
	  <li><a href="n_liquida_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Liquidar contrato</a></li>
	  <li class="divider"></li>
	  <li><a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit_liquida">Modificar liquidación</a></li>
	  <li class="divider"></li>
	  <li><a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=print_liquida">Imprimir liquidación</a></li>
  </ul>
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
		        if ($row['cod_dependencia']==001)
		        {
		        $sql="SELECT clar_bd_evento_clar.cod_clar, 
		        clar_bd_evento_clar.n_contrato, 
		        clar_bd_evento_clar.nombre, 
		        clar_bd_evento_clar.f_evento, 
		        sys_bd_dependencia.nombre AS oficina, 
		        clar_bd_evento_clar.estado, 
		        clar_bd_acta.cod_acta, 
		        clar_bd_evento_clar.cod_dependencia
		        FROM sys_bd_dependencia INNER JOIN clar_bd_evento_clar ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
		        LEFT JOIN clar_bd_acta ON clar_bd_acta.cod_clar = clar_bd_evento_clar.cod_clar
		        WHERE clar_bd_evento_clar.f_evento BETWEEN '$anio-01-01' AND '$anio-12-31'
		        ORDER BY clar_bd_evento_clar.f_evento ASC";
		        }
		        else
		        {
		        $sql="SELECT clar_bd_evento_clar.cod_clar, 
		        clar_bd_evento_clar.n_contrato, 
		        clar_bd_evento_clar.nombre, 
		        clar_bd_evento_clar.f_evento, 
		        sys_bd_dependencia.nombre AS oficina, 
		        clar_bd_evento_clar.estado, 
		        clar_bd_acta.cod_acta, 
		        clar_bd_evento_clar.cod_dependencia
		        FROM sys_bd_dependencia INNER JOIN clar_bd_evento_clar ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
		        LEFT JOIN clar_bd_acta ON clar_bd_acta.cod_clar = clar_bd_evento_clar.cod_clar
		        WHERE 
		        clar_bd_evento_clar.f_evento BETWEEN '$anio-01-01' AND '$anio-12-31'
		        ORDER BY clar_bd_evento_clar.f_evento ASC";
		        }
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
					        if ($modo==imprime and $fila['cod_dependencia']==$row['cod_dependencia'])
					        {
					        ?>
					        <a href="../print/print_demanda_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod_clar'];?>" class="small success button">Imprimir</a>
					        <?
					        }
					        elseif($modo==edit and $fila['cod_dependencia']==$row['cod_dependencia'])
					        {
					        ?>
					        <a href="m_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_clar'];?>" class="small primary button">Editar</a>
					        <?
					        }
					        elseif($modo==delete and $fila['cod_dependencia']==$row['cod_dependencia'])
					        {
					        ?>
					        <a href="gestor_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_clar'];?>&action=DELETE" class="small alert button">Eliminar</a>
					        <?
					        }
					        elseif($modo==asignacion and $fila['cod_dependencia']==$row['cod_dependencia'])
					        {
					        ?>
					        <a href="n_asigna_participante.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_clar'];?>&modo=pit" class="small primary button">Asignar participantes</a>
					        <?
					        }
					        elseif($modo==imprime_consistencia and $fila['cod_dependencia']==$row['cod_dependencia'])
					        {
					        ?>
					        <a href="../print/print_consistencia.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod_clar'];?>" class="small success button">Imprimir consistencia</a>
					        <?
					        }
					        elseif($modo==pit_campo)
					        {
					        ?>
					        <a href="../print/print_ficha_pit_campo.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod_clar'];?>" class="small success button">Imprimir Ficha</a>
					        <?
					        }
					        elseif($modo==pit_clar and $fila['cod_dependencia']==$row['cod_dependencia'])
					        {
					        ?>
					        <a href="../print/print_ficha_pit_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod_clar'];?>" class="small success button">Imprimir Ficha</a>
					        <?
					        }
					        elseif($modo==mrn_campo)
					        {
					        ?>
					        <a href="../print/print_ficha_mrn_campo.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod_clar'];?>" class="small success button">Imprimir Ficha</a>
					        <?
					        }
					        elseif($modo==mrn_clar and $fila['cod_dependencia']==$row['cod_dependencia'])
					        {
					        ?>
					        <a href="../print/print_ficha_mrn_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod_clar'];?>" class="small success button">Imprimir Ficha</a>
					        <?
					        }
					        elseif($modo==pdn_campo)
					        {
					        ?>
					        <a href="../print/print_ficha_pdn_campo.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod_clar'];?>" class="small success button">Imprimir Ficha</a>
					        <?
					        }
					        elseif($modo==pdn_clar and $fila['cod_dependencia']==$row['cod_dependencia'])
					        {
					        ?>
					        <a href="../print/print_ficha_pdn_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod_clar'];?>" class="small success button">Imprimir Ficha</a>
					        <?
					        }
					        elseif($modo==pdn_joven and $fila['cod_dependencia']==$row['cod_dependencia'])
					        {
					        ?>
					        <a href="../print/print_ficha_pdn_joven.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod_clar'];?>" class="small success button">Imprimir Ficha</a>
					        <?
					        }
					        elseif($modo==idl and $fila['cod_dependencia']==$row['cod_dependencia'])
					        {
					        ?>
					        <a href="../print/print_ficha_idl.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod_clar'];?>" class="small success button">Imprimir Ficha</a>
					        <?
					        }
					        elseif($modo==new_acta and $fila['cod_acta']==NULL  and $fila['cod_dependencia']==$row['cod_dependencia'])
					        {
					        ?>
					        <a href="genera_acta_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_clar'];?>&modo=nuevo" class="small primary button">Generar</a>
					        <?
					        }
					        elseif($modo==edit_acta and $fila['cod_acta']<>NULL and $fila['cod_dependencia']==$row['cod_dependencia'])
					        {
					        ?>
					        <a href="genera_acta_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_clar'];?>" class="small primary button">Editar</a>
					        <?
					        }
					        elseif($modo==reset_acta and $fila['cod_acta']<>NULL and $fila['cod_dependencia']==$row['cod_dependencia'])
					        {
					        ?>
					        <a href="genera_acta_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_clar'];?>&modo=reseteo" class="small alert button">Resetear</a>
					        <?
					        }
					        elseif($modo==print_acta and $fila['cod_acta']<>NULL and $fila['cod_dependencia']==$row['cod_dependencia'])
					        {
					        ?>
					        <a href="../print/print_acta_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod_acta'];?>" class="small success button">Imprimir</a>
					        <?
					        }
					        elseif($modo==print_rinde and $fila['estado']==1 and $fila['n_contrato']==0 and $fila['cod_dependencia']==$row['cod_dependencia'])
					        {
					        ?>
					        <a href="../print/print_rinde_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod_clar'];?>&modo=imprime" class="small success button">Imprimir</a>
					        <?
					        }
					        elseif($modo==edit_rinde and $fila['estado']==1 and $fila['n_contrato']==0 and $fila['cod_dependencia']==$row['cod_dependencia'])
					        {
					        ?>
					        <a href="m_rinde_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_clar'];?>" class="small primary button">Editar</a>	
					        <?
					        }
					        elseif($modo==edit_liquida and $fila['estado']==1 and $fila['n_contrato']<>0 and $fila['cod_dependencia']==$row['cod_dependencia'])
					        {
					        ?>
					        <a href="m_liquida_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_clar'];?>" class="small primary button">Editar</a>
					        <?
					        }
					        elseif($modo==print_liquida and $fila['estado']==1 and $fila['n_contrato']<>0 and $fila['cod_dependencia']==$row['cod_dependencia'])
					        {
					        ?>
					        <a href="../print/print_liquida_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod_clar'];?>&modo=imprime" class="small success button">Imprimir</a>
					        <?
					        }
					        elseif($modo==print_reporte and $fila['cod_dependencia']==$row['cod_dependencia'])
					        {
					        ?>
					        <a href="../report/print_reporte_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod_clar'];?>" class="small success button">Imprime</a>
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
