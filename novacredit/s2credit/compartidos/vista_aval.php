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
	<U><B><FONT SIZE=\"5\">Solicitud Confidencial de Crédito</FONT></B></U> <BR>
	<B><FONT SIZE=\"5\">".$sucursal."</FONT></B> <BR>
	<B><FONT SIZE=\"1\">".$direccionsuc."<BR> Tels.".$telsuc ."</FONT></B> <BR>
       </TD>";
echo "<TD ROWSPAN='2'>";



?>


        <!---Control superior--->
<?

$sql = "SELECT Fecha,Monto,Plazo,ID_Promotor,ID_Producto,ID_contacto,Nombre,NombreI,Ap_paterno,Ap_materno,Fecha_nacimiento,SEXO,RFC,Hclave,Ident,Telefono,TelOf,CP,Colonia,Estado,Ciudad,Poblacion,Calle,Num,Interior,Ecalles,EcallesII,Hijos,Dependientes,Num_personas_dom,Vivienda,Nombre_complement,Telefono_complement,Monto_complement,CP_complement,Colonia_complement,Estado_complement,Ciudad_complement,Poblacion_complement,Calle_complement,Num_complement,Interior_complement,Ecalles_complement,Tiempo_year,Tiempo_meses,CP_anterior,Colonia_anterior,Estado_anterior,Ciudad_anterior,Poblacion_anterior,Calle_anterior,Num_anterior,Interior_anterior,Ecalles_anterior,EcallesII_anterior,ECivil,Nombre_conyuge,NombreI_conyuge,Ap_paterno_conyuge,Ap_materno_conyuge,SEXO_conyuge,Regimen_conyuge,Ident_conyuge,Fecha_nacimiento_conyuge,RFC_conyuge,Hclave_conyuge,Telefono_casa_conyuge,Telefono_contacto_conyuge,Actividad_conyuge,CP_conyuge,Colonia_conyuge,Ciudad_conyuge,Estado_conyuge,Poblacion_conyuge,Calle_conyuge,Num_conyuge,Interior_conyuge,
Ecalles_conyuge,Ecalles_conyugeII,Actividad_soli,Convenio,Empresa_soli,Direc_empresa_soli,Telefono_empresa,Extension_tel,Puesto,IMSS,Ingresos_soli,Otros_ingresos_soli,Jefe_soli,Patron,Dep_empresa,Dias_pago,Num_nomina,Tiempo_trabajoI,Tiempo_trabajoII,Empresa_soli_anterior,Direc_empresa_soli_anterior,Telefono_empresa_anterior,Puesto_anterior,Ingresos_soli_anterior,Jefe_soli_anterior,ID_Aval FROM aval WHERE $Param1 = '$Param2' ";
 $rs=$db->Execute($sql);


list($fecha,$monto,$plazo,$promotor,$producto,$medio_publicidad,$nombre_soli,$nombre_soli_dos,$ap_paterno_soli,$ap_materno_soli,$fnacimiento,$sexo,$rfc_soli,$homo_rfc_soli,$iden_soli,$telcasa_soli,$telcontacto_soli,$cp_soli,$colonia_soli,$estado_soli,$ciudad_soli,$poblacion_soli,$calle_soli,$num_soli,$num_int_soli,$entre_calles,$entre_callesII,$hijos_option,$dependientes_soli,$perso_dependientes_soli,$vive_option,$nombre_complent,$tel_complent,$monto_complent,$cp_soli_complement,$colonia_soli_complement,$estado_soli_complement,$ciudad_soli_complement,$poblacion_soli_complement,$calle_soli_complement,$num_soli_complement,$num_int_soli_complement,$entre_calles_complement,$tiempo_year,$tiempo_meses,$cp_soli_anterior,$colonia_soli_anterior,$estado_soli_anterior,$ciudad_soli_anterior,$poblacion_soli_anterior,$calle_soli_anterior,$num_soli_anterior,$num_int_soli_anterior,$entre_calles_anterior,$entre_calles_anteriorII,$edocivil_soli,$conyuge_nomb1,$conyuge_nomb2,$conyuge_app,$conyuge_apm,$sexo_cony,$regimen_conyugal,$iden_soli_cony,$fnacimiento_cony,$rfc_cony,$homo_rfc_cony,$telcasacony_soli,$teloficinacony_soli,$actividad_cony,$cp_soli_cony,$colonia_soli_cony,$ciudad_soli_cony,$estado_soli_cony,$poblacion_soli_cony,$calle_soli_cony,$num_soli_cony,$num_int_soli_cony,$entre_calles_cony,$entre_calles_conyII,$actividad_econom_soli,$convenio_emp,$empresa_soli,$domicilio_empresa,$tel_emp,$telcontacto_extension_soli,$puesto_soli,$imss_emp,$ingresos_emp,$otros_ingresos_emp,$jefe_emp,$patron_emp,$dep_empresa,$dias_pago_emp,$nomina_emp,$tiempo_trabajoI,$tiempo_trabajoII,$empresa_soli_ant,$domicilio_empresa_ant,$tel_emp_ant,$puesto_soli_ant,$ingresos_emp_ant,$jefe_emp_ant,$ID_aval)=$rs->fields;

echo "<TABLE ALIGN='center' class='main' CELLSPACING=0 CELLPADDING=1 WIDTH='95%' BGCOLOR='gray' ID='small'>
      <TR><TD>
      <TABLE ALIGN='right' BORDER=0 CELLSPACING=2 CELLPADDING=1 WIDTH='100%' BGCOLOR='silver' ID='small'>";

$fecha=($fecha!='')?($fecha):('--- ---');


echo "<TR ALIGN=\"left\" VALIGN=\"middle\">";
echo "<TH ALIGN=\"right\" >  Fecha de solicitud     : </TH>";

if($D<=9) $D='0'.$D;
if($M<=9) $M='0'.$M;
if (empty($Ddia)) $Ddia=$D;
if (empty($Mmes)) $Mmes=$M;
if (empty($Aano)) $Aano=$Y;


echo "<TD ALIGN=\"left\" >$fecha";
echo "</TR>";

