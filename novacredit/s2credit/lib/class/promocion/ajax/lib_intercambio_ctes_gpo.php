<?
/****************************************/
/*Fecha: 30/Noviembre/2011
/*Autor: Tonathiu Cárdenas
/*Descripción: GENERA LA TABLA DE SOLICITUDES
/*Dependencias: originacion_credito.php
/****************************************/

$exit = 0;
$noheader =1;
include($DOCUMENT_ROOT."/rutas.php");					//CORE CONSTANTES S2CREDIT
require($class_path."lib_nuevo_credito.php");			//LIBRERÍA ENRIQUE OBJETO TCUENTA

//Inicio conexión
$db = ADONewConnection(SERVIDOR);
$db->Connect(IP,USER,PASSWORD,NUCLEO);
//Fin Conexión

/*********FUNCTIONS**************/
function get_filtros_indiv()
{
	global $db;
	global $ID_SUC;

	$Filtros="";
	
	$Filtros.="
				<H3 CLASS='ui-widget-header'>CONSULTA DE SOLICITUDES.</H3>
				<BR />
				<TABLE  CELLSPACING='0' STYLE='' ALIGN='CENTER' BORDER='0px' WIDTH='99%'>
					
				<TR ALIGN='center' VALIGN='middle' BgCOLOR='#eef5e5'>
					<TD STYLE='text-align:right; font-weight:bold; font-size:small; width:25%;' >
						NOMBRE DEL CLIENTE : &nbsp;
					</TD>
					<TD STYLE='text-align:left; width:75%;'  >
						<INPUT TYPE='TEXT' NAME='nomb_cliente' ID='NMB_CTE' VALUE='".$nomb_cliente."'  SIZE='30' >
					</TD>
				</TR>

				<TR ALIGN='center' VALIGN='middle' BgCOLOR='#eef5e5'>
					<TD STYLE='text-align:right; font-weight:bold; font-size:small; width:25%;' >
						GRUPO SOLIDARIO : &nbsp;
					</TD>
					<TD STYLE='text-align:left; width:75%;'  >
						<INPUT TYPE='TEXT' NAME='gpo_solidario' ID='NMB_GPO' VALUE='".$gpo_solidario."'  SIZE='30' >
					</TD>
				</TR>
				
				<TR ALIGN='center' VALIGN='middle' BgCOLOR='#eef5e5'>
					<TD STYLE='text-align:right; font-weight:bold; font-size:small; width:25%;' >
						FOLIO : &nbsp;
					</TD>
					<TD STYLE='text-align:left; width:75%;'  >
						<INPUT TYPE='TEXT' NAME='folio_soli' ID='FOLIO_SOLI' VALUE='".$folio_soli."'  SIZE='10' CLASS='SOLO_NUMEROS'>
					</TD>
				</TR>

				<TR ALIGN='center' VALIGN='middle' BgCOLOR='#eef5e5'>
					<TD STYLE='text-align:right; font-weight:bold; font-size:small; width:25%;' >
						NUM. DE CLIENTE : &nbsp;
					</TD>
					<TD STYLE='text-align:left; width:75%;'  >
						<INPUT TYPE='TEXT' NAME='num_cte' ID='NUM_CTE' VALUE='".$num_cte."'  SIZE='10' CLASS='SOLO_NUMEROS' >
					</TD>
				</TR>

				<TR ALIGN='center' VALIGN='middle' BgCOLOR='#eef5e5'>
					<TD STYLE='text-align:right; font-weight:bold; font-size:small; width:25%;' >
						PROMOTOR : &nbsp;
					</TD>
					<TD STYLE='text-align:left; width:75%;'  >
						<INPUT TYPE='TEXT' NAME='promotor' ID='PROMO' VALUE='".$promotor."'  SIZE='30' >
					</TD>
				</TR>

				<TR ALIGN='center' VALIGN='middle' BgCOLOR='#eef5e5'>
					<TD STYLE='text-align:right; font-weight:bold; font-size:small; width:25%;' >
						TIPO DE CRÉDITO : &nbsp;
					</TD>
					<TD STYLE='text-align:left; width:75%; height:20px;'  >
						<B>CRÉDITO SOLIDARIO</B>
					</TD>
				</TR>
				
				
				<TR ALIGN='center' VALIGN='middle' BgCOLOR='#eef5e5'>
					
					<TD STYLE='text-align:right; font-weight:bold; font-size:small; width:25%;' >
						FECHA DE CAPTURA : &nbsp;
					</TD>
					<TD STYLE='text-align:left; width:75%;'  >
						<INPUT id='FECH_INI' class='datepicker' type='TEXT' lang='' value=''    style='width: 65px;' name='Fecha_ini'>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<SPAN STYLE='font-size:small; font-weight:bold;'>A LA FECHA :</SPAN>
						<INPUT id='FECH_FIN' class='datepicker' type='TEXT' lang='' value=''    style='width: 65px;' name='Fecha_fin'>
					</TD>
				</TR>

				
			</TABLE>
			<BR />
			<BUTTON  ID='SEARCH_SOLIS'   NAME='Buscar_soli' VALUE='TRUE' STYLE='font-size:x-small;' > &nbsp;&nbsp; BUSCAR SOLICITUDES. </BUTTON>
			<BR /><BR /><BR />
					<DIV ID='grid_seccion' > </DIV>";

	return $Filtros;
}



function get_pagination($SQL_PARAM,$GRID_ROWS,$CURRENT_PAGE,$TIPO_CREDIT)
{
	global $db;

	$rs_count=$db->Execute($SQL_PARAM);
	if($rs_count->fields["CUANTOS"] > $GRID_ROWS)
	  {
		$CONS_ROWS		=	$rs_count->fields["CUANTOS"];
		$NUM_OF_PAGES	=  ceil($CONS_ROWS / $GRID_ROWS);

		$DSBL_FIRST=(empty($CURRENT_PAGE))?("DISABLED"):("");
		$Bar_navegation="<DIV CLASS='demo'>
					       <!--<SPAN ID='toolbar' CLASS='ui-widget-header ui-corner-all'>-->
					       <BUTTON ID='PAGE_FIRST' 		CLASS='NAVIGATION_BUTTON PAGE_BUTTON'	".$DSBL_FIRST." VALUE='1'  STYLE='HEIGHT:25px; WIDTH:25px;' >&nbsp;</BUTTON>&nbsp;
					       <BUTTON ID='PAGE_PREVIOUS'   CLASS='NAVIGATION_BUTTON'	".$DSBL_FIRST." STYLE='HEIGHT:25px; WIDTH:25px;'>&nbsp;</BUTTON>&nbsp;&nbsp;&nbsp;&nbsp;";

		$CURRENT_PAGE=(empty($CURRENT_PAGE))?(1):($CURRENT_PAGE);
		$NUM_OF_PAGES=($NUM_OF_PAGES > 22)?(22):($NUM_OF_PAGES);

		for($index=1; $index <= $NUM_OF_PAGES; $index++ )
			{
				//$DSBL   = ($index==$CURRENT_PAGE)?("DISABLED"):("");
				$STYLE  = ($index==$CURRENT_PAGE)?("BORDER-COLOR:#f6ae38; BACKGROUND:#fff0a5; COLOR:#ce8b3a;"):("");
				$Bar_navegation.="<BUTTON ID='PAGE_".$index."' CLASS='NAVIGATION_BUTTON PAGE_BUTTON' VALUE='".$index."' ".$DSBL." STYLE='HEIGHT:25px; WIDTH:25px; ".$STYLE."' ".$STYLE."> ".$index."</BUTTON>&nbsp;";
			}

         $Bar_navegation.="&nbsp;&nbsp;&nbsp;
							<BUTTON ID='PAGE_NEXT' 		 CLASS='NAVIGATION_BUTTON'	            		 STYLE='HEIGHT:25px; WIDTH:25px;'>&nbsp;</BUTTON>&nbsp;
					        <BUTTON ID='PAGE_LAST'   	 CLASS='NAVIGATION_BUTTON PAGE_BUTTON'	VALUE='".$NUM_OF_PAGES."' STYLE='HEIGHT:25px; WIDTH:25px;'>&nbsp;</BUTTON>&nbsp;
							</DIV>
							<INPUT TYPE='HIDDEN' ID='NAVIGATION_TOTAL_VAL'  VALUE='".$GRID_ROWS."'>
							<INPUT TYPE='HIDDEN' ID='NUM_OF_PAGES'          VALUE='".$NUM_OF_PAGES."'>
							<INPUT TYPE='HIDDEN' ID='CURRENT_PAGES'         VALUE='1'>
							<INPUT TYPE='HIDDEN' ID='TIPO_CREDITO'          VALUE='".$TIPO_CREDIT."'>";

		echo $Bar_navegation;
	  }

}

