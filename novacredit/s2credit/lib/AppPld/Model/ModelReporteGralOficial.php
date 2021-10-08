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

if( $__cmd == "setConsultaSolicitudes" ){

    $noheader=1;
    include($DOCUMENT_ROOT."/rutas.php");

    $db = &ADONewConnection(SERVIDOR);
    $db->PConnect(IP,USER,PASSWORD,NUCLEO);
    require_once("../Controller/ControllerReporteGralOficial.php");
    //---------------------->
    // VALIDAMOS QUE SEA EL OFICIAL DE CUMPLIMIENTO

    $Objeto = new ControllerReporteGralOficial($db);
    $oficial =  $Objeto->validaOficial($ID_USR);
    if( $oficial == false ){

        $html .= "
				 		 <tr class='error'>
					    	<td colspan='10'>¡USTED NO TIENE PERMISO PARA VER LAS SOLICITUDES!</td>
					    </tr>
				 ";

        echo $html;
        die();

    }else{
        echo $Objeto->getSolicitudesHistorico($SherchSolicitud,$SherchNombre,$FechaInicial,$FechaFinal);

    } // fin if
} // fin if


if( $__cmd == "setConsultaComentario" ){

    $noheader=1;
    include($DOCUMENT_ROOT."/rutas.php");

    $db = &ADONewConnection(SERVIDOR);
    $db->PConnect(IP,USER,PASSWORD,NUCLEO);
    require_once("../Controller/ControllerReporteGralOficial.php");
    //---------------------->
    // VALIDAMOS QUE SEA EL OFICIAL DE CUMPLIMIENTO

    $Objeto = new ControllerReporteGralOficial($db);
    echo $Objeto->getComentario($id_OficialCumplimiento);

} // fin if


?>