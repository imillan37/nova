<?
/*   
         ________________________________________________________
        |  Titulo: Busca Códigos postales                       |
        |                                                       |
##      |  Autor : Enrique Godoy Calderón                       |
##      |                                                       |
##      |  Fecha : Miércoles, 23 de Julio de 2008               |
##      |                                                       |
##      |  Descripción : Buscar un código postal utilizando     |
##      |                los datos del cliente como axiliar a   |
##      |                la captura de solicitudes.             |
##      |                                                       |
##      |  Dependencias :Captura de solicitudes                 |
##      |                captura.php                            |
##       ¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯
######################################################
######################################################

*/

//$heading_title = " Búseque de códigos postales  ";
$exit=true;

include($DOCUMENT_ROOT."/rutas.php");

$db = &ADONewConnection(SERVIDOR);
$db->PConnect(IP,USER,PASSWORD,NUCLEO);

//verflujo();

?>
<SCRIPT>

function putcp(val, frm, col, edo, cty, pob)
{
     
     
     var myform  = window.opener.document.<?=$frm?>;
     var myfield = myform.elements['<?=$campo;?>'];
     
     myfield.value = val;
     
     
     var iframe = window.opener.frames['loadvals'];
     var url = 'lvals2.php?cp='+val+'&frm='+frm+'&col='+col+'&edo='+edo+'&cty='+cty+'&pob='+pob;

    
     iframe.location.replace(url);

     window.close();
     
     
     
     

}

</SCRIPT>

<?

echo "<FORM Method='POST' ACTION='".$PHP_SELF."' NAME='buscacp' >";

echo "<INPUT TYPE='HIDDEN' NAME='campo' VALUE='$campo'>";
echo "<INPUT TYPE='HIDDEN' NAME='frm' VALUE='$frm'>";
echo "<INPUT TYPE='HIDDEN' NAME='col' VALUE='$col'>";
echo "<INPUT TYPE='HIDDEN' NAME='edo' VALUE='$edo'>";
echo "<INPUT TYPE='HIDDEN' NAME='cty' VALUE='$cty'>";
echo "<INPUT TYPE='HIDDEN' NAME='pob' VALUE='$pob'>";

echo "<BR>";
echo"<TABLE  CELLSPACING='2' SUMMARY='DATOS ASOCIADOS AL CRÉDITO'  ALIGN='LEFT' WIDTH='100%'>

	<TR  BGCOLOR='#7A9CCF' >
		<TH  ALIGN='LEFT' COLSPAN='2'>
		    <B> <FONT SIZE='2' COLOR='WHITE'>BUSQUEDA DE CÓDIGO POSTAL</FONT></B>
		</TH>
	</TR>
	<TR Bgcolor='#e7eef6'>
		<TH ALIGN='RIGHT'     WIDTH='50%'><FONT size='2' > Estado de la república :&nbsp;&nbsp;</FONT></TH>";
	
		$sql = "SELECT ID_Estado, Nombre
		        FROM   estados
		        ORDER BY Nombre ";
	
		$rs=$db->Execute($sql);
	

		echo"<TD ALIGN='LEFT'  WIDTH='50%'>
			<SELECT NAME='id_estado' OnChange='document.buscacp.submit();' ID='small'>
			        
	        	<OPTION value=''> Seleccione un estado </OPTION>";
			
			while(! $rs->EOF )
			{
			        
			        $sel = ($rs->fields[0] == $id_estado)?("SELECTED"):("");
			        echo "<OPTION value='".$rs->fields[0]."' ".$sel.">".$rs->fields[1]." </OPTION>";
			        $rs->MoveNext();
			}
		echo"</SELECT>
	</TD>

 </TR>";

if( empty($id_estado) )
    die("</FORM></BODY></HTML>");
    

$sql = "SELECT ID_Ciudad, Nombre
        FROM ciudades
        WHERE  ID_Ciudad != 0 and ID_Estado = '".$id_estado."'
        ORDER BY Nombre ";    

$rs=$db->Execute($sql);

