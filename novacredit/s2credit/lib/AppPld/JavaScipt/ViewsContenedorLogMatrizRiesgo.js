/**
 *
 * @author MarsVoltoso (CFA)
 * @category JavaScript
 * @created Mon Sep 15, 2014
 * @version 1.0
 */	

$(document).ready(function (){ 
 
      datos(0,'');               
  }); 


function muestra(id){
  $.ajax( {type: 'POST',
              url:  '../Model/ModelLogMatrizRiesgo.php', 
              data: '__cmd=detalles&id='+id,
              success: function(RESPUESTA) {

                 $("#ContenedorInfo").html(RESPUESTA);
                   $('#modalinfo').modal('show');
              },
              error: function( )   { },
              complete: function(RESPUESTA) { }  
            });
}

function datos(Pagina,Evento){

 $.ajax( {type: 'POST',
            url:  '../Model/ModelLogMatrizRiesgo.php', 
            data: 'datos=si&Pagina='+Pagina+'&Evento='+Evento,
            success: function(RESPUESTA) {
              //alert(RESPUESTA);
            
              $("#datos").html(RESPUESTA);
             

            },
            error: function(RESPUESTA )   { },
            complete: function(RESPUESTA) { }  
          });

}

function ActualizaListado(Evento)
{

   var paginas = $("#paginacion").val();
   datos(paginas,Evento);
}

function CambiaPaginaListado(Obj)
{
   var paginas = $(Obj).val();
   datos(paginas,'');
}


