<?php

/****************************************/
/*Fecha: 19/Agosto/2010
/*Autor: Tonathiu Cárdenas
/*Descripción: Digitalizar documentación para una solicitud 
/*Dependencias: lanzador_proceso.php
/****************************************/

//Librerías
require($DOCUMENT_ROOT."/rutas.php");
require($class_path."promocion/lib_informacion_basica.php");   //INFO SUCURSAL Y USUARIO
require($class_path."promocion/lib_campos_captura.php");       //FORMA LOS COMBOS
require("../sucursal/promocion/js/jquery_links.php");   	   //LIBRERÍAS DE JQUERY

//Inicio conexión
$db = ADONewConnection(SERVIDOR); 
$db->Connect(IP,USER,PASSWORD,NUCLEO);
//Fin Conexión

//JAVASCRIPT Y AJAX
//$HOY=date("Y");
//JQUERY MESSAGE
echo "<SCRIPT TYPE='TEXT/JAVASCRIPT'  src='../sucursal/promocion/js/jquery.tablesorter.js'></SCRIPT>";
?>

<!--ÁREA CSS-->
<LINK REL="stylesheet" HREF="../sucursal/promocion/css/solicitud.css"   TYPE="text/css" MEDIA="print, projection, screen">
<link REL="stylesheet" HREF="<?=$shared_scripts?>/jquery_ui/development-bundle/themes/cupertino/jquery.ui.all.css">
<link REL="stylesheet" HREF="<?=$shared_scripts?>/jquery_ui/development-bundle/demos/demos.css">



<!--JQUERY-->
<SCRIPT>

//FUNCIONES JQUERY
$('live',function() {

                //PORTLET
				$( ".portlet" )
					.addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
					.find( ".portlet-header" )
						.addClass( "ui-widget-header ui-corner-all" )
						.prepend( "<span class='ui-icon ui-icon-minusthick'></span>")
						.end()
					.find( ".portlet-content" );

				 $( ".portlet-header .ui-icon" ).click(function()
				  {
					$( this ).toggleClass( "ui-icon-minusthick" ).toggleClass( "ui-icon-plusthick" );
					$( this ).parents( ".portlet:first" ).find( ".portlet-content" ).toggle();
				 });

				//buttons
			  	$("#SAVE_FILE,.BTN_FILE").button({
						   icons: {
									primary: "ui-icon-disk"
									
								  }
								  
				  });

 
			$("#TBL_DOCS").tablesorter({
				headers: { 
					// assign the secound column (we start counting zero) 
					0: { 
						// disable it by setting the property sorter to false 
						sorter: false 
					 },
					// assign the secound column (we start counting zero) 
					2: { 
						// disable it by setting the property sorter to false 
						sorter: false 
					 },
					// assign the secound column (we start counting zero) 
					3: { 
						// disable it by setting the property sorter to false 
						sorter: false 
					 },
					// assign the secound column (we start counting zero) 
					4: { 
						// disable it by setting the property sorter to false 
						sorter: false 
					 }
				} 
				});


				//FECHA CAMPO 
				$( ".datepicker" ).datepicker
				({
					showOn: "button",
					buttonImage: "<?=$img_path?>calendar-blue.png",
					buttonImageOnly		: true,
					changeMonth			: true,
					changeYear 			: true,
					yearRange  			: "1902:<?=$HOY?>",
					monthNamesShort		: ['Ene.','Feb.','Mar.','Abr.','Mayo','Jun.','Jul.','Ago.','Sept.','Oct.','Nov.','Dic.'],
					dayNamesMin			: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
					showAnim			: 'slide',
					dateFormat			: 'dd/mm/yy'					
				});
				

                 //MENÚ ALERTAS
				  $(".SHOW_ALERT").click(function ()
				{

					  $("#DIV_ALERT").slideToggle("fast");

					  if($("#Arrow_alert").attr('title')=='DESPLEGAR LISTA DE ALERTAS...')
					  {
                         $("#Arrow_alert").attr("src","<?=$img_path?>directional_up.png");
						 $("#Arrow_alert").attr("title","OCULTAR LISTA DE ALERTAS...");
					  }
					   else
					  {
						  $("#Arrow_alert").attr("src","<?=$img_path?>directional_down.png");
 						  $("#Arrow_alert").attr("title","DESPLEGAR LISTA DE ALERTAS...");
						
					  }				  
				}); 
});



function pop_vista_doc(var1,var2)
{
	xpos=(screen.width/2)-(800/2);
	ypos=(screen.height/2)-(450/2);


	win =  window.open('','myWin_documents','menubar=0,resizable=1,location=0,directories=0,scrollbars=1,left='+xpos+',top='+
	ypos+',width=800,height=450');
	document.view_documents.target                     ='myWin_documents';
    document.view_documents.ID_Documentos.value        =var1;
    document.view_documents.action                     =var2;
	document.view_documents.submit();
}



</SCRIPT>

