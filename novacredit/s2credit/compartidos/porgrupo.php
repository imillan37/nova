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


         $sql = "SELECT ID_grupo_soli, Nombre 
                 FROM    grupo_solidario 
                 WHERE grupo_solidario.Nombre LIKE '".$search ."'
                 
                 GROUP BY ID_grupo_soli
                 ORDER BY Nombre  LIMIT 0,100";     







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