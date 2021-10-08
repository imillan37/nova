<?php
	
/**
 *
 * @author Ignacio Ocampo
 * @category Views
 * @created Wed Nov 26, 2014
 * @version 1.0
 */	


include($DOCUMENT_ROOT."/rutas.php");

?>

<script src="../Site_media/Jquery/jquery.accordion.js"></script>
<script src="../Site_media/Jquery/jquery-2.1.1.min.js"></script>

<!--script src="<?=$JQuery_IU_1_9_core?>jquery-1.8.3.js"></script>
    <script src="<?=$JQuery_IU_1_9_core?>jquery-ui-1.9.2.custom.js"></script-->

<script src="../Site_media/packaged/javascript/semantic.js"></script>
<link rel="stylesheet" type="text/css" class="ui" href="../Site_media/packaged/css/semantic.min.css">

<script src="../Plugins/js/BeatPicker.min.js"></script>
<script src="../JavaScipt/coreFunction.js"></script>
<link rel="stylesheet" href="../Plugins/css/BeatPicker.min.css"/>
<script src="../JavaScipt/ViewReporteConsolidado.js"></script>

<style type='text/css'>
#autocomplete {
    position:absolute;
    z-index:2;
    background-color:#FFF;
}
</style>

<div class="ui ignored info message" style="text-align: center;"><h2>Reporte consolidado de operaciones</h2></div>

<div class="ui blue segment">

        <table class="ui table segment" style="width:35%; min-width:300px;">
          <thead>
            <tr>
                <th align="center">N&uacute;m. cliente</th>
                <th align="center">

                    <div class="ui icon input">
                      <input placeholder="N&uacute;m. cliente" type="text" onkeypress="return SoloEnteros(event);" size="10" maxlength="10" id="NumCliente" name="NumCliente" >
                      <i class="inverted search icon"></i>
                    </div>


                </th>
           </tr>
            <tr>
                <th align="center">Nombre</th>
                <th align="center">

                    <div class="ui icon input" style="width:98%;">
                      <input type="text" placeholder="Nombre" id="NombreCliente" name="NombreCliente">
                      <i class="inverted search icon"></i>
                      <div id='autocomplete'></div>
                    </div>


                </th>
           </tr>

            <tr>
                <th align="center" nowrap>Pagos recibidos del: </th>
                <th>
                    <div class="ui three column grid">
                        <div class="column" style="width:46%; margin-top:0;margin-bottom:0;">
                            <div class="ui mini icon input">
                                <input type="text"
                                       data-beatpicker="true"
                                       data-beatpicker-position="['*','*']"
                                       data-beatpicker-format="['DD','MM','YYYY'],separator:'/'"
                                       data-beatpicker-extra="customOptions1"
                                       data-beatpicker-id="FechaInicial"
                                       style="text-align:center;"
                                       placeholder="Fecha inicial">
                            </div>
                        </div>
                        <div class="column" style="width:8%; margin-top:0;margin-bottom:0;">
                            <div class="ui mini icon input">
                                <p style='text-align:center;'>al: </p>
                            </div>
                        </div>
                        <div class="column" style="width:46%;margin-top:0;margin-bottom:0;">
                            <div class="ui horizontal ">
                                <div class="ui mini icon input">
                                     <input type="text"
                                           data-beatpicker="true"
                                           data-beatpicker-position="['*','*']"
                                           data-beatpicker-format="['DD','MM','YYYY'],separator:'/'"
                                           data-beatpicker-extra="customOptions1"
                                           data-beatpicker-id="FechaFinal"
                                           style="text-align:center;"
                                           placeholder="Fecha final">
                                </div>
                            </div>
                        </div>
                    </div>
                </th>
            </tr>
            <tr>
                <td align="center" colspan="2">
                    <a class="ui small blue button" id="Consultar">Consultar</a> 
                </td>
            </tr>
        </table>
</div>

<div class="ui blue segment">
    <div id="TableContent"></div><br>
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


<div class="ui mini text loader" id="Cargando">Espere...</div>

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

