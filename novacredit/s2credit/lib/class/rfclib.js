
/* Calcula el RFC y el CURP de una persona física su homoclave incluida. 
 * Junio.2012
 * Metodos disponibles:
 *
 * CalcularRFC(nombre, apellido_paterno, apellido_materno,fecha_de_nacimiento) - Devuelve RFC a 10 caracteres
 * CalcularRFC13(nombre, apellido_paterno, apellido_materno,fecha_de_nacimiento) - Devuelve RFC a 13 caracteres
 * CalcularCurp(nombre, apellido_paterno, apellido_materno,fecha_de_nacimiento,sexo,estado_de_nacimiento) - Devuelve CURP a 18 caracteres
 * CalcularHomoclave(nombre,apellido_paterno, apellido_materno) - Devuelve 2 caracteres verificadores(caracteres 11 y 12 del RFC)
 * CalcularHomoclaveCompleta(nombre,apellido_paterno, apellido_materno, fecha) - Devuelve 3 caracteres verificadores(caracteres 11-13 del RFC)
 * DigitoVerificador(rfc_a_12_caracteres) - Devuelve 1 digito verificador(caracter 13 del RFC)
 * 
 * /


/*******************************************************************************
FUNCIONES PRINCIPALES
********************************************************************************/

/* Devuelve rfc de 10 caracteres sin homoclave
 * Parametros: 
 * nombre: string - nombre o nombres de pila
 * apPaterno: string - Primer apellido
 * apMaterno: string - segundo apellido
 * fNac: string - fecha de nacimiento
 */
function CalcularRFC(nombre,  apPaterno,  apMaterno, fNac)
	{		
		return CalcularComun(nombre, apPaterno, apMaterno,fNac,"rfc");
	}

/* Devuelve rfc de 13 caracteres(con homoclave)
 * Parametros: 
 * nombre: string - nombre o nombres de pila
 * apPaterno: string - Primer apellido
 * apMaterno: string - segundo apellido
 * fNac: string - fecha de nacimiento
 */

function CalcularRFC13(nombre,  apPaterno,  apMaterno, fNac)
	{		

	 rfc=CalcularComun(nombre, apPaterno, apMaterno,fNac,"rfc");
	 rfc+=CalcularHomoclave(nombre,apPaterno,apMaterno);
	 rfc+=DigitoVerificador(rfc);

	 return rfc;
	}

/* Devuelve 3 caracteres verificadores
 * Parametros: 
 * nombre: string - nombre o nombres de pila
 * apPaterno: string - Primer apellido
 * apMaterno: string - segundo apellido
 * fNac: string - fecha de nacimiento
 */
 
function CalcularHomoclaveCompleta(nombre,  apPaterno,  apMaterno, fNac)
	{		
		clave="";
	 rfc=CalcularComun(nombre, apPaterno, apMaterno,fNac,"rfc");
	 clave+=CalcularHomoclave(nombre,apPaterno,apMaterno);
	 clave+=DigitoVerificador(rfc+clave);

	 return clave;
	}


/* Devuelve Curp de 18 caracteres completo 
 * (Nota: El caracter 17 puede diferir en casos muy remotos, ya que es aginado por la RENAPO)
 * Parametros: 
 * nombre: string - nombre o nombres de pila
 * apPaterno: string - Primer apellido
 * apMaterno: string - segundo apellido
 * fNac: string - fecha de nacimiento
 * sexo: int - 0->hombre , 1->Mujer
 * estado: int - posicion del estado de nacimiento dentro de una lista ordenada alfabeticamente,
 * comenzando en 1.
 */

