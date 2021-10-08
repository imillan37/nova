<?
/****************************************/
/*Fecha: 30/Noviembre/2011
/*Autor: Tonathiu C¡rdenas
/*Descripci³n: GENERA LA TABLA DE SOLICITUDES
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
function get_combo_tipo_credit($OPTION_SELECT)
{
	global $db;
	global $ID_SUC;

	$Cont_tipo_credit=0;

    
    $combo="";
    
	  $Sql_indiv="SELECT
					Status AS ESTAT
				FROM cat_tipo_credito
					INNER JOIN grupo_tipo_credito ON cat_tipo_credito.ID_Tipocredito = grupo_tipo_credito.ID_Tipocredito
													AND grupo_tipo_credito.ID_Sucursal ='".$ID_SUC."'
				WHERE cat_tipo_credito.ID_Tipocredito ='1' ";
   $rs_idiv=$db->Execute($Sql_indiv);
   $SELECTED = ($OPTION_SELECT == 1)?("SELECTED"):("");
   $combo.=($rs_idiv->fields["ESTAT"]=='Activo')?("<OPTION VALUE='1' ".$SELECTED." >CR&Eacute;DITO INDIVIDUAL</OPTION> \n"):("");

   if($rs_idiv->fields["ESTAT"]=='Activo')
		$Cont_tipo_credit++;
		

  $Sql_solid="SELECT
					Status AS ESTAT
				FROM cat_tipo_credito
					INNER JOIN grupo_tipo_credito ON cat_tipo_credito.ID_Tipocredito = grupo_tipo_credito.ID_Tipocredito
													AND grupo_tipo_credito.ID_Sucursal ='".$ID_SUC."'
				WHERE cat_tipo_credito.ID_Tipocredito ='2' ";
   $rs_solid=$db->Execute($Sql_solid);
   $SELECTED = ($OPTION_SELECT == 2)?("SELECTED"):("");
   $combo.=($rs_solid->fields["ESTAT"]=='Activo')?("<OPTION VALUE='2'  ".$SELECTED.">CR&Eacute;DITO SOLIDARIO</OPTION> \n"):("");

   if($rs_solid->fields["ESTAT"]=='Activo')
		$Cont_tipo_credit++;


  $Sql_nom="SELECT
					Status AS ESTAT
				FROM cat_tipo_credito
					INNER JOIN grupo_tipo_credito ON cat_tipo_credito.ID_Tipocredito = grupo_tipo_credito.ID_Tipocredito
													AND grupo_tipo_credito.ID_Sucursal ='".$ID_SUC."'
				WHERE cat_tipo_credito.ID_Tipocredito ='3' ";
   $rs_nom=$db->Execute($Sql_nom);
   $SELECTED = ($OPTION_SELECT == 3)?("SELECTED"):("");
   $combo.=($rs_nom->fields["ESTAT"]=='Activo')?("<OPTION VALUE='3'  ".$SELECTED.">CR&Eacute;DITO N&Oacute;MINA</OPTION> \n"):("");

   if($rs_nom->fields["ESTAT"]=='Activo')
		$Cont_tipo_credit++;



  $Sql_fact="SELECT
					Status AS ESTAT
				FROM cat_tipo_credito
					INNER JOIN grupo_tipo_credito ON cat_tipo_credito.ID_Tipocredito = grupo_tipo_credito.ID_Tipocredito
													AND grupo_tipo_credito.ID_Sucursal ='".$ID_SUC."'
				WHERE cat_tipo_credito.ID_Tipocredito ='4' ";
   $rs_fact=$db->Execute($Sql_fact);
   $SELECTED = ($OPTION_SELECT == 4)?("SELECTED"):("");
   $combo.=($rs_fact->fields["ESTAT"]=='Activo')?("<OPTION VALUE='4' ".$SELECTED." >CR&Eacute;DITO FACTORAJE</OPTION> \n"):("");

   if($rs_fact->fields["ESTAT"]=='Activo')
		$Cont_tipo_credit++;


  $Sql_empr="SELECT
					Status AS ESTAT
				FROM cat_tipo_credito
					INNER JOIN grupo_tipo_credito ON cat_tipo_credito.ID_Tipocredito = grupo_tipo_credito.ID_Tipocredito
													AND grupo_tipo_credito.ID_Sucursal ='".$ID_SUC."'
				WHERE cat_tipo_credito.ID_Tipocredito ='5' ";
   $rs_empr=$db->Execute($Sql_empr);
   $SELECTED = ($OPTION_SELECT == 5)?("SELECTED"):("");
   $combo.=($rs_empr->fields["ESTAT"]=='Activo')?("<OPTION VALUE='5' ".$SELECTED." >CR&Eacute;DITO EMPRESARIAL</OPTION> \n"):("");

   if($rs_empr->fields["ESTAT"]=='Activo')
		$Cont_tipo_credit++;


	$Arr_combo = array('HTML'=>$combo,'CONT'=>$Cont_tipo_credit,'CREDIT_INDIV'=>$rs_idiv->fields["ESTAT"],'CREDIT_NOM'=>$rs_nom->fields["ESTAT"],'CREDIT_SOLID'=>$rs_solid->fields["ESTAT"],'CREDIT_PMORAL'=>$rs_fact->fields["ESTAT"],'CREDIT_EMPRESARIAL'=>$rs_empr->fields["ESTAT"]);

	return $Arr_combo;
}

function get_combo_tipo_credit_proceso($ID_Tipocredito)
{

	$Cont_tipo_credit=0;

    
    $combo="";
    

   $combo.=($ID_Tipocredito == '1')?("<OPTION VALUE='1' ".$SELECTED." >CR&Eacute;DITO INDIVIDUAL</OPTION> \n"):("");
   if($ID_Tipocredito == '1')
   {
		$Cont_tipo_credit++;
		$ESTAT_INDIV='Activo';
   }
		

   $combo.=($ID_Tipocredito == '2')?("<OPTION VALUE='2'  ".$SELECTED.">CR&Eacute;DITO SOLIDARIO</OPTION> \n"):("");
   if($ID_Tipocredito == '2')
   {
		$Cont_tipo_credit++;
		$ESTAT_SOLID='Activo';
	}


   $combo.=($ID_Tipocredito == '3')?("<OPTION VALUE='3'  ".$SELECTED.">CR&Eacute;DITO N&Oacute;MINA</OPTION> \n"):("");
   if($ID_Tipocredito == '3')
   {
		$Cont_tipo_credit++;
		$ESTAT_NOMINA='Activo';
	}



   $combo.=($ID_Tipocredito == '4')?("<OPTION VALUE='4' ".$SELECTED." >CR&Eacute;DITO FACTORAJE</OPTION> \n"):("");
   if($ID_Tipocredito == '4')
   {
		$Cont_tipo_credit++;
		$ESTAT_FACTORAJE='Activo';
	}


   $combo.=($ID_Tipocredito == '5')?("<OPTION VALUE='5' ".$SELECTED." >CR&Eacute;DITO EMPRESARIAL</OPTION> \n"):("");
   if($ID_Tipocredito == '5')
   {
		$Cont_tipo_credit++;
		$ESTAT_EMPRESARIAL='Activo';
	}

	$Arr_combo = array('HTML'=>$combo,'CONT'=>$Cont_tipo_credit,'CREDIT_INDIV'=>$ESTAT_INDIV,'CREDIT_NOM'=>$ESTAT_NOMINA,'CREDIT_SOLID'=>$ESTAT_SOLID,'CREDIT_PMORAL'=>$ESTAT_FACTORAJE,'CREDIT_EMPRESARIAL'=>$ESTAT_EMPRESARIAL);

	return $Arr_combo;

}

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
   $combo.=($rs_idiv->fields["ESTAT"]=='Activo')?("<OPTION VALUE='1'  >CR&Eacute;DITO INDIVIDUAL</OPTION> \n"):("");

   if($rs_idiv->fields["ESTAT"]=='Activo')
		$Cont_tipo_credit++;
		

  $Sql_nom="SELECT
					Status AS ESTAT
				FROM cat_tipo_credito
					INNER JOIN grupo_tipo_credito ON cat_tipo_credito.ID_Tipocredito = grupo_tipo_credito.ID_Tipocredito
													AND grupo_tipo_credito.ID_Sucursal ='".$ID_SUC."'
				WHERE cat_tipo_credito.ID_Tipocredito ='3' ";
   $rs_nom=$db->Execute($Sql_nom);
   $combo.=($rs_nom->fields["ESTAT"]=='Activo')?("<OPTION VALUE='3'  >CR&Eacute;DITO NÓMINA</OPTION> \n"):("");

   if($rs_nom->fields["ESTAT"]=='Activo')
		$Cont_tipo_credit++;



  $Sql_solid="SELECT
					Status AS ESTAT
				FROM cat_tipo_credito
					INNER JOIN grupo_tipo_credito ON cat_tipo_credito.ID_Tipocredito = grupo_tipo_credito.ID_Tipocredito
													AND grupo_tipo_credito.ID_Sucursal ='".$ID_SUC."'
				WHERE cat_tipo_credito.ID_Tipocredito ='2' ";
   $rs_solid=$db->Execute($Sql_solid);
   $combo.=($rs_solid->fields["ESTAT"]=='Activo')?("<OPTION VALUE='2'  >CR&Eacute;DITO SOLIDARIO</OPTION> \n"):("");

   if($rs_solid->fields["ESTAT"]=='Activo')
		$Cont_tipo_credit++;


    if($Cont_tipo_credit > 1)
		$combo = $header_cmb."<OPTION VALUE=''   >--- SELECCIONAR OPCIÓN ---</OPTION> \n".$combo;
	else
		$combo = $header_cmb.$combo;

	$combo.="</SELECT>\n";

	$combo_tipo_soli="<SELECT   NAME='Tipo_solicitud' ID='TIPO_SOLICITUD' >
					  <OPTION VALUE=''   >--- SELECCIONAR OPCIÓN ---</OPTION>\n
					  <OPTION VALUE='N'  >SOLICITUD TRADICIONAL</OPTION> \n
					  <OPTION VALUE='Y'  >SOLICITUD EXPR&Eacute;S</OPTION> \n
					  </SELECT>";
	
	$Filtros.="
				<H3 CLASS='ui-widget-header'>CONSULTA DE SOLICITUDES.</H3>
				<BR />
				<TABLE  CELLSPACING='0' STYLE='' ALIGN='CENTER' BORDER='0px' WIDTH='99%'>
				<TR ALIGN='center' VALIGN='middle' BgCOLOR='#eef5e5'>
					<TD STYLE='text-align:right; font-weight:bold; font-size:small; width:25%;' >
						RAZÓN SOCIAL : &nbsp;
					</TD>
					<TD STYLE='text-align:left; width:75%;'  >
						<INPUT TYPE='TEXT' NAME='razon_social' ID='RZN_SOCIAL' VALUE='".$razon_social."'  SIZE='30' >
					</TD>
				</TR>

				<TR ALIGN='center' VALIGN='middle' BgCOLOR='#eef5e5'>
					<TD STYLE='text-align:right; font-weight:bold; font-size:small; width:25%;' >
						REPRESENTANTE LEGAL : &nbsp;
					</TD>
					<TD STYLE='text-align:left; width:75%;'  >
						<INPUT TYPE='TEXT' NAME='nomb_cliente' ID='NMB_CTE' VALUE='".$nomb_cliente."'  SIZE='30' >
					</TD>
				</TR>
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
						TIPO DE CR&Eacute;DITO : &nbsp;
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

function get_lanzador_solid($Lanzador_tipo_credito)
{
	$Msj_button=($Lanzador_tipo_credito["CREDIT_INDIV"] =='TRUE'  && $Lanzador_tipo_credito["CREDIT_NOM"]=='TRUE' )?("CR&Eacute;DITO  GENERAL"):("CR&Eacute;DITO  PERSONAL");
	$Msj_button=($Lanzador_tipo_credito["CREDIT_INDIV"] =='FALSE' && $Lanzador_tipo_credito["CREDIT_NOM"]=='TRUE' )?("CR&Eacute;DITO  NÓMINA"):($Msj_button);
	
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
					<BUTTON ID='CREDIT_SOLID' STYLE='cursor:pointer;' >CR&Eacute;DITO SOLIDARIO</BUTTON>
					
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


function get_filtros_pmoral()
{

	$Filtros="";

    $header_cmb ="<SELECT   NAME='tipo_credito' ID='TIPO_CREDIT' > ";
	
	$Arr_combo 			= get_combo_tipo_credit(4);
	$Cont_tipo_credit	= $Arr_combo["CONT"];
	$combo 				= $Arr_combo["HTML"];


    if($Cont_tipo_credit > 1)
		$combo = $header_cmb."<OPTION VALUE=''   >--- SELECCIONAR OPCI&Oacute;N ---</OPTION> \n".$combo;
	else
		$combo = $header_cmb.$combo;

	$combo.="</SELECT>\n";

	$Filtros.="
				<H3 CLASS='ui-widget-header'>CONSULTA DE SOLICITUDES - CR&Eacute;DITO FACTORAJE.</H3>
				<BR />
				<TABLE  CELLSPACING='0' STYLE='' ALIGN='CENTER' BORDER='0px' WIDTH='99%'>
					
				<TR ALIGN='center' VALIGN='middle' BgCOLOR='#eef5e5'>
					<TD STYLE='text-align:right; font-weight:bold; font-size:small; width:25%;' >
						RAZÓN SOCIAL : &nbsp;
					</TD>
					<TD STYLE='text-align:left; width:75%;'  >
						<INPUT TYPE='TEXT' NAME='razon_social' ID='RZN_SOCIAL' VALUE='".$razon_social."'  SIZE='30' >
					</TD>
				</TR>


				<TR ALIGN='center' VALIGN='middle' BgCOLOR='#eef5e5'>
					<TD STYLE='text-align:right; font-weight:bold; font-size:small; width:25%;' >
						ID. SOLICITUD : &nbsp;
					</TD>
					<TD STYLE='text-align:left; width:75%;'  >
						<INPUT TYPE='TEXT' NAME='id_solicitud' ID='ID_SOLI' VALUE='".$id_solicitud."'  SIZE='10' CLASS='SOLO_NUMEROS'>
					</TD>
				</TR>

				<TR ALIGN='center' VALIGN='middle' BgCOLOR='#eef5e5'>
					<TD STYLE='text-align:right; font-weight:bold; font-size:small; width:25%;' >
						PROMOTOR : &nbsp;
					</TD>
					<TD STYLE='text-align:left; width:75%;'  >
						<INPUT TYPE='TEXT' NAME='promotor_soli' ID='PROMO_SOLI' VALUE='".$promotor_soli."'  SIZE='30' >
					</TD>
				</TR>

				<TR ALIGN='center' VALIGN='middle' BgCOLOR='#eef5e5'>
					<TD STYLE='text-align:right; font-weight:bold; font-size:small; width:25%;' >
						TIPO DE CR&Eacute;DITO : &nbsp;
					</TD>
					<TD STYLE='text-align:left; width:75%;'  >
						".$combo."
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
			<BUTTON  ID='SEARCH_SOLIS_PMORAL'   NAME='Buscar_soli' VALUE='TRUE' STYLE='font-size:x-small;' > &nbsp;&nbsp; BUSCAR SOLICITUD. </BUTTON>
			<INPUT TYPE='HIDDEN' ID='CURRENT_CREDIT'         VALUE='4'>
			<BR /><BR /><BR />
					<DIV ID='grid_seccion' > </DIV>";

	return $Filtros;


}


function get_filtros_empresarial()
{

	$Filtros="";
    
    $header_cmb ="<SELECT   NAME='tipo_credito' ID='TIPO_CREDIT' > ";
	
	$Arr_combo 			= get_combo_tipo_credit(5);
	$Cont_tipo_credit	= $Arr_combo["CONT"];
	$combo 				= $Arr_combo["HTML"];


    if($Cont_tipo_credit > 1)
		$combo = $header_cmb."<OPTION VALUE=''   >--- SELECCIONAR OPCI&Oacute;N ---</OPTION> \n".$combo;
	else
		$combo = $header_cmb.$combo;

	$combo.="</SELECT>\n";

	$Filtros.="
				<H3 CLASS='ui-widget-header'>CONSULTA DE SOLICITUDES - CR&Eacute;DITO EMPRESARIAL.</H3>
				<BR />
				<TABLE  CELLSPACING='0' STYLE='' ALIGN='CENTER' BORDER='0px' WIDTH='99%'>
					
				<TR ALIGN='center' VALIGN='middle' BgCOLOR='#eef5e5'>
					<TD STYLE='text-align:right; font-weight:bold; font-size:small; width:25%;' >
						RAZÓN SOCIAL : &nbsp;
					</TD>
					<TD STYLE='text-align:left; width:75%;'  >
						<INPUT TYPE='TEXT' NAME='razon_social' ID='RZN_SOCIAL' VALUE='".$razon_social."'  SIZE='30' >
					</TD>
				</TR>

				<TR ALIGN='center' VALIGN='middle' BgCOLOR='#eef5e5'>
					<TD STYLE='text-align:right; font-weight:bold; font-size:small; width:25%;' >
						REPRESENTANTE LEGAL : &nbsp;
					</TD>
					<TD STYLE='text-align:left; width:75%;'  >
						<INPUT TYPE='TEXT' NAME='nomb_cliente' ID='NMB_CTE' VALUE='".$nomb_cliente."'  SIZE='30' >
					</TD>
				</TR>

				<TR ALIGN='center' VALIGN='middle' BgCOLOR='#eef5e5'>
					<TD STYLE='text-align:right; font-weight:bold; font-size:small; width:25%;' >
						ID. SOLICITUD : &nbsp;
					</TD>
					<TD STYLE='text-align:left; width:75%;'  >
						<INPUT TYPE='TEXT' NAME='id_solicitud' ID='ID_SOLI' VALUE='".$id_solicitud."'  SIZE='10' CLASS='SOLO_NUMEROS'>
					</TD>
				</TR>

				<TR ALIGN='center' VALIGN='middle' BgCOLOR='#eef5e5'>
					<TD STYLE='text-align:right; font-weight:bold; font-size:small; width:25%;' >
						PROMOTOR : &nbsp;
					</TD>
					<TD STYLE='text-align:left; width:75%;'  >
						<INPUT TYPE='TEXT' NAME='promotor_soli' ID='PROMO_SOLI' VALUE='".$promotor_soli."'  SIZE='30' >
					</TD>
				</TR>

				<TR ALIGN='center' VALIGN='middle' BgCOLOR='#eef5e5'>
					<TD STYLE='text-align:right; font-weight:bold; font-size:small; width:25%;' >
						TIPO DE CR&Eacute;DITO : &nbsp;
					</TD>
					<TD STYLE='text-align:left; width:75%;'  >
						".$combo."
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
			<BUTTON  ID='SEARCH_SOLIS_PEMPRESARIAL'   NAME='Buscar_soli' VALUE='TRUE' STYLE='font-size:x-small;' > &nbsp;&nbsp; BUSCAR SOLICITUD. </BUTTON>
			<INPUT TYPE='HIDDEN' ID='CURRENT_CREDIT'         VALUE='5'>
			<BR /><BR /><BR />
					<DIV ID='grid_seccion' > </DIV>";

	return $Filtros;


}

function get_filtros_all($EDIT_ALL,$ID_Tipocredito,$PROCESO)
{
	global $ID_SUC;
	global $db;
	global $img_path;
	global $VALIDA_PROCESO;
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


	  $Sql_solid="SELECT
						Status AS ESTAT
					FROM cat_tipo_credito
						INNER JOIN grupo_tipo_credito ON cat_tipo_credito.ID_Tipocredito = grupo_tipo_credito.ID_Tipocredito
														AND grupo_tipo_credito.ID_Sucursal ='".$ID_SUC."'
					WHERE cat_tipo_credito.ID_Tipocredito ='2' ";
	   $rs_solid=$db->Execute($Sql_solid);
	   $Credit_solid=($rs_solid->fields["ESTAT"]=='Activo')?("TRUE"):("FALSE");
	   
	  $Sql_nom="SELECT
						Status AS ESTAT
					FROM cat_tipo_credito
						INNER JOIN grupo_tipo_credito ON cat_tipo_credito.ID_Tipocredito = grupo_tipo_credito.ID_Tipocredito
														AND grupo_tipo_credito.ID_Sucursal ='".$ID_SUC."'
					WHERE cat_tipo_credito.ID_Tipocredito ='3' ";
	   $rs_nom=$db->Execute($Sql_nom);
	   $Credit_nom=($rs_nom->fields["ESTAT"]=='Activo')?("TRUE"):("FALSE");

	  $Sql_pmoral="SELECT
						Status AS ESTAT
					FROM cat_tipo_credito
						INNER JOIN grupo_tipo_credito ON cat_tipo_credito.ID_Tipocredito = grupo_tipo_credito.ID_Tipocredito
														AND grupo_tipo_credito.ID_Sucursal ='".$ID_SUC."'
					WHERE cat_tipo_credito.ID_Tipocredito ='4' ";
	   $rs_pmoral=$db->Execute($Sql_pmoral);
	   $Credit_pmoral=($rs_pmoral->fields["ESTAT"]=='Activo')?("TRUE"):("FALSE");

	  $Sql_empresarial="SELECT
						Status AS ESTAT
					FROM cat_tipo_credito
						INNER JOIN grupo_tipo_credito ON cat_tipo_credito.ID_Tipocredito = grupo_tipo_credito.ID_Tipocredito
														AND grupo_tipo_credito.ID_Sucursal ='".$ID_SUC."'
					WHERE cat_tipo_credito.ID_Tipocredito ='5' ";
	   $rs_empresarial=$db->Execute($Sql_empresarial);
	   $Credit_empresarial=($rs_empresarial->fields["ESTAT"]=='Activo')?("TRUE"):("FALSE");

	   
   $Lanzador_tipo_credito=array("CREDIT_INDIV"=>$Credit_indiv,"CREDIT_NOM"=>$Credit_nom,"CREDIT_SOLID"=>$Credit_solid,"CREDIT_PMORAL"=>$Credit_pmoral,"CREDIT_EMPRESARIAL"=>$Credit_empresarial);
  /***********************/
    $header_cmb ="<SELECT   NAME='tipo_credito' ID='TIPO_CREDIT' > ";

	if( empty($PROCESO) )
		$Arr_combo 			= get_combo_tipo_credit($OPTION_CREDIT);
	else
		$Arr_combo 			= get_combo_tipo_credit_proceso($ID_Tipocredito);
		
	$Cont_tipo_credit	= $Arr_combo["CONT"];
	$combo 				= $Arr_combo["HTML"];


    if($Cont_tipo_credit > 1)
		$combo = $header_cmb."<OPTION VALUE=''   >--- SELECCIONAR OPCI&Oacute;N ---</OPTION> \n".$combo;
	else
		$combo = $header_cmb.$combo;

	$combo.="</SELECT>\n";

	if(count($VALIDA_PROCESO) > 0)
	{
		$combo_proceso ="<SELECT   NAME='valida_proceso' ID='VALIDA_PROCESO' > ";

		foreach($VALIDA_PROCESO AS $Proceso)
		{
			$combo_proceso .= "<OPTION VALUE='PENDIENTES_".$Proceso."'  SELECTED >PENDIENTES DE ".$Proceso."</OPTION>";
			$combo_proceso .= "<OPTION VALUE='PROCESADOS_".$Proceso."'   >PROCESADOS POR ".$Proceso."</OPTION>";
		}
		
		$combo_proceso .="</SELECT>\n";

	}
	/*********************/

	//$LABEL_NMB_CTE 	  = ($Lanzador_tipo_credito['CREDIT_SOLID'] == 'TRUE' )?("NOMBRE DEL CLIENTE / GRUPO"):("NOMBRE DEL CLIENTE");
	//$LABEL_FOLIO_SOLI = ($Lanzador_tipo_credito['CREDIT_SOLID'] == 'TRUE' )?("FOLIO / ID. GRUPO"):("FOLIO");
	$TITLE=($CHECK_LIST == 'TRUE')?("CONSULTA POR SOLICITUDES AUTORIZADAS."):("CONSULTA DE SOLICITUDES.");
	
	    $Filtros ="	<H3 CLASS='ui-widget-header'>".$TITLE."</H3>
						<BR />
					<TABLE  CELLSPACING='0' STYLE='' ALIGN='CENTER' BORDER='0px' WIDTH='99%'>";

	    $combo_nmb_cte="<SELECT ID='CMB_NMB_CTE' STYLE='WIDTH:160px;text-align:right;' >";

		if( $Lanzador_tipo_credito['CREDIT_INDIV'] == 'TRUE' || $Lanzador_tipo_credito['CREDIT_NOM'] == 'TRUE' )
			 $combo_nmb_cte.="<OPTION VALUE='NMB_CTE' >NOMBRE &nbsp;</OPTION> \n";
 
		if($Lanzador_tipo_credito['CREDIT_SOLID'] == 'TRUE')
			$combo_nmb_cte.="<OPTION VALUE='NMB_GPO'   TITLE='APLICA CRÉDITO SOLIDARIO' >GRUPO SOLIDARIO &nbsp;</OPTION> \n";

		if($Lanzador_tipo_credito['CREDIT_PMORAL'] == 'TRUE' || $Lanzador_tipo_credito['CREDIT_EMPRESARIAL'] == 'TRUE')
			$combo_nmb_cte.="<OPTION VALUE='RZN_SOCIAL'TITLE='APLICA CRÉDITO EMPRESARIAL Y FACTORAJE'>RAZÓN SOCIAL / PFAE&nbsp;</OPTION> \n";


		$combo_nmb_cte.="</SELECT>";


		$Filtros.="
						<TR ALIGN='center' VALIGN='middle' BgCOLOR='#eef5e5'>
							<TD STYLE='text-align:right; font-weight:bold; font-size:small; width:25%;' >
								".$combo_nmb_cte." : &nbsp;
							</TD>
							<TD STYLE='text-align:left; width:75%;'  >
								<INPUT TYPE='TEXT'  ID='TXT_NMB_CTE'   SIZE='30' > 
							</TD>
						</TR>";

	    $combo_ident_cte="<SELECT ID='CMB_IDENT_CTE' STYLE='WIDTH:160px;text-align:right;'>";
						  
		if($Lanzador_tipo_credito['CREDIT_INDIV'] == 'TRUE' || $Lanzador_tipo_credito['CREDIT_NOM'] == 'TRUE' || $Lanzador_tipo_credito['CREDIT_PMORAL'] == 'TRUE' || $Lanzador_tipo_credito['CREDIT_EMPRESARIAL'] == 'TRUE')
			$combo_ident_cte.="<OPTION VALUE='FOLIO_SOLI' TITLE='APLICA CRÉDITO EMPRESARIAL,FACTORAJE, INDIVIDUAL Y NÓMINA'>FOLIO</OPTION> \n";


		if($Lanzador_tipo_credito['CREDIT_SOLID'] == 'TRUE')
			$combo_ident_cte.="<OPTION VALUE='ID_GPO' TITLE='APLICA CRÉDITO SOLIDARIO'>ID. GRUPO</OPTION> \n";

				
		if($Lanzador_tipo_credito['CREDIT_INDIV'] == 'TRUE' || $Lanzador_tipo_credito['CREDIT_NOM'] == 'TRUE' || $Lanzador_tipo_credito['CREDIT_PMORAL'] == 'TRUE' || $Lanzador_tipo_credito['CREDIT_EMPRESARIAL'] == 'TRUE')
			$combo_ident_cte.="<OPTION VALUE='NUM_CTE' TITLE='APLICA CRÉDITO EMPRESARIAL,FACTORAJE, INDIVIDUAL Y NÓMINA' >NUM. DE CLIENTE</OPTION> \n";

		$combo_ident_cte.="</SELECT>";


		$Filtros.="
						<TR ALIGN='center' VALIGN='middle' BgCOLOR='#eef5e5'>
							<TD STYLE='text-align:right; font-weight:bold; font-size:small; width:25%;' >
								".$combo_ident_cte." : &nbsp;
							</TD>
							<TD STYLE='text-align:left; width:75%;'  >
								<INPUT TYPE='TEXT'  ID='TXT_IDNT_CTE'   SIZE='10' CLASS='SOLO_NUMEROS' STYLE='text-align:right;'> 
							</TD>
						</TR>";
						

		$Filtros .="
				<TR ALIGN='center' VALIGN='middle' BgCOLOR='#eef5e5'>
					<TD STYLE='text-align:right; font-weight:bold; font-size:small; width:25%;' >
						PROMOTOR : &nbsp;
					</TD>
					<TD STYLE='text-align:left; width:75%;'  >
						<INPUT TYPE='TEXT' NAME='promotor' ID='PROMO' VALUE='".$promotor."'  SIZE='30' TITLE='APLICA A TODOS LOS TIPOS DE CRÉDITO' ALT='APLICA A TODOS LOS TIPOS DE CRÉDITO'>
					</TD>
				</TR>

				<TR ALIGN='center' VALIGN='middle' BgCOLOR='#eef5e5'>
					<TD STYLE='text-align:right; font-weight:bold; font-size:small; width:25%;' >
						TIPO DE CR&Eacute;DITO : &nbsp;
					</TD>
					<TD STYLE='text-align:left; width:75%;'  >
						".$combo."
					</TD>
				</TR>";
				
		if(count($VALIDA_PROCESO) > 0)
		{

		$Filtros .="
				<TR ALIGN='center' VALIGN='middle' BgCOLOR='#eef5e5'>
					<TD STYLE='text-align:right; font-weight:bold; font-size:small; width:25%;' >
						VALIDAR PROCESO : &nbsp;
					</TD>
					<TD STYLE='text-align:left; width:75%;'  >
						".$combo_proceso."
					</TD>
				</TR>";
		}

		$Filtros .="<TR ALIGN='center' VALIGN='middle' BgCOLOR='#eef5e5'>
					
					<TD STYLE='text-align:right; font-weight:bold; font-size:small; width:25%;' >
						FECHA DE CAPTURA : &nbsp;
					</TD>
					<TD STYLE='text-align:left; width:75%;'  >
						<INPUT id='FECH_INI' class='datepicker' type='TEXT' lang='' value=''    style='width: 65px;' name='Fecha_ini' TITLE='APLICA A TODOS LOS TIPOS DE CRÉDITO' ALT='APLICA A TODOS LOS TIPOS DE CRÉDITO'>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<SPAN STYLE='font-size:small; font-weight:bold;'>A LA FECHA :</SPAN>
						<INPUT id='FECH_FIN' class='datepicker' type='TEXT' lang='' value=''    style='width: 65px;' name='Fecha_fin' TITLE='APLICA A TODOS LOS TIPOS DE CRÉDITO' ALT='APLICA A TODOS LOS TIPOS DE CRÉDITO'>
					</TD>
				</TR>

			</TABLE>
			<BR />
			<BUTTON  ID='SEARCH_SOLIS'   NAME='Buscar_soli' VALUE='TRUE' STYLE='font-size:x-small;' > &nbsp;&nbsp; BUSCAR SOLICITUDES. </BUTTON>
			<INPUT TYPE='HIDDEN' ID='EDIT_ALL'         VALUE='".$EDIT_ALL."'>
			<INPUT TYPE='HIDDEN' ID='ID_credito'       VALUE='".$ID_Tipocredito."'>
			<INPUT TYPE='HIDDEN' ID='PROCESO'   	   VALUE='".$PROCESO."'>
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
/*************FIN FUNCTIONS******************/



