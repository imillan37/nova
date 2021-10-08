<?
if( $__cmd == "setActualizaTerrorista" ){

        error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);
        ini_set('display_errors', '1');
        ini_set(" memory_limit "," 192M ");
        ini_set("default_socket_timeout", 200);
        set_time_limit(0);
		
		$noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerServiciosWeb.php");
		
        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
        $Objeto = new ControllerServiciosWeb($db);
        $OFAC 	 =  $Objeto->ActualizaTerrorista($ID_USR);
        //$ALQAIDA =  $Objeto->ActualizaTerrorista("ALQAIDA",$ID_USR);
		//var_dump($OFAC);
		/*
		echo "Actualizado";
		die();
		*/
		if($OFAC == "ACTUALIZADO")
		{
			echo "Actualizado";
		}else
		{
			echo "<h2>Ocurrio un problema <br> ¡Vuelva a intentarlo!</h2>";
		}
		
		

    		 
}


if( $__cmd == "GetListadoTerroristas" ){
		
	    $noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerServiciosWeb.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
                
        $Objeto = new ControllerServiciosWeb($db);
        echo $Objeto->VistaTerroristas($Pagina,$Evento,$Filtro);
		
    		 
}


if( $__cmd == "setActualizaUnidades" ){
		
	     $noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerServiciosWeb.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
         
		       
        $Objeto = new ControllerServiciosWeb($db);
        $RES =  $Objeto->ActualizaUnidades($Tipo,$ID_USR);
		
		if($RES == "Listo")
		{
			echo "Actualizado";
		}else
		{
			echo "<h2>Ocurrio un problema <br> ¡Vuelva a intentarlo!</h2>";
		}	  
}


if( $__cmd == "setVistaUnidades" ){
		
	    $noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerServiciosWeb.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
                
        $Objeto = new ControllerServiciosWeb($db);
        echo   $Objeto->VistaUnidades($Tipo,$Pagina,$Evento,$Fecha_inicial,$Fecha_final);
    		 
}

?>