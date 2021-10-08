/**
 *
 * @author MarsVoltoso (CFA)
 * @category JavaScript
 * @created Mon Sep 15, 2014
 * @version 1.0
 */	

/**
 *
 *  @ Variables frmDigitalizar 
 */

var frmDigitalizar = {
		beforeSubmit: function() { 
			
			if( $("#FileContenct").attr("fullclass") == "No" ){
					return false;
			} // fin if
					
		 },
		 success: function( responseText, statusText, xhr, $form ) {
             responseText = responseText.trim();
             responseText = String(responseText);
		
			 if( responseText == "EXTENCION NO VALIDA" ){
				 $("#ErroLeyenda").html("!La extenci�n del archivo no es correcta: <u>Por favor revise su configuraci�n de sistema oprativo o elija otro archivo</u>!");
			 }else if( responseText == "ERROR TRASFERENCIA" ){
				 $("#ErroLeyenda").html("�Error en la transferencia del archivo!");
			 }else if( responseText == "EL CONTENIDO NO ES VALIDO" ){
				 $("#ErroLeyenda").html("�Error el contenido no es valido!");
			 }else if( responseText == "LISTO" ){
				 $("#ErroLeyenda").html("<b><font color='blue'>Correcto</font></b>");
				 location.replace("ViewsContenedorCatalogos.php");
			 }
		
		 }
	   };

