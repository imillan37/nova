<?php
/****************************************/
/*Fecha:23-Noviembre-2011
/*Autor: Tonathiu C√°rdenas
/*Descripci√≥n:Grid de agregados solicitudes
/*Dependencias:originacion_credito.php?ID_SOLI  & Tipo_credit   
/****************************************/

//Librer√≠as
require($DOCUMENT_ROOT."/rutas.php");
require("../../../../sucursal/promocion/js/jquery_links.php");   								//LIBRER√çAS DE JQUERY


?>
<link REL="stylesheet" HREF="<?=$shared_scripts?>/jquery_ui/development-bundle/themes/cupertino/jquery.ui.all.css">
<link REL="stylesheet" HREF="<?=$shared_scripts?>/jquery_ui/development-bundle/demos/demos.css">

<?php
//Inicio conexiÛn
$db = ADONewConnection(SERVIDOR);
$db->Connect(IP,USER,PASSWORD,NUCLEO);
//Fin Conexi√≥n

/**************************FUNCIONES*******************************************/
function cliente_nombre($id_soli,$Tipo_credit,$ID_Tiposolicitud)
{
 global $db;

	if($Tipo_credit < 4 )
	{
		$sql_cons ="SELECT
							CONCAT(Nombre,' ',NombreI,' ',AP_Paterno,' ',AP_Materno) AS CTE
		             FROM solicitud
						WHERE ID_Solicitud ='".$id_soli."' ";
		$rs_cons = $db->Execute($sql_cons);
	}
	else
	{
				$Sql_reg="SELECT
									ID_Regimen	AS REG
							FROM
									cat_tipo_credito_regimen
							WHERE ID_Tipo_regimen = '".$ID_Tiposolicitud."' ";
				$rs_reg=$db->Execute($Sql_reg);
				$TIPO_REGIMEN=$rs_reg->fields["REG"];

				if($TIPO_REGIMEN == 'PFAE')
				   $NOMBRE_CTE ="CONCAT(Nombre_pfae,' ',NombreI_pfae,' ',Ap_paterno_pfae,' ',Ap_materno_pfae) AS CTE";
				 else
					$NOMBRE_CTE ="Razon_social		AS CTE";
					
				$Sql_cons="SELECT
								".$NOMBRE_CTE."
							FROM 
									solicitud_pmoral
							WHERE ID_Solicitud = '".$id_soli."' ";
							
				$rs_cons=$db->Execute($Sql_cons);

	}

   return $rs_cons->fields["CTE"];
}


function get_tipo_credit_soli($ID_SOLI,$ID_GPO,$Tipo_credit)
{
 global $db;

	if($Tipo_credit ==1 || $Tipo_credit ==3 )
	{
			$Sql_cons="SELECT
							ID_Tipo_regimen		AS ID_TIPO_SOLI,
							ID_Tipocredito		AS ID_TIPO_CREDIT,
							ID_empresa			AS ID_EMPRESA
							
						FROM 
								solicitud
						WHERE ID_Solicitud = '".$ID_SOLI."' ";
						
			$rs_cons=$db->Execute($Sql_cons);
	}
	else if($Tipo_credit ==2)
	{
		
			$Sql_cons = "SELECT
							ID_Tipo_regimen		AS ID_TIPO_SOLI,
							ID_Tipocredito		AS ID_TIPO_CREDIT,
							ID_empresa			AS ID_EMPRESA
					FROM grupo_solidario_integrantes
						 LEFT JOIN solicitud       ON solicitud.ID_Solicitud = grupo_solidario_integrantes.ID_Solicitud
						 LEFT JOIN grupo_solidario ON grupo_solidario_integrantes.ID_grupo_soli = grupo_solidario.ID_grupo_soli
													AND grupo_solidario_integrantes.Ciclo_gpo = grupo_solidario.Ciclo_gpo
													
			   WHERE
						grupo_solidario_integrantes.ID_grupo_soli = '".$ID_GPO."'
						AND grupo_solidario_integrantes.Ciclo_renovado='N'  ";
			$rs_cons=$db->Execute($Sql_cons);
			
	}
	else if($Tipo_credit ==4	||	$Tipo_credit ==5 )
	{

			$Sql_cons="SELECT
							ID_Tipo_regimen		AS ID_TIPO_SOLI,
							ID_Tipocredito		AS ID_TIPO_CREDIT,
							Razon_social		AS RZN_SOC
							#ID_empresa			AS ID_EMPRESA
							
						FROM 
								solicitud_pmoral
						WHERE ID_Solicitud = '".$ID_SOLI."' ";
						
			$rs_cons=$db->Execute($Sql_cons);
   }
 
  $ARR_TIPOS = array('TIPO_SOLICITUD'=>$rs_cons->fields["ID_TIPO_SOLI"],'TIPO_CREDITO'=>$rs_cons->fields["ID_TIPO_CREDIT"],"ID_EMPRESA"=>$rs_cons->fields["ID_EMPRESA"],"RAZON_SOCIAL"=>$rs_cons->fields["RZN_SOC"]);

 return $ARR_TIPOS;
}

