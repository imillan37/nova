/**
 *
 * @author MarsVoltoso (CFA)
 * @category JavaScript
 * @created Mon Sep 15, 2014
 * @version 1.0
 */


$(document).ready(function (){
    //*******quitar el clear //
    $(".beatpicker-clear").remove();
    /**
     *
     *  @ busqueda Solicitudes
     */
/*
    $("#SherchSolicitud").on("change",function (){

        try{
            var SherchSolicitud = $("#SherchSolicitud").val();

            $.ajax( {type: 'POST',
                url:  '../Model/ModelReporteGralOficial.php',
                data: '__cmd=setConsultaSolicitudes&SherchSolicitud=' + SherchSolicitud,
                beforeSend: function(){
                    $("#Cargando").modal("show");
                },
                success: function(RESPUESTA) {

                    $("#TableContent").html(String(RESPUESTA));

                },
                error: function( )   { },
                complete: function(RESPUESTA) {
                    setTimeout(  '$("#Cargando").trigger("click")' ,1250);
                }
            });


        }catch(e){
            alert(e);
        }
    });

    $("#SherchNombre").change(function (){
        try{

            var SherchNombre = $("#SherchNombre").val();

            $.ajax( {type: 'POST',
                url:  '../Model/ModelReporteGralOficial.php',
                data: '__cmd=setConsultaSolicitudes&SherchNombre=' + SherchNombre,
                beforeSend: function(){
                    $("#Cargando").modal("show");
                },
                success: function(RESPUESTA) {
                    $("#TableContent").html(String(RESPUESTA));

                },
                error: function( )   { },
                complete: function(RESPUESTA) {
                    setTimeout(  '$("#Cargando").trigger("click")' ,1250);
                }
            });


        }catch(e){
            alert(e);
        }
    });

*/
    $("#Buscar").on("click",function (){
	
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1; //January is 0!
		var yyyy = today.getFullYear();
		
		if(dd<10) {
		    dd='0'+dd
		} 
		
		if(mm<10) {
		    mm='0'+mm
		} 
		
		today = mm+'/'+dd+'/'+yyyy;
	
	
        var SherchSolicitud = $("#SherchSolicitud").val();
        var SherchNombre = $("#SherchNombre").val();
        var Fecha_final = myDatePicker_final.getSelectedDate();
        var Fecha_inicial = myDatePicker_inicial.getSelectedDate();
		
		 if(Fecha_final == null || Fecha_inicial == null )
		 {
			 Fecha_inicial = "01/01/2000";
			 Fecha_final = today;
		 }
		
		if(SherchSolicitud != "" || SherchNombre != "")
        {
            getConsultaGral(SherchSolicitud,SherchNombre,'','');
        }else
        {
            if(Fecha_final == null || Fecha_inicial == null )
            {
                $("#ContendordivAlertas").html(String("Seleecione el rango de fechas"));
                $('.modalVistaAlertas').modal('show');
            }
            else{
                if(Fecha_inicial > Fecha_final)
                {
                    $("#ContendordivAlertas").html(String("La Fecha Inicial no puede ser mayor a la final"));
                    $('.modalVistaAlertas').modal('show');
                }else
                {
                    var date = new Date(Fecha_inicial);
                    var dia1 = date.getDate();
                    var mes1 = (date.getMonth() + 1);
                    var ano1 = date.getFullYear();

                    var date2 = new Date(Fecha_final);
                    var dia2 = date2.getDate();
                    var mes2 = (date2.getMonth() + 1);
                    var ano2 = date2.getFullYear();

                    var FechaInicial = ano1+'-'+mes1+'-'+dia1;
                    var FechaFinal =  ano2+'-'+mes2+'-'+dia2;

                    getConsultaGral(SherchSolicitud,SherchNombre,FechaInicial,FechaFinal);
                }
            }
        }
    });

$("#Buscar").trigger("click");
	
});



function muestraComentario(id_OficialCumplimiento)
{
    try{
        $.ajax( {type: 'POST',
            url:  '../Model/ModelReporteGralOficial.php',
            data: '__cmd=setConsultaComentario&id_OficialCumplimiento=' + id_OficialCumplimiento,
            beforeSend: function(){},
            success: function(RESPUESTA) {
                $("#ContendordivVistaComentarios").html(String(RESPUESTA));
                $('.modalVistaComentarios').modal('show');
            },
            error: function( )   { },
            complete: function(RESPUESTA) {}
        });


    }catch(e){
        alert(e);
    }

}


function getConsultaGral(SherchSolicitud,SherchNombre,FechaInicial,FechaFinal)
{
    try{
        $.ajax( {type: 'POST',
            url:  '../Model/ModelReporteGralOficial.php',
            data: '__cmd=setConsultaSolicitudes&SherchSolicitud=' + SherchSolicitud + '&SherchNombre=' + SherchNombre + '&FechaInicial=' + FechaInicial + '&FechaFinal=' + FechaFinal,
            beforeSend: function(){
                $("#Cargando").modal("show");
            },
            success: function(RESPUESTA) {

                $("#TableContent").html(String(RESPUESTA));

            },
            error: function( )   { },
            complete: function(RESPUESTA) {
                setTimeout(  '$("#Cargando").trigger("click")' ,1250);
            }
        });


    }catch(e){
        alert(e);
    }

}