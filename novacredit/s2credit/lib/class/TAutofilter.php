<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<HTML>
<HEAD>

<TITLE> Auto-Filtro </TITLE>

</HEAD>

<BODY>
<?
$self= $PHP_SELF;

function gfecha($fecha)		// Retorna una fecha en formato MySQL(AÑO-MES-DIA)  en formato  de DIA/MES/AÑO 
{

$xd=0;
$dia=substr($fecha,0,2);
	if(strpos ($dia,"/"))
	{
		$dia=substr($dia,0,1); 
		$xd=1;
	
	}
$mes=substr($fecha,(3-$xd),2);
	if($xm=strpos ($mes,"/"))
	{
		$mes=substr($mes,$xm-1,1); 
		
	}
	

 $new_fecha = substr($fecha,(strlen($fecha)-4),4).'-'.$mes.'-'.$dia;
 return($new_fecha);


}



//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//

function show($column,$oper,$valor,$conector)
    {
    	global $fields, $mask, $titulos, $a_sql_oper, $a_human_oper, $a_sql_conector, $a_human_conector, $a_sql_condicion, $a_human_condicion;

    	if(!empty($column) and !empty($valor))
    	{
    		$clave =array_search ( str_replace("'", "`",$fields[($column-1)]), $fields);
    		if($mask[$clave]=='D') $sql_val = gfecha($valor); else $sql_val=$valor;
		}	
		
		$sql_condicion    = "";
		$human_condicion  = "";
		
		
		echo"\n\n</FORM><BR><BR>\n";
		echo"\n<!-- -------------------------------------------------------- -->\n";
		
		if(!empty($column))
		{
			$sql_condicion    .= $fields[$column-1]." ";
			$human_condicion  .= "La columna  <B>".$titulos[$column-1]."</B> sea ";
			
			if(!empty($oper))
			{								
				switch($oper)
				{
					case 1 :
					case 5 : $prep = " a "; 	break;
					case 2 : 
					case 3 :
					case 4 : $prep = " que ";	break;
					default: $prep = " "; 	
				}

				$sql_condicion    .= $a_sql_oper[$oper-1]; 
				$human_condicion  .= " <B>".$a_human_oper[$oper-1]."</B>  ".$prep;
				
				if(!empty($valor))
				{	
					
					$sql_condicion    .=  " '".$sql_val."' ";  
					$human_condicion  .=  " <B>".$valor."</B> ";
				
					 if(!empty($conector))
					 {					
						if($conector-1) $sql_condicion .= $a_sql_conector[$conector-1]; 
						$human_condicion .= $a_human_conector[$conector-1];
					 }
				 }			
			 }				
		 }
			
		echo "\n\n\n\n</TD><TD>\n\n\n";
    	
    	echo "<CENTER><B><FONT COLOR='blue'> Condición :  </FONT></B>".$human_condicion."</CENTER>\n\n\n";


				//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//

		
		echo "<FORM NAME='addcondition' ACTION='".$PHP_SELF."' METHOD='POST'> \n\n";
		
		
		echo "<INPUT TYPE='HIDDEN' NAME='sql_condicion' VALUE='".str_replace("'", "`",$sql_condicion)."'>\n";
		echo "<INPUT TYPE='HIDDEN' NAME='human_condicion' VALUE='".$human_condicion."'>\n\n";
		
		if($a_sql_condicion)	
		for($i=0; $i<count($a_sql_condicion); $i++)
		{
		   		echo "<INPUT TYPE='HIDDEN' NAME='a_sql_condicion[$i]' VALUE='".$a_sql_condicion[$i]."'> \n";
		   		echo "<INPUT TYPE='HIDDEN' NAME='a_human_condicion[$i]' VALUE='".$a_human_condicion[$i]."'> \n\n";
		}
	    echo "\n\n";
		
		

		$enabled=($oper and $valor and $column )?(""):("DISABLED");
		echo "<CENTER><INPUT TYPE='SUBMIT' NAME='agregar' VALUE='Agregar esta condición' $enabled ></CENTER>";
		  for($i=0; $i<count($options); $i++)
			echo "<INPUT TYPE='HIDDEN' NAME='options[$i]' VALUE='".str_replace("'", "`",$options[$i])."' > \n";
			echo "\n\n";

		  for($i=0; $i<count($fields); $i++)
			echo "<INPUT TYPE='HIDDEN' NAME='fields[$i]'  VALUE='".str_replace("'", "`",$fields[$i])."' > \n";
			echo "\n\n";		

		  for($i=0; $i<count($mask); $i++)
			echo "<INPUT TYPE='HIDDEN' NAME='mask[$i]'    VALUE='".$mask[$i]."' > \n";
			echo "\n";				
		
 		echo "\n</FORM> \n\n";
		
	    echo "\n\n\n\n</TD>\n";
	    echo "</TR>\n";
		echo "</TABLE>\n<BR>";		
		
		
		
		echo "\n\n<!-- Eliminar condiciones no deseadas -->\n\n";
		
		echo "<FORM NAME='delcondition' ACTION='$self' METHOD='POST'> \n\n";
		if($a_sql_condicion)	
		 for($i=0; $i<count($a_sql_condicion); $i++)
		  {
			 echo "<INPUT TYPE='HIDDEN' NAME='a_sql_condicion[$i]' VALUE='".$a_sql_condicion[$i]."'> \n";
			 echo "<INPUT TYPE='HIDDEN' NAME='a_human_condicion[$i]' VALUE='".$a_human_condicion[$i]."'> \n";

		  }
	    echo "\n\n";
	    
	   for($i=0; $i<count($options); $i++)
		echo "<INPUT TYPE='HIDDEN' NAME='options[$i]' VALUE='".str_replace("'", "`",$options[$i])."' > \n";
		echo "\n\n";

	   for($i=0; $i<count($fields); $i++)
		echo "<INPUT TYPE='HIDDEN' NAME='fields[$i]'  VALUE='".str_replace("'", "`",$fields[$i])."' > \n";
		echo "\n\n";		

	   for($i=0; $i<count($mask); $i++)
		echo "<INPUT TYPE='HIDDEN' NAME='mask[$i]'    VALUE='".$mask[$i]."' > \n";
		echo "\n\n";	    

	    
		echo "<DIR>\n";
	    if(count($a_sql_condicion))
	    echo "<INPUT TYPE='submit' NAME='elimiar' VALUE='Eliminar'> <BR>\n\n";		
		
		echo "<OL>\n ";
		for($i=0; $i<count($a_sql_condicion); $i++)
		   {
			   		echo "<LI><INPUT TYPE='radio' name='delkey' VALUE='".($i+1)."'> ".$a_human_condicion[$i]."</LI> \n "; 		
	       }
	    echo " </OL>\n</DIR>\n ";
	    echo "</FORM> \n";

	} 
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
// Eliminar 