$(document).ready(function (){

/**
 *
 *  @ Cargamos Nuevo Personas
 */ 	
	
	$("#PPEadd").click(function (){
        //***********modificacion por RLJ para el curp**********//
		try{
		     $("#Nombre").val("");
			 $("#ApPaterno").val("");
			 $("#ApMaterno").val("");
			 $("#RFC").val("");
             $("#CURP").val("");
             $("#TipoPersona").val("PPE");
             $("#TipoPersonaFile").val("PPE");
			 $("#uiSibmit").attr({ name: "" });
             $('#ImportarManual').modal('show');	
             
				$("#Manual").on("click",function (){
					SleepShowModal(600, ShowModalParams , "modalAdd" );
				});
				
				$("#Archivo").on("click",function (){
					$("#ErroLeyenda").html("");
					SleepShowModal(600, ShowModalParams , "ImportarArchivo" );
				});
				
               	
		}catch(e){
            alert(e);
     }
	});
	
	$("#ListasSAT").click(function (){

        $("#TipoPersona").val("SAT");
        $("#TipoPersonaFile").val("SAT");
        $("#BuscaRFClp").val("");
        $("#TipoListado").val("SAT");
        $("#Paginacion").val("1");
        ListadoSAT('','',1);

    });
    
    $("#ListasPropias").click(function (){

        $("#TipoPersona").val("LP");
        $("#TipoPersonaFile").val("LP");
        $("#BuscaRFClp").val("");
        $("#TipoListado").val("LP");
        $("#Paginacion").val("1");
        ListadoPropio('','',1);

    });

    /**
     *
     *  @ Cargamos Nuevo Personas
     */

    $("#ListasPropiasAdd").click(function (){
        //***********modificacion por RLJ para el curp**********//
        try{
            $("#Nombre").val("");
            $("#ApPaterno").val("");
            $("#ApMaterno").val("");
            $("#RFC").val("");
            $("#CURP").val("");
            $("#TipoPersona").val("LP");
            $("#TipoPersonaFile").val("LP");
            $("#uiSibmit").attr({ name: "" });
            $('#ImportarManual').modal('show');

            $("#Manual").on("click",function (){
                SleepShowModal(600, ShowModalParams , "modalAdd" );
            });

            $("#Archivo").on("click",function (){
	            $("#ErroLeyenda").html("");
                SleepShowModal(600, ShowModalParams , "ImportarArchivo" );
            });


        }catch(e){
            alert(e);
        }
    });
    
    /**
     *
     *  @ Cargamos Nuevo Personas
     */

    $("#ListasSATAdd").click(function (){
        //***********modificacion por RLJ para el curp**********//
        try{
            $("#Nombre").val("");
            $("#ApPaterno").val("");
            $("#ApMaterno").val("");
            $("#RFC").val("");
            $("#CURP").val("");
            $("#TipoPersona").val("SAT");
            $("#TipoPersonaFile").val("SAT");
            $("#uiSibmit").attr({ name: "" });
            $('#ImportarManual').modal('show');

            $("#Manual").on("click",function (){
                SleepShowModal(600, ShowModalParams , "modalAdd" );
            });

            $("#Archivo").on("click",function (){
	            $("#ErroLeyenda").html("");
                SleepShowModal(600, ShowModalParams , "ImportarArchivo" );
            });


        }catch(e){
            alert(e);
        }
    });

    /**
     *
     *  @ Cargamos Consulta LP RLJ
     */

    $("#BuscaRFClp").on("keyup",function (){
        try{

            var BuscaRFC = $("#BuscaRFClp").val();
            ListadoPropio(BuscaRFC,'',1);
		}catch(e){
            alert(e);
        }
    });
    
    $("#BuscaRFCSat").on("keyup",function (){
        try{
			var BuscaRFC = $("#BuscaRFCSat").val();
            ListadoSAT(BuscaRFC,'',1);
		}catch(e){
            alert(e);
        }
    });

    /**
     * listas condusef RLJ
     */
    $("#Condusef").click(function (){

        $("#TipoPersona").val("LC");
        $("#TipoPersonaFile").val("LC");
        $("#TipoListado").val("LC");

        $("#BuscaRFClc").val("");
        $("#Paginacion").val("1");
        ListadoCondusef("","",1);


    });

    /**
     *
     *  @ Cargamos Nuevo Personas
     */

    $("#CondusefAdd").click(function (){
        //***********modificacion por RLJ para el curp**********//
        try{
			$('#_FileContenct').val("");
			$("#Nombre").val("");
            $("#ApPaterno").val("");
            $("#ApMaterno").val("");
            $("#RFC").val("");
            $("#CURP").val("");
            $("#TipoPersona").val("LC");
            $("#TipoPersonaFile").val("LC");
            $("#uiSibmit").attr({ name: "" });
            $('#ImportarManual').modal('show');

            $("#Manual").on("click",function (){
                SleepShowModal(600, ShowModalParams , "modalAdd" );
            });

            $("#Archivo").on("click",function (){
	            $("#ErroLeyenda").html("");
                SleepShowModal(600, ShowModalParams , "ImportarArchivo" );
            });


        }catch(e){
            alert(e);
        }
    });

    /**
     *
     *  @ Cargamos Consulta LP RLJ
     */

    $("#BuscaRFClc").on("keyup",function (){
        try{

            var BuscaRFC = $("#BuscaRFClc").val();
            ListadoCondusef(BuscaRFC,"",1);


        }catch(e){
            alert(e);
        }
    });

    /**
 *
 *  @ Documento a digitalizar  
 */ 

 var fileExtentionRange = '.txt';
 var MAX_SIZE = 10; // MB

		$(document).on('change', '.btn-file :file', function() {
		    var input = $(this);
		
		    if (navigator.appVersion.indexOf("MSIE") != -1) { // IE
		        var label = input.val();
		
		        input.trigger('fileselect', [ 1, label, 0 ]);
		    } else {
		        var label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
		        var numFiles = input.get(0).files ? input.get(0).files.length : 1;
		        var size = input.get(0).files[0].size;
		
		        input.trigger('fileselect', [ numFiles, label, size ]);
		    }
		});

		$('.btn-file :file').on('fileselect', function(event, numFiles, label, size) {
		    $('#FileContenct').attr('name', 'FileContenct'); // allow upload.
		
		    var postfix = label.substr(label.lastIndexOf('.'));
		    if (fileExtentionRange.indexOf(postfix.toLowerCase()) > -1) {
		        if (size > 1024 * 1024 * MAX_SIZE ) {
		            $("#ErroLeyenda").html("�El Archivo es demasiado grande <b><font color='red'>("+number_format(((size/1000)/1000),1)+" M.B.)</font></b> <u>Por favor revise su configuraci�n de sistema oprativo o elija otro archivo con menos peso</u>!");
		            $('#FileContenct').removeAttr('name'); // cancel upload file.
		        } else {
		            $('#_FileContenct').val(label); 
		            $("#ErroLeyenda").html("<b><font color='blue'>Correcto</font></b>");
		            $("#FileContenct").attr("FullClass","Si");
		        }
		    } else {
		        $("#ErroLeyenda").html("�La extenci�n del archivo no es correcta: <b><font color='red'>("+postfix+")</font></b> <u>Por favor revise su configuraci�n de sistema oprativo o elija otro archivo</u>!");
	            $('#FileContenct').removeAttr('name'); // cancel upload file.
		    }
		}); 

/**
 *
 *  @ Cargamos Alta PPE Archvo  
 */ 

   	$("#ImportarArchivoAdd").click(function(){ 
		     $('#frmDigitalizar').ajaxForm(frmDigitalizar); 
        $("#FileContenctEnviar").trigger("click");    
	});


/**
 *
 *  @ Cargamos Alta/Edicion PPE 
 */

 	$("#uiSibmit").click(function (){
        //***********modificacion por RLJ para el curp**********//
		try{
            
        var Nombre    = String($("#Nombre").val());
        var ApPaterno = String($("#ApPaterno").val());
        var ApMaterno = String($("#ApMaterno").val());
        var RFC       = String($("#RFC").val());
        var CURP      = String($("#CURP").val());
        var id        = String($(this).attr("name"));
            // VALIDAMOS DEL LADO DEL CLIENTE
        var TipoPersona = String($("#TipoPersona").val());

	    $('#FormPPF').form({
						    Nombre: {
						      identifier  : 'Nombre',
						      rules: [
						        {
						          type   : 'empty',
						          prompt : 'Campo requerido (Nombre)'
						        }
						      ]
						    },
			   });	  	
            
          if( Nombre != "" ){
	          
	         if( id == "" ){
		         var accion = "INSERT"; 
	         }else{
		         var accion = "UPDATE";
	         }
	          
	         $.ajax( {type: 'POST',
					  url:  '../Model/ModelContenedorCatalogos.php', 
					  data: '__cmd=setAgregaCatalogos&action='+accion+'&Nombre=' + Nombre + '&ApPaterno=' + ApPaterno + '&ApMaterno=' + ApMaterno + '&RFC=' + RFC + '&CURP=' + CURP + '&id=' + id + '&TipoPersona=' + TipoPersona,
					  success: function(RESPUESTA) {

                          RESPUESTA = RESPUESTA.trim();
                          RESPUESTA = String(RESPUESTA);

					  
					  	if( RESPUESTA == "LISTO" ){ 
						  	$('#ModalAgrego').modal('show');
						  	$("#CerrarModal").trigger("click");
					  	}else if( RESPUESTA == "YA EXISTE" ){
						  	$('#ModalConfirmacionYaExiste').modal('show');
						  	$("#CerrarModal").trigger("click");
					  	}else{
							$('#ModalConfirmacion').modal('show');
							$("#CerrarModal").trigger("click");  	
					  	}  	
	
					  
					  },
					  error: function( )   { },
					  complete: function(RESPUESTA) {  }  
					});
	          
          }  // fin if 
            	  	
          	
            	  	
		}catch(e){
            alert(e);
     }
	});	


/**
 *
 *  @ Cargamos Consulta PPE 
 */


	$("#PPE").click(function (){

        $("#TipoPersona").val("PPE");
        $("#TipoPersonaFile").val("PPE");
        $("#TipoListado").val("PPE");
        $("#Paginacion").val("1");
		try{
             $("#BuscaRFC").val("");	  	
			 ListadoPPE('','',1);

             
        }catch(e){
            alert(e);
        }
		});





    /**
 *
 *  @ busqueda Nombre PPE  
 */	
	
	$("#BuscaRFC").on("keyup",function (){
		try{
             
            var BuscaRFC = $("#BuscaRFC").val(); 
             
             ListadoPPE(BuscaRFC,'',1);
             
        }catch(e){
            alert(e);
        }
	});


/** 
 *
 *  @ busqueda Nombre PPE  
 */	


  $("#SubmitPersonaPoliticamente").click(function (){
      //***********modificacion por RLJ para el curp**********//
		try{
            
        var Nombre    = String($("#Nombre").val());
        var ApPaterno = String($("#ApPaterno").val());
        var ApMaterno = String($("#ApMaterno").val());
        var RFC       = String($("#RFC").val());
        var CURP      = String($("#CURP").val());
            // VALIDAMOS DEL LADO DEL CLIENTE
        var TipoPersona = String($("#TipoPersona").val());

	    $('#FormPPF').form({
						    Nombre: {
						      identifier  : 'Nombre',
						      rules: [
						        {
						          type   : 'empty',
						          prompt : 'Campo requerido (Nombre)'
						        }
						      ]
						    },
						    ApMaterno: {
						      identifier  : 'ApMaterno',
						      rules: [
						        {
						          type   : 'empty',
						          prompt : 'Campo requerido (ApMaterno)'
						        }
						      ]
						    },
			   });	  	
            
          if( Nombre != "" ){
	          
	         $.ajax( {type: 'POST',
					  url:  '../Model/ModelContenedorCatalogos.php', 
					  data: '__cmd=setAgregaCatalogos&action=INSERT&Nombre=' + Nombre + '&ApPaterno=' + ApPaterno + '&ApMaterno=' + ApMaterno + '&RFC=' + RFC + '&CURP=' + CURP  + '&TipoPersona=' + TipoPersona ,
					  success: function(RESPUESTA) {

                          RESPUESTA = RESPUESTA.trim();
                          RESPUESTA = String(RESPUESTA);

					  	if( RESPUESTA == "LISTO" ){ 
						  	$('#ModalAgrego').modal('show');
						  	$("#CerrarModal").trigger("click");
					  	}else if( RESPUESTA == "YA EXISTE" ){
						  	$('#ModalConfirmacionYaExiste').modal('show');
						  	$("#CerrarModal").trigger("click");
					  	}else{
							$('#ModalConfirmacion').modal('show');
							$("#CerrarModal").trigger("click");  	
					  	}  	
	
					  
					  },
					  error: function( )   { },
					  complete: function(RESPUESTA) {  }  
					});
	          
          }  // fin if 
            	  	
          	
            	  	
		}catch(e){
            alert(e);
     }
	});	

/**
 *
 * @author MarsVoltoso (CFA)
 * @category JavaScript
 * @created Mon Sep 15, 2014
 * @version 1.0
 * @Puestos
 */	

/**
 *
 *  @ Cargamos Nuevo  
 */ 	
		
	$("#PuestosAdd").click(function (){
		try{
		     $("#Puesto").val("");
		     $("#SubmitAddPuesto").attr({ name: "" });
			 $('#modalAddPuesto').modal('show');	  	
		}catch(e){
            alert(e);
     }
	});	


/**
 *
 *  @ Cargamos Alta/Edicion PUESTOS  
 */
 
 	$("#SubmitAddPuesto").click(function (){
		try{
            
        var Puesto    = String($("#Puesto").val());
        var id        = String($(this).attr("name")); 
        // VALIDAMOS DEL LADO DEL CLIENTE
            
	    $('.ui.form').form({
						    Puesto: {
						      identifier  : 'Puesto',
						      rules: [
						        {
						          type   : 'empty',
						          prompt : 'Campo requerido (Puesto)'
						        }
						      ]
						    },
						    
			});	  	
            
          if( Puesto != ""  ){
	          
	         if( id == "" ){
		         var accion = "INSERT"; 
	         }else{
		         var accion = "UPDATE";
	         } 
	          
	         $.ajax( {type: 'POST',
					  url:  '../Model/ModelContenedorCatalogos.php', 
					  data: '__cmd=setAgregaCatalogosPuestos&Puesto=' + Puesto + '&accion=' + accion + '&id=' + id,
					  success: function(RESPUESTA) {

                          RESPUESTA = RESPUESTA.trim();
                          RESPUESTA = String(RESPUESTA);

					  	if( RESPUESTA == "LISTO" ){ 
						  	$('#ModalAgrego').modal('show');
						  	$("#CerrarModalPuesto").trigger("click");
					  	}else if( RESPUESTA == "YA EXISTE" ){
						  	$('#ModalConfirmacionYaExiste').modal('show');
						  	$("#CerrarModalPuesto").trigger("click");
					  	}else{
							$('#ModalConfirmacion').modal('show');
							$("#CerrarModalPuesto").trigger("click");  	
					  	}  	
	
					  
					  },
					  error: function( )   { },
					  complete: function(RESPUESTA) {  }  
					});
	          
          }  // fin if 
            	  	
          	
            	  	
		}catch(e){
            alert(e);
     }
	});


/**
 *
 *  @ Cargamos Consulta Puestos
 */ 	
		
	$("#PuestosConsult").click(function (){
		try{
             $("#BuscaPuesto").val("");
             $("#TipoListado").val("PUESTOS");
             $("#Paginacion").val("1");
			 
			ListadoPuestos('','',1);
             
        }catch(e){
            alert(e);
        }
		});


/** 
 *
 *  @ busqueda Puestos
 */			
		
	$("#BuscaPuesto").on("keyup",function (){
		try{
             
            var BuscaPuesto = $("#BuscaPuesto").val(); 
             
            ListadoPuestos(BuscaPuesto,'',1);
             
        }catch(e){
            alert(e);
        }
		});	


/**
 *
 * @author MarsVoltoso (CFA)
 * @category JavaScript
 * @created Mon Sep 15, 2014
 * @version 1.0
 * @Cp
 */	


/**
 *
 *  @ Cargamos Nuevo Personas  
 */ 	
		
	$("#CpAdd").click(function (){
		try{
		    $("#CodigoPostal").val("");
		    $('#modalAddCp').modal('show');	  	
		}catch(e){
            alert(e);
     }
	});	
	
/**
 *
 *  @ Cargamos Nuevo Personas  
 */ 	
		
	$("#PaisAdd").click(function (){
		try{
		    //$("#CodigoPostal").val("");
		    $('#modalAddPais').modal('show');	  	
		}catch(e){
            alert(e);
     }
	});	

/**
 *
 *  @ guardar  
 */ 

$(".btnGuardar").click(function (){
	
	var ID_pais = $("#ID_pais").val();
	var motivo  = $("#motivo").val();
	
	if( motivo == "" ){
		alert("�El motivo no puede ir vac�o!");
        return false;
	}
	
	if( ID_pais == "" ){
		alert("�El pa�s no puede ir vac�o!");
        return false;
	}
	
	    $.ajax({ type: 'POST',
		         url:  '../Model/ModelContenedorCatalogos.php', 
				 data: '__cmd=setAgregaCatalogosPais&ID_pais=' + ID_pais + '&motivo=' + motivo,
				 success: function(RESPUESTA) {

                    RESPUESTA = RESPUESTA.trim();
                    RESPUESTA = String(RESPUESTA);

				  	if( RESPUESTA == "LISTO" ){ 
					  	alert("El registro se agrego con �xito");
					  	location.replace("/novacredit/s2credit/lib/AppPld/Views/ViewsContenedorCatalogos.php");
				  	}else{
						alert("Parametros incorrectos"); 	
				  	}  
				  },
				  error: function( )   { },
				  complete: function(RESPUESTA) {  }  
				});
	
});


/**
 *
 *  @ Cargamos Codigos Postales  
 */ 

 	$("#SubmitAddCp").click(function (){
		try{
            
        var CodigoPostal    = String($("#CodigoPostal").val());
        // VALIDAMOS DEL LADO DEL CLIENTE
            
	    $('.ui.form').form({
						    CodigoPostal: {
						      identifier  : 'CodigoPostal',
						      rules: [
                                  {
                                      type   : 'length[5]',
                                      prompt : 'El codigo postar debe ser de 5 digitos'
                                  },
						        {
						          type   : 'empty',
						          prompt : 'Campo requerido (C�digo Postal)'
						        }

						      ]
						    },
			});	  	
            
          if( CodigoPostal != "" && CodigoPostal.length == 5 ){
	          
	         $.ajax( {type: 'POST',
					  url:  '../Model/ModelContenedorCatalogos.php', 
					  data: '__cmd=setAgregaCatalogosCp&CodigoPostal=' + CodigoPostal,
					  success: function(RESPUESTA) {

                          RESPUESTA = RESPUESTA.trim();
                          RESPUESTA = String(RESPUESTA);

					  	if( RESPUESTA == "LISTO" ){ 
						  	$('#ModalAgrego').modal('show');
						  	$("#CerrarModalCp").trigger("click");
					  	}else if( RESPUESTA == "YA EXISTE" ){
						  	$('#ModalConfirmacionYaExiste').modal('show');
						  	$("#CerrarModalCp").trigger("click");
					  	}else if(  RESPUESTA == "NO EXISTE" ){
						  	$('#ModalConfirmacionNoExiste').modal('show');
						  	$("#CerrarModalCp").trigger("click");
					  	}else{
							$('#ModalConfirmacion').modal('show');
							$("#CerrarModalCp").trigger("click");  	
					  	}  	
	
					  
					  },
					  error: function( )   { },
					  complete: function(RESPUESTA) {  }  
					});
	          
          }  // fin if 
            	  	
          	
            	  	
		}catch(e){
            alert(e);
     }
	});

/**
 *
 *  @ Cargamos Consulta Codigos Postales 
 */ 	
		
	$("#CpConsult").click(function (){
        $("#TipoListado").val("CP");
        $("#Paginacion").val("1");

		try{
             
             $("#Cp").val("");	  	
			 
			ListadoCP('','',1);
             
        }catch(e){
            alert(e);
        }
		});
		
/**
 *
 *  @ Cargamos Consulta Codigos Postales 
 */ 	
		
    $("#PaisConsult").click(function (){
        $("#TipoListado").val("Paises");
        $("#Paginacion").val("1");

		try{
             
             //$("#Cp").val("");	  	
			 
			ListadoPaises('','',1);
             
        }catch(e){
            alert(e);
        }
		});



/** 
 *
 *   @ Cargamos busqueda Codigos Postales  
 */			
		
	$("#Cp").on("keyup",function (){
		try{
             
            var Cp = $("#Cp").val();

            ListadoCP(Cp,'',1);
        }catch(e){
            alert(e);
        }
		});	


/**
 *
 * @author MarsVoltoso (CFA)
 * @category JavaScript
 * @created Mon Sep 15, 2014
 * @version 1.0
 * @Estados Riesgosos
 */	


/**
 *
 *  @ Cargamos Nuevo Estados  
 */ 

$("#EstadoRiesgoAdd").click(function (){
	try{
             
         $.ajax( {type: 'POST',
				  url:  '../Model/ModelContenedorCatalogos.php', 
				  data: '__cmd=setConsultaYaConsultadoCatalogos',
				  success: function(RESPUESTA) {

                      RESPUESTA = RESPUESTA.trim();
                      RESPUESTA = String(RESPUESTA);
				  	var DATOS = jQuery.parseJSON(RESPUESTA);
				  	
				  		for(var i=1;i<=32;i++){
					  		if( DATOS["EstadoRiesgo_"+i+""] !== undefined ){
					  			$("#EstadoRiesgo_"+DATOS["EstadoRiesgo_"+i+""]+"").remove();
						  	}
					  	}
				  	
				  	$('#modalAddEstadoRiesgo').modal('show');
				  	$('.ui.selection.dropdown').dropdown('restore default text');
				  
				  },
				  error: function( )   { },
				  complete: function(RESPUESTA) { }  
				});
         
    }catch(e){
        alert(e);
    }
 });

	
/**
 *
 *  @ Cargamos Insertamos Nuevo Estado  
 */ 	
	
$("#SubmitAddEstado").click(function (){
	
	try{
            
        var EstadosRiesgos  = String($("#EstadosRiesgos").val());
        // VALIDAMOS DEL LADO DEL CLIENTE
            
	    $('.ui.form').form({
						    EstadosRiesgos: {
						      identifier  : 'EstadosRiesgos',
						      rules: [
						        {
						          type   : 'empty',
						          prompt : 'Campo requerido (EstadosRiesgos)'
						        }
						      ]
						    },
						    
			});	  	
            
            
          if( EstadosRiesgos != ""  ){
	          
	         $.ajax( {type: 'POST',
					  url:  '../Model/ModelContenedorCatalogos.php', 
					  data: '__cmd=setAgregaCatalogosEstadoRiesgo&EstadosRiesgos=' + EstadosRiesgos,
					  success: function(RESPUESTA) {

                          RESPUESTA = RESPUESTA.trim();
                          RESPUESTA = String(RESPUESTA);

					  	if( RESPUESTA == "LISTO" ){ 
						  	$('#ModalAgrego').modal('show');
						  	$("#CerrarModalEstadoRiesgoso").trigger("click");
					  	}else if( RESPUESTA == "YA EXISTE" ){
						  	$('#ModalConfirmacionYaExiste').modal('show');
						  	$("#CerrarModalEstadoRiesgoso").trigger("click");
					  	}else{
							$('#ModalConfirmacion').modal('show');
							$("#CerrarModalEstadoRiesgoso").trigger("click");  	
					  	}  	
	
					  
					  },
					  error: function( )   { },
					  complete: function(RESPUESTA) {  }  
					});
	          
          }  // fin if 
            	  	
          	
            	  	
		}catch(e){
            alert(e);
     }
	});	

	
/**
 *
 *  @ Cargamos Consulatamos Nuevo Estado  
 */ 

$("#EstadoRiesgoConsult").click(function (){
		try{

            $("#TipoListado").val("ESTADOS");
            $("#EstadoRiesgo").val("");
            $("#Paginacion").val("1");
			 
			 ListadoEstados('','',1);
             
        }catch(e){
            alert(e);
        }
	});


/** 
 *
 *  @ busqueda Estados Riesgosos
 */			
		
	$("#EstadoRiesgo").on("keyup",function (){
		try{
             
            var EstadoRiesgo = $("#EstadoRiesgo").val();
             ListadoEstados(EstadoRiesgo,'',1);
             
        }catch(e){
            alert(e);
        }
	});	
	
/**
 *
 * @author MarsVoltoso (CFA)
 * @category JavaScript
 * @created Mon Sep 15, 2014
 * @version 1.0
 * @Ciudad Riesgosa
 */	
 
/**
 *
 *  @ Cargamos Nuevo Ciudades  
 */  
 
$("#CiudadRiesgoAdd").click(function (){
	         
  	$('#modalAddCiudadRiesgo').modal('show');
  	$('.ui.selection.dropdown').dropdown('restore default text');
  	
});
 
$("#Dos_EstadosRiesgos").change( function (){
	try{
             
        var EstadoRiesgo = $(this).val(); 
         
         $.ajax( {type: 'POST',
				  url:  '../Model/ModelContenedorCatalogos.php', 
				  data: '__cmd=setConsultaCiudadRiesgoCatalogos&EstadoRiesgo=' + EstadoRiesgo,
				  success: function(RESPUESTA) {
				  	 $("#MenuDiv").html(String(RESPUESTA));
				  	 $('.ui.selection.dropdown').dropdown();
				  },
				  error: function( )   { },
				  complete: function(RESPUESTA) { }  
				});
             
     }catch(e){
            alert(e);
     }
}); 

/**
 *
 *  @ Cargamos Insertamos Nuevo Estado  
 */ 	
	
$("#SubmitAddCiudad").click(function (){
	
	try{
            
        var CiudadRiesgos      = String($("#CiudadRiesgos").val());
        var Dos_EstadosRiesgos = String($("#Dos_EstadosRiesgos").val());
        // VALIDAMOS DEL LADO DEL CLIENTE
            
	    $('.ui.form').form({
						    CiudadRiesgos: {
						      identifier  : 'CiudadRiesgos',
						      rules: [
						        {
						          type   : 'empty',
						          prompt : 'Campo requerido (CiudadRiesgos)'
						        }
						      ]
						    },
						    
			});	  	
            
            
          if( CiudadRiesgos != "" && Dos_EstadosRiesgos != "" ){
	          
	         $.ajax( {type: 'POST',
					  url:  '../Model/ModelContenedorCatalogos.php', 
					  data: '__cmd=setAgregaCatalogosCiudadRiesgo&CiudadRiesgos=' + CiudadRiesgos + '&Dos_EstadosRiesgos=' + Dos_EstadosRiesgos,
					  success: function(RESPUESTA) {

                          RESPUESTA = RESPUESTA.trim();
                          RESPUESTA = String(RESPUESTA);

					  	if( RESPUESTA == "LISTO" ){ 
						  	$('#ModalAgrego').modal('show');
						  	$("#CerrarModalEstadoRiesgoso").trigger("click");
					  	}else if( RESPUESTA == "YA EXISTE" ){
						  	$('#ModalConfirmacionYaExiste').modal('show');
						  	$("#CerrarModalEstadoRiesgoso").trigger("click");
					  	}else{
							$('#ModalConfirmacion').modal('show');
							$("#CerrarModalEstadoRiesgoso").trigger("click");  	
					  	}  	
	
					  
					  },
					  error: function( )   { },
					  complete: function(RESPUESTA) {  }  
					});
	          
          }  // fin if 
            	  	
          	
            	  	
		}catch(e){
            alert(e);
     }
	});	 

/**
 *
 *  @ Cargamos Consulatamos Nueva Ciudad 
 */ 

$("#CiudadRiesgoConsult").click(function (){
		try{
             
            $("#TipoListado").val("CIUDADES");
            $("#CiudadRiesgo").val("");
            $("#Paginacion").val("1");
			 
			ListadoCiudades('','',1);
             
        }catch(e){
            alert(e);
        }
	}); 
	
	
/** 
 *
 *  @ busqueda Ciudad Riesgosos
 */			
		
	$("#CiudadRiesgo").on("keyup",function (){
		try{
             
            var CiudadRiesgo = $("#CiudadRiesgo").val();
            ListadoCiudades(CiudadRiesgo,'',1);

        }catch(e){
            alert(e);
        }
	});		
 
/**
 *
 * @author MarsVoltoso (CFA)
 * @category JavaScript
 * @created Mon Sep 15, 2014
 * @version 1.0
 * @Ciudades
 */	 
 
$("#GirosRiesgoAdd").click(function (){


    try{
        $("#GiroNegocio").val("");
        $("#TipoRiesgos").val("");
        $("#EstatusRiesgo").val("");
        $("#SubmitAddGiros").attr({ name: "" });

        $('#DropRiesgo').dropdown('restore default text');
        $('#DropEstatus').dropdown('restore default text');

        $('#modalAddGirosRiesgo').modal('show');
        //$('.ui.selection.dropdown').dropdown('restore','default','value');
       // $('.ui.selection.dropdown').dropdown();


    }catch(e)
    {
        alert(e);
    }

}); 

/**
 *
 *  @ Cargamos Insertamos Nuevo Giro  
 */ 	
	
$("#SubmitAddGiros").click(function (){
	
	try{
            
        var GiroNegocio = String($("#GiroNegocio").val());
        var TipoRiesgos = String($("#TipoRiesgos").val()); //quitamos esto porque ya no existe y se va a mandar alto riesgo
        var EstatusRiesgo = String($("#EstatusRiesgo").val());
        var id            = String($(this).attr("name"));

		//var TipoRiesgos = String("ALTO RIESGO");
        // VALIDAMOS DEL LADO DEL CLIENTE
	    $('.ui.form').form({
						    GiroNegocio: {
						      identifier  : 'GiroNegocio',
						      rules: [
						        {
						          type   : 'empty',
						          prompt : 'Campo requerido (GiroNegocio)'
						        }
						      ]
						    },
						    
			});	  	

          if( GiroNegocio != "" && TipoRiesgos != "" && EstatusRiesgo != "" ){
	          
	         $.ajax( {type: 'POST',
					  url:  '../Model/ModelContenedorCatalogos.php', 
					  data: '__cmd=setAgregaCatalogosGiroRiesgo&GiroNegocio=' + GiroNegocio + '&TipoRiesgos=' + TipoRiesgos + '&EstatusRiesgo=' + EstatusRiesgo + '&id=' + id,
					  success: function(RESPUESTA) {

                          RESPUESTA = RESPUESTA.trim();
                          RESPUESTA = String(RESPUESTA);
					  
					  	if( RESPUESTA == "LISTO" ){ 
						  	$('#ModalAgrego').modal('show');
						  	$("#CerrarModalGiros").trigger("click");
					  	}else if( RESPUESTA == "EXISTE" ){
						  	$('#ModalConfirmacionYaExiste').modal('show');
						  	$("#CerrarModalGiros").trigger("click");
					  	}else{
							$('#ModalConfirmacion').modal('show');
							$("#CerrarModalGiros").trigger("click");  	
					  	}  	
	
					  
					  },
					  error: function( )   { },
					  complete: function(RESPUESTA) {  }  
					});
	          
          }  // fin if 
            	  	
          	
            	  	
		}catch(e){
            alert(e);
     }
	});	
 
/**
 *
 *  @ Cargamos Consulatamos Nueva Ciudad 
 */ 

$("#GiroRiesgoConsult").click(function (){
		try{
             
             //$("#Cp").val("");	  	

            $("GiroRiesgo").val();
            $("#TipoListado").val("ACTIVIDADES");
            $("#Paginacion").val("1");
            ListadoActividades('','',1);
             
        }catch(e){
            alert(e);
        }
	}); 
 
/** 
*
*  @ busqueda giro 
*/			
		
	$("#GiroRiesgo").on("keyup",function (){
		try{
             
            var GiroRiesgo = $("#GiroRiesgo").val();
            ListadoActividades(GiroRiesgo,'',1);
             
        }catch(e){
            alert(e);
        }
	});	
	
 $("#VerEjemplo").click(function (){
		try{
            SleepShowModal(600, ShowModalParams , "MuestraEjemplo" );
        }catch(e){
            alert(e);
        }
	}); 
 
 
 
//*******************RLJ***********************************//

/**
 *
 *  @ actualiza terrorista  
 */	

	$("#Terrorista_act").click(function (){
		try{
			
			$("#Actualizando").modal("show");
			
			$("#Actualizando").on("click",function (){
//				SleepShowModal(500, ShowModalParams , "ModalActCat" );
			});

             $.ajax( {type: 'POST',
					  url:  '../Model/ModelActualizarCatalogos.php', 
					  data: '__cmd=setActualizaTerrorista',
					  success: function(RESPUESTA) {

                          RESPUESTA = RESPUESTA.trim();
                          RESPUESTA = String(RESPUESTA);

						  if(RESPUESTA == "Actualizado")
						  {
						  	$("#Actualizando").trigger("click");
							SleepShowModal(600, ShowModalParams , "ModalActCat" );
						  }else
						  {
							  $("#Actualizando").trigger("click");
							  SleepShowModal(600, ShowModalParams , "ModalActCatErr" );
						  }


						 
					  },
					  error: function( )   { },
					  complete: function(RESPUESTA) {
						  
						   }  
					});

        }catch(e){
            alert(e);
        }
		});
	
/**
 *
 *  @ muestra terroristas
 */	
		
		
		$("#Terrorista").click(function (){
			VistaTerroristas('','','');
		});
		
/**
 *
 *  @ busca terroristas
 */	
		
		
		$("#BuscarTerrorista").keypress(function (){
			var Filtro  = $("#BuscarTerrorista").val();
			VistaTerroristas('','',Filtro);
		});
	
	
/**
 *
 *  @ actualiza udis
 */	



	$("#UDIS_act").click(function (){
		try{
            $("#Actualizando").modal('show');
			
			$("#Actualizando").on("click",function (){
			});			


             $.ajax( {type: 'POST',
					  url:  '../Model/ModelActualizarCatalogos.php', 
					  data: '__cmd=setActualizaUnidades&Tipo=UDIS',
					  success: function(RESPUESTA) {

                          RESPUESTA = RESPUESTA.trim();
                          RESPUESTA = String(RESPUESTA);

					  	 if(RESPUESTA == "Actualizado")
						  {
						  	$("#Actualizando").trigger("click");
							SleepShowModal(600, ShowModalParams , "ModalActCat" );
						  }else
						  {
							  $("#Actualizando").trigger("click");
							  SleepShowModal(600, ShowModalParams , "ModalActCatErr" );
						  } 	
					  },
					  error: function( )   { },
					  complete: function(RESPUESTA) { }  
					});
              
        }catch(e){
            alert(e);
        }
	});

	
	$("#USD_act").click(function (){
		try{
			
			$("#Actualizando").modal('show');
			
			$("#Actualizando").on("click",function (){
			});	
             $.ajax( {type: 'POST',
					  url:  '../Model/ModelActualizarCatalogos.php', 
					  data: '__cmd=setActualizaUnidades&Tipo=USD',
					  success: function(RESPUESTA) {

                          RESPUESTA = RESPUESTA.trim();
                          RESPUESTA = String(RESPUESTA);


						 if(RESPUESTA == "Actualizado")
						  {
						  	$("#Actualizando").trigger("click");
							SleepShowModal(600, ShowModalParams , "ModalActCat" );
						  }else
						  {
							  $("#Actualizando").trigger("click");
							  SleepShowModal(600, ShowModalParams , "ModalActCatErr" );
						  } 	
					  },
					  error: function( )   { },
					  complete: function(RESPUESTA) { }  
					});
              
        }catch(e){
            alert(e);
        }
		});	
		
		

	$("#UDIS").click(function (){
		$("#Unidades").val("UDIS");
		VistaUnidades("UDIS",'','','','')
	});
	

	$("#USD").click(function (){
		$("#Unidades").val("USD");
		VistaUnidades("USD",'','','','')
	});
	

	$("#Filtar").click(function(e) {
		//var final = new Date(myDatePicker_final.getSelectedDate());
		//var inicio = new Date(myDatePicker_inicial.getSelectedDate());
		
			var Tipo = $("#Unidades").val();
			var Fecha_final = myDatePicker_final.getSelectedDate();
			var Fecha_inicial = myDatePicker_inicial.getSelectedDate();
					
					
		
		if(Fecha_final == null || Fecha_inicial == null )
		{
			alert("Seleccione las fechas");
		}
		else
		{
			var date = new Date(Fecha_inicial);
			var dia1 = date.getDate();
			var mes1 = (date.getMonth() + 1);
			var ano1 = date.getFullYear()
			
			var date2 = new Date(Fecha_final);
			var dia2 = date2.getDate();
			var mes2 = (date2.getMonth() + 1);
			var ano2 = date2.getFullYear()

			VistaUnidades(Tipo,'','',ano1+'-'+mes1+'-'+dia1,ano2+'-'+mes2+'-'+dia2);
		}
	
	});
				


//********************************************************// 
 
 
 
	
}); // fin ready


