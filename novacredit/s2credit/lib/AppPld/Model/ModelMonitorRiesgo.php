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
 *  @ Cargamos Cargamos resultado
 */ 	
 
 if( $__cmd == "setConsultaClientes" ){
		
	    $noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		
        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
  
		
			if( !empty($SherchSolicitud) or !empty($SherchNombre)  ){
				
				if( !empty($SherchSolicitud) ){
					
					$Query = "SELECT 
									 clientes_datos.Num_cliente
								FROM clientes_datos
							   WHERE clientes_datos.Num_cliente = '".$SherchSolicitud."' ";
							   
						$RESPUESTA   = $db->Execute($Query);  
						$Num_cliente = $RESPUESTA->fields["Num_cliente"];	   
					
				}
				
				
				if( empty($Num_cliente) and !empty($SherchNombre) ){
					
					$Query = "SELECT 
									 clientes_datos.Num_cliente
									 ,CONCAT(trim(clientes_datos.Nombre),
                                             if(clientes_datos.NombreI != \"\",concat(' ',trim(clientes_datos.NombreI)),''),
                                             ' ',trim(clientes_datos.Ap_paterno),
                                             if(clientes_datos.Ap_materno != \"\",concat(' ',trim(clientes_datos.Ap_materno)),'')
                                              ) AS Nombre
								FROM clientes_datos
						       HAVING Nombre LIKE '%$SherchNombre%' ";
						       
						$RESPUESTA   = $db->Execute($Query);  //debug($Query);
						$Num_cliente = $RESPUESTA->fields["Num_cliente"];       
					
				} // FIN IF
				
				
				if( !empty($Num_cliente) ){
					
						$Query = "SELECT 
									     solicitud_plvd.Nombre,
										 solicitud_plvd.NombreI,
										 solicitud_plvd.Ap_paterno,
										 solicitud_plvd.Ap_materno,
										 clientes.Num_cliente 
									FROM solicitud_plvd,
										 clientes
								   WHERE clientes.ID_Solicitud = solicitud_plvd.ID_Solicitud AND
								   	     clientes.Num_cliente  = '".$Num_cliente."' ";
								   	     
								$RESPUESTA   = $db->Execute($Query); //debug($Query);
								$Num_cliente = $RESPUESTA->fields["Num_cliente"];
								$Nombre      = $RESPUESTA->fields["Nombre"];
								$NombreI     = $RESPUESTA->fields["NombreI"];
								$Ap_paterno  = $RESPUESTA->fields["Ap_paterno"];
								$Ap_materno  = $RESPUESTA->fields["Ap_materno"];
								$Num_cliente = $RESPUESTA->fields["Num_cliente"];     	     
								   	     
						if( empty($Num_cliente) ){
							$arrEdicion["RespuestaBusqueda"][] = "<i><u><font size='2pt'>El Cliente no existe</font></u></i>";
							$arrEdicion["TbleContent"][] = "";
								
								require_once( $class_path."json.php" ); 
								$json       = new Services_JSON; 
								$formulario = $json->encode($arrEdicion);
								
							echo $formulario; 	 	
						  die();	
						} // fin if		   	     
								   	     
						  $arrEdicion["RespuestaBusqueda"][] = "<i><u><font size='2pt'>".$Nombre." ".$NombreI." ".$Ap_paterno." ".$Ap_materno."</font></i></u>";
						  		
					
					      require_once( $class_path."lib_pld.php" ); 
									 
						  $oriesgo = new TRIESGO($db,$Num_cliente);
						  $oriesgo->evalua_estado_vectores();


						  $htmlDetalle = "<table class='ui table segment' style='width:98%'>
						  					<tr>
						  						<th></th>
						  						<th>Elementos de evaluación de riesgo </th>
						  						<th>Ponderación </th>
						  						<th>Aplica </th>
						  						<th>Valor</th>
						  					</tr>";
						  
						  $opciones_abiertas= array(2,3); //,4,5,6,8,9);
						  
						  $k=0;
                           
                           foreach ($oriesgo->vectores_evaluacion AS $key => $value)
							if($oriesgo->matriz_riesgo[$key]['Ponderacion'] >= 100)
							{
						
						
							       switch ($oriesgo->vectores_evaluacion[$key])
							       {
							       		case 0  : $semaforo = "No";
							       		          $scolor   = "slategray";
							       		          break;
							       		          
							       		case 1  : $semaforo = "Si";
							       		          $scolor   = "red";
							       		          break;
							       		
							       		case -1 : $semaforo = "No";
							       		          $scolor   = "blue";
							       		          break;
								}	       	
						
							       $valor = ($oriesgo->vectores_evaluacion[$key] <= 0)?(0):($oriesgo->matriz_riesgo[$key]['Ponderacion']);	
							       $total_1 += ($oriesgo->vectores_evaluacion[$key] <= 0)?(0):($oriesgo->matriz_riesgo[$key]['Ponderacion']);	
							       $color='silver'; //($color=='white')?('silver'):('white');
						
								$htmlDetalle .="<tr>";
								$htmlDetalle .="<th align='center'>".(++$k).")</th>";
								$htmlDetalle .="<th align='left'  nowrap>&nbsp; ".$oriesgo->matriz_riesgo[$key]['Descripcion']."&nbsp;</th>";
								$htmlDetalle .="<th align='right' nowrap>&nbsp; -- &nbsp;</th>\n";
								$htmlDetalle .="<th align='center' ";
									   
									   if(($oriesgo->vectores_evaluacion[$key]) > 0)
									   {
									   	if( in_array($key, $opciones_abiertas))
									   	{
									   		$htmlDetalle .= "class='boton' onClick='despliega(".($key).",".($num_cliente).");' ";  
									   	}
									   }
									   
								$htmlDetalle .="align='center'  onmouseover=\"javascript: this.style.cursor='pointer';\"  style='color:".$scolor.";'>&nbsp;".$semaforo."&nbsp;</th>";
								//$htmlDetalle .="<th align='right' nowrap>".($valor )."</th>";
								$htmlDetalle .="<th align='right' nowrap> -- </th>";
								$htmlDetalle .="</tr>";
						
						   }
						   
						        $htmlDetalle ."<tr><th colspan='5'  bgcolor='steelblue' align='center' style='height:3px;'></th></tr>";	
						     
						        
						  foreach ($oriesgo->vectores_evaluacion AS $key => $value)
						  	if($oriesgo->matriz_riesgo[$key]['Ponderacion'] < 100)
						  		{


								       switch ($oriesgo->vectores_evaluacion[$key])
								       {
								       		case 0  : $semaforo = "No";
								       		          $scolor   = "slategray";
								       		          break;
								       		          
								       		case 1  : $semaforo = "Si";
								       		          $scolor   = "red";
								       		          break;
								       		
								       		case -1 : $semaforo = "No";
								       		          $scolor   = "blue";
								       		          break;
								      }	       	

									  $valor = ($oriesgo->vectores_evaluacion[$key] <= 0)?(0):($oriesgo->matriz_riesgo[$key]['Ponderacion']);	
									  $total_2 += ($oriesgo->vectores_evaluacion[$key] <= 0)?(0):($oriesgo->matriz_riesgo[$key]['Ponderacion']);	
									  $color=($color=='white')?('lavender'):('white');

							  $htmlDetalle .="<tr  onmouseover=\"javascript:this.style.backgroundColor='yellow'; this.style.cursor='pointer'; \" onmouseout=\"javascript: this.style.backgroundColor='' \" bgcolor='".$color."' >" ;
							  $htmlDetalle .="<th align='center'>".(++$k).") </th>";
							  $htmlDetalle .="<th align='left'  nowrap>&nbsp; ".$oriesgo->matriz_riesgo[$key]['Descripcion']."&nbsp;</th>";
							  $htmlDetalle .="<th align='right' nowrap>&nbsp; ".$oriesgo->matriz_riesgo[$key]['Ponderacion']."&nbsp;</th>";
							  $htmlDetalle .="<th align='center' ";
			   
								   if(($oriesgo->vectores_evaluacion[$key]) > 0)
								   {
								   	if( in_array($key, $opciones_abiertas))
								   	{
								   		$htmlDetalle .= "class='boton' onClick='despliega(".($key).",".($num_cliente).");' ";  
								   	}
								   }
			   
							 $htmlDetalle .="align='center'  onmouseover=\"javascript: this.style.cursor='pointer';\"  STYLE='color:".$scolor.";'>&nbsp;".$semaforo."&nbsp;</th>";
							 $htmlDetalle .="<th ALIGN='right' NOWRAP>".($valor )."</TH>\n";
							 $htmlDetalle .="</tr>";

						}
						
						$htmlDetalle .="<TR ALIGN='center' BGCOLOR='steelblue'  STYLE='font-size: 14px; font-family:tahoma; color:white;'  NOWRAP>";
						$htmlDetalle .="<TH></TH>";
						$htmlDetalle .="<TH ALIGN='left'  >  Parámetro de ponderación : ".number_format($oriesgo->parametro_minimo_riesgo_alto ,0)." puntos </TH>";
						$htmlDetalle .="<TH ALIGN='right' >  Riesgo ponderado   : &nbsp;</TH>";

						$color_riesgo = "blue";
						$riesgo_tipo = "BAJO";
							//if($oriesgo->riesgo_ponderado >= $oriesgo->parametro_minimo_riesgo_alto ) $color_riesgo = "red";
							if($total_1 > 0){
								$color_riesgo = "red";
								$riesgo_tipo = "ALTO";
							}
							elseif($total_2 >= $oriesgo->parametro_minimo_riesgo_alto)
							{
								$color_riesgo = "red";
								$riesgo_tipo = "ALTO";
							}
	
						$htmlDetalle .="<TH ALIGN='center' STYLE='background-color:lightgrey; color:".$color_riesgo.";' >".$riesgo_tipo."</TH>";
						$htmlDetalle .="<TH ALIGN='right' ID='TotalPonderacion'>".number_format($total_2 ,0)."</TH> ";
						$htmlDetalle .="</TR>";
						$htmlDetalle .="</TABLE>";      				
					
					$arrEdicion["TbleContent"][] = utf8_encode($htmlDetalle);
					
					
				}else{
					$arrEdicion["RespuestaBusqueda"][] = "<i><u><font size='2pt'>El Cliente no existe</font></u></i>" ;
					$arrEdicion["TbleContent"][] = "";
				} // fin if
			}else{
				$arrEdicion["RespuestaBusqueda"][] = "<i><u><font size='2pt'>El Cliente no existe</font></u></i>" ;
				$arrEdicion["TbleContent"][] = "";
			} // fin if
			
			require_once( $class_path."json.php" ); 
			$json       = new Services_JSON; 
			$formulario = $json->encode($arrEdicion); 	
			
			
	 echo $formulario; 		
    die();    
}     