<?php

/**
 *
 * @author Ignacio Ocampo
 * @category Contoller
 * @created Tue Dec 30, 2014
 * @version 1.0
 */


/**
 *
 *  @ Cargamos Vista
 */

class ControllerReporteAlertas {

    public $db;

    public function __construct($db){
        $this->db = $db;
    }

    public function getDatosAlertas($TipoAlerta, $Status, $FechaInicial, $FechaFinal, $IDCredito, $NumCliente, $FechaInicialMov, $FechaFinalMov)
    {
        $sql_comp = '';
        $sql_comp_clientes = '';
        $sql_tipo = '';
        $html = '';

        if(!empty($TipoAlerta)) {
            $sql_tipo .= "HAVING Tipo = '" . addslashes($TipoAlerta) . "'";
        }

        if(!empty($Status)) {
            $sql_comp .= " AND Dictaminado = '" . addslashes($Status) . "'";
        }

        if(!empty($IDCredito)) {
            $sql_comp .= " AND ID_Credito = '" . addslashes(trim($IDCredito)) . "'";
        }

        if(!empty($NumCliente)) {
            $sql_comp_clientes .= " AND clientes.Num_cliente = '" . addslashes(trim($NumCliente)) . "'";
        }

        // Fecha deteccion
        $sql_fechas = '';
        if(!empty($FechaInicial)) {
            list($y_i, $m_i, $d_i) = explode('-', $FechaInicial);
            if(checkdate($m_i, $d_i, $y_i)) {
                $sql_fechas .= " AND DATE(tabla.Registro) >= '".date("Y-m-d", strtotime($y_i."-".$m_i."-".$d_i))."'";
            }
        }
        if(!empty($FechaFinal)) {
            list($y_f, $m_f, $d_f) = explode('-', $FechaFinal);
            if(checkdate($m_f, $d_f, $y_f)) {
                $sql_fechas .= " AND DATE(tabla.Registro) <= '".date("Y-m-d", strtotime($y_f."-".$m_f."-".$d_f))."'";
            }
        }
        // Fecha Movimiento
        $sql_fechas_mov = '';
        if(!empty($FechaInicialMov)) {
            list($y_i, $m_i, $d_i) = explode('-', $FechaInicialMov);
            if(checkdate($m_i, $d_i, $y_i)) {
                $sql_fechas_mov .= " AND tabla.Fecha >= '".date("Y-m-d", strtotime($y_i."-".$m_i."-".$d_i))."'";
            }
        }
        if(!empty($FechaFinalMov)) {
            list($y_f, $m_f, $d_f) = explode('-', $FechaFinalMov);
            if(checkdate($m_f, $d_f, $y_f)) {
                $sql_fechas_mov .= " AND tabla.Fecha <= '".date("Y-m-d", strtotime($y_f."-".$m_f."-".$d_f))."'";
            }
        }

        $html .='<table class="ui celled table"><tbody>';
        $html .='   <tr style=" background-color:#407FB7;color:#FFF; text-align:center;font-weight:bold;">
                        <td>ID cliente</td>
                        <td>ID crédito</td>
                        <td>Nombre</td>
                        <td>Régimen</td>
                        <td>Fecha movimiento</td>
                        <td>Monto M.N.</td>
                        <td>Monto USD</td>
                        <td>Tipo</td>
                        <td>Dictaminado</td>
                        <td>Motivo</td>
                        <td>Fecha de detección</td>
                    </tr>';
        
        $sql = "(SELECT  pld_alertas_relevantes.ID_Alerta_Relevante,
                            clientes.Num_cliente AS ID_Cliente,
                            pld_alertas_relevantes.ID_Credito,
                            CONCAT(
                                pld_alertas_relevantes.Nombre, ' ',
                                pld_alertas_relevantes.AP_Paterno, ' ',
                                pld_alertas_relevantes.AP_Materno
                            ) AS Nombre,
                            pld_alertas_relevantes.Regimen_Fiscal,
                            pld_alertas_relevantes.Fecha,
                            pld_alertas_relevantes.Monto_MX,
                            pld_alertas_relevantes.Monto_USD,
                            'Relevante' AS Tipo,
                            pld_alertas_relevantes.Dictaminado,
                            pld_alertas_relevantes.Motivo,
                            pld_alertas_relevantes.Registro

                    FROM   pld_alertas_relevantes
                    LEFT JOIN clientes ON clientes.ID_Cliente = pld_alertas_relevantes.ID_Cliente
                    WHERE  1
                    ".$sql_comp . str_replace("tabla", "pld_alertas_relevantes", $sql_fechas). str_replace("tabla", "pld_alertas_relevantes", $sql_fechas_mov)." 
                    " . $sql_comp_clientes . "
                    " . $sql_tipo . "
                    ORDER BY pld_alertas_relevantes.Registro
                    )
                    UNION
                    (
                    SELECT  pld_alertas_inusuales.ID_Alerta_Inusual,
                            clientes.Num_cliente AS ID_Cliente,
                            pld_alertas_inusuales.ID_Credito,
                            CONCAT(
                                pld_alertas_inusuales.Nombre, ' ',
                                IFNULL(pld_alertas_inusuales.AP_Paterno, ''), ' ',
                                IFNULL(pld_alertas_inusuales.AP_Materno, '')
                            ) AS Nombre,
                            pld_alertas_inusuales.Regimen_Fiscal,
                            pld_alertas_inusuales.Fecha,
                            pld_alertas_inusuales.Monto_MN,
                            '' AS Monto_USD,
                            'Inusual' As Tipo,
                            pld_alertas_inusuales.Dictaminado,
                            pld_alertas_inusuales.Motivo,
                            pld_alertas_inusuales.Registro
                    FROM  pld_alertas_inusuales
                    LEFT JOIN clientes ON clientes.ID_Cliente = pld_alertas_inusuales.ID_Cliente
                    WHERE 1
                    ".$sql_comp . str_replace("tabla", "pld_alertas_inusuales", $sql_fechas).str_replace("tabla", "pld_alertas_inusuales", $sql_fechas_mov)."
                    " . $sql_comp_clientes . "
                    " . $sql_tipo . "
                    ORDER BY pld_alertas_inusuales.Registro
                    )
                    UNION 
                    (
                        SELECT pld_buzon.ID_Buzon,
                                '' AS ID_Cliente,
                                '' AS ID_Credito,
                               CONCAT(
                               pld_buzon.Nombre_Funcionario, ' ',
                               pld_buzon.AP_Paterno, ' ',
                               pld_buzon.AP_Materno
                               ) AS Nombre,
                                '' AS Regimen_Fiscal,
                                pld_buzon.Fecha_Ocurrio_Evento AS Fecha,
                                '' AS Monto_MX, 
                                '' AS Monto_USD,
                                'Preocupante' AS Tipo,
                               pld_buzon.Dictaminado,
                               pld_cat_operaciones_internas_preocupantes.Descripcion AS Motivo,
                               pld_buzon.Registro
                        FROM pld_buzon
                        LEFT JOIN pld_cat_operaciones_internas_preocupantes ON pld_buzon.ID_Oper_Interna = pld_cat_operaciones_internas_preocupantes.ID_Oper_Interna

                        WHERE 1
                        ".str_replace(array('ID_Credito', 'ID_Cliente'), array('Registro', 'Registro'), $sql_comp) . str_replace("tabla", "pld_buzon", $sql_fechas).str_replace("tabla.Fecha", "pld_buzon.Fecha_Ocurrio_Evento", $sql_fechas_mov)."
                        " . $sql_tipo . "
                        ORDER BY pld_buzon.Registro
                        )
                    ";

