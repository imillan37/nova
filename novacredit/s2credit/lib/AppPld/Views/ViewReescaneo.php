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


<script src="../JavaScipt/ViewReescaneo.js"></script>

<div class="ui ignored info message" style="text-align: center;"><h2>Reescaneo</h2></div>


<div class="ui two column middle aligned relaxed grid basic segment">
    <div class="column">
        <div class="ui form segment">
            <div class="ui blue submit button" id="Reescaneo">Reescaneo</div>
        </div>
    </div>
    <div class="left aligned column">
        <div class="content" id="Fecha_actualizacion">
            Fecha
        </div>
    </div>
</div>



<div class="ui blue segment">

	<table class="ui table segment">
	  <thead>
	    <tr>
            <th align="center">ID Solicitud</th>
	    	<th align="center">Num Cliente</th>
            <th align="center">ID Crédito</th>
	    	<th align="center">Nombre</th>
            <th align="center">Tipo de Credito</th>
			<th align="center">Monto</th>
            <th align="center">Catálogo detectado</th>
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

