<?
//--------------------------------------------------------------
//	Digitos Verificadores bancarios
//--------------------------------------------------------------




function digito_banorte($num_emp)
{


	$empresa=62762;
	$Sumadigitos=0;

	$j=2;
	for($i=1; $i<=strlen($num_emp);$i++)
	{

		$digito = (int) substr ($num_emp, -$i, 1);
		$multiplicacion=  $digito * $j;

		if($multiplicacion > 9)
		{
			$decenas=(int)($multiplicacion/10);
			$unidades=     ($multiplicacion % 10);

			$Sumadigitos+= ($decenas + $unidades);
		}
		else
			$Sumadigitos+=$multiplicacion;

	    $j=($j==2)?($j=1):($j=2);

	}


	$division = $Sumadigitos/ 10;
	$residuo = $Sumadigitos % 10;


 	if($residuo)
 		$digito_ver=10-$residuo;
 	else
 		$digito_ver=0;


	return($digito_ver);

}

//--------------------------------------------------------------

function digito_hsbc($num_ref)
{

	$num_ref = $num_ref * 1;




	$num_ref = str_repeat("0",(6-strlen($num_ref) )) . $num_ref;

	//debug("[".$num_ref."]");

	$Sumadigitos=0;



	for($i=1, $j=7; ($i<7 );$i++, $j--)
	{

		$digito = (int) substr($num_ref, ($i-1), 1);
		$Sumadigitos		+= $digito * $j;

	}
	$division = $Sumadigitos / 7;
	$residuo  = $Sumadigitos % 7;

 	$digito_ver = 7-$residuo;



	return($digito_ver);

}
//--------------------------------------------------------------

function digito_bancomer($num_ref)
{

	$sumadigitos=0;

    $mul=0;

	for($i=1;  ($i<=strlen($num_ref));$i++ )
	{


		$mul = ($mul != 2)?(2):(1);

		$d =(int) substr($num_ref, ($i-1), 1);

		$digito = $d * $mul;



		if($digito >= 10)
		{
			$decenas = floor($digito/10);

			$unidades = $digito - $decenas*10 ;

			$digito = $unidades+$decenas;

		}


		$sumadigitos += $digito ;

	}


	$decena_mas_cercana = (ceil($sumadigitos/10) * 10);


	$digito_ver = $decena_mas_cercana -$sumadigitos;


	return($digito_ver);

}
//--------------------------------------------------------------
function digito_banamex($suc, $cta, $ref)
{


	if(strlen($suc)<4)
		$suc = str_repeat("0",(4-strlen($suc))).$suc;


	if(strlen($cta)<7)
		$cta = str_repeat("0",(7-strlen($cta))).$cta;


	if(strlen($suc)<8)
		$ref = str_repeat("0",(8-strlen($ref))).$ref;



	$_suc=array(23,29,31,37);
	$_cta=array(13,17,19,23,29,31,37);
	$_ref=array(11,13,17,19,23,29,31,37);


	$Sumadigitos1=0;

	for($i=0; $i<4; $i++)
	{

		$digito = (int) substr($suc, ($i  ), 1);
		$Sumadigitos1		+= $digito * $_suc[$i];

	}

	$Sumadigitos2=0;

	for($i=0; $i<7; $i++)
	{

		$digito = (int) substr($cta, ($i  ), 1);
		$Sumadigitos2		+= $digito * $_cta[$i];

	}




	$Sumadigitos3=0;

	for($i=0; $i<8; $i++)
	{

		$digito = (int) substr($ref, ($i  ), 1);
		$Sumadigitos3		+= $digito * $_ref[$i];

	}


	$sumatoria = $Sumadigitos1+$Sumadigitos2+$Sumadigitos3;
	$residuo = $sumatoria % 97;


	$DD = 99 - $residuo ;

	$DD =($DD <=9)?("0".$DD):($DD);
	return($DD);


//--------------------------------------------------------------



}
?>