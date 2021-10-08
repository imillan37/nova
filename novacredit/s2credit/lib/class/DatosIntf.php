<?php

class DatosIntf
{
    private $db;
    private $FECHA_CORTE;
    private $Clave_usuario;
    private $NOMBRE_USR;
    private $TotSaldosActuales;
    private $TotSaldosVencidos;
    
    public  $ArrEncabezado    = array();
    public  $ArrNombre        = array();
    public  $ArrDireccion     = array();
    public  $ArrEmpleo        = array();
    public  $ArrCuenta        = array();
    public  $ArrNombreAval    = array();
    public  $ArrDireccionAval = array();
    public  $ArrEmpleoAval    = array();
    public  $ArrCuentaAval    = array();
    public  $ArrCierre        = array();
    public  $ID_Cuentas       = array();
    public  $ID_CuentasAval   = array();
    public  $NombreTablas      = array();
    public  $error            = 0;
    public $NombreTabla;  
  

        
    
        public function __construct($db)
        {
            $this->db = $db;
            
            $this->NombreTablas[0]='buro_encabezado';
            $this->NombreTablas[1]='buro_seg_nombre';
            $this->NombreTablas[2]='buro_seg_direccion';
            $this->NombreTablas[3]='buro_seg_empleo';
            $this->NombreTablas[4]='buro_seg_cuenta';
            
        }
    
        
        public function Reporte($id_empresas = null)
        {

            $this->id_empresas = $id_empresas;
            
            $this->VaciarTablas();
            $this->FECHA_CORTE = $this->DatosReporte();         
            $this->SetDatosSegmentos(); 
            
                        
            $this->VerificarDatosTablas();            
            
            if($this->error == 0)
            {                      
                $this->ID_Cuentas     = $this->ID_Cuentas();
                $this->GetDatosSegmentos();
                
                $sql="SELECT Reportar_avales FROM buro_encabezado";
                $rs = $this->db->Execute($sql);
                
                if($rs->fields['Reportar_avales']=='Y')
                {   
                    $this->VaciarTablasAval();
                    $this->DatosReporteAval();
                    $this->SetDatosSegmentosAval();
                    $this->ID_CuentasAval = $this->ID_CuentasAval();                     
                    $this->GetDatosSegmentosAval();
                }
                  $this->ArrCierre     = $this->ObtenerSegCierre();
       
                    
            }      
        }

  
//============================================================OBTENER DATOS DE SEGMENTOS======================================================
 
        private function SetDatosSegmentos()
        {            
              $this->DatosSegNombre();
              $this->DatosDireccion();
              $this->DatosEmpleo();
              $this->DatosCuenta();       
        }
        

  
//============================================================OBTENER DATOS DE SEGMENTOS AVAL======================================================
 
        private function SetDatosSegmentosAval()
        {            
              $this->DatosNombreAval();
              $this->DatosDireccionAval();
              $this->DatosEmpleoAval();
              $this->DatosCuentaAval();       
        }
        
//======================================================OBTENER SEGMENTOS (GENERAR ARREGLOS)=====================================
 
        private function GetDatosSegmentos()
        {
            $this->ArrEncabezado = $this->ObtenerSegEncabezado();
            $this->ArrNombre     = $this->ObtenerSegNombre();
            $this->ArrDireccion  = $this->ObtenerSegDireccion();
            $this->ArrEmpleo     = $this->ObtenerSegEmpleo();
            $this->ArrCuenta     = $this->ObtenerSegCuenta();
           }

//======================================================OBTENER SEGMENTOS (GENERAR ARREGLOS) AVAL================================
 
        private function GetDatosSegmentosAval()
        {
            $this->ArrNombreAval     = $this->ObtenerSegNombreAval();
            $this->ArrDireccionAval  = $this->ObtenerSegDireccionAval();
            $this->ArrEmpleoAval     = $this->ObtenerSegEmpleoAval();
            $this->ArrCuentaAval     = $this->ObtenerSegCuentaAval();
      }

//=======================================================ARREGLO ID CUENTAS=====================================================
        private function  ID_Cuentas()
        { 
            $i=1;
            $sql = "SELECT   buro_seg_nombre.ID_Cuenta        
                    FROM     buro_seg_nombre, buro_seg_direccion, buro_seg_cuenta, buro_seg_empleo
                    WHERE    buro_seg_nombre.ID_Cuenta = buro_seg_direccion.ID_Cuenta and
                             buro_seg_nombre.ID_Cuenta = buro_seg_cuenta.ID_Cuenta    and
                             buro_seg_nombre.ID_Cuenta = buro_seg_empleo.ID_Cuenta
                    ORDER BY buro_seg_nombre.ID_Cuenta ";

            $rs = $this->db->Execute($sql);
            if($rs->_numOfRows)
               while(! $rs->EOF)
               {

                    $ArrID_Cuentas[$i]=$rs->fields['ID_Cuenta'];
                    $i++;
                    $rs->MoveNext();
                }
                
            return $ArrID_Cuentas;
        }

/*----------------------------------------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------------------------------------*/  
        
        private function VerificarDatosTablas()
        {           
            for ($i=0;$i<count($this->NombreTablas);$i++)
            {
                $sql = "SELECT COUNT(*) FROM ".$this->NombreTablas[$i];
                $rs=$this->db->Execute($sql);
            
                if ($rs->fields[0] < 1)
                { 
                    $this->error = 1;
                    $this->NombreTabla = $this->NombreTablas[$i];
                    break;
                }
                
            }        
        }
        
        
        
        private function ObtenerSegEncabezado()
        {    
            $sql = "SELECT VERSION,
                       CLAVE_KOB, 
                       CALVE_USR,
                       NOMBRE,  
                       CICLO, 
                       FECHA_REPORTE, 
                       USO_FUTURO,
                       ADICIONAL   
                  FROM buro_encabezado ";

            $rs = $this->db->Execute($sql);
            $this->Clave_usuario = $rs->fields['CLAVE_KOB'].$rs->fields['CALVE_USR'];;

            $ArrEncabezado['Clave_Otorgante'] = $this->Clave_usuario;
            $ArrEncabezado['Version']         = $rs->fields['VERSION'];
            $ArrEncabezado['Nombre_Usuario']  = trim($rs->fields['NOMBRE']);
            $ArrEncabezado['Reservado']       = trim($rs->fields['CICLO']);
            $ArrEncabezado['Fecha_Reporte']   = fdia($rs->fields['FECHA_REPORTE']).fmes($rs->fields['FECHA_REPORTE']).fanio($rs->fields['FECHA_REPORTE']); 
            $ArrEncabezado['Uso_Futuro']      = $rs->fields['USO_FUTURO'];
            $ArrEncabezado['Adicional']       = $rs->fields['ADICIONAL'];

            return $ArrEncabezado;

        }


