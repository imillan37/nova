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
 *  	
 */ 	     $.ajax( {type: 'POST',
					  url:  '../Model/ModelLogActualizaciones.php', 
					  data: 'dolares=si',
					  success: function(RESPUESTA) {
              //alert(RESPUESTA);
              var DATOS = jQuery.parseJSON(RESPUESTA);
              //alert(unescape(DATOS[0]))
              $("#dolaress").closest('td').after(unescape(DATOS["fila"][0]));
              $("#tr_dolaress").addClass(DATOS["clase"][0]);  

            },
					  error: function(RESPUESTA )   { },
					  complete: function(RESPUESTA) { }  
					});


           $.ajax( {type: 'POST',
            url:  '../Model/ModelLogActualizaciones.php', 
            data: 'unidades=si',
            success: function(RESPUESTA) {
             // alert(RESPUESTA);
             var DATOS = jQuery.parseJSON(RESPUESTA);
              $("#unidadess").closest('td').after(unescape(DATOS["fila"][0])); 
              $("#tr_unidadess").addClass(DATOS["clase"][0]);

            },
            error: function(RESPUESTA )   { },
            complete: function(RESPUESTA) { }  
          });
            
          $.ajax( {type: 'POST',
            url:  '../Model/ModelLogActualizaciones.php',
            data: 'terroristas=si',
            success: function(RESPUESTA) {
              //alert(RESPUESTA);
               var DATOS = jQuery.parseJSON(RESPUESTA);
              $("#terroristass").closest('td').after(unescape(DATOS["fila"][0]));
              $("#tr_terroristass").addClass(DATOS["clase"][0]);

            },
            error: function(RESPUESTA )   { },
            complete: function(RESPUESTA) { }
          });

          $.ajax( {type: 'POST',
            url:  '../Model/ModelLogActualizaciones.php',
            data: 'listas_propias=si',
            success: function(RESPUESTA) {
                //alert(RESPUESTA);
                var DATOS = jQuery.parseJSON(RESPUESTA);
                $("#ListasPropias").closest('td').after(unescape(DATOS["fila"][0]));
                $("#tr_Listas_Propias").addClass(DATOS["clase"][0]);

            },
            error: function(RESPUESTA )   { },
            complete: function(RESPUESTA) { }
          });

          $.ajax( {type: 'POST',
            url:  '../Model/ModelLogActualizaciones.php',
            data: 'listas_condusef=si',
            success: function(RESPUESTA) {
                //alert(RESPUESTA);
                var DATOS = jQuery.parseJSON(RESPUESTA);
                $("#ListasCondusef").closest('td').after(unescape(DATOS["fila"][0]));
                $("#tr_Listas_Condusef").addClass(DATOS["clase"][0]);

            },
            error: function(RESPUESTA )   { },
            complete: function(RESPUESTA) { }
          });


           $.ajax( {type: 'POST',
            url:  '../Model/ModelLogActualizaciones.php', 
            data: 'PPE=si',
            success: function(RESPUESTA) {
              //alert(RESPUESTA);
               var DATOS = jQuery.parseJSON(RESPUESTA);
              $("#PPEs").closest('td').after(unescape(DATOS["fila"][0])); 
              $("#tr_PPEs").addClass(DATOS["clase"][0]);
               
            },
            error: function(RESPUESTA )   { },
            complete: function(RESPUESTA) { }  
          });
          
          $.ajax( {type: 'POST',
            url:  '../Model/ModelLogActualizaciones.php', 
            data: 'SAT=si',
            success: function(RESPUESTA) {
              //alert(RESPUESTA);
               var DATOS = jQuery.parseJSON(RESPUESTA);
              $("#sat").closest('td').after(unescape(DATOS["fila"][0])); 
              $("#tr_sat").addClass(DATOS["clase"][0]);
               
            },
            error: function(RESPUESTA )   { },
            complete: function(RESPUESTA) { }  
          });

           $.ajax( {type: 'POST',
            url:  '../Model/ModelLogActualizaciones.php', 
            data: 'puestosPPE=si',
            success: function(RESPUESTA) {
              //alert(RESPUESTA);
                var DATOS = jQuery.parseJSON(RESPUESTA);
              $("#puestosPPEs").closest('td').after(unescape(DATOS["fila"][0])); 
              $("#tr_puestosPPEs").addClass(DATOS["clase"][0]);
               
            },
            error: function(RESPUESTA )   { },
            complete: function(RESPUESTA) { }  
          });

           $.ajax( {type: 'POST',
            url:  '../Model/ModelLogActualizaciones.php', 
            data: 'CP=si',
            success: function(RESPUESTA) {
              //alert(RESPUESTA);
               var DATOS = jQuery.parseJSON(RESPUESTA);
              $("#CPs").closest('td').after(unescape(DATOS["fila"][0])); 
              $("#tr_CPs").addClass(DATOS["clase"][0]);
              
            },
            error: function(RESPUESTA )   { },
            complete: function(RESPUESTA) { }  
          });


            $.ajax( {type: 'POST',
            url:  '../Model/ModelLogActualizaciones.php', 
            data: 'Estado=si',
            success: function(RESPUESTA) {
              //alert(RESPUESTA);
                var DATOS = jQuery.parseJSON(RESPUESTA);
              $("#estadoss").closest('td').after(unescape(DATOS["fila"][0])); 
              $("#tr_estadoss").addClass(DATOS["clase"][0]);
              
            },
            error: function(RESPUESTA )   { },
            complete: function(RESPUESTA) { }  
          });


          $.ajax( {type: 'POST',
            url:  '../Model/ModelLogActualizaciones.php', 
            data: 'Ciudades=si',
            success: function(RESPUESTA) {
              //alert(RESPUESTA);
                var DATOS = jQuery.parseJSON(RESPUESTA);
              $("#ciudadess").closest('td').after(unescape(DATOS["fila"][0])); 
              $("#tr_ciudadess").addClass(DATOS["clase"][0]);
              
            },
            error: function(RESPUESTA )   { },
            complete: function(RESPUESTA) { }  
          });

        $.ajax( {type: 'POST',
            url:  '../Model/ModelLogActualizaciones.php', 
            data: 'Giros=si',
            success: function(RESPUESTA) {
              //alert(RESPUESTA);
                var DATOS = jQuery.parseJSON(RESPUESTA);
              $("#giross").closest('td').after(unescape(DATOS["fila"][0])); 
              $("#tr_giross").addClass(DATOS["clase"][0]);
               
            },
            error: function(RESPUESTA )   { },
            complete: function(RESPUESTA) { }  
          });
          
        $.ajax( {type: 'POST',
            url:  '../Model/ModelLogActualizaciones.php', 
            data: 'paiss=si',
            success: function(RESPUESTA) {
                var DATOS = jQuery.parseJSON(RESPUESTA);
                $("#paiss").closest('td').after(unescape(DATOS["fila"][0])); 
                $("#tr_paiss").addClass(DATOS["clase"][0]);
            },
            error: function(RESPUESTA )   { },
            complete: function(RESPUESTA) { }  
        });
  


