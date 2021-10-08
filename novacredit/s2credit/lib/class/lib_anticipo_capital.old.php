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

require_once($class_path."lib_credit.php");

class T_ANTICIPO_CAPITAL
{

var $idc;
var $id_pago;
var $db;
var $fecha_ajuste;


var $error = 0;
var $error_list = array();

var $alerta = 0;
var $alerta_list = array();

var $tabla_amortizacion = array();
var $tabla_modificada = array();

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


	if($this->alerta>0)
	{  
	        $alert_msg = "";
	        
		foreach($this->alerta_list as $txt)
		{
			$alert_msg .= "<LI STYLE='color:black;'>".$txt."</LI>";
		}
		
		info_msg("Existen Alertas : <OL >".$alert_msg."</OL>");
	
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
                      cargos.Fecha_vencimiento



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


     $sql= "SELECT  pagos.Monto,
		    pagos.Fecha
	     FROM   pagos
	     WHERE  pagos.ID_Pago = '".$this->id_pago."' "; 		

      $rs=$this->db->Execute($sql);

     $this->pago_monto = $rs->fields['Monto'];
     $this->pago_fecha = $rs->fields['Fecha'];






     $sql= " SELECT cargos.ID_Cargo,
		    cargos.Fecha_vencimiento,
		    cargos.Monto,
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

		WHERE cargos.Num_compra  = '".($this->num_compra)."' and
		      cargos.Activo      = 'Si' and
		      cargos.ID_Concepto = -3

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
  if($this->metodo == "Saldos Solutos")
  {
  	$this->recalcula_tabla_solutos();
  }
  else
  {
  	$this->recalcula_tabla_insolutos();
  }  
}
//=========================================================================================================================
//
//=========================================================================================================================

function recalcula_tabla_solutos()
{

     $error = 0;

/*
     $oInsolutos  = new TCUENTA($this->idc,    $this->pago_fecha);

     $row = $oInsolutos->obtener_desgloce_abonos_efectivo();

 	
     $monto_anticipo   = (  $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['Capital']       
		          + $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['Interes']       
		          + $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['Interes_IVA']      
		          + $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['Comision']      
		          + $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['Comision_IVA']  );


     $this->monto_anticipo = $monto_anticipo;


*/    
     
     $monto_anticipo = $this->pago_monto;
     $this->monto_anticipo = $monto_anticipo;

    $j=0;



    $this->tasa_eqi = $this->calcula_tasa_equivalente();
    
    

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
		
		if(trunc($this->tabla_modificada[$j]['Saldo_Capital'],2) <0)
		{
			//$this->error++;
			$this->alerta++;
			$this->alerta_list[]="El monto abonado excede el monto del adeudo.";

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


		$this->tabla_modificada[$j]['Saldo_Capital'] 	= $saldo_capital -$this->tabla_amortizacion[$i]['Capital'];
		$this->tabla_modificada[$j]['Modficado']     	= -1;

                
                $i++;
                $saldo_capital = $this->tabla_modificada[$j]['Saldo_Capital'];
 
 
               if($this->tabla_modificada[$j]['Saldo_Capital']<0)
               {
                  $k = ($j-1);
                  $saldo_capital_anterior = $this->tabla_modificada[$k]['Saldo_Capital'];
                  
		  $interes     = trunc(($saldo_capital_anterior * ($this->tasa_eqi/100)),2);

		  $iva_interes = trunc(($interes * (fact_cliente.IVA_Interes/100)),2);
                  
                  $renta = $saldo_capital_anterior +  $interes + $iva_interes;
                  
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
}
//=========================================================================================================================
//
//=========================================================================================================================

function recalcula_tabla_insolutos()
{

     $error = 0;
/*
     $oInsolutos  = new TCUENTA($this->idc,    $this->pago_fecha);
     
     



     $row = $oInsolutos->obtener_desgloce_abonos_efectivo();
	
     $monto_anticipo   = (  $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['Capital']       
		          + $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['Interes']       
		          + $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['Interes_IVA']      
		          + $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['Comision']      
		          + $oInsolutos->abonos_contra_saldos_vigentes_por_id_pago[($this->id_pago)]['Comision_IVA']  );

     $this->monto_anticipo = $monto_anticipo;
*/

     $this->tasa_eqs = $this->calcula_tasa_equivalente();


     $monto_anticipo = $this->pago_monto;
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
		$this->tabla_modificada[$j]['SubTipo'] 		= 'General';

		
		$this->tabla_modificada[$j]['Modficado']     	= 1;
/*		
		if(trunc($this->tabla_modificada[$j]['Saldo_Capital'],2)  <0)
		{
			$this->error++;
			$this->error_list[]="El monto abonado excede el monto del adeudo.";

		}
*/

		if(trunc($this->tabla_modificada[$j]['Saldo_Capital'],2)  <0)
		{


			$pico = trunc(abs($this->tabla_modificada[$j]['Saldo_Capital']),2);

			//$this->error++;
			$this->alerta++;
			$this->alerta_list[]="El monto abonado excede el monto del adeudo.<br> Se ajustáron los intereses por $".number_format($pico,2).".";



			
			
			//debug($pico);
			
			$this->tabla_modificada[$j]['Saldo_Capital'] = 0;
			
			
			$pIVA =   getIVA($oInsolutos->zona_iva, $this->pago_fecha, $oInsolutos->db); 
			
			$_interes_ 	= trunc(($pico/(1+$pIVA)),2);
			$_iva_interes_	= ($pico - $_interes_);
			
			$this->tabla_modificada[$j]['Capital'] 		-= $pico	;
			$this->tabla_modificada[$j]['Interes'] 		= $_interes_ 	;
			$this->tabla_modificada[$j]['IVA_Interes'] 	= $_iva_interes_;
			
			
			
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
		$this->tabla_modificada[$j]['Saldo_Capital'] 	= $this->tabla_amortizacion[$i]['Saldo_Capital'];
		$this->tabla_modificada[$j]['Modficado']     	= $this->tabla_amortizacion[$i]['Modficado'];
		$this->tabla_modificada[$j]['SubTipo']     	= $this->tabla_amortizacion[$i]['SubTipo'];

	}
	$j++;
	
	
	
    }

    $saldo_capital = $this->tabla_modificada[$j]['Saldo_Capital'];
    
   // debug($saldo_capital);
    
    while($saldo_capital >0)
    {
    		$j++;
                $k = ($j-1);
                
                
                $interes     = trunc(($saldo_capital * ($this->tasa_eqs/100)),2);
                
               
                $iva_interes = trunc(($interes *       ($this->iva_interes/100)),2);
                
                $renta = $this->renta;
                
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
		$this->tabla_modificada[$j]['SubTipo'] 		= $this->tabla_amortizacion[$i]['SubTipo'];
		
		
		$this->tabla_modificada[$j]['Saldo_Capital'] 	= $saldo_capital - $abono_capital;
		$this->tabla_modificada[$j]['Modficado']     	= -1;

                
                $i++;
                $saldo_capital = $this->tabla_modificada[$j]['Saldo_Capital'];
 
           
               if($saldo_capital<0)
               {
			  $k = ($j-1);
			  $saldo_capital_anterior = $this->tabla_modificada[$k]['Saldo_Capital'];

			  $interes     = trunc(($saldo_capital_anterior * ($this->tasa_eqs/100)),2);

			  $iva_interes = trunc(($interes * (fact_cliente.IVA_Interes/100)),2);

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
	echo "        <TH>IVA  Interéses        </TH>  \n";

	echo "        <TH>Comisión		</TH>  \n";
	echo "        <TH>IVA Comisión		</TH>  \n";

	echo "        <TH>Seguros		</TH>  \n";
	echo "        <TH>IVA Seguros		</TH>  \n";

	echo "        <TH>Saldo de Capital	</TH>  \n";
	echo "</TR>  \n\n";


	foreach($this->tabla_amortizacion  AS $i=>$row)
	{
		$style = "";
		switch($row['Modficado']*1)
		{
			case -1 : $color = ("#FFFFCC");		break;
			case 0  : $color = ("#FFFFCC");		break;
			case 1  : $color = ("#FFCC99");		
			          $style = " STYLE='font-weight:bold;' ";
			       
			        break;
		}
		
		if($row['SubTipo'] == "Capital")$color = ("#FFCC99");	
		
		
		
		

		$seguros     = ($row['SegV']		+ $row['SegD']		+ $row['SegB'] );

		$iva_seguros = ($row['IVA_SegV']	+ $row['IVA_SegD']	+ $row['IVA_SegB'] );





		if($row['ID_Cargo'] == 0)
		{
		
			echo " <TR Align='right'  BGCOLOR='".$color."'>  \n";

			echo "        <TH Align='right'> ".($row['ID_Cargo']).")&nbsp;</TH>  \n";

			echo "        <TD Align='center'> ".ffecha($row['Fecha'])."</TD>  \n";
			echo "        <TD></TD>  \n";

			echo "        <TD></TD>  \n";
			echo "        <TD></TD>  \n";;

			echo "        <TD></TD>  \n";
			echo "        <TD></TD>  \n";

			echo "        <TD></TD>  \n";
			echo "        <TD></TD>  \n";

			echo "        <TD></TD>  \n";
			echo "        <TD></TD>  \n";

			echo "        <TH Align='right' >".number_format($row['Saldo_Capital']		,2)."</TH>  \n";

			echo " </TR>\n\n";
		
				
			continue;
		
		}





		if( trunc($row['Renta'],2) > 0)
		{

			echo " <TR Align='right'  BGCOLOR='".$color."' ". $style.">\n";

			echo "         <TH Align='right'> ".($row['ID_Cargo']).")&nbsp;</TH>  \n";

			echo "        <TD Align='center'> ".ffecha($row['Fecha']	  )."</TD> \n";
			echo "        <TD Align='left'  > ".($row['SubTipo']	   	  )."</TD> \n";

			echo "        <TD>".number_format($row['Renta']			,2)."</TD> \n";
			echo "        <TD>".number_format($row['Capital']		,2)."</TD> \n";

			echo "        <TD>".number_format($row['Interes']		,2)."</TD> \n";
			echo "        <TD>".number_format($row['IVA_Interes']		,2)."</TD> \n";

			echo "        <TD>".number_format($row['Comision']		,2)."</TD> \n";
			echo "        <TD>".number_format($row['IVA_Comision']		,2)."</TD> \n";

			echo "        <TD>".number_format($seguros			,2)."</TD> \n";      
			echo "        <TD>".number_format($iva_seguros			,2)."</TD> \n";

			echo "        <TH>".number_format($row['Saldo_Capital']		,2)."</TH> \n";

			echo " </TR>\n\n";



			$tot_abono_capital	+=	$row['Capital']		;     

			$tot_abono_comision	+=	$row['Comision']	;      
			$tot_abono_iva_comision	+=	$row['IVA_Comision']	;  

			$tot_abono_interes	+=	$row['Interes']		;       
			$tot_abono_iva_interes	+=	$row['Interes_IVA']	;   

			$tot_abono_seguros	+=	$seguros		; 		
			$tot_abono_iva_seguros	+=	$iva_seguros		; 

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

	echo "        <TH ALIGN='right' STYLE='border:1px solid black;'>".number_format($tot_abono_seguros,		2)."</TH>\n";
	echo "        <TH ALIGN='right' STYLE='border:1px solid black;'>".number_format($tot_abono_iva_seguros,		2)."</TH>\n";

	echo "        <TH></TH>\n";

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

























	$sql =" INSERT INTO anticipo_capital_log
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
		      cargos.ID_Concepto = -3

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
		(ID_Cargo, ID_Concepto, Num_compra, Num_factura, Fecha_vencimiento, Monto, Capital, Interes, IVA_Interes, Comision, IVA_Comision, SegV, IVA_SegV, SegD, IVA_SegD, SegB, IVA_SegB, IVA, Activo, SubTipo, Concepto)
	     VALUES  \n";
	     
    
        $i=0;
        $num_rows = 0;

	foreach($this->tabla_amortizacion AS $row)
	{
	

		if($row['SubTipo'] == "General")
		{
			$i++;
		}


		if(($row['ID_Cargo']>0) and (trunc($row['Renta'],2)>0))
		{


			if(($row['Fecha'] > $this->pago_fecha ) or ( $row['Modficado']== 1))
			{
				$num_rows++;
				$sql.= chr(13);
				
				$ID_CARGO++;
				
				
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
	
	if($num_rows > 0)
	{
		$this->db->Execute($sqlend);
	}
	//debug($sqlend);
	
	$sql= " UPDATE pagos SET pagos.Subtipo = 'Capital' WHERE pagos.ID_Pago = '".$this->id_pago."' ";
	$this->db->Execute($sql);
	
	return(0);


}
//=========================================================================================================================
//
//=========================================================================================================================

function calcula_tasa_equivalente()
{

        $tasa_eq = 0;

	switch($this->vencimiento)
	{

		case "Anios"		: $tasa_eq = 12*$this->tasa_nominal;
					break;

		case "Semestres"	: $tasa_eq = 6*$this->tasa_nominal;
					break;

		case "Trimestres"	: $tasa_eq = 3*$this->tasa_nominal;
					break;

		case "Bimestres"	: $tasa_eq = 2*$this->tasa_nominal;
					break;

		case "Meses"		: $tasa_eq = $this->tasa_nominal;
					break;

		case "Quincenas"	: $tasa_eq = 15*($this->tasa_nominal/30);
					break;

		case "Catorcenas"	: $tasa_eq = 14*($this->tasa_nominal/30);
					break;

		case "Semanas"		: $tasa_eq = 7*($this->tasa_nominal/30);
					break;

		case "Dias"		: $tasa_eq = 1*($this->tasa_nominal/30);
					break;
	}


	return( $tasa_eq);
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