<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);

$sql="SELECT * FROM pit_bd_ficha_pit WHERE cod_pit='$id'";
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
  <meta http-equiv="X-UA-Compatible" content="chrome=1" />

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
<dd  class="active"><a href="">Registrar Propuesta de  Plan de Inversion Territorial</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" class="custom" action="gestor_pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=UPDATE">
<div class="twelve columns">
	<button type="submit" class="success button">Guardar cambios</button>
	<a href="pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="primary button">Cancelar operacion</a>
</div>
<div class="twelve columns"><hr/></div>

<div class="twelve columns"><h6>I.- Propuesta</h6></div>	
	<div class="two columns">Territorio</div>
	<div class="ten columns">
	
<!-- Pongo aca los codigos -->	
	<input type="hidden" name="codigo" value="<? echo $row['cod_pit'];?>">
	<input type="hidden" name="mancomunidad" value="<? echo $row['mancomunidad'];?>">
<!-- Finalizo Pongo aca los codigos -->		
	
	
		<select name="territorio" class="selected">
			<option value="" selected="selected">Seleccionar</option>
			<?
			$sql="SELECT org_ficha_taz.cod_tipo_doc, 
			org_ficha_taz.n_documento, 
			org_ficha_taz.nombre
			FROM org_ficha_taz
			WHERE org_ficha_taz.cod_dependencia='".$row1['cod_dependencia']."'
			ORDER BY org_ficha_taz.nombre ASC";
			$result=mysql_query($sql) or die (mysql_error());
			while($r1=mysql_fetch_array($result))
			{
			?>
			<option value="<? echo $r1['cod_tipo_doc'].",".$r1['n_documento'];?>" <? if ($r1['cod_tipo_doc']==$row['cod_tipo_doc_taz'] and $r1['n_documento']==$row['n_documento_taz']) echo "selected";?>><? echo $r1['nombre'];?></option>
			<?
			}
			?>
		</select>
	</div>
	
	<div class="four columns">Presentara mapa cultural para concursar en CLAR?</div>
	<div class="one columns">Si <input type="radio" name="mapa" value="1" class="required" <? if ($row['concurso']==1) echo "checked";?>></div>
	<div class="one columns">No <input type="radio" name="mapa" value="0" class="required" <? if ($row['concurso']==0) echo "checked";?>></div>
	<div class="two columns">Fecha de presentacion de esta propuesta</div>
	<div class="four columns"><input type="date" name="f_presentacion" placeholder="aaaa-mm-dd" maxlength="10" class="date required five" value="<? echo $row['f_presentacion'];?>"></div>
