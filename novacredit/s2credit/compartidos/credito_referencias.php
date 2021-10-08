<?
$exit='true';
require($DOCUMENT_ROOT."/rutas.php");
 //verflujo();
?>


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

$sql_find="SELECT ID_Solicitud FROM fact_cliente
           LEFT JOIN clientes_datos on fact_cliente.Num_cliente = clientes_datos.Num_cliente
           WHERE id_factura='$factura' ";
$rs_find=$db->Execute($sql_find);

$Param1='ID_Solicitud';
$Param2=$rs_find->fields["ID_Solicitud"];



$sql = "SELECT Fecha,Monto,Plazo,ID_Promotor,ID_Producto,ID_contacto,Nombre,NombreI,Ap_paterno,Ap_materno,Fecha_nacimiento,SEXO,RFC,Hclave,Ident,Telefono,TelOf,CP,Colonia,Estado,Ciudad,Poblacion,Calle,Num,Interior,Ecalles,EcallesII,Hijos,Dependientes,Num_personas_dom,Vivienda,Nombre_complement,Telefono_complement,Monto_complement,CP_complement,Colonia_complement,Estado_complement,Ciudad_complement,Poblacion_complement,Calle_complement,Num_complement,Interior_complement,Ecalles_complement,Tiempo_year,Tiempo_meses,CP_anterior,Colonia_anterior,Estado_anterior,Ciudad_anterior,Poblacion_anterior,Calle_anterior,Num_anterior,Interior_anterior,Ecalles_anterior,EcallesII_anterior,ECivil,Conyuge_soli,Nombre_conyuge,NombreI_conyuge,Ap_paterno_conyuge,Ap_materno_conyuge,SEXO_conyuge,Regimen_conyuge,Ident_conyuge,Fecha_nacimiento_conyuge,RFC_conyuge,Hclave_conyuge,Telefono_casa_conyuge,Telefono_contacto_conyuge,Actividad_conyuge,Ingresos_cony,Otros_ingresos_cony,CP_conyuge,Colonia_conyuge,Ciudad_conyuge,Estado_conyuge,Poblacion_conyuge,Calle_conyuge,Num_conyuge,Interior_conyuge,
Ecalles_conyuge,Ecalles_conyugeII,Actividad_soli,Convenio,Empresa_soli,Direc_empresa_soli,Telefono_empresa,Extension_tel,Puesto,IMSS,Ingresos_soli,Otros_ingresos_soli,Jefe_soli,Patron,Dep_empresa,Dias_pago,Num_nomina,Tiempo_trabajoI,Tiempo_trabajoII,Empresa_soli_anterior,Direc_empresa_soli_anterior,Telefono_empresa_anterior,Puesto_anterior,Ingresos_soli_anterior,Jefe_soli_anterior,ID_Solicitud,Folio FROM solicitud WHERE $Param1 = '$Param2' ";
$rs=$db->Execute($sql);


list($fecha,$monto,$plazo,$promotor,$producto,$medio_publicidad,$nombre_soli,$nombre_soli_dos,$ap_paterno_soli,$ap_materno_soli,$fnacimiento,$sexo,$rfc_soli,$homo_rfc_soli,$iden_soli,$telcasa_soli,$telcontacto_soli,$cp_soli,$colonia_soli,$estado_soli,$ciudad_soli,$poblacion_soli,$calle_soli,$num_soli,$num_int_soli,$entre_calles,$entre_callesII,$hijos_option,$dependientes_soli,$perso_dependientes_soli,$vive_option,$nombre_complent,$tel_complent,$monto_complent,$cp_soli_complement,$colonia_soli_complement,$estado_soli_complement,$ciudad_soli_complement,$poblacion_soli_complement,$calle_soli_complement,$num_soli_complement,$num_int_soli_complement,$entre_calles_complement,$tiempo_year,$tiempo_meses,$cp_soli_anterior,$colonia_soli_anterior,$estado_soli_anterior,$ciudad_soli_anterior,$poblacion_soli_anterior,$calle_soli_anterior,$num_soli_anterior,$num_int_soli_anterior,$entre_calles_anterior,$entre_calles_anteriorII,$edocivil_soli,$cosol_soli,$conyuge_nomb1,$conyuge_nomb2,$conyuge_app,$conyuge_apm,$sexo_cony,$regimen_conyugal,$iden_soli_cony,$fnacimiento_cony,$rfc_cony,$homo_rfc_cony,$telcasacony_soli,$teloficinacony_soli,$actividad_cony,$ingresos_cony,$otros_ingresos_cony,$cp_soli_cony,$colonia_soli_cony,$ciudad_soli_cony,$estado_soli_cony,$poblacion_soli_cony,$calle_soli_cony,$num_soli_cony,$num_int_soli_cony,$entre_calles_cony,$entre_calles_conyII,$actividad_econom_soli,$convenio_emp,$empresa_soli,$domicilio_empresa,$tel_emp,$telcontacto_extension_soli,$puesto_soli,$imss_emp,$ingresos_emp,$otros_ingresos_emp,$jefe_emp,$patron_emp,$dep_empresa,$dias_pago_emp,$nomina_emp,$tiempo_trabajoI,$tiempo_trabajoII,$empresa_soli_ant,$domicilio_empresa_ant,$tel_emp_ant,$puesto_soli_ant,$ingresos_emp_ant,$jefe_emp_ant,$ID_soli,$Folio)=$rs->fields;


