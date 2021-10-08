<?php
	
/**
 *
 * @author MarsVoltoso (CFA)ViewsContenedorCatalogos.php
 * @category Views
 * @created Mon Sep 15, 2014
 * @version 1.0
 */	


include($DOCUMENT_ROOT."/rutas.php");

?>

<script src="../Site_media/Jquery/jquery-2.1.1.min.js"></script>
<script src="../Site_media/Jquery/jquery.form.js"></script>
<script src="../Site_media/packaged/javascript/semantic.js"></script>
<script src="../Site_media/Jquery/jquery.address.js"></script>
<script src="../JavaScipt/coreFunction.js"></script>


<link rel="stylesheet" type="text/css" class="ui" href="../Site_media/packaged/css/semantic.min.css">

<script src="../Plugins/js/BeatPicker.min.js"></script>
<script src="../Plugins/documents/js/prism.js"></script>
<link rel="stylesheet" href="../Plugins/css/BeatPicker.min.css"/>
<link rel="stylesheet" href="../Plugins/documents/css/demos.css"/>

<script src="../JavaScipt/ViewReporteGralOficial.js"></script>

<div class="ui ignored info message" style="text-align: center;"><h2>Historico de Solicitudes Oficial de Cumplimiento</h2></div>

<div class="ui blue segment">
	
	<table class="ui table segment" style="width:30%">
	  <thead>
	    <tr>
	    	<th align="center">ID Solicitud</th>
	    	<th align="center">
		    	
		    	<div class="ui icon input">
				  <input placeholder="ID Solicitud..." type="text" onkeypress="return SoloEnteros(event);" size="10" maxlength="10" id="SherchSolicitud" name="SherchSolicitud" >
				  <i class="inverted search icon"></i>
				</div>
                
		    	
	    	</th>
	   </tr>
	    <tr>
	    	<th align="center">Nombre</th>
	    	<th align="center">
		    	
		    	<div class="ui icon input">
				  <input placeholder="Nombre" type="text" id="SherchNombre" name="SherchNombre" >
				  <i class="inverted search icon"></i>
				</div>
                
		    	
	    	</th>
	   </tr>
        <tr>
            <th align="center">Desde</th>
            <th align="center">

                <div class="ui icon input">
                    <input type="text"
                           data-beatpicker="true"
                           data-beatpicker-position="['*','*']"
                           data-beatpicker-format="['DD','MM','YYYY'],separator:'/'"
                           data-beatpicker-extra="customOptions1"
                           data-beatpicker-id="myDatePicker_inicial" >
                </div>


            </th>
        </tr>
        <tr>
            <th align="center">Hasta</th>
            <th align="center">

                <div class="ui icon input">
                    <input type="text"
                           data-beatpicker="true"
                           data-beatpicker-position="['*','*']"
                           data-beatpicker-format="['DD','MM','YYYY'],separator:'/'"
                           data-beatpicker-extra="customOptions1"
                           data-beatpicker-id="myDatePicker_final" >
                </div>


            </th>
        </tr>
        <tr>
            <th align="center" colspan="2">
                <div class="ui button" id="Buscar">
                    Buscar
                </div></th>
        </tr>
	</table>

</div>

<div class="ui blue segment">

	<table class="ui table segment">
	  <thead>
	    <tr>
	    	<th align="center">ID Solicitud</th>
	    	<th align="center">Nombre</th>
			<th align="center">Tipo de Crédito</th>
			<th align="center">Estatus Oficial</th>
            <th align="center">Fecha</th>
			<th align="center">Comentario</th>
	   </tr>
	 </thead>
	  <tbody id="TableContent">
	  </tbody>
	</table>

</div>

<div class="ui modal modalVistaComentarios">
    <div class="content">
        <div class="ui segment" id="ContendordivVistaComentarios"></div>
    </div>
    <div class="actions">
        <div class="ui small button">
            Cerrar
        </div>
    </div>
</div>

<div class="ui segment">
    <div class="ui mini text loader" id="Cargando">Espere...</div>
</div>

<div class="ui modal modalVistaAlertas">
    <div class="content">
        <div class="ui segment" id="ContendordivAlertas"></div>
    </div>
    <div class="actions">
        <div class="ui small button">
            Cerrar
        </div>
    </div>
</div>

