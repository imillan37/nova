function validaFecha(oTxt){
	var bOk = true;
	if( oTxt.value != "" ) {
		bOk = bOk && (valAno(oTxt));
		bOk = bOk && (valMes(oTxt));
		bOk = bOk && (valDia(oTxt));
		bOk = bOk && (valSep(oTxt));
	}
	return bOk;
}
function restringirCampoFecha(OBJETO) {
	var TEXTO    = OBJETO.value;
	var LONGITUD = TEXTO.length;
	var POSICION = LONGITUD - 1;
	if( ( TEXTO.charAt(POSICION) != '0' ) &&  
			( TEXTO.charAt(POSICION) != '1' ) && 
			( TEXTO.charAt(POSICION) != '2' ) && 
			( TEXTO.charAt(POSICION) != '3' ) && 
			( TEXTO.charAt(POSICION) != '4' ) && 
			( TEXTO.charAt(POSICION) != '5' ) && 
			( TEXTO.charAt(POSICION) != '6' ) && 
			( TEXTO.charAt(POSICION) != '7' ) && 
			( TEXTO.charAt(POSICION) != '8' ) && 
			( TEXTO.charAt(POSICION) != '9' ) ) {
		OBJETO.value = TEXTO.substring( 0, (POSICION) );						
	} 
	if( LONGITUD == 3 ) {
		if( TEXTO.charAt(2) != '/' ) {
			OBJETO.value = TEXTO.substring( 0, 2 ) + '/' + TEXTO.charAt(2);						
		}
	} else if( LONGITUD == 6 ) {
		if( TEXTO.charAt(5) != '/' ) {
			OBJETO.value = TEXTO.substring( 0, 5 ) + '/' + TEXTO.charAt(5);
		}
	}
}
function esDigito(sChr) {
	var sCod = sChr.charCodeAt(0);
	return ((sCod > 47) && (sCod < 58));
}
function valSep(oTxt){
	var bOk = false;
	bOk = bOk || ((oTxt.value.charAt(2) == "-") && (oTxt.value.charAt(5) == "-"));
	bOk = bOk || ((oTxt.value.charAt(2) == "/") && (oTxt.value.charAt(5) == "/"));
	return bOk;
}
function finMes(oTxt){
	var nMes = parseInt(oTxt.value.substr(3, 2), 10);
	var nRes = 0;
	switch (nMes){
		case 1: nRes = 31; break;
		case 2: nRes = 29; break;
		case 3: nRes = 31; break;
		case 4: nRes = 30; break;
		case 5: nRes = 31; break;
		case 6: nRes = 30; break;
		case 7: nRes = 31; break;
		case 8: nRes = 31; break;
		case 9: nRes = 30; break;
		case 10: nRes = 31; break;
		case 11: nRes = 30; break;
		case 12: nRes = 31; break;
	}
	return nRes;
}
function valDia(oTxt){
	var bOk = false;
	var nDia = parseInt(oTxt.value.substr(0, 2), 10);
	bOk = bOk || ((nDia >= 1) && (nDia <= finMes(oTxt)));
	return bOk;
}
function valMes(oTxt){
	var bOk = false;
	var nMes = parseInt(oTxt.value.substr(3, 2), 10);
	bOk = bOk || ((nMes >= 1) && (nMes <= 12));
	return bOk;
}
function valAno(oTxt){
	var bOk = true;
	var nAno = oTxt.value.substr(6);
	bOk = bOk && ((nAno.length == 2) || (nAno.length == 4));
	if (bOk){
		for (var i = 0; i < nAno.length; i++){
			bOk = bOk && esDigito(nAno.charAt(i));
		}
	}
	return bOk;
}