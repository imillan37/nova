<?
$exit = 0;
$noheader =1;
include($DOCUMENT_ROOT."/rutas.php");
require($class_path."lib_nuevo_credito.php");

/*
Fecha: 04-Noviembre-2010
Autor: Tonathiu Cárdenas
Dependencia: cotizador_base.php
*/

//Inicio conexión
$db = ADONewConnection(SERVIDOR);
$db->Connect(IP,USER,PASSWORD,NUCLEO);
//Fin Conexión

/****************FUNCIONES*************************/

 //CMB PROODUCTO FINANCIERO
 function select_producto_financiero($option_select,$id_empresa)
    {

               global $db;
  
		$sql_prod="SELECT  
		              ID_Producto AS ID
		           FROM  cat_convenio_empresas_prod_financiero
		          WHERE ID_empresa='".$id_empresa."' ";
		$rs_prod=$db->Execute($sql_prod);	
		
		
		while(! $rs_prod->EOF)
		{ 
		
		$Id_prod.=$rs_prod->fields["ID"];
		$rs_prod->MoveNext();
		if(! $rs_prod->EOF)
		$Id_prod.=',';
		}
	
		
		
		$sql_prod_financiero = "SELECT  
					     ID_Producto AS IDPROD,
					    Nombre AS NOMPROD
				FROM  cat_productosfinancieros
				WHERE Status <> 'Inactivo' 
				     AND ID_Producto IN($Id_prod)
				ORDER BY Nombre ";
		$rs_prod_financiero = $db->Execute($sql_prod_financiero);

	
		
		
		if($rs_prod_financiero->fields[0]>0)
		{
			$combo ="<SELECT  NAME='producto_financiero' ONCHANGE='muestra_plazo(this.value,0); muestra_detalle_credito(\"limpiar\"); ' > \n";
			$combo.= "<OPTION VALUE='' SELECTED  >SELECCIONAR OPCIÓN</OPTION> \n";
			$combo.= "<OPTION VALUE='' DISABLED>-----------------------------------------------------------</OPTION>";
			while(! $rs_prod_financiero->EOF )
			{

			 $sel = ($option_select == $rs_prod_financiero->fields["IDPROD"])?("SELECTED"):("");
			 $combo.="<OPTION VALUE='".$rs_prod_financiero->fields["IDPROD"]."' ".$sel."  >".$rs_prod_financiero->fields["IDPROD"]."-".$rs_prod_financiero->fields["NOMPROD"]."</OPTION> \n";
			 $rs_prod_financiero->MoveNext();
			}//Fin while
			$combo.="</SELECT>\n";
		}
		else
		{
		        $combo.="<B><FONT COLOR='#FF0000' SIZE='1'>NO EXISTEN PRODUCTOS FINANCIEROS PARA RENOVACIÓN</FONT></B>";
		}

	
     return $combo;
}

//NMB PROD FINANCIERO
function get_nmb_prod_financiero($id_producto)
{
	global $db;
		$sql_prod_financiero = "SELECT  
					     ID_Producto AS IDPROD,
					    Nombre AS NOMPROD
				FROM  cat_productosfinancieros
				WHERE ID_Producto = '".$id_producto."' ";
				
		$rs_prod_financiero = $db->Execute($sql_prod_financiero);

		return $rs_prod_financiero->fields["NOMPROD"];

}

//COMBO PLAZO FUNCTION
function select_plazo($vlr_prod_financiero)
{
 global $db;	
	$sql_prod = "SELECT Vencimiento,
	                    Plazo_Minimo AS PLZMIN,
	                    Plazo_Maximo AS PLZMAX,
	                    Capital_Min  ,
	                    Capital_Max
	               FROM  cat_productosfinancieros
	               WHERE ID_Producto = '".$vlr_prod_financiero."' ";


	$rs_prod=$db->Execute($sql_prod);
	$Plazo_min=$rs_prod->fields["PLZMIN"];
	$Plazo_max=$rs_prod->fields["PLZMAX"];

        $click=(empty($solicitud))?("muestra_detalle_credito(\"calcular\");"):("detalle_autorizar();");

	$cmb_plazo ="<SELECT ID='B' NAME='plazo' ONCHANGE='".$click."' > \n";
	$cmb_plazo.="<OPTION VALUE=''>SELECCIONAR OPCIÓN</OPTION> \n";
	$cmb_plazo.="<OPTION VALUE='' DISABLED>------------------------------------</OPTION>";
	$selected  ="";
	for($x=$Plazo_min;$x<=$Plazo_max;$x++)
	{
		$selected =($plazo == $x)?'selected':'';

		$cmb_plazo.="<OPTION VALUE='".$x."' ".$selected." >".$x."</OPTION> \n";
	}
	$cmb_plazo.="</SELECT>\n";
   return  $cmb_plazo;
}

function get_empresa_soli($id_empresa)
{
 global $db;
 
 	  $sql_cons = "SELECT 
			      
			      cat_convenio_empresas.ID_empresa AS ID,
			      cat_convenio_empresas.Empresa AS EMP
			     
			 FROM  cat_convenio_empresas
			     WHERE  cat_convenio_empresas.ID_empresa ='".$id_empresa."'  ";
	$rs_cons=$db->Execute($sql_cons);
	$empresa_soli=$rs_cons->fields["EMP"];		     
			     
 return $empresa_soli;	
}
//COMBO PLAZO AJAX
if(isset($vlr_prod_financiero) && !empty($vlr_prod_financiero) )
{
	$sql_prod = "SELECT Vencimiento,
	                    Plazo_Minimo AS PLZMIN,
	                    Plazo_Maximo AS PLZMAX,
	                    Capital_Min  ,
	                    Capital_Max
	               FROM  cat_productosfinancieros
	               WHERE ID_Producto = '".$vlr_prod_financiero."' ";


	$rs_prod=$db->Execute($sql_prod);
	$Plazo_min=$rs_prod->fields["PLZMIN"];
	$Plazo_max=$rs_prod->fields["PLZMAX"];

        $click=(empty($solicitud))?("muestra_detalle_credito(\"calcular\");"):("detalle_autorizar();");

	$cmb_plazo="<SELECT ID='B' NAME='plazo' ONCHANGE='".$click."' > \n";
	$cmb_plazo.="<OPTION VALUE=''>SELECCIONAR OPCIÓN</OPTION> \n";
	$cmb_plazo.= "<OPTION VALUE='' DISABLED>------------------------------------</OPTION>";
	$selected = "";
	for($x=$Plazo_min;$x<=$Plazo_max;$x++)
	{
		$selected =($plazo == $x)?'selected':'';

		$cmb_plazo.="<OPTION VALUE='".$x."' ".$selected." >".$x."</OPTION> \n";
	}
	$cmb_plazo.="</SELECT>\n";
echo $cmb_plazo;

}


//CRÉDITOS ASOCIADOS AL CLIENTE
//PRIMERA
function elimina_coma($campo)
{
  $pos_last=strrpos($campo,',');
  $campo=substr($campo,0,$pos_last);
  
return $campo;
}