?>

  <!---Datos generales del solicitante--->

<?


echo "<TABLE ALIGN='center' class='main' CELLSPACING=0 CELLPADDING=1 WIDTH='50%' STYLE='border:4px dotted #6699cc;' >
	 <TR><TD>
	 <TABLE BORDER=0 CELLSPACING=3 CELLPADDING=1 WIDTH='100%' BGCOLOR='white' ID='small' >
	 <TR ALIGN='left' VALIGN='middle' >
	 <TH ALIGN='center'  COLSPAN='2'><U><FONT COLOR='black'></FONT></U> </TH>
	 </TR>";


	echo"<TR ALIGN='left' VALIGN='middle'>

	 <TD ALIGN='center' >
          <B><font size='4'>Folio # $Folio </font></B>
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



echo"<div id='main'>
     <div class='basic' style='float:center; width:98%; margin-bottom:1em'  id='list1a'>
     <a align='center' id='title_inf'><IMG  BORDER=0 SRC='".$img_path."indiv.gif'  ALT='editando'   />REFERENCIAS PERSONALES</a>
     <div>";

echo"<IMG  BORDER=0 SRC='".$img_path."rightarrow.png' onclick='self.scroll(0,980)'  ALT='editando'  align='right'  STYLE='cursor:pointer' />";

echo"<BR>";

echo " <TABLE ALIGN='CENTER'  class='main' CELLSPACING=0 CELLPADDING=0 WIDTH='60%' BGCOLOR='silver' ID='tblId'>  \n\n";
//Reordenamos el campo Num para no tener problemas al borrar

 $sql="SELECT Num FROM solicitud_ii WHERE ID_Solicitud =  '$ID_soli'  ";
 $rs = $db->Execute($sql);
 $contador=1;
 While(!$rs->EOF)
 {
  $temp=$rs->fields[0];
  $sql="UPDATE solicitud_ii SET Num =  '$contador' WHERE ID_solicitud = '$ID_soli' and Num = '$temp' ";
  $db->Execute($sql);
  $contador++;
  $rs->MoveNext() ;
 }
//FIN

$sql="SELECT NombreI,NombreII,Ap_paterno,Ap_materno,RFC,CP,Calle,Parentesco,Telefono,Colonia,Estado,Ciudad,Poblacion,Numero FROM solicitud_ii
                 WHERE ID_Solicitud = '$ID_soli'  ";
//debug($sql);
$rs = $db->Execute($sql);

$sql="SELECT COUNT(*) FROM solicitud_ii
                 WHERE ID_Solicitud = '$ID_soli'  ";
$rs_aux = $db->Execute($sql);
$num_count = $rs_aux->fields[0];

