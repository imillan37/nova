function cargaDireccionDesdeCP( cp, col, edo, cty, pob, vcol, vedo, vcty, vpob ) {
	jQuery.getJSON(  CLASS_PATH + 'direccion_desde_cp.php', 'cp=' + cp, function(direcciones) {
		var i
		jQuery( '#' + col ).empty();
		jQuery( '#' + edo ).empty();
		jQuery( '#' + cty ).empty();
		jQuery( '#' + pob ).empty();
		for( i = 0; i < direcciones.colonia.length; i++ ) {
			jQuery( '#' + col ).append('<option>' + direcciones.colonia[i] + '</option>');
		}
		for( i = 0; i < direcciones.estado.length; i++ ) {
			jQuery( '#' + edo ).append('<option>' + direcciones.estado[i] + '</option>');
		}
		for( i = 0; i < direcciones.ciudad.length; i++ ) {
			jQuery( '#' + cty ).append('<option>' + direcciones.ciudad[i] + '</option>');
		}
		for( i = 0; i < direcciones.poblacion.length; i++ ) {
			jQuery( '#' + pob ).append('<option>' + direcciones.poblacion[i] + '</option>');
		}
		jQuery( '#' + col ).val(vcol); 
		jQuery( '#' + edo ).val(vedo); 
		jQuery( '#' + cty ).val(vcty); 
		jQuery( '#' + pob ).val(vpob); 
	});
}

function cargaDireccionDesdeOpenerCP( cp, col, edo, cty, pob, vcp, vcol, vedo, vcty, vpob ) {
	jQuery.getJSON( CLASS_PATH + 'direccion_desde_cp.php', 'cp=' + vcp, function(direcciones) {
		var i
		jQuery( '#' + col, opener.document ).empty();
		jQuery( '#' + edo, opener.document ).empty();
		jQuery( '#' + cty, opener.document ).empty();
		jQuery( '#' + pob, opener.document ).empty();
		for( i = 0; i < direcciones.colonia.length; i++ ) {
			jQuery( '#' + col, opener.document ).append('<option>' + direcciones.colonia[i] + '</option>');
		}
		for( i = 0; i < direcciones.estado.length; i++ ) {
			jQuery( '#' + edo, opener.document ).append('<option>' + direcciones.estado[i] + '</option>');
		}
		for( i = 0; i < direcciones.ciudad.length; i++ ) {
			jQuery( '#' + cty, opener.document ).append('<option>' + direcciones.ciudad[i] + '</option>');
		}
		for( i = 0; i < direcciones.poblacion.length; i++ ) {
			jQuery( '#' + pob, opener.document ).append('<option>' + direcciones.poblacion[i] + '</option>');
		}
		jQuery( '#' + cp, opener.document ).val(vcp); 
		jQuery( '#' + col, opener.document ).val(vcol); 
		jQuery( '#' + edo, opener.document ).val(vedo); 
		jQuery( '#' + cty, opener.document ).val(vcty); 
		jQuery( '#' + pob, opener.document ).val(vpob); 
		
		window.close();
		
	});
}
