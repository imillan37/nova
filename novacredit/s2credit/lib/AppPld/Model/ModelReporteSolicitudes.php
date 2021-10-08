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

if( $__cmd == "getSucursales" ){

    $noheader=1;
    include($DOCUMENT_ROOT."/rutas.php");

    $db = &ADONewConnection(SERVIDOR);
    $db->PConnect(IP,USER,PASSWORD,NUCLEO);
    require_once("../Controller/ControllerReporteSolicitudes.php");
    //---------------------->
    // VALIDAMOS QUE SEA EL OFICIAL DE CUMPLIMIENTO
    $Objeto = new ControllerReporteSolicitudes($db);
    echo  $Objeto->getSucursales($ID_SUC);
    //echo  $Objeto->getSucursales(70);

} // fin if
if( $__cmd == "getSolicitudes" ){

    $noheader=1;
    include($DOCUMENT_ROOT."/rutas.php");

    $db = &ADONewConnection(SERVIDOR);
    $db->PConnect(IP,USER,PASSWORD,NUCLEO);
    require_once("../Controller/ControllerReporteSolicitudes.php");
    //---------------------->
    // VALIDAMOS QUE SEA EL OFICIAL DE CUMPLIMIENTO
    $Objeto = new ControllerReporteSolicitudes($db);
    echo  $Objeto->getSsolicitudes($SherchCLiente,$SherchNombre,$Periodo,$Sucursal);
    //echo  $Objeto->getSucursales(70);

} // fin if


?>