<?
/*****************FUNCTIONS**************************/
 function check_file_alter($T_credit,$Id_doc,$Ext_file,$Is_upload,$File_hash)
 {
  
  global $DOCUMENT_ROOT, $sys_path;

	$docs_upoload  =($T_credit=='pfisica')?("upload_docs/"):("upload_docs_gpo/");
	$docs_upoload  =($T_credit=='pfisica_actemp')?("upload_docs_nom/"):($docs_upoload);
	$docs_upoload  =($T_credit=='pmoral')?("upload_docs_pmoral/"):($docs_upoload);
	$docs_upoload  =($T_credit=='pempresarial')?("upload_docs_empresarial/"):($docs_upoload);

  //$tbl_docs      =($T_credit=='pfisica')?('solicitud_documentos'):('');
  $tbl_docs              ="solicitud_documentos";

  $Tipo_credito          =($T_credit=='pfisica')?('1'):('2');
  $Tipo_credito          =($T_credit=='pfisica_actemp')?('3'):($Tipo_credito);
  $Tipo_credito          =($T_credit=='pmoral')?('4'):($Tipo_credito);
  $Tipo_credito          =($T_credit=='pempresarial')?('5'):($Tipo_credito);

	$Ruta_file = $docs_upoload . (md5('doc'.$Id_doc)).".".$Ext_file."";

	$status_file='TRUE';
   if((!file_exists($Ruta_file)) and ($Is_upload =='Y') )
      {
        $msg='¡ EL DOCUMENTO FUÉ ELIMINADO MANUALMENTE !';
        $status_file='FALSE';
      }
      else
      {
	 //VERIFICAMOS QUE EL DOCUMENTO NO ESTE ALTERADO
	$f1= fopen($Ruta_file,"rb");
	//LEEMOS EL FICHERO COMPLETO LIMITANDO LA LECTURA AL TAMAÑO DE FICHERO
	$Doc_hash = fread($f1, filesize($Ruta_file));
	//ANTEPONEMOS SLASH A LAS COMILLAS QUE PUDIERA CONTENER EL FICHERO PARA EVITAR QUE SEAN INTERPRETADAS COMO FINAL DE CADENA
	$Doc_hash=addslashes($Doc_hash);
	$Doc_hash=md5($Doc_hash);

	if($File_hash <> $Doc_hash)
	 {
	  $msg='¡ EL CONTENIDO DEL DOCUMENTO FUÉ ALTERADO MANUALMENTE !';
	  $status_file='FALSE';
	 }
       }
 
  $status_arr=array("STATUS"=>$status_file,"MSG"=>$msg);
  return $status_arr;
 }

 function get_documentos_rel( $ID_DOC_CHILD)
 {
   global $db;
   
   	  $sql_cons = "SELECT 
					ID_Documento_Tipo AS ID,
					Descripcion		  AS DESCP
				   FROM
						cat_documentos_tipo
					WHERE ID_Documento ='".$ID_DOC_CHILD."'
				  ";
	  $rs_cons=$db->Execute($sql_cons);

	$combo.="<SELECT ID='TIPO_DOC'  NAME='TIPO_DOC[]'   STYLE='width:250px;'> \n";
	  while(! $rs_cons->EOF )
	   {
	     $sel = ($rs_cons->fields["ID"] == $option_select )?("SELECTED"):("");
	     $combo.= "<OPTION VALUE='".$rs_cons->fields["ID"]."' ".$sel." >".$rs_cons->fields["DESCP"]."</OPTION> \n";
	    $rs_cons->MoveNext();
	   }//Fin while

	$combo.="</SELECT>\n";

  return $combo;
 }

function check_nacionalidad($ID_SOLICITUD,$ID_TIPO_CREDITO)
{
	global $db;

	$CMP_SOLI	= "Nacionalidad_soli";//($ID_TIPO_CREDITO < 4)?("Nacionalidad"):("Nacionalidad_soli");
	$TBL_SOLI	= ($ID_TIPO_CREDITO < 4)?("solicitud"):("solicitud_pmoral");

   	  $sql_cons = "SELECT 
						".$CMP_SOLI."	  AS NACLD
				   FROM
						".$TBL_SOLI."
					WHERE ID_Solicitud ='".$ID_SOLICITUD."'
				  ";
	  $rs_cons=$db->Execute($sql_cons);

	  $VALIDA_REQ=($rs_cons->fields["NACLD"] != 'MEXICANA' )?("TRUE"):("FALSE");

	  return $VALIDA_REQ;

}

function get_count_documentos($Param2,$Tipo_credito)
{
	global $db;
	$EXTRANJ_DOC	=  check_nacionalidad($Param2,$Tipo_credito);
	
	$SQL_CONS=" SELECT
						cat_documentos.ID_Documento					AS ID,
						cat_documentos.Obligatorio_plvd				AS OBLG_PLD
	FROM  cat_documentos	 
	LEFT JOIN documento_credito ON documento_credito.ID_Documentos = cat_documentos.ID_Documento
								AND documento_credito.ID_Tipocredito = '".$Tipo_credito."'
	LEFT JOIN cat_documentos_tipo ON cat_documentos.ID_Documento = cat_documentos_tipo.ID_Documento
	WHERE documento_credito.ID_Documentos IS NOT NULL
		GROUP BY  ID";
   $rs_cons=$db->Execute($SQL_CONS);

	$CONT_DOCS=0;
	
	while(! $rs_cons->EOF )
	  {

      
      $CLASS_REQ = ($rs_cons->fields["ID"] > 0)?("REQ_FILE"):("");
      $CLASS_REQ = ($rs_cons->fields["ID"] < 0 && $rs_cons->fields["OBLG_PLD"] == 'Y')?("REQ_FILE"):($CLASS_REQ);
      $CLASS_REQ = ( ($rs_cons->fields["ID"] ==  '-7' || $rs_cons->fields["ID"] ==  '-8' ) && $EXTRANJ_DOC == 'TRUE')?("REQ_FILE"):($CLASS_REQ);

            if($CLASS_REQ == 'REQ_FILE' && ($rs_cons->fields["ID"] != -6) && ($rs_cons->fields["ID"] != -2))
					$CONT_DOCS ++;

	   $rs_cons->MoveNext();
	  }


	return $CONT_DOCS;
}

function get_constantes_documentos()
{
	global $db;

    $SQL_CONS="SELECT 
					   valor     AS VAL
				FROM
					constantes
				WHERE Nombre = 'DIGITALIZACION_DOCUMENTOS' ";
    $rs_cons=$db->Execute($SQL_CONS);
	$Docs_digtlz_oblig=strtoupper($rs_cons->fields["VAL"]);

    $SQL_CONS="SELECT 
					   valor     AS VAL
				FROM
					constantes
				WHERE Nombre = 'DOCUMENTOS_ALL_FECHA_FOLIO' ";
    $rs_cons=$db->Execute($SQL_CONS);
    $Docs_all_fecha_folio=strtoupper($rs_cons->fields["VAL"]);

	$RESULT = array("OBLIG_DOCS"=>$Docs_digtlz_oblig,"FECHA_FOLIO_DOCS"=>$Docs_all_fecha_folio); 

	return $RESULT;
}
?>
<!--ÁREA CSS-->




<!--JAVASCRIPT-->

<!--JQUERY-->


<!--AJAX-->
<?

