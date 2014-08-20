<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);


if ($action<>NULL)
{
$sql="SELECT * FROM ficha_ag_oferente WHERE n_documento='".$_POST['dni']."'";
$result1=mysql_query($sql) or die (mysql_error());
$total=mysql_num_rows($result1);

	if ($total==0)
	{
		include("../funciones/funciones_externas.php");
		conectarte_externo();
		
		$sql="SELECT * FROM maestro_reniec WHERE dni='".$_POST['dni']."'";
		$result2=mysql_query($sql) or die (mysql_error());
		$total1=mysql_num_rows($result2);
		
		if ($total1==0)
		{
			conectarte();
			$sql="SELECT * FROM org_ficha_usuario WHERE n_documento='".$_POST['dni']."'";
			$result3=mysql_query($sql) or die (mysql_error());
			$total2=mysql_num_rows($result3);
			
			if ($total2==0)
			{
				echo "<script>window.location ='n_animador.php?SES=$SES&anio=$anio&error=no_dni'</script>";
			}
			else
			{
				$row=mysql_fetch_array($result3);
				$dni=$row['n_documento'];
				$nombre=$row['nombre'];
				$paterno=$row['paterno'];
				$materno=$row['materno'];
				$f_nacimiento=$row['f_nacimiento'];
				$sexo=$row['sexo'];
				$ubigeo=$row['ubigeo'];
				$direccion=$row['direccion'];
				$tipo_oferente="";
				$especialidad="";
				$externo=1;
			}
		}
		else
		{
			$row=mysql_fetch_array($result2);
			$dni=$row['dni'];
			$nombre=$row['nombres'];
			$paterno=$row['paterno'];
			$materno=$row['materno'];
			$f_nacimiento=$row['fenac'];
			if ($row['sexo']==M)
			{
			$sexo=1;
			}
			else
			{
			$sexo=0;
			}
			$ubigeo=$row['ubigeo'];
			$direccion="";
			$tipo_oferente="";
			$especialidad="";
			$externo=1;
		}
		
	}
	else
	{
		$row=mysql_fetch_array($result1);
		$dni=$row['n_documento'];
		$nombre=$row['nombre'];
		$paterno=$row['paterno'];
		$materno=$row['materno'];
		$f_nacimiento=$row['f_nacimiento'];
		$sexo=$row['sexo'];
		$ubigeo=$row['ubigeo'];
		$direccion=$row['direccion'];
		$tipo_oferente=$row['cod_tipo_oferente'];
		$especialidad=$row['especialidad'];
		$externo=0;
	}

}


$sql="SELECT sys_bd_numera_dependencia.n_contrato_pd, 
	sys_bd_numera_dependencia.cod
FROM sys_bd_numera_dependencia
WHERE sys_bd_numera_dependencia.cod_dependencia='".$row1['cod_dependencia']."' AND
sys_bd_numera_dependencia.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$n_contrato=$r1['n_contrato_pd']+1;

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
    <!-- Editor de texto -->
  <script type="text/javascript" src="../plugins/texteditor/jscripts/tiny_mce/tiny_mce.js"></script>
  <script type="text/javascript" src="../plugins/texteditor/editor.js"></script>
  <!-- fin de editor de texto -->
</head>
<body>
<? include("menu.php");?>


<!-- Iniciamos el contenido -->
<div class="row">
 <div class="twelve columns">
       
