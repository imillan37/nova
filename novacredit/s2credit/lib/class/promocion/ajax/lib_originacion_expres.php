<?
/****************************************/
/*Fecha: 30/Noviembre/2011
/*Autor: Tonathiu Cárdenas
/*Descripción: GENERA LA TABLA DE SOLICITUDES
/*Dependencias: originacion_credito.php
/****************************************/

$exit = 0;
$noheader =1;
include($DOCUMENT_ROOT."/rutas.php");			//CORE CONSTANTES S2CREDIT

//Inicio conexión
$db = ADONewConnection(SERVIDOR);
$db->Connect(IP,USER,PASSWORD,NUCLEO);
//Fin Conexión

	if( isset($MUESTRA_CAPTURA) && !empty($MUESTRA_CAPTURA) && isset($ID_SOLI) && !empty($ID_SOLI)  && isset($TIPO_CREDIT) && !empty($TIPO_CREDIT)  )
	{

	//SELECT SOLICITUD
		$SQL_CONS = "SELECT
							ID_Producto,
							Monto,
							Plazo,
							solicitud.ID_empresa,
							Status_solicitud,
							cat_convenio_empresas.Empresa
		              FROM  solicitud
						LEFT JOIN cat_convenio_empresas ON cat_convenio_empresas.ID_empresa = solicitud.ID_empresa
		              WHERE
		                 ID_Solicitud = '".$ID_SOLI."' ";
		$rs_cons=$db->Execute($SQL_CONS);
		list($ID_Producto,$Monto,$Plazo,$ID_Empresa,$Status_soli,$Nmb_empresa)=$rs_cons->fields;

				if( ($Status_soli !='ALTA CREDITO' && $Status_soli !='DISPOSICION - CREDITO' )   )
				{
						$ID_Empresa		=(empty($ID_Empresa))?(0):($ID_Empresa);
						$Nmb_empresa	=(empty($Nmb_empresa))?("SELECCIONE UNA OPCIÓN DEL CATÁLOGO..."):($Nmb_empresa);

						$DSBL_BUTTON="DISABLED='DISABLED'";
						$BUTTON_CHECK="";
						$Monto=floatval($Monto);

						if($TIPO_CREDIT == '3')
							if(!empty($ID_Producto) && !empty($Monto) && !empty($Plazo)  && !empty($ID_Empresa)  )
							$DSBL_BUTTON="";
						
						if($TIPO_CREDIT == '1')
							if(!empty($ID_Producto) && !empty($Monto) && !empty($Plazo)   )
							 $DSBL_BUTTON="";
							 

						if($TIPO_CREDIT == '3')
							if( ($Status_soli !='ALTA CREDITO' && $Status_soli !='DISPOSICION - CREDITO'  && $Status_soli !='COMITE CREDITO')   )
							$BUTTON_CHECK="<BUTTON  ID='SAVE_CTE' ".$DSBL_BUTTON."  STYLE='font-size:x-small;' > &nbsp;&nbsp; ASIGNAR CHECK LIST </BUTTON>";
						
						if($TIPO_CREDIT == '1')
							if( ($Status_soli !='ALTA CREDITO' && $Status_soli !='DISPOSICION - CREDITO'  && $Status_soli !='COMITE CREDITO')   )
							$BUTTON_CHECK="<BUTTON  ID='SAVE_CTE'  ".$DSBL_BUTTON." STYLE='font-size:x-small;' > &nbsp;&nbsp; ASIGNAR CHECK LIST </BUTTON>";

							
						
					//COMBOS A GENERAR
						$SQL_PROD = "SELECT
											ID_Producto AS IDPROD,
											Nombre      AS NOMPROD
									  FROM  cat_productosfinancieros
									  WHERE
										  ID_Tipocredito ='".$TIPO_CREDIT."'
										  AND Status='Activo'
									  ORDER BY ID_Producto";
						$rs_prod=$db->Execute($SQL_PROD);

					$combo_prod ="<SELECT ID='ID_Producto'  > \n";
					$combo_prod.= "<OPTION VALUE='' SELECTED  >SELECCIONAR OPCIÓN</OPTION> \n";
					$combo_prod.= "<OPTION VALUE='' DISABLED>---------------------------------</OPTION>";
					while(! $rs_prod->EOF )
						{
							 $sel = ($ID_Producto == $rs_prod->fields["IDPROD"])?("SELECTED"):("");
							 $combo_prod.="<OPTION VALUE='".$rs_prod->fields["IDPROD"]."' ".$sel."  >".$rs_prod->fields["IDPROD"]."-".$rs_prod->fields["NOMPROD"]."</OPTION> \n";
							 $rs_prod->MoveNext();
						}//Fin while
					$combo_prod.="</SELECT>\n";

					$cmb_plazo="<SELECT ID='Plazo' >
								<OPTION  SELECTED VALUE=''>SELECCIONAR UN PRODUCTO FINANCIERO</OPTION>
								<OPTION  VALUE=''>---------------------------</OPTION>
								</SELECT>";


					
					//HTML
					$html  ="<BR /> 
								<H3 class='ui-widget-header'>SOLICITUD FOLIO # ".$ID_SOLI."</H3>
								<INPUT ID='TIPO_CREDITO' TYPE='HIDDEN' VALUE='".$TIPO_CREDIT."'>
								<INPUT ID='PLAZO_AUX'    TYPE='HIDDEN' VALUE='".$Plazo."'>
								<INPUT ID='ID_SOLI'      TYPE='HIDDEN' VALUE='".$ID_SOLI."'>
							<BR />";
					$html .="<TABLE BORDER='0px;' ALIGN='CENTER' WIDTH='95%'>
						 <TR HEIGHT='30px;'>
							<TH ALIGN='RIGHT' WIDTH='25%'>
								Monto:
							</TH>
							<TD ALIGN='LEFT'>
								<INPUT ID='MONTO_SOLI' CLASS='SOLO_NUMEROS' TYPE='TEXT'  VALUE='".$Monto."'  STYLE='text-align: right;' >
							</TD>
						 </TR>

						 <TR	HEIGHT='30px;'>
							<TH ALIGN='RIGHT' >
								Producto financiero:
							</TH>
							<TD ALIGN='LEFT'>
								".$combo_prod."
							</TD>
						 </TR>

						 <TR	HEIGHT='30px;'>
							<TH ALIGN='RIGHT' >
								Plazo:
							</TH>
							<TD ALIGN='LEFT'>
								".$cmb_plazo." &nbsp; <SPAN ID='Label_Plazo'></SPAN>
							</TD>
						 </TR>";
					if($TIPO_CREDIT =='3')
						{
							$html .="
								 <TR	HEIGHT='30px;'>
									<TH ALIGN='RIGHT' WIDTH='25%'>
										Empresa:
									</TH>
									<TD ALIGN='LEFT'>
										<INPUT ID='Empresa_soli' CLASS='LEYEND' TYPE='TEXT' lang='ID_empresa' value='".$Nmb_empresa."' REDONLY='REDONLY'   STYLE='color: gray; font-style: italic;' NAME='Empresa_soli' SIZE='50'>
										<BUTTON ID='BTN_Empresa_soli' CLASS='CATALOG' TITLE='CATÁLOGO DE EMPRESAS.' STYLE='width:30px; height:20px;' >
										</BUTTON>
										<INPUT ID='ID_empresa' TYPE='HIDDEN' VALUE='".$ID_Empresa."'>
									</TD>
								 </TR>";
						}
						
					$html .="</TABLE>";

					$html .="<BR /> 
							<TABLE BORDER='0px;' ALIGN='CENTER' WIDTH='60%'>
								 <TR HEIGHT='30px;'>
									<TD ALIGN='CENTER' WIDTH='50%'>
										<BUTTON  ID='SAVE_ASIGNA'   STYLE='font-size:x-small;' > &nbsp;&nbsp; GURDAR PARÁMETROS </BUTTON>
									</TD>
									<TD ALIGN='CENTER'>
											".$BUTTON_CHECK."
									</TD>
								 </TR>
								</TABLE>";
								
					$html .="<BR />
							<LABEL ID='Label_result' > </LABEL>";
	}
	else
		$html  ="<BR />	<BR /> <BR /> <BR /> <BR /> <BR /> <H3 class='ui-widget-header' STYLE='color:BLACK; font-size:medium;' >&nbsp;<BR />LA SOLICITUD FOLIO # ".$ID_SOLI." <BR /> <BR />SE ENCUENTRA AHORA EN EL MÓDULO DE: &nbsp; \"MESA DE CONTROL\" <BR />&nbsp;</H3>";
	
   echo $html;
}