/************DATOS SQL******************/
 //$arr_sucursal 			=sucursal_datos($ID_SUC,$db);
 //$fecha_captura			=date('Y/m/d');
 //$fecha_captura			=traducefecha($fecha_captura,'FECHA_COMPLETA');
 //$capturista   			=usuario_nombre($ID_USR,$db);
 //$nomb_solicitante      =datos_solicitante($Param2,'solicitud','Nombre',$T_credit,$db);
 
 
 //$tbl_docs              =($T_credit=='pfisica' || $T_credit=='pfisica_actemp')?('solicitud_documentos'):('');
  $tbl_docs              ="solicitud_documentos";

  $Tipo_credito          =($T_credit=='pfisica' )?('1'):('2');
  $Tipo_credito          =($T_credit=='pfisica_actemp')?('3'):($Tipo_credito);
  $Tipo_credito          =($T_credit=='pmoral')?('4'):($Tipo_credito);
  $Tipo_credito          =($T_credit=='pempresarial')?('5'):($Tipo_credito);
 
  $Tipo_documento        =($T_credit=='pfisica' || $T_credit=='pfisica_actemp' || $T_credit=='pmoral' || $T_credit=='pempresarial')?('Individual'):('Solidario');
  $Tipo_documento        =($Nomina=='TRUE')?('Nómina'):($Tipo_documento); 



	
 /*
 $sql_docs="SELECT DISTINCT
		 cat_documentos_solicitudes.ID_Documentos 	    AS ID,
		 cat_documentos_solicitudes.Documento     	    AS NMB,
		 documento_credito.Seleccionado          	    AS SEL,
		 cat_documentos_solicitudes.ID_Tipocredito          AS TX
		 
	      FROM
		 cat_documentos_solicitudes

	      LEFT JOIN documento_credito ON cat_documentos_solicitudes.ID_Tipocredito    = documento_credito.ID_Tipocredito
	       AND documento_credito.ID_Documentos = cat_documentos_solicitudes.ID_Documentos
	      WHERE cat_documentos_solicitudes.Tipo_documento =   '".$Tipo_documento."' ";
	*/
	$sql_docs=" SELECT
			cat_documentos.ID_Documento					AS ID,
			cat_documentos.Descripcion 					AS NMB,
			cat_documentos_tipo.ID_Documento_Tipo		AS ID_TIPO,
			documento_credito.ID_Documentos 			AS SELECCIONADO,
			documento_credito.ID_Tipocredito			AS TX,
			cat_documentos.Obligatorio_plvd				AS OBLG_PLD,
			documento_credito.Seleccionado				AS OBLIGATORIO
	FROM  cat_documentos	 
	LEFT JOIN documento_credito ON documento_credito.ID_Documentos = cat_documentos.ID_Documento
								AND documento_credito.ID_Tipocredito = '".$Tipo_credito."'
	LEFT JOIN cat_documentos_tipo ON cat_documentos.ID_Documento = cat_documentos_tipo.ID_Documento
	WHERE documento_credito.ID_Documentos IS NOT NULL
	 GROUP BY  ID
	 ORDER BY ID";

 $rs_docs=$db->Execute($sql_docs);
/*****************************************/

//HTML

				$arr_sucursal 			=	sucursal_datos($ID_SUC,$db);
				$capturista   			=	usuario_nombre($ID_USR,$db);
				$fecha_captura			=	traducefecha(date("Y/m/d"),'FECHA_COMPLETA');
				$Nombre_cte				=	cliente_nombre($Param2,$db);
				$DOCS_REQUERIDOS		=	get_count_documentos($Param2,$Tipo_credito);

$Header ="
		<DIV CLASS='demo'  ALIGN='center' STYLE='margin-top:1%;'>
					<DIV CLASS='portlet'>
							<H3 CLASS='ui-widget-header ui-corner-all' STYLE='background:#e5f0f8;  color:black; font-size:medium;  margin-top:0px;'>DIGITALIZACIÓN DE DOCUMENTOS     </H3>
							<DIV CLASS='portlet-content'>
								<TABLE  CELLPADDING='2' CELLSPACING='1' BORDER='0px' WIDTH='90%'>
									<TR>
										<TD  WIDTH='33%' ALIGN='CENTER'>
										<DIV  CLASS='ui-widget-content ui-corner-all'  STYLE='height:85px;'>
										
											<H3 CLASS='ui-widget-header ui-corner-all' STYLE='background:#DDDDDD;  color:black; margin-top:0px;'>".$arr_sucursal["Sucursal"]."</H3>
											
											<SPAN  STYLE='vertical-align:middle;' > <I>".$arr_sucursal["Direccion"]."</I></SPAN>
										</DIV>
										
										</TD>

										<TD  WIDTH='33%' ALIGN='CENTER'>
											<DIV  CLASS='ui-widget-content ui-corner-all'  STYLE='height:85px;'> 
												<H3 CLASS='ui-widget-header ui-corner-all' STYLE='background:#DDDDDD;  color:black; margin-top:0px;'>FECHA</H3>
												
												<SPAN  STYLE='vertical-align:middle;' ><I>".strtoupper($fecha_captura)."</I></SPAN>
											</DIV>											  
										</TD>

										<TD  WIDTH='33%' ALIGN='CENTER'>
											<DIV  CLASS='ui-widget-content ui-corner-all'  STYLE='height:85px;'> 
												<H3 CLASS='ui-widget-header ui-corner-all' STYLE='background:#DDDDDD;  color:black; margin-top:0px;'>CAPTURISTA</H3>
												<SPAN  STYLE='vertical-align:middle;' ><I>".strtoupper($capturista)."</I></SPAN>
											</DIV>											  
										</TD>

									</TR>
								</TABLE>";