        private function ObtenerSegNombre()
        {
            $sql ="SELECT ID_Cuenta,
                       AP_PATERNO, 
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
                ORDER BY ID_Cuenta ";

            $rs =  $this->db->Execute($sql);

            if($rs->_numOfRows){
                while(! $rs->EOF)
                {
                   $ArrNombre[$rs->fields['ID_Cuenta']] ['ID_Cuenta']          =   $rs->fields['ID_Cuenta'];
                   $ArrNombre[$rs->fields['ID_Cuenta']] ['Ap_Paterno']         =   $rs->fields['AP_PATERNO'];
                   $ArrNombre[$rs->fields['ID_Cuenta']] ['Ap_Materno']         =   $rs->fields['AP_MATERNO'];
                   $ArrNombre[$rs->fields['ID_Cuenta']] ['Ap_Adicional']       =   $rs->fields['AP_ADICION'];
                   $ArrNombre[$rs->fields['ID_Cuenta']] ['Primer_Nombre']      =   $rs->fields['PRI_NOMBRE'];
                   $ArrNombre[$rs->fields['ID_Cuenta']] ['Segundo_Nombre']     =   $rs->fields['SEG_NOMBRE'];
                   $ArrNombre[$rs->fields['ID_Cuenta']] ['Fecha_Nacimiento']   =   $rs->fields['NACIMIENTO'];         
                   $ArrNombre[$rs->fields['ID_Cuenta']] ['RFC']                =   $rs->fields['RFC'];
                   $ArrNombre[$rs->fields['ID_Cuenta']] ['Prefijo']            =   $rs->fields['PREFIJO'];
                   $ArrNombre[$rs->fields['ID_Cuenta']] ['Sufijo']             =   $rs->fields['SUFIJO'];
                   $ArrNombre[$rs->fields['ID_Cuenta']] ['Nacionalidad']       =   $rs->fields['NACIONALID'];
                   $ArrNombre[$rs->fields['ID_Cuenta']] ['Residencia']         =   $rs->fields['RESIDENCIA'];
                   $ArrNombre[$rs->fields['ID_Cuenta']] ['Lic_Manejo']         =   $rs->fields['LIC_MANEJO'];
                   $ArrNombre[$rs->fields['ID_Cuenta']] ['Edo_Civil']          =   $rs->fields['EDO_CIVIL'];
                   $ArrNombre[$rs->fields['ID_Cuenta']] ['Sexo']               =   $rs->fields['SEXO'];
                   $ArrNombre[$rs->fields['ID_Cuenta']] ['Cedula']             =   $rs->fields['CEDULA'];
                   $ArrNombre[$rs->fields['ID_Cuenta']] ['Registro_Electoral'] =   $rs->fields['IFE'];   
                   $ArrNombre[$rs->fields['ID_Cuenta']] ['Imp_Pais']           =   $rs->fields['IMP_PAIS'];
                   $ArrNombre[$rs->fields['ID_Cuenta']] ['CURP']               =   $rs->fields['CURP'];
                   $ArrNombre[$rs->fields['ID_Cuenta']] ['Clave_Pais']         =   $rs->fields['CVE_PAIS'];
                   $ArrNombre[$rs->fields['ID_Cuenta']] ['Num_Dependientes']   =   $rs->fields['NUM_DEPEND'];
                   $ArrNombre[$rs->fields['ID_Cuenta']] ['Edades_Dependientes']=   $rs->fields['EDADES_DEP'];
                   $ArrNombre[$rs->fields['ID_Cuenta']] ['Defuncion']          =   $rs->fields['DEFUNCION'];
                   $ArrNombre[$rs->fields['ID_Cuenta']] ['Indicador_Defuncion']=   $rs->fields['MURIO']; 

                   $rs->MoveNext();
                }
            }

            return $ArrNombre;
         }


        private function ObtenerSegDireccion()
        {
           $sql ="SELECT ID_Cuenta, 
                         DIR1, 
                         DIR2, 
                         COL_POB, 
                         DELEGACION, 
                         CIUDAD, 
                         ESTADO, 
                         CP, 
                         RESIDENCIA, 
                         TELEFONO, 
                         EXT, 
                         FAX, 
                         TIPO_DOM 
                FROM    buro_seg_direccion
                ORDER BY ID_Cuenta ";

         $rs = $this->db->Execute($sql);

            if($rs->_numOfRows){
                while(! $rs->EOF)
                {            

                    $ArrDireccion[$rs->fields['ID_Cuenta']] ['ID_Cuenta']          =   $rs->fields['ID_Cuenta']; 
                    $ArrDireccion[$rs->fields['ID_Cuenta']] ['Dir1']               =   str_replace("  "," ",$rs->fields['DIR1']);          
                    $ArrDireccion[$rs->fields['ID_Cuenta']] ['Dir2']               =   $rs->fields['DIR2'];            
                    $ArrDireccion[$rs->fields['ID_Cuenta']] ['Col_Poblacion']      =   $rs->fields['COL_POB'];
                    $ArrDireccion[$rs->fields['ID_Cuenta']] ['Delegacion_Municip'] =   $rs->fields['DELEGACION'];     
                    $ArrDireccion[$rs->fields['ID_Cuenta']] ['Ciudad']             =   $rs->fields['CIUDAD'];
                    $ArrDireccion[$rs->fields['ID_Cuenta']] ['Estado']             =   $rs->fields['ESTADO'];
                    $ArrDireccion[$rs->fields['ID_Cuenta']] ['CP']                 =   $rs->fields['CP'];            
                    $ArrDireccion[$rs->fields['ID_Cuenta']] ['Fecha_Residencia']   =   $rs->fields['RESIDENCIA'];           
                    $ArrDireccion[$rs->fields['ID_Cuenta']] ['Telefono']           =   $rs->fields['TELEFONO'];   
                    $ArrDireccion[$rs->fields['ID_Cuenta']] ['Extension']          =   $rs->fields['EXT'];
                    $ArrDireccion[$rs->fields['ID_Cuenta']] ['Fax']                =   $rs->fields['FAX'];
                    $ArrDireccion[$rs->fields['ID_Cuenta']] ['Tipo_Domicilio']     =   $rs->fields['TIPO_DOM'];
                    $ArrDireccion[$rs->fields['ID_Cuenta']] ['Indicador_Dom']      =   $rs->fields['IND_DOM'];
                    $ArrDireccion[$rs->fields['ID_Cuenta']] ['Origen_Dom']         =   'MX';

                     $rs->MoveNext();

                }
            }
            return $ArrDireccion;


        }

        private function ObtenerSegEmpleo()
        {
           $sql ="SELECT ID_Cuenta,
                         RAZON_SOCIAL_EMP,
                         DIR1, 
                         DIR2, 
                         COL_POB, 
                         DELEGACION_MUNICIP, 
                         CIUDAD, 
                         ESTADO, 
                         CP, 
                         TELEFONO, 
                         EXT, 
                         FAX, 
                         OCUPACION,
                         CONTRATACION,
                         CLAVE_MONEDA,
                         SUELDO,
                         PERIODO_PAGO,
                         NUM_EMPLEADO,
                         ULTIMO_EMPLEO,
                         VERIFICACION_EMPLEO,
                         ORIGEN
                FROM    buro_seg_empleo
                ORDER BY ID_Cuenta ";

         $rs =  $this->db->Execute($sql);

            if($rs->_numOfRows){
                while(! $rs->EOF)
                {            
                    $ArrEmpleo[$rs->fields['ID_Cuenta']] ['ID_Cuenta']             =   $rs->fields ['ID_Cuenta']; 
                    $ArrEmpleo[$rs->fields['ID_Cuenta']] ['Razon_Social_EM']       =   $rs->fields ['RAZON_SOCIAL_EMP'];
                    $ArrEmpleo[$rs->fields['ID_Cuenta']] ['Dir1']                  =   str_replace("  "," ",$rs->fields['DIR1']); 
                    $ArrEmpleo[$rs->fields['ID_Cuenta']] ['Dir2']                  =   $rs->fields ['DIR2'];            
                    $ArrEmpleo[$rs->fields['ID_Cuenta']] ['Col_Poblacion']         =   $rs->fields ['COL_POB'];
                    $ArrEmpleo[$rs->fields['ID_Cuenta']] ['Delegacion_Municip']    =   $rs->fields ['DELEGACION_MUNICIP']; 
                    $ArrEmpleo[$rs->fields['ID_Cuenta']] ['Ciudad']                =   $rs->fields ['CIUDAD'];
                    $ArrEmpleo[$rs->fields['ID_Cuenta']] ['Estado']                =   $rs->fields ['ESTADO'];
                    $ArrEmpleo[$rs->fields['ID_Cuenta']] ['CP']                    =   $rs->fields ['CP'];            
                    $ArrEmpleo[$rs->fields['ID_Cuenta']] ['Telefono']              =   $rs->fields ['TELEFONO'];
                    $ArrEmpleo[$rs->fields['ID_Cuenta']] ['Extension']             =   $rs->fields ['EXT'];
                    $ArrEmpleo[$rs->fields['ID_Cuenta']] ['Fax']                   =   $rs->fields ['FAX'];
                    $ArrEmpleo[$rs->fields['ID_Cuenta']] ['Ocupacion']             =   $rs->fields ['OCUPACION'];
                    $ArrEmpleo[$rs->fields['ID_Cuenta']] ['Fecha_Contratacion']    =   $rs->fields ['CONTRATACION'];
                    $ArrEmpleo[$rs->fields['ID_Cuenta']] ['Clave_Moneda']          =   $rs->fields ['CLAVE_MONEDA'];
                    $ArrEmpleo[$rs->fields['ID_Cuenta']] ['Sueldo']                =   $rs->fields ['SUELDO'];
                    $ArrEmpleo[$rs->fields['ID_Cuenta']] ['Periodo_Pago']          =   $rs->fields ['PERIODO_PAGO'];
                    $ArrEmpleo[$rs->fields['ID_Cuenta']] ['Num_Empleado']          =   $rs->fields ['NUM_EMPLEADO'];
                    $ArrEmpleo[$rs->fields['ID_Cuenta']] ['Fecha_Ultimo_Empleo']   =   $rs->fields ['ULTIMO_EMPLEO'];
                    $ArrEmpleo[$rs->fields['ID_Cuenta']] ['Verificacion_Empleo']   =   $rs->fields ['VERIFICACION_EMPLEO'];
                    $ArrEmpleo[$rs->fields['ID_Cuenta']] ['Origen']                =   $rs->fields ['ORIGEN'];;

                     $rs->MoveNext();
                }

            }      


            return $ArrEmpleo;
        }


