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
<dd  class="active"><a href="">
<?
if ($modo==familia)
{
 echo "2 de 3.- Actualizacion de Familias y participantes";
}
elseif($modo==directiva)
{
echo "3 de 3.- Actualizacion de Junta directiva";
}
else
{
echo "1 de 3.- Informacion del PDN";
}
?>
</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<?
if ($modo==familia)
{
$sql="SELECT pit_bd_pdn_sd.hc_soc, 
	pit_bd_pdn_sd.just_soc, 
	pit_bd_ficha_pdn.cod_tipo_doc_org, 
	pit_bd_ficha_pdn.n_documento_org
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_pdn_sd ON pit_bd_ficha_pdn.cod_pdn = pit_bd_pdn_sd.cod_pdn
WHERE pit_bd_pdn_sd.cod_tipo=1 AND
pit_bd_pdn_sd.cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);
?>
<form name="form5" class="custom" method="post" action="gestor_pdn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&action=ADD_FAM" onsubmit="return checkSubmit();">
<div class="two columns">Hubo cambios en las familias?</div>
<div class="two columns">
	<select name="cambio_fam">
		<option value="" selected="selected">Seleccionar</option>
		<option value="1" <? if ($r1['hc_soc']==1) echo "selected";?>>Si</option>
		<option value="0" <? if ($r1['hc_soc']==0) echo "selected";?>>No</option>	
	</select>
</div>
<div class="two columns">Indicar el motivo de los cambios</div>
<div class="six columns">
	<textarea name="motivo_fam"><? echo $r1['just_soc'];?></textarea>
</div>
<div class="twelve columns"><h6>II.-  Añadir nuevos participantes (<a  href="../m_organizacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&tipo=<? echo $r1['cod_tipo_doc_org'];?>&numero=<? echo $r1['n_documento_org'];?>&modo=familia" target="_blank">Click aqui para Registrar Nuevas Familias</a>)</h6></div>
<table>
	<tr>
		<th>Nº</th>
		<th>DNI</th>
		<th class="ten">Nombres y apellidos completos</th>
		<th>Participa</th>
	</tr>
<?
$num=0;
$sql="SELECT org_ficha_usuario.n_documento AS dni, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno, 
	pit_bd_user_iniciativa.n_documento, 
	pit_bd_user_iniciativa.momento, 
	pit_bd_user_iniciativa.estado
	FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
	 LEFT OUTER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = org_ficha_usuario.n_documento AND pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_pdn.cod_pdn
WHERE pit_bd_ficha_pdn.cod_pdn='$cod'
ORDER BY pit_bd_user_iniciativa.estado DESC, org_ficha_usuario.nombre ASC";
$result=mysql_query($sql) or die (mysql_error());
while($f1=mysql_fetch_array($result))
{
	$num++
?>	
	<tr>
		<td><? echo $num;?></td>
		<td><? echo $f1['dni'];?></td>
		<td><? echo $f1['nombre']." ".$f1['paterno']." ".$f1['materno'];?></td>
		<td><input type="checkbox" name="campos[]" value="<? echo $f1['dni'];?>" <? if ($f1['n_documento']<>NULL) echo "checked";?>></td>
	</tr>
<?
}
?>	
</table>

<div class="twelve columns"><h6>III.- Indicar que participantes siguen vigentes (Desmarcar los retirados)</h6></div>
<table>
	<tr>
		<th>Nº</th>
		<th>DNI</th>
		<th class="ten">Nombres y apellidos completos</th>
		<th>Vigente</th>
		<th>Retirado</th>
	</tr>
<?
$nu=0;
$sql="SELECT org_ficha_usuario.n_documento AS dni, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno, 
	pit_bd_user_iniciativa.n_documento, 
	pit_bd_user_iniciativa.momento, 
	pit_bd_user_iniciativa.estado
	FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = org_ficha_usuario.n_documento AND pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_pdn.cod_pdn
WHERE pit_bd_ficha_pdn.cod_pdn='$cod'
ORDER BY pit_bd_user_iniciativa.estado DESC, org_ficha_usuario.nombre ASC";
$result=mysql_query($sql) or die (mysql_error());
while($f2=mysql_fetch_array($result))
{
	$cad=$f2['dni'];
	$nu++
?>		
	<tr>
		<td><? echo $nu;?></td>
		<td><? echo $f2['dni'];?></td>
		<td><? echo $f2['nombre']." ".$f2['paterno']." ".$f2['materno'];?></td>
		<td><input type="radio" name="estado[<? echo $cad;?>]" value="1" <? if ($f2['estado']==1) echo "checked";?>></td>
		<td><input type="radio" name="estado[<? echo $cad;?>]" value="0" <? if ($f2['estado']==0) echo "checked";?>></td>
	</tr>
<?
}
?>	
</table>
<div class="twelve columns"><br/></div>

