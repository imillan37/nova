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
if( $dolares == "si" ){

		$noheader=1;
		include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerLogActualizaciones.php");

		$db = &ADONewConnection(SERVIDOR);
		$db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
		$Objeto = new ControllerLogActualizaciones($db);	
		$datos =  $Objeto->consulta_dolares();
		
		require_once( $class_path."json.php" ); 
		$json       = new Services_JSON; 
		$datos = $json->encode($datos); 
							
		echo $datos;
		die();

	}

if($unidades == "si" ){

		$noheader=1;
		include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerLogActualizaciones.php");

		$db = &ADONewConnection(SERVIDOR);
		$db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
		$Objeto = new ControllerLogActualizaciones($db);	
		$datos = $Objeto->consulta_unidades();
		
		require_once( $class_path."json.php" ); 
		$json       = new Services_JSON; 
		$datos = $json->encode($datos); 
							
		echo $datos;
		die();

}


if($terroristas == "si" ){

		$noheader=1;
		include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerLogActualizaciones.php");

		$db = &ADONewConnection(SERVIDOR);
		$db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
		$Objeto = new ControllerLogActualizaciones($db);	
		$datos = $Objeto->verterroristas();
		
		require_once( $class_path."json.php" ); 
		$json       = new Services_JSON; 
		$datos = $json->encode($datos); 
							
		echo $datos;
		die();

}


if($listas_propias == "si" ){

    $noheader=1;
    include($DOCUMENT_ROOT."/rutas.php");
    require_once("../Controller/ControllerLogActualizaciones.php");

    $db = &ADONewConnection(SERVIDOR);
    $db->PConnect(IP,USER,PASSWORD,NUCLEO);

    $Objeto = new ControllerLogActualizaciones($db);
    $datos = $Objeto->verListasPropias();

    require_once( $class_path."json.php" );
    $json       = new Services_JSON;
    $datos = $json->encode($datos);

    echo $datos;
    die();

}

if($listas_condusef == "si" ){

    $noheader=1;
    include($DOCUMENT_ROOT."/rutas.php");
    require_once("../Controller/ControllerLogActualizaciones.php");

    $db = &ADONewConnection(SERVIDOR);
    $db->PConnect(IP,USER,PASSWORD,NUCLEO);

    $Objeto = new ControllerLogActualizaciones($db);
    $datos = $Objeto->verListasCondusef();

    require_once( $class_path."json.php" );
    $json       = new Services_JSON;
    $datos = $json->encode($datos);

    echo $datos;
    die();

}

if($PPE == "si" ){

		$noheader=1;
		include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerLogActualizaciones.php");

		$db = &ADONewConnection(SERVIDOR);
		$db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
		$Objeto = new ControllerLogActualizaciones($db);	
		$datos = $Objeto->verPPE();

		require_once( $class_path."json.php" ); 
		$json       = new Services_JSON; 
		$datos = $json->encode($datos); 
							
		echo $datos;
		die();

}

if($PPE == "si" ){

		$noheader=1;
		include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerLogActualizaciones.php");

		$db = &ADONewConnection(SERVIDOR);
		$db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
		$Objeto = new ControllerLogActualizaciones($db);	
		$datos = $Objeto->verPPE();

		require_once( $class_path."json.php" ); 
		$json       = new Services_JSON; 
		$datos = $json->encode($datos); 
							
		echo $datos;
		die();

}


if($SAT == "si" ){

		$noheader=1;
		include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerLogActualizaciones.php");

		$db = &ADONewConnection(SERVIDOR);
		$db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
		$Objeto = new ControllerLogActualizaciones($db);	
		$datos = $Objeto->verSAT();

		require_once( $class_path."json.php" ); 
		$json       = new Services_JSON; 
		$datos = $json->encode($datos); 
							
		echo $datos;
		die();
		

}




if($CP == "si" ){

		$noheader=1;
		include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerLogActualizaciones.php");

		$db = &ADONewConnection(SERVIDOR);
		$db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
		$Objeto = new ControllerLogActualizaciones($db);	
		$datos = $Objeto->verCP();

		require_once( $class_path."json.php" ); 
		$json       = new Services_JSON; 
		$datos = $json->encode($datos); 
							
		echo $datos;
		die();

}

if($Estado == "si" ){

		$noheader=1;
		include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerLogActualizaciones.php");

		$db = &ADONewConnection(SERVIDOR);
		$db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
		$Objeto = new ControllerLogActualizaciones($db);	
		$datos = $Objeto->verEstado();

		require_once( $class_path."json.php" ); 
		$json       = new Services_JSON; 
		$datos = $json->encode($datos); 
							
		echo $datos;
		die();

}


