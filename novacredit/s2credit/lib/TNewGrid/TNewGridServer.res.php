<?php
	error_reporting(E_ALL);
	ini_set("display_errors",true);
	$noheader = 1; 
	include($DOCUMENT_ROOT."/rutas.php");   
	$db = &ADONewConnection(SERVIDOR);      
	$db->PConnect(IP,USER,PASSWORD,NUCLEO); 
	$pPage     = $_GET['page'];			  
	$pLimit    = $_REQUEST['rows'];   
	$pSidx     = $_GET['sidx'];			  
	$pSord     = $_GET['sord'];			  
	$pQuery    = $_REQUEST['query'];  
	$fields    = $_REQUEST['fields']; 
	$arrFields = explode( ",", $fields ); 

	$ha = ""; 
	$wh = ""; 
	$searchOn = $_REQUEST['_search']; 
	if( $searchOn == 'true' ) { 
		$sarr = $_REQUEST; 
		foreach( $sarr AS $k => $v ) { 
			for( $x = 0; $x < sizeof($arrFields); $x++ ) { 
				if( str_replace( ".", "_", $arrFields[$x] ) == $k  ) { 
					if( isAliasField( $arrFields[$x], $pQuery ) ) {
						$ha .= " AND ".$arrFields[$x]." LIKE '%".$v."%'"; 
					} else {
						$wh .= " AND ".$arrFields[$x]." LIKE '%".$v."%'"; 
					}
					break; 
				} 
			} 
		} 
	} 

	if( !$pSidx ) { 
		$pSidx = 1;   
	}
	$pQuery = insertWhere( $pQuery, $wh ); 
	$pQuery = insertHaving( $pQuery, $ha ); 
	$pQuery .= "	ORDER BY ".$pSidx." ".$pSord; 

	// <<< TRAZA: GUARDAR EL QUERY EN UN ARCHIVO DE TEXTO 
   $archivo = fopen ( 'traza.txt', "w" );
   flock  ( $archivo, 2 );
   fputs  ( $archivo, $pQuery );
   flock  ( $archivo, 3 );
   fclose ( $archivo );
	// >>> TRAZA: GUARDAR EL QUERY EN UN ARCHIVO DE TEXTO 



	$rs = $db->Execute($pQuery);  
	$count = $rs->RecordCount(); 
	if( $count >0 ) { 
		$total_pages = ceil($count/$pLimit); 
	} else { 
		$total_pages = 0; 
	} 
	if ($pPage > $total_pages) {
		$pPage = $total_pages; 
	}
	$start = $pLimit * $pPage - $pLimit; // do not put $pLimit * ($pPage - 1) 
	$rs->Move($start); 
	$response->page    = $pPage;       
	$response->total   = $total_pages; 
	$response->records = $count;       
	$i = 0; 
	while( !$rs->EOF ) { 
		for( $x = 0;  $x < $rs->FieldCount(); $x++ ) { 
			$arrColumnas[$i][] = htmlentities($rs->fields[$x]);
		} 
		$response->rows[$i]['id']   = $rs->fields[0]; 
		$response->rows[$i]['cell'] = $arrColumnas[$i]; 
		$i++;
		if( $i == $pLimit  ) {
			break;
		}
		$rs->MoveNext(); 
	} 
	require_once($class_path."json.php");
	$json = new Services_JSON;
	echo $json->encode($response);



	function insertWhere( $Query, $wh ) { 
		$contenido = str_replace( "\n", " ", $Query ); 
		$contenido = str_replace( "\t", " ", $Query ); 
		$posGroup = stripos( $contenido, " GROUP " );
		if( $posGroup === false ) { 
			$insertWhere = $Query." ".$wh; 
		} else { 
			$insertWhere = substr( $Query, 0, $posGroup )." ".$wh." ".substr( $Query, $posGroup, 99999999 ); 
		}
		return $insertWhere; 
	}


	function insertHaving( $Query, $ha ) { 
		$insertHaving = $Query;
		if( $ha != "" ) { 
			$insertHaving = $Query." HAVING 0 = 0 ".$ha;
		}
		return $insertHaving; 
	}


	function isAliasField( $campo, $query ) { 
		$isAliasField = false;
		$query = trim($query); 
		$query = str_replace( "\n", " ", $query ); 
		$query = str_replace( "\t", " ", $query ); 
		$query = str_replace( ",", " ", $query ); 
		$query = strtoupper($query); 
		$campo = trim($campo); 
		$campo = strtoupper($campo); 
		$arrPalabras = explode( " ", $query ); 
		$arrDesnatado = array();
		for( $x = 0; $x < sizeof($arrPalabras); $x++ ) { 
			if( trim($arrPalabras[$x]) != "" ) { 
				$arrDesnatado[] = trim($arrPalabras[$x]);
			}
		} 
		for( $x = 0; $x < sizeof($arrDesnatado); $x++ ) { 
			if( $arrDesnatado[$x] == $campo ) { 
				if( $arrDesnatado[($x-1)] == "AS" ) {
					$isAliasField = true;
					break;
				}
			} 
		} 
		return $isAliasField; 
	}


	/*
	// <<< TRAZA: GUARDAR EL QUERY EN UN ARCHIVO DE TEXTO 
   $archivo = fopen ( 'traza.txt', "w" );
   flock  ( $archivo, 2 );
   fputs  ( $archivo, $wh );
   flock  ( $archivo, 3 );
   fclose ( $archivo );
	// >>> TRAZA: GUARDAR EL QUERY EN UN ARCHIVO DE TEXTO 
	*/
?>