function get_id_aval($ID_SOLI,$TIPO_PERSO)
{
	global $db;


	$Sql_cons="SELECT
						ID_Persona		AS ID_PERSO
				FROM
						solicitud_aval_cosol
				WHERE
						ID_Solicitud  = '".$ID_SOLI."'
					AND Tipo_relacion = '".$TIPO_PERSO."' ";
	$rs_cons=$db->Execute($Sql_cons);

	return $rs_cons->fields["ID_PERSO"];
}


function get_vinculos($Tipo_credit,$ID_SOLI,$ID_Tiposolicitud)
{
	global $db,$img_path;

	$Sql_cons="SELECT 
					ID_Proceso AS ID
				FROM cat_tipo_credito_proceso
				WHERE ID_Tipo_regimen ='".$ID_Tiposolicitud."'
					AND Orden ='1'";
	$rs_cons=$db->Execute($Sql_cons);
	$ID_proceso=$rs_cons->fields["ID"];

	$Sql_subproc="SELECT 
					ID_Subproceso    AS ID_SUB,
					Subproceso	     AS  DESCP
				FROM cat_tipo_credito_subproceso
				WHERE ID_Proceso ='".$ID_proceso."'
					AND Status ='Activo'
					AND Requerido ='Y'
				ORDER BY Orden";
	$rs_subproc=$db->Execute($Sql_subproc);


    $Docs_tipo_credito=($Tipo_credit=='1')?('pfisica'):('pfisica_solidaria');
    $Docs_tipo_credito=($Tipo_credit=='3')?('pfisica_actemp'):($Docs_tipo_credito);
    $Docs_tipo_credito=($Tipo_credit=='4')?('pmoral'):($Docs_tipo_credito);
    $Docs_tipo_credito=($Tipo_credit=='5')?('pempresarial'):($Docs_tipo_credito);


	$str_vinculos="";
									
	While(!$rs_subproc->EOF)
		{
			$str_vinculos.="
							<LI class='VINCULOS' ID='".$rs_subproc->fields["ID_SUB"]."'  STYLE='text-align:left; '>
									<IMG SRC='".$img_path."toggle-small-expand.png' ID='IMG_LINK_".$rs_subproc->fields["ID_SUB"]."' STYLE='vertical-align:middle; position:relative; float:left;' TITLE='DESPLEGAR OPCIONES...'  />".$rs_subproc->fields["DESCP"]."
							</LI>";

							if($rs_subproc->fields["DESCP"] =='DOCUMENTOS')
							{
									$str_vinculos.="<TABLE CELLSPACING='0' STYLE='display:none;' ID='OPTION_".$rs_subproc->fields["ID_SUB"]."' ALIGN='RIGHT' BORDER='0px' WIDTH='90%'>
													<TR>
														<TD STYLE='text-align:left;'>
															<LI lang='../../../../compartidos/soli_docs.php?Param1=ID_Solicitud&Param2=".$ID_SOLI."&T_credit=".$Docs_tipo_credito."&ID_Tiposolicitud=".$ID_Tiposolicitud."&noheader=1' class='OPTIONS_".$rs_subproc->fields["ID_SUB"]."' STYLE='color:black;' ID='DIGITLZ_SOLI' TITLE='Digitalizar documentos...'>
															&#187; DIGITALIZAR</LI>

															<LI lang='../../../../compartidos/soli_docsII.php?Param1=ID_Solicitud&Param2=".$ID_SOLI."&T_credit=".$Docs_tipo_credito."&ID_Tiposolicitud=".$ID_Tiposolicitud."&noheader=1' class='OPTIONS_".$rs_subproc->fields["ID_SUB"]."' STYLE='color:black;' ID='VER_DOCS_SOLI' TITLE='Vizualizar documentos...'>
															&#187; VER DOCUMENTOS</LI>
														</TD>
													</TR>
													</TABLE>";
							}
							else
							{
								
								/*****AVALES******/
								if($rs_subproc->fields["DESCP"] =='AVAL')
								{
									$ID_AVAL 	 		=    get_id_aval($ID_SOLI,'AVAL');
									$Pag_captura 		=	($rs_subproc->fields["DESCP"] =='AVAL')?("../../../../sucursal/promocion/avales/captura_aval.php?noheader=1&ID_Solicitud=".$ID_SOLI.""):("");
									$Pag_editar  		=	($rs_subproc->fields["DESCP"] =='AVAL')?("../../../../sucursal/promocion/avales/captura_aval.php?noheader=1&id=".$ID_AVAL):("");
									$Pag_vista	 		=   ($rs_subproc->fields["DESCP"] =='AVAL')?("../../../../sucursal/promocion/avales/ver_aval.php?noheader=1&id=".$ID_AVAL):("");

									$CLASS_VINCULO_CAPTURA		=   ( empty($ID_AVAL))?(''):('NO_PERMIT_CAPT_AVAL');
									$CLASS_VINCULO_EDITA		=   (!empty($ID_AVAL))?(''):('NO_PERMIT_EDIT_VIEW_AVAL');
									$ID_CAPTURA        = 'AVAL_CAPTURA';
									$ID_EDITA		   = 'AVAL_EDITA';
									$ID_VISTA		   = 'AVAL_VISTA';
								}
								/****************/

								/*****COSOLES******/
								if($rs_subproc->fields["DESCP"] =='COSOLICITANTE')
								{
									$ID_COSOL 	 		=    get_id_aval($ID_SOLI,'COSOL');
									$Pag_captura 		=	($rs_subproc->fields["DESCP"] =='COSOLICITANTE')?("../../../../sucursal/promocion/cosolicitantes/captura_cosolicitante.php?noheader=1&ID_Solicitud=".$ID_SOLI.""):("");
									$Pag_editar  		=	($rs_subproc->fields["DESCP"] =='COSOLICITANTE')?("../../../../sucursal/promocion/cosolicitantes/captura_cosolicitante.php?noheader=1&id=".$ID_COSOL):("");
									$Pag_vista	 		=   ($rs_subproc->fields["DESCP"] =='COSOLICITANTE')?("../../../../sucursal/promocion/cosolicitantes/ver_cosolicitante.php?noheader=1&id=".$ID_COSOL):("");

									$CLASS_VINCULO_CAPTURA		=   ( empty($ID_COSOL))?(''):('NO_PERMIT_CAPT_COSOL');
									$CLASS_VINCULO_EDITA		=   (!empty($ID_COSOL))?(''):('NO_PERMIT_EDIT_VIEW_COSOL');

									$ID_CAPTURA        = 'COSOL_CAPTURA';
									$ID_EDITA		   = 'COSOL_EDITA';
									$ID_VISTA		   = 'COSOL_VISTA';

								}
								/****************/


									$str_vinculos.="<TABLE CELLSPACING='0' STYLE='display:none;' ID='OPTION_".$rs_subproc->fields["ID_SUB"]."' ALIGN='RIGHT' BORDER='0px' WIDTH='90%'>
													<TR>
														<TD STYLE='text-align:left;'>
															<LI ID='".$ID_CAPTURA."' lang='".$Pag_captura."' class='OPTIONS_".$rs_subproc->fields["ID_SUB"]."  ".$CLASS_VINCULO_CAPTURA."	' STYLE='color:black;'>
															&#187; CAPTURAR</LI>

															<LI ID='".$ID_EDITA."' lang='".$Pag_editar."' class='OPTIONS_".$rs_subproc->fields["ID_SUB"]."   ".$CLASS_VINCULO_EDITA."   ' STYLE='color:black;'>
															&#187; EDITAR</LI>

															<LI ID='".$ID_VISTA."' lang='".$Pag_vista."' class='OPTIONS_".$rs_subproc->fields["ID_SUB"]."    ".$CLASS_VINCULO_EDITA."   ' STYLE='color:black;'>
															&#187; CONSULTAR</LI>
															
														</TD>
													</TR>
													</TABLE>";

							}

		  $rs_subproc->MoveNext();
		}

	

 return $str_vinculos;
}


