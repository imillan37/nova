<?
/****************************************/
/*Fecha: 21/Septiembre/2011
/*Autor: Tonathiu Cárdenas
/*Descripción: GENERA LOS VALORES PARA EL AUTOCOMPLET
/*Dependencias: captura????.php edicion????.php 
/****************************************/

$exit = 0;
$noheader =1;
include($DOCUMENT_ROOT."/rutas.php");								//CORE CONSTANTES S2CREDIT
require($class_path."lib_pld.php");									//PERFIL TRANSACCIONAL P.L.D.
require("../lib_procesos_alertas.php");								//OBJETO ALERTAS

//Inicio conexión
$db = ADONewConnection(SERVIDOR);
$db->Connect(IP,USER,PASSWORD,NUCLEO);
//Fin Conexión

//$_DATOS=utf8_decode($_POST["Empresa_soli"]);

 //ARRAY PARA VALIDAR
 $fechas_captura = array('Fecha_nacimiento','Fecha_nacimiento_conyuge','Fecha_ingreso_empresa','Fecha_ingreso_empresa_anterior','Fecha_constitucion','Fecha_nacimiento_pfae','Fecha_acta_const','Fecha_insciprp_acta_const','Fecha_poder','Fecha_insciprp_poder','Fecha_comprobante_dom','Fecha_comprobante_ingresos');

 
/***********FUNCIONES AUX******************/

function getRealIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
        return $_SERVER['HTTP_CLIENT_IP'];
       
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
   
    return $_SERVER['REMOTE_ADDR'];
}

/*
function get_check_requeridos($TIPO_CREDITO,$TIPO_SOLICITUD)
{
 global $db;
 global $_POST;
 
	$SQL_CONS="SELECT 
					   cat_tipo_credito_campos.ID_campo                 AS ID_CMP,
					   cat_tipo_credito_campos.Orden					AS ORDN,
					   cat_tipo_credito_campos.ID_seccion     			AS ID_SECC,
					   cat_tipo_credito_campos.Nombre_campo				AS NMB_CMP
				FROM
					cat_tipo_credito_campos
				LEFT JOIN cat_tipo_credito_secciones ON cat_tipo_credito_campos.ID_seccion = cat_tipo_credito_secciones.ID_seccion
														AND cat_tipo_credito_campos.ID_seccion IS NOT NULL
				WHERE cat_tipo_credito_campos.ID_Tipocredito 	   		= '".$TIPO_CREDITO."'
						AND cat_tipo_credito_campos.ID_Tipo_regimen  	= '".$TIPO_SOLICITUD."'
						AND cat_tipo_credito_campos.Visibilidad 		= 'Y'
						AND cat_tipo_credito_campos.Obligatorio 		= 'Y'
				ORDER BY ID_SECC,ORDN ";
	$rs_cons=$db->Execute($SQL_CONS);

		$REQUERIDO = 'TRUE';
		
		 while(! $rs_cons->EOF )
			 {
				$CAMPO_VALUE = trim($_POST["".$rs_cons->fields['NMB_CMP'].""]);
				if( empty( $CAMPO_VALUE ) )
					$REQUERIDO = 'FALSE';

				$rs_cons->MoveNext();
			  }

	return $REQUERIDO;
}
*/
##############################################

function get_num_cte($ID_SOLICITUD)
{
	global $db;

	$SQL_CONS="SELECT
					clientes_datos.Num_cliente		AS NUM_CTE
				FROM	
					clientes_datos
				INNER JOIN solicitud ON clientes_datos.ID_Solicitud = solicitud.ID_Solicitud
											AND  solicitud.ID_Solicitud = '".$ID_SOLICITUD."' ";
	$rs_cons=$db->Execute($SQL_CONS);

	return $rs_cons->fields["NUM_CTE"];
}

function get_max_fact_cte($NUM_CTE)
{
	global $db;

	$SQL_CONS="SELECT
						MAX(id_factura) AS MAX_FACT
				FROM
						fact_cliente
				WHERE
						num_cliente = '".$NUM_CTE."' ";
	$rs_cons=$db->Execute($SQL_CONS);

	return $rs_cons->fields["MAX_FACT"];
}

function set_update_registro_empresa($ID_SOLICITUD,$MAX_FACT_CTE)
{
	global $db;
	global $ID_USR;
	
        $SQL_INS="INSERT INTO credito_empresa_convenio_edicion
				(id_factura,ID_Solicitud,ID_Producto,ID_empresa,Beneficiario,Fecha_expedicion,Responsable_alta,Comentarios_alta)
				SELECT id_factura,ID_Solicitud,ID_Producto,ID_empresa,Beneficiario,Fecha_expedicion,Responsable_alta,Comentarios_alta 
					FROM credito_empresa_convenio 
					WHERE id_factura = '".$MAX_FACT_CTE."' ";
        $db->Execute($SQL_INS);
    
        $SQL_USR ="SELECT
						CONCAT(Nombre,' ',AP_Paterno,' ',AP_Materno) AS Respon
					FROM usuarios
					WHERE ID_User= '".$ID_USR."' ";
        $rs_usr = $db->Execute($SQL_USR);
        $Responsable=$rs_usr->fields["Respon"];
    
        $SQL_UPDATE="UPDATE credito_empresa_convenio_edicion 
                 SET Comentarios_baja='EDICIÓN DE SOLICITUD',Fecha_baja=NOW(),Responsable_baja='".$Responsable."' 
                 WHERE id_factura = '".$MAX_FACT_CTE."' ";
        $db->Execute($SQL_UPDATE);

		$SQL_CONS_EMP="SELECT
							ID_empresa		AS ID_EMP
								FROM
									solicitud
									WHERE ID_Solicitud ='".$ID_SOLICITUD."' ";
		$rs_empr = $db->Execute($SQL_CONS_EMP);
    
        $SQL_UPDATE="UPDATE
							credito_empresa_convenio
					SET
							ID_empresa 	 = '".$rs_empr->fields["ID_EMP"]."'
					WHERE
							id_factura 	 = '".$MAX_FACT_CTE."'
						AND ID_Solicitud = '".$ID_SOLICITUD."' ";
        $db->Execute($SQL_UPDATE);

        $SQL_UPDATE="UPDATE
							clientes_datos
					SET
							ID_empresa 	 = '".$rs_empr->fields["ID_EMP"]."'
					WHERE
							 ID_Solicitud = '".$ID_SOLICITUD."' ";
        $db->Execute($SQL_UPDATE);
         
}

function set_update_empresa($ID_SOLICITUD)
{
	global $db;

	$NUM_CTE 		=	get_num_cte($ID_SOLICITUD);
	$MAX_FACT_CTE	=	get_max_fact_cte($NUM_CTE);
	
	set_update_registro_empresa($ID_SOLICITUD,$MAX_FACT_CTE);

}
function get_tipo_regimen($ID_Tipo_solicitud)
{
  global $db;

  				$sql_cons="SELECT
									ID_Regimen	AS REG
							FROM
									cat_tipo_credito_regimen
							WHERE ID_Tipo_regimen = '".$ID_Tipo_solicitud."' ";
				$rs_cons=$db->Execute($sql_cons);

	return $rs_cons->fields["REG"];
}

function obtener_campos_tabla($ID_Tipocredito,$ID_Tipo_solicitud)
{
	global $db;
	$Tabla_dest = ($ID_Tipocredito < 4)?('solicitud'):('solicitud_pmoral');
   
		 $sql_cons="SELECT Nombre_campo AS NOMB_SOLI,
						   Nombre_tabla AS NOMB_TABLA
					FROM cat_tipo_credito_campos
					WHERE cat_tipo_credito_campos.ID_Tipocredito		= '".$ID_Tipocredito."'
						AND cat_tipo_credito_campos.ID_Tipo_regimen		= '".$ID_Tipo_solicitud."'
					    AND cat_tipo_credito_campos.Tabla_destino 		= '".$Tabla_dest."'
						AND cat_tipo_credito_campos.Nombre_tabla 		IS NOT NULL
					ORDER BY ID_seccion,Orden";
		 $rs_cons=$db->Execute($sql_cons);

		 while(! $rs_cons->EOF )
			 {
				  $campo[$rs_cons->fields["NOMB_SOLI"]]=$rs_cons->fields["NOMB_TABLA"];
				  $rs_cons->MoveNext();
			  }
      
return $campo;
}

function obtener_campos_tabla_especiales($ID_Tipocredito,$ID_Tipo_solicitud)
{
	global $db;
   
		 $sql_cons="SELECT Nombre_campo AS NOMB_SOLI,
						   Nombre_tabla AS NOMB_TABLA,
						   ID_campo		AS ID_CMP,
						   Tipo_dato	AS TIPO_DATO
					FROM cat_tipo_credito_campos
					WHERE cat_tipo_credito_campos.ID_Tipocredito		= '".$ID_Tipocredito."'
						AND cat_tipo_credito_campos.ID_Tipo_regimen		= '".$ID_Tipo_solicitud."'
					    AND cat_tipo_credito_campos.Tabla_destino 		= 'solicitud_campos_especiales'
						AND cat_tipo_credito_campos.Nombre_tabla 		IS NOT NULL
					ORDER BY ID_seccion,Orden";
		 $rs_cons=$db->Execute($sql_cons);

		 while(! $rs_cons->EOF )
			 {
				   $campo[$rs_cons->fields["NOMB_SOLI"]]['NMB_TBL']	=	$rs_cons->fields["NOMB_TABLA"];
				   $campo[$rs_cons->fields["NOMB_SOLI"]]['ID_CMP'] 	=	$rs_cons->fields["ID_CMP"];
				   $campo[$rs_cons->fields["NOMB_SOLI"]]['TIPO_DATO'] 	=	$rs_cons->fields["TIPO_DATO"];
				   
				  $rs_cons->MoveNext();
			  }
      
return $campo;
}

function get_campo_especial_exist($ID_CMP,$ID_SOLICITUD,$ID_TIPO_SOLI)
{
	global $db;

		$Sql_cons="SELECT
						COUNT(ID_campo_especial) AS CONT_ID
					FROM 
						solicitud_campos_especiales
					WHERE ID_campo ='".$ID_CMP."'
						AND ID_Solicitud ='".$ID_SOLICITUD."'
						AND ID_Tipo_regimen = '".$ID_TIPO_SOLI."' ";
		$rs_cons=$db->Execute($Sql_cons);

		$RESULT=($rs_cons->fields["CONT_ID"] > 0 )?('TRUE'):('FALSE');
		
		return $RESULT;
		
}

function obtener_campos_tabla_referencias($ID_Tipocredito,$Tabla_dest,$Num_ref,$Ref_principal)
{
	global $db;

		 $sql_cons="SELECT Nombre_campo AS NOMB_SOLI,
						   Nombre_tabla AS NOMB_TABLA
					FROM cat_tipo_credito_campos
					WHERE cat_tipo_credito_campos.ID_Tipocredito='".$ID_Tipocredito."'
					    AND cat_tipo_credito_campos.Tabla_destino ='".$Tabla_dest."'
						AND cat_tipo_credito_campos.Nombre_tabla IS NOT NULL
						#AND cat_tipo_credito_campos.Nombre_campo LIKE '%".$Num_ref."%'
						AND cat_tipo_credito_campos.Visibilidad ='Y'
					ORDER BY ID_seccion,Orden";
		 $rs_cons=$db->Execute($sql_cons);

		 while(! $rs_cons->EOF )
			 {
				  $Nombre_campo = str_replace($Ref_principal,$Num_ref,$rs_cons->fields["NOMB_SOLI"]);
				  $campo[$Nombre_campo]=$rs_cons->fields["NOMB_TABLA"];
				  $rs_cons->MoveNext();
			  }

return $campo;
}


