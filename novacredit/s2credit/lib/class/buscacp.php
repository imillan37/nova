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

$heading_title = " Búseque de códigos postales  ";
$exit=true;
include($DOCUMENT_ROOT."/rutas.php");

$db = &ADONewConnection(SERVIDOR);
$db->PConnect(IP,USER,PASSWORD,NUCLEO);

//verflujo();

?>
<SCRIPT>

function putcp(val, frm, col, edo, cty, pob)
{
     
     
     var myform  = window.opener.document.solicitud;
     var myfield = myform.elements['<?=$campo; ?>'];
     
     myfield.value = val;
     
     
     var iframe = window.opener.frames['loadvals'];
     var url = 'lvals.php?cp='+val+'&frm='+frm+'&col='+col+'&edo='+edo+'&cty='+cty+'&pob='+pob;

    
     iframe.location.replace(url);

     window.close();

}

</SCRIPT>

<?

echo "<FORM Method='POST' ACTION='".$PHP_SELF."' NAME='buscacp' >\n";

echo "<INPUT TYPE='HIDDEN' NAME='campo' VALUE='$campo'>\n";
echo "<INPUT TYPE='HIDDEN' NAME='frm' VALUE='$frm'>\n";
echo "<INPUT TYPE='HIDDEN' NAME='col' VALUE='$col'>\n";
echo "<INPUT TYPE='HIDDEN' NAME='edo' VALUE='$edo'>\n";
echo "<INPUT TYPE='HIDDEN' NAME='cty' VALUE='$cty'>\n";
echo "<INPUT TYPE='HIDDEN' NAME='pob' VALUE='$pob'>\n";












echo "<DIR>\n";
echo "Estádo de la república : ";

$sql = "SELECT ID_Estado, Nombre
        FROM   estados
        ORDER BY Nombre ";

 $rs=$db->Execute($sql);

echo "<SELECT NAME='id_estado' OnChange='document.buscacp.submit();' ID='small'>\n";
        
echo "          <OPTION value=''> Seleccione un estado </OPTION>\n";

while(! $rs->EOF )
{
        
        $sel = ($rs->fields[0] == $id_estado)?("SELECTED"):("");
        echo "<OPTION value='".$rs->fields[0]."' ".$sel.">".$rs->fields[1]." </OPTION>\n";
        $rs->MoveNext();
}
echo "</SELECT>\n";


if( empty($id_estado) )
    die("</DIR></FORM></BODY></HTML>");
    
echo "<BR><BR>";

$sql = "SELECT ID_Ciudad, Nombre
        FROM ciudades
        WHERE  ID_Ciudad != 0 and ID_Estado = '".$id_estado."'
        ORDER BY Nombre ";    

$rs=$db->Execute($sql);
echo "Ciudad, solo si es el caso : ";
echo "<SELECT NAME='id_ciudad' OnChange='document.buscacp.submit();'  ID='small'>\n";
        
echo "          <OPTION value=''> </OPTION>\n";


$sel = ((isset($id_ciudad)) and ( empty($id_ciudad)))?("SELECTED"):("");
echo "          <OPTION value='0' ".$sel."> No vivo en ninguna ciudad. </OPTION>\n";
while(! $rs->EOF )
{
        
        $sel = ($rs->fields[0] == $id_ciudad)?("SELECTED"):("");
        echo "<OPTION value='".$rs->fields[0]."' ".$sel.">".$rs->fields[1]." </OPTION>\n";
        $rs->MoveNext();
}
echo "</SELECT>\n";

echo "<BR><BR>";




$sql = "SELECT ID_Municipio, nombre
        FROM municipios
        WHERE ID_Estado = '".$id_estado."'
        ORDER BY Nombre "; 

$rs=$db->Execute($sql);


echo "Delegación / Municipio / Población : ";
echo "<SELECT NAME='id_municipio' OnChange='document.buscacp.submit();'  ID='small'>\n";
        
echo "          <OPTION value=''> </OPTION>\n";

while(! $rs->EOF )
{
        
        $sel = ($rs->fields[0] == $id_municipio)?("SELECTED"):("");
        echo "<OPTION value='".$rs->fields[0]."' ".$sel.">".$rs->fields[1]." </OPTION>\n";
        $rs->MoveNext();
}
echo "</SELECT>\n";



if(empty($id_municipio))
    die("</DIR></FORM></BODY></HTML>");
    
echo "<BR><BR>";

$id_ciudad = (empty($id_ciudad))?("0"):($id_ciudad);

$sql = "SELECT ID_Colonia, Colonia
        FROM codigos_postales
        WHERE   ID_Estado='".$id_estado."' and                 
                ID_Municipio='".$id_municipio."'
         ORDER BY Colonia ";
        
$rs=$db->Execute($sql);        
echo " Colonia : ";
echo "<SELECT NAME='id_colonia' OnChange='document.buscacp.submit();'  ID='small'>\n";
        
echo "          <OPTION value=''> </OPTION>\n";

while(! $rs->EOF )
{
        
        $sel = ($rs->fields[0] == $id_colonia)?("SELECTED"):("");
        echo "<OPTION value='".$rs->fields[0]."' ".$sel.">".$rs->fields[1]." </OPTION>\n";
        $rs->MoveNext();
}
echo "</SELECT>\n";


       
if(empty($id_colonia))
    die("</DIR></FORM></BODY></HTML>");
        




echo "<BR><BR>";
        
$sql = "SELECT CP
        FROM codigos_postales
        WHERE ID_Estado    = '".$id_estado."' and 
              ID_Municipio = '".$id_municipio."'  and
              ID_Colonia   = '".$id_colonia."' 
         ORDER BY Colonia ";



$rs=$db->Execute($sql);      


 

        echo "<CENTER>\n";
        while(! $rs->EOF )
        {


                echo "<BUTTON onClick='putcp(\"".$rs->fields[0]."\" ,\"".$frm."\",\"".$col."\",\"".$edo."\",\"".$cty."\",\"".$pob."\");' >".$rs->fields[0]." </BUTTON><BR>\n";
                
                 
                
                $rs->MoveNext();
        }
        echo "</CENTER>\n";




echo "<BR><BR>";
























echo "</DIR>\n";
echo "</FORM>\n";

?>
</BODY>
</HTML>