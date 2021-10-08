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
<script src="../JavaScipt/ViewsContenedorLogConfiguracionPIC.js"></script>


<link rel="stylesheet" type="text/css" class="ui" href="../Site_media/packaged/css/semantic.min.css">



<div class="ui ignored info  message"><h2>Historico de Actualizaciones de Configuración P.I.C. </h2></div>

<!--<div class="ui top attached tabular menu" align="left">
    <a class="active item" data-tab="standard" style="font-size:11px;">Log</a>
  </div>-->



<div class="ui blue segment">

    <div id="ContendordivHistorico"></div>
    </div>

</div>



<!-- ###########################   Modals   ################################-->
<div class="ui modal " id="modalinfo">
  <div class="content">
    <div id="ContenedorInfo">
        <div class="ui bottom blue attached active tab segment" data-tab="standard">
            <div id="Nombre_usuario"></div>
            <div id="Fecha_sistema"></div>
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
                    <td width="70%" align="center">
                        <div class="ui label" disabled>
                            NO
                        </div>
                        <div class="ui toggle checkbox">
                            <input id="Terroristas" type="checkbox" disabled>
                            <label for="Terroristas"></label>
                        </div>
                        <div class="ui label" disabled>
                            SI
                        </div>
                    </td>
                </tr>
                <tr class="positive">
                    <td>NOMBRES (P.P.E)</td>
                    <td width="70%" align="center">
                        <div class="ui label">
                            NO
                        </div>
                        <div class="ui toggle checkbox">
                            <input id="Nombres_PPE" type="checkbox" disabled>
                            <label for="Nombres_PPE"></label>
                        </div>
                        <div class="ui label" disabled>
                            SI
                        </div>
                    </td>
                </tr>
                <tr class="positive">
                    <td>PUESTOS (P.P.E)</td>
                    <td width="70%" align="center">
                        <div class="ui label">
                            NO
                        </div>
                        <div class="ui toggle checkbox">
                            <input id="Puestos_PPE" type="checkbox" disabled>
                            <label for="Puestos_PPE"></label>
                        </div>
                        <div class="ui label" disabled>
                            SI
                        </div>
                    </td>
                </tr>
                <tr class="positive">
                    <td width="30%">C.P. RIESGOSOS</td>
                    <td width="70%" align="center">
                        <div class="ui label">
                            NO
                        </div>
                        <div class="ui toggle checkbox">
                            <input id="CodigosPostales" type="checkbox" disabled>
                            <label for="CodigosPostales"></label>
                        </div>
                        <div class="ui label" disabled>
                            SI
                        </div>
                    </td>
                </tr>
                <tr class="positive">
                    <td>ESTADOS RIESGOSOS</td>
                    <td width="70%" align="center">
                        <div class="ui label">
                            NO
                        </div>
                        <div class="ui toggle checkbox">
                            <input id="Estados" type="checkbox" onchange="ActualizaOpcion(this);"disabled>
                            <label for="Estados"></label>
                        </div>
                        <div class="ui label" disabled>
                            SI
                        </div>
                    </td>
                </tr>
                <tr class="positive">
                    <td>CIUDADES RIESGOSOS</td>
                    <td width="70%" align="center">
                        <div class="ui label">
                            NO
                        </div>
                        <div class="ui toggle checkbox">
                            <input id="Ciudades" type="checkbox" disabled>
                            <label for="Ciudades"></label>
                        </div>
                        <div class="ui label" disabled>
                            SI
                        </div>
                    </td>
                </tr>
                <tr class="positive">
                    <td>ACTIVIDADES DE ALTO RIESGO</td>
                    <td width="70%" align="center">
                        <div class="ui label">
                            NO
                        </div>
                        <div class="ui toggle checkbox">
                            <input id="Giros" type="checkbox" disabled>
                            <label for="Giros"></label>
                        </div>
                        <div class="ui label" disabled>
                            SI
                        </div>
                    </td>
                </tr>
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

