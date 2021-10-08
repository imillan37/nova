<?php
	
/**
 *
 * @author MarsVoltoso (CFA)
 * @category Views
 * @created Mon Sep 15, 2014
 * @version 1.0
 */	


include($DOCUMENT_ROOT."/rutas.php");

$db = &ADONewConnection(SERVIDOR);
$db->PConnect(IP,USER,PASSWORD,NUCLEO);

	
?>

<script src="../Site_media/Jquery/jquery-2.1.1.min.js"></script>
<script src="../Site_media/Jquery/jquery.form.js"></script>
<script src="../Site_media/packaged/javascript/semantic.js"></script>
<script src="../Site_media/Jquery/jquery.address.js"></script>
<script src="../JavaScipt/coreFunction.js"></script>
<script src="../JavaScipt/ViewsContenedorCatalogos.js"></script>


<link rel="stylesheet" type="text/css" class="ui" href="../Site_media/packaged/css/semantic.min.css">


<script src="../Plugins/js/BeatPicker.min.js"></script>
<script src="../Plugins/documents/js/prism.js"></script>
<link rel="stylesheet" href="../Plugins/css/BeatPicker.min.css"/>
<link rel="stylesheet" href="../Plugins/documents/css/demos.css"/>

<div class="ui ignored info message" style="text-align: center;"><h2>Catálogos P.L.D. </h2></div>

<div class="ui blue segment" align="left">
	
			<h4 align="center"> Tipo de Cambio y UDI'S </h4>
	
			<table align="center" width="100%" class="ui table segment">
				<tr class="positive">
					<td width="30%">DÓLARES (ESTADOS UNIDOS)</td>
					<td width="30%"><a class="ui mini blue button" id="USD">Consultar</a></td>
					<td width="40%"><a class="ui mini blue button" id="USD_act">Actualizar</a></td>
				</tr>
				<tr class="positive">
					<td>UNIDADES DE INVERSIÓN</td>
					<td><a class="ui mini blue button" id="UDIS">Consultar</a></td>
					<td><a class="ui mini blue button" id="UDIS_act">Actualizar</a></td>
				</tr>	
			</table>
			
			<h4 align="center"> Listas Negras y Personas Políticamente Expuestas </h4>
	
			<table align="center" width="100%" class="ui table segment">
				<tr class="positive">
					<td width="30%">TERRORISTAS (OFAC, ALQAIDA)</td>
					<td width="30%"><a class="ui mini blue button" id="Terrorista">Consultar</a></td>
					<td width="40%"><a class="ui mini blue button" id="Terrorista_act">Actualizar</a></td>
				</tr>
                <tr class="positive">
                    <td width="30%">PERSONAS BLOQUEADAS</td>
                    <td><a class="ui mini blue button" id="Condusef">Consultar</a></td>
                    <td><a class="ui mini blue button" id="CondusefAdd">Nuevo</a></td>
                </tr>
                <tr class="positive">
                    <td width="30%">LISTAS PROPIAS</td>
                    <td><a class="ui mini blue button" id="ListasPropias">Consultar</a></td>
                    <td><a class="ui mini blue button" id="ListasPropiasAdd">Nuevo</a></td>
                </tr>
                <tr class="positive">
                    <td width="30%">LISTAS S.A.T.</td>
                    <td><a class="ui mini blue button" id="ListasSAT">Consultar</a></td>
                    <td><a class="ui mini blue button" id="ListasSATAdd">Nuevo</a></td>
                </tr>
				<tr class="positive">
					<td>NOMBRES (P.P.E)</td>
					<td><a class="ui mini blue button" id="PPE">Consultar</a></td>
					<td><a class="ui mini blue button" id="PPEadd">Nuevo</a></td>
				</tr>
				<tr class="positive">
					<td>PUESTOS (P.P.E)</td>
					<td><a class="ui mini blue button" id="PuestosConsult">Consultar</a></td>
					<td><a class="ui mini blue button" id="PuestosAdd">Nuevo</a></td>
				</tr>	
			</table>
			
			<h4 align="center"> Clasificación de Riesgos </h4>
			
			<table align="center" width="100%" class="ui table segment">
				<tr class="positive">
					<td width="30%">C.P. RIESGOSOS</td>
					<td width="30%"><a class="ui mini blue button" id="CpConsult">Consultar</a></td>
					<td width="40%"><a class="ui mini blue button" id="CpAdd">Nuevo</a></td>
				</tr>
				<tr class="positive">
					<td width="30%">PAÍSES DE RIESGO</td>
					<td width="30%"><a class="ui mini blue button" id="PaisConsult">Consultar</a></td>
					<td width="40%"><a class="ui mini blue button" id="PaisAdd">Nuevo</a></td>
				</tr>
				<tr class="positive">
					<td>ESTADOS RIESGOSOS</td>
					<td><a class="ui mini blue button" id="EstadoRiesgoConsult">Consultar</a></td>
					<td><a class="ui mini blue button" id="EstadoRiesgoAdd">Nuevo</a></td>
				</tr>
				<tr class="positive">
					<td>CIUDADES RIESGOSAS</td>
					<td><a class="ui mini blue button" id="CiudadRiesgoConsult">Consultar</a></td>
					<td><a class="ui mini blue button" id="CiudadRiesgoAdd">Nuevo</a></td>
				</tr>	
				<tr class="positive">
					<td>ACTIVIDADES DE ALTO RIESGO</td>
					<td><a class="ui mini blue button" id="GiroRiesgoConsult">Consultar</a></td>
					<td><a class="ui mini blue button" id="GirosRiesgoAdd">Nuevo</a></td>
				</tr>
			</table>
			

