<?php

/****************************************/
/*Fecha: 19/Agosto/2010
/*Autor: Tonathiu Cárdenas
/*Descripción: Digitalizar documentación para una solicitud 
/*Dependencias: soli_docs.php 
/****************************************/

//Librerías
//$noheader=1;
require($DOCUMENT_ROOT."/rutas.php");
require($class_path."promocion/lib_informacion_basica.php");   //INFO SUCURSAL Y USUARIO
require($class_path."promocion/lib_campos_captura.php");       //FORMA LOS COMBOS
require("../sucursal/promocion/js/jquery_links.php");   	   //LIBRERÍAS DE JQUERY

//Inicio conexión
$db = ADONewConnection(SERVIDOR); 
$db->Connect(IP,USER,PASSWORD,NUCLEO);
//Fin Conexión


//JAVASCRIPT Y AJAX
echo "<SCRIPT TYPE='TEXT/JAVASCRIPT'  src='../sucursal/promocion/js/jquery.tablesorter.js'></SCRIPT>";

?>
<!--ÁREA CSS-->
<LINK REL="stylesheet" HREF="../sucursal/promocion/css/solicitud.css"   TYPE="text/css" MEDIA="print, projection, screen">
<link REL="stylesheet" HREF="<?=$shared_scripts?>/jquery_ui/development-bundle/themes/cupertino/jquery.ui.all.css">
<link REL="stylesheet" HREF="<?=$shared_scripts?>/jquery_ui/development-bundle/demos/demos.css">