function CalcularCurp(nombre,apPaterno,apMaterno,fNac,sexo,estado){


	curp=CalcularComun(nombre,apPaterno,apMaterno,fNac,"curp");


	nombre=Trim(nombre);
	apPaterno=Trim(apPaterno);
	apMaterno=Trim(apMaterno);

	nombre=nombre.toUpperCase();
	apPaterno=apPaterno.toUpperCase();
	apMaterno=apMaterno.toUpperCase();

    //quitar prefijos de los nombres y remover nombres comunes
    nombre=SinPrefijosYComunes(nombre);
    apPaterno=SinPrefijosYComunes(apPaterno);
    apMaterno=SinPrefijosYComunes(apMaterno);

	//Datos Especificos del CURP
	//concatenar sexo
	if(sexo==0 || sexo=='MASCULINO') 
		curp+="H";
	else 
		curp+="M";

    //obtener codigo del estado de nacimiento
    curp+=obtenerCodigoEstado(estado);
    //curp+=estado;

    //primeras consonantes del nombre y apellidos
    curp+=primerConsonante(apPaterno);
    curp+=primerConsonante(apMaterno);
    curp+=primerConsonante(nombre);

    
    //checar anio de nacimiento
    digitos=curp.substr(4,2);
    if(parseInt(digitos)>=2000)
    	curp+="A"
    else curp+="0";
    
    //obtener digito verificador
    
 	curp+=calcularDigitoCurp(curp); 

    return curp;
}



/********************************************************************************
FUNCIONES NECESARIAS COMUNES DE CURP Y RFC
*********************************************************************************/

function CalcularComun(nombre,  apPaterno,  apMaterno, FNac, tipo)
	{
	
	//alert("llego:"+FNac);
	
	cadenaComun="";
	//Limpiar las palabras
	nombre=Trim(nombre);
	apPaterno=Trim(apPaterno);
	apMaterno=Trim(apMaterno);

	nombre=nombre.toUpperCase();
	apPaterno=apPaterno.toUpperCase();
	apMaterno=apMaterno.toUpperCase();

    //quitar prefijos de los nombres y remover nombres comunes
    nombre=SinPrefijosYComunes(nombre);
    apPaterno=SinPrefijosYComunes(apPaterno);
    apMaterno=SinPrefijosYComunes(apMaterno);
    
    //extraer primeras letras letras del apellido Paterno
    cadenaComun+=primerLetra(apPaterno); 
    cadenaComun+=primerVocal(apPaterno); 
    
    //extraer primera letra del apellido Materno
    cadenaComun+=primerLetra(apMaterno);
    
    //extraerprimera letra del nombre de pila
    
    cadenaComun+=primerLetra(nombre);

    //quitar palabras incorrectas
    if(tipo=="rfc")
    	cadenaComun=SinAltisonantes(cadenaComun);
    else
    	cadenaComun=SinAltisonantesCurp(cadenaComun);

    //Extraer digitos de la fecha de nacimiento y concatenarlos
    digitos=FNac.split("/");
    //if(digitos.length<2)digitos=FNac.split("-");
    cadenaComun+=digitos[2].substr(2,3);
    cadenaComun+=digitos[1]+digitos[0];


	return cadenaComun;
	}




function primerVocal(palabra){
for( i=1; i<palabra.length; i++)
		{
		 	c = palabra.substring(i, i+1);
			if (EsVocal(c))
			{
				return SinAcento(c);
				break;
			}
		}
	return "X";
}

function primerConsonante(palabra){
for( i=1; i<palabra.length; i++)
		{
		 	c = palabra.substring(i, i+1);
		 	console.log(c+": "+c.charCodeAt(0));
			if (!EsVocal(c)&&c!="Ñ")
			{
				return c;
				break;
			}
		}
	return "X";
}


//creada para reemplazar la ñ por X
function primerLetra(palabra){
	if(palabra!=""){
	letra=palabra.substr(0,1);
	if(letra!="Ñ")
		return letra;
	else return "X";
	}
	else 
		return "X";

}



/********************************************************************
Elimina Prefijos de los nombres y nombres comunes como Maria y Jose
*******************************************************************/

