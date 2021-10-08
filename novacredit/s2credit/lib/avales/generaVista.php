
<?php
/*
 * 
 * Script para generar formulario y sus scripts necesarios a partir de la base de Datos
 * Hector Ivan Patricio Moreno
 * Junio.2012
 * 
 */

//include_once $DOCUMENT_ROOT.$ado_path."adodb.inc.php";

$DB = &ADONewConnection(SERVIDOR);
$DB->PConnect(IP,USER,PASSWORD,$DB_EMP);        

/*
 * Funcion que se encarga de imprimir todos los campos requeridos extrayendolos de la base de datos.
 * Recibe como parametro el formulario para imprimir.
 * 
 */
function imprimirDatosPersonas($idForm,$id=-1,$Leyenda=''){
    
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
    
    
    //Si es una peticion de modificacion, obtiene los datos del registro a modificar.
    if($id!=-1) $datosMod= obtenerDatosModificacion($idForm,$id);
    
    //Comienza la impresion del formulario
    
    
   
    
    //contenedores para genrar las pestanas
    $Leyenda=strtoupper($Leyenda);
    echo "<div class='portlet'><h3 style='text-align: center; font-size: 16px' class='portlet-header'> $Leyenda </h3><div id='tabbedForm' style='margin-top:30px;'>";
    echo "<ul>";
    while($seccion=$secciones->fetchRow()){
        echo "<li><a href='#formSeccion".$seccion['ID_secc']."'>".$seccion['Nombre']."</a></li>";
    }
    echo "</ul>";
    $secciones->MoveFirst();
    while($seccion=$secciones->fetchRow()){
     
                //Obtiene los campos de la seccion correcta.
                try{$campos= $DB->Execute("SELECT c.ID_campo,c.Nombre_campo, c.Obligatorio_sistema, c.Obligatorio, c.Visible, c.Clase,
                                                c.Html_asoc,c.Tipo,c.Etiqueta,c.Etiqueta_s2credit,c.Style,c.SQLQuery, 
                                                c.Ajax, c.Script,c.Tabla_dep,c.Campo_dep,t.Tipo AS Tipo_c, t.Tipo_campo
                                        FROM cat_campos_aval c JOIN cat_tipo_campos t ON t.ID_tipo_campo=c.ID_tipo 
                                        WHERE c.ID_secc=".$seccion['ID_secc']." AND c.Visible='Y' 
                                        ORDER BY c.Posicion ASC");
                }catch(exception $e){
                print_r($e);
                }
                //print_r($campos);
            //imprimiendo la envoltura de la seccion, y en su caso, el titulo.
            echo "<div id='formSeccion".$seccion['ID_secc']."' >";
            /*
             * Recorre todos los campos extraidos de la tabla indicada para imprimirlos
             */
            while($campo=$campos->fetchRow()){


            //Se imprimen elementos auxiliares para poder alinear los campos y tener el formulario bien formado.
                    echo'<span class="fila"><span class="etiquetaDatos" >'.$campo['Etiqueta'].'</span> <span class="campo"   >';



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
                                              
                                        echo $valorS.'</span></span>';
                                        break;


                            /********************************************************************************************************************************************
                            ******************************************************Text Area*****************************************************************************
                            ********************************************************************************************************************************************/            

                        case 'Textarea':
                                                           
                                        echo $valorS.'</span></span>';

                                        break;
                        /********************************************************************************************************************************************
                            **************************************************Select construido por consulta o AJAX*****************************************************
                            *********************************************************************************************************************************************/                       
                        case 'Select':
                                                      
                                        
                                        if($campo['SQLQuery']!=NULL && $campo['Ajax']==NULL){

                                                        $opciones=  $DB->Execute($campo['SQLQuery']);

                                                        while($opcion=  $opciones->fetchRow()){

                                                                            echo (($valorS==$opcion[0])?$opcion[1]:"");

                                                        }//end while interno
                                        }// end if
                                        elseif($campo['Nombre_campo']=='Estado_actual'||$campo['Nombre_campo']=='Ciudad'||$campo['Nombre_campo']=='Poblacion'||$campo['Nombre_campo']=='Colonia'){
                                            echo $valorS;
                                        }
	                                    else{
	                                        	if($campo["Tabla_dep"]!=''){

	                                        		$k=$DB->Execute("SHOW KEYS FROM ".$campo['Tabla_dep']." WHERE Key_name = 'PRIMARY'");
	                                				$c= $k->fetchRow();
	                                				$idName=$c['Column_name'];

	                                        		$valorG=$DB->GetOne("Select ".$campo['Campo_dep']." from ".$campo['Tabla_dep']." WHERE ".$idName."='".$valorS."'");
	                                        		echo $valorG;
	                                        	}


                                        }

                                        echo'</span></span>';
                                        break;

                            /********************************************************************************************************************************************
                            *************************************************Select Estatico****************************************************************************
                            ********************************************************************************************************************************************/
                        case 'Select_array':
                                                 
                                        $opciones=explode('|',$campo['SQLQuery']);
                                                for($i=0;$i<count($opciones);$i++){
                                                    $opcion=explode('=',$opciones[$i]);
                                                    echo (($valorS==$opcion[1])?$opcion[0]:"");  
                                                }

                                        echo'</span></span>';
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
                                        echo $valor_CF.'</span></span>';
                                        break;

                    }	//end switch
                    echo '</span>';
                } //end while campos

                echo "</div>"; //fin de envoltura de seccion
    } //end While secciones
    
    echo "</div></div>"; //se cierra contenedor de pestanas 
    
}

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


?>
