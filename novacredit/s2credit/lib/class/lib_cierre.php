<?

require_once($class_path."lib_credit_rsf.php");
require_once($class_path."lib_credimov.php");


class TCIERRE
{


var $id_cierre;
var $id_tipocredito;
var $fechacierre;

var $fecha_cierre_anterior;

var $credito;
var $obj_credito;

var $time_start;
var $time;
var $time_end;

var $status                     = 0;

var $creditos_procesar          = 0;

var $creditos_procesados        = 0;    
var $creditos_activos           = 0;
var $creditos_saldados          = 0;    



function TCIERRE($id_cierre, $id_tipocredito,  $echo, &$db, $lista_id_facturas="")
{
//$echo=1;




        $this->lista_id_facturas = $lista_id_facturas;
        $this->id_cierre         = $id_cierre;
        $this->id_tipocredito    = $id_tipocredito;
        $this->db = &$db;




        if(!empty($id_cierre))
        {



                 $sql= " SELECT  Creditos_Procesados, Fecha_Cierre                            
                         FROM    cierre_contable_log 
                         WHERE   ID_Cierre='".$id_cierre."' ";




                $rs=$db->Execute($sql); 


                $this->creditos_procesar        = $rs->fields[0];
                $this->fecha_cierre             = $rs->fields[1];
                $this->fechacierre              = $this->fecha_cierre;
                
                $fecha_cierre                   = $this->fecha_cierre;

                $sql_last="";

                 
                 
                 $sql_last="SELECT MAX(cierre_contable_log.Fecha_Cierre)
                            FROM   cierre_contable_log
                            WHERE  
                                   cierre_contable_log.Inicio_Individual !='0000-00-00 00:00:00' and  cierre_contable_log.Inicio_Individual IS NOT NULL  and
                                   cierre_contable_log.Fin_Individual    !='0000-00-00 00:00:00' and  cierre_contable_log.Fin_Individual    IS NOT NULL  and
                                   cierre_contable_log.ID_Cierre != '".$id_cierre."' ";

              

                //// debug($sql_last);
                $rs = $db->Execute($sql_last);
                $this->fecha_cierre_anterior = $rs->fields[0];



//===========================================================================================================================================================

//===========================================================================================================================================================



                        $sql= "SELECT cierre_contable_pagos.ID_Pago
                               FROM  fact_cliente
                               INNER JOIN cierre_contable_pagos ON cierre_contable_pagos.Num_Compra = fact_cliente.Num_Compra

                               WHERE  cierre_contable_pagos.ID_Cierre = '".$this->id_cierre."' ";
                               
                         if(!empty($this->lista_id_facturas) )
                         {                              
                             $sql .= " and fact_cliente.id_factura IN (".$this->lista_id_facturas .") ";
                         }
                         
                        $delete_list=array();

                        $rs=$db->Execute($sql);
                        if($rs->_numOfRows)
                           while(! $rs->EOF)
                           {
                                if(!empty($rs->fields['ID_Pago']))
                                   $delete_list[]=$rs->fields['ID_Pago'];

                                $rs->MoveNext();
                           }

                

                        if(count($delete_list))
                        {
                                $listado = implode("','",$delete_list);
                                $sql= "DELETE 
                                       FROM     cierre_contable_pagos 
                                       WHERE    cierre_contable_pagos.ID_Pago IN ('".$listado."')                                 ";

                                $db->Execute($sql);
                        }
                

               $sql_inicio= " SELECT Inicio_Individual
                              FROM cierre_contable_log
                              WHERE ID_Cierre = '".$this->id_cierre."' ";
                              
                $rg = $db->Execute($sql_inicio);
                $inicio_cierre= $rg->fields[0];


                if(empty($inicio_cierre))
                        {
                
                                $this->status=-1;
                                $this->error="La fecha de inicio de cierre es incorrecta.";
                                if($echo) $this->error."\n";
                                return;
                
                        }






//===========================================================================================================================================================

$sql= "DROP TABLE IF EXISTS _clientes_pagos_no_registrados ";
$db->Execute($sql);

//debug($sql);

$sql= "CREATE TEMPORARY TABLE _clientes_pagos_no_registrados 
       (
		id_factura INT(10) UNSIGNED NOT NULL DEFAULT '0',
		PRIMARY KEY (`id_factura`)
	)

	ENGINE=MyISAM ";
$db->Execute($sql);
//debug($sql);

$sql= "INSERT INTO  _clientes_pagos_no_registrados
	(
		SELECT fact_cliente.id_factura


		FROM pagos

		INNER JOIN fact_cliente
			ON fact_cliente.num_compra = pagos.Num_compra

		LEFT JOIN cierre_contable_pagos
		       ON cierre_contable_pagos.ID_Pago = pagos.ID_Pago

		WHERE   pagos.activo = 'S' and
			 pagos.Fecha <= '".$this->fecha_cierre ."' and
			cierre_contable_pagos.ID_Pago IS NULL     ";

		if(!empty($this->lista_id_facturas) )
		  {                              
		      $sql .= " and fact_cliente.id_factura IN (".$this->lista_id_facturas .") ";
		  }


      $sql .= " GROUP BY fact_cliente.id_factura
		ORDER BY fact_cliente.id_factura
	)";

//debug($sql);
$db->Execute($sql);

//die();



$sql= "SELECT          fact_cliente.id_factura                                                   AS id_factura,
                       

			IF((( MAX(pago_referenciado_saldado.pago_referenciado_saldado_id) IS NOT NULL ) OR
			    ( MAX(caja_credito_saldada.caja_credito_saldado_id) IS NOT NULL ) ),'SI','NO') AS Saldar,



                       MAX(caja_credito_saldada.id_caja_pagos)                                 AS ID_Caja,
                       fact_cliente.Num_Compra                                                 AS Num_Compra,                                      
                       COUNT(pagos.ID_Pago)                                                    AS Num_pagos, 
                       IF(creditos_castigados.ID_Fact_Cliente IS NULL,'NO','SI')               AS Castigado, 
                       cierre_contable_log.Fecha_Cierre                                        AS Fecha_Cierre,

                       MAX(pago_referenciado_saldado.pago_referenciado_saldado_id)             AS ID_Referenciado
                       
	FROM fact_cliente


	INNER JOIN cierre_contable_log	ON cierre_contable_log.Fecha_Cierre = '".$this->fecha_cierre ."'  

	LEFT JOIN  _clientes_pagos_no_registrados 
           ON _clientes_pagos_no_registrados.id_factura = fact_cliente.id_factura


	LEFT JOIN  clientes_datos 	ON clientes_datos.Num_cliente = fact_cliente.num_cliente       



       LEFT JOIN compras                       ON     compras.num_compra                   = fact_cliente.num_compra 
       LEFT JOIN factura_cliente_liquidacion   ON     fact_cliente.ID_Factura              = factura_cliente_liquidacion.ID_Factura
                                                  and factura_cliente_liquidacion.Fecha   <  cierre_contable_log.Fecha_Cierre


       LEFT JOIN cierre_contable_saldos        ON cierre_contable_saldos.ID_Factura    = fact_cliente.id_factura   and 
                                                  cierre_contable_saldos.ID_Cierre     = cierre_contable_log.ID_Cierre



       LEFT JOIN creditos_castigados           ON creditos_castigados.ID_Fact_Cliente  =  fact_cliente.id_factura  and      
                                                  creditos_castigados.fecha_castigo   <=  cierre_contable_log.Fecha_Cierre             
      
       LEFT JOIN pagos                         ON pagos.Num_Compra =  fact_cliente.Num_Compra  and 
                                                  pagos.Fecha = cierre_contable_log.Fecha_Cierre and
                                                  pagos.activo ='S'

       LEFT JOIN caja_credito_saldada          ON fact_cliente.id_factura              = caja_credito_saldada.id_factura
                                              AND caja_credito_saldada.id_caja_pagos  = pagos.id_caja_pagos


       LEFT JOIN pago_referenciado_saldado      ON  fact_cliente.id_factura = pago_referenciado_saldado.id_factura  
					                                  and pago_referenciado_saldado.id_pago = pagos.ID_Pago



       WHERE   
       

               
              (	      (factura_cliente_liquidacion.Fecha  IS NULL)     
                or    (pagos.ID_Pago>0)
                or    ( _clientes_pagos_no_registrados.id_factura IS NOT NULL )
                
               )       
               
               
               and cierre_contable_saldos.ID_Factura               IS NULL                          
             
               #fact_cliente.fecha_inicio <= cierre_contable_log.Fecha_Cierre  
               
               and fact_cliente.fecha_exp <= cierre_contable_log.Fecha_Cierre
               
               
               
               
               ";



if(!empty($this->lista_id_facturas) )
  {                              
      $sql .= " and fact_cliente.id_factura IN (".$this->lista_id_facturas .") ";
  }
  

      $sql .= " 
                GROUP BY fact_cliente.Num_Compra 

                ORDER BY fact_cliente.fecha_exp ,fact_cliente.id_factura 
              ";
/*
if($_SESSION['ID_USR']==13) 
{
	debug($sql);
}
*/
  $rs=$db->Execute($sql);
  
  

                $this->time_start = microtime_float(); 

                $sql_head="REPLACE INTO cierre_contable_saldos
                                                (       
                                                         ID_Cierre,
                                                         ID_Factura,
                                                         ID_Tipocredito,

                                                         DiasMora,

                                                         Num_Cuotas_Contratadas,
                                                         Num_Cuotas_Devengadas,
                                                         Num_Cuotas_Pagadas,
                                                         Num_Cuotas_Vencidas,
                                                         Monto_Total_Cuotas_Devengadas,

                                                         Monto_Capital_Devengado,
                                                         Monto_Comisiones_Devengadas,
                                                         Monto_Intereses_Devengados,
                                                         Monto_Otros_Cargos_Generados,
                                                         Monto_Moratorios_Generados,                                                         
                                                         Monto_SegV_Devengado,                                              
                                                         Monto_SegD_Devengado,                                              
                                                         Monto_SegB_Devengado,                                                              
                                                         Monto_Saldo_a_favor,

                                                         Abonos_Capital,
                                                         Abonos_Comisiones,
                                                         Abonos_Intereses,
                                                         Abonos_Moratorios,
                                                         Abonos_Otros_Cargos,                                                        
                                                         Abonos_SegV,                                                         
                                                         Abonos_SegD,                                                         
                                                         Abonos_SegB,                                                                                                                                                                         
                                                         Abonos_Saldo_a_favor,
                                                         Abonos_Total,
                                                         Abonos_Num,

                                                         NotaCredito_Capital,
                                                         NotaCredito_Intereses,
                                                         NotaCredito_Comisiones,
                                                         NotaCredito_Otros_Cargos,
                                                         NotaCredito_Moratorios,                                                         
                                                         NotaCredito_SegV,
                                                         NotaCredito_SegD,
                                                         NotaCredito_SegB,                                                         
                                                         NotaCredito_Total,
                                                         NotaCredito_Num,

                                                         Efectivo_Capital,
                                                         Efectivo_Intereses,
                                                         Efectivo_Comisiones,
                                                         Efectivo_Otros_Cargos,
                                                         Efectivo_Moratorios,                                                         
                                                         Efectivo_SegV,
                                                         Efectivo_SegD,
                                                         Efectivo_SegB,
                                                         Efectivo_Total,
                                                         Efectivo_Num,
                                                         Efectivo_UltimoAbonoFecha,
                                                         Efectivo_UltimoAbonoID,



                                                         Saldo_Vencido_Capital,
                                                         Saldo_Vencido_Comisiones,
                                                         Saldo_Vencido_Intereses,
                                                         Saldo_Vencido_Otros_Cargos,
                                                         Saldo_Vencido_Moratorios,
                                                         Saldo_Vencido_SegV,
                                                         Saldo_Vencido_SegD,
                                                         Saldo_Vencido_SegB,
                                                         Saldo_Total_Vencido,
                                                         
                                                         

                                                         Saldo_Vigente_Capital,
                                                         Saldo_Vigente_Comisiones,
                                                         Saldo_Vigente_Intereses,
                                                         Saldo_Vigente_SegV,                                                         
                                                         Saldo_Vigente_SegD,                                                         
                                                         Saldo_Vigente_SegB,                                                                                                                  
                                                         Saldo_Total_Vigente,

                                                         Saldo_para_liquidar_hoy,
                                                         Adeudo_Total,                          
                                                         
                                                         Saldo_Vencido_0_dias,          
                                                         Saldo_Vencido_1_7_dias,        
                                                         Saldo_Vencido_8_30_dias,       
                                                         Saldo_Vencido_31_60_dias,      
                                                         Saldo_Vencido_61_90_dias,      
                                                         Saldo_Vencido_91_120_dias,     
                                                         Saldo_Vencido_121_y_mas_dias,
                                                         
                                                         Calclulado ) VALUES ";
                                                         
                $this->sql_head = $sql_head;                                                         
                                                         
                $rows_to_insert = array();

                $this->creditos_procesados = 0; 
                $this->creditos_activos    = 0;
                $this->creditos_saldados   = 0; 

 
                $error = false;
 
                //---------------------------------------------------------------------------------
                //Créditos activos.
                //---------------------------------------------------------------------------------
                
                while(! $rs->EOF)
                {


                                 $this->log_advance($rs->fields['id_factura']);

                                 //--------------------------------------------------------------
                                 // Verificar si el crédito es CASTIGADO
                                 //--------------------------------------------------------------

                                if(($rs->fields['Castigado']== 'SI') )
                                {
                                   
                                   
                                   //========================================
                                   // Ultimo cierre en el que apareció.
                                   //========================================
                              
                                   $sql=" SELECT MAX(cierre_contable_log.Fecha_Cierre) AS Ultimo_Cierre
                                          FROM  (cierre_contable_saldos, cierre_contable_log)
                                          WHERE cierre_contable_saldos.ID_Cierre = cierre_contable_log.ID_Cierre and 
                                                cierre_contable_saldos.ID_Factura = '".($rs->fields['id_factura'])."' AND             
                                                cierre_contable_log.Fecha_Cierre < '".$this->fecha_cierre ."'     ";
                                   $rc=$db->Execute($sql);   

                                   $_fecha_cierre_anterior = $rc->fields['Ultimo_Cierre'];

                                   //========================================
                                   // Días mora en el último cierre en el que apareció.
                                   //========================================


                                   $sql=" SELECT cierre_contable_saldos.DiasMora
                                          FROM   (cierre_contable_log, cierre_contable_saldos)
                                          WHERE 
                                                cierre_contable_log.ID_Cierre = cierre_contable_saldos.ID_Cierre and 
                                                cierre_contable_log.Fecha_Cierre = '".$_fecha_cierre_anterior."'  and
                                                cierre_contable_saldos.ID_Factura = '".($rs->fields['id_factura'])."' ";


                                   $rc=$db->Execute($sql);   

                                   $_dias_mora_cierre_anterior = $rc->fields['DiasMora'];

                                   //========================================
                                   // Pagos hechos entre el último cierre y el el cierre  de hoy
                                   //========================================

                                  $sql="SELECT COUNT(ID_Pago) AS PagosRecientes
                                        FROM pagos
                                        WHERE  num_compra='".$rs->fields['Num_Compra']."' and 
                                               fecha > '".$_fecha_cierre_anterior."' and fecha <= '".$this->fecha_cierre."'
                                               and Activo='S' ";
                                   
                                   $rc=$db->Execute($sql); 
                                   
                                   //debug("[".$this->fecha_cierre."]".$sql);
   
                                   $_tiene_pagos_recientes = $rc->fields['PagosRecientes'];
                                   
                                   
                                  $sql="SELECT COUNT(ID_Nota) AS NotasRecientes
                                        FROM notas_credito


                                        WHERE  num_compra='".$rs->fields['Num_Compra']."' and 
                                               fecha > '".$_fecha_cierre_anterior."' and fecha <= '".$this->fecha_cierre."' ";
                                   
                                   $rc=$db->Execute($sql); 
                                      
                                   $_tiene_pagos_recientes += $rc->fields['NotasRecientes'];
                                   
                                   
                                   
                                   
                                   
                                   
                                   
                                  

                                   //========================================
                                   // Cargos que vencieron entre el último cierre y el el cierre  de hoy
                                   //========================================

                                   
                                   $sql = "SELECT COUNT(ID_Cargo) AS NumCargos
                                           FROM cargos 
                                           WHERE  num_compra='".$rs->fields['Num_Compra']."' and 
                                                  fecha_vencimiento > '".$_fecha_cierre_anterior."' and  fecha_vencimiento <= '".$this->fecha_cierre ."'
                                                  and Activo='Si' ";
                                   $rc=$db->Execute($sql);   
                               
                                   $_tiene_cargos_recientes = $rc->fields['NumCargos'];

                                   
                                   
                                   if( ($_dias_mora_cierre_anterior < 91) or  ($_tiene_pagos_recientes > 0)   or  ($_tiene_cargos_recientes > 0) )
                                   {
                                     // debug("C) Castigado [".$this->fecha_cierre."](".$rs->fields['id_factura']."): RECALCULADO : (_dias_mora_cierre_anterior : ".$_dias_mora_cierre_anterior." < 91) or  (_tiene_pagos_recientes : ".$_tiene_pagos_recientes." > 0)   or  (_tiene_cargos_recientes : ".$_tiene_cargos_recientes." > 0) ");
                                   }
                                   else
                                   {     
                                        $result = 1;
                                        $result = $this->reciclar_credito_castigado($rs->fields['id_factura'], $id_cierre, $this->fecha_cierre,  $rp->fields['num_compra'], $sql_head, $db);
                                        
                                        if($result > 0)
                                        {
                                              //  debug("A) Castigado [".$this->fecha_cierre."](".$rs->fields['id_factura']."): Reciclado (_dias_mora_cierre_anterior : ".$_dias_mora_cierre_anterior." < 91) or  (_tiene_pagos_recientes : ".$_tiene_pagos_recientes." > 0)   or  (_tiene_cargos_recientes : ".$_tiene_cargos_recientes." > 0)");
                                                
                                                $this->creditos_procesados++;
                                                $this->creditos_activos++;
                                                $rs->MoveNext();
                                                continue;
                                        }
                                        else
                                        {
                                           // debug("B) Castigado [".$this->fecha_cierre."](".$rs->fields['id_factura']."): Recalculado (_dias_mora_cierre_anterior : ".$_dias_mora_cierre_anterior." < 91) or  (_tiene_pagos_recientes : ".$_tiene_pagos_recientes." > 0)   or  (_tiene_cargos_recientes : ".$_tiene_cargos_recientes." > 0)");
                                        }
                                   }

                                   
                                }





                                //--------------------------------------------------------------
                                // Verificar si se va a liquidar hoy
                                //--------------------------------------------------------------
                                
                               $sql = " SELECT COUNT(convenios.ID_Convenio) AS TieneConvenio,
                                               MAX(convenios.ID_Convenio  ) AS ID_UltimoConvenio,
                                               MIN(convenios.Fecha_inicio ) AS Fecha_Inicio_Convenio,
                                               MAX(convenios.Fecha_final  ) AS Fecha_Final_Convenio,     
                                               MAX(convenios.Monto_Pagar  ) AS Monto_Total_Convenido
 
 
                                       FROM convenios 
                                       
                                       WHERE convenios.ID_Factura='".$rs->fields['id_factura']."'  ";


                               // debug($sql);
                               
                               $rz = $this->db->Execute($sql);
                                               
                                if($rz->fields['TieneConvenio']>0)
                                {

                                        //--------------------------------------------------------------
                                        // Si el crédito tiene convenio y el convenio ya se terminó
                                        //--------------------------------------------------------------
                                        $sql = "SELECT  SUM(pagos.Monto) AS TotalPagos 
                                                 
                                                 FROM   (fact_cliente, pagos )

                                                 WHERE  fact_cliente.id_factura = '".$rs->fields['id_factura']."'   and 
                                                        fact_cliente.num_compra = pagos.Num_compra and 
                                                        pagos.Activo='S'                           and
                                                        pagos.Fecha BETWEEN  '".$rz->fields['Fecha_Inicio_Convenio']."' and '".min($rz->fields['Fecha_Final_Convenio'],$this->fecha_cierre)."'
                                                           
                                                 
                                                 GROUP BY fact_cliente.num_compra ";
                                        


                                        
                                        $rc = $db->Execute($sql);
                                      

                                        if(($rc->fields['TotalPagos']>0) and  ($rc->fields['TotalPagos'] >= $rz->fields['Monto_Total_Convenido']))
                                        {
                                                $this->saldar_credito($rs->fields['id_factura']);


                                        }
                                
                                }
                                else
                                {

                                       //---------------------------------------------------------------
                                       // Si tiene convenio, no es apto para vencimiento anticipado.
                                       //---------------------------------------------------------------
                               
                                
                                        if($rs->fields['Saldar'] == 'SI')
                                        {
                                        
                                             if($rs->fields['ID_Caja'] > 0)
                                             {

                                                $sql = "SELECT pagos.Fecha 
                                                        FROM   pagos 
                                                        WHERE  pagos.ID_Caja_Pagos ='".$rs->fields['ID_Caja']."'    and 
                                                               pagos.Num_compra    ='".$rs->fields['Num_Compra']."' and
                                                               pagos.Activo= 'S' "; 

                                                        $rp  =  $db->Execute($sql);
                                                        $fecha_caja  = $rp->fields['Fecha']; 

                                                        if($fecha_caja >= $rs->fields['Fecha_Cierre'])
                                                           $this->aplica_vencimento_anticipado($rs->fields['id_factura'], $fecha_caja, $rs->fields['Num_Compra']);
                                             }
                                             
                                             else
                                             
                                             if($rs->fields['ID_Referenciado'] > 0)
                                             {
                                             

                                                $sql = "SELECT pagos.Fecha

                                                        FROM   (pago_referenciado_saldado,
                                                               pagos)

                                                        WHERE  pago_referenciado_saldado.pago_referenciado_saldado_id = '".$rs->fields['ID_Referenciado']."' and
                                                               pago_referenciado_saldado.id_pago  = pagos.ID_Pago and
                                                               pagos.Num_compra = '".$rs->fields['Num_Compra']."' and
                                                               pagos.Activo= 'S'  ";

                                                       // debug($sql);

                                                        $rp  =  $db->Execute($sql);
                                                        $fecha_pago_ref  = $rp->fields['Fecha']; 

                                                        if($fecha_pago_ref >= $rs->fields['Fecha_Cierre'])
                                                           $this->aplica_vencimento_anticipado($rs->fields['id_factura'], $fecha_pago_ref, $rs->fields['Num_Compra']);


                                             
                                             }

                                        }
                                }
                                //--------------------------------------------------------------






                                 unset($credito);
                                 
                                 $_id_factura = $rs->fields['id_factura'];
                                 
                                 
                                 

                                 $credito = new TCUENTA($_id_factura, $this->fecha_cierre,'','',true);
                                 

                                 //--------------------------------------------------------------
                                 // NOTA DE CARGO A CREDITOS CON SALDO A FAVOR
                                //--------------------------------------------------------------
				/*
                                  
                                  
                                  if(($credito->numcargosvencidos = $credito->plazo) and ( $credito->adeudo_total < 0.01)) 
                                  {

                                          
                                                                         
                                               //--- Limpieza previa

                                               $dsql = "DELETE FROM cargos ".
                                                       "WHERE ID_Concepto != '-3' and ".
                                                       "Fecha_vencimiento  = '".$this->fecha_cierre."' and ".
                                                       "Concepto LIKE '%depuración%' and ".
                                                       "num_compra='".$rs->fields['Num_Compra']."' ";
                                                       
                                             //  debug($dsql);
                                               $this->db->Execute($dsql);
                                            
                                              unset($credito);
                                              
                                              $credito = new TCUENTA($_id_factura, $this->fecha_cierre,'','',true);


                                               $oMov = new TCREDITO($db, $credito);
                                               
                                               $nn = count($credito->aplicacion)-1;
                                               $nn =($nn<0)?(0):($nn);

                                               $_saldo_capital        =   0;
                                               $_saldo_interes        =   0;
                                               $_saldo_comision       =   0;
                                               $_saldo_moratorio      =   0;
                                               $_saldo_extemporaneo   =   0;


                                               $monto_notacargo = 0;
                                               
						
                                                       if($credito->SaldoGeneralGlobal <= 0 )
                                                       {

                                                               $_saldo_interes       = abs($credito->SaldoFavorPendiente);
                                                               if($_saldo_interes > 0)
                                                               {
                                                                   $_id_concepto = -17;  //Nota de cargo contra interés;

                                                                   $_capital        =       0;
                                                                   $_interes        =       $_saldo_interes ;
                                                                   $_comision       =       0;
                                                                   $_moratorio      =       0;
                                                                   $_extemporaneo   =       0;

                                                                   $oMov->aplica_nota_cargo($_id_concepto, $this->fecha_cierre, $_capital, $_interes, $_comision, $_moratorio, $_extemporaneo, " por depuración del cierre.");

                                                               }
                                                       }
                                                      


                                         unset($oMov);
                                               
                                        
                                        unset($credito);
                                        $credito = new TCUENTA($_id_factura, $this->fecha_cierre,'','',true);
                                     
                                  }
                 		*/
                                //$credito->publica();

                                 $this->documenta_pagos($credito);


                                        //--------------------------------------------------------------
                                        // Número de abonos en efectivo
                                        //--------------------------------------------------------------

                                        $sqle="SELECT COUNT(pagos.ID_Pago),
                                                      MAX(pagos.Fecha),
                                                      MAX(pagos.ID_Pago)
                                               FROM pagos 
                                               
                                               WHERE pagos.Num_compra='".$credito->numcompra."' AND pagos.Fecha <= '".$this->fecha_cierre."' and Activo = 'S'  ";
                                        
                                        $refe=$db->Execute($sqle);
                                        $num_abonos_efectivo =  $refe->fields[0];
                                        $ultimo_abono_fecha  =  $refe->fields[1];
                                        $ultimo_abono_id = 0;
                                        
                                        if(!empty($ultimo_abono_fecha))
                                        {
                                        
						$sqle="SELECT pagos.ID_Pago
							FROM pagos 

							WHERE pagos.Num_compra='".$credito->numcompra."' AND 
							      pagos.Fecha = '".$ultimo_abono_fecha."' AND 
							      Activo = 'S'	 ";
                                        	$refe=$db->Execute($sqle);
                                        	
                                        	$ultimo_abono_id = $refe->fields[0];
                                        }
                                        else
                                        {
                                        	$ultimo_abono_fecha='0000-00-00';
                                        	$ultimo_abono_id ='0';
                                        	
                                        }
                                        
                                       
                                        


                                        //--------------------------------------------------------------
                                        // Número de abonos en notas de crédito
                                        //--------------------------------------------------------------

                                        $sqld="SELECT COUNT(notas_credito.ID_Nota) 
                                               FROM notas_credito 
                                               WHERE notas_credito.Num_compra='".$credito->numcompra."'  AND notas_credito.Fecha<='".$this->fecha_cierre."' ";
                                        $rdoc=$db->Execute($sqld);
                                        $num_abonos_documento =  $rdoc->fields[0];

                                        //--------------------------------------------------------------
                                        // Desgloce de saldos por cada crédito
                                        //--------------------------------------------------------------
                                        
                                        $saldo_para_liquidar_hoy = $credito->saldo_para_liquidar_hoy + $credito->SaldoFavorPendiente;
                                        $saldo_para_liquidar_hoy =($saldo_para_liquidar_hoy<0.01)?(0):($saldo_para_liquidar_hoy);
                                        
                                        
                                        
                                        
                                        
                                        
                                        unset($antiguedad_saldos);
                                        $antiguedad_saldos = $credito->obtener_antiguedad_saldos();
                                        
                                  	// "       '".($credito->numcargosvencidos_pagados + $credito->numcargosvencidos_no_pagados)."',".
					//   "       '".$credito->numcargosvencidos."',".
 
                                             $rows_to_insert[]= "\n (   '".($id_cierre)."', ".
                                                                "       '".($credito->id_factura)."',     ".  
                                                                "       '".($credito->id_tipocredito) ."' ,  ". 
                                                                
                                                                "       '".$credito->dias_mora."',      ".  
                                                                
                                                                "       '".$credito->plazo."',          ".
                                                                "       '".($credito->numcargosvencidos_pagados + $credito->numcargosvencidos_no_pagados)."',".
                                                                "       '".$credito->numcargosvencidos_pagados."',".
                                                                "       '".$credito->numcargosvencidos_no_pagados."',".
                                                                "       '".number_format(($credito->SumaCapital + ($credito->SumaInteres  + $credito->SumaIVAInteres )+($credito->SumaComision +$credito->SumaIVAComision) + ($credito->SumaSegV + $credito->SumaIVASegV)+($credito->SumaSegD + $credito->SumaIVASegD) + ($credito->SumaSegB + $credito->SumaIVASegB )) ,6,".","")."',".
                                                                
                                                                "       '".number_format( $credito->SumaCapital,6,".","")."',".
                                                                "       '".number_format(($credito->SumaComision + $credito->SumaIVAComision),6,".","")."',".
                                                                "       '".number_format(($credito->SumaInteres  + $credito->SumaIVAInteres ),6,".","")."',".
                                                                "       '".number_format(($credito->SumaOtros    + $credito->SumaIVAOtros   ),6,".","")."',".
                                                                "       '".number_format(($credito->SumaIMB   ),6,".","")."',".                                                           
                                                                "       '".number_format(($credito->SumaSegV            + $credito->SumaIVASegV    ),6,".","")."',".
                                                                "       '".number_format(($credito->SumaSegD            + $credito->SumaIVASegD    ),6,".","")."',".
                                                                "       '".number_format(($credito->SumaSegB            + $credito->SumaIVASegB    ),6,".","")."',".
                                                                "       '".number_format(($credito->SaldoFavorPendiente) ,6,".","")."',".


                                                                
                                                                "       '".number_format( $credito->SumaAbonoCapital ,6,".","")."',".
                                                                "       '".number_format(($credito->SumaAbonoComision   +  $credito->SumaAbonoIVAComision	),6,".","")."',".
                                                                "       '".number_format(($credito->SumaAbonoInteres    +  $credito->SumaAbonoIVAInteres 	),6,".","")."',".
                                                                "       '".number_format(($credito->SumaAbonoIMB                                           	),6,".","")."',".
                                                                "       '".number_format(($credito->SumaAbonoOtros      +  $credito->SumaAbonoIVAOtros     	),6,".","")."',".
                                                                "       '".number_format(($credito->SumaAbonoSegV       +  $credito->SumaAbonoIVASegV      	),6,".","")."',".
                                                                "       '".number_format(($credito->SumaAbonoSegD       +  $credito->SumaAbonoIVASegD      	),6,".","")."',".
                                                                "       '".number_format(($credito->SumaAbonoSegB       +  $credito->SumaAbonoIVASegB      	),6,".","")."',".                                                               
                                                                "       '".number_format(($credito->SaldoFavorPendiente),6,".","")."',".
                                                                "       '".number_format(($credito->SumaAbono ),6,".","")."',".                                                         
                                                                "       '".number_format(($num_abonos_efectivo + $num_abonos_documento),6,".","")."',".



                                                                
                                                                "       '".number_format(($credito->Suma_Abono_Capital_Documento),6,".","")."',".
                                                                "       '".number_format(($credito->Suma_Abono_Interes_Documento   + $credito->Suma_Abono_IVA_Interes_Documento  ),6,".","")."',".
                                                                "       '".number_format(($credito->Suma_Abono_Comision_Documento  + $credito->Suma_Abono_IVA_Comision_Documento ),6,".","")."',".
                                                                "       '".number_format(($credito->Suma_Abono_Otros_Documento     + $credito->Suma_Abono_IVA_Otros_Documento    ),6,".","")."',".
                                                                "       '".number_format(($credito->Suma_Abono_Moratorio_Documento + $credito->Suma_Abono_IVA_Moratorio_Documento),6,".","")."',".
                                                                "       '".number_format(($credito->Suma_Abono_SegV_Documento      + $credito->Suma_Abono_IVA_SegV_Documento     ),6,".","")."',".                                          
                                                                "       '".number_format(($credito->Suma_Abono_SegD_Documento      + $credito->Suma_Abono_IVA_SegD_Documento     ),6,".","")."',".                                          
                                                                "       '".number_format(($credito->Suma_Abono_SegB_Documento      + $credito->Suma_Abono_IVA_SegB_Documento     ),6,".","")."',".
                                                                "       '".number_format(($credito->Suma_Abonos_Total_Documento),6,".","")."',".
                                                                "       '".number_format(($num_abonos_documento),6,".","")."',".



                                                                
                                                                "       '".number_format(($credito->Suma_Abono_Capital_Efectivo),6,".","")."',".
                                                                "       '".number_format(($credito->Suma_Abono_Interes_Efectivo   + $credito->Suma_Abono_IVA_Interes_Efectivo   ),6,".","")."',".
                                                                "       '".number_format(($credito->Suma_Abono_Comision_Efectivo  + $credito->Suma_Abono_IVA_Comision_Efectivo  ),6,".","")."',".
                                                                "       '".number_format(($credito->Suma_Abono_Otros_Efectivo     + $credito->Suma_Abono_IVA_Otros_Efectivo     ),6,".","")."',".
                                                                "       '".number_format(($credito->Suma_Abono_Moratorio_Efectivo + $credito->Suma_Abono_IVA_Moratorio_Efectivo ),6,".","")."',".
                                                                "       '".number_format(($credito->Suma_Abono_SegV_Efectivo      +$credito->Suma_Abono_IVA_SegV_Efectivo       ),6,".","")."',".                                                
                                                                "       '".number_format(($credito->Suma_Abono_SegD_Efectivo      +$credito->Suma_Abono_IVA_SegD_Efectivo       ),6,".","")."',".                                                
                                                                "       '".number_format(($credito->Suma_Abono_SegB_Efectivo      +$credito->Suma_Abono_IVA_SegB_Efectivo       ),6,".","")."',". 
                                                                "       '".number_format(($credito->Suma_Abonos_Total_Efectivo ),6,".","")."',".
                                                                "       '".number_format(($num_abonos_efectivo),6,".","")."',".
                                                                "       '".($ultimo_abono_fecha)."',".
                                                                "       '".($ultimo_abono_id   )."',".

 

                                                                
                                                                "       '".number_format(($credito->SaldoCapital                                   ),6,".","")."',".
                                                                "       '".number_format(($credito->SaldoComision +     $credito->SaldoIVAComision ),6,".","")."', ".
                                                                "       '".number_format(($credito->SaldoInteres  +     $credito->SaldoIVAInteres  ),6,".","")."', ".
                                                                "       '".number_format(($credito->SaldoOtros    +     $credito->SaldoIVAOtros    ),6,".","")."', ".
                                                                "       '".number_format(($credito->SaldoIMB                                       ),6,".","")."', ".
                                                                "       '".number_format(($credito->SaldoSegV     +     $credito->SaldoIVASegV     ),6,".","")."', ".
                                                                "       '".number_format(($credito->SaldoSegD     +     $credito->SaldoIVASegD     ),6,".","")."', ". 
                                                                "       '".number_format(($credito->SaldoSegB     +     $credito->SaldoIVASegB     ),6,".","")."', ". 
                                                                "       '".number_format(($credito->SaldoGeneralVencido                            ),6,".","")."', ".



                                                                
                                                                "       '".number_format(($credito->SaldoCapitalPorVencer                                         ),6,".","")."',".
                                                                "       '".number_format(($credito->SaldoComisionPorVencer + $credito->Saldo_IVA_ComisionPorVencer),6,".","")."',".
                                                                "       '".number_format(($credito->SaldoInteresPorVencer  + $credito->Saldo_IVA_InteresPorVencer ),6,".","")."',".
                                                                "       '".number_format(($credito->SaldoSegVPorVencer     + $credito->Saldo_IVA_SegVPorVencer    ),6,".","")."',".
                                                                "       '".number_format(($credito->SaldoSegDPorVencer     + $credito->Saldo_IVA_SegDPorVencer    ),6,".","")."',".
                                                                "       '".number_format(($credito->SaldoSegBPorVencer     + $credito->Saldo_IVA_SegBPorVencer    ),6,".","")."',".
                                                                "       '".number_format(($credito->SaldoGeneralVigente                                           ),6,".","")."',".



                                                                
                                                                "       '".number_format(($credito->saldo_para_liquidar_hoy + $credito->SaldoFavorPendiente),6,".","")."',".
                                                                "       '".number_format(($credito->adeudo_total),6,".","")."',".                                                                
                                                                "       '".number_format($antiguedad_saldos[0], 6,".","")."',".
                                                                "       '".number_format($antiguedad_saldos[1], 6,".","")."',".
                                                                "       '".number_format($antiguedad_saldos[2], 6,".","")."',".
                                                                "       '".number_format($antiguedad_saldos[3], 6,".","")."',".
                                                                "       '".number_format($antiguedad_saldos[4], 6,".","")."',".
                                                                "       '".number_format($antiguedad_saldos[5], 6,".","")."',".
                                                                "       '".number_format($antiguedad_saldos[6], 6,".","")."',".
                                                                "       '".date("Y-m-d H:i:s")."')";
                                                                
                                                                
                                                                
                                                $this->creditos_procesados++;

                                                if($echo) echo "\n ".number_format($this->creditos_procesados,0)." de ".number_format($this->creditos_procesar,0)." ";



                                                // El crédito ya está saldado.
                                                if(($credito->adeudo_total <= 0.004))
                                                {

                                                        
                                                        $sql = "SELECT ID_Factura FROM factura_cliente_liquidacion WHERE id_factura  = '".$credito->id_factura."' ";
                                                        $rz=$db->Execute($sql);

                                                        if(empty($rz->fields[0]))
                                                        {
                                                               //-------------------------------------------
                                                               // Fecha del Máximo Abono existente que salda el crédito. 
                                                               //-------------------------------------------
                                                               
                                                                
                                                                      $sql="    SELECT  MAX(pagos.Fecha)                                        AS MAXFechaPago,
                                                                                        MAX(notas_credito.Fecha)                                AS MAXFechaNota,
                                                                                        GREATEST(MAX(pagos.Fecha), MAX(notas_credito.Fecha))    AS FechaSaldado
                                                                                FROM fact_cliente
                                                                                
                                                                                
                                                                                LEFT JOIN pagos         ON fact_cliente.num_compra  = pagos.Num_compra and pagos.activo ='S'                              
                                                                                                           and pagos.Fecha <= '".$this->fecha_cierre ."' 
                                                                                
                                                                                LEFT JOIN notas_credito ON notas_credito.Num_compra = fact_cliente.num_compra 
                                                                                                           and notas_credito.Fecha <= '".$this->fecha_cierre ."' 
                                                                                
                                                                                
                                                                                WHERE fact_cliente.id_factura  = '".$credito->id_factura."' 
                                                                                      
                                                                                
                                                                                GROUP BY fact_cliente.Num_compra ";


                                                                      $rt = $db->Execute($sql); 
                                                                      $FechaSaldado = $rt->fields['FechaSaldado'];                                                              
                                                                      $MAXFechaPago = $rt->fields['MAXFechaPago'];
                                                                      $MAXFechaNota = $rt->fields['MAXFechaNota'];  

                                                                      $ID_Pago = 0; 
                                                                      $ID_Caja = 0;

                                                                     if(!empty($MAXFechaPago))
                                                                     {
                                                                            //-------------------------------------------
                                                                            // Fecha del ID del maximo abono en EFECTIVO 
                                                                            //-------------------------------------------


                                                                              $sql="    SELECT MAX(pagos.ID_Pago) AS MID_Pago
                                                                                        FROM fact_cliente
                                                                                        LEFT JOIN pagos                    ON fact_cliente.num_compra  = pagos.Num_compra and pagos.activo ='S'   
                                                                                        WHERE fact_cliente.id_factura  = '".$credito->id_factura."' 
                                                                                              and pagos.Fecha          = '".$MAXFechaPago."' 
                                                                                              
                                                                                        
                                                                                        GROUP BY fact_cliente.Num_compra   ";                         

                                                                              $rt = $db->Execute($sql); 

                                                                              $ID_Pago = $rt->fields['MID_Pago'];



                                                                              $sql = "SELECT pagos.id_caja_pagos FROM pagos WHERE pagos.ID_Pago = '".$ID_Pago."' ";

                                                                              $rt  = $db->Execute($sql);                                                             

                                                                              $ID_Caja = $rt->fields['ID_Caja'];




                                                                     }
                                                                     
                                                                     
                                                                     if(empty($FechaSaldado))
                                                                     {
                                                                     	$sql="  SELECT pagos.Fecha
                                                                     		FROM   pagos
                                                                     		WHERE  pagos.ID_Pago = '".$ID_Pago."' ";
                                                                     	$rf = $db->Execute($sql);
                                                                     	$FechaSaldado = $rf->fields['Fecha'];
                                                                     
                                                                     }
                                                             
                                                             
                                                             
                                                                
                                                                $sql="  INSERT IGNORE INTO factura_cliente_liquidacion                                
                                                                        (ID_Factura, Fecha, ID_Pago, ID_Caja, ID_Cierre)      
                                                                        VALUES                                                                
                                                                        ('".$credito->id_factura."',                                          
                                                                         '".$FechaSaldado."',                                                 
                                                                         '".($ID_Pago * 1)."',                                                      
                                                                         '".($ID_Caja * 1)."',
                                                                         '".$this->id_cierre."'
                                                                        ) ";

                                                                $db->Execute($sql);
                                                                if($db->_affectedrows())
                                                                {
                                                                        $this->creditos_saldados++;
                                                                        if($echo)  echo "... Crédito saldado : ( cliente :".$credito->numcliente." ID : ".$credito->id_factura." ) ";

                                                                }
                                                        }

                                                }
                                                else
                                                {
                                                        $this->creditos_activos++;
                                                }



                             $rs->MoveNext();

                             if((count($rows_to_insert)>10) or ($rs->EOF))
                                {

                                        $rg = $db->Execute($sql_inicio);
                                        
                                       // debug(" ".($credito->id_factura)."($inicio_cierre == ".($rg->fields[0])." ");
                                        
                                        // Si nadie ha destruido la cabecera del cierre, seguimos adelante, de otro modo, nos detenemos.

                                        if($inicio_cierre == $rg->fields[0])
                                        {

                                                $insert_sql = $sql_head.implode(",", $rows_to_insert);
                                                
                                                unset($rows_to_insert);
                                                $rows_to_insert = array();

                                                $db->Execute($insert_sql);
                                              
                                                
                                                
                                               
                                        }
                                        else
                                        {
                                                $error=true;
                                                break;
                                        }
                                        
                                        //debug($insert_sql);
                                        
                                        
                                }





                }
                
                
                
                
                
                //---------------------------------------------------------------------------------
                //Créditos cancelados que estaban activos a la fecha del cierre
                //---------------------------------------------------------------------------------
              
                if(!$error)
                {
                      $num_registros_cancelados = $this->creditos_activos_cancelados_despues_del_cierre();
                        
                }


                //---------------------------------------------------------------------------------
                //Resumen de proceso
                //---------------------------------------------------------------------------------

                  
                if(!$error)
                {

                       $sql = "SELECT COUNT(*) AS CreditosSaldados
                               FROM  cierre_contable_saldos
                               WHERE 
                                     cierre_contable_saldos.ID_Cierre      ='".$this->id_cierre."'      and
                                     cierre_contable_saldos.Adeudo_Total  <=0        ";  
                                
                        $rs = $this->db->Execute($sql);
                        $this->creditos_saldados = $rs->fields['CreditosSaldados'];
   



                       $sql = "SELECT COUNT(*) AS CreditosActivos
                               FROM  cierre_contable_saldos
                               WHERE 
                                     cierre_contable_saldos.ID_Cierre      ='".$this->id_cierre."'      and
                                     cierre_contable_saldos.Adeudo_Total  > 0        ";  
                                
                        $rs = $this->db->Execute($sql);
                        $this->creditos_activos = $rs->fields['CreditosActivos'];
                        
                           



                         if(empty($this->lista_id_facturas) )  // Si no es RE-CIERRE si actualizamos Fin_Individual
                         {                              

                           $sql = "UPDATE cierre_contable_log 
                                   SET Fin_Individual               = now(), 
                                       Fin_Solidarios		    = Fin_Individual,
                                       Creditos_Saldados            = '".$this->creditos_saldados."', 
                                       Creditos_Activos             = '".$this->creditos_activos."'
                                   WHERE ID_Cierre = '". $this->id_cierre ."'";


                          }
                          else
                          {

                            $sql = "UPDATE cierre_contable_log 
                                   SET 
                                       Creditos_Saldados            = '".$this->creditos_saldados."', 
                                       Creditos_Activos             = '".$this->creditos_activos."'
                                   WHERE ID_Cierre = '". $this->id_cierre ."'";


                          }
                        

                        if($this->creditos_saldados.+ $this->creditos_activos) $db->Execute($sql);      




                        $this->time_end         = microtime_float();
                        $this->process_time     = $this->time_end - $this->time_start;  

                        if($echo) echo "\n Tiempo total de procesamiento : ".number_format($this->process_time,2)." seg. ( aprox :".$this->procesa_tiempo($this->process_time).") \n";

                }

        }
        else
        {

                $this->status=-1;
                $this->error="No se econtró el identificador del cierre.";
                if($echo) $this->error."\n";
                return;

        }
}

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Aplica vencimiento anticipado si es posible al crédito origen
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function saldar_credito($id_factura)
{
       
       
        $oMov = new TCREDITO($id_factura, $this->fecha_cierre, $this->db);

        if(empty($oMov->error_msg))
                 $oMov->saldar_cuotas(0);

}

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

function aplica_vencimento_anticipado($id_factura, $fecha_pago, $num_compra)
{

        
/*

        $sql = " SELECT  MAX(pagos.Fecha)
                 FROM    pagos 
                 WHERE   Activo          = 'S' and 
                         Num_Compra      = '".$num_compra."' 
                 GROUP BY pagos.Num_Compra ";

        $rs=$this->db->Execute($sql);
        $fecha_minima_posible  = $rs->fields[0];

*/
        $fecha_minima_posible = $fecha_pago;
        
        
        $sql = "SELECT  COUNT(*) AS NUM 

                FROM    cargos 

                WHERE   cargos.num_compra = '".$num_compra."' and
                        cargos.Fecha_vencimiento >= '".$fecha_minima_posible."' and
                        cargos.ID_Concepto = -3 and 
                        cargos.Concepto NOT LIKE 'Vencimiento anticipado de la cuota%' ";
       // debug($sql);
        $rn  = $this->db->Execute($sql);
        $num_cuotas_por_vencer = $rn->fields['NUM'];
        
        
        
        // -------->Limpieza previa
        
       
       
       if(!empty($this->lista_id_facturas) )
          {                              


  
                $sql = "DELETE FROM notas_credito 
                        WHERE 	notas_credito.Fecha >= '".$fecha_minima_posible."' and  
                        	num_compra='".$num_compra."' and 
                        	ID_Concepto NOT IN (-20,-21,-22,-23,-24)
                        	
                        	and  ((notas_credito.Forma LIKE '%por depuración del cierre%')  OR  (notas_credito.Forma LIKE '%por pago anticipado%'))";
                
                
                
                $this->db->Execute($sql);
                
                //debug($sql);
                
                
                
                $sql = "DELETE FROM cargos WHERE ID_Concepto = -3 and Concepto LIKE 'Vencimiento anticipado de la cuota%' and num_compra='".$num_compra."' ";
                $this->db->Execute($sql);

                $sql = "DELETE FROM cargos WHERE ID_Concepto != -3 and Concepto LIKE '%depuración%' and num_compra='".$num_compra."' ";
                $this->db->Execute($sql);
                
                $sql = "UPDATE cargos SET Activo='Si' WHERE ID_Concepto = -3 and num_compra='".$num_compra."' ";
                $this->db->Execute($sql);
          }



        // -------->Limpieza previa
        $fecha_corte = $this->fecha_cierre;

        
        $lista_pagos = "";

 
        $sql = "SELECT    GROUP_CONCAT(ID_Pago) AS ListadoPagos,
                          pagos.Fecha,
                          SUM(pagos.Monto) AS Monto
                  
                  FROM    pagos 
                  
                  WHERE   Activo          = 'S' and 
                          Num_Compra      = '".$num_compra."' and 
                          Fecha           = '".$fecha_minima_posible."'
                                            
                  GROUP BY pagos.Num_Compra ";
          

          $rp           = $this->db->Execute($sql);
          $lista_pagos  = $rp->fields['ListadoPagos'];
          $fecha_abonos = $rp->fields['Fecha'];
          $monto_abonos = $rp->fields['Monto'];


             $oMov = new TCREDITO($id_factura, $fecha_corte, $this->db, $lista_pagos);

             if(empty($oMov->error_msg))
             {

			
		//debug($num_cuotas_por_vencer );
			
                    if($num_cuotas_por_vencer > 1)
                      $idva = $oMov->aplica_vencimiento_anticipado_con_nota_intereses(" por pago anticipado");
                    else
                      $idva = $oMov->aplica_vencimiento_anticipado();

/*                  
                      if($idva > 0)   
                      {
                              $oMov = new TCREDITO($id_factura, $fecha_corte, $this->db);
                              $oMov->saldar_cuotas();
                      }
*/                     
             }




   return;
}




function procesa_tiempo($segs)
{
        $_hrs=($segs/3600);
        $hrs = floor($_hrs);
        $_min = ($_hrs - $hrs) * 60;
        $min = floor($_min);
        $_seg = ($_min - $min) * 60;
        $seg  =  floor($_seg);

        return ($hrs.":".$min.":".$seg);


}


function documenta_pagos(&$credito)
{




        $abonos = $credito->obtener_desgloce_abonos_efectivo();

        $Capital_Vigente         = 0;   

        $Interes_Vigente         = 0;   
        $Interes_IVA_Vigente     = 0;   


        $Comision_Vigente        = 0;           
        $Comision_IVA_Vigente    = 0;    
        
        $SegV_Vigente            = 0; 
        $SegV_IVA_Vigente        = 0; 
        
        $SegD_Vigente            = 0; 
        $SegD_IVA_Vigente        = 0; 
        
        $SegB_Vigente            = 0; 
        $SegB_IVA_Vigente        = 0; 


        //------------------------------------------

        $Capital_Vencido         = 0;   

        $Interes_Vencido         = 0;   
        $Interes_IVA_Vencido     = 0;   


        $Comision_Vencido        = 0;           
        $Comision_IVA_Vencido    = 0;    

        $SegV_Vencido            = 0; 
        $SegV_IVA_Vencido        = 0; 
        
        $SegD_Vencido            = 0; 
        $SegD_IVA_Vencido        = 0; 
        
        $SegB_Vencido            = 0; 
        $SegB_IVA_Vencido        = 0; 

        //------------------------------------------





     
        $sql = "SELECT   pagos.ID_Pago                  AS ID_Pago,         
                         pagos.Fecha                    AS Fecha,
                         pagos.Monto                    AS Monto,                        
                         caja_pagos.fecha_pago          AS Fecha_Caja,
                         pagos.num_compra               AS Num_Coampra,
                         cat_caja.ID_Sucursal           AS ID_Suc_receptora

                FROM pagos
                
                LEFT JOIN caja_pagos    ON pagos.id_caja_pagos          = caja_pagos.id_caja_pagos
                LEFT JOIN caja_apertura ON caja_pagos.id_caja_apertura  = caja_apertura.id_caja_apertura
                LEFT JOIN cat_caja      ON caja_apertura.id_caja        = cat_caja.id_caja 

                LEFT JOIN cierre_contable_pagos  ON  cierre_contable_pagos.ID_Pago = pagos.ID_Pago and 
                                                     cierre_contable_pagos.ID_Cierre != '".$this->id_cierre."'

                WHERE   pagos.Activo = 'S'  and 
                        pagos.num_compra  = '".$credito->numcompra."'    and
                        pagos.Fecha      <= '".$this->fechacierre."'     and      
                        
                        ((cierre_contable_pagos.ID_Pago  IS NULL)   OR   (pagos.Fecha = '".$this->fechacierre."' )) ";
//   debug($sql);

        $sql_header = " REPLACE DELAYED INTO cierre_contable_pagos 
                        (       ID_Pago, 
                                ID_Cierre, 
                                Num_Compra, 
                                ID_Suc, 
                                
                                Dias_Mora,
                                Monto, 
                                Fecha, 
                                Fecha_Caja, 
                                
                                Capital, 
                                Comision, 
                                Comision_IVA, 
                                Interes, 
                                Interes_IVA, 
                                
                                Moratorio, 
                                Moratorio_IVA, 
                                Otros, 
                                Otros_IVA, 
                                
                                SegV,    
                                SegV_IVA,
                                SegD,  
                                SegD_IVA,
                                SegB,    
                                SegB_IVA,
                                                                
                                SaldoFavor,  




                                Capital_Vigente,        
                                Comision_Vigente,       
                                Comision_IVA_Vigente,   
                                Interes_Vigente,        
                                Interes_IVA_Vigente,    

                                SegV_Vigente,
                                SegV_IVA_Vigente,
                                SegD_Vigente, 
                                SegD_IVA_Vigente,
                                SegB_Vigente, 
                                SegB_IVA_Vigente,




                                Capital_Vencido,        
                                Comision_Vencido,       
                                Comision_IVA_Vencido,   
                                Interes_Vencido,        
                                Interes_IVA_Vencido,  
                                
                                SegV_Vencido,     
                                SegV_IVA_Vencido, 
                                SegD_Vencido,     
                                SegD_IVA_Vencido, 
                                SegB_Vencido,     
                                SegB_IVA_Vencido 
                                
                                
                                
                                ) VALUES ";




        $sql_rows = array();

        $num = 0;




        $rz = $this->db->Execute($sql);
        

        if($rz->_numOfRows)
           while(! $rz->EOF)
           {


                $Capital_Vigente         = 0;   

                $Interes_Vigente         = 0;   
                $Interes_IVA_Vigente     = 0;   


                $Comision_Vigente        = 0;           
                $Comision_IVA_Vigente    = 0;    


                $SegV_Vigente           = 0; 
                $SegV_IVA_Vigente       = 0; 
                $SegD_Vigente           = 0; 
                $SegD_IVA_Vigente       = 0; 
                $SegB_Vigente           = 0; 
                $SegB_IVA_Vigente       = 0; 






                //------------------------------------------

                $Capital_Vencido         = 0;   

                $Interes_Vencido         = 0;   
                $Interes_IVA_Vencido     = 0;   


                $Comision_Vencido        = 0;           
                $Comision_IVA_Vencido    = 0;    

                $SegV_Vencido            = 0; 
                $SegV_IVA_Vencido        = 0; 
                $SegD_Vencido            = 0; 
                $SegD_IVA_Vencido        = 0; 
                $SegB_Vencido            = 0; 
                $SegB_IVA_Vencido        = 0; 
                //------------------------------------------


                $id = $rz->fields['ID_Pago'];
                
                $id_suc         = (empty($rz->fields['ID_Suc_receptora'])       )?("NULL"):("'".$rz->fields['ID_Suc_receptora']."'");
                $fecha_cxc      = (empty($rz->fields['Fecha_Caja'])             )?("NULL"):("'".$rz->fields['Fecha_Caja']."'");
        
        //===================================================================================================================================   
        
                $Capital_Vigente        =       $credito->abonos_contra_saldos_vigentes_por_id_pago[$id]['Capital']                     ;
                
                $Interes_Vigente        =       $credito->abonos_contra_saldos_vigentes_por_id_pago[$id]['Interes']                     ;
                $Interes_IVA_Vigente    =       $credito->abonos_contra_saldos_vigentes_por_id_pago[$id]['Interes_IVA']                 ;
                
                
                $Comision_Vigente       =       $credito->abonos_contra_saldos_vigentes_por_id_pago[$id]['Comision']                    ; 
                $Comision_IVA_Vigente   =       $credito->abonos_contra_saldos_vigentes_por_id_pago[$id]['Comision_IVA']                ; 
                
                
                
                
                $SegV_Vigente           =       $credito->abonos_contra_saldos_vigentes_por_id_pago[$id]['SegV']                ; 
                $SegV_IVA_Vigente       =       $credito->abonos_contra_saldos_vigentes_por_id_pago[$id]['SegV_IVA']            ; 
                $SegD_Vigente           =       $credito->abonos_contra_saldos_vigentes_por_id_pago[$id]['SegD']                ;  
                $SegD_IVA_Vigente       =       $credito->abonos_contra_saldos_vigentes_por_id_pago[$id]['SegD_IVA']            ; 
                $SegB_Vigente           =       $credito->abonos_contra_saldos_vigentes_por_id_pago[$id]['SegB']                ;  
                $SegB_IVA_Vigente       =       $credito->abonos_contra_saldos_vigentes_por_id_pago[$id]['SegB_IVA']            ; 
                
                
        
        //===================================================================================================================================   
        
                $Capital_Vencido        =       $abonos[$id]['Capital']         - $Capital_Vigente      ;

                $Interes_Vencido        =       $abonos[$id]['Interes']         - $Interes_Vigente      ;
                $Interes_IVA_Vencido    =       $abonos[$id]['Interes_IVA']     - $Interes_IVA_Vigente  ;


                $Comision_Vencido       =       $abonos[$id]['Comision']        - $Comision_Vigente     ; 
                $Comision_IVA_Vencido   =       $abonos[$id]['Comision_IVA']    - $Comision_IVA_Vigente ; 
        
                                                             

                $SegV_Vencido           =       $abonos[$id]['SegV']      - $SegV_Vigente         ;
                $SegV_IVA_Vencido       =       $abonos[$id]['SegV_IVA']  - $SegV_IVA_Vigente     ;
                $SegD_Vencido           =       $abonos[$id]['SegD']      - $SegD_Vigente         ;
                $SegD_IVA_Vencido       =       $abonos[$id]['SegD_IVA']  - $SegD_IVA_Vigente     ;
                $SegB_Vencido           =       $abonos[$id]['SegB']      - $SegB_Vigente         ;
                $SegB_IVA_Vencido       =       $abonos[$id]['SegB_IVA']  - $SegB_IVA_Vigente     ;








                                                             
                $sql_rows[] = "\n(      '".($rz->fields['ID_Pago'])."', 
                                        '".($this->id_cierre 		* 1)."', 
                                        '".($credito->numcompra		   )."', 
                                         ".($id_suc			* 1).",                                          
                                        '".($abonos[$id]['Dias_Mora']	* 1)."',                                          
                                        '".($rz->fields['Monto']	* 1)."', 
                                        '".($rz->fields['Fecha']	)."', 
                                         ".($fecha_cxc			).", 
                                        '".($abonos[$id]['Capital'      ] * 1)."', 
                                        '".($abonos[$id]['Comision'     ] * 1)."', 
                                        '".($abonos[$id]['Comision_IVA' ] * 1)."', 
                                        '".($abonos[$id]['Interes'      ] * 1)."', 
                                        '".($abonos[$id]['Interes_IVA'  ] * 1)."',                                         
                                        '".($abonos[$id]['Moratorio'    ] * 1)."', 
                                        '".($abonos[$id]['Moratorio_IVA'] * 1)."', 
                                        '".($abonos[$id]['Otros'        ] * 1)."', 
                                        '".($abonos[$id]['Otros_IVA'    ] * 1)."', 
                                        '".($abonos[$id]['SegV'         ] * 1)."', 
                                        '".($abonos[$id]['SegV_IVA'     ] * 1)."', 
                                        '".($abonos[$id]['SegD'         ] * 1)."', 
                                        '".($abonos[$id]['SegD_IVA'     ] * 1)."', 
                                        '".($abonos[$id]['SegB'         ] * 1)."', 
                                        '".($abonos[$id]['SegB_IVA'     ] * 1)."', 
                                        '".($abonos[$id]['SaldoFavor'   ] * 1)."',   
                                        
                                        '".($Capital_Vigente		* 1)."', 
                                        '".($Comision_Vigente		* 1)."', 
                                        '".($Comision_IVA_Vigente	* 1)."', 
                                        '".($Interes_Vigente		* 1)."', 
                                        '".($Interes_IVA_Vigente	* 1)."', 
                                        
                                        
                                        '".($SegV_Vigente               * 1)."',
                                        '".($SegV_IVA_Vigente           * 1)."',
                                        '".($SegD_Vigente               * 1)."',
                                        '".($SegD_IVA_Vigente           * 1)."',
                                        '".($SegB_Vigente               * 1)."',
                                        '".($SegB_IVA_Vigente           * 1)."',
                                        
                                        
                                        '".($Capital_Vencido		* 1)."', 
                                        '".($Comision_Vencido		* 1)."', 
                                        '".($Comision_IVA_Vencido	* 1)."', 
                                        '".($Interes_Vencido		* 1)."', 
                                        '".($Interes_IVA_Vencido	* 1)."', 
                                        
                                        '".($SegV_Vencido		* 1)."',    
                                        '".($SegV_IVA_Vencido		* 1)."',
                                        '".($SegD_Vencido		* 1)."',    
                                        '".($SegD_IVA_Vencido		* 1)."',
                                        '".($SegB_Vencido		* 1)."',   
                                        '".($SegB_IVA_Vencido		* 1)."') ";
                                        
                                        
                                        
                                        


                ++$num;


                 $rz->MoveNext();
           }
        
        
        
