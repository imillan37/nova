<?


class TINTF
{



    var $info = "";
    var $Clave_del_Otorgante  ="";



    var $TotSaldosActuales   = 0; //  Int64;         //   N      14
    var $TotSaldosVencidos   = 0; //  Int64;         //   N      14
    var $TotSegINTF          = 0; //  byte;          //   N      3
    var $TotSegPN            = 0; //  Longint    ;   //   N      9
    var $TotSegPA            = 0; //  Longint    ;   //   N      9
    var $TotSegPE            = 0; //  Longint    ;   //   N      9
    var $TotSegTL            = 0; //  Longint    ;   //   N      9
    var $ContaBloques        = 0; //  byte;          //   N      6
    var $NomOtorgante        = "";
    var $DomDevolucion       = "";

    var $fecha_reporte_con_formato="";

    var $error_msg          = "";

    var $edo_sepomex_a_buro = array();


function TINTF(&$db )
{

        $this->db = $db; 

        
        //======================================================================================
        //   Conversión de claves de Estados de la Republica de SOPMEX => A => BURO DE CREDITO
        //======================================================================================

        
        $this->edo_sepomex_a_buro['AGS']      = 'AGS';
        $this->edo_sepomex_a_buro['BC']       = 'BCN';
        $this->edo_sepomex_a_buro['BCS']      = 'BCS';
        $this->edo_sepomex_a_buro['CAM']      = 'CAM';
        $this->edo_sepomex_a_buro['CHIS']     = 'CHS';
        $this->edo_sepomex_a_buro['CHIH']     = 'CHI';
        $this->edo_sepomex_a_buro['COAH']     = 'COA';
        $this->edo_sepomex_a_buro['COL']      = 'COL';
        $this->edo_sepomex_a_buro['DF']       = 'DF';
        $this->edo_sepomex_a_buro['DGO']      = 'DGO';
        $this->edo_sepomex_a_buro['GTO']      = 'GTO';
        $this->edo_sepomex_a_buro['GRO']      = 'GRO';
        $this->edo_sepomex_a_buro['HGO']      = 'HGO';
        $this->edo_sepomex_a_buro['JAL']      = 'JAL';
        $this->edo_sepomex_a_buro['MEX']      = 'EM';
        $this->edo_sepomex_a_buro['MICH']     = 'MICH';
        $this->edo_sepomex_a_buro['MOR']      = 'MOR';
        $this->edo_sepomex_a_buro['NAY']      = 'NAY';
        $this->edo_sepomex_a_buro['NL']       = 'NL';
        $this->edo_sepomex_a_buro['OAX']      = 'OAX';
        $this->edo_sepomex_a_buro['PUE']      = 'PUE';
        $this->edo_sepomex_a_buro['QRO']      = 'QRO';
        $this->edo_sepomex_a_buro['QROO']     = 'QR';
        $this->edo_sepomex_a_buro['SLP']      = 'SLP';
        $this->edo_sepomex_a_buro['SIN']      = 'SIN';
        $this->edo_sepomex_a_buro['SON']      = 'SON';
        $this->edo_sepomex_a_buro['TAB']      = 'TAB';
        $this->edo_sepomex_a_buro['TAM']      = 'TAM';
        $this->edo_sepomex_a_buro['TLAX']     = 'TLA';
        $this->edo_sepomex_a_buro['VER']      = 'VER';
        $this->edo_sepomex_a_buro['YUC']      = 'YUC';
        $this->edo_sepomex_a_buro['ZAC']      = 'ZAC';




}

function AdStr($s)
{
  $this->info .= $s;
}


function RegVar($Etiqueta, $Cadena)
{
     if(strlen($Cadena)==0) 
     {
         
         // Si la cadena está vacía no reportamos el campo

         $_cadena="";
         
     }    
     else
     {

             //--------------------------------------------------------------------             
             // Caracteres no permitidos convertir a espacios en blanco
             //--------------------------------------------------------------------             


             $bad_chars= array("+","-","_",",",";",".","#","|","'","$","%","&","(",")","[","]","*","´","`","^","¨","~",'"',"/");

             $_tmp = $Cadena;

             // Si hay agún caracter no permitido lo reemplazamos por espacio en blanco.
             foreach($bad_chars AS $_char)
             {
                $_tmp = str_replace($_char," ",$_tmp);
             }

             $_cadena = trim($_tmp);
             
             
             
             //--------------------------------------------------------------------             
             // Minúsculas a mayúsculas
             //--------------------------------------------------------------------             

             
             $_cadena = strtoupper($_cadena);
             
             



             //--------------------------------------------------------------------             
             // Caracteres no permitidos contra tabla de sustitución
             //--------------------------------------------------------------------             
             
             
             $sustutucion = array();      
             
             
             $sustutucion['Á']  = 'A';
             $sustutucion['É']  = 'E';
             $sustutucion['Í']  = 'I';
             $sustutucion['Ó']  = 'O';
             $sustutucion['Ú']  = 'U';
             
             $sustutucion['Ü']  = 'U';
             $sustutucion['Ñ']  = 'N';
             
             $_tmp = $_cadena;
           
             foreach($sustutucion AS  $badcahr => $goodchar)
             {
                $_tmp = str_replace($badcahr,$goodchar,$_tmp);
             }

             $_cadena = trim($_tmp);
           
           
           
           
           
           
           
           
           
           
             


     }

     $this->AdStr($Etiqueta . $this->SizeIN($_cadena) . $_cadena);

}

function RegFixNum($Etiqueta,$S, $N)
{   

    $sN = "";

    $sN=strval($N);
    
    if($N<=9)  $sN='0'. $sN;

    $this->AdStr($Etiqueta . $sN . $this->ponceros($S,$N));

}


function RegFixChar($Etiqueta,$S,$N)
{   $sN = "";

    $sN=strval($N);
    
    if ($N<=9) $sN='0' . $sN;
        
    $this->AdStr($Etiqueta . $sN . $this->ponespacios($S,$N));
}




function SizeIN($S)
{
  $I=0;
  $J=0;

   $I = strlen(trim($S));
   $J = strval($I);
   
   if($I<=9) $J= '0'.$J;

   return($J);

}


function ponespacios($S, $N)
{
  $cadena = "";
  $J = 0;

  $cadena = $S;
  $J      = strlen($S);

  while($J < $N)
    {
      $cadena .= ' ';
      ++$J;
    }

  return($cadena);
}


function ponceros($S,$N)
{
  $cadena ="";
  $J = 0;

  $J = strlen(trim($S));
  
  while($J < $N)
  {

    $cadena = '0'.$cadena;

    ++$J;
  }



     if(strlen(trim($S)) > 0)
       $cadena .= $cadena + $S;




  return($cadena);

}



function error_msg( $msg )
{

        echo "\n<SCRIPT>\n\n";
        echo "   alert('". $msg."'); \n";
        echo "\n</SCRIPT>\n\n";

        return;

}



//----------------------------------------------------------------------//
function GeneraFormato()
{

 $I=0;
 $AUX = "";
 $this->n_error=0;

  //-----------------------------------------------------


   

        $sql = "SELECT COUNT(*) FROM buro_encabezado ";
        $rs=$this->db->Execute($sql);
         if ($rs->fields[0] < 1) 
           {
                $this->error_msg( 'La tabla de datos de la empresa está vacia. No se podrá generar la información.');
                $this->n_error++;
                return;
           }



        $sql = "SELECT COUNT(*) FROM buro_seg_nombre ";
        $rs=$this->db->Execute($sql);
         if ($rs->fields[0] < 1) 
           {
               // debug($sql."->:".$rs->fields[0]);
                
               // debug(print_r($this->db,1));
                
                
                $this->error_msg('La tabla de segmento de nombres de clientes está vacia. No se podrá generar la información.');
                $this->n_error++;
                return;
           }


        $sql = "SELECT COUNT(*) FROM buro_seg_nombre ";
        $rs=$this->db->Execute($sql);
         if ($rs->fields[0] < 1) 
           {
                $this->error_msg('La tabla de cuentas de los clientes, está vacia. No se podrá generar la información.');
                $this->n_error++;
                return;
           }



        $sql = "SELECT COUNT(*) FROM buro_seg_cuenta ";
        $rs=$this->db->Execute($sql);
         if ($rs->fields[0] < 1) 
           {
                $this->error_msg('La tabla de cuentas de los clientes, está vacia. No se podrá generar la información.');
                $this->n_error++;
                return;
           }


  //-----------------------------------------------------

 
  $this->info ="";


  //----------Encabezado


$sql = "SELECT CLAVE_KOB, 
               CALVE_USR,
               NOMBRE,  
               CICLO, 
               FECHA_REPORTE, 
               USO_FUTURO,
               ADICIONAL   
          FROM buro_encabezado ";

$rs=$this->db->Execute($sql);

  $this->Clave_del_Otorgante = $rs->fields['CLAVE_KOB'].$rs->fields['CALVE_USR'];


/**/
  $this->AdStr('INTF');
  $this->AdStr('12');
  $this->AdStr($this->Clave_del_Otorgante);




  $this->AdStr($this->ponespacios(trim($rs->fields['NOMBRE']),16));
  $this->AdStr($this->ponespacios(trim($rs->fields['CICLO']) ,2 ));
 
  $fecha_reporte = fdia($rs->fields['FECHA_REPORTE']).fmes($rs->fields['FECHA_REPORTE']).fanio($rs->fields['FECHA_REPORTE']); 
 
 
 
  $this->AdStr($this->ponespacios($fecha_reporte ,8 ));

  $this->AdStr($this->ponceros($rs->fields['USO_FUTURO']   ,10));

  $this->AdStr($this->ponespacios($rs->fields['ADICIONAL'] ,98));
  
  
  

  $I=1;

  $this->TotSaldosActuales=0;
  $this->TotSaldosVencidos=0;
  $this->TotSegINTF=0;
  $this->TotSegPN=0;
  $this->TotSegPA=0;
  $this->TotSegPE=0;
  $this->TotSegTL=0;
  $this->ContaBloques=1;
  $this->NomOtorgante=$rs->fields['NOMBRE'];
  $this->DomDevolucion='';




$sql = "SELECT   buro_seg_nombre.ID_Cuenta        
        FROM     buro_seg_nombre, buro_seg_direccion, buro_seg_cuenta
        WHERE    buro_seg_nombre.ID_Cuenta = buro_seg_direccion.ID_Cuenta and
                 buro_seg_nombre.ID_Cuenta = buro_seg_cuenta.ID_Cuenta
        ORDER BY buro_seg_nombre.ID_Cuenta ";

        
        
$rs=$this->db->Execute($sql);



if($rs->_numOfRows)
   while(! $rs->EOF)
   {

           $ID=$rs->fields['ID_Cuenta'];

           $this->AddRecordNombreDirEmpleo($ID) ;
           $this->AddRecordCuentas($ID) ;

          $I++;


     $rs->MoveNext();
   }


  //--------------------
  // El Segmento de cierre ahora si  es requerido, así que lo reportaremos.
  {

   $this->AdStr('TRLR');
   $this->AdStr($this->ponceros(strval($this->TotSaldosActuales),14));
   $this->AdStr($this->ponceros(strval($this->TotSaldosVencidos),14));
   $this->AdStr('001');
   $this->AdStr($this->ponceros(strval($this->TotSegPN ),9));
   $this->AdStr($this->ponceros(strval($this->TotSegPA ),9));
   $this->AdStr($this->ponceros(strval($this->TotSegPE ),9));
   $this->AdStr($this->ponceros(strval($this->TotSegTL ),9));
   $this->AdStr($this->ponceros('0',6));  // ContaBloques
   
   $this->AdStr($this->ponespacios($this->NomOtorgante,16));
   $this->AdStr($this->ponespacios('',160));

  }
   //--------------------


}


//----------------------------------------------------------------------//


function AddRecordNombreDirEmpleo($ID) 
{
      //---------------------------------------------------------
     //------------->Segmento de Nombre



 $sql ="SELECT AP_PATERNO, 
               AP_MATERNO, 
               AP_ADICION, 
               PRI_NOMBRE, 
               SEG_NOMBRE, 
               NACIMIENTO, 
               RFC, 
               PREFIJO, 
               SUFIJO, 
               NACIONALID, 
               RESIDENCIA, 
               LIC_MANEJO, 
               EDO_CIVIL, 
               SEXO, 
               CEDULA, 
               IFE, 
               IMP_PAIS, 
               CVE_PAIS, 
               NUM_DEPEND, 
               EDADES_DEP, 
               DEFUNCION, 
               MURIO
        FROM   buro_seg_nombre
        WHERE ID_Cuenta = '".$ID."' 
        ORDER BY ID_Cuenta ";

 $rs=$this->db->Execute($sql);

        if($rs->_numOfRows)
        {

                $this->RegVar('PN', $rs->fields['AP_PATERNO']);                //Apellido Paterno
                $this->RegVar('00', $rs->fields['AP_MATERNO']);                //Apellido Materno

        if(strlen(trim($rs->fields['AP_ADICION'])) != 0)        
                $this->RegVar('01', $rs->fields['AP_ADICION']);                //Apellido Adicional

                $this->RegVar('02', $rs->fields['PRI_NOMBRE']);                //Primer Nombre

        if(strlen(trim($rs->fields['SEG_NOMBRE'])) != 0)        
                $this->RegVar('03', $rs->fields['SEG_NOMBRE']);                //Segundo Nombre


        if(strlen(trim($rs->fields['NACIMIENTO'])) != 0)        
                $this->RegVar('04', $rs->fields['NACIMIENTO']);                //Fecha de Nacimiento



                $this->RegVar('05', $rs->fields['RFC']);                       //Número de RFC



        if(strlen(trim($rs->fields['PREFIJO'])) != 0)        
                $this->RegVar('06', $rs->fields['PREFIJO']);                   //Prefijo Personal o Profesional

        if(strlen(trim($rs->fields['SUFIJO'])) != 0)        
                $this->RegVar('07', $rs->fields['SUFIJO']);                    //Sufijo

        if(strlen(trim($rs->fields['NACIONALID'])) != 0)        
                $this->RegVar('08', $rs->fields['NACIONALID']);                //Nacionalidad

        if(strlen(trim($rs->fields['RESIDENCIA'])) != 0)        
                $this->RegVar('09', $rs->fields['RESIDENCIA']);                //Residencia

        if(strlen(trim($rs->fields['LIC_MANEJO'])) != 0)        
                $this->RegVar('10', $rs->fields['LIC_MANEJO']);                //Número de Licencia de Conducir

        if(strlen(trim($rs->fields['EDO_CIVIL'])) != 0)        
                $this->RegVar('11', $rs->fields['EDO_CIVIL']);                 //Estado Civil                
                
        if(strlen(trim($rs->fields['SEXO'])) != 0)        
                $this->RegVar('12', $rs->fields['SEXO']);                      //Sexo
                
        if(strlen(trim($rs->fields['CEDULA'])) != 0)        
                $this->RegVar('13', $rs->fields['CEDULA']);                    //Número de Cédula Profesional

        if(strlen(trim($rs->fields['IFE'])) != 0)
                $this->RegVar('14', $rs->fields['IFE']);                       //Número de Registro Electoral (IFE)

        if(strlen(trim($rs->fields['IMP_PAIS'])) != 0)
                $this->RegVar('15', $rs->fields['IMP_PAIS']);                  //Clave para impuestos en otro País

        if(strlen(trim($rs->fields['CVE_PAIS'])) != 0)
                $this->RegVar('16', $rs->fields['CVE_PAIS']);                  //Clave de otro País

        if( intval($rs->fields['NUM_DEPEND']) > 0 )
                $this->RegVar('17', $rs->fields['NUM_DEPEND']);        //Número de Dependientes

        if(strlen(trim($rs->fields['EDADES_DEP'])) != 0)
                $this->RegVar('18', $rs->fields['EDADES_DEP']);                //Edades de los Dependientes
                                                
        if(strlen(trim($rs->fields['DEFUNCION'])) != 0)
                $this->RegVar('20', $rs->fields['DEFUNCION']);                 //Fecha de Defunción

                if($rs->fields['MURIO'] == 'Y' )          
                       $this->RegVar('21', 'Y');                               //Indicador de Defunción

                $this->TotSegPN++;

        }    
    //---------------------------------------------------------
    //-------------> Dirección




 $sql ="SELECT ID_Cuenta, DIR1, DIR2, COL_POB, DELEGACION, CIUDAD, ESTADO, CP, RESIDENCIA, TELEFONO, EXT, FAX, TIPO_DOM 
        FROM    buro_seg_direccion
        WHERE ID_Cuenta = '".$ID."'         
        ORDER BY ID_Cuenta ";


 $rs=$this->db->Execute($sql);


       if($rs->_numOfRows)
       {
        $this->RegVar('PA', str_replace("  "," ",$$rs->fields['DIR1']));

        if(strlen(trim($rs->fields['DIR2'])) != 0)        
           $this->RegVar('00', $rs->fields['DIR2']);
        
        if(strlen(trim($rs->fields['COL_POB'])) != 0)        
           $this->RegVar('01', $rs->fields['COL_POB']);
           
        $this->RegVar('02', $rs->fields['DELEGACION']);
        $this->RegVar('03', $rs->fields['CIUDAD']);
        $this->RegVar('04', $rs->fields['ESTADO']);
        $this->RegVar('05', $rs->fields['CP']);


        if(strlen(trim($rs->fields['RESIDENCIA'])) != 0)
           $this->RegVar('06', $rs->fields['RESIDENCIA']);
                                

        if(strlen(trim($rs->fields['TELEFONO'])) != 0)        
           $this->RegVar('07', $rs->fields['TELEFONO']);

        if(strlen(trim($rs->fields['EXT'])) != 0)        
           $this->RegVar('08', $rs->fields['EXT']);

        if(strlen(trim($rs->fields['FAX'])) != 0)        
           $this->RegVar('09', $rs->fields['FAX']);

        if(strlen(trim($rs->fields['TIPO_DOM'])) != 0)        
           $this->RegVar('10', $rs->fields['TIPO_DOM']);

        if(strlen(trim($rs->fields['IND_DOM'])) != 0)        
           $this->RegVar('11', $rs->fields['IND_DOM']);


        $this->TotSegPA++;
      }

     //---------------------------------------------------------
    //-------------> Segmento de Empleo



/*  No es obligatorio, no lo reportamos



               $this->RegVar('PE', Empleo.FieldByName('EMPLEADOR']);
               $this->RegVar('00', Empleo.FieldByName('DIR_1']);
               $this->RegVar('01', Empleo.FieldByName('DIR_2']);
               $this->RegVar('02', Empleo.FieldByName('COL_POB']);
               $this->RegVar('03', Empleo.FieldByName('DELEGACION']);
               $this->RegVar('04', Empleo.FieldByName('CIUDAD']);
               $this->RegVar('05', Empleo.FieldByName('ESTADO']);
               $this->RegVar('06', Empleo.FieldByName('CP']);
               $this->RegVar('07', Empleo.FieldByName('TELEFONO']);
               $this->RegVar('08', Empleo.FieldByName('EXT']);
               $this->RegVar('09', Empleo.FieldByName('FAX']);
               $this->RegVar('10', Empleo.FieldByName('CARGO']);
               $this->RegVar('11', Empleo.FieldByName('CONTRATADO']);
               $this->RegVar('12', Empleo.FieldByName('CVEMON_SAL']);
               $this->RegVar('13', Empleo.FieldByName('SALARIO']);
               $this->RegVar('14', Empleo.FieldByName('BASE_SAL']);
               $this->RegVar('15', Empleo.FieldByName('N_EMPLEADO']);
               $this->RegVar('16', Empleo.FieldByName('ULTIMO_DIA']);
               $this->RegVar('17', Empleo.FieldByName('FECHA_VER']);
               $this->TotSegPE++;



*/




}


//-------------------------------------------------------------------------------//

function AddRecordCuentas($ID) 
{


       $sql = " SELECT  ID_Cuenta,
                         ETIQUETA,
                         MEMBER_CVE,
                         NOMBRE_USR,
                         ID_Cuenta,
                         TIPO_RESP,
                         TIPO_CTA,
                         T_CONTRATO,
                         CVE_MONEDA,
                         IMPORTE_AV,
                         NUM_PAGOS,
                         FREC_PAGOS,
                         MONTO,
                         APERTURA,
                         ULT_PAGO,
                         ULT_COMPRA,
                         F_CIERRE,
                         F_REPORTE,
                         GARANTIA,
                         CRED_MAX,
                         SALDO_ACT,
                         CRED_LIM,
                         
                         SALDO_VENC,
                         PAGOS_VENC,
                         
                         FORMA_PAGO,
                         HIST_PAGOS,
                         CVE_OBSERV,
                         TOT_PAGOS,
                         TPAG_MOP2,
                         TPAG_MOP3,
                         TPAG_MOP4,
                         TPAG_MOP5,
                         CVE_ANT_OT,
                         NOMBRE_ANT,
                         N_CTA_ANT,
                         DATE_FORMAT(buro_base.primer_inc, '%d%m%Y') AS PRIMER_INC,
                         SALDO_INS_PRIN,
                         FIN

                FROM buro_seg_cuenta INNER JOIN buro_base ON buro_base.ID_Buro = buro_seg_cuenta.ID_Cuenta
                WHERE ID_Cuenta = '".$ID."' ";

       
       $rs=$this->db->Execute($sql);
       
       
       if($rs->_numOfRows)
       {

               $this->RegVar('TL','TL');

               $this->RegVar('01', $this->Clave_del_Otorgante,10);                      //    01      Clave del Otorgante (Member Code)


               $this->RegVar('02', $rs->fields['NOMBRE_USR']);                          //    02      Nombre del Usuario
               $this->RegVar('04', $rs->fields['ID_Cuenta']);                           //    04      Número de Cuenta Actual
               $this->RegVar('05', $rs->fields['TIPO_RESP']);                           //    05      Indicador de Tipo de Responsabilidad de la Cuenta
               $this->RegVar('06', $rs->fields['TIPO_CTA']);                            //    06      Tipo de Cuenta
               $this->RegVar('07', $rs->fields['T_CONTRATO']);                            //  07      Tipo de Contrato (producto crediticio)
               $this->RegVar('08', $rs->fields['CVE_MONEDA']);                          //    08      Clave de Unidad Monetaria

	    if( strlen($rs->fields['IMPORTE_AV']) > 0 )                
               $this->RegVar('09', $rs->fields['IMPORTE_AV']);                          //    09      Importe del Avalúo
               
               
               
               $this->RegVar('10', $rs->fields['NUM_PAGOS']);                           //    10      Número de Pagos
               $this->RegVar('11', $rs->fields['FREC_PAGOS']);                        //      11      Frecuencia de Pagos

               $this->RegFixNum('12', $rs->fields['MONTO'],9);

               $this->RegVar('13', $rs->fields['APERTURA']);                            //    13      Fecha de Apertura de la Cuenta
               $this->RegVar('14', $rs->fields['ULT_PAGO']);                            //    14      Fecha de Último Pago
               $this->RegVar('15', $rs->fields['ULT_COMPRA']);                          //    15      Fecha de Última Compra (disposición)

	    if( strlen($rs->fields['F_CIERRE']) > 0 )                 
               $this->RegVar('16', $rs->fields['F_CIERRE']);                          //      16      Fecha de Cierre del Crédito






               $this->RegVar('17', $rs->fields['F_REPORTE']);                           //    17      Fecha de Reporte
               


	    if( strlen($rs->fields['GARANTIA']) > 0 )                 
               $this->RegVar('20', $rs->fields['GARANTIA']);                            //    20      Garantía
               
               $this->RegVar('21', $rs->fields['CRED_MAX']);                            //    21      Crédito Máximo
               
               
               $this->RegVar('22', $rs->fields['SALDO_ACT']);                           //    22      Saldo Actual



            if($rs->fields['TIPO_CTA']!='I')        //-Etiqueta 23 no aplica para cuentas a pagos fijos, por lo tanto se omite.
               $this->RegVar('23', $rs->fields['CRED_LIM']);                            //    23      Límite de Crédito





               $this->RegVar('24', $rs->fields['SALDO_VENC']);                          //    24      Saldo Vencido



               $this->RegVar('25', $rs->fields['PAGOS_VENC']);                          //    25      Número de Pagos Vencidos


               $this->TotSaldosActuales =   $this->TotSaldosActuales + $rs->fields['SALDO_ACT'];
               $this->TotSaldosVencidos =   $this->TotSaldosVencidos + $rs->fields['SALDO_VENC'];


               $this->RegVar('26', $rs->fields['FORMA_PAGO']);                          //    26      Forma de Pago (MOP) Actual

           if(!empty($rs->fields['HIST_PAGOS']))    
            {
            	$this->RegVar('27', $rs->fields['HIST_PAGOS']);                          //    27      Histórico de Pagos
            } 
            
           if(!empty($rs->fields['CVE_OBSERV']))    
            {
               $this->RegVar('30', $rs->fields['CVE_OBSERV']);                          //    30      Clave de Observación
            }

           if( intval($rs->fields['TOT_PAGOS']) > 0 )
                $this->RegFixNum('31', $rs->fields['TOT_PAGOS'],3 );                    //  31      Total de Pagos Reportados

           if( intval($rs->fields['TPAG_MOP2']) > 0 )
                $this->RegFixNum('32', $rs->fields['TPAG_MOP2'],2 );                    //  32      Total de Pagos Calificados MOP 02

           if( intval($rs->fields['TPAG_MOP3']) > 0 )
                $this->RegFixNum('33', $rs->fields['TPAG_MOP3'],2 );                    //  33      Total de Pagos Calificados con MOP 03

           if( intval($rs->fields['TPAG_MOP4']) > 0 )
                $this->RegFixNum('34', $rs->fields['TPAG_MOP4'],2 );                    //  34      Total de Pagos Calificados con MOP 04

           if( intval($rs->fields['TPAG_MOP5']) > 0 )
                $this->RegFixNum('35', $rs->fields['TPAG_MOP5'],2 );                    //  35      Total de Pagos Calificados con MOP 05 o mayor

      //     if( strlen($rs->fields['CVE_ANT_OT']) > 0 )  
      //         $this->RegVar('39', $rs->fields['CVE_ANT_OT']);  			//   39      Clave Anterior del Otorgante
               
               
      //     if( strlen($rs->fields['NOMBRE_ANT']) > 0 )               
      //         $this->RegVar('40', $rs->fields['NOMBRE_ANT']);                          //   40      Nombre Anterior del Otorgante

      //     if( strlen($rs->fields['N_CTA_ANT']) > 0 )               
      //         $this->RegVar('41', $rs->fields['N_CTA_ANT']);                           //   41      Número de Cuenta Anterior
               
               
               
               $this->RegVar('43', $rs->fields['PRIMER_INC']);                          //   43      Fecha Primer Incumplimiento que se reporta como 01011900
               $this->RegFixNum('44', $rs->fields['SALDO_INS_PRIN'],9);                 //   44      Saldo Insoluto Del Principal   
                         
           $this->RegVar('99', 'FIN');                                              //   99      Indicador de Fin del Segmento TL
               $this->TotSegTL++;
        }


}


function sincomas($token)
{

        return(str_replace(",","",$token));
}



function GeneraDatos($id_empresas = null)
{


        $sql="  SELECT Clave_kob, Calve_usr, Nombre, Fecha_reporte
                FROM buro_encabezado ";

        $rs = $this->db->Execute($sql);


        $MEMBER_CVE     =       $rs->fields['Clave_kob'].$rs->fields['Calve_usr'];
        $NOMBRE_USR     =       $rs->fields['Nombre'];
        $FECHA_CORTE    =       $rs->fields['Fecha_reporte'];


	$this->fecha_reporte = 

        //=========================================================================================

        list($AA,$MM,$DD)= explode("-",$FECHA_CORTE);

	$this->fecha_reporte_con_formato = date("dmY", mktime(0,0,0,$MM,$DD,$AA));
        //=========================================================================================


        $sql ="DELETE FROM buro_seg_nombre ";
        $this->db->Execute($sql);


        $sql ="DELETE FROM buro_seg_direccion ";
        $this->db->Execute($sql);


        $sql ="DELETE FROM buro_seg_cuenta ";
        $this->db->Execute($sql);



        $sql = "DROP TABLE IF EXISTS buro_reporte ";
        $this->db->Execute($sql);


        //=========================================================================================

//       $sql = " CREATE TEMPORARY TABLE IF NOT EXISTS buro_reporte ENGINE=MyISAM  

        
$sql = " CREATE TABLE buro_reporte (
		ID_Factura 	INT(10) UNSIGNED NOT NULL DEFAULT '0',
		Fecha_Reporte 	VARCHAR(10) NOT NULL DEFAULT '',
		Regimen 	VARCHAR(14) NOT NULL DEFAULT '',
		PRIMARY KEY (`ID_Factura`)
	)
	COLLATE='latin1_swedish_ci'
	ENGINE=MyISAM ";       

