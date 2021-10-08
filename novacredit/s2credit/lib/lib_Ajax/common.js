
// #################################################################################

/* 
- DUPLICAR CAMPOS DE FORMULARIO  -


<div id="readroot" style="display:none">

	<input value="Remove review" onclick="this.parentNode.parentNode.removeChild(this.parentNode);" type="button"><br><br>

	<input name="cd" value="title">

	<select name="rankingsel">
		<option>Rating</option>
		<option value="excellent">Excellent</option>
		<option value="good">Good</option>
		<option value="ok">OK</option>
		<option value="poor">Poor</option>
		<option value="bad">Bad</option>
	</select><br><br>

	<textarea rows="5" cols="20" name="review">Short review</textarea>
	<br>Radio buttons included to test them in Explorer:<br>
	<input name="something" value="test1" type="radio">Test 1<br>
	<input name="something" value="test2" type="radio">Test 2

</div>



	<span id="writeroot"></span>

*/
//var doCounter = true
function moreFields(source,target) { 
	counter++;
	var newFields = document.getElementById(source).cloneNode(true);
	newFields.id = '';
	newFields.style.display = 'block';
	var newField = newFields.childNodes;
	for (var i=0;i<newField.length;i++) {
		var theName = newField[i].name
		if (theName){
	/*		if (doCounter == true){
				newField[i].name = theName + counter;
			}
			else{*/
				newField[i].name = theName;
		//	}
		}
	}
	var insertHere = document.getElementById(target);
	insertHere.parentNode.insertBefore(newFields,insertHere);
}

// #################################################################################


function copyFieldContent(source,target){
		target.value=source.value
}