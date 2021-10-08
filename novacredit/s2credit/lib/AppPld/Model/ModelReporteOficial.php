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
        require_once("../Controller/ControllerReporteOficial.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
                
                
        $Objeto = new ControllerReporteOficial($db);
        echo $Objeto->VistaListas($Pagina,$Evento,$Filtro,$Filtro2);
}     

 if( $__cmd == "detalles" ){
		
	    $noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
        require_once("../Controller/ControllerReporteOficial.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
                
                
        $Objeto = new ControllerReporteOficial($db);
        echo $Objeto->verDetalles($id);
}     