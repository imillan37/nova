<?php
/*
 * Este archivo contiene funciones para imprimir un boton y el codigo javascript 
 * necesario para llenar un formulario especifico con proposito de pruebas y 
 * demostraciones.
 * Julio.2012
 * Creado por Hector Ivan Patricio Moreno - S2Credit
 * 
 * 
 * Funciones disponibles:
 * 
 * imprimirScriptCompletarForm($idForm) - Imprime el codigo para llenar el formulario
 * bajo pedido, y pega el evento al boton que se imprime con la funcion 
 * imprimirBotonCompletarForm.
 * Llamarla en cabecera de documento HTML.
 * Parametros:
 * $idForm - Id del formulario para el que se desea crear la funcion
 * 
 * 
 * imprimirBotonCompletarForm() - Imprime un boton que sirve para lanzar la funcion
 * de llenado del formulario. Llamar en el lugar que se desea imprimir el boton.
 * 
 * imprimirCSSBoton() - imprime el css necesario para que el boton de la funcion
 * imprimirBotonCompletarForm se muestre correctamente. Se pueden aplicar estilos
 * independintes si se desea.
 * 
 */

$DB = &ADONewConnection(SERVIDOR);
$DB->PConnect(IP,USER,PASSWORD,$DB_EMP);  


/*
 * Parametros:
 * $idForm - ID del formulario para el que se desea crear la funcion.
 * Restricciones:
 * - Llamar dentro de un bloque <script>
 */
function imprimirScriptCompletarForm($idForm){
    
    global $DB;
    //Extraer nombre, tipo y requerimiento de los campos de la BD
     try{
    $campos= $DB->Execute("SELECT c.Nombre_campo,s.Posicion,t.Tipo,c.Obligatorio,c.Obligatorio_sistema,t.Dato_ejemplo from cat_campos_aval c 
                                JOIN cat_secc_aval s ON c.ID_secc=s.ID_secc 
                                JOIN cat_tipo_campos t ON c.ID_tipo=t.ID_tipo_campo  
                                 WHERE s.ID_form=".$idForm." AND Ajax IS NULL AND c.Visible='Y'".
                                " ORDER BY s.Posicion ASC");
    }catch(Exception $e){
        print_r($e);
    }
    
    //Crear linea de codigo que asigna un valor al campo con el nombre del campo

    echo "function llenarCampos(){ ";
    while($campo=$campos->fetchRow()){
        
            if($campo['Tipo']!='Select'&&$campo['Tipo']!='Select_array'){
                echo "$('#".$campo['Nombre_campo']."').val('".$campo['Dato_ejemplo']."');\n";}
            else{
                echo "$('#".$campo['Nombre_campo']."')[0].selectedIndex=1;\n";}
            echo "$('#".$campo['Nombre_campo']."').change();";
    }
    echo "\n}";
    
    
        
    //pegar evento click al boton
    echo "$(function(){ $('#botonCompletarForm').click(llenarCampos);} );";
    
    
}


/*
 * 
 * Imprime Boton que lanza la funcion para llenar los campos
 * 
 */
function imprimirBotonCompletarForm(){
    
    echo "<input type='button' id='botonCompletarForm' name='botonCompletarForm' value='Llenar Campos'/>";
        
}

?>