//SEGUNDA
function get_renta_actual($frecuencia,$Renta)
{
     switch ($frecuencia )
        {
          case 'Anios' : 		$Renta=$Renta/12;    break;
          case 'Semestres' :    $Renta=$Renta/6;     break;
          case 'Trimestres' :   $Renta=$Renta/3;     break;
          case 'Bimestres' :    $Renta=$Renta/2;     break;
          case 'Meses' : 		$Renta=$Renta;       break;
          case 'Quincenas' :    $Renta=$Renta*2;     break;
		  case 'Catorcenas' :   $Renta=$Renta*1.8;   break;
          case 'Semanas' :      $Renta=$Renta*4;     break;        
          case 'Dias' :         $Renta=$Renta*30;    break;     
        };  
        
        
 return $Renta;   
        
}
//TERCERA
function get_info_facturas($num_cte)
{
   
	  global $db; 
	   $sql_fact = "SELECT 
			      id_factura  AS FACT,
			      Renta       AS RENTA,
			      Vencimiento AS VNC                      
			    FROM fact_cliente 
			    WHERE num_cliente ='".$num_cte."' ";
	   $rs_fact=$db->Execute($sql_fact);

	   $fecha_hoy  =  date("Y-m-d");

	   $Tot_renta=0;
	   $Tot_saldo_vencido=0;

		while(! $rs_fact->EOF )
		{
		       $obj = new TCUENTA($rs_fact->fields["FACT"], $fecha_hoy,'','',true);


			if($obj->adeudo_total > '0.01')
			 {
			  $Id_factura.=$rs_fact->fields["FACT"].",";

			  $Renta_tmp=get_renta_actual($rs_fact->fields["VNC"],$rs_fact->fields["RENTA"]);		 
			  $Renta.=$Renta_tmp.",";
			  $Tot_renta+=$Renta_tmp;


			  $Saldo_liquidar_hoy.=$obj->SaldoParaLiquidar.",";
			  $Saldo_vencido.=$obj->SaldoGeneralVencido.",";
			  $Tot_saldo_vencido+=$obj->SaldoGeneralVencido;

			  //VERIFICAR SI SE SALDA AL CIERRE
			  $sql_cierre = "SELECT COUNT(*) AS CUANTAS
					  FROM caja_credito_saldada 
					  WHERE id_factura='".$Id_factura."' ";
			  $rs_cierre=$db->Execute($sql_cierre);
			  $Saldada_al_cierre.=($rs_cierre->fields["CUANTAS"] > '0' )?("1,"):("0,");
			 }

		 $rs_fact->MoveNext();
		}

		//QUITANDO LA ÚLTIMA COMA 
		$Id_factura=elimina_coma($Id_factura);
		$Saldo_liquidar_hoy=elimina_coma($Saldo_liquidar_hoy);
		$Saldada_al_cierre=elimina_coma($Saldada_al_cierre);
		$Saldo_vencido=elimina_coma($Saldo_vencido);
		$Renta=elimina_coma($Renta);




	  $informe_fact=array("FACTURAS"=>$Id_factura,"SALDO_LIQUIDAR_HOY"=>$Saldo_liquidar_hoy,"SALDADO_AL_CIERRE"=>$Saldada_al_cierre,"SALDO_VENCIDO"=>$Saldo_vencido,"RENTA"=>$Renta,"TOT_RENTA"=>$Tot_renta,"TOT_SALDO_VENCIDO"=>"$".number_format($Tot_saldo_vencido,2)); 

return $informe_fact;
}


//VENCIMIENTO
function get_vencimiento ($vlr_prod_financiero)
{
	 global $db;

	    $sql_prod = "SELECT Vencimiento AS VNC
			    FROM  cat_productosfinancieros
			    WHERE ID_Producto = '".$vlr_prod_financiero."' ";
	    $rs_prod=$db->Execute($sql_prod);

	    $vencimiento=$rs_prod->fields["VNC"];

	 switch ($vencimiento )
		{
		  case 'Anio' : 		$vencimiento = 'Anual';			break;
		  case 'Semestres' :    $vencimiento = 'Semestral';		break;
		  case 'Trimestres' :   $vencimiento = 'Trimestral';	break;
		  case 'Bimestres' :    $vencimiento = 'Bimestral';		break;
		  case 'Meses' : 		$vencimiento = 'Mensual';		break;
		  case 'Quincenas' :    $vencimiento = 'Quincenal';		break;
		  case 'Catorcenas' :   $vencimiento = 'Catorcenal';	break;
		  case 'Semanas' :      $vencimiento = 'Semanal';		break;        
		  case 'Dias' :         $vencimiento = 'Diario';		break;     
		};
		
 return $vencimiento;
}

//NOMBRE DEL PRODUCTO FINANCIERO

function get_nombre_producto($vlr_prod_financiero)
{
	 global $db;

	    $sql_prod = "SELECT
						Nombre AS NMB
					FROM  cat_productosfinancieros
					WHERE ID_Producto = '".$vlr_prod_financiero."' ";
	    $rs_prod=$db->Execute($sql_prod);

	    $NMB_PROD_FIN=$rs_prod->fields["NMB"];

	return $NMB_PROD_FIN;
}
	    

//TIPO DE INGRESOS
function get_tipo_ingresos($ID_empresa,$Tipo_credito)
{
	 global $db;

   if($Tipo_credito == '3')
   {
		$sql_cons = "SELECT Porcentaje_Descuento_Tipo AS TIPO_ING
				  FROM cat_convenio_empresas
				  WHERE ID_empresa='".$ID_empresa."' ";
		$rs_cons=$db->Execute($sql_cons);

		$Tipo_pago=strtoupper($rs_cons->fields["TIPO_ING"]);
	}

	if($Tipo_credito == '1' || $Tipo_credito == '2')
	{
		$Discriminante = ($Tipo_credito == '1')?('GET_INGRESOS_INDIV'):('GET_INGRESOS_SOLIDARIO');
		$sql_cons="
					SELECT
						   VALOR  AS TIPO_ING
					 FROM constantes
					 WHERE NOMBRE ='".$Discriminante."'  ";
	    $rs_cons=$db->Execute($sql_cons);

		$Tipo_pago=strtoupper($rs_cons->fields["TIPO_ING"]);
	}

 return $Tipo_pago;

}

//PERCENT DESC
function get_percent_desc($ID_empresa,$Tipo_credito)
{
	 global $db;

   if($Tipo_credito == '3')
   {
		$sql_cons = "SELECT Porcentaje_Descuento AS DSCNT
				  FROM cat_convenio_empresas
				  WHERE ID_empresa='".$ID_empresa."' ";
		$rs_cons=$db->Execute($sql_cons);

		$Percent_desc=(!empty($rs_cons->fields["DSCNT"]))?($rs_cons->fields["DSCNT"]/100):("0");
	}

	if($Tipo_credito == '1')
	{
		$sql_cons="
					SELECT
						   VALOR  AS DSCNT
					 FROM constantes
					 WHERE NOMBRE ='PORCENTAJE_DESCUENTO_INDIV'  ";
	    $rs_cons=$db->Execute($sql_cons);

		$Percent_desc=(!empty($rs_cons->fields["DSCNT"]))?($rs_cons->fields["DSCNT"]/100):("0");
	}

 return $Percent_desc;
}

//CAPACIDAD DE PAGO
function get_capacidad_pago($id_empresa,$ingresos_soli,$id_producto,$num_cte,$tipo_credito)
{
	 global $db;


	$sql_prod = "SELECT Vencimiento AS VNC
		    FROM  cat_productosfinancieros
		    WHERE ID_Producto = '".$id_producto."' ";
	$rs_prod=$db->Execute($sql_prod);

	$frecuencia=$rs_prod->fields["VNC"];

	 switch ($frecuencia )
		{
		  case 'Anios' : 		$dias_periodo = 360; 	break;
		  case 'Semestres' :    $dias_periodo = 180; 	break;
		  case 'Trimestres' :   $dias_periodo = 90; 	break;
		  case 'Bimestres' :    $dias_periodo = 60; 	break;
		  case 'Meses' : 		$dias_periodo = 30; 	break;
		  case 'Quincenas' :    $dias_periodo = 15; 	break;
		  case 'Catorcenas' :   $dias_periodo = 14; 	break;
		  case 'Semanas' :      $dias_periodo = 7; 		break;        
		  case 'Dias' :         $dias_periodo = 1; 		break;     
		};  

	/*
	$sql_cons = "SELECT Porcentaje_Descuento AS DSCNT
		      FROM cat_convenio_empresas
		      WHERE ID_empresa='".$id_empresa."' ";
	$rs_cons=$db->Execute($sql_cons);
	*/
	
	$Percent_desc=get_percent_desc($id_empresa,$tipo_credito);

	$Cap_pago=$ingresos_soli * $Percent_desc;
	$Cap_pago= ($frecuencia != 'Semanas' )?(($Cap_pago/30)* $dias_periodo):($Cap_pago/4);


	  if(isset($num_cte) && !empty($num_cte))
	    {
	    //SI ES CLIENTE ACTUAL SE MUESTRA INFO ADICIONAL
	    $Info_facturas=get_info_facturas($num_cte);
	    $Renta=$Info_facturas["TOT_RENTA"];
	    $Renta= ($frecuencia != 'Semanas' )?(($Renta/30)* $dias_periodo):($Renta/4);

	    $Cap_pago=$Cap_pago-$Renta;
	    }
	 
 return $Cap_pago;
}

