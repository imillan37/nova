<?

/**
 *
 * @author MarsVoltoso (CFA)
 * @category Model
 * @created Mon Sep 15, 2014
 * @version 1.0
 */	

/**
 *
 *  @ Cargamos Cargamos resultado
 */ 	
 
 if( $__cmd == "consulta" ){
		
	    $noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerListasNegras.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
                
        $Objeto = new ControllerListasNegras($db);
        echo $Objeto->VistaListas($Pagina,$Evento,utf8_decode($Filtro),$Opcion,$tipoCatalogo);
}     