if( isset($UPDATE_SOLI) && !empty($UPDATE_SOLI) && isset($ID_PROD) && !empty($ID_PROD)  && isset($MONTO) && !empty($MONTO)   && isset($PLAZO) && !empty($PLAZO)  && isset($ID_SOLI) && !empty($ID_SOLI) && isset($ID_Empresa) && !empty($ID_Empresa) && isset($TIPO_CREDITO) && !empty($TIPO_CREDITO) )
{

	//SELECT SOLICITUD
		$SQL_CONS = "SELECT
							Status_solicitud AS STAT_SOLI
		              FROM  solicitud
						
		              WHERE
		                 ID_Solicitud = '".$ID_SOLI."' ";
		$rs_cons=$db->Execute($SQL_CONS);
		$Status_soli = $rs_cons->fields["STAT_SOLI"];
		

		if(($Status_soli !='ALTA CREDITO' && $Status_soli !='DISPOSICION - CREDITO' ) )
		{
			$NOMINA=($TIPO_CREDITO=='3')?("Y"):("N");
		
			$SQL_UPDATE = "UPDATE
									solicitud
							SET 
									ID_Producto 		= '".$ID_PROD."',
									Monto				= '".$MONTO."',
									Plazo				= '".$PLAZO."' ,
									ID_empresa			= '".$ID_Empresa."',
									Solicitud_expres 	= 'Y',
									Nomina				= '".$NOMINA."'
						WHERE
		                 ID_Solicitud = '".$ID_SOLI."' ";
			if($db->Execute($SQL_UPDATE))
				echo "TRUE";
			else
				echo "FALSE";
		}
		else
			echo "[LA SOLICITUD FUÉ AUTORIZADA, IMPOSIBLE GUARDAR LOS CAMBIOS]";

}