        private function ObtenerSegCuenta()
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
                             MONTO_ULTIMO_PAGO,
                             PLAZO_MESES,
                             MONTO_ORIGINACION,
                             FIN

                        FROM buro_seg_cuenta INNER JOIN buro_base ON buro_base.ID_Buro = buro_seg_cuenta.ID_Cuenta
                        ORDER BY ID_Cuenta";


               $rs = $this->db->Execute($sql);


            if($rs->_numOfRows){
                while(! $rs->EOF)
                {
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Nombre_Seg']        =   $rs->fields['ETIQUETA'];
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Clave_Usuario']     =   $this->Clave_usuario;
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Nombre_Usuario']    =   $rs->fields['NOMBRE_USR'];
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Num_Cuenta_Actual'] =   $rs->fields['ID_Cuenta'];
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Tipo_Respons']      =   $rs->fields['TIPO_RESP'];
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Tipo_Cuenta']       =   $rs->fields['TIPO_CTA'];
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Tipo_Contrato']     =   $rs->fields['T_CONTRATO'];
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Moneda']            =   $rs->fields['CVE_MONEDA'];
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Importe_Avaluo']    =   $rs->fields['IMPORTE_AV'];
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Num_Pagos']         =   $rs->fields['NUM_PAGOS'];
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Frec_Pagos']        =   $rs->fields['FREC_PAGOS'];
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Monto_Pagar']       =   $rs->fields['MONTO'];
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Fecha_Apertura']    =   $rs->fields['APERTURA'];
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Ultimo_Pago']       =   $rs->fields['ULT_PAGO'];
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Ultima_Compra']     =   $rs->fields['ULT_COMPRA'];
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Fecha_Cierre']      =   $rs->fields['F_CIERRE'];
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Fecha_Reporte']     =   $rs->fields['F_REPORTE'];            
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Garantia']          =   $rs->fields['GARANTIA'];
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Credito_Max']       =   $rs->fields['CRED_MAX'];
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Saldo_Actual']      =   $rs->fields['SALDO_ACT'];
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Limite_Credito']    =   $rs->fields['CRED_LIM'];
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Saldo_Vencido']     =   $rs->fields['SALDO_VENC'];
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Num_Pagos_Vencidos']=   $rs->fields['PAGOS_VENC'];
                    
                    $this->TotSaldosActuales =   $this->TotSaldosActuales + $rs->fields['SALDO_ACT'];
                    $this->TotSaldosVencidos =   $this->TotSaldosVencidos + $rs->fields['SALDO_VENC'];
                    
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Forma_Pago']        =   $rs->fields['FORMA_PAGO'];
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Clave_Observacion'] =   $rs->fields['CVE_OBSERV'];
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Tot_Pagos']         =   $rs->fields['TOT_PAGOS'];                   
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['TPAG_MOP3']         =   $rs->fields['TPAG_MOP3'];
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['TPAG_MOP4']         =   $rs->fields['TPAG_MOP4'];
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['TPAG_MOP5']         =   $rs->fields['TPAG_MOP5'];
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['CVE_ANT_OT']        =   $rs->fields['CVE_ANT_OT'];
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Nom_Usu_Ant']       =   $rs->fields['NOMBRE_ANT'];
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Num_Cuenta_Ant']    =   $rs->fields['N_CTA_ANT'];
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Primer_Incumpliento']=  $rs->fields['PRIMER_INC'];
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Saldo_Insoluto']    =   $rs->fields['SALDO_INS_PRIN'];
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Monto_Ultimo_Pago'] =   $rs->fields['MONTO_ULTIMO_PAGO'];
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Plazo_Meses']       =   $rs->fields['PLAZO_MESES'];
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Monto_Originacion'] =   $rs->fields['MONTO_ORIGINACION'];
                    $ArrCuenta[$rs->fields['ID_Cuenta']] ['Fin']               =   'FIN' ;

                    $rs->MoveNext();

                }
            }

            return $ArrCuenta;
         }


        private function ObtenerSegCierre()
        {

            
        
            $Total_seg_nombre    = count($this->ArrNombre)   + count($this->ArrNombreAval) ;
            $Total_seg_direccion = count($this->ArrDireccion)+ count($this->ArrDireccionAval) ;
            $Total_seg_empleo    = count($this->ArrEmpleo)   + count($this->ArrEmpleoAval) ;
            $Total_seg_cuenta    = count($this->ArrCuenta)   + count($this->ArrCuentaAval) ;
            $TotalSegmentos      = count($this->ID_Cuentas)  + count($this->ID_CuentasAval);
            
            $TotalSegmentos = (empty($TotalSegmentos)?('0'):($TotalSegmentos));
            
            $ArrCierre['Total_Saldos_Actuales'] = $this->TotSaldosActuales;
            $ArrCierre['Total_Saldos_Vencidos'] = $this->TotSaldosVencidos;
            $ArrCierre['Total_Seg_Encabezado']  = '001';        
            $ArrCierre['Total_Seg_Nombre']      = $TotalSegmentos ;
            $ArrCierre['Total_Seg_Direccion']   = $TotalSegmentos ;
            $ArrCierre['Total_Seg_Empleo']      = $TotalSegmentos ;
            $ArrCierre['Total_Seg_Cuenta']      = $TotalSegmentos ;
            $ArrCierre['Contador_Bloques']      = '0';
            $ArrCierre['Nom_Devolucion']        = $this->NOMBRE_USR;
            $ArrCierre['Dir_Devolucion']        = '';     


            return $ArrCierre;

        }


