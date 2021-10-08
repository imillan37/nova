<?
 $noheader=1;
 require($DOCUMENT_ROOT."/rutas.php");
 //Inicio conexión
  $db = ADONewConnection(SERVIDOR);  # create a connection
  $db->Connect(IP,USER,PASSWORD,NUCLEO);
 //Fin Conexión
?>

<?php
/************************SQL***************************************/
 $sql_entregado = "SELECT Entregado,
                          ID_Doc 				AS IDDOC,
                          Descripcion    		AS DSC,
                          Fecha_upload   		AS FECH,
                          ID_Tipocredito 		AS TCREDIT,
                          Tipo           		AS TIPDOC
                    FROM ".$Tbl_docs." 
                    WHERE ".$Param1."  = '".$Param2."' 
                      AND ID_Documento = '".$ID_Documentos."' ";
 $rs_entregado=$db->Execute($sql_entregado);



 $sql = "SELECT Documento AS DOC
          FROM cat_documentos_solicitudes 
          WHERE ID_Documentos = '".$ID_Documentos."' ";
 $rs=$db->Execute($sql);



 $f= (md5('doc'.$rs_entregado->fields["IDDOC"])).".".$rs_entregado->fields["TIPDOC"]."";
 $Tcredito=$rs_entregado->fields["TCREDIT"];

	$docs_upoload  ="upload_docs_gpo_solidario/";

 $descdoc=$rs->fields["DOC"];
 $Nombre_usuario=$rs_entregado->fields["DSC"];
 $Fecha_upload=$rs_entregado->fields["FECH"];
/******************************************************************/



//$file = $docs_jpg . $f;
$filename = $docs_upoload . $f;

$fecha_access  =  date("d-m-y");
touch($filename,$fecha_access, time());

header("Cache-Control: public");
header("Content-Description: File Transfer");
header('Content-disposition: attachment; filename='.basename($filename));
header("Content-Type: application/pdf");
header("Content-Transfer-Encoding: binary");
header('Content-Length: '. filesize($filename));
readfile($filename);
?>