        //debug($sql);
        $rs = $this->db->Execute($sql);
        if($rs->_numOfRows == 0) {
            return 'No se encontraron resultados.';
            die();
        }

        while (!$rs->EOF) {
            $html .= '  <tr class="row">
                            <td align="right">' . $rs->fields['ID_Cliente'] . '&nbsp;</td>
                            <td align="right">' . $rs->fields['ID_Credito'] . '&nbsp;</td>
                            <td align="left" nowrap>' . $rs->fields['Nombre'] . '</td>
                            <td>' . $rs->fields['Regimen_Fiscal'] . '</td>
                            <td align="center">' . ffecha($rs->fields['Fecha']) . '</td>
                            <td align="right">' . (($rs->fields['Monto_MX'] != '' && $rs->fields['Monto_MX'] != 0)?number_format($rs->fields['Monto_MX'], 2):'') . '</td>
                            <td align="right">' . (($rs->fields['Monto_MX'] != '' && $rs->fields['Monto_USD'] != 0)?number_format($rs->fields['Monto_USD'], 2):'') . '</td>
                            <td align="center">' . $rs->fields['Tipo'] . '</td>
                            <td align="center">' . $rs->fields['Dictaminado'] . '</td>
                            <td align="left">' . $rs->fields['Motivo'] . '</td>
                            <td align="center" nowrap>' . ffecha($rs->fields['Registro']) . '</td>
                        </tr>';
            $total_pagos += $rs->fields['Monto'];
            $rs->MoveNext();
        }

        $html .='</tbody></table>';
        return $html;
    }
}
?>