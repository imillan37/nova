<?php
/**
 *
 * @author MarsVoltoso (CFA)
 * @category Contoller
 * @created Mon Sep 15, 2014
 * @version 1.0
 */	
 
 
/**
 *
 *  @ Cargamos Vista  
 */ 

 
class ControllerContenedorCatalogos {
 
 	public $db;
    public $muestra_global = 10;
 	 
 		public function __construct($db){
	 		$this->db = $db; 
	 	}
 		
 		public function VistaPersonasPuestos($BuscaPuesto,$Evento,$Pagina){
            $muestra = $this->muestra_global;
            $QueryWhere = "";
 		
 		if( !empty($BuscaPuesto) ){
		 	$QueryWhere = "WHERE pld_politicamente_expuestos.Puesto LIKE '%".$BuscaPuesto."%'";
	 	} // fin if	

            $Query_count = "SELECT count(*) as Total
									FROM pld_politicamente_expuestos $QueryWhere ";


            $RESPUESTA      = $this->db->Execute($Query_count);
            $total_registros = $RESPUESTA->fields["Total"];
            $total_paginas = $total_registros/$muestra;
            $total_paginas = ceil($total_paginas);

            if($Pagina>$total_paginas)
            {
                $Pagina = 1;
            }

            if (!$Pagina or $Pagina == 0) {
                $limite_ini = 0;
                $Pagina = 1;
            }
            else {
                if($Evento == "Prev"){
                    $Pagina--;
                    if($Pagina == 0)
                    {
                        $Pagina = 1;
                    }
                }elseif($Evento == "Next")
                {
                    $Pagina++;
                    if($Pagina>$total_paginas)
                    {
                        $Pagina--;
                    }
                }elseif($Evento == "First")
                {
                    $Pagina = 1;
                }elseif($Evento == "Last")
                {
                    $Pagina = $total_paginas;
                }else
                {
                    $limite_ini = ($Pagina) * $muestra;
                }

                $limite_ini = ($Pagina-1) * $muestra;

            }

            if($limite_ini < 0)
            {
                $limite_ini = 0;
            }

            $limite = "LIMIT ".$limite_ini.", ".$muestra;

	 		$Query = "SELECT 
		 					 pld_politicamente_expuestos.ID_PPE,
		 					 pld_politicamente_expuestos.Puesto,
		 					 pld_politicamente_expuestos.Registro
		 				FROM pld_politicamente_expuestos
		 				$QueryWhere
		 		    ORDER BY ID_PPE DESC
				   	   $limite";
        
			 $RESPUESTA      = $this->db->Execute($Query);  //debug($Query);
        	 
        	 
        	 while( !$RESPUESTA->EOF ) { 
						
				 $ID_PPE       = $RESPUESTA->fields["ID_PPE"];
	        	 $Puesto       = $RESPUESTA->fields["Puesto"];
	        	 $Registro     = $RESPUESTA->fields["Registro"];
	        	 		
			  $htmlTbody .= '
			  
			  	<tr>
					<td>'.mb_strtoupper($Puesto).'</td>
					<td>'.mb_strtoupper($Registro).'</td>
					<td align="center"><a class="ui mini green button" onclick="EditarPuestoPoliticamente('.$ID_PPE.');">Editar</a></td>
					<td align="center"><a class="ui mini red button" onclick="EliminarPuesto('.$ID_PPE.');">Eliminar</a></td>
				</tr>
			  
			  ';
			   
			   
			   $RESPUESTA->MoveNext(); 
			 } // fin while( !$RESPUESTA->EOF ) {
	 		
	 		$html = ' 
        	  
        	  <table align="center" width="100%" class="ui table segment">
				<thead>
					<tr>
						<th width="50%">PUESTO</th>
						<th width="10%">FECHA DE ALTA</th>
						<th width="20%"></th>
						<th width="20%"></th>
					</tr>
				</thead>
				'.$htmlTbody.'
			</table>';

            $html .= "
            <div class='ui'>
                <i class='big step backward icon' onclick='ActualizaLista(\"First\",".$Pagina.")'></i>
                <i class='big backward icon' onclick='ActualizaLista(\"Prev\",".$Pagina.")'></i>

                <div class='ui small icon input'>
                    <input size='4' type='text' id='Paginacion' onchange='CambiaPagina(this)' value='".$Pagina."'>
                </div>

                <i class='big forward icon' onclick='ActualizaLista(\"Next\",".$Pagina.")'></i>
                <i class='big step forward icon' onclick='ActualizaLista(\"Last\",".$Pagina.")'></i>
            </div>";

            echo  $html;
	 		
 		} // fin function
 		
 		public function VistaPersonas($BuscaRFC,$Evento,$Pagina){

        $muestra = $this->muestra_global;
        $QueryWhere = "";

	 	if( !empty($BuscaRFC) ){
            $BuscaRFC = str_replace(" ", "%", $BuscaRFC);
		 	$QueryWhere = "WHERE CONCAT(pld_cat_nombres_ppe.Nombre_I,' ',pld_cat_nombres_ppe.Ap_paterno,' ',pld_cat_nombres_ppe.Ap_materno) LIKE '%".$BuscaRFC."%'";
	 	} // fin if	

            $Query_count = "SELECT count(*) as Total
									FROM pld_cat_nombres_ppe $QueryWhere ";

            $RESPUESTA      = $this->db->Execute($Query_count);
            $total_registros = $RESPUESTA->fields["Total"];
            $total_paginas = $total_registros/$muestra;
            $total_paginas = ceil($total_paginas);

            if($Pagina>$total_paginas)
            {
                $Pagina = 1;
            }

            if (!$Pagina or $Pagina == 0) {
                $limite_ini = 0;
                $Pagina = 1;
            }
            else {
                if($Evento == "Prev"){
                    $Pagina--;
                    if($Pagina == 0)
                    {
                        $Pagina = 1;
                    }
                }elseif($Evento == "Next")
                {
                    $Pagina++;
                    if($Pagina>$total_paginas)
                    {
                        $Pagina--;
                    }
                }elseif($Evento == "First")
                {
                    $Pagina = 1;
                }elseif($Evento == "Last")
                {
                    $Pagina = $total_paginas;
                }else
                {
                    $limite_ini = ($Pagina) * $muestra;
                }

                $limite_ini = ($Pagina-1) * $muestra;

            }

            if($limite_ini < 0)
            {
                $limite_ini = 0;
            }

            $limite = "LIMIT ".$limite_ini.", ".$muestra;


            $Query = "SELECT
        				 pld_cat_nombres_ppe.ID_Nmb_ppe,
						 pld_cat_nombres_ppe.ID_Usr,
						 pld_cat_nombres_ppe.Nombre_I,
						 pld_cat_nombres_ppe.Ap_paterno,
						 pld_cat_nombres_ppe.Ap_materno,
						 pld_cat_nombres_ppe.RFC,
						 pld_cat_nombres_ppe.CURP,
						 pld_cat_nombres_ppe.Fecha_sistema
					FROM pld_cat_nombres_ppe
					$QueryWhere
					ORDER BY ID_Nmb_ppe DESC
					$limite ";
        
			 $RESPUESTA      = $this->db->Execute($Query);  // debug($Query);
        	 
        	 
        	 while( !$RESPUESTA->EOF ) { 
						
				 $ID_Nmb_ppe     = $RESPUESTA->fields["ID_Nmb_ppe"];
	        	 $ID_Usr         = $RESPUESTA->fields["ID_Usr"];
	        	 $Nombre_I       = $RESPUESTA->fields["Nombre_I"];
	        	 $Ap_paterno     = $RESPUESTA->fields["Ap_paterno"];
	        	 $Ap_materno     = $RESPUESTA->fields["Ap_materno"];
	        	 $RFC            = $RESPUESTA->fields["RFC"];
                 $CURP           = $RESPUESTA->fields["CURP"];
	        	 $Fecha_sistema  = $RESPUESTA->fields["Fecha_sistema"];			
						
			  $htmlTbody .= '
			  
			  	<tr>
					<td>'.mb_strtoupper($Nombre_I).'</td>
					<td>'.mb_strtoupper($Ap_paterno).'</td>
					<td>'.mb_strtoupper($Ap_materno).'</td>
					<td>'.mb_strtoupper($RFC).'</td>
					<td>'.mb_strtoupper($CURP).'</td>
					<td>'.mb_strtoupper($Fecha_sistema).'</td>
					<td align="center"><a class="ui mini green button" onclick="EditarPersonaPoliticamente('.$ID_Nmb_ppe.');">Editar</a></td>
					<td align="center"><a class="ui mini red button" onclick="EliminarPersonaPoliticamente('.$ID_Nmb_ppe.');">Eliminar</a></td>
				</tr>
			  
			  ';
			   
			   
			   $RESPUESTA->MoveNext(); 
			 } // fin while( !$RESPUESTA->EOF ) {
	 		
	 		$html = ' 
        	  
        	  <table align="center" width="100%" class="ui table segment">
				<thead>
					<tr>
						<th width="15%">NOMBRE</th>
						<th width="15%">APELLIDO PATERNO</th>
						<th width="15%">APELLIDO MATERNO</th>
						<th width="10%">R.F.C.</th>
						<th width="10%">C.U.R.P.</th>
						<th width="10%">FECHA DE ALTA</th>
						<th width="20%"></th>
						<th width="20%"></th>
					</tr>
				</thead>
				'.$htmlTbody.'
			</table>';

            $html .= "
            <div class='ui'>
                <i class='big step backward icon' onclick='ActualizaLista(\"First\",".$Pagina.")'></i>
                <i class='big backward icon' onclick='ActualizaLista(\"Prev\",".$Pagina.")'></i>

                <div class='ui small icon input'>
                    <input size='4' type='text' id='Paginacion' onchange='CambiaPagina(this)' value='".$Pagina."'>
                </div>

                <i class='big forward icon' onclick='ActualizaLista(\"Next\",".$Pagina.")'></i>
                <i class='big step forward icon' onclick='ActualizaLista(\"Last\",".$Pagina.")'></i>
            </div>";

            echo  $html;
        	 
 		} // fin public function VistaPersonas(){
 		
