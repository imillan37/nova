<?php
/****************************************/
/*Fecha:08-Diciembre-2011
/*Autor: Tonathiu Cárdenas
/*Descripción:Página de home Grupos solidarios
/*Dependencias:solicitud_total.php
/****************************************/

//Librerías
$exit = 0;
$noheader =1;
require($DOCUMENT_ROOT."/rutas.php");
require($class_path."promocion/lib_informacion_basica.php");   									//INFO SUCURSAL Y USUARIO
require("../../../../sucursal/promocion/js/jquery_links.php");   								//LIBRERÍAS DE JQUERY

//Inicio conexión
$db = ADONewConnection(SERVIDOR);
$db->Connect(IP,USER,PASSWORD,NUCLEO);
//Fin Conexión
?>
<!--ÁREA CSS-->
<link rel="stylesheet" href="<?=$shared_scripts?>/jquery_ui/development-bundle/themes/cupertino/jquery.ui.all.css">
<link rel="stylesheet" href="<?=$shared_scripts?>/jquery_ui/development-bundle/demos/demos.css">
<link rel="stylesheet" href="../../../../sucursal/promocion/css/blue/style.css">
<STYLE>
	#resizable    { width: 90%;  padding: 1.5em;  }
	#resizable h3 { text-align: center; margin: 0; font-size:150%;}

	
	.ui-resizable-helper { border: 2px dotted #00F; }

	.portlet { margin: 0 1em 1em 0; width: 550px;  }
	.portlet-header { margin: 0.3em; padding-bottom: 4px; padding-left: 0.2em; }
	.portlet-header .ui-icon { float: right; }
	.portlet-content { padding: 0.4em; height: auto; }
</STYLE>

<SCRIPT>
//FUNCIONES JQUERY
$('live',function() {

                //PORTLET
				$( ".portlet" )
					.addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
					.find( ".portlet-header" )
						.addClass( "ui-widget-header ui-corner-all" )
						.prepend( "<span class='ui-icon ui-icon-minusthick'></span>")
						.end()
					.find( ".portlet-content" );

				 $( ".portlet-header .ui-icon" ).click(function()
				  {
					$( this ).toggleClass( "ui-icon-minusthick" ).toggleClass( "ui-icon-plusthick" );
					$( this ).parents( ".portlet:first" ).find( ".portlet-content" ).toggle();
				 });

				//RESIZE
			     $("#resizable" ).resizable({
				 	helper: "ui-resizable-helper"
				  });
});
</SCRIPT>
<?php

	/*********QUERY**************/
	$Sql_cons = "SELECT
					grupo_solidario_integrantes.ID_Solicitud																AS ID_SOLI,
				    (Concat(solicitud.Nombre,' ',solicitud.NombreI,' ',solicitud.Ap_paterno,' ',solicitud.Ap_materno)) 		AS NMB_CTE,
					solicitud.Status																						AS STAT,
					solicitud.Status_solicitud																				AS STAT_SOLI,
					UCASE(grupo_solidario.Nombre)																			AS NMB_GPO,
					grupo_solidario.Status_grupo																			AS STAT_GPO,
					solicitud.Telefono           AS TEL,
					solicitud.Tel_contacto       AS TELCONT,
					solicitud.Num_celular        AS CEL,
					solicitud.Email    AS EMAIL,
					CONCAT(if(solicitud.Calle IS NULL,'',solicitud.Calle),' ',if(solicitud.Numero IS NULL,'',solicitud.Numero),' ',if(solicitud.Interior IS NULL,'',solicitud.Interior),', COL. ',if(solicitud.Colonia IS NULL,'',solicitud.Colonia),', ',if(solicitud.Poblacion IS NULL,'',solicitud.Poblacion),', ',if(solicitud.Estado IS NULL,'',solicitud.Estado),', C.P. ',if(solicitud.CP IS NULL,'',solicitud.CP) ) AS DOMCTE
			FROM grupo_solidario
				 
				 LEFT JOIN grupo_solidario_integrantes ON grupo_solidario_integrantes.ID_grupo_soli 		= grupo_solidario.ID_grupo_soli
														AND grupo_solidario_integrantes.Ciclo_gpo		 	= grupo_solidario.Ciclo_gpo
														AND grupo_solidario_integrantes.Ciclo_renovado='N'

				LEFT JOIN solicitud       ON solicitud.ID_Solicitud = grupo_solidario_integrantes.ID_Solicitud
       WHERE
				grupo_solidario.ID_grupo_soli = '".$ID_GPO."'  ";
	$rs_cons=$db->Execute($Sql_cons);

	
		$html="<BR /><BR /><BR /><BR /><BR />
			  <DIV CLASS='demo' ALIGN='center'>
				 <DIV ID='resizable' CLASS='ui-widget-content' ALIGN='center'>";

						$html.="				<BR />
														<H3 CLASS='ui-widget-header' STYLE='font-size:x-medium; font-weight:bold; width:90%;'>".$rs_cons->fields["NMB_GPO"]."
														</H3>
														<BR />";
														
						$html.="			<TABLE CELLSPACING='3' STYLE='' ALIGN='CENTER' BORDER='0px' WIDTH='90%'>";
						$html.="<TR ALIGN='center' VALIGN='middle' BgCOLOR='white' >
										<TH STYLE='font-size:medium;  text-align:center; color:gray; height:50px;' COLSPAN='9' >
										<IMG SRC='".$img_path."exclamation.png'  STYLE='height:15px; cursor:pointer;' /> &nbsp; SELECCIONE UN INTEGRANTE DEL GRUPO, DESDE EL PANEL DE LA DERECHA.
										</TH>
							 </TR>";
		$html.="</DIV>
			 </DIV>";


	echo $html;
?>