        $this->db->Execute($sql);

//debug($sql);        
        
        
        
        
        
        
        
        
        $sql = " INSERT INTO buro_reporte (ID_Factura, Fecha_Reporte, Regimen)
                 (

			 SELECT cierre_contable_saldos.ID_Factura,
				'".$FECHA_CORTE."'      AS Fecha_Reporte,
				'Persona Fisica'        AS Regimen

			FROM  cierre_contable_saldos

			INNER JOIN  cierre_contable_log ON cierre_contable_log.Fecha_Cierre BETWEEN  '".$AA."-".$MM."-01' and '".$FECHA_CORTE."'  AND
							   cierre_contable_saldos.ID_Cierre = cierre_contable_log.ID_Cierre


			inner JOIN fact_cliente   ON fact_cliente.id_factura  = cierre_contable_saldos.ID_Factura
			inner JOIN clientes_datos ON fact_cliente.num_cliente = clientes_datos.Num_cliente

";

                    if(is_array($id_empresas)){

                        $empresas = implode(",",$id_empresas);

                        $sql .= "   INNER JOIN credito_empresa_convenio ON credito_empresa_convenio.id_factura = fact_cliente.id_factura
                                WHERE credito_empresa_convenio.ID_empresa in($empresas) ";
                    }

                $sql .= "

			 GROUP BY  cierre_contable_saldos.ID_Factura 
                 )
                 ";

