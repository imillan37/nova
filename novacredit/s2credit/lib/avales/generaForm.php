<?php
/*
 * 
 * Script para generar formulario y sus scripts necesarios a partir de la base de Datos
 * Hector Ivan Patricio Moreno
 * Junio.2012
 * 
 */



$DB = &ADONewConnection(SERVIDOR);
$DB->PConnect(IP,USER,PASSWORD,$DB_EMP);        

/*
 * Funcion que se encarga de imprimir todos los campos requeridos extrayendolos de la base de datos.
 * Recibe como parametro el formulario para imprimir.
 * 
 */
function imprimirCampos($idForm,$id=-1,$ID_Solicitud){
    
   global $DB,$ID_SUC,$ID_USR;
        //echo $ID_SUC;
    $r=$DB->Execute("SELECT Nombre,Direccion FROM sucursales WHERE ID_Sucursal=".$ID_SUC);
    $datosSucursal=$r->fetchRow();
    $r=$DB->Execute("SELECT CONCAT(Nombre,' ',AP_Paterno,' ',AP_Materno) FROM usuarios WHERE ID_User=".$ID_USR);
    $datosCapturista=$r->fetchRow();
    /*
     * Ejecucion de consultas para obtener los datos del Formulario, las secciones y los campos.
     */
   
   
   //Obtiene los datos Generales del Formulario.
   try{
    $formR=  $DB->Execute("SELECT * from cat_forms_aval WHERE ID_form=".$idForm );
    $form=$formR->fetchRow();
   }catch(exception $e){
       print_r($e);
   }
    //obtiene las secciones y sus nombres
   try{$secciones= $DB->Execute("SELECT s.ID_secc, s.Nombre,s.Nombre_mostrado, s.Posicion 
                               FROM cat_secc_aval s
                               WHERE s.ID_form=".$idForm." 
                               ORDER BY s.Posicion ASC");
     }catch(exception $e){
       print_r($e);
   }
   
  
   
   
    /*
     * Variables auxiliares 
     */
   
    //$displayNone="Style='display: none'";
    $vacio="";
    $valor="";
    $valorS="";
    $Obligatorio="";
    $clasesDeps="";
    $camposDependencias=  obtenerDependenciasAjax($idForm);
    
    //Si es una peticion de modificacion, obtiene los datos del registro a modificar.
    if($id!=-1) $datosMod= obtenerDatosModificacion($idForm,$id);
    
    //Comienza la impresion del formulario
    //echo "<h1>".$form['Nombre']."</h1>"    ;
    
    
    echo "<div id='alertasW' class='portlet'>";
    echo "<div class='portlet-header pgrande hpers'>".(($id!=-1)?"MODIFICACIÓN":"CAPTURA")." DE ".(($idForm==1)?"AVAL":"COSOLICITANTE")." DE LA SOLICITUD <span id='solw'></span></div>";
    echo "<div class='portlet-content'>";

//portlet de sucursal
    echo "<div class='alerta portlet alertaLeft' >";
    echo "<h3 class='portlet-header gris'>".$datosSucursal[0]."</h3>";
    echo "<span class='espaciador'>".$datosSucursal[1]."</span>";
    echo "</div>";//se cierra div de guardar

//portlet de fecha  

    setlocale(LC_ALL, 'es_ES'); 
    echo "<div class='alerta portlet' >";
    echo "<h3 class='portlet-header gris'> FECHA </h3>";
    echo "<span class='espaciador'>".strftime("%a %d de %B de %Y")."</span>";
    echo "</div>";//se cierra div de guardar

//portlet de sucursal
    echo "<div class='alerta portlet' >";
    echo "<h3 class='portlet-header gris'> CAPTURISTA </h3>";
    echo "<span class='espaciador'>".$datosCapturista[0]."</span>";
    echo "</div>";//se cierra div de guardar

//portlet de campos obligatorios
    echo "<div class='alerta portlet alertaLeft' >";
    echo "<h3 class='portlet-header hpers'>Campos Requeridos</h3>";
    echo "<div id='numerosObl' class='numeros portlet-content'><span id='camposLlenosAlerta' >0</span> /<span id='camposObligatoriosAlerta' ></span></div>";
    echo "</div>";//cierra div obligatorios
//portlet de acciones    
    
    echo "<div class='alerta portlet' >";
    echo "<h3 class='portlet-header hpers'>Acciones</h3>";
    echo "<input id='mandarForm' type='button' class='buttonS' style='margin-top: 15px;' value='Guardar' />";
    echo "</div>";//se cierra div de guardar


 echo "<div class='alerta portlet' >";
    echo "<h3 class='portlet-header hpers'>Alertas</h3>";
    echo "<div id='output2' class='numeros'>Ninguna</div>";
    echo "</div>";//se cierra div de guardar

    echo "</div>";//cierra div content-portlet
    echo "</div>";//cierra div Alertas
   
echo '<form method="post" id="'.$form['Nombre_HTML'].'" name="'.$form['Nombre_HTML'].'" class="formG" action="procesarForm.php">';
    
   
    /*
     * Campos auxiliares no incluidos en la tabla. De utilidad para el procesamiento.
     * 
     * tablaProcesarForm: pasa el nombre de la tabla a consultar para construir la consulta de insercion o modificacion
     */
    echo "<input type='hidden' name='form' value='".$idForm."'>";
    
    
    
    /*
     * Envia el valor identificador si se esta modificando un elemento, en otro caso envia -1 
     */
    echo "<input type='hidden' name='id' value='".$id."'>";
    
    

echo "<table icellspacing='1' style='width:100%' cellpadding='0' cellspacing='0' ><tr><td>";

echo "<table style='margin: 0 auto; width: 90%; text-align: left; margin-bottom: -2px;' cellpadding='0' cellspacing='0'><tr><td style='text-align: left;'>";     
echo "<table id='tblMenuSecciones' class='tblReporte' border='1' cellpadding='3' cellspacing='1' 
        style='width: auto; text-align: left; margin: 0;border-top-right-radius: 6px; border-top-left-radius: 6px;'>"; 
 echo   "<tr>";

while($seccion=$secciones->fetchRow()){

       echo "<th class='titulo_izquierda thTblSeccionesOpciones' 
                style='padding: 3px; line-height: 175%; cursor: pointer;' 
                onclick=\" javascript:jQuery('.tblSecciones').css('display','none');
                           jQuery('#tblSeccion".$seccion['ID_secc']."').css('display','');
                           jQuery('.thTblSeccionesOpciones').css('opacity','0.70');
                           jQuery(this).css('opacity','1');\">&nbsp;&nbsp;".$seccion['Nombre']."&nbsp;&nbsp;</th>";

    }
   echo "</tr></table>"; 
echo "</table></td></tr><tr><td>"; 
//echo "<table class='tblReporte'><tr><td>";
    $secciones->MoveFirst();
    $f=true;
    while($seccion=$secciones->fetchRow()){
     
                //Obtiene los campos de la seccion correcta.
                try{$campos= $DB->Execute("SELECT c.ID_campo,c.Nombre_campo, c.Obligatorio_sistema, c.Obligatorio, c.Visible, c.Clase,
                                                c.Html_asoc,c.Tipo,c.Etiqueta,c.Etiqueta_s2credit,c.Style,c.SQLQuery, 
                                                c.Ajax, c.Script, t.Tipo AS Tipo_c, t.Tipo_campo
                                        FROM cat_campos_aval c JOIN cat_tipo_campos t ON t.ID_tipo_campo=c.ID_tipo 
                                        WHERE c.ID_secc=".$seccion['ID_secc']." AND c.Visible='Y' 
                                        ORDER BY c.Posicion ASC");
                }catch(exception $e){
                print_r($e);
                }
                //print_r($campos);
            //imprimiendo la envoltura de la seccion, y en su caso, el titulo.

            echo "<table id='tblSeccion".$seccion['ID_secc']."' cellspacing='1' cellpadding='3' class='tblReporte tblSeccion tblSecciones' >";
           
            if($f){

                   if($ID_Solicitud)$estadoCivil=$DB->GetOne("SELECT Estado_civil from solicitud where ID_Solicitud= '$ID_Solicitud' ");

              if($estadoCivil=='CASADO'){
                echo'<tr ><td style="width: 25%;"><label for="pcAval">¿Es el conyuge el '.(($idForm==1)?"Aval":"Cosolicitante").'?</label></td>
                <td><Select id="pcAval" onchange="llenarCamposConyuge()"><option value=-1>Seleccione</option><option value="1">Si</option><option value="0">No</option></Select></td></tr>';

              }

                $f=false;

            }

            /*
             * Recorre todos los campos extraidos de la tabla indicada para imprimirlos
             */
            while($campo=$campos->fetchRow()){

                    //servia para ocultar los campos que no deben mostrarse
                    // $impresion= ($campo['Visible']=='N')?$displayNone:$vacio;


                    //sirve para poner clases en los campos obligatorios 
                    $Obligatorio= ($campo['Obligatorio']=='Y'||$campo['Obligatorio_sistema']=='Y')?"Obligatorio":$vacio;
                    if($Obligatorio!=''){ 
                        if($campo['Tipo']!="Select"&&$campo['Tipo']!="Select_array") 
                            $Obligatorio.=" ob-texto"; 
                    }
                    //Si es un campo que tiene dependientes AJAX
                    if(array_key_exists($campo['Nombre_campo'], $camposDependencias)) $clasesDeps=$camposDependencias[$campo['Nombre_campo']];
                    else $clasesDeps="";

            //Se imprimen elementos auxiliares para poder alinear los campos y tener el formulario bien formado.
                    echo'<tr ><td style="width: 25%;"><label for="'.$campo['Nombre_campo'].'">'.$campo['Etiqueta'].'</label></td> <td>';



                    //En caso de Modificacion, asigna el valor del campo obtenido a una cadena que se imprimira          
                    if($id!=-1) {
                        $valor="value='".$datosMod[$campo['Nombre_campo']]."'";
                        $valorS=$datosMod[$campo['Nombre_campo']];
                    }

                    /*
                        * Sirve para imprimir el tipo de campo correcto, si se definen nuevos campos, este es el lugar.
                        */
                    switch($campo['Tipo']){


                        /********************************************************************************************************************************************
                            ***************************************************Campo de Texto***************************************************************************
                            ********************************************************************************************************************************************/

                        case 'Text':
                                              
                                        echo '<input '.$campo['Html_asoc'].' type="text" name="'.$campo['Nombre_campo'].'" id="'.$campo['Nombre_campo'].'"  '.$valor.' class="'.$campo['Clase']." ".$Obligatorio.' '.$clasesDeps.' " size=20 /></td></tr>';
                                        break;


                            /********************************************************************************************************************************************
                            ******************************************************Text Area*****************************************************************************
                            ********************************************************************************************************************************************/            

                        case 'Textarea':
                                                     
                                        echo '<textarea id="'.$campo['Nombre_campo'].'" name="'.$campo['Nombre_campo'].'" '.$valor.'  class="'.$campo['Clase']." ".$Obligatorio.' '.$clasesDeps.'"  ></textarea></td></tr>';


                        /********************************************************************************************************************************************
                            **************************************************Select construido por consulta o AJAX*****************************************************
                            *********************************************************************************************************************************************/                       
                        case 'Select':
                                                      
                                        echo '<select name="'.$campo['Nombre_campo'].'" id="'.$campo['Nombre_campo'].'"   class="'.$campo['Clase']." ".$Obligatorio.' '.$clasesDeps.'" >';

                                        echo $campo['Html_asoc'];

                                        if($campo['SQLQuery']!=NULL && $campo['Ajax']==NULL){

                                                        $opciones=  $DB->Execute($campo['SQLQuery']);

                                                        while($opcion=  $opciones->fetchRow()){

                                                                            echo '<option value= "'.$opcion[0].'"  '.(($valorS==$opcion[0])?"selected":"").' >'.$opcion[1]."</option>";

                                                        }//end while interno
                                        }// end if
                                        else{   //echo "hello";
                                                 if($id!=-1){
                                                                $query=$campo['SQLQuery'];
                                                                $deps=explode('|',$campo['Ajax']);
                                                                    
  
                                                                            foreach($deps as $dep){
                                                                                //echo '#'.$dep.'#';

                                                                                if($dep!='Estado_actual'&&$dep!='Ciudad'&&$dep!='Poblacion'&&$dep!='Colonia')
                                                                                $query=preg_replace('/#'.$dep.'#/', $datosMod[$dep], $query);
                                                                            else{

                                                                                 if($dep=='Estado_actual') {$t='estados'; $pk='ID_Estado'; $v='Nombre';}
                                                                                elseif($dep='Poblacion') {$t='municipios'; $pk='ID_Municipio'; $v='Nombre';}
                                                                                elseif($campo['Nombre_tabla']='Colonia') {$t='codigos_postales'; $pk='ID_Colonia';$v='Colonia';}
                                                                                
                                                                                $valorReal=$DB->GetOne("SELECT $pk from $t WHERE $v='".$datosMod[$dep]."'");
                                                                                //echo "valor real ".$valorReal;
                                                                                $query=preg_replace('/#'.$dep.'#/', $valorReal, $query);
                                                                                //$valorS.="||".$valorReal;
                                                                                echo "<br />$valorS";
                                                                            }
                                                                                
                                                                            }
                                                                
                                                                if($campo['Tipo']=='Select'){

                                                                $resultado=  $DB->Execute($query);  

                                                                    
                                                                        
                                                                while($r= $resultado->fetchRow()){
                                                                    
                                                                    echo "<option data-id='".$r[0]."' value='".$r[1]."||".$r[0]."' ".(($valorS==$r[1])?"selected":"")." >".$r[1]."</option>";
                                                                    
                                                                    }    
                                                                    
                                                                }


                                        } //end if modificacion
                                    }//end else
                                        echo'</select></td></tr>';
                                        break;

                            /********************************************************************************************************************************************
                            *************************************************Select Estatico****************************************************************************
                            ********************************************************************************************************************************************/
                        case 'Select_array':
                                                           
                                        echo '<select name="'.$campo['Nombre_campo'].'" id="'.$campo['Nombre_campo'].'"  class="'.$campo['Clase']." ".$Obligatorio.' '.$clasesDeps.'" >';
                                        echo $campo['Html_asoc'];
                                        $opciones=explode('|',$campo['SQLQuery']);
                                                for($i=0;$i<count($opciones);$i++){
                                                    $opcion=explode('=',$opciones[$i]);
                                                    echo '<option value="'.$opcion[1].'" '.(($valorS==$opcion[1])?"selected='selected'":"").'>'.$opcion[0].'</option>';  
                                                }

                                        echo'</select></td></tr>';
                                        break;


                        /********************************************************************************************************************************************
                        ****************************************************Campo de Fecha**************************************************************************
                        ********************************************************************************************************************************************/

                        case 'Date':
                                                         
                                        $valor_CF='';    
                                        if($id!=-1){
                                            $aFecha=explode("-",$valorS);
                                            $valor_CF=$aFecha[2]."-".$aFecha[1]."-".$aFecha[0];
                                        }
                                        echo '<input type="text" name="'.$campo['Nombre_campo'].'" id="'.$campo['Nombre_campo'].'" readOnly="true" value="'.$valor_CF.'" class="datepicker '.$campo['Clase']." ".$Obligatorio.' '.$clasesDeps.'" size=20 /></td></tr>';
                                        break;

                    }//end switch
                   // echo '</tr>';
                } //end while campos

        echo "</table>"; //fin de envoltura de seccion
    } //end While secciones
    
        //echo "</td></tr></table>"; //se cierra contenedor de pestanas 
    
    echo "</td></tr></table>"; //se cierra contenedor del form
    
    
    echo "</form>";
    
    
    
};


/*  
 * Sirve para imprimir los scripts definidos por los campos y ademas los necesarios para AJAX.
 * Esta funcion se debe llamar en la cabecera del archivo donde se imprimiran el form.
 * Recibe el nombre de la tabla de la que extraer los campos y opcionalmente el id del registro a modificar.
 */


function imprimirScripts($idForm,$id=-1,$ID_Solicitud){
    
   global $DB;
    
    
    try{
    //Elementos modificables dependiendo de otros campos.
    $ajaxElements= $DB->Execute("SELECT c.*,s.Nombre,s.Posicion from cat_campos_aval c JOIN cat_secc_aval s ON c.ID_secc=s.ID_secc
                                 WHERE s.ID_form=".$idForm." AND Ajax IS NOT NULL AND c.Visible='Y'".
                                " ORDER BY s.Posicion ASC");
    }catch(Exception $e){
        print_r($e);
    }
    try{
    //Elementos que  necesitan javascript en la cabecera para funcionar.
    $scriptElements= $DB->Execute("SELECT c.*,s.Nombre,s.Posicion from cat_campos_aval c JOIN cat_secc_aval s ON c.ID_secc=s.ID_secc
                                   WHERE s.ID_form=".$idForm." AND c.Script IS NOT NULL AND c.SQLQuery!='*' AND c.Visible='Y'".
                                 " ORDER BY s.Posicion ASC");
    }catch(Exception $e){
        print_r($e);
    }
    try{
    //Elementos que son obligatorios.
    $mandatoryElements= $DB->Execute("SELECT c.*,s.Nombre,s.Posicion from cat_campos_aval c JOIN cat_secc_aval s ON c.ID_secc=s.ID_secc
                                   WHERE s.ID_form=".$idForm." AND c.Obligatorio='Y' AND c.Visible='Y'".
                                 " ORDER BY s.Posicion ASC");
    
    }catch(Exception $e){
        print_r($e);
    }

    try{
    $formR=  $DB->GetOne("SELECT Nombre_HTML from cat_forms_aval WHERE ID_form=".$idForm );
   }catch(exception $e){
       print_r($e);
   }


    /********************************************************************************************************************************************
     ****************************************************Comienza funcion onReady****************************************************************
     ********************************************************************************************************************************************/
    
echo "$(function(){\n";


        /*Estilizando tabs*/
        echo "jQuery('#tblMenuSecciones').find('th').first().css('border-top-left-radius','6px').click();
        jQuery('#tblMenuSecciones').find('th').last().css('border-top-right-radius','6px');                     
        jQuery('.thTblSeccionesOpciones').css('opacity','0.70');
        jQuery('#tblMenuSecciones').find('th').first().css('opacity','1');";


        
        //echo "$('#tabbedForm').tabs();\n";
    
    if($id!=-1){ 
            $fMod="";
            $datosMod=  obtenerDatosModificacion($idForm, $id);
        }
    
    while ($ajax=  $ajaxElements->fetchRow()){

        $dependencias=explode('|',$ajax['Ajax']);
                        
                echo "$('.Dependencia_".$ajax['Nombre_campo']."').bind('change',funcionCambio".$ajax['Nombre_campo'].");\n";

             if($id!=-1){ 

                if( strpos( $fMod,"$('.Dependencia_".$ajax['Nombre_campo']."').each(function(){ $(this).change();});\n" )===false) 
                        
                       $fMod.="$('.Dependencia_".$ajax['Nombre_campo']."').each(function(){ $(this).change();});\n";

            } 



    }//end While ajaxElements
        
    while ($scriptElement= $scriptElements->fetchRow()){

          echo "\n".$scriptElement['Script'];

    } //end While scriptElements
        
    
    
        if($mandatoryElements->RecordCount()!=0){ 
                    
            
                    //echo "\n$('.formG').bind('submit',validarForm);\n";
            
                    
            }
            
        //
        echo "var options={beforeSubmit: validarForm,success: function(data){alert(data);
                var doc=$(parent.document.getElementById('lateralPanel'));
                    
                    ".(($idForm==1)?" 
                        $.post('ajax/refreshInterface.php',{\"ID_Solicitud\": '$ID_Solicitud',\"tipo\":\"AVAL\"},function(datos){
                            //var datos= $.parseJSON(datos);
                        doc.find('#AVAL_CAPTURA').attr('class',datos.classCaptura);
                        doc.find('#AVAL_EDITA').attr('class',datos.classEdita);
                        doc.find('#AVAL_VISTA').attr('class',datos.classVista);

                        doc.find('#AVAL_CAPTURA').addClass('OPTIONS_2');
                        doc.find('#AVAL_EDITA').addClass('OPTIONS_2');
                        doc.find('#AVAL_VISTA').addClass('OPTIONS_2');


                        doc.find('#AVAL_CAPTURA').attr('lang',datos.langCaptura);
                        doc.find('#AVAL_EDITA').attr('lang',datos.langEdita);
                        doc.find('#AVAL_VISTA').attr('lang',datos.langVista);
                    }
                        ,'json')//se cierra post;

                    ":"
                        $.post('ajax/refreshInterface.php',{\"ID_Solicitud\": '$ID_Solicitud',\"tipo\":\"COSOL\"},function(datos){
                        //var datos= $.parseJSON(datos);
                        

                        doc.find('#COSOL_CAPTURA').attr('class',datos.classCaptura);
                        doc.find('#COSOL_EDITA').attr('class',datos.classEdita);
                        doc.find('#COSOL_VISTA').attr('class',datos.classVista);

                        doc.find('#COSOL_CAPTURA').addClass('OPTIONS_5');
                        doc.find('#COSOL_EDITA').addClass('OPTIONS_5');
                        doc.find('#COSOL_VISTA').addClass('OPTIONS_5');

                        doc.find('#COSOL_CAPTURA').attr('lang',datos.langCaptura);
                        doc.find('#COSOL_EDITA').attr('lang',datos.langEdita);
                        doc.find('#COSOL_VISTA').attr('lang',datos.langVista);
                    }
                        ,'json')//se cierra post;

                        ")."

                } 


        };\n";
        //echo "$('.formG').unbind('submit');";
        echo "$('.formG').live('submit',
                function(){\n

                    //event.preventDefault();\n
                    //alert ('Enviado');\n
                    $(this).ajaxSubmit(options);\n
                    
                    return false;\n

            }
            );";

         echo "$('#mandarForm').bind('click',function(){ $('#".$formR."').submit();});";



        /*
         * Llenar alertas;
         */


        
        echo "$('.Obligatorio').bind('change',contarObligatorios);\n";
        echo "var camposObl=$('.Obligatorio').length;\n";
        echo "$('#camposObligatoriosAlerta').html(camposObl);\n";
        echo "contarObligatorios();\n";
        
       if($id!=-1){ //echo "\n RefrescarAjax(); \n";
   }
        echo " });\n"; //termina onReady
        
       /********************************************************************************************************************************************
        ****************************************************Comienza escritura de funciones*********************************************************
        ********************************************************************************************************************************************/
        
        
        
        
        
        

           $in="\t";
           $in2=$in."\t";
        
        echo "function contarObligatorios(event){\n";

            echo $in."var campos=$('.Obligatorio');\n";
            echo $in."var llenos=0;\n";
            echo $in."campos.each(function(){\n";
                echo $in2."var campo=$(this);\n";
                echo $in2."if(validarCampo(campo)){\n";
                echo $in2."llenos++;";
                echo $in2."campo.addClass('normalInput');";
                echo $in2."}else{campo.removeClass('normalInput')}//end if  \n";
                
           echo $in."\t });\n";
            
            echo "$('#camposLlenosAlerta').html(llenos);\n";
            echo "if(llenos!=$('.Obligatorio').length){\n";
            echo "$('#numerosObl').css('color', '#ee0011');\n";
            
            echo "}else{ $('#numerosObl').css('color', '#00dd11'); }\n";
        echo "}\n"; //termina funcion validarCampos();
        
        
        
        
        
        echo "function validarForm(){\n";
        echo "var validez=true;\n";
        
      //echo "alert('llamado a validar');";
            echo $in."var campos=$('.Obligatorio');\n";
            echo $in."campos.each(function(){\n";
                echo $in2."var campo=$(this);\n";
                echo $in2."if(!validarCampo(campo)){\n";
                    echo $in2."marcarCampoError(campo);\n";
                    echo $in2."validez=false; \n";
                echo $in2."}//end if  \n";
                echo $in2."else if(campo.hasClass('error') ) desmarcarCampo(campo);\n";
           echo $in."\t });\n";//fin checar textos
         
   
        echo "return validez;\n";
        echo "}\n"; //termina funcion validarCampos();
        
        
        
