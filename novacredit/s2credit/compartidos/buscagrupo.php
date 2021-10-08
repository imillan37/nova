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
        <script language='JavaScript' type='text/javascript' src='ajax_search.js'></script>
<script language='JavaScript'>
function checkArrows (field, evt) 
{
  var keyCode =
    document.layers ? evt.which :
    document.all ? event.keyCode :
    document.getElementById ? evt.keyCode : 0;
  var r = '';
//  if (keyCode == 39)
//    r += 'arrow right';

if (keyCode == 40)
{
	var element = getElemetByID('search_suggest');
	element.focus();
}
/*    r += 'arrow down';
  else if (keyCode == 38)
    r += 'arrow up';
  else if (keyCode == 37)
    r += 'arrow left';
  r += ' ' + keyCode;
  alert(r);
 */
  return true;
}

</script>




    </head>
    <body>
    <DIR><BR>
        <U><h4  style='color: #000000; text-decoration: none;' ID='big'>Búsqueda de grupos por nombre.</h4></U>

            <FORM id='frmSearch' action='porgrupo2.php'>
<? //               <INPUT TYPE='text' size='50' maxlength='100' id='txtSearch' name='txtSearch' alt='Búsqueda por nombre' onkeyup='searchSuggest("porgrupo.php");' autocomplete='off' />
   //             <INPUT TYPE='submit' id='cmdSearch' name='cmdSearch' value='OK' alt='Ejecutar búsqueda' ONKEYDOWN='return checkArrows(this, event)'/><br />
?>


                <INPUT TYPE='text' size='50' maxlength='100' id='txtSearch' name='txtSearch' alt='Búsqueda por nombre' autocomplete='off' />
                <INPUT TYPE='button' id='cmdNow' name='cmdNow' value='&raquo;' alt='Ejecutar búsqueda' onClick='searchSuggest("porgrupo.php");' />                
                <INPUT TYPE='submit' id='cmdSearch' name='cmdSearch' value='OK' alt='Aplicar resultados' /><br />

                <div id='search_suggest'></div>
<?
		echo "<INPUT TYPE='HIDDEN' NAME='campo'                 VALUE='$campo' >";
		echo "<INPUT TYPE='HIDDEN' NAME='forma'                 VALUE='$forma' >";
?>                
                
                
            </FORM>
        </DIR>
   <SCRIPT> 
     document.forms[0].elements[0].focus();
   </SCRIPT>     
    </body>
</html>