        $this->db->Execute($sql);

        //=========================================================================================
        // Segmento de nombre
        //=========================================================================================

//                               concat(clientes_datos.RFC,clientes_datos.Hclave)          AS rfch,     
//                               clientes_datos.RFC                                       AS rfch,     

        $sql = "SELECT         buro_base.ID_Buro                                        AS Cuenta,
                               TRIM(clientes_datos.Ap_paterno)                          AS Paterno,
                               TRIM(clientes_datos.Ap_materno)                          AS Materno,       
                               TRIM(clientes_datos.Nombre)                              AS Nombre1,
                               TRIM(clientes_datos.NombreI)                             AS Nombre2,
                               TRIM(clientes_datos.Fecha_nacimiento)                    AS Fec_nac,      
                               buro_base.RFC                                            AS rfch,     
                              IF(clientes_datos.SEXO='FEMENINO','F','M')                AS Sexo,
                              fact_cliente.ID_TipoCredito

                FROM  buro_base   

                INNER JOIN buro_reporte  ON buro_reporte.ID_Factura     = buro_base.ID_Factura and 
                                            buro_reporte.Fecha_Reporte  = '".$FECHA_CORTE ."'

                INNER JOIN fact_cliente   ON fact_cliente.id_factura    = buro_reporte.ID_Factura
                INNER JOIN clientes_datos ON clientes_datos.Num_cliente = fact_cliente.num_cliente";

