<?php
/*
error_reporting(E_ALL);
ini_set( "display_errors", true );
*/
	$noheader = true;
	include($DOCUMENT_ROOT."/rutas.php");   
	include("json.php");
	$db = &ADONewConnection(SERVIDOR);      
	$db->PConnect(IP,USER,PASSWORD,NUCLEO); 
	
	$Query = parseQuery( trim($_REQUEST["q"]), trim($_REQUEST["s"]) );
	
	
	
	$Query = "SELECT		cat_material_construccion.ID_Material, 
											CONCAT( cat_material_construccion.Nombre, ' - ', cat_unidades.Nombre ) 
						FROM			cat_material_construccion, 
											cat_unidades               
						WHERE			cat_material_construccion.ID_Unidad = cat_unidades.ID_Unidad 
						AND				cat_material_construccion.ID_Tipocredito = '".$_REQUEST["m"]."' 
						AND				CONCAT( cat_material_construccion.Nombre, ' - ', cat_unidades.Nombre ) LIKE '%".str_replace( " ", "%", trim($_REQUEST["s"]) )."%'
						ORDER BY	cat_material_construccion.Nombre  ";
	$Query = "SELECT		ID_User, 
											CONCAT( Nombre, ' ', AP_Paterno, ' ', AP_Materno )
						FROM			usuarios
						WHERE			CONCAT( Nombre, ' ', AP_Paterno, ' ', AP_Materno ) LIKE '%".str_replace( " ", "%", trim($_REQUEST["s"]) )."%'
						ORDER BY	Nombre, 
											AP_Paterno, 
											AP_Materno"; 
	
	
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
			if( !$rs->EOF ) {
				while( !$rs->EOF ) {
			 		//$matches[] = $rs->fields[0]." - ".( str_replace( "ñ", "&#241;", $rs->fields[1] ) );
					//$matches[] = $rs->fields[0]." - ".utf8_encode($rs->fields[1]);
					$matches[] = utf8_encode($rs->fields[1]); 
					$rs->MoveNext();
				}	
			} else {
				$matches[] = utf8_encode("No se encontró nada "); 
			}
			$type = "text/plain";
			
			

			$json = new Services_JSON;
			
			$response = $json->encode($matches);
			
			//$response = json_encode($matches);
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


		$parseQuery = trim( $arrFragmentos03[0] )." WHERE cat_material_construccion.Nombre LIKE '%".str_replace( " ", "%", $busqueda )."%' AND 	".trim( $arrFragmentos03[1] ); 



		$archivo = @fopen ( "traza.txt", "w" );
		@flock  ( $archivo, 2 );
		@fputs  ( $archivo, $parseQuery);
		@flock  ( $archivo, 3 );
		@fclose ( $archivo );

		return $parseQuery;
	}
?>