<?

require_once($class_path."lib_nuevo_credito.php");
require_once($class_path."lib_cierre.php");


class TAnticipoCapitalNuevo extends TNuevoCredito 
{

  var $db;

  var $id_credito;

  var $num_cliente;
  var $fecha_apertura;
  var $fecha_inicio;
  var $capital;
  var $id_producto;
  var $plazo;
  var $id_sucursal;
  var $fecha_para_primer_vencimiento;
  
  var $fecha_ultimo_anticipo_aplicado;
  
  
  var $id_pago 	  = 0;
  var $pago_monto = 0;
  var $pago_fecha = 0;
  var $lista_pagos = '';

  var $vencimiento;  
  var $error = 0;
  
  var $tabla_amortizacion = array();
  var $cuotas_extra = array();

  var $notas_cargo = array();
  var $id_notas_cargo = array();

  var $pcnt_iva_segv = 0;

//========================================================================================================================
//
//========================================================================================================================

function __construct($id_credito, $id_abono=0, $otro=0)
{
  
        if(empty($id_credito)) return;
        
	$this->id_credito = $id_credito;
        
	$db = ADONewConnection(SERVIDOR);  # create a connection
	$db->Connect(IP,USER,PASSWORD,NUCLEO);
	
	$this->db = $db;

                $sql = "SELECT  clientes.Num_cliente,

				CASE clientes.Regimen 
				WHEN 'PM'   THEN clientes_datos_pmoral.Razon_social  COLLATE 'latin1_swedish_ci'
				
				WHEN 'PFAE' THEN CONCAT(clientes_datos_pmoral.Ap_paterno_pfae, ' ',
							clientes_datos_pmoral.Ap_materno_pfae,' ',
							clientes_datos_pmoral.Nombre_pfae, ' ',
							clientes_datos_pmoral.NombreI_pfae) COLLATE 'latin1_swedish_ci'

				WHEN 'PF'  THEN CONCAT( clientes_datos.Ap_paterno,' ',
							clientes_datos.Ap_materno,' ',
							clientes_datos.Nombre,' ',
							clientes_datos.NombreI )   COLLATE 'latin1_swedish_ci'
				END AS Nombre
                              
                        FROM  fact_cliente 
                        
                        INNER JOIN clientes
                                ON clientes.num_cliente = fact_cliente.num_cliente
                       

			LEFT  JOIN clientes_datos	 ON clientes.num_cliente 	      = clientes_datos.num_cliente
			LEFT  JOIN clientes_datos_pmoral ON clientes_datos_pmoral.num_cliente = clientes.num_cliente


                   
                  WHERE   fact_cliente.id_factura ='".$id_credito."' ";

	 $rs=$db->Execute($sql);


	$this->nombre_cliente = $rs->fields['Nombre'];
	$this->num_cliente = $rs->fields['Num_cliente'];


	$sql = "SELECT fact_cliente.num_cliente,
		       fact_cliente.fecha_exp,       
		       fact_cliente.Fecha_Inicio,
		       fact_cliente.Fecha_Vencimiento,
		       fact_cliente.Capital,
		       fact_cliente.ID_Producto,
		       fact_cliente.plazo,
		       fact_cliente.vencimiento,
		       cargos.Fecha_vencimiento AS fecha_para_primer_vencimiento,
		       fact_cliente.TasaNominal,
		       fact_cliente.IVA_Interes,
		       fact_cliente.IVA_Comision,
		       fact_cliente.Renta,
		       fact_cliente.Metodo,
		       fact_cliente.RedondeoCifras,
		       fact_cliente.Nombre_Producto,
		       #fact_cliente.Esquema_Pagos,
		       fact_cliente.Periodos_con_Gracia,
		       fact_cliente.ID_Sucursal,
		       fact_cliente.ID_Tipocredito,
		       fact_cliente.num_compra,
       		       sucursales.Nombre AS Sucursal,
       		       sucursales.IVA_General,
       		       fact_cliente.Renta


		FROM fact_cliente
		INNER JOIN cargos
			ON cargos.Num_compra = fact_cliente.num_compra
		       AND cargos.ID_Cargo = 1 

		LEFT JOIN sucursales
		       ON sucursales.ID_Sucursal =  fact_cliente.ID_Sucursal   


		WHERE fact_cliente.id_factura = '".$this->id_credito."' ";
	 
	$rs=$db->Execute($sql);
	
	
	$num_cliente		= $rs->fields['num_cliente'];
	$fecha_apertura		= $rs->fields['fecha_exp'];
	$fecha_inicio		= $rs->fields['Fecha_Inicio'];
	$capital			= $rs->fields['Capital'];
	$id_producto		= $rs->fields['ID_Producto'];
	$plazo				= $rs->fields['plazo'];
	$id_sucursal		= $rs->fields['ID_Sucursal'];	
 
	$numcompra			= $rs->fields['num_compra'];
	
	
	$periodo 			= $rs->fields['vencimiento'];
	$this->numcompra	= $numcompra;
	

	$fecha_para_primer_vencimiento = $rs->fields['fecha_para_primer_vencimiento']; 

 	$this->fecha_inicio	= $rs->fields['Fecha_Inicio'];



	$sql = "SELECT cargos.Fecha_vencimiento

		 FROM  cargos

		 WHERE cargos.SubTipo = 'Capital' 
		   and cargos.Num_compra = '".$this->numcompra."'
		 ORDER BY cargos.Fecha_vencimiento  DESC ";

	$rs = $this->db->Execute($sql);	

	$this->fecha_ultimo_anticipo_aplicado = $rs->fields['Fecha_vencimiento'];



	parent::__construct($num_cliente, $fecha_apertura, $fecha_inicio, $capital, $id_producto, $plazo, $db, $id_sucursal, $fecha_para_primer_vencimiento );



	$this->periodo = $periodo;



	$sql = "SELECT cargos.Fecha_vencimiento
		FROM   cargos
		WHERE  cargos.num_compra = '".$this->numcompra."' and
		       cargos.ID_Cargo = 1 ";


	 $rs = $this->db->Execute($sql);
	 $this->fecha_primer_vencimiento = $rs->fields['Fecha_vencimiento'];











        switch ($this->periodo )
        {
          // Anual
          case 'Anios' : $tipo_plazo = "años.";
                         $tipo_plazox = "anual.";
                         $frecuencia = 1;
                   break;
          // Semestral         
          case 'Semestres' : $tipo_plazo = "semestres.";
                           $tipo_plazox = "semestral";
                           $frecuencia = 2;
                   break;
         // Trimestral          
          case 'Trimestres' : $tipo_plazo = "trimestres.";
                           $tipo_plazox = "trimestral";   
                           $frecuencia = 3;
                   break;
         // Bimestral          
          case 'Bimestres' : $tipo_plazo = "bimestres.";
                           $tipo_plazox = "bimestral";   
                           $frecuencia = 4;
                   break; 
          // Mensual
          case 'Meses' : $tipo_plazo = "meses.";
                           $tipo_plazox = "mensual";   
                           $frecuencia = 5;
                   break;
          //Quincenal 
          case 'Quincenas' : $tipo_plazo = "quincenas.";
                           $tipo_plazox = "quincenal";   
                           $frecuencia = 6;
                   break;
                   
          //Semanal                   
          case 'Semanas' : $tipo_plazo = "semanas.";
                           $tipo_plazox = "semanal";    
                           $frecuencia = 7;
                   break;
           //Diaria                   
          
          case 'Dias' : $tipo_plazo = "dias.";
                           $tipo_plazox = "diaria";     
                           $frecuencia = 8;
                   break;
          
          //Catorcenal                    
          case 'Catorcenas' : $tipo_plazo = "catorcenas.";
                           $tipo_plazox = "catorcenal";     
                           $frecuencia = 9;
                   break;
        };

        
        switch ($frecuencia )
        {
		  case 1 : $dias_periodo = 360; break;
		  case 2 : $dias_periodo = 180; break;
		  case 3 : $dias_periodo = 90; break;
		  case 4 : $dias_periodo = 60; break;
		  case 5 : $dias_periodo = 30; break;
		  case 6 : $dias_periodo = 15; break;
		  case 7 : $dias_periodo = 7; break;        
		  case 8 : $dias_periodo = 1; break;     
		  case 9 : $dias_periodo = 14; break; 
        };


        $this->frecuencia   = $frecuencia;
        $this->tipo_plazox  = $tipo_plazox;             
        $this->dias_periodo = $dias_periodo;




	if($id_abono > 0)
	{
	     $this->id_pago = $id_abono;

	     $sql= "SELECT  pagos.Monto,
			    pagos.Fecha
		     FROM   pagos
		     WHERE  pagos.ID_Pago = '".$this->id_pago."' "; 		

	      $rs=$this->db->Execute($sql);

	     $this->pago_monto = $rs->fields['Monto'];
	     $this->pago_fecha = $rs->fields['Fecha'];	
	     
	     $this->monto_anticipo = $this->pago_monto;
	     
	     
	     $fecha_anticipo = ffecha($this->pago_fecha);
	     
	     
	     $this->pre_anticipo_capital($fecha_anticipo, $this->pago_monto);
	     $this->cotiza_anticipo();
	}

 	
}

//========================================================================================================================
//
//========================================================================================================================

function pre_anticipo_capital($fecha_anticipo, $monto_anticipo_val)
{

  $this->error = 0;
  
  list($dd,$mm,$yyyy) = explode("/",$fecha_anticipo);
  
  $dd   *= 1;
  $mm   *= 1;
  $yyyy *= 1;
    	


  if( checkdate($mm,$dd,$yyyy) )
  {
	$this->fecha_anticipo = date("d/m/Y", mktime(0,0,0,$mm,$dd,$yyyy));  
	     $gfecha_anticipo = date("Y-m-d", mktime(0,0,0,$mm,$dd,$yyyy)); 	
	
	$this->gfecha_anticipo = $gfecha_anticipo;
	
//debug("($dd,$mm,$yyyy)");
//die();	  

	if( $gfecha_anticipo < $this->fecha_inicio )
	{

		$this->error++;
		$this->fecha_anticipo ="";	
				
		return;
	}





	//debug($fecha_primer_vencimiento);	 
/*	 
	if( $gfecha_anticipo < $this->fecha_primer_vencimiento )
	{
		debug("$gfecha_anticipo <= ".$this->fecha_primer_vencimiento."");

		$this->error++;
		$this->fecha_anticipo ="";	
				
		return;
	}	 
*/	


	
  }
  else
  {
  	$this->error++;
 	$this->fecha_anticipo =""; 
 	
 	return;
  }




   if($this->fecha_ultimo_anticipo_aplicado >= $gfecha_anticipo)
   {
   
  	$this->error++;

 	error_msg("Error : No es posible aplicar anticipos con fecha anterior o igual al ".ffecha($this->fecha_ultimo_anticipo_aplicado)."");
 	$this->fecha_anticipo ="";    

 	return;
   
   }
 
  
  if($monto_anticipo_val <=0 )
  {
  	$this->error++;
  	$this->monto_anticipo_val = 0;
  	return;
  }
  else
  {
  	$this->monto_anticipo_val = abs($monto_anticipo_val * 1); 
  }
  


}

//========================================================================================================================
//
//========================================================================================================================

function cotiza_anticipo()
{
	if($this->error>0){
		return;  
	}

	$i = 0;

	//debug($this->periodos_con_gracia);
	$cuota_extra = array();
	$this->tabla_amortizacion = array();
	
	$this->tabla_amortizacion[$i]['FECHA']      	   	= ffecha($this->fecha_inicio);
	$this->tabla_amortizacion[$i]['COMISION']   	   	= 0;
	$this->tabla_amortizacion[$i]['ABONO_IVA_COMISION']	= 0;
	$this->tabla_amortizacion[$i]['ABONO_INTERES']     	= 0;
	$this->tabla_amortizacion[$i]['ABONO_IVA_INTERES'] 	= 0;
	$this->tabla_amortizacion[$i]['ABONO_SEGV']        	= 0;
	$this->tabla_amortizacion[$i]['ABONO_IVA_SEGV'] 	= 0;
	$this->tabla_amortizacion[$i]['ABONO_SEGD']     	= 0;
	$this->tabla_amortizacion[$i]['ABONO_IVA_SEGD'] 	= 0;
	$this->tabla_amortizacion[$i]['ABONO_SEGB']     	= 0;
	$this->tabla_amortizacion[$i]['ABONO_IVA_SEGB'] 	= 0;
	$this->tabla_amortizacion[$i]['ABONO_CAPITAL']  	= 0;
	$this->tabla_amortizacion[$i]['ABONO_IVA']      	= 0;
	$this->tabla_amortizacion[$i]['RENTA']          	= 0;
    $this->tabla_amortizacion[$i]['CUOTA_EXTRA']        = 0;
	$this->tabla_amortizacion[$i]['SALDO_CAPITAL'] 		= $this->capital;
								 

	$this->tot_abono_capital        = 0;
		
	$this->tot_abono_interes	= 0;
	$this->tot_abono_iva_interes	= 0;

	$this->tot_abono_comision	= 0;	
	$this->tot_abono_iva_comision	= 0;

	$this->tot_abono_segv   	= 0;
	$this->tot_abono_iva_segv	= 0;

	$this->tot_abono_segd		= 0;
	$this->tot_abono_iva_segd	= 0;

	$this->tot_abono_segb		= 0;
	$this->tot_abono_iva_segb	= 0;

    $this->tot_cuota_extra      = 0;

	$this->tot_abonos		= 0;


	$sql = "SELECT SUM(cargos.Comision)
		    FROM cargos

		    WHERE cargos.Num_compra = '".$this->numcompra."'
            AND cargos.Activo = 'Si'
		    AND cargos.ID_Concepto = -3 ";

	$rs=$this->db->Execute($sql);
	
	$this->monto_total_comision = $rs->fields[0];

	// Notas de cargo
   // ----------------------------------------------------
	$sql= "	SELECT 	ID_Cargo,
					ID_Concepto,
					Fecha_vencimiento,
					Monto,
					Capital,
					Interes,
					IVA_Interes,
					Comision,
					IVA_Comision,
					Moratorio,
					IVA_Moratorio,
					Otros,
					IVA_Otros,
					SegV,
					IVA_SegV,
					SegD,
					IVA_SegD,
					SegB,
					IVA_SegB,
					IVA,
					SubTipo,
					Concepto,
					Observaciones
	     	FROM cargos
	     	WHERE cargos.Num_compra  = '".($this->numcompra)."'
	     	AND cargos.Activo      = 'Si'
	     	AND cargos.ID_Concepto = -2

	     	ORDER BY  cargos.Fecha_vencimiento ";

	$rs=$this->db->Execute($sql);

	$this->cuotas_extra = array();
	if($rs->_numOfRows)
   	while(! $rs->EOF) {
   		$i = $rs->fields['ID_Cargo'];
   		$this->notas_cargo[$i]['ID_Cargo'] = $rs->fields['ID_Cargo'];
   		$this->notas_cargo[$i]['ID_Concepto'] = $rs->fields['ID_Concepto'];
   		$this->notas_cargo[$i]['FECHA'] = ffecha($rs->fields['Fecha_vencimiento']);
		$this->notas_cargo[$i]['RENTA'] = $rs->fields['Monto'];
		$this->notas_cargo[$i]['ABONO_CAPITAL'] = $rs->fields['Capital'];
		$this->notas_cargo[$i]['ABONO_INTERES'] = $rs->fields['Interes'];
		$this->notas_cargo[$i]['ABONO_IVA_INTERES'] = $rs->fields['IVA_Interes'];
		$this->notas_cargo[$i]['COMISION'] = $rs->fields['Comision'];
		$this->notas_cargo[$i]['ABONO_IVA_COMISION'] = $rs->fields['IVA_Comision'];
		$this->notas_cargo[$i]['moratorio'] = $rs->fields['Moratorio'];
		$this->notas_cargo[$i]['iva_moratorio'] = $rs->fields['IVA_Moratorio'];
		$this->notas_cargo[$i]['otros'] = $rs->fields['Otros'];
		$this->notas_cargo[$i]['iva_otros'] = $rs->fields['IVA_Otros'];
		$this->notas_cargo[$i]['ABONO_SEGV'] = $rs->fields['SegV'];
		$this->notas_cargo[$i]['ABONO_IVA_SEGV'] = $rs->fields['IVA_SegV'];
		$this->notas_cargo[$i]['ABONO_SEGD'] = $rs->fields['SegD'];
		$this->notas_cargo[$i]['ABONO_IVA_SEGD'] = $rs->fields['IVA_SegD'];
		$this->notas_cargo[$i]['ABONO_SEGB'] = $rs->fields['SegB'];
		$this->notas_cargo[$i]['ABONO_IVA_SEGB'] = $rs->fields['IVA_SegB'];
		$this->notas_cargo[$i]['ABONO_IVA'] = $rs->fields['IVA'];
		$this->notas_cargo[$i]['SUBTIPO'] = $rs->fields['SubTipo'];
		$this->notas_cargo[$i]['concepto'] = $rs->fields['Concepto'];
		$this->notas_cargo[$i]['observaciones'] = $rs->fields['Observaciones'];
		$this->notas_cargo[$i]['CUOTA_EXTRA'] = $rs->fields['Cuota_Extra'];

   		$this->id_notas_cargo[] = $rs->fields['ID_Cargo'];
		$rs->MoveNext();
	}
   //-----------------------------------------------------

	$sql = "SELECT cargos.ID_Cargo,
                   cargos.Monto,
                   cargos.Fecha_vencimiento,
                   cargos.Capital,
                   cargos.Comision,
                   cargos.IVA_Comision,
                   cargos.Interes,
                   cargos.IVA_Interes,
                   cargos.SegV,
                   cargos.IVA_SegV,
                   cargos.SegD,
                   cargos.IVA_SegD,
                   cargos.SegB,
                   cargos.IVA_SegB,
                   cargos.SubTipo,
                   cargos.Cuota_Extra
		    FROM cargos

		    WHERE cargos.Num_compra = '".$this->numcompra."'
		    AND cargos.Activo = 'Si'
		    AND cargos.ID_Concepto = -3

		    ORDER BY cargos.ID_Cargo ";
		
	//debug($sql);

	$rs=$this->db->Execute($sql);
    $rs_ce = $this->db->Execute($sql);
	$total_abonos_capital = 0;

	
	//------------------------------------------------------------------	
	// Tabla Amortización Estado Actual
	//------------------------------------------------------------------

    while(!$rs_ce->EOF)
    {
        if($rs_ce->fields['Cuota_Extra'] > 0)
        $this->cuotas_extra[$rs_ce->fields['ID_Cargo']+1] = $rs_ce->fields['Cuota_Extra'];
        $rs_ce->MoveNext();
    }

	if($rs->_numOfRows)

	    while(!$rs->EOF)
	    {
		    if((!empty($this->fecha_anticipo)) and ( fdifdias($rs->fields['Fecha_vencimiento'],gfecha($this->fecha_anticipo)) < 0))
		    {
			    break;
	    	}
		
		    $i = $rs->fields['ID_Cargo'];
		
            $total_abonos_capital += $rs->fields['Capital'];

            $this->tabla_amortizacion[$i]['ID_Cargo']		=  $rs->fields['ID_Cargo'];
            $this->tabla_amortizacion[$i]['FECHA']      	   	=  ffecha($rs->fields['Fecha_vencimiento']);
            $this->tabla_amortizacion[$i]['COMISION']   	   	=  $rs->fields['Comision']*1;
            $this->tabla_amortizacion[$i]['ABONO_IVA_COMISION']	=  $rs->fields['IVA_Comision']*1;


            $this->tabla_amortizacion[$i]['ABONO_INTERES']     	=  $rs->fields['Interes']*1;
            $this->tabla_amortizacion[$i]['ABONO_IVA_INTERES'] 	=  $rs->fields['IVA_Interes']*1;


            $this->tabla_amortizacion[$i]['ABONO_SEGV']        	=  $rs->fields['SegV']*1;
            $this->tabla_amortizacion[$i]['ABONO_IVA_SEGV'] 	=  $rs->fields['IVA_SegV']*1;

            if(($rs->fields['SegV']*1)>0 && $rs->fields['IVA_SegV'] > 0)
            {
                $this->pcnt_iva_segv = $rs->fields['IVA_SegV']*1/$rs->fields['SegV']*1;
            }
		

            $this->tabla_amortizacion[$i]['ABONO_SEGD']     	=  $rs->fields['SegD']*1;
            $this->tabla_amortizacion[$i]['ABONO_IVA_SEGD'] 	=  $rs->fields['IVA_SegD']*1;

            $this->tabla_amortizacion[$i]['ABONO_SEGB']     	=  $rs->fields['SegB']*1;
            $this->tabla_amortizacion[$i]['ABONO_IVA_SEGB'] 	=  $rs->fields['IVA_SegB']*1;

            $this->tabla_amortizacion[$i]['ABONO_CAPITAL']  	= $rs->fields['Capital']*1;

            $this->tabla_amortizacion[$i]['ABONO_IVA']      	= $rs->fields['IVA_Comision']+
                                                                  $rs->fields['IVA_Interes']+
                                                                  $rs->fields['IVA_SegV']+
                                                                  $rs->fields['IVA_SegD']+
                                                                  $rs->fields['IVA_SegB'];

            $this->tabla_amortizacion[$i]['RENTA']          	= $rs->fields['Monto']*1;

            $this->tabla_amortizacion[$i]['CUOTA_EXTRA']          	= $rs->fields['Cuota_Extra']*1;

            $this->tabla_amortizacion[$i]['SALDO_CAPITAL'] 		= $this->capital - $total_abonos_capital;

            $this->tabla_amortizacion[$i]['SUBTIPO']          	= $rs->fields['SubTipo'];



            $this->tot_abono_capital        += $rs->fields['Capital']*1;

            $this->tot_abono_interes	+= $rs->fields['Interes']*1;
            $this->tot_abono_iva_interes	+= $rs->fields['IVA_Interes']*1;

            $this->tot_abono_comision	+= $rs->fields['Comision']*1;
            $this->tot_abono_iva_comision	+= $rs->fields['IVA_Comision']*1;

            $this->tot_abono_segv   	+= $rs->fields['SegV']*1;
            $this->tot_abono_iva_segv	+= $rs->fields['IVA_SegV']*1;

            $this->tot_abono_segd		+= $rs->fields['SegD']*1;
            $this->tot_abono_iva_segd	+= $rs->fields['IVA_SegD']*1;

            $this->tot_abono_segb		+= $rs->fields['SegB']*1;
            $this->tot_abono_iva_segb	+= $rs->fields['IVA_SegB']*1;

            $this->tot_abonos		+= $rs->fields['Monto']*1;
            $this->tot_cuota_extra		+= $rs->fields['Cuota_Extra']*1;

            $fecha_previa_anticipo = $rs->fields['Fecha_vencimiento'];
            $ndx_previo_anticipo = $i;

	        $rs->MoveNext();
	    }

        if(empty($this->fecha_anticipo) or ($this->monto_anticipo_val<=0)) {
            return;
        }

        if(empty($fecha_previa_anticipo))
        {
            $fecha_previa_anticipo =  $this->fecha_inicio;
            $ndx_previo_anticipo   = 0;
        }
	  
	    $fecha_anterior = ffecha($fecha_previa_anticipo);
	
	  
	    if($this->monto_anticipo_val > $this->tabla_amortizacion[$i]['SALDO_CAPITAL'])
	    {
            $this->error++;

            error_msg("Error : El monto de anticipo no puede ser mayor al saldo de capital.");
            // debug($this->tabla_amortizacion[$i]['FECHA'] );
            // debug($this->tabla_amortizacion[$i]['SALDO_CAPITAL'] );

            $this->monto_anticipo_val = $this->tabla_amortizacion[$i]['SALDO_CAPITAL'] ;

		    //return;
	    }
	   
	   $ANTICIPO_APLICADO = false;

	   $tasa_periodo = ($this->dias_periodo * (($this->tasa_nominal)/30))/100;


	   $tasa_diaria  = ((($this->tasa_nominal)/30)/100);
	   
	   $sig_couta      = ffecha($rs->fields['Fecha_vencimiento']);
	   $dia_espacifico = fdia($rs->fields['Fecha_vencimiento']);

	   $count= count($this->tabla_amortizacion);

	   while($this->tabla_amortizacion[$i]['SALDO_CAPITAL'] > 0 || ($this->tot_abono_comision < $this->monto_total_comision))
	   {
	   
            $saldo_capital = $this->tabla_amortizacion[$i]['SALDO_CAPITAL'];
	   	    ++$i;
	   	if($ANTICIPO_APLICADO)
	   	{
				if($this->metodo == 'Saldos Insolutos')
				{
						if($this->tabla_amortizacion[($i-1)]['ANTICIPO'])
						{
                            $dias1 = ffdias($fecha_previa_anticipo, gfecha($this->fecha_anticipo));

                            $abono_interes = ($tasa_diaria * $dias1 * $this->tabla_amortizacion[$ndx_previo_anticipo]['SALDO_CAPITAL']);

                            $dias2 = ffdias(gfecha($this->fecha_anticipo), gfecha($sig_couta));

                            $dif_dias_cuota = ffdias($fecha_previa_anticipo, gfecha($sig_couta));

                            // Resta un día a $dias2 si son 31 para que solo se calcule a 30 días
                            if($dif_dias_cuota == 31) {
                            	$dias2--;
                            }

                            if ($dias2 < 0) {
                                $dias2 = 0;
                            }

                            $abono_interes += ($tasa_diaria * $dias2 * $this->tabla_amortizacion[($i - 1)]['SALDO_CAPITAL']);
						}
						else
						{
                            $abono_interes      = trunc(($saldo_capital * $tasa_periodo),2);
						}
				}
				else
				{
                    $abono_interes	    = trunc(($tasa_periodo    * $this->capital),2);
				}

                $abono_renta = $this->renta;
				if($this->iva_interes > 0)
				{
                    $abono_interes_neto = round(($abono_interes * (1 + $this->iva_interes )),2);

                    $abono_interes      = trunc($abono_interes,2);
					$abono_iva_interes  = $abono_interes_neto - $abono_interes;
				}
				else
				{
                    $abono_interes      = round($abono_interes,2);
					$abono_iva_interes  = 0;				
				}

				/*
				echo "<div style='text-align:left'><pre>";
				print_r($this->tabla_amortizacion);
				echo "</pre></div>";
				*/
				$abono_comision = $this->monto_comision_diferida;
				$abono_iva_comision = ($abono_comision  * $this->iva_comision ) ;

				$abono_segv     =  $this->cuota_segv;
				$abono_iva_segv = ($this->cuota_segv * $this->pcnt_iva_segv ) ; 

				$abono_segd     =  $this->cuota_segd;
				$abono_iva_segd = ($this->cuota_segd * $this->iva_comision ) ;

				$abono_segb     =  $this->cuota_segb;
				$abono_iva_segb = ($this->cuota_segb * $this->iva_comision ) ;

                $abono_cuota_extra = 0;
                if(isset($this->cuotas_extra[$i])){
                    $abono_cuota_extra = $this->cuotas_extra[$i];
                    $abono_renta = $this->renta + $abono_cuota_extra;
                }
				
				
				$abono_iva	= $abono_iva_interes +
						  $abono_iva_comision +
						  $abono_iva_segv +
						  $abono_iva_segd +
						  $abono_iva_segb;


				if($this->periodos_con_gracia == 'Si')
				{
					if($i == ($this->plazo+1))
					{									
						$abono_capital = $this->tabla_amortizacion[($i-1)]['SALDO_CAPITAL'];
					}
					else
					{
						$abono_capital = 0;
					}

                    $abono_renta  = ($abono_capital + $abono_comision + $abono_interes + $abono_iva + $abono_segv + $abono_segd + $abono_segb);
				}
				else
				{
					$abono_capital = $this->renta  - ($abono_comision + $abono_interes + $abono_iva + $abono_segv + $abono_segd + $abono_segb)+$abono_cuota_extra;
				}
					



                        $total_abonos_capital +=  $abono_capital; 


			$this->tot_abono_capital        += $abono_capital;

			$this->tot_abono_interes	+= $abono_interes;
			$this->tot_abono_iva_interes	+= $abono_iva_interes;

			$this->tot_abono_comision	+= $abono_comision;
			$this->tot_abono_iva_comision	+= $abono_iva_comision;

			$this->tot_abono_segv   	+= $abono_segv;     
			$this->tot_abono_iva_segv	+= $abono_iva_segv; 

			$this->tot_abono_segd		+= $abono_segd;     
			$this->tot_abono_iva_segd	+= $abono_iva_segd; 

			$this->tot_abono_segb		+= $abono_segb;     
			$this->tot_abono_iva_segb	+= $abono_iva_segb;

            $this->tot_cuota_extra += $abono_cuota_extra;

/**/
			if(!empty($sig_couta ))
			{
				$this->tabla_amortizacion[$i]['FECHA']= $sig_couta;
				$sig_couta ="";
												    
			}
			else
			{
				$fecha_anterior = $this->tabla_amortizacion[($i-1)]['FECHA'];
				
				//debug("fecha_anterior : ".$fecha_anterior);
				
	   			$dia_especifico = fdia(gfecha($fecha_anterior));	
	   			
				$this->tabla_amortizacion[$i]['FECHA']=  fechavencimiento($fecha_anterior, $this->frecuencia, $dia_especifico);
				
				//debug("fecha_nueva : ".$this->tabla_amortizacion[$i]['FECHA']." =  fechavencimiento($fecha_anterior, ".$this->frecuencia.", $dia_espacifico);");
				
				//$fecha_anterior = $this->tabla_amortizacion[$i]['FECHA'];
			}

			$this->tabla_amortizacion[$i]['COMISION']   	   	=  $abono_comision;
			$this->tabla_amortizacion[$i]['ABONO_IVA_COMISION']	=  $abono_iva_comision;				


			$this->tabla_amortizacion[$i]['ABONO_INTERES']     	=  $abono_interes;		
			$this->tabla_amortizacion[$i]['ABONO_IVA_INTERES'] 	=  $abono_iva_interes;


			$this->tabla_amortizacion[$i]['ABONO_SEGV']        	=  $abono_segv;			
			$this->tabla_amortizacion[$i]['ABONO_IVA_SEGV'] 	=  $abono_iva_segv;

			$this->tabla_amortizacion[$i]['ABONO_SEGD']     	=  $abono_segd;
			$this->tabla_amortizacion[$i]['ABONO_IVA_SEGD'] 	=  $abono_iva_segd;

			$this->tabla_amortizacion[$i]['ABONO_SEGB']     	=  $abono_segb;
			$this->tabla_amortizacion[$i]['ABONO_IVA_SEGB'] 	=  $abono_iva_segb;

			$this->tabla_amortizacion[$i]['ABONO_CAPITAL']  	=  $abono_capital;

			$this->tabla_amortizacion[$i]['ABONO_IVA']      	=  $abono_iva;

            if(isset($this->cuotas_extra[$i])){
                $this->tabla_amortizacion[$i]['CUOTA_EXTRA'] = $this->cuotas_extra[$i]*1;
            }
            else{
                $this->tabla_amortizacion[$i]['CUOTA_EXTRA'] = 0;
            }

			$this->tabla_amortizacion[$i]['RENTA']          	= $abono_renta;

            //$this->tabla_amortizacion[$i]['CUOTA_EXTRA'] = $cuota_extra[$i];
			
			$this->tabla_amortizacion[$i]['SALDO_CAPITAL'] 		= $this->capital - $total_abonos_capital;	
			
			$this->tabla_amortizacion[$i]['SUBTIPO'] = 'General';
			
			if($this->tabla_amortizacion[$i]['SALDO_CAPITAL'] < 0 )
			{
			   $this->tabla_amortizacion[$i]['SALDO_CAPITAL'] = 0;
			   
			   //debug("$total_abonos_capital -= ".$this->tabla_amortizacion[$i]['ABONO_CAPITAL']);
			   $total_abonos_capital = $total_abonos_capital - $this->tabla_amortizacion[$i]['ABONO_CAPITAL'];
			   
			   
			   
			   $abono_capital = $this->tabla_amortizacion[($i-1)]['SALDO_CAPITAL'];
			   $this->tabla_amortizacion[$i]['ABONO_CAPITAL']    =  $abono_capital;
			   

			   $total_abonos_capital += $this->tabla_amortizacion[$i]['ABONO_CAPITAL'];
			   $this->tot_abono_capital = $total_abonos_capital;


                if($abono_cuota_extra>0)
                    $abono_cuota_extra = 0;

			   $this->tabla_amortizacion[$i]['RENTA']=( $abono_comision	+
			   					    $abono_iva_comision	+
			   					    $abono_interes	+	
			   					    $abono_iva_interes	+			   
			   					    $abono_segv		+		
			   					    $abono_iva_segv	+			   
			   					    $abono_segd		+
								    $abono_iva_segd	+
								    $abono_segb		+
	   							    $abono_iva_segb	+
	   							    $abono_capital +
                                    $abono_cuota_extra);
			}
            $this->tot_abonos += $this->tabla_amortizacion[$i]['RENTA'];
	   	}
	   	else						   
	   	{
			$this->tot_abono_capital        += $this->monto_anticipo_val;

			$this->tot_abonos		+= $this->monto_anticipo_val;
	   	
			$total_abonos_capital +=  $this->monto_anticipo_val; 

			$this->tabla_amortizacion[$i]['ANTICIPO']      	   	=  true;

			$this->tabla_amortizacion[$i]['FECHA']      	   	=  $this->fecha_anticipo;

			$this->tabla_amortizacion[$i]['COMISION']   	   	=  0;
			$this->tabla_amortizacion[$i]['ABONO_IVA_COMISION']	=  0;				


			$this->tabla_amortizacion[$i]['ABONO_INTERES']     	=  0;		
			$this->tabla_amortizacion[$i]['ABONO_IVA_INTERES'] 	=  0;


			$this->tabla_amortizacion[$i]['ABONO_SEGV']        	=  0;			
			$this->tabla_amortizacion[$i]['ABONO_IVA_SEGV'] 	=  0;

			$this->tabla_amortizacion[$i]['ABONO_SEGD']     	=  0;
			$this->tabla_amortizacion[$i]['ABONO_IVA_SEGD'] 	=  0;

			$this->tabla_amortizacion[$i]['ABONO_SEGB']     	=  0;
			$this->tabla_amortizacion[$i]['ABONO_IVA_SEGB'] 	=  0;

			$this->tabla_amortizacion[$i]['ABONO_CAPITAL']  	=  $this->monto_anticipo_val;

			$this->tabla_amortizacion[$i]['ABONO_IVA']      	=  0;

			$this->tabla_amortizacion[$i]['RENTA']          	= $this->monto_anticipo_val*1;

            $this->tabla_amortizacion[$i]['CUOTA_EXTRA']          	= 0;

			$this->tabla_amortizacion[$i]['SALDO_CAPITAL'] 		= $this->capital - $total_abonos_capital;
			
						
			$this->tabla_amortizacion[$i]['SUBTIPO'] = 'Capital';
			
			$ANTICIPO_APLICADO = true;

		} // Registra los anticipos a capital
	   
		$this->tabla_amortizacion[$i]['ID_Cargo']  = $i;
		$this->tabla_amortizacion[$i]['Modficado'] = 1;
	   } // Fin while saldo capital > 0
	   
	   
//	   if($this->tot_abono_capital > $this->capital)
	   if(($this->capital - $this->tot_abono_capital)>0.01)
	   {
	   	++$this->error;
	   	
	   	error_msg("Error : Monto de anticipo a capital inválido.");
	   	
	   	
	   	if($_SESSION['ID_USR'] == 13){debug($this->tot_abono_capital." > ".$this->capital);}
	   	
	   	return;
	   
	   }


	  //debug("if(".$ANTICIPO_APLICADO." and (".$this->monto_total_comision." >0 ))");

/*	$sql ="	SELECT SUM(cargos.Comision)

		FROM cargos

		WHERE cargos.Num_compra = '".$this->numcompra."'
		  AND cargos.Activo = 'Si'
		  AND cargos.ID_Concepto = -3
		 # AND cargos.ID_Cargo > 0
		ORDER BY cargos.ID_Cargo ";

	$_rs=$this->db->Execute($sql);
	$_monto_total_comision  = $_rs->fields[0];
*/	
	//debug($_monto_total_comision);
	/*
	echo "<div style='text-align:left'><pre>";
	print_r($this->tabla_amortizacion);
	echo "</pre></div>";
	*/

    if($ANTICIPO_APLICADO and ($this->monto_total_comision >0 )) {
        $this->ajusta_comision();
    }

    $tmp_amortizacion = array();
	// Verifica si hay notas de cargo y las ordena
	if(count($this->notas_cargo)) {
		foreach ($this->notas_cargo as $id_cargo => $cargos) {
			$pos = '';
			$row_cargo = $cargos;
			foreach ($this->tabla_amortizacion as $i => $row) {
				if(gfecha($row['FECHA']) >= gfecha($cargos['FECHA']) && $pos == '') {
					$pos = $i;
				}
			}

			// Agrega las notas de cargo
			$a = 0;
			$entra = false;
			foreach ($this->tabla_amortizacion as $i => $row) {
				if($i == $pos && !$entra) {
					$row_cargo['SALDO_CAPITAL'] = $this->tabla_amortizacion[$i-1]['SALDO_CAPITAL'];
					$tmp_amortizacion[] = $row_cargo;
					$entra = true;
				} 

				$tmp_amortizacion[] = $this->tabla_amortizacion[$a];
				$a++;
			}

			// Suma a totales de notas de cargo
			$this->tot_abonos		+= $cargos['RENTA'];
			$this->tot_abono_capital        += $cargos['ABONO_CAPITAL'];

			$this->tot_abono_interes	+= $cargos['ABONO_INTERES'];
			$this->tot_abono_iva_interes	+= $cargos['ABONO_IVA_INTERES'];

			$this->tot_abono_comision	+= $cargos['COMISION'];
			$this->tot_abono_iva_comision	+= $cargos['ABONO_IVA_COMISION'];

			$this->tot_abono_segv   	+= $cargos['ABONO_SEGV'];
			$this->tot_abono_iva_segv	+= $cargos['ABONO_IVA_SEGV'];

			$this->tot_abono_segd		+= $cargos['ABONO_SEGD'];
			$this->tot_abono_iva_segd	+= $cargos['ABONO_IVA_SEGD'];

			$this->tot_abono_segb		+= $cargos['ABONO_SEGB'];
			$this->tot_abono_iva_segb	+= $cargos['ABONO_IVA_SEGB'];

            $this->tot_cuota_extra += $cargos['CUOTA_EXTRA'];
		}
		$this->tabla_amortizacion = $tmp_amortizacion;
	}
}

//========================================================================================================================
//
//========================================================================================================================

function cotiza_credito()
{
	parent::cotiza_credito();
	
	$sql = "SELECT fact_cliente.num_cliente,
		       fact_cliente.fecha_exp,       
		       fact_cliente.Fecha_Inicio,
		       fact_cliente.Fecha_Vencimiento,
		       fact_cliente.Capital,
		       fact_cliente.ID_Producto,
		       fact_cliente.plazo,
		       fact_cliente.vencimiento,
		       cargos.Fecha_vencimiento AS fecha_para_primer_vencimiento,
		       fact_cliente.TasaNominal,
		       fact_cliente.IVA_Interes,
		       fact_cliente.IVA_Comision,
		       fact_cliente.Renta,
		       fact_cliente.Metodo,
		       fact_cliente.RedondeoCifras,
		       fact_cliente.Nombre_Producto,
		       #fact_cliente.Esquema_Pagos,
		       fact_cliente.Periodos_con_Gracia,		       
		       fact_cliente.ID_Sucursal,
		       fact_cliente.ID_Tipocredito,
		       fact_cliente.num_compra,
       		       sucursales.Nombre AS Sucursal,
       		       sucursales.IVA_General,
       		       fact_cliente.Renta,
       		       cargos.Comision,
       		       cargos.SegV,
       		       cargos.SegD,
       		       cargos.SegB,
       		       cargos.Capital AS Primer_Abono_Capital


		FROM fact_cliente
		INNER JOIN cargos
			ON cargos.Num_compra = fact_cliente.num_compra
		       AND cargos.ID_Cargo = 1 

		LEFT JOIN sucursales
		       ON sucursales.ID_Sucursal =  fact_cliente.ID_Sucursal   


		WHERE fact_cliente.id_factura = '".$this->id_credito."' ";
	 
	$rs=$this->db->Execute($sql);


        $this->numcompra	= $rs->fields['num_compra'];

	$this->num_cliente	= $rs->fields['num_cliente'];
	$this->fecha_apertura	= $rs->fields['fecha_exp'];
	$this->fecha_inicio	= $rs->fields['Fecha_Inicio'];
	$this->capital		= $rs->fields['Capital'];
	$this->id_producto	= $rs->fields['ID_Producto'];
	$this->plazo		= $rs->fields['plazo'];
	$this->id_sucursal	= $rs->fields['ID_Sucursal'];

	$this->vencimiento	= $rs->fields['vencimiento'];
	$this->tasa_nominal     = $rs->fields['TasaNominal'];

        $this->metodo           = $rs->fields['Metodo'];  

        //$this->esquema_pagos    = $rs->fields['Esquema_Pagos'];        
        $this->esquema_pagos    = 'Multipago';
        
        $this->redondeocifras   =($rs->fields['RedondeoCifras']=='Si')?(true):(false);        
	$this->id_tipocredito   = $rs->fields['ID_Tipocredito'];
	
	
	//$this->periodos_con_gracia = $rs->fields['Periodos_con_Gracia'];

    $this->periodos_con_gracia = 'No';
    if(($rs->fields['Primer_Abono_Capital']*1)==0)
    {
        $this->periodos_con_gracia = 'Si';
    }

    $this->renta            = $rs->fields['Renta'];
        
        

	$this->fecha_para_primer_vencimiento	= $rs->fields['fecha_para_primer_vencimiento'];
	
	$this->producto_financiero = $rs->fields['Nombre_Producto'];


	$this->nombre_sucursal  = $rs->fields['Sucursal'];
	$this->iva_general      = $rs->fields['IVA_Genera']/100;
	$this->iva_interes      = $rs->fields['IVA_Interes']/100;
	$this->iva_comision     = $rs->fields['IVA_Comision']/100; 

	
	
	$this->monto_comision_diferida = $rs->fields['Comision'];
	
	
	$this->cuota_segv = $rs->fields['SegV']; 
	$this->cuota_segb = $rs->fields['SegB']; 	
	$this->cuota_segd = $rs->fields['SegD']; 	
	

        if($this->metodo == 'Saldos Solutos') 
        {
		$sql="  SELECT cargos.Interes
			FROM cargos
			WHERE cargos.ID_Cargo   = 1 and
			      cargos.Num_compra = '".$this->numcompra."' ";       	

        	$rs=$this->db->Execute($sql);
        	if(($rs->fields['Interes']>0) and ($this->dias_periodo > 0))
        	{

			$this->tasa_nominal = 100 * 30*($rs->fields['Interes']/($this->capital * $this->dias_periodo));	
        	
        	}        	
        }

	
	$this->cotiza_anticipo();
	return;
	

}

//========================================================================================================================
//
//========================================================================================================================

function publica()
{
	//echo "<div style='text-align:left;'><pre>";
	//print_r($this->tabla_amortizacion);
	//echo "</pre></div>";

	echo "<TABLE ALIGN='center' CELLSPACING=0 CELLPADDING=0 ID='small'  WIDTH='900px'>   \n";
	echo "<TR>    \n";
	echo "<TD bgcolor='lightsteelblue' >    \n";
	echo "<FIELDSET>    \n";
	echo "<TABLE ALIGN='left' border=0 CELLSPACING=1 CELLPADDING=2 ID='small' bgcolor='white' WIDTH='100%'>   \n";

	
	
				echo "</TR><TR>    \n";
				echo "<TH Align='right' Width='170px'>Nombre del acreditado : </TH><TD ALIGN='left'COLSPAN='3'>  ".ucwords(strtolower($this->nombre_cliente))."</TD>\n";


				echo "<TR>    \n";
				echo "<TH Align='right' Width='170px'> Sucursal :</TH><TD ALIGN='left' COLSPAN='3'>".$this->nombre_sucursal."</TD>\n";

				echo "</TR><TR>    \n";
				echo "<TH Align='right' Width='170px'> Producto Financiero :</TH><TD ALIGN='left' COLSPAN='3'> ".$this->producto_financiero."</TD>\n";

				echo "</TR><TR>    \n";
				echo "<TH Align='right' Width='170px'> Periodos con Gracia :</TH><TD ALIGN='left' COLSPAN='3'> ".$this->periodos_con_gracia."</TD>\n";

				echo "</TR><TR>    \n";
				echo "<TH Align='right' Width='170px'>Plazo : </TH><TD ALIGN='left' COLSPAN='3'>".$this->plazo ." ".$this->periodo	."</TD>\n";



				echo "</TR><TR>    \n";
				echo "<TH Align='right' Width='170px'> Monto otorgado :</TH><TD ALIGN='left'> $".number_format(($this->capital),2)."</TD>\n";


				echo "</TR><TR>    \n";
				echo "<TH Align='right' Width='170px'> Tasa mensual bruta:</TH><TD ALIGN='left'> ".number_format(($this->tasa_nominal),6)."%</TD>\n";
				echo "</TR> \n";
				echo "</TABLE> \n";
				echo "</FIELDSET>    \n";
				echo "</TD>    \n";
				echo "</TR>    \n";

			        echo "</TABLE><BR> \n";





	echo "<TABLE ALIGN='center' CELLSPACING=0 CELLPADDING=0 ID='small'  WIDTH='900px'>   \n";
	echo "<TR>    \n";
	echo "<TD bgcolor='lightsteelblue' >    \n";
	echo "<FIELDSET>    \n";
	echo "<TABLE ALIGN='center' CELLSPACING=1 CELLPADDING=1 ID='small' bgcolor='lightsteelblue' WIDTH='100%'>   \n";

	echo "<TR  bgcolor='lightsteelblue'>  \n";
	echo "        <TH> No. Vencimiento     </TH>  \n";
	echo "        <TH> Fecha 	</TH>  \n";
	echo "        <TH> Pago fijo	</TH>  \n";

	echo "        <TH> Interéses </TH>  \n";
	echo "        <TH> IVA       </TH>  \n";

	//if($this->tot_abono_comision > 0)
	{
		echo "        <TH> Comisión    </TH>  \n";
		echo "        <TH> IVA Comisión     </TH>  \n";
	}

	if($this->tot_abono_segv > 0)
	{
		echo "        <TH> Seg Vida    </TH>  \n";
		echo "        <TH> IVA      </TH>  \n";
	}

	if($this->tot_abono_segd > 0)
	{
		echo "        <TH> Seg Desempleo    </TH>  \n";
		echo "        <TH> IVA      </TH>  \n";
	}

	if($this->tot_abono_segb > 0)
	{
		echo "        <TH> Seg Bienes Mat.    </TH>  \n";
		echo "        <TH> IVA      </TH>  \n";
	}
        echo "        <TH> Cuota Extra    </TH>  \n";

	echo "        <TH> Capital     </TH>  \n";


	echo "        <TH>Saldo de Capital    </TH>  \n";
	echo "</TR>  \n";


	$j = 0;
	
	$digits = 2;
	/*
	echo "<div style='text-align:left'><pre>";
	print_r($this->tabla_amortizacion);
	echo "</pre></div>";
	*/

	foreach($this->tabla_amortizacion AS $i=>$row)
	{
		$color =($color == "white")?("whitesmoke"):("white");
		$color =(($this->tabla_amortizacion[$i]['RENTA'] == $this->tabla_amortizacion[$i]['ABONO_CAPITAL']) and($this->tabla_amortizacion[$i]['RENTA']>0) )?("yellow"):($color);	
		$color =($this->tabla_amortizacion[$i]['ANTICIPO'] )?("orange"):(($row['CUOTA_EXTRA'] > 0)?"#EFE7BF":($color));

		echo "<TR Align='right'  BGCOLOR='".$color."'>\n";


		if($this->tabla_amortizacion[$i]['ANTICIPO'] or 
		  (($this->tabla_amortizacion[$i]['RENTA'] == $this->tabla_amortizacion[$i]['ABONO_CAPITAL']) 
		     and ($this->tabla_amortizacion[$i]['RENTA']>0)) or isset($this->tabla_amortizacion[$i]['ID_Concepto']))
		{     
			
			echo "	<TH Align='center'>     </TH>\n";

		}
		else
		{		
			echo "	<TH Align='center'> ".$j.")    </TH>\n";

			++$j;
		}

		echo "	<TD Align='center'> ".$row['FECHA']."</TD>\n";
		echo "	<TH>".number_format($row['RENTA'] 		   ,$digits)."</TH>\n";

		echo "	<TD>".number_format($row['ABONO_INTERES']  	   ,$digits)."</TD>\n";
		echo "	<TD>".number_format($row['ABONO_IVA_INTERES'] 	   ,$digits)."</TD>\n";

		//if($this->tot_abono_comision>0)
		{
			echo "	<TD>".number_format($row['COMISION']	   	   ,$digits)."</TD>\n
				<TD>".number_format($row['ABONO_IVA_COMISION'] 	   ,$digits)."</TD>\n";
		}

		if($this->tot_abono_segv > 0)
		{
			echo "	<TD>".number_format($row['ABONO_SEGV']	   ,$digits)."</TD>\n
				<TD>".number_format($row['ABONO_IVA_SEGV'] ,$digits)."</TD>\n";
		}

		if($this->tot_abono_segd > 0)
		{
			echo "	<TD>".number_format($row['ABONO_SEGD']	   	,$digits)."</TD>\n
				<TD>".number_format($row['ABONO_IVA_SEGD'] 	,$digits)."</TD>\n";
		}

		if($this->tot_abono_segb > 0)
		{
			echo "	<TD>".number_format($row['ABONO_SEGB']	   	,$digits)."</TD>\n
				<TD>".number_format($row['ABONO_IVA_SEGB'] 	,$digits)."</TD>\n";
		}

        echo "	<TD>".number_format($row['CUOTA_EXTRA']	   	,$digits)."</TD>\n";

		echo "	<TD>".number_format($row['ABONO_CAPITAL']  	   ,$digits)."</TD>";

		echo "	<TH><I>".number_format($row['SALDO_CAPITAL']  	   ,$digits)."</I></TH>\n";
		echo "</TR>\n";




	}


	echo "	<TR ALIGN='right'>";
	echo "	<TD COLSPAN='2'></TD>";
	echo "	<TH>".number_format($this->tot_abonos,         	 	$digits)."</TH>";

	echo "	<TH>".number_format($this->tot_abono_interes,  	 	$digits)."</TH>";
	echo "	<TH>".number_format($this->tot_abono_iva_interes,       $digits)."</TH>";

	//if($this->tot_abono_comision>0)
	{
		echo "	<TH>".number_format($this->tot_abono_comision, 	  $digits)."</TH>";
		echo "	<TH>".number_format($this->tot_abono_iva_comision,      $digits)."</TH>";
	}


	if($this->tot_abono_segv > 0)
	{
		echo "	<TH>".number_format($this->tot_abono_segv   	,$digits)."</TH>\n
			<TH>".number_format($this->tot_abono_iva_segv	,$digits)."</TH>\n";
	}

	if($this->tot_abono_segd > 0)
	{
		echo "	<TH>".number_format($this->tot_abono_segd	,$digits)."</TH>\n
			<TH>".number_format($this->tot_abono_iva_segd	,$digits)."</TH>\n";
	}

	if($this->tot_abono_segb > 0)
	{
		echo "	<TH>".number_format($this->tot_abono_segb	,$digits)."</TH>\n
			<TH>".number_format($this->tot_abono_iva_segb	,$digits)."</TH>\n";
	}
    echo "	<TH>".number_format($this->tot_cuota_extra,  	  $digits)."</TH>";
	echo "	<TH>".number_format($this->tot_abono_capital,  	  $digits)."</TH>";
	echo "	</TR>";



	echo "</TABLE> \n";
	echo "</FIELDSET>    \n";
	echo "</TD>    \n";
	echo "</TR>    \n";

	echo "</TABLE> \n";

}
//=========================================================================================================================
//
//=========================================================================================================================
function ajusta_comision()
{
	$faltante = ($this->monto_total_comision - $this->tot_abono_comision);
	
	//debug(" $faltante = (".$this->monto_total_comision." - ".$this->tot_abono_comision.");  ");
	
	if($faltante <= 0.01)
	{
		return;
	}

	$j = count($this->tabla_amortizacion)-1;
	
	$diferencial_ultima_cuota = $this->renta - $this->tabla_amortizacion[$j]['RENTA'];
	
	$diferencial_ultima_cuota_bruto = 	$diferencial_ultima_cuota/(1+$this->iva_comision);
	
	$diferencial_ultima_cuota_bruto_cuotas_nuevas = $faltante - $diferencial_ultima_cuota_bruto;

	$num_cuotas_extra = 0;
	if(( $diferencial_ultima_cuota_bruto_cuotas_nuevas/$this->renta) >0)
	{
		$num_cuotas_extra = ceil($diferencial_ultima_cuota_bruto_cuotas_nuevas/$this->renta);
	}

	if($diferencial_ultima_cuota_bruto > 0)
	{
		if($num_cuotas_extra > 0)
		{
			$this->tabla_amortizacion[$j]['RENTA'] = $this->renta;
			
			//debug($j);
			
			$this->tabla_amortizacion[$j]['SALDO_CAPITAL'] = ($num_cuotas_extra/100);			
			$this->tabla_amortizacion[$j]['ABONO_CAPITAL'] = $this->tabla_amortizacion[$j]['ABONO_CAPITAL'] - ($num_cuotas_extra/100);
			
			
			$diferencial_ultima_cuota += ($num_cuotas_extra/100);
			$diferencial_ultima_cuota_bruto = $diferencial_ultima_cuota/(1+$this->iva_comision);
			
			$this->tabla_amortizacion[$j]['COMISION'] += $diferencial_ultima_cuota_bruto ;
			$this->tabla_amortizacion[$j]['ABONO_IVA_COMISION'] += ($diferencial_ultima_cuota - $diferencial_ultima_cuota_bruto);
			
		}
		else
		{
			$this->tabla_amortizacion[$j]['COMISION'] += $diferencial_ultima_cuota_bruto;
			$this->tabla_amortizacion[$j]['ABONO_IVA_COMISION'] += ($diferencial_ultima_cuota - $diferencial_ultima_cuota_bruto);

			$this->tabla_amortizacion[$j]['RENTA'] += $diferencial_ultima_cuota;
	  	}

		$this->tot_abono_comision	+= $diferencial_ultima_cuota_bruto;
		$this->tot_abono_iva_comision	+= ($diferencial_ultima_cuota - $diferencial_ultima_cuota_bruto);
	}
/**/	
	for($i=$j+1; $i<=($j+$num_cuotas_extra); $i++)
	{
			$fecha_anterior = $this->tabla_amortizacion[($i-1)]['FECHA'];
	   		$dia_espacifico = fdia(gfecha($fecha_anterior));	
	   		
			$this->tabla_amortizacion[$i]['FECHA']=  fechavencimiento($fecha_anterior, $this->frecuencia, $dia_espacifico);
			
			
			if(( $diferencial_ultima_cuota_bruto_cuotas_nuevas/$this->renta) >=1)
			{
				
				$abono_comision 	= $this->renta/(1+$this->iva_comision);		
				$abono_iva_comision 	= $this->renta-$abono_comision;
			}
			else
			{
				
				$abono_comision 	= $diferencial_ultima_cuota_bruto_cuotas_nuevas;
				$abono_iva_comision 	= $diferencial_ultima_cuota_bruto_cuotas_nuevas * ($this->iva_comision);
				
				//debug("$abono_iva_comision 	= $diferencial_ultima_cuota_bruto_cuotas_nuevas * (".$this->iva_comision."); ");
			}
			

			$this->tot_abono_comision	+= $abono_comision;
			$this->tot_abono_iva_comision	+= $abono_iva_comision;
			
			$renta = $abono_comision + $abono_iva_comision;



			$diferencial_ultima_cuota_bruto_cuotas_nuevas = $this->monto_total_comision - $this->tot_abono_comision;
			

			$this->tabla_amortizacion[$i]['COMISION']   	   	=  $abono_comision;
			$this->tabla_amortizacion[$i]['ABONO_IVA_COMISION']	=  $abono_iva_comision;				


			$this->tabla_amortizacion[$i]['ABONO_INTERES']     	=  0;		
			$this->tabla_amortizacion[$i]['ABONO_IVA_INTERES'] 	=  0;


			$this->tabla_amortizacion[$i]['ABONO_SEGV']        	=  0;			
			$this->tabla_amortizacion[$i]['ABONO_IVA_SEGV'] 	=  0;

			$this->tabla_amortizacion[$i]['ABONO_SEGD']     	=  0;
			$this->tabla_amortizacion[$i]['ABONO_IVA_SEGD'] 	=  0;

			$this->tabla_amortizacion[$i]['ABONO_SEGB']     	=  0;
			$this->tabla_amortizacion[$i]['ABONO_IVA_SEGB'] 	=  0;

			$this->tabla_amortizacion[$i]['ABONO_CAPITAL']  	=  0.01;

			$this->tabla_amortizacion[$i]['ABONO_IVA']      	=  0;

			$this->tabla_amortizacion[$i]['RENTA']          	= $renta;
			$this->tabla_amortizacion[$i]['SALDO_CAPITAL'] 		= $this->tabla_amortizacion[($i-1)]['SALDO_CAPITAL'] - $this->tabla_amortizacion[$i]['ABONO_CAPITAL'];


	
	
	}


/**/

}
//=========================================================================================================================
//
//=========================================================================================================================

function aplica_anticipo()
{
	global $_SESSION;
	
	if($this->error > 0)
	    return(-1);



        switch ($this->vencimiento )
        {
          // Anual
          case 'Anios' : 	$tipo_plazox = "anual";                         
			 	break;
          // Semestral         
          case 'Semestres' : 	$tipo_plazox = "semestral";                          
				break;
         // Trimestral          
          case 'Trimestres' :	$tipo_plazox = "trimestral";   
				 break;
         // Bimestral          
          case 'Bimestres' : 	$tipo_plazox = "bimestral";   
				break; 
          // Mensual
          case 'Meses' : 	$tipo_plazox = "mensual";   
                        	break;
          //Quincenal 
          case 'Quincenas' : 	$tipo_plazox = "quincenal";   
                          	break;                   
          //Semanal                   
          case 'Semanas' : 	$tipo_plazox = "semanal";                               
				break;
           //Diaria                             
          case 'Dias' :		$tipo_plazox = "diaria";     
                       		break;          
          //Catorcenal                    
          case 'Catorcenas' :	$tipo_plazox = "catorcenal";     
				break;
        };




	$sql =" INSERT INTO anticipo_capital_log
		  (ID_Credito, ID_Pago, Fecha, Monto, Usuario)
		  VALUES
		  ('".$this->id_credito."','".$this->id_pago."','".$this->pago_fecha."','".abs($this->monto_anticipo)."','".$_SESSION['NOM_USR']."') ";

	$this->db->Execute($sql);
	$this->id_anticipo =  $this->db->_insertid();

	if($this->id_anticipo == 0)
	{	
		$this->error++;
		$this->error_list[]="No fue posible generar una entrada en la bitácora.";
		return;
	}

	$sql ="INSERT INTO anticipo_capital_cargos
	      ( 
	        SELECT  '".$this->id_anticipo."' AS id_anticipo,
			cargos.*
		FROM 	cargos

		WHERE cargos.Num_compra = '".$this->numcompra."' and
		      cargos.Activo = 'Si' and
		      cargos.ID_Concepto = -3

		ORDER BY  cargos.ID_Cargo 
	      ) ";  

	//debug($sql);
	
	$this->db->Execute($sql);
	
	$sql =" SELECT COUNT(ID_Anticipo)
		FROM anticipo_capital_cargos
		WHERE ID_Anticipo =  '".$this->id_anticipo."' ";

	//debug($sql);

	$rs = $this->db->Execute($sql);
	
	if($rs->fields[0] == 0)
	{
		$this->error++;
		$this->error_list[]="No fue posible aplicar respaldo en la bitácora.";
		return;
	}


	$sql =" DELETE FROM cargos
		WHERE cargos.Num_compra         = '".$this->numcompra."' and
		      cargos.Fecha_Vencimiento >= '".$this->pago_fecha."' and
		      cargos.ID_Concepto 	= -3 ";

	//debug($sql);
	$this->db->Execute($sql);


	$zql ="	SELECT MAX(cargos.ID_Cargo) AS New_ID_Cargo
		FROM cargos
		WHERE cargos.Num_compra  = '".$this->numcompra."' and
		      cargos.ID_Concepto = -3 ";				
	$rz = $this->db->Execute($zql);
	$ID_CARGO = $rz->fields[0];

	//debug($zql);


	$sql="REPLACE INTO cargos
		(ID_Cargo, ID_Concepto, Num_compra, Num_factura, Fecha_vencimiento, Monto, Capital, Interes, IVA_Interes, Comision, IVA_Comision, SegV, IVA_SegV, SegD, IVA_SegD, SegB, IVA_SegB, IVA, Activo, SubTipo, Concepto)
	     VALUES  \n";
	     
    
        $i=0;

	foreach($this->tabla_amortizacion AS $row)
	{
	

		if($row['SUBTIPO'] == "General")
		{
			$i++;
		}


		if(($row['ID_Cargo']>0) and (trunc($row['RENTA'],2)>0))
		{


			if(gfecha($row['FECHA']) >= $this->pago_fecha ) 
			{

				$sql.= chr(13);
				
				$ID_CARGO++;
				
				
				$sql.="('".$ID_CARGO."','-3','".($this->numcompra)."','".$this->id_credito."',";
				$sql.= "'".gfecha($row['FECHA'])."',"; 			
				$sql.= "'".($row['RENTA']*1)."',";  			
				$sql.= "'".($row['ABONO_CAPITAL']*1)."',";  		

				$sql.= "'".($row['ABONO_INTERES']*1)."',";  		
				$sql.= "'".($row['ABONO_IVA_INTERES']*1)."',";  	

				$sql.= "'".($row['COMISION']*1)."',";  		
				$sql.= "'".($row['ABONO_IVA_COMISION']*1)."',"; 	

				$sql.= "'".($row['ABONO_SEGV']*1)."',";  			
				$sql.= "'".($row['ABONO_IVA_SEGV']*1)."',";  		

				$sql.= "'".($row['ABONO_SEGD']*1)."',";  			
				$sql.= "'".($row['ABONO_IVA_SEGD']*1)."',";  		

				$sql.= "'".($row['ABONO_SEGB']*1)."',";  			
				$sql.= "'".($row['ABONO_IVA_SEGB']*1)."',";  	

				$iva = 	  $this->tabla_amortizacion[$i]['ABONO_IVA_INTERES'] 	
					+ $this->tabla_amortizacion[$i]['ABONO_IVA_COMISION'] 	
					+ $this->tabla_amortizacion[$i]['ABONO_IVA_SEGV'] 	
					+ $this->tabla_amortizacion[$i]['ABONO_IVA_SEGD'] 	
					+ $this->tabla_amortizacion[$i]['ABONO_IVA_SEGB'] ;

				$sql.= "'".($iva * 1)."',"; 			
				$sql.= "'Si', ";			
				
				
				$sql.= "'".$row['SUBTIPO']."', ";		
				
				

				if($row['SUBTIPO'] == "Capital")
				{
					$concepto = "Anticipo a capital";			
				}
				else
				{
					$concepto = "Cuota ".$tipo_plazox." no. ".$i." de ".$this->plazo;
				}
				$sql.= "'".$concepto."' ),";			
			}


			

		}
	
	}

        $len    = strlen($sql) - 1;
	$sqlend = substr($sql,0, $len);
	
	$this->db->Execute($sqlend);
	//debug($sqlend);
	
	$sql= " UPDATE pagos SET pagos.Subtipo = 'Capital' WHERE pagos.ID_Pago = '".$this->id_pago."' ";
	$this->db->Execute($sql);

	//debug($sql);
	$this->aplica_recierre();
	
	return(0);


}
//=========================================================================================================================
//
//=========================================================================================================================
/**
 * Método para aplicación de anticipos a capital sobre saldo a favor
 * Puede provenir o no de un pago registrado
 *
 * Autor: Ignacio Ocampo Sandoval
 *
 * Fecha: Jueves 21 de agosto de 2014
 * 
 */
function aplica_anticipo_saldo_favor()
{
	global $_SESSION;

	$this->pago_fecha = gfecha($this->fecha_anticipo);
	
	if($this->error > 0)
	    return(-1);

        switch ($this->vencimiento )
        {
          // Anual
          case 'Anios' : 	$tipo_plazox = "anual";                         
			 	break;
          // Semestral         
          case 'Semestres' : 	$tipo_plazox = "semestral";                          
				break;
         // Trimestral          
          case 'Trimestres' :	$tipo_plazox = "trimestral";   
				 break;
         // Bimestral          
          case 'Bimestres' : 	$tipo_plazox = "bimestral";   
				break; 
          // Mensual
          case 'Meses' : 	$tipo_plazox = "mensual";   
                        	break;
          //Quincenal 
          case 'Quincenas' : 	$tipo_plazox = "quincenal";   
                          	break;                   
          //Semanal                   
          case 'Semanas' : 	$tipo_plazox = "semanal";                               
				break;
           //Diaria                             
          case 'Dias' :		$tipo_plazox = "diaria";
                       		break;          
          //Catorcenal                    
          case 'Catorcenas' :	$tipo_plazox = "catorcenal";     
				break;
        };

	$sql =" INSERT INTO anticipo_capital_saldo_favor_log	(	ID_Credito,
                                                            ID_Abonos,
															Fecha,
															Monto,
															Usuario,
															Registro)
		  										VALUES 	(	'".$this->id_credito."',
		  										            '".$this->lista_pagos."',
		  													'".$this->pago_fecha."',
		  													'".abs($this->monto_anticipo_val)."',
		  													'".$_SESSION['NOM_USR']."',
		  													NOW())";

