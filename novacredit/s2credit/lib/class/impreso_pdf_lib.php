<?

function getReciboCaja($arr){

	try{
		$pdf = new generadorPDF("Recibo de Caja.pdf");
		$pdf->llenarBuffer("recibo_pago.php");
		$pdf->reemplazarTags(
		array(
			"SUCURSAL"=>$arr["SUCURSAL"],
			"FECHA"=>$arr["FECHA"],
			"FOLIO"=>$arr["FOLIO"],
			"NO_CREDITO"=>$arr["NO_CREDITO"],
			"NOM_CLIENTE"=>$arr["NOM_CLIENTE"],
			"MONTO"=>$arr["MONTO"],
			"MONTO_LETRA"=>$arr["MONTO_LETRA"],
			"CONCEPTO"=> $arr["CONCEPTO"],
			"NOM_CAJERO"=>$arr["NOM_CAJERO"]
			));
		$pdf->renderPDF();

	}catch(Exception $e){
		echo "<h3>".$e->getMessage()."</h3>";
	}

}
function getAnexoI($arr){

	try{

		$pdf = new generadorPDF("Anexo I.pdf");
		$pdf->llenarBuffer("anexo1.php");
		$pdf->reemplazarTags(
			array(
				"nombreAcreditado"=>$arr["NOMBRE_ACREDITADO"],
				"domicilioAcreditado"=>$arr["DOMCILIO_ACREDITADO"],
				"obligadoNombre"=>$arr["OBLIGADO_NOMBRE"],
				"obligadoDireccion"=>$arr["OBLIGADO_DIRECCION"],
				"montoCredito"=>$arr["MONTO_CREDITO"],
				"CAT"=>$arr["CAT"],
				"plazo"=> $arr["PLAZO"],
				"tazaInteres"=> $arr["TAZA_INTERES"],
				"tInteresOrdAnual"=>$arr["INTERES_ORD_ANUAL"],
				"tInteresMorAnual"=> $arr["INTERES_MOR_ANUAL"],
				"comisionApP"=>$arr["COMISION_APP"],
				"comisionApC"=>$arr["COMISION_APC"],
				"cobranzaP"=>$arr["COBRANZA_P"],
				"cobranzaC"=> $arr["COBRANZA_C"],
				"seguros"=>$arr["SEGUROS"]
			));

		$pdf->renderPDF();

	}catch(Exception $e){
		echo "<h3>".$e->getMessage()."</h3>";
	}

}

function getAnexoII($arr){

	try{

		$pdf = new generadorPDF("AnexoII.pdf");
		$pdf->llenarBuffer("anexo2.php");
		$pdf->reemplazarTags(
			array(
					"nombreAcreditado"=>$arr["NOMBRE_ACREDITADO"],
					"fecha" => $arr["FECHA_LETRA"],
					"numero"=>$arr["NUMERO"],
					"plazo" =>$arr["PLAZO"],
					"tabla" =>$arr["TABLA_PLAZO"]
				)
		);

		$pdf->renderPDF();
	}

	catch(Exception $e){
		echo "<h3>".$e->getMessage()."</h3>";
	}
}

function getPagare($arr){

	try{

		$pdf = new generadorPDF("Pagare.pdf");
		$pdf->llenarBuffer("pagare.php");
		$pdf->reemplazarTags(array(
							"monto"=>$arr["MONTO"].' '.$arr["MONTO_STR"],
							"nombreSuscriptor"=>$arr["NOMBRE_ACREDITADO"],
							"fecha"=>$arr["FECHA_STR"],
							"tasaAnual"=>$arr["TASA_ANUAL"].' '.$arr["TASA_ANUAL_STR"],
							"credito"=>$arr["NO_CREDITO"],
							"direccionScrp"=>$arr["DIRECCION_ACREDITADO"]
							));

		$pdf->renderPDF();

	}catch(Exception $e){
		echo "<h3>".$e->getMessage()."</h3>";
	}
}


