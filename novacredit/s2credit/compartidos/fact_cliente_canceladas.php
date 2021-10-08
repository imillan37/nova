<?
$exit='true';
require($DOCUMENT_ROOT."/rutas.php");
 //verflujo();
?>
<STYLE>
hr{
width:85%;
height:0px;/*solo queremos borde*/
text-align:left;
border-top:0px;/*quita el grosor extra de Opera y FFox*/
border-bottom:navy dashed 0px;
}
</STYLE>
<?
/*
         ________________________________________________________
        |  Titulo: Captura de solicitudes Personas Físicas      |
        |                                                       |
##      |  Autor : Enrique Godoy Calderón                       |
##      |          Tonathiú Cárdenas                            |
##      |  Fecha : Miércoles, 23 de Julio de 2008               |
##      |                                                       |
##      |  Descripción : Captura de solicitudes                 |
##      |                                                       |
##      |  Scripts relacionados: captura2pf.php                 |
##      |                                                       |
##      |  Lenguaje interpretado utilizado: php y javascript    |
##      |                                                       |
##      |  Dependencias : No hay.                               |
##      |                                                       |
##      |  Pendientes script:Documentación.                     |
##      |                                                       |
##      |  Tablas consultas "SELECT" SQL:                       |
##      |              sucursales,solicitud,promotores,usuarios |
##      |   codigos_postales,estados,ciudades,municipios,       |
##      |   cat_activosfijos,cat_finanzas_personales,cat_bancos |
##      |   parentesco_fam                                      |
##      |                                                       |
##      | ----------------------------------------------------- |
##      | Funciones java script                                 |
##      |                      loadaddress()                    |
##      |                      activacheck()                    |
##      |                      calcularfc()                     |
##      |                      vaciar_clone()                   |
##      |                      crear()                          |
##      |                      BuscaCP()                        |
##      |                      swapage()                        |
##      |                      swichedo()                       |
##      |                                                       |
##      | Funciones PHP                                         |
##      |                      NINGUNO                          |
##      |                                                       |
##      |                                                       |
##       ¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯
######################################################
######################################################

*/
//Inicio conexión
$db = ADONewConnection(SERVIDOR);  # create a connection
$db->Connect(IP,USER,PASSWORD,NUCLEO);
//Fin Conexión
//Verflujo sirve para dibujar tabla con varibles pasadas por el método post verflujo(); die();
//verflujo();
//rfclib.js rutina en javascript para la gerneración de RFC
echo "<SCRIPT type='text/javascript'  src='$rfcscript'></SCRIPT>";
echo "<script type='text/javascript'  src='".$shared_scripts."/jquery/accordion/jquery.dimensions.js'></script>";
echo "<script type='text/javascript'  src='".$shared_scripts."/jquery/accordion/jquery.accordion.js'></script>";



?>
<link rel="stylesheet" href="css/vista_presoli.css" type="text/css" media="print, projection, screen">



<!--IFRAME utilizado en loadaddress calcularfc -->
<CENTER>
<IFRAME NAME='loadvals' HEIGHT='0px' WIDTH='0px' ></IFRAME>
</CENTER>



<SCRIPT>
jQuery().ready(function(){
		// simple accordion
		jQuery('#list1a').accordion({
			autoheight: false
		});
		jQuery('#list1b').accordion({
			autoheight: false,
			top: true

		});

		// second simple accordion with special markup
		jQuery('#navigation').accordion({
			active: false,
			header: '.head',
			navigation: true,
			event: 'mouseover',
			fillSpace: true,
			animated: 'easeslide'
		});


	});

</SCRIPT>

<?
 echo "<FORM Method='POST' ACTION='".$PHP_SELF."' NAME='solicitud' >\n";




?>


        <!---Control superior--->
<?

$sql_find="SELECT ID_Solicitud,Responsable_cancel,Observaciones,Observaciones_cancel,Fecha_cancelacion FROM fact_cliente_cancelacion
           LEFT JOIN clientes_datos on fact_cliente_cancelacion.Num_cliente = clientes_datos.Num_cliente
           WHERE id_factura='$factura' ";
$rs_find=$db->Execute($sql_find);