 		public function AddPersonas($arrDatos,$ID_USR){

            //***********modificacion por RLJ para el curp**********//
	 		
	 		$NombreCompleto = mb_strtoupper($arrDatos[0])." ".mb_strtoupper($arrDatos[1])." ".mb_strtoupper($arrDatos[2]);
	 		
	 		$Query = "SELECT COUNT(pld_cat_nombres_ppe.ID_Nmb_ppe) AS ID_Nmb_ppe
	 					FROM pld_cat_nombres_ppe
	 				   WHERE CONCAT(pld_cat_nombres_ppe.Nombre_I,' ',pld_cat_nombres_ppe.Ap_paterno,' ',pld_cat_nombres_ppe.Ap_materno)  = '$NombreCompleto' ";
	 		
	 				    $RESPUESTA      = $this->db->Execute($Query); // debug($Query);
	 					$ID_Nmb_ppe     = $RESPUESTA->fields["ID_Nmb_ppe"];
	 		
	 		if( empty($ID_Nmb_ppe) ){			
	 		
	 		$Query = "INSERT INTO pld_cat_nombres_ppe (ID_Usr,Nombre_I,Ap_paterno,Ap_materno,RFC,CURP)
	 					   VALUES
	 					   		 (".$ID_USR.", '".mb_strtoupper($arrDatos[0])."', '".mb_strtoupper($arrDatos[1])."', '".mb_strtoupper($arrDatos[2])."', '".mb_strtoupper($arrDatos[3])."', '".mb_strtoupper($arrDatos[4])."')";
	 			
	 			 $this->db->Execute($Query);  //debug($Query);			   		 
                 $id_insert = $this->db->_insertid();

                 $Query = "INSERT INTO pld_cat_listas_negras_log (ID_persona,ID_Usr,Tipo,Accion)
	 					  			   VALUES
	 					  		   		  (".$id_insert.", ".$ID_USR.", 'PPE', 'INSERT')";
                 $this->db->Execute($Query); // debug($Query);

	 			 echo "LISTO";
	 	
	 		}else{
		 		 echo "YA EXISTE";
	 		}	 
	 	
	 	} // fin function
	 	
	 	public function EditionPersonas($arrDatos,$ID_USR,$id){

	 	    //***********modificacion por RLJ para el curp**********//
		 	if( !empty($id) ){
			 	
			 	$Query = "UPDATE pld_cat_nombres_ppe 
			 				 SET Nombre_I    = '".mb_strtoupper($arrDatos[0])."',
			 				     Ap_paterno  = '".mb_strtoupper($arrDatos[1])."',
			 				     Ap_materno  = '".mb_strtoupper($arrDatos[2])."',
			 				     RFC         = '".mb_strtoupper($arrDatos[3])."',
			 				     CURP        = '".mb_strtoupper($arrDatos[4])."'
			 			   WHERE ID_Nmb_ppe  = '".$id."'";
			 			   
			 		 $this->db->Execute($Query);  // debug($Query);

                $Query = "INSERT INTO pld_cat_listas_negras_log (ID_persona,ID_Usr,Tipo,Accion)
	 					  			   VALUES
	 					  		   		  (".$id.", ".$ID_USR.", 'PPE', 'UPDATE')";

                $this->db->Execute($Query); // debug($Query);
			 	echo "LISTO"; 	   
			} // fin if
		 	
	 	} // fin function
 
	 	public function DeletePersonas($id,$ID_USR){
		 	
		 	if(!empty($id)){
                $Query = "SELECT
        				 pld_cat_nombres_ppe.ID_Nmb_ppe,
						 pld_cat_nombres_ppe.ID_Usr,
						 pld_cat_nombres_ppe.Nombre_I,
						 pld_cat_nombres_ppe.Ap_paterno,
						 pld_cat_nombres_ppe.Ap_materno,
						 pld_cat_nombres_ppe.RFC,
						 pld_cat_nombres_ppe.CURP,
						 pld_cat_nombres_ppe.Fecha_sistema
					FROM pld_cat_nombres_ppe
					WHERE pld_cat_nombres_ppe.ID_Nmb_ppe = '".$id."'";

                $RESPUESTA      = $this->db->Execute($Query);  // debug($Query);

                $ID_Nmb_ppe     = $RESPUESTA->fields["ID_Nmb_ppe"];
                $ID_Usr         = $RESPUESTA->fields["ID_Usr"];
                $Nombre_I       = $RESPUESTA->fields["Nombre_I"];
                $Ap_paterno     = $RESPUESTA->fields["Ap_paterno"];
                $Ap_materno     = $RESPUESTA->fields["Ap_materno"];
                $RFC            = $RESPUESTA->fields["RFC"];
                $CURP           = $RESPUESTA->fields["CURP"];

                $QueryRespaldo = "INSERT INTO pld_cat_nombres_ppe (ID_Nmb_ppe,ID_Usr,Nombre_I,Ap_paterno,Ap_materno,RFC,CURP)
                VALUES ( ".$ID_Nmb_ppe.", ".$ID_Usr.", '".mb_strtoupper($Nombre_I)."', '".mb_strtoupper($Ap_paterno)."', '".mb_strtoupper($Ap_materno)."', '".mb_strtoupper($RFC)."', '".mb_strtoupper($CURP)."' ) ";

			 	$Query = "DELETE FROM pld_cat_nombres_ppe WHERE ID_Nmb_ppe = '".$id."'";
			 	$this->db->Execute($Query);  //debug($Query);

                $QueryRespaldo=str_replace("'","\'",$QueryRespaldo);
                $Query = "INSERT INTO pld_cat_listas_negras_log (ID_persona,ID_Usr,Tipo,Accion,sentencia)
	 					  			   VALUES
	 					  		   		  (".$id.", ".$ID_USR.", 'PPE', 'DELETE','".$QueryRespaldo."')";
                $this->db->Execute($Query);

			 	echo "LISTO";
			}else{
				echo "ERROR";
			}
		 	
	 	} // fin function
	 	
	 	public function DeletePersonasPuestos($id,$ID_USR){
		 	
		 	if(!empty($id)){

                $Query = "SELECT  ID_PPE,  Puesto,  Registro, ID_Usr FROM pld_politicamente_expuestos
                WHERE pld_politicamente_expuestos.ID_PPE = '".$id."' ";

                $RESPUESTA      = $this->db->Execute($Query);  // debug($Query);

                $ID_PPE     = $RESPUESTA->fields["ID_PPE"];
                $Puesto     = $RESPUESTA->fields["Puesto"];
                $Registro   = $RESPUESTA->fields["Registro"];
                $ID_Usr     = $RESPUESTA->fields["ID_Usr"];


                $QueryRespaldo = "INSERT INTO pld_politicamente_expuestos (ID_PPE,  Puesto,  Registro, ID_Usr)
                VALUES ( ".$ID_PPE.", '".mb_strtoupper($Puesto)."', '".$Registro."', ".$ID_Usr." ) ";


			 	$Query = "DELETE FROM pld_politicamente_expuestos WHERE ID_PPE = '".$id."'";
			 	$this->db->Execute($Query);  //debug($Query);


                $QueryRespaldo=str_replace("'","\'",$QueryRespaldo);
                $Query = "INSERT INTO pld_catalogos_log(ID_registro,ID_Usr,Tipo,Accion,sentencia)
		 						   VALUES (".$id.", '".$ID_USR."', 'PUESTOS','DELETE', '".$QueryRespaldo."' )";
                $this->db->Execute($Query); // debug($Query);

			 	echo "LISTO";
			}else{
				echo "ERROR";
			}
		 	
	 	} // fin function
	 	
	    public function EditDatosPersonas($id){
		    
		    if( !empty($id) ){
			    
		 $Query = "SELECT 
        				 pld_cat_nombres_ppe.ID_Nmb_ppe,
						 pld_cat_nombres_ppe.ID_Usr,
						 pld_cat_nombres_ppe.Nombre_I,
						 pld_cat_nombres_ppe.Ap_paterno,
						 pld_cat_nombres_ppe.Ap_materno,
						 pld_cat_nombres_ppe.RFC,
						 pld_cat_nombres_ppe.Fecha_sistema
					FROM pld_cat_nombres_ppe
				   WHERE pld_cat_nombres_ppe.ID_Nmb_ppe = '".$id."' ";
        
				 $RESPUESTA      = $this->db->Execute($Query);  //debug($Query);
				 $Nombre_I       = $RESPUESTA->fields["Nombre_I"];
	        	 $Ap_paterno     = $RESPUESTA->fields["Ap_paterno"];
	        	 $Ap_materno     = $RESPUESTA->fields["Ap_materno"];
	        	 $RFC            = $RESPUESTA->fields["RFC"];	
			    
			          $html = '<div class="ui form segment">
									
									<div class="field" id="divNombre">
										<label>Nombre</label>
										<div class="ui left labeled icon input">
											<input type="text" name="Nombre" id="Nombre" placeholder="Nombre" value="'.$Nombre_I.'">
											<div class="ui corner label">
												<i class="icon asterisk"></i>
											</div>
										</div>
									</div>
									
									<div class="field" id="divApPaterno">
										<label>Apellido paterno</label>
										<div class="ui left labeled icon input">
											<input type="text" name="ApPaterno" id="ApPaterno" placeholder="Apellido paterno" value="'.$ApPaterno.'">
											<div class="ui corner label">
												<i class="icon asterisk"></i>
											</div>
										</div>
									</div>
									
									<div class="field" id="divApMaterno">
										<label>Apellido Materno</label>
										<div class="ui left labeled icon input">
											<input type="text" name="ApMaterno" id="ApMaterno" placeholder="Apellido Materno" value="'.$ApMaterno.'">
											<div class="ui corner label">
												<i class="icon asterisk"></i>
											</div>
										</div>
									</div>
									
									<div class="field" id="divRFC">
										<label>R.F.C.</label>
										<div class="ui left labeled icon input">
											<input type="text" name="RFC" id="RFC" placeholder="R.F.C" value="'.$RFC.'">
											<div class="ui corner label">
												<i class="icon asterisk"></i>
											</div>
										</div>
									</div>
									
									<div class="ui error message">
										<div class="header">We noticed some issues</div>
									</div>
									<div class="ui mini blue submit button" id="uiSibmitEdit">Editar</div>	
								
								</div>
						  
			    ';
			    
			   echo $html;
			    
		    } // fin if
		    
	    } // fin function
	    
	    public function AddFormDatosPersonas($id){	
	 	
			  $html = '<div class="ui form segment">
			  			  <div class="ui form segment">
									
									<div class="field" id="divNombre">
										<label>Nombre</label>
										<div class="ui left labeled icon input">
											<input type="text" name="Nombre" id="Nombre" placeholder="Nombre">
											<div class="ui corner label">
												<i class="icon asterisk"></i>
											</div>
										</div>
									</div>
									
									<div class="field" id="divApPaterno">
										<label>Apellido paterno</label>
										<div class="ui left labeled icon input">
											<input type="text" name="ApPaterno" id="ApPaterno" placeholder="Apellido paterno">
											<div class="ui corner label">
												<i class="icon asterisk"></i>
											</div>
										</div>
									</div>
									
									<div class="field" id="divApMaterno">
										<label>Apellido Materno</label>
										<div class="ui left labeled icon input">
											<input type="text" name="ApMaterno" id="ApMaterno" placeholder="Apellido Materno">
											<div class="ui corner label">
												<i class="icon asterisk"></i>
											</div>
										</div>
									</div>
									
									<div class="field" id="divRFC">
										<label>R.F.C.</label>
										<div class="ui left labeled icon input">
											<input type="text" name="RFC" id="RFC" placeholder="R.F.C">
											<div class="ui corner label">
												<i class="icon asterisk"></i>
											</div>
										</div>
									</div>
									
									<div class="ui error message">
										<div class="header">We noticed some issues</div>
									</div>
									<div class="ui mini blue submit button" id="uiSibmit">Agregar</div>	
								
					</div>';
			    
			   echo $html;
	 	
	 	 } // fin function
	 	