        if($rz->_numOfRows)
        {
                
                $insert_sql = $sql_header.implode(",", $sql_rows);
                
                
                $this->db->Execute($insert_sql);
                
                 //debug($insert_sql);
		 //die();
                                        
                
        }
                
        unset($sql_rows);

}


function reciclar_credito_castigado($id_factura, $id_cierre, $fecha_cierre, $num_compra, $sql_head, &$db)
{

          //-----------------------------------------------------
          // Fecha del último cierre donde apareció el crédito.
          //-----------------------------------------------------
          
          $sql=" SELECT MAX(cierre_contable_log.Fecha_Cierre) AS Ultimo_Cierre
                 FROM  (cierre_contable_saldos, cierre_contable_log)
                 WHERE cierre_contable_saldos.ID_Cierre = cierre_contable_log.ID_Cierre and 
                       cierre_contable_saldos.ID_Factura = '".$id_factura."' AND             
                       cierre_contable_log.Fecha_Cierre < '".$fecha_cierre."'     ";


         $rs=$db->Execute($sql);

         $fecha_ultimo_cierre =  $rs->fields['Ultimo_Cierre'];

         if(empty($rs->fields['Ultimo_Cierre']) )
         {
                return(-1);
         }

          //-----------------------------------------------------
          // ID_Cierre del último cierre donde apareció el crédito.
          //-----------------------------------------------------

          $sql="SELECT cierre_contable_log.ID_Cierre 
                FROM cierre_contable_log 
                WHERE cierre_contable_log.Fecha_Cierre = '".$fecha_ultimo_cierre ."' ";



         $rs=$db->Execute($sql);

         if(empty($rs->fields['ID_Cierre']) )
         {
                return(-1);
         }


         $sql = "SELECT * FROM cierre_contable_saldos WHERE cierre_contable_saldos.ID_Cierre = '".$rs->fields['ID_Cierre']."' and cierre_contable_saldos.ID_Factura = '".$id_factura."' ";
         $rs=$db->Execute($sql);
         
         //debug("A# ".$sql);
         
         
         $tit=array();
         $x = $rs->GetRowAssoc(false);
  
         foreach($x AS $key=>$values)
         {
                $tit[] = $key;
         } 

         //debug(print_r($tit,true));
        
         $rs=$db->Execute($sql);

         $sql_values = "('".$id_cierre."',"; 

         $numcols= $rs->FieldCount()-1;

         for($i=1; $i<$numcols; $i++)
          {
              if($tit[$i]=='diasmora')
              {
                
                //===================================================================================================================================================
                // Al reciclar un crédito castigado, unicamente aumentamos los días de mora de acuerdo a la distancia en días del último cierre al cierre actual.
                //===================================================================================================================================================              
                //   debug("(tit[$i]=='".$tit[$i]."') => ".$rs->fields[$i]."+ffdias($fecha_ultimo_cierre, $fecha_cierre) == ".($rs->fields[$i]+ffdias($fecha_ultimo_cierre, $fecha_cierre)));
                //---------------------------------------------------------------------------------------------------------------------------------------------------
                
                
                $sql_values .= " '".($rs->fields[$i]+ffdias($fecha_ultimo_cierre, $fecha_cierre))."',";           
              
              }
              else
              {          
                $sql_values .= " '".$rs->fields[$i]."',";           
              }
          }


          $sql_values .= " now() ) ";


          $final_sql = $sql_head." ".$sql_values;

          $rs=$db->Execute($final_sql);
          
          
          //debug("B# ".$final_sql);          
          //die();
          
          
          if($db->_affectedrows()>0)
          {
                return(1);

          }
          else
          {
                return(-1);
          }

  
  

}