$Header.="
									  <DIV  ALIGN='LEFT'  >
											<TABLE WIDTH='90%' BORDER='0px' ALIGN='CENTER'>
												<TR STYLE='cursor:pointer;' >
													<TH ALIGN='CENTER' CLASS='SHOW_CMP_REQ'  WIDTH='33%'>
														<DIV ID='DIV_PRTLT_REQ' CLASS='ui-widget-content ui-corner-all' STYLE='height:65px;'> 
															 <H3 CLASS='ui-widget-header ui-corner-all' STYLE='margin-top:0px; margin-bottom:3%;'>DOCUMENTOS REQUERIDOS</H3> 
															<SPAN ID='DIV_PRTLT_REQ_CONTENT'  STYLE='color:black; font-weight:bold; font-size:120%; '>
																	
																	[<LABEL ID='CONT_CMP_REQ' LANG='' > ".$DOCS_REQUERIDOS." </LABEL>]
															 </SPAN>
													     </DIV>  


													</TH>
													<TH STYLE='text-align:center; width:33%;' ALIGN='CENTER'  WIDTH='33%'>
														<DIV ID='DIV_SAVE_SOLI' CLASS='ui-widget-content ui-corner-all' STYLE='height:65px;'> 
															 <H3 CLASS='ui-widget-header ui-corner-all' STYLE='margin-top:0px; margin-bottom:3%;'>ANEXAR DOCUMENTACIÓN</H3> 
													         <SPAN ID='DIV_SAVE_SOLI_CONTENT'>
													             <BUTTON  ID='SAVE_FILE'   NAME='capturar' VALUE='GUARDAR DOCUMENTACIÓN' STYLE='font-size:x-small;' > &nbsp;&nbsp; GUARDAR. </BUTTON>
													         </SPAN>
													     </DIV>    
													 </TH>
													<TH STYLE='text-align:center; width:33%;' ALIGN='CENTER'  WIDTH='33%' CLASS='SHOW_ALERT'>

														<DIV ID='effect' CLASS='ui-widget-content ui-corner-all' STYLE='height:65px;'> 
															<H3 CLASS='ui-widget-header ui-corner-all' STYLE='margin-top:0px; margin-bottom:3%;'>AVISOS S2CREDIT</H3> 
															<SPAN ID='effect_content' STYLE='color:black; font-weight:bold; font-size:120%; width:33%;'>
															
															  	[<LABEL ID='CONT_ALERT' > 0 </LABEL>] 
																<IMG SRC='".$img_path."directional_down.png' ALT='DESPLEGAR LISTA DE ALERTAS' STYLE='HEIGHT:12px;  WIDTH:11; cursor:pointer;' TITLE='DESPLEGAR LISTA DE ALERTAS...'        ID='Arrow_alert'   />
							                                   <DIV  STYLE='opacity:0.90; filter:alpha(opacity=90); display:none; width:300px; height:auto; background:#fff1a0;' ALIGN='CENTER' ID='DIV_ALERT' >
																		<BR />
																		<UL STYLE='text-align:left;' ID='LIST_ALERT'>
																		<LI  STYLE='color:#3B240B; font-weight:bold; font-size:80%; height:10px; cursor:pointer; ' ID='ALERT_CLEAN'  >&nbsp;&nbsp;&nbsp;&nbsp;<IMG  BORDER=0 SRC='".$img_path."tick-circle-frame.png'  ALT='editando'  ALIGN='center' HEIGHT='20px' WIDTH='20px' STYLE='vertical-align:middle;' />&nbsp;&nbsp;SIN SUCESOS</LI>
																		</UL>
																		<BR />
																</DIV>
															</SPAN>
														</DIV>
														 
													</TH>
												</TR>
											</TABLE>
								</DIV>
			</DIV>
		</DIV>
	</DIV>
</DIV>";

								//<BR>
								//<SPAN  STYLE='color:black; font-size:small;  font-weight:bold;'>  SOLICITUD FOLIO&nbsp;&nbsp;&nbsp;#".$Param2." </SPAN>
$html = $Header;
$html.="<FORM Method='POST' ACTION='soli_docsII.php' NAME='solicitud' ID='FORM_DOCS_SOLI' enctype='multipart/form-data'>\n";
$html.="<BR>";
$html.="<TABLE  CELLSPACING='0' STYLE='BORDER:0PX DASHED #999999;' ALIGN='CENTER' WIDTH='98%' CLASS='tablesorter' ID='TBL_DOCS'   >
		<THEAD>
			<TR>
				<TD  ALIGN='CENTER' COLSPAN='7'  STYLE='-moz-border-radius-topleft:  6px; -moz-border-radius-topright: 6px;  background-color : #6fa7d1; height:30px;'>
					<B> <FONT SIZE='2' COLOR='WHITE'>DOCUMENTACIÓN REQUERIDA</FONT></B>
				</TD>
			</TR>
			<TR BGCOLOR='#6fa7d1'>
			  <TH COLSPAN='1' ALIGN='CENTER'>						<FONT COLOR='WHITE' SIZE='2'><B>							 </B></FONT></TH>
			  <TH COLSPAN='1' ALIGN='CENTER' STYLE='cursor:pointer'><FONT COLOR='WHITE' SIZE='2'><B><U>TIPO	            	 </U></B></FONT></TH>
			  <TH COLSPAN='1' ALIGN='CENTER'>						<FONT COLOR='WHITE' SIZE='2'><B>   P.L.D.	 		         </B></FONT></TH>
			  <TH COLSPAN='1' ALIGN='CENTER'>						<FONT COLOR='WHITE' SIZE='2'><B>   STATUS    		         </B></FONT></TH>
			  <TH COLSPAN='1' ALIGN='CENTER'>						<FONT COLOR='WHITE' SIZE='2'><B>   ADJUNTAR DOCUMENTO        </B></FONT></TH>
			  <TH COLSPAN='1' ALIGN='CENTER'  STYLE='cursor:pointer;' ><FONT COLOR='WHITE' SIZE='2'><B> DOC. PENDIENTE </B></FONT></TH>
			  <TH COLSPAN='1' ALIGN='CENTER'>						<FONT COLOR='WHITE' SIZE='2'><B>   DOCUMENTO                 </B></FONT></TH>
			</TR>
       </THEAD>";


      $html.="<TR  > ";
      $html.="<TD COLSPAN='7' ALIGN='CENTER' STYLE='color:black; font-weight:bold; font-size:small;  height:30px;'  BGCOLOR='#dddddd' >		PREVENCIÓN DE LAVADO DE DINERO                                </B></FONT></TD>";            
      $html.="<TR>";

      $html_doc_custom ="<TR  > ";
      $html_doc_custom.="<TD COLSPAN='7' ALIGN='CENTER' STYLE='color:black; font-weight:bold; font-size:small;  height:30px;'  BGCOLOR='#dddddd' >		DOCUMENTACIÓN ADICIONAL                                </B></FONT></TD>";            
      $html_doc_custom.="<TR>";



	
	$RESULT   = get_constantes_documentos();
	
$cont			=	1;
$cont_row		=   1;    
$CONT_PLVD		=	1;
$EXTRANJ_DOC	=  check_nacionalidad($Param2,$T_credit);

