<?

/**
 *
 * @author MarsVoltoso (CFA)
 * @category Model
 * @created Mon Sep 15, 2014
 * @version 1.0
 */	


/**
 *
 *  @ Cargamos registros
 */ 	
 
 if( $__cmd == "setLiberaCancelarDtl" ){
		
	    $noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		
        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
      $html = '<tr>
		 	   	   <td align="center"><u><b>Comentarios:</b></u></td>
		 	   </tr>
			   <tr>
		 	   	   <td align="center"><textarea id="ComentariosDichosCancelar" type="text" cols="90" rows="10" ></textarea></td>
		 	   </tr>
		 	   <tr>
		 	   	   <td align="right"> <a class="ui mini blue button" onclick="CancelarComent('.$ID_Solicitud.');">CANCELAR</a> </td>
		 	   </tr>';  
        
     echo $html;    
        
 }
 
/**
 *
 *  @ Cargamos registros
 */ 	
 
 if( $__cmd == "setLiberaSolicitudDtl" ){
		
	    $noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		
        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
      $html = '<tr>
		 	   	   <td align="center"><u><b>Comentarios:</b></u></td>
		 	   </tr>
			   <tr>
		 	   	   <td align="center"><textarea id="ComentariosDichosLiberar" type="text" cols="90" rows="10" ></textarea></td>
		 	   </tr>
		 	   <tr>
		 	   	   <td align="right"> <a class="ui mini blue button" onclick="LibearComent('.$ID_Solicitud.');">LIBERAR Y COMENTAR</a> </td>
		 	   </tr>';  
        
     echo $html;    
        
 }

/**
 *
 *  @ Cargamos registros
 */ 	
 
 if( $__cmd == "setConsultaSolicitudes" ){
		
	    $noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		
        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
        	//---------------------->
			// VALIDAMOS QUE SEA EL OFICIAL DE CUMPLIMIENTO
			
				$Query = "SELECT 
								 ID_User_Oficial_Cumplimiento
							FROM pld_parametros_configuracion
						   LIMIT 1";
						   
						   $RESPUESTA                    = $db->Execute($Query); 
						   $ID_User_Oficial_Cumplimiento = $RESPUESTA->fields["ID_User_Oficial_Cumplimiento"];
			
			if( $ID_User_Oficial_Cumplimiento != $ID_USR ){
				
				$html .= "
				 		 <tr class='error'>
					    	<td colspan='10'>¡USTED NO TIENE PERMISO PARA VER LAS SOLICITUDES!</td>
					    </tr>
				 ";
				 
				//echo $html;
				//die();
				
			} // fin if
			
			//---------------------->

        
        if( !empty($SherchSolicitud) ){
	        $QueryWhere = " AND solicitud.ID_Solicitud = '".$SherchSolicitud."' ";
        }elseif(!empty($SherchNombre))
		{
			$SherchNombre = str_replace(" ","%",$SherchNombre);
	        $QueryWhere = " AND CONCAT( solicitud.Nombre,' ',solicitud.NombreI,' ',solicitud.Ap_paterno,' ',solicitud.Ap_materno ) like '%".$SherchNombre."%' ";
		}// fin if
        
        $Query = "SELECT 
        				 solicitud.ID_Solicitud,
						 CONCAT( solicitud.Nombre,' ',solicitud.NombreI,' ',solicitud.Ap_paterno,' ',solicitud.Ap_materno ) AS NombreSolicitante,
						 cat_tipo_credito.Descripcion,
						 solicitud.Monto
					FROM solicitud
			  INNER JOIN cat_tipo_credito ON cat_tipo_credito.ID_Tipocredito = solicitud.ID_Tipocredito
				   WHERE solicitud.SolicitudRevisionPld = 'SI'
				   AND   solicitud.Status_solicitud != 'CANCELADA'
				   $QueryWhere
				   ORDER BY solicitud.ID_Solicitud DESC  ";
        
			$RESPUESTA   = $db->Execute($Query); //debug($Query);
			$html = "";
			
						
			while( !$RESPUESTA->EOF ) { 
			 
			 $ID_Solicitud      = $RESPUESTA->fields["ID_Solicitud"];
			 $NombreSolicitante = $RESPUESTA->fields["NombreSolicitante"];
			 $Descripcion       = $RESPUESTA->fields["Descripcion"];
			 $Monto             = $RESPUESTA->fields["Monto"];
			 
			  $html .= "
				 		<tr class='warning'>
					    	<td align='center'>".$ID_Solicitud."</td>
					    	<td align='left'>".strtoupper($NombreSolicitante)."</td>
							<td align='left'>".strtoupper($Descripcion)."</td>
							<td>$ ".number_format($Monto,2) ."</td>
							<td> <a class='ui tiny orange  button DetalleSolicitud' onclick='DetallesSolicitud($ID_Solicitud);' alt='$ID_Solicitud'>DETALLES</a> </td>
							<td> <a class='ui tiny green button' onclick='LiberarSolicitud($ID_Solicitud);'>LIBERAR SOLICITUD</a> </td>
							<td> <a class='ui tiny red button' onclick='CancelacionCompleta($ID_Solicitud);'>CANCELAR LA SOLICITUD</a> </td>
						</tr>
				 ";
			   
			  $RESPUESTA->MoveNext(); 
			 } // fin while( !$RESPUESTA->EOF ) {
			 
       echo $html;  
         
} // fin if


