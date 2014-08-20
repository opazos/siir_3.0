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
  
  <script src="../javascripts/btn_envia.js"></script>
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
<? echo $mensaje;?>


<!-- Iniciamos el contenido -->
<div class="row">
 <div class="twelve columns">
       

<?
if ($modo==mrn)
{
?>
<!-- Contenedores -->
<dl class="tabs">
<dd  class="active"><a href="">Paso 1.- Informacion de la Organizacion</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" id="form5" method="post" action="gestor_mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=ADD" onsubmit="return checkSubmit();">
	
	<div class="twelve columns">Seleccionar Organizacion</div>
	<div class="twelve columns">
		<select name="mrn" class="hyjack">
			<option value="" selected="selected">Seleccionar</option>
			<?
			$sql="SELECT org_ficha_organizacion.cod_tipo_doc, 
			org_ficha_organizacion.n_documento, 
			org_ficha_organizacion.nombre, 
			org_ficha_organizacion.cod_tipo_org
			FROM pit_bd_ficha_pit INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pit.cod_tipo_doc_taz = org_ficha_organizacion.cod_tipo_doc_taz AND pit_bd_ficha_pit.n_documento_taz = org_ficha_organizacion.n_documento_taz
			WHERE org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."' AND
			org_ficha_organizacion.cod_tipo_org<>6 AND
			pit_bd_ficha_pit.cod_estado_iniciativa=001
			ORDER BY org_ficha_organizacion.nombre ASC";
			$result=mysql_query($sql) or die (mysql_error());
			while($fila=mysql_fetch_array($result))
			{
			?>
			<option value="<? echo $fila['cod_tipo_doc'].",".$fila['n_documento'];?>"><? echo $fila['nombre'];?></option>
			<?
			}
			?>
		</select>
	</div>
	
	<div class="twelve columns"><br/></div>
	
	<div class="two columns">Sector/Anexo</div>
	<div class="four columns"><input type="text" name="sector"  class="eleven"></div>
	<div class="two columns">Lema del Plan de Gestión</div>
	<div class="four columns"><input type="text" name="lema" required="required" class="eleven"></div>
	<div class="two columns">Fecha de Inicio</div>
	<div class="four columns"><input type="date" name="f_inicio" required="required" class="five"></div>
	<div class="two columns"><span class="has-tip tip-top noradius" data-width="210" title="Duración expresada en meses">Duracion del Plan de Gestión</span></div>
	<div class="four columns"><input type="text" name="duracion" required="required" class="five"></div>
	<div class="twelve columns"><h6>Información Financiera</h6></div>
	<table class="responsive">
		<tbody>
			<tr>
				<th>Nº de cuenta</th>
				<th>Banco</th>
				<th>Nº voucher de deposito</th>
				<th>Monto depositado (S/.)</th>
			</tr>
			<tr>
				<td><input type="text" name="n_cuenta" class="required"></td>
				<td>
					<select name="ifi" class="ten required">
						<option value="" selected="selected">Seleccionar</option>
						<?
						$sql="SELECT sys_bd_ifi.cod_ifi, 
						sys_bd_ifi.descripcion
						FROM sys_bd_ifi
						ORDER BY sys_bd_ifi.descripcion ASC";
						$result=mysql_query($sql) or die (mysql_error());
						while($fila1=mysql_fetch_array($result))
						{
						?>
						<option value="<? echo $fila1['cod_ifi'];?>"><? echo $fila1['descripcion'];?></option>
						<?
						}
						?>
					</select>
				</td>
				<td><input type="text" name="n_voucher" class="required"></td>
				<td><input type="text" name="aporte_org" class="required number"></td>
			</tr>
		</tbody>
	</table>
	
	<div class="twelve columns"><h6>Propuesta de cofinanciamiento y contrapartidas</h6></div>
	<div class="two columns">Concursos interfamiliares</div>
	<div class="four columns"><input type="text" name="cif" class="five required number"></div>
	<div class="two columns">Apoyo a la gestion</div>
	<div class="four columns"><input type="text" name="ag" value="700" readonly="readonly" class="five required number"></div>
	
	<div class="twelve columns"><h6>Asistencia Tecnica</h6></div>
	<div class="two columns">Aporte Proyecto</div>
	<div class="four columns"><input type="text" name="at_pdss" class="required number five"></div>
	<div class="two columns">Aporte Organizacion</div>
	<div class="four columns"><input type="text" name="at_org" class="required number five"></div>
	
	<div class="twelve columns"><h6>Visitas Guiadas</h6></div>
	<div class="two columns">Aporte Proyecto</div>
	<div class="four columns"><input type="text" name="vg_pdss" class="required number five"></div>
	<div class="two columns">Aporte Organizacion</div>
	<div class="four columns"><input type="text" name="vg_org" class="required number five"></div>


<div class="twelve columns"><h6>IV.- Entidades Cofinanciadoras</h6></div>

<table class="responsive">
	<thead>
		<tr>
			<th class="nine">Nombre de la Entidad</th>
			<th>Tipo de Ente</th>
			<th>Monto de Aporte (S/.)</th>
		</tr>
	</thead>
	
	<tbody>
<?
for($i=1;$i<=3;$i++)
{
?>	
		<tr>
			<td><input type="text" name="ente[]"></td>
			<td>
				<select name="tipo_ente[]">
					<option value="" selected="selected">Seleccionar</option>
					<?
					$sql="SELECT * FROM sys_bd_ente_cofinanciador ORDER BY descripcion";
					$result1=mysql_query($sql) or die (mysql_error());
					while($r11=mysql_fetch_array($result1))
					{
					?>
					<option value="<? echo $r11['cod_ente'];?>"><? echo $r11['descripcion'];?></option>
					<?
					}
					?>
				</select>
			</td>
			<td><input type="text" name="aporte_ente[]" class="number"></td>
		</tr>
<?
}
?>		
	</tbody>
	
</table>


	
	
	<div class="twelve columns"><hr/></div>
	<div class="twelve columns">
		<button type="submit" class="primary button" id="btn_envia">Guardar cambios</button>
		<button type="button" class="secondary button" onclick="document.form5.btn_envia.disabled=!document.form5.btn_envia.disabled">Desbloquear</button>
		<a href="mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button">Cancelar</a>
	</div>
</form>

</div>
</li>
</ul>
</div>
</div>

<?
}
elseif($modo==familia)
{

//Busco las familias
$sql="SELECT pit_bd_user_iniciativa.n_documento
FROM pit_bd_user_iniciativa INNER JOIN pit_bd_ficha_mrn ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org
WHERE pit_bd_user_iniciativa.momento=1 AND
org_ficha_usuario.titular=1 AND
pit_bd_ficha_mrn.cod_mrn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$total_familia=mysql_num_rows($result);

//Ahora bajará a 60 siendo hoy 18-02-14
if ($total_familia<=150)
{
	$cif=$total_familia*120;
}
else
{
	$cif=150*120;
}
$sql="UPDATE pit_bd_ficha_mrn SET cif_pdss='$cif' WHERE cod_mrn='$cod'";
$result=mysql_query($sql) or die (mysql_error());

?>

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

<!-- Contenedores -->
<dl class="tabs">
<dd  class="active"><a href="">Paso 2.- Familias participantes en el Plan de Gestión</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<!-- formulario y contenido del mismo -->
<form name="form2" id="form5" method="post" action="gestor_mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=ADD_FAM&cod=<? echo $cod;?>">

<div class="twelve columns">
	<button type="submit" class="success button">Guardar cambios</button>
	<a href="n_mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=inform&cod=<? echo $cod;?>" class="primary button">Paso 3.- Informacion del plan de gestión >></a>
	<a href="mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="primary button">Finalizar</a>
</div>
<div class="twelve columns"><hr/></div>

<table class="responsive" id="lista">
	<tbody>
		<tr>
			<th>Nº</th>
			<th>DNI</th>
			<th>Nombres completos</th>
			<th>Participa</th>
		</tr>
<?
$sql="SELECT org_ficha_usuario.n_documento, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno, 
	pit_bd_user_iniciativa.n_documento AS dni
FROM org_ficha_usuario INNER JOIN pit_bd_ficha_mrn ON org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org
	 LEFT OUTER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = org_ficha_usuario.n_documento AND pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn
WHERE pit_bd_ficha_mrn.cod_mrn='$cod'
ORDER BY org_ficha_usuario.nombre ASC";
$result=mysql_query($sql) or die (mysql_error());
$num=0;
while($fila=mysql_fetch_array($result))
{
	$num++
?>		
		<tr>
			<td><? echo $num;?></td>
			<td><? echo $fila['n_documento'];?></td>
			<td class="ten"><? echo $fila['nombre']." ".$fila['paterno']." ".$fila['materno'];?></td>
			<td><input type="checkbox" name="campos[]" value="<? echo $fila['n_documento'];?>" <? if ($fila['dni']<>NULL) echo "checked";?>></td>
		</tr>
<?
}
?>		
	</tbody>
</table>

</form>
<!-- fin del formulario -->
</div>
</li>
</ul>
</div>
</div>
<?
}
elseif($modo==inform)
{
?>
<!-- Contenedores -->
<dl class="tabs">
<dd  class="active"><a href="">Paso 3.- Información del Plan de Gestión de Recursos Naturales</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<!-- formulario y contenido del mismo -->
<form name="form3" class="custom" method="post" action="gestor_mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&action=ADD_INFO">

<div class="twelve columns">
<button type="submit" class="primary button">Guardar cambios</button>
<a href="mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button">Finalizar</a>
</div>
<div class="twelve columns"><hr/></div>
<div class="twelve columns"><h6>2.1.- Actividades que actualmente realizan </h6></div>
<table class="responsive">
	<tbody>
		<tr>
			<th>Nº</th>
			<th>Actividades</th>
			<th>Nivel</th>
			<th>Nº de familias aprox.</th>
			<th>Eliminar</th>
		</tr>
<?
$sql="SELECT mrn_actividad_actual.cod, 
	mrn_actividad_actual.nivel, 
	mrn_actividad_actual.n_familia, 
	mrn_actividad_actual.cod_actividad
FROM sys_bd_actividad_mrn INNER JOIN mrn_actividad_actual ON sys_bd_actividad_mrn.cod = mrn_actividad_actual.cod_actividad
WHERE mrn_actividad_actual.cod_mrn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$num=0;
while($f1=mysql_fetch_array($result))
{
$cad=$f1['cod'];
$num++
?>			
		<tr>
			<td><? echo $num;?></td>
			<td>
				<select name="actividad_presente[<? echo $cad;?>]">
					<option value="" selected="selected">Seleccionar</option>
					<?
					$sql="SELECT sys_bd_actividad_mrn.cod, 
					sys_bd_actividad_mrn.descripcion
					FROM sys_bd_actividad_mrn";
					$result11=mysql_query($sql) or die (mysql_error());
					while($r11=mysql_fetch_array($result11))
					{
					?>
					<option value="<? echo $r11['cod'];?>" <? if ($r11['cod']==$f1['cod_actividad']) echo "selected";?>><? echo $r11['descripcion'];?></option>
					<?
					}
					?>
				</select>
			</td>
			<td>
			<select name="nivel_presente[<? echo $cad;?>]">
			<option value="" selected="selected">Seleccionar</option>
			<option value="1" <? if ($f1['nivel']==1) echo "selected";?>>Familiar</option>
			<option value="2" <? if ($f1['nivel']==2) echo "selected";?>>Organizacion</option>
			</select>
			</td>
			<td><input type="text" name="familias_presente[<? echo $cad;?>]" value="<? echo $f1['n_familia'];?>"></td>
			<td><a href="" class="small alert button">Eliminar</a></td>
		</tr>
<?
}
?>	

<?
for ($i = 1; $i <=5; $i++) 
{
?>
<tr>
	<td>-</td>
	<td>
				<select name="actividad_presentes[]">
					<option value="" selected="selected">Seleccionar</option>
					<?
					$sql="SELECT sys_bd_actividad_mrn.cod, 
					sys_bd_actividad_mrn.descripcion
					FROM sys_bd_actividad_mrn";
					$result2=mysql_query($sql) or die (mysql_error());
					while($r2=mysql_fetch_array($result2))
					{
					?>
					<option value="<? echo $r2['cod'];?>"><? echo $r2['descripcion'];?></option>
					<?
					}
					?>
				</select>		
	</td>
	<td><select name="nivel_presentes[]"><option value="" selected="selected">Seleccionar</option><option value="1">Familiar</option><option value="2">Organizacion</option></select></td>
	<td><input type="text" name="familias_presentes[]"></td>
	<td>-</td>
</tr>
<?
}
?>
	</tbody>
</table>
<div class="twelve columns"><h6>2.2.- Actividades que se proponen mejorar o realizar</h6></div>
<table class="responsive">
	<tbody>
		<tr>
			<th>Nº</th>
			<th>Actividades</th>
			<th>Nivel</th>
			<th>Nº de familias aprox.</th>
			<th>Eliminar</th>
		</tr>
<?
$n1=0;
$sql="SELECT mrn_actividad_futuro.cod, 
	mrn_actividad_futuro.cod_actividad, 
	mrn_actividad_futuro.nivel, 
	mrn_actividad_futuro.n_familia
FROM mrn_actividad_futuro
WHERE mrn_actividad_futuro.cod_mrn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($f2=mysql_fetch_array($result))
{
$ced=$f2['cod'];
$n1++
?>		
		<tr>
			<td><? echo $n1;?></td>
			<td>
				<select name="actividad_futura[<? echo $ced;?>]">
					<option value="" selected="selected">Seleccionar</option>
					<?
					$sql="SELECT sys_bd_actividad_mrn.cod, 
					sys_bd_actividad_mrn.descripcion
					FROM sys_bd_actividad_mrn";
					$result3=mysql_query($sql) or die (mysql_error());
					while($r3=mysql_fetch_array($result3))
					{
					?>
					<option value="<? echo $r3['cod'];?>" <? if ($r3['cod']==$f2['cod_actividad']) echo "selected";?>><? echo $r3['descripcion'];?></option>
					<?
					}
					?>
				</select>		
			</td>
			<td>
			<select name="nivel_futuro[<? echo $ced;?>]">
			<option value="" selected="selected">Seleccionar</option>
			<option value="1" <? if ($f2['nivel']==1) echo "selected";?>>Familiar</option>
			<option value="2" <? if ($f2['nivel']==2) echo "selected";?>>Organizacion</option>
			</select>
			</td>
			<td><input type="text" name="familia_futuro[<? echo $ced;?>]" value="<? echo $f2['n_familia'];?>"></td>
			<td><a href="" class="small alert button">Eliminar</a></td>
		</tr>
<?
}
?>	

<?
for ($i=1; $i<=5; $i++) 
{
?>

<tr>
	<td>-</td>
	<td>
				<select name="actividad_futuras[]">
					<option value="" selected="selected">Seleccionar</option>
					<?
					$sql="SELECT sys_bd_actividad_mrn.cod, 
					sys_bd_actividad_mrn.descripcion
					FROM sys_bd_actividad_mrn";
					$result4=mysql_query($sql) or die (mysql_error());
					while($r4=mysql_fetch_array($result4))
					{
					?>
					<option value="<? echo $r4['cod'];?>"><? echo $r4['descripcion'];?></option>
					<?
					}
					?>
				</select>			
	</td>
	<td><select name="nivel_futuros[]"><option value="" selected="selected">Seleccionar</option><option value="1">Familiar</option><option value="2">Organizacion</option></select></td>
	<td><input type="text" name="familia_futuros[]"></td>
	<td>-</td>
</tr>
<?
}
?>
</tbody>
</table>	
<div class="twelve columns"><h6>2.3.- Areas destinadas al plan de gestión de recursos naturales</h6></div>
<div class="six columns">Area</div>
<div class="two columns">Total Has.</div>
<div class="two columns">Total Has. bajo riego</div>
<div class="two columns">Total Has. en secano</div>
<hr/>
<?
$sql="SELECT * FROM mrn_area WHERE cod_mrn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r5=mysql_fetch_array($result);
?>
<div class="six columns">Area que destinará cada familia, en promedio, para trabajar en el plan de Gestión de Recursos Naturales
<input type="hidden" name="cod_area" value="<? echo $r5['cod'];?>">
</div>
<div class="two columns"><input type="text" name="area_a" class="number ten" value="<? echo $r5['a1'];?>"></div>
<div class="two columns"><input type="text" name="area_b" class="number ten" value="<? echo $r5['a2'];?>"></div>
<div class="two columns"><input type="text" name="area_c" class="number ten" value="<? echo $r5['a3'];?>"></div>
<div class="six columns">Area que destinará la organizacion para trabajar en el Plan de Gestión de Recursos Naturales</div>
<div class="two columns"><input type="text" name="area_d" class="number ten" value="<? echo $r5['a4'];?>"></div>
<div class="two columns"><input type="text" name="area_e" class="number ten" value="<? echo $r5['a5'];?>"></div>
<div class="two columns"><input type="text" name="area_f" class="number ten" value="<? echo $r5['a6'];?>"></div>

<div class="twelve columns"><h6>2.4.- Propuesta e calendario de concursos</h6></div>
<table class="responsive">
	<tbody>
		<tr>
			<th>Nº</th>
			<th>Mes</th>
			<th>Año</th>
			<th class="seven">Principales lineas de actividad a concursar</th>
			<th>Eliminar</th>
		</tr>
<?
$n2=0;
$sql="SELECT * FROM mrn_concurso WHERE cod_mrn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($f3=mysql_fetch_array($result))
{
	$cid=$f3['cod'];
	$n2++
?>		
		<tr>
		<td><? echo $n2;?></td>
		<td>
				<select name="mes[<? echo $cid;?>]">
				    <option value="" selected="selected">Seleccionar</option>
				    <option value="ENERO" <? if ($f3['mes']==ENERO) echo "selected";?>>ENERO</option>
				    <option value="FEBRERO" <? if ($f3['mes']==FEBRERO) echo "selected";?>>FEBRERO</option>
				    <option value="MARZO" <? if ($f3['mes']==MARZO) echo "selected";?>>MARZO</option>
				    <option value="ABRIL" <? if ($f3['mes']==ABRIL) echo "selected";?>>ABRIL</option>
				    <option value="MAYO" <? if ($f3['mes']==MAYO) echo "selected";?>>MAYO</option>
				    <option value="JUNIO" <? if ($f3['mes']==JUNIO) echo "selected";?>>JUNIO</option>
				    <option value="JULIO" <? if ($f3['mes']==JULIO) echo "selected";?>>JULIO</option>
				    <option value="AGOSTO" <? if ($f3['mes']==AGOSTO) echo "selected";?>>AGOSTO</option>
				    <option value="SEPTIEMBRE" <? if ($f3['mes']==SEPTIEMBRE) echo "selected";?>>SEPTIEMBRE</option>
				    <option value="OCTUBRE" <? if ($f3['mes']==OCTUBRE) echo "selected";?>>OCTUBRE</option>
				    <option value="NOVIEMBRE" <? if ($f3['mes']==NOVIEMBRE) echo "selected";?>>NOVIEMBRE</option>
				    <option value="DICIEMBRE" <? if ($f3['mes']==DICIEMBRE) echo "selected";?>>DICIEMBRE</option>
			    </select>
		</td>
		<td><input type="text" name="anio[<? echo $cid;?>]" value="<? echo $f3['anio'];?>"></td>
		<td><input type="text" name="linea[<? echo $cid;?>]" value="<? echo $f3['linea'];?>"></td>
		<td><a href="" class="small alert button">Eliminar</a></td>	
		</tr>
<?
}
?>		

<?
for ($i=1; $i<=3; $i++) 
{
?>
<tr>
	<td>-</td>
	<td>
					<select name="mess[]">
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
	<td><input type="text" name="anios[]"></td>
	<td><input type="text" name="lineas[]"></td>
	<td>-</td>
</tr>
<?
}
?>
	</tbody>
</table>

</form>

<!-- fin del formulario -->
</div>
</li>
</ul>
</div>
</div>

<?
}
?>
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
