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

function get_lanzador_solid($Lanzador_tipo_credito)
{
	$Msj_button=($Lanzador_tipo_credito["CREDIT_INDIV"] =='TRUE'  && $Lanzador_tipo_credito["CREDIT_NOM"]=='TRUE' )?("CRÉDITO  GENERAL"):("CRÉDITO  PERSONAL");
	$Msj_button=($Lanzador_tipo_credito["CREDIT_INDIV"] =='FALSE' && $Lanzador_tipo_credito["CREDIT_NOM"]=='TRUE' )?("CRÉDITO  NÓMINA"):($Msj_button);
	
	 $html="";
	 $html.="<H3 CLASS='ui-widget-header' STYLE='font-size:medium;'>SELECCIONE El TIPO DE SOLICITUD A CONSULTAR</H3>
			<BR />";
					
	 $html.="<TABLE CLASS='tblReporte' CELLPADDING='3' CELLSPACING='1'>";

	 $html.="	<TD CLASS='centro' WIDTH='33%'>
						<BR>
					<BUTTON ID='CREDIT_INDIV_NOM' STYLE='cursor:pointer;' >".$Msj_button."</BUTTON>
					
					<BR><BR>
				</TD>

				<TD CLASS='centro' WIDTH='33%'
						<BR><BR>
					<BUTTON ID='CREDIT_SOLID' STYLE='cursor:pointer;' >CRÉDITO SOLIDARIO</BUTTON>
					
					<BR><BR>				
				</TD>
			</TR>
		</TABLE><BR />";

	return $html;
}

function get_filtros_solid()
{
	$Filtros="";
	
	$Filtros.="
				<H3 CLASS='ui-widget-header'>CONSULTA DE SOLICITUDES.</H3>
				<BR />
				<TABLE  CELLSPACING='0' STYLE='' ALIGN='CENTER' BORDER='0px' WIDTH='99%'>
					
				<TR ALIGN='center' VALIGN='middle' BgCOLOR='#eef5e5'>
					<TD STYLE='text-align:right; font-weight:bold; font-size:small; width:25%;' >
						NOMBRE DEL GRUPO : &nbsp;
					</TD>
					<TD STYLE='text-align:left; width:75%;'  >
						<INPUT TYPE='TEXT' NAME='nomb_gpo' ID='NMB_GPO' VALUE='".$nomb_gpo."'  SIZE='30' >
					</TD>
				</TR>

				<TR ALIGN='center' VALIGN='middle' BgCOLOR='#eef5e5'>
					<TD STYLE='text-align:right; font-weight:bold; font-size:small; width:25%;' >
						ID. GRUPO : &nbsp;
					</TD>
					<TD STYLE='text-align:left; width:75%;'  >
						<INPUT TYPE='TEXT' NAME='id_gpo' ID='ID_GPO' VALUE='".$id_gpo."'  SIZE='10' CLASS='SOLO_NUMEROS'>
					</TD>
				</TR>

				<TR ALIGN='center' VALIGN='middle' BgCOLOR='#eef5e5'>
					<TD STYLE='text-align:right; font-weight:bold; font-size:small; width:25%;' >
						PROMOTOR : &nbsp;
					</TD>
					<TD STYLE='text-align:left; width:75%;'  >
						<INPUT TYPE='TEXT' NAME='promotor_gpo' ID='PROMO_GPO' VALUE='".$promotor_gpo."'  SIZE='30' >
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
			<BUTTON  ID='SEARCH_SOLIS_SOLID'   NAME='Buscar_soli' VALUE='TRUE' STYLE='font-size:x-small;' > &nbsp;&nbsp; BUSCAR GRUPO. </BUTTON>
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


	 if( ( $Lanzador_tipo_credito["CREDIT_INDIV"] =='FALSE' &&  $Lanzador_tipo_credito["CREDIT_NOM"]=='FALSE' ) &&  $Lanzador_tipo_credito["CREDIT_SOLID"]=='TRUE' )
			$Filtros_soli=get_filtros_solid();

	 if(  $Lanzador_tipo_credito["CREDIT_INDIV"] =='FALSE' &&  $Lanzador_tipo_credito["CREDIT_NOM"]=='FALSE'  &&  $Lanzador_tipo_credito["CREDIT_SOLID"]=='FALSE' )
		 $Filtros_soli ="<H3 CLASS='ui-widget-header' STYLE='font-size:small; color:red;'><B>NO EXISTE NINGÚN TIPO DE CRÉDITO ACTIVO</B></H3>";

  echo $Filtros_soli;

}