while(! $rs_docs->EOF )
  {
      /********VALIDACIONES***********/
      $row_color       =(($cont % 2) == 0 )?('#FDFEFF'):('#E7EEF6');
      $sql_entregado = "SELECT Entregado 			AS ENTG,
                               ID_Doc    			AS ID_DOC,
                               Status    			AS STAT,
                               Fecha_upload 		AS FECH,
                               Tipo         		AS TP,
                               Documento    		AS HASH,
                               Descripcion  		AS USR,
                               ID_Documentos   		AS CAT_DOC,
                               Folio_documento		AS FOLIO_DOC,
                               IF ( ID_Documentos = '-1', fecha_registro_identificacion, Fecha_documento)		AS FECH_DOC
                               
                          FROM ".$tbl_docs ."
                          WHERE ".$Param1." = '".$Param2."' 
                            AND ID_Documentos = '".$rs_docs->fields["ID"]."' ";
      $rs_entregado=$db->Execute($sql_entregado);
      
      
      $color_row='#FBFAAE';
      $img_type_file="";
      $img_entg="";
      $img_zoom="";
      $clic_file="";
      $Fecha_upload="";
      $Hora="";
      if($rs_entregado->fields["ENTG"] == 'Y' && $rs_entregado->fields["STAT"] != 'PENDIENTE')
      {
       $Hora=substr($rs_entregado->fields["FECH"],10);
       $Fecha_upload=substr($rs_entregado->fields["FECH"],-11,2)."/".substr($rs_entregado->fields["FECH"],-14,2)."/".substr($rs_entregado->fields["FECH"],-19,4);

       $status_file=check_file_alter($T_credit,$rs_entregado->fields["ID_DOC"],$rs_entregado->fields["TP"],$rs_entregado->fields["ENTG"],$rs_entregado->fields["HASH"]);
       $color_row=($status_file["STATUS"]=='FALSE')?('#FBC6C6'):('#FBFAAE');
       
       $img_entg=($status_file["STATUS"]=='TRUE')?("<IMG BORDER=0 SRC='".$img_path."accept.png'  ALT='Correcto' STYLE='cursor:pointer;' ONCLICK='alert(\"¡ LA INTEGRIDAD DEL ARCHIVO ES CORRECTA ! \\n\\n=============================\\n\\nDIGITALIZADO POR:  ".$rs_entregado->fields["USR"]." \\n\\nFECHA: ".$Fecha_upload." \\n\\nHORA: ".$Hora." hrs. \");' />"):("<IMG BORDER=0 SRC='".$img_path."exclamation.png'  ALT='Correcto' STYLE='cursor:pointer;' ONCLICK='alert(\"".$status_file["MSG"]."\\n\\n==========================================\\n\\nDIGITALIZADO POR:  ".$rs_entregado->fields["USR"]." \\n\\nFECHA: ".$Fecha_upload." \\n\\nHORA: ".$Hora." hrs. \");'  />");
       
       $img_type_file=($rs_entregado->fields["TP"] == 'pdf')?("<IMG  BORDER=0 SRC='".$img_path."file_acrobat.gif'    ALT='editando'   STYLE='cursor:pointer;' ONCLICK='alert(\"TIPO DE ARCHIVO: *.pdf \");' />"):("<IMG  BORDER=0 SRC='".$img_path."page_white_camera.png'  ALT='editando' STYLE='cursor:pointer;' ONCLICK='alert(\"TIPO DE ARCHIVO: *.".$rs_entregado->fields["TP"]." \");'  />");
       
       $action=($rs_entregado->fields["TP"]=='pdf')?('download_pdf.php'):('download.php?exit=true');

       $img_zoom="<IMG  BORDER=0 SRC='".$img_path."zoom.png'  ALT='editando'   STYLE='cursor:pointer;' ONCLICK='pop_vista_doc(\"".$rs_entregado->fields["CAT_DOC"]."\",\"".$action."\")' />";
      
       $clic_file="ONCLICK='existe_doc(\"".$rs_entregado->fields["ENTG"]."\");'";
       
      }
    
       $checked_doc="";
     if($rs_entregado->fields["ENTG"] == 'Y' && $rs_entregado->fields["STAT"] == 'PENDIENTE')
       {
           $img_type_file="<IMG  BORDER=0 SRC='".$img_path."exclamation-diamond-frame.png'  ALT='editando' />
                           <FONT COLOR='ORANGE' SIZE='1'><B><I>PENDIENTE DE ADJUNTAR</I></B></FONT>";
           $checked_doc='CHECKED';
           
      }
      
      $img_requerido=($rs_docs->fields["SEL"]=='Y')?("<IMG  BORDER=0 SRC='".$img_path."asterisk_orange.png'  ALT='editando'  />"):("");

      $combo_documento=get_documentos_rel($rs_docs->fields["ID"]);

	  /*********PVLD************/
    

           
      if($rs_docs->fields["ID"] > 0)
			$CONT_PLVD ++;
			
      $html.=($CONT_PLVD == 2)?($html_doc_custom):("");

		/*****************FECHA Y FOLIO*********************/
		//$RESULT = array("OBLIG_DOCS"=>$Docs_digtlz_oblig,"FECHA_FOLIO_DOCS"=>$Docs_all_fecha_folio);
		//$FECH_DOC	=($rs_docs->fields["ID"] > 0)?("<B>Fecha:</B> &nbsp;<INPUT  CLASS='datepicker' TYPE='TEXT'  READONLY='readonly' NAME='fech_document[]'  LANG='".$rs_docs->fields["ID"]."' STYLE='width:80px; border:thin solid; borderColor:#dddddd;'>"):("");
		$OBLIG_FOLIO_FECHA = ($rs_docs->fields["ID"] < 0 && $rs_docs->fields["OBLG_PLD"] == 'Y')?("TRUE"):("FALSE");
		$OBLIG_FOLIO_FECHA = (($rs_docs->fields["ID"] ==  '-7' || $rs_docs->fields["ID"] ==  '-8') && $EXTRANJ_DOC == 'TRUE')?("TRUE"):($OBLIG_FOLIO_FECHA);
		$OBLIG_FOLIO_FECHA = ($RESULT["FECHA_FOLIO_DOCS"] == 'SI')?("TRUE"):($OBLIG_FOLIO_FECHA);
		
		if( $OBLIG_FOLIO_FECHA == "TRUE" ){
			
			if( $rs_docs->fields["ID"] == '-1' ){
				$FECH_DOC = ("<B>Fecha Registro:</B> <INPUT  CLASS='numeroEntero FECH_BOX' TYPE='TEXT'  NAME='fech_document[".$rs_docs->fields["ID"]."]'  LANG='".$rs_docs->fields["ID"]."' VALUE='".$rs_entregado->fields["FECH_DOC"]."' STYLE='width:80px; border:thin solid; borderColor:#dddddd;'>");
			}else{
				$FECH_DOC = ("<B>Fecha:</B> &nbsp;<INPUT  CLASS='datepicker FECH_BOX' TYPE='TEXT'  READONLY='readonly' NAME='fech_document[".$rs_docs->fields["ID"]."]'  LANG='".$rs_docs->fields["ID"]."' VALUE='".ffecha($rs_entregado->fields["FECH_DOC"])."' STYLE='width:80px; border:thin solid; borderColor:#dddddd;'>&nbsp;");
			}
			
		}else{
			$FECH_DOC = "";
		}
		
		
		//$FECH_DOC	=($OBLIG_FOLIO_FECHA == "TRUE" )?("<B>Fecha:</B> &nbsp;<INPUT  CLASS='datepicker FECH_BOX' TYPE='TEXT'  READONLY='readonly' NAME='fech_document[".$rs_docs->fields["ID"]."]'  LANG='".$rs_docs->fields["ID"]."' VALUE='".ffecha($rs_entregado->fields["FECH_DOC"])."' STYLE='width:80px; border:thin solid; borderColor:#dddddd;'>"):("");
		//$FECH_DOC	=()?("<B>Fecha:</B> &nbsp;<INPUT  CLASS='datepicker FECH_BOX' TYPE='TEXT'  READONLY='readonly' NAME='fech_document[".$rs_docs->fields["ID"]."]'  LANG='".$rs_docs->fields["ID"]."' VALUE='".ffecha($rs_entregado->fields["FECH_DOC"])."' STYLE='width:80px; border:thin solid; borderColor:#dddddd;'>"):($FECH_DOC);

		
		//$FOLIO_DOC	=($rs_docs->fields["ID"] > 0)?("<B>Folio:</B> &nbsp;<INPUT  CLASS='folio'      TYPE='TEXT'                      NAME='folio_document[]' LANG='".$rs_docs->fields["ID"]."' STYLE='width:80px; border:thin solid; borderColor:#dddddd;'>"):("");
		$FOLIO_DOC	=($OBLIG_FOLIO_FECHA == "TRUE")?("<B>Folio:</B> &nbsp;<INPUT  CLASS='FOLIO_BOX'      TYPE='TEXT'                      NAME='folio_document[".$rs_docs->fields["ID"]."]' LANG='".$rs_docs->fields["ID"]."' VALUE='".$rs_entregado->fields["FOLIO_DOC"]."' STYLE='width:80px; border:thin solid; borderColor:#dddddd;'>"):("");
		//$FOLIO_DOC	=( ($rs_docs->fields["ID"] ==  '-7' || $rs_docs->fields["ID"] ==  '-8' ) && $EXTRANJ_DOC == 'TRUE')?("<B>Folio:</B> &nbsp;<INPUT  CLASS='FOLIO_BOX'      TYPE='TEXT'                      NAME='folio_document[".$rs_docs->fields["ID"]."]' LANG='".$rs_docs->fields["ID"]."' VALUE='".$rs_entregado->fields["FOLIO_DOC"]."' STYLE='width:80px; border:thin solid; borderColor:#dddddd;'>"):($FOLIO_DOC);

		/******************************************************/

	  /**********CLASE DOC REQUERIDO******************/
      $CLASS_REQ = ($rs_docs->fields["ID"] > 0 && $rs_docs->fields["OBLIGATORIO"] == 'Y')?("REQ_FILE"):("");
      $CLASS_REQ = ($rs_docs->fields["ID"] < 0 && $rs_docs->fields["OBLG_PLD"] == 'Y')?("REQ_FILE"):($CLASS_REQ);
      $CLASS_REQ = ( ($rs_docs->fields["ID"] ==  '-7' || $rs_docs->fields["ID"] ==  '-8' ) && $EXTRANJ_DOC == 'TRUE')?("REQ_FILE"):($CLASS_REQ);
	 
	  $IMG_REQ		=  ($CLASS_REQ == 'REQ_FILE' )?("<SUP><IMG  BORDER=0 SRC='".$img_path."asterisk.png'    ALT='Requerido...'   STYLE='WITH:11PX; HEIGHT:11PX;'</IMG> </SUP>"):("");
 
     
	 /********DOC PENDIENTE*************************/
	 $CHECKBOX=($rs_entregado->fields["STAT"] == 'PENDIENTE')?("CHECKED"):("");
	 $INPUT_CHECK = ($rs_entregado->fields["STAT"] == 'DIGITALIZADO' || $RESULT["OBLIG_DOCS"] == 'SI' )?(""):("<INPUT TYPE='CHECKBOX' CLASS='DOC_PEND'  NAME='document_pendiente[".$rs_docs->fields["ID"]."]' ID='DOC_PEND_".$rs_docs->fields["ID"]."' LANG='".$rs_docs->fields["ID"]."' TITLE='MARCAR COMO PENDIENTE' STYLE='cursor:pointer;' ".$CHECKBOX."/>");
     /*******ENTREGADO**************************/
     $ENTREGADO_DOC=($rs_entregado->fields["ENTG"] == 'Y' )?("Y"):("N");
      /*************HTML******************/

      //if($CLASS_REQ == 'REQ_FILE' && ($rs_docs->fields["ID"] != -6) && ($rs_docs->fields["ID"] != -2))
	if(($rs_docs->fields["ID"] != -6) && ($rs_docs->fields["ID"] != -2))     
      {
			  $html.="<TR   BGCOLOR='$row_color' BGCOLOR='#FFFFFF'  
						   ONMOUSEOVER=\"javascript:this.style.backgroundColor='".$color_row."'; this.style.cursor='hand'; \" 
						   ONMOUSEOUT =\"javascript:this.style.backgroundColor='' \"
										   > ";
			  $html.="<TD COLSPAN='1' ALIGN='CENTER' WIDTH='25px'>		<FONT COLOR='GRAY'  SIZE='2'><B> ".$cont_row."                               </B></FONT></TD>";              
			  $html.="<TD COLSPAN='1' ALIGN='LEFT'   WIDTH='300px'>		<FONT COLOR='BLACK' SIZE='2'><B> ".$rs_docs->fields["NMB"]."   ".$IMG_REQ."	 </B></FONT></TD>";
			//$html.="<TD COLSPAN='1' ALIGN='CENTER' WIDTH='20px'>		".$IMG_REQ."																 </B></FONT></TD>";
			  $html.="<TD COLSPAN='1' ALIGN='CENTER' WIDTH='300px'>		".$FECH_DOC." <BR /> &nbsp; &nbsp; &nbsp; &nbsp;".$FOLIO_DOC." &nbsp;&nbsp;&nbsp;						 </B></FONT></TD>";
			  $html.="<TD COLSPAN='1' ALIGN='CENTER' WIDTH='100px'>		".$img_type_file." &nbsp;&nbsp;&nbsp;".$img_zoom."&nbsp;&nbsp;&nbsp;".$img_entg."            </TD>";
			  $html.="<TD COLSPAN='1' ALIGN='RIGHT'  WIDTH='100px'>
																		
																		<INPUT TYPE='FILE'    CLASS='BTN_FILE	".$CLASS_REQ."'   ID='".$rs_docs->fields["ID"]."'   LANG='".$ENTREGADO_DOC."'    STYLE='CURSOR:POINTER; '   NAME='document[]'    ".$clic_file.">
																		<INPUT TYPE='HIDDEN'  NAME='ID_DOC[]'           VALUE='".$rs_docs->fields["ID"]."'>
																																								</TD>";
			  $html.="<TD COLSPAN='1' ALIGN='CENTER'  WIDTH='100px'>".$INPUT_CHECK."</TD>"; 
			  $html.="<TD COLSPAN='1' ALIGN='CENTER'  WIDTH='100px'>".$combo_documento."</TD>";      
			  //$html.="<TD COLSPAN='1' ALIGN='CENTER' WIDTH='20px'>        ".$img_requerido." </TD>";

			  $html.="<TR>";
		}
		
   $cont++;
   //$cont_row=($CLASS_REQ == 'REQ_FILE')?($cont_row + 1):($cont_row);
   $cont_row++;
   $rs_docs->MoveNext();
  }
  
