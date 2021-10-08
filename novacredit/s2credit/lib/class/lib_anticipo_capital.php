<?php
/*                                                          
    _________________________________________________________________________________________
   |  Titulo: Aplicar pagos como anticipo a capital               
   |                                                        
 # |  Autor : Enrique Godoy Calderón                        
## |                                                        
## |  Fecha : Tuesday, 2012 Nov 12                   
## |                                                        
## |                                                                                        
##  ¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯
########################################################################################
######################################################################################
*/

require_once($class_path."lib_credit_rsf.php");

class T_ANTICIPO_CAPITAL
{

var $idc;
var $id_pago;
var $db;
var $fecha_ajuste;


var $error = 0;
var $error_list = array();
var $tabla_amortizacion = array();
var $tabla_modificada = array();
var $notas_cargo = array();
var $id_notas_cargo = array();

function T_ANTICIPO_CAPITAL($idc, $id_pago, $db)
{
	$this->idc		= $idc;
	$this->id_pago		= $id_pago;
	$this->db		= $db;
				
		
	$this->calcula_datos_credito();
	
	$this->calcula_tabla_modificada();
	
	if($this->error>0)
	{  
	        $error_msg = "";
	        
		foreach($this->error_list as $txt)
		{
			$error_msg .= "<LI STYLE='color:red;'>".$txt."</LI>";
		}
		
		error_msg("Existen errores : <OL >".$error_msg."</OL>");
	
	}
	
}
//=========================================================================================================================
//
//=========================================================================================================================

function calcula_datos_credito()
{

     $sql= " SELECT   fact_cliente.ID_Tipocredito,
                      fact_cliente.ID_Producto,
                      fact_cliente.num_cliente,
                      fact_cliente.Capital,
                      fact_cliente.TasaNominal,
                      fact_cliente.Metodo,
                      fact_cliente.renta,
                      fact_cliente.IVA_Interes,
                      fact_cliente.plazo,
                      fact_cliente.vencimiento,
                      fact_cliente.ID_Sucursal,
                      fact_cliente.num_compra,
                      fact_cliente.Fecha_Inicio,
                      cargos.Fecha_vencimiento,
                      cargos.Capital AS Primer_Abono_Capital



            FROM fact_cliente
            
            INNER JOIN cargos ON cargos.num_compra = fact_cliente.num_compra and cargos.ID_Cargo = 1


            WHERE  fact_cliente.id_factura = '".$this->idc."' ";
      
      $rs=$this->db->Execute($sql);


      $this->num_cliente           		= $rs->fields['num_cliente']; 
      $this->capital               		= $rs->fields['Capital']; 
      $this->id_producto           		= $rs->fields['ID_Producto']; 
      $this->tasa_nominal      			= $rs->fields['TasaNominal']; 
      $this->metodo      			= $rs->fields['Metodo']; 
      $this->renta				= $rs->fields['renta'];
      $this->iva_interes			= $rs->fields['IVA_Interes'];
      
      $this->plazo                 		= $rs->fields['plazo']; 
      $this->vencimiento                 	= $rs->fields['vencimiento'];
      $this->id_sucursal           		= $rs->fields['ID_Sucursal']; 
      $this->num_factura           		= $rs->fields['num_factura']; 
      $this->num_compra            		= $rs->fields['num_compra']; 
      $this->fecha_inicio          		= $rs->fields['Fecha_Inicio'];  
      $this->fecha_primer_vencimiento 		= $rs->fields['Fecha_vencimiento']; 
      
      $dias_periodo=30;      

        switch ($this->vencimiento )
        {
          // Anual
          case 'Anios' : 	$dias_periodo=360;                         
			 	break;
          // Semestral         
          case 'Semestres' :	$dias_periodo=180;                         
			     	break;
         // Trimestral          
          case 'Trimestres' :	$dias_periodo=90;                         
			     	 break;                          
         // Bimestral          
          case 'Bimestres' : 	$dias_periodo=90;                         
			     	break;
          // Mensual
          case 'Meses' : 	$dias_periodo=30;                         
			     	break;
          //Quincenal 
          case 'Quincenas' : 	$dias_periodo=15;                         
			     	break;
          //Catorcenal                    
          case 'Catorcenas' :  $dias_periodo=14;                         
				break;
	  //Semanal                   
          case 'Semanas' : 	$dias_periodo=7;                         
			     	break;
          //Diaria                             
          case 'Dias' :		$dias_periodo=1;                         
			     	break;
        };
    $this->dias_periodo = $dias_periodo;
    
    $this->tasa_periodo =($this->tasa_nominal/30)  * $this->dias_periodo;
      

      $this->periodos_gracia = 'No';
      if(($rs->fields['Primer_Abono_Capital']*1)==0)
      {
      		$this->periodos_gracia = 'Si';
      }

     $sql= "SELECT  pagos.Monto,
		    pagos.Fecha
	     FROM   pagos
	     WHERE  pagos.ID_Pago = '".$this->id_pago."' "; 		

      $rs=$this->db->Execute($sql);

     $this->pago_monto = $rs->fields['Monto'];
     $this->pago_fecha = $rs->fields['Fecha'];



     $sql= "SELECT   cargos.Fecha_vencimiento,
		     cargos.Cuota_Extra

	     FROM cargos

	     WHERE cargos.Num_compra  = '".($this->num_compra)."' and
		   cargos.Activo      = 'Si' and
		   cargos.ID_Concepto = -3 and
		      cargos.Cuota_Extra > 0

	     ORDER BY  cargos.Fecha_vencimiento ";

      $rs=$this->db->Execute($sql);

      $this->cuotas_extra = array();
      
if($rs->_numOfRows)
   while(! $rs->EOF)
   {
     
     $_fecha = $rs->fields['Fecha_vencimiento'];
     $_monto = $rs->fields['Cuota_Extra']*1;
     
     $this->cuotas_extra[$_fecha]=$_monto;
     $rs->MoveNext();
   }

   // Notas de cargo
   // ----------------------------------------------------
	$sql= "	SELECT 	ID_Cargo,
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
	     	WHERE cargos.Num_compra  = '".($this->num_compra)."'
	     	AND cargos.Activo      = 'Si'
	     	AND cargos.ID_Concepto = -2

	     	ORDER BY  cargos.Fecha_vencimiento ";
	
	$rs=$this->db->Execute($sql);

	$this->cuotas_extra = array();
	$i = 0;
	if($rs->_numOfRows)
   	while(! $rs->EOF) {
   		$this->notas_cargo[$rs->fields['ID_Cargo']] = $rs->fields['Fecha_vencimiento'];
   		$this->id_notas_cargo[] = $rs->fields['ID_Cargo'];
		$i++;
		$rs->MoveNext();
	}
   //-----------------------------------------------------

     $sql= " SELECT cargos.ID_Cargo,
		    cargos.Monto,
		    cargos.Fecha_vencimiento,
		    cargos.Capital,
		    cargos.Interes,
		    cargos.IVA_Interes,
		    cargos.Comision,
		    cargos.IVA_Comision,
		    cargos.SegV,
		    cargos.IVA_SegV,
		    cargos.SegD,
		    cargos.IVA_SegD,
		    cargos.SegB,
		    cargos.IVA_SegB,
		    
		    cargos.SubTipo
		    


		FROM cargos

		WHERE cargos.Num_compra  = '".($this->num_compra)."' 
		AND cargos.Activo      = 'Si' 
		AND (cargos.ID_Concepto = -3 OR cargos.ID_Concepto = -2)

		ORDER BY  cargos.ID_Cargo  ";
   $rs=$this->db->Execute($sql);
   $i =0;

   $this->tabla_amortizacion[$i]['ID_Cargo'] = 0;
   $this->tabla_amortizacion[$i]['Fecha']    = $this->fecha_inicio;
   $this->tabla_amortizacion[$i]['Saldo_Capital'] = $this->capital;
   
   $i++;

if($rs->_numOfRows)
   while(! $rs->EOF)
   {

	
	$this->tabla_amortizacion[$i]['ID_Cargo'] 		= $rs->fields['ID_Cargo'];
	$this->tabla_amortizacion[$i]['Fecha'] 			= $rs->fields['Fecha_vencimiento'];
	
	$this->tabla_amortizacion[$i]['Renta'] 			= $rs->fields['Monto'];
	$this->tabla_amortizacion[$i]['Capital'] 		= $rs->fields['Capital'];
	
	$this->tabla_amortizacion[$i]['Interes'] 		= $rs->fields['Interes'];
	$this->tabla_amortizacion[$i]['IVA_Interes'] 		= $rs->fields['IVA_Interes'];
	
	$this->tabla_amortizacion[$i]['Comision'] 		= $rs->fields['Comision'];
	$this->tabla_amortizacion[$i]['IVA_Comision'] 		= $rs->fields['IVA_Comision'];
	
	$this->tabla_amortizacion[$i]['SegV'] 			= $rs->fields['SegV'];
	$this->tabla_amortizacion[$i]['IVA_SegV'] 		= $rs->fields['IVA_SegV'];
	$this->tabla_amortizacion[$i]['SegD'] 			= $rs->fields['SegD'];
	$this->tabla_amortizacion[$i]['IVA_SegD'] 		= $rs->fields['IVA_SegD'];
	$this->tabla_amortizacion[$i]['SegB'] 			= $rs->fields['SegB'];
	$this->tabla_amortizacion[$i]['IVA_SegB'] 		= $rs->fields['IVA_SegB'];

	$this->tabla_amortizacion[$i]['Cuota_Extra'] 		= $this->cuotas_extra[($rs->fields['Fecha_vencimiento'])]*1;

	$this->tabla_amortizacion[$i]['SubTipo'] 		= $rs->fields['SubTipo'];

	$this->tabla_amortizacion[$i]['Saldo_Capital'] =         $saldo_capital = $this->tabla_amortizacion[($i-1)]['Saldo_Capital'] - $rs->fields['Capital'];
	$this->tabla_amortizacion[$i]['Modficado']     = 0;
	


     $rs->MoveNext();
     $i++;
     
   }



}
//=========================================================================================================================
//
//=========================================================================================================================

function calcula_tabla_modificada()
{  

	$sql =" SELECT SUM(cargos.Comision)
		FROM cargos
		WHERE cargos.ID_Cargo > 0 AND
		      cargos.Num_compra = '".($this->num_compra)."' ";
		      
	   $rs=$this->db->Execute($sql);
	   $comision = $rs->fields[0];

	if(( $comision > 0) and ($this->periodos_gracia != 'Si'))
	{

		error_msg("No es posible por el momento aplicar anticipos a créditos con comisión financiada.");
		die("</BODY></HTML>");
	}



	if($this->metodo == "Saldos Solutos")
  	{
  		$this->recalcula_tabla_solutos();
  	}
  	else
  	{
		if($this->periodos_gracia == 'Si')
		{
			$this->recalcula_tabla_insolutos_con_gracia();	
		}
		else
		{
			$this->recalcula_tabla_insolutos();
		}
  	}


  	$tmp_amortizacion = array();
	// Verifica si hay notas de cargo y las ordena
	if(count($this->notas_cargo)) {
		foreach ($this->notas_cargo as $id_cargo => $fecha) {
			$pos = '';
			$row_cargo = '';
			foreach ($this->tabla_amortizacion as $i => $row) {
				if($row['Fecha'] >= $fecha && $pos == '') {
					$pos = $i;
				} else if ($id_cargo == $row['ID_Cargo']) {
					$row_cargo = $row;
				}
			}
			// Agrega las notas de cargo
			$a = 0;
			$entra = false;
			foreach ($this->tabla_amortizacion as $i => $row) {
				if($i == $pos && !$entra) {
					$row_cargo['Saldo_Capital'] = $this->tabla_amortizacion[$i-1]['Saldo_Capital'];
					$tmp_amortizacion[] = $row_cargo;
					$entra = true;
					$a--;
				} else {
					$tmp_amortizacion[] = $this->tabla_amortizacion[$a];
				}
				$a++;
			}
		}
		$this->tabla_amortizacion = $tmp_amortizacion;
	}

	
	
}
//=========================================================================================================================
//
//=========================================================================================================================

function recalcula_tabla_solutos()
{

     $error = 0;
     $oInsolutos  = new TCUENTA($this->idc,    $this->pago_fecha);

     $row = $oInsolutos->obtener_desgloce_abonos_efectivo();
	if($oInsolutos->aplica_saldos_a_favor_anticipadamente )
	{

	     $monto_anticipo   = (  	  $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['Capital']

					+ $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['Interes']
					+ $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['Interes_IVA']

					+ $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['Comision']
					+ $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['Comision_IVA']

					+ $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['SegV']
					+ $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['SegV_IVA']	

					+ $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['SegB']
					+ $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['SegB_IVA']	

					+ $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['SegD']
					+ $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['SegD_IVA']	

				);
	}
	else
	{
		 foreach($row as $key => $data)
		 {
		   if(($data['Fecha']==$this->pago_fecha) and (abs($data['Monto']) == $this->pago_monto))
		   {
				$monto_anticipo = $row[($this->id_pago)]['SaldoFavor'];
		   		break;
		   }
		 }
		
	}

     $this->monto_anticipo = $monto_anticipo;


    $j=0;

    foreach($this->tabla_amortizacion  AS $i=>$row)
    {


	if((! $aplicado) and ($row['Fecha'] > $this->pago_fecha))
	{	  

		$this->tabla_modificada[$j]['ID_Cargo'] 	= $this->tabla_amortizacion[$i]['ID_Cargo'];	
		$this->tabla_modificada[$j]['Fecha'] 		= $this->pago_fecha;	
		$this->tabla_modificada[$j]['Renta'] 		= abs($monto_anticipo);
		$this->tabla_modificada[$j]['Capital'] 		= abs($monto_anticipo);
		$this->tabla_modificada[$j]['Interes'] 		= 0;
		$this->tabla_modificada[$j]['IVA_Interes'] 	= 0;
		$this->tabla_modificada[$j]['Comision'] 	= 0;
		$this->tabla_modificada[$j]['IVA_Comision'] 	= 0;
		$this->tabla_modificada[$j]['SegV'] 		= 0;
		$this->tabla_modificada[$j]['IVA_SegV'] 	= 0;
		$this->tabla_modificada[$j]['SegD'] 		= 0;
		$this->tabla_modificada[$j]['IVA_SegD'] 	= 0;
		$this->tabla_modificada[$j]['SegB'] 		= 0;
		$this->tabla_modificada[$j]['IVA_SegB'] 	= 0;
		$this->tabla_modificada[$j]['Saldo_Capital'] 	= $this->tabla_amortizacion[($i-1)]['Saldo_Capital'] - abs($monto_anticipo);
		$this->tabla_modificada[$j]['Cuota_Extra']      = 0;
		$this->tabla_modificada[$j]['SubTipo'] 		= 'Capital';

		$this->tabla_modificada[$j]['Modficado']     	= 1;
		
		if(trunc($this->tabla_modificada[$j]['Saldo_Capital'],2) <0)
		{
			$this->error++;
			$this->error_list[]="El monto abonado excede el monto del adeudo.";

		}
		
		
		
		break;
	  
	}
	else
	{
	
		$this->tabla_modificada[$j]['ID_Cargo'] 	= $this->tabla_amortizacion[$i]['ID_Cargo'];
		$this->tabla_modificada[$j]['Fecha'] 		= $this->tabla_amortizacion[$i]['Fecha'];
		$this->tabla_modificada[$j]['Renta'] 		= $this->tabla_amortizacion[$i]['Renta'];
		$this->tabla_modificada[$j]['Capital'] 		= $this->tabla_amortizacion[$i]['Capital'];
		$this->tabla_modificada[$j]['Interes'] 		= $this->tabla_amortizacion[$i]['Interes'];
		$this->tabla_modificada[$j]['IVA_Interes'] 	= $this->tabla_amortizacion[$i]['IVA_Interes'];
		$this->tabla_modificada[$j]['Comision'] 	= $this->tabla_amortizacion[$i]['Comision'];
		$this->tabla_modificada[$j]['IVA_Comision'] 	= $this->tabla_amortizacion[$i]['IVA_Comision'];
		$this->tabla_modificada[$j]['SegV'] 		= $this->tabla_amortizacion[$i]['SegV'];
		$this->tabla_modificada[$j]['IVA_SegV'] 	= $this->tabla_amortizacion[$i]['IVA_SegV'];
		$this->tabla_modificada[$j]['SegD'] 		= $this->tabla_amortizacion[$i]['SegD'];
		$this->tabla_modificada[$j]['IVA_SegD'] 	= $this->tabla_amortizacion[$i]['IVA_SegD'];
		$this->tabla_modificada[$j]['SegB'] 		= $this->tabla_amortizacion[$i]['SegB'];
		$this->tabla_modificada[$j]['IVA_SegB'] 	= $this->tabla_amortizacion[$i]['IVA_SegB'];

		$this->tabla_modificada[$j]['Cuota_Extra'] 	= $this->cuotas_extra[($this->tabla_amortizacion[$i]['Fecha'])]*1;

		$this->tabla_modificada[$j]['Saldo_Capital'] 	= $this->tabla_amortizacion[$i]['Saldo_Capital'];
		$this->tabla_modificada[$j]['Modficado']     	= $this->tabla_amortizacion[$i]['Modficado'];
		$this->tabla_modificada[$j]['SubTipo']     	= $this->tabla_amortizacion[$i]['SubTipo'];



	}
	$j++;
	
	
	
    }

    $saldo_capital = $this->tabla_modificada[$j]['Saldo_Capital'];
    while($saldo_capital >0)
    {
    		$j++;
    		
    		
	
		$this->tabla_modificada[$j]['ID_Cargo'] 	= $this->tabla_modificada[($j-1)]['ID_Cargo']+1; 
		$this->tabla_modificada[$j]['Fecha'] 		= $this->tabla_amortizacion[$i]['Fecha'];

		$this->tabla_modificada[$j]['Renta'] 		= $this->tabla_amortizacion[$i]['Renta'];

		$this->tabla_modificada[$j]['Capital'] 		= $this->tabla_amortizacion[$i]['Capital'];
		$this->tabla_modificada[$j]['Interes'] 		= $this->tabla_amortizacion[$i]['Interes'];
		$this->tabla_modificada[$j]['IVA_Interes'] 	= $this->tabla_amortizacion[$i]['IVA_Interes'];
		$this->tabla_modificada[$j]['Comision'] 	= $this->tabla_amortizacion[$i]['Comision'];
		$this->tabla_modificada[$j]['IVA_Comision'] 	= $this->tabla_amortizacion[$i]['IVA_Comision'];
		$this->tabla_modificada[$j]['SegV'] 		= $this->tabla_amortizacion[$i]['SegV'];
		$this->tabla_modificada[$j]['IVA_SegV'] 	= $this->tabla_amortizacion[$i]['IVA_SegV'];
		$this->tabla_modificada[$j]['SegD'] 		= $this->tabla_amortizacion[$i]['SegD'];
		$this->tabla_modificada[$j]['IVA_SegD'] 	= $this->tabla_amortizacion[$i]['IVA_SegD'];
		$this->tabla_modificada[$j]['SegB'] 		= $this->tabla_amortizacion[$i]['SegB'];
		$this->tabla_modificada[$j]['IVA_SegB'] 	= $this->tabla_amortizacion[$i]['IVA_SegB'];
		$this->tabla_modificada[$j]['SubTipo'] 		= $this->tabla_amortizacion[$i]['SubTipo'];

		$this->tabla_modificada[$j]['Cuota_Extra'] 	= $this->cuotas_extra[($this->tabla_amortizacion[$i]['Fecha'])]*1;


		$this->tabla_modificada[$j]['Saldo_Capital'] 	= $saldo_capital -$this->tabla_amortizacion[$i]['Capital'];
		$this->tabla_modificada[$j]['Modficado']     	= -1;

                
                $i++;
                $saldo_capital = $this->tabla_modificada[$j]['Saldo_Capital'];
 
 
               if($this->tabla_modificada[$j]['Saldo_Capital']<0)
               {
                  $k = ($j-1);
                  $saldo_capital_anterior = $this->tabla_modificada[$k]['Saldo_Capital'];
                  
		  $interes     = trunc(($saldo_capital_anterior * ($this->tasa_periodo/100)),2);

		  $iva_interes = trunc(($interes * ($this->iva_interes/100)),2);
                  
                  $renta = $saldo_capital_anterior +  $interes + $iva_interes;
                  
                  $renta += $this->tabla_modificada[$j]['Comision'];	                  
                  $renta += $this->tabla_modificada[$j]['IVA_Comision'];
                  $renta += $this->tabla_modificada[$j]['SegV'];
                  $renta += $this->tabla_modificada[$j]['IVA_SegV'];
                  $renta += $this->tabla_modificada[$j]['SegD'];
                  $renta += $this->tabla_modificada[$j]['IVA_SegD'];
                  $renta += $this->tabla_modificada[$j]['SegB'];
                  $renta += $this->tabla_modificada[$j]['IVA_SegB'];
                  $renta += $this->tabla_modificada[$j]['Cuota_Extra'];

			$this->tabla_modificada[$j]['Renta'] 		= $renta;
			$this->tabla_modificada[$j]['Capital'] 		= $saldo_capital_anterior;
			$this->tabla_modificada[$j]['Interes'] 		= $interes ;
			$this->tabla_modificada[$j]['IVA_Interes'] 	= $iva_interes;
			$this->tabla_modificada[$j]['Saldo_Capital'] 	= 0;
			$this->tabla_modificada[$j]['SubTipo'] 		= 'General';

			if( empty($this->tabla_modificada[$j]['Fecha']))
			{
				$this->error++;
				$this->error_list[]="La tabla crece en lugar de acortarse.";
			}

               }
    }

   $this->tabla_amortizacion =  $this->tabla_modificada;
   unset($oInsolutos);

}
//=========================================================================================================================
//
//=========================================================================================================================
function recalcula_tabla_insolutos_con_gracia()
{

     $error = 0;
     $oInsolutos  = new TCUENTA($this->idc,    $this->pago_fecha);

     $row = $oInsolutos->obtener_desgloce_abonos_efectivo();
     
     //debug(print_r($row,true));

    if($oInsolutos->aplica_saldos_a_favor_anticipadamente )
    {

     $monto_anticipo   = (  	  $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['Capital']

				+ $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['Interes']
				+ $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['Interes_IVA']

				+ $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['Comision']
				+ $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['Comision_IVA']

				+ $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['SegV']
				+ $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['SegV_IVA']	

				+ $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['SegB']
				+ $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['SegB_IVA']	

				+ $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['SegD']
				+ $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['SegD_IVA']	

			);
	}
	else
	{
		 foreach($row as $key => $data)
		 {
		   if(($data['Fecha']==$this->pago_fecha) and (abs($data['Monto']) == $this->pago_monto))
		   {
				$monto_anticipo = $row[($this->id_pago)]['SaldoFavor'];
		   		break;
		   }
		 }
		
	}


     $this->monto_anticipo = $monto_anticipo;

    $j=0;

    foreach($this->tabla_amortizacion  AS $i=>$row)
    {


	if((! $aplicado) and ($row['Fecha'] > $this->pago_fecha))
	{	  

		$this->tabla_modificada[$j]['ID_Cargo'] 	= $this->tabla_amortizacion[$i]['ID_Cargo'];	
		$this->tabla_modificada[$j]['Fecha'] 		= $this->pago_fecha;	
		$this->tabla_modificada[$j]['Renta'] 		= abs($monto_anticipo);
		$this->tabla_modificada[$j]['Capital'] 		= abs($monto_anticipo);
		$this->tabla_modificada[$j]['Interes'] 		= 0;
		$this->tabla_modificada[$j]['IVA_Interes'] 	= 0;
		$this->tabla_modificada[$j]['Comision'] 	= 0;
		$this->tabla_modificada[$j]['IVA_Comision'] 	= 0;
		$this->tabla_modificada[$j]['SegV'] 		= 0;
		$this->tabla_modificada[$j]['IVA_SegV'] 	= 0;
		$this->tabla_modificada[$j]['SegD'] 		= 0;
		$this->tabla_modificada[$j]['IVA_SegD'] 	= 0;
		$this->tabla_modificada[$j]['SegB'] 		= 0;
		$this->tabla_modificada[$j]['IVA_SegB'] 	= 0;
		$this->tabla_modificada[$j]['Saldo_Capital'] 	= $this->tabla_amortizacion[($i-1)]['Saldo_Capital'] - abs($monto_anticipo);
		$this->tabla_modificada[$j]['SubTipo'] 		= 'Capital';

		
		$this->tabla_modificada[$j]['Modficado']     	= 1;
		
		if(trunc($this->tabla_modificada[$j]['Saldo_Capital'],2)  <0)
		{
			$this->error++;
			$this->error_list[]="El monto abonado excede el monto del adeudo.";

		}
		
		break;
	  
	}
	else
	{
	
		$this->tabla_modificada[$j]['ID_Cargo'] 	= $this->tabla_amortizacion[$i]['ID_Cargo'];
		$this->tabla_modificada[$j]['Fecha'] 		= $this->tabla_amortizacion[$i]['Fecha'];
		$this->tabla_modificada[$j]['Renta'] 		= $this->tabla_amortizacion[$i]['Renta'];
		$this->tabla_modificada[$j]['Capital'] 		= $this->tabla_amortizacion[$i]['Capital'];
		$this->tabla_modificada[$j]['Interes'] 		= $this->tabla_amortizacion[$i]['Interes'];
		$this->tabla_modificada[$j]['IVA_Interes'] 	= $this->tabla_amortizacion[$i]['IVA_Interes'];
		$this->tabla_modificada[$j]['Comision'] 	= $this->tabla_amortizacion[$i]['Comision'];
		$this->tabla_modificada[$j]['IVA_Comision'] 	= $this->tabla_amortizacion[$i]['IVA_Comision'];
		$this->tabla_modificada[$j]['SegV'] 		= $this->tabla_amortizacion[$i]['SegV'];
		$this->tabla_modificada[$j]['IVA_SegV'] 	= $this->tabla_amortizacion[$i]['IVA_SegV'];
		$this->tabla_modificada[$j]['SegD'] 		= $this->tabla_amortizacion[$i]['SegD'];
		$this->tabla_modificada[$j]['IVA_SegD'] 	= $this->tabla_amortizacion[$i]['IVA_SegD'];
		$this->tabla_modificada[$j]['SegB'] 		= $this->tabla_amortizacion[$i]['SegB'];
		$this->tabla_modificada[$j]['IVA_SegB'] 	= $this->tabla_amortizacion[$i]['IVA_SegB'];

		$this->tabla_modificada[$j]['Cuota_Extra'] 	= $this->cuotas_extra[($this->tabla_amortizacion[$i]['Fecha'])]*1;


		$this->tabla_modificada[$j]['Saldo_Capital'] 	= $this->tabla_amortizacion[$i]['Saldo_Capital'];
		$this->tabla_modificada[$j]['Modficado']     	= $this->tabla_amortizacion[$i]['Modficado'];
		$this->tabla_modificada[$j]['SubTipo']     	= $this->tabla_amortizacion[$i]['SubTipo'];

	}
	$j++;
	
	
	
    }

    $saldo_capital = $this->tabla_modificada[$j]['Saldo_Capital'];
    $count= count($this->tabla_amortizacion);

    $sig_cuota = true;
    $fecha_sig_couta = $this->tabla_amortizacion[$j]['Fecha'];
    for($k =$j+1; $k<=$count; $k++)
    {
        $i = $k-1;
        if($sig_cuota) {
            $dias1 = ffdias($this->tabla_modificada[$i-1]['Fecha'], $this->pago_fecha);
            $interes     = trunc(($this->tabla_modificada[$i-1]['Saldo_Capital'] * $dias1 * (($this->tasa_periodo/30)/100)),2);

            $dias2 = ffdias($this->pago_fecha, $fecha_sig_couta);
            if ($dias2 < 0) {
                $dias2 = 0;
            }

            $interes     += trunc(($this->tabla_modificada[$i]['Saldo_Capital'] * $dias2 * (($this->tasa_periodo/30)/100)),2);
            $sig_cuota = false;
        } else {
            $interes     = trunc(($saldo_capital * ($this->tasa_periodo/100)),2);
        }

        $iva_interes = trunc(($interes *       ($this->iva_interes/100)),2);
                
        $abono_capital = ($this->tabla_amortizacion[$i]['Capital']>$saldo_capital)?($saldo_capital):($this->tabla_amortizacion[$i]['Capital']);

        $renta = $interes +
            $iva_interes +
            $abono_capital +
			$this->tabla_amortizacion[$i]['Comision']+
			$this->tabla_amortizacion[$i]['IVA_Comision']+
			$this->tabla_amortizacion[$i]['SegV']+
			$this->tabla_amortizacion[$i]['IVA_SegV']+
			$this->tabla_amortizacion[$i]['SegD']+
			$this->tabla_amortizacion[$i]['IVA_SegD']+
			$this->tabla_amortizacion[$i]['SegB']+
			$this->tabla_amortizacion[$i]['IVA_SegB'];
                         

		$this->tabla_modificada[$k]['ID_Cargo'] 	= $this->tabla_amortizacion[$i]['ID_Cargo'];
		$this->tabla_modificada[$k]['Fecha'] 		= $this->tabla_amortizacion[$i]['Fecha'];

		$this->tabla_modificada[$k]['Renta'] 		= $renta;
		$this->tabla_modificada[$k]['Capital'] 		= $abono_capital;
		$this->tabla_modificada[$k]['Interes'] 		= $interes;
		$this->tabla_modificada[$k]['IVA_Interes'] 	= $iva_interes;

		$this->tabla_modificada[$k]['Comision'] 	= $this->tabla_amortizacion[$i]['Comision'];
		$this->tabla_modificada[$k]['IVA_Comision'] 	= $this->tabla_amortizacion[$i]['IVA_Comision'];
		$this->tabla_modificada[$k]['SegV'] 		= $this->tabla_amortizacion[$i]['SegV'];
		$this->tabla_modificada[$k]['IVA_SegV'] 	= $this->tabla_amortizacion[$i]['IVA_SegV'];
		$this->tabla_modificada[$k]['SegD'] 		= $this->tabla_amortizacion[$i]['SegD'];
		$this->tabla_modificada[$k]['IVA_SegD'] 	= $this->tabla_amortizacion[$i]['IVA_SegD'];
		$this->tabla_modificada[$k]['SegB'] 		= $this->tabla_amortizacion[$i]['SegB'];
		$this->tabla_modificada[$k]['IVA_SegB'] 	= $this->tabla_amortizacion[$i]['IVA_SegB'];

		$this->tabla_modificada[$k]['Cuota_Extra'] 	= $this->cuotas_extra[($this->tabla_amortizacion[$i]['Fecha'])]*1;

		$saldo_capital = $saldo_capital - $this->tabla_modificada[$k]['Capital'];

		$this->tabla_modificada[$k]['Saldo_Capital'] 	= $saldo_capital;
		$this->tabla_modificada[$k]['Modficado']     	= $this->tabla_amortizacion[$i]['Modficado'];
		$this->tabla_modificada[$k]['SubTipo']     	= $this->tabla_amortizacion[$i]['SubTipo'];
   }

   $this->tabla_amortizacion =  $this->tabla_modificada;
   unset($oInsolutos);
   
}


//=========================================================================================================================
//
//=========================================================================================================================

function recalcula_tabla_insolutos()
{
     $error = 0;
     $oInsolutos  = new TCUENTA($this->idc,    $this->pago_fecha);

     $row = $oInsolutos->obtener_desgloce_abonos_efectivo();

    if($oInsolutos->aplica_saldos_a_favor_anticipadamente )
    {

     $monto_anticipo   = (  	  $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['Capital']

				+ $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['Interes']
				+ $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['Interes_IVA']

				+ $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['Comision']
				+ $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['Comision_IVA']

				+ $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['SegV']
				+ $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['SegV_IVA']	

				+ $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['SegB']
				+ $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['SegB_IVA']	

				+ $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['SegD']
				+ $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['SegD_IVA']	

			);

	}
	else
	{
		 foreach($row as $key => $data)
		 {
		   if(($data['Fecha']==$this->pago_fecha) and (abs($data['Monto']) == $this->pago_monto))
		   {
				$monto_anticipo = $row[($this->id_pago)]['SaldoFavor'];
		   		break;
		   }
		 }
	
	}

     $this->monto_anticipo = $monto_anticipo;


    $j=0;

    foreach($this->tabla_amortizacion  AS $i=>$row)
    {


	if((! $aplicado) and ($row['Fecha'] > $this->pago_fecha))
	{	  

		$this->tabla_modificada[$j]['ID_Cargo'] 	= $this->tabla_amortizacion[$i]['ID_Cargo'];	
		$this->tabla_modificada[$j]['Fecha'] 		= $this->pago_fecha;	
		$this->tabla_modificada[$j]['Renta'] 		= abs($monto_anticipo);
		$this->tabla_modificada[$j]['Capital'] 		= abs($monto_anticipo);
		$this->tabla_modificada[$j]['Interes'] 		= 0;
		$this->tabla_modificada[$j]['IVA_Interes'] 	= 0;
		$this->tabla_modificada[$j]['Comision'] 	= 0;
		$this->tabla_modificada[$j]['IVA_Comision'] 	= 0;
		$this->tabla_modificada[$j]['SegV'] 		= 0;
		$this->tabla_modificada[$j]['IVA_SegV'] 	= 0;
		$this->tabla_modificada[$j]['SegD'] 		= 0;
		$this->tabla_modificada[$j]['IVA_SegD'] 	= 0;
		$this->tabla_modificada[$j]['SegB'] 		= 0;
		$this->tabla_modificada[$j]['IVA_SegB'] 	= 0;
		$this->tabla_modificada[$j]['Saldo_Capital'] 	= $this->tabla_amortizacion[($i-1)]['Saldo_Capital'] - abs($monto_anticipo);
		$this->tabla_modificada[$j]['SubTipo'] 		= 'Capital';

		
		$this->tabla_modificada[$j]['Modficado']     	= 1;
		
		if(trunc($this->tabla_modificada[$j]['Saldo_Capital'],2)  <0)
		{
			$this->error++;
			$this->error_list[]="El monto abonado excede el monto del adeudo.";

		}
		
		break;
	  
	}
	else
	{
	
		$this->tabla_modificada[$j]['ID_Cargo'] 	= $this->tabla_amortizacion[$i]['ID_Cargo'];
		$this->tabla_modificada[$j]['Fecha'] 		= $this->tabla_amortizacion[$i]['Fecha'];
		$this->tabla_modificada[$j]['Renta'] 		= $this->tabla_amortizacion[$i]['Renta'];
		$this->tabla_modificada[$j]['Capital'] 		= $this->tabla_amortizacion[$i]['Capital'];
		$this->tabla_modificada[$j]['Interes'] 		= $this->tabla_amortizacion[$i]['Interes'];
		$this->tabla_modificada[$j]['IVA_Interes'] 	= $this->tabla_amortizacion[$i]['IVA_Interes'];
		$this->tabla_modificada[$j]['Comision'] 	= $this->tabla_amortizacion[$i]['Comision'];
		$this->tabla_modificada[$j]['IVA_Comision'] 	= $this->tabla_amortizacion[$i]['IVA_Comision'];
		$this->tabla_modificada[$j]['SegV'] 		= $this->tabla_amortizacion[$i]['SegV'];
		$this->tabla_modificada[$j]['IVA_SegV'] 	= $this->tabla_amortizacion[$i]['IVA_SegV'];
		$this->tabla_modificada[$j]['SegD'] 		= $this->tabla_amortizacion[$i]['SegD'];
		$this->tabla_modificada[$j]['IVA_SegD'] 	= $this->tabla_amortizacion[$i]['IVA_SegD'];
		$this->tabla_modificada[$j]['SegB'] 		= $this->tabla_amortizacion[$i]['SegB'];
		$this->tabla_modificada[$j]['IVA_SegB'] 	= $this->tabla_amortizacion[$i]['IVA_SegB'];

		$this->tabla_modificada[$j]['Cuota_Extra'] 	= $this->cuotas_extra[($this->tabla_amortizacion[$i]['Fecha'])]*1;


		$this->tabla_modificada[$j]['Saldo_Capital'] 	= $this->tabla_amortizacion[$i]['Saldo_Capital'];
		$this->tabla_modificada[$j]['Modficado']     	= $this->tabla_amortizacion[$i]['Modficado'];
		$this->tabla_modificada[$j]['SubTipo']     	= $this->tabla_amortizacion[$i]['SubTipo'];

	}
	$j++;
	
	
	
    }

    $saldo_capital = $this->tabla_modificada[$j]['Saldo_Capital'];

    $sig_cuota = true;
    $fecha_sig_couta = $this->tabla_amortizacion[$j]['Fecha'];

    while($saldo_capital >0)
    {
    		$j++;
                $k = ($j-1);

        if($sig_cuota) {
            $dias1 = ffdias($this->tabla_modificada[$i-1]['Fecha'], $this->pago_fecha);
            $interes     = trunc(($this->tabla_modificada[$i-1]['Saldo_Capital'] * $dias1 * (($this->tasa_periodo/30)/100)),2);

            $dias2 = ffdias($this->pago_fecha, $fecha_sig_couta);
            if ($dias2 < 0) {
                $dias2 = 0;
            }

            $dif_dias_cuota = ffdias($this->tabla_modificada[$i-1]['Fecha'], $fecha_sig_couta);
            
            if($dif_dias_cuota == 31) {
            	$dias2--;
            }

            $interes     += trunc(($this->tabla_modificada[$i]['Saldo_Capital'] * $dias2 * (($this->tasa_periodo/30)/100)),2);
            $sig_cuota = false;
        } else {
            $interes     = trunc(($saldo_capital * ($this->tasa_periodo/100)),2);
        }

        $iva_interes = trunc(($interes *       ($this->iva_interes/100)),2);

        $renta = $this->renta +
                 $this->cuotas_extra[($this->tabla_amortizacion[$i]['Fecha'])]*1;

        $otros_cragos =  $this->tabla_amortizacion[$i]['Comision']
                +$this->tabla_amortizacion[$i]['IVA_Comision']
                +$this->tabla_amortizacion[$i]['SegV']
                +$this->tabla_amortizacion[$i]['IVA_SegV']
                +$this->tabla_amortizacion[$i]['SegD']
                +$this->tabla_amortizacion[$i]['IVA_SegD']
                +$this->tabla_amortizacion[$i]['SegB']
        +$this->tabla_amortizacion[$i]['IVA_SegB'];
				
		$abono_capital =  $renta - $otros_cragos - $interes - $iva_interes;
	
		$this->tabla_modificada[$j]['ID_Cargo'] 	= $this->tabla_modificada[($j-1)]['ID_Cargo']+1; 
		$this->tabla_modificada[$j]['Fecha'] 		= $this->tabla_amortizacion[$i]['Fecha'];
		$this->tabla_modificada[$j]['Renta'] 		= $renta;
		$this->tabla_modificada[$j]['Capital'] 		= $abono_capital ;
		$this->tabla_modificada[$j]['Interes'] 		= $interes;
		$this->tabla_modificada[$j]['IVA_Interes'] 	= $iva_interes;
		$this->tabla_modificada[$j]['Comision'] 	= $this->tabla_amortizacion[$i]['Comision'];
		$this->tabla_modificada[$j]['IVA_Comision'] 	= $this->tabla_amortizacion[$i]['IVA_Comision'];
		$this->tabla_modificada[$j]['SegV'] 		= $this->tabla_amortizacion[$i]['SegV'];
		$this->tabla_modificada[$j]['IVA_SegV'] 	= $this->tabla_amortizacion[$i]['IVA_SegV'];
		$this->tabla_modificada[$j]['SegD'] 		= $this->tabla_amortizacion[$i]['SegD'];
		$this->tabla_modificada[$j]['IVA_SegD'] 	= $this->tabla_amortizacion[$i]['IVA_SegD'];
		$this->tabla_modificada[$j]['SegB'] 		= $this->tabla_amortizacion[$i]['SegB'];
		$this->tabla_modificada[$j]['IVA_SegB'] 	= $this->tabla_amortizacion[$i]['IVA_SegB'];
		
		$this->tabla_modificada[$j]['Cuota_Extra'] 	= $this->cuotas_extra[($this->tabla_amortizacion[$i]['Fecha'])]*1;
		
		
		$this->tabla_modificada[$j]['SubTipo'] 		= $this->tabla_amortizacion[$i]['SubTipo'];
		
		
		$this->tabla_modificada[$j]['Saldo_Capital'] 	= $saldo_capital - $abono_capital;
		$this->tabla_modificada[$j]['Modficado']     	= -1;
                
                $i++;
                $saldo_capital = $this->tabla_modificada[$j]['Saldo_Capital'];
 
           
               if($saldo_capital<0)
               {
			  $k = ($j-1);
			  $saldo_capital_anterior = $this->tabla_modificada[$k]['Saldo_Capital'];

			  $interes     = trunc(($saldo_capital_anterior * ($this->tasa_periodo/100)),2);

			  $iva_interes = trunc(($interes * ($this->iva_interes/100)),2);

			  $renta = ($saldo_capital_anterior +  $interes + $iva_interes);

			  $renta += $this->tabla_modificada[$j]['Comision'];	                  
			  $renta += $this->tabla_modificada[$j]['IVA_Comision'];
			  $renta += $this->tabla_modificada[$j]['SegV'];
			  $renta += $this->tabla_modificada[$j]['IVA_SegV'];
			  $renta += $this->tabla_modificada[$j]['SegD'];
			  $renta += $this->tabla_modificada[$j]['IVA_SegD'];
			  $renta += $this->tabla_modificada[$j]['SegB'];
			  $renta += $this->tabla_modificada[$j]['IVA_SegB'];


			$this->tabla_modificada[$j]['Renta'] 		= $renta;
			$this->tabla_modificada[$j]['Capital'] 		= $saldo_capital_anterior;
			$this->tabla_modificada[$j]['Interes'] 		= $interes ;
			$this->tabla_modificada[$j]['IVA_Interes'] 	= $iva_interes;
			$this->tabla_modificada[$j]['Saldo_Capital'] 	= 0;
			$this->tabla_modificada[$j]['SubTipo'] 		= 'General';
			
			
			if( empty($this->tabla_modificada[$j]['Fecha']))
			{
				$this->error++;
				$this->error_list[]="La tabla crece en lugar de acortarse.";
			}


               }
    }

   $this->tabla_amortizacion =  $this->tabla_modificada;
   unset($oInsolutos);
   
}
//=========================================================================================================================
//
//=========================================================================================================================

function imprime_tabla()
{
    echo "<U><H2 ALIGN='center'> Método : ".$this->metodo."</H2></U><br/>";

	echo "<TABLE ALIGN='center' CELLSPACING=1 CELLPADDING=1 BGCOLOR='gray' ID='small'  WIDTH='1000px' >   \n";

	echo "<TR  bgcolor='lightsteelblue'>  \n";
	echo "        <TH>No.			</TH>  \n";

	echo "        <TH>Fecha 		</TH>  \n";
	echo "        <TH>Concepto 		</TH>  \n";

	echo "        <TH>Cuota			</TH>  \n";

	echo "        <TH>Capital		</TH>  \n";


	echo "        <TH>Interéses             </TH>  \n";
	echo "        <TH>IVA          		</TH>  \n";

	echo "        <TH>Comisión		</TH>  \n";
	echo "        <TH>IVA 			</TH>  \n";

	echo "        <TH>Seg Vida		</TH>  \n";
	echo "        <TH>IVA 			</TH>  \n";

	echo "        <TH>Seg Bienes		</TH>  \n";
	echo "        <TH>IVA 			</TH>  \n";


	echo "        <TH>Seg Desempleo		</TH>  \n";
	echo "        <TH>IVA 		</TH>  \n";




	echo "        <TH>Saldo de Capital	</TH>  \n";

	echo "        <TH>Cuota Extra	</TH>  \n";


	echo "</TR>  \n\n";
	/*
	echo "<pre>";
	print_r($this->tabla_amortizacion);
	echo "</pre>";
	*/

	$id = 1;
	foreach($this->tabla_amortizacion  AS $i=>$row)
	{
		
		$style = "";




		switch($row['Modficado']*1)
		{
			case -1 : $color = ("#FFFFCC");		
				  break;
			case 0  : $color = ("#FFFFCC");
			 	  break;			
			case 1  : $color = ("#FFCC99");		
			          $style = " STYLE='font-weight:bold;' ";			       
			          break;
		}
		
		if($row['SubTipo'] == "Capital") 
		{
			$color = ("#FFCC99");	
		}
		else
		{
			if($row['Cuota_Extra']>0) $color = ("yellow");	
		}
		
		
		
		

		$seguros     = ($row['SegV']		+ $row['SegD']		+ $row['SegB'] );

		$iva_seguros = ($row['IVA_SegV']	+ $row['IVA_SegD']	+ $row['IVA_SegB'] );







		if( trunc($row['Renta'],2) > 0)
		{

			echo " <TR Align='right'  BGCOLOR='".$color."' ". $style.">\n";
			
			if($row['SubTipo'] == "General") {
				echo "         <TH Align='right'> ".$id.")&nbsp;</TH>  \n";
				$id++;
			}
				
			else
				echo "         <TH Align='right'>&nbsp;</TH>  \n";

			echo "        <TD Align='center'> ".ffecha($row['Fecha']	  )."</TD> \n";
			echo "        <TD Align='left'  > ".($row['SubTipo']	   	  )."</TD> \n";

			echo "        <TD>".number_format($row['Renta']			,2)."</TD> \n";
			echo "        <TD>".number_format($row['Capital']		,2)."</TD> \n";

			echo "        <TD>".number_format($row['Interes']		,2)."</TD> \n";
			echo "        <TD>".number_format($row['IVA_Interes']		,2)."</TD> \n";

			echo "        <TD>".number_format($row['Comision']		,2)."</TD> \n";
			echo "        <TD>".number_format($row['IVA_Comision']		,2)."</TD> \n";

//			echo "        <TD>".number_format($seguros			,2)."</TD> \n";      
//			echo "        <TD>".number_format($iva_seguros			,2)."</TD> \n";


			echo "        <TD>".number_format($row['SegV']			,2)."</TD> \n";
			echo "        <TD>".number_format($row['IVA_SegV']		,2)."</TD> \n";

			echo "        <TD>".number_format($row['SegB']			,2)."</TD> \n";
			echo "        <TD>".number_format($row['IVA_SegB']		,2)."</TD> \n";

			echo "        <TD>".number_format($row['SegD']			,2)."</TD> \n";
			echo "        <TD>".number_format($row['IVA_SegD']		,2)."</TD> \n";

			echo "        <TH>".number_format($row['Saldo_Capital']		,2)."</TH> \n";

			echo "        <TD><EM>".number_format($row['Cuota_Extra']	,2)."</EM></TD> \n";


			echo " </TR>\n\n";



			$tot_abono_capital	+=	$row['Capital']		;     

			$tot_abono_comision	+=	$row['Comision']	;      
			$tot_abono_iva_comision	+=	$row['IVA_Comision']	;  

			$tot_abono_interes	+=	$row['Interes']		;       
			$tot_abono_iva_interes	+=	$row['IVA_Interes']	;   

			$tot_abono_seguros	+=	$seguros		; 		
			$tot_abono_iva_seguros	+=	$iva_seguros		; 

			$tot_abono_segv		+=	$row['SegV']		;		
			$tot_abono_iva_segv	+=	$row['IVA_SegV']	;

			$tot_abono_segb		+=	$row['SegB']		;		
			$tot_abono_iva_segb	+=	$row['IVA_SegB']	;

			$tot_abono_segd		+=	$row['SegD']		;		
			$tot_abono_iva_segd	+=	$row['IVA_SegD']	;






			$tot_abonos		+=	$row['Renta']		;              
				 
 		}
 
      
	}


	echo "<TR  ALIGN='right' BGCOLOR='white' >\n";
	echo "        <TH  ALIGN='center' COLSPAN='3' STYLE='border:1px solid black;'> Subtotal </TH>\n";

	echo "        <TH ALIGN='right' STYLE='border:1px solid black;'>".number_format($tot_abonos,			2)."</TH>\n";

	echo "        <TH ALIGN='right' STYLE='border:1px solid black;'>".number_format($tot_abono_capital,		2)."</TH>\n";

	echo "        <TH ALIGN='right' STYLE='border:1px solid black;'>".number_format($tot_abono_interes,		2)."</TH>\n";
	echo "        <TH ALIGN='right' STYLE='border:1px solid black;'>".number_format($tot_abono_iva_interes,		2)."</TH>\n";

	echo "        <TH ALIGN='right' STYLE='border:1px solid black;'>".number_format($tot_abono_comision,		2)."</TH>\n";
	echo "        <TH ALIGN='right' STYLE='border:1px solid black;'>".number_format($tot_abono_iva_comision,	2)."</TH>\n";

//	echo "        <TH ALIGN='right' STYLE='border:1px solid black;'>".number_format($tot_abono_seguros,		2)."</TH>\n";
//	echo "        <TH ALIGN='right' STYLE='border:1px solid black;'>".number_format($tot_abono_iva_seguros,		2)."</TH>\n";

 	echo "        <TH ALIGN='right' STYLE='border:1px solid black;'>".number_format($tot_abono_segv,		2)."</TH>\n";
	echo "        <TH ALIGN='right' STYLE='border:1px solid black;'>".number_format($tot_abono_iva_segv,		2)."</TH>\n";

	echo "        <TH ALIGN='right' STYLE='border:1px solid black;'>".number_format($tot_abono_segb,		2)."</TH>\n";
	echo "        <TH ALIGN='right' STYLE='border:1px solid black;'>".number_format($tot_abono_iva_segb,		2)."</TH>\n";

	echo "        <TH ALIGN='right' STYLE='border:1px solid black;'>".number_format($tot_abono_segd,		2)."</TH>\n";
	echo "        <TH ALIGN='right' STYLE='border:1px solid black;'>".number_format($tot_abono_iva_segd,		2)."</TH>\n";





	echo "        <TH COLSPAN=2></TH>\n";

	echo "</TR>\n\n";



	echo "</TABLE> \n";
	echo "</FIELDSET>\n";
	echo "</TD>    \n";
	echo "</TR>    \n";

	echo "</TABLE> \n";

	echo "<BR/> \n";
	echo "<BR/> \n";
	echo "<BR/> \n";
	echo "<BR/> \n";



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
          case 'Anios' : 
                         $tipo_plazox = "anual";
                         
                   break;
          // Semestral         
          case 'Semestres' : 
                           $tipo_plazox = "semestral";
                          
                   break;
         // Trimestral          
          case 'Trimestres' :
                           $tipo_plazox = "trimestral";   
                          
                   break;
         // Bimestral          
          case 'Bimestres' : 
                           $tipo_plazox = "bimestral";   
                           
                   break; 
          // Mensual
          case 'Meses' : 
                           $tipo_plazox = "mensual";   
                        
                   break;
          //Quincenal 
          case 'Quincenas' : 
                           $tipo_plazox = "quincenal";   
                          
                   break;
                   
          //Semanal                   
          case 'Semanas' : 
                           $tipo_plazox = "semanal";    
                           
                   break;
           //Diaria                   
          
          case 'Dias' :
                           $tipo_plazox = "diaria";     
                       
                   break;
          
          //Catorcenal                    
          case 'Catorcenas' :    
          	$tipo_plazox = "catorcenal";     
                   break;
        };