//VALIDAR CLIENTES


/*************FIN FUNCTIONS******************/
function get_tipo_solicitud($ID_SOLI)
{
	global $db;
	
   $SQL_CONS="	SELECT 
					ID_Tipo_regimen	AS TIPO_SOLI
				FROM solicitud
				WHERE 	ID_Solicitud ='".$ID_SOLI."' ";
	$rs_cons=$db->Execute($SQL_CONS);

	return $rs_cons->fields["TIPO_SOLI"];
	
}

function get_nombre_cte($ID_SOLI)
{
	global $db;
	
   $SQL_CONS="	SELECT 
					Concat(solicitud.Nombre,' ',solicitud.NombreI,' ',solicitud.Ap_paterno,' ',solicitud.Ap_materno)	AS NMB_CTE
				FROM solicitud
				WHERE 	ID_Solicitud ='".$ID_SOLI."' ";
	$rs_cons=$db->Execute($SQL_CONS);

	return $rs_cons->fields["NMB_CTE"];
}

function get_num_cte($ID_SOLI,$ID_GPO)
{
	global $db;

	$SQL_CONS="SELECT
					clientes_datos.Num_cliente		AS NUM_CTE
				FROM clientes_datos
					INNER JOIN	clientes		ON	 clientes_datos.ID_Solicitud	=		 clientes.ID_Solicitud
					INNER JOIN	solicitud		ON	 clientes_datos.ID_Solicitud	=		solicitud.ID_Solicitud
				WHERE clientes_datos.ID_Solicitud = '".$ID_SOLI."'
						AND clientes_datos.ID_grupo_soli = '".$ID_GPO."' ";
	$rs_cons=$db->Execute($SQL_CONS);

	$NUM_CTE=(empty($rs_cons->fields["NUM_CTE"]))?("S/N"):($rs_cons->fields["NUM_CTE"]);

	return $NUM_CTE;

}


function get_num_cte_clientes($ID_SOLI)
{
	global $db;

	$SQL_CONS="SELECT
					clientes_datos.Num_cliente		AS NUM_CTE
				FROM clientes_datos
					INNER JOIN	clientes		ON	 clientes_datos.ID_Solicitud	=		 clientes.ID_Solicitud
					INNER JOIN	solicitud		ON	 clientes_datos.ID_Solicitud	=		solicitud.ID_Solicitud
				WHERE clientes_datos.ID_Solicitud = '".$ID_SOLI."' ";
	$rs_cons=$db->Execute($SQL_CONS);

	$NUM_CTE=(empty($rs_cons->fields["NUM_CTE"]))?(0):($rs_cons->fields["NUM_CTE"]);

	return $NUM_CTE;

}


function get_last_fact_cte($NUM_CTE)
{
	if(!empty($NUM_CTE))
	{
		global $db;
		$SQL_CONS="SELECT 
						MAX(id_factura)	AS 	ID_FACT
					FROM	fact_cliente
					WHERE num_cliente	='".$NUM_CTE."' ";
		$rs_cons=$db->Execute($SQL_CONS);

		$ID_FACT	= (empty($rs_cons->fields["ID_FACT"]))?("S/F"):($rs_cons->fields["ID_FACT"]);

		return $ID_FACT;
	}
	else
		return "S/F";

}

function check_adeudo_actual($ID_FACT)
{


	$bandera_adeudo ='FALSE';
	if(!empty($ID_FACT))
	{
			global $db;
			//Revisar si existe saldo vencido en el grupo
			$FECHA_HOY  =  date("Y-m-d");
			$obj = new TCUENTA($ID_FACT, $FECHA_HOY,'','',true);
			if($obj->adeudo_total > 0.004 )
				$bandera_adeudo ='TRUE';

		   $Adeudo_total=($bandera_adeudo=='TRUE' && $obj->adeudo_total != 0 )?($obj->adeudo_total):(0.00);
		   return $ARR_RESULT=array("ADEUDO"=>$bandera_adeudo,"SALDO"=>$Adeudo_total);
	 }
	 else
		   return $ARR_RESULT=array("ADEUDO"=>$bandera_adeudo,"SALDO"=>0.00);
}

function check_saldo_vencido($ID_FACT)
{
	$bandera_saldo ='FALSE';
	if(!empty($ID_FACT))
	{
			global $db;
			//Revisar si existe saldo vencido en el grupo
			$FECHA_HOY  =  date("Y-m-d");
			$obj = new TCUENTA($ID_FACT, $FECHA_HOY,'','',true);
			if($obj->SaldoGeneralVencido > 0.004 )
				$bandera_saldo ='TRUE';

		   $Saldo_vencido=($bandera_saldo=='TRUE' && $obj->SaldoGeneralVencido != 0 )?($obj->SaldoGeneralVencido):(0.00);
		   return $ARR_RESULT=array("SALDO_VENCIDO"=>$bandera_saldo,"SALDO"=>$Saldo_vencido);
	 }
	 else
		   return $ARR_RESULT=array("SALDO_VENCIDO"=>$bandera_saldo,"SALDO"=>0.00);
	
}

function check_pagos_realizados($ID_FACT)
{
	if(!empty($ID_FACT))
	{
		global $db;
		$SQL_CONS="SELECT
							Num_compra 	AS NUM_CMP
						FROM fact_cliente
						WHERE id_factura ='".$ID_FACT."' ";
		$rs_cons=$db->Execute($SQL_CONS);
		$NUM_CMP = $rs_cons->fields["NUM_CMP"];

		$SQL_CONS="SELECT
							COUNT(ID_Pago) AS CUANTOS
						FROM pagos
						WHERE Num_compra ='".$NUM_CMP."' ";
		$rs_cons=$db->Execute($SQL_CONS);

		$NUM_PAGOS=(empty($rs_cons->fields["CUANTOS"]))?(0):($rs_cons->fields["CUANTOS"]);
		
		return $NUM_PAGOS;
	}
	else
		return 0;
}

function check_grupo_destino($ID_GPO)
{
	global $db;

	
	$SQL_CONS="SELECT
				ID_grupo_soli		AS ID_GPO,
				Nombre				AS NMB,
				Status_grupo		AS STAT_GPO,
				Status				AS STAT,
				Ciclo_gpo			AS CICLO_GPO
			FROM
					grupo_solidario
			WHERE 
				ID_grupo_soli = '".$ID_GPO."' ";
				
	$rs_cons=$db->Execute($SQL_CONS);

	$Status_valido=(($rs_cons->fields["STAT_GPO"] != 'PROCESO INTEGRACION' && $rs_cons->fields["STAT_GPO"] != 'PROCESO INTEGRACION - RENOVACION') && ($rs_cons->fields["STAT"] != 'Activo') )?("FALSE"):("TRUE");
	
	$ARR_RESULT = array("VALIDA_STATUS"=>$Status_valido,"NMB_GPO"=>$rs_cons->fields["NMB"],"STATUS"=>$rs_cons->fields["STAT_GPO"],"CICLO_GPO"=>$rs_cons->fields["CICLO_GPO"]);

	return $ARR_RESULT;
}

