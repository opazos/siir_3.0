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
      <li class="has-dropdown"><a href="">Primer desembolso</a>
      <ul class="dropdown">
      <li><a href="contrato_pit_mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Contrato PIT con Plan de Gestión de Recursos Naturales</a></li>
      <li class="divider"></li>
      <li><a href="contrato_pit_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Contrato PIT con Plan de Negocios</a></li>	
      <li class="divider"></li>
      <li><a href="contrato_pit_ampliacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Ampliacion a contrato PIT</a></li>	
      <li class="divider"></li>
      <li><a href="contrato_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Contrato de Plan de Negocio</a></li>
      <li class="divider"></li>
      <li><a href="contrato_idl.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Contrato de Inversion para el Desarrollo Local</a></li>			      
      </ul>
      </li>
      <li class="divider"></li>
      <li class="has-dropdown"><a href="">Segundo desembolso</a>
      
      <ul class="dropdown">
	      <li><a href="pit_sd.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Segundos desembolsos para Iniciativas que conforman un Plan de Inversion Territorial</a></li>
	      <li class="divider"></li>
	      <li><a href="pdn_sd.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Segundos desembolsos para contratos de Planes de Negocio</a></li>
	      <li class="divider"></li>
	      <li><a href="idl_sd.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Segundos desembolsos para contratos de Inversiones de Desarrollo Local</a></li>
      </ul>
      </li>
      
      <li class="divider"></li>
      <li class="has-dropdown"><a href="">Mercados Locales</a>
      <ul class="dropdown">
	      <li><a href="contrato_pc.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Contratos de Promoción Comercial</a></li>
	      <li class="divider"></li>
	      <li><a href="contrato_pf.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Contratos de Participación en Ferias</a></li>
	      <li class="divider"></li>
	      <li><a href="contrato_vg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Contratos de Visita Guiada</a></li>
      </ul>
      
      
      
      
      </li>
      <li class="divider"></li>
      <li class="has-dropdown"><a href="">Gestion del conocimiento</a>
      <ul class="dropdown">
      	  <li><a href="convenio.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Convenios interistitucionales</a></li>
      	  <li class="divider"></li>
	      <li><a href="evento.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Eventos, talleres, encuentros y otros</a></li>
	      <li class="divider"></li>
	      <li><a href="contrato_gira.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Giras de aprendizaje e intercambio de conocimiento</a></li>
	      <li class="divider"></li>
	      <li><a href="contrato_gc.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Concursos de gestión del conocimiento</a></li>
	      <li class="divider"></li>
	      <li><a href="contrato_ruta.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Rutas de aprendizaje</a></li>
	      	      
      </ul>
      </li>
	      <li class="divider"></li>
	      
	      
	      <li class="has-dropdown"><a href="">Addendas</a>
	      
	      <ul class="dropdown">		      
		      
		      <li><a href="adenda_plazo.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">PIT - Addenda de ampliación de plazo</a></li>
		      
		      <li class="divider"></li>
		      <li><a href="adenda_monto.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">PIT - Addenda de ampliación de plazo y presupuesto</a></li>
		      

		      <li class="divider"></li>
		      <li><a href="adenda_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">PDN - Addenda de ampliación de plazo</a></li>

		      <li class="divider"></li>
		      <li><a href="adenda_idl.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">IDL - Addenda de ampliación de plazo</a></li>

		      <li class="divider"></li>
		      <li><a href="adenda_convenio.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Convenios - Addenda de ampliación de plazo</a></li>
		      
		      
		      	      
	      </ul>
	      
	      </li>
	      

	      <li class="divider"></li>
	      
	      <li class="has-dropdown"><a href="">Herramientas</a>
	      <ul class="dropdown">	      
	      <li><a href="contrato_lc.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Contratos de Locación de Servicios</a></li>     
	      <li class="divider"></li> 
	      <li><a href="dj.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Declaraciones Juradas</a></li> 
	      <li class="divider"></li>
	      <li><a href="formalicer.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modulo Apoyo a la Formalización de Organizaciones</a></li>     
	      
	      
	</ul>
	      </li> 
	       
	       
        

      </ul>
    </section>
  </nav>

  <!-- Termino del Menú del SIIR -->