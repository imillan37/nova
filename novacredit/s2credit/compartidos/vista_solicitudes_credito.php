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

echo"<SCRIPT type='text/javascript' src='../sucursal/promocion/js/number_format.js'>		</SCRIPT>";

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

				 //TABS
				$( "#tabs" ).tabs();
				
				 //ACORDION
				 $("#accordion").accordion({
					autoHeight: false,
					navigation: true,
					//event     : "mouseover"
				});

					//BUTTON'S
				 $("#NEW_SOLICITUD").button({
						   icons: {
									primary: "ui-icon-circle-triangle-e"
									
								  }
				  });


});
</SCRIPT>
<?

	 $Genera_soli = new TNuevaSolicitud($Tipo_credito,$ID_Tiposolicitud,$db,$ID_SUC,$ID_USR,0,$PHP_SELF,$ID_GPO);
	 $Genera_soli->get_vista_solicitud($Param2);
	 echo $Genera_soli->HTML_CSS;
	 echo $Genera_soli->HTML;

?>

<SCRIPT>
$("#NEW_SOLICITUD").live('click',function()
{
	var ID_SOLICITUD = $("#TIPO_SOLICITUD").val();
	
			$.ajax({
						type: 'POST',
						url: '../sucursal/promocion/solicitudes_solidario/captura_solicitud_solidario.php', 
						beforeSend: function () {
								jQuery('#DTL_SOLI').html("<IMG  BORDER=0 SRC='<?=$img_path?>Loading_transparent.gif'  ALT='Cargando...'  /><BR /><P STYLE='font-weight:bold; color:black;'>CARGANDO LA SOLICITUD DE CRÉDITO NÓMINA</P> ");
						},
						success: function(result)
						{
							jQuery(window).attr("location","../sucursal/promocion/solicitudes_solidario/captura_solicitud_solidario.php?ID_Tiposolicitud="+ID_SOLICITUD+"&ID_GPO=<?=$ID_GPO?>");
							return false;
						}//FIN SUCCESS
					});


});

$('body').ready(function()
{
				  //ACTUALIZAR COTIZADOR
				  $("#Monto").trigger('change');
				  var ID_PROD = $("#ID_Producto").val();
				  jQuery.ajax( {type: 'POST',
								url:  '../lib/class/promocion/ajax/lib_cotizador_ajax.php', 
								data: "Detalle_vence=TRUE&id_producto="+ID_PROD+"",
								success: function(RESPUESTA) {  jQuery('#VENCIMIENTO').html(jQuery.trim(RESPUESTA)); },
								error: function( )   { },
								complete: function(RESPUESTA) { }  
								});

				 				//REFRESCAR LIQUIDO
 				var Tot_liquido	= Number(0);
				var Cmp_liquido	= Number(0);
				//INGRESOS
				 $(".LIQUIDO").each(function(n) {
					Cmp_liquido = Number ($(this).html());
					
					Tot_liquido = parseFloat(Number(Tot_liquido)) + parseFloat(Number(Cmp_liquido));
					$("#TOTAL_LIQUIDO").html('TOTAL_LIQUIDO: $ '+number_format(Tot_liquido,2));
				 });
});


