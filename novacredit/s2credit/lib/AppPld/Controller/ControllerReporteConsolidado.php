<?php

/**
 *
 * @author Ignacio Ocampo
 * @category Contoller
 * @created Wed Nov 26, 2014
 * @version 1.0
 */


/**
 *
 *  @ Cargamos Vista
 */

class ControllerReporteConsolidado {

    public $db;

    public function __construct($db){
        $this->db = $db;
    }

    public function getDatosConsolidado($NumCliente, $NombreCliente, $FechaInicial, $FechaFinal)
    {
        $error = array();
        $sql_comp = "";
        if (!empty($NumCliente) && is_numeric($NumCliente)) {
            $NumCliente = addslashes($NumCliente);
            $sql_comp .= " AND clientes.Num_cliente = '" . $NumCliente . "' \n";
        }

        if (!empty($NombreCliente) && empty($NumCliente)) {
            $NombreCliente = addslashes($NombreCliente);
            $NombreCliente = str_replace(" ","%",$NombreCliente);
            $sql_comp .= " AND (CONCAT( clientes_datos.Nombre,' ',clientes_datos.NombreI,' ',clientes_datos.Ap_paterno,' ',clientes_datos.Ap_materno ) LIKE '%".$NombreCliente."%' OR CONCAT( clientes_datos_pmoral.Nombre_pfae,' ',clientes_datos_pmoral.NombreI_pfae,' ',clientes_datos_pmoral.Ap_paterno_pfae,' ',clientes_datos_pmoral.Ap_materno_pfae ) LIKE '%".$NombreCliente."%' OR clientes_datos_pmoral.Razon_social LIKE '%".$NombreCliente."%') ";
        }


        $sql = "SELECT 
                clientes.Num_cliente,
                CASE clientes.Regimen
                        WHEN 'PM'   THEN clientes_datos_pmoral.Razon_social

                        WHEN 'PFAE' THEN CONCAT( clientes_datos_pmoral.Nombre_pfae, ' ',
                                                clientes_datos_pmoral.NombreI_pfae, ' ',
                                                clientes_datos_pmoral.Ap_paterno_pfae, ' ',
                                                clientes_datos_pmoral.Ap_materno_pfae)

                        WHEN 'PF'  THEN CONCAT( clientes_datos.Nombre,' ',
                                                clientes_datos.NombreI,' ',
                                                clientes_datos.Ap_paterno,' ',
                                                clientes_datos.Ap_materno )
                        END  AS  Nombre_Cliente,

                IF (clientes.Regimen = 'PF', CONCAT(clientes_datos.Calle, ' ', clientes_datos.Num), CONCAT(clientes_datos_pmoral.Calle, ' ', clientes_datos_pmoral.Numero)) As Calle,
                IF (clientes.Regimen = 'PF', clientes_datos.Colonia, clientes_datos_pmoral.Colonia) As Colonia,
                IF (clientes.Regimen = 'PF', CONCAT(clientes_datos.Poblacion, IF(clientes_datos.Ciudad <> '',CONCAT(', ', clientes_datos.Ciudad), '')), CONCAT(clientes_datos_pmoral.Poblacion, IF(clientes_datos_pmoral.Ciudad <> '',CONCAT(', ', clientes_datos_pmoral.Ciudad), ''))) As Municipio,

                IF (clientes.Regimen = 'PF', clientes_datos.Telefono, clientes_datos_pmoral.Telefono) As Telefono

                FROM clientes

                LEFT JOIN clientes_datos ON clientes_datos.num_cliente = clientes.Num_cliente
                LEFT JOIN clientes_datos_pmoral ON clientes_datos_pmoral.num_cliente = clientes.num_cliente
                LEFT JOIN solicitud ON solicitud.ID_Solicitud = clientes_datos.ID_Solicitud
                WHERE 1
                ".$sql_comp;

        //debug($sql);
        $rs = $this->db->Execute($sql);
        if($rs->_numOfRows == 0) {
            return 'No se encontraron resultados.';
            die();
        }
        $num_cliente = $rs->fields['Num_cliente'];
        $nombre = $rs->fields['Nombre_Cliente'];
        $calle = $rs->fields['Calle'];
        $colonia = $rs->fields['Colonia'];
        $municipio = $rs->fields['Municipio'];
        $tel_casa = $rs->fields['Telefono'];
        $tel_contacto = $rs->fields['Tel_Contacto'];



        $html = '';
        $html .= '
    <script>
    semantic.accordion = {};
    semantic.accordion.ready = function() {
        var $accordion     = $(".ui.accordion");
        $accordion.accordion();
    };
    
    $(document).ready(semantic.accordion.ready);
    </script>
    <div class="ui small styled accordion" style="text-align:left; font-size:12px; font-weight:bold; width:100%;">
        <div class="active title">
            <i class="dropdown icon"></i>
            Datos del cliente
        </div>
        <div class="active content">
          
            <table class="ui definition table">
              <tbody>
                <tr>
                  <td style="width:15%; background-color:#458AC6; color:#FFF; text-align:right;" nowrap>N&uacute;m. Cliente: </td>
                  <td>' . $num_cliente . '</td>
                </tr>
                <tr>
                  <td style="background-color:#458AC6;color:#FFF; text-align:right;" nowrap>Nombre: </td>
                  <td>' . $nombre . '</td>
                </tr>
                <tr>
                      <td style="width:15%; background-color:#458AC6; color:#FFF; text-align:right;">Calle y No: </td>
                      <td colspan="6">' . $calle . '</td>
                </tr>
                <tr>
                  <td style="background-color:#458AC6;color:#FFF; text-align:right;">Colonia: </td>
                  <td colspan="6">' . $colonia . '</td>
                </tr>
                <tr>
                  <td style=" background-color:#458AC6;color:#FFF; text-align:right;">Delegaci&oacute;n o municipio: </td>
                  <td colspan="6">' . $municipio . '</td>
                </tr>
                <tr>
                  <td style=" background-color:#458AC6;color:#FFF; text-align:right;">Tel&eacute;fono casa: </td>
                  <td colspan="6">' . $tel_casa . '</td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Comienza segundo acordeon detalle consolidado-->

        <div class="accordion" style="text-align:left; font-size:12px; font-weight:bold; width:100%;">
            <div class="active title">
                <i class="dropdown icon"></i>
                Detalle consolidado
            </div>
            <div class="content active">

                <table class="ui table">
                    <tbody>
                    <tr style=" background-color:#407FB7;color:#FFF; text-align:center;">
                      <td style="width:14%;">ID cr&eacute;dito</td>
                      <td style="width:14%;">Monto</td>
                      <td style="width:13%;">Fecha inicio</td>
                      <td style="width:14%;">Pagos</td>
                      <td style="width:14%;">Notas cr&eacute;dito</td>
                      <td style="width:14%;">Total a pagar</td>
                      <td style="width:14%;">Saldo actual</td>
                    </tr>
                    </tbody>
                </table>';



        $sql = "SELECT  fact_cliente.id_factura,
                        fact_cliente.Nombre_Producto,
                        fact_cliente.plazo,
                        fact_cliente.Vencimiento,
                        fact_cliente.Capital,
                        fact_cliente.Fecha_Inicio,
                        fact_cliente.Fecha_Vencimiento,
                        fact_cliente.Renta,
                        fact_cliente.num_compra,
                        sucursales.Nombre AS Nombre_Sucursal,
                        (cierre_contable_saldos.Saldo_Total_Vencido + cierre_contable_saldos.Saldo_Total_Vigente) AS Saldo_Total

                FROM fact_cliente

                LEFT JOIN (
                    SELECT MAX(cierre_contable_saldos.ID_Cierre) AS ID_Cierre,
                    fact_cliente.id_factura
                    FROM cierre_contable_saldos
                    INNER JOIN fact_cliente ON fact_cliente.num_cliente = '" . $num_cliente . "'
                    AND fact_cliente.id_factura = cierre_contable_saldos.ID_Factura
                    GROUP BY fact_cliente.id_factura
                ) AS max_cierres ON max_cierres.id_factura = fact_cliente.id_factura

                LEFT JOIN cierre_contable_saldos ON cierre_contable_saldos.ID_Factura = fact_cliente.id_factura 
                AND cierre_contable_saldos.ID_Cierre = max_cierres.ID_Cierre

                LEFT JOIN sucursales ON sucursales.ID_Sucursal = fact_cliente.ID_Sucursal

                WHERE fact_cliente.num_cliente = '" . $num_cliente . "'";

        $rs = $this->db->Execute($sql);
        if($rs->_numOfRows == 0) {
            return 'No se encontraron cr&eacute;ditos activos para este cliente.';
            die();
        }

        while (!$rs->EOF) {

            $html .= '<div class="accordion" style="text-align:left; font-size:12px; font-weight:bold; width:100%;">
                <div class="title" style="padding:0;">
                    <table class="ui celled definition table" style="width:100%;" >
                        <tbody>';

            $num_compra = $rs->fields['num_compra'];
            $sucursal = $rs->fields['Nombre_Sucursal'];

            $sql_sum = "  SELECT SUM(Monto) AS Total_Monto
                            FROM cargos
                            WHERE Num_compra = '" . $num_compra . "'
                            AND Activo='Si'";

            $rs_sum = $this->db->Execute($sql_sum);
            $total_cargos = $rs_sum->fields['Total_Monto'];

            $sql_sum = "  SELECT SUM(Monto) AS Total_Monto
                            FROM notas_credito
                            WHERE Num_compra = '" . $num_compra . "'";

            $rs_sum = $this->db->Execute($sql_sum);
            $total_notas = $rs_sum->fields['Total_Monto'];

            $sql_fechas = '';
            $periodo = '';
            if(!empty($FechaInicial)) {
                list($y_i, $m_i, $d_i) = explode('-', $FechaInicial);

                if(checkdate($m_i, $d_i, $y_i)) {
                    $fecha_inicial = date("Y-m-d", strtotime($y_i."-".$m_i."-".$d_i));
                    $sql_fechas .= " AND pagos.Fecha >= '".$fecha_inicial."'";
                    $periodo .= ' del : '.ffecha($fecha_inicial);
                }
            }
            if(!empty($FechaFinal)) {
                list($y_f, $m_f, $d_f) = explode('-', $FechaFinal);
        
                if(checkdate($m_f, $d_f, $y_f)) {
                    $fecha_final = date("Y-m-d", strtotime($y_f."-".$m_f."-".$d_f));
                    $sql_fechas .= " AND pagos.Fecha <= '".$fecha_final."'";
                    $periodo .= ' al : '.ffecha($fecha_final);
                }
            }

            $sql_sum = "  SELECT SUM(Monto) AS Total_Monto
                            FROM pagos
                            WHERE Num_compra = '" . $num_compra . "'
                            AND activo='S'
                            ".$sql_fechas;

            $rs_sum = $this->db->Execute($sql_sum);
            $total_pagos = $rs_sum->fields['Total_Monto'];


            $html .= '      <tr style="text-align:right;">
                            <td style="width:14%;text-align:center;">' . $rs->fields['id_factura'] . '</td>
                            <td style="width:14%;">' . number_format($rs->fields['Capital'], 2) . '</td>
                            <td style="width:14%;text-align:center;">' . ffecha($rs->fields['Fecha_Inicio']) . '</td>
                            <td style="width:14%;">' . number_format($total_pagos, 2) . '</td>
                            <td style="width:14%;">' . number_format($total_notas, 2) . '</td>
                            <td style="width:14%;">' . number_format($total_cargos, 2) . '</td>
                            <td style="width:14%;">' . number_format($rs->fields['Saldo_Total'], 2) . '</td>
                        </tr>
                        </tbody>
                        </table>
                    </div>

                    <div class="content" style="padding:0;">
                        
                        <table class="ui celled table">
                      <tbody>
                        <tr>
                          <td style="width:15%; background-color:#458AC6; color:#FFF; text-align:right;">ID cr&eacute;dito: </td>
                          <td colspan="8">' . $rs->fields['id_factura'] . '</td>
                        </tr>
                        <tr>
                          <td style="background-color:#458AC6;color:#FFF; text-align:right;">Producto financiero: </td>
                          <td colspan="8">' . $rs->fields['Nombre_Producto'] . '</td>
                        </tr>
                        <tr>
                          <td style=" background-color:#458AC6;color:#FFF; text-align:right;">Plazo: </td>
                          <td colspan="8">' . $rs->fields['plazo'] . ' '  . $rs->fields['Vencimiento'] . '</td>
                        </tr>
                        <tr>
                          <td style=" background-color:#458AC6;color:#FFF; text-align:right;">Sucursal: </td>
                          <td colspan="8">' . $sucursal . '</td>
                        </tr>
                        <tr>
                          <td style=" background-color:#458AC6;color:#FFF; text-align:right;">Capital: </td>
                          <td colspan="8">' . number_format($rs->fields['Capital'], 2) . '</td>
                        </tr>
                        <tr>
                          <td style=" background-color:#458AC6;color:#FFF; text-align:right;">Fecha inicio: </td>
                          <td colspan="8">' . ffecha($rs->fields['Fecha_Inicio']) . '</td>
                        </tr>
                        <tr>
                          <td style=" background-color:#458AC6;color:#FFF; text-align:right;">Fecha t&eacute;rmino: </td>
                          <td colspan="8">' . ffecha($rs->fields['Fecha_Vencimiento']) . '</td>
                        </tr>
                        <tr>
                          <td style=" background-color:#458AC6;color:#FFF; text-align:right;">Valor cuota: </td>
                          <td colspan="8">' . $rs->fields['Renta'] . '</td>
                        </tr>
                        <tr>
                          <td style=" background-color:#458AC6;color:#FFF; text-align:right;">Monto apertura: </td>
                          <td colspan="8">' . number_format($rs->fields['Capital'], 2) . '</td>
                        </tr>';


            $html .='   <tr>
                          <td colspan="9" style="text-align:center;">Historial de pagos ' . $periodo . '</td>
                        </tr>

                        <tr style=" background-color:#407FB7;color:#FFF; text-align:center;">
                          <td>Folio</td>
                          <td>Fecha</td>
                          <td>Monto</td>
                          <td>Sucursal</td>
                          <td>Tipo</td>
                          <td>Usuario</td>
                          <td>Caja no.</td>
                          <td>Fecha registro</td>
                          <td>Concepto</td>
                        </tr>';

            /*
            if(!empty($FechaInicial)) {
                $FechaInicial = addslashes($FechaInicial);
            }
            if(!empty($FechaFinal)) {
                $FechaFinal = addslashes($FechaFinal);
            }
            */

            $sql_pagos = "  SELECT  pagos.ID_Pago,
                                    IF(conceptos.forma IS NULL,'!',conceptos.forma) AS Concepto,
                                    pagos.fecha AS Fecha,
                                    pagos.Monto,
                                    conceptos.Descripcion,
                                    pagos.Forma,
                                    pagos.id_caja_pagos,
                                    pagos.Reg,
                                    CONCAT(usuarios.Nombre, ' ',
                                    usuarios.AP_Paterno, ' ',
                                    usuarios.AP_Materno) AS Usuario,
                                    IFNULL(IFNULL(tesoreria_aplicacion_pagos_b.Tipo, tesoreria_aplicacion_pagos.Tipo), pagos.Forma) AS Tipo

                            FROM pagos
                            LEFT JOIN conceptos ON pagos.id_concepto = conceptos.id_concepto

                            LEFT JOIN tesoreria_depositos_referenciados_dtl ON tesoreria_depositos_referenciados_dtl.ID_Pago = pagos.ID_Pago
                            LEFT JOIN tesoreria_depositos_referenciados ON tesoreria_depositos_referenciados.ID_Deposito_Referenciado = tesoreria_depositos_referenciados_dtl.ID_Deposito_Referenciado

                            LEFT JOIN tesoreria_aplicacion_pagos_dtl ON tesoreria_aplicacion_pagos_dtl.ID_Pago = pagos.ID_Pago
                            LEFT JOIN tesoreria_aplicacion_pagos ON tesoreria_aplicacion_pagos.ID_Aplicacion = tesoreria_aplicacion_pagos_dtl.ID_Aplicacion

                            LEFT JOIN tesoreria_aplicacion_pagos AS tesoreria_aplicacion_pagos_b ON tesoreria_aplicacion_pagos_b.ID_Pago = pagos.ID_Pago

                            LEFT JOIN usuarios ON (usuarios.ID_User = tesoreria_depositos_referenciados.ID_Usuario OR usuarios.ID_User = tesoreria_aplicacion_pagos.ID_Usuario OR usuarios.ID_User = tesoreria_aplicacion_pagos_b.ID_Usuario)

                            WHERE num_compra = '" . $num_compra . "'
                            AND pagos.Activo = 'S' 
                            " . $sql_fechas ."
                            ORDER BY pagos.fecha, pagos.ID_Pago
                            ";

            $rs_pagos = $this->db->Execute($sql_pagos);

            $total_pagos = 0; 

            while (!$rs_pagos->EOF) {
                $html .= '<tr>
                          <td align="center">' . $rs_pagos->fields['ID_Pago'] . '</td>
                          <td align="center">' . ffecha($rs_pagos->fields['Fecha']) . '</td>
                          <td align="right">' . number_format($rs_pagos->fields['Monto'], 2) . '</td>
                          <td>' . $sucursal . '</td>
                          <td align="left">' . $rs_pagos->fields['Tipo'] . '</td>
                          <td>' . $rs_pagos->fields['Usuario'] . '</td>
                          <td align="center">' . $rs_pagos->fields['id_caja_pagos'] . '</td>
                          <td align="center">' . ffecha($rs_pagos->fields['Reg']) . '</td>
                          <td>' . $rs_pagos->fields['Descripcion'] . '</td>
                        </tr>';
                $total_pagos += $rs_pagos->fields['Monto'];
                $rs_pagos->MoveNext();
            }


            $html .= '<tr style=" background-color:#407FB7;color:#FFF; text-align:center;">
                          <td></td>
                          <td></td>
                          <td align="right">' . number_format($total_pagos, 2) . '</td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                        </tr>
                        </tbody>
                    </table><br>
                </div>
            </div>';

            $rs->MoveNext();
        } // Fin por cada crédito

        $html .= '
            </div>
          </div>
      </div>';
       
        return $html;
    }
    