function get_procesos_tcredito($Tipo_credit,$ID_SOLI,$ID_Tiposolicitud)
{
	global $db,$img_path;

	$Sql_cons="SELECT
				ID_Proceso		AS ID_PROC,
				Proceso			AS DESCP
			FROM	cat_tipo_credito_proceso
			WHERE ID_Tipo_regimen = '".$ID_Tiposolicitud."'
			 AND Status = 'Activo'
				ORDER BY Orden";
	$rs_cons=$db->Execute($Sql_cons);


	$str_procesos="";
									
	While(!$rs_cons->EOF)
		{
					if($rs_cons->fields["DESCP"] == 'VALIDACION CLIENTE' || $rs_cons->fields["DESCP"] == 'CHECK LIST ')
					{
						$IMG_PROCSS = ($rs_cons->fields["DESCP"] == 'VALIDACION CLIENTE')?("tick-circle-frame.png"):("add.png");
						$CLASS_PROC = ($rs_cons->fields["DESCP"] == 'CHECK LIST ')?("PROCESOS_VALIDATE"):("PROCESOS");
						
						$str_procesos.="<li lang='../../../../compartidos/vista_solicitudes_credito_datos.php?Param1=ID_Solicitud&Param2=".$ID_SOLI."&Tipo_credito=".$Tipo_credit."&noheader=1&ID_GPO=".$ID_GPO."&ID_Tiposolicitud=".$ID_Tiposolicitud."' ID='".$ID_SOLI."TIPO_SOLI_".$ID_Tiposolicitud."' class='".$CLASS_PROC."'  STYLE='text-align:left;'  TITLE='".$rs_cons->fields["DESCP"]."...'>
						<IMG SRC='".$img_path."".$IMG_PROCSS."' STYLE='vertical-align:middle; position:relative; float:left;'  />&nbsp;&nbsp;".$rs_cons->fields["DESCP"]."</li>";
					}


		  $rs_cons->MoveNext();
		}

	

 return $str_procesos;

}