$("#verdolar").click(function (){
         
    $("#Unidades").val("UDIS");
    VistaDolares("DOLARES",0,'','','')
                      
  }); 

$("#verunidades").click(function (){
         
    $("#Unidades").val("UDIS");
    VistaUnidades("UNIDADES",0,'','','')
                      
  });

  $("#verterro").click(function (){
         
    $("#Unidades").val("UDIS");
    VistaTerroristas("TERRORISTAS",0,'','','')
                      
  });

    $("#verlistasCondusef").click(function (){

        $("#Unidades").val("ListasCondusef");
        VistaListasCondusef("ListasCondusef",0,'','','')

    });

    $("#verListasPropias").click(function (){

        $("#Unidades").val("ListasPropias");
        VistaListasPropias("ListasPropias",0,'','','')

    });

    $("#verListasPPE").click(function (){

        $("#Unidades").val("ListasPPE");
        VistaListasPPE("ListasPPE",0,'','','')

    });

});

function VistaDolares(Tipo,Pagina,Evento,Fecha_inicial,Fecha_final)
{
  
    try{

         $(".beatpicker-clear").remove();

         $.ajax( {type: 'POST',
              url:  '../Model/ModelLogActualizaciones.php', 
              data: '__cmd=setVistaDolares&Tipo='+Tipo+'&Pagina='+Pagina+'&Evento='+Evento+'&Fecha_inicial='+Fecha_inicial+'&Fecha_final='+Fecha_final,
              success: function(RESPUESTA) {
               $("#ContendordivListadoUnidades").html(String(RESPUESTA))
               $('#modalinfo').modal('show');      
              },
              error: function( )   { },
              complete: function(RESPUESTA) { }  
            });
          //$('loader').modal('hide');
      }catch(e){
        alert(e);
      }
}