//FORM'S 

$html.="</TABLE>
		<BR>";
$html.="<CENTER>
        
        <INPUT TYPE=HIDDEN  NAME='Param1'                                                 VALUE='".$Param1."'>
        <INPUT TYPE=HIDDEN  NAME='Param2'                                                 VALUE='".$Param2."'>
        <INPUT TYPE=HIDDEN  NAME='ID_Proceso'                                             VALUE='".$ID_Proceso."'>
        <INPUT TYPE=HIDDEN  NAME='T_credit'                                               VALUE='".$T_credit."'>
        <INPUT TYPE=HIDDEN  NAME='tbl_docs'                                               VALUE='".$tbl_docs."'>
        <INPUT TYPE=HIDDEN  NAME='Nomina'                                                 VALUE='".$Nomina."'>
        <INPUT TYPE=HIDDEN  NAME='SIN_MSG'                                                VALUE='".$SIN_MSG."'>
        
        <INPUT TYPE=HIDDEN  NAME='upload'                                                 VALUE='true'>

 		<DIV ID='dialog-message' TITLE='AVISO S2CREDIT.'  STYLE='DISPLAY:NONE;'>
				   <P><SPAN class='ui-icon ui-icon-alert' style='float:left; margin:0 7px 20px 0;'></SPAN></P>
		</DIV>
        </CENTER>";
