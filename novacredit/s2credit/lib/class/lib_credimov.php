<?
//=====================================================================================================================//
// Class TCREDITO : Genera movimientos para un estado de cuenta
//=====================================================================================================================//
require_once($DOCUMENT_ROOT."/rutas.php");
require_once($class_path."lib_credit_rsf.php");

class TCREDITO
{

  var $db;
  var $num_cliente;
  var $nombre_cliente;
  var $id_factura;
  var $id_pagos_a_ignorar;
  
  var $fecha_corte;
  var $fecha_proceso;
  
  var $edo_cuenta;
  var $aprelacion;

  var $usuario;
  var $id_usuario;
  var $id_scursal;
  var $id_grupo;
  
  var $ip;
  var $modulus_name ;   
  var $modulus_path ;
  
  var $error_msg = "";
  
  var $info;

  var $log=true;
  
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Constructor
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

function TCREDITO() 
{
        $argv = func_get_args();
        
        
        $num_args = func_num_args();
        
        
        

        switch($num_args )
        {
                case 0: $this->error_msg = "Argumentos insuficientes";
                        return(-1);
                        break;
                
                case 1: $this->error_msg = "Argumentos insuficientes";
                        return(-1);
                        break;

                case 2:   $this->__construct1($argv[0], $argv[1] );
                         break;
                case 3:   $this->__construct2($argv[0], $argv[1], $argv[2] );
                         break;
                case 4:   $this->__construct2($argv[0], $argv[1], $argv[2], $argv[3]);
                         break;
                case 5:   $this->__construct2($argv[0], $argv[1], $argv[2], $argv[3], $argv[4]);
                         break;

        }
        
        
        
        if($num_args > 4) 
        {
                $this->error_msg = "Número de argumentos inválido";
                return(-1);
        }
        
}






  function __construct1( &$db,&$edocta)
  {
        $this->info               = 1;
        
        $this->db               =& $db;
        
        
        $this->edo_cuenta         =& $edocta;


        $this->fecha_corte        = $this->edo_cuenta->fecha_corte;
 
        $this->id_factura         = $this->edo_cuenta->id_factura;
        $this->id_pagos_a_ignorar = $this->edo_cuenta->id_pagos_a_ignorar;
       
        $this->init();
 
  }




  
  function __construct2($id_factura, $fecha_corte, &$db, $id_pagos_a_ignorar='')
  {




        $this->info               = 2;

        $this->fecha_proceso    = date("Y-m-s H:M:i");

        $this->db               =& $db;
        $this->fecha_proceso    = date("Y-m-s H:M:i");
        $this->fecha_corte      = $fecha_corte;
        
        $this->id_factura         = $id_factura;
        $this->id_pagos_a_ignorar = $id_pagos_a_ignorar;
        
        $this->edo_cuenta         = new TCUENTA($this->id_factura, $this->fecha_corte, 0,$this->id_pagos_a_ignorar);
        
        $this->init();
       
       
   }
   
   
  function init()
  {
        global $_SESSION; 
        global $_SERVER;
        global $sys_path;

        
        $this->usuario          = $_SESSION['NOM_USR'];
        $this->id_usuario       = $_SESSION['ID_USR'];
        $this->id_scursal       = $_SESSION['ID_SUC'];
        $this->id_grupo         = $_SESSION['ID_GRP'];
        $this->ip               = $_SESSION['USR_IP'];
   
        $this->aprelacion = array();
        $this->aprelacion = str_split($this->edo_cuenta->prelacion);
        
        

        $this->iva_aplicable      = $this->edo_cuenta->iva_pcnt_intereses;
        $this->num_compra         = $this->edo_cuenta->numcompra;
        
        $this->num_cliente        = $this->edo_cuenta->numcliente;
        $this->nombre_cliente     = $this->edo_cuenta->nombrecliente;
                
        $sender_file    = $_SERVER["SCRIPT_NAME"];
        $a_ruta         = explode('/', $sender_file);
        $modulus_file   = $break[count($a_ruta) - 1]; 

        $modulus_path = substr($arch,0, (strlen($sender_file)-strlen($modulus_file))  );
        $modulus_path = str_replace($sys_path,'../',$modulus_path);

        $sql = "SELECT  a.Nombre
                FROM    menu_dtl a,
                        permisos b,
                        rutas    c
                
                WHERE    a.ID_Sub = b.ID_Sub            and
                         a.ID_Sub2 =b.ID_Sub2           and
                         a.ID_Sub3 =b.ID_Sub3           and
                         ID_grupo = '".$this->id_grupo."' and
                         a.Modulo = '".$modulus_file."' and
                         a.ID_Ruta = c.id_ruta          and
                         c.ruta = '".$modulus_path."' ";
                         
         $rs = $this->db->Execute($sql);
         
         
         $this->modulus_name = $modulus_name;            
         $this->modulus_path = $modulus_path;
         
         
        
        
  }
  


