<?
date_default_timezone_set('America/Lima');
//1.- Funcion para conectarse a la base de datos
function conectarte()
{
//mysql_connect("www.sierrasur.gob.pe","psierras_masters","rumpeltinsky") or die("Error:Servidor sin conexion");
//mysql_select_db("psierras_bd_siir") or die("Error:Base de datos sin conexion & No disponible");

mysql_connect("localhost","root","root") or die("Error:Servidor sin conexion");
mysql_select_db("bd_siir") or die("Error:Base de datos sin conexion & No disponible");
}

//2.- Funcion para convertir una fecha a letras
function traducefecha($fecha) 
{ 
$fecha= strtotime($fecha); // convierte la fecha de formato mm/dd/yyyy a marca de tiempo 
$diasemana=date("w", $fecha);// optiene el número del dia de la semana. El 0 es domingo 
switch ($diasemana) 
{ 
case "0": 
$diasemana="Domingo"; 
break; 
case "1": 
$diasemana="Lunes"; 
break; 
case "2": 
$diasemana="Martes"; 
break; 
case "3": 
$diasemana="Miercoles"; 
break; 
case "4": 
$diasemana="Jueves"; 
break; 
case "5": 
$diasemana="Viernes"; 
break; 
case "6": 
$diasemana="Sabado"; 
break; 
} 
$dia=date("d",$fecha); // da del mes en nmero 
$mes=date("m",$fecha); // nmero del mes de 01 a 12 
switch($mes) 
{ 
case "01": 
$mes="Enero"; 
break; 
case "02": 
$mes="Febrero"; 
break; 
case "03": 
$mes="Marzo"; 
break; 
case "04": 
$mes="Abril"; 
break; 
case "05": 
$mes="Mayo"; 
break; 
case "06": 
$mes="Junio"; 
break; 
case "07": 
$mes="Julio"; 
break; 
case "08": 
$mes="Agosto"; 
break; 
case "09": 
$mes="Septiembre"; 
break; 
case "10": 
$mes="Octubre"; 
break; 
case "11": 
$mes="Noviembre"; 
break; 
case "12": 
$mes="Diciembre"; 
break; 
} 
$ano=date("Y",$fecha); // optenemos el ao en formato 4 digitos 
//$fecha= $diasemana.", ".$dia." de ".$mes." de ".$ano; // unimos el resultado en una unica cadena 
$fechacorta=$dia." de ".$mes." de ".$ano;
//return $fecha; //enviamos la fecha al programa 
return $fechacorta;
} 

//3.- Funcion de suma de años
function dateadd($date, $dd=0, $mm=0, $yy=0, $hh=0, $mn=0, $ss=0){
$date_r = getdate(strtotime($date));
$date_result = date("Y-m-d", mktime(($date_r["hours"]+$hh),($date_r["minutes"]+$mn),($date_r["seconds"]+$ss),($date_r["mon"]+$mm),($date_r["mday"]+$dd),($date_r["year"]+$yy)));
return $date_result;
}

//4.- Convierte fecha de mysql a normal  
function  fecha_normal ( $fecha ){ 
ereg (  "([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})" ,  $fecha ,  $mifecha ); 
$lafecha = $mifecha [ 3 ]. "/" . $mifecha [ 2 ]. "/" . $mifecha [ 1 ]; 
     return  $lafecha ; 
} 

//5.- Devuelve la fecha actual
$fecha_hoy=date('Y-m-d');

//ESPACIADO
$espacio="&nbsp;";

//6.- Año actual
$periodo=date('Y');

//7.- Funcion para realizar la numeracion segun el pedido de los usuarios
function numeracion($numero)
{
	if ($numero < 10)
	{
	$number="00".$numero;
	}
	elseif($numero > 9 and $numero < 100)
	{
	$number="0".$numero;	
	}
	elseif($numero > 99 and $numero < 1000)
	{
	$number=$numero;	
	}
	elseif($numero > 1000)
	{
	$number=$numero;
	}
	
	return $number;
}

//8.- Funcion que retorna el año de una variable