function VistaTerroristas(Tipo,Pagina,Evento,Fecha_inicial,Fecha_final)
{

    try{

        $(".beatpicker-clear").remove();

        $.ajax( {type: 'POST',
            url:  '../Model/ModelLogActualizaciones.php',
            data: '__cmd=setVistaTerroristas&Tipo='+Tipo+'&Pagina='+Pagina+'&Evento='+Evento+'&Fecha_inicial='+Fecha_inicial+'&Fecha_final='+Fecha_final,
            success: function(RESPUESTA) {
                $("#ContendordivListadoUnidades").html(String(RESPUESTA))
                $('#modalinfo').modal('show');
            },
            error: function( )   { },
            complete: function(RESPUESTA) { }
        });
        //$('loader').modal('hide');
    }catch(e){
        alert(e);
    }
}


function VistaListasCondusef(Tipo,Pagina,Evento,Fecha_inicial,Fecha_final)
{

    try{

        $(".beatpicker-clear").remove();

        $.ajax( {type: 'POST',
            url:  '../Model/ModelLogActualizaciones.php',
            data: '__cmd=setVistaListaCondusef&Tipo='+Tipo+'&Pagina='+Pagina+'&Evento='+Evento+'&Fecha_inicial='+Fecha_inicial+'&Fecha_final='+Fecha_final,
            success: function(RESPUESTA) {
                $("#ContendordivListadoUnidades").html(String(RESPUESTA))
                $('#modalinfo').modal('show');
            },
            error: function( )   { },
            complete: function(RESPUESTA) { }
        });
        //$('loader').modal('hide');
    }catch(e){
        alert(e);
    }
}
function VistaListasPropias(Tipo,Pagina,Evento,Fecha_inicial,Fecha_final)
{

    try{

        $(".beatpicker-clear").remove();

        $.ajax( {type: 'POST',
            url:  '../Model/ModelLogActualizaciones.php',
            data: '__cmd=setVistaListasPropias&Tipo='+Tipo+'&Pagina='+Pagina+'&Evento='+Evento+'&Fecha_inicial='+Fecha_inicial+'&Fecha_final='+Fecha_final,
            success: function(RESPUESTA) {
                $("#ContendordivListadoUnidades").html(String(RESPUESTA))
                $('#modalinfo').modal('show');
            },
            error: function( )   { },
            complete: function(RESPUESTA) { }
        });
        //$('loader').modal('hide');
    }catch(e){
        alert(e);
    }
}


function VistaListasPPE(Tipo,Pagina,Evento,Fecha_inicial,Fecha_final)
{

    try{

        $(".beatpicker-clear").remove();

        $.ajax( {type: 'POST',
            url:  '../Model/ModelLogActualizaciones.php',
            data: '__cmd=setVistaListasPPE&Tipo='+Tipo+'&Pagina='+Pagina+'&Evento='+Evento+'&Fecha_inicial='+Fecha_inicial+'&Fecha_final='+Fecha_final,
            success: function(RESPUESTA) {
                $("#ContendordivListadoUnidades").html(String(RESPUESTA))
                $('#modalinfo').modal('show');
            },
            error: function( )   { },
            complete: function(RESPUESTA) { }
        });
        //$('loader').modal('hide');
    }catch(e){
        alert(e);
    }
}