//CAPITAL MÁXIMO A OTORGAR
//SE COMENTA EL DÍA 23/FEB/2012
/*
function get_capital_maximo($id_producto,$plazo,$capacidad_pago)
{
	 global $db;
	 global $ID_SUC ;

	$sql_cons = "SELECT TasaMensual_Tipo     AS MET,
			    Capital_Max          AS CAP_MAX,
			    Vencimiento          AS VNC
		      FROM cat_productosfinancieros
		      WHERE ID_Producto='".$id_producto."' ";
	$rs_cons=$db->Execute($sql_cons);
	$Cap_max=$rs_cons->fields["CAP_MAX"];
	$frecuencia=$rs_cons->fields["VNC"];

	$sql_iva = "SELECT 
                           IVA_General AS IVA
		      FROM sucursales
		      WHERE ID_Sucursal='".$ID_SUC."' ";
	$rs_iva=$db->Execute($sql_iva);
 	$IVA=$rs_iva->fields["IVA"]/100;       

	 switch ($frecuencia )
		{
		  case 'Anios' : 	$dias_periodo = 360; break;
		  case 'Semestres' :    $dias_periodo = 180; break;
		  case 'Trimestres' :   $dias_periodo = 90; break;
		  case 'Bimestres' :    $dias_periodo = 60; break;
		  case 'Meses' : 	$dias_periodo = 30; break;
		  case 'Quincenas' :    $dias_periodo = 15; break;
		  case 'Semanas' :      $dias_periodo = 7; break;        
		  case 'Dias' :         $dias_periodo = 1; break;     
		};



	if($rs_cons->fields["MET"]=='Insoluta')
	{

		$sql_cons = "SELECT TasaMensual AS TASA,
				    IVA_Interes AS IVA,
				    Vencimiento AS VNC

			      FROM cat_productosfinancieros
			      WHERE ID_Producto='".$id_producto."' ";
		$rs_cons=$db->Execute($sql_cons);

		$Tasa=$rs_cons->fields["TASA"]/100;
		$Vencimiento= $rs_cons->fields["VNC"];


		$Tasa_semanal_bruta=(($Tasa/30)*$dias_periodo);

		$Tasa_semanal_neta=$Tasa_semanal_bruta*(1+$IVA);

		$Capital_sugerido=$capacidad_pago * (1-( pow( (1+$Tasa_semanal_neta) , (-1*$plazo)  ) ))/$Tasa_semanal_neta;

		$Capital_sugerido=($Capital_sugerido > $Cap_max)?($Cap_max):($Capital_sugerido);


	 }

	if($rs_cons->fields["MET"]=='Soluta')
	{
	$sql_cons = "SELECT TasaMensual AS TASA,
				    IVA_Interes AS IVA,
				    Vencimiento AS VNC

			      FROM cat_productosfinancieros
			      WHERE ID_Producto='".$id_producto."' ";
		$rs_cons=$db->Execute($sql_cons);

		$Tasa=$rs_cons->fields["TASA"]/100;
		$Vencimiento= $rs_cons->fields["VNC"];


		$Tasa_semanal_bruta=(($Tasa/30)*$dias_periodo);

		$Tasa_semanal_neta=$Tasa_semanal_bruta*(1+$IVA);


		$Capital_sugerido=($capacidad_pago * $plazo) / (1+($plazo*$Tasa_semanal_neta) );

	}

 return $Capital_sugerido;
}
*/