	$this->db->Execute($sql);
	$this->id_anticipo =  $this->db->_insertid();

	if($this->id_anticipo == 0)
	{
		$this->error++;
		$this->error_list[]="No fue posible generar una entrada en la bitácora.";
		return;
	}

	$sql ="INSERT INTO anticipo_capital_saldo_favor_cargos
	      ( 
	        SELECT  '".$this->id_anticipo."' AS id_anticipo,
			cargos.*
            FROM 	cargos

		    WHERE cargos.Num_compra = '".$this->numcompra."' and
		      cargos.Activo = 'Si' and
		      (cargos.ID_Concepto = -3 OR cargos.ID_Concepto = -2)

		    ORDER BY  cargos.ID_Cargo
	      ) ";
	
	$this->db->Execute($sql);
	
	$sql =" SELECT COUNT(ID_Anticipo)
		    FROM anticipo_capital_saldo_favor_cargos
		    WHERE ID_Anticipo =  '".$this->id_anticipo."' ";


	$rs = $this->db->Execute($sql);
	
	if($rs->fields[0] == 0)
	{
		$this->error++;
		$this->error_list[]="No fue posible aplicar respaldo en la bitácora.";
		return;
	}


	$sql =" DELETE FROM cargos
		    WHERE cargos.Num_compra         = '".$this->numcompra."' and
		      cargos.Fecha_Vencimiento >= '".$this->pago_fecha."' and
		      cargos.ID_Concepto 	= -3 ";