<?
if ($row['mancomunidad']==0)
{
?>	
	<div class="twelve columns"><h6>Informacion financiera</h6></div>
	
	<div class="two columns">Nº cuenta bancaria</div>
	<div class="two columns"><input type="text" name="n_cuenta" class="eleven" value="<? echo $row['n_cuenta'];?>"></div>
	<div class="two columns">Entidad financiera</div>
	<div class="six columns">
		<select name="ifi" class="required">
			<option value="" selected="selected">Seleccionar</option>
<?
$sql="SELECT sys_bd_ifi.cod_ifi, 
	sys_bd_ifi.descripcion
FROM sys_bd_ifi
ORDER BY sys_bd_ifi.descripcion ASC";
$result=mysql_query($sql) or die (mysql_error());
while($r2=mysql_fetch_array($result))
{
?>
<option value="<? echo $r2['cod_ifi'];?>" <? if ($row['cod_ifi']==$r2['cod_ifi']) echo "selected";?>><? echo $r2['descripcion'];?></option>
<?
}
?>
		</select>
	</div>
<hr/>
<div class="twelve columns"><h6>Propuesta de confinanciamiento y contrapartidas</h6></div>
<table class="responsive">
	<tbody>
		<tr>
			<th>Nº animadores territoriales</th>
			<th>Incentivo por animador territorial</th>
			<th>Nº meses a trabajar</th>
			<th><span class="has-tip tip-top noradius" data-width="210" title="Esta información es opcional">Aporte de la Organizacion</span></th>
			<th><span class="has-tip tip-top noradius" data-width="210" title="Esta información es opcional">Nº voucher de deposito</span></th>
			<th><span class="has-tip tip-top noradius" data-width="210" title="Esta información es opcional">Monto depositado</span></th>
		</tr>
		
		<tr>
			<td><input type="text" name="n_animador" class="digits" value="<? echo $row['n_animador'];?>"></td>
			<td><input type="text" name="monto_animador" value="210"></td>
			<td><input type="text" name="n_mes" value="13"></td>
			<td><input type="text" name="aporte_org" value="<? echo $row['aporte_org'];?>"></td>
			<td><input type="text" name="n_voucher" value="<? echo $row['n_voucher'];?>"></td>
			<td><input type="text" name="deposito" value="<? echo $row['monto_organizacion'];?>"></td>
		</tr>
	</tbody>
</table>

<div class="twelve columns"><h6>Propuesta de animadores territoriales</h6></div>
<table class="responsive">
	<tbody>
		<tr>
			<th>DNI</th>
			<th>Nombres</th>
			<th>A. paterno</th>
			<th>A. materno</th>
			<th>Fecha nac.</th>
			<th>Sexo</th>
			<th>Nivel Educ.</th>
		</tr>
		
<?
$sql="SELECT * FROM pit_bd_animador WHERE cod_pit='$id'";
$result=mysql_query($sql) or die (mysql_error());
while($fila=mysql_fetch_array($result))
{
$cod=$fila['n_documento'];
?>		
		<tr>
		<td><input name="dni[<? echo $cod;?>]" type="text" maxlength="8" value="<? echo $fila['n_documento'];?>"></td>
		<td><input name="nombre[<? echo $cod;?>]" type="text" value="<? echo $fila['nombres'];?>"></td>
		<td><input type="text" name="paterno[<? echo $cod;?>]" value="<? echo $fila['paterno'];?>"></td>
		<td><input type="text" name="materno[<? echo $cod;?>]" value="<? echo $fila['materno'];?>"></td>
		<td><input type="date" name="fecha[<? echo $cod;?>]" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $fila['f_nacimiento'];?>"></td>
		<td><select name="sexo[<? echo $cod;?>]"><option value="" selected="selected">Selecc.</option><option value="1" <? if ($fila['sexo']==1) echo "selected";?>>M</option><option value="0"  <? if ($fila['sexo']==0) echo "selected";?>>F</option></select></td>
		<td><select name="nivel[<? echo $cod;?>]"><option value="" selected="selected">Selecc.</option><option value="001" <? if ($fila['cod_grado_instruccion']==001) echo "selected";?>>Primaria</option><option value="002" <? if ($fila['cod_grado_instruccion']==002) echo "selected";?>>Secundaria</option><option value="003" <? if ($fila['cod_grado_instruccion']==003) echo "selected";?>>Superior</option><option value="004" <? if ($fila['cod_grado_instruccion']==004) echo "selected";?>>Sin Instruccion</option></select></td>			
		</tr>
<?
}
?>		
<tr><th colspan="7">Podemos añadir mas registros desde aqui:</th></tr>				
<?
for ($i = 1; $i <= 3; $i++) 
{
?>		
		<tr>
		<td><input name="dnis[]" type="text" maxlength="8"></td>
		<td><input name="nombres[]" type="text"></td>
		<td><input type="text" name="paternos[]"></td>
		<td><input type="text" name="maternos[]"></td>
		<td><input type="date" name="fechas[]" placeholder="aaaa-mm-dd" maxlength="10"></td>
		<td><select name="sexos[]"><option value="" selected="selected">Selecc.</option><option value="1">M</option><option value="0">F</option></select></td>
		<td><select name="nivels[]"><option value="" selected="selected">Selecc.</option><option value="001">Primaria</option><option value="002">Secundaria</option><option value="003">Superior</option><option value="004">Sin Instruccion</option></select></td>			
		</tr>
<?
}
?>

		
	</tbody>
</table>
<?
}
?>
<div class="twelve columns"><h6>II.- Información general del Plan de inversión Territorial</h6></div>
<hr/>
<div class="six columns">a.- Principales cultivos (Por orden de importancia)</div>
<div class="six columns">b.- Principales crianzas de ganado</div>
<div class="six columns">
<table class="responsive">
	<tbody>
		<tr>
			<th>Tipo de cultivo</th>
			<th>Descripcion</th>
		</tr>