</div>

<!-- MODAL -->

<div class="ui modal modalConsultaGiroRiesgo">
  <div class="content">
    <div id="ContendordivGiroRiesgo"></div>
  </div>
  <div class="actions">
    <div class="ui icon input">
    	<input placeholder="Giro" type="text" id="GiroRiesgo">
  	    <i class="search icon"></i>
    </div>
    <div class="ui mini button" id="CerrarModalVistaGiroRiesgo">
      Cerrar
    </div>
  </div>
</div>

<div class="ui modal modalConsultaCiudadRiesgo">
  <div class="content">
    <div id="ContendordivCiudadRiesgo"></div>
  </div>
  <div class="actions">
    <div class="ui icon input">
    	<input placeholder="Nombre del la Ciudad" type="text" id="CiudadRiesgo">
  	    <i class="search icon"></i>
    </div>
    <div class="ui mini button" id="CerrarModalVistaCiudadRiesgo">
      Cerrar
    </div>
  </div>
</div>

<div class="ui modal modalConsultaEstadoRiesgo">
  <div class="content">
    <div id="ContendordivEstadoRiesgo"></div>
  </div>
  <div class="actions">
    <div class="ui icon input">
    	<input placeholder="Nombre del Estado" type="text" id="EstadoRiesgo">
  	    <i class="search icon"></i>
    </div>
    <div class="ui mini button" id="CerrarModalVistaEstadoRiesgo">
      Cerrar
    </div>
  </div>
</div>

<div class="ui modal modalConsultaCp">
  <div class="content">
    <div id="ContendordivCp"></div>
  </div>
  <div class="actions">
    <div class="ui icon input">
    	<input  type="text" id="Cp" onkeypress="return SoloEnteros(event);" size="5" maxlength="5">
  	    <i class="search icon"></i>
    </div>
    <div class="ui mini button" id="CerrarModalVistaCp">
      Cerrar
    </div>
  </div>
</div>

<div class="ui modal modalConsultaCpPais">
  <div class="content">
    <div id="ContendordivCpPais"></div>
  </div>
  <div class="actions">
    <div class="ui icon input">
    	<input  type="text" id="conultaPais" size="50" maxlength="50">
  	    <i class="search icon"></i>
    </div>
    <div class="ui mini button" id="CerrarModalVistaCp">
      Cerrar
    </div>
  </div>
</div>

