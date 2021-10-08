<?php

/**
 *
 * @author Ignacio Ocampo
 * @category Model
 * @created Tue Dec 30, 2014
 * @version 1.0
 */

if ( $__cmd == "setConsultaAlertas" ) {

    $noheader=1;
    include($DOCUMENT_ROOT."/rutas.php");

    $db = &ADONewConnection(SERVIDOR);
    $db->PConnect(IP,USER,PASSWORD,NUCLEO);
    require_once("../Controller/ControllerReporteAlertas.php");
    //---------------------->
    // VALIDAMOS QUE SEA EL OFICIAL DE CUMPLIMIENTO

    $Objeto = new ControllerReporteAlertas($db);
    echo $Objeto->getDatosAlertas($TipoAlerta, $Status, $FechaInicial, $FechaFinal, $IDCredito, $NumCliente, $FechaInicialMov, $FechaFinalMov);
} // fin if