$sql = "SELECT solicitud_aval.ID_Solicitud  ,
CONCAT(solicitud.Nombre,' ',solicitud.NombreI,' ',solicitud.Ap_paterno,' ',solicitud.Ap_materno)
FROM solicitud_aval
left join solicitud on solicitud_aval.ID_Solicitud = solicitud.ID_Solicitud
WHERE solicitud_aval.ID_Aval='$Param2'";
$rs=$db->Execute($sql);


echo" <TR ALIGN='left' VALIGN='middle'>
      <TD ALIGN='right' ><BR><B>Solicitud vinculada:</B></TD></TR>";
echo "</TD> </TR>\n";

echo" <TR ALIGN='left' VALIGN='middle'>
      <TH ALIGN='right' ><IMG  BORDER=0 SRC='".$img_path."indiv.gif'  ALT='editando'/></TH>
      <TD ALIGN='left' ><H3>Folio: <U>".$rs->fields[0]."</U></H3></TD></TR>";
echo "</TD> </TR>\n";

echo" <TR ALIGN='left' VALIGN='middle'>
      <TH ALIGN='right' ></TH>
      <TD ALIGN='left' ><H3><U>".$rs->fields[1]."</U></H3></TD></TR>";
echo "</TD> </TR>\n";


/*$monto=($monto!='')?($monto):('--- ---');
$plazo=($plazo!='')?($plazo):('--- ---');

if(!$monto) $monto='0.0';

echo"      <TR ALIGN='left' VALIGN='middle'>
      <TH ALIGN='right' >     Monto Solicitado : </TH>
      <TD ALIGN='left' > $monto</TD></TR>";
echo"      <TR ALIGN='left' VALIGN='middle'>
      <TH ALIGN='right' >     Plazo (meses) : </TH>
      <TD ALIGN='left' > $plazo</TD></TR>";*/


echo "</TD> </TR>\n";
echo "</TABLE>";
echo "</TD>
      </TR>
      </TABLE>";
echo "</TD>";
echo "</TR></TABLE>";

echo"<CENTER><H2>CONSULTA DE AVAL.</H2></CENTER>";

?>
   <!---Datos generales del solicitante--->
<?

echo"<div id='main'>
     <div class='basic' style='float:center; width:98%; margin-bottom:1em'  id='list1a'>
     <a align='center' id='title_inf'><IMG  BORDER=0 SRC='".$img_path."user.png'  ALT='editando' STYLE='width:20px;height:20px;'  /> INFORMACIÓN GENERAL</a>
     <div>";

echo"<IMG  BORDER=0 SRC='".$img_path."rightarrow.png' onclick='self.scroll(0,700)'  ALT='editando'  align='right'  STYLE='cursor:pointer' />";

echo"<BR><BR>";
echo "<TABLE ALIGN='center' class='main' CELLSPACING=0 CELLPADDING=1 WIDTH='90%' BGCOLOR='gray' ID='small'>
      <TR><TD>
      <TABLE BORDER=0 CELLSPACING=3 CELLPADDING=1 WIDTH='100%' BGCOLOR='silver' ID='small' >
      <TR ALIGN='left' VALIGN='middle' >
      <TH ALIGN='center' ID='big' COLSPAN='2'><U><FONT COLOR='blue'> </FONT></U> </TH>
      </TR>\n";

/*
$sql = "SELECT Nombre FROM  cat_productosfinancieros WHERE ID_Producto = '$producto' ";
	$rs=$db->Execute($sql);
	$producto=$rs->fields[0];


echo" <TR ALIGN='left' VALIGN='middle'>
      <TH ALIGN='right' width='20%' > Producto financiero :        </TH>
      <TD ALIGN='left'> $producto ";

echo"</TD></TR>";*/

/*$sql = "SELECT nombre FROM promotores where  num_promo = '$promotor'";
$rs=$db->Execute($sql);
$promotor=($rs->fields[0]!='')?($rs->fields[0]):('--- ---');

echo" <TR ALIGN='left' VALIGN='middle'>
      <TH ALIGN='right' >     Promotor :        </TH>
      <TD ALIGN='left' >$promotor ";

echo "</TD></TR>\n";*/


/***QUERY*****/
$sql = "SELECT Descripcion FROM cat_contacto_promocion WHERE ID_contacto ='$medio_publicidad' ";
$rs=$db->Execute($sql);
/***FIN QUERY*****/


$medio_publicidad=($rs->fields[0]!='')?($rs->fields[0]):('---');

echo" <TR ALIGN='left' VALIGN='middle'>
      <TH ALIGN='right' > ¿Cómo se enteró de nosotros? :        </TH>
      <TD ALIGN='left' > $medio_publicidad";
echo"</TD></TR>";




$nombre_soli=($nombre_soli!='')?($nombre_soli):('--- ---');
$nombre_soli_dos=($nombre_soli_dos!='')?($nombre_soli_dos):('--- ---');
$ap_paterno_soli=($ap_paterno_soli!='')?($ap_paterno_soli):('--- ---');
$ap_materno_soli=($ap_materno_soli!='')?($ap_materno_soli):('--- ---');




echo "<TR ALIGN='left' VALIGN='middle'>
         <TH ALIGN='right'> Primer nombre: </TH>
         <TD ALIGN='left' >$nombre_soli</TD>
      </TR>

      <TR ALIGN='left' VALIGN='middle'>
               <TH ALIGN='right'> Segundo nombre:  </TH>
               <TD ALIGN='left' >$nombre_soli_dos</TD>
      </TR>

      <TR ALIGN='left' VALIGN='middle'>
         <TH ALIGN='right' >Ap. Paterno : </TH>
         <TD ALIGN='left' >$ap_paterno_soli</TD>
      </TR>
      <TR ALIGN='left' VALIGN='middle'>
         <TH ALIGN='right' >Ap. Materno : </TH>
         <TD ALIGN='left' >$ap_materno_soli</TD>
      </TR>";


     $sexo=($sexo=='M')?("Masculino"):("Femenino");


      echo "
            <TR ALIGN='left' VALIGN='middle' ID='sexo'>
                 <TH ALIGN='right' >Sexo  : </TH>
                 <TD ALIGN='left' > $sexo";


$iden_soli=($iden_soli!='')?($iden_soli):('--- ---');
$fnacimiento= substr($fnacimiento,8)."/".substr($fnacimiento,-5,2)."/".substr($fnacimiento,-10,4);