function get_count_integrantes($ID_GPO)
{
	global $db;
	
  $SQL_CONT="SELECT
					COUNT(grupo_solidario_integrantes.ID_grupo_soli)			AS INTEG_CONT
					
			FROM
				grupo_solidario
				LEFT JOIN grupo_solidario_integrantes		ON grupo_solidario.ID_grupo_soli		= grupo_solidario_integrantes.ID_grupo_soli
																			AND grupo_solidario.Ciclo_gpo    = grupo_solidario_integrantes.Ciclo_gpo
																			AND grupo_solidario_integrantes.Ciclo_renovado ='N'
																			AND grupo_solidario_integrantes.`Status`='Activo'

			WHERE grupo_solidario_integrantes.ID_grupo_soli = '".$ID_GPO."'
			GROUP BY grupo_solidario.ID_grupo_soli ";
  $rs_cont= $db->Execute($SQL_CONT);

	return  $rs_cont->fields["INTEG_CONT"];
}


function get_integrantes_gpo($ID_GPO)
{
 global $db;
 global $img_path;

	//$CLASS_EDIT	= (check_solicitud_solidario($ID_GPO) == 'FALSE')?("NOT_EDIT_SOLI"):($CLASS_EDIT);
 
	$Sql_cons = "SELECT
					grupo_solidario_integrantes.ID_Solicitud																AS ID_SOLI,
				    (Concat(solicitud.Nombre,' ',solicitud.NombreI,' ',solicitud.Ap_paterno,' ',solicitud.Ap_materno)) 		AS NMB_CTE,
					solicitud.Status																						AS STAT,
					solicitud.Status_solicitud																				AS STAT_SOLI,
					UCASE(grupo_solidario.Nombre)																			AS NMB_GPO,
					ID_Tipo_regimen																							AS ID_TIPO_SOLI,
					grupo_solidario_integrantes.Status																		AS STAT_INTG
			FROM grupo_solidario_integrantes
				 LEFT JOIN solicitud       ON solicitud.ID_Solicitud = grupo_solidario_integrantes.ID_Solicitud
				 LEFT JOIN grupo_solidario ON grupo_solidario_integrantes.ID_grupo_soli = grupo_solidario.ID_grupo_soli
											AND grupo_solidario_integrantes.Ciclo_gpo = grupo_solidario.Ciclo_gpo
											
       WHERE
				grupo_solidario_integrantes.ID_grupo_soli = '".$ID_GPO."'
				AND grupo_solidario_integrantes.Ciclo_renovado='N'
		ORDER BY NMB_CTE";
	$rs_cons=$db->Execute($Sql_cons);

	$CONT_INTG= get_count_integrantes($ID_GPO);
	
	$str_vinculos ="<SPAN  STYLE='font-size:x-small; font-weight:bold; text-decoration:underline' > ".$rs_cons->fields["NMB_GPO"]." </SPAN>	<BR /> 
					<SPAN  STYLE='font-size:xx-small; font-weight:bold;' > INTEGRANTES ACTIVOS: ".$CONT_INTG." </SPAN> <BR />  <BR />
					<INPUT TYPE='HIDDEN' ID='NMB_GPO' 			VALUE='".$rs_cons->fields["NMB_GPO"]."' />";

	While(!$rs_cons->EOF)
		{

			$COLOR_INTG	=($rs_cons->fields["STAT_INTG"] == 'Activo' )?("black"):("red");
			
			$str_vinculos.="
							<LI class='INTG_GPO		".$CLASS_EDIT." ' LANG='../../../../compartidos/vista_solicitudes_credito.php?Param1=ID_Solicitud&Param2=".$rs_cons->fields["ID_SOLI"]."&Tipo_credito=2&noheader=1&ID_GPO=".$ID_GPO."&ID_Tiposolicitud=".$rs_cons->fields["ID_TIPO_SOLI"]."' ID='SOLI_".$rs_cons->fields["ID_SOLI"]."_GPO_SOLI_".$ID_GPO."'   STYLE='font-size:xx-small; text-align:left; color:".$COLOR_INTG.";' TITLE='".$rs_cons->fields["STAT_INTG"]."'>
									 <IMG SRC='".$img_path."user.png' STYLE='vertical-align:middle; position:relative; float:left; width:10px; height:10px; '  /> &nbsp; ".$rs_cons->fields["NMB_CTE"]."
							</LI>";
		  $rs_cons->MoveNext();
		}

 return $str_vinculos;
}