                    if(is_array($id_empresas)){

                        $empresas = implode(",",$id_empresas);

                        $sql .= "   INNER JOIN credito_empresa_convenio ON credito_empresa_convenio.id_factura = buro_base.ID_Factura
                                WHERE credito_empresa_convenio.ID_empresa in($empresas) ";
                    }

                $sql .= "   ORDER BY fact_cliente.num_cliente,buro_base.ID_Buro     

                ";
				

        $rs=$this->db->Execute($sql);
        if($rs->_numOfRows)
           while(! $rs->EOF)
           {

                list($cuenta, $paterno, $materno, $nombre1, $nombre2, $fec_nac, $rfc, $sexo) = $rs->fields;

                $nombre2=(empty($nombre2))?(" "):($nombre2);
                $nombre1=(empty($nombre1))?(" "):($nombre1);


                //Si el apellido paterno no existe el materno se reportará como paterno

                if(empty($paterno))
                {
                    $paterno=$materno;
                    $materno="NO PROPORCIONADO";
                }


                $materno=(empty($materno))?("NO PROPORCIONADO"):($materno);     






                $sexo=(empty($sexo))?(" "):($sexo);     

                $_nacimiento= fdia($fec_nac).fmes($fec_nac).fanio($fec_nac);



                $sql= "INSERT INTO buro_seg_nombre  
                        (ID_Cuenta, ap_paterno, ap_materno, pri_nombre, seg_nombre, nacimiento ,rfc, nacionalid, sexo, num_depend, murio)
                       VALUES
                       ('".$cuenta."',
                        '".$this->sincomas($paterno)."',
                        '".$this->sincomas($materno)."',
                        '".$this->sincomas($nombre1)."',
                        '".$this->sincomas($nombre2)."',
                        '".$_nacimiento."',
                        '".$rfc."',   
                        'MX',       
                        '".$sexo."',   
                        '0',        
                        'N') ";

                $this->db->Execute($sql);



               $rs->MoveNext();
           }





