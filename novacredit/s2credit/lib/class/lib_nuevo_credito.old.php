<?
require_once($class_path."lib_credit.php");
require_once($DOCUMENT_ROOT.$sys_path."sucursal/tesoreria/cheques_credito_lib.php");
require_once($class_path."lib_pld.php");
/*
ALTER TABLE `cat_productosfinancieros`
	CHANGE COLUMN `Gastos_Cobranza_Porcentaje` `Gastos_Cobranza_Porcentaje` DOUBLE(12,8) UNSIGNED NOT NULL DEFAULT '0.00' AFTER `MoratorioMinimo`;

ALTER TABLE `cat_productosfinancieros`
	CHANGE COLUMN `Comision_Apertura` `Comision_Apertura` DOUBLE(15,8) UNSIGNED NOT NULL DEFAULT '0.00' AFTER `Capital_Max`;

*/


class TNuevoCredito 
{


  var $db;
  var $num_cliente;
  var $nombre_cliente;

  var $id_producto;                             // Producto financiero
  var $metodo;                                  // Método de amortización( Saldos Solutos, Insoluto Tasa equivalente, Insoluto Pago equivalente...)
  
  var $tasa_moratoria;
  
  var $tasa_mensual_ssi;
  var $tasa_periodo_ssi;
  
  
  var $tasa_mensual_ssol;
  var $tasa_periodo_ssol;
  
  
  var $no_generar = 0;  // Cuando hay un error crazo a la hora de cotizar el crédito, se prende este semaforo que impide la generación real del credito
  
  var $comision_apertura;
  var $comision_tipo;
  var $comision_calculo;
  
  
  var $monto_comision_capitalizada =0;
  var $monto_comision_diferida     = 0;
  var $monto_comision_anticipada   = 0;
  var $monto_comision_apertura     = 0;
 
  
  var $nombre_sucursal;
  
  var $iva_general;
  
  var $id_empresa = 0;  //ID de la empresa si es que el crédito es individual y de tipo nómina
  
  
  var $iva_interes_interes;
  var $iva_interes_moratorio;
  var $iva_interes_comision;                    
  var $dias_gracia;

  var $capital;
  var $renta;
  var $tasa_nominal;
  var $periodo;                 // Meses Dias Años...
  var $tipo_plazox;             // mensual, diario, anual...
  var $frecuencia;
  
  var $plazo;                                   // Numero total de amortizaciones de pagos
  var $dias_periodo; 
  var $tasa_golbal_efectiva;
  
  var $tabla_amortizacion = array();
  
  var $fecha_inicio;
  var $primer_vencimiento;            // Permite consultar cual será la fecha de inicio 
  var $ultimo_vencimiento;
  
  var $fecha_para_primer_vencimiento; // Parametro mandatorio. Cuando se establece (en formato (YYYY-mm-dd) hará que 
                                     // el primer vencimiento caiga en ésta fecha independientemente de la fecha de inicio 
  
  
  var $cat;
  var $tasa_moratorio;
  var $diasa_gracia;

  var $id_factura;
  var $id_fondeo;
  var $cargostotales;
  var $fecha_apertura;

  var $fecha_ultimo_abono;
  var $numcompra;
  
  
  var $segv_anual;
  var $segd_anual;
  var $segb_anual;
  