function getSolicitudAval($arr){

	try{

		$pdf = new generadorPDF("Solicitud de Credito Aval.pdf");
		$pdf->llenarBuffer("solicitudAval.php");
		$pdf->reemplazarTags(array(
				"NOMBRE"=>$arr["NOMBRE"],"APPATERNO"=>$arr["APPATERNO"],"APMATERNO"=>$arr["APMATERNO"],
				"DIRECCION"=>$arr["DIRECCION"],"ENTRE_CALLES"=>$arr["ENTRE_CALLES"],
				"COLONIA"=>$arr["COLONIA"],"DELEGACION"=>$arr["DELEGACION"],"ESTADO"=>$arr["ESTADO"],"CIUDAD"=>$arr["CIUDAD"],"CP"=>$arr["CP"],
				"FECHA_NACIMIENTO"=>$arr["FECHA_NACIMIENTO"],"TELEFONO_CASA"=>$arr["TELEFONO_CASA"],"TELEFONO_CONTACTO"=>$arr["TELEFONO_CONTACTO"],"CURP"=>$arr["CURP"],
				"RFC"=>$arr["RFC"],"ESTADO_CIVIL"=>$arr["ESTADO_CIVIL"],"SEXO"=>$arr["SEXO"],"REGIMEN_CONYUGAL"=>$arr["REGIMEN_CONYUGAL"],
				"DEP_ECONOMICOS"=>$arr["DEP_ECONOMICOS"],"NOMBRE_CONYUGUE"=>$arr["NOMBRE_CONYUGUE"],"HABITANTES_DOMICILIO"=>$arr["HABITANTES_DOMICILIO"],
				"IDENTIFICAION"=>$arr["IDENTIFICAION"],"E_MAIL"=>$arr["E_MAIL"],
				"HABITA_CASA"=>$arr["HABITA_CASA"],"RESIDENCIA"=>$arr["RESIDENCIA"],"NOMBRE_PROPIETARIO"=>$arr["NOMBRE_PROPIETARIO"],
				"TELEFONO_PROPIETARIO"=>$arr["TELEFONO_PROPIETARIO"],"PAGO_RENTA"=>$arr["PAGO_RENTA"],
				"EMPRESA_O_PATRON"=>$arr["EMPRESA_O_PATRON"],"TELEFONO_EMPLEO"=>$arr["TELEFONO_EMPLEO"],
				"DIRECCION_EMPRESA"=>$arr["DIRECCION_EMPRESA"],"CP_EMPRESA"=>$arr["CP_EMPRESA"],
				"PUESTO_EMPLEO"=>$arr["PUESTO_EMPLEO"],"ANTIGUEDAD_EMPRESA"=>$arr["ANTIGUEDAD_EMPRESA"],"JEFE_INMEDIATO"=>$arr["JEFE_INMEDIATO"],
				"INGRESO_MENSUAL"=>$arr["INGRESO_MENSUAL"],"DIAS_PAGO"=>$arr["DIAS_PAGO"],"OBSERVACIONES"=>$arr["OBSERVACIONES"],
				"FECHA"=>$arr["FECHA"]
							));

		$pdf->renderPDF();

	}catch(Exception $e){
		echo "<h3>".$e->getMessage()."</h3>";
	}
}