function get_nmb_gpo_origin($ID_GPO_ORIGIN)
{
	global $db;

	
	$SQL_CONS="SELECT
				ID_grupo_soli		AS ID_GPO,
				Nombre				AS NMB,
				Status_grupo		AS STAT_GPO,
				Status				AS STAT,
				Ciclo_gpo			AS CICLO_GPO
			FROM
					grupo_solidario
			WHERE 
				ID_grupo_soli = '".$ID_GPO_ORIGIN."' ";
				
	$rs_cons=$db->Execute($SQL_CONS);

	$ARR_RESULT=array("NMB"=>$rs_cons->fields["NMB"],"STATUS"=>$rs_cons->fields["STAT_GPO"],"CICLO_GPO"=>$rs_cons->fields["CICLO_GPO"]);

	return $ARR_RESULT;
}

function check_status_gpo_vs_gpo($ID_GPO_ORIGIN,$ID_GPO_DEST)
{
	global $db;

	
	$SQL_CONS="SELECT
				ID_grupo_soli		AS ID_GPO,
				Nombre				AS NMB,
				Status_grupo		AS STAT_GPO,
				Status				AS STAT,
				Ciclo_gpo			AS CICLO_GPO
			FROM
					grupo_solidario
			WHERE 
				ID_grupo_soli = '".$ID_GPO_ORIGIN."' ";
				
	$rs_cons=$db->Execute($SQL_CONS);

	$Staus_gpo_origen = $rs_cons->fields["STAT_GPO"];

	$SQL_CONS="SELECT
				ID_grupo_soli		AS ID_GPO,
				Nombre				AS NMB,
				Status_grupo		AS STAT_GPO,
				Status				AS STAT,
				Ciclo_gpo			AS CICLO_GPO
			FROM
					grupo_solidario
			WHERE 
				ID_grupo_soli = '".$ID_GPO_DEST."' ";
				
	$rs_cons=$db->Execute($SQL_CONS);

	$Staus_gpo_destino = $rs_cons->fields["STAT_GPO"];

	$ARR_RESULT=array("STATUS_ORIGEN"=>$Staus_gpo_origen,"STATUS_DESTINO"=>$Staus_gpo_destino);

	return $ARR_RESULT;
}

function check_status_cte($ID_SOLI_CTE,$ID_GPO_ORIGIN)
{
	global $db;

	//1 REVISAR SI ES UNA SOLICITUD ACTIVA EN EL ÚLTIMO CICLO DEL GPO
	$SQL_CONS="SELECT
						grupo_solidario_integrantes.Status		AS STAT_INT_GPO,
						solicitud.Status						AS SOLI_STAT,
						grupo_solidario.Status_grupo			AS STAT_GPO,
						grupo_solidario.Nombre					AS NMB
				FROM grupo_solidario
					INNER JOIN grupo_solidario_integrantes		ON	grupo_solidario.ID_grupo_soli	=	grupo_solidario_integrantes.ID_grupo_soli
																	AND grupo_solidario.Ciclo_gpo	=  grupo_solidario_integrantes.Ciclo_gpo
																	AND grupo_solidario_integrantes.Ciclo_renovado  ='N'
					INNER JOIN solicitud						ON grupo_solidario_integrantes.ID_Solicitud	 		= solicitud.ID_Solicitud
																	AND grupo_solidario_integrantes.ID_grupo_soli	= solicitud.ID_grupo_soli
				WHERE grupo_solidario.ID_grupo_soli ='".$ID_GPO_ORIGIN."'
					AND grupo_solidario_integrantes.ID_Solicitud ='".$ID_SOLI_CTE."' ";
	$rs_cons=$db->Execute($SQL_CONS);
	$STAT_GPO	=	$rs_cons->fields["STAT_GPO"];
	$STAT_INTG	=	$rs_cons->fields["STAT_INT_GPO"];

	 $VALIDA_STAT_GPO	="TRUE";
	 $MSG_STAT_GPO		="";

	if( ($STAT_GPO =='CHECK LIST' || $STAT_GPO =='CLIENTE' || $STAT_GPO =='ASIGNACION MONTOS') && ($STAT_INTG == 'Activo') )
	  {
		  $VALIDA_STAT_GPO="FALSE";
		  $MSG_STAT_GPO="<FONT SIZE='2' COLOR='RED'><B>EL CLIENTE SE ENCUENTRA ACTIVO EN SU GRUPO ACTUAL. </B></FONT> <BR /><BR /><FONT SIZE='2'><B> ".$rs_cons->fields["NMB"]." / ".$rs_cons->fields["STAT_GPO"]." </B></FONT> ";
	   }

	$ARR_RESULT=array("VALIDA_STAT"=>$VALIDA_STAT_GPO,"MSG"=>$MSG_STAT_GPO);

	 return $ARR_RESULT;
}

function get_last_row_cte($ID_SOLI_CTE)
{
	global $db;

		$SQL_CONS="SELECT	
						ID_grupo_soli		AS ID_GPO,
						Ciclo_gpo			AS CICLO_GPO,
						Ciclo_cliente		AS CICLO_INTG,
						Ciclo_renovado		AS CICLO_RENOV,
						Num_cliente			AS NUM_CTE,
						Status				AS STAT_INTG,
						id_factura			AS FACT_CTE
				FROM grupo_solidario_integrantes
				WHERE grupo_solidario_integrantes.ID_Solicitud ='".$ID_SOLI_CTE."' ";
		$rs_cons=$db->Execute($SQL_CONS);

		While(!$rs_cons->EOF)
			{
				$LAST_ID_GPO				=	$rs_cons->fields["ID_GPO"];
				$LAST_CICLO_GPO				=	$rs_cons->fields["CICLO_GPO"];
				$LAST_CICLO_INTG			=	$rs_cons->fields["CICLO_INTG"];
				$LAST_STAT_INTG				=	$rs_cons->fields["STAT_INTG"];
				$LAST_STAT_INTG_II			=	$rs_cons->fields["STAT_INTG"];
				$LAST_FACT_INTG				=	$rs_cons->fields["FACT_CTE"];
				$LAST_CICLO_RENOV_CTE		=	$rs_cons->fields["CICLO_RENOV"];
				$rs_cons->MoveNext();
			}

	$LAST_STAT_INTG=($LAST_STAT_INTG == 'Inactivo')?(strtoupper("<FONT SIZE='1' COLOR='RED'><B>".$LAST_STAT_INTG."</B></FONT>")):(strtoupper("<FONT SIZE='1' COLOR='BLACK'><B>".$LAST_STAT_INTG."</B></FONT>"));
	
	$ARR_INFO=array("LAST_ID_GPO"=>$LAST_ID_GPO,"LAST_CICLO_GPO"=>$LAST_CICLO_GPO,"LAST_CICLO_INTG"=>$LAST_CICLO_INTG,"LAST_STAT_INTG"=>$LAST_STAT_INTG,"LAST_STAT_INTG_II"=>$LAST_STAT_INTG_II,"FACT_CTE"=>$LAST_FACT_INTG,"CICLO_RENOV"=>$LAST_CICLO_RENOV_CTE);

	return $ARR_INFO;
}




/*****************FILTROS**************************/
if(isset($body) )
{

 $Filtros_soli=get_filtros_indiv();
	   
  echo $Filtros_soli;

}

if( isset($CREDIT_INDIV) && !empty($CREDIT_INDIV) )
{
	$Filtros_soli=get_filtros_indiv();

	  echo $Filtros_soli;
}


/***************************FIN FILTROS********************************************/

/**************************GRIDS**********************************************/