	$sql =" INSERT IGNORE INTO anticipo_capital_log
		  (ID_Credito, ID_Pago, Fecha, Monto, Usuario)
		  VALUES
		  ('".$this->idc."','".$this->id_pago."','".$this->pago_fecha."','".abs($this->monto_anticipo)."','".$_SESSION['NOM_USR']."') ";

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
		FROM cargos

		WHERE cargos.Num_compra = '".$this->num_compra."' and
		      cargos.Activo = 'Si' and
		      cargos.ID_Concepto = -3 OR cargos.ID_Concepto = -2

		ORDER BY  cargos.ID_Cargo 
	      ) ";  

	$this->db->Execute($sql);
	
	$sql =" SELECT COUNT(ID_Anticipo)
		FROM anticipo_capital_cargos
		WHERE ID_Anticipo =  '".$this->id_anticipo."' ";

	$rs = $this->db->Execute($sql);
	
	if($rs->fields[0] == 0)
	{
		$this->error++;
		$this->error_list[]="No fue posible aplicar respaldo en la bitácora.";
		return;
	}


	$sql =" DELETE FROM cargos
		WHERE cargos.Num_compra  	= '".$this->num_compra."' and
		      cargos.Fecha_Vencimiento > '".$this->pago_fecha."' and
		      cargos.ID_Concepto 	= -3 ";

	$this->db->Execute($sql);


	$zql ="	SELECT MAX(cargos.ID_Cargo) AS New_ID_Cargo
		FROM cargos
		WHERE cargos.Num_compra  = '".$this->num_compra."' and
		      cargos.ID_Concepto = -3 ";				
	$rz = $this->db->Execute($zql);
	$ID_CARGO = $rz->fields[0];







	$sql="REPLACE INTO cargos
		(ID_Cargo, ID_Concepto, Num_compra, Num_factura, Fecha_vencimiento, Monto, Capital, Interes, IVA_Interes, Comision, IVA_Comision, 
		     SegV, IVA_SegV, 
		     SegD, IVA_SegD, 
		     SegB, IVA_SegB, 
		     
		     IVA, 
		     
		     Cuota_Extra, Activo, SubTipo, Concepto)
	     
	     VALUES  \n";
	     
    
        $i=0;

	foreach($this->tabla_amortizacion AS $row)
	{
		if(($row['ID_Cargo']>0) and (trunc($row['Renta'],2)>0))
		{
			if($row['SubTipo'] == "General")
			{
				$i++;
			}

			if(($row['Fecha'] > $this->pago_fecha ) or ( $row['Modficado']== 1))
			{

				$sql.= chr(13);
				
				$ID_CARGO++;

				if(in_array($ID_CARGO, $this->id_notas_cargo)) {
					$ID_CARGO++;
				}

				
				
				$sql.="('".$ID_CARGO."','-3','".($this->num_compra)."','".$this->idc."',";
				$sql.= "'".($row['Fecha'])."',"; 			
				$sql.= "'".($row['Renta']*1)."',";  			
				$sql.= "'".($row['Capital']*1)."',";  		

				$sql.= "'".($row['Interes']*1)."',";  		
				$sql.= "'".($row['IVA_Interes']*1)."',";  	

				$sql.= "'".($row['Comision']*1)."',";  		
				$sql.= "'".($row['IVA_Comision']*1)."',"; 	

				$sql.= "'".($row['SegV']*1)."',";  			
				$sql.= "'".($row['IVA_SegV']*1)."',";  		

				$sql.= "'".($row['SegD']*1)."',";  			
				$sql.= "'".($row['IVA_SegD']*1)."',";  		

				$sql.= "'".($row['SegB']*1)."',";  			
				$sql.= "'".($row['IVA_SegB']*1)."',";  	
				

				$iva = 	  $this->tabla_amortizacion[$i]['IVA_Interes'] 	
					+ $this->tabla_amortizacion[$i]['IVA_Comision'] 	
					+ $this->tabla_amortizacion[$i]['IVA_SegV'] 	
					+ $this->tabla_amortizacion[$i]['IVA_SegD'] 	
					+ $this->tabla_amortizacion[$i]['IVA_SegB'] ;

				$sql.= "'".($iva * 1)."',"; 			
				$sql.= "'".($row['Cuota_Extra']*1)."',";  
				$sql.= "'Si', ";			
				$sql.= "'".$row['SubTipo']."', ";		
				
				

				if($row['SubTipo'] == "General")
				{
					$concepto = "Cuota ".$tipo_plazox." no. ".$i." de ".$this->plazo;
				}
				else
				{
					$concepto = "Anticipo a capital";			
				}
				$sql.= "'".$concepto."' ),";			
			}
		}
	
	}

        $len = strlen($sql) - 1;
	$sqlend = substr($sql,0, $len);
	
	$this->db->Execute($sqlend);
	
	
	//debug($sqlend);
	
	$sql= " UPDATE pagos SET pagos.Subtipo = 'Capital' WHERE pagos.ID_Pago = '".$this->id_pago."' ";
	$this->db->Execute($sql);
	
	return(0);


}



};

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
	INDEX `ID_Credito` (`ID_Credito`),
	INDEX `ID_Pago` (`ID_Pago`),
	UNIQUE INDEX `Unicidad` (`ID_Credito`, `ID_Pago`)
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
)
COLLATE='latin1_swedish_ci'
ENGINE=MyISAM;

*/

?>