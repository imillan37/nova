<?
class TSaldoFactura
{
	var $id_factura;
	var $folio;
	var $RFC;
	var $nombre;

	var $id_pedido;
	
	var $fecha_factura;       	// Fecha de emisión de la factura.
	var $fecha_vencimiento;		// Fecha máxima a la que debe ser totalmente pagada la factura. = FECHA_FACTURA + DIAS_DE_CREDITO.


	var $subtotal  		= 0;
	var $iva  		= 0;
	var $otroscostos    	= 0;
	var $total  		= 0;

	
	var $abonos		= array();
	var $valor_abonos 	= 0;	
	
	var $notas_credito  	= array();
	var $valor_notas_credito= 0;	

	var $notas_cargo   	= array();	
	var $valor_notas_cargo  = 0;	

	var $saldo_factura      = 0;	

	// Implementation
   //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//

	function TSaldoFactura($db, $id_factura, $DB_EMP )
	{
			$this->id_factura = $id_factura;

			$sql = "SELECT  		ID_Pedido,
							Folio,
							RFC,
							CONCAT(Nombre,' ',Ap_Paterno,' ',Ap_Materno),	
							Fecha,
							Vencimiento,
							Subtotal,
							IVA,
							(CostoAdm+CostoEnvio) As OtrosCostos,
							Total
					FROM   	".$DB_EMP.".facturas
					WHERE 	ID_Factura='".$id_factura."' ";

			$rs = $db->Execute($sql);	
			//debug($sql);

			$this->id_pedido 			= $rs->fields[0];
			$this->folio     			= $rs->fields[1];
			$this->RFC		 		= $rs->fields[2];
			$this->nombre	 			= $rs->fields[3];

			$this->fecha_factura	 		= $rs->fields[4];
			$this->fecha_vencimiento 		= $rs->fields[5];

			$this->subtotal  			= $rs->fields[6];
			$this->iva  				= $rs->fields[7];	
			$this->otroscostos      		= $rs->fields[8];
			$this->total  				= $rs->fields[9];	

			//--------------------------------------------------------------------------------------
			// Abonos en efectivo 
			//--------------------------------------------------------------------------------------

			$sql=	"	SELECT  Fecha_captura,
								Fecha_aplicacion, 
								Forma_pago,
								Monto
						FROM   	".$DB_EMP.".facturas_abonos
						WHERE 	ID_Factura='".$id_factura."' and Stat != 'Cancelado' ";	
			$rs = $db->Execute($sql);
			$i=0;
			if($rs->_numOfRows)
			  While(! $rs->EOF)
			  {

					$this->abonos[$i++]  = array('FCaptura'		=>$rs->fields[0],
												 'FAplicacion'	=>$rs->fields[1],
												 'Formato'		=>$rs->fields[2],
												 'Monto'		=>$rs->fields[3]	);

					$this->valor_abonos	+= $rs->fields[3];

					$rs->MoveNext() ;
			  }
			
			//--------------------------------------------------------------------------------------
			// Notas de Crédito
			//--------------------------------------------------------------------------------------
	
				
			$sql=	"	SELECT  Fecha_captura,
								Fecha_aplicacion, 
								Concepto,
								Monto
						FROM   	".$DB_EMP.".facturas_notas_credito
						WHERE 	ID_Factura='".$id_factura."' and Stat !='Cancelada'	";
	
			$rs = $db->Execute($sql);
			$i=0;
			if($rs->_numOfRows)
			  While(! $rs->EOF)
			  {

					$this->notas_credito[$i++]  = array( 'FCaptura'		=>$rs->fields[0],
														 'FAplicacion'	=>$rs->fields[1],
														 'Concepto'		=>$rs->fields[2],
														 'Monto'		=>$rs->fields[3]	);

					$this->valor_notas_credito	+= $rs->fields[3];
	
	
					$rs->MoveNext() ;
			  }
	
			//--------------------------------------------------------------------------------------
			// Notas de Cargo
			//--------------------------------------------------------------------------------------
	
			$sql=	"	SELECT  Fecha_captura,
								Fecha_aplicacion, 
								Concepto,
								Monto
						FROM   	".$DB_EMP.".facturas_notas_cargo
						WHERE 	ID_Factura='".$id_factura."' and Stat !='Cancelada'	";
	
			$rs = $db->Execute($sql);
			$i=0;
			if($rs->_numOfRows)
			  While(! $rs->EOF)
			  {

					$this->notas_cargo[$i++]  = array(   'FCaptura'		=>$rs->fields[0],
														 'FAplicacion'	=>$rs->fields[1],
														 'Concepto'		=>$rs->fields[2],
														 'Monto'		=>$rs->fields[3]	);

					$this->valor_notas_cargo	+= $rs->fields[3];
	
	
					$rs->MoveNext() ;
			  }
	
	
			//--------------------------------------------------------------------------------------

			$this->saldo_factura    = ($this->total + $this->valor_notas_cargo) - ($this->valor_abonos + $this->valor_notas_credito);

		
	}





};

