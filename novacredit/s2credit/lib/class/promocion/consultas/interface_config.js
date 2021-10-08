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
			
			//CLICK SECTION TITLE (toggle)
			$(".section").live('click',function()
			{
				 //$(this).slideToggle("slow");
				 $("#" + $(this).attr("id") + " + ul").slideToggle("slow");
			});
			
			/**********************LINKS**************************/
			//CLICK SITE TO CHANGE SRC PROPERTY OF THE IFRAME
			$("#PROD_FINANC").live('change',function()
			{
				var ID_TIPO_CREDIT		= $(this).val()
				if(ID_TIPO_CREDIT != '')
					{
							jQuery.ajax( {type: 'POST',
										  url:  '../../lib/class/promocion/consultas/ajax/panel_control_configuracion_ajax.php', 
										  data: "GET_PARAMETROS=TRUE&ID_TIPO_CREDIT="+ID_TIPO_CREDIT,
										  success: function(RESPUESTA) {
																$("#PARAMETROS_CONFIG").html(RESPUESTA);
																$("#iframe").attr("src","");
																return false;
															},
										  error: function( )   { },
										  complete: function(RESPUESTA) { }  
											});
					}
					else
					$("#PARAMETROS_CONFIG").html('');
					
			});


			$(".PARAM_DTL").live('click',function()
			{

					var lang 				= $(this).attr('lang');
					if(jQuery.trim(lang) != '')
					{
						var ID_Tipocredito		= $("#ID_Tipocredito").val();
						lang = lang+"&ID_Tipocredito="+ID_Tipocredito;
						
						$("#iframe").attr("src",lang);
						return false;
					}
					if(jQuery.trim(lang) == '')
					{
						$("#iframe").attr("src",'under_construction.html');
						return false;
					}

			});





});



