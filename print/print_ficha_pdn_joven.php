<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT org_ficha_organizacion.nombre AS organizacion, 
	pit_bd_ficha_pdn.denominacion, 
	sys_bd_distrito.nombre AS distrito, 
	clar_bd_miembro.n_documento, 
	clar_bd_miembro.nombre, 
	clar_bd_miembro.paterno, 
	clar_bd_miembro.materno, 
	sys_bd_cargo_jurado_clar.descripcion AS cargo, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.lugar
FROM pit_bd_ficha_pdn INNER JOIN clar_bd_ficha_pdn_suelto ON pit_bd_ficha_pdn.cod_pdn = clar_bd_ficha_pdn_suelto.cod_pdn
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_pdn_suelto.cod_clar
	 INNER JOIN clar_bd_jurado_evento_clar ON clar_bd_jurado_evento_clar.cod_clar = clar_bd_ficha_pdn_suelto.cod_clar
	 INNER JOIN clar_bd_miembro ON clar_bd_miembro.n_documento = clar_bd_jurado_evento_clar.n_documento
	 INNER JOIN sys_bd_cargo_jurado_clar ON sys_bd_cargo_jurado_clar.cod_cargo_jurado = clar_bd_jurado_evento_clar.cod_cargo_miembro
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
WHERE pit_bd_ficha_pdn.tipo=2 AND
clar_bd_ficha_pdn_suelto.cod_clar='$cod'";
$result=mysql_query($sql) or die (mysql_error());

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>::Vista Preeliminar::</title>
<!-- cargamos el estilo de la pagina -->
<link href="../stylesheets/print.css" rel="stylesheet" type="text/css">
<style type="text/css">

  @media print 
  {
    .oculto {display:none;background-color: #333;color:#FFF; font: bold 84% 'trebuchet ms',helvetica,sans-serif;  }
  }
</style>
<!-- Fin -->
</head>

<body>
<?
$num=0;
while($row=mysql_fetch_array($result))
{
$num++
?>  
    <? include("encabezado.php");?>
    <div class="capa txt_titulo centrado">CONCURSO DE INICIATIVAS DE NEGOCIO DE
      JOVENES RURALES EMPRENDEDORES<br>
      PROPUESTA DE PLAN DE NEGOCIO - PDN<br>
      <br>
      FICHA DE EVALUACIÓN - CLAR</div>
    <table class="mini" align="center" cellpadding="1" cellspacing="0" width="90%">
      <tbody>
        <tr>
          <td width="25%">ORGANIZACION RESPONSABLE DEL PLAN DE NEGOCIO</td>
          <td width="5%">:</td>
          <td width="70%"><? echo $row['organizacion'];?>
          </td>
        </tr>
        <tr>
          <td>DENOMINACIÓN DEL PLAN DE NEGOCIO</td>
          <td>:</td>
          <td><? echo $row['denominacion'];?>
          </td>
        </tr>
        <tr>
          <td>DISTRITO</td>
          <td>:</td>
          <td><? echo $row['distrito'];?></td>
        </tr>
        <tr>
          <td colspan="3">
            <hr></td>
        </tr>
      </tbody>
    </table>
    <br>
    <table style="width: 90%" align="center" border="1" class="mini" cellpadding="1" cellspacing="1">
      <tbody>
        <tr class="txt_titulo centrado">
          <td>EJES<br>
          </td>
          <td>CRITERIOS<br>
          </td>
          <td>DESCRIPCION<br>
          </td>
          <td>PUNTAJE MAXIMO<br>
          </td>
          <td>PUNTAJE ASIGNADO<br>
          </td>
        </tr>
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
          <td colspan="1" rowspan="3"><br>
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
          <td colspan="1" rowspan="2"><br>
          </td>
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
          <td colspan="1" rowspan="2"><br>
          </td>
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
          <td colspan="1" rowspan="2"><br>
          </td>
        </tr>
        <tr>
          <td>Las mujeres que integran la organización son menos de la mitad del
            total de socios<br>
          </td>
          <td class="centrado">6
          </td>
        </tr>
        <tr>
          <td colspan="3" rowspan="1">Subtotal Organización Responsable del Plan
            de negocio<br>
          </td>
          <td class="centrado">40
          </td>
          <td><br>
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
          <td><br>
          </td>
        </tr>
        <tr>
          <td>2.2 Plazo de ejecución</td>
          <td>Se verifica si existen las condiciones para el cumplimiento de los
            resultados esperados en el plazo propuesto en el PDN</td>
          <td class="centrado">6
          </td>
          <td><br>
          </td>
        </tr>
        <tr>
          <td>2.3 Actividades conexas al PDN</td>
          <td>Pertinencia de las visitas guiadas y participación en ferias con
            la propuesta del PDN</td>
          <td class="centrado">6
          </td>
          <td><br>
          </td>
        </tr>
        <tr>
          <td>2.4 Inversiones en Activos Fijos y/o Capital de Trabajo</td>
          <td>Evalúa la coherencia de las inversiones propuestas y el monto de
            Préstamo propuesto</td>
          <td class="centrado">10
          </td>
          <td><br>
          </td>
        </tr>
        <tr>
          <td>2.5 Aportes para cofinanciar el PDN</td>
          <td>Percepción de que hay posibilidades reales de cumplir con los
            aportes para el PDN</td>
          <td class="centrado">7
          </td>
          <td><br>
          </td>
        </tr>
        <tr>
          <td>2.6 Contribución al Medio Ambiente<br>
          </td>
          <td>Percepción de que el PDN no afecta el medio ambiente o contribuye
            favorablemente</td>
          <td class="centrado">6
          </td>
          <td><br>
          </td>
        </tr>
        <tr>
          <td colspan="3" rowspan="1">Subtotal Coherencia del Plan de Negocio<br>
          </td>
          <td class="centrado">45
          </td>
          <td><br>
          </td>
        </tr>
        <tr>
          <td colspan="1" rowspan="3">III.- Presentación del PDN (15 puntos)</td>
          <td>3.1 Claridad</td>
          <td>Evalúa la claridad en la presentación del pdn, utilizando los
            medios que son accesibles o ingeniados por el grupo (fotos, mapas,
            productos, sociodrama, etc)</td>
          <td class="centrado">6
          </td>
          <td><br>
          </td>
        </tr>
        <tr>
          <td>3.2 Identidad y cultura<br>
          </td>
          <td>Evalúa los aspectos socioculturales que se reflejan en la
            presentación del grupo (vestimenta, idioma, música y danza)</td>
          <td class="centrado">5
          </td>
          <td><br>
          </td>
        </tr>
        <tr>
          <td>3.3 Género<br>
          </td>
          <td>Evalúa el nivel de participación de varones y mujeres de manera
            equitativa</td>
          <td class="centrado">4
          </td>
          <td><br>
          </td>
        </tr>
        <tr>
          <td colspan="3" rowspan="1">Subtotal Presentación del Plan de Negocio<br>
          </td>
          <td class="centrado">15
          </td>
          <td><br>
          </td>
        </tr>
        <tr>
          <td colspan="3" rowspan="1">TOTAL EVALUACIÓN</td>
          <td class="centrado">100
          </td>
          <td><br>
          </td>
        </tr>
      </tbody>
    </table>
    
    <table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="23%">Observaciones</td>
    <td width="1%">:</td>
    <td width="76%">
        <p>&nbsp;</p></td>
  </tr>
  <tr>
    <td>Nombres y apellidos del Evaluador</td>
    <td>:</td>
    <td><? echo $row['nombre']." ".$row['paterno']." ".$row['materno'];?></td>
  </tr>
  <tr>
    <td>DNI</td>
    <td>:</td>
    <td><? echo $row['n_documento'];?></td>
  </tr>
  <tr>
    <td>Firma</td>
    <td>:</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Lugar y Fecha</td>
    <td>:</td>
    <td><? echo $row['lugar'].", ".traducefecha($row['f_evento']);?></td>
  </tr>
</table>
<div class="capa centrado mini">-<? echo $num;?>-</div>
<H1 class=SaltoDePagina></H1>
<?
}
?>
<div class="capa">
	<button type="submit" class="secondary button oculto">Imprimir</button>
	<a href="../clar/clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=pdn_joven" class="secondary button oculto">Finalizar</a>
</div>

</body>
</html>