	 	public function AddPuestosDatosPersonas($arrDatos,$ID_USR){
		 	
		 	$Query = "SELECT 
		 					 pld_politicamente_expuestos.ID_PPE,
		 					 pld_politicamente_expuestos.Puesto,
		 					 pld_politicamente_expuestos.Registro
		 				FROM pld_politicamente_expuestos
		 			   WHERE pld_politicamente_expuestos.Puesto = '".$arrDatos[0]."' ";
		 			   
		 			   $RESPUESTA  = $this->db->Execute($Query); // debug($Query);
	 				   $ID_PPE     = $RESPUESTA->fields["ID_PPE"];
	 				   
	 			if( empty($ID_PPE) ){
		 			
		 			$Query = "INSERT INTO pld_politicamente_expuestos(Puesto,Registro,ID_Usr) 
		 						   VALUES ('".mb_strtoupper($arrDatos[0])."', NOW(), '".$ID_USR."' )";
		 				$this->db->Execute($Query); // debug($Query);
                        $id_insert = $this->db->_insertid();

                    $Query = "INSERT INTO pld_catalogos_log(ID_registro,ID_Usr,Tipo,Accion)
		 						   VALUES (".$id_insert.", '".$ID_USR."', 'PUESTOS','INSERT' )";
                    $this->db->Execute($Query); // debug($Query);

		 			 echo "LISTO";		   
		 		}else{
		 			echo "YA EXISTE";
	 			}	   
		 	
	 	} // fin function
	 	
	 	public function EditPuestosDatosPersonas($arrDatos,$ID_USR,$id){
		 	
		 	if( !empty($id) ){
			 	
			 	$Query = "UPDATE pld_politicamente_expuestos 
			 				 SET Puesto = '".$arrDatos[0]."' ,
							 ID_Usr = '".$ID_USR."'
			 			   WHERE ID_PPE = '".$id."' ";
			 		
			 		$this->db->Execute($Query);   //debug($Query);

                $Query = "INSERT INTO pld_catalogos_log(ID_registro,ID_Usr,Tipo,Accion)
		 						   VALUES (".$id.", '".$ID_USR."', 'PUESTOS','UPDATE' )";
                $this->db->Execute($Query); // debug($Query);

			 	echo "LISTO"; 	
			 	
		 	} // fin if
		 			 	
	 	} // fin function
	 	
	 	public function AddCpDatosPersonas($CodigoPostal,$ID_USR){
		 	
		 	$Query = "SELECT codigos_postales.ID_Colonia,
		 					 codigos_postales.ID_Municipio,
		 					 codigos_postales.ID_Ciudad,
		 					 codigos_postales.ID_Estado,
		 					 codigos_postales.Colonia
		 				FROM codigos_postales
		 			   WHERE codigos_postales.CP = '".$CodigoPostal."' ";
	 				   
	 				    $RESPUESTA   = $this->db->Execute($Query); // debug($Query);
	 					$ID_Colonia  = $RESPUESTA->fields["ID_Colonia"];
	 					$ID_Estado   = $RESPUESTA->fields["ID_Estado"];


	 		$Query = "SELECT ID_ciudad_pld
	 					FROM pld_cat_codigos_postales_riesgo
	 				   WHERE pld_cat_codigos_postales_riesgo.CP = '".$CodigoPostal."' ";	
	 				   
	 				    $RESPUESTA      = $this->db->Execute($Query); // debug($Query);
	 					$ID_ciudad_pld  = $RESPUESTA->fields["ID_ciudad_pld"];
		
	 		if( !empty($ID_ciudad_pld) ){
		 		 echo "YA EXISTE";
		 		 die();
	 		} // fin if			


	 		if( !empty($ID_Colonia) ){			
	 		
	 		$Query = "INSERT INTO pld_cat_codigos_postales_riesgo (ID_Usr,Fecha_sistema,Fecha,ID_Estado,CP)
	 					   VALUES
	 					   		 (".$ID_USR.", NOW(), NOW(), '".$ID_Estado."' , '".$CodigoPostal."')";
	 			
	 			 $this->db->Execute($Query);  //debug($Query);

                $id_insert = $this->db->_insertid();

                $Query = "INSERT INTO pld_catalogos_log(ID_registro,ID_Usr,Tipo,Accion)
		 						   VALUES (".$id_insert.", '".$ID_USR."', 'CP','INSERT' )";
                $this->db->Execute($Query); // debug($Query);

                echo "LISTO";
	 	
	 		}else{
		 		 echo "NO EXISTE";
	 		}	 
	
		 	
	 	} // fin function
	 	
	 	public function ConsultaCpDatosPersonas($Cp,$Evento,$Pagina){

            $muestra = $this->muestra_global;
            $QueryWhere = "";
		 if( !empty($Cp) ){
		 	$QueryWhere = "WHERE pld_cat_codigos_postales_riesgo.CP LIKE '%".$Cp."%'";
	 	 } // fin if		

            $Query_count = "SELECT count(*) as Total
									FROM pld_cat_codigos_postales_riesgo $QueryWhere ";

            $RESPUESTA      = $this->db->Execute($Query_count);
            $total_registros = $RESPUESTA->fields["Total"];
            $total_paginas = $total_registros/$muestra;
            $total_paginas = ceil($total_paginas);

            if($Pagina>$total_paginas)
            {
                $Pagina = 1;
            }

            if (!$Pagina or $Pagina == 0) {
                $limite_ini = 0;
                $Pagina = 1;
            }
            else {
                if($Evento == "Prev"){
                    $Pagina--;
                    if($Pagina == 0)
                    {
                        $Pagina = 1;
                    }
                }elseif($Evento == "Next")
                {
                    $Pagina++;
                    if($Pagina>$total_paginas)
                    {
                        $Pagina--;
                    }
                }elseif($Evento == "First")
                {
                    $Pagina = 1;
                }elseif($Evento == "Last")
                {
                    $Pagina = $total_paginas;
                }else
                {
                    $limite_ini = ($Pagina) * $muestra;
                }

                $limite_ini = ($Pagina-1) * $muestra;

            }

            if($limite_ini < 0)
            {
                $limite_ini = 0;
            }

            $limite = "LIMIT ".$limite_ini.", ".$muestra;

		 	$Query = "SELECT 
		 					 pld_cat_codigos_postales_riesgo.ID_ciudad_pld,
		 					 pld_cat_codigos_postales_riesgo.ID_Usr,
		 					 pld_cat_codigos_postales_riesgo.Fecha_sistema,
		 					 pld_cat_codigos_postales_riesgo.Fecha,
		 					 pld_cat_codigos_postales_riesgo.ID_Estado,
		 					 pld_cat_codigos_postales_riesgo.CP
		 				FROM pld_cat_codigos_postales_riesgo
		 				$QueryWhere	
		 			GROUP BY pld_cat_codigos_postales_riesgo.CP
		 			ORDER BY ID_ciudad_pld DESC
				   	   $limite ";
        
			 $RESPUESTA      = $this->db->Execute($Query); // debug($Query);
        	 
        	 
        	 while( !$RESPUESTA->EOF ) { 
						
				 $ID_ciudad_pld = $RESPUESTA->fields["ID_ciudad_pld"];
	        	 $ID_Usr        = $RESPUESTA->fields["ID_Usr"];
	        	 $Fecha_sistema = $RESPUESTA->fields["Fecha_sistema"];
	        	 $Fecha         = $RESPUESTA->fields["Fecha"];
	        	 $ID_Estado     = $RESPUESTA->fields["ID_Estado"];
	        	 $CP            = $RESPUESTA->fields["CP"];
	        	 		
			  $htmlTbody .= '
			  
			  	<tr>
					<td>'.$CP.'</td>
					<td>'.$Fecha.'</td>
					<td align="center"></td>
					<td align="center"><a class="ui mini red button" onclick="EliminarCp('.$ID_ciudad_pld.');">Eliminar</a></td>
				</tr>
			  
			  ';
			   
			   
			   $RESPUESTA->MoveNext(); 
			 } // fin while( !$RESPUESTA->EOF ) {
	 		
	 		$html = ' 
        	  
        	  <table align="center" width="100%" class="ui table segment">
				<thead>
					<tr>
						<th width="25%">CODIGO POSTAL</th>
						<th width="250%">FECHA DE ALTA</th>
						<th width="20%"></th>
						<th width="20%"></th>
					</tr>
				</thead>
				'.$htmlTbody.'
			</table>';

            $html .= "
            <div class='ui'>
                <i class='big step backward icon' onclick='ActualizaLista(\"First\",".$Pagina.")'></i>
                <i class='big backward icon' onclick='ActualizaLista(\"Prev\",".$Pagina.")'></i>

                <div class='ui small icon input'>
                    <input size='4' type='text' id='Paginacion' onchange='CambiaPagina(this)' value='".$Pagina."'>
                </div>

                <i class='big forward icon' onclick='ActualizaLista(\"Next\",".$Pagina.")'></i>
                <i class='big step forward icon' onclick='ActualizaLista(\"Last\",".$Pagina.")'></i>
            </div>";

            echo  $html;
		 	
	 	} // fin function
	 	