if( isset($nomb_cliente) && isset($num_folio) && isset($num_cte) && isset($Nmb_promotor)  && isset($Fecha_inicio) && isset($Fecha_fin) )
{

		//DISCRIMINANTES
		$filtro_nombre="(Concat(solicitud.Nombre,solicitud.NombreI,solicitud.Ap_paterno,solicitud.Ap_materno))";
		$Discriminante ="";

		if(!empty($nomb_cliente) )
			{
			  $nomb_cliente=str_replace(" ", "", $nomb_cliente);
			  $Discriminante.=" AND ".$filtro_nombre."   		LIKE '%".$nomb_cliente."%' ";
			 }

		if(!empty($Gpo_soli) )
			  $Discriminante.=" AND grupo_solidario.Nombre  		LIKE '%".$Gpo_soli."%' ";


		if(!empty($num_folio))
		   $Discriminante.=" AND	solicitud.ID_Solicitud 		=  '".$num_folio."' ";

		if(!empty($num_cte))
		   $Discriminante.=" AND	clientes_datos.Num_cliente 	=  '".$num_cte."' ";

		if(!empty($Nmb_promotor))
		   $Discriminante.=" AND	promotores.Nombre 			=  '".$Nmb_promotor."' ";


		   
		 if(!empty($Fecha_inicio) || !empty($Fecha_fin))
		 {
			if( !empty($Fecha_inicio) && !empty($Fecha_fin)  )
				 $Discriminante.=" AND	solicitud.Fecha BETWEEN '".gfecha($Fecha_inicio)."' AND '".gfecha($Fecha_fin)."' ";

			if( !empty($Fecha_inicio) && empty($Fecha_fin)  )
				 $Discriminante.=" AND	solicitud.Fecha BETWEEN '".gfecha($Fecha_inicio)."' AND '".gfecha($Fecha_inicio)."' ";

			if( empty($Fecha_inicio) && !empty($Fecha_fin)  )
				 $Discriminante.=" AND	solicitud.Fecha BETWEEN '".gfecha($Fecha_fin)."' AND '".gfecha($Fecha_fin)."' ";
				 
		 }

  	   /***********************************/
	  /****ROWS GRID***********************/
			 $sql_params="SELECT Valor
					  FROM constantes
					 WHERE Nombre = 'GRID_RENGLONES_POR_PAGINA_PROMOCION'";
			 $rs=$db->execute($sql_params);
			 $rows_grid=$rs->fields["Valor"];
			 $LIMIT_INI=(empty($LIMIT_INI))?('0'):($LIMIT_INI);
			 $LIMIT_FIN=$rows_grid;
		/*********QUERY**************/

					  $Discriminante.=($ID_SUC =='1')?(""):(" AND solicitud.ID_Sucursal='".$ID_SUC."' ");
					  $tabla="solicitud";
					  $select_nombre="(Concat(".$tabla.".Nombre,' ',".$tabla.".NombreI,' ',".$tabla.".Ap_paterno,' ',".$tabla.".Ap_materno))";

					  $Sql_cons="SELECT
										".$tabla.".ID_Solicitud 													AS IDSOLI,
										".$tabla.".ID_Tipo_regimen 													AS TIPO_SOLI,
										".$select_nombre." 															AS CLIENTE,
										sucursales.Nombre  															AS SUC,
										IF(promotores.Nombre IS NOT NULL, promotores.Nombre,'SIN PROMOTOR')  		AS PROMO,
										".$tabla.".Status_solicitud   												AS STATSOLI,
										IF(solicitud.Nomina='Y','NÓMINA','INDIVIDUAL')   							AS PROSPECT,
										solicitud.ID_Tipocredito         											AS TP_CREDIT,
										solicitud.Fecha_sistema          											AS FECH_CAPT,
										solicitud.Renovacion_credit      											AS RENOV,
										IF(clientes_datos.Num_cliente != 0,clientes_datos.Num_cliente,'S/N')		AS NUM_CTE,
										".$tabla.".ID_Tipocredito 													AS TIPO_CREDIT,
										IF( ".$tabla.".ID_Tipocredito = 1,'CRÉDITO INDIVIDUAL',(  IF( ".$tabla.".Solicitud_expres='Y','CRÉDITO EXPRÉS'       ,    IF(".$tabla.".ID_Tipocredito = 2,'CRÉDITO SOLIDARIO','CRÉDITO NÓMINA') )     )      ) 								AS TIPO_CREDIT_II,
										grupo_solidario.ID_grupo_soli												AS ID_GPO,
										grupo_solidario.Nombre												 		AS NMB_GPO,
										grupo_solidario.Status_grupo										 		AS STAT_GPO,
										".$tabla.".Solicitud_expres													AS EXPRS	\n";
						
						 $Sql_form="FROM    ".$tabla."
									LEFT JOIN promotores      ON ".$tabla.".ID_Promotor  = promotores.Num_promo
									LEFT JOIN sucursales      ON ".$tabla.".ID_sucursal  = sucursales.ID_Sucursal
									LEFT JOIN clientes_datos  ON ".$tabla.".ID_Solicitud = clientes_datos.ID_Solicitud
																AND ".$tabla.".ID_Tipocredito='2'
									LEFT JOIN grupo_solidario ON solicitud.ID_grupo_soli = grupo_solidario.ID_grupo_soli
																AND solicitud.ID_Tipocredito='2'
																AND solicitud.ID_grupo_soli <> 0

								WHERE
									Status_solicitud IS NOT NULL
									".$Discriminante."
									AND solicitud.ID_Tipocredito='2'
									LIMIT ". $LIMIT_INI." , ". $LIMIT_FIN." ";

						$Sql_cons.=$Sql_form;
					$rs_cons=$db->Execute($Sql_cons);

		/**********************************/
		/******DISPLAY NAVIGATION BAR******/
			 $Sql_navegation="SELECT
										COUNT(".$tabla.".ID_Solicitud)	AS CUANTOS 	\n";

			$Sql_form=str_replace("LIMIT ". $LIMIT_INI." , ". $LIMIT_FIN."","",$Sql_form);
			$Sql_navegation.=$Sql_form;
			
		 $Bar_navegation=get_pagination($Sql_navegation,$rows_grid,$CURRENT_PAGE,1);
		 /********************************/

		 
	$Table_grid="<TABLE  CELLSPACING='0' STYLE='' ALIGN='CENTER' BORDER='0px' WIDTH='99%'  CLASS='tablesorter' ID='TBL_SOLI'>
				<THEAD>
				
					<TR>
						<TD  ALIGN='CENTER' COLSPAN='8'  STYLE='-moz-border-radius-topleft:  6px; -moz-border-radius-topright: 6px;  background-color : #6fa7d1;'>
							<B> <FONT SIZE='2' COLOR='WHITE'>SOLICITUDES DE CRÉDITO</FONT></B>
						</TD>
					</TR>
					
				
					<TR ALIGN='center' VALIGN='middle'  BGCOLOR='#6fa7d1' STYLE='height:30px;'>
						   <TH STYLE='font-size:small;  text-align:center; color:white; text-decoration:underline; cursor:pointer;'  WIDTH='7%' >     			FOLIO          </TH>
						   <TH STYLE='font-size:small;  text-align:left; color:white; text-decoration:underline; cursor:pointer;'    WIDTH='25%' >     			NOMBRE         </TH>
						   <TH STYLE='font-size:small;  text-align:left; color:white; text-decoration:underline; cursor:pointer;'    WIDTH='10%' >     			TIPO CRÉDITO   </TH>
						   <TH STYLE='font-size:small;  text-align:left; color:white; text-decoration:underline; cursor:pointer;'    WIDTH='18%' >     			GRUPO          </TH>
						   <TH STYLE='font-size:small;  text-align:left; color:white; text-decoration:underline; cursor:pointer;'    WIDTH='15%' >     			SUCURSAL       </TH>
						   <TH STYLE='font-size:small;  text-align:left; color:white; text-decoration:underline; cursor:pointer;'    WIDTH='15%' >     			FECHA CAPTURA  </TH>
						   <TH STYLE='font-size:small;  text-align:left; color:white; text-decoration:underline; cursor:pointer;'    WIDTH='5%' >     			NUM. CLIENTE   </TH>
						   <TH STYLE='font-size:small;  text-align:left; color:white; text-decoration:underline;                '    WIDTH='5%' >     						   </TH>
					 </TR>
			</THEAD>
			<TBODY>";
					$cont=1;
					While(!$rs_cons->EOF)
					{

					  /*****VALIDACIONES*********/
					  $row_color        =(($cont % 2) == 0 )?('#FDFEFF'):('#E7EEF6');

					  $Expres_trad		="<IMG SRC='".$img_path."repeat.png'  LANG='".$rs_cons->fields["IDSOLI"]."_".$rs_cons->fields["ID_GPO"]."' STYLE='height:15px; width:15px; padding-bottom:1px; padding-top:1px; cursor:pointer;'  CLASS='ASIGNA_GPO'  />";
					  /**************/

					  $Table_grid .="<TR ALIGN='center' VALIGN='middle' BgCOLOR='".$row_color ."' 												ONMOUSEOVER=\"javascript:this.style.backgroundColor='#FBFAAE'; this.style.cursor='hand'; \"
						 ONMOUSEOUT =\"javascript:this.style.backgroundColor='' \"  BgCOLOR='".$row_color ."'>
											<TH STYLE='font-size:small; text-decoration:underline; text-align:center; color:gray; cursor:pointer;' 	WIDTH='7%' CLASS='SHOW_DETAIL_SOLI' ID='".$rs_cons->fields["IDSOLI"]."'>
												".$rs_cons->fields["IDSOLI"]."
											</TH>

											<TH STYLE='font-size:small;  text-align:left;   ' 				WIDTH='30%' >    ".strtoupper($rs_cons->fields["CLIENTE"])."		</TH>
											<TH STYLE='font-size:small;  text-align:left; color:navy;' 	    WIDTH='15%' >    ".strtoupper($rs_cons->fields["TIPO_CREDIT_II"])."	</TH>
											<TH STYLE='font-size:small;  text-align:left; ' 				WIDTH='18%' >    ".strtoupper($rs_cons->fields["NMB_GPO"])."	 	</TH>
											<TH STYLE='font-size:small;  text-align:left; ' 			    WIDTH='15%' >    ".strtoupper($rs_cons->fields["SUC"])."		 	</TH>
											<TH STYLE='font-size:small;  text-align:left; color:gray;'		WIDTH='15%' >    ".ffecha($rs_cons->fields["FECH_CAPT"])."		    </TH>
											<TH STYLE='font-size:small;  text-align:left; ' 				WIDTH='5%' >     ".$rs_cons->fields["NUM_CTE"]."		    		</TH>
											<TH STYLE='font-size:small;' WIDTH='5%'>										 ".$Expres_trad."									</TH>
									 </TR>";
									 
					  $cont++;
					  $rs_cons->MoveNext();
					}

					if($cont==1)
					{
					  $Table_grid .="<TR ALIGN='center' VALIGN='middle' BgCOLOR='white' >
											<TH STYLE='font-size:small;  text-align:center; color:gray; height:50px;' COLSPAN='8' >
												<IMG SRC='".$img_path."exclamation.png'  STYLE='height:15px; cursor:pointer;' /> &nbsp; NO SE ENCONTRARON SOLICITUDES, CONFORME EL CRITERIO DE BÚSQUEDA.
											</TH>
									 </TR>";
					}
					
	$Table_grid.="</TBODY>
					</TABLE>";


  echo $Table_grid;
}