<div class="ui modal modalConsultaPuesto">
  <div class="content">
    <div id="ContendordivPuesto"></div>
  </div>
  <div class="actions">
    <div class="ui icon input">
    	<input placeholder="Puesto" type="text" id="BuscaPuesto">
  	    <i class="search icon"></i>
    </div>
    <div class="ui mini button" id="CerrarModalVistaPuesto">
      Cerrar
    </div>
  </div>
</div>


<div class="ui modal modalConsulta">
  <div class="content">
    <div id="Contendordiv"></div>
  </div>
  <div class="actions">
    <div class="ui icon input">
    	<input placeholder="Nombre Completo" type="text" id="BuscaRFC">
  	    <i class="search icon"></i>
    </div>
    <div class="ui mini button" id="CerrarModalVista">
      Cerrar
    </div>
  </div>
</div>

<!-- ***********RLJ ************-->
<div class="ui modal modalConsultaListaPropia">
    <div class="content">
        <div id="ContendordivListaPropia"></div>
    </div>
    <div class="actions">
        <div class="ui icon input">
            <input placeholder="Nombre Completo" type="text" id="BuscaRFClp">
            <i class="search icon"></i>
        </div>
        <div class="ui mini button" id="CerrarModalVistaListaPropia">
            Cerrar
        </div>
    </div>
</div>

<div class="ui modal modalConsultaListaSAT">
    <div class="content">
        <div id="ContendordivListaSAT"></div>
    </div>
    <div class="actions">
        <div class="ui icon input">
            <input placeholder="Nombre Completo" type="text" id="BuscaRFCSat">
            <i class="search icon"></i>
        </div>
        <div class="ui mini button" id="CerrarModalVistaListaSAT">
            Cerrar
        </div>
    </div>
</div>

<div class="ui modal modalConsultaListaCondusef">
    <div class="content">
        <div id="ContendordivListaCondusef"></div>
    </div>
    <div class="actions">
        <div class="ui icon input">
            <input placeholder="Nombre Completo" type="text" id="BuscaRFClc">
            <i class="search icon"></i>
        </div>
        <div class="ui mini button" id="CerrarModalVistaListaCondusef">
            Cerrar
        </div>
    </div>
</div>

<div class="ui modal" id="modalAddPuesto">
  <div class="content">
    	
    	<div class="ui form segment">
			<div class="field">
				<label>Puesto</label>
				<div class="ui left labeled icon input">
					<input type="text" name="Puesto" id="Puesto" placeholder="Puesto">
					<div class="ui corner label">
						<i class="icon asterisk"></i>
					</div>
				</div>
			</div>
			
			<div class="ui error message">
				<div class="header">We noticed some issues</div>
			</div>
			<div class="ui mini blue submit button" id="SubmitAddPuesto" name="">Agregar</div>	
		</div>
  
  </div>
  <div class="actions">
    <div class="ui mini button" id="CerrarModalPuesto">
      Cerrar
    </div>
  </div>
</div>

<div class="ui modal" id="modalAddCp">
  <div class="content">
    	<div class="ui form segment">
			
			<div class="field">
				<label>Códigos Postal </label>
				<div class="ui left labeled icon input">
					<input onkeypress="return SoloEnteros(event);" type="text" size="5" maxlength="5" name="CodigoPostal" id="CodigoPostal" placeholder="00000">
					<div class="ui corner label">
						<i class="icon asterisk"></i>
					</div>
				</div>
			</div>
			
			<div class="ui error message">
				<div class="header">We noticed some issues</div>
			</div>
			<div class="ui mini blue submit button" id="SubmitAddCp">Agregar</div>	
		</div>
  </div>
  <div class="actions">
    <div class="ui mini button" id="CerrarModalCp">
      Cerrar
    </div>
  </div>
</div>

<?php

    $query = "SELECT
                    cat_paises.ID_pais,
                    cat_paises.Pais,
                    pld_cat_paraisos_fiscales.motivo
               FROM cat_paises 
          LEFT JOIN pld_cat_paraisos_fiscales ON cat_paises.ID_pais = pld_cat_paraisos_fiscales.ID_Pais
              WHERE pld_cat_paraisos_fiscales.motivo IS NULL";
              
        $RESPUESTA = $db->Execute($query);  //debug($Query);
        	 
            while( !$RESPUESTA->EOF ) { 
						
			    $ID_pais = $RESPUESTA->fields["ID_pais"];
	            $Pais    = $RESPUESTA->fields["Pais"];
	            $motivo  = $RESPUESTA->fields["motivo"];
	        	 		
			        $option .= '<option value='.$ID_pais.'>'.$Pais.'</option>';
			   
			   $RESPUESTA->MoveNext(); 
			 } // fin while( !$RESPUESTA->EOF ) {
	
	
