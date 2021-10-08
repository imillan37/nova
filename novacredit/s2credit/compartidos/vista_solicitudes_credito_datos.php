<?php
/****************************************/
/*Fecha: 02/09/2011
/*Autor: Tonathiu Cárdenas
/*Descripcíón: Captura de solicitud de crédito tipo individual 
/*Dependencias:Ninguna
/*Versión S2credit: 4.5
/****************************************/


//Librerías

require($DOCUMENT_ROOT."/rutas.php");								//CORE CONSTANTES S2CREDIT
require($class_path."promocion/lib_render_solicitud.php");			//OBJETO RENDER DE LA SOLICITUD
require("../sucursal/promocion/js/jquery_links.php");   			//LIBRERÍAS DE JQUERY


//Inicio conexión
$db = ADONewConnection(SERVIDOR);
$db->Connect(IP,USER,PASSWORD,NUCLEO);
//Fin Conexión


//JAVASCRIPT Y AJAX

?>

<!--ÁREA CSS-->
<LINK REL="stylesheet" HREF="../sucursal/promocion/css/solicitud.css"   TYPE="text/css" MEDIA="print, projection, screen">
<link REL="stylesheet" HREF="<?=$shared_scripts?>/jquery_ui/development-bundle/themes/cupertino/jquery.ui.all.css">
<link REL="stylesheet" HREF="<?=$shared_scripts?>/jquery_ui/development-bundle/demos/demos.css">

<!--JQUERY-->
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

});
</SCRIPT>
<?
	 $Genera_soli = new TNuevaSolicitud($Tipo_credito,$ID_Tiposolicitud,$db,$ID_SUC,$ID_USR,0,$PHP_SELF,$ID_GPO);
	 $Genera_soli->get_vista_solicitud_datos($ID_SOLICITUD,$MODULO);
	 echo $Genera_soli->HTML_CSS;
	 echo $Genera_soli->HTML;
?>

<SCRIPT>

</SCRIPT>