function get_soli_renovadas($ID_SOLI,$TIPO_CREDITO)
{

	global $db;

	$TABLA_CTE 		= ($TIPO_CREDITO < 4)?('clientes_datos'):('clientes_datos_pmoral');
	$TABLA_RENUEVA  = ($TIPO_CREDITO < 4)?('solicitud_renovadas'):('solicitud_pmoral_renovadas');
	
			$SQL_NUM_CTE="SELECT
								Num_cliente AS NUM_CTE
						FROM
							".$TABLA_CTE."
						WHERE
								ID_Solicitud = '".$ID_SOLI."' ";
			$rs_num_cte=$db->Execute($SQL_NUM_CTE);
			$NUM_CLIENTE= $rs_num_cte->fields["NUM_CTE"];
		
		if(!empty($NUM_CLIENTE))
		{
				$SQL_CONS="SELECT
									COUNT(ID_Solicitud) AS CUANTOS
							FROM
								".$TABLA_RENUEVA."
							WHERE
									Num_cliente = '".$NUM_CLIENTE."' ";
				$rs_cons=$db->Execute($SQL_CONS);

				return $rs_cons->fields["CUANTOS"];
		}
		else
			return 0;
}

function check_solicitud($ID_SOLI,$TIPO_CREDITO)
{
	global $db;

	$NUM_SOLI_RENOV = get_soli_renovadas($ID_SOLI,$TIPO_CREDITO);

	if($TIPO_CREDITO < 4)
	{
		$SQL_CONS="SELECT
							COUNT(id_factura) AS CUANTOS
					FROM
						solicitud
						INNER JOIN clientes_datos ON solicitud.ID_Solicitud 		= 	clientes_datos.ID_Solicitud
						INNER JOIN fact_cliente	  ON clientes_datos.Num_cliente		= 	fact_cliente.num_cliente
					WHERE
							solicitud.ID_Solicitud = '".$ID_SOLI."' ";
		$rs_cons=$db->Execute($SQL_CONS);


		
	}
	else
	{
		$SQL_CONS="SELECT
							COUNT(id_factura) AS CUANTOS
					FROM
						solicitud_pmoral
						INNER JOIN clientes_datos_pmoral ON solicitud_pmoral.ID_Solicitud 		    = 	clientes_datos_pmoral.ID_Solicitud
						INNER JOIN fact_cliente	  		 ON clientes_datos_pmoral.Num_cliente		= 	fact_cliente.num_cliente
					WHERE
							solicitud_pmoral.ID_Solicitud = '".$ID_SOLI."' ";
		$rs_cons=$db->Execute($SQL_CONS);

	}
		$VALIDA_CTE = ( $rs_cons->fields["CUANTOS"] == $NUM_SOLI_RENOV )?(0):(1);
		//$VALIDA_CTE = ( $rs_cons->fields["CUANTOS"] > $NUM_SOLI_RENOV )?(0):($VALIDA_CTE);
		
return $VALIDA_CTE;
}

