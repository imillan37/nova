<?

require_once($class_path."TSaldo.php");

class TComision
{
	var $db;
	var $oSaldoFactura;    // TSaldoFactura (Agregación)
	var $id_cliente;	
	var $id_factura;
 	var $id_cargo;
	var $id_vendedor;
	var $folio;
	var $id_pedido;
	var $id_futuro;
	var $id_comision_mst;



    var $num_convenio;
	var $caducada = false;
	
	var $vendedor;
	var $pedido;
	
	var $dias_gracia  = 0;	// Dias naturales a partir de los cuales caduca el pago de la comisión.
	
	var $fecha_factura;       	// Fecha de emisión de la factura.
	var $fecha_vencimiento;		// Fecha máxima a la que debe ser totalmente pagada la factura. = FECHA_FACTURA + DIAS_DE_CREDITO.
	var $fecha_saldo;			// Fecha en el que la factura fue saldada. (Fecha del cheque más viejo del conj. de los que cubren la factura.)
	var $fecha_caducidad;		// Fecha en la cual caduca la comisión. = 
	var $fecha_calculo;

	var $descuento_obra   = 0; // %porcentaje de descuento a la comisión por obra determinada.
	var $descuento_futuro = 0; // %porcentaje de descuento a la comisión por futuro.
	
	var $nombre_obra;
	var $nombre_cliente;
	
	var $porc_notas_credito = 0; 
	var $porc_notas_cargo   = 0; 
	
	var $notas_credito 		 	= array();
	var $valor_notas_credito 	= array();	

	var $notas_cargo   		 	= array();	
	var $valor_notas_cargo   	= array();	


	var $articulos_id        	= array();
	var $articulos_nombre    	= array();	
	var $articulos_cantidad  	= array();
	var $articulos_unidad  		= array();
	var $articulos_precio_venta = array();   // Valor venta artículo.
	var $articulos_precio_lista = array();   // Valor lista artículo.
	
	var $art_tipo_comision   	= array();   // (Porcentaje, Cuota Fija)
	var $art_valor_comision  	= array();   // Valor para hacer el cálculo
	var $art_base_comision   	= array();   // Cálculo base de la comisión por artículo
	

	var $comision_previamente_pagada = 0;
	var $comision_valor_actual       = 0;
	var $comision_saldo 			 = 0;	
	
	
	var $error =  false;
	var $error_msg ='';
	
	// Implementation

   //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//