$rfc_soli=($rfc_soli!='')?($rfc_soli):('--- ---');
$homo_rfc_soli=($homo_rfc_soli!='')?($homo_rfc_soli):('--- ---');


echo"
     </TD>
     </TR>";

echo"<TR ALIGN='left' VALIGN='middle'>
     <TH ALIGN='right' >Identificación :</TH>
     <TD  ALIGN='left' >$iden_soli ";

echo"</TD></TR>";


echo"    <TR ALIGN='left' VALIGN='middle'>
         <TH ALIGN='right'>Fecha de nacimiento: </TH>
         <TD ALIGN='left' >
         $fnacimiento

           </TD>
     </TR>

     <TR ALIGN='left' VALIGN='middle'>
         <TH ALIGN='right' > RFC: </TH>
         <TD ALIGN='left' >$rfc_soli-$homo_rfc_soli</TD>
     </TR>";

echo" <TR ALIGN='left' VALIGN='middle' >
                  <TH ALIGN='center' ID='big' COLSPAN='2'><U><BR></TH>
          </TR>
          <TR ALIGN='left' VALIGN='middle' >
              <TH ALIGN='center' ID='big' COLSPAN='2'><FONT COLOR='blue'>Domicilio  vivienda actual </FONT><BR><HR></TH>
          </TR>
          <TR ALIGN='left' VALIGN='middle' >
                  <TH ALIGN='center' ID='big' COLSPAN='2'><U><BR></TH>
     </TR>";



$cp_soli=($cp_soli!='')?($cp_soli):('--- ---');

  echo"<TR ALIGN='left' VALIGN='middle'>
           <TH ALIGN='right'> C.P.: </TH>
           <TD ALIGN='left' > $cp_soli

           </TD>
       </TR> ";

$colonia_soli=($colonia_soli!='')?($colonia_soli):('--- ---');


  echo"<TR ALIGN='left' VALIGN='middle'>
       <TH ALIGN='right'> Colonia: </TH>
       <TD ALIGN='left' > $colonia_soli";


  echo "</TD>
        </TR>\n";

  //--------------------------------------------------------------------
  // Estado
  $estado_soli=($estado_soli!='')?($estado_soli):('--- ---');


  echo"<TR ALIGN='left' VALIGN='middle'>
  	<TH ALIGN='right'> Estado :  </TH>
  	<TD ALIGN='left' > $estado_soli";

  echo "</TD>
        </TR>\n";
  //--------------------------------------------------------------------
  // Ciudad
  $ciudad_soli=($ciudad_soli!='')?($ciudad_soli):('--- ---');


  echo"<TR ALIGN='left' VALIGN='middle'>
           <TH ALIGN='right'>Ciudad :  </TH>
           <TD ALIGN='left' >$ciudad_soli";

  echo "</TD>
        </TR>\n";

  //--------------------------------------------------------------------
  // Poblacion o Municipio o Delegación.

  $poblacion_soli=($poblacion_soli!='')?($poblacion_soli):('--- ---');

  echo " <TR ALIGN='left' VALIGN='middle'>
            <TH ALIGN='right'> Población/Municipio/Delegación: </TH>
            <TD ALIGN='left' > $poblacion_soli\n";

  echo "</TD>
        </TR> ";


  $calle_soli=($calle_soli!='')?($calle_soli):('--- ---');
  $num_soli=($num_soli!='')?($num_soli):('--- ---');
  $num_int_soli=($num_int_soli!='')?($num_int_soli):('--- ---');
  $entre_calles=($entre_calles!='')?($entre_calles):('--- ---');
  $entre_callesII=($entre_callesII!='')?($entre_callesII):('--- ---');



  echo "<TR ALIGN='left' VALIGN='middle'>
            <TH ALIGN='right' > Calle: </TH>
            <TD ALIGN='left'>$calle_soli </TD>
        </TR>
      <TR ALIGN='left' VALIGN='middle'>
           <TH ALIGN='right' >No. Exterior  : </TH>
           <TD ALIGN='left'>$num_soli </TD>
      </TR>
      <TR ALIGN='left' VALIGN='middle'>
           <TH ALIGN='right' >No. Interior  : </TH>
           <TD ALIGN='left'>$num_int_soli </TD>
      </TR>

      <TR ALIGN='left' VALIGN='middle'>
           <TH ALIGN='right' width='20%'>Entre las calles de :  </TH>
           <TD ALIGN='left' > $entre_calles <B>y</B> $entre_callesII</TD>
      </TR> ";



$tiempo_year=($tiempo_year!='')?($tiempo_year):('--- ---');
$tiempo_meses=($tiempo_meses!='')?($tiempo_meses):('--- ---');

echo"<TR ALIGN='left' VALIGN='middle'>
           <TH ALIGN='right'>Antigüedad en domicilio : </TH>
           <TD>$tiempo_year años, $tiempo_meses meses";



       echo"
            </TD>
            </TR>";


echo "<TBODY ID='vive_anterior'>\n";
echo" <TR ALIGN='left' VALIGN='middle' >
          <TH ALIGN='center' ID='big' COLSPAN='2'><BR> </TH>
     </TR>";

echo" <TR ALIGN='left' VALIGN='middle' >
          <TH ALIGN='center' ID='big' COLSPAN='2'><FONT COLOR='blue'>Domicilio anterior </FONT><BR><HR></TH>
     </TR>";

 $cp_soli_anterior=($cp_soli_anterior!='')?($cp_soli_anterior):('--- ---');

echo"<TR ALIGN='left' VALIGN='middle'>
         <TH ALIGN='right'> C.P.: </TH>
         <TD ALIGN='left' > $cp_soli_anterior

         </TD>
     </TR> ";



//--------------------------------------------------------------------
// Colonia
 $colonia_soli_anterior=($colonia_soli_anterior!='')?($colonia_soli_anterior):('--- ---');

echo"<TR ALIGN='left' VALIGN='middle'>
     <TH ALIGN='right'> Colonia: </TH>
     <TD ALIGN='left' > $colonia_soli_anterior";


echo "</TD>
      </TR>\n";

