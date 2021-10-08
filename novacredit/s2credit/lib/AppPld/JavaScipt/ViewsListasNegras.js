/**
 *
 * @author MarsVoltoso (CFA)
 * @category JavaScript
 * @created Mon Sep 15, 2014
 * @version 1.0
 */	


/**
 *
 *  @ Cargamos Cargamos resultado
 */ 	

$(document).ready(function (){
    
    $("#Catalogos").change(function (){
	   
	   if( $(this).val() == "Terroristas" ){
		   $("#trDisplay").show();
		   if( $(this).val() == "Todo" ){
			   $("#tipoCatalogo").val("");
			    $("#trDisplay").hide();
		   }
	   }else{
		   $("#trDisplay").hide();
	   } //@end
	});
	
	$("#tipoCatalogo").change(function (){
		var paginas = $("#paginacion").val();
	    var Filtro  = $("#BuscarTerrorista").val();
        var Opcion  = $("#Catalogos").val();
        VistaListasNegras(paginas,'',Filtro,Opcion);
	});
    
    $('.ui.selection.dropdown').dropdown('restore default text');
	VistaListasNegras(0,'','','');



$("#BuscarTerrorista").on("keyup",function (){
			var Filtro  = $("#BuscarTerrorista").val();
            var Opcion  = $("#Catalogos").val();
            VistaListasNegras(0,'',Filtro,Opcion);

		});

    $("#Catalogos").on("change",function (){
        var Filtro  = $("#BuscarTerrorista").val();
        var Opcion  = $("#Catalogos").val();
        VistaListasNegras(0,'',Filtro,Opcion);

    });
  
});



function VistaListasNegras(Pagina,Evento,Filtro,Opcion){
	
	var tipoCatalogo = $("#tipoCatalogo").val();
	
	$.ajax( {type: 'POST',
						  url:  '../Model/ModelListasNegras.php', 
						  data: '__cmd=consulta&Pagina='+Pagina+'&Evento='+Evento+'&Filtro='+Filtro+'&Opcion='+Opcion+'&tipoCatalogo='+tipoCatalogo,
						  success: function(RESPUESTA) {

						  	  $("#listasNegras").html(String(RESPUESTA))

						  },
						  error: function( )   { },
						  complete: function(RESPUESTA) { }  
						});

}
 						
function ActualizaListado(Evento)
{
	 var paginas = $("#paginacion").val();
	 var Filtro  = $("#BuscarTerrorista").val();
     var Opcion  = $("#Catalogos").val();
    VistaListasNegras(paginas,Evento,Filtro,Opcion);
}

function CambiaPaginaListado(Obj)
{
	 var paginas = $(Obj).val();
	 var Filtro  = $("#BuscarTerrorista").val();
     var Opcion  = $("#Catalogos").val();

    VistaListasNegras(paginas,'',Filtro,Opcion);
}