	//debug($sql);
	$this->db->Execute($sql);


	$zql ="	SELECT MAX(cargos.ID_Cargo) AS New_ID_Cargo
		    FROM cargos
		    WHERE cargos.Num_compra  = '".$this->numcompra."' and
		      cargos.ID_Concepto = -3 ";				
	$rz = $this->db->Execute($zql);
	$ID_CARGO = $rz->fields[0];

	$sql="REPLACE INTO cargos
		(ID_Cargo, ID_Concepto, Num_compra, Num_factura, Fecha_vencimiento, Monto, Capital, Interes, IVA_Interes, Comision, IVA_Comision, SegV, IVA_SegV, SegD, IVA_SegD, SegB, IVA_SegB, IVA, Cuota_Extra, Activo, SubTipo, Concepto)
	     VALUES  \n";

        $i=0;

	foreach($this->tabla_amortizacion AS $row)
	{
		if($row['SUBTIPO'] == "General")
		{
			$i++;
		}
		if(($row['ID_Cargo']>0) and (trunc($row['RENTA'],2)>0))
		{
			if(gfecha($row['FECHA']) >= $this->pago_fecha ) 
			{
				$sql.= chr(13);
				
				$ID_CARGO++;

				if(in_array($ID_CARGO, $this->id_notas_cargo)) {
					$ID_CARGO++;
				}

				$sql.="('".$ID_CARGO."','-3','".($this->numcompra)."','".$this->id_credito."',";
				$sql.= "'".gfecha($row['FECHA'])."',"; 			
				$sql.= "'".($row['RENTA']*1)."',";  			
				$sql.= "'".($row['ABONO_CAPITAL']*1)."',";  		

				$sql.= "'".($row['ABONO_INTERES']*1)."',";  		
				$sql.= "'".($row['ABONO_IVA_INTERES']*1)."',";  	

				$sql.= "'".($row['COMISION']*1)."',";  		
				$sql.= "'".($row['ABONO_IVA_COMISION']*1)."',"; 	

				$sql.= "'".($row['ABONO_SEGV']*1)."',";  			
				$sql.= "'".($row['ABONO_IVA_SEGV']*1)."',";  		

				$sql.= "'".($row['ABONO_SEGD']*1)."',";  			
				$sql.= "'".($row['ABONO_IVA_SEGD']*1)."',";  		

				$sql.= "'".($row['ABONO_SEGB']*1)."',";  			
				$sql.= "'".($row['ABONO_IVA_SEGB']*1)."',";  	

				$iva = 	  $this->tabla_amortizacion[$i]['ABONO_IVA_INTERES'] 	
					+ $this->tabla_amortizacion[$i]['ABONO_IVA_COMISION'] 	
					+ $this->tabla_amortizacion[$i]['ABONO_IVA_SEGV'] 	
					+ $this->tabla_amortizacion[$i]['ABONO_IVA_SEGD'] 	
					+ $this->tabla_amortizacion[$i]['ABONO_IVA_SEGB'] ;

				$sql.= "'".($iva * 1)."',";
                $sql.= "'".($row['CUOTA_EXTRA']*1)."',";
				$sql.= "'Si', ";			
				
				
				$sql.= "'".$row['SUBTIPO']."', ";		
				
				

				if($row['SUBTIPO'] == "Capital")
				{
					$concepto = "Anticipo a capital";			
				}
				else
				{
					$concepto = "Cuota ".$tipo_plazox." no. ".$i." de ".$this->plazo;
				}
				$sql.= "'".$concepto."' ),";			
			}
		}
	}

