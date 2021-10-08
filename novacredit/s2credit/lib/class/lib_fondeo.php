<?php
	/***************************************************************************
	*                                                                          *
	****************************************************************************/
	
	class TFondeador {
		
 		var $db; 
 		var $ID_Fondeador; 
 		var $detalleSaldo; 
 
	
		function TFondeador( $ID_Fondeador, $db ) {

			$this->ID_Fondeador = $ID_Fondeador;
			$this->db           = $db;
			$this->detalleSaldo = false; 

		}
		
 
		

		function getSaldoCuenta($ID_Cuenta) {
		
			$Query = "SELECT	MAX(ID_Cierre) AS ID_Cierre
								FROM		cierre_contable_log"; 
			$rs = $this->db->Execute($Query);
			$ID_Cierre = $rs->fields["ID_Cierre"]; 

			$Query = "SELECT	SUM( 
													IF( Tipo = 'Entrada', 
														tesoreria_fondeador_movimientos.Monto, 
														IF( tesoreria_fondeador_movimientos.ID_Factura IS NULL, 
															( tesoreria_fondeador_movimientos.Monto * -1 ),
															IF( cierre_contable_saldos.ID_Factura IS NULL,															
																( tesoreria_fondeador_movimientos.Monto * -1 ),
																( ( cierre_contable_saldos.Saldo_Vencido_Capital + cierre_contable_saldos.Saldo_Vigente_Capital ) * -1 ) 																
															) 
														)
													) 
												) AS SALDO
								FROM		tesoreria_fondeador_movimientos
								LEFT JOIN	cierre_contable_saldos ON cierre_contable_saldos.ID_Factura = tesoreria_fondeador_movimientos.ID_Factura AND cierre_contable_saldos.ID_Cierre = '".$ID_Cierre."'
								WHERE		tesoreria_fondeador_movimientos.ID_Fondeador = '".$this->ID_Fondeador."'  
								AND			tesoreria_fondeador_movimientos.ID_Cuenta    = '".$ID_Cuenta."' "; 
			$rs = $this->db->Execute($Query);
			$getSaldoCuenta = $rs->fields["SALDO"]; 
			
			return $getSaldoCuenta; 		

		}


		function setDetalleSaldo($detalle) {

			$this->detalleSaldo = $detalle; 

		}


		function getSaldoInicial($Fecha) {

			$Query = "SELECT	MAX(ID_Cierre) AS ID_Cierre
								FROM		cierre_contable_log"; 
			$rs = $this->db->Execute($Query);
			$ID_Cierre = $rs->fields["ID_Cierre"]; 

			$condicionDetalle = " AND tesoreria_fondeador_movimientos.ID_Factura IS NULL ";
			if($this->detalleSaldo) {
				$condicionDetalle = "";			
			}

			$Query = "SELECT	SUM( 
													IF( Tipo = 'Entrada', 
														tesoreria_fondeador_movimientos.Monto, 
														IF( tesoreria_fondeador_movimientos.ID_Factura IS NULL, 
															( tesoreria_fondeador_movimientos.Monto * -1 ),
															IF( cierre_contable_saldos.ID_Factura IS NULL,															
																( tesoreria_fondeador_movimientos.Monto * -1 ),
																( ( cierre_contable_saldos.Saldo_Vencido_Capital + cierre_contable_saldos.Saldo_Vigente_Capital ) * -1 ) 																
															) 
														)
													) 
												) AS SALDO
								FROM		tesoreria_fondeador_movimientos
								LEFT JOIN	cierre_contable_saldos ON cierre_contable_saldos.ID_Factura = tesoreria_fondeador_movimientos.ID_Factura AND cierre_contable_saldos.ID_Cierre = '".$ID_Cierre."'
								WHERE		tesoreria_fondeador_movimientos.ID_Fondeador = '".$this->ID_Fondeador."'  
								".$condicionDetalle."
								AND			tesoreria_fondeador_movimientos.Fecha_Movimiento < '".$Fecha."' "; 
			$rs = $this->db->Execute($Query);
			$getSaldoInicial = $rs->fields["SALDO"]; 
			
			return $getSaldoInicial; 		
		}
		




		function getTotalFondeo($Fecha) {
		
			$Query = "SELECT	SUM( 
													IF( Tipo = 'Entrada', 
														tesoreria_fondeador_movimientos.Monto, 
														( tesoreria_fondeador_movimientos.Monto * -1 )
													) 
												) AS SALDO
								FROM		tesoreria_fondeador_movimientos
								WHERE		tesoreria_fondeador_movimientos.ID_Fondeador = '".$this->ID_Fondeador."'  
								AND			tesoreria_fondeador_movimientos.ID_Factura IS NULL
								AND			tesoreria_fondeador_movimientos.Fecha_Movimiento < '".$Fecha."' "; 
			$rs = $this->db->Execute($Query);
			$getTotalFondeo = $rs->fields["SALDO"]; 
			
			return $getTotalFondeo;
			
		}





	}
	 
?>