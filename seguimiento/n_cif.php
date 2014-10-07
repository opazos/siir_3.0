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



<!-- Fin Head -->   
</head>
<body>
<? include("menu.php");?>
<? echo $mensaje;?>

<!-- Iniciamos el contenido -->
<div class="row">
 <div class="twelve columns">
       
<!-- Contenedores -->
<dl class="tabs">
<dd  class="active">
<?
if ($modo==participante)
{
?>
<a href="">2 de 2.- Participantes del Concurso Interfamiliar</a>
<?
}
else
{
?>
<a href="">1 de 2.- Datos del Concurso Interfamiliar</a>
<?
}
?>

</dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">

<?
if ($modo==participante)
{
	$sql="SELECT cif_bd_concurso.cod_mrn
	FROM cif_bd_concurso
	WHERE cif_bd_concurso.cod_concurso_cif='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	$r4=mysql_fetch_array($result);
?>
<div class="twelve columns"><h6>Seleccionar a los participantes del CIF</h6></div>
<? include("../plugins/buscar/buscador.html");?>

<!-- CODIGO PARA SELECCIONAR TODOS LOS CHECKBOX -->
<script language="javascript">
  function seleccionar_todo(check_box)
  {
      for(var x=0;x<document.forms["form5"].elements.length; x++)
      {
        var input=document.forms[0].elements[x];
        if(input.type=="checkbox")
        {
            input.checked=check_box.checked;
        }
      }
  }
</script> 

<form name="form5" id="form5" method="post" action="gestor_cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&action=ADD_PARTICIPANTE" onsubmit="return checkSubmit();">


<table id="lista">
	<thead>
		<th>Nº</th>
		<th>DNI</th>
		<th class="eleven">NOMBRES Y APELLIDOS COMPLETOS</th>
		<th><span class="has-tip tip-top noradius" data-width="210" title="Seleccionar todos los campos"><input type="checkbox" name="checkbox" value="checkbox" onclick="seleccionar_todo(this);" /></span></th>
		<th><a href="gestor_cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&action=DELETE_PARTICIPANTES" class="small alert button" onclick="return confirm('Va a eliminar todos los registros, desea proceder ?')">Desvincular Todo</a></th>
	</thead>
	<tbody>	
	
<?
$num=0;
$sql="SELECT DISTINCT pit_bd_user_iniciativa.n_documento AS dni, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno, 
	cif_bd_participante_cif.n_documento
FROM pit_bd_ficha_mrn INNER JOIN pit_bd_user_iniciativa ON pit_bd_ficha_mrn.cod_mrn = pit_bd_user_iniciativa.cod_iniciativa AND pit_bd_ficha_mrn.cod_tipo_iniciativa = pit_bd_user_iniciativa.cod_tipo_iniciativa
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org
	 INNER JOIN cif_bd_concurso ON cif_bd_concurso.cod_mrn = pit_bd_ficha_mrn.cod_mrn
	 LEFT OUTER JOIN cif_bd_participante_cif ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif AND cif_bd_participante_cif.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND cif_bd_participante_cif.n_documento = pit_bd_user_iniciativa.n_documento
WHERE pit_bd_ficha_mrn.cod_mrn='".$r4['cod_mrn']."' AND
cif_bd_concurso.cod_concurso_cif='$cod'
ORDER BY org_ficha_usuario.nombre ASC";
$result=mysql_query($sql) or die (mysql_error());
while($r5=mysql_fetch_array($result))
{
	$num++
?>	
		<tr>
		<td><? echo $num;?></td>
		<td><? echo $r5['dni'];?></td>
		<td><? echo $r5['nombre']." ".$r5['paterno']." ".$r5['materno'];?></td>
		<td><input type="checkbox" name="campos[]" value="<? echo $r5['dni'];?>" <? if ($r5['n_documento']<>NULL) echo "checked";?>></td>
		<td>
		<? 
		if ($r5['n_documento']<>NULL)
		{
		?>
		<a href="gestor_cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&id=<? echo $r5['n_documento'];?>&action=DELETE_PARTICIPANTE" class="small alert button" onclick="return confirm('Va a eliminar permanentemente este registro, desea proceder ?')">Desvincular</a>
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

<div class="twelve columns"><br/></div>
<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<a href="cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button">Finalizar</a>
</div>

</form>
<?
}
else
{
?>
<form name="form5" method="post" action="gestor_cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=ADD" onsubmit="return checkSubmit();">
<div class="two columns">Nº concurso</div>
<div class="four columns">
<select name="n_concurso" class="five required">
<option value="" selected="selected">Seleccionar</option>
<option value="1">Primero</option>
<option value="2">Segundo</option>
<option value="3">Tercero</option>
<option value="4">Cuarto</option>
<option value="5">Quinto</option>	
</select>
</div>
<div class="two columns">Fecha de concurso</div>
<div class="four columns"><input type="date" name="f_concurso" class="seven" required="required" maxlength="10" placeholder="aaaa-mm-dd"></div>
<div class="twelve columns">Organizacion que realiza el concurso</div>
<div class="twelve columns">
	<select name="mrn" class="hyjack">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT pit_bd_ficha_mrn.cod_mrn, 
		org_ficha_organizacion.n_documento, 
		org_ficha_organizacion.nombre, 
		pit_bd_ficha_mrn.sector
		FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_mrn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
		WHERE pit_bd_ficha_mrn.cod_estado_iniciativa<>000 AND
		pit_bd_ficha_mrn.cod_estado_iniciativa<>001 AND
		pit_bd_ficha_mrn.cod_estado_iniciativa<>003 AND
		org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
		ORDER BY org_ficha_organizacion.nombre ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($fila=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $fila['cod_mrn'];?>"><? echo $fila['nombre'] ." ".$fila['sector'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="twelve columns"><br/></div>
<div class="two columns">Monto destinado a premios (S/.)</div>
<div class="four columns"><input type="text" name="premio" class="required number seven"></div>
<div class="two columns">Monto destinado a otros (S/.)</div>
<div class="four columns"><input type="text" name="otro" class="required number seven"></div>


<div class="twelve columns"><h6>Actividades en las que se va a concursar</h6></div>

<div class="four columns"><h6>Actividad Nº 1</h6></div>
<div class="four columns"><h6>Actividad Nº 2</h6></div>
<div class="four columns"><h6>Actividad Nº 3</h6></div>


<div class="four columns">
	<select name="actividad_1" class="hyjack">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT * FROM sys_bd_actividad_mrn";
		$result=mysql_query($sql) or die (mysql_error());
		while($r1=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $r1['cod'];?>"><? echo $r1['descripcion'];?></option>
		<?
		}
		?>
	</select>
</div>

<div class="four columns">
	<select name="actividad_2" class="hyjack">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT * FROM sys_bd_actividad_mrn";
		$result=mysql_query($sql) or die (mysql_error());
		while($r2=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $r2['cod'];?>"><? echo $r2['descripcion'];?></option>
		<?
		}
		?>
	</select>	
</div>

<div class="four columns">
	<select name="actividad_3" class="hyjack">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT * FROM sys_bd_actividad_mrn";
		$result=mysql_query($sql) or die (mysql_error());
		while($r3=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $r3['cod'];?>"><? echo $r3['descripcion'];?></option>
		<?
		}
		?>
	</select>	
</div>

<div class="twelve columns"><br/></div>
<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<a href="cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button">Cancelar</a>
</div>
	
</form>
<?
}
?>
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
  <script type="text/javascript" src="../plugins/buscar/js/buscar-en-tabla.js"></script>
  
<!-- VALIDADOR DE FORMULARIOS -->
<script src="../plugins/validation/jquery.validate.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" media="screen" href="../plugins/validation/stylesheet.css" />
<script type="text/javascript" src="../plugins/validation/jquery.maskedinput.js"></script>
<script type="text/javascript" src="../plugins/validation/mktSignup.js"></script>  

<!-- Script de combo buscador -->
<!-- CARGAMOS EL JQUERY -->

<?
if ($modo<>participante)
{
?>
<script type="text/javascript" src="../plugins/jquery.js"></script>
<link href="../plugins/combo_buscador/hyjack.css" rel="stylesheet" type="text/css" />
<script src="../plugins/combo_buscador/hyjack_externo.js" type="text/javascript"></script>
<script src="../plugins/combo_buscador/configuracion.js" type="text/javascript"></script>
<?
}
?>


</body>
</html>
