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
<dd class="active"><a href="#simple1">Registro de calificaciones</a></dd>
<dd><a href="#simple2">Calificaciones ingresadas</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" action="gestor_calif_pdn_joven.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=ADD&modo=PRIMERO_CLAR" onsubmit="return checkSubmit();">
<div class="two columns">Seleccionar PDN</div>
<div class="four columns">
	<select name="iniciativa" class="hyjack">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT org_ficha_organizacion.n_documento, 
		org_ficha_organizacion.nombre, 
		pit_bd_ficha_pdn.denominacion, 
		clar_bd_ficha_pdn_suelto.cod_ficha_pdn_clar
		FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
		INNER JOIN clar_bd_ficha_pdn_suelto ON pit_bd_ficha_pdn.cod_pdn = clar_bd_ficha_pdn_suelto.cod_pdn
		WHERE clar_bd_ficha_pdn_suelto.cod_clar='$id' AND
		pit_bd_ficha_pdn.tipo=2";
	 	$result=mysql_query($sql) or die (mysql_error());
	 	while($f1=mysql_fetch_array($result))
	 	{
		?>
		<option value="<? echo $f1['cod_ficha_pdn_clar'];?>"><? echo $f1['nombre']." ".$f1['denominacion'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="two columns">Seleccionar Jurado</div>
<div class="four columns">
	<select name="jurado" class="hyjack">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT clar_bd_jurado_evento_clar.cod_jurado, 
		clar_bd_miembro.n_documento, 
		clar_bd_miembro.nombre, 
		clar_bd_miembro.paterno, 
		clar_bd_miembro.materno
		FROM clar_bd_miembro INNER JOIN clar_bd_jurado_evento_clar ON clar_bd_miembro.cod_tipo_doc = clar_bd_jurado_evento_clar.cod_tipo_doc AND clar_bd_miembro.n_documento = clar_bd_jurado_evento_clar.n_documento
		WHERE clar_bd_jurado_evento_clar.cod_clar='$id' AND
		clar_bd_jurado_evento_clar.calif_clar=1
		ORDER BY clar_bd_miembro.nombre ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($f2=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f2['cod_jurado'];?>"><? echo $f2['nombre']." ".$f2['paterno']." ".$f2['materno'];?></option>
		<?
		}
		?>
	</select>
</div>

<div class="twelve columns"><hr/></div>

<table>
<thead>
	<tr>
		<th>EJES</th>
		<th>CRITERIOS</th>
		<th>DESCRIPCION</th>
		<th>PUNTAJE MAXIMO</th>
		<th>PUNTAJE</th>
	</tr>
</thead>


      <tbody>
        <tr>
          <td colspan="1" rowspan="9">I.- Organización Responsable del PDN (40
            puntos)<br>
          </td>
          <td colspan="1" rowspan="3">1.1 Composición de la organización (15
            puntos)<br>
          </td>
          <td>La Organización está compuesta integramente por jóvenes entre 18 y
            29 años<br>
          </td>
          <td class="centrado">15
          </td>
          <td colspan="1" rowspan="3"><input type="text" name="p1" id="info" class="mini required number">
          </td>
        </tr>
        <tr>
          <td>La Organización esta compuesta por jóvenes entre 18 y 29 años y
            adultos mayores de 30 años, pero los jóvenes son mayoria.<br>
          </td>
          <td class="centrado">10
          </td>
        </tr>
        <tr>
          <td>La Organización está compuesta por adultos mayores de 30 años y
            jóvenes entre 18 y 29 años, pero los adultos son mayoría.<br>
          </td>
          <td class="centrado">8
          </td>
        </tr>
        <tr>
          <td colspan="1" rowspan="2">1.2 Directivos responsables (7 puntos)<br>
          </td>
          <td>Se cumple que (la) Presidente(a) y Tesorero(a) son jóvenes entre
            18 y 29 años<br>
          </td>
          <td class="centrado">7
          </td>
          <td colspan="1" rowspan="2"><input type="text" name="p2" id="info" class="mini required number"></td>
        </tr>
        <tr>
          <td>No se cumple el requisito anterior, es necesario cambiar la
            composición de la Junta directiva<br>
          </td>
          <td class="centrado">4
          </td>
        </tr>
        <tr>
          <td colspan="1" rowspan="2">1.3 Personería Jurídica (8 puntos)<br>
          </td>
          <td>La Personería jurídica esta formalizada<br>
          </td>
          <td class="centrado">8
          </td>
          <td colspan="1" rowspan="2"><input type="text" name="p3" id="info" class="mini required number"></td>
        </tr>
        <tr>
          <td>La Personería jurídica esta en vías de formalización<br>
          </td>
          <td class="centrado">5
          </td>
        </tr>
        <tr>
          <td colspan="1" rowspan="2">1.4 Participación de Mujeres (10 puntos)<br>
          </td>
          <td>Las mujeres que integran la organización son la mitad o más, del
            total de socios<br>
          </td>
          <td class="centrado">10
          </td>
          <td colspan="1" rowspan="2"><input type="text" name="p4" id="info" class="mini required number"></td>
        </tr>
        <tr>
          <td>Las mujeres que integran la organización son menos de la mitad del
            total de socios<br>
          </td>
          <td class="centrado">6
          </td>
        </tr>
        <tr>
          <td colspan="1" rowspan="6">II.- Coherencia del Plan de Negocio (45
            puntos)<br>
          </td>
          <td>2.1 Coherencia del PDN
          </td>
          <td>Refleja de manera objetiva la coherencia de actividades del
            pdn&nbsp; y su perspectiva de desarrollo y sostenibilidad<br>
          </td>
          <td class="centrado">10
          </td>
          <td><input type="text" name="p5" id="info" class="mini required number"></td>
        </tr>
        <tr>
          <td>2.2 Plazo de ejecución</td>
          <td>Se verifica si existen las condiciones para el cumplimiento de los
            resultados esperados en el plazo propuesto en el PDN</td>
          <td class="centrado">6
          </td>
          <td><input type="text" name="p6" id="info" class="mini required number"></td>
        </tr>
        <tr>
          <td>2.3 Actividades conexas al PDN</td>
          <td>Pertinencia de las visitas guiadas y participación en ferias con
            la propuesta del PDN</td>
          <td class="centrado">6
          </td>
          <td><input type="text" name="p7" id="info" class="mini required number"></td>
        </tr>
        <tr>
          <td>2.4 Inversiones en Activos Fijos y/o Capital de Trabajo</td>
          <td>Evalúa la coherencia de las inversiones propuestas y el monto de
            Préstamo propuesto</td>
          <td class="centrado">10
          </td>
          <td><input type="text" name="p8" id="info" class="mini required number"></td>
        </tr>
        <tr>
          <td>2.5 Aportes para cofinanciar el PDN</td>
          <td>Percepción de que hay posibilidades reales de cumplir con los
            aportes para el PDN</td>
          <td class="centrado">7
          </td>
          <td><input type="text" name="p9" id="info" class="mini required number"></td>
        </tr>
        <tr>
          <td>2.6 Contribución al Medio Ambiente<br>
          </td>
          <td>Percepción de que el PDN no afecta el medio ambiente o contribuye
            favorablemente</td>
          <td class="centrado">6
          </td>
          <td><input type="text" name="p10" id="info" class="mini required number"></td>
        </tr>
        <tr>
          <td colspan="1" rowspan="3">III.- Presentación del PDN (15 puntos)</td>
          <td>3.1 Claridad</td>
          <td>Evalúa la claridad en la presentación del pdn, utilizando los
            medios que son accesibles o ingeniados por el grupo (fotos, mapas,
            productos, sociodrama, etc)</td>
          <td class="centrado">6
          </td>
          <td><input type="text" name="p11" id="info" class="mini required number"></td>
        </tr>
        <tr>
          <td>3.2 Identidad y cultura<br>
          </td>
          <td>Evalúa los aspectos socioculturales que se reflejan en la
            presentación del grupo (vestimenta, idioma, música y danza)</td>
          <td class="centrado">5
          </td>
          <td><input type="text" name="p12" id="info" class="mini required number"></td>
        </tr>
        <tr>
          <td>3.3 Género<br>
          </td>
          <td>Evalúa el nivel de participación de varones y mujeres de manera
            equitativa</td>
          <td class="centrado">4
          </td>
          <td><input type="text" name="p13" id="info" class="mini required number"></td>
        </tr>
      </tbody>
    </table>
          
      <div class="twelve columns"><br/></div>
      <div class="twelve columns">
	      <button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	      <a href="calif_clar_primero.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button">Finalizar</a>
      </div>

	
</form>
</div>
</li>

<li id="simple2Tab">

<table>
	<thead>
		<tr>
			<th>Nº</th>
			<th>Nº Documento</th>
			<th>Nombre de la Organización</th>
			<th>Nombre del Jurado</th>
			<th>Puntaje</th>
			<th><br/></th>
		</tr>
	</thead>
	
	<tbody>
	<?
	$num=0;
	$sql="SELECT clar_calif_ficha_pdn_joven.total_puntaje, 
	pit_bd_ficha_pdn.denominacion, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre AS organizacion, 
	clar_bd_miembro.nombre, 
	clar_bd_miembro.paterno, 
	clar_bd_miembro.materno, 
	clar_calif_ficha_pdn_joven.cod
FROM clar_bd_ficha_pdn_suelto INNER JOIN clar_calif_ficha_pdn_joven ON clar_bd_ficha_pdn_suelto.cod_ficha_pdn_clar = clar_calif_ficha_pdn_joven.cod_ficha_pdn_clar
	 INNER JOIN clar_bd_jurado_evento_clar ON clar_calif_ficha_pdn_joven.cod_jurado = clar_bd_jurado_evento_clar.cod_jurado
	 INNER JOIN clar_bd_miembro ON clar_bd_miembro.cod_tipo_doc = clar_bd_jurado_evento_clar.cod_tipo_doc AND clar_bd_miembro.n_documento = clar_bd_jurado_evento_clar.n_documento
	 INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_bd_ficha_pdn_suelto.cod_pdn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
WHERE clar_bd_ficha_pdn_suelto.cod_clar='$id'
ORDER BY organizacion ASC";
	$result=mysql_query($sql) or die (mysql_error());
	while($fila=mysql_fetch_array($result))
	{
		$num++
	
	?>
		<tr>
			<td><? echo $num;?></td>
			<td><? echo $fila['n_documento'];?></td>
			<td><? echo $fila['organizacion']." ".$fila['denominacion'];?></td>
			<td><? echo $fila['nombre']." ".$fila['paterno']." ".$fila['materno'];?></td>
			<td><? echo number_format($fila['total_puntaje'],2);?></td>
			<td><a href="" class="small alert button">Eliminar</a></td>
		</tr>
	<?
	}
	?>	
	</tbody>
	
</table>

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

<!-- Script de combo buscador -->
<!-- CARGAMOS EL JQUERY -->
<script type="text/javascript" src="../plugins/jquery.js"></script>

<link href="../plugins/combo_buscador/hyjack.css" rel="stylesheet" type="text/css" />
<script src="../plugins/combo_buscador/hyjack_externo.js" type="text/javascript"></script>
<script src="../plugins/combo_buscador/configuracion.js" type="text/javascript"></script>

</body>
</html>