echo"<TR Bgcolor='#e7eef6'>
	<TH ALIGN='RIGHT'     WIDTH='50%'><FONT size='2' > Ciudad, solo si es el caso :&nbsp;&nbsp;</FONT></TH>";
	echo"<TD ALIGN='LEFT'  WIDTH='50%'>
             <SELECT NAME='id_ciudad' OnChange='document.buscacp.submit();'  ID='small'>";        
echo "          <OPTION value=''> </OPTION>";

$sel = ((isset($id_ciudad)) and ( empty($id_ciudad)))?("SELECTED"):("");
echo "          <OPTION value='0' ".$sel."> No vivo en ninguna ciudad. </OPTION>";
while(! $rs->EOF )
{
        
        $sel = ($rs->fields[0] == $id_ciudad)?("SELECTED"):("");
        echo "<OPTION value='".$rs->fields[0]."' ".$sel.">".$rs->fields[1]." </OPTION>";
        $rs->MoveNext();
}
echo "</SELECT>";

echo"</TD>";
echo"</TR>";




$sql = "SELECT ID_Municipio, nombre
        FROM municipios
        WHERE ID_Estado = '".$id_estado."'
        ORDER BY Nombre "; 

$rs=$db->Execute($sql);

echo"<TR Bgcolor='#e7eef6'>
	<TH ALIGN='RIGHT'     WIDTH='50%'><FONT size='2' > Delegación / Municipio / Población :&nbsp;&nbsp;</FONT></TH>";
	echo"<TD ALIGN='LEFT'  WIDTH='50%'>";

echo "<SELECT NAME='id_municipio' OnChange='document.buscacp.submit();'  ID='small'>";
        
echo "          <OPTION value=''> </OPTION>";

while(! $rs->EOF )
{
        
        $sel = ($rs->fields[0] == $id_municipio)?("SELECTED"):("");
        echo "<OPTION value='".$rs->fields[0]."' ".$sel.">".$rs->fields[1]." </OPTION>";
        $rs->MoveNext();
}
echo "</SELECT>";

echo"</TD>";
echo"</TR>";

if(empty($id_municipio))
    die("</FORM></BODY></HTML>");
    

$id_ciudad = (empty($id_ciudad))?("0"):($id_ciudad);

$sql = "SELECT ID_Colonia, Colonia
        FROM codigos_postales
        WHERE   ID_Estado='".$id_estado."' and                 
                ID_Municipio='".$id_municipio."'
         ORDER BY Colonia ";
        
$rs=$db->Execute($sql);        

echo"<TR Bgcolor='#e7eef6'>
	<TH ALIGN='RIGHT'     WIDTH='50%'><FONT size='2' > Colonia :&nbsp;&nbsp;</FONT></TH>";
	echo"<TD ALIGN='LEFT'  WIDTH='50%'>";
	
echo "<SELECT NAME='id_colonia' OnChange='document.buscacp.submit();'  ID='small'>";
        
echo "          <OPTION value=''> </OPTION>";

while(! $rs->EOF )
{
        
        $sel = ($rs->fields[0] == $id_colonia)?("SELECTED"):("");
        echo "<OPTION value='".$rs->fields[0]."' ".$sel.">".$rs->fields[1]." </OPTION>";
        $rs->MoveNext();
}
echo "</SELECT>";

echo"</TD>";
echo"</TR>";


       
if(empty($id_colonia))
    die("</FORM></BODY></HTML>");
        
        
$sql = "SELECT CP
        FROM codigos_postales
        WHERE ID_Estado    = '".$id_estado."' and 
              ID_Municipio = '".$id_municipio."'  and
              ID_Colonia   = '".$id_colonia."' 
         ORDER BY Colonia ";



$rs=$db->Execute($sql);      


 

        echo"<TR Bgcolor='#e7eef6'>
	<TH ALIGN='CENTER'     COLSPAN='2'><FONT size='2' >";
        while(! $rs->EOF )
        {


                echo "<BUTTON onClick='putcp(\"".$rs->fields[0]."\" ,\"".$frm."\",\"".$col."\",\"".$edo."\",\"".$cty."\",\"".$pob."\");' >".$rs->fields[0]." </BUTTON><BR>";
                
                 
                
                $rs->MoveNext();
        }
        echo "</FONT></TH>";


echo"</TABLE>";

echo "</FORM>";

?>
</BODY>
</HTML>