?>	

<div class="ui modal" id="modalAddPais">
  <div class="content">
    	<div class="ui form segment">
			
			<table id="ContenidoTabla" class="tblReporte" cellspacing="1" cellpadding="3" style="width:100%;">
                <tr>
                    <th id="TituloCotizador" class="titulo" style="padding: 5px; line-height: 200%;" colspan="2">ELIGE UN PAÍS</th>
                </tr>
                <tr>
                    <td valign="top" style="width:30%">País</td>
                    <td> 
	                    
	                   <select id="ID_pais" name="ID_pais">
		                   <option value=""> SELECCIONE </option>
		                   <option value="" disabled> --------------------------- </option>
		                   <?=$option?>
	                   </select>
	                    
	                </td>
                </tr>
                <tr>
                    <td valign="top" style="width:30%">Motivo</td>
                    <td> 
	                    <textarea id="motivo" name="motivo"></textarea>
	                </td>
                </tr>
                <tr>
                    <td valign="top" colspan="2" align="center">
	                    <div class="ui green button btnGuardar">Guardar</div>
	                </td>
                </tr>
			</table>    
			
		</div>
  </div>
  <div class="actions">
    <div class="ui mini button" id="CerrarModalCp">
      Cerrar
    </div>
  </div>
</div>

<div class="ui modal" id="modalAddGirosRiesgo">
  <div class="content">
    	<div class="ui form segment">
			
			<div class="field">
				<label>Actividades de Alto Riesgo</label>
				<div class="ui left labeled icon input">
					<input type="text" name="GiroNegocio" id="GiroNegocio" placeholder="Actividad Riesgosa">
					<div class="ui corner label">
						<i class="icon asterisk"></i>
					</div>
				</div>
			</div>
            <div class="field">
                <div class="ui fluid selection dropdown" id="DropRiesgo">
                    <input type="hidden" id="TipoRiesgos" name="TipoRiesgos">
                    <div class="default text">RIESGO</div>
                    <i class="dropdown icon"></i>
                    <div class="menu">
                        <div class="item" data-value="ALTO RIESGO">ALTO RIESGO</div>
                        <div class="item" data-value="BAJO RIESGO">BAJO RIESGO</div>
                    </div>
                </div>
            </div>
            <div class="field">
                <div class="ui fluid selection dropdown" id="DropEstatus">
                    <input type="hidden" id="EstatusRiesgo" name="EstatusRiesgo">
                    <div class="default text">ESTATUS</div>
                    <i class="dropdown icon"></i>
                    <div class="menu">
                        <div class="item" data-value="INACTIVO">INACTIVO</div>
                        <div class="item" data-value="ACTIVO">ACTIVO</div>
                    </div>
                </div>
            </div>
			<div class="ui error message">
				<div class="header">We noticed some issues</div>
			</div>
			<div class="ui mini blue submit button" id="SubmitAddGiros">Agregar</div>	
		</div>
  </div>
  <div class="actions">
    <div class="ui mini button" id="CerrarModalGiros">
      Cerrar
    </div>
  </div>
</div>