  var $segv_total;
  var $segd_total;
  var $segb_total;



  
  var $cuota_segv;
  var $cuota_segd;
  var $cuota_segb;
  
  
  var $permite_creditos_simultaneos  = false;
    
  
//---------------------------------------------------------------------------------------------------------------------
//
//---------------------------------------------------------------------------------------------------------------------

function TNuevoCredito($num_cliente, $fecha_apertura, $fecha_inicio, $capital, $id_producto, $plazo, &$db, $id_sucursal=-1, $fecha_para_primer_vencimiento="" )  // Constructor
{
        
        $this->db = $db;
                                           


        $sql = "SELECT sucursales.Nombre, 
                       sucursales.IVA_General,
                       clientes_datos.ID_Sucursal 
                FROM   clientes_datos, sucursales
                WHERE  sucursales.ID_Sucursal      = clientes_datos.ID_Sucursal and
                       clientes_datos.Num_cliente  = '".$num_cliente."' "; 
                       



        $rs = $this->db->Execute($sql);
        if(!$rs->_numOfRows)
        {
                $this->no_generar=1;
        }
        
        
        

        $this->nombre_sucursal           = $rs->fields[0];
        $this->iva_general               = $rs->fields['IVA_General']/100;
        $this->iva_interes               = $this->iva_general;
        $this->iva_comision              = $this->iva_general;  
        
        
        if(!empty($fecha_para_primer_vencimiento))
        {
        	list($yy,$mm,$dd) = explode("-",$fecha_para_primer_vencimiento);
        
        	$fecha_para_primer_vencimiento = date("Y-m-d",mktime(0,0,0,$mm,$dd,$yy));
        	
        	if($fecha_para_primer_vencimiento >= $fecha_inicio)
        		$this->fecha_para_primer_vencimiento = $fecha_para_primer_vencimiento;
        
	}
        
        if(($id_sucursal==-1) or (empty($id_sucursal)))
        {
                $this->id_sucursal    = $rs->fields['ID_Sucursal'];
        }
        else
        {
                $this->id_sucursal    = $id_sucursal;


                $sql = "SELECT sucursales.Nombre, 
                               sucursales.IVA_General,
                               sucursales.ID_Sucursal 
                        FROM   sucursales
                        WHERE  sucursales.ID_Sucursal      =  '".$this->id_sucursal."' "; 
                $rs = $this->db->Execute($sql);
                
                $this->nombre_sucursal           = $rs->fields[0];
                $this->iva_general               = $rs->fields['IVA_General']/100;
                $this->iva_interes               = $this->iva_general;
                $this->iva_comision              = $this->iva_general;  
        }
        
        


        $sql = "SELECT  CONCAT(clientes_datos.Ap_Paterno,' ',clientes_datos.Ap_Materno,' ',clientes_datos.Nombre,' ',clientes_datos.NombreI) AS NombreCliente,
                       solicitud.ID_empresa
                FROM   clientes, clientes_datos
                LEFT JOIN solicitud ON solicitud.ID_Solicitud = clientes_datos.ID_Solicitud
                WHERE  clientes.Num_cliente = clientes_datos.Num_cliente and clientes.Num_cliente  = '".$num_cliente."' "; 








        $rs = $this->db->Execute($sql);
        $this->id_producto     = $id_producto;
        $this->id_empresa      = $rs->fields['ID_empresa'];
        $this->nombre_cliente  = $rs->fields['NombreCliente'];
        $this->num_cliente     = $num_cliente;
        $this->capital            = $capital;
                

            list($anioz,$mesz,$diaz) = split("-",$fecha_apertura);
            $this->fecha_apertura  = date("Y-m-d",mktime(0,0,0,$mesz,$diaz,$anioz));


            list($aniox,$mesx,$diax) = split("-",$fecha_inicio);

        //      if($diax > 28)   $diax = 28;
        

            $this->fecha_inicio  = date("Y-m-d",mktime(0,0,0,$mesx,$diax,$aniox));


        
        if(empty($plazo))
        {
                $sql="  SELECT Plazo_Minimo
                        FROM cat_productosfinancieros
                        WHERE ID_Producto = '".$id_producto."' ";
                $rs = $db->Execute($sql);
                $this->plazo = $rs->fields['Plazo_Minimo'];
                
        }
        else
        {
        

                $sql="  SELECT IF((".(1*$plazo).") <=Plazo_Minimo, Plazo_Minimo ,IF((".(1*$plazo).") >= Plazo_Maximo,Plazo_Maximo,(".(1*$plazo).") )) AS Plazo
                        FROM cat_productosfinancieros
                        WHERE ID_Producto = '".$id_producto."' ";
                
                $rs = $db->Execute($sql);
                $this->plazo = $rs->fields['Plazo'];
        }
        
        



        $sql = "SELECT                  cat_productosfinancieros.TasaMensual, 
                                        cat_productosfinancieros.TasaMensual_Tipo,
                                        cat_productosfinancieros.Vencimiento, 
                                        
                                        cat_productosfinancieros.Comision_Apertura,
                                        cat_productosfinancieros.Comision_Tipo,
                                        cat_productosfinancieros.Comision_calculo,


                                        cat_productosfinancieros.Metodo,
                                        cat_productosfinancieros.Nombre,
                                        cat_productosfinancieros.RedondeoCifras,
                                        
					cat_productosfinancieros.Esquema_Pagos,
					cat_productosfinancieros.ID_Tipocredito

                        FROM    cat_productosfinancieros
                        WHERE   ID_Producto = '".$id_producto."' ";

        $rs = $db->Execute($sql);

        $this->producto_financiero       = $rs->fields['Nombre'];       
        $this->tasa_nominal              = $rs->fields['TasaMensual']/100;

        $this->tasa_tipo                 = $rs->fields['TasaMensual_Tipo'];

        
        
        
        
        
        $this->periodo                   = $rs->fields['Vencimiento'];
        $this->tipovencimiento           = $rs->fields['Vencimiento'];

        
        $this->comision_apertura         = $rs->fields['Comision_Apertura'];
        $this->comision_tipo             = $rs->fields['Comision_Tipo'];
        $this->comision_calculo          = $rs->fields['Comision_calculo'];
        
        
        
        $this->metodo                   = $rs->fields['Metodo'];
  
        $this->esquema_pagos    	= $rs->fields['Esquema_Pagos'];

        $this->redondeocifras   	=($rs->fields['RedondeoCifras']=='Si')?(true):(false);
        
	$this->id_tipocredito    	= $rs->fields['ID_Tipocredito'];

//      // debug("this->comision_calculo : ". $this->comision_calculo );
        
        
        if($this->comision_calculo == 'Porcentual')
        {
                $this->monto_comision_apertura = $this->capital * $this->comision_apertura/100;
        }
        else
        {
                $this->monto_comision_apertura = $this->comision_apertura;
        }
        
       // debug($this->comision_tipo ." ".$this->monto_comision_apertura);
        
        if($this->comision_tipo == 'Diferida')
        {       
               if($this->plazo ==0)
               $this->monto_comision_diferida   = $this->monto_comision_apertura;
               else
               $this->monto_comision_diferida   = $this->monto_comision_apertura/$this->plazo;
                
                
                
                
                $this->monto_comision_anticipada = 0;                   
        }
        else
        {       
        	if($this->comision_tipo == 'Capitalizada')
        	{
 			
 			$this->monto_comision_capitalizada = $this->monto_comision_apertura * (1+$this->iva_comision);
 			
 			$this->capital += $this->monto_comision_capitalizada;
 			
			$this->monto_comision_diferida   = 0; 
			$this->monto_comision_anticipada = 0; 
			$this->monto_comision_apertura   = 0;
        	
        	
        	}
        	else if($this->comision_tipo == 'Anticipada')
        	{

			$this->monto_comision_diferida   = 0; 
			$this->monto_comision_anticipada = $this->monto_comision_apertura;
                }
                else
                {
                
			$this->monto_comision_diferida   = 0; 
			$this->monto_comision_anticipada = 0; 
			$this->monto_comision_apertura   = 0;
                }



        }
        




        $this->cotiza_credito();








}

//---------------------------------------------------------------------------------------------------------------------
// Financiar seguros en base a su costo anual
//---------------------------------------------------------------------------------------------------------------------

function permite_creditos_simultaneos($Bool)
{
        $this->permite_creditos_simultaneos = $Bool;

}


function seguro_vida_costo_anual($monto_anual)
{
        $this->segv_anual = $monto_anual*1;

}


function seguro_desempleo_costo_anual($monto_anual)
{

        $this->segd_anual = $monto_anual*1;

}



function seguro_bienes_costo_anual($monto_anual)
{

        $this->segb_anual = $monto_anual*1;

}



function get_tasa_nominal($tasa)
{
        $this->tasa_nominal_usuario = $tasa/100;
        
         //debug("this->tasa_nominal_usuario  : ".$this->tasa_nominal_usuario );

}




//---------------------------------------------------------------------------------------------------------------------
// Financiar seguros en base a su total durante la vida del crédito
//---------------------------------------------------------------------------------------------------------------------



function seguro_vida_costo_total($monto_total)
{
        $this->segv_total = $monto_total*1;
}


function seguro_desempleo_costo_total($monto_total)
{
        $this->segd_total = $monto_total*1;
}


function seguro_bienes_costo_total($monto_total)
{
        $this->segb_total = $monto_total*1;
}









//---------------------------------------------------------------------------------------------------------------------
//
//---------------------------------------------------------------------------------------------------------------------

function cotiza_credito()
{

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

        if( empty($dias_periodo)) 
                $dias_periodo=30;
                         
        $this->frecuencia   = $frecuencia;
        $this->tipo_plazox  = $tipo_plazox;             
        $this->dias_periodo = $dias_periodo;
                

        $tasanominal= $this->tasa_nominal;

        
        $dia_referencia = ($frecuencia >= 6)?(""):(fdia($this->fecha_inicio));
                
        
        $saldo_capital  = $this->capital;

        
        //========================================================================
        // Cuota de Seguro de Vida
        //========================================================================


        if($frecuencia == 7) $dean=364; else $dean=360;

        if($this->segv_anual > 0)
            $this->cuota_segv = $this->dias_periodo * ($this->segv_anual/$dean);
        elseif($this->segv_total > 0)
            $this->cuota_segv = ($this->segv_total/$this->plazo);
        else    
            $this->cuota_segv = 0;   


        //========================================================================
        // Cuota de Seguro de Desempleo
        //========================================================================

        if($this->segd_anual > 0)
            $this->cuota_segd = $this->dias_periodo * ($this->segd_anual/$dean);
        elseif($this->segd_total > 0)
            $this->cuota_segd = ($this->segd_total/$this->plazo);
        else    
            $this->cuota_segd = 0;   


        //========================================================================
        // Cuota de Seguro de Bienes Muebles / Inmuebles
        //========================================================================

        if($this->segb_anual > 0)
            $this->cuota_segb = $this->dias_periodo * ($this->segb_anual/$dean);
        elseif($this->segb_total > 0)
            $this->cuota_segb = ($this->segb_total/$this->plazo);
        else    
            $this->cuota_segb = 0;   










                //---------------------------------------------------------------------------------------------------
                // Cálculo de Renta y Tasa equivalente del periodo 
                //---------------------------------------------------------------------------------------------------
/*
                // debug("iva_interes :         [".$this->iva_interes."]");
                // debug("capital :             ".$this->capital);
                // debug("vencimientos :                ".$this->plazo);
                // debug("tasa_nominal :                ".$this->tasa_nominal);
                // debug("metodo :              ".$this->metodo);

*/
                //---------------------------------------------------------------------------------------------------
                // CÁLCULO DE CREDITO CON SALDOS INSOLUTOS
                //---------------------------------------------------------------------------------------------------

                if($this->metodo == 'Saldos Insolutos')
                {


			if($this->tasa_nominal_usuario > 0)
			{
			
				$this->tasa_tipo = 'Insoluta';
				$this->tasa_nominal = $this->tasa_nominal_usuario;
			
			}


                        if($this->tasa_tipo == 'Insoluta')
                        {

                                $this->tasa_mensual_ssi = $this->tasa_nominal;  

                                $this->tasa_periodo_ssi = $this->dias_periodo * ($this->tasa_mensual_ssi/30);
                                
                                $tasaeq_iva = $this->tasa_periodo_ssi * (1+$this->iva_interes);                         
                                
                                if($tasaeq_iva > 0)
                                   $renta = ($this->capital * $tasaeq_iva)/(1 - pow(1+$tasaeq_iva, -$this->plazo));
                                else
                                   $renta = ($this->capital/$this->plazo);
                                

                        
                        
                        }
                        else
                        {
                                //===================================================================================================
                                //                      CONVERTIR SALDOS TASA DE SOLUTOS  ==>>  TASA SALDOS INSOLUTOS
                                //===================================================================================================
                        
                                  $tasaeq = $this->dias_periodo * (($this->tasa_nominal)/30);

                                  $renta  = ($this->capital + (($this->capital * $tasaeq * $this->plazo)*(1+$this->iva_interes)))/$this->plazo; 
 
                                  $tasa_eq_ssi = $this->tasa_soluta_insoluta($this->capital, $renta, $this->plazo)/(1+$this->iva_interes);

                                  $tasa_eq_mensual_ssi = 30 *  $tasa_eq_ssi/$dias_periodo; 
                                
                                  $this->tasa_mensual_ssi = $tasa_eq_mensual_ssi;
                                
                                  $this->tasa_periodo_ssi = $tasa_eq_ssi;
                                
                                  $tasaeq =  $tasa_eq_ssi;
                        
                        
                        
                        }
                        
/*                      
                        // debug($this->tasa_tipo." ".$this->tasa_nominal);
                        // debug($renta);
                        // debug("this->tasa_mensual_ssi ".$this->tasa_mensual_ssi );
                        // debug("this->tasa_periodo_ssi ".$this->tasa_periodo_ssi );
*/      

                        
                        $this->renta = $renta + $this->monto_comision_diferida * (1+$this->iva_comision);
                        
                        $tasaeq_anualizada     = ( $this->tasa_mensual_ssi/$dias_periodo)*360;

                        $tasaeq_iva_anualizada = ( ($this->tasa_mensual_ssi * (1+$this->iva_interes))/$dias_periodo)*360;

        $fecha  = ffecha($this->fecha_inicio);
        

        $_renta = 0;


        //--------------------------------------------------------------------------------------------------------------------------------
        // Si el crédito es de tipo NOMINA, puede haber días de diferencia entre la fecha de inicio y la fecha del primer vencimiento
        // entonces es necesario compensar la diferencia de intereses en la tabla de amortizacion
        //--------------------------------------------------------------------------------------------------------------------------------
       
        //debug($this->fecha_para_primer_vencimiento);
        //if(!empty($this->fecha_para_primer_vencimiento))        
        //$this->id_empresa = 1;
/* 
//===================================================================================================================================================
//	Mecanismo de compensación 
//	PErmite compensar en la tasa la diferencia de días entre la fecha en que se da el crédito y la fecha del primer vencimiento, cuando  
//      la cantidad de días es mayor o menor a un periodo estándar de vencimiento.
//===================================================================================================================================================

        if(($this->id_empresa > 0) and (!empty($this->fecha_para_primer_vencimiento)))
        {
        
             $difdias = ffdias($this->fecha_para_primer_vencimiento,$this->fecha_inicio);
             
             //debug("Diferencia entre el primer vencimiento ".ffecha($this->fecha_para_primer_vencimiento)." y la fecha de inicio ".ffecha($this->fecha_inicio)." = ".$difdias." dias ");
             //debug("El periodo del crédito es en ".$this->periodo." y debe tener ".$this->dias_periodo." dias entre cada periodo.");
             
             
             $dias_compensar = $difdias - $this->dias_periodo;
             
             $tasa_diaria = ( $this->tasa_mensual_ssi/30);
             
             
             
             if(abs($dias_compensar)>0)
             {
             
             
                     $monto_compensacion = ($tasa_diaria * $this->capital * $dias_compensar) * (1+$this->iva_interes);

                     //debug("   Monto Compensacion = (Tasa diaria) ".$tasa_diaria ." * ( capital) ".$this->capital." * ( dias a compensar ) ".$dias_compensar ."  = ".$monto_compensacion." ");



                     $renta_sin_comision = $this->renta - ($this->monto_comision_diferida * (1+$this->iva_comision));             
                     $nueva_renta        = $renta_sin_comision  + ($monto_compensacion/$this->plazo);




                     $nueva_tasa_compensada = $this->tasa_soluta_insoluta($this->capital, $nueva_renta, $this->plazo, 0 );

                     $nueva_tasa_compensada = $nueva_tasa_compensada/(1+$this->iva_interes);



                     $nueva_tasa_mensual = ($nueva_tasa_compensada/$this->dias_periodo) * 30;

                     //debug(" La tasa nominal era ". $this->tasa_mensual_ssi. " y ahora es ".$nueva_tasa_mensual);


             debug("IMPORTANTE : Se compensarán  ".$dias_compensar ." días de diferéncia entre fecha de inicio y el primer cargo modificando la tasa de ".number_format((100*$this->tasa_mensual_ssi),4)."% a ".number_format((100*$nueva_tasa_mensual),4)."% ");


                    $this->renta = $nueva_renta + ($this->monto_comision_diferida * (1+$this->iva_comision)); 
                    $this->tasa_mensual_ssi = $nueva_tasa_mensual;
                    $this->tasa_periodo_ssi = $nueva_tasa_compensada;
            }
            
      
        }

*/ 



        $cuota_seguros = ($this->cuota_segv + $this->cuota_segd + $this->cuota_segb) *  (1 + $this->iva_comision );

        $this->renta += $cuota_seguros;


        
        if($this->redondeocifras)
        {

                
                
                
                if(round($this->renta,2) > trunc($this->renta))
		{
		     $this->renta_ajustada = ceil($this->renta  );
		       
		}
		else
		{
			$this->renta_ajustada = $this->renta  ;
		}

                
                
                
                
                
                
                
        
                $renta_ajustada_sin_comision = $this->renta_ajustada - ($this->monto_comision_diferida * (1+$this->iva_comision))  - $cuota_seguros;
        
                $this->tasa_reversa_ssi = $this->tasa_soluta_insoluta($this->capital, $renta_ajustada_sin_comision, $this->plazo, 0 ); 
                
                $this->tasa_reversa_ssi = $this->tasa_reversa_ssi/(1+$this->iva_interes);
        
        
                $this->renta            = $this->renta_ajustada;
                $this->tasa_periodo_ssi = number_format($this->tasa_reversa_ssi,20,".",",");
                
                
                
                $this->tasa_mensual_ssi = 30 *  ($this->tasa_periodo_ssi/$this->dias_periodo);
                
         }
        else
        {
                $ajuste=0;
                
        }
        



/*

         if($this->renta>0)
            $this->cat = calcula_cat($tasaeq_iva_anualizada, ($this->capital - $this->monto_comision_capitalizada), $this->renta, $this->plazo, $this->periodo  , false);
         else
            $this->cat = 0;


*/    


        $this->tot_abono_capital = 0;
        $this->tot_abono_interes = 0;
        $this->tot_abono_comision = 0;
        
        
        $this->tot_abono_iva     = 0;
        $this->tot_abonos          = 0;
        $this->tot_abono_iva_comision   = 0;
        $this->tot_abono_iva_interes    = 0;



        $this->tot_abono_segv = 0;
        $this->tot_abono_iva_segv = 0;

        $this->tot_abono_segd = 0;
        $this->tot_abono_iva_segd = 0;

        $this->tot_abono_segb = 0;
        $this->tot_abono_iva_segb = 0;



        for ($i=0; $i<=$this->plazo; $i++)
	{

                                                if($i)
                                                {
                                                                
                                                $diff = 0;              
                                                                
                                                                
                                                                $_renta = $this->renta;
                                                                
                                                                
                                                                $abono_interes  = ($saldo_capital * $this->tasa_periodo_ssi);
                                                                $abono_comision = $this->monto_comision_diferida;

                                                                $abono_iva_interes  = ($abono_interes   * $this->iva_interes  ) ;
                                                                $abono_iva_comision = ($abono_comision  * $this->iva_comision ) ;


                                                                


                                                                $abono_segv     =  $this->cuota_segv;
                                                                $abono_iva_segv = ($this->cuota_segv * $this->iva_comision ) ; 

                                                                $abono_segd     =  $this->cuota_segd;
                                                                $abono_iva_segd = ($this->cuota_segd * $this->iva_comision ) ;

                                                                $abono_segb     =  $this->cuota_segb;
                                                                $abono_iva_segb = ($this->cuota_segb * $this->iva_comision ) ;


                                                                $abono_iva      = ($abono_iva_interes  + 
                                                                                   $abono_iva_comision + 
                                                                                   $abono_iva_segv     + 
                                                                                   $abono_iva_segd     + 
                                                                                   $abono_iva_segb      );







                                                                $abono_capital = $_renta - ($abono_comision + $abono_interes + $abono_iva + $abono_segv + $abono_segd + $abono_segb );
                                                                
                                                                if(($this->tot_abono_capital + $abono_capital) > $this->capital)
                                                                {
                                                                
                                                                    $abono_capital = $this->capital - $this->tot_abono_capital;
                                                                
                                                                }
                                                                
                                                                $abono_capital = ($abono_capital <0 )?(0):($abono_capital);


                                                                //$fecha  = fechavencimiento($fecha, $frecuencia);
                                                                //debug("fuera : ".$fecha);

                                                                if($i==1)
                                                                {
									  if(!empty($this->fecha_para_primer_vencimiento) )
									  {
										     $fecha = ffecha($this->fecha_para_primer_vencimiento);

										     if(!empty($dia_referencia))
										     {
												if($this->periodo =='Meses')
												{ 
													list($dd,$mm,$yy) = explode("/",$fecha);
													$ulimo_dia_mes_primera_cuota = date("t",mktime(0,0,0,$mm,1,$yy));

													if($dd == $ulimo_dia_mes_primera_cuota )
													{
													   $dia_referencia = "";
													}
													else
													{										       
													   $dia_referencia = fdia(gfecha($fecha));
													}

												}
												else
												{
												  $dia_referencia = fdia(gfecha($fecha));
												}
												
										     }
									  }
									  else
									  {
										$fecha  = $this->calcula_primer_vencimiento();





										if(empty($fecha))
										{
											$fecha  = fechavencimiento(ffecha($this->fecha_inicio), $frecuencia, $dia_referencia);
										}
										else
										{
										    
										    
										    
										    if($this->periodo =='Meses')
										    { 
												list($dd,$mm,$yy) = explode("/",$fecha);
												$ulimo_dia_mes_primera_cuota = date("t",mktime(0,0,0,$mm,1,$yy));
												
												if($dd == $ulimo_dia_mes_primera_cuota )
												{
												   $dia_referencia = "";
												}
												else
												{										       
										       		   $dia_referencia = fdia(gfecha($fecha));
										       		}
										       
										    }
										    else
										    {
										       $dia_referencia ="";
										    }
										}
									  }

									   $this->primer_vencimiento = gfecha($fecha);
                                                                }
                                                                else
                                                                {                                                                
									   
									   $fecha  = fechavencimiento($fecha, $frecuencia, $dia_referencia);
                                                                }
                                                                
                                                                
                                                                
                                                                
                                                                $this->tot_abono_capital        += $abono_capital ;                  

                                                                $this->tot_abono_interes        += $abono_interes ;     
                                                                $this->tot_abono_iva_interes    += $abono_iva_interes; 


                                                                $this->tot_abono_comision       += $abono_comision ;   
                                                                $this->tot_abono_iva_comision   += $abono_iva_comision;
                                                                
                                                                
                                                                $this->tot_abono_iva            +=  $abono_iva    ;
                                                                

                                                                $this->tot_abono_segv           += $abono_segv    ;
                                                                $this->tot_abono_iva_segv       += $abono_iva_segv;

                                                                $this->tot_abono_segd           += $abono_segd    ;
                                                                $this->tot_abono_iva_segd       += $abono_iva_segd;

                                                                $this->tot_abono_segb           += $abono_segb    ;
                                                                $this->tot_abono_iva_segb       += $abono_iva_segb;

                                                                
                                                                
                                                                
                                                                
                                                                
                                                                
                                                                
                                                                
                                                                $this->tot_abonos        +=    $_renta;


                                                }
                                                else
                                                {
                                                           $_renta = $this->monto_comision_anticipada * (1+$this->iva_comision);        
                                                           
                                                           $abono_iva         +=  $this->monto_comision_anticipada  *  $this->iva_comision;                                                        
                                                           $abono_iva_interes  = 0;
                                                           $abono_iva_comision = $this->monto_comision_anticipada   *  $this->iva_comision;
                                                           
                                                           
                                                           $this->tot_abono_iva                 +=  $this->monto_comision_anticipada  *  $this->iva_comision;

                                                           $this->tot_abono_iva_comision        += $abono_iva_comision; 
                                                           $this->tot_abono_iva_interes         += 0;                                           




                                                           $this->tot_abono_comision    +=  $this->monto_comision_anticipada;   
                                                           $this->tot_abonos            +=  $_renta;
                                                }
                                                
                                                
                                                $saldo_capital = $saldo_capital - $abono_capital;

                                                if($saldo_capital <0.01)
                                                   $saldo_capital = 0;

                                                $this->tabla_amortizacion[$i]['FECHA'] = $fecha; 


                                                if(!$i)
                                                {
                                                        $this->tabla_amortizacion[$i]['COMISION']  = $this->monto_comision_anticipada;
                                                        $this->tabla_amortizacion[$i]['ABONO_IVA'] = $this->monto_comision_anticipada * $this->iva_comision;

                                                        

                                                }
                                                else
                                                {
                                                        $this->tabla_amortizacion[$i]['COMISION'] =   $this->monto_comision_diferida;
                                                        $this->tabla_amortizacion[$i]['ABONO_IVA'] += $this->monto_comision_diferida * $this->iva_comision;
                                                }

                                                $this->tabla_amortizacion[$i]['ABONO_IVA_COMISION']     = $abono_iva_comision;


                                                $this->tabla_amortizacion[$i]['ABONO_INTERES']          = $abono_interes;                                                
                                                $this->tabla_amortizacion[$i]['ABONO_IVA_INTERES']      = $abono_iva_interes;
                                                

                                                $this->tabla_amortizacion[$i]['ABONO_SEGV']          = $abono_segv    ;                                       
                                                $this->tabla_amortizacion[$i]['ABONO_IVA_SEGV']      = $abono_iva_segv;
                                                
                                                $this->tabla_amortizacion[$i]['ABONO_SEGD']          = $abono_segd    ;                                       
                                                $this->tabla_amortizacion[$i]['ABONO_IVA_SEGD']      = $abono_iva_segd;

                                                $this->tabla_amortizacion[$i]['ABONO_SEGB']          = $abono_segb    ;                                       
                                                $this->tabla_amortizacion[$i]['ABONO_IVA_SEGB']      = $abono_iva_segb;


                                                
                                                
                                                
                                                $this->tabla_amortizacion[$i]['ABONO_CAPITAL']  = $abono_capital;
                                                
                                                
                                                
                                                
                                                $this->tabla_amortizacion[$i]['ABONO_IVA']      = $abono_iva;
                                                
                                                
                                                $this->tabla_amortizacion[$i]['RENTA']          = $_renta;
                                                $this->tabla_amortizacion[$i]['SALDO_CAPITAL']  = $saldo_capital;
                                                
                                                
	}
	
	
	
	
        $this->ultimo_vencimiento = gfecha($fecha);

        if($this->capital != 0)
           $this->tasa_golbal_efectiva = ($this->tot_abonos/$this->capital)-1;
        else
           $this->tasa_golbal_efectiva = 0;             

        $x=$i-1;



			if($this->esquema_pagos == 'Unipago')
			{
			
				$this->tabla_amortizacion = array();
				
				$i=0;

				$this->tabla_amortizacion[$i]['FECHA']      	   	= ffecha($this->fecha_inicio);
				$this->tabla_amortizacion[$i]['SALDO_CAPITAL']  	= $this->capital;

				$i=1;
				
				
				
				
				
				$this->tot_abono_interes      =    ($this->tasa_periodo_ssi  * $this->capital * $this->plazo);
				$this->tot_abono_iva_interes  =    ($this->tot_abono_interes * $this->iva_interes);
				
				
				
				
				
				$this->tot_abonos =       $this->capital 
							 +$this->tot_abono_interes	
							 +$this->tot_abono_iva_interes

							 +$this->tot_abono_comision				
							 +$this->tot_abono_iva_comision

							 +$this->tot_abono_segv   	
							 +$this->tot_abono_iva_segv

							 +$this->tot_abono_segd
							 +$this->tot_abono_iva_segd

							 +$this->tot_abono_segb
							 +$this->tot_abono_iva_segb;

				$this->renta = $this->tot_abonos;
				
				
				
				$this->tabla_amortizacion[$i]['FECHA']      	   	=  ffecha($this->ultimo_vencimiento);

                                $this->tabla_amortizacion[$i]['COMISION']   	   	=  $this->tot_abono_comision;
				$this->tabla_amortizacion[$i]['ABONO_IVA_COMISION']	=  $this->tot_abono_iva_comision;				


				$this->tabla_amortizacion[$i]['ABONO_INTERES']     	=  $this->tot_abono_interes; 			
				$this->tabla_amortizacion[$i]['ABONO_IVA_INTERES'] 	=  $this->tot_abono_iva_interes;


				$this->tabla_amortizacion[$i]['ABONO_SEGV']        	=  $this->tot_abono_segv;   				
				$this->tabla_amortizacion[$i]['ABONO_IVA_SEGV'] 	=  $this->tot_abono_iva_segv;

				$this->tabla_amortizacion[$i]['ABONO_SEGD']     	=  $this->tot_abono_segd;
				$this->tabla_amortizacion[$i]['ABONO_IVA_SEGD'] 	=  $this->tot_abono_iva_segd;

				$this->tabla_amortizacion[$i]['ABONO_SEGB']     	=  $this->tot_abono_segb;
				$this->tabla_amortizacion[$i]['ABONO_IVA_SEGB'] 	=  $this->tot_abono_iva_segb;

				$this->tabla_amortizacion[$i]['ABONO_CAPITAL']  	= $this->capital;
				$this->tabla_amortizacion[$i]['ABONO_IVA']      	= $this->tot_abono_iva;


				$this->tabla_amortizacion[$i]['RENTA']          	= $this->tot_abonos;
				$this->tabla_amortizacion[$i]['SALDO_CAPITAL']  	= 0;
			}
			else
			{
			
                                ## Compnesación  de interéses entre la fecha de la primera cuota y los días del periodo
                                //if($this->fecha_inicio > $this->fecha_apertura)
                                
                               
                               $_compensar_intereses_diferenciales = false;
                               
                               
                               if($this->id_tipocredito == 3)
                               {
                                	$habilitar_compensacion_intereses_nomina = system_const('habilitar_compensacion_intereses_nomina',$this->db);
                               
                                        if($habilitar_compensacion_intereses_nomina == "Si")
                                        {
                                        	$_compensar_intereses_diferenciales = true;
                                        }
                               }
                               
                               
                               if($this->id_tipocredito == 1)
                               {
                                	$habilitar_compensacion_intereses_individual = system_const('habilitar_compensacion_intereses_individual',$this->db);
                               
                                        if($habilitar_compensacion_intereses_individual == "Si")
                                        {
                                        	$_compensar_intereses_diferenciales = true;
                                        }
                               }
                               
                               
                              if($_compensar_intereses_diferenciales)
                              { 
                               
                                
					$_dias_entre_inicio_y_primer_cuota = fdifdias(gfecha($this->tabla_amortizacion[0]['FECHA']),gfecha($this->tabla_amortizacion[1]['FECHA']));


					if(($_dias_entre_inicio_y_primer_cuota >= 0) and abs($this->dias_periodo - $_dias_entre_inicio_y_primer_cuota) > 0)
					{
						$_dias_extra  = $_dias_entre_inicio_y_primer_cuota - $this->dias_periodo;                                                                       


						$_monto_extra     =  $_dias_extra  * ($this->tasa_periodo_ssi/$this->dias_periodo) * $this->capital;


						$_monto_extra_iva   =  $_monto_extra *  $this->iva_interes;



						$this->tabla_amortizacion[1]['ABONO_INTERES']     += $_monto_extra;

						$this->tabla_amortizacion[1]['ABONO_IVA_INTERES'] += $_monto_extra_iva;                                        

						$this->tabla_amortizacion[1]['ABONO_IVA']         += $_monto_extra_iva;

						$this->tabla_amortizacion[1]['RENTA']             += $_monto_extra + $_monto_extra_iva;



						$this->tot_abono_interes        += $_monto_extra; 
						$this->tot_abono_iva_interes    += $_monto_extra_iva;
						$this->tot_abono_iva            += $_monto_extra_iva;

						$this->tot_abonos        	+= $_monto_extra + $_monto_extra_iva;



				       }
                             
			
			    }
			
			
			
			}




                }
                else
                {

                //---------------------------------------------------------------------------------------------------
                // CÁLCULO DE CREDITO CON SALDOS SOLUTOS
                //---------------------------------------------------------------------------------------------------

                        if($this->tasa_tipo == 'Soluta')
                        {

                                $this->tasa_mensual_ssol = $this->tasa_nominal; 

                                $this->tasa_periodo_ssol = $this->dias_periodo * ($this->tasa_mensual_ssol/30);
                                
                                $tasaeq_iva = $this->tasa_periodo_ssol * (1+$this->iva_interes);                                
                                
                                
                                if($this->plazo == 0)
                                	$renta = $this->capital;
                                else
                                if($this->tasa_periodo_ssol>0)
                                   $renta    = ($this->capital + ($this->capital * $this->tasa_periodo_ssol * $this->plazo*(1+$this->iva_interes)))/$this->plazo; 
                                
                                else
                                   $renta = ($this->capital/$this->plazo);
                                

                        
                        
                        }
                        else
                        {
                                //===================================================================================================
                                //                      CONVERTIR SALDOS TASA DE INSOLUTOS  ==>>  TASA SALDOS SOLUTOS
                                //===================================================================================================
                        

                                  $this->tasa_mensual_ssi = $this->tasa_nominal;        


                                  $this->tasa_periodo_ssi = ($this->tasa_nominal/30)*$this->dias_periodo;


                                  $tasaeq_iva = $this->tasa_periodo_ssi * (1+$this->iva_interes);

                                  
                                  if($tasaeq_iva >0 )
                                      $renta      = ($this->capital * $tasaeq_iva)/(1 - pow(1+$tasaeq_iva, -$this->plazo));
                                  else
                                      $renta = ($this->capital/$this->plazo);

                                  $interes_total_pagar = (($this->plazo  * $renta)-$this->capital)/((1+$this->iva_interes));

                                  if( ( $this->capital * $this->plazo ) != 0 )
	                                  $this->tasa_periodo_ssol= $interes_total_pagar/($this->capital * $this->plazo);
	                                 else 
	                                  $this->tasa_periodo_ssol= 0;
	                                  
                                  $this->tasa_mensual_ssol =  30 *  $this->tasa_periodo_ssol/$this->dias_periodo;
                        
                        
                        
                        }
                        
                        $this->renta = $renta + $this->monto_comision_diferida * (1+$this->iva_comision);
                        
                        $tasaeq_anualizada     = ( $this->tasa_mensual_ssi/$dias_periodo)*360;

                        $tasaeq_iva_anualizada = ( ($this->tasa_mensual_ssi * (1+$this->iva_interes))/$dias_periodo)*360;





        $fecha  = ffecha($this->fecha_inicio);
        

        $_renta = 0;
        $ajuste=0;

     
        if($this->redondeocifras)
        {
                
          
               if(round($this->renta,2) > trunc($this->renta))
               {
		       $this->renta = ceil($this->renta);
		       
               }
               
                

         
                
               $_renta =  $this->renta - ($this->monto_comision_diferida * (1+$this->iva_comision));
                

               $_numerador   = ($_renta * $this->plazo - $this->capital);
               $_denominador = ($this->capital * $this->plazo * (1+$this->iva_interes));
               
               
               if($_denominador != 0)
               {
                 $this->tasa_periodo_ssol = $_numerador /$_denominador ;

		// $this->tasa_nominal = 30 * ($this->tasa_periodo_ssol/$dias_periodo);
	      
	       }

/*  */       
        }


        $this->tot_abono_capital = 0;
        $this->tot_abono_interes = 0;
        $this->tot_abono_comision = 0;
        $this->tot_abono_iva     = 0;
        $this->tot_abonos          = 0;
        $this->tot_abono_iva_comision   = 0;
        $this->tot_abono_iva_interes    = 0;
        for ($i=0; $i<=$this->plazo; $i++)
                {


                                                if($i)
                                                {
                                                                $abono_interes =  $this->capital * $this->tasa_periodo_ssol ;
                                                                $abono_comision = $this->monto_comision_diferida;
                                                                
                                                                
                                                                $abono_iva_interes = ($this->capital * $this->tasa_periodo_ssol * $this->iva_interes);
                                                                $abono_iva_comision= ($this->monto_comision_diferida  * $this->iva_comision);

                                                                $abono_iva     =  $abono_iva_interes+$abono_iva_comision  ;

                                                                $abono_capital = $this->renta - ($abono_comision + $abono_interes + $abono_iva);
                                                                $abono_capital = ($abono_capital <0 )?(0):($abono_capital);

								
                                                               

                                                                if($i==1)
                                                                {
									  if(!empty($this->fecha_para_primer_vencimiento) )
									  {
									     	     $fecha = ffecha($this->fecha_para_primer_vencimiento);

										     if(!empty($dia_referencia))
										     {
												if($this->periodo =='Meses')
												{ 
													list($dd,$mm,$yy) = explode("/",$fecha);
													$ulimo_dia_mes_primera_cuota = date("t",mktime(0,0,0,$mm,1,$yy));

													if($dd == $ulimo_dia_mes_primera_cuota )
													{
													   $dia_referencia = "";
													}
													else
													{										       
													   $dia_referencia = fdia(gfecha($fecha));
													}

												}
												else
												{
												  $dia_referencia = fdia(gfecha($fecha));
												}
												
										     }
									  }
									  else
									  {
										$fecha  = $this->calcula_primer_vencimiento();

										if(empty($fecha))
										{
											$fecha  = fechavencimiento(ffecha($this->fecha_inicio), $frecuencia, $dia_referencia);
										}
										else
										{
										    if($this->periodo =='Meses')
										    {
												/*$dia_referencia = fdia(gfecha($fecha));*/
												
												list($dd,$mm,$yy) = explode("/",$fecha);
												$ulimo_dia_mes_primera_cuota = date("t",mktime(0,0,0,$mm,1,$yy));
												
												if($dd == $ulimo_dia_mes_primera_cuota )
												{
												   $dia_referencia = "";
												}
												else
												{										       
										       		   $dia_referencia = fdia(gfecha($fecha));
										       		}
										       		
										       		
										    }
										    else
										    {
										       $dia_referencia ="";
										    }
										}
									  }

									  $this->primer_vencimiento = gfecha($fecha);
                                                                }
                                                                else
                                                                {                                                            
									   $fecha  = fechavencimiento($fecha, $frecuencia, $dia_referencia);
                                                                }                                                                
                                                                

                                                                $_renta = $this->renta;

                                                                $this->tot_abono_capital        +=      $abono_capital ;                  
                                                                $this->tot_abono_interes        +=      $abono_interes ;     
                                                                $this->tot_abono_iva            +=      $abono_iva    ;
                                                                $this->tot_abono_comision       +=      $abono_comision ;   
                                                                
                                                                $this->tot_abono_iva_comision   +=      $abono_iva_comision;
                                                                $this->tot_abono_iva_interes    +=      $abono_iva_interes;                                                     

                                                                
                                                                

                                                                $this->tot_abonos        +=    $_renta;


                                                }
                                                else
                                                {
                                                           $_renta = $this->monto_comision_anticipada * (1+$this->iva_comision);        
                                                           $abono_iva                   +=  $this->monto_comision_anticipada  *  $this->iva_comision;
                                                           $abono_iva_comision          +=  $this->monto_comision_anticipada  *  $this->iva_comision;
                                                            
                                                           $this->tot_abono_iva         +=  $abono_iva_comision;
                                                           $this->tot_abono_iva_comision+=  $abono_iva_comision;
                                                   
                                                           $this->tot_abono_comision    +=  $this->monto_comision_anticipada;   
                                                           $this->tot_abonos            +=  $_renta;
                                                }
                                                
                                                
                                                $saldo_capital = $saldo_capital - $abono_capital;

                                                if($saldo_capital <0.01)
                                                   $saldo_capital = 0;

                                                $this->tabla_amortizacion[$i]['FECHA'] = $fecha; 


                                                if(!$i)
                                                {
                                                        $this->tabla_amortizacion[$i]['COMISION']  = $this->monto_comision_anticipada;
                                                        $this->tabla_amortizacion[$i]['ABONO_IVA'] = $this->monto_comision_anticipada * $this->iva_comision;
                                                        $this->tabla_amortizacion[$i]['ABONO_IVA_COMISION']     = $this->monto_comision_anticipada * $this->iva_comision;

                                                        

                                                }
                                                else
                                                {
                                                        $this->tabla_amortizacion[$i]['COMISION']               =   $this->monto_comision_diferida;
                                                        $this->tabla_amortizacion[$i]['ABONO_IVA']              += $this->monto_comision_diferida * $this->iva_comision;
                                                        $this->tabla_amortizacion[$i]['ABONO_IVA_COMISION']      = $this->monto_comision_diferida * $this->iva_comision;
                                                        
                                                }



                                                $this->tabla_amortizacion[$i]['ABONO_CAPITAL']  = $abono_capital;
                                                $this->tabla_amortizacion[$i]['ABONO_INTERES']  = $abono_interes;
                                                $this->tabla_amortizacion[$i]['ABONO_IVA']      = $abono_iva;
                                                $this->tabla_amortizacion[$i]['ABONO_IVA_INTERES']      = $abono_iva_interes;
                                                $this->tabla_amortizacion[$i]['ABONO_IVA_COMISION']     = $abono_iva_comision;

                                                
                                                
                                                
                                                
                                                $this->tabla_amortizacion[$i]['RENTA']          = $_renta;
                                                $this->tabla_amortizacion[$i]['SALDO_CAPITAL']  = $saldo_capital;
                                }

                                 $this->ultimo_vencimiento = gfecha($fecha);

                                if($this->capital != 0)
                                   $this->tasa_golbal_efectiva = ($this->tot_abonos/$this->capital)-1;
                                else
                                   $this->tasa_golbal_efectiva = 0;             


			if($this->esquema_pagos == 'Unipago')
			{
			
				$this->tabla_amortizacion = array();
				
				$i=0;

				$this->tabla_amortizacion[$i]['FECHA']      	   	= ffecha($this->fecha_inicio);
				$this->tabla_amortizacion[$i]['SALDO_CAPITAL']  	= $this->capital;

				$i=1;
				
				
				
				
				$this->tot_abono_interes      =    ($this->tasa_periodo_ssi  * $this->capital * $this->plazo);
				$this->tot_abono_iva_interes  =    ($this->tot_abono_interes * $this->iva_interes);
				
				
				
				
				
				$this->tot_abonos =       $this->capital 
							 +$this->tot_abono_interes	
							 +$this->tot_abono_iva_interes

							 +$this->tot_abono_comision				
							 +$this->tot_abono_iva_comision

							 +$this->tot_abono_segv   	
							 +$this->tot_abono_iva_segv

							 +$this->tot_abono_segd
							 +$this->tot_abono_iva_segd

							 +$this->tot_abono_segb
							 +$this->tot_abono_iva_segb;

				$this->renta = $this->tot_abonos;				
				
				$this->tabla_amortizacion[$i]['FECHA']      	   	= ffecha($this->ultimo_vencimiento);

                                $this->tabla_amortizacion[$i]['COMISION']   	   	= $this->tot_abono_comision;

				$this->tabla_amortizacion[$i]['ABONO_IVA_COMISION']	= $this->tot_abono_iva_comision;				


				$this->tabla_amortizacion[$i]['ABONO_INTERES']     	=  $this->tot_abono_interes; 				
				$this->tabla_amortizacion[$i]['ABONO_IVA_INTERES'] 	=  $this->tot_abono_iva_interes;				


				$this->tabla_amortizacion[$i]['ABONO_SEGV']        	=  $this->tot_abono_segv;   				
				$this->tabla_amortizacion[$i]['ABONO_IVA_SEGV'] 	=  $this->tot_abono_iva_segv;

				$this->tabla_amortizacion[$i]['ABONO_SEGD']     	=  $this->tot_abono_segd;
				$this->tabla_amortizacion[$i]['ABONO_IVA_SEGD'] 	=  $this->tot_abono_iva_segd;

				$this->tabla_amortizacion[$i]['ABONO_SEGB']     	=  $this->tot_abono_segb;
				$this->tabla_amortizacion[$i]['ABONO_IVA_SEGB'] 	=  $this->tot_abono_iva_segb;

				$this->tabla_amortizacion[$i]['ABONO_CAPITAL']  	= $this->capital;
				$this->tabla_amortizacion[$i]['ABONO_IVA']      	= $this->tot_abono_iva;


				$this->tabla_amortizacion[$i]['RENTA']          	= $this->tot_abonos;
				$this->tabla_amortizacion[$i]['SALDO_CAPITAL']  	= 0;
			}
                
                
                
                
                
                
                }


if($this->esquema_pagos == 'Unipago')
{
            //CAPITAL REAL = ($this->capital - $this->monto_comision_capitalizada) =  $this->capital_valor
            $this->cat = calcula_cat($tasaeq_iva_anualizada, ($this->capital - $this->monto_comision_capitalizada), $this->renta, $this->plazo, $this->periodo  , false, $this->esquema_pagos);
}            
else
{
            $this->cat = calcula_cat($tasaeq_iva_anualizada, $this->capital , $this->renta, $this->plazo, $this->periodo  , false, $this->esquema_pagos);


			
			
            ## Compensación  de interéses entre la fecha de la primera cuota y los días del periodo
            //if($this->fecha_inicio > $this->fecha_apertura)
              
             
             $_compensar_intereses_diferenciales = false;
             
             
             if($this->id_tipocredito == 3)
             {
              	$habilitar_compensacion_intereses_nomina = system_const('habilitar_compensacion_intereses_nomina',$this->db);
             
                      if($habilitar_compensacion_intereses_nomina == "Si")
                      {
                      	$_compensar_intereses_diferenciales = true;
                      }
             }
             
             
             if($this->id_tipocredito == 1)
             {
              	$habilitar_compensacion_intereses_individual = system_const('habilitar_compensacion_intereses_individual',$this->db);
             
                      if($habilitar_compensacion_intereses_individual == "Si")
                      {
                      	$_compensar_intereses_diferenciales = true;
                      }
             }
             
             
            if($_compensar_intereses_diferenciales)
            { 
             
                
			$_dias_entre_inicio_y_primer_cuota = fdifdias(gfecha($this->tabla_amortizacion[0]['FECHA']),gfecha($this->tabla_amortizacion[1]['FECHA']));


			if(($_dias_entre_inicio_y_primer_cuota >= 0) and abs($this->dias_periodo - $_dias_entre_inicio_y_primer_cuota) > 0)
			{
				$_dias_extra  = $_dias_entre_inicio_y_primer_cuota - $this->dias_periodo;                                                                       


				$_monto_extra     =  $_dias_extra  * ($this->tasa_periodo_ssol/$this->dias_periodo) * $this->capital;


				$_monto_extra_iva   =  $_monto_extra *  $this->iva_interes;



				$this->tabla_amortizacion[1]['ABONO_INTERES']     += $_monto_extra;

				$this->tabla_amortizacion[1]['ABONO_IVA_INTERES'] += $_monto_extra_iva;                                        

				$this->tabla_amortizacion[1]['ABONO_IVA']         += $_monto_extra_iva;

				$this->tabla_amortizacion[1]['RENTA']             += $_monto_extra + $_monto_extra_iva;



				$this->tot_abono_interes        += $_monto_extra; 
				$this->tot_abono_iva_interes    += $_monto_extra_iva;
				$this->tot_abono_iva            += $_monto_extra_iva;

				$this->tot_abonos        	+= $_monto_extra + $_monto_extra_iva;



		       }
             
		
		}
			

}



}
//---------------------------------------------------------------------------------------------------------------------
//
//---------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------//

function tasa_soluta_insoluta($capital, $renta, $num_periodos, $debug=0 )
{

     if(  (empty($renta)) or (empty($capital))  )       return(0);

     $thispoint =0.0;
     $tasa      =0.0;

    $diferencial = 1.0;

//    if($this->renta)
    $setpoint = ($capital/$renta);   
//    else
//    $setpoint = 0;
    
    $tasa = (($renta * $num_periodos )- $capital)/($capital * $num_periodos);
    $iteracion = 0;
    $MAXINT=1;
    $MININT=0;    
    
    $table = "";
    $table .= " <TABLE BGCOLOR='white' ALIGN='center' BORDER=1 CELLSPACING=0 CELLPADDING=0 WIDTH='80%' ID='small'>\n";
    $table .= " <TR ALIGN='center'><TH>Iteracion </TH><TH>MAX</TH><TH>MIN</TH><TH>oper</TH><TH> ValorTest </TH><TH> Error</TH></TR>  \n";
    
    While( abs($diferencial) > 0.00000000000000001)
    {       
        
                    $thispoint =   ( 1 - pow((1+$tasa),(-1 * $num_periodos)))/$tasa  ;    
                    
                    
                    $diferencial =$setpoint - $thispoint ;
                    $skip=0;


                    if($diferencial > 0)
                    {
                        $MAXINT = $tasa;                       
                        $tasa -= (abs($tasa - $MININT)/ 2);
                        $oper = "<B>-</B>";                     
                        $skip=1;
                        
                    }
                    
                    if(!$skip)
                        if($diferencial  < 0)
                        {
                            $MININT = $tasa;
                             $tasa += (abs($tasa - $MAXINT)/ 2);
                            $oper = "<B>+</B>";
                        }                   
                    $table .= " <TR ALIGN='right'><TD> ".number_format($iteracion,0)." </TD ><TD>".number_format($MAXINT,20)."</TD><TD>".number_format($MININT,20)."</TD><TD ID='S2' ALIGN='center'>".$oper."</TD><TD> ".number_format(($tasa * 100/(1+$this->iva_interes)),20)."% </TD><TD> ".number_format($diferencial,20)." </TD></TR>  \n";

                    $iteracion++;
                
        
                
                    if($iteracion > 10000 )
                    break;

    }
        
       $table .=  " </TABLE>\n";
    
    if($debug) echo  $table;

    return($tasa);
}

//---------------------------------------------------------------------------------------------------------------------
//
//---------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------//



function genera_clave_compra()
{
    $ok = 1;
    while( $ok != 0 ) //Impedir la creación de números repetidos.
    {
            $anio=strftime("%Y",time());
            $mes=strftime("%m",time());

            srand( (double)microtime()*1000000 );
            $XH = (string)DecHex(rand(0,255));
            $prod_fecha = substr( (string) DecHex(($anio+$mes)*$this->num_cliente), 0, 7);

            $cve = substr(strtoupper("1".MD5($prod_fecha.$XH)),0,10);
            $nuevo = $cve;

            $sql="SELECT count(*) FROM compras WHERE num_compra = '".$cve."' ";
            
            $rs = $this->db->Execute($sql);                                
            $ok = $rs->fields[0];
            $error++;
            if($error >= 100)
            {                                                       
                       return(0);
                                   die("Se produjo un error..  No es posible generar un número de sin duplicidad.");
                      //  break; 
            }
    } 
    
    return($cve);

}
//---------------------------------------------------------------------------------------------------------------------
//
//---------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------//
function verifica_status_cliente()
{


        
        if($this->num_cliente <= 0)
        {
                error_msg("ERROR : Número de cliente inválido (".$this->num_cliente."). Imposible generar el crédito. ");
                return(-1);
        
        
        }
        
        
        $sql="  SELECT COUNT(*)
                FROM clientes 
                WHERE clientes.Num_cliente = '".$this->num_cliente."' ";
                
        //debug($sql);

        $rs=$this->db->Execute($sql);
        if($rs->fields[0]<= 0)
        {
                error_msg("ERROR : El número de cliente no existe (".$this->num_cliente."). Imposible generar el crédito. ");
                return(-1);
        
        
        }
        
        
        
        
        


        $sql="  SELECT fact_cliente.id_factura, 
                       fact_cliente.fecha_exp
                FROM fact_cliente

                LEFT JOIN factura_cliente_liquidacion ON factura_cliente_liquidacion.ID_Factura = fact_cliente.id_factura
                LEFT JOIN caja_credito_saldada        ON caja_credito_saldada.id_factura        = fact_cliente.id_factura


 
                WHERE      fact_cliente.num_cliente = '".$this->num_cliente."'
                      
                      and  factura_cliente_liquidacion.ID_Cierre  IS NULL  
                      
                      and  caja_credito_saldada.id_caja_pagos     IS NULL    ";


        //debug($sql);

       $fecha_hoy = date("Y-m-d");
       $rs=$this->db->Execute($sql);
         
         if($rs->_numOfRows)
            while(! $rs->EOF)
            {
              
                       
                       
               $optmp = new TCUENTA($rs->fields['id_factura'], $fecha_hoy,'','',true);

                
                if(!$this->permite_creditos_simultaneos)
                    if($optmp->adeudo_total >= 0.005)
                    {
                            error_msg("ERROR : El crédito con ID <U STYLE='color:navy;'>".$rs->fields['id_factura']."</U>  del cliente  <U STYLE='color:navy;'>#".$optmp->numcliente." : ".$optmp->nombrecliente." </U>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/>presenta un adeudo total  de ".number_format($optmp->adeudo_total,3)." al día de hoy ".ffecha($fecha_hoy).".<br/><br/> Imposible generar el crédito. ");                        
                            unset($optmp);                        
                            return(-1);
                    }
                
                
              
              
              
               unset($optmp);
               
               
               
              
              $rs->MoveNext();
            }
         
         

        return(1);
}


function genera_credito_nuevo($id_fondeo, $id_asoc_compra, $observaciones)
{



        if($this->no_generar==1)
        {
        
                error_msg("No se encontró la sucursal del cliente, imposible generar el crédito. ");
                return(-1);
        
        }
        
        


        //------------------------------------------------------------------------------------------------------------
        // ¿ Existe algún crédito activo para este cliente actualmente sin liquidar ?
        //------------------------------------------------------------------------------------------------------------
/*
        $verifica_status_cliente = $this->verifica_status_cliente();
        
        if($verifica_status_cliente < 1) return(-1);
*/







                        
        $this->numcompra = $this->genera_clave_compra();
        $this->id_fondeo = $id_fondeo;


        $sql = "INSERT INTO compras 
                 (Num_cliente, Num_compra, Tipo, ID_Fondeo, Observaciones )  
                VALUES 
                 ('".$this->num_cliente."','".$this->numcompra."','Credito','".$this->id_fondeo."', '".addslashes($observaciones)."') ";
   
       $this->db->Execute($sql);   
       // // debug(" I ) ".$sql);

       if($this->db->_affectedrows() > 0)
       {
                    $sql = "INSERT INTO compras_asociados 
                                   (Id_asociado,        Num_compra) 
                            VALUES ('".$id_asoc_compra."','".$this->numcompra."') ";           
            
            $this->db->Execute($sql);
            // // debug(" II ) ".$sql);
                                                
       }
       else
       {
                return(-1);
       
       }
                                


/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
/*                                      Factura Asociado */
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
         


         $sql="INSERT INTO fact_asociado 
                   (num_factura,  Num_compra,   subtotal,  iva,  Num_asociado, id_plazo)
               
                                   (SELECT MAX(num_factura*1)+1         AS num_factura,
                                        '".$this->numcompra."'                  AS Num_compra,  
                                        '".$this->capital."'                    AS subtotal, 
                                        '".$this->tot_abono_iva."'              AS iva, 
                                        '".$id_asoc_compra."'                   AS Num_asociado, 
                                        '".$this->id_producto."'                AS id_plazo

                                         FROM  fact_asociado)   ";
                 
         $this->db->Execute($sql);    
         // // debug("IIIa) ".$sql);
         
         
         
         $sql = "SELECT num_factura FROM  fact_asociado WHERE Num_compra='".$this->numcompra."' ";
         $rs  = $this->db->Execute($sql);          
         $num_factura  = $rs->fields[0];   
         
         

          
        /* XXX */ //                                                
         
         
         $sql="INSERT INTO det_fact_asociado 
                         (Num_compra, cantidad, descripcion, prec_unit, id_unidad)
                 VALUES 
                         ('".$this->numcompra."',1,'Crédito en efectivo','".$this->capital."',1) ";

         $this->db->Execute($sql);
         // // debug("IIIb) ".$sql);         
         
         
         
        /* XXX */ //


           //******************************************************************************************************//
           //Revisar si cliente tenía asociado un promotor recientemente, si no usaremos el de default
           //******************************************************************************************************//
           
           $sql="SELECT ID_Promo FROM promo_cliente WHERE Num_cliente='".$this->num_cliente."'";
           $rs = $this->db->Execute($sql);
           $id_promo = $rs->fields[0];
           if(empty($id_promo))
           {
           
                           // Promotor default ( El que está en la solicitud si es que aun sigue activo. )
                           $sql="   SELECT solicitud.ID_Promotor
                                                FROM   clientes,  solicitud, promotores
                                                WHERE  clientes.ID_Solicitud =  solicitud.ID_Solicitud and 
                                                       solicitud.ID_Promotor =  promotores.Num_promo   and
                                                       promotores.Status     =  'Activo' and
                                                       clientes.Num_cliente  =  '".$this->num_cliente."' ";
                                $rs = $this->db->Execute($sql);
                                $id_promo = $rs->fields[0];
                       
                       if(empty($id_promo))
                       {
                           $id_promo = -1; // Venta sin promotor.
                       }
                   
                   }

            
            if(!empty($id_promo))
            {
                    $sql="INSERT INTO promo_ventas 
                                 (Num_compra, Id_Promo, Fecha_Captura ) 
                          VALUES ('".$this->numcompra."','".$id_promo."',now() )";
                    
                    
                    $this->db->Execute($sql);
                             // // debug("IV )".$sql);         

                  
            }
           
           
            // Liberar al cliente del Promotor     
            // $sql=" DELETE FROM promo_cliente WHERE Num_cliente='".$num_cliente."' ";
            // $db->Execute($sql);
            //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
            //Generación del registro de la Factura del Cliente.
            //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//

            $sql = "SELECT count(*) FROM fact_cliente WHERE num_compra='".$this->numcompra."' ";
            $rs = $this->db->Execute($sql);
            $fact_cliente = $rs->fields[0];
            
            if($fact_cliente == 0) //Evitar la generación de duplicados.
            {
                    
                    $fecha_inicio =  $this->fecha_inicio;
// Metodo de amotrizacion Credito

// Composicion Moratorios

// Tasa en formato original

// Monto de Comision Apertura

// Método de amortización comision


if($this->metodo == 'Saldos Insolutos')
  $TASA_MENSUAL = 100 * $this->tasa_mensual_ssi;
else
  $TASA_MENSUAL = 100 * $this->tasa_mensual_ssol;




$REDONDEO = ($this->redondeocifras)?("Si"):("No");

$zona = (($this->iva_general * 100) >= 15)?('A'):('B');
          

                                                
                    $sql="INSERT INTO fact_cliente 
                          (ID_Factura, 
                           ID_Sucursal,
                           ID_Tipocredito,
                           num_factura, 
                           num_compra, 
                           num_cliente, 
                           fecha_exp, 
                           plazo, 
                           interes, 
                           int_moratorio, 
                           vencimiento , 
                           Fecha_Inicio, 
                           Fecha_Vencimiento,
                           TasaNominal, 
                           IVA_Interes, 
                           IVA_Moratorio, 
                           IVA_Comision, 
                           Zona_IVA,
                           Dias_Gracia, 
                           Capital, 
                           Renta, 
                           ID_Producto, 
                           Metodo,
                           Prelacion,
                           TasaOrigen,
                           TasaOrigen_Tipo,
                           Moratorio_Base_Capital,
                           Moratorio_Base_Interes,
                           Moratorio_Base_Comision,
                           Moratorio_Base_Moratorio,
                           Moratorio_Base_Otros,
                           Comision_Apertura,
                           Comision_Tipo,       
                           Comision_calculo,
                           RedondeoCifras,
                           MoratorioMinimo,
                           Nombre_Producto,
                           Gastos_Cobranza_Porcentaje,
                           Extemporaneos_Tipo
                        )                           
                        ( SELECT 
                                 NULL, 
                                 '".$this->id_sucursal."',
                                 ID_Tipocredito,
                                 '".$num_factura."',
                                 '".$this->numcompra."', 
                                 '".$this->num_cliente."', 
                                 '".$this->fecha_apertura."', 
                                 '".$this->plazo."',
                                 '".($this->tasa_golbal_efectiva*100)."', 
                                 TasaMoratoria, 
                                 Vencimiento,         
                                 '".$this->fecha_inicio."', 
                                 '".$this->ultimo_vencimiento."',
                                 '".$TASA_MENSUAL ."' AS  Tasa_Mensual, 
                                 '".($this->iva_general * 100)."', 
                                 '".($this->iva_general * 100)."', 
                                 '".($this->iva_general * 100)."', 
                                 '".$zona."',
                                 Dias_Gracia, 
                                 '".$this->capital."', 
                                 '".$this->renta."', 
                                 ID_Producto, 
                                 Metodo,
                                 Prelacion,
                                 TasaMensual,
                                 TasaMensual_Tipo,
                                 Moratorio_Base_Capital,
                                 Moratorio_Base_Interes,
                                 Moratorio_Base_Comision,
                                 Moratorio_Base_Moratorio,
                                 Moratorio_Base_Otros,
                                 Comision_Apertura,
                                 Comision_Tipo, 
                                 Comision_calculo,
                                 RedondeoCifras,
                                 MoratorioMinimo,
                                 Nombre,
                                 Gastos_Cobranza_Porcentaje,
                                 Extemporaneos_Tipo
                                 
                                 
                        FROM  cat_productosfinancieros
                        WHERE ID_Producto=".$this->id_producto." ) ";

                   
                    // debug($sql);
                    $this->db->Execute($sql);
                   /* XXX */ // 
                    
                    $this->id_factura = $this->db->_insertid();



	//======================================================================================================================
        // Generer ID para  Buró de Credito
	//======================================================================================================================
        
        if($this->id_factura > 0)
        {
        
        
                $sql = " SELECT clientes_datos.RFC
                         FROM   clientes_datos
                         WHERE  clientes_datos.Num_cliente = '".$this->num_cliente."'     ";    
        
                $rs = $this->db->Execute($sql);
                $rfc = strtoupper(trim($rs->fields[0]));
        
        
        
                $sql = " INSERT INTO buro_base (ID_Buro, ID_Factura, RFC) VALUES  (NULL, '".$this->id_factura."','".$rfc."') ";
                $this->db->Execute($sql);       
        }

	//======================================================================================================================
        // PEFIL TRANSACCIONAL : Prevención de lavado de dinero
	//======================================================================================================================

        if($this->id_factura > 0)
        {


		$oriesgo = new TRIESGO($this->db, $this->num_cliente);

		$oriesgo->evalua_estado_vectores();

		$oriesgo->actualiza_perfil_transaccional();
		
		unset($oriesgo);


        }
























	
	//======================================================================================================================
	//Detalle de cargos extemporáneos
	//======================================================================================================================

	$sql="	SELECT COUNT(*) FROM cat_productosfinancieros_ext WHERE cat_productosfinancieros_ext.ID_Producto = '".$this->id_producto."' ";
	$rs = $this->db->Execute($sql);
	
	if(($rs->fields[0]>0) and ($this->id_factura > 0))
	{

		$sql="	INSERT INTO fact_cliente_ext 
			(ID_Factura, Dias, Monto)
			(
			SELECT 		'".$this->id_factura."' 		AS ID_Factura,
					cat_productosfinancieros_ext.Dias	AS Dias,
					cat_productosfinancieros_ext.Monto	AS Monto

			FROM  		cat_productosfinancieros_ext
			WHERE 		cat_productosfinancieros_ext.ID_Producto = '".$this->id_producto."'
			ORDER BY 	cat_productosfinancieros_ext.Dias  )   ";
		 
		 $this->db->Execute($sql);
		 
		 //debug($sql);
	}        

	//======================================================================================================================

        if($this->cuota_segv > 0)
        {
        
            $sql= "    INSERT INTO fact_cliente_seguros
                        (id_factura,               tipo,   movimiento, fecha,                      costo_total_bruto,           costo_anual_bruto,       cuota_seguro,               cuota_iva)
                        VALUES
                        ('".$this->id_factura."', 'SegV', 'Alta',      '".$this->fecha_inicio."', '".$this->segv_total."',  '".$this->segv_anual."', '".$this->cuota_segv."',  '".($this->cuota_segv * $this->iva_general)."' ) ";
        
           $this->db->Execute($sql);
        
        }


        if($this->cuota_segd > 0)
        {
        
            $sql= "    INSERT INTO fact_cliente_seguros
                        (id_factura,               tipo,   movimiento, fecha,                      costo_total_bruto,           costo_anual_bruto,       cuota_seguro,               cuota_iva)
                        VALUES
                        ('".$this->id_factura."', 'SegD', 'Alta',      '".$this->fecha_inicio."', '".$this->segv_total."',   '".$this->segd_anual."', '".$this->cuota_segd."',  '".($this->cuota_segd * $this->iva_general)."' ) ";
        
           $this->db->Execute($sql);
        
        }



        if($this->cuota_segb > 0)
        {
        
            $sql= "    INSERT INTO fact_cliente_seguros
                        (id_factura,               tipo,   movimiento, fecha,                      costo_total_bruto,           costo_anual_bruto,       cuota_seguro,               cuota_iva)
                        VALUES
                        ('".$this->id_factura."', 'SegB', 'Alta',      '".$this->fecha_inicio."', '".$this->segb_total."',   '".$this->segb_anual."', '".$this->cuota_segb."',  '".($this->cuota_segb * $this->iva_general)."' ) ";
        
           $this->db->Execute($sql);
        
        }







        insertChequeCredito($this->id_factura);



            }
    
            //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//                                                        
            // ------>Generar los CARGOS 
            //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//                                                        
                                                        

                 //--------Obtener el total de la factura 
/*
            $sql= "INSERT INTO cargos 
                                               
                   (ID_Cargo, ID_Concepto, num_compra, num_factura, fecha_vencimiento, Monto, Capital, Interes, IVA,  Comision, SubTipo, Concepto,  Observaciones)
                                               
                    VALUES    ";                                          
*/
            $sql= "INSERT INTO cargos 
                                               
                   (ID_Cargo, ID_Concepto, 
                              num_compra, 
                              num_factura, 
                              fecha_vencimiento, 
                              Monto, 
                              
                              Capital, 
                              
                              Interes, 
                              IVA_Interes, 
                              
                              Comision, 
                              IVA_Comision, 
                              
                              SegV,
                              IVA_SegV,
                              
                              SegD,
                              IVA_SegD,
                              
                              SegB,
                              IVA_SegB,

                              IVA, 
                              SubTipo, 
                              Concepto,  
                              Observaciones)
                                               
                    VALUES    ";                                          



                foreach($this->tabla_amortizacion AS $i=>$row)
                {

                        $ID=(!$i)?(count($this->tabla_amortizacion)):($i);              


                        if($i)
                        {

                                $_id_concepto = "-3";   
                                $_leyenda     = "Cuota ".$this->tipo_plazox ." no. ".$i." de ".$this->plazo." ";

                                 
                                                $sql .= "\n('".($ID)."',
                                                            '".$_id_concepto."', 
                                                            '".$this->numcompra."',
                                                            '".$num_factura."',";

                                                 $sql .= "  '".gfecha($row['FECHA'])."',
                                                            '".number_format($row['RENTA'],4,".","")."',
                                                            '".number_format($row['ABONO_CAPITAL'],4,".","")."',                    

                                                            '".number_format( $row['ABONO_INTERES'],4,".","")."', 
                                                            '".number_format(($row['ABONO_INTERES'] * $this->iva_general),4,".","")."',                                                             

                                                            '".number_format( $row['COMISION']     ,4,".","")."',
                                                            '".number_format(($row['COMISION']      * $this->iva_general),4,".","")."',
                                                            
                                                            
                                                            '".number_format( $row['ABONO_SEGV']   ,4,".","")."', 
                                                            '".number_format(($row['ABONO_SEGV']    * $this->iva_general),4,".","")."',                                                             
                                                                                                                   
                                                            '".number_format( $row['ABONO_SEGD']   ,4,".","")."', 
                                                            '".number_format(($row['ABONO_SEGD']    * $this->iva_general),4,".","")."',                                                             
                                                            
                                                            '".number_format( $row['ABONO_SEGB']   ,4,".","")."', 
                                                            '".number_format(($row['ABONO_SEGB']    * $this->iva_general),4,".","")."',                                                             
                                                            
                                                            
                                                            
                                                            
                                                            '".number_format($row['ABONO_IVA']    ,4,".","")."', 
                                                            'General', ";               
                                                $sql .= " '".$_leyenda."','') , \n";    
                                 
                                 
                                 



                        }
                        else
                        {
/*                              if($this->monto_comision_anticipada >0)
                                {
                                
                                        $_id_concepto = "-8";   
                                        $_leyenda     = "Comisión por apertura. ";


                                                         $sql .= "\n('".($ID)."',
                                                                     '".$_id_concepto."', 
                                                                     '".$this->numcompra."',
                                                                     '".$num_factura."',";

                                                         $sql .= "  '".gfecha($row['FECHA'])."',
                                                                    '".number_format($row['RENTA'],4,".","")."',
                                                                    '".number_format($row['ABONO_CAPITAL'],4,".","")."',                    
                                                                    '".number_format($row['ABONO_INTERES'],4,".","")."',       
                                                                    '".number_format($row['ABONO_IVA']    ,4,".","")."', 
                                                                    '".number_format($row['COMISION']     ,4,".","")."',
                                                                    'Comision', ";               

                                                        $sql .= " '".$_leyenda."','') , \n";    
                                
                                
                                
                                }
                        
*/                      
                        }


                }


             
             // Quitar la última coma


        $sql = substr($sql, 0, -4);
      
       
       $this->db->Execute($sql);
                                    
              

                        
                        //// debug($this->id_factura." ".$this->numcompra);

                        //Log de creditos creados AQUI
/*
ALTER TABLE fact_cliente CHANGE Metodo Metodo ENUM('Saldos Solutos','Saldos Insolutos') DEFAULT 'Saldos Insolutos' NOT NULL;
ALTER TABLE fact_cliente ADD TasaOrigen DOUBLE(11,8) UNSIGNED DEFAULT '0.0' NOT NULL;
ALTER TABLE fact_cliente ADD TasaOrigen_Tipo ENUM('Soluta','Insoluta') DEFAULT 'Insoluta' NOT NULL AFTER TasaOrigen;


ALTER TABLE fact_cliente ADD Moratorio_Base_Capital    ENUM('Si','No') DEFAULT 'Si' NOT NULL;
ALTER TABLE fact_cliente ADD Moratorio_Base_Interes    ENUM('Si','No') DEFAULT 'No' NOT NULL;
ALTER TABLE fact_cliente ADD Moratorio_Base_Comision   ENUM('Si','No') DEFAULT 'No' NOT NULL;
ALTER TABLE fact_cliente ADD Moratorio_Base_Moratorio  ENUM('Si','No') DEFAULT 'No' NOT NULL;



ALTER TABLE fact_cliente ADD Comision_Apertura      DOUBLE(12,2) UNSIGNED           DEFAULT '0' NOT NULL;
ALTER TABLE fact_cliente ADD Comision_Tipo          ENUM('Diferida','Anticipada')   DEFAULT 'Diferida' NOT NULL;
ALTER TABLE fact_cliente ADD Comision_calculo       ENUM('Porcentual','Cuota')      DEFAULT 'Porcentual' NOT NULL;



*/



}


function calcula_primer_vencimiento()
{

    $calcula_primer_vencimiento = false;
    
    
    if($this->id_tipocredito == 1)
    { 
    
		if($this->periodo == 'Semanas')
		{


			$primera_cuota_semanal_fecha_individual = system_const('primera_cuota_semanal_fecha_individual',$this->db);
			


			list($_yy,$_mm,$_dd) = explode("-",$this->fecha_inicio);
			
			if($primera_cuota_semanal_fecha_individual == 'Natural')
			{
				$_dd += $this->dias_periodo;
				$fecha_primer_vencimiento = date("d/m/Y",mktime(0,0,0,$_mm,$_dd,$_yy));
				
				return($fecha_primer_vencimiento);
			}
			else
			{
			
				$primera_cuota_semanal_dias_minimos_individual = system_const('primera_cuota_semanal_dias_minimos_individual',$this->db);
				
				$_dd += $primera_cuota_semanal_dias_minimos_individual;
				
				
				$fecha_primer_vencimiento = date("d/m/Y",mktime(0,0,0,$_mm,$_dd,$_yy));
				

				$a_dias = array("Lunes"=>1,"Martes"=>2,"Miercoles"=>3,"Jueves"=>4,"Viernes"=>5,"Sabado"=>6);
				
				
				
				
				for($i=0; $i<=7; $i++)
				{
				 				     
				     $W = date("w",mktime(0,0,0,$_mm,$_dd,$_yy));
				     
				     if($W == $a_dias[$primera_cuota_semanal_fecha_individual])
				     {
				     
				     	break;
				     }
				     else
				     {
				         $_dd++;
				     }
				}

				$fecha_primer_vencimiento = date("d/m/Y",mktime(0,0,0,$_mm,$_dd,$_yy));
				
				return($fecha_primer_vencimiento);


			
			}


			

		} 
		else
		if($this->periodo == 'Catorcenas')
		{
		
			$primera_cuota_catorcenal_fecha_individual = system_const('primera_cuota_catorcenal_fecha_individual',$this->db);
			

			list($_yy,$_mm,$_dd) = explode("-",$this->fecha_inicio);
			
			if($primera_cuota_catorcenal_fecha_individual == 'Natural')
			{
				$_dd += $this->dias_periodo;
				$fecha_primer_vencimiento = date("d/m/Y",mktime(0,0,0,$_mm,$_dd,$_yy));
				
				return($fecha_primer_vencimiento);
			}
			else
			{
			
				$primera_cuota_catorcenal_dias_minimos_individual = system_const('primera_cuota_catorcenal_dias_minimos_individual',$this->db);
				
				$_dd += $primera_cuota_catorcenal_dias_minimos_individual;
								
				$fecha_primer_vencimiento = date("d/m/Y",mktime(0,0,0,$_mm,$_dd,$_yy));
				
				$a_dias = array("Lunes"=>1,"Martes"=>2,"Miercoles"=>3,"Jueves"=>4,"Viernes"=>5,"Sabado"=>6);
											
				
				for($i=0; $i<=7; $i++)
				{
				 				     
				     $W = date("w",mktime(0,0,0,$_mm,$_dd,$_yy));
				     
				     if($W == $a_dias[$primera_cuota_catorcenal_fecha_individual])
				     {
				     
				     	break;
				     }
				     else
				     {
				         $_dd++;
				     }
				}

				$fecha_primer_vencimiento = date("d/m/Y",mktime(0,0,0,$_mm,$_dd,$_yy));
				
				return($fecha_primer_vencimiento);
			}
		
		}
		else
		if($this->periodo == 'Quincenas')
		{

			$primera_cuota_quincenal_fecha_individual = system_const('primera_cuota_quincenal_fecha_individual',$this->db);


			list($_yy,$_mm,$_dd) = explode("-",$this->fecha_inicio);
			
			if($primera_cuota_quincenal_fecha_individual == 'Natural')
			{
				$_dd += $this->dias_periodo;
				$fecha_primer_vencimiento = date("d/m/Y",mktime(0,0,0,$_mm,$_dd,$_yy));
				
				return($fecha_primer_vencimiento);
			}
			else
			{

				$primera_cuota_quincenal_dias_minimos_individual = system_const('primera_cuota_quincenal_dias_minimos_individual',$this->db);
				
			
				$_dd += $primera_cuota_quincenal_dias_minimos_individual;


				$fecha_primer_vencimiento = date("d/m/Y",mktime(0,0,0,$_mm,$_dd,$_yy));
				

				
				for($i=0; $i<=31; $i++)
				{
				 				     
				     $tmp_date = date("Y-m-d",mktime(0,0,0,$_mm,$_dd,$_yy));
				     list($_yy,$_mm,$_dd) = explode("-",$tmp_date );
				     
				     $ultimo_dia_mes = (int) date("t",mktime(0,0,0,$_mm,1,$_yy));
				     
				     
				     if($primera_cuota_quincenal_fecha_individual == '1')
				     {
				     		if((($_dd * 1) == 1) or (($_dd * 1) == 16))
				     		{
				     		   break;
				     		}
				     }
				     else
				     if($primera_cuota_quincenal_fecha_individual == '2')
				     {				     
				     		if((($_dd * 1) == 15) or (($_dd * 1) == $ultimo_dia_mes))
				     		{				     		
				     		   break;
				     		}				     
				     }
				     
				     
				     
				     
				     $_dd++;
				    
				}

				$fecha_primer_vencimiento = date("d/m/Y",mktime(0,0,0,$_mm,$_dd,$_yy));
				
				return($fecha_primer_vencimiento);
			}

		}
		if($this->periodo == 'Meses')
		{
		
			$primera_cuota_mensual_fecha_individual = system_const('primera_cuota_mensual_fecha_individual',$this->db);


			list($_yy,$_mm,$_dd) = explode("-",$this->fecha_inicio);
			
			if($primera_cuota_mensual_fecha_individual == 'Natural')
			{
				$_mm++;
				
				$fecha_primer_vencimiento = fechavencimiento(ffecha($this->fecha_inicio), $this->frecuencia); 
								
				return($fecha_primer_vencimiento);
			}
			else
			{
			
				$primera_cuota_mensual_dias_minimos_individual = system_const('primera_cuota_mensual_dias_minimos_individual',$this->db);
				
			
				$_dd += $primera_cuota_mensual_dias_minimos_individual;
				

				$fecha_primer_vencimiento = date("d/m/Y",mktime(0,0,0,$_mm,$_dd,$_yy));
				
				
				for($i=0; $i<=31; $i++)
				{
				 				     
				     $tmp_date = date("Y-m-d",mktime(0,0,0,$_mm,$_dd,$_yy));

				     list($_yy,$_mm,$_dd) = explode("-",$tmp_date );
				     
				     $ultimo_dia_mes = (int) date("t",mktime(0,0,0,$_mm,1,$_yy));
				     

				     
				     
				     if($primera_cuota_mensual_fecha_individual == 'ultimo')
				     {
				     		if(($_dd * 1) == $ultimo_dia_mes)
				     		{
				     		   break;
				     		}
				     }
				     else
				     if($primera_cuota_mensual_fecha_individual == $_dd)
				     {				     
				     		   break;				     
				     }
				     
				     $_dd++;
				    
				}

				$fecha_primer_vencimiento = date("d/m/Y",mktime(0,0,0,$_mm,$_dd,$_yy));
				
				return($fecha_primer_vencimiento);
			}
		
		
		}
         
    
    }








    if($this->id_tipocredito == 3)
    { 
    
		if($this->periodo == 'Semanas')
		{


			$primera_cuota_semanal_fecha_nomina = system_const('primera_cuota_semanal_fecha_nomina',$this->db);
			


			list($_yy,$_mm,$_dd) = explode("-",$this->fecha_inicio);
			
			if($primera_cuota_semanal_fecha_nomina == 'Natural')
			{
				$_dd += $this->dias_periodo;
				$fecha_primer_vencimiento = date("d/m/Y",mktime(0,0,0,$_mm,$_dd,$_yy));
				
				return($fecha_primer_vencimiento);
			}
			else
			{
			
				$primera_cuota_semanal_dias_minimos_nomina = system_const('primera_cuota_semanal_dias_minimos_nomina',$this->db);
				
				$_dd += $primera_cuota_semanal_dias_minimos_nomina;
				
				
				$fecha_primer_vencimiento = date("d/m/Y",mktime(0,0,0,$_mm,$_dd,$_yy));
				

				$a_dias = array("Lunes"=>1,"Martes"=>2,"Miercoles"=>3,"Jueves"=>4,"Viernes"=>5,"Sabado"=>6);
				
				
				
				
				for($i=0; $i<=7; $i++)
				{
				 				     
				     $W = date("w",mktime(0,0,0,$_mm,$_dd,$_yy));
				     
				     if($W == $a_dias[$primera_cuota_semanal_fecha_nomina])
				     {
				     
				     	break;
				     }
				     else
				     {
				         $_dd++;
				     }
				}

				$fecha_primer_vencimiento = date("d/m/Y",mktime(0,0,0,$_mm,$_dd,$_yy));
				
				return($fecha_primer_vencimiento);


			
			}


			

		} 
		else
		if($this->periodo == 'Catorcenas')
		{
		
			$primera_cuota_catorcenal_fecha_nomina = system_const('primera_cuota_catorcenal_fecha_nomina',$this->db);
			

			list($_yy,$_mm,$_dd) = explode("-",$this->fecha_inicio);
			
			if($primera_cuota_catorcenal_fecha_nomina == 'Natural')
			{
				$_dd += $this->dias_periodo;
				$fecha_primer_vencimiento = date("d/m/Y",mktime(0,0,0,$_mm,$_dd,$_yy));
				
				return($fecha_primer_vencimiento);
			}
			else
			{
			
				$primera_cuota_catorcenal_dias_minimos_nomina = system_const('primera_cuota_catorcenal_dias_minimos_nomina',$this->db);
				
				$_dd += $primera_cuota_catorcenal_dias_minimos_nomina;
								
				$fecha_primer_vencimiento = date("d/m/Y",mktime(0,0,0,$_mm,$_dd,$_yy));
				
				$a_dias = array("Lunes"=>1,"Martes"=>2,"Miercoles"=>3,"Jueves"=>4,"Viernes"=>5,"Sabado"=>6);
											
				
				for($i=0; $i<=7; $i++)
				{
				 				     
				     $W = date("w",mktime(0,0,0,$_mm,$_dd,$_yy));
				     
				     if($W == $a_dias[$primera_cuota_catorcenal_fecha_nomina])
				     {
				     
				     	break;
				     }
				     else
				     {
				         $_dd++;
				     }
				}

				$fecha_primer_vencimiento = date("d/m/Y",mktime(0,0,0,$_mm,$_dd,$_yy));
				
				return($fecha_primer_vencimiento);
			}
		
		}
		else
		if($this->periodo == 'Quincenas')
		{

			$primera_cuota_quincenal_fecha_nomina = system_const('primera_cuota_quincenal_fecha_nomina',$this->db);


			list($_yy,$_mm,$_dd) = explode("-",$this->fecha_inicio);
			
			if($primera_cuota_quincenal_fecha_nomina == 'Natural')
			{
				$_dd += $this->dias_periodo;
				$fecha_primer_vencimiento = date("d/m/Y",mktime(0,0,0,$_mm,$_dd,$_yy));
				
				return($fecha_primer_vencimiento);
			}
			else
			{

				$primera_cuota_quincenal_dias_minimos_nomina = system_const('primera_cuota_quincenal_dias_minimos_nomina',$this->db);
				
			
				$_dd += $primera_cuota_quincenal_dias_minimos_nomina;


				$fecha_primer_vencimiento = date("d/m/Y",mktime(0,0,0,$_mm,$_dd,$_yy));
				

				
				for($i=0; $i<=31; $i++)
				{
				 				     
				     $tmp_date = date("Y-m-d",mktime(0,0,0,$_mm,$_dd,$_yy));
				     list($_yy,$_mm,$_dd) = explode("-",$tmp_date );
				     
				     $ultimo_dia_mes = (int) date("t",mktime(0,0,0,$_mm,1,$_yy));
				     
				     
				     if($primera_cuota_quincenal_fecha_nomina == '1')
				     {
				     		if((($_dd * 1) == 1) or (($_dd * 1) == 16))
				     		{
				     		   break;
				     		}
				     }
				     else
				     if($primera_cuota_quincenal_fecha_nomina == '2')
				     {				     
				     		if((($_dd * 1) == 15) or (($_dd * 1) == $ultimo_dia_mes))
				     		{				     		
				     		   break;
				     		}				     
				     }
				     
				     
				     
				     
				     $_dd++;
				    
				}

				$fecha_primer_vencimiento = date("d/m/Y",mktime(0,0,0,$_mm,$_dd,$_yy));
				
				return($fecha_primer_vencimiento);
			}

		}
		if($this->periodo == 'Meses')
		{
		
			$primera_cuota_mensual_fecha_nomina = system_const('primera_cuota_mensual_fecha_nomina',$this->db);


			list($_yy,$_mm,$_dd) = explode("-",$this->fecha_inicio);
			
			if($primera_cuota_mensual_fecha_nomina == 'Natural')
			{
				$_mm++;
				
				$fecha_primer_vencimiento = fechavencimiento(ffecha($this->fecha_inicio), $this->frecuencia); 
								
				return($fecha_primer_vencimiento);
			}
			else
			{
			
				$primera_cuota_mensual_dias_minimos_nomina = system_const('primera_cuota_mensual_dias_minimos_nomina',$this->db);
				
			
				$_dd += $primera_cuota_mensual_dias_minimos_nomina;
				

				$fecha_primer_vencimiento = date("d/m/Y",mktime(0,0,0,$_mm,$_dd,$_yy));
				
				
				for($i=0; $i<=31; $i++)
				{
				 				     
				     $tmp_date = date("Y-m-d",mktime(0,0,0,$_mm,$_dd,$_yy));

				     list($_yy,$_mm,$_dd) = explode("-",$tmp_date );
				     
				     $ultimo_dia_mes = (int) date("t",mktime(0,0,0,$_mm,1,$_yy));
				     

				     
				     
				     if($primera_cuota_mensual_fecha_nomina == 'ultimo')
				     {
				     		if(($_dd * 1) == $ultimo_dia_mes)
				     		{
				     		   break;
				     		}
				     }
				     else
				     if($primera_cuota_mensual_fecha_nomina == $_dd)
				     {				     
				     		   break;				     
				     }
				     
				     $_dd++;
				    
				}

				$fecha_primer_vencimiento = date("d/m/Y",mktime(0,0,0,$_mm,$_dd,$_yy));
				
				return($fecha_primer_vencimiento);
			}
		
		
		}
         
    
    }










}


};



?>