echo $in."function validarCampo(campo){\n";
        
        
        echo $in2."var etiqueta=campo.get(0).tagName;";
        $in3=$in2."\t";
        //echo "console.log(etiqueta);";
        echo $in2."switch(etiqueta){\n";
                echo $in3."case 'INPUT':\n";
                echo "var val=campo.val();\n";
                    echo $in3."if(campo.val()==''){\n";
                                echo $in3."return false;\n";
                    echo $in3."}//end if  \n";
                    
                    echo $in3."else if(campo.hasClass('datepicker')&&(val.search(/^(0?[1-9]|[12][0-9]|3[01])[\-](0?[1-9]|1[012])[\-]\d{4}$/)==-1))";
                    echo $in3."return false;\n";
                    echo "break;\n";
                echo $in3."case 'SELECT':\n";
                    echo $in3."if(campo.val()==-1){\n";
                                echo "return false;\n";
                    echo $in2."}//end if  \n";
                    echo "break;\n";
                           
            echo $in2."\t }\n";//fin switch
         
         echo $in2."return true;\n"; //si salio vivo del switch, regresar verdadero.   
            
         echo $in."\t }\n";//fin funcion
        
        
        
        
        
        /*
         * Funcion que se encarga de mostrar los campos no llenados visualmente en javascript
         * Recibe el campo a marcar como error.
         */
        
       echo "function marcarCampoError(campo){\n";
       
                    echo "var etiqueta=campo.get(0).tagName;";
                    echo "var tipo=(etiqueta=='SELECT')?'select':'text';\n";
                    echo "if(campo.hasClass('datepicker')) tipo='date';\n";
                    echo "campo.addClass('error');\n";
                    echo "campo.css('border','1px solid #E00' );\n";
                    echo "campo.css('border-radius','5px' );\n";
                    
                    echo "switch(tipo){\n";
                            echo "case 'text':\n";
                            echo "mensaje='Este campo no puede quedar vacío'; \n";
                            echo  "break;\n";
                            
                            echo "case 'select':\n";
                            echo "mensaje='Debe seleccionar un valor'; \n";    
                            echo  "break;\n";
                            
                            echo "case 'date':\n";
                            echo "mensaje='Debe seleccionar una fecha valida';\n";    
                            echo  "break;\n";
                    echo "}\n";     //fin switch
            
        
        echo "if(campo.parent().children('.msjError').length==0) campo.parent().append('<span class=\"msjError\" style=\"color: red ; display: inline-block\">'+mensaje+'</span>');\n";
        echo "}\n";//fin de funcion que marca el error
        
        /*
         * Funcion que quita la muestra del mensaje de error en campos corregidos
         * Recibe el campo a limpiar.
         */
        echo "function desmarcarCampo(campo){\n";
        echo "campo.removeClass('error');\n";
        echo "  campo.css('border','2px solid #999');\n";
        echo "  campo.css('border-radius','5px');\n";
        echo "  campo.parent().children('.msjError').remove();\n";
        echo "}\n";//fin de funcion que marca el error
        







        // En caso de ser modificacion, imprime las funciones necesarias para actualizar los campos con AJAX;
        
        if($id!=-1){
            echo "function RefrescarAjax(){\n";
            
            echo $fMod;
            
            echo "}\n";
        }
        
        
        //resetear el array de elementos AJAX
         $ajaxElements->MoveFirst();
        
        
        // Imprime las funciones AJAX, lanzadas por los eventos change y Change
        
        while ($ajax=  $ajaxElements->fetchRow()){
             
                    $dependencias=explode('|',$ajax['Ajax']);

                    echo "function funcionCambio".$ajax['Nombre_campo']."(){\n";
                    echo "var validez=true;\n";
                    echo "valores={'campo':'".$ajax['ID_campo']."'};\n";
                      
                    
                    echo "var dependencias=$('.Dependencia_".$ajax['Nombre_campo'].".Obligatorio');\n";
                    echo "dependencias.each(function(){ \n";
                        echo "if(!validarCampo($(this))){ \n ";
                        echo "validez=validez&&false;\n";
                        echo "}\n"; 
                        
                         echo "else{  if($(this).attr('name')!='Estado_actual'){valores[$(this).attr('name')]=$(this).val();\n}";

                         echo "else { var des=$(this).val().split('||'); console.log(des[0]); valores[$(this).attr('name')]=des[1];}}";
                        
                        
                    echo"});";//fin each

                     
                     echo "if(validez){\n";
                     
                     if($ajax['SQLQuery']!='*'){
                            echo "console.log(valores);";
                            echo "$.post('ajax/ajaxForms.php',valores, function(result){\n";
                            echo "$('#".$ajax['Nombre_campo']."').html(result);\n";
                            
                            if($id!=-1){ 
                                   /* echo "console.log('algo:".$datosMod[$ajax['Nombre_campo']]."');";
                                    echo "$('.Dependencia_".$ajax['Nombre_campo']."').each(function(){ console.log('cambiado');$(this).change();});\n";
                                    echo "$('#".$ajax['Nombre_campo']."').val('".$datosMod[$ajax['Nombre_campo']]."');\n";
                                    echo "console.log($('#".$ajax['Nombre_campo']."').val());";
                                    echo "$('#".$ajax['Nombre_campo']."').change();\n";
                                    echo "$('#".$ajax['Nombre_campo']."').change();\n";
                                    echo "$('#".$ajax['Nombre_campo']."').change();\n";*/
                            }
                            
                            
                            echo  "});\n";
                            
                            echo "}\n";
                            
                     echo "else { $('#".$ajax['Nombre_campo']."').html('".$ajax['Html_asoc']."'); }\n";
                     }    
                     
                     else{
                         foreach($dependencias as $dep){
                                              echo "var ".$dep."=$('#".$dep."').val();";
                     
                         }
                         
                         $scripts=explode("|||",$ajax['Script']);
                         echo "\n".$scripts[0]."\n";
                         echo "var val=".$scripts[1]." ;\n";
                         echo "$('#".$ajax['Nombre_campo']."').val(val); \n }//end if campos\n";
                         echo "else { $('#".$ajax['Nombre_campo']."').val('') }";                    
                     }
                    
                   echo "} \n";
                     
                      

        }// end while funcionesCambio


      /*****************************************************************************************************
      Impresion del Script para llenar los campos del aval en caso de que sea el conyuge.
      ******************************************************************************************************/
      if($ID_Solicitud)
        {
            $estadoCivil=$DB->GetOne("SELECT Estado_civil from solicitud where ID_Solicitud= '$ID_Solicitud' ");

              if($estadoCivil=='CASADO'){
              echo "function llenarCamposConyuge(){ if($('#pcAval').val()==1){";

                            //obener datos del conyuge
                $r=$DB->Execute("SELECT Nombre_conyuge                      AS Nombre,
                                        NombreI_conyuge                     AS Nombre1,
                                        Ap_paterno_conyuge                  AS Ap_paterno,
                                        Ap_materno_conyuge                  AS Ap_materno,
                                        SEXO_conyuge                        AS Sexo,
                                        Fecha_nacimiento_conyuge            AS Fecha_nac,
                                        RFC_conyuge                         AS Rfc,
                                        Hclave_conyuge                      AS Homoclave,
                                        Telefono_casa_conyuge               AS Telefono,
                                        Telefono_contacto_conyuge           AS Telefono_oficina,
                                        Actividad_conyuge                   AS Actividad_Economica,
                                        Ingresos_conyuge                    AS Ingresos,
                                        CP_conyuge                          AS CP,
                                        Colonia_conyuge                     AS Colonia,
                                        Estado_conyuge                      AS Estado_actual,
                                        Ciudad_conyuge                      AS Ciudad,
                                        Poblacion_conyuge                   AS Poblacion,
                                        Calle_conyuge                       AS Calle,
                                        Num_conyuge                         AS Num_exterior,
                                        Interior_conyuge                    AS Num_interior,
                                        Ecalles_conyuge                     AS entre_calle1,
                                        Ecalles_conyugeII                   AS entre_calle2,
                                        Empresa_conyugue                    AS Empresa,
                                        Puesto_conyugue                     AS Puesto
                                    FROM solicitud 
                                            WHERE ID_Solicitud='".$ID_Solicitud."' ");

                $dConyuge=$r->fetchRow();

                            //obtener campos a llenar y llenarlos
                try{
                $campos= $DB->Execute("SELECT c.Nombre_campo,c.Tipo AS Tipo,s.Posicion,t.Tipo AS t2,c.SQLQuery,c.Obligatorio,c.Obligatorio_sistema,c.Ajax from cat_campos_aval c 
                                JOIN cat_secc_aval s ON c.ID_secc=s.ID_secc 
                                JOIN cat_tipo_campos t ON c.ID_tipo=t.ID_tipo_campo  
                                 WHERE s.ID_form=".$idForm." AND c.Visible='Y'".
                                " ORDER BY s.Posicion ASC");
                }catch(Exception $e){
                print_r($e);
                }

                             while($campo=$campos->fetchRow()){
                    
                                    if( !($campo['Tipo']=='Select'&&$campo['Ajax']!='') ){
                                            if($campo['Tipo']=='Date'){ $aFecha=explode("-",$dConyuge[$campo['Nombre_campo']]);
                                            $dConyuge[$campo['Nombre_campo']]=$aFecha[2]."-".$aFecha[1]."-".$aFecha[0];}
                                            echo "$('#".$campo['Nombre_campo']."').val('".$dConyuge[$campo['Nombre_campo']]."');\n";
                                    }
                                    else{   

                                                                $query=$campo['SQLQuery'];
                                                                $deps=explode('|',$campo['Ajax']);
                                                                    
  
                                                                            foreach($deps as $dep){
                                                                                //echo '#'.$dep.'#';

                                                                                if($dep!='Estado_actual'&&$dep!='Ciudad'&&$dep!='Poblacion'&&$dep!='Colonia')
                                                                                $query=preg_replace('/#'.$dep.'#/',"'".$dConyuge[$dep]."'", $query);
                                                                            else{

                                                                                 if($dep=='Estado_actual') {$t='estados'; $pk='ID_Estado'; $v='Nombre';}
                                                                                elseif($dep='Poblacion') {$t='municipios'; $pk='ID_Municipio'; $v='Nombre';}
                                                                                elseif($campo['Nombre_tabla']='Colonia') {$t='codigos_postales'; $pk='ID_Colonia';$v='Colonia';}
                                                                                
                                                                                $valorReal=$DB->GetOne("SELECT $pk from $t WHERE $v='".$dConyuge[$dep]."'");
                                                                                //echo "valor real ".$valorReal;
                                                                                $query=preg_replace('/#'.$dep.'#/', "'".$valorReal."'", $query);
                                                                                //$valorS.="||".$valorReal;
                                                                               //echo "<br />$valorS";
                                                                            }
                                                                                
                                                                            }
                                                                
                                                                if($campo['Tipo']=='Select'){

                                                                $resultado=  $DB->Execute($query);  

                                                                    
                                                                $html='';
                                                                while($r= $resultado->fetchRow()){
                                                                    
                                                                    $html.="<option data-id='".$r[0]."' value='".$r[1]."||".$r[0]."' ".(($valorS==$r[1])?"selected":"")." >".$r[1]."</option>";
                                                                    
                                                                    }    
                                                                    
                                                                    }
                                                                echo "$('#".$campo['Nombre_campo']."').html(\"".$html."\");\n";
                                        }
                                         
                                       echo "$('#".$campo['Nombre_campo']."').change();";
                                      
                            }

             echo "}}//finaliza funcion de llenar campos";
                }//finaliza if interno


        }//finaliza if externo


        
}//end function