<!-- Contenedores -->
<dl class="tabs">
<dd  class="active"><a href="">Registro de Contratos de Locación de Servicios</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" action="gestor_contrato_lc.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=ADD" onsubmit="return checkSubmit();">
	
	
	<div class="twelve columns"><h6>I.- Información del Contratado</h6></div>
	<div class="twelve columns"><br/></div>

	<div class="two columns">Nº DNI</div>
	<div class="two columns"><input type="text" name="dni" class="dni required ten digits" maxlength="8" value="<? echo $dni;?>">
	<input type="hidden" name="codigo" value="<? echo $row['cod'];?>">
	</div>
	<div class="one columns">

	<button type="button" onclick="this.form.action='n_contrato_lc.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=VERIFICAR';this.form.submit()" class="small success button">Verificar DNI</button>
	</div>
	<div class="one columns"><br/></div>
	<div class="two columns">Fecha de nacimiento</div>
	<div class="four columns"><input type="date" name="f_nacimiento" class="required date seven" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $f_nacimiento;?>"></div>
	<div class="two columns">Apellido Paterno</div>
	<div class="four columns"><input type="text" name="paterno" class="required ten" value="<? echo $paterno;?>"></div>
	<div class="two columns">Apellido Materno</div>
	<div class="four columns"><input type="text" name="materno" class="required ten" value="<? echo $materno;?>"></div>
	<div class="two columns">Nombres</div>
	<div class="four columns"><input type="text" name="nombre" class="required ten" value="<? echo $nombre;?>"></div>
	<div class="two columns">Sexo</div>
	<div class="four columns">
	<select name="sexo" class="five">
		<option value="" selected="selected">Seleccionar</option>
		<option value="1" <? if ($sexo==1) echo "selected";?>>M</option>
		<option value="0" <? if ($sexo==0) echo "selected";?>>F</option>
	</select>
	</div>
	<div class="twelve columns"></div>
	<div class="two columns">Direccion</div>
	<div class="four columns"><input type="text" name="direccion" class="ten" value="<? echo $direccion;?>"></div>
	<div class="two columns">Ubigeo (6 digitos)</div>
	<div class="four columns"><input type="text" name="ubigeo" class="required digits five" maxlength="6" value="<? echo $ubigeo;?>"></div>
	<div class="two columns">Tipo de oferente</div>
	<div class="four columns">
	<select name="tipo_oferente" class="ten">
		<option value="" selected="selected">Seleccionar</option>
		<?
		if ($externo==1)
		{
			conectarte();
		}
		$sql="SELECT * FROM sys_bd_tipo_oferente";
		$result=mysql_query($sql) or die (mysql_error());
		while($f2=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f2['cod'];?>" <? if ($tipo_oferente==$f2['cod']) echo "selected";?>><? echo $f2['descripcion'];?></option>
		<?
		}
		?>
	</select>
</div>
	<div class="two columns">Especialidad/Profesion</div>
	<div class="four columns"><input type="text" name="especialidad" class="required ten" value="<? echo $especialidad;?>"></div>

	<div class="twelve columns"><h6>II.- Datos del contrato</h6></div>	
	<div class="twelve columns"><br/></div>
	
	<div class="two columns">Nº Contrato</div>
	<div class="four columns"><input type="text" name="n_contrato" class="required digits five" readonly="readonly" value="<? echo $n_contrato;?>"></div>
	<div class="two columns">Fecha de contrato</div>
	<div class="four columns"><input type="date" name="f_contrato" class="required date seven" placeholder="aaaa-mm-dd" maxlength="10"></div>
	<div class="two columns">Puesto a ocupar</div>
	<div class="ten columns"><input type="text" name="puesto" class="required eleven"></div>
	
	<div class="twelve columns"><h6>III.- Condiciones del servicio</h6></div>
	<div class="twelve columns"><textarea name="condiciones" id="elm1" rows="20"></textarea></div>
	
	<div class="twelve columns"><h6>IV.- Monto del contrato y vigencia</h6></div>
	<div class="two columns">Total a pagar</div>
	<div class="four columns"><input type="text" name="monto" class="required number five"></div>
	<div class="two columns">Vigencia en meses</div>
	<div class="four columns"><input type="text" name="mes" class="required digits five"></div>
	<div class="twelve columns"><h6>V.- Afectación Presupuestal</h6></div>
	<div class="twelve columns">
		
		<select name="poa" class="hyjack">
			<option value="" selected="selected">Seleccionar</option>
			<?
				$sql="SELECT sys_bd_subactividad_poa.codigo, 
				sys_bd_subactividad_poa.nombre, 
				sys_bd_subactividad_poa.cod
				FROM sys_bd_subactividad_poa
				ORDER BY sys_bd_subactividad_poa.codigo ASC";
				$result=mysql_query($sql) or die (mysql_error());
				while($f1=mysql_fetch_array($result))
				{
			?>
			<option value="<? echo $f1['cod'];?>"><? echo $f1['codigo']."-".$f1['nombre'];?></option>
			<?
			}
			?>
		</select>
		
	</div>
	
	
<div class="twelve columns"><br/></div>

<div class="twelve columns">
	
	<button type="submit" class="success button">Guardar cambios</button>
	<a href="contrato_lc.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button">Cancelar</a>
	
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
