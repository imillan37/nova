<?
$noheader=1;
include($DOCUMENT_ROOT."/rutas.php");


?><!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd' >
<html lang='es-MX'>
<head>
<?
   echo "<link href='".$style_path."sistema.css' rel='stylesheet' type='text/css'>\n";

?>

        <style type='text/css' media='screen'>
            body {
                font: 11px arial;
            }
            .suggest_link {
                background-color: #FFFFFF;
                padding: 2px 6px 2px 6px;
            }
            .suggest_link_over {
                background-color: #3366CC;
                padding: 2px 6px 2px 6px;
            }
            #search_suggest {
                position: absolute;
                background-color: #FFFFFF;
                text-align: left;
                border: 1px solid #000000;
            }
        </style>
        <script language='JavaScript' type='text/javascript' src='ajax_search_emp.js'></script>
    </head>
    <body>
    <DIR><BR>
        <U><h4  style='color: #000000; text-decoration: none;' ID='big'>Búsqueda de empresa por nombre.</h4></U>

            <FORM id='frmSearch' action='porempresa2.php'>


                <INPUT TYPE='text' size='50' maxlength='100' id='txtSearch' name='txtSearch' alt='Búsqueda por nombre' autocomplete='off' />

                <INPUT TYPE='button' id='cmdNow' name='cmdNow' value='&raquo;' alt='Ejecutar búsqueda' onClick='searchSuggest("porempresa.php");' />

                <INPUT TYPE='submit' id='cmdSearch' name='cmdSearch' value='OK' alt='Aplicar resultados' /><br />









                <div id='search_suggest'></div>
<?

        echo "<INPUT TYPE='HIDDEN' NAME='campo_ext'             VALUE='$campo_ext' >";
        echo "<INPUT TYPE='HIDDEN' NAME='campo'                 VALUE='$campo' >";
        echo "<INPUT TYPE='HIDDEN' NAME='forma'                 VALUE='$forma' >";
        echo "<INPUT TYPE='HIDDEN' NAME='campo_tel'             VALUE='$campo_tel' >";
        echo "<INPUT TYPE='HIDDEN' NAME='campo_direc'           VALUE='$campo_direc' >";

?>


            </FORM>
        </DIR>
   <SCRIPT>
     document.forms[0].elements[0].focus();
   </SCRIPT>
    </body>
</html>