function periodo($nume)
{
	$anio=substr($nume,0,4);
	return $anio;
}
//8.1 Funcion para volver a letras numeros hasta el 20
function litera($valor)
{

	
	switch($valor)
	{
		case 0:
			$numerico=Cero;
			break;
		case 1:
			$numerico=Uno;
			break;
		case 2:
			$numerico=Dos;
			break;
		case 3:
			$numerico=Tres;
			break;
		case 4:
			$numerico=Cuatro;
			break;
		case 5:
			$numerico=Cinco;
			break;
		case 6:
			$numerico=Seis;
			break;
		case 7:
			$numerico=Siete;
			break;
		case 8:
			$numerico=Ocho;
			break;
		case 9:
			$numerico=Nueve;
			break;
		case 10:
			$numerico=Diez;
			break;				
	}
	return $numerico;

	
}




//9.- funcion para volver numeros a letras
function vuelveletra($num, $fem = false, $dec = true) { 
//if (strlen($num) > 14) die("El n?mero introducido es demasiado grande"); 
   $matuni[1]  = "uno"; 
   $matuni[2]  = "dos"; 
   $matuni[3]  = "tres"; 
   $matuni[4]  = "cuatro"; 
   $matuni[5]  = "cinco"; 
   $matuni[6]  = "seis"; 
   $matuni[7]  = "siete"; 
   $matuni[8]  = "ocho"; 
   $matuni[9]  = "nueve"; 
   $matuni[10] = "diez"; 
   $matuni[11] = "once"; 
   $matuni[12] = "doce"; 
   $matuni[13] = "trece"; 
   $matuni[14] = "catorce"; 
   $matuni[15] = "quince"; 
   $matuni[16] = "dieciseis"; 
   $matuni[17] = "diecisiete"; 
   $matuni[18] = "dieciocho"; 
   $matuni[19] = "diecinueve"; 
   $matuni[20] = "veinte"; 
   
   $matuni[1]  = "uno"; 
   $matunisub[2] = "dos"; 
   $matunisub[3] = "tres"; 
   $matunisub[4] = "cuatro"; 
   $matunisub[5] = "quin"; 
   $matunisub[6] = "seis"; 
   $matunisub[7] = "sete"; 
   $matunisub[8] = "ocho"; 
   $matunisub[9] = "nove"; 

   $matdec[2] = "veint"; 
   $matdec[3] = "treinta"; 
   $matdec[4] = "cuarenta"; 
   $matdec[5] = "cincuenta"; 
   $matdec[6] = "sesenta"; 
   $matdec[7] = "setenta"; 
   $matdec[8] = "ochenta"; 
   $matdec[9] = "noventa"; 
   $matsub[3]  = 'mill'; 
   $matsub[5]  = 'bill'; 
   $matsub[7]  = 'mill'; 
   $matsub[9]  = 'trill'; 
   $matsub[11] = 'mill'; 
   $matsub[13] = 'bill'; 
   $matsub[15] = 'mill'; 
   $matmil[4]  = 'millones'; 
   $matmil[6]  = 'billones'; 
   $matmil[7]  = 'de billones'; 
   $matmil[8]  = 'millones de billones'; 
   $matmil[10] = 'trillones'; 
   $matmil[11] = 'de trillones'; 
   $matmil[12] = 'millones de trillones'; 
   $matmil[13] = 'de trillones'; 
   $matmil[14] = 'billones de trillones'; 
   $matmil[15] = 'de billones de trillones'; 
   $matmil[16] = 'millones de billones de trillones'; 

   $num = trim((string)@$num); 
   if ($num[0] == '-') { 
      $neg = 'menos '; 
      $num = substr($num, 1); 
   }else 
      $neg = ''; 
   while ($num[0] == '0') $num = substr($num, 1); 
   if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num; 
   $zeros = true; 
   $punt = false; 
   $ent = ''; 
   $fra = ''; 
   for ($c = 0; $c < strlen($num); $c++) { 
      $n = $num[$c]; 
      if (! (strpos(".,'''", $n) === false)) { 
         if ($punt) break; 
         else{ 
            $punt = true; 
            continue; 
         } 

      }elseif (! (strpos('0123456789', $n) === false)) { 
         if ($punt) { 
            if ($n != '0') $zeros = false; 
            $fra .= $n; 
         }else 

            $ent .= $n; 
      }else 

         break; 

   } 
   $ent = '     ' . $ent; 
   if ($dec and $fra and ! $zeros) { 
      $fin = ' con '; 
      for ($n = 0; $n < strlen($fra); $n++) { 
         if (($s = $fra[$n]) == '0') 
            $fin .= ''; 
         elseif ($s == '1') 
            $fin .= $fem ? ' una' : ' un'; 
         else 
            $fin = " con ".$fra."/100";
			//$fin .= ' ' . $matuni[$s]."/100"; 
      } 
   }else 
      $fin = ' con 00/100'; 
   if ((int)$ent === 0) return 'Cero ' . $fin; 
   $tex = ''; 
   $sub = 0; 
   $mils = 0; 
   $neutro = false; 
   while ( ($num = substr($ent, -3)) != '   ') { 
      $ent = substr($ent, 0, -3); 
      if (++$sub < 3 and $fem) { 
         $matuni[1] = 'una'; 
         $subcent = 'as'; 
      }else{ 
         $matuni[1] = $neutro ? 'un' : 'uno'; 
         $subcent = 'os'; 
      } 
      $t = ''; 
      $n2 = substr($num, 1); 
      if ($n2 == '00') { 
      }elseif ($n2 < 21) 
         $t = ' ' . $matuni[(int)$n2]; 
      elseif ($n2 < 30) { 
         $n3 = $num[2]; 
         if ($n3 != 0) $t = 'i' . $matuni[$n3]; 
         $n2 = $num[1]; 
         $t = ' ' . $matdec[$n2] . $t; 
      }else{ 
         $n3 = $num[2]; 
         if ($n3 != 0) $t = ' y ' . $matuni[$n3]; 
         $n2 = $num[1]; 
         $t = ' ' . $matdec[$n2] . $t; 
      } 
      $n = $num[0]; 
      if ($n == 1) { 
         $t = ' ciento' . $t; 
      }elseif ($n == 5){ 
         $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t; 
      }elseif ($n != 0){ 
         $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t; 
      } 
      if ($sub == 1) { 
      }elseif (! isset($matsub[$sub])) { 
         if ($num == 1) { 
            $t = ' mil'; 
         }elseif ($num > 1){ 
            $t .= ' mil'; 
         } 
      }elseif ($num == 1) { 
         $t .= ' ' . $matsub[$sub] . '?n'; 
      }elseif ($num > 1){ 
         $t .= ' ' . $matsub[$sub] . 'ones'; 
      }   
      if ($num == '000') $mils ++; 
      elseif ($mils != 0) { 
         if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub]; 
         $mils = 0; 
      } 
      $neutro = true; 
      $tex = $t . $tex; 
   } 
   $tex = $neg . substr($tex, 1) . $fin; 
   return ucfirst($tex); 
} 
/******************************************************************************/