/*=========================================================================DATOS REPORTE========================================================================
 * ============================================================================================================================================================= */

        private function DatosReporte()
        {

            $sql="  SELECT Clave_kob, Calve_usr, Nombre, Fecha_reporte
                        FROM buro_encabezado ";

            $rs = $this->db->Execute($sql);

            $MEMBER_CVE           =       $rs->fields['Clave_kob'].$rs->fields['Calve_usr'];
            $this->NOMBRE_USR     =       $rs->fields['Nombre'];
            $FECHA_CORTE          =       $rs->fields['Fecha_reporte'];

            $fecha_reporte = 

            //=========================================================================================

            list($AA,$MM,$DD)= explode("-",$FECHA_CORTE);

            $fecha_reporte_con_formato = date("dmY", mktime(0,0,0,$MM,$DD,$AA));

            //=========================================================================================
            $sql = "DROP TABLE IF EXISTS buro_reporte ";
            $this->db->Execute($sql);


            $sql = " CREATE TABLE buro_reporte (
                            ID_Factura 	INT(10) UNSIGNED NOT NULL DEFAULT '0',
                            Fecha_Reporte 	VARCHAR(10) NOT NULL DEFAULT '',
                            Regimen 	VARCHAR(14) NOT NULL DEFAULT '',
                            PRIMARY KEY (ID_Factura)
                    )
                    COLLATE='latin1_swedish_ci'
                    ENGINE=MyISAM ";       

            $this->db->Execute($sql);



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

            if(is_array($this->id_empresas)){

                        $empresas = implode(",",  $this->id_empresas);

                        $sql .= "   INNER JOIN credito_empresa_convenio ON credito_empresa_convenio.id_factura = fact_cliente.id_factura
                                WHERE credito_empresa_convenio.ID_empresa in($empresas) ";
            }

            $sql .= "

			 GROUP BY  cierre_contable_saldos.ID_Factura 
                 )
                 ";

            $this->db->Execute($sql);
          
            return $FECHA_CORTE;

        }
        

        private function VaciarTablas()
        {
            $sql ="DELETE FROM buro_seg_nombre ";
            $this->db->Execute($sql);


            $sql ="DELETE FROM buro_seg_direccion ";
            $this->db->Execute($sql);
            
             $sql ="DELETE FROM buro_seg_empleo ";
            $this->db->Execute($sql);

            $sql ="DELETE FROM buro_seg_cuenta ";
            $this->db->Execute($sql);




        }


/*===================================================================DATOS SEGMENTO NOMBRE======================================================================
 *============================================================================================================================================================== */
        private function DatosSegNombre()
        {
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
                                            buro_reporte.Fecha_Reporte  = '".  $this->FECHA_CORTE ."'

                INNER JOIN fact_cliente   ON fact_cliente.id_factura    = buro_reporte.ID_Factura
                INNER JOIN clientes_datos ON clientes_datos.Num_cliente = fact_cliente.num_cliente";

                    if(is_array($this->id_empresas)){

                        $empresas = implode(",",  $this->id_empresas);

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

                $materno = (empty($materno))?("NO PROPORCIONADO"):($materno);
                $sexo    = (empty($sexo))?(" "):($sexo);     

                $_nacimiento= fdia($fec_nac).fmes($fec_nac).fanio($fec_nac);

                $sql= "INSERT INTO buro_seg_nombre  
                        (ID_Cuenta, ap_paterno, ap_materno, pri_nombre, seg_nombre, nacimiento ,rfc, nacionalid, sexo, num_depend, murio)
                       VALUES
                       ('".$cuenta."',
                        '".$this->sincaracteres($paterno)."',
                        '".$this->sincaracteres($materno)."',
                        '".$this->sincaracteres($nombre1)."',
                        '".$this->sincaracteres($nombre2)."',
                        '".$_nacimiento."',
                        '".$rfc."',   
                        'MX',       
                        '".$sexo."',   
                        '0',        
                        'N') ";

                $this->db->Execute($sql);

               $rs->MoveNext();
            }

        }

/*===================================================================DATOS SEGMENTO DIRECCIÓN===================================================================
 * ============================================================================================================================================================= */

        private function DatosDireccion()
        {
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
                                            buro_reporte.Fecha_Reporte =  '".  $this->FECHA_CORTE ."'

                INNER JOIN fact_cliente   ON fact_cliente.id_factura    = buro_reporte.ID_Factura
                INNER JOIN clientes_datos ON clientes_datos.Num_cliente = fact_cliente.num_cliente
                LEFT JOIN estados         ON estados.Nombre        = clientes_datos.Estado ";

                    if(is_array($this->id_empresas)){

                        $empresas = implode(",",  $this->id_empresas);

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
                  $estado = $edo_sepomex;


                $sql = "INSERT INTO buro_seg_direccion
                        (ID_Cuenta, Dir1, Dir2, Col_Pob, Delegacion, Ciudad, Estado, Cp, Residencia, Telefono, Ext, Fax, Tipo_Dom, Ind_Dom)
                        VALUES
                        ('".$cuenta."',            
                        '".$this->sincaracteres($direc1)."', 
                        '".$this->sincaracteres($direc2)."', 
                        '".$this->sincaracteres($colonia)."', 
                        '".$this->sincaracteres($munici)."', 
                        '".$this->sincaracteres($ciudad)."', 
                        '".$this->sincaracteres($estado)."', 
                        '".$cp."', 
                        '',
                        '".$this->sincaracteres($telefono)."', 
                        '',
                        '',
                        '',
                        '') ";

                $this->db->Execute($sql);


               $rs->MoveNext();
           }

        }


/*=====================================================================DATOS SEGMENTO EMPLEO====================================================================
* ============================================================================================================================================================= */
        private function DatosEmpleo()
        {
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
                                            buro_reporte.Fecha_Reporte =  '".  $this->FECHA_CORTE ."'

                INNER JOIN fact_cliente   ON fact_cliente.id_factura    = buro_reporte.ID_Factura
                INNER JOIN clientes_datos ON clientes_datos.Num_cliente = fact_cliente.num_cliente
                LEFT JOIN estados         ON estados.Nombre        = clientes_datos.Estado ";

                    if(is_array($this->id_empresas)){

                        $empresas = implode(",",  $this->id_empresas);

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

                        $razon_social   =(empty($razon_social))?("TRABAJADOR INDEPENDIENTE"):($razon_social);
                        $direc1         =(empty($direc1  ))?(" "):($direc1      );
                        $direc2         =(empty($direc2  ))?(" "):($direc2      );
                        $colonia        =(empty($colonia ))?(" "):($colonia     );
                        $ciudad         =(empty($ciudad  ))?(" "):($ciudad      );
                        $munici         =(empty($munici  ))?(" "):($munici      );
                        $cp             =(empty($cp      ))?(" "):($cp          );
                        $telefono       =(empty($telefono))?(" "):($telefono);

                    //debug($direc1);


                        if(empty($edo_sepomex))
                          $estado = " ";
                        else 
                            $estado=$edo_sepomex;



                        $sql = "INSERT INTO buro_seg_empleo
                                (ID_Cuenta, razon_social_emp, dir1, dir2, col_pob, delegacion_municip, ciudad, estado, cp, telefono, ext, fax, ocupacion, contratacion, clave_moneda, sueldo, periodo_pago, num_empleado, ultimo_empleo, verificacion_empleo, origen)
                                VALUES
                                ('".$cuenta."',
                                '".$this->sincaracteres($razon_social)."',
                                '".$this->sincaracteres($direc1)."', 
                                '".$this->sincaracteres($direc2)."', 
                                '".$this->sincaracteres($colonia)."', 
                                '".$this->sincaracteres($munici)."', 
                                '".$this->sincaracteres($ciudad)."', 
                                '".$this->sincaracteres($estado)."', 
                                '".$cp."', 
                                '".$this->sincaracteres($telefono)."', 
                                '',
                                '',
                                '',
                                '',
                                '',
                                '0',
                                '',
                                '',
                                '',
                                '',
                                'MX') ";

                       $this->db->Execute($sql);
                       $rs->MoveNext();
                   }

        }