//-------------------------------------------------------------------------------------------------------
//	TSaldoCliente : Saldo gobal del cliente.
//-------------------------------------------------------------------------------------------------------


class TSaldoCliente
{
	var $fecha_corte;
	var $facturas = array();
	var $id_cliente;
	var $nombre_cliente;
	
	var $saldo_vencido;
	var $saldo_por_vencer;	
	var $saldo_global;
	
	
	var $num_compras;
	var $num_facturas_vencidas;
	var $num_facturas_por_vencer;
	
	var $monto_abonos;
	
	var $notas_credito;
	var $monto_notas_credito;	

	var $notas_cargo;	
	var $monto_notas_cargo;	
	
	var $fecha_ini;
	var $fecha_fin;
	
	//--------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------
	
	
	function TSaldoCliente($db, $id_cliente, $fecha_corte, $DB_EMP,  $fecha1, $fecha2 )	
	{
			
			$this->fecha_corte = $fecha_corte;		
			$this->id_cliente  = $id_cliente;



			if( !empty($fecha1) and !empty($fecha2) )	
			{
				$this->fecha_ini = min($fecha1,$fecha2);
				$this->fecha_fin = max($fecha1,$fecha2);
			}
	


			$sql = "SELECT  ID_Vendedor,
					CONCAT(Nombre,' ',Ap_Paterno,' ',Ap_Materno)
					FROM   	".NUCLEO.".vendedores
					
					WHERE 	ID_Vendedor='".$id_cliente."' ";

			
			
			$rs = $db->Execute($sql);	
			
			
			

			$this->id_cliente		= $rs->fields[0];
			$this->nombre_cliente   = $rs->fields[1];
			$this->monto_abonos     = 0;



			

			$sql = "SELECT  ID_Factura
					FROM   	".$DB_EMP.".facturas
					WHERE 	ID_Vendedor='".$id_cliente."' ";
					
			if( !empty($this->fecha_ini) and !empty($this->fecha_fin))		
			{
				
				$min = min($this->fecha_ini,$this->fecha_fin);
				$max = max($this->fecha_ini,$this->fecha_fin);
				
				$sql .= " and Fecha BETWEEN '".$min."' and '".$max."' ";
			}
			
			
					
			$rs = $db->Execute($sql);			

			$this->num_facturas_vencidas	=0;
			$this->num_facturas_por_vencer	=0;
			$this->saldo_vencido			=0;
			$this->saldo_por_vencer			=0;	
			$this->num_compras = $rs->_numOfRows;

			

			if($rs->_numOfRows)
			  While(! $rs->EOF)
			  {

					$tmp =  new TSaldoFactura($db, $rs->fields[0], $DB_EMP);
					$this->facturas[] = $tmp;

					//debug($tmp->fecha_vencimiento." < ".$this->fecha_corte." = ".($tmp->fecha_vencimiento < $this->fecha_corte) );
					if($tmp->fecha_vencimiento < $this->fecha_corte)
					{
						$this->num_facturas_vencidas++;
						$this->saldo_vencido += $tmp->saldo_factura;
					}
					else
					{
						$this->num_facturas_por_vencer++;
						$this->saldo_por_vencer+= $tmp->saldo_factura;
				
					}
					
					$this->monto_abonos  += $tmp->valor_abonos;
					
					$this->notas_credito      	+= count($tmp->notas_credito);
					$this->monto_notas_credito  += $tmp->valor_notas_credito;

					$this->notas_cargo			+= count($tmp->notas_cargo);
					$this->monto_notas_cargo	+= $tmp->valor_notas_cargo;
	
	
	
					unset($tmp);
					$rs->MoveNext() ;

			  }			

			
			
			$this->saldo_global	= 	$this->saldo_vencido + $this->saldo_por_vencer;
		
	}
	//--------------------------------------------------------------------------------------
	
		
}
?>