    $len    = strlen($sql) - 1;
	$sqlend = substr($sql,0, $len);
	
	$this->db->Execute($sqlend);
	//debug($sqlend);
    if($this->lista_pagos != ''){
        $sql= " UPDATE pagos SET pagos.Subtipo = 'Capital' WHERE pagos.ID_Pago in (".$this->lista_pagos.") ";
        $this->db->Execute($sql);
    }
	$this->aplica_recierre();
	
	return(0);
}
//=========================================================================================================================
//
//=========================================================================================================================

function aplica_recierre()
{

if(empty($this->pago_fecha)) return;
if(empty($this->id_credito)) return;



 $sql=" SELECT cierre_contable_log.ID_Cierre, 
               cierre_contable_log.Fecha_Cierre
        FROM  cierre_contable_log
        WHERE cierre_contable_log.Fecha_Cierre >= '".$this->pago_fecha."'
        ORDER BY Fecha_Cierre ";

 $rs=$this->db->Execute($sql);


if($rs->_numOfRows)
{
	
	$fecha_cierre = $rs->fields[1];
	$sql = "DELETE FROM cierre_contable_pagos
		WHERE       cierre_contable_pagos.Fecha >=  '".$fecha_cierre."'
			and cierre_contable_pagos.Num_Compra = '".$this->numcompra."'  ";

	$this->db->Execute($sql);




   while(! $rs->EOF)
	{
		$id_cierre = $rs->fields[0];
		$fecha_cierre = $rs->fields[1];


		$sql = "DELETE FROM cierre_contable_saldos
			WHERE       cierre_contable_saldos.ID_Cierre  = '".$id_cierre."'
				and cierre_contable_saldos.ID_Factura = '".$this->id_credito."'  ";
		$this->db->Execute($sql);
		


	        $obj =  new TCIERRE($id_cierre, 1,false, $this->db, $this->id_credito);
		//echo "<BR> ID_CIERRE : ".$id_cierre." Fecha : ".$rs->fields['Fecha_Cierre']." : ID Crédito : (".$this->id_credito.")";    

		if($obj->status<0)
		{
			error_msg($obj->error);
		}


	     $rs->MoveNext();
        }


}








}




}