function getSolicitudCredito($arr){

	try{

		$pdf = new generadorPDF("Solicitud de Credito Titular.pdf");
		$pdf->llenarBuffer("solicitudCredito.php");
		$pdf->reemplazarTags(array(
		"FECHA"=>$arr["FECHA"], "NO_FOLIO"=>$arr["NO_FOLIO"], "NO_SOLICITUD"=>$arr["NO_SOLICITUD"],
		"ATENDIDO_POR"=>$arr["ATENDIDO_POR"], "COMO_ENTERO"=>$arr["COMO_ENTERO"],
		"MONTO_FINANCIAR"=>$arr["MONTO_FINANCIAR"], "PLAZO"=>$arr["PLAZO"], "DESTINO_PRESTAMO"=>$arr["DESTINO_PRESTAMO"],
		"NOMBRE"=>$arr["NOMBRE"], "APPATERNO"=>$arr["APPATERNO"], "APMATERNO"=>$arr["APMATERNO"],
		"DIRECCION"=>$arr["DIRECCION"], "ENTRE_CALLES"=>$arr["ENTRE_CALLES"],
		"COLONIA"=>$arr["COLONIA"], "MUNICIPIO"=>$arr["MUNICIPIO"], "ESTADO"=>$arr["ESTADO"], "CIUDAD"=>$arr["CIUDAD"], "CP"=>$arr["CP"],
		"FECHA_NACIMIENTO"=>$arr["FECHA_NACIMIENTO"], "TELEFONO_CASA"=>$arr["TELEFONO_CASA"], "TELEFONO_CONTACTO"=>$arr["TELEFONO_CONTACTO"], "CURP"=>$arr["CURP"],
		"RFC"=>$arr["RFC"], "EDO_CIVIL"=>$arr["EDO_CIVIL"], "SEXO"=>$arr["SEXO"], "REGIMEN_CONYUGAL"=>$arr["REGIMEN_CONYUGAL"],
		"DEP_ECONOMICOS"=>$arr["DEP_ECONOMICOS"], "NOMBRE_CONYUGUE"=>$arr["NOMBRE_CONYUGUE"], "PERSONAS_HABITAN"=>$arr["PERSONAS_HABITAN"],
		"IDENTIFICACION"=>$arr["IDENTIFICACION"], "CORREO_ELECTRONICO"=>$arr["CORREO_ELECTRONICO"],
		"HABITA_CASA"=>$arr["HABITA_CASA"], "TIEMPO_RESIDENCIA"=>$arr["TIEMPO_RESIDENCIA"], "PROPIETARIO_RENTERO"=>$arr["PROPIETARIO_RENTERO"],
		"TELEFONO_PROPIETARIO"=>$arr["TELEFONO_PROPIETARIO"], "RENTA"=>$arr["RENTA"],
		"NOMBRE_EMPRESA"=>$arr["NOMBRE_EMPRESA"], "TELEFONO_EMPRESA"=>$arr["TELEFONO_EMPRESA"],
		"DIRECCION_EMPRESA"=>$arr["DIRECCION_EMPRESA"], "CP_EMPRESA"=>$arr["CP_EMPRESA"],
		"PUESTO_EMPRESA"=>$arr["PUESTO_EMPRESA"], "ANTIGUEDAD_EMPRESA"=>$arr["ANTIGUEDAD_EMPRESA"], "PUESTO_EMPRESA"=>$arr["PUESTO_EMPRESA"], "JEFE_EMPRESA"=>$arr["JEFE_EMPRESA"],
		"INGRESO"=>$arr["INGRESO"], "DIAS_PAGO"=>$arr["DIAS_PAGO"],
		"CO_NOMBRE"=>$arr["CO_NOMBRE"], "CO_APPATERNO"=>$arr["CO_APPATERNO"], "CO_APMATERNO"=>$arr["CO_APMATERNO"],
		"CO_DIRECCION"=>$arr["CO_DIRECCION"], "CO_ENTRECALLES"=>$arr["CO_ENTRECALLES"],
		"CO_COLONIA"=>$arr["CO_COLONIA"], "CO_MUNICIPIO"=>$arr["CO_MUNICIPIO"], "CO_ESTADO"=>$arr["CO_ESTADO"], "CO_CIUDAD"=>$arr["CO_CIUDAD"], "CO_CP"=>$arr["CO_CP"],
		"CO_FECHA_NACIMIENTO"=>$arr["CO_FECHA_NACIMIENTO"], "CO_TELEFONO_CASA"=>$arr["CO_TELEFONO_CASA"], "CO_TELEFONO_CONTACTO"=>$arr["CO_TELEFONO_CONTACTO"], "CO_CURP"=>$arr["CO_CURP"],
		"CO_RFC"=>$arr["CO_RFC"], "CO_ACTIVIDAD"=>$arr["CO_ACTIVIDAD"], "CO_SEXO"=>$arr["CO_SEXO"], "CO_IDENTIFICACION"=>$arr["CO_IDENTIFICACION"], "CO_E_MAIL"=>$arr["CO_E_MAIL"],
		"CO_DEP_ECON"=>$arr["CO_DEP_ECON"], "CO_NOMBRE_CONYUGUE"=>$arr["CO_NOMBRE_CONYUGUE"], "CO_PERSONAS_HABITAN"=>$arr["CO_PERSONAS_HABITAN"],
		"CO_NOMBRE_EMPRESA"=>$arr["CO_NOMBRE_EMPRESA"], "CO_TELEFONO"=>$arr["CO_TELEFONO"], "CO_DIRECCION_EMPRESA"=>$arr["CO_DIRECCION_EMPRESA"], "CO_CP_EMPRESA"=>$arr["CO_CP_EMPRESA"],
		"CO_PUESTO"=>$arr["CO_PUESTO"], "CO_DEPARTAMENTO"=>$arr["CO_DEPARTAMENTO"], "CO_JEFE"=>$arr["CO_JEFE"], "CO_INGRESO"=>$arr["CO_INGRESO"], "CO_DIAS_PAGO"=>$arr["CO_DIAS_PAGO"], "CO_ANTIGUEDAD"=>$arr["CO_ANTIGUEDAD"],

		"NOMBRE_REFERENCIA1"=>$arr["NOMBRE_REFERENCIA1"], "PARENTESCO_REFERENCIA1"=>$arr["PARENTESCO_REFERENCIA1"],
		"DIRECCION_REFERENCIA1"=>$arr["DIRECCION_REFERENCIA1"],"TELEFONO_REFERENCIA1"=>$arr["TELEFONO_REFERENCIA1"],

		"NOMBRE_REFERENCIA2"=>$arr["NOMBRE_REFERENCIA2"], "PARENTESCO_REFERENCIA2"=>$arr["PARENTESCO_REFERENCIA2"],
		"DIRECCION_REFERENCIA2"=>$arr["DIRECCION_REFERENCIA2"],"TELEFONO_REFERENCIA2"=>$arr["TELEFONO_REFERENCIA2"],

		"NOMBRE_REFERENCIA3"=>$arr["NOMBRE_REFERENCIA3"], "PARENTESCO_REFERENCIA3"=>$arr["PARENTESCO_REFERENCIA3"],
		"DIRECCION_REFERENCIA3"=>$arr["DIRECCION_REFERENCIA3"],"TELEFONO_REFERENCIA3"=>$arr["TELEFONO_REFERENCIA3"],

		"NOMBRE_REFERENCIA4"=>$arr["NOMBRE_REFERENCIA4"], "PARENTESCO_REFERENCIA4"=>$arr["PARENTESCO_REFERENCIA4"],
		"DIRECCION_REFERENCIA4"=>$arr["DIRECCION_REFERENCIA4"],"TELEFONO_REFERENCIA4"=>$arr["TELEFONO_REFERENCIA4"],

		"NOMBRE_REFERENCIA5"=>$arr["NOMBRE_REFERENCIA5"], "PARENTESCO_REFERENCIA5"=>$arr["PARENTESCO_REFERENCIA5"],
		"DIRECCION_REFERENCIA5"=>$arr["DIRECCION_REFERENCIA5"],"TELEFONO_REFERENCIA5"=>$arr["TELEFONO_REFERENCIA5"],

		"NOMBRE_REFERENCIA6"=>$arr["NOMBRE_REFERENCIA6"], "PARENTESCO_REFERENCIA6"=>$arr["PARENTESCO_REFERENCIA6"],
		"DIRECCION_REFERENCIA6"=>$arr["DIRECCION_REFERENCIA6"],"TELEFONO_REFERENCIA6"=>$arr["TELEFONO_REFERENCIA6"],

		"OBSERVACIONES"=>$arr["OBSERVACIONES"]
		));

		$pdf->renderPDF();

	}catch(Exception $e){
		echo "<h3>".$e->getMessage()."</h3>";
	}
}