/** 
 *
 *  @ Eliminar   
 */	


function EliminarCp(id){
	
	if( id != "" ){
		
		     $.ajax( {type: 'POST',
					  url:  '../Model/ModelContenedorCatalogos.php', 
					  data: '__cmd=setEliminarCpCatalogos&id=' + id,
					  success: function(RESPUESTA) {

                          RESPUESTA = RESPUESTA.trim();
                          RESPUESTA = String(RESPUESTA);

					  		if( RESPUESTA == "LISTO" ){
						  	   $("#ModalListo").modal("show");
						  	   $("#CerrarModalVistaCp").trigger("click");
						  	}	 
					  },
					  error: function( )   { },
					  complete: function(RESPUESTA) { }  
					});

		
	} // fin if
}

/** 
 *
 *  @ Editar   
 */	

function Editar(id){
	
	if( id != "" ){
		$("#CerrarModalVista").trigger("click");
			
			 $.ajax( {type: 'POST',
					  url:  '../Model/ModelContenedorCatalogos.php', 
					  data: '__cmd=setEditarDatosCatalogos&TipoPersona=PPE&id=' + id,
					  success: function(RESPUESTA) {
					         $('#modalEdit').modal('show');
					         $("#ContendordivEdit").html(String(RESPUESTA));
					  },
					  error: function( )   { },
					  complete: function(RESPUESTA) { }  
					});
	} // fin if
}