        //=========================================================================================
        // Segmento de direccion
        //=========================================================================================




        $sql= " SELECT buro_base.ID_Buro AS Cuenta,
                        '001' AS Sec, 
                         CONCAT(IFNULL(clientes_datos.Calle,' '),' ',IFNULL(clientes_datos.Num,' ')) AS Calle,       
                         clientes_datos.Interior        AS CNum,  
                         ''         AS CMz,       
                         ''            AS CLt,
                         clientes_datos.Colonia, 
                         clientes_datos.Ciudad, 
                         clientes_datos.Poblacion,   
                         estados.cve_estado,
                         clientes_datos.CP, 
                         clientes_datos.Telefono
                FROM  buro_base   

                INNER JOIN buro_reporte  ON buro_reporte.ID_Factura    = buro_base.ID_Factura and 
                                            buro_reporte.Fecha_Reporte =  '".$FECHA_CORTE ."'

                INNER JOIN fact_cliente   ON fact_cliente.id_factura    = buro_reporte.ID_Factura
                INNER JOIN clientes_datos ON clientes_datos.Num_cliente = fact_cliente.num_cliente
                LEFT JOIN estados         ON estados.Nombre        = clientes_datos.Estado ";

                    if(is_array($id_empresas)){

                        $empresas = implode(",",$id_empresas);

                        $sql .= "   INNER JOIN credito_empresa_convenio ON credito_empresa_convenio.id_factura = buro_base.ID_Factura
                                WHERE credito_empresa_convenio.ID_empresa in($empresas)";
                    }