/****************CORE PRINCIPAL**************/
if(isset($body) )
{

	$Filtros = get_filtros_all($EDIT_ALL,$ID_Tipocredito,'');

	echo $Filtros;
}

if(isset($VALIDA_PROCESO) && count($VALIDA_PROCESO > 0) && isset($ID_Tipocredito) && ($ID_Tipocredito > 0) )
{


	foreach($VALIDA_PROCESO AS $PROCESO)
	{
		if($PROCESO == 'CHECK_LIST')
			$Filtros = get_filtros_all($EDIT_ALL,$ID_Tipocredito,$PROCESO);
	}

	echo $Filtros;
}

/********************************************/

/*****************FILTROS**************************/
if( isset($CREDIT_INDIV) && !empty($CREDIT_INDIV) )
{
	$Filtros_soli=get_filtros_indiv();

	  echo $Filtros_soli;
}


if( isset($CREDIT_SOLIDARIO) && !empty($CREDIT_SOLIDARIO) )
{
	$Filtros_soli=get_filtros_solid();

	  echo $Filtros_soli;
}

if( isset($CREDIT_PMORAL) && !empty($CREDIT_PMORAL) )
{
	$Filtros_soli=get_filtros_pmoral();

	  echo $Filtros_soli;
}


if( isset($CREDIT_EMPRESARIAL) && !empty($CREDIT_EMPRESARIAL) )
{
	$Filtros_soli=get_filtros_empresarial();

	  echo $Filtros_soli;
}