function get_capital_maximo($id_producto,$plazo,$capacidad_pago)
{

	global $ID_SUC;
	global $db;
	$id_sucursal	= 	$ID_SUC;
	$renta			= $capacidad_pago;
	

	$sql= "SELECT
				sucursales.IVA_General
			FROM sucursales
			WHERE sucursales.ID_Sucursal = '".$id_sucursal."' ";
 	$rs=$db->Execute($sql);
 	
 	$piva = $rs->fields[0]/100;


	$sql= "SELECT  cat_productosfinancieros.Capital_Min,
		       cat_productosfinancieros.Capital_Max,
		       cat_productosfinancieros.Vencimiento,
		       cat_productosfinancieros.Comision_Tipo,
		       cat_productosfinancieros.Comision_calculo,
		       cat_productosfinancieros.Comision_Apertura,
		       cat_productosfinancieros.TasaMensual,
		       cat_productosfinancieros.TasaMensual_Tipo,
		       cat_productosfinancieros.Metodo,
		       cat_productosfinancieros.RedondeoCifras

		FROM cat_productosfinancieros
		WHERE cat_productosfinancieros.ID_Producto = '".$id_producto."' ";
		
 	$rs=$db->Execute($sql);



	$capital_min		= $rs->fields[0];
	$capital_max		= $rs->fields[1];
	$periodo			= $rs->fields[2];
	$comision_tipo		= $rs->fields[3];
	$comision_calculo	= $rs->fields[4];
	$comision_apertura	= $rs->fields[5];

	$tasamensual		= $rs->fields[6];
	$tasamensual_tipo	= $rs->fields[7];
	$metodo				= $rs->fields[8];
	$redondeocifras		= ($rs->fields['RedondeoCifras']=='Si')?(true):(false);
	
	


        switch ($periodo )
        {
          // Anual
          case 'Anios' 		: 	$tipo_plazo = "años.";
								$tipo_plazox = "anual.";
								$frecuencia = 1;
                   break;
          // Semestral         
          case 'Semestres'  : 	$tipo_plazo = "semestres.";
								$tipo_plazox = "semestral";
								$frecuencia = 2;
                   break;
         // Trimestral          
          case 'Trimestres' : 	$tipo_plazo = "trimestres.";
								$tipo_plazox = "trimestral";   
								$frecuencia = 3;
                   break;
         // Bimestral          
          case 'Bimestres'  : 	$tipo_plazo = "bimestres.";
								$tipo_plazox = "bimestral";   
								$frecuencia = 4;
                   break; 
          // Mensual
          case 'Meses'     :	$tipo_plazo = "meses.";
								$tipo_plazox = "mensual";   
								$frecuencia = 5;
                   break;
          //Quincenal 
          case 'Quincenas' : 	$tipo_plazo = "quincenas.";
								$tipo_plazox = "quincenal";   
								$frecuencia = 6;
                   break;
          //Semanal                   
          case 'Semanas'   :	$tipo_plazo = "semanas.";
								$tipo_plazox = "semanal";    
								$frecuencia = 7;
                   break;
           //Diaria                   
          
          case 'Dias' 		:	$tipo_plazo = "dias.";
								$tipo_plazox = "diaria";     
								$frecuencia = 8;
                   break;
          case 'Catorcenas' : 	$tipo_plazo = "catorcenas.";
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


	$tasa_bruta_periodo = ($tasamensual/(30*100))*$dias_periodo;
	$tasa_neta_periodo  = $tasa_bruta_periodo * (1+$piva);

	$capacidad_pago_periodo   = $renta;		
	
	
	if($redondeocifras)
	{
	    if(round($capacidad_pago_periodo,2)>trunc($capacidad_pago_periodo))
	       $capacidad_pago_periodo   = ceil($capacidad_pago_periodo);
	}

	


	if($comision_tipo == 'Diferida')
	{

		 if($comision_calculo == 'Cuota')
			  $capacidad_pago_periodo = (($capacidad_pago_periodo - (($comision_apertura*(1+$piva))/$plazo)));
			
	}


	
	if($tasamensual_tipo == "Insoluta")		
	{


		if($tasa_neta_periodo > 0)
		   $_capital = ($capacidad_pago_periodo * (1- pow((1+$tasa_neta_periodo),(-1 * $plazo))))/$tasa_neta_periodo;
		else
		   $_capital = $capacidad_pago_periodo * $plazo;
		   
	}
	else
		   $_capital = ($capacidad_pago_periodo * $plazo)/(1+($tasa_neta_periodo* $plazo));


	$_capital = ($_capital >  $capital_max)?($capital_max):($_capital);
	$_capital = ($_capital <  $capital_min)?($capital_min):($_capital);
	$_capital = ($plazo    <= 0)?(0.0):($_capital);

	return($_capital);

}


//RENTA NORMAL Ó MÁXIMA
function get_renta($id_producto,$monto,$plazo,$num_cliente)
{

	 global $db;
	 global $ID_SUC ;

	/***************RENTA******************/

	$sql_fact = "SELECT MAX(Fecha_Inicio)
		       FROM fact_cliente
		       WHERE num_cliente ='".$num_cliente."'";
	$rs_fact=$db->Execute($sql_fact);
	$ultima_fech_ini=$rs_fact->fields[0];


	$sql_fact = "SELECT id_factura
		       FROM fact_cliente
		       WHERE num_cliente ='".$num_cliente."' and Fecha_Inicio='".$ultima_fech_ini."' ";
	$rs_fact=$db->Execute($sql_fact);
	$id_factura=$rs_fact->fields[0];
	$ultima_fact=$rs_fact->fields[0];


	$fecha_sol  =  date("Y-m-d");

	$obj = new TCUENTA($id_factura, $fecha_sol,'','',true);


	$sql_cierre = "SELECT COUNT(*)
			FROM caja_credito_saldada
		       WHERE id_factura='".$id_factura."' ";
	$rs_cierre=$db->Execute($sql_cierre);
	$bandera_cierre=$rs_cierre->fields[0];

	if($bandera_cierre >'0')
	{
	$obj->SaldoParaLiquidar='0';
	}

	$Total=$monto+$obj->SaldoParaLiquidar;
	$Total=round($Total,2);

	$fecha_sol  =  date("Y-m-d");

	$genera=new TNuevoCredito(0,$fecha_sol,$fecha_sol,$Total,$id_producto,$plazo,$db,$ID_SUC);
	$Renta=$genera->renta;

	/*************************************/

return $Renta;
}

function get_renta_simula($id_producto,$monto,$plazo)
{


	 global $db;
	 global $ID_SUC ;

	

	$fecha_sol  =  date("Y-m-d");

	$genera=new TNuevoCredito(0,$fecha_sol,$fecha_sol,$monto,$id_producto,$plazo,$db,$ID_SUC);
	/*************************************/
	$genera->cotiza_credito();
	$tabla= "<H2 Align='center'><U> Simulación de crédito </U></H2>";

			$tabla.= "<TABLE ALIGN='center' CELLSPACING=0 CELLPADDING=0 ID='B'  WIDTH='900px'>   \n";
			$tabla.= "<TR>    \n";
			$tabla.= "<TD bgcolor='lightsteelblue' >    \n";
			$tabla.= "<FIELDSET>    \n";
			$tabla.= "<TABLE ALIGN='center' CELLSPACING=1 CELLPADDING=2 ID='B' bgcolor='white' WIDTH='100%'>   \n";
			$tabla.= "<TR>    \n";
			$tabla.= "<TH Align='right' Width='170px'> Sucursal :</TH><TD> ".$genera->nombre_sucursal."</TD>\n";
			$tabla.= "</TR><TR>    \n";
			$tabla.= "<TH Align='right' Width='170px'>Nombre del acreditado : </TH><TD>  ".$genera->nombre_cliente."</TD>\n";
			$tabla.= "</TR><TR>    \n";
			$tabla.= "<TH Align='right' Width='170px'> Producto Financiero :</TH><TD> ".$genera->producto_financiero."</TD>\n";
			$tabla.= "</TR><TR>    \n";
			$tabla.= "<TH Align='right' Width='170px'>Plazo : </TH><TD>".$genera->plazo ." ".$genera->periodo	."</TD>\n";
			$tabla.= "</TR><TR>    \n";
			$tabla.= "<TH Align='right' Width='170px'> IVA Aplicable :</TH><TD> ".number_format(($genera->iva_general * 100),2)."%</TD>\n";
			$tabla.= "</TR><TR>    \n";
			$tabla.= "<TH Align='right' Width='170px'> Capital :</TH><TD> ".number_format(($genera->capital),2)."</TD>\n";
			$tabla.= "</TR><TR>    \n";
			$tabla.= "<TH Align='right' Width='170px'> Pago fijo :</TH><TD> ".number_format(($genera->renta),2)."</TD>\n";
			if($genera->esquema == 1)
			{
				$tabla.= "</TR><TR>    \n";
				$tabla.= "<TH Align='right' Width='170px'> Vencimientos :</TH><TD> ".number_format(($genera->num_vencimientos),0)."</TD>\n";
			}

			if($genera->metodo == 'Saldos Insolutos')
			{

					if($genera->tipo_plazox == 'mensual')
					{
						$tabla.= "</TR><TR>    \n";
						$tabla.= "<TH Align='right' Width='170px'> Tasa ".$genera->tipo_plazox." :</TH><TD> ".number_format(($genera->tasa_periodo_ssi* 100),16)."%</TD>\n";
					}
					else
					{

						$tabla.= "</TR><TR>    \n";
						$tabla.= "<TH Align='right' Width='170px'> Tasa mensual : </TH><TD> ".number_format(($genera->tasa_mensual_ssi* 100),12)."%</TD>\n";

						$tabla.= "<TR></TR>    \n";
						$tabla.= "<TH Align='right' Width='170px'> Tasa ".$genera->tipo_plazox." : </TH><TD> ".number_format(($genera->tasa_periodo_ssi* 100),12)."%</TD>\n";

					}
			}
			else
			{

					if($genera->tipo_plazox != 'mensual')
					{
						$tabla.= "</TR><TR>    \n";
						$tabla.= "<TH Align='right' Width='170px'> Tasa ".$genera->tipo_plazox." : </TH><TD> ".number_format(($genera->tasa_periodo_ssol* 100),8)."%</TD>\n";
					}
					else
					{

						$tabla.= "</TR><TR>    \n";
						$tabla.= "<TH Align='right' Width='170px'> Tasa mensual :</TH><TD> ".number_format(($genera->tasa_mensual_ssol* 100),8)."%</TD>\n";

						$tabla.= "</TR><TR>    \n";
						$tabla.= "<TH Align='right' Width='170px'> Tasa ".$genera->tipo_plazox." : </TH><TD> ".number_format(($genera->tasa_periodo_ssol* 100),8)."%</TD>\n";


					}



			}

			$tabla.= "<TH Align='right' Width='170px'> C.A.T. informativo : </TH><TD> ".number_format(($genera->cat * 100),2)	."%</TD>\n";
			$tabla.= "</TR> \n";
			$tabla.= "</TABLE> \n";
			$tabla.= "</FIELDSET>    \n";
			$tabla.= "</TD>    \n";
			$tabla.= "</TR>    \n";
			$tabla.= "</TABLE><BR> \n";


			$tabla.= "<TABLE ALIGN='center' CELLSPACING=0 CELLPADDING=0 ID='B'  WIDTH='900px'>   \n";
			$tabla.= "<TR>    \n";
			$tabla.= "<TD bgcolor='lightsteelblue' >    \n";
			$tabla.= "<FIELDSET>    \n";
			$tabla.= "<TABLE ALIGN='center' CELLSPACING=1 CELLPADDING=2 ID='B' bgcolor='lightsteelblue' WIDTH='100%'>   \n";

			$tabla.= "<TR  bgcolor='lightsteelblue'>  \n";
			$tabla.= "<TH  ALIGN='left' COLSPAN='9'>";

			$msj = ($default_redondeo != $genera->redondeocifras)?("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<SPAN STYLE='color:red;'> -- ATENCIÓN : Ésta vista no representa la opción predeterminada.</SPAN>"):("");

			if($genera->redondeocifras)
			{
				$tabla.= "<INPUT TYPE='CHECKBOX'  onClick='document.simulafin.verconredondeo.value=\"-1\"; document.simulafin.genera.value=\"1\";    document.simulafin.submit();'> Visualizar tabla de amortización <SPAN STYLE='color:blue;'>sin</SPAN> redondeo de cifras. ".$msj;
			}
			else
			{
				$tabla.= "<INPUT TYPE='CHECKBOX'  onClick='document.simulafin.verconredondeo.value=\"+1\"; document.simulafin.genera.value=\"1\";    document.simulafin.submit();'> Visualizar tabla de amortización <SPAN STYLE='color:blue;'>con</SPAN> redondeo de cifras.".$msj;

			}
			$tabla.= "</TH>  \n";

			$tabla.= "</TR>  \n";

			$tabla.= "<TR  bgcolor='gray'>  \n";
			$tabla.= "<TH  ALIGN='left' COLSPAN='9' STYLE='height:2px;'></TH>  \n";
			$tabla.= "</TR>  \n";

			$tabla.= "<TR  bgcolor='lightsteelblue' ALIGN='center'>  \n";
			$tabla.= "        <TH>No. Vencimiento     </TH>  \n";
			$tabla.= "        <TH>Fecha de Vencimiento</TH>  \n";

			$tabla.= "        <TH>Capital     </TH>  \n";

			$tabla.= "        <TH>Comisiones    </TH>  \n";
			$tabla.= "        <TH>IVA Comisiones     </TH>  \n";



			$tabla.= "        <TH>Intereses     </TH>  \n";
			$tabla.= "        <TH>IVA  Intereses     </TH>  \n";

			$tabla.= "        <TH>Pago fijo           </TH>  \n";
			$tabla.= "        <TH>Saldo de Capital    </TH>  \n";
			$tabla.= "</TR>  \n";

			foreach($genera->tabla_amortizacion AS $i=>$row)
			{
				$color=($color=='white')?('lavender'):('white');

				$tabla.= "  <TR Align='right'  BGCOLOR='".$color."'>
								<TH Align='center'> ".$i.")    </TH>
								<TD Align='center'> ".$row['FECHA']."</TD>\n";
				$tabla.= "          		<TD>".number_format($row['ABONO_CAPITAL']  	   ,2)."</TD>";
				$tabla.= "          		<TD>".number_format($row['COMISION']	   	   ,2)."</TD>
								<TD>".number_format($row['ABONO_IVA_COMISION'] 	   ,2)."</TD>
								<TD>".number_format($row['ABONO_INTERES']  	   ,2)."</TD>
								<TD>".number_format($row['ABONO_IVA_INTERES'] 	   ,2)."</TD>
								<TD>".number_format($row['RENTA'] 		   ,2)."</TD>
								<TD>".number_format($row['SALDO_CAPITAL']  	   ,2)."</TD>\n";
				$tabla.= " </TR>\n";
			}

			$tabla.= "<TR  bgcolor='lightsteelblue' ALIGN='center'>  \n";
			$tabla.= "        <TH></TH>  \n";
			$tabla.= "        <TH></TH>  \n";

			$tabla.= "        <TH>Capital     </TH>  \n";

			$tabla.= "        <TH>Comisiones    </TH>  \n";
			$tabla.= "        <TH>IVA Comisiones     </TH>  \n";



			$tabla.= "        <TH>Intereses     </TH>  \n";
			$tabla.= "        <TH>IVA  Intereses     </TH>  \n";

			$tabla.= "        <TH>Pago fijo           </TH>  \n";
			$tabla.= "        <TH>Saldo de Capital    </TH>  \n";
			$tabla.= "</TR>  \n";
			$tabla.= "<TR  bgcolor='gray'>  \n";
			$tabla.= "<TH  ALIGN='left' COLSPAN='9' STYLE='height:2px;'></TH>  \n";
			$tabla.= "</TR>  \n";

			$tabla.= "	<TR ALIGN='right'>";
			$tabla.= "	<TD COLSPAN='2'></TD>";
			$tabla.= "	<Th>".number_format($genera->tot_abono_capital,  	  2)."</Th>";

			$tabla.= "	<Th>".number_format($genera->tot_abono_comision, 	  2)."</Th>";
			$tabla.= "	<Th>".number_format($genera->tot_abono_iva_comision,      2)."</Th>";

			$tabla.= "	<Th>".number_format($genera->tot_abono_interes,  	  2)."</Th>";
			$tabla.= "	<Th>".number_format($genera->tot_abono_iva_interes,       2)."</Th>";
			$tabla.= "	<Th>".number_format($genera->tot_abonos,         	  2)."</Th>";
			$tabla.= "	</TR>";



			$tabla.= "</TABLE> \n";
			$tabla.= "</FIELDSET>    \n";
			$tabla.= "</TD>    \n";
			$tabla.= "</TR>    \n";

	$tabla.= "</TABLE> \n";
	
return $tabla;	
}

