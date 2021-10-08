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
var MsjFecha;
var HoraValida;
var Error;

    try{
        /* aqui debe ir el ajax que traiga la fecha */



        $.ajax( {type: 'POST',
            async:false,
            url:  '../Model/ModelReescaneo.php',
            data: '__cmd=getFechaActualizacion',
            success: function(RESPUESTA_JSON) {


                RESPUESTA_JSON = RESPUESTA_JSON.trim();
                RESPUESTA_JSON = String(RESPUESTA_JSON);
                var DATOS = jQuery.parseJSON(RESPUESTA_JSON);

                if(DATOS["FechaReescaneo"] == "" || DATOS["FechaReescaneo"] == null)
                {
                    MsjFecha = "No se ha realizado ningun Escaneo";
                }else
                {
                    MsjFecha = "Ultimo Reescaneo "+DATOS["FechaReescaneo"];
                }
                $("#Fecha_actualizacion").html(MsjFecha);
                //$("#TableContent").html(DATOS["TableContent"]);
                PermisoOficial = DATOS["PermisoOficial"];
                HoraValida  = DATOS["HoraValida"];
                if(PermisoOficial == false)
                {
                    $("#TableContent").html("<tr class='error'><td colspan='7'>¡Usted no tiene permiso para ver este modulo!</td></tr> ");
                    $("#Reescaneo").addClass("disabled");
                }

                if(HoraValida != "")
                {
                    $("#TableContent").html("<tr class='error'><td colspan='7'>"+ HoraValida +"</td></tr> ");
                    $("#Reescaneo").addClass("disabled");
                    //$("Reescaneo").attr( "disabled", true );
                }

            },
            error: function( )   { },
            complete: function(RESPUESTA) { }
        });



    }catch(e){
        alert(e);
    }


    $("#Reescaneo").on("click",function(){
        if(PermisoOficial == true && HoraValida == "")
        {
            $.ajax( {type: 'POST',
                async:false,
                url:  '../Model/ModelReescaneo.php',
                data: '__cmd=setReescaneo',
                beforeSend: function(){
                    $("#Cargando").modal("show");
                },
                success: function(RESPUESTA_JSON) {

                    RESPUESTA_JSON = RESPUESTA_JSON.trim();
                    RESPUESTA_JSON = String(RESPUESTA_JSON);
                    try{
                        var DATOS = jQuery.parseJSON(RESPUESTA_JSON);
                        PermisoOficial = DATOS["PermisoOficial"];
                        HoraValida  = DATOS["HoraValida"];
                        Error       = DATOS["Error"];
                    }catch(e)
                    {
                        Error = "Ocurrio un problema comuniquese con s2credt";
                    }



                    if(Error == "" || Error == undefined)
                    {
                        if(PermisoOficial == false)
                        {
                            $("#TableContent").html("<tr class='error'><td colspan='7'>¡Usted no tiene permiso para ver este modulo!</td></tr> ");
                            //$("Reescaneo").attr( "disabled", true );
                        }else
                        {
                            if(HoraValida != "")
                            {
                                $("#TableContent").html("<tr class='error'><td colspan='7'>"+ HoraValida +"</td></tr> ");
                                //$("Reescaneo").attr( "disabled", true );
                            }else
                            {
                                $("#TableContent").html(DATOS["Tabla"]);
                                MsjFecha = "Reescaneo "+DATOS["FechaReescaneo"];
                                $("#Fecha_actualizacion").html(MsjFecha);
                            }
                        }
                    }
                    else
                    {
                        $("#TableContent").html("<tr class='error'><td colspan='7'>"+ Error +"</td></tr> ");
                    }


                },
                error: function( )   { },
                complete: function(RESPUESTA) {
                    setTimeout(  '$("#Cargando").trigger("click")' ,1250);
                }
            });
        }
    });



}); // fin ready