if(!empty($delkey))
{


	for($i=0, $j=0; $i<count($a_sql_condicion); $i++)	

     if(($i+1) != $delkey)
		 {
			$t_human_condicion[$j] = $a_human_condicion[$i];
			$t_sql_condicion[$j]   = $a_sql_condicion[$i];
			$j++;
		 }
		  
		$a_human_condicion = $t_human_condicion;
		$a_sql_condicion   = $t_sql_condicion;

}


	
	
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
if(! isset($fields ))
{							
$fields    = array(	"Cotizac_MST.ID_Cotizac",			
					"IF(Clientes.Nombre IS NULL, Cotizac_MST.Destinatario, Clientes.Nombre)",			
					"DATE_FORMAT(Cotizac_MST.Fecha_Emision,'%d/%m/%Y')",
					"DATE_FORMAT(Cotizac_MST.Fecha_Caducidad,'%d/%m/%Y')",				
					"Cotizac_MST.Impresion",		
 					"CONCAT('$',FORMAT(SUM(Cotizac_DTL.Cantidad*(0.15)*( Cotizac_DTL.Precio - Cotizac_DTL.Descuento)),2))");


$mask = array(	"I",			
				"SU",			
				"D",
				"D",				
				"O",		
 				"M");


}

if(! isset($titulos))
{					
$titulos =  array(	"Folio",			
					"Cliente",			
					"Fecha",			
					"Vigencia",			
					"Se imprimió",		
					"Monto");
					
}


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//