function SinPrefijosYComunes(nombres)
	{
		//quitar espacios multiples
		
		nombres= nombres.replace(/\s{2,}/g,' ');
		nombres= nombres.replace(/Í/g,'I');
		nombres= nombres.replace("Á",'A');
		nombres= nombres.replace(/É/g,'E');
		//arrglo de expresiones regulares de palabras a no tomar en cuenta
		prefix= new Array(

							   /^MA. DE LOS /,
							   /^MARIA DE LOS /,
							   /^MARIA DEL /,
							   /^MARIA DE /,
							   /^MARIA /,
							   /^MA DE /,
							   /^MA. DE /,
							   /^MA. DEL /,
							   /^MA DEL /,
							   /^MA. /,	
							   /^MA. /,	
							   /^DE LA /, 
							   /^DE EL /, 
							   /^DE LOS /, 
							   /^DE LAS /,
							   /^DA /, 
							   /^DAS /, 
							   /^DE /, 
							   /^DEL /, 
							   /^DER /, 
							   /^DI /, 
							   /^DIE /, 
							   /^DD /,
							   /^EL /, 
							   /^LA /, 
							   /^LOS /, 
							   /^LAS /, 
							   /^LE /, 
							   /^LES /, 
							   /^MAC /, 
							   /^MC /, 
							   /^VAN /, 
							   /^VON /, 
							   /^Y /,
							   
							   
							   /^JOSE DE /,
							   /^JOSE /,
							   /^J. DE /
							   );

		for(i=0;i<prefix.length;i++)
		{
			
			nombres=nombres.replace(prefix[i],"");
			
		}
		
		
	   return(nombres);
	}


/*****************************************************************
Funciones sin valor semantico pero utiles para tratar con cadenas
*****************************************************************/

function Trim(STRING)
	{
		STRING = LTrim(STRING);
		return RTrim(STRING);
	}

function RTrim(STRING)
	{
		while(STRING.charAt((STRING.length -1))==" ")
		{
			STRING = STRING.substring(0,STRING.length-1);
		}
		return STRING;
	}

function LTrim(STRING)
	{
		while(STRING.charAt(0)==" ")
		{
			STRING = STRING.replace(STRING.charAt(0),"");
		}
		return STRING;
	}


function EsVocal( letra)
	{
		//Aunque para el caso del RFC cambié todas las letras a mayúsculas
		//igual agregé las minúsculas.
		letra=SinAcento(letra);
		if (	letra == 'A' || letra == 'E' || letra == 'I' || letra == 'O' || letra == 'U' ||
			letra == 'a' || letra == 'e' || letra == 'i' || letra == 'o' || letra == 'u' ||
			letra == "Á" || letra == 'É' || letra == 'Í' || letra == 'Ó' || letra == 'Ú' ||
			letra == 'á' || letra == 'é' || letra == 'í' || letra == 'ó' || letra == 'ú')
			return true;
		else
			return false;
	}

function inArray(elemento, arreglo)
	{
		for ( var keyVar in arreglo )
		{

			if (keyVar== elemento)
			{
			  return arreglo[keyVar];
			}
		}
		return false;

	}

function SinAcento( vocal )
	{

	  //Minúsculas
	  if(vocal == 'á')
	       return('a');

	  if(vocal == 'é')
	       return('e');

	  if(vocal == 'í')
	       return('i');

	  if(vocal == 'ó')
	       return('o');

	  if(vocal == 'ú')
	       return('u');

	  // Mayusculas
	  if(vocal == 'Á')
	       return('A');

	  if(vocal == 'É')
	       return('E');

	  if(vocal == 'Í')
	       return('I');

	  if(vocal == 'Ó')
	       return('O');

	  if(vocal == 'Ú')
	       return('U');


	  return(vocal);


	}
function strpos( haystack, needle, offset){
    // http://kevin.vanzonneveld.net
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // *     example 1: strpos('Kevin van Zonneveld', 'e', 5);
    // *     returns 1: 14

    var i = haystack.indexOf( needle, offset ); // returns -1
    return i >= 0 ? i : false;
}

/***********************************************************************************************
FUNCIONES PARA CALCULAR HOMOCLAVE Y DE UTILIDAD PARA RFC
***********************************************************************************************/


function SinAltisonantes(palabra)
	{

		var mal = new Array(    "BUEI" ,
								"BUEY" ,
								"CACA" ,
								"CACO" ,
								"CAGA" ,
								"CAGO" ,
								"CAKA" ,
								"CAKO" ,
								"COGE" ,
								"COJA" ,
								"COJE" ,
								"COJI" ,
								"COJO" ,
								"CULO" ,
								"FETO" ,
								"GUEY" ,
								"JOTO" ,
								"KACA" ,
								"KACO" ,
								"KAGA" ,
								"KAGO" ,
								"KOGE" ,
								"KOJO" ,
								"KAKA" ,
								"KULO" ,
								"LOCA" ,
								"LOCO" ,
								"LOKA" ,
								"LOKO" ,
								"MAME" ,
								"MAMO" ,
								"MEAR" ,
								"MEAS" ,
								"MEON" ,
								"MION" ,
								"MOCO" ,
								"MULA" ,
								"PEDA" ,
								"PEDO" ,
								"PENE" ,
								"PUTA" ,
								"PUTO" ,
								"QULO" ,
								"RATA" ,
								"RUIN"  );



		for ( i=0; i< mal.length; i++)
		{
		    if( mal[i] == palabra )
		    {
		        nrfx = mal[i];
		    	nrfx = nrfx.substring(0,3) + 'X';
	                return(nrfx);

		    }
		}

/*	*/
	return(palabra);
	}

