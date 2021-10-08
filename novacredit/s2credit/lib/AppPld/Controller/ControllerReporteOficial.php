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

 
class ControllerReporteOficial {
 
 	public $db;
 	 
 		public function __construct($db){
	 		$this->db = $db; 
	 	} 	 

	 	public function VistaListas($Pagina,$Evento,$Filtro,$Filtro2){
	 	
	 			$Pagina = intval($Pagina);

				$muestra = 20;
				$condicion = "";
				if($Filtro != "")
				{	
					$condicion  = "where CONCAT( solicitud.Nombre,' ',solicitud.NombreI,' ',solicitud.Ap_paterno,' ',solicitud.Ap_materno ) like '%".$Filtro."%'";

				}
				if($Filtro2 != "")
				{	
					$condicion  = "where pld_oficial_cumplimiento_log.ID_Solicitud  like '%".$Filtro2."%'";

				}
				
				 
				
				
				$Query_count = "SELECT
								   count(*)
							  FROM pld_oficial_cumplimiento_log	
					    INNER JOIN solicitud ON solicitud.ID_Solicitud = pld_oficial_cumplimiento_log.ID_Solicitud   
					    $condicion
						  ORDER BY id_OficialCumplimiento
									";
								
				
				 //debug($Query_count);
				 $RESPUESTA      = $this->db->Execute($Query_count); 
				 $total_registros = $RESPUESTA->fields["Total"];
				 $total_paginas = number_format($total_registros/$muestra,2);
				 $total_paginas =  round($total_paginas, 0, PHP_ROUND_HALF_UP); 
				 $tot = $total_paginas - number_format($total_registros/$muestra,0);
				 //echo $total_paginas;

				 //if($tot>0){
				 //$total_paginas = $total_paginas+1;	
				 //}
				//verflujo();
				 if($Pagina>=$total_paginas)
				 {	
					$Pagina = 0; 
				 }

				 if($Pagina==0)
				 {
					 $limite_ini = 0;
					 $Pagina = 1;
				 }else
				 {

					 if($Evento == "Prev")
					 {	

						 $limite_ini = ($Pagina - 1) * $muestra;
						 
						 if($Pagina == 1)
						 {
							 $Pagina = 1;
						 }else
						 {
							 $Pagina--;
						 }
						 

					 }elseif($Evento == "Next")
					 {   
						 $limite_ini = ($Pagina) * $muestra;

 						 $Pagina++;

				     }elseif($Evento == "First")
					 {
						 $limite_ini = 0;
						 $Pagina = 1;

				     }elseif($Evento == "Last")
					 {
						 $limite_ini = ($total_paginas-1)*$muestra;
						 $Pagina = $total_paginas-1;

				     }else
					 {	
 						  $limite_ini = ($Pagina) * $muestra;
						 //$Pagina = 1;

					 }
					 
				 }

				 $limite = "LIMIT ".$limite_ini.", ".$muestra;

				// echo $limite;
				  $Query = "SELECT
								   *,
								   CONCAT( Nombre,' ',NombreI,' ',Ap_paterno,' ',Ap_materno ) AS NOMBRE_SOLICITANTE,
								   pld_oficial_cumplimiento_log.id_OficialCumplimiento,
								   pld_oficial_cumplimiento_log.SolicitudRevisionPLD,
								   pld_oficial_cumplimiento_log.fecha_cambio,
								   pld_oficial_cumplimiento_log.Comentarios
							  FROM pld_oficial_cumplimiento_log	
					    INNER JOIN solicitud ON solicitud.ID_Solicitud = pld_oficial_cumplimiento_log.ID_Solicitud   
					    $condicion
						  ORDER BY id_OficialCumplimiento
								$limite 
								
								";
				 
				  $RESPUESTA      = $this->db->Execute($Query); // debug($Query);
				  
				  //echo $Query;
				  				
				 //echo $total_registros."<-->".$total_paginas."<-->".$Pagina."<-->".$limite_ini."<-->".$Query;
				 //return;
				  while( !$RESPUESTA->EOF ) { 

				  	if($RESPUESTA->fields["SolicitudRevisionPLD"]=="NO")
				  		$SolicitudRevisionPLD = "LIBERADA";
				  	else if($RESPUESTA->fields["SolicitudRevisionPLD"]=="CANCELADA")
				  		$SolicitudRevisionPLD = "CANCELADA";
						
					 $ID_Solicitud       = $RESPUESTA->fields["ID_Solicitud"];
					 $NOMBRE_SOLICITANTE = $RESPUESTA->fields["NOMBRE_SOLICITANTE"];
					 $NOMBRE_SOLICITANTE = $RESPUESTA->fields["NOMBRE_SOLICITANTE"];
					 $Fecha 			 = $RESPUESTA->fields["fecha_cambio"];
					 $DETALLE 			 = $RESPUESTA->fields["Comentarios"];
					 $id_OficialCumplimiento = $RESPUESTA->fields["id_OficialCumplimiento"];
					 
					 $htmlTbody .= '
			  
							<tr>
								<td align="center">'.strtoupper($ID_Solicitud).'</td>
								<td align="left">'.strtoupper($NOMBRE_SOLICITANTE).'</td>
								<td align="left">'.strtoupper($SolicitudRevisionPLD).'</td>		
								<td align="left">'.strtoupper($Fecha).'</td>			
								<td align="left"><input type="submit" id="'.$ID_Solicitud.'" name="botones" onclick="muestra('.$id_OficialCumplimiento.');" class="ui blue button tiny"> </td>					
							</tr>
						  
						  ';
					 $RESPUESTA->MoveNext(); 
				  } // fin while( !$RESPUESTA->EOF ) 	

				$html = ' 
	  				<br>
				  <table align="center" width="100%" class="ui table segment">
					<thead>
						<tr>
							<th width="10%">ID Solicitud</th>
							<th width="20%">NOMBRE</th>
							<th width="20%">TIPO</th>
							<th width="20%">FECHA</th>
							<th width="20%">DETALLE</th>

						</tr>
					</thead>
					'.$htmlTbody.'
				</table>';
				
				$html .= "<div class='ui'>
							<i class='big step backward icon' onclick='ActualizaListado(\"First\")'></i>
							<i class='big backward icon' onclick='ActualizaListado(\"Prev\")'></i>
							<input type='text' maxlength='4' size='4' id='paginacion' onchange='CambiaPaginaListado(this)' value='".$Pagina."'>
							<i class='big forward icon' onclick='ActualizaListado(\"Next\")'></i>
							<i class='big step forward icon' onclick='ActualizaListado(\"Last\")'></i>
							
					    </div>
						
						"; 
							
				echo  $html;
	 	
        	 
 		} 

 		public function verDetalles($id){

 			$Query = "SELECT
								   CONCAT( Nombre,' ',NombreI,' ',Ap_paterno,' ',Ap_materno ) AS NOMBRE_SOLICITANTE,
								   pld_oficial_cumplimiento_log.SolicitudRevisionPLD,
								   pld_oficial_cumplimiento_log.fecha_cambio,
								   pld_oficial_cumplimiento_log.Comentarios
							  FROM pld_oficial_cumplimiento_log	
					    INNER JOIN solicitud ON solicitud.ID_Solicitud = pld_oficial_cumplimiento_log.ID_Solicitud  
					    WHERE pld_oficial_cumplimiento_log.id_OficialCumplimiento = '".$id."'
						  ORDER BY id_OficialCumplimiento
								
								
								";
				 
				  $RESPUESTA      = $this->db->Execute($Query); // debug($Query);


				 $html = '
				 <table class="ui table segment">
							<thead>
								<tr>
									<th align="center">Comentarios</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td align="left">
										'.$RESPUESTA->fields["Comentarios"].'
									</td>
								</tr>
							</tbody>
						</table>';

				echo $html;

 		}

 
 }





?>