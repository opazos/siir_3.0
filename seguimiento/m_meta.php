<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$sql="SELECT pit_bd_ficha_idl.denominacion, 
	org_ficha_organizacion.nombre, 
	pit_bd_ficha_idl.n_contrato, 
	pit_bd_ficha_idl.f_contrato, 
	sys_bd_departamento.nombre AS dep, 
	sys_bd_provincia.nombre AS prov, 
	sys_bd_distrito.nombre AS dist
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_idl ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
WHERE pit_bd_ficha_idl.cod_ficha_idl='$id'";
$result=Mysql_query($sql) or die (mysql_error());
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
 <? echo $mensaje;?>        
<!-- Contenedores -->
<dl class="tabs">
<dd  class="active"><a href="">Actualizar información de insumos, mano de obra y equipo</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" action="gestor_idl.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=UPDATE_META" onsubmit="return checkSubmit();">

<div class="two columns"><h6>Organizacion Contratante</h6></div>
<div class="ten columns"><h6><? echo $r1['nombre'];?></h6></div>
	
<div class="two columns"><h6>Nombre de la Obra</h6></div>
<div class="ten columns"><h6><? echo $r1['denominacion'];?></h6></div>	
<div class="two columns">Número de contrato</div>
<div class="four columns"><? echo numeracion($r1['n_contrato'])."-".periodo($r1['f_contrato']);?></div>
<div class="two columns">Fecha de contrato</div>
<div class="four columns"><? echo traducefecha($r1['f_contrato']);?></div>
<div class="two columns">Departamento</div>
<div class="four columns"><? echo $r1['dep'];?></div>
<div class="two columns">Provincia</div>
<div class="four columns"><? echo $r1['prov'];?></div>
<div class="two columns">Distrito</div>
<div class="ten columns"><? echo $r1['dist'];?></div>

<div class="twelve columns"><hr/></div>
	
<table>
<thead>
	<tr>
		<th>Nº</th>
		<th class="seven">Descripción</th>
		<th>Unidad</th>
		<th>Cantidad Programada</th>
		<th>Cantidad Ejecutada</th>
    <th><br/></th>
	</tr>
</thead>	

<?
$num=0;
$sql="SELECT * FROM idl_meta_fisica WHERE cod_ficha_idl='$id' ORDER BY descripcion DESC";
$result=mysql_query($sql) or die (mysql_error());
while($fila=mysql_fetch_array($result))
{
  $cad=$fila['cod_meta'];
  $num++
?>
  <tr>
    <td><? echo $num;?></td>
    <td><input type="text" name="actividad[<? echo $cad;?>]" value="<? echo $fila['descripcion'];?>" readonly="readonly"></td>
    <td><input type="text" name="unidad[<? echo $cad;?>]" value="<? echo $fila['unidad'];?>" readonly="readonly"></td>
    <td><input type="text" name="meta[<? echo $cad;?>]" class="number" value="<? echo $fila['meta'];?>" readonly="readonly"></td>
    <td><input type="text" name="ejec[<? echo $cad;?>]" class="number" value="<? echo $fila['avance_fn'];?>"></td>
    <td><a href="gestor_idl.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&cod=<? echo $fila['cod_meta'];?>&action=DELETE_META"><img src="../images/Delete.png" border="0" width="48" height="48"></a></td>
  </tr>
<?
}
?>
<?
$numa=$num;
for($i=1;$i<=10;$i++)
{
  $numa++
?>
  <tr>
    <td><? echo $numa;?></td>
    <td><input type="text" name="actividads[]"></td>
    <td><input type="text" name="unidads[]"></td>
    <td><input type="text" name="metas[]"></td>
    <td><input type="text" name="ejecs[]"></td>
  </tr>
<?
}
?>




</table>

<div class="twelve columns">
<button type="submit" class="success button">Guardar cambios</button>
<a href="idl.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=meta" class="secondary button">Finalizar</a>
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