function getVerificacionDom($arr){

	try{

		$pdf = new generadorPDF("Verificacion Domiciliaria.pdf");
		$pdf->llenarBuffer("verificacionDomiciliaria.php");
		$pdf->reemplazarTags(array(

		"SOLICITUD"=>$arr["SOLICITUD"],"NO_CLIENTE"=>$arr["NO_CLIENTE"],
		"FECHA_SOLCITUD"=>$arr["FECHA_SOLCITUD"],"FECHA_INVESTIGACION"=>$arr["FECHA_INVESTIGACION"],
		"PROMOTOR"=>$arr["PROMOTOR"],"SUCURSAL"=>$arr["SUCURSAL"],
		"NOMBRE_ATENDIO"=>$arr["NOMBRE_ATENDIO"],"RELACION_INVESTIGADO"=>$arr["RELACION_INVESTIGADO"],
		"NOMBRE_CLIENTE"=>$arr["NOMBRE_CLIENTE"],
		"DIRECCION_CLIENTE"=>$arr["DIRECCION_CLIENTE"],
		"ENTRE_CALLES"=>$arr["ENTRE_CALLES"],
		"COLONIA"=>$arr["COLONIA"],"CP"=>$arr["CP"],"TELEFONO"=>$arr["TELEFONO"],
		"DELEGACION"=>$arr["DELEGACION"],"CIUDAD"=>$arr["CIUDAD"],
		"VIVE_DOM_S"=>$arr["VIVE_DOM_S"],"VIVE_DOM_N"=>$arr["VIVE_DOM_N"],
		"ANTI_3"=>$arr["ANTI_3"],"ANTI_2_3"=>$arr["ANTI_2_3"],"ANTI_1"=>$arr["ANTI_1"],
		"ANTI_LAB_3"=>$arr["ANTI_LAB_3"],"ANTI_LAB_2_3"=>$arr["ANTI_LAB_2_3"],"ANTI_LAB_1"=>$arr["ANTI_LAB_1"],"ANTI_LAB_NO"=>$arr["ANTI_LAB_NO"],
		"VIVIENDA_PROP"=>$arr["VIVIENDA_PROP"],"VIVIENDA_RENT"=>$arr["VIVIENDA_RENT"],"VIVIENDA_PAGO"=>$arr["VIVIENDA_PAGO"],"VIVIENDA_FAM"=>$arr["VIVIENDA_FAM"],
		"AUTO_SI"=>$arr["AUTO_SI"],"AUTO_NO"=>$arr["AUTO_NO"],
		"EDO_C_CASADO"=>$arr["EDO_C_CASADO"],"EDO_C_SOLTERO"=>$arr["EDO_C_SOLTERO"],"EDO_C_DIVOR"=>$arr["EDO_C_DIVOR"],"EDO_C_LIBRE"=>$arr["EDO_C_LIBRE"],"EDO_C_VIUDO"=>$arr["EDO_C_VIUDO"],"EDO_C_SEPARADO"=>$arr["EDO_C_SEPARADO"],
		"HIJOS_SI"=>$arr["HIJOS_SI"],"HIJOS_NO"=>$arr["HIJOS_NO"],
		"TTRABAJO_PLANTA"=>$arr["TTRABAJO_PLANTA"],"TTRABAJO_EVENT"=>$arr["TTRABAJO_EVENT"],"TTRABAJO_INDI"=>$arr["TTRABAJO_INDI"],"TTRABAJO_NO"=>$arr["TTRABAJO_NO"],
		"INT_CASA_DES"=>$arr["INT_CASA_DES"],"INT_CASA_ORD"=>$arr["INT_CASA_ORD"],"INT_CASA_NO"=>$arr["INT_CASA_NO"],
		"EDAD_18_24"=>$arr["EDAD_18_24"],"EDAD_25_45"=>$arr["EDAD_25_45"],"EDAD_46_65"=>$arr["EDAD_46_65"],
		"TCALLE_PAVIMENTO"=>$arr["TCALLE_PAVIMENTO"],"TCALLE_SNPAVIMENTO"=>$arr["TCALLE_SNPAVIMENTO"],
		"RECAMARA"=>$arr["RECAMARA"],"LAVADORA"=>$arr["LAVADORA"],
		"COMEDOR"=>$arr["COMEDOR"],"VIDEO"=>$arr["VIDEO"],
		"SALA"=>$arr["SALA"],"COMPU"=>$arr["COMPU"],
		"ESTUFA"=>$arr["ESTUFA"],"SONIDO"=>$arr["SONIDO"],
		"MW"=>$arr["MW"],"TV"=>$arr["TV"],
		"REFRI"=>$arr["REFRI"],"TINACO"=>$arr["TINACO"],
		"CALENT"=>$arr["CALENT"],"CABLE"=>$arr["CABLE"],
		"OBSERVACIONES"=>$arr["OBSERVACIONES"],
		"FECHA"=>$arr["FECHA"]

		));

		$pdf->renderPDF();

	}catch(Exception $e){
		echo "<h3>".$e->getMessage()."</h3>";
	}
}

/*function replaceChars($str){

	$str = str_replace("á","&aacute;",$str);
	$str = str_replace("é","&eacute;",$str);
	$str = str_replace("í","&iacute;",$str);
	$str = str_replace("ó","&oacute;",$str);
	$str = str_replace("ú","&uacute;",$str);
	$str = str_replace("Á","&Aacute;",$str);
	$str = str_replace("É","&Eacute;",$str);
	$str = str_replace("Í","&Iacute;",$str);
	$str = str_replace("Ó","&Oacute;",$str);
	$str = str_replace("Ú","&Uacute;",$str);
	$str = str_replace("ü","&uuml;",$str);
	$str = str_replace("Ü","&Uuml;",$str);

	return $str;
}*/
?>