if(isset($Show_detail) && !empty($Show_detail) && isset($ID_SOLI) && !empty($ID_SOLI))
{

			/*********QUERY**************/

					  
					  $tabla="solicitud";
					  $select_nombre="(Concat(".$tabla.".Nombre,' ',".$tabla.".NombreI,' ',".$tabla.".Ap_paterno,' ',".$tabla.".Ap_materno))";
					  $sql_cons="SELECT
										".$tabla.".ID_Solicitud 											 AS IDSOLI,
										".$select_nombre." 													 AS CLIENTE,
										sucursales.Nombre  AS SUC,
										IF(promotores.Nombre IS NOT NULL, promotores.Nombre,'SIN PROMOTOR')  AS PROMO,
										".$tabla.".Status_solicitud   										 AS STATSOLI,
										".$tabla.".Telefono           										 AS TEL,
										".$tabla.".Tel_contacto       										 AS TELCONT,
										".$tabla.".Num_celular        										 AS CEL,
										solicitud.Email    													 AS EMAIL,
										CONCAT(if(solicitud.Calle IS NULL,'',solicitud.Calle),' ',if(solicitud.Numero IS NULL,'',solicitud.Numero),' ',if(solicitud.Interior IS NULL,'',solicitud.Interior),', COL. ',if(solicitud.Colonia IS NULL,'',solicitud.Colonia),', ',if(solicitud.Poblacion IS NULL,'',solicitud.Poblacion),', ',if(solicitud.Estado IS NULL,'',solicitud.Estado),', C.P. ',if(solicitud.CP IS NULL,'',solicitud.CP) ) AS DOMCTE,
										IF(solicitud.Nomina='Y','NÓMINA','INDIVIDUAL')   					 AS PROSPECT,
										solicitud.ID_Tipocredito         									 AS TP_CREDIT,
										solicitud.Fecha_sistema          									 AS FECH_CAPT,
										solicitud.Renovacion_credit      									 AS RENOV,
										IF(clientes_datos.Num_cliente != 0,clientes_datos.Num_cliente,'S/N') AS NUM_CTE,
										".$tabla.".ID_Tipocredito 											 AS TIPO_CREDIT,
										IF(".$tabla.".ID_Tipocredito = 1,'CRÉDITO INDIVIDUAL',( IF(".$tabla.".ID_Tipocredito = 2,'CRÉDITO SOLIDARIO','CRÉDITO NÓMINA') ) ) AS TIPO_CREDIT_II,
										grupo_solidario.Nombre												 AS NMB_GPO,
										grupo_solidario.Status_grupo										 AS STAT_GPO,
										".$tabla.".Empresa_soli         									 AS EMPR,
										".$tabla.".Solicitud_expres											 AS EXPRS

								FROM    ".$tabla."
									LEFT JOIN promotores      ON ".$tabla.".ID_Promotor  = promotores.Num_promo
									LEFT JOIN sucursales      ON ".$tabla.".ID_sucursal  = sucursales.ID_Sucursal
									LEFT JOIN clientes_datos  ON ".$tabla.".ID_Solicitud = clientes_datos.ID_Solicitud
									LEFT JOIN grupo_solidario ON solicitud.ID_grupo_soli = grupo_solidario.ID_grupo_soli
																AND solicitud.ID_Tipocredito='2'
																AND solicitud.ID_grupo_soli <> 0
								WHERE
									solicitud.ID_Solicitud='".$ID_SOLI."'";

					$rs_cons=$db->Execute($sql_cons);
		/*****************************/
		
	$Table_detail="				<BR />
								<H3 CLASS='ui-widget-header' STYLE='font-size:x-medium; font-weight:bold; width:90%;'>".strtoupper($rs_cons->fields["CLIENTE"])."
								</H3>
								<BR />";

	
	
	$Table_detail.="				<TABLE CELLSPACING='3' STYLE='' ALIGN='CENTER' BORDER='0px' WIDTH='90%'>";

	$Expres = ($rs_cons->fields["EXPRS"] == 'Y' )?(" - EXPRÉS"):("");
	$Table_detail.="							<TR STYLE='font-size:small;'>
															<TH STYLE='text-align:left;   width:5%;' >Tipo</TH>
															<TH STYLE='text-align:center; width:1%;' > :</TH>
															<TD STYLE='text-align:left; font-weight:bold;  width:94%;'>".$rs_cons->fields["TIPO_CREDIT_II"]." ".$Expres."</TD>
														</TR>";
	$Statatus=($rs_cons->fields["TIPO_CREDIT"]=='2')?($rs_cons->fields["STAT_GPO"]):($rs_cons->fields["STATSOLI"]);
	
	$Table_detail.="								<TR STYLE='font-size:small;'>
															<TH STYLE='text-align:left;   width:5%;' 			 > Estatus </TH>
															<TH STYLE='text-align:center; width:1%;' 			 >:</TH>
															<TD STYLE='text-align:left; text-decoration:underline;  width:94%; color:navy;'>".$Statatus."</TD>
														</TR>";
	if($rs_cons->fields["TIPO_CREDIT"]=='2')
		$Table_detail.="								<TR STYLE='font-size:small;'>
															<TH STYLE='text-align:left;   width:5%;' 			 > Grupo </TH>
															<TH STYLE='text-align:center; width:1%;' 			 >:</TH>
															<TD STYLE='text-align:left;  font-weight:bold;  width:94%; color:black;'>".$rs_cons->fields["NMB_GPO"]."</TD>
														</TR>";
	if($rs_cons->fields["TIPO_CREDIT"]=='3')
		$Table_detail.="								<TR STYLE='font-size:small;'>
															<TH STYLE='text-align:left;   width:5%;' 			 > Empresa </TH>
															<TH STYLE='text-align:center; width:1%;' 			 >:</TH>
															<TD STYLE='text-align:left;  font-weight:bold;  width:94%; color:black;'>".$rs_cons->fields["EMPR"]."</TD>
														</TR>";
														
	$Dom_cte=(trim($rs_cons->fields["DOMCTE"]) != ", COL. , , , C.P.")?($rs_cons->fields["DOMCTE"]):("");
	$Table_detail.="									<TR STYLE='font-size:small;'>
															<TH STYLE='text-align:left;   width:5%;' >Teléfono</TH>
															<TH STYLE='text-align:center; width:1%;' > :</TH>
															<TD STYLE='text-align:left;   width:94%;'>".$rs_cons->fields["TEL"]."</TD>
														</TR>
														
														<TR STYLE='font-size:small;'>
															<TH STYLE='text-align:left;   width:5%;' >Celular</TH>
															<TH STYLE='text-align:center; width:1%;' >:</TH>
															<TD STYLE='text-align:left;   width:94%;'>".$rs_cons->fields["CEL"]."</TD>
														</TR>
														
														<TR STYLE='font-size:small;'>
															<TH STYLE='text-align:left;   width:5%;' >Email</TH>
															<TH STYLE='text-align:center; width:1%;' >:</TH>
															<TD STYLE='text-align:left;   width:94%;'>".$rs_cons->fields["EMAIL"]."</TD>
														</TR>
														
														<TR STYLE='font-size:small;'>
															<TH STYLE='text-align:left;   width:5%;' >Dirección</TH>
															<TH STYLE='text-align:center; width:1%;' >:</TH>
															<TD STYLE='text-align:left;   width:94%;'>".$Dom_cte."</TD>
														</TR>
								</TABLE>";
	echo $Table_detail;
}

