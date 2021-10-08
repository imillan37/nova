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


	//verflujo();
		
?>

<script src="../Site_media/Jquery/jquery-2.1.1.min.js"></script>
<script src="../Site_media/Jquery/jquery.form.js"></script>
<script src="../Site_media/packaged/javascript/semantic.js"></script>
<script src="../Site_media/Jquery/jquery.address.js"></script>
<script src="../JavaScipt/coreFunction.js"></script>
<script src="../JavaScipt/ViewsContenedorLogActualizaciones.js"></script>


<link rel="stylesheet" type="text/css" class="ui" href="../Site_media/packaged/css/semantic.min.css">



<div class="ui ignored info  message"><h2>Actualizaciones de catálogos</h2></div>

<!--<div class="ui top attached tabular menu" align="left">
    <a class="active item" data-tab="standard" style="font-size:11px;">Log</a>
  </div>-->


<div class="ui bottom attached blue segment active tab segment">
   
   <div class="ui  message" style="width:30%"><!-- fondo gris-->
  
  <table class="ui table segment" style="width:100%">
    <thead>
      <TR ALIGN='center' STYLE='font-size: 14px; font-family:tahoma;' >
          <Td style="background:#119000;" width="20%" class="">   </TH>
          <Td><h4 align="left"> Catálogo Actualizado hace menos de 2 días</h4> </Td>
      </TR>

      <TR ALIGN='center' STYLE='font-size: 14px; font-family:tahoma; color:white;' >
          <Td style="background:#7d6c00;" width="25%" class="">    </TH>
          <Td><h4 align="left"> Catálogo Actualizado hace menos de 8 días </h4> </Td>
      </TR>

      <TR ALIGN='center' STYLE='font-size: 14px; font-family:tahoma; color:white;' >
          <Td style="background:#cd2929;" width="25%" class="error">    </TH>
          <Td><h4 align="left"> Catálogo Actualizado hace más de 8 días </h4> </Td>
      </TR>
  
  </table>
</div>
</div>


  <div class="ui bottom attached blue segment active tab segment" data-tab="standard">


    <div id="TbleContent" class="ui ignored message"><!-- fondo gris-->

  	<TABLE BORDER='0' ALIGN='left' CELLSPACING=1 CELLPADDING=3  ID='small'  width='100%'>
			<TR ALIGN='center' STYLE='font-size: 14px; font-family:; color:black;' >
  				<td width="25%" > <label align="center"> Catálogos </label> </td>
				<Td><label align="center"> Fecha de última actualización </label> </Td>
				<Td align="left"><label align="left"> Usuario que actualizó </label> </Td>

			</TR>
		</TABLE><br>
	<TABLE BORDER='0' BGCOLOR='black' ALIGN='center' CELLSPACING=1 CELLPADDING=3  ID='small' class="ui table segment" width='50%'>
			<tr ALIGN='center' STYLE='font-size: 14px; font-family:;' id="tr_dolaress">
  					<td width="25%" id="dolaress">DÓLARES (ESTADOS UNIDOS) </td>



            <td> <input id='verdolar' class='ui mini blue button' type='button' value='Detalle' name='verdolar'> </td>
  					
			</tr>
			<tr ALIGN='center' STYLE='font-size: 14px; font-family:;' id="tr_unidadess" >
  					<td id="unidadess">UNIDADES DE INVERSIÓN </td>

            <td> <input id='verunidades' class='ui mini blue button' type='BUTTON'  value='Detalle' name='verunidades'> </td>
  					
			</tr>
			<tr ALIGN='center' STYLE='font-size: 14px; font-family:;'   id="tr_terroristass">
  					<td id="terroristass">TERRORISTAS (OFAC, ALQAIDA)</td>
  					

           <td>  <input id='verterro' class='ui mini blue button' type='BUTTON'  value='Detalle' name='verterro'></td> 
			</tr>
		   
            <tr ALIGN='center' STYLE='font-size: 14px; font-family:;' id="tr_Listas_Condusef" >
                <td id="ListasCondusef">LISTAS BLOQUEADAS </td>

                <td> <input id='verlistasCondusef' class='ui mini blue button' type='BUTTON'  value='Detalle' name='verlistasCondusef'> </td>

            </tr>
            <tr ALIGN='center' STYLE='font-size: 14px; font-family:;' id="tr_Listas_Propias" >
                <td id="ListasPropias">LISTAS PROPIAS </td>

                <td> <input id='verListasPropias' class='ui mini blue button' type='BUTTON'  value='Detalle' name='verListasPropias'> </td>

            </tr>
			<tr ALIGN='center' STYLE='font-size: 14px; font-family:;'  id="tr_PPEs" >
  					<td width="30%" id="PPEs">NOMBRES (P.P.E)</td>
  					<!--
  					 <td> <input id='verListasPPE' class='ui mini blue button' type='BUTTON'  value='Detalle' name='verListasPPE'> </td>
  					--> 
			</tr>
			<tr ALIGN='center' STYLE='font-size: 14px; font-family:;'  id="tr_puestosPPEs" >
  					<td id="puestosPPEs">PUESTOS (P.P.E) </td>
  					

			</tr>
			<tr ALIGN='center' STYLE='font-size: 14px; font-family:;'  id="tr_sat" >
  					<td id="sat">SAT</td>
  					

			</tr>
       <td></td>
        <tr ALIGN='center' STYLE='font-size: 14px; font-family:;'   id="tr_puestosPPEs">
          
            
      </tr>
			<tr ALIGN='center' STYLE='font-size: 14px; font-family:;'  id="tr_CPs" >
  					<td id="CPs">C.P RIESGOSOS </td>
  					
			</tr>

			<tr ALIGN='center' STYLE='font-size: 14px; font-family:;' id="tr_estadoss"  >
  					<td id="estadoss">ESTADOS RIESGOSOS </td>
			</tr>

			<tr ALIGN='center' STYLE='font-size: 14px; font-family:;' id="tr_ciudadess"  >
  					<td id="ciudadess">CIUDADES RIESGOSOS </td>
			</tr>

			<tr ALIGN='center' STYLE='font-size: 14px; font-family:;' id="tr_giross"  >
  					<td id="giross">ACTIVIDADES DE ALTO RIESGO </td>
			</tr>
			<tr ALIGN='center' STYLE='font-size: 14px; font-family:;' id="tr_paiss"  >
  					<td id="paiss">PAÍS DE RIESGO </td>
			</tr>
	</TABLE>
			
      </div>



</div>



<!-- ###########################   Modals   ################################-->
<div class="ui modal " id="modalinfo">
  <div class="content">
 	 <table width="50%" align="center">
	       <tr>
            	<!--td width="33%">
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
            </tr>-->
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