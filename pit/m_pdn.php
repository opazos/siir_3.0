<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);

$sql="SELECT * FROM pit_bd_ficha_pdn WHERE cod_pdn='$id'";
$result=mysql_query($sql) or die (myql_error());
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
<form name="form5" class="custom" method="post" action="gestor_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=<? echo $modo;?>&cod=<? echo $cod;?>&action=ADD_FAM" onsubmit="return checkSubmit();">

<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<a href="n_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=indicadores&cod=<? echo $cod;?>" class="primary button">Siguiente >></a>
</div>
<div class="twelve columns"><br/></div>

	       
<table class="responsive" id="lista">
	<tbody>
		<tr>
			<th>Nº</th>
			<th>DNI</th>
			<th class="ten">Nombres y apellidos</th>
			<th>Participa?</th>
		</tr>
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
	<button type="submit" class="success button">Guardar cambios</button>
	<a href="pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="primary button">Finalizar</a>
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
				<td><a href="" class="small alert button">Quitar</a></td>
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
		<td><a href="" class="small alert button">Quitar</a></td>
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
		<td>-</td>
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
		<th><? echo $ne;?></th>
		<th><?php  if ($f6['tipo']==1) echo "PROVISION DE MATERIAS PRIMAS"; elseif($f6['tipo']==2) echo "PROCESO PRODUCTIVO"; elseif($f6['tipo']==3) echo "COMERCIALIZACION"; else echo "OTROS PROBLEMAS";?></th>
		<th><? echo $f6['descripcion'];?></th>
		<th><? echo $f6['solucion'];?></th>
		<th><a href="" class="small alert button">Quitar</a></th>
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
<form name="form5" class="custom" method="post" action="gestor_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=<? echo $modo;?>&action=UPDATE" onsubmit="return checkSubmit();">


<div class="twelve columns">Seleccionar organizacion que presenta demanda de Plan de negocio</div>
<div class="twelve columns">

