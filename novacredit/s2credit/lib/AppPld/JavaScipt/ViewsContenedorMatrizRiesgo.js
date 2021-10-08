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
					  url:  '../Model/ModelMatrizRiesgo.php', 
					  data: 'puntosmin=si',
					  success: function(RESPUESTA) { $('#puntos_minimos').val(RESPUESTA);  },
					  error: function(RESPUESTA )   { },
					  complete: function(RESPUESTA) { }  
					});

 			$.ajax( {type: 'POST',
					  url:  '../Model/ModelMatrizRiesgo.php', 
					  data: 'validacioninfo_determinante=si',
					  success: function(RESPUESTA) { $("#tabla_elementos_eva").closest('tr').after(RESPUESTA); },
					  error: function(RESPUESTA )   { },
					  complete: function(RESPUESTA) { }  
					});

 			$.ajax( {type: 'POST',
					  url:  '../Model/ModelMatrizRiesgo.php', 
					  data: 'validacioninfo=si',
					  success: function(RESPUESTA) { $("#tabla_elementos_eva2").closest('tr').after(RESPUESTA); },
					  error: function(RESPUESTA )   { },
					  complete: function(RESPUESTA) { }  
					});
            
  
	$("#makesubmit").click(function (){
		
		     
			safesubmit($(this).val());
			 
				
               
	});	


});



//***************************************//

function SoloEnteros(evt)
{
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
 
         return true;
}
function SoloReales(evt)
{
   var charCode = (evt.which) ? evt.which : event.keyCode   
   if(charCode == 46) return true;  // Permite punto decimal   
   if (charCode > 31 && (charCode < 48 || charCode > 57))
      return false;
   return true;
}


function safesubmit(sender)
{
     
     $('#decide').modal('show');

     $("#Aceptar").on("click",function (){
					sender.disabled=true;        
        			document.forms['matriz'].elements['dispach'].value = 1;                
        			document.forms['matriz'].submit();
				});
				
	  $("#Cancelar").on("click",function (){
					sender.disabled=false
				});

    /*if(confirm(" Está Ud por cambiar la ponderación de la matriz de riesgo, ¿Desea continiar? "))
    {    
        sender.disabled=true;        
        document.forms['matriz'].elements['dispach'].value = 1;                
        document.forms['matriz'].submit();

    }
    
    sender.disabled=false*/;
}


function compara_maximos(sender)
{

	if( sender.value > 10 )
	{
		//sender.value=10;
	}

}


function actualiza_sumas()
{

       
        var ponderacion =   document.forms['matriz'].elements['ponderacion[]'];     
        

        
        var i = 0;
        var valor1 = "0";
        var key;
        var SumaUno = 0;
        
        
        for(i=0; i< ponderacion.length; i++)
        {
        
                key =   ponderacion[i].value;
                
                valor1 =   document.forms['matriz'].elements['ponderacion[]'][i].value;   
                _val1 = parseInt(valor1.replace(/\,/g,''));
                
                
                if(isNaN(_val1) )  _val1 = 0;
                
                SumaUno += _val1;
        
        }

        
        var cell1 =  document.getElementById('TotalPonderacion');
        

        cell1.innerHTML=addCommas(SumaUno.toFixed(0));
        
 
}


function addCommas(nStr)
{
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
}