if( isset($CREDIT_SOLIDARIO) && !empty($CREDIT_SOLIDARIO) )
{
	$Filtros_soli=get_filtros_solid();

	  echo $Filtros_soli;
}

/***************************FIN FILTROS********************************************/

/**************************GRIDS**********************************************/
if( isset($Nmb_gpo) &&  isset($Id_gpo) && isset($Id_gpo) && isset($Promotor) && isset($Fecha_inicio) && isset($Fecha_fin) )
{

		//DISCRIMINANTES
		$Discriminante ="";

		if(!empty($Nmb_gpo) )
			  $Discriminante.=" AND grupo_solidario.Nombre		       LIKE '%".$Nmb_gpo."%' ";


		if(!empty($Id_gpo))
		   $Discriminante.="    AND	 grupo_solidario.ID_grupo_soli		=  '".$Id_gpo."' ";


		if(!empty($Promotor))
		   $Discriminante.=" AND	promotores.Nombre 			 LIKE '%".$Promotor."%'  ";

		   
		 if(!empty($Fecha_inicio) || !empty($Fecha_fin))
		 {
			if( !empty($Fecha_inicio) && !empty($Fecha_fin)  )
				 $Discriminante.=" AND	grupo_solidario.Fecha_captura BETWEEN '".gfecha($Fecha_inicio)."' AND '".gfecha($Fecha_fin)."' ";

			if( !empty($Fecha_inicio) && empty($Fecha_fin)  )
				 $Discriminante.=" AND	grupo_solidario.Fecha_captura BETWEEN '".gfecha($Fecha_inicio)."' AND '".gfecha($Fecha_inicio)."' ";

			if( empty($Fecha_inicio) && !empty($Fecha_fin)  )
				 $Discriminante.=" AND	grupo_solidario.Fecha_captura BETWEEN '".gfecha($Fecha_fin)."' AND '".gfecha($Fecha_fin)."' ";
				 
		 }

  	/***********************************/
	/********NAVIGATION BAR*************/
			 $sql_params="SELECT Valor
					  FROM constantes
					 WHERE Nombre = 'GRID_RENGLONES_POR_PAGINA_PROMOCION'";
			 $rs=$db->execute($sql_params);
			 $rows_grid=$rs->fields["Valor"];
			 $LIMIT_INI=(empty($LIMIT_INI))?('0'):($LIMIT_INI);
			 $LIMIT_FIN=$rows_grid;
	/*********QUERY**************/

					  $Discriminante.=($ID_SUC =='1')?(""):(" AND grupo_solidario.ID_Suc ='".$ID_SUC."' ");
					  $tabla="grupo_solidario";


					  $Sql_select="SELECT
										grupo_solidario.ID_grupo_soli								AS ID_GPO,
										UCASE(grupo_solidario.Nombre)								AS NMB_GPO,
										grupo_solidario.Status_grupo								AS STAT_GPO,
										grupo_solidario.Ciclo_gpo									AS CICLO_GPO,
										UCASE(promotores.Nombre)									AS PROMO_GPO,
										COUNT(grupo_solidario_integrantes.ID_grupo_soli)			AS INTEG_GPO,
										sucursales.Nombre  											AS SUC_GPO,
										grupo_solidario.Fecha_captura								AS FECH_GPO \n";
										
						$Sql_form="FROM
									grupo_solidario
									LEFT JOIN promotores 							ON grupo_solidario.ID_Promotor 		         = promotores.Num_promo
									LEFT JOIN grupo_solidario_integrantes		    ON grupo_solidario.ID_grupo_soli		     = grupo_solidario_integrantes.ID_grupo_soli
																								AND grupo_solidario.Ciclo_gpo    = grupo_solidario_integrantes.Ciclo_gpo
																								AND grupo_solidario_integrantes.Ciclo_renovado ='N'
																								AND grupo_solidario_integrantes.`Status`='Activo'
									LEFT JOIN sucursales      						ON grupo_solidario.ID_Suc					  = sucursales.ID_Sucursal

								WHERE	grupo_solidario.ID_grupo_soli IS NOT NULL
										".$Discriminante."
								GROUP BY grupo_solidario.ID_grupo_soli
								 LIMIT ". $LIMIT_INI." , ". $LIMIT_FIN." ";

					  $Sql_select.=$Sql_form;
					  $rs_cons= $db->Execute($Sql_select);
		/*****************************/
		 /**NAVIGATION BAR***********/
			 $Sql_navegation="SELECT
										COUNT(grupo_solidario.ID_grupo_soli)	AS CUANTOS 	\n";

			
			 $Sql_form="FROM
									grupo_solidario
									LEFT JOIN promotores 							ON grupo_solidario.ID_Promotor 		         = promotores.Num_promo
						LEFT JOIN sucursales      						ON grupo_solidario.ID_Suc					  = sucursales.ID_Sucursal

								WHERE	grupo_solidario.ID_grupo_soli IS NOT NULL
										".$Discriminante." \n";
			 $Sql_navegation.=$Sql_form;
			
		 $Bar_navegation=get_pagination($Sql_navegation,$rows_grid,$CURRENT_PAGE,2);
		 /********************************/
		 
	$Table_grid="<TABLE  CELLSPACING='0' STYLE='' ALIGN='CENTER' BORDER='0px' WIDTH='99%'  CLASS='tablesorter' ID='TBL_SOLI_SOLID'>
				<THEAD>
				
					<TR>
						<TD  ALIGN='CENTER' COLSPAN='9'  STYLE='-moz-border-radius-topleft:  6px; -moz-border-radius-topright: 6px;  background-color : #6fa7d1;'>
							<B> <FONT SIZE='2' COLOR='WHITE'>SOLICITUDES DE CRÉDITO</FONT></B>
						</TD>
					</TR>
					
				
					<TR ALIGN='center' VALIGN='middle'  BGCOLOR='#6fa7d1' STYLE='height:30px;'>
						   <TH STYLE='font-size:small;  text-align:center;  color:white; text-decoration:underline; cursor:pointer;'    WIDTH='5%' >     			ID             </TH>
						   <TH STYLE='font-size:small;  text-align:left;    color:white; text-decoration:underline; cursor:pointer;'    WIDTH='25%' >     			GRUPO          </TH>
						   <TH STYLE='font-size:small;  text-align:center;  color:white; text-decoration:underline; cursor:pointer;'    WIDTH='5%' >     			CICLO          </TH>
						   <TH STYLE='font-size:small;  text-align:center;  color:white; text-decoration:underline; cursor:pointer;'    WIDTH='15%' >     			STATUS		   </TH>
						   <TH STYLE='font-size:small;  text-align:left;    color:white; text-decoration:underline; cursor:pointer;'    WIDTH='15%' >     			PROMOTOR       </TH>
						   <TH STYLE='font-size:small;  text-align:left;    color:white; text-decoration:underline; cursor:pointer;'    WIDTH='15%' >     			SUCURSAL       </TH>
						   <TH STYLE='font-size:small;  text-align:left;    color:white; text-decoration:underline; cursor:pointer;'    WIDTH='15%' >     			FECHA CAPTURA  </TH>
						   <TH STYLE='font-size:small;  text-align:center;  color:white; text-decoration:underline; cursor:pointer;'    WIDTH='5%' >     			INTEGRANTES    </TH>
						   <TH STYLE='font-size:small;  text-align:left;    color:white; text-decoration:underline;                '    WIDTH='5%' >     						   </TH>
					 </TR>
			</THEAD>";
					$cont=1;
					While(!$rs_cons->EOF)
					{
					  /**************/
					  $row_color       =(($cont % 2) == 0 )?('#FDFEFF'):('#E7EEF6');
					  /**************/
					  $Table_grid .="<TR ALIGN='center' VALIGN='middle' BgCOLOR='".$row_color ."' 												          			ONMOUSEOVER=\"javascript:this.style.backgroundColor='#FBFAAE'; this.style.cursor='hand'; \"
						 ONMOUSEOUT =\"javascript:this.style.backgroundColor='' \"  BgCOLOR='".$row_color ."'>

											<TH STYLE='font-size:small; text-decoration:underline; text-align:center; color:gray; cursor:pointer;' 	WIDTH='5%' CLASS='SHOW_DETAIL_SOLIDARIO' ID='".$rs_cons->fields["ID_GPO"]."'>
												".$rs_cons->fields["ID_GPO"]."
											</TH>

											<TH STYLE='font-size:small;  text-align:left;   ' 				WIDTH='25%' >    ".strtoupper($rs_cons->fields["NMB_GPO"])."		</TH>
											<TH STYLE='font-size:small;  text-align:center; '               WIDTH='5%' >     ".strtoupper($rs_cons->fields["CICLO_GPO"])."		</TH>
											<TH STYLE='font-size:small;  text-align:left; color:navy;' 		WIDTH='15%' >    ".strtoupper($rs_cons->fields["STAT_GPO"])."		</TH>
											<TH STYLE='font-size:small;  text-align:left; ' 			    WIDTH='15%' >    ".strtoupper($rs_cons->fields["PROMO_GPO"])."		</TH>
											<TH STYLE='font-size:small;  text-align:left; ' 				WIDTH='15%' >    ".strtoupper($rs_cons->fields["SUC_GPO"])."		</TH>
											<TH STYLE='font-size:small;  text-align:left; color:gray;' 		WIDTH='15%' >    ".ffecha($rs_cons->fields["FECH_GPO"])."		    </TH>
											<TH STYLE='font-size:small;  text-align:center; ' 				WIDTH='5%' >    ".strtoupper($rs_cons->fields["INTEG_GPO"])."		</TH>
											<TH STYLE='font-size:small;' WIDTH='5%' ><IMG SRC='".$img_path."up_arrow.png'  STYLE='height:18px; width:18px; cursor:pointer;' ONCLICK='window.location.replace(\"../../../compartidos/soli_docs_gpo.php?Param1=ID_grupo_soli&Param2=".$rs_cons->fields["ID_GPO"]."\");'/></TH>
									 </TR>";
									 
					  $cont++;
					  $rs_cons->MoveNext();
					}

					if($cont==1)
					{
					  $Table_grid .="<TR ALIGN='center' VALIGN='middle' BgCOLOR='white' >
											<TH STYLE='font-size:small;  text-align:center; color:gray; height:50px;' COLSPAN='9' >
												<IMG SRC='".$img_path."exclamation.png'  STYLE='height:15px; cursor:pointer;' /> &nbsp; NO SE ENCONTRARON GRUPOS, CONFORME EL CRITERIO DE BÚSQUEDA.
											</TH>
									 </TR>";
					}
					
	$Table_grid.="</TABLE>";


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


if(isset($Show_detail_solidario) && !empty($Show_detail_solidario) && isset($ID_GPO) && !empty($ID_GPO))
{
	/*********QUERY**************/
	$Sql_cons = "SELECT
					grupo_solidario_integrantes.ID_Solicitud																AS ID_SOLI,
				    (Concat(solicitud.Nombre,' ',solicitud.NombreI,' ',solicitud.Ap_paterno,' ',solicitud.Ap_materno)) 		AS NMB_CTE,
					solicitud.Status																						AS STAT,
					solicitud.Status_solicitud																				AS STAT_SOLI,
					UCASE(grupo_solidario.Nombre)																			AS NMB_GPO,
					grupo_solidario.Status_grupo																			AS STAT_GPO,
					solicitud.Telefono           AS TEL,
					solicitud.Tel_contacto       AS TELCONT,
					solicitud.Num_celular        AS CEL,
					solicitud.Email    AS EMAIL,
					CONCAT(if(solicitud.Calle IS NULL,'',solicitud.Calle),' ',if(solicitud.Numero IS NULL,'',solicitud.Numero),' ',if(solicitud.Interior IS NULL,'',solicitud.Interior),', COL. ',if(solicitud.Colonia IS NULL,'',solicitud.Colonia),', ',if(solicitud.Poblacion IS NULL,'',solicitud.Poblacion),', ',if(solicitud.Estado IS NULL,'',solicitud.Estado),', C.P. ',if(solicitud.CP IS NULL,'',solicitud.CP) ) AS DOMCTE
			FROM grupo_solidario
				 
				 LEFT JOIN grupo_solidario_integrantes ON grupo_solidario_integrantes.ID_grupo_soli 		= grupo_solidario.ID_grupo_soli
														AND grupo_solidario_integrantes.Ciclo_gpo		 	= grupo_solidario.Ciclo_gpo
														AND grupo_solidario_integrantes.Ciclo_renovado='N'

				LEFT JOIN solicitud       ON solicitud.ID_Solicitud = grupo_solidario_integrantes.ID_Solicitud
       WHERE
				grupo_solidario.ID_grupo_soli = '".$ID_GPO."'  ";
	$rs_cons=$db->Execute($Sql_cons);

	/***************************/

	
	$Table_detail="				<BR />
								<H3 CLASS='ui-widget-header' STYLE='font-size:x-medium; font-weight:bold; width:90%;'>".strtoupper($rs_cons->fields["NMB_GPO"])."
								</H3>
								<BR />";
								
	$Table_detail.="			<TABLE CELLSPACING='3' STYLE='' ALIGN='CENTER' BORDER='0px' WIDTH='90%'>";
	$Table_detail.="		   <TR STYLE='font-size:small;'>
									<TH STYLE='text-align:left;   width:5%;' 			 >Status</TH>
									<TH STYLE='text-align:center; width:1%;' 			 >:</TH>
									<TD STYLE='text-align:left; text-decoration:underline;  width:94%; color:navy;'>".strtoupper($rs_cons->fields["STAT_GPO"])."</TD>
								</TR>

						       <TR STYLE='font-size:small;'>
									<TD COLSAPN='3'><BR /></TD>
								</TR>";

		$cont=1;
		While(!$rs_cons->EOF)
		{
			if(!empty($rs_cons->fields["ID_SOLI"]))
			{
					$str_ctes.="		<TR STYLE='font-size:small;'>
												<TD STYLE='text-align:right;   width:5%;' >".$rs_cons->fields["ID_SOLI"]."</TH>
												<TD STYLE='text-align:center; width:1%;' > .-</TH>
												<TD STYLE='text-align:left;   width:94%;'><B>".$rs_cons->fields["NMB_CTE"]."</B></TD>
										</TR>";
					$cont++;
			}
		  $rs_cons->MoveNext();
		}

		if($cont==1)
		  $str_ctes .="<TR ALIGN='center' VALIGN='middle' BgCOLOR='white' >
							<TH STYLE='font-size:small;  text-align:center; color:gray; height:50px;' COLSPAN='9' >
							<IMG SRC='".$img_path."exclamation.png'  STYLE='height:15px; cursor:pointer;' /> &nbsp; SIN INTEGRANTES ACTUALES.
							</TH>
					    </TR>";

									
	$Table_detail.=$str_ctes."</TABLE>";
	
	echo $Table_detail;

}



?>
