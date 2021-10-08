<?php
	
/**
 *
 * @author Ignacio Ocampo
 * @category Views
 * @created Tue Dec 30, 2014
 * @version 1.0
 */	


include($DOCUMENT_ROOT."/rutas.php");

?>

<script src="../Site_media/Jquery/jquery.accordion.js"></script>
<script src="../Site_media/Jquery/jquery-2.1.1.min.js"></script>

<script src="../Site_media/packaged/javascript/semantic.js"></script>
<link rel="stylesheet" type="text/css" class="ui" href="../Site_media/packaged/css/semantic.min.css">

<script src="../Plugins/js/BeatPicker.min.js"></script>
<script src="../JavaScipt/coreFunction.js"></script>
<link rel="stylesheet" href="../Plugins/css/BeatPicker.min.css"/>
<script src="../JavaScipt/ViewReporteAlertas.js"></script>

<style type='text/css'>
#autocomplete {
    position:absolute;
    z-index:2;
    background-color:#FFF;
}
.row:hover {
    background-color: #F4F4F4;
    cursor: pointer;
}

.beatpicker-clear{
    background-color: #D36161;
    border: medium none;
    color: #FFFFFF;
    cursor: pointer;
    font: bold 14px arial,serif;
    margin: 3px;
    padding: 4px;
}
</style>

<div class="ui ignored info message" style="text-align: center;"><h2>Reporte de alertas PLD</h2></div>

<div class="ui blue segment">

        <table class="ui table segment" style="width:40%; min-width:300px;">
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
                <th align="center">ID crédito</th>
                <th align="center">
                    <div class="ui icon input">
                      <input placeholder="ID crédito" type="text" onkeypress="return SoloEnteros(event);" size="10" maxlength="10" id="IDCredito" name="IDCredito" >
                      <i class="inverted search icon"></i>
                    </div>
                </th>
            </tr>

            <tr>
                <th align="center" nowrap>Tipo de alerta: </th>
                <th align="center">
                    <div class="ui fluid selection dropdown">
                        <div class="text">Todas</div>
                        <i class="dropdown icon"></i>
                        <input name="tipo" id="tipo" type="hidden">
                        <div class="menu">
                            <div id="" class="item" data-value="">Todas</div>
                            <div id="" class="item" data-value="Relevante">Relevante</div>
                            <div id="" class="item" data-value="Inusual">Inusual</div>
                            <div id="" class="item" data-value="Preocupante">Preocupante</div>
                        </div>
                    </div>
                </th>
            </tr>
            <tr>
                <th align="center">Status: </th>
                <th align="center">
                    <div class="ui fluid selection dropdown">
                        <div class="text">Todas</div>
                        <i class="dropdown icon"></i>
                        <input name="status" id="status" type="hidden">
                        <div class="menu">
                            <div id="" class="item" data-value="">Todas</div>
                            <div id="" class="item" data-value="Si">Dictaminadas</div>
                            <div id="" class="item" data-value="No">No dictaminadas</div>
                        </div>
                    </div>
                </th>
            </tr>
            <tr>
                <th align="center" nowrap>Periodo de detección: </th>
                <th>
                    <div class="ui three column grid">
                        <div class="column" style="width:46%; margin-top:0;margin-bottom:0;">
                            <div class="ui mini icon input">
                                <input  id="IDFechaInicial"
                                        type="text"
                                       data-beatpicker="true"
                                       data-beatpicker-position="[0,35]"
                                       data-beatpicker-format="['DD','MM','YYYY'],separator:'/'"
                                       data-beatpicker-extra="customOptions1"
                                       data-beatpicker-id="FechaInicial"
                                       style="text-align:center;background-position: 95%;width:70%;"
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
                                            id="IDFechaFinal"
                                           data-beatpicker="true"
                                           data-beatpicker-position="[0,35]"
                                           data-beatpicker-format="['DD','MM','YYYY'],separator:'/'"
                                           data-beatpicker-extra="customOptions1"
                                           data-beatpicker-id="FechaFinal"
                                           style="text-align:center;background-position: 95%;width:70%;"
                                           placeholder="Fecha final">
                                </div>
                            </div>
                        </div>
                    </div>
                </th>
                <tr>
                <th align="center" nowrap>Periodo de movimiento: </th>
                <th>
                    <div class="ui three column grid">
                        <div class="column" style="width:46%; margin-top:0;margin-bottom:0;">
                            <div class="ui mini icon input">
                                <input type="text"
                                        id="IDFechaInicialMov"
                                       data-beatpicker="true"
                                       data-beatpicker-position="[0,35]"
                                       data-beatpicker-format="['DD','MM','YYYY'],separator:'/'"
                                       data-beatpicker-extra="customOptions1"
                                       data-beatpicker-id="FechaInicialMov"
                                       style="text-align:center;background-position: 95%;width:70%;"
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
                                            id="IDFechaFinalMov"
                                           data-beatpicker="true"
                                           data-beatpicker-position="[0,35]"
                                           data-beatpicker-format="['DD','MM','YYYY'],separator:'/'"
                                           data-beatpicker-extra="customOptions1"
                                           data-beatpicker-id="FechaFinalMov"
                                           style="text-align:center;background-position: 95%;width:70%;"
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

