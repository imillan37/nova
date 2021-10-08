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

        //$pos = strpos($txtSearch,"->");
        //$num=substr($txtSearch,0,$pos);
        //$result = $num*1;
        $result = trim($txtSearch);

        $sql = "SELECT Direccion,Telefono,Extension FROM cat_convenio_empresas WHERE Status<>'Inactiva'  AND Convenio='1'
        AND Empresa = '".$result."'";
        $rs = $db->Execute($sql);
        list($direc_emp,$tel_emp,$ext_tel)=$rs->fields;

        echo "\n<SCRIPT>\n";
        echo "   window.opener.document.".$forma.".".$campo.".value='".$result."'; \n";
        echo "   window.opener.document.".$forma.".".$campo_direc.".value='".$direc_emp."'; \n";
        echo "   window.opener.document.".$forma.".".$campo_tel.".value='".$tel_emp."'; \n";
        echo "   window.opener.document.".$forma.".".$campo_ext.".value='".$ext_tel."'; \n";
        echo "   window.close(); \n";
        echo "</SCRIPT>\n";
?>
</HEAD>
</HTML>