                $sql .= "   ORDER BY fact_cliente.num_cliente,buro_base.ID_Buro    


                ";

        $rs=$this->db->Execute($sql);                    
        if($rs->_numOfRows)
           while(! $rs->EOF)
           {

                 list($cuenta, $sec, $d1, $d2, $d3, $d4, $colonia, $ciudad, $munici, $edo_sepomex, $cp, $telefono) = $rs->fields;


                $direc =  trim($d1." ".$d2." ".$d3." ".$d4);

                $direc1 = substr($direc,0,  40);
                $direc2 = substr($direc,40, 79);

                $direc1         =(empty($direc1  ))?(" "):($direc1      );
                $direc2         =(empty($direc2  ))?(" "):($direc2      );
                $colonia        =(empty($colonia ))?(" "):($colonia     );
                $ciudad         =(empty($ciudad  ))?(" "):($ciudad      );
                $munici         =(empty($munici  ))?(" "):($munici      );
                $cp             =(empty($cp      ))?(" "):($cp          );
                $telefono       =(empty($telefono))?(" "):($telefono);


                if(empty($edo_sepomex))
                  $estado = " ";
                else
                  $estado = $this->edo_sepomex_a_buro[$edo_sepomex];


                $sql = "INSERT INTO buro_seg_direccion
                        (ID_Cuenta, Dir1, Dir2, Col_Pob, Delegacion, Ciudad, Estado, Cp, Residencia, Telefono, Ext, Fax, Tipo_Dom, Ind_Dom)
                        VALUES
                        ('".$cuenta."',            
                        '".$this->sincomas($direc1)."', 
                        '".$this->sincomas($direc2)."', 
                        '".$this->sincomas($colonia)."', 
                        '".$this->sincomas($munici)."', 
                        '".$this->sincomas($ciudad)."', 
                        '".$this->sincomas($estado)."', 
                        '".$cp."', 
                        '',
                        '".$this->sincomas($telefono)."', 
                        '',
                        '',
                        '',
                        '') ";

                $this->db->Execute($sql);


               $rs->MoveNext();
           }



        //=========================================================================================
        // Segmento de Cuentas_Originales
        //=========================================================================================



        $sql = "   SELECT 
                               buro_base.ID_Buro                                    AS Cuenta,
                               fact_cliente.ID_factura                              AS ID_Credito,
                               fact_cliente.fecha_exp                               AS fecha_alta,
                               'I'          					    AS Tipo_Res,
                               'I'                                                  AS Tipo_Cue,
                               'PL'                                                 AS Tipo_Con,
                               'MX'                                                 AS Moneda,
                               fact_cliente.plazo                                   AS Num_Pag,
                               fact_cliente.Renta                                   AS Renta,       

                               case(fact_cliente.vencimiento)
                                          WHEN 'Semanas'    THEN 'W'
                                          WHEN 'Quincenas'  THEN 'S'                                          
                                          WHEN 'Meses'      THEN 'M'            
                                          WHEN 'Anios'      THEN 'A'                                          
                                end                                                  AS Frec_Pag, 

                               TRUNCATE((fact_cliente.Renta * fact_cliente.plazo),0) AS SaldoIni,
                               
                               
                               MAX(pagos.fecha)                                     AS Ultimo_Pago,
                            
                               
                               


                               MAX(creditos.Capital)                                 AS Credito_Maximo,
                               cierre_contable_log.Fecha_Cierre                      AS Fecha_Reporte,       

                               MAX(creditos.fecha_exp)                               AS Fecha_Ultima_Compra,

                               cierre_contable_saldos.DiasMora                       AS DiasMora, 

                               cierre_contable_saldos.Num_Cuotas_Vencidas            AS Num_Cuotas_Vencidas,

                                cierre_contable_saldos.Saldo_Vigente_Capital          AS Saldo_Vigente_Capital,

                               IF(cierre_contable_saldos.DiasMora <=0,0,
                               TRUNCATE((cierre_contable_saldos.Saldo_Vencido_Capital   +
                                cierre_contable_saldos.Saldo_Vencido_Comisiones         +
                                cierre_contable_saldos.Saldo_Vencido_Intereses          +
                                cierre_contable_saldos.Saldo_Vencido_Otros_Cargos       +
                                cierre_contable_saldos.Saldo_Vencido_Moratorios         ),0))  AS Saldo_Vencido,


