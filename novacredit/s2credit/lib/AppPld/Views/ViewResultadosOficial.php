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
<script src="../JavaScipt/ViewsResultadosOficial.js"></script>

<link rel="stylesheet" type="text/css" class="ui" href="../Site_media/packaged/css/semantic.min.css">

<div class="ui ignored info message" style="text-align: center;"><h2>Solicitudes Riesgosas</h2></div>

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
	
	</table>
</div>

<div class="ui blue segment">

	<table class="ui table segment">
	  <thead>
	    <tr>
	    	<th align="center">ID Solicitud</th>
	    	<th align="center">Nombre</th>
			<th align="center">Tipo de Crédito</th>
			<th align="center">Monto</th>
			<th align="center"></th>
			<th align="center"></th>
			<th align="center"></th>
	   </tr>
	 </thead>
	  <tbody id="TableContent">
	  </tbody>
	</table>

</div>

<div class="ui modal" id="modalCancelarSolicitud">
  <div class="content">
    	<table class="ui table segment">
	 	 <thead>
	 	 	<tr>
	    		<th align="center">¿Seguro que desea cancelar solicitud?</th>
			</tr>
		  </thead>
		  <tbody id="TableContentModalCancelar">
		 </tbody>
    	</table>		  
    </div>
    <div class="actions">
	    <div class="ui mini button" id="CerrarCancelarSolicitud">
	      Cerrar
	    </div>
    </div>
</div>

<div class="ui modal" id="modalLiberaSolicitud">
  <div class="content">
    	
    	<table class="ui table segment">
	 	 <thead>
	 	 	<tr>
	    		<th align="center">¿Seguro que desea liberar solicitud?</th>
			</tr>
		  </thead>
		  <tbody id="TableContentModalLiberar">
		 </tbody>
    	</table>		  
      
  </div>
  <div class="actions">
	    <div class="ui mini button" id="CerrarLiberaSolicitud">
	      Cerrar
	    </div>
  </div>
</div>

<div class="ui modal" id="modalAddPuesto">
  <div class="content">
    	
      <table class="ui table segment">
			  <thead>
			    <tr>
			    	<th align="center">Detalles</th>
			    </tr>
			  </thead>
		 <tbody id="TableContentModal">
		 </tbody>
	  </table>
	  <!--
	   <table class="ui table segment">
			  <thead>
			    <tr>
			    	<th align="center">Comentarios</th>
			    </tr>
			  </thead>
		 <tbody>
		 	   <tr>
		 	   	   <td align="center"><textarea id="ComentariosDichos" type="text" cols="90" rows="10" ></textarea>
			 	   </td>
		 	   </tr>	
		 </tbody>
	  </table>
  -->
  </div>
  <div class="actions">
    <div class="ui mini button" id="CerrarModalPuesto">
      Cerrar
    </div>
  </div>
</div>

<div class="ui mini modal" id="ModalConfirmacionCancel">
	<div class="content">
	       ¡La solicitud se cancelo con éxito!
	</div>	   
</div>

<div class="ui mini modal" id="ModalConfirmacion">
	<div class="content">
	       ¡La solicitud se libero con éxito!
	</div>	   
</div>

<div class="ui mini modal" id="ModalConfirmacionComent">
	<div class="content">
	       ¡El comentario se agrego con éxito!
	</div>	   
</div>

<div class="ui mini modal" id="ModalConfirmacionError">
	<div class="content">
	       ¡Error Notifique a Soft2Be!
	</div>	   
</div>

<div class="ui mini modal" id="ModalComentError">
	<div class="content">
	       ¡Los comentarios son obligatorios!
	</div>	   
</div>