//--------------------------------------------------------------------
// Estado
 $estado_soli_anterior=($estado_soli_anterior!='')?($estado_soli_anterior):('--- ---');

echo"<TR ALIGN='left' VALIGN='middle'>
	<TH ALIGN='right'> Estado :  </TH>
	<TD ALIGN='left' > $estado_soli_anterior";
echo "</TD>
      </TR>\n";
//--------------------------------------------------------------------
// Ciudad

 $ciudad_soli_anterior=($ciudad_soli_anterior!='')?($ciudad_soli_anterior):('--- ---');

echo"<TR ALIGN='left' VALIGN='middle'>
         <TH ALIGN='right'>Ciudad :  </TH>
         <TD ALIGN='left' >$ciudad_soli_anterior";
echo "</TD>
      </TR>\n";

//--------------------------------------------------------------------
// Poblacion o Municipio o Delegación.
 $poblacion_soli_anterior=($poblacion_soli_anterior!='')?($poblacion_soli_anterior):('--- ---');

echo " <TR ALIGN='left' VALIGN='middle'>
          <TH ALIGN='right'> Población/Municipio/Delegación: </TH>
          <TD ALIGN='left' >$poblacion_soli_anterior \n";
echo "</TD>
      </TR> ";

$calle_soli_anterior=($calle_soli_anterior!='')?($calle_soli_anterior):('--- ---');
$num_soli_anterior=($num_soli_anterior!='')?($num_soli_anterior):('--- ---');
$num_int_soli_anterior=($num_int_soli_anterior!='')?($num_int_soli_anterior):('--- ---');
$entre_calles_anterior=($entre_calles_anterior!='')?($entre_calles_anterior):('--- ---');


echo "<TR ALIGN='left' VALIGN='middle'>
          <TH ALIGN='right' > Calle: </TH>
          <TD ALIGN='left'>$calle_soli_anterior        </TD>
      </TR>
    <TR ALIGN='left' VALIGN='middle'>
         <TH ALIGN='right' >No. Exterior  : </TH>
         <TD ALIGN='left'>$num_soli_anterior  </TD>
    </TR>
    <TR ALIGN='left' VALIGN='middle'>
         <TH ALIGN='right' >No. Interior  : </TH>
         <TD ALIGN='left'>$num_int_soli_anterior   </TD>
    </TR>

    <TR ALIGN='left' VALIGN='middle'>
         <TH ALIGN='right'>Entre las calles de :  </TH>
         <TD ALIGN='left' >$entre_calles_anterior   &nbsp;&nbsp;<B>y</B>&nbsp;&nbsp;$entre_calles_anteriorII      </TD>
    </TR> ";


echo "</TBODY>\n";





  echo"          </TABLE>
               </TD>
               </TR>
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

		          <BUTTON   STYLE='cursor:not-allowed;' DISABLED><B>&lt;&lt; ANTERIOR</b></BUTTON>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

		           <BUTTON   STYLE='cursor:pointer;'  onclick=\"$('#title_dom').trigger('click'); window.scrollTo(0,0);\" title='Siguiente panel' ><B>SIGUIENTE &gt;&gt;</B></BUTTON>

		           </TD>
		      </TR> ";

		    echo"</TABLE>
		          </TR>
		          </TD>
     </TABLE>";

echo"<BR>";

     echo"</div>";
	 echo"<a id='title_dom'>DOMICILIO  VIVIENDA ACTUAL</a>
     <div>";
echo"<IMG  BORDER=0 SRC='".$img_path."rightarrow.png' onclick='self.scroll(0,1800)'  ALT='editando'  align='right'  STYLE='cursor:pointer' />";


echo"<BR><BR>";
echo "<TABLE ALIGN='center' class='main' CELLSPACING=0 CELLPADDING=1 WIDTH='90%' BGCOLOR='gray' ID='small'>
      <TR><TD>
      <TABLE BORDER=0 CELLSPACING=3 CELLPADDING=1 WIDTH='100%' BGCOLOR='silver' ID='small' >
      <TR ALIGN='left' VALIGN='middle' >
      <TH ALIGN='center' ID='big' COLSPAN='2'><U><FONT COLOR='blue'> </FONT></U> </TH>
      </TR>\n";

$telcasa_soli=($telcasa_soli!='')?($telcasa_soli):('--- ---');
$telcontacto_soli=($telcontacto_soli!='')?($telcontacto_soli):('--- ---');


echo"
       <TR ALIGN='left' VALIGN='middle'>
         <TH ALIGN='right' width='20%'>  Teléfono casa: </TH>
         <TD ALIGN='left' >$telcasa_soli</TD>
     </TR>

     <TR ALIGN='left' VALIGN='middle'>
              <TH ALIGN='right'>  Teléfono de contacto: </TH>
              <TD ALIGN='left' >$telcontacto_soli</TD>
     </TR>

    ";



 $dependientes_soli=($dependientes_soli!='')?($dependientes_soli):('--- ---');
 $perso_dependientes_soli=($perso_dependientes_soli!='')?($perso_dependientes_soli):('--- ---');
 $hijos_option=($hijos_option!='')?($hijos_option):('--- ---');

echo"<TR ALIGN='left' VALIGN='middle'>
              <TH ALIGN='right' ID='residencia'>¿Tiene hijos?:  </TH>
              <TD>$hijos_option ";

echo"</TD></TR>";



 echo"<TR ALIGN='left' VALIGN='middle'>
                <TH ALIGN='right'>  Dependientes económicos: </TH>
                <TD ALIGN='left' >$dependientes_soli </TD>
      </TR>

 <TR ALIGN='left' VALIGN='middle'>
                <TH ALIGN='right'>  Personas que habitan en el domicilio: </TH>
                <TD ALIGN='left' >$perso_dependientes_soli</TD>
     </TR>";

 $vive_option=($vive_option!='')?($vive_option):('--- ---');

  echo"<TR ALIGN='left' VALIGN='middle'>
              <TH ALIGN='right' ID='residencia'>Residencia:  </TH>
              <TD>$vive_option";


   echo"</TD></TR>";

echo "<TBODY ID='vive_datos'   >\n";
echo" <TR ALIGN='left' VALIGN='middle' >
          <TH ALIGN='center' ID='big' COLSPAN='2'><BR> </TH>
     </TR>";



