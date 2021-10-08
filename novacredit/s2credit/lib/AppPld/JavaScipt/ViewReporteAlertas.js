/**
 *
 * @author Ignacio Ocampo
 * @category JavaScript
 * @created Tue Dec 30, 2014
 * @version 1.0
 */


$(document).ready(function (){
    //$(".beatpicker-clear").remove();
    $(".beatpicker-clear").css("height", "32px");
    $(".beatpicker-clear").html("x");
    
    $('.ui.selection.dropdown').dropdown('restore default text');

    /*
    $(".beatpicker-input").on("click",function (){
        $(this).attr("readonly", false);
    });
    */
    

    $("#Consultar").on("click",function (){
        var NumCliente = $("#NumCliente").val();
        var IDCredito = $("#IDCredito").val();
        var TipoAlerta = $("#tipo").val();
        var Status = $("#status").val();
        
        var FechaInicial_ = FechaInicial.getSelectedDate();
        var FechaFinal_ = FechaFinal.getSelectedDate();
        var FechaInicialMov_ = FechaInicialMov.getSelectedDate();
        var FechaFinalMov_ = FechaFinalMov.getSelectedDate();


        //alert(FechaInicial_ +" <-> "+ FechaFinal_ +" <-> "+ FechaInicialMov_ +" <-> "+ FechaFinalMov_);

        var erorr_msg = '';

        if(FechaInicial_ !== null && FechaFinal_ !== null) {
            if(FechaFinal_ < FechaInicial_ || FechaFinalMov_ < FechaInicialMov_) {
                erorr_msg = 'La fecha inicial no puede ser mayor que la fecha final.';
            }
        }

        if(erorr_msg === '') {
            var FechaInicial1 = '';
            var FechaFinal1 =  '';
            var FechaInicialMov1 = '';
            var FechaFinalMov1 =  '';
            if(FechaInicial_ !== null) {
                var date = new Date(FechaInicial_);
                var dia1 = date.getDate();
                var mes1 = (date.getMonth() + 1);
                var ano1 = date.getFullYear();
                FechaInicial1 = ano1+'-'+mes1+'-'+dia1;
            }
            if(FechaFinal_ !== null) {
                var date2 = new Date(FechaFinal_);
                var dia2 = date2.getDate();
                var mes2 = (date2.getMonth() + 1);
                var ano2 = date2.getFullYear();
                FechaFinal1 =  ano2+'-'+mes2+'-'+dia2;
            }

            if(FechaInicialMov_ !== null) {
                var datemov = new Date(FechaInicialMov_);
                var dia1mov = datemov.getDate();
                var mes1mov = (datemov.getMonth() + 1);
                var ano1mov = datemov.getFullYear();
                FechaInicialMov1 = ano1mov+'-'+mes1mov+'-'+dia1mov;
            }
            if(FechaFinalMov_ !== null) {
                var date2mov = new Date(FechaFinalMov_);
                var dia2mov = date2mov.getDate();
                var mes2mov = (date2mov.getMonth() + 1);
                var ano2mov = date2mov.getFullYear();
                FechaFinalMov1 =  ano2mov+'-'+mes2mov+'-'+dia2mov;
            }
            
            //alert(TipoAlerta +' - '+ Status);

            getConsultaAlertas(TipoAlerta, Status, FechaInicial1, FechaFinal1, IDCredito, NumCliente, FechaInicialMov1, FechaFinalMov1);
        } else {
            // Se presentaron errores
            $("#ContendordivAlertas").html(String(erorr_msg));
            $('.modalVistaAlertas').modal('show');
        }
        
    });


});


function getConsultaAlertas(TipoAlerta, Status, FechaInicial, FechaFinal, IDCredito, NumCliente, FechaInicialMov, FechaFinalMov)
{
    try{
        $.ajax( {type: 'POST',
            url:  '../Model/ModelReporteAlertas.php',
            data: '__cmd=setConsultaAlertas&TipoAlerta=' + TipoAlerta+ '&Status=' + Status + '&FechaInicial=' + FechaInicial + '&FechaFinal=' + FechaFinal + '&IDCredito=' + IDCredito + '&NumCliente=' + NumCliente + '&FechaInicialMov=' + FechaInicialMov + '&FechaFinalMov=' + FechaFinalMov,
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