$html.="</FORM>";

//POP IMG
$html.="<FORM Method='POST' ACTION=''                                  NAME='view_documents' >";
$html.="  <INPUT TYPE=HIDDEN NAME='Param1'                             VALUE='".$Param1."'>
          <INPUT TYPE=HIDDEN NAME='Param2'                             VALUE='".$Param2."'>
          <INPUT TYPE=HIDDEN NAME='ID_Documentos'                      VALUE=''>
          <INPUT TYPE=HIDDEN NAME='Tbl_docs'                           VALUE='".$tbl_docs."'>
          <INPUT TYPE=HIDDEN NAME='ID_Proceso'                         VALUE='".$ID_Proceso."'>
          <INPUT TYPE=HIDDEN  NAME='Nomina'                            VALUE='".$Nomina."'>
          <INPUT TYPE=HIDDEN  NAME='SIN_MSG'                           VALUE='".$SIN_MSG."'>
          <INPUT TYPE=HIDDEN  NAME='noheader'                          VALUE='1'> ";
$html.="</FORM>";

	   
echo $html;
?>
<!--JAVASCRIPT-->
<SCRIPT>

$(document).ready(function(){
   
    $( ".numeroEntero" ).live( "keyup", function() {
       this.value = this.value.replace(/[^0-9\/\-]/g,'');
    }); 
                     
});
        




jQuery.fn.File_Requeridos = function()
{

	var CMP_FILE = 'TRUE';
	
					$("input:file").each(function(n)
					{

						var Str_Class   	= $(this).attr('class');
						var Pos_Class		= Str_Class.search('REQ_FILE');
						var Entregado		= $(this).attr('lang');
						var ID_FILE			= $(this).attr('id');

						//DOCS PENDIENTES
						if( $("#DOC_PEND_"+ID_FILE).length )
							var DOC_PEND		= $("#DOC_PEND_"+ID_FILE).attr('checked');
						else
							var DOC_PEND		= false;

						if( (Pos_Class  >= 0 ) &&  ( $(this).val().length === 0 ) &&  (DOC_PEND === false ) && (Entregado == 'N') )
							 CMP_FILE ='FALSE';
						
					});

	return CMP_FILE;
}

jQuery.fn.Fech_Box_Req = function()
{

	var CMP_FILE 	= 'TRUE';
	
					$(".FECH_BOX").each(function(n)
					 {
						//DOCS PENDIENTES
						var ID_FILE			= $(this).attr('lang');
						//var Str_name		= $(this).attr('name');
						//Str_name            = Str_name.replace(/[^\d\-()\s]+/g, '$');

						//TEXT FILE REQUIRE
						//var Pos_ini    		= Str_name.indexOf('$');
						//var ID_FILE			= Str_name.substring(Pos_ini+1,Str_name.length -1);
						var Str_Class   	= $("#"+ID_FILE).attr('class');
						var Pos_Class		= Str_Class.search('REQ_FILE');

						if( $("#DOC_PEND_"+ID_FILE).length )
							var DOC_PEND		= $("#DOC_PEND_"+ID_FILE).attr('checked');
						else
							var DOC_PEND		= false;
						
						if( (Pos_Class  >= 0 ) && ( jQuery.trim($(this).val()) == '' ) && (DOC_PEND == false ) )
							CMP_FILE = 'FALSE';

							
					 });

	return CMP_FILE;
}


