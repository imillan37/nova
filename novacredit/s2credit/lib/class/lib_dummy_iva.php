<?
require_once($class_path."lib_credit_rsf.php");


class DUMMY_IVA
{


   var $id_factura;
   var $num_compra;
   var $numcliente;   
   var $nombre_cliente;
   
   var $tabla_ajustada;
   var $tabla_previa;
   var $capital;
   var $tasa_nominal;
   
   var $iva_actual;

   var $plazo;
   var $renta_actual;
   var $saldo_capital_inicial;

   var $tipo_plazox;
   var $iva_nuevo;
   var $comision_por_apertura;   
   var $comision_devengada;
   var $comision_vigente;


   var $monto_comision_diferida_vigente;
   
   var $renta_cruda;
   var $renta_nueva_con_comision;
   var $renta_nueva = 0;
   
   var $tasa_nominal_nueva;
   
   var $plazo_vigente;
   var $cuota_frontera;
   var $fecha_frontera;

   

function DUMMY_IVA($id_factura)
{

    $this->id_factura = $id_factura;

    $factura = $id_factura;

    $db = ADONewConnection(SERVIDOR);  # create a connection
    $db->Connect(IP,USER,PASSWORD,NUCLEO);

    $cierre=true;
    $idnv=1;



    $sql=" SELECT   IF(vencimiento='Meses',     
                         ADDDATE(Fecha_Inicio,INTERVAL Plazo MONTH),
                         ADDDATE(Fecha_Inicio,INTERVAL Plazo *7 DAY)) AS Fecha_final,
                        fact_cliente.TasaNominal,
                        fact_cliente.num_compra
                         
                FROM    fact_cliente
                
                WHERE   id_factura = '".$factura."' ";

     $rs=$db->Execute($sql);
 
 
 
     if($rs->_numOfRows == 1) list($anio_sx, $mes_sx, $dia_sx) = explode("-",$rs->fields['Fecha_final']);


        if( empty($dia_sx )) {$dia_sx = $D;}
        if( empty($mes_sx) ) {$mes_sx = $M;}
        if( empty($anio_sx)) {$anio_sx= $Y;}

        if( strlen($dia_sx)==1 ) {$dia_sx = "0".$dia_sx;}
        if( strlen($mes_sx)==1 ) {$mes_sx = "0".$mes_sx;}

        if( ! checkdate($mes_sx, $dia_sx, $anio_sx))
        {

                $tmp = date("Y-m-d",mktime(0, 0, 0, $mes_sx, $dia_sx, $anio_sx));
                $dia_sx = fdia($tmp );
                $mes_sx = fmes($tmp );
                $anio_sx= fanio($tmp );
                $fecha_hoy = $tmp;
        }

        $nueva_fecha = mktime(0,0,0,$mes_sx,$dia_sx,$anio_sx);
        $fecha_hoy   = $anio_sx ."-".$mes_sx."-".$dia_sx;

        $tasanominal = $rs->fields['TasaNominal'];

        $j=0;




    $op = new TCUENTA($id_factura, $fecha_hoy,'','',$cierre);


    $this->numcliente            = $op->numcliente;
    $this->nombre_cliente        = $op->nombrecliente;

    $this->capital               = $op->capital;
    $this->comision_por_apertura = $op->comision_por_apertura;

    $this->tasa_nominal          = $tasanominal;

    $this->plazo                 = $op->plazo;    
    $this->tipovencimiento       = $op->tipovencimiento;

