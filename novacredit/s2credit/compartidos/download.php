<?
 $noheader=1;
 require($DOCUMENT_ROOT."/rutas.php");
 //Inicio conexión
 $db = ADONewConnection(SERVIDOR);  # create a connection
 $db->Connect(IP,USER,PASSWORD,NUCLEO);
 //Fin Conexión

?>
<?
/************************SQL***************************************/
 $sql_entregado = "SELECT Entregado,
                          ID_Doc 				AS IDDOC,
                          Descripcion    		AS DSC,
                          Fecha_upload   		AS FECH,
                          ID_Tipocredito 		AS TCREDIT,
                          Tipo           		AS TIPDOC,
                          Documento_migrado		AS MIGRA
                    FROM ".$Tbl_docs." 
                    WHERE ".$Param1." = '".$Param2."' 
                      AND ID_Documentos = '".$ID_Documentos."' ";
 $rs_entregado=$db->Execute($sql_entregado);

 $sql = "SELECT Documento AS DOC
          FROM cat_documentos_solicitudes 
          WHERE ID_Documentos = '".$ID_Documentos."' ";
 $rs=$db->Execute($sql);


 $f= (md5('doc'.$rs_entregado->fields["IDDOC"])).".".$rs_entregado->fields["TIPDOC"]."";
 $Tcredito=$rs_entregado->fields["TCREDIT"];

	$docs_upoload  =($Tcredito=='1')?("upload_docs/"):("upload_docs_gpo/");
	$docs_upoload  =($Tcredito=='3')?("upload_docs_nom/"):($docs_upoload);
	$docs_upoload  =($rs_entregado->fields["MIGRA"]=='SI')?("upload_docs_nom_v4/"):($docs_upoload);

 $descdoc=$rs->fields["DOC"];
 $Nombre_usuario=$rs_entregado->fields["DSC"];
 $Fecha_upload=$rs_entregado->fields["FECH"];
/******************************************************************/
?>
<?php


if (eregi("MSIE",$_SERVER['HTTP_USER_AGENT']))
        $MHTML=true;
else
        $MHTML = false;

if($MHTML)
{

  //see http://www.w3schools.com/media/media_mimeref.asp for details about Content-type:message/rfc822
  header('Content-type:message/rfc822');
}

header('Cache-Control: no-cache');
header('Cache-Control: no-store');
header('Cache-Control: private');


$file = $docs_upoload . $f;


$fecha_access  =  date("d-m-y");
touch($file,$fecha_access, time());

list($ancho, $altura, $tipo, $atr) = getimagesize($file);

if($fp = fopen($file,"rb", 0))
{
   $picture = fread($fp,filesize($file));
   fclose($fp);
   // base64 encode the binary data, then break it
   // into chunks according to RFC 2045 semantics
$image_data_1 =    $base64 = base64_encode($picture);
}


$image_data_1 = trim($image_data_1);



?>
<?php if($MHTML):?>
MIME-Version: 1.0
Content-Type: multipart/related; boundary="----=_NextPart"

------=_NextPart
Content-Location: file:///X:/
Content-Transfer-Encoding: quoted-printable
Content-Type: text/html; charset="us-ascii"

<?php endif ?>


<!doctype html public '-//w3c//dtd html 4.01//en' 'http://www.w3.org/tr/html4/strict.dtd'>
<html xmlns:v="urn:schemas-microsoft-com:vml">
<head>


<style>
v\:* {behavior:url(#default#VML);display:inline-block;}

</style>
</head>


<body>

<? $Hora=substr($Fecha_upload,10);
   $Fecha_upload= substr($Fecha_upload,-11,2)."/".substr($Fecha_upload,-14,2)."/".substr($Fecha_upload,-19,4);?>
<?php echo "<CENTER><b>$descdoc </b><BR><BR>";?>
<?php if($MHTML):?>

<?php
  $ancho.='px';
  $altura.='px';
   echo"<v:shape style=3D'width:$ancho;height:$altura;' ><v:imagedata src=  www.img1.com /></v:shape>";
   echo "<BR><BR><b>Archivado por:</b> $Nombre_usuario <BR> <b>Fecha:</b> $Fecha_upload &nbsp;&nbsp;<b>Hora:</b> $Hora<BR>";
?>

<?php else: ?>
<div align="center">
<img  src="data:image/png;base64,<?php echo $image_data_1  ?>"  />
</div>
<? echo "<BR><BR><b>Archivado por:</b> $Nombre_usuario <BR> <b>Fecha:</b> $Fecha_upload  &nbsp;&nbsp;<b>Hora:</b> $Hora<BR>"; ?>



<?php endif ?>

<BR>
<BR>
<BR>




</body>
</html>

<?php if($MHTML):?>

------=_NextPart
Content-Location: file:///X:/www.img1.com
Content-Transfer-Encoding: base64
Content-Type: image/png

<?php echo $image_data_1 ?>



<?php endif ?>


<?php echo "</CENTER>";?>



