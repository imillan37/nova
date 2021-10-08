<script>
function calcularfc()
{
        if(document.frmrfc.elements['txt_rfc'].value == "")
        {

                var nombre = document.frmrfc.elements['txt_nombre'].value;
                var pat = document.frmrfc.elements['txt_apaterno'].value;
                var mat = document.frmrfc.elements['txt_amaterno'].value;
                var fecha = document.frmrfc.elements['fecha'].value;

                var rfc = CalcularRFC(nombre, pat,  mat, fecha);
                document.frmrfc.elements['txt_rfc'].value = rfc;
                document.frmrfc.elements['txt_homodig'].focus();
		var homo_clave = CalcularHomoclave(nombre,pat,mat);
	        var dig_ver = DigitoVerificador(rfc + homo_clave );
		document.frmrfc.elements['txt_homodig'].value=homo_clave + dig_ver;
        }
}
</script>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<HTML>
<HEAD>
<TITLE>Generar Homoclave</TITLE>
</HEAD>
<BODY BGCOLOR="#FFFFFF" TEXT="#000000" LINK="#FF0000" VLINK="#800000" ALINK="#FF00FF" BACKGROUND="?">
<?php
echo "<SCRIPT type='text/javascript' src='rfclib_pruebas.js'></SCRIPT>";

function fanio($fecha)      // Retrorna el a? de una fecha en formato MySQL i.e. A?-MES-DIA
{
 
	 if ($fecha == "Info. no disponible")
	 {
		return (""); 	
	 }

	 $anio=substr($fecha,0,4);	 
	 return($anio);

}
//------------------------------------------------------------------------------------------------- //
function fmes($fecha)		// Retrorna el mes de una fecha en formato MySQL i.e. A?-MES-DIA
{
 
	 if ($fecha == "Info. no disponible")
	 {
		return (""); 	
	 }
	 
	 $mes=substr($fecha,strpos($fecha,"-")+1,2);
	 $mes=str_replace("-","",$mes);
	 //$mes=substr($fecha,5,2);
	 
	 	

	 return($mes);

}
function fdia($fecha)			// Retrorna el dia de una fecha en formato MySQL i.e. A?-MES-DIA
{
 
	 if ($fecha == "Info. no disponible")
	 {
		return (""); 	
	 }

	 $dia=substr($fecha,strlen($fecha)-2,2);	// Ultimos 2 caracteres
	 $dia=str_replace("-","",$dia);
	 
	
	 return($dia);

}
function ffecha($fecha)		// Retorna una fecha en formato DIA/MES/A? de de una fecha en formato MySQL i.e. A?-MES-DIA
{

 if (strlen($fecha) > 10)
 {

 	$fecha = substr($fecha,0,10);
 
 }
 
 
 $new_fecha = fdia($fecha)."/".fmes($fecha)."/".fanio($fecha);



 return($new_fecha);

}
?>
<?php
if($_POST['send'])
{
//Inicio homoclave	
	$strNombreComp=strtoupper($txt_apaterno). " " . strtoupper($txt_amaterno) . " ". strtoupper($txt_nombre);	
	$strCharsHc = "123456789ABCDEFGHIJKLMNPQRSTUVWXYZ";
	$strCadena = "0";
	for($i=0;$i<=(strlen($strNombreComp));$i++)
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
	$txt_homodig=$RFCHomoclave;
//Fin homoclave	

//Inicio digito verificador

if($_POST['txt_rfc'])
{
  $strChars = "0123456789ABCDEFGHIJKLMN&OPQRSTUVWXYZ*";
  $temp=$txt_rfc.$RFCHomoclave;
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
$txt_homodig=$txt_homodig.$strDV;
}
//Fin digito verificador
}
?>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" name="frmrfc" >
<?php
echo "<H2 ALIGN='left'>Calcular homo clave</H2> <br>";
echo "<TABLE  ALIGN='left' BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH='25%'>";
echo "<TR  ALIGN='left' VALIGN='middle'>";
echo "<TH  ALIGN='right'>Nombre : &nbsp;</TH><TD ALIGN='left'><INPUT TYPE='text' name='txt_nombre'  size='20' maxlenth='30'></TD>";
echo "</TR><TR  ALIGN='left' VALIGN='middle'>";
echo "<TH  ALIGN='right'>A. Paterno : &nbsp;</TH><TD ALIGN='left'><INPUT TYPE='text' name='txt_apaterno'  size='20' maxlenth='30'></TD>";
echo "</TR><TR  ALIGN='left' VALIGN='middle'>";
echo "<TH  ALIGN='right'>A. Materno : &nbsp;</TH><TD ALIGN='left'><INPUT TYPE='text' name='txt_amaterno'  size='20' maxlenth='30'></TD>";
echo "</TR><TR  ALIGN='left' VALIGN='middle'>";
echo "<TH  ALIGN='right'>Fecha de nacimiento: &nbsp;</TH><TD ALIGN='left'>";
echo"<INPUT ID='small' READONLY type=text name=\"fecha\"         value='".ffecha($fecha_hoy)."'  size=12 MAXLENGTH=10 >";
echo "<BUTTON onClick='window.open(\"calendario.php?campo=fecha&forma=frmrfc\",\"selfecha\",\"width=220,height=220,menubar=0,toolbar=0,resizable=1,scrollbars=0\");' ID='S2'>...</BUTTON><BR>\n";
echo"</TD>";
echo "</TR><TR  ALIGN='left' VALIGN='middle'>";
echo "<TH  ALIGN='right'></TH><TD ALIGN='center'><input name='send' type='submit'  value='Enviar!'></TD>";
echo "</TR><TR  ALIGN='left' VALIGN='middle'>";
echo "<TH  ALIGN='right'>RFC : &nbsp;</TH><TD ALIGN='left'><INPUT TYPE='text' name='txt_rfc' onClick='javascript:calcularfc();'  size='12' maxlenth='10' value='".$txt_rfc."'>-<INPUT TYPE='text' name='txt_homodig' value='".$txt_homodig."' size='3' maxlenth='100'></TD>";
echo "</TABLE>";
echo "</form>";
?>
</BODY>
</HTML>

