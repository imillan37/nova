<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<HTML>
<HEAD>

<TITLE> Auto-Filtro </TITLE>

</HEAD>

<BODY>
<?
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
if(! isset($fields ))
{
$fields   = array(	"Cotizac_MST.ID_Cotizac",			
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
if(empty($fields)) 
{
	echo "<SCRIPT> window.close();</SCRIPT>";
	echo "</BODY>";			
	echo "</HTML>";
}
		
if(empty($titulos)) {$titulos = strtoupper($titulos); }

if(empty($options)) {$options=array(); }

if(empty($mask)) 	{$mask==array(); }
				
				
				
				
	echo "<FORM NAME='newcondition' ACTION='".$PHP_SELF."' METHOD='POST'> \n";
	
	echo "<TABLE BORDER=1>";

	
	echo "<TR><TD>Columna : </TD><TD><SELECT NAME='column' >\n";
	
	for($i=0; $i<count($fields); $i++)
		{	
			$sel=(str_replace("'", "`",$fields[$i])==$column)?("SELECTED"):("");
			echo "\t<OPTION VALUE='".str_replace("'", "`",$fields[$i])."' $sel > ".$titulos[$i]."</OPTION>\n";
		}
	echo "</SELECT></TD></TR>\n\n";

	echo "<TR><TD>Operador : </TD><TD><SELECT NAME='oper' onChange='document.newcondition.sub.click();'>\n";
					
					$sel=($oper=='=')?(" SELECTED "):("");
					echo "\t<OPTION VALUE='=' 	  $sel> ( = ) igual 			</OPTION>\n";
					$sel=($oper=='>')?(" SELECTED "):("");
					echo "\t<OPTION VALUE='>' 	  $sel> ( > ) mayor que			</OPTION>\n";
					$sel=($oper=='>=')?(" SELECTED "):("");
					echo "\t<OPTION VALUE='>='	  $sel> ( >=) mayor igual		</OPTION>\n";
					$sel=($oper=='<')?(" SELECTED "):("");
					echo "\t<OPTION VALUE='<' 	  $sel> ( < ) menor que  		</OPTION>\n";
					$sel=($oper=='<=')?(" SELECTED "):("");
					echo "\t<OPTION VALUE='<='	  $sel> ( <=) menor igual 		</OPTION>\n";
					$sel=($oper=='<>')?(" SELECTED "):("");
					echo "\t<OPTION VALUE='<>'	  $sel> ( <>) diferente			</OPTION>\n";
					$sel=($oper=='LIKE')?(" SELECTED "):("");
					echo "\t<OPTION VALUE='LIKE'  $sel> ( ~ ) parecido 		    </OPTION>\n";

	echo "</SELECT>\n\n\n";

	echo "<INPUT TYPE='SUBMIT' NAME='sub' VALUE='->'></TD></TR> \n";

   for($i=0; $i<count($options); $i++)
   	echo "<INPUT TYPE='HIDDEN' NAME='options[$i]' VALUE='".str_replace("'", "`",$options[$i])."'> \n";
	echo "\n\n";
	
   for($i=0; $i<count($fields); $i++)
   	echo "<INPUT TYPE='HIDDEN' NAME='fields[$i]'  VALUE='".str_replace("'", "`",$fields[$i])."'> \n";
    echo "\n\n";		

   for($i=0; $i<count($mask); $i++)
   	echo "<INPUT TYPE='HIDDEN' NAME='mask[$i]'    VALUE='".$mask[$i]."'> \n";
	echo "\n\n";
	



	echo "</FORM>";
	
	
	
	if(empty($oper) or empty($column)) die("</TABLE>\n\n </BODY> </HTML>");
	
	
		echo "\n\n<FORM NAME='newvalue' ACTION='".$PHP_SELF."' METHOD='POST'> \n";
		
	   	echo "<INPUT TYPE='HIDDEN' NAME='oper'		VALUE='$oper'	>\n";
	   	echo "<INPUT TYPE='HIDDEN' NAME='column'	VALUE='$column' >\n\n";
	
	    $clave =array_search ( $column, $fields);
	    
	    if($clave === false) 
	    {
	    	echo "<TR><TD>(*)Valor : </TD><TD><INPUT TYPE='TEXT' NAME='valor' value='$valor'>\n";
	    }
		else
		 switch($mask[$clave]) 
			{
				    case 'F' : echo "<TR><TD>(F)Valor : </TD><TD><INPUT style='text-align:right;'  TYPE='TEXT'  size='15' maxlength='25'  NAME='valor' VALUE='$valor' >\n";   break;
				
					case 'M' : echo "<TR><TD>(M)Valor : </TD><TD>$<INPUT style='text-align:right;' TYPE='TEXT'  size='15' maxlength='25'  NAME='valor' VALUE='$valor'>\n"; break;
				
				    case 'I' : echo "<TR><TD>(I)Valor : </TD><TD>$<INPUT style='text-align:right;' TYPE='TEXT'  size='15' maxlength='25'  NAME='valor' VALUE='$valor'  >\n"; break; break;

				    case 'D' : echo "<TR><TD>(D)Valor : </TD><TD>";		    	 				
                               echo "<INPUT TYPE='TEXT  ID='S2  NAME='valor' VALUE='$valor' SIZE='25' MAXLENGTH='10' READONLY > ";
   							   echo "<BUTTON   ID='S2'  onClick='window.open(\"/sica/lib/class/calendario.php?campo=valor&forma=newvalue\",\"selfecha\",\"width=220,height=220,menubar=0,toolbar=0,resizable=1,scrollbars=0,status=1\");'> ... </BUTTON> "; 
 
							   break;	    

				    default :  echo "<TR><TD>(*)Valor : </TD><TD><INPUT TYPE='TEXT' NAME='valor' VALUE='$valor' >";
			}

	    	
		echo " <INPUT TYPE='SUBMIT' NAME='sub' VALUE='->'></TD></TR>\n\n </FORM>\n\n";
	
	
	
	if(empty($valor)) die("</TABLE>\n\n");
	
	
	echo "\n\n<FORM NAME='newconector' ACTION='".$PHP_SELF."' METHOD='POST'> \n";
			
	echo "<INPUT TYPE='HIDDEN' NAME='oper'		VALUE='$oper'	>\n";
	echo "<INPUT TYPE='HIDDEN' NAME='column'	VALUE='$column' >\n";
	echo "<INPUT TYPE='HIDDEN' NAME='valor' 	VALUE='$valor'	>\n";

	
	echo "<TR><TD>Conector : </TD><TD>\n\n<SELECT NAME='conector' >\n";
											
					$sel=($conector=='*')?(" SELECTED "):("");
					echo "\t<OPTION VALUE='*'   $sel > 		    </OPTION>\n";
					$sel=($conector=='and')?(" SELECTED "):("");
					echo "\t<OPTION VALUE='and' $sel > y además...</OPTION>\n";
					$sel=($conector=='or')?(" SELECTED "):("");
					echo "\t<OPTION VALUE='or' $sel  > o bien...</OPTION>\n";



	echo "</SELECT> \n\n<INPUT TYPE='SUBMIT' NAME='sub' VALUE='->'></TD></TR>\n\n";
	
	if(empty($conector)) die("</TABLE>\n\n");
	
	
	
	
	
				
/*				
		echo "<BR><BR>
					<IFRAME 
						SRC='Autovalue.php' 
						ALIGN='center' 
						valign='MIDDLE' 
						NAME='setnewvalue' 
						FRAMEBORDER=1 
						WIDTH='98%'
						MARGINHEIGHT=0
						MARGINWIDTH=0
						vspace=0
						hspace=0
						SCROLLING=1>
				 </IFRAME>";

				
*/				
				
?>		 
</BODY>			
</HTML>
