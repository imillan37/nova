//VARIABLES GLOBALES
var file_status = 0;
var tools_status = 0;
var help_status = 0;
var lateralPanel_status = 1;


//EVENTOS
$(document).ready(function()
{

					//BUTTON'S
				 $("#SOLI_ANT").button({
						   text:false,
						   icons: {
									primary: "ui-icon-circle-triangle-w"
								  }
								  
				  });

				 $("#SOLI_NEXT").button({
						  text:false,
						   icons: {
									primary: "ui-icon-circle-triangle-e"
								  }
								  
				  });
  
			/********** CONFIGURACIÓN CSS ******************************/
			var windowHeight = document.documentElement.clientHeight;
			var menuHeight   = document.getElementById("menu").clientHeight;
			
			if(navigator.appName == "Microsoft Internet Explorer")
			{
				//$("body").css("max-height",windowHeight-menuHeight);
				$("#lateralClick").css("height",windowHeight-menuHeight);
				$("#lateralPanel").css("height",windowHeight-menuHeight);
				$("#mainContent").css("height",windowHeight-menuHeight);
			}
			else
			{
				//$("body").css("max-height",windowHeight-menuHeight);
				$("#lateralClick").css("height",windowHeight-menuHeight);
				$("#lateralPanel").css("height",windowHeight-menuHeight);
				$("#mainContent").css("height",windowHeight-menuHeight);
			}
			   //for resize of the window, recalculate the max-height available
				window.onresize = function(){
												windowHeight = document.documentElement.clientHeight;
												menuHeight = document.getElementById("menu").clientHeight;
												//$("body").css("max-height",windowHeight-menuHeight);
												$("#lateralClick").css("height",windowHeight-menuHeight);
												$("#lateralPanel").css("height",windowHeight-menuHeight);
												$("#mainContent").css("height",windowHeight-menuHeight);
											 };
			

			/********** PANEL LATERAL **********************************/
			//CLICK LATERALCLICK
			$("#lateralClick").click(function()
			{
				//MOSTRAMOS Y OCULTAMOS EL PANEL LATERAL
				if(lateralPanel_status == 0)
					lateralPanelShow();
				else
					lateralPanelHide();
				
			});
			
			//CLICK SECTION TITLE (toggle)
			$(".section").click(function(){
				$("#" + $(this).attr("id") + " + ul").slideToggle("slow");
			});


			/**********************LINKS**************************/
			//CLICK SITE TO CHANGE SRC PROPERTY OF THE IFRAME
			$("#lateralPanel li").click(function()
			{
			
				
				if($(this).attr('class') != 'VINCULOS')
				{
							//SAVE PAGE
							if($(this).attr('title') != '')
							  $("#PAGINA_ACTUAL").val($(this).attr('id'));

							//INTEGRANTES DEL GRUPO
							var CLASS_GPO	=  $(this).attr('class');
							var POS_GPO 	=  CLASS_GPO.search('INTG_GPO');
							var POS_EDIT 	=  CLASS_GPO.search('NOT_EDIT_SOLI');
							
							if( POS_GPO >= 0 )
							{
												var ID_SOLI 			=  $(this).attr('id');
												var Pos    				=  ID_SOLI.search('_GPO_SOLI_');
													Id 					=  ID_SOLI.substr(5,(Pos - 5));
													GPO_SOLI			=  ID_SOLI.substr((Pos + 10),ID_SOLI.length);
												var ID_Tiposolicitud	=  $("#TIPO_SOLICITUD").val();
												var ID_EMPRESA      	=  $("#ID_EMPRESA").val();
												

												$("#ID_SOLI_ACTUAL").val(Id);

												var Vista				= "../../../../compartidos/vista_solicitudes_credito.php?Param1=ID_Solicitud&Param2="+Id+"&Tipo_credito=2&noheader=1&ID_GPO="+GPO_SOLI+"&ID_Tiposolicitud="+ID_Tiposolicitud;
												
												var Edita				="../../../../sucursal/promocion/solicitudes_solidario/edita_solicitud_solidario.php?Param1=ID_Solicitud&Param2="+Id+"&Tipo_credito=2&noheader=1&ID_GPO="+GPO_SOLI+"&ID_Tiposolicitud="+ID_Tiposolicitud+"&ID_EMPRESA="+ID_EMPRESA;

												var Historial			="../../../../compartidos/historial_solicitudes_credito.php?Param1=ID_Solicitud&Param2="+Id+"&Tipo_credito=2&noheader=1&ID_Tiposolicitud="+ID_Tiposolicitud;

												var Digitalizar			="../../../../compartidos/soli_docs.php?Param1=ID_Solicitud&Param2="+Id+"&T_credit=pfisica_solidaria&noheader=1&ID_Tiposolicitud="+ID_Tiposolicitud;

												var Visualizar			="../../../../compartidos/soli_docsII.php?Param1=ID_Solicitud&Param2="+Id+"&T_credit=pfisica_solidaria&noheader=1&ID_Tiposolicitud="+ID_Tiposolicitud;
												
												$("#VISTA_SOLI").attr('lang',Vista);

												if($("#PAGINA_ACTUAL").val() =='VISTA_SOLI')
													 $(this).attr('lang',Vista);


													if(POS_EDIT < 0)
													{
															$("#EDITA_SOLI").attr('lang',Edita);
															if($("#PAGINA_ACTUAL").val() =='EDITA_SOLI')
																 $(this).attr('lang',Edita);
													}
													else
														$(this).attr('lang',Vista);

												$("#HIST_SOLI").attr('lang',Historial);
												if($("#PAGINA_ACTUAL").val() =='HIST_SOLI')
													 $(this).attr('lang',Historial);




												$("#DIGITLZ_SOLI").attr('lang',Digitalizar);
												if($("#PAGINA_ACTUAL").val() =='DIGITLZ_SOLI')
													 $(this).attr('lang',Digitalizar);

												$("#VER_DOCS_SOLI").attr('lang',Visualizar);
												if($("#PAGINA_ACTUAL").val() =='VER_DOCS_SOLI')
													 $(this).attr('lang',Visualizar);




												$(".INTG_GPO").each(function(n) {
													var Title = $(this).attr('title');
													if(Title == 'Activo')
														var Color_text = 'black';
													else
														var Color_text = 'red';
													
													$(this).css({"font-size":"xx-small","text-align":"left","color":Color_text,"background-color":"","font-weight":"normal"});
												});
												
												$(this).css({"font-weight":"bold","background-color":"#FBFAAE"});

												$.ajax({
															type: 'POST',
															url: 'ajax/nombre_cte.php',
															data:"id_soli="+Id,
															beforeSend: function () {
																	jQuery('#LOAD_ACTIONS').html("<IMG  BORDER=0 SRC='../../../../images/Loading_transparent.gif'  ALT='Cargando...' STYLE='width:15px; height:15px;' /><BR /><P STYLE='font-weight:bold; color:black; font-size:xx-small;'>CARGANDO SOLICITUD.</P> ");
															},
															success: function(result)
															{
																$("#NMB_CTE").html(result);
															}//FIN SUCCESS
														});

							}//FIN CLASS INTG_GPO

							if(jQuery.trim($("#NMB_CTE").html()) =='' && ( POS_GPO < 0 ) )
							{
										
										$("#dialog-message" ).html('<FONT SIZE="2"><B> <BR /> SELECCIONE UN INTEGRANTE <BR /> <BR />\" ' +$("#NMB_GPO").val()+' \"</B></FONT>');
										$("#dialog-message" ).dialog({
										modal: true,
										buttons: {
													Ok: function()
														{
															 $(this).dialog( "close" );
														}
												}
											 });
							}
							else
							{

								if($(this).attr('class') != 'PROCESOS_VALIDATE')
								{
													var lang 		= $(this).attr('lang');
													var class_li	= $(this).attr('class')
													var ARR_CLASS	= ["NO_PERMIT_CAPT_AVAL","NO_PERMIT_EDIT_VIEW_AVAL","NO_PERMIT_CAPT_COSOL","NO_PERMIT_EDIT_VIEW_COSOL","NOT_EDIT_SOLI"];

													if ( (class_li.search('NO_PERMIT_CAPT_AVAL')) > -1)
														TIPO_CLASS = 'NO_PERMIT_CAPT_AVAL';
														else if( (class_li.search('NO_PERMIT_EDIT_VIEW_AVAL')) > -1 )
															TIPO_CLASS = 'NO_PERMIT_EDIT_VIEW_AVAL';
														else if( (class_li.search('NO_PERMIT_CAPT_COSOL')) > -1 )
															TIPO_CLASS = 'NO_PERMIT_CAPT_COSOL';
														else if( (class_li.search('NO_PERMIT_EDIT_VIEW_COSOL')) > -1 )
															TIPO_CLASS = 'NO_PERMIT_EDIT_VIEW_COSOL';
														else if( (class_li.search('NOT_EDIT_SOLI')) > -1 )
															TIPO_CLASS = 'NOT_EDIT_SOLI';
														else
															TIPO_CLASS = '';
						

													$.ajax({
																type: 'POST',
																url: 'solicitud_total.php',
																success: function(result)
																{
																	if( jQuery.inArray(TIPO_CLASS, ARR_CLASS) == -1 )
																		{
																			$("#iframe").attr("src",lang);
																			return false;
																		}
																		else
																		{

																			if(TIPO_CLASS == 'NOT_EDIT_SOLI')
																				$("#dialog-message" ).html("<IMG  BORDER=0 SRC='../../../../images/cross-octagon.png'  ALT='editando'  STYLE='height:20px; width:20px; vertical-align:middle;'  />&nbsp;&nbsp;<FONT SIZE='2'><B>EL ESTATUS DE LA SOLICITUD, NO PERMITE LA EDICI&Oacute;N, <BR /> <BR /> <SPAN STYLE='color:red;'> ! IMPOSIBLE CONTINUAR ! <SPAN/></B></FONT>");
																			if(TIPO_CLASS == 'NO_PERMIT_CAPT_AVAL')
																				$("#dialog-message" ).html("<IMG  BORDER=0 SRC='../../../../images/exclamation-diamond-frame.png'  ALT='editando'  STYLE='height:20px; width:20px; vertical-align:middle;'  />&nbsp;&nbsp;<FONT SIZE='2'><B>LA SOLICITUD, YA CUENTA CON UN AVAL ASOCIADO, <BR /> <BR /> <SPAN STYLE='color:red;'> ! IMPOSIBLE CONTINUAR ! <SPAN/></B></FONT>");
																			if(TIPO_CLASS == 'NO_PERMIT_EDIT_VIEW_AVAL')
																				$("#dialog-message" ).html("<IMG  BORDER=0 SRC='../../../../images/exclamation-diamond-frame.png'  ALT='editando'  STYLE='height:20px; width:20px; vertical-align:middle;'  />&nbsp;&nbsp;<FONT SIZE='2'><B>LA SOLICITUD, NO CUENTA CON UN AVAL ASOCIADO, <BR /> <BR /> <SPAN STYLE='color:red;'> ! IMPOSIBLE CONTINUAR ! <SPAN/></B></FONT>");
																			if(TIPO_CLASS == 'NO_PERMIT_CAPT_COSOL')
																				$("#dialog-message" ).html("<IMG  BORDER=0 SRC='../../../../images/exclamation-diamond-frame.png'  ALT='editando'  STYLE='height:20px; width:20px; vertical-align:middle;'  />&nbsp;&nbsp;<FONT SIZE='2'><B>LA SOLICITUD, YA CUENTA CON UN COSOLICITANTE ASOCIADO, <BR /> <BR /> <SPAN STYLE='color:red;'> ! IMPOSIBLE CONTINUAR ! <SPAN/></B></FONT>");
																			if(TIPO_CLASS == 'NO_PERMIT_EDIT_VIEW_COSOL')
																				$("#dialog-message" ).html("<IMG  BORDER=0 SRC='../../../../images/exclamation-diamond-frame.png'  ALT='editando'  STYLE='height:20px; width:20px; vertical-align:middle;'  />&nbsp;&nbsp;<FONT SIZE='2'><B>LA SOLICITUD, NO CUENTA CON UN COSOLICITANTE ASOCIADO, <BR /> <BR /> <SPAN STYLE='color:red;'> ! IMPOSIBLE CONTINUAR ! <SPAN/></B></FONT>");

																			return false;
																		}
																	
																}//FIN SUCCESS
															});

													if( jQuery.inArray(TIPO_CLASS, ARR_CLASS) == -1 )
														{
															$("#lateralPanel li").removeClass("active");
															$(this).addClass("active");
														}
														else
														{
															$("#dialog-message" ).dialog({
															modal: true,
															buttons: {
																		Ok: function()
																			{
																				 $(this).dialog( "close" );
																			}
																	}
																 });
														}
								}
								if($(this).attr('class') == 'PROCESOS_VALIDATE')
								{
												var ID_TAG	 			=  $(this).attr('id');
												var Pos    				=  ID_TAG.search('TIPO_SOLI_');
												var ID_SOLI 			=  ID_TAG.substr(0,Pos);
												var ID_TIPO_REG			=  ID_TAG.substr((Pos + 10),ID_TAG.length);


										$.ajax({
												type: 'POST',
												url: '../ajax/lib_valida_procesos_ajax.php',
												data:{'MODULO_VALIDATE':'VALIDAR_STATUS','ID_SOLICITUD':ID_SOLI,'ID_Tipo_regimen':ID_TIPO_REG},
												success: function(result)
													 {
																if(jQuery.trim(result) == 'TRUE' )
																{
																				$("#dialog-message" ).attr('title',"<LABEL STYLE='font-weight:bold; color:black;'>AVISO S2CREDIT.</LABEL>");
																				$("#dialog-message" ).html("<BR /> <SPAN CLASS='ui-icon ui-icon-alert' style='float:left;'></SPAN> <FONT SIZE='2' COLOR='BLACK'><B>&iquest;  DESEA ACEPTAR O RECHAZAR A LA SOLICITUD  &#63;</B></FONT> ");
																				$("#dialog-message" ).css({"width":"900px"});
																				$("#dialog-message" ).dialog(
																				{
																					width: 450,
																					modal: true,
																					buttons: {
																								RECHAZAR: function()
																								{
																									
																									$.ajax({
																												type: 'POST',
																												url: '../ajax/lib_valida_procesos_ajax.php',
																												data:{'MODULO_VALIDATE':'INACTIVAR','ID_SOLICITUD':ID_SOLI,'ID_Tipo_regimen':ID_TIPO_REG,'OBSERVACION':'SOLICITUD INACTIVADA DESDE EL MÓDULO DE CHECK LIST'},
																												success: function(result)
																												{
																													$("#dialog-message").dialog( "close" );
																													$("#dialog-message" ).html(result);
																													$("#dialog-message" ).dialog(
																														{
																															width: 450,
																															modal: true,
																															buttons: {
																																			OK: function()
																																				{
																																					$(this).dialog( "close" );
																																				}
																																		}
																														});
																												}//FIN SUCCESS
																											});
																								},
																								ACEPTAR: function()
																								{
																									
																									$.ajax({
																											type: 'POST',
																											url: '../ajax/lib_valida_procesos_ajax.php',
																											data:{'MODULO_VALIDATE':'CHECK_LIST','ID_SOLICITUD':ID_SOLI,'ID_Tipo_regimen':ID_TIPO_REG,'OBSERVACION':'CHECK LIST DE LA SOLICITUD COMPLETA'},
																											success: function(result)
																											{
																													$("#dialog-message").dialog( "close" );
																													$("#dialog-message" ).html(result);
																													$("#dialog-message" ).dialog(
																														{
																															width: 450,
																															modal: true,
																															buttons: {
																																			OK: function()
																																				{
																																					$(this).dialog( "close" );
																																				}
																																		}
																														});
																											}//FIN SUCCESS
																										});
																																		
																								}									
																								
																							}
																				});
																}
																else
																{

																	$("#dialog-message" ).html(result);
																	$("#dialog-message" ).dialog(
																						{
																							width: 450,
																							modal: true,
																							buttons: {
																										OK: function()
																											{
																												$(this).dialog( "close" );
																											}
																									}
																						});

																}
																				
													 }//FIN SUCCESS
												});
												

							     }
								



										
								}//FIN ELSE
				}
	});//#lateralPanel li


			$(".VINCULOS").live('click',function ()
			{
									var Id =  $(this).attr('id');
									//Id =  Id.substr(7,Id.length);

								  $("#OPTION_"+Id).slideToggle("fast");
								  $("#OPTION_"+Id).css({"display":"block"});

								  if($("#IMG_LINK_"+Id).attr('title')=='DESPLEGAR OPCIONES...')
								  {
									 $("#IMG_LINK_"+Id).attr("src","../../../../images/toggle-small.png");
									 $("#IMG_LINK_"+Id).attr("title","OCULTAR OPCIONES...");
								  }
								   else
								  {
									  $("#IMG_LINK_"+Id).attr("src","../../../../images/toggle-small-expand.png");
									  $("#IMG_LINK_"+Id).attr("title","DESPLEGAR OPCIONES...");
									
								  }			  
				});

	//NAVEGAR SOLICITUDES
	$("#SOLI_NEXT").live('click',function ()
	{
		var SOLI_ACTUAL		=$("#ID_SOLI_ACTUAL").val();
		var GPO_SOLI		=$("#GPO_SOLI").val();
		var Active_soli		='FALSE';

		$(".INTG_GPO").each(function(n) {
			
			if(Active_soli	=='TRUE')
				$(this).trigger('click');
			
			if($(this).attr('id')=='SOLI_'+SOLI_ACTUAL+'_GPO_SOLI_'+GPO_SOLI)
				Active_soli		='TRUE';
			else
				Active_soli		='FALSE';

		});
		
		//SI Active_soli =='TRUE' es que es el último elemento deshabilitamos el boton
		if(Active_soli =='TRUE')
			$("#SOLI_NEXT").button({ disabled: true });
		else
			$("#SOLI_ANT").button({ disabled: false });
	});

	$("#SOLI_ANT").live('click',function ()
	{
		var SOLI_ACTUAL		=$("#ID_SOLI_ACTUAL").val();
		var GPO_SOLI		=$("#GPO_SOLI").val();
		var Active_soli		='FALSE';
		var LI_ANT			='';
		var CONT			= 1;

		$(".INTG_GPO").each(function(n) {
			
			if(Active_soli	=='TRUE' && LI_ANT !='')
				$("#"+LI_ANT).trigger('click');

			if(Active_soli	=='TRUE' && LI_ANT =='')
				$("#SOLI_ANT").button({ disabled: true });
			
			if($(this).attr('id')=='SOLI_'+SOLI_ACTUAL+'_GPO_SOLI_'+GPO_SOLI)
				Active_soli		='TRUE';
			else
			{
				Active_soli		='FALSE';
				LI_ANT			=$(this).attr('id');
			}

	
		});

					if(Active_soli	=='TRUE' && LI_ANT !='')
					{
						$("#"+LI_ANT).trigger('click');
						$("#SOLI_NEXT").button({ disabled: false });
					}

		
	});
	
	
});



	//PANEL LATERAL
	function lateralPanelShow()
	{
		$("#lateralClickImg").attr("src","images/toggleRight.gif");
		$("#lateralPanel").show();
		lateralPanel_status = 1;
	}

	function lateralPanelHide()
	{
		$("#lateralClickImg").attr("src","images/toggleLeft.gif");
		$("#lateralPanel").hide();
		lateralPanel_status = 0;
	}