	function TComision(&$db, $id_factura)
	{

		
		$this->db = $db;
		$this->id_factura = $id_factura;
		
  		//»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»		
		//  Existe algún registro maestro de algún cálculo previo ? 
			$sql = "SELECT ID_Comision_MST ".
				   "FROM   Comisiones_MST  ".
				   "WHERE  ID_Factura_MST = '".$this->id_factura."'	";			
			$rs = $db->Execute($sql);			
			$this->id_comision_mst = $rs->fields[0];		
		//««««««««««««««««««««««««««««««««««««««««
			
  		
			
		$this->oSaldoFactura 		= new TSaldoFactura($db,$id_factura);
		$this->id_cargo				= $this->oSaldoFactura->id_cargo;
		$this->id_cliente			= $this->oSaldoFactura->id_cliente;	
		$this->nombre_cliente		= $this->oSaldoFactura->nombre_cliente;
		$this->fecha_factura	    = $this->oSaldoFactura->fecha_factura;

		
  		//------------------------------------------------------------------------
  		// Parametros fundamentales de cálculo de comisiones.
		
		$fini = system_const('FUTUROS_FOLIO_INI',$db);
		if(empty($fini)) $fini = '0';		
		
		
		if($this->id_comision_mst)
		{
			//»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»
			// Si existe cálculo previo se usará esos mismos parámetros de cálculo.		
			$sql="SELECT 	Folio,
							Dias_gracia,							
							Fecha_calculo,
							Fecha_saldo,
							Fecha_vencimiento,
							Fecha_caducidad,
							Porc_ncredito,
							Porc_ncargo
					FROM 	Comisiones_MST
					WHERE   ID_Comision_MST='".$this->id_comision_mst."'";		
		
			$rs=$db->Execute($sql);
			
			
			$this->folio			  = $rs->fields[0];
			$this->dias_gracia 		  = $rs->fields[1];
			
			$this->fecha_calculo	  = $rs->fields[2];
			$this->fecha_saldo 		  = $rs->fields[3];
			$this->fecha_vencimiento  = $rs->fields[4];
			$this->fecha_caducidad 	  = $rs->fields[5];
						
			$this->porc_notas_credito = $rs->fields[6];
			$this->porc_notas_cargo   = $rs->fields[7];
			//««««««««««««««««««««««««««««««««««««««««
		}
		else
		{
			//--------------------------------------------------------------------
			// Cálculo totalmente nuevo.
			
			$this->dias_gracia 		  = system_const('COMISION_DIAS_GRACIA',  $db);				
			$this->porc_notas_credito = system_const('COMISION_NOTAS_CREDITO',$db);				
			$this->porc_notas_cargo   = system_const('COMISION_NOTAS_CARGO',  $db);				
			
			
			$this->fecha_vencimiento  = $this->oSaldoFactura->fecha_vencimiento;			
			$this->folio			  = $this->oSaldoFactura->folio;
  			$this->fecha_calculo	  = date("Y-m-d");
		
		    //»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»
			// Cálculo de la caducidad de la comisión!
			$JulianoCaducidad = $this->dias_gracia + GregorianToJD(fmes($this->fecha_vencimiento),fdia($this->fecha_vencimiento),fanio($this->fecha_vencimiento));  		
			
			$fecha_caducidad = explode("/",JDToGregorian($JulianoCaducidad));
			$fecha_caducidad[0] = ($fecha_caducidad[0]<=9)?("0".$fecha_caducidad[0]):($fecha_caducidad[0]);
			$fecha_caducidad[1] = ($fecha_caducidad[1]<=9)?("0".$fecha_caducidad[1]):($fecha_caducidad[1]);

			$this->fecha_caducidad = $fecha_caducidad[2]."-".$fecha_caducidad[0]."-".$fecha_caducidad[1];
			//««««««««««««««««««««««««««««««««««««««««
  		
  		
			//»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»
			// Está totalmente saldada la factura?
			if($this->oSaldoFactura->saldo_total > 0.09)
			{	// Si la factura no ha sido saldada, no hay comisión !!
				$this->fecha_saldo = '0000-00-00';	
				$this->error =  true;
				$this->error_msg ='La factura no está saldada, y no es posible hacer el cálculo de la comisión.';
				//return;  		
			}
			//««««««««««««««««««««««««««««««««««««««««
  		
  		
			//------------------------------------------------------------------------
			//> ¿ En qué fecha fue finalmente saldada la factura ?
			//> Fecha factura fue saldada. ==> (Fecha del cheque más viejo del conj. de los que cubren la factura.)
			$sql="	SELECT	Max(Mov_Bancario.FechaOperacion) AS fecha_saldo 
					FROM 	Aplicacion_AbonosCargos,
							Mov_Bancario,
							CargosClientes
					WHERE  	Mov_Bancario.ID_Mov_Ban 				= Aplicacion_AbonosCargos.ID_Mov_Ban AND 			    			   	
							Aplicacion_AbonosCargos.ID_Factura_MST 	= CargosClientes.ID_Factura_MST 	 AND
							Aplicacion_AbonosCargos.ID_Cliente 		= '".$this->id_cliente."' 			 AND 
							CargosClientes.ID_Cargo   				= '".$this->id_cargo."' ";  		

			 $rs=$db->Execute($sql);		 
			 $this->fecha_saldo = $rs->fields[0];
			//------------------------------------------------------------------------

		}
			
		//»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»
		// Si la fecha en que se saldó la cuenta es mayor a la fecha de caducidad, no hay comisión. 		
		if($this->fecha_saldo > $this->fecha_caducidad)
		{
			$this->error =  true;
			$this->caducada=  true;
			$this->error_msg ="La comisión ha caducado. La fecha de caducidad de la comisión (".ffecha($this->fecha_caducidad).") es anterior a la fecha en que se saldó la factura (".ffecha($this->fecha_saldo) .").";			

		}		 
  		//««««««««««««««««««««««««««««««««««««««««
  		
  		
  		//------------------------------------------------------------------------
		// Detalles de la factura y descuentos a la comisión por obra y futuro
		 
		 $sql="	SELECT			Pedidos_Venta_MST.ID_Pedido,
								Pedidos_Venta_MST.ID_Vendedor,
								Pedidos_Venta_MST.Folio_pedido, 
								Cat_Vendedores.Nombre,
								Direcciones_Entrega.Comision,
								Futuros_MST.Part_vendedor,
								Futuros_MST.ID_Futuro_MST,
								Direcciones_Entrega.Nombre
				
				FROM 			Pedidos_Venta_MST,
								Remision_Venta_DTL,
								CargosClientes,
								Cat_Vendedores
				LEFT JOIN 		Direcciones_Entrega ON Direcciones_Entrega.ID_Cliente = Pedidos_Venta_MST.ID_Cliente AND
													   Direcciones_Entrega.ID_Entrega = Pedidos_Venta_MST.ID_Entrega
				LEFT JOIN 		Futuros_MST 		ON Futuros_MST.ID_Futuro_MST = Pedidos_Venta_MST.ID_Futuro_MST																 
				WHERE   		Cat_Vendedores.ID_Vendedor 		   = Pedidos_Venta_MST.ID_Vendedor  AND
								Pedidos_Venta_MST.ID_Pedido		   = Remision_Venta_DTL.ID_Pedido	AND
								Remision_Venta_DTL.ID_Remision_Vta = CargosClientes.ID_Remision 	AND		 		 
								CargosClientes.ID_Cargo   		   = '".$this->id_cargo."' ";  
		 
		 

		  $rs=$db->Execute($sql);		  
		  $this->id_pedido   		= $rs->fields[0];		  
		  $this->id_vendedor 		= $rs->fields[1];
		  $this->pedido 	 		= $rs->fields[2]; 	
		  $this->vendedor    		= $rs->fields[3];
		  $this->descuento_obra 	= $rs->fields[4];		  
		  $this->descuento_futuro 	= $rs->fields[5];
		  $this->id_futuro 	    	= $rs->fields[6];
		  $this->nombre_obra    	= $rs->fields[7];		  
		  $this->num_convenio	  = $fini + $this->id_futuro;
		  

  		if(empty($this->id_pedido))
  		{
			$this->error =  true;
			$this->error_msg ='La factura no cuenta con remisión, por lo que no califica para pago de comisión.';
			return;  		
  		}

  		if(empty($this->id_vendedor))
  		{
			$this->error =  true;
			$this->error_msg ='No se ha encontrado al vendedor que gestionó el pedido del que emana ésta factura.';
			return;  		
  		}




		//»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»
		// Si ya existía algún registro previo.	
		if($this->id_comision_mst)
		{
			
			$sql = " SELECT Descuento_obra, Descuento_futuro ".
				   " FROM 	Comisiones_MST  ".
				   " WHERE ID_Comision_MST='".$this->id_comision_mst."'";
			$rs=$db->Execute($sql);
			
			$this->descuento_obra 		= $rs->fields[0];
			$this->descuento_futuro 	= $rs->fields[1];

		
		}			
  		//««««««««««««««««««««««««««««««««««««««««			
			




	  //----------------------------------------------------------------------
	  //Detalle de los artículos de la factura y su comisión encaso de ser por cuota fija.
	
	
	  if($this->id_comision_mst)
	  {
		//»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»
		// Tomámos los artículos del histórico de la comisión.	
		
		$sql="	SELECT 	Comisiones_DTL.ID_Producto,
						Facturas_DTL.Descripcion,
						Comisiones_DTL.Cantidad,
						Facturas_DTL.Unidad,	
						Comisiones_DTL.Precio_venta,
						Comisiones_DTL.Precio_lista,						
						Comisiones_DTL.TipoComision,
						Comisiones_DTL.CuotaComision
				FROM 	Comisiones_DTL, Facturas_DTL
				WHERE 	Comisiones_DTL.ID_Factura_MST  = Facturas_DTL.ID_Factura_MST and
						Comisiones_DTL.ID_Producto     = Facturas_DTL.ID_Producto    and
						Comisiones_DTL.ID_Comision_MST = '".$this->id_comision_mst."'  ";

		$rs=$db->Execute($sql);	
		$i=0;
		if($rs)
			while(! $rs->EOF)
			{
				  $this->articulos_id[$i]    			= $rs->fields[0];
				  $this->articulos_nombre[$i]    		= $rs->fields[1];
				  $this->articulos_cantidad[$i]  		= $rs->fields[2];			 			  
				  $this->articulos_unidad[$i]  			= $rs->fields[3];
				  $this->articulos_precio_venta[$i]		= $rs->fields[4];
				  $this->articulos_precio_lista[$i]		= $rs->fields[5];				  
				  $this->art_tipo_comision[$i]   		= $rs->fields[6];   // (Porcentual, CuotaFija)
				  $this->art_valor_comision[$i]  		= $rs->fields[7];   // Valor para hacer el cálculo

				  if( trim($this->art_tipo_comision[$i])  == 'Cuota_Fija')
				  {
						$this->art_base_comision[$i]   = $this->art_valor_comision[$i] *  $this->articulos_cantidad[$i];  
				  
				  }
				  else
				  		$this->art_base_comision[$i]   = ($this->art_valor_comision[$i]/100) * $this->articulos_precio_venta[$i] * $this->articulos_cantidad[$i];  
				  
				  $rs->MoveNext();
				  $i++;
			}	  	  
		//««««««««««««««««««««««««««««««««««««««««	
 
	  
	  }
	  else
	  {		
		//»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»
		// Tomámos los artículos de la factura tal y como están actualmente.	
		
		$sql="	SELECT 	Facturas_DTL.ID_Producto,
						Facturas_DTL.Descripcion,
						Facturas_DTL.Cantidad,
						Facturas_DTL.Unidad,
						Facturas_DTL.Precio    AS Precio_venta,
						Productos.Precio_venta AS Precio_Lista,
						Productos.TipoComision,
						Productos.CuotaComision			 
				FROM   	Facturas_DTL, Productos
				WHERE  	Facturas_DTL.ID_Producto = Productos.ID_Producto AND
						Facturas_DTL.ID_Factura_MST = '".$this->id_factura."' ";

		$rs=$db->Execute($sql);
		//debug($sql);
		$i=0;
		if($rs)
			while(! $rs->EOF)
			{
				  $this->articulos_id[$i]    			= $rs->fields[0];
				  $this->articulos_nombre[$i]    		= $rs->fields[1];
				  $this->articulos_cantidad[$i]  		= $rs->fields[2];			 			  
				  $this->articulos_unidad[$i]  			= $rs->fields[3];
				  $this->articulos_precio_venta[$i]		= $rs->fields[4];
				  $this->articulos_precio_lista[$i]		= $rs->fields[5];				  
				  $this->art_tipo_comision[$i]   		= $rs->fields[6];   // (Porcentual, CuotaFija)
				  $this->art_valor_comision[$i]  		= $rs->fields[7];   // Valor para hacer el cálculo

				  if($this->art_tipo_comision[$i]  == 'Cuota_Fija')
				  {
						$this->art_base_comision[$i] = $this->art_valor_comision[$i] *  $this->articulos_cantidad[$i];  
						  //debug("Cuota_Fija : (" .$this->art_valor_comision[$i] ." *  ".$this->articulos_cantidad[$i]."  = ".$this->art_base_comision[$i] );
				  }
				  

				  $rs->MoveNext();
				  $i++;
			}
		
		//««««««««««««««««««««««««««««««««««««««««	
		
	   

		//»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»»
		//Calcular comisión en base a la tabla de descuento por línea 
		//para los artículos con tipo de comisión = 'Porcentual'
		  for($i=0; $i<count($this->articulos_id); $i++)
			  if($this->art_tipo_comision[$i]  == 'Porcentual')
			  {
					if($this->articulos_precio_lista[$i] > 0)
					{
						$porcentaje_descuento = 100*($this->articulos_precio_lista[$i] - $this->articulos_precio_venta[$i])/($this->articulos_precio_lista[$i]);
						$porcentaje_descuento = ($porcentaje_descuento<0)?(0):($porcentaje_descuento);
					}
					else
					{
						$porcentaje_descuento  = 100;
						$this->error =  true;
						$this->error_msg ='Durante el cálculo de la comisión se encontráron artículos con precio de lista CERO.';

					}	  			    


					$sql="	SELECT 	 Descuento_Comision.Comision
							FROM     Descuento_Comision, Productos
							WHERE    Productos.ID_Producto = '".$this->articulos_id[$i] ."' AND
									 Productos.ID_Linea = Descuento_Comision.ID_Linea 		AND
									 Descuento_Comision.Limite_sup <= '".$porcentaje_descuento."'				AND
									 Descuento_Comision.Limite_inf >= '".$porcentaje_descuento."'	 ";	
					//debug($sql);


					 $rs=$db->Execute($sql);
					 $this->art_valor_comision[$i] = $rs->fields[0]/100;	

					 $this->art_base_comision[$i]   = $this->art_valor_comision[$i] * $this->articulos_precio_venta[$i] * $this->articulos_cantidad[$i];  
					 $this->art_valor_comision[$i] *= 100;

				}
		 //««««««««««««««««««««««««««««««««««««««««	
		
		}		  
	  //----------------------------------------------------------------------
	  //Cálculo de las notas de crédito (bonificación o devolución)y de cargo asociadas a una factura
	 
		$sql = "SELECT 	Folio,  Monto
				FROM 	NotasCredito_MST
				WHERE 	NotasCredito_MST.ID_Factura_MST = '".$this->id_factura."' ";
		
		
		// debug($sql);
		$i = 0;
		$rs=$db->Execute($sql);
		if($rs)
		   while(! $rs->EOF)
		   {
				$this->notas_credito[$i] 		 = $rs->fields[0];
				$this->valor_notas_credito[$i]   = $rs->fields[1];
				$i++;
				$rs->MoveNext();
		   }

	  //----------------------------------------------------------------------
	  //Cálculo de las notas de cargo asociadas a la factura
	
		$sql = "SELECT 	Folio,  Monto
				FROM 	NotasCargo
				WHERE 	NotasCargo.ID_Factura_MST = '".$this->id_factura."' ";		
		$i = 0;
		$rs=$db->Execute($sql);
		
		if($rs)
		   while(! $rs->EOF )
		   {	

				$this->notas_cargo[$i]			= $rs->fields[0];
				$this->valor_notas_cargo[$i]	= $rs->fields[1];
				$i++;
				$rs->MoveNext();
		   }			 
  //----------------------------------------------------------------------
  // Hay que saber si ya se le había pagado algo por la comisión de esta factura.	 

   $this->comision_previamente_pagada = 0;
 
 	$sql =  "	SELECT 	SUM(Comisiones.Monto_pagado)
				FROM 	Comisiones_MST, Comisiones
				WHERE 	Comisiones_MST.ID_Comision_MST = Comisiones.ID_Comision_MST AND 
						Comisiones_MST.ID_Factura_MST  = '".$this->id_factura."'  ";

	
   $rs  = $db->Execute($sql);	
   $this->comision_previamente_pagada = $rs->fields[0];
   
   
//------------------------------------------------------------------------   
   
   // Sumamos las comisiones individuales por artículo.
   $this->comision_valor_actual = 0; 
   for($i=0; $i<=count($this->articulos_id); $i++)
   {   	
   		$this->comision_valor_actual += $this->art_base_comision[$i];
   }
   

   // Descontamos las notas de crédito.
   if($i=count($this->notas_credito))
	for($j=0; $j<$i; $j++)
	 {
		$this->comision_valor_actual -= ($this->porc_notas_credito * $this->valor_notas_credito[$j]);
	 }


   // Sumamos las notas de cargo.
   if($i=count($this->notas_cargo))
	for($j=0; $j<$i; $j++)
	 {
		$this->comision_valor_actual += ($this->porc_notas_cargo   * $this->valor_notas_cargo[$j]);
	 }

//------------------------------------------------------------------------   
 
   // Descuento por obra y/o por convenio (futuro)
 
 	// Si no se establece %participación a la obra es como si la comisión fuera del 100%
 	if($this->descuento_obra == 0 ) $this->descuento_obra = 100;
 	
 
	$tmp1 = ($this->descuento_obra/100)    * $this->comision_valor_actual;
	$tmp2 = $this->descuento_futuro  * $this->comision_valor_actual;


	//Si viene de futuro tomámos la mínima de entre las dos
	
	if($this->id_futuro)	
		$this->comision_valor_actual = min($tmp1, $tmp2);
	else
		$this->comision_valor_actual = $tmp1;


	//--------------------------------------------------------------------------------------	
	if( ($this->caducada) or ($this->oSaldoFactura->saldo_total > 0.09) ) 	
		$this->comision_saldo = 0;	
	else	
		$this->comision_saldo = ($this->comision_valor_actual - $this->comision_previamente_pagada);	
	
	
	} //end Constructor
	//------------------------------------------------------------------------------