function check_solicitud_solidario($ID_GPO)
{
	global $db;



	$SQL_CONS="SELECT
						ID_Tipocredito		AS TIPO_CREDIT,
						ID_grupo_soli		AS GPO_SOLI
				FROM
						solicitud
				WHERE
						ID_grupo_soli	= '".$ID_GPO."' ";
	$rs_cons=$db->Execute($SQL_CONS);

	if($rs_cons->fields["TIPO_CREDIT"] == '2' )
	{
		$SQL_GPO="SELECT
							Status_grupo	as ESTAT
					FROM
							grupo_solidario
					WHERE 
							ID_grupo_soli ='".$rs_cons->fields["GPO_SOLI"]."' ";
		$rs_gpo=$db->Execute($SQL_GPO);

		if( ($rs_gpo->fields["ESTAT"] == 'PROCESO INTEGRACION') || ($rs_gpo->fields["ESTAT"] == 'PROCESO INTEGRACION - RENOVACION') )
			$VALIDA_GPO = 'TRUE';
		else
			$VALIDA_GPO = 'FALSE';

	}
	else
			$VALIDA_GPO = 'TRUE';

			
	return $VALIDA_GPO;

}
/************************************************************************/



//JAVASCRIPT Y AJAX
echo "<SCRIPT TYPE='TEXT/JAVASCRIPT'  SRC='interface.js'></SCRIPT>";

