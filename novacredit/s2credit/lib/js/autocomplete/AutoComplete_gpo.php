<?php
	$noheader = true;
	include($DOCUMENT_ROOT."/rutas.php");   
	$db = &ADONewConnection(SERVIDOR);      
	$db->PConnect(IP,USER,PASSWORD,NUCLEO); 
	
	
	$Query = "SELECT     
							grupo_solidario.ID_grupo_soli,
							grupo_solidario.Nombre,
							grupo_solidario.Status_grupo,
							promotores.Nombre,
							sucursales.Nombre,
							SUM(grupo_solidario_integrantes.Monto_asignado),
							grupo_solidario_integrantes.Fecha_vence,
							grupo_solidario.Ciclo_gpo,
							grupo_solidario.Fecha_captura
					FROM grupo_solidario
					LEFT JOIN grupo_solidario_integrantes ON grupo_solidario.ID_grupo_soli = grupo_solidario_integrantes.ID_grupo_soli
					LEFT JOIN promotores                  ON grupo_solidario.ID_Promotor = promotores.Num_promo
					LEFT JOIN sucursales                  ON grupo_solidario.ID_Suc = sucursales.ID_Sucursal 
					WHERE  ";
	
	
		$Query = parseQuery( $Query, trim($_REQUEST["s"]) );
	
	//$Query = parseQuery( trim($_REQUEST["q"]), trim($_REQUEST["s"]) );
	
	if( !empty($_REQUEST["s"]) ) {
		if( isset($_REQUEST["e"]) ) {
			if( $_REQUEST["e"] < 0 ) {
				$Query .= " LIMIT 0, 1 ";
			} else {
				$Query .= " LIMIT ".$_REQUEST["e"].", 1 ";
			}
			$rs = $db->Execute($Query);
	 		echo $rs->fields[0];
		} else {
			$Query .= " LIMIT 0, 25 ";
			$rs = $db->Execute($Query);
			while( !$rs->EOF ) {
		 		//$matches[] = $rs->fields[0]." - ".( str_replace( "ñ", "&#241;", $rs->fields[1] ) );
				//$matches[] = $rs->fields[0]." - ".utf8_encode($rs->fields[1]);
				$matches[] = utf8_encode($rs->fields[1]); 
				$rs->MoveNext();
			}	
			$type = "text/plain";
			$response = json_encode($matches);
			header( "X-JSON: ".$response );
			header( "Content-Type: ".$type );
			echo $response;
		}
	}
	exit; 


	function parseQuery( $_Query, $busqueda ) {
		// EL SELECT DEL QUERY DEBEN SER DOS CAMPOS, EL ID Y LA DESCRIPCION 
		// EL QUERY NO DEBE TENER ALIAS EN NINGUNA PARTE 
		// SUPONEMOS QUE EL QUERY SIEMPRE TIENE 'WHERE'  
		$arrFragmentos01 = explode( "FROM", $_Query );	 
		if( $arrFragmentos01[0] == $_Query ) {
			$arrFragmentos01 = explode( "from", $_Query );	
		}
		$arrFragmentos02 = explode( ",", trim($arrFragmentos01[0]) );
		for( $x = 1; $x < sizeof($arrFragmentos02); $x++ ) {
			$campo .=  ",".trim($arrFragmentos02[$x]); 
		}
		$campo =  substr( $campo, 1, 999 ); 
		$arrFragmentos03 = explode( "WHERE", $_Query );
		if( $arrFragmentos03[0] == $_Query ) {
			$arrFragmentos03 = explode( "where", $_Query );	
		}
		$parseQuery = trim( $arrFragmentos03[0] )." WHERE  grupo_solidario.Nombre LIKE '%".str_replace( " ", "%", $busqueda )."%' ".trim( $arrFragmentos03[1] ." 
		            
		            AND  grupo_solidario.Status='Activo'
					AND (grupo_solidario.Status_grupo='CONFORMIDAD COMPLETA')
					AND  grupo_solidario.Cerrado='Y' 
					AND  grupo_solidario.Alta_cliente='Y'
					AND  grupo_solidario_integrantes.Status ='Activo' 
					AND  grupo_solidario.Alta_credito='Y'       
					AND  grupo_solidario_integrantes.Ciclo_renovado='N'
		
		
		GROUP BY grupo_solidario.Fecha_captura ASC ");
		
		
		
		$archivo = @fopen ( "traza_gpo.txt", "w" );
		@flock  ( $archivo, 2 );
		@fputs  ( $archivo, $parseQuery);
		@flock  ( $archivo, 3 );
		@fclose ( $archivo );
	
	return $parseQuery;
	
	}
?>