/****************FIN FUNCIONES*****************************/




/****************DESPLEGADOS AJAX**************************/

//DESPLIEGA TABLA DE PRODUCTO FINANCIERO Y PLAZO
if(isset($Tipo_cotizador) && !empty($Tipo_cotizador)  )
{

	 //SI es cliente Actual
	 if(isset($num_cte) && !empty($num_cte))
	 {
	  $sql_cons = "SELECT 
			      clientes_datos.Ingresos_soli,
			      clientes_datos.ID_empresa,
			      cat_convenio_empresas.Empresa,
			      cat_convenio_empresas.Porcentaje_Descuento,
			      Concat(clientes_datos.Nombre,' ',clientes_datos.NombreI,' ',clientes_datos.Ap_paterno,' ',clientes_datos.Ap_materno) AS NomCliente,
			      clientes_datos.ID_Producto
			 FROM  clientes_datos
			 INNER JOIN cat_convenio_empresas ON clientes_datos.ID_empresa = cat_convenio_empresas.ID_empresa
			 WHERE Num_cliente ='".$num_cte."'  ";


	   $rs_cons =$db->Execute($sql_cons);   
	   list($ingresos_soli,$id_empresa,$empresa_soli,$Percent_emp,$Nombre,$producto_financiero)=$rs_cons->fields;
	   
	   $Nombre             ="(".$rs_cons->fields["NomCliente"].")";
	   $Capacidad_pago_temp=$ingresos_soli*($Percent_emp/100);
           
	   $plazo_cmb=select_plazo($producto_financiero);
	   $producto_financiero=select_producto_financiero($producto_financiero,$id_empresa);
	 }

	 $Msg_header=(!empty($num_cte))?("CLIENTE ACTUAL <BR> $Nombre"):("CLIENTE NUEVO");
	 
	
	 

	   $html="";
	   $html.="<BR><BR>";
	   $html.="<HR>";
	   $html.="<BR><BR>";
	   $html.="<TABLE  CELLSPACING='2' SUMMARY='DATOS ASOCIADOS AL COTIZADOR'  ALIGN='CENTER' WIDTH='90%' >

		   <TR>
			<TH  ALIGN='CENTER' COLSPAN='4'  STYLE='-moz-border-radius-topleft:  6px; -moz-border-radius-topright: 6px;  background-color : #7A9CCF;'>
			    <B> <FONT SIZE='2' COLOR='WHITE'>COTIZADOR - ".$Msg_header."</FONT></B>
			</TH>
		   </TR>

		<TR Bgcolor='#e7eef6'>
			<TH ALIGN='RIGHT'     WIDTH='20%'><FONT size='2' > Empresa:<SUP STYLE='color:red;'>*</SUP>&nbsp;&nbsp;</FONT></TH>
			    <TD ALIGN='LEFT'  WIDTH='30%'>

				<INPUT ID='B' type=text name='empresa_soli' VALUE='".$empresa_soli."'  size=45  READONLY>
				<INPUT TYPE='BUTTON'  ID='B' NAME='grid_emp' STYLE='font-weight:bold; height:21px; cursor:pointer;' VALUE='+'  ONCLICK='popup();' />
				<INPUT TYPE='HIDDEN'  NAME='id_empresa' VALUE='".$id_empresa."'  >

			    </TD>

			<TH ALIGN='RIGHT'      WIDTH='20%'><FONT SIZE='2' > % máximo de descuento:&nbsp;&nbsp;</FONT></TH>
			     <TD ALIGN='LEFT'  WIDTH='30%'>

			       <LABEL ID='Percent_emp' STYLE='font-size:11px; color:gray; font-style:oblique;'>".$Percent_emp."</LABEL>
			     </TD>

		</TR>

		<TR Bgcolor='#e7eef6'>
			<TH ALIGN='RIGHT'     WIDTH='20%'><FONT size='2' > Ingresos netos (mensuales):<SUP STYLE='color:red;'>*</SUP>&nbsp;&nbsp;</FONT></TH>
			    <TD ALIGN='RIGHT'  WIDTH='30%'>

				<B>$</B><INPUT ID='B' TYPE=text NAME='ingresos_soli' VALUE='".$ingresos_soli."'  SIZE=20 STYLE='text-align: right;'
					 ONKEYPRESS='return Tipo_permitido(event,\"NUMEROS\");' ONBLUR='muestra_detalle_credito(\"limpiar\");'>

			    </TD>

			<TH ALIGN='RIGHT'      WIDTH='20%'><FONT SIZE='2' >&nbsp;&nbsp;</FONT></TH>
			     <TD ALIGN='RIGHT'  WIDTH='30%'>
			       &nbsp;
			     </TD>

		</TR>";

	  if(isset($num_cte) && !empty($num_cte))
	  {

		 //SI ES CLIENTE ACTUAL SE MUESTRA INFO ADICIONAL
		  $Info_facturas=get_info_facturas($num_cte);


	   $html.="<TR Bgcolor='#e7eef6'>
			<TH ALIGN='RIGHT'     WIDTH='20%'><FONT size='2' >ID. factura vigente(s)&nbsp;&nbsp;</FONT></TH>
			    <TD ALIGN='LEFT'  WIDTH='30%'>

				<B><B>".$Info_facturas["FACTURAS"]."</B></B>&nbsp;&nbsp;
				<INPUT  TYPE='BUTTON' ID='S2' STYLE='font-weight:bold; height:21px; cursor:pointer;' VALUE='&raquo;' NAME='edo_cuenta'  onclick='popup_ver_edocta(\"$num_cte\")' STYLE='cursor:pointer'  >

			    </TD>

			<TH ALIGN='RIGHT'      WIDTH='20%'><FONT SIZE='2' >Valor cuota (mensual):&nbsp;&nbsp;</FONT></TH>
			     <TD ALIGN='RIGHT'  WIDTH='30%'>
			       <B><B>$".number_format($Info_facturas["TOT_RENTA"],2)."</B></B>
			     </TD>

		</TR>";


		if($Info_facturas["TOT_SALDO_VENCIDO"] > '0.01')
		    {
		      echo"<BR><BR>
		           <CENTER>
			    <H1><FONT COLOR='BLACK'>EL CLIENTE CUENTA CON SALDO VENCIDO : ".$Info_facturas["TOT_SALDO_VENCIDO"]."

			    <BR>IMPOSIBLE CONTINUAR CON LA RENOVACIÓN</font><BR><IMG  BORDER=0 SRC='".$img_path."stop.png'  ALT='editando'   />

			    </H1>
			    <CENTER>";
		      die();
		    }
		    
            }
 		
 		
 	    if($Capacidad_pago_temp < $Info_facturas["TOT_RENTA"] )
	       {

		        $html.="<BR><BR>
		                </TABLE>";
		       	$html.="<TABLE  CELLSPACING='2' SUMMARY='DATOS ASOCIADOS A LA COTIZACIÓN'  ALIGN='CENTER' WIDTH='90%' >
		       
		       		     <TR>
		       			<TH  ALIGN='CENTER' COLSPAN='4'  STYLE='-moz-border-radius-topleft:  6px; -moz-border-radius-topright: 6px;  background-color : #7A9CCF;'>
		       			    <B> <FONT SIZE='3' COLOR='YELLOW'>EL CLIENTE NO CUENTA CON UNA CAPACIDAD DE PAGO SUFICIENTE PARA CONTINUAR</FONT></B>
		       			    <BR>IMPOSIBLE CONTINUAR CON LA RENOVACIÓN</font><BR><IMG  BORDER=0 SRC='".$img_path."stop.png'  ALT='editando'   />
		       			</TH>
		       		      </TR>
		                    </TABLE>";
		  		     
		}
                else
                {
			$html.="
			       <TR Bgcolor='#e7eef6'>
					<TH ALIGN='RIGHT'     WIDTH='20%'><FONT size='2' > Producto:<SUP STYLE='color:red;'>*</SUP>&nbsp;&nbsp;</FONT></TH>
					    <TD ALIGN='RIGHT'  WIDTH='30%'>
						<DIV ID='DIV_PROD_FIN' ALIGN='LEFT'>".$producto_financiero."</DIV>
					    </TD>

					<TH ALIGN='RIGHT'      WIDTH='20%'><FONT SIZE='2' > Plazo:<SUP STYLE='color:red;'>*</SUP>&nbsp;&nbsp;</FONT></TH>
					     <TD ALIGN='RIGHT'  WIDTH='30%'>

						<DIV ID='DIV_PLAZO' ALIGN='RIGHT'>".$plazo_cmb."</DIV>
					     </TD>

				</TR>


				<TR Bgcolor='#e7eef6'>
					<TH ALIGN='RIGHT'     WIDTH='20%'><FONT size='2' > Vencimiento:&nbsp;&nbsp;</FONT></TH>
					    <TD ALIGN='LEFT'  WIDTH='30%'>
						<DIV ID='DIV_VENCE' ALIGN='LEFT'></DIV>
					    </TD>

					<TH ALIGN='RIGHT'      WIDTH='20%'><FONT SIZE='2' > Capacidad de pago:&nbsp;&nbsp;</FONT></TH>
					     <TD ALIGN='RIGHT'  WIDTH='30%'>
						 <DIV ID='DIV_CAP_PAGO' ALIGN='RIGHT'>
					     </TD>

				</TR>

				</TABLE>";

			  $html.="<BR>
				  <CENTER>
				  <IMG  BORDER=0 SRC='".$img_path."redo.png'  ALT='editando'  ALIGN='CENTER' STYLE='CURSOR:POINTER' ONCLICK='muestra_detalle_credito(\"calcular\");' />
				</CENTER>";
			
			
		}	
            
            
	   echo $html;
}


