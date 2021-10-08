<?
/*
         ________________________________________________________
        |  Titulo: Adjuntar documentación del crédito           |
        |                                                       |
##      |  Autor : Enrique Godoy Calderón                       |
##      |          Tonathiú Cárdenas                            |
##      |  Fecha : Miércoles, 3 de octubre del 2008             |
##      |                                                       |
##      |  Descripción : Realiza un upload de la documentación  |
##      |                                                       |
##      |  Scripts relacionados: soli_docs.php, download.php    |
##      |                                                       |
##      |                                                       |
##      |  Lenguaje interpretado utilizado: php  y javascript   |
##      |                                                       |
##      |  Dependencias : soli_docs.php (sólo para la edición)  |
##      |                 download.php                          |
##      |                                                       |
##      |  Pendientes script:Documentación.                     |
##      |                                                       |
##      |  Tablas consultas "SELECT" SQL:                       |
##      |              cat_documentos_solicitudes,solicitud_vii |
##      |                   "DELETE"                            |
##      |              solicitud_vi                             |
##      | ----------------------------------------------------- |
##      |                                                       |
##      | Funciones PHP                                         |
##      |              reArrayFiles(&$file_post)                |
##      |                                                       |
##      |                                                       |
##      | Funciones javascript                                  |
##      |                                                       |
##      |                     popup(file,ruta,descdoc)          |
##      |                                                       |
##      | Recibe 5 variables vía Post: (soli_docs.php)          |
##      |                                                       |
##      |                     $Param1                           |
##      |                     $Param2                           |
##      |                     $Regimen                          |
##      |                     $Num_docs                         |
##      |                     $ID_soli                          |
##      |                                                       |
##       ¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯
######################################################
######################################################

*/
header("Cache-Control: no-cache, must-revalidate");
$exit=true;
require($DOCUMENT_ROOT."/rutas.php");
//Inicio conexión
$db = ADONewConnection(SERVIDOR);  # create a connection
$db->Connect(IP,USER,PASSWORD,NUCLEO);
//Fin Conexión
//verflujo();
//die();



?>

<script type="text/javascript">
function popup(var1,var2,index)
{
  window.open('downloadx.php?Param1='+var1+'&Param2='+var2+'&Cont='+index, 'vista_doc', 'menubar=0,resizable=1,location=0,directories=0,scrollbars=1, width=640,height=400');
  //alert(ruta);
  return true;
}
</script>

<STYLE>
hr{
width:85%;
height:0px;/*solo queremos borde*/
text-align:left;
border-top:0px;/*quita el grosor extra de Opera y FFox*/
border-bottom:navy dashed 1px;
}
</STYLE>
<?php

