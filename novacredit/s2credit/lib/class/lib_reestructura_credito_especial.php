<?
//=====================================================================================================================//
// Class TREESTRUCTURA : Salda un crédito y genera otro
//=====================================================================================================================//
require_once($class_path."lib_credit.php");
require_once($class_path."lib_credimov.php");
require_once($class_path."lib_nuevo_credito.php");

class TREESTRUCTURA
{


  var $obj_credito;

  var $num_cliente;

  var $id_factura_origen;
  var $id_factura_destino;
  var $db;

  var $status=0;
  var $genera;
  var $num_compra;

  var $monto_capital_reestructura;
 
  var $monto_comision_reestructura;
  var $monto_iva_comision_reestructura;
 
  var $monto_interes_reestructura;
  var $monto_iva_interes_reestructura;


  var $monto_total_reestructura;

  var $capital_autorizado;
  var $fecha_apertura;
  var $fecha_inicio;
      
  var $id_producto;
  var $plazo;
  var $id_sucursal;



function TREESTRUCTURA($idfactura, $fecha_corte, $id_producto, $fecha_inicio, $capital_autorizado, $plazo, $tipo=0)  // Constructor
{
    
    $this->db = &ADONewConnection(SERVIDOR);  # create a connection
    $this->db->Connect(IP,USER,PASSWORD,NUCLEO);

    $this->id_factura_origen    = trim($idfactura);
    $this->fecha_corte          = $fecha_corte;

    $this->tipo          = $tipo;
    
    
    $this->id_producto          = $id_producto;
    
    $this->obj_credito          = new TCUENTA( $this->id_factura_origen, $this->fecha_corte,'','',true);

    $this->oMov = new TCREDITO($this->db, $this->obj_credito );

     if(!empty($this->oMov->error_msg))
     {
        error_msg(" Error ".$this->oMov->error_msg);
        $this->status = -10;
        return(-1);
     
     }
     




    $this->num_compra           = $this->obj_credito->numcompra;
    $this->plazo                = $plazo;
    $this->fecha_apertura       = $this->fecha_corte;
    $this->fecha_inicio         = $fecha_inicio;
    $this->capital_autorizado   = $capital_autorizado;
    
    $this->status = -10;
    
    $this->verifica_condiciones_iniciales();



}

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Aplica la reestructura
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

function aplica_reestructura()
{
        
    global $_SESSION;
    
    if( $this->status == 0)
    {
    
    
        if($this->genera->no_generar == 0) 
        {
   
 
 
                
               $this->aplica_saldar_cuenta_reestructura();                        
               $this->aplica_vencimento_anticipado();
               
                
                
                $this->oMov->refresh();
                
                $this->obj_credito = &$this->oMov->edo_cuenta;
                
                
                if($this->obj_credito->adeudo_total < 0.005)
                {



                        $response =  $this->genera_nuevo_credito();
 
                        
                        if($this->id_factura_destino)
                        {
                        




                                {

                                $sql = "INSERT INTO restructura_credito 
                                        (id_factura_origen ,
                                         id_factura_destino ,
                                         tipo,
                                         
                                         fecha ,
                                         monto_capital ,

                                         monto_comision ,
                                         monto_comision_iva ,
                                         
                                         monto_interes ,
                                         monto_interes_iva ,
                                         
                                         monto_moratorio ,
                                         monto_moratorio_iva ,

                                         monto_otros ,
                                         monto_otros_iva ,
                                                                                  
                                         total_restructurado ,
                                         capital_nuevo ,
                                         fecha_captura ,
                                         usuario) 
                                         
                                         VALUES 
                                         ('".$this->id_factura_origen."',
                                          '".$this->id_factura_destino."',
                                          '".$this->tipo."',
                                          
                                          
                                          '".$this->fecha_corte."',
                                          '".$this->monto_capital_reestructura."',
                                          
                                          '".$this->monto_comision_reestructura ."',
                                          '".$this->monto_iva_comision_reestructura."',
                                          
                                          '".$this->monto_interes_reestructura  ."',
                                          '".$this->monto_iva_interes_reestructura ."',
                                          
                                          '".$this->monto_moratorio_reestructura."',                                          
                                          '".$this->monto_iva_moratorio_reestructura."',
                                          
                                          '".$this->monto_otros_reestructura."',                                          
                                          '".$this->monto_iva_otros_reestructura."',
                                          
                                          
                                          '".$this->monto_total_reestructura."',
                                          '".$this->capital_autorizado."',
                                          now(),
                                          '".$_SESSION['NOM_USR']."'
                                        )";
                                        
                                $this->db->Execute($sql);
                               } 
                                       
                                        
                                        
                        }
                        else
                        {
                                //Como no se generó el nuevo crédito, le damos Rollback al vencimiento anticipado.
                                
                                
                                $sql = "DELETE FROM cargos WHERE ID_Concepto = -3 and Concepto LIKE 'Vencimiento anticipado de la cuota%' and num_compra='".$this->num_compra."' ";
                                $this->db->Execute($sql);
 
                                $sql = "UPDATE cargos SET Activo='Si' WHERE ID_Concepto = -3 and  num_compra='".$this->num_compra."' ";
                                $this->db->Execute($sql);

                                $sql = "DELETE FROM cargos WHERE ID_Concepto IN( -30,-31,-32,-33,-34) and  num_compra='".$this->num_compra."' ";
                                $this->db->Execute($sql);
                              
                        
                        }
                        
  
  
                        return(0);
                }
                else
                {
                
                        $this->genera->error = "Error : El crédito original no ha sido saldado : ".$this->obj_credito->adeudo_total;
                        
                        return -1;
                
                }
                
                   
                   
                   
                   
        }
        else
        {
            
            $this->genera->error = "No se encontró la sucursal del cliente, imposible generar el crédito. ";
            
            return -1;
        
        
        }
    }

   return $this->status;

}









function genera_nuevo_credito()
{

                        
                        $id_fondeo      =1;
                        $id_asoc_compra =1;
                        
                        if($this->genera->no_generar>0)
                        {
                                $this->genera->error = "No se encontró la sucursal del cliente, imposible generar el crédito. ";
                                
                                return -1;
                        
                        }
                        else
                        {
                                $response = $this->genera->genera_credito_nuevo($id_fondeo, $id_asoc_compra,  '');
                                
                                $this->id_factura_destino = $this->genera->id_factura;
                                return $response;
                        
                        }



}


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Verifica si es posible aplicar la reestructura
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------



function verifica_condiciones_iniciales()
{

        // Verificar que existan condiciones adecuadas para el procesar el crédito
        
        
        if($this->tipo == 0)
        {
        
                if($this->obj_credito->SaldoGeneralVencido  > 0.005)
                {
                        $this->status =   -1 ; 
                        $this->error_msg = "El crédito tiene saldo vencido.";
                }     
                
        }        

        if($this->obj_credito->SaldoGlobalGeneral  <  0.005)
        {
                $this->status =   -2 ; 
                $this->error_msg = "El crédito está saldado ";
        }
           

        if($this->obj_credito->saldo_para_liquidar_hoy  >  $this->capital_autorizado )
        {
                $this->status =   -3 ; 
                $this->error_msg = "El capital autorizado es insuficiente";
        }


        if($this->tipo == 0)
        {
                if($this->obj_credito->avance == 100)
                {
                        $this->status =   -4 ; 
                        $this->error_msg = "El crédito está totalmente vencido, no es objeto de una renovación. ";

                }
        }


        $sql = "SELECT COUNT(*) FROM pagos WHERE Fecha > '".$this->obj_credito->fecha_corte."' and num_compra = '".$this->obj_credito->numcompra."' ";
        //debug($sql);
        $rs = $this->db->Execute($sql);
        if($rs->fields[0]>0)
        {
                $this->status =  -5 ; 
                $this->error_msg = "Existen pagos posteriores a la fecha de corte a la que se desea efectuar la operación ";   
        }


        $sql = "SELECT COUNT(*) FROM restructura_credito WHERE  id_factura_origen = '".$this->id_factura_origen."' ";
        $rs = $this->db->Execute($sql);
        if($rs->fields[0]>0)
        {
                $this->status =  -6 ; 
                $this->error_msg = "El crédito ya había sido reestructurado previamente ";  
        }


        if($this->status == -10) //Situación inicial
                $this->status = 0;


        if($this->status < 0)
           return($this->status);


        
        $this->num_cliente = $this->obj_credito->numcliente;




        $this->monto_capital_reestructura       = 0;

        //------------------------------------------


        $this->monto_comision_reestructura      = 0;
        $this->monto_iva_comision_reestructura  = 0;

        //------------------------------------------

        $this->monto_interes_reestructura       = 0;
        $this->monto_iva_interes_reestructura   = 0;


        //------------------------------------------

        $this->monto_moratorio_reestructura      = 0;
        $this->monto_iva_moratorio_reestructura  = 0;

        //------------------------------------------

        $this->monto_otros_reestructura          = 0;        
        $this->monto_iva_otros_reestructura      = 0;       

        //------------------------------------------

        $this->monto_total_reestructura          = 0; 






        //----------------------------------------------------------------------------------------------
        // Saldos vencidos a reestructurar
        //----------------------------------------------------------------------------------------------
        
                        $this->monto_capital_reestructura      += $this->obj_credito->SaldoCapital;

                        //----------------------------------------------------------------------------------------------


                        $this->monto_comision_reestructura     += $this->obj_credito->SaldoComision;
                        $this->monto_iva_comision_reestructura += $this->obj_credito->SaldoIVAComision;

                        //----------------------------------------------------------------------------------------------

                        $this->monto_interes_reestructura       += $this->obj_credito->SaldoInteres;
                        $this->monto_iva_interes_reestructura   += $this->obj_credito->SaldoIVAInteres;


                        //----------------------------------------------------------------------------------------------

                        $this->monto_moratorio_reestructura       += $this->obj_credito->SaldoIM;
                        $this->monto_iva_moratorio_reestructura   += $this->obj_credito->SaldoIVAIM;

                        //----------------------------------------------------------------------------------------------

                        $this->monto_otros_reestructura       += $this->obj_credito->SaldoOtros;
                        $this->monto_iva_otros_reestructura   += $this->obj_credito->SaldoIVAOtros;



        //----------------------------------------------------------------------------------------------
        // Saldos vigentes a reestructurar
        //----------------------------------------------------------------------------------------------


                        $this->monto_capital_reestructura  += $this->obj_credito->SaldoCapitalPorVencer;

                        //----------------------------------------------------------------------------------------------


                        $VencimientoAnticipado_Comision    = $this->obj_credito->SaldoComisionPorVencer;
                        $IVA_Comision                      = $this->obj_credito->Saldo_IVA_ComisionPorVencer;


                        $this->monto_comision_reestructura     += $VencimientoAnticipado_Comision;
                        $this->monto_iva_comision_reestructura += $IVA_Comision;

                        //----------------------------------------------------------------------------------------------

                
                        $ID_Cuota = $this->obj_credito->numcargosvencidos;

                        $sqlNextCuota= "        SELECT  (cargos.Interes - cargos.AntiInteres) AS Interes,
                                                         cargos.IVA_Interes
                                                FROM    cargos
                                                WHERE   cargos.ID_Cargo  > '".$ID_Cuota."'                   and 
                                                        num_compra = '".$this->obj_credito->numcompra."'     and
                                                        cargos.ID_Concepto = -3                              and 
                                                        cargos.Activo      ='Si' 

                                                ORDER BY Fecha_vencimiento ";
                        //debug( $sqlNextCuota);



                        $rn=$this->db->Execute($sqlNextCuota);

                        $proxima_cuota_interes          = (($rn->fields['Interes']    +  $rn->fields['IVA_Interes'] ) + 
                                                          ($this->obj_credito->abonos_contra_saldos_vigentes_por_couta[($ID_Cuota+1)]['Interes'] +                
                                                           $this->obj_credito->abonos_contra_saldos_vigentes_por_couta[($ID_Cuota+1)]['Interes_IVA']));


                        $VencimientoAnticipado_Interes  =  $rn->fields['Interes']     ;//+ $this->obj_credito->abonos_contra_saldos_vigentes_por_couta[($ID_Cuota+1)]['Interes'];
                        $IVA_Interes                    =  $rn->fields['IVA_Interes'] ;//+ $this->obj_credito->abonos_contra_saldos_vigentes_por_couta[($ID_Cuota+1)]['Interes_IVA'];
   
   
   
   
   			//debug($VencimientoAnticipado_Interes  );
   			//debug($IVA_Interes  );   
   			
   			//debug("<HR>");
   			
   			//debug($this->monto_interes_reestructura  ); 
   			//debug($this->monto_iva_interes_reestructura  );  			
   
                        
                        $this->monto_interes_reestructura_vencida    =   $this->monto_interes_reestructura;       
                        $this->monto_iva_interes_reestructura_vencida=   $this->monto_iva_interes_reestructura;
                        
                        
                        $this->monto_interes_reestructura_vigente    =   $VencimientoAnticipado_Interes ; 
                        $this->monto_iva_interes_reestructura_vigente=   $IVA_Interes;
                        
                        
                        
                        
                        $this->monto_interes_reestructura       += $VencimientoAnticipado_Interes ;
                        $this->monto_iva_interes_reestructura   += $IVA_Interes;




                        //----------------------------------------------------------------------------------------------
                        $this->monto_total_reestructura = $this->monto_capital_reestructura        + 
                                                          
                                                          $this->monto_comision_reestructura       +
                                                          $this->monto_iva_comision_reestructura   +
                                                          
                                                          $this->monto_interes_reestructura        +
                                                          $this->monto_iva_interes_reestructura    +
                                                          
                                                          $this->monto_moratorio_reestructura      +                                                        
                                                          $this->monto_iva_moratorio_reestructura  +
                                                          
                                                          $this->monto_otros_reestructura          +
                                                          $this->monto_iva_otros_reestructura       ;
                                                          
        //----------------------------------------------------------------------------------------------

                        $this->monto_interes_depuracion       = $this->obj_credito->adeudo_total - $this->monto_total_reestructura ;     


        //----------------------------------------------------------------------------------------------
        //----------------------------------------------------------------------------------------------
        


        $this->capital_nuevo_credito = $this->capital_autorizado;
        

        $this->valor_cheque         = $this->capital_nuevo_credito - $this->monto_total_reestructura;
        
                        
    //  $this->genera  = new TNuevoCredito($this->num_cliente, $this->fecha_apertura, $this->fecha_inicio, $this->capital_autorizado, 		$this->id_producto, $this->plazo, $this->db);
        

        $this->genera  = new TNuevoCredito($this->num_cliente, $this->fecha_apertura, $this->fecha_inicio, $this->monto_total_reestructura, 	$this->id_producto, $this->plazo, $this->db);


        
        $this->genera->cotiza_credito();


        
        return 0;
}       


function show_error()
{

        switch($this->status)
        {
                case 0  : return("Todo en orden para proceder. "); 
        
                case -1 : return("El crédito tiene saldo vencido : ".number_format($this->obj_credito->SaldoGeneralVencido ,2)." al ".ffecha($this->fecha_corte)); 
        
                case -2 : return("El crédito ya está saldado ");

                case -3 : return("El capital autorizado es insuficiente.  Saldo para liquidar hoy : ".number_format($this->obj_credito->saldo_para_liquidar_hoy ,2).", - Capital autorizado : ".number_format($this->capital_autorizado,2)); 

                case -4 : return("El crédito está totalmente vencido, no es objeto de crédito para una renovación."); 

                case -5 : return("Existen pagos posteriores a la fecha de corte a la que se desea efectuar la operación ");

                case -6 : return("El crédito ya había sido reestructurado previamente ");

                default : return($this->error_msg);
        
        }

}



//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Saldar el crédito origen
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

function aplica_saldar_cuenta_reestructura()
{

        $this->oMov->refresh();
        
        $idva=0;
        if( $this->status >= 0)
        {
                      $cuota_desde = 0;
                      $cuota_hasta="99999";
                      
                       
                       $obj =& $this->oMov->edo_cuenta;
        
        
        
                        $num_compra  = $this->oMov->num_compra;
                        
                        
                        
                        $num_coutas_a_saldar = $obj->numcargosvencidos;


                        $fecha_corte         = $this->oMov->fecha_corte;

			//debug("for(cuota_desde = (".($obj->numcargosvencidos_pagados+1)."); $cuota_desde <= $num_coutas_a_saldar; $cuota_desde++))");

                        for($cuota_desde = ($obj->numcargosvencidos_pagados+1); $cuota_desde <= $num_coutas_a_saldar; $cuota_desde++)
                        {

                                if($cuota_desde > 0)
                                {
                                        //=======================================================================
                                        //      echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*** Saldando couta # ".$cuota_desde." <BR>";
                                        //=======================================================================

                                        //debug(print_r($this->oMov->edo_cuenta->aprelacion,1));
                                        //die();

                                        foreach($this->oMov->edo_cuenta->aprelacion AS $TIPO)
                                        {
                                                
                                                                //-----------------------------------------------------------------------------------------------------------------------//

                                                                switch( $TIPO )
                                                                {                       
                                                                        case 'M' : {    //-------------------------------
                                                                                        //Saldar Moratorios     
                                                                                        //-------------------------------

                                                                                        $monto_nota_credito =   ($obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_Moratorio']>=0.01)?($obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_Moratorio'] + $obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_IVA_Moratorio']        ):(0);

                                                                                        $id_concepto_nota   =   -30;


                                                                                        if($monto_nota_credito >0.004)
                                                                                        $this->oMov->aplica_nota_credito_salda_cuota($id_concepto_nota, $cuota_desde, $obj->fecha_corte  );

                                                                                        break;

                                                                                   }

                                                                        case 'A' : {    

                                                                                        //-------------------------------
                                                                                        //Saldar Comisión por Apertura  
                                                                                        //-------------------------------

                                                                                        $monto_nota_credito =   ($obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_Comision']>=0.01)?($obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_Comision']    + $obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_IVA_Comision']        ):(0);

                                                                                        $id_concepto_nota   =    -31;

                                                                                        if($monto_nota_credito >0.004)
                                                                                        $this->oMov->aplica_nota_credito_salda_cuota($id_concepto_nota, $cuota_desde, $obj->fecha_corte  );

                                                                                        break;

                                                                                    }


                                                                        case 'I' : {    //-------------------------------
                                                                                        //Saldar Interéses Normales
                                                                                        //-------------------------------

                                                                                        $monto_nota_credito =   ($obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_Interes']>=0.01)?($obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_Interes']    + $obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_IVA_Interes']           ):(0);

                                                                                        $id_concepto_nota   =    -32;

                                                                                        if($monto_nota_credito >0.004)
                                                                                        $this->oMov->aplica_nota_credito_salda_cuota($id_concepto_nota, $cuota_desde, $obj->fecha_corte  );

                                                                                        break;
                                                                                    }


                                                                        case 'C' : {    //-------------------------------
                                                                                        //Saldar Capital        
                                                                                        //-------------------------------

                                                                                        $monto_nota_credito =   ($obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_Capital']>=0.01)?($obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_Capital']):(0);

                                                                                        $id_concepto_nota   =    -33;

                                                                                        if($monto_nota_credito >0.004)
                                                                                        $this->oMov->aplica_nota_credito_salda_cuota($id_concepto_nota, $cuota_desde, $obj->fecha_corte  );

                                                                                        //debug(" if(monto_nota_credito{$monto_nota_credito} >0.004)   this->aplica_nota_credito(monto_nota_credito=$monto_nota_credito, id_concepto_nota=$id_concepto_nota, cuota_desde=$cuota_desde, fecha_corte =".$this->fecha_corte."  ); ");


                                                                                        break; 
                                                                                   }


                                                                        case 'E' : {    //-------------------------------
                                                                                        //Saldar Extemporáneos  
                                                                                        //-------------------------------

                                                                                        $monto_nota_credito =   ($obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_Otros']>=0.01      )?($obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_Otros']   + $obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_IVA_Otros']    ):(0);

                                                                                        $id_concepto_nota   =   -34;

                                                                                        if($monto_nota_credito >0.004)
                                                                                        $this->oMov->aplica_nota_credito_salda_cuota($id_concepto_nota, $cuota_desde, $obj->fecha_corte  );

                                                                                        break;
                                                                                   }
                                                                        //-----------------------------------------------------------------------------------------------------------------------//


                                                                }


                                        }



                                        

                                }

                        }

                        $obj = new TCUENTA( $this->oMov->id_factura, $this->oMov->fecha_corte);
                        
        
        }
}


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Aplica vencimiento anticipado si es posible al crédito origen
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------