<input type="hidden" name="codigo" value="<? echo $row['cod_pdn'];?>">

	<select name="org">
		<option value="" selected="selected">Seleccionar</option>
		<?
			$sql="SELECT org_ficha_organizacion.cod_tipo_doc, 
			org_ficha_organizacion.n_documento, 
			org_ficha_organizacion.nombre
			FROM org_ficha_organizacion
			WHERE org_ficha_organizacion.cod_dependencia='".$row1['cod_dependencia']."' AND
			org_ficha_organizacion.cod_tipo_org<>6
			ORDER BY org_ficha_organizacion.nombre ASC";
			$result=mysql_query($sql) or die (mysql_error());
			while($f1=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f1['cod_tipo_doc'].",".$f1['n_documento'];?>" <? if ($row['cod_tipo_doc_org']==$f1['cod_tipo_doc'] and $row['n_documento_org']==$f1['n_documento']) echo "selected";?>><? echo $f1['nombre'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="two columns">Linea de negocio</div>
<div class="four columns">
	<select name="linea">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT * FROM sys_bd_linea_pdn ORDER BY descripcion";
		$result=mysql_query($sql) or die (mysql_error());
		while($f3=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f3['cod_linea_pdn'];?>" <? if ($f3['cod_linea_pdn']==$row['cod_linea_pdn']) echo "selected";?>><? echo $f3['descripcion'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="two columns">Duracion en meses</div>
<div class="four columns"><input type="text" name="duracion" class="five required digits" value="<? echo $row['mes'];?>"></div>
<div class="two columns">Denominación</div>
<div class="ten columns"><input type="text" name="denominacion" class="required" value="<? echo $row['denominacion'];?>"></div>
<div class="twelve columns"><h6>II.- Propuesta de actividades</h6></div>
<div class="twelve columns"><h6>2.1.- Asistencia Tecnica</h6></div>







<div class="twelve columns">
<ul class="accordion">
<?
$sql="SELECT pit_bd_at_pdn.cod_at, 
	pit_bd_at_pdn.rubro, 
	pit_bd_at_pdn.resultado, 
	pit_bd_at_pdn.rubro_especialista, 
	pit_bd_at_pdn.n_dia, 
	pit_bd_at_pdn.costo_dia, 
	pit_bd_at_pdn.n_semana, 
	pit_bd_at_pdn.n_mes, 
	((pit_bd_at_pdn.aporte_pdss/(pit_bd_at_pdn.aporte_pdss+pit_bd_at_pdn.aporte_org))*100) AS pp_pdss, 
	((pit_bd_at_pdn.aporte_org/(pit_bd_at_pdn.aporte_pdss+pit_bd_at_pdn.aporte_org))*100) AS pp_org, 
	pit_bd_at_pdn.aporte_pdss, 
	pit_bd_at_pdn.aporte_org, 
	pit_bd_at_pdn.ene, 
	pit_bd_at_pdn.feb, 
	pit_bd_at_pdn.mar, 
	pit_bd_at_pdn.abr, 
	pit_bd_at_pdn.may, 
	pit_bd_at_pdn.jun, 
	pit_bd_at_pdn.jul, 
	pit_bd_at_pdn.ago, 
	pit_bd_at_pdn.sep, 
	pit_bd_at_pdn.oct, 
	pit_bd_at_pdn.nov, 
	pit_bd_at_pdn.dic, 
	pit_bd_at_pdn.aporte_total
FROM pit_bd_at_pdn
WHERE cod_pdn='$id'";
$result=mysql_query($sql) or die (mysql_error());
while($r2=mysql_fetch_array($result))
{
$cod=$r2['cod_at'];
?>
<li>
<div class="title"><h5>Requerimiento de SAT : <? echo $r2['rubro'];?></h5></div>
<div class="content">
<div class="row">
<div class="two columns">Rubro del SAT</div>
<div class="ten columns"><input type="text" name="rubros[<? echo $cod;?>]" value="<? echo $r2['rubro'];?>"></div>
<div class="two columns">Resultado esperado con el SAT</div>
<div class="ten columns"><input type="text" name="resultados[<? echo $cod;?>]" value="<? echo $r2['resultado'];?>"></div>
<div class="two columns">Especialista en</div>
<div class="ten columns"><input type="text" name="especialistas[<? echo $cod;?>]" value="<? echo $r2['rubro_especialista'];?>"></div>
<div class="two columns">Nº de dias a la semana</div>
<div class="four columns"><input type="text" name="dias[<? echo $cod;?>]" class="five number" value="<? echo $r2['n_dia'];?>"></div>
<div class="two columns">Costo por día</div>
<div class="four columns"><input type="text" name="costos[<? echo $cod;?>]" class="five number" value="<? echo $r2['costo_dia'];?>"></div>
<div class="two columns">Nº de semanas al mes</div>
<div class="four columns"><input type="text" name="semanas[<? echo $cod;?>]" class="five number" value="<? echo $r2['n_semana'];?>"></div>
<div class="two columns">Nº de meses</div>
<div class="four columns"><input type="text" name="mess[<? echo $cod;?>]" class="five number" value="<? echo $r2['n_mes'];?>"></div>
<div class="two columns">% de aporte NEC PDSS</div>
<div class="four columns"><input type="text" name="ppdsss[<? echo $cod;?>]" class="number five" value="<? echo $r2['pp_pdss'];?>"></div>
<div class="two columns">% de aporte Organizacion</div>
<div class="four columns"><input type="text" name="porgs[<? echo $cod;?>]" class="number five" value="<? echo $r2['pp_org'];?>"></div>
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
		<td><input type="checkbox" name="mas[<? echo $cod;?>]" value="1" <? if ($r2['ene']==1) echo "checked";?>></td>
		<td><input type="checkbox" name="mbs[<? echo $cod;?>]" value="1" <? if ($r2['feb']==1) echo "checked";?>></td>
		<td><input type="checkbox" name="mcs[<? echo $cod;?>]" value="1" <? if ($r2['mar']==1) echo "checked";?>></td>
		<td><input type="checkbox" name="mds[<? echo $cod;?>]" value="1" <? if ($r2['abr']==1) echo "checked";?>></td>
		<td><input type="checkbox" name="mes[<? echo $cod;?>]" value="1" <? if ($r2['may']==1) echo "checked";?>></td>
		<td><input type="checkbox" name="mfs[<? echo $cod;?>]" value="1" <? if ($r2['jun']==1) echo "checked";?>></td>
		<td><input type="checkbox" name="mgs[<? echo $cod;?>]" value="1" <? if ($r2['jul']==1) echo "checked";?>></td>
		<td><input type="checkbox" name="mhs[<? echo $cod;?>]" value="1" <? if ($r2['ago']==1) echo "checked";?>></td>
		<td><input type="checkbox" name="mis[<? echo $cod;?>]" value="1" <? if ($r2['sep']==1) echo "checked";?>></td>
		<td><input type="checkbox" name="mjs[<? echo $cod;?>]" value="1" <? if ($r2['oct']==1) echo "checked";?>></td>
		<td><input type="checkbox" name="mks[<? echo $cod;?>]" value="1" <? if ($r2['nov']==1) echo "checked";?>></td>
		<td><input type="checkbox" name="mls[<? echo $cod;?>]" value="1" <? if ($r2['dic']==1) echo "checked";?>></td>
	</tr>	
</tbody>
</table>
</div>
</div>
</li>
<?
}
?>



<?
for($i=1;$i<=1;$i++)
{
?>
<li>
<div class="title"><h5>Nuevo Requerimiento de SAT Nº <? echo $i;?></h5></div>
<div class="content">
<div class="row">
<div class="two columns">Rubro del SAT</div>
<div class="ten columns"><input type="text" name="rubro[]"></div>
<div class="two columns">Resultado esperado con el SAT</div>
<div class="ten columns"><input type="text" name="resultado[]"></div>
<div class="two columns">Especialista en</div>
<div class="ten columns"><input type="text" name="especialista[]"></div>
<div class="two columns">Nº de dias a la semana</div>
<div class="four columns"><input type="text" name="dia[]" class="five number"></div>
<div class="two columns">Costo por día</div>
<div class="four columns"><input type="text" name="costo[]" class="five number"></div>
<div class="two columns">Nº de semanas al mes</div>
<div class="four columns"><input type="text" name="semana[]" class="five number"></div>
<div class="two columns">Nº de meses</div>
<div class="four columns"><input type="text" name="mes[]" class="five number"></div>
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

<?
$sql="SELECT * FROM pit_bd_visita_pdn WHERE cod_pdn='$id'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);
?>
<div class="twelve columns"><h6>2.2.- Visita Guiada</h6></div>
<!-- Visita guiada -->
<div class="two columns">Fecha de visita</div>
<div class="four columns">
<input type="hidden" name="cod_visita" value="<? echo $r1['cod_visita_pdn'];?>">
<input type="date" name="f_visita" class="five date" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $r1['f_visita'];?>"></div>
<div class="two columns">Nº de participantes</div>
<div class="four columns"><input type="text" name="n_participantes" class="five digits" value="<? echo $r1['participantes'];?>"></div>
<div class="two columns">Aporte NEC PDSS (S/.)</div>
<div class="four columns"><input type="text" name="aporte_pdss" class="five number" value="<? echo $r1['aporte_pdss'];?>"></div>
<div class="two columns">Aporte Organizacion (S/.)</div>
<div class="four columns"><input type="text" name="aporte_org" class="five number" value="<? echo $r1['aporte_org'];?>"></div>
<div class="twelve columns">Resultados esperados</div>
<div class="twelve columns"><textarea name="resultado_visita" rows="3"><? echo $r1['resultados'];?></textarea></div>
<div class="twelve columns">Propuesta de lugares a visitar</div>
<div class="twelve columns"><textarea name="lugar_visita" rows="3"><? echo $r1['itinerario'];?></textarea></div>

<!-- participacion en ferias -->
<div class="twelve columns"><h6>2.3.- Participacion en Ferias</h6></div>

<?
$nf=0;
$sql="SELECT * FROM pit_bd_feria_pdn WHERE cod_pdn='$id'";
$result=mysql_query($sql) or die (mysql_error());
while($r3=mysql_fetch_array($result))
{
$cad=$r3['cod_feria_pdn'];
$nf++
?>
<div class="twelve columns"><h6>Propuesta de participacion en feria Nº <? echo $nf;?></h6></div>
<div class="two columns">Fecha</div>
<div class="four columns"><input type="date" name="fecha_fer[<? echo $cad;?>]" class="date five" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $r3['f_realizacion'];?>"></div>
<div class="two columns">Nº de participantes</div>
<div class="four columns"><input type="text" name="n_part[<? echo $cad;?>]" class="digits five" value="<? echo $r3['participantes'];?>"></div>
<div class="two columns">Nombre del evento</div>
<div class="ten columns"><input type="text" name="evento[<? echo $cad;?>]" value="<? echo $r3['nombre'];?>"></div>
<div class="two columns">Lugar donde se realiza</div>
<div class="ten columns"><input type="text" name="lugar[<? echo $cad;?>]" value="<? echo $r3['lugar'];?>"></div>
<div class="two columns">Aporte NEC PDSS (S/.)</div>
<div class="four columns"><input type="text" name="aporte_necs[<? echo $cad;?>]" class="number five" value="<? echo $r3['aporte_pdss'];?>"></div>
<div class="two columns">Aporte Organizacion (S/.)</div>
<div class="four columns"><input type="text" name="aporte_pdns[<? echo $cad;?>]" class="number five" value="<? echo $r3['aporte_org'];?>"></div>
<div class="twelve columns"><hr/></div>
<?
}
?>
<div class="twelve columns"><h6>III.- Informacion Financiera</h6></div>
<div class="two columns">Nº de cuenta</div>
<div class="four columns"><input type="text" name="n_cuenta" class="five required" value="<? echo $row['n_cuenta'];?>"></div>
<div class="two columns">Banco</div>
<div class="four columns">
	<select name="ifi">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT * FROM sys_bd_ifi ORDER BY descripcion";
		$result=mysql_query($sql) or die (mysql_error());
		while($f4=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f4['cod_ifi'];?>" <? if ($row['cod_ifi']==$f4['cod_ifi']) echo "selected";?>><? echo $f4['descripcion'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="twelve columns"><br/></div>
<div class="two columns">Nº de voucher</div>
<div class="four columns"><input type="text" name="n_voucher" class="five required" value="<? echo $row['n_voucher'];?>"></div>
<div class="two columns">Monto depositado por la organización (S/.)</div>
<div class="four columns"><input type="text" name="monto_org" class="five required number" value="<? echo $row['monto_organizacion'];?>"></div>
<div class="two columns">Fte. FIDA (%)</div>
<div class="four columns"><input type="text" name="fida" class="five required number" value="<? echo $row['fuente_fida'];?>"></div>
<div class="two columns">Fte. RO (%)</div>
<div class="four columns"><input type="text" name="ro" class="five required number" value="<? echo $row['fuente_ro'];?>"></div>
<div class="twelve columns"><br/></div>


<div class="twelve columns"><h6>IV.- Entidades Cofinanciadoras</h6></div>

<table class="responsive">
	<thead>
		<tr>
			<th class="seven">Nombre de la Entidad</th>
			<th>Tipo de Ente</th>
			<th>Monto de Aporte (S/.)</th>
		</tr>
	</thead>
	
	<tbody>
<?
$sql="SELECT * FROM  pit_bd_cofi_pdn WHERE cod_pdn='$id'";
$result=mysql_query($sql) or die (mysql_error());
while($r4=mysql_fetch_array($result))
{
	$cad=$r4['cod_cofinanciador'];
?>	
<tr>
	<td><input type="text" name="entes[<? echo $cad;?>]" value="<? echo $r4['nombre'];?>"></td>
	<td>
		<select name="tipo_entes[<? echo $cad;?>]">
			<option value="" selected="selected">Seleccionar</option>
			<?
			$sql="SELECT * FROM sys_bd_ente_cofinanciador ORDER BY descripcion";
			$result2=mysql_query($sql) or die (mysql_error());
			while($r2=mysql_fetch_array($result2))
			{
			?>
			<option value="<? echo $r2['cod_ente'];?>" <? if ($r2['cod_ente']==$r4['cod_tipo_ente']) echo "selected";?>><? echo $r2['descripcion'];?></option>
			<?
			}
			?>
		</select>
	</td>
	<td><input type="text" name="aporte_entes[<? echo $cad;?>]" value="<? echo $r4['aporte'];?>"></td>
</tr>	
<?
}
?>	
	
	
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
<a href="n_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=familia&cod=<? echo $row['cod_pdn'];?>" class="primary button">Asignacion de participantes >></a>
<a href="pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="primary button">Cancelar</a>
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

</body>
</html>