    $this->renta_actual          = $op->renta;
    $this->num_compra            = $rs->fields['num_compra'];
 









$SaldoCapital       = $op->capital;
$comision_apertura  = $op->comision_por_apertura;




$sql = "SELECT  IF( (Concepto LIKE 'Vencimiento anticipado de la cuota%'), 
                        (SELECT COUNT(cargos.ID_Cargo) 
                            FROM fact_cliente, cargos

                                 WHERE fact_cliente.num_compra = cargos.Num_compra      and
                                      cargos.ID_Concepto=-3  and cargos.Activo='Si' and      
                                                fact_cliente.id_factura = '".$factura."'                        
                         ),
                        cargos.ID_Cargo
                )  AS ID,  

                cargos.Fecha_vencimiento                                               AS Fecha,
                (cargos.Capital         -  cargos.AntiCapital    )                     AS Capital,     
                (cargos.Comision        -  cargos.AntiComision   )                     AS Comision,
                (cargos.Interes         -  cargos.AntiInteres    )                     AS Interes,
                (cargos.IVA             -  cargos.AntiIVA        )                     AS IVA,
                
               cargos.Concepto                                                        AS Concepto,
               cargos.Activo,
               cargos.Monto,
               fact_cliente.IVA_Interes AS pIVA,
               fact_cliente.Fecha_inicio,
               fact_cliente.vencimiento

        FROM  fact_cliente, cargos

        WHERE fact_cliente.num_compra = cargos.Num_compra       and
              cargos.ID_Concepto=-3   and      
              cargos.Activo     ='Si' and
              fact_cliente.id_factura = '".$factura."'                        



        ORDER BY cargos.Fecha_vencimiento, cargos.ID_Cargo ";

        $rs=$db->Execute($sql);

        $Capital_Otorgado       = $SaldoCapital; 

        $Fecha_inicio_credito   = $rs->fields['Fecha_inicio'];
        $pIVA                   = $rs->fields['pIVA'];
        $periodo                = $rs->fields['vencimiento'];

        $this->iva_actual = $rs->fields['pIVA'];


        $suma_comision_primera_parte = 0;

         $agregar = true;
         $primero = true;
         $i=0;
         list($anioc, $mesc, $diac) = explode("-",$rs->fields['Fecha_inicio']);


 
 $tabla_previa = array();

 if($rs->_numOfRows)
    while(! $rs->EOF)
    {

       $abono_anticipado = abs($op->abonos_contra_saldos_vigentes_por_couta[($rs->fields['ID'])]['Comision'] +
                               $op->abonos_contra_saldos_vigentes_por_couta[($rs->fields['ID'])]['Interes'] +
                               $op->abonos_contra_saldos_vigentes_por_couta[($rs->fields['ID'])]['Capital']);

      $_color="";


      $is_saldada  =  ($op->saldos_cuota[($rs->fields['ID'])]['SALDO_General'] <= 0.004 );

      if( (!$is_saldada) and (fanio($rs->fields['Fecha'])>=2010)  and ($agregar))
      {
                $Saldo_Capital_inicial = $SaldoCapital;
                $fecha_inicial = $anioc."-".$mesc."-".$diac;
                $ultimo_cargo = $rs->fields['ID'];

                $agregar=false;
                $_color="yellow";
      }
      
      $SaldoCapital -= $rs->fields['Capital'] ;
    
      if($agregar)
      {
        $tabla_previa[$i]['ID']           = $rs->fields['ID']          ;
        $tabla_previa[$i]['Fecha']        = $rs->fields['Fecha']       ;
        $tabla_previa[$i]['Monto']        = $rs->fields['Monto']       ;
        $tabla_previa[$i]['Capital']      = $rs->fields['Capital']     ;
        $tabla_previa[$i]['Comision']     = $rs->fields['Comision']    ;
        $tabla_previa[$i]['Comision_IVA'] = $rs->fields['Comision']*$rs->fields['IVA']/100;
        $tabla_previa[$i]['Interes']      = $rs->fields['Interes']     ;
        $tabla_previa[$i]['Interes_IVA']  = $rs->fields['Interes'] *$rs->fields['IVA']/100;
        $tabla_previa[$i]['SaldoCapital'] = $SaldoCapital;
       
       $suma_cuota_primera_parte += $rs->fields['Comision'];          
        
        $i++;

      }

       $this->tabla_previa = $tabla_previa;

       list($anioc, $mesc, $diac) = explode("-",$rs->fields['Fecha']);
   
       if($anioc >= 2010)
       {
               $primero = false;                           
       }


       $rs->MoveNext();
    }



//===================================================================//


