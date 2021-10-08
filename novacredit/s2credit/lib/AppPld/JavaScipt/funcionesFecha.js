/**
 *
 * @author MarsVoltoso (CFA), Confi (RLJ)
 * @category JavaScript
 * @created Mon Sep 15, 2014
 * @version 1.0
 */	
 
/**
 *
 *  @ esto es para que puedan funcionar las fechas y el archivo lo referenciamos al final de la vista 
 */ 
 
 
 $(document).ready(function(){
	 
	 
			
                
                myDatePicker_inicial.on("select", function (data) {
					var Fecha_final = myDatePicker_final.getSelectedDate();
					var Fecha_inical = myDatePicker_inicial.getSelectedDate();
					
					if(Fecha_final != null)
					{
						if(Fecha_final < Fecha_inical)
						{
							alert("¡La Fecha debe ser menor a la fecha final!");
							myDatePicker_inicial.reset();
						}else
						{
							//filtrar();
							//aqui vamos a hacer la superfuncion
						}
						
					}
					
                });
                
				
				
                myDatePicker_final.on("select", function (data) {

					var Fecha_final = myDatePicker_final.getSelectedDate();
					var Fecha_inical = myDatePicker_inicial.getSelectedDate();
					
					if(Fecha_inical != null)
					{
						if(Fecha_final < Fecha_inical)
						{
							alert("¡La Fecha debe ser mayor a la fecha inicial!");
							myDatePicker_final.reset();
						}else
						{
							//aqui vamos a hacer la superfuncion
							//filtrar();
						}
						
					}				

                });
				
				
				function GetFechaInicial()
				{
					var Fecha_inicial = myDatePicker_inicial.getSelectedDate();
					var Fecha ="";
					
					
					if(Fecha_inicial != null)
					{
						var date = new Date(Fecha_inicial);
						var dia1 = date.getDate();
						var mes1 = (date.getMonth() + 1);
						var ano1 = date.getFullYear()
						Fecha = ano1+'-'+mes1+'-'+dia1;
					}
					
					return Fecha;
			
				}
				
				function GetFechaFinal()
				{
					var Fecha_final = myDatePicker_final.getSelectedDate();
					var Fecha ="";
										
					if(Fecha_final != null)
					{
						var date2 = new Date(Fecha_final);
						var dia2 = date2.getDate();
						var mes2 = (date2.getMonth() + 1);
						var ano2 = date2.getFullYear()
						
						Fecha = ano2+'-'+mes2+'-'+dia2;
					}
						
					return Fecha;
			
				}						
                
});