echo" <TR ALIGN='left' VALIGN='middle' >
          <TH ALIGN='center' ID='big' COLSPAN='2'><FONT COLOR='blue'>Datos complementarios (domicilio)</FONT><BR><HR></TH>
     </TR>";

 $nombre_complent=($nombre_complent!='')?($nombre_complent):('--- ---');

echo"<TR ALIGN='left' VALIGN='middle'>
         <TH ALIGN='right'>  Nombre (Titular o arrendatario): </TH>
         <TD ALIGN='left' >$nombre_complent </TD>
     </TR>";

 $tel_complent=($tel_complent!='')?($tel_complent):('--- ---');

echo"<TR ALIGN='left' VALIGN='middle'>
         <TH ALIGN='right'>  Teléfono: </TH>
         <TD ALIGN='left' >$tel_complent</TD>
     </TR>";

 $monto_complent=($monto_complent!='')?($monto_complent):('--- ---');

echo"<TR ALIGN='left' VALIGN='middle'>
         <TH ALIGN='right'>  Monto: </TH>
         <TD ALIGN='left' >$monto_complent</TD>
     </TR>";

echo "<TBODY ID='vive_datos_complementarios'   >\n";

 $cp_soli_complement=($cp_soli_complement!='')?($cp_soli_complement):('--- ---');


 echo"<TR ALIGN='left' VALIGN='middle'>
          <TH ALIGN='right'> C.P.: </TH>
          <TD ALIGN='left' > $cp_soli_complement

          </TD>
      </TR> ";

 //--------------------------------------------------------------------
 // Colonia
 $colonia_soli_complement=($colonia_soli_complement!='')?($colonia_soli_complement):('--- ---');

 echo"<TR ALIGN='left' VALIGN='middle'>
      <TH ALIGN='right'> Colonia: </TH>
      <TD ALIGN='left' > $colonia_soli_complement";
 echo "</TD>
       </TR>\n";

 //--------------------------------------------------------------------
 // Estado
 $estado_soli_complement=($estado_soli_complement!='')?($estado_soli_complement):('--- ---');

 echo"<TR ALIGN='left' VALIGN='middle'>
 	<TH ALIGN='right'> Estado :  </TH>
 	<TD ALIGN='left' >$estado_soli_complement ";
echo "</TD>
       </TR>\n";
 //--------------------------------------------------------------------
 // Ciudad
 $ciudad_soli_complement=($ciudad_soli_complement!='')?($ciudad_soli_complement):('--- ---');

 echo"<TR ALIGN='left' VALIGN='middle'>
          <TH ALIGN='right'>Ciudad :  </TH>
          <TD ALIGN='left' >$ciudad_soli_complement";
 echo "</TD>
       </TR>\n";

 //--------------------------------------------------------------------
 // Poblacion o Municipio o Delegación.

 $poblacion_soli_complement=($poblacion_soli_complement!='')?($poblacion_soli_complement):('--- ---');

 echo " <TR ALIGN='left' VALIGN='middle'>
           <TH ALIGN='right'> Población/Municipio/Delegación: </TH>
           <TD ALIGN='left' > $poblacion_soli_complement\n";
 echo "</TD>
       </TR> ";

 $calle_soli_complement=($calle_soli_complement!='')?($calle_soli_complement):('--- ---');
 $num_soli_complement=($num_soli_complement!='')?($num_soli_complement):('--- ---');
 $num_int_soli_complement=($num_int_soli_complement!='')?($num_int_soli_complement):('--- ---');
 $entre_calles_complement=($entre_calles_complement!='')?($entre_calles_complement):('--- ---');


 echo "<TR ALIGN='left' VALIGN='middle'>
           <TH ALIGN='right' > Calle: </TH>
           <TD ALIGN='left'>$calle_soli_complement</TD>
       </TR>
     <TR ALIGN='left' VALIGN='middle'>
          <TH ALIGN='right' >No. Exterior  : </TH>
          <TD ALIGN='left'>$num_soli_complement</TD>
     </TR>
     <TR ALIGN='left' VALIGN='middle'>
          <TH ALIGN='right' >No. Interior  : </TH>
          <TD ALIGN='left'>$num_int_soli_complement</TD>
     </TR>

      ";
      /*<TR ALIGN='left' VALIGN='middle'>
          <TH ALIGN='right'>Entre las calles de :  </TH>
          <TD ALIGN='left' > $entre_calles_complement</TD>
     </TR>*/

echo "</TBODY>\n";
echo"</TBODY>";



echo" <TR ALIGN='left' VALIGN='middle' >
          <TH ALIGN='center' ID='big' COLSPAN='2'><BR></TH>
     </TR>";
