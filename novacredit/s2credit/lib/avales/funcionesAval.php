<?php
$noheader =1;
include_once($DOCUMENT_ROOT."/rutas.php");
include_once $ado_path."adodb.inc.php";

    
//imprime una lista de opciones con los id y los nombre de la tabla estados, limitado por el codigo postal
function imprimirEstados($cp){
        
       $DB = &ADONewConnection(SERVIDOR); $DB->PConnect(IP,USER,PASSWORD,$DB_EMP);  
        $estados=  ejecutarQueryArray($conexion, "Select * from estados where $cp>=Rango1 AND $cp<=Rango2");
            
            echo"<option value=-1> Seleccione Estado </option>";
        while($e=mysql_fetch_array($estados)){
            
            echo"<option value=".$e['ID_Estado'].">".$e['Nombre']."</option>";
        
        }
        
    }


//imprime una lista de opciones con los id y los nombre de la tabla estados, limitado por el codigo postal
function imprimirColonias($cp,$estado){
        
       $DB = &ADONewConnection(SERVIDOR); $DB->PConnect(IP,USER,PASSWORD,$DB_EMP);  
        $colonias=  ejecutarQueryArray($conexion, "Select ID_Colonia,Colonia from codigos_postales  where ID_Estado=$estado AND CP=$cp");
        
        echo"<option value=-1> Seleccione Colonia </option>";
        
        while($e=mysql_fetch_array($colonias)){
            
            echo"<option value=".$e['ID_Colonia'].">".$e['Colonia']."</option>";
        
        }
        
    }    
//imprime una lista de opciones con los id y los nombre de la tabla ciudades, limitado por el codigo postal y el estado
function imprimirCiudades($cp,$estado){
        
        $DB = NewADOConnection('mysql'); $DB->Connect($server, $user, $pwd, $db);
        $query= "Select ID_Ciudad,Nombre from ciudades  where ID_Estado=$estado AND ((rango1<=$cp AND rango2>=$cp) OR (rango3<=$cp AND rango4>=$cp)) ";
        $ciudades=  ejecutarQueryArray($conexion, $query );
            
        
        echo"<option value=-1> Seleccione Ciudad </option>";
        while($e=mysql_fetch_array($ciudades)){
            
            echo"<option value=".$e['ID_Ciudad'].">".$e['Nombre']."</option>";
        
        }
        
    } 
    
//imprime una lista de opciones con los id y los nombre de la tabla poblaciones, limitado por el codigo postal y el estado
function imprimirPoblaciones($cp,$estado){
        
       $DB = &ADONewConnection(SERVIDOR); $DB->PConnect(IP,USER,PASSWORD,$DB_EMP);  
        $query= "Select ID_Municipio,Nombre from municipios  where ID_Estado=$estado AND ((rango1<=$cp AND rango2>=$cp) OR (rango3<=$cp AND rango4>=$cp) OR (rango5<=$cp AND rango6>=$cp) OR (rango7<=$cp AND rango8>=$cp))";
        $poblaciones=  ejecutarQueryArray($conexion, $query );
        
        echo"<option value=-1> Seleccione Población/Delegación </option>";
        
        while($e=mysql_fetch_array($poblaciones)){
            
            echo"<option value=".$e['ID_Municipio'].">".$e['Nombre']."</option>";
        
        }
        
    }
    
function imprimirTablaAvales(){
        global $server, $user, $pwd, $db;
       
        
        try{
                $DB = &ADONewConnection(SERVIDOR);
$DB->PConnect(IP,USER,PASSWORD,$DB_EMP);  
                $avales=  $DB->Execute("Select * from aval order by Nombre asc");
        }catch(Exception $e){ print_r($e);}
       
        while( $a=$avales->fetchRow() ){
            
                echo "<tr><td>".$a['idAval']."</td><td>".
                       $a['Nombre']." ".$a['Nombre1']." ".$a['Ap_paterno']." ".$a['Ap_materno']."</td><td>".
                       $a['Fecha_nac']."</td><td>".
                       $a['Rfc']."</td><td>".
                       $a['CP']."</td><td>".
                       $a['Calle']."</td><td>".
                       $a['Num_exterior']."</td><td>".
                       $a['Colonia']."</td>".
                       "<td><a href='#' class='aModificar' data-inf=".$a['idAval'].">Modificar</a></td>".
                       "<td><a href='#' class='aEliminar' data-inf=".$a['idAval'].">Eliminar</a></td></tr>";
            }
        
    }
    
function imprimirResultado($caso){
    
    
                echo  '<!DOCTYPE html>
                            <html>
                            <head>
                        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                        <link href="css/estilo.css" type="text/css" rel="stylesheet" />

                        <title>Éxito</title>
                            </head>
                        <body>
                        <div id="contenedor">
                        <h1>Éxito al Realizar Operación</h1>';
                

                if($caso=="1"){
                    echo' <p>Se ha registrado el Aval con éxito.</p>
                        <a href="index.php"> Registrar Otro Aval</a><br />
                        <a href="listar.php"> Ver todos las personas registradas como Aval</a><br />';
                }
                
                else if($caso=="2"){
                    echo '<p>Se ha Eliminado a la persona seleccionada. </p>        
                        <a href="index.php"> Registrar Otro Aval</a><br />
                        <a href="listar.php"> Regresar a la Lista</a><br />';
                }


                echo '</div></body></html>';
    
}    
    
    
/*pruebas*/

function imprimirTablaAvalesPruebas($limite){
        
       $DB = &ADONewConnection(SERVIDOR); $DB->PConnect(IP,USER,PASSWORD,$DB_EMP);  
        $avales=  ejecutarQueryArray($conexion, "Select * from aval order by Nombre asc ");
        while($a=mysql_fetch_array($avales)){
        echo "<tr style='display: none' id=".$a['idAval']."   data-rfc='".$a['Nombre']." ".$a['Nombre1'].",".$a['Ap_paterno'].",".$a['Ap_materno'].",".$a['Fecha_nac'].",".$a['Rfc'].",".$a['Homoclave']."' ".
                       "><td>".$a['idAval']."</td><td>".
                       $a['Nombre']." ".$a['Nombre1']." ".$a['Ap_paterno']." ".$a['Ap_materno']."</td><td>".
                       $a['Fecha_nac']."</td><td>".
                       $a['Rfc']."</td>".
                       "<td id=rfc".$a['idAval']."></td>".
                       "<td id=vRfc".$a['idAval']."></td><td>".
                       $a['Homoclave']."</td>".
                       "<td id=h".$a['idAval']."></td>".
                       "<td id=vH".$a['idAval']."></td></tr>"
                            ;
        }
        
    }

    

    
?>