//Organizamos los atributos del File (name,type,size)
function reArrayFiles(&$file_post)
{
    $file_arry = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_arry[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_arry;
}

?>
<?php
$zql = "SELECT ID_Solicitud,Status,ID_Tipocredito,ID_Producto,Folio,CONCAT(Nombre,' ',NombreI,' ',Ap_paterno,' ',Ap_materno),Renovacion_credit,rfc
		   FROM solicitud
		   WHERE $Param1 = '$Param2' ";
$rz = $db->Execute($zql);
$ID_soli=$rz->fields[0];
$Regimen=$rz->fields[1];
$Tcredito=$rz->fields[2];
$docs_upoload=($Tcredito=='3' )?($docs_jpg):($docs_GS_jpg);
$docs_upoload=($Tcredito=='1' )?($docs_indiv_jpg):($docs_upoload);
$Prod_soli=$rz->fields[3];
$Renovacion=$rz->fields[6];
$rfc_soli=$rz->fields[7];
//debug($docs_upoload);
//die();
//debug("1");
if ($_FILES['document'])
{
//debug("2");
    $file_arry = reArrayFiles($_FILES['document']);
    $cont=0;
    $err=0;
    foreach ($file_arry as $file)
    {

      if($file['name'] <> '')
      {
        if ($file["error"] > 0)
        {
          $Msg.= "El tamaño del archivo (#$cont) , es superior a los 5 MB <BR> ";
          $err++;
        }
        else
        {
		  //echo "File Name: " . $file['name'] . "         \n ";
		  //echo "File Type: " . $file['type'] . "   	\n";
		  //echo "File Size: " . $file['size'] . "   	\n";
		  //echo "Temp Name: " . $file['tmp_name'] . "   	\n";
 	        if  ($file['size'] > 4000000 )
		{
		$err++;
		$Msg .= "El tamaño del archivo (#$err ) que intentó intoducir, es muy grande. (".number_format($file['size'],0)."Bytes) <BR>";


		}
		else
		{

//debug("3");

		   if  (($file['type'] != 'image/jpeg')  and ($file['type'] != 'image/jpg') and ($file['type'] != 'image/pjpeg' )
		          and ($file['type'] != 'application/octet-stream' ) and ($file['type'] != 'application/pdf' ))

                   {
                     $err++;
		     $Msg .= "Solamente se aceptan archivos con formato JPG/JPEG/pjpeg/TIF/TIFF  No se actualizó el documento # $cont <BR>";

		   }
		   else
		   {
//debug("4");
		           //Select para conocer si el documento se actualiza ó es la primera inserción
		           //$sql="SELECT COUNT(*) FROM solicitud_vii WHERE ID_solicitud = '$ID_soli' and ID_Documentos = '$cont'";
		           $sql="SELECT COUNT(*) FROM solicitud_vii WHERE ID_solicitud = '$ID_soli' and ID_Documentos = '".$ref_document[$cont]."'";
//debug($sql);
		           //die();
		           $rs = $db->Execute($sql);
                           $num_count = $rs->fields[0];

		           if($num_count == 0)
		           {
		              //echo  $docs_upoload. $file['name'];
			     //Insertar documento nuevo

			     if (@copy($file['tmp_name'], $docs_upoload . $file['name']))
		 	        {
//debug("5");

		 	                //Procesar imágenes
		 	              if($file['type'] != 'application/pdf')
		 	              {


					chmod($docs_upoload . $file['name'],0777);
					 $Doc=$docs_upoload . $file['name'];
					 $fecha_upload  =  date("y-m-d h:i:s");
					 
					 
					 

$Query = "SELECT	cat_documentos.ID_Documento 
					FROM		cat_documentos,             
									cat_documentos_tipo         
					WHERE		cat_documentos.ID_Documento           = cat_documentos_tipo.ID_Documento 
					AND			cat_documentos_tipo.ID_Documento_Tipo = '".$ref_document[$cont]."' ";		 
$rsIN = $db->Execute($Query); 
if( $rsIN->fields[0] > 0 ) { 
	$Query = "SELECT	solicitud_vii.ID_Doc            
						FROM		solicitud_vii, cat_documentos, 
										cat_documentos_tipo            
						WHERE		solicitud_vii.ID_Documentos = cat_documentos_tipo.ID_Documento_Tipo 
						AND			cat_documentos.ID_Documento = cat_documentos_tipo.ID_Documento      
						AND			solicitud_vii.ID_Solicitud  = '".$ID_soli."'                        
						AND			cat_documentos.ID_Documento = '".$rsIN->fields[0]."' ";				 
	$rsIN = $db->Execute($Query); 
	if( $rsIN->fields[0] > 0 ) { 
		$Query= "DELETE FROM solicitud_vii WHERE ID_Doc =  '".$rsIN->fields[0]."' ";
		$db->Execute($Query); 
	}
}

					 
					 
					 $sql = "INSERT INTO solicitud_vii (ID_Solicitud,ID_Documentos,Entregado,Descripcion,Fecha_upload,Tipo,ID_Tipocredito) VALUES ('$ID_soli','".$ref_document[$cont]."','Y','$NOM_USR','$fecha_upload','jpg','$Tcredito')";

					 $rs=$db->Execute($sql);
					 $id_doc = $db->_insertid();
					 //Hash sobre el nombre de la imágen y su ID para identificación
					 $docimg = (md5('doc'.$id_doc)).".jpg";
					 rename($docs_upoload . $file['name'],$docs_upoload .$docimg);
					 chmod($docs_upoload .$docimg,0777);

					 //************* hash a la imágen *******
					$f1= fopen($docs_upoload .$docimg,"rb");
					//leemos el fichero completo limitando la lectura al tamaño de fichero
					$Doc_hash = fread($f1, $file['size']);
					//anteponemos \ a las comillas que pudiera contener el fichero para evitar que sean interpretadas como final de cadena

					$Doc_hash=addslashes($Doc_hash);
					//echo"Variable - $Doc_hash - <BR> <BR>";
					$Doc_hash=md5($Doc_hash);
					//echo"Variable - $Doc_hash - <BR> <BR>";
					//*************fin hash

					$sql = "UPDATE solicitud_vii SET Documento = '$Doc_hash' WHERE ID_Doc = '$id_doc'";
					//$sql = "UPDATE solicitud_vii SET Documento = '$Doc_hash' WHERE ID_Doc = '$id_doc'";
					$rs=$db->Execute($sql);


					$sql_cons ="SELECT CONCAT(Nombre,' ',AP_Paterno,' ',AP_Materno) FROM usuarios WHERE ID_User= '$ID_USR'";
					$rs_cons = $db->Execute($sql_cons);

                                         $fecha_sol  =  date("y-m-d h:i:s");

					/***QUERY*****/
					//$sql_insert ="INSERT INTO solicitud_sucesos (ID_Solicitud,Fecha,Atendio,Status,Suceso) VALUES('$ID_soli','$fecha_sol','".$rs_cons->fields[0]."','Digitalizar-docs','DIGITALIZACIÓN DE DOCUMENTOS ')";
					//debug($sql_insert);
					//die();
					//$rs_cons = $db->Execute($sql_insert);
					/***FIN QUERY*****/

				     }

				     //Procesar pdf
				     if($file['type'] == 'application/pdf')
		 	              {

				        //debug($file['name']);
				        /* chmod($docs_upoload . $file['name'],0777);
					 $Doc=$docs_upoload . $file['name'];*/

					$fecha_upload  =  date("y-m-d h:i:s");
					$sql = "INSERT INTO solicitud_vii (ID_Solicitud,ID_Documentos,Entregado,Descripcion,Fecha_upload,Tipo,ID_Tipocredito) VALUES ('$ID_soli','".$ref_document[$cont]."','Y','$NOM_USR','$fecha_upload','pdf','$Tcredito')";
					//debug($sql);
					$rs=$db->Execute($sql);
					$id_doc = $db->_insertid();


					 //Hash sobre el nombre de la imágen y su ID para identificación
					$docimg = (md5('doc'.$id_doc)).".pdf";
					 rename($docs_upoload . $file['name'],$docs_upoload .$docimg);
					 chmod($docs_upoload .$docimg,0777);

					 //************* hash a la imágen *******
					$f1= fopen($docs_upoload .$docimg,"rb");
					//leemos el fichero completo limitando la lectura al tamaño de fichero
					$Doc_hash = fread($f1, $file['size']);
					//anteponemos \ a las comillas que pudiera contener el fichero para evitar que sean interpretadas como final de cadena

					$Doc_hash=addslashes($Doc_hash);
					//echo"Variable - $Doc_hash - <BR> <BR>";
					$Doc_hash=md5($Doc_hash);
					//echo"Variable - $Doc_hash - <BR> <BR>";
					//*************fin hash

					$sql = "UPDATE solicitud_vii SET Documento = '$Doc_hash', Verificado ='N' WHERE ID_Doc = '$id_doc'";
					//$sql = "UPDATE solicitud_vii SET Documento = '$Doc_hash' WHERE ID_Doc = '$id_doc'";
					//debug($sql);
					$rs=$db->Execute($sql);

					$sql_cons ="SELECT CONCAT(Nombre,' ',AP_Paterno,' ',AP_Materno) FROM usuarios WHERE ID_User= '$ID_USR'";
					$rs_cons = $db->Execute($sql_cons);




					$sql_cons ="SELECT CONCAT(Nombre,' ',AP_Paterno,' ',AP_Materno) FROM usuarios WHERE ID_User= '$ID_USR'";
					$rs_cons = $db->Execute($sql_cons);

                                         $fecha_sol  =  date("y-m-d h:i:s");

					/***QUERY*****/
					//$sql_insert ="INSERT INTO solicitud_sucesos (ID_Solicitud,Fecha,Atendio,Status,Suceso) VALUES('$ID_soli','$fecha_sol','".$rs_cons->fields[0]."','Digitalizar-docs','DIGITALIZACIÓN DE DOCUMENTOS ')";
					//debug($sql_insert);
					//die();
					//$rs_cons = $db->Execute($sql_insert);
					/***FIN QUERY*****/

				     }
			       }
			   }
			   else
			   {
			    //Actualización de documento
			     if (@copy($file['tmp_name'], $docs_upoload . $file['name']))
				{
				   chmod($docs_upoload . $file['name'],0777);
				   $sql_entregado = "SELECT ID_Doc,Tipo FROM solicitud_vii WHERE ID_Solicitud = '$ID_soli' AND ID_Documentos = '".$ref_document[$cont]."' ";
                                   $rs_entregado=$db->Execute($sql_entregado);
                                   $id_doc=$rs_entregado->fields[0];

                                   $Tipo=($file['type'] != 'application/pdf')?('jpg'):('pdf');

                                   //unlink($docimg_update);
                                   if($Tipo == $rs_entregado->fields[1])
                                   {
                                    $docimg_update = (md5('doc'.$rs_entregado->fields[0])).".$Tipo";
                                    rename($docs_upoload . $file['name'],$docs_upoload .$docimg_update);
                                   }
                                   else
                                   {
                                    $docimg_update = (md5('doc'.$rs_entregado->fields[0])).".".$rs_entregado->fields[1]."";
                                    chmod($docs_upoload .$docimg_update ,0777);
                                    $Ruta_delete =$docs_upoload .$docimg_update;
                                    unlink($Ruta_delete);
                                    $docimg_update = (md5('doc'.$rs_entregado->fields[0])).".$Tipo";
                                    rename($docs_upoload . $file['name'],$docs_upoload .$docimg_update);
                                   }

                                   chmod($docs_upoload .$docimg_update,0777);

				//************* hash a la imágen *******
				$f1= fopen($docs_upoload .$docimg_update,"rb");
				//leemos el fichero completo limitando la lectura al tamaño de fichero
				$Doc_hash = fread($f1, $file['size']);
				//anteponemos \ a las comillas que pudiera contener el fichero para evitar que sean interpretadas como final de cadena

				$Doc_hash=addslashes($Doc_hash);
				//echo"Variable - $Doc_hash - <BR> <BR>";
				$Doc_hash=md5($Doc_hash);
				//echo"Variable - $Doc_hash - <BR> <BR>";
				//*************fin hash
				$fecha_upload  =  date("y-m-d h:i:s");
				$sql = "UPDATE solicitud_vii SET Documento = '$Doc_hash',Descripcion='$NOM_USR',Fecha_upload='$fecha_upload',Tipo = '$Tipo' , Verificado ='N' WHERE ID_Doc = '$id_doc'";
				$rs=$db->Execute($sql);

				$sql_cons ="SELECT CONCAT(Nombre,' ',AP_Paterno,' ',AP_Materno) FROM usuarios WHERE ID_User= '$ID_USR'";
				$rs_cons = $db->Execute($sql_cons);

				$sql_cons ="SELECT CONCAT(Nombre,' ',AP_Paterno,' ',AP_Materno) FROM usuarios WHERE ID_User= '$ID_USR'";
				$rs_cons = $db->Execute($sql_cons);

                               $fecha_sol  =  date("y-m-d h:i:s");




				/***QUERY*****/
				//$sql_insert ="INSERT INTO solicitud_sucesos (ID_Solicitud,Fecha,Atendio,Status,Suceso) VALUES('$ID_soli','$fecha_sol','".$rs_cons->fields[0]."','Digitalizar-docs','DIGITALIZACIÓN DE DOCUMENTOS ')";
				//debug($sql_insert);
				//die();
				//$rs_cons = $db->Execute($sql_insert);
				/***FIN QUERY*****/

				}
  		            }//Fin else

		   }//FIN else  if  (($file['type'] != 'image/jpeg')

		}//Fin else if  ($file['size'] > 3500000 )

       }//FIN else if ($file["error"] > 0)

     }//Fin if($file['name'] <> '')

      $cont++;
  }//Fin FOR

     if($err)
	error_msg($Msg);
}//Fin if ($_FILES['document'])



?>

<HTML>
<HEAD>
<link href=".\opcion.css" rel="stylesheet" type="text/css">
<TITLE>Documentación de la solicitud</TITLE>
</HEAD>
<BODY BGCOLOR="skyblue" TEXT="#000000" LINK="#FF0000" VLINK="#800000" ALINK="#FF00FF" BACKGROUND="?">
<?



echo "<TABLE ALIGN='center' class='main' CELLSPACING=0 CELLPADDING=1 WIDTH='50%' STYLE='border:3px dotted #6699cc;' >
	 <TR><TD>
	 <TABLE BORDER=0 CELLSPACING=3 CELLPADDING=1 WIDTH='100%' BGCOLOR='white' ID='small' >
	 <TR ALIGN='left' VALIGN='middle' >
	 <TH ALIGN='center'  COLSPAN='2'><U><FONT COLOR='black'></FONT></U> </TH>
	 </TR>";




	echo"<TR ALIGN='left' VALIGN='middle'>

	 <TD ALIGN='center' >
          <B><font size='4'>DOCUMENTACIÓN DE LA SOLICITUD <BR> FOLIO: ".$ID_soli." </font></B>


	 </TD>
	</TR> ";

	echo"</TABLE>
	</TR>
	</TD>
	</TABLE>";


echo"<CENTER><H2><IMG  BORDER=0 SRC='".$img_path."user_blue.png'  ALT='editando'/>&nbsp;&nbsp;<U>".$rz->fields[5]."</U></H2></CENTER>";

echo"<BR><BR>";



 echo "<FORM Method='POST' ACTION='".$PHP_SELF."' NAME='soli_documentos_dos' >\n";
 echo"<TABLE width='90%' STYLE='border:3px dotted #6699cc;' align='center' CELLPADDING='10' CELLSPACING='0' ID='medium'>";
//---------------------------------------------------------------------------------------------------
// Documentos entregados
//---------------------------------------------------------------------------------------------------
echo"<TD>";

echo"<TABLE WIDTH='100%' BORDER='0' CELLPADDING='5' CELLSPACING='3'>
    ";
echo" <TR BGcolor='#6699cc'  ID='small'>
         <TD colspan='1' align='center'>&nbsp;</TD>
         <TD colspan='1' align='center'><strong><font color='white' ><U>Tipo</U></font></strong></TD>
         <TD colspan='1' align='center'><strong><font color='white' ><U>Documento</U></font></strong></TD>
         <TD colspan='1' align='center'><strong><font color='white' ><U>Entregado</U></font></strong></TD>
         <TD colspan='1' align='center'><strong><font color='white' ><U>Tamaño</U></font></strong></TD>
         <TD colspan='1' align='center'><strong><font color='white' ><U>Último acceso</U></font></strong></TD>
         <TD colspan='1' align='center'><strong><font color='white' ><U>Ver documento</U></font></strong></TD>
         <TD colspan='1' align='center'><strong><font color='white' ><U>Status Doc. </U></font></strong></TD>
         <TD colspan='1' align='center'><strong><font color='white' ><U>Tipo Doc. </U></font></strong></TD>
      </TR>";

/*$sql = "SELECT
cat_documentos_solicitudes.ID_Documentos,
cat_documentos_solicitudes.Documento,
documento_credito.Seleccionado,
cat_tipo_credito.Descripcion
FROM cat_documentos_solicitudes
LEFT JOIN documento_credito ON documento_credito.ID_Documentos = cat_documentos_solicitudes.ID_Documentos
LEFT JOIN cat_tipo_credito ON cat_documentos_solicitudes.ID_Tipocredito = cat_tipo_credito.ID_Tipocredito
WHERE cat_tipo_credito.ID_Tipocredito ='$Tcredito' ";*/


$Query = "SELECT			perfilador_dtl.valor 
					FROM				perfilador_dtl       
					INNER JOIN	perfilador ON perfilador.ID_Perfilador = perfilador_dtl.id_perfilador 
					INNER JOIN	solicitud  ON solicitud.ID_Perfilador  = perfilador.ID_Perfilador AND solicitud.ID_Solicitud = '".$Param2."' 
					WHERE				perfilador_dtl.nombre = 'Ocupación' "; 
//$rs = $db->Execute($Query); 

$Query = "SELECT	ID_Tipocredito
					FROM		solicitud
					WHERE		ID_Solicitud = '".$Param2."' ";
$rs = $db->Execute($Query);
$ID_Tipocredito = $rs->fields["ID_Tipocredito"]; 

$sql = "SELECT				cat_documentos_tipo.ID_Documento_Tipo,  
											cat_documentos_tipo.Descripcion,        
											cat_documentos.Descripcion,             
											if(documento_credito.id_documentos IS NULL, '', 'REQUERIDO' ) AS REQUERIDO, 
											MAX(solicitud_vii.ID_Documentos)
					FROM				( cat_documentos,     
											cat_documentos_tipo ) 
					LEFT JOIN		documento_credito ON ( documento_credito.id_documentos = cat_documentos.ID_Documento AND  documento_credito.ID_Tipocredito ='".$ID_Tipocredito."' ) 
					LEFT JOIN solicitud_vii ON solicitud_vii.ID_Solicitud = '".$Param2."' AND solicitud_vii.ID_Documentos = cat_documentos_tipo.ID_Documento_Tipo
					WHERE				cat_documentos.ID_Documento = cat_documentos_tipo.ID_Documento 
					AND documento_credito.ID_Tipocredito > 0 
					GROUP BY		cat_documentos.ID_Documento ";    
//verflujo();
//debug($sql);
$sqlc="SELECT DISTINCT
`cat_documentos_solicitudes`.`ID_Documentos`,
`cat_documentos_solicitudes`.`Documento`,
`documento_credito`.`Seleccionado`,
`cat_tipo_credito`.`ID_Tipocredito`
FROM
`cat_documentos_solicitudes`
Inner Join `cat_tipo_credito` ON `cat_tipo_credito`.`ID_Tipocredito` = `cat_documentos_solicitudes`.`ID_Tipocredito`
Left Join `documento_credito` ON  `cat_documentos_solicitudes`.`ID_Tipocredito` = `documento_credito`.`ID_Tipocredito`
AND `documento_credito`.`ID_Documentos` = `cat_documentos_solicitudes`.`ID_Documentos`
WHERE `cat_tipo_credito`.`ID_Tipocredito` = '$Tcredito'";
//debug($sql);
$rs=$db->Execute($sql);
$cont=1;
$entregados=0;
$requeridos=0;
$requeridos2 = 0; 
while(! $rs->EOF )
  {
		$requeridos2++;
							$QueryIN = "SELECT	ID_Documento_Tipo,  
																	Descripcion         
													FROM		cat_documentos_tipo 
													WHERE		ID_Documento_Tipo = '".$rs->fields[4]."' ";    
							$rsIN = $db->Execute($QueryIN); 


              $Alarm='False';
              //Verificar si el documento fué entregado
              $sql_entregado = "SELECT Entregado,ID_Doc,Descripcion,Fecha_upload,Tipo,Verificado,ID_Documentos FROM solicitud_vii WHERE $Param1 = $Param2 AND ID_Documentos = '".$rsIN->fields[0]."' ";
             //debug($sql_entregado);
              $rs_entregado=$db->Execute($sql_entregado);

              $color_entregado=($rs_entregado->fields[0] == 'Y')?("BGcolor='#FFFFFF' "):("BGcolor='#FFFFFF'");

              echo"<TR $color_entregado ID='small'  onmouseover=\"javascript:this.style.backgroundColor='yellow'; this.style.cursor='hand'; \" onmouseout=\"javascript:this.style.backgroundColor='' \" BGCOLOR='white'> ";
              echo"<TD colspan='1' align='left' ID='S2' >$cont)</td>";
              echo"<TD colspan='1' align='left' ID='S2' >".$rs->fields[2]."</td>";
							
							
														

              echo"<TD colspan='1' align='left' ID='S2' >".$rsIN->fields[1]."</td>";


             $enbl=($rs_entregado->fields[0] != 'Y')?("DISABLED"):("");
             $check_verif=($rs_entregado->fields[5] == 'Y')?("Ok"):("---");
             $requerid_verif=($rs->fields[2]=='Y')?("<IMG  BORDER=0 SRC='".$img_path."required_asterix.gif'  ALT='editando'  />"):("");

            if($rs->fields[2]=='Y')
            {
            $requeridos=$requeridos+1;
            }

            if($rs_entregado->fields[0]=='Y' and $rs->fields[2]=='Y')
            {
            $entregados=$entregados+1;

            }

             $Ruta_temp = $docs_upoload . (md5('doc'.$rs_entregado->fields[1])).".".$rs_entregado->fields[4]."";
             /*$Ruta= (md5('doc'.$rs_entregado->fields[1])).".jpg";
             $Ruta_temp = $docs_upoload . (md5('doc'.$rs_entregado->fields[1])).".jpg";
             $Ruta_temp_pdf = $docs_upoload . (md5('doc'.$rs_entregado->fields[1])).".pdf";

             $Pdf_file = (!file_exists($Ruta_temp_pdf))?('False'):('True');*/
             //echo $Ruta_temp;

		if((!file_exists($Ruta_temp)) and ($rs_entregado->fields[0] =='Y') )
		{

		$sql_update ="UPDATE solicitud_vii SET Verificado = 'N'   WHERE $Param1 = '$Param2' AND ID_Documentos = '".$rsIN->fields[0]."' ";
		$db->Execute($sql_update);

		//$sql_update = "UPDATE solicitud  SET Verificada = 'N' WHERE $Param1 = '$Param2' ";
		//debug($sql_update);
		//$db->Execute($sql_update);

		$check_verif="---";

		}


             if (!file_exists($Ruta_temp))
                {

       	   echo"<TD colspan='1' align='center'><strong><font color='#000099' > No  </font></strong></TD>";
       	   echo"<TD colspan='1' align='center'><strong><font color='#000099' > --- ---  </font></strong></TD>";
       	   echo"<TD colspan='1' align='center'><strong><font color='#000099' > --- ---  </font></strong></TD>";


                  //$sql_delete = "DELETE FROM solicitud_vii WHERE $Param1 = $Param2 AND ID_Documentos = $cont ";
       	   //debug($sql_delete);
       	   //$rs_delete=$db->Execute($sql_delete);

             	   echo"<TD colspan='1' align='center' ID='S2'><INPUT TYPE='BUTTON'  NAME='ver_doc[".$cont."]' VALUE='Ver documento' DISABLED></td>";

                  if( $rs_entregado->fields[0] =='Y'){echo"<TD colspan='1' align='center'><strong><font color='#FF0000' >¡Eliminado!</font></TD>";}       else {echo"<TD colspan='1' align='center'><strong><font color='#000099' ></font></TD>";}

        echo"<TD colspan='1' align='center' ID='S2'></td>";
//		echo"<TD colspan='1' align='center' ID='S2'><font color='#000099' >$requerid_verif</font></td>";



                }
                else
                {

       		//Verificamos que el documento no este alterado
       		//************* hash a la imágen *******
       		$f1= fopen($Ruta_temp,"rb");
       		//leemos el fichero completo limitando la lectura al tamaño de fichero
       		$Doc_hash = fread($f1, filesize($Ruta_temp));
       		//anteponemos \ a las comillas que pudiera contener el fichero para evitar que sean interpretadas como final de cadena

       		$Doc_hash=addslashes($Doc_hash);
       		//echo"Variable - $Doc_hash - <BR> <BR>";
       		$Doc_hash=md5($Doc_hash);
       		//echo"Variable - $Doc_hash - <BR> <BR>";
       		//*************fin hash
       		//Verificar que el documento no este alterado
       		$zql = "SELECT Documento FROM solicitud_vii WHERE $Param1 = $Param2 AND ID_Documentos = '".$rsIN->fields[0]."' ";
       		$rs_zql=$db->Execute($zql);



       		$label_entregado=($rs_entregado->fields[0] == 'Y')?("Si"):("No");


	if( $rs->fields["REQUERIDO"] == "REQUERIDO" && $rs_entregado->fields[0] == 'Y' )  {
		//$requeridos2++;
	}

       		echo"<TD colspan='1' align='center'><strong><font color='#000099' > $label_entregado </font></strong></TD>";

                $file_size=filesize($Ruta_temp);
                $file_date=date("d/m/Y",fileatime($Ruta_temp));



		echo"<TD colspan='1' align='center'><strong><font color='#000099' > $file_size bytes </font></strong></TD>";
		echo"<TD colspan='1' align='center'><strong><font color='#000099' > $file_date </font></strong></TD>";

                      if ($rs_entregado->fields[4] == 'jpg')
                      {
       		 echo"<TD colspan='1' align='center' ID='S2'  ><INPUT TYPE='BUTTON' STYLE='cursor:pointer; ' NAME='ver_doc[".$cont."]' VALUE='Ver documento' ".$enbl." onclick='popup(\"$Param1\",\"$Param2\",\"".$rsIN->fields[0]."\")'></td>";




                      }
                      else //es pdf
                      {


                      echo"</FORM>";
                      echo "<FORM METHOD=POST NAME='Gen_poliza_factura' ACTION='download_pdf.php' >";
       	       echo "<INPUT  TYPE=hidden  NAME='Param1'         VALUE='$Param1'>";
       	       echo "<INPUT  TYPE=hidden  NAME='Param2'         VALUE='$Param2'>";
       	       echo "<INPUT  TYPE=hidden  NAME='Cont'           VALUE='".$rsIN->fields[0]."'>";
       	       echo "<TD colspan='1' align='center' ID='S2'><INPUT TYPE='SUBMIT' STYLE='cursor:pointer' NAME='ver_doc[".$cont."]' VALUE='Ver documento' ".$enbl." onclick=''></td>";
       	       echo"</FORM>";

                      }


		if($rs_zql->fields[0] != $Doc_hash)
		{

		$sql_update ="UPDATE solicitud_vii SET Verificado = 'N'   WHERE $Param1 = $Param2 AND ID_Documentos = '".$rsIN->fields[0]."' ";
		$db->Execute($sql_update);

		$sql_update = "UPDATE solicitud  SET Verificar = 'NO' WHERE $Param1 = '$Param2' ";
		//debug($sql_update);
		$db->Execute($sql_update);



		}






		echo"<TD colspan='1' align='center'><strong><font color='#000099' >&nbsp;&nbsp;&nbsp;";


		if($rs_zql->fields[0] != $Doc_hash){ 
			echo"<font color='#FF0000' >Modificado";}else{echo"<font color='#000099' >Correcto";

$Num_docs2++;
		}


		echo"</font></TD>";
		//debug($rs->fields[2]);

       if ($rs_entregado->fields[4] == 'jpg')
        {
        echo"<TD colspan='1' align='center' ID='S2'><IMG  BORDER=0 SRC='".$img_path."picture.png'  ALT='editando'   /></td>";
        }
        else
        {
        echo"<TD colspan='1' align='center' ID='S2'><IMG  BORDER=0 SRC='".$img_path."file.png'  ALT='editando'   /></td>";
        }


//		echo"<TD colspan='1' align='center' ID='S2'><font color='#000099' >$requerid_verif</font></td>";

		$entregado++;
               }




              echo"</TR>";
              $cont++;

              $i++;
              $rs->MoveNext();
       $rs_entregado->MoveNext();

  }//Fin while

//Actualizar Status
//Seleccionar la lista de docuemntos



$sql = "SELECT   COUNT(*)
FROM cat_documentos_solicitudes
LEFT JOIN documento_credito ON documento_credito.ID_Documentos = cat_documentos_solicitudes.ID_Documentos
LEFT JOIN cat_tipo_credito ON cat_documentos_solicitudes.ID_Tipocredito = cat_tipo_credito.ID_Tipocredito
WHERE cat_tipo_credito.ID_Tipocredito ='$Tcredito'";


$Query = "SELECT	ID_Tipocredito
					FROM		solicitud
					WHERE		ID_Solicitud = '".$Param2."' ";
$rs = $db->Execute($Query);
$ID_Tipocredito = $rs->fields["ID_Tipocredito"]; 

$sql = "SELECT				COUNT(cat_documentos_tipo.ID_Documento_Tipo)
					FROM				cat_documentos_tipo                       
					INNER JOIN	cat_documentos ON cat_documentos.ID_Documento = cat_documentos_tipo.ID_Documento 
					LEFT JOIN documento_credito ON  documento_credito.id_documentos = cat_documentos.ID_Documento
					WHERE				  documento_credito.ID_Tipocredito ='".$ID_Tipocredito."'
					GROUP BY cat_documentos.ID_Documento";    

$rs=$db->Execute($sql);
//debug($sql);
//$Num_docs=$rs->fields[0];

if( $ID_USR == 1 ) { 
	//debug($Num_docs2); 
	//debug($requeridos2); 
}
if( $requeridos2 == $Num_docs2 ) { 
	
	$Query = "SELECT	COUNT(*)          
						FROM		solicitud_sucesos 
						WHERE		ID_Solicitud = '".$ID_soli."'               
						AND			Suceso       = 'DIGITALIZACION COMPLETA' "; 
	$rs = $db->Execute($Query); 
	if( $rs->fields[0] == 0 ) { 
		
		$Query = "UPDATE	solicitud                           
							SET			Status_solicitud = 'DIGITALIZACION' 
							WHERE		ID_Solicitud = '".$ID_soli."' ";    
		$db->Execute($Query); 
		
		$Query ="INSERT INTO solicitud_sucesos (ID_Solicitud,Fecha,Atendio,Status,Suceso) VALUES('".$ID_soli."','$fecha_sol','".$_SESSION["NOM_USR"]."','Digitalizar Documentos','DIGITALIZACION COMPLETA')";	
		$db->Execute($Query); 
	}
} 

//echo"Entregado $entregado";
//echo"Num docs $Num_docs";
/*if(($Num_docs == $entregado) and ($Num_docs > 0))
{
$sql = "UPDATE solicitud SET Status = 'Completa' WHERE $Param1 = '$Param2'";
//debug($sql);
//die();
$rs=$db->Execute($sql);
}
else if($entregado > 0)
{
$sql = "UPDATE solicitud SET Status = 'Incompleta' ,Verificar = 'NO' WHERE $Param1 = '$Param2'";
//debug($sql);
//die();
$rs=$db->Execute($sql);
}
else if($entregado == 0)
{
$sql = "UPDATE solicitud SET Status = 'Capturada',Verificar = 'NO' WHERE $Param1 = '$Param2'";
//debug($sql);
//die();
$rs=$db->Execute($sql);
}

$sql = "SELECT Status FROM solicitud WHERE $Param1 = $Param2";
$rs=$db->Execute($sql);
$Status_soli=$rs->fields[0];


echo"<script>opener.location.reload();</script>";*/

/*
$sql = "SELECT ID_grupo_soli FROM solicitud WHERE $Param1 = '$Param2'";
//debug($sql);
//die();
$rs=$db->Execute($sql);
$gpo_soli=$rs->fields[0];

//debug($requeridos);
//debug($entregados);

if($requeridos==$entregados)
{
$sql = "UPDATE solicitud SET Verificar_docs = 'Y' WHERE $Param1 = '$Param2'  ";
//debug($sql);
//die();
$rs=$db->Execute($sql);
}
else
{
$sql = "UPDATE solicitud SET Verificar_docs = 'N' WHERE $Param1 = '$Param2'  ";
//debug($sql);
//die();
$rs=$db->Execute($sql);


}




		$sql_cons_docs="SELECT Verificar_docs FROM solicitud  WHERE $Param1 = '$Param2'";
		$rs_cons_docs=$db->Execute($sql_cons_docs);

		$sql_cons_dom="SELECT Inv_domiciliaria FROM solicitud  WHERE $Param1 = '$Param2' ";
		$rs_cons_dom=$db->Execute($sql_cons_dom);

		$sql_cons_tel="SELECT Inv_telefonica FROM solicitud  WHERE $Param1 = '$Param2' ";
		$rs_cons_tel=$db->Execute($sql_cons_tel);

		$sql_tcredito="SELECT ID_Tipocredito FROM solicitud  WHERE $Param1 = '$Param2' ";
		$rs_tcredito=$db->Execute($sql_tcredito);

		$sql_status="SELECT Status FROM solicitud  WHERE $Param1 = '$Param2' ";
		$rs_status=$db->Execute($sql_status);



//debug($rs_status->fields[0]);
//die();
if(($rs_status->fields[0]!='Alta cliente') && ($rs_status->fields[0]!='Alta credito') && (empty($is_consulta)) )
{
	  if($rs_tcredito->fields[0]=='1')
	  {

				if(($rs_cons_dom->fields[0]=='Y') && ($rs_cons_tel->fields[0]=='Y'))
				{
					  //$sql="UPDATE solicitud SET Status = 'Completa' WHERE ID_solicitud = '$ID_soli' ";
					   $rs=$db->Execute($sql);
				}
			   else
			   {
				 //$sql="UPDATE solicitud SET Status = 'Incompleta' WHERE ID_solicitud = '$ID_soli' ";
				 $rs=$db->Execute($sql);
			   }
	  }
	  else
	  {

			//$sql="UPDATE solicitud SET Status = 'Completa-Docs' WHERE ID_solicitud = '$ID_soli' ";
			$rs=$db->Execute($sql);

	  }


	if($rs_cons_docs->fields[0]=='N')
		  {
				  //$sql="UPDATE solicitud SET Status = 'Pendiente - Docs.' WHERE $Param1 = '$Param2' ";
				  $rs=$db->Execute($sql);

		  }

}
*/
if(empty($Sin_reload))
echo"<script>opener.location.reload();</script>";


//$msg_stat_docs=($rs_cons_docs->fields[0]=='N')?("Existen documentos requeridos sin digitalizar"):("Documentos requeridos completos");

echo"<TR BGcolor='#6699cc'  ID='medium'>
         <TD colspan='9' Align='Center'><FONT COLOR='white' name='Times New Roman' size='3' >$msg_stat_docs</font></tH>
     </TR>";


echo"</TABLE>";
echo"</TD>";
echo"</TABLE>";


if($rs_cons_docs->fields[0]=='N')
{
 echo"<SCRIPT>alert('Existen documentos requeridos sin digitalizar');</SCRIPT>";
}

echo "<BR>
      <BR>
      <BR>
      <BR>
      <CENTER><INPUT TYPE='BUTTON' VALUE='Cerrar'  onClick='window.close();' ></CENTER>
      </FORM>";
?>
</BODY>
</HTML>
