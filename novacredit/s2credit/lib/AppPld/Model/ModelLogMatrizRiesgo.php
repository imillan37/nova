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
 *  @ Cargamos Alta PPE Model  
 */ 
if( $datos == "si" ){

		$noheader=1;
		include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerLogMatrizRiesgo.php");

		$db = &ADONewConnection(SERVIDOR);
		$db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
		$Objeto = new ControllerLogMatrizRiesgo($db);	
		$datos =  $Objeto->consulta_lista($Pagina,$Evento);
							
		echo $datos;


	}

if( $__cmd == "detalles" ){
		
	    $noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerLogMatrizRiesgo.php");

		$db = &ADONewConnection(SERVIDOR);
		$db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
		$Objeto = new ControllerLogMatrizRiesgo($db);
        echo $Objeto->verDetalles($id);
}     






?>