   function refresh()
   {
   
           
           $this->edo_cuenta = new TCUENTA($this->id_factura, $this->fecha_corte, 0,$this->id_pagos_a_ignorar);

   }


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Salda todo el crédito couta por cuota
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


function saldar_cuotas($cuota_desde = 0, $cuota_hasta="99999")
{
        $obj =& $this->edo_cuenta;
        
        
        
        $num_compra  = $this->num_compra;
        
        //======================================================================================================================        
        //¿Ya tiene vencimiento anticipado o no ?
        //======================================================================================================================        
        if($obj->is_vencimiento_anticipado)             
                $num_coutas_a_saldar = $obj->id_cargo_reemplazo_vencimiento_anticipado;
        else
                $num_coutas_a_saldar = $obj->plazo;
        //======================================================================================================================        
        
        $num_coutas_a_saldar =  min($num_coutas_a_saldar, $cuota_hasta);
        
        $fecha_corte        = $this->fecha_corte;
        
        
        //debug(print_r($this->aprelacion,true));
        //debug("for($cuota_desde = (".$obj->numcargosvencidos_pagados."+1); $cuota_desde <= $num_coutas_a_saldar; $cuota_desde++)");
        
        for($cuota_desde = ($obj->numcargosvencidos_pagados+1); $cuota_desde <= $num_coutas_a_saldar; $cuota_desde++)
        {

                if($cuota_desde > 0)
                {
                        //=======================================================================
                        //      echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*** Saldando couta # ".$cuota_desde." <BR>";
                        //=======================================================================
                        
                        

                        foreach($this->aprelacion AS $TIPO)
                        {

                                                //-----------------------------------------------------------------------------------------------------------------------//

                                                switch( $TIPO )
                                                {                       
                                                        case 'M' : {    //-------------------------------
                                                                        //Saldar Moratorios     
                                                                        //-------------------------------

                                                                        $monto_nota_credito =   ($obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_Moratorio']>=0.01)?($obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_Moratorio'] + $obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_IVA_Moratorio']        ):(0);
                                                                                           
                                                                        $id_concepto_nota   =   -4;
                                                                        
                                                                        
                                                                        if($monto_nota_credito >0.004)
                                                                        $this->aplica_nota_credito($monto_nota_credito, $id_concepto_nota, $cuota_desde, $this->fecha_corte  );

                                                                        break;

                                                                   }
                                                        
                                                        case 'A' : {    

                                                                        //-------------------------------
                                                                        //Saldar Comisión por Apertura  
                                                                        //-------------------------------

                                                                        $monto_nota_credito =   ($obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_Comision']>=0.01)?($obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_Comision']    + $obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_IVA_Comision']        ):(0);
                                                                                        
                                                                        $id_concepto_nota   =    -12;
    
                                                                        if($monto_nota_credito >0.004)
                                                                        $this->aplica_nota_credito($monto_nota_credito, $id_concepto_nota, $cuota_desde, $this->fecha_corte  );

                                                                        break;
                                                                        
                                                                    }

                                                        
                                                        case 'I' : {    //-------------------------------
                                                                        //Saldar Interéses Normales
                                                                        //-------------------------------
                                
                                                                        $monto_nota_credito =   ($obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_Interes']>=0.01)?($obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_Interes']    + $obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_IVA_Interes']           ):(0);
                                                                                          
                                                                        $id_concepto_nota   =    -5;
   
                                                                        if($monto_nota_credito >0.004)
                                                                        $this->aplica_nota_credito($monto_nota_credito, $id_concepto_nota, $cuota_desde, $this->fecha_corte  );
                                                        
                                                                        break;
                                                                    }

                                                        
                                                        case 'C' : {    //-------------------------------
                                                                        //Saldar Capital        
                                                                        //-------------------------------

                                                                        $monto_nota_credito =   ($obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_Capital']>=0.01)?($obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_Capital']):(0);
                                                                                          
                                                                        $id_concepto_nota   =    -6;

                                                                        if($monto_nota_credito >0.004)
                                                                        $this->aplica_nota_credito($monto_nota_credito, $id_concepto_nota, $cuota_desde, $this->fecha_corte  );
                                                                        
                                                                        //debug(" if(monto_nota_credito{$monto_nota_credito} >0.004)   this->aplica_nota_credito(monto_nota_credito=$monto_nota_credito, id_concepto_nota=$id_concepto_nota, cuota_desde=$cuota_desde, fecha_corte =".$this->fecha_corte."  ); ");
                                                                        
                                                        
                                                                        break; 
                                                                   }


                                                        case 'E' : {    //-------------------------------
                                                                        //Saldar Extemporáneos  
                                                                        //-------------------------------

                                                                        $monto_nota_credito =   ($obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_Otros']>=0.01      )?($obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_Otros']   + $obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_IVA_Otros']    ):(0);
                                                                                            
                                                                        $id_concepto_nota   =   -15;

                                                                        if($monto_nota_credito >0.004)
                                                                        $this->aplica_nota_credito($monto_nota_credito, $id_concepto_nota, $cuota_desde, $this->fecha_corte  );

                                                                        break;
                                                                   }
                                                                   
                                                                   
                                                                   
  
                                                        case 'S' : {    //-------------------------------
                                                                        //Saldar Seguros
                                                                        //-------------------------------

											//-------------------                                                                        
											//Seguro de Vida
											//-------------------
											 $monto_nota_credito =	($obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_SegV']>=0.01      )?($obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_SegV'] + $obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_IVA_SegV']    ):(0);

											 $id_concepto_nota   =   -50;

											if($monto_nota_credito >0.004)
											    $this->aplica_nota_credito($monto_nota_credito, $id_concepto_nota, $cuota_desde, $this->fecha_corte  );


											//-------------------                                                                        
											//Seguro Desempleo
											//-------------------

											 $monto_nota_credito =	($obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_SegD']>=0.01      )?($obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_SegD'] + $obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_IVA_SegD']    ):(0);

											 $id_concepto_nota   =   -51;

											if($monto_nota_credito >0.004)
											    $this->aplica_nota_credito($monto_nota_credito, $id_concepto_nota, $cuota_desde, $this->fecha_corte  );


											//-------------------                                                                        
											//Seguro Bienes Materiales
											//-------------------

											 $monto_nota_credito =	($obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_SegB']>=0.01      )?($obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_SegB'] + $obj->saldos_cuota[$cuota_desde]['CUOTA_SALDO_MOV_IVA_SegB']    ):(0);


											 $id_concepto_nota   =   -52;

											if($monto_nota_credito >0.004)
											    $this->aplica_nota_credito($monto_nota_credito, $id_concepto_nota, $cuota_desde, $this->fecha_corte  );

                                                                        
                                                                        break;
                                                                   
                                                                   }
                                                                   
                                                        //-----------------------------------------------------------------------------------------------------------------------//


                                                }


                        }


                        
                        $obj = new TCUENTA( $this->id_factura, $this->fecha_corte);
                }
                        
        }

        
        
        
        $this->edo_cuenta =& $obj;
        return;

}




  //--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
  // Aplica nota de crédito
  //--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
  
  function aplica_nota_credito( $monto_nota_credito, $id_concepto_nota, $num_cuota, $fecha, $sufix="")
  {

         $this->error_msg = "";

        if($num_cuota<1) return(-1) ;
        $referencia = " Aplicado el día : ".date("Y-m-d H:i:s") ." por : ".$this->usuario;
  
        if($monto_nota_credito>=0.001)
        {
  
                        $pIVA =  getIVA($this->edo_cuenta->zona_iva, $fecha, $this->db);

                        //debug("$pIVA =  getIVA(".$this->edo_cuenta->zona_iva.", $fecha, this->db);");


                $sql="  SELECT Descripcion, Subtipo
                        FROM   conceptos 
                        WHERE   tipo    =       'A'             and
                                forma   =       'Documento'     and
                                Status  =       'Activo'        and
                                id_concepto =   '".$id_concepto_nota."'
                        ORDER BY subtipo " ;

                 $rs =  $this->db->Execute($sql);
                 $forma         = $rs->fields[0].$sufix;
                 $tipo_nota     = $rs->fields[1];
                 
                 if ($tipo_nota == 'Capital')
                 {
                        $monto  = $monto_nota_credito;
                        $_abono = $monto;
                        $_iva   = 0; 
                 
                 }
                 else
                 {
                        $monto  = $monto_nota_credito;
                        //$_abono = $monto / (1+$this->iva_aplicable);
                        $_abono = $monto / (1+$pIVA);                        
                        $_iva   = $monto - $_abono; 

                 }    
                 
                 
                 

                  if($monto>=0.01)
                          {
                                $sql   ="INSERT INTO notas_credito 
                                                        (ID_Cargo,      
                                                         Num_compra,    
                                                         ID_concepto,           
                                                         Fecha,           
                                                         Monto,       
                                                         Abono, 
                                                         IVA,   
                                                         Forma,                 
                                                         Subtipo,       
                                                         Aplicacion,    
                                                         Usuario)
                                         VALUES 
                                                        ('".$num_cuota."', 
                                                         '".$this->num_compra."',       
                                                         '".$id_concepto_nota."',
                                                         '".$fecha."',
                                                         '".$monto."',
                                                         '".$_abono ."',
                                                         '".$_iva ."',
                                                         '".$forma."',
                                                         '".$tipo_nota."',      
                                                         'Progresivo',  
                                                         '".$referencia."')";   
                                $this->db->Execute($sql);
                                
                                return($this->db->_insertid());
  
                        }
                        else
                        {
                                $this->error_msg = "El monto es cero, no es posible aplicar la nota de crédito.";
                      
                               return(-1); 
                                
                                
                        }
  
        }
  
  
        $this->error_msg = "El monto es cero, no es posible aplicar la nota de crédito.";

        return(-1);
  
  }
  
  //--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
  // Aplica nota de crédito que salda cuota completa
  //--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    