/** 
 *
 *  @ Eliminar PPE  
 */	


function EliminarPersonaPoliticamente(id){
	
	if( id != "" ){
		
	     $.ajax( {type: 'POST',
				  url:  '../Model/ModelContenedorCatalogos.php', 
				  data: '__cmd=setEliminarCatalogos&id=' + id,
				  success: function(RESPUESTA) {

                      RESPUESTA = RESPUESTA.trim();
                      RESPUESTA = String(RESPUESTA);

				  		if( RESPUESTA == "LISTO" ){
					  	   $("#ModalListo").modal("show");
					  	   $("#CerrarModalVista").trigger("click");
					  	}	 
				  },
				  error: function( )   { },
				  complete: function(RESPUESTA) { }  
				});

		
	} // fin if
}

/** 
 *
 *  @ Editar PPE  
 */	


function EditarPersonaPoliticamente(id){

	if( id != "" ){
		
		 $.ajax( {type: 'POST',
				  url:  '../Model/ModelContenedorCatalogos.php', 
				  data: '__cmd=setEditarCatalogos&id=' + id,
				  success: function(RESPUESTA) {
				  		
				  		var DATOS = jQuery.parseJSON(RESPUESTA);
				  		$("#Nombre").val(DATOS["Nombre"]);
				  		$("#ApPaterno").val(DATOS["ApPaterno"]);
				  		$("#ApMaterno").val(DATOS["ApMaterno"]);
				  		$("#RFC").val(DATOS["RFC"]);
                        $("#CURP").val(DATOS["CURP"]);
				  		
				  		$("#CerrarModalVista").trigger("click");
				  		SleepShowModal(600, ShowModalParams , "modalAdd" );	
				  		
				  		$("#uiSibmit").attr({ name: id });
				  			 
				  },
				  error: function( )   { },
				  complete: function(RESPUESTA) { }  
				});
		
	}
	
} // fin function

