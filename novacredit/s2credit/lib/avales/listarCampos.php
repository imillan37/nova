<?php
/* 
 * Funciones para listar los campos de un tabla catalogo de campos y modificar sus opciones.
 * Junio.2012
 */

$DB = &ADONewConnection(SERVIDOR);
$DB->PConnect(IP,USER,PASSWORD,$DB_EMP);        


function imprimirTablaDeCampos($idForm=0){
    
    global $DB;
    
    
     //Obtiene los campos de la seccion correcta.
                try{$campos= $DB->Execute("SELECT c.ID_campo,c.Nombre_campo, c.Tabla_destino,c.Obligatorio_sistema, c.Obligatorio, c.Visible, c.Clase,
                                                c.Html_asoc,c.Tipo,c.Etiqueta,c.Etiqueta_s2credit,c.Style,c.Posicion, 
                                                c.Ajax, c.Script, s.Nombre,s.ID_secc 
                                        FROM cat_forms_aval f JOIN cat_secc_aval s JOIN cat_campos_aval c ON (f.ID_form=s.ID_form AND s.ID_secc=c.ID_secc)
                                        WHERE f.ID_form=".$idForm." ".
                                        "ORDER BY c.ID_campo ASC");
                }catch(exception $e){
                print_r($e);
                }
                
     
   //Obtiene los datos Generales del Formulario.
   try{
    $formR=  $DB->Execute("SELECT f.ID_form,
                                  f.Nombre,
                                  f.Leyenda,
                                  COUNT(c.ID_campo),
                                  SUM(CASE WHEN c.Obligatorio = 'Y' THEN 1 ELSE 0 END),
                                  GROUP_CONCAT(DISTINCT c.Tabla_destino ORDER BY c.Tabla_destino ASC SEPARATOR ','),
                                  COUNT( DISTINCT s.ID_secc) 
                           FROM cat_forms_aval f JOIN cat_secc_aval s JOIN cat_campos_aval c ON (f.ID_form=s.ID_form AND s.ID_secc=c.ID_secc)  
                           WHERE f.ID_form=".$idForm." GROUP BY f.ID_form" );
    $form=$formR->fetchRow();
   }catch(exception $e){
       print_r($e);
   }
   echo "<table id='formTabla' class='tablaListar'>";
   
   echo "<thead>";
   echo "<td>Título</td>";
   echo "<td>Leyenda</td>";
   echo "<td># de Campos </td>";

   echo "</thead>";
   
   echo "<tr data-id='".$form['ID_form']."'>";
   echo "<td id='tituloForm' contenteditable='true'>".$form[1]."</td>";
   echo "<td id='leyendaForm' contenteditable='true'>".$form[2]."</td>";
   echo "<td>".$form[3]."</td>";

  
   echo "</tr >";
  
   
   echo "</tabla>";
   
   
   
   
   echo "<table id='camposTabla' class='tablaListar'>";
   
   echo "<thead>";
  
   echo "<td>Etiqueta</td>";

   echo "<td>Obligatorio</td>";
   echo "<td>Visible</td>";
  // echo "<td>Acciones</td>";

   echo "</thead>";
   
   
   while( $campo=$campos->fetchRow()){
   echo "<tr data-id='".$campo['ID_campo']."'>";
   
   echo "<td class='etiqueta' contenteditable='true'>".$campo['Etiqueta']."</td>";
  
   echo "<td>";
   echo "<input class='Obligatorio' type='checkbox' value='Y' ";
   
        if($campo['Obligatorio_sistema']=='Y') echo "checked disabled />";
        else if($campo['Obligatorio']=='Y') echo "checked />";

   echo "</td>";
   echo "<td>";
   echo "<input class='visible' type='checkbox' value='Y' ";
   
        if($campo['Obligatorio_sistema']=='Y'||$campo['Obligatorio']=='Y') echo "checked disabled />";
        else if($campo['Visible']=='Y') echo "checked />";

   echo "</td>";
   
  // echo "<td><input type='button' value='Guardar' class='botonGuardar'/></td>";

   echo "</tr>";
   }
   
   echo "</tabla>";
   
    
    
}

function imprimirScriptsEdicionDeCampos(){
    
 echo "
     var contenido='';
     $(function(){
     $('.Obligatorio').bind('change', actualizarCampos);   
     $('.visible').bind('change', guardarCambiosVisible);   
     
     $('.etiqueta').bind('focus', guardarContenido);
     $('.etiqueta').bind('blur', checarCambios);
     

     $('#tituloForm').bind('focus', guardarContenido);
     $('#leyendaForm').bind('focus', guardarContenido);
     $('#tituloForm').bind('blur', checarCambiosForm);
     $('#leyendaForm').bind('blur', checarCambiosForm);
     
     });
     

     function actualizarCampos(event){
     
                    if($(event.target).is(':checked')){ 
                            $(this).parent().parent().find('.visible').attr('checked',true);
                            $(this).parent().parent().find('.visible').attr('disabled','disabled');
                    }
                    else{
                            $(this).parent().parent().find('.visible').removeAttr('disabled');
                    }
     

                guardarCambios($(this).parent().parent(),1);
        
       }

     function guardarCambiosVisible(){  guardarCambios($(this).parent().parent(),1); }
      
     function guardarContenido(event){ $(this).css('background-color','#C8D4B1');  contenido=$(event.target).html();   }
     
     function checarCambios(event){ $(this).css('background-color','#FFF'); if(contenido!=$(event.target).html())    guardarCambios($(this).parent(),1); }
     function checarCambiosForm(event){ $(this).css('background-color','#FFF'); if(contenido!=$(event.target).html())    guardarCambios($(this).parent(),0); }
        
     
        
function guardarCambios(fila,tipo){
     
        //var fila=$(this).parent().parent();
        
        if(tipo){
                var campo=fila.data('id');
                var etiqueta=fila.children('.etiqueta').html();

                if( fila.find('.Obligatorio').attr('disabled')!='disabled'){ 
                        var obligatorio=(fila.find('.Obligatorio').is(':checked'))?'Y':'N';
                        var visible=(fila.find('.visible').is(':checked'))?'Y':'N';

                        }
                else {
                    obligatorio='sistema';
                    visible='sistema';
                    }    
                var data={
                    'tipo': tipo,
                    'campo': campo,
                    'etiqueta': etiqueta,
                    'obligatorio': obligatorio,
                    'visible': visible
                        }
                 }
        else{
            var form=fila.data('id');
            var titulo=fila.children('#tituloForm').html();
            var leyenda=fila.children('#leyendaForm').html();
            
            var data={
                    'tipo': tipo,
                    'form': form,
                    'titulo': titulo,
                    'leyenda': leyenda
                    }
        }
        $.post('ajax/ajaxAdmin.php',data,function(html){ alert(html)});

        }        
        ";   
    
    
}




?>