                                TRUNCATE((cierre_contable_saldos.Monto_Saldo_a_favor+
                                 cierre_contable_saldos.Saldo_Vencido_Capital+
                                 cierre_contable_saldos.Saldo_Vencido_Comisiones+
                                 cierre_contable_saldos.Saldo_Vencido_Intereses+
                                 cierre_contable_saldos.Saldo_Vencido_Otros_Cargos+
                                 cierre_contable_saldos.Saldo_Vencido_Moratorios+
                                 cierre_contable_saldos.Saldo_Vigente_Capital+
                                 cierre_contable_saldos.Saldo_Vigente_Comisiones+
                                 cierre_contable_saldos.Saldo_Vigente_Intereses),0)     AS Saldo_Actual,
                                 

                   		 fact_cliente_vendida.Fecha_Cierre 			AS Fecha_Cierre_Cartera_Vendida,
                                 (fact_cliente_vendida.Saldo_Total_Vencido+
				  fact_cliente_vendida.Saldo_Total_Vigente) 		AS Saldo_cartera_vendida



                                 
                                 


                        FROM  buro_base


                        INNER JOIN buro_reporte  	ON buro_reporte.ID_Factura    = buro_base.ID_Factura and buro_reporte.Fecha_Reporte = '".$FECHA_CORTE."'

                        INNER JOIN fact_cliente   	ON fact_cliente.id_factura    = buro_reporte.ID_Factura
                        INNER JOIN clientes_datos 	ON clientes_datos.Num_cliente = fact_cliente.num_cliente

                        INNER JOIN clientes 		ON fact_cliente.Num_cliente   = clientes.Num_cliente 
                        INNER JOIN compras  		ON compras.Num_compra         = fact_cliente.Num_compra 


			LEFT JOIN factura_cliente_liquidacion ON factura_cliente_liquidacion.ID_Factura   = buro_base.ID_Factura and 
				                                 DATE(factura_cliente_liquidacion.Fecha) <= buro_reporte.Fecha_Reporte



			LEFT JOIN fact_cliente_vendida 		ON fact_cliente_vendida.ID_Factura   = buro_reporte.ID_Factura

                        

                        LEFT JOIN  pagos                        ON fact_cliente.num_compra           = pagos.num_compra and 
							                                               pagos.activo='S' and 
							                                               pagos.Fecha<=buro_reporte.Fecha_Reporte



                        

                        LEFT JOIN   fact_cliente AS creditos    ON creditos.num_cliente              = clientes.Num_cliente

                        LEFT JOIN cierre_contable_log           ON cierre_contable_log.Fecha_Cierre  = buro_reporte.Fecha_Reporte
                        LEFT JOIN cierre_contable_saldos        ON cierre_contable_saldos.ID_Cierre  = cierre_contable_log.ID_Cierre and 
                                                                   cierre_contable_saldos.ID_Factura = buro_base.id_factura";

                    if(is_array($id_empresas)){

                        $empresas = implode(",",$id_empresas);

                        $sql .= "   INNER JOIN credito_empresa_convenio ON credito_empresa_convenio.id_factura = buro_base.ID_Factura
                                WHERE credito_empresa_convenio.ID_empresa in($empresas)";
                    }

                $sql .= " GROUP BY buro_base.id_factura 

                        ORDER BY fact_cliente.num_cliente, buro_base.ID_Buro 



                        ";

        $rs=$this->db->Execute($sql);






