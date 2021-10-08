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


<script src="../JavaScipt/ViewReescaneoLog.js"></script>

<div class="ui ignored info message" style="text-align: center;"><h2>Reescaneo Log</h2></div>


<div class="ui blue segment">

	<table class="ui table segment">
	  <thead>
	    <tr>
            <th align="center">Usuario</th>
	    	<th align="center">Fecha Reescaneo</th>
            <th align="center">Total Registros</th>
	    	<th align="center">Detalle</th>
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

<div class="ui modal " id="modalinfo">
    <div class="content">
        <div id="ContenedorInfo">
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
                    <tbody id="TableContenido">
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <div class="actions">
        <div class="ui small button">
            Cerrar
        </div>
    </div>
</div>



