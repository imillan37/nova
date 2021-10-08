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

$noheader=1;
include($DOCUMENT_ROOT."/rutas.php");

$db = &ADONewConnection(SERVIDOR);
$db->PConnect(IP,USER,PASSWORD,NUCLEO);
require_once("../Controller/ControllerReescaneo.php");
$Objeto = new ControllerReescaneo($db);
$Objeto->setIdUSuario($ID_USR);


if( $__cmd == "getFechaActualizacion" ){

    echo $Objeto->getFechaActualizacion();
    //
    if($ID_USR == 9)
    {
       // echo print_r($Objeto);
    }
} // fin if


if( $__cmd == "setReescaneo" ){

    $tabla = $Objeto->setReescaneo();

    echo  $tabla;





} // fin if


if( $__cmd == "getTablaLog" ){

echo $Objeto->getTablaLog();
} // fin if


if( $__cmd == "getTabladtl" ){

    echo $Objeto->getRegistros($Id_reescaneo);
}


?>