/*************************************/
// FUNCIONES DE CONVERSION DE NUMEROS A LETRAS.
function centimos()
{
	global $importe_parcial;

	$importe_parcial = number_format($importe_parcial, 2, ".", "") * 100;

	if ($importe_parcial > 0)
		//$num_letra = " con $importe_parcial/100";
		$num_letra = " con ".decena_centimos($importe_parcial);
	else
		$num_letra = "";

	return $num_letra;
}

function unidad_centimos($numero)
{
	switch ($numero)
	{
		case 9:
		{
			$num_letra = "nueve cntimos";
			break;
		}
		case 8:
		{
			$num_letra = "ocho cntimos";
			break;
		}
		case 7:
		{
			$num_letra = "siete cntimos";
			break;
		}
		case 6:
		{
			$num_letra = "seis cntimos";
			break;
		}
		case 5:
		{
			$num_letra = "cinco cntimos";
			break;
		}
		case 4:
		{
			$num_letra = "cuatro cntimos";
			break;
		}
		case 3:
		{
			$num_letra = "tres cntimos";
			break;
		}
		case 2:
		{
			$num_letra = "dos cntimos";
			break;
		}
		case 1:
		{
			$num_letra = "un cntimo";
			break;
		}
	}
	return $num_letra;
}

function decena_centimos($numero)
{
	if ($numero >= 10)
	{
		if ($numero >= 90 && $numero <= 99)
		{
			  if ($numero == 90)
				  return "noventa cntimos";
			  else if ($numero == 91)
				  return "noventa y un cntimos";
			  else
				  return "noventa y ".unidad_centimos($numero - 90);
		}
		if ($numero >= 80 && $numero <= 89)
		{
			if ($numero == 80)
				return "ochenta cntimos";
			else if ($numero == 81)
				return "ochenta y un cntimos";
			else
				return "ochenta y ".unidad_centimos($numero - 80);
		}
		if ($numero >= 70 && $numero <= 79)
		{
			if ($numero == 70)
				return "setenta cntimos";
			else if ($numero == 71)
				return "setenta y un cntimos";
			else
				return "setenta y ".unidad_centimos($numero - 70);
		}
		if ($numero >= 60 && $numero <= 69)
		{
			if ($numero == 60)
				return "sesenta cntimos";
			else if ($numero == 61)
				return "sesenta y un cntimos";
			else
				return "sesenta y ".unidad_centimos($numero - 60);
		}
		if ($numero >= 50 && $numero <= 59)
		{
			if ($numero == 50)
				return "cincuenta cntimos";
			else if ($numero == 51)
				return "cincuenta y un cntimos";
			else
				return "cincuenta y ".unidad_centimos($numero - 50);
		}
		if ($numero >= 40 && $numero <= 49)
		{
			if ($numero == 40)
				return "cuarenta cntimos";
			else if ($numero == 41)
				return "cuarenta y un cntimos";
			else
				return "cuarenta y ".unidad_centimos($numero - 40);
		}
		if ($numero >= 30 && $numero <= 39)
		{
			if ($numero == 30)
				return "treinta cntimos";
			else if ($numero == 91)
				return "treinta y un cntimos";
			else
				return "treinta y ".unidad_centimos($numero - 30);
		}
		if ($numero >= 20 && $numero <= 29)
		{
			if ($numero == 20)
				return "veinte cntimos";
			else if ($numero == 21)
				return "veintiun cntimos";
			else
				return "veinti".unidad_centimos($numero - 20);
		}
		if ($numero >= 10 && $numero <= 19)
		{
			if ($numero == 10)
				return "diez cntimos";
			else if ($numero == 11)
				return "once cntimos";
			else if ($numero == 11)
				return "doce cntimos";
			else if ($numero == 11)
				return "trece cntimos";
			else if ($numero == 11)
				return "catorce cntimos";
			else if ($numero == 11)
				return "quince cntimos";
			else if ($numero == 11)
				return "dieciseis cntimos";
			else if ($numero == 11)
				return "diecisiete cntimos";
			else if ($numero == 11)
				return "dieciocho cntimos";
			else if ($numero == 11)
				return "diecinueve cntimos";
		}
	}
	else
		return unidad_centimos($numero);
}

