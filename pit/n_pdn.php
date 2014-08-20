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
<? echo $error;?>


<!-- Iniciamos el contenido -->
<div class="row">
 <div class="twelve columns">
       
<!-- Contenedores -->
<dl class="tabs">
<dd  class="active">
<a href="">
<?
if ($modo==familia)
{
echo "2 de 3.- Asignacion de participantes al Plan de Negocio";
}
elseif($modo==indicadores)
{
echo "3 de 3.- Informacion general del Plan de Negocio";
}
else
{
echo "1 de 3.- Información de la propuesta del Plan de Negocio";
}
?>
</a>
</dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<?
if ($modo==familia)
{
?>
<? include("../plugins/buscar/buscador.html");?>	
<div class="twelve columns"><br/></div>
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

<form name="form5" id="form5" method="post" action="gestor_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=<? echo $modo;?>&cod=<? echo $cod;?>&action=ADD_FAM" onsubmit="return checkSubmit();">

<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<a href="n_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=indicadores&cod=<? echo $cod;?>" class="primary button">Siguiente >></a>
</div>
<div class="twelve columns"><br/></div>

	       
<table id="lista">
	<thead>
		<tr>
			<th>Nº</th>
			<th>DNI</th>
			<th class="ten">Nombres y apellidos</th>
			<th>Participa?</th>
			<th align="center"><span class="has-tip tip-top noradius" data-width="210" title="Seleccionar todos los campos"><input type="checkbox" name="checkbox" value="checkbox" onclick="seleccionar_todo(this);" /></span></th>
		</tr>
	</thead>
	
	<tbody>
<?
$num=0;
$sql="SELECT org_ficha_usuario.n_documento AS dni, 
		org_ficha_usuario.nombre, 
		org_ficha_usuario.paterno, 
		org_ficha_usuario.materno, 
		pit_bd_user_iniciativa.n_documento
		FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
		INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
		LEFT OUTER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = org_ficha_usuario.n_documento AND pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_pdn.cod_pdn
		WHERE pit_bd_ficha_pdn.cod_pdn='$cod'
		ORDER BY org_ficha_usuario.nombre ASC";
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
			<td>
			<?
				if($f1['n_documento']<>NULL)
				{
			?>
			<a href="gestor_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&id=<? echo $f1['dni'];?>&action=DELETE_USER" class="small alert button">Quitar</a>
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
</form>
<?
}
elseif($modo==indicadores)
{
?>
<form name="form5" class="custom" method="post" action="gestor_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=indicadores&cod=<? echo $cod;?>&action=ADD_IND" onsubmit="return checkSubmit();">

<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<?
	if ($tipo==1)
	{
	?>
	<a href="../seguimiento/pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="primary button">Finalizar</a>	
	<?
	}
	else
	{
	?>	
	<a href="pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="primary button">Finalizar</a>
	<?
	}
	?>
</div>
<div class="twelve columns"><hr/></div>

<div class="twelve columns"><h6>I.- Datos del negocio</h6></div>

<div class="twelve columns"><h6>1.1.- Situacion actual y perspectivas del negocio</h6></div>
<table>
	<tbody>
		<tr>
			<th>Nº</th>
			<th class="seven">Situacion actual</th>
			<th class="seven">Perspectiva del negocio</th>
			<th>Quitar</th>
		</tr>
		<?
		$na=0;
		$sql="SELECT * FROM pdn_situacion WHERE cod_pdn='$cod'";
		$result=mysql_query($sql) or die (mysql_error());
		while($f1=mysql_fetch_array($result))
		{
			$na++		
		?>
		<tr>
			<td><? echo $na;?></td>
			<td><? echo $f1['situacion_a'];?></td>
			<td><? echo $f1['situacion_b'];?></td>
			<td><a href="gestor_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=<? echo $modo;?>&cod=<? echo $cod;?>&id=<? echo $f1['cod'];?>&action=DELETE1" class="small alert button">Quitar</a></td>
		</tr>
		<?
		}
		?>
		<?
		for ($i=1;$i<=5;$i++)
		{
		?>
		<tr>
			<td>-</td>
			<td><input type="text" name="situaciona[]"></td>
			<td><input type="text" name="situacionb[]"></td>
			<td>-</td>
		</tr>
		<?
		}
		?>
	</tbody>
</table>

<div class="twelve columns"><h6>1.2.- Apoyo recibido en los 3 ultimos años</h6></div>
	<table>
		<tbody>
			<tr>
				<th>Nº</th>
				<th class="seven">Institucion</th>
				<th>Tiempo que apoyo</th>
				<th>Monto de apoyo(S/.)</th>
				<th>Quitar</th>
			</tr>
<?
$nb=0;
$sql="SELECT * FROM pdn_apoyo WHERE cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($f2=mysql_fetch_array($result))
{
	$nb++
?>			
			<tr>
				<td><? echo $nb;?></td>
				<td><? echo $f2['institucion'];?></td>
				<td><? echo $f2['mes'];?></td>
				<td><? echo $f2['tipo_apoyo'];?></td>
				<td><a href="gestor_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=indicadores&cod=<? echo $cod;?>&id=<? echo $f2['cod'];?>&action=DELETE_APOYO" class="small alert button">Quitar</a></td>
			</tr>
<?
}
?>			
<?
for($i=1;$i<=5;$i++)
{
?>			
			<tr>
				<td>-</td>
				<td><input type="text" name="institucion[]"></td>
				<td><input type="text" name="mes[]"></td>
				<td><input type="text" name="apoyo[]"></td>
				<td>-</td>
			</tr>
<?
}
?>			
		</tbody>
	</table>

<div class="twelve columns"><h6>1.3.- Eventos comerciales en los que a participado</h6></div>
<table>
	<tr>
		<th>Nº</th>
		<th class="six">Nombre del evento</th>
		<th class="six">Lugar del evento</th>
		<th>Quitar</th>
	</tr>
<?
$nc=0;
$sql="SELECT * FROM pdn_evento WHERE cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($f3=mysql_fetch_array($result))
{
$nc++
?>	
	<tr>
		<td><? echo $nc;?></td>
		<td><? echo $f3['evento'];?></td>
		<td><? echo $f3['lugar'];?></td>
		<td><a href="gestor_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=indicadores&cod=<? echo $cod;?>&id=<? echo $f3['cod'];?>&action=DELETE_EC" class="small alert button">Quitar</a></td>
	</tr>
<?
}
?>	
<?
for($i=0;$i<=5;$i++)
{
?>
	<tr>
		<td>-</td>
		<td><input type="text" name="evento[]"></td>
		<td><input type="text" name="lugar[]"></td>
		<td>-</td>
	</tr>
<?
}
?>
</table>

<div class="twelve columns"><h6>1.4.- La actividad del negocio y el medio ambiente</h6></div>
<?
$sql="SELECT * FROM pdn_ambiente WHERE cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$f4=mysql_fetch_array($result);
?>
<table>
	<tr>
		<th>Efectos ambientales</th>
		<th>Atributos</th>
		<th>Marcar</th>
		<th>Breve descripcion del atributo</th>
	</tr>
	
	<tr>
		<td rowspan="2">Efectos positivos</td>
		<td>La Actividad de negocio esta contribuyendo favorablemente a la protección y cuidado del medio ambiente</td>
		<td>
		<input type="hidden" name="codigo" value="<? echo $f4['cod'];?>"> 
		<input type="checkbox" name="opcion1" <? if ($f4['opcion_1']==1) echo "selected";?>></td>
		<td><input type="text" name="describe1" value="<? echo $f4['descripcion_1'];?>"></td>
	</tr>
	
	<tr>
		<td>La actividad de negocio se está beneficiando de las buenas prácticas de manejo ambiental de otros</td>
		<td><input type="checkbox" name="opcion2"  <? if ($f4['opcion_2']==1) echo "selected";?>></td>
		<td><input type="text" name="describe2" value="<? echo $f4['descripcion_2'];?>"></td>
	</tr>
	
	<tr>
		<td rowspan="2">Efectos negativos</td>
		<td>La actividad de negocio esta afectando negativamente el medio ambiente</td>
		<td><input type="checkbox" name="opcion3"  <? if ($f4['opcion_3']==1) echo "selected";?>></td>
		<td><input type="text" name="describe3" value="<? echo $f4['descripcion_3'];?>"></td>
	</tr>
	
	<tr>
		<td>La actividad de negocio se perjudica de las malas prácticas de manejo ambiental de otros</td>
		<td><input type="checkbox" name="opcion4"  <? if ($f4['opcion_4']==1) echo "selected";?>></td>
		<td><input type="text" name="describe4" value="<? echo $f4['descripcion_4'];?>"></td>
	</tr>
</table>
<div class="twelve columns"><h6>1.5.- Situacion patrimonial de la organizacion o de la personal natural</h6></div>

<table>
	<tr>
		<th>Nº</th>
		<th>Tipo</th>
		<th>Descripcion del patrimonio con el que cuenta la actividad del negocio</th>
		<th>Unidad de medida</th>
		<th>Cantidad</th>
		<th>Costo unitario</th>
		<th>Quitar</th>
	</tr>
<?
$nd=0;
$sql="SELECT * FROM pdn_patrimonio WHERE cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($f5=mysql_fetch_array($result))
{
$nd++
?>	
	<tr>
		<td><? echo $nd;?></td>
		<td><? if ($f5['tipo_patrimonio']==1) echo "Terrenos de uso en el negocio"; elseif($f5['tipo_patrimonio']==2) echo "Construcciones/instalaciones";elseif($f5['tipo_patrimonio']==3) echo "Maquinaria, equipo, herramientas"; elseif($f5['tipo_patrimonio']==4) echo "Ganado reproductor"; elseif($f5['tipo_patrimonio']==5) echo "Matreria prima/insumos"; else echo "Otro";?></td>
		<td><? echo $f5['descripcion'];?></td>
		<td><? echo $f5['unidad'];?></td>
		<td><? echo $f5['cantidad'];?></td>
		<td><? echo $f5['costo_unitario'];?></td>
		<td><a href="gestor_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=<? echo $modo;?>&cod=<? echo $cod;?>&id=<? echo $f5['cod'];?>&action=DELETE3" class="small alert button">Quitar</a></td>
	</tr>
<?
}
?>	
<?
for($i=1;$i<=5;$i++)
{
?>
	<tr>
		<td>-</td>
		<td><select name="tipo[]" class="medium required">
				    <option value="" selected="selected">Seleccionar</option>
				    <option value="1">Terrenos de uso en el negocio</option>
				    <option value="2">Construcciones / Instalaciones</option>
				    <option value="3">Maquinaria, equipo, herramientas</option>
				    <option value="4">Ganado reproductor</option>
				    <option value="5">Materia prima / insumos</option>
				    <option value="6">Otros</option>
			    </select></td>
		<td><input type="text" name="describepatrimonio[]"></td>
		<td><input type="text" name="unidad[]"></td>
		<td><input type="text" name="cantidad[]"></td>
		<td><input type="text" name="costo[]"></td>
		<td>-</td>
	</tr>
<?
}
?>
	
</table>

<div class="twelve columns"><h6>II.- Problemas o limitaciones y propuestas de solucion</h6></div>

<table>
	<tr>
		<th>Nº</th>
		<th>Tipo</th>
		<th class="five">Descripcion del problema</th>
		<th class="five">Propuesta de solucion</th>
		<th>Quitar</th>
	</tr>
<?
$ne=0;
$sql="SELECT * FROM pdn_problema WHERE cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error($result));
while($f6=mysql_fetch_array($result))
{
$ne++
?>	
	<tr>
		<td><? echo $ne;?></td>
		<td><?php  if ($f6['tipo']==1) echo "PROVISION DE MATERIAS PRIMAS"; elseif($f6['tipo']==2) echo "PROCESO PRODUCTIVO"; elseif($f6['tipo']==3) echo "COMERCIALIZACION"; else echo "OTROS PROBLEMAS";?></td>
		<td><? echo $f6['descripcion'];?></td>
		<td><? echo $f6['solucion'];?></td>
		<td><a href="gestor_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=<? echo $modo;?>&cod=<? echo $cod;?>&id=<? echo $f6['cod'];?>&action=DELETE_PROBLEM" class="small alert button">Quitar</a></td>
	</tr>
<?
}
?>	

<?
for($i=1;$i<=5;$i++)
{
?>
	<tr>
		<td>-</td>
		<td><select name="tipoproblem[]"><option value="" selected="selected">Seleccionar</option><option value="1">PROVISION DE MATERIAS PRIMAS</option><option value="2">PROCESO PRODUCTIVO</option><option value="3">COMERCIALIZACION</option><option value="4">OTROS PROBLEMAS</option></select></td>
		<td><input type="text" name="pro[]"></td>
		<td><input type="text" name="sol[]"></td>
		<td>-</td>
	</tr>
<?
}
?>	
</table>

</form>
<?
}
else
{
?>
<form name="form5" method="post" action="gestor_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=<? echo $modo;?>&action=ADD" onsubmit="return checkSubmit();">
<?
if ($modo==amp)
{
?>
<div class="two columns">Nº de contrato al que se anexara este PDN</div>
<div class="ten columns">
	<select name="pit" class="three">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT pit_bd_ficha_pit.cod_pit, 
		pit_bd_ficha_pit.n_contrato, 
		pit_bd_ficha_pit.f_contrato
		FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
		WHERE pit_bd_ficha_pit.n_contrato<>0 AND
		org_ficha_taz.cod_dependencia='".$row['cod_dependencia']."'
		ORDER BY pit_bd_ficha_pit.f_contrato ASC, pit_bd_ficha_pit.n_contrato ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($f2=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f2['cod_pit'];?>"><? echo numeracion($f2['n_contrato'])."-".periodo($f2['f_contrato']);?></option>
		<?
		}
		?>
	</select>
</div>
<div class="twelve columns"><hr/></div>
<?
}
?>

<div class="twelve columns">Seleccionar organizacion que presenta demanda de Plan de negocio</div>
<div class="twelve columns">
	<select name="org" class="hyjack">
		<option value="" selected="selected">Seleccionar</option>
		<?
		if ($modo==pit)
		{
			$sql="SELECT org_ficha_organizacion.cod_tipo_doc, 
			org_ficha_organizacion.n_documento, 
			org_ficha_organizacion.nombre, 
			pit_bd_ficha_pit.cod_estado_iniciativa, 
			pit_bd_ficha_pit.cod_pit
			FROM pit_bd_ficha_pit INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pit.cod_tipo_doc_taz = org_ficha_organizacion.cod_tipo_doc_taz AND pit_bd_ficha_pit.n_documento_taz = org_ficha_organizacion.n_documento_taz
			WHERE org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."' AND
			org_ficha_organizacion.cod_tipo_org<>6 AND
			pit_bd_ficha_pit.cod_estado_iniciativa=001
			ORDER BY org_ficha_organizacion.nombre ASC";
		}
		else
		{
			$sql="SELECT org_ficha_organizacion.cod_tipo_doc, 
			org_ficha_organizacion.n_documento, 
			org_ficha_organizacion.nombre
			FROM org_ficha_organizacion
			WHERE org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."' AND
			org_ficha_organizacion.cod_tipo_org<>6
			ORDER BY org_ficha_organizacion.nombre ASC";
		}
		$result=mysql_query($sql) or die (mysql_error());
		while($f1=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f1['cod_tipo_doc'].",".$f1['n_documento'];?>"><? echo $f1['nombre'];?></option>
		<?
		}
		?>
	</select>
</div>

<div class="twelve columns"><br/></div>

<div class="two columns">Linea de negocio</div>
<div class="four columns">
	<select name="linea" class="required ten">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT * FROM sys_bd_linea_pdn ORDER BY descripcion";
		$result=mysql_query($sql) or die (mysql_error());
		while($f3=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f3['cod_linea_pdn'];?>"><? echo $f3['descripcion'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="two columns">Duracion en meses</div>
<div class="four columns"><input type="text" name="duracion" class="five required digits"></div>
<div class="two columns">Denominación</div>
<div class="ten columns"><input type="text" name="denominacion" class="required"></div>
<div class="twelve columns"><h6>II.- Propuesta de actividades</h6></div>
<div class="twelve columns"><h6>2.1.- Asistencia Tecnica</h6></div>
<!-- Asistencia tecnica -->
<div class="twelve columns">
<ul class="accordion">
<?
for($i=1;$i<=5;$i++)
{
?>
<li>
<div class="title"><h5>Requerimiento de SAT Nº <? echo $i;?></h5></div>
<div class="content">
<div class="row">
<div class="two columns">Rubro del SAT</div>
<div class="ten columns"><input type="text" name="rubro[]"></div>
<div class="two columns">Resultado esperado con el SAT</div>
<div class="ten columns"><input type="text" name="resultado[]"></div>
<div class="two columns">Especialista en</div>
<div class="ten columns"><input type="text" name="especialista[]"></div>
<div class="two columns">Nº de dias a la semana</div>
<div class="four columns"><input type="text" name="dia[]" class="five digits"></div>
<div class="two columns">Costo por día</div>
<div class="four columns"><input type="text" name="costo[]" class="five number"></div>
<div class="two columns">Nº de semanas al mes</div>
<div class="four columns"><input type="text" name="semana[]" class="five digits"></div>
<div class="two columns">Nº de meses</div>
<div class="four columns"><input type="text" name="mes[]" class="five digits"></div>
<div class="two columns">% de aporte NEC PDSS</div>
<div class="four columns"><input type="text" name="ppdss[]" class="number five"></div>
<div class="two columns">% de aporte Organizacion</div>
<div class="four columns"><input type="text" name="porg[]" class="number five"></div>
<div class="twelve columns"><h6>Cronograma de ejecución del SAT</h6></div>
<table>
	<tbody>
	<tr>
		<th class="one">Ene</th>
		<th class="one">Feb</th>
		<th class="one">Mar</th>
		<th class="one">Abr</th>
		<th class="one">May</th>
		<th class="one">Jun</th>
		<th class="one">Jul</th>
		<th class="one">Ago</th>
		<th class="one">Sep</th>
		<th class="one">Oct</th>
		<th class="one">Nov</th>
		<th class="one">Dic</th>
	</tr>
	
	<tr>
		<td><input type="checkbox" name="ma[]" value="1"></td>
		<td><input type="checkbox" name="mb[]" value="1"></td>
		<td><input type="checkbox" name="mc[]" value="1"></td>
		<td><input type="checkbox" name="md[]" value="1"></td>
		<td><input type="checkbox" name="me[]" value="1"></td>
		<td><input type="checkbox" name="mf[]" value="1"></td>
		<td><input type="checkbox" name="mg[]" value="1"></td>
		<td><input type="checkbox" name="mh[]" value="1"></td>
		<td><input type="checkbox" name="mi[]" value="1"></td>
		<td><input type="checkbox" name="mj[]" value="1"></td>
		<td><input type="checkbox" name="mk[]" value="1"></td>
		<td><input type="checkbox" name="ml[]" value="1"></td>
	</tr>
		
	</tbody>
</table>
</div>
</div>
</li>
<?
}
?>
</ul>
</div>
<!-- fin asistencia tecnica -->
<div class="twelve columns"><h6>2.2.- Visita Guiada</h6></div>
<!-- Visita guiada -->
<div class="two columns">Fecha de visita</div>
<div class="four columns"><input type="date" name="f_visita" class="five date" placeholder="aaaa-mm-dd" maxlength="10"></div>
<div class="two columns">Nº de participantes</div>
<div class="four columns"><input type="text" name="n_participantes" class="five digits"></div>
<div class="two columns">Aporte NEC PDSS (S/.)</div>
<div class="four columns"><input type="text" name="aporte_pdss" class="five number" value="2310"></div>
<div class="two columns">Aporte Organizacion (S/.)</div>
<div class="four columns"><input type="text" name="aporte_org" class="five number" value="990"></div>
<div class="twelve columns">Resultados esperados</div>
<div class="twelve columns"><textarea name="resultado_visita" rows="3"></textarea></div>
<div class="twelve columns">Propuesta de lugares a visitar</div>
<div class="twelve columns"><textarea name="lugar_visita" rows="3"></textarea></div>

<!-- participacion en ferias -->
<div class="twelve columns"><h6>2.3.- Participacion en Ferias</h6></div>

<div class="twelve columns"><h6>Propuesta de participacion en feria Nº 1</h6></div>
<div class="two columns">Fecha</div>
<div class="four columns"><input type="date" name="fecha_fer_1" class="date five" placeholder="aaaa-mm-dd" maxlength="10"></div>
<div class="two columns">Nº de participantes</div>
<div class="four columns"><input type="text" name="n_part_1" class="digits five"></div>
<div class="two columns">Nombre del evento</div>
<div class="ten columns"><input type="text" name="evento_1"></div>
<div class="two columns">Lugar donde se realiza</div>
<div class="ten columns"><input type="text" name="lugar_1"></div>
<div class="two columns">Aporte NEC PDSS (S/.)</div>
<div class="four columns"><input type="text" name="aporte_pdss_1" class="number five"></div>
<div class="two columns">Aporte Organizacion (S/.)</div>
<div class="four columns"><input type="text" name="aporte_org_1" class="number five"></div>

<div class="twelve columns"><hr/></div>

<div class="twelve columns"><h6>Propuesta de participacion en feria Nº 2</h6></div>
<div class="two columns">Fecha</div>
<div class="four columns"><input type="date" name="fecha_fer_2" class="date five" placeholder="aaaa-mm-dd" maxlength="10"></div>
<div class="two columns">Nº de participantes</div>
<div class="four columns"><input type="text" name="n_part_2" class="digits five"></div>
<div class="two columns">Nombre del evento</div>
<div class="ten columns"><input type="text" name="evento_2"></div>
<div class="two columns">Lugar donde se realiza</div>
<div class="ten columns"><input type="text" name="lugar_2"></div>
<div class="two columns">Aporte NEC PDSS (S/.)</div>
<div class="four columns"><input type="text" name="aporte_pdss_2" class="number five"></div>
<div class="two columns">Aporte Organizacion (S/.)</div>
<div class="four columns"><input type="text" name="aporte_org_2" class="number five"></div>
<div class="twelve columns"><hr/></div>
<div class="twelve columns"><h6>III.- Informacion Financiera</h6></div>
<div class="two columns">Nº de cuenta</div>
<div class="four columns"><input type="text" name="n_cuenta" class="five required"></div>
<div class="two columns">Banco</div>
<div class="four columns">
	<select name="ifi" class="required ten">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT * FROM sys_bd_ifi ORDER BY descripcion";
		$result=mysql_query($sql) or die (mysql_error());
		while($f4=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f4['cod_ifi'];?>"><? echo $f4['descripcion'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="twelve columns"><br/></div>
<div class="two columns">Nº de voucher</div>
<div class="four columns"><input type="text" name="n_voucher" class="five required"></div>
<div class="two columns">Monto depositado por la organización (S/.)</div>
<div class="four columns"><input type="text" name="monto_org" class="five required number"></div>
<div class="two columns">Fte. FIDA (%)</div>
<div class="four columns"><input type="text" name="fida" class="five required number"></div>
<div class="two columns">Fte. RO (%)</div>
<div class="four columns"><input type="text" name="ro" class="five required number"></div>
<div class="twelve columns"><br/></div>


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
					while($r1=mysql_fetch_array($result1))
					{
					?>
					<option value="<? echo $r1['cod_ente'];?>"><? echo $r1['descripcion'];?></option>
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



<div class="twelve columns">
<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
<button type="button" class="secondary button" onclick="document.form5.btn_envia.disabled=!document.form5.btn_envia.disabled">Desbloquear</button>
<a href="pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button">Cancelar</a>
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

<?
if ($modo<>familia)
{
?>
<!-- Combo Buscador -->
<link href="../plugins/combo_buscador/hyjack.css" rel="stylesheet" type="text/css" />
<script src="../plugins/combo_buscador/hyjack_externo.js" type="text/javascript"></script>
<script src="../plugins/combo_buscador/configuracion.js" type="text/javascript"></script>
<?
}
?>

</body>
</html>
