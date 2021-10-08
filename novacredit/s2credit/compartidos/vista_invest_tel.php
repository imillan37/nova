<?
require($DOCUMENT_ROOT."/rutas.php");
echo "<SCRIPT type='text/javascript'  src='javascript/valida_captura.js'></SCRIPT>";
//verflujo();
echo "<script type='text/javascript'  src='".$shared_scripts."/jquery/accordion/jquery.dimensions.js'></script>";
echo "<script type='text/javascript'  src='".$shared_scripts."/jquery/accordion/jquery.accordion.js'></script>";

?>


<script type="text/javascript">
jQuery().ready(function(){
		// simple accordion
		jQuery('#list1a').accordion({
			autoheight: false
			});
		jQuery('#list1b').accordion({
			autoheight: false
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
</script>
<link rel="stylesheet" href="css/vista_presoli.css" type="text/css" media="print, projection, screen">

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
echo "<SCRIPT type='text/javascript'  src='javascript/valida_captura.js'></SCRIPT>";

?>

<?
$sql = "SELECT ID_Solicitud,Fecha,Folio,Monto,Plazo,ID_Producto,Nombre,NombreI,Ap_paterno,Ap_materno,ID_Solicitud FROM solicitud WHERE ID_Solicitud = '$ID_Solicitud' ";

//debug($sql);
$rs=$db->Execute($sql);
list($ID_soli,$fecha_sol,$folio,$monto,$plazo,$producto,$nombre_soli,$nombre_soli_dos,$ap_paterno_soli,$ap_materno_soli)=$rs->fields;

 echo "<FORM Method='POST' ACTION='".$PHP_SELF."' NAME='solicitud' >\n";


 $sql = "SELECT Nombre, Direccion, Colonia, CP, Ciudad, Estado, Telefonos, FAX FROM sucursales WHERE   ID_Sucursal= '$ID_SUC' ";
 $rs=$db->Execute($sql);

 $sucursal=$rs->fields[0];
 $direccionsuc = $rs->fields[1]." Col. ".$rs->fields[2]." C.P. ".$rs->fields[3]." ".$rs->fields[4]." ".$rs->fields[5];
 $telsuc = $rs->fields[6];

 if( $rs->fields[7])
 {
   $telsuc .= "  Fax. ".$rs->fields[6] ;
 }

 echo "<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=\"100%\" >";
 echo "<TR ALIGN=\"left\" VALIGN=\"middle\">";
 echo "<TD WIDTH='40%' ALIGN='center'>
	<U><B><FONT SIZE=\"5\">Investigación telefónica</FONT></B></U> <BR>
	<B><FONT SIZE=\"5\">".$sucursal."</FONT></B> <BR>
	<B><FONT SIZE=\"1\">".$direccionsuc."<BR> Tels.".$telsuc ."</FONT></B> <BR>
       </TD>";
echo "<TD ROWSPAN='2'>";



?>


        <!---Control superior--->
<?
echo "<TABLE ALIGN='center' class='main' CELLSPACING=0 CELLPADDING=1 WIDTH='95%' BGCOLOR='gray' ID='small'>
      <TR><TD>      <TABLE ALIGN='right' BORDER=0 CELLSPACING=2 CELLPADDING=1 WIDTH='100%' BGCOLOR='silver' ID='small'>";



echo "<TR ALIGN=\"left\" VALIGN=\"middle\">";
echo "<TH ALIGN=\"right\" >  Fecha de solicitud     : </TH>";

$fecha_sol= substr($fecha_sol,8)."/".substr($fecha_sol,-5,2)."/".substr($fecha_sol,-10,4);


echo " <TD ALIGN=\"left\" width='60%' >$fecha_sol";
echo "</TR>";

echo"<TR ALIGN='left' VALIGN='middle'>
            <TH ALIGN='right' >    Folio solicitud: </TH>
            <TD ALIGN='left' ><U>$ID_soli</U></TD>
      </TR>";


if(!$monto) $monto='0.0';

echo"      <TR ALIGN='left' VALIGN='middle'>
      <TH ALIGN='right' >     Monto Solicitado : </TH>
      <TD ALIGN='left' >$$monto</TD></TR>";

/*echo"<TR ALIGN='left' VALIGN='middle'>
            <TH ALIGN='right' >     Plazo (meses): </TH>
            <TD ALIGN='left' > $plazo</TD>
      </TR>";

echo" <TR ALIGN='left' VALIGN='middle'>
      <TH ALIGN='right' > Producto financiero :        </TH>
      <TD ALIGN='left' > ";
echo"</TD></TR>";


$sql = "SELECT Nombre  FROM  cat_productosfinancieros WHERE ID_Producto = '$producto' ";
$rs=$db->Execute($sql);
$producto=$rs->fields[0];

echo"$producto";

*/

echo "<TR ALIGN='left' VALIGN='middle'>
         <TH ALIGN='right'> Nombre: </TH>
         <TD ALIGN='left' >$nombre_soli $nombre_soli_dos $ap_paterno_soli $ap_materno_soli</TD>
      </TR>";


echo "</TD> </TR>\n";
echo "</TABLE>";
echo "</TD>
      </TR>
      </TABLE>";
echo "</TD>";
echo "</TR>";
?>
<SCRIPT>Disabled_color();</SCRIPT>
</TABLE>
<BR>
<BR>
<BR>


<?

echo "<TABLE ALIGN='center' class='main' CELLSPACING=0 CELLPADDING=1 WIDTH='50%' STYLE='border:4px dotted #6699cc;' >
	 <TR><TD>
	 <TABLE BORDER=0 CELLSPACING=3 CELLPADDING=1 WIDTH='100%' BGCOLOR='white' ID='small' >
	 <TR ALIGN='left' VALIGN='middle' >
	 <TH ALIGN='center'  COLSPAN='2'><U><FONT COLOR='black'></FONT></U> </TH>
	 </TR>";


	echo"<TR ALIGN='left' VALIGN='middle'>

	 <TD ALIGN='center' >
          <B><font size='4'>Folio # $ID_soli </font></B>
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

$sql_cons="SELECT ID_Solicitud,Fecha,Actividad_soli,Convenio,Empresa_soli,Direc_empresa_soli,Telefono_empresa,Extension_tel,Puesto,IMSS,Ingresos_soli,Otros_ingresos_soli,Jefe_soli,Patron,Dep_empresa,Dias_pago,Num_nomina,Tiempo_trabajoI,Tiempo_trabajoII,Empresa_soli_anterior,Direc_empresa_soli_anterior,Telefono_empresa_anterior,Puesto_anterior,Ingresos_soli_anterior,Jefe_soli_anterior,Confirm_laborales,Observaciones,Nombre_informante,Puesto_informante
           FROM investigacion_tel
           WHERE ID_Solicitud = '$ID_Solicitud'";


$rs_cons=$db->Execute($sql_cons);
list($ID_soli,$fecha_inv,$actividad_econom_soli,$convenio_emp,$empresa_soli,$domicilio_empresa,$tel_emp,$telcontacto_extension_soli,$puesto_soli,$imss_emp,$ingresos_emp,$otros_ingresos_emp,$jefe_emp,$patron_emp,$dep_empresa,$dias_pago_emp,$nomina_emp,$tiempo_trabajoI,$tiempo_trabajoII,$empresa_soli_ant,$domicilio_empresa_ant,$tel_emp_ant,$puesto_soli_ant,$ingresos_emp_ant,$jefe_emp_ant,$confirm_laboral_soli,$observciones,$nomb_informante,$puesto_informante)=$rs_cons->fields;


$fecha_inv= substr($fecha_inv,8)."/".substr($fecha_inv,-5,2)."/".substr($fecha_inv,-10,4);

echo"<div id='main'>
     <div class='basic' style='float:center; width:98%; margin-bottom:1em'  id='list1a'>";

echo"<IMG  BORDER=0 SRC='".$img_path."rightarrow.png' onclick='self.scroll(0,700)'  ALT='editando'  align='right'  STYLE='cursor:pointer' />";


	echo"<a id='title_ref'>REFERENCIAS PERSONALES</a>
	     <div>";
	echo"<IMG  BORDER=0 SRC='".$img_path."rightarrow.png' onclick='self.scroll(0,3500)'  ALT='editando'  align='right'  STYLE='cursor:pointer' />";


echo"<BR>";


//Reordenamos el campo Num para no tener problemas al borrar
/*
 $sql="SELECT Num FROM solicitud_ii WHERE ID_Solicitud =  '$ID_soli'  order by Num";
 $rs = $db->Execute($sql);
 $contador=1;
 While(!$rs->EOF)
 {
  $temp=$rs->fields[0];
  $sql="UPDATE solicitud_ii SET Num =  $contador WHERE ID_solicitud = '$ID_soli'  and Num = '".$temp."'";
  $db->Execute($sql);
  $contador++;
  $rs->MoveNext() ;
 }
 */
//FIN

/*
$sql="SELECT
            NombreI,NombreII,Ap_paterno,Ap_materno,RFC,CP,Calle,Parentesco,Telefono,
            Colonia,Numero,Hijos,Relacion,Tiempo_conocido,ECivil,Vivienda,Actividad_laboral,
            Ultimo_contacto,Cumple_pagos,Dinero_prestamo,Aceptaria_aval,Estado,Ciudad,Poblacion
FROM solicitud_ii
            WHERE ID_Solicitud = '$ID_soli' ";
*/

$sql="SELECT
           Nombre,Nombre_dos,Ap_paterno,Ap_materno,RFC,CP,Calle,Relacion,Telefono,
           Colonia,Numero,Hijos,Relacion,Tiempo_conocido,ECivil,Vivienda,Actividad_laboral,
            Ultimo_contacto,Cumple_pagos,Dinero_prestamo,Aceptaria_aval,Estado,Ciudad,Poblacion,
            referencias.ID_Referencia
        FROM referencias
          LEFT JOIN solicitud_referencias ON referencias.ID_Referencia = solicitud_referencias.ID_Referencia
          WHERE solicitud_referencias.ID_Solicitud = '".$ID_soli."' ";

//debug($sql);
$rs_cons = $db->Execute($sql);

/*
$sql="SELECT COUNT(*) FROM solicitud_ii
                 WHERE ID_Solicitud = '$ID_soli' ";
*/

$sql="SELECT COUNT(referencias.ID_Referencia)
       FROM referencias
        LEFT JOIN solicitud_referencias ON referencias.ID_Referencia = solicitud_referencias.ID_Referencia
         WHERE solicitud_referencias.ID_Solicitud = '".$ID_soli."' ";
         
$rs_aux = $db->Execute($sql);
$num_count = $rs_aux->fields[0];
$cont=1;
$i=0;

if($num_count>0)
{
 While(!$rs_cons->EOF)
 {


$nombre_persoI=$rs_cons->fields[0];
$nombre_persoII=$rs_cons->fields[1];
$ap_paterno_perso=$rs_cons->fields[2];
$ap_matenro_perso=$rs_cons->fields[3];
$rfc_perso=$rs_cons->fields[4];
$cp_perso=$rs_cons->fields[5];
$colonia_perso=$rs_cons->fields[9];
$estado_perso=$rs_cons->fields[21];
$ciudad_perso=$rs_cons->fields[22];
$poblacion_perso=$rs_cons->fields[23];
$calle_perso=$rs_cons->fields[6];
$parentesco_perso=$rs_cons->fields[7];
$telefono_perso=$rs_cons->fields[8];
$num_perso=$rs_cons->fields[10];
$hijos_option_ref=$rs_cons->fields[11];
$relacion_soli_ref=$rs_cons->fields[12];
$tiempo_rel_ref=$rs_cons->fields[13];
$edocivil_soli_ref=$rs_cons->fields[14];
$vive_option_ref=$rs_cons->fields[15];
$laboral_option_ref=$rs_cons->fields[16];
$ultimo_tiempo_ref=$rs_cons->fields[17];
$pago_option_ref=$rs_cons->fields[18];
$dinero_option_ref=$rs_cons->fields[19];
$aval_option_ref=$rs_cons->fields[20];

$ref=$i+1;


echo "<TABLE ALIGN='center' class='main' CELLSPACING=0 CELLPADDING=1 WIDTH='70%' STYLE='border:4px dotted #6699cc;' >
	 <TR><TD>
	 <TABLE BORDER=0 CELLSPACING=3 CELLPADDING=1 WIDTH='100%' BGCOLOR='white' ID='small' >
	 <TR ALIGN='left' VALIGN='middle' >
	 <TH ALIGN='center'  COLSPAN='2'><U><FONT COLOR='black'></FONT></U> </TH>
	 </TR>";


	echo"<TR ALIGN='left' VALIGN='middle'>

	 <TD ALIGN='center' >
          <B> <FONT size='3'> $ref) REFERENCIA PERSONAL.</FONT></B> &nbsp;&nbsp;<IMG SRC='".$sys_path."images/user_blue.png' BORDER=0>
	 </TD>
	</TR> ";


	echo"<TR ALIGN='left' VALIGN='middle'>

	 <TD ALIGN='center' >
          <B><font size='4'> $nombre_persoI $nombre_persoII $ap_paterno_perso $ap_matenro_perso </font></B>
	 </TD>
	</TR> ";

	echo"<TR ALIGN='left' VALIGN='middle'>

	<TD ALIGN='center' >
	<HR>
	</TD>
	</TR> ";



echo"<TR ALIGN='left' VALIGN='middle' >

	 <TD ALIGN='center' >
          <font size='2'> <B>CP:</B> $cp_perso &nbsp;&nbsp; <B>Colonia :</B> $colonia_perso &nbsp;&nbsp; <B>Estado : </B>$estado_perso &nbsp;&nbsp; <B>Ciudad :</B> $ciudad_perso&nbsp;&nbsp; <BR> <B>Población/Municipio/Delegación :</B> $poblacion_perso&nbsp;&nbsp;<B>Calle :</B> $calle_perso&nbsp;&nbsp; <B>Número :</B> $num_perso&nbsp;&nbsp; <BR><BR> <B>Teléfono :</B> $telefono_perso&nbsp;&nbsp;</font>
	 </TD>
	</TR> ";


	echo"</TABLE>
	</TR>
	</TD>
	</TABLE>";

echo"<BR>";


echo " <TABLE ALIGN='CENTER' class='main' CELLSPACING=0 CELLPADDING=0 WIDTH='90%' BGCOLOR='silver' ID='tblId'>  \n\n";


  echo"<TR ALIGN='left' VALIGN='middle'>
          <TD>
       <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH='100%'  ID='small'>";




echo" <TR ALIGN='left' VALIGN='middle'>
	  <TH ALIGN='center' ID='big' COLSPAN='2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<IMG  BORDER=0 SRC='".$img_path."info.png'  ALT='editando'  STYLE='width:20px; height:20px;' /><U><FONT size='2' > Telefónica</U></FONT>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</TH>
 </TR>";



echo"<TR ALIGN='left' VALIGN='middle'>
	<TH ALIGN='right'>¿Tiene hijos?: &nbsp;</TH>
		  <TD ALIGN='left' ><FIELDSET style='WIDTH:70%'>$hijos_option_ref";


echo"</FIELDSET></TD>
	</TR>";

echo"<TR ALIGN='left' VALIGN='middle' >
                             <TD colspan='4'><HR>";
echo"</TD></TR>";

echo" <TR ALIGN='left' VALIGN='middle'>
	      <TH ALIGN='right'> ¿Relación o parentezco? :&nbsp;

	      <TD ALIGN='left'><FIELDSET style='WIDTH:70%'>$relacion_soli_ref ";

	echo "</FIELDSET ></TD> ";
	echo "</TR>";


echo"<TR ALIGN='left' VALIGN='middle' >
                             <TD colspan='4'><HR>";
echo"</TD></TR>";

echo" <TR ALIGN='left' VALIGN='middle'>
      <TH ALIGN='right'>  ¿Tiempo de conocerlo?:&nbsp; </TH>
      <TD ALIGN='left'><FIELDSET style='WIDTH:70%'>$tiempo_rel_ref";


	echo "</FIELDSET></TD> ";
	echo "</TR>";

echo"<TR ALIGN='left' VALIGN='middle' >
                             <TD colspan='4'><HR>";
echo"</TD></TR>";

echo" <TR ALIGN='left' VALIGN='middle'>
      <TH ALIGN='right'1 WIDTH='30%'>  Estado Civil :  </TH>
      <TD ALIGN='left' ><FIELDSET style='WIDTH:60%'>$edocivil_soli_ref ";

echo "</FIELDSET></TD> ";
echo "</TR>";

echo"<TR ALIGN='left' VALIGN='middle' >
                             <TD colspan='4'><HR>";
echo"</TD></TR>";


 echo"<TR ALIGN='left' VALIGN='middle'>";
 echo"       <TH ALIGN='right' ID='residencia'>¿Modalidad de vivienda?: &nbsp; </TH>
              <TD ALIGN='left'><FIELDSET style='WIDTH:70%'>$vive_option_ref";

   echo"</FIELDSET></TD></TR>";


echo"<TR ALIGN='left' VALIGN='middle' >
                             <TD colspan='4'><HR>";
echo"</TD></TR>";

 echo"<TR ALIGN='left' VALIGN='middle'>";
 echo"       <TH ALIGN='right' ID='residencia'>¿Actividad laboral?: &nbsp; </TH>
              <TD ALIGN='left'><FIELDSET style='WIDTH:70%'>$laboral_option_ref";

   echo"</FIELDSET></TD></TR>";

echo"<TR ALIGN='left' VALIGN='middle' >
                             <TD colspan='4'><HR>";
echo"</TD></TR>";


   echo" <TR ALIGN='left' VALIGN='middle'>
   		      <TH ALIGN='right'> ¿Última vez que tuvo contacto?:&nbsp; </TH><TD ALIGN='left'><FIELDSET style='WIDTH:70%'>$ultimo_tiempo_ref";


      	echo "</FIELDSET></TD> ";
	echo "</TR>";

echo"<TR ALIGN='left' VALIGN='middle' >
                             <TD colspan='4'><HR>";
echo"</TD></TR>";

     echo"<TR ALIGN='left' VALIGN='middle'>
   	     <TH ALIGN='right'>¿Cumple con sus obligaciones de pago?:&nbsp;  </TH>
	<TD ALIGN='left'><FIELDSET style='WIDTH:70%'>$pago_option_ref";

	echo"</FIELDSET></TD></TR>";


echo"<TR ALIGN='left' VALIGN='middle' >
         <TD colspan='4'><HR>";
echo"</TD></TR>";

	echo"<TR ALIGN='left' VALIGN='middle'>
	<TH ALIGN='right'>¿Le prestaría dinero?:&nbsp;  </TH>
	<TD ALIGN='left'><FIELDSET>$dinero_option_ref";

	echo"</FIELDSET ></TD></TR>";

echo"<TR ALIGN='left' VALIGN='middle' >
       <TD colspan='4'><HR>";
echo"</TD></TR>";

    echo"<TR ALIGN='left' VALIGN='middle'>
	     <TH ALIGN='right'>¿Aceptaría ser su avál?:&nbsp;  </TH>
	<TD><FIELDSET style='WIDTH:70%'>$aval_option_ref";

	echo"</FIELDSET></TD></TR>";



echo"       <TR><TD COLSPAN='8'><BR></TD></TR>
          </TABLE>
       </TD>
       </TR>\n";


echo"   </TABLE> \n";
echo" </TD>
      </TR>
      </TABLE>";

echo"<BR><BR>";

$i++;
$rs_cons->MoveNext();
 }//Fin while


}
else
{
echo"<TR ALIGN='left' VALIGN='middle'>
            <TD>
        <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH='100%'  ID='small'>
        <TR ALIGN='center' VALIGN='middle'>
            <TH ALIGN='center'></TD>
            <TD ALIGN='center' >No existen referencias  </TD>

        </TR>


        <TR><TD STYLE='background-color:navy;'COLSPAN='8'></TD></TR>
        </TABLE>
            </TD>
         </TR>";

}

echo"   </TABLE> \n";
//--------------------------------------------------------------------------------------------------------------------
//
echo" </TD>
      </TR>
      </TABLE>
          <BR>";



  echo "<TABLE ALIGN='center' class='main' CELLSPACING=0 CELLPADDING=1 WIDTH='40%' BGCOLOR='gray' ID='small'>
	           <TR><TD>
	           <TABLE BORDER=0 CELLSPACING=3 CELLPADDING=1 WIDTH='100%' BGCOLOR='#cdcde5' ID='small' >
	           <TR ALIGN='left' VALIGN='middle' >
	           <TH ALIGN='center'  COLSPAN='2'><U><FONT COLOR='black'></FONT></U> </TH>
	           </TR>";


	  echo"<TR ALIGN='left' VALIGN='middle'>

	           <TD ALIGN='center' >

	          <BUTTON   STYLE='cursor:pointer;' onclick=\"$('#title_inf').trigger('click'); window.scrollTo(0,0);\" ><B>&lt;&lt; ANTERIOR</B></BUTTON>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;



	          <BUTTON  STYLE='cursor:pointer;'  onclick=\"$('#title_coments').trigger('click'); window.scrollTo(0,0);\" ><B>SIGUIENTE &gt;&gt;<B></BUTTON>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;




	           </TD>
	      </TR> ";

	    echo"</TABLE>
	          </TR>
	          </TD>
     </TABLE>";

echo"<BR>";



echo"</div>";
echo"<a id='title_coments'>COMENTARIOS</a>
     <div>";

echo"<BR><BR>";
echo"<TABLE width='50%' border='0' align='center' CELLPADDING='0' CELLSPACING='0' ID='medium'>";
echo"<TD>";

echo"<TABLE WIDTH='100%' STYLE='border:2px dotted #6699cc;' CELLPADDING='4' CELLSPACING='3'>
<TR   ID='medium' BGcolor='#6699cc'>

<TD  align='center' ><FONT COLOR='WHITE'> COMENTARIOS:</FONT>&nbsp;&nbsp;<IMG  BORDER=0 SRC='".$img_path."comment.png'  ALT='editando'/> </TD>
</TR>

<TR  BGcolor='#ffffff' ID='medium'>

<TD  align='center' ><TEXTAREA ID='small' COLS=180 ROWS=2 NAME='observciones' >$observciones</TEXTAREA></TD>
</TR>";
echo"</TABLE></TD></TABLE>";
echo"<BR>";



echo"<INPUT TYPE=HIDDEN name='ID_soli'   VALUE='".$ID_soli."'>";

echo "\n</FORM>\n\n";

echo"</div>
<a ID='HISTORIAL' >HISTORIAL DE SUCESOS</a>
     <div >";


echo"<br>";

echo"<TABLE WIDTH='70%' STYLE='border:3px dotted #6699cc;' align='center' CELLPADDING='5' CELLSPACING='3'>";

echo" <TR BGcolor='#6699cc'  ID='small'>
         <TD colspan='1' align='center'><strong><font color='white' ><U>Fecha - Hora</U></font></strong></TD>
         <TD colspan='1' align='center'><strong><font color='white' ><U>Usuario</U></font></strong></TD>
         <TD colspan='1' align='center'><strong><font color='white' ><U>Status</U></font></strong></TD>
         <TD colspan='1' align='center'><strong><font color='white' ><U>Suceso</U></font></strong></TD>
      </TR>";


$fecha=ffecha($fecha_hoy);
$fecha=gfecha($fecha);
/***QUERY*****/
$sql_cons ="SELECT  Fecha, Atendio, Status, Suceso
            FROM solicitud_sucesos
            WHERE ID_Solicitud = '".$ID_soli."' ORDER BY  Fecha ";


//debug($sql_cons);
$rs_cons = $db->Execute($sql_cons);
/***FIN QUERY*****/

if($rs_cons->_numOfRows)
{
 while(! $rs_cons->EOF )
 {

 $color=($rs_cons->fields[2] == 'Modificada')?("<FONT color='blue'>"):("<FONT color='blue'>");
 $colorII=($rs_cons->fields[3] == 'RECHAZA REPORTE DE BC')?("<FONT color='red'>"):("<FONT color='black'>");

 echo"<TR BGcolor='#FFFFFF'  ID='small' onmouseover=\"javascript:this.style.backgroundColor='yellow'; this.style.cursor='hand'; \" onmouseout=\"javascript:  this.style.backgroundColor='' \" BGCOLOR='white'> ";
  echo"<TD colspan='1' align='left' >".$rs_cons->fields[0]."</TD>
       <TD colspan='1' align='left'>".$rs_cons->fields[1]."</TD>";
  echo"<TD colspan='1' align='center' >$color ".$rs_cons->fields[2]." </FONT></TD>";
  echo"<TD colspan='1' align='center' >$colorII ".$rs_cons->fields[3]." </FONT></TD>";
  echo"</TR>";
  $rs_cons->MoveNext();

 }
}
else
{
  echo"<TR BGcolor='#FFFFFF'  ID='small'> ";
   echo"<TD colspan='6' align='center' ID='S2'><H2>SIN SUCESOS</H2></TD>";
  echo"</TR>";

}


echo"</TABLE>";

echo"</DIV></DIV>";


 echo"    <TABLE ALIGN='CENTER' BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH='90%' >
          <TR ALIGN='RIGHT' VALIGN='middle'>
          <BR>
          <BR>
          <TD ALIGN='CENTER'>

          <input type='button' value='Imprimir' onclick='window.print();' />
          </TD>
          </TR>
     </TABLE>";


//************************************************************************************************************************

?>