/*
 * Devuelve dos caracteres que representan la homoclave en el RFC
 * Recibe: 
 * nombre: string - nombre o nombre de pila de la persona.
 * pat: string - apellido Paterno
 * mat: string - apellido Materno
 */

function CalcularHomoclave(nombre, pat,  mat)
{
        var strNombreComp =  pat.toUpperCase() + " " + mat.toUpperCase() + " " + nombre.toUpperCase();
	var strCharsHc = '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ';
	var strCadena = '0';
	var strChr;
	var intNum1;
	var intNum2;
	var intSum=0;
	var int3;
	var intQuo;
	var intRem;

	for( i=0; i<=strNombreComp.length; i++)
	 {
	   strChr = strNombreComp.substr(i,1);

	   if (strChr==' ' || strChr=='-' )
	   {
	     strCadena = strCadena + '00';
	   }

	   else if (strChr=='Ñ' || strChr=='Ü' )
	   {
	     strCadena = strCadena + '10';
	   }

	   else if (strChr=='A' || strChr=='B' || strChr=='C' || strChr=='D' || strChr=='E' ||
		strChr=='F' || strChr=='G' || strChr=='H' || strChr=='I')
	   {
	    strCadena = strCadena + ((strChr.charCodeAt())-54);
	   }

       else if (strChr=='J' || strChr=='K' || strChr=='L' || strChr=='M' || strChr=='N' ||
		strChr=='O' || strChr=='P' || strChr=='Q' || strChr=='R')
	   {
	     strCadena = strCadena + ((strChr.charCodeAt())-53);
	   }

	  else if (strChr=='S' || strChr=='T' || strChr=='U' || strChr=='V' || strChr=='W' ||
		strChr=='X' || strChr=='Y' || strChr=='Z')
	   {
	     strCadena = strCadena + ((strChr.charCodeAt())-51);
	   }

	   else{
	   		strCadena = strCadena + '00';
	   }

	 }

	 for( i=0; i<(strCadena.length)-1; i++)
	 {
	  intNum1 = parseInt(strCadena.substr(i,2));
	  intNum2 = parseInt(strCadena.substr(i+1,1));
	  intSum = intSum + intNum1 * intNum2;
	 }

	intSum=intSum+' ';
	intSum= Trim(intSum)
	int3 = intSum.substr(-3);
	intQuo = parseInt(int3 / 34);
	intRem = int3 % 34;

	return ((strCharsHc.substr(intQuo, 1)) + (strCharsHc.substr(intRem, 1)));
}


/*
 * Devuelve el digito verificador del RFC
 * Recibe: 
 * rfc_homo: rfc a 12 caracteres, es decir incluyendo 2 caracteres de homoclave
 */

function DigitoVerificador(rfc_homo)
{
 var strChars = '0123456789ABCDEFGHIJKLMN&OPQRSTUVWXYZ*';
 var strDV;
 var intDV;
 intSumas=0
 for(i=0;i<rfc_homo.length;i++)
  {
   strCh=rfc_homo.substr(i,1);
   strCh= ((strCh == ' ') ? '*':strCh);
   intIdx = strpos(strChars,strCh);
   intSumas = intSumas + intIdx * (14 - (i+1));
  }
  if ((intSumas % 11)==0)
  {
   strDV=0;
  }
  else
  {
    intDV = 11 - intSumas % 11;
    if(intDV > 9)
    {
     strDV='A';
    }
    else
    {
       strDV=intDV;
    }
  }
return strDV;
}



/***************************************************************************************************************************
FUNCIONES DE LA CURP
***************************************************************************************************************************/