<div class="ui modal" id="modalAddCiudadRiesgo">
  <div class="content">
    	<div class="ui form segment">
			
			<div class="ui form segment">
				
				<div class="field">
					<label>Estados</label>
					<div class="ui fluid selection dropdown">
						<div class="text">SELECCIONE</div>
							<i class="dropdown icon"></i>
							<input name="Dos_EstadosRiesgos" id="Dos_EstadosRiesgos" type="hidden">
							<div class="menu">
								<div id="Dos_EstadoRiesgo_1" class="item" data-value="1">AGUASCALIENTES</div>
								<div id="Dos_EstadoRiesgo_2" class="item" data-value="2">BAJA CALIFORNIA</div>
								<div id="Dos_EstadoRiesgo_3" class="item" data-value="3">BAJA CALIFORNIA SUR</div>
								<div id="Dos_EstadoRiesgo_4" class="item" data-value="4">CAMPECHE</div>
								<div id="Dos_EstadoRiesgo_5" class="item" data-value="5">COAHUILA</div>
								<div id="Dos_EstadoRiesgo_6" class="item" data-value="6">COLIMA</div>
								<div id="Dos_EstadoRiesgo_7" class="item" data-value="7">CHIAPAS</div>
								<div id="Dos_EstadoRiesgo_8" class="item" data-value="8">CHIHUAHUA</div>
								<div id="Dos_EstadoRiesgo_9" class="item" data-value="9">DISTRITO FEDERAL</div>
								<div id="Dos_EstadoRiesgo_10" class="item" data-value="10">DURANGO</div>
								<div id="Dos_EstadoRiesgo_11" class="item" data-value="11">GUANAJUATO</div>
								<div id="Dos_EstadoRiesgo_12" class="item" data-value="12">GUERRERO</div>
								<div id="Dos_EstadoRiesgo_13" class="item" data-value="13">HIDALGO</div>
								<div id="Dos_EstadoRiesgo_14" class="item" data-value="14">JALISCO</div>
								<div id="Dos_EstadoRiesgo_15" class="item" data-value="15">MEXICO</div>
								<div id="Dos_EstadoRiesgo_16" class="item" data-value="16">MICHOACAN</div>
								<div id="Dos_EstadoRiesgo_17" class="item" data-value="17">MORELOS</div>
								<div id="Dos_EstadoRiesgo_18" class="item" data-value="18">NAYARIT</div>
								<div id="Dos_EstadoRiesgo_19" class="item" data-value="19">NUEVO LEON</div>
								<div id="Dos_EstadoRiesgo_20" class="item" data-value="20">OAXACA</div>
								<div id="Dos_EstadoRiesgo_21" class="item" data-value="21">PUEBLA</div>
								<div id="Dos_EstadoRiesgo_22" class="item" data-value="22">QUERETARO</div>
								<div id="Dos_EstadoRiesgo_23" class="item" data-value="23">QUINTANA ROO</div>
								<div id="Dos_EstadoRiesgo_24" class="item" data-value="24">SAN LUIS POTOSI</div>
								<div id="Dos_EstadoRiesgo_25" class="item" data-value="25">SINALOA</div>
								<div id="Dos_EstadoRiesgo_26" class="item" data-value="26">SONORA</div>
								<div id="Dos_EstadoRiesgo_27" class="item" data-value="27">TABASCO</div>
								<div id="Dos_EstadoRiesgo_28" class="item" data-value="28">TAMAULIPAS</div>
								<div id="Dos_EstadoRiesgo_29" class="item" data-value="29">TLAXCALA</div>
								<div id="Dos_EstadoRiesgo_30" class="item" data-value="30">VERACRUZ</div>
								<div id="Dos_EstadoRiesgo_31" class="item" data-value="31">YUCATAN</div>
								<div id="Dos_EstadoRiesgo_32" class="item" data-value="32">ZACATECAS</div>
							</div>
						</div>
						
				  <div class="field">
					<label>Ciudad</label>
					<div class="ui fluid selection dropdown">
						<div class="text">SELECCIONE</div>
							<i class="dropdown icon"></i>
							<input name="CiudadRiesgos" id="CiudadRiesgos" type="hidden">
							<div class="menu" id="MenuDiv">
								
							</div>
						</div>		
						
				 </div>
			</div>
			
			<div class="ui error message">
				<div class="header">We noticed some issues</div>
			</div>
			<div class="ui mini blue submit button" id="SubmitAddCiudad">Agregar</div>	
		</div>
  </div>
  <div class="actions">
    <div class="ui mini button" id="CerrarModalCiudadRiesgoso">
      Cerrar
    </div>
  </div>
</div>

