/**
 *
 * @author MarsVoltoso (CFA)
 * @category JavaScript
 * @created Mon Sep 15, 2014
 * @version 1.0
 */	

$(document).ready(function (){ 
 
      VerInformacion('','',1);
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

function VerInformacion(Filtro,Evento,Pagina){

 $.ajax( {type: 'POST',
             url:  '../Model/ModelConfiguracionPLD.php',
             data: '__cmd=getHistorico&Filtro='+ Filtro + '&Evento=' + Evento + '&Pagina=' + Pagina,
            success: function(RESPUESTA) {
              //alert(RESPUESTA);
            
              $("#ContendordivHistorico").html(RESPUESTA);
             

            },
            error: function(RESPUESTA )   { },
            complete: function(RESPUESTA) { }  
          });

}

//*****************FUNCIONES **************//
function ActualizaLista(Evento)
{
    var Paginacion  = $("#Paginacion").val();
    var Filtro     = "";
    VerInformacion(Filtro,Evento,Paginacion);
}

function CambiaPagina(OBJ)
{
    var Paginacion  = $(OBJ).val();
    var Filtro      = "";
    VerInformacion(Filtro,'',Paginacion);
}

function DetalleHistorico(ID_pld_originacion_log)
{
    $.ajax( {type: 'POST',
        url:  '../Model/ModelConfiguracionPLD.php',
        data: '__cmd=getDetalle&ID_pld_originacion_log='+ ID_pld_originacion_log,
        success: function(RESPUESTA_JSON) {
            //alert(RESPUESTA);

            //$("#ContenedorInfo").html(RESPUESTA);
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
            $("#Nombre_usuario").html("USUARIO QUE ACTUALIZO: " + DATOS.Nombre_usuario.toUpperCase());
            $("#Fecha_sistema").html("FECHA DE ACTUALIZACION: " + DATOS.Fecha_sistema.toUpperCase());
            $('#modalinfo').modal('show');

        },
        error: function(RESPUESTA )   { },
        complete: function(RESPUESTA) { }
    });
}
//***************************************//