/*
 * Funcion auxiliar
 * Funcion que obtiene los datos de la(s) tabla(s) de destino de un registro identificado por el id.
 * Recibe el nombre de la tabla contenedora de los campos y el id.
 * Devuelve un array con los nombres de los campos como claves y su valor.
 */

function obtenerDatosModificacion($idForm,$id){
    
            
          
           global $DB ;
           
            $datos=Array();
           
    
           $tablas= $DB->Execute("SELECT c.Tabla_destino from cat_campos_aval c JOIN cat_secc_aval s ON c.ID_secc=s.ID_secc
                                   WHERE s.ID_form=".$idForm.
                                 " GROUP BY c.Tabla_destino");

           
            while ($tabla= $tablas->fetchRow()){

                $query="Select ";
                $campos=$DB->Execute("SELECT c.Nombre_tabla from cat_campos_aval c JOIN cat_secc_aval s ON c.ID_secc=s.ID_secc
                                   WHERE s.ID_form=".$idForm." AND c.Tabla_destino='".$tabla[0]."'");

                $i=  $campos->RecordCount();
                
                while ($campo=  $campos->fetchRow()){

                        $i-=1;
                        $query.=$campo[0].(($i)?" , ":"  ");

                    

                }//end while-Campos
                
                
                
                /*
                 * Investigando el nombre del campo ID de la tabla de Destino.
                 */
                $k=$DB->Execute("SHOW KEYS FROM ".$tabla[0]." WHERE Key_name = 'PRIMARY'");
                $c= $k->fetchRow();
                $idName=$c['Column_name'];
               
                
                $query.=" from ".$tabla[0]." where ".$idName."=".$id;
                $valoresObj=  $DB->Execute($query);
                $valoresObtenidos=$valoresObj->fetchRow();
                //volver el arreglo de campos a la posicion 1 y usarlo para crear el array
                $campos->MoveFirst();
                while ($campo=  $campos->fetchRow()){
                         $datos[$campo[0]]=$valoresObtenidos[$campo[0]];
                }
                
    
}//end while-tablas
    
    
 return $datos;   
    
};

/*
 * funcion auxiliar
 * Devuelve un array asociativo con los campos que son dependencias AJAX como claves
 * y como valor una cadena con las clases a las que debe pertenecer.
 * 
 */


function obtenerDependenciasAjax($idForm){
    global $DB;
    $depAjax=Array();
    
    
    try{
    //Elementos modificables dependiendo de otros campos.
    $ajaxElements= $DB->Execute("SELECT c.Nombre_campo, c.Ajax from cat_campos_aval c JOIN cat_secc_aval s ON c.ID_secc=s.ID_secc
                                 WHERE s.ID_form=".$idForm." AND Ajax IS NOT NULL ".
                                " ORDER BY s.Posicion ASC");
    }catch(Exception $e){
        print_r($e);
    }
    
    
    //recorremos cada campo
    while($campo=$ajaxElements->fetchRow()){
        
        $deps=explode('|', $campo['Ajax']);
        
        
        //agregamos al arreglo cada dependencia y su campo dependiente.
        for($i=0;$i<count($deps);$i++){
            
            if(array_key_exists($deps[$i], $depAjax)) 
                    $depAjax[$deps[$i]].=" Dependencia_".$campo['Nombre_campo'];
            else $depAjax[$deps[$i]]="Dependencia_".$campo['Nombre_campo'];
            
            
        }//end for
        
    }//end while
    
    return $depAjax;
}

?>