//DESPLIEGA TABLA DE DETALLE CRÉDITO
if(isset($Detalle_cotizador) && !empty($Detalle_cotizador) && isset($id_producto) && !empty($id_producto) && isset($id_empresa) && !empty($id_empresa) && isset($ingresos_soli) && !empty($ingresos_soli) && isset($Cotizador) && !empty($Cotizador))
{

	   //$vencimiento      = get_vencimiento   ($id_producto);
	   $capacidad_pago     = get_capacidad_pago($id_empresa,$ingresos_soli,$id_producto,$num_cte,$tipo_credito);
	   $Capital_maximo     = get_capital_maximo($id_producto,$plazo,$capacidad_pago);
	   $Renta_maxima       = get_renta         ($id_producto,$Capital_maximo,$plazo,$num_cliente);

	 if($capacidad_pago > '0' )
	 {
	   $html ="<BR><BR>";
	   $html.="<TABLE  CELLSPACING='2' SUMMARY='DATOS ASOCIADOS A LA COTIZACIÓN'  ALIGN='CENTER' WIDTH='90%' >

		   <TR>
			<TH  ALIGN='CENTER' COLSPAN='4'  STYLE='-moz-border-radius-topleft:  6px; -moz-border-radius-topright: 6px;  background-color : #7A9CCF;'>
			    <B> <FONT SIZE='2' COLOR='WHITE'>DATOS ASOCIADOS A LA COTIZACIÓN</FONT></B>
			</TH>
		   </TR>
		<TR Bgcolor='#e7eef6'>
			<TH ALIGN='RIGHT'     WIDTH='20%'><FONT size='2' > Máximo a otorgar:&nbsp;&nbsp;</FONT></TH>
			    <TD ALIGN='RIGHT'  WIDTH='30%'>
			       <B>$".number_format($Capital_maximo,2)."</B>
			    </TD>


			<TH ALIGN='RIGHT'      WIDTH='20%'><FONT SIZE='2' > Máximo monto a pagar:&nbsp;&nbsp;</FONT></TH>
			     <TD ALIGN='RIGHT'  WIDTH='30%'>
				<B>$".number_format($Renta_maxima,2)."</B>
			     </TD>

		</TR>




		<TR Bgcolor='#e7eef6'>
			<TH ALIGN='RIGHT'      WIDTH='20%'><FONT SIZE='2' >Monto a autorizar:<SUP STYLE='color:red;'>*</SUP>&nbsp;&nbsp;</FONT></TH>
			     <TD ALIGN='LEFT'  WIDTH='30%'>
					<B>$</B><INPUT ID='B' TYPE=text NAME='monto' VALUE='".$monto."'  SIZE=20 STYLE='text-align: right;'
					ONKEYPRESS='return Tipo_permitido(event,\"NUMEROS\")' ONBLUR='detalle_autorizar();'; >
			     </TD>



			<TH ALIGN='RIGHT'     WIDTH='20%'><FONT size='2' > Monto a pagar autorizado:&nbsp;&nbsp;</FONT></TH>
			    <TD ALIGN='RIGHT'  WIDTH='30%'>
				<DIV ID='DIV_RENTA' ALIGN='RIGHT'></DIV>
			    </TD>
		</TR>


		<TR Bgcolor='#e7eef6'>

			<TH ALIGN='RIGHT'     WIDTH='20%'><FONT size='2' > &nbsp;&nbsp;</FONT></TH>
			    <TD ALIGN='RIGHT'  WIDTH='30%'>
				&nbsp;
			    </TD>


			<TH ALIGN='RIGHT'     WIDTH='20%'><FONT size='2' > Diferencia:&nbsp;&nbsp;</FONT></TH>
			    <TD ALIGN='RIGHT'  WIDTH='30%'>
				<DIV ID='DIV_DIFER' ALIGN='RIGHT'></DIV>
			    </TD>
		</TR>

		<TR Bgcolor='#e7eef6'>

			     <TD ALIGN='CENTER'  COLSPAN='4'>
				 <DIV ID='DIV_MSG' ALIGN='CENTER'></DIV>
			     </TD>

		</TR>


		   </TABLE>";

	  $html.="<BR>
		  <CENTER>
		  <IMG  BORDER=0 SRC='".$img_path."redo.png'  ALT='editando'  ALIGN='CENTER' STYLE='CURSOR:POINTER' ONCLICK='detalle_autorizar();' />
		</CENTER>";
	  }
	  else
	  {
	     $html ="<BR><BR>";
	     $html.="<TABLE  CELLSPACING='2' SUMMARY='DATOS ASOCIADOS A LA COTIZACIÓN'  ALIGN='CENTER' WIDTH='90%' >

		     <TR>
			<TH  ALIGN='CENTER' COLSPAN='4'  STYLE='-moz-border-radius-topleft:  6px; -moz-border-radius-topright: 6px;  background-color : #7A9CCF;'>
			    <B> <FONT SIZE='3' COLOR='YELLOW'>NO CUENTA CON UNA CAPACIDAD DE PAGO SUFICIENTE PARA SOLICITAR UN NUEVO CRÉDITO</FONT></B>
			</TH>
		   </TR>
		   </TABLE>";


	  }
	  echo $html;

}