function get_referencia_solicitud($ID_SOLICITUD,$ORDEN,$TABLE_INS,$TABLE_REF,$ID_TABLE)
{
 	global $db;
    $REFERENCIA="";

	$Sql_cons="SELECT ".$ID_TABLE." AS ID_REF
	           FROM ".$TABLE_REF."
	           WHERE ID_Solicitud ='".$ID_SOLICITUD."' ";
	$rs_cons=$db->Execute($Sql_cons);

	while(! $rs_cons->EOF )
		{
			$ID_REF=$rs_cons->fields["ID_REF"];

			if(!empty($ID_REF))
				{
					$Sql_cont="SELECT COUNT(".$ID_TABLE.") AS CUANTOS
							   FROM ".$TABLE_INS."
							   WHERE ".$ID_TABLE." ='".$ID_REF."'
									AND Orden ='".$ORDEN."' ";
					$rs_cont=$db->Execute($Sql_cont);

					if(!empty($rs_cont->fields["CUANTOS"]) && $rs_cont->fields["CUANTOS"]  > 0)
						$REFERENCIA=$ID_REF;
					
				}//FIN IF

			$rs_cons->MoveNext();
		}//FIN WHILE
		
  return $REFERENCIA;
}

function get_status_valido_update($ID_SOLICITUD,$ID_Tipocredito)
{
	global $db;

	   if($ID_Tipocredito != 2)
		{
					 $TABLA_DEST = ($ID_Tipocredito < 4)?('solicitud'):('solicitud_pmoral');
					 
					$Sql_status="SELECT
										Status_solicitud		AS STAT
									FROM ".$TABLA_DEST."
									WHERE ID_Solicitud ='".$ID_SOLICITUD."' ";
					$rs_stat=$db->Execute($Sql_status);
					
					$STATUS_SOLI = $rs_stat->fields["STAT"];
					
					 if ( ($STATUS_SOLI != 'DISPOSICION - CREDITO') && ($STATUS_SOLI != 'IMPRESION/DIGITALIZACION') && ($STATUS_SOLI != 'ALTA CREDITO') )
						$STATUS_SOLI = "TRUE";
					else
						$STATUS_SOLI = "FALSE";

					

					//INVESTIGAR SUCESOS
					$TABLA_DEST = ($ID_Tipocredito < 4)?('solicitud_sucesos'):('solicitud_pmoral_sucesos');
					$SQL_COMIT="SELECT
										COUNT(Status)			AS STAT
									FROM ".$TABLA_DEST."
									WHERE ID_Solicitud ='".$ID_SOLICITUD."'
										AND Status = 'COMITE APROBADO' ";
					$rs_comit=$db->Execute($SQL_COMIT);
					
					
					 if ( $rs_comit->fields["STAT"] > 0 )
						$STATUS_SOLI = "FALSE";
						
						
		}
		else
		{
				$SQL_CONS="SELECT
								ID_grupo_soli		AS GPO_SOLI
							FROM solicitud
							WHERE ID_Solicitud ='".$ID_SOLICITUD."' ";
				$rs_gpo=$db->Execute($SQL_CONS);
					
				$SQL_GPO="SELECT
									Status_grupo	as ESTAT
							FROM
									grupo_solidario
							WHERE 
									ID_grupo_soli ='".$rs_gpo->fields["GPO_SOLI"]."' ";
				$rs_gpo=$db->Execute($SQL_GPO);

				if( ($rs_gpo->fields["ESTAT"] == 'PROCESO INTEGRACION') || ($rs_gpo->fields["ESTAT"] == 'PROCESO INTEGRACION - RENOVACION') )
					$STATUS_SOLI = "TRUE";
				else
					$STATUS_SOLI = "FALSE";
		}
		
		return $STATUS_SOLI;
}

