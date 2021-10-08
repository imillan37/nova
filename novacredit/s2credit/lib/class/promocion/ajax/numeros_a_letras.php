<?
/****************************************/
/*Fecha: 04/Mayo/2012
/*Autor: Tonathiu Cárdenas
/*Descripción: GENERA UNA CADENA DE CARÁCTERES CORRESPONDIENTE A UN VALÓR NUMÉRICO
/*Dependencias: valida_captura.js
/****************************************/

include("../../TNumeros.php");

/***********VALIDAMOS QUE EL RFC Y HOMOCLAVE NO SE ENCUENTREN EN LA BD**************/

if(isset($CAD_NUMERICA) && !empty($CAD_NUMERICA))
{
    $onum         = new Numeros(number_format($CAD_NUMERICA,2," ",""),"","pesos ");
    $CAD_NUMERICA = strtoupper($onum->cLetras);

	$CAD_NUMERICA =  str_ireplace('00/100','',$CAD_NUMERICA);
	$CAD_NUMERICA =  str_ireplace('PESOS','',$CAD_NUMERICA);
	
    echo $CAD_NUMERICA;
}



?>