   //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
	
	
    function ReporteIndividual()
	{

		if($this->error)
		{
			
			
			$script .= "<SPAN ID='S2R'> ".$this->error_msg."</SPAN>\n";
			$script .= "<BR><BR> \n";
			//return;
		}


		$script .= " <Center ID='S2'><U> Cálculo de comisión.  </U></Center><BR>\n";
		
		$script .= " <Center ID='S2'><span style='font-size: 12px; '>Vendedor : ".$this->vendedor.".</span></Center> \n<BR>";


		//-------------------------------------------------------------------------------
		//							Datos de la Factura
		//-------------------------------------------------------------------------------

		$script .= " <SPAN ID='S2'><U> Datos de la factura </U></SPAN><BR><BR>";
		
		$script  .= "<TABLE ALIGN='center' WIDTH='100%' BORDER='0' CELLPADDING=0 CELLSPACING=1 BGCOLOR='gray' ><TR><TD>\n";
			$script  .= "<TABLE ALIGN='center' WIDTH='100%' BORDER='0' CELLSPACING=1 CELLPADDING=1  rules=groups   BGCOLOR='#e8e8e8' >\n";
				$script .= "<TR BGCOLOR='White'><TD BGCOLOR='#e8e8e8' ID='S2'> Factura  				: </TD><TD ID='S2'>&nbsp;".$this->folio."</TD></TR>\n";					
				$script .= "<TR BGCOLOR='White'><TD BGCOLOR='#e8e8e8' ID='S2'> Cliente    				: </TD><TD>&nbsp;".$this->nombre_cliente."</TD></TR>\n";		
				$script .= "<TR BGCOLOR='White'><TD BGCOLOR='#e8e8e8' ID='S2'> Obra    					: </TD><TD>&nbsp;".$this->nombre_obra."</TD></TR>\n";		
				$script .= "<TR BGCOLOR='White'><TD BGCOLOR='#e8e8e8' ID='S2'> Pedido   				: </TD><TD>&nbsp;".$this->pedido."</TD></TR>\n";

				if($this->id_futuro)
					$script .= "<TR BGCOLOR='White'><TD  BGCOLOR='#e8e8e8' ID='S2'> Convenio número 		: </TD><TD>&nbsp;".$this->num_convenio."</TD></TR>\n";	

				$script .= "<TR BGCOLOR='White'><TD  BGCOLOR='#e8e8e8' ID='S2'> Monto    : </TD><TD>&nbsp;$".number_format($this->oSaldoFactura->monto_total_factura,2)."</TD></TR>\n";
				$script .= "<TR BGCOLOR='White'><TD  BGCOLOR='#e8e8e8' ID='S2'> Saldo Actual : </TD><TD>&nbsp;$".number_format($this->oSaldoFactura->saldo_total,4)."</TD></TR>\n";

			$script  .= "</TABLE>\n";
		$script  .= "</TD></TR></TABLE> <BR> \n";
	
		//-------------------------------------------------------------------------------
		//							Parámetros de cálculo
		//-------------------------------------------------------------------------------
	
	
	   $script .= " <SPAN ID='S2'><U> Parámetros de cálculo aplicados </U></SPAN><BR><BR> ";
	
		$script  .= "<TABLE ALIGN='center' WIDTH='100%' BORDER='0' CELLPADDING=0 CELLSPACING=1 BGCOLOR='gray' ><TR><TD>\n";			
			$script  .= "<TABLE ALIGN='center' BORDER=0 CELLSPACING=1 CELLPADDING=1 WIDTH='100%'   BGCOLOR='#e8e8e8' >\n";
				$script .= "<TR BGCOLOR='White'><TD BGCOLOR='#e8e8e8'  ID='S2' WIDTH='50%'> Dias de gracia 		  : </TD><TD>&nbsp;".$this->dias_gracia." dias.</TD></TR>\n";		
				$script .= "<TR BGCOLOR='White'><TD BGCOLOR='#e8e8e8'  ID='S2'> Fecha cálculo            : </TD><TD>&nbsp;". ffecha($this->fecha_calculo)."		</TD></TR>\n";       
				$script .= "<TR BGCOLOR='White'><TD BGCOLOR='#e8e8e8'  ID='S2'> Fecha factura            : </TD><TD>&nbsp;". ffecha($this->fecha_factura)."		</TD></TR>\n";       
				$script .= "<TR BGCOLOR='White'><TD BGCOLOR='#e8e8e8'  ID='S2'> Fecha vencimiento        : </TD><TD>&nbsp;". ffecha($this->fecha_vencimiento)."	</TD></TR>\n"; 	
				$script .= "<TR BGCOLOR='White'><TD BGCOLOR='#e8e8e8'  ID='S2'> Fecha en que se saldó    : </TD><TD>&nbsp;". ffecha($this->fecha_saldo)."		</TD></TR>\n"; 			
				$script .= "<TR BGCOLOR='White'><TD BGCOLOR='#e8e8e8'  ID='S2'> Fecha caducidad comisión : </TD><TD>&nbsp;". ffecha($this->fecha_caducidad)."	</TD></TR>\n"; 		
				if($this->descuento_obra)
					$script .= "<TR BGCOLOR='White'><TD BGCOLOR='#e8e8e8'  ID='S2'> Participación en la obra :     </TD><TD>&nbsp;%".$this->descuento_obra   ."		</TD></TR>\n"; 

				if($this->id_futuro)
					$script .= "<TR BGCOLOR='White'><TD  BGCOLOR='#e8e8e8' ID='S2'> Participación en el convenio : </TD><TD>&nbsp;".$this->descuento_futuro ."		</TD></TR>\n"; 

				$script .= "<TR BGCOLOR='White'><TD BGCOLOR='#e8e8e8'  ID='S2'> Deducción nominal sobre notas de crédito : </TD><TD>&nbsp;%".number_format(($this->porc_notas_credito * 100),3)."</TD></TR>\n";  
				$script .= "<TR BGCOLOR='White'><TD BGCOLOR='#e8e8e8'  ID='S2'> Comisión  nominal sobre notas de cargo   : </TD><TD>&nbsp;%".number_format(($this->porc_notas_cargo   * 100),3)."</TD></TR>\n";  	
			$script .= "</TABLE>\n";
		$script  .= "</TD></TR></TABLE><BR>   \n";
		
		//--------------------------------------------------------
		// Detalle de artículos		
		//--------------------------------------------------------

		if(count($this->articulos_id ))
		{
			$script .= " <SPAN ID='S2'><U> Detalle de la factura. </U></SPAN><BR><BR>\n ";		
			$script  .= "<table border='0' WIDTH='100%' style='PADDING: 2px; BACKGROUND-COLOR: black;' cellpadding='0' cellspacing='1'>\n";
			
				$script .= "<TR align='center' ID='S2' style=' FONT-SIZE:10px; PADDING: 2px; BACKGROUND-COLOR: #e8e8e8; FONT-WEIGHT: bold'> \n";
				$script .= "	<TH ID='S2'> Artículo				</TH>\n";
				$script .= "    <TH ID='S2'> Precio <BR>lista		</TH>\n";				
				$script .= "    <TH ID='S2'> Precio <BR>cliente		</TH>\n";
				$script .= "    <TH ID='S2'> Descuento				</TH>\n";				
				$script .= "    <TH ID='S2' COLSPAN='2'> Cantidad	</TH>\n";
				$script .= "    <TH ID='S2'> Subtotal				</TH>\n";				
				$script .= "    <TH ID='S2'> Tipo de<BR> comisión	</TH>\n";				
				$script .= "    <TH ID='S2'> Valor <BR>comisión		</TH>\n";		
				$script .= "    <TH ID='S2'> Comisión<BR> nominal  	</TH>\n";				
				$script .= "</TR>\n";
			
			$det_articulos = array();
			for($i=0; $i<count($this->articulos_id); $i++)
			{
				$script .= "<TR  style='FONT-SIZE:10px;  BACKGROUND-COLOR: white; FONT-WEIGHT: normal'> \n";
				$script .= "	<TD> ".$this->articulos_nombre[$i]  ."</TD>\n";
				$script .= "    <TD ALIGN='right'> $".number_format($this->articulos_precio_lista[$i],2)."</TD>\n";			
				$script .= "    <TD ALIGN='right'> $".number_format($this->articulos_precio_venta[$i],2)."</TD>\n";
				
				$descuento = (100*($this->articulos_precio_lista[$i] - $this->articulos_precio_venta[$i])/$this->articulos_precio_lista[$i]);
				$script .= "    <TD ALIGN='right'> %".number_format($descuento ,2)."</TD>\n";								
				$script .= "    <TD ALIGN='right'> ".number_format($this->articulos_cantidad[$i],3)."</TD>\n";
				$script .= "    <TD ALIGN='left' >&nbsp;".$this->articulos_unidad[$i]."</TD>\n";
				
				$subtotal = ($this->articulos_cantidad[$i] * $this->articulos_precio_venta[$i]);
				
				$script .= "    <TD ALIGN='right' > $".number_format($subtotal ,2)."</TD>\n";
				$script .= "    <TD ALIGN='center'> ".$this->art_tipo_comision[$i]." </TD>\n";				
				
				$prefix=($this->art_tipo_comision[$i] == 'Porcentual')?('%'):('$');
				$script .= "    <TD ALIGN='right'> ".$prefix.number_format($this->art_valor_comision[$i],2)."</TD>\n";		
				$script .= "    <TD ALIGN='right'> $".number_format($this->art_base_comision[$i],2)." </TD>\n";
				
				$script .= "</TR>\n";
				
				$det_articulos[0] += $subtotal;
				$det_articulos[1] += $this->art_base_comision[$i];
	


			}

			$script .= "<TR style='FONT-SIZE:10px;  BACKGROUND-COLOR: #e8e8e8; FONT-WEIGHT: normal'>\n";
			$script .= "<TH COLSPAN='4' ALIGN='center'> ".$i." Artículos</TH>\n";
			$script .= "    <TH ALIGN='right' COLSPAN='3'>  $".number_format($det_articulos[0],2)."</TH>\n";	
			$script .= "    <TH ALIGN='right' COLSPAN='3'>  $".number_format($det_articulos[1],2)."</TH>\n";					
			$script .= "</TR>\n";
	
					
			$script .= "</TABLE> <BR>  \n";		
		
		}
				
		//--------------------------------------------------------		
		// Detalle de notas de crédito
		//--------------------------------------------------------
		
		if(count($this->notas_credito ))
		{
			$script .= " <SPAN ID='S2'><U> Notas de crédito </U></SPAN><BR><BR>\n ";		
			$script  .= "<table border='0' WIDTH='100%' style='PADDING: 2px; BACKGROUND-COLOR: black;' cellpadding='0' cellspacing='1'>\n";
			$sumanotas = 0;
			
			for($i=0; $i<count($this->notas_credito ); $i++)
			{
				$script .= "<TR class='pbty' style=' FONT-SIZE:10px; PADDING: 2px; BACKGROUND-COLOR: white; FONT-WEIGHT: bold'> \n";				
				$script .= "<TD Colspan='2'>&nbsp; Nota de crédito # ".$this->notas_credito[$i]."</TD><TD ALIGN='right'>$".number_format($this->valor_notas_credito[$i],2)."</TD></TR>\n";
				$sumanotas = $this->valor_notas_credito[$i];
			
			}
						
			$script .= "<TR 	class='pbty' style=' FONT-SIZE:10px; PADDING: 2px; BACKGROUND-COLOR: white; FONT-WEIGHT: bold'> \n";
			$script .= "<TD> 	Total	</TD><TD>".$i." Notas de crédito.     </TD><TD ALIGN='right'>$".number_format($sumanotas,2)."</TD></TR>\n";				
			$script .= "<TR align='center' class='pbty' style=' FONT-SIZE:10px; PADDING: 2px; BACKGROUND-COLOR:  #e8e8e8; FONT-WEIGHT: bold'> \n";			
			$script .= "<TD>&nbsp;</TD><TD>Monto aplicable a la comisión (-)</TD><TD ALIGN='right'>$".number_format(($sumanotas * $this->porc_notas_credito),2)."</TD></TR>\n";				
			
			$script .= "</TABLE> <BR> \n";
		}

		//--------------------------------------------------------
		// Detalle de notas de cargo
		//--------------------------------------------------------
				
		if(count($this->notas_cargo ))
		{
						
			$script .= " <SPAN ID='S2'><U> Notas de cargo </U></SPAN><BR><BR>\n ";
			$script  .= "<table border='0' WIDTH='100%' style='PADDING: 2px; BACKGROUND-COLOR: black;' cellpadding='0' cellspacing='1'>\n";
			
			$sumanotas = 0;
			for($i=0; $i<count($this->notas_cargo ); $i++)
			{
				$script .= "<TR  style=' FONT-SIZE:10px; PADDING: 2px; BACKGROUND-COLOR: white; FONT-WEIGHT: bold'> \n";
				$script .= "<TD Colspan='2'>&nbsp; Nota de cargo # ".$this->notas_cargo[$i]."</TD><TD ALIGN='right'> $".number_format($this->valor_notas_cargo[$i],2)."</TD>\n</TR>\n";
				$sumanotas = $this->valor_notas_cargo[$i];
			}
			
			$script .= "<TR  class='pbty' style=' FONT-SIZE:10px; PADDING: 2px; BACKGROUND-COLOR: white; FONT-WEIGHT: bold'> \n";
			$script .= "<TD> Total </TD><TD>".$i." Notas de cargo.     </TD><TD ALIGN='right'>$".number_format($sumanotas,2)."</TD></TR>\n";				
			$script .= "<TR align='center' class='pbty' style=' FONT-SIZE:10px; PADDING: 2px; BACKGROUND-COLOR: #e8e8e8; FONT-WEIGHT: bold'> \n";

			$script .= "<TD>&nbsp;</TD><TD>Monto aplicable a la comisión (+) </TD><TD ALIGN='right'>$".number_format(($sumanotas * $this->porc_notas_credito),2)."</TD>\n</TR>\n";				

			$script .= "</TABLE> <BR> <BR> \n";
		}		
		


		$script  .= "<TABLE border='0' WIDTH='100%' style='PADDING: 2px; BACKGROUND-COLOR: black;' cellpadding='0' cellspacing='1'>\n";
		$script .= "<TR align='right' class='pbty' style=' FONT-SIZE:12px; PADDING: 2px; BACKGROUND-COLOR: #e8e8e8; FONT-WEIGHT: bold'> \n";
		$script .= "<TD> Comisión previamente pagada  		:&nbsp; </TD><TD ALIGN='right'>$".number_format($this->comision_previamente_pagada,2)."&nbsp;</TD></TR>\n";					
		$script .= "<TR align='right' class='pbty' style=' FONT-SIZE:12px; PADDING: 2px; BACKGROUND-COLOR: #e8e8e8; FONT-WEIGHT: bold'> \n";		
		$script .= "<TD> Comisión valor actual    			:&nbsp; </TD><TD ALIGN='right'>$".number_format($this->comision_valor_actual      ,2)."&nbsp;</TD></TR>\n";		
		$script .= "<TR align='right' class='pbty' style=' FONT-SIZE:12px; PADDING: 2px; BACKGROUND-COLOR: #e8e8e8; FONT-WEIGHT: bold'> \n";		
		$script .= "<TD> Saldo comisión    					:&nbsp; </TD><TD ALIGN='right'>$".number_format($this->comision_saldo 		    ,2)."&nbsp;</TD></TR>\n";		
		$script .= "</TABLE> <BR> <BR> \n";	
		
		$preal = (100 * $this->comision_valor_actual / $this->oSaldoFactura->monto_total_factura );
		$script .= "<SPAN style=' FONT-SIZE:10px;' > Porcentaje real de la comisión respecto del monto total de la factura : %".number_format($preal,3)."</SPAN>\n";
		
		//$script  .= "</div>\n";
		
		
		
		return($script);
	}

