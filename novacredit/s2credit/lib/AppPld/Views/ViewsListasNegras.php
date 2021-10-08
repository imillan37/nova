<?php
	
/**
 *
 * @author MarsVoltoso (CFA)ViewsContenedorCatalogos.php
 * @category Views
 * @created Mon Sep 15, 2014
 * @version 1.0
 */	



include($DOCUMENT_ROOT."/rutas.php");
    
$db = &ADONewConnection(SERVIDOR);
$db->PConnect(IP,USER,PASSWORD,NUCLEO);	    
    
    $query = "SELECT
                    id_alias_lista_negra,
                    alias,
                    UN_LIST_TYPE
               FROM pld_alias_listas_negras ";
               
        $response = $db->Execute($query);  //debug($Query);
				  
		    while( !$response->EOF ) { 
						
					$id_alias_lista_negra = $response->fields["id_alias_lista_negra"];
					$alias                = $response->fields["alias"];
					$UN_LIST_TYPE         = $response->fields["UN_LIST_TYPE"];
					 
				        $htmlTbody .= '<div id="" class="item" data-value="'.$UN_LIST_TYPE.'">'.$alias.'</div>';
		        
		        $response->MoveNext(); 
            } // fin while( !$RESPUESTA->EOF ) 	
    
?>

<script src="../Site_media/Jquery/jquery-2.1.1.min.js"></script>
<script src="../Site_media/Jquery/jquery.form.js"></script>
<script src="../Site_media/packaged/javascript/semantic.js"></script>
<script src="../Site_media/Jquery/jquery.address.js"></script>
<script src="../JavaScipt/coreFunction.js"></script>

<script src="../JavaScipt/ViewsListasNegras.js"></script>

<link rel="stylesheet" type="text/css" class="ui" href="../Site_media/packaged/css/semantic.min.css">

<div class="ui ignored info message" style="text-align: center;"><h2>Listas Negras</h2></div>
  <div class="ui blue segment" data-tab="standard">
		
		<div class="content">

			<table class="ui table segment" style="width:30%">
	  <thead>
	    <tr>
	    	<th align="center">Nombre</th>
	    	<th align="center">
		    	
		    	<div class="actions" align="left">
    				<div class="ui icon input">
    					<input placeholder="Nombre" class="large" type="text" id="BuscarTerrorista" style="width: 400px;" size="32">
  	    				<i class="search inverted icon"></i>
    				</div>
  				</div>
		    	
	    	</th>
	   </tr>
        <tr>
            <th align="center">Catalogo</th>
            <th align="center">

                <div class="ui fluid selection dropdown">
                    <div class="text">SELECCIONE</div>
                    <i class="dropdown icon"></i>
                    <input name="Catalogos" id="Catalogos" type="hidden">
                    <div class="menu">
                        <div id="" class="item" data-value="Todo">TODOS</div>
                        <div id="" class="item" data-value="Terroristas">TERRORISTAS</div>
                        <div id="" class="item" data-value="lc">LISTAS BLOQUEDAS</div>
                        <div id="" class="item" data-value="lp">LISTAS PROPIAS</div>
                        <div id="" class="item" data-value="ppe">PERSONAS POLITICAMENTE EXPUESTAS</div>
                        <div id="" class="item" data-value="sat">SAT</div>
                    </div>
                </div>

            </th>
        </tr>
        <tr id="trDisplay" style="display:none;">
            <th align="center">Tipo de lista </th>
            <th align="center">

                <div class="ui fluid selection dropdown">
                    <div class="text">SELECCIONE</div>
                    <i class="dropdown icon"></i>
                    <input name="tipoCatalogo" id="tipoCatalogo" type="hidden">
                    <div class="menu">
                        <?=$htmlTbody?>
                    </div>
                </div>

            </th>
        </tr>
	</table>


  			
  	</div>

  </div>

 <div class="ui blue segment" data-tab="standard">  
	<div class="ui" id="">
  		<div class="content">
    		<div id="listasNegras"></div>
  		</div>
  		
	</div>
	


</div>
<br>