if(isset($ASIGNA_NEW_GPO) && !empty($ASIGNA_NEW_GPO) && isset($ID_SOLI) && !empty($ID_SOLI) && isset($ID_GPO) && !empty($ID_GPO))
{

	/*******VALIDACIONES******/
	$NMB_CTE					=	get_nombre_cte($ID_SOLI);
	$NUM_CTE					=	get_num_cte($ID_SOLI,$ID_GPO);
	$ID_FACT					=	get_last_fact_cte($NUM_CTE);
	$RESULT_ADEUDO_ACTUAL		=	check_adeudo_actual($ID_FACT);
	$RESULT_SALDO_VENCIDO		= 	check_saldo_vencido($ID_FACT);
	$NUM_PAGOS					=	check_pagos_realizados($ID_FACT);
    $VALIDA_STAT_CTE			=	check_status_cte($ID_SOLI,$ID_GPO);
	$ID_TIPO_SOLI				=	get_tipo_solicitud($ID_SOLI);
	/*************************/
	
	$HTML="<INPUT TYPE='HIDDEN' ID='ID_GPO_ORIGEN' 		VALUE='".$ID_GPO."'>
		   <INPUT TYPE='HIDDEN' ID='ID_SOLI_CTE'   		VALUE='".$ID_SOLI."'>
		   <INPUT TYPE='HIDDEN' ID='ID_Tiposolicitud'   VALUE='".$ID_TIPO_SOLI."'>
			<TABLE CELLSPACING='0'  ALIGN='CENTER' BORDER='0px' WIDTH='95%'>
				 <TR STYLE='HEIGHT:30px;'>
					<TH ALIGN='CENTER' COLSPAN='4' STYLE='-moz-border-radius-topleft:  6px; -moz-border-radius-topright: 6px;  background-color : #eef5e5;' >\" ".$NMB_CTE." \"</TH>
				 </TR>

				 <TR   STYLE='background-color : #eef5e5; HEIGHT:20px;'>
					 <TH STYLE='WIDTH:35%; HEIGHT:20px;' ALIGN='RIGHT'>NUM. CLIENTE : </TH>
					 <TD ALIGN='LEFT' STYLE='WIDTH:15%;'>".$NUM_CTE."</TD>
					 <TH STYLE='WIDTH:25%; HEIGHT:20px;' ALIGN='RIGHT'>ID CRÉDITO: </TH>
					 <TD ALIGN='LEFT' STYLE='WIDTH:25%;'> ".$ID_FACT."</TD>
				</TR>
			</TABLE>";

	/*********SKIN RESULT************/
	//ADEUDO ACTUAL
	$Msg_adeudo		=	($RESULT_ADEUDO_ACTUAL["ADEUDO"] == 'TRUE' )?("<FONT COLOR='RED'>$".number_format($RESULT_ADEUDO_ACTUAL["SALDO"],2)."</FONT>"):("<FONT>$0.00</FONT>&nbsp;&nbsp;<IMG SRC='".$img_path."tick.png'  STYLE='height:12px;' />");
	//$Img_adeudo		=	($RESULT_ADEUDO_ACTUAL["ADEUDO"] == 'TRUE' )?("<IMG SRC='".$img_path."cross-octagon.png'  STYLE='height:15px;' />"):("");
	//SALDO VENCIDO
	$Msg_saldo		=	($RESULT_SALDO_VENCIDO["SALDO_VENCIDO"] == 'TRUE' )?("<FONT COLOR='RED'>$".number_format($RESULT_SALDO_VENCIDO["SALDO"],2)."</FONT> "):("<FONT>$0.00</FONT>&nbsp;&nbsp;<IMG SRC='".$img_path."tick.png'  STYLE='height:12px;' />");
	/*******************************/

	$HTML.="<HR STYLE='width:95%; border:dotted; border-width:1px;' />
			<TABLE CELLSPACING='0'  ALIGN='CENTER' BORDER='0px' WIDTH='95%'>
				<TR STYLE='HEIGHT:20px;'>
					<TH ALIGN='CENTER' COLSPAN='4' STYLE='COLOR:BLACK;' >VALIDAR CLIENTE</TH>
				 </TR>
				<TR ALIGN='LEFT' STYLE='HEIGHT:25px;'>
					<TH  ALIGN='RIGHT' STYLE='WIDTH:25%;'>ADEUDO ACTUAL : </TH>
					<TD  ALIGN='LEFT'>&nbsp;".$Msg_adeudo."&nbsp;".$Img_adeudo."</TD>
				</TR>
				<TR ALIGN='LEFT' STYLE='HEIGHT:25px;'>
					<TH  ALIGN='RIGHT' STYLE='WIDTH:25%;'>SALDO VENCIDO : </TH>
					<TD  ALIGN='LEFT'>&nbsp;".$Msg_saldo."&nbsp;".$Img_adeudo."</TD>
				</TR>
				<TR ALIGN='LEFT' STYLE='HEIGHT:25px;'>
					<TH  ALIGN='RIGHT' STYLE='WIDTH:25%;'>PAGOS : </TH>
					<TD  ALIGN='LEFT'>&nbsp;".$NUM_PAGOS."</TD>
				</TR>
			</TABLE>";

 	if($RESULT_ADEUDO_ACTUAL["ADEUDO"] == 'TRUE' || $RESULT_SALDO_VENCIDO["SALDO_VENCIDO"] == 'TRUE' || $VALIDA_STAT_CTE["VALIDA_STAT"] == 'FALSE')
	{
		$HTML.="<BR /><CENTER><FONT SIZE='2' COLOR='RED'><B>IMPOSIBLE CONTINUAR </B></FONT><BR /> ".$VALIDA_STAT_CTE["MSG"]." </CENTER><HR STYLE='width:95%; border:dotted; border-width:1px;' />";
	}
	else
		$HTML.="<BR /><BUTTON  VALUE='CRÉDITO SOLIDARIO'  ID='CREDIT_SOLIDARIO' >ASIGNAR GRUPO SOLIDARIO</BUTTON>
				<BR> <DIV ID='VALIDA_GPO_OK' ></DIV>";

	echo $HTML;

}