   //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//

	function ComisionPersistente()
	{
		if( $this->comision_saldo > 0.9)
		{
		
  			//------------------------------------------------------------------------			
			//Existe algún registro maestro ?
			$sql = "SELECT ID_Comision_MST ".
				   "FROM   Comisiones_MST  ".
				   "WHERE  ID_Factura_MST = '".$this->id_factura."'	";	
			
		
			$rs = $this->db->Execute($sql);			
			$this->id_comision_mst = $rs->fields[0];

			//-----------------------------------------------------------------------------
			// Si no, lo creamos !!!
			
			if( empty($this->id_comision_mst) )
			{
			
					$sql= "INSERT INTO Comisiones_MST 
							 (  ID_Comision_MST,							 
								ID_Factura_MST, 		   
								ID_Vendedor, 			   				   
								Folio, 					   
								Monto_Comision,  		   
								Monto_Factura, 			   
								Dias_gracia,  			   
								Fecha_calculo, 			   
								Fecha_saldo, 			   
								Fecha_vencimiento,  	   
								Fecha_caducidad,  		   
								Porc_ncredito, 			   
								Porc_ncargo";
								
					if($this->descuento_obra  )  $sql.= ", Descuento_obra";					
					if($this->descuento_futuro)	 $sql.= ", Descuento_futuro";			
																
					$sql.= "	) 			   
							 VALUES  				
				 			(  NULL, 
				 			  '".$this->id_factura."', 
				 			  '".$this->id_vendedor."', 				 			  
				 			  '".$this->folio."', 
				 			  '".$this->comision_valor_actual."', 
				 			  '".$this->oSaldoFactura->monto_total_factura."',  
				 			  '".$this->dias_gracia."',  
				 			  '".date('Y-m-d')."', 
				 			  '".$this->fecha_saldo."', 
				 			  '".$this->fecha_vencimiento."', 
				 			  '".$this->fecha_caducidad."', 
				 			  '".$this->porc_notas_credito."', 
				 			  '".$this->porc_notas_cargo."' ";

					if($this->descuento_obra  )  $sql.= ", '".$this->descuento_obra."'";					
					if($this->descuento_futuro)	 $sql.= ", '".$this->descuento_futuro."'";
				 			  
				 	$sql.= "  ) 	 ";

					$this->db->Execute($sql);	
					$this->id_comision_mst = $this->db->_insertid();
			
				//-----------------------------------------------------------------------------
				// Detalle de la factura y la forma en la que se calculó			
				if($this->id_comision_mst)
				{
					for($i=0; $i < count($this->articulos_id); $i++)			
					{					
							$sql= " INSERT INTO Comisiones_DTL
										(ID_Comision_MST, 		
										 ID_Factura_MST, 		
										 ID_Producto, 			
										 Cantidad, 				
										 Precio_lista, 			
										 Precio_venta, 			
										 TipoComision, 			
										 CuotaComision ) 		
									VALUES			
										('".$this->id_comision_mst."', 
										 '".$this->id_factura."', 
										 '".$this->articulos_id[$i]."',
										 '".$this->articulos_cantidad[$i]."', 
										 '".$this->articulos_precio_lista[$i]."', 
										 '".$this->articulos_precio_venta[$i]."', 
										 '".$this->art_tipo_comision[$i]."', 
										 '".$this->art_valor_comision[$i]."' ) ";

							$rs = $this->db->Execute($sql);	
					}
				}
				
			}
			else
			{
					$sql = " UPDATE Comisiones_MST SET Monto_Comision = '".$this->comision_valor_actual."' WHERE ID_Comision_MST='".$this->id_comision_mst."' ";
					$this->db->Execute($sql);
			}
  			//------------------------------------------------------------------------			
		
		}
	
	
	}

}


function new_file_size($dir) 
{ 
   $du = popen("/usr/bin/du -sh $dir", "r"); 
   $res = fgets($du, 256); 
   pclose($du); 
   $res = explode(" ", $res); 
   
   return $res[0]; 
}





?>