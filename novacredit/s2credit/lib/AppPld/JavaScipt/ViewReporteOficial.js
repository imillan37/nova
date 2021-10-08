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



function VistaTerroristas(Pagina,Evento,Filtro,Filtro2){
	$.ajax( {type: 'POST',
						  url:  '../Model/ModelReporteOficial.php', 
						  data: '__cmd=consulta&Pagina='+Pagina+'&Evento='+Evento+'&Filtro='+Filtro+'&Filtro2='+Filtro2,
						  success: function(RESPUESTA) {

						  	  $("#listasNegras").html(String(RESPUESTA));
						  	  
						  },
						  error: function( )   { },
						  complete: function(RESPUESTA) { }  
						});

}
 						



function ActualizaListado(Evento)
{
	 var paginas = $("#paginacion").val();
	 var Filtro  = $("#BuscarTerrorista").val();
	 VistaTerroristas(paginas,Evento,Filtro,Filtro2);
}

function CambiaPaginaListado(Obj)
{
	 var paginas = $(Obj).val();
	 var Filtro  = $("#BuscarTerrorista").val();
	 VistaTerroristas(paginas,'',Filtro,Filtro2);
}

$(document).ready(function (){ 

	VistaTerroristas(0,'','','');



$("#BuscarTerrorista").keypress(function (event){
			var Filtro  = $("#BuscarTerrorista").val();
			VistaTerroristas(0,'',Filtro,'');
	});

$("#BuscarporID").keypress(function (event){
			var Filtro2  = $("#BuscarporID").val();
			VistaTerroristas(0,'','',Filtro2);
	});



});

function muestra(id){
	$.ajax( {type: 'POST',
						  url:  '../Model/ModelReporteOficial.php', 
						  data: '__cmd=detalles&id='+id,
						  success: function(RESPUESTA) {

						  	  $("#ContenedorInfo").html(String(RESPUESTA));
						  	   $('#modalInfo').modal('show');
						  },
						  error: function( )   { },
						  complete: function(RESPUESTA) { }  
						});
}
 	
