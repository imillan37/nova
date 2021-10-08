<?
//=====================================================================================================================//
// Class TCUENTA : Estado de cuenta sobre saldos insolutos
//=====================================================================================================================//
//require_once($DOCUMENT_ROOT."/rutas.php");

class TCUENTA
{

  
  var $max_dias_calculo   = 91;   // Maximo de dias en que se calculan moratorios
  var $pcnt_extemporaneos = 0.08; // Porcentage de abono a extemporaneos.
  
  var $aplica_saldos_a_favor_anticipadamente=true;
  
  var $moratorios_con_dia_extra=false;

  var $db;
  var $numcliente;
  var $nombrecliente;
  var $idfactura;
  var $id_factura;
  var $cargosvencidos;
  var $tipovencimiento;
  var $dias_periodo;
  var $numcargosvencidos;
  var $numcargosvencidos_no_pagados=0;
  var $numcargosvencidos_pagados=0;
  var $primer_vencimiento;
  var $primvencimiento;   // Mismo, se queda por Compatibilidad reversa.
  var $autorizado_por;
  var $monto_apertura;


  var $fecha_de_exincion;  // Si es un crédito soldario y está dado de baja, esta fecha dirá a partir de que fecha.
  var $motivo_de_extincion;
/**/
  var $ultimo_vencimiento;
  var $tasa_global;
  var $tasa_moratorio ;
  var $plazo;
  var $cargoporperiodo;
  var $fecha_corte;
  var $regimen;
  var $renta;

  var $id_pagos_a_ignorar;
  var $prelacion  = 'EMIACS';
  var $aprelacion = array();

  var $tasa_eq_ssi;

  var $productofinanciero;
  var $id_producto;

  var $avance=0;

  var $SaldoOptimoCobranza ;

  var $iva_pcnt_intereses  = 0.16;
  var $iva_pcnt_comisiones = 0.16;
  var $iva_pcnt_moratorios = 0.16;

  var $dias_gracia_moratorios = 5;

  var $dias_gracia_int_normal = 5;

//  var $dias_en_mes =  (365/12);
  var $dias_en_mes = 30;

  var $IDNV;
  var $IDNVPlus;   // IDNV al finalizar el día.
  var $aIDNV = array();
  var $_ultimo_cargo_fecha;
  var $_ultimo_vencimiento_anticipado_fecha;
  var $_dif;

  var $capital;
  var $interes;
  var $iva;

  var $segv; 
  var $segd; 
  var $segb; 

  var $is_vencimiento_anticipado;    
  var $fecha_vencimiento_anticipado;  
  var $id_cargo_reemplazo_vencimiento_anticipado; 



  var $cargostotales;
  var $fechaapertura;
  var $fecha_inicio;
  var $fecha_ultimo_abono;
  var $numcompra;

  var $tasa_moratoria_diaria;

  var $moratorio_base_capital;
  var $moratorio_base_interes;
  var $moratorio_base_comision;
  var $moratorio_base_moratorio;

  var $fecha_partida;

  var $clasificacion_puntualidad;
  var $id_clasificacion_puntualidad;



  var $montoabonosefectivo;
  var $numabonosefectivo;
  var $montoabonosdocumento;
  var $numabonosdocumento;
  var $montoabonostotal;
  var $numabonostotal;


  var $moratorio_minimo;


  var $num_abonos_antes_pirmer_atraso =0;



  var $id_restructura_origen;
  var $fecha_captura_restructura;

  var $aplicacion = array();

  //--------------------------
  //  Sumatorias Totales


  var $SaldoGeneralVencido = 0;


  var $SumaAbonoCapital         = 0;
  var $SumaAbonoInteres         = 0;
  var $SumaAbonoIVA             = 0;
  var $SumaAbonoComision        = 0;
  var $SumaAbonoIM              = 0;


  var $SumaAbonoIVAComision  = 0;
  var $SumaAbonoIVAInteres   = 0;
  var $SumaAbonoIVAMoratorio = 0;


  var $SumaMonto    = 0;

//============================================
// Abonos en efectivo exclisivamente
//============================================

  var $Suma_Abono_Capital_Efectivo              = 0;
 
  var $Suma_Abono_Interes_Efectivo              = 0;
  var $Suma_Abono_IVA_Interes_Efectivo          = 0;

  var $Suma_Abono_Comision_Efectivo             = 0;
  var $Suma_Abono_IVA_Comision_Efectivo         = 0;

  var $Suma_Abono_Otros_Efectivo                = 0;
  var $Suma_Abono_IVA_Otros_Efectivo            = 0;
  
  var $Suma_Abono_Moratorio_Efectivo            = 0;
  var $Suma_Abono_IVA_Moratorio_Efectivo        = 0;

  var $Suma_Abonos_Num_Efectivo                 = 0;
  var $Suma_Abonos_Total_Efectivo               = 0;

//============================================
// Notas Crédito Exclusivamente
//============================================
  
  var $Suma_Abono_Capital_Documento             = 0;
 
  var $Suma_Abono_Interes_Documento             = 0;
  var $Suma_Abono_IVA_Interes_Documento         = 0;

  var $Suma_Abono_Comision_Documento            = 0;
  var $Suma_Abono_IVA_Comision_Documento        = 0;

  var $Suma_Abono_Otros_Documento               = 0;
  var $Suma_Abono_IVA_Otros_Documento           = 0;
  
  var $Suma_Abono_Moratorio_Documento           = 0;
  var $Suma_Abono_IVA_Moratorio_Documento       = 0;

  var $Suma_Abonos_Num_Documento                = 0;
  var $Suma_Abonos_Total_Documento              = 0;
  
  
  


  //--------------------------

  var $SumaComision     = 0;
  var $SumaCapital      = 0;
  var $SumaInteres      = 0;
  var $SumaIM           = 0;

  var $SumaSegV         = 0;
  var $SumaSegD         = 0;
  var $SumaSegB         = 0;



  var $SumaIVA          = 0;

  var $SumaIVAComision  = 0;
  var $SumaIVAInteres   = 0;
  var $SumaIVAMoratorio = 0;
  
  var $SumaIVASegV         = 0;  
  var $SumaIVASegD         = 0;  
  var $SumaIVASegB         = 0;  

  var $SumaSaldoFavorAplicado = 0;


  var $SumaAbonos       = 0;

  //--------------------------
  //  Saldo vencido no pagado

  var $SaldoCapital     = 0;

  var $SaldoComision    = 0;
  var $SaldoInteres     = 0;
  var $SaldoIM          = 0;
  
  var $SaldoSegV   = 0;
  var $SaldoSegD   = 0;
  var $SaldoSegB   = 0;
 
  var $SaldoIVAComision  = 0;
  var $SaldoIVAInteres   = 0;
  var $SaldoIVAMoratorio = 0;
  
  var $SaldoIVASegV = 0;  
  var $SaldoIVASegD = 0;  
  var $SaldoIVASegB = 0;  

  var $SaldoIVA         = 0;


  var $Saldo_IVA_InteresPorVencer  = 0;
  var $Saldo_IVA_ComisionPorVencer = 0;
  
  var $SaldoSegV_IVA_PorVencer  = 0;
  var $SaldoSegD_IVA_PorVencer  = 0;
  var $SaldoSegB_IVA_PorVencer  = 0;

  //---------------------------
  // Saldo global
  var $SaldoGlobalCapital = 0;
  var $SaldoGlobalInteres = 0;
  var $SaldoGlobalIVA   = 0;
  
  var $SaldoGlobalSegV    = 0;
  var $SaldoGlobalSegD    = 0;
  var $SaldoGlobalSegB    = 0;

  var $SaldoParaLiquidar = 0;
  var $SaldoParaLiquidarSinMoratorios = 0;
  var $SaldoFavorPendiente = 0;

  var $SaldoCapitalNoVencido= 0;



  var $num_cuota_preprocesada;

  var $abonos_contra_saldos_vigentes_por_id_pago;
  var $abonos_contra_saldos_vigentes_por_pago;
  var $abonos_contra_saldos_vigentes_por_couta;
  
  var $parametros_variables             = array();

  var $detalle_moratorios               = array();
  var $encabezado_detalle_moratorios    = array();

  var $ver_desglose_calculos    = false;

  var $ver_desglose_cargos      = true;
  var $ver_desglose_abonos      = true;
  var $ver_saldo_desglosado     = true;
  var $ver_saldos_vencer        = true;
  var $ver_saldo_general        = true;
  var $ver_cabeceras            = true;
  var $ver_abonos               = true;
  var $ver_cargos               = true;


  var $SumaMoratoriosPorCuota = 0;
  
  var $saldos_cuota                     = array();   // Almacena los saldos de cada cuota;
  var $abonos_desde                     = array();
  var $fechas_inhabiles                 = array();
  var $cargos_cobranza_automaticos      = array();

 
 var $historico_morosidad               = array();
 var $historico_morosidad_cuota         = array();
 var $historico_morosidad_global        = array(); 
 
 var $historico_morosidad_cobranza      = array(); 
 
 var $semaforo_mora                     = array();

        //-------------------------------------------------------------------------------------------------------------------------
        //Saldos Con IVA
        //-------------------------------------------------------------------------------------------------------------------------


  var   $ref_externa;
  var   $saldo_vencido ;
  var   $saldo_por_vencer_capital ;                
  var   $saldo_por_vencer_comision ;
  var   $saldo_por_vencer_interes ;
  var   $saldo_por_vencer_total;
  var   $adeudo_total; 
  var   $proxima_cuota;
  var   $tasa_equivalente_periodo ;
  var   $proxima_cuota_interes;
  var   $saldo_para_liquidar_hoy ;
  
  var   $dias_mora_max=0;
  
  var   $ver_detalle_de_saldos_por_concepto = true;
  var   $ver_detalle_de_saldo_base_cuotas   = true;
  var   $ver_detalle_de_datos_del_credito   = true;  
  var   $ver_detalle_de_datos_credito       = true;
  var   $ver_historico_morosidad            = false;
  var   $ver_saldos_por_cuota               = false;
  
  var   $ultima_couta_preprocesada=-1;
  
  //-----------------------------------------------------------



function TCUENTA($idfactura, $fecha_corte, $debug=0, $id_pagos_a_ignorar='', $moratorios_con_dia_extra=false)  // Constructor
{
    $time_start = microtime_float();
    
    $this->db = &ADONewConnection(SERVIDOR);  # create a connection
    $this->db->Connect(IP,USER,PASSWORD,NUCLEO);
    
    if(NUCLEO == 'rsinaloa_v45')
    {
    	$this->max_dias_calculo   = 360; 
    }
    
    

    $idfactura= intval(trim($idfactura));

    $this->idfactura  = $idfactura;
    $this->id_factura = $idfactura;

   
   $this->debug=$debug;
   $primer_atraso = false;

   $this->moratorios_con_dia_extra = $moratorios_con_dia_extra;

   
   if(!empty($id_pagos_a_ignorar))
   {   
        $this->id_pagos_a_ignorar = $id_pagos_a_ignorar;

        $this->abonos_desde = explode(",", $id_pagos_a_ignorar);
        if($this->debug)
        {
                //debug($id_pagos_a_ignorar);
                //print_r($this->abonos_desde);
        }
        
   }
   
 
   $this->fecha_partida = $fecha_partida;
   
   
   
   

   $this->fecha_corte = $fecha_corte;

   $_anio = fanio($fecha_corte);

   $_mes  = fmes($fecha_corte);
   $_mes = (strlen($_mes)<2)?("0".$_mes):($_mes);

   $_dia  = fdia($fecha_corte);
   $_dia = (strlen($_dia)<2)?("0".$_dia):($_dia);


   $sql = "SELECT Zona_IVA FROM fact_cliente WHERE fact_cliente.id_factura   = '".$idfactura."' ";
   $rs=$this->db->Execute($sql);
   
   $this->zona_iva = $rs->fields['Zona_IVA'];
   
   
   
   
        $sql = "SELECT compras.Observaciones, 
                       compras.ID_Credito_pisa,
                       fact_cliente.num_compra
                FROM  compras, fact_cliente
                WHERE compras.Num_compra = fact_cliente.num_compra and
                      fact_cliente.id_factura = '".$idfactura."' ";

              
        $rs=$this->db->Execute($sql);

        $this->autorizado_por = $rs->fields[0];
        
        if($_pos = strpos($this->autorizado_por," : "))
          $this->autorizado_por = trim(substr($this->autorizado_por,($_pos+3)));
        else
          $this->autorizado_por = "";

        $this->ref_externa = $rs->fields[1];


    $this->numcompra    = $rs->fields[2];
    $this->num_compra   = $this->numcompra ;




        $sql = "SELECT promotores.Nombre, 
                       fact_cliente.Metodo, 
                       fact_cliente.Renta,
                       fact_cliente.Extemporaneos_Tipo
                       
                FROM  fact_cliente
                LEFT JOIN promo_ventas     ON promo_ventas.Num_compra = fact_cliente.num_compra     
                LEFT JOIN promotores       ON promotores.Num_promo   = promo_ventas.ID_Promo          
                WHERE fact_cliente.id_factura =    '".$idfactura."'";
                

                

 $rs=$this->db->Execute($sql);

 $this->promotor 	    = $rs->fields[0];
 $this->metodo_amortizacion = $rs->fields[1];
 $this->renta 		    = $rs->fields[2]; 
 $this->extemporaneos_tipo  = $rs->fields[3];
 


 $sql = "SELECT MAX(fact_cliente_ext.Dias) AS max,
		MIN(fact_cliente_ext.Dias) AS min
	 FROM   fact_cliente_ext
	 WHERE  fact_cliente_ext.id_factura = '".$this->id_factura."' ";
 $rs=$this->db->Execute($sql);


 $this->limite_ext_inferior_dias = $rs->fields['min'];
 $this->limite_ext_superior_dias = $rs->fields['max'];


 $sql = "SELECT fact_cliente_ext.Dias,
		fact_cliente_ext.Monto
	 FROM   fact_cliente_ext
	 WHERE  fact_cliente_ext.id_factura = '".$this->id_factura."' ";
//debug($sql);

 $this->extemporaneos_tabla = array();
 $this->ext_tabla = array();

 $rs=$this->db->Execute($sql);
 if($rs->_numOfRows)
 {
 
    $_i=0;
    while(! $rs->EOF)
    {
      $_dias_ext  = $rs->fields['Dias'];
      $_monto_ext = $rs->fields['Monto']; 
      
      if(($_dias_ext>0) and ($_monto_ext > 0))
      {
      	
      	//Por Cuota
      	$this->extemporaneos_tabla[$_i]['dias']  =$_dias_ext;      	
      	$this->extemporaneos_tabla[$_i]['monto']= $_monto_ext;
      	
      	//Generales
      	$this->ext_tabla[$_dias_ext] = $_monto_ext;
      	
      	
      	
      	++$_i;
      	$rs->MoveNext();
      }
    }
 }
 



//========================================================================================================
// Convenios de pago.
//========================================================================================================
// Establecen fechas en las cuales todos los abonos que se recieban se tomarán con prelación inversa.
// Si durante alguno de los periodos establecidos, ( en el caso de multiples convenios ) la suma de los
// abonos realizados fuera igual o mayor a lo que el cliente se comprometió, entonces el cierre saldará 
// el crédito en cuestión.
//========================================================================================================


 $sql = "SELECT ID_Convenio, Fecha_inicio, Fecha_final
         FROM convenios
         WHERE ID_Factura = '".$idfactura."'  
         ORDER BY Fecha_inicio ";

 $rs=$this->db->Execute($sql);
 
   $this->id_convenio           = $rs->fields['ID_Convenio'];
   $this->fecha_inicio_convenio = $rs->fields['Fecha_inicio'];
   $this->fecha_final_covenio   = $rs->fields['Fecha_final'];



  $this->convenios_pago = array();
  $c=0;
  if($rs->_numOfRows)
     while(! $rs->EOF)
     {
          $this->convenios_pago[$c]['id_convenio']           = $rs->fields['ID_Convenio'];
          $this->convenios_pago[$c]['fecha_inicio_convenio'] = $rs->fields['Fecha_inicio'];
          $this->convenios_pago[$c]['fecha_final_covenio']   = $rs->fields['Fecha_final'];
          
          ++$c;
          
          $rs->MoveNext();
     }

  






$this->parametros_variables = array();

 $sql = "   SELECT 
               
               Fecha_ini, 
               Fecha_fin, 
               Dias_Gracia,
               Gastos_Cobranza_Porcentaje,
               Int_Moratorio
               
        FROM  fact_cliente_parametros
        WHERE ID_Factura = '".$this->idfactura."'
        ORDER BY Fecha_ini, Fecha_fin ";
        
        //debug($sql);
        
$rs =  $this->db->Execute($sql);
if($rs->_numOfRows)
{ $ndx = 0;
   while(! $rs->EOF)
   {

        $this->parametros_variables[$ndx]['FECHA_INI'      ] = $rs->fields['Fecha_ini'    ];
        $this->parametros_variables[$ndx]['FECHA_FIN'      ] = $rs->fields['Fecha_fin'    ];
        $this->parametros_variables[$ndx]['DIAS_GRACIA'    ] = $rs->fields['Dias_Gracia'  ];
        $this->parametros_variables[$ndx]['GASTOS_COBRANZA'] = $rs->fields['Gastos_Cobranza_Porcentaje'];
        $this->parametros_variables[$ndx]['INT_MORATORIO'  ] = $rs->fields['Int_Moratorio'];
        
        ++$ndx;
        
        
     $rs->MoveNext();
   }
}







/*

   $sql = "    SELECT      id_factura_origen, fecha_captura, usuario, total_restructurado
                           FROM restructura_credito
                           WHERE id_factura_destino = '".$idfactura."' ";
    $rs =  $this->db->Execute($sql);

    $this->id_restructura_origen     = $rs->fields[0];
    $this->fecha_captura_restructura = $rs->fields[1];
    $this->autor_captura_restructura = $rs->fields[2];
    $this->monto_restructura    = $rs->fields[3];
*/



   $sql = "    SELECT   cargos.monto,
                        fact_cliente.vencimiento,
                        fact_cliente.vencimiento,
                        fact_cliente.renta,
                        fact_cliente.prelacion,
                        fact_cliente.seguros_prelacion,
                        fact_cliente.Comision_Apertura,
                        fact_cliente.Comision_Tipo,
                        fact_cliente.Comision_calculo,
                        fact_cliente.Capital,
                        fact_cliente.Plazo,
                        fact_cliente.DiasEnElMes,
                        fact_cliente.Gastos_Cobranza_Porcentaje
                FROM    cargos,
                        fact_cliente
                WHERE   cargos.ID_Cargo         = 1     and 
                        cargos.Activo           = 'Si'  and
                        cargos.Num_compra       = fact_cliente.Num_compra and
                        fact_cliente.id_factura =   '".$idfactura."' ";

    $rs =  $this->db->Execute($sql);
    $renta = $rs->fields['renta'];
    $this->dias_en_mes = $rs->fields['DiasEnElMes'];

      $prelacion = $rs->fields['prelacion'];

      if((empty($prelacion)) or (strlen($prelacion) < 6))
      {
        $prelacion = 'EMIACS';
      }
      
      
      $this->prelacion = $prelacion;       
      $this->aprelacion = array();
      $this->aprelacion = str_split($this->prelacion);
      


      $seguros_prelacion = $rs->fields['seguros_prelacion'];

      if((empty($seguros_prelacion)) or (strlen($seguros_prelacion) < 3))
      {
        $seguros_prelacion = 'VDB';
      }
      
      $this->seguros_prelacion = $seguros_prelacion;       
      $this->seguros_aprelacion = array();
      $this->seguros_aprelacion = str_split($this->seguros_prelacion);
      
       
      
      
     
//      Se le agregó el IVA a pertición de Adrián
//      $this->pcnt_extemporaneos = $rs->fields['Gastos_Cobranza_Porcentaje']/100 * (1+$this->iva_pcnt_comisiones);

      $this->pcnt_extemporaneos = $rs->fields['Gastos_Cobranza_Porcentaje']/100 ;

/*        


    if($rs->fields['Comision_calculo'] == 'Cuota')
    {
        $this->comision_por_apertura     = $rs->fields['Comision_Apertura'];
        $this->comision_por_apertura_iva = $this->comision_por_apertura  *   $this->iva_pcnt_comisiones;
    }
    else
    {
        $this->comision_por_apertura     = ($rs->fields['Comision_Apertura']/100) *$rs->fields['Capital'];
        $this->comision_por_apertura_iva = $this->comision_por_apertura_iva * $this->iva_pcnt_comisiones;
    }


    if($rs->fields['Comision_Tipo'] == 'Porcentual')
    {
        $this->comision_por_apertura     = $rs->fields['Comision_Apertura'] *   $rs->fields['Plazo'];
        $this->comision_por_apertura_iva = $this->comision_por_apertura     *   $this->iva_pcnt_comisiones;
    }


      // Por el cambio de IVA 
*/


   $sql = " SELECT 
                SUM((cargos.Interes - cargos.AntiInteres))              AS Interes,
                SUM(cargos.IVA_Interes)                                 AS IVA_Interes,
                SUM((cargos.Comision - cargos.AntiComision))            AS Comision,
                SUM(cargos.IVA_Comision)                                AS IVA_Comision


                FROM cargos, fact_cliente 

                WHERE   cargos.ID_Concepto = -3 and
                        cargos.Activo           = 'Si'  and
                        cargos.Num_compra       = fact_cliente.Num_compra and
                        fact_cliente.id_factura =   '".$idfactura."' ";

    $rs =  $this->db->Execute($sql);
    
    $this->comision_por_apertura     = $rs->fields['Comision'];
    $this->comision_por_apertura_iva = $rs->fields['IVA_Comision'];


    $this->Cargos_Totales_Interes = $rs->fields['Interes']; 
    $this->Cargos_Totales_IVA_Interes   = $rs->fields['IVA_Interes']; 



//$dm = (365/12);
 $this->dias_periodo = $this->dias_en_mes ;
        switch($rs->fields[1])
        {
                case 'Anios'            : $this->dias_periodo =  12*($this->dias_en_mes );   break;
                case 'Semestres'        : $this->dias_periodo =   6*($this->dias_en_mes );   break;
                case 'Trimestres'       : $this->dias_periodo =   3*($this->dias_en_mes );   break;
                case 'Bimestres'        : $this->dias_periodo =   2*($this->dias_en_mes );   break;
                case 'Meses'            : $this->dias_periodo =   1*($this->dias_en_mes );   break;
                case 'Quincenas'        : $this->dias_periodo =   15;    break;
                case 'Catorcenas'       : $this->dias_periodo =   14;    break;                
                case 'Semanas'          : $this->dias_periodo =   7;     break;
                case 'Dias'             : $this->dias_periodo =   1;     break;
        };


/*
    $sql = "    SELECT
                       SUM(IF(conceptos.forma='Efectivo',pagos.Monto,0))                AS MontoAbonosEfectivo,
                       SUM(IF(conceptos.forma='Efectivo',1,0))                          AS NumAbonosEfectivo,

                       SUM(IF(conceptos.forma='Documento',pagos.Monto,0))               AS MontoAbonosDocumento,
                       SUM(IF(conceptos.forma='Documento',1,0))                         AS NumAbonosDocumento,

                       SUM(pagos.Monto)                                                 AS MontoAbonosTotal,
                       COUNT(*)                                                         AS NumAbonosTotal


                FROM pagos,
                     fact_cliente

                LEFT JOIN  conceptos ON pagos.id_concepto = conceptos.id_concepto AND conceptos.tipo='A'


                WHERE pagos.Num_compra = fact_cliente.Num_compra and
                      fact_cliente.id_factura =  '".$idfactura."' and
                      pagos.fecha <= '".$_anio."-".$_mes."-".$_dia."' and 
                      pagos.Activo               = 'S'   ";


    $rs =  $this->db->Execute($sql);

    list($montoabonosefectivo,$numabonosefectivo,$montoabonosdocumento,$numabonosdocumento,$montoabonostotal,$numabonostotal )=$rs->fields;


    $this->montoabonosefectivo          =        $montoabonosefectivo;
    $this->numabonosefectivo            =        $numabonosefectivo;
    $this->montoabonosdocumento         =        $montoabonosdocumento;
    $this->numabonosdocumento           =        $numabonosdocumento;
    $this->montoabonostotal             =        $montoabonostotal;
    $this->numabonostotal               =        $numabonostotal;
*/






    $sql = "SELECT COUNT(*)
              FROM fact_cliente
             WHERE fact_cliente.Num_compra = '".$this->numcompra."' and
                   Nombre_Producto LIKE '%RENOVACION%' ";
 
        $rs = $this->db->Execute($sql);

    $this->is_renovacion = $rs->fields[0];
    
    $sql = "SELECT cargos.ID_Cargo, cargos.Fecha_Vencimiento
              FROM cargos
             WHERE cargos.Num_compra = '".$this->numcompra."' 
                   and ID_Concepto = '-3' 
                   and    (Concepto LIKE 'Vencimiento anticipado de la cuota%') ";
        
        $rs = $this->db->Execute($sql);
                   
        if($rs->fields[0]>0)
        {
        
            $this->is_vencimiento_anticipado    = $rs->fields[0];
            $this->fecha_vencimiento_anticipado = $rs->fields[1];


                if($this->is_vencimiento_anticipado)
                {
                        $sql = "SELECT COUNT(*) FROM cargos       WHERE num_compra = '".$this->numcompra."' AND  cargos.ID_Concepto =-3    and  cargos.Activo='Si' ";
                        
                        $rs  = $this->db->Execute($sql);
                        
                        $this->id_cargo_reemplazo_vencimiento_anticipado = $rs->fields[0];
                }

        
        }
        



    $sql = "SELECT      SUM(Monto) AS MotoApertura

                FROM    cargos,
                        fact_cliente

                WHERE   cargos.Num_compra           = fact_cliente.Num_compra  and
                        fact_cliente.id_factura     = '".$idfactura."' and
                        cargos.ID_Concepto = '-3' and 
                        Concepto NOT LIKE 'Vencimiento anticipado%' 

                GROUP BY    cargos.Num_compra
                ORDER BY    cargos.Num_compra ";

    $rs =  $this->db->Execute($sql);
    $this->monto_apertura  =$rs->fields['MotoApertura'];
    if( empty($this->monto_apertura))
        {
                return(-1);
        }



    $sql = "
    SELECT   MIN(cargos.fecha_vencimiento)           AS PrimVencimiento,
             MAX(cargos.fecha_vencimiento)           AS UltimoVencimiento,
             COUNT(cargos.ID_Cargo),
             SUM(Monto)

        FROM    cargos,
                fact_cliente

        WHERE   cargos.Num_compra           = fact_cliente.Num_compra  and
                fact_cliente.id_factura     = '".$idfactura."' and
                cargos.ID_Concepto = '-3' and 
                cargos.Activo = 'Si' 

        GROUP BY    cargos.Num_compra
        ORDER BY    cargos.Num_compra ";


    $rs =  $this->db->Execute($sql);


    list($primer_vencimiento, $ultimo_vencimiento, $numero_cargos_efectivos)=$rs->fields;
    $this->primer_vencimiento = $primer_vencimiento;
    $this->ultimo_vencimiento = $ultimo_vencimiento;
    $this->numcargos_totales  = $numero_cargos_efectivos;



    $sql = "
    SELECT  compras.Num_cliente                     AS NumCliente ,

            (Concat(clientes_datos.Ap_paterno,' ',clientes_datos.Ap_materno,' ',clientes_datos.Nombre,' ',clientes_datos.NombreI)) AS NomCliente,
            fact_cliente.id_factura                 AS IdFactura,

            SUM(IF(cargos.fecha_vencimiento  <=     '".$_anio."-".$_mes."-".$_dia."' and cargos.Activo = 'Si',
                   (cargos.Monto - cargos.AntiMonto),0))                 AS CargosVencidos,


            (fact_cliente.interes/100)              AS Tasa_Global,
            fact_cliente.int_moratorio              AS Tasa_Moratorio,
            fact_cliente.plazo                      AS Plazo,
            fact_cliente.capital                    AS Capital,

            SUM((cargos.Monto - cargos.AntiMonto))                       AS CargosTotales,
            fact_cliente.fecha_exp                  AS FechaApertura,
            cargos.Num_compra                       AS NumCompra,
            MAX(cargos.fecha_vencimiento)           AS UltimoVencimiento,
            fact_cliente.renta                      AS CargoPorPeriodo,
            SUM(IF( (cargos.ID_Concepto =-3 and cargos.fecha_vencimiento  <=     '".$_anio."-".$_mes."-".$_dia."'),
                   1,0))                            AS NumCargosVencidos,
            'solicitud.Regimen',
            fact_cliente.fecha_inicio,
            fact_cliente.ID_Tipocredito

        FROM    cargos,
                compras,
                fact_cliente
                
                
        LEFT JOIN clientes_datos on clientes_datos.num_cliente = fact_cliente.num_cliente

        WHERE   cargos.Num_compra       = compras.Num_compra and
            fact_cliente.Num_compra     = compras.Num_compra and
            compras.Num_cliente         = fact_cliente.Num_cliente and
            cargos.Activo = 'Si'  and
            fact_cliente.id_factura     = '".$idfactura."'

        GROUP BY    cargos.Num_compra
        ORDER BY    cargos.Num_compra ";


//debug($sql);
    $rs =  $this->db->Execute($sql);
    list($numcliente,      $nombrecliente,              $idfactura,     $cargosvencidos, $tasa_global,          $tasa_moratorio,
         $plazo,$capital,  $cargostotales,              $fechaapertura, $numcompra,      $ultimovencimiento,
         $cargoporperiodo, $numcargosvencidos,          $regimen,       $fecha_inicio,   $id_tipocredito)=$rs->fields;

        if( empty($cargostotales))
        {
                return(-1);
        }




    $this->idfactura            = $idfactura;
    $this->id_factura           = $this->idfactura;    
    $this->numcliente           = $numcliente;
    $this->nombrecliente        = $nombrecliente;
    $this->primer_vencimiento   = $primer_vencimiento;
    $this->primvencimiento      = $this->primer_vencimiento;


    $this->tasa_global          = $tasa_global;
    $this->tasa_moratorio       = $tasa_moratorio;
    $this->cargoporperiodo              = $cargoporperiodo;

    $this->tasa_moratoria_diaria = $this->tasa_moratorio/30;

    $this->plazo                = $plazo;
    $this->capital              = $capital;
    $this->cargostotales        = $cargostotales;
    $this->fechaapertura        = $fechaapertura;
    $this->numcargosvencidos    = 0;
    $this->regimen              = $regimen;
    $this->fecha_inicio         = $fecha_inicio;
    
    $this->id_tipocredito       = $id_tipocredito;

//-------------------------------------------------------
//Cuando se remueven creditos solidarios de un grupo.
//-------------------------------------------------------
    if( $this->id_tipocredito == 2)
    {

              $sql = " SELECT  fecha_baja, id_motivo  FROM grupo_solidario_baja_definitiva WHERE id_factura = '".$this->idfactura."' ";
              $rs = $this->db->Execute($sql);
     
    
    
              $this->fecha_de_exincion   = $rs->fields['fecha_baja'];
              $this->motivo_de_extincion = $rs->fields['id_motivo'];
    
    
    }

/**/

    $sql = "SELECT Nombre_Producto, ID_Producto
              FROM fact_cliente
             WHERE fact_cliente.Num_compra = '".$this->numcompra."' ";

 
 
    $rs = $this->db->Execute($sql);
    $this->productofinanciero = $rs->fields[0];
    $this->id_producto  = $rs->fields[1];





    $sql="      SELECT Fecha_Inhabil, Fecha_Sig_habil
                FROM cat_fechas_inhabiles
                WHERE Fecha_Inhabil >= '".$this->primer_vencimiento."' and Fecha_Inhabil <= '". $this->ultimo_vencimiento."'
                ORDER BY Fecha_Inhabil ";

    $rs =  $this->db->Execute($sql);


        if($rs->_numOfRows)
           while(! $rs->EOF)
           {
                   $this->fechas_inhabiles[($rs->fields[0])] = $rs->fields[1];

                $rs->MoveNext();
           }








//-----------------------------------------------------------------------------------
// Definición de tasa de iva por rubro y días de gracia para aplicación de moratorios
//-----------------------------------------------------------------------------------


    $sql = "SELECT IVA_Interes, IVA_Moratorio, IVA_Comision, Dias_Gracia
              FROM   fact_cliente
             WHERE id_factura='".$idfactura."' ";


    $rs =  $this->db->Execute($sql);



    $this->iva_pcnt_intereses           = $rs->fields[0]/100;
    $this->iva_pcnt_moratorios          = $rs->fields[1]/100;
    $this->iva_pcnt_comisiones          = $rs->fields[2]/100;
    $this->dias_gracia_moratorios       = $rs->fields[3];
    $this->dias_gracia_int_normal       = $rs->fields[3];



//    Se removió para evitar que los vencimientos anticipados afecten el SALDO DE INTERES POR VENCER

    $sql = "SELECT 
    
            SUM((cargos.Capital  -  cargos.AntiCapital)   ) AS Capital,  
            SUM((cargos.Interes  -  cargos.AntiInteres)   ) AS Interes,  
            SUM((cargos.Comision  -  cargos.AntiComision) ) AS Comision,  

            SUM(cargos.SegV) AS SegV,
            SUM(cargos.SegD) AS SegD,
            SUM(cargos.SegB) AS SegB,





            SUM((IVA - AntiIVA)) AS IVA,


            SUM(cargos.IVA_Comision  ) AS IVA_Comision, 
            SUM(cargos.IVA_Interes   ) AS IVA_Interes, 
            
            SUM(cargos.IVA_SegV) AS IVA_SegV,
            SUM(cargos.IVA_SegD) AS IVA_SegD,
            SUM(cargos.IVA_SegB) AS IVA_SegB
            
 
 
            FROM cargos
            WHERE num_compra='".$this->numcompra."' and id_concepto = -3  and Activo='Si' ";

    $rs=$this->db->Execute($sql);



        $this->_capital                 = $rs->fields['Capital'];
        $this->interes                  = $rs->fields['Interes'];
        $this->_comision_por_apertura   = $rs->fields['Comision'];
        $this->iva                      = $rs->fields['IVA'];

        $this->iva_comision_por_apertura    = $rs->fields['IVA_Comision'];
        $this->iva_interes                  = $rs->fields['IVA_Interes'];
        
        
        $this->_segv = $rs->fields['SegV'];
        $this->_segd = $rs->fields['SegD'];
        $this->_segb = $rs->fields['SegB'];
        
        
        $this->segv = $rs->fields['SegV'];
        $this->segd = $rs->fields['SegD'];
        $this->segb = $rs->fields['SegB'];
        
        

        
       $this->iva_segv = $rs->fields['IVA_SegV'];        
       $this->iva_segd = $rs->fields['IVA_SegD'];        
       $this->iva_segb = $rs->fields['IVA_SegB'];


       $this->_iva_segv = $rs->fields['IVA_SegV'];        
       $this->_iva_segd = $rs->fields['IVA_SegD'];        
       $this->_iva_segb = $rs->fields['IVA_SegB'];



//    $this->comision_por_apertura     = $rs->fields[2]; 
//    $this->comision_por_apertura_iva = $this->comision_por_apertura  *   $this->iva_pcnt_comisiones;
    
/*   
    
    $this->interes = (($this->renta)*($this->plazo) -$this->capital - ($this->comision_por_apertura *(1+$this->iva_pcnt_comisiones)))/(1+$this->iva_pcnt_intereses);
    $this->iva     = ($this->interes * $this->iva_pcnt_intereses) + ($this->comision_por_apertura * $this->iva_pcnt_comisiones);
*/     
    
    
    if($this->monto_apertura >0 )
    {
	    $pcapital = $capital/$this->monto_apertura;
	    $pinteres = ($this->interes)/$this->monto_apertura;
	    $piva     = ($this->iva )/$this->monto_apertura;

	    $this->pcapital = $pcapital;
	    $this->pinteres = $pinteres;
	    $this->piva     = $piva;

    }
    else
    {
	    $pcapital = 0;
	    $pinteres = 0;
	    $piva     = 0;

	    $this->pcapital = $pcapital;
	    $this->pinteres = $pinteres;
	    $this->piva     = $piva;

    }
    
   $capital_credito = $capital;

    //-----------------------------------------------------
    // SQL Cargos y Abonos
    //-----------------------------------------------------


  $sql="  SELECT vencimiento, (TasaNominal/100)
          FROM fact_cliente
          WHERE id_factura='".$this->idfactura."' ";


   $rs = $this->db->Execute($sql);
   $this->tipovencimiento =  $rs->fields[0];
   $this->tasa_eq_ssi     =  $rs->fields[1];



    $sql = "    SELECT  Moratorio_Base_Capital,
                        Moratorio_Base_Interes,
                        Moratorio_Base_Comision,
                        Moratorio_Base_Moratorio,
                        Moratorio_Base_Otros,
                        MoratorioMinimo

                FROM fact_cliente

                WHERE num_compra= '".$this->numcompra."' ";

 $rs=$this->db->Execute($sql);


$this->moratorio_base_capital       =($rs->fields[0]=='Si')?(true):(false);
$this->moratorio_base_interes       =($rs->fields[1]=='Si')?(true):(false);
$this->moratorio_base_comision      =($rs->fields[2]=='Si')?(true):(false);
$this->moratorio_base_moratorio     = 0;
$this->moratorio_base_otros         =($rs->fields[4]=='Si')?(true):(false);

$this->moratorio_minimo    = $rs->fields['MoratorioMinimo'];


// Corrección EGC:2012-02-15 
// El moratorio mínimo no debe nunca ser igual a cero.

if(NUCLEO == 'rsinaloa_v45')
{
	if($this->fechaapertura >= '2012-07-01')
	{
	    $this->moratorio_minimo = 1;
	}
}
else
{
	if($this->moratorio_minimo <= 0)
	{
		 $this->moratorio_minimo = 1;
	}
}




if($this->is_vencimiento_anticipado)
    $sql = "SELECT COUNT(*) FROM cargos       WHERE num_compra = '".$this->numcompra."' AND  cargos.ID_Concepto =-3    and  cargos.Activo='Si' ";
else
    $sql = "SELECT MAX(ID_Cargo) FROM cargos  WHERE num_compra = '".$this->numcompra."' AND  cargos.ID_Concepto = -3   and  cargos.Activo='Si' ";



    $rs = $this->db->Execute($sql);

    $LastCargo = $rs->fields[0];
    
    $this->id_cuota_final = $rs->fields[0];
    //debug("[".$LastCargo."]");

//========================================================================================================================================================================

 $sql = "SELECT   MIN( cargos.Fecha_vencimiento)
         FROM     cargos
         WHERE    num_compra = '".$this->numcompra."'  and Activo='Si' ";
 $rs=$this->db->Execute($sql);

$primer_cargo=$rs->fields[0];
//--------------------------------------------------------------------
// Cargos y Abonos
//--------------------------------------------------------------------

        $this->SaldoCapitalPorVencer    = $this->_capital;
        $this->SaldoInteresPorVencer    = $this->interes;
        $this->SaldoComisionPorVencer   = $this->_comision_por_apertura;

        $this->SaldoSegVPorVencer      = $this->_segv;
        $this->SaldoSegDPorVencer      = $this->_segd;
        $this->SaldoSegBPorVencer      = $this->_segb;


//debug("V=[".$this->SaldoSegVPorVencer."] D=[". $this->SaldoSegDPorVencer."] B=[".$this->SaldoSegBPorVencer."] ");



        $this->SaldoGlobalCapital       = $this->_capital;
        $this->SaldoGlobalInteres       = $this->interes;
        $this->SaldoGlobalComision      = $this->_comision_por_apertura;
        
        
        $this->SaldoGlobalSegV      = $this->_segv;
        $this->SaldoGlobalSegD      = $this->_segd;
        $this->SaldoGlobalSegB      = $this->_segb;
        
        
        
        
        
        
        
        
/*
        $this->SaldoGlobalIVA           = (($this->interes * $this->iva_pcnt_intereses) + ($this->_comision_por_apertura *$this->iva_pcnt_comisiones));
*/

   $sql = " SELECT 
                SUM((cargos.IVA - cargos.AntiIVA)) AS IVA,
                SUM(cargos.IVA_Interes)  AS IVA_Interes,
                SUM(cargos.IVA_Comision) AS IVA_Comision,


                SUM(cargos.IVA_SegV) AS IVA_SegV,
                SUM(cargos.IVA_SegD) AS IVA_SegD,
                SUM(cargos.IVA_SegB) AS IVA_SegB



                FROM cargos, fact_cliente 

                WHERE   cargos.ID_Concepto = -3 and
                        cargos.Activo           = 'Si'  and
                        cargos.Num_compra       = fact_cliente.Num_compra and
                        fact_cliente.id_factura =   '".$idfactura."' ";

    $rs =  $this->db->Execute($sql);

 
 
    $this->Saldo_IVA_InteresPorVencer    = $rs->fields['IVA_Interes'];
    $this->Saldo_IVA_ComisionPorVencer   = $rs->fields['IVA_Comision'];


    $this->Saldo_IVA_SegVPorVencer   = $rs->fields['IVA_SegV'];
    $this->Saldo_IVA_SegDPorVencer   = $rs->fields['IVA_SegD'];
    $this->Saldo_IVA_SegBPorVencer   = $rs->fields['IVA_SegB'];

    //$this->SaldoGlobalIVA  = $rs->fields['IVA'];
    
    
    $this->SaldoGlobalIVA  =  $this->Saldo_IVA_InteresPorVencer   +
                              $this->Saldo_IVA_ComisionPorVencer  +

                              $this->Saldo_IVA_SegVPorVencer     +
                              $this->Saldo_IVA_SegDPorVencer     +
                              $this->Saldo_IVA_SegBPorVencer ;
                              
 
 //debug("iva V=[".$this->Saldo_IVA_SegVPorVencer."] iva D=[". $this->Saldo_IVA_SegDPorVencer."] iva  B=[".$this->Saldo_IVA_SegBPorVencer."] ");

 
 
 
                              

        $this->SaldoGlobalGeneral =$this->SaldoGlobalCapital  +
                                   $this->SaldoGlobalInteres  +
                                   $this->SaldoGlobalComision +

                                   
                                   $this->SaldoGlobalSegV     +                                
                                   $this->SaldoGlobalSegD     +                                
                                   $this->SaldoGlobalSegB     +                                
                              
                                   $this->SaldoGlobalIVA      ;                                   







        $this->SaldoGeneralVencido=0;

        $this->SaldoIVA         = 0;
        $this->SaldoIM          = 0;
        $this->SaldoComision    = 0;
        $this->SaldoInteres     = 0;
        $this->SaldoCapital     = 0;
        $this->SaldoOtros       = 0;


        $this->SaldoSegV   = 0;
        $this->SaldoSegD   = 0;
        $this->SaldoSegB   = 0;





        $this->SumaCapital      = 0;
        $this->SumaComision     = 0;
        $this->SumaInteres      = 0;
        $this->SumaIVA          = 0;
        $this->SumaIM           = 0;
        $this->SumaOtros        = 0;

        $this->SumaSegV   = 0;
        $this->SumaSegD   = 0;
        $this->SumaSegB   = 0;



        $this->SumaIVAComision  = 0;
        $this->SumaIVAInteres   = 0;
        $this->SumaIVAMoratorio = 0;

        $this->SumaIVAOtros     = 0;

        $this->SumaAbonos       = 0;




        $id = 0;

        $SALDO_Comision  =0;
        $SALDO_Capital   =0;
        $SALDO_Interes   =0;
        $SALDO_IVA       =0;
        $SALDO_Moratorio =0;
        $SALDO_GENERAL   =0;


        $SALDO_SegV   = 0;
        $SALDO_SegD   = 0;
        $SALDO_SegB   = 0;



        $SALDO_MOV_Comision  =0;
        $SALDO_MOV_Capital   =0;
        $SALDO_MOV_Interes   =0;
        $SALDO_MOV_IVA       =0;
        $SALDO_MOV_Moratorio =0;
        $SALDO_MOV_GENERAL   =0;

        
        

        $this->aplicacion[0]['SaldoCapitalPorVencer']           = $this->_capital;
        $this->aplicacion[0]['SaldoInteresPorVencer']           = $this->interes;
        $this->aplicacion[0]['SaldoComisionPorVencer']          = $this->_comision_por_apertura;
        
        $this->aplicacion[0]['SaldoSegVPorVencer']              = $this->_segv;
        $this->aplicacion[0]['SaldoSegDPorVencer']              = $this->_segd;
        $this->aplicacion[0]['SaldoSegBPorVencer']              = $this->_segb;
        
        
        


        $this->aplicacion[0]['SaldoInteres_IVA_PorVencer' ]      = $this->Saldo_IVA_InteresPorVencer;
        $this->aplicacion[0]['SaldoComision_IVA_PorVencer']      = $this->Saldo_IVA_ComisionPorVencer;

        $this->aplicacion[0]['SaldoSegV_IVA_PorVencer']          = $this->Saldo_IVA_SegVPorVencer;
        $this->aplicacion[0]['SaldoSegD_IVA_PorVencer']          = $this->Saldo_IVA_SegDPorVencer;
        $this->aplicacion[0]['SaldoSegB_IVA_PorVencer']          = $this->Saldo_IVA_SegBPorVencer;





        
        $this->aplicacion[0]['SaldoGlobalCapital']      = $this->_capital;              
        $this->aplicacion[0]['SaldoGlobalInteres']      = $this->interes;               
        $this->aplicacion[0]['SaldoGlobalComision']     = $this->_comision_por_apertura;  
        $this->aplicacion[0]['SaldoGlobalMoratorio']    = 0;
        $this->aplicacion[0]['SaldoGlobalOtros']        = 0;


        $this->aplicacion[0]['SaldoGlobalSegV']     = $this->_segv;
        $this->aplicacion[0]['SaldoGlobalSegD']     = $this->_segd;
        $this->aplicacion[0]['SaldoGlobalSegB']     = $this->_segb;


        $this->aplicacion[0]['SaldoGlobalIVA']      =  $this->Saldo_IVA_InteresPorVencer      +
                                                       $this->Saldo_IVA_ComisionPorVencer     +        
                                                       $this->Saldo_IVA_SegVPorVencer        +
                                                       $this->Saldo_IVA_SegDPorVencer        +
                                                       $this->Saldo_IVA_SegBPorVencer        ;


$id = 0;
    
//-------------------------------------------------------------------------------------------------------------
// Pagos anticipados
//-------------------------------------------------------------------------------------------------------------
//      

 $sqlAbonos = "
         SELECT  'Abono'                     AS Tipo,
             IF(conceptos.forma IS NULL,'!',conceptos.forma) AS Concepto,
             conceptos.Descripcion           AS Descripcion,             
             pagos.fecha                     AS Fecha,
             pagos.Monto * (-1)              AS Monto,
             pagos.SubTipo                   AS SubTipo,
             pagos.Aplicacion                AS Aplicacion,
             pagos.Id_pago                   AS ID,
             pagos.Forma                     AS Via,
             pagos.ID_Concepto               AS ID_Concepto,
             '0.0'                           AS Comision,
             '0.0'                           AS Capital,
             '0.0'                           AS Interes,
             '0.0'                           AS Otros,
             '0.0'                           AS IVA

         FROM pagos
         LEFT JOIN conceptos ON pagos.id_concepto = conceptos.id_concepto

         WHERE num_compra = '".$this->numcompra."'           and
               Fecha     < '".$this->primer_vencimiento."'   and 
               Fecha     <= '".$this->fecha_corte."'         and 
               conceptos.Forma = 'Efectivo'  and 
               pagos.Activo    = 'S'   ";
               
                if(count($this->abonos_desde))
                {
                        $lista = implode("','",$this->abonos_desde);
                        $sqlAbonos .= " and pagos.Id_pago    NOT IN  ('".$lista."')  ";
                
                }
               
               
               
 $sqlAbonos .= "  ORDER BY pagos.fecha, pagos.ID_Pago";

  $rs=$this->db->Execute($sqlAbonos);

        if($rs)
           while(! $rs->EOF)
           {
        

                $this->aplicacion[$id]['Tipo']          = $rs->fields['Tipo'];
                $this->aplicacion[$id]['SubTipo']       = $rs_parciales->fields['SubTipo'];
                $this->aplicacion[$id]['Fecha']         = $rs->fields['Fecha'];
                $this->aplicacion[$id]['Fecha_Mov']     = $rs->fields['Fecha'];
                $this->aplicacion[$id]['Concepto']      = $rs->fields['Concepto'];
                $this->aplicacion[$id]['Descripcion']   = $rs->fields['Descripcion']." con aplicación anticipada ";

                $this->aplicacion[$id]['ID']            = $rs->fields['ID'];
                $this->aplicacion[$id]['ID_Concepto']   = $rs->fields['ID_Concepto'];

                $this->aplicacion[$id]['Monto']         = $rs->fields['Monto'];         
                $this->aplicacion[$id]['Abono']         = $this->aplicacion[$id]['Monto'];
                        
                $this->SumaAbonos       += $this->aplicacion[$id]['Abono'] ;

                $this->aplicacion[$id]['SALDO_General'] = $this->aplicacion[($id-1)]['SALDO_MOV_General'] + $this->aplicacion[$id]['Abono']; // $this->aplicacion[($id-1)]['SALDO_General'] + $this->aplicacion[$id]['Abono'];
                 
                 
                 
                 
                 
                 
                 if($this->aplicacion[$id]['SALDO_General']<0)
                 {              
                            $this->aplicacion[$id]['SALDO_MOV_Pendiente_Aplicar'] = $this->aplicacion[$id]['SALDO_General'];//$this->aplicacion[($id-1)]['SALDO_MOV_Pendiente_Aplicar'] + $this->aplicacion[$id]['Abono'];      
                        
                            $this->aplicacion[$id]['SALDO_General'] = 0 ;
                 }
                 $this->SaldoFavorPendiente = $this->aplicacion[$id]['SALDO_MOV_Pendiente_Aplicar'];
                 
                $this->abonos_desde[]= $rs->fields['ID'];

                $this->fecha_ultimo_abono = $this->aplicacion[$id]['Fecha'];
                $this->id_ultimo_abono = $id;
                $this->ultimo_abono_ID    = $this->aplicacion[$id]['ID'];

                if($id)
                {

                        $this->aplicacion[$id]['SaldoCapitalPorVencer']  = $this->aplicacion[($id-1)]['SaldoCapitalPorVencer'];  //    
                        $this->aplicacion[$id]['SaldoInteresPorVencer']  = $this->aplicacion[($id-1)]['SaldoInteresPorVencer'];   //   
                        
                        
                        
                        
                        
                        
                        
                        $this->aplicacion[$id]['SaldoInteres_IVA_PorVencer' ] = $this->aplicacion[($id-1)]['SaldoInteres_IVA_PorVencer' ];                  
                        
                        if($this->aplicacion[$id]['SaldoCapitalPorVencer']<=0)
                        {
                           $this->aplicacion[$id]['SaldoInteresPorVencer'] = 0;
                           $this->aplicacion[$id]['SaldoInteres_IVA_PorVencer' ] = 0;
                        
                        }
                        
                        
                        
                        $this->aplicacion[$id]['SaldoComisionPorVencer']      = $this->aplicacion[($id-1)]['SaldoComisionPorVencer'];  //   
                        $this->aplicacion[$id]['SaldoComision_IVA_PorVencer'] = $this->aplicacion[($id-1)]['SaldoComision_IVA_PorVencer'];                  
                

                        $this->aplicacion[$id]['SaldoSegVPorVencer']          = $this->aplicacion[($id-1)]['SaldoSegVPorVencer'];     
                        $this->aplicacion[$id]['SaldoSegV_IVA_PorVencer']     = $this->aplicacion[($id-1)]['SaldoSegV_IVA_PorVencer'];      


                        $this->aplicacion[$id]['SaldoSegDPorVencer']          = $this->aplicacion[($id-1)]['SaldoSegDPorVencer'];     
                        $this->aplicacion[$id]['SaldoSegD_IVA_PorVencer']     = $this->aplicacion[($id-1)]['SaldoSegD_IVA_PorVencer'];     


                        $this->aplicacion[$id]['SaldoSegBPorVencer']          = $this->aplicacion[($id-1)]['SaldoSegBPorVencer'];  
                        $this->aplicacion[$id]['SaldoSegB_IVA_PorVencer']     = $this->aplicacion[($id-1)]['SaldoSegB_IVA_PorVencer'];     


 


                }

                $this->desglosar_movimientos($id,0);

                ++$id;

                $rs->MoveNext();





           }
             



//-------------------------------------------------------------------------------------------------------------
// Cuotas Pactadas
//-------------------------------------------------------------------------------------------------------------

 $SALDO_PARCIAL = false;


 $sql = "        SELECT  'Cargo'                                                            AS Tipo,
                     Concepto                                                               AS Concepto,
                     cargos.Fecha_vencimiento                                               AS Fecha,
                     (cargos.Monto  -  cargos.AntiMonto )                                   AS Monto,
                     cargos.SubTipo                                                         AS SubTipo,
                     NULL                                                                   AS Aplicacion,
                     
                     IF( (Concepto LIKE 'Vencimiento anticipado de la cuota%'), 
                                (SELECT COUNT(*) FROM cargos WHERE num_compra = '".$this->numcompra."' and cargos.Fecha_vencimiento <= '".$this->fecha_corte."' and cargos.ID_Concepto=-3 and cargos.Activo='Si' ),
                                cargos.ID_Cargo)                                            AS ID,  

                     NULL                                                                   AS Via,
                     cargos.ID_Concepto                                                     AS ID_Concepto,
                     (cargos.Comision        -  cargos.AntiComision   )                     AS Comision,
                     (cargos.Moratorio       -  cargos.AntiMoratorio  )                     AS Moratorios,                   
                     (cargos.Capital         -  cargos.AntiCapital    )                     AS Capital,
                     (cargos.Interes         -  cargos.AntiInteres    )                     AS Interes,
                     (cargos.Otros           -  cargos.AntiOtros      )                     AS Otros,
                     (cargos.IVA             -  cargos.AntiIVA        )                     AS IVA,

                     (cargos.SegV                                     )                     AS SegV,
                     (cargos.SegD                                     )                     AS SegD,
                     (cargos.SegB                                     )                     AS SegB,




                      cargos.IVA_Interes,  
                      cargos.IVA_Comision,
                      cargos.IVA_Moratorio,
                      cargos.IVA_Otros,


                      cargos.IVA_SegV,
                      cargos.IVA_SegD,
                      cargos.IVA_SegB 


                 FROM cargos
                 
                 WHERE cargos.num_compra = '".$this->numcompra."' and
                       cargos.Fecha_vencimiento <= '".$this->fecha_corte."'  and
                       cargos.ID_Concepto = -3  and cargos.Activo='Si' 
                 ORDER BY Fecha ";






       $rs=$this->db->Execute($sql);
    
    
    
        $primer_cargo=true;
        if($rs)
           while(! $rs->EOF)
           {

                $faplicacion ="";
                
                $faplicacion = $this->fechas_inhabiles[($rs->fields['Fecha'])];
                
                
                if($faplicacion > $this->fecha_corte)
                {               
                        break;
                }


                list($_yf,$_mf, $_df)= explode("-",$rs->fields['Fecha']);
                if(date("w",mktime(0,0,0,$_mf,$_df,$_yf)) == 0)
                {
                        $faplicacion  = date("Y-m-d",mktime(0,0,0,$_mf,($_df+1),$_yf));         

                        if($faplicacion > $this->fecha_corte)
                        {               
                                break;
                        }
                }





                $this->aplicacion[$id]['Tipo']          = $rs->fields['Tipo'];
                $this->aplicacion[$id]['Fecha_Mov']     = $rs->fields['Fecha'];
                $this->aplicacion[$id]['Fecha']         = $rs->fields['Fecha'];
                


                if($faplicacion = $this->fechas_inhabiles[($this->aplicacion[$id]['Fecha'])])
                {               
                      $this->aplicacion[$id]['Fecha'] = $faplicacion;
                }
                
                list($_yf,$_mf, $_df)= explode("-",$this->aplicacion[$id]['Fecha']);

                if(date("w",mktime(0,0,0,$_mf,$_df,$_yf)) == 0)
                {
                  $this->aplicacion[$id]['Fecha'] = date("Y-m-d",mktime(0,0,0,$_mf,($_df+1),$_yf));             
                }
                

                $FechaCuota                             = $this->aplicacion[$id]['Fecha'];
                $this->FechaCuota                       = $this->aplicacion[$id]['Fecha'];
                
                
                $this->aplicacion[$id]['Concepto']      = $rs->fields['Concepto'];
                $this->aplicacion[$id]['Descripcion']   = $rs->fields['Concepto'];

                $this->aplicacion[$id]['ID']            = $rs->fields['ID'];
                $ID_Cuota                               = $rs->fields['ID'];
                $this->id_ultima_cuota                  = $rs->fields['ID'];
                $this->id_aplicacion_ultima_cuota       = $id;
                
                $this->aplicacion[$id]['ID_Concepto']   = $rs->fields['ID_Concepto'];

                $this->aplicacion[$id]['Monto']         = $rs->fields['Monto'];     
                
                
                $this->renta = $rs->fields['Monto'];     
                
                
                $this->aplicacion[$id]['Cargo']         = $this->aplicacion[$id]['Monto'];
                        

                $this->aplicacion[$id]['CARGO_Capital']         = $rs->fields['Capital'];       
                
                $this->aplicacion[$id]['CARGO_Interes' ]        = $rs->fields['Interes' ];
                $this->aplicacion[$id]['CARGO_IVA_Interes']     = $rs->fields['IVA_Interes' ];
                
                
                $this->aplicacion[$id]['CARGO_Comision']        = ($rs->fields['Comision']>0)?($rs->fields['Comision']):(0);    
                $this->aplicacion[$id]['CARGO_IVA_Comision']    =  $rs->fields['IVA_Comision' ];               
                
                
                $this->aplicacion[$id]['CARGO_Otros']           = $rs->fields['Otros'];         
                $this->aplicacion[$id]['CARGO_IVA_Otros']       = $rs->fields['IVA_Otros' ];   


                $this->aplicacion[$id]['CARGO_Moratorio']       = $rs->fields['Moratorios'];                                                                                                  
                $this->aplicacion[$id]['CARGO_IVA_Moratorio']   = $rs->fields['IVA_Moratorio'];  



                $this->aplicacion[$id]['CARGO_SegV']            = $rs->fields['SegV'];                                                                                                  
                $this->aplicacion[$id]['CARGO_IVA_SegV']        = $rs->fields['IVA_SegV'];  

                $this->aplicacion[$id]['CARGO_SegD']            = $rs->fields['SegD'];                                                                                                  
                $this->aplicacion[$id]['CARGO_IVA_SegD']        = $rs->fields['IVA_SegD'];  

                $this->aplicacion[$id]['CARGO_SegB']            = $rs->fields['SegB'];                                                                                                  
                $this->aplicacion[$id]['CARGO_IVA_SegB']        = $rs->fields['IVA_SegB'];  






                $this->aplicacion[$id]['CARGO_IVA']             = $rs->fields['IVA'];           

                
                $this->SumaCapital              += $this->aplicacion[$id]['CARGO_Capital'];     
                $this->SumaInteres              += $this->aplicacion[$id]['CARGO_Interes' ];
                $this->SumaComision             += $this->aplicacion[$id]['CARGO_Comision'];
                $this->SumaOtros                += $this->aplicacion[$id]['CARGO_Otros'];                       

                $this->SumaSegV                 += $this->aplicacion[$id]['CARGO_SegV'];
                $this->SumaSegD                 += $this->aplicacion[$id]['CARGO_SegD'];
                $this->SumaSegB                 += $this->aplicacion[$id]['CARGO_SegB'];




                $this->SumaIVAInteres           += $this->aplicacion[$id]['CARGO_IVA_Interes'];
                $this->SumaIVAComision          += $this->aplicacion[$id]['CARGO_IVA_Comision'];
                $this->SumaIVAOtros             += $this->aplicacion[$id]['CARGO_IVA_Otros'];
                
                
                $this->SumaIVASegV              += $this->aplicacion[$id]['CARGO_IVA_SegV'];
                $this->SumaIVASegD              += $this->aplicacion[$id]['CARGO_IVA_SegD'];
                $this->SumaIVASegB              += $this->aplicacion[$id]['CARGO_IVA_SegB'];
                
                
                
                $this->aplicacion[$id]['DiasAtraso'] = 0;
                $this->aplicacion[$id]['DiasAtrasoAcum']= 0;
                
                
                
                
                $this->SumaIVA  =   $this->SumaIVAInteres +      
                                    $this->SumaIVAComision+
                                    $this->SumaIVAOtros   +
                                    
                                    $this->SumaIVASegV    +                                 
                                    $this->SumaIVASegD    +                                 
                                    $this->SumaIVASegB;                                
                                    
                                    




                if($id==0)
                {
/**/
                        $this->aplicacion[$id]['SaldoCapitalPorVencer']  = $this->_capital                      -$this->SumaCapital;  // 
                        $this->aplicacion[$id]['SaldoCapitalPorVencer']  =($this->aplicacion[$id]['SaldoCapitalPorVencer']<0)?(0):($this->aplicacion[$id]['SaldoCapitalPorVencer']);

                        $this->aplicacion[$id]['SaldoInteresPorVencer']  = $this->interes                       -$this->SumaInteres;   //  
                        $this->aplicacion[$id]['SaldoInteresPorVencer']  = ($this->aplicacion[$id]['SaldoInteresPorVencer']<0)?(0):($this->aplicacion[$id]['SaldoInteresPorVencer']);

                        
                        //debug(" [$id] Saldo_IVA_InteresPorVencer : ".$this->iva_interes  ."   - ".$this->SumaIVAInteres );

                        
                        $this->aplicacion[$id]['SaldoInteres_IVA_PorVencer']  = $this->iva_interes              -$this->SumaIVAInteres;   //  
                        $this->aplicacion[$id]['SaldoInteres_IVA_PorVencer']  = ($this->aplicacion[$id]['SaldoInteres_IVA_PorVencer']<0)?(0):($this->aplicacion[$id]['SaldoInteres_IVA_PorVencer']);


                        if($this->aplicacion[$id]['SaldoCapitalPorVencer']<=0)
                        {
                           $this->aplicacion[$id]['SaldoInteresPorVencer']      = 0;
                           $this->aplicacion[$id]['SaldoInteres_IVA_PorVencer'] = 0;

                        }

                        $this->aplicacion[$id]['SaldoComisionPorVencer']  = $this->_comision_por_apertura                -$this->SumaComision;  //   
                        $this->aplicacion[$id]['SaldoComisionPorVencer']  = ($this->aplicacion[$id]['SaldoComisionPorVencer']<0)?(0):($this->aplicacion[$id]['SaldoComisionPorVencer']);

                        $this->aplicacion[$id]['SaldoComision_IVA_PorVencer']  = $this->iva_comision_por_apertura                -$this->SumaIVAComision;  //   
                        $this->aplicacion[$id]['SaldoComision_IVA_PorVencer']  = ($this->aplicacion[$id]['SaldoComision_IVA_PorVencer']<0)?(0):($this->aplicacion[$id]['SaldoComision_IVA_PorVencer']);





                        
                        $this->aplicacion[$id]['SaldoSegVPorVencer']  = $this->_segv -  $this->SumaSegV; // 
                        $this->aplicacion[$id]['SaldoSegVPorVencer']  = ($this->aplicacion[$id]['SaldoSegVPorVencer']<0)?(0):($this->aplicacion[$id]['SaldoSegVPorVencer']);
                        
                        $this->aplicacion[$id]['SaldoSegDPorVencer']  = $this->_segd -  $this->SumaSegD; //
                        $this->aplicacion[$id]['SaldoSegDPorVencer']  = ($this->aplicacion[$id]['SaldoSegDPorVencer']<0)?(0):($this->aplicacion[$id]['SaldoSegDPorVencer']);
                        
                        $this->aplicacion[$id]['SaldoSegBPorVencer']  = $this->_segb -  $this->SumaSegB; //
                        $this->aplicacion[$id]['SaldoSegBPorVencer']  = ($this->aplicacion[$id]['SaldoSegBPorVencer']<0)?(0):($this->aplicacion[$id]['SaldoSegBPorVencer']);
                        


                        $this->aplicacion[$id]['SaldoSegV_IVA_PorVencer']  = $this->iva_segv -  $this->SumaIVASegV; // 
                        $this->aplicacion[$id]['SaldoSegV_IVA_PorVencer']  = ($this->aplicacion[$id]['SaldoSegV_IVA_PorVencer']<0)?(0):($this->aplicacion[$id]['SaldoSegV_IVA_PorVencer']);
                        
                        $this->aplicacion[$id]['SaldoSegD_IVA_PorVencer']  = $this->iva_segd -  $this->SumaIVASegD ; //
                        $this->aplicacion[$id]['SaldoSegD_IVA_PorVencer']  = ($this->aplicacion[$id]['SaldoSegD_IVA_PorVencer']<0)?(0):($this->aplicacion[$id]['SaldoSegD_IVA_PorVencer']);
                        
                        $this->aplicacion[$id]['SaldoSegB_IVA_PorVencer']  = $this->iva_segb -  $this->SumaIVASegB; //
                        $this->aplicacion[$id]['SaldoSegB_IVA_PorVencer']  = ($this->aplicacion[$id]['SaldoSegB_IVA_PorVencer']<0)?(0):($this->aplicacion[$id]['SaldoSegB_IVA_PorVencer']);



                  //    debug("($id)_segv ".($this->_segv)." iva ". $this->iva_segv);
                  //    debug("($id)_segv ".($this->_segd)." iva ". $this->iva_segd);
                  //    debug("($id)_segv ".($this->_segb)." iva ". $this->iva_segv);




                }
                else
                {




                        if($this->aplicacion[$id]['CARGO_Capital']>=0)
                                $this->aplicacion[$id]['SaldoCapitalPorVencer']  = ( $this->aplicacion[($id-1)]['SaldoCapitalPorVencer'] - $this->aplicacion[$id]['CARGO_Capital']);


        
                        if($this->aplicacion[$id]['CARGO_Interes' ]>=0)
                                $this->aplicacion[$id]['SaldoInteresPorVencer']  = ($this->aplicacion[($id-1)]['SaldoInteresPorVencer'] - $this->aplicacion[$id]['CARGO_Interes' ]);

                        if($this->aplicacion[$id]['CARGO_IVA_Interes' ]>=0)
                                $this->aplicacion[$id]['SaldoInteres_IVA_PorVencer']  = ($this->aplicacion[($id-1)]['SaldoInteres_IVA_PorVencer'] - $this->aplicacion[$id]['CARGO_IVA_Interes' ]);



                        if($this->aplicacion[$id]['CARGO_Comision']>=0)
                                $this->aplicacion[$id]['SaldoComisionPorVencer'] = ($this->aplicacion[($id-1)]['SaldoComisionPorVencer'] - $this->aplicacion[$id]['CARGO_Comision']);

                        if($this->aplicacion[$id]['CARGO_IVA_Comision']>=0)
                                $this->aplicacion[$id]['SaldoComision_IVA_PorVencer'] = ($this->aplicacion[($id-1)]['SaldoComision_IVA_PorVencer'] - $this->aplicacion[$id]['CARGO_IVA_Comision']);


 
 
                        if($this->aplicacion[$id]['CARGO_SegV']>=0)
                         $this->aplicacion[$id]['SaldoSegVPorVencer'      ]  = ( $this->aplicacion[($id-1)]['SaldoSegVPorVencer']       - $this->aplicacion[$id]['CARGO_SegV']);


                        if($this->aplicacion[$id]['CARGO_IVA_SegV']>=0)
                         $this->aplicacion[$id]['SaldoSegV_IVA_PorVencer']  = ( $this->aplicacion[($id-1)]['SaldoSegV_IVA_PorVencer'] - $this->aplicacion[$id]['CARGO_IVA_SegV']);
                         
                         

                         
                       if($this->aplicacion[$id]['CARGO_SegD']>=0)
                         $this->aplicacion[$id]['SaldoSegDPorVencer'      ]  = ( $this->aplicacion[($id-1)]['SaldoSegDPorVencer']       - $this->aplicacion[$id]['CARGO_SegD']);


                        if($this->aplicacion[$id]['CARGO_IVA_SegD']>=0)
                         $this->aplicacion[$id]['SaldoSegD_IVA_PorVencer']  = ( $this->aplicacion[($id-1)]['SaldoSegD_IVA_PorVencer'] - $this->aplicacion[$id]['CARGO_IVA_SegD']);
                         



                       if($this->aplicacion[$id]['CARGO_SegB']>=0)
                         $this->aplicacion[$id]['SaldoSegBPorVencer'      ]  = ( $this->aplicacion[($id-1)]['SaldoSegBPorVencer']       - $this->aplicacion[$id]['CARGO_SegB']);


                        if($this->aplicacion[$id]['CARGO_IVA_SegB']>=0)
                         $this->aplicacion[$id]['SaldoSegB_IVA_PorVencer']  = ( $this->aplicacion[($id-1)]['SaldoSegB_IVA_PorVencer'] - $this->aplicacion[$id]['CARGO_IVA_SegB']);
 
 
 
 

                        if($this->aplicacion[$id]['SaldoCapitalPorVencer']<=0)
                                $this->aplicacion[$id]['SaldoCapitalPorVencer'] = 0;

                        if($this->aplicacion[$id]['SaldoComisionPorVencer']<=0)
                        {
                                $this->aplicacion[$id]['SaldoComisionPorVencer']      = 0;
                                $this->aplicacion[$id]['SaldoComision_IVA_PorVencer'] = 0;
                        
                        
                        }

                        if($this->aplicacion[$id]['SaldoInteresPorVencer']<=0)
                        {
                                $this->aplicacion[$id]['SaldoInteresPorVencer']      = 0;
                                $this->aplicacion[$id]['SaldoInteres_IVA_PorVencer'] = 0;

                        }


                        if($this->aplicacion[$id]['SaldoCapitalPorVencer']<=0)
                        {
                                $this->aplicacion[$id]['SaldoInteresPorVencer'] = 0;
                        }



                        if($this->aplicacion[$id]['SaldoSegVPorVencer']<=0)
                        {
                                $this->aplicacion[$id]['SaldoSegVPorVencer']      = 0;
                                $this->aplicacion[$id]['SaldoSegV_IVA_PorVencer'] = 0;

                        }

                        if($this->aplicacion[$id]['SaldoSegDPorVencer']<=0)
                        {
                                $this->aplicacion[$id]['SaldoSegDPorVencer']      = 0;
                                $this->aplicacion[$id]['SaldoSegD_IVA_PorVencer'] = 0;

                        }

                        if($this->aplicacion[$id]['SaldoSegBPorVencer']<=0)
                        {
                                $this->aplicacion[$id]['SaldoSegBPorVencer']      = 0;
                                $this->aplicacion[$id]['SaldoSegB_IVA_PorVencer'] = 0;

                        }










                }

                
                


                $this->SaldoCapitalPorVencer    = $this->aplicacion[$id]['SaldoCapitalPorVencer'] ;
                $this->SaldoInteresPorVencer    = $this->aplicacion[$id]['SaldoInteresPorVencer'] ; 
                $this->SaldoComisionPorVencer   = $this->aplicacion[$id]['SaldoComisionPorVencer'];

                $this->Saldo_IVA_InteresPorVencer  = $this->aplicacion[$id]['SaldoInteres_IVA_PorVencer' ] ;            
                $this->Saldo_IVA_ComisionPorVencer = $this->aplicacion[$id]['SaldoComision_IVA_PorVencer'];   
                
   
 
               $this->SaldoSegVPorVencer        = $this->aplicacion[$id]['SaldoSegVPorVencer'] ;
               $this->SaldoSegDPorVencer        = $this->aplicacion[$id]['SaldoSegDPorVencer'] ; 
               $this->SaldoSegBPorVencer        = $this->aplicacion[$id]['SaldoSegBPorVencer'];

               $this->Saldo_IVA_SegVPorVencer  = $this->aplicacion[$id]['SaldoSegV_IVA_PorVencer']; 
               $this->Saldo_IVA_SegDPorVencer  = $this->aplicacion[$id]['SaldoSegD_IVA_PorVencer'];  
               $this->Saldo_IVA_SegBPorVencer  = $this->aplicacion[$id]['SaldoSegB_IVA_PorVencer']; 
   
   
   
            

             //-----------------------------------------------------------------------------------------------------------------------------------------
             // Si existe saldo a favor por aplicar al saldo actual, verificaremos por la fecha del último abono si es que será necesario calcular moratorios, antes de aplicar el saldo
             //-----------------------------------------------------------------------------------------------------------------------------------------
            

                if($this->aplicacion[($id-1)]['SALDO_General']<0)
                {
                        if(abs($this->aplicacion[($id-1)]['SALDO_General'])<0.01)
                               $this->aplicacion[($id-1)]['SALDO_General'] = 0;
                   
                }       
                




              
                //-------------------------------------------------------------------------
                //   Nunca calculamos moratorios en las coutas
                //-------------------------------------------------------------------------

                $this->aplicacion[$id]['DiasAtraso'] = 0;

//
                //---------------------------------------------------------------
                // Reiniciamos con cada CUOTA el acumulado de días vencidos
                //---------------------------------------------------------------

                $this->aplicacion[$id]['DiasAtrasoAcum'] = $this->aplicacion[$id]['DiasAtraso'];


                $this->aplicacion[$id]['IMB'] = $this->calculo_interes_moratorio_variable($id,$ID_Cuota, "A"); 
                //-----------------------------------------------------------------------------------------------------------------------
                // EL Método calculo_interes_moratorio_variable actualiza éste valor desde adentro Lunes, 28 de Diciembre de 2009
                //-----------------------------------------------------------------------------------------------------------------------

                //$this->aplicacion[$id]['IM']  = $this->aplicacion[$id]['IMB']/(1+$this->aplicacion[$id]['IM_pIVA']);


// debug("Antes [$id]['IM'] = ".$this->aplicacion[$id]['IM']);

// debug("Después [$id]['IM'] = ".$this->aplicacion[$id]['IM']);                
                
                
                
                $this->aplicacion[$id]['CARGO_IVA']             +=($this->aplicacion[$id]['IMB'] - $this->aplicacion[$id]['IM']);
                $this->aplicacion[$id]['CARGO_IVA_Moratorio' ]  +=($this->aplicacion[$id]['IMB'] - $this->aplicacion[$id]['IM']);                                       


             
                $this->aplicacion[$id]['SALDO_General']       = $this->aplicacion[($id-1)]['SALDO_General'] + $this->aplicacion[$id]['Cargo'] + $this->aplicacion[$id]['IMB'];
                if($this->aplicacion[($id-1)]['SALDO_MOV_General'] < 0)
                {
                  $this->aplicacion[$id]['SALDO_General']       += $this->aplicacion[($id-1)]['SALDO_MOV_General'];
                }
                
                
                
                
                
                
                $this->aplicacion[$id]['SaldoParcial']        = $this->aplicacion[$id]['Cargo'] + $this->aplicacion[$id]['IMB'];


                if($this->aplicacion[($id-1)]['SALDO_MOV_General']<0)
                        $this->aplicacion[$id]['SaldoParcial'] += $this->aplicacion[($id-1)]['SALDO_MOV_General'];

                $this->aplicacion[$id]['SaldoParcial'] = ($this->aplicacion[$id]['SaldoParcial'] <0)?(0):($this->aplicacion[$id]['SaldoParcial']);









              
              $this->desglosar_movimientos($id,$ID_Cuota);


              ++$id;
              $rs->MoveNext();
              $primer_cargo=false;
     
//----------------------------------------------------------------------------------------------------------------------------------
// Fecha de la siguiente CUOTA
//----------------------------------------------------------------------------------------------------------------------------------
/*
//debug($this->numcompra);
                $sqlsig="       SELECT cargos.Fecha_vencimiento AS Fecha
                                FROM cargos
                                WHERE num_compra = '".$this->numcompra."'  and
                                                  cargos.ID_Concepto=-3     AND
                                                  cargos.Fecha_vencimiento > '".$this->aplicacion[($id-1)]['Fecha']."'  and 
                                                  cargos.Activo='Si'
                                ORDER BY          cargos.Fecha_vencimiento 
                                Limit 0,1";                     


        En el caso de los vencimientos anticipados, es posible tener 2 coutas en el mismo día: la del vencimiento anticipado y la anterior, por eso se cambió esta sentencia SQL 


*/

                $sqlsig="       SELECT cargos.Fecha_vencimiento AS Fecha
                                FROM   cargos
                                WHERE num_compra = '".$this->numcompra."'  and
                                                  cargos.ID_Concepto=-3     AND
                                                  cargos.ID_Cargo > ".$ID_Cuota ." AND
                                                  cargos.Activo='Si'
                                ORDER BY          cargos.Fecha_vencimiento 
                                Limit 0,1";                     





               $rs_sig =$this->db->Execute($sqlsig);


               if(($this->is_vencimiento_anticipado)  and ($this->id_cargo_reemplazo_vencimiento_anticipado == $ID_Cuota))
               {
                
                        $this->Fecha_Sig_Cuota = '2999-12-31';
               }
               else
               {
                        $this->Fecha_Sig_Cuota = $rs_sig->fields['Fecha'];
                        $this->Fecha_Sig_Cuota = (empty($this->Fecha_Sig_Cuota))?('2999-12-31'):($this->Fecha_Sig_Cuota);

                        if($_faplicacion = $this->fechas_inhabiles[($this->Fecha_Sig_Cuota)])
                        {               
                              $this->Fecha_Sig_Cuota = $_faplicacion;
                        }

                        list($_yf,$_mf, $_df)= explode("-",$this->Fecha_Sig_Cuota);

                        if(date("w",mktime(0,0,0,$_mf,$_df,$_yf)) == 0)
                        {
                          $this->Fecha_Sig_Cuota = date("Y-m-d",mktime(0,0,0,$_mf,($_df+1),$_yf));              
                        }
                }

//debug("CUOTA : ".$ID_Cuota." Fecha_Sig_Cuota : (".$rs_sig->fields['Fecha'].") [".$this->fecha_vencimiento_anticipado."]". $this->Fecha_Sig_Cuota);
//----------------------------------------------------------------------------------------------------------------------------------

            
             //-----------------------------------------------
             //Aplicación de abonos  y Cargos no generales           
             //-----------------------------------------------
             

                 $sql = "(       
                                SELECT  'Abono'                                                         AS Tipo,
                                     conceptos.Descripcion                                              AS Descripcion,
                                     'Efectivo'                                                         AS Concepto,
                                     pagos.fecha                                                        AS Fecha,
                                     pagos.Monto * (-1)                                                 AS Monto,
                                     pagos.SubTipo                                                      AS SubTipo,
                                     pagos.Aplicacion                                                   AS Aplicacion,
                                     pagos.Id_pago                                                      AS ID,
                                     pagos.Forma                                                        AS Via,
                                     pagos.ID_Concepto                                                  AS ID_Concepto,
                                     '0.0'                                                              AS Comision,
                                     '0.0'                                                              AS Capital,
                                     '0.0'                                                              AS Interes,
                                     '0.0'                                                              AS Otros,
                                     '0.0'                                                              AS Moratorios,


                                     '0.0'                                                              AS SegV,
                                     '0.0'                                                              AS SegD,
                                     '0.0'                                                              AS SegB,


                                     '0.0'                                                              AS IVA,


                                        0                                                               AS IVA_Interes,  
                                        0                                                               AS IVA_Comision,
                                        0                                                               AS IVA_Moratorio,
                                        0                                                               AS IVA_Otros,

                                        0                                                               AS IVA_SegV,
                                        0                                                               AS IVA_SegD,
                                        0                                                               AS IVA_SegB,




                                     
                                     2                                                                  AS ORD,
                                     2                                                                  AS ORD2


                                 FROM pagos
                                 LEFT JOIN conceptos ON pagos.id_concepto = conceptos.id_concepto

                                 WHERE num_compra        = '".$this->numcompra."'      and
                                       Fecha            <= '".$this->fecha_corte."'    and 
                                       Activo            = 'S'    and                                  
                                       conceptos.Forma = 'Efectivo'\n";
                                       
                                       
                if(count($this->abonos_desde))
                {
                        $lista = implode("','",$this->abonos_desde);
                        $sql .= " and pagos.Id_pago    NOT IN  ('".$lista."')  ";
                
                }
//                          IF(conceptos.Descripcion IS NULL,'!',conceptos.Descripcion)      AS Descripcion,               
              
                $sql .= "    ORDER BY pagos.Fecha, pagos.ID_Pago )                 
                        UNION                
                (  SELECT 'Abono'                                                                       AS Tipo,                        
                        notas_credito.Forma                                                             AS Descripcion,
                        'Documento'                                                                     AS Concepto,
                        notas_credito.fecha                                                             AS Fecha,
                        notas_credito.Monto * (-1)                                                      AS Monto,
                        notas_credito.SubTipo                                                           AS SubTipo,
                        notas_credito.Aplicacion                                                        AS Aplicacion,
                        notas_credito.ID_Nota                                                           AS ID,
                        notas_credito.Forma                                                             AS Via,
                        notas_credito.ID_Concepto                                                       AS ID_Concepto,
                        IF(notas_credito.SubTipo = 'Comision' ,notas_credito.Abono * (-1),  '0.0')      AS Comision,
                        IF(notas_credito.SubTipo = 'Capital'  ,notas_credito.Abono * (-1),  '0.0')      AS Capital,
                        IF(notas_credito.SubTipo = 'Interes'  ,notas_credito.Abono * (-1),  '0.0')      AS Interes,
                        IF(notas_credito.SubTipo = 'Otros'    ,notas_credito.Abono * (-1),  '0.0')      AS Otros,
                        IF(notas_credito.SubTipo = 'Moratorio',notas_credito.Abono * (-1),  '0.0')      AS Moratorios,
                        
                        IF(notas_credito.SubTipo = 'SegV'  ,notas_credito.Abono * (-1),  '0.0')         AS SegV,
                        IF(notas_credito.SubTipo = 'SegD'  ,notas_credito.Abono * (-1),  '0.0')         AS SegD,
                        IF(notas_credito.SubTipo = 'SegB'  ,notas_credito.Abono * (-1),  '0.0')         AS SegB,
                        
                        
                        
                        notas_credito.IVA                                                               AS IVA,

                        IF(notas_credito.SubTipo = 'Comision' ,notas_credito.IVA * (-1),  '0.0')        AS IVA_Comision,      
                        IF(notas_credito.SubTipo = 'Interes'  ,notas_credito.IVA * (-1),  '0.0')        AS IVA_Interes,  
                        IF(notas_credito.SubTipo = 'Otros'    ,notas_credito.IVA * (-1),  '0.0')        AS IVA_Otros,
                        IF(notas_credito.SubTipo = 'Moratorio',notas_credito.IVA * (-1),  '0.0')        AS IVA_Moratorio,

                        IF(notas_credito.SubTipo = 'SegV'     ,notas_credito.IVA * (-1),  '0.0')        AS IVA_SegV,
                        IF(notas_credito.SubTipo = 'SegD'     ,notas_credito.IVA * (-1),  '0.0')        AS IVA_SegD,
                        IF(notas_credito.SubTipo = 'SegB'     ,notas_credito.IVA * (-1),  '0.0')        AS IVA_SegB,




                        1                                                                               AS ORD,
                        1                                                                               AS ORD2





                        FROM notas_credito
                        LEFT JOIN conceptos ON notas_credito.id_concepto = conceptos.id_concepto

                        WHERE   num_compra       = '".$this->numcompra."'               and
                                Fecha           <= '".$this->fecha_corte."'     and                     
                                ID_Cargo         = '".$ID_Cuota."'      )       
                
                
                        UNION

                        (       SELECT              'Cargo'                                                                     AS Tipo,
                                                     Concepto                                                                   AS Descripcion,
                                                     Concepto                                                                   AS Concepto,
                                                     cargos.Fecha_vencimiento                                                   AS Fecha,
                                                    (cargos.Monto  -  cargos.AntiMonto )                                        AS Monto,
                                                     cargos.SubTipo                                                             AS SubTipo,
                                                     NULL                                                                       AS Aplicacion,
                                                     cargos.ID_Cargo                                                            AS ID,
                                                     NULL                                                                       AS Via,
                                                     cargos.ID_Concepto                                                         AS ID_Concepto,
                                                     (cargos.Comision        -  cargos.AntiComision   )                         AS Comision,
                                                     (cargos.Capital         -  cargos.AntiCapital    )                         AS Capital,
                                                     (cargos.Interes         -  cargos.AntiInteres    )                         AS Interes,
                                                     (cargos.Otros           -  cargos.AntiOtros      )                         AS Otros,
                                                     (cargos.Moratorio       -  cargos.AntiMoratorio  )                         AS Moratorios,                                                               


                                                     (cargos.SegV    )                                                          AS SegV,
                                                     (cargos.SegD    )                                                          AS SegD,
                                                     (cargos.SegB    )                                                          AS SegB,





                                                     (cargos.IVA             -  cargos.AntiIVA        )                         AS IVA,


                                                      cargos.IVA_Interes                                                        AS IVA_Interes,  
                                                      cargos.IVA_Comision                                                       AS IVA_Comision,
                                                      cargos.IVA_Moratorio                                                      AS IVA_Moratorio,
                                                      cargos.IVA_Otros                                                          AS IVA_Otros,


                                                      cargos.IVA_SegV                                                           AS IVA_SegV,
                                                      cargos.IVA_SegD                                                           AS IVA_SegD,
                                                      cargos.IVA_SegB                                                           AS IVA_SegB,

                                                                                

                                                     0                                                                          AS ORD,
                                                     IF(SubTipo='Otros',0,abs(cargos.ID_Concepto))                              AS ORD2

                                         FROM cargos
                                         WHERE num_compra = '".$this->numcompra."' and
                                               cargos.Fecha_vencimiento >=  '".$this->aplicacion[($id-1)]['Fecha']."' and  cargos.Fecha_vencimiento < '".$this->Fecha_Sig_Cuota."' and
                                               cargos.Fecha_vencimiento  <= '".$this->fecha_corte."'        and
                                               cargos.ID_Concepto!=-3   and cargos.Activo='Si' )
                                        
                                        ORDER BY Fecha, ORD, ORD2, ID, ID_Concepto DESC, Monto DESC";

                  $rs_parciales = $this->db->Execute($sql);
                
              // debug($sql);
              // die();
                  
                  if($rs_parciales)
                     while(! $rs_parciales->EOF)
                     {
                                  // Si ya se saldo hasta el momento el cago actual. ya no aplicamos más abonos por el momento,
                                  // pero si aun hay saldo pendiente, lo aplicamos y registramos el abono                                    


                                        if(($this->aplicacion[($id-1)]['SALDO_MOV_Pendiente_Aplicar'] <0)) //  and ( !( $rs_parciales->fields['Fecha'] > $this->fecha_ultimo_abono) and ( $rs_parciales->fields['Tipo'] == 'Cargo') and ($rs_parciales->fields['Concepto'] == 'Documento')))                                    
                                        {                                                                               
                                                
                                            if( ($rs_parciales->fields['Fecha'] <= $this->fecha_ultimo_abono )  and  ( $rs_parciales->fields['Tipo'] == 'Cargo') and ($rs_parciales->fields['ID_Concepto'] != 3))
                                            {
                                                // Entra primero la nota de cargo antes de aplicación de saldo a favor
                                            }   
                                            else
                                            {
                                                
                                                    if( ($rs_parciales->fields['Fecha'] <= $this->fecha_ultimo_abono )  and  ( $rs_parciales->fields['Tipo'] == 'Abono') and ($rs_parciales->fields['Concepto'] == 'Documento'))
                                                    {
                                                    
                                                                // Entra primero la nota de crédito antes de aplicación de saldo a favor
                                                               // $this->aplicacion[$id]['Descripcion'] = "*";
                                                    
                                                    }
                                                    else
                                                    {


                                                
                                                                $id = $this->aplica_cargos_cobranza($id,$ID_Cuota, $this->fecha_ultimo_abono,  -1, "A");

                                                             // if($ID_Cuota != $this->id_cargo_reemplazo_vencimiento_anticipado)
                                                                $id = $this->aplica_saldo_favor_pendiente($id,$ID_Cuota,-1);
                                                    }

                                            }


                                        }
                                
                                
                                        $_saldo_mov_general = $this->SaldoGeneralVencido;
                                        
                                        

                                                
                                                if($_saldo_mov_general < 0.01)
                                                        if(( $rs_parciales->fields['Tipo'] == 'Abono') and ($rs_parciales->fields['Concepto'] == 'Efectivo') and ($ID_Cuota < $this->id_cuota_final) and (! $rs_parciales->EOF))
                                                        {
                                                                   
                                                                $rs_parciales->MoveNext();

                                                                continue;
                                                        }

                                                /**/

                                                if(( $rs_parciales->fields['Tipo'] == 'Abono'  ) and (  $rs_parciales->fields['Concepto']== 'Efectivo'))
                                                {
                                                        // Guardamos los ID de los abonos que ya están aplicados
                                                        $this->abonos_desde[]= $rs_parciales->fields['ID'];
                                                        $this->fecha_ultimo_abono = $rs_parciales->fields['Fecha'];

                                                        $this->aplicacion[$id]['DEBUG'] = $_saldo_mov_general;

                                                }

                                        
                                               $id = $this->aplica_cargos_cobranza($id,$ID_Cuota, $rs_parciales->fields['Fecha'], -1, "B");


                                                if(( $rs_parciales->fields['Tipo'] == 'Abono'  ) and (  $rs_parciales->fields['Concepto']== 'Efectivo'))
                                                {

                                                        $this->fecha_ultimo_abono = $rs_parciales->fields['Fecha'];
                                                        $this->id_ultimo_abono    = $id;
                                                        $this->ultimo_abono_ID    = $rs_parciales->fields['ID'];
                                                        

                                                }




                                                $this->aplicacion[$id]['Tipo']          = $rs_parciales->fields['Tipo'];
                                                $this->aplicacion[$id]['SubTipo']       = $rs_parciales->fields['SubTipo'];

                                                $this->aplicacion[$id]['Fecha']         = $rs_parciales->fields['Fecha'];
                                                $this->aplicacion[$id]['Fecha_Mov']     = $rs_parciales->fields['Fecha'];                                               
                                                $this->aplicacion[$id]['Concepto']      = $rs_parciales->fields['Concepto'];
                                                $this->aplicacion[$id]['Descripcion']   = $rs_parciales->fields['Descripcion'];

                                                $this->aplicacion[$id]['ID']            = $rs_parciales->fields['ID'];
                                                $this->aplicacion[$id]['ID_Concepto']   = $rs_parciales->fields['ID_Concepto'];
                                                
                                                $this->aplicacion[$id]['ID_Cuota']      = $ID_Cuota;
                                                
                                                
                                                
                                                
                                                

                                                $this->aplicacion[$id]['Monto']         = $rs_parciales->fields['Monto'];               

                                                $this->aplicacion[$id]['SaldoCapitalPorVencer']  = ($this->aplicacion[($id-1)]['SaldoCapitalPorVencer'] <0)?(0):($this->aplicacion[($id-1)]['SaldoCapitalPorVencer'] );    
                                                $this->aplicacion[$id]['SaldoInteresPorVencer']  = ($this->aplicacion[($id-1)]['SaldoInteresPorVencer'] <0)?(0):($this->aplicacion[($id-1)]['SaldoInteresPorVencer'] );    
                                                $this->aplicacion[$id]['SaldoComisionPorVencer'] = ($this->aplicacion[($id-1)]['SaldoComisionPorVencer']<0)?(0):($this->aplicacion[($id-1)]['SaldoComisionPorVencer']);



                                                $this->aplicacion[$id]['SaldoSegVPorVencer']  = ($this->aplicacion[($id-1)]['SaldoSegVPorVencer'] <0)?(0):($this->aplicacion[($id-1)]['SaldoSegVPorVencer'] );    
                                                $this->aplicacion[$id]['SaldoSegDPorVencer']  = ($this->aplicacion[($id-1)]['SaldoSegDPorVencer'] <0)?(0):($this->aplicacion[($id-1)]['SaldoSegDPorVencer'] );    
                                                $this->aplicacion[$id]['SaldoSegBPorVencer']  = ($this->aplicacion[($id-1)]['SaldoSegBPorVencer'] <0)?(0):($this->aplicacion[($id-1)]['SaldoSegBPorVencer'] );






                                                $this->aplicacion[$id]['SaldoInteres_IVA_PorVencer']  = ($this->aplicacion[($id-1)]['SaldoInteres_IVA_PorVencer'] <0)?(0):($this->aplicacion[($id-1)]['SaldoInteres_IVA_PorVencer'] );
                                                $this->aplicacion[$id]['SaldoComision_IVA_PorVencer'] = ($this->aplicacion[($id-1)]['SaldoComision_IVA_PorVencer']<0)?(0):($this->aplicacion[($id-1)]['SaldoComision_IVA_PorVencer']);

                                                $this->aplicacion[$id]['SaldoSegV_IVA_PorVencer']  = ($this->aplicacion[($id-1)]['SaldoSegV_IVA_PorVencer'] <0)?(0):($this->aplicacion[($id-1)]['SaldoSegV_IVA_PorVencer'] );    
                                                $this->aplicacion[$id]['SaldoSegD_IVA_PorVencer']  = ($this->aplicacion[($id-1)]['SaldoSegD_IVA_PorVencer'] <0)?(0):($this->aplicacion[($id-1)]['SaldoSegD_IVA_PorVencer'] );    
                                                $this->aplicacion[$id]['SaldoSegB_IVA_PorVencer']  = ($this->aplicacion[($id-1)]['SaldoSegB_IVA_PorVencer'] <0)?(0):($this->aplicacion[($id-1)]['SaldoSegB_IVA_PorVencer'] );








                                                        if($this->aplicacion[$id]['SaldoCapitalPorVencer']<=0)
                                                        {
                                                                $this->aplicacion[$id]['SaldoInteresPorVencer'] = 0;
                                                                $this->aplicacion[$id]['SaldoInteres_IVA_PorVencer'] = 0;
                                                        }




                                                        $this->SaldoCapitalPorVencer    = $this->aplicacion[$id]['SaldoCapitalPorVencer'] ;
                                                        $this->SaldoInteresPorVencer    = $this->aplicacion[$id]['SaldoInteresPorVencer'] ; 
                                                        $this->SaldoComisionPorVencer   = $this->aplicacion[$id]['SaldoComisionPorVencer'];

                                                        $this->SaldoSegVPorVencer      = $this->aplicacion[$id]['SaldoSegVPorVencer'] ;
                                                        $this->SaldoSegDPorVencer      = $this->aplicacion[$id]['SaldoSegDPorVencer'] ; 
                                                        $this->SaldoSegBPorVencer      = $this->aplicacion[$id]['SaldoSegBPorVencer'];





                                                        $this->Saldo_IVA_InteresPorVencer    = $this->aplicacion[$id]['SaldoInteres_IVA_PorVencer'] ;
                                                        $this->Saldo_IVA_ComisionPorVencer   = $this->aplicacion[$id]['SaldoComision_IVA_PorVencer'] ;

                                                        $this->Saldo_IVA_SegVPorVencer   = $this->aplicacion[$id]['SaldoSegV_IVA_PorVencer'] ;
                                                        $this->Saldo_IVA_SegDPorVencer   = $this->aplicacion[$id]['SaldoSegD_IVA_PorVencer'] ;
                                                        $this->Saldo_IVA_SegBPorVencer   = $this->aplicacion[$id]['SaldoSegB_IVA_PorVencer'] ;



                                                        if($this->SaldoInteresPorVencer  <= 0)
                                                           $this->Saldo_IVA_InteresPorVencer     = 0;
                                                        
                                                        if($this->SaldoComisionPorVencer  <= 0)
                                                           $this->Saldo_IVA_ComisionPorVencer    = 0;
                                                           
                                                           
                                                        if($this->SaldoSegVPorVencer   <= 0)
                                                           $this->Saldo_IVA_SegVPorVencer   = 0;
                                                        
                                                        if($this->SaldoSegDPorVencer   <= 0)
                                                           $this->Saldo_IVA_SegDPorVencer   = 0;
                                                        
                                                        if($this->SaldoSegBPorVencer   <= 0)
                                                           $this->Saldo_IVA_SegBPorVencer   = 0;
                                                           
                                                           



                                                if($rs_parciales->fields['Tipo'] == 'Abono')
                                                {

                                                    if( $rs_parciales->fields['Concepto'] == 'Efectivo')
                                                        if($this->aplicacion[$id]['Fecha'] < $this->aplicacion[($id-1)]['Fecha'])
                                                        {
                                                                $this->aplicacion[$id]['Descripcion'] .= " : ".ffecha( $this->aplicacion[$id]['Fecha'] );
                                                                $this->aplicacion[$id]['Fecha'] = $this->aplicacion[($id-1)]['Fecha'];
                                                        
                                                        }



                                                        $this->aplicacion[$id]['Abono']                 = $this->aplicacion[$id]['Monto'];
                                                        $this->SumaAbonos                              += $this->aplicacion[$id]['Monto'];





                                                        //$this->aplicacion[$id]['SaldoComisionPorVencer'] = $this->aplicacion[($id-1)]['SaldoComisionPorVencer'];   


                                                        //------------------------------------------------------------------
                                                        if(($this->aplicacion[$id]['Concepto']== 'Documento') and ($this->aplicacion[$id]['SubTipo'] != 'General' ))
                                                        {

                                
                                                                $this->aplicacion[$id]['NOTA_CREDITO_Capital']          = $rs_parciales->fields['Capital'];     
                                                                $this->aplicacion[$id]['NOTA_CREDITO_Interes' ]         = $rs_parciales->fields['Interes' ];
                                                                $this->aplicacion[$id]['NOTA_CREDITO_Comision']         = $rs_parciales->fields['Comision'];    
                                                                $this->aplicacion[$id]['NOTA_CREDITO_Otros']            = $rs_parciales->fields['Otros'];               
                                                                $this->aplicacion[$id]['NOTA_CREDITO_Moratorios']       = $rs_parciales->fields['Moratorios'];          


                                                                $this->aplicacion[$id]['NOTA_CREDITO_SegV']         = $rs_parciales->fields['SegV'];     
                                                                $this->aplicacion[$id]['NOTA_CREDITO_SegD']         = $rs_parciales->fields['SegD'];
                                                                $this->aplicacion[$id]['NOTA_CREDITO_SegB']         = $rs_parciales->fields['SegB'];    





                                                                $this->aplicacion[$id]['NOTA_CREDITO_IVA']              = $rs_parciales->fields['IVA'];         
                          
                                                               // debug(" NOTA_CREDITO_IVA : (".$this->aplicacion[$id]['NOTA_CREDITO_IVA'].") SubTipo: ".$rs_parciales->fields['SubTipo']);
                                                                
                                                                
                                                                switch (trim($rs_parciales->fields['SubTipo']))
                                                                {
                                                                
                                                                        case 'Interes'   :  $this->aplicacion[$id]['NOTA_CREDITO_IVA_Interes']      = -1*( $rs_parciales->fields['IVA']); break;
                                                                        case 'Moratorio' :  $this->aplicacion[$id]['NOTA_CREDITO_IVA_Moratorios']   = -1*( $rs_parciales->fields['IVA']); break;
                                                                        case 'Comision'  :  $this->aplicacion[$id]['NOTA_CREDITO_IVA_Comision']     = -1*( $rs_parciales->fields['IVA']); break;
                                                                        case 'Otros'     :  $this->aplicacion[$id]['NOTA_CREDITO_IVA_Otros']        = -1*( $rs_parciales->fields['IVA']); break;
                                                                        case 'SegV'      :  $this->aplicacion[$id]['NOTA_CREDITO_IVA_SegV']         = -1*( $rs_parciales->fields['IVA']); break;
                                                                        case 'SegD'      :  $this->aplicacion[$id]['NOTA_CREDITO_IVA_SegD']         = -1*( $rs_parciales->fields['IVA']); break;
                                                                        case 'SegB'      :  $this->aplicacion[$id]['NOTA_CREDITO_IVA_SegB']         = -1*( $rs_parciales->fields['IVA']); break;



                                                                }
                                                                
                                                                
                                                                
                                                              /*  
                                                                
                                                                
                                                                  if($this->aplicacion[$id]['NOTA_CREDITO_Interes' ]    < 0)    $this->aplicacion[$id]['NOTA_CREDITO_IVA_Interes']      = -1*( $rs_parciales->fields['IVA']);
                           
                                                                  if($this->aplicacion[$id]['NOTA_CREDITO_Comision' ]   < 0)    $this->aplicacion[$id]['NOTA_CREDITO_IVA_Comision']     = -1*( $rs_parciales->fields['IVA']);
                                                         
                                                                  if($this->aplicacion[$id]['NOTA_CREDITO_Otros' ]      < 0)    $this->aplicacion[$id]['NOTA_CREDITO_IVA_Otros']        = -1*( $rs_parciales->fields['IVA']);

                                                                  if($this->aplicacion[$id]['NOTA_CREDITO_Moratorios' ] < 0)    $this->aplicacion[$id]['NOTA_CREDITO_IVA_Moratorios']   = -1*( $rs_parciales->fields['IVA']);
                             
                                                                */

                                                        }



                                                }
                                                else
                                                {
                                                        
                                                        
                                                        
                                                        
                                                        
                                                        
                                                        
                                                        
                                                        
                                                        
                                                        
                                                        
                                                        $this->aplicacion[$id]['Cargo']                 = $this->aplicacion[$id]['Monto'];


                                                        $this->aplicacion[$id]['CARGO_Capital']         = $rs_parciales->fields['Capital'];     

                                                        $this->aplicacion[$id]['CARGO_Interes' ]        = $rs_parciales->fields['Interes' ];
                                                        $this->aplicacion[$id]['CARGO_IVA_Interes']     = $rs_parciales->fields['IVA_Interes' ];
                                                        

                                                        $this->aplicacion[$id]['CARGO_Comision']        = $rs_parciales->fields['Comision'];    
                                                        $this->aplicacion[$id]['CARGO_IVA_Comision']    = $rs_parciales->fields['IVA_Comision'];

                                                        $this->aplicacion[$id]['CARGO_Otros']           = $rs_parciales->fields['Otros'];               
                                                        $this->aplicacion[$id]['CARGO_IVA_Otros']       = $rs_parciales->fields['IVA_Otros'];        

                                                        $this->aplicacion[$id]['CARGO_Moratorio']       = $rs_parciales->fields['Moratorios'];
                                                        $this->aplicacion[$id]['CARGO_IVA_Moratorio']   += $rs_parciales->fields['IVA_Moratorio'];



                                                        $this->aplicacion[$id]['CARGO_SegV']            = $rs_parciales->fields['SegV'];        
                                                        $this->aplicacion[$id]['CARGO_IVA_SegV']        = $rs_parciales->fields['IVA_SegV'];  


                                                        $this->aplicacion[$id]['CARGO_SegD']            = $rs_parciales->fields['SegD'];        
                                                        $this->aplicacion[$id]['CARGO_IVA_SegD']        = $rs_parciales->fields['IVA_SegD'];  


                                                        $this->aplicacion[$id]['CARGO_SegB']            = $rs_parciales->fields['SegB'];        
                                                        $this->aplicacion[$id]['CARGO_IVA_SegB']        = $rs_parciales->fields['IVA_SegB'];  


                                                        //if($rs_parciales->fields['Capital']) debug($sql);


                                                        //$this->SumaIM           +=  $this->aplicacion[$id]['CARGO_Moratorio'];
                                                        //$this->SaldoIM          +=  $this->aplicacion[$id]['CARGO_Moratorio'];

                                                        
                                                       // $this->SumaMoratorioBruto       += $this->aplicacion[$id]['CARGO_Moratorio'] + $this->aplicacion[$id]['CARGO_IVA_Moratorio'];
                                                       // $this->SumaIMB                  = $this->SumaMoratorioBruto;

                                                      //  $this->SumaMoratorio            = $this->SumaIM;
                                                      //  $this->SumaIVAMoratorio         += $this->aplicacion[$id]['CARGO_IVA_Moratorio'];
                                                        
                                                        // debug("this->SumaIVAMoratorio :".$this->SumaIVAMoratorio);

                                                        $this->aplicacion[$id]['CARGO_IVA']     = $rs_parciales->fields['IVA'];         

                                                        $this->SumaCapital      += $this->aplicacion[$id]['CARGO_Capital'];     
                                                        $this->SumaInteres      += $this->aplicacion[$id]['CARGO_Interes' ];
                                                        $this->SumaComision     += $this->aplicacion[$id]['CARGO_Comision'];
                                                        $this->SumaOtros        += $this->aplicacion[$id]['CARGO_Otros'];                       

                                                        $this->SumaIVAInteres   += $this->aplicacion[$id]['CARGO_IVA_Interes'];
                                                        $this->SumaIVAComision  += $this->aplicacion[$id]['CARGO_IVA_Comision'];
                                                        $this->SumaIVAOtros     += $this->aplicacion[$id]['CARGO_IVA_Otros'];                   



                                                        $this->SumaSegV         += $this->aplicacion[$id]['CARGO_SegV'];
                                                        $this->SumaSegD         += $this->aplicacion[$id]['CARGO_SegD'];
                                                        $this->SumaSegB         += $this->aplicacion[$id]['CARGO_SegB'];

                                                        $this->SumaIVASegV      += $this->aplicacion[$id]['CARGO_IVA_SegV'];
                                                        $this->SumaIVASegD      += $this->aplicacion[$id]['CARGO_IVA_SegD'];
                                                        $this->SumaIVASegB      += $this->aplicacion[$id]['CARGO_IVA_SegB'];


                                                        $this->SumaIVA  =   $this->SumaIVAInteres +      
                                                                            $this->SumaIVAComision+
                                                                            $this->SumaIVAOtros   +

                                                                            $this->SumaIVASegV    +
                                                                            $this->SumaIVASegD    +
                                                                            $this->SumaIVASegB;
                                                                            
                                                                            
                                                                            
                                                                            
                                                                            
                                                                            


                                                }


                                                if($this->aplicacion[($id-1)]['Fecha']< $this->aplicacion[$id]['Fecha'])
                                                {                               

                                                   $this->aplicacion[$id]['DiasAtraso'] = fposdias($this->aplicacion[$id]['Fecha'],$this->aplicacion[($id-1)]['Fecha']);


                                                        if($this->aplicacion[$id]['Tipo'] == 'Abono')
                                                        {
                                                                        $_fecha_referencia = max($this->aplicacion[($id-1)]['Fecha'], $FechaCuota);

                                                                        $this->aplicacion[$id]['DiasAtraso'] = fposdias($this->aplicacion[($id)]['Fecha'],$_fecha_referencia);

                                                        }




                                                        $this->aplicacion[$id]['IMB'] = $this->calculo_interes_moratorio_variable($id,$ID_Cuota,"B");

                                                        if($this->aplicacion[$id]['IMB'])
                                                        {

                                                                //$this->aplicacion[$id]['IM']  = $this->aplicacion[$id]['IMB']/(1+$this->iva_pcnt_moratorios);
                                                                $this->aplicacion[$id]['CARGO_IVA']            +=($this->aplicacion[$id]['IMB'] - $this->aplicacion[$id]['IM']);
                                                                $this->aplicacion[$id]['CARGO_IVA_Moratorio' ] +=($this->aplicacion[$id]['IMB'] - $this->aplicacion[$id]['IM']);;                                       
                                                        }



                                                }



                                                $this->aplicacion[$id]['SaldoParcial']        = $this->aplicacion[($id-1)]['SaldoParcial']   + $this->aplicacion[$id]['Monto'] + $this->aplicacion[$id]['IMB'];
                                                $this->aplicacion[$id]['SALDO_General']       = $this->aplicacion[($id-1)]['SALDO_General']  + $this->aplicacion[$id]['Monto'] + $this->aplicacion[$id]['IMB'];
                                                
                                                if($this->aplicacion[($id-1)]['SALDO_MOV_General'] < 0)
                                                {
                                                  $this->aplicacion[$id]['SALDO_General']       += $this->aplicacion[($id-1)]['SALDO_MOV_General'];
                                                }


                                                if($this->aplicacion[$id]['SaldoParcial']<=0)
                                                {
                                                   $this->aplicacion[$id]['SaldoParcial'] = 0;                                                          
                                                }

                                                if($this->aplicacion[$id]['SALDO_General']<=0)
                                                {                                               
                                                              $this->aplicacion[$id]['SALDO_General'] = 0;                                                              
                                                }

                                                
                                                
                                                $this->aplicacion[$id]['DiasAtrasoAcum'] = $this->aplicacion[($id-1)]['DiasAtrasoAcum'] + $this->aplicacion[$id]['DiasAtraso'];         

                                                
                                                if(($this->aplicacion[$id]['DiasAtraso'] == 0) and ($this->aplicacion[$id]['SALDO_General']<=0.01))
                                                    $this->aplicacion[$id]['DiasAtrasoAcum'] = 0;
                                                        


                                                $this->desglosar_movimientos($id,$ID_Cuota);

                                                $rs_parciales->MoveNext();

                                                ++$id;
                                                
                                                
                                                
                                
                     }



                if($this->aplicacion[($id-1)]['SALDO_MOV_Pendiente_Aplicar'] < 0) 
                {                                                                               
                                $id = $this->aplica_cargos_cobranza($id,$ID_Cuota, $this->fecha_ultimo_abono,  -1, "C");
                                $id = $this->aplica_saldo_favor_pendiente($id,$ID_Cuota,-1);
                }
                else
                {
                                $id = $this->aplica_cargos_cobranza($id,$ID_Cuota,min($this->Fecha_Sig_Cuota, $this->fecha_corte) ,  -1, "D");

                }


//              debug("$id) Ultimo Mov : ".$this->aplicacion[($id-1)]['Fecha']." (".$this->aplicacion[($id-1)]['SALDO_General'] .")   Siguiente Couta ".$this->Fecha_Sig_Cuota  );






//-------------------------------------------------------------------------------------------------------------
// Pagos que entran entre una cuota saldada y otra que no inicia aun 
//-------------------------------------------------------------------------------------------------------------
if($this->aplicacion[($id-1)]['SALDO_General'] < 0.01)
{

            $this->aplicacion[($id-1)]['SALDO_General'] = 0;
 
 $maxima_fecha_busqueda = min($this->Fecha_Sig_Cuota, $this->fecha_corte);
 
 $fecha_cierre_cuota_anterior = $this->aplicacion[($id-1)]['Fecha'];
 $sqlAbonosMedios = "
     SELECT  'Abono'                     AS Tipo,
             IF(conceptos.forma IS NULL,'!',conceptos.forma) AS Concepto,
             conceptos.Descripcion           AS Descripcion,
             pagos.fecha                     AS Fecha,
             pagos.Monto * (-1)              AS Monto,
             pagos.SubTipo                   AS SubTipo,
             pagos.Aplicacion                AS Aplicacion,
             pagos.Id_pago                   AS ID,
             pagos.Forma                     AS Via,
             pagos.ID_Concepto               AS ID_Concepto,
             '0.0'                           AS Comision,
             '0.0'                           AS Capital,
             '0.0'                           AS Interes,
             '0.0'                           AS Otros,
             '0.0'                           AS IVA

         FROM pagos
         LEFT JOIN conceptos ON pagos.id_concepto = conceptos.id_concepto

        WHERE num_compra         = '".$this->numcompra."'      and
              Fecha              < '".$maxima_fecha_busqueda."'    and 
              Fecha             >= '".$fecha_cierre_cuota_anterior."'    and 
              pagos.Activo      = 'S'   and 

              conceptos.Forma = 'Efectivo'  \n";
               
               
                if(count($this->abonos_desde))
                {
                        $lista = implode("','",$this->abonos_desde);
                        $sqlAbonosMedios .= " and pagos.Id_pago    NOT IN  ('".$lista."')  ";
                
                }
          $sqlAbonosMedios .= "Order BY  pagos.fecha ";                  
        
        $rs_medios=$this->db->Execute($sqlAbonosMedios);

        if($rs_medios->_numOfRows)
        {
                
           while(! $rs_medios->EOF)
           {
        
                $this->aplicacion[$id]['ABONO_LIBRE']   = 1;

                $this->aplicacion[$id]['Tipo']          = $rs_medios->fields['Tipo'];

                $this->aplicacion[$id]['SubTipo']               = $rs_parciales->fields['SubTipo'];             

                $this->aplicacion[$id]['Fecha']         = $rs_medios->fields['Fecha'];


                $this->aplicacion[$id]['Fecha']         = $rs_medios->fields['Fecha'];
                $this->aplicacion[$id]['Fecha_Mov']     = $rs_medios->fields['Fecha'];


                $this->aplicacion[$id]['Concepto']      = $rs_medios->fields['Concepto'];
                $this->aplicacion[$id]['Descripcion']   = $rs_medios->fields['Descripcion']." con aplicación anticipada ";



                $this->aplicacion[$id]['ID']            = $rs_medios->fields['ID'];
                $this->aplicacion[$id]['ID_Concepto']   = $rs_medios->fields['ID_Concepto'];
                $this->aplicacion[$id]['ID_Cuota']      = $ID_Cuota;

                $this->aplicacion[$id]['Monto']         = $rs_medios->fields['Monto'];          
                $this->aplicacion[$id]['Abono']         = $this->aplicacion[$id]['Monto'];
                        
                $this->SumaAbonos       += $this->aplicacion[$id]['Abono'] ;

                $this->aplicacion[$id]['SALDO_General'] = $this->aplicacion[($id-1)]['SALDO_General'] + $this->aplicacion[$id]['Abono'];
                if($this->aplicacion[($id-1)]['SALDO_MOV_General'] < 0)
                {
                  $this->aplicacion[$id]['SALDO_General']       += $this->aplicacion[($id-1)]['SALDO_MOV_General'];
                }

                
                
                
                if($this->aplicacion[$id]['SALDO_General'] < 0)
                {
                
                        $this->aplicacion[$id]['SALDO_MOV_Pendiente_Aplicar'] = $this->aplicacion[$id]['SALDO_General'];        
                
                }               
                
                $this->aplicacion[$id]['SALDO_General'] = 0;
                
                
                $this->abonos_desde[]= $rs_medios->fields['ID'];

                $this->fecha_ultimo_abono = $this->aplicacion[$id]['Fecha'];
                $this->id_ultimo_abono = $id;
                $this->ultimo_abono_ID = $rs_medios->fields['ID'];

                
                if($id)
                {

                        $this->aplicacion[$id]['SaldoCapitalPorVencer']      = $this->aplicacion[($id-1)]['SaldoCapitalPorVencer'];  //    
                        $this->aplicacion[$id]['SaldoInteresPorVencer']      = $this->aplicacion[($id-1)]['SaldoInteresPorVencer'];   //                          
                        $this->aplicacion[$id]['SaldoInteres_IVA_PorVencer'] = $this->aplicacion[($id-1)]['SaldoInteres_IVA_PorVencer'];
  
  
  
  
  
                        if($this->aplicacion[$id]['SaldoCapitalPorVencer']<=0)
                        {
                           $this->aplicacion[$id]['SaldoInteresPorVencer'] = 0;
                           $this->aplicacion[$id]['SaldoInteres_IVA_PorVencer'] =0;
                        }
                     
                        
                        
                        $this->aplicacion[$id]['SaldoComisionPorVencer']      = $this->aplicacion[($id-1)]['SaldoComisionPorVencer'];  //   
                        
                        $this->aplicacion[$id]['SaldoComision_IVA_PorVencer'] = $this->aplicacion[($id-1)]['SaldoComision_IVA_PorVencer'] ;
               
               
                        $this->aplicacion[$id]['SaldoSegVPorVencer']  = $this->aplicacion[($id-1)]['SaldoSegVPorVencer'];
                        $this->aplicacion[$id]['SaldoSegDPorVencer']  = $this->aplicacion[($id-1)]['SaldoSegDPorVencer'];
                        $this->aplicacion[$id]['SaldoSegBPorVencer']  = $this->aplicacion[($id-1)]['SaldoSegBPorVencer'];
               
                        $this->aplicacion[$id]['SaldoSegV_IVA_PorVencer']  =  $this->aplicacion[($id-1)]['SaldoSegV_IVA_PorVencer'];             
                        $this->aplicacion[$id]['SaldoSegD_IVA_PorVencer']  =  $this->aplicacion[($id-1)]['SaldoSegD_IVA_PorVencer'];
                        $this->aplicacion[$id]['SaldoSegB_IVA_PorVencer']  =  $this->aplicacion[($id-1)]['SaldoSegB_IVA_PorVencer'];                       
                        
               
               }

                $this->desglosar_movimientos($id,$ID_Cuota);
                ++$id;
                $rs_medios->MoveNext();
           }
        }
}





//-------------------------------------------------------------------                
//      SALDO PARCIAL VERDE
//-------------------------------------------------------------------                

//                    
//


                    if(  ( $this->SaldoGeneralVencido >= 0.005) )
                     {
                     
                     
                    // debug(" SaldoGeneralVencido : fecha :".$this->aplicacion[$id]['Fecha']."  ".$this->SaldoGeneralVencido);
                     
                    //  ($this->aplicacion[($id-1)]['ID'] != $ID_Cuota) and  
                     // and( $this->aplicacion[($id-1)]['Fecha'] != $this->fecha_corte)and 
                     // (!$SALDO_PARCIAL ))
                        $bonificaciones_por_aplicar = 0;
                        
                        $sqlEfectivosPorAplicar = "     SELECT SUM(pagos.Monto)
                                                        FROM pagos
                                                        WHERE num_compra         = '".$this->numcompra."'          and
                                                               Fecha            <= '".$this->fecha_corte."'        and
                                                               pagos.Activo     = 'S'                              and 
                                                               pagos.Id_pago    NOT IN ('".implode("','",$this->abonos_desde)."')   ";                  
                        $rs_pa =$this->db->Execute($sqlEfectivosPorAplicar);
                        $bonificaciones_por_aplicar = $rs_pa->fields[0];
                        
                        //------------------------------------------------------------------
                        //Si ya no hay ya abonos por aplicar obtenemos el saldo parcial

                        $id = $this->aplica_cargos_cobranza($id,$ID_Cuota, min($this->Fecha_Sig_Cuota, $this->fecha_corte),  -1, "F");

                        if( ! $bonificaciones_por_aplicar)
                        {

                        $id = $this->aplica_cargos_cobranza($id,$ID_Cuota, $this->fecha_corte,  -1, "G");

                                        //Saldo parcial Insoluto
                                        $SALDO_PARCIAL = true;


                                        $this->aplicacion[$id]['Tipo']          = 'Saldo';
                                        $this->aplicacion[$id]['Fecha']         = $this->fecha_corte;
                                        $this->aplicacion[$id]['Concepto']      = "Saldo";

                                        $this->aplicacion[$id]['Descripcion']   = "Saldo";
                                                
                                                $_fecha_referencia = $this->aplicacion[($id-1)]['Fecha'];                                       
                                                
                                                
                                                $this->aplicacion[$id]['DiasAtraso'] = fposdias($this->fecha_corte,$_fecha_referencia);
                                                
                                                //$this->aplicacion[$id]['DiasAtraso'] = fposdias($this->fecha_corte,$FechaCuota);
                                        
                                                        
                                                        
                                                        
                                                        
                                                $this->aplicacion[$id]['DiasAtrasoAcum'] = ($this->aplicacion[$id]['DiasAtraso'] + $this->aplicacion[($id-1)]['DiasAtrasoAcum']);
                                                        
                                                if(!$this->aplicacion[$id]['DiasAtraso'])
                                                {
                                                        if($this->aplicacion[($id-1)]['SALDO_General']<0.01) 
                                                            $this->aplicacion[$id]['DiasAtrasoAcum'] = 0;
                                                                
                                                }
                                                
                                        
                                        

                                        $this->aplicacion[$id]['IMB'] = $this->calculo_interes_moratorio_variable($id,$ID_Cuota,"C");


                                        // (DESGLOSAR IVA)
                                        
                                        //$this->aplicacion[$id]['IM']                     = $this->aplicacion[$id]['IMB']/(1+$this->iva_pcnt_moratorios);

                                        $this->aplicacion[$id]['CARGO_IVA_Moratorio']   += ($this->aplicacion[$id]['IMB'] - $this->aplicacion[$id]['IM']);
                                        $this->aplicacion[$id]['CARGO_IVA']             += ($this->aplicacion[$id]['IMB'] - $this->aplicacion[$id]['IM']);


                                        $this->aplicacion[$id]['SALDO_General'] =  $this->aplicacion[($id-1)]['SALDO_General']  +       $this->aplicacion[$id]['IMB'];
                                        
                                        
                                        if($this->aplicacion[($id-1)]['SALDO_MOV_General'] < 0)
                                        {
                                          $this->aplicacion[$id]['SALDO_General']       += $this->aplicacion[($id-1)]['SALDO_MOV_General'];
                                        }
                                        
                                        
                                        
                                        $this->aplicacion[$id]['SaldoParcial']  =  $this->aplicacion[($id-1)]['SaldoParcial']   +       $this->aplicacion[$id]['IMB'];

                                        $this->aplicacion[$id]['SaldoCapitalPorVencer'      ]  = $this->aplicacion[($id-1)]['SaldoCapitalPorVencer'] ;  
                                        $this->aplicacion[$id]['SaldoInteresPorVencer'      ]  = $this->aplicacion[($id-1)]['SaldoInteresPorVencer'] ;                                          
                                        $this->aplicacion[$id]['SaldoInteres_IVA_PorVencer' ] =  $this->aplicacion[($id-1)]['SaldoInteres_IVA_PorVencer' ];                                   


                                        $this->aplicacion[$id]['SaldoSegVPorVencer']  =  $this->aplicacion[($id-1)]['SaldoSegVPorVencer']; 
                                        $this->aplicacion[$id]['SaldoSegDPorVencer']  =  $this->aplicacion[($id-1)]['SaldoSegDPorVencer'];
                                        $this->aplicacion[$id]['SaldoSegBPorVencer']  =  $this->aplicacion[($id-1)]['SaldoSegBPorVencer'];

                        
                                        $this->aplicacion[$id]['SaldoSegV_IVA_PorVencer']  =  $this->aplicacion[($id-1)]['SaldoSegV_IVA_PorVencer'];                       
                                        $this->aplicacion[$id]['SaldoSegD_IVA_PorVencer']  =  $this->aplicacion[($id-1)]['SaldoSegD_IVA_PorVencer'];                       
                                        $this->aplicacion[$id]['SaldoSegB_IVA_PorVencer']  =  $this->aplicacion[($id-1)]['SaldoSegB_IVA_PorVencer'];                      
                        
                        
                                        
                                        if($this->aplicacion[$id]['SaldoCapitalPorVencer']<=0)
                                        {
                                           $this->aplicacion[$id]['SaldoInteresPorVencer'] = 0;
                                           $this->aplicacion[$id]['SaldoInteres_IVA_PorVencer' ] = 0;
                                        }
                                        
                                        
                                        
                                        $this->aplicacion[$id]['SaldoComisionPorVencer']      = $this->aplicacion[($id-1)]['SaldoComisionPorVencer'];  
                                        $this->aplicacion[$id]['SaldoComision_IVA_PorVencer'] = $this->aplicacion[($id-1)]['SaldoComision_IVA_PorVencer'];                                       




                                        $dias_mora_anterior = $this->aplicacion[($id-1)]['DiasAtraso'];
                                        
                                        //if($this->aplicacion[$id]['SALDO_General']<0.01) $this->dias_mora = 0;

                                        
                                        
                                        
                                        
                                        $this->desglosar_movimientos($id,$ID_Cuota);

                                        ++$id;
                        }
                        

                        //$this->dias_mora = max($this->aplicacion[($id-1)]['DiasAtrasoAcum'],$this->dias_mora);
                        //if($this->aplicacion[($id-1)]['SALDO_General']<0.01) $this->dias_mora = 0;



                     }
             
           }

$this->get_cuotas_pagas_v_vencidas();

//-------------------------------------------------------------------------------------------------------------
// Pagos pendientes de aplicación
//-------------------------------------------------------------------------------------------------------------

 $sqlAbonos = "
         SELECT  'Abono'                     AS Tipo,
             IF(conceptos.forma IS NULL,'!',conceptos.forma) AS Concepto,
             conceptos.Descripcion           AS Descripcion,
             pagos.fecha                     AS Fecha,
             pagos.Monto * (-1)              AS Monto,
             pagos.SubTipo                   AS SubTipo,
             pagos.Aplicacion                AS Aplicacion,
             pagos.Id_pago                   AS ID,
             pagos.Forma                     AS Via,
             pagos.ID_Concepto               AS ID_Concepto,
             '0.0'                           AS Comision,
             '0.0'                           AS Capital,
             '0.0'                           AS Interes,
             '0.0'                           AS Otros,
             '0.0'                           AS IVA

         FROM pagos
         LEFT JOIN conceptos ON pagos.id_concepto = conceptos.id_concepto

        WHERE num_compra         = '".$this->numcompra."'      and
              Fecha             <= '".$this->fecha_corte."'    and 
              pagos.Activo      = 'S'   and 

              conceptos.Forma = 'Efectivo'  \n";
               
               
                if(count($this->abonos_desde))
                {
                        $lista = implode("','",$this->abonos_desde);
                        $sqlAbonos .= " and pagos.Id_pago    NOT IN  ('".$lista."')  ";
                
                }
          $sqlAbonos .= "ORDER BY  pagos.fecha ";                  
  
        
        
        $rs=$this->db->Execute($sqlAbonos);

        if($rs->_numOfRows)
        {
                
           while(! $rs->EOF)
           {
        

                $this->aplicacion[$id]['Tipo']          = $rs->fields['Tipo'];

                $this->aplicacion[$id]['SubTipo']       = $rs_parciales->fields['SubTipo'];             

                $this->aplicacion[$id]['Fecha']         = $rs->fields['Fecha'];


                $this->aplicacion[$id]['Fecha']         = $rs->fields['Fecha'];
                $this->aplicacion[$id]['Fecha_Mov']     = $rs->fields['Fecha'];


                $this->aplicacion[$id]['Concepto']      = $rs->fields['Concepto'];
                $this->aplicacion[$id]['Descripcion']   = $rs->fields['Descripcion'];



                $this->aplicacion[$id]['ID']            = $rs->fields['ID'];
                $this->aplicacion[$id]['ID_Concepto']   = $rs->fields['ID_Concepto'];

                $this->aplicacion[$id]['Monto']         = $rs->fields['Monto'];         
                $this->aplicacion[$id]['Abono']         = $this->aplicacion[$id]['Monto'];
                        
                $this->SumaAbonos       += $this->aplicacion[$id]['Abono'] ;

                $this->aplicacion[$id]['SALDO_General'] = $this->aplicacion[($id-1)]['SALDO_General'] + $this->aplicacion[$id]['Abono'];
                if($this->aplicacion[($id-1)]['SALDO_MOV_General'] < 0)
                {
                  $this->aplicacion[$id]['SALDO_General']       += $this->aplicacion[($id-1)]['SALDO_MOV_General'];
                }
                
                if($this->aplicacion[$id]['SALDO_General'] < 0)
                {
                
                        $this->aplicacion[$id]['SALDO_MOV_Pendiente_Aplicar'] = $this->aplicacion[$id]['SALDO_General'];        
                
                }               
                
                
                $this->aplicacion[$id]['SALDO_General'] = 0;
                
                
                $this->abonos_desde[]= $rs->fields['ID'];

                $this->fecha_ultimo_abono = $this->aplicacion[$id]['Fecha'];
                $this->id_ultimo_abono = $id;
                $this->ultimo_abono_ID = $rs->fields['ID'];
                
                if($id)
                {

                        $this->aplicacion[$id]['SaldoCapitalPorVencer']         = $this->aplicacion[($id-1)]['SaldoCapitalPorVencer'];  //    
                        $this->aplicacion[$id]['SaldoInteresPorVencer']         = $this->aplicacion[($id-1)]['SaldoInteresPorVencer'];   //  
                        $this->aplicacion[$id]['SaldoInteres_IVA_PorVencer' ]   = $this->aplicacion[($id-1)]['SaldoInteres_IVA_PorVencer' ];


                        if($this->aplicacion[$id]['SaldoCapitalPorVencer']<=0)
                        {
                           $this->aplicacion[$id]['SaldoInteresPorVencer'] = 0;
                           $this->aplicacion[$id]['SaldoInteres_IVA_PorVencer' ] = 0;                        
                        }
                        
                        
                        $this->aplicacion[$id]['SaldoComisionPorVencer']      = $this->aplicacion[($id-1)]['SaldoComisionPorVencer'];  //   
                        $this->aplicacion[$id]['SaldoComision_IVA_PorVencer'] = $this->aplicacion[($id-1)]['SaldoComision_IVA_PorVencer'];
                        
                        
                        

                        $this->aplicacion[$id]['SaldoSegVPorVencer']  =  $this->aplicacion[($id-1)]['SaldoSegVPorVencer']; 
                        $this->aplicacion[$id]['SaldoSegDPorVencer']  =  $this->aplicacion[($id-1)]['SaldoSegDPorVencer'];
                        $this->aplicacion[$id]['SaldoSegBPorVencer']  =  $this->aplicacion[($id-1)]['SaldoSegBPorVencer'];

                        
                        $this->aplicacion[$id]['SaldoSegV_IVA_PorVencer']  =  $this->aplicacion[($id-1)]['SaldoSegV_IVA_PorVencer'];                       
                        $this->aplicacion[$id]['SaldoSegD_IVA_PorVencer']  =  $this->aplicacion[($id-1)]['SaldoSegD_IVA_PorVencer'];                       
                        $this->aplicacion[$id]['SaldoSegB_IVA_PorVencer']  =  $this->aplicacion[($id-1)]['SaldoSegB_IVA_PorVencer'];                      
                        



                }

                $this->desglosar_movimientos($id,$ID_Cuota);
                ++$id;
                $rs->MoveNext();
           }
        }
/**/

        $fin=$id-1;




        // $this->SaldoGeneralVencido = $this->aplicacion[$id]['SALDO_General'];



        // Solo para Red de Servicios Financieros
        

//debug("Num_cuota :  ".$ID_Cuota);

$sql= "SELECT cargos.Monto

        FROM cargos 
        WHERE 
        cargos.Num_compra = '".$this->numcompra."' and
        cargos.ID_Cargo   > '".$ID_Cuota."'  and
        cargos.ID_Concepto = -3 and 
        cargos.Concepto NOT LIKE 'Vencimiento anticipado%'
        ORDER BY ID_Cargo
        LIMIT 0,1 ";

$rs  = $this->db->Execute($sql);

        $this->proxima_cuota = $rs->fields['Monto'];
        $this->proxima_cuota = ($this->proxima_cuota <0)?(0):($this->proxima_cuota);    
        


        $this->tasa_equivalente_periodo = ($this->dias_periodo * $this->tasa_eq_ssi/30);


        //----------------------------------------------------------------------
        // Por Compatibilidad con el estado de cuenta de AXEFIN

        $this->SaldoCapitalNoVencido = $this->SaldoCapitalPorVencer;
        
        $this->SaldoIMB =$this->SaldoIM + $this->SaldoIVAIM;    


        $_SumaCargos =  number_format($this->SumaCapital,2,".","")+
                        number_format($this->SumaIMB,2,".",""    )+
                        number_format(($this->SumaInteres         + $this->SumaIVAInteres ),2,".","") +
                        number_format(($this->SumaComision        + $this->SumaIVAComision),2,".","") +
                        number_format(($this->SumaOtros           + $this->SumaIVAOtros   ),2,".","") +

                        number_format(($this->SumaSegV            + $this->SumaIVASegV    ),2,".","") +
                        number_format(($this->SumaSegD            + $this->SumaIVASegD    ),2,".","") +
                        number_format(($this->SumaSegB            + $this->SumaIVASegB    ),2,".","") ;








        $this->SumaCargos = $_SumaCargos;
        /*
        $this->SumaCargos = $this->SumaCapital +
                           ($this->SumaInteres          *   (1+$this->iva_pcnt_intereses ))+
                           ($this->SumaComision         *(1+ $this->iva_pcnt_comisiones)) +
                           ($this->SumaOtros    + $this->SumaIVAOtros           )+  $this->SumaIMB;
        */






        $this->SumaAbono = number_format( $this->SumaAbonoCapital,2,".","")     +
                           number_format(($this->SumaAbonoInteres               +  $this->SumaAbonoIVAInteres    ),2,".","") +

                           number_format( $this->SumaAbonoIMB,2,".",""    )     +

                           number_format(($this->SumaAbonoComision              +  $this->SumaAbonoIVAComision   ),2,".","") +

                           number_format(($this->SumaAbonoOtros                 +  $this->SumaAbonoIVAOtros      ),2,".","") +
                           
                           
                           number_format(($this->SumaAbonoSegV                  +  $this->SumaAbonoIVASegV       ),2,".","") +
                           number_format(($this->SumaAbonoSegD                  +  $this->SumaAbonoIVASegD       ),2,".","") +                           
                           number_format(($this->SumaAbonoSegB                  +  $this->SumaAbonoIVASegB       ),2,".","") +
                           
                           
                           number_format( $this->SaldoFavorPendiente,2,".","")  ;
                           
                           
                           
                           
                           
                           



        $this->Suma_Abonos_Total_Efectivo =     $this->Suma_Abono_Capital_Efectivo              +       
                                                $this->Suma_Abono_Interes_Efectivo              +       
                                                $this->Suma_Abono_IVA_Interes_Efectivo          +       
                                                $this->Suma_Abono_Comision_Efectivo             +       
                                                $this->Suma_Abono_IVA_Comision_Efectivo         +       
                                                $this->Suma_Abono_Otros_Efectivo                +       
                                                $this->Suma_Abono_IVA_Otros_Efectivo            +       
                                                $this->Suma_Abono_Moratorio_Efectivo            +       
                                                $this->Suma_Abono_IVA_Moratorio_Efectivo        +       
     
                                                $this->Suma_Abono_SegV_Efectivo                 +
                                                $this->Suma_Abono_IVA_SegV_Efectivo             +
                                                
                                                $this->Suma_Abono_SegD_Efectivo                 +
                                                $this->Suma_Abono_IVA_SegD_Efectivo             +
                                                
                                                $this->Suma_Abono_SegB_Efectivo                 +
                                                $this->Suma_Abono_IVA_SegB_Efectivo              ; 





        $this->Suma_Abonos_Total_Documento =    $this->Suma_Abono_Capital_Documento             +       
                                                $this->Suma_Abono_Interes_Documento             +       
                                                $this->Suma_Abono_IVA_Interes_Documento         +       
                                                $this->Suma_Abono_Comision_Documento            +       
                                                $this->Suma_Abono_IVA_Comision_Documento        +       
                                                $this->Suma_Abono_Otros_Documento               +       
                                                $this->Suma_Abono_IVA_Otros_Documento           +       
                                                $this->Suma_Abono_Moratorio_Documento           +       
                                                $this->Suma_Abono_IVA_Moratorio_Documento       +    


                                                $this->Suma_Abono_SegV_Documento                +
                                                $this->Suma_Abono_IVA_SegV_Documento            +
                                                
                                                $this->Suma_Abono_SegD_Documento                +
                                                $this->Suma_Abono_IVA_SegD_Documento            +
                                                
                                                $this->Suma_Abono_SegB_Documento                +
                                                $this->Suma_Abono_IVA_SegB_Documento             ; 







/*
        $this->SaldoTotalParaLiquidar = round(($this->SaldoFavorPendiente   + 
                                               $this->SaldoGeneralVencido   +
                                               $this->SaldoCapitalPorVencer + 
                                              ($this->SaldoInteresPorVencer *  (1+ $this->iva_pcnt_intereses))  + 
                                              ($this->SaldoComisionPorVencer * (1+ $this->iva_pcnt_comisiones))  ),2);



        $this->SaldoGeneralVigente =    (($this->SaldoCapitalPorVencer) +
                                         ($this->SaldoInteresPorVencer  *   (1+ $this->iva_pcnt_intereses )) +
                                         ($this->SaldoComisionPorVencer *   (1+ $this->iva_pcnt_comisiones)));

*/


        $this->SaldoTotalParaLiquidar = round(($this->SaldoFavorPendiente    + 
                                               $this->SaldoGeneralVencido    +
                                               $this->SaldoCapitalPorVencer  + 
                                               $this->SaldoInteresPorVencer  + 
                                               $this->SaldoComisionPorVencer +

                                               $this->SaldoSegVPorVencer     +
                                               $this->SaldoSegDPorVencer     +
                                               $this->SaldoSegBPorVencer     +

                                               $this->SaldoGlobalIVA),2);



        $this->SaldoGeneralVigente =    (($this->SaldoCapitalPorVencer  +
                                          $this->SaldoInteresPorVencer  + $this->Saldo_IVA_InteresPorVencer  +
                                          $this->SaldoComisionPorVencer + $this->Saldo_IVA_ComisionPorVencer +

                                          $this->SaldoSegVPorVencer     + $this->Saldo_IVA_SegVPorVencer    +
                                          $this->SaldoSegDPorVencer     + $this->Saldo_IVA_SegDPorVencer    +
                                          $this->SaldoSegBPorVencer     + $this->Saldo_IVA_SegBPorVencer     ));






        // Ajustar saldos negativos con cargo a saldos vigentes
/**/

        if($this->SaldoCapital<0)
        {
                $this->SaldoCapitalPorVencer += $this->SaldoCapital;
                $this->SaldoCapital=0;
        }


        $this->saldo_interes = $this->SaldoInteres;
        if($this->SaldoInteres<0)
        {       
                $this->SaldoInteresPorVencer += $this->SaldoInteres;
                $this->SaldoInteres=0;
                
        }
        
        if($this->SaldoIVAInteres <0)
        {
        
                $this->Saldo_IVA_InteresPorVencer += $this->SaldoIVAInteres;
                $this->SaldoIVAInteres = 0;
        }
        

        if($this->SaldoComision<0)
        {
                $this->SaldoComisionPorVencer += $this->SaldoComision;
                $this->SaldoComision=0;
        }       
        

        if($this->SaldoIVAComision<0)
        {
                $this->Saldo_IVA_ComisionPorVencer += $this->SaldoIVAComision;
                
                $this->SaldoIVAComision=0;
        }       




        if($this->SaldoSegV    <0)
        {
             $this->SaldoSegVPorVencer      += $this->SaldoSegV;
             $this->SaldoSegV  = 0;
        }

        if($this->SaldoSegD    <0)
        {
             $this->SaldoSegDPorVencer      += $this->SaldoSegD;
             $this->SaldoSegD  = 0;
        }

        if($this->SaldoSegB   <0)
        {
             $this->SaldoSegBPorVencer      += $this->SaldoSegB;
             $this->SaldoSegB  = 0;
        }



        if($this->SaldoIVASegV    <0) 
        {
             $this->Saldo_IVA_SegVPorVencer +=  $this->SaldoIVASegV;
             $this->SaldoIVASegV = 0;
        }

        if($this->Saldo_IVA_SegD    <0)
        {
             $this->Saldo_IVA_SegDPorVencer +=  $this->SaldoIVASegD;
             $this->SaldoIVASegD = 0;
        }


        if($this->Saldo_IVA_SegB    <0)
        {
             $this->Saldo_IVA_SegBPorVencer +=  $this->SaldoIVASegB;
             $this->SaldoIVASegB = 0;
        }





        if($this->SaldoGeneralVencido<0)
        {
                        $this->SaldoGeneralVigente += $this->SaldoGeneralVencido;
                        $this->SaldoGeneralVencido  =0;
        }       
        


        if($this->SaldoCapitalPorVencer < 0)
           $this->SaldoCapitalPorVencer = 0;
           
        
        if($this->SaldoInteresPorVencer < 0)
        {
           $this->SaldoInteresPorVencer = 0;
           $this->Saldo_IVA_InteresPorVencer = 0;
           
        }
           

        if($this->SaldoComisionPorVencer < 0) 
        {
           $this->SaldoComisionPorVencer = 0;
           $this->Saldo_IVA_ComisionPorVencer = 0;
        
        }


        if($this->SaldoGeneralVigente < 0)
           $this->SaldoGeneralVigente = 0;

        


        $this->SaldoPendienteAplicar = $this->aplicacion[($id-1)]['SALDO_MOV_Pendiente_Aplicar'];



        $this->SaldoGeneralGlobal  =  (    $this->SaldoGlobalCapital            +
                                         
                                         (($this->SaldoInteres                  + $this->SaldoInteresPorVencer  +  $this->Saldo_IVA_InteresPorVencer   + $this->SaldoIVAInteres )) +
                                         (($this->SaldoComision                 + $this->SaldoComisionPorVencer +  $this->Saldo_IVA_ComisionPorVencer  + $this->SaldoIVAComision)) +
                                         
                                         
                                         (($this->SaldoSegV                     + $this->SaldoSegVPorVencer     +  $this->Saldo_IVA_SegVPorVencer     + $this->SaldoIVASegV    )) +
                                         (($this->SaldoSegD                     + $this->SaldoSegDPorVencer     +  $this->Saldo_IVA_SegDPorVencer     + $this->SaldoIVASegD    )) +
                                         (($this->SaldoSegB                     + $this->SaldoSegBPorVencer     +  $this->Saldo_IVA_SegBPorVencer     + $this->SaldoIVASegB    )) +
                                         
                                           $this->SaldoIMB                      +       
                                          
                                         ( $this->SaldoOtros                    + $this->SaldoIVAOtros        ) +
                                         
                                           $this->SaldoFavorPendiente                                         );








        if(empty($ID_Cuota))
        $ID_Cuota = "0";


        if(($this->is_vencimiento_anticipado) and ($ID_Cuota >= $this->id_cargo_reemplazo_vencimiento_anticipado))
        {
                $this->proxima_cuota_interes = 0;
        }
        else
        {

                $sqlNextCuota= "        SELECT  (cargos.Interes - cargos.AntiInteres) AS Interes,
                                                 cargos.IVA_Interes
                                        FROM    cargos
                                        WHERE   cargos.ID_Cargo  > '".$ID_Cuota."'      and 
                                                num_compra = '".$this->numcompra."'     and
                                                cargos.ID_Concepto = -3                 and 
                                                cargos.Activo      ='Si' 

                                        ORDER BY Fecha_vencimiento ";



                $rn=$this->db->Execute($sqlNextCuota);

                $this->proxima_cuota_interes    = (($rn->fields['Interes']    +  $rn->fields['IVA_Interes'] ) + 
                                                  ($this->abonos_contra_saldos_vigentes_por_couta[($ID_Cuota+1)]['Interes'] +                
                                                   $this->abonos_contra_saldos_vigentes_por_couta[($ID_Cuota+1)]['Interes_IVA']));


        }

        //debug($this->proxima_cuota_interes);

        $_saldo_vencido = ($this->saldo_vencido <=0)?(0):($this->saldo_vencido);
        
        //$this->saldo_para_liquidar_hoy = ($_saldo_vencido  + $this->saldo_por_vencer_capital + $this->saldo_por_vencer_comision + $this->proxima_cuota_interes);
        

/*                                      
        
        $this->saldo_para_liquidar_hoy = $this->SaldoGeneralVencido     + 
                                         $this->SaldoCapitalPorVencer   + 
                                        ($this->SaldoComisionPorVencer  * ( 1+ $this->iva_pcnt_comisiones))  + 
                                         $this->proxima_cuota_interes ;
                                        

*/

        $this->saldo_para_liquidar_hoy = $this->SaldoGeneralVencido     + 
                                         $this->SaldoCapitalPorVencer   + 
                                        ($this->SaldoComisionPorVencer  + $this->Saldo_IVA_ComisionPorVencer)  + 
                                         $this->proxima_cuota_interes ;


       //===========================================================================================================
       // Abonos a saldo vigente
       //===========================================================================================================

        
        if($this->saldo_para_liquidar_hoy >= 1)
                $this->saldo_para_liquidar_hoy = ceil($this->saldo_para_liquidar_hoy);
        else
                $this->saldo_para_liquidar_hoy = 0;
        
        
        $this->SaldoParaLiquidar       = $this->saldo_para_liquidar_hoy;

       //===========================================================================================================



        
        $this->saldo_vencido             =  round($this->SaldoFavorPendiente + $this->SaldoGeneralVencido );
        
        
        $this->saldo_vencido            =  ($this->saldo_vencido<0)?(0):($this->saldo_vencido);
        


        $this->saldo_por_vencer_capital  =  $this->saldo_por_vencer_capital  =  $this->SaldoCapitalPorVencer;
        $this->saldo_por_vencer_capital  = ($this->saldo_por_vencer_capital  <0)?(0):($this->saldo_por_vencer_capital);

        $this->saldo_por_vencer_comision = $this->SaldoComisionPorVencer + $this->Saldo_IVA_ComisionPorVencer;       
        $this->saldo_por_vencer_comision = ($this->saldo_por_vencer_comision  <0)?(0):($this->saldo_por_vencer_comision);

        $this->saldo_por_vencer_interes  =  $this->SaldoInteresPorVencer  + $this->Saldo_IVA_InteresPorVencer;
        $this->saldo_por_vencer_interes  = ($this->saldo_por_vencer_interes  <0)?(0):($this->saldo_por_vencer_interes);
        
        $this->saldo_por_vencer_total  = round( ($this->SaldoCapitalPorVencer  + 
                                                ($this->SaldoInteresPorVencer  + $this->Saldo_IVA_InteresPorVencer )  + 
                                                ($this->SaldoComisionPorVencer + $this->Saldo_IVA_ComisionPorVencer)  ),2);




        $this->saldo_por_vencer_total = ($this->saldo_por_vencer_total  <0)?(0):($this->saldo_por_vencer_total);


        
        $this->adeudo_total =   (       $this->SaldoFavorPendiente      + 
                                        $this->SaldoGeneralVencido      +
                                        
                                        $this->SaldoCapitalPorVencer    + 
                                        $this->SaldoInteresPorVencer    + 
                                        $this->SaldoComisionPorVencer   +   
                                        
                                        $this->SaldoSegVPorVencer       + 
                                        $this->SaldoSegDPorVencer       + 
                                        $this->SaldoSegBPorVencer       + 
                                        
                                          
                                        $this->Saldo_IVA_InteresPorVencer  + 
                                        $this->Saldo_IVA_ComisionPorVencer +   
                                        
                                        $this->Saldo_IVA_SegVPorVencer    +                                        
                                        $this->Saldo_IVA_SegDPorVencer    +                                        
                                        $this->Saldo_IVA_SegBPorVencer     );
  

//debug(" Saldo_IVA_InteresPorVencer : ".$this->SaldoInteresPorVencer." + ".$this->Saldo_IVA_InteresPorVencer );

//debug("Saldo_IVA_ComisionPorVencer : " .$this->SaldoComisionPorVencer." + ".$this->Saldo_IVA_ComisionPorVencer );
      
        
        
        $this->adeudo_total = ($this->adeudo_total  <0)?(0):($this->adeudo_total);




        $this->avance=   ($this->plazo)?((100 * $this->numcargosvencidos)/$this->plazo):(0);

        if($this->numcargosvencidos_no_pagados == 0)
        {
                $this->dias_mora=0;
        }
        else
        {
                $_DIAS = 0;
                foreach($this->saldos_cuota AS $key =>$row)
                {
                        if($row['SALDO_General'] >= 0.01 ) 
                        {
                                $_DIAS = max($_DIAS, $row['DiasAtrasoAcum']);
                        }
                }

                $this->dias_mora = $_DIAS;
        }
        
        $this->obtener_clasificacion_puntualidad();

        $this->calcula_saldos_individuales_por_cuota();

        $this->ajuste_saldos($id);




        $time_end = microtime_float();
        $this->precess_time= $time_end - $time_start;












        return;
}

//-------------------------------------------------------------------------------------------------------------------------------------------------------------

function calcula_saldos_individuales_por_cuota()
{

/*
        Poner información de  abonos vencidos y vigentes 
*/      
   if(is_array($this->abonos_contra_saldos_vigentes_por_pago))
    foreach($this->abonos_contra_saldos_vigentes_por_pago AS $fecha_abono => $row)
    {
    
        foreach($row AS $num_cuota => $data)
        {


                $this->saldos_cuota[$num_cuota]['ABONOS_VIGENTES'] += (    $data['Interes'      ]+  
                                                                           $data['Comision'     ]+
                                                                           $data['Capital'      ]+

                                                                           $data['SegV'         ]+
                                                                           $data['SegD'         ]+
                                                                           $data['SegB'         ]+


                                                                           $data['Interes_IVA'  ]+
                                                                           $data['Comision_IVA' ]+
                                                                                                
                                                                           $data['SegV_IVA'     ]+
                                                                           $data['SegD_IVA'     ]+
                                                                           $data['SegB_IVA'     ]       );


        }

    }



        foreach($this->saldos_cuota AS $NumCuota => $saldos_cuota)
        {
        
                        if(abs($this->saldos_cuota[$NumCuota]['PAGOS'] +   $this->saldos_cuota[$NumCuota]['ABONOS_VIGENTES']) > $this->saldos_cuota[$NumCuota]['CARGOS'])
                        {
                                $this->saldos_cuota[$NumCuota]['ABONOS'] = $this->saldos_cuota[$NumCuota]['CARGOS'] * -1;
                        }
                        else
                        {
                                $this->saldos_cuota[$NumCuota]['ABONOS'] = $this->saldos_cuota[$NumCuota]['PAGOS'] +   $this->saldos_cuota[$NumCuota]['ABONOS_VIGENTES'];
                        
                        }
                        
        }



/**/
        return;
}

//-------------------------------------------------------------------------------------------------------------------------------------------------------------

function calificacion_mora_promedio()
{

        $suma_mora_promedio = 0;
        $num_cuota = 0;
        
        $si_pagadas = 0;
        $no_pagadas = 0;
        
        foreach($this->saldos_cuota AS $NumCuota => $row)
        {
        
		  if(($row['Fecha'] >= $this->fecha_inicio) and  ($row['Fecha'] <= $this->fecha_corte) )
		  {

			if(($row['SALDO_MOV_General']< 0.01))
			{

				$suma_mora_promedio += $row['DiasAtrasoAcum'];
				$num_cuota = max($num_cuota,$NumCuota);

				++$si_pagadas;

			}
			else
			{
				++$no_pagadas;	

			}


		  }

        
        }
        
        
        if($num_cuota)
                $this->suma_mora_promedio = $suma_mora_promedio/$num_cuota;
        else
                $this->suma_mora_promedio = 0;  
                
                
        if(($this->suma_mora_promedio == 0) and ( $no_pagadas > 0))
        {
        	$this->suma_mora_promedio = $this->dias_mora;
        
        }



}




//-------------------------------------------------------------------------------------------------------------------------------------------------------------
function desglosar_movimientos($id,$NumCuota)
{
        if(($this->aplicacion[$id]['Tipo'] == 'Abono') and ($this->aplicacion[$id]['Concepto']== 'Documento') and ($this->aplicacion[$id]['SubTipo'] != 'General') )
        {
                $por_aplicar    =  $this->aplicacion[$id]['SaldoFavorAplicado'];
        }
        else
        {
                $por_aplicar    =  $this->aplicacion[$id]['Abono'] + $this->aplicacion[$id]['SaldoFavorAplicado'];              
        }

        //if($NumCuota ==0)
        
        

        if($por_aplicar<0)
        {
                if(abs($por_aplicar)<0.01)
                $por_aplicar = 0;
        
        }



        $SALDO_Moratorio =      $this->aplicacion[$id]['IM'] +($this->aplicacion[$id]['CARGO_Moratorio'])+ $this->aplicacion[($id-1)]['SALDO_MOV_Moratorio'];
        $SALDO_Comision  =      $this->aplicacion[$id]['CARGO_Comision'] + $this->aplicacion[($id-1)]['SALDO_MOV_Comision'];
        $SALDO_Interes   =      $this->aplicacion[$id]['CARGO_Interes' ] + $this->aplicacion[($id-1)]['SALDO_MOV_Interes'];     
        $SALDO_Capital   =      $this->aplicacion[$id]['CARGO_Capital']  + $this->aplicacion[($id-1)]['SALDO_MOV_Capital'];     
        $SALDO_Otros     =      $this->aplicacion[$id]['CARGO_Otros']    + $this->aplicacion[($id-1)]['SALDO_MOV_Otros'];
        


        $SALDO_SegV      =      $this->aplicacion[$id]['CARGO_SegV']     + $this->aplicacion[($id-1)]['SALDO_MOV_SegV'];
        $SALDO_SegD      =      $this->aplicacion[$id]['CARGO_SegD']     + $this->aplicacion[($id-1)]['SALDO_MOV_SegD'];
        $SALDO_SegB      =      $this->aplicacion[$id]['CARGO_SegB']     + $this->aplicacion[($id-1)]['SALDO_MOV_SegB'];










        $SALDO_IVA_Comision  =  $this->aplicacion[$id]['CARGO_IVA_Comision']    + $this->aplicacion[$id]['ABONO_Comision_IVA']         +$this->aplicacion[($id-1)]['SALDO_MOV_IVA_Comision'] ;
        $SALDO_IVA_Interes   =  $this->aplicacion[$id]['CARGO_IVA_Interes']     + $this->aplicacion[$id]['ABONO_Interes_IVA']          +$this->aplicacion[($id-1)]['SALDO_MOV_IVA_Interes']  ;
        $SALDO_IVA_Otros     =  $this->aplicacion[$id]['CARGO_IVA_Otros']       + $this->aplicacion[$id]['ABONO_Otros_IVA']            +$this->aplicacion[($id-1)]['SALDO_MOV_IVA_Otros']    ;

        $SALDO_IVA_Moratorio =  ($this->aplicacion[$id]['CARGO_Moratorio']      + ($this->aplicacion[$id]['IMB'] - $this->aplicacion[$id]['IM']))  + 
                                 $this->aplicacion[$id]['ABONO_Moratorio_IVA']  + $this->aplicacion[($id-1)]['SALDO_MOV_IVA_Moratorio'];




        $SALDO_IVA_SegV      =      $this->aplicacion[$id]['CARGO_IVA_SegV']     + $this->aplicacion[$id]['ABONO_SegV_IVA']         + $this->aplicacion[($id-1)]['SALDO_MOV_IVA_SegV'];
        $SALDO_IVA_SegD      =      $this->aplicacion[$id]['CARGO_IVA_SegD']     + $this->aplicacion[$id]['ABONO_SegD_IVA']         + $this->aplicacion[($id-1)]['SALDO_MOV_IVA_SegD'];
        $SALDO_IVA_SegB      =      $this->aplicacion[$id]['CARGO_IVA_SegB']     + $this->aplicacion[$id]['ABONO_SegB_IVA']         + $this->aplicacion[($id-1)]['SALDO_MOV_IVA_SegB'];





     //   debug("($id)         $SALDO_IVA_Interes   =  $this->aplicacion[$id]['CARGO_IVA_Interes']     + $this->aplicacion[$id]['ABONO_Interes_IVA']          +$this->aplicacion[($id-1)]['SALDO_MOV_IVA_Interes']  ; ");


     //   debug("($id) SALDO_Moratorio  $SALDO_Moratorio \n SALDO_Comision $SALDO_Comision \n SALDO_Interes  $SALDO_Interes \n SALDO_Capital  $SALDO_Capital \n SALDO_Otros  $SALDO_Otros \n");


     //   debug("($id) SALDO_IVA_Comision $SALDO_IVA_Comision \n SALDO_IVA_Interes $SALDO_IVA_Interes \n SALDO_IVA_Otros  $SALDO_IVA_Otros \n SALDO_IVA_Moratorio $SALDO_IVA_Moratorio ");









        $this->aplicacion[$id]['ABONO_IVA']             = 0;

        $this->aplicacion[$id]['ABONO_Comision']        = 0;
        $this->aplicacion[$id]['ABONO_Interes']         = 0;
        $this->aplicacion[$id]['ABONO_Moratorio']       = 0;
        $this->aplicacion[$id]['ABONO_Capital']         = 0;
        $this->aplicacion[$id]['ABONO_Otros']           = 0;

        $this->aplicacion[$id]['ABONO_SegV']            = 0;
        $this->aplicacion[$id]['ABONO_SegD']            = 0;
        $this->aplicacion[$id]['ABONO_SegB']            = 0;
 
 
 
 
 
 
 
        
        // ¿Existen más coutas vencidas a la fecha, que la cuota que entró éste abono?
        $saldo_vencido_extra = 0;
        
        if(($this->aplicacion[$id]['Tipo'] == 'Abono') and (($this->aplicacion[$id]['Concepto']== 'Efectivo') or ($this->aplicacion[$id]['SubTipo'] == 'General')))
        {
        
                list($_saldo_capital,$_saldo_interes, $_saldo_comision, $_saldo_moratorio, $_saldo_otros, $_saldo_segv, $_saldo_segd, $_saldo_segb)= $this->obtener_saldos_por_vencer($id,$this->aplicacion[$id]['Fecha'],$NumCuota);

                $saldo_vencido_extra =  ($_saldo_capital +$_saldo_interes + $_saldo_comision + $_saldo_moratorio + $_saldo_otros + $_saldo_segv + $_saldo_segd + $_saldo_segb);
        

              // if($this->aplicacion[$id]['Fecha'] >= '2009-12-21')
              //28476     debug(" (Couta : $NumCuota| ".$this->aplicacion[$id]['Fecha']."|".$this->aplicacion[$id]['Abono'].") saldo_vencido_extra : [".$saldo_vencido_extra."] | (aplica_saldos_a_favor_anticipadamente : ".$this->aplica_saldos_a_favor_anticipadamente.") Por aplicar : ".$por_aplicar);



        }


        if(($this->aplica_saldos_a_favor_anticipadamente) and ($saldo_vencido_extra < 0.01))
        {




           if((empty($this->num_cuota_preprocesada)) or ($NumCuota > $this->num_cuota_preprocesada ))
             $this->num_cuota_preprocesada = $NumCuota;



 
 
/* 
           if(( $this->id_convenio > 0) and ($this->aplicacion[$id]['Fecha'] >= $this->fecha_inicio_convenio)  and ($this->aplicacion[$id]['Fecha'] <= $this->fecha_final_covenio) )
             {
                      
                      $this->aprelacion = array();
                      $this->aprelacion = str_split(strrev($this->prelacion));

             }
             else
             {

                      $this->aprelacion = array();
                      $this->aprelacion = str_split($this->prelacion);
             }      
*/


               $this->aprelacion = $this->verifica_prelacion_de_convenio($this->aplicacion[$id]['Fecha']);
           
           
             

            if(($por_aplicar <0))
                while($por_aplicar <0)
                {

                                foreach($this->aprelacion AS $TIPO)
                                        {
                                                //-----------------------------------------------------------------------------------------------------------------------//

                                                switch( $TIPO )
                                                {                       //-------------------------------------------------------------------------
                                                                        // Moratorios
                                                                        //-------------------------------------------------------------------------

                                                        case 'M' : $por_aplicar = $this->aplica_abonos_moratorios($por_aplicar,   $SALDO_Moratorio,  $SALDO_IVA_Moratorio,   $id, $NumCuota);        break;

                                                                        //-------------------------------------------------------------------------
                                                                        // Comisiones y Otros cargos
                                                                        //-------------------------------------------------------------------------
                                                        case 'A' : $por_aplicar = $this->aplica_abonos_comisiones($por_aplicar,   $SALDO_Comision,   $SALDO_IVA_Comision,   $id, $NumCuota);        break;

                                                                        //-------------------------------------------------------------------------
                                                                        // Intereses normales
                                                                        //-------------------------------------------------------------------------
                                                        case 'I' : $por_aplicar = $this->aplica_abonos_intereses($por_aplicar,    $SALDO_Interes,    $SALDO_IVA_Interes,    $id, $NumCuota);        break;

                                                                        //-------------------------------------------------------------------------
                                                                        // Cargos Extra
                                                                        //-------------------------------------------------------------------------
                                                        case 'E' : $por_aplicar = $this->aplica_abonos_otros_cargos($por_aplicar, $SALDO_Otros,      $SALDO_IVA_Otros,   $id, $NumCuota);        break;

                                                                        //-------------------------------------------------------------------------
                                                                        // Capital
                                                                        //-------------------------------------------------------------------------
                                                        case 'C' : $por_aplicar = $this->aplica_abonos_capital($por_aplicar,      $SALDO_Capital,    $id, $NumCuota);        break; 

                                                                        //-------------------------------------------------------------------------
                                                                        // Seguros
                                                                        //-------------------------------------------------------------------------
                                                        case 'S' : $por_aplicar = $this->aplica_abonos_seguros($por_aplicar,      $SALDO_SegV, $SALDO_SegD, $SALDO_SegB, $SALDO_IVA_SegV, $SALDO_IVA_SegD, $SALDO_IVA_SegB,    $id, $NumCuota);        break; 









                                                        //-----------------------------------------------------------------------------------------------------------------------//
                                                }

                                                if($por_aplicar<0)
                                                {
                                                        if(abs($por_aplicar)<0.01)
                                                        $por_aplicar = 0;


                                                }
                                        
                                        }


                        if($por_aplicar<0)
                        {

                                
                                if(($this->ultima_couta_preprocesada == $NumCuota) or (empty($NumCuota)))
                                {
                                    ++$this->num_cuota_preprocesada;
                                   // $this->num_cuota_preprocesada = $NumCuota+1;
                                
                                }
                                
                                if($this->num_cuota_preprocesada == $NumCuota)
                                {
                                   $this->num_cuota_preprocesada = $NumCuota+1;
                                
                                }
                                    
                                
                                
                                list($SALDO_Capital,    $SALDO_Interes,       $SALDO_Comision,       $SALDO_Moratorio,      $SALDO_Otros,     $SALDO_SegV,     $SALDO_SegD,     $SALDO_SegB,
                                                        $SALDO_IVA_Interes,   $SALDO_IVA_Comision,   $SALDO_IVA_Moratorio,  $SALDO_IVA_Otros, $SALDO_IVA_SegV, $SALDO_IVA_SegD, $SALDO_IVA_SegB)= $this->obtener_saldos_por_preprocesar($id,$NumCuota);
        
                                //debug("list($SALDO_Capital,$SALDO_Interes, $SALDO_Comision, $SALDO_Moratorio, $SALDO_Otros,| $SALDO_IVA_Interes,$SALDO_IVA_Comision,  $SALDO_IVA_Moratorio,  $SALDO_IVA_Otros )");
                                
                                
                                $this->ultima_couta_preprocesada = $NumCuota;
                                
                        
                        }
                        
                        
                        if(($SALDO_Capital + $SALDO_Interes + $SALDO_Comision + $SALDO_Moratorio + $SALDO_Otros + $SALDO_SegV + $SALDO_SegD + $SALDO_SegB) < 0.01)
                        {
                                break;
                        }


                }


        }
        else
        {
        


                if(($por_aplicar <0))
                {

/*
 
           if(( $this->id_convenio > 0) and ($this->aplicacion[$id]['Fecha'] >= $this->fecha_inicio_convenio)  and ($this->aplicacion[$id]['Fecha'] <= $this->fecha_final_covenio) )
             {
                      
                      
                      $this->aprelacion = array();
                      $this->aprelacion = str_split(strrev($this->prelacion));

             }
             else
             {

                      $this->aprelacion = array();
                      $this->aprelacion = str_split($this->prelacion);
             } 
*/    
      //  if($this->aplicacion[$id]['Fecha'] >='2010-03-01')


               $this->aprelacion = $this->verifica_prelacion_de_convenio($this->aplicacion[$id]['Fecha']);
           

                                foreach($this->aprelacion AS $TIPO)
                                        {
                                                //-----------------------------------------------------------------------------------------------------------------------//





                                                switch( $TIPO )
                                                {                       //-------------------------------------------------------------------------
                                                                        // Moratorios
                                                                        //-------------------------------------------------------------------------
                                                        case 'M' : $por_aplicar = $this->aplica_abonos_moratorios($por_aplicar,   $SALDO_Moratorio,  $SALDO_IVA_Moratorio,   $id, $NumCuota);        
                                                                   break;

                                                                        //-------------------------------------------------------------------------
                                                                        // Comisiones y Otros cargos
                                                                        //-------------------------------------------------------------------------
                                                        case 'A' : $por_aplicar = $this->aplica_abonos_comisiones($por_aplicar,   $SALDO_Comision,   $SALDO_IVA_Comision,   $id, $NumCuota);        
                                                                   break;

                                                                        //-------------------------------------------------------------------------
                                                                        // Intereses normales
                                                                        //-------------------------------------------------------------------------
                                                        case 'I' : $por_aplicar = $this->aplica_abonos_intereses($por_aplicar,    $SALDO_Interes,    $SALDO_IVA_Interes,    $id, $NumCuota);        
                                                                   break;

                                                                        //-------------------------------------------------------------------------
                                                                        // Cargos Extra
                                                                        //-------------------------------------------------------------------------
                                                        case 'E' : $por_aplicar = $this->aplica_abonos_otros_cargos($por_aplicar, $SALDO_Otros,      $SALDO_IVA_Otros,   $id, $NumCuota);        
                                                                   break;

                                                                        //-------------------------------------------------------------------------
                                                                        // Capital
                                                                        //-------------------------------------------------------------------------
                                                        case 'C' : $por_aplicar = $this->aplica_abonos_capital($por_aplicar,      $SALDO_Capital,    $id, $NumCuota);        
                                                                   break; 

                                                                        //-------------------------------------------------------------------------
                                                                        // Seguros
                                                                        //-------------------------------------------------------------------------
                                                        case 'S' : $por_aplicar = $this->aplica_abonos_seguros($por_aplicar,      $SALDO_SegV, $SALDO_SegD, $SALDO_SegB, $SALDO_IVA_SegV, $SALDO_IVA_SegD, $SALDO_IVA_SegB,    $id, $NumCuota);        break; 


                                                        //-----------------------------------------------------------------------------------------------------------------------//


                                                }

                                                if($por_aplicar<0)
                                                {
                                                        if(abs($por_aplicar)<0.01)
                                                        $por_aplicar = 0;
                                                        continue;

                                                }



                                        }



                }
        }


      if(($this->aplicacion[$id]['Tipo'] == 'Abono') and (($this->aplicacion[$id]['Concepto']== 'Efectivo') or ($this->aplicacion[$id]['SubTipo'] == 'General')))
      {
/**/

         $this->SumaAbonoCapital        +=      $this->aplicacion[$id]['ABONO_Capital'];

         $this->SumaAbonoInteres        +=      $this->aplicacion[$id]['ABONO_Interes'];
         $this->SumaAbonoIVAInteres     +=      $this->aplicacion[$id]['ABONO_Interes_IVA'];

         $this->SumaAbonoOtros          +=      ($this->aplicacion[$id]['ABONO_Otros']);
         $this->SumaAbonoIVAOtros       +=      ($this->aplicacion[$id]['ABONO_Otros_IVA']);


         $this->SumaAbonoComision       +=      ($this->aplicacion[$id]['ABONO_Comision']);
         $this->SumaAbonoIVAComision    +=      ($this->aplicacion[$id]['ABONO_Comision_IVA']);




        $this->SumaAbonoSegV            +=      ($this->aplicacion[$id]['ABONO_SegV']);
        $this->SumaAbonoIVASegV         +=      ($this->aplicacion[$id]['ABONO_SegV_IVA']);

        $this->SumaAbonoSegD            +=      ($this->aplicacion[$id]['ABONO_SegD']);
        $this->SumaAbonoIVASegD         +=      ($this->aplicacion[$id]['ABONO_SegD_IVA']); 

        $this->SumaAbonoSegB            +=      ($this->aplicacion[$id]['ABONO_SegB']);
        $this->SumaAbonoIVASegB         +=      ($this->aplicacion[$id]['ABONO_SegB_IVA']);




        $this->SumaAbonoIMB             +=      round(($this->aplicacion[$id]['ABONO_Moratorio'] + $this->aplicacion[$id]['ABONO_Moratorio_IVA']),2);
        $this->SumaAbonoIM              +=      round(($this->aplicacion[$id]['ABONO_Moratorio'])       ,2);
        $this->SumaAbonoIVAIM           +=      round(($this->aplicacion[$id]['ABONO_Moratorio_IVA'])   ,2);






      }
      else
      if(($this->aplicacion[$id]['Tipo'] == 'Abono') and ($this->aplicacion[$id]['Concepto']== 'Documento') and ($this->aplicacion[$id]['SubTipo'] != 'General'))
      {


      //-----------------------------------------------------------------------------------------------------
      // Notas de crédito 
      //-----------------------------------------------------------------------------------------------------



                $por_aplicar    =  $this->aplicacion[$id]['SaldoFavorAplicado'];
      
                $this->aplicacion[$id]['ABONO_Capital']          +=   $this->aplicacion[$id]['NOTA_CREDITO_Capital'];   
                $this->aplicacion[$id]['ABONO_Interes']          +=   $this->aplicacion[$id]['NOTA_CREDITO_Interes' ];      
                $this->aplicacion[$id]['ABONO_Moratorio']        +=   $this->aplicacion[$id]['NOTA_CREDITO_Moratorios']; 
                $this->aplicacion[$id]['ABONO_Comision']         +=   $this->aplicacion[$id]['NOTA_CREDITO_Comision'];      
                $this->aplicacion[$id]['ABONO_Otros']            +=   $this->aplicacion[$id]['NOTA_CREDITO_Otros'];      
      
                $this->aplicacion[$id]['ABONO_SegV']             +=   $this->aplicacion[$id]['NOTA_CREDITO_SegV'];      
                $this->aplicacion[$id]['ABONO_SegD']             +=   $this->aplicacion[$id]['NOTA_CREDITO_SegD'];      
                $this->aplicacion[$id]['ABONO_SegB']             +=   $this->aplicacion[$id]['NOTA_CREDITO_SegB'];      
                
                
                
                
                $this->aplicacion[$id]['ABONO_Interes_IVA']     +=   $this->aplicacion[$id]['NOTA_CREDITO_IVA_Interes'];
                $this->aplicacion[$id]['ABONO_Moratorio_IVA']   +=   $this->aplicacion[$id]['NOTA_CREDITO_IVA_Moratorios'];  
                $this->aplicacion[$id]['ABONO_Comision_IVA']    +=   $this->aplicacion[$id]['NOTA_CREDITO_IVA_Comision'];
                $this->aplicacion[$id]['ABONO_Otros_IVA']       +=   $this->aplicacion[$id]['NOTA_CREDITO_IVA_Otros'];

                 
                $this->aplicacion[$id]['ABONO_SegV_IVA']        +=   $this->aplicacion[$id]['NOTA_CREDITO_IVA_SegV'];      
                $this->aplicacion[$id]['ABONO_SegD_IVA']        +=   $this->aplicacion[$id]['NOTA_CREDITO_IVA_SegD'];      
                $this->aplicacion[$id]['ABONO_SegB_IVA']        +=   $this->aplicacion[$id]['NOTA_CREDITO_IVA_SegB'];      





                 
                 
                $this->SumaAbonoCapital         +=      $this->aplicacion[$id]['NOTA_CREDITO_Capital'];

                $this->SumaAbonoInteres         +=      $this->aplicacion[$id]['NOTA_CREDITO_Interes' ]; 
                $this->SumaAbonoIVAInteres      +=      $this->aplicacion[$id]['NOTA_CREDITO_IVA_Interes'];

                $this->SumaAbonoIMB             +=      $this->aplicacion[$id]['NOTA_CREDITO_Moratorios']       + $this->aplicacion[$id]['NOTA_CREDITO_IVA_Moratorios'];
                $this->SumaAbonoIM              +=      $this->aplicacion[$id]['NOTA_CREDITO_Moratorios'];
                $this->SumaAbonoIVAIM           +=      $this->aplicacion[$id]['NOTA_CREDITO_IVA_Moratorios'];


                $this->SumaAbonoComision        +=      $this->aplicacion[$id]['NOTA_CREDITO_Comision'];        
                $this->SumaAbonoIVAComision     +=      $this->aplicacion[$id]['NOTA_CREDITO_IVA_Comision'];


                $this->SumaAbonoOtros           +=      $this->aplicacion[$id]['NOTA_CREDITO_Otros'];
                $this->SumaAbonoIVAOtros        +=      $this->aplicacion[$id]['NOTA_CREDITO_IVA_Otros'];
                


                
                $this->SumaAbonoSegV            +=      $this->aplicacion[$id]['NOTA_CREDITO_SegV'];
                $this->SumaAbonoIVASegV         +=      $this->aplicacion[$id]['NOTA_CREDITO_IVA_SegV'];

                $this->SumaAbonoSegD            +=      $this->aplicacion[$id]['NOTA_CREDITO_SegD'];
                $this->SumaAbonoIVASegD         +=      $this->aplicacion[$id]['NOTA_CREDITO_IVA_SegD']; 

                $this->SumaAbonoSegB            +=      $this->aplicacion[$id]['NOTA_CREDITO_SegB'];
                $this->SumaAbonoIVASegB         +=      $this->aplicacion[$id]['NOTA_CREDITO_IVA_SegB'];
                
                
                
                
                
                
                
                
                
//-------------------------------------------------------------------------------------------------------------------------------------

                $this->Suma_Abono_Capital_Documento             +=      $this->aplicacion[$id]['NOTA_CREDITO_Capital'];

                $this->Suma_Abono_Interes_Documento             +=      $this->aplicacion[$id]['NOTA_CREDITO_Interes' ]; 
                $this->Suma_Abono_IVA_Interes_Documento         +=      $this->aplicacion[$id]['NOTA_CREDITO_IVA_Interes'];

                $this->Suma_Abono_Moratorio_Documento           +=      $this->aplicacion[$id]['NOTA_CREDITO_Moratorios'];
                $this->Suma_Abono_IVA_Moratorio_Documento       +=      $this->aplicacion[$id]['NOTA_CREDITO_IVA_Moratorios'];


                $this->Suma_Abono_Comision_Documento            +=      $this->aplicacion[$id]['NOTA_CREDITO_Comision'];        
                $this->Suma_Abono_IVA_Comision_Documento        +=      $this->aplicacion[$id]['NOTA_CREDITO_IVA_Comision'];


                $this->Suma_Abono_Otros_Documento               +=      $this->aplicacion[$id]['NOTA_CREDITO_Otros'];
                $this->Suma_Abono_IVA_Otros_Documento           +=      $this->aplicacion[$id]['NOTA_CREDITO_IVA_Otros'];
                
                
                $this->Suma_Abono_SegV_Documento                +=      $this->aplicacion[$id]['NOTA_CREDITO_SegV'];
                $this->Suma_Abono_IVA_SegV_Documento            +=      $this->aplicacion[$id]['NOTA_CREDITO_IVA_SegV'];
                
                $this->Suma_Abono_SegD_Documento                +=      $this->aplicacion[$id]['NOTA_CREDITO_SegD'];
                $this->Suma_Abono_IVA_SegD_Documento            +=      $this->aplicacion[$id]['NOTA_CREDITO_IVA_SegD'];
                
                $this->Suma_Abono_SegB_Documento                +=      $this->aplicacion[$id]['NOTA_CREDITO_SegB'];
                $this->Suma_Abono_IVA_SegB_Documento            +=      $this->aplicacion[$id]['NOTA_CREDITO_IVA_SegB'];
                
                
                
                
//-------------------------------------------------------------------------------------------------------------------------------------


       } 
       
       
       
       $this->aplicacion[$id]['SALDO_MOV_Pendiente_Aplicar']= $por_aplicar + $this->aplicacion[($id-1)]['SALDO_MOV_Pendiente_Aplicar'] - $this->aplicacion[$id]['SaldoFavorAplicado'];


       $this->aplicacion[$id]['ABONO_IVA'] =$this->aplicacion[$id]['ABONO_Interes_IVA'  ]+                
                                            $this->aplicacion[$id]['ABONO_Moratorio_IVA']+      
                                            $this->aplicacion[$id]['ABONO_Comision_IVA' ]+       
                                            $this->aplicacion[$id]['ABONO_Otros_IVA'    ]+
                                            
                                            $this->aplicacion[$id]['ABONO_SegV_IVA']+
                                            $this->aplicacion[$id]['ABONO_SegD_IVA']+
                                            $this->aplicacion[$id]['ABONO_SegB_IVA'];
                                            
                                            
                                            
                                            
                                            
                                            
        //-------------------------------------------------------------
        // Eliminar cantidades inferiores a un centavo 
        //-------------------------------------------------------------
        if($this->aplicacion[$id]['SALDO_General'] <0)
        {       
                    if(abs($this->aplicacion[$id]['SALDO_General']) <0.01)
                           $this->aplicacion[$id]['SALDO_General'] = 0;
                   
        }
        //-------------------------------------------------------------


        $this->SALDO_General    =       $this->aplicacion[$id]['SALDO_General'];                                                                 


        $this->aplicacion[$id]['SALDO_MOV_Capital']             =       $this->aplicacion[$id]['ABONO_Capital']    +    $this->aplicacion[$id]['CARGO_Capital']  + $this->aplicacion[($id-1)]['SALDO_MOV_Capital'];             
        $this->aplicacion[$id]['SALDO_MOV_Interes']             =       $this->aplicacion[$id]['ABONO_Interes']    +    $this->aplicacion[$id]['CARGO_Interes' ] + $this->aplicacion[($id-1)]['SALDO_MOV_Interes'];             
        $this->aplicacion[$id]['SALDO_MOV_Moratorio']           =       $this->aplicacion[$id]['ABONO_Moratorio']  +    $this->aplicacion[$id]['IM']             +   ($this->aplicacion[$id]['CARGO_Moratorio'])  + $this->aplicacion[($id-1)]['SALDO_MOV_Moratorio'];

        $this->aplicacion[$id]['SALDO_MOV_Comision']            =       $this->aplicacion[$id]['ABONO_Comision']   +    $this->aplicacion[$id]['CARGO_Comision'] + $this->aplicacion[($id-1)]['SALDO_MOV_Comision'];
        $this->aplicacion[$id]['SALDO_MOV_Otros']               =       $this->aplicacion[$id]['ABONO_Otros']      +    $this->aplicacion[$id]['CARGO_Otros']    + $this->aplicacion[($id-1)]['SALDO_MOV_Otros'];



        $this->aplicacion[$id]['SALDO_MOV_SegV']               =       $this->aplicacion[$id]['ABONO_SegV']      +    $this->aplicacion[$id]['CARGO_SegV']    + $this->aplicacion[($id-1)]['SALDO_MOV_SegV'];
        $this->aplicacion[$id]['SALDO_MOV_SegD']               =       $this->aplicacion[$id]['ABONO_SegD']      +    $this->aplicacion[$id]['CARGO_SegD']    + $this->aplicacion[($id-1)]['SALDO_MOV_SegD'];
        $this->aplicacion[$id]['SALDO_MOV_SegB']               =       $this->aplicacion[$id]['ABONO_SegB']      +    $this->aplicacion[$id]['CARGO_SegB']    + $this->aplicacion[($id-1)]['SALDO_MOV_SegB'];









        $this->aplicacion[$id]['SALDO_MOV_IVA_Interes']         =       $this->aplicacion[$id]['CARGO_IVA_Interes']     + $this->aplicacion[$id]['ABONO_Interes_IVA']          +$this->aplicacion[($id-1)]['SALDO_MOV_IVA_Interes']  ;
        $this->aplicacion[$id]['SALDO_MOV_IVA_Comision']        =       $this->aplicacion[$id]['CARGO_IVA_Comision']    + $this->aplicacion[$id]['ABONO_Comision_IVA']         +$this->aplicacion[($id-1)]['SALDO_MOV_IVA_Comision'] ;
        $this->aplicacion[$id]['SALDO_MOV_IVA_Otros']           =       $this->aplicacion[$id]['CARGO_IVA_Otros']       + $this->aplicacion[$id]['ABONO_Otros_IVA']            +$this->aplicacion[($id-1)]['SALDO_MOV_IVA_Otros']    ;

        $this->aplicacion[$id]['SALDO_MOV_IVA_Moratorio']       =       ($this->aplicacion[$id]['CARGO_IVA_Moratorio']      + 
                                                                         $this->aplicacion[$id]['ABONO_Moratorio_IVA']      +
                                                                         $this->aplicacion[($id-1)]['SALDO_MOV_IVA_Moratorio']);


        $this->aplicacion[$id]['SALDO_MOV_IVA_SegV']            =       $this->aplicacion[$id]['CARGO_IVA_SegV']    + $this->aplicacion[$id]['ABONO_SegV_IVA']      +    $this->aplicacion[($id-1)]['SALDO_MOV_IVA_SegV'];
        $this->aplicacion[$id]['SALDO_MOV_IVA_SegD']            =       $this->aplicacion[$id]['CARGO_IVA_SegD']    + $this->aplicacion[$id]['ABONO_SegD_IVA']      +    $this->aplicacion[($id-1)]['SALDO_MOV_IVA_SegD'];
        $this->aplicacion[$id]['SALDO_MOV_IVA_SegB']            =       $this->aplicacion[$id]['CARGO_IVA_SegB']    + $this->aplicacion[$id]['ABONO_SegB_IVA']      +    $this->aplicacion[($id-1)]['SALDO_MOV_IVA_SegB'];






        $this->aplicacion[$id]['SALDO_MOV_IVA']                 += $this->aplicacion[$id]['SALDO_MOV_IVA_Interes']+
                                                                   $this->aplicacion[$id]['SALDO_MOV_IVA_Moratorio']+
                                                                   $this->aplicacion[$id]['SALDO_MOV_IVA_Comision']+
                                                                   $this->aplicacion[$id]['SALDO_MOV_IVA_Otros'];       
        

                                                                                                



        $this->aplicacion[$id]['SALDO_MOV_General'] =          ($this->aplicacion[$id]['SALDO_MOV_Capital']            +               
                                                                $this->aplicacion[$id]['SALDO_MOV_Interes']            +       
                                                                $this->aplicacion[$id]['SALDO_MOV_Moratorio']          +               
                                                                $this->aplicacion[$id]['SALDO_MOV_Comision']           +       
                                                                $this->aplicacion[$id]['SALDO_MOV_Otros']              +       

                                                                $this->aplicacion[$id]['SALDO_MOV_SegV']               +       
                                                                $this->aplicacion[$id]['SALDO_MOV_SegD']               +       
                                                                $this->aplicacion[$id]['SALDO_MOV_SegB']               +       



                                                                $this->aplicacion[$id]['SALDO_MOV_IVA_Interes']        +               
                                                                $this->aplicacion[$id]['SALDO_MOV_IVA_Moratorio']      +       
                                                                $this->aplicacion[$id]['SALDO_MOV_IVA_Comision']       +       
                                                                $this->aplicacion[$id]['SALDO_MOV_IVA_Otros']          +     
                                                                
                                                                $this->aplicacion[$id]['SALDO_MOV_IVA_SegV']           +       
                                                                $this->aplicacion[$id]['SALDO_MOV_IVA_SegD']           +       
                                                                $this->aplicacion[$id]['SALDO_MOV_IVA_SegB']           );                 








/**/

//debug($this->numcompra);


        if(abs($this->aplicacion[$id]['SALDO_MOV_Capital']              ) <0.001  )    $this->aplicacion[$id]['SALDO_MOV_Capital']      =0;
        if(abs($this->aplicacion[$id]['SALDO_MOV_Interes']              ) <0.001  )    $this->aplicacion[$id]['SALDO_MOV_Interes']      =0;
        if(abs($this->aplicacion[$id]['SALDO_MOV_Moratorio']            ) <0.001  )    $this->aplicacion[$id]['SALDO_MOV_Moratorio']    =0;
        if(abs($this->aplicacion[$id]['SALDO_MOV_Comision']             ) <0.001  )    $this->aplicacion[$id]['SALDO_MOV_Comision']     =0;
        if(abs($this->aplicacion[$id]['SALDO_MOV_Otros']                ) <0.001  )    $this->aplicacion[$id]['SALDO_MOV_Otros']        =0;

        if(abs($this->aplicacion[$id]['SALDO_MOV_SegV']                 ) <0.001  )     $this->aplicacion[$id]['SALDO_MOV_SegV']        =0;
        if(abs($this->aplicacion[$id]['SALDO_MOV_SegD']                 ) <0.001  )     $this->aplicacion[$id]['SALDO_MOV_SegD']        =0;
        if(abs($this->aplicacion[$id]['SALDO_MOV_SegB']                 ) <0.001  )     $this->aplicacion[$id]['SALDO_MOV_SegB']        =0;

        if(abs($this->aplicacion[$id]['SALDO_MOV_General']              ) <0.001  )    $this->aplicacion[$id]['SALDO_MOV_General']      =0;



        if(abs($this->aplicacion[$id]['SALDO_MOV_IVA_Interes']          ) <0.001 )    $this->aplicacion[$id]['SALDO_MOV_IVA_Interes']   =0;
        if(abs($this->aplicacion[$id]['SALDO_MOV_IVA_Moratorio']        ) <0.001 )    $this->aplicacion[$id]['SALDO_MOV_IVA_Moratorio'] =0;
        if(abs($this->aplicacion[$id]['SALDO_MOV_IVA_Comision']         ) <0.001 )    $this->aplicacion[$id]['SALDO_MOV_IVA_Comision']  =0;
        if(abs($this->aplicacion[$id]['SALDO_MOV_IVA_Otros']            ) <0.001 )    $this->aplicacion[$id]['SALDO_MOV_IVA_Otros']     =0;

        if(abs($this->aplicacion[$id]['SALDO_MOV_IVA_SegV']             ) <0.001  )   $this->aplicacion[$id]['SALDO_MOV_IVA_SegV']      =0;
        if(abs($this->aplicacion[$id]['SALDO_MOV_IVA_SegD']             ) <0.001  )   $this->aplicacion[$id]['SALDO_MOV_IVA_SegD']      =0;
        if(abs($this->aplicacion[$id]['SALDO_MOV_IVA_SegB']             ) <0.001  )   $this->aplicacion[$id]['SALDO_MOV_IVA_SegB']      =0;

        if(abs($this->aplicacion[$id]['SALDO_MOV_IVA']                  ) <0.001 )    $this->aplicacion[$id]['SALDO_MOV_IVA']           =0;





        $this->mora_maxima = max($this->mora_maxima, $this->dias_mora);
        //$this->mora_maxima = max($this->mora_maxima,$this->aplicacion[$id]['DiasAtrasoAcum']);




        $this->SaldoGeneralVencido = $this->aplicacion[$id]['SALDO_MOV_General'];
        $this->SALDO_MOV_General   = $this->aplicacion[$id]['SALDO_MOV_General'];


        $this->SaldoCapital     = $this->aplicacion[$id]['SALDO_MOV_Capital']   ;
        $this->SaldoInteres     = $this->aplicacion[$id]['SALDO_MOV_Interes']   ;
        $this->SaldoIM          = $this->aplicacion[$id]['SALDO_MOV_Moratorio'] ;
        $this->SaldoComision    = $this->aplicacion[$id]['SALDO_MOV_Comision']  ;
        $this->SaldoOtros       = $this->aplicacion[$id]['SALDO_MOV_Otros']     ;

        $this->SaldoSegV        = $this->aplicacion[$id]['SALDO_MOV_SegV']      ;
        $this->SaldoSegD        = $this->aplicacion[$id]['SALDO_MOV_SegD']      ;
        $this->SaldoSegB        = $this->aplicacion[$id]['SALDO_MOV_SegB']      ;



        $this->SaldoIVAInteres  = $this->aplicacion[$id]['SALDO_MOV_IVA_Interes']  ;    
        $this->SaldoIVAIM       = $this->aplicacion[$id]['SALDO_MOV_IVA_Moratorio'];
        $this->SaldoIVAComision = $this->aplicacion[$id]['SALDO_MOV_IVA_Comision'] ;    
        $this->SaldoIVAOtros    = $this->aplicacion[$id]['SALDO_MOV_IVA_Otros']    ;    

        $this->SaldoIVASegV        = $this->aplicacion[$id]['SALDO_MOV_IVA_SegV']  ;
        $this->SaldoIVASegD        = $this->aplicacion[$id]['SALDO_MOV_IVA_SegD']  ;
        $this->SaldoIVASegB        = $this->aplicacion[$id]['SALDO_MOV_IVA_SegB']  ;






        $this->SumaIVA = $this->SumaIVAInteres          +
                         $this->SumaIVAMoratorio        +
                         $this->SumaIVAComision         +
                         $this->SumaIVAOtros            +

                         $this->SumaIVASegV             +             
                         $this->SumaIVASegD             +
                         $this->SumaIVASegB             ;
                         
                         

        $this->SaldoIVA         = $this->SaldoIVAIM       +    
                                  $this->SaldoIVAComision + 
                                  $this->SaldoIVAInteres  +
                                  $this->SaldoIVAOtros    +   

                                  $this->SaldoIVASegV     +             
                                  $this->SaldoIVASegD     +
                                  $this->SaldoIVASegB     ;


        $this->SumaAbonoIVA     = $this->SumaAbonoIVAIM         +
                                  $this->SumaAbonoIVAComision   + 
                                  $this->SumaAbonoIVAInteres    +
                                  $this->SumaAbonoIVAOtros      +

                                  $this->SumaAbonoIVASegV       +
                                  $this->SumaAbonoIVASegD       +
                                  $this->SumaAbonoIVASegB       ;




        $this->aplicacion[$id]['SaldoGlobalCapital']    = $this->aplicacion[$id]['SaldoCapitalPorVencer']  + $this->aplicacion[$id]['SALDO_MOV_Capital'];               
        $this->aplicacion[$id]['SaldoGlobalInteres']    = $this->aplicacion[$id]['SaldoInteresPorVencer']  + $this->aplicacion[$id]['SALDO_MOV_Interes'];       
        $this->aplicacion[$id]['SaldoGlobalComision']   = $this->aplicacion[$id]['SaldoComisionPorVencer'] + $this->aplicacion[$id]['SALDO_MOV_Comision'];
        $this->aplicacion[$id]['SaldoGlobalMoratorio']  = $this->aplicacion[$id]['SALDO_MOV_Moratorio'];
        $this->aplicacion[$id]['SaldoGlobalOtros']      = $this->aplicacion[$id]['SALDO_MOV_Otros'];



        $this->aplicacion[$id]['SaldoGlobalSegV']       = $this->aplicacion[$id]['SaldoSegVPorVencer'] + $this->aplicacion[$id]['SALDO_MOV_SegV'];
        $this->aplicacion[$id]['SaldoGlobalSegD']       = $this->aplicacion[$id]['SaldoSegDPorVencer'] + $this->aplicacion[$id]['SALDO_MOV_SegD'];
        $this->aplicacion[$id]['SaldoGlobalSegB']       = $this->aplicacion[$id]['SaldoSegBPorVencer'] + $this->aplicacion[$id]['SALDO_MOV_SegB'];




        
        
        $this->aplicacion[$id]['SaldoGlobal_IVA_Interes']    = $this->aplicacion[$id]['SaldoInteres_IVA_PorVencer']  + $this->aplicacion[$id]['SALDO_MOV_IVA_Interes'];       
        $this->aplicacion[$id]['SaldoGlobal_IVA_Comision']   = $this->aplicacion[$id]['SaldoComision_IVA_PorVencer'] + $this->aplicacion[$id]['SALDO_MOV_IVA_Comision'];
        $this->aplicacion[$id]['SaldoGlobal_IVA_Moratorio']  = $this->aplicacion[$id]['SALDO_MOV_IVA_Moratorio'];
        $this->aplicacion[$id]['SaldoGlobal_IVA_Otros']      = $this->aplicacion[$id]['SALDO_MOV_IVA_Otros'];



        $this->aplicacion[$id]['SaldoGlobal_IVA_SegV']       = $this->aplicacion[$id]['SaldoSegV_IVA_PorVencer'] + $this->aplicacion[$id]['SALDO_MOV_IVA_SegV'];
        $this->aplicacion[$id]['SaldoGlobal_IVA_SegD']       = $this->aplicacion[$id]['SaldoSegD_IVA_PorVencer'] + $this->aplicacion[$id]['SALDO_MOV_IVA_SegD'];
        $this->aplicacion[$id]['SaldoGlobal_IVA_SegB']       = $this->aplicacion[$id]['SaldoSegB_IVA_PorVencer'] + $this->aplicacion[$id]['SALDO_MOV_IVA_SegB'];






/*       
        
        $this->aplicacion[$id]['SaldoGlobalIVA']        = ($this->aplicacion[$id]['SaldoGlobalInteres']     * $this->iva_pcnt_intereses  ) +    
                                                          ($this->aplicacion[$id]['SaldoGlobalComision']    * $this->iva_pcnt_comisiones ) +                                                     
                                                          ($this->aplicacion[$id]['SaldoGlobalMoratorio']   * $this->iva_pcnt_moratorios ) +
                                                          ($this->aplicacion[$id]['SaldoGlobalOtros']       * $this->iva_pcnt_comisiones ) ;
        $_SaldoGlobalIVA = $this->aplicacion[$id]['SaldoGlobalIVA'];

 */


        $this->aplicacion[$id]['SaldoGlobalIVA']        = ( $this->aplicacion[$id]['SaldoGlobal_IVA_Interes']   +    
                                                            $this->aplicacion[$id]['SaldoGlobal_IVA_Comision']  +                                                     
                                                            $this->aplicacion[$id]['SaldoGlobal_IVA_Moratorio'] +
                                                            $this->aplicacion[$id]['SaldoGlobal_IVA_Otros']     +
                                                            $this->aplicacion[$id]['SaldoGlobal_IVA_SegV']      +
                                                            $this->aplicacion[$id]['SaldoGlobal_IVA_SegD']      +
                                                            $this->aplicacion[$id]['SaldoGlobal_IVA_SegB']   ) ;







/*
if(abs($this->aplicacion[$id]['SaldoGlobalIVA'] - $_SaldoGlobalIVA )>0.001)
{
debug("A [$id]  ".$_SaldoGlobalIVA );

debug("B [$id]  ".$this->aplicacion[$id]['SaldoGlobalIVA']);
}
*/


        $this->SaldoGlobalCapital       = $this->aplicacion[$id]['SaldoGlobalCapital']; 
        $this->SaldoGlobalInteres       = $this->aplicacion[$id]['SaldoGlobalInteres']; 
        $this->SaldoGlobalComision      = $this->aplicacion[$id]['SaldoGlobalComision'];        
        $this->SaldoGlobalOtros         = $this->aplicacion[$id]['SaldoGlobalOtros'];   

        $this->SaldoGlobalMoratorio     = $this->aplicacion[$id]['SaldoGlobalMoratorio'];
        $this->SaldoGlobalIVA           = $this->aplicacion[$id]['SaldoGlobalIVA'];



        $this->SaldoGlobalSegV         = $this->aplicacion[$id]['SaldoGlobalSegV'];   
        $this->SaldoGlobalSegD         = $this->aplicacion[$id]['SaldoGlobalSegD'];   
        $this->SaldoGlobalSegB         = $this->aplicacion[$id]['SaldoGlobalSegB'];   


        $this->SaldoGlobalGeneral =   ( $this->SaldoGlobalCapital +
                                        $this->SaldoGlobalInteres +
                                        $this->SaldoGlobalComision+
                                        $this->SaldoGlobalOtros   +

                                        $this->SaldoGlobalSegV    +                                    
                                        $this->SaldoGlobalSegD    +                                    
                                        $this->SaldoGlobalSegB    +                                    

                                        $this->SaldoGlobalIVA );     
                                   



        $this->SaldoFavorPendiente = $this->aplicacion[$id]['SALDO_MOV_Pendiente_Aplicar'];


        $this->SaldoCapitalPorVencer    = $this->aplicacion[$id]['SaldoCapitalPorVencer'] ;
        $this->SaldoInteresPorVencer    = $this->aplicacion[$id]['SaldoInteresPorVencer'] ; 
        $this->SaldoComisionPorVencer   = $this->aplicacion[$id]['SaldoComisionPorVencer'];


        $this->Saldo_IVA_InteresPorVencer  = $this->aplicacion[$id]['SaldoInteres_IVA_PorVencer'] ;            
        $this->Saldo_IVA_ComisionPorVencer = $this->aplicacion[$id]['SaldoComision_IVA_PorVencer'];   



        $this->SaldoSegVPorVencer    = $this->aplicacion[$id]['SaldoSegVPorVencer'] ;
        $this->SaldoSegDPorVencer    = $this->aplicacion[$id]['SaldoSegDPorVencer'] ;
        $this->SaldoSegBPorVencer    = $this->aplicacion[$id]['SaldoSegBPorVencer'] ;







        if($this->SaldoGeneralVencido<0.01) $this->dias_mora = 0;
        
        
        $this->saldos_parciales_por_cuota($id,$NumCuota);
        

        
        return;

}
//-------------------------------------------------------------------------------------------------------------------------------------------------------------
function obtener_antiguedad_saldos()
{

        $saldos_vencidos = array();

        if(count($this->saldos_cuota))
                foreach( $this->saldos_cuota AS $NumCuota => $row)
                {
                        if($NumCuota > $this->numcargosvencidos)
                          break;
                        
                        
                        
                        
                        //debug("NumCuota $NumCuota : ".$row['DiasAtrasoAcum']."  ".$row['SALDO_MOV_General'] );
                        if($row['SALDO_MOV_General'] <= 0)
                        {
                                unset($saldos_vencidos);
                                $saldos_vencidos = array();
                        }
                        else
                        {
                                $dias = $row['DiasAtrasoAcum'];
                                $saldos_vencidos[$dias] += $row['SaldoParcial'];


                        }

                }

        unset($row);
        
        $antiguedad_saldos = array();
        
        
        
        //debug(" saldos_vencidos : ".print_r($saldos_vencidos,1));

        if(count($saldos_vencidos))
                foreach( $saldos_vencidos AS $dias_mora => $saldo)
                {
                        
                        
                        switch(true)
                        { 
                          case ( $dias_mora == 0                                 ):  $antiguedad_saldos['0']     += $saldo ; break; 
                          case (($dias_mora >= 1  )  and ($dias_mora <= 7  )     ):  $antiguedad_saldos['1']     += $saldo ; break; 
                          case (($dias_mora >= 8  )  and ($dias_mora <= 30 )     ):  $antiguedad_saldos['2']     += $saldo ; break; 
                          case (($dias_mora >= 31 )  and ($dias_mora <= 60 )     ):  $antiguedad_saldos['3']     += $saldo ; break; 
                          case (($dias_mora >= 61 )  and ($dias_mora <= 90 )     ):  $antiguedad_saldos['4']     += $saldo ; break; 
                          case (($dias_mora >= 91 )  and ($dias_mora <= 120)     ):  $antiguedad_saldos['5']     += $saldo ; break; 
                          case (($dias_mora >= 121)                              ):  $antiguedad_saldos['6']     += $saldo ; break; 

                        } 
                }
        
        
        
        
        return($antiguedad_saldos);
        



}


//-------------------------------------------------------------------------------------------------------------------------------------------------------------

function obtener_prelacion_abono_por_convenio($fecha)
{

    $aprelacion = array();    
    if(count($this->convenios_pago) > 0)
    {
    
        foreach($this->convenios_pago AS $row)
        {


             if(($fecha >= $row['fecha_inicio_convenio']) and ($fecha <= $row['fecha_final_covenio'] ))
             {
                      
                      
                      $aprelacion = str_split(strrev($this->prelacion));
                      return($aprelacion);

             }


             if($fecha<$row['fecha_inicio_convenio'])
             {
                   
                   $aprelacion = str_split($this->prelacion);
                   return($aprelacion); 
             
             }


        }
        $aprelacion = str_split($this->prelacion);
        return($aprelacion); 
  
  
  }

  $aprelacion = str_split($this->prelacion);
  return($aprelacion); 
}
//-------------------------------------------------------------------------------------------------------------------------------------------------------------

function obtener_desgloce_abonos_efectivo()
{

        $abonos = array();




        foreach($this->aplicacion as $id => $data)
        {
        
        
                if(($data['Tipo'] == 'Abono') and ($data['Concepto']== 'Efectivo'))
                {
        
                        $id_pago = $data['ID'];
                        
                        if(empty($abonos[$id_pago]['Descripcion']))
                        $abonos[$id_pago]['Descripcion']        =       $data['Descripcion'];

                        $abonos[$id_pago]['Fecha']              =       $data['Fecha'];

                        if(empty($abonos[$id_pago]['Dias_Mora']))
                        $abonos[$id_pago]['Dias_Mora']          =       $data['DiasAtrasoAcum'];

                        $abonos[$id_pago]['Monto']              +=      $data['Monto'];
                        
                        $abonos[$id_pago]['Capital']            +=      $data['ABONO_Capital']          ;
                        
                        
                        $abonos[$id_pago]['Interes']            +=      $data['ABONO_Interes']          ;
                        $abonos[$id_pago]['Interes_IVA']        +=      $data['ABONO_Interes_IVA']      ;


                        $abonos[$id_pago]['Comision']           +=      $data['ABONO_Comision']         ;
                        $abonos[$id_pago]['Comision_IVA']       +=      $data['ABONO_Comision_IVA']     ;

                        $abonos[$id_pago]['Moratorio']          +=      $data['ABONO_Moratorio']        ;
                        $abonos[$id_pago]['Moratorio_IVA']      +=      $data['ABONO_Moratorio_IVA']    ;


                        $abonos[$id_pago]['Otros']              +=      $data['ABONO_Otros']            ;
                        $abonos[$id_pago]['Otros_IVA']          +=      $data['ABONO_Otros_IVA']        ;
 

 
 
                        $abonos[$id_pago]['SegV']              +=      $data['ABONO_SegV']            ;
                        $abonos[$id_pago]['SegV_IVA']          +=      $data['ABONO_SegV_IVA']        ;
 
  
                        $abonos[$id_pago]['SegD']              +=      $data['ABONO_SegD']            ;
                        $abonos[$id_pago]['SegD_IVA']          +=      $data['ABONO_SegD_IVA']        ;
 
 
                        $abonos[$id_pago]['SegB']              +=      $data['ABONO_SegB']            ;
                        $abonos[$id_pago]['SegB_IVA']          +=      $data['ABONO_SegB_IVA']        ;
 
 
 
 
                        
                        $abonos[$id_pago]['SaldoFavor']         +=      $data['Monto'] - ($data['ABONO_Capital']        + 
                                                                                          $data['ABONO_Interes']        +
                                                                                          $data['ABONO_Interes_IVA']    +
                                                                                          $data['ABONO_Comision']       +               
                                                                                          $data['ABONO_Comision_IVA']   +                       
                                                                                          $data['ABONO_Moratorio']      + 
                                                                                          $data['ABONO_Moratorio_IVA']  +
                                                                                          $data['ABONO_Otros']          +       
                                                                                          $data['ABONO_Otros_IVA']      +  
                                                                                          
                                                                                          $data['ABONO_SegV']           +                                                                                
                                                                                          $data['ABONO_SegV_IVA']       +                                                                                
                                                                                          $data['ABONO_SegD']           +                                                                                
                                                                                          $data['ABONO_SegD_IVA']       +                                                                                
                                                                                          $data['ABONO_SegB']           +                                                                                
                                                                                          $data['ABONO_SegB_IVA']               )   ;
                        
                }
        
        
        
        }
        
        
        return($abonos);

}




//-------------------------------------------------------------------------------------------------------------------------------------------------------------

function obtener_saldos_por_preprocesar($id,$NumCuota)
{

        $saldo_capital   =0;
        $saldo_interes   =0;
        $saldo_comision  =0;
        $saldo_moratorio =0;
        $saldo_otros     =0;
     

        $saldo_interes_iva   =0;
        $saldo_comision_iva  =0;
        $saldo_moratorio_iva =0;
        $saldo_otros_iva     =0;






// debug("---------------------> obtener_saldos_por_preprocesar(id=$id, NumCuota= $NumCuota)");



        if($this->num_cuota_preprocesada > ($NumCuota+1))
        {
        
                $cuota_anterior = $this->num_cuota_preprocesada-1;
                
                $sql = "SELECT  (cargos.Capital         -  cargos.AntiCapital    )                     AS Capital,
                                (cargos.Interes         -  cargos.AntiInteres    )                     AS Interes,
                                (cargos.Comision        -  cargos.AntiComision   )                     AS Comision,
                                (cargos.Moratorio       -  cargos.AntiMoratorio  )                     AS Moratorio,
                                (cargos.Otros           -  cargos.AntiOtros      )                     AS Otros,


                                cargos.SegV                                                            AS SegV,
                                cargos.SegD                                                            AS SegD,
                                cargos.SegB                                                            AS SegB,



                                cargos.IVA_Interes                                                     AS IVA_Interes,
                                cargos.IVA_Comision                                                    AS IVA_Comision,
                                cargos.IVA_Moratorio                                                   AS IVA_Moratorio,
                                cargos.IVA_Otros                                                       AS IVA_Otros,
                                
                                cargos.IVA_SegV                                                        AS IVA_SegV,
                                cargos.IVA_SegD                                                        AS IVA_SegD,
                                cargos.IVA_SegB                                                        AS IVA_SegB
                                
                                



                        FROM    cargos 
                        WHERE   Num_compra = '".$this->numcompra."'  AND  ID_Concepto = -3 AND
                                ID_Cargo   = '".($cuota_anterior)."'  ";  // AND Activo='Si'           

                
                
                $rs=$this->db->Execute($sql);


                $saldo_capital       = $rs->fields['Capital']       + $this->abonos_contra_saldos_vigentes_por_couta[$cuota_anterior]['Capital'];
                $saldo_interes       = $rs->fields['Interes']       + $this->abonos_contra_saldos_vigentes_por_couta[$cuota_anterior]['Interes'];
                $saldo_comision      = $rs->fields['Comision']      + $this->abonos_contra_saldos_vigentes_por_couta[$cuota_anterior]['Comision'];
                $saldo_moratorio     = $rs->fields['Moratorio']     + $this->abonos_contra_saldos_vigentes_por_couta[$cuota_anterior]['Moratorio'];
                $saldo_otros         = $rs->fields['Otros']         + $this->abonos_contra_saldos_vigentes_por_couta[$cuota_anterior]['Otros'];

                $saldo_segv          = $rs->fields['SegV']          + $this->abonos_contra_saldos_vigentes_por_couta[$cuota_anterior]['SegV'];
                $saldo_segd          = $rs->fields['SegD']          + $this->abonos_contra_saldos_vigentes_por_couta[$cuota_anterior]['SegD'];
                $saldo_segb          = $rs->fields['SegB']          + $this->abonos_contra_saldos_vigentes_por_couta[$cuota_anterior]['SegB'];







                $saldo_interes_iva   = $rs->fields['IVA_Interes'  ] + $this->abonos_contra_saldos_vigentes_por_couta[$cuota_anterior]['Interes_IVA'];
                $saldo_comision_iva  = $rs->fields['IVA_Comision' ] + $this->abonos_contra_saldos_vigentes_por_couta[$cuota_anterior]['Comision_IVA'];
                $saldo_moratorio_iva = $rs->fields['IVA_Moratorio'] + $this->abonos_contra_saldos_vigentes_por_couta[$cuota_anterior]['Moratorio_IVA'];
                $saldo_otros_iva     = $rs->fields['IVA_Otros'    ] + $this->abonos_contra_saldos_vigentes_por_couta[$cuota_anterior]['Otros_IVA'];

                $saldo_segv_iva      = $rs->fields['IVA_SegV']      + $this->abonos_contra_saldos_vigentes_por_couta[$cuota_anterior]['SegV_IVA'];
                $saldo_segd_iva      = $rs->fields['IVA_SegD']      + $this->abonos_contra_saldos_vigentes_por_couta[$cuota_anterior]['SegD_IVA'];
                $saldo_segb_iva      = $rs->fields['IVA_SegB']      + $this->abonos_contra_saldos_vigentes_por_couta[$cuota_anterior]['SegB_IVA'];



        
        

                $saldo_capital   = ($saldo_capital      <0)?(0):($saldo_capital  );                                                        
                $saldo_interes   = ($saldo_interes      <0)?(0):($saldo_interes  );                                                              
                $saldo_comision  = ($saldo_comision     <0)?(0):($saldo_comision );                                                                
                $saldo_moratorio = ($saldo_moratorio    <0)?(0):($saldo_moratorio);                                                              
                $saldo_otros     = ($saldo_otros        <0)?(0):($saldo_otros    );                           

                $saldo_segv      = ($saldo_segv         <0)?(0):($saldo_segv     ); 
                $saldo_segd      = ($saldo_segd         <0)?(0):($saldo_segd     ); 
                $saldo_segb      = ($saldo_segb         <0)?(0):($saldo_segb     ); 






                $saldo_interes_iva   = ($saldo_interes_iva      <0)?(0):($saldo_interes_iva  ); 
                $saldo_comision_iva  = ($saldo_comision_iva     <0)?(0):($saldo_comision_iva ); 
                $saldo_moratorio_iva = ($saldo_moratorio_iva    <0)?(0):($saldo_moratorio_iva); 
                $saldo_otros_iva     = ($saldo_otros_iva        <0)?(0):($saldo_otros_iva    ); 

                $saldo_segv_iva      = ($saldo_segv_iva         <0)?(0):($saldo_segv_iva     );
                $saldo_segd_iva      = ($saldo_segd_iva         <0)?(0):($saldo_segd_iva     );
                $saldo_segb_iva      = ($saldo_segb_iva         <0)?(0):($saldo_segb_iva     );




                if(($saldo_capital + $saldo_interes + $saldo_comision + $saldo_moratorio + $saldo_otros + $saldo_segv + $saldo_segd + $saldo_segb )>=0.01)
                    $this->num_cuota_preprocesada--;


        
                $saldo_capital   = 0;
                $saldo_interes   = 0;
                $saldo_comision  = 0;
                $saldo_moratorio = 0;
                $saldo_otros     = 0;

                $saldo_segv_iva  = 0;
                $saldo_segd_iva  = 0;
                $saldo_segb_iva  = 0;

 
 
 
                $saldo_interes_iva   = 0;
                $saldo_comision_iva  = 0;
                $saldo_moratorio_iva = 0;
                $saldo_otros_iva     = 0;

                $saldo_segv_iva      = 0;
                $saldo_segd_iva      = 0;
                $saldo_segb_iva      = 0;               
        
        }



        while((($saldo_capital + $saldo_interes + $saldo_comision + $saldo_moratorio + $saldo_otros + $saldo_segv + $saldo_segd + $saldo_segb )<0.01)) 
        {


                $sql = "SELECT COUNT(*) 
                        FROM   cargos 
                        WHERE Num_compra='".$this->numcompra."'   AND ID_Concepto = -3 AND ID_Cargo>='".$this->num_cuota_preprocesada."' ";// AND ID_Cargo   !=   '".$this->is_vencimiento_anticipado."'  ";               
                //AND Activo='Si'
                $rs=$this->db->Execute($sql);


                if(!$rs->fields[0])
                        return(array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0));       




                $sql = "SELECT COUNT(cargos.ID_Cargo) ,
                                sum((cargos.Capital         -  cargos.AntiCapital    ))                AS Capital , 
                                sum((cargos.Interes         -  cargos.AntiInteres    ))                AS Interes , 
                                sum((cargos.Comision        -  cargos.AntiComision   ))                AS Comision,

                                sum(cargos.SegV    )                                                   AS SegV,
                                sum(cargos.SegD    )                                                   AS SegD,
                                sum(cargos.SegB    )                                                   AS SegB,

                                sum( cargos.IVA_Interes         )                                      AS IVA_Interes , 
                                sum( cargos.IVA_Comision        )                                      AS IVA_Comision,
                                
                                sum(cargos.IVA_SegV    )                                               AS IVA_SegV,
                                sum(cargos.IVA_SegD    )                                               AS IVA_SegD,
                                sum(cargos.IVA_SegB    )                                               AS IVA_SegB
                                
                                
                                


                        FROM   cargos 
                        WHERE Num_compra='".$this->numcompra."' AND ID_Concepto = -3 AND ID_Cargo <= '".$this->num_cuota_preprocesada."'";               
//AND Activo='Si' 
                $rs=$this->db->Execute($sql);
     
                $suma_cargos_capital_vencido  =  $rs->fields['Capital'];
                $suma_cargos_interes_vencido  =  $rs->fields['Interes'];
                $suma_cargos_comision_vencido =  $rs->fields['Comision'];
                
                $suma_cargos_segv_vencido     =  $rs->fields['SegV'];
                $suma_cargos_segd_vencido     =  $rs->fields['SegD'];
                $suma_cargos_segb_vencido     =  $rs->fields['SegB'];
                
                
    
                $suma_cargos_interes_vencido_iva  =  $rs->fields['IVA_Interes'];
                $suma_cargos_comision_vencido_iva =  $rs->fields['IVA_Comision'];
                
                $suma_cargos_segv_vencido_iva     =  $rs->fields['IVA_SegV'];
                $suma_cargos_segd_vencido_iva     =  $rs->fields['IVA_SegD'];
                $suma_cargos_segb_vencido_iva     =  $rs->fields['IVA_SegB'];
                
                
                
                
                


                $sql = "
                
                SELECT
                
                (cargos.Capital         -  cargos.AntiCapital    )                     AS Capital,
                (cargos.Interes         -  cargos.AntiInteres    )                     AS Interes,
                (cargos.Comision        -  cargos.AntiComision   )                     AS Comision,
                (cargos.Moratorio       -  cargos.AntiMoratorio  )                     AS Moratorio,
                (cargos.Otros           -  cargos.AntiOtros      )                     AS Otros,
                

                cargos.SegV                                                            AS SegV,
                cargos.SegD                                                            AS SegD,
                cargos.SegB                                                            AS SegB,


                cargos.IVA_Interes                                                     AS IVA_Interes,
                cargos.IVA_Comision                                                    AS IVA_Comision,
                cargos.IVA_Moratorio                                                   AS IVA_Moratorio,
                cargos.IVA_Otros                                                       AS IVA_Otros,

                                
                cargos.IVA_SegV                                                        AS IVA_SegV,
                cargos.IVA_SegD                                                        AS IVA_SegD,
                cargos.IVA_SegB                                                        AS IVA_SegB
                                


                
                FROM cargos 
                WHERE   Num_compra='".$this->numcompra."'  AND 
                        ID_Cargo='".$this->num_cuota_preprocesada."'
                         ";          
// AND Activo='Si'
                //debug($sql);

                $rs=$this->db->Execute($sql);


                $saldo_capital       = $rs->fields['Capital']       + $this->abonos_contra_saldos_vigentes_por_couta[$this->num_cuota_preprocesada]['Capital'];
                $saldo_interes       = $rs->fields['Interes']       + $this->abonos_contra_saldos_vigentes_por_couta[$this->num_cuota_preprocesada]['Interes'];
                $saldo_comision      = $rs->fields['Comision']      + $this->abonos_contra_saldos_vigentes_por_couta[$this->num_cuota_preprocesada]['Comision'];
                $saldo_moratorio     = $rs->fields['Moratorio']     + $this->abonos_contra_saldos_vigentes_por_couta[$this->num_cuota_preprocesada]['Moratorio'];
                $saldo_otros         = $rs->fields['Otros']         + $this->abonos_contra_saldos_vigentes_por_couta[$this->num_cuota_preprocesada]['Otros'];

                $saldo_segv          = $rs->fields['SegV']          + $this->abonos_contra_saldos_vigentes_por_couta[$this->num_cuota_preprocesada]['SegV'];
                $saldo_segd          = $rs->fields['SegD']          + $this->abonos_contra_saldos_vigentes_por_couta[$this->num_cuota_preprocesada]['SegD'];
                $saldo_segb          = $rs->fields['SegB']          + $this->abonos_contra_saldos_vigentes_por_couta[$this->num_cuota_preprocesada]['SegB'];





                $saldo_interes_iva   = $rs->fields['IVA_Interes'  ] + $this->abonos_contra_saldos_vigentes_por_couta[$this->num_cuota_preprocesada]['Interes_IVA'];
                $saldo_comision_iva  = $rs->fields['IVA_Comision' ] + $this->abonos_contra_saldos_vigentes_por_couta[$this->num_cuota_preprocesada]['Comision_IVA'];
                $saldo_moratorio_iva = $rs->fields['IVA_Moratorio'] + $this->abonos_contra_saldos_vigentes_por_couta[$this->num_cuota_preprocesada]['Moratorio_IVA'];
                $saldo_otros_iva     = $rs->fields['IVA_Otros'    ] + $this->abonos_contra_saldos_vigentes_por_couta[$this->num_cuota_preprocesada]['Otros_IVA'];

                $saldo_segv_iva      = $rs->fields['IVA_SegV']      + $this->abonos_contra_saldos_vigentes_por_couta[$this->num_cuota_preprocesada]['SegV_IVA'];
                $saldo_segd_iva      = $rs->fields['IVA_SegD']      + $this->abonos_contra_saldos_vigentes_por_couta[$this->num_cuota_preprocesada]['SegD_IVA'];
                $saldo_segb_iva      = $rs->fields['IVA_SegB']      + $this->abonos_contra_saldos_vigentes_por_couta[$this->num_cuota_preprocesada]['SegB_IVA'];





        

                $saldo_capital          = ($saldo_capital      <0)?(0):($saldo_capital  );                                                        
                $saldo_interes          = ($saldo_interes      <0)?(0):($saldo_interes  );                                                              
                $saldo_comision         = ($saldo_comision     <0)?(0):($saldo_comision );                                                                
                $saldo_moratorio        = ($saldo_moratorio    <0)?(0):($saldo_moratorio);                                                              
                $saldo_otros            = ($saldo_otros        <0)?(0):($saldo_otros    );       
                
                $saldo_segv             = ($saldo_segv         <0)?(0):($saldo_segv     ); 
                $saldo_segd             = ($saldo_segd         <0)?(0):($saldo_segd     ); 
                $saldo_segb             = ($saldo_segb         <0)?(0):($saldo_segb     ); 
                
                
                
                
                
                
                
                $saldo_interes_iva      = ($saldo_interes_iva      <0)?(0):($saldo_interes_iva  );                
                $saldo_comision_iva     = ($saldo_comision_iva     <0)?(0):($saldo_comision_iva );                
                $saldo_moratorio_iva    = ($saldo_moratorio_iva    <0)?(0):($saldo_moratorio_iva);                
                $saldo_otros_iva        = ($saldo_otros_iva        <0)?(0):($saldo_otros_iva    );                

                $saldo_segv_iva         = ($saldo_segv_iva         <0)?(0):($saldo_segv_iva     );
                $saldo_segd_iva         = ($saldo_segd_iva         <0)?(0):($saldo_segd_iva     );
                $saldo_segb_iva         = ($saldo_segb_iva         <0)?(0):($saldo_segb_iva     );





                
                


                if(($saldo_capital + $saldo_interes + $saldo_comision + $saldo_moratorio + $saldo_otros + $saldo_segv + $saldo_segd + $saldo_segb )<0.01)
                {
                        ++$this->num_cuota_preprocesada;

                }

        }

      // return(array($saldo_capital,$saldo_interes, $saldo_comision, $saldo_moratorio, $saldo_otros));
      
      return(array($saldo_capital,    $saldo_interes,      $saldo_comision,      $saldo_moratorio,     $saldo_otros,     $saldo_segv,     $saldo_segd,     $saldo_segb,
                                      $saldo_interes_iva,  $saldo_comision_iva,  $saldo_moratorio_iva, $saldo_otros_iva, $saldo_segv_iva, $saldo_segd_iva, $saldo_segb_iva ));
      
      
}
//-------------------------------------------------------------------------------------------------------------------------------------------------------------

function obtener_saldos_por_vencer($id, $fecha_limite="", $sin_couta="")
{
        
        $sql = "SELECT  (cargos.Capital         -  cargos.AntiCapital    )                     AS Capital,
                        (cargos.Interes         -  cargos.AntiInteres    )                     AS Interes,
                        (cargos.Comision        -  cargos.AntiComision   )                     AS Comision,
                        (cargos.Moratorio       -  cargos.AntiMoratorio  )                     AS Moratorio,
                        (cargos.Otros           -  cargos.AntiOtros      )                     AS Otros, 
        
                        (cargos.SegV                                     )                     AS SegV,
                        (cargos.SegD                                     )                     AS SegD,
                        (cargos.SegB                                     )                     AS SegB
        
        
        
                FROM cargos WHERE Num_compra='".$this->numcompra."' AND 
                     ID_Concepto = -3  
                      ";
        
        //AND Activo = 'Si'
        
        
        
        
        if(!empty($fecha_limite))
        {
                $sql .= " and Fecha_Vencimiento <= '".$fecha_limite."' ";       
                
        }

        
        
        if(!empty($sin_couta))
        {
                $sql .= " and ID_Cargo > '".$sin_couta."'";     
                
        }


//debug($sql);
//die();


        $rs=$this->db->Execute($sql);
        
        
        $saldo_capital   = $rs->fields['Capital'];
        $saldo_interes   = $rs->fields['Interes'];
        $saldo_comision  = $rs->fields['Comision'];
        $saldo_moratorio = $rs->fields['Moratorio'];
        $saldo_otros     = $rs->fields['Otros'];        
        
        $saldo_segv      = $rs->fields['SegV'];        
        $saldo_segd      = $rs->fields['SegD'];        
        $saldo_segb      = $rs->fields['SegB'];        
        
        
    
    
    
    
    
        

        return(array($saldo_capital,$saldo_interes, $saldo_comision, $saldo_moratorio, $saldo_otros, $saldo_segv, $saldo_segd, $saldo_segb ));
}


//-------------------------------------------------------------------------------------------------------------------------------------------------------------

function aplica_saldo_favor_pendiente($id,$ID_Cuota, $offSet)
{

/**/
        
        
        $_id= $id;

        if(empty($this->aplicacion[($id+$offSet)]['Fecha']))
           return($id);


        $_fecha_referencia      = max($this->fecha_ultimo_abono, $this->FechaCuota);
        $_fecha_referencia      = max($_fecha_referencia, $this->aplicacion[($id+$offSet)]['Fecha']);

        if(empty($_fecha_referencia))
           return($id);

        
        $_suma_saldos_parciales  = $this->aplicacion[($id+$offSet)]['SALDO_MOV_General'];
        $saldo_a_favor_pendiente = $this->aplicacion[($id+$offSet)]['SALDO_MOV_Pendiente_Aplicar'];
        
        
        $_fecha_mov_anterior = $this->aplicacion[($id+$offSet)]['Fecha'];
        
        
        
        if(($_suma_saldos_parciales>=0.01) and ($saldo_a_favor_pendiente<= -0.01))
        {
                $id=($id+$offSet)+1;
        
        
                if($_fecha_referencia > $fecha_mov_anterior)
                     $this->aplicacion[$id]['DiasAtraso']  = ffdias($_fecha_mov_anterior,$_fecha_referencia);
                else
                     $this->aplicacion[$id]['DiasAtraso']  = 0;         


               // Si se acyiva la opcioón de saldo a favor anticipado, solo aplicamos saldos a
               
               //and (!$this->aplicacion[$id]['DiasAtraso'])
               //if(($this->aplica_saldos_a_favor_anticipadamente) )
               // {
               //       return($id);
               // }



                $this->aplicacion[$id]['AplicacionSaldo']       = 1;
                $this->aplicacion[$id]['Fecha']                 =  $this->fecha_ultimo_abono;           
                $this->aplicacion[$id]['Fecha_Mov']             =  $this->fecha_ultimo_abono;
                $this->aplicacion[$id]['ID']                    =  $this->ultimo_abono_ID;

                $this->aplicacion[$id]['SALDO_General']         =  $this->aplicacion[($id+$offSet)]['SALDO_General'];

                $this->aplicacion[$id]['Tipo']                  = "Abono";
                $this->aplicacion[$id]['Concepto']              = "Efectivo";
                $this->aplicacion[$id]['Descripcion']           = "Aplicación de saldo a favor ";
                


                if($this->aplica_saldos_a_favor_anticipadamente)
                {
                        $saldo_a_favor_aplicado =  $saldo_a_favor_pendiente;
                
                }
                else
                {
                        if(abs($saldo_a_favor_pendiente) > $_suma_saldos_parciales)
                                $saldo_a_favor_aplicado =  -1*$_suma_saldos_parciales;
                        else
                                $saldo_a_favor_aplicado =  $saldo_a_favor_pendiente;
                }
        
                $this->aplicacion[$id]['SaldoFavorAplicado']     = $saldo_a_favor_aplicado;
                $this->SumaSaldoFavorAplicado                   += $saldo_a_favor_aplicado ;

                
                $this->aplicacion[$id]['IMB']                    = $this->calculo_interes_moratorio_variable(($id),$ID_Cuota,"D");
                
                if($this->fecha_ultimo_abono   > $this->FechaCuota)
                {
                        
                        
                        $this->aplicacion[$id]['DiasAtrasoAcum'] = ($this->aplicacion[$id]['DiasAtraso'] + $this->aplicacion[($id+$offSet)]['DiasAtrasoAcum']);
                        
                        
                        

                                //$this->aplicacion[$id]['IM'] =$this->aplicacion[$id]['IMB']/(1+$this->iva_pcnt_moratorios);
                                $this->aplicacion[$id]['CARGO_IVA']             +=($this->aplicacion[$id]['IMB'] - $this->aplicacion[$id]['IM']);
                                $this->aplicacion[$id]['CARGO_IVA_Moratorio' ]  +=($this->aplicacion[$id]['IMB'] - $this->aplicacion[$id]['IM']);                                       
                        
                        
                        
                                $_suma_saldos_parciales += $this->aplicacion[$id]['IMB'];
                        
                        
                        
                        
                        $this->aplicacion[$id]['Descripcion']   .= " de pago extemporáneo.";
                                
                }
                

                $this->aplicacion[$id]['Fecha']         = $_fecha_referencia;

                if($this->aplica_saldos_a_favor_anticipadamente)
                {
                        $saldo_a_favor_aplicado =  $saldo_a_favor_pendiente;
                
                }
                else
                {
                        if(abs($saldo_a_favor_pendiente) > $_suma_saldos_parciales)
                                $saldo_a_favor_aplicado =  -1*$_suma_saldos_parciales;
                        else
                                $saldo_a_favor_aplicado =  $saldo_a_favor_pendiente;
                }







                $this->aplicacion[$id]['SaldoParcial']          =  $saldo_a_favor_aplicado + $this->aplicacion[$id]['IMB'] + $this->aplicacion[($id+$offSet)]['SaldoParcial'] ;
                $this->aplicacion[$id]['SALDO_General']         += $saldo_a_favor_aplicado + $this->aplicacion[$id]['IMB'];
                
                $this->aplicacion[$id]['SaldoParcial'] = ($this->aplicacion[$id]['SaldoParcial'] <0)?(0):($this->aplicacion[$id]['SaldoParcial']);




                $this->aplicacion[$id]['SaldoCapitalPorVencer']  = $this->aplicacion[($id-1)]['SaldoCapitalPorVencer'] ;  
                $this->aplicacion[$id]['SaldoInteresPorVencer']  = $this->aplicacion[($id-1)]['SaldoInteresPorVencer'] ;  
                $this->aplicacion[$id]['SaldoComisionPorVencer'] = $this->aplicacion[($id-1)]['SaldoComisionPorVencer'];  
    
                $this->aplicacion[$id]['SaldoSegVPorVencer']     = $this->aplicacion[($id-1)]['SaldoSegVPorVencer'] ;  
                $this->aplicacion[$id]['SaldoSegDPorVencer']     = $this->aplicacion[($id-1)]['SaldoSegDPorVencer'] ;  
                $this->aplicacion[$id]['SaldoSegBPorVencer']     = $this->aplicacion[($id-1)]['SaldoSegBPorVencer'] ;  
    
    
    
    
                $this->aplicacion[$id]['SaldoInteres_IVA_PorVencer' ] = $this->aplicacion[($id-1)]['SaldoInteres_IVA_PorVencer'] ;  
                $this->aplicacion[$id]['SaldoComision_IVA_PorVencer'] = $this->aplicacion[($id-1)]['SaldoComision_IVA_PorVencer'];  
    
                $this->aplicacion[$id]['SaldoSegV_IVA_PorVencer']     = $this->aplicacion[($id-1)]['SaldoSegV_IVA_PorVencer'] ;  
                $this->aplicacion[$id]['SaldoSegD_IVA_PorVencer']     = $this->aplicacion[($id-1)]['SaldoSegD_IVA_PorVencer'] ;  
                $this->aplicacion[$id]['SaldoSegB_IVA_PorVencer']     = $this->aplicacion[($id-1)]['SaldoSegB_IVA_PorVencer'] ;  
    
                


                if($this->aplicacion[$id]['SaldoCapitalPorVencer']<=0)
                {
                   $this->aplicacion[$id]['SaldoInteresPorVencer'] = 0;
                   $this->aplicacion[$id]['SaldoComision_IVA_PorVencer'] =0;
                }



                $this->desglosar_movimientos($id,$ID_Cuota);
                
                if($_id == $id)
                {
                        ++$id;
                }

        
        }
        
        return($id);

        //return($saldo_a_favor_aplicado);
}
//-------------------------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------------------------
function aplica_cargos_cobranza($id,$ID_Cuota, $FechaSigMov,  $offSet, $debug="")
{
     
     // Los créditos solidarios no tienen comisiones de cobranza.
     if($this->extemporaneos_tipo == '')  return($id);
     
	if($this->extemporaneos_tipo == 'Cuota')
	{
	
		$id = $this->aplica_cargos_extemporaneos_por_cuota($id,$ID_Cuota, $FechaSigMov,  $offSet, $debug);
	
	}
	elseif($this->extemporaneos_tipo == 'General')
	{
	
	      	$id = $this->aplica_cargos_extemporaneos_general($id,$ID_Cuota, $FechaSigMov,  $offSet, $debug);

	
	}
	else
	{
		return($id);
	}



     return($id);

}


//-------------------------------------------------------------------------------------------------------------------------------------------------------------
//
//-------------------------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------------------------

function aplica_cargos_extemporaneos_general($id,$ID_Cuota, $FechaSigMov,  $offSet, $dbg="")
{
        if(empty($this->FechaCuota))                            return($id);

        if(empty($FechaSigMov))                                 return($id);
                                
        if(empty($this->aplicacion[($id+$offSet)]['Fecha']))    return($id);
                        
        if($this->aplicacion[($id+$offSet)]['SALDO_General']< 1) return($id);
        
        if(($this->is_vencimiento_anticipado) and ($ID_Cuota == $this->id_cargo_reemplazo_vencimiento_anticipado)) return($id);


        
        
        $_offset=0;
        
        $_id= $id;
        $_fecha_referencia        = $this->aplicacion[($id+$offSet)]['Fecha'];
        $fecha_dia                = $_fecha_referencia;
        
        


        // $monto_extemporaneo = array();


        //$monto_extemporaneo[7]   =  40;
        //$monto_extemporaneo[31]  =  60;
        //$monto_extemporaneo[61]  =  80;
        //$monto_extemporaneo[91]  = 100;
        //$monto_extemporaneo[121] = 120;
        //$monto_extemporaneo[151] = 140;
        //$monto_extemporaneo[181] = 160;
        //$monto_extemporaneo[211] = 180;
        //$monto_extemporaneo[241] = 200;
        
        
        $monto_extemporaneo = $this->ext_tabla;
        


	$a_dias_max_key = array_keys($monto_extemporaneo);







        //---------------------------------------------------------------------------------
        //Guararda la referencia por cada cota de las fechas en que existen dias de atraso
        //---------------------------------------------------------------------------------

        $this->historico_morosidad_por_couta[$ID_Cuota][$fecha_dia]['DiasAtraso']       = $this->aplicacion[($id+$offSet)]['DiasAtrasoAcum'];
        $this->historico_morosidad_por_couta[$ID_Cuota][$fecha_dia]['Monto']            = $this->aplicacion[($id+$offSet)]['Abono']+$this->aplicacion[($id+$offSet)]['Cargo']+$this->aplicacion[($id+$offSet)]['SaldoFavorAplicado'];
        $this->historico_morosidad_por_couta[$ID_Cuota][$fecha_dia]['SALDO_General']    = $this->aplicacion[($id+$offSet)]['SALDO_General'];
        //---------------------------------------------------------------------------------


        if($this->SaldoGeneralVencido<0.01)
        {
                $this->dias_mora_max_cobranza                           = $this->aplicacion[($id+$offSet)]['DiasAtrasoAcum'];
                $this->historico_morosidad_cobranza[$fecha_dia]         = "";   //$this->historico_morosidad_global[$_fecha_referencia];        //$this->aplicacion[($id+$offSet)]['DiasAtraso'];
                
                $this->historico_morosidad_diferencial_cobranza[$fecha_dia] = "X";
                $this->historico_diferencial_continuo[$fecha_dia] = "Y";
            

                $this->hmdc[$fecha_dia][3] = ffecha($this->aplicacion[($id+$offSet)]['Fecha']);
                $this->hmdc[$fecha_dia][4] = $this->aplicacion[($id+$offSet)]['Abono']; 
                $this->hmdc[$fecha_dia][5] = $this->aplicacion[($id+$offSet)]['DiasAtrasoAcum'];
                $this->hmdc[$fecha_dia][6] = $this->aplicacion[($id+$offSet)]['SALDO_General'];

                $this->historico_morosidad_por_couta[$ID_Cuota][$fecha_dia]['SALDO_General']  = "X";


            return($id);
        }

        
        
        
        
        list($_y, $_m, $_d) = explode("-",$fecha_dia);

        //list($_y, $_m, $_d) = explode("-",$_fecha_referencia);
        //$fecha_dia = date("Y-m-d",mktime(0,0,0,$_m, ($_d+1), $_y));
        
        
        
        
        $dias_acumulados = 0;
        
        
        $this->dias_mora_max_cobranza = max($this->historico_morosidad_global[$_fecha_referencia],$this->dias_mora_max_cobranza);


        
        
        
        while ($fecha_dia < $FechaSigMov )
        {




                list($_y, $_m, $_d) = explode("-",$fecha_dia);
                        

                if(! $this->historico_morosidad_cobranza[$fecha_dia] )                                  
                { 

                        
                        
                        
                        //---------------------------------------------------------------------------------                     
                        //Mantenemos actualizado el contador de días por cada cuota y el ctrol de traslapes de fecha
                        //---------------------------------------------------------------------------------                     
                        
                        $fecha_anterior = ($fecha_dia == $_fecha_referencia)?($fecha_dia ):(date("Y-m-d",mktime(0,0,0,$_m, ($_d-1), $_y)));                     
                        $this->historico_morosidad_por_couta[$ID_Cuota][$fecha_dia]['DiasAtraso']       = $this->historico_morosidad_por_couta[$ID_Cuota][$fecha_anterior]['DiasAtraso']+1; //"x";

                        //---------------------------------------------------------------------------------                     
                        //                              
                        //---------------------------------------------------------------------------------                     
                        
                        $fecha_ayer = date("Y-m-d",mktime(0,0,0,$_m, ($_d-1), $_y));

                                
                                if(($this->aplicacion[($id+$offSet)]['Tipo']=='Abono') and ($this->aplicacion[($id+$offSet)]['Fecha']==$fecha_dia) )
                                {
                                        
                                        
                                        
                                        
                                        
                                        if($this->aplicacion[($id+$offSet)]['SALDO_General']<0.01)
                                                $this->historico_morosidad_cobranza[$fecha_dia]=($this->aplicacion[($id+$offSet)]['DiasAtrasoAcum']);
                                        else                                    
                                                $this->historico_morosidad_cobranza[$fecha_dia] =  max(($this->aplicacion[($id+$offSet)]['DiasAtrasoAcum']),($this->historico_morosidad_por_couta[$ID_Cuota][$fecha_dia]['DiasAtraso']));
                                        
                                        
                                }
                                else
                                {                                       
                                        $this->historico_morosidad_cobranza[$fecha_dia]=$this->historico_morosidad_cobranza[$fecha_ayer]+1;
                                }

                        //---------------------------------------------------------------------------------                     
                        // Si se reinician el historico_morosidad_global, debemos reiniciar el contador historico_morosidad_diferencial_cobranza                
                        //---------------------------------------------------------------------------------                     
                        if(($this->historico_morosidad_cobranza[$fecha_dia] == 1))
                        {
                                        // Como se reinicia el contador cuando la cuota queda saldada y no hay traslape.
                                    $this->historico_morosidad_diferencial_cobranza[$fecha_dia] = 1;
                                    $this->historico_diferencial_continuo[$fecha_dia]           = 1;
                        
                        }
                           else
                                if($this->historico_morosidad_cobranza[$fecha_dia] <= $this->historico_morosidad_cobranza[$fecha_ayer] )
                                {

                                        // Como se reinicia el contador cuando la cuota cuota queda saldada y hay traslape.con otras cuotas.

                                        $this->historico_morosidad_diferencial_cobranza[$fecha_dia] =  1;
                                        $this->historico_diferencial_continuo[$fecha_dia]           =  1;
                                }
                                else
                                        {

                                                $this->historico_morosidad_diferencial_cobranza[$fecha_dia]     = $this->historico_morosidad_diferencial_cobranza[$fecha_ayer]+1;
                                                $this->historico_diferencial_continuo[$fecha_dia]               = $this->historico_diferencial_continuo[$fecha_ayer]+1;
                                        }


                        



                        $dias_max = $this->historico_morosidad_diferencial_cobranza[$fecha_dia];
                        //$dias_max =  $this->historico_morosidad_cobranza[$fecha_dia];
/*
                          if((($dias_max == 7)    or 
                              ($dias_max == 31)   or 
                              ($dias_max == 61)   or 
                              ($dias_max == 91)   or                              
                              ($dias_max == 121)  or 
                              ($dias_max == 151)  or 
                              ($dias_max == 181)  or
                              ($dias_max == 211)  or 
                              ($dias_max == 241)
                              )         and 
*/                              


                          if( ($dias_max>0)			    and
                              (in_array($dias_max,$a_dias_max_key)) and                              
                              ($this->SaldoGeneralVencido>0.01)     and 
                              ($this->historico_diferencial_continuo[$fecha_ayer] != $this->historico_diferencial_continuo[$fecha_dia]))
                          {


                                $fecha_cero = date("Y-m-d",mktime(0,0,0,$_m, ($_d - ($this->historico_diferencial_continuo[$fecha_dia] -1)), $_y));
                                $fecha_zero = date("Y-m-d",mktime(0,0,0,$_m, ($_d - ($this->historico_diferencial_continuo[$fecha_dia] +0)), $_y));

                                $valor_cero = $this->historico_morosidad_cobranza[$fecha_cero];
                                $valor_zero = $this->historico_morosidad_cobranza[$fecha_zero];
                                
                                $this->hmdc[$fecha_dia][0] = $fecha_cero;
                                $this->hmdc[$fecha_dia][1] = $valor_cero;
                                //$this->hmdc[$fecha_dia][2] = $fecha_zero;                                             
                                //$this->hmdc[$fecha_dia][3] = $valor_zero;                                             
                                
                                //if($this->historico_diferencial_continuo[$fecha_dia] ==  $this->historico_morosidad_cobranza[$fecha_dia])
                                
                                if($valor_cero <=($dias_max))
                                {
                                
                                        $pIVA =  getIVAComision($this->zona_iva, $fecha_dia, $this->db);     
                                        
                                        $_fecha_aplicacion_cargo = date("Y-m-d",mktime(0,0,0,$_m,(  $_d+1),$_y));
/*
                                        $_pcnt_extemporaneos     = $this->busca_tasa_cobranza($_fecha_aplicacion_cargo);
                                        if($_pcnt_extemporaneos < 0)
                                        {
                                                $_pcnt_extemporaneos = $this->pcnt_extemporaneos;
                                        }
*/                                        
                                        //if($_pcnt_extemporaneos == 0) return($id);

                                        if($monto_extemporaneo[$dias_max] == 0) return($id);
                                        
                                        $this->cargos_cobranza_automaticos[$fecha_dia] = ($monto_extemporaneo[$dias_max] * (1+$pIVA));
                                                                
                                   //    debug( "this->cargos_cobranza_automaticos[$fecha_dia] = [$dias_max] ($".$monto_extemporaneo[$dias_max].")");


                                                                
                                                                $this->aplicacion[$id]['CARGO_COBRANZA'] = true;
                                                                
                                                                
                                                                $this->aplicacion[$id]['Fecha_Mov'] = $_fecha_aplicacion_cargo ; 
                                                                $this->aplicacion[$id]['Fecha']     = $_fecha_aplicacion_cargo;


                                                                if($_id == $id)
                                                                {
                                                                        $this->aplicacion[$id]['DiasAtraso']      = ffdias($this->aplicacion[($id+$offSet)]['Fecha'],$_fecha_aplicacion_cargo );                              
                                                                        $this->aplicacion[$id]['DiasAtrasoAcum'] = ($this->aplicacion[$id]['DiasAtraso'] + $this->aplicacion[($_id+$offSet)]['DiasAtrasoAcum']);



                                                                }
                                                                else
                                                                {
                                                                        $this->aplicacion[$id]['DiasAtraso']      = ffdias($fecha_dia, $this->aplicacion[($id-1)]['Fecha']) + 1;                         
                                                                        $this->aplicacion[$id]['DiasAtrasoAcum']  =($this->aplicacion[$id]['DiasAtraso']  +$dias_acumulados);

                                                                }



                                                                $this->aplicacion[$id]['Tipo']          = 'Cargo';
                                                                $this->aplicacion[$id]['Concepto']      = "Documento";
                                                                $this->aplicacion[$id]['Descripcion']   = "<B>Comisión por gastos de cobranza con ".$dias_max ." días mora.</B> ";//($id punto : ".$debug."[$dias_max]  FechaSigMov $FechaSigMov) $XX "; //(".$dias_max .") (".$debug.")"; //  (".$this->aplicacion[($id+$offSet)]['Fecha']." al ".$fecha_aplicacion." )   "; //($_fecha_mov_anterior  al  $FechaSigMov) : $dias días";

                                                                $this->aplicacion[$id]['IMB'] = $this->calculo_interes_moratorio_variable(($id),$ID_Cuota,"Z");

                                                                $dias_acumulados = $this->aplicacion[$id]['DiasAtrasoAcum'];


                                                               //$this->aplicacion[$id]['ID'] =$this->aplicacion[$id]['IMB']/(1+$this->iva_pcnt_moratorios);


                                                                //$this->aplicacion[$id]['IM'] =$this->aplicacion[$id]['IMB']/(1+$this->iva_pcnt_moratorios);
                                                                $this->aplicacion[$id]['CARGO_IVA']             +=($this->aplicacion[$id]['IMB'] - $this->aplicacion[$id]['IM']);
                                                                $this->aplicacion[$id]['CARGO_IVA_Moratorio' ]  +=($this->aplicacion[$id]['IMB'] - $this->aplicacion[$id]['IM']);                                       


                                                                
                                                                
                                                                $this->aplicacion[$id]['Monto']         = $this->cargos_cobranza_automaticos[$fecha_dia];

        

                                                                $this->aplicacion[$id]['Cargo']         = $this->aplicacion[$id]['Monto'] ;

                                                                
                                                                
                                                                
     

                                                                //$this->aplicacion[$id]['CARGO_Otros']           = $this->aplicacion[$id]['Monto']/(1  + $this->iva_pcnt_comisiones);            
                                                                
                                                                $pIVA =   getIVA($this->zona_iva, $fecha_dia, $this->db);                                                                
                                                                $this->aplicacion[$id]['CARGO_Otros']           = $this->aplicacion[$id]['Monto']/(1  + $pIVA);            
                                                               
                                                            //   debug("$pIVA =   getIVA(".$this->zona_iva.", ".$fecha_dia.", this->db);  ");
                                                               
                                                            //   debug("this->aplicacion[$id]['CARGO_Otros'] = ".$this->aplicacion[$id]['CARGO_Otros']." = ".$this->aplicacion[$id]['Monto']."/(1  + $pIVA);");



                                                                
                                                                $this->aplicacion[$id]['CARGO_IVA_Otros']       = $this->aplicacion[$id]['Monto'] -     $this->aplicacion[$id]['CARGO_Otros'];
                                                                $this->aplicacion[$id]['CARGO_IVA']             += $this->aplicacion[$id]['CARGO_IVA_Otros'];           

                                                                $this->SumaOtros        += $this->aplicacion[$id]['CARGO_Otros'];                       
                                                                $this->SumaIVAOtros     += $this->aplicacion[$id]['CARGO_IVA_Otros'];                   
                                                                $this->SumaIVA          += $this->aplicacion[$id]['CARGO_IVA_Otros'];

                                                                $this->aplicacion[$id]['SaldoCapitalPorVencer']  = $this->aplicacion[($id+$offSet)]['SaldoCapitalPorVencer'] ;
                                                                $this->aplicacion[$id]['SaldoInteresPorVencer']  = $this->aplicacion[($id+$offSet)]['SaldoInteresPorVencer']  ;
                                                                $this->aplicacion[$id]['SaldoComisionPorVencer'] = $this->aplicacion[($id+$offSet)]['SaldoComisionPorVencer'] ; 

                                                                $this->aplicacion[$id]['SaldoInteres_IVA_PorVencer']  = $this->aplicacion[($id+$offSet)]['SaldoInteres_IVA_PorVencer']  ;
                                                                $this->aplicacion[$id]['SaldoComision_IVA_PorVencer'] = $this->aplicacion[($id+$offSet)]['SaldoComision_IVA_PorVencer'] ; 





                                                                $this->aplicacion[$id]['SaldoGlobalCapital']    = $this->aplicacion[$id]['SaldoCapitalPorVencer'];                                      
                                                                $this->aplicacion[$id]['SaldoGlobalInteres']    = $this->aplicacion[$id]['SaldoInteresPorVencer'];                              
                                                                $this->aplicacion[$id]['SaldoGlobalComision']   = $this->aplicacion[$id]['SaldoComisionPorVencer'];     

                                                                if($_id == $id)
                                                                {
                                                                        $this->aplicacion[$id]['SaldoParcial']          =  $this->aplicacion[$id]['Cargo']  + $this->aplicacion[$id]['IMB'] + $this->aplicacion[($_id+$offSet)]['SaldoParcial'] ;
                                                                        $this->aplicacion[$id]['SALDO_General']         += $this->aplicacion[$id]['Cargo']  + $this->aplicacion[$id]['IMB'] + $this->aplicacion[($_id+$offSet)]['SALDO_General'] ;
                                                                        if($this->aplicacion[($id-1)]['SALDO_MOV_General'] < 0)
                                                                        {
                                                                          $this->aplicacion[$id]['SALDO_General']       += $this->aplicacion[($_id+$offSet)]['SALDO_MOV_General'];
                                                                        }
                                                                        
                                                                        
                                                                        

                                                                        
                                                                }
                                                                else
                                                                {
                                                                        $this->aplicacion[$id]['SaldoParcial']          =  $this->aplicacion[$id]['Cargo']  + $this->aplicacion[$id]['IMB'] + $this->aplicacion[($id-1)]['SaldoParcial'] ;
                                                                        $this->aplicacion[$id]['SALDO_General']         += $this->aplicacion[$id]['Cargo']  + $this->aplicacion[$id]['IMB'] + $this->aplicacion[($id-1)]['SALDO_General'] ;
                                                                        if($this->aplicacion[($id-1)]['SALDO_MOV_General'] < 0)
                                                                        {
                                                                          $this->aplicacion[$id]['SALDO_General']       += $this->aplicacion[($id-1)]['SALDO_MOV_General'];
                                                                        }
                                                                        
                                                                }

                                                                $this->desglosar_movimientos($id,$ID_Cuota);


                                                                ++$id;



                                }
                                        
                                        
                                        

                        }

                        
                }       
                        
                list($_y, $_m, $_d) = explode("-",$fecha_dia);
                ++$_d;
                $fecha_dia = date("Y-m-d",mktime(0,0,0,$_m, $_d, $_y));
 
                ++$this->dias_mora_max_cobranza;        


        }



        return($id);


}
//-------------------------------------------------------------------------------------------------------------------------------------------------------------
//
//-------------------------------------------------------------------------------------------------------------------------------------------------------------


function aplica_cargos_extemporaneos_por_cuota($id,$ID_Cuota, $FechaSigMov,  $offSet, $debug="")
{
        
        

    $sql = "SELECT Fecha_Vencimiento FROM cargos WHERE cargos.ID_Cargo = '".$ID_Cuota."' and Num_compra = '".$this->numcompra."'";
    $rs = $this->db->Execute($sql);


     $limite_inferior_dias = $this->limite_ext_inferior_dias;
     $limite_superior_dias = $this->limite_ext_superior_dias;        
        
        
        
        
        
        $_id= $id;

        if(empty($this->FechaCuota))       return($id);

        if(empty($FechaSigMov)) return($id);
                        
        if($this->FechaCuota >= $FechaSigMov)  return($id);
        
        if(empty($this->aplicacion[($id+$offSet)]['Fecha']))  return($id);
        
                
        $_fecha_mov_anterior      = $this->FechaCuota;

        $_fecha_referencia        = $FechaSigMov;

        $_suma_saldos_parciales   = $this->aplicacion[($id+$offSet)]['SALDO_MOV_General'];
        
        if($_suma_saldos_parciales < 2) return($id);        
        
        $dias =  ffdias($_fecha_referencia,$_fecha_mov_anterior);
        
        

        $fecha_dia                = $_fecha_referencia;



        if($dias < $limite_inferior_dias ) return($id);
        
        //$cobranza_automatica= array();
        //$cobranza_automatica[0] =  2;
        //$cobranza_automatica[1] =  9;
        
        
        $cobranza_automatica = $this->extemporaneos_tabla;


        

        
        if($_suma_saldos_parciales>=0.01)
        {
                $i=0;
                $aplicacion = array();
                $dias_acumulados = 0;
                
              if(count($cobranza_automatica) > 0)
                foreach($cobranza_automatica AS $key => $val)
                {
                        
                        $tipo_comision_cobraza      = $key;
                        $dias_tipo_comision_cobraza = $val['dias'] ;
                        $monto_cobranza_automatica  = $val['monto'];


                                if(($dias >= $dias_tipo_comision_cobraza) and ( !$this->cargos_cobranza_automaticos[$ID_Cuota][$tipo_comision_cobraza]))
                                {
                                        list($_y,$_m,$_d) = split("-",$_fecha_mov_anterior);

                                        
                                        $_y_ = $_y;
                                        $_m_ = $_m;
                                        $_d_ = $_d;
                                        
                                        // Cuenta que hallan transcurrido en efecto 2 días hábiles.
                                        if($dias_tipo_comision_cobraza == 2) 
                                        {
                                             $_cuenta_dias_habiles = 0;
                                             
                                             while($_cuenta_dias_habiles <2)
                                             {
                                             
                                                        ++$_d_;

                                                        $fecha_tmp = date("Y-m-d",mktime(0,0,0,$_m_,$_d_,$_y_));

                                                        $dia_tmp   = date("w",mktime(0,0,0,$_m_,$_d_,$_y_));



                                                        $_es_habil  = ($this->fechas_inhabiles[$fecha_tmp]=="");


                                                        if(  $_es_habil and ($dia_tmp > 0) )
                                                        {

                                                                $_cuenta_dias_habiles++;

                                                        }


                                                
                                                
                                                
                                                                                          
                                             }
                                             
                                               $fecha_aplicacion = date("Y-m-d",mktime(0,0,0,$_m_,$_d_,$_y_));
                                             
                                               $dia_de_la_semana = $dia_tmp ;
                                        
                                        
                                        }
                                        else
                                        {
                                        
                                                $fecha_aplicacion = date("Y-m-d",mktime(0,0,0,$_m,($_d + $dias_tipo_comision_cobraza),$_y));
                                                
                                                $dia_de_la_semana = date("w",mktime(0,0,0,$_m,($_d + $dias_tipo_comision_cobraza),$_y));


                                        }
                                        
   

                                  
                                  
                               //   if($_fecha_mov_anterior == "2010-04-30") debug("fecha_aplicacion : ".$fecha_aplicacion);



                                        list($_y,$_m,$_d) = split("-",$fecha_aplicacion);

                                        
                                        $dias_max = $this->historico_morosidad_global[$fecha_aplicacion] ;


                                        
                                        
                                        

/*
                                        // Si es comisión es de 2 días y se cobra el lunes, la pasamos hasta el martes para evitar contar los días domingos que antecen al lunes
                                        if(($dias_tipo_comision_cobraza == 2) and (1 == $dia_de_la_semana))
                                        {
                                           $fecha_aplicacion = date("Y-m-d",mktime(0,0,0,$_m,($_d + 1 + $dias_tipo_comision_cobraza),$_y));
                                        }  
                                        
*/                                      
                                        
                                        
                                        // En cualquier caso nunca cobramos los domingos
                                        if(0 == $dia_de_la_semana )
                                        {
                                           $fecha_aplicacion = date("Y-m-d",mktime(0,0,0,$_m,($_d + 1),$_y));
                                        }
                                           



                                        if(  !empty($this->fechas_inhabiles[$fecha_aplicacion]) )
                                        {
                                             $fecha_aplicacion = $this->fechas_inhabiles[$fecha_aplicacion];
                                        }

                                        








                                        if($fecha_aplicacion > $FechaSigMov) return($id);




                                        if($fecha_aplicacion > $this->fecha_corte)  
                                          return($id);
                                        else
                                        {       

                                                $this->aplicacion[$id]['Fecha_Mov'] = $fecha_aplicacion ; 
                                                $this->aplicacion[$id]['Fecha']     = $fecha_aplicacion;


                                                if($_id == $id)
                                                {
                                                        $this->aplicacion[$id]['DiasAtraso']      = ffdias($this->aplicacion[($id+$offSet)]['Fecha'],$fecha_aplicacion );                               
                                                        $this->aplicacion[$id]['DiasAtrasoAcum'] = ($this->aplicacion[$id]['DiasAtraso'] + $this->aplicacion[($_id+$offSet)]['DiasAtrasoAcum']);



                                                }
                                                else
                                                {
                                                        $this->aplicacion[$id]['DiasAtraso']      = ffdias($fecha_aplicacion, $this->aplicacion[($id-1)]['Fecha']);                             
                                                        $this->aplicacion[$id]['DiasAtrasoAcum']  =($this->aplicacion[$id]['DiasAtraso']  +$dias_acumulados);




                                                }
                                                        //$this->dias_mora = max($this->aplicacion[$id]['DiasAtrasoAcum'],$this->dias_mora);



                                                $this->aplicacion[$id]['Tipo']          = 'Cargo';
                                                $this->aplicacion[$id]['Concepto']      = "Documento";
                                                
                                                
                                                
   
                                                
                                              //  if(($key + 1) == count($cobranza_automatica))
                                                    $this->aplicacion[$id]['Descripcion']   = "<B>Comisión por gastos de cobranza con ".$dias_tipo_comision_cobraza." días mora.</B> "; 
                                              //  else
                                              //      $this->aplicacion[$id]['Descripcion']   = "Cargo por pago extemporáneo ".$dias_tipo_comision_cobraza." a ".($cobranza_automatica[($key + 1)]['dias']-1)." días."; 
                                                
                                                
                                                
                                                
                                                
                                                
                                                $this->aplicacion[$id]['IMB'] = $this->calculo_interes_moratorio_variable(($id),$ID_Cuota,"E");

                                                $dias_acumulados = $this->aplicacion[$id]['DiasAtrasoAcum'];



                                                $this->aplicacion[$id]['ID'] =$this->aplicacion[$id]['IMB']/(1+$this->iva_pcnt_moratorios);


                                                $this->aplicacion[$id]['IM'] =$this->aplicacion[$id]['IMB']/(1+$this->iva_pcnt_moratorios);
                                                $this->aplicacion[$id]['CARGO_IVA']             +=($this->aplicacion[$id]['IMB'] - $this->aplicacion[$id]['IM']);
                                                $this->aplicacion[$id]['CARGO_IVA_Moratorio' ]   =($this->aplicacion[$id]['IMB'] - $this->aplicacion[$id]['IM']);                                       


                                                //$this->aplicacion[$id]['Monto']         = $pcnt * $this->renta; 
                                                

                                                $pIVA =  getIVAComision($this->zona_iva, $fecha_aplicacion, $this->db);     
 
                                                $this->aplicacion[$id]['Monto']         = $monto_cobranza_automatica * (1+$pIVA); 
                                                 
                                                
                                                
                                                

                                                $this->aplicacion[$id]['Cargo']         = $this->aplicacion[$id]['Monto'] ;
                                                $this->SumaCargos                      += $this->aplicacion[$id]['Monto'] ;


                                                $this->aplicacion[$id]['CARGO_Otros']           = $this->aplicacion[$id]['Monto']/(1  + $pIVA);            
                                                $this->aplicacion[$id]['CARGO_IVA_Otros']       = $this->aplicacion[$id]['Monto'] -     $this->aplicacion[$id]['CARGO_Otros'];
                                                $this->aplicacion[$id]['CARGO_IVA']             = $this->aplicacion[$id]['CARGO_IVA_Otros'];            

                                                $this->SumaOtros        += $this->aplicacion[$id]['CARGO_Otros'];                       
                                                $this->SumaIVAOtros     += $this->aplicacion[$id]['CARGO_IVA_Otros'];                   
                                                $this->SumaIVA          += $this->aplicacion[$id]['CARGO_IVA_Otros'];

                                               //  $this->aplicacion[$id]['SaldoCapitalPorVencer']  = $this->capital - $this->SumaCapital;   
                                               //  $this->aplicacion[$id]['SaldoInteresPorVencer']  = $this->interes - $this->SumaInteres;   
                                               //  $this->aplicacion[$id]['SaldoComisionPorVencer'] = $this->comision_por_apertura         -$this->SumaComision;  

                                               $this->aplicacion[$id]['SaldoCapitalPorVencer']  = $this->aplicacion[($id+$offSet)]['SaldoCapitalPorVencer'] ;
                                               $this->aplicacion[$id]['SaldoInteresPorVencer']  = $this->aplicacion[($id+$offSet)]['SaldoInteresPorVencer'] ;
                                               $this->aplicacion[$id]['SaldoComisionPorVencer'] = $this->aplicacion[($id+$offSet)]['SaldoComisionPorVencer']; 

                                               // EL IVA POR DEVENGAR
                                               $this->aplicacion[$id]['SaldoInteres_IVA_PorVencer']  = $this->aplicacion[($id+$offSet)]['SaldoInteres_IVA_PorVencer'] ;
                                               $this->aplicacion[$id]['SaldoComision_IVA_PorVencer'] = $this->aplicacion[($id+$offSet)]['SaldoComision_IVA_PorVencer']; 




                                                $this->aplicacion[$id]['SaldoCapitalPorVencer']   = ($this->aplicacion[$id]['SaldoCapitalPorVencer']<0 )?(0):($this->aplicacion[$id]['SaldoCapitalPorVencer']);
                                                $this->aplicacion[$id]['SaldoInteresPorVencer']   = ($this->aplicacion[$id]['SaldoInteresPorVencer']<0 )?(0):($this->aplicacion[$id]['SaldoInteresPorVencer']);
                                                $this->aplicacion[$id]['SaldoComisionPorVencer']  = ($this->aplicacion[$id]['SaldoComisionPorVencer']<0)?(0):($this->aplicacion[$id]['SaldoComisionPorVencer']);

                                                if($this->aplicacion[$id]['SaldoCapitalPorVencer']<=0)
                                                   $this->aplicacion[$id]['SaldoInteresPorVencer'] = 0;


                                                $this->SaldoCapitalPorVencer    = $this->aplicacion[$id]['SaldoCapitalPorVencer'] ;
                                                $this->SaldoInteresPorVencer    = $this->aplicacion[$id]['SaldoInteresPorVencer'] ; 
                                                $this->SaldoComisionPorVencer   = $this->aplicacion[$id]['SaldoComisionPorVencer'];

                                                $this->aplicacion[$id]['SaldoGlobalCapital']    = $this->aplicacion[$id]['SaldoCapitalPorVencer'];                                      
                                                $this->aplicacion[$id]['SaldoGlobalInteres']    = $this->aplicacion[$id]['SaldoInteresPorVencer'];                              
                                                $this->aplicacion[$id]['SaldoGlobalComision']   = $this->aplicacion[$id]['SaldoComisionPorVencer'];     

                                                if($_id == $id)
                                                {
                                                        $this->aplicacion[$id]['SaldoParcial']          =  $this->aplicacion[$id]['Cargo']  + $this->aplicacion[$id]['IMB'] + $this->aplicacion[($_id+$offSet)]['SaldoParcial'] ;
                                                        $this->aplicacion[$id]['SALDO_General']         += $this->aplicacion[$id]['Cargo']  + $this->aplicacion[$id]['IMB'] + $this->aplicacion[($_id+$offSet)]['SALDO_General'] ;
                                                }
                                                else
                                                {
                                                        $this->aplicacion[$id]['SaldoParcial']          =  $this->aplicacion[$id]['Cargo']  + $this->aplicacion[$id]['IMB'] + $this->aplicacion[($id-1)]['SaldoParcial'] ;
                                                        $this->aplicacion[$id]['SALDO_General']         += $this->aplicacion[$id]['Cargo']  + $this->aplicacion[$id]['IMB'] + $this->aplicacion[($id-1)]['SALDO_General'] ;
                                                }

                                                $this->desglosar_movimientos($id,$ID_Cuota);

                                                $_suma_saldos_parciales += $this->aplicacion[$id]['IMB'] + $this->aplicacion[$id]['Cargo'];
                                                $this->cargos_cobranza_automaticos[$ID_Cuota][$tipo_comision_cobraza] = true;

                                                $id++;

                                }
                                
                                
                                $this->historico_morosidad_por_couta[$ID_Cuota][$fecha_aplicacion]['DiasAtraso']       = $this->aplicacion[($id+$offSet)]['DiasAtrasoAcum'];
                                $this->historico_morosidad_por_couta[$ID_Cuota][$fecha_aplicacion]['Monto']            = $this->aplicacion[($id+$offSet)]['Abono']+$this->aplicacion[($id+$offSet)]['Cargo']+$this->aplicacion[($id+$offSet)]['SaldoFavorAplicado'];
                                $this->historico_morosidad_por_couta[$ID_Cuota][$fecha_aplicacion]['SALDO_General']    = $this->aplicacion[($id+$offSet)]['SALDO_General'];
                                
                        }
                
                }
        
        
        
        
        
        
        
        
        }


        return($id);
}



//-------------------------------------------------------------------------------------------------------------------------------------------------------------
// METODO DEPRECIADO EN FAVOR DE aplica_cargos_extemporaneos_general($id,$ID_Cuota, $FechaSigMov,  $offSet, $dbg="")
//-------------------------------------------------------------------------------------------------------------------------------------------------------------
function aplica_cargos_cobranza_general($id,$ID_Cuota, $FechaSigMov,  $offSet, $dbg="")
{
        if(empty($this->FechaCuota))                            return($id);

        if(empty($FechaSigMov))                                 return($id);
                                
        if(empty($this->aplicacion[($id+$offSet)]['Fecha']))    return($id);
                
        if($this->pcnt_extemporaneos == 0)                      return($id);
        
        if($this->aplicacion[($id+$offSet)]['SALDO_General']<= 0.01) return($id);
        
        if(($this->is_vencimiento_anticipado) and ($ID_Cuota == $this->id_cargo_reemplazo_vencimiento_anticipado)) return($id);


        
        
        $_offset=0;        
        $_id= $id;
        $_fecha_referencia        = $this->aplicacion[($id+$offSet)]['Fecha'];
        $fecha_dia                = $_fecha_referencia;
        
        


        //---------------------------------------------------------------------------------
        //Guarda la referencia por cada cota de las fechas en que existen dias de atraso
        //---------------------------------------------------------------------------------

        $this->historico_morosidad_por_couta[$ID_Cuota][$fecha_dia]['DiasAtraso']       = $this->aplicacion[($id+$offSet)]['DiasAtrasoAcum'];
        $this->historico_morosidad_por_couta[$ID_Cuota][$fecha_dia]['Monto']            = $this->aplicacion[($id+$offSet)]['Abono']+$this->aplicacion[($id+$offSet)]['Cargo']+$this->aplicacion[($id+$offSet)]['SaldoFavorAplicado'];
        $this->historico_morosidad_por_couta[$ID_Cuota][$fecha_dia]['SALDO_General']    = $this->aplicacion[($id+$offSet)]['SALDO_General'];
        //---------------------------------------------------------------------------------


        if($this->SaldoGeneralVencido<0.01)
        {
                $this->dias_mora_max_cobranza                           = $this->aplicacion[($id+$offSet)]['DiasAtrasoAcum'];
                $this->historico_morosidad_cobranza[$fecha_dia]         = "";   //$this->historico_morosidad_global[$_fecha_referencia];        //$this->aplicacion[($id+$offSet)]['DiasAtraso'];
                
                $this->historico_morosidad_diferencial_cobranza[$fecha_dia] = "X";
                $this->historico_diferencial_continuo[$fecha_dia] = "Y";
            

                $this->hmdc[$fecha_dia][3] = ffecha($this->aplicacion[($id+$offSet)]['Fecha']);
                $this->hmdc[$fecha_dia][4] = $this->aplicacion[($id+$offSet)]['Abono']; 
                $this->hmdc[$fecha_dia][5] = $this->aplicacion[($id+$offSet)]['DiasAtrasoAcum'];
                $this->hmdc[$fecha_dia][6] = $this->aplicacion[($id+$offSet)]['SALDO_General'];

                $this->historico_morosidad_por_couta[$ID_Cuota][$fecha_dia]['SALDO_General']  = "X";


            return($id);
        }

        
        
        
        
        list($_y, $_m, $_d) = explode("-",$fecha_dia);

        //list($_y, $_m, $_d) = explode("-",$_fecha_referencia);
        //$fecha_dia = date("Y-m-d",mktime(0,0,0,$_m, ($_d+1), $_y));
        
        
        
        
        $dias_acumulados = 0;
        
        
        $this->dias_mora_max_cobranza = max($this->historico_morosidad_global[$_fecha_referencia],$this->dias_mora_max_cobranza);


        
        
        
        while ($fecha_dia < $FechaSigMov )
        {




                list($_y, $_m, $_d) = explode("-",$fecha_dia);
                        

                if(! $this->historico_morosidad_cobranza[$fecha_dia] )                                  
                { 

                        
                        
                        
                        //---------------------------------------------------------------------------------                     
                        //Mantenemos actualizado el contador de días por cada cuota y el ctrol de traslapes de fecha
                        //---------------------------------------------------------------------------------                     
                        
                        $fecha_anterior = ($fecha_dia == $_fecha_referencia)?($fecha_dia ):(date("Y-m-d",mktime(0,0,0,$_m, ($_d-1), $_y)));                     
                        $this->historico_morosidad_por_couta[$ID_Cuota][$fecha_dia]['DiasAtraso']       = $this->historico_morosidad_por_couta[$ID_Cuota][$fecha_anterior]['DiasAtraso']+1; //"x";
/*
                        if(! is_array($this->fechas_cuota[$fecha_dia])) 
                        {
                                $this->fechas_cuota[$fecha_dia]=array();
                                $this->fechas_cuota[$fecha_dia][0]=$ID_Cuota;
                        
                        }
                        else
                        if(! in_array($ID_Cuota, $this->fechas_cuota[$fecha_dia]))
                        {

                                $this->fechas_cuota[$fecha_dia][(count($this->fechas_cuota[$fecha_dia]))]=$ID_Cuota;
                        }
*/
                        //---------------------------------------------------------------------------------                     
                        //                              
                        //---------------------------------------------------------------------------------                     
                        
                        $fecha_ayer = date("Y-m-d",mktime(0,0,0,$_m, ($_d-1), $_y));

                                
                                if(($this->aplicacion[($id+$offSet)]['Tipo']=='Abono') and ($this->aplicacion[($id+$offSet)]['Fecha']==$fecha_dia) )
                                {
                                        
                                        
                                        
                                        
                                        
                                        if($this->aplicacion[($id+$offSet)]['SALDO_General']<0.01)
                                                $this->historico_morosidad_cobranza[$fecha_dia]=($this->aplicacion[($id+$offSet)]['DiasAtrasoAcum']);
                                        else                                    
                                                $this->historico_morosidad_cobranza[$fecha_dia] =  max(($this->aplicacion[($id+$offSet)]['DiasAtrasoAcum']),($this->historico_morosidad_por_couta[$ID_Cuota][$fecha_dia]['DiasAtraso']));
                                        
/*                                      
                                        if($fecha_dia == '2009-07-03')
                                        {
                                                
                                                
                                                debug("B] max((this->aplicacion[($id+$offSet)]['DiasAtrasoAcum']),(this->historico_morosidad_por_couta[$ID_Cuota][$fecha_dia]['DiasAtraso'])) =  max(".($this->aplicacion[($id+$offSet)]['DiasAtrasoAcum']).",".($this->historico_morosidad_por_couta[$ID_Cuota][$fecha_dia]['DiasAtraso']).") ") ;
                                                
                                                
                                                
                                                debug("C]  this->historico_morosidad_cobranza[$fecha_dia]  =  ". $this->historico_morosidad_cobranza[$fecha_dia]);
                                        
                                        
                                                debug("Punto de enrada : [".$dbg."]");
                                                
                                                
                                                debug("this->aplicacion[($id+$offSet)]['Tipo']          == ".$this->aplicacion[($id+$offSet)]['Tipo']);
                                                debug("this->aplicacion[($id+$offSet)]['Fecha']         == ".$this->aplicacion[($id+$offSet)]['Fecha']);
                                                debug("this->aplicacion[($id+$offSet)]['Concepto']      == ".$this->aplicacion[($id+$offSet)]['Concepto']);
                                                debug("this->aplicacion[($id+$offSet)]['Descripcion']   == ".$this->aplicacion[($id+$offSet)]['Descripcion']);
                                                debug("this->aplicacion[($id+$offSet)]['Monto']         == ".$this->aplicacion[($id+$offSet)]['Monto']);
                                                debug("this->aplicacion[($id+$offSet)]['DiasAtraso']    == ".$this->aplicacion[($id+$offSet)]['DiasAtraso']);
                                                debug("this->aplicacion[($id+$offSet)]['DiasAtrasoAcum']== ".$this->aplicacion[($id+$offSet)]['DiasAtrasoAcum']);




                                        }
*/                                      
                                        
                                }
                                else
                                {                                       
                                        $this->historico_morosidad_cobranza[$fecha_dia]=$this->historico_morosidad_cobranza[$fecha_ayer]+1;
                                }

                        //---------------------------------------------------------------------------------                     
                        // Si se reinician el historico_morosidad_global, debemos reiniciar el contador historico_morosidad_diferencial_cobranza                
                        //---------------------------------------------------------------------------------                     
                        if(($this->historico_morosidad_cobranza[$fecha_dia] == 1))
                        {
                                        // Como se reinicia el contador cuando la cuota queda saldada y no hay traslape.
                                    $this->historico_morosidad_diferencial_cobranza[$fecha_dia] = 1;
                                    $this->historico_diferencial_continuo[$fecha_dia]           = 1;
                        
                        }
                           else
                                if($this->historico_morosidad_cobranza[$fecha_dia] <= $this->historico_morosidad_cobranza[$fecha_ayer] )
                                {

                                        // Como se reinicia el contador cuando la cuota cuota queda saldada y hay traslape.con otras cuotas.

                                        $this->historico_morosidad_diferencial_cobranza[$fecha_dia] =  1;
                                        $this->historico_diferencial_continuo[$fecha_dia]           =  1;
                                }
                                else
                                        {

                                                $this->historico_morosidad_diferencial_cobranza[$fecha_dia]     = $this->historico_morosidad_diferencial_cobranza[$fecha_ayer]+1;
                                                $this->historico_diferencial_continuo[$fecha_dia]               = $this->historico_diferencial_continuo[$fecha_ayer]+1;
                                        }


                        



                        $dias_max = $this->historico_morosidad_diferencial_cobranza[$fecha_dia];
                        //$dias_max =  $this->historico_morosidad_cobranza[$fecha_dia];

                        if(($this->is_renovacion) and ($fecha_dia < '2008-09-01'))
                        {
                                // Los créditos etiquetados como "renovacion" no aplican para cargos de cobranza en fechas anteriores al 2008-09-01'
                                
                        }
                        else                    // or ($dias_max == 60) )
                          if((($dias_max == 7) or ($dias_max == 30)  or ($dias_max == 60) or ($dias_max == 90))         and 
                              ($this->SaldoGeneralVencido>0.01)                                 and 
                              ($this->historico_diferencial_continuo[$fecha_ayer] != $this->historico_diferencial_continuo[$fecha_dia]))
                          {


                                $fecha_cero = date("Y-m-d",mktime(0,0,0,$_m, ($_d - ($this->historico_diferencial_continuo[$fecha_dia] -1)), $_y));
                                $fecha_zero = date("Y-m-d",mktime(0,0,0,$_m, ($_d - ($this->historico_diferencial_continuo[$fecha_dia] +0)), $_y));

                                $valor_cero = $this->historico_morosidad_cobranza[$fecha_cero];
                                $valor_zero = $this->historico_morosidad_cobranza[$fecha_zero];
                                
                                $this->hmdc[$fecha_dia][0] = $fecha_cero;
                                $this->hmdc[$fecha_dia][1] = $valor_cero;
                                //$this->hmdc[$fecha_dia][2] = $fecha_zero;                                             
                                //$this->hmdc[$fecha_dia][3] = $valor_zero;                                             
                                
                                //if($this->historico_diferencial_continuo[$fecha_dia] ==  $this->historico_morosidad_cobranza[$fecha_dia])
                                
                                if($valor_cero <=($dias_max))
                                {
                                
                                        $pIVA =  getIVAComision($this->zona_iva, $fecha_dia, $this->db);     
                                        
                                        $_fecha_aplicacion_cargo = date("Y-m-d",mktime(0,0,0,$_m,(  $_d+1),$_y));

                                        $_pcnt_extemporaneos     = $this->busca_tasa_cobranza($_fecha_aplicacion_cargo);
                                        if($_pcnt_extemporaneos < 0)
                                        {
                                                $_pcnt_extemporaneos = $this->pcnt_extemporaneos;
                                        }
                                        
                                        //if($_pcnt_extemporaneos == 0) return($id);
                                        
                                        $this->cargos_cobranza_automaticos[$fecha_dia] = ($_pcnt_extemporaneos * $this->renta * (1+$pIVA));
                                                                
                                       //debug( $this->cargos_cobranza_automaticos[$fecha_dia]." = (".$this->pcnt_extemporaneos." * ".$this->renta." * (1+$pIVA)");


                                                                
                                                                $this->aplicacion[$id]['CARGO_COBRANZA'] = true;
                                                                
                                                                
                                                                $this->aplicacion[$id]['Fecha_Mov'] = $_fecha_aplicacion_cargo ; 
                                                                $this->aplicacion[$id]['Fecha']     = $_fecha_aplicacion_cargo;
                                                                

 
                                                                if($_id == $id)
                                                                {
                                                                        $this->aplicacion[$id]['DiasAtraso']      = ffdias($this->aplicacion[($id+$offSet)]['Fecha'],$_fecha_aplicacion_cargo );                                
                                                                        $this->aplicacion[$id]['DiasAtrasoAcum'] = ($this->aplicacion[$id]['DiasAtraso'] + $this->aplicacion[($_id+$offSet)]['DiasAtrasoAcum']);
                                                                }
                                                                else
                                                                {
                                                                        $this->aplicacion[$id]['DiasAtraso']      = ffdias($fecha_dia, $this->aplicacion[($id-1)]['Fecha']); //+1;                            
                                                                        $this->aplicacion[$id]['DiasAtrasoAcum']  =($this->aplicacion[$id]['DiasAtraso']  +$dias_acumulados);
                                                                }



                                                                $this->aplicacion[$id]['Tipo']          = 'Cargo';
                                                                $this->aplicacion[$id]['Concepto']      = "Documento";
                                                                $this->aplicacion[$id]['Descripcion']   = "<B>Comisión por gastos de cobranza ".$dias_max ." días.</B> ";//($id punto : ".$debug."[$dias_max]  FechaSigMov $FechaSigMov) $XX "; //(".$dias_max .") (".$debug.")"; //  (".$this->aplicacion[($id+$offSet)]['Fecha']." al ".$fecha_aplicacion." )   "; //($_fecha_mov_anterior  al  $FechaSigMov) : $dias días";

                                                                $this->aplicacion[$id]['IMB'] = $this->calculo_interes_moratorio_variable(($id),$ID_Cuota,"Z");

                                                                $dias_acumulados = $this->aplicacion[$id]['DiasAtrasoAcum'];


                                                               //$this->aplicacion[$id]['ID'] =$this->aplicacion[$id]['IMB']/(1+$this->iva_pcnt_moratorios);


                                                                //$this->aplicacion[$id]['IM'] =$this->aplicacion[$id]['IMB']/(1+$this->iva_pcnt_moratorios);
                                                                $this->aplicacion[$id]['CARGO_IVA']             +=($this->aplicacion[$id]['IMB'] - $this->aplicacion[$id]['IM']);
                                                                $this->aplicacion[$id]['CARGO_IVA_Moratorio' ]  +=($this->aplicacion[$id]['IMB'] - $this->aplicacion[$id]['IM']);                                       


                                                                
                                                                
                                                                $this->aplicacion[$id]['Monto']         = $this->cargos_cobranza_automaticos[$fecha_dia];

        

                                                                $this->aplicacion[$id]['Cargo']         = $this->aplicacion[$id]['Monto'] ;

                                                                
                                                                
                                                                
     

                                                                //$this->aplicacion[$id]['CARGO_Otros']           = $this->aplicacion[$id]['Monto']/(1  + $this->iva_pcnt_comisiones);            
                                                                
                                                                $pIVA =   getIVA($this->zona_iva, $fecha_dia, $this->db);                                                                
                                                                $this->aplicacion[$id]['CARGO_Otros']           = $this->aplicacion[$id]['Monto']/(1  + $pIVA);            
                                                               
                                                            //   debug("$pIVA =   getIVA(".$this->zona_iva.", ".$fecha_dia.", this->db);  ");
                                                               
                                                            //   debug("this->aplicacion[$id]['CARGO_Otros'] = ".$this->aplicacion[$id]['CARGO_Otros']." = ".$this->aplicacion[$id]['Monto']."/(1  + $pIVA);");



                                                                
                                                                $this->aplicacion[$id]['CARGO_IVA_Otros']       = $this->aplicacion[$id]['Monto'] -     $this->aplicacion[$id]['CARGO_Otros'];
                                                                $this->aplicacion[$id]['CARGO_IVA']             += $this->aplicacion[$id]['CARGO_IVA_Otros'];           

                                                                $this->SumaOtros        += $this->aplicacion[$id]['CARGO_Otros'];                       
                                                                $this->SumaIVAOtros     += $this->aplicacion[$id]['CARGO_IVA_Otros'];                   
                                                                $this->SumaIVA          += $this->aplicacion[$id]['CARGO_IVA_Otros'];

                                                                $this->aplicacion[$id]['SaldoCapitalPorVencer']  = $this->aplicacion[($id+$offSet)]['SaldoCapitalPorVencer'] ;
                                                                $this->aplicacion[$id]['SaldoInteresPorVencer']  = $this->aplicacion[($id+$offSet)]['SaldoInteresPorVencer']  ;
                                                                $this->aplicacion[$id]['SaldoComisionPorVencer'] = $this->aplicacion[($id+$offSet)]['SaldoComisionPorVencer'] ; 

                                                                $this->aplicacion[$id]['SaldoInteres_IVA_PorVencer']  = $this->aplicacion[($id+$offSet)]['SaldoInteres_IVA_PorVencer']  ;
                                                                $this->aplicacion[$id]['SaldoComision_IVA_PorVencer'] = $this->aplicacion[($id+$offSet)]['SaldoComision_IVA_PorVencer'] ; 


                                                                $this->aplicacion[$id]['SaldoSegVPorVencer']  = $this->aplicacion[($id+$offSet)]['SaldoSegVPorVencer'] ;
                                                                $this->aplicacion[$id]['SaldoSegDPorVencer']  = $this->aplicacion[($id+$offSet)]['SaldoSegDPorVencer']  ;
                                                                $this->aplicacion[$id]['SaldoSegBPorVencer']  = $this->aplicacion[($id+$offSet)]['SaldoSegBPorVencer'] ; 

                                                                $this->aplicacion[$id]['SaldoSegV_IVA_PorVencer']  = $this->aplicacion[($id+$offSet)]['SaldoSegV_IVA_PorVencer'] ;
                                                                $this->aplicacion[$id]['SaldoSegD_IVA_PorVencer']  = $this->aplicacion[($id+$offSet)]['SaldoSegD_IVA_PorVencer']  ;
                                                                $this->aplicacion[$id]['SaldoSegB_IVA_PorVencer']  = $this->aplicacion[($id+$offSet)]['SaldoSegB_IVA_PorVencer'] ; 



                                                                $this->aplicacion[$id]['SaldoGlobalCapital']    = $this->aplicacion[$id]['SaldoCapitalPorVencer'];                                      
                                                                $this->aplicacion[$id]['SaldoGlobalInteres']    = $this->aplicacion[$id]['SaldoInteresPorVencer'];                              
                                                                $this->aplicacion[$id]['SaldoGlobalComision']   = $this->aplicacion[$id]['SaldoComisionPorVencer'];     

                                                                $this->aplicacion[$id]['SaldoGlobalSegV']       = $this->aplicacion[$id]['SaldoSegVPorVencer'];                                      
                                                                $this->aplicacion[$id]['SaldoGlobalSegD']       = $this->aplicacion[$id]['SaldoSegDPorVencer'];                              
                                                                $this->aplicacion[$id]['SaldoGlobalSegB']       = $this->aplicacion[$id]['SaldoSegBPorVencer'];     






                                                                if($_id == $id)
                                                                {
                                                                        $this->aplicacion[$id]['SaldoParcial']          =  $this->aplicacion[$id]['Cargo']  + $this->aplicacion[$id]['IMB'] + $this->aplicacion[($_id+$offSet)]['SaldoParcial'] ;
                                                                        $this->aplicacion[$id]['SALDO_General']         += $this->aplicacion[$id]['Cargo']  + $this->aplicacion[$id]['IMB'] + $this->aplicacion[($_id+$offSet)]['SALDO_General'] ;
                                                                        if($this->aplicacion[($id-1)]['SALDO_MOV_General'] < 0)
                                                                        {
                                                                          $this->aplicacion[$id]['SALDO_General']       += $this->aplicacion[($_id+$offSet)]['SALDO_MOV_General'];
                                                                        }
                                                                        
                                                                        
                                                                        

                                                                        
                                                                }
                                                                else
                                                                {
                                                                        $this->aplicacion[$id]['SaldoParcial']          =  $this->aplicacion[$id]['Cargo']  + $this->aplicacion[$id]['IMB'] + $this->aplicacion[($id-1)]['SaldoParcial'] ;
                                                                        $this->aplicacion[$id]['SALDO_General']         += $this->aplicacion[$id]['Cargo']  + $this->aplicacion[$id]['IMB'] + $this->aplicacion[($id-1)]['SALDO_General'] ;
                                                                        if($this->aplicacion[($id-1)]['SALDO_MOV_General'] < 0)
                                                                        {
                                                                          $this->aplicacion[$id]['SALDO_General']       += $this->aplicacion[($id-1)]['SALDO_MOV_General'];
                                                                        }
                                                                        
                                                                }

                                                                $this->desglosar_movimientos($id,$ID_Cuota);


                                                                ++$id;



                                }
                                        
                                        
                                        

                        }

                        
                }       
                        
                list($_y, $_m, $_d) = explode("-",$fecha_dia);
                ++$_d;
                $fecha_dia = date("Y-m-d",mktime(0,0,0,$_m, $_d, $_y));
 
                ++$this->dias_mora_max_cobranza;        


        }



        return($id);


}

//-------------------------------------------------------------------------------------------------------------------------------------------------------------


function ajuste_saldos($i)
{

	$minimo_depurable = 0.005;

        $this->adeudo_total                     = (abs($this->adeudo_total      )<$minimo_depurable )?(0):($this->adeudo_total        );


        $this->SaldoTotalParaLiquidar           = (abs($this->SaldoTotalParaLiquidar    )<$minimo_depurable )?(0):($this->SaldoTotalParaLiquidar      );
        $this->SaldoPendienteAplicar            = (abs($this->SaldoPendienteAplicar     )<$minimo_depurable )?(0):($this->SaldoPendienteAplicar       );
        
        
        $this->SALDO_General                    = (abs($this->SALDO_General             )<$minimo_depurable )?(0):($this->SALDO_General               );



        $this->SaldoCapitalPorVencer            = (abs($this->SaldoCapitalPorVencer     )<$minimo_depurable )?(0):($this->SaldoCapitalPorVencer       );       
        $this->SaldoCapital                     = (abs($this->SaldoCapital              )<$minimo_depurable )?(0):($this->SaldoCapital                );                      


        $this->SaldoInteresPorVencer            = (abs($this->SaldoInteresPorVencer     )<$minimo_depurable )?(0):($this->SaldoInteresPorVencer       );              
        $this->SaldoInteres                     = (abs($this->SaldoInteres              )<$minimo_depurable )?(0):($this->SaldoInteres                );      
        $this->SaldoIVAInteres                  = (abs($this->SaldoIVAInteres           )<$minimo_depurable )?(0):($this->SaldoIVAInteres             );      
        


        $this->SaldoSegVPorVencer            = (abs($this->SaldoSegVPorVencer     )<$minimo_depurable )?(0):($this->SaldoSegVPorVencer       );              
        $this->SaldoSegV                     = (abs($this->SaldoSegV              )<$minimo_depurable )?(0):($this->SaldoSegV                );      
        $this->SaldoIVASegV                  = (abs($this->SaldoIVASegV           )<$minimo_depurable )?(0):($this->SaldoIVASegV             );      


        $this->SaldoSegDPorVencer            = (abs($this->SaldoSegDPorVencer     )<$minimo_depurable )?(0):($this->SaldoSegDPorVencer       );              
        $this->SaldoSegD                     = (abs($this->SaldoSegD              )<$minimo_depurable )?(0):($this->SaldoSegD                );      
        $this->SaldoIVASegD                  = (abs($this->SaldoIVASegD           )<$minimo_depurable )?(0):($this->SaldoIVASegD             );      


        $this->SaldoSegBPorVencer            = (abs($this->SaldoSegBPorVencer     )<$minimo_depurable )?(0):($this->SaldoSegBPorVencer       );              
        $this->SaldoSegB                     = (abs($this->SaldoSegB              )<$minimo_depurable )?(0):($this->SaldoSegB                );      
        $this->SaldoIVASegB                  = (abs($this->SaldoIVASegB           )<$minimo_depurable )?(0):($this->SaldoIVASegB             );      










        $this->SaldoComisionPorVencer           = (abs($this->SaldoComisionPorVencer    )<$minimo_depurable )?(0):($this->SaldoComisionPorVencer      );              
        $this->SaldoComision                    = (abs($this->SaldoComision             )<$minimo_depurable )?(0):($this->SaldoComision               );      
        $this->SaldoIVAComision                 = (abs($this->SaldoIVAComision          )<$minimo_depurable )?(0):($this->SaldoIVAComision            );      

        
        $this->SaldoIMB                         = (abs($this->SaldoIMB                  )<$minimo_depurable )?(0):($this->SaldoIMB                    );      
        $this->SaldoIM                          = (abs($this->SaldoIM                   )<$minimo_depurable )?(0):($this->SaldoIM                     );      


        $this->SaldoOtros                       = (abs($this->SaldoOtros                )<$minimo_depurable )?(0):($this->SaldoOtros                  );      
        $this->SaldoIVAOtros                    = (abs($this->SaldoIVAOtros             )<$minimo_depurable )?(0):($this->SaldoIVAOtros               );      


        $this->SaldoFavorPendiente              = (abs($this->SaldoFavorPendiente       )<$minimo_depurable )?(0):($this->SaldoFavorPendiente         );      

        $this->SaldoGlobalCapital               = (abs($this->SaldoGlobalCapital        )<$minimo_depurable )?(0):($this->SaldoGlobalCapital          );              
        $this->SaldoGlobalInteres               = (abs($this->SaldoGlobalInteres        )<$minimo_depurable )?(0):($this->SaldoGlobalInteres          );              
        $this->SaldoGlobalComision              = (abs($this->SaldoGlobalComision       )<$minimo_depurable )?(0):($this->SaldoGlobalComision         );              

        $this->SaldoGlobalOtros                 = (abs($this->SaldoGlobalOtros          )<$minimo_depurable )?(0):($this->SaldoGlobalOtros            );      
        $this->SaldoGlobalMoratorio             = (abs($this->SaldoGlobalMoratorio      )<$minimo_depurable )?(0):($this->SaldoGlobalMoratorio        );



        $this->SaldoGlobalSegV                  = (abs($this->SaldoGlobalSegV           )<$minimo_depurable )?(0):($this->SaldoGlobalSegV         );              
        $this->SaldoGlobalSegD                  = (abs($this->SaldoGlobalSegD           )<$minimo_depurable )?(0):($this->SaldoGlobalSegD         );              
        $this->SaldoGlobalSegB                  = (abs($this->SaldoGlobalSegB           )<$minimo_depurable )?(0):($this->SaldoGlobalSegB         );              






        $this->SaldoGlobalIVA                   = (abs($this->SaldoGlobalIVA            )<$minimo_depurable )?(0):($this->SaldoGlobalIVA              );

        $this->SaldoGlobalGeneral               = (abs($this->SaldoGlobalGeneral        )<$minimo_depurable )?(0):($this->SaldoGlobalGeneral          );      




        $id=count($this->aplicacion)-1;


        $this->aplicacion[$id]['SALDO_MOV_Capital']             = (abs($this->aplicacion[$id]['SALDO_MOV_Capital']      )<$minimo_depurable )?(0):($this->aplicacion[$id]['SALDO_MOV_Capital']        );      
        $this->aplicacion[$id]['SALDO_MOV_Interes']             = (abs($this->aplicacion[$id]['SALDO_MOV_Interes']      )<$minimo_depurable )?(0):($this->aplicacion[$id]['SALDO_MOV_Interes']        );      
        $this->aplicacion[$id]['SALDO_MOV_Moratorio']           = (abs($this->aplicacion[$id]['SALDO_MOV_Moratorio']    )<$minimo_depurable )?(0):($this->aplicacion[$id]['SALDO_MOV_Moratorio']      );      
        $this->aplicacion[$id]['SALDO_MOV_Comision']            = (abs($this->aplicacion[$id]['SALDO_MOV_Comision']     )<$minimo_depurable )?(0):($this->aplicacion[$id]['SALDO_MOV_Comision']       );      
        $this->aplicacion[$id]['SALDO_MOV_Otros']               = (abs($this->aplicacion[$id]['SALDO_MOV_Otros']        )<$minimo_depurable )?(0):($this->aplicacion[$id]['SALDO_MOV_Otros']          );      
        $this->aplicacion[$id]['SALDO_MOV_General']             = (abs($this->aplicacion[$id]['SALDO_MOV_General']      )<$minimo_depurable )?(0):($this->aplicacion[$id]['SALDO_MOV_General']        );      



        $this->aplicacion[$id]['SALDO_MOV_SegV']                = (abs($this->aplicacion[$id]['SALDO_MOV_SegV']     )<$minimo_depurable )?(0):($this->aplicacion[$id]['SALDO_MOV_SegV']       );      
        $this->aplicacion[$id]['SALDO_MOV_SegD']                = (abs($this->aplicacion[$id]['SALDO_MOV_SegD']     )<$minimo_depurable )?(0):($this->aplicacion[$id]['SALDO_MOV_SegD']       );      
        $this->aplicacion[$id]['SALDO_MOV_SegB']                = (abs($this->aplicacion[$id]['SALDO_MOV_SegB']     )<$minimo_depurable )?(0):($this->aplicacion[$id]['SALDO_MOV_SegB']       );      









        $this->aplicacion[$id]['SALDO_MOV_IVA_Interes']         = (abs($this->aplicacion[$id]['SALDO_MOV_IVA_Interes']  )<$minimo_depurable )?(0):($this->aplicacion[$id]['SALDO_MOV_IVA_Interes']    );      
        $this->aplicacion[$id]['SALDO_MOV_IVA_Moratorio']       = (abs($this->aplicacion[$id]['SALDO_MOV_IVA_Moratorio'])<$minimo_depurable )?(0):($this->aplicacion[$id]['SALDO_MOV_IVA_Moratorio']  );      
        $this->aplicacion[$id]['SALDO_MOV_IVA_Comision']        = (abs($this->aplicacion[$id]['SALDO_MOV_IVA_Comision'] )<$minimo_depurable )?(0):($this->aplicacion[$id]['SALDO_MOV_IVA_Comision']   );      
        $this->aplicacion[$id]['SALDO_MOV_IVA_Otros']           = (abs($this->aplicacion[$id]['SALDO_MOV_IVA_Otros']    )<$minimo_depurable )?(0):($this->aplicacion[$id]['SALDO_MOV_IVA_Otros']      );      
        $this->aplicacion[$id]['SALDO_MOV_IVA']                 = (abs($this->aplicacion[$id]['SALDO_MOV_IVA']          )<$minimo_depurable )?(0):($this->aplicacion[$id]['SALDO_MOV_IVA']            );      


        $this->aplicacion[$id]['SALDO_MOV_IVA_SegV']            = (abs($this->aplicacion[$id]['SALDO_MOV_IVA_SegV'] )<$minimo_depurable )?(0):($this->aplicacion[$id]['SALDO_MOV_IVA_SegV']   );      
        $this->aplicacion[$id]['SALDO_MOV_IVA_SegD']            = (abs($this->aplicacion[$id]['SALDO_MOV_IVA_SegD'] )<$minimo_depurable )?(0):($this->aplicacion[$id]['SALDO_MOV_IVA_SegD']   );      
        $this->aplicacion[$id]['SALDO_MOV_IVA_SegB']            = (abs($this->aplicacion[$id]['SALDO_MOV_IVA_SegB'] )<$minimo_depurable )?(0):($this->aplicacion[$id]['SALDO_MOV_IVA_SegB']   );      







        $this->aplicacion[$id]['SALDO_MOV_Pendiente_Aplicar']   = (abs($this->aplicacion[$id]['SALDO_MOV_Pendiente_Aplicar'])<$minimo_depurable )?(0):($this->aplicacion[$id]['SALDO_MOV_Pendiente_Aplicar']);


        $this->aplicacion[$id]['SaldoGlobalCapital']    = (abs($this->aplicacion[$id]['SaldoGlobalCapital']     )<$minimo_depurable )?(0):($this->aplicacion[$id]['SaldoGlobalCapital']       );
        $this->aplicacion[$id]['SaldoGlobalInteres']    = (abs($this->aplicacion[$id]['SaldoGlobalInteres']     )<$minimo_depurable )?(0):($this->aplicacion[$id]['SaldoGlobalInteres']       );
        $this->aplicacion[$id]['SaldoGlobalComision']   = (abs($this->aplicacion[$id]['SaldoGlobalComision']    )<$minimo_depurable )?(0):($this->aplicacion[$id]['SaldoGlobalComision']      );
        $this->aplicacion[$id]['SaldoGlobalOtros']      = (abs($this->aplicacion[$id]['SaldoGlobalOtros']       )<$minimo_depurable )?(0):($this->aplicacion[$id]['SaldoGlobalOtros']         );
        $this->aplicacion[$id]['SaldoGlobalMoratorio']  = (abs($this->aplicacion[$id]['SaldoGlobalMoratorio']   )<$minimo_depurable )?(0):($this->aplicacion[$id]['SaldoGlobalMoratorio']     );


        $this->aplicacion[$id]['SaldoGlobalIVA']        = (abs($this->aplicacion[$id]['SaldoGlobalIVA']         )<$minimo_depurable )?(0):($this->aplicacion[$id]['SaldoGlobalIVA']           );

        $this->aplicacion[$id]['SaldoGlobalSegV']   = (abs($this->aplicacion[$id]['SaldoGlobalSegV']    )<$minimo_depurable )?(0):($this->aplicacion[$id]['SaldoGlobalSegV']      );
        $this->aplicacion[$id]['SaldoGlobalSegD']   = (abs($this->aplicacion[$id]['SaldoGlobalSegD']    )<$minimo_depurable )?(0):($this->aplicacion[$id]['SaldoGlobalSegD']      );
        $this->aplicacion[$id]['SaldoGlobalSegB']   = (abs($this->aplicacion[$id]['SaldoGlobalSegB']    )<$minimo_depurable )?(0):($this->aplicacion[$id]['SaldoGlobalSegB']      );






        $this->aplicacion[$id]['SALDO_General']         = (abs($this->aplicacion[$id]['SALDO_General']          )<$minimo_depurable )?(0):($this->aplicacion[$id]['SALDO_General']            );
        $this->aplicacion[$id]['SaldoParcial']          = (abs($this->aplicacion[$id]['SaldoParcial']           )<$minimo_depurable )?(0):($this->aplicacion[$id]['SaldoParcial']             );






        if(($this->adeudo_total < $minimo_depurable ) or ($this->SaldoGeneralVencido <$minimo_depurable ))
        {
                $this->dias_mora = 0;
                $this->numcargosvencidos_pagados        = $this->numcargosvencidos;
                $this->numcargosvencidos_no_pagados     = 0;
                
        }




/*

        $_SaldoGeneralVencido                   =       number_format($this->SaldoCapital,4,".","")+
                                                        number_format($this->SaldoIMB    ,4,".","")+
                                                        number_format(($this->SaldoInteres      *   (1+ $this->iva_pcnt_intereses )),4,".","")+
                                                        number_format(($this->SaldoComision     *   (1+ $this->iva_pcnt_comisiones)),4,".","")+
                                                        number_format(($this->SaldoOtros        + $this->SaldoIVAOtros          ),4,".","");
*/
        $_SaldoGeneralVencido                   =       number_format( $this->SaldoCapital,4,".","") +
                                                        number_format( $this->SaldoIMB    ,4,".","") +
                                                        number_format(($this->SaldoInteres      + $this->SaldoIVAInteres        ),4,".","")+
                                                        number_format(($this->SaldoComision     + $this->SaldoIVAComision       ),4,".","")+
                                                        number_format(($this->SaldoOtros        + $this->SaldoIVAOtros          ),4,".","")+

                                                        number_format(($this->SaldoSegV         + $this->SaldoIVASegV           ),4,".","")+
                                                        number_format(($this->SaldoSegD         + $this->SaldoIVASegD           ),4,".","")+
                                                        number_format(($this->SaldoSegB         + $this->SaldoIVASegB           ),4,".","");










        $this->SaldoGeneralVencido              =       $_SaldoGeneralVencido;


/*
        $_SaldoGeneralVigente                   =       number_format($this->SaldoCapitalPorVencer,4,".","")+
                                                        number_format(($this->SaldoInteresPorVencer     *   (1+ $this->iva_pcnt_intereses )),4,".","")+
                                                        number_format(($this->SaldoComisionPorVencer    *   (1+ $this->iva_pcnt_comisiones)),4,".","");

*/


        $_SaldoGeneralVigente       =    number_format(  ($this->SaldoCapitalPorVencer  +
                                                          $this->SaldoInteresPorVencer  + $this->Saldo_IVA_InteresPorVencer  +
                                                          $this->SaldoComisionPorVencer + $this->Saldo_IVA_ComisionPorVencer +

                                                          $this->SaldoSegVPorVencer     + $this->Saldo_IVA_SegVPorVencer     +
                                                          $this->SaldoSegDPorVencer     + $this->Saldo_IVA_SegDPorVencer     +
                                                          $this->SaldoSegBPorVencer     + $this->Saldo_IVA_SegBPorVencer       ),4,".","");





        $this->SaldoGeneralVigente              = $_SaldoGeneralVigente;
        
        
        $this->SaldoGeneralGlobal               = $_SaldoGeneralVigente + $_SaldoGeneralVencido;



        $this->SaldoGeneralVencido              = (abs($this->SaldoGeneralVencido       )<$minimo_depurable )?(0):($this->SaldoGeneralVencido         );                                      
        $this->SaldoGeneralVigente              = (abs($this->SaldoGeneralVigente       )<$minimo_depurable )?(0):($this->SaldoGeneralVigente         );                                              
        $this->SaldoGeneralGlobal               = (abs($this->SaldoGeneralGlobal        )<$minimo_depurable )?(0):($this->SaldoGeneralGlobal          );       







        $_renta = ($this->SaldoCapitalPorVencer >= $minimo_depurable )?($this->proxima_cuota):(0);





     if($this->SaldoGeneralVencido <= $minimo_depurable  ) 
     {

        $this->saldo_vencido = 0.0;
         
     }
     else
     {     

        $this->saldo_vencido = ceil($this->SaldoGeneralVencido);

     }


        $this->SaldoOptimoCobranza = number_format(($this->saldo_vencido + $_renta )    ,2,".","");







}

//-------------------------------------------------------------------------------------------------------------------------------------------------------------


function saldos_parciales_por_cuota($id,$NumCuota)
{

/*
        if($NumCuota == 0 )
           $NumCuota = 1;


        if($NumCuota > $this->plazo)
           $NumCuota = $this->plazo;
*/

        
        if($this->aplicacion[$id]['ID_Concepto'] == -3)
        {
        
                $this->saldos_cuota[$NumCuota]['Fecha']         = $this->aplicacion[$id]['Fecha'];
                $this->saldos_cuota[$NumCuota]['Fecha_Mov']     = $this->aplicacion[$id]['Fecha_Mov'];
        
        }

        
        
        
        $this->saldos_cuota[$NumCuota]['CARGOS']                +=      $this->aplicacion[$id]['IMB'];                                          

        if($this->aplicacion[$id]['Tipo'] == 'Cargo')
        {
                $this->saldos_cuota[$NumCuota]['CARGOS']        +=      $this->aplicacion[$id]['Monto'];
        }
        else
        {
                $this->saldos_cuota[$NumCuota]['PAGOS']         +=      $this->aplicacion[$id]['Abono'] + $this->aplicacion[$id]['SaldoFavorAplicado'];

        }




        if($this->aplicacion[$id]['Tipo'] != 'Saldo')
                $this->saldos_cuota[$NumCuota]['Fecha_Ultimo_Mov']              =       max($this->saldos_cuota[$NumCuota]['Fecha_Ultimo_Mov'], $this->aplicacion[$id]['Fecha']);       
        
        if($this->aplicacion[$id]['Tipo'] == 'Abono')
                $this->saldos_cuota[$NumCuota]['Fecha_Ultimo_Abono']            =       max($this->saldos_cuota[$NumCuota]['Fecha_Ultimo_Abono'], $this->aplicacion[$id]['Fecha']);
        else    
         if($this->aplicacion[$id]['Tipo'] == 'Cargo')
                $this->saldos_cuota[$NumCuota]['Fecha_Ultimo_Cargo']            =       max($this->saldos_cuota[$NumCuota]['Fecha_Ultimo_Cargo'], $this->aplicacion[$id]['Fecha']);     

        //-------------------------------------------------------------------------------------------------------------
        // Acumulado de Cargos
/**/
        $this->saldos_cuota[$NumCuota]['CARGO_Capital']                 +=      $this->aplicacion[$id]['CARGO_Capital']         ;       
        $this->saldos_cuota[$NumCuota]['CARGO_Interes' ]                +=      $this->aplicacion[$id]['CARGO_Interes' ]        ;
        $this->saldos_cuota[$NumCuota]['CARGO_IVA_Interes']             +=      $this->aplicacion[$id]['CARGO_IVA_Interes']     ;       
        $this->saldos_cuota[$NumCuota]['CARGO_Comision']                +=      $this->aplicacion[$id]['CARGO_Comision']        ;
        $this->saldos_cuota[$NumCuota]['CARGO_IVA_Comision']            +=      $this->aplicacion[$id]['CARGO_IVA_Comision']    ;

        $this->saldos_cuota[$NumCuota]['CARGO_Otros']                   +=      $this->aplicacion[$id]['CARGO_Otros']           ;
        $this->saldos_cuota[$NumCuota]['CARGO_IVA_Otros']               +=      $this->aplicacion[$id]['CARGO_IVA_Otros']       ;
        $this->saldos_cuota[$NumCuota]['IM']                            +=      $this->aplicacion[$id]['IM'] +($this->aplicacion[$id]['CARGO_Moratorio']);                      ;
        
        
        $this->saldos_cuota[$NumCuota]['CARGO_IVA_Moratorio']           +=      $this->aplicacion[$id]['CARGO_IVA_Moratorio']   ;
        $this->saldos_cuota[$NumCuota]['CARGO_IVA']                     +=      $this->aplicacion[$id]['CARGO_IVA']             ;       



        $this->saldos_cuota[$NumCuota]['CARGO_SegV' ]                   +=      $this->aplicacion[$id]['CARGO_SegV' ]        ;
        $this->saldos_cuota[$NumCuota]['CARGO_IVA_SegV']                +=      $this->aplicacion[$id]['CARGO_IVA_SegV']     ;       

        $this->saldos_cuota[$NumCuota]['CARGO_SegD' ]                   +=      $this->aplicacion[$id]['CARGO_SegD' ]        ;
        $this->saldos_cuota[$NumCuota]['CARGO_IVA_SegD']                +=      $this->aplicacion[$id]['CARGO_IVA_SegD']     ;       

        $this->saldos_cuota[$NumCuota]['CARGO_SegB' ]                   +=      $this->aplicacion[$id]['CARGO_SegB' ]        ;
        $this->saldos_cuota[$NumCuota]['CARGO_IVA_SegB']                +=      $this->aplicacion[$id]['CARGO_IVA_SegB']     ;       










        $this->saldos_cuota[$NumCuota]['CUOTA_CARGO_Capital']                +=      $this->aplicacion[$id]['CARGO_Capital']         ;       
        $this->saldos_cuota[$NumCuota]['CUOTA_CARGO_Interes' ]               +=      $this->aplicacion[$id]['CARGO_Interes' ]        ;
        $this->saldos_cuota[$NumCuota]['CUOTA_CARGO_IVA_Interes']            +=      $this->aplicacion[$id]['CARGO_IVA_Interes']     ;       
        $this->saldos_cuota[$NumCuota]['CUOTA_CARGO_Comision']               +=      $this->aplicacion[$id]['CARGO_Comision']        ;
        $this->saldos_cuota[$NumCuota]['CUOTA_CARGO_IVA_Comision']           +=      $this->aplicacion[$id]['CARGO_IVA_Comision']    ;
        $this->saldos_cuota[$NumCuota]['CUOTA_CARGO_Otros']                  +=      $this->aplicacion[$id]['CARGO_Otros']           ;
        $this->saldos_cuota[$NumCuota]['CUOTA_CARGO_IVA_Otros']              +=      $this->aplicacion[$id]['CARGO_IVA_Otros']       ;
        $this->saldos_cuota[$NumCuota]['CUOTA_IM']                           +=      $this->aplicacion[$id]['IM'] +($this->aplicacion[$id]['CARGO_Moratorio']);                      ;
        
        
        $this->saldos_cuota[$NumCuota]['CUOTA_CARGO_IVA_Moratorio']          +=      $this->aplicacion[$id]['CARGO_IVA_Moratorio']   ;
        $this->saldos_cuota[$NumCuota]['CUOTA_CARGO_IVA']                    +=      $this->aplicacion[$id]['CARGO_IVA']             ;       


        $this->saldos_cuota[$NumCuota]['CUOTA_CARGO_SegV' ]                  +=      $this->aplicacion[$id]['CARGO_SegV' ]        ;
        $this->saldos_cuota[$NumCuota]['CUOTA_CARGO_IVA_SegV']               +=      $this->aplicacion[$id]['CARGO_IVA_SegV']     ;       

        $this->saldos_cuota[$NumCuota]['CUOTA_CARGO_SegD' ]                  +=      $this->aplicacion[$id]['CARGO_SegD' ]        ;
        $this->saldos_cuota[$NumCuota]['CUOTA_CARGO_IVA_SegD']               +=      $this->aplicacion[$id]['CARGO_IVA_SegD']     ;       

        $this->saldos_cuota[$NumCuota]['CUOTA_CARGO_SegB' ]                  +=      $this->aplicacion[$id]['CARGO_SegB' ]        ;
        $this->saldos_cuota[$NumCuota]['CUOTA_CARGO_IVA_SegB']               +=      $this->aplicacion[$id]['CARGO_IVA_SegB']     ;       





        //-------------------------------------------------------------------------------------------------------------
        // Acumulado de Abonos
        



      if($this->saldos_cuota[$NumCuota]['ABONO_Capital'] == 0)
      {
              $this->saldos_cuota[$NumCuota]['ABONO_Capital'] = $this->abonos_contra_saldos_vigentes_por_couta[$NumCuota]['Capital'];
      }
        
      $this->saldos_cuota[$NumCuota]['ABONO_Capital']         +=      $this->aplicacion[$id]['ABONO_Capital']; 
      



      if($this->saldos_cuota[$NumCuota]['ABONO_Interes'] == 0)
      {
              $this->saldos_cuota[$NumCuota]['ABONO_Interes'] = $this->abonos_contra_saldos_vigentes_por_couta[$NumCuota]['Interes'];
      }

      $this->saldos_cuota[$NumCuota]['ABONO_Interes']         +=      $this->aplicacion[$id]['ABONO_Interes'];
      



      if($this->saldos_cuota[$NumCuota]['ABONO_Interes_IVA'] == 0)
      {
              $this->saldos_cuota[$NumCuota]['ABONO_Interes_IVA'] = $this->abonos_contra_saldos_vigentes_por_couta[$NumCuota]['Interes_IVA'];
      }       

      $this->saldos_cuota[$NumCuota]['ABONO_Interes_IVA']     +=      $this->aplicacion[$id]['ABONO_Interes_IVA']             ;
      




      if($this->saldos_cuota[$NumCuota]['ABONO_Comision'] == 0)
      {
              $this->saldos_cuota[$NumCuota]['ABONO_Comision'] = $this->abonos_contra_saldos_vigentes_por_couta[$NumCuota]['Comision'];
      }               

      $this->saldos_cuota[$NumCuota]['ABONO_Comision']        +=      $this->aplicacion[$id]['ABONO_Comision']                ;
      



      if($this->saldos_cuota[$NumCuota]['ABONO_Comision_IVA'] == 0)
      {
              $this->saldos_cuota[$NumCuota]['ABONO_Comision_IVA'] = $this->abonos_contra_saldos_vigentes_por_couta[$NumCuota]['Comision_IVA'];
      }               

      $this->saldos_cuota[$NumCuota]['ABONO_Comision_IVA']    +=      $this->aplicacion[$id]['ABONO_Comision_IVA']            ;






      if($this->saldos_cuota[$NumCuota]['ABONO_SegV'] == 0)
      {
              $this->saldos_cuota[$NumCuota]['ABONO_SegV'] = $this->abonos_contra_saldos_vigentes_por_couta[$NumCuota]['SegV'];
      }               

      $this->saldos_cuota[$NumCuota]['ABONO_SegV']        +=      $this->aplicacion[$id]['ABONO_SegV']                ;



      if($this->saldos_cuota[$NumCuota]['ABONO_SegV_IVA'] == 0)
      {
              $this->saldos_cuota[$NumCuota]['ABONO_SegV_IVA'] = $this->abonos_contra_saldos_vigentes_por_couta[$NumCuota]['SegV_IVA'];
      }               

      $this->saldos_cuota[$NumCuota]['ABONO_SegV_IVA']    +=      $this->aplicacion[$id]['ABONO_SegV_IVA']            ;





      if($this->saldos_cuota[$NumCuota]['ABONO_SegD'] == 0)
      {
              $this->saldos_cuota[$NumCuota]['ABONO_SegD'] = $this->abonos_contra_saldos_vigentes_por_couta[$NumCuota]['SegD'];
      }               

      $this->saldos_cuota[$NumCuota]['ABONO_SegD']        +=      $this->aplicacion[$id]['ABONO_SegD']                ;



      if($this->saldos_cuota[$NumCuota]['ABONO_SegD_IVA'] == 0)
      {
              $this->saldos_cuota[$NumCuota]['ABONO_SegD_IVA'] = $this->abonos_contra_saldos_vigentes_por_couta[$NumCuota]['SegD_IVA'];
      }               

      $this->saldos_cuota[$NumCuota]['ABONO_SegD_IVA']    +=      $this->aplicacion[$id]['ABONO_SegD_IVA']            ;






      if($this->saldos_cuota[$NumCuota]['ABONO_SegB'] == 0)
      {
              $this->saldos_cuota[$NumCuota]['ABONO_SegB'] = $this->abonos_contra_saldos_vigentes_por_couta[$NumCuota]['SegB'];
      }               

      $this->saldos_cuota[$NumCuota]['ABONO_SegB']        +=      $this->aplicacion[$id]['ABONO_SegB']                ;




      if($this->saldos_cuota[$NumCuota]['ABONO_SegB_IVA'] == 0)
      {
              $this->saldos_cuota[$NumCuota]['ABONO_SegB_IVA'] = $this->abonos_contra_saldos_vigentes_por_couta[$NumCuota]['SegB_IVA'];
      }               

        $this->saldos_cuota[$NumCuota]['ABONO_SegB_IVA']    +=      $this->aplicacion[$id]['ABONO_SegB_IVA']            ;









        
        
        $this->saldos_cuota[$NumCuota]['ABONO_Otros']           +=      $this->aplicacion[$id]['ABONO_Otros']                 ;
                
        $this->saldos_cuota[$NumCuota]['ABONO_Otros_IVA']       +=      $this->aplicacion[$id]['ABONO_Otros_IVA']             ;
        

        
        $this->saldos_cuota[$NumCuota]['ABONO_Moratorio']       +=      $this->aplicacion[$id]['ABONO_Moratorio']             ;
                
        $this->saldos_cuota[$NumCuota]['ABONO_Moratorio_IVA']   +=      $this->aplicacion[$id]['ABONO_Moratorio_IVA']         ;
        
        
        
        $this->saldos_cuota[$NumCuota]['ABONO_IVA']             +=      $this->aplicacion[$id]['ABONO_IVA']                   ;

/*        
Corrección : EGC 2012-02-15
Esto duplicaba el abono por cuota de los seguros y por eso se removió.


        $this->saldos_cuota[$NumCuota]['ABONO_SegV']           +=      $this->aplicacion[$id]['ABONO_SegV']                   ;
            
        $this->saldos_cuota[$NumCuota]['ABONO_SegV_IVA']       +=      $this->aplicacion[$id]['ABONO_SegV_IVA']               ;



        $this->saldos_cuota[$NumCuota]['ABONO_SegD']           +=      $this->aplicacion[$id]['ABONO_SegD']                   ;
                
        $this->saldos_cuota[$NumCuota]['ABONO_SegD_IVA']       +=      $this->aplicacion[$id]['ABONO_SegD_IVA']               ;



        $this->saldos_cuota[$NumCuota]['ABONO_SegB']           +=      $this->aplicacion[$id]['ABONO_SegB']                   ;
        
        $this->saldos_cuota[$NumCuota]['ABONO_SegB_IVA']       +=      $this->aplicacion[$id]['ABONO_SegB_IVA']               ;
*/












        //-------------------------------------------------------------------------------------------------------------
        // Saldos a Favor 

        $this->saldos_cuota[$NumCuota]['DiasAtrasoAcum']        =       max($this->saldos_cuota[$NumCuota]['DiasAtrasoAcum'], $this->aplicacion[$id]['DiasAtrasoAcum']);



//===================================================================================================================================================================
// INICIO SALDOS POR CUOTA
//===================================================================================================================================================================

        //-------------------------------------------------------------------------------------------------------------
        // Saldos


        
        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_Capital']               =       $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_A_FAVOR_Capital']           +       $this->saldos_cuota[$NumCuota]['CUOTA_CARGO_Capital']           + $this->saldos_cuota[$NumCuota]['ABONO_Capital'];



        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_Interes']               =       $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_A_FAVOR_Interes']           +       $this->saldos_cuota[$NumCuota]['CUOTA_CARGO_Interes']           + $this->saldos_cuota[$NumCuota]['ABONO_Interes'];

        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_Moratorio']             =       $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_A_FAVOR_Moratorio']         +       $this->saldos_cuota[$NumCuota]['CUOTA_IM']                      + $this->saldos_cuota[$NumCuota]['ABONO_Moratorio'];



        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_Comision']              =       $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_A_FAVOR_Comision']          +       $this->saldos_cuota[$NumCuota]['CUOTA_CARGO_Comision']          + $this->saldos_cuota[$NumCuota]['ABONO_Comision'];


        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_Otros']                 =       $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_A_FAVOR_Otros']             +       $this->saldos_cuota[$NumCuota]['CUOTA_CARGO_Otros']             + $this->saldos_cuota[$NumCuota]['ABONO_Otros'];


        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_IVA_Interes']           =       $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_A_FAVOR_IVA_Interes']       +       $this->saldos_cuota[$NumCuota]['CUOTA_CARGO_IVA_Interes']       + $this->saldos_cuota[$NumCuota]['ABONO_Interes_IVA'];  



        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_IVA_Moratorio']         =       $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_A_FAVOR_IVA_Moratorio']     +       $this->saldos_cuota[$NumCuota]['CUOTA_CARGO_IVA_Moratorio']     + $this->saldos_cuota[$NumCuota]['ABONO_Moratorio_IVA'];                


        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_IVA_Comision']            =     $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_A_FAVOR_IVA_Comision']      +       $this->saldos_cuota[$NumCuota]['CUOTA_CARGO_IVA_Comision']      + $this->saldos_cuota[$NumCuota]['ABONO_Comision_IVA'];         

        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_IVA_Otros']             =       $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_A_FAVOR_IVA_Otros']         +       $this->saldos_cuota[$NumCuota]['CUOTA_CARGO_IVA_Otros']         + $this->saldos_cuota[$NumCuota]['ABONO_Otros_IVA'];    





        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_SegV']                 =       $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_A_FAVOR_SegV']             +       $this->saldos_cuota[$NumCuota]['CUOTA_CARGO_SegV']             + $this->saldos_cuota[$NumCuota]['ABONO_SegV'];

        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_SegB']                 =       $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_A_FAVOR_SegB']             +       $this->saldos_cuota[$NumCuota]['CUOTA_CARGO_SegB']             + $this->saldos_cuota[$NumCuota]['ABONO_SegB'];

        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_SegD']                 =       $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_A_FAVOR_SegD']             +       $this->saldos_cuota[$NumCuota]['CUOTA_CARGO_SegD']             + $this->saldos_cuota[$NumCuota]['ABONO_SegD'];





        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_IVA_SegV']             =       $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_A_FAVOR_IVA_SegV']         +       $this->saldos_cuota[$NumCuota]['CUOTA_CARGO_IVA_SegV']         + $this->saldos_cuota[$NumCuota]['ABONO_SegV_IVA'];    

        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_IVA_SegD']             =       $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_A_FAVOR_IVA_SegD']         +       $this->saldos_cuota[$NumCuota]['CUOTA_CARGO_IVA_SegD']         + $this->saldos_cuota[$NumCuota]['ABONO_SegD_IVA'];    

        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_IVA_SegB']             =       $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_A_FAVOR_IVA_SegB']         +       $this->saldos_cuota[$NumCuota]['CUOTA_CARGO_IVA_SegB']         + $this->saldos_cuota[$NumCuota]['ABONO_SegB_IVA'];    





        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_IVA']                   =       $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_IVA_Interes']           +
                                                                                        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_IVA_Moratorio']         +
                                                                                        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_IVA_Comision']          +
                                                                                        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_IVA_Otros']             +

                                                                                        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_IVA_SegV']              +  
                                                                                        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_IVA_SegD']              +  
                                                                                        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_IVA_SegB']              ;  










        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_General']               =       $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_Capital']               +                               
                                                                                        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_Interes']               +                               
                                                                                        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_Moratorio']             +                               
                                                                                        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_Comision']              +                               
                                                                                        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_Otros']                 +                               

                                                                                        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_SegV']                  +  
                                                                                        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_SegD']                  +  
                                                                                        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_SegB']                  + 



                                                                                        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_IVA_Interes']           +
                                                                                        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_IVA_Moratorio']         +
                                                                                        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_IVA_Comision']          +
                                                                                        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_IVA_Otros']             +

                                                                                        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_IVA_SegV']              +  
                                                                                        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_IVA_SegD']              +  
                                                                                        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_IVA_SegB']              ; 













        $this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_Pendiente_Aplicar'] = ($this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_General']<=0)?($this->saldos_cuota[$NumCuota]['CUOTA_SALDO_MOV_General']):(0.0);


//===================================================================================================================================================================
// FIN SALDOS POR CUOTA
//===================================================================================================================================================================










        $this->saldos_cuota[$NumCuota]['SALDO_MOV_Capital']             =       $this->aplicacion[$id]['SALDO_MOV_Capital'];
                                                                                                                                
        $this->saldos_cuota[$NumCuota]['SALDO_MOV_Interes']             =       $this->aplicacion[$id]['SALDO_MOV_Interes'];            

        $this->saldos_cuota[$NumCuota]['SALDO_MOV_Moratorio']           =       $this->aplicacion[$id]['SALDO_MOV_Moratorio'];          
                                                                                                                               
        $this->saldos_cuota[$NumCuota]['SALDO_MOV_Comision']            =       $this->aplicacion[$id]['SALDO_MOV_Comision'];           
                                                                                                                                
        $this->saldos_cuota[$NumCuota]['SALDO_MOV_Otros']               =       $this->aplicacion[$id]['SALDO_MOV_Otros'];              

                                                                                                                                
        $this->saldos_cuota[$NumCuota]['SALDO_MOV_SegV']                =       $this->aplicacion[$id]['SALDO_MOV_SegV'];

        $this->saldos_cuota[$NumCuota]['SALDO_MOV_SegD']                =       $this->aplicacion[$id]['SALDO_MOV_SegD'];

        $this->saldos_cuota[$NumCuota]['SALDO_MOV_SegB']                =       $this->aplicacion[$id]['SALDO_MOV_SegB'];






        $this->saldos_cuota[$NumCuota]['SALDO_MOV_IVA_Interes']         =       $this->aplicacion[$id]['SALDO_MOV_IVA_Interes'];                
                                                                                                                               
        $this->saldos_cuota[$NumCuota]['SALDO_MOV_IVA_Moratorio']       =       $this->aplicacion[$id]['SALDO_MOV_IVA_Moratorio'];      
                                                                                                                                
        $this->saldos_cuota[$NumCuota]['SALDO_MOV_IVA_Comision']        =       $this->aplicacion[$id]['SALDO_MOV_IVA_Comision'];       
                                                                                                                                
        $this->saldos_cuota[$NumCuota]['SALDO_MOV_IVA_Otros']           =       $this->aplicacion[$id]['SALDO_MOV_IVA_Otros'];                  


        $this->saldos_cuota[$NumCuota]['SALDO_MOV_IVA_SegV']           =       $this->aplicacion[$id]['SALDO_MOV_IVA_SegV'];                  

        $this->saldos_cuota[$NumCuota]['SALDO_MOV_IVA_SegD']           =       $this->aplicacion[$id]['SALDO_MOV_IVA_SegD'];                  

        $this->saldos_cuota[$NumCuota]['SALDO_MOV_IVA_SegB']           =       $this->aplicacion[$id]['SALDO_MOV_IVA_SegB'];                  


        $this->saldos_cuota[$NumCuota]['SALDO_MOV_IVA']                 =       $this->aplicacion[$id]['SALDO_MOV_IVA'];                

                                                                                
                                                                                
                                                                                
                                                                                

        $this->saldos_cuota[$NumCuota]['SALDO_MOV_General']             =       $this->aplicacion[$id]['SALDO_MOV_General'];                            

        
        $this->saldos_cuota[$NumCuota]['SaldoGlobalCapital']            =       $this->aplicacion[$id]['SaldoGlobalCapital']    ;       
        $this->saldos_cuota[$NumCuota]['SaldoGlobalInteres']            =       $this->aplicacion[$id]['SaldoGlobalInteres']    ;       
        $this->saldos_cuota[$NumCuota]['SaldoGlobalComision']           =       $this->aplicacion[$id]['SaldoGlobalComision']   ;
        $this->saldos_cuota[$NumCuota]['SaldoGlobalOtros']              =       $this->aplicacion[$id]['SaldoGlobalOtros']      ;       

        $this->saldos_cuota[$NumCuota]['SaldoGlobalMoratorio']          =       $this->aplicacion[$id]['SaldoGlobalMoratorio']  ;



        $this->saldos_cuota[$NumCuota]['SaldoGlobalSegV']              =       $this->aplicacion[$id]['SaldoGlobalSegV']      ;       
        $this->saldos_cuota[$NumCuota]['SaldoGlobalSegD']              =       $this->aplicacion[$id]['SaldoGlobalSegD']      ;       
        $this->saldos_cuota[$NumCuota]['SaldoGlobalSegB']              =       $this->aplicacion[$id]['SaldoGlobalSegB']      ;       






        $this->saldos_cuota[$NumCuota]['SaldoGlobalIVA']                =       $this->aplicacion[$id]['SaldoGlobalIVA']        ;
        
        
        
        
        $this->saldos_cuota[$NumCuota]['SaldoCapitalPorVencer']         =       $this->aplicacion[$id]['SaldoCapitalPorVencer'] ;

        $this->saldos_cuota[$NumCuota]['SaldoInteresPorVencer']         =       $this->aplicacion[$id]['SaldoInteresPorVencer'] ;
        $this->saldos_cuota[$NumCuota]['SaldoInteres_IVA_PorVencer' ]   =       $this->aplicacion[$id]['SaldoInteres_IVA_PorVencer' ];



        
        if($this->saldos_cuota[$NumCuota]['SaldoCapitalPorVencer'] <= 0)
        {
           $this->saldos_cuota[$NumCuota]['SaldoInteresPorVencer']         = 0;
           $this->saldos_cuota[$NumCuota]['SaldoInteres_IVA_PorVencer' ]   = 0;        
        }
        
        $this->saldos_cuota[$NumCuota]['SaldoComisionPorVencer']        =       $this->aplicacion[$id]['SaldoComisionPorVencer'];
        $this->saldos_cuota[$NumCuota]['SaldoComision_IVA_PorVencer']   =       $this->aplicacion[$id]['SaldoComision_IVA_PorVencer'];
        


        
        $this->saldos_cuota[$NumCuota]['SaldoSegVPorVencer']            =       $this->aplicacion[$id]['SaldoSegVPorVencer'] ;
        $this->saldos_cuota[$NumCuota]['SaldoSegV_IVA_PorVencer']       =       $this->aplicacion[$id]['SaldoSegV_IVA_PorVencer'];


        $this->saldos_cuota[$NumCuota]['SaldoSegDPorVencer']            =       $this->aplicacion[$id]['SaldoSegDPorVencer'] ;
        $this->saldos_cuota[$NumCuota]['SaldoSegD_IVA_PorVencer']       =       $this->aplicacion[$id]['SaldoSegD_IVA_PorVencer'];


        $this->saldos_cuota[$NumCuota]['SaldoSegBPorVencer']            =       $this->aplicacion[$id]['SaldoSegBPorVencer'] ;
        $this->saldos_cuota[$NumCuota]['SaldoSegB_IVA_PorVencer']       =       $this->aplicacion[$id]['SaldoSegB_IVA_PorVencer'];


        
        
        
        
        
        
        $this->saldos_cuota[$NumCuota]['SALDO_General']                 =       $this->aplicacion[$id]['SALDO_General'];
        
        $this->saldos_cuota[$NumCuota]['SaldoParcial']                  =       $this->aplicacion[$id]['SaldoParcial'];



        $this->saldos_cuota[$NumCuota]['SALDO_MOV_Capital']             =(abs($this->saldos_cuota[$NumCuota]['SALDO_MOV_Capital']               )< 0.009)?(0):($this->saldos_cuota[$NumCuota]['SALDO_MOV_Capital']      );
        $this->saldos_cuota[$NumCuota]['SALDO_MOV_Interes']             =(abs($this->saldos_cuota[$NumCuota]['SALDO_MOV_Interes']               )< 0.009)?(0):($this->saldos_cuota[$NumCuota]['SALDO_MOV_Interes']      );
        $this->saldos_cuota[$NumCuota]['SALDO_MOV_Moratorio']           =(abs($this->saldos_cuota[$NumCuota]['SALDO_MOV_Moratorio']             )< 0.009)?(0):($this->saldos_cuota[$NumCuota]['SALDO_MOV_Moratorio']    );
        $this->saldos_cuota[$NumCuota]['SALDO_MOV_Comision']            =(abs($this->saldos_cuota[$NumCuota]['SALDO_MOV_Comision']              )< 0.009)?(0):($this->saldos_cuota[$NumCuota]['SALDO_MOV_Comision']     );
        $this->saldos_cuota[$NumCuota]['SALDO_MOV_Otros']               =(abs($this->saldos_cuota[$NumCuota]['SALDO_MOV_Otros']                 )< 0.009)?(0):($this->saldos_cuota[$NumCuota]['SALDO_MOV_Otros']        );
        $this->saldos_cuota[$NumCuota]['SALDO_MOV_General']             =(abs($this->saldos_cuota[$NumCuota]['SALDO_MOV_General']               )< 0.009)?(0):($this->saldos_cuota[$NumCuota]['SALDO_MOV_General']      );


        $this->saldos_cuota[$NumCuota]['SALDO_MOV_SegV']               =(abs($this->saldos_cuota[$NumCuota]['SALDO_MOV_SegV']                 )< 0.009)?(0):($this->saldos_cuota[$NumCuota]['SALDO_MOV_SegV']                );
        $this->saldos_cuota[$NumCuota]['SALDO_MOV_SegD']               =(abs($this->saldos_cuota[$NumCuota]['SALDO_MOV_SegD']                 )< 0.009)?(0):($this->saldos_cuota[$NumCuota]['SALDO_MOV_SegD']                );
        $this->saldos_cuota[$NumCuota]['SALDO_MOV_SegB']               =(abs($this->saldos_cuota[$NumCuota]['SALDO_MOV_SegB']                 )< 0.009)?(0):($this->saldos_cuota[$NumCuota]['SALDO_MOV_SegB']                );






        $this->saldos_cuota[$NumCuota]['SALDO_MOV_IVA_Interes']         =(abs($this->saldos_cuota[$NumCuota]['SALDO_MOV_IVA_Interes']           )< 0.009)?(0):($this->saldos_cuota[$NumCuota]['SALDO_MOV_IVA_Interes']          );
        $this->saldos_cuota[$NumCuota]['SALDO_MOV_IVA_Moratorio']       =(abs($this->saldos_cuota[$NumCuota]['SALDO_MOV_IVA_Moratorio']         )< 0.009)?(0):($this->saldos_cuota[$NumCuota]['SALDO_MOV_IVA_Moratorio']        );
        $this->saldos_cuota[$NumCuota]['SALDO_MOV_IVA_Comision']        =(abs($this->saldos_cuota[$NumCuota]['SALDO_MOV_IVA_Comision']          )< 0.009)?(0):($this->saldos_cuota[$NumCuota]['SALDO_MOV_IVA_Comision']         );
        $this->saldos_cuota[$NumCuota]['SALDO_MOV_IVA_Otros']           =(abs($this->saldos_cuota[$NumCuota]['SALDO_MOV_IVA_Otros']             )< 0.009)?(0):($this->saldos_cuota[$NumCuota]['SALDO_MOV_IVA_Otros']            );
        $this->saldos_cuota[$NumCuota]['SALDO_MOV_IVA']                 =(abs($this->saldos_cuota[$NumCuota]['SALDO_MOV_IVA']                   )< 0.009)?(0):($this->saldos_cuota[$NumCuota]['SALDO_MOV_IVA']                  );
                                                                        

        $this->saldos_cuota[$NumCuota]['SALDO_MOV_IVA_SegV']           =(abs($this->saldos_cuota[$NumCuota]['SALDO_MOV_IVA_SegV']             )< 0.009)?(0):($this->saldos_cuota[$NumCuota]['SALDO_MOV_IVA_SegV']    );
        $this->saldos_cuota[$NumCuota]['SALDO_MOV_IVA_SegD']           =(abs($this->saldos_cuota[$NumCuota]['SALDO_MOV_IVA_SegD']             )< 0.009)?(0):($this->saldos_cuota[$NumCuota]['SALDO_MOV_IVA_SegD']    );
        $this->saldos_cuota[$NumCuota]['SALDO_MOV_IVA_SegB']           =(abs($this->saldos_cuota[$NumCuota]['SALDO_MOV_IVA_SegB']             )< 0.009)?(0):($this->saldos_cuota[$NumCuota]['SALDO_MOV_IVA_SegB']    );
                                                                                
                                                                                










        return; 
                                                                                
                                                                        
}
//-------------------------------------------------------------------------------------------------------------------------------------------------------------
function get_cuotas_pagas_v_vencidas()
{
        $i=0;
        foreach($this->saldos_cuota AS $num=>$cuota)
        {
        
                        
                        if($num)
                        {
                                
                                
                                
                                if($this->saldos_cuota[$num]['Fecha'] == $this->fecha_corte)
                                {
                                        
                                        ++$this->numcargosvencidos;
                                        return;                 
                                }
                                
                                // Intradía, la cuota no se vence sino hasta después de su fecha de vencimiento
                                
                                if($this->saldos_cuota[$num]['Fecha'] >= $this->fecha_corte) return;
                                
                                if($cuota['SALDO_General']<0.01)
                                {
                                        ++$this->numcargosvencidos_pagados ;
                                }
                                else
                                {       
                                        ++$this->numcargosvencidos_no_pagados ;

                                }
                                
                                
                                //debug($num.")  SALDO_General :  ".$cuota['SALDO_General']."   [".$this->numcargosvencidos_pagados."] {".$this->numcargosvencidos_no_pagados."}");
                                
                                ++$this->numcargosvencidos;
                                ++$i;
                                
                                
                                
                                
                                
                        }
        }
}
//-------------------------------------------------------------------------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------------------------------------------------------------------------

function calculo_interes_moratorio_variable($id, $ID_Cuota, $dbg)
{
   //

   $debug = "";
   $monto_base=0;


    //Si hay vencimiento anticipado, después de la fecha del vencimiento anticipado ya no se cobran moratorios
/**/
     if($this->is_vencimiento_anticipado) 
      if($this->aplicacion[($id-1)]['Fecha']>= $this->fecha_vencimiento_anticipado)
           {
                $this->dias_mora = 0;
                
                return(0);

           }
           
   
   // ------------------------------------------------------------------------------------------------------------------------   
   // El monto base de cálculo para interés moratorio, si el registro actual es una CUOTA o bien una NOTA DE CRÉDITO 
   // y su movimiento anterior fue una CUOTA
   // ------------------------------------------------------------------------------------------------------------------------   
/**/
   if(($this->aplicacion[$id]['Concepto']== 'Documento') and ($this->aplicacion[$id]['Tipo']== 'Abono') and ($this->aplicacion[$id]['SubTipo'] != 'General') and ($this->aplicacion[($id-1)]['ID_Concepto']==-3))   
    {
        
                        if($this->moratorio_base_capital        ) $monto_base += $this->saldos_cuota[$ID_Cuota]['CUOTA_SALDO_MOV_Capital'] ;                                  
                        if($this->moratorio_base_interes        ) $monto_base += $this->saldos_cuota[$ID_Cuota]['CUOTA_SALDO_MOV_Interes']  +$this->saldos_cuota[$ID_Cuota]['CUOTA_SALDO_MOV_IVA_Interes'];  
                        if($this->moratorio_base_comision       ) $monto_base += $this->saldos_cuota[$ID_Cuota]['CUOTA_SALDO_MOV_Comision'] +$this->saldos_cuota[$ID_Cuota]['CUOTA_SALDO_MOV_IVA_Comision']; 
                        if($this->moratorio_base_otros          ) $monto_base += $this->saldos_cuota[$ID_Cuota]['CUOTA_SALDO_MOV_Otros']    +$this->saldos_cuota[$ID_Cuota]['CUOTA_SALDO_MOV_IVA_Otros']; 





    }
    else
    {

     if($this->aplicacion[($id)]['ID_Concepto']==-3)
        {
                        if($this->moratorio_base_capital        ) $monto_base += $this->aplicacion[$id]['CARGO_Capital'] ;                                                 
                        if($this->moratorio_base_interes        ) $monto_base += $this->aplicacion[$id]['CARGO_Interes']  +$this->saldos_cuota[$ID_Cuota]['CARGO_IVA_Interes'];             
                        if($this->moratorio_base_comision       ) $monto_base += $this->aplicacion[$id]['CARGO_Comision'] +$this->saldos_cuota[$ID_Cuota]['CARGO_IVA_Comision'];            
                        if($this->moratorio_base_otros          ) $monto_base += $this->aplicacion[$id]['CARGO_Otros']    +$this->saldos_cuota[$ID_Cuota]['CARGO_IVA_Otros'];              


                
        }
        else
        
        {

                        if($this->moratorio_base_capital        ) $monto_base += $this->saldos_cuota[$ID_Cuota]['CUOTA_SALDO_MOV_Capital'] ;                                  
                        if($this->moratorio_base_interes        ) $monto_base += $this->saldos_cuota[$ID_Cuota]['CUOTA_SALDO_MOV_Interes']   +$this->saldos_cuota[$ID_Cuota]['CUOTA_SALDO_MOV_IVA_Interes'];  
                        if($this->moratorio_base_comision       ) $monto_base += $this->saldos_cuota[$ID_Cuota]['CUOTA_SALDO_MOV_Comision']  +$this->saldos_cuota[$ID_Cuota]['CUOTA_SALDO_MOV_IVA_Comision'];        
                        if($this->moratorio_base_otros          ) $monto_base += $this->saldos_cuota[$ID_Cuota]['CUOTA_SALDO_MOV_Otros']     +$this->saldos_cuota[$ID_Cuota]['CUOTA_SALDO_MOV_IVA_Otros']; 
                        
        }
    }


 $acumdias    = $this->aplicacion[($id-1)]['DiasAtrasoAcum'];

 $fecha_desde = $this->aplicacion[($id-1)]['Fecha'];
 $fecha_hasta = $this->aplicacion[$id]['Fecha'];


 if($monto_base<0) $monto_base=0;

 $dias = $this->aplicacion[$id]['DiasAtraso'];
  
   if(($dias<=0) or ($monto_base <0.01))
   {
           
           if(($this->aplicacion[($id-1)]['SALDO_General']) <0.01)
           {
                $this->dias_mora = 0;
                
                return(0);

           }
           
           
   }
   
   
   
   
  
    
  
//---------------------------------------------------------------
// Desde el día siguiente al moviniento anterior hasta éste día  
//---------------------------------------------------------------
  


 $SumaIMB = 0;

 
 list($_Y, $_M, $_D) = explode("-", $fecha_hasta);





 $fecha_hasta= date("Y-m-d",mktime(0,0,0,$_M, $_D, $_Y));  


 $idx = count($this->detalle_moratorios);


                if(($this->saldos_cuota[($ID_Cuota-1)]['CUOTA_SALDO_General']<0.01) and ($this->detalle_moratorios[($idx-1)]['ID_Cargo'] !=  $ID_Cuota) )
                {
                        $this->dias_mora = 0;
                        
                        unset($this->historico_morosidad);
                        $this->historico_morosidad =  array();
                                                
                }       
                





        list($_y, $_m, $_d) = explode("-",$fecha_desde);

        $fecha_dia = date("Y-m-d",mktime(0,0,0,$_m, ($_d+1), $_y));
        list($_y, $_m, $_d) = explode("-",$fecha_dia);


        if(($this->detalle_moratorios[($idx-1)]['Fecha'] ==  $fecha_dia)  and ($this->detalle_moratorios[($idx-1)]['ID_Cargo'] ==  $ID_Cuota))
        {

                        ++$_d;
                        $fecha_dia = date("Y-m-d",mktime(0,0,0,$_m, $_d, $_y)); 
                        ++$acumdias;


        }



         $dias_gracia_moratorios = $this->busca_dias_gracia($fecha_dia);

         if($dias_gracia_moratorios < 0)
         {
                $dias_gracia_moratorios = $this->dias_gracia_moratorios;
         }
         
         
         
         $saldo_moratorio_couta  = $this->saldos_cuota[($ID_Cuota)]['CUOTA_SALDO_MOV_Moratorio'] + $this->saldos_cuota[$ID_Cuota]['CUOTA_SALDO_MOV_IVA_Moratorio'];

         $saldo_moratorio_global = $this->aplicacion[($id-1)]['CUOTA_SALDO_MOV_Moratorio'];

         $j=0;





$tasa_diaria = ($this->tasa_eq_ssi/30.01);

$Sum_pIVA=0;
$num_pIVA=0;

 while ($fecha_dia <= $fecha_hasta )
 {
	$pIVA = 0;
	if($this->iva_pcnt_intereses > 0)
	{
        	$pIVA =   getIVA($this->zona_iva, $fecha_dia, $this->db);     
    	}
    	
    //  $_valor_moratorio_calculado = number_format((($this->tasa_moratorio)  * ($tasa_diaria) * (1+$this->iva_pcnt_moratorios) * $monto_base ),2,".","");

   //    $_valor_moratorio_calculado = number_format((($this->tasa_moratorio)  * ($tasa_diaria) * (1+$pIVA) * $monto_base ),2,".","");

       $factor_moratorio = $this->busca_tasa_moratorios($fecha_dia);
       if($factor_moratorio < 0)
       {
            $factor_moratorio = $this->tasa_moratorio;
       }

        $_valor_moratorio_calculado = number_format(($factor_moratorio  * ($tasa_diaria) * (1+$pIVA) * $monto_base ),2,".","");
/*
	if($fecha_dia == '2011-12-04')
	{
		debug(   "     $_valor_moratorio_calculado = number_format(($factor_moratorio  * ($tasa_diaria) * (1+$pIVA) * $monto_base ),2,'.','')");

	}
*/        
        $compensado = false; 

        $this->detalle_moratorios[$idx ]['Factor']      =  $factor_moratorio;


        $this->detalle_moratorios[$idx ]['pIVA']        =  $pIVA;

        $this->detalle_moratorios[$idx ]['Fecha']       =  $fecha_dia;
        
        $this->detalle_moratorios[$idx ]['ID_Cargo']    =  $ID_Cuota;   
        
        $this->detalle_moratorios[$idx ]['ID_Mov']      =  $id;
        
        $this->detalle_moratorios[$idx ]['MontoBase']   =  $monto_base;

        $this->detalle_moratorios[$idx ]['Dias']        =  1;
        
        $this->detalle_moratorios[$idx ]['AcumDias']    =  ++$acumdias;

        $saldo_moratorio_couta  += $IMB;
        $saldo_moratorio_global += $IMB;
        
        $this->detalle_moratorios[$idx ]['Saldo_Moratorio']     = $saldo_moratorio_couta;

        $this->detalle_moratorios[$idx ]['Saldo_Mov_Moratorio'] = $saldo_moratorio_global;

        if($this->detalle_moratorios[($idx-1) ]['ID_Cargo'] != $this->detalle_moratorios[$idx ]['ID_Cargo']) 
           $this->SumaMoratoriosCalculadosPorCuota = 0;


        

        if( (array_key_exists($fecha_dia,  $this->historico_morosidad))  )
        {
        
                
                $this->detalle_moratorios[$idx ]['dias_mora']   = $this->historico_morosidad[$fecha_dia];
                
        }
        else
        {
                ++$this->dias_mora;
                
                $this->historico_morosidad[$fecha_dia]          = $this->dias_mora;
        
                $this->detalle_moratorios[$idx ]['dias_mora']   = $this->dias_mora;
        
        }
        
        $this->dias_mora_max = $this->dias_mora;
        
        if(array_key_exists($fecha_dia,  $this->historico_morosidad_global))
        {
                $this->detalle_moratorios[$idx ]['dias_mora_maximos'] = $this->historico_morosidad_global[$fecha_dia];
        
        }
        else
        {
                $this->historico_morosidad_global[$fecha_dia]         = $this->dias_mora_max;
                $this->detalle_moratorios[$idx ]['dias_mora_maximos'] = $this->historico_morosidad_global[$fecha_dia];
                
                
                $fecha_ayer = date("Y-m-d",mktime(0,0,0,$_m, ($_d-1), $_y));


                if($this->dias_mora_max < $this->historico_morosidad_global[$fecha_ayer])
                        $this->historico_morosidad_diferencial[$fecha_dia] = 1;
                else
                        $this->historico_morosidad_diferencial[$fecha_dia] = ++$this->historico_morosidad_diferencial[$fecha_ayer];


        }
        



        $this->detalle_moratorios[$idx ]['Tipo'] =  $this->aplicacion[$id]['Tipo']; //." $fecha_dia : (".$this->historico_morosidad_global[$fecha_dia].")";

        //--------------------------------------------------------------------------------------------------------------------------------------------------
        // No procesamos mas calculos si los días pasan de $this->max_dias_calculo
        //--------------------------------------------------------------------------------------------------------------------------------------------------


        if(($this->detalle_moratorios[$idx ]['dias_mora_maximos'] >$this->max_dias_calculo) or ($acumdias > $this->max_dias_calculo))
        {
               $IMB = 0;
                
                $this->detalle_moratorios[($idx-1)]['MoratorioCalculado'] = 0;
                $this->detalle_moratorios[($idx-1)]['Acumulado_Cuota'] = 0;
                $this->detalle_moratorios[($idx-1)]['SumaMoratoriosCalculadosPorCuota'] =0;
                
                
                ++$_d;
                ++$idx;
                ++$j;






                $fecha_dia = date("Y-m-d",mktime(0,0,0,$_m, $_d, $_y));

                if($fecha_dia > $fecha_hasta)
                {
                        break;
                }

               continue;

                /*
                    $this->aplicacion[$id]['MoraMAX'] = $this->detalle_moratorios[($idx-1) ]['dias_mora_maximos'];


                    $this->SumaIM       += round(($SumaIMB/(1+$this->iva_pcnt_moratorios)),2) + $this->aplicacion[$id]['CARGO_Moratorio'];
                    $this->SaldoIM      += round(($SumaIMB/(1+$this->iva_pcnt_moratorios)),2) + $this->aplicacion[$id]['CARGO_Moratorio'];


                    $IM = $SumaIMB/(1+$this->iva_pcnt_moratorios);



                    $this->SumaMoratorioBruto   += $SumaIMB + $this->aplicacion[$id]['CARGO_Moratorio'] + $this->aplicacion[$id]['CARGO_IVA_Moratorio'];
                    $this->SumaIMB               = $this->SumaMoratorioBruto;
                    $this->SumaMoratorio        += $this->SumaIM + $this->aplicacion[$id]['CARGO_Moratorio'];

                    $this->SumaIVAMoratorio     += ($SumaIMB - $IM) + $this->aplicacion[$id]['CARGO_IVA_Moratorio'];


                  return($SumaIMB);
                 */

        }
        
        //--------------------------------------------------------------------------------------------------------------------------------------------------

        $IMB = 0;


        unset($_fecha_ultima_gracia);




        if($acumdias > $dias_gracia_moratorios  )
        {
                
                $IMB = $_valor_moratorio_calculado;
                // debug("($acumdias > $dias_gracia_moratorios  )".$fecha_dia."  IMB : ".$IMB);

        }
        else
        {
        
              $IMB = 0;
              if($dias_gracia_moratorios == $acumdias)                
                       $fecha_ultima_gracia = $fecha_dia;
        }
        





        //-------------------------------------------------------------------------
        // Si aun no rebasa su saldo, el número monto oratorio mínimo le perdonamos diferentes cosas dependiendo del día
        //-------------------------------------------------------------------------

       $fecha_antier  = date("Y-m-d",mktime(0,0,0,$_m, ($_d-2), $_y));
       $fecha_ayer    = date("Y-m-d",mktime(0,0,0,$_m, ($_d-1), $_y));
       $fecha_maniana = date("Y-m-d",mktime(0,0,0,$_m, ($_d+1), $_y));


                  //-------------------------------------------------------------------------
                  // Si hoy es lunes y ayer domingo fue mi día de gracia, no hay moratorio hoy.
                  //-------------------------------------------------------------------------
                  if( (date("w",mktime(0,0,0,$_m, $_d, $_y)) == 1) and ( $fecha_ayer == $fecha_ultima_gracia) or ( $fecha_ayer  == $this->fecha_ultima_gracia_efectiva))
                  {             
                        $IMB = 0;       
                        //$this->fecha_ultima_gracia_efectiva= date("Y-m-d",mktime(0,0,0,$_m, $_d, $_y));
                        
                  }
                  //-------------------------------------------------------------------------



                  //-------------------------------------------------------------------------
                  // Si ayer fue día de gracia, y hoy es inhábil, Hoy no se cobra
                  //-------------------------------------------------------------------------
                  if((!empty($this->fechas_inhabiles[$fecha_dia])  ) and (($fecha_ayer == $fecha_ultima_gracia) or ( $fecha_ayer  == $this->fecha_ultima_gracia_efectiva)))
                  {             
                        $IMB = 0;       

                        //$this->fecha_ultima_gracia_efectiva= date("Y-m-d",mktime(0,0,0,$_m, $_d, $_y));

                  }


                  //-------------------------------------------------------------------------
                  // Si ayer fue inhabil y me tocaba día de gracia, mi día de gracia será hoy.
                  //-------------------------------------------------------------------------
                  if((!empty($this->fechas_inhabiles[$fecha_ayer])  ) and (($fecha_ayer == $fecha_ultima_gracia) or ( $fecha_ayer  == $this->fecha_ultima_gracia_efectiva)))
                  {             
                        $IMB = 0;       

                        //$this->fecha_ultima_gracia_efectiva= date("Y-m-d",mktime(0,0,0,$_m, $_d, $_y));

                  }
                  //-------------------------------------------------------------------------



                  //-------------------------------------------------------------------------
                  // Si hoy es lunes y día inhabil, obvio ayer fue domingo y antier fue mi día de gracia, HOY no hay moratorio hoy.
                  //-------------------------------------------------------------------------
                  if( (date("w",mktime(0,0,0,$_m, $_d, $_y)) == 1)  and 
                      (!empty($this->fechas_inhabiles[$fecha_dia])) and
                      ($fecha_antier  == $fecha_ultima_gracia) )
                  {             
                        $IMB = 0;       
                        //$this->fecha_ultima_gracia_efectiva= date("Y-m-d",mktime(0,0,0,$_m, $_d, $_y));
                        
                  }
                  //-------------------------------------------------------------------------



                  //-------------------------------------------------------------------------
                  // Si hoy es domingo y ayer día feriado y mañana es inhábil, HOY no hay moratorio hoy.
                  //-------------------------------------------------------------------------
                  if( (date("w",mktime(0,0,0,$_m, $_d, $_y)) == 0  ) and 
                      (!empty($this->fechas_inhabiles[$fecha_maniana])) and
                      ($fecha_ayer  == $fecha_ultima_gracia) )
                  {             
                        $IMB = 0;       
                           //$this->fecha_ultima_gracia_efectiva = date("Y-m-d",mktime(0,0,0,$_m, $_d, $_y));
                        
                  }
                  //-------------------------------------------------------------------------



                  //-------------------------------------------------------------------------
                  // Si ayer fue día inhabil, y hoy también es inhabil hoy no se cobra.
                  //-------------------------------------------------------------------------
                  if((!empty($this->fechas_inhabiles[$fecha_dia])  and   !empty($this->fechas_inhabiles[$fecha_ayer]) ))
                  {             
                        $IMB = 0;       

                  }







/*
                  //-------------------------------------------------------------------------
                  // Si hoy es domingo y ayer sábado fue mi día de gracia, no hay moratorio hoy.
                  //-------------------------------------------------------------------------
                  if( (date("w",mktime(0,0,0,$_m, $_d, $_y)) == 0) and (($fecha_ayer  == $fecha_ultima_gracia) or ( $fecha_ayer  == $this->fecha_ultima_gracia_efectiva)))
                  {             
                        $IMB = 0;       
                        $this->fecha_ultima_gracia_efectiva = date("Y-m-d",mktime(0,0,0,$_m, $_d, $_y));
                        
                  }
                  //-------------------------------------------------------------------------



                  //-------------------------------------------------------------------------
                  // Si ayer fue día de gracia, y hoy es inhabil hoy no se cobra .
                  //-------------------------------------------------------------------------
                  if((!empty($this->fechas_inhabiles[$fecha_dia])  ) and (($fecha_ayer == $fecha_ultima_gracia) or ( $fecha_ayer  == $this->fecha_ultima_gracia_efectiva)))
                  {             
                        $IMB = 0;       

                        $this->fecha_ultima_gracia_efectiva= date("Y-m-d",mktime(0,0,0,$_m, $_d, $_y));

                  }


                if(($_d == 31) )
                {
                        
                        $IMB = 0;       
                }




                //-------------------------------------------------------------------------
                // Si ayer es primer día del mes y hoy es día feriado...hoy no cobro
                //-------------------------------------------------------------------------             

                  if((!empty($this->fechas_inhabiles[$fecha_dia]) ))
                  {             
                        $IMB = 0;       

                  }



                if(($fecha_dia == '2008-06-01')  or ($fecha_dia == '2009-02-01') or ($fecha_dia == '2009-02-02') or ($fecha_dia == '2009-05-01'))
                {
                   $IMB = 0;
                }
 
                if((!empty($this->fechas_inhabiles[$fecha_dia])))
                {
                   $IMB = 0;
                }

                  
                //-------------------------------------------------------------------------
                // Primer domingo de cada mes... es gracia
                //-------------------------------------------------------------------------             
                if(($_d < 7) and (date("w",mktime(0,0,0,$_m, $_d, $_y))==0))
                {
                        
                        $IMB = 0;       

                        //$this->fecha_ultima_gracia_efectiva= date("Y-m-d",mktime(0,0,0,$_m, $_d, $_y));
                }


                //-------------------------------------------------------------------------
                // Primer día del mes
                //-------------------------------------------------------------------------             
                if(($_d == 31))
                {
                        
                        $IMB = 0;       
                }




*/









        if($this->detalle_moratorios[($idx-1) ]['ID_Cargo'] == $this->detalle_moratorios[$idx ]['ID_Cargo'])
        {               
                $this->SumaMoratoriosCalculadosPorCuota += $_valor_moratorio_calculado; 
        }
        else
        {
                $this->SumaMoratoriosCalculadosPorCuota  = $_valor_moratorio_calculado;         

        }
        

        
        
        //$this->detalle_moratorios[$idx]['MoratorioCalculado'] = $IMB;
        $this->detalle_moratorios[$idx]['MoratorioCalculado'] = $_valor_moratorio_calculado;
        
        if(($this->detalle_moratorios[$idx ]['ID_Mov'] !=  $this->detalle_moratorios[($idx-1) ]['ID_Mov'] ) and ($this->detalle_moratorios[$idx ]['Saldo_Moratorio']<= 0.01))
        {
        
                $this->SumaMoratoriosCalculadosPorCuota = $_valor_moratorio_calculado;
                        
        }


                if((( $this->moratorio_minimo - $this->detalle_moratorios[$idx ]['Saldo_Moratorio'] )> 0.01  ) and ($this->detalle_moratorios[$idx ]['dias_mora_maximos'] >1))
                {
                           // $this->detalle_moratorios[$idx ]['Tipo']=$IMB;
                           
                            if($IMB>0)
                            {
                                        
                           
                           
                           
                                    if(($this->SumaMoratoriosCalculadosPorCuota < $this->moratorio_minimo) and ($IMB < $this->moratorio_minimo ))
                                    {

                                             $IMB = $this->moratorio_minimo - $this->detalle_moratorios[$idx]['Saldo_Moratorio'];


                                    }
                                    else
                                    {
                                        $IMB =$this->SumaMoratoriosCalculadosPorCuota ;
                                    }
                                
                                
                                $compensado = true;
                            
                            
                            }
                }
        
/**/

        if($this->detalle_moratorios[($idx-1) ]['ID_Cargo'] == $this->detalle_moratorios[$idx ]['ID_Cargo'])
        if(!$compensado) 
        {
        
        
        
                  if(((($this->SumaMoratoriosCalculadosPorCuota) - $this->detalle_moratorios[($idx-1)]['Acumulado_Cuota'] )<0) or ($IMB ==0))
                  {
                        //$this->detalle_moratorios[$idx ]['Tipo'].=" XX ";
                          
                            $IMB = 0;
                  }
                  else
                  {
                                                
                        $IMB = (($this->SumaMoratoriosCalculadosPorCuota) - $this->detalle_moratorios[($idx-1)]['Acumulado_Cuota']);

                                                
                  }
        }

        
        
        if(($this->detalle_moratorios[$idx ]['dias_mora_maximos'] >($this->max_dias_calculo-1)) or ($acumdias > ($this->max_dias_calculo-1)))
        {
               $IMB = 0;
        }






        $IMB = round($IMB,2);

        if($this->detalle_moratorios[($idx-1) ]['ID_Cargo'] == $this->detalle_moratorios[$idx ]['ID_Cargo'])
        {               
                $this->SumaMoratoriosCargadosPorCuota += $IMB;          
        }
        else
        {
                $this->SumaMoratoriosCargadosPorCuota = $IMB;           
        }





         $SumaIMB += $IMB;

         $this->detalle_moratorios[$idx ]['IMB']   = $IMB;
         $this->detalle_moratorios[$idx ]['IM']    = $IMB / (1+$pIVA);



        if($this->detalle_moratorios[($idx-1)]['ID_Cargo'] == $this->detalle_moratorios[($idx)]['ID_Cargo'])
        {


                        if(( $this->moratorio_minimo - $this->detalle_moratorios[$idx ]['Saldo_Moratorio'] )> 0.01 )
                        {

                                //$this->SumaMoratoriosCalculadosPorCuota += $_valor_moratorio_calculado ;

                                //$this->detalle_moratorios[$idx ]['SumaMoratoriosCalculadosPorCuota']  = $this->detalle_moratorios[$idx]['MoratorioCalculado'] ;               
                                $this->detalle_moratorios[$idx ]['SumaMoratoriosCalculadosPorCuota']    = $this->detalle_moratorios[($idx-1)]['SumaMoratoriosCalculadosPorCuota'] +$this->detalle_moratorios[$idx]['MoratorioCalculado'] ;
                                $this->detalle_moratorios[$idx ]['Acumulado_Cuota']                     = $this->detalle_moratorios[$idx ]['Saldo_Moratorio'] + $IMB;

                        }
                        else
                        {
                        

                          $this->detalle_moratorios[$idx ]['Acumulado_Cuota']                   = $this->detalle_moratorios[($idx-1)]['Acumulado_Cuota']  + $IMB;
                          $this->detalle_moratorios[$idx ]['SumaMoratoriosCalculadosPorCuota']  = $this->detalle_moratorios[($idx-1)]['SumaMoratoriosCalculadosPorCuota'] +$this->detalle_moratorios[$idx]['MoratorioCalculado'] ;


                        }
          
        }
        else
        {
        
                        $this->detalle_moratorios[$idx ]['Acumulado_Cuota']     = $IMB; 
                        $this->detalle_moratorios[$idx ]['SumaMoratoriosCalculadosPorCuota']    = $this->detalle_moratorios[$idx]['MoratorioCalculado'] ;

        }


        if(($this->detalle_moratorios[$idx ]['ID_Mov'] !=  $this->detalle_moratorios[($idx-1) ]['ID_Mov'] ) and ($this->detalle_moratorios[$idx ]['Saldo_Moratorio']<= 0.01))
        {
        
                $this->SumaMoratoriosCalculadosPorCuota = $_valor_moratorio_calculado;          
                $this->detalle_moratorios[($idx)]['MoratorioCalculado'] = $_valor_moratorio_calculado ;
                $this->detalle_moratorios[($idx)]['SumaMoratoriosCalculadosPorCuota']   =$_valor_moratorio_calculado ;
        
        }

        ++$_d;


        ++$idx;
        ++$j;
        
        ++$num_pIVA;
        $Sum_pIVA += $pIVA;
        

        $fecha_dia = date("Y-m-d",mktime(0,0,0,$_m, $_d, $_y));
        if($fecha_dia > $fecha_hasta)
        {
                break;
        }
        
        list($_y, $_m, $_d) = explode("-",$fecha_dia);
        

}


    if($num_pIVA>0)
        $prom_IVA = $Sum_pIVA/$num_pIVA;
      else
        $prom_IVA = 0;
        
         
    $this->aplicacion[$id]['IM_pIVA'] = $prom_IVA;    

    $this->aplicacion[$id]['MoraMAX'] = $this->detalle_moratorios[($idx-1) ]['dias_mora_maximos'];
    
/*    
    $this->SumaIM       += round(($SumaIMB/(1+$this->iva_pcnt_moratorios)),2) + $this->aplicacion[$id]['CARGO_Moratorio'];
    $this->SaldoIM      += round(($SumaIMB/(1+$this->iva_pcnt_moratorios)),2) + $this->aplicacion[$id]['CARGO_Moratorio'];
    
    
    $IM = $SumaIMB/(1+$this->iva_pcnt_moratorios);
*/


    $this->SumaIM       += round(($SumaIMB/(1+$pIVA)),2) + $this->aplicacion[$id]['CARGO_Moratorio'];
    $this->SaldoIM      += round(($SumaIMB/(1+$pIVA)),2) + $this->aplicacion[$id]['CARGO_Moratorio'];
    
    
    $IM = $SumaIMB/(1+$prom_IVA);
    $this->aplicacion[$id]['IM'] = $IM;


        
        
    $this->SumaMoratorioBruto   += $SumaIMB + $this->aplicacion[$id]['CARGO_Moratorio'] + $this->aplicacion[$id]['CARGO_IVA_Moratorio'];
    $this->SumaIMB               = $this->SumaMoratorioBruto;
    $this->SumaMoratorio        += $this->SumaIM + $this->aplicacion[$id]['CARGO_Moratorio'];
  
    $this->SumaIVAMoratorio     += ($SumaIMB - $IM) + $this->aplicacion[$id]['CARGO_IVA_Moratorio'];


  return($SumaIMB);



}

//----------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------
// PRELACION : Aplicacion de otros cargos
//----------------------------------------------------------------------------------------------------------

function aplica_abonos_otros_cargos($por_aplicar, $SALDO_Otros, $SALDO_IVA_Otros,  $id, $NumCuota)
{

	$ABONO_Otros		=0;
	$ABONO_Otros_IVA	=0;

        if(( $por_aplicar < 0) and (($SALDO_Otros )> 0))
        {
                //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
                // Verificamos si tiene parte proporcionel de IVA
                //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
                {
                        
                       // $SALDO_IVA_OTROS = $SALDO_Otros * $this->iva_pcnt_comisiones;
                       $SALDO_IVA_OTROS = $SALDO_IVA_Otros;

                        if(abs($por_aplicar) >= ($SALDO_Otros + $SALDO_IVA_OTROS))
                        {
                                $ABONO_Otros       += -1 * $SALDO_Otros ;
                                $ABONO_Otros_IVA   += -1 * $SALDO_IVA_OTROS ;

                                $por_aplicar += $SALDO_Otros + $SALDO_IVA_OTROS;


                        }
                        else
                        {

                                $factor1 = $SALDO_Otros/($SALDO_Otros + $SALDO_IVA_OTROS);
                                $factor2 = $SALDO_IVA_OTROS/($SALDO_Otros + $SALDO_IVA_OTROS);

                                $ABONO_Otros              += round($por_aplicar * $factor1,4);
                                $ABONO_Otros_IVA          += round($por_aplicar * $factor2,4);



                                $por_aplicar = 0;

                        }


                }

                $this->aplicacion[$id]['ABONO_Otros']                += $ABONO_Otros	;
                $this->aplicacion[$id]['ABONO_Otros_IVA']            += $ABONO_Otros_IVA;


		if($this->aplicacion[$id]['Concepto'] == 'Documento')
		{
			$this->Suma_Abono_Otros_Documento               +=      $ABONO_Otros	;
			$this->Suma_Abono_IVA_Otros_Documento           +=      $ABONO_Otros_IVA;                
		}
		else
		{
			$this->Suma_Abono_Otros_Efectivo                +=      $ABONO_Otros	;
			$this->Suma_Abono_IVA_Otros_Efectivo            +=      $ABONO_Otros_IVA;
		}

        }


 //     debug(" $NumCuota [".$this->aplicacion[$id]['Concepto']."] (".$this->aplicacion[$id]['ABONO_Otros'].") ==== [".$this->Suma_Abono_Otros_Efectivo ."]");



         $this->aplicacion[$id]['SALDO_Otros']     = $SALDO_Otros;
         $this->aplicacion[$id]['SALDO_Otros_IVA'] = $SALDO_IVA_OTROS + $this->aplicacion[$id]['ABONO_Otros_IVA'] ;



         
         
         
         
         

        return($por_aplicar);   
        
}

//----------------------------------------------------------------------------------------------------------
// PRELACION : Aplicacion de capital
//----------------------------------------------------------------------------------------------------------

function aplica_abonos_capital($por_aplicar, $SALDO_Capital, $id, $NumCuota)
{

         $ABONO_Capital = 0;
         
         
         if(( $por_aplicar < 0) and (($SALDO_Capital )> 0))
         {
                if(abs($por_aplicar) >= $SALDO_Capital)
                {
                        $ABONO_Capital = -1 * $SALDO_Capital ;
                        $por_aplicar +=$SALDO_Capital;

                }
                else
                {
                        $ABONO_Capital = $por_aplicar ;
                        $por_aplicar = 0;


                }

              $this->aplicacion[$id]['ABONO_Capital'] += $ABONO_Capital;
              $this->aplicacion[$id]['SALDO_Capital']  = $SALDO_Capital;


         }




        if($this->aplicacion[$id]['Concepto']== 'Documento')
        {
                $this->Suma_Abono_Capital_Documento             +=      $ABONO_Capital;         
        }
        else
        {
                $this->Suma_Abono_Capital_Efectivo              +=      $ABONO_Capital;
        }





        if($this->num_cuota_preprocesada > $NumCuota)
        {
        
                $this->abonos_contra_saldos_vigentes_por_couta[$this->num_cuota_preprocesada]['Capital'] += $ABONO_Capital;
                
                $fecha_abono = $this->aplicacion[$id]['Fecha'];
                $this->abonos_contra_saldos_vigentes_por_pago[$fecha_abono][$this->num_cuota_preprocesada]['Capital']  += $ABONO_Capital;

                $this->abonos_contra_saldos_vigentes_por_id_pago[($this->aplicacion[$id]['ID'] )]['Capital']           += $ABONO_Capital;

        }



         return($por_aplicar);
}


//----------------------------------------------------------------------------------------------------------
// PRELACION : Aplicacion de intereses normales
//----------------------------------------------------------------------------------------------------------

function aplica_abonos_intereses($por_aplicar, $SALDO_Interes, $SALDO_IVA_Interes, $id, $NumCuota)
{

//        debug("aplica_abonos_intereses($por_aplicar, $SALDO_Interes, $SALDO_IVA_Interes, $id, $NumCuota)");


        $ABONO_Interes          = 0;
        $ABONO_Interes_IVA      = 0;


        if(( $por_aplicar < 0) and (($SALDO_Interes )> 0))
        {
                //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
                // Verificamos si tiene parte proporcionel de IVA
                //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

                {

                        // $SALDO_IVA_INTERES = $SALDO_Interes * $this->iva_pcnt_intereses;
                        $SALDO_IVA_INTERES = $SALDO_IVA_Interes;

                        if(abs($por_aplicar) >= ($SALDO_Interes + $SALDO_IVA_INTERES))
                        {
                                $ABONO_Interes          = -1 * $SALDO_Interes ;
                                $ABONO_Interes_IVA      = -1 * $SALDO_IVA_INTERES ;

                                $por_aplicar += $SALDO_Interes + $SALDO_IVA_INTERES;


                        }
                        else
                        {

                                $factor1 = $SALDO_Interes/($SALDO_Interes + $SALDO_IVA_INTERES);
                                $factor2 = $SALDO_IVA_INTERES/($SALDO_Interes + $SALDO_IVA_INTERES);

                                $ABONO_Interes          = round($por_aplicar * $factor1,4);
                                $ABONO_Interes_IVA      = round($por_aplicar * $factor2,4);



                                $por_aplicar = 0;

                        }


                }

                
                
                $this->aplicacion[$id]['ABONO_Interes']                += $ABONO_Interes;
                $this->aplicacion[$id]['ABONO_Interes_IVA']            += $ABONO_Interes_IVA;
        }






        if($this->aplicacion[$id]['Concepto']== 'Documento')
        {
                $this->Suma_Abono_Interes_Documento             +=      $ABONO_Interes;
                $this->Suma_Abono_IVA_Interes_Documento         +=      $ABONO_Interes_IVA;
                
        }
        else
        {
                $this->Suma_Abono_Interes_Efectivo              +=      $ABONO_Interes;
                $this->Suma_Abono_IVA_Interes_Efectivo          +=      $ABONO_Interes_IVA;
        }


        if($this->num_cuota_preprocesada > $NumCuota)
        {

                $fecha_abono = $this->aplicacion[$id]['Fecha'];

        
                $this->abonos_contra_saldos_vigentes_por_couta[$this->num_cuota_preprocesada]['Interes']     += $ABONO_Interes;
                $this->abonos_contra_saldos_vigentes_por_couta[$this->num_cuota_preprocesada]['Interes_IVA'] += $ABONO_Interes_IVA;



                $this->abonos_contra_saldos_vigentes_por_pago[$fecha_abono][$this->num_cuota_preprocesada]['Interes']      += $ABONO_Interes;   
                $this->abonos_contra_saldos_vigentes_por_pago[$fecha_abono][$this->num_cuota_preprocesada]['Interes_IVA']  += $ABONO_Interes_IVA;


                $this->abonos_contra_saldos_vigentes_por_id_pago[($this->aplicacion[$id]['ID'] )]['Interes']            += $ABONO_Interes;      
                $this->abonos_contra_saldos_vigentes_por_id_pago[($this->aplicacion[$id]['ID'] )]['Interes_IVA']        += $ABONO_Interes_IVA;



        }





        return($por_aplicar);
}

//----------------------------------------------------------------------------------------------------------
// PRELACION : Aplicacion de comisiones
//----------------------------------------------------------------------------------------------------------

function aplica_abonos_comisiones($por_aplicar, $SALDO_Comision, $SALDO_IVA_Comision, $id, $NumCuota)
{

       // debug("($NumCuota) ".$this->aplicacion[$id]['Descripcion']  ." Comision SALDO_Comision $SALDO_Comision Por Aplicar ".$por_aplicar);



        $ABONO_Comision         = 0 ;
        $ABONO_Comision_IVA     = 0 ;



        if(( $por_aplicar < 0) and ($SALDO_Comision > 0))
        {

/*
                //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
                // Verificamos si tiene parte proporcionel de IVA
                //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
                if(!$this->iva_pcnt_comisiones)
                {

                        if(abs($por_aplicar) >= $SALDO_Comision)
                        {
                                $ABONO_Comision = -1 * $SALDO_Comision ;
                                $por_aplicar +=$SALDO_Comision;

                        }
                        else
                        {
                                $ABONO_Comision = $por_aplicar ;
                                $por_aplicar = 0;


                        }
                }
                else
*/                
                {

                       // $SALDO_IVA_COMISION = $this->iva_pcnt_comisiones * $SALDO_Comision;
                        $SALDO_IVA_COMISION = $SALDO_IVA_Comision;

                        if(abs($por_aplicar) >= ($SALDO_Comision + $SALDO_IVA_COMISION))
                        {
                                $ABONO_Comision         = -1  * $SALDO_Comision ;
                                $ABONO_Comision_IVA     = -1  * $SALDO_IVA_COMISION ;

                                $por_aplicar += $SALDO_Comision + $SALDO_IVA_COMISION;

                        }
                        else
                        {
                                $factor1 = $SALDO_Comision/($SALDO_Comision + $SALDO_IVA_COMISION);
                                $factor2 = $SALDO_IVA_COMISION/($SALDO_Comision + $SALDO_IVA_COMISION);




                                $ABONO_Comision         = round($por_aplicar * $factor1,4);
                                $ABONO_Comision_IVA     = round($por_aplicar * $factor2,4);



                                $por_aplicar = 0;

                        }


                }




                $this->aplicacion[$id]['ABONO_Comision']                += $ABONO_Comision;
                $this->aplicacion[$id]['ABONO_Comision_IVA']            += $ABONO_Comision_IVA;

            
                $this->aplicacion[$id]['SALDO_Comision']     = $SALDO_Comision;
                $this->aplicacion[$id]['SALDO_Comision_IVA'] = $SALDO_IVA_COMISION + $this->aplicacion[$id]['ABONO_Comision_IVA'] ;
                
                




        }




        if($this->aplicacion[$id]['Concepto']== 'Documento')
        {
                $this->Suma_Abono_Comision_Documento            += $ABONO_Comision;
                $this->Suma_Abono_IVA_Comision_Documento        += $ABONO_Comision_IVA;
                
        }
        else
        {
                $this->Suma_Abono_Comision_Efectivo             += $ABONO_Comision;
                $this->Suma_Abono_IVA_Comision_Efectivo         += $ABONO_Comision_IVA;
        }

      //debug("COMISION ($NumCuota)  [".$this->aplicacion[$id]['Concepto']."] (".$this->aplicacion[$id]['ABONO_Comision'].") ==== [".$this->Suma_Abono_Comision_Efectivo ."]");


        if($this->num_cuota_preprocesada > $NumCuota)
        {
        
                $this->abonos_contra_saldos_vigentes_por_couta[$this->num_cuota_preprocesada]['Comision']     += $ABONO_Comision;
                $this->abonos_contra_saldos_vigentes_por_couta[$this->num_cuota_preprocesada]['Comision_IVA'] += $ABONO_Comision_IVA;
                                
                $fecha_abono = $this->aplicacion[$id]['Fecha'];
                $this->abonos_contra_saldos_vigentes_por_pago[$fecha_abono][$this->num_cuota_preprocesada]['Comision']          += $ABONO_Comision;
                $this->abonos_contra_saldos_vigentes_por_pago[$fecha_abono][$this->num_cuota_preprocesada]['Comision_IVA']      += $ABONO_Comision_IVA;


                $this->abonos_contra_saldos_vigentes_por_id_pago[($this->aplicacion[$id]['ID'] )]['Comision']                   += $ABONO_Comision;
                $this->abonos_contra_saldos_vigentes_por_id_pago[($this->aplicacion[$id]['ID'] )]['Comision_IVA']               += $ABONO_Comision_IVA;


        }





        return($por_aplicar);
}


//----------------------------------------------------------------------------------------------------------
// PRELACION : Aplicacion de moratorios
//----------------------------------------------------------------------------------------------------------

function aplica_abonos_moratorios($por_aplicar, $SALDO_Moratorio, $SALDO_IVA_Moratorio, $id, $NumCuota)
{




            //$this->aplicacion[$id]['ABONO_Moratorio'] = 0;
            if(( $por_aplicar < 0) and (($SALDO_Moratorio )> 0))
             {
                //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
                // Verificamos si tiene parte proporcional de IVA
                //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
                {

                       // $SALDO_IVA_MORATORIO = $this->iva_pcnt_moratorios * $SALDO_Moratorio;                       
                        $SALDO_IVA_MORATORIO = $SALDO_IVA_Moratorio;                       

                        if(abs($por_aplicar) >= ($SALDO_Moratorio + $SALDO_IVA_MORATORIO))
                        {
                                $this->aplicacion[$id]['ABONO_Moratorio']               += -1 * $SALDO_Moratorio ;
                                $this->aplicacion[$id]['ABONO_Moratorio_IVA']           += -1 * $SALDO_IVA_MORATORIO ;

                                $por_aplicar += $SALDO_Moratorio + $SALDO_IVA_MORATORIO;

                        }
                        else
                        {

                                $factor1 = $SALDO_Moratorio/($SALDO_Moratorio + $SALDO_IVA_MORATORIO);
                                $factor2 = $SALDO_IVA_MORATORIO/($SALDO_Moratorio + $SALDO_IVA_MORATORIO);

                                $this->aplicacion[$id]['ABONO_Moratorio']               += round($por_aplicar * $factor1,4);
                                $this->aplicacion[$id]['ABONO_Moratorio_IVA']           += round($por_aplicar * $factor2,4);

                                $por_aplicar = 0;

                        }



                }


                if($this->aplicacion[$id]['Concepto']== 'Documento')
                {
                        $this->Suma_Abono_Moratorio_Documento           +=      $this->aplicacion[$id]['ABONO_Moratorio'];
                        $this->Suma_Abono_IVA_Moratorio_Documento       +=      $this->aplicacion[$id]['ABONO_Moratorio_IVA'];

                }
                else
                {
                        $this->Suma_Abono_Moratorio_Efectivo            +=      $this->aplicacion[$id]['ABONO_Moratorio'];
                        $this->Suma_Abono_IVA_Moratorio_Efectivo        +=      $this->aplicacion[$id]['ABONO_Moratorio_IVA'];
                }



                $this->aplicacion[$id]['SALDO_Moratorio']      = $SALDO_Moratorio;
                $this->aplicacion[$id]['SALDO_Moratorio_IVA']  = $SALDO_Moratorio + $this->aplicacion[$id]['ABONO_Moratorio_IVA'] ;



        }



        return($por_aplicar);
}

//----------------------------------------------------------------------------------------------------------
//
//----------------------------------------------------------------------------------------------------------

function aplica_abonos_seguros($por_aplicar,      $SALDO_SegV, $SALDO_SegD, $SALDO_SegB, $SALDO_IVA_SegV, $SALDO_IVA_SegD, $SALDO_IVA_SegB,    $id, $NumCuota)
{


        $SALDO_Seguros = $SALDO_SegV + $SALDO_IVA_SegV +
                         $SALDO_SegD + $SALDO_IVA_SegD +
                         $SALDO_SegB + $SALDO_IVA_SegB ;

        //debug( $this->seguros_prelacion."   ".$id."), Cuota ".$NumCuota." por aplicar : ".$por_aplicar." Saldo : ".$SALDO_Seguros);

            if(( $por_aplicar < 0) and (($SALDO_Seguros )> 0))
             {

                  foreach($this->seguros_aprelacion AS $TIPO)
                          {
                                  switch( $TIPO )
                                  {    

                                                       //-------------------------------------------------------------------------
                                                       // Seguro de Vida
                                                       //-------------------------------------------------------------------------

                                       case 'V' : $por_aplicar = $this->aplica_abonos_segv($por_aplicar,   $SALDO_SegV,   $SALDO_IVA_SegV,    $id, $NumCuota);        break;

                                                       //-------------------------------------------------------------------------
                                                       // Seguro de Desempleo
                                                       //-------------------------------------------------------------------------
                                       case 'D' : $por_aplicar = $this->aplica_abonos_segd($por_aplicar,   $SALDO_SegD,   $SALDO_IVA_SegD,    $id, $NumCuota);        break;

                                                       //-------------------------------------------------------------------------
                                                       // Seguro de Bienes Muebles/Inmeuebles
                                                       //-------------------------------------------------------------------------
                                       case 'B' : $por_aplicar = $this->aplica_abonos_segb($por_aplicar,   $SALDO_SegB,   $SALDO_IVA_SegB,    $id, $NumCuota);        break;


                                  }

                        }
            }


        return($por_aplicar);

}
//----------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------

function aplica_abonos_segv($por_aplicar,   $SALDO_SegV,   $SALDO_IVA_SegV,    $id, $NumCuota)
{



        $ABONO_SegV         = 0 ;
        $ABONO_SegV_IVA     = 0 ;



        if(( $por_aplicar < 0) and ($SALDO_SegV > 0))
        {

                {

                        if(abs($por_aplicar) >= ($SALDO_SegV + $SALDO_IVA_SegV))
                        {
                                $ABONO_SegV         = -1  * $SALDO_SegV ;
                                $ABONO_SegV_IVA     = -1  * $SALDO_IVA_SegV ;

                                $por_aplicar += ($SALDO_SegV + $SALDO_IVA_SegV);

                        }
                        else
                        {
                                $factor1 =     $SALDO_SegV/($SALDO_SegV + $SALDO_IVA_SegV);
                                $factor2 = $SALDO_IVA_SegV/($SALDO_SegV + $SALDO_IVA_SegV);




                                $ABONO_SegV         = round($por_aplicar * $factor1,4);
                                $ABONO_SegV_IVA     = round($por_aplicar * $factor2,4);



                                $por_aplicar = 0;

                        }


                }




                $this->aplicacion[$id]['ABONO_SegV']                += $ABONO_SegV;   
                $this->aplicacion[$id]['ABONO_SegV_IVA']            += $ABONO_SegV_IVA;


            
                $this->aplicacion[$id]['SALDO_SegV']     = $SALDO_SegV;
                $this->aplicacion[$id]['SALDO_SegV_IVA'] = $SALDO_IVA_SegV + $this->aplicacion[$id]['ABONO_SegV_IVA'] ;
                
                




        }




        if($this->aplicacion[$id]['Concepto']== 'Documento')
        {
                $this->Suma_Abono_SegV_Documento            += $ABONO_SegV;   
                $this->Suma_Abono_IVA_SegV_Documento        += $ABONO_SegV_IVA;
                
        }
        else
        {
                $this->Suma_Abono_SegV_Efectivo             += $ABONO_SegV;   
                $this->Suma_Abono_IVA_SegV_Efectivo         += $ABONO_SegV_IVA;
        }



        if($this->num_cuota_preprocesada > $NumCuota)
        {
        
                $this->abonos_contra_saldos_vigentes_por_couta[$this->num_cuota_preprocesada]['SegV'    ]                   += $ABONO_SegV;   
                $this->abonos_contra_saldos_vigentes_por_couta[$this->num_cuota_preprocesada]['SegV_IVA']                   += $ABONO_SegV_IVA;
                                
                $fecha_abono = $this->aplicacion[$id]['Fecha'];
                $this->abonos_contra_saldos_vigentes_por_pago[$fecha_abono][$this->num_cuota_preprocesada]['SegV'    ]      += $ABONO_SegV;   
                $this->abonos_contra_saldos_vigentes_por_pago[$fecha_abono][$this->num_cuota_preprocesada]['SegV_IVA']      += $ABONO_SegV_IVA;


                $this->abonos_contra_saldos_vigentes_por_id_pago[($this->aplicacion[$id]['ID'] )]['SegV'    ]               += $ABONO_SegV;   
                $this->abonos_contra_saldos_vigentes_por_id_pago[($this->aplicacion[$id]['ID'] )]['SegV_IVA']               += $ABONO_SegV_IVA;


        }




        return($por_aplicar);


}
//----------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------

function aplica_abonos_segd($por_aplicar,   $SALDO_SegD,   $SALDO_IVA_SegD,    $id, $NumCuota)
{

//debug( " por_aplicar[$por_aplicar],   SALDO_SegD[$SALDO_SegD],   SALDO_IVA_SegD[$SALDO_IVA_SegD],    id[$id], NumCuota[$NumCuota]");


        $ABONO_SegD         = 0 ;
        $ABONO_SegD_IVA     = 0 ;



        if(( $por_aplicar < 0) and ($SALDO_SegD > 0))
        {

                {

                        if(abs($por_aplicar) >= ($SALDO_SegD + $SALDO_IVA_SegD))
                        {
                                $ABONO_SegD         = -1  * $SALDO_SegD ;
                                $ABONO_SegD_IVA     = -1  * $SALDO_IVA_SegD ;

                                $por_aplicar += ($SALDO_SegD + $SALDO_IVA_SegD);

                        }
                        else
                        {
                                $factor1 =     $SALDO_SegD/($SALDO_SegD + $SALDO_IVA_SegD);
                                $factor2 = $SALDO_IVA_SegD/($SALDO_SegD + $SALDO_IVA_SegD);




                                $ABONO_SegD         = round($por_aplicar * $factor1,4);
                                $ABONO_SegD_IVA     = round($por_aplicar * $factor2,4);



                                $por_aplicar = 0;

                        }


                }




                $this->aplicacion[$id]['ABONO_SegD']                += $ABONO_SegD;   
                $this->aplicacion[$id]['ABONO_SegD_IVA']            += $ABONO_SegD_IVA;


            
                $this->aplicacion[$id]['SALDO_SegD']     = $SALDO_SegD;
                $this->aplicacion[$id]['SALDO_SegD_IVA'] = $SALDO_IVA_SegD + $this->aplicacion[$id]['ABONO_SegD_IVA'] ;
                
                




        }




        if($this->aplicacion[$id]['Concepto']== 'Documento')
        {
                $this->Suma_Abono_SegD_Documento            += $ABONO_SegD;   
                $this->Suma_Abono_IVA_SegD_Documento        += $ABONO_SegD_IVA;
                
        }
        else
        {
                $this->Suma_Abono_SegD_Efectivo             += $ABONO_SegD;   
                $this->Suma_Abono_IVA_SegD_Efectivo         += $ABONO_SegD_IVA;
        }



        if($this->num_cuota_preprocesada > $NumCuota)
        {
        
                $this->abonos_contra_saldos_vigentes_por_couta[$this->num_cuota_preprocesada]['SegD'    ]                   += $ABONO_SegD;   
                $this->abonos_contra_saldos_vigentes_por_couta[$this->num_cuota_preprocesada]['SegD_IVA']                   += $ABONO_SegD_IVA;
                                
                $fecha_abono = $this->aplicacion[$id]['Fecha'];
                $this->abonos_contra_saldos_vigentes_por_pago[$fecha_abono][$this->num_cuota_preprocesada]['SegD'    ]      += $ABONO_SegD;   
                $this->abonos_contra_saldos_vigentes_por_pago[$fecha_abono][$this->num_cuota_preprocesada]['SegD_IVA']      += $ABONO_SegD_IVA;


                $this->abonos_contra_saldos_vigentes_por_id_pago[($this->aplicacion[$id]['ID'] )]['SegD'    ]               += $ABONO_SegD;   
                $this->abonos_contra_saldos_vigentes_por_id_pago[($this->aplicacion[$id]['ID'] )]['SegD_IVA']               += $ABONO_SegD_IVA;


        }


        return($por_aplicar);



}
//----------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------

function aplica_abonos_segb($por_aplicar,   $SALDO_SegB,   $SALDO_IVA_SegB,    $id, $NumCuota)
{


        $ABONO_SegB         = 0 ;
        $ABONO_SegB_IVA     = 0 ;



        if(( $por_aplicar < 0) and ($SALDO_SegB > 0))
        {

                {

                        if(abs($por_aplicar) >= ($SALDO_SegB + $SALDO_IVA_SegB))
                        {
                                $ABONO_SegB         = -1  * $SALDO_SegB ;
                                $ABONO_SegB_IVA     = -1  * $SALDO_IVA_SegB ;

                                $por_aplicar += ($SALDO_SegB + $SALDO_IVA_SegB);

                        }
                        else
                        {
                                $factor1 =     $SALDO_SegB/($SALDO_SegB + $SALDO_IVA_SegB);
                                $factor2 = $SALDO_IVA_SegB/($SALDO_SegB + $SALDO_IVA_SegB);




                                $ABONO_SegB         = round($por_aplicar * $factor1,4);
                                $ABONO_SegB_IVA     = round($por_aplicar * $factor2,4);



                                $por_aplicar = 0;

                        }


                }




                $this->aplicacion[$id]['ABONO_SegB']                += $ABONO_SegB;   
                $this->aplicacion[$id]['ABONO_SegB_IVA']            += $ABONO_SegB_IVA;


            
                $this->aplicacion[$id]['SALDO_SegB']     = $SALDO_SegB;
                $this->aplicacion[$id]['SALDO_SegB_IVA'] = $SALDO_IVA_SegB + $this->aplicacion[$id]['ABONO_SegB_IVA'] ;
                
                




        }




        if($this->aplicacion[$id]['Concepto']== 'Documento')
        {
                $this->Suma_Abono_SegB_Documento            += $ABONO_SegB;   
                $this->Suma_Abono_IVA_SegB_Documento        += $ABONO_SegB_IVA;
                
        }
        else
        {
                $this->Suma_Abono_SegB_Efectivo             += $ABONO_SegB;   
                $this->Suma_Abono_IVA_SegB_Efectivo         += $ABONO_SegB_IVA;
        }



        if($this->num_cuota_preprocesada > $NumCuota)
        {
        
                $this->abonos_contra_saldos_vigentes_por_couta[$this->num_cuota_preprocesada]['SegB'    ]                   += $ABONO_SegB;   
                $this->abonos_contra_saldos_vigentes_por_couta[$this->num_cuota_preprocesada]['SegB_IVA']                   += $ABONO_SegB_IVA;
                                
                $fecha_abono = $this->aplicacion[$id]['Fecha'];
                $this->abonos_contra_saldos_vigentes_por_pago[$fecha_abono][$this->num_cuota_preprocesada]['SegB'    ]      += $ABONO_SegB;   
                $this->abonos_contra_saldos_vigentes_por_pago[$fecha_abono][$this->num_cuota_preprocesada]['SegB_IVA']      += $ABONO_SegB_IVA;


                $this->abonos_contra_saldos_vigentes_por_id_pago[($this->aplicacion[$id]['ID'] )]['SegB'    ]               += $ABONO_SegB;   
                $this->abonos_contra_saldos_vigentes_por_id_pago[($this->aplicacion[$id]['ID'] )]['SegB_IVA']               += $ABONO_SegB_IVA;


        }


        return($por_aplicar);



}









//================================================================================================================
function obtener_clasificacion_puntualidad()
{

                $sql="  SELECT ID_MOP, MOP
                                FROM cat_formas_pago
                        WHERE Dias_vencidos_hasta >= '".$this->dias_mora."'
                                ORDER BY Dias_vencidos_hasta  ";


                $rs = $this->db->Execute($sql);

                $this->id_clasificacion_puntualidad = $rs->fields[0];
                $this->clasificacion_puntualidad    = $rs->fields[1];
                
                if($this->SaldoGlobalGeneral<0.01) $this->clasificacion_puntualidad = 'Crédito Saldado';



                $sql="  SELECT COUNT(*) FROM creditos_castigados WHERE ID_Fact_Cliente='".$this->id_factura."' and creditos_castigados.fecha_castigo <= '".$this->fecha_corte."' ";
                //debug($sql);
                $rs = $this->db->Execute($sql);
                if($rs->fields[0])
                {
                        $this->clasificacion_puntualidad = "<SPAN STYLE='font-weight: bold; color:red;'>Crédito Castigado </SPAN> ";
                
                }
                





}

//----------------------------------------------------------------------------------------------------------
//
//----------------------------------------------------------------------------------------------------------
function obtener_saldo_vencido($fecha_ini)
{


         if($this->fecha_inicio > $fecha_fin)
         {
                return(0);
         }

         $count = count($this->aplicacion);
         for($i=0; $i<$count; $i++)
         {

                           if( ($this->aplicacion[$i]['Fecha']=$fecha_ini)  )
                           {

                                           return($this->aplicacion[$i]);

                           }


         }

   return;

}

//----------------------------------------------------------------------------------------------------------


 //----------------------------------------------------------------------------------------------------------
 // * * * Publicación
 //----------------------------------------------------------------------------------------------------------

function publica( $tipo=1, $debug=false )
{

global $sys_path;
global $img_path;




$sql= " SELECT sucursales.Nombre
        FROM  sucursales, fact_cliente
        WHERE sucursales.ID_Sucursal = fact_cliente.ID_Sucursal and
              fact_cliente.num_compra = '".$this->numcompra."' ";


$rs=$this->db->Execute($sql);   
 
$this->sucursal_nombre = $rs->fields[0];




if($this->fecha_corte < $this->fecha_inicio)
                return;


$dec = 2;

//debug(" AL CIERRE : ".$this->moratorios_con_dia_extra);


?>
<SCRIPT type="text/javascript">

                function SelectItem(id)
                {


                   var node=document.getElementById(id);
                   var rng=document.body.createTextRange();
                   rng.moveToElementText(node);
                   rng.select();


                   document.execCommand('copy');
                }



                function registrallamada(numcli,idcred)
                {

                        var url="<? echo $sys_path; ?>sucursal/cobranza/procesa_llamada.php?num_cliente="+numcli+"&idcred="+idcred;
                        window.open(url,"agendar","width=700,height=600,menubar=0,toolbar=0,resizable=1,scrollbars=1,status=1");
                        return;

                }



</SCRIPT>
<?



echo "\n<CENTER ID='cuerpo' >\n";

$margen="&nbsp;&nbsp;&nbsp;";

if($this->ver_cabeceras)
{

//-------------------------------------------------------------------------------
//               DATOS DEL CLIENTE
//------------------------------------------------------------------------------
/* DATOS DEL CLIENTE*/

if($this->ver_detalle_de_datos_credito)
{

   $sql = "SELECT clientes.Num_cliente,
		  clientes.Regimen
	   FROM   clientes
	   WHERE  clientes.Num_cliente = '".$this->numcliente."'";
  $rs =  $this->db->Execute($sql);


   if(($rs->fields['Regimen'] ==  'PM') or ($rs->fields['Regimen'] ==  'PFAE'))
   {
           if($rs->fields['Regimen'] ==  'PM') 
           $regimen_fiscal = "Persona Moral";
           else
           $regimen_fiscal = "Persona Física con Actividad Empresarial";
           
           
           
	   $sql = "SELECT        
			 CONCAT(IFNULL(clientes_datos_pmoral.Razon_social,''),
			 IFNULL(CONCAT(Nombre_pfae,' ',NombreI_pfae,' ',Ap_Paterno_pfae,' ',Ap_Materno_pfae),'')) as NombreCliente,
				   clientes_datos_pmoral.Calle  AS calle,
				   clientes_datos_pmoral.Numero AS  num,
				   clientes_datos_pmoral.Colonia,
				   clientes_datos_pmoral.Poblacion,
				   clientes_datos_pmoral.Estado,
				   clientes_datos_pmoral.Telefono


				FROM
					clientes,
					clientes_datos_pmoral

				WHERE

				    clientes_datos_pmoral.Num_cliente    = clientes.Num_cliente and
				    clientes_datos_pmoral.Num_cliente      = '".$this->numcliente."' ";

        $rs =  $this->db->Execute($sql);

        echo "<TABLE WIDTH='95%' CELLPADDDING=1 CELLSPACING=1 BORDER=0   BGCOLOR='black' ID='small'>\n";

        echo "<TR>\n";
        echo "          <TH WIDTH='50%'    ALIGN='center' BGCOLOR='steelblue' COLSPAN='2'        STYLE='color:white;'> DATOS DEL CLIENTE </TH>\n";
        echo "</TR>\n";

        echo "<TR>\n";
        echo "          <TH ALIGN='right' WIDTH='20%'   BGCOLOR='steelblue'   STYLE='color:white;'>Nombre&nbsp;:&nbsp;</TH>";
        echo "          <TH ALIGN='left'  WIDTH='80%'   BGCOLOR='white'       STYLE='color:black;'>".$rs->fields['NombreCliente']."</TH>";
        echo "</TR>\n";


        echo "<TR>\n";
        echo "          <TH ALIGN='right' WIDTH='20%'   BGCOLOR='steelblue'   STYLE='color:white;'>Régimen Fiscal&nbsp;:&nbsp;</TH>";
        echo "          <TH ALIGN='left'  WIDTH='80%'   BGCOLOR='white'       STYLE='color:black;'>".$regimen_fiscal."</TH>";
        echo "</TR>\n";
        

        $_entrecalles = $rs->fields['Ecalles'].$rs->fields['EcallesII'];
        $entrecalles = (empty($_entrecalles))?(""):("<DIV><SPAN STYLE='color:blue;'>Entre : </SPAN>".$rs->fields['Ecalles']."<SPAN STYLE='color:blue;'> y </SPAN>".$rs->fields['EcallesII']."</DIV>");

        echo "<TR>\n";
        echo "          <TH ALIGN='right' WIDTH='20%'   BGCOLOR='steelblue'   STYLE='color:white;' VALIGN='TOP'>Calle y No.&nbsp;:&nbsp;</TH>";
        echo "          <TH ALIGN='left'  VALIGN='top'  WIDTH='80%'   BGCOLOR='white'       STYLE='color:black;'>".$rs->fields['calle']." ".$rs->fields['num'].$entrecalles."</TH>";

        echo "</TR>\n";

        echo "<TR>\n";
        echo "          <TH ALIGN='right' WIDTH='20%'   BGCOLOR='steelblue'   STYLE='color:white;'>Colonia&nbsp;:&nbsp;</TH>";
        echo "          <TH ALIGN='left'  WIDTH='80%'   BGCOLOR='white'       STYLE='color:black;'>".$rs->fields['Colonia']."</TH>";

        echo "</TR>\n";

        echo "<TR>\n";
        echo "          <TH ALIGN='right' WIDTH='20%'   BGCOLOR='steelblue'  STYLE='color:white;'>Municipio&nbsp;:&nbsp;</TH>";
        echo "          <TH ALIGN='left'  WIDTH='80%'   BGCOLOR='white'      STYLE='color:black;'>".$rs->fields['Poblacion'].", ".$rs->fields['Estado']."</TH>";

        echo "</TR>\n";

        echo "<TR>\n";
        echo "          <TH ALIGN='right' WIDTH='20%'   BGCOLOR='steelblue'  STYLE='color:white;'>Teléfono&nbsp;:&nbsp;</TH>";
        echo "          <TH ALIGN='left'  WIDTH='80%'   BGCOLOR='white'      STYLE='color:black;'>".$rs->fields['Telefono']." ".$rs->fields['Num_celular']."</TH>";

        echo "</TR>\n";


        echo "</TABLE>\n";

   }
   else
   {

	   $sql = "
	    SELECT  CONCAT(Nombre,' ',NombreI,' ',Ap_Paterno,' ',Ap_Materno) as NombreCliente,
		    calle,
		    num,
		    Colonia,
		    Poblacion,
		    Estado,
		    Telefono,
		    TelOf,
		    Empresa_soli,
		    Direc_empresa_soli,
		    Telefono_empresa,
		    Extension_tel,
		    Puesto,
		    Jefe_soli AS Patron,
		    clientes_datos.Ecalles,
		    clientes_datos.EcallesII,
		    IF(clientes_datos.Num_celular IS NULL,'',CONCAT(' Móvil ',clientes_datos.Num_celular)) Num_celular




		FROM
			clientes,
			clientes_datos

		WHERE

		    clientes_datos.Num_cliente    = clientes.Num_cliente and
		    clientes.Num_cliente     = '".$this->numcliente."' ";


        $rs =  $this->db->Execute($sql);

        echo "<TABLE WIDTH='95%' CELLPADDDING=1 CELLSPACING=1 BORDER=0   BGCOLOR='black' ID='small'>\n";

        echo "<TR>\n";
        echo "          <TH WIDTH='50%'    ALIGN='center' BGCOLOR='steelblue' COLSPAN='2'        STYLE='color:white;'> DATOS DEL CLIENTE </TH>\n";
        echo "          <TH WIDTH='50%'    ALIGN='center' BGCOLOR='steelblue' COLSPAN='2'        STYLE='color:white;'> DATOS LABORALES  </TH>\n";
        echo "</TR>\n";

        echo "<TR>\n";
        echo "          <TH ALIGN='right' WIDTH='20%'   BGCOLOR='steelblue'   STYLE='color:white;'>Nombre&nbsp;:&nbsp;</TH>";
        echo "          <TH ALIGN='left'  WIDTH='30%'   BGCOLOR='white'       STYLE='color:black;'>".$rs->fields['NombreCliente']."</TH>";

        echo "          <TH ALIGN='right' WIDTH='20%'   BGCOLOR='steelblue'   STYLE='color:white;'>Empresa/Lugar de trabajo&nbsp;:&nbsp;</TH>";
        echo "          <TH ALIGN='left'  WIDTH='30%'   BGCOLOR='white'       STYLE='color:black;'>".$rs->fields['Empresa_soli']."</TH>";

        echo "</TR>\n";

        $_entrecalles = $rs->fields['Ecalles'].$rs->fields['EcallesII'];
        $entrecalles = (empty($_entrecalles))?(""):("<DIV><SPAN STYLE='color:blue;'>Entre : </SPAN>".$rs->fields['Ecalles']."<SPAN STYLE='color:blue;'> y </SPAN>".$rs->fields['EcallesII']."</DIV>");

        echo "<TR>\n";
        echo "          <TH ALIGN='right' WIDTH='20%'   BGCOLOR='steelblue'   STYLE='color:white;' VALIGN='TOP'>Calle y No.&nbsp;:&nbsp;</TH>";
        echo "          <TH ALIGN='left'  VALIGN='top'  WIDTH='30%'   BGCOLOR='white'       STYLE='color:black;'>".$rs->fields['calle']." ".$rs->fields['num'].$entrecalles."</TH>";

        echo "          <TH ALIGN='right' WIDTH='20%'   BGCOLOR='steelblue'   STYLE='color:white;' VALIGN='TOP'>Dirección&nbsp;:&nbsp;</TH>";
        echo "          <TH ALIGN='left'  VALIGN='top'  WIDTH='30%'   BGCOLOR='white'       STYLE='color:black;'>".$rs->fields['Direc_empresa_soli']."</TH>";
        echo "</TR>\n";

        echo "<TR>\n";
        echo "          <TH ALIGN='right' WIDTH='20%'   BGCOLOR='steelblue'   STYLE='color:white;'>Colonia&nbsp;:&nbsp;</TH>";
        echo "          <TH ALIGN='left'  WIDTH='30%'   BGCOLOR='white'       STYLE='color:black;'>".$rs->fields['Colonia']."</TH>";

        echo "          <TH ALIGN='right' WIDTH='20%'   BGCOLOR='steelblue'   STYLE='color:white;'>Puesto&nbsp;:&nbsp;</TH>";
        echo "          <TH ALIGN='left'  WIDTH='30%'   BGCOLOR='white'       STYLE='color:black;'>".$rs->fields['Puesto']."</TH>";
        echo "</TR>\n";

        echo "<TR>\n";
        echo "          <TH ALIGN='right' WIDTH='20%'   BGCOLOR='steelblue'  STYLE='color:white;'>Municipio&nbsp;:&nbsp;</TH>";
        echo "          <TH ALIGN='left'  WIDTH='30%'   BGCOLOR='white'      STYLE='color:black;'>".$rs->fields['Poblacion'].", ".$rs->fields['Estado']."</TH>";

        echo "          <TH ALIGN='right' WIDTH='20%'   BGCOLOR='steelblue'  STYLE='color:white;'>Jefe inmediato&nbsp;:&nbsp;</TH>";
        echo "          <TH ALIGN='left'  WIDTH='30%'   BGCOLOR='white'      STYLE='color:black;'>".$rs->fields['Patron']."</TH>";
        echo "</TR>\n";

        echo "<TR>\n";
        echo "          <TH ALIGN='right' WIDTH='20%'   BGCOLOR='steelblue'  STYLE='color:white;'>Tel Casa&nbsp;:&nbsp;</TH>";
        echo "          <TH ALIGN='left'  WIDTH='30%'   BGCOLOR='white'      STYLE='color:black;'>".$rs->fields['Telefono']." ".$rs->fields['Num_celular']."</TH>";

        echo "          <TH ALIGN='right' WIDTH='20%'   BGCOLOR='steelblue'  STYLE='color:white;'>Teléfono empresa&nbsp;:&nbsp;</TH>";
        echo "          <TH ALIGN='left'  WIDTH='30%'   BGCOLOR='white'      STYLE='color:black;'>".$rs->fields['Telefono_empresa']."</TH>";
        echo "</TR>\n";

        echo "<TR>\n";
        echo "          <TH ALIGN='right' WIDTH='20%'     BGCOLOR='steelblue'  STYLE='color:white;'>Tel Contacto&nbsp;:&nbsp;</TH>";
        echo "          <TH ALIGN='left'  WIDTH='30%'     BGCOLOR='white'      STYLE='color:black;'>".$rs->fields['TelOf']."</TH>";

        echo "          <TH ALIGN='right' WIDTH='20%'     BGCOLOR='steelblue'  STYLE='color:white;'>Extensión&nbsp;:&nbsp;</TH>";
        echo "          <TH ALIGN='left'  WIDTH='30%'     BGCOLOR='white'      STYLE='color:black;'>".$rs->fields['Extension_tel']."</TH>";
        echo "</TR>\n";

        echo "</TABLE>\n";









	}

        echo "<BR><BR>\n";

}


//------------------------------------------------------------------------------
//                DATOS DEL CREDITO
//------------------------------------------------------------------------------
/*DATOS DEL CREDITO*/
if($this->ver_detalle_de_datos_del_credito)
{
global $DOCUMENT_ROOT;
global $sys_path;

/**/
        $cobranza =$DOCUMENT_ROOT.$sys_path."sucursal/cobranza/cobranza_lib.php";
        if(file_exists($cobranza))
        {
                require_once($cobranza);
        }
        
        if(function_exists("getClasificacion"))
        {
                $clasificacion_cobranza = getClasificacion($this->id_factura);

        }
        
        echo "<TABLE WIDTH='95%' CELLPADDDING=1 CELLSPACING=1 BORDER=0   BGCOLOR='black' ID='small'>\n";

        echo "<TR>\n";
        echo "          <TH  ALIGN='center'  COLSPAN='4' BGCOLOR='steelblue' STYLE='color:white;'> DATOS DEL CREDITO  ".str_repeat("&nbsp;",10);
        echo " NUM CLIENTE :&nbsp;".$this->numcliente.str_repeat("&nbsp;",10);
        echo " ID CREDITO  :&nbsp;".$this->idfactura.str_repeat("&nbsp;",10);




       $sql="SELECT ID_Credito_pisa FROM compras WHERE Num_compra='".$this->numcompra."' ";
       $rs=$this->db->Execute($sql);      
       if(!empty($rs->fields[0]))
       {      
         echo " REF. EXTERNA  :&nbsp;".$rs->fields[0].str_repeat("&nbsp;",10);
      
       }

/*        
        
        switch($this->id_tipocredito)
        {
                case 1 : echo " CREDITO INDIVIDUAL "; break;
                
                case 2 : echo " CREDITO SOLIDARIO "; break;
        }
        
       

        $sql = "SELECT    promotores.Nombre AS Promotor
                FROM      promotores, promo_ventas, fact_cliente
                WHERE 
                          promotores.Num_promo    = promo_ventas.ID_Promo   and
                          fact_cliente.num_compra = promo_ventas.Num_compra and                  
                          fact_cliente.num_compra = '".$this->numcompra."' ";
                          
                          
                          
                          
        $rs=$this->db->Execute($sql);   
        


       $promotor = ucwords(strtolower($rs->fields['Promotor'])); 
 */ 

/*
        switch($this->id_tipocredito)
        {
                case 1 : echo " CREDITO INDIVIDUAL "; 
                


                        $sql = "SELECT    promotores.Nombre AS Promotor
                                FROM      promotores, promo_ventas, fact_cliente
                                WHERE 
                                          promotores.Num_promo    = promo_ventas.ID_Promo   and
                                          fact_cliente.num_compra = promo_ventas.Num_compra and                  
                                          fact_cliente.num_compra = '".$this->numcompra."' ";
                
                        $rs=$this->db->Execute($sql);   



                        $promotor = ucwords(strtolower($rs->fields['Promotor'])); 
                
                
                
                
                break;
                



                case 2 : echo " CREDITO SOLIDARIO ".str_repeat("&nbsp;",10);
                

                        $sql = "SELECT      CONCAT(grupo_solidario.Nombre,' - CICLO : ',grupo_solidario_integrantes.Ciclo_gpo) AS GrupoSolidario,
                                            promotores.Nombre AS Promotor            
                                
                                FROM        grupo_solidario,
                                            grupo_solidario_integrantes
                               
                                LEFT JOIN       grupo_solidario_promotor  ON grupo_solidario_promotor.ID_grupo_soli = grupo_solidario_integrantes.ID_grupo_soli and
                                                                             grupo_solidario_promotor.Ciclo_gpo     = grupo_solidario_integrantes.Ciclo_gpo
                                
                                LEFT JOIN   promotores                    ON promotores.Num_promo =  grupo_solidario_promotor.ID_Promotor                           

                                WHERE       grupo_solidario_integrantes.id_factura =  '".$this->id_factura."' 
                                        and grupo_solidario_integrantes.ID_grupo_soli = grupo_solidario.ID_grupo_soli   ";             
                
                
                          $rs=$this->db->Execute($sql);   


                         echo " GRUPO : ". strtoupper($rs->fields['GrupoSolidario']);

                         $promotor = ucwords(strtolower($rs->fields['Promotor'])); 
              
                
                break;
        }
*/

        if($this->id_tipocredito == 2)
        {


			echo " CREDITO SOLIDARIO ".str_repeat("&nbsp;",10);
                

                        $sql = "SELECT      CONCAT(grupo_solidario.Nombre,' - CICLO : ',grupo_solidario_integrantes.Ciclo_gpo) AS GrupoSolidario,
                                            promotores.Nombre AS Promotor            
                                
                                FROM        grupo_solidario,
                                            grupo_solidario_integrantes
                               
                                LEFT JOIN       grupo_solidario_promotor  ON grupo_solidario_promotor.ID_grupo_soli = grupo_solidario_integrantes.ID_grupo_soli and
                                                                             grupo_solidario_promotor.Ciclo_gpo     = grupo_solidario_integrantes.Ciclo_gpo
                                
                                LEFT JOIN   promotores                    ON promotores.Num_promo =  grupo_solidario_promotor.ID_Promotor                           

                                WHERE       grupo_solidario_integrantes.id_factura =  '".$this->id_factura."' 
                                        and grupo_solidario_integrantes.ID_grupo_soli = grupo_solidario.ID_grupo_soli   ";             
                
                
                          $rs=$this->db->Execute($sql);   


                         echo " GRUPO : ". strtoupper($rs->fields['GrupoSolidario']);

                         $promotor = ucwords(strtolower($rs->fields['Promotor'])); 
	}
	else
	{

                
		$sql = "SELECT cat_tipo_credito.Descripcion
			FROM cat_tipo_credito
			WHERE cat_tipo_credito.ID_Tipocredito = '".$this->id_tipocredito."'  ";
		$rs=$this->db->Execute($sql); 
		echo " ".strtoupper($rs->fields[0]); 

                        $sql = "SELECT    promotores.Nombre AS Promotor
                                FROM      promotores, promo_ventas, fact_cliente
                                WHERE 
                                          promotores.Num_promo    = promo_ventas.ID_Promo   and
                                          fact_cliente.num_compra = promo_ventas.Num_compra and                  
                                          fact_cliente.num_compra = '".$this->numcompra."' ";
                
                        $rs=$this->db->Execute($sql);   



                        $promotor = ucwords(strtolower($rs->fields['Promotor'])); 
                

        }


 
 
        $sql = "SELECT CONCAT(usuarios.Nombre,' ',usuarios.AP_Paterno,' ',usuarios.AP_Materno) AS Gestor
                FROM  campania_usuario_cliente, usuarios
                WHERE campania_usuario_cliente.ID_User = usuarios.ID_User and
                      campania_usuario_cliente.ID_Factura = '".$this->id_factura."' ";
 
 
        $rs=$this->db->Execute($sql);   
        


       $gestor = ucwords(strtolower($rs->fields['Gestor']));
 
 
 
 
        echo "</TH>\n";
        echo "</TR>\n";


        echo "<TR BGCOLOR='white'  ALIGN='left'><Th BGCOLOR='lightsteelblue' WIDTH='15%' nowrap>".$margen."Producto financiero :         </Th><TD WIDTH='35%'nowrap>".$margen. $this->productofinanciero."              </TD>";
        echo "                                  <Th BGCOLOR='lightsteelblue' WIDTH='15%' nowrap>".$margen."Días vencidos :               </Th><TD ALIGN='center' WIDTH='35%'nowrap>".number_format($this->dias_mora,0)."</TD></TR>";


        if($this->SaldoGlobalGeneral<0.01)
        {
        
        
                $status = "Crédito Saldado";
        
        }
        else
        {
                $status = "Crédito con saldo";
                
                
               $sql= "  SELECT COUNT(*)
                        FROM caja_credito_saldada
                        WHERE id_factura = '".$this->id_factura."' ";
               
               $rs = $this->db->Execute($sql); 
               if($rs->fields[0] >= 1)
               {
                $status = "Crédito Saldado (Se saldará al cierre)";
               }
                
                
        
        }
        
        
        echo "<TR  BGCOLOR='white' ALIGN='left'><TH BGCOLOR='lightsteelblue'>".$margen."Plazo                :   </TH><TD ALIGN='center'>".$this->plazo."  ".$this->tipovencimiento;

        if($this->numcargos_totales != $this->plazo)
        {
         echo "&nbsp;<sup>*</sup>(Crédito vencido anticipadamente en ".$this->numcargos_totales." cuotas)";
        }



        echo "   </TD>";
        echo "                                  <TH BGCOLOR='lightsteelblue'>".$margen."Status                           : </TH><TD ALIGN='center'>".$status."</TD></TR>";

        echo "<TR  BGCOLOR='white' ALIGN='left'><TH BGCOLOR='lightsteelblue'>".$margen."Sucursal                         : </TH><TD ALIGN='left' >".$margen." ".$this->sucursal_nombre."</TD>
                                                <TH BGCOLOR='lightsteelblue'>".$margen."Status (MOP)                     : </TH><TD ALIGN='center'>".$this->clasificacion_puntualidad."</TD></TR>";



        echo "<TR  BGCOLOR='white' ALIGN='left'><TH BGCOLOR='lightsteelblue'>".$margen."Capital                          : </TH><TD ALIGN='right' >".number_format($this->capital,$dec) ."         </TD>
                                                <TH BGCOLOR='lightsteelblue'>".$margen."Clasificación                    : </TH><TD ALIGN='center' >".$margen.$clasificacion_cobranza."</TD></TR>";

        $this->calificacion_mora_promedio();
        echo "<TR  BGCOLOR='white' ALIGN='left'><TH BGCOLOR='lightsteelblue'>".$margen."Fecha de inicio                  : </TH><TD ALIGN='center'>".ffecha($this->fecha_inicio) ."              </TD>
                                                <TH BGCOLOR='lightsteelblue'>".$margen."Calificación                     : </TH><TD ALIGN='center' >".number_format($this->suma_mora_promedio,2)." dias/cuota saldada </TD></TR>";


        echo "<TR  BGCOLOR='white' ALIGN='left'><TH BGCOLOR='lightsteelblue'>".$margen."Fecha de termino                 : </TH><TD ALIGN='center'>".ffecha($this->ultimo_vencimiento) ."                </TD>
                                                <TH BGCOLOR='lightsteelblue'>".$margen."Dias máximos de mora             : </TH><Th ALIGN='center' >".$this->mora_maxima."</Th></TR>";


        echo "<TR  BGCOLOR='white' ALIGN='left'><TH BGCOLOR='lightsteelblue'>".$margen."Valor Cuota                      : </TH><TD ALIGN='right'>".number_format($this->renta,2) ."             </TD>
                                                <TH BGCOLOR='lightsteelblue'>".$margen."Saldo para estar al corriente    : </TH><Th ALIGN='right'>".number_format(ceil($this->saldo_vencido)     ,2)."</Th>";
        echo "</TR>";

        echo "<TR  BGCOLOR='white' ALIGN='left'><TH BGCOLOR='lightsteelblue'>".$margen."Monto apertura                   : </TH><TD ALIGN='right'>".number_format($this->monto_apertura ,2  ) ."                  </TD>
                                                <TH BGCOLOR='lightsteelblue'>".$margen."Adeudo total                     : </TH><Th ALIGN='right'>".number_format($this->adeudo_total ,2)."</Th>";
        echo "</TR>";

        $saldo_para_liquidar_hoy = ($this->saldo_para_liquidar_hoy + $this->SaldoFavorPendiente);
        $saldo_para_liquidar_hoy = ($saldo_para_liquidar_hoy<0.01)?(0):($saldo_para_liquidar_hoy);

        echo "<TR  BGCOLOR='white' ALIGN='left'><TH BGCOLOR='lightsteelblue'>".$margen."Promotor                         : </TH><TD ALIGN='left'  >".$margen." ".$promotor ."                  </TD>
                                                <TH BGCOLOR='lightsteelblue'>".$margen."Saldo para liquidar hoy          : </TH><Th ALIGN='right' >".number_format( $saldo_para_liquidar_hoy     ,2)."</Th>";


        $_renta = ($this->SaldoCapitalPorVencer >= 0.01)?($this->renta):(0);


        echo "<TR  BGCOLOR='white' ALIGN='left'><TH BGCOLOR='lightsteelblue'>".$margen."Gestor Cobranza                  : </TH><TD ALIGN='left'  >".$margen." ".$gestor."</TD>
                                                <TH BGCOLOR='lightsteelblue'>".$margen."Saldo óptimo de cobranza         : </TH><Th ALIGN='right' >" .number_format($this->SaldoOptimoCobranza    ,2)."</Th>";    //.number_format(($this->saldo_vencido + $_renta )    ,2)."</Th>";



//      echo "<TR  BGCOLOR='white' ALIGN='left'><TH>".$margen."Fecha de apertura :        </TH><TD ALIGN='center'>".ffecha($this->ultimo_apertura) ."                   </TD>
//                                              <TH>".$margen."Prelación        :      </TH><Th ALIGN='center' >".$this->prelacion."</Th></TR>";




	$sql = "SELECT  convenios.ID_Convenio, 
			IF(factura_cliente_liquidacion.ID_Factura IS NOT NULL,'Saldado',(					  
			   IF(convenios.Fecha_final < '".$this->fecha_corte."', 
			     IF((convenios.Monto_Pagar - SUM(pagos.Monto))<=0, 'Cumplido','Vencido'), 
			     IF((convenios.Monto_Pagar -     SUM(pagos.Monto))<=0, 'Cumplido','Vigente')))) AS StatusConvenio
		
		
		
		FROM fact_cliente

			INNER JOIN convenios ON fact_cliente.id_factura = convenios.ID_Factura
			
			
			LEFT JOIN pagos ON pagos.Num_compra = fact_cliente.num_compra and pagos.activo='S' and pagos.Fecha BETWEEN convenios.Fecha_inicio and convenios.Fecha_final


			LEFT JOIN convenios_extension  USE INDEX (ID_Convenio) ON convenios_extension.ID_Convenio_Ant = convenios.ID_Convenio
			LEFT JOIN convenios_extension AS cnv                   ON cnv.ID_Convenio_NVO = convenios.ID_Convenio

			LEFT JOIN factura_cliente_liquidacion   ON factura_cliente_liquidacion.ID_Factura = fact_cliente.ID_Factura and 
								   factura_cliente_liquidacion.Fecha <= '".$this->fecha_corte."'


		WHERE fact_cliente.id_factura = '".$this->id_factura."'        

		GROUP BY convenios.ID_Convenio 
		
		ORDER BY convenios.Fecha_inicio DESC ";
		
		//debug($sql);
		//die();

		$rs = $this->db->Execute($sql);		
		

                        $textcolor = "black";
                        switch($rs->fields['StatusConvenio'])
                        {
                                case ('Saldado' ): $textcolor='blue'; break;
                                case ('Cumplido'): $textcolor='blue'; break;
                                case ('Vencido' ): $textcolor='red'; break;
                                case ('Vigente' ): $textcolor='green'; break;
                                default : $textcolor='black';                         
                        }
                        
                        
                                             
                       if(  empty($rs->fields['StatusConvenio'] ) )
                       $_statusconvenio.= "<SPAN STYLE='color:gray;'>N/A</SPAN>";
                       else                    
                       $_statusconvenio.= "<SPAN STYLE='color:".$textcolor.";'>".$rs->fields['StatusConvenio']."</SPAN>";



	$sql = "SELECT credito_empresa_convenio.id_factura
		FROM fact_cliente 
		LEFT JOIN credito_empresa_convenio ON fact_cliente.id_factura = credito_empresa_convenio.id_factura
		WHERE fact_cliente.id_factura = '".$this->id_factura."' ";

	$rs = $this->db->Execute($sql);	
	
	$_statusnomina.= "";
	if(!empty($rs->fields['id_factura']) )
	{
	   $_statusnomina = "Activo";	
	}
	else
	{
	
		$sql = "SELECT credito_empresa_convenio_cancelacion.id_factura
			FROM fact_cliente 
			LEFT JOIN credito_empresa_convenio_cancelacion ON fact_cliente.id_factura = credito_empresa_convenio_cancelacion.id_factura
			WHERE fact_cliente.id_factura = '".$this->id_factura."' ";	
	
		 $rs = $this->db->Execute($sql);	
	
		if(!empty($rs->fields['id_factura']) )		   $_statusnomina = "Baja";
	
	}


         $textcolor = "black";
         switch($_statusnomina)
         {
                 case ('Activo'): $textcolor='blue'; break;
                 case ('Baja' ): $textcolor='red'; break;
                 default : $textcolor='black';                         
         }
         
         
                              
        if(  empty($_statusnomina) )
        $_status_nomina = "<SPAN STYLE='color:gray;'>N/A</SPAN>";
        else                    
        $_status_nomina = "<SPAN STYLE='color:".$textcolor.";'>".$_statusnomina."</SPAN>";














      echo "<TR  BGCOLOR='white' ALIGN='left'><TH BGCOLOR='lightsteelblue'>".$margen."Nómina empresarial :      </TH><TD ALIGN='center'>".$_status_nomina." </TD>
                                              <TH BGCOLOR='lightsteelblue'>".$margen."Convenio de pagos  :      </TH><Th ALIGN='center' >".$_statusconvenio."</Th></TR>";



        echo "</TABLE>\n";
        echo "<BR><BR>\n";





        $cuotas = $this->numcargosvencidos;
        $fin_couta = $cuotas - 10;
        $fin_couta =($fin_couta<0)?(1):($fin_couta);

        $saldo_ultimas_cuotas =  array();
        $k=0;

        for($c=$cuotas; $c>=$fin_couta; $c--)
        {

                $saldo_ultimas_cuotas[$k]['CUOTA'] = $c;
                
                // SALDO_General
                // SALDO_MOV_Individual
                
                $saldo_ultimas_cuotas[$k]['DIAS'] = $this->saldos_cuota[$c]['DiasAtrasoAcum'];
                
                $saldo_ultimas_cuotas[$k]['SALDO'] = $this->saldos_cuota[$c]['SALDO_General'];
                
                ++$k;

        }



       //debug("Próxima_cuota_interes : [".$this->proxima_cuota_interes."] + SaldoGeneralVencido : [".$this->SaldoGeneralVencido."] +  SaldoCapitalPorVencer : + [".$this->SaldoCapitalPorVencer."] SaldoComisionPorVencer + [". ($this->SaldoComisionPorVencer *( 1+ $this->iva_pcnt_comisiones)) ."]  =   <U>Saldo_para_liquidar_hoy : [".number_format($this->saldo_para_liquidar_hoy,2)."] </U>");

//verflujo();


echo "<TABLE WIDTH='95%' CELLPADDDING=1 CELLSPACING=1 BORDER=0   BGCOLOR='black' ID='small'>\n";

echo "<TR ALIGN='center' BGCOLOR='steelblue' ' STYLE='color:white;'>\n";
echo "          <TH COLSPAN='3'> Gestión de cobranza</TH>";
echo "</TR>\n";
echo "<TR ALIGN='center' BGCOLOR='white' ' STYLE='color:black;'>\n";
if($this->fecha_ultimo_abono)
        echo "          <TH>Inactividad : ".number_format(fposdias($this->fecha_corte,$this->fecha_ultimo_abono),0)."   días    </TH>\n";
else
        echo "          <TH>Inactividad : ".number_format(fposdias($this->fecha_corte,$this->fecha_inicio),0)."         días </TH>\n";

echo "<TH>";




echo "<TABLE WIDTH='100%' CELLPADDDING=0 CELLSPACING=1 BORDER=0   BGCOLOR='black' ID='small'>\n";

echo "<TR ALIGN='center' BGCOLOR='steelblue'  STYLE='color:white; FONT-SIZE:7pt;  FONT-FAMILY: Verdana,Geneva,Tahoma, Arial, Helvetica, sans-serif;'>\n";
foreach($saldo_ultimas_cuotas AS $row)
{
        echo "<TH>".$row['CUOTA']."</TH>";      
}
echo "</TR>\n";

$none=true;
echo "<TR ALIGN='center' BGCOLOR='white' STYLE='color:black; FONT-SIZE:7pt;  FONT-FAMILY: Verdana,Geneva,Tahoma, Arial, Helvetica, sans-serif;'>\n";
foreach($saldo_ultimas_cuotas AS $row)
{
        
        if($row['SALDO'] < 0.01) 
                $style=" STYLE='background-color:silver; color:gray; ' ";
        else
                $style=" STYLE='background-color:yellow; color:black; ' ";
        
        
        
        if(($none) and ($this->dias_mora == $row['DIAS']) and ($this->dias_mora>0))
        {
                
                $style=" STYLE='background-color:orange; color:black; ' ";
                $none=false;
        }
        
        
        echo "<TH ".$style." >".number_format($row['DIAS'],0)."</TH>";  
        
}
echo "</TR>\n";
echo "</TABLE>\n";










echo "</TH>\n";
echo "          <TH onClick='registrallamada(".$this->numcliente.",".$this->idfactura.")' > Registro de llamadas</TH>\n";
echo "</TABLE>\n";


echo "<BR><BR>\n";

}




//-------------------------------------------------------------------------------
//               SALDO EN BASE A CUOTAS    (CANCELADO POR VICTOR 20/04/2009)
//------------------------------------------------------------------------------
/* SALDO EN BASE A CUOTAS */
if($this->ver_detalle_de_saldo_base_cuotas)
{


if($this->id_convenio) 
{
        foreach($this->convenios_pago AS $convenios)
        echo "<center><U>Este crédito cuenta con un convenio de pagos válido del ".ffecha($convenios['fecha_inicio_convenio'])." al ".ffecha($convenios['fecha_final_covenio'])."</U></center><br> ";

}


echo "<TABLE WIDTH='95%' CELLPADDDING=1 CELLSPACING=1 BORDER=0   BGCOLOR='black' ID='small'>\n";

echo "<TR ALIGN='center' BGCOLOR='steelblue' ' STYLE='color:white;'>\n";
echo "          <TH COLSPAN='5'> SALDO EN BASE A CUOTAS ";

if($this->numcargos_totales != $this->plazo)
{
 echo "<SPAN>&nbsp;&nbsp;&nbsp;&nbsp;<sup>*</sup>(Crédito vencido anticipadamente en ".$this->numcargos_totales." cuotas)</SPAN>";
}


echo "</TH>\n";

echo "</TR>\n";
echo "<TR ALIGN='center' BGCOLOR='steelblue' ' STYLE='color:white;'>\n";

echo "          <TH> Cuotas contratadas         </TH>\n";
//echo "          <TH> Cuotas totales   </TH>\n";

echo "          <TH> Cuotas devengadas          </TH>\n";
echo "          <TH> Cuotas pagadas             </TH>\n";
echo "          <TH> Cuotas vencidas    </TH>\n";
echo "          <TH> SALDO A PAGAR              </TH>\n";
echo "</TR>\n";

echo "<TR ALIGN='CENTER'  BGCOLOR='white'  STYLE='color:black;'>\n";
echo "  <TH                     >".$this->plazo."</TD>";
//echo "  <TH                   >".number_format($this->numcargos_totales,0)."</TH>";

echo "  <TH                     >".number_format( $this->numcargosvencidos                      ,0)."</TH>";
echo "  <TH                     >".number_format(($this->numcargosvencidos_pagados              ),0)."</TH>";
echo "  <TH                     >".number_format(($this->numcargosvencidos_no_pagados           ),0)."</TH>";
echo "  <TH ALIGN='right'       >".number_format(($this->SaldoGeneralVencido                    ),$dec)."</TH>";

echo "</TR>\n";

echo "</TABLE>\n";


echo "<BR><BR>\n";
}








//------------------------------------------------------------------------------
//                SALDO EN BASE A MONTOS
//------------------------------------------------------------------------------


if($this->debug)
{

echo "<TABLE WIDTH='95%' CELLPADDDING=1 CELLSPACING=1 BORDER=0   BGCOLOR='black' ID='small'>\n";

echo "<TR ALIGN='center' BGCOLOR='steelblue' ' STYLE='color:white;'>\n";
echo "          <TH COLSPAN='4'> SALDO EN BASE A MONTOS </TH>\n";

echo "</TR>\n";

echo "<TR ALIGN='left' BGCOLOR='lightsteelblue' ' STYLE='color:black;'>\n";
echo "          <TH ALIGN='center' > CONCEPTO   </TH>\n";
echo "          <TH ALIGN='center' > CARGOS     </TH>\n";

echo "          <TH ALIGN='center' > TOTAL ABONOS     </TH>\n";



echo "          <TH ALIGN='center' > SALDO ACTUAL     </TH>\n";
echo "</TR>\n";




echo "<TR ALIGN='right' BGCOLOR='white' STYLE='color:black;'>\n";
echo "          <TH  ALIGN='left' >".$margen."Cuotas Devengadas                 </TH>\n";
echo "          <TD>".number_format(($this->SumaCapital +
                                    ($this->SumaInteres  + $this->SumaIVAInteres )+
                                    ($this->SumaComision + $this->SumaIVAComision))           ,$dec)."</TD>\n";


echo "          <TD  STYLE='color:blue;'>".number_format(
                                    ($this->SumaAbonoCapital  +
                                    ($this->SumaAbonoInteres  + $this->SumaAbonoIVAInteres )+
                                    ($this->SumaAbonoComision + $this->SumaAbonoIVAComision)) ,$dec)."</TD>\n";


echo "          <TD>".number_format(($this->SaldoCapital +
                                    ($this->SaldoInteres  + $this->SaldoIVAInteres )+
                                    ($this->SaldoComision + $this->SaldoInteres ))            ,$dec)."</TD>\n";
echo "</TR>\n";



















echo "<TR ALIGN='right'  BGCOLOR='white' STYLE='color:black;'>\n";
echo "          <TH  ALIGN='left'>".$margen."Interés Moratorio Generado         </TH>\n";
echo "          <TD>".number_format( $this->SumaIMB                                                      ,$dec)."</TD>\n";
echo "          <TD  STYLE='color:blue;'>".number_format(($this->SumaAbonoIMB                   )        ,$dec)."</TD>\n";
echo "          <TD>".number_format(($this->SaldoIMB),$dec)."</TD>\n";

echo "</TR>\n";



echo "<TR ALIGN='right' BGCOLOR='white' STYLE='color:black;'>\n";
echo "          <TH  ALIGN='left' >".$margen."Otros cargos              </TH>\n";
echo "          <TD>".number_format(($this->SumaOtros  + $this->SumaIVAOtros ),$dec)."</TD>\n";
echo "          <TD  STYLE='color:blue;'>".number_format(($this->SumaAbonoOtros + $this->SumaAbonoIVAOtros) ,$dec)."</TD>\n";
echo "          <TD>                     ".number_format(($this->SaldoOtros      + $this->SaldoIVAOtros   ) ,$dec)."</TD>\n";

echo "</TR>\n";



echo "<TR ALIGN='right' BGCOLOR='lightsteelblue' STYLE='color:black;'>\n";
echo "          <TH  ALIGN='left' >".$margen."Sumatoria                 </TH>\n";
echo "          <TH>".number_format(($this->SumaCapital  +
                                    ($this->SumaInteres  + $this->SumaIVAInteres )+
                                    ($this->SumaComision + $this->SumaIVAComision)+
                                     $this->SumaIMB      +
                                    ($this->SumaOtros    + $this->SumaIVAOtros ))                ,$dec)."</TH>\n";

echo "          <TH  STYLE='color:blue;'>".number_format(($this->SumaAbonoCapital  +
                                    ($this->SumaAbonoInteres  + $this->SumaAbonoIVAInteres )+
                                    ($this->SumaAbonoComision + $this->SumaAbonoIVAComision)+
                                    ($this->SumaAbonoIMB                                   )+
                                    ($this->SumaAbonoOtros    + $this->SumaAbonoIVAOtros   ))    ,$dec)."</TH>\n";

echo "          <TH>".number_format(($this->SaldoCapital +
                                    ($this->SaldoInteres + $this->SaldoIVAInteres  )+
                                    ($this->SaldoComision+ $this->SaldoIVAComision )+
                                    ($this->SaldoIMB    )+
                                    ($this->SaldoOtros   + $this->SaldoIVAOtros    ))    ,$dec)."</TH>\n";
echo "</TR>\n";

echo "</TABLE>\n";

echo "<BR><BR>\n";

}


//------------------------------------------------------------------------------
//      DETALLE DE SALDOS POR CONCEPTO
//------------------------------------------------------------------------------
/*DETALLE DE SALDOS POR CONCEPTO*/
if($this->ver_detalle_de_saldos_por_concepto)
{
echo "<TABLE WIDTH='95%' CELLPADDDING=1 CELLSPACING=1 BORDER=0   BGCOLOR='black' ID='small'>\n";

echo "<TR ALIGN='center' BGCOLOR='steelblue' ' STYLE='color:white;'>\n";
echo "          <TH COLSPAN='8'> DETALLE DE SALDOS POR CONCEPTO</TH>\n";
echo "</TR>\n";



echo "<TR ALIGN='center' BGCOLOR='lightsteelblue' STYLE='color:black;'>";
echo "  <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' WIDTH='30%'>CONCEPTO         </TH>  \n";
echo "  <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' WIDTH='10%'>CARGOS           </TH>  \n";

echo "  <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' WIDTH='10%'>ABONOS EFECTIVO  </TH>  \n";
echo "  <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' WIDTH='10%'>NOTAS CREDITO    </TH>  \n";
echo "  <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' WIDTH='10%'>RENOVACION    </TH>  \n";

echo "  <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' WIDTH='10%'>SALDO VENCIDO    </TH>  \n";
echo "  <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' WIDTH='10%'>SALDO VIGENTE    </TH>  \n";
echo "  <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' WIDTH='10%'>SALDO TOTAL      </TH>  \n";

echo "</TR>\n";

//-------------------------------------------------------------------------------------------------







$sql="	SELECT   restructura_credito.monto_capital                                               AS Capital,
		 (restructura_credito.monto_comision + restructura_credito.monto_comision_iva  ) AS Comision,
		 (restructura_credito.monto_interes  + restructura_credito.monto_interes_iva   ) AS Intereses,
		 (restructura_credito.monto_otros    + restructura_credito.monto_otros_iva     ) AS Extemporaneos,
		 (restructura_credito.monto_moratorio + restructura_credito.monto_moratorio_iva) AS Moratorios,
		 restructura_credito.total_restructurado				         AS Total

	FROM restructura_credito

	WHERE restructura_credito.id_factura_origen = '".$this->id_factura."' ";

$rs=$this->db->Execute($sql);




$Suma_Abono_Capital_Documento_Renovacion   = (-1)*$rs->fields['Capital'];
$Suma_Abono_Comision_Documento_Renovacion  = (-1)*$rs->fields['Comision'];
$Suma_Abono_Interes_Documento_Renovacion   = (-1)*$rs->fields['Intereses'];
$Suma_Abono_Otros_Documento_Renovacion     = (-1)*$rs->fields['Extemporaneos'];
$Suma_Abono_Moratorio_Documento_Renovacion = (-1)*$rs->fields['Moratorios'];
$Suma_Abono_Total_Documento_Renovacion     = (-1)*$rs->fields['Total'];










echo "<TR ALIGN='right'  BGCOLOR='white' STYLE='color:black;'>";
echo "<TH ALIGN='left'   BGCOLOR='lightsteelblue' STYLE='color:black;'>".$margen."CAPITAL                                                                 </TH>";

echo "  <TD                     >".number_format($this->SumaCapital                                                                             ,$dec)."</TD>";
//echo "  <TD STYLE='color:blue;' >".number_format($this->SumaAbonoCapital                                                                        ,$dec)."</TD>";
echo "  <TD STYLE='color:blue;' >".number_format($this->Suma_Abono_Capital_Efectivo                                                               ,$dec)."</TD>";

$Notas_Capital = $this->Suma_Abono_Capital_Documento - $Suma_Abono_Capital_Documento_Renovacion;
$Notas_Capital = (($Notas_Capital >0) or (abs($Notas_Capital)<0.01))?(0):($Notas_Capital);
echo "  <TD STYLE='color:blue;' >".number_format($Notas_Capital                                                             ,$dec)."</TD>";
echo "  <TD STYLE='color:blue;' >".number_format($Suma_Abono_Capital_Documento_Renovacion                                   ,$dec)."</TD>";



echo "  <TD                     >".number_format($this->SaldoCapital                                                                            ,$dec)."</TD>";
echo "  <TD                     >".number_format($this->SaldoCapitalPorVencer                                                                   ,$dec)."</TD>";
echo "  <TD                     >".number_format($this->SaldoGlobalCapital                                                                      ,$dec)."</TD>";
echo "</TR>\n";
//-------------------------------------------------------------------------------------------------
echo "<TR ALIGN='right'  BGCOLOR='ghostwhite' STYLE='color:black;'>";
echo "<TH ALIGN='left'   BGCOLOR='lightsteelblue' STYLE='color:black;'>".$margen."INTERÉS NOMINAL                                                         </TH>";

echo "  <TD                     >".number_format(($this->SumaInteres            +$this->SumaIVAInteres          )                             ,$dec)."</TD>";
//echo "  <TD STYLE='color:blue;' >".number_format(($this->SumaAbonoInteres       +$this->SumaAbonoIVAInteres     )                             ,$dec)."</TD>";
echo "  <TD STYLE='color:blue;' >".number_format(($this->Suma_Abono_Interes_Efectivo    + $this->Suma_Abono_IVA_Interes_Efectivo   )                             ,$dec)."</TD>";


$Notas_Interes = ($this->Suma_Abono_Interes_Documento   + $this->Suma_Abono_IVA_Interes_Documento  ) - $Suma_Abono_Interes_Documento_Renovacion;
$Notas_Interes = (($Notas_Interes >0) or (abs($Notas_Interes)<0.01))?(0):($Notas_Interes);
echo "  <TD STYLE='color:blue;' >".number_format($Notas_Interes                            ,$dec)."</TD>";
echo "  <TD STYLE='color:blue;' >".number_format($Suma_Abono_Interes_Documento_Renovacion ,$dec)."</TD>";




echo "  <TD                     >".number_format(($this->SaldoInteres           +$this->SaldoIVAInteres         )                             ,$dec)."</TD>";
echo "  <TD                     >".number_format(($this->SaldoInteresPorVencer  +$this->Saldo_IVA_InteresPorVencer)                           ,$dec)."</TD>";
echo "  <TD                     >".number_format((
                                ($this->SaldoInteres + $this->SaldoInteresPorVencer) +   ($this->SaldoIVAInteres + $this->Saldo_IVA_InteresPorVencer))       ,$dec)."</TD>";
echo "</TR>\n";
//-------------------------------------------------------------------------------------------------

echo "<TR ALIGN='right'  BGCOLOR='ghostwhite' STYLE='color:black;'>";
echo "<TH ALIGN='left'   BGCOLOR='lightsteelblue' STYLE='color:black;'>".$margen."COMISIÓN POR APERTURA                                                   </TH>";

echo "  <TD                     >".number_format(($this->SumaComision            + $this->SumaIVAComision           )                               ,$dec)."</TD>";
//echo "  <TD STYLE='color:blue;' >".number_format(($this->SumaAbonoComision       + $this->SumaAbonoIVAComision      )                             ,$dec)."</TD>";
echo "  <TD STYLE='color:blue;' >".number_format(($this->Suma_Abono_Comision_Efectivo   + $this->Suma_Abono_IVA_Comision_Efectivo  )                ,$dec)."</TD>";


$Notas_Comision = ($this->Suma_Abono_Comision_Documento  + $this->Suma_Abono_IVA_Comision_Documento ) - $Suma_Abono_Comision_Documento_Renovacion;
$Notas_Comision = (($Notas_Comision >0) or (abs($Notas_Comision)<0.01))?(0):($Notas_Comision);

echo "  <TD STYLE='color:blue;' >".number_format($Notas_Comision                               ,$dec)."</TD>";
echo "  <TD STYLE='color:blue;' >".number_format(($Suma_Abono_Comision_Documento_Renovacion  ) ,$dec)."</TD>";




echo "  <TD                     >".number_format(($this->SaldoComision           + $this->SaldoIVAComision          )                               ,$dec)."</TD>";
echo "  <TD                     >".number_format(($this->SaldoComisionPorVencer  + $this->Saldo_IVA_ComisionPorVencer )                              ,$dec)."</TD>";
echo "  <TD                     >".number_format((($this->SaldoComisionPorVencer  + $this->SaldoComision)+($this->Saldo_IVA_ComisionPorVencer + $this->SaldoIVAComision))       ,$dec)."</TD>";
echo "</TR>\n";


//-------------------------------------------------------------------------------------------------

echo "<TR ALIGN='right'  BGCOLOR='white' STYLE='color:black;'>";
echo "<TH ALIGN='left' BGCOLOR='lightsteelblue' STYLE='color:black;'>".$margen."INTERÉS MORATORIO                                                       </TH>";
echo "  <TD>".number_format(($this->SumaIMB)                                                                                                    ,$dec)."</TD>";
//echo "  <TD STYLE='color:blue;'>".number_format($this->SumaAbonoIMB                                                                             ,$dec)."</TD>";
echo "  <TD STYLE='color:blue;'>".number_format(($this->Suma_Abono_Moratorio_Efectivo  + $this->Suma_Abono_IVA_Moratorio_Efectivo )           ,$dec)."</TD>";


$Notas_Moratorio = ($this->Suma_Abono_Moratorio_Documento + $this->Suma_Abono_IVA_Moratorio_Documento) - $Suma_Abono_Moratorio_Documento_Renovacion;
$Notas_Moratorio = (($Notas_Moratorio >0) or (abs($Notas_Moratorio)<0.01))?(0):($Notas_Moratorio);


echo "  <TD STYLE='color:blue;'>".number_format($Notas_Moratorio           ,$dec)."</TD>";
echo "  <TD STYLE='color:blue;'>".number_format(($Suma_Abono_Moratorio_Documento_Renovacion )           ,$dec)."</TD>";




echo "  <TD>".number_format($this->SaldoIMB                                                                                                     ,$dec)."</TD>";
echo "  <TD> ---                                        </TD>";
echo "  <TD>".number_format($this->SaldoIMB                                                                                                      ,$dec)."</TD>";
echo "</TR>\n";


//-------------------------------------------------------------------------------------------------


echo "<TR ALIGN='right'  BGCOLOR='white' STYLE='color:black;'>";
echo "<TH ALIGN='left'   BGCOLOR='lightsteelblue' STYLE='color:black;'>".$margen."EXTEMPORANEOS         </TH>";

echo "  <TD                     >".number_format(($this->SumaOtros      + $this->SumaIVAOtros           ),$dec)."</TD>";
//echo "  <TD STYLE='color:blue;' >".number_format(($this->SumaAbonoOtros + $this->SumaAbonoIVAOtros      ),$dec)."</TD>";

echo "  <TD STYLE='color:blue;' >".number_format(($this->Suma_Abono_Otros_Efectivo      + $this->Suma_Abono_IVA_Otros_Efectivo     ),$dec)."</TD>";

$Notas_Otros = ($this->Suma_Abono_Otros_Documento     + $this->Suma_Abono_IVA_Otros_Documento    ) - $Suma_Abono_Otros_Documento_Renovacion;
$Notas_Otros = (($Notas_Otros >0) or (abs($Notas_Otros)<0.01))?(0):($Notas_Otros);


echo "  <TD STYLE='color:blue;' >".number_format($Notas_Otros,$dec)."</TD>";
echo "  <TD STYLE='color:blue;' >".number_format(($Suma_Abono_Otros_Documento_Renovacion       ),$dec)."</TD>";




echo "  <TD                     >".number_format(($this->SaldoOtros     + $this->SaldoIVAOtros          ),$dec)."</TD>";
echo "  <TD                     > -- </TD>";
echo "  <TD                     >".number_format(($this->SaldoOtros     + $this->SaldoIVAOtros          ),$dec)."</TD>";
echo "</TR>\n";

//-------------------------------------------------------------------------------------------------



echo "<TR ALIGN='right'  BGCOLOR='ghostwhite' STYLE='color:black;'>";
echo "<TH ALIGN='left'   BGCOLOR='lightsteelblue' STYLE='color:black;'>".$margen."SEGURO VIDA         </TH>";

echo "  <TD                     >".number_format(($this->SumaSegV            + $this->SumaIVASegV           )                               ,$dec)."</TD>";
//echo "  <TD STYLE='color:blue;' >".number_format(($this->SumaAbonoSegV       + $this->SumaAbonoIVASegV      )                               ,$dec)."</TD>";
echo "  <TD STYLE='color:blue;' >".number_format(($this->Suma_Abono_SegV_Efectivo       + $this->Suma_Abono_IVA_SegV_Efectivo       )                               ,$dec)."</TD>";
echo "  <TD STYLE='color:blue;' >".number_format(($this->Suma_Abono_SegV_Documento      + $this->Suma_Abono_IVA_SegV_Documento      )                               ,$dec)."</TD>";
echo "  <TD STYLE='color:blue;' >".number_format(0 ,$dec)."</TD>";


echo "  <TD                     >".number_format(($this->SaldoSegV           + $this->SaldoIVASegV          )                               ,$dec)."</TD>";
echo "  <TD                     >".number_format(($this->SaldoSegVPorVencer  + $this->Saldo_IVA_SegVPorVencer )                              ,$dec)."</TD>";
echo "  <TD                    >".number_format((($this->SaldoSegVPorVencer  + $this->SaldoSegV)+($this->Saldo_IVA_SegVPorVencer + $this->SaldoIVASegV))       ,$dec)."</TD>";
echo "</TR>\n";

//-------------------------------------------------------------------------------------------------


echo "<TR ALIGN='right'  BGCOLOR='white' STYLE='color:black;'>";
echo "<TH ALIGN='left'   BGCOLOR='lightsteelblue' STYLE='color:black;'>".$margen."SEGURO DESEMPLEO         </TH>";

echo "  <TD                     >".number_format(($this->SumaSegD            + $this->SumaIVASegD           )                               ,$dec)."</TD>";
//echo "  <TD STYLE='color:blue;' >".number_format(($this->SumaAbonoSegD       + $this->SumaAbonoIVASegD      )                               ,$dec)."</TD>";
echo "  <TD STYLE='color:blue;' >".number_format(($this->Suma_Abono_SegD_Efectivo       + $this->Suma_Abono_IVA_SegD_Efectivo      )           ,$dec)."</TD>";
echo "  <TD STYLE='color:blue;' >".number_format(($this->Suma_Abono_SegD_Documento      + $this->Suma_Abono_IVA_SegD_Documento     )           ,$dec)."</TD>";
echo "  <TD STYLE='color:blue;' >".number_format(0 ,$dec)."</TD>";


echo "  <TD                     >".number_format(($this->SaldoSegD           + $this->SaldoIVASegD          )                               ,$dec)."</TD>";
echo "  <TD                     >".number_format(($this->SaldoSegDPorVencer  + $this->Saldo_IVA_SegDPorVencer )                              ,$dec)."</TD>";
echo "  <TD                    >".number_format((($this->SaldoSegDPorVencer  + $this->SaldoSegD)+($this->Saldo_IVA_SegDPorVencer + $this->SaldoIVASegD))       ,$dec)."</TD>";
echo "</TR>\n";

//-------------------------------------------------------------------------------------------------


echo "<TR ALIGN='right'  BGCOLOR='ghostwhite' STYLE='color:black;'>";
echo "<TH ALIGN='left'   BGCOLOR='lightsteelblue' STYLE='color:black;'>".$margen."SEGURO BIENES MATERIALES        </TH>";
echo "  <TD                     >".number_format(($this->SumaSegB            + $this->SumaIVASegB           )                               ,$dec)."</TD>";
//echo "  <TD STYLE='color:blue;' >".number_format(($this->SumaAbonoSegB       + $this->SumaAbonoIVASegB      )                               ,$dec)."</TD>";
echo "  <TD STYLE='color:blue;' >".number_format(($this->Suma_Abono_SegB_Efectivo       + $this->Suma_Abono_IVA_SegB_Efectivo       )                               ,$dec)."</TD>";
echo "  <TD STYLE='color:blue;' >".number_format(($this->Suma_Abono_SegB_Documento      + $this->Suma_Abono_IVA_SegB_Documento     )                               ,$dec)."</TD>";
echo "  <TD STYLE='color:blue;' >".number_format(0 ,$dec)."</TD>";


echo "  <TD                     >".number_format(($this->SaldoSegB           + $this->SaldoIVASegB          )                               ,$dec)."</TD>";
echo "  <TD                     >".number_format(($this->SaldoSegBPorVencer  + $this->Saldo_IVA_SegBPorVencer )                             ,$dec)."</TD>";
echo "  <TD                    >".number_format((($this->SaldoSegBPorVencer  + $this->SaldoSegB)+($this->Saldo_IVA_SegBPorVencer + $this->SaldoIVASegB))       ,$dec)."</TD>";

echo "</TR>\n";

//-------------------------------------------------------------------------------------------------


echo "<TR ALIGN='right'  BGCOLOR='white' STYLE='color:black;'>";

echo "<TH ALIGN='left' BGCOLOR='lightsteelblue' STYLE='color:black;'>".$margen."SALDO A FAVOR                                   </TH>";
echo "  <TD> -- </TD>";
echo "  <TD STYLE='color:blue;'>".number_format($this->SaldoFavorPendiente,2)."</TD>";
echo "  <TD> -- </TD>";
echo "  <TD> -- </TD>";
echo "  <TD> -- </TD>";
echo "  <TD> -- </TD>";
echo "  <TD STYLE='color:blue;'>".number_format($this->SaldoFavorPendiente,2)."</TD>";

echo "</TR>\n";
//-------------------------------------------------------------------------------------------------







echo "<TR ALIGN='right'  STYLE='color:black; background-color:silver;'>";
echo "<TH ALIGN='left'   BGCOLOR='lightsteelblue' STYLE='color:black;'>".$margen."SUMATORIA                                     </TH>";




//echo "<Th  ALIGN='right' STYLE='color:black;'>".number_format(($this->SumaCargos),$dec)."</Th>\n";

$_SumaCargos =  number_format($this->SumaCapital,2,".","")+
                number_format($this->SumaIMB,2,".",""    )+
                number_format(($this->SumaInteres       + $this->SumaIVAInteres  ),2,".","")+
                number_format(($this->SumaComision      + $this->SumaIVAComision ),2,".","")+
                number_format(($this->SumaOtros         + $this->SumaIVAOtros    ),2,".","")+

                number_format(($this->SumaSegV          + $this->SumaIVASegV ),2,".","")+
                number_format(($this->SumaSegD          + $this->SumaIVASegD ),2,".","")+
                number_format(($this->SumaSegB          + $this->SumaIVASegB ),2,".","");









echo "<Th  ALIGN='right' STYLE='color:black;'>".number_format($_SumaCargos,$dec)."</Th>\n";









$_SumaAbonos =  number_format($this->SumaAbonoCapital,2,".","")+
                number_format(($this->SumaAbonoInteres         + $this->SumaAbonoIVAInteres   ),2,".","")+
                number_format($this->SumaAbonoIMB,2,".","")+
                number_format(($this->SumaAbonoComision        + $this->SumaAbonoIVAComision),2,".","")+
                number_format(($this->SumaAbonoOtros           + $this->SumaAbonoIVAOtros   ),2,".","")+


                number_format(($this->SumaAbonoSegV           + $this->SumaAbonoIVASegV   ),2,".","")+
                number_format(($this->SumaAbonoSegD           + $this->SumaAbonoIVASegD   ),2,".","")+
                number_format(($this->SumaAbonoSegB           + $this->SumaAbonoIVASegB   ),2,".","");




                number_format($this->SaldoFavorPendiente,2,".","");


//echo "<Th  ALIGN='right' STYLE='color:blue;'>".number_format($_SumaAbonos,$dec)."</Th>\n";
        
echo "<Th  ALIGN='right' STYLE='color:blue;'>".number_format($this->Suma_Abonos_Total_Efectivo , 2)."</Th>\n";

$Notas_Total= $this->Suma_Abonos_Total_Documento - $Suma_Abono_Total_Documento_Renovacion ;
$Notas_Total = (($Notas_Total >0) or (abs($Notas_Total)<0.01))?(0):($Notas_Total);

echo "<Th  ALIGN='right' STYLE='color:blue;'>".number_format($Notas_Total, 2)."</Th>\n";
echo "<Th  ALIGN='right' STYLE='color:blue;'>".number_format($Suma_Abono_Total_Documento_Renovacion , 2)."</Th>\n";
























/*
        $_SaldoGeneralVencido   =       number_format($this->SaldoCapital,2,".","")+
                                        number_format($this->SaldoIMB    ,2,".","")+
                                        number_format(($this->SaldoInteres      *   (1+ $this->iva_pcnt_intereses )),2,".","")+
                                        number_format(($this->SaldoComision     *   (1+ $this->iva_pcnt_comisiones)),2,".","")+
                                        number_format(($this->SaldoOtros        + $this->SaldoIVAOtros          ),2,".","");

        echo "<Th ALIGN='right' >A : ".number_format($_SaldoGeneralVencido,$dec)."</Th>\n";
*/



        echo "<Th ALIGN='right' >  ".number_format($this->SaldoGeneralVencido,$dec)."</Th>\n";


/*


        $_SaldoGeneralVigente   =       number_format($this->SaldoCapitalPorVencer,2,".","")+
                                        number_format(($this->SaldoInteresPorVencer     *   (1+ $this->iva_pcnt_intereses )),2,".","")+
                                        number_format(($this->SaldoComisionPorVencer    *   (1+ $this->iva_pcnt_comisiones)),2,".","");
        echo "<Th ALIGN='right' >".number_format($_SaldoGeneralVigente,$dec)."</Th>\n";

*/

        echo "<Th ALIGN='right' > ".number_format($this->SaldoGeneralVigente,$dec)."</Th>\n";





        $_SaldoGeneralGlobal  = number_format($this->SaldoGlobalCapital,2,".","")+
                                number_format($this->SaldoIMB,          2,".","")+      
                                number_format((($this->SaldoInteres           + $this->SaldoInteresPorVencer  ) + ($this->SaldoIVAInteres  + $this->Saldo_IVA_InteresPorVencer)),2,".","")+
                                number_format((($this->SaldoComisionPorVencer + $this->SaldoComision          ) + ($this->SaldoIVAComision + $this->Saldo_IVA_ComisionPorVencer)),2,".","")+


                                number_format((($this->SaldoSegVPorVencer + $this->SaldoSegV          ) + ($this->SaldoIVASegV + $this->Saldo_IVA_SegVPorVencer)                ),2,".","")+
                                number_format((($this->SaldoSegDPorVencer + $this->SaldoSegD          ) + ($this->SaldoIVASegD + $this->Saldo_IVA_SegDPorVencer)                ),2,".","")+
                                number_format((($this->SaldoSegBPorVencer + $this->SaldoSegB          ) + ($this->SaldoIVASegB + $this->Saldo_IVA_SegBPorVencer)                ),2,".","")+



                                number_format(($this->SaldoOtros                                                + $this->SaldoIVAOtros         ),2,".","")+
                                number_format($this->SaldoFavorPendiente,2,".","");




        echo "<Th ALIGN='right' >".number_format($_SaldoGeneralGlobal,$dec)."</Th>\n";

        //echo "<Th ALIGN='right' >".number_format(($_SaldoGeneralVigente + $_SaldoGeneralVencido),$dec)."</Th>\n";






echo "</TR>\n";


echo "</TABLE>\n";

echo "<BR><BR>\n";
}

}

//------------------------------------------------------------------------------
//      DESGLOCE DE CALCULOS
//------------------------------------------------------------------------------
if($this->ver_desglose_calculos) 
{



   $colspan=13;
   $width=1300;



   if($this->ver_desglose_cargos )
   {
           $width+=  (600+300);
         $colspan+=6+5  +5;

   }

  if($this->ver_desglose_abonos)
   {
           $width+=  700+300+100  ; //+400;
         $colspan+=7+3+1  +5;

   }


  if($this->ver_saldo_desglosado)
   {
           $width+=  700+200+200  ; //+400;
         $colspan+=7+4+1  +5;

   }


  if($this->ver_saldos_vencer)
   {
               $width+=  400      ; //+200;
         $colspan+=4   + 3;

   }
/*
  if($this->ver_saldo_general)
   {
           $width+= 400           ; //+200;
         $colspan+=6+2 +3;

   }
*/


$width .= "px ";


//  echo "<IMG SRC='".$img_path."toexel.png'  onMouseOver=\"javascript:this.style.cursor='hand';\"  onClick=\"javascript:SelectItem('detalle');\" /> \n\n";






if( !($this->ver_desglose_cargos or $this->ver_desglose_abonos or $this->ver_saldo_desglosado or $this->ver_saldos_vencer or $this->ver_saldo_general))
        $width = "95%";
else
        echo "<DIR>\n";


echo "<TABLE WIDTH='".$width."'  BORDER=0 BGCOLOR='black' CELLPADDDING=1 CELLSPACING=1 BORDER=0  STYLE='font-size: 10px; font-family:arial;' >\n";




  $tableheadgroup = "<TR ALIGN='center' BGCOLOR='silver'  STYLE='color:black;'  >   \n";
  $tableheadgroup .= "<TH ColSpan='9'   BGCOLOR='gray'    STYLE='color:white;'> Movimientos </TH> \n";
  $tableheadgroup .= "<TH ColSpan='3'   BGCOLOR='silver'  STYLE='color:black;'>  Saldo </TH> \n";
  $tableheadgroup .= "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";

if($this->ver_desglose_cargos )
{

  $tableheadgroup .= "<TH ColSpan='".(5+3+2 +5)."'   BGCOLOR='gray'   STYLE='color:white;'> Desglose de cargos.</TH> \n";
  $tableheadgroup .= "  <TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";

}

if($this->ver_desglose_abonos)
{
  $tableheadgroup .= "<TH ColSpan='".(6+3+1 +5)."'   BGCOLOR='silver' STYLE='color:black;'> Desglose de abonos.</TH> \n";
  $tableheadgroup .= "  <TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";
}

if($this->ver_saldo_desglosado)
{
  $tableheadgroup .= "<TH ColSpan='".(6+3+2 +5)."'   BGCOLOR='gray'   STYLE='color:white;'> Saldo vencido desglosado.</TH> \n";
  $tableheadgroup .= "  <TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";
}

if($this->ver_saldos_vencer)
{
  $tableheadgroup .= "<TH ColSpan='".(3 + 3)."'   BGCOLOR='silver'   STYLE='color:black;'> Saldos por devengar.</TH> \n";
  $tableheadgroup .= "  <TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";
}
/*
if($this->ver_saldo_general)
{
  $tableheadgroup .= "  <TH ColSpan='".(5+2+3)."'   BGCOLOR='gray'   STYLE='color:white;'> Saldo gobal.</TH> \n";
  $tableheadgroup .= "  <TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";
}
*/

  $tableheadgroup .= "  </TR> \n";






   $tablehead .= "  <TR  BGCOLOR='lightsteelblue'  STYLE='color:black;' >   \n";
         $tablehead .= "  <TH Width='150px;'>   Fecha Movimiento            </TH>\n";
         $tablehead .= "  <TH Width='150px;'>   Fecha Aplicación            </TH>\n";

         $tablehead .= "  <TH Width='350px;'>   Concepto                    </TH>\n";
         $tablehead .= "  <TH >   Cargos                                    </TH>\n";
         $tablehead .= "  <TH >   Abonos                                    </TH>\n";
         $tablehead .= "  <TH >   Saldo Favor Aplicado                      </TH>\n";
         $tablehead .= "  <TH >   Días vencidos                             </TH>\n";
         $tablehead .= "  <TH >   Días Acumulados                           </TH>\n";
         $tablehead .= "  <TH >   Interés moratorio                         </TH>\n";
         
         $tablehead .= "  <TH >   Cuota                                     </TH>\n";
         $tablehead .= "  <TH >   General                                   </TH>\n";
         $tablehead .= "  <TH >   A favor                                   </TH>\n";

         $tablehead .= "  <TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";


if($this->ver_desglose_cargos )
{

         $tablehead .= "  <TH>   Capital                                   </TH>\n";
     
     
         $tablehead .= "  <TH>   Int. nominal                           </TH>\n";
         $tablehead .= "  <TH>   IVA                                    </TH>\n";

         $tablehead .= "  <TH>   Int. moratorio                         </TH> \n";
         $tablehead .= "  <TH>   IVA                                    </TH>\n";

         $tablehead .= "  <TH>   Comisiones                             </TH>\n";
         $tablehead .= "  <TH>   IVA                                    </TH>\n";

         $tablehead .= "  <TH>   Extemporáneos                          </TH>\n";
         $tablehead .= "  <TH>   IVA                                    </TH>\n";


         $tablehead .= "  <TH>   Seg. Vida.                             </TH>\n";
         $tablehead .= "  <TH>   IVA                                    </TH>\n";

         $tablehead .= "  <TH>   Seg. Des.                              </TH>\n";
         $tablehead .= "  <TH>   IVA                                    </TH>\n";

         $tablehead .= "  <TH>   Seg. Bienes                            </TH>\n";
         $tablehead .= "  <TH>   IVA                                    </TH>\n";
         

//         $tablehead .= "  <TH>  Suma IVA  </TH>\n";

       $tablehead .= "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";
}

if($this->ver_desglose_abonos)
{

         $tablehead .= "  <TH>   Capital                                </TH>\n";

         $tablehead .= "  <TH>   Int. nominal                           </TH>\n";
         $tablehead .= "  <TH>   IVA                                    </TH>\n";


         $tablehead .= "  <TH>   Int. moratorio                         </TH> \n";
         $tablehead .= "  <TH>   IVA                                    </TH>\n";


         $tablehead .= "  <TH>   Comisiones                             </TH>\n";
         $tablehead .= "  <TH>   IVA                                    </TH>\n";


         $tablehead .= "  <TH>   Extemporáneos                          </TH>\n";
         $tablehead .= "  <TH>   IVA                                    </TH>\n";

         $tablehead .= "  <TH>   Seg. Vida.                             </TH>\n";
         $tablehead .= "  <TH>   IVA                                    </TH>\n";

         $tablehead .= "  <TH>   Seg. Des.                              </TH>\n";
         $tablehead .= "  <TH>   IVA                                    </TH>\n";

         $tablehead .= "  <TH>   Seg. Bienes                            </TH>\n";
         $tablehead .= "  <TH>   IVA                                    </TH>\n";

//         $tablehead .= "  <TH>   Suma IVA                               </TH>\n";

   $tablehead .= "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>\n";
}


if($this->ver_saldo_desglosado)
{

         $tablehead .= "  <TH>   Capital                                </TH>\n";

         $tablehead .= "  <TH>   Int. nominal                           </TH>\n";
         $tablehead .= "  <TH>   IVA                                    </TH>\n";


         $tablehead .= "  <TH>   Int. moratorio                         </TH> \n";
         $tablehead .= "  <TH>   IVA                                    </TH>\n";


         $tablehead .= "  <TH>   Comisiones                             </TH>\n";
         $tablehead .= "  <TH>   IVA                                    </TH>\n";


         $tablehead .= "  <TH>   Extemporáneos                          </TH>\n";
         $tablehead .= "  <TH>   IVA                                    </TH>\n";

         $tablehead .= "  <TH>   Seg. Vida.                             </TH>\n";
         $tablehead .= "  <TH>   IVA                                    </TH>\n";

         $tablehead .= "  <TH>   Seg. Des.                              </TH>\n";
         $tablehead .= "  <TH>   IVA                                    </TH>\n";

         $tablehead .= "  <TH>   Seg. Bienes                            </TH>\n";
         $tablehead .= "  <TH>   IVA                                    </TH>\n";

//         $tablehead .= "  <TH>   Suma IVA                               </TH>\n";
         $tablehead .= "  <TH>   Saldo                             </TH>\n";

   $tablehead .= "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";
}

if($this->ver_saldos_vencer)
{

         $tablehead .= "  <TH>   Capital                                   </TH>\n";
         $tablehead .= "  <TH>   Int. nominal                              </TH>\n";
         $tablehead .= "  <TH>   Comisiones                     </TH>\n";

         $tablehead .= "  <TH>   Seg. Vida.                                </TH>\n";
         $tablehead .= "  <TH>   Seg. Des.                                 </TH>\n";
         $tablehead .= "  <TH>   Seg. Bienes                               </TH>\n";


         $tablehead .= "   <TD   ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";
}
/*
if($this->ver_saldo_general)
{

         $tablehead .= "  <TH>   Capital                                          </TH>\n";
         $tablehead .= "  <TH>   Int. nominal                                  </TH>\n";
         $tablehead .= "  <TH>   Comisión                                         </TH>\n";
         $tablehead .= "  <TH>   Extemporaneos                                    </TH>\n";
         $tablehead .= "  <TH>   Moratorios                                       </TH>\n";

         $tablehead .= "  <TH>   Seg. Vida.                                </TH>\n";
         $tablehead .= "  <TH>   Seg. Des.                                 </TH>\n";
         $tablehead .= "  <TH>   Seg. Bienes                               </TH>\n";

         $tablehead .= "  <TH>   IVA                                              </TH>\n";
         $tablehead .= "  <TH>   Global                                           </TH>\n";


   $tablehead .= "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";
}
*/
    $tablehead .= "  </TR> \n";


 echo $tableheadgroup;
 echo $tablehead;

   //----------------------------------------------------------------------------------------
   // Detalle del estado de cuenta
   //----------------------------------------------------------------------------------------
 $LastSaldoGlobalCapital = 1;
 $k = 1;

 $MontosCargos =0;
 $SumaAbonoCapitalVencido    = 0;
 $SumaAbonoCapitalGlobal     = 0;
 $SumaAbonoRemanente         = 0;









 //$InteresNetoPorDevengar = ($this->monto_apertura - $this->_capital)/(1+$this->iva_pcnt_intereses);
 //$IVAPorDevengar = $InteresNetoPorDevengar * $this->iva_pcnt_intereses;
 
 $InteresNetoPorDevengar = $this->Cargos_Totales_Interes;
 $IVAPorDevengar         = $this->Cargos_Totales_IVA_Interes;



 $sf=0;
 $saldo_mov = 0;
 $dias_mora = 0;

   //----------------------------------------------------------------------------------------
   // Desgloce de cargos y abonos
   //----------------------------------------------------------------------------------------
$primer_cargo=true;


$ultima_cuota=0;
$count=0;
//
$dia[0] = "Dom";
$dia[1] = "Lun";
$dia[2] = "Mar";
$dia[3] = "Mie";
$dia[4] = "Jue";
$dia[5] = "Vie";
$dia[6] = "Sab";


$ivacolor = 'yellow';


foreach($this->aplicacion AS $row)  
if(!empty($row['Fecha']))
{
        $color=($color=='white')?('lavender'):('white');

        $es_cuota = false;
        if($row['ID_Concepto']==-3) 
                $es_cuota = true;
          

        if($row['ID_Concepto']==-3) 
        {

                if(($SALDO_General<0.01) )
                        echo "<TR  BGCOLOR='steelblue'><TD STYLE='height:2px;' COLSPAN='".$colspan."'></TD></TR>     \n";
                else
                        echo "<TR  BGCOLOR='lime'     ><TD STYLE='height:2px;' COLSPAN='".$colspan."'></TD></TR>     \n";
        }


        if(($this->id_ultima_cuota == $row['ID']) and ($row['ID_Concepto']==-3))
        {
                $ultima_cuota=1;
                
        }
        
        
        

        if((($row['ID_Concepto']==-3) ) and( $primer_cargo))
                $primer_cargo=false;



        if (($row['Tipo']=='Saldo') )
        {
                $color = 'lime';
        }
        
        if($row['Tipo']=='Abono')
        {
           if(!$row['AplicacionSaldo'])
                   $style = " STYLE='color:blue; font-weight:bold;' ";
        }
        else
          $style = ""; 
/**/    
        
        echo "<TR  BGCOLOR='".$color."' ".$style." >      \n";
        
        
        if($row['Fecha_Mov'])
        {
                list($_y,$_m,$_d) = explode("-",$row['Fecha_Mov']);
                $wdia = date("w",mktime(0,0,0,$_m,$_d,$_y));
        }
        
        $_swdia = ($row['Fecha_Mov'])?($dia[$wdia]):("");


        echo "<TD ALIGN='center' Width='100px;'>".ffecha($row['Fecha_Mov'])." ".$_swdia ."</TD>     \n";
        
        $_style=($row['Fecha_Mov'] != $row['Fecha'])?(" STYLE='font-weight:bold; color:green;' "):("");

        
        list($_y,$_m,$_d) = explode("-",$row['Fecha']);
        $_wdia1 = date("w",mktime(0,0,0,$_m,$_d,$_y));
        
        echo "<TD ALIGN='center' Width='100px;' ".$_style." >".ffecha($row['Fecha']) ." ".$dia[$_wdia1]."</TD>     \n";

        
        $sangria = ($row['Tipo']=="Abono")?("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"):("");
        
        if($row['Tipo']=="Abono")
                echo "<TH ALIGN='left'   Width='800px;' STYLE='color:blue;' > ".$sangria.$row['Descripcion']."</TH>     \n";
        else
                echo "<TD ALIGN='left'   Width='800px;'> ".$sangria.$row['Descripcion']." </TD>     \n";
        


        if($row['Tipo']=='Saldo')
        {
                echo "<TH></TH>\n";
                echo "<TH></TH>\n";
        }
        else
        if($row['Tipo']=='Abono')
        {
                echo "<TH></TH>     \n";
                if($row['Abono'] or $row['SaldoFavorAplicado'])
                        echo "<TH  ALIGN='right'   Width='100px;'  STYLE='color:blue;'>".number_format($row['Abono'],$dec)."</TH>     \n";
                else
                        echo "<TH></TH>     \n";
        }
        else
        {
                echo "<TH  ALIGN='right' Width='100px;'>".number_format($row['Cargo'],$dec)."</TH>     \n";
                echo "<TH></TH>     \n";
        }

        
        //if($row['SaldoFavorAplicado'])
        //      echo "<TH  ALIGN='right'   Width='100px;'  STYLE='color:blue;'>".$row['debug']."".number_format($row['SaldoFavorAplicado'] ,$dec)."</TH>     \n";
        //else
                echo "<TH></TH>     \n";



        if(!$row['DiasAtraso'])
                echo "<TH  ALIGN='right' Width='100px;'></TH>     \n";
        else
                echo "<TH  ALIGN='right' Width='100px;' STYLE='color:black;'>".($row['DiasAtraso']+$dias_offset)."</TH>     \n";


        if(!$row['DiasAtrasoAcum'])
                echo "<TH  ALIGN='right' Width='100px;'></TH>     \n";
        else

                echo "<TH  ALIGN='right' Width='100px;' STYLE='color:black;'>".($row['DiasAtrasoAcum']+$dias_offset)."</TH>     \n";




        if(!$row['DiasAtraso'])
                echo "<TH  ALIGN='right' Width='100px;'></TH>     \n";
        else
                echo "<Th  ALIGN='right' Width='100px;' STYLE='color:black;'>".number_format($row['IMB'],$dec)."</Th>     \n";

        $style = ($row['SaldoParcial']<0)?(" STYLE='color:blue;' "):(" STYLE='color:black;'");


        echo "<TH ALIGN='right' Width='100px;' ".$style." >".number_format($row['SaldoParcial'],$dec)." </TH>     \n";

        $SALDO_General = $row['SALDO_General'];
        
        
        if($row['SALDO_MOV_General']<0)
                echo "<TH ALIGN='right' Width='100px;'  STYLE='color:blue;'>".number_format($row['SALDO_MOV_General'],$dec). "</TH>     \n";
        else    
                echo "<TH ALIGN='right' Width='100px;'  STYLE='color:black;'>".number_format($row['SALDO_General'],$dec). "</TH>     \n";
        
        
        
        
        
        
        $style = ($row['SALDO_MOV_Pendiente_Aplicar']<0)?(" STYLE='color:blue;' "):(" STYLE='color:black;'");
        
        
        echo "<TH ALIGN='right' Width='150px;' ".$style.">".number_format($row['SALDO_MOV_Pendiente_Aplicar'],$dec). "</TH>     \n";

        echo "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";

        if($this->ver_desglose_cargos )
        {

        echo "<!-- desglose DE CARGOS --> \n";


        if($color == 'lime')
                   $ivacolor = $color;
        else
                   $ivacolor = 'yellow';




                       $font_color=($row['CARGO_Capital']                       <0)?("red; font-weight:bold;"):("black;");
                       echo "<TD ALIGN='right' Width='100px;' STYLE='color:".$font_color."'  >".number_format($row['CARGO_Capital'],$dec)."</TD>     \n";



                       $font_color=($row['CARGO_Interes']                       <0)?("red; font-weight:bold;"):("black;");
                       echo "<TD ALIGN='right' Width='100px;' STYLE='color:".$font_color."'  >".number_format($row['CARGO_Interes'],$dec)."</TD>     \n";

                       $font_color=($row['CARGO_IVA_Interes']   <0)?("red; font-weight:bold;"):("black;");
                       echo "<TD ALIGN='right' Width='100px;' STYLE='color:".$font_color."'  bgcolor='".$ivacolor."'>".number_format($row['CARGO_IVA_Interes'],$dec)."</TD>     \n";


                       $font_color=(($row['IM'] + $row['CARGO_Moratorio'])      <0)?("red; font-weight:bold;"):("black;");
                       echo "<TD ALIGN='right' Width='100px;' STYLE='color:".$font_color."'  >".number_format(($row['IM'] + ($row['CARGO_Moratorio'])),$dec)."</TD>     \n";
   
                       $font_color=($row['CARGO_IVA_Moratorio'] <0)?("red; font-weight:bold;"):("black;");
                       echo "<TD ALIGN='right' Width='100px;' STYLE='color:".$font_color."'  bgcolor='".$ivacolor."'>".number_format($row['CARGO_IVA_Moratorio'],$dec)."</TD>     \n";
   
   
   
                       $font_color=($row['CARGO_Comision']                      <0)?("red; font-weight:bold;"):("black;");
                       echo "<TD ALIGN='right' Width='100px;' STYLE='color:".$font_color."'  >".number_format($row['CARGO_Comision'],$dec)."</TD>     \n";

                       $font_color=($row['CARGO_IVA_Comision']  <0)?("red; font-weight:bold;"):("black;");
                       echo "<TD ALIGN='right' Width='100px;' STYLE='color:".$font_color."'  bgcolor='".$ivacolor."'>".number_format($row['CARGO_IVA_Comision'],$dec)."</TD>     \n";



                       $font_color=($row['CARGO_Otros']                         <0)?("red; font-weight:bold;"):("black;");
                       echo "<TD ALIGN='right' Width='100px;' STYLE='color:".$font_color."'  >".number_format($row['CARGO_Otros'],$dec)."</TD>     \n";

                       $font_color=($row['CARGO_IVA_Otros']     <0)?("red; font-weight:bold;"):("black;");                     
                       echo "<TD ALIGN='right' Width='100px;' STYLE='color:".$font_color."'  bgcolor='".$ivacolor."'>".number_format($row['CARGO_IVA_Otros'],$dec)."</TD>     \n";







                       $font_color=($row['CARGO_SegV']                      <0)?("red; font-weight:bold;"):("black;");
                       echo "<TD ALIGN='right' Width='100px;' STYLE='color:".$font_color."'  >".number_format($row['CARGO_SegV'],$dec)."</TD>     \n";

                       $font_color=($row['CARGO_IVA_SegV']  <0)?("red; font-weight:bold;"):("black;");
                       echo "<TD ALIGN='right' Width='100px;' STYLE='color:".$font_color."'  bgcolor='".$ivacolor."'>".number_format($row['CARGO_IVA_SegV'],$dec)."</TD>     \n";



                       $font_color=($row['CARGO_SegD']                      <0)?("red; font-weight:bold;"):("black;");
                       echo "<TD ALIGN='right' Width='100px;' STYLE='color:".$font_color."'  >".number_format($row['CARGO_SegD'],$dec)."</TD>     \n";

                       $font_color=($row['CARGO_IVA_SegD']  <0)?("red; font-weight:bold;"):("black;");
                       echo "<TD ALIGN='right' Width='100px;' STYLE='color:".$font_color."'  bgcolor='".$ivacolor."'>".number_format($row['CARGO_IVA_SegD'],$dec)."</TD>     \n";



                       $font_color=($row['CARGO_SegB']                      <0)?("red; font-weight:bold;"):("black;");
                       echo "<TD ALIGN='right' Width='100px;' STYLE='color:".$font_color."'  >".number_format($row['CARGO_SegB'],$dec)."</TD>     \n";

                       $font_color=($row['CARGO_IVA_SegB']  <0)?("red; font-weight:bold;"):("black;");
                       echo "<TD ALIGN='right' Width='100px;' STYLE='color:".$font_color."'  bgcolor='".$ivacolor."'>".number_format($row['CARGO_IVA_SegB'],$dec)."</TD>     \n";







                       


//                       $font_color=($row['CARGO_IVA']           <0)?("red; font-weight:bold;"):("black;");                     
//                       echo "<TD ALIGN='right' Width='100px;' STYLE='color:".$font_color."'  bgcolor='".$ivacolor."'>".number_format($row['CARGO_IVA'],$dec)."</TD>     \n";



//                     echo "<TD ALIGN='right' Width='100px;'>".number_format($row['CARGO_IVA'],$dec)."</TD>     \n";

                 echo "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";
        }

        if($this->ver_desglose_abonos)
        {

               echo "<!-- desglose DE ABONOS --> \n";


                if($color == 'lime')
                           $ivacolor = $color;
                else
                           $ivacolor = 'yellow';



                       $style = ($row['ABONO_Capital']>0)?(" STYLE='color:black;' "):(" STYLE='color:blue;' ");
                       echo "<TD ALIGN='right' Width='100px;' ".$style.">".number_format($row['ABONO_Capital']       ,$dec)."</TD>     \n";



                       $style = ($row['ABONO_Interes']>0)?(" STYLE='color:black;' "):(" STYLE='color:blue;' ");
                       echo "<TD ALIGN='right' Width='100px;' ".$style.">".number_format($row['ABONO_Interes']       ,$dec)."</TD>     \n";

                       $style = ($row['ABONO_Interes_IVA']>0)?(" STYLE='color:black;' "):(" STYLE='color:blue;' ");
                       echo "<TD ALIGN='right' Width='100px;' ".$style." bgcolor='".$ivacolor."' >".number_format($row['ABONO_Interes_IVA']    ,$dec)."</TD>     \n";



                       $style = ($row['ABONO_Moratorio']>0)?(" STYLE='color:black;' "):(" STYLE='color:blue;' ");
                       echo "<TD ALIGN='right' Width='100px;' ".$style.">".number_format($row['ABONO_Moratorio']     ,$dec)."</TD>     \n";

                       $style = ($row['ABONO_Moratorio_IVA']>0)?(" STYLE='color:black;' "):(" STYLE='color:blue;' ");
                       echo "<TD ALIGN='right' Width='100px;' ".$style." bgcolor='".$ivacolor."' >".number_format($row['ABONO_Moratorio_IVA']  ,$dec)."</TD>     \n";



                       $style = ($row['ABONO_Comision']>0)?(" STYLE='color:black;' "):(" STYLE='color:blue;' ");
                       echo "<TD ALIGN='right' Width='100px;' ".$style.">".number_format($row['ABONO_Comision']      ,$dec)."</TD>     \n";

                       $style = ($row['ABONO_Comision_IVA']>0)?(" STYLE='color:black;' "):(" STYLE='color:blue;' ");
                       echo "<TD ALIGN='right' Width='100px;' ".$style." bgcolor='".$ivacolor."' >".number_format($row['ABONO_Comision_IVA']   ,$dec)."</TD>     \n";



                       $style = ($row['ABONO_Otros']>0)?(" STYLE='color:black;' "):(" STYLE='color:blue;' ");
                       echo "<TD ALIGN='right' Width='100px;' ".$style.">".number_format($row['ABONO_Otros']      ,$dec)."</TD>     \n";

                       $style = ($row['ABONO_Otros_IVA']>0)?(" STYLE='color:black;' "):(" STYLE='color:blue;' ");
                       echo "<TD ALIGN='right' Width='100px;' ".$style." bgcolor='".$ivacolor."' >".number_format($row['ABONO_Otros_IVA']      ,$dec)."</TD>     \n";







                       $style = ($row['ABONO_SegV']>0)?(" STYLE='color:black;' "):(" STYLE='color:blue;' ");
                       echo "<TD ALIGN='right' Width='100px;' ".$style.">".number_format($row['ABONO_SegV']      ,$dec)."</TD>     \n";

                       $style = ($row['ABONO_SegV_IVA']>0)?(" STYLE='color:black;' "):(" STYLE='color:blue;' ");
                       echo "<TD ALIGN='right' Width='100px;' ".$style." bgcolor='".$ivacolor."' >".number_format($row['ABONO_SegV_IVA']      ,$dec)."</TD>     \n";



                       $style = ($row['ABONO_SegD']>0)?(" STYLE='color:black;' "):(" STYLE='color:blue;' ");
                       echo "<TD ALIGN='right' Width='100px;' ".$style.">".number_format($row['ABONO_SegD']      ,$dec)."</TD>     \n";

                       $style = ($row['ABONO_SegD_IVA']>0)?(" STYLE='color:black;' "):(" STYLE='color:blue;' ");
                       echo "<TD ALIGN='right' Width='100px;' ".$style." bgcolor='".$ivacolor."' >".number_format($row['ABONO_SegD_IVA']      ,$dec)."</TD>     \n";



                       $style = ($row['ABONO_SegB']>0)?(" STYLE='color:black;' "):(" STYLE='color:blue;' ");
                       echo "<TD ALIGN='right' Width='100px;' ".$style.">".number_format($row['ABONO_SegB']      ,$dec)."</TD>     \n";

                       $style = ($row['ABONO_SegB_IVA']>0)?(" STYLE='color:black;' "):(" STYLE='color:blue;' ");
                       echo "<TD ALIGN='right' Width='100px;' ".$style." bgcolor='".$ivacolor."' >".number_format($row['ABONO_SegB_IVA']      ,$dec)."</TD>     \n";










//                       $style = ($row['ABONO_IVA']>0)?(" STYLE='color:black;' "):(" STYLE='color:blue;' ");
//                       echo "<TD ALIGN='right' Width='100px;' ".$style." bgcolor='".$ivacolor."' >".number_format($row['ABONO_IVA']            ,$dec)."</TD>     \n";
                       
                       
                       
                      
               echo "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";

        }



        if($this->ver_saldo_desglosado)
        {

               echo "<!-- Desglose DE Saldos --> \n";

             if($color == 'lime')
                        $ivacolor = $color;
             else
                        $ivacolor = 'yellow';

                       $style = ($row['SALDO_MOV_Capital']<0)?(" STYLE='color:blue;' "):(" STYLE='color:black;' ");
                       echo "<TD ALIGN='right' ".$style."  Width='100px;'>".number_format($row['SALDO_MOV_Capital']             ,$dec)."</TD>     \n";




                       $style = ($row['SALDO_MOV_Interes']<0)?(" STYLE='color:blue;' "):(" STYLE='color:black;' ");                    
                       echo "<TD ALIGN='right' ".$style."  Width='100px;'>".number_format($row['SALDO_MOV_Interes']             ,$dec)."</TD>     \n";

                       $style = ($row['SALDO_MOV_IVA_Interes']<0)?(" STYLE='color:blue;' "):(" STYLE='color:black;' ");                
                       echo "<TD ALIGN='right' ".$style."   Width='100px;' bgcolor='".$ivacolor."'>".number_format($row['SALDO_MOV_IVA_Interes']        ,$dec)."</TD>     \n";




                       $style = ($row['SALDO_MOV_Moratorio']<0)?(" STYLE='color:blue;' "):(" STYLE='color:black;' ");                  
                       echo "<TD ALIGN='right' ".$style."   Width='100px;'>".number_format($row['SALDO_MOV_Moratorio']          ,$dec)."</TD>     \n";
                       
                       $style = ($row['SALDO_MOV_IVA_Moratorio']<0)?(" STYLE='color:blue;' "):(" STYLE='color:black;' ");                      
                       echo "<TD ALIGN='right' ".$style."   Width='100px;' bgcolor='".$ivacolor."'>".number_format($row['SALDO_MOV_IVA_Moratorio']      ,$dec)."</TD>     \n";



                       $style = ($row['SALDO_MOV_Comision']<0)?(" STYLE='color:blue;' "):(" STYLE='color:black;' ");                   
                       echo "<TD ALIGN='right' ".$style."   Width='100px;'>".number_format($row['SALDO_MOV_Comision']           ,$dec)."</TD>     \n";
                       
                       $style = ($row['SALDO_MOV_IVA_Comision']<0)?(" STYLE='color:blue;' "):(" STYLE='color:black;' ");                       
                       echo "<TD ALIGN='right' ".$style."   Width='100px;' bgcolor='".$ivacolor."'>".number_format($row['SALDO_MOV_IVA_Comision']       ,$dec)."</TD>     \n";



                       $style = ($row['SALDO_MOV_Otros']<0)?(" STYLE='color:blue;' "):(" STYLE='color:black;' ");                      
                       echo "<TD ALIGN='right' ".$style." '  Width='100px;'>".number_format($row['SALDO_MOV_Otros']                     ,$dec)."</TD>     \n";

                       $style = ($row['SALDO_MOV_IVA_Otros']<0)?(" STYLE='color:blue;' "):(" STYLE='color:black;' ");                  
                       echo "<TD ALIGN='right' ".$style."   Width='100px;' bgcolor='".$ivacolor."'>".number_format($row['SALDO_MOV_IVA_Otros']  ,$dec)."</TD>     \n";


      
      
      
                       $style = ($row['SALDO_MOV_SegV']<0)?(" STYLE='color:blue;' "):(" STYLE='color:black;' ");                      
                       echo "<TD ALIGN='right' ".$style." '  Width='100px;'>".number_format($row['SALDO_MOV_SegV']                     ,$dec)."</TD>     \n";

                       $style = ($row['SALDO_MOV_IVA_SegV']<0)?(" STYLE='color:blue;' "):(" STYLE='color:black;' ");                  
                       echo "<TD ALIGN='right' ".$style."   Width='100px;' bgcolor='".$ivacolor."'>".number_format($row['SALDO_MOV_IVA_SegV']  ,$dec)."</TD>     \n";
      
      
                       $style = ($row['SALDO_MOV_SegD']<0)?(" STYLE='color:blue;' "):(" STYLE='color:black;' ");                      
                       echo "<TD ALIGN='right' ".$style." '  Width='100px;'>".number_format($row['SALDO_MOV_SegD']                     ,$dec)."</TD>     \n";

                       $style = ($row['SALDO_MOV_IVA_SegD']<0)?(" STYLE='color:blue;' "):(" STYLE='color:black;' ");                  
                       echo "<TD ALIGN='right' ".$style."   Width='100px;' bgcolor='".$ivacolor."'>".number_format($row['SALDO_MOV_IVA_SegD']  ,$dec)."</TD>     \n";
     


                       $style = ($row['SALDO_MOV_SegB']<0)?(" STYLE='color:blue;' "):(" STYLE='color:black;' ");                      
                       echo "<TD ALIGN='right' ".$style." '  Width='100px;'>".number_format($row['SALDO_MOV_SegB']                     ,$dec)."</TD>     \n";

                       $style = ($row['SALDO_MOV_IVA_SegB']<0)?(" STYLE='color:blue;' "):(" STYLE='color:black;' ");                  
                       echo "<TD ALIGN='right' ".$style."   Width='100px;' bgcolor='".$ivacolor."'>".number_format($row['SALDO_MOV_IVA_SegB']  ,$dec)."</TD>     \n";
      
      
      
      
      
      
      
      
      
      
      
      
                       
                       

//                       $style = ($row['SALDO_MOV_IVA']<0)?(" STYLE='color:blue;' "):(" STYLE='color:black;' ");                
//                       echo "<TD ALIGN='right' ".$style."   Width='100px;' bgcolor='".$ivacolor."'>".number_format($row['SALDO_MOV_IVA']                ,$dec)."</TD>     \n";

                      
                       $style = ($row['SALDO_MOV_General']<0)?(" STYLE='color:blue;' "):(" STYLE='color:black;' ");                    
                       echo "<TH ALIGN='right'".$style."   Width='100px;'>".number_format($row['SALDO_MOV_General'],$dec). "</TH>     \n";


               echo "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp;</TD>     \n";
        }

        if($this->ver_saldos_vencer)
        {

                        echo "<TH ALIGN='right' STYLE='color:black;'  Width='100px;'>".number_format(  $row['SaldoCapitalPorVencer']    ,$dec)."</TH>     \n";
                        echo "<TH ALIGN='right' STYLE='color:black;'  Width='100px;'>".number_format( ($row['SaldoInteresPorVencer']    +$row['SaldoInteres_IVA_PorVencer'] ) ,$dec)."</TH>     \n";
                        echo "<TH ALIGN='right' STYLE='color:black;'  Width='100px;'>".number_format( ($row['SaldoComisionPorVencer']   +$row['SaldoComision_IVA_PorVencer']) ,$dec)."</TH>     \n";

                        echo "<TH ALIGN='right' STYLE='color:black;'  Width='100px;'>".number_format( ($row['SaldoSegVPorVencer']   +$row['SaldoSegV_IVA_PorVencer']) ,$dec)."</TH>     \n";
                        echo "<TH ALIGN='right' STYLE='color:black;'  Width='100px;'>".number_format( ($row['SaldoSegDPorVencer']   +$row['SaldoSegD_IVA_PorVencer']) ,$dec)."</TH>     \n";
                        echo "<TH ALIGN='right' STYLE='color:black;'  Width='100px;'>".number_format( ($row['SaldoSegBPorVencer']   +$row['SaldoSegB_IVA_PorVencer']) ,$dec)."</TH>     \n";








               echo "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";
        }
/*
        if($this->ver_saldo_general)
        {


                       echo "<TH ALIGN='right' STYLE='color:black;'  Width='100px;'>".number_format($row['SaldoGlobalCapital' ] ,$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' STYLE='color:black;'  Width='100px;'>".number_format($row['SaldoGlobalInteres' ] ,$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' STYLE='color:black;'  Width='100px;'>".number_format($row['SaldoGlobalComision'] ,$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' STYLE='color:black;'  Width='100px;'>".number_format($row['SaldoGlobalOtros'   ] ,$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' STYLE='color:black;'  Width='100px;'>".number_format($row['SaldoGlobalMoratorio']        ,$dec)."</TH>     \n";

                       echo "<TH ALIGN='right' STYLE='color:black;'  Width='100px;'>".number_format($row['SaldoGlobalSegV']     ,$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' STYLE='color:black;'  Width='100px;'>".number_format($row['SaldoGlobalSegD']     ,$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' STYLE='color:black;'  Width='100px;'>".number_format($row['SaldoGlobalSegB']     ,$dec)."</TH>     \n";




                       echo "<TH ALIGN='right' STYLE='color:black;'  Width='100px;'  bgcolor='".$ivacolor."'  >".number_format($row['SaldoGlobalIVA']              ,$dec)."</TH>     \n";

  
                        echo "<TH ALIGN='right' STYLE='color:black;'  Width='100px;'>".number_format((  $row['SaldoGlobalCapital' ] +
                                                                                                        $row['SaldoGlobalInteres' ] +
                                                                                                        $row['SaldoGlobalComision'] +
                                                                                                        $row['SaldoGlobalOtros'   ] +
                                                                                                        $row['SaldoGlobalMoratorio']+
                        
                                                                                                        $row['SaldoGlobalSegV']     +
                                                                                                        $row['SaldoGlobalSegD']     +
                                                                                                        $row['SaldoGlobalSegB']     +
                                                                                                        
                                                                                                        $row['SaldoGlobalIVA']       ),$dec)."</TH>     \n";



               echo "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";
        }
*/        
        echo "</TR> \n";


        $SALDO_General = $row['SALDO_General'];

        if($ultima_cuota and ($row['SALDO_General']<0.01) )
        {
                        
                        echo "<TR  BGCOLOR='steelblue'  ><TD STYLE='height:2px;' COLSPAN='".$colspan."'></TD></TR>     \n";
                        $ultima_cuota=0;
        
        
        }

        $ABONO_LIBRE = $row['ABONO_LIBRE'];
        
        ++$count;

     }

   //----------------------------------------------------------------------------------------
   // Totales
   //----------------------------------------------------------------------------------------

 echo $tableheadgroup;
 echo $tablehead;


       echo "<TR  BGCOLOR='steelblue'  ID='small' STYLE='color:white;'>      \n";

                               echo "<TD        ALIGN='center'></TD>\n";
                               echo "<TD        ALIGN='center'></TD>\n";
                               echo "<TD        ALIGN='center'></TD>\n";
                               echo "<TH  ALIGN='right'>".number_format($this->SumaCargos               ,$dec)."</TH>     \n";
                               echo "<TH  ALIGN='right'>".number_format($this->SumaAbonos               ,$dec)."</TH>     \n";
                               //echo "<TH  ALIGN='right'>".number_format($this->SumaSaldoFavorAplicado ,$dec)."</TH>     \n";
                               
                               echo "<Th  ALIGN='right'></Th>     \n";
                               echo "<Th  ALIGN='right'></Th>     \n";

                               echo "<Th  ALIGN='right'>".number_format($this->dias_mora                ,   0)."</Th>     \n";
                               echo "<TH  ALIGN='right'>".number_format($this->SumaIMB                  ,$dec)."</TH>     \n";
                               echo "<TD        ALIGN='left'  ></TD>     \n";


                        $style = ($row['SALDO_General']<0)?(" STYLE='color:blue;' "):("");
                       
                       
        if($this->SaldoGeneralVigente<0)
        {
                       
                       echo "<TH ALIGN='right' Width='100px;' ".$style.">".number_format($this->SaldoGeneralVigente,$dec). "</TH>     \n";
        }
        else
        {
                       echo "<TH ALIGN='right' Width='100px;' ".$style.">".number_format($this->SaldoGeneralVencido,$dec). "</TH>     \n";
        
        }
                       
                       
                       echo "<TH ALIGN='right' Width='150px;' STYLE='color:blue;' >".number_format($this->SaldoPendienteAplicar ,$dec)."</TH>     \n";

       echo "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";

if($this->ver_desglose_cargos )
{

       echo "<!-- desglose DE CARGOS --> \n";



                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaCapital                ,$dec)."</TH>     \n";
                       
                       
                       
                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaInteres                ,$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaIVAInteres             ,$dec)."</TH>     \n";
                       
                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaIM                     ,$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaIVAMoratorio           ,$dec)."</TH>     \n";
                       
                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaComision               ,$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaIVAComision            ,$dec)."</TH>     \n";
                                                                    
                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaOtros                  ,$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaIVAOtros               ,$dec)."</TH>     \n";


                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaSegV               ,$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaIVASegV            ,$dec)."</TH>     \n";

                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaSegD               ,$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaIVASegD            ,$dec)."</TH>     \n";

                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaSegB               ,$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaIVASegB            ,$dec)."</TH>     \n";



//                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaIVA,$dec)."</TH>     \n";

       echo "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";
}



if($this->ver_desglose_abonos)
{

       echo "<!-- desglose DE ABONOS --> \n";



                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaAbonoCapital   ,$dec)."</TH>     \n";



                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaAbonoInteres   ,$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaAbonoIVAInteres        ,$dec)."</TH>     \n";


                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaAbonoIM        ,$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaAbonoIVAIM             ,$dec)."</TH>     \n";


                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaAbonoComision  ,$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaAbonoIVAComision       ,$dec)."</TH>     \n";


                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaAbonoOtros     ,$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaAbonoIVAOtros  ,$dec)."</TH>     \n";




                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaAbonoSegV      ,$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaAbonoIVASegV   ,$dec)."</TH>     \n";

                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaAbonoSegD      ,$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaAbonoIVASegD   ,$dec)."</TH>     \n";


                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaAbonoSegB      ,$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaAbonoIVASegB   ,$dec)."</TH>     \n";







//                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaAbonoIVA       ,$dec)."</TH>     \n";


       echo "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";
}

if($this->ver_saldo_desglosado)
{

       echo "<!-- desglose DE Saldos --> \n";




                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoCapital       ,$dec)."</TH>     \n";


                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoInteres       ,$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoIVAInteres    ,$dec)."</TH>     \n";


                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoIM            ,$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoIVAIM         ,$dec)."</TH>     \n";


                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoComision      ,$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoIVAComision   ,$dec)."</TH>     \n";


                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoOtros         ,$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoIVAOtros      ,$dec)."</TH>     \n";



                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoSegV      ,$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoIVASegV   ,$dec)."</TH>     \n";


                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoSegD      ,$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoIVASegD   ,$dec)."</TH>     \n";


                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoSegB      ,$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoIVASegB   ,$dec)."</TH>     \n";




 //                      echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoIVA           ,$dec)."</TH>     \n";

                        $style = ($row['SALDO_General']<0)?(" STYLE='color:blue;' "):("");
                       echo "<TH ALIGN='right' Width='100px;' ".$style.">".number_format($row['SALDO_General'],$dec). "</TH>     \n";



       echo "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";

}

if($this->ver_saldos_vencer)
{
                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoCapitalPorVencer ,$dec)."</TH>     \n";
                      
                       echo "<TH ALIGN='right' Width='100px;'>".number_format(($this->SaldoInteresPorVencer  + $this->Saldo_IVA_InteresPorVencer ),$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' Width='100px;'>".number_format(($this->SaldoComisionPorVencer + $this->Saldo_IVA_ComisionPorVencer),$dec)."</TH>     \n";

                       echo "<TH ALIGN='right' Width='100px;'>".number_format(($this->SaldoSegVPorVencer + $this->Saldo_IVA_SegVPorVencer),$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' Width='100px;'>".number_format(($this->SaldoSegDPorVencer + $this->Saldo_IVA_SegDPorVencer),$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' Width='100px;'>".number_format(($this->SaldoSegBPorVencer + $this->Saldo_IVA_SegBPorVencer),$dec)."</TH>     \n";




                      
                      
                       echo "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";


}
/*
if($this->ver_saldo_general)
{

                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoGlobalCapital                         ,$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoGlobalInteres                         ,$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoGlobalComision                        ,$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoGlobalOtros                           ,$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoGlobalMoratorio                       ,$dec)."</TH>     \n";


                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoGlobalSegV                        ,$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoGlobalSegD                        ,$dec)."</TH>     \n";
                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoGlobalSegB                        ,$dec)."</TH>     \n";





                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoGlobalIVA                             ,$dec)."</TH>     \n";

                       echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoGlobalGeneral                         ,$dec)."</TH>     \n";


       echo "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";
}
*/
       echo "</TR> \n";

 echo "</TABLE><BR>\n\n";

if(($this->ver_desglose_cargos or $this->ver_desglose_abonos or $this->ver_saldo_desglosado or $this->ver_saldos_vencer or $this->ver_saldo_general))
 echo "\n</DIR>\n";



echo '<DIR><small>Tiempo procesamiento : '.$this->precess_time." seg.</small></DIR>";

}



if($this->ver_historico_morosidad)
{
 echo "<BR><BR>\n ";



        echo "<TABLE ALIGN='center'   BORDER=0 BGCOLOR='black' CELLPADDDING=1 CELLSPACING=1 BORDER=0  ID='small' >\n";
        echo "<TR ALIGN='CENTER'  BGCOLOR='white'  STYLE='color:black;'>\n";
        echo "  <TH> Fecha              </TH>
                <TH> Dias Mora          </TH>
                <TH> Contador           </TH>
                <TH> Extemporáneo       </TH>           ";

        echo "</TR>\n";

         
         $fecha_dia = $this->primer_vencimiento;
         while ($fecha_dia<= $this->fecha_corte )
         {

                        list($_y, $_m, $_d) = explode("-",$fecha_dia);

                        $color= ($_d == 31)?("yellow"):("white");       

                        $color= (empty($this->fechas_inhabiles[$fecha_dia]))?($color):("orange");       



                        echo "<TR ALIGN='CENTER'  BGCOLOR='".$color."'  STYLE='color:black;'>\n";
                        echo "  <TH>".ffecha($fecha_dia)."</TH>";
                        echo "  <TH>".$this->historico_morosidad_cobranza[$fecha_dia]."</TH>";//
                        echo "  <TH>".$this->historico_morosidad_diferencial_cobranza[$fecha_dia]."</TH>";// [".$this->hmdc[$fecha_dia][3]."]  (".$this->hmdc[$fecha_dia][4].") {".$this->hmdc[$fecha_dia][5]."} [".$this->hmdc[$fecha_dia][6]."]  </TH>";
                        echo "  <TH>".$this->cargos_cobranza_automaticos[$fecha_dia]."</TH>";

                        echo "</TR>\n";
                        list($_y, $_m, $_d) = explode("-",$fecha_dia);
                        

                        $fecha_dia = date("Y-m-d",mktime(0,0,0,$_m, ($_d+1), $_y));
                        
        }       
        echo "</TABLE>\n";



 }

//-------------------------------------------------------------------------------
//               Parámentos moratorios
//------------------------------------------------------------------------------
/* SALDO EN BASE A CUOTAS */

if($this->ver_saldos_por_cuota)
{
        $this->imprime_saldos();
}


if(($this->ver_parametros_moratorios) and ($this->ver_moratorios_cuota))
{

        $dia[0] = "Dom";
        $dia[1] = "Lun";
        $dia[2] = "Mar";
        $dia[3] = "Mie";
        $dia[4] = "Jue";
        $dia[5] = "Vie";
        $dia[6] = "Sab";

        echo "<H3 Align='center'> Detalle cálculos para el cargo de intereses moratorios </H3>";

        echo "<TABLE ALIGN='center' WIDTH='95%'  BORDER=0 BGCOLOR='black' CELLPADDDING=1 CELLSPACING=1 BORDER=0  ID='small' >\n";

        echo "<TR ALIGN='center' BGCOLOR='steelblue'  STYLE='color:white;'>\n";
        echo "<TH COLSPAN='4'> Parámetros para moratorios       </TH>\n";

        echo "</TR>\n";

        $base_capital           =($this->moratorio_base_capital         )?("Si"):("No");

        $base_interes           =($this->moratorio_base_interes         )?("Si"):("No"); 

        $base_comision          =($this->moratorio_base_comision        )?("Si"):("No");                

        $base_exteporaneos      =($this->moratorio_base_otros           )?("Si"):("No"); 


        echo "<TR ALIGN='CENTER'  BGCOLOR='silver'  STYLE='color:black;'>\n";
        echo "  <TH> Moratorio base capital             </TH>
                <TH> Moratorio base interés             </TH>
                <TH> Moratorio base comisión            </TH>
                <TH> Moratorio base extemporáneos       </TH>";
        echo "</TR>\n";

        echo "<TR ALIGN='CENTER'  BGCOLOR='white'  STYLE='color:black;'>\n";

        echo "  <TH>".($base_capital    )."</TH>";
        echo "  <TH>".($base_interes    )."</TH>";
        echo "  <TH>".($base_comision   )."</TH>";
        echo "  <TH>".($base_exteporaneos)."</TH>";
        /**/
        echo "</TR>\n";


        echo "</TABLE>\n";

        echo "<BR><BR>\n";

        


        echo "<TABLE ALIGN='center'  WIDTH='95%'  BORDER=0 BGCOLOR='black' CELLPADDDING=1 CELLSPACING=1 BORDER=0   ID='small'  >\n";
        {



                echo "<TR Align='center'  BGCOLOR='steelblue' STYLE='color:white;'>
                                  <TH> Cuota </TH>                                
                                  <TH> ID Mov</TH>
                                  <TH> Fecha </TH>
                                  <TH> Tipo  </TH>
                                  <TH> Monto Base</TH>                                  
                                  <TH WIDTH='100px;'> Saldo Cuota  Mora   </TH>                                  
                                 
                                  <TH> Días <BR>naturales </TH>
                                  <TH> Acumulado <BR>de Días </TH>

                                  <TH> Días mora<BR> general </TH>

                                  <TH> Días mora<BR> máximos </TH>

                                  <TH> Factor   </TH>

                                  <TH> Moratorio<BR> calculado    </TH>
                                  <TH> Acumulado de Calculados </TH>

                                  <TH> Valor<BR> Bruto    </TH>
                                  <TH> IVA    </TH>

                                  <TH> Cargo Efectivo  </TH>
                                  <TH> Acumulado </TH>  
                                  <TH WIDTH='100px;'> Saldo Final</TH>                                  
                                  
                                  
                     </TR>\n";




                $desactivar = "";
                foreach($this->detalle_moratorios AS $row)
                {
                        
                        if($this->ver_moratorios_cuota == $row['ID_Cargo'])     
                        {
                        
                        
                                   if($row['ID_Cargo'] != $_ID_Cargo)
                                   {
                                        echo "<TR  BGCOLOR='steelblue'>\n";
                                        echo "  <TH COLSPAN='18' ALIGN='left' >&nbsp;&nbsp;&nbsp;Cuota # ".number_format($row['ID_Cargo'],0)."</TH>";

                                        echo "</TR>\n";

                                        $_ID_Cargo = $row['ID_Cargo'];


                                   }



                                $fecha_dia = $row['Fecha'];



                                $color=($color=='white')?('lavender'):('white');
                                
                                
                                
                                
                                
                                if(!empty($this->fechas_inhabiles[$fecha_dia]))
                                {
                                        $color="orange";
                                }
                                


                                if(($row['dias_mora_maximos'] >($this->max_dias_calculo-1) or ($row['AcumDias']>($this->max_dias_calculo-1))))
                                {
                                                $color="lime";
                                }

                                   list($_y,$_m,$_d) = explode("-",$row['Fecha']);
                                   $_wdia1 = date("w",mktime(0,0,0,$_m,$_d,$_y));

                                if($_d == 31) 
                                {
                                                $color="yellow";
                                }
                        


                                   echo "<TR  BGCOLOR='".$color."'>\n";

                                   echo "  <TD  ALIGN='right'   >".number_format($row['ID_Cargo'],0)."                          </TD>";// ($_dex)       ($reactivar)
                                   echo "  <TD  ALIGN='right'   >".number_format($row['ID_Mov'],  0)."                          </TD>";


                                   list($_y,$_m,$_d) = explode("-",$row['Fecha']);
                                   $_wdia1 = date("w",mktime(0,0,0,$_m,$_d,$_y));

                                   echo "<TD ALIGN='center' Width='100px;'  >".ffecha($row['Fecha']) ." ".$dia[$_wdia1]."</TD>     \n";


                                   //echo "  <TD        ALIGN='center'  >".ffecha($row['Fecha'])." ".$dia[$_wdia1]."                                    </TD>";
                                   echo "  <TD  ALIGN='left'    >".$row['Tipo']."                                               </TD>";
                                   echo "  <TD  ALIGN='right'   >".number_format($row['MontoBase']                              ,2)."</TD>";
                                   echo "  <TD  ALIGN='right'   >".number_format($row['Saldo_Moratorio']                        ,2)."</TD>";


                                   echo "  <TD  ALIGN='right'   >".number_format($row['Dias']                                   ,0)."</TD>";
                                   echo "  <TD  ALIGN='right'   >".number_format($row['AcumDias']                               ,0)."</TD>";
                                   echo "  <TD  ALIGN='right'   >".number_format($row['dias_mora']                              ,0)."</TD>";

                                   echo "  <Th  ALIGN='right'   >".number_format($row['dias_mora_maximos'],  0)."</Th>";
                                   
                                   
                                   $stylef = ($row['Factor'] != $this->tasa_moratorio)?(" STYLE='color:blue; font-weight:bold;' "):("");

                                   echo "  <TD  ALIGN='right'  ".$stylef." >".number_format($row['Factor']                     ,2)."</TD>";

                                   echo "  <TD  ALIGN='right'   >".number_format($row['MoratorioCalculado']                     ,2)."</TD>";
                                   echo "  <TD  ALIGN='right'   >".number_format($row['SumaMoratoriosCalculadosPorCuota']       ,2)."</TD>";
                                   
     
                                   echo "  <TD  ALIGN='right'   >".number_format($row['IM']                                    ,2)."</TD>";
                                   echo "  <TD  ALIGN='right'   >".number_format(($row['pIVA'] *100)                   ,2)."%</TD>";
        
                                   
                                   echo "  <TD  ALIGN='right'   >".number_format($row['IMB']                                    ,2)."</TD>";
                                   echo "  <TD  ALIGN='right'   >".number_format($row['Acumulado_Cuota']                        ,2)."</TD>";
                                   echo "  <TD  ALIGN='right'   >".number_format(($row['Saldo_Moratorio']+$row['IMB'])                  ,2)."</TD>";


                                   echo "</TR>\n";
                                   
                                   $Total_Dias                                  += $row['Dias'];

                                   $Total_AcumDias                              = $row['AcumDias']                              ;
                                   $Total_dias_mora                             = $row['dias_mora']                             ;

                                   $Total_dias_mora_max                         = max($Total_dias_mora_max, $this->historico_morosidad_global[$fecha_dia]);


                                   $Total_MoratorioCalculado                    += $row['MoratorioCalculado']                   ;
                                   $Total_SumaMoratoriosCalculadosPorCuota      =  $row['SumaMoratoriosCalculadosPorCuota']     ;
    
 
                                   $Total_SumaMoratoriosCargadosPorCuota_Bruto      += $row['IM'];
                                   $Total_SumaMoratoriosCargadosPorCuota_IVA        += ($row['pIVA']*$row['IM'] );

 
 
                                   $Total_SumaMoratoriosCargadosPorCuota        += $row['IMB']                                  ;
                                   $Total_Acumulado                             =  $row['Acumulado']                            ;

                                   $Total_Acumulado_Cuota                       =  $row['Acumulado_Cuota'];
                                   $Total_Saldo_Moratorio_Previo                =  $row['Saldo_Moratorio'];
                                   $Total_Saldo_Moratorio_Final                 =  $row['Saldo_Moratorio']+$row['IMB'];
                           
                           
                           
                }
                else
                {
                
                        if(($row['ID_Cargo'] > $this->ver_moratorios_cuota))
                                break;
                
                }

            }
            
            
                if($_ID_Cargo)
                {

                   echo "<TR  BGCOLOR='lightsteelblue'>\n";
                   echo "  <TH COLSPAN='5' ALIGN='left' >&nbsp;&nbsp;&nbsp; Subtotal </TH>";

                   echo "  <TH  ALIGN='right'   >".number_format($Total_Saldo_Moratorio_Previo                  ,2)."</TH>";
                   echo "  <TH  ALIGN='right'   >".number_format($Total_Dias                    ,0)."</TH>";


                   echo "  <TH  ALIGN='right'   >".number_format($Total_AcumDias                        ,0)."</TH>";

                   echo "  <TH  ALIGN='right'   >".number_format($Total_dias_mora                       ,0)."</TH>";
                   echo "  <TH  ALIGN='right'   >".number_format($Total_dias_mora_max                   ,0)."</TH>";

                   echo "  <TH  ALIGN='right'   ></TH>";

                   echo "  <TH  ALIGN='right'   >".number_format($Total_MoratorioCalculado              ,2)."</TH>";

                   echo "  <TH  ALIGN='right'   >".number_format($Total_SumaMoratoriosCalculadosPorCuota,2)."</TH>";

                   echo "  <TH  ALIGN='right'   >".number_format($Total_SumaMoratoriosCargadosPorCuota_Bruto,2)."</TH>";
                   echo "  <TH  ALIGN='right'   >".number_format($Total_SumaMoratoriosCargadosPorCuota_IVA  ,2)."</TH>";

                   echo "  <TH  ALIGN='right'   >".number_format($Total_SumaMoratoriosCargadosPorCuota  ,2)."</TH>";
                   echo "  <TH  ALIGN='right'   >".number_format($Total_Acumulado_Cuota                 ,2)."</TH>";
                   echo "  <TH  ALIGN='right'   >".number_format($Total_Saldo_Moratorio_Final                   ,2)."</TH>";


                   echo "</TR>\n";
                   $Total_Dias                                 = 0;
                   $Total_AcumDias                              =0;
                   $Total_MoratorioCalculado                    =0;
                   
                   $Total_SumaMoratoriosCargadosPorCuota_Bruto  =0;
                   $Total_SumaMoratoriosCargadosPorCuota_IVA    =0;
                   
                   $Total_SumaMoratoriosCalculadosPorCuota      =0;
                   $Total_SumaMoratoriosCargadosPorCuota        =0;
                   $Total_Acumulado_Mov                         =0;
                   $Total_Acumulado_Cuota                       =0;
                   $Total_dias_mora                             =0;
                   $Total_dias_mora_max                         =0;
                   $Total_Saldo_Moratorio_Previo                =0;
                   $Total_Saldo_Moratorio_Final                 =0;

                }
            
            
            
        }
        echo "</TABLE><BR><BR>"; 

        }
 
   }



function imprime_saldos()
{
   $dec = 2;
   $colspan=13;
   $width=1300;



   if($this->ver_desglose_cargos )
   {
           $width+=  (600+300);
         $colspan+=6+5  +5;

   }

  if($this->ver_desglose_abonos)
   {
           $width+=  700+300+100  ; //+400;
         $colspan+=7+3+1  +5;

   }


  if($this->ver_saldo_desglosado)
   {
           $width+=  700+200+200  ; //+400;
         $colspan+=7+4+1  +5;

   }


  if($this->ver_saldos_vencer)
   {
               $width+=  400     ; //+200;
         $colspan+=4   + 3;

   }
/*
  if($this->ver_saldo_general)
   {
           $width+= 400         ; //+200;
         $colspan+=6+2 +3;

   }

*/


                $width .= "px ";


                //  echo "<IMG SRC='".$img_path."toexel.png'  onMouseOver=\"javascript:this.style.cursor='hand';\"  onClick=\"javascript:SelectItem('detalle');\" /> \n\n";





/**/
        if( !($this->ver_desglose_cargos or $this->ver_desglose_abonos or $this->ver_saldo_desglosado or $this->ver_saldos_vencer or $this->ver_saldo_general))
                $width = "95%";
        else
                echo "<DIR>\n";



        echo "<TABLE WIDTH='".$width."'  BORDER=0 BGCOLOR='black' CELLPADDDING=1 CELLSPACING=1 BORDER=0   STYLE='font-size: 10px; font-family:arial;'  >\n";




                  $tableheadgroup = "<TR ALIGN='center' BGCOLOR='silver'  STYLE='color:black;'  >   \n";

                  $tableheadgroup .= "<TH ColSpan='9'   BGCOLOR='gray'    STYLE='color:white;'> Movimientos </TH> \n";
                  $tableheadgroup .= "<TH ColSpan='2'   BGCOLOR='silver'  STYLE='color:black;'>  Saldo </TH> \n";
                  $tableheadgroup .= "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";

                if($this->ver_desglose_cargos )
                {

                  $tableheadgroup .= "<TH ColSpan='".(5+3+2     +5)."'   BGCOLOR='gray'   STYLE='color:white;'> Desglose de cargos.</TH> \n";
                  $tableheadgroup .= "  <TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";

                }

                if($this->ver_desglose_abonos)
                {
                  $tableheadgroup .= "<TH ColSpan='".(6+3+2     +5)."'   BGCOLOR='silver' STYLE='color:black;'> Desglose de abonos.</TH> \n";
                  $tableheadgroup .= "  <TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";
                }

                if($this->ver_saldo_desglosado)
                {
                  $tableheadgroup .= "<TH ColSpan='".(6+3+2     +5)."'   BGCOLOR='gray'   STYLE='color:white;'> Saldo vencido desglosado.</TH> \n";
                  $tableheadgroup .= "  <TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";
                }

                if($this->ver_saldos_vencer)
                {
                  $tableheadgroup .= "<TH ColSpan='".(3     +3)."'   BGCOLOR='silver'   STYLE='color:black;'> Saldos por devengar.</TH> \n";
                  $tableheadgroup .= "  <TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";
                }
/*
                if($this->ver_saldo_general)
                {
                  $tableheadgroup .= "  <TH ColSpan='".(5+2     +3)."'   BGCOLOR='gray'   STYLE='color:white;'> Saldo gobal.</TH> \n";
                  $tableheadgroup .= "  <TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";
                }

*/
                  $tableheadgroup .= "  </TR> \n";






                   $tablehead .= "  <TR  BGCOLOR='lightsteelblue'  STYLE='color:black;' >   \n";
                         $tablehead .= "  <TH Width='150px;'>   Fecha Movimiento                                    </TH>\n";
                         $tablehead .= "  <TH Width='150px;'>   Fecha Aplicación                                    </TH>\n";
                         
                         $tablehead .= "  <TH Width='350px;'>   Concepto                                  </TH>\n";
                         $tablehead .= "  <TH > Cargos                                    </TH>\n";
                         $tablehead .= "  <TH > Abonos                                    </TH>\n";

                         $tablehead .= "  <TH > Días vencidos                             </TH>\n";
                         $tablehead .= "  <TH > Fecha Ultimo Mov                          </TH>\n";


                         $tablehead .= "  <TH > Fecha Ultimo Abono                         </TH>\n";
                         $tablehead .= "  <TH > Fecha Ultimo Cargo                         </TH>\n";
                         $tablehead .= "  <TH > Saldo Parcial </TH>\n";
                         $tablehead .= "  <TH > Saldo General </TH>\n";
                         
                         $tablehead .= "  <TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";


                if($this->ver_desglose_cargos )
                {
                         $tablehead .= "  <TH>   Capital                                </TH>\n";

                         $tablehead .= "  <TH>   Int. nominal                           </TH>\n";
                         $tablehead .= "  <TH>   IVA                                    </TH>\n";


                         $tablehead .= "  <TH>   Int. moratorio                         </TH> \n";
                         $tablehead .= "  <TH>   IVA                                    </TH>\n";


                         $tablehead .= "  <TH>   Comisiones                             </TH>\n";
                         $tablehead .= "  <TH>   IVA                                    </TH>\n";


                         $tablehead .= "  <TH>   Extemporáneos                          </TH>\n";
                         $tablehead .= "  <TH>   IVA                                    </TH>\n";

                         $tablehead .= "  <TH>   Seg. Vida.                             </TH>\n";
                         $tablehead .= "  <TH>   IVA                                    </TH>\n";

                         $tablehead .= "  <TH>   Seg. Des.                              </TH>\n";
                         $tablehead .= "  <TH>   IVA                                    </TH>\n";

                         $tablehead .= "  <TH>   Seg. Bienes                            </TH>\n";
                         $tablehead .= "  <TH>   IVA                                    </TH>\n";


//                         $tablehead .= "  <TH>  Suma IVA  </TH>\n";

                       $tablehead .= "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";
                }

                if($this->ver_desglose_abonos)
                {

                           $tablehead .= "  <TH>   Capital                                </TH>\n";

                           $tablehead .= "  <TH>   Int. nominal                           </TH>\n";
                           $tablehead .= "  <TH>   IVA                                    </TH>\n";


                           $tablehead .= "  <TH>   Int. moratorio                         </TH> \n";
                           $tablehead .= "  <TH>   IVA                                    </TH>\n";


                           $tablehead .= "  <TH>   Comisiones                             </TH>\n";
                           $tablehead .= "  <TH>   IVA                                    </TH>\n";


                           $tablehead .= "  <TH>   Extemporáneos                          </TH>\n";
                           $tablehead .= "  <TH>   IVA                                    </TH>\n";

                           $tablehead .= "  <TH>   Seg. Vida.                             </TH>\n";
                           $tablehead .= "  <TH>   IVA                                    </TH>\n";

                           $tablehead .= "  <TH>   Seg. Des.                              </TH>\n";
                           $tablehead .= "  <TH>   IVA                                    </TH>\n";

                           $tablehead .= "  <TH>   Seg. Bienes                            </TH>\n";
                           $tablehead .= "  <TH>   IVA                                    </TH>\n";

 //                        $tablehead .= "  <TH>   Suma IVA                               </TH>\n";
                           $tablehead .= "  <TH>   Por  Aplicar                   </TH>\n";

                   $tablehead .= "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>\n";
                }


                if($this->ver_saldo_desglosado)
                {

                           $tablehead .= "  <TH>   Capital                                </TH>\n";

                           $tablehead .= "  <TH>   Int. nominal                           </TH>\n";
                           $tablehead .= "  <TH>   IVA                                    </TH>\n";


                           $tablehead .= "  <TH>   Int. moratorio                         </TH> \n";
                           $tablehead .= "  <TH>   IVA                                    </TH>\n";


                           $tablehead .= "  <TH>   Comisiones                             </TH>\n";
                           $tablehead .= "  <TH>   IVA                                    </TH>\n";


                           $tablehead .= "  <TH>   Extemporáneos                          </TH>\n";
                           $tablehead .= "  <TH>   IVA                                    </TH>\n";

                           $tablehead .= "  <TH>   Seg. Vida.                             </TH>\n";
                           $tablehead .= "  <TH>   IVA                                    </TH>\n";

                           $tablehead .= "  <TH>   Seg. Des.                              </TH>\n";
                           $tablehead .= "  <TH>   IVA                                    </TH>\n";

                           $tablehead .= "  <TH>   Seg. Bienes                            </TH>\n";
                           $tablehead .= "  <TH>   IVA                                    </TH>\n";

//                         $tablehead .= "  <TH>   Suma IVA  </TH>\n";



                         $tablehead .= "  <TH>   Saldo                             </TH>\n";

                   $tablehead .= "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";
                }

                if($this->ver_saldos_vencer)
                {

                         $tablehead .= "  <TH>   Capital                                   </TH>\n";
                         $tablehead .= "  <TH>   Int.                                     </TH>\n";
                         $tablehead .= "  <TH>   Comisiones                               </TH>\n";

                         $tablehead .= "  <TH>   Seg. Vida.                                </TH>\n";
                         $tablehead .= "  <TH>   Seg. Des.                                 </TH>\n";
                         $tablehead .= "  <TH>   Seg. Bienes                               </TH>\n";


                         $tablehead .= "   <TD   ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";
                }
/*
                if($this->ver_saldo_general)
                {

                         $tablehead .= "  <TH>   Capital                                </TH>\n";
                         $tablehead .= "  <TH>   Int. nominal                        </TH>\n";
                         $tablehead .= "  <TH>   Comisión                               </TH>\n";
                         $tablehead .= "  <TH>   Extemporaneos                          </TH>\n";
                         $tablehead .= "  <TH>   Moratorios                             </TH>\n";


                         $tablehead .= "  <TH>   Seg. Vida.                             </TH>\n";
                         $tablehead .= "  <TH>   Seg. Des.                              </TH>\n";
                         $tablehead .= "  <TH>   Seg. Bienes                            </TH>\n";


                         $tablehead .= "  <TH>   IVA                                              </TH>\n";
                         $tablehead .= "  <TH>   Global                                           </TH>\n";


                   $tablehead .= "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";
                }
*/
                    $tablehead .= "  </TR> \n";





                
//        echo "<TABLE WIDTH='".$width."'  BORDER=0 BGCOLOR='black' CELLPADDDING=1 CELLSPACING=1 BORDER=0  >\n";

        echo $tableheadgroup;

        echo $tablehead;
        
        $ivacolor = 'yellow';

        foreach($this->saldos_cuota AS $cuota => $row)
        {

                if($cuota <= $this->numcargosvencidos)
                {

                                $color=($color=='white')?('lavender'):('white');

                                
                                $leyenda =  "Cuota # ".$cuota;
                                
                                if($cuota == 0)
                                {
                                        $row['Fecha_Mov'] = $this->fecha_inicio;
                                        $row['Fecha']     = $this->fecha_inicio;
                                        $leyenda = "Inicio ";
                                }



                                echo "<TR  BGCOLOR='".$color."'  >      \n";
                                echo "<TH  ALIGN='center' Width='100px;'>".ffecha($row['Fecha_Mov'])."</TH>     \n";
                                echo "<TH  ALIGN='center' Width='100px;'>".ffecha($row['Fecha']    )."</TH>     \n";

                                
                                
                                
                                echo "<TH ALIGN='center' Width='100px;'>".$leyenda ."</TD>     \n";

                                $SUMA_CARGOS += $row['CARGOS'];
                                //$SUMA_ABONOS += $row['ABONOS'];
                                $SUMA_PAGOS  += $row['PAGOS'];




                                echo "<TH  ALIGN='right' Width='100px;'                         >".number_format($row['CARGOS'], $dec)."</TH>     \n";

                                echo "<TH  ALIGN='right' Width='100px;' STYLE='color:blue;'     >".number_format($row['PAGOS'], $dec)."</TH>     \n";



                                if($row['DiasAtrasoAcum'])
                                echo "<TH  ALIGN='right' Width='100px;'>".$row['DiasAtrasoAcum']."</TH>     \n";
                                else
                                echo "<TH  ALIGN='right' Width='100px;'></TH>     \n";


                                echo "<TH  ALIGN='center' Width='100px;'>".ffecha($row['Fecha_Ultimo_Mov'])."</TH>     \n";
                                echo "<TH  ALIGN='center' Width='100px;'>".ffecha($row['Fecha_Ultimo_Abono'])."</TH>     \n";
                                echo "<TH  ALIGN='center' Width='100px;'>".ffecha($row['Fecha_Ultimo_Cargo'])."</TH>     \n";

                                echo "<TH ALIGN='right' Width='100px;' >".number_format($row['SaldoParcial'], $dec)."</TH>     \n";

                                //$SALDO_MOV_General = ($row['SALDO_MOV_General']<0)?(0):($row['SALDO_MOV_General']);

                                $SALDO_MOV_General = $row['SALDO_MOV_General'];
                                $STYLE = ($SALDO_MOV_General <0)?(" STYLE=' color:blue;' "):(" ");

                                echo "<TH ALIGN='right' Width='100px;' $STYLE >".number_format($SALDO_MOV_General,$dec). "  </TH>     \n";


                                echo "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>    \n"; 

                                if($this->ver_desglose_cargos )
                                {

                                echo "<!-- desglose DE CARGOS --> \n";




                                               echo "<TD ALIGN='right' Width='100px;'>".number_format($row['CUOTA_CARGO_Capital'],$dec)."</TD>     \n";



                                               echo "<TD ALIGN='right' Width='100px;'>".number_format($row['CUOTA_CARGO_Interes'],$dec)."</TD>     \n";
                                               echo "<TD ALIGN='right' Width='100px;' bgcolor='".$ivacolor."'>".number_format($row['CUOTA_CARGO_IVA_Interes'],$dec)."</TD>     \n";


                                               echo "<TD ALIGN='right' Width='100px;'>".number_format(($row['CUOTA_IM'] + $row['CUOTA_CARGO_Moratorio']),$dec)."</TD>     \n";
                                               echo "<TD ALIGN='right' Width='100px;' bgcolor='".$ivacolor."'>".number_format($row['CUOTA_CARGO_IVA_Moratorio'],$dec)."</TD>     \n";



                                               echo "<TD ALIGN='right' Width='100px;'>".number_format($row['CUOTA_CARGO_Comision'],$dec)."</TD>     \n";
                                               echo "<TD ALIGN='right' Width='100px;' bgcolor='".$ivacolor."'>".number_format($row['CUOTA_CARGO_IVA_Comision'],$dec)."</TD>     \n";



                                               echo "<TD ALIGN='right' Width='100px;'>".number_format($row['CUOTA_CARGO_Otros'],$dec)."</TD>     \n";
                                               echo "<TD ALIGN='right' Width='100px;' bgcolor='".$ivacolor."'>".number_format($row['CUOTA_CARGO_IVA_Otros'],$dec)."</TD>     \n";


                                               echo "<TD ALIGN='right' Width='100px;'>".number_format($row['CUOTA_CARGO_SegV'],$dec)."</TD>     \n";
                                               echo "<TD ALIGN='right' Width='100px;' bgcolor='".$ivacolor."'>".number_format($row['CUOTA_CARGO_IVA_SegV'],$dec)."</TD>     \n";


                                               echo "<TD ALIGN='right' Width='100px;'>".number_format($row['CUOTA_CARGO_SegD'],$dec)."</TD>     \n";
                                               echo "<TD ALIGN='right' Width='100px;' bgcolor='".$ivacolor."'>".number_format($row['CUOTA_CARGO_IVA_SegD'],$dec)."</TD>     \n";

                                               echo "<TD ALIGN='right' Width='100px;'>".number_format($row['CUOTA_CARGO_SegB'],$dec)."</TD>     \n";
                                               echo "<TD ALIGN='right' Width='100px;' bgcolor='".$ivacolor."'>".number_format($row['CUOTA_CARGO_IVA_SegB'],$dec)."</TD>     \n";




//                                               echo "<TD ALIGN='right' Width='100px;' bgcolor='".$ivacolor."'>".number_format($row['CUOTA_CARGO_IVA'],$dec)."</TD>     \n";




                                         echo "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";
                                }

                                if($this->ver_desglose_abonos)
                                {

                                       echo "<!-- desglose DE ABONOS --> \n";

 

                                               echo "<TD ALIGN='right' Width='100px;' STYLE='color:blue;'>".number_format($row['ABONO_Capital']       ,$dec)."</TD>     \n";



                                               echo "<TD ALIGN='right' Width='100px;' STYLE='color:blue;'>".number_format($row['ABONO_Interes']       ,$dec)."</TD>     \n";
                                               echo "<TD ALIGN='right' Width='100px;' STYLE='color:blue;' bgcolor='".$ivacolor."' >".number_format($row['ABONO_Interes_IVA']    ,$dec)."</TD>     \n";
  
 
                                               echo "<TD ALIGN='right' Width='100px;' STYLE='color:blue;'>".number_format($row['ABONO_Moratorio']     ,$dec)."</TD>     \n";
                                               echo "<TD ALIGN='right' Width='100px;' STYLE='color:blue;' bgcolor='".$ivacolor."' >".number_format($row['ABONO_Moratorio_IVA']  ,$dec)."</TD>     \n";


                                               echo "<TD ALIGN='right' Width='100px;' STYLE='color:blue;'>".number_format($row['ABONO_Comision']      ,$dec)."</TD>     \n";
                                               echo "<TD ALIGN='right' Width='100px;' STYLE='color:blue;' bgcolor='".$ivacolor."' >".number_format($row['ABONO_Comision_IVA']   ,$dec)."</TD>     \n";


                                               echo "<TD ALIGN='right' Width='100px;' STYLE='color:blue;'>".number_format($row['ABONO_Otros']      ,$dec)."</TD>     \n";
                                               echo "<TD ALIGN='right' Width='100px;' STYLE='color:blue;' bgcolor='".$ivacolor."' >".number_format($row['ABONO_Otros_IVA']      ,$dec)."</TD>     \n";



                                               echo "<TD ALIGN='right' Width='100px;' STYLE='color:blue;'>".number_format($row['ABONO_SegV']      ,$dec)."</TD>     \n";
                                               echo "<TD ALIGN='right' Width='100px;' STYLE='color:blue;' bgcolor='".$ivacolor."' >".number_format($row['ABONO_SegV_IVA']   ,$dec)."</TD>     \n";

                                               echo "<TD ALIGN='right' Width='100px;' STYLE='color:blue;'>".number_format($row['ABONO_SegD']      ,$dec)."</TD>     \n";
                                               echo "<TD ALIGN='right' Width='100px;' STYLE='color:blue;' bgcolor='".$ivacolor."' >".number_format($row['ABONO_SegD_IVA']   ,$dec)."</TD>     \n";

                                               echo "<TD ALIGN='right' Width='100px;' STYLE='color:blue;'>".number_format($row['ABONO_SegB']      ,$dec)."</TD>     \n";
                                               echo "<TD ALIGN='right' Width='100px;' STYLE='color:blue;' bgcolor='".$ivacolor."' >".number_format($row['ABONO_SegB_IVA']   ,$dec)."</TD>     \n";



//                                               echo "<TD ALIGN='right' Width='100px;' STYLE='color:blue;' bgcolor='".$ivacolor."' >".number_format($row['ABONO_IVA']            ,$dec)."</TD>     \n";

                                               echo "<Th ALIGN='right' Width='150px;' STYLE='color:blue;'>".number_format($row['CUOTA_SALDO_MOV_Pendiente_Aplicar']       ,$dec)."</Th>     \n";



                                       echo "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";

                                }


                        //
                        //      if($this->ver_saldo_desglosado)
                        //      {
                        //
                        //             echo "<!-- Desglose DE Saldos --> \n";
                        //
                        //
                        //
                        //                     echo "<TD ALIGN='right' Width='100px;'>".number_format($row['SALDO_Capital'] ,$dec)."</TD>     \n";
                        //                     echo "<TD ALIGN='right' Width='100px;'>".number_format($row['SALDO_Interes'],$dec)."</TD>     \n";
                        //                     echo "<TD ALIGN='right' Width='100px;'>".number_format($row['SALDO_Moratorio'],$dec)."</TD>     \n";
                        //                     echo "<TD ALIGN='right' Width='100px;'>".number_format($row['SALDO_Comision'],$dec)."</TD>     \n";
                        //                     echo "<TD ALIGN='right' Width='100px;'>".number_format($row['SALDO_IVA'],$dec)."</TD>     \n";
                        //
                        //                      $style = ($row['SALDO_General']<0)?(" STYLE='color:blue;' "):("");
                        //                     echo "<TH ALIGN='right' Width='100px;' ".$style.">".number_format($row['SALDO_General'],$dec). "</TH>     \n";
                        //
                        //
                        //             echo "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp;</TD>     \n";
                        //      }
                        //

                                if($this->ver_saldo_desglosado)
                                {

                                       echo "<!-- Desglose DE Saldos --> \n";

  

                                               $style = ($row['SALDO_MOV_Capital']<0)?(" STYLE='color:blue;' "):("STYLE='color:black;' ");
                                               echo "<TD ALIGN='right' Width='100px;' ".$style.">".number_format($row['CUOTA_SALDO_MOV_Capital']                ,$dec)."</TD>     \n";

                                               $style = ($row['CUOTA_SALDO_MOV_Interes']<0)?(" STYLE='color:blue;' "):("STYLE='color:black;' ");
                                               echo "<TD ALIGN='right' Width='100px;' ".$style.">".number_format($row['CUOTA_SALDO_MOV_Interes']                 ,$dec)."</TD>     \n";

                                                $style = ($row['CUOTA_SALDO_MOV_IVA_Interes']<0)?(" STYLE='color:blue;' "):("STYLE='color:black;' ");
                                               echo "<TD ALIGN='right' Width='100px;' ".$style." bgcolor='".$ivacolor."'>".number_format($row['CUOTA_SALDO_MOV_IVA_Interes']    ,$dec)."</TD>     \n";



                                               $style = ($row['CUOTA_SALDO_MOV_Moratorio']<0)?(" STYLE='color:blue;' "):("STYLE='color:black;' ");
                                               echo "<TD ALIGN='right' Width='100px;' ".$style.">".number_format($row['CUOTA_SALDO_MOV_Moratorio']              ,$dec)."</TD>     \n";

                                               $style = ($row['CUOTA_SALDO_MOV_IVA_Moratorio']<0)?(" STYLE='color:blue;' "):("STYLE='color:black;' ");
                                               echo "<TD ALIGN='right' Width='100px;' ".$style." bgcolor='".$ivacolor."'>".number_format($row['CUOTA_SALDO_MOV_IVA_Moratorio']  ,$dec)."</TD>     \n";



                                               $style = ($row['CUOTA_SALDO_MOV_Comision']<0)?(" STYLE='color:blue;' "):("STYLE='color:black;' ");
                                               echo "<TD ALIGN='right' Width='100px;' ".$style.">".number_format($row['CUOTA_SALDO_MOV_Comision']               ,$dec)."</TD>     \n";

                                                $style = ($row['CUOTA_SALDO_MOV_IVA_Comision']<0)?(" STYLE='color:blue;' "):("STYLE='color:black;' ");
                                               echo "<TD ALIGN='right' Width='100px;' ".$style." bgcolor='".$ivacolor."'>".number_format($row['CUOTA_SALDO_MOV_IVA_Comision']   ,$dec)."</TD>     \n";



                                               $style = ($row['CUOTA_SALDO_MOV_Otros']<0)?(" STYLE='color:blue;' "):("STYLE='color:black;' ");
                                               echo "<TD ALIGN='right' Width='100px;'  ".$style.">".number_format($row['CUOTA_SALDO_MOV_Otros']                 ,$dec)."</TD>     \n";

                                                $style = ($row['CUOTA_SALDO_MOV_IVA_Otros']<0)?(" STYLE='color:blue;' "):("STYLE='color:black;' ");
                                               echo "<TD ALIGN='right' Width='100px;' ".$style." bgcolor='".$ivacolor."'>".number_format($row['CUOTA_SALDO_MOV_IVA_Otros']      ,$dec)."</TD>     \n";





                                               $style = ($row['CUOTA_SALDO_MOV_SegV']<0)?(" STYLE='color:blue;' "):("STYLE='color:black;' ");
                                               echo "<TD ALIGN='right' Width='100px;'  ".$style.">".number_format($row['CUOTA_SALDO_MOV_SegV']                 ,$dec)."</TD>     \n";

                                                $style = ($row['CUOTA_SALDO_MOV_IVA_SegV']<0)?(" STYLE='color:blue;' "):("STYLE='color:black;' ");
                                               echo "<TD ALIGN='right' Width='100px;' ".$style." bgcolor='".$ivacolor."'>".number_format($row['CUOTA_SALDO_MOV_IVA_SegV']      ,$dec)."</TD>     \n";


                                               $style = ($row['CUOTA_SALDO_MOV_SegD']<0)?(" STYLE='color:blue;' "):("STYLE='color:black;' ");
                                               echo "<TD ALIGN='right' Width='100px;'  ".$style.">".number_format($row['CUOTA_SALDO_MOV_SegD']                 ,$dec)."</TD>     \n";

                                                $style = ($row['CUOTA_SALDO_MOV_IVA_SegD']<0)?(" STYLE='color:blue;' "):("STYLE='color:black;' ");
                                               echo "<TD ALIGN='right' Width='100px;' ".$style." bgcolor='".$ivacolor."'>".number_format($row['CUOTA_SALDO_MOV_IVA_SegD']      ,$dec)."</TD>     \n";


                                               $style = ($row['CUOTA_SALDO_MOV_SegB']<0)?(" STYLE='color:blue;' "):("STYLE='color:black;' ");
                                               echo "<TD ALIGN='right' Width='100px;'  ".$style.">".number_format($row['CUOTA_SALDO_MOV_SegB']                 ,$dec)."</TD>     \n";

                                                $style = ($row['CUOTA_SALDO_MOV_IVA_SegB']<0)?(" STYLE='color:blue;' "):("STYLE='color:black;' ");
                                               echo "<TD ALIGN='right' Width='100px;' ".$style." bgcolor='".$ivacolor."'>".number_format($row['CUOTA_SALDO_MOV_IVA_SegB']      ,$dec)."</TD>     \n";









//                                                $style = ($row['CUOTA_SALDO_MOV_IVA']<0)?(" STYLE='color:blue;' "):("STYLE='color:black;' ");
//                                               echo "<TD ALIGN='right' Width='100px;' ".$style." bgcolor='".$ivacolor."'>".number_format($row['CUOTA_SALDO_MOV_IVA']            ,$dec)."</TD>     \n";

                                              $style = ($row['CUOTA_SALDO_MOV_General']<0)?(" STYLE='color:blue;' "):("STYLE='color:black;' ");
                                               echo "<TH ALIGN='right' Width='100px;' ".$style." >".number_format($row['CUOTA_SALDO_MOV_General'],$dec). "</TH>     \n";


                                       echo "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp;</TD>     \n";


                                }

                                if($this->ver_saldos_vencer)
                                {


                                           //    echo "<TH ALIGN='right' Width='100px;'>".number_format( $row['SaldoCapitalPorVencer']  ,$dec)."</TH>     \n";
                                           //    echo "<TH ALIGN='right' Width='100px;'>".number_format( $row['SaldoInteresPorVencer']  ,$dec)."</TH>     \n";
                                           //    echo "<TH ALIGN='right' Width='100px;'>".number_format( $row['SaldoComisionPorVencer']         ,$dec)."</TH>     \n";

                                               echo "<TH ALIGN='right' Width='100px;'>".number_format( $row['SaldoCapitalPorVencer']    ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format( ($row['SaldoInteresPorVencer']   + $row['SaldoInteres_IVA_PorVencer']  ) ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format( ($row['SaldoComisionPorVencer']  + $row['SaldoComision_IVA_PorVencer'] ) ,$dec)."</TH>     \n";

                                               echo "<TH ALIGN='right' Width='100px;'>".number_format( ($row['SaldoSegVPorVencer']  + $row['SaldoSegV_IVA_PorVencer'] ) ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format( ($row['SaldoSegDPorVencer']  + $row['SaldoSegD_IVA_PorVencer'] ) ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format( ($row['SaldoSegBPorVencer']  + $row['SaldoSegB_IVA_PorVencer'] ) ,$dec)."</TH>     \n";







                                       echo "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";
                                }

/*
                                if($this->ver_saldo_general)
                                {


                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($row['SaldoGlobalCapital' ]       ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($row['SaldoGlobalInteres' ]       ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($row['SaldoGlobalComision']       ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($row['SaldoGlobalOtros'   ]       ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($row['SaldoGlobalMoratorio']      ,$dec)."</TH>     \n";

                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($row['SaldoGlobalSegV']       ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($row['SaldoGlobalSegD']       ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($row['SaldoGlobalSegB']       ,$dec)."</TH>     \n";

                                               echo "<TH ALIGN='right' Width='100px;' bgcolor='".$ivacolor."'>".number_format($row['SaldoGlobalIVA']        ,$dec)."</TH>     \n";

                                               
                                               
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format(($row['SaldoGlobalCapital' ]  +
                                                                                                       $row['SaldoGlobalInteres' ]  +
                                                                                                       $row['SaldoGlobalComision']  +                        
                                                                                                       $row['SaldoGlobalOtros'   ]  +                        
                                                                                                       $row['SaldoGlobalMoratorio'] +                        
                                                                                                                                
                                                                                                       $row['SaldoGlobalSegV']      +                        
                                                                                                       $row['SaldoGlobalSegD']      +                        
                                                                                                       $row['SaldoGlobalSegB']      +                        
                                                                                                                                
                                                                                                       $row['SaldoGlobalIVA']        )     ,$dec)."</TH>     \n";



                                       echo "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";
                                }
*/
                                echo "</TR> \n";

                        }



                     }

                   //----------------------------------------------------------------------------------------
                   // Totales
                   //----------------------------------------------------------------------------------------



                       echo "<TR  BGCOLOR='steelblue'  ID='small' STYLE='color:white;'>      \n";



                        echo "<TD       ALIGN='center' COLSPAN='3'></TD>\n";
                        


                        
                        
                        
                        
                        echo "<TH  ALIGN='right' Width='100px;' STYLE='color:black;'                    >".number_format($SUMA_CARGOS , $dec)."</TH>     \n";
                        //echo "<TH  ALIGN='right' Width='100px;' STYLE='color:blue;'   >".number_format($SUMA_ABONOS , $dec)."</TH>     \n";                   
                        echo "<TH  ALIGN='right' Width='100px;' STYLE='color:navy;'     >".number_format($this->SumaAbonos         , $dec)."</TH>     \n";

                        
                        if($this->dias_mora)
                        echo "<Th  ALIGN='right'>".number_format($this->dias_mora               ,   0)."</Th>     \n";
                        else
                        echo "<Th  ALIGN='right'></Th>     \n";




                        echo "<TD COLSPAN='5'>&nbsp;</TD>\n";

                        //echo "<TH ALIGN='right' WIDTH='100px'>".number_format($row['SaldoParcial'],   $dec)."</TH>     \n";

                        //echo "<TH ALIGN='right' WIDTH='100px'>".number_format($SALDO_MOV_General,     $dec). "</TH>     \n";

                        echo "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>    \n"; 

                        if($this->ver_desglose_cargos )
                        {

                               echo "<!-- desglose DE CARGOS --> \n";



                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaCapital                ,$dec)."</TH>     \n";


                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaInteres                ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaIVAInteres             ,$dec)."</TH>     \n";


                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaIM                     ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaIVAMoratorio           ,$dec)."</TH>     \n";


                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaComision               ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaIVAComision            ,$dec)."</TH>     \n";


                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaOtros                  ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaIVAOtros               ,$dec)."</TH>     \n";


                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaSegV               ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaIVASegV            ,$dec)."</TH>     \n";

                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaSegD               ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaIVASegD            ,$dec)."</TH>     \n";

                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaSegB               ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaIVASegB            ,$dec)."</TH>     \n";








//                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaIVA,$dec)."</TH>     \n";

                               echo "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";
                        }



                        if($this->ver_desglose_abonos)
                        {

                               echo "<!-- desglose DE ABONOS --> \n";



                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaAbonoCapital   ,$dec)."</TH>     \n";

                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaAbonoInteres   ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaAbonoIVAInteres        ,$dec)."</TH>     \n";


                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaAbonoIM        ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaAbonoIVAIM             ,$dec)."</TH>     \n";


                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaAbonoComision  ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaAbonoIVAComision       ,$dec)."</TH>     \n";


                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaAbonoOtros     ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaAbonoIVAOtros   ,$dec)."</TH>     \n";



                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaAbonoSegV  ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaAbonoIVASegV       ,$dec)."</TH>     \n";

                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaAbonoSegD  ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaAbonoIVASegD       ,$dec)."</TH>     \n";

                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaAbonoSegB  ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaAbonoIVASegB       ,$dec)."</TH>     \n";



//                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SumaAbonoIVA       ,$dec)."</TH>     \n";

                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($row['CUOTA_SALDO_MOV_Pendiente_Aplicar'] ,$dec)."</TH>     \n";

                               echo "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";
                        }

                        if($this->ver_saldo_desglosado)
                        {

                               echo "<!-- desglose DE Saldos --> \n";



                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoCapital       ,$dec)."</TH>     \n";


                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoInteres       ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoIVAInteres    ,$dec)."</TH>     \n";


                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoIM            ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoIVAIM         ,$dec)."</TH>     \n";


                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoComision      ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoIVAComision   ,$dec)."</TH>     \n";


                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoOtros         ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoIVAOtros      ,$dec)."</TH>     \n";



                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoSegV      ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoIVASegV   ,$dec)."</TH>     \n";

                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoSegD      ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoIVASegD   ,$dec)."</TH>     \n";

                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoSegB      ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoIVASegB   ,$dec)."</TH>     \n";






//                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoIVA           ,$dec)."</TH>     \n";

                                                $style = ($row['SALDO_General']<0)?(" STYLE='color:blue;' "):("");
                                               echo "<TH ALIGN='right' Width='100px;' ".$style.">".number_format($row['SALDO_General'],$dec). "</TH>     \n";



                               echo "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";

                        }

                        if($this->ver_saldos_vencer)
                        {
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoCapitalPorVencer ,$dec)."</TH>     \n";
 

                                               echo "<TH ALIGN='right' Width='100px;'>".number_format(($this->SaldoInteresPorVencer  +$this->Saldo_IVA_InteresPorVencer ),$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format(($this->SaldoComisionPorVencer +$this->Saldo_IVA_ComisionPorVencer),$dec)."</TH>     \n";

                                               echo "<TH ALIGN='right' Width='100px;'>".number_format(($this->SaldoSegVPorVencer + $this->Saldo_IVA_SegVPorVencer),$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format(($this->SaldoSegDPorVencer + $this->Saldo_IVA_SegDPorVencer),$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format(($this->SaldoSegBPorVencer + $this->Saldo_IVA_SegBPorVencer),$dec)."</TH>     \n";


                                               echo "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";


                        }
/*
                        if($this->ver_saldo_general)
                        {

                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoGlobalCapital                         ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoGlobalInteres                         ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoGlobalComision                        ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoGlobalOtros                           ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoGlobalMoratorio                       ,$dec)."</TH>     \n";


                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoGlobalSegV                        ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoGlobalSegD                        ,$dec)."</TH>     \n";
                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoGlobalSegB                        ,$dec)."</TH>     \n";






                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoGlobalIVA                             ,$dec)."</TH>     \n";

                                               echo "<TH ALIGN='right' Width='100px;'>".number_format($this->SaldoGlobalGeneral                         ,$dec)."</TH>     \n";


                               echo "<TD  ALIGN='center' BGCOLOR='steelblue' width='10px'> &nbsp; </TD>     \n";
                        }
*/
                 echo "</TR> \n";

                 echo "</TABLE><BR>\n\n";


                 echo "<BR><BR>\n ";

}

function busca_dias_gracia($fecha)
{

        foreach($this->parametros_variables AS $data)
        {
                
                if(($fecha >= $data['FECHA_INI']) and ($fecha <= $data['FECHA_FIN'] ))
                   return $data['DIAS_GRACIA'];
                
                
                if($fecha<$data['FECHA_INI'])   //Los datos negativos significan que debe usarse el valor default.
                   return -1 ; 
        }
        
        return -1 ; 



}

function busca_tasa_moratorios($fecha)
{
        foreach($this->parametros_variables AS $data)
        {
                
                if(($fecha >= $data['FECHA_INI']) and ($fecha <= $data['FECHA_FIN'] ))
                   return $data['INT_MORATORIO'];
                
                
                if($fecha<$data['FECHA_INI'])   //Los datos negativos significan que debe usarse el valor default.
                   return -1 ; 
        }
        
        return -1 ; 
}


function busca_tasa_cobranza($fecha)
{
        foreach($this->parametros_variables AS $data)
        {
                
                if(($fecha >= $data['FECHA_INI']) and ($fecha <= $data['FECHA_FIN'] ))
                   return $data['GASTOS_COBRANZA'];
                
                
                if($fecha<$data['FECHA_INI'])   //Los datos negativos significan que debe usarse el valor default.
                   return -1 ; 
        }
        
        return -1 ; 


}



function verifica_prelacion_de_convenio($fecha_abono)
{
  $aprelacion = array();
  $aprelacion = str_split($this->prelacion);





  if( $this->id_convenio <= 0)  return($aprelacion);
          
     if(count($this->convenios_pago)>=1)  
     {
        foreach($this->convenios_pago AS $convenios)
        {
        
          if(($fecha_abono >= $convenios['fecha_inicio_convenio'])  and ($fecha_abono  <= $convenios['fecha_final_covenio']))
          {
                $aprelacion = str_split(strrev($this->prelacion));
                return($aprelacion);
          }
        
        
        }
    }

   return($aprelacion);


}
















};

//---------------------------------------------------------------------------
// Verifica el disponible sobre línea de crédito
//---------------------------------------------------------------------------

class TDISPONIBLE_CAPACIDAD_PAGO
{
        var $numcliente;
        var $fecha_corte;
        var $saldo_vencido    = 0;    
        var $linea_credito    = 0;    
        var $capacidad_semanal_maxima = 0;    

        var $disponible        = 0;

        var $num_creditos      = 0;
        var $creditos_vigentes = 0;
        var $saldoglobalcapital= 0;

        var $id_creditos_vigentes= array();

        
        var $rentas_activas_semanales= 0;

        function TDISPONIBLE_CAPACIDAD_PAGO($numcliente, $fecha_corte)  // Constructor
        {
                $this->db = ADONewConnection(SERVIDOR);  # create a connection
                $this->db->Connect(IP,USER,PASSWORD,NUCLEO);
                $this->numcliente  = $numcliente;
                $this->fecha_corte = $fecha_corte;
                
                $sql = " SELECT Linea_credito, Capacidad_pago FROM clientes WHERE Num_cliente = '".$numcliente."' ";
                $rs  = $this->db->Execute($sql);
                $this->linea_credito = $rs->fields[0];
                $this->capacidad_semanal_maxima= $rs->fields[1];
                
                
                
                
                
                $sql = "        SELECT   compras.num_compra, 
                                         fact_cliente.id_factura
                                FROM     compras, 
                                         fact_cliente
                                WHERE    compras.num_cliente = '".$numcliente."' AND 
                                         fact_cliente.num_compra = compras.Num_compra 
                                GROUP BY compras.num_compra ";

                $rs = $this->db->Execute($sql);
                $i = 0;
                $SaldoGlobalCapital=0;
                
                if($rs->_numOfRows)
                While(! $rs->EOF)
                {
                        
                        $factura = $rs->fields[1];
                        $op= new TCUENTA($factura, $fecha_corte);
                        $this->saldo_vencido += $op->SaldoGeneralVencido;
                        
                        $SaldoGlobalCapital  += $op->SaldoGlobalCapital;
                        
                        
                        if($op->SaldoGlobalCapital > 0)
                        {
                                ++$this->creditos_vigentes;
                                
                                $this->rentas_activas_semanales += 7*($op->renta/$op->dias_periodo);
                                
                                $this->id_creditos_vigentes[] = $op->idfactura;
                                
                                
                                
                        }
                        ++$i;
                        
                        unset($op);
                        
                        $rs->MoveNext() ;
                }

                $this->saldoglobalcapital += $SaldoGlobalCapital;
                
                $this->disponible = $this->capacidad_semanal_maxima - $this->rentas_activas_semanales;
                        
                

        }
};
//---------------------------------------------------------------------------
// Verifica el disponible sobre línea de crédito
//---------------------------------------------------------------------------

class TDISPONIBLE
{
        var $numcliente;
        var $fecha_corte;
        var $saldo_vencido    = 0;    
        var $linea_credito    = 0;    
        var $disponible        = 0;
        var $num_creditos      = 0;
        var $creditos_vigentes = 0;
        var $saldoglobalcapital= 0;

        function TDISPONIBLE($numcliente, $fecha_corte)  // Constructor
        {
                $this->db = ADONewConnection(SERVIDOR);  # create a connection
                $this->db->Connect(IP,USER,PASSWORD,NUCLEO);
                $this->numcliente  = $numcliente;
                $this->fecha_corte = $fecha_corte;
                
                $sql = " SELECT Linea_credito FROM clientes WHERE Num_cliente = '".$numcliente."' ";
                $rs  = $this->db->Execute($sql);
                $this->linea_credito = $rs->fields[0];
                $sql = " SELECT       compras.num_compra, 
                                                        fact_cliente.id_factura
                                FROM        compras, 
                                                        fact_cliente
                                WHERE       compras.num_cliente = '".$numcliente."' AND 
                                                        fact_cliente.num_compra = compras.Num_compra 
                                GROUP BY compras.num_compra ";

                $rs = $this->db->Execute($sql);
                $i = 0;
                $SaldoGlobalCapital=0;
                
                if($rs->_numOfRows)
                While(! $rs->EOF)
                {
                        
                        $factura = $rs->fields[1];
                        $op= new TCUENTA($factura, $fecha_corte);
                        // debug("SaldoGeneralVencido : ($factura)[$fecha_corte] ".$op->SaldoGeneralVencido);
                        $this->saldo_vencido += $op->SaldoGeneralVencido;
                        
                        $SaldoGlobalCapital  += $op->SaldoGlobalCapital;
                        
                        
                        if($op->SaldoGlobalCapital > 0)
                        ++$this->creditos_vigentes;
                        ++$i;
                        
                        unset($op);
                        
                        $rs->MoveNext() ;
                }

                $this->saldoglobalcapital += $SaldoGlobalCapital;
                
                $this->disponible = $this->linea_credito - $this->saldoglobalcapital;
                
                

        }
};



function ifnullzero($val)
{

        if(empty($val))
                return("0");
        else
                return(1*$val);

}



function getIVA($zona, $fecha, &$db)
{
/*
        $sql = "SELECT  cat_iva_transitorio.Valor_Procentual/100 AS pIVa
                FROM    cat_iva_transitorio
                WHERE   cat_iva_transitorio.Tipo = '".$zona."' and
                        '".$fecha."' BETWEEN cat_iva_transitorio.Fecha_inicio and  cat_iva_transitorio.Fecha_final ";
 
      //  debug($sql);
       
         $rs=$db->Execute($sql);
         
         return($rs->fields[0]);
*/         
        if($zona == 'A')
        {
                if($fecha<='2009-12-31')
                        $iva = 0.15;
                else
                        $iva = 0.16;
        }
        else
        if($zona == 'B')
        {
                if($fecha<='2009-12-31')
                        $iva = 0.10;
                else
                        $iva = 0.11;
        
        
        
        }




         return($iva);
}


function getIVAComision($zona, $fecha, &$db)
{
        if($fecha<='2009-12-31')
                $iva = 0.15;
        else
                $iva = getIVA($zona, $fecha, $db);


        return($iva);
}



//=====================================================================================================================//
// Funciones libres
//=====================================================================================================================//
function tasa($capital, $renta, $num_periodos )
{

    $diferencial = 1;
    $setpoint = $capital/$renta;
    $tasa = (($renta * $num_periodos )- $capital)/($capital * $num_periodos);
    $iteracion = 0;
    $MAXINT=1;
    $MININT=0;

    While( abs($diferencial) > 0.00000000001)
    {

                    $thispoint =   ( 1 - pow((1+$tasa),(-1 * $num_periodos)))/$tasa  ;


                    $diferencial =$setpoint - $thispoint ;
                    $skip=0;


                    if($diferencial > 0)
                    {
                        $MAXINT = $tasa;
                        $tasa -= (abs($tasa - $MININT)/ 2);
                       // $oper = "<B>-</B>";
                        $skip=1;

                    }

                    if(!$skip)
                        if($diferencial  < 0)
                        {
                            $MININT = $tasa;
                            $tasa += (abs($tasa - $MAXINT)/ 2);
                           // $oper = "<B>+</B>";
                        }

                    ++$iteracion;



                    if($iteracion > 1000 )
                    break;

    }

    return($tasa);

}

//===========================================================================================================================//


function fechavencimiento($fecha_anterior, $tipo_vencimiento, $dia='')
{



        if(empty($dia))
            list($dia, $mes, $anio) = explode( "/",$fecha_anterior);
        else
            list($dya, $mes, $anio) = explode( "/",$fecha_anterior);
            
       

        switch ($tipo_vencimiento )
        {
          // Anual
          case 1 : $fecha = $dia."/".$mes."/".( ++$anio);

                   break;
          // Semestral
          case 2 :
                   $mes+=6;
                   if($mes>12)
                   {
                     $mes-=12;
                     $anio++;
                   }
                   $mes = ($mes<10)?("0".$mes):($mes);
                   $dia=($dia>31)?(31):($dia);
                   if($dia>28)
                   {
                        switch ($dia)
                        {
                                 case 31 :   $dia = last_day_of_month($mes,$anio); break;                                
                                 case 30 :   $dia = ($mes==2)?(last_day_of_month($mes,$anio)):($dia);
                                 case 29 :   $dia = ($mes==2)?(last_day_of_month($mes,$anio)):($dia);
                        }
                   }

                   $fecha = $dia."/".$mes."/".$anio;
                   break;
         // Trimestral
          case 3 : $mes+=3;
                   if($mes>12)
                   {
                     $mes-=12;
                     $anio++;
                   }
                   $mes = ($mes<10)?("0".$mes):($mes);
                   $dia=($dia>31)?(31):($dia);
                   if($dia>28)
                   {
                        switch ($dia)
                        {
                                 case 31 :   $dia = last_day_of_month($mes,$anio); break;                                
                                 case 30 :   $dia = ($mes==2)?(last_day_of_month($mes,$anio)):($dia);
                                 case 29 :   $dia = ($mes==2)?(last_day_of_month($mes,$anio)):($dia);
                        }
                   }

                   $fecha = $dia."/".$mes."/".$anio;
                   break;
         // Bimestral
          case 4 : $mes+=2;
                   if($mes>12)
                   {
                     $mes-=12;
                     $anio++;
                   }
                   $mes = ($mes<10)?("0".$mes):($mes);
                   $dia=($dia>31)?(31):($dia);
                   if($dia>28)
                   {
                        switch ($dia)
                        {
                                 case 31 :   $dia = last_day_of_month($mes,$anio); break;                                
                                 case 30 :   $dia = ($mes==2)?(last_day_of_month($mes,$anio)):($dia);
                                 case 29 :   $dia = ($mes==2)?(last_day_of_month($mes,$anio)):($dia);
                        }
                   }

                   $fecha = $dia."/".$mes."/".$anio;
                   break;
          // Mensual
          case 5 : 
                   //$dia=($dia>28)?(28):($dia);
                   
                   $debug= " ENTRA : $dia - $mes - $anio";

                   $dia=($dia>31)?(31):($dia);


                    if( $dia >= last_day_of_month($mes,$anio) )
                    {
                        
                        if($mes >= 12)
                        {
                        	$dia = 31; //(last_day_of_month( (1),($anio+1)));                        
                        }
                        else
                        {                        
                        	$dia = (last_day_of_month( ($mes+1),$anio));
                        }
                    }

                  
                   $mes++;
                   if($mes>12)
                   {
                     $mes-=12;
                     $anio++;
                   }
                   $mes = ($mes<10)?("0".$mes):($mes);
                
                   
                   $fecha = $dia."/".$mes."/".$anio;
                   
                   //debug($debug." SALE : [$dia - $mes - $anio]");
                   
                   break;


          //Quincenal
          case 6 :


                  $ultimo_dia = date("t",mktime(0,0,0,$mes, '1' ,$anio));
                  
                  if($dia >= $ultimo_dia)
                  {
                  
                     $dia = 15;
                     $mes++;
                  }
                  else
                  {

                       if($dia == 15)
                       {
                         	$dia = date("t",mktime(0,0,0,$mes, '1' ,$anio));
                       }
                       else                       
                       if($dia>15)
                       {
                         $dia -= 15;
                         $mes++;
                       }
                       else
                         $dia+=15;
                  }

                  if($mes>12)
                  {
                       $mes-=12;
                       $anio++;
                  }

                   $mes = ($mes<10)?("0".(1*$mes)):($mes);
                   $dia = ($dia<10)?("0".(1*$dia)):($dia);

                   if(($mes == "02") and ($dia>= 28))
                   {
                       $dia = date("t",mktime(0,0,0,$mes, '1' ,$anio));
                   }
                   

                   $fecha = $dia."/".$mes."/".$anio;
                   break;

          //Semanal
          case 7 : $dia+=7;
                   $fecha =strftime("%d/%m/%Y",mktime(0,0,0,$mes, $dia ,$anio));
                   break;



          //Diaria

          case 8 : $dia+=1;
                   $fecha =strftime("%d/%m/%Y",mktime(0,0,0,$mes, $dia ,$anio));
                   break;

          //Catorcenas
          case 9 : $dia+=14;
                   $fecha =strftime("%d/%m/%Y",mktime(0,0,0,$mes, $dia ,$anio));
                   break;
        };




        return($fecha);

}

//===============================================================================================

function numberToRoman($num)
{
     $n = intval($num);
     $result = '';

     $lookup = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400,
     'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40,
     'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);

     foreach ($lookup as $roman => $value)
     {
         $matches = intval($n / $value);
         $result .= str_repeat($roman, $matches);

         $n = $n % $value;
     }
     return $result;
 }

//===============================================================================================
//      Cálculo de COSTO ANUAL TOTAL  [CAT]
//===============================================================================================
function eval_cat($evalcat, $capital_efectivo, $renta, $plazo, $periodos_poranio)
{

   $res = $capital_efectivo;

   for($n=1; $n<=$plazo; $n++)
   {

        $res -= $renta/( pow( (1+$evalcat),($n/$periodos_poranio)) );

   }

   return( $res );
}

function calcula_cat($ini_cat, $capital_efectivo, $renta, $plazo, $periodo,$debug=false, $esquema_pagos="")
 {




    // $ini_cat :  Valor inicial aproximado (tasa anualizada preferentemente)
    // $capital_efectivo : Capital del crédito entregado después de comisiones.
    // $renta : Pago fijo que se hace cada periodo
    // $plazo : Numero de pagos que se deveran hacer
    // $periodo : (Mensual, Semanal, Quincenal, Diario...etc)
    //--------------------------------------------------------------
    // $periodos_poranio : Número de periodos que se pagan a lo largo de UN año
    // Fuente : http://www.banxico.org.mx/tipo/disposiciones/OtrasDisposiciones/cat.html
    //  debug("calcula_cat([$ini_cat], [$capital_efectivo], [$renta], [$plazo], [$periodo], debug=false)");




        if($ini_cat <= 0)
           $ini_cat = 0.5;


        switch($periodo)
        {
                case 'Anios'            : $periodos_poranio =     1;   break;
                case 'Semestres'        : $periodos_poranio =     2;   break;
                case 'Trimestres'       : $periodos_poranio =     4;   break;
                case 'Bimestres'        : $periodos_poranio =     6;   break;
                case 'Meses'            : $periodos_poranio =     12;  break;
                case 'Quincenas'        : $periodos_poranio =     24;  break;
                case 'Catorcenas'        : $periodos_poranio =    26;  break;                
                case 'Semanas'          : $periodos_poranio =     52;  break;
                case 'Dias'             : $periodos_poranio =     365; break;
        };
        

        if(empty($periodos_poranio))
        {

             $periodos_poranio = 12;

        }


	if($esquema_pagos == 'Unipago')
	{

		$dias_efectivos = $plazo;
		$plazo=1;

		$per_pau=(360/$dias_efectivos);


		$periodos_poranio =  trunc($per_pau,1);
	}




      $i = $ini_cat;

      $funceval = 1;

        if($debug)
        {
                echo " <TABLE Border=1 ID='S2' BGCOLOR='white' WIDTH='80%' ALIGN='center'> ";
                echo " <TR ALIGN='right' ALIGN='center'>
                                   <TH> Iteración </TH >
                                   <TH>MAX</TH>
                                   <TH>MIN</TH>
                                   <TH> Operacion </TH>
                                   <TH> CAT % </TH>
                                   <TH> RESULT </TH>
                           </TR>  \n";
        }

    while (abs($funceval) > 0.00001)
         {

                 $funceval = eval_cat($i, $capital_efectivo, $renta, $plazo, $periodos_poranio);
                 $inter = $i;
                         $skip=0;

                         if($funceval > 0)
                         {
                                 $MAXINT = $i;

                                 $i -= (abs($i-$MININT)/ 2);
                             $oper = "<B>-</B>";

                                 $skip=1;

                         }

                         if(!$skip)
                                 if($funceval < 0)
                                 {
                                         $MININT = $i;

                                         $i += (abs($i-$MAXINT)/ 2);
                                         $oper = "<B>+</B>";
                                 }

                         $iteracion++;

                          if($debug) echo " <TR ALIGN='right'><TD> ".number_format($iteracion,0)." </TD ><TD>".number_format($MAXINT,5)."</TD><TD>".number_format($MININT,5)."</TD><TD ID='S2' ALIGN='center'>".$oper."</TD><TD> ".number_format(($inter * 100),12)."% </TD><TD> ".number_format($funceval,8)." </TD></TR>  \n";

                         if($iteracion > 1000 ) break;

         }

        if($debug) echo " </TABLE> ";

        return($i);


 }



function last_day_of_month($month = '', $year = '') 
{
   if (empty($month)) {
      $month = date('m');
   }
   if (empty($year)) {
      $year = date('Y');
   }
   $result = strtotime("{$year}-{$month}-01");
   $result = strtotime('-1 second', strtotime('+1 month', $result));
   return date('d', $result);
}

?>