<SCRIPT>
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

				$(".BTN_FILE").button({
						   icons: {
									primary: "ui-icon-circle-zoomin"
									
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
});
</SCRIPT>
<?
/*****************FUNCTIONS**************************/
//SELECT TIPO DOC
/*
function get_tipo_doc($TIPO_DOC)
{
  global $db;
  
		 $sql_cons="SELECT
							Descripcion   AS DESCP
						FROM
							cat_documentos_tipo
						WHERE ID_Documento_Tipo ='".$TIPO_DOC."' ";

		 $rs_cons = $db->Execute($sql_cons);

	return  strtoupper($rs_cons->fields["DESCP"]);
}
*/

//DOCUMENTOS DIGITALIZADOS VS REQUERIDOS

function get_documentos_digitalizados($Tipo_credito,$tbl_docs,$Param2)
{
  global $db;

  	$sql_Count_req=" SELECT
						COUNT(ID_Documento) AS CUANTOS
					FROM  grupo_solidario_cat_documentos	 
						WHERE Status = 'Activo'  ";
    $rs_Count_req=$db->Execute($sql_Count_req);

	$Cont_docs=$rs_Count_req->fields["CUANTOS"];

	$CICLO_GPO = get_ciclo_gpo_solidario($Param2);
	
	$sql_Count_docs=" SELECT
						COUNT(*)	AS CUANTOS
					FROM  ".$tbl_docs."	 
						WHERE ID_grupo_soli      =   '".$Param2."'
							AND Ciclo_gpo		 =   '".$CICLO_GPO."'
							AND Status ='DIGITALIZADO'  ";
    $rs_Count_docs=$db->Execute($sql_Count_docs);
    
	$Docs_digitalizados=$rs_Count_docs->fields["CUANTOS"];



   $INFO_DOCS=array("REQUERIDOS"=>$Cont_docs,"DIGITALIZADOS"=>$Docs_digitalizados);
   
  return $INFO_DOCS;

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
//ORGANIZAMOS LOS ATRIBUTOS DEL FILE (NAME,TYPE,SIZE)
function reArrayFiles($file_post)
{
    $file_order = array();
    $file_keys = array_keys($file_post);

    for ($i=0; $i<(count($file_post['name'])); $i++) {
        foreach ($file_keys as $key) 
        {
            $file_order[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_order;
}

//REVISAMOS EL ARCHIVO ADJUNTO
function check_file($file)
{
   $allowedExtensions = array("jpeg","jpg","pjpeg","pdf","zip", 
     "tar.gz","gif","png","TIF","TIFF"); 
    
  $status='TRUE';
 
  if($file["name"] == '')
  {
   $status='FALSE';
   $msg="";
  }
  else if($file["error"] > 0)
  {
    $status='FALSE';
    $msg="ERROR AL SUBIR EL  ARCHIVO (".$file['name']."), INTENTELO DE NUEVO.";
  }
  else if($file['size'] > 4000000)
  {
     $status='FALSE';
     $msg="EL TAMAÑO DEL ARCHIVO (".$file['name']." - ".$file['size'].") , ES SUPERIOR A LOS 4 MB.";
  }
  else if (!in_array(end(explode(".", 
            strtolower($file['name']))), 
            $allowedExtensions)) 
  {
     $status='FALSE';
     $msg .= "EL  ARCHIVO (".$file['name'].") NO CUENTA CON EL  FORMATO REQUERIDO (JPG/JPEG/PJPEG/TIF/TIFF/PDF/ZIP/TAR.GZ).";
  }

 $status=array("STATUS"=>$status,"MSG"=>$msg);
 
 return $status;
}

//UPLOAD FILES
function upload_documentos($docs_params)   
{
global $db;

                 //DETECTAR SI EXISTE EL DOCUMENTO DIGITALIZADO
		 $sql_cont="SELECT COUNT(*) AS CONT 
			       FROM ".$docs_params["TBL"]." 
			       WHERE ID_grupo_soli  = '".$docs_params["ID_SOLI"]."'
			         AND Ciclo_gpo		= '".$docs_params["CICLO_GPO"]."'
				     AND ID_Documento 	= '".$docs_params["ID_DOC"]."' ";

		 $rs_cont = $db->Execute($sql_cont);
		 $num_count = $rs_cont->fields["CONT"];

          if (@copy($docs_params["TMP_NMB"], $docs_params["RUTA"].$docs_params["NAME"])) //DEPRECIADA POR SEGURIDAD move_uploaded_file - NOS ASEGURAMOS FILE UPLOAD POR POST
		 //if(move_uploaded_file($docs_params["TMP_NMB"],$docs_params["RUTA"].$docs_params["NAME"]) )
		 {
                    
               chmod ($docs_params["RUTA"].$docs_params["NAME"],0777);
		    if($num_count == '0')	 
		      {	 
						 $sql_ins = "INSERT INTO ".$docs_params["TBL"]."
									 (ID_grupo_soli,Ciclo_gpo,ID_Documento,Entregado,Descripcion,Fecha_upload,Tipo,ID_Tipocredito) 
									 VALUES ('".$docs_params["ID_SOLI"]."','".$docs_params["CICLO_GPO"]."','".$docs_params["ID_DOC"]."','Y','".$docs_params["USR"]."',NOW(),'".$docs_params["EXT"]."','".$docs_params["TCREDIT"]."')";
						 $rs_ins=$db->Execute($sql_ins);
						 $id_doc = $db->_insertid();
               }
               else
               {
						$sql_entregado = "SELECT ID_Doc AS ID,
												 Tipo AS TIPO
										   FROM ".$docs_params["TBL"]." 
										   WHERE ID_grupo_soli   =   '".$docs_params["ID_SOLI"]."'
											  AND Ciclo_gpo		 =   '".$docs_params["CICLO_GPO"]."'
											  AND ID_Documento   =   '".$docs_params["ID_DOC"]."' ";
						$rs_entregado=$db->Execute($sql_entregado);
						$id_doc=$rs_entregado->fields["ID"];
						$ext_file=$rs_entregado->fields["TIPO"];
						
						$Exist_name_doc = (md5('doc'.$id_doc)).".".$ext_file." ";
						unlink($docs_params["RUTA"].$Exist_name_doc);
                }

			  //HASH SOBRE  SU PK-ID PARA IDENTIFICACIÓN
			  // chdir($docs_params["RUTA"]);
			  $New_name_doc = (md5('doc'.$id_doc)).".".$docs_params["EXT"]."";
			  rename($docs_params["RUTA"].$docs_params["NAME"],$docs_params["RUTA"].$New_name_doc);
			  //$salida=shell_exec("mv ".$docs_params["RUTA"].$docs_params["NAME"]." ".$docs_params["RUTA"].$New_name_doc." ");
			  //debug("mv ".$docs_params["RUTA"].$docs_params["NAME"]." ".$docs_params["RUTA"].$New_name_doc." ");
			  chmod ($docs_params["RUTA"].$New_name_doc,0777);



			//*************ALMACENANDO HASH A LA IMÁGEN - IDENTIFICAR MODIFICACIONES *******
			$f1= fopen($docs_params["RUTA"].$New_name_doc,"rb");

			//LEEMOS EL FICHERO COMPLETO LIMITANDO LA LECTURA AL TAMAÑO DE FICHERO
			$Doc_hash = fread($f1, $docs_params["SIZE"]);

			//ANTEPONEMOS SLASH A LAS COMILLAS QUE PUDIERA CONTENER EL FICHERO PARA EVITAR QUE SEAN INTERPRETADAS COMO FINAL DE CADENA
			$Doc_hash=addslashes($Doc_hash);

			$Doc_hash=md5($Doc_hash);


			$sql_update = "UPDATE ".$docs_params["TBL"]."
								SET Documento 			= '".$Doc_hash."',
									Tipo      			= '".$docs_params["EXT"]."',
									Fecha_upload		= NOW(),
									Descripcion     	= '".$docs_params["USR"]."',
									Status           	='DIGITALIZADO'
					 WHERE ID_Doc = '".$id_doc."' ";
			$db->Execute($sql_update);

			
		}
}

//REVISAR INTEGRIDAD DEL ARCHIVO
function check_file_alter($T_credit,$Id_doc,$Ext_file,$Is_upload,$File_hash,$Doc_migra)
{
  
global $ID_USR;

	$docs_upoload  			= "upload_docs_gpo_solidario/";
	$tbl_docs        		= "grupo_solidario_documentos";
    $Tipo_credito           = '2';

		
$Ruta_file = $docs_upoload . (md5('doc'.$Id_doc)).".".$Ext_file."";
//debug($Ruta_file);
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
 
  $status_arr=array("STATUS"=>$status_file,"MSG"=>$msg,"FILE"=>$Ruta_file);
  return $status_arr;
 }
 
 function existe_archivo($T_credit,$Id_doc,$ID_solicitud)
 {
 global $db;

  $tbl_docs              ="grupo_solidario_documentos";
  $Tipo_credito          ='2';
  $Tipo_documento        ='Solidario';
  $CICLO_GPO 			 = get_ciclo_gpo_solidario($Param2);
	     
  
     $sql_entregado = "SELECT
							   Entregado 			AS ENTG,
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
                          WHERE ID_grupo_soli    = '".$ID_solicitud."'
                            AND Ciclo_gpo		 =   '".$CICLO_GPO."'
                            AND ID_Documento     = '".$Id_doc."' ";
      $rs_entregado=$db->Execute($sql_entregado);
    
      $Entregado=($rs_entregado->fields["ENTG"] == 'Y')?('TRUE'):('FALSE');

   return $Entregado;
 }
/***************FIN FUCTIONS******************************/
?>
<!--ÁREA CSS-->



<!--JAVASCRIPT-->
<script type="text/javascript">
function popup(var1,var2,index)
{
  window.open('download_gpo.php?Param1='+var1+'&Param2='+var2+'&Cont='+index, 'vista_doc'+index, 'menubar=0,resizable=1,location=0,directories=0,scrollbars=1, width=640,height=400');
  //alert(ruta);
  return true;
}
</script>

<!--JQUERY-->


<!--AJAX-->
<?

//UPLOAD FILES POST

if(!empty($upload))
{
	/***PARÁMETROS FUNCIÓN UPLOAD_DOCUMENTOS***/
	$docs_upoload  		   ="upload_docs_gpo_solidario/";
	$tbl_docs              ="grupo_solidario_documentos";
    $Tipo_credito          ='2';



	$sql_usr ="SELECT UCASE(CONCAT(Nombre,' ',AP_Paterno,' ',AP_Materno)) AS USR 
		  FROM usuarios 
		  WHERE ID_User= '".$ID_USR."' ";
	$rs_usr  = $db->Execute($sql_usr); 
	$Responsable=$rs_usr->fields["USR"];
	/*********************************************/
	
	if ($_FILES['document'])
	{
	        
        $msg_error="";
		$file_document = reArrayFiles($_FILES['document']);
		foreach ($file_document as $key => $file_atrr)
		{
		  $status=check_file($file_atrr);
		  if( $status["STATUS"] == 'TRUE' )
		  {
                    
                    //MANDAMOS TODOS LOS PARÁMETROS NECESARIOS PARA EL UPLOAD
                    $ext_file	=	end(explode(".", strtolower($file_atrr['name'])));
                    $CICLO_GPO	=	get_ciclo_gpo_solidario($Param2);
                    $docs_params=array("RUTA"=>$docs_upoload,"NAME"=>$file_atrr['name'],"ID_SOLI"=>$Param2,"ID_DOC"=>$ID_DOC[$key],"TBL"=>$tbl_docs,"TCREDIT"=>$Tipo_credito,"USR"=>$Responsable,"EXT"=>$ext_file,"TMP_NMB"=>$file_atrr['tmp_name'],"SIZE"=>$file_atrr['size'],"TIPO_DOCUMENT"=>$TIPO_DOC[$key],"FECH_DOCUMENT"=>$fech_document[$ID_DOC[$key]],"FOLIO_DOCUMENT"=>$folio_document[$ID_DOC[$key]],"CICLO_GPO"=>$CICLO_GPO);                     

					
                    upload_documentos($docs_params);
                 
		  }
		  else
                      $msg_error.=$status["MSG"];



		}
                
        }//Fin if ($_FILES['document'])

        /***DOCUMENTOS PENDIENTES***/
       if(count($check_document) > '0') 
       foreach($check_document as $key => $value)
       {
	 
				 $file_exist=existe_archivo($T_credit,$value,$Param2);

				 if($file_exist=='FALSE')
				 {
				  $CICLO_GPO 			 = get_ciclo_gpo_solidario($Param2);
				  $sql_ins = "INSERT INTO ".$tbl_docs."
							 (ID_grupo_soli,Ciclo_gpo,ID_Documentos,Entregado,Descripcion,Fecha_upload,ID_Tipocredito,Status) 
							 VALUES ('".$Param2."','".$CICLO_GPO."','".$value."','Y','".$Responsable."',NOW(),'".$Tipo_credito."','PENDIENTE')";

				  $rs_ins=$db->Execute($sql_ins);

				 }
       }




      $INFO_DOCS=get_documentos_digitalizados($Tipo_credito,$tbl_docs,$Param2);
    
	 if(  ($INFO_DOCS["DIGITALIZADO"]  >=  $INFO_DOCS["REQUERIDO"]) )
	 {
			 if($INFO_DOCS["SOLI_SUCESOS"] == 'FALSE')
			 {

					$Query ="INSERT INTO grupo_solidario_sucesos (ID_grupo_soli,Fecha,Atendio,Status,Suceso)
													 VALUES('".$Param2."',NOW(),'".$Responsable."','Digitalización','DIGITALIZACIÓN DE DOCUMENTOS GRUPO')";	
					$db->Execute($Query);

					echo"<SCRIPT>opener.location.reload();</SCRIPT>";
			  }
	 }
  
}

/************DATOS SQL******************/

 if(!empty($tipo_soli))
 {
    
    $sql="SELECT Fecha_sistema,
		CONCAT(Nombre,' ',AP_Paterno,' ',AP_Materno)   AS RESPONSABLE
		FROM solicitud_proceso
		LEFT JOIN usuarios ON solicitud_proceso.ID_User = usuarios.ID_User
		WHERE ID_Proceso=5
		AND ID_tipo_solicitud=".$tipo_soli."
		AND ID_Solicitud=".$Param2."";
		
    $rs= $db->Execute($sql);
    
    list($fecha_captura,$capturista)=$rs -> fields;
    
    $T_credit='pfisica';
    
 }
 else
 {
    $fecha_captura		=date('Y/m/d');
    $capturista   		=usuario_nombre($ID_USR,$db);
 }

 
 
  $tbl_docs              ="grupo_solidario_documentos";
  $Tipo_credito          ='2';
  $Tipo_documento        ='Solidario';


	$sql_docs="SELECT
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
				$arr_sucursal 			=sucursal_datos($ID_SUC,$db);
				$capturista   			=usuario_nombre($ID_USR,$db);
				$fecha_captura			=traducefecha(date("Y/m/d"),'FECHA_COMPLETA');

$html ="
		<DIV CLASS='demo'  ALIGN='center'   STYLE='margin-top:1%;'>
					<DIV CLASS='portlet'	STYLE='width:90%;'>
							<H3 CLASS='ui-widget-header ui-corner-all' STYLE='background:#e5f0f8;  color:black; font-size:medium;  margin-top:0px;'>DIGITALIZACIÓN DE DOCUMENTOS  </H3>
							<DIV CLASS='portlet-content'>
								<TABLE  CELLPADDING='2' CELLSPACING='1' BORDER='0px' WIDTH='90%'>
									<TR>
										<TD  WIDTH='33%' ALIGN='CENTER'>
										<DIV  CLASS='ui-widget-content ui-corner-all'  STYLE='height:80px;'>
										
											<H3 CLASS='ui-widget-header ui-corner-all' STYLE='background:#DDDDDD;  color:black;  margin-top:0px;'>".$arr_sucursal["Sucursal"]."</H3>
											
											<SPAN  STYLE='vertical-align:middle;' > <I>".$arr_sucursal["Direccion"]."</I></SPAN>
										</DIV>
										
										</TD>

										<TD  WIDTH='33%' ALIGN='CENTER'>
											<DIV  CLASS='ui-widget-content ui-corner-all'  STYLE='height:80px;'> 
												<H3 CLASS='ui-widget-header ui-corner-all' STYLE='background:#DDDDDD;  color:black;  margin-top:0px;'>FECHA</H3>
												
												<SPAN  STYLE='vertical-align:middle;' > <I>".strtoupper($fecha_captura)."</I></SPAN>
											</DIV>											  
										</TD>

										<TD  WIDTH='33%' ALIGN='CENTER'>
											<DIV  CLASS='ui-widget-content ui-corner-all'  STYLE='height:80px;'> 
												<H3 CLASS='ui-widget-header ui-corner-all' STYLE='background:#DDDDDD;  color:black;  margin-top:0px;'>CAPTURISTA</H3>
												
												<SPAN  STYLE='vertical-align:middle;' > <I>".strtoupper($capturista)."</I></SPAN>
											</DIV>											  
										</TD>

									</TR>
							</TABLE>
							<BR>
							<SPAN  STYLE='color:black; font-size:small;  font-weight:bold;'>  SOLICITUD FOLIO&nbsp;&nbsp;&nbsp;#".$Param2." </SPAN>
			</DIV>
		</DIV>
	</DIV>
</DIV>";
	
	$NMB_GPO = get_nmb_gpo_solidario($Param2);
	$html.="<FORM Method='POST' ACTION='soli_docs_gpoII.php' NAME='solicitud' enctype='multipart/form-data'>\n";
	$html.="<BR>";
	$html.="<TABLE  CELLSPACING='0' STYLE='BORDER:0PX DASHED #999999;' ALIGN='CENTER' WIDTH='80%' CLASS='tablesorter' ID='TBL_DOCS'   >
			<THEAD>
				<TR>
					<TD  ALIGN='CENTER' COLSPAN='5'  STYLE='-moz-border-radius-topleft:  6px; -moz-border-radius-topright: 6px;  background-color : #6fa7d1;'>
						<B> <FONT SIZE='2' COLOR='WHITE'>GRUPO SOLIDARIO: ".$NMB_GPO." </FONT></B>
					</TD>
				</TR>
				<TR BGCOLOR='#6fa7d1'>
				  <TH COLSPAN='1' ALIGN='CENTER'>						<FONT COLOR='WHITE' SIZE='2'><B>							 </B></FONT></TH>
				  <TH COLSPAN='1' ALIGN='CENTER' STYLE='cursor:pointer'><FONT COLOR='WHITE' SIZE='2'><B><U>TIPO	            	 </U></B></FONT></TH>
				  <TH COLSPAN='1' ALIGN='CENTER'>						<FONT COLOR='WHITE' SIZE='2'><B>   STATUS    		         </B></FONT></TH>
				  <TH COLSPAN='1' ALIGN='CENTER'>						<FONT COLOR='WHITE' SIZE='2'><B>   ADJUNTAR DOCUMENTO        </B></FONT></TH>
				  <TH COLSPAN='1' ALIGN='CENTER'>						<FONT COLOR='WHITE' SIZE='2'><B>   DOCUMENTO                 </B></FONT></TH>
				</TR>
		   </THEAD>";
       
      
$cont			=	1;
$cont_row		=   1;  
$CONT_PLVD		=	1;
$requeridos		=	'0';
$CICLO_GPO 		= get_ciclo_gpo_solidario($Param2);

while(! $rs_docs->EOF )
  {
      /********VALIDACIONES***********/
      $row_color       =(($cont % 2) == 0 )?('#FDFEFF'):('#E7EEF6');
      $sql_entregado = "SELECT  Entregado 			AS ENTG,
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
                            AND ID_Documento     = '".$rs_docs->fields["ID"]."'
                            AND Ciclo_gpo		 = '".$CICLO_GPO."' ";
      $rs_entregado=$db->Execute($sql_entregado);

      $img_entg="";
      $color_row='#FBFAAE';
      $img_type_file="";
      $boton="";
      $Fecha_upload="";
      $Hora="";
      if($rs_entregado->fields["ENTG"] == 'Y' && $rs_entregado->fields["STAT"] == 'DIGITALIZADO')
      {
      
       $Hora=substr($rs_entregado->fields["FECH"],10);
       $Fecha_upload=substr($rs_entregado->fields["FECH"],-11,2)."/".substr($rs_entregado->fields["FECH"],-14,2)."/".substr($rs_entregado->fields["FECH"],-19,4);

       $status_file=check_file_alter($T_credit,$rs_entregado->fields["ID_DOC"],$rs_entregado->fields["TP"],$rs_entregado->fields["ENTG"],$rs_entregado->fields["HASH"],$rs_entregado->fields["MIGRA"]);
 
       $color_row=($status_file["STATUS"]=='FALSE')?('#FBC6C6'):('#FBFAAE');
              
       $img_type_file=($rs_entregado->fields["TP"] == 'pdf')?("<IMG  BORDER=0 SRC='".$img_path."file_acrobat.gif'  ALT='editando'   STYLE='cursor:pointer;' ONCLICK='alert(\"TIPO DE ARCHIVO: *.pdf \");' />"):("<IMG  BORDER=0 SRC='".$img_path."page_white_camera.png'  ALT='editando' STYLE='cursor:pointer;' ONCLICK='alert(\"TIPO DE ARCHIVO: *.".$rs_entregado->fields["TP"]." \");'  />");
      
       $img_entg=($status_file["STATUS"]=='TRUE')?("<IMG BORDER=0 SRC='".$img_path."accept.png'  ALT='Correcto' STYLE='cursor:pointer;' ONCLICK='alert(\"¡ LA INTEGRIDAD DEL ARCHIVO ES CORRECTA ! \\n\\n=============================\\n\\nDIGITALIZADO POR:  ".$rs_entregado->fields["USR"]." \\n\\nFECHA: ".$Fecha_upload." \\n\\nHORA: ".$Hora." hrs. \");' />"):("<IMG BORDER=0 SRC='".$img_path."exclamation.png'  ALT='Correcto' STYLE='cursor:pointer;' ONCLICK='alert(\"".$status_file["MSG"]."\\n\\n==========================================\\n\\nDIGITALIZADO POR:  ".$rs_entregado->fields["USR"]." \\n\\nFECHA: ".$Fecha_upload." \\n\\nHORA: ".$Hora." hrs. \");'  />");
     
       $action=($rs_entregado->fields["TP"]=='pdf')?('download_gpo_pdf.php'):('download_gpo.php?exit=true');
       
       $boton="<BUTTON STYLE='cursor:pointer;' CLASS='BTN_FILE' NAME='ver_doc[".$cont."]'  ONCLICK='pop_vista_doc(\"".$rs_entregado->fields["CAT_DOC"]."\",\"".$action."\")'>DOCUMENTO</BUTTON>"; 
       
       if($rs_docs->fields["SEL"]=='Y')
         $requeridos++;
               
     }
    
    if($rs_entregado->fields["ENTG"] == 'Y' && $rs_entregado->fields["STAT"] == 'PENDIENTE')
      {
       $img_type_file="<IMG  BORDER=0 SRC='".$img_path."icon_alert.gif'  ALT='editando' />
                       <FONT COLOR='ORANGE' SIZE='1'><B><I>ENTREGADO - PENDIENTE DE ADJUNTAR</I></B></FONT>
                       <IMG  BORDER=0 SRC='".$img_path."icon_alert.gif'  ALT='editando' />";
       $img_entg="";
       
       if($rs_docs->fields["SEL"]=='Y')
       $requeridos++;
      }
      
      $img_requerido=($rs_docs->fields["SEL"]=='Y')?("<IMG  BORDER=0 SRC='".$img_path."asterisk_orange.png'  ALT='editando'  />"):("");
          
	  //$Tipo_documento=get_tipo_doc($rs_entregado->fields["TIPO_DOC"]);
	  $Tipo_documento='';

            if($rs_docs->fields["ID"] > 0)
			$CONT_PLVD ++;

	  /**********CLASE DOC REQUERIDO******************/
      $CLASS_REQ = ($rs_docs->fields["OBLG"] == 'y' )?("REQ_FILE"):("");

      /**********************************************/
      
      /*************HTML******************/

		  $html.="<TR   BGCOLOR='$row_color' BGCOLOR='#FFFFFF'  
					   ONMOUSEOVER=\"javascript:this.style.backgroundColor='".$color_row."'; this.style.cursor='hand'; \" 
					   ONMOUSEOUT =\"javascript:this.style.backgroundColor='' \"
									   > ";
		  $html.="<TD COLSPAN='1' ALIGN='LEFT'   WIDTH='20px'>	<FONT COLOR='GRAY'  SIZE='2'><B> ".$cont_row."                  </B></FONT></TD>";              
		  $html.="<TD COLSPAN='1' ALIGN='LEFT'   WIDTH='250px'>	<FONT COLOR='BLACK' SIZE='2'><B> ".$rs_docs->fields["NMB"]." </B></FONT></TD>";
		  $html.="<TD COLSPAN='1' ALIGN='CENTER' WIDTH='200px'> ".$img_type_file."&nbsp;&nbsp;&nbsp;".$img_entg."	  		            </TD>";
		  $html.="<TD COLSPAN='1' ALIGN='CENTER'  WIDTH='50px'>                                      ".$boton."                         </TD>";
		  
		  $html.="<TD COLSPAN='1' ALIGN='CENTER' WIDTH='120px'>        ".$Tipo_documento." </TD>";

		  $html.="<TR>";

      
   $cont++;

   $rs_docs->MoveNext();
  }
  

//UPLOAD FILES POST
if(!empty($upload))
 $Tipo_documento        ='Solidario';
   

$html.="<CENTER>
       
        <INPUT TYPE=HIDDEN  NAME='Param1'                                         VALUE='".$Param1."'>
        <INPUT TYPE=HIDDEN  NAME='Param2'                                         VALUE='".$Param2."'>
        <INPUT TYPE=HIDDEN  NAME='ID_Proceso'                                     VALUE='".$ID_Proceso."'>
        <INPUT TYPE=HIDDEN  NAME='T_credit'                                       VALUE='".$T_credit."'>
        <INPUT TYPE=HIDDEN  NAME='tbl_docs'                                       VALUE='".$tbl_docs."'>
        <INPUT TYPE=HIDDEN  NAME='Nomina'                                         VALUE='".$Nomina."'>
        <INPUT TYPE=HIDDEN  NAME='SIN_MSG'                           			  VALUE='".$SIN_MSG."'>	        
        
        </CENTER>
        ";
$html.="</FORM>";
//FORM'S 

//POP IMG
$html.="<FORM Method='POST' ACTION=''                                  NAME='view_documents' >";
$html.="  <INPUT TYPE=HIDDEN NAME='Param1'                             VALUE='".$Param1."'>
          <INPUT TYPE=HIDDEN NAME='Param2'                             VALUE='".$Param2."'>
          <INPUT TYPE=HIDDEN NAME='ID_Documentos'                      VALUE=''>
          <INPUT TYPE=HIDDEN NAME='Tbl_docs'                           VALUE='".$tbl_docs."'>
          <INPUT TYPE=HIDDEN NAME='ID_Proceso'                         VALUE='".$ID_Proceso."'>
          ";
$html.="</FORM>";

echo $html;
?>
<!--JAVASCRIPT-->