function unidad($numero)
{
	switch ($numero)
	{
		case 9:
		{
			$num = "nueve";
			break;
		}
		case 8:
		{
			$num = "ocho";
			break;
		}
		case 7:
		{
			$num = "siete";
			break;
		}
		case 6:
		{
			$num = "seis";
			break;
		}
		case 5:
		{
			$num = "cinco";
			break;
		}
		case 4:
		{
			$num = "cuatro";
			break;
		}
		case 3:
		{
			$num = "tres";
			break;
		}
		case 2:
		{
			$num = "dos";
			break;
		}
		case 1:
		{
			$num = "uno";
			break;
		}
	}
	return $num;
}

function decena($numero)
{
	if ($numero >= 90 && $numero <= 99)
	{
		$num_letra = "noventa ";
		
		if ($numero > 90)
			$num_letra = $num_letra."y ".unidad($numero - 90);
	}
	else if ($numero >= 80 && $numero <= 89)
	{
		$num_letra = "ochenta ";
		
		if ($numero > 80)
			$num_letra = $num_letra."y ".unidad($numero - 80);
	}
	else if ($numero >= 70 && $numero <= 79)
	{
			$num_letra = "setenta ";
		
		if ($numero > 70)
			$num_letra = $num_letra."y ".unidad($numero - 70);
	}
	else if ($numero >= 60 && $numero <= 69)
	{
		$num_letra = "sesenta ";
		
		if ($numero > 60)
			$num_letra = $num_letra."y ".unidad($numero - 60);
	}
	else if ($numero >= 50 && $numero <= 59)
	{
		$num_letra = "cincuenta ";
		
		if ($numero > 50)
			$num_letra = $num_letra."y ".unidad($numero - 50);
	}
	else if ($numero >= 40 && $numero <= 49)
	{
		$num_letra = "cuarenta ";
		
		if ($numero > 40)
			$num_letra = $num_letra."y ".unidad($numero - 40);
	}
	else if ($numero >= 30 && $numero <= 39)
	{
		$num_letra = "treinta ";
		
		if ($numero > 30)
			$num_letra = $num_letra."y ".unidad($numero - 30);
	}
	else if ($numero >= 20 && $numero <= 29)
	{
		if ($numero == 20)
			$num_letra = "veinte ";
		else
			$num_letra = "veinti".unidad($numero - 20);
	}
	else if ($numero >= 10 && $numero <= 19)
	{
		switch ($numero)
		{
			case 10:
			{
				$num_letra = "diez ";
				break;
			}
			case 11:
			{
				$num_letra = "once ";
				break;
			}
			case 12:
			{
				$num_letra = "doce ";
				break;
			}
			case 13:
			{
				$num_letra = "trece ";
				break;
			}
			case 14:
			{
				$num_letra = "catorce ";
				break;
			}
			case 15:
			{
				$num_letra = "quince ";
				break;
			}
			case 16:
			{
				$num_letra = "dieciseis ";
				break;
			}
			case 17:
			{
				$num_letra = "diecisiete ";
				break;
			}
			case 18:
			{
				$num_letra = "dieciocho ";
				break;
			}
			case 19:
			{
				$num_letra = "diecinueve ";
				break;
			}
		}
	}
	else
		$num_letra = unidad($numero);

	return $num_letra;
}