function aplica_vencimento_anticipado()
{
        
        
        $idva=0;
        if( $this->status >= 0)
        {
            
            $proxima_cuota_interes   = $this->oMov->edo_cuenta->proxima_cuota_interes;
            
            $idva = $this->oMov->aplica_vencimiento_anticipado();
            
            $this->error_msg = $this->oMov->error_msg;
    
           if($idva > 0)   
            {

                $this->oMov->refresh();

                $obj =& $this->oMov->edo_cuenta;
        
        
                        $cuota_desde = $obj->id_cargo_reemplazo_vencimiento_anticipado;

                        //-------------------------------
                        //Saldar Capital por concepto de RENOVACION       
                        //-------------------------------

                        $monto_nota_credito =   $this->monto_capital_reestructura;
                        $id_concepto_nota   =    -33;

                        if($monto_nota_credito >0.004)
                        $this->oMov->aplica_nota_credito_salda_cuota( $id_concepto_nota, $cuota_desde, $this->fecha_corte );
                        

                        //-------------------------------
                        //Saldar Comisión por Apertura por concepto de RENOVACION   
                        //-------------------------------

                        $monto_nota_credito =   $this->monto_comision_reestructura + $this->monto_iva_comision_reestructura ;
                        $id_concepto_nota   =    -31;
    
                        if($monto_nota_credito >0.004)
                        $this->oMov->aplica_nota_credito_salda_cuota( $id_concepto_nota, $cuota_desde, $this->fecha_corte  );

                      
                        //-------------------------------
                        //Saldar Intereses (Solo siguiente cuota) Normales por concepto de RENOVACION 
                        //-------------------------------
                        
                        $monto_nota_credito =    $this->monto_interes_reestructura_vigente + $this->monto_iva_interes_reestructura_vigente;
                        $id_concepto_nota   =    -32;
   
                        if($monto_nota_credito >0.004)
                        $this->oMov->aplica_nota_credito($monto_nota_credito, $id_concepto_nota, $cuota_desde, $this->fecha_corte  );
                        
                        

    

                        
                        
                        $this->oMov->refresh();

                       
                        
                        $obj =& $this->oMov->edo_cuenta;




                        //-------------------------------
                        //Saldar Interéses Normales por concepto de DEPURACION a RENOVACION 
                        //-------------------------------
                        

                        
                        $monto_nota_credito =   ($obj->SaldoGlobalGeneral >=0.01)?($obj->SaldoGlobalGeneral):(0);
                        
                        $monto_nota_credito =   ($monto_nota_credito >=0.01)?($monto_nota_credito):(0);
                        
                        $id_concepto_nota   =    -35;
 
   
                        if($monto_nota_credito >0.004)
                        $this->oMov->aplica_nota_credito_salda_cuota($id_concepto_nota, $cuota_desde, $this->fecha_corte  );
                        
                        $this->oMov->refresh();
                        $this->obj_credito =& $this->oMov->edo_cuenta;
                        


            }
            else
            {
                if($this->tipo == 0)
                {
                
                        $this->status = -12;

                        $this->error_msg =   $this->oMov->error_msg;
                }
            }
           


        }
        
  return($idva);
}


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Salda todo el crédito couta por cuota
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------




};



