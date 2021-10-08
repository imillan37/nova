<?php
	$noheader = true;
	include($DOCUMENT_ROOT."/rutas.php");   
	$db = &ADONewConnection(SERVIDOR);      
	$db->PConnect(IP,USER,PASSWORD,NUCLEO); 
	
	$Query = parseQuery( trim($_REQUEST["q"]), trim($_REQUEST["s"]) );
	
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

		require_once( $class_path."json.php" ); 
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
		$adicional  = explode( "FROM", $_Query );
		$adicional2 = explode( "WHERE", $adicional[1] );
		//debug($adicional2[1]);	 
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
		$parseQuery = trim( $arrFragmentos03[0] )." WHERE ".$adicional2[1]." LIKE '%".str_replace( " ", "%", $busqueda )."%'";
		
		$archivo = @fopen ( "traza.txt", "w" );
		@flock  ( $archivo, 2 );
		@fputs  ( $archivo, $parseQuery);
		@flock  ( $archivo, 3 );
		@fclose ( $archivo );
		
		
		return $parseQuery;
	}
?>