function VistaUnidades(Tipo,Pagina,Evento,Fecha_inicial,Fecha_final)
{
  
    try{

         $(".beatpicker-clear").remove();

         $.ajax( {type: 'POST',
              url:  '../Model/ModelLogActualizaciones.php', 
              data: '__cmd=setVistaUnidades&Tipo='+Tipo+'&Pagina='+Pagina+'&Evento='+Evento+'&Fecha_inicial='+Fecha_inicial+'&Fecha_final='+Fecha_final,
              success: function(RESPUESTA) {
               $("#ContendordivListadoUnidades").html(String(RESPUESTA))
               $('#modalinfo').modal('show');      
              },
              error: function( )   { },
              complete: function(RESPUESTA) { }  
            });
          //$('loader').modal('hide');
      }catch(e){
        alert(e);
      }
}


function ActualizaListadoUnidades(Evento)
{

   var paginas = $("#paginacionUni").val();
   var Tipo = $("#Unidades").val();
   VistaUnidades(Tipo,paginas,Evento,'','' );
}

function CambiaPaginaListadoUnidades(Obj)
{
   var paginas = $(Obj).val();
   var Tipo = $("#Unidades").val();
   VistaUnidades(Tipo,paginas,'','','' );
}


function ActualizaListadoDolares(Evento)
{

   var paginas = $("#paginacionUni").val();
   var Tipo = $("#Unidades").val();
   VistaDolares(Tipo,paginas,Evento,'','' );
}

function CambiaPaginaListadoDolares(Obj)
{
   var paginas = $(Obj).val();
   var Tipo = $("#Unidades").val();
   VistaDolares(Tipo,paginas,'','','' );
}


function ActualizaListadoTerroristas(Evento)
{

   var paginas = $("#paginacionUni").val();
   var Tipo = $("#Unidades").val();
   VistaTerroristas(Tipo,paginas,Evento,'','' );
}

function CambiaPaginaListadoTerroristas(Obj)
{
   var paginas = $(Obj).val();
   var Tipo = $("#Unidades").val();
   VistaTerroristas(Tipo,paginas,'','','' );
}

function ActualizaListasCondusef(Evento)
{

    var paginas = $("#paginacionUni").val();
    var Tipo = $("#Unidades").val();
    VistaListasCondusef(Tipo,paginas,Evento,'','' );
}

function CambiaPaginaListasCondusef(Obj)
{
    var paginas = $(Obj).val();
    var Tipo = $("#Unidades").val();
    VistaListasCondusef(Tipo,paginas,'','','' );
}

function ActualizaListasPropias(Evento)
{

    var paginas = $("#paginacionUni").val();
    var Tipo = $("#Unidades").val();
    VistaListasCondusef(Tipo,paginas,Evento,'','' );
}

function CambiaPaginaListasPropias(Obj)
{
    var paginas = $(Obj).val();
    var Tipo = $("#Unidades").val();
    VistaListasCondusef(Tipo,paginas,'','','' );
}

function ActualizaListasPPPE(Evento)
{

    var paginas = $("#paginacionUni").val();
    var Tipo = $("#Unidades").val();
    VistaListasPPE(Tipo,paginas,Evento,'','' );
}

function CambiaPaginaListasPPE(Obj)
{
    var paginas = $(Obj).val();
    var Tipo = $("#Unidades").val();
    VistaListasPPE(Tipo,paginas,'','','' );
}


function verinfo(Pagina,Evento,Evento2){

  //alert(Evento2);


        $.ajax( {type: 'POST',
            url:  '../Model/ModelLogActualizaciones.php',
            data: '__cmd=setVistasCatalogos&Pagina='+Pagina+'&Evento='+Evento+'&Evento2='+Evento2,
            success: function(RESPUESTA) {
                $("#ContendordivListadoUnidades").html(String(RESPUESTA))
                $('#modalinfo').modal('show');
            },
            error: function( )   { },
            complete: function(RESPUESTA) { }
        });
}


function ActualizaListasInfo(Evento,Evento2)
{

    var paginas = $("#paginacionUni").val();
    var Tipo = $("#Unidades").val();

    verinfo(paginas,Evento,Evento2 );
}

function CambiaPaginaListasInfo(Obj,Evento2)
{
    var paginas = $(Obj).val();
    var Tipo = $("#Unidades").val();
    verinfo(paginas,'',Evento2);
}