function aplica_nota_credito_salda_cuota($id_concepto_nota, $num_cuota, $fecha, $sufix="")
{




         $this->error_msg = "";

        if($num_cuota<1) return(-1) ;
        $referencia = " Aplicado el día : ".date("Y-m-d H:i:s") ." por : ".$this->usuario;
  


        $sql="  SELECT Descripcion, Subtipo
                  FROM   conceptos 
                 WHERE   tipo    =       'A'             and
                         forma   =       'Documento'     and
                         Status  =       'Activo'        and
                         id_concepto =   '".$id_concepto_nota."'
                 ORDER BY subtipo " ;

          $rs =  $this->db->Execute($sql);
          
          $forma         = $rs->fields[0].$sufix;
          $tipo_nota     = $rs->fields[1];




        switch($tipo_nota )
        {
        

                    
                case 'Capital' :   $_abono = $this->edo_cuenta->saldos_cuota[$num_cuota]['CUOTA_SALDO_MOV_Capital'];
                                   $_iva   = 0;
                                   break;
                
                case 'Interes':    $_abono = $this->edo_cuenta->saldos_cuota[$num_cuota]['CUOTA_SALDO_MOV_Interes'];
                                   $_iva   = $this->edo_cuenta->saldos_cuota[$num_cuota]['CUOTA_SALDO_MOV_IVA_Interes'];
                                   break;
                
                case 'Moratorio':  $_abono = $this->edo_cuenta->saldos_cuota[$num_cuota]['CUOTA_SALDO_MOV_Moratorio'];
                                   $_iva   = $this->edo_cuenta->saldos_cuota[$num_cuota]['CUOTA_SALDO_MOV_IVA_Moratorio'];
                                   break;
                
                case 'Comision':   $_abono = $this->edo_cuenta->saldos_cuota[$num_cuota]['CUOTA_SALDO_MOV_Comision'];
                                   $_iva   = $this->edo_cuenta->saldos_cuota[$num_cuota]['CUOTA_SALDO_MOV_IVA_Comision'];
                                   break;
                
                
                case 'Otros':      $_abono = $this->edo_cuenta->saldos_cuota[$num_cuota]['CUOTA_SALDO_MOV_Otros'];
                                   $_iva   = $this->edo_cuenta->saldos_cuota[$num_cuota]['CUOTA_SALDO_MOV_IVA_Otros'];
                                   break;
       
                case 'SegV':      $_abono = $this->edo_cuenta->saldos_cuota[$num_cuota]['CUOTA_SALDO_MOV_SegV'];
                                  $_iva   = $this->edo_cuenta->saldos_cuota[$num_cuota]['CUOTA_SALDO_MOV_IVA_SegV'];
                                  break;
       
                case 'SegD':      $_abono = $this->edo_cuenta->saldos_cuota[$num_cuota]['CUOTA_SALDO_MOV_SegD'];
                                  $_iva   = $this->edo_cuenta->saldos_cuota[$num_cuota]['CUOTA_SALDO_MOV_IVA_SegD'];
                                  break;

                case 'SegB':      $_abono = $this->edo_cuenta->saldos_cuota[$num_cuota]['CUOTA_SALDO_MOV_SegB'];
                                  $_iva   = $this->edo_cuenta->saldos_cuota[$num_cuota]['CUOTA_SALDO_MOV_IVA_SegB'];
                                  break;



       
         }                        
     
        
        $monto  = $_abono  + $_iva;
    

                  if($monto>=0.01)
                          {
                                $sql   ="INSERT INTO notas_credito 
                                                        (ID_Cargo,      
                                                         Num_compra,    
                                                         ID_concepto,           
                                                         Fecha,           
                                                         Monto,       
                                                         Abono, 
                                                         IVA,   
                                                         Forma,                 
                                                         Subtipo,       
                                                         Aplicacion,    
                                                         Usuario)
                                         VALUES 
                                                        ('".$num_cuota."', 
                                                         '".$this->num_compra."',       
                                                         '".$id_concepto_nota."',
                                                         '".$fecha."',
                                                         '".$monto."',
                                                         '".$_abono ."',
                                                         '".$_iva ."',
                                                         '".$forma."',
                                                         '".$tipo_nota."',      
                                                         'Progresivo',  
                                                         '".$referencia."')";   
                                $this->db->Execute($sql);
                                
                                
                                
                                return($this->db->_insertid());
  
                        }
                        else
                        {
                                $this->error_msg = "El monto es cero, no es posible aplicar la nota de crédito.";
                      
                               return(-1); 
                                
                                
                        }
  
        
  
  
        $this->error_msg = "El monto es cero, no es posible aplicar la nota de crédito.";

        return(-1);
  
}  
  //----------------------------------------------------------------------------------------
  //  Aplica nota de cargo 
  //----------------------------------------------------------------------------------------
  
  function aplica_nota_cargo($id_concepto, $fecha_corte, $capital,$interes, $comision, $moratorio, $extemporaneo, $suffix="")
  {              
                  $this->error_msg="";
                  
                  $referencia = "Aplicado el día ".date("d/m/Y H:i:s")." por : ".$this->usuario;
                  
                   
                  $sql = "SELECT num_factura FROM fact_asociado WHERE Num_compra='".$this->num_compra."' ";
                  $rs  = $this->db->Execute($sql);
                  $num_factura = $rs->fields[0];
   
   
                   $sql = "SELECT       fact_cliente.IVA_Interes,
                                        fact_cliente.IVA_Moratorio,
                                        fact_cliente.IVA_Comision
                                 FROM   fact_cliente            
                                 WHERE  Num_compra='".$this->num_compra."' ";
                   
                   
                   $rs = $this->db->Execute($sql);
                   
                    $pIVA =  getIVA($this->edo_cuenta->zona_iva, $fecha_corte, $this->db);  
                   
                   //$iva_interes         = ($rs->fields['IVA_Interes']  /100);
                   //$iva_moratorio       = ($rs->fields['IVA_Moratorio']/100);
                   //$iva_comisiones      = ($rs->fields['IVA_Comision'] /100);
                   
                   $iva_interes         = $pIVA;
                   $iva_moratorio       = $pIVA;
                   $iva_comisiones      = $pIVA;
              
                  
                   
                   
  
                  
                  
                  $sql = "SELECT descripcion, Subtipo FROM conceptos            WHERE id_concepto= '".$id_concepto."' ";
                  $rs  =  $this->db->Execute($sql);
                  $Concepto = $rs->fields[0];  
                  
                  if(!empty($suffix))
                        $Concepto .= " ".$suffix;
                  
                  $Subtipo  = $rs->fields[1];
                 
   
   
                 $iva_general =((($interes      / (1+$iva_interes   ) ) * $iva_interes    )+
                                (($comision     / (1+$iva_comisiones) ) * $iva_comisiones )+
                                (($moratorio    / (1+$iva_moratorio ) ) * $iva_moratorio  )+
                                (($extemporaneo / (1+$iva_comisiones) ) * $iva_comisiones ));
   


                 $monto =       ( $capital +
                                  $interes  +   
                                  $comision  +  
                                  $moratorio   +
                                  $extemporaneo  );
   
                          if($monto>=0.01)
                          {
                  
                                  $sql = "SELECT MAX(id_cargo)+1 FROM  cargos                   WHERE num_compra='".$this->num_compra."' ";
                                  $rs  = $this->db->Execute($sql);
                                  $ID_Cargo = $rs->fields[0];
                                if($ID_Cargo)
                                {
                                $sql = " INSERT INTO  cargos 
  
                                               (ID_Cargo, 
                                                ID_Concepto, 
                                                Num_compra, 
                                                Num_factura, 
                                                Fecha_vencimiento, 
                                                Monto, 
                                                Capital, 
                                                Interes, 
                                                Comision, 
                                                Moratorio, 
                                                Otros,  
                                                IVA_Interes,
                                                IVA_Comision,
                                                IVA_Moratorio,
                                                IVA_Otros,                                           
                                                IVA, 
                                                Activo, SubTipo, Concepto, Observaciones)
  
                                         VALUES('".$ID_Cargo."', 
                                                '".$id_concepto."', 
                                                '".$this->num_compra."',
                                                '".$num_factura."',
                                                '".$fecha_corte."',
                                                '".($monto                              )."',
                                                '".($capital                            )."',
                                                '".($interes      / (1+$iva_interes   ) )."',
                                                '".($comision     / (1+$iva_comisiones) )."',
                                                '".($moratorio    / (1+$iva_moratorio ) )."',
                                                '".($extemporaneo / (1+$iva_comisiones) )."',
                                                
                                                
                                                '".(($interes      / (1+$iva_interes   ) ) * $iva_interes    )."',
                                                '".(($comision     / (1+$iva_comisiones) ) * $iva_comisiones )."',
                                                '".(($moratorio    / (1+$iva_moratorio ) ) * $iva_moratorio  )."',
                                                '".(($extemporaneo / (1+$iva_comisiones) ) * $iva_comisiones )."',

                                                
                                                '".($iva_general)."',                   
                                                'Si',
                                                '".$Subtipo."',
                                                '".$Concepto."',
                                                '".$referencia."') ";
  
  
  
                               $this->db->Execute($sql);
                               return($this->db->_insertid());
                               }
                               else
                               {
                                return(0);
                               }
                              
                              }
                $this->error_msg = "El monto es cero, no es posible aplicar la nota de cargo.";

                return(-1);
  }

  
  
 //----------------------------------------------------------------------------------------
 // Aplica vencimientos anticipados
 //----------------------------------------------------------------------------------------



 function aplica_vencimiento_anticipado()
 {
 
        $this->error_msg="";

        //-------------------------------------------------------------------
        //¿Existe vencimiento anticipado previo ?
        //-------------------------------------------------------------------

        if($this->edo_cuenta->is_vencimiento_anticipado)                
                {
                        $this->error_msg = "Ya existía un vencimiento anticipado previo.";
                        
                        //debug($this->error_msg);
                       
                        return(-1);


                }
/*
        //-------------------------------------------------------------------
        //¿Existen abonos posteriores a la fecha de vencimiento anticipado ?
        //-------------------------------------------------------------------

                 $sql =" SELECT COUNT(*)
                         FROM  pagos
                         WHERE Num_compra='".$this->num_compra."' and Fecha > '".$this->fecha_corte."'and Activo='S' ";
               // debug($sql);
                $rs = $this->db->Execute($sql);
 
                if($rs->fields[0]>0)                
                {
                        $this->error_msg = "Existen abonos posteriores a la fecha a la que se desea hacer el vencimiento anticipado. ";

                        //debug($this->error_msg);


                        return(-1);
                }
*/
        //-------------------------------------------------------------------
        //¿Existen notas de cargos  posteriores a la fecha de vencimiento anticipado ?
        //-------------------------------------------------------------------
/*
                $sql =" SELECT COUNT(*)
                        FROM  cargos
                        WHERE Num_compra='".$this->num_compra."' and Fecha_vencimiento > '".$this->fecha_corte."'and Activo='Si' and ID_Concepto != '-3' ";
                

                $rs = $this->db->Execute($sql);
  
                if($rs->fields[0]>0)                
                {
                        $this->error_msg = "Existen notas de cargo posteriores a la fecha a la que se desea hacer el vencimiento anticipado. ";

                        //debug($this->error_msg);


                        return(-1);
                }
*/
        //-------------------------------------------------------------------
        //¿Existen notas de crédito  posteriores a la fecha de vencimiento anticipado ?
        //-------------------------------------------------------------------
/*
                 $sql =" SELECT COUNT(*)
                         FROM  notas_credito
                         WHERE Num_compra='".$this->num_compra."' and Fecha > '".$this->fecha_corte."' ";
                
                $rs = $this->db->Execute($sql);
 
                if($rs->fields[0]>0)                
                {
                        $this->error_msg = "Existen notas de crédito posteriores a la fecha a la que se desea hacer el vencimiento anticipado. ";

                        //debug($this->error_msg);


                        return(-1);
                }

*/
        //-------------------------------------------------------------------
        // ¿Todo en órden?
        //-------------------------------------------------------------------

          // debug("¿Todo en órden? : ".$this->num_compra);

          if($this->num_compra)
          {              
  
        
  
                $sql =" SELECT COUNT(*), SUM(Monto)
                        FROM  cargos
                        WHERE Num_compra='".$this->num_compra."' and Fecha_vencimiento > '".$this->fecha_corte."'and Activo='Si' and ID_Concepto='-3' ";
                

                $rs = $this->db->Execute($sql);
                
                $num_cargos     =$rs->fields[0];
                $monto_cargos   =$rs->fields[1];
                
                
             //debug("¿num_cargos? : ".$num_cargos);
              
                
                
                if($num_cargos>0)
                {



                        $sql=" INSERT INTO cargos ( ID_Cargo,
                                                    ID_Concepto,
                                                    Num_compra,
                                                    Num_factura,
                                                    Fecha_vencimiento,
                                                    Monto,Capital,
                                                    Interes,
                                                    Comision,
                                                    Moratorio,
                                                    Otros,
                                                    IVA,
                                                    IVA_Interes,  
                                                    IVA_Comision,
                                                    IVA_Moratorio,
                                                    IVA_Otros, 
                                                    
                                                    SegV,    
                                                    IVA_SegV,    
                                                    SegD,    
                                                    IVA_SegD,    
                                                    SegB,    
                                                    IVA_SegB,    
 
                                                    SubTipo,
                                                    Concepto,
                                                    Observaciones)
                                (SELECT   
                                        (SELECT MAX(ID_Cargo)+1 from cargos     WHERE Num_compra='".$this->num_compra."')       AS ID_Cargo, 
                                        ID_Concepto,
                                        Num_compra,
                                        Num_factura,
                                        '".$this->fecha_corte."'   AS Fecha,
                                        IF(SUM((Monto           -  AntiMonto      ))!=0,                  SUM((Monto           -  AntiMonto      )),0)       AS _Monto,
                                        IF(SUM((Capital         -  AntiCapital    ))!=0,                  SUM((Capital         -  AntiCapital    )),0)       AS _Capital, 
                                        IF(SUM((Interes         -  AntiInteres    ))!=0,                  SUM((Interes         -  AntiInteres    )),0)       AS _Interes,
                                        IF(SUM((Comision        -  AntiComision   ))!=0,                  SUM((Comision        -  AntiComision   )),0)       AS _Comision,
                                        IF(SUM((Moratorio       -  AntiMoratorio  ))!=0,                  SUM((Moratorio       -  AntiMoratorio  )),0)       AS _Moratorio,
                                        IF(SUM((Otros           -  AntiOtros      ))!=0,                  SUM((Otros           -  AntiOtros      )),0)       AS _Otros,
                                        IF(SUM((IVA             -  AntiIVA        ))!=0,                  SUM((IVA             -  AntiIVA        )),0)       AS _IVA,

                                       SUM(IVA_Interes)         AS _IVA_Interes,
                                       SUM(IVA_Comision)        AS _IVA_Comision,
                                       SUM(IVA_Moratorio)       AS _IVA_Moratorio,
                                       SUM(IVA_Otros)           AS _IVA_Otros,



                                       SUM(SegV         )       AS _SegV,   
                                       SUM(IVA_SegV     )       AS _IVA_SegV,   
                                       SUM(SegD         )       AS _SegD,   
                                       SUM(IVA_SegD     )       AS _IVA_SegD,   
                                       SUM(SegB         )       AS _SegB,   
                                       SUM(IVA_SegB     )       AS _IVA_SegB,   



                                        'General'                                                               AS SubTipo,
                                        CONCAT('Vencimiento anticipado de la cuota ',MIN(ID_Cargo),' a la ',MAX(ID_Cargo)) AS Concepto,
                                        GROUP_CONCAT(ID_Cargo) AS Observaciones
  
                                FROM  cargos
                                WHERE Num_compra           = '".$this->num_compra."' and 
                                      Fecha_vencimiento   >  '".$this->fecha_corte."'and 
                                      Activo               = 'Si' and 
                                      ID_Concepto          = '-3'
                                GROUP BY Num_compra) ";






                        $this->db->Execute($sql);  
/*                        
                       debug($sql);
                        
                        debug("_affectedrows : ".$this->db->_affectedrows());
                        debug("_insertid : ".$this->db->_insertid());
                        debug("_numOfRows : ".$this->rs->_numOfRows);
*/                        
                        
                        if($this->db->_affectedrows() == 1)
                        {
                                $sql = " SELECT MAX(ID_Cargo) from cargos WHERE Num_compra='".$this->num_compra."' ";
                                $rs  = $this->db->Execute($sql);


                                $id_cargo = $rs->fields[0];
                                if($id_cargo)
                                {
                                        $sql = "SELECT Observaciones FROM cargos WHERE Num_compra='".$this->num_compra."' and  ID_Cargo='".$id_cargo."' ";

                                        $rs=$this->db->Execute($sql);   

                                        //debug($sql);  

                                        if($rs->fields[0])
                                        {
                                                $this->lista_id_cargos_asimilados =   $rs->fields[0];
                                                $this->numero_cargos_asimilados   =   $num_cargos;
                                                $this->monto_cargos_asimilados    =   $monto_cargos;


                                                $sql="UPDATE cargos SET Activo='No' WHERE ID_Cargo IN(".$this->lista_id_cargos_asimilados.") and Num_compra='".$this->num_compra."' ";

                                                //debug($sql);

                                                $rs=$this->db->Execute($sql);

                                                return($id_cargo);


                                       }




                                }
                                else
                                {

                                        //RollBack



                                        $sql = "DELETE FROM cargos WHERE   num_compra = '".$this->num_compra."' and ID_Concepto = -3 and  Concepto LIKE 'Vencimiento anticipado de la cuota%' ";
                                        $this->db->Execute($sql);

                                        $sql = "UPDATE cargos SET Activo='Si' WHERE ID_Concepto = -3 and num_compra = '".$this->num_compra."' ";
                                        $this->db->Execute($sql);

                                        $this->error_msg = "No se generó el ID de la operación de vencimiento anticipado.";


                                        return(-1);

                                }
                        }
                        else
                        {
                                $this->error_msg = "No se logró aplicar el vencimiento anticipado.";
                                return(-1);
                        }
  
                }
                else
                {
                        $this->error_msg = "No hay nungún vecimiento que vencer por anticipado.";
                        return(-1);
                }
                
  
          
          }  
         
         
         
          $this->error_msg = "No encontró el número de compra.";

          return(-1);
  
  
  
  }

 //----------------------------------------------------------------------------------------
 // Aplica vencimientos anticipados
 //----------------------------------------------------------------------------------------

 function aplica_vencimiento_anticipado_con_nota_intereses($sufix="")
 {
 
 

        $this->error_msg="";
        
        $saldo_para_liquidar_hoy = $this->edo_cuenta->saldo_para_liquidar_hoy;
                
        //debug("saldo_para_liquidar_hoy : ".$saldo_para_liquidar_hoy );

        $proxima_cuota_interes   = ($this->edo_cuenta->proxima_cuota_interes<0)?(0):($this->edo_cuenta->proxima_cuota_interes);
        
        //debug("proxima_cuota_interes : ".$proxima_cuota_interes );
        
        $id = $this->aplica_vencimiento_anticipado();
        
        
        
        
        $pago_para_liquidar = 0;
        
        if(!empty($this->edo_cuenta->id_pagos_a_ignorar))
        {
		$sql=" SELECT SUM(pagos.Monto) AS Monto
			FROM pagos
			WHERE pagos.Activo = 'S' and
			      pagos.ID_Pago IN ('0',".$this->edo_cuenta->id_pagos_a_ignorar.") ";

		$rs = $this->db->Execute($sql);
		$pago_para_liquidar = $rs->fields['Monto'];


		//debug(" Pago Monto : ".$pago_para_liquidar);
        
        
        }
        
        
        if($id > 0 )
        {
               $this->refresh();
               //$this->edo_cuenta = new TCUENTA($this->id_factura, $this->fecha_corte );
        
               $id_cargo = $this->edo_cuenta->id_cargo_reemplazo_vencimiento_anticipado;

               
               
               $monto  = $this->edo_cuenta->SaldoGeneralVencido - max($saldo_para_liquidar_hoy,$pago_para_liquidar);
   
                if($monto > 0.004)
                { 

			
			$SaldoSegV = ($this->edo_cuenta->SaldoSegV + $this->edo_cuenta->SaldoIVASegV);   			
			
			$SaldoSegD = ($this->edo_cuenta->SaldoSegD + $this->edo_cuenta->SaldoIVASegD);   			
			
			$SaldoSegB = ($this->edo_cuenta->SaldoSegB + $this->edo_cuenta->SaldoIVASegB);  
			

			if($SaldoSegV>0)
			   $this->aplica_nota_credito( $SaldoSegV, '-50', $id_cargo, $this->fecha_corte, $sufix);  

			if($SaldoSegD>0)
			   $this->aplica_nota_credito( $SaldoSegD, '-51', $id_cargo, $this->fecha_corte, $sufix);  

			if($SaldoSegB>0)
			   $this->aplica_nota_credito( $SaldoSegB, '-52', $id_cargo, $this->fecha_corte, $sufix);  


			$monto -= ($SaldoSegV + $SaldoSegD + $SaldoSegB);
			
			if($monto > 0)
			{
				$id_concepto_nota       = -5;

				$nota_id = $this->aplica_nota_credito( $monto, $id_concepto_nota, $id_cargo, $this->fecha_corte, $sufix);                                
			}
                        

               }


        
        
                return($nota_id);
        }

   return(-1);
  }

}