<?
$sql="SELECT * FROM pit_tipo_cultivo WHERE cod_pit='$id'";
$result=mysql_query($sql) or die (mysql_error());
while($fila1=mysql_fetch_array($result))
{
$cod=$fila1['cod'];
?>		
		<tr>
			<td>
				<select name="tipo_cultivo[<? echo $cod;?>]">
					<option value="" selected="selected">Seleccionar</option>
					<option value="1" <? if ($fila1['tipo_cultivo']==1) echo "selected";?>>Cultivos agricolas</option>
					<option value="2" <? if ($fila1['tipo_cultivo']==2) echo "selected";?>>Pastos y forrajes</option>
					<option value="3" <? if ($fila1['tipo_cultivo']==3) echo "selected";?>>Frutales</option>
					<option value="4" <? if ($fila1['tipo_cultivo']==4) echo "selected";?>>Plantaciones forestales</option>
				</select>
			</td>
			<td><input name="describe_cultivo[<? echo $cod;?>]" type="text" value="<? echo $fila1['descripcion'];?>"></td>
		</tr>		
<?
}
?>
<tr><th colspan="2">Añadir registros:</th></tr>		
<?
for ($i = 1; $i <= 5; $i++) 
{
?>			
		<tr>
			<td>
				<select name="tipo_cultivos[]">
					<option value="" selected="selected">Seleccionar</option>
					<option value="1">Cultivos agricolas</option>
					<option value="2">Pastos y forrajes</option>
					<option value="3">Frutales</option>
					<option value="4">Plantaciones forestales</option>
				</select>
			</td>
			<td><input name="describe_cultivos[]" type="text"></td>
		</tr>
<?
}
?>		
	</tbody>
</table>
</div>
<div class="six columns">
<table class="responsive">
	<tbody>
		<tr>
			<th>Tipo de ganado</th>
			<th>Descripcion</th>
		</tr>	
<?
$sql="SELECT * FROM pit_ganado_pit WHERE cod_pit='$id'";
$result=mysql_query($sql) or die (mysql_error());
while($fila2=mysql_fetch_array($result))
{
$cod=$fila2['cod'];
?>		
		<tr>
			<td>
				<select name="tipo_ganado[<? echo $cod;?>]">
					<option value="" selected="selected">Seleccionar</option>
					<option value="1" <? if ($fila2['tipo']==1) echo "selected";?> >Ganado mayor</option>
					<option value="2" <? if ($fila2['tipo']==2) echo "selected";?>>Ganado menor</option>
				</select>
			</td>
			<td><input name="describe_ganado[<? echo $cod;?>]" type="text" value="<? echo $fila2['descripcion'];?>"></td>
		</tr>
<?
}
?>
<tr><th colspan="2">Añadir registros:</th></tr>		
<?
for ($i = 1; $i <= 5; $i++) 
{
?>			
		<tr>
			<td>
				<select name="tipo_ganados[]">
					<option value="" selected="selected">Seleccionar</option>
					<option value="1" >Ganado mayor</option>
					<option value="2">Ganado menor</option>
				</select>
			</td>
			<td><input name="describe_ganados[]" type="text"></td>
		</tr>
<?
}
?>		
	</tbody>
</table>	
</div>
<hr/>
<div class="six columns">c.- Areas del territorio</div>
<div class="six columns">d.- Tipo de actividades de transformacion y servicio</div>
<?
$sql="SELECT * FROM pit_area_pit WHERE cod_pit='$id'";
$result=mysql_query($sql) or die (mysql_error());
$fila3=mysql_fetch_array($result);
?>