function set_update_clientes_datos($ID_SOLICITUD,$ID_Tipocredito,$ID_Tipo_solicitud)
{
	global $db;


   if($ID_Tipocredito < 4)
   {
						$SQL_CHECK="SELECT
											COUNT(ID_Cliente_datos) AS CUANTOS
										FROM
												clientes_datos
										WHERE
												ID_Solicitud = '".$ID_SOLICITUD."'   ";
						$rs_result = $db->Execute($SQL_CHECK);

						if($rs_result->fields["CUANTOS"] == 1)
						{
											$STATUS_SOLI = get_status_valido_update($ID_SOLICITUD,$ID_Tipocredito);
											
											$STR_CREDIT=( $STATUS_SOLI == 'TRUE' )?("

																clientes_datos.Monto						 		= solicitud.Monto,
																clientes_datos.Plazo						 		= solicitud.Plazo,
																clientes_datos.ID_Producto				 			= solicitud.ID_Producto,
																clientes_datos.ID_empresa				 			= solicitud.ID_empresa,"):("");

											$SQL_UPD="UPDATE clientes_datos,solicitud
															SET 
																clientes_datos.ID_referencia_externa 				= solicitud.ID_referencia_externa,
																clientes_datos.ID_Promotor				 			= solicitud.ID_Promotor,
																clientes_datos.ID_Sucursal				 			= solicitud.ID_Sucursal,
																clientes_datos.ID_grupo_soli						= solicitud.ID_grupo_soli,
																clientes_datos.ID_Tipocredito			 			= solicitud.ID_Tipocredito,
																clientes_datos.ID_User					 			= solicitud.ID_User,
																clientes_datos.Nomina					 			= solicitud.Nomina,
																".$STR_CREDIT."
																clientes_datos.Nombre					 			= solicitud.Nombre,
																clientes_datos.NombreI					 			= solicitud.NombreI,
																clientes_datos.Ap_paterno				 			= solicitud.Ap_paterno,
																clientes_datos.Ap_materno				 			= solicitud.Ap_materno,
																clientes_datos.Fecha_nacimiento		 				= solicitud.Fecha_nacimiento,
																clientes_datos.SEXO						 			= solicitud.SEXO,
																clientes_datos.RFC						 			= solicitud.RFC,
																clientes_datos.Hclave					 			= solicitud.Hclave,
																clientes_datos.Telefono					 			= solicitud.Telefono,
																clientes_datos.Tipo_telefono			 			= solicitud.Tipo_telefono,
																clientes_datos.TelOf						 		= solicitud.Tel_contacto,
																clientes_datos.Num_celular							= solicitud.Num_celular,
																clientes_datos.CP							 		= solicitud.CP,
																clientes_datos.Colonia					 			= solicitud.Colonia,
																clientes_datos.Estado					 			= solicitud.Estado,
																clientes_datos.Ciudad					 			= solicitud.Ciudad,
																clientes_datos.Poblacion				 			= solicitud.Poblacion,
																clientes_datos.Calle						 		= solicitud.Calle,
																clientes_datos.Num						 			= solicitud.Numero,
																clientes_datos.Interior					 			= solicitud.Interior,
																clientes_datos.Ecalles					 			= solicitud.Ecalles,
																clientes_datos.EcallesII				 			= solicitud.EcallesII,
																clientes_datos.Empresa_soli			 				= solicitud.Empresa_soli,
																clientes_datos.Direc_empresa_soli    				= solicitud.Direcc_empresa,
																clientes_datos.Telefono_empresa		 				= solicitud.Telefono_empresa,
																clientes_datos.Extension_tel			 			= solicitud.Extension,
																clientes_datos.Puesto					 			= solicitud.Puesto,
																clientes_datos.Jefe_soli				 			= solicitud.Jefe_soli,
																clientes_datos.Num_nomina				 			= solicitud.Num_nomina,
																clientes_datos.Num_empleado			 				= solicitud.Num_empleado,
																clientes_datos.Telefono_complement	 				= solicitud.Telefono_anterior,
																clientes_datos.Telefono_casa_conyuge 				= solicitud.Telefono_casa_conyuge,
																clientes_datos.Nombre_conyuge   	    			= solicitud.Nombre_conyuge,
																clientes_datos.Ap_paterno_conyuge	 				= solicitud.Ap_paterno_conyuge,
																clientes_datos.Telefono_contacto_conyuge			= solicitud.Telefono_contacto_conyuge,
																clientes_datos.Folio								= solicitud.Folio
															WHERE clientes_datos.ID_Solicitud = solicitud.ID_Solicitud
																AND solicitud.ID_Solicitud ='".$ID_SOLICITUD."' ";
											$db->Execute($SQL_UPD);
							}
		}
		else
		{
						$SQL_CHECK="SELECT
											COUNT(ID_Cliente_datos) AS CUANTOS
										FROM
												clientes_datos_pmoral
										WHERE
												ID_Solicitud = '".$ID_SOLICITUD."'   ";
						$rs_result = $db->Execute($SQL_CHECK);
						
						if($rs_result->fields["CUANTOS"] == 1)
						{
											$STR_CREDIT=($STATUS_SOLI != 'DISPOSICION - CREDITO' && $STATUS_SOLI != 'IMPRESION/DIGITALIZACION' && $STATUS_SOLI != 'ALTA CREDITO' )?("

																clientes_datos_pmoral.Linea_factoraje		 		= solicitud_pmoral.Linea_factoraje,"):("");

											$REGIMEN = get_tipo_regimen($ID_Tipo_solicitud);
											$STR_PFAE=($REGIMEN == 'PFAE' )?("
																clientes_datos_pmoral.Nombre_pfae		 		= solicitud_pmoral.Nombre_pfae,
																clientes_datos_pmoral.NombreI_pfae		 		= solicitud_pmoral.NombreI_pfae,
																clientes_datos_pmoral.Ap_paterno_pfae	 		= solicitud_pmoral.Ap_paterno_pfae,
																clientes_datos_pmoral.Ap_materno_pfae	 		= solicitud_pmoral.Ap_materno_pfae,
																clientes_datos_pmoral.Fecha_nacimiento_pfae		= solicitud_pmoral.Fecha_nacimiento_pfae,
																clientes_datos_pmoral.SEXO_pfae		 			= solicitud_pmoral.SEXO_pfae,
																clientes_datos_pmoral.RFC_pfae			 		= solicitud_pmoral.RFC_pfae,
																clientes_datos_pmoral.Hclave_pfae		 		= solicitud_pmoral.Hclave_pfae,"):("");
																
											$SQL_UPD="UPDATE clientes_datos_pmoral,solicitud_pmoral
															SET 
																				clientes_datos_pmoral.ID_Referencia_Externa				=	solicitud_pmoral.ID_Referencia_Externa,
																				clientes_datos_pmoral.ID_Promotor						=	solicitud_pmoral.ID_Promotor,
																				clientes_datos_pmoral.ID_Sucursal						=	solicitud_pmoral.ID_Sucursal,
																				clientes_datos_pmoral.ID_pais							=	solicitud_pmoral.ID_pais,
																				clientes_datos_pmoral.Regimen							=	solicitud_pmoral.Regimen,
																				".$STR_CREDIT."
																				".$STR_PFAE."
																				clientes_datos_pmoral.Razon_social						=	solicitud_pmoral.Razon_social,
																				clientes_datos_pmoral.Fecha_constitucion				=	solicitud_pmoral.Fecha_constitucion,
																				clientes_datos_pmoral.Linea_credito						=	solicitud_pmoral.Linea_credito,
																				clientes_datos_pmoral.RFC								=	solicitud_pmoral.RFC,
																				clientes_datos_pmoral.Email								=	solicitud_pmoral.Email,
																				clientes_datos_pmoral.CP								=	solicitud_pmoral.CP,
																				clientes_datos_pmoral.Colonia							=	solicitud_pmoral.Colonia,
																				clientes_datos_pmoral.Estado							=	solicitud_pmoral.Estado,
																				clientes_datos_pmoral.Ciudad							=	solicitud_pmoral.Ciudad,
																				clientes_datos_pmoral.Poblacion							=	solicitud_pmoral.Poblacion,
																				clientes_datos_pmoral.Calle								=	solicitud_pmoral.Calle,
																				clientes_datos_pmoral.Numero							=	solicitud_pmoral.Numero,
																				clientes_datos_pmoral.Interior							=	solicitud_pmoral.Interior,
																				clientes_datos_pmoral.Ecalles							=	solicitud_pmoral.Ecalles,
																				clientes_datos_pmoral.EcallesII							=	solicitud_pmoral.EcallesII,
																				clientes_datos_pmoral.Telefono							=	solicitud_pmoral.Telefono,
																				clientes_datos_pmoral.FIEL								=	solicitud_pmoral.FIEL,
																				clientes_datos_pmoral.Nacionalidad_soli					=	solicitud_pmoral.Nacionalidad_soli,
																				clientes_datos_pmoral.Objeto							=	solicitud_pmoral.Objeto,
																				clientes_datos_pmoral.Nombre							=	solicitud_pmoral.Nombre,
																				clientes_datos_pmoral.NombreI							=	solicitud_pmoral.NombreI,
																				clientes_datos_pmoral.Ap_paterno						=	solicitud_pmoral.Ap_materno,
																				clientes_datos_pmoral.Ap_materno						=	solicitud_pmoral.Ap_materno,
																				clientes_datos_pmoral.Aforo								=	solicitud_pmoral.Aforo
																				
															WHERE clientes_datos_pmoral.ID_Solicitud = solicitud_pmoral.ID_Solicitud
																AND solicitud_pmoral.ID_Solicitud ='".$ID_SOLICITUD."' ";
											$db->Execute($SQL_UPD);

						}
		}

}

function set_insert_prev_lavado_dinero($ID_SOLICITUD,$ID_Tipocredito,$ID_Tipo_solicitud)
{
	global $db;

	   if($ID_Tipocredito < 4)
	   {
			$Sql_select_insert="INSERT INTO solicitud_plvd (ID_Solicitud,ID_Ocupacion,ID_actividad_economica,ID_pais,ID_pais_extrj,Nombre,NombreI,Ap_paterno,Ap_materno,Fecha_nacimiento,RFC,Hclave,CURP,Nacionalidad,Profesion,CP,Colonia,Estado,Ciudad,Poblacion,Calle,Num,Interior,Ecalles,EcallesII,Telefono,Email,Num_FIEL,CP_extrj,Colonia_extrj,Estado_extrj,Ciudad_extrj,Poblacion_extrj,Calle_extrj,Num_extrj,Interior_extrj,Ecalles_extrj,EcallesII_extrj,Grado_riesgo,Observaciones_plvd,Puesto_politico,Puesto_publico_extrj,Dtl_puesto_publico_extrj,Edo_nacimiento,Num_oper_ventas,Efectivo_mnd_nac,Efectivo_mnd_extrj,Tarjeta_credito,Cheque_transferencia,ID_Giro_negocio,Origen_recursos,Ingresos_netos)

			SELECT ID_Solicitud,ID_Ocupacion,Actividad_soli,ID_pais,ID_pais_extrj,Nombre,NombreI,Ap_paterno,Ap_materno,Fecha_nacimiento,RFC,Hclave,CURP,Nacionalidad_soli,Profesion,CP,Colonia,Estado,Ciudad,Poblacion,Calle,Numero,Interior,Ecalles,EcallesII,Telefono,Email,Num_FIEL,CP_extrj,Colonia_extrj,Estado_extrj,Ciudad_extrj,Poblacion_extrj,Calle_extrj,Numero_extrj,Interior_extrj,Ecalles_extrj,EcallesII_extrj,Grado_riesgo,Observaciones_plvd,Puesto_politico,Puesto_publico_extrj,Dtl_puesto_publico_extrj,Edo_nacimiento,Num_oper_ventas,Efectivo_mnd_nac,Efectivo_mnd_extrj,Tarjeta_credito,Cheque_transferencia,ID_Giro_negocio,Origen_recursos,Ingresos_netos
			FROM solicitud
			WHERE ID_Solicitud ='".$ID_SOLICITUD."' ";
			$db->Execute($Sql_select_insert);
		}
		else
		{

				$Sql_select_insert="INSERT INTO solicitud_pmoral_plvd (ID_Solicitud,ID_Ocupacion,ID_pais,ID_pais_extrj,Razon_social,Fecha_constitucion,Nombre_pfae,NombreI_pfae,Ap_paterno_pfae,Ap_materno_pfae,Fecha_nacimiento_pfae,RFC_pfae,Hclave_pfae,CURP,Nacionalidad,Profesion,CP,Colonia,Estado,Ciudad,Poblacion,Calle,Num,Interior,Ecalles,EcallesII,Telefono,Email,Num_FIEL,CP_extrj,Colonia_extrj,Estado_extrj,Ciudad_extrj,Poblacion_extrj,Calle_extrj,Num_extrj,Interior_extrj,Ecalles_extrj,EcallesII_extrj,Grado_riesgo,Observaciones_plvd,Puesto_politico,Puesto_publico_extrj,Dtl_puesto_publico_extrj,Edo_nacimiento,Num_oper_ventas,Efectivo_mnd_nac,Efectivo_mnd_extrj,Tarjeta_credito,Cheque_transferencia,ID_Giro_negocio,Origen_recursos,Ingresos_netos)

				SELECT ID_Solicitud,ID_Ocupacion,ID_pais,ID_pais_extrj,Razon_social,Fecha_constitucion,Nombre_pfae,NombreI_pfae,Ap_paterno_pfae,Ap_materno_pfae,Fecha_nacimiento_pfae,RFC_pfae,Hclave_pfae,CURP,Nacionalidad_soli,Profesion,CP,Colonia,Estado,Ciudad,Poblacion,Calle,Numero,Interior,Ecalles,EcallesII,Telefono,Email,Num_FIEL,CP_extrj,Colonia_extrj,Estado_extrj,Ciudad_extrj,Poblacion_extrj,Calle_extrj,Numero_extrj,Interior_extrj,Ecalles_extrj,EcallesII_extrj,Grado_riesgo,Observaciones_plvd,Puesto_politico,Puesto_publico_extrj,Dtl_puesto_publico_extrj,Edo_nacimiento,Num_oper_ventas,Efectivo_mnd_nac,Efectivo_mnd_extrj,Tarjeta_credito,Cheque_transferencia,ID_Giro_negocio,Origen_recursos,Ingresos_netos
				FROM solicitud_pmoral
				WHERE ID_Solicitud ='".$ID_SOLICITUD."' ";
				$db->Execute($Sql_select_insert);

		}


}

function set_update_prev_lavado_dinero($ID_SOLICITUD,$ID_Tipocredito)
{
	global $db;

	   if($ID_Tipocredito < 4)
	   {
											$SQL_UPD="UPDATE solicitud_plvd,solicitud
															SET 
																solicitud_plvd.ID_Ocupacion 						= solicitud.ID_Ocupacion,
																solicitud_plvd.ID_actividad_economica				= solicitud.Actividad_soli,
																solicitud_plvd.ID_pais				 				= solicitud.ID_pais,
																solicitud_plvd.ID_pais_extrj						= solicitud.ID_pais_extrj,
																solicitud_plvd.Nombre			 					= solicitud.Nombre,
																solicitud_plvd.NombreI					 			= solicitud.NombreI,
																solicitud_plvd.Ap_paterno				 			= solicitud.Ap_paterno,
																solicitud_plvd.Ap_materno				 			= solicitud.Ap_materno,
																solicitud_plvd.Fecha_nacimiento		 				= solicitud.Fecha_nacimiento,
																solicitud_plvd.RFC						 			= solicitud.RFC,
																solicitud_plvd.Hclave						 		= solicitud.Hclave,
																solicitud_plvd.CURP					 				= solicitud.CURP,
																solicitud_plvd.Nacionalidad					 		= solicitud.Nacionalidad_soli,
																solicitud_plvd.Profesion			 				= solicitud.Profesion,
																solicitud_plvd.CP						 			= solicitud.CP,
																solicitud_plvd.Colonia								= solicitud.Colonia,
																solicitud_plvd.Estado							 	= solicitud.Estado,
																solicitud_plvd.Ciudad					 			= solicitud.Ciudad,
																solicitud_plvd.Poblacion					 		= solicitud.Poblacion,
																solicitud_plvd.Calle					 			= solicitud.Calle,
																solicitud_plvd.Num				 					= solicitud.Numero,
																solicitud_plvd.Interior						 		= solicitud.Interior,
																solicitud_plvd.Ecalles					 			= solicitud.Ecalles,
																solicitud_plvd.EcallesII				 			= solicitud.EcallesII,
																solicitud_plvd.Telefono			 					= solicitud.Telefono,
																solicitud_plvd.Email    							= solicitud.Email,
																solicitud_plvd.Num_FIEL		 						= solicitud.Num_FIEL,
																solicitud_plvd.CP_extrj			 					= solicitud.CP_extrj,
																solicitud_plvd.Colonia_extrj					 	= solicitud.Colonia_extrj,
																solicitud_plvd.Estado_extrj				 			= solicitud.Estado_extrj,
																solicitud_plvd.Ciudad_extrj						 	= solicitud.Ciudad_extrj,
																solicitud_plvd.Poblacion_extrj						= solicitud.Poblacion_extrj,
																solicitud_plvd.Calle_extrj					 		= solicitud.Calle_extrj,
																solicitud_plvd.Num_extrj					 		= solicitud.Numero_extrj,
																solicitud_plvd.Interior_extrj				 		= solicitud.Interior_extrj,
																solicitud_plvd.Ecalles_extrj			 			= solicitud.Ecalles_extrj,
																solicitud_plvd.EcallesII_extrj    					= solicitud.EcallesII_extrj,
																solicitud_plvd.Grado_riesgo		 					= solicitud.Grado_riesgo,
																solicitud_plvd.Observaciones_plvd			 		= solicitud.Observaciones_plvd,
																solicitud_plvd.Puesto_politico					 	= solicitud.Puesto_politico,
																solicitud_plvd.Puesto_publico_extrj					= solicitud.Puesto_publico_extrj,
																solicitud_plvd.Dtl_puesto_publico_extrj				= solicitud.Dtl_puesto_publico_extrj,
																solicitud_plvd.Edo_nacimiento					 	= solicitud.Edo_nacimiento,
																solicitud_plvd.Num_oper_ventas					 	= solicitud.Num_oper_ventas,
																solicitud_plvd.Efectivo_mnd_nac					 	= solicitud.Efectivo_mnd_nac,
																solicitud_plvd.Efectivo_mnd_extrj					= solicitud.Efectivo_mnd_extrj,
																solicitud_plvd.Tarjeta_credito					 	= solicitud.Tarjeta_credito,
																solicitud_plvd.Cheque_transferencia					= solicitud.Cheque_transferencia,
																solicitud_plvd.ID_Giro_negocio					 	= solicitud.ID_Giro_negocio,
																solicitud_plvd.Origen_recursos					 	= solicitud.Origen_recursos,
																solicitud_plvd.Ingresos_netos					 	= solicitud.Ingresos_netos
															WHERE solicitud_plvd.ID_Solicitud = solicitud.ID_Solicitud
																AND solicitud.ID_Solicitud ='".$ID_SOLICITUD."' ";
											$db->Execute($SQL_UPD);
	   }
	   else
	   {
											$SQL_UPD="UPDATE solicitud_pmoral_plvd,solicitud_pmoral
															SET 
																solicitud_pmoral_plvd.ID_Ocupacion 							= solicitud_pmoral.ID_Ocupacion,
																solicitud_pmoral_plvd.ID_pais				 				= solicitud_pmoral.ID_pais,
																solicitud_pmoral_plvd.ID_pais_extrj							= solicitud_pmoral.ID_pais_extrj,
																solicitud_pmoral_plvd.Razon_social							= solicitud_pmoral.Razon_social,
																solicitud_pmoral_plvd.Fecha_constitucion					= solicitud_pmoral.Fecha_constitucion,
																solicitud_pmoral_plvd.Nombre_pfae			 				= solicitud_pmoral.Nombre_pfae,
																solicitud_pmoral_plvd.NombreI_pfae					 		= solicitud_pmoral.NombreI_pfae,
																solicitud_pmoral_plvd.Ap_paterno_pfae				 		= solicitud_pmoral.Ap_paterno_pfae,
																solicitud_pmoral_plvd.Ap_materno_pfae				 		= solicitud_pmoral.Ap_materno_pfae,
																solicitud_pmoral_plvd.Fecha_nacimiento_pfae		 			= solicitud_pmoral.Fecha_nacimiento_pfae,
																solicitud_pmoral_plvd.RFC_pfae						 		= solicitud_pmoral.RFC_pfae,
																solicitud_pmoral_plvd.Hclave_pfae						 	= solicitud_pmoral.Hclave_pfae,
																solicitud_pmoral_plvd.CURP					 				= solicitud_pmoral.CURP,
																solicitud_pmoral_plvd.Nacionalidad					 		= solicitud_pmoral.Nacionalidad_soli,
																solicitud_pmoral_plvd.Profesion			 					= solicitud_pmoral.Profesion,
																solicitud_pmoral_plvd.CP						 			= solicitud_pmoral.CP,
																solicitud_pmoral_plvd.Colonia								= solicitud_pmoral.Colonia,
																solicitud_pmoral_plvd.Estado							 	= solicitud_pmoral.Estado,
																solicitud_pmoral_plvd.Ciudad					 			= solicitud_pmoral.Ciudad,
																solicitud_pmoral_plvd.Poblacion					 			= solicitud_pmoral.Poblacion,
																solicitud_pmoral_plvd.Calle					 				= solicitud_pmoral.Calle,
																solicitud_pmoral_plvd.Num				 					= solicitud_pmoral.Numero,
																solicitud_pmoral_plvd.Interior						 		= solicitud_pmoral.Interior,
																solicitud_pmoral_plvd.Ecalles					 			= solicitud_pmoral.Ecalles,
																solicitud_pmoral_plvd.EcallesII				 				= solicitud_pmoral.EcallesII,
																solicitud_pmoral_plvd.Telefono			 					= solicitud_pmoral.Telefono,
																solicitud_pmoral_plvd.Email    								= solicitud_pmoral.Email,
																solicitud_pmoral_plvd.Num_FIEL		 						= solicitud_pmoral.Num_FIEL,
																solicitud_pmoral_plvd.CP_extrj			 					= solicitud_pmoral.CP_extrj,
																solicitud_pmoral_plvd.Colonia_extrj					 		= solicitud_pmoral.Colonia_extrj,
																solicitud_pmoral_plvd.Estado_extrj				 			= solicitud_pmoral.Estado_extrj,
																solicitud_pmoral_plvd.Ciudad_extrj						 	= solicitud_pmoral.Ciudad_extrj,
																solicitud_pmoral_plvd.Poblacion_extrj						= solicitud_pmoral.Poblacion_extrj,
																solicitud_pmoral_plvd.Calle_extrj					 		= solicitud_pmoral.Calle_extrj,
																solicitud_pmoral_plvd.Num_extrj					 			= solicitud_pmoral.Numero_extrj,
																solicitud_pmoral_plvd.Interior_extrj				 		= solicitud_pmoral.Interior_extrj,
																solicitud_pmoral_plvd.Ecalles_extrj			 				= solicitud_pmoral.Ecalles_extrj,
																solicitud_pmoral_plvd.EcallesII_extrj    					= solicitud_pmoral.EcallesII_extrj,
																solicitud_pmoral_plvd.Grado_riesgo		 					= solicitud_pmoral.Grado_riesgo,
																solicitud_pmoral_plvd.Observaciones_plvd			 		= solicitud_pmoral.Observaciones_plvd,
																solicitud_pmoral_plvd.Puesto_politico					 	= solicitud_pmoral.Puesto_politico,
																solicitud_pmoral_plvd.Puesto_publico_extrj					= solicitud_pmoral.Puesto_publico_extrj,
																solicitud_pmoral_plvd.Dtl_puesto_publico_extrj				= solicitud_pmoral.Dtl_puesto_publico_extrj,
																solicitud_pmoral_plvd.Edo_nacimiento					 	= solicitud_pmoral.Edo_nacimiento,
																solicitud_pmoral_plvd.Num_oper_ventas					 	= solicitud_pmoral.Num_oper_ventas,
																solicitud_pmoral_plvd.Efectivo_mnd_nac					 	= solicitud_pmoral.Efectivo_mnd_nac,
																solicitud_pmoral_plvd.Efectivo_mnd_extrj					= solicitud_pmoral.Efectivo_mnd_extrj,
																solicitud_pmoral_plvd.Tarjeta_credito					 	= solicitud_pmoral.Tarjeta_credito,
																solicitud_pmoral_plvd.Cheque_transferencia					= solicitud_pmoral.Cheque_transferencia,
																solicitud_pmoral_plvd.ID_Giro_negocio					 	= solicitud_pmoral.ID_Giro_negocio,
																solicitud_pmoral_plvd.Origen_recursos					 	= solicitud_pmoral.Origen_recursos,
																solicitud_pmoral_plvd.Ingresos_netos					 	= solicitud_pmoral.Ingresos_netos
															WHERE solicitud_pmoral_plvd.ID_Solicitud = solicitud_pmoral.ID_Solicitud
																AND solicitud_pmoral.ID_Solicitud ='".$ID_SOLICITUD."' ";
											$db->Execute($SQL_UPD);


	   }
} 

function set_insert_solicitud_backup($ID_SOLICITUD,$ID_Tipocredito,$EVENTO,$ID_Tipo_solicitud)
{
	global $db;
	
	   if($ID_Tipocredito < 4)
	   {
					$SQL_ID = "SELECT
										ID_Cliente			AS ID,
										Num_cliente			AS NUM_CTE
								FROM
									clientes
								WHERE	ID_Solicitud	= '".$ID_SOLICITUD."' ";
					$rs_id=$db->Execute($SQL_ID);

					$ID_CTE		=	$rs_id->fields["ID"];
					$NUM_CTE	=	$rs_id->fields["NUM_CTE"];
					$IP_CLIENT	=	getRealIP();
					
					$SQL_INS = "INSERT INTO solicitud_backup(	ID_Solicitud,
																ID_grupo_soli,
																ID_Promotor,
																ID_Sucursal,
																ID_Tipocredito,
																ID_Producto,
																ID_empresa,
																ID_User,
																ID_Cliente,
																Num_cliente,
																Monto,
																Plazo,
																Nombre,
																NombreI,
																Ap_paterno,
																Ap_materno,
																Fecha_nacimiento,
																SEXO,
																RFC,
																Hclave,
																Telefono,
																CP,
																Colonia,
																Estado,
																Ciudad,
																Poblacion,
																Calle,
																Num,
																Interior,
																TelOf,
																Empresa_soli,
																Direc_empresa_soli,
																Puesto,
																Telefono_empresa,
																Nomina,
																Num_nomina,
																Num_empleado,
																Telefono_complement,
																Telefono_casa_conyuge,
																Nombre_complement,
																Nombre_conyuge,
																Ap_paterno_conyuge,
																Telefono_contacto_conyuge,
																Num_celular,
																Fecha,
																Evento,
																IP)
														SELECT 	solicitud.ID_Solicitud,
																solicitud.ID_grupo_soli,
																solicitud.ID_Promotor,
																solicitud.ID_Sucursal,
																solicitud.ID_Tipocredito,
																solicitud.ID_Producto,
																solicitud.ID_empresa,
																solicitud.ID_User,
																'".$ID_CTE."',
																'".$NUM_CTE."',
																solicitud.Monto,
																solicitud.Plazo,
																solicitud.Nombre,
																solicitud.NombreI,
																solicitud.Ap_paterno,
																solicitud.Ap_materno,
																solicitud.Fecha_nacimiento,
																solicitud.SEXO,
																solicitud.RFC,
																solicitud.Hclave,
																solicitud.Telefono,
																solicitud.CP,
																solicitud.Colonia,
																solicitud.Estado,
																solicitud.Ciudad,
																solicitud.Poblacion,
																solicitud.Calle,
																solicitud.Numero,
																solicitud.Interior,
																solicitud.Tel_contacto,
																cat_convenio_empresas.Empresa,
																cat_convenio_empresas.Direccion,
																solicitud.Puesto,
																cat_convenio_empresas.Telefono,
																solicitud.Nomina,
																solicitud.Num_nomina,
																solicitud.Num_empleado,
																solicitud.Telefono_complement,
																solicitud.Telefono_casa_conyuge,
																solicitud.Nombre_contacto,
																solicitud.Nombre_conyuge,
																solicitud.Ap_paterno_conyuge,
																solicitud.Telefono_contacto_conyuge,
																solicitud.Num_celular,
																solicitud.Fecha,
																'".$EVENTO."',
																'".$IP_CLIENT."'
														FROM		solicitud
														LEFT JOIN cat_convenio_empresas ON cat_convenio_empresas.ID_empresa = solicitud.ID_empresa
														WHERE		ID_Solicitud = '".$ID_SOLICITUD."' ";
					$db->Execute($SQL_INS);
	   }
	   else
	   {
					$SQL_ID = "SELECT
										ID_Cliente			AS ID,
										Num_cliente			AS NUM_CTE
								FROM
									clientes
								WHERE	ID_Solicitud	= '".$ID_SOLICITUD."' ";
					$rs_id=$db->Execute($SQL_ID);

					$ID_CTE			=	$rs_id->fields["ID"];
					$NUM_CTE		=	$rs_id->fields["NUM_CTE"];
					$IP_CLIENT		=	getRealIP();
					$REGIMEN 		= get_tipo_regimen($ID_Tipo_solicitud);
					
				$SQL_INS = "INSERT INTO solicitud_pmoral_backup(
																ID_Solicitud,
																ID_Cliente,
																Num_cliente,
																Fecha,
																Fecha_sistema,
																ID_Referencia_Externa,
																ID_Promotor,
																ID_Sucursal,
																ID_Tipocredito,
																ID_pais,
																Regimen,
																Razon_social,
																Fecha_constitucion,
																Linea_credito,
																RFC,
																Email,
																CP,
																Colonia,
																Estado,
																Ciudad,
																Poblacion,
																Calle,
																Numero,
																Interior,
																Ecalles,
																EcallesII,
																Telefono,
																FIEL,
																Nacionalidad_soli,
																Objeto,
																Linea_factoraje,
																Nombre,
																NombreI,
																Ap_paterno,
																Ap_materno,
																Aforo,
																Dependencia_gob,
																Nombre_pfae,
																NombreI_pfae,
																Ap_paterno_pfae,
																Ap_materno_pfae,
																Fecha_nacimiento_pfae,
																SEXO_pfae,
																RFC_pfae,
																Hclave_pfae,
																ID_pais_extrj,
																ID_Ocupacion,
																ID_Giro_negocio,
																Profesion,
																Grado_riesgo,
																Observaciones_plvd,
																Efectivo_mnd_nac,
																Efectivo_mnd_extrj,
																Num_FIEL,
																CP_extrj,
																Colonia_extrj,
																Estado_extrj,
																Ciudad_extrj,
																Poblacion_extrj,
																Calle_extrj,
																Numero_extrj,
																Interior_extrj,
																Ecalles_extrj,
																EcallesII_extrj,
																Puesto_publico_extrj,
																Dtl_puesto_publico_extrj,
																Puesto_politico,
																Edo_nacimiento,
																Num_oper_ventas,
																Tarjeta_credito,
																Cheque_transferencia,
																Origen_recursos,
																Ingresos_netos,
																Evento,
																IP)
														SELECT
																solicitud_pmoral.ID_Solicitud,
																'".$ID_CTE."',
																'".$NUM_CTE."',
																solicitud_pmoral.Fecha,
																solicitud_pmoral.Fecha_sistema,
																solicitud_pmoral.ID_referencia_externa,
																solicitud_pmoral.ID_Promotor,
																solicitud_pmoral.ID_Sucursal,
																solicitud_pmoral.ID_Tipocredito,
																solicitud_pmoral.ID_pais,
																'".$REGIMEN."',
																solicitud_pmoral.Razon_social,
																solicitud_pmoral.Fecha_constitucion,
																solicitud_pmoral.Linea_credito,
																solicitud_pmoral.RFC,
																solicitud_pmoral.Email,
																solicitud_pmoral.CP,
																solicitud_pmoral.Colonia,
																solicitud_pmoral.Estado,
																solicitud_pmoral.Ciudad,
																solicitud_pmoral.Poblacion,
																solicitud_pmoral.Calle,
																solicitud_pmoral.Numero,
																solicitud_pmoral.Interior,
																solicitud_pmoral.Ecalles,
																solicitud_pmoral.EcallesII,
																solicitud_pmoral.Telefono,
																solicitud_pmoral.FIEL,
																solicitud_pmoral.Nacionalidad_soli,
																solicitud_pmoral.Objeto,
																solicitud_pmoral.Linea_factoraje,
																solicitud_pmoral.Nombre,
																solicitud_pmoral.NombreI,
																solicitud_pmoral.Ap_paterno,
																solicitud_pmoral.Ap_materno,
																solicitud_pmoral.Aforo,
																solicitud_pmoral.Dependencia_gob,
																solicitud_pmoral.Nombre_pfae,
																solicitud_pmoral.NombreI_pfae,
																solicitud_pmoral.Ap_paterno_pfae,
																solicitud_pmoral.Ap_materno_pfae,
																solicitud_pmoral.Fecha_nacimiento_pfae,
																solicitud_pmoral.SEXO_pfae,
																solicitud_pmoral.RFC_pfae,
																solicitud_pmoral.Hclave_pfae,
																solicitud_pmoral.ID_pais_extrj,
																solicitud_pmoral.ID_Ocupacion,
																solicitud_pmoral.ID_Giro_negocio,
																solicitud_pmoral.Profesion,
																solicitud_pmoral.Grado_riesgo,
																solicitud_pmoral.Observaciones_plvd,
																solicitud_pmoral.Efectivo_mnd_nac,
																solicitud_pmoral.Efectivo_mnd_extrj,
																solicitud_pmoral.Num_FIEL,
																solicitud_pmoral.CP_extrj,
																solicitud_pmoral.Colonia_extrj,
																solicitud_pmoral.Estado_extrj,
																solicitud_pmoral.Ciudad_extrj,
																solicitud_pmoral.Poblacion_extrj,
																solicitud_pmoral.Calle_extrj,
																solicitud_pmoral.Numero_extrj,
																solicitud_pmoral.Interior_extrj,
																solicitud_pmoral.Ecalles_extrj,
																solicitud_pmoral.EcallesII_extrj,
																solicitud_pmoral.Puesto_publico_extrj,
																solicitud_pmoral.Dtl_puesto_publico_extrj,
																solicitud_pmoral.Puesto_politico,
																solicitud_pmoral.Edo_nacimiento,
																solicitud_pmoral.Num_oper_ventas,
																solicitud_pmoral.Tarjeta_credito,
																solicitud_pmoral.Cheque_transferencia,
																solicitud_pmoral.Origen_recursos,
																solicitud_pmoral.Ingresos_netos,
																'".$EVENTO."',
																'".$IP_CLIENT."'
														FROM		solicitud_pmoral
														WHERE		ID_Solicitud = '".$ID_SOLICITUD."' ";
					$db->Execute($SQL_INS);

	   }
}

function get_solicitud_cliente($tbl_cliente,$num_cliente)
{
	global $db;
	
	$SQL_SOLI= "SELECT
						ID_Solicitud AS ID_SOLI
	                   FROM
							".$tbl_cliente."
	                   WHERE
								".$tbl_cliente.".Num_cliente = '".$num_cliente."' ";
	$rs_soli=$db->Execute($SQL_SOLI);

	return $rs_soli->fields["ID_SOLI"];
}

function set_renueva_solicitud($NUM_CLIENTE,$TIPO_CREDITO)
{
		global $db;

		if(!empty($NUM_CLIENTE) && ($NUM_CLIENTE > 0) )
		{
					$TABLA_CTE 		= ($TIPO_CREDITO < 4)?('clientes_datos'):('clientes_datos_pmoral');
					$ID_SOLICITUD   = get_solicitud_cliente($TABLA_CTE,$NUM_CLIENTE);

					$TABLA_RENUEVA  = ($TIPO_CREDITO < 4)?('solicitud_renovadas'):('solicitud_pmoral_renovadas');
					$TABLA_ORIGEN   = ($TIPO_CREDITO < 4)?('solicitud'):('solicitud_pmoral');

				if( !empty($ID_SOLICITUD) && ($ID_SOLICITUD > 0) )
				{
						$SQL_INS= "INSERT INTO ".$TABLA_RENUEVA."
									SELECT *
									FROM ".$TABLA_ORIGEN."
									WHERE ID_Solicitud = '".$ID_SOLICITUD."' ";

						if ($db->Execute($SQL_INS) != false)
						{
								$SQL_DELETE= "DELETE	FROM ".$TABLA_ORIGEN." WHERE	ID_Solicitud = '".$ID_SOLICITUD."' ";
								if ($db->Execute($SQL_DELETE) != false)
								{
									$SQL_UPDATE="UPDATE	".$TABLA_RENUEVA."
														SET Num_cliente = '".$NUM_CLIENTE."'
													WHERE ID_Solicitud = '".$ID_SOLICITUD."' ";
									$db->Execute($SQL_UPDATE);
									
									return "TRUE";
								}
					    }
					    else
							return "FALSE";
				}
				else
					return "FALSE";
		}
		else
			return "FALSE";

}

function set_update_solicitud_renovada($NUM_CLIENTE,$ID_SOLICITUD_NEW,$TIPO_CREDITO,$TIPO_SOLICITUD)
{
	global $db;
		if( !empty($NUM_CLIENTE) && ($NUM_CLIENTE > 0) )
		{
					$TABLA_CTE 		= ($TIPO_CREDITO < 4)?('clientes_datos'):('clientes_datos_pmoral');
					$ID_SOLICITUD   = get_solicitud_cliente($TABLA_CTE,$NUM_CLIENTE);

					$SQL_UPDATE= "UPDATE ".$TABLA_CTE."
										SET
											ID_Solicitud 		= '".$ID_SOLICITUD_NEW."',
											ID_Tipocredito		= '".$TIPO_CREDITO."',
											ID_Tipo_regimen		= '".$TIPO_SOLICITUD."',
											ID_Sucursal      	= '".$ID_SUC."'
									WHERE	ID_Solicitud 		= '".$ID_SOLICITUD."' ";
					$db->Execute($SQL_UPDATE);

		}
  
}

function set_firma_digital($ID_SOLICITUD)
{
	global $db;

				$SQL_CONS ="SELECT
								ID_Solicitud																														AS ID_SOLI,
								(Concat(IFNULL(solicitud.Nombre,''),IFNULL(solicitud.NombreI,''),IFNULL(solicitud.Ap_paterno,''),IFNULL(solicitud.Ap_materno,'')))	AS NMB_CTE,
								Fecha_nacimiento																													AS FECH_INI
						FROM
							solicitud
						WHERE
								solicitud.ID_Solicitud = '".$ID_SOLICITUD."' ";
				$rs_cons  = $db->Execute($SQL_CONS);

				$Firma_soli  = $rs_cons->fields["NMB_CTE"].$rs_cons->fields["FECH_INI"];
				$Firma_soli  = str_replace(' ','',$Firma_soli);
				$Firma_soli  = strtoupper($Firma_soli);
				$Firma_soli  = md5($Firma_soli);

				$SQL_UPDT= "UPDATE
									solicitud
								SET
									solicitud.Firma_digital = '".$Firma_soli."'
								WHERE
									solicitud.ID_Solicitud  = '".$rs_cons->fields["ID_SOLI"]."'";
				$db->Execute($SQL_UPDT);

				$SQL_UPDT= "UPDATE
									clientes_datos
								SET
									clientes_datos.Firma_digital = '".$Firma_soli."'
								WHERE
									clientes_datos.ID_Solicitud  = '".$rs_cons->fields["ID_SOLI"]."'";
				$db->Execute($SQL_UPDT);
		
}

function get_firma_digital()
{
	global $db;

	$SQL_SOLI= "SELECT
						Valor		AS TIPO_VAL
	                   FROM
							constantes
	                   WHERE  Nombre = 'VALIDAR_FIRMA_DIGITAL'  ";
	$rs_soli=$db->Execute($SQL_SOLI);


	return $rs_soli->fields["TIPO_VAL"];
}

/************FIN FUNCTIONS AUX*******************/

function insert_campos_solicitud()
{
 global $_POST;
 global $db;
 global $ID_SUC;
 global $ID_USR;
 global $fechas_captura;
 //$Log_file = "insert_log.txt";

		/*******RENUEVA SOLICITUD*******/
		 if( !empty($_POST["NUM_CLIENTE"]) && ($_POST["NUM_CLIENTE"] > 0) )
		 {
		     $STATUS_RENUEVA = set_renueva_solicitud($_POST["NUM_CLIENTE"],$_POST["TIPO_CREDITO"]);
			 if($STATUS_RENUEVA == 'FALSE')
				{ echo "ERROR AL RENOVAR CLIENTE"; die();}
			 
		 }
		    
         // CAPTURAMOS SOLICITUDES
		 $campos_solicitud=obtener_campos_tabla($_POST["TIPO_CREDITO"],$_POST["TIPO_SOLICITUD"]);
		 $TABLA_DEST = ($_POST["TIPO_CREDITO"] < 4)?('solicitud'):('solicitud_pmoral');
		 
		 //FORMAR INSERT SOLICITUD
			$sql="INSERT INTO ".$TABLA_DEST." (";

			foreach($campos_solicitud AS $key => $value)
			{
				   if(array_key_exists($key,$_POST))
				 $sql.="$value,";
			}
			$sql=substr($sql, 0, -1);
			$sql.=") VALUES (";


	 	foreach($campos_solicitud AS $key => $value)
			{
			 if(array_key_exists($key,$_POST))
			  {
				   if(in_array($key, $fechas_captura) )
				  	$sql.="'".gfecha(utf8_decode($_POST[$key]))."',";
				   else
				  	$sql.="UCASE('".strtoupper(trim(utf8_decode($_POST[$key])))."'),";
			  }
			}

			$sql=substr($sql, 0, -1);
			$sql.=");";

    //$Log = fopen($Log_file, 'a+');
    //$Hoy = date("Y/m/d");
    //fwrite($Log , $Hoy . "\n");
    //fwrite($Log, $sql  . "\n");
   $VALIDATE= $db->Execute($sql);
   $ID_RESULT_SOLI=$db->_insertid();

	

   $Arr_resut = array("RESULT"=>$VALIDATE,"ID"=>$ID_RESULT_SOLI);

  if($db->_insertid() > 0)
  {
				if($_POST["TIPO_CREDITO"] < 4)
				{
						$Nomina			=	($_POST["TIPO_CREDITO"]==3)?('Y'):('N');
						$TCREDIT_ESP	=	($_POST["TIPO_NOMINA"] == 'TRUE')?('TRUE'):('FALSE');

						$Sql_update="UPDATE solicitud
										SET Status_solicitud 			= '".$_POST["STATUS_SOLI"]."',
											ID_Tipocredito   			= '".$_POST["TIPO_CREDITO"]."',
											Fecha			 			= NOW(),
											Fecha_sistema    			= NOW(),
											ID_Sucursal      			= '".$ID_SUC."',
											Tipocredito_especial		= '".$TCREDIT_ESP."',
											Nomina						= '".$Nomina."',
											Folio						= '".$db->_insertid()."',
											ID_Tipo_regimen				= '".$_POST["TIPO_SOLICITUD"]."',
											ID_User						= '".$ID_USR."'
									WHERE ID_Solicitud ='".$db->_insertid()."' ";
						$db->Execute($Sql_update);

						if($TCREDIT_ESP=='TRUE')
						{
							//ACTUALIZAR COTIZADOR ESPECIAL 4-ABRIL-2012
							$SQL_UPDATE="UPDATE cotizador_cliente
											SET ID_Solicitud ='".$db->_insertid()."'
											WHERE ID_Solicitud_importacion = '".$_POST["NOMINA_ID_SOLI_IMP"]."' ";
							$db->Execute($SQL_UPDATE);
						}
				}
				else
				{
						$Sql_update="UPDATE solicitud_pmoral
										SET Status_solicitud 			= '".$_POST["STATUS_SOLI"]."',
											ID_Tipocredito   			= '".$_POST["TIPO_CREDITO"]."',
											Fecha			 			= NOW(),
											Fecha_sistema    			= NOW(),
											ID_Sucursal      			= '".$ID_SUC."',
											Folio						= '".$db->_insertid()."',
											ID_Tipo_regimen				= '".$_POST["TIPO_SOLICITUD"]."',
											ID_User						= '".$ID_USR."'
									WHERE ID_Solicitud ='".$db->_insertid()."' ";
						$db->Execute($Sql_update);
				}

				/****FIRMA DIGITAL*****/
				$FIRMA_DIGITAL		= get_firma_digital();
				if($FIRMA_DIGITAL == 'SI')
					set_firma_digital($db->_insertid());

				

				$sql_usr ="SELECT UCASE(CONCAT(Nombre,' ',AP_Paterno,' ',AP_Materno)) AS USR 
							  FROM usuarios 
							  WHERE ID_User= '".$ID_USR."' ";
				$rs_usr  = $db->Execute($sql_usr); 
				$Responsable=$rs_usr->fields["USR"];

				$Msg_tipo_credito=($_POST["TIPO_CREDITO"]=='1')?("PERSONAL"):("SOLIDARIO");
				$Msg_tipo_credito=($_POST["TIPO_CREDITO"]=='3')?("NÓMINA"):($Msg_tipo_credito);
				$Msg_tipo_credito=($_POST["TIPO_CREDITO"]=='4')?("FACTORAJE"):($Msg_tipo_credito);
				$Msg_tipo_credito=($_POST["TIPO_CREDITO"]=='5')?("EMPRESARIAL"):($Msg_tipo_credito);
				
				$Tbl_suceso = ($_POST["TIPO_CREDITO"]<'4')?("solicitud_sucesos"):("solicitud_pmoral_sucesos");
				$Suceso		= "CAPTURA DE SOLICITUD DE CRÉDITO ".$Msg_tipo_credito.", EN LA SUCURSAL(".$ID_SUC.")";
				$Sql_suceso ="INSERT INTO ".$Tbl_suceso."  (ID_Solicitud,Fecha,Atendio,Status,Suceso)
							  VALUES('".$db->_insertid()."',NOW(),'".$Responsable."','SOLICITUD - CAPTURADA','".$Suceso."')";	
				$db->Execute($Sql_suceso);

    /******INSERT PREVENSIÓN DE LAVADO DE DINERO******/
    set_insert_prev_lavado_dinero($ID_RESULT_SOLI,$_POST["TIPO_CREDITO"],$_POST["TIPO_SOLICITUD"]);

	/*********BACKUP*******/
	set_insert_solicitud_backup($ID_RESULT_SOLI,$_POST["TIPO_CREDITO"],'Alta',$_POST["TIPO_SOLICITUD"]);

	/*******RENUEVA SOLICITUD*******/
	 if( !empty($_POST["NUM_CLIENTE"]) && ($_POST["NUM_CLIENTE"] > 0) )
	 {
	     set_update_solicitud_renovada($_POST["NUM_CLIENTE"],$ID_RESULT_SOLI,$_POST["TIPO_CREDITO"],$_POST["TIPO_SOLICITUD"]);
	     set_update_clientes_datos($ID_RESULT_SOLI,$_POST["TIPO_CREDITO"],$_POST["TIPO_SOLICITUD"]);
	 }

	 /**************ALERTAS ****************/
	 $Genera_alertas = new  TNuevaAlerta ($ID_RESULT_SOLI,$_POST["TIPO_CREDITO"],$_POST["TIPO_SOLICITUD"],'ALTA DE SOLICITUD','',$db,$ID_SUC,$ID_USR);
	 $Genera_alertas->set_notifica_proceso('EMAIL','CAPTURA SOLICITUD','CAPTURADA');
	 /*************************************/
    
  } //FIN DB INSERT
  
    //fwrite($Log, $Sql_update . "\n");

	//CAPTURAMOS CAMPOS ESPECIALES
	
		 $campos_solicitud_especial=obtener_campos_tabla_especiales($_POST["TIPO_CREDITO"],$_POST["TIPO_SOLICITUD"]);
		 //FORMAR INSERT SOLICITUD
			if(!empty($campos_solicitud_especial))
			{
					foreach($campos_solicitud_especial AS $key => $value)
					{
					
						$NMB_CMP_ESP =( $campos_solicitud_especial[$key]['TIPO_DATO'] == 'INT')?('Valor_int'):('Valor_varchar_large');
						$NMB_CMP_ESP =( $campos_solicitud_especial[$key]['TIPO_DATO'] == 'FLOAT')?('Valor_float'):($NMB_CMP_ESP);
						$NMB_CMP_ESP =( $campos_solicitud_especial[$key]['TIPO_DATO'] == 'VARCHAR_SHORT')?('Valor_varchar_short'):($NMB_CMP_ESP);
						$NMB_CMP_ESP =( $campos_solicitud_especial[$key]['TIPO_DATO'] == 'TEXT')?('Valor_text'):($NMB_CMP_ESP);
						$NMB_CMP_ESP =( $campos_solicitud_especial[$key]['TIPO_DATO'] == 'DATE')?('Valor_date'):($NMB_CMP_ESP);
						
						   if(array_key_exists($key,$_POST))
						   {
								$sql_especial="INSERT INTO solicitud_campos_especiales (".$NMB_CMP_ESP.",ID_campo,ID_Solicitud,ID_Tipo_regimen) VALUES (";
								
								if(in_array($key, $fechas_captura) )
									$sql_especial.="'".gfecha(utf8_decode($_POST[$key]))."', ";
								else
									$sql_especial.="UCASE('".strtoupper(trim(utf8_decode($_POST[$key])))."'), ";

								$sql_especial.="".$campos_solicitud_especial[$key]['ID_CMP'].",".$ID_RESULT_SOLI.",".$_POST["TIPO_SOLICITUD"]." )";
								$db->Execute($sql_especial);
									
						   }
		 
					}
				}
	//FIN CAMPOS ESPECIALES

	
	 //CAPTURAMOS REFERENCIAS *
	 if($ID_RESULT_SOLI > 0)
	 {
					$ARR_REFERENCIAS=array('REFERENCIA_ACCIONISTA','REFERENCIA_FUNCIONARIO','REFERENCIA_FUNCIONARIO_AUTO','REFERENCIA_PROVEEDOR','REFERENCIA_COMERCIAL','REFERENCIA_BANCARIA','REFERENCIA','REFERENCIA_CHEQUES');

					foreach($ARR_REFERENCIAS AS &$TIPO_REF)
						{

								switch($TIPO_REF)
								{
									case 'REFERENCIA_ACCIONISTA':
										$NUM_REFERENCIA = 'NUM_REFERENCIA_ACCION';
										$TABLE_REF   	= 'solicitud_accionista';
										$CMP_FOUND	    = '_accionista_';
										$TABLE_INS		= 'accionistas';
										$ID_TABLE		= 'ID_Accionista';
									break; 

									case 'REFERENCIA_FUNCIONARIO':
										$NUM_REFERENCIA = 'NUM_REFERENCIA_FUNCION';
										$TABLE_REF	    = 'solicitud_funcionarios';
										$CMP_FOUND	    = '_funcionario_';
										$TABLE_INS	    = 'funcionarios';
										$ID_TABLE		= 'ID_Funcionario';
									break; 

									case 'REFERENCIA_FUNCIONARIO_AUTO':
										$NUM_REFERENCIA = 'NUM_REFERENCIA_FUNCIONAUTO';
										$TABLE_REF	    = 'solicitud_funcionarios_autorizados';
										$CMP_FOUND	    = '_funcionario_autorizado_';
										$TABLE_INS	    = 'funcionarios_autorizados';
										$ID_TABLE		= 'ID_Funcionario_Autorizado';
									break;

									case 'REFERENCIA_PROVEEDOR':
										$NUM_REFERENCIA = 'NUM_REFERENCIA_PROVEEDORES';
										$TABLE_REF	    = 'solicitud_proveedores';
										$CMP_FOUND	    = '_proveedores_';
										$TABLE_INS	    = 'proveedores';
										$ID_TABLE		= 'ID_Proveedor';
									break;

									case 'REFERENCIA_COMERCIAL':
										$NUM_REFERENCIA = 'NUM_REFERENCIA_COMERCIAL';
										$TABLE_REF	    = 'solicitud_referencias_comerciales';
										$CMP_FOUND	    = '_referencias_comerciales_';
										$TABLE_INS	    = 'referencias_comerciales';
										$ID_TABLE		= 'ID_Referencia_Comercial';
									break;

									case 'REFERENCIA_BANCARIA':
										$NUM_REFERENCIA = 'NUM_REFERENCIA_BANCARIA';
										$TABLE_REF	    = 'solicitud_referencias_bancarias';
										$CMP_FOUND	    = '_referencias_bancarias_';
										$TABLE_INS		= 'referencias_bancarias';
										$ID_TABLE		= 'ID_Referencia_Banco';
									break;

									case 'REFERENCIA_CHEQUES':
										$NUM_REFERENCIA = 'NUM_REFERENCIA_CHEQUES';
										$TABLE_REF	    = 'solicitud_referencias_cheques';
										$CMP_FOUND	    = '_referencias_cheques_';
										$TABLE_INS		= 'referencias_cheques';
										$ID_TABLE		= 'ID_Referencia_Cheque';
									break;
									
									default:
										$NUM_REFERENCIA = 'NUM_REFERENCIAS_SOLI';
										$TABLE_REF	    = 'solicitud_referencias';
										$CMP_FOUND	    = '_referencia_';
										$TABLE_INS	    = 'referencias';
										$ID_TABLE		= 'ID_Referencia';
									break;
								}

							
							
							
									$Num_ref = $_POST["".$NUM_REFERENCIA.""];
									$Indice_orden=1;

									  for($cont=1;($cont <= $Num_ref );$cont++)
									  {
											 $campos_solicitud=obtener_campos_tabla_referencias($_POST["TIPO_CREDITO"],$TABLE_INS,$CMP_FOUND.$cont,$CMP_FOUND.'1');

											 //FORMAR INSERT REFERENCIAS
											$sql="INSERT INTO ".$TABLE_INS." (";

												foreach($campos_solicitud AS $key => $value)
												{
													 if(array_key_exists($key,$_POST))
														$sql.="$value,";
												}
												$sql=substr($sql, 0, -1);
												$sql.=") VALUES (";

												$Vacio= 0;
												foreach($campos_solicitud AS $key => $value)
													{

														 if(array_key_exists($key,$_POST))
														  {
																$CMP_VALUE=trim($_POST[$key]);
																
															   if(in_array($key, $fechas_captura) )
																$sql.="'".gfecha(utf8_decode($CMP_VALUE))."',";
															   else
																$sql.="UCASE('".strtoupper(utf8_decode($CMP_VALUE))."'),";


															   $Vacio=( empty($CMP_VALUE) )?($Vacio):($Vacio + 1);
														  }
													}//FIN FOREACH

												$sql=substr($sql, 0, -1);
												$sql.=");";
												//fwrite($Log, $sql . "\n");
												
											if(!empty($sql) && ($Vacio > 0))
											{      
												$db->Execute($sql);
																	
												  if($db->_insertid() > 0)
												  {
													$Sql_ins="INSERT INTO ".$TABLE_REF."
																			   (".$ID_TABLE.",ID_Solicitud)
																		VALUES ('".$db->_insertid()."','".$ID_RESULT_SOLI."')";
													$db->Execute($Sql_ins);

													$Sql_update="UPDATE ".$TABLE_INS."
																		SET
																			Fecha_sistema    = NOW(),
																			ID_Sucursal      = '".$ID_SUC."',
																			Orden			 = '".$Indice_orden."'
																	WHERE ".$ID_TABLE." ='".$db->_insertid()."' ";
													$db->Execute($Sql_update);

													$Indice_orden++;
												  }
										   }//FIN IF VACIO == TRUE

									  }//FIN FOR
				 }//FIN REFERENCIAS PERSONALES FOR EACH

	

					//SI ES UN CRÉDITO SOLIDARIO
					if($_POST["TIPO_CREDITO"] == '2')
					{

									$Sql_ciclo="SELECT
													Ciclo_gpo 		AS CICLO,
													ID_Promotor		AS PROMO,
													Ciclo_inicial	AS CILO_INTG
												FROM (grupo_solidario,cat_params_grupo)
												WHERE ID_grupo_soli='".$_POST["ID_grupo_soli"]."' ";
									$rs_ciclo=$db->Execute($Sql_ciclo);

									$Sql_ins="INSERT INTO grupo_solidario_integrantes (ID_grupo_soli,Ciclo_gpo,Ciclo_cliente,Fecha_integracion,ID_Solicitud)
												VALUES('".$_POST["ID_grupo_soli"]."','".$rs_ciclo->fields["CICLO"]."','".$rs_ciclo->fields["CILO_INTG"]."',NOW(),'".$ID_RESULT_SOLI."')";
									$db->Execute($Sql_ins);

							if(!empty($_POST["ID_Promotor"]) )
							{
									$Sql_update="UPDATE solicitud
													SET
														ID_Promotor = '".$rs_ciclo->fields["PROMO"]."'
												WHERE ID_Solicitud  = '".$ID_RESULT_SOLI."' ";
									$db->Execute($Sql_update);

									//fwrite($Log, $Sql_ins . "\n");
							}
					}



	  //fclose($Log);
	  
	}//fin $ID_RESULT_SOLI > 0

	return $Arr_resut;
}
/**************FIN INSERT******************/


/***UPDATE SOLI*****/
function update_campos_solicitud()
{
 global $_POST;
 global $db;
 global $ID_SUC;
 global $ID_USR;
 global $fechas_captura;


		// CAPTURAMOS SOLICITUDES
		 $campos_solicitud=obtener_campos_tabla($_POST["TIPO_CREDITO"],$_POST["TIPO_SOLICITUD"]);
		 $TABLA_DEST = ($_POST["TIPO_CREDITO"] < 4)?('solicitud'):('solicitud_pmoral');
		 
		 //FORMAR INSERT SOLICITUD
			$sql="UPDATE  ".$TABLA_DEST."  SET ";
	
		   foreach($campos_solicitud AS $key => $value)
			{
			 if(array_key_exists($key,$_POST))
			  {
				  if(in_array($key, $fechas_captura))
				   		$sql.=" ".$value." = '".gfecha(utf8_decode($_POST[$key]))."',";
				   else
				    	$sql.=" ".$value." = UCASE('".strtoupper(trim(utf8_decode($_POST[$key])))."'),";
			  }
			}

			$sql=substr($sql, 0, -1);
			$sql.=" WHERE ".$_POST["Param1"]." = '".$_POST["Param2"]."';";

   $VALIDATE= $db->Execute($sql);
   //$ID_RESULT_SOLI=$db->_insertid();

   $Arr_resut = array("RESULT"=>$VALIDATE,"ID"=>$_POST["Param2"]);

  if($_POST["Param2"] > 0)
  {
				if($_POST["TIPO_CREDITO"] < 4)
				{
					$Sql_update="UPDATE solicitud
									SET
										ID_Tipocredito   = '".$_POST["TIPO_CREDITO"]."',
										Fecha			 = NOW(),
										Fecha_sistema    = NOW(),
										#ID_Sucursal      = '".$ID_SUC."',
										Folio			 = '".$_POST["Param2"]."',
										ID_User			 = '".$ID_USR."'
								WHERE ID_Solicitud ='".$_POST["Param2"]."' ";
					$db->Execute($Sql_update);
				}
				else
				{
					$Sql_update="UPDATE solicitud_pmoral
									SET
										ID_Tipocredito   		='".$_POST["TIPO_CREDITO"]."',
										Fecha			 		= NOW(),
										Fecha_sistema    		= NOW(),
										#ID_Sucursal      		= '".$ID_SUC."',
										Folio			 		= '".$_POST["Param2"]."',
										ID_Tipo_regimen			= '".$_POST["TIPO_SOLICITUD"]."',
										ID_User					= '".$ID_USR."'
								WHERE ID_Solicitud ='".$_POST["Param2"]."' ";
					$db->Execute($Sql_update);

				}

				/****FIRMA DIGITAL*******/
				$FIRMA_DIGITAL		= get_firma_digital();
				if($FIRMA_DIGITAL == 'SI')
					set_firma_digital($_POST["Param2"]);
				/************************/

				
					$Sql_status="SELECT
										Status_solicitud		AS STAT
									FROM ".$TABLA_DEST."
									WHERE ID_Solicitud ='".$_POST["Param2"]."' ";
					$rs_stat=$db->Execute($Sql_status);
				
				set_update_clientes_datos($_POST["Param2"],$_POST["TIPO_CREDITO"],$_POST["TIPO_SOLICITUD"]);

				/*************ACTUALIZA CONVENIO EMPRESA******************/
				if($_POST["TIPO_CREDITO"] == 3)
					set_update_empresa($_POST["Param2"]);
					

					if($rs_stat->fields["STAT"] =='CAPTURADA-PENDIENTE' || $rs_stat->fields["STAT"] =='CAPTURADA')
					{
						$Sql_update="UPDATE ".$TABLA_DEST."
										SET Status_solicitud ='".$_POST["STATUS_SOLI"]."'
									WHERE ID_Solicitud ='".$_POST["Param2"]."' ";
						$db->Execute($Sql_update);
					}

				/**********MARCAR SUCESO***********/

				$sql_usr ="SELECT UCASE(CONCAT(Nombre,' ',AP_Paterno,' ',AP_Materno)) AS USR 
							  FROM usuarios 
							  WHERE ID_User= '".$ID_USR."' ";
				$rs_usr  = $db->Execute($sql_usr); 
				$Responsable=$rs_usr->fields["USR"];

				$Msg_tipo_credito=($_POST["TIPO_CREDITO"]=='1')?("PERSONAL"):("SOLIDARIO");
				$Msg_tipo_credito=($_POST["TIPO_CREDITO"]=='3')?("NÓMINA"):($Msg_tipo_credito);
				$Msg_tipo_credito=($_POST["TIPO_CREDITO"]=='4')?("P. MORAL"):($Msg_tipo_credito);
				
				$Tbl_suceso = ($_POST["TIPO_CREDITO"]<'4')?("solicitud_sucesos"):("solicitud_pmoral_sucesos");

				$Sql_suceso ="INSERT INTO ".$Tbl_suceso." (ID_Solicitud,Fecha,Atendio,Status,Suceso)
							  VALUES('".$_POST["Param2"]."',NOW(),'".$Responsable."','SOLICITUD - EDITADA','EDICIÓN DE SOLICITUD DE CRÉDITO ".$Msg_tipo_credito.", EN LA SUCURSAL(".$ID_SUC."), CON STATUS (".$_POST["STATUS_SOLI"].")')";	
				$db->Execute($Sql_suceso);
				/***********************************/

		/******UPDATE PREVENSIÓN DE LAVADO DE DINERO******/
		set_update_prev_lavado_dinero($_POST["Param2"],$_POST["TIPO_CREDITO"]);
		$NUM_CLIENTE = get_num_cte($_POST["Param2"]);
		
		if( !empty($NUM_CLIENTE) )
		{
			$oriesgo = new TRIESGO($db, $NUM_CLIENTE);
			$oriesgo->actualiza_perfil_transaccional();
		}

		/*********BACKUP*******/
		set_insert_solicitud_backup($_POST["Param2"],$_POST["TIPO_CREDITO"],'Edición',$_POST["TIPO_SOLICITUD"]);

		 /**************ALERTAS ****************/
		 $Genera_alertas = new  TNuevaAlerta ($_POST["Param2"],$_POST["TIPO_CREDITO"],$_POST["TIPO_SOLICITUD"],'ALTA DE SOLICITUD','EDICION',$db,$ID_SUC,$ID_USR);
		 $Genera_alertas->set_notifica_proceso('EMAIL','EDICIÓN DE SOLICITUD','EDITADA');
		 /*************************************/


	//SI ES UN CRÉDITO SOLIDARIO
	if($_POST["TIPO_CREDITO"] == '2')
	{
					if( empty($_POST["ID_Promotor"]) )
					{
							$Sql_ciclo="SELECT
											Ciclo_gpo 		AS CICLO,
											ID_Promotor		AS PROMO
										FROM grupo_solidario
										WHERE ID_grupo_soli='".$_POST["ID_grupo_soli"]."' ";
							$rs_ciclo=$db->Execute($Sql_ciclo);

							$Sql_update="UPDATE solicitud
											SET
												ID_Promotor = '".$rs_ciclo->fields["PROMO"]."'
										WHERE ID_Solicitud  = '".$_POST["Param2"]."' ";
							$db->Execute($Sql_update);
					}

					//fwrite($Log, $Sql_ins . "\n");
	}

 }//FIN $_POST["Param2"] > 0

	//EDITAMOS CAMPOS ESPECIALES
	
		 $campos_solicitud_especial=obtener_campos_tabla_especiales($_POST["TIPO_CREDITO"],$_POST["TIPO_SOLICITUD"]);
		 //FORMAR INSERT SOLICITUD
			if(!empty($campos_solicitud_especial))
			{
					foreach($campos_solicitud_especial AS $key => $value)
					{
					
						$NMB_CMP_ESP =( $campos_solicitud_especial[$key]['TIPO_DATO'] == 'INT')?('Valor_int'):('Valor_varchar_large');
						$NMB_CMP_ESP =( $campos_solicitud_especial[$key]['TIPO_DATO'] == 'FLOAT')?('Valor_float'):($NMB_CMP_ESP);
						$NMB_CMP_ESP =( $campos_solicitud_especial[$key]['TIPO_DATO'] == 'VARCHAR_SHORT')?('Valor_varchar_short'):($NMB_CMP_ESP);
						$NMB_CMP_ESP =( $campos_solicitud_especial[$key]['TIPO_DATO'] == 'TEXT')?('Valor_text'):($NMB_CMP_ESP);
						$NMB_CMP_ESP =( $campos_solicitud_especial[$key]['TIPO_DATO'] == 'DATE')?('Valor_date'):($NMB_CMP_ESP);
						
						   if(array_key_exists($key,$_POST))
						   {
							   $ROW_EXIST = get_campo_especial_exist($campos_solicitud_especial[$key]['ID_CMP'],$_POST["Param2"],$_POST["TIPO_SOLICITUD"]);

							   if(	($ROW_EXIST == 'TRUE') && ( trim($_POST[$key]) != '' ) )
							   {
									$sql_especial="UPDATE solicitud_campos_especiales
														SET ".$NMB_CMP_ESP."  =   ";
									
									if(in_array($key, $fechas_captura) )
										$sql_especial.="'".gfecha(utf8_decode($_POST[$key]))."' ";
									else
										$sql_especial.="UCASE('".strtoupper(trim(utf8_decode($_POST[$key])))."') ";

									$sql_especial.="WHERE	 ID_campo 		 =  '".$campos_solicitud_especial[$key]['ID_CMP']."'
														AND  ID_Solicitud 	 =  '".$_POST["Param2"]."'
														AND	 ID_Tipo_regimen =	'".$_POST["TIPO_SOLICITUD"]."' ";
									$db->Execute($sql_especial);
								}

							   if(	($ROW_EXIST == 'TRUE') && ( trim($_POST[$key]) == '' ) )
							   {
									$sql_especial="DELETE FROM solicitud_campos_especiales
													WHERE	 ID_campo 		 =  '".$campos_solicitud_especial[$key]['ID_CMP']."'
														AND  ID_Solicitud 	 =  '".$_POST["Param2"]."'
														AND	 ID_Tipo_regimen =	'".$_POST["TIPO_SOLICITUD"]."'";
									$db->Execute($sql_especial);
							   }

							   if(	($ROW_EXIST == 'FALSE') && ( trim($_POST[$key]) != '' ) )
							   {
									$sql_especial="INSERT INTO solicitud_campos_especiales (".$NMB_CMP_ESP.",ID_campo,ID_Solicitud,ID_Tipo_regimen) VALUES (";
									
									if(in_array($key, $fechas_captura) )
										$sql_especial.="'".gfecha(utf8_decode($_POST[$key]))."', ";
									else
										$sql_especial.="UCASE('".strtoupper(trim(utf8_decode($_POST[$key])))."'), ";

									$sql_especial.="".$campos_solicitud_especial[$key]['ID_CMP'].",".$_POST["Param2"].",".$_POST["TIPO_SOLICITUD"]." )";
									$db->Execute($sql_especial);
							   }
								
						   }
		 
					}
				}
	//FIN CAMPOS ESPECIALES
	
   /*****************************************/
  //REFERENCIAS PERSONALES
  /*****************************************/
  $ARR_REFERENCIAS=array('REFERENCIA_ACCIONISTA','REFERENCIA_FUNCIONARIO','REFERENCIA_FUNCIONARIO_AUTO','REFERENCIA_PROVEEDOR','REFERENCIA_COMERCIAL','REFERENCIA_BANCARIA','REFERENCIA','REFERENCIA_CHEQUES');

		foreach($ARR_REFERENCIAS AS &$TIPO_REF)
			{

					switch($TIPO_REF)
					{
						case 'REFERENCIA_ACCIONISTA':
							$NUM_REFERENCIA = 'NUM_REFERENCIA_ACCION';
							$TABLE_REF   	= 'solicitud_accionista';
							$CMP_FOUND	    = '_accionista_';
							$TABLE_INS		= 'accionistas';
							$ID_TABLE		= 'ID_Accionista';
						break; 

						case 'REFERENCIA_FUNCIONARIO':
							$NUM_REFERENCIA = 'NUM_REFERENCIA_FUNCION';
							$TABLE_REF	    = 'solicitud_funcionarios';
							$CMP_FOUND	    = '_funcionario_';
							$TABLE_INS	    = 'funcionarios';
							$ID_TABLE		= 'ID_Funcionario';
						break; 

						case 'REFERENCIA_FUNCIONARIO_AUTO':
							$NUM_REFERENCIA = 'NUM_REFERENCIA_FUNCIONAUTO';
							$TABLE_REF	    = 'solicitud_funcionarios_autorizados';
							$CMP_FOUND	    = '_funcionario_autorizado_';
							$TABLE_INS	    = 'funcionarios_autorizados';
							$ID_TABLE		= 'ID_Funcionario_Autorizado';
						break;

						case 'REFERENCIA_PROVEEDOR':
							$NUM_REFERENCIA = 'NUM_REFERENCIA_PROVEEDORES';
							$TABLE_REF	    = 'solicitud_proveedores';
							$CMP_FOUND	    = '_proveedores_';
							$TABLE_INS	    = 'proveedores';
							$ID_TABLE		= 'ID_Proveedor';
						break;

						case 'REFERENCIA_COMERCIAL':
							$NUM_REFERENCIA = 'NUM_REFERENCIA_COMERCIAL';
							$TABLE_REF	    = 'solicitud_referencias_comerciales';
							$CMP_FOUND	    = '_referencias_comerciales_';
							$TABLE_INS	    = 'referencias_comerciales';
							$ID_TABLE		= 'ID_Referencia_Comercial';
						break;

						case 'REFERENCIA_BANCARIA':
							$NUM_REFERENCIA = 'NUM_REFERENCIA_BANCARIA';
							$TABLE_REF	    = 'solicitud_referencias_bancarias';
							$CMP_FOUND	    = '_referencias_bancarias_';
							$TABLE_INS		= 'referencias_bancarias';
							$ID_TABLE		= 'ID_Referencia_Banco';
						break;

						case 'REFERENCIA_CHEQUES':
							$NUM_REFERENCIA = 'NUM_REFERENCIA_CHEQUES';
							$TABLE_REF	    = 'solicitud_referencias_cheques';
							$CMP_FOUND	    = '_referencias_cheques_';
							$TABLE_INS		= 'referencias_cheques';
							$ID_TABLE		= 'ID_Referencia_Cheque';
						break;
									
						default:
							$NUM_REFERENCIA = 'NUM_REFERENCIAS_SOLI';
							$TABLE_REF	    = 'solicitud_referencias';
							$CMP_FOUND	    = '_referencia_';
							$TABLE_INS	    = 'referencias';
							$ID_TABLE		= 'ID_Referencia';
						break;
					}
					
						$Num_ref = $_POST["".$NUM_REFERENCIA.""];
						$Indice_orden=1;

						  for($cont=1;($cont <= $Num_ref );$cont++)
						  {
								$campos_solicitud=obtener_campos_tabla_referencias($_POST["TIPO_CREDITO"],$TABLE_INS,$CMP_FOUND.$cont,$CMP_FOUND.'1');
								$ID_REFERENCIA= get_referencia_solicitud($_POST["Param2"],$cont,$TABLE_INS,$TABLE_REF,$ID_TABLE);

								 

									$REF_VACIA="TRUE";
									$sql_update="UPDATE  ".$TABLE_INS." SET ";
								   foreach($campos_solicitud AS $key => $value)
									{
									 if(array_key_exists($key,$_POST))
									  {
										  if(in_array($key, $fechas_captura))
										   	{
											   $sql_update.=" ".$value." = '".gfecha(utf8_decode($_POST[$key]))."',";
										   	   $REF_VACIA=(!empty($_POST[$key]))?("FALSE"):($REF_VACIA);
										   	}
										   else
										   	{
												$sql_update.=" ".$value." = UCASE('".strtoupper(trim(utf8_decode($_POST[$key])))."'),";
												$REF_VACIA=(!empty($_POST[$key]))?("FALSE"):($REF_VACIA);
											}
									   }
									}

									$sql_update=substr($sql_update, 0, -1);
									$sql_update.=" WHERE ".$ID_TABLE." = '".$ID_REFERENCIA."';";

									//PRIMER CASO SÓLO ACTUALIZAMOS
									if( ($REF_VACIA == "FALSE") && ( !empty($ID_REFERENCIA) ) )
									     $db->Execute($sql_update);

									//SEGUNDO CASO ELIMINAR REFERENCIA VACÍA
									if( ($REF_VACIA == "TRUE") && ( !empty($ID_REFERENCIA) ) )
									{
										$Sql_del="DELETE FROM ".$TABLE_INS."
														 WHERE ".$ID_TABLE." = '".$ID_REFERENCIA."' ";

										$db->Execute($Sql_del);

										$Sql_del="DELETE FROM ".$TABLE_REF."
														 WHERE      ".$ID_TABLE." = '".$ID_REFERENCIA."'
																AND ID_Solicitud  = '".$_POST["Param2"]."' ";

										$db->Execute($Sql_del);
									}

									//TERCER CASO INSERTAR NUEVA REFERENCIA
									if( ($REF_VACIA == "FALSE") && ( empty($ID_REFERENCIA) ) )
									{
														$sql="INSERT INTO ".$TABLE_INS." (";

															foreach($campos_solicitud AS $key => $value)
															{
																 if(array_key_exists($key,$_POST))
																	$sql.="$value,";
															}
															$sql=substr($sql, 0, -1);
															$sql.=") VALUES (";


															foreach($campos_solicitud AS $key => $value)
																{
																	
																 if(array_key_exists($key,$_POST))
																  {
																		$CMP_VALUE=trim($_POST[$key]);
																		
																	   if(in_array($key, $fechas_captura) )
																		  $sql.="'".gfecha(utf8_decode($CMP_VALUE))."',";
																	   else
																		  $sql.="UCASE('".strtoupper(utf8_decode($CMP_VALUE))."'),";
																  }
																}//FIN FOREACH

															$sql=substr($sql, 0, -1);
															$sql.=");";
															//fwrite($Log, $sql . "\n");
															
														if(!empty($sql) )
														{      
															   $db->Execute($sql);
																				
															  if($db->_insertid() > 0)
															  {
																$Sql_ins="INSERT INTO ".$TABLE_REF."
																						   (".$ID_TABLE.",ID_Solicitud)
																					VALUES ('".$db->_insertid()."','".$_POST["Param2"]."')";
																$db->Execute($Sql_ins);

																$Sql_update="UPDATE ".$TABLE_INS."
																					SET
																						Fecha_sistema    = NOW(),
																						ID_Sucursal      = '".$ID_SUC."',
																						Orden			 = '".$cont."'
																				WHERE ".$ID_TABLE." ='".$db->_insertid()."' ";
																$db->Execute($Sql_update);

															  }
													   }//FIN IF VACIO == TRUE

									}//FIN TERCER CASO

									

						  }//FIN FOR
			}//FIN FOREACH

  return $Arr_resut;

}


if(isset($_POST) && !empty($_POST))
{

 if($_POST["CRUD_SOLI"] == 'CAPTURA_SOLICITUD' )
 {
	   //$VALIDA_REQUERIDOS	= get_check_requeridos($_POST["TIPO_CREDITO"],$_POST["TIPO_SOLICITUD"]);

	   //if($VALIDA_REQUERIDOS == 'TRUE')
	   //{
		   
		   $Insert_soli 	 	= insert_campos_solicitud();
		   echo $Insert_soli["ID"];
	   //}
	   //else
		//echo $VALIDA_REQUERIDOS;
 }

 if($_POST["CRUD_SOLI"] == 'EDITA_SOLICITUD' )
 {
	 //$VALIDA_REQUERIDOS	= get_check_requeridos($_POST["TIPO_CREDITO"],$_POST["TIPO_SOLICITUD"]);
	 
	 //if($VALIDA_REQUERIDOS == 'TRUE')
	   //{
		   $Update_soli = update_campos_solicitud();
		   echo $_POST["Param2"];
	   //}
	   //else
	     //echo $VALIDA_REQUERIDOS;
 }
  
}
?>
