<?php

/**
 *
 * @author Ignacio Ocampo
 * @category Model
 * @created Wed Nov 26, 2014
 * @version 1.0
 */	

/**
 *
 *  @ Cargamos Alta PPE Model  
 */
if ( $__cmd == "setConsultaConsolidado" ) {

    $noheader=1;
    include($DOCUMENT_ROOT."/rutas.php");

    $db = &ADONewConnection(SERVIDOR);
    $db->PConnect(IP,USER,PASSWORD,NUCLEO);
    require_once("../Controller/ControllerReporteConsolidado.php");
    //---------------------->
    // VALIDAMOS QUE SEA EL OFICIAL DE CUMPLIMIENTO

    $Objeto = new ControllerReporteConsolidado($db);
    
    echo $Objeto->getDatosConsolidado($NumCliente, $NombreCliente, $FechaInicial, $FechaFinal);
} // fin if

if ( $__cmd == "setConsultaCliente" ) {

    $noheader=1;
    include($DOCUMENT_ROOT."/rutas.php");

    $db = &ADONewConnection(SERVIDOR);
    $db->PConnect(IP,USER,PASSWORD,NUCLEO);
    require_once("../Controller/ControllerReporteConsolidado.php");
    //---------------------->
    // VALIDAMOS QUE SEA EL OFICIAL DE CUMPLIMIENTO

    $Objeto = new ControllerReporteConsolidado($db);
    
    echo $Objeto->getCliente($NombreCliente);
}