/** 
 *
 *  @ Eliminar Puesto
 */	


function EliminarPuesto(id){
	
	if( id != "" ){
		
		     $.ajax( {type: 'POST',
					  url:  '../Model/ModelContenedorCatalogos.php', 
					  data: '__cmd=setEliminarPuestoCatalogos&id=' + id,
					  success: function(RESPUESTA) {

                          RESPUESTA = RESPUESTA.trim();
                          RESPUESTA = String(RESPUESTA);

					  		if( RESPUESTA == "LISTO" ){
						  	   $("#ModalListo").modal("show");
						  	   $("#CerrarModalVistaPuesto").trigger("click");
						  	}	 
					  },
					  error: function( )   { },
					  complete: function(RESPUESTA) { }  
					});

		
	} // fin if
} // fin function

/** 
 *
 *  @ Editar Puesto  
 */	


function EditarPuestoPoliticamente(id){
	
	if( id != "" ){
		
		 $.ajax( {type: 'POST',
				  url:  '../Model/ModelContenedorCatalogos.php', 
				  data: '__cmd=setEditarCatalogosPuestos&id=' + id,
				  success: function(RESPUESTA) {
				  		
				  		var DATOS = jQuery.parseJSON(RESPUESTA);
				  		$("#Puesto").val(DATOS["Puesto"]);
				  		
				  		$("#CerrarModalVistaPuesto").trigger("click");
				  		SleepShowModal(600, ShowModalParams , "modalAddPuesto" );	
				  		
				  		$("#SubmitAddPuesto").attr({ name: id });
				  			 
				  },
				  error: function( )   { },
				  complete: function(RESPUESTA) { }  
				});
		
	}
	
} // fin function

