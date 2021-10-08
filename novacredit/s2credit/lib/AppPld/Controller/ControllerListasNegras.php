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

 
class ControllerListasNegras {
 
 	public $db;
 	 
 		public function __construct($db){
	 		$this->db = $db; 
	 	} 	 

	 	public function VistaListas($Pagina,$Evento,$Filtro,$Opcion,$tipoCatalogo){
	 			$Pagina = intval($Pagina);

				$muestra = 20;
				$condicion = "";
                $cont_cat = 0;

                if($Filtro != "")
                {
                    $Filtro = str_replace(" ","%",$Filtro);
                    $condicion = "where CONCAT(Nombre,' ',Ap_paterno,' ',Ap_materno) like '%".$Filtro."%'";
                    $condicion1 = "where CONCAT(pld_cat_nombres_lp.Nombre_I,' ',pld_cat_nombres_lp.Ap_paterno,' ',pld_cat_nombres_lp.Ap_materno) like '%".$Filtro."%'";
                    $condicion2 = "where CONCAT(pld_cat_nombres_lc.Nombre_I,' ',pld_cat_nombres_lc.Ap_paterno,' ',pld_cat_nombres_lc.Ap_materno) like '%".$Filtro."%'";
                    $condicion3 = "where CONCAT(pld_cat_nombres_ppe.Nombre_I,' ',pld_cat_nombres_ppe.Ap_paterno,' ',pld_cat_nombres_ppe.Ap_materno) like '%".$Filtro."%'";
                    $condicion4 = "and CONCAT(pld_importacion_catalogos_dtl.Nombre_I,' ',pld_importacion_catalogos_dtl.Nombre_II) like '%".$Filtro."%'";
                    $condicion5 = "and CONCAT(pld_cat_nombres_sat.Nombre_I,' ',Ap_paterno,' ',Ap_materno) like '%".$Filtro."%'";
                }


                if($Opcion == "Terroristas" or ($Opcion == "Todo" or $Opcion == "" ))
                {
                    
                    if( !empty($tipoCatalogo) ){
	                	$restric = "AND pld_alias_listas_negras.UN_LIST_TYPE = '".$tipoCatalogo."' ";
                    }
                    
                    
                    $sql_terrorista = "SELECT
					pld_importacion_catalogos.ID_Importacion as ID,
					pld_importacion_catalogos_dtl.Nombre_I as Nombre,
					pld_importacion_catalogos_dtl.Nombre_II as Ap_paterno,
					' ' as Ap_materno,
					pld_importacion_catalogos_dtl.UN_LIST_TYPE AS Tipo,
					pld_importacion_catalogos.Registro AS fecha_ingreso
					from pld_importacion_catalogos
					INNER JOIN pld_importacion_catalogos_dtl on pld_importacion_catalogos_dtl.ID_Importacion = pld_importacion_catalogos.ID_Importacion
					LEFT JOIN pld_alias_listas_negras ON pld_alias_listas_negras.UN_LIST_TYPE = pld_importacion_catalogos_dtl.UN_LIST_TYPE
					where pld_importacion_catalogos.MD5 !='HISTORICO' $condicion4
					$restric
					
					
					 ";
                    $cont_cat++;
                }

                if($Opcion == "lc"  or ($Opcion == "Todo" or $Opcion == "" ))
                {
                    $sql_lc = "SELECT
					pld_cat_nombres_lc.ID_Nmb_lc as ID,
					pld_cat_nombres_lc.Nombre_I as Nombre,
					pld_cat_nombres_lc.Ap_paterno,
					pld_cat_nombres_lc.Ap_materno,
					'Listas bloqueadas' as Tipo, 
					pld_cat_nombres_lc.Fecha_sistema AS fecha_ingreso
					from pld_cat_nombres_lc
					$condicion2 ";
                    $cont_cat++;

                }

                if($Opcion == "lp"  or ($Opcion == "Todo" or $Opcion == "" ))
            {
                $sql_lp = "SELECT
					pld_cat_nombres_lp.ID_Nmb_lp as ID,
					pld_cat_nombres_lp.Nombre_I as Nombre,
					pld_cat_nombres_lp.Ap_paterno,
					pld_cat_nombres_lp.Ap_materno,
					'Lista Propia' as Tipo,
					pld_cat_nombres_lp.Fecha_sistema AS fecha_ingreso 
					from pld_cat_nombres_lp
					$condicion1 ";
                $cont_cat++;

            }

            if($Opcion == "ppe"  or ($Opcion == "Todo" or $Opcion == "" ))
            {
                $sql_ppe = "SELECT
					pld_cat_nombres_ppe.ID_Nmb_ppe as ID,
					pld_cat_nombres_ppe.Nombre_I as Nombre,
					pld_cat_nombres_ppe.Ap_paterno,
					pld_cat_nombres_ppe.Ap_materno,
					'Persona Politicamente Expuesta' as Tipo,
					pld_cat_nombres_ppe.Fecha_sistema AS fecha_ingreso 
					from pld_cat_nombres_ppe
					$condicion3 ";
                $cont_cat++;

            }
            
            if($Opcion == "sat"  or ($Opcion == "Todo" or $Opcion == "" ))
            {
                $sql_ppe = "SELECT
					pld_cat_nombres_sat.ID_Nmb_sat as ID,
					pld_cat_nombres_sat.Nombre_I as Nombre,
					pld_cat_nombres_sat.Ap_paterno,
					pld_cat_nombres_sat.Ap_materno,
					'SAT' as Tipo,
					pld_cat_nombres_sat.Fecha_sistema AS fecha_ingreso 
					from pld_cat_nombres_sat
					WHERE true
					$condicion5 ";
                $cont_cat++;

            }
								
		$Query_count = "SELECT count(*) as Total from (";

            if($sql_terrorista != "" )
            {
                $Query_count .= $sql_terrorista;
                $Query       .= "(".$sql_terrorista.")";
            }
            if($sql_lc != "")
            {
                if($cont_cat > 1)
                {
                    $Query_count .=  " UNION ".$sql_lc;
                    $Query       .=  " UNION (".$sql_lc.")";
                }else{
                    $Query_count .=  $sql_lc;
                    $Query       .=  "(".$sql_lc.")";
                }
            }
            if($sql_lp != "")
            {
                if($cont_cat > 1)
                {
                    $Query_count .=  " UNION ".$sql_lp;
                    $Query       .=  " UNION (".$sql_lp.")";
                }else{
                    $Query_count .=  $sql_lp;
                    $Query       .=  "(".$sql_lp.")";
                }
            }
            if($sql_ppe != "")
            {
                if($cont_cat > 1)
                {
                    $Query_count .=  " UNION ".$sql_ppe;
                    $Query       .=  " UNION (".$sql_ppe.")";
                }else{
                    $Query_count .=  $sql_ppe;
                    $Query       .=  "(".$sql_ppe.")";
                }
            }
            $Query_count .= ") as consulta";

				 $RESPUESTA      = $this->db->Execute($Query_count); 
				 $total_registros = $RESPUESTA->fields["Total"];
				 $total_paginas = number_format($total_registros/$muestra,2);
				 $total_paginas =  round($total_paginas, 0, PHP_ROUND_HALF_UP); 
				 $tot = $total_paginas - number_format($total_registros/$muestra,0);
				 //echo $total_paginas;

				 //if($tot>0){
				 //$total_paginas = $total_paginas+1;	
				 //}
				
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

				//echo $limite;
				 $Query .= " order by Nombre
								$limite
								
								";
				 
				  $RESPUESTA      = $this->db->Execute($Query);  //debug($Query);
				  
				  //echo $Query;
				  				
				 //echo $total_registros."<-->".$total_paginas."<-->".$Pagina."<-->".$limite_ini."<-->".$Query;
				 //return;
				  while( !$RESPUESTA->EOF ) { 
						
					 $Nombre     = $RESPUESTA->fields["Nombre"];
					 $Apellido   = $RESPUESTA->fields["Ap_paterno"]." ".$RESPUESTA->fields["Ap_materno"];
					 $Tipo       = ( empty($RESPUESTA->fields["Tipo"])?"OFAC":$RESPUESTA->fields["Tipo"] );
					 $Fecha      = $RESPUESTA->fields["fecha_ingreso"];
			
					 if( !empty($Nombre) ){
					
					 $htmlTbody .= '
			  
							<tr>
								<td align="left">'.strtoupper($Nombre).'</td>
								<td align="left">'.strtoupper($Apellido).'</td>
								<td align="left">'.strtoupper($Tipo).'</td>		
								<td align="left">'.strtoupper(ffecha($Fecha)).'</td>					
							</tr>
						  
						  ';
						 
					 } //@end
					 
					 $RESPUESTA->MoveNext(); 
				  } // fin while( !$RESPUESTA->EOF ) 	

				$html = ' 
	  				<br>
				  <table align="center" width="100%" class="ui table segment">
					<thead>
						<tr>
							<th width="30%">NOMBRE(S)</th>
							<th width="30%">APELLIDO(S)</th>
							<th width="20%">TIPO</th>
							<th width="20%">FECHA</th>

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

 
 }



?>