jQuery.fn.Folio_Box_Req = function()
 {
	var CMP_FILE 	= 'TRUE';
	
					$(".FOLIO_BOX").each(function(n)
					 {
						//DOCS PENDIENTES
						var ID_FILE			= $(this).attr('lang');
						var Str_Class   	= $(this).attr('class');
						var Pos_Class		= Str_Class.search('REQ_FILE');

						//TEXT FILE REQUIRE
						//var Pos_ini    		= Str_name.indexOf('$');
						//var ID_FILE			= Str_name.substring(Pos_ini+1,Str_name.length -1);
						var Str_Class   	= $("#"+ID_FILE).attr('class');
						var Pos_Class		= Str_Class.search('REQ_FILE');

						if( $("#DOC_PEND_"+ID_FILE).length )
							var DOC_PEND		= $("#DOC_PEND_"+ID_FILE).attr('checked');
						else
							var DOC_PEND		= false;
						
						if( (Pos_Class  >= 0 ) && (jQuery.trim($(this).val()) == '') && (DOC_PEND == false ) )
							CMP_FILE = 'FALSE';
					 });

	return CMP_FILE;
}


jQuery.fn.File_Ext_valido = function()
{

	var CMP_FILE 	= 'TRUE';
	var VALID_EXT   = ["JPEG","JPG","PJPEG","PDF","ZIP","TAR.GZ","GIF","PNG","TIF","TIFF"];
	
					$("input:file").each(function(n) {

						if( $(this).val().length != 0 )
						{
							var Str_file   	= $(this).val();
								
							var Pos_ext		= Str_file.lastIndexOf(".");
							var Ext_file	= Str_file.substring((Pos_ext+1),Str_file.length);
							    Ext_file    = Ext_file.toUpperCase(); 

							if( jQuery.inArray(Ext_file, VALID_EXT) === -1 )
								CMP_FILE = 'FALSE';
							
						}

					});

	return CMP_FILE;
}



$('.DOC_PEND').live('click',function ()
{
	var ID_FILE			= $(this).attr('lang');
	var DOC_PEND		= $(this).attr('checked');
	
	if(DOC_PEND)
	{
			if( $("#"+ID_FILE).val().length != 0 )
					$("#"+ID_FILE).val('');
	}
			
});

$('#SAVE_FILE').live('click',function ()
{
  var RESULT_FILE_REQ = $(this).File_Requeridos();
  var RESULT_FILE_EXT = $(this).File_Ext_valido();

 var RESULT_FECH_BOX = $(this).Fech_Box_Req();
 var RESULT_FOLIO_BOX = $(this).Folio_Box_Req();


	if(RESULT_FILE_REQ === 'TRUE' && RESULT_FILE_EXT === 'TRUE'	&& RESULT_FECH_BOX === 'TRUE' && RESULT_FOLIO_BOX === 'TRUE')
	{
		  $("#dialog-message" ).html('<BR /><FONT SIZE="2"><B>¿LOS DOCUMENTOS SE ANEXARÁN A  LA SOLICITUD, DESEA CONTINUAR?</B></FONT>');
		  var html =  $("#dialog-message" ).html();
		  $("#dialog-message" ).dialog({
								modal: true,
								buttons: {
											Cancelar: function()
												{
													 $( this ).dialog( "close" );
												},
											OK: function()
												{
													jQuery('#dialog-message').html('');
													jQuery('#dialog-message').html( "<FONT SIZE='2'><B>DIGITALIZANDO LOS DOCUMENTOS, ESPERE UN MOMENTO...</B></FONT><BR /> <BR /> <CENTER><IMG  BORDER=0 SRC='../images/Loading_transparent.gif'  ALT='Cargando...'  /></CENTER>");
													jQuery('#dialog-message').delay(800);
													 $("#FORM_DOCS_SOLI").submit();
												}
										}
									 });
	}
	else
	{
		var MSG_FAIL='';
		if(RESULT_FILE_REQ == 'FALSE' )
			MSG_FAIL="&nbsp;&nbsp;&nbsp;<IMG  BORDER=0 SRC='../images/exclamation-red.png'  ALT='Cargando...'  />&nbsp;&nbsp;&nbsp;EXISTEN DOCUMENTOS OBLIGATORIOS, SIN ASIGNAR. <BR /> <BR />";

		if(RESULT_FILE_EXT == 'FALSE' )
			MSG_FAIL=MSG_FAIL+"&nbsp;&nbsp;&nbsp;<IMG  BORDER=0 SRC='../images/exclamation-diamond-frame.png'  ALT='Cargando...'  />&nbsp;&nbsp;&nbsp;DOCUMENTOS SELECCIONADOS, NO PRESENTAN UNA EXTENSIÓN VÁLIDA. <BR /> <BR />";


		if(RESULT_FECH_BOX == 'FALSE' )
			MSG_FAIL=MSG_FAIL+"&nbsp;&nbsp;&nbsp;<IMG  BORDER=0 SRC='../images/exclamation-diamond-frame.png'  ALT='Cargando...'  />&nbsp;&nbsp;&nbsp;CAPTURE LA FECHA CORRESPONDIENTE A CADA DOCUMENTO  <BR /> <BR />";

		if(RESULT_FOLIO_BOX == 'FALSE' )
			MSG_FAIL=MSG_FAIL+"&nbsp;&nbsp;&nbsp;<IMG  BORDER=0 SRC='../images/exclamation-diamond-frame.png'  ALT='Cargando...'  />&nbsp;&nbsp;&nbsp;CAPTURE EL FOLIO CORRESPONDIENTE A CADA DOCUMENTO ";
			
		jQuery('#dialog-message').html('');
		jQuery('#dialog-message').html( "<LEFT><FONT SIZE='2'><B>"+MSG_FAIL+"</B></FONT></LEFT>");
		  $("#dialog-message" ).dialog({
								width: 550,
								modal: true,
								buttons: {
											Cancelar: function()
												{
													 $( this ).dialog( "close" );
												},
										}
									 });

	}

});


<?
	if($EXTRANJ_DOC == 'TRUE')
	{
?>
				$("#CONT_ALERT").html(1);
				$("#LIST_ALERT li").remove("li#ALERT_CLEAN");
				$("#LIST_ALERT").append("<LI  STYLE='color:#3B240B; font-weight:bold; color:red; font-size:80%; height:19px;  margin-top:3px;' ID='ALERT_REF_BANC_OBLIG'  >&nbsp;&nbsp;&nbsp;&nbsp;<IMG  BORDER=0 SRC='../images/exclamation-diamond-frame.png'  ALT='editando'  STYLE='height:20px; width:20px; vertical-align:middle;'  />&nbsp;&nbsp;EL SOLICITANTE ES EXTRANJERO, POR FAVOR DIGITALIZAR, SU PASAPORTE Y SU CARTA I.N.M.</LI>");

				$( "#effect" ).effect( 'bounce','', 500, '' );
				$( "#effect" ).effect( 'highlight','', 900, '' );

<?
	}
?>

</SCRIPT>