/*

CREATE TABLE `anticipo_capital_log` (
	`ID_Anticipo` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`ID_Credito` INT(10) UNSIGNED NOT NULL,
	`ID_Pago` INT(10) UNSIGNED NOT NULL,
	`Fecha` DATE NOT NULL,
	`Monto` DECIMAL(12,2) NOT NULL,
	`Usuario` VARCHAR(100) NOT NULL,
	`Registro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`ID_Anticipo`),
	UNIQUE INDEX `Unicidad` (`ID_Credito`, `ID_Pago`),
	INDEX `ID_Credito` (`ID_Credito`),
	INDEX `ID_Pago` (`ID_Pago`)
)
COLLATE='latin1_swedish_ci'
ENGINE=MyISAM;


CREATE TABLE `anticipo_capital_cargos` (
	`ID_Anticipo` SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	`ID_Cargo` SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	`ID_Concepto` INT(10) NOT NULL DEFAULT '-99',
	`Num_compra` VARCHAR(10) NOT NULL DEFAULT '0',
	`Num_factura` VARCHAR(20) NOT NULL DEFAULT '0',
	`Fecha_vencimiento` DATE NULL DEFAULT NULL,
	`Monto` DECIMAL(14,2) NOT NULL DEFAULT '0.00',
	`AntiMonto` DOUBLE(14,4) NOT NULL DEFAULT '0.0000',
	`Capital` DECIMAL(14,2) NULL DEFAULT NULL,
	`AntiCapital` DOUBLE(14,4) NOT NULL DEFAULT '0.0000',
	`Interes` DECIMAL(14,2) NULL DEFAULT NULL,
	`IVA_Interes` DECIMAL(14,2) NULL DEFAULT NULL,
	`AntiInteres` DOUBLE(14,4) NOT NULL DEFAULT '0.0000',
	`Comision` DECIMAL(14,2) NULL DEFAULT NULL,
	`IVA_Comision` DECIMAL(14,2) NULL DEFAULT NULL,
	`AntiComision` DOUBLE(14,4) NOT NULL DEFAULT '0.0000',
	`Moratorio` DECIMAL(12,2) NULL DEFAULT NULL,
	`IVA_Moratorio` DECIMAL(12,2) NULL DEFAULT NULL,
	`AntiMoratorio` DOUBLE(14,4) NOT NULL DEFAULT '0.0000',
	`Otros` DECIMAL(12,2) UNSIGNED NULL DEFAULT NULL,
	`IVA_Otros` DECIMAL(12,2) NULL DEFAULT NULL,
	`AntiOtros` DOUBLE(14,4) NOT NULL DEFAULT '0.0000',
	`SegV` DECIMAL(10,2) NULL DEFAULT NULL,
	`IVA_SegV` DECIMAL(10,2) NULL DEFAULT NULL,
	`SegD` DECIMAL(10,2) NULL DEFAULT NULL,
	`IVA_SegD` DECIMAL(10,2) NULL DEFAULT NULL,
	`SegB` DECIMAL(10,2) NULL DEFAULT NULL,
	`IVA_SegB` DECIMAL(10,2) NULL DEFAULT NULL,
	`IVA` DECIMAL(10,2) NULL DEFAULT NULL,
	`AntiIVA` DOUBLE(14,4) NOT NULL DEFAULT '0.0000',
	`Activo` ENUM('Si','No') NOT NULL DEFAULT 'Si',
	`SubTipo` ENUM('General','Capital','Interes','Comision','Moratorio','Otros','SegV','SegD','SegB') NOT NULL DEFAULT 'General',
	`Concepto` VARCHAR(65) NOT NULL DEFAULT 'Cargo semanal.',
	`Observaciones` TEXT NULL,
	INDEX `ID_Anticipo` (`ID_Anticipo`),
	INDEX `ID_Cargo` (`ID_Cargo`),
	INDEX `Num_compra` (`Num_compra`),
	INDEX `Fecha_vencimiento` (`Fecha_vencimiento`)
);



*/

?>