/*=====================================================================DATOS SEGMENTO CUENTA====================================================================
 * ============================================================================================================================================================= */
        private function DatosCuenta(){
            $sql = "   SELECT 
                               buro_base.ID_Buro                                    AS Cuenta,
                               fact_cliente.Num_compra                              AS Num_compra,
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
                                          WHEN 'Anios'      THEN 'Y'                                          
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
				  fact_cliente_vendida.Saldo_Total_Vigente) 		AS Saldo_cartera_vendida,
                                  case(fact_cliente.vencimiento)
                                          WHEN 'Semanas'    THEN (fact_cliente.plazo*7)/30.4
                                          WHEN 'Quincenas'  THEN (fact_cliente.plazo*15)/30.4                                         
                                          WHEN 'Meses'      THEN fact_cliente.plazo            
                                          WHEN 'Anios'      THEN fact_cliente.plazo*12                                         
                                end                                                  AS Monto_plazo,
                                fact_cliente.Capital                                 As Monto_originacion



                                 
                                 


                        FROM  buro_base


                        INNER JOIN buro_reporte  	ON buro_reporte.ID_Factura    = buro_base.ID_Factura and buro_reporte.Fecha_Reporte = '".  $this->FECHA_CORTE."'

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

                    if(is_array($this->id_empresas)){

                        $empresas = implode(",",  $this->id_empresas);

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
             $lim_cred            = $rs->fields['Monto_originacion']; 
             $fec_cie             = $rs->fields['Fecha_Cierre'];
             $plazo_meses         = $rs->fields['Monto_plazo'];
             $monto_originacion   = $rs->fields['Monto_originacion'];
             $primer_incumplimiento = '01011900';
             $saldo_insoluto_principal = number_format( $rs->fields['Saldo_Vigente_Capital'],0,"","");
                        
           
             $clave_observacion   = '';

             $sql_aux="SELECT pagos.monto AS Monto_ultimo_pago FROM pagos WHERE fecha='".$fec_ult."' AND Num_compra='".$num_compra."'";
             $rs_aux=$this->db->Execute($sql_aux);
             $monto_ult_pago= $rs_aux->fields['Monto_ultimo_pago'];

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

            list($AA,$MM,$DD)= explode("-",$this->FECHA_CORTE);
            $Fecha_reporte   = date("dmY", mktime(0,0,0,$MM,$DD,$AA));
            $fec_rep         = $Fecha_reporte;
            
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

                if(( abs(fdifdias($_fecha_alta,  $this->FECHA_CORTE)) < 25 ) and  ($saldo_vencido <1   ))
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
                        (ID_Cuenta ,Etiqueta ,Member_cve ,Nombre_usr ,Num_cuenta ,Tipo_resp ,Tipo_cta ,T_contrato ,Cve_moneda ,Importe_av ,Num_pagos ,Frec_pagos ,Monto ,Apertura ,Ult_pago ,Ult_compra ,F_cierre ,F_reporte ,Garantia ,Cred_max ,Saldo_act ,Cred_lim ,Saldo_venc ,Pagos_venc ,Forma_pago ,Hist_pagos ,Cve_observ ,Tot_pagos ,Tpag_mop2 ,Tpag_mop3 ,Tpag_mop4 ,Tpag_mop5 ,Cve_ant_ot ,Nombre_ant ,N_cta_ant, primer_inc, saldo_ins_prin, monto_ultimo_pago, plazo_meses, monto_originacion, Fin )
                VALUES 
                ('".$cuenta."',            	
                 'TL',                 		
                 '".$MEMBER_CVE."',          	
                 '".$this->NOMBRE_USR."',          	
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
                 '".$monto_ult_pago."',
                 '".$plazo_meses."',
                 '".$monto_originacion."',
                 'FIN' )";                	


                $this->db->Execute($sql);



             $rs->MoveNext();
           }

            //======================================================================================================
            // Colocamos todas la fecha de primer vencimiento en fecha primer incumpliento de todos los creditos que :
            // A) Se están reportando con MOP > 01
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

        }


        private function sincaracteres($token)
        {
              $busc = array(",","'",'"',"\\");
              $repl = array("","","","");
              return (str_replace($busc, $repl,$token));   

        }
        
