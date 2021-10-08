<?
/*
         ________________________________________________________
        |  Titulo: LVals                                        |
        |                                                       |
##      |  Autor : Jesús Tonathiu Cárdenas Pérez                |
##      |                                                       |
##      |  Fecha : Wednesday, July 26, 2006                     |
##      |                                                       |
##      |  Descripción : Cálculo de homoclave                   |
##      |                                                       |
##      |  Dependencias :                                       |
##      |                                                       |
##      |                                                       |
##       ¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯
######################################################
######################################################
*/

if(!empty($Nombre))
{
//Inicio homoclave
	$strNombreComp=strtoupper($Apat). " " . strtoupper($Amat) . " ". strtoupper($Nombre);
	$strCharsHc = "123456789ABCDEFGHIJKLMNPQRSTUVWXYZ";
	$strCadena = "0";
	for($i=0;$i<=(strlen($strNombreComp))-1;$i++)
	{
	   $strChr=substr($strNombreComp,$i,1);
	   switch ($strChr)
	       {
		case " ":
		 case"-":
			$strCadena = $strCadena . "00";
	

		 break;

		 case "Ñ":
		 case "Ü":
			 $strCadena = $strCadena . "10";
		 break;

		 case "A":
		 case "B":
		 case "C":
		 case "D":
		 case "E":
		 case "F":
		 case "G":
		 case "H":
		 case "I":
			$strCadena = $strCadena . (ord($strChr)-54);

		 break;

		 case "J":
		 case "K":
		 case "L":
		 case "M":
		 case "N":
		 case "O":
		 case "P":
		 case "Q":
		 case "R":
		       $strCadena = $strCadena . (ord($strChr)-53);

		 break;

		 case "S":
		 case "T":
		 case "U":
		 case "V":
		 case "W":
		 case "X":
		 case "Y":
		 case "Z":
		       $strCadena = $strCadena . (ord($strChr)-51);

		 break;
		 default:
		 	$strCadena.="00";

		 	break;
	       }
	}

	for($i=0;$i<strlen($strCadena)-1;$i++)
	{
	  $intNum1 = intval(substr($strCadena,$i,2));
	  $intNum2 = intval(substr($strCadena,$i+1,1));
	  $intSum = intval($intSum) + intval($intNum1 * $intNum2);

	}

	$int3 = intval(substr("$intSum",-3));
	$intQuo = intval($int3 / 34);
	$intRem = $int3 % 34;

	$RFCHomoclave = substr($strCharsHc, ($intQuo), 1) . substr($strCharsHc,($intRem), 1);

//Fin homoclave
//Inicio digito verificador

	  $strChars = "0123456789ABCDEFGHIJKLMN&OPQRSTUVWXYZ*";
	  $temp=$Rfc.$RFCHomoclave;
	  for($i=0;$i<strlen($temp);$i++)
	  {
	   $strCh=substr($temp,$i,1);
	   $strCh= (($strCh == " ") ? "*":$strCh);
	   $intIdx = strpos($strChars,$strCh);
	   $intSumas = $intSumas + $intIdx * (14 - ($i+1));
	   }

	   if (($intSumas % 11)==0)
	  {
	   $strDV=0;
	  }
	  else
	  {
	    $intDV = 11 - $intSumas % 11;
	    if($intDV > 9)
	    {
	     $strDV="A";
	    }
	    else
	    {
	       $strDV=$intDV;
	    }
	  }

echo $RFCHomoclave.$strDV;
}
?>