function creditos_activos_cancelados_despues_del_cierre()
{


             $sql="     SELECT          cierre_contable_log.ID_Cierre                                                                                   AS ID_Cierre,
                                        fact_cliente_cancelacion.ID_Factura                                                                             AS ID_Factura,
                                        fact_cliente_cancelacion.ID_Tipocredito                                                                         AS ID_Tipocredito,

                                        0                                                                                                               AS DiasMora,

                                        fact_cliente_cancelacion.plazo                                                                                  AS Num_Cuotas_Contratadas,


                                        fact_cliente_cancelacion.Capital                                                                                AS Saldo_Vigente_Capital,

                                        IF(fact_cliente_cancelacion.Comision_calculo = 'Porcentual', 
                                          (fact_cliente_cancelacion.Capital * fact_cliente_cancelacion.Comision_Apertura/100),
                                          (fact_cliente_cancelacion.Comision_Apertura)) * (1+fact_cliente_cancelacion.IVA_Comision/100)                 AS Saldo_Vigente_Comisiones,



                                         (fact_cliente_cancelacion.Renta * fact_cliente_cancelacion.plazo) 
                                         - fact_cliente_cancelacion.Capital
                                         -( IF(fact_cliente_cancelacion.Comision_calculo = 'Porcentual', 
                                              (fact_cliente_cancelacion.Capital * fact_cliente_cancelacion.Comision_Apertura/100),
                                              (fact_cliente_cancelacion.Comision_Apertura)) * (1+fact_cliente_cancelacion.IVA_Comision/100))            AS Saldo_Vigente_Intereses,


                                        (fact_cliente_cancelacion.Renta * fact_cliente_cancelacion.plazo)                                               AS Saldo_Total_Vigente,

                                        (fact_cliente_cancelacion.Capital + fact_cliente_cancelacion.Renta )                                            AS Saldo_para_liquidar_hoy,

                                        (fact_cliente_cancelacion.Renta * fact_cliente_cancelacion.plazo)                                               AS Adeudo_Total,                  
                                        now()                                                                                                           AS Calclulado 

                        FROM      fact_cliente_cancelacion

                        LEFT JOIN cierre_contable_log    ON cierre_contable_log.Fecha_Cierre  = '".$this->fecha_cierre ."'  

                        LEFT JOIN cierre_contable_saldos ON cierre_contable_saldos.ID_Factura = fact_cliente_cancelacion.id_factura and
                                                            cierre_contable_saldos.ID_Cierre  = cierre_contable_log.ID_Cierre

                        WHERE      
                                   cierre_contable_saldos.ID_Factura          IS NULL 
                               AND fact_cliente_cancelacion.Fecha_cancelacion IS NOT NULL
                               AND fact_cliente_cancelacion.fecha_exp         <= cierre_contable_log.Fecha_Cierre 
                               AND date(fact_cliente_cancelacion.Fecha_cancelacion) >  cierre_contable_log.Fecha_Cierre                ";



                        if(!empty($this->lista_id_facturas) )
                          {                              
                              $sql .= " and fact_cliente_cancelacion.id_factura IN (".$this->lista_id_facturas .") ";
                          }
  

      $sql .= "      GROUP BY fact_cliente_cancelacion.ID_Factura ";

     // debug($sql."<HR>");

      $rs = $this->db->Execute($sql);
      
      
      $num_registros_por_ingresar = 0;
      
      
      if($rs->_numOfRows)
         while(! $rs->EOF)
         {
                   $insert_sql = " INSERT INTO cierre_contable_saldos           
                                   (ID_Cierre,       
                                    ID_Factura,       
                                    ID_Tipocredito,               
                                    DiasMora,               
                                    Num_Cuotas_Contratadas,       
                                    Saldo_Vigente_Capital,       
                                    Saldo_Vigente_Comisiones,       
                                    Saldo_Vigente_Intereses,       
                                    Saldo_Total_Vigente,               
                                    Saldo_para_liquidar_hoy,               
                                    Adeudo_Total,                     
                                    Calclulado   )    VALUES   "; 

                   $insert_sql .= "(    '".$rs->fields['ID_Cierre']."',
                                        '".$rs->fields['ID_Factura']."',
                                        '".$rs->fields['ID_Tipocredito']."',
                                        '".$rs->fields['DiasMora']."',
                                        '".$rs->fields['Num_Cuotas_Contratadas']."',
                                        '".$rs->fields['Saldo_Vigente_Capital']."',
                                        '".$rs->fields['Saldo_Vigente_Comisiones']."',
                                        '".$rs->fields['Saldo_Vigente_Intereses']."',
                                        '".$rs->fields['Saldo_Total_Vigente']."',
                                        '".$rs->fields['Saldo_para_liquidar_hoy']."',
                                        '".$rs->fields['Adeudo_Total']."',                 
                                        '".$rs->fields['Calclulado']."' ) ";


                   $this->db->Execute($insert_sql);
                   
                  // debug($insert_sql);

                   $num_registros_por_ingresar += $this->db->_affectedrows();

                   $rs->MoveNext();
         }

      

        
        return($num_registros_por_ingresar);
}





































function log_advance($idf)
{

/*
  global $sys_upload_path;
  
  $tiempo = time();
  $fecha =   strftime("%Y-%m-%d",$tiempo);
  $hora  =    strftime("%H:%M:%S",$tiempo);
  
  
  $sys_log_path = $sys_upload_path;               
  $filename = $sys_log_path."cierre_avance_tipo_".$this->id_tipocredito."_del_".date("Y-m-d").".txt";
  
  //debug($filename);  
  //die();
 
  $fp = fopen($filename, 'a');
 
   if($fp)
   {
          $row = $fecha." ".$hora."\tID Factura : ".$idf."\n";
          fwrite($fp, $row);
          fclose($fp);
   }
*/
}









};







?>