	 	public function DeletePaiseDatosPersonas($Pais,$Evento,$Pagina){

            $muestra = $this->muestra_global;
            $QueryWhere = "";
		 if( !empty($Pais) ){
		 	$QueryWhere = " WHERE cat_paises.Pais LIKE '%".$Pais."%' ";
	 	 } // fin if		
            
            $Query_count = "SELECT
                            count(*) as Total
                       FROM pld_cat_paraisos_fiscales
                 INNER JOIN cat_paises ON cat_paises.ID_pais = pld_cat_paraisos_fiscales.ID_Pais
                 $QueryWhere";
            
            $RESPUESTA      = $this->db->Execute($Query_count);
            $total_registros = $RESPUESTA->fields["Total"];
            $total_paginas = $total_registros/$muestra;
            $total_paginas = ceil($total_paginas);

            if($Pagina>$total_paginas)
            {
                $Pagina = 1;
            }

            if (!$Pagina or $Pagina == 0) {
                $limite_ini = 0;
                $Pagina = 1;
            }
            else {
                if($Evento == "Prev"){
                    $Pagina--;
                    if($Pagina == 0)
                    {
                        $Pagina = 1;
                    }
                }elseif($Evento == "Next")
                {
                    $Pagina++;
                    if($Pagina>$total_paginas)
                    {
                        $Pagina--;
                    }
                }elseif($Evento == "First")
                {
                    $Pagina = 1;
                }elseif($Evento == "Last")
                {
                    $Pagina = $total_paginas;
                }else
                {
                    $limite_ini = ($Pagina) * $muestra;
                }

                $limite_ini = ($Pagina-1) * $muestra;

            }

            if($limite_ini < 0)
            {
                $limite_ini = 0;
            }

            $limite = "LIMIT ".$limite_ini.", ".$muestra;
            
            $Query = "SELECT
                            cat_paises.ID_pais,
                            cat_paises.Pais,
                            pld_cat_paraisos_fiscales.motivo
                       FROM pld_cat_paraisos_fiscales
                 INNER JOIN cat_paises ON cat_paises.ID_pais = pld_cat_paraisos_fiscales.ID_Pais
                 $QueryWhere
                    GROUP BY cat_paises.ID_pais
		 			ORDER BY cat_paises.ID_pais DESC
                 $limite
                 ";
            
		 	 $RESPUESTA      = $this->db->Execute($Query);  //debug($Query);
        	 
        	 
        	 while( !$RESPUESTA->EOF ) { 
						
				 $ID_pais = $RESPUESTA->fields["ID_pais"];
	        	 $Pais    = $RESPUESTA->fields["Pais"];
	        	 $motivo  = $RESPUESTA->fields["motivo"];
	        	 		
			  $htmlTbody .= '
			  
			  	<tr>
					<td>'.$Pais.'</td>
					<td>'.$motivo.'</td>
					<td align="center"></td>
					<td align="center"><a class="ui mini red button" onclick="EliminarPais('.$ID_pais.');">Eliminar</a></td>
				</tr>
			  
			  ';
			   
			   $RESPUESTA->MoveNext(); 
			 } // fin while( !$RESPUESTA->EOF ) {
	 		
	 		$html = ' 
        	  
        	  <table align="center" width="100%" class="ui table segment">
				<thead>
					<tr>
						<th width="25%">PAÍSES</th>
						<th width="250%">MOTIVO</th>
						<th width="20%"></th>
						<th width="20%"></th>
					</tr>
				</thead>
				'.$htmlTbody.'
			</table>';

            $html .= "
            <div class='ui'>
                <i class='big step backward icon' onclick='ActualizaLista(\"First\",".$Pagina.")'></i>
                <i class='big backward icon' onclick='ActualizaLista(\"Prev\",".$Pagina.")'></i>

                <div class='ui small icon input'>
                    <input size='4' type='text' id='Paginacion' onchange='CambiaPagina(this)' value='".$Pagina."'>
                </div>

                <i class='big forward icon' onclick='ActualizaLista(\"Next\",".$Pagina.")'></i>
                <i class='big step forward icon' onclick='ActualizaLista(\"Last\",".$Pagina.")'></i>
            </div>";

            echo  $html;
		 	
	 	} // fin function
	 	
	 	public function DeleteCpDatosPersonas($id,$ID_USR){
		 	
		 	if(!empty($id)){

                $Query = "SELECT  ID_ciudad_pld,  ID_Usr,  Fecha_sistema,  Fecha,  ID_Estado, CP FROM pld_cat_codigos_postales_riesgo
                WHERE pld_cat_codigos_postales_riesgo.ID_ciudad_pld = '".$id."' ";

                $RESPUESTA      = $this->db->Execute($Query);  // debug($Query);

                $ID_ciudad_pld   = $RESPUESTA->fields["ID_ciudad_pld"];
                $ID_Usr          = $RESPUESTA->fields["ID_Usr"];
                $Fecha_sistema   = $RESPUESTA->fields["Fecha_sistema"];
                $Fecha           = $RESPUESTA->fields["Fecha"];
                $ID_Estado       = $RESPUESTA->fields["ID_Estado"];
                $CP              = $RESPUESTA->fields["CP"];

                $QueryRespaldo = "INSERT INTO pld_cat_codigos_postales_riesgo (ID_ciudad_pld,  ID_Usr,  Fecha_sistema,  Fecha,  ID_Estado, CP)
                VALUES ( ".$ID_ciudad_pld.", '.$ID_Usr.', '".$Fecha_sistema."', '".$Fecha."', '".$ID_Estado."', '".$CP."' ) ";

			 	$Query = "DELETE FROM pld_cat_codigos_postales_riesgo WHERE ID_ciudad_pld = '".$id."'";
			 	$this->db->Execute($Query);  //debug($Query);

                $QueryRespaldo=str_replace("'","\'",$QueryRespaldo);
                $Query = "INSERT INTO pld_catalogos_log(ID_registro,ID_Usr,Tipo,Accion,sentencia)
		 						   VALUES (".$id.", '".$ID_USR."', 'CP','DELETE', '".$QueryRespaldo."' )";
                $this->db->Execute($Query); // debug($Query);


			 	echo "LISTO";
			}else{
				echo "ERROR";
			}
		 	
	 	} // fin function
	 	
	 	public function AddEstadosRiesgososDatosPersonas($EstadosRiesgos,$ID_USR){
		 	
		 	$Query = "SELECT COUNT(pld_cat_estados_riesgo.ID_edo_pld) AS ID_edo_pld
	 					FROM pld_cat_estados_riesgo
	 				   WHERE pld_cat_estados_riesgo.ID_Estado = '".$EstadosRiesgos."' ";
	 				   
	 				    $RESPUESTA      = $this->db->Execute($Query); // debug($Query);
	 					$ID_edo_pld     = $RESPUESTA->fields["ID_edo_pld"];
	 			
	 		$Query = "SELECT 
	 						 estados_finafim.Edo_id
	 					FROM estados_finafim
	 				   WHERE estados_finafim.ID_Estado = '".$EstadosRiesgos."' ";
	 				   
	 				    $RESPUESTA  = $this->db->Execute($Query); // debug($Query);
	 					$Edo_id     = $RESPUESTA->fields["Edo_id"];	
	 					
	 			if( !empty($ID_edo_pld) ){
		 			echo "YA EXISTE";
		 		    die();
		 		} // fin if		
	 					
	 			if( empty($ID_edo_pld) ){
		 			
		 			$Query = "INSERT INTO pld_cat_estados_riesgo (ID_Usr,Fecha_sistema,Fecha,ID_Estado,cve_estado)
	 					   VALUES
	 					   		 (".$ID_USR.", NOW(), NOW(), '".$EstadosRiesgos."','".$Edo_id."')";
	 					   		 
	 					   $this->db->Execute($Query); // debug($Query);

                    $id_insert = $this->db->_insertid();

                    $Query = "INSERT INTO pld_catalogos_log(ID_registro,ID_Usr,Tipo,Accion)
		 						   VALUES (".$id_insert.", '".$ID_USR."', 'ESTADOS','INSERT' )";
                    $this->db->Execute($Query); // debug($Query);

	 				echo "LISTO";	   		 
	 			}else{
		 			echo "ERROR";
	 			} // fin if		
		 	
	 	} // fin function
	 	
	 	function ConsultaEstadoRiesgo($EstadoRiesgo,$Evento,$Pagina){

            $muestra = $this->muestra_global;
            $QueryWhere = "";

            if( !empty($EstadoRiesgo) ){

			  $Query = "SELECT
			  				   estados.id_estado 
			  			  FROM estados
			  			 WHERE estados.nombre LIKE ('%".$EstadoRiesgo."%')";
			  		
			  		 $RESPUESTA  = $this->db->Execute($Query); // debug($Query);
                    $con = 0;
                    while( !$RESPUESTA->EOF ) {
                        if($con==0){
                            $id_estado  .= "'".$RESPUESTA->fields["id_estado"]."'";
                        }else{
                            $id_estado  .= ", '".$RESPUESTA->fields["id_estado"]."'";
                        }
                        $con++;
                        $RESPUESTA->MoveNext();
                    }
                if($con == 0)
                {
                    $id_estado = "''";
                }
			  	
			  	$QueryWhere = "WHERE pld_cat_estados_riesgo.ID_Estado IN ($id_estado) ";
			  
		  }	// fin if

            $Query_count = "SELECT count(*) as Total
									FROM pld_cat_estados_riesgo $QueryWhere ";

            $RESPUESTA      = $this->db->Execute($Query_count);
            $total_registros = $RESPUESTA->fields["Total"];
            $total_paginas = $total_registros/$muestra;
            $total_paginas = ceil($total_paginas);

            if($Pagina>$total_paginas)
            {
                $Pagina = 1;
            }

            if (!$Pagina or $Pagina == 0) {
                $limite_ini = 0;
                $Pagina = 1;
            }
            else {
                if($Evento == "Prev"){
                    $Pagina--;
                    if($Pagina == 0)
                    {
                        $Pagina = 1;
                    }
                }elseif($Evento == "Next")
                {
                    $Pagina++;
                    if($Pagina>$total_paginas)
                    {
                        $Pagina--;
                    }
                }elseif($Evento == "First")
                {
                    $Pagina = 1;
                }elseif($Evento == "Last")
                {
                    $Pagina = $total_paginas;
                }else
                {
                    $limite_ini = ($Pagina) * $muestra;
                }

                $limite_ini = ($Pagina-1) * $muestra;

            }

            if($limite_ini < 0)
            {
                $limite_ini = 0;
            }

            $limite = "LIMIT ".$limite_ini.", ".$muestra;


            $Query = "SELECT
        					 pld_cat_estados_riesgo.ID_edo_pld,
							 pld_cat_estados_riesgo.ID_Usr,
							 pld_cat_estados_riesgo.Fecha_sistema,
							 pld_cat_estados_riesgo.Fecha,
							 pld_cat_estados_riesgo.ID_Estado,
							 pld_cat_estados_riesgo.cve_estado,
							 estados.nombre AS NombreEstado
						FROM pld_cat_estados_riesgo
				  INNER JOIN estados ON estados.id_estado = pld_cat_estados_riesgo.ID_Estado
				  $QueryWhere
				    ORDER BY ID_edo_pld DESC
				   	   $limite ";
					   
			 $RESPUESTA  = $this->db->Execute($Query);   //debug($Query);
			
			 while( !$RESPUESTA->EOF ) { 
			 
			  	$ID_edo_pld     = $RESPUESTA->fields["ID_edo_pld"];
			  	$ID_Usr         = $RESPUESTA->fields["ID_Usr"];
			  	$Fecha_sistema  = $RESPUESTA->fields["Fecha_sistema"];
			  	$Fecha          = $RESPUESTA->fields["Fecha"];
			  	$ID_Estado      = $RESPUESTA->fields["ID_Estado"];
			  	$cve_estado     = $RESPUESTA->fields["cve_estado"];
			  	$NombreEstado   = $RESPUESTA->fields["NombreEstado"];
	        	 		
				  $htmlTbody .= '
				  
				  	<tr>
						<td>'.$NombreEstado.'</td>
						<td>'.$Fecha_sistema.'</td>
						<td align="center"></td>
						<td align="center"><a class="ui mini red button" onclick="EliminarEstadoRiesgo('.$ID_edo_pld.');">Eliminar</a></td>
					</tr>
				  
				  ';			  
			  
			  $RESPUESTA->MoveNext(); 
			 } // fin while( !$RESPUESTA->EOF ) {
		 	
		 	$html = ' 
        	  
        	  <table align="center" width="100%" class="ui table segment">
				<thead>
					<tr>
						<th width="25%">ESTADO</th>
						<th width="250%">FECHA DE REGISTRO</th>
						<th width="20%"></th>
						<th width="20%"></th>
					</tr>
				</thead>
				'.$htmlTbody.'
			</table>';

            $html .= "
            <div class='ui'>
                <i class='big step backward icon' onclick='ActualizaLista(\"First\",".$Pagina.")'></i>
                <i class='big backward icon' onclick='ActualizaLista(\"Prev\",".$Pagina.")'></i>

                <div class='ui small icon input'>
                    <input size='4' type='text' id='Paginacion' onchange='CambiaPagina(this)' value='".$Pagina."'>
                </div>

                <i class='big forward icon' onclick='ActualizaLista(\"Next\",".$Pagina.")'></i>
                <i class='big step forward icon' onclick='ActualizaLista(\"Last\",".$Pagina.")'></i>
            </div>";


            echo  $html;
		 	
	 	} // fin function
	 	
