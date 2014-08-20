<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);


$sql="SELECT * FROM fm_formalizacion WHERE cod='$cod'";
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
  <script src="../javascripts/btn_envia.js"></script>
  <script src="../rtables/javascripts/jquery.min.js"></script>
  <script src="../rtables/responsive-tables.js"></script>
    <!-- Included CSS Files (Compressed) -->
  <!--
  <link rel="stylesheet" href="stylesheets/foundation.min.css">
-->  
</head>
<body>
<? include("menu.php");?>


<!-- Iniciamos el contenido -->
<div class="row">
 <div class="twelve columns">
       
<!-- Contenedores -->
<dl class="tabs">
<dd  class="active"><a href="">II.- Organizaciones Formalizadas</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" class="custom" method="post" action="gestor_formalicer.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&action=ADD_ORG" onsubmit="return checkSubmit();">

<div class="two columns">Nº contrato SIGLO  (Formato Número-Año)</div>
<div class="four columns"><input type="text" name="n_contrato" class="required seven" placeholder="0000-0000" maxlength="9" value="<? echo $row['n_contrato'];?>"></div>
<div class="two columns">Fecha de contrato</div>
<div class="four columns"><input type="date" name="f_contrato" class="required seven date" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $row['f_firma'];?>"></div>


<div class="two columns">Nº del Requerimiento con el que solicito el servicio SIGLO (Formato Número-Año)</div>
<div class="four columns"><input type="text" name="n_requerimiento" class="required seven" placeholder="0000-0000" maxlength="9" value="<? echo $row['n_requerimiento'];?>"></div>

<div class="two columns">Fecha de presentacion de esta solicitud de desembolso</div>
<div class="four columns"><input type="date" name="f_demanda" class="required seven date" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $row['f_solicitud'];?>"></div>




<div class="twelve columns"><h6>Justificación del Servicio</h6></div>
<div class="twelve columns">
	<textarea name="objeto" rows="5"><? echo $row['justificacion'];?></textarea>
</div>

<div class="two columns">Ente contratante</div>
<div class="ten columns"><input type="text" name="contratante" class="ten required" value="<? echo $row['contratante'];?>"></div>

<div class="two columns">Nº de cuenta</div>
<div class="four columns"><input type="text" name="n_cuenta" class="required nine" value="<? echo $row['n_cuenta'];?>"></div>
<div class="two columns">Banco</div>
<div class="four columns">
	<select name="ifi">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT * FROM sys_bd_ifi";
		$result=mysql_query($sql) or die (mysql_error());
		while($r1=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $r1['cod_ifi'];?>" <? if ($r1['cod_ifi']==$row['cod_ifi']) echo "selected";?>><? echo $r1['descripcion'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="twelve columns"><h6>III.- Conceptos de pago</h6></div>

<div class="two columns">Monto correspondiente a formalizacion de Organizaciones(S/.)</div>
<div class="two columns"><input type="text" name="monto1" class="required number seven" value="<? echo $row['monto_form'];?>"></div>

<div class="two columns">Monto correspondiente a otros conceptos (Pagos varios, tramites, etc) (S/.)</div>
<div class="two columns"><input type="text" name="monto2" class="required number seven" value="<? echo $row['monto_otro'];?>"></div>

<div class="two columns">Monto correspondiente a la contrapartida de las Organizaciones (S/.)</div>
<div class="two columns"><input type="text" name="monto3" class="required number seven" value="<? echo $row['monto_org'];?>"></div>


<div class="twelve columns"><hr/></div>
<div class="twelve columns"><h6>II.- ORGANIZACIONES FORMALIZADAS</h6></div>
<div class="two columns">Nombre de la organización</div>
<div class="ten columns">
	<select name="org">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT org_ficha_organizacion.cod_tipo_doc, 
		org_ficha_organizacion.n_documento, 
		org_ficha_organizacion.nombre
		FROM org_ficha_organizacion
		WHERE org_ficha_organizacion.cod_tipo_doc<>001 AND
		org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
		ORDER BY org_ficha_organizacion.nombre ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($r2=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $r2['cod_tipo_doc'].",".$r2['n_documento'];?>"><? echo $r2['nombre'];?></option>
		<?
		}
		?>
	</select>
</div>

<div class="twelve columns"><br/></div>
<div class="two columns">Observaciones</div>
<div class="four columns"><input type="text" name="observacion" class="required eleven"></div>
<div class="two columns">Costo de Formalización (S/.)</div>
<div class="four columns"><input type="text" name="costo" class="required number six"></div>






<div class="twelve columns"><br/></div>	
<div class="twelve columns">
	<button type="submit" class="primary button">Añadir</button>
	
	<a href="../print/print_formalicer.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>" class="success button">Imprimir</a>
	
	<a href="formalicer.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="secondary button">Finalizar</a>
</div>
<div class="twelve columns"><hr/></div>	

<table class="responsive">
	
	<thead>
		<tr>
			<th>Nº Documento</th>
			<th>Nombre de la Organización</th>
			<th>Observacion</th>
			<th>Costo del servicio de Formalización (S/.)</th>
			<th><br/></th>
		</tr>
	</thead>
	
	<tbody>
	<?
		$sql="SELECT fm_org_formalizada.justificacion, 
		fm_org_formalizada.costo, 
		org_ficha_organizacion.n_documento, 
		org_ficha_organizacion.nombre, 
		fm_org_formalizada.cod_tipo_doc, 
		fm_org_formalizada.n_documento_org, 
		fm_org_formalizada.cod_formalizador
		FROM org_ficha_organizacion INNER JOIN fm_org_formalizada ON org_ficha_organizacion.cod_tipo_doc = fm_org_formalizada.cod_tipo_doc 	AND org_ficha_organizacion.n_documento = fm_org_formalizada.n_documento_org
		WHERE fm_org_formalizada.cod_formalizador='$cod'
		ORDER BY org_ficha_organizacion.nombre ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($r3=mysql_fetch_array($result))
		{
	?>
		<tr>
			<td><? echo $r3['n_documento'];?></td>
			<td><? echo $r3['nombre'];?></td>
			<td><? echo $r3['justificacion'];?></td>
			<td><? echo number_format($r3['costo'],2);?></td>
			<td><a href="gestor_formalicer.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>" class="small alert button">Quitar</a></td>
		</tr>
	<?
	}
	?>	
	</tbody>
	
</table>




</form>







</div>
</li>
</ul>
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
  <!-- VALIDADOR DE FORMULARIOS -->
<script src="../plugins/validation/jquery.validate.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" media="screen" href="../plugins/validation/stylesheet.css" />
<script type="text/javascript" src="../plugins/validation/jquery.maskedinput.js"></script>
<script type="text/javascript" src="../plugins/validation/mktSignup.js"></script>    
</body>
</html>
