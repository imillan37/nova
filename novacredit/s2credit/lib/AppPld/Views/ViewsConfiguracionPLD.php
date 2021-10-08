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
<script src="../JavaScipt/ViewsConfiguracionPLD.js"></script>

<link rel="stylesheet" type="text/css" class="ui" href="../Site_media/packaged/css/semantic.min.css">


<div class="ui ignored info message" style="text-align: center;"><h2>Configuración P.I.C.</h2></div>



  <div class="ui bottom blue attached active tab segment" data-tab="standard">
  	
			<table align="center" width="100%" class="ui table segment">
            	<thead>
            	<tr>
					<td width="30%">CATÁLOGOS</td>
					<td  align="center" width="70%">


							      <label>OFICIAL DE CUMPLIMIENTO</label>

                    </td>
				</tr>
                </thead>
				<tr class="positive">
					<td width="30%">LISTAS NEGRAS</td>
					<td width="70%">
                    	<div class="ui label" disabled>
                        	NO
                        </div>  
                        <div class="ui toggle checkbox">
                            <input id="Terroristas" type="checkbox" onchange="ActualizaOpcion(this);" disabled>
                            <label for="Terroristas"></label>
                        </div>
                        <div class="ui label" disabled>
                            SI
                        </div>
                    </td>
				</tr>
				<tr class="positive">
					<td>NOMBRES (P.P.E)</td>
					<td width="70%">
	                    <div class="ui label">
                        	NO
                        </div> 
                        <div class="ui toggle checkbox">
                            <input id="Nombres_PPE" type="checkbox" onchange="ActualizaOpcion(this);">
                            <label class="ui label" for="Nombres_PPE">SI</label>
                        </div>
                    </td>
				</tr>
				<tr class="positive">
					<td>PUESTOS (P.P.E)</td>
					<td width="70%">
                    	<div class="ui label">
                        	NO
                        </div> 
                       <div class="ui toggle checkbox">
                          <input id="Puestos_PPE" type="checkbox" onchange="ActualizaOpcion(this);">
                          <label class="ui label" for="Puestos_PPE">SI</label>
                        </div>                       
                    </td>
				</tr>			
				<tr class="positive">
					<td width="30%">C.P. RIESGOSOS</td>
					<td width="70%">
                    	<div class="ui label">
                        	NO
                        </div>                         
                        <div class="ui toggle checkbox">
                          <input id="CodigosPostales" type="checkbox" onchange="ActualizaOpcion(this);">
                          <label class="ui label" for="CodigosPostales">SI</label>
                        </div>                        
                    </td>
				</tr>
				<tr class="positive">
					<td width="30%">PAISES RIESGOSOS</td>
					<td width="70%">
                    	<div class="ui label">
                        	NO
                        </div>                         
                        <div class="ui toggle checkbox">
                          <input id="PaisesRiesgosos" type="checkbox" onchange="ActualizaOpcion(this);">
                          <label class="ui label" for="PaisesRiesgosos">SI</label>
                        </div>                        
                    </td>
				</tr>
				<tr class="positive">
					<td>ESTADOS RIESGOSOS</td>
                    <td width="70%">
	                    <div class="ui label">
                        	NO
                        </div>                        
                        <div class="ui toggle checkbox">
                          <input id="Estados" type="checkbox" onchange="ActualizaOpcion(this);">
                          <label class="ui label" for="Estados">SI</label>
                        </div>
                    </td>
				</tr>
				<tr class="positive">
					<td>CIUDADES RIESGOSOS</td>
                    <td width="70%">
                    	<div class="ui label">
                        	NO
                        </div> 
                        <div class="ui toggle checkbox">
                          <input id="Ciudades" type="checkbox" onchange="ActualizaOpcion(this);">
                          <label class="ui label" for="Ciudades">SI</label>
                        </div>
                    </td>
				</tr>	
				<tr class="positive">
					<td>ACTIVIDADES DE ALTO RIESGO</td>
                    <td width="70%">
                    	<div class="ui label">
                        	NO
                        </div> 
                        <div class="ui toggle checkbox">
                          <input id="Giros" type="checkbox" onchange="ActualizaOpcion(this);">
                          <label class="ui label" for="Giros">SI</label>
                        </div>
                    </td>
				</tr>	
			</table>

      <div id="GuardaCambios" class="ui mini blue submit button" name="">Guardar Cambios</div
</div>

<div class="ui red segment" align="justify">
    <p>
        Política de Identificación del Cliente (P.I.C)<br>
        Determina que criterios de riesgo evaluarán las solicitudes para que el oficial de cumplimiento determine si se valida o no al solicitante.<br>
        Si el interruptor está en "SI", las solicitudes caerán en la bandeja de SOLICITUDES RIESGOSAS. Si están en "NO", no se mostrarán en la bandeja, sin embargo, todos los criterios se evaluarán en la MATRIZ DE RIESGOS.

    </p>
</div>
<div class="ui mini modal" id="ModalErr">
	<div class="content">
    	¡Ocurrio un problema con la actualizacion!<br />Comuniquerse con S2credit
	</div>
</div>
<div class="ui mini modal" id="ModalOK">
    <div class="content">
        ¡Se ha actualizado la configuración!
    </div>
</div>