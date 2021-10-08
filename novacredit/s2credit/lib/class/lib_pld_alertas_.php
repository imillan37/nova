<?php

class TMINERIA
{


    protected  $db;

    public  $ff_ini; 
    public  $ff_fin;
    
    public  $alertas_inusuales          = array();  
    public  $alertas_relevantes         = array();    
    public  $alertas_preocupantes       = array();  
    
    private $Minimo_Relevantes_Fisicas_USD              ;
    private $Minimo_Relevantes_Morales_USD              ;
    private $Minimo_Seguimiento_Fisicas_MX              ;
    private $Minimo_Seguimiento_Morales_MX              ;
    private $Pcnt_Minimo_Inusual_Rebasa_Pagos           ;
    private $Pcnt_Minimo_Inusual_Aumento_Ingresos       ;
    
    private $aPeriodos = array();
    
    private $aUltimoDiaPeriodo  = array();    
    
    private $aFechaCierreDivisa = array();
    
    private $tipo_cambio_usd     = array();
    private $tipo_cambio_usd_ord = array();
    
    private $num_periodos = 0;
    
    
    
//==========================================================================================================================================
//
//==========================================================================================================================================


function __construct(&$db, $ff_ini, $ff_fin)
{ 

    $this->TMINERIA($db, $ff_ini, $ff_fin);

}

//==========================================================================================================================================
//
//==========================================================================================================================================


function TMINERIA($db, $ff_ini, $ff_fin)
{
        $this->db = $db;
        $this->ff_ini = min($ff_ini, $ff_fin);
        $this->ff_fin = max($ff_ini, $ff_fin);

        $this->inicializa();  

}
//==========================================================================================================================================
// Inicializa :  Obtiene los valores ponderados para la matriz de riesgo
//==========================================================================================================================================

function inicializa()
{
        //-----------------------------------------------------------------------------------------------------
        // Verificar los valores entrada
        //-----------------------------------------------------------------------------------------------------

        $dd =  fdia($this->ff_ini);
        $mm =  fmes($this->ff_ini);
        $yy = fanio($this->ff_ini);


        if( (empty($this->ff_ini )) or (!checkdate($mm,$dd,$yy)))
        {
                error_msg("1) Una de las fechas del periodo es inválida (".$dd."/".$mm."/".$yy.") ");
                die("</BODY></HTML>");

        }
        $this->ff_ini  = date("Y-m-d",mktime(0,0,0,$mm,1,$yy));


        $_dd =  fdia($this->ff_fin);
        $_mm =  fmes($this->ff_fin);
        $_yy = fanio($this->ff_fin);

        if( (empty($this->ff_fin )) or (!checkdate($_mm,$_dd,$_yy)))
        {
                error_msg("2) Una de las fechas del periodo es inválida (".$_dd."/".$_mm."/".$_yy.") ");
                die("</BODY></HTML>");

        }
        //$this->ff_fin  = date("Y-m-t",mktime(0,0,0,$_mm,1,$_yy));

        $this->_ff_fin = date("Y-m-d",mktime(0,0,0,$_mm,1,$_yy));

        //----------------------------------------------------------------------------------------------------- 
        // Verificar la session del usuario
        //-----------------------------------------------------------------------------------------------------
        
        $sql = "SELECT   pld_parametros_configuracion.ID_User_Oficial_Cumplimiento,
                         pld_parametros_configuracion.Minimo_Relevantes_Fisicas_USD,
                         pld_parametros_configuracion.Minimo_Relevantes_Morales_USD, 
                         pld_parametros_configuracion.Minimo_Seguimiento_Fisicas_MX, 
                         pld_parametros_configuracion.Minimo_Seguimiento_Morales_MX, 
                         pld_parametros_configuracion.Pcnt_Minimo_Inusual_Rebasa_Pagos, 
                         pld_parametros_configuracion.Pcnt_Minimo_Inusual_Aumento_Ingresos,
                         
                         pld_parametros_configuracion.Min_Seguimiento_Especial_MX,
                         pld_parametros_configuracion.Min_Seguimiento_Especial_USD                       
                         

                FROM  pld_parametros_configuracion
                WHERE pld_parametros_configuracion.ID_Parametros = 1 ";
                
        $rs=$this->db->Execute($sql);
        
        if($rs->fields[0] != $_SESSION['ID_USR'])
        {
                error_msg(" Solo el Oficial de cumpliento está autorizado para utilizar este módulo. ");
                die("</BODY></HTML>");
        }
        
        //-----------------------------------------------------------------------------------------------------
        // Obtener los parámetros para las alertas
        //-----------------------------------------------------------------------------------------------------
        
        
        $this->Minimo_Relevantes_Fisicas_USD            = $rs->fields['Minimo_Relevantes_Fisicas_USD'];
        $this->Minimo_Relevantes_Morales_USD            = $rs->fields['Minimo_Relevantes_Morales_USD']; 

        $this->Minimo_Seguimiento_Fisicas_MX            = $rs->fields['Minimo_Seguimiento_Fisicas_MX']; 
        $this->Minimo_Seguimiento_Morales_MX            = $rs->fields['Minimo_Seguimiento_Morales_MX']; 

        $this->Pcnt_Minimo_Inusual_Rebasa_Pagos         = $rs->fields['Pcnt_Minimo_Inusual_Rebasa_Pagos']; 
        $this->Pcnt_Minimo_Inusual_Aumento_Ingresos     = $rs->fields['Pcnt_Minimo_Inusual_Aumento_Ingresos'];
        
        $this->Min_Seguimiento_Especial_MX              = $rs->fields['Min_Seguimiento_Especial_MX']; 
        $this->Min_Seguimiento_Especial_USD             = $rs->fields['Min_Seguimiento_Especial_USD'];
        

        //-----------------------------------------------------------------------------------------------------
        // Verificar que el periodo de tiempo sea válido
        //-----------------------------------------------------------------------------------------------------



        $this->aPeriodos = array();

        $this->aUltimoDiaPeriodo  = array();

        $this->aFechaCierreDivisa = array();

        $num_periodos = 0;
        for($i=$this->ff_ini; $i<$this->ff_fin; $i=date("Y-m-d",mktime(0,0,0,$mm,1,$yy)))
        {


                ++$num_periodos;

                if($num_periodos >= 13) break;

                if(($num_periodos > 0) and ($num_periodos < 13))
                {
                        $strNum = ($num_periodos <10)?("0".($num_periodos*1)):($num_periodos*1);

                        //-----------------------------------------------
                        // Para construir la consulta con el acumulado

                        $sum  = " SUM(IF(pld_pagos.Fecha BETWEEN '".$i."' and '".date("Y-m-t",mktime(0,0,0,$mm,1,$yy))."', pld_pagos.Monto_MX,  0)) As Periodo_".$strNum."_MX,\n";
                        $sum .= " SUM(IF(pld_pagos.Fecha BETWEEN '".$i."' and '".date("Y-m-t",mktime(0,0,0,$mm,1,$yy))."', pld_pagos.Monto_USD, 0)) As Periodo_".$strNum."_USD";
                        
                        $this->aPeriodos[] = $sum;
                        $this->aUltimoDiaPeriodo[]  = date("Y-m-t",mktime(0,0,0,$mm,1,$yy));


                        //----------------------------------------------------------------------------------------------                
                        // Inicializamos los valores de fechas que requerimos para el histórco de cambio de divisas
                        //----------------------------------------------------------------------------------------------                
                        $_FechaCierreDivisa = date("Y-m-t",mktime(0,0,0,($mm-1),1,$yy));

                        $this->aFechaCierreDivisa[] = $_FechaCierreDivisa;


                        $this->tipo_cambio_usd[$_FechaCierreDivisa] = 0.0;


                }

                ++$mm;
                
        

        }
        

        
        

        if($num_periodos == 0)
        {
                error_msg("El periodo mínimo que se puede analizar es de 1 mes.");
                die("</BODY></HTML>");

        }

        if($num_periodos > 12)
        {
                error_msg("El periodo más grande que se puede analizar es de 12 meses.");
                die("</BODY></HTML>");
        }


        $this->num_periodos = $num_periodos;


        //----------------------------------------------------------------------------------------------                
        // Validamos que existan los tipos de cambio de divisa en las fechas indicadas para poder realizar los cálculos de 
        // conversión a dólares.
        //
        // Se usa la fecha con la que cerró el día actual
        //----------------------------------------------------------------------------------------------                

        $listado_fechas =       implode("','",$this->aFechaCierreDivisa);


        $sql = " TRUNCATE TABLE pld_usd_periodo ";
        $this->db->Execute($sql);
        

        list($yy,$mm, $dd) =  explode("-",$this->ff_ini);
        $yy*=1;
        $mm*=1; 
        $dd*=1;
        $cont=0;
   
        $sql = "INSERT INTO pld_usd_periodo (Fecha) VALUES ";
        for($_fecha=$this->ff_ini; $_fecha<$this->ff_fin; $_fecha=date("Y-m-d",mktime(0,0,0,$mm,$dd,$yy)))
        {       
                if($_fecha < $this->ff_fin) {
                        $sql .= "('".$_fecha."'),";
                }
                $dd++;
                
                $cont++;
                if($cont>365) break;
        }

        //$sql .= "('".$this->ff_fin."') ";
        $sql .= ",";

        $sql = str_replace(",,", "", $sql);
         $rs=$this->db->Execute($sql);
        


/*
//=================================================================
// Obtenemos todas las fechas con el tipo de cambio y verficamos 
//=================================================================
*/

        $sql =" UPDATE pld_usd_periodo
                INNER JOIN 
                (

                        SELECT pld_importacion_tipocambio.Dia              AS Fecha,
                               pld_importacion_tipocambio_dtl.Valor_venta  AS Monto

                        FROM   pld_importacion_tipocambio,
                               pld_importacion_tipocambio_dtl

                        WHERE  pld_importacion_tipocambio.ID_Importacion = pld_importacion_tipocambio_dtl.ID_Importadtl
                           AND pld_importacion_tipocambio.Dia  BETWEEN '".$this->ff_ini."' and '".$this->ff_fin."'

                        ORDER BY pld_importacion_tipocambio.Dia ASC 
                        
                ) AS valor_cambiario
                  ON valor_cambiario.Fecha = pld_usd_periodo.Fecha

                SET pld_usd_periodo.Valor = valor_cambiario.Monto  ";


         $rs=$this->db->Execute($sql);



/*
//=================================================================
// Si hay NULL Faltan fechas por actulizar la divisa
//=================================================================
*/

        $sql =" SELECT Fecha,
                       Valor
                FROM pld_usd_periodo
                WHERE Valor IS NULL
                ORDER BY Fecha ";

         $rs=$this->db->Execute($sql);
         $error = 0;

         
        if($rs->_numOfRows > 0)
        {

                   while(! $rs->EOF)
                   {
                        $error ++;                 
                        $error_msg .= "<SPAN STYLE='color:red;'>".$error.") Unidad de cambio al ".ffecha($rs->fields[0])."</SPAN><br>\n";
                        $rs->MoveNext();
                   }

                          $msg = "<br>\n";
                          $msg .= "Error : Se requiere el valor cambiario del dolar USD en las fechas especificadas <br>";
                          $msg .= "para poder continuar.<br><br>";
                          $msg .= "Ingrese los valores solicitados en el catálogo de TIPO DE CAMBIO USD<br>";
                          $msg .= "En la sección de CATALOGOS PLD "."<DIR>".$error_msg."</DIR>";

                          error_msg($msg);

                          die("</BODY></HTML>");

        }

//=================================================================
//


        $sql =" SELECT Fecha,
                       Valor
                FROM pld_usd_periodo
                WHERE Valor IS NOT NULL
                ORDER BY Fecha ";

         $rs=$this->db->Execute($sql);

         $tipo_cambio_usd_ord = array();

         if($rs->_numOfRows)
            while(! $rs->EOF)
            {

                        $_FechaCierreDivisa = $rs->fields['Fecha'];

                        $this->tipo_cambio_usd[$_FechaCierreDivisa] = $rs->fields['Valor']*1; 

                        $this->tipo_cambio_usd_ord[] = $rs->fields['Valor']*1; 

                        $rs->MoveNext();
            }

        
}

//==========================================================================================================================================
// Analiza Datos :  Obtiene los valores para la emision de alertas
//==========================================================================================================================================

function mineria_datos_pld()
{

        //===================================================================================================
        // Cabecera del minería
        //===================================================================================================


        $sql = " TRUNCATE pld_mineria_datos ";
        $this->db->Execute($sql);



        $sql = "INSERT INTO pld_mineria_datos
                        (ID_Mineria, ID_Usuario,        Periodo_Ini, Periodo_Fin,       Num_Periodos)
                VALUES 
                        (1, ".$_SESSION['ID_USR'].",    '".$this->ff_ini."','".$this->ff_fin."' , ".$this->num_periodos.") ";

        $this->db->Execute($sql);


        //----------------------------

/*
        $sql="  UPDATE  pld_mineria_datos
                SET 
                        pld_mineria_datos.Tipo_Cambio_01 = '".(1*($this->tipo_cambio_usd_ord[0] ))."',
                        pld_mineria_datos.Tipo_Cambio_02 = '".(1*($this->tipo_cambio_usd_ord[1] ))."',
                        pld_mineria_datos.Tipo_Cambio_03 = '".(1*($this->tipo_cambio_usd_ord[2] ))."',
                        pld_mineria_datos.Tipo_Cambio_04 = '".(1*($this->tipo_cambio_usd_ord[3] ))."',
                        pld_mineria_datos.Tipo_Cambio_05 = '".(1*($this->tipo_cambio_usd_ord[4] ))."',
                        pld_mineria_datos.Tipo_Cambio_06 = '".(1*($this->tipo_cambio_usd_ord[5] ))."',
                        pld_mineria_datos.Tipo_Cambio_07 = '".(1*($this->tipo_cambio_usd_ord[6] ))."',
                        pld_mineria_datos.Tipo_Cambio_08 = '".(1*($this->tipo_cambio_usd_ord[7] ))."',
                        pld_mineria_datos.Tipo_Cambio_09 = '".(1*($this->tipo_cambio_usd_ord[8] ))."',
                        pld_mineria_datos.Tipo_Cambio_10 = '".(1*($this->tipo_cambio_usd_ord[9] ))."',
                        pld_mineria_datos.Tipo_Cambio_11 = '".(1*($this->tipo_cambio_usd_ord[10]))."',
                        pld_mineria_datos.Tipo_Cambio_12 = '".(1*($this->tipo_cambio_usd_ord[11]))."'

                WHERE pld_mineria_datos.ID_Mineria = 1 ";

        $this->db->Execute($sql);
*/







        //===================================================================================================
        // Todos los pagos con todo y coversión a DOLARES
        //===================================================================================================




        $sql="  TRUNCATE TABLE pld_pagos ";
        $this->db->Execute($sql);




        $sql="  INSERT INTO pld_pagos
                (ID_Pago, ID_Credito, Num_Compra, Fecha, Monto_MX, Monto_USD)
                (
                        SELECT pagos.ID_Pago,
                               fact_cliente.ID_Factura AS ID_Credito,
                               fact_cliente.Num_compra,
                               pagos.Fecha,
                               pagos.Monto AS Monto_MX,
                               IF(pld_usd_periodo.Valor<=0,0,(pagos.Monto/pld_usd_periodo.Valor)) AS Monto_USD

                        FROM fact_cliente

                        INNER JOIN pagos 
                                ON pagos.Num_compra = fact_cliente.num_compra
                               AND pagos.activo = 'S'

                        LEFT JOIN pld_usd_periodo
                                ON pld_usd_periodo.Fecha = pagos.Fecha


                        WHERE pagos.Fecha BETWEEN '".$this->ff_ini."' and '".$this->ff_fin."'

                        GROUP BY pagos.ID_Pago

                        ORDER BY pagos.ID_Pago
                ) ";
        $this->db->Execute($sql);

        //---------------------------------------------------------------------------------------------------
        // Verificar el tipo de pago solo Efectivo y Null nos interesan, cheques y transferencias no.
        //---------------------------------------------------------------------------------------------------

        $sql="  UPDATE pld_pagos
                        INNER JOIN tesoreria_aplicacion_pagos
                               ON tesoreria_aplicacion_pagos.ID_Pago    = pld_pagos.ID_Pago
                        SET pld_pagos.Tipo = tesoreria_aplicacion_pagos.Tipo ";
        $this->db->Execute($sql);


        $sql="  UPDATE pld_pagos
                        INNER JOIN tesoreria_aplicacion_pagos_dtl
                               ON tesoreria_aplicacion_pagos_dtl.ID_Pago    = pld_pagos.ID_Pago
                        INNER JOIN tesoreria_aplicacion_pagos
                                ON tesoreria_aplicacion_pagos.ID_Aplicacion =  tesoreria_aplicacion_pagos_dtl.ID_Aplicacion 
                        SET pld_pagos.Tipo = tesoreria_aplicacion_pagos.Tipo ";
                        
        $this->db->Execute($sql);
        
        
        
        
        $sql="  DELETE FROM pld_pagos WHERE pld_pagos.Tipo IN ('Transferencia','Cheque')  ";
        $this->db->Execute($sql);
        
        
        //---------------------------------------------------------------------------------------------------
        // Generámos la consulta en forma dinámica para que ponga los vlaores de los periodos en orden
        //===================================================================================================



        for( $i=($this->num_periodos+1); $i<=12; $i++)
        {       
                $strNum = ($i<10)?("0".($i*1)):($i*1);

                $cero  = " 0.0 AS Periodo_".$strNum."_MX,\n";
                $cero .= " 0.0 AS Periodo_".$strNum."_USD";
                
                $this->aPeriodos[] = $cero;             
                
        }




        $sql_concentrado = " INSERT INTO  pld_mineria_pagos_credito
                                (        ID_Factura,
                                         ID_Mineria,
                                         ID_Cliente,
                                         Num_Cliente,
                                         Regimen,
                                         Frecuencia,
                                         Monto_Cuota,
                                         Monto_Cuota_Mensualizada,
                                         Primer_Pago,
                                         Ultimo_Pago,
                                         Num_Total_Pagos,
                                         Monto_Total_Periodo,
                                         
                                         Periodo_01_MX,
                                         Periodo_01_USD,
                                         
                                         Periodo_02_MX,
                                         Periodo_02_USD,

                                         Periodo_03_MX,
                                         Periodo_03_USD,

                                         Periodo_04_MX,
                                         Periodo_04_USD,

                                         Periodo_05_MX,
                                         Periodo_05_USD,

                                         Periodo_06_MX,
                                         Periodo_06_USD,

                                         Periodo_07_MX,
                                         Periodo_07_USD,

                                         Periodo_08_MX,
                                         Periodo_08_USD,

                                         Periodo_09_MX,
                                         Periodo_09_USD,

                                         Periodo_10_MX,
                                         Periodo_10_USD,

                                         Periodo_11_MX,
                                         Periodo_11_USD,

                                         Periodo_12_MX,
                                         Periodo_12_USD,

                                         MAX_Monto_Periodo,
                                         MAX_Num_Periodo,
                                         MAX_Factor_Cuota
                             )   
                            (
                                        SELECT fact_cliente.ID_Factura          AS ID_Factura,
                                                1                               AS ID_Mineria,
                                               clientes.ID_Cliente              AS ID_Cliente,
                                               fact_cliente.Num_Cliente         AS Num_Cliente, 
                                               clientes.Regimen                 AS Regimen,
                                               fact_cliente.Vencimiento         AS Frecuencia,
                                               fact_cliente.Renta               AS Monto_Cuota,
                                               '0.0'                            AS Monto_Cuota_Mensualizada,
                                               
                                               MIN(pld_pagos.Fecha)                     AS Primer_Pago,
                                               MAX(pld_pagos.Fecha)                     AS Ultimo_Pago,
                                               COUNT(pld_pagos.ID_Pago)                 AS Num_Total_Pagos,
                                               SUM(pld_pagos.Monto_MX)                  AS Monto_Periodo, \n";



                foreach($this->aPeriodos AS $id=>$value)
                {
                        $sql_concentrado .= $value.",\n";                       
                }



        $sql_concentrado .= "           0.0 MAX_Monto_Periodo,
                                          0 MAX_Num_Periodo,
                                        0.0 MAX_Factor_Cuota    \n";


        $sql_concentrado .= "           FROM fact_cliente

                                        INNER JOIN clientes   
                                                ON clientes.Num_cliente = fact_cliente.num_cliente


                                        INNER JOIN pld_pagos  
                                                ON pld_pagos.ID_Credito = fact_cliente.id_factura 

                                        WHERE pld_pagos.Fecha BETWEEN '".$this->ff_ini."' and '".$this->ff_fin."' 

                                        GROUP BY fact_cliente.id_factura

                                        ORDER BY fact_cliente.num_cliente,
                                                 fact_cliente.id_factura 
                        ) ";


        //---------------------------------      
        // Concentrado de pagos
        //---------------------------------      

                $sql = " TRUNCATE pld_mineria_pagos_credito ";
                $this->db->Execute($sql);


                $this->db->Execute($sql_concentrado);


        //---------------------------------      
        // Cual máximo monto de pagos 
        // en todo el periodo
        //---------------------------------      


        $sql = " UPDATE pld_mineria_pagos_credito
                    SET pld_mineria_pagos_credito.MAX_Monto_Periodo 
                      = GREATEST(Periodo_01_MX, Periodo_02_MX, Periodo_03_MX, Periodo_04_MX, Periodo_05_MX, Periodo_06_MX, 
                                 Periodo_07_MX, Periodo_08_MX, Periodo_09_MX, Periodo_10_MX, Periodo_11_MX, Periodo_12_MX) ";

        $this->db->Execute($sql);      


        //---------------------------------      
        // Cual es el mes de periodo que 
        // tiene el mayor monto de pagos
        //---------------------------------      




        $sql = " UPDATE pld_mineria_pagos_credito
                 SET    pld_mineria_pagos_credito.MAX_Num_Periodo = 

                     CASE pld_mineria_pagos_credito.MAX_Monto_Periodo   

                             WHEN pld_mineria_pagos_credito.Periodo_01_MX THEN 01
                             WHEN pld_mineria_pagos_credito.Periodo_02_MX THEN 02
                             WHEN pld_mineria_pagos_credito.Periodo_03_MX THEN 03
                             WHEN pld_mineria_pagos_credito.Periodo_04_MX THEN 04
                             WHEN pld_mineria_pagos_credito.Periodo_05_MX THEN 05
                             WHEN pld_mineria_pagos_credito.Periodo_06_MX THEN 06
                             WHEN pld_mineria_pagos_credito.Periodo_07_MX THEN 07
                             WHEN pld_mineria_pagos_credito.Periodo_08_MX THEN 08   
                             WHEN pld_mineria_pagos_credito.Periodo_09_MX THEN 09   
                             WHEN pld_mineria_pagos_credito.Periodo_10_MX THEN 10   
                             WHEN pld_mineria_pagos_credito.Periodo_11_MX THEN 11   
                             WHEN pld_mineria_pagos_credito.Periodo_12_MX THEN 12 
                     END ";

        $this->db->Execute($sql);   



        //------------------------------------------------------------------      
        // Mensualizacion de CUOTA para creditos con periodos menores a un mes
        //------------------------------------------------------------------      

        $sql = "UPDATE pld_mineria_pagos_credito

                SET    pld_mineria_pagos_credito.Monto_Cuota_Mensualizada = pld_mineria_pagos_credito.Monto_Cuota

                WHERE  pld_mineria_pagos_credito.Frecuencia IN( 'Anios','Semestres','Trimestres','Bimestres','Meses') ";
        $this->db->Execute($sql);  



        $sql = "UPDATE pld_mineria_pagos_credito

                SET    pld_mineria_pagos_credito.Monto_Cuota_Mensualizada = 30 * (pld_mineria_pagos_credito.Monto_Cuota/15)

                WHERE  pld_mineria_pagos_credito.Frecuencia = 'Quincenas' ";
        $this->db->Execute($sql);  



        $sql = "UPDATE pld_mineria_pagos_credito

                SET    pld_mineria_pagos_credito.Monto_Cuota_Mensualizada = 30 * (pld_mineria_pagos_credito.Monto_Cuota/14)

                WHERE  pld_mineria_pagos_credito.Frecuencia = 'Catorcenas'  ";
        $this->db->Execute($sql);  



        $sql = "UPDATE pld_mineria_pagos_credito

                SET    pld_mineria_pagos_credito.Monto_Cuota_Mensualizada = 30 * (pld_mineria_pagos_credito.Monto_Cuota/7)

                WHERE  pld_mineria_pagos_credito.Frecuencia = 'Semanas'  ";
        $this->db->Execute($sql);  




        $sql = " UPDATE pld_mineria_pagos_credito

                    SET pld_mineria_pagos_credito.MAX_Factor_Cuota 

                          = ( pld_mineria_pagos_credito.MAX_Monto_Periodo/pld_mineria_pagos_credito.Monto_Cuota_Mensualizada ) * 100

                 WHERE pld_mineria_pagos_credito.Monto_Cuota_Mensualizada > 0 ";
        $this->db->Execute($sql);   







        for($i=1; $i <= $this->num_periodos; $i++)
        {

                $index = $i-1;
                
                $ffin = $this->aUltimoDiaPeriodo[$index];
                
                list($yy,$mm,$dd) = explode("-",$ffin);

                $fini = $yy."-".$mm."-01";
                
                $num_field =($i<10)?("0".$i):($i);


               $sql = " UPDATE pld_mineria_pagos_credito
                        INNER JOIN
                        (
                                SELECT   fact_cliente.ID_Factura        AS ID_Factura,
                                         MAX(pld_pagos.Fecha)           AS Ultimo_Pago
                                FROM fact_cliente
                                INNER JOIN pld_pagos        
                                        ON pld_pagos.ID_Credito = fact_cliente.id_factura 

                                WHERE pld_pagos.Fecha BETWEEN '".$fini."' and '".$ffin."'

                                GROUP BY fact_cliente.ID_Factura
                                ORDER BY fact_cliente.ID_Factura

                        ) AS max_pago_periodo
                          ON max_pago_periodo.ID_Factura = pld_mineria_pagos_credito.ID_Factura

                        SET pld_mineria_pagos_credito.Ultimo_Pago_".$num_field." = max_pago_periodo.Ultimo_Pago 
                        
                        ";
                $this->db->Execute($sql);  

        }
        
//debug($sql);
//die();


        //---------------------------------      
        // CONCENTRADO POR CLIENTE EN DOLARES
        //---------------------------------      

        $sql="  TRUNCATE pld_mineria_pagos_cliente_usd ";
        $rs=$this->db->Execute($sql);


        $sql="  INSERT INTO pld_mineria_pagos_cliente_usd
                (
                         ID_Cliente,     ID_Mineria,     Num_Cliente,    Regimen,       Primer_Pago,    Ultimo_Pago,    
                         
                         Num_Total_Pagos,       Num_Total_Creditos,

                         Periodo_MX_01, Periodo_MX_02, Periodo_MX_03, Periodo_MX_04, Periodo_MX_05, Periodo_MX_06,
                         Periodo_MX_07, Periodo_MX_08, Periodo_MX_09, Periodo_MX_10, Periodo_MX_11, Periodo_MX_12,


                         Periodo_USD_01, Periodo_USD_02, Periodo_USD_03, Periodo_USD_04, Periodo_USD_05, Periodo_USD_06,
                         Periodo_USD_07, Periodo_USD_08, Periodo_USD_09, Periodo_USD_10, Periodo_USD_11, Periodo_USD_12,
                         
                        
                         Ultimo_Pago_01, Ultimo_Pago_02, Ultimo_Pago_03, Ultimo_Pago_04, Ultimo_Pago_05, Ultimo_Pago_06,
                         Ultimo_Pago_07, Ultimo_Pago_08, Ultimo_Pago_09, Ultimo_Pago_10, Ultimo_Pago_11, Ultimo_Pago_12
                         
                         
                )
                (

                        SELECT  pld_mineria_pagos_credito.ID_Cliente                    AS ID_Cliente   ,
                                pld_mineria_pagos_credito.ID_Mineria                    AS ID_Mineria   ,
                                pld_mineria_pagos_credito.Num_Cliente                   AS Num_Cliente  ,
                                pld_mineria_pagos_credito.Regimen                       AS Regimen      ,
                                MIN(pld_mineria_pagos_credito.Primer_Pago)              AS Primer_Pago  ,
                                MAX(pld_mineria_pagos_credito.Ultimo_Pago)              AS Ultimo_Pago  ,
                                SUM(pld_mineria_pagos_credito.Num_Total_Pagos)          AS Num_Total_Pagos,
                                COUNT(pld_mineria_pagos_credito.ID_factura)             AS Num_Total_Creditos,


                                SUM(pld_mineria_pagos_credito.Periodo_01_MX)            AS Periodo_MX_01,
                                SUM(pld_mineria_pagos_credito.Periodo_02_MX)            AS Periodo_MX_02,
                                SUM(pld_mineria_pagos_credito.Periodo_03_MX)            AS Periodo_MX_03,
                                SUM(pld_mineria_pagos_credito.Periodo_04_MX)            AS Periodo_MX_04,
                                SUM(pld_mineria_pagos_credito.Periodo_05_MX)            AS Periodo_MX_05,
                                SUM(pld_mineria_pagos_credito.Periodo_06_MX)            AS Periodo_MX_06,
                                SUM(pld_mineria_pagos_credito.Periodo_07_MX)            AS Periodo_MX_07,
                                SUM(pld_mineria_pagos_credito.Periodo_08_MX)            AS Periodo_MX_08,
                                SUM(pld_mineria_pagos_credito.Periodo_09_MX)            AS Periodo_MX_09,
                                SUM(pld_mineria_pagos_credito.Periodo_10_MX)            AS Periodo_MX_10,
                                SUM(pld_mineria_pagos_credito.Periodo_11_MX)            AS Periodo_MX_11,
                                SUM(pld_mineria_pagos_credito.Periodo_12_MX)            AS Periodo_MX_12,

                                
                                SUM(pld_mineria_pagos_credito.Periodo_01_USD)           AS Periodo_USD_01,
                                SUM(pld_mineria_pagos_credito.Periodo_02_USD)           AS Periodo_USD_02,
                                SUM(pld_mineria_pagos_credito.Periodo_03_USD)           AS Periodo_USD_03,
                                SUM(pld_mineria_pagos_credito.Periodo_04_USD)           AS Periodo_USD_04,
                                SUM(pld_mineria_pagos_credito.Periodo_05_USD)           AS Periodo_USD_05,
                                SUM(pld_mineria_pagos_credito.Periodo_06_USD)           AS Periodo_USD_06,
                                SUM(pld_mineria_pagos_credito.Periodo_07_USD)           AS Periodo_USD_07,
                                SUM(pld_mineria_pagos_credito.Periodo_08_USD)           AS Periodo_USD_08,
                                SUM(pld_mineria_pagos_credito.Periodo_09_USD)           AS Periodo_USD_09,
                                SUM(pld_mineria_pagos_credito.Periodo_10_USD)           AS Periodo_USD_10,
                                SUM(pld_mineria_pagos_credito.Periodo_11_USD)           AS Periodo_USD_11,
                                SUM(pld_mineria_pagos_credito.Periodo_12_USD)           AS Periodo_USD_12,
                                
                                MAX(Ultimo_Pago_01) AS _Ultimo_Pago_01,
                                MAX(Ultimo_Pago_02) AS _Ultimo_Pago_02,
                                MAX(Ultimo_Pago_03) AS _Ultimo_Pago_03,
                                MAX(Ultimo_Pago_04) AS _Ultimo_Pago_04,
                                MAX(Ultimo_Pago_05) AS _Ultimo_Pago_05,
                                MAX(Ultimo_Pago_06) AS _Ultimo_Pago_06,
                                MAX(Ultimo_Pago_07) AS _Ultimo_Pago_07,
                                MAX(Ultimo_Pago_08) AS _Ultimo_Pago_08,
                                MAX(Ultimo_Pago_09) AS _Ultimo_Pago_09,
                                MAX(Ultimo_Pago_10) AS _Ultimo_Pago_10,
                                MAX(Ultimo_Pago_11) AS _Ultimo_Pago_11,
                                MAX(Ultimo_Pago_12) AS _Ultimo_Pago_12
                                                 
                        FROM pld_mineria_pagos_credito
                        
                        INNER JOIN pld_mineria_datos ON pld_mineria_datos.ID_Mineria = pld_mineria_pagos_credito.ID_Mineria

                        GROUP BY ID_Cliente
                        ORDER BY ID_Cliente 

                )  ";
         $rs=$this->db->Execute($sql);


         //---------------------------------      
         // Cual es el mes de periodo que 
         // tiene el mayor monto de pagos
         // por cliente
         //---------------------------------      


        $sql="  UPDATE pld_mineria_pagos_cliente_usd
                   SET pld_mineria_pagos_cliente_usd.MAX_Monto_Periodo 
                       = GREATEST(Periodo_USD_01, Periodo_USD_02, Periodo_USD_03, Periodo_USD_04, Periodo_USD_05, Periodo_USD_06, 
                                  Periodo_USD_07, Periodo_USD_08, Periodo_USD_09, Periodo_USD_10, Periodo_USD_11, Periodo_USD_12) ";


         $this->db->Execute($sql);




        $sql="   UPDATE pld_mineria_pagos_cliente_usd
                 SET    pld_mineria_pagos_cliente_usd.MAX_Num_Periodo = 

                     CASE pld_mineria_pagos_cliente_usd.MAX_Monto_Periodo   

                             WHEN pld_mineria_pagos_cliente_usd.Periodo_USD_01 THEN 01
                             WHEN pld_mineria_pagos_cliente_usd.Periodo_USD_02 THEN 02
                             WHEN pld_mineria_pagos_cliente_usd.Periodo_USD_03 THEN 03
                             WHEN pld_mineria_pagos_cliente_usd.Periodo_USD_04 THEN 04
                             WHEN pld_mineria_pagos_cliente_usd.Periodo_USD_05 THEN 05
                             WHEN pld_mineria_pagos_cliente_usd.Periodo_USD_06 THEN 06
                             WHEN pld_mineria_pagos_cliente_usd.Periodo_USD_07 THEN 07
                             WHEN pld_mineria_pagos_cliente_usd.Periodo_USD_08 THEN 08   
                             WHEN pld_mineria_pagos_cliente_usd.Periodo_USD_09 THEN 09   
                             WHEN pld_mineria_pagos_cliente_usd.Periodo_USD_10 THEN 10   
                             WHEN pld_mineria_pagos_cliente_usd.Periodo_USD_11 THEN 11   
                             WHEN pld_mineria_pagos_cliente_usd.Periodo_USD_12 THEN 12 
                     END ";


          $this->db->Execute($sql);





         //===========================================================================================     
         // ALERTA : Concentrado de Perfil transaccional
         //===========================================================================================  
         
         

       $sql="   UPDATE pld_mineria_pagos_cliente_usd,
                       pld_perfil_transaccional

                SET    pld_mineria_pagos_cliente_usd.Perfil_Transaccional_Vigente  = pld_perfil_transaccional.Ultima_Actualizacion,
                       pld_mineria_pagos_cliente_usd.Ingresos_Reportados_Vigente   = pld_perfil_transaccional.Ingresos_Netos_Mes

                WHERE  pld_mineria_pagos_cliente_usd.ID_Cliente = pld_perfil_transaccional.ID_Cliente ";
          $this->db->Execute($sql);



       $sql="   UPDATE pld_mineria_pagos_cliente_usd,
                       pld_perfil_transaccional_log

                SET    pld_mineria_pagos_cliente_usd.Perfil_Transaccional_Vigente  = pld_perfil_transaccional_log.Ultima_Actualizacion,
                       pld_mineria_pagos_cliente_usd.Ingresos_Reportados_Vigente   = pld_perfil_transaccional_log.Ingresos_Netos_Mes

                WHERE  pld_mineria_pagos_cliente_usd.ID_Cliente = pld_perfil_transaccional_log.ID_Cliente AND
                       pld_perfil_transaccional_log.Historic_ord = '1' ";

          $this->db->Execute($sql);
       
  

}

//==========================================================================================================================================
// Analiza Datos :  Obtiene los registros de todas las alertas
//==========================================================================================================================================

function obtener_alertas()
{
        //+----------------------+--------------------------------------------------
        //|     KEY              |      SIGNIFICADO
        //+----------------------+--------------------------------------------------
        //| ID                   | Autonumerico
        //| PERIODO              | Periodo
        //| TIPO                 | TipoAlerta (Inusual, Relevante, Preocupante)
        //| IDC                  | ID_Credito
        //| MONTO                | Monto
        //| FECHA                | Fecha_Operacion
        //| NACIONALIDAD         | Nacionalidad
        //| REGIMEN              | Regimen Fiscal
        //| NOMBRE               | Nombre
        //| AP_PATERNO           | AP_Paterno
        //| AP_MATERNO           | AP_Materno
        //| RFC                  | RFC 
        //| CURP                 | CURP
        //| MOTIVO               | Motivo_Alerta
       
        $this->alertas_inusuales    = $this->buscar_actividad_inusual();        
        $this->alertas_relevantes   = $this->buscar_actividad_relevante();      
        $this->alertas_preocupantes = $this->buscar_actividad_preocupante();    



        if(count($this->alertas_inusuales) > 0 )
        {
                $this->registra_alertas_inusuales();
        }


        if(count($this->alertas_relevantes) > 0 )
        {
                $this->registra_alertas_relevantes();
        }





}

//==========================================================================================================================================
// ALERTAS INUSUALES
//==========================================================================================================================================

function buscar_actividad_inusual()
{
        $alerta = array();
        $ID=0;
        
        //---------------------------------------------------------------------------------------
        // Créditos cuyos pagos exceden mucho el porcentaje de pago respecto a su couta  
        //---------------------------------------------------------------------------------------



           $factor = 1+($this->Pcnt_Minimo_Inusual_Rebasa_Pagos/100);


       $sql="  SELECT    clientes.ID_Cliente,
                         pld_mineria_pagos_credito.ID_Factura,
                         pld_mineria_pagos_credito.Monto_Cuota,
                         pld_mineria_pagos_credito.Frecuencia,
                         pld_mineria_pagos_credito.Monto_Cuota_Mensualizada,
                         pld_mineria_pagos_credito.MAX_Monto_Periodo,
                         pld_mineria_pagos_credito.MAX_Num_Periodo,

                         @Fecha_Mov := CASE ( pld_mineria_pagos_credito.MAX_Num_Periodo )
                                                 WHEN  1  THEN pld_mineria_pagos_credito.Ultimo_Pago_01
                                                 WHEN  2  THEN pld_mineria_pagos_credito.Ultimo_Pago_02
                                                 WHEN  3  THEN pld_mineria_pagos_credito.Ultimo_Pago_03
                                                 WHEN  4  THEN pld_mineria_pagos_credito.Ultimo_Pago_04
                                                 WHEN  5  THEN pld_mineria_pagos_credito.Ultimo_Pago_05
                                                 WHEN  6  THEN pld_mineria_pagos_credito.Ultimo_Pago_06
                                                 WHEN  7  THEN pld_mineria_pagos_credito.Ultimo_Pago_07
                                                 WHEN  8  THEN pld_mineria_pagos_credito.Ultimo_Pago_08
                                                 WHEN  9  THEN pld_mineria_pagos_credito.Ultimo_Pago_09
                                                 WHEN  10 THEN pld_mineria_pagos_credito.Ultimo_Pago_10
                                                 WHEN  11 THEN pld_mineria_pagos_credito.Ultimo_Pago_11
                                                 WHEN  12 THEN pld_mineria_pagos_credito.Ultimo_Pago_12 
                         END AS Fecha_Mov,

                        
                         MONTH(@Fecha_Mov)      AS MES_PERIODO,
                         YEAR( @Fecha_Mov)      AS ANIO_PERIODO,



                         IFNULL(solicitud_plvd.Nacionalidad,'MEXICANA') AS Nacionalidad,


                         clientes.Regimen       AS Regimen,

                 
                         CONCAT(solicitud_plvd.Nombre,' ',solicitud_plvd.NombreI) AS Nombre,
                                solicitud_plvd.Ap_paterno,
                                solicitud_plvd.Ap_materno


                FROM pld_mineria_pagos_credito
                LEFT JOIN clientes       ON clientes.ID_Cliente         = pld_mineria_pagos_credito.ID_Cliente
                LEFT JOIN solicitud_plvd ON solicitud_plvd.ID_Solicitud = clientes.ID_Solicitud

                WHERE pld_mineria_pagos_credito.MAX_Monto_Periodo > 0 AND
                      (pld_mineria_pagos_credito.Monto_Cuota_Mensualizada * (".($factor).") <  pld_mineria_pagos_credito.MAX_Monto_Periodo )
                        
                        
                        ";


         $rs=$this->db->Execute($sql);
        

        if($rs->_numOfRows)
           while(! $rs->EOF)
           {
                $alerta[$ID]['MES_PERIODO']             = $rs->fields['MES_PERIODO'];
                $alerta[$ID]['ANIO_PERIODO']            = $rs->fields['ANIO_PERIODO'];
                
                $alerta[$ID]['TIPO']                    = 'Inusual';
                $alerta[$ID]['IDC']                     = $rs->fields['ID_Factura'];
                $alerta[$ID]['ID_CLIENTE']              = $rs->fields['ID_Cliente'];
                
                $alerta[$ID]['MONTO']                   = $rs->fields['MAX_Monto_Periodo'];
                $alerta[$ID]['MONTO_US']                = '0';
                
                
                $alerta[$ID]['FECHA']                   = $rs->fields['Fecha_Mov'];
                $alerta[$ID]['NACIONALIDAD']            = $rs->fields['Nacionalidad'];
                $alerta[$ID]['REGIMEN']                 = $rs->fields['Regimen'];

                $alerta[$ID]['NOMBRE']                  = $rs->fields['Nombre'];
                $alerta[$ID]['AP_PATERNO']              = $rs->fields['Ap_paterno'];
                $alerta[$ID]['AP_MATERNO']              = $rs->fields['Ap_materno'];


                $alerta[$ID]['NOMBRE_COMPLETO']         = $rs->fields['Nombre_Completo'];
                $alerta[$ID]['MOTIVO']                  = "El cliente presenta pagos acumulados que exceden por ".number_format(($this->Pcnt_Minimo_Inusual_Rebasa_Pagos/100),2)." veces o más la cuota mensual de su crédito.";




                $ID++;

             $rs->MoveNext();
           }
        
        //---------------------------------------------------------------------------------------
        // Créditos que entre su perfil transaccional más reciente y el anterior aumentraon mucho 
        // sus ingresos  
        //---------------------------------------------------------------------------------------



       $sql="  SELECT   pld_mineria_pagos_cliente_usd.ID_Cliente,
                        pld_mineria_pagos_credito.ID_Factura,

                        YEAR(pld_mineria_pagos_cliente_usd.Perfil_Transaccional_Vigente)   AS ANIO_PERIODO,
                        MONTH(pld_mineria_pagos_cliente_usd.Perfil_Transaccional_Vigente)  AS MES_PERIODO,
                        
                        pld_mineria_pagos_cliente_usd.Perfil_Transaccional_Anterior,
                        pld_mineria_pagos_cliente_usd.Ingresos_Reportados_Anterior,

                        pld_mineria_pagos_cliente_usd.Perfil_Transaccional_Vigente,
                        pld_mineria_pagos_cliente_usd.Ingresos_Reportados_Vigente,

                        (DATEDIFF(now(), pld_mineria_pagos_cliente_usd.Perfil_Transaccional_Vigente)/(".($this->Pcnt_Minimo_Inusual_Aumento_Ingresos).")) AS retraso,

                        IF((Ingresos_Reportados_Anterior > Ingresos_Reportados_Vigente),0,(100*((Ingresos_Reportados_Vigente/Ingresos_Reportados_Anterior)-1))) Aumento_Ingresos,

                        IFNULL(solicitud_plvd.Nacionalidad,'MEXICANA') AS Nacionalidad,


                        clientes.Regimen        AS Regimen,


                        CONCAT( solicitud_plvd.Nombre,' ',solicitud_plvd.NombreI,' ',solicitud_plvd.Ap_paterno,' ',solicitud_plvd.Ap_materno) AS Nombre_Completo,
                        
                        CONCAT( solicitud_plvd.Nombre,' ',solicitud_plvd.NombreI) AS Nombre,
                        solicitud_plvd.Ap_paterno,
                        solicitud_plvd.Ap_materno
                        
                        


                FROM pld_mineria_pagos_cliente_usd

                INNER JOIN pld_mineria_pagos_credito    ON  pld_mineria_pagos_cliente_usd.ID_Cliente    = pld_mineria_pagos_credito.ID_Cliente
                LEFT JOIN clientes                      ON clientes.ID_Cliente                          = pld_mineria_pagos_cliente_usd.ID_Cliente
                LEFT JOIN solicitud_plvd                ON solicitud_plvd.ID_Solicitud                  = clientes.ID_Solicitud

                WHERE     pld_mineria_pagos_cliente_usd.Perfil_Transaccional_Anterior IS NOT NULL
                      AND Ingresos_Reportados_Anterior > 0

                HAVING Aumento_Ingresos > ".($this->Pcnt_Minimo_Inusual_Aumento_Ingresos)." ";

         $rs=$this->db->Execute($sql);
        

        if($rs->_numOfRows)
           while(! $rs->EOF)
           {
                $alerta[$ID]['MES_PERIODO']             = $rs->fields['MES_PERIODO'];
                $alerta[$ID]['ANIO_PERIODO']            = $rs->fields['ANIO_PERIODO'];

                $alerta[$ID]['TIPO']                    = 'Inusual';
                $alerta[$ID]['IDC']                     = $rs->fields['ID_Factura'];
                $alerta[$ID]['ID_CLIENTE']              = $rs->fields['ID_Cliente'];

                $alerta[$ID]['MONTO']                   = ""; //$rs->fields['Aumento_Ingresos'];
                $alerta[$ID]['MONTO_US']                = "";
                
                
                
                $alerta[$ID]['FECHA']                   = $rs->fields['Perfil_Transaccional_Vigente'];
                $alerta[$ID]['NACIONALIDAD']            = $rs->fields['Nacionalidad'];
                $alerta[$ID]['REGIMEN']                 = $rs->fields['Regimen'];

                $alerta[$ID]['NOMBRE']                  = $rs->fields['Nombre'];
                $alerta[$ID]['AP_PATERNO']              = $rs->fields['Ap_paterno'];
                $alerta[$ID]['AP_MATERNO']              = $rs->fields['Ap_materno'];


                $alerta[$ID]['NOMBRE_COMPLETO']         = $rs->fields['Nombre_Completo'];
                $alerta[$ID]['MOTIVO']                  = "El cliente presenta un aumento de ingresos igual o superior al ".number_format(($this->Pcnt_Minimo_Inusual_Aumento_Ingresos),2)."% ";

                $ID++;

             $rs->MoveNext();
           }


        //---------------------------------------------------------------------------------------
        // Motiva sospechas de actividades ilícitas
        //---------------------------------------------------------------------------------------


       $sql="   SELECT  pld_perfil_transaccional.ID_Cliente,
                        pld_mineria_pagos_credito.ID_Factura,
                        
                        YEAR( pld_perfil_transaccional.Ultima_Actualizacion)   AS ANIO_PERIODO, 
                        MONTH(pld_perfil_transaccional.Ultima_Actualizacion)   AS MES_PERIODO,
                        
                        
                        DATE(pld_perfil_transaccional.Ultima_Actualizacion) AS Perfil_Transaccional_Vigente,
                        IFNULL(solicitud_plvd.Nacionalidad,'MEXICANA') AS Nacionalidad,

                        clientes.Regimen        AS Regimen,


                        CONCAT( solicitud_plvd.Nombre,' ',solicitud_plvd.NombreI,' ',solicitud_plvd.Ap_paterno,' ',solicitud_plvd.Ap_materno) AS Nombre_Completo,
                        CONCAT( solicitud_plvd.Nombre,' ',solicitud_plvd.NombreI) AS Nombre,
                        solicitud_plvd.Ap_paterno,
                        solicitud_plvd.Ap_materno,
                        CONCAT( 'Motiva Sospechas de Act. Ilicitas : ',pld_cat_operaciones_inusuales.Descripcion) AS Motivo

                FROM        pld_perfil_transaccional

                INNER JOIN  clientes 
                        ON  clientes.ID_Cliente = pld_perfil_transaccional.ID_Cliente

                INNER JOIN  solicitud_plvd
                        ON  solicitud_plvd.ID_Solicitud = clientes.ID_Solicitud

                LEFT JOIN  pld_mineria_pagos_credito 
                        ON  pld_mineria_pagos_credito.ID_Cliente = pld_perfil_transaccional.ID_Cliente

                LEFT JOIN  pld_cat_operaciones_inusuales 
                       ON   pld_cat_operaciones_inusuales.ID_Tipo_Operacion_Inusual = pld_perfil_transaccional.ID_Tipo_Operacion_Inusual

                WHERE       pld_perfil_transaccional.Motiva_Sospechas_Act_Ilicitas = 'Si' ";



         $rs=$this->db->Execute($sql);
        

        if($rs->_numOfRows)
           while(! $rs->EOF)
           {
                $alerta[$ID]['MES_PERIODO']             = $rs->fields['MES_PERIODO'];
                $alerta[$ID]['ANIO_PERIODO']            = $rs->fields['ANIO_PERIODO'];
                
                
                
                $alerta[$ID]['TIPO']                    = 'Inusual';
                $alerta[$ID]['IDC']                     = $rs->fields['ID_Factura'];
                $alerta[$ID]['ID_CLIENTE']              = $rs->fields['ID_Cliente'];

                $alerta[$ID]['MONTO']                   = "";
                $alerta[$ID]['MONTO_US']                = "";

                $alerta[$ID]['FECHA']                   = $rs->fields['Perfil_Transaccional_Vigente'];
                $alerta[$ID]['NACIONALIDAD']            = $rs->fields['Nacionalidad'];
                $alerta[$ID]['REGIMEN']                 = $rs->fields['Regimen'];

                $alerta[$ID]['NOMBRE']                  = $rs->fields['Nombre'];
                $alerta[$ID]['AP_PATERNO']              = $rs->fields['Ap_paterno'];
                $alerta[$ID]['AP_MATERNO']              = $rs->fields['Ap_materno'];
                
                
                $alerta[$ID]['NOMBRE_COMPLETO']         = $rs->fields['Nombre_Completo'];
                $alerta[$ID]['MOTIVO']                  = $rs->fields['Motivo'];

                $ID++;

             $rs->MoveNext();
           }


        //---------------------------------------------------------------------------------------
        // Terrorismo 
        //---------------------------------------------------------------------------------------


       $sql="   SELECT  pld_perfil_transaccional.ID_Cliente,
                        pld_mineria_pagos_credito.ID_Factura,

                        YEAR(pld_perfil_transaccional.Ultima_Actualizacion ) AS ANIO_PERIODO,
                        MONTH(pld_perfil_transaccional.Ultima_Actualizacion) AS MES_PERIODO,
                        
                        DATE(pld_perfil_transaccional.Ultima_Actualizacion) AS Perfil_Transaccional_Vigente,
                        IFNULL(solicitud_plvd.Nacionalidad,'MEXICANA') AS Nacionalidad,

                        clientes.Regimen        AS Regimen,


                        CONCAT( solicitud_plvd.Nombre,' ',solicitud_plvd.NombreI,' ',solicitud_plvd.Ap_paterno,' ',solicitud_plvd.Ap_materno) AS Nombre_Completo,
                        
                        CONCAT( solicitud_plvd.Nombre,' ',solicitud_plvd.NombreI ) AS Nombre,
                        solicitud_plvd.Ap_paterno,
                        solicitud_plvd.Ap_materno,
                        
                        CONCAT( 'Vinculado al terrorismo internacional u org. criminales') AS Motivo


                FROM        pld_perfil_transaccional

                INNER JOIN  clientes 
                        ON  clientes.ID_Cliente = pld_perfil_transaccional.ID_Cliente

                INNER JOIN  solicitud_plvd
                        ON  solicitud_plvd.ID_Solicitud = clientes.ID_Solicitud

                 LEFT JOIN  pld_mineria_pagos_credito 
                        ON  pld_mineria_pagos_credito.ID_Cliente = pld_perfil_transaccional.ID_Cliente

                WHERE pld_perfil_transaccional.Vinculado_Terrorismo_u_Org_Criminales  = 'Si'    ";


         $rs=$this->db->Execute($sql);
        

        if($rs->_numOfRows)
           while(! $rs->EOF)
           {

                $alerta[$ID]['MES_PERIODO']             = $rs->fields['MES_PERIODO'];
                $alerta[$ID]['ANIO_PERIODO']            = $rs->fields['ANIO_PERIODO'];

                $alerta[$ID]['TIPO']                    = 'Inusual';
                $alerta[$ID]['IDC']                     = $rs->fields['ID_Factura'];
                $alerta[$ID]['ID_CLIENTE']              = $rs->fields['ID_Cliente'];

                $alerta[$ID]['MONTO']                   = "";
                $alerta[$ID]['MONTO_US']                = "";

                $alerta[$ID]['FECHA']                   = $rs->fields['Perfil_Transaccional_Vigente'];
                $alerta[$ID]['NACIONALIDAD']            = $rs->fields['Nacionalidad'];
                $alerta[$ID]['REGIMEN']                 = $rs->fields['Regimen'];
                $alerta[$ID]['NOMBRE_COMPLETO']         = $rs->fields['Nombre_Completo'];

                $alerta[$ID]['NOMBRE']                  = $rs->fields['Nombre'];
                $alerta[$ID]['AP_PATERNO']              = $rs->fields['Ap_paterno'];
                $alerta[$ID]['AP_MATERNO']              = $rs->fields['Ap_materno'];

                $alerta[$ID]['MOTIVO']                  = $rs->fields['Motivo'];

                $ID++;

             $rs->MoveNext();
           }


        
        //---------------------------------------------------------------------------------------
        // Paraisos Fiscales 
        //---------------------------------------------------------------------------------------


       $sql="   SELECT  pld_perfil_transaccional.ID_Cliente,
                        pld_mineria_pagos_credito.ID_Factura,
                        
                        YEAR(pld_perfil_transaccional.Ultima_Actualizacion)   AS ANIO_PERIODO,
                        MONTH(pld_perfil_transaccional.Ultima_Actualizacion)  AS MES_PERIODO,
                        
                        DATE(pld_perfil_transaccional.Ultima_Actualizacion) AS Perfil_Transaccional_Vigente,
                        IFNULL(solicitud_plvd.Nacionalidad,'MEXICANA') AS Nacionalidad,

                        clientes.Regimen        AS Regimen,

                        CONCAT( solicitud_plvd.Nombre,' ',solicitud_plvd.NombreI,' ',solicitud_plvd.Ap_paterno,' ',solicitud_plvd.Ap_materno) AS Nombre_Completo,

                        CONCAT( solicitud_plvd.Nombre,' ',solicitud_plvd.NombreI) AS Nombre,
                        solicitud_plvd.Ap_paterno,
                        solicitud_plvd.Ap_materno,


                        CONCAT( 'Vinculado a paraisos fiscales ') AS Motivo


                FROM        pld_perfil_transaccional

                INNER JOIN  clientes 
                        ON  clientes.ID_Cliente = pld_perfil_transaccional.ID_Cliente

                INNER JOIN  solicitud_plvd
                        ON  solicitud_plvd.ID_Solicitud = clientes.ID_Solicitud

                 LEFT JOIN  pld_mineria_pagos_credito 
                        ON  pld_mineria_pagos_credito.ID_Cliente = pld_perfil_transaccional.ID_Cliente

                WHERE pld_perfil_transaccional.Vinculado_Paraisos_Fiscales  = 'Si'      ";


         $rs=$this->db->Execute($sql);
        

        if($rs->_numOfRows)
           while(! $rs->EOF)
           {
                $alerta[$ID]['MES_PERIODO']             = $rs->fields['MES_PERIODO'];
                $alerta[$ID]['ANIO_PERIODO']            = $rs->fields['ANIO_PERIODO'];

                $alerta[$ID]['TIPO']                    = 'Inusual';
                $alerta[$ID]['IDC']                     = $rs->fields['ID_Factura'];
                $alerta[$ID]['ID_CLIENTE']              = $rs->fields['ID_Cliente'];

                $alerta[$ID]['MONTO']                   = "";
                $alerta[$ID]['MONTO_US']                = "";

                $alerta[$ID]['FECHA']                   = $rs->fields['Perfil_Transaccional_Vigente'];
                $alerta[$ID]['NACIONALIDAD']            = $rs->fields['Nacionalidad'];
                $alerta[$ID]['REGIMEN']                 = $rs->fields['Regimen'];
                $alerta[$ID]['NOMBRE_COMPLETO']         = $rs->fields['Nombre_Completo'];

                $alerta[$ID]['NOMBRE']                  = $rs->fields['Nombre'];
                $alerta[$ID]['AP_PATERNO']              = $rs->fields['Ap_paterno'];
                $alerta[$ID]['AP_MATERNO']              = $rs->fields['Ap_materno'];
                
                
                
                $alerta[$ID]['MOTIVO']                  = $rs->fields['Motivo'];

                $ID++;

             $rs->MoveNext();
           }
        
        

        
        //---------------------------------------------------------------------------------------
        // Persona Politicamente Expuesta Extrangera
        //---------------------------------------------------------------------------------------

        
        
        
        
       $sql="   SELECT  pld_perfil_transaccional.ID_Cliente,    
                        pld_mineria_pagos_credito.ID_Factura,   

                        YEAR(pld_perfil_transaccional.Ultima_Actualizacion)   AS ANIO_PERIODO,
                        MONTH(pld_perfil_transaccional.Ultima_Actualizacion)  AS MES_PERIODO,


                        DATE(pld_perfil_transaccional.Ultima_Actualizacion) AS Perfil_Transaccional_Vigente,    
                        IFNULL(solicitud_plvd.Nacionalidad,'MEXICANA') AS Nacionalidad, 

                        clientes.Regimen        AS Regimen,
        
                        CONCAT( solicitud_plvd.Nombre,' ',solicitud_plvd.NombreI,' ',solicitud_plvd.Ap_paterno,' ',solicitud_plvd.Ap_materno) AS Nombre_Completo,
                        CONCAT( solicitud_plvd.Nombre,' ',solicitud_plvd.NombreI) AS Nombre,
                        solicitud_plvd.Ap_paterno,
                        solicitud_plvd.Ap_materno,
                        
                        CONCAT( 'Persona Politicamente Expuesta Extranjera ') AS Motivo
                    
                    
        FROM        pld_perfil_transaccional
        
        INNER JOIN  clientes 
                ON  clientes.ID_Cliente = pld_perfil_transaccional.ID_Cliente
        
        INNER JOIN  solicitud_plvd
                ON  solicitud_plvd.ID_Solicitud = clientes.ID_Solicitud
        
        LEFT JOIN  pld_mineria_pagos_credito 
                ON  pld_mineria_pagos_credito.ID_Cliente = pld_perfil_transaccional.ID_Cliente
        
        
        
        WHERE pld_perfil_transaccional.Persona_Pol_Expuesta_Extranjera = 'Si' ";
        
        

         $rs=$this->db->Execute($sql);
        

        if($rs->_numOfRows)
           while(! $rs->EOF)
           {
                $alerta[$ID]['MES_PERIODO']             = $rs->fields['MES_PERIODO'];
                $alerta[$ID]['ANIO_PERIODO']            = $rs->fields['ANIO_PERIODO'];
                $alerta[$ID]['TIPO']                    = 'Inusual';
                $alerta[$ID]['IDC']                     = $rs->fields['ID_Factura'];
                $alerta[$ID]['ID_CLIENTE']              = $rs->fields['ID_Cliente'];

                $alerta[$ID]['MONTO']                   = "";
                $alerta[$ID]['MONTO_US']                = "";


                $alerta[$ID]['FECHA']                   = $rs->fields['Perfil_Transaccional_Vigente'];
                $alerta[$ID]['NACIONALIDAD']            = $rs->fields['Nacionalidad'];
                $alerta[$ID]['REGIMEN']                 = $rs->fields['Regimen'];
                $alerta[$ID]['NOMBRE_COMPLETO']         = $rs->fields['Nombre_Completo'];

                $alerta[$ID]['NOMBRE']                  = $rs->fields['Nombre'];
                $alerta[$ID]['AP_PATERNO']              = $rs->fields['Ap_paterno'];
                $alerta[$ID]['AP_MATERNO']              = $rs->fields['Ap_materno'];

                $alerta[$ID]['MOTIVO']                  = $rs->fields['Motivo'];

                $ID++;

             $rs->MoveNext();
           }

                
        //$this->showarray($alerta);

        return $alerta;

}
//==========================================================================================================================================
// ALERTAS RELEVANTES
//==========================================================================================================================================

function buscar_actividad_relevante()
{
        $alerta = array();
        $ID=0;
        
        //---------------------------------------------------------------------------------------
        // Operaciones acumuladsas por 10,000 US 
        //---------------------------------------------------------------------------------------

// $this->Minimo_Relevantes_Morales_USD = 1000;
// $this->Minimo_Relevantes_Fisicas_USD = 1000;

       $sql="   SELECT  clientes.ID_Cliente                                                             AS ID_Cliente,
                        pld_mineria_pagos_credito.ID_Factura                                            AS ID_Factura,


                        pld_mineria_pagos_cliente_usd.MAX_Monto_Periodo                                 AS MAX_Monto_Periodo_US,

                        CASE ( pld_mineria_pagos_cliente_usd.MAX_Num_Periodo )
                                        WHEN 1  THEN pld_mineria_pagos_cliente_usd.Periodo_MX_01
                                        WHEN 2  THEN pld_mineria_pagos_cliente_usd.Periodo_MX_02
                                        WHEN 3  THEN pld_mineria_pagos_cliente_usd.Periodo_MX_03
                                        WHEN 4  THEN pld_mineria_pagos_cliente_usd.Periodo_MX_04
                                        WHEN 5  THEN pld_mineria_pagos_cliente_usd.Periodo_MX_05
                                        WHEN 6  THEN pld_mineria_pagos_cliente_usd.Periodo_MX_06
                                        WHEN 7  THEN pld_mineria_pagos_cliente_usd.Periodo_MX_07
                                        WHEN 8  THEN pld_mineria_pagos_cliente_usd.Periodo_MX_08
                                        WHEN 9  THEN pld_mineria_pagos_cliente_usd.Periodo_MX_09
                                        WHEN 10 THEN pld_mineria_pagos_cliente_usd.Periodo_MX_10
                                        WHEN 11 THEN pld_mineria_pagos_cliente_usd.Periodo_MX_11
                                        WHEN 12 THEN pld_mineria_pagos_cliente_usd.Periodo_MX_12
                        END                                                                             AS MAX_Monto_Periodo_MX,


                        @Fecha_Mov := CASE ( pld_mineria_pagos_cliente_usd.MAX_Num_Periodo )
                                         WHEN  1  THEN pld_mineria_pagos_cliente_usd.Ultimo_Pago_01
                                         WHEN  2  THEN pld_mineria_pagos_cliente_usd.Ultimo_Pago_02
                                         WHEN  3  THEN pld_mineria_pagos_cliente_usd.Ultimo_Pago_03
                                         WHEN  4  THEN pld_mineria_pagos_cliente_usd.Ultimo_Pago_04
                                         WHEN  5  THEN pld_mineria_pagos_cliente_usd.Ultimo_Pago_05
                                         WHEN  6  THEN pld_mineria_pagos_cliente_usd.Ultimo_Pago_06
                                         WHEN  7  THEN pld_mineria_pagos_cliente_usd.Ultimo_Pago_07
                                         WHEN  8  THEN pld_mineria_pagos_cliente_usd.Ultimo_Pago_08
                                         WHEN  9  THEN pld_mineria_pagos_cliente_usd.Ultimo_Pago_09
                                         WHEN  10 THEN pld_mineria_pagos_cliente_usd.Ultimo_Pago_10
                                         WHEN  11 THEN pld_mineria_pagos_cliente_usd.Ultimo_Pago_11
                                         WHEN  12 THEN pld_mineria_pagos_cliente_usd.Ultimo_Pago_12 
                        END                                                                             AS Fecha_Mov,


                        YEAR(@Fecha_Mov)                                                                AS ANIO_PERIODO,
                        MONTH(@Fecha_Mov)                                                               AS MES_PERIODO,
                        IFNULL(solicitud_plvd.Nacionalidad,'MEXICANA')                                  AS Nacionalidad,
                        
                        clientes.Regimen                                                                AS Regimen,
                        
                        CONCAT(solicitud_plvd.Nombre,' ',
                                solicitud_plvd.NombreI,' ',
                                solicitud_plvd.Ap_paterno,' ',
                                solicitud_plvd.Ap_materno)                                              AS Nombre_Completo,

                        CONCAT( solicitud_plvd.Nombre,' ',solicitud_plvd.NombreI)                       AS Nombre,
                        solicitud_plvd.Ap_paterno                                                       AS Ap_paterno,
                        solicitud_plvd.Ap_materno                                                       AS Ap_materno


              FROM pld_mineria_pagos_credito
              
              INNER JOIN pld_mineria_pagos_cliente_usd  ON pld_mineria_pagos_cliente_usd.ID_Cliente     = pld_mineria_pagos_credito.ID_Cliente

              INNER JOIN clientes                       ON clientes.ID_Cliente                          = pld_mineria_pagos_credito.ID_Cliente
                
              LEFT JOIN solicitud_plvd                  ON solicitud_plvd.ID_Solicitud                  = clientes.ID_Solicitud

             
              WHERE  (clientes.Regimen = 'PM' and pld_mineria_pagos_cliente_usd.MAX_Monto_Periodo >= ".(1 *$this->Minimo_Relevantes_Morales_USD)." ) 
                     OR
                    (pld_mineria_pagos_cliente_usd.MAX_Monto_Periodo > ".(1 * $this->Minimo_Relevantes_Fisicas_USD)." )";
        
        
         $rs=$this->db->Execute($sql);
        

        if($rs->_numOfRows)
           while(! $rs->EOF)
           {
                $alerta[$ID]['MES_PERIODO']             = $rs->fields['MES_PERIODO'];
                $alerta[$ID]['ANIO_PERIODO']            = $rs->fields['ANIO_PERIODO'];

                $alerta[$ID]['TIPO']                    = 'Relevante';
                $alerta[$ID]['IDC']                     = $rs->fields['ID_Factura']*1;
                $alerta[$ID]['ID_CLIENTE']              = $rs->fields['ID_Cliente']*1;

                $alerta[$ID]['MONTO']                   = $rs->fields['MAX_Monto_Periodo_MX'];
                $alerta[$ID]['MONTO_US']                = $rs->fields['MAX_Monto_Periodo_US'];

                $alerta[$ID]['FECHA']                   = $rs->fields['Fecha_Mov'];
                $alerta[$ID]['NACIONALIDAD']            = $rs->fields['Nacionalidad'];
                $alerta[$ID]['REGIMEN']                 = $rs->fields['Regimen'];
                $alerta[$ID]['NOMBRE_COMPLETO']         = $rs->fields['Nombre_Completo'];

                $alerta[$ID]['NOMBRE']                  = $rs->fields['Nombre'];
                $alerta[$ID]['AP_PATERNO']              = $rs->fields['Ap_paterno'];
                $alerta[$ID]['AP_MATERNO']              = $rs->fields['Ap_materno'];


                
                if($rs->fields['_Regimen'] == 'PM')
                {
                        $alerta[$ID]['MOTIVO'] = "Persona moral, con pagos acumulados mensuales por ".(1 *$this->Minimo_Relevantes_Morales_USD)." USD o más";
                }
                else
                {
                        $alerta[$ID]['MOTIVO'] = "Persona física, con pagos acumulados mensuales por ".(1 *$this->Minimo_Relevantes_Fisicas_USD)." USD o más";
                }



                $alerta[$ID]['SEGUIMIENTO_NORMAL'  ]    = 'No';
                $alerta[$ID]['SEGUIMIENTO_ESPECIAL']    = 'No';


                if($alerta[$ID]['REGIMEN'] == 'PM')
                {
                        if($this->Minimo_Seguimiento_Morales_MX <= $alerta[$ID]['MONTO'])
                        {
                                $alerta[$ID]['SEGUIMIENTO_NORMAL'  ]    = 'Si';
                        }   
                }
                else
                {
                        if($this->Minimo_Seguimiento_Fisicas_MX <= $alerta[$ID]['MONTO'] )
                        {
                                $alerta[$ID]['SEGUIMIENTO_NORMAL'  ]    = 'Si';
                        } 
                }
                
                
                
                if(($this->Min_Seguimiento_Especial_MX  <= $alerta[$ID]['MONTO']) 
                    or
                   ($this->Min_Seguimiento_Especial_USD <= $alerta[$ID]['MONTO_US']))
                {               
                        $alerta[$ID]['SEGUIMIENTO_NORMAL'  ]    = 'No';
                        $alerta[$ID]['SEGUIMIENTO_ESPECIAL']    = 'Si';
                }

                
                $ID++;

                $rs->MoveNext();
           }


        //$this->showarray($alerta);


        return $alerta;


}
//==========================================================================================================================================
// ALERTAS PREOCUPANTES
//==========================================================================================================================================

function buscar_actividad_preocupante()
{
        $alerta = array();
        $ID=0;
        
        //---------------------------------------------------------------------------------------
        // Operaciones preocupantes 
        //---------------------------------------------------------------------------------------

       $sql="   SELECT  YEAR(pld_buzon.Registro)        AS ANIO_PERIODO,
                        MONTH(pld_buzon.Registro)       AS MES_PERIODO,

       
                        DATE(pld_buzon.Registro) AS Fecha,
                        pld_buzon.Nombre_Funcionario,                  
                        pld_buzon.Ap_Paterno,
                        pld_buzon.Ap_Materno,
                       
                       
                       pld_cat_operaciones_internas_preocupantes.Descripcion


                FROM pld_buzon
                LEFT JOIN pld_cat_operaciones_internas_preocupantes 
                       ON pld_cat_operaciones_internas_preocupantes.ID_Oper_Interna = pld_buzon.ID_Oper_Interna


                WHERE DATE(pld_buzon.Registro) BETWEEN '".$this->ff_ini."' and  '".$this->ff_fin."' ";

        
         $rs=$this->db->Execute($sql);
        

        if($rs->_numOfRows)
           while(! $rs->EOF)
           {
                $alerta[$ID]['MES_PERIODO']             = $rs->fields['MES_PERIODO'];
                $alerta[$ID]['ANIO_PERIODO']            = $rs->fields['ANIO_PERIODO'];

                $alerta[$ID]['TIPO']                    = 'Preocupante';
                $alerta[$ID]['IDC']                     = "";
                $alerta[$ID]['ID_CLIENTE']              = "";

                
                $alerta[$ID]['MONTO']                   = "";
                $alerta[$ID]['FECHA']                   = $rs->fields['Fecha'];
                $alerta[$ID]['NACIONALIDAD']            = "";
                $alerta[$ID]['REGIMEN']                 = "";
                $alerta[$ID]['NOMBRE_COMPLETO']         = $rs->fields['Nombre_Funcionario']." ".$rs->fields['Ap_Paterno']." ".$rs->fields['Ap_Materno'];

                $alerta[$ID]['NOMBRE']                  = $rs->fields['Nombre_Funcionario'];
                $alerta[$ID]['AP_PATERNO']              = $rs->fields['Ap_Paterno'];
                $alerta[$ID]['AP_MATERNO']              = $rs->fields['Ap_Materno'];
                
                $alerta[$ID]['MOTIVO']                  = $rs->fields['Descripcion'];

                $ID++;

             $rs->MoveNext();
           }


        //$this->showarray($alerta);


        return $alerta;


}
//==========================================================================================================================================
// 
//==========================================================================================================================================


function showarray(&$datos)
{
        $c2 = 'white';
        $c1 = 'aliceblue';

        echo "<BR>\n";
        echo "<BR>\n";
        echo "<BR>\n";
        echo "<TABLE ALIGN='center'  ORDER='0' BGCOLOR='black' ALIGN='center' CELLSPACING=1 CELLPADDING=3  ID='small'>\n";
        foreach($datos AS $key => $row)
        {
              $color=($color==$c2)?($c1):($c2);
        
                
            if($key==0)
            {
                echo "<TR BGCOLOR='steelblue'>\n";
                echo "<TH>&nbsp;</TH>\n";
                $_tit = array_keys($row);
                foreach($_tit AS $titulo)
                {
                        echo "<TH ALIGN='center' STYLE='color:white;' >".($titulo)."</TH>\n";                           
                
                }           
                echo "</TR>\n\n";
            }
                
                
             echo "<TR BGCOLOR='".$color."'>\n";
             echo "<TH BGCOLOR='steelblue'  STYLE='color:white;' >".($key+1)."</TH>\n";
                
                foreach($row AS $id => $data)
                {
                                   if(empty($data))
                                   {
                                        echo "<TD>&nbsp;</TD>\n";
                                   }
                                   else                    
                                   if(isdate($data))
                                   {
                                        echo "<TD ALIGN='center'>".ffecha($data)."</TD>\n";                             
                                   }
                                   elseif(is_numeric($data))
                                   {
                                        
                                        $isfloat=strpos($data,".") ;
                                        
                                        echo "<TD ALIGN='right'> ";
                                        
                                        if(  $isfloat > 0   )
                                        {
                                                echo number_format((1*$data),2);
                                        }
                                        else
                                        {
                                                echo $data;
                                        }
                                        echo "</TD>\n";                                                    
                                   }
                                   else
                                   {
                                        echo "<TD ALIGN='left'>".$data."</TD>\n";
                                   }
                           
                }
           echo "</TR>\n\n";

        }
        echo "</TABLE>\n";
        
        echo "<BR>\n";
        echo "<BR>\n";
        echo "<BR>\n";
        echo "<BR>\n";
        echo "<BR>\n";
        echo "<BR>\n";
        
}

//==========================================================================================================================================
// 
//==========================================================================================================================================

function registra_alertas_relevantes()
{

        if(count($this->alertas_relevantes) == 0 )
        {
                return 0;
        }

        
        foreach($this->alertas_relevantes       AS $row => $cell)
        {
        
                // REPLACE
                $sql = "INSERT IGNORE INTO pld_alertas_relevantes
                        (ID_Cliente, ID_Credito, Mes_Periodo, Anio_Periodo, 
                        
                         Monto_MX, Monto_USD, Fecha, 
                         
                         Candidato_Seguimiento_Normal, Candidato_Seguimiento_Especial, Regimen_Fiscal, 
                         
                         Nombre, AP_Paterno, AP_Materno, Nacionalidad, Motivo )
                        VALUES
                        ('".($cell['ID_CLIENTE']*1      )."',
                         '".($cell['IDC']*1             )."',
                         '".($cell['MES_PERIODO']*1     )."',
                         '".($cell['ANIO_PERIODO']*1    )."',
                         
                         '".($cell['MONTO']*1           )."',
                         '".($cell['MONTO_US']*1        )."',
                         '".($cell['FECHA']             )."',
                         
                         '".($cell['SEGUIMIENTO_NORMAL'  ])."',                  
                         '".($cell['SEGUIMIENTO_ESPECIAL'])."',                  
                         '".($cell['REGIMEN']           )."',
                         
                         '".($cell['NOMBRE']            )."',
                         '".($cell['AP_PATERNO']        )."',
                         '".($cell['AP_MATERNO']        )."',
                         '".($cell['NACIONALIDAD']      )."',
                         '".($cell['MOTIVO']            )."' ) \n";
                 
                
                //debug($sql);
                 $this->db->Execute($sql);
        }



}


//==========================================================================================================================================
// 
//==========================================================================================================================================


function registra_alertas_inusuales()
{

        if(count($this->alertas_inusuales) == 0 )
        {
                return 0;
        }
        
        foreach($this->alertas_inusuales        AS $row => $cell)
        {
                //REPLACE
                $sql = "INSERT IGNORE INTO pld_alertas_inusuales
                        (ID_Cliente, ID_Credito, Mes_Periodo, Anio_Periodo , Monto_MN, Monto_USD, Fecha, Regimen_Fiscal, Nombre, AP_Paterno, AP_Materno, Nacionalidad, Motivo)  
                        VALUES
                        ('".($cell['ID_CLIENTE']*1      )."',
                         '".($cell['IDC']*1             )."',
                         '".($cell['MES_PERIODO']*1     )."',
                         '".($cell['ANIO_PERIODO']*1    )."',
                         '".($cell['MONTO']*1           )."',
                         '".($cell['MONTO_US']*1        )."',
                         '".($cell['FECHA']             )."',
                         '".($cell['REGIMEN']           )."',
                         '".($cell['NOMBRE']            )."',
                         '".($cell['AP_PATERNO']        )."',
                         '".($cell['AP_MATERNO']        )."',
                         '".($cell['NACIONALIDAD']      )."',
                         '".($cell['MOTIVO']            )."' ) \n";
                 
                 $this->db->Execute($sql);
        }



}








}

function isdate($ds)
{
        
        if( strlen($ds)!= 10 ) return(false);
        
        list($y,$m,$d)=explode("-",$ds);
        
        $y = (int) $y*1;
        $m = (int) $m*1;
        $d = (int) $d*1;

        if( checkdate($m,$d,$y) )
        {
                return(true);
        }
        
        return(false);
        
}

?>