if($ultimo_cargo<=0)
{

        // El crédito no alcanza a cruzar el límite del  1° de Enere de 2010" );

        return;   
}


        switch ($periodo )
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
          
          case 'Dias' :    $tipo_plazo = "dias.";
                           $tipo_plazox = "diaria";     
                           $frecuencia = 8;
                   break;
        };

        $this->tipo_plazox  =  $tipo_plazox;
        
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
        };

        if( empty($dias_periodo)) 
                $dias_periodo=30;

   $plazo     =  $op->plazo - $ultimo_cargo + 1;
   $iva_nuevo = ($pIVA + 1)/100;
   $capital   = $Saldo_Capital_inicial;

 
   
   if($periodo == 'Semanas')
        $tasa_periodo_ssi = (12*$tasanominal/52)/100;
   else
        $tasa_periodo_ssi = ($dias_periodo * ($tasanominal/30))/100;
   









   $tasaeq_iva = $tasa_periodo_ssi * (1 + $iva_nuevo);                         
   
   if($tasaeq_iva > 0)
      $renta = ($capital * $tasaeq_iva)/(1 - pow(1+$tasaeq_iva, -$plazo));
   else
      $renta = ($capital/$plazo);

   $renta_cruda = $renta ;

   
   $monto_comision_diferida = ($comision_apertura - $suma_cuota_primera_parte)/$plazo;
   
   
   $renta  += ($monto_comision_diferida ) * (1 + $iva_nuevo) ;
   


    if(($renta - floor($renta ))<0.01)
            $renta_ajustada = floor($renta  );
    else
            $renta_ajustada = ceil($renta  );
 
 
 
    $renta_ajustada_sin_comision = $renta_ajustada - ($monto_comision_diferida * (1 + $iva_nuevo));
    
    $tasa_reversa_ssi = $this->tasa_soluta_insoluta($capital, $renta_ajustada_sin_comision, $plazo, $iva_nuevo, 0 ); 
    
    $tasa_reversa_ssi = $tasa_reversa_ssi/(1+$iva_nuevo);
        
    $tasa_periodo_ssi_reversa = number_format($tasa_reversa_ssi,20,".",",");
    
    $tasa_mensual_ssi_reversa = 30 *  ($tasa_periodo_ssi_reversa/$dias_periodo);
                   


        $tabla_ajustada = array();
        $fecha  = ffecha($fecha_inicial);
        $dia_referencia = $diac;

        if(dias_periodo<15)
        $dia_referencia ="";


        $SaldoCapital = $capital;


        for($j=0; $j< $plazo; $j++)
        {


                $tabla_ajustada[$j]['ID'] = $ultimo_cargo +$j;


                $fecha  = fechavencimiento($fecha, $frecuencia, $dia_referencia);


                $tabla_ajustada[$j]['Fecha']  = $fecha;
                $tabla_ajustada[$j]['Monto']  = $renta_ajustada;


                $tabla_ajustada[$j]['Interes']     = $SaldoCapital * $tasa_periodo_ssi_reversa;
                $tabla_ajustada[$j]['Interes_IVA'] = $SaldoCapital * $tasa_periodo_ssi_reversa * $iva_nuevo;


                $tabla_ajustada[$j]['Comision']     = $monto_comision_diferida;
                $tabla_ajustada[$j]['Comision_IVA'] = $monto_comision_diferida * $iva_nuevo;

                $diferencial = $tabla_ajustada[$j]['Interes']  + $tabla_ajustada[$j]['Interes_IVA'] +
                               $tabla_ajustada[$j]['Comision'] + $tabla_ajustada[$j]['Comision_IVA'];
                
                $_capital = $renta_ajustada - $diferencial;
                $tabla_ajustada[$j]['Capital']          = $_capital;
                
                $SaldoCapital -= $_capital;
                $tabla_ajustada[$j]['SaldoCapital']     = $SaldoCapital;


        }



        $this->tabla_ajustada = $tabla_ajustada;                
        
        $this->cuota_frontera = $tabla_ajustada[0]['ID'];

        $this->fecha_frontera = $tabla_ajustada[0]['Fecha'];
 
        $this->saldo_capital_inicial = $Saldo_Capital_inicial;

        $this->iva_nuevo = $iva_nuevo ;

        $this->comision_devengada = $suma_cuota_primera_parte;

        $this->comision_vigente =($comision_apertura - $suma_cuota_primera_parte);


        $this->monto_comision_diferida_vigente = $monto_comision_diferida ;


        $this->renta_cruda = $renta_cruda;

        $this->renta_nueva_con_comision = $renta;


        $this->renta_nueva = $renta_ajustada;


        $this->tasa_nominal_nueva = tasa_mensual_ssi_reversa;

        $this->plazo_vigente = $plazo;


}


function tasa_soluta_insoluta($capital, $renta, $num_periodos, $piva, $debug=0 )
{

     if(  (empty($renta)) or (empty($capital))  )       return(0);

     $thispoint =0.0;
     $tasa      =0.0;

    $diferencial = 1.0;

    $setpoint = ($capital/$renta);   
    
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
                    $table .= " <TR ALIGN='right'><TD> ".number_format($iteracion,0)." </TD ><TD>".number_format($MAXINT,20)."</TD><TD>".number_format($MININT,20)."</TD><TD ID='S2' ALIGN='center'>".$oper."</TD><TD> ".number_format(($tasa * 100/(1+$piva)),20)."% </TD><TD> ".number_format($diferencial,20)." </TD></TR>  \n";

                    $iteracion++;
                
        
                
                    if($iteracion > 10000 )
                    break;

    }
        
       $table .=  " </TABLE>\n";
    
    if($debug) echo  $table;

    return($tasa);
}

};
?>
