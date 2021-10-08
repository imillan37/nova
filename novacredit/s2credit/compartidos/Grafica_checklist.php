<html>
<style type="text/css">
<!--
table {
	font: 11px Verdana, Arial, Helvetica, sans-serif;
	color: #777;
	padding:7px;
}
-->
</style>
<?php
echo "<CENTER><H1>Check list .</H1></CENTER>";
echo "<FORM Method='POST' ACTION='".$PHP_SELF."' NAME='check_list' >\n";
 echo"<TABLE width='95%' border='0' align='center' CELLPADDING='10' CELLSPACING='0' ID='medium'>";
//---------------------------------------------------------------------------------------------------
// Documentos entregados
//---------------------------------------------------------------------------------------------------
echo"<TD>";

echo"<TABLE WIDTH='100%' BORDER='0' CELLPADDING='5' CELLSPACING='0'>";
     
echo" <TR BGcolor='#6699cc'  ID='small' >
         <TD colspan='3'  align='center'><strong><font color='#000099' ><U>$Param1 </U></font></strong></TD>
         
     </TR>"; 

$datosTabla = array(
	      array( "Solicitud", $Param4, "#BDDA4C"),
	      array( "Investigación telefónica", $Param2, "#FF9A68"),
	      array( "Investigación domiciliaria", $Param3, "#69ABBF"),
                                                 );
$maximo = 0;
foreach ( $datosTabla as $ElemArray ) { $maximo += $ElemArray[1]; }
?>
<table width="730" border='1' cellspacing="0" cellpadding="5" align="center">

<?php 
foreach( $datosTabla as $ElemArray ) 
{
  
?>
<tr>
	
	<td align='left'><?php echo("<B>".$ElemArray[0].":</B> "); echo($ElemArray[1]." campos "); ?></td>
	<td>
		<table width="<?php echo($ElemArray[1]*30) ?>" bgcolor="<?php echo($ElemArray[2]) ?>">
		<tr><td> </td></tr>
	</table>
	</td>
	</tr>
	
<?php
} 
echo"</table>";


echo"</TABLE>";
echo"</TD>";
echo"</TABLE>";
?>

</html>









