if( isset($CHECK_SOLI) && !empty($CHECK_SOLI) && isset($ID_SOLI) && !empty($ID_SOLI) )
{
	//SELECT SOLICITUD
		$SQL_CONS = "SELECT
							ID_Producto,
							Monto,
							Plazo,
							solicitud.ID_empresa,
							Status_solicitud,
							ID_Tipocredito,
							Solicitud_expres
		              FROM  solicitud
						WHERE
		                 ID_Solicitud = '".$ID_SOLI."' ";
		$rs_cons=$db->Execute($SQL_CONS);
		list($ID_PROD,$MONTO,$PLAZO,$ID_EMPRESA,$STATUS_SOLI,$ID_TIPO_CREDIT,$SOLI_EXPRES)=$rs_cons->fields;

		$Valida_soli='FALSE';
		$MONTO=floatval($MONTO);

		if($ID_TIPO_CREDIT == '3')
			if(!empty($ID_PROD) && !empty($MONTO) && !empty($PLAZO) && ($STATUS_SOLI !='ALTA CREDITO' && $STATUS_SOLI !='DISPOSICION - CREDITO' ) && ($SOLI_EXPRES =='Y') && (!empty($ID_EMPRESA))  )
			$Valida_soli='TRUE';
		
		if($ID_TIPO_CREDIT == '1')
			if(!empty($ID_PROD) && !empty($MONTO) && !empty($PLAZO) && ($STATUS_SOLI !='ALTA CREDITO' && $STATUS_SOLI !='DISPOSICION - CREDITO' ) && ($SOLI_EXPRES =='Y')  )
			 $Valida_soli='TRUE';
		
		echo  $Valida_soli;
}