function SinAltisonantesCurp(palabra)
	{

		var mal = new Array(    

						"BACA",
						"BAKA",
						"BUEI",
						"BUEY",
						"CACA",
						"CACO",
						"CAGA",
						"CAGO",
						"CAKA",
						"CAKO",
						"COGE",
						"COGI",
						"COJA",
						"COJE",
						"COJI",
						"COJO",
						"COLA",
						"CULO",
						"FALO",
						"FETO",
						"GETA",
						"GUEI",
						"GUEY",
						"JETA",
						"JOTO",
						"KACA",
						"KACO",
						"KAGA",
						"KAGO",
						"KAKA",
						"KAKO",
						"KOGE",
						"KOGI",
						"KOJA",
						"KOJE",
						"KOJI",
						"KOJO",
						"KOLA",
						"KULO",
						"LILO",
						"LOCA",
						"LOCO",
						"LOKA",
						"LOKO",
						"MAME",
						"MAMO",
						"MEAR",
						"MEAS",
						"MEON",
						"MIAR",
						"MION",
						"MOCO",
						"MOKO",
						"MULA",
						"MULO",
						"NACA",
						"NACO",
						"PEDA",
						"PEDO",
						"PENE",
						"PIPI",
						"PITO",
						"POPO",
						"PUTA",
						"PUTO",
						"QULO",
						"RATA",
						"ROBA",
						"ROBE",
						"ROBO",
						"RUIN",
						"SENO",
						"TETA",
						"VACA",
						"VAGA",
						"VAGO",
						"VAKA",
						"VUEI",
						"VUEY",
						"WUEI",
						"WUEY"
			);



for ( i=0; i< mal.length; i++)
		{
		    if( mal[i] == palabra )
		    {
		        nrfx = mal[i];
		    	nrfx = nrfx.substr(0,1) + 'X'+nrfx.substr(2,3);
	                return(nrfx);

		    }
		}

/*	*/
	return(palabra);
	}



/*El digito verdificador se calcula asignando un valor del 0 al 36 a cada caracter, de la
siguiente forma:
 (0-0),(1,1)...(A,10),(B-12)...(N,23),(O,25)...(Z,36) 

el 24 no esta asginado por la Ñ,
y multiplicandolo por 19-(posicion en la CURP). Se suman todos los valores y se saca el 
modulo 10. El resultado se le resta a 10 y ese el digito verificador.
*/
function calcularDigitoCurp(curp){

        segRaiz      = curp.substr(0,17);
        chrCaracter  = "0123456789ABCDEFGHIJKLMN-OPQRSTUVWXYZ";
        suma      = 0.0;
        digito    = 0.0;

        for(i=0; i<17; i++){
            for(j=0; j<37; j++){
                if(segRaiz.substr(i,1)==chrCaracter.substr(j,1)){
                    suma+=j* (18 - i);
                }
            }
        }
        digito= (10 - (suma % 10));
        if(digito==10){
            return 0;
        }
  
  	return digito;

}

 function obtenerCodigoEstado(estado){
	codigos=new Array();

	codigos['AGS']="AS";
	codigos['BC']="BC";
	codigos['BCS']="BS";
	codigos['CAM']="CC";
	codigos['COAH']="CL";
	codigos['COL']="CM";
	codigos['CHIS']="CS";
	codigos['CHIH']="CH";
	codigos['CDMX']="DF"; //codigos['DF']="DF";
	codigos['DGO']="DG";
	codigos['GTO'] ="GT";
	codigos['GRO'] ="GR";
	codigos['HGO'] ="HG";
	codigos['JAL'] ="JC";
	codigos['MEX'] ="MC";
	codigos['MICH']="MN";
	codigos['MOR'] ="MS";
	codigos['NAY'] ="NT";
	codigos['NL']  ="NL";
	codigos['OAX'] ="OC";
	codigos['PUE'] ="PL";
	codigos['QRO'] ="QT";
	codigos['QROO']="QR";
	codigos['SLP'] ="SP";
	codigos['SIN'] ="SL";
	codigos['SON'] ="SR";
	codigos['TAB'] ="TC";
	codigos['TAM'] ="TS";
	codigos['TLAX']="TL";
	codigos['VER'] ="VZ";
	codigos['YUC'] ="YN";
	codigos['ZAC'] ="ZS";
	return codigos[estado];
}