function centena($numero)
{
	if ($numero >= 100)
	{
		if ($numero >= 900 & $numero <= 999)
		{
			$num_letra = "novecientos ";
			
			if ($numero > 900)
				$num_letra = $num_letra.decena($numero - 900);
		}
		else if ($numero >= 800 && $numero <= 899)
		{
			$num_letra = "ochocientos ";
			
			if ($numero > 800)
				$num_letra = $num_letra.decena($numero - 800);
		}
		else if ($numero >= 700 && $numero <= 799)
		{
			$num_letra = "setecientos ";
			
			if ($numero > 700)
				$num_letra = $num_letra.decena($numero - 700);
		}
		else if ($numero >= 600 && $numero <= 699)
		{
			$num_letra = "seiscientos ";
			
			if ($numero > 600)
				$num_letra = $num_letra.decena($numero - 600);
		}
		else if ($numero >= 500 && $numero <= 599)
		{
			$num_letra = "quinientos ";
			
			if ($numero > 500)
				$num_letra = $num_letra.decena($numero - 500);
		}
		else if ($numero >= 400 && $numero <= 499)
		{
			$num_letra = "cuatrocientos ";
			
			if ($numero > 400)
				$num_letra = $num_letra.decena($numero - 400);
		}
		else if ($numero >= 300 && $numero <= 399)
		{
			$num_letra = "trescientos ";
			
			if ($numero > 300)
				$num_letra = $num_letra.decena($numero - 300);
		}
		else if ($numero >= 200 && $numero <= 299)
		{
			$num_letra = "doscientos ";
			
			if ($numero > 200)
				$num_letra = $num_letra.decena($numero - 200);
		}
		else if ($numero >= 100 && $numero <= 199)
		{
			if ($numero == 100)
				$num_letra = "cien ";
			else
				$num_letra = "cienta ".decena($numero - 100);
		}
	}
	else
		$num_letra = decena($numero);
	
	return $num_letra;
}

