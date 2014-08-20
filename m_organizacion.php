<?
require("funciones/sesion.php");
include("funciones/funciones.php");
include("funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);

$sql="SELECT * FROM org_ficha_organizacion WHERE cod_tipo_doc='$tipo' AND n_documento='$numero'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$ahora=date('Y-m-d');
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
   <link type="image/x-icon" href="images/favicon.ico" rel="shortcut icon" />
   
   
  <link rel="stylesheet" href="stylesheets/foundation.css">
  <link rel="stylesheet" href="stylesheets/general_foundicons.css">
 
  <link rel="stylesheet" href="stylesheets/app.css">
  <link rel="stylesheet" href="rtables/responsive-tables.css">
  

    <script src="javascripts/btn_envia.js"></script>
  <script src="javascripts/modernizr.foundation.js"></script>
  <script src="rtables/javascripts/jquery.min.js"></script>
  <script src="rtables/responsive-tables.js"></script>
  
  <!-- COMBO DE 3 NIVELES -->
  <script type="text/javascript" src="plugins/combo_dinamico/select_dependientes_4_niveles.js"></script>
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

<? 
if ($modo==familia or $modo==directiva)
{
echo "<br/>";
}
else
{
?>
<dl class="tabs">
<dd  class="active"><a href="">Paso 1 de 3 - Modificar  Organizacion <? if ($modo==pit) echo "asociada a un territorio";?></a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form id="form5" method="post" action="gestor_organizacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=UPDATE" onsubmit="return checkSubmit();">
	<div class="two columns">Tipo documento</div>
	<div class="four columns">
	<select name="tipo_doc" class="five">
<option value="" selected="selected">Seleccionar</option>
<?
$sql="SELECT sys_bd_tipo_doc.cod_tipo_doc, 
sys_bd_tipo_doc.descripcion
FROM sys_bd_tipo_doc
ORDER BY sys_bd_tipo_doc.descripcion ASC";
$result=mysql_query($sql) or die (mysql_error());
while($r1=mysql_fetch_array($result))
{
?>
<option value="<? echo $r1['cod_tipo_doc'];?>" <? if ($r1['cod_tipo_doc']==$row['cod_tipo_doc']) echo "selected";?>><? echo $r1['descripcion'];?></option>
<?
}
?>
	</select>
	</div>
	<div class="two columns">Nº documento</div>
	<div class="four columns"><input type="text" name="n_documento" class="five required" value="<? echo $row['n_documento'];?>"><input type="hidden" name="codigo" value="<? echo $row['cod_tipo_doc'].",".$row['n_documento'];?>"></div>

<div class="two columns">Nombre</div>
<div class="ten columns"><input type="text" name="organizacion" class="required" value="<? echo $row['nombre'];?>"></div>

<div class="two columns">Fecha de inscripcion a RRPP</div>
<div class="four columns"><input type="date" name="f_registro" placeholder="aaaa-mm-dd" maxlength="10" class="five" value="<? echo $row['f_formalizacion'];?>"></div>
<div class="two columns">Tipo de organizacion</div>
<div class="four columns">
<select name="tipo" class="five required">
<option value="" selected="selected">Seleccionar</option>	
<?
$sql="SELECT sys_bd_tipo_org.cod_tipo_org, 
	sys_bd_tipo_org.descripcion
FROM sys_bd_tipo_org
ORDER BY sys_bd_tipo_org.descripcion ASC";
$result=mysql_query($sql) or die (mysql_error());
while($r2=mysql_fetch_array($result))
{
?>
<option value="<? echo $r2['cod_tipo_org'];?>" <? if ($r2['cod_tipo_org']==$row['cod_tipo_org']) echo "selected";?>><? echo $r2['descripcion'];?></option>
<?
}
?>
</select>
</div>
<div class="twelve columns"><br/></div>

<div class="two columns">Territorio al que pertenece</div>
<div class="ten columns">
	<select name="territorio" class="required nine">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT org_ficha_taz.cod_tipo_doc, 
		org_ficha_taz.n_documento, 
		org_ficha_taz.nombre
		FROM org_ficha_taz
		WHERE org_ficha_taz.cod_dependencia='".$row['cod_dependencia']."'
		ORDER BY org_ficha_taz.nombre ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($r3=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $r3['cod_tipo_doc'].",".$r3['n_documento'];?>" <? if ($r3['cod_tipo_doc']==$row['cod_tipo_doc_taz'] and $r3['n_documento']==$row['n_documento_taz']) echo "selected";?>><? echo $r3['nombre'];?></option>
		<?
		}
		?>
	</select>
</div>


<div class="two columns">Departamento</div>
<div class="ten columns"><?
$consulta=mysql_query("SELECT cod,nombre FROM sys_bd_departamento");
// Voy imprimiendo el primer select compuesto por los paises
echo "<select  name='select1' id='select1' onChange='cargaContenido(this.id)' class='three'>";
echo "<option value='0' selected='selected'>Seleccionar</option>";
while($registro=mysql_fetch_row($consulta))
{
if($row['cod_dep']==$registro[0])
echo "<option value=".$registro[0]." selected='selected'>".$registro[1]."</option>";
echo "<option value='".$registro[0]."'>".$registro[1]."</option>";
}
echo "</select>";

?></div>
<div class="two columns">Provincia</div>
<div class="ten columns">
<select name="select2" id="select2" class="three">
            <option value="<? echo $row['cod_prov'];?>">
              <? 
$prov=$row['cod_prov'];
$sql="SELECT * FROM sys_bd_provincia WHERE cod='$prov'";
$result=mysql_query($sql) or die (mysql_error());
$row_1=mysql_fetch_array($result);
echo $row_1['nombre'];
?>
              </option>
</select>	
</div>
<div class="two columns">Distrito</div>
<div class="ten columns">
<select  name="select3" id="select3" class="three">
            <option value="<? echo $row['cod_dist'];?>">
              <?
$distrito=$row['cod_dist'];
$sql="SELECT * FROM sys_bd_distrito WHERE cod='$distrito'";
$result=mysql_query($sql) or die (mysql_error());
$row_2=mysql_fetch_array($result);
echo $row_2['nombre'];
?>
              </option>
</select>	
</div>

<div class="two columns">Centro Poblado</div>
<div class="ten columns">
	<select name="select4" id="select4" class="three">
		<option value="<? echo $row['cod_cp'];?>">
<?
$cp=$row['cod_cp'];
$sql="SELECT * FROM sys_bd_cp WHERE cod='$cp'";
$result=mysql_query($sql) or die (mysql_error());
$row_3=mysql_fetch_array($result);
echo $row_3['nombre'];
?>			
		</option>
	</select>
</div>


<div class="two columns">Direccion /Sector</div>
<div class="ten columns"><input type="text" name="direccion" class="required" value="<? echo $row['sector'];?>"></div>



<div class="twelve columns">
	<a href="m_organizacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&tipo=<? echo $tipo;?>&numero=<? echo $numero;?>&modo=familia" class="primary button">Edicion de Familias >></a>
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<a href="organizacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="primary button">Terminar</a>
</div>	
</form>
</div>
</li>
</ul>

<?
}
?>
<? 
if ($modo==familia)
{


if ($action <>NULL)
{
include("funciones/funciones_externas.php");
conectarte_externo();

$sql="SELECT * FROM maestro_reniec WHERE dni='".$_POST['n_dni']."'";
$result=mysql_query($sql) or die (mysql_error());
$total=mysql_num_rows($result);

if ($total==0)
{
//echo "<script>window.location ='n_organizacion.php?SES=$SES&anio=$anio&tipo=$tipo&numero=$numero&modo=familia&error=no_dni'</script>";
	conectarte();
	$sql="SELECT * FROM org_ficha_usuario WHERE n_documento='".$_POST['n_dni']."'";
	$result1=mysql_query($sql) or die (mysql_error());
	$total1=mysql_num_rows($result1);
	if ($total1==0)
	{
		echo "<script>window.location ='m_organizacion.php?SES=$SES&anio=$anio&tipo=$tipo&numero=$numero&modo=familia&error=no_dni'</script>";
	}
	else
	{
		$row1=mysql_fetch_array($result1);
		
		//Jalo valores
		$dni=$row1['n_documento'];
		$nombre=$row1['nombre'];
		$paterno=$row1['paterno'];
		$materno=$row1['materno'];
		$f_nac=$row1['f_nacimiento'];
		$sexo=$row1['sexo'];
		$ubigeo=$row1['ubigeo'];
		$direccion=$row1['direccion'];
		$hijos=$row1['n_hijo'];
		$externo=0;
	}

}
else
{
$row1=mysql_fetch_array($result);

		//Jalo valores
		$dni=$row1['dni'];
		$nombre=$row1['nombres'];
		$paterno=$row1['paterno'];
		$materno=$row1['materno'];
		$f_nac=$row1['fenac'];
		
		if ($row1['sexo']==M)
		{
			$sexo=1;
		}
		else
		{
			$sexo=0;
		}
		
		$ubigeo=$row1['ubigeo'];
		$direccion="";
		$hijos="";
		$externo=1;
}

}

?>
<!--  ************************************************************************************************************************************************************************************************** -->
<!-- Registro de familias -->
<dl class="tabs">
<dd  class="active"><a href="">Paso 2 de 3 - Edicion de Familias</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<form name="form5" id="form5"  method="post" class="custom"  action="gestor_organizacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&tipo=<? echo $tipo;?>&numero=<? echo $numero;?>&action=ADD_FAM_SAVE" onsubmit="return checkSubmit();">
<!-- contenido del formulario -->
<div class="twelve columns"><h6>I.- Desde aqui podemos añadir nuevas familias a la organizacion</h6></div>
<div class="twelve columns"><hr/></div>

<div class="twelve panel  columns">
<div class="row collapse">
<div class="two columns">Nº DNI</div>
<div class="two columns"><input type="text" name="n_dni" class="dni required ten digits" maxlength="8" value="<? echo $dni;?>"></div>
<div class="one columns">

<button type="button" onclick="this.form.action='m_organizacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&tipo=<? echo $tipo;?>&numero=<? echo $numero;?>&modo=familia&action=VERIFICAR';this.form.submit()" class="small success button">Verificar DNI</button>
</div>
<div class="one columns"><br/></div>
<div class="two columns">Jefe de Familia/Pareja</div>
<div class="four columns">
	<select name="n_titular" class="ten">
		<option value="" selected="selected">Seleccionar</option>
		<option value="1">Jefe de Familia</option>
		<option value="0">Pareja</option>
	</select>
</div>
<div class="twelve columns"></div>
<div class="two columns">Primer apellido</div>
<div class="four columns"><input type="text" name="n_paterno" class="required ten" value="<? echo $paterno;?>"></div>
<div class="two columns">Segundo apellido</div>
<div class="four columns"><input type="text" name="n_materno" class="required ten" value="<? echo $materno;?>"></div>
<div class="two columns">Pre nombres</div>
<div class="four columns"><input type="text" name="n_nombre" class="required ten" value="<? echo $nombre;?>"></div>
<div class="two columns">Fecha de nacimiento</div>
<div class="four columns"><input type="date" name="n_f_nacimiento" class="required date six" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $f_nac;?>"></div>
<div class="two columns">Ubigeo</div>
<div class="four columns"><input type="text" name="n_ubigeo" class="digits five" value="<? echo $ubigeo;?>"></div>
<div class="two columns">Sexo</div>
<div class="four columns">
	<select name="n_sexo" class="five">
		<option value="" selected="selected">Seleccionar</option>
		<option value="1" <? if ($sexo==1) echo "selected";?>>M</option>
		<option value="0" <? if ($sexo==0) echo "selected";?>>F</option>

	</select>
</div>
<div class="twelve columns"></div>
<div class="two columns">Direccion</div>
<div class="ten columns"><input type="text" name="n_direccion"  value="<? echo $direccion;?>"></div>
<div class="two columns">Figura en el padron de socios?</div>
<div class="four columns">
	<select name="n_socio" class="five">
		<option value="" selected="selected">Seleccionar</option>
		<option value="1">Si</option>
		<option value="0">No</option>
	</select>
</div>
<div class="two columns">Nº de hijos < 5 años (Llenar solo si es Jefe de Familia)</div>
<div class="four columns"><input type="text" name="n_hijos" class="five digits" value="<? echo $hijos;?>"></div>
<div class="twelve columns">En caso de que no sea Jefe de Familia, seleccionar al Jefe de Familia desde la lista</div>
<div class="six columns">
	<select name="n_pareja" class="ten">
		<option value="" selected="selected">Seleccionar</option>
		<?
		if ($externo==1)
		{
		conectarte();
		}
				
		$sql="SELECT org_ficha_usuario.n_documento, 
		org_ficha_usuario.nombre, 
		org_ficha_usuario.paterno, 
		org_ficha_usuario.materno
		FROM org_ficha_usuario
		WHERE org_ficha_usuario.titular=1 AND
		org_ficha_usuario.cod_tipo_doc_org='$tipo' AND
		org_ficha_usuario.n_documento_org='$numero'
		ORDER BY org_ficha_usuario.nombre ASC	";
		$result=mysql_query($sql) or die (mysql_error());
		while($f1=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f1['n_documento'];?>"><? echo $f1['nombre']." ".$f1['paterno']." ".$f1['materno'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="six columns">
<button name="btn1" type="submit" class="success button" id="btn_envia" >Registrar</button>
<button type="button" class="secondary button" onclick="document.form5.btn_envia.disabled=!document.form5.btn_envia.disabled">Desbloquear</button>
</div>	
</div>
</div>
<!-- fin del contenido del formulario -->
</form>

<form name="f2" method="post" class="custom"  action="" onsubmit="return checkSubmit();">
<!-- Contenido del formulario -->
<div class="twelve columns"><h6>II.- Desde aqui podemos actualizar a las familias ya registradas</h6></div>
<div class="twelve columns">	


<button type="button" onclick="this.form.action='gestor_organizacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&tipo=<? echo $tipo;?>&numero=<? echo $numero;?>&action=SAVE_FAM';this.form.submit()" class="success button" id="btn_envia">Guardar cambios</button>


<a href="m_organizacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&tipo=<? echo $tipo;?>&numero=<? echo $numero;?>&modo=directiva" class="primary button">Junta Directiva >></a>
<a href="organizacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="secondary button">Terminar</a>

</div>
<div class="twelve columns"><hr/></div>
<!-- Generamos un acordeon para darle vistosidad a los registros -->
<div class="twelve columns">
<ul class="accordion">
<?
$num=0;
$sql="SELECT * FROM org_ficha_usuario WHERE cod_tipo_doc_org='$tipo' AND n_documento_org='$numero' ORDER BY nombre ASC";
$result=mysql_query($sql) or die (mysql_error());
while($fila=mysql_fetch_array($result))
{

$cod=$fila['n_documento'];
$num++
?>
<li>
<div class="title">
<h5><? echo $num.".- ". $fila['nombre']." ".$fila['paterno']." ".$fila['materno'];?> - Edad : <? $edad=$ahora-$fila['f_nacimiento']; echo $edad;?> Años</h5>
</div>
<div class="content">
<div class="row">
<div class="two columns">Nº DNI</div>
<div class="four columns">

<input type="hidden" name="codigo" value="<? echo $fila['n_documento'];?>">
<input type="text" name="dni[<? echo $cod;?>]" value="<? echo $fila['n_documento'];?>" class="five"></div>


<div class="two columns">Fecha Nac.</div>
<div class="four columns"><input type="date" name="fecha[<? echo $cod;?>]" maxlength="10" placeholder="aaaa-mm-dd" value="<? echo $fila['f_nacimiento'];?>" class="five" ></div>

<div class="two columns">Nombres</div>
<div class="ten columns"><input type="text" name="nombres[<? echo $cod;?>]" value="<? echo $fila['nombre'];?>"></div>

<div class="two columns">Apellido paterno</div>
<div class="four columns"><input type="text" name="paterno[<? echo $cod;?>]" value="<? echo $fila['paterno'];?>"></div>

<div class="two columns">Apellido materno</div>
<div class="four columns"><input type="text" name="materno[<? echo $cod;?>]" value="<? echo $fila['materno'];?>"></div>

<div class="two columns">Sexo</div>
<div class="four columns"><select name="sexo[<? echo $cod;?>]"><option value="" selected="selected">Seleccionar</option><option value="1" <? if ($fila['sexo']==1) echo "selected";?>>Hombre</option><option value="0" <? if ($fila['sexo']==0) echo "selected";?>>Mujer</option></select></div>

<div class="two columns">Ubigeo</div>
<div class="four columns"><input type="text" name="ubigeo[<? echo $cod;?>]" class="five" value="<? echo $fila['ubigeo'];?>"></div>

<div class="two columns">Direccion</div>
<div class="ten columns"><input type="text" name="address[<? echo $cod;?>]" value="<? echo $fila['direccion'];?>"></div>

<div class="two columns">Aparece en el padron de socios?</div>
<div class="four columns"><select name="socio[<? echo $cod;?>]"><option value="" selected="selected">Seleccionar</option><option value="1" <? if ($fila['socio']==1) echo "selected";?>>Si</option><option value="0" <? if ($fila['socio']==0) echo "selected";?>>No</option></select></div>

<div class="two columns">Nº Hijos < 5 años</div>
<div class="four columns"><input type="text" name="hijo[<? echo $cod;?>]" value="<? echo $fila['n_hijo'];?>" class="five" <? if ($fila['titular']==0) echo "readonly";?>></div>

<div class="two columns">Jefe de Familia / Pareja</div>
<div class="four columns"><select name="titular[<? echo $cod;?>]"><option value="" selected="selected">Seleccionar</option><option value="1" <? if ($fila['titular']==1) echo "selected";?>>Jefe de Familia</option><option value="0" <? if ($fila['titular']==0) echo "selected";?>>Pareja</option></select></div>

<div class="two columns">DNI Jefe de Familia (En caso no lo sea)</div>
<div class="four columns"><input type="text" maxlength="8" name="pareja[<? echo $cod;?>]" value="<? echo $fila['n_documento_conyuge'];?>" class="five"></div>

<div class="twelve columns">
	<a href="gestor_organizacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&tipo=<? echo $tipo;?>&numero=<? echo $numero;?>&dni=<? echo $fila['n_documento'];?>&action=DELETE_FAM" class="alert button">Eliminar</a>
</div>

</div>
</div>
</li>
<?
}
?>
</ul>
</div>
<!-- fin del acordeon -->

<!-- fin del contenido del formulario -->
</form>
</li>
</ul>
<?
}
?>
<? 
if ($modo==directiva)
{
?>
<!--  ************************************************************************************************************************************************************************************************** -->
<!-- Registro de familias -->
<dl class="tabs">
<dd  class="active"><a href="">Paso 3 de 3 - Edicion de junta directiva</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form3" class="custom" method="post" action="gestor_organizacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&tipo=<? echo $tipo;?>&numero=<? echo $numero;?>&action=SAVE_DIR">

<div class="twelve columns">
<a href="m_organizacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&tipo=<? echo $tipo;?>&numero=<? echo $numero;?>&modo=familia" class="primary button"><< Edicion de Familias</a>
<button type="submit" class="success button">Guardar cambios y finalizar</button>
<a href="organizacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="primary button">Terminar</a>
</div>

<div class="twelve columns"><hr/></div>

<table class="responsive">
	<tbody>
		<tr>
			<th class="seven">Directivo</th>
			<th>Cargo</th>
			<th>Vigencia hasta</th>
			<th></th>
		</tr>
<?
$sql="SELECT org_ficha_directivo.cod_directivo, 
	org_ficha_directivo.n_documento, 
	org_ficha_directivo.cod_cargo, 
	org_ficha_directivo.f_termino
FROM org_ficha_directivo
WHERE org_ficha_directivo.cod_tipo_doc_org='$tipo' AND
org_ficha_directivo.n_documento_org='$numero'";
$result=mysql_query($sql) or die (mysql_error());
while($r4=mysql_fetch_array($result))
{
$cod=$r4['cod_directivo'];
?>		
		<tr>
		<td>
	    <select name="dir[<? echo $cod;?>]" class="eleven">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT org_ficha_usuario.n_documento, 
		org_ficha_usuario.nombre, 
		org_ficha_usuario.paterno, 
		org_ficha_usuario.materno
		FROM org_ficha_usuario
		WHERE org_ficha_usuario.cod_tipo_doc_org='$tipo' AND
		org_ficha_usuario.n_documento_org='$numero'
		ORDER BY org_ficha_usuario.nombre ASC";
		$result1=mysql_query($sql) or die (mysql_error());
		while($r5=mysql_fetch_array($result1))
		{
		?>
		<option value="<? echo $r5['n_documento'];?>" <? if ($r5['n_documento']==$r4['n_documento']) echo "selected";?>><? echo $r5['nombre']." ".$r5['paterno']." ".$r5['materno'];?></option>
		<?
		}
		?>
		</select>
		</td>
		<td>
		<select name="cargo[<? echo $cod;?>]" class="eleven">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT sys_bd_cargo_directivo.cod_cargo, 
		sys_bd_cargo_directivo.descripcion
		FROM sys_bd_cargo_directivo";
		$result2=mysql_query($sql) or die (mysql_error());
		while($r6=mysql_fetch_array($result2))
		{
		?>
		<option value="<? echo $r6['cod_cargo'];?>" <? if ($r6['cod_cargo']==$r4['cod_cargo']) echo "selected";?>><? echo $r6['descripcion'];?></option>
		<?
		}
		?>
		</select>	
		</td>
		<td><input name="vigencia[<? echo $cod;?>]" type="date" placeholder="aaaa-mm-dd" maxlength="10" class="ten"  value="<? echo $r4['f_termino'];?>"></td>
		<td><a href="gestor_organizacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&tipo=<? echo $tipo;?>&numero=<? echo $numero;?>&id=<? echo $r4['cod_directivo'];?>&action=DELETE_DIR" class="small alert button" onclick="return confirm('Va a eliminar permanentemente este registro, desea proceder ?')">Eliminar</a></td>
		</tr>
<?
}
?>		
<tr><td colspan="4"><h6>Puede añadir mas directivos desde aqui</h6></td></tr>
<?
for ($i = 1; $i <= 10; $i++) 
{
?>		
		<tr>
			<td>
				<select name="dira[]" class="eleven">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT org_ficha_usuario.n_documento, 
		org_ficha_usuario.nombre, 
		org_ficha_usuario.paterno, 
		org_ficha_usuario.materno
		FROM org_ficha_usuario
		WHERE org_ficha_usuario.cod_tipo_doc_org='$tipo' AND
		org_ficha_usuario.n_documento_org='$numero'
		ORDER BY org_ficha_usuario.nombre ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($r7=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $r7['n_documento'];?>"><? echo $r7['nombre']." ".$r7['paterno']." ".$r7['materno'];?></option>
		<?
		}
		?>
	</select>
	</td>
			<td>
					<select name="cargoa[]" class="eleven">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT sys_bd_cargo_directivo.cod_cargo, 
		sys_bd_cargo_directivo.descripcion
		FROM sys_bd_cargo_directivo";
		$result=mysql_query($sql) or die (mysql_error());
		while($r8=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $r8['cod_cargo'];?>"><? echo $r8['descripcion'];?></option>
		<?
		}
		?>
	</select>
			</td>
			<td><input type="date" name="vigenciaa[]" placeholder="aaaa-mm-dd" maxlength="10" class="date ten"></td>
			<td><br/></td>
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
<?
}
?>





</div>
</div>





    </div>

  </div>

  <!-- Footer -->
<? include("footer.php");?>


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
  <script src="javascripts/jquery.js"></script>
  <script src="javascripts/foundation.min.js"></script>
  
  <!-- Initialize JS Plugins -->
  <script src="javascripts/app.js"></script>
    <!-- VALIDADOR DE FORMULARIOS -->
<script src="plugins/validation/jquery.validate.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" media="screen" href="plugins/validation/stylesheet.css" />
<script type="text/javascript" src="plugins/validation/jquery.maskedinput.js"></script>
<script type="text/javascript" src="plugins/validation/mktSignup.js"></script>    
</body>
</html>
