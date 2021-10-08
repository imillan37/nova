<?php
$noheader=1;
include($DOCUMENT_ROOT."/rutas.php");


$db = &ADONewConnection(SERVIDOR);
$db->PConnect(IP,USER,PASSWORD,NUCLEO);

header("Expires: Wed, 21 Jun 2007 05:00:00 GMT" );
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
header("Cache-Control: no-cache, must-revalidate" );
header("Pragma: no-cache" );
header('Content-Type: text/xml; charset=ISO-8859-1');

if (isset($_GET['search']) && $_GET['search'] != '')
{
        $search = addslashes($_GET['search']);

        if(strlen($search))
          $search .= "%";


                $sql = "SELECT clientes.Num_cliente,

				CASE clientes.Regimen 
				WHEN 'PM'   THEN clientes_datos_pmoral.Razon_social  COLLATE 'latin1_swedish_ci'
				
				WHEN 'PFAE' THEN CONCAT(clientes_datos_pmoral.Ap_paterno_pfae, ' ',
							clientes_datos_pmoral.Ap_materno_pfae,' ',
							clientes_datos_pmoral.Nombre_pfae, ' ',
							clientes_datos_pmoral.NombreI_pfae) COLLATE 'latin1_swedish_ci'

				WHEN 'PF'  THEN CONCAT( clientes_datos.Ap_paterno,' ',
							clientes_datos.Ap_materno,' ',
							clientes_datos.Nombre,' ',
							clientes_datos.NombreI )   COLLATE 'latin1_swedish_ci'
				END AS Nombre,
                              
                                   
                                 
                                   
                                   
                                   CONCAT( 
                                   clientes_datos.Nombre,' ',
                                   clientes_datos.Ap_paterno,' ',
                                   clientes_datos.Ap_materno) AS Nombre2,


                                   CONCAT( 
                                   clientes_datos.NombreI,' ',
                                   clientes_datos.Ap_paterno,' ',
                                   clientes_datos.Ap_materno) AS Nombre3,
                                   
                                   
                                   CONCAT( 
                                   clientes_datos.NombreI,' ',
                                   clientes_datos.Nombre,' ', 
                                   clientes_datos.Ap_paterno,' ',
                                   clientes_datos.Ap_materno) AS Nombre4,

                                   CONCAT( 
                                   clientes_datos.NombreI,' ',
                                   clientes_datos.Nombre,' ', 
                                   clientes_datos.Ap_paterno,' ',
                                   clientes_datos.Ap_materno) AS Nombre5                                   

                        FROM  clientes
                       

		LEFT  JOIN clientes_datos	 ON clientes.num_cliente 	      = clientes_datos.num_cliente
		LEFT  JOIN clientes_datos_pmoral ON clientes_datos_pmoral.num_cliente = clientes.num_cliente
                        
                        HAVING  Nombre  LIKE '%".$search ."'  or
                                Nombre2 LIKE '".$search ."'  or
                                Nombre3 LIKE '".$search ."'  or 
                                Nombre4 LIKE '".$search ."'  or
                                Nombre5 LIKE '".$search ."'  
                        
                        ORDER BY Nombre LIMIT 0,100";
/*
if($_SESSION['ID_USR']==13)
{
	debug($sql);
}
*/

                $rs = $db->Execute($sql);


                if($rs->_numOfRows)
                While(! $rs->EOF)
                {

                echo $rs->fields[0].") ".$rs->fields[1]."\n";
                $rs->MoveNext() ;
                }


}
?>
</BODY>
</HTML>