	 	function EliminarEstadoRiesgo($id,$ID_USR){
		 	
		 	if( !empty($id) ){

                $Query = "SELECT ID_edo_pld, ID_Usr, Fecha_sistema, Fecha, ID_Estado, cve_estado FROM pld_cat_estados_riesgo
                WHERE pld_cat_estados_riesgo.ID_edo_pld = '".$id."' ";

                $RESPUESTA      = $this->db->Execute($Query);  // debug($Query);

                $ID_edo_pld      = $RESPUESTA->fields["ID_edo_pld"];
                $ID_Usr          = $RESPUESTA->fields["ID_Usr"];
                $Fecha_sistema   = $RESPUESTA->fields["Fecha_sistema"];
                $Fecha           = $RESPUESTA->fields["Fecha"];
                $ID_Estado       = $RESPUESTA->fields["ID_Estado"];
                $cve_estado      = $RESPUESTA->fields["cve_estado"];

                $QueryRespaldo = "INSERT INTO pld_cat_estados_riesgo (ID_edo_pld, ID_Usr, Fecha_sistema, Fecha, ID_Estado, cve_estado )
                VALUES ( ".$ID_edo_pld.", '.$ID_Usr.', '".$Fecha_sistema."', '".$Fecha."', '".$ID_Estado."', '".$cve_estado."' ) ";

			 	$Query = "DELETE FROM pld_cat_estados_riesgo WHERE ID_edo_pld IN ('$id'); ";
			 	$this->db->Execute($Query);  //debug($Query);

                $QueryRespaldo=str_replace("'","\'",$QueryRespaldo);
                $Query = "INSERT INTO pld_catalogos_log(ID_registro,ID_Usr,Tipo,Accion,sentencia)
		 						   VALUES (".$id.", '".$ID_USR."', 'ESTADOS','DELETE', '".$QueryRespaldo."' )";
                $this->db->Execute($Query); // debug($Query);

			 	echo "LISTO";
			}else{
				echo "ERROR";
			}

	 	} // fin if
	 	
	 	
	 	function AddCiudadRiesgososDatosPersonas($arrDatos,$ID_USR){
		 	
		    $Query = "SELECT 
		    				 COUNT(pld_cat_ciudades_riesgo.ID_codigo_pld) AS ID_codigo_pld
						FROM pld_cat_ciudades_riesgo
					   WHERE pld_cat_ciudades_riesgo.ID_Estado = '".$arrDatos[1]."' 
					     AND pld_cat_ciudades_riesgo.ID_Ciudad = '".$arrDatos[0]."'";
		    
		   		    $RESPUESTA      = $this->db->Execute($Query); // debug($Query);
	 				$ID_codigo_pld  = $RESPUESTA->fields["ID_codigo_pld"];
		 	
		 	if( !empty($ID_codigo_pld) ){
		 			echo "YA EXISTE";
		 		    die();
		 	} // fin if
		 	
		 	if( empty($ID_codigo_pld) ){
			 	
			 	$Query = "INSERT INTO pld_cat_ciudades_riesgo (ID_Usr, Fecha_sistema, Fecha, ID_Estado, ID_Ciudad) 
			 				   VALUES ('".$ID_USR."', NOW(), NOW(), '".$arrDatos[1]."', '".$arrDatos[0]."') ";
			 			$this->db->Execute($Query);  //debug($Query);
			 			echo "LISTO";

                $id_insert = $this->db->_insertid();

                $Query = "INSERT INTO pld_catalogos_log(ID_registro,ID_Usr,Tipo,Accion)
		 						   VALUES (".$id_insert.", '".$ID_USR."', 'CIUDADES','INSERT' )";
                $this->db->Execute($Query); // debug($Query);

			}else{
		 		echo "ERROR";
	 		} // fin if
	 	} // fin if
	 	
	 	function ConsultaCiudadRiesgoShow($CiudadRiesgo,$Evento,$Pagina){

            $muestra = $this->muestra_global;
            $QueryWhere = "";

            if( !empty($CiudadRiesgo) ){
			  
			  $CiudadRiesgo = str_replace(" ", "%", $CiudadRiesgo);


                $QueryWhere = "WHERE  ciudades.Nombre like ('%".$CiudadRiesgo."%') ";
			  
		  }	// fin if


            $Query_count = "SELECT count(*) as Total
									FROM pld_cat_ciudades_riesgo
									INNER JOIN ciudades ON ciudades.ID_Ciudad = pld_cat_ciudades_riesgo.ID_Ciudad
									INNER JOIN estados ON estados.ID_Estado = pld_cat_ciudades_riesgo.ID_Estado
									AND estados.ID_Estado = ciudades.ID_Estado

									 $QueryWhere
									";


            $RESPUESTA      = $this->db->Execute($Query_count); //debug($Query_count);
            $total_registros = $RESPUESTA->fields["Total"];
            $total_paginas = $total_registros/$muestra;
            $total_paginas = ceil($total_paginas);

            if($Pagina>$total_paginas)
            {
                $Pagina = 1;
            }

            if (!$Pagina or $Pagina == 0) {
                $limite_ini = 0;
                $Pagina = 1;
            }
            else {
                if($Evento == "Prev"){
                    $Pagina--;
                    if($Pagina == 0)
                    {
                        $Pagina = 1;
                    }
                }elseif($Evento == "Next")
                {
                    $Pagina++;
                    if($Pagina>$total_paginas)
                    {
                        $Pagina--;
                    }
                }elseif($Evento == "First")
                {
                    $Pagina = 1;
                }elseif($Evento == "Last")
                {
                    $Pagina = $total_paginas;
                }else
                {
                    $limite_ini = ($Pagina) * $muestra;
                }

                $limite_ini = ($Pagina-1) * $muestra;

            }

            if($limite_ini < 0)
            {
                $limite_ini = 0;
            }
            $limite = "LIMIT ".$limite_ini.", ".$muestra;

		 	
		 	$Query = "SELECT 
        					 pld_cat_ciudades_riesgo.ID_codigo_pld,
							 pld_cat_ciudades_riesgo.ID_Usr,
							 pld_cat_ciudades_riesgo.Fecha_sistema,
							 pld_cat_ciudades_riesgo.Fecha,
							 pld_cat_ciudades_riesgo.ID_Estado,
							 pld_cat_ciudades_riesgo.ID_Ciudad,
							 ciudades.Nombre AS NombreCiudad,
							 estados.nombre AS NombreEstado
						FROM pld_cat_ciudades_riesgo
				  INNER JOIN ciudades ON ciudades.ID_Ciudad = pld_cat_ciudades_riesgo.ID_Ciudad
				  INNER JOIN estados ON estados.ID_Estado = pld_cat_ciudades_riesgo.ID_Estado
				  AND estados.ID_Estado = ciudades.ID_Estado

				    $QueryWhere

				  ORDER BY pld_cat_ciudades_riesgo.Fecha_sistema DESC
				     $limite ";
		 			   
			 $RESPUESTA  = $this->db->Execute($Query);   //debug($Query);
			
			 while( !$RESPUESTA->EOF ) { 
			 
			  	$ID_codigo_pld  = $RESPUESTA->fields["ID_codigo_pld"];
			  	$ID_Usr         = $RESPUESTA->fields["ID_Usr"];
			  	$Fecha_sistema  = $RESPUESTA->fields["Fecha_sistema"];
			  	$Fecha          = $RESPUESTA->fields["Fecha"];
			  	$ID_Estado      = $RESPUESTA->fields["ID_Estado"];
			  	$ID_Ciudad      = $RESPUESTA->fields["ID_Ciudad"];
			  	$NombreCiudad   = $RESPUESTA->fields["NombreCiudad"];
			  	$NombreEstado   = $RESPUESTA->fields["NombreEstado"];
	        	 		
				  $htmlTbody .= '
				  
				  	<tr>
						<td>'.$NombreEstado.'</td>
						<td>'.$NombreCiudad.'</td>
						<td align="center">'.$Fecha_sistema.'</td>
						<td align="center"><a class="ui mini red button" onclick="EliminarCiudadRiesgo('.$ID_codigo_pld.');">Eliminar</a></td>
					</tr>
				  
				  ';			  
			  
			  $RESPUESTA->MoveNext(); 
			 } // fin while( !$RESPUESTA->EOF ) {
		 	
		 	$html = ' 
        	  
        	  <table align="center" width="100%" class="ui table segment">
				<thead>
					<tr>
						<th width="25%">ESTADO</th>
						<th width="25%">CIUDAD</th>
						<th width="20%">FECHA DE REGISTRO</th>
						<th width="20%"></th>
					</tr>
				</thead>
				'.$htmlTbody.'
			</table>';

            $html .= "
            <div class='ui'>
                <i class='big step backward icon' onclick='ActualizaLista(\"First\",".$Pagina.")'></i>
                <i class='big backward icon' onclick='ActualizaLista(\"Prev\",".$Pagina.")'></i>

                <div class='ui small icon input'>
                    <input size='4' type='text' id='Paginacion' onchange='CambiaPagina(this)' value='".$Pagina."'>
                </div>

                <i class='big forward icon' onclick='ActualizaLista(\"Next\",".$Pagina.")'></i>
                <i class='big step forward icon' onclick='ActualizaLista(\"Last\",".$Pagina.")'></i>
            </div>";

            echo  $html;
		 	
		 	
	 	} // fin function
	 	
