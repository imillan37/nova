/**
 *
 * @author MarsVoltoso (CFA)
 * @category JavaScript
 * @created Mon Sep 15, 2014
 * @version 1.0
 */	

$(document).ready(function (){ 

/**
 *
 *  @ Cargamos Cargamos resultado
 */ 	
 	
	 $("#ConsultarCliente").click(function (){
		
		var SherchSolicitud = $("#SherchSolicitud").val();
		var SherchNombre    = $("#SherchNombre").val(); 
		
			try{
		     		 
				 $.ajax( {type: 'POST',
						  url:  '../Model/ModelMonitorRiesgo.php', 
						  data: '__cmd=setConsultaClientes&SherchSolicitud=' + SherchSolicitud + '&SherchNombre=' + SherchNombre,
						  success: function(RESPUESTA) {
						  	 var DATOS = jQuery.parseJSON(RESPUESTA);
						  	 $("#RespuestaBusqueda").html(String(DATOS["RespuestaBusqueda"]));
						  	 $("#TbleContent").html(String(unescape(DATOS["TbleContent"])));
						  },
						  error: function( )   { },
						  complete: function(RESPUESTA) { }  
						});
		             
		  }catch(e){
		    alert(e);
		  }
	
	 });
 	
 	
  
});