<div class="ui modal" id="modalAddEstadoRiesgo">
  <div class="content">
    	<div class="ui form segment">
			
			<div class="ui form segment">
				<div class="field">
					<label>Estados</label>
					<div class="ui fluid selection dropdown">
						<div class="text">SELECCIONE</div>
							<i class="dropdown icon"></i>
							<input name="EstadosRiesgos" id="EstadosRiesgos" type="hidden">
							<div class="menu">
								<div id="EstadoRiesgo_1" class="item" data-value="1">AGUASCALIENTES</div>
								<div id="EstadoRiesgo_2" class="item" data-value="2">BAJA CALIFORNIA</div>
								<div id="EstadoRiesgo_3" class="item" data-value="3">BAJA CALIFORNIA SUR</div>
								<div id="EstadoRiesgo_4" class="item" data-value="4">CAMPECHE</div>
								<div id="EstadoRiesgo_5" class="item" data-value="5">COAHUILA</div>
								<div id="EstadoRiesgo_6" class="item" data-value="6">COLIMA</div>
								<div id="EstadoRiesgo_7" class="item" data-value="7">CHIAPAS</div>
								<div id="EstadoRiesgo_8" class="item" data-value="8">CHIHUAHUA</div>
								<div id="EstadoRiesgo_9" class="item" data-value="9">DISTRITO FEDERAL</div>
								<div id="EstadoRiesgo_10" class="item" data-value="10">DURANGO</div>
								<div id="EstadoRiesgo_11" class="item" data-value="11">GUANAJUATO</div>
								<div id="EstadoRiesgo_12" class="item" data-value="12">GUERRERO</div>
								<div id="EstadoRiesgo_13" class="item" data-value="13">HIDALGO</div>
								<div id="EstadoRiesgo_14" class="item" data-value="14">JALISCO</div>
								<div id="EstadoRiesgo_15" class="item" data-value="15">MEXICO</div>
								<div id="EstadoRiesgo_16" class="item" data-value="16">MICHOACAN</div>
								<div id="EstadoRiesgo_17" class="item" data-value="17">MORELOS</div>
								<div id="EstadoRiesgo_18" class="item" data-value="18">NAYARIT</div>
								<div id="EstadoRiesgo_19" class="item" data-value="19">NUEVO LEON</div>
								<div id="EstadoRiesgo_20" class="item" data-value="20">OAXACA</div>
								<div id="EstadoRiesgo_21" class="item" data-value="21">PUEBLA</div>
								<div id="EstadoRiesgo_22" class="item" data-value="22">QUERETARO</div>
								<div id="EstadoRiesgo_23" class="item" data-value="23">QUINTANA ROO</div>
								<div id="EstadoRiesgo_24" class="item" data-value="24">SAN LUIS POTOSI</div>
								<div id="EstadoRiesgo_25" class="item" data-value="25">SINALOA</div>
								<div id="EstadoRiesgo_26" class="item" data-value="26">SONORA</div>
								<div id="EstadoRiesgo_27" class="item" data-value="27">TABASCO</div>
								<div id="EstadoRiesgo_28" class="item" data-value="28">TAMAULIPAS</div>
								<div id="EstadoRiesgo_29" class="item" data-value="29">TLAXCALA</div>
								<div id="EstadoRiesgo_30" class="item" data-value="30">VERACRUZ</div>
								<div id="EstadoRiesgo_31" class="item" data-value="31">YUCATAN</div>
								<div id="EstadoRiesgo_32" class="item" data-value="32">ZACATECAS</div>
							</div>
						</div>
				</div>
			</div>
			
			<div class="ui error message">
				<div class="header">We noticed some issues</div>
			</div>
			<div class="ui mini blue submit button" id="SubmitAddEstado">Agregar</div>	
		</div>
  </div>
  <div class="actions">
    <div class="ui mini button" id="CerrarModalEstadoRiesgoso">
      Cerrar
    </div>
  </div>
</div>