function cien()
{
	global $importe_parcial;
	
	$parcial = 0; $car = 0;
	
	while (substr($importe_parcial, 0, 1) == 0)
		$importe_parcial = substr($importe_parcial, 1, strlen($importe_parcial) - 1);
	
	if ($importe_parcial >= 1 && $importe_parcial <= 9.99)
		$car = 1;
	else if ($importe_parcial >= 10 && $importe_parcial <= 99.99)
		$car = 2;
	else if ($importe_parcial >= 100 && $importe_parcial <= 999.99)
		$car = 3;
	
	$parcial = substr($importe_parcial, 0, $car);
	$importe_parcial = substr($importe_parcial, $car);
	
	$num_letra = centena($parcial).centimos();
	
	return $num_letra;
}

function cien_mil()
{
	global $importe_parcial;
	
	$parcial = 0; $car = 0;
	
	while (substr($importe_parcial, 0, 1) == 0)
		$importe_parcial = substr($importe_parcial, 1, strlen($importe_parcial) - 1);
	
	if ($importe_parcial >= 1000 && $importe_parcial <= 9999.99)
		$car = 1;
	else if ($importe_parcial >= 10000 && $importe_parcial <= 99999.99)
		$car = 2;
	else if ($importe_parcial >= 100000 && $importe_parcial <= 999999.99)
		$car = 3;
	
	$parcial = substr($importe_parcial, 0, $car);
	$importe_parcial = substr($importe_parcial, $car);
	
	if ($parcial > 0)
	{
		if ($parcial == 1)
			$num_letra = "mil ";
		else
			$num_letra = centena($parcial)." mil ";
	}
	
	return $num_letra;
}


function millon()
{
	global $importe_parcial;
	
	$parcial = 0; $car = 0;
	while (substr($importe_parcial, 0, 1) == 0) 
	$importe_parcial = substr($importe_parcial, 1, strlen($importe_parcial) - 1);
	
	if ($importe_parcial >= 1000000 && $importe_parcial <= 9999999.99)
		$car = 1;
	else if ($importe_parcial >= 10000000 && $importe_parcial <= 99999999.99)
		$car = 2;
	else if ($importe_parcial >= 100000000 && $importe_parcial <= 999999999.99)
		$car = 3;
	
	$parcial = substr($importe_parcial, 0, $car);
	$importe_parcial = substr($importe_parcial, $car);
	
	if ($parcial == 1)
		$num_letras = "un milln ";
	else
		$num_letras = centena($parcial)." millones ";
	
	return $num_letras;
}

function convertir_a_letras($numero)
{
	global $importe_parcial;
	
	$importe_parcial = $numero;
	
	if ($numero < 1000000000)
	{
		if ($numero >= 1000000 && $numero <= 999999999.99)
			$num_letras = millon().cien_mil().cien();
		else if ($numero >= 1000 && $numero <= 999999.99)
			$num_letras = cien_mil().cien();
		else if ($numero >= 1 && $numero <= 999.99)
			$num_letras = cien();
		else if ($numero >= 0.01 && $numero <= 0.99)
		{
			if ($numero == 0.01)
				$num_letras = "un cntimo";
			else
				$num_letras = convertir_a_letras(($numero * 100)."/100")." cntimos";
		}
	}
	return $num_letras;
}
/***********************************/



$hora = getdate(time());
$hora_actual=$hora["hours"]-1 . ":" . $hora["minutes"];


