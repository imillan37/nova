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

    var PermisoOficial;
    try{
        /* aqui debe ir el ajax que traiga la fecha */



        $.ajax( {type: 'POST',
            url:  '../Model/ModelReescaneo.php',
            data: '__cmd=getTablaLog',
            success: function(RESPUESTA_JSON) {


                RESPUESTA_JSON = RESPUESTA_JSON.trim();
                RESPUESTA_JSON = String(RESPUESTA_JSON);
                var DATOS = jQuery.parseJSON(RESPUESTA_JSON);

                PermisoOficial = DATOS["PermisoOficial"];
                if(PermisoOficial == false)
                {
                    $("#TableContent").html("<tr class='error'><td colspan='4'>¡Usted no tiene permiso para ver este modulo!</td></tr> ");
                    //$("Reescaneo").attr( "disabled", true );
                }else
                {
                    $("#TableContent").html(DATOS["Tabla"]);
                }


            },
            error: function( )   { },
            complete: function(RESPUESTA) { }
        });



    }catch(e){
        alert(e);
    }




}); // fin ready

function VerDatos(Id_reescaneo)
{
    try{

        $.ajax( {type: 'POST',
            url:  '../Model/ModelReescaneo.php',
            data: '__cmd=getTabladtl&Id_reescaneo='+Id_reescaneo,
            success: function(RESPUESTA) {

                $("#TableContenido").html(RESPUESTA);
                $('#modalinfo').modal('show');
            },
            error: function( )   { },
            complete: function(RESPUESTA) { }
        });



    }catch(e){
        alert(e);
    }
}