if(isset($VALIDA_GPO) && !empty($VALIDA_GPO) && isset($ID_GPO_DEST) && !empty($ID_GPO_DEST) && isset($ID_GPO_ORIGIN) && !empty($ID_GPO_ORIGIN) && isset($ID_SOLI_CTE) && !empty($ID_SOLI_CTE))
{
    $INFO_GPO_DEST 		= check_grupo_destino($ID_GPO_DEST);
    $NMB_GPO_ORIGIN		= get_nmb_gpo_origin($ID_GPO_ORIGIN);
    $LAST_INFO_CTE		= get_last_row_cte($ID_SOLI_CTE);

  	//$ARR_INFO=array("LAST_ID_GPO"=>$LAST_ID_GPO,"LAST_CICLO_GPO"=>$LAST_CICLO_GPO,"LAST_CICLO_INTG"=>$LAST_CICLO_INTG,"LAST_STAT_INTG"=>$LAST_STAT_INTG	);

    if( $INFO_GPO_DEST["VALIDA_STATUS"] == 'TRUE')
      {
		$HTML="<INPUT TYPE='HIDDEN' ID='ID_GPO_DEST'  	VALUE='".$ID_GPO_DEST."'>
			   <INPUT TYPE='HIDDEN' ID='ID_GPO_ORIGIN'  VALUE='".$ID_GPO_ORIGIN."'>
			   <INPUT TYPE='HIDDEN' ID='NMB_GPO_DEST' 	VALUE='".$INFO_GPO_DEST["NMB_GPO"]."'>
				<BR /><HR STYLE='width:95%; border:dotted; border-width:1px;' />
				<TABLE CELLSPACING='0'  ALIGN='CENTER' BORDER='0px' WIDTH='95%'>

					<TR STYLE='HEIGHT:20px;'>
						<TH ALIGN='CENTER' COLSPAN='4' STYLE='COLOR:BLACK;' >TRASPASAR CLIENTE</TH>
					 </TR>
					 
					<TR ALIGN='LEFT' STYLE='HEIGHT:25px;'>
						<TH  ALIGN='RIGHT' STYLE='WIDTH:25%;'>CLIENTE: </TH>
						<TD  ALIGN='LEFT'>".$LAST_INFO_CTE["LAST_STAT_INTG"]."</TD>
					</TR>
					
					<TR ALIGN='LEFT' STYLE='HEIGHT:25px;'>
						<TH  ALIGN='RIGHT' STYLE='WIDTH:25%;'>GRUPO ACTUAL: </TH>
						<TD  ALIGN='LEFT'>".$NMB_GPO_ORIGIN["NMB"]."&nbsp; <I>(".$NMB_GPO_ORIGIN["STATUS"].") / CICLO # ".$NMB_GPO_ORIGIN["CICLO_GPO"]."</I></TD>
					</TR>
					
					<TR ALIGN='LEFT'  STYLE='HEIGHT:25px;' >
						<TH  ALIGN='RIGHT' STYLE='WIDTH:25%;'>GRUPO DESTINO: </TH>
						<TD  ALIGN='LEFT'>".$INFO_GPO_DEST["NMB_GPO"]."&nbsp;<I>(".$INFO_GPO_DEST["STATUS"].") / CICLO # ".$INFO_GPO_DEST["CICLO_GPO"]." </I></TD>
					</TR>

				</TABLE>";

			if($ID_GPO_DEST == $ID_GPO_ORIGIN && $NMB_GPO_ORIGIN["CICLO_GPO"] == $INFO_GPO_DEST["CICLO_GPO"] && $LAST_INFO_CTE["CICLO_GPO"] == $INFO_GPO_DEST["CICLO_GPO"] )
			{
				$HTML.="<TABLE CELLSPACING='0'  ALIGN='CENTER' BORDER='0px' WIDTH='95%'>
						<TR ALIGN='CENTER' STYLE='HEIGHT:25px;'>
								<TD  ALIGN='CENTER' COLSAPN='2'>
									<FONT SIZE='2' COLOR='RED'><B>EL GRUPO Y EL CICLO PERTENECEN AL GRUPO ACTUAL. <BR /> IMPOSIBLE CONTINUAR.</B></FONT>
								</TD>
							</TR>
						</TABLE>";
			}
			else
			{
				$HTML.="<TABLE CELLSPACING='0'  ALIGN='CENTER' BORDER='0px' WIDTH='95%'>
							<TR ALIGN='CENTER' STYLE='HEIGHT:25px;'>
								<TD  ALIGN='CENTER' COLSAPN='2'><DIV ID='TRASPASA_CTE_GPO' ><BUTTON  ID='TRASPASAR_CTE'   STYLE='font-size:x-small;' > &nbsp;&nbsp; TRASPASAR CLIENTE </BUTTON></DIV></TD>
							</TR>
						</TABLE>";
			}

		echo $HTML; 
	  }

}