//DESPLIEGA TABLA DE DETALLE CRÉDITO
if(isset($Detalle_cotizador) && !empty($Detalle_cotizador) && isset($id_producto) && !empty($id_producto) && isset($id_empresa) && !empty($id_empresa) && isset($ingresos_soli) && !empty($ingresos_soli) && !isset($Cotizador) && empty($Cotizador))
{

	//$vencimiento      = get_vencimiento   ($id_producto);
	//$capacidad_pago     = get_capacidad_pago($id_empresa,$ingresos_soli);
	//$Capital_maximo     = get_capital_maximo($id_producto,$plazo,$capacidad_pago);
	//$Renta_maxima       = get_renta         ($id_producto,$Capital_maximo,$plazo,$num_cliente);

	$html ="<BR><BR>";
	$html.="<TABLE  CELLSPACING='2' SUMMARY='DATOS ASOCIADOS A LA COTIZACIÓN'  ALIGN='CENTER' WIDTH='90%' >

	   <TR>
		<TH  ALIGN='CENTER' COLSPAN='4'  STYLE='-moz-border-radius-topleft:  6px; -moz-border-radius-topright: 6px;  background-color : #7A9CCF;'>
		    <B> <FONT SIZE='2' COLOR='WHITE'>DATOS ASOCIADOS A LA COTIZACIÓN</FONT></B>
		</TH>
	   </TR>
	<TR Bgcolor='#e7eef6'>
		<TH ALIGN='RIGHT'     WIDTH='20%'><FONT size='2' > Máximo a otorgar:&nbsp;&nbsp;</FONT></TH>
		    <TD ALIGN='RIGHT'  WIDTH='30%'>
			<DIV ID='DIV_CAPTMAX' ALIGN='RIGHT'></DIV>
		    </TD>


		<TH ALIGN='RIGHT'      WIDTH='20%'><FONT SIZE='2' > Máximo monto a pagar:&nbsp;&nbsp;</FONT></TH>
		     <TD ALIGN='RIGHT'  WIDTH='30%'>
			 <DIV ID='DIV_RENTA_MAX' ALIGN='RIGHT'></DIV>

		     </TD>

	</TR>

	<TR Bgcolor='#e7eef6'>
		<TH ALIGN='RIGHT'      WIDTH='20%'><FONT SIZE='2' >Monto a autorizar:<SUP STYLE='color:red;'>*</SUP>&nbsp;&nbsp;</FONT></TH>
		     <TD ALIGN='LEFT'  WIDTH='30%'>
				<B>$</B><INPUT ID='B' TYPE=text NAME='monto' VALUE='".$monto."'  SIZE=20 STYLE='text-align: right;'
				ONKEYPRESS='return Tipo_permitido(event,\"NUMEROS\")' ONBLUR='detalle_autorizar();'; >
		     </TD>



		<TH ALIGN='RIGHT'     WIDTH='20%'><FONT size='2' > Monto a pagar autorizado:&nbsp;&nbsp;</FONT></TH>
		    <TD ALIGN='RIGHT'  WIDTH='30%'>
			<DIV ID='DIV_RENTA' ALIGN='RIGHT'></DIV>
		    </TD>
	</TR>


	<TR Bgcolor='#e7eef6'>

		<TH ALIGN='RIGHT'     WIDTH='20%'><FONT size='2' > &nbsp;&nbsp;</FONT></TH>
		    <TD ALIGN='RIGHT'  WIDTH='30%'>
			&nbsp;
		    </TD>


		<TH ALIGN='RIGHT'     WIDTH='20%'><FONT size='2' > Diferencia:&nbsp;&nbsp;</FONT></TH>
		    <TD ALIGN='RIGHT'  WIDTH='30%'>
			<DIV ID='DIV_DIFER' ALIGN='RIGHT'></DIV>
		    </TD>
	</TR>

	<TR Bgcolor='#e7eef6'>

		     <TD ALIGN='CENTER'  COLSPAN='4'>
			 <DIV ID='DIV_MSG' ALIGN='CENTER'></DIV>
		     </TD>

	</TR>
        </TABLE>";

	$html.="<BR>
	  <CENTER>
	  <IMG  BORDER=0 SRC='".$img_path."redo.png'  ALT='editando'  ALIGN='CENTER' STYLE='CURSOR:POINTER' ONCLICK='detalle_autorizar();' />
	</CENTER>";
	echo $html;
}


//DIV AJAX RENTA
if(isset($Detalle_renta) && !empty($Detalle_renta) && isset($id_producto) && !empty($id_producto)  && isset($monto_autoriza) && !empty($monto_autoriza))
{
	$Renta            = get_renta         ($id_producto,$monto_autoriza,$plazo,$num_cliente);
	echo "<B>".number_format($Renta,2)."</B>";
}


//DIV AJAX DIFERENCIA
if(isset($Detalle_difer) && !empty($Detalle_difer) && isset($id_producto) && !empty($id_producto)  && isset($monto_autoriza) && !empty($monto_autoriza) && isset($id_empresa) && !empty($id_empresa) && isset($ingresos_soli) && !empty($ingresos_soli))
{
	$capacidad_pago   = get_capacidad_pago($id_empresa,$ingresos_soli,$id_producto,$num_cte,$tipo_credito);
	$Renta            = get_renta         ($id_producto,$monto_autoriza,$plazo,$num_cliente);
    $Diferencia       = ceil($capacidad_pago) - $Renta ;
 
    $Color			 =($Diferencia < 0)?('RED'):('BLUE');  
	echo "<B><FONT COLOR='".$Color."'>".number_format($Diferencia,2)."</FONT></B>";
}


