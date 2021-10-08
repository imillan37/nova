<?                                                               
/*                                                          
    _________________________________________________________________________________________
   |  Titulo: Aplicación de abonos a estado de cuenta solidario             
   |                                                        
## |  Fecha : Viernes 17 de Julio de 2009
## | 
## |  Autor : Enrique Godoy Calderón                        
## |                                                        
## |  Nombre original del archivo : [aplicacion_pagos_solidarios.php]     
## |                                                        
## |                                                                                        
##  ¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯
########################################################################################
######################################################################################
*/
require_once($class_path."lib_credit.php");





class TDEPOSITO_SOLIDARIO
{

  var $db;
  var $fecha_corte;
  var $error;
  var $nerror    = 0;
  
  var $sucursal;         
  var $num_cuenta;      
  var $fecha_deposito ;  
  var $monto;   
  
  var $nombre;
  var $ciclo_gpo;  
  var $creditos;
  var $suma_cuotas;

  var $id_pago;
  var $result;






function TDEPOSITO_SOLIDARIO($id_cta, $id_grupo, $fecha_corte, $ciclo_ref)
{


        global $_SESSION;
        global $REMOTE_ADDR;

        $this->id_cta =         $id_cta;
        $this->id_grupo =       $id_grupo;
        $this->fecha_corte =    $fecha_corte;




        $this->db = ADONewConnection(SERVIDOR);  
        $this->db->Connect(IP,USER,PASSWORD,NUCLEO);


        $sql = "SELECT  edo_cta.ID_Edo_Cta,
                        edo_cta.monto,
                        ctas_banco.ID_Cuenta,
                        ctas_banco.ID_Sucursal,
                        edo_cta.fecha,
                        sucursales.Nombre AS Sucursal,
                        ctas_banco.Num_Cta AS Num_Cta
                        
                FROM edo_cta

                INNER JOIN ctas_banco ON edo_cta.ID_Cuenta = ctas_banco.ID_Cuenta
                
                LEFT JOIN aplicacion_depositos_solidarios ON aplicacion_depositos_solidarios.ID_Edo_Cta = edo_cta.ID_Edo_Cta
                
                LEFT JOIN sucursales ON sucursales.ID_sucursal = ctas_banco.ID_Sucursal

                WHERE edo_cta.tipo_flujo = 'Entrada' AND
                      edo_cta.ID_Cat_Conceptos = '-1' AND
                      aplicacion_depositos_solidarios.Fecha_Aplicacion IS NULL  and edo_cta.ID_Edo_Cta='".$this->id_cta."'  ";
        
        
        
        $rs=$this->db->Execute($sql);


        if($rs->fields[0] != $this->id_cta)
        {
        
                $this->error = "El identificador del depósito es inválido. ";
                $this->nerror++;                
                $this->state=-1;
                return;
        
        }
        
        
        
        
        
        
        $this->sucursal         = $rs->fields['Sucursal'];
        $this->id_sucursal      = $rs->fields['ID_Sucursal'];
        
        $this->num_cuenta       = $rs->fields['Num_Cta'];
        $this->fecha_deposito   = $rs->fields['fecha'];
        $this->monto            = $rs->fields['monto'];


         $sql = "SELECT grupo_solidario.ID_grupo_soli,
                        grupo_solidario.Nombre, 
                        grupo_solidario.Ciclo_gpo               
                
                FROM grupo_solidario 
                
                
                LEFT JOIN sucursales ON sucursales.ID_Sucursal = grupo_solidario.ID_Suc
                
                WHERE   grupo_solidario.Alta_cliente='Y' and 
                        grupo_solidario.Alta_credito='Y' and 
                        grupo_solidario.ID_grupo_soli='".$this->id_grupo."'  
                
                ORDER BY Ciclo_gpo DESC ";


        $rs = $this->db->Execute($sql);

        if($rs->fields[0] != $this->id_grupo)
        {
        
                $this->error = "El identificador del grupo solidario es inválido. ";
                $this->nerror++;                
                $this->state = -1;
                return;
        
        }




        $this->nombre           =  $rs->fields['nombre'];
        $this->ciclo_gpo        =  $rs->fields['ciclo_gpo'];


        $sql =  "SELECT  grupo_solidario_integrantes.id_factura, fact_cliente.Renta
                 FROM    grupo_solidario_integrantes, 
                         fact_cliente


                         INNER JOIN grupo_solidario on grupo_solidario_integrantes.ID_grupo_soli = grupo_solidario.ID_grupo_soli and
                                    grupo_solidario_integrantes.Ciclo_gpo                        = '".$ciclo_ref."'
                         
                         LEFT JOIN solicitud on solicitud.ID_Solicitud                          = grupo_solidario_integrantes.ID_Solicitud
                         LEFT JOIN promotores on grupo_solidario.ID_Promotor                    = promotores.Num_promo
                         LEFT JOIN sucursales on grupo_solidario.ID_Suc                         = sucursales.ID_Sucursal
                         
												 LEFT JOIN  grupo_solidario_baja_definitiva ON grupo_solidario_baja_definitiva.id_factura = fact_cliente.id_factura AND 
                                                              grupo_solidario_baja_definitiva.fecha_baja <= '".$this->fecha_corte."'
                         
                 WHERE   grupo_solidario_integrantes.id_factura                                 = fact_cliente.id_factura       and 
                         grupo_solidario_integrantes.ID_grupo_soli                              = '".$id_grupo."'               and
                         grupo_solidario_integrantes.Status                                     ='Activo'                       and 
                         grupo_solidario.Alta_credito                                           ='Y'                            and
                         grupo_solidario_integrantes.Cliente                                    ='Y'                            and
                         grupo_solidario_integrantes.Conformidad                                ='Y' 
												 
												 and 	grupo_solidario_baja_definitiva.id_factura IS NULL
												 
												 ";


        $rs=$this->db->Execute($sql);
        
        $this->creditos = array();
        

        $this->suma_cuotas = 0;


        if($rs->_numOfRows)
        {
           while(! $rs->EOF)
           {
                $key = $rs->fields['id_factura'];
                
                $renta = $rs->fields['Renta'];
                
                
               $this->creditos[$key] = $renta;
               
               $this->suma_cuotas   += $renta;
               
               
               $rs->MoveNext();
           }
        }
        else
        {
        
                $this->error = "No se encontraron créditos asociados al grupo : ".$this->nombre." ";
                $this->nerror++;                
                $this->state = -1;
                return;
        
        }
        








        if((!$this->nerror) and ($this->id_grupo) and ($this->id_cta))
        {

					// <<< ************************************************************************************** 
   				$Query = "SELECT	ID_Cierre           
										FROM		cierre_contable_log 
										WHERE		Fin_Solidarios IS NOT NULL              
										AND			Fin_Solidarios != '0000-00-00 00:00:00' 
										AND			Fecha_Cierre    = '".trim($this->fecha_corte)."' ";     
   				$rs = $this->db->Execute($Query);
   				$id_cierre = $rs->fields["ID_Cierre"]; 
					
					
					$saldo_vencido_grupo = 0;
					foreach($this->creditos AS $id_factura => $monto) {
						$Query = "SELECT	Saldo_Total_Vencido,   
															Adeudo_Total           
											FROM		cierre_contable_saldos 
											WHERE		ID_Cierre  = '".$id_cierre."'      
											AND			ID_Factura = '".$id_factura."'  "; 
	   				$rs = $this->db->Execute($Query);
  	 				$saldo_vencido_grupo               += $rs->fields["Saldo_Total_Vencido"]; 
						$arrObjetoSaldoVencido[$id_factura] = $rs->fields["Saldo_Total_Vencido"]; 
						$adeudo_total_grupo                += $rs->fields["Adeudo_Total"];        
						$arrObjetoAdeudoTotal[$id_factura]  = $rs->fields["Adeudo_Total"]; 
					}
					
					if( $saldo_vencido_grupo > 0 ) { 
						if( $this->monto < $saldo_vencido_grupo ) { 
							$monto_de_pago = $this->monto; 
						} else {
							$monto_de_pago = $saldo_vencido_grupo; 
						}
						foreach($this->creditos AS $id_factura => $monto) { 
							$arrPagoSaldoVencido[$id_factura] = ( $monto_de_pago * $arrObjetoSaldoVencido[$id_factura] ) / $saldo_vencido_grupo;  
							
							$arrObjetoAdeudoTotal[$id_factura] -= $arrPagoSaldoVencido[$id_factura]; 
							$adeudo_total_grupo                -= $arrPagoSaldoVencido[$id_factura]; 
						} 
					} 
					
					
					
					if( $this->monto > $saldo_vencido_grupo ) { 
						
						if( ( $this->monto - $saldo_vencido_grupo ) > $adeudo_total_grupo ) { 
							$monto_de_pago = $adeudo_total_grupo; 
						} else {
							$monto_de_pago = $this->monto - $saldo_vencido_grupo; 
						}
						
						foreach($this->creditos AS $id_factura => $monto) { 
							if( $adeudo_total_grupo > 0 ) { 
								$arrPagoSaldoVencido[$id_factura] += ( $monto_de_pago * $arrObjetoAdeudoTotal[$id_factura] ) / $adeudo_total_grupo;  
							} else {
								$arrPagoSaldoVencido[$id_factura] += 0;  
							}
							$ultima_factura = $id_factura; 
						} 
					}  
					
					// *** 
					if( ( $this->monto - $saldo_vencido_grupo - $adeudo_total_grupo ) > 0 ) { 
						$arrPagoSaldoVencido[$ultima_factura] += $this->monto - $saldo_vencido_grupo - $adeudo_total_grupo;  
					}
					// <<< ************************************************************************************** 

 
//$saldo_vencido_grupo  // EL SALDO VENCIDO DEL GRUPO
//$this->suma_cuotas    // LA SUMA DE LAS CUOTAS DEL GRUPO 
//$this->monto          // EL MONTO TOTAL DE PAGO DEL GRUPO
//$this->saldo_vencido  // EL SALDO VENCIDO DE CADA INTEGRANTE







                        $_suma_abonos = 0;
                        $sql = "INSERT INTO aplicacion_depositos_solidarios (ID_Edo_Cta,          Fecha_Aplicacion,             ID_Grupo_Soli,          ID_Usr,    Usuario,             IP) 
                                                                     VALUES ('".$this->id_cta."','".date("Y-m-d H:i:s")."', '".$this->id_grupo."',      '".$_SESSION['ID_USR']."','".$_SESSION['NOM_USR']."','".$REMOTE_ADDR."') ";
                        $this->db->Execute($sql);
                        if($this->db->_affectedrows())
                        {
                                $referencia = " Aplicado el día : ".date("Y-m-d H:i:s") ." por : $NOM_USR ";
                                $k=0;
                                foreach($this->creditos AS $id_factura => $monto)
                                {
                                
                                $monto = $arrPagoSaldoVencido[$id_factura]; //
                                
                                
                                        $_monto=str_replace(",","",$monto);
                                        if($_monto >= 0.01)
                                        {
                                                $_suma_abonos += $_monto;
                                                $sql="  INSERT INTO pagos
                                                        ( Num_compra, ID_concepto, Fecha, Monto, Forma, Subtipo, Aplicacion,Referencia, Activo)
                                                        (SELECT 
                                                            fact_cliente.num_compra             AS Num_compra,  
                                                            '1'                                 AS ID_concepto, 
                                                            '".$this->fecha_corte."'            AS Fecha,
                                                            '".$_monto."'                       AS Monto,
                                                           'Efectivo'                           AS Forma,   
                                                           'General'                            AS Subtipo,      
                                                            'Progresivo'                        AS Aplicacion,
                                                           '".$referencia."'                    AS Referencia,   
                                                            'S'                                 AS Activo

                                                          FROM    fact_cliente
                                                          WHERE   fact_cliente.ID_Factura='".$id_factura."' ) ";  
                                                 $this->db->Execute($sql);
                                                 $this->id_pago = $this->db->_insertid();
                                                $sql=" INSERT INTO aplicacion_depositos_solidarios_det (ID_Edo_Cta,ID_Pago,Fecha,Monto) VALUES ('".$this->id_cta."','".$this->id_pago ."','".$this->fecha_corte."','".$_monto."') ";
                                                $this->db->Execute($sql);
                                                $k++;
                                        }
                                }
                                $this->result = "Se aplicaron ".$k." abonos por un monto total de ".number_format($_suma_abonos,2)." con fecha ".ffecha($this->fecha_corte);
                                $this->state = $k;
                        }
                        else
                        {
                                $this->error = "No se logró insertar el registro de bitácora en [aplicacion_depositos_solidarios] ";
                                $this->nerror++;
                                $this->state=-1;
                                return;                 
                        
                        }

                















        }
        else
        {
                $this->error = "Error desconocido. ";
                $this->nerror++;
                $this->state=-1;
                return;
        
        
        }

}


};



?>