if($Ciudades == "si" ){

		$noheader=1;
		include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerLogActualizaciones.php");

		$db = &ADONewConnection(SERVIDOR);
		$db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
		$Objeto = new ControllerLogActualizaciones($db);	
		$datos = $Objeto->verCiudades();

		require_once( $class_path."json.php" ); 
		$json       = new Services_JSON; 
		$datos = $json->encode($datos); 
							
		echo $datos;
		die();

}


if($Giros == "si" ){

		$noheader=1;
		include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerLogActualizaciones.php");

		$db = &ADONewConnection(SERVIDOR);
		$db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
		$Objeto = new ControllerLogActualizaciones($db);	
		$datos = $Objeto->verGiros();

		require_once( $class_path."json.php" ); 
		$json       = new Services_JSON; 
		$datos = $json->encode($datos); 
							
		echo $datos;
		die();

}

if($_POST["paiss"] == "si" ){

		$noheader=1;
		include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerLogActualizaciones.php");

		$db = &ADONewConnection(SERVIDOR);
		$db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
		$Objeto = new ControllerLogActualizaciones($db);	
		$datos = $Objeto->verPais();

		require_once( $class_path."json.php" ); 
		$json       = new Services_JSON; 
		$datos = $json->encode($datos); 
							
		echo $datos;
		die();

}

if($_POST["puestosPPE"] == "si" ){

		$noheader=1;
		include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerLogActualizaciones.php");

		$db = &ADONewConnection(SERVIDOR);
		$db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
		$Objeto = new ControllerLogActualizaciones($db);	
		$datos = $Objeto->verpuestosPPE();

		require_once( $class_path."json.php" ); 
		$json       = new Services_JSON; 
		$datos = $json->encode($datos); 
							
		echo $datos;
		die();

}

if( $__cmd == "setVistaDolares" ){
		
	    $noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerLogActualizaciones.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
                
        $Objeto = new ControllerLogActualizaciones($db);
        echo   $Objeto->VistaDolares($Tipo,$Pagina,$Evento,$Fecha_inicial,$Fecha_final);
        die();
    		 
}


if( $__cmd == "setVistaUnidades" ){
		
	    $noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerLogActualizaciones.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
                
        $Objeto = new ControllerLogActualizaciones($db);
        echo   $Objeto->VistaUnidades($Tipo,$Pagina,$Evento,$Fecha_inicial,$Fecha_final);
        die();
    		 
}


if( $__cmd == "setVistaTerroristas" ){
		
	    $noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerLogActualizaciones.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
                
        $Objeto = new ControllerLogActualizaciones($db);
        echo   $Objeto->VistaTerroristas($Tipo,$Pagina,$Evento,$Fecha_inicial,$Fecha_final);
        die();
    		 
}

if( $__cmd == "setVistaListaCondusef" ){

    $noheader=1;
    include($DOCUMENT_ROOT."/rutas.php");
    require_once("../Controller/ControllerLogActualizaciones.php");

    $db = &ADONewConnection(SERVIDOR);
    $db->PConnect(IP,USER,PASSWORD,NUCLEO);

    $Objeto = new ControllerLogActualizaciones($db);
    echo   $Objeto->VistaListaCondusef($Tipo,$Pagina,$Evento,$Fecha_inicial,$Fecha_final);
    die();

}


if( $__cmd == "setVistaListasPropias" ){

    $noheader=1;
    include($DOCUMENT_ROOT."/rutas.php");
    require_once("../Controller/ControllerLogActualizaciones.php");

    $db = &ADONewConnection(SERVIDOR);
    $db->PConnect(IP,USER,PASSWORD,NUCLEO);

    $Objeto = new ControllerLogActualizaciones($db);
    echo   $Objeto->VistaListaPropias($Tipo,$Pagina,$Evento,$Fecha_inicial,$Fecha_final);
    die();

}


if( $__cmd == "setVistaListasPPE" ){

    $noheader=1;
    include($DOCUMENT_ROOT."/rutas.php");
    require_once("../Controller/ControllerLogActualizaciones.php");

    $db = &ADONewConnection(SERVIDOR);
    $db->PConnect(IP,USER,PASSWORD,NUCLEO);

    $Objeto = new ControllerLogActualizaciones($db);
    echo   $Objeto->VistaListaPPE($Tipo,$Pagina,$Evento,$Fecha_inicial,$Fecha_final);
    die();

}


if( $__cmd == "setVistasCatalogos" ){

    $noheader=1;
    include($DOCUMENT_ROOT."/rutas.php");
    require_once("../Controller/ControllerLogActualizaciones.php");

    $db = &ADONewConnection(SERVIDOR);
    $db->PConnect(IP,USER,PASSWORD,NUCLEO);

    $Objeto = new ControllerLogActualizaciones($db);
    echo   $Objeto->VistaListaPPE($Pagina,$Evento,$Evento2);
    die();

}


?>