        if($rs->_numOfRows)
           while(! $rs->EOF)
           {


             $num_compra          = $rs->fields['Num_compra']; 

             $cuenta              = $rs->fields['Cuenta']; 
             $fecha_alta          = $rs->fields['fecha_alta']; 
             $tipo_res            = $rs->fields['Tipo_Res']; 
             $tipo_cue            = $rs->fields['Tipo_Cue']; 
             $tipo_con            = $rs->fields['Tipo_Con']; 
             $moneda              = $rs->fields['Moneda']; 
             $num_pag             = $rs->fields['Num_Pag']; 
             $frec_pag            = $rs->fields['Frec_Pag']; 
             $saldoini            = $rs->fields['SaldoIni'];
             $fec_ult             = $rs->fields['Ultimo_Pago']; 
             $lim_cred            = $rs->fields['Credito_Maximo']; 
             $fec_cie             = $rs->fields['Fecha_Cierre'];
             $primer_incumplimiento = '01011900';
             $saldo_insoluto_principal = number_format( $rs->fields['Saldo_Vigente_Capital'],0,"","");
                        
            
             
             
             $clave_observacion   = '';

		


           // Le comento que cuando reporta cuentas a pagos fijos la etiqueta 14 (Fecha de última compra) 
           // se refiere a la fecha más reciente cuando el Cliente efectuó una disposición de crédito, el 
           // cual coincide con la fecha de apertura (Etiqueta 13).


             
             //$fec_compra          = $rs->fields['Fecha_Ultima_Compra']; 
             
             
             $fec_compra          = $fecha_alta;
             

             $num_cuenta_anterior = $rs->fields['Num_Cuenta_Anterior']; 


             $_fecha_alta = $fecha_alta;

             $fecha_alta      =(empty($fecha_alta)    )?(""):($fecha_alta    );
             $moneda          =(empty($moneda        ))?(""):($moneda                );
             $num_pag         =(empty($num_pag       ))?(""):($num_pag               );

             $fecha_alta     = fdia($fecha_alta).fmes($fecha_alta).fanio($fecha_alta);
             $fec_ult        = fdia($fec_ult).fmes($fec_ult).fanio($fec_ult);


             $fec_compra     = fdia($fec_compra).fmes($fec_compra).fanio($fec_compra);







            // $fec_rep        = fdia($fecha_hoy).fmes($fecha_hoy).fanio($fecha_hoy);
             
             $fec_rep        = $this->fecha_reporte_con_formato;





                $saldoini = number_format(  $saldoini,0,"","");




                $_vencidos              = number_format( $rs->fields['Num_Cuotas_Vencidas'],0,"","");
                $saldo_vencido          = number_format( $rs->fields['Saldo_Vencido'],0,"","");    

                $saldo_actual           = number_format( $rs->fields['Saldo_Actual'],0,"","");

                $pago_fijo              = number_format( $rs->fields['Renta'],0,"","");


                if($_vencidos <= 0)   $_vencidos = 0;




                 $cred_max = $rs->fields['Credito_Maximo'];


              //--------------------------------------------------------------------------------------------
              // Realmente la fecha de cierre está dada por el último abono, el que deja la cuenta liquidada
/*
                if (($saldo_actual < 1) and ($saldo_vencido  < 1))
                {        
                    $fecha_cierre = fdia($rs->fields['Ultimo_Pago']).fmes($rs->fields['Ultimo_Pago']).fanio($rs->fields['Ultimo_Pago']);
                    $pago_fijo = 0;
                    $_vencidos=0;        
                }
                else
*/
                  $fecha_cierre   = "";


                $dias_vencidos = $rs->fields['DiasMora'];




                if(( abs(fdifdias($_fecha_alta,$FECHA_CORTE)) < 25 ) and  ($saldo_vencido <1   ))
                {  
                    $mop="00";              
                    $_vencidos=0;            

                }
                else 

                if( ($saldo_vencido   < 1 ) or  ($saldo_actual < 1    ))
                {         
                        $mop="01";   
                        $_vencidos=0;
                }
                else       

                if($_vencidos == 0)
                {
                      $mop="01";  
                }
                else
                {
                        if( ($dias_vencidos >= 01 ) and ($dias_vencidos<= 29  )) $mop="02"; else        
                        if( ($dias_vencidos >= 30 ) and ($dias_vencidos<= 59  )) $mop="03"; else
                        if( ($dias_vencidos >= 60 ) and ($dias_vencidos<= 89  )) $mop="04"; else
                        if( ($dias_vencidos >= 90 ) and ($dias_vencidos<= 119 )) $mop="05"; else
                        if( ($dias_vencidos >= 120) and ($dias_vencidos<= 149 )) $mop="06"; else
                        if( ($dias_vencidos >= 150) and ($dias_vencidos<= 365 )) $mop="07"; else
                        if( $dias_vencidos  > 365 )                               $mop="99"; 
                }

                if($saldo_actual <1) $saldo_actual=0;

                if($saldo_vencido<1) $saldo_vencido=0;

                if($_vencidos<1)  $_vencidos=0;

                if($cred_max <1)  $cred_max=0;  
                
                
                
                
                if($saldo_vencido > $saldo_actual)
                {
                	$saldo_actual = $saldo_vencido;
                }
                
                
   
                if($saldo_actual <1)
                {
                	$saldo_actual=0;
                	$saldo_vencido=0;               	
                	$pago_fijo = 0;

                	$clave_observacion = "CC";


		      if(!empty($fec_cie))
		      {

			list($yy,$mm,$dd) = explode("-",$fec_cie);

			$fecha_cierre = $dd.$mm.$yy;
			
		      }
		      else
		      {
		      
		      	list($yy,$mm,$dd) = explode("-",$rs->fields['Ultimo_Pago']);
		      	
		      	$fecha_cierre = $dd.$mm.$yy;
		      	
		      }
                }	   
   
   
                
                
                
                
                
                
                if(!empty($rs->fields['Fecha_Cierre_Cartera_Vendida']))
                {
                
                	$fec_cie = $rs->fields['Fecha_Cierre_Cartera_Vendida'];
                	
                	$fec_cie = fdia($fec_cie).fmes($fec_cie).fanio($fec_cie);
                	
                	
                	if(($mop=="01") or ($mop=="00"))
                	{
                		$clave_observacion = "CA";
                	} 
                	else
                	{
                		$clave_observacion = "CV";                	
                	}
                	
                	$saldo_actual = 0;
                	$pago_fijo    = 0;
                	
                	
                	if($mop=="99")
                	{
                	  	$mop="96";
                	}
                }

               // $saldoini=$saldo_vencido;



        $sql = "INSERT INTO `buro_seg_cuenta`   
                        (ID_Cuenta ,Etiqueta ,Member_cve ,Nombre_usr ,Num_cuenta ,Tipo_resp ,Tipo_cta ,T_contrato ,Cve_moneda ,Importe_av ,Num_pagos ,Frec_pagos ,Monto ,Apertura ,Ult_pago ,Ult_compra ,F_cierre ,F_reporte ,Garantia ,Cred_max ,Saldo_act ,Cred_lim ,Saldo_venc ,Pagos_venc ,Forma_pago ,Hist_pagos ,Cve_observ ,Tot_pagos ,Tpag_mop2 ,Tpag_mop3 ,Tpag_mop4 ,Tpag_mop5 ,Cve_ant_ot ,Nombre_ant ,N_cta_ant, primer_inc, saldo_ins_prin, Fin )
                VALUES 
                ('".$cuenta."',            	
                 'TL',                 		
                 '".$MEMBER_CVE."',          	
                 '".$NOMBRE_USR."',          	
                 '".$cuenta."',              	
                 '".$tipo_res."',            	
                 '".$tipo_cue."',            	
                 '".$tipo_con."',            	
                 '".$moneda."',              	
                 '0',                  		
                 '".$num_pag."',             	
                 '".$frec_pag."',            	
                 '".$pago_fijo."',           	
                 '".$fecha_alta."',          	
                 '".$fec_ult."',             	
                 '".$fec_compra."',          	
                 '".$fecha_cierre."',        	
                 '".$fec_rep."',             	
                 '',                   		
                 '".$cred_max."',            	
                 '".$saldo_actual."',        	
                 '".$lim_cred."',            	
                 '".$saldo_vencido."',       	
                 '".$_vencidos."',           	
                 '".$mop."',                 	
                 '',                   		
                 '".$clave_observacion."',     	
                 '".($num_pag * 1)."',             	
                 '0',                   		
                 '0',                   		
                 '0',                   		
                 '0',                   		
                 '0',                   		
                 '0',                   		
                 '".$num_cuenta_anterior."',
                 '".$primer_incumplimiento."',
                 '".$saldo_insoluto_principal."', 	
                 'FIN' )";                	


                $this->db->Execute($sql);



             $rs->MoveNext();
           }


	//======================================================================================================
	// Colocamos todas la fecha de primer vencimiento en fecha primer incumpliento de todos los creditos que :
	// A) Se estén reportando con MOP > 01
	// B) Que hallan sido aperturados antes del 25/05/2011
	// Modificación : Enrique Godoy Calderón, Domingo, 19 de mayo de 2013
	//======================================================================================================	
         $sql_pv = "    UPDATE  buro_base 
			INNER JOIN fact_cliente 
				ON fact_cliente.id_factura = buro_base.ID_Factura
			       AND fact_cliente.fecha_exp >= '2010-05-25' 

			INNER JOIN  buro_seg_cuenta 
				ON  buro_seg_cuenta.ID_Cuenta = buro_base.ID_Buro 
			       AND  buro_seg_cuenta.Forma_pago != '00' 
			       AND  buro_seg_cuenta.Forma_pago != '01' 

			INNER JOIN cargos 
				ON cargos.ID_Cargo = 1
			       AND cargos.Num_compra = fact_cliente.num_compra 

			SET   buro_base.primer_inc = cargos.Fecha_vencimiento ";  

 	 $this->db->Execute($sql_pv);
	//======================================================================================================






}













};





?>