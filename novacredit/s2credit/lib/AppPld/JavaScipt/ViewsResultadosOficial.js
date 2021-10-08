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
 *  @ Cargamos registros
 */ 	
 	
 try{
     		 
		 $.ajax( {type: 'POST',
				  url:  '../Model/ModelResultadosOficial.php', 
				  data: '__cmd=setConsultaSolicitudes',
				  success: function(RESPUESTA) {
                      RESPUESTA = RESPUESTA.trim();
                      RESPUESTA = String(RESPUESTA);
				  	 $("#ComentariosDichos").val("");
				  	 $("#TableContent").html(String(RESPUESTA))
				  },
				  error: function( )   { },
				  complete: function(RESPUESTA) { }  
				});
             
  }catch(e){
    alert(e);
  }
  
/** 
 *
 *  @ busqueda Solicitudes  
 */	
	
	$("#SherchSolicitud").change(function (){
		try{
             
            var SherchSolicitud = $("#SherchSolicitud").val(); 
             
            $.ajax( {type: 'POST',
				  url:  '../Model/ModelResultadosOficial.php', 
				  data: '__cmd=setConsultaSolicitudes&SherchSolicitud=' + SherchSolicitud,
				  success: function(RESPUESTA) {
                      RESPUESTA = RESPUESTA.trim();
                      RESPUESTA = String(RESPUESTA);
				  	 $("#TableContent").html(String(RESPUESTA))
				  },
				  error: function( )   { },
				  complete: function(RESPUESTA) { }  
				});

             
        }catch(e){
            alert(e);
        }
	});  
	
	$("#SherchNombre").change(function (){
		try{
             
            var SherchNombre = $("#SherchNombre").val(); 
             
            $.ajax( {type: 'POST',
				  url:  '../Model/ModelResultadosOficial.php', 
				  data: '__cmd=setConsultaSolicitudes&SherchNombre=' + SherchNombre,
				  success: function(RESPUESTA) {
                      RESPUESTA = RESPUESTA.trim();
                      RESPUESTA = String(RESPUESTA);
				  	 $("#TableContent").html(String(RESPUESTA))
				  },
				  error: function( )   { },
				  complete: function(RESPUESTA) { }  
				});

             
        }catch(e){
            alert(e);
        }
	});  
  

});

/**
 *
 *  @ function
 */ 

function DetallesSolicitud(ID_Solicitud){
	
	try{
     		 
		 $.ajax( {type: 'POST',
				  url:  '../Model/ModelResultadosOficial.php', 
				  data: '__cmd=setConsultaDetalles&ID_Solicitud=' + ID_Solicitud,
				  success: function(RESPUESTA) {
                      RESPUESTA = RESPUESTA.trim();
                      RESPUESTA = String(RESPUESTA);
				  	 $("#ComentariosDichos").val("");
				  	 $("#TableContentModal").html("");
				  	 $("#TableContentModal").html(String(RESPUESTA));
				  	 $("#modalAddPuesto").modal("show");
				  },
				  error: function( )   { },
				  complete: function(RESPUESTA) { }  
				});
             
	}catch(e){
     alert(e);
    }

} // fin function

function LibearComent(ID_Solicitud){
	
	var Coment = $("#ComentariosDichosLiberar").val();
	
	if( Coment != "" && Coment !== undefined ){
	
		try{
	     		 
			 $.ajax( {type: 'POST',
					  url:  '../Model/ModelResultadosOficial.php', 
					  data: '__cmd=setLiberaSolicitud&ID_Solicitud=' + ID_Solicitud + '&Coment=' + Coment,
					  success: function(RESPUESTA) {
                          RESPUESTA = RESPUESTA.trim();
                          RESPUESTA = String(RESPUESTA);
					  	 if( RESPUESTA == "LISTO" ){
					  	 	 $('#ModalConfirmacion').modal('show');
						  	 $("#CerrarLiberaSolicitud").trigger("click");
					  	 	 ActualizaGrid();
						 }else{
						     $('#ModalConfirmacionError').modal('show');
						  	 $("#CerrarLiberaSolicitud").trigger("click");
						     ActualizaGrid();  
					  	 }
					  },
					  error: function( )   { },
					  complete: function(RESPUESTA) { }  
					});
	             
		}catch(e){
	     alert(e);
	    }
    
    }else{
	    $('#ModalComentError').modal('show');
	    $("#CerrarModalPuesto").trigger("click");
    } // fin if( Coment != "" ){

} // fin function