	 	public function EliminarCiudadRiesgo($id,$ID_USR){
		 	
		 	if( !empty($id) ){

			 	if(!empty($id)){


                    $Query = "SELECT  ID_codigo_pld,  ID_Usr,  Fecha_sistema,  Fecha,  ID_Estado, ID_Ciudad FROM pld_cat_ciudades_riesgo
                     WHERE pld_cat_ciudades_riesgo.ID_codigo_pld = '".$id."' ";

                    $RESPUESTA      = $this->db->Execute($Query);  // debug($Query);

                    $ID_codigo_pld   = $RESPUESTA->fields["ID_codigo_pld"];
                    $ID_Usr          = $RESPUESTA->fields["ID_Usr"];
                    $Fecha_sistema   = $RESPUESTA->fields["Fecha_sistema"];
                    $Fecha           = $RESPUESTA->fields["Fecha"];
                    $ID_Estado       = $RESPUESTA->fields["ID_Estado"];
                    $ID_Ciudad       = $RESPUESTA->fields["ID_Ciudad"];

                    $QueryRespaldo = "INSERT INTO pld_cat_ciudades_riesgo (ID_codigo_pld,  ID_Usr,  Fecha_sistema,  Fecha,  ID_Estado, ID_Ciudad)
                    VALUES ( ".$ID_codigo_pld.", '.$ID_Usr.', '".$Fecha_sistema."', '".$Fecha."', '".$ID_Estado."', '".$ID_Ciudad."' ) ";

			 		$Query = "DELETE FROM pld_cat_ciudades_riesgo WHERE ID_codigo_pld = '".$id."'";
			 		$this->db->Execute($Query);  //debug($Query);

                    $QueryRespaldo=str_replace("'","\'",$QueryRespaldo);
                    $Query = "INSERT INTO pld_catalogos_log(ID_registro,ID_Usr,Tipo,Accion,sentencia)
		 						   VALUES (".$id.", '".$ID_USR."', 'CIUDADES','DELETE', '".$QueryRespaldo."' )";
                    $this->db->Execute($Query); // debug($Query);


                    echo "LISTO";
			 	}else{
					echo "ERROR";
				}
			 	
		 	} // fin if
		 	
	 	} // fi function
	 	
	 	public function AddGiroRiesgososDatosPersonas($arrDatos,$ID_USR){


            if( !empty($arrDatos[3]) ){
                $Query = " UPDATE pld_cat_giro_negocio  SET
                                ID_Usr   = '".$ID_USR."',
                                Giro     = '".mb_strtoupper($arrDatos[0])."',
                                Tipo     = '".$arrDatos[1]."',
                                Estatus  = '".$arrDatos[2]."',
                                CVE_SARE = '1'
                            WHERE ID_Giro_negocio = '".$arrDatos[3]."' ";
                $this->db->Execute($Query);  //debug($Query);

                $Query = "INSERT INTO pld_catalogos_log(ID_registro,ID_Usr,Tipo,Accion)
		 						   VALUES (".$arrDatos[3].", '".$ID_USR."', 'ACTIVIDADES','UPDATE' )";
                $this->db->Execute($Query); // debug($Query);


                echo "LISTO";
            }else{
                $Query = "SELECT
		 						 COUNT(pld_cat_giro_negocio.ID_Giro_negocio) AS ID_Giro_negocio
		 					FROM pld_cat_giro_negocio 
		 				   WHERE pld_cat_giro_negocio.Giro = '".$arrDatos[0]."' ";

                $RESPUESTA        = $this->db->Execute($Query); // debug($Query);
                $ID_Giro_negocio  = $RESPUESTA->fields["ID_Giro_negocio"];

                if( !empty($ID_Giro_negocio) ){
                    echo "EXISTE";
                    die();
                } // fin if

                $Query = "INSERT INTO pld_cat_giro_negocio (ID_Usr, Fecha_sistema, Fecha, Giro, Tipo, Estatus, CVE_SARE)
		 				   VALUES ('".$ID_USR."', NOW(), NOW(), '".mb_strtoupper($arrDatos[0])."', '".$arrDatos[1]."', '".$arrDatos[2]."' , '1') ";
                $this->db->Execute($Query);  //debug($Query);

                $id_insert = $this->db->_insertid();

                $Query = "INSERT INTO pld_catalogos_log(ID_registro,ID_Usr,Tipo,Accion)
		 						   VALUES (".$id_insert.", '".$ID_USR."', 'ACTIVIDADES','INSERT' )";
                $this->db->Execute($Query); // debug($Query);

                echo "LISTO";

            }
        } // fin function
	 	
	 	
	 	public function ConsultaGiroRiesgoShow($GiroRiesgo,$Evento,$Pagina){

            $muestra = $this->muestra_global;
            $QueryWhere = "";

            if( !empty($GiroRiesgo) ){
			 	$QueryWhere = "WHERE pld_cat_giro_negocio.Giro LIKE ('%".$GiroRiesgo."%')  ";
			} // fin if

            $Query_count = "SELECT count(*) as Total
									FROM pld_cat_giro_negocio $QueryWhere ";

            $RESPUESTA      = $this->db->Execute($Query_count);
            $total_registros = $RESPUESTA->fields["Total"];
            $total_paginas = $total_registros/$muestra;
            $total_paginas = ceil($total_paginas);

            if($Pagina>$total_paginas)
            {
                $Pagina = 1;
            }

            if (!$Pagina or $Pagina == 0) {
                $limite_ini = 0;
                $Pagina = 1;
            }
            else {
                if($Evento == "Prev"){
                    $Pagina--;
                    if($Pagina == 0)
                    {
                        $Pagina = 1;
                    }
                }elseif($Evento == "Next")
                {
                    $Pagina++;
                    if($Pagina>$total_paginas)
                    {
                        $Pagina--;
                    }
                }elseif($Evento == "First")
                {
                    $Pagina = 1;
                }elseif($Evento == "Last")
                {
                    $Pagina = $total_paginas;
                }else
                {
                    $limite_ini = ($Pagina) * $muestra;
                }

                $limite_ini = ($Pagina-1) * $muestra;

            }

            if($limite_ini < 0)
            {
                $limite_ini = 0;
            }

            $limite = "LIMIT ".$limite_ini.", ".$muestra;

            $Query = "SELECT
		 					 pld_cat_giro_negocio.ID_Giro_negocio,
		 					 pld_cat_giro_negocio.ID_Usr,
		 					 pld_cat_giro_negocio.Fecha_sistema,
		 					 pld_cat_giro_negocio.Fecha,
		 					 pld_cat_giro_negocio.Giro,
		 					 pld_cat_giro_negocio.Tipo,
		 					 pld_cat_giro_negocio.Estatus
		 				FROM pld_cat_giro_negocio
		 				$QueryWhere
		 			ORDER BY pld_cat_giro_negocio.ID_Giro_negocio DESC
		 			   $limite ";
		 			   
			 $RESPUESTA  = $this->db->Execute($Query);  // debug($Query);
			
			 while( !$RESPUESTA->EOF ) { 
			 
			  	$ID_Giro_negocio  = $RESPUESTA->fields["ID_Giro_negocio"];
			  	$ID_Usr           = $RESPUESTA->fields["ID_Usr"];
			  	$Fecha_sistema    = $RESPUESTA->fields["Fecha_sistema"];
			  	$Fecha            = $RESPUESTA->fields["Fecha"];
			  	$Giro             = $RESPUESTA->fields["Giro"];
			  	$Tipo             = $RESPUESTA->fields["Tipo"];
                $Estatus          = $RESPUESTA->fields["Estatus"];
			  	 
				 $btn_eliminar = '';
                 $btn_editar   = '';
                 $class        = '';
				 
				 if($ID_Giro_negocio > 0)
				 {
					 $btn_eliminar = '<a class="ui mini red button" onclick="EliminarGiroRiesgo('.$ID_Giro_negocio.');">Eliminar</a>';
                     $btn_editar = '<a class="ui mini green button" onclick="EditarGiroRiesgo('.$ID_Giro_negocio.');">Editar</a>';
				 }

                 if($Estatus == "INACTIVO")
                 {
                     $class = 'class="error" ';
                 }else{
                     $class = 'class="positive" ';
                 }
				 		
				  $htmlTbody .= '
				  
				  	<tr '.$class.'>
						<td>'.$Giro.'</td>
						<td>'.$Tipo.'</td>
						<td>'.$Estatus.'</td>
						<td align="center">'.$Fecha_sistema.'</td>
						<td align="center">'.$btn_editar.'</td>
						<td align="center">'.$btn_eliminar.'</td>
					</tr>
				  
				  ';			  
			  
			  $RESPUESTA->MoveNext(); 
			 } // fin while( !$RESPUESTA->EOF ) {
		 	
		 	$html = ' 
        	  
        	  <table align="center" width="100%" class="ui table segment">
				<thead>
					<tr>
						<th width="25%">TIPO DE ACTIVIDAD</th>
						<th width="25%">GRADO DE RIESGO</th>
						<th width="25%">ESTATUS</th>
						<th width="20%">FECHA DE REGISTRO</th>
						<th width="20%"></th>
						<th width="20%"></th>
					</tr>
				</thead>
				'.$htmlTbody.'
			</table>';

            $html .= "
            <div class='ui'>
                <i class='big step backward icon' onclick='ActualizaLista(\"First\",".$Pagina.")'></i>
                <i class='big backward icon' onclick='ActualizaLista(\"Prev\",".$Pagina.")'></i>

                <div class='ui small icon input'>
                    <input size='4' type='text' id='Paginacion' onchange='CambiaPagina(this)' value='".$Pagina."'>
                </div>

                <i class='big forward icon' onclick='ActualizaLista(\"Next\",".$Pagina.")'></i>
                <i class='big step forward icon' onclick='ActualizaLista(\"Last\",".$Pagina.")'></i>
            </div>";
		 	echo  $html;
		 	
		 	
	 	} // fin function
	 	
	 	public function EliminarGiroRiesgo($id,$ID_USR){
		 	
		 	if( !empty($id) ){
			 	
				 if(!empty($id)){

                     $Query = "SELECT ID_Giro_negocio, ID_Usr, Fecha_sistema, Fecha, Giro, Tipo, Estatus, CVE_SARE
                     FROM pld_cat_giro_negocio
                      WHERE ID_Giro_negocio = '".$id."' ";

                     $RESPUESTA      = $this->db->Execute($Query);  // debug($Query);

                     $ID_Giro_negocio = $RESPUESTA->fields["ID_Giro_negocio"];
                     $ID_Usr          = $RESPUESTA->fields["ID_Usr"];
                     $Fecha_sistema   = $RESPUESTA->fields["Fecha_sistema"];
                     $Fecha           = $RESPUESTA->fields["Fecha"];
                     $Giro            = $RESPUESTA->fields["Giro"];
                     $Tipo            = $RESPUESTA->fields["Tipo"];
                     $Estatus         = $RESPUESTA->fields["Estatus"];
                     $CVE_SARE        = $RESPUESTA->fields["CVE_SARE"];


                     $QueryRespaldo = "INSERT INTO pld_cat_giro_negocio (ID_Giro_negocio, ID_Usr, Fecha_sistema, Fecha, Giro, Tipo, Estatus, CVE_SARE)
                      VALUES ( ".$ID_Giro_negocio.", '.$ID_Usr.', '".$Fecha_sistema."', '".$Fecha."', '".$Giro."', '".$Tipo."', '".$Estatus."', '".$CVE_SARE."' ) ";


				 	$Query = "DELETE FROM pld_cat_giro_negocio WHERE ID_Giro_negocio = '".$id."'";
				 	$this->db->Execute($Query);  //debug($Query);


                     $QueryRespaldo=str_replace("'","\'",$QueryRespaldo);
                     $Query = "INSERT INTO pld_catalogos_log(ID_registro,ID_Usr,Tipo,Accion,sentencia)
		 						   VALUES (".$id.", '".$ID_USR."', 'ACTIVIDADES','DELETE', '".$QueryRespaldo."' )";
                     $this->db->Execute($Query); // debug($Query);

				 	echo "LISTO";
				}else{
					echo "ERROR";
				}	
			 	
			} // fin if
		 	
	 	} // fin function



