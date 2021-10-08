<?
if( $__cmd == "setActualizaTerrorista" ){
		
	    $noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerServiciosWeb.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
                
        $Objeto = new ControllerServiciosWeb($db);
        $ONU 	=  $Objeto->ActualizaTerrorista($ID_USR);
        
		echo "Actualizado";
			 
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