if(isset($TRASPASA_CTE_GPO) && !empty($TRASPASA_CTE_GPO) && isset($ID_GPO_DEST) && !empty($ID_GPO_DEST) && isset($ID_GPO_ORIGIN) && !empty($ID_GPO_ORIGIN) && isset($ID_SOLI_CTE) && !empty($ID_SOLI_CTE))
{
	$INFO_STAT_GPOS		=	check_status_gpo_vs_gpo($ID_GPO_ORIGIN,$ID_GPO_DEST);
	$LAST_INFO_CTE		=	get_last_row_cte($ID_SOLI_CTE);


	if( ( $INFO_STAT_GPOS["STATUS_ORIGEN"] =='PROCESO INTEGRACION' || $INFO_STAT_GPOS["STATUS_ORIGEN"] =='PROCESO INTEGRACION - RENOVACION' ) && ($INFO_STAT_GPOS["STATUS_DESTINO"] =='PROCESO INTEGRACION' || $INFO_STAT_GPOS["STATUS_DESTINO"] =='PROCESO INTEGRACION - RENOVACION') && (empty($LAST_INFO_CTE["FACT_CTE"]))  && ($LAST_INFO_CTE["CICLO_RENOV"] == 'N' ) )
		{
						//ACTUALIZAR SOLICITUD, CLIENTES DATOS, GRUPO_SOLIDARIO_INTEGRANTES, TRAER NÚMERO DE CLIENTE
						//$ARR_INFO=array("LAST_ID_GPO"=>$LAST_ID_GPO,"LAST_CICLO_GPO"=>$LAST_CICLO_GPO,"LAST_CICLO_INTG"=>$LAST_CICLO_INTG,"LAST_STAT_INTG"=>$LAST_STAT_INTG	);
						//$NUM_CTE=(empty($rs_cons->fields["NUM_CTE"]))?(0):($rs_cons->fields["NUM_CTE"]);
						$NUM_CTE 		=	get_num_cte_clientes($ID_SOLI_CTE);
						$INFO_GPO_DEST 	=   check_grupo_destino($ID_GPO_DEST);

						$SQL_UPDATE="UPDATE
											grupo_solidario_integrantes
										SET
											ID_grupo_soli	= '".$ID_GPO_DEST."',
											Num_cliente		= '".$NUM_CTE."',
											Ciclo_gpo		= '".$INFO_GPO_DEST["CICLO_GPO"]."',
											Ciclo_cliente	= '".$LAST_INFO_CTE["LAST_CICLO_INTG"]."',
											Status			= 'Activo',
											Ciclo_renovado	= 'N',
											Ahorro			= '0.00',
											Monto_asignado	= '0.00',
											Cliente			= 'N',
											Fecha_integracion = NOW()
										WHERE
												ID_grupo_soli	=	'".$ID_GPO_ORIGIN."'
											AND	ID_Solicitud	=	'".$ID_SOLI_CTE."'
											AND Ciclo_renovado	=   'N'
											AND	Ciclo_gpo		=   '".$LAST_INFO_CTE["LAST_CICLO_GPO"]."'
											AND Ciclo_cliente	=   '".$LAST_INFO_CTE["LAST_CICLO_INTG"]."'
											AND Status			=   '".$LAST_INFO_CTE["LAST_STAT_INTG_II"]."'";

						if($db->Execute($SQL_UPDATE))
						{
							$SQL_UPDATE_SOLI="UPDATE
													solicitud
												SET
													ID_grupo_soli	= '".$ID_GPO_DEST."',
													Status			= 'Activa'
												WHERE
														ID_grupo_soli	=	'".$ID_GPO_ORIGIN."'
													AND	ID_Solicitud	=	'".$ID_SOLI_CTE."' ";
							$db->Execute($SQL_UPDATE_SOLI);

							$SQL_UPDATE_CTES="UPDATE
													clientes_datos
												SET
													ID_grupo_soli	= '".$ID_GPO_DEST."'
												WHERE
														ID_grupo_soli	=	'".$ID_GPO_ORIGIN."'
													AND	ID_Solicitud	=	'".$ID_SOLI_CTE."' ";
							$db->Execute($SQL_UPDATE_CTES);

							echo "TRUE";
						}
						else
							echo "FALSE";
			

		}
		else
		{
						//ACTUALIZAR SOLICITUD, CLIENTES DATOS, GRUPO_SOLIDARIO_INTEGRANTES, TRAER NÚMERO DE CLIENTE
						//$ARR_INFO=array("LAST_ID_GPO"=>$LAST_ID_GPO,"LAST_CICLO_GPO"=>$LAST_CICLO_GPO,"LAST_CICLO_INTG"=>$LAST_CICLO_INTG,"LAST_STAT_INTG"=>$LAST_STAT_INTG	);
						//$NUM_CTE=(empty($rs_cons->fields["NUM_CTE"]))?(0):($rs_cons->fields["NUM_CTE"]);
						//$ARR_RESULT = array("VALIDA_STATUS"=>$Status_valido,"NMB_GPO"=>$rs_cons->fields["NMB"],"STATUS"=>$rs_cons->fields["STAT_GPO"],"CICLO_GPO"=>$rs_cons->fields["CICLO_GPO"]);
						//$ARR_RESULT=array("NMB"=>$rs_cons->fields["NMB"],"STATUS"=>$rs_cons->fields["STAT_GPO"],"CICLO_GPO"=>$rs_cons->fields["CICLO_GPO"]);

						//'PROCESO INTEGRACION','ASIGNACION MONTOS','CHECK LIST','CLIENTE','APROBADO','CONFORMIDAD COMPLETA','PROCESO INTEGRACION - RENOVACION'
						
						$NUM_CTE 			=	get_num_cte_clientes($ID_SOLI_CTE);
						$INFO_GPO_DEST 		=   check_grupo_destino($ID_GPO_DEST);
						$INFO_GPO_ORIGIN	=	get_nmb_gpo_origin($ID_GPO_ORIGIN);
						$ABORT_INSERT		=	'FALSE';

						if ( ( $INFO_GPO_ORIGIN["STATUS"] == 'CONFORMIDAD COMPLETA' || $INFO_GPO_ORIGIN["STATUS"] == 'CLIENTE' )      && ($LAST_INFO_CTE["LAST_STAT_INTG_II"] == 'Activo' ) )
							{$CICLO_CLIENTE =(  $LAST_INFO_CTE["LAST_CICLO_INTG"]  + 1);}
						else if ( ( $INFO_GPO_ORIGIN["STATUS"] == 'CONFORMIDAD COMPLETA' || $INFO_GPO_ORIGIN["STATUS"] == 'CLIENTE' || $INFO_GPO_ORIGIN["STATUS"] == 'PROCESO INTEGRACION' || $INFO_GPO_ORIGIN["STATUS"] == 'PROCESO INTEGRACION - RENOVACION') && ($LAST_INFO_CTE["LAST_STAT_INTG_II"] == 'Inactivo' ) )
							{$CICLO_CLIENTE = $LAST_INFO_CTE["LAST_CICLO_INTG"] ;}
						else if ( ($INFO_GPO_ORIGIN["STATUS"] == 'CHECK LIST' || $INFO_GPO_ORIGIN["STATUS"] == 'ASIGNACION MONTOS' )  && ($LAST_INFO_CTE["LAST_STAT_INTG_II"] == 'Inactivo' ) )
							{$CICLO_CLIENTE = $LAST_INFO_CTE["LAST_CICLO_INTG"] ;}
						else if( ($INFO_GPO_ORIGIN["STATUS"] == 'CHECK LIST' || $INFO_GPO_ORIGIN["STATUS"] == 'ASIGNACION MONTOS' )  && ($LAST_INFO_CTE["LAST_STAT_INTG_II"] == 'Activo' ) )
							{$ABORT_INSERT='TRUE';}


						if($ABORT_INSERT == 'FALSE' )
						{
										$SQL_INSERT="INSERT INTO
																grupo_solidario_integrantes
												(ID_grupo_soli     ,ID_Solicitud,		   Num_cliente   ,Ciclo_gpo,Ciclo_cliente,Status,Ciclo_renovado,Ahorro,Monto_asignado,Cliente,Fecha_integracion)
										  VALUES('".$ID_GPO_DEST."','".$ID_SOLI_CTE."','".$NUM_CTE."','".$INFO_GPO_DEST["CICLO_GPO"]."','".$CICLO_CLIENTE."','Activo','N','0.00','0.00','N',NOW())";
										

										if($db->Execute($SQL_INSERT))
											{
												$SQL_UPDATE_SOLI="UPDATE
																		solicitud
																	SET
																		ID_grupo_soli	= '".$ID_GPO_DEST."',
																		Status			= 'Activa'
																	WHERE
																			ID_grupo_soli	=	'".$ID_GPO_ORIGIN."'
																		AND	ID_Solicitud	=	'".$ID_SOLI_CTE."' ";
												$db->Execute($SQL_UPDATE_SOLI);

												$SQL_UPDATE_CTES="UPDATE
																		clientes_datos
																	SET
																		ID_grupo_soli	= '".$ID_GPO_DEST."'
																	WHERE
																			ID_grupo_soli	=	'".$ID_GPO_ORIGIN."'
																		AND	ID_Solicitud	=	'".$ID_SOLI_CTE."' ";
												$db->Execute($SQL_UPDATE_CTES);

												echo "TRUE";
											}
											else
												echo "FALSE";
							
						}
						else
							echo "FALSE";


		}

	
}
?>