/***************************FIN FILTROS********************************************/

/**************************GRIDS**********************************************/

if( isset($nomb_cliente) && isset($num_folio) && isset($num_cte) && isset($Nmb_promotor) && isset($Tipo_credito) && isset($Fecha_inicio) && isset($Fecha_fin) && isset($VALIDA_PROCESO) && !empty($VALIDA_PROCESO)  )
{
	
	global $db;
	global $ID_SUC;

			$filtro_nombre="(Concat(solicitud.Nombre,solicitud.NombreI,solicitud.Ap_paterno,solicitud.Ap_materno)";
		$Discriminante ="";

		if(!empty($nomb_cliente) )
			{
			  $nomb_cliente=str_replace(" ", "", $nomb_cliente);
			  $Discriminante.=" AND ".$filtro_nombre."   		LIKE '%".$nomb_cliente."%' )";
			 }

		if(!empty($num_folio))
		   $Discriminante.=" AND	solicitud.ID_Solicitud 		=  '".$num_folio."' ";

		if(!empty($num_cte))
		   $Discriminante.=" AND	clientes_datos.Num_cliente 	=  '".$num_cte."' ";

		if(!empty($Nmb_promotor))
		   $Discriminante.=" AND	promotores.Nombre 			LIKE '%".$Nmb_promotor."%' ";

		if(!empty($Tipo_credito))
		   $Discriminante.=" AND	solicitud.ID_Tipocredito 	=  '".$Tipo_credito."' ";
		   
		 if(!empty($Fecha_inicio) || !empty($Fecha_fin))
		 {
			if( !empty($Fecha_inicio) && !empty($Fecha_fin)  )
				 $Discriminante.=" AND	solicitud.Fecha BETWEEN '".gfecha($Fecha_inicio)."' AND '".gfecha($Fecha_fin)."' ";

			if( !empty($Fecha_inicio) && empty($Fecha_fin)  )
				 $Discriminante.=" AND	solicitud.Fecha BETWEEN '".gfecha($Fecha_inicio)."' AND '".gfecha($Fecha_inicio)."' ";

			if( empty($Fecha_inicio) && !empty($Fecha_fin)  )
				 $Discriminante.=" AND	solicitud.Fecha BETWEEN '".gfecha($Fecha_fin)."' AND '".gfecha($Fecha_fin)."' ";
				 
		 }

	  /****ROWS GRID***********************/
			 $sql_params="SELECT Valor
					  FROM constantes
					 WHERE Nombre = 'GRID_RENGLONES_POR_PAGINA_PROMOCION'";
			 $rs=$db->execute($sql_params);
			 $rows_grid=$rs->fields["Valor"];
			 $LIMIT_INI=(empty($LIMIT_INI))?('0'):($LIMIT_INI);
			 $LIMIT_FIN=$rows_grid;
		/*********QUERY**************/

					  $Discriminante .=($ID_SUC =='1')?(""):(" AND solicitud.ID_Sucursal='".$ID_SUC."' ");
					  $Discriminante .="AND solicitud.Renovacion_saldo = 'N'
										AND (solicitud.Status_solicitud = 'ALTA CREDITO' || solicitud.Status_solicitud = 'IMPRESION/DIGITALIZACION' )
										AND solicitud.ID_Tipocredito = '".$ID_credito."' ";
					  $Discriminante .=($Cmb_valida_proceso =='PENDIENTES_CHECK_LIST')?(" AND solicitud_valida_procesos.ID_Solicitud IS NULL "):(" AND solicitud_valida_procesos.ID_Solicitud IS NOT NULL ");
					  
					  $tabla="solicitud";
					  $select_nombre="(Concat(".$tabla.".Nombre,' ',".$tabla.".NombreI,' ',".$tabla.".Ap_paterno,' ',".$tabla.".Ap_materno))";

					  $Sql_cons="SELECT
										".$tabla.".ID_Solicitud 													AS IDSOLI,
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
										".$tabla.".Solicitud_expres													AS EXPRS,
										".$tabla.".ID_Tipo_regimen 													AS TIPO_SOLI,
										CONCAT(clientes_datos.Plazo,' ',cat_productosfinancieros.Vencimiento) 		AS PLAZO,
										clientes_datos.Monto														AS MONTO,
										cat_productosfinancieros.Nombre												AS NMB_PRODUCTO \n";
						
						 $Sql_form="FROM    ".$tabla."
									LEFT JOIN promotores      			ON ".$tabla.".ID_Promotor  				= promotores.Num_promo
									LEFT JOIN sucursales      			ON ".$tabla.".ID_sucursal  				= sucursales.ID_Sucursal
									LEFT JOIN clientes_datos  			ON ".$tabla.".ID_Solicitud 				= clientes_datos.ID_Solicitud
									LEFT JOIN cat_productosfinancieros	ON cat_productosfinancieros.ID_Producto = clientes_datos.ID_Producto
									LEFT JOIN solicitud_valida_procesos ON ".$tabla.".ID_Solicitud				= solicitud_valida_procesos.ID_Solicitud
																			AND ".$tabla.".ID_Tipo_regimen		= solicitud_valida_procesos.ID_Tipo_regimen
									LEFT JOIN grupo_solidario ON solicitud.ID_grupo_soli = grupo_solidario.ID_grupo_soli
																AND solicitud.ID_Tipocredito='2'
																AND solicitud.ID_grupo_soli <> 0

								WHERE
									Status_solicitud IS NOT NULL
									AND ".$tabla.".Status = 'Activa'
									".$Discriminante."
									LIMIT ". $LIMIT_INI." , ". $LIMIT_FIN." ";

						$Sql_cons.=$Sql_form;
					$rs_cons=$db->Execute($Sql_cons);

		/**********************************/
		/******DISPLAY NAVIGATION BAR******/
			 $Sql_navegation="SELECT
										COUNT(".$tabla.".ID_Solicitud)	AS CUANTOS 	\n";

			$Sql_form=str_replace("LIMIT ". $LIMIT_INI." , ". $LIMIT_FIN."","",$Sql_form);
			$Sql_navegation.=$Sql_form;

			
		 $Bar_navegation=get_pagination($Sql_navegation,$rows_grid,$CURRENT_PAGE,$Tipo_credito);
		 /********************************/

		 
		$Table_grid="<TABLE  CELLSPACING='0' STYLE='' ALIGN='CENTER' BORDER='0px' WIDTH='99%'  CLASS='tablesorter' ID='TBL_SOLI'>
				<THEAD>
				
					<TR>
						<TD  ALIGN='CENTER' COLSPAN='8'  STYLE='-moz-border-radius-topleft:  6px; -moz-border-radius-topright: 6px;  background-color : #6fa7d1;'>
							<B> <FONT SIZE='2' COLOR='WHITE'>SOLICITUDES DE CR&Eacute;DITO AUTORIZADAS</FONT></B>
						</TD>
					</TR>
					
				
					<TR ALIGN='center' VALIGN='middle'  BGCOLOR='#6fa7d1' STYLE='height:30px;'>
						   <TH STYLE='font-size:small;  text-align:center; color:white; text-decoration:underline; cursor:pointer;'  WIDTH='7%' >     			NUM. CLIENTE   </TH>
						   <TH STYLE='font-size:small;  text-align:left; color:white; text-decoration:underline; cursor:pointer;'    WIDTH='25%' >     			NOMBRE         </TH>
						   <TH STYLE='font-size:small;  text-align:left; color:white; text-decoration:underline; cursor:pointer;'    WIDTH='10%' >     			TIPO CR&Eacute;DITO   </TH>
						   <TH STYLE='font-size:small;  text-align:left; color:white; text-decoration:underline; cursor:pointer;'    WIDTH='15%' >     			MONTO          </TH>
						   <TH STYLE='font-size:small;  text-align:left; color:white; text-decoration:underline; cursor:pointer;'    WIDTH='15%' >     			PRODUCTO  </TH>
						   <TH STYLE='font-size:small;  text-align:left; color:white; text-decoration:underline; cursor:pointer;'    WIDTH='5%' >     			PLAZO   </TH>
						   <TH STYLE='font-size:small;  text-align:left; color:white; text-decoration:underline;                '    WIDTH='5%' >     						   </TH>
					 </TR>
			</THEAD>
			<TBODY>";
					$cont=1;
					While(!$rs_cons->EOF)
					{

					  /*****VALIDACIONES*********/
					  $row_color        =(($cont % 2) == 0 )?('#FDFEFF'):('#E7EEF6');

						$REDIRECT="../../lib/class/promocion/consultas/solicitud_total.php?ID_SOLI=".$rs_cons->fields["IDSOLI"]."&ID_GPO=".$rs_cons->fields["ID_GPO"]."&Tipo_credit=".$rs_cons->fields["TP_CREDIT"]."&CHECK_LIST=TRUE";
						
					  $Expres_trad		=($rs_cons->fields["EXPRS"] == 'N')?("<IMG SRC='".$img_path."navigation.png'  STYLE='height:18px; width:18px; cursor:pointer;' ONCLICK='window.location.replace(\"".$REDIRECT."\");'/>"):("<IMG SRC='".$img_path."navigation.png'  STYLE='height:18px; width:18px; cursor:pointer;' LANG='".$rs_cons->fields["IDSOLI"]."_".$rs_cons->fields["TIPO_CREDIT"]."' CLASS='ASIGNA_PROD' />");
					  
					  /**************/

					  $Table_grid .="<TR ALIGN='center' VALIGN='middle' BgCOLOR='".$row_color ."' 												ONMOUSEOVER=\"javascript:this.style.backgroundColor='#FBFAAE'; this.style.cursor='hand'; \"
						 ONMOUSEOUT =\"javascript:this.style.backgroundColor='' \"  BgCOLOR='".$row_color ."'>
											<TH STYLE='font-size:small; text-decoration:underline; text-align:center; color:gray; cursor:pointer;' 	WIDTH='10%' CLASS='SHOW_DETAIL_SOLI' ID='".$rs_cons->fields["IDSOLI"]."'>
												".$rs_cons->fields["NUM_CTE"]."
											</TH>

											<TH STYLE='font-size:small;  text-align:left;   ' 				WIDTH='25%' >    ".strtoupper($rs_cons->fields["CLIENTE"])."		</TH>
											<TH STYLE='font-size:small;  text-align:left; color:navy;' 	    WIDTH='20%' >    ".strtoupper($rs_cons->fields["TIPO_CREDIT_II"])."	</TH>
											<TH STYLE='font-size:small;  text-align:left; ' 			    WIDTH='10%' >    $".number_format($rs_cons->fields["MONTO"],2)."	 </TH>
											<TH STYLE='font-size:small;  text-align:left; color:gray;'		WIDTH='15%' >    ".$rs_cons->fields["NMB_PRODUCTO"]."		    </TH>
											<TH STYLE='font-size:small;  text-align:left; ' 				WIDTH='15%' >     ".$rs_cons->fields["PLAZO"]."		    		</TH>
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

if( isset($nomb_cliente) && isset($num_folio) && isset($num_cte) && isset($Nmb_promotor) && isset($Tipo_credito) && isset($Fecha_inicio) && isset($Fecha_fin) && empty($VALIDA_PROCESO) ){

		//DISCRIMINANTES
		$filtro_nombre="(Concat(solicitud.Nombre,solicitud.NombreI,solicitud.Ap_paterno,solicitud.Ap_materno)";
		$Discriminante ="";

		if(!empty($nomb_cliente) )
			{
			  $nomb_cliente=str_replace(" ", "", $nomb_cliente);
			  $Discriminante.=" AND ".$filtro_nombre."   		LIKE '%".$nomb_cliente."%' )";
			 }

		if(!empty($num_folio))
		   $Discriminante.=" AND	solicitud.ID_Solicitud 		=  '".$num_folio."' ";

		if(!empty($num_cte))
		   $Discriminante.=" AND	clientes_datos.Num_cliente 	=  '".$num_cte."' ";

		if(!empty($Nmb_promotor))
		   $Discriminante.=" AND	promotores.Nombre 			LIKE '%".$Nmb_promotor."%' ";

		if(!empty($Tipo_credito))
		   $Discriminante.=" AND	solicitud.ID_Tipocredito 	=  '".$Tipo_credito."' ";
		   
		 if(!empty($Fecha_inicio) || !empty($Fecha_fin))
		 {
			if( !empty($Fecha_inicio) && !empty($Fecha_fin)  )
				 $Discriminante.=" AND	solicitud.Fecha BETWEEN '".gfecha($Fecha_inicio)."' AND '".gfecha($Fecha_fin)."' ";

			if( !empty($Fecha_inicio) && empty($Fecha_fin)  )
				 $Discriminante.=" AND	solicitud.Fecha BETWEEN '".gfecha($Fecha_inicio)."' AND '".gfecha($Fecha_inicio)."' ";

			if( empty($Fecha_inicio) && !empty($Fecha_fin)  )
				 $Discriminante.=" AND	solicitud.Fecha BETWEEN '".gfecha($Fecha_fin)."' AND '".gfecha($Fecha_fin)."' ";
				 
		 }

		if(!empty($Tipo_solicitud))
			$Discriminante.=" AND	solicitud.Solicitud_expres 		=  '".$Tipo_solicitud."' ";
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
					  $select_nombre="(Concat(IFNULL(".$tabla.".Nombre,' '),' ',IFNULL(".$tabla.".NombreI,' '),' ',IFNULL(".$tabla.".Ap_paterno,' '),' ',IFNULL(".$tabla.".Ap_materno,' ')))";

					  $Sql_cons="SELECT
										".$tabla.".ID_Solicitud 													AS IDSOLI,
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
										".$tabla.".Solicitud_expres													AS EXPRS,
										".$tabla.".ID_Tipo_regimen 													AS TIPO_SOLI	\n";
						
						 $Sql_form="FROM    ".$tabla."
									LEFT JOIN promotores      ON ".$tabla.".ID_Promotor  = promotores.Num_promo
									LEFT JOIN sucursales      ON ".$tabla.".ID_sucursal  = sucursales.ID_Sucursal
									LEFT JOIN clientes_datos  ON ".$tabla.".ID_Solicitud = clientes_datos.ID_Solicitud
									LEFT JOIN grupo_solidario ON solicitud.ID_grupo_soli = grupo_solidario.ID_grupo_soli
																AND solicitud.ID_Tipocredito='2'
																AND solicitud.ID_grupo_soli <> 0

								WHERE
									Status_solicitud IS NOT NULL
									".$Discriminante."
									LIMIT ". $LIMIT_INI." , ". $LIMIT_FIN." ";

						$Sql_cons.=$Sql_form;
					$rs_cons=$db->Execute($Sql_cons);

		/**********************************/
		/******DISPLAY NAVIGATION BAR******/
			 $Sql_navegation="SELECT
										COUNT(".$tabla.".ID_Solicitud)	AS CUANTOS 	\n";

			$Sql_form=str_replace("LIMIT ". $LIMIT_INI." , ". $LIMIT_FIN."","",$Sql_form);
			$Sql_navegation.=$Sql_form;

			
		 $Bar_navegation=get_pagination($Sql_navegation,$rows_grid,$CURRENT_PAGE,$Tipo_credito);
		 /********************************/

		 
		$Table_grid="<TABLE  CELLSPACING='0' STYLE='' ALIGN='CENTER' BORDER='0px' WIDTH='99%'  CLASS='tablesorter' ID='TBL_SOLI'>
				<THEAD>
				
					<TR>
						<TD  ALIGN='CENTER' COLSPAN='8'  STYLE='-moz-border-radius-topleft:  6px; -moz-border-radius-topright: 6px;  background-color : #6fa7d1;'>
							<B> <FONT SIZE='2' COLOR='WHITE'>SOLICITUDES DE CR&Eacute;DITO</FONT></B>
						</TD>
					</TR>
					
				
					<TR ALIGN='center' VALIGN='middle'  BGCOLOR='#6fa7d1' STYLE='height:30px;'>
						   <TH STYLE='font-size:small;  text-align:center; color:white; text-decoration:underline; cursor:pointer;'  WIDTH='7%' >     			FOLIO          </TH>
						   <TH STYLE='font-size:small;  text-align:left; color:white; text-decoration:underline; cursor:pointer;'    WIDTH='25%' >     			NOMBRE         </TH>
						   <TH STYLE='font-size:small;  text-align:left; color:white; text-decoration:underline; cursor:pointer;'    WIDTH='10%' >     			TIPO CR&Eacute;DITO   </TH>
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

						if($EDIT_ALL == 'FALSE')
						{
							
							if($rs_cons->fields["TP_CREDIT"] == 1)
								$REDIRECT = "solicitudes_individual/edita_solicitud_indiv.php?ID_Tiposolicitud=".$rs_cons->fields["TIPO_SOLI"]."&Param2=".$rs_cons->fields["IDSOLI"]."&EDIT_ALL=".$EDIT_ALL." ";
							//if($rs_cons->fields["TP_CREDIT"] == 2)
								//$REDIRECT = "solicitudes_solidario/edita_solicitud_solidario.php?ID_Tiposolicitud=".$rs_cons->fields["TIPO_SOLI"]."&Param2=".$rs_cons->fields["IDSOLI"]."&EDIT_ALL=".$EDIT_ALL." ";

							$ARR_TIPOS		 	=	get_tipo_credit_soli($rs_cons->fields["IDSOLI"],$rs_cons->fields["ID_GPO"],$rs_cons->fields["TP_CREDIT"]);
							$ID_EMPRESA=$ARR_TIPOS["ID_EMPRESA"];
							
							if($rs_cons->fields["TP_CREDIT"] == 3)
								$REDIRECT = "solicitudes_nomina/edita_solicitud_nomina.php?ID_Tiposolicitud=".$rs_cons->fields["TIPO_SOLI"]."&Param2=".$rs_cons->fields["IDSOLI"]."&EDIT_ALL=".$EDIT_ALL."&ID_EMPRESA=".$ID_EMPRESA." ";
							//if($rs_cons->fields["TP_CREDIT"] == 4)
								//$REDIRECT = "solicitudes_pmoral/edita_solicitud_pmoral.php?ID_Tiposolicitud=".$rs_cons->fields["TIPO_SOLI"]."&Param2=".$rs_cons->fields["IDSOLI"]."&EDIT_ALL=".$EDIT_ALL." ";
							//if($rs_cons->fields["TP_CREDIT"] == 5)
								//$REDIRECT = "solicitudes_empresarial/edita_solicitud_empresarial.php?ID_Tiposolicitud=".$rs_cons->fields["TIPO_SOLI"]."&Param2=".$rs_cons->fields["IDSOLI"]."&EDIT_ALL=".$EDIT_ALL." ";
						}
						else
						$REDIRECT="../../lib/class/promocion/consultas/solicitud_total.php?ID_SOLI=".$rs_cons->fields["IDSOLI"]."&ID_GPO=".$rs_cons->fields["ID_GPO"]."&Tipo_credit=".$rs_cons->fields["TP_CREDIT"]."";
						
					  $Expres_trad		=($rs_cons->fields["EXPRS"] == 'N')?("<IMG SRC='".$img_path."navigation.png'  STYLE='height:18px; width:18px; cursor:pointer;' ONCLICK='window.location.replace(\"".$REDIRECT."\");'/>"):("<IMG SRC='".$img_path."navigation.png'  STYLE='height:18px; width:18px; cursor:pointer;' LANG='".$rs_cons->fields["IDSOLI"]."_".$rs_cons->fields["TIPO_CREDIT"]."' CLASS='ASIGNA_PROD' />");
					  
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


if( isset($razon_social) && isset($id_solicitud) && isset($num_cte) && isset($promotor_soli) && isset($Fecha_inicio) && isset($Fecha_fin)  && isset($Tipo_credit) && empty($VALIDA_PROCESO) )
{

		//DISCRIMINANTES
		$Discriminante ="";


		if(!empty($id_solicitud))
		   $Discriminante.=" AND	solicitud_pmoral.ID_Solicitud 		=  '".$id_solicitud."' ";

		if(!empty($num_cte))
		   $Discriminante.=" AND	clientes_datos_pmoral.Num_cliente 	=  '".$num_cte."' ";
		   
		if(!empty($promotor_soli))
		   $Discriminante.=" AND	promotores.Nombre 			        =  '".$promotor_soli."' ";

		if(!empty($Tipo_credit))
		   $Discriminante.=" AND solicitud_pmoral.ID_Tipocredito = '".$Tipo_credit."' ";


		if(!empty($razon_social))
		{
				   $Discriminante.=" AND	(solicitud_pmoral.Razon_social 	LIKE  '%".$razon_social."%' ";
				   
				$filtro_nombre="(Concat(solicitud_pmoral.Nombre_pfae,solicitud_pmoral.NombreI_pfae,solicitud_pmoral.Ap_paterno_pfae,solicitud_pmoral.Ap_materno_pfae))";	

				$razon_social=str_replace(" ", "", $razon_social);
				$Discriminante.=" || ".$filtro_nombre."   		LIKE '%".$razon_social."%' ) ";
	    }



		 if(!empty($Fecha_inicio) || !empty($Fecha_fin))
		 {
			if( !empty($Fecha_inicio) && !empty($Fecha_fin)  )
				 $Discriminante.=" AND	solicitud_pmoral.Fecha BETWEEN '".gfecha($Fecha_inicio)."' AND '".gfecha($Fecha_fin)."' ";

			if( !empty($Fecha_inicio) && empty($Fecha_fin)  )
				 $Discriminante.=" AND	solicitud_pmoral.Fecha BETWEEN '".gfecha($Fecha_inicio)."' AND '".gfecha($Fecha_inicio)."' ";

			if( empty($Fecha_inicio) && !empty($Fecha_fin)  )
				 $Discriminante.=" AND	solicitud_pmoral.Fecha BETWEEN '".gfecha($Fecha_fin)."' AND '".gfecha($Fecha_fin)."' ";
				 
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

					  $Discriminante.=($ID_SUC =='1')?(""):(" AND solicitud_pmoral.ID_Sucursal='".$ID_SUC."' ");
					  $tabla="solicitud_pmoral";
					  $Nombre_sel="(Concat(solicitud_pmoral.Nombre_pfae,' ',solicitud_pmoral.NombreI_pfae,' ',solicitud_pmoral.Ap_paterno_pfae,' ',solicitud_pmoral.Ap_materno_pfae))";
					  
					  $Sql_cons="SELECT
										".$tabla.".ID_Solicitud 																				AS IDSOLI,
										".$tabla.".ID_Tipo_regimen																				AS TIPO_SOLI,
										IF(cat_tipo_credito_regimen.ID_Regimen ='PM', solicitud_pmoral.Razon_social,".$Nombre_sel.") 			AS CLIENTE,
										sucursales.Nombre  																						AS SUC,
										IF(promotores.Nombre IS NOT NULL, promotores.Nombre,'SIN PROMOTOR')  									AS PROMO,
										".$tabla.".Status_solicitud   																			AS STATSOLI,
										solicitud_pmoral.ID_Tipocredito         																AS TP_CREDIT,
										solicitud_pmoral.Fecha_sistema          																AS FECH_CAPT,
										solicitud_pmoral.Renovacion_credit      																AS RENOV,
										IF(clientes_datos_pmoral.ID_Cliente != 0,clientes_datos_pmoral.ID_Cliente,'S/N')						AS NUM_CTE,
										".$tabla.".ID_Tipocredito 																				AS TIPO_CREDIT,
										IF( ".$tabla.".ID_Tipocredito = 4,'CRÉDITO FACTORAJE','CRÉDITO EMPRESARIAL' ) 							AS TIPO_CREDIT_II  \n";
						
						 $Sql_form="FROM    ".$tabla."
									LEFT JOIN promotores      		 		ON ".$tabla.".ID_Promotor  		= promotores.Num_promo
									LEFT JOIN sucursales      		 		ON ".$tabla.".ID_sucursal  		= sucursales.ID_Sucursal
									LEFT JOIN clientes_datos_pmoral  		ON ".$tabla.".ID_Solicitud 		= clientes_datos_pmoral.ID_Solicitud
									LEFT JOIN cat_tipo_credito_regimen		ON ".$tabla.".ID_Tipo_regimen	= cat_tipo_credito_regimen.ID_Tipo_regimen
								WHERE
									Status_solicitud IS NOT NULL
									".$Discriminante."
									LIMIT ". $LIMIT_INI." , ". $LIMIT_FIN." ";

						$Sql_cons.=$Sql_form;
					$rs_cons=$db->Execute($Sql_cons);

		/**********************************/
		/******DISPLAY NAVIGATION BAR******/
			 $Sql_navegation="SELECT
										COUNT(".$tabla.".ID_Solicitud)	AS CUANTOS 	\n";

			$Sql_form=str_replace("LIMIT ". $LIMIT_INI." , ". $LIMIT_FIN."","",$Sql_form);
			$Sql_navegation.=$Sql_form;


		 $Bar_navegation=get_pagination($Sql_navegation,$rows_grid,$CURRENT_PAGE,$Tipo_credit);
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
						   <TH STYLE='font-size:small;  text-align:left; color:white; text-decoration:underline; cursor:pointer;'    WIDTH='25%' >     			RAZÓN SOCIAL   </TH>
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



					if($EDIT_ALL == 'FALSE')
						{
							if($rs_cons->fields["TP_CREDIT"] == 4)
								$REDIRECT = "solicitudes_pmoral/edita_solicitud_pmoral.php?ID_Tiposolicitud=".$rs_cons->fields["TIPO_SOLI"]."&Param2=".$rs_cons->fields["IDSOLI"]."&EDIT_ALL=".$EDIT_ALL." ";
							if($rs_cons->fields["TP_CREDIT"] == 5)
								$REDIRECT = "solicitudes_empresarial/edita_solicitud_empresarial.php?ID_Tiposolicitud=".$rs_cons->fields["TIPO_SOLI"]."&Param2=".$rs_cons->fields["IDSOLI"]."&EDIT_ALL=".$EDIT_ALL." ";
						}
						else
							$REDIRECT="../../lib/class/promocion/consultas/solicitud_total.php?ID_SOLI=".$rs_cons->fields["IDSOLI"]."&ID_Tiposolicitud=".$rs_cons->fields["TIPO_SOLI"]."&Tipo_credit=".$rs_cons->fields["TIPO_CREDIT"]."";

					  $IMG_SIG		="<IMG SRC='".$img_path."navigation.png'  STYLE='height:18px; width:18px; cursor:pointer;' ONCLICK='window.location.replace(\"".$REDIRECT."\");'/>";
					  
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
											<TH STYLE='font-size:small;' WIDTH='5%'>										 ".$IMG_SIG."										</TH>
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


if( isset($Nmb_gpo) &&  isset($Id_gpo) && isset($Id_gpo) && isset($Promotor) && isset($Fecha_inicio) && isset($Fecha_fin) && empty($VALIDA_PROCESO) )
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
							<B> <FONT SIZE='2' COLOR='WHITE'>SOLICITUDES DE CR&Eacute;DITO</FONT></B>
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
											<TH STYLE='font-size:small;' WIDTH='5%' ><IMG SRC='".$img_path."navigation.png'  STYLE='height:18px; width:18px; cursor:pointer;' ONCLICK='window.location.replace(\"../../lib/class/promocion/consultas/solicitud_total.php?ID_SOLI=0&ID_GPO=".$rs_cons->fields["ID_GPO"]."&Tipo_credit=2&EDIT_ALL=".$EDIT_ALL."\");'/></TH>
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

	$Expres = ($rs_cons->fields["EXPRS"] == 'Y' )?(" - EXPR&Eacute;S"):("");
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
															<TH STYLE='text-align:left;   width:5%;' >Direcci³n</TH>
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