/*
echo" <TR ALIGN='left' VALIGN='middle' >
          <TH ALIGN='center' ID='big' COLSPAN='2'><FONT COLOR='blue'>Estado civil</FONT><BR><HR> </TH>
     </TR>";


$edocivil_soli=($edocivil_soli!='')?($edocivil_soli):('--- ---');

echo"  <TR ALIGN='left' VALIGN='middle'>
      <TH ALIGN='right' VALIGN='top' WIDTH='30%'>  Estado Civil :  </TH><Th ALIGN='left' >$edocivil_soli ";

echo "</Th> ";
echo "</TR>";



echo "<TBODY ID='casado'>\n";

echo" <TR ALIGN='left' VALIGN='middle' >
          <TH ALIGN='center' ID='big' COLSPAN='2'><U><FONT size='2' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Inicio datos del cónyuge&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</FONT></U> </TH>
     </TR>";

$conyuge_nomb1=($conyuge_nomb1!='')?($conyuge_nomb1):('--- ---');

echo "</TR>
      <TR ALIGN='left' VALIGN='middle'>
          <TH ALIGN='right'>  Primer nombre : </TH>
          <TD ALIGN='left'>  $conyuge_nomb1</TD>
      </TR>";

$conyuge_nomb2=($conyuge_nomb2!='')?($conyuge_nomb2):('--- ---');

echo "</TR>
      <TR ALIGN='left' VALIGN='middle'>
          <TH ALIGN='right'>  Segundo nombre : </TH>
          <TD ALIGN='left'> $conyuge_nomb2 </TD>
      </TR>";


$conyuge_app=($conyuge_app!='')?($conyuge_app):('--- ---');

echo "</TR>
      <TR ALIGN='left' VALIGN='middle'>
          <TH ALIGN='right'>  Ap. Paterno : </TH>
          <TD ALIGN='left'>$conyuge_app </TD>
      </TR>";
$conyuge_apm=($conyuge_apm!='')?($conyuge_apm):('--- ---');

 echo "</TR>
      <TR ALIGN='left' VALIGN='middle'>
          <TH ALIGN='right'>  Ap. Materno : </TH>
          <TD ALIGN='left'>$conyuge_apm </T>
      </TR>";

$sexo_cony=($sexo_cony=='F')?('Femenino'):('Masculino');


      echo"<TR ALIGN='left' VALIGN='middle' ID='sexo'>
                       <TH ALIGN='right' >Sexo  : </TH>
                       <TH ALIGN='left' > $sexo_cony";

      echo"
     </TH></TR>";

$regimen_conyugal=($regimen_conyugal!='')?($regimen_conyugal):('--- ---');

     echo" <TR ALIGN='left' VALIGN='middle'>
          <TH ALIGN='right'>Régimen conyugal : </TH>
          <Th>$regimen_conyugal ";

      echo "    </Th></TR>";


$iden_soli_cony=($iden_soli_cony!='')?($iden_soli_cony):('--- ---');

echo"<TR ALIGN='left' VALIGN='middle'>  ";
	echo"   <TH ALIGN='right' >Identificación :</TH>";

	echo"<Td>$iden_soli_cony</Td>\n";

$fnacimiento_cony= substr($fnacimiento_cony,8)."/".substr($fnacimiento_cony,-5,2)."/".substr($fnacimiento_cony,-10,4);

$rfc_cony=($rfc_cony!='')?($rfc_cony):('---');
$homo_rfc_cony=($homo_rfc_cony!='')?($homo_rfc_cony):('---');

echo"
     </TH>
     </TR>
     <TR ALIGN='left' VALIGN='middle'>
         <TH ALIGN='right' WIDTH='20%'>Fecha de nacimiento: </TH>
         <TD ALIGN='left' >$fnacimiento_cony

           </TD>
     </TR>
   <TR ALIGN='left' VALIGN='middle'>
         <TH ALIGN='right' WIDTH='20%'> RFC: </TH>
         <TD ALIGN='left' > $rfc_cony-$homo_rfc_cony</TD>
     </TR>";

$telcasacony_soli=($telcasacony_soli!='')?($telcasacony_soli):('--- ---');
$teloficinacony_soli=($teloficinacony_soli!='')?($teloficinacony_soli):('--- ---');

echo "

	<TR ALIGN='left' VALIGN='middle'>
	 <TH ALIGN='right'>  Teléfono casa: </TH>
	 <TD ALIGN='left' >$telcasacony_soli</TD>
	</TR>

	<TR ALIGN='left' VALIGN='middle'>
	      <TH ALIGN='right'>  Teléfono de contacto: </TH>
	      <TD ALIGN='left' >$teloficinacony_soli </TD>
	</TR>

	";

$actividad_cony=($actividad_cony!='')?($actividad_cony):('--- ---');

echo"<TR ALIGN='left' VALIGN='middle'>
           <TH ALIGN='right'>Actividad económica : </TH>
           <TD>$actividad_cony";


echo"       </TD>
            </TR>";


$cp_soli_cony=($cp_soli_cony!='')?($cp_soli_cony):('--- ---');

echo"<TR ALIGN='left' VALIGN='middle'>
         <TH ALIGN='right'> C.P.: </TH>
         <TD ALIGN='left' > $cp_soli_cony
         </TD>
     </TR> ";

//--------------------------------------------------------------------
// Colonia
$colonia_soli_cony=($colonia_soli_cony!='')?($colonia_soli_cony):('--- ---');

echo"<TR ALIGN='left' VALIGN='middle'>
     <TH ALIGN='right'> Colonia: </TH>
     <TD ALIGN='left' > $colonia_soli_cony";

echo "</TD>
      </TR>\n";

//--------------------------------------------------------------------
// Estado
$estado_soli_cony=($estado_soli_cony!='')?($estado_soli_cony):('--- ---');

echo"<TR ALIGN='left' VALIGN='middle'>
	<TH ALIGN='right'> Estado :  </TH>
	<TD ALIGN='left' >$estado_soli_cony ";
echo "</TD>
      </TR>\n";
//--------------------------------------------------------------------
// Ciudad
$ciudad_soli_cony=($ciudad_soli_cony!='')?($ciudad_soli_cony):('--- ---');

echo"<TR ALIGN='left' VALIGN='middle'>
         <TH ALIGN='right'>Ciudad :  </TH>
         <TD ALIGN='left' >$ciudad_soli_cony";
echo "</TD>
      </TR>\n";

//--------------------------------------------------------------------
// Poblacion o Municipio o Delegación.
$poblacion_soli_cony=($poblacion_soli_cony!='')?($poblacion_soli_cony):('--- ---');
$calle_soli_cony=($calle_soli_cony!='')?($calle_soli_cony):('--- ---');
$num_soli_cony=($num_soli_cony!='')?($num_soli_cony):('--- ---');
$num_int_soli_cony=($num_int_soli_cony!='')?($num_int_soli_cony):('--- ---');
$entre_calles_cony=($entre_calles_cony!='')?($entre_calles_cony):('--- ---');
$entre_calles_cony=($entre_calles_cony!='')?($entre_calles_cony):('--- ---');
$entre_calles_conyII=($entre_calles_conyII!='')?($entre_calles_conyII):('--- ---');


echo " <TR ALIGN='left' VALIGN='middle'>
          <TH ALIGN='right'> Población/Municipio/Delegación: </TH>
          <TD ALIGN='left' >$poblacion_soli_cony \n";
echo "</TD>
      </TR> ";
echo "<TR ALIGN='left' VALIGN='middle'>
          <TH ALIGN='right' > Calle: </TH>
          <TD ALIGN='left'>$calle_soli_cony</TD>
      </TR>
    <TR ALIGN='left' VALIGN='middle'>
         <TH ALIGN='right' >No. Exterior  : </TH>
         <TD ALIGN='left'>$num_soli_cony</TD>
    </TR>
    <TR ALIGN='left' VALIGN='middle'>
         <TH ALIGN='right' >No. Interior  : </TH>
         <TD ALIGN='left'>$num_int_soli_cony</TD>
    </TR>

    <TR ALIGN='left' VALIGN='middle'>
         <TH ALIGN='right'>Entre las calles de :  </TH>
         <TD ALIGN='left' > $entre_calles_cony &nbsp;&nbsp;<B>y</B>&nbsp;&nbsp;$entre_calles_conyII</TD>
    </TR> ";

    echo" <TR ALIGN='left' VALIGN='middle' >
              <TH ALIGN='center' ID='big' COLSPAN='2'><U><FONT size='2' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fin datos del cónyuge&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</FONT></U> </TH>
         </TR>";



echo "</TBODY>\n";*/

