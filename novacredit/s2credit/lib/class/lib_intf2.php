<?php

require($class_path."DatosIntf.php");

class TINTF
{
    
    public $file;
    public $file_reg;
    public $error_msg = "";
    public $n_error   = 0;
    public $TotSegPN  = 0;
    public $TotSegPA  = 0; 
    public $TotSegPE  = 0; 
    public $TotSegTL  = 0; 

   
  
     public function __construct($db)
     {
            $this->db = $db;              
     }
    
    
    public function GenerarReporte($id_empresas)
    {  
        $Obj = new DatosIntf($this->db);
        $Obj->Reporte($id_empresas);
        $error         = $Obj->error; 
        $NombreTabla   = $Obj->NombreTabla;
           
        if($error > 0)
        {
            $msg=$this->MensajeError($NombreTabla);           
            $this->error_msg = $msg;
            $this->n_error++;
            
        }
        else
        {
            $ArrEncabezado    = $Obj->ArrEncabezado;
            $ArrNombre        = $Obj->ArrNombre;
            $ArrDireccion     = $Obj->ArrDireccion;
            $ArrEmpleo        = $Obj->ArrEmpleo;
            $ArrCuenta        = $Obj->ArrCuenta;
            $ArrNombreAval    = $Obj->ArrNombreAval;
            $ArrDireccionAval = $Obj->ArrDireccionAval;
            $ArrEmpleoAval    = $Obj->ArrEmpleoAval;
            $ArrCuentaAval    = $Obj->ArrCuentaAval;
            $ArrCierre        = $Obj->ArrCierre;
            $ArrID_Cuentas    = $Obj->ID_Cuentas;
            $ArrID_CuentasAval= $Obj->ID_CuentasAval;
            
            $this->GenerarSegEncabezado($ArrEncabezado);
          
            for($i=1;$i<=count($ArrID_Cuentas);$i++)
            {
                fwrite($this->file_reg, $i."\n"); 
                $this->GenerarSegNombre($ArrNombre,$ArrID_Cuentas[$i]);
                $this->GenerarSegDireccion($ArrDireccion,$ArrID_Cuentas[$i]);
                $this->GenerarSegEmpleo($ArrEmpleo,$ArrID_Cuentas[$i]);
                $this->GenerarSegCuenta($ArrCuenta,$ArrID_Cuentas[$i]);                
               
            }
            
               
//=============================================================DATOS AVAL==================================================            
            if(!empty($ArrID_CuentasAval))
               for($j=1;$j<=count($ArrID_CuentasAval);$j++)
                {
                   
                    //fwrite($this->file_reg, $i."\n"); 
                    $this->GenerarSegNombreAval   ($ArrNombreAval,   $ArrID_CuentasAval[$j]['ID_Cuenta'],$ArrID_CuentasAval[$j]['ID_Aval']);
                    $this->GenerarSegDireccionAval($ArrDireccionAval,$ArrID_CuentasAval[$j]['ID_Cuenta'],$ArrID_CuentasAval[$j]['ID_Aval']);
                    $this->GenerarSegEmpleoAval   ($ArrEmpleoAval,   $ArrID_CuentasAval[$j]['ID_Cuenta'],$ArrID_CuentasAval[$j]['ID_Aval']);
                    $this->GenerarSegCuentaAval   ($ArrCuentaAval,   $ArrID_CuentasAval[$j]['ID_Cuenta'],$ArrID_CuentasAval[$j]['ID_Aval']);
                    $i++;
                }

            $this->GenerarSegCierre($ArrCierre);
        }

    } 

/*====================================================SEGMENTO ENCABEZADO================================================
========================================================================================================================= */
      
   
    private function GenerarSegmentos($ArrNombre, $ArrDireccion, $ArrEmpleo, $ArrCuenta, $ID_Cuenta)
    {
          $this->GenerarSegNombre($ArrNombre,$ID_Cuenta);
          $this->GenerarSegDireccion($ArrDireccion,$ID_Cuenta);
          $this->GenerarSegEmpleo($ArrEmpleo,$ID_Cuenta);
          $this->GenerarSegCuenta($ArrCuenta,$ID_Cuenta);
        
    }
    private function GenerarSegEncabezado($ArrEncabezado)
    {    
        $this->AdStr($this->Etiquetas('IN'));
        $this->AdStr($ArrEncabezado['Version']);    
        $this->AdStr($ArrEncabezado['Clave_Otorgante']);
        $this->AdStr($this->ponespacios($ArrEncabezado['Nombre_Usuario'],16));
        $this->AdStr($this->ponespacios($ArrEncabezado['Reservado'],2));
        $this->AdStr($this->ponespacios($ArrEncabezado['Fecha_Reporte'],8));
        $this->AdStr($this->ponceros($ArrEncabezado['Uso_Futuro'],10));
        $this->AdStr($this->ponespacios($ArrEncabezado['Adicional'],98));
    }
    

/*====================================================SEGMENTO NOMBRE====================================================
========================================================================================================================= */
    private function GenerarSegNombre($ArrNombre,$ID_Cuenta)
    {       
                    
            $this->RegVar($this->Etiquetas('PN'),$ArrNombre[$ID_Cuenta]['Ap_Paterno']);
            $this->RegVar($this->Etiquetas('00'),$ArrNombre[$ID_Cuenta]['Ap_Materno']);
            
            if(strlen(trim($ArrNombre[$ID_Cuenta]['Ap_Adicional'])) != 0)   
                $this->RegVar($this->Etiquetas('01'), $ArrNombre[$ID_Cuenta] ['Ap_Adicional']);
            
            $this->RegVar($this->Etiquetas('02'), $ArrNombre[$ID_Cuenta]['Primer_Nombre']);
            $this->RegVar($this->Etiquetas('03'), $ArrNombre[$ID_Cuenta]['Segundo_Nombre']);
            $this->RegVar($this->Etiquetas('04'), $ArrNombre[$ID_Cuenta]['Fecha_Nacimiento']);
            $this->RegVar($this->Etiquetas('05'), $ArrNombre[$ID_Cuenta]['RFC']);
            
            if(strlen(trim($ArrNombre[$ID_Cuenta]['Prefijo'])) != 0)   
                $this->RegVar($this->Etiquetas('06'), $ArrNombre[$ID_Cuenta]['Prefijo']);
            
            if(strlen(trim($ArrNombre[$ID_Cuenta]['Sufijo'])) != 0)   
                $this->RegVar($this->Etiquetas('07'), $ArrNombre[$ID_Cuenta]['Sufijo']);
            
            $this->RegVar($this->Etiquetas('08'), $ArrNombre[$ID_Cuenta]['Nacionalidad']);
            
            if(strlen(trim($ArrNombre[$ID_Cuenta]['Residencia'])) != 0)   
                $this->RegVar($this->Etiquetas('09'), $ArrNombre[$ID_Cuenta]['Residencia']);
            
            if(strlen(trim($ArrNombre[$ID_Cuenta]['Lic_Manejo'])) != 0) 
                $this->RegVar($this->Etiquetas('10'), $ArrNombre[$ID_Cuenta]['Lic_Manejo']);
            
            if(strlen(trim($ArrNombre[$ID_Cuenta]['Edo_Civil'])) != 0)
                $this->RegVar($this->Etiquetas('11'), $ArrNombre[$ID_Cuenta]['Edo_Civil']);
            
            if(strlen(trim($ArrNombre[$ID_Cuenta]['Sexo'])) != 0)
                $this->RegVar($this->Etiquetas('12'), $ArrNombre[$ID_Cuenta]['Sexo']);
            
            if(strlen(trim($ArrNombre[$ID_Cuenta]['Cedula'])) != 0)
                $this->RegVar($this->Etiquetas('13'), $ArrNombre[$ID_Cuenta]['Cedula']);
            
            if(strlen(trim($ArrNombre[$ID_Cuenta]['Registro_Electoral'])) != 0)
                $this->RegVar($this->Etiquetas('14'), $ArrNombre[$ID_Cuenta]['Registro_Electoral']);
           
            if(strlen(trim($ArrNombre[$ID_Cuenta]['CURP'])) != 0)
                $this->RegVar($this->Etiquetas('15'), $ArrNombre[$ID_Cuenta]['CURP']);
           
            if(strlen(trim($ArrNombre[$ID_Cuenta]['Clave_Pais'])) != 0) 
                $this->RegVar($this->Etiquetas('16'), $ArrNombre[$ID_Cuenta]['Clave_Pais']);
           
            if(intval($ArrNombre[$ID_Cuenta]['Num_Dependientes']) != 0) 
                $this->RegVar($this->Etiquetas('17'), $ArrNombre[$ID_Cuenta]['Num_Dependientes']);
           
            if(strlen(trim($ArrNombre[$ID_Cuenta]['Edades_Dependientes'])) != 0) 
                $this->RegVar($this->Etiquetas('18'), $ArrNombre[$ID_Cuenta]['Edades_Dependientes']);
            
            if(strlen(trim($ArrNombre[$ID_Cuenta]['Defuncion'])) != 0)
                $this->RegVar($this->Etiquetas('20'), $ArrNombre[$ID_Cuenta]['Defuncion']);
            
            if($ArrNombre[$ID_Cuenta]['Indicador_Defuncion'] == 'Y')
                $this->RegVar($this->Etiquetas('21'), $ArrNombre[$ID_Cuenta]['Indicador_Defuncion']);            
             
    }
    
    
/*==================================================SEGMENTO DIRECCIÓN===================================================
========================================================================================================================= */
    private function GenerarSegDireccion($ArrDireccion,$ID_Cuenta)
    {
            $this->RegVar($this->Etiquetas('PA'), $ArrDireccion[$ID_Cuenta]['Dir1']);
            
            if(strlen(trim($ArrDireccion[$ID_Cuenta]['Dir2']))!=0)
                $this->RegVar($this->Etiquetas('00'), $ArrDireccion[$ID_Cuenta]['Dir2']);
            
            $this->RegVar($this->Etiquetas('01'), $ArrDireccion[$ID_Cuenta]['Col_Poblacion']);
            $this->RegVar($this->Etiquetas('02'), $ArrDireccion[$ID_Cuenta]['Delegacion_Municip']);
            
            if(strlen(trim($ArrDireccion[$ID_Cuenta]['Ciudad']))!=0)
                $this->RegVar($this->Etiquetas('03'), $ArrDireccion[$ID_Cuenta]['Ciudad']);
            else
                $this->RegVar($this->Etiquetas('03'), $ArrDireccion[$ID_Cuenta]['Delegacion_Municip']);
         
            $this->RegVar($this->Etiquetas('04'), $this->Estados($ArrDireccion[$ID_Cuenta]['Estado']));
            $this->RegVar($this->Etiquetas('05'), $ArrDireccion[$ID_Cuenta]['CP']);
            
            if(strlen(trim($ArrDireccion[$ID_Cuenta]['Fecha_Residencia']))!=0)
                $this->RegVar($this->Etiquetas('06'), $ArrDireccion[$ID_Cuenta]['Fecha_Residencia']);
             
            if(strlen(trim($ArrDireccion[$ID_Cuenta]['Telefono']))!=0)
                $this->RegVar($this->Etiquetas('07'), $ArrDireccion[$ID_Cuenta]['Telefono']);
             
            if(strlen(trim($ArrDireccion[$ID_Cuenta]['Extension']))!=0)
                $this->RegVar($this->Etiquetas('08'), $ArrDireccion[$ID_Cuenta]['Extension']);
            
            if(strlen(trim($ArrDireccion[$ID_Cuenta]['Fax']))!=0)
                $this->RegVar($this->Etiquetas('09'), $ArrDireccion[$ID_Cuenta]['Fax']);
             
            if(strlen(trim($ArrDireccion[$ID_Cuenta]['Tipo_Domicilio']))!=0)
                $this->RegVar($this->Etiquetas('10'), $ArrDireccion[$ID_Cuenta]['Tipo_Domicilio']);
              
            if(strlen(trim($ArrDireccion[$ID_Cuenta]['Indicador_Dom']))!=0)
                $this->RegVar($this->Etiquetas('11'), $ArrDireccion[$ID_Cuenta]['Indicador_Dom']);
            
            $this->RegVar($this->Etiquetas('12'), $ArrDireccion[$ID_Cuenta]['Origen_Dom']);           
            
        
        
    }
 
    
/*====================================================SEGMENTO EMPLEO====================================================
========================================================================================================================= */
  
