function setUnsetVentanaPlatica(tituloPlatica) {
	if (jQuery('#platicaApp_'+tituloPlatica+' .platicaAppcontent').css('display') == 'none') {  
		jQuery('#platicaApp_'+tituloPlatica+' .platicaAppcontent').css('display','block');
		jQuery('#platicaApp_'+tituloPlatica+' .platicaAppinput').css('display','block');
		jQuery("#platicaApp_"+tituloPlatica+" .platicaAppcontent").scrollTop($("#platicaApp_"+tituloPlatica+" .platicaAppcontent")[0].scrollHeight);
	} else {
		jQuery('#platicaApp_'+tituloPlatica+' .platicaAppcontent').css('display','none');
		jQuery('#platicaApp_'+tituloPlatica+' .platicaAppinput').css('display','none');
	}	
}
function cerrarVentanaPlatica(tituloPlatica) {
	jQuery('#platicaApp_'+tituloPlatica).css('display','none');
}
function getChatMessages() {
	jQuery.ajax( {type: 'POST',
								url:  JS_PATH + 'chatMessages.php', 
								data: '__cmd=getMessages',
								success: function(RESPUESTA) { 								
								  html = RESPUESTA;																
								  html = String(html);
								  if( html != '' ) {  
										var formulario = jQuery.parseJSON(html);		
										for( var j in formulario['ID_Usuario_Origen'] ) {
											if (jQuery("#platicaApp_"+formulario['ID_Usuario_Origen'][j]).length <= 0) {
												crearVentanaPlatica(formulario['ID_Usuario_Origen'][j],formulario['Usuario'][j]);
											}
											if (jQuery("#platicaApp_"+formulario['ID_Usuario_Origen'][j]).css('display') == 'none') {
												jQuery("#platicaApp_"+formulario['ID_Usuario_Origen'][j]).css('display','block');
												reacomodaVentanaPlatica();
											}
										  jQuery("#platicaApp_"+formulario['ID_Usuario_Origen'][j]+" .platicaAppcontent").append('<div class="platicaAppmessage"><span class="platicaAppinfo">'+formulario['Mensaje'][j]+'</span></div>');
											jQuery("#platicaApp_"+formulario['ID_Usuario_Origen'][j]+" .platicaAppcontent").scrollTop(jQuery("#platicaApp_"+formulario['ID_Usuario_Origen'][j]+" .platicaAppcontent")[0].scrollHeight);  								
											jQuery('#platicaApp_'+formulario['ID_Usuario_Origen'][j]+' .platicaApphead').toggleClass('platicaAppblink');
										}
									}																			
								},
								complete: function() {
									setTimeout( "getChatMessages()", 3000 );
								}  
	});
};
function tecleaVentanaPlatica(event,platicaApptextarea,tituloPlatica) {	
	if(event.keyCode == 13 && event.shiftKey == 0)  {
		message = jQuery(platicaApptextarea).val();
		message = message.replace(/^\s+|\s+$/g,"");
		jQuery(platicaApptextarea).val('');
		jQuery(platicaApptextarea).focus();
		jQuery(platicaApptextarea).css('height','44px');
		if (message != '') {
			var html = jQuery.ajax({
		  	url: JS_PATH + "chatMessages.php?__cmd=sendMessage&ID_Usuario_Destino="+tituloPlatica+"&Mensaje="+message,
		  	async: false
		  }).responseText;	
			message = message.replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/\"/g,"&quot;");
			jQuery("#platicaApp_"+tituloPlatica+" .platicaAppcontent").append('<div class="platicaAppmessage"><span class="platicaAppmessagefrom">'+nombreUsuarioPlatica+':&nbsp;&nbsp;</span><span class="platicaAppmessagecontent">'+message+'</span></div>');
			jQuery("#platicaApp_"+tituloPlatica+" .platicaAppcontent").scrollTop(jQuery("#platicaApp_"+tituloPlatica+" .platicaAppcontent")[0].scrollHeight);
		}
		return false;
	}
	var adjustedHeight = platicaApptextarea.clientHeight;
	var maxHeight = 94;
	if (maxHeight > adjustedHeight) {
		adjustedHeight = Math.max(platicaApptextarea.scrollHeight, adjustedHeight);
		if (maxHeight)
			adjustedHeight = Math.min(maxHeight, adjustedHeight);
		if (adjustedHeight > platicaApptextarea.clientHeight)
			jQuery(platicaApptextarea).css('height',adjustedHeight+8 +'px');
	} else {
		jQuery(platicaApptextarea).css('overflow','auto');
	}
}
function crearVentanaPlatica(tituloPlatica,usuario) {
	if (jQuery("#platicaApp_"+tituloPlatica).length > 0) {
		if (jQuery("#platicaApp_"+tituloPlatica).css('display') == 'none') {
			jQuery("#platicaApp_"+tituloPlatica).css('display','block');
			reacomodaVentanaPlatica();
		}
		jQuery("#platicaApp_"+tituloPlatica+" .platicaApptextarea").focus();
		return;
	}
	jQuery(" <div />" ).attr("id","platicaApp_"+tituloPlatica)
	.addClass("platicaApp") // EL USERNAME DE ABAJO DEBE SER EL DEL USUARIO AL QUE SE ESTA ENVIANDO EL MENSAJE
	.html('<div class="platicaApphead"><div class="platicaApptitle">'+usuario+'</div><div class="platicaAppoptions"><a href="javascript:void(0)" onclick="javascript:setUnsetVentanaPlatica(\''+tituloPlatica+'\')">-</a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" onclick="javascript:cerrarVentanaPlatica(\''+tituloPlatica+'\')">x</a></div><br clear="all"/></div><div class="platicaAppcontent"></div><div class="platicaAppinput"><textarea class="platicaApptextarea" onkeydown="javascript:return tecleaVentanaPlatica(event,this,\''+tituloPlatica+'\');"></textarea></div>')
	.appendTo(jQuery( "body" ));
	jQuery("#platicaApp_"+tituloPlatica).css('bottom', '0px');
	ventanasPlaticalength = 0;
	for (x in ventanasPlatica) {
		if (jQuery("#platicaApp_"+ventanasPlatica[x]).css('display') != 'none') {
			ventanasPlaticalength++;
		}
	}
	if (ventanasPlaticalength == 0) {
		jQuery("#platicaApp_"+tituloPlatica).css('right', '20px');
	} else {
		width = (ventanasPlaticalength)*(225+7)+20;
		jQuery("#platicaApp_"+tituloPlatica).css('right', width+'px');
	}
	ventanasPlatica.push(tituloPlatica);
	platicaAppFocus[tituloPlatica] = false;
	jQuery("#platicaApp_"+tituloPlatica+" .platicaApptextarea").blur(function(){
		platicaAppFocus[tituloPlatica] = false;
		jQuery("#platicaApp_"+tituloPlatica+" .platicaApptextarea").removeClass('platicaApptextareaselected');
	}).focus(function(){
		platicaAppFocus[tituloPlatica] = true;
		mensajesNuevos[tituloPlatica] = false;
		jQuery('#platicaApp_'+tituloPlatica+' .platicaApphead').removeClass('platicaAppblink');
		jQuery("#platicaApp_"+tituloPlatica+" .platicaApptextarea").addClass('platicaApptextareaselected');
	});
	jQuery("#platicaApp_"+tituloPlatica).click(function() {
		if (jQuery('#platicaApp_'+tituloPlatica+' .platicaAppcontent').css('display') != 'none') {
			jQuery("#platicaApp_"+tituloPlatica+" .platicaApptextarea").focus();
		}
	});
	jQuery("#platicaApp_"+tituloPlatica).show();
}
function reacomodaVentanaPlatica() {
	align = 0;
	for (x in ventanasPlatica) {
		tituloPlatica = ventanasPlatica[x];
		if (jQuery("#platicaApp_"+tituloPlatica).css('display') != 'none') {
			if (align == 0) {
				jQuery("#platicaApp_"+tituloPlatica).css('right', '20px');
			} else {
				width = (align)*(225+7)+20;
				jQuery("#platicaApp_"+tituloPlatica).css('right', width+'px');
			}
			align++;
		}
	}
}
function platicarVentanaPlatica(chatuser,usuario) {
	crearVentanaPlatica(chatuser,usuario);
	$("#platicaApp_"+chatuser+" .platicaApptextarea").focus();
}