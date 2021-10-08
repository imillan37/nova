<?
require($DOCUMENT_ROOT."/rutas.php");

echo "<SCRIPT type='text/javascript'  src='javascript/valida_captura.js'></SCRIPT>";
echo "<script type='text/javascript'  src='".$shared_scripts."/jquery/accordion/jquery.dimensions.js'></script>";
echo "<script type='text/javascript'  src='".$shared_scripts."/jquery/accordion/jquery.accordion.js'></script>";?>
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

?>

<link rel="stylesheet" href="css/vista_presoli.css" type="text/css" media="print, projection, screen">

<?

$sql_cons="SELECT ID_Solicitud,Fecha,Actividad_soli,Convenio,Empresa_soli,Direc_empresa_soli,Telefono_empresa,Extension_tel,Puesto,IMSS,Ingresos_soli,Otros_ingresos_soli,Jefe_soli,Patron,Dep_empresa,Dias_pago,Num_nomina,Tiempo_trabajoI,Tiempo_trabajoII,Empresa_soli_anterior,Direc_empresa_soli_anterior,Telefono_empresa_anterior,Puesto_anterior,Ingresos_soli_anterior,Jefe_soli_anterior,Confirm_laborales,ECivil,Vivienda,Automovil,Hijos,Tipo_trabajo,Interior_casa,Tipo_calle,Edad_cliente,Inmobiliario,Observaciones,Nombre_informante,Puesto_informante,Atendido
           FROM investigacion_dom
           WHERE $Param1 = '$Param2'";

$rs_cons=$db->Execute($sql_cons);
list($ID_soli,$fecha_sol,$actividad_econom_soli,$convenio_emp,$empresa_soli,$domicilio_empresa,$tel_emp,$telcontacto_extension_soli,$puesto_soli,$imss_emp,$ingresos_emp,$otros_ingresos_emp,$jefe_emp,$patron_emp,$dep_empresa,$dias_pago_emp,$nomina_emp,$tiempo_trabajoI,$tiempo_trabajoII,$empresa_soli_ant,$domicilio_empresa_ant,$tel_emp_ant,$puesto_soli_ant,$ingresos_emp_ant,$jefe_emp_ant,$confirm_laboral_soli,$edocivil_soli,$vive_option,$automovil_option,$hijos_option,$tipo_trabajo_soli,$casa_option,$calle_option,$cliente_edad,$str_vivienda,$observciones,$nomb_informante,$puesto_informante,$atendido)=$rs_cons->fields;