$("#Monto").live('change',function()
{
	
	
			var ID_empresa = $("#ID_empresa").val();
			if(ID_empresa != '')
			{
					jQuery.ajax( {type: 'POST',
						url:  '../lib/class/promocion/ajax/lib_catalog_empresa_ajax.php', 
						data: "Detalle_cmp_empresa=TRUE&id_empresa="+ID_empresa+"&tipo_campo=Direccion",
						success: function(RESPUESTA) {  jQuery('#Direc_empresa').html(jQuery.trim(RESPUESTA)); },
						error: function( )   { },
						complete: function(RESPUESTA) { }  
					});
 
					jQuery.ajax( {type: 'POST',
						url:  '../lib/class/promocion/ajax/lib_catalog_empresa_ajax.php', 
						data: "Detalle_cmp_empresa=TRUE&id_empresa="+ID_empresa+"&tipo_campo=Telefono",
						success: function(RESPUESTA) {  jQuery('#Telefono_empresa').val(jQuery.trim(RESPUESTA)); },
						error: function( )   { },
						complete: function(RESPUESTA) { }  
					});
			 }

	if( $("#COTIZADOR").val() == 'TRUE' )
	{

		if($("#NOMINA_ESPECIAL").val() == 'TRUE')
		{
	
				 var Ingresos_netos 	= parseFloat($("#EGRESOS_NOM_ESP").val());
				 var Ingresos_brutos	= parseFloat($("#INGRESOS_NOM_ESP").val());

				 
		}
		else
		{
				var Tot_ingresos	= Number(0);
				var Cmp_ingreso		= Number(0);
				//INGRESOS
				 $(".INGRESOS").each(function(n) {
					Cmp_ingreso = Number ($(this).html());
					Tot_ingresos = parseFloat(Tot_ingresos) + parseFloat(Cmp_ingreso);
				 });
				 

				 var Tot_egresos	= Number(0);
				 var Cmp_egreso		= Number(0);

				 //EGRESOS
				 $(".EGRESOS").each(function(n) {
					Cmp_egreso = Number ($(this).html());
					Tot_egresos = parseFloat(Tot_egresos) + parseFloat(Cmp_egreso);
				 });

				 //var Ingresos_netos 	= parseFloat(Tot_ingresos) - parseFloat(Tot_egresos);
				 var Ingresos_netos 	= parseFloat($("#Ingresos_netos").html());
				 var Ingresos_brutos	= parseFloat(Number(Tot_ingresos));

		}

		 
		//DESPLEGAR INFO
		$("#INGR_BRT").html(number_format(Ingresos_brutos,2));
		$("#INGR_NET").html(number_format(Ingresos_netos,2));



		var Tipo_credito	= $("#TIPO_CREDITO").val();
		var Monto 			= jQuery.trim($("#Monto").html());
		var ID_Producto		= $("#ID_Producto").val();
		var Plazo 			= $("#Plazo").val();
		var Num_cte			= 0;

			//ETIQUETAS PRODUCTO Y PLAZO EN EL COTIZADOR
					//ETIQUETA PRODUCTO FINANCIERO
					jQuery.ajax( {type: 'POST',
						url:  '../lib/class/promocion/ajax/lib_cotizador_ajax.php', 
						data: "NMB_PROD_FINANCIERO=TRUE&ID_PROD_FIN="+ID_Producto+"",
						success: function(RESPUESTA) {
														jQuery('#LBL_PRODUCTO').html(jQuery.trim(RESPUESTA));

													},
						error: function( )   { },
						complete: function(RESPUESTA) { }  
					});

					jQuery('#LBL_PLAZO').html(Plazo);
					
		//alert(Tipo_credito+'--'+Monto+'***'+ID_Producto+'___'+Plazo);
		
		//VALIDAR PERCENT DESC
		if(Tipo_credito == 3)
		{
			var ID_empresa = $("#ID_empresa").val();

			if(ID_empresa != '')
			{
					jQuery.ajax( {type: 'POST',
						url:  '../lib/class/promocion/ajax/lib_cotizador_ajax.php', 
						data: "Detalle_percent_desc=TRUE&id_empresa="+ID_empresa+"&tipo_credito="+Tipo_credito+"",
						success: function(RESPUESTA) {  jQuery('#PERCENT_DESC').html(jQuery.trim(RESPUESTA)); },
						error: function( )   { },
						complete: function(RESPUESTA) { }  
					});
			 }
			 else
			  $("#PERCENT_DESC").html("<FONT COLOR='GRAY' SIZE='1PX' >SELECCIONE LA EMPRESA DEL SOLICITANTE</FONT>");

        }
		
		if(Tipo_credito == 1)
		{
					var ID_empresa = "SE";
					
					jQuery.ajax( {type: 'POST',
						url:  '../lib/class/promocion/ajax/lib_cotizador_ajax.php', 
						data: "Detalle_percent_desc=TRUE&id_empresa=SE&tipo_credito="+Tipo_credito+"",
						success: function(RESPUESTA)
						 {
							if(jQuery.trim(RESPUESTA) != '')
								jQuery('#PERCENT_DESC').html(jQuery.trim(RESPUESTA));
							else
								$("#PERCENT_DESC").html("<FONT COLOR='GRAY' SIZE='1PX' >INGRESE UN PORCENTAJE DE DESCUENTO, PARA EL TIPO DE CRÉDITO.</FONT>");
								
						 },
						error: function( )   { },
						complete: function(RESPUESTA) { }  
					});
		}
       //FIN VALIDAR PERCENT DESC


	  //VALIDAR TIPO DE PAGO

					jQuery.ajax( {type: 'POST',
						url:  '../lib/class/promocion/ajax/lib_cotizador_ajax.php', 
						data: "Detalle_tipo_pago=TRUE&id_empresa="+ID_empresa+"&tipo_credito="+Tipo_credito+"",
						success: function(Tipo_pago)
						{

								var Ingresos_soli = Number(0);
								
								if(Tipo_pago == 'BRUTO')
								{
									$("#CHK_INGR_NET").empty();
									$("#CHK_INGR_BRT").html("<IMG  BORDER=0 SRC='../images/tick.png'  ALT='editando'  STYLE='height:15px; width:15px; vertical-align:middle;'  />");

									Ingresos_soli = Ingresos_brutos ;
									
								}
								else if(Tipo_pago == 'NETO')
								{
									$("#CHK_INGR_BRT").empty();
									$("#CHK_INGR_NET").html("<IMG  BORDER=0 SRC='../images/tick.png'  ALT='editando'  STYLE='height:15px; width:15px; vertical-align:middle;'  />");

									Ingresos_soli = Ingresos_netos;
								}

								//CAPACIDAD DE PAGO
								jQuery.ajax( {type: 'POST',
											url:  '../lib/class/promocion/ajax/lib_cotizador_ajax.php', 
											data: "Detalle_cap_pago=TRUE&id_empresa="+ID_empresa+"&ingresos_soli="+Ingresos_soli+"&id_producto="+ID_Producto+"&tipo_credito="+Tipo_credito+"",
											success: function(RESPUESTA)
											{

												 jQuery('#CAP_PAGO').html(RESPUESTA);
												  
											},
											error: function( )   { },
											complete: function(RESPUESTA) { }  
								});
											
								//MONTO MÁXIMO AUTORIZAR
								jQuery.ajax( {type: 'POST',
											url:  '../lib/class/promocion/ajax/lib_cotizador_ajax.php', 
											data: "Detalle_cap_max=TRUE&id_producto="+ID_Producto+"&id_empresa="+ID_empresa+"&ingresos_soli="+Ingresos_soli+"&plazo="+Plazo+"&num_cte="+Num_cte+"&tipo_credito="+Tipo_credito+"",
											success: function(RESPUESTA)
											 {
												jQuery('#MNT_MAX').html(RESPUESTA);
											 },
											error: function( )   { },
											complete: function(RESPUESTA) { }  
								});

								//RENTA MÁXIMA
								jQuery.ajax( {type: 'POST',
												url:  '../lib/class/promocion/ajax/lib_cotizador_ajax.php', 
												data: "Detalle_renta_max=TRUE&id_producto="+ID_Producto+"&id_empresa="+ID_empresa+"&ingresos_soli="+Ingresos_soli+"&plazo="+Plazo+"&num_cte="+Num_cte+"&tipo_credito="+Tipo_credito+"",
												success: function(RESPUESTA)
												  {
														 jQuery('#RENTA_MAX').html(RESPUESTA);
														
												  },
												error: function( )   { },
												complete: function(RESPUESTA) { }  
								});
								
								//MONTO SOLICITADO
								$("#MONTO_SOLICITADO").html(number_format(Monto,2));
								
								//RENTA MONTO SOLI
								jQuery.ajax( {type: 'POST',
												url:  '../lib/class/promocion/ajax/lib_cotizador_ajax.php', 
												data: "Detalle_renta=TRUE&id_producto="+ID_Producto+"&plazo="+Plazo+"&monto_autoriza="+Monto+"&num_cte="+Num_cte+"",
												success: function(RESPUESTA) {  jQuery('#RENTA_MONTO_SOLI').html(RESPUESTA); },
												error: function( )   { },
												complete: function(RESPUESTA) { }  
								});

								//DIFERENCIA RENTA MÁXIMA VS RENTA SOLICITADA
								jQuery.ajax( {type: 'POST',
												url:  '../lib/class/promocion/ajax/lib_cotizador_ajax.php', 
												data: "Detalle_difer=TRUE&id_producto="+ID_Producto+"&id_empresa="+ID_empresa+"&ingresos_soli="+Ingresos_soli+"&plazo="+Plazo+"&monto_autoriza="+Monto+"&num_cte="+Num_cte+"&tipo_credito="+Tipo_credito+"",
												success: function(RESPUESTA) {  jQuery('#DIFERENCIA').html(RESPUESTA); },
												error: function( )   { },
												complete: function(RESPUESTA) { }  
								});

								//MENSAJE COTIZADOR
								jQuery.ajax( {type: 'POST',
												url:  '../lib/class/promocion/ajax/lib_cotizador_ajax.php', 
												data: "Detalle_msg=TRUE&id_producto="+ID_Producto+"&id_empresa="+ID_empresa+"&ingresos_soli="+Ingresos_soli+"&plazo="+Plazo+"&monto_autoriza="+Monto+"&num_cte="+Num_cte+"&asigna_montos=TRUE"+"&tipo_credito="+Tipo_credito+"",
												success: function(RESPUESTA) {

													jQuery('#MSG_CREDIT').html(RESPUESTA);
													if(RESPUESTA == "<CENTER><B><FONT COLOR='BLUE' SIZE='2'>EL CR&Eacute;DITO ES AUTORIZADO CON LOS PAR&Aacute;METROS ACTUALES</FONT></B></CENTER>")
														$("#VALIDAR_COTIZADOR").val('TRUE');
													else
													    $("#VALIDAR_COTIZADOR").val('FALSE');
												},
												error: function( )   { },
												complete: function(RESPUESTA) { }  
								});
					
						},
						error: function( )   { },
						complete: function(RESPUESTA) { }  
					});

		
	}

});
</SCRIPT>

