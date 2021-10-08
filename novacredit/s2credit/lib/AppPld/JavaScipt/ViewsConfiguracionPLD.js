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
 *  @ Cargamos Vista  
 */ 	
 try{

     $.ajax( {type: 'POST',
         url:  '../Model/ModelConfiguracionPLD.php',
         data: '__cmd=getConfiguracion',
         success: function(RESPUESTA_JSON) {

             RESPUESTA_JSON = RESPUESTA_JSON.trim();
             RESPUESTA_JSON = String(RESPUESTA_JSON);
             var DATOS = jQuery.parseJSON(RESPUESTA_JSON);

             $("input:checkbox").each(function(){

                 var campo = $(this).attr("id");

                 if(DATOS[campo] == 'SI')
                 {
                     $("#"+campo+"").prop("checked",true);
                 }else
                 {
                     $("#"+campo+"").prop("checked",false);
                 }



             });
         },
         error: function( )   { },
         complete: function(RESPUESTA) { }
     });

	}catch(e){
		alert(e);
	}
				

    $("#GuardaCambios").on("click",function(){
        try{
            jsonObj = [];
            $("input:checkbox").each(function(){
                var campo = $(this).attr("id");
                var marcado = $(this).prop("checked");

                if(campo != "Terroristas")
                {
                    item = {};
                    item ["campo"] = campo;
                    item ["valor"] = marcado;
                    jsonObj.push(item);
                }



            });

            //alert(JSON.stringify(jsonObj));
            var campos = JSON.stringify(jsonObj);

            $.ajax( {type: 'POST',
                url:  '../Model/ModelConfiguracionPLD.php',
                data: '__cmd=setConfiguracion&Campos='+ campos ,
                success: function(RESPUESTA) {

                    RESPUESTA = RESPUESTA.trim();
                    RESPUESTA = String(RESPUESTA);

                    $("#ModalOK").modal("show");

                },
                error: function( )   { },
                complete: function(RESPUESTA) { }
            });


        }catch(e){
            alert(e);
        }
    });




}); // fin ready


function ActualizaOpcion(Obj)
{
	try{

		 var campo = $(Obj).attr("id");
		 var marcado = $("#"+campo+"").prop("checked");
		 var valor;
		 
		 if(marcado == true)
		 {
			 valor = "SI";
		 }
		 else
		 {
			 valor = "NO";
		 }
			 

		//alert(campo + '<-->'+valor);
        /* comentamos el ajax porque ya no va a funcionar asi y lo voy a quitar
		 $.ajax( {type: 'POST',
				  url:  '../Model/ModelConfiguracionPLD.php', 
				  data: '__cmd=setCampoPLD&campo='+campo+'&valor='+valor,
				  success: function(RESPUESTA) {
                      RESPUESTA = RESPUESTA.trim();
                      RESPUESTA = String(RESPUESTA);
					  if(RESPUESTA != 'OK')
					  {
						  $("#ModalErr").modal("show");
					  }

				  },
				  error: function( )   { },
				  complete: function(RESPUESTA) { }  
				});
				*/
		  //$('loader').modal('hide');
	}catch(e){
		alert(e);
	}

	
}