if($rs_cons->fields)
{

	$sql = "SELECT Fecha,ID_Solicitud,Monto,Plazo,ID_Producto,Nombre,NombreI,Ap_paterno,Ap_materno,ID_Solicitud FROM solicitud WHERE ID_Solicitud = '$ID_soli' ";

	//debug($sql);
	$rs=$db->Execute($sql);
	list($fecha_sol,$folio,$monto,$plazo,$producto,$nombre_soli,$nombre_soli_dos,$ap_paterno_soli,$ap_materno_soli)=$rs->fields;




	 echo "<FORM Method='POST' ACTION='".$PHP_SELF."' NAME='solicitud' >\n";
	 echo"<INPUT TYPE=HIDDEN name='ID_Presoli'        	VALUE='$Param2'>";


	 $sql = "SELECT  Nombre, Direccion, Colonia, CP, Ciudad, Estado, Telefonos, FAX  FROM sucursales WHERE   ID_Sucursal= '$ID_SUC' ";
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
		<U><B><FONT SIZE=\"5\">Investigación domiciliaria</FONT></B></U> <BR>
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
            <TD ALIGN='left' ><U>$folio</U></TD>
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
echo"<div id='main'>
     <div class='basic' style='float:center; width:98%; margin-bottom:1em'  id='list1a'>";
echo"<a id='title_vivienda'>DATOS DE VIVIENDA</a>
     <div>";
echo"<IMG  BORDER=0 SRC='".$img_path."rightarrow.png' onclick='self.scroll(0,3500)'  ALT='editando'  align='right'  STYLE='cursor:pointer' />";


echo"<BR><BR>";
echo "<TABLE ALIGN='center' class='main' CELLSPACING=0 CELLPADDING=1 WIDTH='98%' BGCOLOR='gray' ID='small'>
      <TR><TD>
      <TABLE BORDER=0 CELLSPACING=3 CELLPADDING=1 WIDTH='100%' BGCOLOR='silver' ID='small' >
      <TR ALIGN='left' VALIGN='middle' >
      <TH ALIGN='center' ID='big' COLSPAN='2'><U><FONT COLOR='blue'> </FONT></U> </TH>
      </TR>\n";


echo" <TR ALIGN='left' VALIGN='middle' >
	  <TH ALIGN='center' ID='big' COLSPAN='2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<IMG  BORDER=0 SRC='".$img_path."info.png'  ALT='editando' STYLE='width:20px; height:20px;'  /><U><FONT size='2' > Domiciliaria</U></FONT>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</TH>
 </TR>";

echo" <TR ALIGN='left' VALIGN='middle' >
			<TH ALIGN='center' ID='big' COLSPAN='2'><BR> </TH>
	   </TR>";

echo"<TR ALIGN='left' VALIGN='middle'  >";

echo"       <TH ALIGN='right' ID='residencia'>Modalidad de vivienda:  </TH>
              <TD><FIELDSET style='WIDTH:70%'>$vive_option";

   echo"</FIELDSET ></TD></TR>";

echo"<TR ALIGN='left' VALIGN='middle' >
                             <TD colspan='4'><HR>";
echo"</TD></TR>";

echo"<TR ALIGN='left' VALIGN='middle'>
              <TH ALIGN='right' ID='residencia'>¿Tiene automóvil?:  </TH>
              <TD> <FIELDSET style='WIDTH:70%'>$automovil_option";

   echo"</FIELDSET></TD></TR>";

echo"<TR ALIGN='left' VALIGN='middle' >
                             <TD colspan='4'><HR>";
echo"</TD></TR>";


echo"</TD></TR>";
echo" <TR ALIGN='left' VALIGN='middle'>
      <TH ALIGN='right'1 WIDTH='30%'>  Estado Civil :  </TH>
      <TD ALIGN='left' ><FIELDSET style='WIDTH:60%'>$edocivil_soli ";

echo "</FIELDSET></TD> ";
echo "</TR>";



echo"<TR ALIGN='left' VALIGN='middle' >
                             <TD colspan='4'><HR>";
echo"</TD></TR>";

echo"<TR ALIGN='left' VALIGN='middle'>
              <TH ALIGN='right' ID='residencia'>¿Tiene hijos?:  </TH>
              <TD><FIELDSET style='WIDTH:70%'>$hijos_option";

   echo"</FIELDSET></TD></TR>";

echo"<TR ALIGN='left' VALIGN='middle' >
                             <TD colspan='4'><HR>";
echo"</TD></TR>";

echo"  <TR ALIGN='left' VALIGN='middle'>
      <TH ALIGN='right' VALIGN='top' WIDTH='30%'>  Tipo de trabajo :  </TH><TD ALIGN='left' ><FIELDSET style='WIDTH:70%'> $tipo_trabajo_soli";

echo "</FIELDSET></TD> ";
echo "</TR>";

echo"<TR ALIGN='left' VALIGN='middle' >
                             <TD colspan='4'><HR>";
echo"</TD></TR>";

echo"<TR ALIGN='left' VALIGN='middle'>
              <TH ALIGN='right' ID='residencia'>Interior de la casa:  </TH>
              <TD><FIELDSET style='WIDTH:70%'>$casa_option";

   echo"</FIELDSET></TD></TR>";

echo"<TR ALIGN='left' VALIGN='middle' >
                             <TD colspan='4'><HR>";
echo"</TD></TR>";

echo"<TR ALIGN='left' VALIGN='middle'>
              <TH ALIGN='right' ID='residencia'>Tipo de calle:  </TH>
              <TD><FIELDSET style='WIDTH:70%'>$calle_option";


   echo"</FIELDSET></TD></TR>";

echo"<TR ALIGN='left' VALIGN='middle' >
             <TD colspan='4'><HR>";
echo"</TD></TR>";


echo" <TR ALIGN='left' VALIGN='middle'>
      <TH ALIGN='right' width='20%'> Edad del cliente :        </TH>
      <TD ALIGN='left' ><FIELDSET style='WIDTH:70%'>$cliente_edad ";



echo"</FIELDSET></TD></TR>";

echo"</TD></TR>
</TABLE>
</TD>
</TR>
</TABLE>";

echo"<BR>";
echo"<BR>";



 $chk_recamara="--";
 $chk_refri="--";
 $chk_estereo="--";
 $chk_comedor="--";
 $chk_calentador="--";
 $chk_tv="--";
 $chk_sala="--";
 $chk_lavadora="--";
 $chk_tinaco="--";
 $chk_estufa="--";
 $chk_dvd="--";
 $chk_horno="--";
 $chk_compu="--";

$str_vivienda=explode(",",$str_vivienda);

 for( $i=0; $i<=count($str_vivienda); $i++)
 {
	if($str_vivienda[$i]=='Recamara')
	$chk_recamara="Si";

	if($str_vivienda[$i]=='Refrigerador')
	$chk_refri="Si";

	if($str_vivienda[$i]=='Estereo')
	$chk_estereo="Si";

	if($str_vivienda[$i]=='Comedor')
	$chk_comedor="Si";

	if($str_vivienda[$i]=='Calentador')
	$chk_calentador="Si";

	if($str_vivienda[$i]=='Televisión')
	$chk_tv="Si";

	if($str_vivienda[$i]=='Sala')
	$chk_sala="Si";

	if($str_vivienda[$i]=='Lavadora')
	$chk_lavadora="Si";

	if($str_vivienda[$i]=='Tinaco')
	$chk_tinaco="Si";

	if($str_vivienda[$i]=='Estufa')
	$chk_estufa="Si";

	if($str_vivienda[$i]=='DVD')
	$chk_dvd="Si";

	if($str_vivienda[$i]=='Horno de microondas')
	$chk_horno="Si";


	if($str_vivienda[$i]=='Computadora')
	$chk_compu="Si";

}

echo "<TABLE ALIGN='center' class='main' CELLSPACING=0 CELLPADDING=1 WIDTH='70%' STYLE='border:4px dotted #6699cc;' >
	 <TR><TD>
	 <TABLE BORDER=0 CELLSPACING=3 CELLPADDING=1 WIDTH='100%' BGCOLOR='white' ID='small' >
	 ";


	echo"<TR ALIGN='left' VALIGN='middle'>

	 <TD ALIGN='center' colspan='4'>
          <B> <FONT size='3'> OPCIONES PRESENTES EN LA VIVIENDA</FONT></B> &nbsp;&nbsp;<IMG SRC='".$sys_path."images/house.png' BORDER=0>
	 </TD>
	</TR> ";


echo"<TR ALIGN='left' VALIGN='middle'>

	<TD ALIGN='center' colspan='4'>
	<HR>
	</TD>
	</TR> ";




echo "<TR >


         <TD align='right'>
         <B>Recamara:</B>
         $chk_recamara
         </TD>



         <TD align='right'>
         <B>Refrigerador:</B>
         $chk_refri
         </TD>



         <TD align='right'>
         <B>Estereo:</B>
         $chk_estereo
         </TD>

		<TD align='right'>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</TD>

      </TR>";



echo "<TR >

		<TD align='right'>
		<B>Comedor:</B>
		$chk_comedor
		</TD>

		<TD align='right'>
		<B>Calentador:</B>
		 $chk_calentador
		</TD>


		<TD align='right'>
		<B>Televisión: </B>
		$chk_tv
		</TD>

		<TD align='right'>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</TD>

      </TR>";


      echo "<TR >

			<TD align='right'>
			<B>Sala:</B>
			 $chk_sala
			</TD>

			<TD align='right'>
			<B>Lavadora:</B>
			$chk_lavadora
			</TD>

			<TD align='right'>
			<B>Tinaco:</B>
			$chk_tinaco
			</TD>

	  		<TD align='right'>
	          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  		</TD>

      </TR>";

 echo "<TR >

			<TD align='right'>
			<B>Estufa:</B>
			 $chk_estufa
			</TD>

			<TD align='right'>
			<B>DVD:</B>
			$chk_dvd  <B>
			</TD>

			<TD align='right'>
			<B>Horno de microondas:</B>
			$chk_horno
			</TD>

	  		<TD align='right'>
	          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  		</TD>

      </TR>";


echo "<TR >

			<TD align='right'>
			<B>Computadora:</B>
			$chk_compu
			</TD>

			<TD align='right'>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			</TD>

			<TD align='right'>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			</TD>

	  		<TD align='right'>
	          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  		</TD>

      </TR>";



echo"<TR ALIGN='left' VALIGN='middle'>

	<TD ALIGN='center' colspan='4'>
	<HR>
	</TD>
	</TR> ";


echo"<TR ALIGN='left' VALIGN='middle'>

      	    <TD ALIGN='center' colspan='4'><B>Atendido por:</B> $atendido </TD>
      	</TR>";


	echo"</TABLE>
	</TR>
	</TD>
	</TABLE>";

echo"<BR>";



echo "<TABLE ALIGN='center' class='main' CELLSPACING=0 CELLPADDING=1 WIDTH='30%' BGCOLOR='gray' ID='small'>
	           <TR><TD>
	           <TABLE BORDER=0 CELLSPACING=3 CELLPADDING=1 WIDTH='100%' BGCOLOR='#cdcde5' ID='small' >
	           <TR ALIGN='left' VALIGN='middle' >
	           <TH ALIGN='center'  COLSPAN='2'><U><FONT COLOR='black'></FONT></U> </TH>
	           </TR>";




	  echo"<TR ALIGN='left' VALIGN='middle'>

	           <TD ALIGN='center' >

	          <BUTTON STYLE='cursor:pointer;' onclick=\"$('#title_inf').trigger('click'); window.scrollTo(0,0);\" ><B>&lt;&lt; ANTERIOR</B></BUTTON>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;



	          <BUTTON  STYLE='cursor:pointer;'  onclick=\"$('#title_coments').trigger('click'); window.scrollTo(0,0);\" ><B>SIGUIENTE &gt;&gt;</B></BUTTON>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;





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


  echo "<TABLE ALIGN='center' class='main' CELLSPACING=0 CELLPADDING=1 WIDTH='30%' BGCOLOR='gray' ID='small'>
	           <TR><TD>
	           <TABLE BORDER=0 CELLSPACING=3 CELLPADDING=1 WIDTH='100%' BGCOLOR='#cdcde5' ID='small' >
	           <TR ALIGN='left' VALIGN='middle' >
	           <TH ALIGN='center'  COLSPAN='2'><U><FONT COLOR='black'></FONT></U> </TH>
	           </TR>";



	  echo"<TR ALIGN='left' VALIGN='middle'>

	           <TD ALIGN='center' >



	         <BUTTON STYLE='cursor:pointer;'  onclick=\"$('#title_vivienda').trigger('click'); window.scrollTo(0,0);\"  ><B>&lt;&lt; ANTERIOR</B></BUTTON>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;



	          <BUTTON  STYLE='cursor:pointer;'  onclick=\"$('#HISTORIAL').trigger('click'); window.scrollTo(0,0);\" ><B>SIGUIENTE &gt;&gt;</B></BUTTON>


	           </TD>
	      </TR> ";

	    echo"</TABLE>
	          </TR>
	          </TD>
     </TABLE>";

echo"<BR>";


echo"</div>
<a ID='HISTORIAL' >HISTORIAL DE SUCESOS</a>
     <div >";
echo"<INPUT TYPE=HIDDEN name='ID_soli'   VALUE='".$ID_soli."'>";

echo "\n</FORM>\n\n";

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
            WHERE ID_Solicitud = '".$Param2."' ORDER BY  Fecha";


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

}

?>
