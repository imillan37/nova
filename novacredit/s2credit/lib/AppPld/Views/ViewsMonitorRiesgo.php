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
<script src="../JavaScipt/ViewsMonitorRiesgo.js"></script>

<link rel="stylesheet" type="text/css" class="ui" href="../Site_media/packaged/css/semantic.min.css">

<div class="ui ignored info message" style="text-align: center;"><h2>Monitor de Riesgo</h2></div>


  <div class="ui blue segment" data-tab="standard">
	
	<table class="ui table segment" style="width:50%">
	  <thead>
	    <tr>
	    	<th align="center">Número de Cliente</th>
	    	<th align="center">
		    	<div class="ui icon input">
				  <input placeholder="Número de Client..." type="text" onkeypress="return SoloEnteros(event);" size="10" maxlength="10" id="SherchSolicitud" name="SherchSolicitud" >
				  <i class="inverted search icon"></i>
				</div>
		    </th>
	   </tr>
	    <tr>
	    	<th align="center">Nombre del cliente</th>
	    	<th align="center">
		    	<div class="ui icon input">
				  <input placeholder="Nombre del cliente..." type="text" id="SherchNombre" size="50" maxlength="50" name="SherchNombre" >
				  <i class="inverted search icon"></i>
				</div>
		    </th>
	   </tr>
	   <tr>
	    	<td align="center" colspan="2"> <a class="ui small blue button" id="ConsultarCliente">Consultar</a> </td>
	   </tr>
	</table>
</div>

<br>


<div class="ui blue segment" data-tab="standard">
	<div id="RespuestaBusqueda"></div>
	<div class="ui bottom attached tab segment active" data-tab="standard">
		<div class="ui ignored message" id="TbleContent">
			
		</div>
</div>