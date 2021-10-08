<?
if( $__cmd == "setConfiguracion" ){


        $noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
        require_once("../Controller/ControllerConfiguradorPLD.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);
         
        $Objeto = new ControllerConfiguradorPLD($db);
        echo $Objeto->setConfiguracion($Campos,$ID_USR);

}

if( $__cmd == "getConfiguracion" ){



        $noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerConfiguradorPLD.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);

        $Objeto = new ControllerConfiguradorPLD($db);
        echo $Objeto->getConfiguracion();

}

if( $__cmd == "getHistorico" )
{
    $noheader=1;
    include($DOCUMENT_ROOT."/rutas.php");
    require_once("../Controller/ControllerConfiguradorPLD.php");

    $db = &ADONewConnection(SERVIDOR);
    $db->PConnect(IP,USER,PASSWORD,NUCLEO);

    $Objeto = new ControllerConfiguradorPLD($db);
    echo $Objeto->getHistorico($Filtro,$Evento,$Pagina);

}

if( $__cmd == "getDetalle" )
{
    $noheader=1;
    include($DOCUMENT_ROOT."/rutas.php");
    require_once("../Controller/ControllerConfiguradorPLD.php");

    $db = &ADONewConnection(SERVIDOR);
    $db->PConnect(IP,USER,PASSWORD,NUCLEO);

    $Objeto = new ControllerConfiguradorPLD($db);
    echo $Objeto->getDetalle($ID_pld_originacion_log);
}

?>