<div class="ui modal" id="modalAdd">
  <div class="content">
    <!--//***********modificacion por RLJ para el curp**********//-->
     <form method='post' name='frmPersonaPoliticamente' id='frmPersonaPoliticamente'>	
    	<div class="ui form segment" id="FormPPF">
			<div class="field" id="divNombre">
				<label>Nombre</label>
				<div class="ui left labeled icon input">
					<input type="text" name="Nombre" id="Nombre" placeholder="Nombre">
					<div class="ui corner label">
						<i class="icon asterisk"></i>
					</div>
				</div>
			</div>
			
			<div class="field" id="divApPaterno">
				<label>Apellido paterno</label>
				<div class="ui left labeled icon input">
					<input type="text" name="ApPaterno" id="ApPaterno" placeholder="Apellido Paterno">
					<div class="ui corner label">
						<i class="icon asterisk"></i>
					</div>
				</div>
			</div>
			
			<div class="field" id="divApMaterno">
				<label>Apellido Materno</label>
				<div class="ui left labeled icon input">
					<input type="text" name="ApMaterno" id="ApMaterno" placeholder="Apellido Materno">
					<div class="ui corner label">
						<i class="icon asterisk"></i>
					</div>
				</div>
			</div>
			
			<div class="field" id="divRFC">
				<label>R.F.C.</label>
				<div class="ui left labeled icon input">
					<input type="text" name="RFC" id="RFC" placeholder="R.F.C">
					<div class="ui corner label">
						<i class="icon asterisk"></i>
					</div>
				</div>
			</div>

            <div class="field" id="divCURP">
                <label>C.U.R.P.</label>
                <div class="ui left labeled icon input">
                    <input type="text" name="CURP" id="CURP" placeholder="C.U.R.P.">
                    <div class="ui corner label">
                        <i class="icon asterisk"></i>
                    </div>
                </div>
            </div>
            <input type="hidden" name="TipoPersona" id="TipoPersona" value="">
			<div class="ui error message">
				<div class="header">We noticed some issues</div>
			</div>
			<div class="ui mini blue submit button" id="uiSibmit" name="">Agregar</div>
		</div>
      </form>	
  
  </div>
  <div class="actions">
    <div class="ui mini button" id="CerrarModal">
      Cerrar
    </div>
  </div>
</div>

<div class="ui modal" id="modalEdit">
  <div class="content">
  	   <div id="ContendordivEdit"></div>
  </div>
  <div class="actions">
    <div class="ui mini button" id="CerrarModalEdit">
      Cerrar
    </div>
  </div>
</div>

<div class="ui mini modal" id="Error">
	<div class="content" id="contentErrorArgument">
	       
	</div>	   
</div>
<div class="ui mini modal" id="ModalConfirmacion">
	<div class="content">
	       ¡Error Notifique a Soft2Be!
	</div>	   
</div>
<div class="ui mini modal" id="ModalConfirmacionYaExiste">
	<div class="content">
	      ¡El Registro Ya esta dado de alta!
	</div>
</div>
<div class="ui mini modal" id="ModalListo">
	<div class="content">
	      ¡El registro se borro con éxito!
	</div>
</div>
<div class="ui mini modal" id="ModalAgrego">
	<div class="content">
	      ¡El registro se agrego con éxito!
	</div>
</div>	
<div class="ui mini modal" id="ModalConfirmacionNoExiste">
	<div class="content">
	      ¡El Código Postal no existe!
	</div>
</div>
<div class="ui mini modal" id="ImportarManual">
	<div class="content">
    	<div>
			<b>¿De que forma quiere importar los datos?</b>
		</div>
	</div>
	  <div class="actions">
	    <div class="ui mini buttons">
			<div class="ui button Manual" id="Manual">Manual</div>
			<div class="or"></div>
			<div class="ui button Archivo" id="Archivo">Archivo</div>
		</div>
	  </div>
</div>
<div class="ui modal" id="MuestraEjemplo">
	<div class="content">
    	<div>
			<b>El archivo debe tener en la cabecera los titulos (NOMBRE PATERNO MATERNO RFC)<br />
            El archivo se debe guardar como: Texto (delimitado por tabulaciones)(*.txt)<br /><br />
            </b>
         </div>
         <div align="center">
            <img class="ui huge image" src="../Imagenes/formato.jpg">
		</div>
	</div>	
  <div class="actions">
    <div class="ui mini button" id="CerrarModalMuestraEjemplo">
      Cerrar
    </div>
  </div>