/** 
 *
 *  @ Eliminar Estado Riesgoso
 */	

function EliminarEstadoRiesgo(id){
	
	if( id != "" ){
		
		 $.ajax( {type: 'POST',
				  url:  '../Model/ModelContenedorCatalogos.php', 
				  data: '__cmd=setEliminaCatalogosEstadoRiesgoso&id=' + id,
				  success: function(RESPUESTA) {

                      RESPUESTA = RESPUESTA.trim();
                      RESPUESTA = String(RESPUESTA);

				  		if( RESPUESTA == "LISTO" ){
						  	   $("#ModalListo").modal("show");
						  	   $("#CerrarModalVistaEstadoRiesgo").trigger("click");
						}					  			 
				  },
				  error: function( )   { },
				  complete: function(RESPUESTA) { }  
				});
		
	}
	
} // fin if

/** 
 *
 *  @ Eliminar Ciudad Riesgoso
 */

function EliminarCiudadRiesgo(id){
	
	if( id != "" ){
		
		 $.ajax( {type: 'POST',
				  url:  '../Model/ModelContenedorCatalogos.php', 
				  data: '__cmd=setEliminaCatalogosCiudadRiesgoso&id=' + id,
				  success: function(RESPUESTA) {

                      RESPUESTA = RESPUESTA.trim();
                      RESPUESTA = String(RESPUESTA);

				  		if( RESPUESTA == "LISTO" ){
						  	   $("#ModalListo").modal("show");
						  	   $("#CerrarModalVistaCiudadRiesgo").trigger("click");
						}					  			 
				  },
				  error: function( )   { },
				  complete: function(RESPUESTA) { }  
				});
		
	}
	
} // fin if

/** 
 *
 *  @ Eliminar Ciudad Riesgoso
 */

function EliminarGiroRiesgo(id){
	
	if( id != "" ){
		
		 $.ajax( {type: 'POST',
				  url:  '../Model/ModelContenedorCatalogos.php', 
				  data: '__cmd=setEliminaCatalogosRiesgoRiesgoso&id=' + id,
				  success: function(RESPUESTA) {

                      RESPUESTA = RESPUESTA.trim();
                      RESPUESTA = String(RESPUESTA);

				  		if( RESPUESTA == "LISTO" ){
						  	   $("#ModalListo").modal("show");
						  	   $("#CerrarModalVistaGiroRiesgo").trigger("click");
						}					  			 
				  },
				  error: function( )   { },
				  complete: function(RESPUESTA) { }  
				});
		
	}
	
} // fin if



/** ************************RLJ*******************
 *
 *  @ vista terroistas   
 */	


function VistaTerroristas(Pagina,Evento,Filtro)
{
	
		try{
             //$('.loader').modal('show');
             $.ajax( {type: 'POST',
					  url:  '../Model/ModelActualizarCatalogos.php', 
					  data: '__cmd=GetListadoTerroristas&Pagina='+Pagina+'&Evento='+Evento+'&Filtro='+Filtro,
					  success: function(RESPUESTA) {
					  	 $("#ContendordivListadoTerrorista").html(String(RESPUESTA))
						 $('.modalListadoTerrorista').modal('show');	  	
					  },
					  error: function( )   { },
					  complete: function(RESPUESTA) { }  
					});
              //$('loader').modal('hide');
        }catch(e){
            alert(e);
        }
}


function ActualizaListado(Evento)
{
	 var paginas = $("#paginacion").val();
	 var Filtro  = $("#BuscarTerrorista").val();
	 VistaTerroristas(paginas,Evento,Filtro);
}

function CambiaPaginaListado(Obj)
{
	 var paginas = $(Obj).val();
	 var Filtro  = $("#BuscarTerrorista").val();
	 VistaTerroristas(paginas,'',Filtro);
}