$a_sql_oper	 =array( "=", 	
					 "&gt;", 	
					 "&gt;=",	
					 "&lt;", 	
					 "&lt;=",	
					 "!=",	 	
					 "LIKE" );	
					 	
$a_human_oper =array( "igual ",
					  "mayor ",
					  "mayor igual ",
					  "menor ",
					  "menor igual ",
					  "diferente ",
					  "parecido ");


$a_sql_conector	 =array( "@", 	
						 " and ", 	
						 " or ");	
					 	
$a_human_conector =array( " ",
					      "y además...",
					      "o bien...");



//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//



if(empty($fields)) 
{
	echo "<SCRIPT> window.close();</SCRIPT>";
	echo "</BODY>";			
	echo "</HTML>";
}
		
if(empty($titulos)) {$titulos = strtoupper($titulos); }

if(empty($options)) {$options=array(); }

if(empty($mask)) 	{$mask==array(); }
				
if (!isset($a_sql_condicion)) $a_sql_condicion=array();

if (!isset($a_human_condicion)) $a_human_condicion=array();





if(!empty($sql_condicion) )
{
	$nas=count($a_sql_condicion);

	$a_sql_condicion[$nas]  =$sql_condicion;
	$a_human_condicion[$nas]=$human_condicion;

	//echo "<BR> sql_condicion $sql_condicion";
}