?>
<!--¡REA CSS-->
<LINK REL="STYLESHEET" HREF="base.css" TYPE="TEXT/CSS" MEDIA="PRINT, PROJECTION, SCREEN">
<?php

	/****VALIDACIONES PREVIAS******/
	$Edita_soli=($Tipo_credit==1)?("solicitudes_individual/edita_solicitud_indiv.php"):("solicitudes_solidario/edita_solicitud_solidario.php");
	$Edita_soli=($Tipo_credit==3)?("solicitudes_nomina/edita_solicitud_nomina.php"):($Edita_soli);
	$Edita_soli=($Tipo_credit==4)?("solicitudes_pmoral/edita_solicitud_pmoral.php"):($Edita_soli);
	$Edita_soli=($Tipo_credit==5)?("solicitudes_empresarial/edita_solicitud_empresarial.php"):($Edita_soli);

	//$CLASS_EDIT	= (check_solicitud($ID_SOLI,$Tipo_credit) > 0)?("NOT_EDIT_SOLI"):("EDIT_SOLI");

	//if($Tipo_credit == '2')
		//$CLASS_EDIT	= (check_solicitud_solidario($ID_GPO) == 'FALSE')?("NOT_EDIT_SOLI"):($CLASS_EDIT);

	$ARR_TIPOS		 	=	get_tipo_credit_soli($ID_SOLI,$ID_GPO,$Tipo_credit);
	$STR_Vinculos		=	get_vinculos($Tipo_credit,$ID_SOLI,$ARR_TIPOS['TIPO_SOLICITUD']);
	$STR_Procesos		=	get_procesos_tcredito($Tipo_credit,$ID_SOLI,$ARR_TIPOS['TIPO_SOLICITUD']);
	$Nombre_cte			=	($Tipo_credit!='2')?(cliente_nombre($ID_SOLI,$Tipo_credit,$ARR_TIPOS['TIPO_SOLICITUD'])):("");

	$ID_EMPRESA=$ARR_TIPOS["ID_EMPRESA"];
	/*****************************/
	
	 $Plantilla="
				   <DIV ID='menu'>
					</DIV>";
		if($Tipo_credit=='2'  )
		{
		$Plantilla.="<DIV ID='navegar'>
					   <BUTTON ID='SOLI_ANT'  STYLE='cursor:pointer; height:20px;'  />Anterior</BUTTON>&nbsp;
					   <BUTTON ID='SOLI_NEXT' STYLE='cursor:pointer; height:20px;'  />Siguiente</BUTTON>
					</DIV>";
		}

		$Pagina_redirect=($Tipo_credit=='2' && empty($SHOW_INTG) && empty($ID_SOLI) )?("home_grupos_solidarios.php?ID_GPO=".$ID_GPO.""):("../../../../compartidos/vista_solicitudes_credito.php?Param1=ID_Solicitud&Param2=".$ID_SOLI."&Tipo_credito=".$Tipo_credit."&noheader=1&ID_GPO=".$ID_GPO."&ID_Tiposolicitud=".$ARR_TIPOS['TIPO_SOLICITUD']."&ID_EMPRESA=".$ID_EMPRESA."");

			$Plantilla.="<INPUT TYPE='HIDDEN' ID='TIPO_CREDIT' 			VALUE='".$Tipo_credit."' />
						 <INPUT TYPE='HIDDEN' ID='PAGINA_ACTUAL' 		VALUE='".$Pagina_redirect."' />
						 <INPUT TYPE='HIDDEN' ID='ID_SOLI_ACTUAL' 		VALUE='' />
						 <INPUT TYPE='HIDDEN' ID='GPO_SOLI' 			VALUE='".$ID_GPO."' />
						 <INPUT TYPE='HIDDEN' ID='TIPO_SOLICITUD' 		VALUE='".$ARR_TIPOS['TIPO_SOLICITUD']."' />
						 <INPUT TYPE='HIDDEN' ID='ID_EMPRESA' 		    VALUE='".$ARR_TIPOS['ID_EMPRESA']."' />
						 
						<DIV ID='dialog-message' TITLE='AVISO S2CREDIT'  STYLE='DISPLAY:NONE;'></DIV>
						
						<DIV ID='lateralPanel'>";


		$Plantilla.="	<DIV ID='Soli_Section' CLASS='section'>SOLICITUD DE CR…DITO   </DIV>

							<UL>
								<SPAN STYLE='font-size:x-small; font-weight:bold;' ID='NMB_CTE' > ".$Nombre_cte." </SPAN>
								
								<li lang='../../../../compartidos/vista_solicitudes_credito.php?Param1=ID_Solicitud&Param2=".$ID_SOLI."&Tipo_credito=".$Tipo_credit."&noheader=1&ID_GPO=".$ID_GPO."&ID_Tiposolicitud=".$ARR_TIPOS['TIPO_SOLICITUD']."' class='active'  STYLE='text-align:left;' ID='VISTA_SOLI' TITLE='Vista solicitud...'>
								<IMG SRC='".$img_path."tick-circle-frame.png' STYLE='vertical-align:middle; position:relative; float:left;'  />&nbsp;&nbsp;VISUALIZAR</li>
								
								<li lang='../../../../sucursal/promocion/".$Edita_soli."?Param1=ID_Solicitud&Param2=".$ID_SOLI."&Tipo_credito=".$Tipo_credit."&noheader=1&ID_GPO=".$ID_GPO."&ID_Tiposolicitud=".$ARR_TIPOS['TIPO_SOLICITUD']."&ID_EMPRESA=".$ID_EMPRESA."' class='".$CLASS_EDIT."'  STYLE='text-align:left;' ID='EDITA_SOLI' TITLE='Editar solicitud...'>
								<IMG SRC='".$img_path."tick-circle-frame.png' STYLE='vertical-align:middle; position:relative; float:left;'  />&nbsp;&nbsp;EDITAR</li>

								<li lang='../../../../compartidos/historial_solicitudes_credito.php?Param1=ID_Solicitud&Param2=".$ID_SOLI."&Tipo_credito=".$Tipo_credit."&noheader=1&ID_Tiposolicitud=".$ARR_TIPOS['TIPO_SOLICITUD']."' class=''  STYLE='text-align:left;' ID='HIST_SOLI' TITLE='Historial de la solicitud...'>
								<IMG SRC='".$img_path."tick-circle-frame.png' STYLE='vertical-align:middle; position:relative; float:left;'  />&nbsp;&nbsp;HISTORIAL</li>
								
							</UL>";

		if($Tipo_credit=='2'  )
		{
			 $Integ_gpo = get_integrantes_gpo($ID_GPO);;
			$Plantilla.="  <DIV ID='GPO_Section' CLASS='section'>GRUPO SOLIDARIO</DIV>
								<UL >
									".$Integ_gpo."
								</UL>";
			
			
		}
		

							
		if($CHECK_LIST=='TRUE')
		{
			$Plantilla.="	  <DIV ID='Procesos_Section' CLASS='section'  >PROCESOS</DIV>
								<UL >
										".$STR_Procesos."
								</UL>";
		}
		

		$Plantilla.="	  <DIV ID='Agregados_Section' CLASS='section'>VINCULAR</DIV>
							<UL >
									".$STR_Vinculos."
							</UL>";


					
		$Plantilla.="
					</DIV>
					<A><DIV ID='lateralClick'><IMG ID='lateralClickImg' SRC='images/toggleRight.gif' /></DIV></A>
					
					<DIV ID='mainContent'>
						<IFRAME ID='iframe' WIDTH='100%' HEIGHT='100%' SRC='".$Pagina_redirect."' frameborder='0'>
					</DIV>
					<DIV ID='LOAD_ACTIONS' ></DIV>	";
					
	  echo $Plantilla;
?>
<SCRIPT>
/***************************************/

</SCRIPT>
