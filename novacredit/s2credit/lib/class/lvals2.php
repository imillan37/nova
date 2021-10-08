<?
/*   
         ________________________________________________________
        |  Titulo: LVals                                        |
        |                                                       |
##      |  Autor : Enrique Godoy Calderón                       |
##      |                                                       |
##      |  Fecha : Wednesday, July 26, 2006                     |
##      |                                                       |
##      |  Descripción : Cambiar valores de estado, municipio   |
##      |               ciudad y colonia de la forma de captura.|
##      |  Dependencias : Forma de captura.                     |
##      |                                                       |
##      |                                                       |
##       ¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯
######################################################
######################################################

*/

$heading_title = " :  ";
$noheader=1;
include($DOCUMENT_ROOT."/rutas.php");

$db = &ADONewConnection(SERVIDOR);
$db->PConnect(IP,USER,PASSWORD,NUCLEO);

//verflujo();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<HTML>
<HEAD>
<TITLE></TITLE>
</HEAD>
<BODY>
<?
//colonia_soli, estado_soli, ciudad_soli, poblacion_soli


//verflujo();
if(!empty($cp))
{

echo "<SCRIPT>\n";

//echo "\t var frm = parent.document.forms[".$frm."]; \n";
echo "\t var frm = parent.document.".$frm."; \n";

    //»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»

        echo "\tvar  fre = frm.elements['".$col."']; 
        
        
        
        for (var i=(fre.options.length-1); i>=0; i--)
                        fre.options[i] = null;

         \n";
        $i=0;


        $sql = "SELECT id_colonia, colonia 
                FROM ".NUCLEO.".codigos_postales 
                WHERE cp='".$cp."' ";   
                        
                        
        $rs = $db->Execute($sql);
        if($rs->_numOfRows)
        while(!$rs->EOF)
                {
                        $idm  = $rs->fields[1];
                        $nom  = $rs->fields[1];                 
                        echo "\t fre.options[".$i."] = new Option( '".$nom."', '".$idm."', false, false); \n";
                        $rs->MoveNext();
                        $i++;
                }
        $num = $rs->_numOfRows;

    //»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»


        
        echo "\tvar  fre = frm.elements['".$edo."'];
        
        
        for (var i=(fre.options.length-1); i>=0; i--)
                        fre.options[i] = null;
         \n";
        $i=0;
    if($num)
    {
    
        $sql = "SELECT id_estado, Nombre 
                        FROM ".NUCLEO.".estados 
                        WHERE '".$cp."'>= Rango1 and '".$cp."'<= Rango2 ";              
    
    
    
                $rs = $db->Execute($sql);
                if($rs->_numOfRows)
                while(!$rs->EOF)
                        {
                                $idm  = $rs->fields[1];
                                $nom  = $rs->fields[1]; 

                                echo "\t fre.options[".$i."] = new Option( '".$nom."', '".$idm."', false, false); \n";
                                $i++;
                                $rs->MoveNext();
                                $i++;

                        }
        }       
                
echo "\n\n";            
                
                
    //»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»

        echo "\tvar  fre = frm.elements['".$cty."'];
        
        
        for (var i=(fre.options.length-1); i>=0; i--)
                        fre.options[i] = null;
         \n";
        $i=0;


    if($num)
    {
        

        $sql = "SELECT id_ciudad, Nombre 
                        FROM ".NUCLEO.".ciudades 
                        WHERE ('".$cp."'>=Rango1 and '".$cp."'<=Rango2) or
                              ('".$cp."'>=Rango3 and '".$cp."'<=Rango4) ";      
        $rs = $db->Execute($sql);

                if($rs->_numOfRows)
                while(!$rs->EOF)
                        {
                                $idm  = $rs->fields[1];
                                $nom  = $rs->fields[1];                 
                                echo "\t fre.options[".$i."] = new Option( '".$nom."', '".$idm."', false, false); \n";

                                $rs->MoveNext();
                                $i++;
                        }
        }       
echo "\n\n";            
                                
                
                

    //»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»

        echo "\tvar  fre = frm.elements['".$pob."'];
        
        
        for (var i=(fre.options.length-1); i>=0; i--)
                        fre.options[i] = null;
         \n";
        $i=0;

    if($num)
    {

                $sql = "SELECT id_municipio, Nombre 
                                FROM ".NUCLEO.".municipios 
                                WHERE ('".$cp."'>=Rango1 and '".$cp."'<=Rango2) or 
                                          ('".$cp."'>=Rango3 and '".$cp."'<=Rango4)     or 
                                          ('".$cp."'>=Rango5 and '".$cp."'<=Rango6)     or 
                                          ('".$cp."'>=Rango7 and '".$cp."'<=Rango8)     ";                                        
                $rs = $db->Execute($sql);
                if($rs->_numOfRows)
                while(!$rs->EOF)
                        {
                                $idm  = $rs->fields[1];
                                $nom  = $rs->fields[1];                 
                                echo "\t fre.options[".$i."] = new Option( '".$nom."', '".$idm."', false, false); \n";
                                $rs->MoveNext();
                                $i++;
                        }
        }
echo "\n\n";            
                                
if(! $num) echo  "    alert('El Código postal seleccionado no existe'); \n ";


/*
*/
echo "\n\n";            
                                

echo "\n</SCRIPT>\n";


}       





?>
</BODY>
</HTML>