if($num_count > 0)
{
 $ref=1;
While(!$rs->EOF)
{
 $nombre_persoI=$rs->fields[0];
 $nombre_persoI=($nombre_persoI!='')?($nombre_persoI):('--- ---');

 $nombre_persoII=$rs->fields[1];
 $nombre_persoII=($nombre_persoII!='')?($nombre_persoII):('--- ---');

 $ap_paterno_perso=$rs->fields[2];
 $ap_paterno_perso=($ap_paterno_perso!='')?($ap_paterno_perso):('--- ---');

 $ap_matenro_perso=$rs->fields[3];
 $ap_matenro_perso=($ap_matenro_perso!='')?($ap_matenro_perso):('--- ---');

 $rfc_perso=$rs->fields[4];
 $rfc_perso=($rfc_perso!='')?($rfc_perso):('--- ---');

 $cp_perso=$rs->fields[5];
 $cp_perso=($cp_perso!='')?($cp_perso):('--- ---');


 $calle_perso=$rs->fields[6];
 $calle_perso=($calle_perso!='')?($calle_perso):('--- ---');


 $parentesco_perso=$rs->fields[7];
 $parentesco_perso=($parentesco_perso!='')?($parentesco_perso):('--- ---');



 $telefono_perso=$rs->fields[8];
 $telefono_perso=($telefono_perso!='')?($telefono_perso):('--- ---');


 $colonia_perso=$rs->fields[9];
 $colonia_perso=($colonia_perso!='')?($colonia_perso):('--- ---');


$estado_perso=$rs->fields[10];
 $estado_perso=($estado_perso!='')?($estado_perso):('--- ---');

$ciudad_perso=$rs->fields[11];
$ciudad_perso=($ciudad_perso!='')?($ciudad_perso):('--- ---');


$poblacion_perso=$rs->fields[12];
$poblacion_perso=($poblacion_perso!='')?($poblacion_perso):('--- ---');

$numero_perso=$rs->fields[13];
$numero_perso=($numero_perso!='')?($numero_perso):('--- ---');


/*<TR ALIGN='left' VALIGN='middle'>
	<TH ALIGN='right'> RFC :&nbsp;</TD>
	<TD ALIGN='left' >$rfc_perso</TD>
	</TR>*/

 echo"



           <TD>
      <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH='100%'  ID='small'>



      <TR ALIGN='left' VALIGN='middle'>
<TR ALIGN='center' VALIGN='middle' Bgcolor='#a2a2a2'>
                     	<TH ALIGN='center'>&nbsp; </TH>
                     	<TD ALIGN='center' ><B> <FONT size='3'> $ref) REFERENCIA PERSONAL.</FONT> &nbsp;&nbsp;<IMG SRC='".$sys_path."images/user_blue.png' BORDER=0></B></TD>
	</TR>

	<TR><TD COLSPAN='8'><HR><BR></TD></TR>

	<TR ALIGN='left' VALIGN='middle'>
	<TH ALIGN='right'> Primer nombre:&nbsp;</TD>
	<TD ALIGN='left' >$nombre_persoI </TD>
	</TR>
	<TR ALIGN='left' VALIGN='middle'>
	<TH ALIGN='right' > Segundo nombre:&nbsp;</TD>
	<TD ALIGN='left' >$nombre_persoII </TD>
	</TR>
        <TR ALIGN='left' VALIGN='middle'>
	<TR ALIGN='left' VALIGN='middle'>
	<TH ALIGN='right'> Ap. Paterno:&nbsp;</TD>
	<TD ALIGN='left' >$ap_paterno_perso </TD>
	</TR>
	<TR ALIGN='left' VALIGN='middle'>
	<TH ALIGN='right'> Ap. Materno:&nbsp;</TD>
	<TD ALIGN='left' >$ap_matenro_perso </TD>
	</TR>

	<TR ALIGN='left' VALIGN='middle'>
	<TH ALIGN='right'> Relación :&nbsp;</TD>
	<TD ALIGN='left' >$parentesco_perso</TD>
	</TR>

	<TR ALIGN='left' VALIGN='middle'>
		<TH ALIGN='right'>CP :&nbsp;</TD>
		<TD ALIGN='left' >$cp_perso</TD>
	</TR>

	<TR ALIGN='left' VALIGN='middle'>
		<TH ALIGN='right'> Colonia :&nbsp;</TD>
		<TD ALIGN='left' >$colonia_perso</TD>
	</TR>

	<TR ALIGN='left' VALIGN='middle'>
		<TH ALIGN='right'> Estado :&nbsp;</TD>
		<TD ALIGN='left' >$estado_perso</TD>
	</TR>

	<TR ALIGN='left' VALIGN='middle'>
		<TH ALIGN='right'> Ciudad :&nbsp;</TD>
		<TD ALIGN='left' >$ciudad_perso</TD>
	</TR>

	<TR ALIGN='left' VALIGN='middle'>
		<TH ALIGN='right'> Población/Municipio/Delegación :&nbsp;</TD>
		<TD ALIGN='left' >$poblacion_perso</TD>
	</TR>

	<TR ALIGN='left' VALIGN='middle'>
		 <TH ALIGN='right'> Calle :&nbsp;</TD>
		 <TD ALIGN='left' >$calle_perso</TD>
	</TR>

	<TR ALIGN='left' VALIGN='middle'>
			 <TH ALIGN='right'> Número :&nbsp;</TD>
			 <TD ALIGN='left' >$numero_perso</TD>
	</TR>
	<TR ALIGN='left' VALIGN='middle'>
		 <TH ALIGN='right' width='20%'> Teléfono :&nbsp;</TD>
		 <TD ALIGN='left' > $telefono_perso</TD>
	</TR>


      </TABLE>
      </TR>
      </TD>
       </TR>\n";
 $ref++;
 $rs->MoveNext() ;
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
//--------------------------------------------------------------------------------------------------------------------
echo"  </TD>
      </TR>
      <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH='100%' ID='small'>
      <TR ALIGN='left' VALIGN='middle'>
      </TR>
      </TABLE>";
echo"</TABLE>
      <TABLE ALIGN='CENTER' BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH='90%' >

      </TABLE>
          <BR>";



echo "\n</FORM>\n\n";


echo"</div>";



echo"</DIV></DIV>";



?>

</BODY>
</HTML>


















