function VistaUnidades(Tipo,Pagina,Evento,Fecha_inicial,Fecha_final)
{
	
		try{

				 $(".beatpicker-clear").remove();

				 $.ajax( {type: 'POST',
						  url:  '../Model/ModelActualizarCatalogos.php', 
						  data: '__cmd=setVistaUnidades&Tipo='+Tipo+'&Pagina='+Pagina+'&Evento='+Evento+'&Fecha_inicial='+Fecha_inicial+'&Fecha_final='+Fecha_final,
						  success: function(RESPUESTA) {
							 $("#ContendordivListadoUnidades").html(String(RESPUESTA))
							 $('.modalListadoUnidades').modal('show');	  	
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

     var Fecha_final = myDatePicker_final.getSelectedDate();
     var Fecha_inicial = myDatePicker_inicial.getSelectedDate();

    var Fecha_final_1 = "";
    var Fecha_inicial_1 = "";


    if( (Fecha_final != null || Fecha_inicial != null)   )
    {
       
        var date = new Date(Fecha_inicial);
        var dia1 = date.getDate();
        var mes1 = (date.getMonth() + 1);
        var ano1 = date.getFullYear()

        var date2 = new Date(Fecha_final);
        var dia2 = date2.getDate();
        var mes2 = (date2.getMonth() + 1);
        var ano2 = date2.getFullYear()


        Fecha_inicial_1 =  ano1+'-'+mes1+'-'+dia1;
        Fecha_final_1  = ano2+'-'+mes2+'-'+dia2;
    }

        var paginas = $("#paginacionUni").val();
	    var Tipo = $("#Unidades").val();


	 VistaUnidades(Tipo,paginas,Evento,Fecha_inicial_1,Fecha_final_1 );
}

function CambiaPaginaListadoUnidades(Obj)
{
	 var paginas = $(Obj).val();
	 var Tipo = $("#Unidades").val();
	 VistaUnidades(Tipo,paginas,'','','' );
}



/**
 *
 *  @ Editar LP
 */

function EditarListaPropia(id){

    if( id != "" ){

        $.ajax( {type: 'POST',
            url:  '../Model/ModelContenedorCatalogos.php',
            data: '__cmd=setEditarCatalogosLP&id=' + id,
            success: function(RESPUESTA) {

                var DATOS = jQuery.parseJSON(RESPUESTA);
                $("#Nombre").val(DATOS["Nombre"]);
                $("#ApPaterno").val(DATOS["ApPaterno"]);
                $("#ApMaterno").val(DATOS["ApMaterno"]);
                $("#RFC").val(DATOS["RFC"]);
                $("#CURP").val(DATOS["CURP"]);

                $("#CerrarModalVistaListaPropia").trigger("click");
                SleepShowModal(600, ShowModalParams , "modalAdd" );

                $("#uiSibmit").attr({ name: id });

            },
            error: function( )   { },
            complete: function(RESPUESTA) { }
        });

    }

} // fin function

/**
 *
 *  @ Editar LP
 */

function EditarSAT(id){

    if( id != "" ){

        $.ajax( {type: 'POST',
            url:  '../Model/ModelContenedorCatalogos.php',
            data: '__cmd=setEditarCatalogosSAT&id=' + id,
            success: function(RESPUESTA) {

                var DATOS = jQuery.parseJSON(RESPUESTA);
                $("#Nombre").val(DATOS["Nombre"]);
                $("#ApPaterno").val(DATOS["ApPaterno"]);
                $("#ApMaterno").val(DATOS["ApMaterno"]);
                $("#RFC").val(DATOS["RFC"]);
                $("#CURP").val(DATOS["CURP"]);

                $("#CerrarModalVistaListaSAT").trigger("click");
                SleepShowModal(600, ShowModalParams , "modalAdd" );

                $("#uiSibmit").attr({ name: id });

            },
            error: function( )   { },
            complete: function(RESPUESTA) { }
        });

    }

} // fin function

/**
 *
 *  @ Eliminar LP
 */


function EliminarListaPropia(id){

    if( id != "" ){

        $.ajax( {type: 'POST',
            url:  '../Model/ModelContenedorCatalogos.php',
            data: '__cmd=setEliminarCatalogosLP&id=' + id,
            success: function(RESPUESTA) {

                RESPUESTA = RESPUESTA.trim();
                RESPUESTA = String(RESPUESTA);

                if( RESPUESTA == "LISTO" ){
                    $("#ModalListo").modal("show");
                    $("#CerrarModalVistaListaPropia").trigger("click");
                }
            },
            error: function( )   { },
            complete: function(RESPUESTA) { }
        });


    } // fin if
}

/**
 *
 *  @ Eliminar LP
 */


function EliminarSAT(id){

    if( id != "" ){

        $.ajax( {type: 'POST',
            url:  '../Model/ModelContenedorCatalogos.php',
            data: '__cmd=setEliminarCatalogosSAT&id=' + id,
            success: function(RESPUESTA) {

                RESPUESTA = RESPUESTA.trim();
                RESPUESTA = String(RESPUESTA);

                if( RESPUESTA == "LISTO" ){
                    $("#ModalListo").modal("show");
                    $("#CerrarModalVistaListaSAT").trigger("click");
                }
            },
            error: function( )   { },
            complete: function(RESPUESTA) { }
        });


    } // fin if
}


/**
 *
 *  @ Editar LP
 */


function EditarListaCondusef(id){

    if( id != "" ){

        $.ajax( {type: 'POST',
            url:  '../Model/ModelContenedorCatalogos.php',
            data: '__cmd=setEditarCatalogosLC&id=' + id,
            success: function(RESPUESTA) {

                var DATOS = jQuery.parseJSON(RESPUESTA);
                $("#Nombre").val(DATOS["Nombre"]);
                $("#ApPaterno").val(DATOS["ApPaterno"]);
                $("#ApMaterno").val(DATOS["ApMaterno"]);
                $("#RFC").val(DATOS["RFC"]);
                $("#CURP").val(DATOS["CURP"]);

                $("#CerrarModalVistaListaCondusef").trigger("click");
                SleepShowModal(600, ShowModalParams , "modalAdd" );

                $("#uiSibmit").attr({ name: id });

            },
            error: function( )   { },
            complete: function(RESPUESTA) { }
        });

    }

} // fin function

/**
 *
 *  @ Eliminar LP
 */


function EliminarListaCondusef(id){

    if( id != "" ){

        $.ajax( {type: 'POST',
            url:  '../Model/ModelContenedorCatalogos.php',
            data: '__cmd=setEliminarCatalogosLC&id=' + id,
            success: function(RESPUESTA) {

                RESPUESTA = RESPUESTA.trim();
                RESPUESTA = String(RESPUESTA);

                if( RESPUESTA == "LISTO" ){
                    $("#ModalListo").modal("show");
                    $("#CerrarModalVistaListaCondusef").trigger("click");
                }
            },
            error: function( )   { },
            complete: function(RESPUESTA) { }
        });


    } // fin if
}


function EditarGiroRiesgo(id){

    if( id != "" ){

        $.ajax( {type: 'POST',
            url:  '../Model/ModelContenedorCatalogos.php',
            data: '__cmd=setEditaCatalogosRiesgoRiesgoso&id=' + id,
            success: function(RESPUESTA) {

                var DATOS = jQuery.parseJSON(RESPUESTA);
                $("#GiroNegocio").val(DATOS["Giro"]);
                $("#TipoRiesgos").val(DATOS["Tipo"]);
                $("#EstatusRiesgo").val(DATOS["Estatus"]);

                $("#CerrarModalVistaGiroRiesgo").trigger("click");
                SleepShowModal(600, ShowModalParams , "modalAddGirosRiesgo" );

                $('#DropRiesgo').dropdown();
                $('#DropEstatus').dropdown();

                $("#SubmitAddGiros").attr({ name: id });

            },
            error: function( )   { },
            complete: function(RESPUESTA) { }
        });

    }

} // fin function



//*****************LISTADOS**************//

function ListadoCondusef(Filtro,Evento,Pagina)
{

    try{
        $.ajax( {type: 'POST',
            url:  '../Model/ModelContenedorCatalogos.php',
            data: '__cmd=setConsultaCatalogosLC&BuscaRFC=' + Filtro + '&Evento=' + Evento + '&Pagina='+ Pagina,
            success: function(RESPUESTA) {
                $("#ContendordivListaCondusef").html(String(RESPUESTA))
                $('.modalConsultaListaCondusef').modal('show');
            },
            error: function( )   { },
            complete: function(RESPUESTA) { }
        });

    }catch(e){
        alert(e);
    }
}
function ListadoPropio(Filtro,Evento,Pagina)
{
    try{
	    $.ajax( {type: 'POST',
            url:  '../Model/ModelContenedorCatalogos.php',
            data: '__cmd=setConsultaCatalogosLP&BuscaRFC=' + Filtro + '&Evento=' + Evento + '&Pagina='+ Pagina,
            success: function(RESPUESTA) {
                $("#ContendordivListaPropia").html(String(RESPUESTA))
                $('.modalConsultaListaPropia').modal('show');
            },
            error: function( )   { },
            complete: function(RESPUESTA) { }
        });

    }catch(e){
        alert(e);
    }
}

function ListadoSAT( Filtro,Evento,Pagina ){
	try{
	    $.ajax( {type: 'POST',
            url:  '../Model/ModelContenedorCatalogos.php',
            data: '__cmd=setConsultaCatalogosSAT&BuscaRFC=' + Filtro + '&Evento=' + Evento + '&Pagina='+ Pagina,
            success: function(RESPUESTA) {
                $("#ContendordivListaSAT").html(String(RESPUESTA))
                $('.modalConsultaListaSAT').modal('show');
            },
            error: function( )   { },
            complete: function(RESPUESTA) { }
        });

    }catch(e){
        alert(e);
    }
} //@end

function ListadoPPE(Filtro,Evento,Pagina)
{
    try{
        $.ajax( {type: 'POST',
            url:  '../Model/ModelContenedorCatalogos.php',
            data: '__cmd=setConsultaCatalogos&BuscaRFC=' + Filtro + '&Evento=' + Evento + '&Pagina='+ Pagina,
            success: function(RESPUESTA) {
                $("#Contendordiv").html(String(RESPUESTA))
                $('.modalConsulta').modal('show');
            },
            error: function( )   { },
            complete: function(RESPUESTA) { }
        });
    }catch(e){
        alert(e);
    }


}


function ListadoCP(Filtro,Evento,Pagina)
{
    try{

        $.ajax( {type: 'POST',
            url:  '../Model/ModelContenedorCatalogos.php',
            data: '__cmd=setConsultaCpCatalogos&Cp=' + Filtro + '&Evento=' + Evento + '&Pagina='+ Pagina,
            success: function(RESPUESTA) {
                $("#ContendordivCp").html(String(RESPUESTA));
                $('.modalConsultaCp').modal('show');
            },
            error: function( )   { },
            complete: function(RESPUESTA) { }
        });

    }catch(e){
        alert(e);
    }
}

function ListadoPaises(Filtro,Evento,Pagina)
{
    try{

        $.ajax( {type: 'POST',
            url:  '../Model/ModelContenedorCatalogos.php',
            data: '__cmd=setConsultaPaisesCatalogos&Cp=' + Filtro + '&Evento=' + Evento + '&Pagina='+ Pagina,
            success: function(RESPUESTA) {
                $("#ContendordivCp").html(String(RESPUESTA));
                $('.modalConsultaCp').modal('show');
            },
            error: function( )   { },
            complete: function(RESPUESTA) { }
        });

    }catch(e){
        alert(e);
    }
}

function ListadoEstados(Filtro,Evento,Pagina)
{
    try{
        $.ajax( {type: 'POST',
            url:  '../Model/ModelContenedorCatalogos.php',
            data: '__cmd=setConsultaEstadoRiesgoCatalogos&EstadoRiesgo=' + Filtro + '&Evento=' + Evento + '&Pagina='+ Pagina,
            success: function(RESPUESTA) {
                $("#ContendordivEstadoRiesgo").html(String(RESPUESTA))
                $('.modalConsultaEstadoRiesgo').modal('show');
            },
            error: function( )   { },
            complete: function(RESPUESTA) { }
        });

    }catch(e){
        alert(e);
    }
}

function ListadoCiudades(Filtro,Evento,Pagina)
{
    try{

        $.ajax( {type: 'POST',
            url:  '../Model/ModelContenedorCatalogos.php',
            data: '__cmd=setConsultaCiudadShowRiesgoCatalogos&CiudadRiesgo=' + Filtro + '&Evento=' + Evento + '&Pagina='+ Pagina,
            success: function(RESPUESTA) {
                $("#ContendordivCiudadRiesgo").html(String(RESPUESTA))
                $('.modalConsultaCiudadRiesgo').modal('show');
            },
            error: function( )   { },
            complete: function(RESPUESTA) { }
        });

    }catch(e){
        alert(e);
    }
}

function ListadoActividades(Filtro,Evento,Pagina)
{
    try{

        $.ajax( {type: 'POST',
            url:  '../Model/ModelContenedorCatalogos.php',
            data: '__cmd=setConsultaGiroRiesgoCatalogos&GiroRiesgo=' + Filtro + '&Evento=' + Evento + '&Pagina='+ Pagina,
            success: function(RESPUESTA) {
                $("#ContendordivGiroRiesgo").html(String(RESPUESTA))
                $('.modalConsultaGiroRiesgo').modal('show');
            },
            error: function( )   { },
            complete: function(RESPUESTA) { }
        });

    }catch(e){
        alert(e);
    }
}

function ListadoPuestos(Filtro,Evento,Pagina)
{
    try{

        $.ajax( {type: 'POST',
            url:  '../Model/ModelContenedorCatalogos.php',
            data: '__cmd=setConsultaPuestosCatalogos&BuscaPuesto=' + Filtro + '&Evento=' + Evento + '&Pagina='+ Pagina,
            success: function(RESPUESTA) {
                $("#ContendordivPuesto").html(String(RESPUESTA))
                $('.modalConsultaPuesto').modal('show');
            },
            error: function( )   { },
            complete: function(RESPUESTA) { }
        });

    }catch(e){
        alert(e);
    }
}


//*****************FUNCIONES **************//
function ActualizaLista(Evento,Pagi)
{
    var TipoListado = $("#TipoListado").val();
    //var Paginacion  = $("#Paginacion").val();
    var Paginacion  = Pagi;
    var Filtro     = "";
    
    if(TipoListado == "LC")
    {
        Filtro = $("#BuscaRFClc").val();
        ListadoCondusef(Filtro,Evento,Paginacion);

    }else if(TipoListado == "LP"){
	    Filtro = $("#BuscaRFClp").val();
        ListadoPropio(Filtro,Evento,Paginacion);
    
    }else if(TipoListado == "SAT"){
    	
    	Filtro = $("#BuscaRFCSat").val();
        ListadoSAT(Filtro,Evento,Paginacion);
    	
    }else if(TipoListado == "PPE")
    {
        Filtro = $("#BuscaRFC").val();
        ListadoPPE(Filtro,Evento,Paginacion);
    }else if(TipoListado == "CP")
    {
        Filtro = $("#Cp").val();
        ListadoCP(Filtro,Evento,Paginacion);

    }else if(TipoListado == "ESTADOS")
    {
        Filtro = $("#EstadoRiesgo").val();
        ListadoEstados(Filtro,Evento,Paginacion);

    }else if(TipoListado == "CIUDADES")
    {
        Filtro = $("#CiudadRiesgo").val();
        ListadoCiudades(Filtro,Evento,Paginacion);

    }else if(TipoListado == "ACTIVIDADES")
    {
        Filtro = $("#GiroRiesgo").val();
        ListadoActividades(Filtro,Evento,Paginacion);

    }else if(TipoListado == "PUESTOS")
    {
        Filtro = $("#BuscaPuesto").val();
        ListadoPuestos(Filtro,Evento,Paginacion);

    }else if( TipoListado == "Paises" )
    {
	    Filtro = $("#BuscaPuesto").val();
        ListadoPaises(Filtro,Evento,Paginacion);
    }


}
function CambiaPagina(OBJ)
{

    var TipoListado = $("#TipoListado").val();
    var Paginacion  = $(OBJ).val();
    var Filtro      = "";

    if(TipoListado == "LC")
    {
        Filtro = $("#BuscaRFClc").val();
        ListadoCondusef(Filtro,'',Paginacion);

    }else if(TipoListado == "LP")
    {
        Filtro = $("#BuscaRFClp").val();
        ListadoPropio(Filtro,'',Paginacion);
    }else if(TipoListado == "PPE")
    {
        Filtro = $("#BuscaRFC").val();
        ListadoPPE(Filtro,'',Paginacion);
    }else if(TipoListado == "CP")
    {
        Filtro = $("#Cp").val();
        ListadoCP(Filtro,'',Paginacion);

    }else if(TipoListado == "CIUDADES")
    {
        Filtro = $("#CiudadRiesgo").val();
        ListadoCiudades(Filtro,'',Paginacion);

    }else if(TipoListado == "ACTIVIDADES")
    {
        Filtro = $("#GiroRiesgo").val();
        ListadoActividades(Filtro,'',Paginacion);

    }else if(TipoListado == "PUESTOS")
    {
        Filtro = $("#BuscaPuesto").val();
        ListadoPuestos(Filtro,'',Paginacion);

    }
}
//***************************************//

function EliminarPais( ID_pais )
{
	
	$.ajax({ type: 'POST',
	         url:  '../Model/ModelContenedorCatalogos.php', 
			 data: '__cmd=setEliminaCatalogosPais&ID_pais=' + ID_pais,
			 success: function(RESPUESTA) {

                RESPUESTA = RESPUESTA.trim();
                RESPUESTA = String(RESPUESTA);

			  	if( RESPUESTA == "LISTO" ){ 
				  	alert("�El registro se elimino con �xito!");
				  	location.replace("/novacredit/s2credit/lib/AppPld/Views/ViewsContenedorCatalogos.php");
			  	}else{
					alert("Parametros incorrectos"); 	
			  	}  
			  },
			  error: function( )   { },
			  complete: function(RESPUESTA) {  }  
			});
	
} //@end