//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
// Interfaz de generación de condiciones en lenguaje natural.
if(!empty($a_sql_condicion) )
{
	$nas=count($a_sql_condicion)-1;

	$_and =strpos($a_sql_condicion[$nas]," and ");
	$_or  =strpos($a_sql_condicion[$nas]," or ");	
	
	if(($_and ==0) and ($_or == 0))
		$_disabled=" DISABLED ";
	else
 		$_disabled="";
	
}	
else
 $_disabled="";
	
	

	

	

	

	echo "<FORM NAME='newcondition' ACTION='".$PHP_SELF."' METHOD='POST' $_disabled> \n";
	
	echo "<TABLE BORDER=1 WIDTH='100%' >";
	echo "<TR>";
	echo "<TD WIDTH='50%' >";
	

	echo "<TABLE BORDER=1 WIDTH='100%'  CELLSPACING='0' CELLPADDING='0'  >";

	
	echo "<TR><TD Align='right'>Columna : </TD><TD><SELECT NAME='column' $_disabled>\n";	
	for($i=0; $i<count($fields); $i++)
		{	
			$sel=($i==($column-1))?("SELECTED"):("");
			echo "\t<OPTION VALUE='".($i+1)."' $sel > ".$titulos[$i]."</OPTION>\n";
		}
	echo "</SELECT></TD></TR>\n\n";



	echo "<TR><TD  Align='right'>Operador : </TD><TD><SELECT NAME='oper' onChange='document.newcondition.sub.click();' $_disabled>\n";	
	for($i=0;$i<count($a_sql_oper);$i++)
	{
			$sel=(($oper-1)==$i)?(" SELECTED "):("");
			echo "\t<OPTION VALUE='".($i+1)."' $sel> ".$a_human_oper[$i]."</OPTION>\n";
    }
	echo "</SELECT>\n\n\n";


	echo "<INPUT TYPE='SUBMIT' NAME='sub' VALUE='->' $_disabled></TD></TR> \n";



   for($i=0; $i<count($options); $i++)
   	echo "<INPUT TYPE='HIDDEN' NAME='options[$i]' VALUE='".str_replace("'", "`",$options[$i])."' > \n";
	echo "\n\n";
	
   for($i=0; $i<count($fields); $i++)
   	echo "<INPUT TYPE='HIDDEN' NAME='fields[$i]'  VALUE='".str_replace("'", "`",$fields[$i])."' > \n";
    echo "\n\n";		

   for($i=0; $i<count($mask); $i++)
   	echo "<INPUT TYPE='HIDDEN' NAME='mask[$i]'    VALUE='".$mask[$i]."' > \n";
	echo "\n\n";

   if($a_sql_condicion)	
   for($i=0; $i<count($a_sql_condicion); $i++)
   {
   		echo "<INPUT TYPE='HIDDEN' NAME='a_sql_condicion[$i]' VALUE='".$a_sql_condicion[$i]."'> \n";
   		echo "<INPUT TYPE='HIDDEN' NAME='a_human_condicion[$i]' VALUE='".$a_human_condicion[$i]."'> \n\n";
   }
	echo "\n\n";





	if(empty($oper) or empty($column))
	{
		echo "</TABLE>\n\n";
		show($column,$oper,$valor,$conector);
		die("</BODY> </HTML>");
	}
	
	//----------------------------------------------------------------------------------------------------------//
	
	
	    $clave =array_search ( str_replace("'", "`",$fields[($column-1)]), $fields);
	    
	    if($clave === false) 
	    {
	    	echo "<TR><TD  Align='right'>(*)Valor : </TD><TD><INPUT TYPE='TEXT' NAME='valor' value='$valor'>\n";
	    }
		else
		 switch($mask[$clave]) 
			{
				    case 'F' : echo "<TR><TD  Align='right'>(F)Valor : </TD><TD><INPUT style='text-align:right;'  TYPE='TEXT'  size='15' maxlength='25'  NAME='valor' VALUE='$valor' $_disabled>\n";   break;
				
					case 'M' : echo "<TR><TD  Align='right'>(M)Valor : </TD><TD>$<INPUT style='text-align:right;' TYPE='TEXT'  size='15' maxlength='25'  NAME='valor' VALUE='$valor' $_disabled>\n"; break;
				
				    case 'I' : echo "<TR><TD  Align='right'>(I)Valor : </TD><TD>$<INPUT style='text-align:right;' TYPE='TEXT'  size='15' maxlength='25'  NAME='valor' VALUE='$valor' $_disabled>\n"; break; break;

				    case 'D' : echo "<TR><TD  Align='right'>(D)Valor : </TD><TD>";		    	 				
                               echo "<INPUT TYPE='TEXT  ID='S2  NAME='valor' VALUE='$valor' SIZE='25' MAXLENGTH='10' READONLY > ";
   							   echo "<BUTTON   ID='S2'  onClick='window.open(\"/sica/lib/class/calendario.php?campo=valor&forma=newcondition\",\"selfecha\",\"width=220,height=220,menubar=0,toolbar=0,resizable=1,scrollbars=0,status=1\");' $_disabled> ... </BUTTON> "; 
 
							   break;	    

				    default :  echo "<TR><TD  Align='right'>(*)Valor : </TD><TD><INPUT TYPE='TEXT' NAME='valor' VALUE='$valor' >";
			}

	    	
		echo " <INPUT TYPE='SUBMIT' NAME='sub' VALUE='->' $_disabled></TD></TR>\n\n ";
	    
	
	if(empty($valor))
	{
		echo "</TABLE>\n\n";
		show($column,$oper,$valor,$conector);
		die("</BODY> </HTML>");
	}
	
	//----------------------------------------------------------------------------------------------------------//

	
	echo "<TR><TD  Align='right' >Conector : </TD><TD>\n\n<SELECT NAME='conector'>\n";
											
	for($i=0;$i<count($a_sql_conector);$i++)
	{
			$sel=(($conector-1)==$i)?(" SELECTED "):("");
			echo "\t<OPTION VALUE='".($i+1)."' $sel> ".$a_human_conector[$i]."</OPTION>\n";
    }


	echo "</SELECT> \n\n<INPUT TYPE='SUBMIT' NAME='sub' VALUE='->' $_disabled></TD></TR>\n\n";
	
	if(empty($conector))
	{
		echo "</TABLE>\n\n";
		show($column,$oper,$valor,$conector);
		die("</BODY> </HTML>");
	}
	
	echo "</TABLE>\n\n";
	show($column,$oper,$valor,$conector);
	//----------------------------------------------------------------------------------------------------------//
	


    
		
?>		 
</BODY>			
</HTML>
