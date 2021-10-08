function numReport(obj){
	objMask = new Mask("###"); 
	objMask.attach(obj);
} 

function ctaContable(obj){
	objMask = new Mask("####-#-##-###"); 
	objMask.attach(obj);
}

function monto(obj){
	objMask = new Mask("##.##");
	objMask.attach(obj);
}
function precio(obj){
	objMask = new Mask("###.##");
	objMask.attach(obj);
}

function segFechaMask(obj){ //FECHA (text)
	
		objMask = new Mask("####/##/##");
		objMask.attach(obj);
}

function timeMask(obj){
		objMask = new Mask("##:##");
		objMask.attach(obj);
}
function integerMask(obj){
		objMask = new Mask("##############################################################");
		objMask.attach(obj)
}