    private function GenerarSegEmpleo($ArrEmpleo,$ID_Cuenta)
    {
          
            $this->RegVar($this->Etiquetas('PE'), $ArrEmpleo[$ID_Cuenta]['Razon_Social_EM']); 
            $this->RegVar($this->Etiquetas('00'), $ArrEmpleo[$ID_Cuenta]['Dir1']);
            
            if(strlen(trim($ArrEmpleo[$ID_Cuenta]['Dir2']))!=0)
                $this->RegVar($this->Etiquetas('01'), $ArrEmpleo[$ID_Cuenta]['Dir2']);
            
            $this->RegVar($this->Etiquetas('02'), $ArrEmpleo[$ID_Cuenta]['Col_Poblacion']);
            $this->RegVar($this->Etiquetas('03'), $ArrEmpleo[$ID_Cuenta]['Delegacion_Municip']);
            
             if(strlen(trim($ArrEmpleo[$ID_Cuenta]['Ciudad']))!=0)
                $this->RegVar($this->Etiquetas('04'), $ArrEmpleo[$ID_Cuenta]['Ciudad']);
            else
                $this->RegVar($this->Etiquetas('04'), $ArrEmpleo[$ID_Cuenta]['Delegacion_Municip']);
            
            $this->RegVar($this->Etiquetas('05'), $this->Estados($ArrEmpleo[$ID_Cuenta]['Estado']));
            $this->RegVar($this->Etiquetas('06'), $ArrEmpleo[$ID_Cuenta]['CP']);
            
              if(strlen(trim($ArrEmpleo[$ID_Cuenta]['Telefono']))!=0)
                $this->RegVar($this->Etiquetas('07'), $ArrEmpleo[$ID_Cuenta]['Telefono']);
             
            if(strlen(trim($ArrEmpleo[$ID_Cuenta]['Extension']))!=0)
                $this->RegVar($this->Etiquetas('08'), $ArrEmpleo[$ID_Cuenta]['Extension']);
            
            if(strlen(trim($ArrEmpleo[$ID_Cuenta]['Fax']))!=0)
                $this->RegVar($this->Etiquetas('09'), $ArrEmpleo[$ID_Cuenta]['Fax']);
            
             if(strlen(trim($ArrEmpleo[$ID_Cuenta]['Ocupacion']))!=0)
                $this->RegVar($this->Etiquetas('10'), $ArrEmpleo[$ID_Cuenta]['Ocupacion']);
             
            if(strlen(trim($ArrEmpleo[$ID_Cuenta]['Fecha_Contratacion']))!=0)
                $this->RegVar($this->Etiquetas('11'), $ArrEmpleo[$ID_Cuenta]['Fecha_Contratacion']);
              
            if(strlen(trim($_ArrEmpleo[$ID_Cuenta]['Clave_Moneda']))!=0)
                $this->RegVar($this->Etiquetas('12'), $ArrEmpleo[$ID_Cuenta]['Clave_Moneda']);
               
            if($ArrEmpleo[$ID_Cuenta]['Sueldo']>0)
                $this->RegVar($this->Etiquetas('13'), $ArrEmpleo[$ID_Cuenta]['Sueldo']);
            
            if(strlen(trim($ArrEmpleo[$ID_Cuenta]['Periodo_Pago']))!=0)
                $this->RegVar($this->Etiquetas('14'), $ArrEmpleo[$ID_Cuenta]['Periodo_Pago']);
            
            if(strlen(trim($ArrEmpleo[$ID_Cuenta]['Num_Empleado']))!=0)
                $this->RegVar($$this->Etiquetas('15'), $ArrEmpleo[$ID_Cuenta]['Num_Empleado']);
            
            if(strlen(trim($ArrEmpleo[$ID_Cuenta]['Fecha_Ultimo_Empleo']))!=0)
                $this->RegVar($this->Etiquetas('16'), $ArrEmpleo[$ID_Cuenta]['Fecha_Ultimo_Empleo']);
             
            if(strlen(trim($ArrEmpleo[$ID_Cuenta]['Verificacion_Empleo']))!=0)
                $this->RegVar($this->Etiquetas('17'), $ArrEmpleo[$ID_Cuenta]['Verificacion_Empleo']);
            
            $this->RegVar($this->Etiquetas('18'), $ArrEmpleo[$ID_Cuenta]['Origen']);            
            
            
    }
  
/*====================================================SEGMENTO CUENTA====================================================
========================================================================================================================= */
      