</div>
<div class="ui modal" id="ImportarArchivo">
  <div class="content">

  		<div class="content">
	  		<div id="ErroLeyenda">¡Por favor seleccione un archivo separado por tabuladores (.txt)</div>
	  	</div>
	  	
	  <form method='post' name='frmDigitalizar' id='frmDigitalizar' action='../Model/ModelContenedorCatalogos.php' enctype='multipart/form-data'>  
    	<div class="ui form segment">
		 	<div class="field">
				<div class="ui action input">
					<input type="text" id="_FileContenct" name="_FileContenct">
					<label for="FileContenct" class="ui icon button btn-file">
					 <i class="text file outline icon"></i>
					<input type="file" id="FileContenct" name="FileContenct" FullClass="No" style="display: none">
					</label>	
				</div>
			</div>
            <input type="hidden" name="TipoPersonaFile" id="TipoPersonaFile" value="">

			<div class="ui error message">
				<div class="header">We noticed some issues</div>
			</div>
			<div class="ui mini blue submit button" id="ImportarArchivoAdd">Subir</div>
			<input type="submit" id="FileContenctEnviar" name="FileContenctEnviar" style="display: none">
            <div class="ui mini blue submit button" id="VerEjemplo">Ejemplo</div>
		</div>
	  </form>	
  </div>
  <div class="actions">
    <div class="ui mini button" id="CerrarModalImportarArchivo">
      Cerrar
    </div>
  </div>
</div>


<!--************RLJ *************************-->
<div class="ui mini modal" id="ModalActualizar"> 
	<div class="ui text loader" id="Actualizando">
    	<b>Actualizando<br />¡Puede tardar algunos minutos!</b>
	</div>
</div>    

<div class="ui mini modal" id="ModalActCat"> 
	<div class="content">
	      ¡Se han actualizado los catálogos!
	</div
></div>

<div class="ui mini modal" id="ModalActCatErr"> 
	<div class="content">
    	¡Ocurrio un problema con la actualizacion!<br />Comuniquerse con S2credit
	</div
></div>


<div class="ui modal modalListadoTerrorista">
  <div class="content">
    <div id="ContendordivListadoTerrorista"></div>
  </div>
  <div class="actions">
    <div class="ui icon input">
    	<input placeholder="Nombre" type="text" id="BuscarTerrorista">
  	    <i class="search icon"></i>
    </div>
    <div class="ui small button">
      Cerrar
    </div>
  </div>
</div>

<div class="ui modal modalListadoUnidades">
  <div class="content">
 	 <table width="50%" align="center">
	        <tr>
            	<td width="33%">
                	Desde: 
    	    		<input type="text" 
                    data-beatpicker="true" 
                    data-beatpicker-position="['*','*']" 
                    data-beatpicker-format="['DD','MM','YYYY'],separator:'/'"
                    data-beatpicker-extra="customOptions1"
                    data-beatpicker-id="myDatePicker_inicial" >

	            </td>
                <td width="33%">
                	Hasta:
      	    		<input type="text" 
                    data-beatpicker="true" 
                    data-beatpicker-position="['*','*']" 
                    data-beatpicker-format="['DD','MM','YYYY'],separator:'/'"
                    data-beatpicker-extra="customOptions1"
                    data-beatpicker-id="myDatePicker_final" >
                </td>
                <td width="33%">
                <div class="ui small button" id="Filtar">
                      Filtrar
                    </div>
                </td>
            </tr>
            <input type="hidden" id="Unidades" value="" />
         </table>
    <div id="ContendordivListadoUnidades"></div>
  </div>
  <div class="actions">
    <div class="ui small button">
      Cerrar
    </div>
  </div>
</div>

  <!--************esto es para saber que lista se va a mostrar *****************-->
  <input type="hidden" name="TipoListado" id="TipoListado" value="">
  <!--******************************************-->
<!--***************esto lo agrego para leer fechas***********-->
<script src="../JavaScipt/funcionesFecha.js"></script>
<!--******************************************-->