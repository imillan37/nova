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


<script src="../JavaScipt/ViewReporteSolicitudes.js"></script>

<div class="ui ignored info message" style="text-align: center;"><h2>Reporte de Actualización de Clientes</h2></div>

<div class="ui blue segment">

        <table class="ui table segment" style="width:30%">
          <thead>
            <tr>
                <th align="center">Num Cliente</th>
                <th align="center">

                    <div class="ui icon input">
                      <input placeholder="Num_cliente..." type="text" onkeypress="return SoloEnteros(event);" size="10" maxlength="10" id="SherchCLiente" name="SherchCLiente" >
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
                <th align="center">Periodo</th>
                <th align="center">
                    <div class="field">
                        <div class="ui fluid selection dropdown" id="DropPeriodo">
                            <input type="hidden" id="Periodo" name="Periodo">
                            <div class="default text">SELECCIONE</div>
                            <i class="dropdown icon"></i>
                            <div class="menu">
                                <div class="item" data-value="Desactualizado">Sin Actualizar mayor a 6 meses</div>
                                <div class="item" data-value="Actualizado">Actualizados menor a 6 meses</div>
                            </div>
                        </div>
                    </div>

                </th>
            </tr>
            <tr>
                <th align="center">
                    Sucursal
                </th>
                <th align="center">
                    <div id="SucursalDrop">
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
	    	<th align="center">Num Cliente</th>
	    	<th align="center">Nombre</th>
			<th align="center">Estatus</th>
            <th align="center">Fecha ultima actualización</th>
            <th align="center"></th
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