    public function getCliente($NombreCliente)
    {
        //return $NombreCliente;
        $html = '';
        $term = str_replace(" ","%",addslashes(utf8_decode($NombreCliente)));
        $sql = "(
                    SELECT CONCAT(clientes_datos.Nombre,' ',clientes_datos.NombreI,' ',clientes_datos.Ap_paterno,' ',clientes_datos.Ap_materno) AS Nombre,
                    clientes_datos.Num_cliente
                    FROM clientes_datos
                    WHERE CONCAT(clientes_datos.Nombre,' ',clientes_datos.NombreI,' ',clientes_datos.Ap_paterno,' ',clientes_datos.Ap_materno) LIKE '%" . $term . "%'
                    GROUP BY CONCAT(clientes_datos.Nombre,' ',clientes_datos.NombreI,' ',clientes_datos.Ap_paterno,' ',clientes_datos.Ap_materno)
                    ORDER BY Nombre
                    LIMIT 10
                    )

                    UNION (

                    SELECT clientes_datos_pmoral.Razon_social COLLATE 'latin1_swedish_ci' As Nombre,
                    clientes_datos_pmoral.Num_cliente
                    FROM clientes_datos_pmoral
                    WHERE clientes_datos_pmoral.razon_social LIKE '%" . $term . "%'
                    GROUP BY clientes_datos_pmoral.razon_social
                    ORDER BY clientes_datos_pmoral.razon_social
                    LIMIT 10)";

        $rs = $this->db->Execute($sql);

        $html .= '<table class="ui definition table">
              <tbody>';
        if($rs->_numOfRows == 0) {
            $html .= '<tr>
                  <td>No se encontraron resultados</td>
                </tr>';
        }

        while (!$rs->EOF) {
            $html .= '<tr>
                  <td align="center">'.$rs->fields['Num_cliente'].'</td>  
                  <td><a style="cursor:pointer;" onclick="javascript:setNombre(\''.$rs->fields['Num_cliente'].'\', \''.$rs->fields['Nombre'].'\')">'.$rs->fields['Nombre'].'</a></td>
                </tr>';
            $rs->MoveNext();
        }

        $html .= "</tbody></table>";

        return $html;
    }
}