echo" <TR ALIGN='left' VALIGN='middle' >
          <TH ALIGN='center' ID='big' COLSPAN='2'><U><FONT COLOR='blue'> <BR></FONT></U> </TH>
     </TR>";


echo" <TR ALIGN='left' VALIGN='middle' >
          <TH ALIGN='center' ID='big' COLSPAN='2'><FONT COLOR='blue'>Datos laborales</FONT><BR><HR> </TH>
     </TR>";

$convenio_emp=($convenio_emp!='')?($convenio_emp):('--- ---');
$empresa_soli=($empresa_soli!='')?($empresa_soli):('--- ---');
$domicilio_empresa=($domicilio_empresa!='')?($domicilio_empresa):('--- ---');
$tel_emp=($tel_emp!='')?($tel_emp):('--- ---');
$telcontacto_extension_soli=($telcontacto_extension_soli!='')?($telcontacto_extension_soli):('--- ---');
$actividad_econom_soli=($actividad_econom_soli!='')?($actividad_econom_soli):('--- ---');


echo"<TR ALIGN='left' VALIGN='middle'>
           <TH ALIGN='right'>Actividad económica : </TH>
           <TD>$actividad_econom_soli";

echo"       </TD>
            </TR>";

echo" <TR ALIGN='left' VALIGN='middle'>
          <TH ALIGN='right'>Existe convenio con empresa : </TH>
          <Th>$convenio_emp";

      echo "    </Th></TR>";

      echo "<TBODY ID='convenio_emp_no'>\n";

      echo"<TR ALIGN='left' VALIGN='middle'>
               <TH ALIGN='right' > Empresa : </TH>
               <TD ALIGN='left' > $empresa_soli</TD>
           </TR>

           <TR ALIGN='left' VALIGN='middle'>
                       <TH ALIGN='right' > Dirección: </TH>
                       <TD ALIGN='left' >$domicilio_empresa</TD>
              </TR>

          <TR ALIGN='left' VALIGN='middle'>
               <TH ALIGN='right'>      Teléfono : </TH>
               <TD ALIGN='left'>$tel_emp</TD>
           </TR>

           <TR ALIGN='left' VALIGN='middle'>
	                 <TH ALIGN='right'>  Extensión: </TH>
	                 <TD ALIGN='left' >$telcontacto_extension_soli</TD>
     </TR>

     ";

      echo"</TBODY>";


      echo"</TD></TR>";

      echo"</TBODY>";


$puesto_soli=($puesto_soli!='')?($puesto_soli):('--- ---');
$ingresos_emp=($ingresos_emp!='')?($ingresos_emp):('--- ---');
$jefe_emp=($jefe_emp!='')?($jefe_emp):('--- ---');
$dias_pago_emp=($dias_pago_emp!='')?($dias_pago_emp):('--- ---');
$nomina_emp=($nomina_emp!='')?($nomina_emp):('--- ---');
$tiempo_trabajoI=($tiempo_trabajoI!='')?($tiempo_trabajoI):('--- ---');
$tiempo_trabajoII=($tiempo_trabajoII!='')?($tiempo_trabajoII):('--- ---');
$dep_empresa=($dep_empresa!='')?($dep_empresa):('--- ---');
$imss_emp=($imss_emp=='Y')?('Si'):('No');
$patron_emp=($patron_emp!='')?($patron_emp):('--- ---');
$otros_ingresos_emp=($otros_ingresos_emp!='')?($otros_ingresos_emp):('--- ---');


      echo"     <TR ALIGN='left' VALIGN='middle'>
                     <TH ALIGN='right'> Puesto : </TH>
                     <TD ALIGN='left' >$puesto_soli</TD>
           </TR>

     <TR ALIGN='left' VALIGN='middle'>
		     <TH ALIGN='right'>Cuenta con IMSS : </TH><TD ALIGN='left' >$imss_emp</TD>

           <TR ALIGN='left' VALIGN='middle'>
                          <TH ALIGN='right'> Ingresos (mensuales): </TH>
                          <TD ALIGN='left' > $ingresos_emp</TD>
           </TR>

            <TR ALIGN='left' VALIGN='middle'>
	     <TH ALIGN='right'> Otros ingresos (mensuales): </TH>
	     <TD ALIGN='left' > $otros_ingresos_emp</TD>
           </TR>

      <TR ALIGN='left' VALIGN='middle'>
                          <TH ALIGN='right'> Jefe inmediato : </TH>
                          <TD ALIGN='left' >$jefe_emp</TD>
           </TR>

<TR ALIGN='left' VALIGN='middle'>

      	<TH ALIGN='right'> Patrón ante el IMSS: </TH>

      	 <TD ALIGN='left' > $patron_emp</TD>
           </TR>

