//AJAX ###################


function loadURL(url,divContainer,divLoading,loadingImg){ // Enviar GET
	
	AjaxRequest.get(
  {
    'url': url
	,'timeout':100000
    ,'onSuccess':function(req){ document.getElementById(divContainer).innerHTML = req.responseText;}
	,'onTimeout':function(req){
		
		alert("Tiempo Excedido");	
	}
    ,'onError':function(req){ alert('Error!\nStatusText='+req.statusText+'\nContents='+req.responseText);}
	,'onLoading':function(req){document.getElementById(divLoading).innerHTML = loadingImg}
	,'onComplete':function(req){document.getElementById(divLoading).innerHTML = ''}
  }
);
	
}// <,--





// 
function setSubmitURL(form,processFile){ //Enviar Formulario
	
	var url = processFile;
	var isFirst = 0;
	var varConcat;
	
	for(i=0;i<=form.elements.length-1;i++){
		
	if (form.elements[i].type=="checkbox" || form.elements[i].type=="radio" ){
		if (form.elements[i].checked){
		if (isFirst<=0){
			varConcat = "?";
			isFirst++;
		}
		else{
			varConcat = "&";
		}
		url += varConcat+form.elements[i].name+"="+form.elements[i].value
		}
		
	}
	else{
		if (isFirst<=0){
			varConcat = "?";
			isFirst++;
		}
		else{
			varConcat = "&";
		}
		url += varConcat+form.elements[i].name+"="+form.elements[i].value
		}
	}
	
	return url;
	

}// <<--




// FORMA MAS TRADICIONAL DE SUBMIT ESTILO AJAX
function submitForm(theform,l) {
	  var status = AjaxRequest.submit(
    theform
    ,{
      'onSuccess':function(req){ 
	  	if(req.responseText == 1){ // OK
			alert("OK");
		}
		else{ // ??
			alert(req.responseText);
		}
	  }
    }
  );
  return status;
}// <<--