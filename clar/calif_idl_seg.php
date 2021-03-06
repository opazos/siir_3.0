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
<dd class="active"><a href="#simple1">Registro de calificaciones</a></dd>
<dd><a href="#simple2">Calificaciones ingresadas</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" action="gestor_calif_idl.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=ADD&modo=SEGUNDO_CLAR" onsubmit="return checkSubmit();">
<div class="two columns">Seleccionar IDL</div>
<div class="four columns">
	<select name="iniciativa" class="hyjack">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT clar_bd_ficha_idl_2.cod_ficha_idl_clar, 
		pit_bd_ficha_idl.denominacion, 
		org_ficha_organizacion.n_documento, 
		org_ficha_organizacion.nombre
		FROM pit_bd_ficha_idl INNER JOIN clar_bd_ficha_idl_2 ON pit_bd_ficha_idl.cod_ficha_idl = clar_bd_ficha_idl_2.cod_idl
		INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND 	org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
		 WHERE clar_bd_ficha_idl_2.cod_clar='$id'
		ORDER BY org_ficha_organizacion.nombre ASC";
	 	$result=mysql_query($sql) or die (mysql_error());
	 	while($f1=mysql_fetch_array($result))
	 	{
		?>
		<option value="<? echo $f1['cod_ficha_idl_clar'];?>"><? echo $f1['nombre']." - ".$f1['denominacion'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="two columns">Seleccionar Jurado</div>
<div class="four columns">
	<select name="jurado" class="hyjack">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT clar_bd_jurado_evento_clar.cod_jurado, 
		clar_bd_miembro.n_documento, 
		clar_bd_miembro.nombre, 
		clar_bd_miembro.paterno, 
		clar_bd_miembro.materno
		FROM clar_bd_miembro INNER JOIN clar_bd_jurado_evento_clar ON clar_bd_miembro.cod_tipo_doc = clar_bd_jurado_evento_clar.cod_tipo_doc AND clar_bd_miembro.n_documento = clar_bd_jurado_evento_clar.n_documento
		WHERE clar_bd_jurado_evento_clar.cod_clar='$id' AND
		clar_bd_jurado_evento_clar.calif_clar=1
		ORDER BY clar_bd_miembro.nombre ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($f2=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f2['cod_jurado'];?>"><? echo $f2['nombre']." ".$f2['paterno']." ".$f2['materno'];?></option>
		<?
		}
		?>
	</select>
</div>

<div class="twelve columns"><hr/></div>

<table>
<thead>
        <tr>
          <th class="five">EJES</th>
          <th class="five">CRITERIOS</th>
          <th>PUNTAJE MAXIMO</th>
        </tr>
</thead>       
        <tr>
          <td colspan="3" class="txt_titulo">EVALUACION CLAR</td>
        </tr>
        
        <tr>
          <td rowspan="5">I.- EJECUCION FISICA</td>
          <td>1.1 Se evidencia que la IDL se ejecuta en coherencia con el diseño y las normas tecnicas</td>
          <td align="center"><input name="p1" type="text" class="required number mini" id="p1" /></td>
        </tr>
        
        <tr>
          <td>1.2 El avance logrado permitirá concluir la obra en plazo razonable</td>
          <td align="center"><input name="p2" type="text" class="required number mini" id="p2" /></td>
        </tr>
        <tr>
          <td>1.3 Se evidencia que existen condiciones técnicas, administrativas y legales favorables</td>
          <td align="center"><input name="p3" type="text" class="required number mini" id="p3" /></td>
        </tr>    
        <tr>
	        <td>1.4 Las condiciones climaticas y ambientales son favorables para la ejecución de la IDL</td>
	        <td align="center"><input name="p4" type="text" class="required number mini" id="p4"></td>
        </tr>
        <tr>
	        <td>1.5 Se evidencian condiciones favorables para la operación y mantenimiento de la IDL</td>
	        <td align="center"><input name="p5" type="text" class="require number mini" id="p5"></td>    
        </tr>
        
        
        
        
        
        
        <tr>
          <td rowspan="3">II.- EJECUCION FINANCIERA</td>
          <td>2.1 Los aportes financieros han sido cumplidos, guardan coherencia con el avance físico</td>
          <td align="center"><input name="p6" type="text" class="required number mini" id="p6" /></td>
        </tr>
        <tr>
          <td>2.2 Se evidencia que no habran limitaciones financieras para concluir la IDL satisfactoriamente</td>
          <td align="center"><input name="p7" type="text" class="required number mini" id="p7" /></td>
        </tr>
        <tr>
          <td>2.3 Se ha logrado apalancar recursos financieros para concluir o mejorar la IDL</td>
          <td align="center"><input name="p8" type="text" class="required number mini" id="p8" /></td>
        </tr>


        <tr>
          <td rowspan="3">II.- PRESENTACION Y PARTICIPACION</td>
          <td>3.1 Presentación clara y entendible por parte de la entidad responsable</td>
          <td align="center"><input name="p9" type="text" class="required number mini" id="p9" /></td>
        </tr>
        <tr>
          <td>3.2 Participan potenciales beneficiarios de la IDL y contribuyen a la presentación</td>
          <td align="center"><input name="p10" type="text" class="required number mini" id="p10" /></td>
        </tr>
        <tr>
          <td>3.3 Se muestra evidencias que la población beneficiaria participa en la ejecución de la IDL</td>
          <td align="center"><input name="p11" type="text" class="required number mini" id="p11" /></td>
        </tr>
      </table>
      
              
      <div class="twelve columns"><br/></div>
      <div class="twelve columns">
	      <button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	      <a href="calif_clar_segundo.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button">Finalizar</a>
      </div>

	
</form>
</div>
</li>

<li id="simple2Tab">

<table>
	<thead>
		<tr>
			<th>Nº</th>
			<th>Nº Documento</th>
			<th>Nombre de la Organización</th>
			<th>Nombre del Jurado</th>
			<th>Puntaje</th>
			<th><br/></th>
		</tr>
	</thead>
	
	<tbody>
	<?
	$num=0;
	$sql="SELECT clar_calif_ficha_idl_clar_2.total_puntaje, 
	clar_calif_ficha_idl_clar_2.cod_ficha_calif_idl, 
	pit_bd_ficha_idl.denominacion, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre AS organizacion, 
	clar_bd_miembro.nombre, 
	clar_bd_miembro.paterno, 
	clar_bd_miembro.materno
FROM clar_bd_ficha_idl_2 INNER JOIN clar_calif_ficha_idl_clar_2 ON clar_bd_ficha_idl_2.cod_ficha_idl_clar = clar_calif_ficha_idl_clar_2.cod_ficha_idl_clar
	 INNER JOIN clar_bd_jurado_evento_clar ON clar_bd_jurado_evento_clar.cod_jurado = clar_calif_ficha_idl_clar_2.cod_jurado
	 INNER JOIN clar_bd_miembro ON clar_bd_miembro.cod_tipo_doc = clar_bd_jurado_evento_clar.cod_tipo_doc AND clar_bd_miembro.n_documento = clar_bd_jurado_evento_clar.n_documento
	 INNER JOIN pit_bd_ficha_idl ON pit_bd_ficha_idl.cod_ficha_idl = clar_bd_ficha_idl_2.cod_idl
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
WHERE clar_bd_ficha_idl_2.cod_clar='$id'
ORDER BY organizacion ASC";
	$result=mysql_query($sql) or die (mysql_error());
	while($fila=mysql_fetch_array($result))
	{
		$num++
	
	?>
		<tr>
			<td><? echo $num;?></td>
			<td><? echo $fila['n_documento'];?></td>
			<td><? echo $fila['organizacion']." ".$fila['denominacion'];?></td>
			<td><? echo $fila['nombre']." ".$fila['paterno']." ".$fila['materno'];?></td>
			<td><? echo number_format($fila['total_puntaje'],2);?></td>
			<td><a href="gestor_calif_idl.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&cod=<? echo $fila['cod_ficha_calif_idl'];?>&action=DELETE&modo=SEGUNDO_CLAR" class="small alert button" onclick="return confirm('Va a eliminar permanentemente este registro, desea proceder ?')">Eliminar</a></td>
		</tr>
	<?
	}
	?>	
	</tbody>
	
</table>

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

<!-- Script de combo buscador -->
<!-- CARGAMOS EL JQUERY -->
<script type="text/javascript" src="../plugins/jquery.js"></script>

<link href="../plugins/combo_buscador/hyjack.css" rel="stylesheet" type="text/css" />
<script src="../plugins/combo_buscador/hyjack_externo.js" type="text/javascript"></script>
<script src="../plugins/combo_buscador/configuracion.js" type="text/javascript"></script>

</body>
</html>