<TR ALIGN='left' VALIGN='middle'>
                     <TH ALIGN='right'> Departamento : </TH>
                     <TD ALIGN='left' >$dep_empresa</TD>
           </TR>




      <TR ALIGN='left' VALIGN='middle'>
                          <TH ALIGN='right'> Días de págo : </TH>
                          <TD ALIGN='left' >$dias_pago_emp</TD>
           </TR>

      	<TR ALIGN='left' VALIGN='middle'>
      		 <TH ALIGN='right'>Número de nomina : </TH>
      		 <TD ALIGN='left' > $nomina_emp</TD>
      	</TR>";


          echo"<TR ALIGN='left' VALIGN='middle'>
                     <TH ALIGN='right'>Antigüedad: </TH>
                     <TD>$tiempo_trabajoI años, $tiempo_trabajoII meses

                      </TD>
                  </TR>";


      echo "<TBODY ID='convenio_anterior_emp'>\n";

      echo" <TR ALIGN='left' VALIGN='middle' >
                <TH ALIGN='center' ID='big' COLSPAN='2'><U><FONT COLOR='blue'> <BR></FONT></U> </TH>
           </TR>";


      echo" <TR ALIGN='left' VALIGN='middle' >
                <TH ALIGN='center' ID='big' COLSPAN='2'><FONT COLOR='blue'> Datos del empleo anterior</FONT><BR><HR></TH>
           </TR>";

$empresa_soli_ant=($empresa_soli_ant!='')?($empresa_soli_ant):('--- ---');
$domicilio_empresa_ant=($domicilio_empresa_ant!='')?($domicilio_empresa_ant):('--- ---');
$tel_emp_ant=($tel_emp_ant!='')?($tel_emp_ant):('--- ---');
$puesto_soli_ant=($puesto_soli_ant!='')?($puesto_soli_ant):('--- ---');
$ingresos_emp_ant=($ingresos_emp_ant!='')?($ingresos_emp_ant):('--- ---');
$jefe_emp_ant=($jefe_emp_ant!='')?($jefe_emp_ant):('--- ---');

if($imss_emp != '--- ---')
{
$imss_emp=($imss_emp='Y')?('Si'):('No');

}

      	echo"<TR ALIGN='left' VALIGN='middle'>
      	<TH ALIGN='right' > Empresa : </TH>
      	<TD ALIGN='left' >$empresa_soli_ant </TD>
      	</TR>

      	<TR ALIGN='left' VALIGN='middle'>
      	 <TH ALIGN='right' > Dirección: </TH>
      	 <TD ALIGN='left' > $domicilio_empresa_ant</TD>
      	</TR>

      	<TR ALIGN='left' VALIGN='middle'>
      	<TH ALIGN='right'>      Teléfono : </TH>
      	<TD ALIGN='left'>$tel_emp_ant</TD>
      	</TR>";

      	echo"     <TR ALIGN='left' VALIGN='middle'>
      	<TH ALIGN='right'> Puesto : </TH>
      	<TD ALIGN='left' > $puesto_soli_ant</TD>
      	</TR>";



      	echo"<TR ALIGN='left' VALIGN='middle'>
      	    <TH ALIGN='right'> Ingresos : </TH>
      	    <TD ALIGN='left' > $ingresos_emp_ant</TD>
      	</TR>

      	<TR ALIGN='left' VALIGN='middle'>
      	    <TH ALIGN='right'> Jefe inmediato : </TH>
      	    <TD ALIGN='left' >$jefe_emp_ant</TD>
      	</TR>





      	";







      echo"</TBODY>";





  echo"          </TABLE>
               </TD>
               </TR>
      </TABLE>";


echo"<BR>
     <BR>";

 echo "<TABLE ALIGN='center' class='main' CELLSPACING=0 CELLPADDING=1 WIDTH='30%' BGCOLOR='gray' ID='small'>
	           <TR><TD>
	           <TABLE BORDER=0 CELLSPACING=3 CELLPADDING=1 WIDTH='100%' BGCOLOR='#cdcde5' ID='small' >
	           <TR ALIGN='left' VALIGN='middle' >
	           <TH ALIGN='center'  COLSPAN='2'><U><FONT COLOR='black'></FONT></U> </TH>
	           </TR>";



	  echo"<TR ALIGN='left' VALIGN='middle'>

	           <TD ALIGN='center' >

	          <BUTTON STYLE='cursor:pointer;' onclick=\"$('#title_inf').trigger('click'); window.scrollTo(0,0);\"><B>&lt;&lt; ANTERIOR</B></BUTTON>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;



	          <BUTTON   STYLE='cursor:pointer;'  onclick=\"$('#historial').trigger('click'); window.scrollTo(0,0);\" ><B>SIGUIENTE &gt;&gt;</B></BUTTON>





	           </TD>
	      </TR> ";

	    echo"</TABLE>
	          </TR>
	          </TD>
     </TABLE>";

echo"<BR>";



echo"</div>";

echo"<a id='historial' >HISTORIAL DE SUCESOS</a>
     <div >";


echo"<br>
     <TABLE  BORDER='0' CELLPADDING='6' CELLSPACING='0' align='center'>

     ";
echo"<TABLE WIDTH='70%' BORDER='0' CELLPADDING='6' CELLSPACING='0' align='center'>
     <TR BGcolor='#6699cc'  ID='medium'>
         <TD colspan='6' align='center'><strong>&nbsp;</strong></tH>
     </TR>";

echo" <TR BGcolor='#FFFFFF'  ID='small'>
         <TD colspan='1' align='center'><strong><font color='#000099' ><U>Fecha - Hora</U></font></strong></TD>
         <TD colspan='1' align='center'><strong><font color='#000099' ><U>Usuario</U></font></strong></TD>
         <TD colspan='1' align='center'><strong><font color='#000099' ><U>Status</U></font></strong></TD>
         <TD colspan='1' align='center'><strong><font color='#000099' ><U>Suceso</U></font></strong></TD>
      </TR>";


$fecha=ffecha($fecha_hoy);
$fecha=gfecha($fecha);
/***QUERY*****/
$sql_cons ="SELECT  Fecha, Atendio, Status, Suceso
            FROM aval_sucesos
            WHERE ID_Aval = '".$Param2."' ORDER BY  Fecha";


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

echo"<TABLE WIDTH='70%' BORDER='0' CELLPADDING='6' CELLSPACING='0' align='center'>
     <TR BGcolor='#6699cc'  ID='medium'>
         <TD colspan='6' align='center'><strong>&nbsp;</strong></tH>
     </TR>";

echo"</TABLE>";
echo"</TABLE>";

echo"</DIV></DIV>";



?>
</div>
</BODY>
</HTML>


















