    private function GenerarSegCuenta($ArrCuenta,$ID_Cuenta)
    {
            $this->RegVar($this->Etiquetas('TL'), $ArrCuenta[$ID_Cuenta]['Nombre_Seg']);
            $this->RegVar($this->Etiquetas('01'), $ArrCuenta[$ID_Cuenta]['Clave_Usuario']);
            $this->RegVar($this->Etiquetas('02'), $ArrCuenta[$ID_Cuenta]['Nombre_Usuario']);
            $this->RegVar($this->Etiquetas('04'), $ArrCuenta[$ID_Cuenta]['Num_Cuenta_Actual']);
            $this->RegVar($this->Etiquetas('05'), $ArrCuenta[$ID_Cuenta]['Tipo_Respons']);
            $this->RegVar($this->Etiquetas('06'), $ArrCuenta[$ID_Cuenta]['Tipo_Cuenta']);
            $this->RegVar($this->Etiquetas('07'), $ArrCuenta[$ID_Cuenta]['Tipo_Contrato']);
            $this->RegVar($this->Etiquetas('08'), $ArrCuenta[$ID_Cuenta]['Moneda']);
            
            if($ArrCuenta[$ID_Cuenta]['Importe_Avaluo']>0)
                $this->RegVar($this->Etiquetas('09'), $ArrCuenta[$ID_Cuenta]['Importe_Avaluo']);
            
            $this->RegVar($this->Etiquetas('10'), $ArrCuenta[$ID_Cuenta]['Num_Pagos']);
            $this->RegVar($this->Etiquetas('11'), $ArrCuenta[$ID_Cuenta]['Frec_Pagos']);
            $this->RegVar($this->Etiquetas('12'), $ArrCuenta[$ID_Cuenta]['Monto_Pagar']);
            $this->RegVar($this->Etiquetas('13'), $ArrCuenta[$ID_Cuenta]['Fecha_Apertura']);
            $this->RegVar($this->Etiquetas('14'), $ArrCuenta[$ID_Cuenta]['Ultimo_Pago']);
            $this->RegVar($this->Etiquetas('15'), $ArrCuenta[$ID_Cuenta]['Ultima_Compra']);
            
            if(strlen(trim($ArrCuenta[$ID_Cuenta]['Fecha_Cierre']))!=0)
                $this->RegVar($this->Etiquetas('16'), $ArrCuenta[$ID_Cuenta]['Fecha_Cierre']);
             
            $this->RegVar($this->Etiquetas('17'), $ArrCuenta[$ID_Cuenta]['Fecha_Reporte']);
            
            if(strlen(trim($ArrCuenta[$ID_Cuenta]['Garantia']))!=0)
                $this->RegVar($this->Etiquetas('20'), $ArrCuenta[$ID_Cuenta]['Garantia']);
            
            $this->RegVar($this->Etiquetas('21'), $ArrCuenta[$ID_Cuenta]['Credito_Max']);
            $this->RegVar($this->Etiquetas('22'), $ArrCuenta[$ID_Cuenta]['Saldo_Actual']);
            
            //if($ArrCuenta[$ID_Cuenta]['Tipo_Cuenta']!='I')
                $this->RegVar($this->Etiquetas('23'), $ArrCuenta[$ID_Cuenta]['Limite_Credito']);
            
            $this->RegVar($this->Etiquetas('24'), $ArrCuenta[$ID_Cuenta]['Saldo_Vencido']);
            
            if(strlen(trim($ArrCuenta[$ID_Cuenta]['Num_Pagos_Vencidos']))>0)
                $this->RegVar($this->Etiquetas('25'), $ArrCuenta[$ID_Cuenta]['Num_Pagos_Vencidos']);
            
            $this->RegVar($this->Etiquetas('26'), $ArrCuenta[$ID_Cuenta]['Forma_Pago']);
            
            if(strlen(trim($ArrCuenta[$ID_Cuenta]['Clave_Observacion']))!=0  )
                $this->RegVar($this->Etiquetas('30'), $ArrCuenta[$ID_Cuenta]['Clave_Observacion']);
            
            if($ArrCuenta[$ID_Cuenta]['Clave_Usu_Ant']!=0)
                $this->RegVar($this->Etiquetas('39'), $ArrCuenta[$ID_Cuenta]['Clave_Usu_Ant']);
            
            if($ArrCuenta[$ID_Cuenta]['Nom_Usu_Ant']!=0)
                $this->RegVar($this->Etiquetas('40'), $ArrCuenta[$ID_Cuenta]['Nom_Usu_Ant']);
            
            if($ArrCuenta[$ID_Cuenta]['Num_Cuenta_Ant']!=0)
                $this->RegVar($this->Etiquetas('41'), $ArrCuenta[$ID_Cuenta]['Num_Cuenta_Ant']);
            
            $this->RegVar($this->Etiquetas('43'), $ArrCuenta[$ID_Cuenta]['Primer_Incumpliento']);
            $this->RegVar($this->Etiquetas('44'), $ArrCuenta[$ID_Cuenta]['Saldo_Insoluto']);
            $this->RegVar($this->Etiquetas('45'), $ArrCuenta[$ID_Cuenta]['Monto_Ultimo_Pago']);
            $this->RegVar($this->Etiquetas('50'), $ArrCuenta[$ID_Cuenta]['Plazo_Meses']);
            $this->RegVar($this->Etiquetas('51'), $ArrCuenta[$ID_Cuenta]['Monto_Originacion']);
            $this->RegVar($this->Etiquetas('99'), $ArrCuenta[$ID_Cuenta]['Fin']);
        
        
    }
    
   
/*==================================================SEGMENTO CIERRE======================================================
========================================================================================================================= */
  
