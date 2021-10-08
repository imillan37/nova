/**
 *
 * @author Ignacio Ocampo
 * @category JavaScript
 * @created Wed Nov 26, 2014
 * @version 1.0
 */


$(document).ready(function (){
    $('*').click(function(e){
        var id = e.target.id;
        if(id !== 'autocomplete') {
        $("#autocomplete").fadeOut("slow");
        }
    });

    $(".beatpicker-clear").remove();

    $("#NombreCliente").keyup(function (){
        var NombreCliente = $("#NombreCliente").val();
        if(NombreCliente.length > 2) {
            //alert(NombreCliente);
            $("#autocomplete").fadeIn("slow");
            try{
            $.ajax( {type: 'POST',
                url:  '../Model/ModelReporteConsolidado.php',
                data: '__cmd=setConsultaCliente&NombreCliente=' + NombreCliente,
                beforeSend: function(){
                    //$("#Cargando").modal("show");
                },
                success: function(RESPUESTA) {

                    $("#autocomplete").html(String(RESPUESTA));

                },
                error: function( )   { },
                complete: function(RESPUESTA) {
                    //setTimeout(  '$("#Cargando").trigger("click")' ,1250);
                }
            });
        }catch(e){
            alert(e);
        }
        }

    });



    $("#Consultar").on("click",function (){

        var NumCliente = $.trim($("#NumCliente").val());
        var NombreCliente = $.trim($("#NombreCliente").val());
        var FechaInicial_ = FechaInicial.getSelectedDate();
        var FechaFinal_ = FechaFinal.getSelectedDate();

        //alert(FechaInicial_ +" <-> "+ FechaFinal_);

        var erorr_msg = '';

        if ((NumCliente === "" || isNaN(NumCliente)) && NombreCliente === "") {
            erorr_msg = 'Debe proporcionar el n&uacute;mero o el nombre del cliente.';
        } else if(FechaInicial_ !== null && FechaFinal_ !== null) {
            if(FechaFinal_ < FechaInicial_) {
                erorr_msg = 'La fecha inicial no puede ser mayor que la fecha final.';
            }
        }

        if(erorr_msg === '') {
            var FechaInicial1 = '';
            var FechaFinal1 =  '';
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
            

            getConsultaConsolidado(NumCliente, NombreCliente, FechaInicial1, FechaFinal1);
        } else {
            // Se presentaron errores
            $("#ContendordivAlertas").html(String(erorr_msg));
            $('.modalVistaAlertas').modal('show');
        }
    });

});


function getConsultaConsolidado(NumCliente, NombreCliente, FechaInicial, FechaFinal)
{
    try{
        $.ajax( {type: 'POST',
            url:  '../Model/ModelReporteConsolidado.php',
            data: '__cmd=setConsultaConsolidado&NumCliente=' + NumCliente + '&NombreCliente=' + NombreCliente + '&FechaInicial=' + FechaInicial + '&FechaFinal=' + FechaFinal,
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

function setNombre(NumCliente, NombreCliente)
{
    $("#NumCliente").val(NumCliente);
    $("#NombreCliente").val(NombreCliente);
    $("#autocomplete").fadeOut("slow");
    
}