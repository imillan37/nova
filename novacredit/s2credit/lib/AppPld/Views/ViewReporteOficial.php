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

<script src="../JavaScipt/ViewReporteOficial.js"></script>

<link rel="stylesheet" type="text/css" class="ui" href="../Site_media/packaged/css/semantic.min.css">

<div class="ui ignored info message" style="text-align: center;"><h2>Reporte Oficial Cumplimiento</h2></div>
  <div class="ui blue segment" data-tab="standard">


<div class="ui" id="">
      <div class="content">

      <table class="ui table segment" style="width:30%">
    <thead>
       <tr>
        <th align="center">ID Solicitud</th>
        <th align="center">
          
          <div class="actions" align="left">
            <div class="ui icon input">
              <input placeholder="ID Solicitud" class="large" type="text" id="BuscarporID" style="width: 400px;" size="32">
                <i class="search inverted icon"></i>
            </div>
          </div>
          
        </th>
     </tr>
      <tr>
        <th align="center">Nombre</th>
        <th align="center">
          
          <div class="actions" align="left">
            <div class="ui icon input">
              <input placeholder="Nombre" class="large" type="text" id="BuscarTerrorista" style="width: 400px;" size="32">
                <i class="search inverted icon"></i>
            </div>
          </div>
          
        </th>
     </tr>
  
  </table>


        
    </div>

      
  </div>	
</div>

 <div class="ui blue segment" data-tab="standard">

	<div class="ui" id="">
  		<div class="content">
  			<div id="listasNegras"></div>
  		</div>
  		
	</div>
	
</div>
<br>
<!-- MODAL --> 
<div class="ui modal" id="modalInfo">
  <div class="content">
    <div id="ContenedorInfo"></div>
  </div>

  <div class="actions">
    <div class="ui mini button" id="CerrarModal">
      Cerrar
    </div>
  </div>

</div>
