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
<script src="../JavaScipt/ViewsContenedorLogMatrizRiesgo.js"></script>


<link rel="stylesheet" type="text/css" class="ui" href="../Site_media/packaged/css/semantic.min.css">



<div class="ui ignored info  message"><h2>Actualizaciones de la Matriz de Riesgo</h2></div>

<!--<div class="ui top attached tabular menu" align="left">
    <a class="active item" data-tab="standard" style="font-size:11px;">Log</a>
  </div>-->


  <div class="ui bottom attached blue segment active tab segment" data-tab="standard">


    <div id="TbleContent" class="ui ignored message"><!-- fondo gris-->

  	<TABLE BORDER='0' ALIGN='left' CELLSPACING=1 CELLPADDING=3  ID='small'  width='100%'>
			<TR ALIGN='center' STYLE='font-size: 14px; font-family:; color:black;' >
  			<td width="25%" align="left" > <label align="center"> Usuario que actualizó </label> </td>
				<Td width="25%" align="left"><label > Puntos mínimos </label> </Td>
				<Td width="25%" align="left"><label align="left"> Fecha de última actualización </label> </Td>
        <Td align="center"><label align="left"> Detalle </label> </Td>

			</TR>
		</TABLE><br>

    <div id="datos">
    </div>
			
      </div>



</div>



<!-- ###########################   Modals   ################################-->
<div class="ui modal " id="modalinfo">
  <div class="content">
 	 <table width="50%" align="center">
	       <tr>
            <input type="hidden" id="Unidades" value="" />
         </table>
    <div id="ContenedorInfo"></div>
  </div>
  <div class="actions">
    <div class="ui small button">
      Cerrar
    </div>
  </div>
</div>