//DIV AJAX MENSAGE AUTORIZACIÓN
if(isset($Detalle_msg) && !empty($Detalle_msg) && isset($id_producto) && !empty($id_producto)  && isset($monto_autoriza) && !empty($monto_autoriza) && isset($id_empresa) && !empty($id_empresa) && isset($ingresos_soli) && !empty($ingresos_soli) )
{

	 $capacidad_pago   = get_capacidad_pago($id_empresa,$ingresos_soli,$id_producto,$num_cte,$tipo_credito);
	 $Capital_maximo   = get_capital_maximo($id_producto,$plazo,$capacidad_pago);
	 $Renta            = get_renta         ($id_producto,$monto_autoriza,$plazo,$num_cliente);
	 $Diferencia       = ceil($capacidad_pago) - round($Renta) ;


	$sql_cons = "SELECT Metodo       AS MET,
			    Capital_Min  AS CAP_MIN
		      FROM cat_productosfinancieros
		      WHERE ID_Producto='".$id_producto."' ";
	$rs_cons=$db->Execute($sql_cons);
	$Cap_min=$rs_cons->fields["CAP_MIN"];


	if($monto_autoriza > round($Capital_maximo,2) )
	{
	 echo"<CENTER><FONT COLOR='RED' SIZE='2'>EL MONTO AUTORIZADO ES MAYOR AL M&Aacute;XIMO A OTORGAR</FONT></CENTER>";
	}
	elseif($Diferencia < 0)
	{
	 echo"<CENTER><FONT COLOR='RED' SIZE='2'>LA DIFERENCIA ES NEGATIVA.</FONT></CENTER>";

	}
	elseif( $monto_autoriza < $Cap_min  )
	{

	 echo"<CENTER><FONT COLOR='RED' SIZE='2'>EL MONTO AUTORIZADO ES MENOR A: $".number_format($Cap_min,2).".</FONT></CENTER>";


	}
	else
	{

	 echo"<CENTER><B><FONT COLOR='BLUE' SIZE='2'>EL CR&Eacute;DITO ES AUTORIZADO CON LOS PAR&Aacute;METROS ACTUALES</FONT></B></CENTER>";
	 if(empty($solicitud) && empty($asigna_montos) && empty($num_cte) )
	 {
	   $empresa_soli=get_empresa_soli($id_empresa); 
	   
	  $pagina="../presolicitud/presolicitud.php";
		/*
	   echo"<BR><CENTER>
		 <INPUT TYPE='BUTTON'  STYLE='cursor:pointer'  VALUE='CAPTURAR PRESOLICITUD' 
		                                               ONCLICK='location.replace(\"$pagina?Nomina=True&producto=$id_producto&plazo=$plazo&ingresos=$ingresos_soli&monto_autoriza=$monto_autoriza&id_empresa=$id_empresa&empresa_soli=$empresa_soli\");'  ID='B'>
	   <CENTER>";
		 */
	 }

	 if(!empty($asigna_montos) &&  empty($num_cte))
	 {
	 //echo"<CENTER><INPUT TYPE='BUTTON'  STYLE='cursor:pointer' NAME='alta'  VALUE='Alta capacidad de pago' onclick='confirmar_asigna();'  ></CENTER>   ";

	 }

	 if(isset($num_cte) && !empty($num_cte))
	 {
	  //echo"<CENTER><INPUT TYPE='BUTTON'  STYLE='cursor:pointer' NAME='alta'  VALUE='Asignar un nuevo crédito' onclick='location.replace(\"../../tesoreria/autorizacion_cliente.php?num_cliente=".$num_cte."&monto_autoriza=".$monto_autoriza."&id_producto=".$id_producto."&plazo=".$plazo."\");'  ></CENTER>   ";
	 }

	}


}


//DIV AJAX MENSAGE CAPACIDAD DE PAGO
if(isset($Detalle_cap_pago) && !empty($Detalle_cap_pago)  && isset($id_empresa) && !empty($id_empresa) && isset($ingresos_soli) && !empty($ingresos_soli) && isset($id_producto) && !empty($id_producto))
{

	 $capacidad_pago   = get_capacidad_pago($id_empresa,$ingresos_soli,$id_producto,$num_cte,$tipo_credito);
	 echo "<B>".number_format(ceil($capacidad_pago),2)."</B>";

}


//DIV AJAX MENSAGE VENCIMIENTO
if(isset($Detalle_vence) && !empty($Detalle_vence)  && isset($id_producto) && !empty($id_producto) )
{
	$vencimiento      = get_vencimiento   ($id_producto);
	echo "<B>".$vencimiento."</B>";
}


//DIV AJAX  CAPITAL MÁXIMO
if(isset($Detalle_cap_max) && !empty($Detalle_cap_max) && isset($id_producto) && !empty($id_producto) && isset($id_empresa) && !empty($id_empresa) && isset($ingresos_soli) && !empty($ingresos_soli) )
{
	$capacidad_pago     = get_capacidad_pago($id_empresa,$ingresos_soli,$id_producto,$num_cte,$tipo_credito);
	$Capital_maximo     = get_capital_maximo($id_producto,$plazo,$capacidad_pago);
	echo "<B>".number_format($Capital_maximo,2)."</B>";
}


//DIV AJAX  MONTO A PAGAR MÁXIMO
if(isset($Detalle_renta_max) && !empty($Detalle_renta_max) && isset($id_producto) && !empty($id_producto) && isset($id_empresa) && !empty($id_empresa) && isset($ingresos_soli) && !empty($ingresos_soli) )
{
	$capacidad_pago     = get_capacidad_pago($id_empresa,$ingresos_soli,$id_producto,$num_cte,$tipo_credito);
	$Capital_maximo     = get_capital_maximo($id_producto,$plazo,$capacidad_pago);
	$Renta_maxima       = get_renta         ($id_producto,$Capital_maximo,$plazo,$num_cliente);
	echo "<B>".number_format($Renta_maxima,2)."</B>";
}


//DIV AJAX MENSAGE AUTORIZACIÓN
if(isset($Tbl_autoriza) && !empty($Tbl_autoriza) && isset($id_producto) && !empty($id_producto)  && isset($monto_autoriza) && !empty($monto_autoriza) && isset($id_empresa) && !empty($id_empresa) && isset($ingresos_soli) && !empty($ingresos_soli) )
{	$capacidad_pago     = get_capacidad_pago($id_empresa,$ingresos_soli,$id_producto,$num_cte,$tipo_credito);
	$Capital_maximo     = get_capital_maximo($id_producto,$plazo,$capacidad_pago);
	$Renta_maxima       = get_renta         ($id_producto,$Capital_maximo,$plazo,$num_cliente);
        $Tabla_simula       = get_renta_simula  ($id_producto,$monto_autoriza,$plazo);
        
	
    echo $Tabla_simula;    
}

//DETALLE PROD FINAN
if(isset($Detalle_prod_finan) && !empty($Detalle_prod_finan) && isset($id_producto) && !empty($id_producto) )
{
	$Producto_financiero     = get_nmb_prod_financiero($id_producto);
	echo "<B>".$Producto_financiero."</B>";
}

//DETALLE PLAZO
if(isset($Detalle_plazo) && !empty($Detalle_plazo) && isset($plazo) && !empty($plazo)  && isset($id_producto) && !empty($id_producto) )
{
   $vencimiento      = get_vencimiento   ($id_producto);
	echo "<B>".$plazo." ".$vencimiento."</B>";
}



//DIV MONTO AUTORIZAR
if(isset($Detalle_monto_autorizar) && !empty($Detalle_monto_autorizar) && isset($id_producto) && !empty($id_producto) && isset($id_empresa) && !empty($id_empresa) && isset($ingresos_soli) && !empty($ingresos_soli) && isset($monto) && !empty($monto) )
{
	$capacidad_pago     = get_capacidad_pago($id_empresa,$ingresos_soli,$id_producto,$num_cte,$tipo_credito);
	$Capital_maximo     = get_capital_maximo($id_producto,$plazo,$capacidad_pago);
	echo "<B>$".$Capital_maximo."</B>";
}


//DIV PERCENT DESC
if(isset($Detalle_percent_desc) && !empty($Detalle_percent_desc) && isset($id_empresa)  && isset($tipo_credito) && !empty($tipo_credito) )
{
	$Percent_desc = get_percent_desc($id_empresa,$tipo_credito);
	echo "<B>".number_format(($Percent_desc * 100),2)." %</B>";
}


//DIV TIPO PAGO
if(isset($Detalle_tipo_pago) && !empty($Detalle_tipo_pago) && isset($id_empresa)  && isset($tipo_credito) && !empty($tipo_credito) )
{
	$Tipo_pago = get_tipo_ingresos($id_empresa,$tipo_credito);
	echo "".$Tipo_pago."";
}



if(isset($ID_empresa) && !empty($ID_empresa) )
   {   
     	
     $Combo=select_producto_financiero($ID_empresa,$ID_producto);
     echo $Combo;
	
   }

//NOMBRE DEL PRODUCTO FINANCIERO

if(isset($NMB_PROD_FINANCIERO) && !empty($NMB_PROD_FINANCIERO) && isset($ID_PROD_FIN) && !empty($ID_PROD_FIN))
{
	$NMB_PROD_FIN = get_nombre_producto($ID_PROD_FIN);

	echo $NMB_PROD_FIN ;

}


   
?>