    private function GenerarSegCierre($ArrCierre)
    {
        
        $this->AdStr($this->Etiquetas('TR'));
        $this->AdStr($this->ponceros($ArrCierre['Total_Saldos_Actuales'],14));
        $this->AdStr($this->ponceros($ArrCierre['Total_Saldos_Vencidos'],14));
        $this->AdStr($ArrCierre['Total_Seg_Encabezado']);        
        $this->AdStr($this->ponceros($ArrCierre['Total_Seg_Nombre'],9));
        $this->AdStr($this->ponceros($ArrCierre['Total_Seg_Direccion'],9));
        $this->AdStr($this->ponceros($ArrCierre['Total_Seg_Empleo'],9));
        $this->AdStr($this->ponceros($ArrCierre['Total_Seg_Cuenta'],9));
        $this->AdStr($this->ponceros($ArrCierre['Contador_Bloques'],6));
        $this->AdStr($this->ponespacios($ArrCierre['Nom_Devolucion'],16));
        $this->AdStr($this->ponespacios($ArrCierre['Dir_Devolucion'],160));        
        
    }
    
/*====================================================SEGMENTO NOMBRE AVAL===============================================
========================================================================================================================= */
    private function GenerarSegNombreAval($ArrNombreAval,$ID_Cuenta, $ID_Aval)
    {       
            $this->RegVar($this->Etiquetas('PN'),$ArrNombreAval[$ID_Cuenta][$ID_Aval]['Ap_Paterno']);
            $this->RegVar($this->Etiquetas('00'),$ArrNombreAval[$ID_Cuenta][$ID_Aval]['Ap_Materno']);
            
            if(strlen(trim($ArrNombreAval[$ID_Cuenta][$ID_Aval]['Ap_Adicional'])) != 0)   
                $this->RegVar($this->Etiquetas('01'), $ArrNombreAval[$ID_Cuenta][$ID_Aval]['Ap_Adicional']);
            
            $this->RegVar($this->Etiquetas('02'), $ArrNombreAval[$ID_Cuenta][$ID_Aval]['Primer_Nombre']);
            $this->RegVar($this->Etiquetas('03'), $ArrNombreAval[$ID_Cuenta][$ID_Aval]['Segundo_Nombre']);
            $this->RegVar($this->Etiquetas('04'), $ArrNombreAval[$ID_Cuenta][$ID_Aval]['Fecha_Nacimiento']);
            $this->RegVar($this->Etiquetas('05'), $ArrNombreAval[$ID_Cuenta][$ID_Aval]['RFC']);
            
            if(strlen(trim($ArrNombreAval[$ID_Cuenta][$ID_Aval]['Prefijo'])) != 0)   
                $this->RegVar($this->Etiquetas('06'), $ArrNombreAval[$ID_Cuenta][$ID_Aval]['Prefijo']);
            
            if(strlen(trim($ArrNombreAval[$ID_Cuenta][$ID_Aval]['Sufijo'])) != 0)   
                $this->RegVar($this->Etiquetas('07'), $ArrNombreAval[$ID_Cuenta][$ID_Aval]['Sufijo']);
            
            $this->RegVar($this->Etiquetas('08'), $ArrNombreAval[$ID_Cuenta][$ID_Aval]['Nacionalidad']);
            
            if(strlen(trim($ArrNombreAval[$ID_Cuenta][$ID_Aval]['Residencia'])) != 0)   
                $this->RegVar($this->Etiquetas('09'), $ArrNombreAval[$ID_Cuenta][$ID_Aval]['Residencia']);
            
            if(strlen(trim($ArrNombreAval[$ID_Cuenta][$ID_Aval]['Lic_Manejo'])) != 0) 
                $this->RegVar($this->Etiquetas('10'), $ArrNombreAval[$ID_Cuenta][$ID_Aval]['Lic_Manejo']);
            
            if(strlen(trim($ArrNombreAval[$ID_Cuenta][$ID_Aval]['Edo_Civil'])) != 0)
                $this->RegVar($this->Etiquetas('11'), $ArrNombreAval[$ID_Cuenta][$ID_Aval]['Edo_Civil']);
            
            if(strlen(trim($ArrNombreAval[$ID_Cuenta][$ID_Aval]['Sexo'])) != 0)
                $this->RegVar($this->Etiquetas('12'), $ArrNombreAval[$ID_Cuenta][$ID_Aval]['Sexo']);
            
            if(strlen(trim($ArrNombreAval[$ID_Cuenta][$ID_Aval]['Cedula'])) != 0)
                $this->RegVar($this->Etiquetas('13'), $ArrNombreAval[$ID_Cuenta][$ID_Aval]['Cedula']);
            
            if(strlen(trim($ArrNombreAval[$ID_Cuenta][$ID_Aval]['Registro_Electoral'])) != 0)
                $this->RegVar($this->Etiquetas('14'), $ArrNombreAval[$ID_Cuenta][$ID_Aval]['Registro_Electoral']);
           
            if(strlen(trim($ArrNombreAval[$ID_Cuenta][$ID_Aval]['CURP'])) != 0)
                $this->RegVar($this->Etiquetas('15'), $ArrNombreAval[$ID_Cuenta][$ID_Aval]['CURP']);
           
            if(strlen(trim($ArrNombreAval[$ID_Cuenta][$ID_Aval]['Clave_Pais'])) != 0) 
                $this->RegVar($this->Etiquetas('16'), $ArrNombreAval[$ID_Cuenta][$ID_Aval]['Clave_Pais']);
           
            if(intval($ArrNombreAval[$ID_Cuenta][$ID_Aval]['Num_Dependientes']) != 0) 
                $this->RegVar($this->Etiquetas('17'), $ArrNombreAval[$ID_Cuenta][$ID_Aval]['Num_Dependientes']);
           
            if(strlen(trim($ArrNombreAval[$ID_Cuenta][$ID_Aval]['Edades_Dependientes'])) != 0) 
                $this->RegVar($this->Etiquetas('18'), $ArrNombreAval[$ID_Cuenta][$ID_Aval]['Edades_Dependientes']);
            
            if(strlen(trim($ArrNombreAval[$ID_Cuenta][$ID_Aval]['Defuncion'])) != 0)
                $this->RegVar($this->Etiquetas('20'), $ArrNombreAval[$ID_Cuenta][$ID_Aval]['Defuncion']);
            
            if($ArrNombreAval[$ID_Cuenta][$ID_Aval]['Indicador_Defuncion'] == 'Y')
                $this->RegVar($this->Etiquetas('21'), $ArrNombreAval[$ID_Cuenta][$ID_Aval]['Indicador_Defuncion']);            
             
    }
    
    
/*==================================================SEGMENTO DIRECCIÓN===================================================
========================================================================================================================= */
    private function GenerarSegDireccionAval($ArrDireccionAval,$ID_Cuenta, $ID_Aval)
    {
            $this->RegVar($this->Etiquetas('PA'), $ArrDireccionAval[$ID_Cuenta][$ID_Aval]['Dir1']);
            
            if(strlen(trim($ArrDireccionAval[$ID_Cuenta][$ID_Aval]['Dir2']))!=0)
                $this->RegVar($this->Etiquetas('00'), $ArrDireccionAval[$ID_Cuenta][$ID_Aval]['Dir2']);
            
            $this->RegVar($this->Etiquetas('01'), $ArrDireccionAval[$ID_Cuenta][$ID_Aval]['Col_Poblacion']);
            $this->RegVar($this->Etiquetas('02'), $ArrDireccionAval[$ID_Cuenta][$ID_Aval]['Delegacion_Municip']);
            
            if(strlen(trim($ArrDireccionAval[$ID_Cuenta][$ID_Aval]['Ciudad']))!=0)
                $this->RegVar($this->Etiquetas('03'), $ArrDireccionAval[$ID_Cuenta][$ID_Aval]['Ciudad']);
            else
                $this->RegVar($this->Etiquetas('03'), $ArrDireccionAval[$ID_Cuenta][$ID_Aval]['Delegacion_Municip']);
         
            $this->RegVar($this->Etiquetas('04'), $this->Estados($ArrDireccionAval[$ID_Cuenta][$ID_Aval]['Estado']));
            $this->RegVar($this->Etiquetas('05'), $ArrDireccionAval[$ID_Cuenta][$ID_Aval]['CP']);
            
            if(strlen(trim($ArrDireccionAval[$ID_Cuenta][$ID_Aval]['Fecha_Residencia']))!=0)
                $this->RegVar($this->Etiquetas('06'), $ArrDireccionAval[$ID_Cuenta][$ID_Aval]['Fecha_Residencia']);
             
            if(strlen(trim($ArrDireccionAval[$ID_Cuenta][$ID_Aval]['Telefono']))!=0)
                $this->RegVar($this->Etiquetas('07'), $ArrDireccionAval[$ID_Cuenta][$ID_Aval]['Telefono']);
             
            if(strlen(trim($ArrDireccionAval[$ID_Cuenta][$ID_Aval]['Extension']))!=0)
                $this->RegVar($this->Etiquetas('08'), $ArrDireccionAval[$ID_Cuenta][$ID_Aval]['Extension']);
            
            if(strlen(trim($ArrDireccionAval[$ID_Cuenta][$ID_Aval]['Fax']))!=0)
                $this->RegVar($this->Etiquetas('09'), $ArrDireccionAval[$ID_Cuenta][$ID_Aval]['Fax']);
             
            if(strlen(trim($ArrDireccionAval[$ID_Cuenta][$ID_Aval]['Tipo_Domicilio']))!=0)
                $this->RegVar($this->Etiquetas('10'), $ArrDireccionAval[$ID_Cuenta][$ID_Aval]['Tipo_Domicilio']);
              
            if(strlen(trim($ArrDireccionAval[$ID_Cuenta][$ID_Aval]['Indicador_Dom']))!=0)
                $this->RegVar($this->Etiquetas('11'), $ArrDireccionAval[$ID_Cuenta][$ID_Aval]['Indicador_Dom']);
            
            $this->RegVar($this->Etiquetas('12'), $ArrDireccionAval[$ID_Cuenta][$ID_Aval]['Origen_Dom']);           
            
        
        
    }
 
    
/*====================================================SEGMENTO EMPLEO====================================================
========================================================================================================================= */
  
