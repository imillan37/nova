/**
 *
 * @author MarsVoltoso (CFA)
 * @category JavaScript
 * @created Mon Sep 15, 2014
 * @version 1.0
 */


$(document).ready(function (){

    $('.ui.selection.dropdown').dropdown();

    try{

        $.ajax( {type: 'POST',
            url:  '../Model/ModelReporteSolicitudes.php',
            data: '__cmd=getSucursales',
            success: function(RESPUESTA) {
                $("#SucursalDrop").html(String(RESPUESTA));
                $('.ui.selection.dropdown').dropdown();
            },
            error: function( )   { },
            complete: function(RESPUESTA) { }
        });

    }catch(e){
        alert(e);
    }

    $("#Buscar").on("click",function (){

        var Periodo =  $("#Periodo").val();
        var Sucursal = $("#Sucursales").val();

        var SherchCLiente = $("#SherchCLiente").val();
        var SherchNombre = $("#SherchNombre").val();


        $('.ui.form')
            .form({
                Periodo: {
                    identifier  : 'Periodo',
                    rules: [
                        {
                            type   : 'empty',
                            prompt : 'Please enter a Periodo'
                        }
                    ]
                }
            })
        ;


        if(Periodo == "")
        {
            $("#ContendordivAlertas").html(String("Seleecione el periodo"));
            $('.modalVistaAlertas').modal('show');
        }else if(Sucursal == "")
        {
            $("#ContendordivAlertas").html(String("Seleecione la sucursal"));
            $('.modalVistaAlertas').modal('show');
        }else
        {
            getSolicitudesAct(SherchCLiente,SherchNombre,Periodo,Sucursal);
        }



    });

});


function getSolicitudesAct(SherchCLiente,SherchNombre,Periodo,Sucursal)
{
    try{

        $.ajax( {type: 'POST',
            url:  '../Model/ModelReporteSolicitudes.php',
            data: '__cmd=getSolicitudes&SherchCLiente=' + SherchCLiente + '&SherchNombre=' + SherchNombre + '&Periodo=' + Periodo + '&Sucursal='+ Sucursal,
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

function ActualizaDatos (ID_Cliente, ID_Tipocredito)
{
    //alert(ID_Cliente +"<-->"+ ID_Tipocredito);
    var pkey  = "pld_perfil_transaccional.ID_Cliente = `"+ID_Cliente+"`";
    var edkey = "pld_perfil_transaccional.ID_Cliente = `"+ID_Cliente+"`";

   //window.location.href = '../../../sys/pld/perfil_transaccional.php?consulta=Consulta&tipo_riesgo=Bajo&pkey='+pkey+'&edkey='+edkey+'&id_tipocredito='+ID_Tipocredito+'&actualizado=SI';

    window.location.href = '../../../sys/pld/perfil_transaccional.php?filtro1=1&desdecliente=5&consulta=Consulta&edkey='+edkey;
}