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
<form name="form5" method="post" action="gestor_calif_mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=ADD&modo=SEGUNDO_CAMPO" onsubmit="return checkSubmit();">
<div class="two columns">Seleccionar PGRN</div>
<div class="four columns">
	<select name="iniciativa" class="hyjack">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT clar_bd_ficha_mrn_2.cod_ficha_mrn_clar, 
		org_ficha_organizacion.n_documento, 
		org_ficha_organizacion.nombre
		FROM pit_bd_ficha_mrn INNER JOIN clar_bd_ficha_mrn_2 ON pit_bd_ficha_mrn.cod_mrn = clar_bd_ficha_mrn_2.cod_mrn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 WHERE clar_bd_ficha_mrn_2.cod_clar='$id' AND
	 org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'";
	 	$result=mysql_query($sql) or die (mysql_error());
	 	while($f1=mysql_fetch_array($result))
	 	{
		?>
		<option value="<? echo $f1['cod_ficha_mrn_clar'];?>"><? echo $f1['n_documento']." - ".$f1['nombre'];?></option>
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
		clar_bd_jurado_evento_clar.calif_campo=1 AND
		clar_bd_miembro.cod_dependencia='".$row['cod_dependencia']."'
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
          <th>EJES</th>
          <th>CRITERIOS</th>
          <th>DESCRIPCION</th>
          <th>PUNTAJE MAXIMO</th>
        </tr>
      </thead>
        <tr>
          <td colspan="4" class="txt_titulo">EVALUACION DE CAMPO</td>
        </tr>
        <tr>
          <td rowspan="7">I.- Organización Responsable del PGRN </td>
          <td>1.1 La Junta Directiva </td>
          <td>Se evidencia que los directivos administran bien el Contrato suscrito con Sierra Sur II. Tienen la documentación en orden, vigente y/o actualizada (Partida registral, padrón de socios, Informes, documentos de pago)</td>
          <td align="center"><input name="p1" type="text" class="required number mini" id="p1" /></td>
        </tr>
        <tr>
          <td>1.2. Fortaleza organizacional </td>
          <td>Observa y percibe las relaciones de la Junta Directiva del PGRN con los directivos del PIT. Evalúa el relacionamiento con otra(s) institucion(es) y apoyo(s) logrado(s) en favor del PGRN</td>
          <td align="center"><input name="p2" type="text" class="required number mini" id="p2" /></td>
        </tr>
        <tr>
          <td>1.3 Manejo de Fondos </td>
          <td>Los responsables del PGRN demuestran conocimiento y capacidad para el manejo de fondos. Los socios conocen sobre el uso de los fondos transferidos por Sierra Sur II y de sus aportes</td>
          <td align="center"><input name="p3" type="text" class="required number mini" id="p3" /></td>
        </tr>
        <tr>
          <td rowspan="3">1.4 Familias comprometidas </td>
          <td>a) Mas del 75% de las familias registradas en el PGRN han participado en Concursos Inter Familiares - CIF (4 a 5 puntos)</td>
          <td rowspan="3" align="center"><input name="p4" type="text" class="required number mini" id="p4" /></td>
        </tr>
        <tr>
          <td>b) Entre 51% a 74% de las familias registradas en el PGRN han participado en CIF (de 2 a 3  puntos)</td>
          </tr>
        <tr>
          <td>c) Menos de 50% de las familias registradas en el PGRN han participado en CIF (hasta 2 puntos)</td>
          </tr>
        <tr>
          <td>1.5 Mujer y jóvenes </td>
          <td>Percepción del grado de interés y participacion de mujeres y jóvenes en Concursos Inter Familiares y en la gestión del PGRN </td>
          <td align="center"><input name="p5" type="text" class="required number mini" id="p5" /></td>
        </tr>
        
        <tr>
          <td rowspan="5">II.- Avances del PGRN</td>
          <td>2.1 Coherencia de Actividades </td>
          <td>Los temas eligidos para Concursos Inter Familiares fueron adecuados a las necesidades de las familias y/o potencialidades del territorio </td>
          <td align="center"><input name="p6" type="text" class="required number mini" id="p6" /></td>
        </tr>
        <tr>
          <td>2.2 Avances en la ejecución del PGRN </td>
          <td>Se percibe que los avances logrados en MRN por las familias entrevistada guardan relacion con los fondos utilizados y con la propuesta del PGRN</td>
          <td align="center"><input name="p7" type="text" class="required number mini" id="p7" /></td>
        </tr>
        <tr>
          <td>2.3 Asistencia Técnica </td>
          <td>Se evidencia que el (los) técnicos contratado(s) han contribuido en mejorar los conocimientos de las familias y en los avances logrados. Eligieron bien al (los) técnicos </td>
          <td align="center"><input name="p8" type="text" class="required number mini" id="p8" /></td>
        </tr>
        <tr>
          <td>2.4 Visita Guiada </td>
          <td>La visita guiada contribuyó a motivar y comprometer la participacion de las familias en el PGRN y en los avances logrados</td>
          <td align="center"><input name="p9" type="text" class="required number mini" id="p9" /></td>
        </tr>
        <tr>
          <td>2.5 Perspectivas del PGRN </td>
          <td>Se percibe que el PGRN marcha bien, pueden lograr buenos resultados a su conclusión.</td>
          <td align="center"><input name="p10" type="text" class="required number mini" id="p10" /></td>
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
	$sql="SELECT clar_calif_ficha_mrn_2.total_puntaje, 
	clar_calif_ficha_mrn_2.cod_ficha_calif_mrn, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre AS organizacion, 
	clar_bd_miembro.nombre, 
	clar_bd_miembro.paterno, 
	clar_bd_miembro.materno
FROM clar_bd_ficha_mrn_2 INNER JOIN clar_calif_ficha_mrn_2 ON clar_bd_ficha_mrn_2.cod_ficha_mrn_clar = clar_calif_ficha_mrn_2.cod_ficha_mrn_clar
	 INNER JOIN clar_bd_jurado_evento_clar ON clar_bd_jurado_evento_clar.cod_jurado = clar_calif_ficha_mrn_2.cod_jurado
	 INNER JOIN clar_bd_miembro ON clar_bd_miembro.cod_tipo_doc = clar_bd_jurado_evento_clar.cod_tipo_doc AND clar_bd_miembro.n_documento = clar_bd_jurado_evento_clar.n_documento
	 INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = clar_bd_ficha_mrn_2.cod_mrn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
WHERE clar_bd_ficha_mrn_2.cod_clar='$id'
ORDER BY organizacion ASC";
	$result=mysql_query($sql) or die (mysql_error());
	while($fila=mysql_fetch_array($result))
	{
		$num++
	
	?>
		<tr>
			<td><? echo $num;?></td>
			<td><? echo $fila['n_documento'];?></td>
			<td><? echo $fila['organizacion'];?></td>
			<td><? echo $fila['nombre']." ".$fila['paterno']." ".$fila['materno'];?></td>
			<td><? echo number_format($fila['total_puntaje'],2);?></td>
			<td><a href="gestor_calif_mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&cod=<? echo $fila['cod_ficha_calif_mrn'];?>&action=DELETE&modo=SEGUNDO_CAMPO" class="small alert button">Eliminar</a></td>
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