/**
 *
 *  @ Vemos el Detalle
 */ 	
 
 if( $__cmd == "setConsultaDetalles" ){
		
	    $noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		
        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
		  require_once( $class_path."libValidacionOriginacion.php" ); 
									 
		  $OBJ = new libValidacionOriginacion($db,$ID_Solicitud,$ID_USR);
		  $arrResultados = $OBJ->resultadosConfiguracion();
        
         	foreach( $arrResultados as $key ){
			   
			   if( !empty($key) ){	
			  	$html .= "
				 		<tr class='warning'>
					    	<td colspan='2'>".($key)."</td>
					    </tr>
				      ";
				}     
			  	
		  	} // fin foreach
		  	
		  	if( empty($html) ){
				$html .= "
			 		  <tr>
				    	  <td colspan='6' >
				    	  	<p>¡No hay detalles!</p>
				    	  </td>
				      </tr>
				 ";  	
		  	}
		  	
		  	/*
		  	$html .= "
			 		  <tr>
				    	  <td colspan='6' ><a class='ui mini blue button' onclick='GuardarInformacion($ID_Solicitud);'>Guardar</a></td>
				      </tr>
				 ";
	    	*/
     echo $html;  
         
} // fin if

/**
 *
 *  @ Libera solicitud
 */ 	
 
 if( $__cmd == "setLiberaSolicitud" ){
		
	    $noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		
        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
			$Query = "UPDATE solicitud SET SolicitudRevisionPld = 'NO' WHERE ID_Solicitud = '".$ID_Solicitud."' ";	
			    $db->Execute($Query);
				
			$Query = "INSERT INTO pld_oficial_cumplimiento_log (id_usr, fecha_cambio, SolicitudRevisionPld,ID_Solicitud,Comentarios)
						   VALUES
						   		  ('".$ID_USR."', NOW(), 'NO','".$ID_Solicitud."','".$Coment."') ";
				$db->Execute($Query);
				
     echo "LISTO";  
} // fin if

/**
 *
 *  @ Baja definitiva
 */ 	
 
 if( $__cmd == "setBajaSolicitud" ){
		
	    $noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		
        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
        	$Query = "UPDATE solicitud SET Status_solicitud = 'CANCELADA' WHERE ID_Solicitud = '".$ID_Solicitud."' ";	
			    $db->Execute($Query); //debug($Query);
				
			$Query = "INSERT INTO pld_oficial_cumplimiento_log (id_usr, fecha_cambio, SolicitudRevisionPld,ID_Solicitud,Comentarios)
						   VALUES
						   		  ('".$ID_USR."', NOW(), 'CANCELADA','".$ID_Solicitud."','".$Coment."') ";	
				$db->Execute($Query);
			
							
     echo "LISTO";  
} // fin if

/**
 *
 *  @ Comentarios
 */ 	
 
 if( $__cmd == "setComentatiosSolicitud" ){
		
	    $noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		
        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
        	$Query = "INSERT INTO pld_comentarios_alertas (ID_Comentario, ID_Solicitud, ID_Usr, Comentario) VALUES (NULL, '".$ID_Solicitud."', '".$ID_USR."', '".$ComentariosDichos."')";
        		$db->Execute($Query);
			
							
     echo "LISTO";  
} // fin if