    private function GenerarSegEmpleoAval($ArrEmpleoAval,$ID_Cuenta, $ID_Aval)
    {
          
           $this->RegVar($this->Etiquetas('PE'), $ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['Razon_Social_EM']); 
            $this->RegVar($this->Etiquetas('00'), $ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['Dir1']);
            
            if(strlen(trim($ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['Dir2']))!=0)
                $this->RegVar($this->Etiquetas('01'), $ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['Dir2']);
            
            $this->RegVar($this->Etiquetas('02'), $ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['Col_Poblacion']);
            $this->RegVar($this->Etiquetas('03'), $ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['Delegacion_Municip']);
            
             if(strlen(trim($ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['Ciudad']))!=0)
                $this->RegVar($this->Etiquetas('04'), $ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['Ciudad']);
            else
                $this->RegVar($this->Etiquetas('04'), $ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['Delegacion_Municip']);
            
            $this->RegVar($this->Etiquetas('05'), $this->Estados($ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['Estado']));
            $this->RegVar($this->Etiquetas('06'), $ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['CP']);
            
              if(strlen(trim($ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['Telefono']))!=0)
                $this->RegVar($this->Etiquetas('07'), $ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['Telefono']);
             
            if(strlen(trim($ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['Extension']))!=0)
                $this->RegVar($this->Etiquetas('08'), $ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['Extension']);
            
            if(strlen(trim($ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['Fax']))!=0)
                $this->RegVar($this->Etiquetas('09'), $ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['Fax']);
            
             if(strlen(trim($ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['Ocupacion']))!=0)
                $this->RegVar($this->Etiquetas('10'), $ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['Ocupacion']);
             
            if(strlen(trim($ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['Fecha_Contratacion']))!=0)
                $this->RegVar($this->Etiquetas('11'), $ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['Fecha_Contratacion']);
              
            if(strlen(trim($ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['Clave_Moneda']))!=0)
                $this->RegVar($this->Etiquetas('12'), $ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['Clave_Moneda']);
               
            if($ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['Sueldo']>0)
                $this->RegVar($this->Etiquetas('13'), $ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['Sueldo']);
            
            if(strlen(trim($ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['Periodo_Pago']))!=0)
                $this->RegVar($this->Etiquetas('14'), $ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['Periodo_Pago']);
            
            if(strlen(trim($ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['Num_Empleado']))!=0)
                $this->RegVar($$this->Etiquetas('15'), $ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['Num_Empleado']);
            
            if(strlen(trim($ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['Fecha_Ultimo_Empleo']))!=0)
                $this->RegVar($this->Etiquetas('16'), $ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['Fecha_Ultimo_Empleo']);
             
            if(strlen(trim($ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['Verificacion_Empleo']))!=0)
                $this->RegVar($this->Etiquetas('17'), $ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['Verificacion_Empleo']);
            
            $this->RegVar($this->Etiquetas('18'), $ArrEmpleoAval[$ID_Cuenta][$ID_Aval]['Origen']);  
          
            
            
    }
  
/*====================================================SEGMENTO CUENTA====================================================
========================================================================================================================= */
      
