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

class ControllerReporteSolicitudes {

    public $db;

    public function __construct($db){
        $this->db = $db;
    }


    public function getSucursales($id_sucursal)
    {

        if($id_sucursal != 1)
        {
            $restriccion = "AND sucursales.ID_Sucursal = '".$id_sucursal."' ";
        }
        $Query = "SELECT
                  sucursales.ID_Sucursal,
                  sucursales.Nombre
                  FROM sucursales
                  WHERE sucursales.`Status` = 'Activo'
                  AND sucursales.ID_Sucursal > 0
                  $restriccion
                  ";

        $RESPUESTA                    = $this->db->Execute($Query);

        if($id_sucursal != 1)
        {
            $html = "<input type = 'hidden' name='Sucursales' id='Sucursales' value='".$RESPUESTA->fields["ID_Sucursal"]."'> ".$RESPUESTA->fields["Nombre"]." ";

        }else
        {

            $html = " <div class='ui fluid selection dropdown'>
                            <div class='text'>SELECCIONE</div>
                            <i class='dropdown icon'></i>
                            <input name='Sucursales' id='Sucursales' type='hidden'>
                            <div class='menu' id='SucursalDiv'>
                           ";


            while( !$RESPUESTA->EOF ) {
                $sucursal = $RESPUESTA->fields["ID_Sucursal"];
                $nombre_sucursal = $RESPUESTA->fields["Nombre"];
                $html .= "<div class='item' data-value='".$sucursal."'>".$nombre_sucursal."</div>";
                $RESPUESTA->MoveNext();
            }
            $html .= " </div>
                </div>";
        }

        return $html;

    }


    public function getSsolicitudes($SherchSolicitud,$SherchNombre,$Periodo,$Sucursal)
    {
        $accion = "";
        $class = "";
        if($Periodo == "Desactualizado")
        {
            $accion = "<";
            $class = "negative";
        }else
        {

            $accion = ">=";
            $class = "positive";
        }
        if($SherchSolicitud != "")
        {
            $reestriccion .= "AND b.Num_cliente = '".$SherchSolicitud."' ";
        }elseif(!empty($SherchNombre))
        {
            $SherchNombre = str_replace(" ","%",$SherchNombre);
            $reestriccion .= " AND CONCAT(c.Nombre,' ',c.NombreI,' ',c.Ap_paterno,' ',c.Ap_materno) like '%".$SherchNombre."%' ";
        }
        if($Sucursal != "" and  $Sucursal != 1)
        {
            $reestriccion .= "AND a.ID_Sucursal = '".$Sucursal."' ";
        }
		
		$Periodo = str_replace("pld_perfil_transaccional.","a.",$Periodo);
		
		$Query = "SELECT
                        b.Regimen,
                        a.ID_Cliente,
                        b.Num_cliente,
						a.ID_Tipocredito,
						IF( b.Regimen = 'PM', pm.Razon_social , CONCAT(z.Nombre,' ',z.NombreI,' ',z.Ap_paterno,' ',z.Ap_materno) ) as NombreCliente,
						d.Nombre AS sucursal,
						a.Ultima_Actualizacion AS Fecha_sistema,
						'Actualizado' as Tipo
				   FROM clientes b
			  LEFT JOIN clientes_datos_pmoral pm ON pm.Num_cliente = b.Num_cliente
			  LEFT JOIN clientes_datos z ON z.Num_cliente = b.Num_cliente
			  LEFT JOIN pld_perfil_transaccional a ON b.Num_cliente = a.ID_Cliente
			  LEFT JOIN solicitud_plvd c ON c.ID_Solicitud = b.ID_Solicitud
			  LEFT JOIN sucursales d ON d.ID_Sucursal = a.ID_Sucursal
			      WHERE true
				    AND a.Ultima_Actualizacion $accion DATE_SUB(CURDATE(),INTERVAL 6 MONTH)
					$reestriccion
			   GROUP BY a.ID_Cliente ";
		
			   $RESPUESTA = $this->db->Execute($Query); //debug( $Query );
			   		
			   		$contador = 0;
                    while( !$RESPUESTA->EOF ) {

                        $Num_cliente    = $RESPUESTA->fields["Num_cliente"];
                        $Fecha_sistema  = $RESPUESTA->fields["Fecha_sistema"];
                        $NombreCliente  = $RESPUESTA->fields["NombreCliente"];
                        $Tipo           = $RESPUESTA->fields["Tipo"];
                        $ID_Tipocredito = $RESPUESTA->fields["ID_Tipocredito"];
                        $ID_Cliente     = $RESPUESTA->fields["ID_Cliente"];
						
						$html .= "
                                <tr class='".$class."'>
                                    <td align='center'>".$Num_cliente."</td>
                                    <td align='left'>".strtoupper($NombreCliente)."</td>
                                    <td align='left'>".strtoupper($Tipo)."</td>
                                    <td align='left'>".$Fecha_sistema."</td>
                                    <td align='center'><a class='ui mini blue button' onclick='ActualizaDatos(".$ID_Cliente.",".$ID_Tipocredito." );'>DETALLE</a></td>
                                </tr>
                         ";
                        $contador++;
                        $RESPUESTA->MoveNext();
                    
                    }
                 if($contador == 0)
                 {
                   $html = "<tr>
                                <td align='center' colspan ='4'><p>No se encontraron solicitudes</p></td>
                            </tr>";
                 }

        return $html;

    }

}


?>