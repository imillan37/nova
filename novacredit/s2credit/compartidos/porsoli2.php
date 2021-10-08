<?php
$noheader=1;
include($DOCUMENT_ROOT."/rutas.php");


$db = &ADONewConnection(SERVIDOR);
$db->PConnect(IP,USER,PASSWORD,NUCLEO);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<HTML>
<HEAD>
<TITLE>Búsqueda por nombre</TITLE>

<?

	$pos = strpos($txtSearch,")");
        $num=substr($txtSearch,0,$pos);
	$result = $num*1;
	
	echo "\n<SCRIPT>\n";
	echo "   window.opener.document.".$forma.".".$campo.".value='".$result."'; ";
	echo "   window.close(); ";
	echo "</SCRIPT>\n";
?>
</HEAD>
</HTML>