function LiberarSolicitud(ID_Solicitud){
	
	$("#modalLiberaSolicitud").modal("show");
	
	try{
	
		 $.ajax( {type: 'POST',
				  url:  '../Model/ModelResultadosOficial.php', 
				  data: '__cmd=setLiberaSolicitudDtl&ID_Solicitud=' + ID_Solicitud,
				  success: function(RESPUESTA) {
                          RESPUESTA = RESPUESTA.trim();
                          RESPUESTA = String(RESPUESTA);
				  		$("#TableContentModalLiberar").html(String(RESPUESTA));
				  	
				  },
				  error: function( )   { },
				  complete: function(RESPUESTA) { }  
				});
	
	
	 }catch(e){
     alert(e);
    }

	
} // fin function 

function ActualizaGrid(){
	
   try{
     		 
		 $.ajax( {type: 'POST',
				  url:  '../Model/ModelResultadosOficial.php', 
				  data: '__cmd=setConsultaSolicitudes',
				  success: function(RESPUESTA) {
                      RESPUESTA = RESPUESTA.trim();
                      RESPUESTA = String(RESPUESTA);
				  	 $("#TableContent").html(String(RESPUESTA))
				  },
				  error: function( )   { },
				  complete: function(RESPUESTA) { }  
				});
             
  }catch(e){
    alert(e);
  }

	
}

function CancelacionCompleta(ID_Solicitud){
	
  $("#modalCancelarSolicitud").modal("show");
	
	try{
	
		 $.ajax( {type: 'POST',
				  url:  '../Model/ModelResultadosOficial.php', 
				  data: '__cmd=setLiberaCancelarDtl&ID_Solicitud=' + ID_Solicitud,
				  success: function(RESPUESTA) {
                      RESPUESTA = RESPUESTA.trim();
                      RESPUESTA = String(RESPUESTA);
				  		$("#TableContentModalCancelar").html(String(RESPUESTA));
				  	
				  },
				  error: function( )   { },
				  complete: function(RESPUESTA) { }  
				});
	
	
	 }catch(e){
     alert(e);
    }	

} // FON IF

function CancelarComent(ID_Solicitud){
	
	var Coment = $("#ComentariosDichosCancelar").val(); 
	
	if( Coment != "" && Coment !== undefined ){
		
		try{
	     	
	     	$.ajax( {type: 'POST',
					  url:  '../Model/ModelResultadosOficial.php', 
					  data: '__cmd=setBajaSolicitud&ID_Solicitud=' + ID_Solicitud + '&Coment=' + Coment,
					  success: function(RESPUESTA) {

                          RESPUESTA = RESPUESTA.trim();
                          RESPUESTA = String(RESPUESTA);

					  	 if( RESPUESTA == "LISTO" ){
						  	$('#ModalConfirmacionCancel').modal('show');
						  	$("#CerrarCancelarSolicitud").trigger("click");
						  	ActualizaGrid();
						 }else{
						  	$('#ModalConfirmacionError').modal('show');
						  	$("#CerrarCancelarSolicitud").trigger("click");
						  	ActualizaGrid();  
					  	 }
					  },
					  error: function( )   { },
					  complete: function(RESPUESTA) { }  
					});
	             
		}catch(e){
	     alert(e);
	    }
    
    }else{
	    $('#ModalComentError').modal('show');
	    $("#CerrarCancelarSolicitud").trigger("click");
    } // fin if( Coment != "" ){
	
} // FON IF

function GuardarInformacion(ID_Solicitud){
	
	var ComentariosDichos = $("#ComentariosDichos").val();
	
  	try{
     		 
		 $.ajax( {type: 'POST',
				  url:  '../Model/ModelResultadosOficial.php', 
				  data: '__cmd=setComentatiosSolicitud&ID_Solicitud=' + ID_Solicitud + '&ComentariosDichos=' + ComentariosDichos,
				  success: function(RESPUESTA) {

                      RESPUESTA = RESPUESTA.trim();
                      RESPUESTA = String(RESPUESTA);

				  	 if( RESPUESTA == "LISTO" ){
					  	$('#ModalConfirmacionComent').modal('show');
					  	$("#CerrarModalPuesto").trigger("click");
					 }else{
					  	$('#ModalConfirmacionError').modal('show');
					  	$("#CerrarModalPuesto").trigger("click");
					 }
				  },
				  error: function( )   { },
				  complete: function(RESPUESTA) { }  
				});
             
	}catch(e){
     alert(e);
    }
	
} // fin