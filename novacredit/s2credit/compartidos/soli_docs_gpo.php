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
			  	$("#SAVE_FILE").button({
						   icons: {
									primary: "ui-icon-disk"
									
								  }
								  
				  });

				$(".BTN_FILE").button({
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

	$docs_upoload  ="upload_docs_gpo_solidario/";

	$tbl_docs              ="grupo_solidario_documentos";
    $Tipo_credito          ='2';
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


function get_count_documentos()
{
	global $db;
	
	$SQL_CONS="  SELECT
					COUNT(grupo_solidario_cat_documentos.ID_Documento)	AS CUANTOS
				FROM  grupo_solidario_cat_documentos	 ";
   $rs_cons=$db->Execute($SQL_CONS);


	return $rs_cons->fields["CUANTOS"];
}

function get_nmb_gpo_solidario($ID_GPO)
{
	global $db;
	
			$Sql_cons="
						SELECT
							   UCASE(grupo_solidario.Nombre)		AS NMB
							 
						 FROM grupo_solidario
						 WHERE grupo_solidario.ID_grupo_soli ='".$ID_GPO."' ";
			$rs_cons=$db->Execute($Sql_cons);

			return $rs_cons->fields["NMB"];

}

function get_ciclo_gpo_solidario($ID_GPO)
{
	global $db;
	
			$Sql_cons="
						SELECT
							   Ciclo_gpo		AS CICLO_GPO
						 FROM grupo_solidario
						 WHERE grupo_solidario.ID_grupo_soli ='".$ID_GPO."' ";
			$rs_cons=$db->Execute($Sql_cons);

			return $rs_cons->fields["CICLO_GPO"];

}

?>
<!--ÁREA CSS-->




<!--JAVASCRIPT-->

<!--JQUERY-->


<!--AJAX-->
<?

/************DATOS SQL******************/

  $tbl_docs              ="grupo_solidario_documentos";
  $Tipo_credito          ='2';
  $Tipo_documento        ='Solidario';


	$sql_docs=" SELECT
			grupo_solidario_cat_documentos.ID_Documento					AS ID,
			grupo_solidario_cat_documentos.Descripcion 					AS NMB,
			grupo_solidario_cat_documentos.Status						AS STAT,
			grupo_solidario_cat_documentos.Obligatorio					AS OBLG
	FROM  grupo_solidario_cat_documentos
		WHERE
				grupo_solidario_cat_documentos.Status = 'Activo'
	 ORDER BY ID";

 $rs_docs=$db->Execute($sql_docs);
/*****************************************/

//HTML

				$arr_sucursal 			=	sucursal_datos($ID_SUC,$db);
				$capturista   			=	usuario_nombre($ID_USR,$db);
				$fecha_captura			=	traducefecha(date("Y/m/d"),'FECHA_COMPLETA');
				$Nombre_cte				=	cliente_nombre($Param2,$db);
				$DOCS_REQUERIDOS		=	get_count_documentos();

$Header ="
		<DIV CLASS='demo'  ALIGN='center' STYLE='margin-top:1%;'>
					<DIV CLASS='portlet' STYLE='width:90%;'>
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

$NMB_GPO = get_nmb_gpo_solidario($Param2);

$html = $Header;
$html.="<FORM Method='POST' ACTION='soli_docs_gpoII.php' NAME='solicitud' ID='FORM_DOCS_SOLI' enctype='multipart/form-data'>\n";
$html.="<BR>";
$html.="<TABLE  CELLSPACING='0' STYLE='BORDER:0PX DASHED #999999;' ALIGN='CENTER' WIDTH='80%' CLASS='tablesorter' ID='TBL_DOCS'   >
		<THEAD>
			<TR>
				<TD  ALIGN='CENTER' COLSPAN='7'  STYLE='-moz-border-radius-topleft:  6px; -moz-border-radius-topright: 6px;  background-color : #6fa7d1; height:30px;'>
					<B> <FONT SIZE='3' COLOR='WHITE'>GRUPO SOLIDARIO: ".$NMB_GPO." </FONT></B>
				</TD>
			</TR>
			<TR BGCOLOR='#6fa7d1'>
			  <TH COLSPAN='1' ALIGN='CENTER'>						<FONT COLOR='WHITE' SIZE='2'><B>							 </B></FONT></TH>
			  <TH COLSPAN='1' ALIGN='CENTER' STYLE='cursor:pointer'><FONT COLOR='WHITE' SIZE='2'><B><U>DOCUMENTO	            	 </U></B></FONT></TH>
			  <TH COLSPAN='1' ALIGN='CENTER'>						<FONT COLOR='WHITE' SIZE='2'><B>   OBLIGATORIO 		         </B></FONT></TH>
			  <TH COLSPAN='1' ALIGN='CENTER'>						<FONT COLOR='WHITE' SIZE='2'><B>   STATUS    		         </B></FONT></TH>
			  <TH COLSPAN='1' ALIGN='CENTER'>						<FONT COLOR='WHITE' SIZE='2'><B>   ADJUNTAR DOCUMENTO        </B></FONT></TH>
			</TR>
       </THEAD>";


$cont			=	1;
$cont_row		=   1;    
$CONT_PLVD		=	1;
$CICLO_GPO 		= get_ciclo_gpo_solidario($Param2);

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
                               ID_Documento   		AS CAT_DOC,
                               Folio_documento		AS FOLIO_DOC,
                               Fecha_documento		AS FECH_DOC
                               
                          FROM ".$tbl_docs ."
                          WHERE $Param1 = '".$Param2."' 
                            AND ID_Documento 	 = '".$rs_docs->fields["ID"]."'
                            AND Ciclo_gpo		 = '".$CICLO_GPO."' ";
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
		   
		   $action=($rs_entregado->fields["TP"]=='pdf')?('download_gpo_pdf.php'):('download_gpo.php?exit=true');

		   $img_zoom="<IMG  BORDER=0 SRC='".$img_path."zoom.png'  ALT='editando'   STYLE='cursor:pointer;' ONCLICK='pop_vista_doc(\"".$rs_entregado->fields["CAT_DOC"]."\",\"".$action."\")' />";
		  
		   $clic_file="ONCLICK='existe_doc(\"".$rs_entregado->fields["ENTG"]."\");'";
      }
    
       $checked_doc="";
     if($rs_entregado->fields["ENTG"] == 'Y' && $rs_entregado->fields["STAT"] == 'PENDIENTE')
       {
           $img_type_file="<IMG  BORDER=0 SRC='".$img_path."icon_alert.gif'  ALT='editando' />
                           <FONT COLOR='ORANGE' SIZE='1'><B><I>ENTREGADO - PENDIENTE DE ADJUNTAR</I></B></FONT>
                           <IMG  BORDER=0 SRC='".$img_path."icon_alert.gif'  ALT='editando' />";
           $checked_doc='CHECKED';
      }
      
        $IMG_REQ		=  ($rs_docs->fields["OBLG"] == 'Y')?("<IMG  BORDER=0 SRC='".$img_path."asterisk.png'    ALT='Requerido...'   STYLE='' "):("");
		
	  /**********CLASE DOC REQUERIDO******************/
      $CLASS_REQ = ($rs_docs->fields["OBLG"] == 'Y' )?("REQ_FILE"):("");
	  $ENTEGRADO = (empty($rs_entregado->fields["ENTG"]))?("N"):($rs_entregado->fields["ENTG"]);
      /*************HTML******************/
			  $html.="<TR   BGCOLOR='$row_color'   
						   ONMOUSEOVER=\"javascript:this.style.backgroundColor='".$color_row."'; this.style.cursor='hand'; \" 
						   ONMOUSEOUT =\"javascript:this.style.backgroundColor='' \"
										   > ";
			  $html.="<TD COLSPAN='1' ALIGN='CENTER' WIDTH='25px'>		<FONT COLOR='GRAY'  SIZE='2'><B> ".$cont_row."                               </B></FONT></TD>";              
			  $html.="<TD COLSPAN='1' ALIGN='LEFT'   WIDTH='300px'>		<FONT COLOR='BLACK' SIZE='2'><B> ".$rs_docs->fields["NMB"]."   				 </B></FONT></TD>";
			  $html.="<TD COLSPAN='1' ALIGN='CENTER' WIDTH='20px'>		".$IMG_REQ."																 </B></FONT></TD>";
			  //$html.="<TD COLSPAN='1' ALIGN='CENTER' WIDTH='200px'>	".$FECH_DOC." <BR /> ".$FOLIO_DOC." &nbsp;&nbsp;&nbsp;					 	 </B></FONT></TD>";
			  $html.="<TD COLSPAN='1' ALIGN='CENTER' WIDTH='100px'>		".$img_type_file." &nbsp;&nbsp;&nbsp;".$img_zoom."&nbsp;&nbsp;&nbsp;".$img_entg."            </TD>";
			  $html.="<TD COLSPAN='1' ALIGN='RIGHT'  WIDTH='100px'>      <INPUT TYPE='FILE'    CLASS='BTN_FILE	".$CLASS_REQ."'   ID='".$rs_docs->fields["ID"]."'   LANG='".$ENTEGRADO."'    STYLE='CURSOR:POINTER; '   NAME='document[]'    ".$clic_file.">
																		<INPUT TYPE='HIDDEN'  NAME='ID_DOC[]'           VALUE='".$rs_docs->fields["ID"]."'>
																																								</TD>";
			  //$html.="<TD COLSPAN='1' ALIGN='CENTER'  WIDTH='100px'>".$combo_documento."</TD>";      
			  //$html.="<TD COLSPAN='1' ALIGN='CENTER' WIDTH='20px'>        ".$img_requerido." </TD>";

			  $html.="<TR>";
		
   $cont++;
 
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
$html.="<FORM Method='POST' ACTION=''                                   NAME='view_documents' >";
$html.="  <INPUT TYPE=HIDDEN  NAME='Param1'                             VALUE='".$Param1."'>
          <INPUT TYPE=HIDDEN  NAME='Param2'                             VALUE='".$Param2."'>
          <INPUT TYPE=HIDDEN  NAME='ID_Documentos'                      VALUE=''>
          <INPUT TYPE=HIDDEN  NAME='Tbl_docs'                           VALUE='".$tbl_docs."'>
          <INPUT TYPE=HIDDEN  NAME='ID_Proceso'                         VALUE='".$ID_Proceso."'>
          <INPUT TYPE=HIDDEN  NAME='Nomina'                             VALUE='".$Nomina."'>
          <INPUT TYPE=HIDDEN  NAME='SIN_MSG'                            VALUE='".$SIN_MSG."'>
          <INPUT TYPE=HIDDEN  NAME='noheader'                           VALUE='1'> ";
$html.="</FORM>";

	   
echo $html;
?>
<!--JAVASCRIPT-->
<SCRIPT>
jQuery.fn.File_Requeridos = function()
{

	var CMP_FILE = 'TRUE';
	
					$("input:file").each(function(n) {

						var Str_Class   	= $(this).attr('class');
						var Pos_Class		= Str_Class.search('REQ_FILE');
						var Entregado		= jQuery.trim($(this).attr('lang'));

						if( (Pos_Class  >= 0 ) &&  ( $(this).val().length === 0 ) && (Entregado !='Y' ) ) 
							 CMP_FILE ='FALSE';
						
					});

	return CMP_FILE;
}

jQuery.fn.File_Ext_valido = function()
{

	var CMP_FILE 	= 'TRUE';
	var VALID_EXT   = ["jpeg","jpg","pjpeg","pdf","zip","tar.gz","gif","png","TIF","TIFF"];
	
					$("input:file").each(function(n) {

						if( $(this).val().length != 0 )
						{
							var Str_file   	= $(this).val();
								
							var Pos_ext		= Str_file.lastIndexOf(".");
							var Ext_file	= Str_file.substring((Pos_ext+1),Str_file.length);

							if( jQuery.inArray(Ext_file, VALID_EXT) === -1 )
								CMP_FILE = 'FALSE';
							
						}

					});

	return CMP_FILE;
}
				 
$('#SAVE_FILE').live('click',function ()
{
  var RESULT_FILE_REQ = $(this).File_Requeridos();
  var RESULT_FILE_EXT = $(this).File_Ext_valido();


	if(RESULT_FILE_REQ === 'TRUE' && RESULT_FILE_EXT === 'TRUE')
	{
		  $("#dialog-message" ).html('<BR /><FONT SIZE="2"><B>¿LOS DOCUMENTOS SE ANEXARÁN AL GRUPO SOLIDARIO, DESEA CONTINUAR?</B></FONT>');
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

</SCRIPT>