$Param1='ID_Solicitud';
$Param2=$rs_find->fields["ID_Solicitud"];
$Responcable_cancel=$rs_find->fields["Responsable_cancel"];
$Fecha_cancel=$rs_find->fields["Fecha_cancelacion"];
$Coments_cancelI=$rs_find->fields["Observaciones_cancel"];
$Coments_cancelII=$rs_find->fields["Observaciones"];
if(empty($Coments_cancelII)){$Coments_cancelII='SIN COMENTARIOS.';}
?>

  <!---Datos generales del solicitante--->

<?

$sql = "SELECT Nombre,NombreI,Ap_paterno,Ap_materno FROM solicitud WHERE ID_Solicitud = '$Param2' ";

 $rs=$db->Execute($sql);
list($nombre_soli,$nombre_soli_dos,$ap_paterno_soli,$ap_materno_soli)=$rs->fields;


echo "<TABLE ALIGN='center' class='main' CELLSPACING=0 CELLPADDING=1 WIDTH='50%' STYLE='border:4px dotted #6699cc;' >
	 <TR><TD>
	 <TABLE BORDER=0 CELLSPACING=3 CELLPADDING=1 WIDTH='100%' BGCOLOR='white' ID='small' >
	 <TR ALIGN='left' VALIGN='middle' >
	 <TH ALIGN='center'  COLSPAN='2'><U><FONT COLOR='black'></FONT></U> </TH>
	 </TR>";


	echo"<TR ALIGN='left' VALIGN='middle'>

	 <TD ALIGN='center' >
          <B><font size='4'>ID CRÉDITO # $factura </font></B>
	 </TD>
	</TR> ";


	echo"<TR ALIGN='left' VALIGN='middle'>

	 <TD ALIGN='center' >
          <B><font size='4'><IMG  BORDER=0 SRC='".$img_path."user_blue.png'  ALT='editando'   />&nbsp; $nombre_soli $nombre_soli_dos $ap_paterno_soli $ap_materno_soli </font></B>
	 </TD>
	</TR> ";

	echo"</TABLE>
	</TR>
	</TD>
	</TABLE>";

echo"<BR>";



echo "<FORM METHOD=POST NAME='Consulta' ACTION='$PHP_SELF'   validate='onchange' invalidColor='yellow' lang='es'>";
echo "<INPUT TYPE='HIDDEN' NAME='ID_soli'       value='$ID_soli'>";
echo "<INPUT TYPE='HIDDEN' NAME='gpo_soli'      value='$gpo_soli'>";
echo "<INPUT TYPE='HIDDEN' NAME='exit'          value='True'>";

echo"<CENTER><IMG  BORDER=0 SRC='".$img_path."info_blue.png'  ALT='editando'   /> <BR> <font color='red' size='3'><B>CANCELADO POR:&nbsp;&nbsp;$Responcable_cancel  <BR> FECHA: $Fecha_cancel  </B></font><BR><BR><font color='red' size='2'><B><U>$Coments_cancelI </U></B></font></CENTER>";
echo"<BR><HR><BR>
<TABLE width='50%' border='0' align='center' CELLPADDING='0' CELLSPACING='0' ID='medium'>";
echo"<TD>";

echo"<TABLE WIDTH='100%' STYLE='border:2px dotted #6699cc;' CELLPADDING='3' CELLSPACING='2'>
<TR  BGcolor='white' ID='medium'>
<TD  align='center' ><strong><font color='black' ><IMG  BORDER=0 SRC='".$img_path."comment.png'  ALT='editando'   />&nbsp;&nbsp;MOTIVO POR EL CUAL SE CANCELÓ EL CRÉDITO: </font></strong></TD>";
echo"</TR>";

echo"<TR  BGcolor='white' ID='medium'>
<TD  align='center' ><TEXTAREA  COLS=180 ROWS=2 id='small' NAME='coments_cancel' READONLY>$Coments_cancelII</TEXTAREA></TD>
</TR>";

echo"</TABLE>";
echo"</TD>";
echo"</TABLE>";




echo"</FORM>";

echo"<div id='divForm'></div>";



?>

</BODY>
</HTML>


















































