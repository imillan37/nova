<?php
/****************************************/
/*Fecha: 02/09/2011
/*Autor: Tonathiu Cárdenas
/*Descripcíón: VISTA DEL HISTORIAL DE UNA SOLICITUD
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
<!--ÁREA CSS-->
<LINK REL="stylesheet" HREF="../sucursal/promocion/css/solicitud.css"   TYPE="text/css" MEDIA="print, projection, screen">
<link REL="stylesheet" HREF="<?=$shared_scripts?>/jquery_ui/development-bundle/themes/cupertino/jquery.ui.all.css">
<link REL="stylesheet" HREF="<?=$shared_scripts?>/jquery_ui/development-bundle/demos/demos.css">

<?php

	 $Genera_soli = new TNuevaSolicitud($Tipo_credito,$ID_Tiposolicitud,$db,$ID_SUC,$ID_USR,0,$PHP_SELF);
	 $Genera_soli->get_historico_solicitud($Param2);
	 echo $Genera_soli->HTML_CSS;
	 echo $Genera_soli->HTML;

?>
<SCRIPT>
$(".SHOW_DETAIL_SUC").live('click',function ()
	{
						var Id =  $(this).attr('id');
							Id =  Id.substr(13,Id.length);

					  $("#ROW_SUC_"+Id).slideToggle("");

					  if($("#IMG_SUC_SOLI_"+Id).attr('title')=='DESPLEGAR DETALLES...')
					  {
                         $("#IMG_SUC_SOLI_"+Id).attr("src","<?=$img_path?>toggle-small.png");
						 $("#IMG_SUC_SOLI_"+Id).attr("title","OCULTAR DETALLES...");
					  }
					   else
					  {
						  $("#IMG_SUC_SOLI_"+Id).attr("src","<?=$img_path?>toggle-small-expand.png");
 						  $("#IMG_SUC_SOLI_"+Id).attr("title","DESPLEGAR DETALLES...");
						
					  }			
	});
</SCRIPT>