    //*****************RLJ esto es para las nuevas entidades de listas y condusef

        public function AddListasPropias($arrDatos,$ID_USR){

            $NombreCompleto = mb_strtoupper($arrDatos[0])." ".mb_strtoupper($arrDatos[1])." ".mb_strtoupper($arrDatos[2]);

            $Query = "SELECT COUNT(pld_cat_nombres_lp.ID_Nmb_lp) AS ID_Nmb_lp
                            FROM pld_cat_nombres_lp
                           WHERE CONCAT(pld_cat_nombres_lp.Nombre_I,' ',pld_cat_nombres_lp.Ap_paterno,' ',pld_cat_nombres_lp.Ap_materno)  = '$NombreCompleto' ";

            $RESPUESTA      = $this->db->Execute($Query); // debug($Query);
            $ID_Nmb_lp       = $RESPUESTA->fields["ID_Nmb_lp"];


            if( empty($ID_Nmb_lp) ){

                $Query = "INSERT INTO pld_cat_nombres_lp (ID_Usr,Nombre_I,Ap_paterno,Ap_materno,RFC,CURP)
                               VALUES
                                     (".$ID_USR.", '".mb_strtoupper($arrDatos[0])."', '".mb_strtoupper($arrDatos[1])."', '".mb_strtoupper($arrDatos[2])."', '".mb_strtoupper($arrDatos[3])."', '".mb_strtoupper($arrDatos[4])."')";

                $this->db->Execute($Query);  //debug($Query);
                $id_insert = $this->db->_insertid();

                $Query = "INSERT INTO pld_cat_listas_negras_log (ID_persona,ID_Usr,Tipo,Accion)
	 					  			   VALUES
	 					  		   		  (".$id_insert.", ".$ID_USR.", 'LP', 'INSERT')";
                $this->db->Execute($Query); // debug($Query);
                echo "LISTO";

            }else{
                echo "YA EXISTE";
            }

        } // fin function


        public function EditionListasPropias($arrDatos,$ID_USR,$id){

            //***********modificacion por RLJ para el curp**********//
            if( !empty($id) ){

                $Query = "UPDATE pld_cat_nombres_lp
                                     SET Nombre_I    = '".mb_strtoupper($arrDatos[0])."',
                                         Ap_paterno  = '".mb_strtoupper($arrDatos[1])."',
                                         Ap_materno  = '".mb_strtoupper($arrDatos[2])."',
                                         RFC         = '".mb_strtoupper($arrDatos[3])."',
                                         CURP        = '".mb_strtoupper($arrDatos[4])."'
                                   WHERE ID_Nmb_lp  = '".$id."'";

                $this->db->Execute($Query);  // debug($Query);

                $Query = "INSERT INTO pld_cat_listas_negras_log (ID_persona,ID_Usr,Tipo,Accion)
	 					  			   VALUES
	 					  		   		  (".$id.", ".$ID_USR.", 'LP', 'UPDATE')";
                $this->db->Execute($Query); // debug($Query);

                echo "LISTO";
            } // fin if

        } // fin function

        public function VistaListasPropias($BuscaRFC,$Evento,$Pagina){

            $muestra = $this->muestra_global;
            $QueryWhere = "";

            if( !empty($BuscaRFC) ){
                $BuscaRFC = str_replace(" ", "%", $BuscaRFC);
                $QueryWhere = "WHERE CONCAT(pld_cat_nombres_lp.Nombre_I,' ',pld_cat_nombres_lp.Ap_paterno,' ',pld_cat_nombres_lp.Ap_materno) LIKE '%".$BuscaRFC."%'";
            } // fin if

            $Query_count = "SELECT count(*) as Total
									FROM pld_cat_nombres_lp $QueryWhere ";


            $RESPUESTA      = $this->db->Execute($Query_count);
            $total_registros = $RESPUESTA->fields["Total"];
            $total_paginas = $total_registros/$muestra;
            $total_paginas = ceil($total_paginas);

            if($Pagina>$total_paginas)
            {
                $Pagina = 1;
            }

            if (!$Pagina or $Pagina == 0) {
                $limite_ini = 0;
                $Pagina = 1;
            }
            else {
                if($Evento == "Prev"){
                    $Pagina--;
                    if($Pagina == 0)
                    {
                        $Pagina = 1;
                    }
                }elseif($Evento == "Next")
                {
                    $Pagina++;
                    if($Pagina>$total_paginas)
                    {
                        $Pagina--;
                    }
                }elseif($Evento == "First")
                {
                    $Pagina = 1;
                }elseif($Evento == "Last")
                {
                    $Pagina = $total_paginas;
                }else
                {
                    $limite_ini = ($Pagina) * $muestra;
                }

                $limite_ini = ($Pagina-1) * $muestra;

            }
            if($limite_ini < 0)
            {
                $limite_ini = 0;
            }
            $limite = "LIMIT ".$limite_ini.", ".$muestra;


            $Query = "SELECT
                             pld_cat_nombres_lp.ID_Nmb_lp,
                             pld_cat_nombres_lp.ID_Usr,
                             pld_cat_nombres_lp.Nombre_I,
                             pld_cat_nombres_lp.Ap_paterno,
                             pld_cat_nombres_lp.Ap_materno,
                             pld_cat_nombres_lp.RFC,
                             pld_cat_nombres_lp.CURP,
                             pld_cat_nombres_lp.Fecha_sistema
                        FROM pld_cat_nombres_lp
                        $QueryWhere
                        ORDER BY ID_Nmb_lp DESC
                        $limite ";

            $RESPUESTA      = $this->db->Execute($Query);  // debug($Query);


            while( !$RESPUESTA->EOF ) {

                $ID_Nmb_lp     = $RESPUESTA->fields["ID_Nmb_lp"];
                $ID_Usr         = $RESPUESTA->fields["ID_Usr"];
                $Nombre_I       = $RESPUESTA->fields["Nombre_I"];
                $Ap_paterno     = $RESPUESTA->fields["Ap_paterno"];
                $Ap_materno     = $RESPUESTA->fields["Ap_materno"];
                $RFC            = $RESPUESTA->fields["RFC"];
                $CURP           = $RESPUESTA->fields["CURP"];
                $Fecha_sistema  = $RESPUESTA->fields["Fecha_sistema"];

                $htmlTbody .= '

                    <tr>
                        <td>'.mb_strtoupper($Nombre_I).'</td>
                        <td>'.mb_strtoupper($Ap_paterno).'</td>
                        <td>'.mb_strtoupper($Ap_materno).'</td>
                        <td>'.mb_strtoupper($RFC).'</td>
                        <td>'.mb_strtoupper($CURP).'</td>
                        <td>'.mb_strtoupper($Fecha_sistema).'</td>
                        <td align="center"><a class="ui mini green button" onclick="EditarListaPropia('.$ID_Nmb_lp.');">Editar</a></td>
                        <td align="center"><a class="ui mini red button" onclick="EliminarListaPropia('.$ID_Nmb_lp.');">Eliminar</a></td>
                    </tr>

                  ';


                $RESPUESTA->MoveNext();
            } // fin while( !$RESPUESTA->EOF ) {

            $html = '

                  <table align="center" width="100%" class="ui table segment">
                    <thead>
                        <tr>
                            <th width="15%">NOMBRE</th>
                            <th width="15%">APELLIDO PATERNO</th>
                            <th width="15%">APELLIDO MATERNO</th>
                            <th width="10%">R.F.C.</th>
                            <th width="10%">C.U.R.P.</th>
                            <th width="10%">FECHA DE ALTA</th>
                            <th width="20%"></th>
                            <th width="20%"></th>
                        </tr>
                    </thead>
                    '.$htmlTbody.'
                </table>';

            $html .= "
            <div class='ui'>
                <i class='big step backward icon' onclick='ActualizaLista(\"First\",".$Pagina.")'></i>
                <i class='big backward icon' onclick='ActualizaLista(\"Prev\",".$Pagina.")'></i>

                <div class='ui small icon input'>
                    <input size='4' type='text' id='Paginacion' onchange='CambiaPagina(this)' value='".$Pagina."'>
                </div>

                <i class='big forward icon' onclick='ActualizaLista(\"Next\",".$Pagina.")'></i>
                <i class='big step forward icon' onclick='ActualizaLista(\"Last\",".$Pagina.")'></i>
            </div>";

            echo  $html;

        } // fin public function VistaPersonas(){

//*****************RLJ esto es para las nuevas entidades de listas y condusef

    public function AddListasCondusef($arrDatos,$ID_USR){

        $NombreCompleto = mb_strtoupper($arrDatos[0])." ".mb_strtoupper($arrDatos[1])." ".mb_strtoupper($arrDatos[2]);

        $Query = "SELECT COUNT(pld_cat_nombres_lc.ID_Nmb_lc) AS ID_Nmb_lc
                            FROM pld_cat_nombres_lc
                           WHERE CONCAT(pld_cat_nombres_lc.Nombre_I,' ',pld_cat_nombres_lc.Ap_paterno,' ',pld_cat_nombres_lc.Ap_materno)  = '$NombreCompleto' ";

        $RESPUESTA      = $this->db->Execute($Query); // debug($Query);
        $ID_Nmb_lc       = $RESPUESTA->fields["ID_Nmb_lc"];


        if( empty($ID_Nmb_lc) ){

            $Query = "INSERT INTO pld_cat_nombres_lc (ID_Usr,Nombre_I,Ap_paterno,Ap_materno,RFC,CURP)
                               VALUES
                                     (".$ID_USR.", '".mb_strtoupper($arrDatos[0])."', '".mb_strtoupper($arrDatos[1])."', '".mb_strtoupper($arrDatos[2])."', '".mb_strtoupper($arrDatos[3])."', '".mb_strtoupper($arrDatos[4])."')";

            $this->db->Execute($Query);  //debug($Query);
            $id_insert = $this->db->_insertid();

            $Query = "INSERT INTO pld_cat_listas_negras_log (ID_persona,ID_Usr,Tipo,Accion)
	 					  			   VALUES
	 					  		   		  (".$id_insert.", ".$ID_USR.", 'LC', 'INSERT')";
            $this->db->Execute($Query); // debug($Query);

            echo "LISTO";

        }else{
            echo "YA EXISTE";
        }

    } // fin function


