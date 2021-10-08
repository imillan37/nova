<?php

/**
 *
 * @author MarsVoltoso (CFA)
 * @category Contoller
 * @created Mon Sep 15, 2014
 * @version 1.0
 */


/**
 *
 *  @ Cargamos Vista
 */

class ControllerReporteGralOficial {

    public $db;

    public function __construct($db){
        $this->db = $db;
    }


    public function validaOficial($id_usuario)
    {
        $Query = "SELECT
								 ID_User_Oficial_Cumplimiento
							FROM pld_parametros_configuracion
						   LIMIT 1";

        $RESPUESTA                    = $this->db->Execute($Query);
        $ID_User_Oficial_Cumplimiento = $RESPUESTA->fields["ID_User_Oficial_Cumplimiento"];
        if( $ID_User_Oficial_Cumplimiento != $id_usuario )
        {
            return false;
        }else
        {
            return true;
        }
    }

    public function getSolicitudesHistorico($SherchSolicitud,$SherchNombre,$FechaInicial,$FechaFinal)
    {
        if( !empty($SherchSolicitud) ){
            $QueryWhere = " AND solicitud.ID_Solicitud = '".$SherchSolicitud."' ";
        }elseif(!empty($SherchNombre))
        {
            $SherchNombre = str_replace(" ","%",$SherchNombre);
            $QueryWhere .= " AND CONCAT( solicitud.Nombre,' ',solicitud.NombreI,' ',solicitud.Ap_paterno,' ',solicitud.Ap_materno ) like '%".$SherchNombre."%' ";
        }// fin if

        if($FechaInicial != "" and $FechaFinal != "")
        {
            $QueryWhere1 .= "AND DATE(pld_oficial_cumplimiento_log.fecha_cambio) BETWEEN '".$FechaInicial."' and '".$FechaFinal."' ";
            $QueryWhere2 .= "AND solicitud.Fecha BETWEEN '".$FechaInicial."' and '".$FechaFinal."' ";
        }

        $Query = "(SELECT
                    pld_oficial_cumplimiento_log.id_OficialCumplimiento AS id_OficialCumplimiento,
                    pld_oficial_cumplimiento_log.ID_Solicitud AS ID_Solicitud,
                    CONCAT( solicitud.Nombre,' ',solicitud.NombreI,' ',solicitud.Ap_paterno,' ',solicitud.Ap_materno ) AS NombreSolicitante,
                    cat_tipo_credito.Descripcion AS Tipo_credito,
                    solicitud.Monto AS Monto,
                    pld_oficial_cumplimiento_log.SolicitudRevisionPld AS Estatus_PLD,
                    pld_oficial_cumplimiento_log.Comentarios AS Comentarios,
                    DATE( pld_oficial_cumplimiento_log.fecha_cambio) AS Fecha,
                    '' AS Envio_CNBV

                    FROM
                    pld_oficial_cumplimiento_log
                    LEFT JOIN solicitud on solicitud.ID_Solicitud = pld_oficial_cumplimiento_log.ID_Solicitud
                    INNER JOIN cat_tipo_credito ON cat_tipo_credito.ID_Tipocredito = solicitud.ID_Tipocredito
                    WHERE 0=0
                    $QueryWhere1
                    $QueryWhere)
                    UNION
              (SELECT
                    '' AS id_OficialCumplimiento,
                    solicitud.ID_Solicitud AS ID_Solicitud,
                    CONCAT( solicitud.Nombre,' ',solicitud.NombreI,' ',solicitud.Ap_paterno,' ',solicitud.Ap_materno ) AS NombreSolicitante,
                    cat_tipo_credito.Descripcion as Tipo_credito,
                    solicitud.Monto AS Monto,
                    solicitud.SolicitudRevisionPld AS Estatus_PLD,
                    '' AS Comentarios,
                    solicitud.Fecha AS Fecha,
                    '' AS Envio_CNBV
                    FROM solicitud
                    INNER JOIN cat_tipo_credito ON cat_tipo_credito.ID_Tipocredito = solicitud.ID_Tipocredito
                    WHERE solicitud.SolicitudRevisionPld = 'SI'
                    AND solicitud.Status_solicitud != 'CANCELADA'
                    $QueryWhere2
				    $QueryWhere)
				    ORDER BY 2 DESC  ";

        $RESPUESTA   = $this->db->Execute($Query); //debug($Query);
        $html = "";

        while( !$RESPUESTA->EOF ) {
            $id_OficialCumplimiento = $RESPUESTA->fields["id_OficialCumplimiento"];
            $ID_Solicitud      = $RESPUESTA->fields["ID_Solicitud"];
            $NombreSolicitante = $RESPUESTA->fields["NombreSolicitante"];
            $Descripcion       = $RESPUESTA->fields["Tipo_credito"];
            $Monto             = $RESPUESTA->fields["Monto"];
            $Estatus_PLD       = $RESPUESTA->fields["Estatus_PLD"];
            $Comentarios       = $RESPUESTA->fields["Comentarios"];
            $Envio_CNBV        = $RESPUESTA->fields["Envio_CNBV"];
            $Fecha        = $RESPUESTA->fields["Fecha"];

            $Estatus_PLD_oficial = "";
            $class= "";
            if($Estatus_PLD == "SI")
            {
                $Estatus_PLD_oficial = "REVISION OFICIAL";
                $class= "";
            }elseif($Estatus_PLD == "NO")
            {
                $Estatus_PLD_oficial = "LIBERADA";
                $class= "positive";
            }elseif($Estatus_PLD == "CANCELADA")
            {
                $Estatus_PLD_oficial = "CANCELADA";
                $class= "negative";
            }

            $boton = "";
            if($id_OficialCumplimiento > 0)
            {
                $boton = " <a class='ui mini blue button' onclick='muestraComentario(".$id_OficialCumplimiento.");'>COMENTARIO</a> ";
            }


            $html .= "
                            <tr class='".$class."'>
                                <td align='center'>".$ID_Solicitud."</td>
                                <td align='left'>".strtoupper($NombreSolicitante)."</td>
                                <td align='left'>".strtoupper($Descripcion)."</td>
                                <td align='left'>".strtoupper($Estatus_PLD_oficial)."</td>
                                <td align='left'>".$Fecha."</td>
                                <td align='left'>".$boton." </td>
                            </tr>
                     ";

            $RESPUESTA->MoveNext();
        } // fin while( !$RESPUESTA->EOF ) {

        return $html;
    }

    public function getComentario($id_OficialCumplimiento)
    {
        $Query = "SELECT
                              pld_oficial_cumplimiento_log.Comentarios
                              FROM pld_oficial_cumplimiento_log
                              WHERE pld_oficial_cumplimiento_log.id_OficialCumplimiento = '".$id_OficialCumplimiento."'
                               LIMIT 1";

        $RESPUESTA                    = $this->db->Execute($Query);
        $comentario = "<p>".utf8_decode($RESPUESTA->fields["Comentarios"])."</p>";
        return $comentario;
    }


}


?>