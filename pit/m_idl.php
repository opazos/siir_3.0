<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);

$sql="SELECT * FROM pit_bd_ficha_idl WHERE cod_ficha_idl='$id'";
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
<dd  class="active"><a href="">Registro de Inversiones de Desarrollo Local</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" class="custom" method="post" action="gestor_idl.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=UPDATE" onsubmit="return checkSubmit();">
<div class="twelve columns">Seleccionar Organizacion que solicita IDL</div>
<div class="twelve columns">
<input type="hidden" name="codigo" value="<? echo $row['cod_ficha_idl'];?>">
	<select name="org">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT org_ficha_organizacion.cod_tipo_doc, 
		org_ficha_organizacion.n_documento, 
		org_ficha_organizacion.nombre
		FROM org_ficha_organizacion
		WHERE org_ficha_organizacion.cod_dependencia='".$row1['cod_dependencia']."'
		ORDER BY org_ficha_organizacion.nombre ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($f1=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f1['cod_tipo_doc'].",".$f1['n_documento'];?>" <? if ($f1['cod_tipo_doc']==$row['cod_tipo_doc_org'] and $f1['n_documento']==$row['n_documento_org']) echo "selected";?>><? echo $f1['nombre'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="two columns">Denominacion</div>
<div class="ten columns"><input type="text" name="denominacion" class="required ten" value="<? echo $row['denominacion'];?>"></div>
<div class="two columns">Inicio de la obra</div>
<div class="four columns"><input type="date" name="f_inicio" class="six required date" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $row['f_inicio'];?>"></div>
<div class="two columns">Fin de la obra</div>
<div class="four columns"><input type="date" name="f_termino" class="six required date" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $row['f_termino'];?>"></div>
<div class="twelve columns">¿Que se espera lograr con esta Obra?</div>
<div class="twelve columns"><textarea name="objetivo" rows="5"><? echo $row['objetivo'];?></textarea></div>
<div class="two columns">Tipo de IDL</div>
<div class="four columns">
	<select name="tipo">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT * FROM sys_bd_tipo_idl";
		$result=mysql_query($sql) or die (mysql_error());
		while($f2=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f2['cod_tipo_idl'];?>" <? if ($f2['cod_tipo_idl']==$row['cod_tipo_idl']) echo "selected";?>><? echo $f2['descripcion'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="two columns">Nº de familias a beneficiarse con la IDL (Aprox.)</div>
<div class="four columns"><input type="text" name="n_familia" class="required digits five" value="<? echo $row['familias'];?>"></div>
<div class="twelve columns"><h6>II.- Informacion presupuestal</h6></div>
<div class="two columns">Nº de cuenta</div>
<div class="four columns"><input type="text" name="n_cuenta" class="required nine" value="<? echo $row['n_cuenta'];?>"></div>
<div class="two columns">Banco</div>
<div class="four columns">
	<select name="ifi">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT * FROM sys_bd_ifi ORDER BY descripcion ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($f3=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f3['cod_ifi'];?>" <? if ($f3['cod_ifi']==$row['cod_ifi']) echo "selected";?>><? echo $f3['descripcion'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="twelve columns"></div>
<div class="two columns">Aporte Sierra Sur II (S/.)</div>
<div class="ten columns"><input type="text" name="aporte_pdss" class="required number three" value="<? echo $row['aporte_pdss'];?>"></div>
<div class="two columns">Aporte Organizacion (S/.)</div>
<div class="ten columns"><input type="text" name="aporte_org" class="required number three" value="<? echo $row['aporte_org'];?>"></div>
<div class="two columns">Otros Aportes (S/.)</div>
<div class="ten columns"><input type="text" name="aporte_otro" class="required number three" value="<? echo $row['aporte_otro'];?>"></div>
<div class="twelve columns"><h6>III.- Metas Fisicas esperadas</h6></div>
<table>
<thead>
	<tr>
		<th>Nº</th>
		<th class="nine">Descripcion de la Meta Física</th>
		<th>Unidad</th>
		<th>Meta Programada</th>
	</tr>
</thead>	

<?
$sql="SELECT * FROM idl_meta_fisica WHERE cod_ficha_idl='$id'";
$result=mysql_query($sql) or die (mysql_error());
while($f4=mysql_fetch_array($result))
{
$cad=$f4['cod_meta'];
?>
	<tr>
		<td>-</td>
		<td><input type="text" name="actividads[<? echo $cad;?>]" value="<? echo $f4['descripcion'];?>"></td>
		<td><input type="text" name="unidads[<? echo $cad;?>]" value="<? echo $f4['unidad'];?>"></td>
		<td><input type="text" name="metas[<? echo $cad;?>]" class="number" value="<? echo $f4['meta'];?>"></td>
	</tr>
<?
}
?>



<?
$na=0;
for($i=1;$i<=15;$i++)
{
	$na++
?>	
	<tr>
		<td><? echo $na;?></td>
		<td><input type="text" name="actividad[]"></td>
		<td><input type="text" name="unidad[]"></td>
		<td><input type="text" name="meta[]" class="number"></td>
	</tr>
<?
}
?>	
</table>
<div class="twelve columns"><h6>IV.- Cronograma de ejecución de actividades para el desarrollo de la IDL</h6></div>
<table>
	<thead>
		<tr>
			<th>Nº</th>
			<th class="five">Actividad Programada</th>
			<th>M 1</th>
			<th>M 2</th>
			<th>M 3</th>
			<th>M 4</th>
			<th>M 5</th>
			<th>M 6</th>
			<th>M 7</th>
			<th>M 8</th>
			<th>M 9</th>
			<th>M 10</th>
			<th>M 11</th>
			<th>M 12</th>
		</tr>
	</thead>
	
<?
$sql="SELECT * FROM idl_actividad_idl WHERE cod_ficha_idl='$id'";
$result=mysql_query($sql) or die (mysql_error());
while($f5=mysql_fetch_array($result))
{
$ced=$f5['cod_actividad'];
?>
	<tr>
		<td>-</td>
		<td><input type="text" name="actividad_progs[<? echo $ced;?>]" value="<? echo $f5['descripcion'];?>"></td>
		<td><input type="checkbox" name="enes[<? echo $ced;?>]" value="1" <? if($f5['m1']==1) echo "checked";?>></td>
		<td><input type="checkbox" name="febs[<? echo $ced;?>]" value="1" <? if($f5['m2']==1) echo "checked";?>></td>
		<td><input type="checkbox" name="mars[<? echo $ced;?>]" value="1" <? if($f5['m3']==1) echo "checked";?>></td>
		<td><input type="checkbox" name="abrs[<? echo $ced;?>]" value="1" <? if($f5['m4']==1) echo "checked";?>></td>
		<td><input type="checkbox" name="mays[<? echo $ced;?>]" value="1" <? if($f5['m5']==1) echo "checked";?>></td>
		<td><input type="checkbox" name="juns[<? echo $ced;?>]" value="1" <? if($f5['m6']==1) echo "checked";?>></td>
		<td><input type="checkbox" name="juls[<? echo $ced;?>]" value="1" <? if($f5['m7']==1) echo "checked";?>></td>
		<td><input type="checkbox" name="agos[<? echo $ced;?>]" value="1" <? if($f5['m8']==1) echo "checked";?>></td>
		<td><input type="checkbox" name="seps[<? echo $ced;?>]" value="1" <? if($f5['m9']==1) echo "checked";?>></td>
		<td><input type="checkbox" name="octs[<? echo $ced;?>]" value="1" <? if($f5['m10']==1) echo "checked";?>></td>
		<td><input type="checkbox" name="novs[<? echo $ced;?>]" value="1" <? if($f5['m11']==1) echo "checked";?>></td>
		<td><input type="checkbox" name="dics[<? echo $ced;?>]" value="1" <? if($f5['m12']==1) echo "checked";?>></td>
	</tr>	
<?
}
?>	
	
<?
$nb=0;
for($i=1;$i<=15;$i++)
{
	$nb++
?>	
	<tr>
		<td><? echo $nb;?></td>
		<td><input type="text" name="actividad_prog[]"></td>
		<td><input type="checkbox" name="ene[]" value="1"></td>
		<td><input type="checkbox" name="feb[]" value="1"></td>
		<td><input type="checkbox" name="mar[]" value="1"></td>
		<td><input type="checkbox" name="abr[]" value="1"></td>
		<td><input type="checkbox" name="may[]" value="1"></td>
		<td><input type="checkbox" name="jun[]" value="1"></td>
		<td><input type="checkbox" name="jul[]" value="1"></td>
		<td><input type="checkbox" name="ago[]" value="1"></td>
		<td><input type="checkbox" name="sep[]" value="1"></td>
		<td><input type="checkbox" name="oct[]" value="1"></td>
		<td><input type="checkbox" name="nov[]" value="1"></td>
		<td><input type="checkbox" name="dic[]" value="1"></td>
	</tr>
<?
}
?>	
</table>
<div class="twelve columns"><h6>V.- Presupuesto Programado para la ejecucion de la IDL</h6></div>
<table>
	<thead>
		<tr>
			<th>Nº</th>
			<th class="seven">Concepto de gasto</th>
			<th>Unidad</th>
			<th>Precio Unitario</th>
			<th>Cantidad</th>
		</tr>
	</thead>
<?
$sql="SELECT * FROM idl_detalle_financiamiento WHERE cod_ficha_idl='$id'";
$result=mysql_query($sql) or die (mysql_error());
while($f6=mysql_fetch_array($result))
{
	$cid=$f6['cod_df'];
?>	
	<tr>
		<td>-</td>
		<td><input type="text" name="conceptos[<? echo $cid;?>]" value="<? echo $f6['descripcion'];?>"></td>
		<td><input type="text" name="unidad_gastos[<? echo $cid;?>]" value="<? echo $f6['unidad'];?>"></td>
		<td><input type="text" name="precios[<? echo $cid;?>]" value="<? echo $f6['costo_unitario_1'];?>"></td>
		<td><input type="text" name="cantidad_gastos[<? echo $cid;?>]" value="<? echo $f6['cantidad_1'];?>"></td>
	</tr>	
<?
}
?>	
	
<?
$nc=0;
for($i=1;$i<=20;$i++)
{
	$nc++
?>	
	<tr>
		<td><? echo $nc;?></td>
		<td><input type="text" name="concepto[]"></td>
		<td><input type="text" name="unidad_gasto[]"></td>
		<td><input type="text" name="precio[]"></td>
		<td><input type="text" name="cantidad_gasto[]"></td>
	</tr>
<?
}
?>	
</table>



<div class="twelve columns"><br/></div>
<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambio</button>
	<a href="idl.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="secondary button">Cancelar</a>
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