    public function EditionListasCondusef($arrDatos,$ID_USR,$id){

        //***********modificacion por RLJ para el curp**********//
        if( !empty($id) ){

            $Query = "UPDATE pld_cat_nombres_lc
                                     SET Nombre_I    = '".mb_strtoupper($arrDatos[0])."',
                                         Ap_paterno  = '".mb_strtoupper($arrDatos[1])."',
                                         Ap_materno  = '".mb_strtoupper($arrDatos[2])."',
                                         RFC         = '".mb_strtoupper($arrDatos[3])."',
                                         CURP        = '".mb_strtoupper($arrDatos[4])."'
                                   WHERE ID_Nmb_lc  = '".$id."'";

            $this->db->Execute($Query);  // debug($Query);

            $Query = "INSERT INTO pld_cat_listas_negras_log (ID_persona,ID_Usr,Tipo,Accion)
	 					  			   VALUES
	 					  		   		  (".$id.", ".$ID_USR.", 'LC', 'UPDATE')";
            $this->db->Execute($Query); // debug($Query);
            echo "LISTO";
        } // fin if

    } // fin function

    public function VistaListasCondusef($BuscaRFC,$Evento,$Pagina){

        //debug($BuscaRFC."--".$Evento."--".$Pagina);

        $muestra = $this->muestra_global;
        $QueryWhere = "";

        if( !empty($BuscaRFC) ){
            $BuscaRFC = str_replace(" ", "%", $BuscaRFC);
            $QueryWhere = "WHERE CONCAT(pld_cat_nombres_lc.Nombre_I,' ',pld_cat_nombres_lc.Ap_paterno,' ',pld_cat_nombres_lc.Ap_materno) LIKE '%".$BuscaRFC."%'";
        } // fin if

        $Query_count = "SELECT count(*) as Total
									FROM pld_cat_nombres_lc $QueryWhere ";


        $RESPUESTA      = $this->db->Execute($Query_count);
        $total_registros = $RESPUESTA->fields["Total"];
        $total_paginas = $total_registros/$muestra;
        $total_paginas = ceil($total_paginas);

        if($Pagina>$total_paginas)
        {
            $Pagina = 1;
        }

        if (!$Pagina or $Pagina == 0) {
            $limite_ini = 0;
            $Pagina = 1;
        }
        else {
            if($Evento == "Prev"){
                $Pagina--;
                if($Pagina == 0)
                {
                    $Pagina = 1;
                }
            }elseif($Evento == "Next")
            {
                $Pagina++;
                if($Pagina>$total_paginas)
                {
                    $Pagina--;
                }
            }elseif($Evento == "First")
            {
                $Pagina = 1;
            }elseif($Evento == "Last")
            {
                $Pagina = $total_paginas;
            }else
            {
                $limite_ini = ($Pagina) * $muestra;
            }

            $limite_ini = ($Pagina-1) * $muestra;

        }

        if($limite_ini < 0)
        {
            $limite_ini = 0;
        }

        $limite = "LIMIT ".$limite_ini.", ".$muestra;

        $Query = "SELECT
                             pld_cat_nombres_lc.ID_Nmb_lc,
                             pld_cat_nombres_lc.ID_Usr,
                             pld_cat_nombres_lc.Nombre_I,
                             pld_cat_nombres_lc.Ap_paterno,
                             pld_cat_nombres_lc.Ap_materno,
                             pld_cat_nombres_lc.RFC,
                             pld_cat_nombres_lc.CURP,
                             pld_cat_nombres_lc.Fecha_sistema
                        FROM pld_cat_nombres_lc
                        $QueryWhere
                        ORDER BY ID_Nmb_lc DESC
                        $limite ";

        $RESPUESTA      = $this->db->Execute($Query);  // debug($Query);


        while( !$RESPUESTA->EOF ) {

            $ID_Nmb_lc     = $RESPUESTA->fields["ID_Nmb_lc"];
            $ID_Usr         = $RESPUESTA->fields["ID_Usr"];
            $Nombre_I       = $RESPUESTA->fields["Nombre_I"];
            $Ap_paterno     = $RESPUESTA->fields["Ap_paterno"];
            $Ap_materno     = $RESPUESTA->fields["Ap_materno"];
            $RFC            = $RESPUESTA->fields["RFC"];
            $CURP           = $RESPUESTA->fields["CURP"];
            $Fecha_sistema  = $RESPUESTA->fields["Fecha_sistema"];

            $htmlTbody .= '

                    <tr>
                        <td>'.mb_strtoupper($Nombre_I).'</td>
                        <td>'.mb_strtoupper($Ap_paterno).'</td>
                        <td>'.mb_strtoupper($Ap_materno).'</td>
                        <td>'.mb_strtoupper($RFC).'</td>
                        <td>'.mb_strtoupper($CURP).'</td>
                        <td>'.mb_strtoupper($Fecha_sistema).'</td>
                        <td align="center"><a class="ui mini green button" onclick="EditarListaCondusef('.$ID_Nmb_lc.');">Editar</a></td>
                        <td align="center"><a class="ui mini red button" onclick="EliminarListaCondusef('.$ID_Nmb_lc.');">Eliminar</a></td>
                    </tr>

                  ';


            $RESPUESTA->MoveNext();
        } // fin while( !$RESPUESTA->EOF ) {

        $html = '

                  <table align="center" width="100%" class="ui table segment">
                    <thead>
                        <tr>
                            <th width="15%">NOMBRE</th>
                            <th width="15%">APELLIDO PATERNO</th>
                            <th width="15%">APELLIDO MATERNO</th>
                            <th width="10%">R.F.C.</th>
                            <th width="10%">C.U.R.P.</th>
                            <th width="10%">FECHA DE ALTA</th>
                            <th width="20%"></th>
                            <th width="20%"></th>
                        </tr>
                    </thead>
                    '.$htmlTbody.'
                </table>';
        $html .= "
            <div class='ui'>
                <i class='big step backward icon' onclick='ActualizaLista(\"First\",".$Pagina.")'></i>
                <i class='big backward icon' onclick='ActualizaLista(\"Prev\",".$Pagina.")'></i>

                <div class='ui small icon input'>
                    <input size='4' type='text' id='Paginacion' onchange='CambiaPagina(this)' value='".$Pagina."'>
                </div>

                <i class='big forward icon' onclick='ActualizaLista(\"Next\",".$Pagina.")'></i>
                <i class='big step forward icon' onclick='ActualizaLista(\"Last\",".$Pagina.")'></i>
            </div>";

        echo  $html;

    } // fin public function VistaPersonas(){

    public function DeleteListasCondusef($id,$ID_USR){

        if(!empty($id)){

            $Query = "SELECT
        				 pld_cat_nombres_lc.ID_Nmb_lc,
						 pld_cat_nombres_lc.ID_Usr,
						 pld_cat_nombres_lc.Nombre_I,
						 pld_cat_nombres_lc.Ap_paterno,
						 pld_cat_nombres_lc.Ap_materno,
						 pld_cat_nombres_lc.RFC,
						 pld_cat_nombres_lc.CURP,
						 pld_cat_nombres_lc.Fecha_sistema
					FROM pld_cat_nombres_lc
					WHERE pld_cat_nombres_lc.ID_Nmb_lc = '".$id."'";

            $RESPUESTA      = $this->db->Execute($Query);  // debug($Query);

            $ID_Nmb_lc      = $RESPUESTA->fields["ID_Nmb_lc"];
            $ID_Usr         = $RESPUESTA->fields["ID_Usr"];
            $Nombre_I       = $RESPUESTA->fields["Nombre_I"];
            $Ap_paterno     = $RESPUESTA->fields["Ap_paterno"];
            $Ap_materno     = $RESPUESTA->fields["Ap_materno"];
            $RFC            = $RESPUESTA->fields["RFC"];
            $CURP           = $RESPUESTA->fields["CURP"];

            $QueryRespaldo = "INSERT INTO pld_cat_nombres_lc (ID_Nmb_lc,ID_Usr,Nombre_I,Ap_paterno,Ap_materno,RFC,CURP)
                VALUES ( ".$ID_Nmb_lc.", ".$ID_Usr.", '".mb_strtoupper($Nombre_I)."', '".mb_strtoupper($Ap_paterno)."', '".mb_strtoupper($Ap_materno)."', '".mb_strtoupper($RFC)."', '".mb_strtoupper($CURP)."' ) ";


            $Query = "DELETE FROM pld_cat_nombres_lc WHERE ID_Nmb_lc = '".$id."'";
            $this->db->Execute($Query);  //debug($Query);

            $QueryRespaldo=str_replace("'","\'",$QueryRespaldo);
            $Query = "INSERT INTO pld_cat_listas_negras_log (ID_persona,ID_Usr,Tipo,Accion,sentencia)
	 					  			   VALUES
	 					  		   		  (".$id.", ".$ID_USR.", 'LC', 'DELETE','".$QueryRespaldo."')";
            $this->db->Execute($Query);


            echo "LISTO";
        }else{
            echo "ERROR";
        }

    } // fin function

    public function DeleteListasPropias($id,$ID_USR){

        if(!empty($id)){
            $Query = "SELECT
        				 pld_cat_nombres_lp.ID_Nmb_lp,
						 pld_cat_nombres_lp.ID_Usr,
						 pld_cat_nombres_lp.Nombre_I,
						 pld_cat_nombres_lp.Ap_paterno,
						 pld_cat_nombres_lp.Ap_materno,
						 pld_cat_nombres_lp.RFC,
						 pld_cat_nombres_lp.CURP,
						 pld_cat_nombres_lp.Fecha_sistema
					FROM pld_cat_nombres_lp
					WHERE pld_cat_nombres_lp.ID_Nmb_lp = '".$id."'";

            $RESPUESTA      = $this->db->Execute($Query);  // debug($Query);

            $ID_Nmb_lp      = $RESPUESTA->fields["ID_Nmb_lp"];
            $ID_Usr         = $RESPUESTA->fields["ID_Usr"];
            $Nombre_I       = $RESPUESTA->fields["Nombre_I"];
            $Ap_paterno     = $RESPUESTA->fields["Ap_paterno"];
            $Ap_materno     = $RESPUESTA->fields["Ap_materno"];
            $RFC            = $RESPUESTA->fields["RFC"];
            $CURP           = $RESPUESTA->fields["CURP"];

            $QueryRespaldo = "INSERT INTO pld_cat_nombres_lp (ID_Nmb_lp,ID_Usr,Nombre_I,Ap_paterno,Ap_materno,RFC,CURP)
                VALUES ( ".$ID_Nmb_lp.", ".$ID_Usr.", '".mb_strtoupper($Nombre_I)."', '".mb_strtoupper($Ap_paterno)."', '".mb_strtoupper($Ap_materno)."', '".mb_strtoupper($RFC)."', '".mb_strtoupper($CURP)."' ) ";

            $Query = "DELETE FROM pld_cat_nombres_lp WHERE ID_Nmb_lp = '".$id."'";
            $this->db->Execute($Query);  //debug($Query);

            $QueryRespaldo=str_replace("'","\'",$QueryRespaldo);
            $Query = "INSERT INTO pld_cat_listas_negras_log (ID_persona,ID_Usr,Tipo,Accion,sentencia)
	 					  			   VALUES
	 					  		   		  (".$id.", ".$ID_USR.", 'LP', 'DELETE','".$QueryRespaldo."')";
            $this->db->Execute($Query);

            echo "LISTO";
        }else{
            echo "ERROR";
        }

    } // fin function



}


?>