/***===================================================================DATOS DE AVALES===================================================================================
 ***=====================================================================================================================================================================***/

  private function ID_CuentasAval()
  { 
            $i=1;
            $sql = "SELECT   buro_seg_nombre_aval.ID_Cuenta, buro_seg_nombre_aval.ID_Aval

                    FROM buro_seg_cuenta_aval

                    INNER JOIN buro_seg_nombre_aval    ON buro_seg_nombre_aval.ID_Cuenta = buro_seg_cuenta_aval.ID_Cuenta
                    INNER JOIN buro_seg_direccion_aval ON buro_seg_nombre_aval.ID_Cuenta = buro_seg_direccion_aval.ID_Cuenta
                    INNER JOIN buro_seg_empleo_aval    ON buro_seg_empleo_aval.ID_Cuenta = buro_seg_cuenta_aval.ID_Cuenta

                    GROUP BY buro_seg_nombre_aval.ID_Cuenta, buro_seg_nombre_aval.ID_Aval
                    ORDER BY buro_seg_nombre_aval.ID_Cuenta, buro_seg_nombre_aval.ID_Aval";

    

            $rs = $this->db->Execute($sql);
            if($rs->_numOfRows)
               while(! $rs->EOF)
               {

                   $ArrID_CuentasAval[$i]['ID_Cuenta']=$rs->fields['ID_Cuenta'];
                   $ArrID_CuentasAval[$i]['ID_Aval']=$rs->fields['ID_Aval'];
                    $i++;
                    $rs->MoveNext();
                }
                
            return $ArrID_CuentasAval;
    }
    
    
    private function VaciarTablasAval()
    {
        $sql ="TRUNCATE TABLE buro_seg_nombre_aval ";
        $this->db->Execute($sql);


        $sql ="TRUNCATE TABLE buro_seg_direccion_aval ";
        $this->db->Execute($sql);

        $sql ="TRUNCATE TABLE buro_seg_empleo_aval ";
        $this->db->Execute($sql);
        
        $sql ="TRUNCATE TABLE buro_seg_cuenta_aval ";
        $this->db->Execute($sql);

        $sql ="TRUNCATE TABLE buro_reporte_aval ";
        $this->db->Execute($sql);     
        
    }
    
  private function DatosReporteAval()
  {
       $sql = "INSERT INTO buro_reporte_aval
		(ID_Factura, ID_Aval, Fecha_Reporte, Regimen)
		(
			SELECT	solicitud.id_factura_solicitud AS ID_Factura,
                                originacion_aval.ID_Aval,
                                buro_reporte.Fecha_Reporte,
                                buro_reporte.Regimen

                        FROM buro_reporte
                        
                        INNER JOIN solicitud ON solicitud.id_factura_solicitud = buro_reporte.ID_Factura
                        INNER JOIN originacion_aval ON originacion_aval.ID_Solicitud = solicitud.ID_Solicitud                        
                        INNER JOIN fact_cliente ON solicitud.id_factura_solicitud = fact_cliente.id_factura
                        
                        WHERE originacion_aval.AvalActivoSolicitud = 'ACTIVO'
				AND solicitud.id_factura_solicitud > 0
				AND buro_reporte.Fecha_Reporte = '".  $this->FECHA_CORTE."'

		)";
        $this->db->Execute($sql);

       
  }
  
  private function DatosNombreAval()
  {
      $sql = "SELECT         buro_base.ID_Buro                          AS Cuenta,
                               originacion_aval.ID_Aval                   AS ID_Aval,
			       TRIM(originacion_aval.Apellido_Paterno)    AS Paterno,
			       TRIM(originacion_aval.Apellido_Materno)    AS Materno,       
			       TRIM(originacion_aval.Nombre_1)            AS Nombre1,
			       TRIM(originacion_aval.Nombre_2)            AS Nombre2,
			       TRIM(originacion_aval.Fecha_Nacimiento)    AS Fec_nac,      
			       CONCAT(originacion_aval.RFC,originacion_aval.Homoclave)                       AS rfch,
			      IF((originacion_aval.Sexo='FEMENINO' OR originacion_aval.Sexo='F') ,'F','M')  AS Sexo,
			      fact_cliente.ID_TipoCredito

		FROM  buro_base
		INNER JOIN buro_reporte_aval  ON buro_reporte_aval.ID_Factura   = buro_base.ID_Factura  
		INNER JOIN fact_cliente       ON fact_cliente.id_factura    	= buro_reporte_aval.ID_Factura
		INNER JOIN originacion_aval   ON originacion_aval.ID_Aval 	= buro_reporte_aval.ID_Aval
		ORDER BY fact_cliente.num_cliente, buro_base.ID_Buro  ";

        $rs=$this->db->Execute($sql);
        
        if($rs->_numOfRows)
           while(! $rs->EOF)
           {

                list($cuenta, $ID_Aval, $paterno, $materno, $nombre1, $nombre2, $fec_nac, $rfc, $sexo) = $rs->fields;

                $nombre2=(empty($nombre2))?(" "):($nombre2);
                $nombre1=(empty($nombre1))?(" "):($nombre1);


                //Si el apellido paterno no existe el materno se reportar� como paterno

                if(empty($paterno))
                {
                    $paterno=$materno;
                    $materno="NO PROPORCIONADO";
                }


                $materno=(empty($materno))?("NO PROPORCIONADO"):($materno);     

                $sexo=(empty($sexo))?(" "):($sexo);     

                $_nacimiento= fdia($fec_nac).fmes($fec_nac).fanio($fec_nac);



                $sql= "INSERT INTO buro_seg_nombre_aval  
                        (ID_Cuenta, ID_Aval, ap_paterno, ap_materno, pri_nombre, seg_nombre, nacimiento ,rfc, nacionalid, sexo, num_depend, murio)
                       VALUES
                       ('".$cuenta."',
                        '".$ID_Aval."',
                        '".$this->sincaracteres($paterno)."',
                        '".$this->sincaracteres($materno)."',
                        '".$this->sincaracteres($nombre1)."',
                        '".$this->sincaracteres($nombre2)."',
                        '".$_nacimiento."',
                        '".$rfc."',   
                        'MX',       
                        '".$sexo."',   
                        '0',        
                        'N') ";

                $this->db->Execute($sql);



               $rs->MoveNext();
           }
      
  }
  
  
  private function DatosDireccionAval()
  {
     $sql= " SELECT buro_base.ID_Buro AS Cuenta,
			originacion_aval.ID_Aval                AS ID_Aval, 
			 CONCAT(originacion_aval.Aval_Calle,' ',originacion_aval.Aval_Numero_Exterior)	AS Calle,
			 originacion_aval.Aval_Numero_Interior	AS CNum, 
			 ''        				AS CMz,       
			 ''					AS CLt,
			 originacion_aval.Aval_Colonia, 
			 originacion_aval.Aval_Ciudad, 
			 originacion_aval.Aval_Poblacion,   
			 estados.cve_estado,
			 originacion_aval.Aval_CP, 
			 originacion_aval.Telefono_Contacto2
		FROM  buro_base   

		INNER JOIN buro_reporte_aval	ON buro_reporte_aval.ID_Factura		= buro_base.ID_Factura 
		INNER JOIN fact_cliente		ON fact_cliente.id_factura    		= buro_reporte_aval.ID_Factura
		INNER JOIN originacion_aval	ON originacion_aval.ID_Aval 		= buro_reporte_aval.ID_Aval
		LEFT JOIN estados         	ON estados.Nombre			= originacion_aval.Aval_Estado 
		ORDER BY fact_cliente.id_factura, buro_base.ID_Buro      ";

        $rs=$this->db->Execute($sql);                    
        if($rs->_numOfRows)
           while(! $rs->EOF)
           {

                 list($cuenta, $ID_Aval, $d1, $d2, $d3, $d4, $colonia, $ciudad, $munici, $edo_sepomex, $cp, $telefono) = $rs->fields;


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
                  $estado = $edo_sepomex;


                $sql = "INSERT INTO buro_seg_direccion_aval
                        (ID_Cuenta, ID_Aval, Dir1, Dir2, Col_Pob, Delegacion, Ciudad, Estado, Cp, Residencia, Telefono, Ext, Fax, Tipo_Dom, Ind_Dom)
                        VALUES
                        ('".$cuenta."',
                        '".$ID_Aval."',
                        '".$this->sincaracteres($direc1)."', 
                        '".$this->sincaracteres($direc2)."', 
                        '".$this->sincaracteres($colonia)."', 
                        '".$this->sincaracteres($munici)."', 
                        '".$this->sincaracteres($ciudad)."', 
                        '".$this->sincaracteres($estado)."', 
                        '".$cp."', 
                        '',
                        '".$this->sincaracteres($telefono)."', 
                        '',
                        '',
                        '',
                        '') ";

                $this->db->Execute($sql);


               $rs->MoveNext();
           }  
  }
  
  
    private function DatosEmpleoAval()
    {
       $sql= " SELECT buro_base.ID_Buro AS Cuenta,
			originacion_aval.ID_Aval                AS ID_Aval, 
			 CONCAT(originacion_aval.Aval_Calle,' ',originacion_aval.Aval_Numero_Exterior)	AS Calle,
			 originacion_aval.Aval_Numero_Interior	AS CNum, 
			 ''        				AS CMz,       
			 ''					AS CLt,
			 originacion_aval.Aval_Colonia, 
			 originacion_aval.Aval_Ciudad, 
			 originacion_aval.Aval_Poblacion,   
			 estados.cve_estado,
			 originacion_aval.Aval_CP, 
			 originacion_aval.Telefono_Contacto2
		FROM  buro_base   

		INNER JOIN buro_reporte_aval	ON buro_reporte_aval.ID_Factura		= buro_base.ID_Factura 
		INNER JOIN fact_cliente		ON fact_cliente.id_factura    		= buro_reporte_aval.ID_Factura
		INNER JOIN originacion_aval	ON originacion_aval.ID_Aval 		= buro_reporte_aval.ID_Aval
		LEFT JOIN estados         	ON estados.Nombre			= originacion_aval.Aval_Estado 
		ORDER BY fact_cliente.id_factura, buro_base.ID_Buro      ";

        $rs=$this->db->Execute($sql);                    
        if($rs->_numOfRows)
           while(! $rs->EOF)
           {

                 list($cuenta, $ID_Aval, $d1, $d2, $d3, $d4, $colonia, $ciudad, $munici, $edo_sepomex, $cp, $telefono) = $rs->fields;


                $direc =  trim($d1." ".$d2." ".$d3." ".$d4);

                $direc1 = substr($direc,0,  40);
                $direc2 = substr($direc,40, 79);
                
               
                $razon_social   =(empty($razon_social))?("TRABAJADOR INDEPENDIENTE"):($razon_social);
                $direc1        =(empty($direc1  ))?(" "):($direc1      );
                $direc2         =(empty($direc2  ))?(" "):($direc2      );
                $colonia        =(empty($colonia ))?(" "):($colonia     );
                $ciudad         =(empty($ciudad  ))?(" "):($ciudad      );
                $munici         =(empty($munici  ))?(" "):($munici      );
                $cp             =(empty($cp      ))?(" "):($cp          );
                $telefono       =(empty($telefono))?(" "):($telefono);


                if(empty($edo_sepomex))
                  $estado = " ";
                else
                  $estado = $edo_sepomex;

  
                $sql = "INSERT INTO buro_seg_empleo_aval
                                (ID_Cuenta, ID_Aval, razon_social_emp, dir1, dir2, col_pob, delegacion_municip, ciudad, estado, cp, telefono, ext, fax, ocupacion, contratacion, clave_moneda, sueldo, periodo_pago, num_empleado, ultimo_empleo, verificacion_empleo, origen)
                                VALUES
                                ('".$cuenta."',
                                '".$ID_Aval."',
                                '".$this->sincaracteres($razon_social)."',
                                '".$this->sincaracteres($direc1)."', 
                                '".$this->sincaracteres($direc2)."', 
                                '".$this->sincaracteres($colonia)."', 
                                '".$this->sincaracteres($munici)."', 
                                '".$this->sincaracteres($ciudad)."', 
                                '".$this->sincaracteres($estado)."', 
                                '".$cp."', 
                                '".$this->sincaracteres($telefono)."', 
                                '',
                                '',
                                '',
                                '',
                                '',
                                '0',
                                '',
                                '',
                                '',
                                '',
                                'MX') ";
                $this->db->Execute($sql);
                
               
               $rs->MoveNext();
           }
    }
    
    
    private function DatosCuentaAval()
    {
        $sql = "INSERT INTO buro_seg_cuenta_aval ( ID_Cuenta, ID_Aval, Etiqueta, Member_cve, Nombre_usr, Num_cuenta, Tipo_resp, Tipo_cta, T_contrato, Cve_moneda, Importe_av, Num_pagos, Frec_pagos, Monto, Apertura, Ult_pago, Ult_compra, F_cierre, F_reporte, Garantia, Cred_max, Saldo_act, Cred_lim, Saldo_venc, Pagos_venc, Forma_pago, Hist_pagos, Cve_observ, Tot_pagos, Tpag_mop2, Tpag_mop3, Tpag_mop4, Tpag_mop5, Cve_ant_ot, Nombre_ant, N_cta_ant, primer_inc, saldo_ins_prin, monto_ultimo_pago, plazo_meses, monto_originacion, Fin)
		(
			SELECT  buro_seg_cuenta.ID_Cuenta,
                                buro_reporte_aval.ID_Aval,
				buro_seg_cuenta.Etiqueta,
				buro_seg_cuenta.Member_cve,
				buro_seg_cuenta.Nombre_usr,
				buro_seg_cuenta.Num_cuenta,
				'C' AS Tipo_resp,
				buro_seg_cuenta.Tipo_cta,
				buro_seg_cuenta.T_contrato,
				buro_seg_cuenta.Cve_moneda,
				buro_seg_cuenta.Importe_av,
				buro_seg_cuenta.Num_pagos,
				buro_seg_cuenta.Frec_pagos,
				buro_seg_cuenta.Monto,
				buro_seg_cuenta.Apertura,
				buro_seg_cuenta.Ult_pago,
				buro_seg_cuenta.Ult_compra,
				buro_seg_cuenta.F_cierre,
				buro_seg_cuenta.F_reporte,
				buro_seg_cuenta.Garantia,
				buro_seg_cuenta.Cred_max,
				buro_seg_cuenta.Saldo_act,
				buro_seg_cuenta.Cred_lim,
				buro_seg_cuenta.Saldo_venc,
				buro_seg_cuenta.Pagos_venc,
				buro_seg_cuenta.Forma_pago,
				buro_seg_cuenta.Hist_pagos,
				buro_seg_cuenta.Cve_observ,
				buro_seg_cuenta.Tot_pagos,
				buro_seg_cuenta.Tpag_mop2,
				buro_seg_cuenta.Tpag_mop3,
				buro_seg_cuenta.Tpag_mop4,
				buro_seg_cuenta.Tpag_mop5,
				buro_seg_cuenta.Cve_ant_ot,
				buro_seg_cuenta.Nombre_ant,
				buro_seg_cuenta.N_cta_ant,
				buro_seg_cuenta.primer_inc,
				buro_seg_cuenta.saldo_ins_prin,
                                buro_seg_cuenta.monto_ultimo_pago,
                                buro_seg_cuenta.plazo_meses,
                                buro_seg_cuenta.monto_originacion,
				buro_seg_cuenta.Fin

			FROM 	buro_seg_cuenta

			INNER JOIN buro_base         ON buro_base.ID_Buro 		= buro_seg_cuenta.ID_Cuenta
			INNER JOIN buro_reporte_aval ON buro_reporte_aval.ID_Factura	= buro_base.ID_Factura
			INNER JOIN fact_cliente      ON fact_cliente.id_factura		= buro_reporte_aval.ID_Factura 

		)";

                $this->db->Execute($sql); 
    }
    
    private function ObtenerSegNombreAval()
    {
          $sql ="SELECT ID_Cuenta,
                        ID_Aval,
                        AP_PATERNO, 
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
                        FROM   buro_seg_nombre_aval
                        ORDER BY ID_Cuenta ";

            $rs =  $this->db->Execute($sql);

            if($rs->_numOfRows){
                while(! $rs->EOF)
                {
                   $ArrNombreAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['ID_Cuenta']          =   $rs->fields['ID_Cuenta'];
                   $ArrNombreAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Ap_Paterno']         =   $rs->fields['AP_PATERNO'];
                   $ArrNombreAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Ap_Materno']         =   $rs->fields['AP_MATERNO'];
                   $ArrNombreAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Ap_Adicional']       =   $rs->fields['AP_ADICION'];
                   $ArrNombreAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Primer_Nombre']      =   $rs->fields['PRI_NOMBRE'];
                   $ArrNombreAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Segundo_Nombre']     =   $rs->fields['SEG_NOMBRE'];
                   $ArrNombreAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Fecha_Nacimiento']   =   $rs->fields['NACIMIENTO'];         
                   $ArrNombreAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['RFC']                =   $rs->fields['RFC'];
                   $ArrNombreAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Prefijo']            =   $rs->fields['PREFIJO'];
                   $ArrNombreAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Sufijo']             =   $rs->fields['SUFIJO'];
                   $ArrNombreAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Nacionalidad']       =   $rs->fields['NACIONALID'];
                   $ArrNombreAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Residencia']         =   $rs->fields['RESIDENCIA'];
                   $ArrNombreAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Lic_Manejo']         =   $rs->fields['LIC_MANEJO'];
                   $ArrNombreAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Edo_Civil']          =   $rs->fields['EDO_CIVIL'];
                   $ArrNombreAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Sexo']               =   $rs->fields['SEXO'];
                   $ArrNombreAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Cedula']             =   $rs->fields['CEDULA'];
                   $ArrNombreAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Registro_Electoral'] =   $rs->fields['IFE'];   
                   $ArrNombreAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Imp_Pais']           =   $rs->fields['IMP_PAIS'];
                   $ArrNombreAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['CURP']               =   $rs->fields['CURP'];
                   $ArrNombreAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Clave_Pais']         =   $rs->fields['CVE_PAIS'];
                   $ArrNombreAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Num_Dependientes']   =   $rs->fields['NUM_DEPEND'];
                   $ArrNombreAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Edades_Dependientes']=   $rs->fields['EDADES_DEP'];
                   $ArrNombreAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Defuncion']          =   $rs->fields['DEFUNCION'];
                   $ArrNombreAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Indicador_Defuncion']=   $rs->fields['MURIO']; 

                   $rs->MoveNext();
                }
            }

            return $ArrNombreAval;
         }


        private function ObtenerSegDireccionAval()
        {
           $sql ="SELECT ID_Cuenta,
                        ID_Aval,
                        DIR1, 
                        DIR2, 
                        COL_POB, 
                        DELEGACION, 
                        CIUDAD, 
                        ESTADO, 
                        CP, 
                        RESIDENCIA, 
                        TELEFONO, 
                        EXT, 
                        FAX, 
                        TIPO_DOM 
        FROM    buro_seg_direccion_aval
        ORDER BY ID_Cuenta ";

         $rs = $this->db->Execute($sql);

            if($rs->_numOfRows){
                while(! $rs->EOF)
                {            

                    $ArrDireccionAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['ID_Cuenta']          =   $rs->fields['ID_Cuenta']; 
                    $ArrDireccionAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Dir1']               =   $rs->fields['DIR1'];            
                    $ArrDireccionAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Dir2']               =   $rs->fields['DIR2'];            
                    $ArrDireccionAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Col_Poblacion']      =   $rs->fields['COL_POB'];
                    $ArrDireccionAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Delegacion_Municip'] =   $rs->fields['DELEGACION'];     
                    $ArrDireccionAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Ciudad']             =   $rs->fields['CIUDAD'];
                    $ArrDireccionAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Estado']             =   $rs->fields['ESTADO'];
                    $ArrDireccionAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['CP']                 =   $rs->fields['CP'];            
                    $ArrDireccionAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Fecha_Residencia']   =   $rs->fields['RESIDENCIA'];           
                    $ArrDireccionAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Telefono']           =   $rs->fields['TELEFONO'];   
                    $ArrDireccionAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Extension']          =   $rs->fields['EXT'];
                    $ArrDireccionAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Fax']                =   $rs->fields['FAX'];
                    $ArrDireccionAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Tipo_Domicilio']     =   $rs->fields['TIPO_DOM'];
                    $ArrDireccionAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Indicador_Dom']      =   $rs->fields['IND_DOM'];
                    $ArrDireccionAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Origen_Dom']         =   'MX';

                     $rs->MoveNext();

                }
            }
            return $ArrDireccionAval;


        }

        private function ObtenerSegEmpleoAval()
        {
           $sql ="SELECT ID_Cuenta,
                         ID_Aval,
                         RAZON_SOCIAL_EMP,
                         DIR1, 
                         DIR2, 
                         COL_POB, 
                         DELEGACION_MUNICIP, 
                         CIUDAD, 
                         ESTADO, 
                         CP, 
                         TELEFONO, 
                         EXT, 
                         FAX, 
                         OCUPACION,
                         CONTRATACION,
                         CLAVE_MONEDA,
                         SUELDO,
                         PERIODO_PAGO,
                         NUM_EMPLEADO,
                         ULTIMO_EMPLEO,
                         VERIFICACION_EMPLEO,
                         ORIGEN
                FROM    buro_seg_empleo_aval
                ORDER BY ID_Cuenta ";

         $rs =  $this->db->Execute($sql);

            if($rs->_numOfRows){
                while(! $rs->EOF)
                {            
                    $ArrEmpleoAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['ID_Cuenta']             =   $rs->fields ['ID_Cuenta']; 
                    $ArrEmpleoAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Razon_Social_EM']       =   $rs->fields ['RAZON_SOCIAL_EMP'];
                    $ArrEmpleoAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Dir1']                  =   $rs->fields ['DIR1'];  
                    $ArrEmpleoAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Dir2']                  =   $rs->fields ['DIR2'];            
                    $ArrEmpleoAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Col_Poblacion']         =   $rs->fields ['COL_POB'];
                    $ArrEmpleoAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Delegacion_Municip']    =   $rs->fields ['DELEGACION_MUNICIP']; 
                    $ArrEmpleoAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Ciudad']                =   $rs->fields ['CIUDAD'];
                    $ArrEmpleoAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Estado']                =   $rs->fields ['ESTADO'];
                    $ArrEmpleoAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['CP']                    =   $rs->fields ['CP'];            
                    $ArrEmpleoAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Telefono']              =   $rs->fields ['TELEFONO'];
                    $ArrEmpleoAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Extension']             =   $rs->fields ['EXT'];
                    $ArrEmpleoAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Fax']                   =   $rs->fields ['FAX'];
                    $ArrEmpleoAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Ocupacion']             =   $rs->fields ['OCUPACION'];
                    $ArrEmpleoAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Fecha_Contratacion']    =   $rs->fields ['CONTRATACION'];
                    $ArrEmpleoAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Clave_Moneda']          =   $rs->fields ['CLAVE_MONEDA'];
                    $ArrEmpleoAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Sueldo']                =   $rs->fields ['SUELDO'];
                    $ArrEmpleoAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Periodo_Pago']          =   $rs->fields ['PERIODO_PAGO'];
                    $ArrEmpleoAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Num_Empleado']          =   $rs->fields ['NUM_EMPLEADO'];
                    $ArrEmpleoAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Fecha_Ultimo_Empleo']   =   $rs->fields ['ULTIMO_EMPLEO'];
                    $ArrEmpleoAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Verificacion_Empleo']   =   $rs->fields ['VERIFICACION_EMPLEO'];
                    $ArrEmpleoAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Origen']                =   $rs->fields ['ORIGEN'];;

                     $rs->MoveNext();
                }

            } 

            return $ArrEmpleoAval;
        }


        private function ObtenerSegCuentaAval()
        {

            $sql = " SELECT  ID_Cuenta,
                         ID_Aval,
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
                         MONTO_ULTIMO_PAGO,
                         PLAZO_MESES, 
                         MONTO_ORIGINACION,
                         FIN

                FROM buro_seg_cuenta_aval INNER JOIN buro_base ON buro_base.ID_Buro = buro_seg_cuenta_aval.ID_Cuenta
                ORDER BY ID_cuenta";
                

               $rs = $this->db->Execute($sql);


            if($rs->_numOfRows){
                while(! $rs->EOF)
                {
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Nombre_Seg']        =   $rs->fields['ETIQUETA'];
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Clave_Usuario']     =   $this->Clave_usuario;
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Nombre_Usuario']    =   $rs->fields['NOMBRE_USR'];
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Num_Cuenta_Actual'] =   $rs->fields['ID_Cuenta'];
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Tipo_Respons']      =   $rs->fields['TIPO_RESP'];
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Tipo_Cuenta']       =   $rs->fields['TIPO_CTA'];
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Tipo_Contrato']     =   $rs->fields['T_CONTRATO'];
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Moneda']            =   $rs->fields['CVE_MONEDA'];
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Importe_Avaluo']    =   $rs->fields['IMPORTE_AV'];
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Num_Pagos']         =   $rs->fields['NUM_PAGOS'];
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Frec_Pagos']        =   $rs->fields['FREC_PAGOS'];
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Monto_Pagar']       =   $rs->fields['MONTO'];
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Fecha_Apertura']    =   $rs->fields['APERTURA'];
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Ultimo_Pago']       =   $rs->fields['ULT_PAGO'];
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Ultima_Compra']     =   $rs->fields['ULT_COMPRA'];
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Fecha_Cierre']      =   $rs->fields['F_CIERRE'];
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Fecha_Reporte']     =   $rs->fields['F_REPORTE'];            
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Garantia']          =   $rs->fields['GARANTIA'];
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Credito_Max']       =   $rs->fields['CRED_MAX'];
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Saldo_Actual']      =   $rs->fields['SALDO_ACT'];
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Limite_Credito']    =   $rs->fields['CRED_LIM'];
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Saldo_Vencido']     =   $rs->fields['SALDO_VENC'];
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Num_Pagos_Vencidos']=   $rs->fields['PAGOS_VENC'];
                    
                    $this->TotSaldosActuales =   $this->TotSaldosActuales + $rs->fields['SALDO_ACT'];
                    $this->TotSaldosVencidos =   $this->TotSaldosVencidos + $rs->fields['SALDO_VENC'];
                    
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Forma_Pago']        =   $rs->fields['FORMA_PAGO'];
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Clave_Observacion'] =   $rs->fields['CVE_OBSERV'];
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Tot_Pagos']         =   $rs->fields['TOT_PAGOS'];                   
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['TPAG_MOP3']         =   $rs->fields['TPAG_MOP3'];
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['TPAG_MOP4']         =   $rs->fields['TPAG_MOP4'];
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['TPAG_MOP5']         =   $rs->fields['TPAG_MOP5'];
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['CVE_ANT_OT']        =   $rs->fields['CVE_ANT_OT'];
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Nom_Usu_Ant']       =   $rs->fields['NOMBRE_ANT'];
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Num_Cuenta_Ant']    =   $rs->fields['N_CTA_ANT'];
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Primer_Incumpliento']=  $rs->fields['PRIMER_INC'];
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Saldo_Insoluto']    =   $rs->fields['SALDO_INS_PRIN'];
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Monto_Ultimo_Pago'] =   $rs->fields['MONTO_ULTIMO_PAGO'];
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Plazo_Meses']       =   $rs->fields['PLAZO_MESES'];
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Monto_Originacion'] =   $rs->fields['MONTO_ORIGINACION'];
                    $ArrCuentaAval[$rs->fields['ID_Cuenta']] [$rs->fields['ID_Aval']] ['Fin']               =   'FIN' ;

                    $rs->MoveNext();

                }
            }

            return $ArrCuentaAval;
         }

     

}



?>