//Funcion de resta de fechas
function meses($fech_ini,$fech_fin) {
	/*
	 FELIPE DE JESUS SANTOS SALAZAR, LIFER35@HOTMAIL.COM
	SEP-2010

	ESTA FUNCION NOS REGRESA LA CANTIDAD DE MESES ENTRE 2 FECHAS

	EL FORMATO DE LAS VARIABLES DE ENTRADA $fech_ini Y $fech_fin ES YYYY-MM-DD

	$fech_ini TIENE QUE SER MENOR QUE $fech_fin

	ESTA FUNCION TAMBIEN SE PUEDE HACER CON LA FUNCION date

	SI ENCUENTRAS ALGUN ERROR FAVOR DE HACERMELO SABER

	ESPERO TE SEA DE UTILIDAD, POR FAVOR NO QUIERES ESTE COMENTARIO, GRACIAS

	*/



	//SEPARO LOS VALORES DEL ANIO, MES Y DIA PARA LA FECHA INICIAL EN DIFERENTES
	//VARIABLES PARASU MEJOR MANEJO

	$fIni_yr=substr($fech_ini,0,4);
	$fIni_mon=substr($fech_ini,5,2);
	$fIni_day=substr($fech_ini,8,2);

	//SEPARO LOS VALORES DEL ANIO, MES Y DIA PARA LA FECHA FINAL EN DIFERENTES
	//VARIABLES PARASU MEJOR MANEJO
	$fFin_yr=substr($fech_fin,0,4);
	$fFin_mon=substr($fech_fin,5,2);
	$fFin_day=substr($fech_fin,8,2);

	$yr_dif=$fFin_yr - $fIni_yr;
	//echo "la diferencia de años es -> ".$yr_dif."<br>";
	//LA FUNCION strtotime NOS PERMITE COMPARAR CORRECTAMENTE LAS FECHAS
	//TAMBIEN ES UTIL CON LA FUNCION date
	if(strtotime($fech_ini) > strtotime($fech_fin)){
		echo 'ERROR -> la fecha inicial es mayor a la fecha final <br>';
		exit();
	}
	else{
		if($yr_dif == 1){
			$fIni_mon = 12 - $fIni_mon;
			$meses = $fFin_mon + $fIni_mon;
			return $meses;
			//LA FUNCION utf8_encode NOS SIRVE PARA PODER MOSTRAR ACENTOS Y
			//CARACTERES RAROS
			//echo utf8_encode("la diferencia de meses con un año de diferencia es -> ".$meses."<br>");
		}
		else{
			if($yr_dif == 0){
				$meses=$fFin_mon - $fIni_mon;
				return $meses;
				//echo utf8_encode("la diferencia de meses con cero años de diferencia es -> ".$meses.", donde el mes inicial es ".$fIni_mon.", el mes final es ".$fFin_mon."<br>");
			}
			else{
				if($yr_dif > 1){
					$fIni_mon = 12 - $fIni_mon;
					$meses = $fFin_mon + $fIni_mon + (($yr_dif - 1) * 12);
					return $meses;
					//echo utf8_encode("la diferencia de meses con mas de un año de diferencia es -> ".$meses."<br>");
				}
				else
					echo "ERROR -> la fecha inicial es mayor a la fecha final <br>";
				exit();
			}
		}
	}

}

//Funcion que suma o resta n dias a una fecha
function diasFecha($fecha,$dias,$operacion){
	Switch($operacion){
		case "sumar":
			$varFecha = date("Y-m-d", strtotime("$fecha + $dias day"));
			return $varFecha;
			break;
		case "restar":
			$varFecha = date("Y-m-d", strtotime("$fecha - $dias day"));
			return $varFecha;
			break;
		default:
			$varFecha = date("Y-m-d", strtotime("$fecha + $dias day"));
			break;
	}
}


function dateadd1($date, $dd=0, $mm=0, $yy=0, $hh=0, $mn=0, $ss=0)
{

      $date_r = getdate(strtotime($date));

      $date_result = date("Y-m-d",

                    mktime(($date_r["hours"]+$hh),
                           ($date_r["minutes"]+$mn),
                           ($date_r["seconds"]+$ss),
                           ($date_r["mon"]+$mm),
                           ($date_r["mday"]+$dd),
                           ($date_r["year"]+$yy)));

     return $date_result;
}

function dias_transcurridos($fecha_i,$fecha_f)
{
	$dias	= (strtotime($fecha_i)-strtotime($fecha_f))/86400;
	$dias 	= abs($dias); $dias = floor($dias);		
	return $dias;
}


?>