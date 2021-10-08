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

/*********FUNCTIONS**************/
function get_filtros_indiv()
{
	global $db;
	global $ID_SUC;

	$Filtros="";
	$Cont_tipo_credit=0;
     $header_cmb ="<SELECT   NAME='tipo_credito' ID='TIPO_CREDIT' > ";
    
    $combo="";
    
	  $Sql_indiv="SELECT
					Status AS ESTAT
				FROM cat_tipo_credito
					INNER JOIN grupo_tipo_credito ON cat_tipo_credito.ID_Tipocredito = grupo_tipo_credito.ID_Tipocredito
													AND grupo_tipo_credito.ID_Sucursal ='".$ID_SUC."'
				WHERE cat_tipo_credito.ID_Tipocredito ='1' ";
   $rs_idiv=$db->Execute($Sql_indiv);
   $combo.=($rs_idiv->fields["ESTAT"]=='Activo')?("<OPTION VALUE='1'  >CRÉDITO INDIVIDUAL</OPTION> \n"):("");

   if($rs_idiv->fields["ESTAT"]=='Activo')
		$Cont_tipo_credit++;
		

  $Sql_nom="SELECT
					Status AS ESTAT
				FROM cat_tipo_credito
					INNER JOIN grupo_tipo_credito ON cat_tipo_credito.ID_Tipocredito = grupo_tipo_credito.ID_Tipocredito
													AND grupo_tipo_credito.ID_Sucursal ='".$ID_SUC."'
				WHERE cat_tipo_credito.ID_Tipocredito ='3' ";
   $rs_nom=$db->Execute($Sql_nom);
   $combo.=($rs_nom->fields["ESTAT"]=='Activo')?("<OPTION VALUE='3'  >CRÉDITO NÓMINA</OPTION> \n"):("");

   if($rs_nom->fields["ESTAT"]=='Activo')
		$Cont_tipo_credit++;



  $Sql_solid="SELECT
					Status AS ESTAT
				FROM cat_tipo_credito
					INNER JOIN grupo_tipo_credito ON cat_tipo_credito.ID_Tipocredito = grupo_tipo_credito.ID_Tipocredito
													AND grupo_tipo_credito.ID_Sucursal ='".$ID_SUC."'
				WHERE cat_tipo_credito.ID_Tipocredito ='2' ";
   $rs_solid=$db->Execute($Sql_solid);
   $combo.=($rs_solid->fields["ESTAT"]=='Activo')?("<OPTION VALUE='2'  >CRÉDITO SOLIDARIO</OPTION> \n"):("");

   if($rs_solid->fields["ESTAT"]=='Activo')
		$Cont_tipo_credit++;


    if($Cont_tipo_credit > 1)
		$combo = $header_cmb."<OPTION VALUE=''   >--- SELECCIONAR OPCIÓN ---</OPTION> \n".$combo;
	else
		$combo = $header_cmb.$combo;

	$combo.="</SELECT>\n";

	$combo_tipo_soli="<SELECT   NAME='Tipo_solicitud' ID='TIPO_SOLICITUD' >
					  <OPTION VALUE=''   >--- SELECCIONAR OPCIÓN ---</OPTION>\n";
					  $Select=(empty($Tipo_solicitud) || ($Tipo_solicitud == 'solicitud') )?('SELECTED'):('');
	$combo_tipo_soli.="<OPTION VALUE='solicitud'       ".$Select." >SOLICITUD ACTIVA</OPTION> \n";
					 $Select=($Tipo_solicitud == 'solicitud_morosa' )?('SELECTED'):('');
	$combo_tipo_soli.="<OPTION VALUE='solicitud_morosa' ".$Select." >SOLICITUD MOROSA</OPTION> \n
					  </SELECT>";
	
	$Filtros.="
				<H3 CLASS='ui-widget-header'>CONSULTA DE SOLICITUDES - CLIENTES MOROSOS.</H3>
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
					<TD STYLE='text-align:left; width:75%;'  >
						".$combo."
					</TD>
				</TR>
				
				<TR ALIGN='center' VALIGN='middle' BgCOLOR='#eef5e5'>
					<TD STYLE='text-align:right; font-weight:bold; font-size:small; width:25%;' >
						TIPO DE SOLICITUD : &nbsp;
					</TD>
					<TD STYLE='text-align:left; width:75%;'  >
						".$combo_tipo_soli."
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

function check_solicitud($ID_SOLI)
{
	global $db;

	$SQL_CONS="SELECT
						COUNT(id_factura) AS CUANTOS
				FROM
					solicitud
					INNER JOIN clientes_datos ON solicitud.ID_Solicitud 		= 	clientes_datos.ID_Solicitud
					INNER JOIN fact_cliente	  ON clientes_datos.Num_cliente		= 	fact_cliente.num_cliente
				WHERE
						solicitud.ID_Solicitud = '".$ID_SOLI."' ";
	$rs_cons=$db->Execute($SQL_CONS);

return $rs_cons->fields["CUANTOS"];
}


function check_solicitud_solidario($ID_SOLI)
{
	global $db;



	$SQL_CONS="SELECT
						ID_Tipocredito		AS TIPO_CREDIT,
						ID_grupo_soli		AS GPO_SOLI
				FROM
						solicitud
				WHERE
						ID_Solicitud	= '".$ID_SOLI."' ";
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

function get_tipo_credito($ID_SOLI)
{
	global $db;
	
	$SQL_CONS="SELECT
						ID_Tipocredito		AS TIPO_CREDIT
				FROM
						solicitud
				WHERE
						ID_Solicitud	= '".$ID_SOLI."' ";
	$rs_cons=$db->Execute($SQL_CONS);

	return $rs_cons->fields["TIPO_CREDIT"];
}

function getRealIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
        return $_SERVER['HTTP_CLIENT_IP'];
       
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
   
    return $_SERVER['REMOTE_ADDR'];
}

function set_insert_solicitud_morosa($ID_SOLICITUD,$ID_Tipocredito)
{
	global $db;
	global $ID_USR;
	
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
					
					$SQL_INS = "INSERT INTO
											solicitud_pfisica_morosa (ID_User,ID_Solicitud,Num_cliente,IP)
										VALUES	('".$ID_USR."','".$ID_SOLICITUD."','".$NUM_CTE."','".$IP_CLIENT."') ";
					$db->Execute($SQL_INS);

					if ( $db->_insertid() )
					{
						 if($ID_Tipocredito == 2)
						 {
								$SQL_UPDATE="UPDATE  grupo_solidario_integrantes
												SET
													Cte_moroso		= 'Y'
												WHERE ID_Solicitud = '".$ID_SOLICITUD."' ";
								$db->Execute($SQL_UPDATE);

						 }
								$SQL_UPDATE="UPDATE  solicitud
												SET
													Cte_moroso		= 'Y'
												WHERE ID_Solicitud = '".$ID_SOLICITUD."' ";
								$db->Execute($SQL_UPDATE);

						return "TRUE";
					}
					else
						return "FALSE";
}


/*************FIN FUNCTIONS******************/



/*****************FILTROS**************************/
if(isset($body) )
{
	 /******VALIDACIONES*******/
  $inicia_flujo_por='SOLICITUD';

  $Sql_indiv="SELECT
					Status AS ESTAT
				FROM cat_tipo_credito
					INNER JOIN grupo_tipo_credito ON cat_tipo_credito.ID_Tipocredito = grupo_tipo_credito.ID_Tipocredito
													AND grupo_tipo_credito.ID_Sucursal ='".$ID_SUC."'
				WHERE cat_tipo_credito.ID_Tipocredito ='1' ";
   $rs_idiv=$db->Execute($Sql_indiv);
   $Credit_indiv=($rs_idiv->fields["ESTAT"]=='Activo')?("TRUE"):("FALSE");

  $Sql_nom="SELECT
					Status AS ESTAT
				FROM cat_tipo_credito
					INNER JOIN grupo_tipo_credito ON cat_tipo_credito.ID_Tipocredito = grupo_tipo_credito.ID_Tipocredito
													AND grupo_tipo_credito.ID_Sucursal ='".$ID_SUC."'
				WHERE cat_tipo_credito.ID_Tipocredito ='3' ";
   $rs_nom=$db->Execute($Sql_nom);
   $Credit_nom=($rs_nom->fields["ESTAT"]=='Activo')?("TRUE"):("FALSE");


  $Sql_solid="SELECT
					Status AS ESTAT
				FROM cat_tipo_credito
					INNER JOIN grupo_tipo_credito ON cat_tipo_credito.ID_Tipocredito = grupo_tipo_credito.ID_Tipocredito
													AND grupo_tipo_credito.ID_Sucursal ='".$ID_SUC."'
				WHERE cat_tipo_credito.ID_Tipocredito ='2' ";
   $rs_solid=$db->Execute($Sql_solid);
   $Credit_solid=($rs_solid->fields["ESTAT"]=='Activo')?("TRUE"):("FALSE");

   $Lanzador_tipo_credito=array("CREDIT_INDIV"=>$Credit_indiv,"CREDIT_NOM"=>$Credit_nom,"CREDIT_SOLID"=>$Credit_solid);
  /***********************/

     if( ( $Lanzador_tipo_credito["CREDIT_INDIV"] =='TRUE' ||  $Lanzador_tipo_credito["CREDIT_NOM"]=='TRUE' ) &&  $Lanzador_tipo_credito["CREDIT_SOLID"]=='FALSE' )
		   $Filtros_soli=get_filtros_indiv();

	 if(  $Lanzador_tipo_credito["CREDIT_INDIV"] =='FALSE' &&  $Lanzador_tipo_credito["CREDIT_NOM"]=='FALSE'  &&  $Lanzador_tipo_credito["CREDIT_SOLID"]=='FALSE' )
		 $Filtros_soli ="<H3 CLASS='ui-widget-header' STYLE='font-size:small; color:red;'><B>NO EXISTE NINGÚN TIPO DE CRÉDITO ACTIVO</B></H3>";

  echo $Filtros_soli;

}

if( isset($CREDIT_INDIV) && !empty($CREDIT_INDIV) )
{
	$Filtros_soli=get_filtros_indiv();

	  echo $Filtros_soli;
}



/***************************FIN FILTROS********************************************/

/**************************GRIDS**********************************************/

if( isset($nomb_cliente) && isset($num_folio) && isset($num_cte) && isset($Nmb_promotor) && isset($Tipo_credito) && isset($Fecha_inicio) && isset($Fecha_fin) )
{
		//TABLA
		$TABLA ="solicitud";
		
		
		//DISCRIMINANTES
		$filtro_nombre="(Concat(".$TABLA.".Nombre,".$TABLA.".NombreI,".$TABLA.".Ap_paterno,".$TABLA.".Ap_materno))";
		$Discriminante ="";
		
		if( empty($Tipo_solicitud) || ($Tipo_solicitud == 'solicitud') )
			$Discriminante.="	AND Cte_moroso = 'N' ";
		else
			$Discriminante.="	AND Cte_moroso = 'Y' ";

			
		if(!empty($nomb_cliente) )
			{
			  $nomb_cliente=str_replace(" ", "", $nomb_cliente);
			  $Discriminante.=" AND ".$filtro_nombre."   		LIKE '%".$nomb_cliente."%' ";
			 }

		if(!empty($num_folio))
		   $Discriminante.=" AND	".$TABLA.".ID_Solicitud 		=  '".$num_folio."' ";

		if(!empty($num_cte))
		   $Discriminante.=" AND	clientes_datos.Num_cliente 	=  '".$num_cte."' ";

		if(!empty($Nmb_promotor))
		   $Discriminante.=" AND	promotores.Nombre 			=  '".$Nmb_promotor."' ";

		if(!empty($Tipo_credito))
		   $Discriminante.=" AND	".$TABLA.".ID_Tipocredito 	=  '".$Tipo_credito."' ";
		   
		 if(!empty($Fecha_inicio) || !empty($Fecha_fin))
		 {
			if( !empty($Fecha_inicio) && !empty($Fecha_fin)  )
				 $Discriminante.=" AND	".$TABLA.".Fecha BETWEEN '".gfecha($Fecha_inicio)."' AND '".gfecha($Fecha_fin)."' ";

			if( !empty($Fecha_inicio) && empty($Fecha_fin)  )
				 $Discriminante.=" AND	".$TABLA.".Fecha BETWEEN '".gfecha($Fecha_inicio)."' AND '".gfecha($Fecha_inicio)."' ";

			if( empty($Fecha_inicio) && !empty($Fecha_fin)  )
				 $Discriminante.=" AND	".$TABLA.".Fecha BETWEEN '".gfecha($Fecha_fin)."' AND '".gfecha($Fecha_fin)."' ";
				 
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

					  $SUCURSAL_VIEW = ($ID_SUC =='1')?(""):(" AND solicitud.ID_Sucursal='".$ID_SUC."' ");
					  $tabla="solicitud";
					  $select_nombre="(Concat(".$TABLA.".Nombre,' ',".$TABLA.".NombreI,' ',".$TABLA.".Ap_paterno,' ',".$TABLA.".Ap_materno))";

					  $Sql_cons="SELECT
										".$TABLA.".ID_Solicitud 													AS IDSOLI,
										".$select_nombre." 															AS CLIENTE,
										sucursales.Nombre  															AS SUC,
										IF(promotores.Nombre IS NOT NULL, promotores.Nombre,'SIN PROMOTOR')  		AS PROMO,
										".$TABLA.".Status_solicitud   												AS STATSOLI,
										IF(".$TABLA.".Nomina='Y','NÓMINA','INDIVIDUAL')   							AS PROSPECT,
										".$TABLA.".ID_Tipocredito         											AS TP_CREDIT,
										".$TABLA.".Fecha		          											AS FECH_CAPT,
										".$TABLA.".Renovacion_credit      											AS RENOV,
										IF(clientes_datos.Num_cliente != 0,clientes_datos.Num_cliente,'S/N')		AS NUM_CTE,
										".$TABLA.".ID_Tipocredito 													AS TIPO_CREDIT,
										IF( ".$TABLA.".ID_Tipocredito = 1,'CRÉDITO INDIVIDUAL',(  IF( ".$TABLA.".Solicitud_expres='Y','CRÉDITO EXPRÉS'       ,    IF(".$TABLA.".ID_Tipocredito = 2,'CRÉDITO SOLIDARIO','CRÉDITO NÓMINA') )     )      ) 								AS TIPO_CREDIT_II,
										grupo_solidario.ID_grupo_soli												AS ID_GPO,
										".$TABLA.".Solicitud_expres													AS EXPRS	\n";
						
						 $Sql_form="FROM    ".$TABLA."
									LEFT JOIN promotores      ON ".$TABLA.".ID_Promotor  = promotores.Num_promo
									LEFT JOIN sucursales      ON ".$TABLA.".ID_sucursal  = sucursales.ID_Sucursal
									LEFT JOIN clientes_datos  ON ".$TABLA.".ID_Solicitud = clientes_datos.ID_Solicitud
									LEFT JOIN grupo_solidario ON ".$TABLA.".ID_grupo_soli = grupo_solidario.ID_grupo_soli
																AND ".$TABLA.".ID_Tipocredito='2'
																AND ".$TABLA.".ID_grupo_soli <> 0

								WHERE
									Status_solicitud IS NOT NULL
									".$Discriminante."
									".$SUCURSAL_VIEW."
									LIMIT ". $LIMIT_INI." , ". $LIMIT_FIN." ";

						$Sql_cons.=$Sql_form;
					$rs_cons=$db->Execute($Sql_cons);

		/**********************************/
		/******DISPLAY NAVIGATION BAR******/
			 $Sql_navegation="SELECT
										COUNT(".$TABLA.".ID_Solicitud)	AS CUANTOS 	\n";

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
						   <TH STYLE='font-size:small;  text-align:left; color:white; text-decoration:underline; cursor:pointer;'    WIDTH='18%' >     			PROMOTOR       </TH>
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

					  $Expres_trad		=($Tipo_solicitud == 'solicitud')?("<IMG SRC='".$img_path."minus-shield.png'  STYLE='height:18px; width:18px; cursor:pointer;' LANG='".$rs_cons->fields["IDSOLI"]."' 	CLASS='SOLI_MOROSA' />"):("&nbsp;");
					  /**************/

					  $Table_grid .="<TR ALIGN='center' VALIGN='middle' BgCOLOR='".$row_color ."' 												ONMOUSEOVER=\"javascript:this.style.backgroundColor='#FBFAAE'; this.style.cursor='hand'; \"
						 ONMOUSEOUT =\"javascript:this.style.backgroundColor='' \"  BgCOLOR='".$row_color ."'>
											<TH STYLE='font-size:small; text-decoration:underline; text-align:center; color:gray; cursor:pointer;' 	WIDTH='7%' CLASS='SHOW_DETAIL_SOLI' ID='".$rs_cons->fields["IDSOLI"]."'>
												".$rs_cons->fields["IDSOLI"]."
											</TH>

											<TH STYLE='font-size:small;  text-align:left;   ' 				WIDTH='30%' >    ".strtoupper($rs_cons->fields["CLIENTE"])."		</TH>
											<TH STYLE='font-size:small;  text-align:left; color:navy;' 	    WIDTH='15%' >    ".strtoupper($rs_cons->fields["TIPO_CREDIT_II"])."	</TH>
											<TH STYLE='font-size:small;  text-align:left; ' 				WIDTH='18%' >    ".strtoupper($rs_cons->fields["PROMO"])."		 	</TH>
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


if( isset($SOLI_MOROSA_MSG) && !empty($SOLI_MOROSA_MSG) && isset($ID_SOLI) && !empty($ID_SOLI))
{
		$NMB_CTE_SOLI 			=	get_nombre_cte($ID_SOLI);
		//$VALIDA_SOLICITUD		= 	check_solicitud($ID_SOLI);
		//$VALID_TIPO_CREDIT	=	check_solicitud_solidario($ID_SOLI);

		echo "<INPUT TYPE='HIDDEN' ID='ID_SOLICITUD'          VALUE='".$ID_SOLI."'>
			  <IMG SRC='".$img_path."exclamation-diamond-frame.png'  STYLE='height:18px; width:18px;' />&nbsp; &nbsp; <FONT STYLE='font-weight:bold; text-align:center;' >SE HA SELECCIONADO MARCAR, CON ESTATUS DE MOROSIDAD, AL CLIENTE CON NOMBRE :  <BR /> <BR /> <SPAN STYLE='text-decoration:underline;'>\" ".$NMB_CTE_SOLI." \"</SPAN> 	</FONT><BR /><BR /><BUTTON   ID='SOLI_MOROSA_CONFRIM' >MARCAR CLIENTE MOROSO</BUTTON>";

}

if( isset($SOLI_MOROSA_CONFIRM) && !empty($SOLI_MOROSA_CONFIRM) && isset($ID_SOLI) && !empty($ID_SOLI))
{
	$ID_Tipocredito	= get_tipo_credito($ID_SOLI);
	$RESULT_MOROSO  = set_insert_solicitud_morosa($ID_SOLI,$ID_Tipocredito);

	echo $RESULT_MOROSO;	
	
}
?>
