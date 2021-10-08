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
if( $puntosmin == "si" ){

		$noheader=1;
		include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerMatrizRiesgo.php");

		$db = &ADONewConnection(SERVIDOR);
		$db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
		$Objeto = new ControllerMatrizRiesgo($db);	
		echo  $Objeto->puntosminimos();

die();

	}

if($validacioninfo_determinante == "si" ){

		$noheader=1;
		include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerMatrizRiesgo.php");

		$db = &ADONewConnection(SERVIDOR);
		$db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
		$Objeto = new ControllerMatrizRiesgo($db);	
		echo  $Objeto->elementoseva_determinantes();

}

if($validacioninfo == "si" ){

		$noheader=1;
		include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerMatrizRiesgo.php");

		$db = &ADONewConnection(SERVIDOR);
		$db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
		$Objeto = new ControllerMatrizRiesgo($db);	
		echo  $Objeto->elementoseva();

}


if( $dispach == 1 ){


$noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerMatrizRiesgo.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
	    $Objeto = new ControllerMatrizRiesgo($db);	
		echo $Objeto->fundispach($ID_USR);



}



 		

?>