if( isset($ASIGN_CTE) && !empty($ASIGN_CTE) && isset($ID_SOLI) && !empty($ID_SOLI) )
{
	//SELECT SOLICITUD
		$SQL_CONS = "SELECT
							ID_Producto,
							Monto,
							Plazo,
							solicitud.ID_empresa,
							Status_solicitud,
							ID_Tipocredito,
							Solicitud_expres,
							Ingresos_soli
		              FROM  solicitud
						WHERE
		                 ID_Solicitud = '".$ID_SOLI."' ";
		$rs_cons=$db->Execute($SQL_CONS);
		list($ID_PROD,$MONTO,$PLAZO,$ID_EMPRESA,$STATUS_SOLI,$ID_TIPO_CREDIT,$SOLI_EXPRES,$INGRESOSO_SOLI)=$rs_cons->fields;
		
		$NOMINA=($ID_TIPO_CREDIT=='3')?("Y"):("N");
									
		$SQL_UPDATE_SOLI = "UPDATE	solicitud
								SET			Nomina ='".$NOMINA."'
								WHERE		ID_Solicitud = '".$ID_SOLI."' ";
		$db->Execute($SQL_UPDATE_SOLI);

												
				$CAPACIDAD_PAGO     = $INGRESOSO_SOLI / 4;
				
				$OBSERVACIONES		 = "SOLICITUD EXPRÉS - GENERADA POR EL USUARIO (".$ID_USR."), EN LA SUCURSAL (".$ID_SUC."), TIPO CRÉDITO (".$ID_TIPO_CREDIT.") ";
				
				$SQL_CTE = "INSERT INTO clientes( ID_Tipocredito,
												ID_Solicitud,
												Folio,
												Linea_credito,
												Capacidad_pago,
												Status,
												Autorizacion,
												ID_Usr,
												Obsevaciones,
												ID_Categoria )
									  VALUES( '".$ID_TIPO_CREDIT."',
									          '".$ID_SOLI."',
									          '".$ID_SOLI."',
									          '".$MONTO."',
									          '".$CAPACIDAD_PAGO."',
									          'Activo',
									          NOW(),
									          '".$ID_USR."',
									          '".$OBSERVACIONES."',
									          '1' )";
				$db->Execute($SQL_CTE);
				$ID_CTE = $db->_insertid();

				if( $ID_CTE > 0 )
				{
					$SQL_UPDATE = "UPDATE
									clientes
									SET
										Num_cliente = '".$ID_CTE."'
								WHERE	ID_Cliente  = '".$ID_CTE."' ";

					if($db->Execute($SQL_UPDATE))
					{
										$SQL_INS_CTE = "INSERT INTO clientes_datos(	ID_Solicitud,
																					ID_Promotor,
																					ID_Sucursal,
																					ID_Tipocredito,
																					ID_Producto,
																					ID_empresa,
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
																					Telefono_empresa  )
																SELECT
																					solicitud.ID_Solicitud,
																					solicitud.ID_Promotor,
																					solicitud.ID_Sucursal,
																					solicitud.ID_Tipocredito,
																					solicitud.ID_Producto,
																					solicitud.ID_empresa,
																					'".$ID_CTE."',
																					'".$ID_CTE."',
																					solicitud.Monto,
																					solicitud.Plazo,
																					solicitud.Nombre,
																					solicitud.NombreI,
																					solicitud.Ap_paterno,
																					solicitud.Ap_materno,
																					solicitud.Fecha_nacimiento,
																					solicitud.SEXO,
																					solicitud.RFC,
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
																					cat_convenio_empresas.Telefono
																FROM		solicitud
																LEFT JOIN cat_convenio_empresas ON cat_convenio_empresas.ID_empresa = solicitud.ID_empresa
																WHERE		ID_Solicitud = '".$ID_SOLI."' ";

											if($db->Execute($SQL_INS_CTE))
											{
												
												
												$SQL_UPDATE = "UPDATE	solicitud
																	SET			Status_solicitud = 'COMITE CREDITO',
																				Solicitud_expres = 'Y'
																	WHERE		ID_Solicitud = '".$ID_SOLI."' ";
												$db->Execute($SQL_UPDATE);

												$sql_usr ="SELECT UCASE(CONCAT(Nombre,' ',AP_Paterno,' ',AP_Materno)) AS USR 
															FROM usuarios 
															WHERE ID_User= '".$ID_USR."' ";
												$rs_usr  = $db->Execute($sql_usr); 
												$Responsable=$rs_usr->fields["USR"];
												
												$Sql_suceso ="INSERT INTO solicitud_sucesos (ID_Solicitud,Fecha,Atendio,Status,Suceso)
															  VALUES('".$ID_SOLI."',NOW(),'".$Responsable."','COMITE CREDITO','COMITÉ CREDITO COMPLETO - SOLICITUD EXPRÉS - GENERADA POR EL USUARIO (".$ID_USR."), EN LA SUCURSAL (".$ID_SUC."), TIPO CRÉDITO (".$ID_TIPO_CREDIT.")')";	
												$db->Execute($Sql_suceso);
						
												echo "TRUE";
											}
											else
												echo "FALSE";
											
						}//FIN if($db->Execute($SQL_UPDATE)
						else
							echo "FALSE";

			}//FIN if( $ID_CTE > 0 )
			else
			 echo "FALSE";
}



?>
