<?php

if($tipo==1)
{
?>
<div class="twelve columns centrado"><h6>ORGANIZACIONES TERRITORIALES</h6></div>
<table id="lista">
	<thead>
		<tr>
			<th><small>N.</small></th>
			<th><small>Documento</small></th>
			<th><small>Nombre</small></th>
			<th><small>Centro Poblado</small></th>
			<th><small>Oficina</small></th>
			<th><br/></th>
		</tr>
	</thead>
	<tbody>
	<?
	$num=0;
	if($row['cod_dependencia']==001)
	{
	$sql="SELECT org_ficha_taz.cod_tipo_doc, 
	org_ficha_taz.n_documento, 
	org_ficha_taz.nombre, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_cp.nombre AS cp
FROM sys_bd_dependencia INNER JOIN org_ficha_taz ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
	 LEFT JOIN sys_bd_cp ON sys_bd_cp.cod = org_ficha_taz.cod_cp
ORDER BY org_ficha_taz.nombre ASC";
	}
	else
	{
	$sql="SELECT org_ficha_taz.cod_tipo_doc, 
	org_ficha_taz.n_documento, 
	org_ficha_taz.nombre, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_cp.nombre AS cp
FROM sys_bd_dependencia INNER JOIN org_ficha_taz ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
	 LEFT JOIN sys_bd_cp ON sys_bd_cp.cod = org_ficha_taz.cod_cp
WHERE org_ficha_taz.cod_dependencia='".$row['cod_dependencia']."'
ORDER BY org_ficha_taz.nombre ASC";	
	}
	$result=mysql_query($sql) or die (mysql_error());
	while($fila=mysql_fetch_array($result))
	{
		$num++
	?>
		<tr>
			<td><small><? echo $num;?></small></td>
			<td><small><? echo $fila['n_documento'];?></small></td>
			<td><small><? echo $fila['nombre'];?></small></td>
			<td><small><? echo $fila['cp'];?></small></td>
			<td><small><? echo $fila['oficina'];?></small></td>
			<td>
				<?php
				if($modo==edit)
				{
					echo "<a href='update_panel.php?SES=".$SES."&anio=".$anio."&tipo=".$tipo."&cod1=".$fila['cod_tipo_doc']."&cod2=".$fila['n_documento']."' class='button tiny'>Editar</a>";
				}
				elseif($modo==delete)
				{
					echo "<a href='gestor_panel.php?SES=".$SES."&anio=".$anio."&tipo=".$tipo."&cod1=".$fila['cod_tipo_doc']."&cod2=".$fila['n_documento']."&action=DELETE' class='button tiny delete'>Eliminar</a>";
				}
				?>
			</td>
		</tr>
	<?
	}
	?>	
	</tbody>
</table>
<?	
}
elseif($tipo==2)
{
?>
<div class="twelve columns centrado"><h6>ORGANIZACIONES QUE PERTENECEN A UN TERRITORIO</h6></div>
<table id="lista">
	<thead>
		<tr>
			<th><small>N.</small></th>
			<th><small>Documento</small></th>
			<th><small>Nombre</small></th>
			<th><small>Centro Poblado</small></th>
			<th><small>Usuarios</small></th>
			<th><small>Oficina</small></th>
			<th><br/></th>
		</tr>
	</thead>
	<tbody>
	<?
	$num=0;
	if($row['cod_dependencia']==001)
	{
	$sql="SELECT org_ficha_organizacion.cod_tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_cp.nombre AS cp, 
	COUNT(org_ficha_usuario.n_documento) AS familia
	FROM sys_bd_dependencia INNER JOIN org_ficha_organizacion ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	LEFT OUTER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
	LEFT OUTER JOIN sys_bd_cp ON sys_bd_cp.cod = org_ficha_organizacion.cod_cp
	GROUP BY org_ficha_organizacion.n_documento
	ORDER BY org_ficha_organizacion.nombre ASC";
	}
	else
	{
	$sql="SELECT org_ficha_organizacion.cod_tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_cp.nombre AS cp, 
	COUNT(org_ficha_usuario.n_documento) AS familia
	FROM sys_bd_dependencia INNER JOIN org_ficha_organizacion ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	LEFT OUTER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
	LEFT OUTER JOIN sys_bd_cp ON sys_bd_cp.cod = org_ficha_organizacion.cod_cp
	WHERE org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
	GROUP BY org_ficha_organizacion.n_documento
	ORDER BY org_ficha_organizacion.nombre ASC";	
	}
	$result=mysql_query($sql) or die (mysql_error());
	while($fila=mysql_fetch_array($result))
	{
		$num++
	?>
		<tr>
			<td><small><? echo $num;?></small></td>
			<td><small><? echo $fila['n_documento'];?></small></td>
			<td><small><? echo $fila['nombre'];?></small></td>
			<td><small><? echo $fila['cp'];?></small></td>
			<td><small><? echo number_format($fila['familia']);?></small></td>
			<td><small><? echo $fila['oficina'];?></small></td>
			<td>
				<?
				if($modo==edit)
				{
					echo "<a href='update_panel.php?SES=".$SES."&anio=".$anio."&tipo=".$tipo."&cod1=".$fila['cod_tipo_doc']."&cod2=".$fila['n_documento']."' class='button tiny'>Editar</a>";
				}
				elseif($modo==delete)
				{
					echo "<a href='gestor_panel.php?SES=".$SES."&anio=".$anio."&tipo=".$tipo."&cod1=".$fila['cod_tipo_doc']."&cod2=".$fila['n_documento']."&action=DELETE' class='button tiny delete'>Eliminar</a>";
				}
				?>
			</td>
		</tr>
	<?
	}
	?>	
	</tbody>
</table>
<?	
}
elseif($tipo==3)
{
?>
<div class="twelve columns centrado"><h6>CONSISTENCIAMIENTO DE ORGANIZACIONES</h6></div>
<table id="lista">
	<thead>
		<tr>
			<th><small>N.</small></th>
			<th><small>Documento</small></th>
			<th><small>Nombre</small></th>
			<th><small>Centro Poblado</small></th>
			<th><small>Usuarios</small></th>
			<th><small>Oficina</small></th>
			<th><br/></th>
		</tr>
	</thead>
	<tbody>
	<?
	$num=0;
	if($row['cod_dependencia']==001)
	{
	$sql="SELECT org_ficha_organizacion.cod_tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_cp.nombre AS cp, 
	COUNT(org_ficha_usuario.n_documento) AS familia
	FROM sys_bd_dependencia INNER JOIN org_ficha_organizacion ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	LEFT OUTER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
	LEFT OUTER JOIN sys_bd_cp ON sys_bd_cp.cod = org_ficha_organizacion.cod_cp
	WHERE org_ficha_organizacion.cod_tipo_org<>6
	GROUP BY org_ficha_organizacion.n_documento
	ORDER BY org_ficha_organizacion.nombre ASC";
	}
	else
	{
	$sql="SELECT org_ficha_organizacion.cod_tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_cp.nombre AS cp, 
	COUNT(org_ficha_usuario.n_documento) AS familia
	FROM sys_bd_dependencia INNER JOIN org_ficha_organizacion ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	LEFT OUTER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
	LEFT OUTER JOIN sys_bd_cp ON sys_bd_cp.cod = org_ficha_organizacion.cod_cp
	WHERE org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
	AND org_ficha_organizacion.cod_tipo_org<>6
	GROUP BY org_ficha_organizacion.n_documento
	ORDER BY org_ficha_organizacion.nombre ASC";	
	}
	$result=mysql_query($sql) or die (mysql_error());
	while($fila=mysql_fetch_array($result))
	{
		$num++
	?>
		<tr>
			<td><small><? echo $num;?></small></td>
			<td><small><? echo $fila['n_documento'];?></small></td>
			<td><small><? echo $fila['nombre'];?></small></td>
			<td><small><? echo $fila['cp'];?></small></td>
			<td><small><? echo number_format($fila['familia']);?></small></td>
			<td><small><? echo $fila['oficina'];?></small></td>
			<td>
				<?
				if($modo==edit)
				{
					echo "<a href='panel_form.php?SES=".$SES."&anio=".$anio."&tipo=".$tipo."&cod1=".$fila['cod_tipo_doc']."&cod2=".$fila['n_documento']."' class='button tiny'>Consistenciar</a>";
				}
				elseif($modo==imprime)
				{
					echo "<a href='print/print_panel.php?SES=".$SES."&anio=".$anio."&tipo=".$tipo."&cod1=".$fila['cod_tipo_doc']."&cod2=".$fila['n_documento']."' class='button tiny success'>Imprimir</a>";
				}
				?>
			</td>
		</tr>
	<?
	}
	?>	
	</tbody>
</table>
<?	
}
?>