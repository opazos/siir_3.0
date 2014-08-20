  <!-- Inicio del menu del SIIR -->
<?
$sql="SELECT sys_bd_dependencia.nombre
FROM sys_bd_dependencia
WHERE sys_bd_dependencia.cod_dependencia='".$row['cod_dependencia']."'";
$result=mysql_query($sql) or die (mysql_error());
$i1=mysql_fetch_array($result);
?>

  <nav class="top-bar fixed">
    <ul>
      <li class="name"><h1><a href="../principal.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">SIIR - OLP. <? echo $i1['nombre'];?></a></h1></li>
      <li class="toggle-topbar"><a href="#"></a></li>
    </ul>

    <section>
 

      <ul class="right">
        <li class="has-dropdown">
          <a href="#">Organizaciones</a>

           <ul class="dropdown">
          <li><a href="../territorio.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Organizaciones Territoriales</a></li>
          <li class="divider"></li>
          <li><a href="../organizacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Organizaciones y Municipios</a></li>
          <li class="divider"></li>
          <li><a href="../familias.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Consistenciamiento de Familias</a></li>
          </ul>
          </li>
         <li class="divider"></li>
         <li class="has-dropdown"><a href="">Iniciativas</a>
         <ul class="dropdown">
         <li><a href="../inic_primero.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Iniciativas que participan en un CLAR de Primer Desembolso</a></li>
         <li class="divider"></li>
         <li><a href="../inic_segundo.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Iniciativas que participan en un CLAR de Segundo Desembolso</a></li>
          <li class="divider"></li>
         <li><a href="../seguro.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Programa Seguro de Vida Campesino</a></li>   
         <li class="divider"></li>
         <li><a href="../inic_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Liquidación de Iniciativas</a></li>
              
	      </ul>
         </li>
	        
        </ul>
        </li>
      </ul>
    </section>
  </nav>
  <!-- Termino del Menú del SIIR -->