<div class="twelve columns">
	<button type="submit" class="success button">Guardar cambios</button>
	<a href="n_pdn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&modo=directiva" class="primary button">Siguiente >></a>
	<a href="pdn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button">Cancelar</a>
</div>

</form>

<?
}
elseif($modo==directiva)
{
$sql="SELECT * FROM pit_bd_pdn_sd WHERE cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);
?>
<form name="form5" class="custom" method="post" action="gestor_pdn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&action=ADD_DIR" onsubmit="return checkSubmit();">
<div class="two columns">Hubo cambios en la directiva?</div>
<div class="two columns">
	<select name="hc_dir">
		<option value="" selected="selected">Seleccionar</option>
		<option value="1" <? if ($r2['hc_dir']==1) echo "selected";?>>Si</option>
		<option value="0"<? if ($r2['hc_dir']==0) echo "selected";?>>No</option>
	</select>
</div>
<div class="two columns">Indicar el motivo de los cambios</div>
<div class="six columns">
	<textarea name="mc_dir"><? echo $r2['just_dir'];?></textarea>
</div>
<div class="twelve columns"><h6>II.- Registro de nuevos directivos</h6></div>

<table>
	<tr>
		<th>Nº</th>
		<th class="six">Nombres y apellidos completos</th>
		<th class="five">Cargo</th>
		<th>Vigencia hasta</th>
	</tr>
<?
for($i=1;$i<=5;$i++)
{
?>	
	<tr>
		<td><? echo $i;?></td>
		<td>
			<select name="nombre[]">
				<option value="" selected="selected">Seleccionar</option>
				<?
				$sql="SELECT org_ficha_usuario.n_documento, 
				org_ficha_usuario.nombre, 
				org_ficha_usuario.paterno, 
				org_ficha_usuario.materno
				FROM org_ficha_usuario INNER JOIN pit_bd_ficha_pdn ON org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
				WHERE pit_bd_ficha_pdn.cod_pdn='$cod'
				ORDER BY org_ficha_usuario.nombre ASC";
				$result=mysql_query($sql) or die (mysql_error());
				while($f1=mysql_fetch_array($result))
				{
				?>
				<option value="<? echo $f1['n_documento'];?>"><? echo $f1['nombre']." ".$f1['paterno']." ".$f1['materno'];?></option>
				<?
				}
				?>
			</select>
		</td>
		<td>
			<select name="cargo[]">
				<option value="" selected="selected">Seleccionar</option>
				<?
				$sql="SELECT * FROM sys_bd_cargo_directivo ORDER BY cod_cargo";
				$result=mysql_query($sql) or die (mysql_error());
				while($f2=mysql_fetch_array($result))
				{
				?>
				<option value="<? echo $f2['cod_cargo'];?>"><? echo $f2['descripcion'];?></option>
				<?
				}
				?>
			</select>
		</td>
		<td><input type="date" name="f_vigencia[]" class="date" placeholder="aaaa-mm-dd" maxlength="10"></td>
	</tr>
<?
}
?>	
</table>
<div class="twelve columns"><h6>III.- Directiva vigente</h6></div>
<table>
	<tr>
		<th>Nº</th>
		<th>DNI</th>
		<th class="five">Nombres y apellidos completos</th>
		<th class="four">Cargo</th>
		<th>Vigencia hasta</th>
		<th>Vigente</th>
		<th>No vigente</th>
	</tr>
<?
$num=0;
$sql="SELECT org_ficha_directivo.cod_directivo, 
	org_ficha_directivo.n_documento, 
	sys_bd_cargo_directivo.descripcion AS cargo, 
	org_ficha_directivo.f_termino, 
	org_ficha_directivo.vigente, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno
FROM org_ficha_directivo INNER JOIN pit_bd_ficha_pdn ON org_ficha_directivo.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_directivo.n_documento_org = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_cargo_directivo ON sys_bd_cargo_directivo.cod_cargo = org_ficha_directivo.cod_cargo
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = org_ficha_directivo.cod_tipo_doc AND org_ficha_usuario.n_documento = org_ficha_directivo.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_ficha_pdn.cod_pdn='$cod'
ORDER BY org_ficha_directivo.f_termino ASC, org_ficha_directivo.cod_cargo ASC";
$result=mysql_query($sql) or die (mysql_error());
while($f3=mysql_fetch_array($result))
{
$cad=$f3['cod_directivo'];
$num++
?>	
	<tr>
		<td><? echo $num;?></td>
		<td><? echo $f3['n_documento'];?></td>
		<td><? echo $f3['nombre']." ".$f3['paterno']." ".$f3['materno'];?></td>
		<td><? echo $f3['cargo'];?></td>
		<td><? echo fecha_normal($f3['f_termino']);?></td>
		<td><input type="radio" name="vigente[<? echo $cad;?>]" value="1" <? if ($f3['vigente']==1) echo "checked";?>></td>
		<td><input type="radio" name="vigente[<? echo $cad;?>]" value="0" <? if ($f3['vigente']==0) echo "checked";?>></td>
	</tr>
<?
}
?>	
</table>
<div class="twelve columns"><br/></div>
<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<a href="pdn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button">Terminar</a>
</div>

</form>
<?
}
else
{
?>
<form name="form5" method="post" action="gestor_pdn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=ADD" onsubmit="return checkSubmit();">
	
	<div class="twelve columns">Seleccionar iniciativa de Plan de Negocio</div>
	<div class="twelve columns">
		<select name="pdn" class="hyjack">
			<option value="" selected="selected">Seleccionar</option>
			<?
			$sql="SELECT pit_bd_ficha_pdn.cod_pdn, 
			org_ficha_organizacion.nombre, 
			pit_bd_ficha_pdn.denominacion, 
			pit_bd_ficha_pdn.cod_estado_iniciativa
			FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
			WHERE org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."' AND
			pit_bd_ficha_pdn.cod_estado_iniciativa=005
			ORDER BY org_ficha_organizacion.nombre ASC";
			$result=mysql_query($sql) or die (mysql_error());
			while($f1=mysql_fetch_array($result))
			{
			?>
			<option value="<? echo $f1['cod_pdn'];?>"><? echo $f1['nombre']." / ".$f1['denominacion'];?></option>
			<?
			}
			?>
		</select>
	</div>
	
	<div class="twelve columns"><br/></div>
	
	<div class="twelve columns"><h6>II.- Información de contrapartida de la organizacion</h6></div>
	<div class="two columns">Nº de voucher</div>
	<div class="four columns"><input type="text" name="n_voucher" class="required five"></div>
	<div class="two columns">Monto depositado por la organizacion (S/.)</div>
	<div class="four columns"><input type="text" name="monto_org" class="required number five"></div>
	
	<div class="twelve columns"><h6>III.- Informacion de Ejecucion Financiera</h6></div>
	<div class="three columns">Nº de CH/ o C/O con la que se realizo el deposito del NEC PDSS II</div>
	<div class="three columns"><input type="text" name="n_cheque" class="ten required"></div>
	<div class="two columns">Fecha de desembolso</div>
	<div class="four columns"><input type="date" name="f_desembolso" class="required date five" placeholder="aaaa-mm-dd" maxlength="10"></div>
	<div class="twelve columns"><hr/></div>
	
	<div class="six columns"><h6>3.1 Total ejecutado a la fecha</h6></div>
	<div class="three columns"><h6>Sierra Sur II</h6></div>
	<div class="three columns"><h6>Organizacion</h6></div>
	
	<div class="six columns">Total ejecutado por Asistencia Tecnica (S/.)</div>
	<div class="three columns"><input type="text" name="total1" class="required five number"></div>
	<div class="three columns"><input type="text" name="total2" class="required five number"></div>
	<div class="six columns">Total ejecutado por Participacion en Ferias (S/.)</div>
	<div class="three columns"><input type="text" name="total3" class="required five number"></div>
	<div class="three columns"><input type="text" name="total4" class="required five number"></div>	
	<div class="six columns">Total ejecutado por Visita Guiada (S/.)</div>
	<div class="three columns"><input type="text" name="total5" class="required five number"></div>
	<div class="three columns"><input type="text" name="total6" class="required five number"></div>	
	<div class="six columns">Total ejecutado por Apoyo a la Gestión (S/.)</div>
	<div class="three columns"><input type="text" name="total7" class="required five number"></div>
	<div class="three columns"><br/></div>	
	<div class="twelve columns"><hr/></div>
	<div class="six columns"><h6>3.2 Nº de meses que lleva la iniciativa ejecutandose</h6></div>
	<div class="six columns"><input type="text" name="mes" class="required digits three"></div>
	
	<div class="twelve columns"><hr/></div>
	
	
	<div class="twelve columns"><h6>IV.- Patrimonio del Plan de Negocio</h6></div>	
	<table>
		<tr>
			<th>Tipo de patrimonio</th>
			<th class="three">Descripcion del patrimonio</th>
			<th>Valor estimado antes del PDN (S/.)</th>
			<th>Valor con el PDN (S/.)</th>
			<th>Monto de inversion propia en patrimonio (S/.)</th>
			<th>Monto de aporte de otros en patrimonio (S/.)</th>
		</tr>
<?
for($i=1;$i<=20;$i++)
{
?>		
		<tr>
			<td>
				<select name="tipo_activo[]">
					<option value="" selected="selected">Seleccionar</option>
					<?
					$sql="SELECT * FROM sys_bd_tipo_activo";
					$result1=mysql_query($sql) or die (mysql_error());
					while($f4=mysql_fetch_array($result1))
					{
					?>
					<option value="<? echo $f4['cod'];?>"><? echo $f4['cod']."-".$f4['descripcion'];?></option>
					<?
					}
					?>
				</select>
			</td>
			<td><input type="text" name="descripcion[]"></td>
			<td><input type="text" name="valor_a[]"></td>
			<td><input type="text" name="valor_b[]"></td>
			<td><input type="text" name="aporte_propio[]"></td>
			<td><input type="text" name="aporte_otro[]"></td>
		</tr>
<?
}
?>		
	</table>

<div class="twelve columns"><h6>V.- Ventas del Plan de Negocio</h6></div>	
<table>
	<tr>
		<th class="three">Producto vendido</th>
		<th>Unidad de medida</th>
		<th>Cantidad vendida al año anterior del PDN</th>
		<th>Valor de ventas al año anterior al PDN</th>
		<th>Cantidad vendida con el PDN</th>
		<th>Valor de ventas con el PDN</th>
	</tr>
<?
for ($i=1;$i<=10;$i++)
{
?>	
	<tr>
		<td><input type="text" name="producto_vendido[]"></td>
		<td><input type="text" name="unidad_producto[]"></td>
		<td><input type="text" name="cantidad_a[]" class="number"></td>
		<td><input type="text" name="valor_a[]" class="number"></td>
		<td><input type="text" name="cantidad_b[]" class="number"></td>
		<td><input type="text" name="valor_b[]" class="number"></td>
	</tr>
<?
}
?>	
</table>
<div class="twelve columns"><h6>VI.- Costos de Produccion</h6></div>	

<table>
	<tr>
		<th class="seven">Tipo de costo o gasto</th>
		<th>Valor de compras o gastos anteriores al PDN (S/.)</th>
		<th>Valor de compras o gastos con el PDN (S/.)</th>
	</tr>
	
	<tr>
		<td>Compras de Materias primas e insumos</td>
		<td><input type="text" name="costo_a" class="number"></td>
		<td><input type="text" name="costo_b" class="number"></td>
	</tr>
	<tr>
		<td>Pago de Personal-Jornales Pagados</td>
		<td><input type="text" name="costo_c" class="number"></td>
		<td><input type="text" name="costo_d" class="number"></td>
	</tr>	
	<tr>
		<td>Pago de Personal-Asistencia Tecnica</td>
		<td><input type="text" name="costo_e" class="number"></td>
		<td><input type="text" name="costo_f" class="number"></td>
	</tr>	
	<tr>
		<td>Pago de Servicios (Telefono,Pasajes, Luz, Agua, Etc)</td>
		<td><input type="text" name="costo_g" class="number"></td>
		<td><input type="text" name="costo_h" class="number"></td>
	</tr>		
	<tr>
		<td>Materiales de oficina y otros</td>
		<td><input type="text" name="costo_i" class="number"></td>
		<td><input type="text" name="costo_j" class="number"></td>
	</tr>	
	<tr>
		<td>Otros gastos diversos</td>
		<td><input type="text" name="costo_k" class="number"></td>
		<td><input type="text" name="costo_l" class="number"></td>
	</tr>	
</table>	
	
	
	
	
	<div class="twelve columns"><br/></div>
	<div class="twelve columns">
		<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
		<button type="button" class="secondary button" onclick="document.form5.btn_envia.disabled=!document.form5.btn_envia.disabled">Desbloquear</button>
		<a href="pdn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button">Cancelar</a>
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
<script type="text/javascript" src="../plugins/jquery.js"></script>

<!-- VALIDADOR DE FORMULARIOS -->
<script src="../plugins/validation/jquery.validate.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" media="screen" href="../plugins/validation/stylesheet.css" />
<script type="text/javascript" src="../plugins/validation/jquery.maskedinput.js"></script>
<script type="text/javascript" src="../plugins/validation/mktSignup.js"></script>    

<!-- Combo Buscador -->
<link href="../plugins/combo_buscador/hyjack.css" rel="stylesheet" type="text/css" />
<script src="../plugins/combo_buscador/hyjack_externo.js" type="text/javascript"></script>
<script src="../plugins/combo_buscador/configuracion.js" type="text/javascript"></script>

</body>
</html>