<div class="six columns">
	<table class="responsive">
		<tbody>
			<tr>
				<th>Tipologia del área <input type="hidden" name="cod_area" value="<? echo $fila3['cod'];?>"></th>
				<th>Nº Has</th>
			</tr>
			<tr>
				<td>Area Total</td>
				<td><input type="text" name="area1" value="<? echo $fila3['a1'];?>"></td>
			</tr>
			<tr>
				<td>Cultivos agricolas con riego</td>
				<td><input type="text" name="area2" value="<? echo $fila3['a2'];?>"></td>
			</tr>
			<tr>
				<td>Pastos con riego</td>
				<td><input type="text" name="area3" value="<? echo $fila3['a3'];?>"></td>
			</tr>
			<tr>
				<td>Areas forestales con riego</td>
				<td><input type="text" name="area4" value="<? echo $fila3['a4'];?>"></td>
			</tr>
			<tr>
				<td>Cultivos agricolas en secano</td>
				<td><input type="text" name="area5" value="<? echo $fila3['a5'];?>"></td>
			</tr>
			<tr>
				<td>Pastos en secano</td>
				<td><input type="text" name="area6" value="<? echo $fila3['a6'];?>"></td>
			</tr>
			<tr>
				<td>Areas forestales en secano</td>
				<td><input type="text" name="area7" value="<? echo $fila3['a7'];?>"></td>
			</tr>
			<tr>
				<td>Areas de pastos naturales</td>
				<td><input type="text" name="area8" value="<? echo $fila3['a8'];?>"></td>
			</tr>
			<tr>
				<td>Otras areas</td>
				<td><input type="text" name="area9" value="<? echo $fila3['a9'];?>"></td>
			</tr>																								
		</tbody>
	</table>
</div>

<div class="six columns">
	<table class="responsive">
		<tbody>
			<tr>
				<th>Tipo de actividad</th>
				<th>Descripcion</th>
			</tr>
<?
$sql="SELECT * FROM pit_actividad_pit WHERE cod_pit='$id'";
$result=mysql_query($sql) or die (mysql_error());
while($fila4=mysql_fetch_array($result))
{
	$cod=$fila4['cod'];
?>			
			<tr>
				<td><input type="text" name="tipo_actividad[<? echo $cod;?>]" value="<? echo $fila4['tipo'];?>"></td>
				<td><input type="text" name="describe_actividad[<? echo $cod;?>]" value="<? echo $fila4['descripcion'];?>"></td>
			</tr>			
<?
}
?>
<tr><th colspan="2">Añadir registros:</th></tr>					
<?
for ($i = 1; $i <= 5; $i++) 
{
?>			
			<tr>
				<td><input type="text" name="tipo_actividads[]"></td>
				<td><input type="text" name="describe_actividads[]"></td>
			</tr>
<?
}
?>			
		</tbody>
	</table>
</div>
<hr/>
<div class="six columns">e.- Principales festividades en el territorio</div>
<div class="six columns">f.- Patrimonio y manisfestaciones culturales</div>

<div class="six columns">
	<table class="responsive">
		<tbody>
			<tr>
				<th>Dia</th>
				<th>Mes</th>
				<th>Festividad</th>
			</tr>
			
		<?
		$sql="SELECT * FROM pit_festividad_pit WHERE cod_pit='$id'";
		$result=mysql_query($sql) or die (mysql_error());
		while($fila5=mysql_fetch_array($result))
		{
			$cod=$fila5['cod'];
		
		?>		
				<tr>
				<td><input type="text" name="festday[<? echo $cod;?>]" value="<? echo $fila5['dia'];?>"></td>
				<td>
					<select name="festmes[<? echo $cod;?>]">
				    <option value="" selected="selected">Seleccionar</option>
				    <option value="ENERO" <? if ($fila5['mes']==ENERO) echo "selected";?>>ENERO</option>
				    <option value="FEBRERO" <? if ($fila5['mes']==FEBRERO) echo "selected";?>>FEBRERO</option>
				    <option value="MARZO"  <? if ($fila5['mes']==MARZO) echo "selected";?>>MARZO</option>
				    <option value="ABRIL" <? if ($fila5['mes']==ABRIL) echo "selected";?>>ABRIL</option>
				    <option value="MAYO"  <? if ($fila5['mes']==MAYO) echo "selected";?>>MAYO</option>
				    <option value="JUNIO"  <? if ($fila5['mes']==JUNIO) echo "selected";?>>JUNIO</option>
				    <option value="JULIO" <? if ($fila5['mes']==JULIO) echo "selected";?>>JULIO</option>
				    <option value="AGOSTO" <? if ($fila5['mes']==AGOSTO) echo "selected";?>>AGOSTO</option>
				    <option value="SEPTIEMBRE" <? if ($fila5['mes']==SEPTIEMBRE) echo "selected";?>>SEPTIEMBRE</option>
				    <option value="OCTUBRE" <? if ($fila5['mes']==OCTUBRE) echo "selected";?>>OCTUBRE</option>
				    <option value="NOVIEMBRE" <? if ($fila5['mes']==NOVIEMBRE) echo "selected";?>>NOVIEMBRE</option>
				    <option value="DICIEMBRE" <? if ($fila5['mes']==DICIEMBRE) echo "selected";?>>DICIEMBRE</option>
			    </select>
				</td>
				<td><input type="text" name="festdescribe[<? echo $cod;?>]" value="<? echo $fila5['descripcion'];?>"></td>
			</tr>	
			<?
			}
			?>
			
