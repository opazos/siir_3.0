<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$sql="SELECT cif_bd_concurso.n_concurso, 
	cif_bd_concurso.f_concurso, 
	org_ficha_organizacion.nombre
FROM pit_bd_ficha_mrn INNER JOIN cif_bd_concurso ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
WHERE cif_bd_concurso.cod_concurso_cif='$id'";
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
<dd  class="active"><a href="">Información consolidada de concursos</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" action="gestor_consolidado.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=ADD" onsubmit="return checkSubmit();">

<div class="twelve columns"><h5><? echo $r1['n_concurso'];?> CONCURSO INTERFAMILIAR DE LA ORGANIZACION "<? echo $r1['nombre'];?>" CON FECHA <? echo fecha_normal($r1['f_concurso']);?></h5></div>	
<div class="twelve columns"><hr/></div>
	
<div class="twelve columns">
<ul class="accordion">
<?
$sql="SELECT cif_bd_ficha.n_participantes, 
	cif_bd_ficha.n_mujeres, 
	cif_bd_ficha.n_varones, 
	cif_bd_ficha.meta, 
	cif_bd_ficha.valor_meta, 
	cif_bd_ficha.n_premios, 
	cif_bd_ficha.monto_premios, 
	cif_bd_ficha.premio_max, 
	cif_bd_ficha.premio_min, 
	cif_bd_ficha.premio_otr, 
	sys_bd_actividad_mrn.unidad, 
	sys_bd_actividad_mrn.descripcion, 
	cif_bd_ficha.cod_ficha_cif
FROM sys_bd_actividad_mrn INNER JOIN cif_bd_ficha ON sys_bd_actividad_mrn.cod = cif_bd_ficha.cod_actividad
WHERE cif_bd_ficha.cod_concurso='$id'";
$result=mysql_query($sql) or die (mysql_error());
while($fila=mysql_fetch_array($result))
{
	$cad=$fila['cod_ficha_cif'];
?>
<li>
<div class="title"><h5>Actividad: <? echo $fila['descripcion'];?>, Unidad de medida: <? echo $fila['unidad'];?> </h5></div>
<div class="content">
<div class="row">

<div class="twelve columns"><h6>I.- Participantes</h6></div>
<div class="two columns">Nº de participantes</div>
<div class="ten columns"><input type="text" name="n_participante[<? echo $cad;?>]" class="two columns required digits" value="<? echo $fila['n_participantes'];?>"></div>
<div class="two columns">Participantes varones</div>
<div class="four columns"><input type="text" name="n_varones[<? echo $cad;?>]" class="five required digits" value="<? echo $fila['n_varones'];?>"></div>
<div class="two columns">Participantes mujeres</div>
<div class="four columns"><input type="text" name="n_mujeres[<? echo $cad;?>]" class="five required digits" value="<? echo $fila['n_mujeres'];?>"></div>
<div class="twelve columns"><h6>II.- Meta lograda</h6></div>
<div class="two columns">Meta lograda</div>
<div class="four columns"><input type="text" name="meta[<? echo $cad;?>]" class="five required number" value="<? echo $fila['meta'];?>"></div>
<div class="two columns">Valor estimado de la meta lograda (S/.)</div>
<div class="four columns"><input type="text" name="valor_meta[<? echo $cad;?>]" class="five required number" value="<? echo $fila['valor_meta'];?>"></div>
<div class="twelve columns"><h6>III.- Premiación</h6></div>
<div class="two columns">Nº de premios otorgados</div>
<div class="four columns"><input type="text" name="n_premio[<? echo $cad;?>]" class="five required digits" value="<? echo $fila['n_premios'];?>"></div>
<div class="two columns">Monto total de Premios</div>
<div class="four columns"><input type="text" name="monto_premio[<? echo $cad;?>]" class="five required number" value="<? echo $fila['monto_premios'];?>"></div>
<div class="two columns">Monto del premio maximo (S/.)</div>
<div class="four columns"><input type="text" name="premio_max[<? echo $cad;?>]" class="five required number" value="<? echo $fila['premio_max'];?>"></div>
<div class="two columns">Monto del premio minimo (S/.)</div>
<div class="four columns"><input type="text" name="premio_min[<? echo $cad;?>]" class="five required number" value="<? echo $fila['premio_min'];?>"></div>
<div class="two columns">Monto total de premio otorgado por otras entidades</div>
<div class="ten columns"><input type="text" name="premio_otro[<? echo $cad;?>]" class="two required number" value="<? echo $fila['premio_otr'];?>"></div>

</div>
</div>
</li>
<?
}
?>
</ul>
</div>	
	
<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<a href="cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=consolidado" class="secondary button">Cancelar</a>
</div>	
	
	
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