    private function GenerarSegCuentaAval($ArrCuentaAval,$ID_Cuenta, $ID_Aval)
    {
            $this->RegVar($this->Etiquetas('TL'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Nombre_Seg']);
            $this->RegVar($this->Etiquetas('01'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Clave_Usuario']);
            $this->RegVar($this->Etiquetas('02'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Nombre_Usuario']);
            $this->RegVar($this->Etiquetas('04'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Num_Cuenta_Actual']);
            $this->RegVar($this->Etiquetas('05'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Tipo_Respons']);
            $this->RegVar($this->Etiquetas('06'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Tipo_Cuenta']);
            $this->RegVar($this->Etiquetas('07'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Tipo_Contrato']);
            $this->RegVar($this->Etiquetas('08'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Moneda']);
            
            if($ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Importe_Avaluo']>0)
                $this->RegVar($this->Etiquetas('09'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Importe_Avaluo']);
            
            $this->RegVar($this->Etiquetas('10'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Num_Pagos']);
            $this->RegVar($this->Etiquetas('11'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Frec_Pagos']);
            $this->RegVar($this->Etiquetas('12'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Monto_Pagar']);
            $this->RegVar($this->Etiquetas('13'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Fecha_Apertura']);
            $this->RegVar($this->Etiquetas('14'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Ultimo_Pago']);
            $this->RegVar($this->Etiquetas('15'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Ultima_Compra']);
            
            if(strlen(trim($ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Fecha_Cierre']))!=0)
                $this->RegVar($this->Etiquetas('16'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Fecha_Cierre']);
             
            $this->RegVar($this->Etiquetas('17'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Fecha_Reporte']);
            
            if(strlen(trim($ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Garantia']))!=0)
                $this->RegVar($this->Etiquetas('20'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Garantia']);
            
            $this->RegVar($this->Etiquetas('21'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Credito_Max']);
            $this->RegVar($this->Etiquetas('22'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Saldo_Actual']);
            
            //if($ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Tipo_Cuenta']!='I')
                $this->RegVar($this->Etiquetas('23'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Limite_Credito']);
            
            $this->RegVar($this->Etiquetas('24'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Saldo_Vencido']);
            
            if(strlen(trim($ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Num_Pagos_Vencidos']))>0)
                $this->RegVar($this->Etiquetas('25'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Num_Pagos_Vencidos']);
            
            $this->RegVar($this->Etiquetas('26'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Forma_Pago']);
            
            if(strlen(trim($ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Clave_Observacion']))!=0  )
                $this->RegVar($this->Etiquetas('30'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Clave_Observacion']);
            
            if($ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Clave_Usu_Ant']!=0)
                $this->RegVar($this->Etiquetas('39'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Clave_Usu_Ant']);
            
            if($ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Nom_Usu_Ant']!=0)
                $this->RegVar($this->Etiquetas('40'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Nom_Usu_Ant']);
            
            if($ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Num_Cuenta_Ant']!=0)
                $this->RegVar($this->Etiquetas('41'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Num_Cuenta_Ant']);
            
            $this->RegVar($this->Etiquetas('43'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Primer_Incumpliento']);
            $this->RegVar($this->Etiquetas('44'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Saldo_Insoluto']);
            $this->RegVar($this->Etiquetas('45'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Monto_Ultimo_Pago']);
            $this->RegVar($this->Etiquetas('50'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Plazo_Meses']);
            $this->RegVar($this->Etiquetas('51'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Monto_Originacion']);
            $this->RegVar($this->Etiquetas('99'), $ArrCuentaAval[$ID_Cuenta][$ID_Aval]['Fin']);

    }
    



/*==============================================FUNCIONES PARA FORMATO===================================================
========================================================================================================================= */
  
    private function AdStr($s)
    {
       fwrite($this->file, $s);
    }


    private function RegVar($Etiqueta, $Cadena)
    {
         if(strlen($Cadena)!=0) 
         {  
                $sustutucion = array();      

                $sustutucion['Á']  = 'A';
                $sustutucion['É']  = 'E';
                $sustutucion['Í']  = 'I';
                $sustutucion['Ó']  = 'O';
                $sustutucion['Ú']  = 'U';

                $sustutucion['Ü']  = 'U';
                $sustutucion['ë']  = 'E';
                $sustutucion['Ñ']  = 'N';
                $sustutucion['á']  = 'A';
                $sustutucion['é']  = 'E';
                $sustutucion['í']  = 'I';
                $sustutucion['ó']  = 'O';
                $sustutucion['ú']  = 'U';

                $sustutucion['ü']  = 'U';

                $sustutucion['ñ']  = 'N';
             
                $_tmp = $Cadena;
                
                foreach($sustutucion AS  $badcahr => $goodchar)
                {
                    $_tmp = str_replace($badcahr,$goodchar,$_tmp);
                }
                $_cadena = trim($_tmp);

             if(!(($Etiqueta == "03" || $Etiqueta == "04") && strpos($_cadena, "HEROICA PUEBLA")))
                {
                    $_tmp = $_cadena;

                    $patron='/[^\w\s]/';
                    $_cadena=preg_replace($patron,'', $_tmp); 

                }
                               
                $_cadena = strtoupper($_cadena);
        }
        else
            $_cadena="";      

         $this->AdStr($Etiqueta . $this->SizeIN($_cadena) . $_cadena);
    }


    private function SizeIN($S)
    {
      $I=0;
      $J=0;

       $I = strlen(trim($S));
       $J = strval($I);

       if($I<=9) $J= '0'.$J;

       return($J);
    }

    
    private function ponespacios($S, $N)
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
    
    
    private function ponceros($S,$N)
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
    
    
    
    private function Etiquetas($Etiqueta)
    {

        $ArrEtiquetas = array('IN'   =>  'INTF',
                              'PN'   =>  'PN',
                              'PA'   =>  'PA',
                              'PE'   =>  'PE',
                              'TL'   =>  'TL',
                              'TR'   =>  'TRLR',
                              '00'   =>  '00',
                              '01'   =>  '01',
                              '02'   =>  '02',
                              '03'   =>  '03',
                              '04'   =>  '04',
                              '05'   =>  '05',
                              '06'   =>  '06',
                              '07'   =>  '07',
                              '08'   =>  '08',
                              '09'   =>  '09',
                              '10'   =>  '10',
                              '11'   =>  '11',
                              '12'   =>  '12',
                              '13'   =>  '13',
                              '14'   =>  '14',
                              '15'   =>  '15',
                              '16'   =>  '16',
                              '17'   =>  '17',
                              '18'   =>  '18',
                              '19'   =>  '19',
                              '20'   =>  '20',
                              '21'   =>  '21',
                              '22'   =>  '22',
                              '23'   =>  '23',
                              '24'   =>  '24',
                              '25'   =>  '25',
                              '26'   =>  '26',
                              '30'   =>  '30',
                              '39'   =>  '39',
                              '40'   =>  '40',
                              '41'   =>  '41',
                              '43'   =>  '43',
                              '44'   =>  '44',
                              '45'   =>  '45',
                              '50'   =>  '50',
                              '51'   =>  '51',
                              '99'   =>  '99');

            return $ArrEtiquetas[$Etiqueta];

    }       


    private function Estados($Estado){

         $edo_sepomex_a_buro = array();

                $edo_sepomex_a_buro['AGS']      = 'AGS';
                $edo_sepomex_a_buro['BC']       = 'BCN';
                $edo_sepomex_a_buro['BCS']      = 'BCS';
                $edo_sepomex_a_buro['CAM']      = 'CAM';
                $edo_sepomex_a_buro['CHIS']     = 'CHS';
                $edo_sepomex_a_buro['CHIH']     = 'CHI';
                $edo_sepomex_a_buro['COAH']     = 'COA';
                $edo_sepomex_a_buro['COL']      = 'COL';
                $edo_sepomex_a_buro['DF']       = 'DF';
                $edo_sepomex_a_buro['DGO']      = 'DGO';
                $edo_sepomex_a_buro['GTO']      = 'GTO';
                $edo_sepomex_a_buro['GRO']      = 'GRO';
                $edo_sepomex_a_buro['HGO']      = 'HGO';
                $edo_sepomex_a_buro['JAL']      = 'JAL';
                $edo_sepomex_a_buro['MEX']      = 'EM';
                $edo_sepomex_a_buro['MICH']     = 'MICH';
                $edo_sepomex_a_buro['MOR']      = 'MOR';
                $edo_sepomex_a_buro['NAY']      = 'NAY';
                $edo_sepomex_a_buro['NL']       = 'NL';
                $edo_sepomex_a_buro['OAX']      = 'OAX';
                $edo_sepomex_a_buro['PUE']      = 'PUE';
                $edo_sepomex_a_buro['QRO']      = 'QRO';
                $edo_sepomex_a_buro['QROO']     = 'QR';
                $edo_sepomex_a_buro['SLP']      = 'SLP';
                $edo_sepomex_a_buro['SIN']      = 'SIN';
                $edo_sepomex_a_buro['SON']      = 'SON';
                $edo_sepomex_a_buro['TAB']      = 'TAB';
                $edo_sepomex_a_buro['TAM']      = 'TAM';
                $edo_sepomex_a_buro['TLAX']     = 'TLA';
                $edo_sepomex_a_buro['VER']      = 'VER';
                $edo_sepomex_a_buro['YUC']      = 'YUC';
                $edo_sepomex_a_buro['ZAC']      = 'ZAC';


                return $edo_sepomex_a_buro[$Estado];

        } 
        
/*====================================================MENSAJES DE ERROR==================================================
========================================================================================================================= */
    private function MensajeError($tabla)
    {
        
        $_tablas = array('buro_encabezado'    =>'Encabezado',
                         'buro_seg_nombre'    =>'Nombres',
                         'buro_seg_direccion' =>'Direccion',
                         'buro_seg_empleo'    =>'Empleo',
                         'buro_seg_cuenta'    =>'Cuenta');
        
        $msg =  "La tabla del Segmento ".$_tablas[$tabla]." esta vacia. No se podra generar la informacion";        
        return $msg;
    }
    
} 



?>