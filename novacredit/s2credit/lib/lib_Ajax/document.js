///Fondo de agua estático
function fondo_agua(img){
if (document.all||document.getElementById)
document.body.style.background="url("+img+") white center no-repeat fixed"
}





///Abrir Pop
function open_pop(url,id,ancho,alto){
window.open(url,id,'width=' + ancho + ',height=' + alto + ',toolbar=0,location=0,directories=0,status=0,dependent=0,menuBar=0,scrollbars=1,resizable=0,left=0,top=0');
}


//Confirmar
function confirmar(url,str){
var confirmar 
confirmar = confirm (str)

if (confirmar==true){
location=url
}
else{
return 
}
}



//Form 2 pop
function createTarget(target){
window.open("", target, "width=600,height=550toolbar=0,location=0,directories=0,status=0,dependent=0,menuBar=0,scrollbars=1,resizable=0,left=100,top=100");
return true;
}



function dupli(source,target){ // Duplica el contenido de un div en otro
	document.getElementById(target).innerHTML +="<hr>"+document.getElementById(source).innerHTML;
}





//envia documentos a frames por sus indices | send2Frames(int indice frame, str url, int indice frame, str url, int indice frame, str url ...)    send2Frames(0,'doc0.asp',1,'doc1.htm',2,'doc2.php')
function send2Frames(){
	
fIndex = 0
loc  = 1
while ((arguments.length/2)-1){
		window.parent.frames[arguments[fIndex]].location=arguments[loc]
	//alert ("Frame: "+arguments[fIndex]+" \nLocation: "+arguments[loc])
	fIndex += 2
	loc += 2
	}
	
	  }




function selOption(selObj,value){
	
	for (i=0;i<=selObj.options.length;i++){
			if (selObj.options[i].value == value){
				selObj.selectedIndex = i;
				}
		}	
	
	}





function checkall(element,thestate){ // checar los checkboxes
	if (element.length>0){
		for (c=0;c<element.length;c++){
			element[c].checked=thestate
		}
	}	
	else{
		element.checked=thestate	
	}

}


// Revisa si existe un docum	ento o no
function chkObject(inParent,theVal) {
	if(inParent){
		if (window.opener.document.getElementById(theVal) != null) {
			return true;
		} else {
			return false;
		}
	}else{
		if (document.getElementById(theVal) != null) {
			return true;
		} else {
			return false;
		}
	}
}


function ltrim(s) {
   return s.replace(/^\s+/, "");
}

function rtrim(s) {
   return s.replace(/\s+$/, "");
}

function trim(s) {
   return rtrim(ltrim(s));
}