<tr><th colspan="3">Añadir registros:</th></tr>				
			
			
<?
for ($i = 1; $i <= 3; $i++) 
{
?>				
			<tr>
				<td><input type="text" name="festdays[]"></td>
				<td>
					<select name="festmess[]">
				    <option value="" selected="selected">Seleccionar</option>
				    <option value="ENERO">ENERO</option>
				    <option value="FEBRERO">FEBRERO</option>
				    <option value="MARZO">MARZO</option>
				    <option value="ABRIL">ABRIL</option>
				    <option value="MAYO">MAYO</option>
				    <option value="JUNIO">JUNIO</option>
				    <option value="JULIO">JULIO</option>
				    <option value="AGOSTO">AGOSTO</option>
				    <option value="SEPTIEMBRE">SEPTIEMBRE</option>
				    <option value="OCTUBRE">OCTUBRE</option>
				    <option value="NOVIEMBRE">NOVIEMBRE</option>
				    <option value="DICIEMBRE">DICIEMBRE</option>
			    </select>
				</td>
				<td><input type="text" name="festdescribes[]"></td>
			</tr>
<?
}
?>			
		</tbody>
	</table>
</div>
<div class="six columns">
	<table class="responsive">
		<tbody>
			<tr>
				<th>Tipo</th>
				<th>Descripcion</th>
			</tr>
			
<?
$sql="SELECT * FROM pit_patrimonio_pit WHERE cod_pit='$id'";
$result=mysql_query($sql) or die (mysql_error());
while($fila6=mysql_fetch_array($result))
{
	$cod=$fila6['cod'];
?>			
			<tr>
				<td><select name="tipo_cult[<? echo $cod;?>]"><option value="" selected="selected">Seleccionar</option><option value="1" <? if ($fila6['cod_tipo']==1) echo "selected";?>>Patrimonio</option><option value="2" <? if ($fila6['cod_tipo']==2) echo "selected";?>>Manifestaciones culturales</option></select></td>
				<td><input type="text" name="descrip_cult[<? echo $cod;?>]" value="<? echo $fila6['descripcion'];?>"></td>
			</tr>
<?
}
?>
<tr><th colspan="2">Añadir registros:</th></tr>				
			
<?
for ($i = 1; $i <= 2; $i++) 
{
?>			
			<tr>
				<td><select name="tipo_cults[]"><option value="" selected="selected">Seleccionar</option><option value="1">Patrimonio</option><option value="2">Manifestaciones culturales</option></select></td>
				<td><input type="text" name="descrip_cults[]"></td>
			</tr>
<?
}
?>			
		</tbody>
	</table>
</div>	
<hr/>

<div class="twelve columns">g.- Recursos hidricos</div>
<div class="six columns">
	<table class="responsive">
		<tbody>
			<tr>
				<th>Principales fuentes de agua</th>
				<th>Uso actual</th>
				<th>Limitaciones</th>
			</tr>
<?
$sql="SELECT * FROM pit_agua_pit WHERE cod_pit='$id'";
$result=mysql_query($sql) or die (mysql_error);
while($fila7=mysql_fetch_array($result))
{
$cod=$fila7['cod'];
?>			
			<tr>
				<td><input type="text" name="fuente[<? echo $cod;?>]" value="<? echo $fila7['descripcion'];?>"></td>
				<td><input type="text" name="uso[<? echo $cod;?>]" value="<? echo $fila7['uso'];?>"></td>
				<td><input type="text" name="limite[<? echo $cod;?>]" value="<? echo $fila7['limitaciones'];?>"></td>
			</tr>
<?
}
?>			
			
			
			
<?
for ($i = 1; $i <= 3; $i++) 
{
?>				
			<tr>
				<td><input type="text" name="fuentes[]"></td>
				<td><input type="text" name="usos[]"></td>
				<td><input type="text" name="limites[]"></td>
			</tr>
<?
}
?>			
		</tbody>
	</table>
</div>
<div class="six columns"><br/></div>


	
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
