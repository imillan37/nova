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

 
class ControllerLogMatrizRiesgo {
 
 	public $db;
 	 
 		public function __construct($db){
	 		$this->db = $db; 
	 	}
 		

	 	public function consulta_lista($Pagina,$Evento){

	 			
	 			$muestra = 10;

	 			$Query_count= "SELECT count(*) as todo	
						from pld_matriz_riesgo_clasificacion
						ORDER BY Registro desc ";

			$rs_count = $this->db->Execute($Query_count);

			$total_registros = $rs_count->fields["todo"];
			$total_paginas = number_format($total_registros/$muestra,2);
			$total_paginas =  round($total_paginas, 0, PHP_ROUND_HALF_UP);

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



	 			$Query = "SELECT pld_matriz_riesgo_clasificacion.Identificador,
								 pld_matriz_riesgo_clasificacion.Puntos_Minimos, 
								 pld_matriz_riesgo_clasificacion.ID_Usuario, 
							 	 pld_matriz_riesgo_clasificacion.Registro,
								CONCAT(usuarios.Nombre,' ',usuarios.AP_Paterno,' ',usuarios.AP_Materno) AS Nombre
						from pld_matriz_riesgo_clasificacion
						INNER JOIN usuarios ON usuarios.ID_User = pld_matriz_riesgo_clasificacion.ID_Usuario
						ORDER BY Registro desc
						$limite";
	 			
	 			$rs = $this->db->Execute($Query);

	 			//debug($Query);
	 			
				$Datos = "	<br><TABLE BORDER='0' BGCOLOR='black' ALIGN='center' CELLSPACING=1 CELLPADDING=3  ID='small' class='ui table segment' width='50%'>";

	    		while(!$rs->EOF){

	    		$fecha 		= $rs->fields["Registro"];
	 			$nombre  	= $rs->fields["Nombre"];
	 			$punto_minimos = $rs->fields["Puntos_Minimos"];
	 			$Identificador = $rs->fields["Identificador"];

	 			$Query = "SELECT puntos_minimos 
 						FROM pld_matriz_riesgo_dtl
 						WHERE Identificador = '".$Identificador."'
					
								";				 
				  $RESPUESTA      = $this->db->Execute($Query); // debug($Query);

	    			$Datos .= "<tr class='' ><td width='25%' style='text-align:left;'>".$nombre."</td>
												 <td width='25%' style='text-align:left;'>".$punto_minimos."</td>
												 <td width='25%' style='text-align:left;'>".$fecha."</td>
												 <td width='25%' style='text-align:center;'><input type='submit' id='".$Identificador."' name='botones' value='Detalle' onclick='muestra(".$Identificador.");' class='ui blue button tiny'> </td>";
	 			

	    		$rs->MoveNext();	
	    		}
				
	 			$Datos .= "</table><div class='ui'>
							<i class='big step backward icon' onclick='ActualizaListado(\"First\")'></i>
							<i class='big backward icon' onclick='ActualizaListado(\"Prev\")'></i>
							<input type='text' maxlength='4' size='4' id='paginacion' onchange='CambiaPaginaListado(this)' value='".$Pagina."'>
							<i class='big forward icon' onclick='ActualizaListado(\"Next\")'></i>
							<i class='big step forward icon' onclick='ActualizaListado(\"Last\")'></i>
							
					    </div>
						
						"; 

	 			return $Datos;	

	 		

	 	}


	 	public function verDetalles($id){

 			$Query = "SELECT Descripcion, Ponderacion 
 						FROM pld_matriz_riesgo_dtl
 						WHERE Identificador = '".$id."'
						and  Determinante != 'Y'
								";
				 
				  $RESPUESTA      = $this->db->Execute($Query); // debug($Query);
		  $html = '<table class="ui table segment">
							
							<tbody>';
		while(!$RESPUESTA->EOF){
			$html .= '
				 
								<tr>
									<td align="left">
										'.$RESPUESTA->fields["Descripcion"].'
									</td>
									<td align="left">
										'.$RESPUESTA->fields["Ponderacion"].'
									</td>

								</tr>
							';
		$RESPUESTA->MoveNext();
		}

		$html .= '</tbody>
						</table>';
				 
				//return debug($Query);
				echo $html;

 		}


}//fin clase




?>