<?
	
	//sleep(3);
	
	$noheader = 1; 
	include($DOCUMENT_ROOT."/rutas.php");
	$db = &ADONewConnection(SERVIDOR);
	$db->PConnect(IP,USER,PASSWORD,NUCLEO);
	if( isset($_GET[fields]) ) { 
		$a = stripslashes( $_GET[fields] ); 
		$fields = unserialize($a); 
	}
	if( isset($_GET[headers]) ) { 
		$a = stripslashes( $_GET[headers] ); 
		$headers = unserialize($a); 
	}
	if( isset($_GET[anchos]) ) { 
		$a = stripslashes( $_GET[anchos] ); 
		$anchos = unserialize($a); 
	}
	if( isset($_GET[colalign]) ) { 
		$a = stripslashes( $_GET[colalign] ); 
		$colalign = unserialize($a); 
	}
	if( isset($_GET[botones]) ) { 
		$a = stripslashes( $_GET[botones] ); 
		$botones = unserialize($a); 
	}
	if( isset($_GET[botonesDef]) ) { 
		$a = stripslashes( $_GET[botonesDef] ); 
		$botonesDef = unserialize($a); 
	}
	
	$url   = stripslashes( $_GET[url] ); 
	$Query = stripslashes( $_GET[query] ); 
	
	$Query = insertWhere( $Query, " AND ".$fields[0]." = '".$id."' " ); 
	
	//$Query .= " AND ".$fields[0]." = '".$id."' " ;
	
	
	// <<< TRAZA: GUARDAR EL QUERY EN UN ARCHIVO DE TEXTO 
	/*
   $archivo = fopen ( 'traza.txt', "w" );
   flock  ( $archivo, 2 );
   fputs  ( $archivo, $Query );
   flock  ( $archivo, 3 );
   fclose ( $archivo );
	 */
	// >>> TRAZA: GUARDAR EL QUERY EN UN ARCHIVO DE TEXTO 

	
	$rs = $db->Execute($Query); 
	echo "<table class='tblDetalle' cellpadding='3' cellspacing='0' style='background-color: transparent !important;'>";
	echo "<tr>";
	echo "<th colspan='2' class='ui-pg-button ui-corner-all'>";
	echo "<div class='ui-pg-div' style='padding: 1px; float: left;'>DETALLE</div>";
	echo "<div class='ui-pg-div' style='padding: 1px; float: right; cursor: pointer;' onclick=\"javascript: jQuery('#ex2').jqmHide();\" title='Cerrar la ventana de detalle'>x</div>";
	echo "</th>";
	echo "</tr>";
	for( $x = 0; $x < sizeof($fields); $x++ ) {
		if( $headers[$x] != "#" ) {
			
			switch($colalign[$x]) { 
    		case "L":
        	$alineacion = "text-align: left; ";
	        break;
    		case "R":
      	  $alineacion = "text-align: right; ";
        	break;
    		case "C":
      	  $alineacion = "text-align: center; ";
      	  break;
		    default:
    	    $alineacion = "text-align: left; ";
      	  break;
			} 
			if( strpos( $fields[$x], "." ) === false ) {
				echo "<tr><th style='text-align: right;'>".str_replace( "<br>", " ", $headers[$x] )."&nbsp;</th><td style='".$alineacion."'>".htmlentities($rs->fields[$fields[$x]])."</td></tr>";
			} else {
				echo "<tr><th style='text-align: right;'>".str_replace( "<br>", " ", $headers[$x] )."&nbsp;</th><td style='".$alineacion."'>".htmlentities($rs->fields[substr( $fields[$x], ( strpos( $fields[$x], "." ) + 1 ), 99999 )])." </td></tr>";
			}
		}
	}
	if( sizeof($botones) > 0 ) {
		foreach( $botones AS $clave => $valor ) { 
			if( $valor[0] == "size" ) { 
				$anchoBoton = $valor[1]; 
			} else { 
				echo "<tr>";
				echo "<th style='text-align: center;'></th>";
				echo "<td style='text-align: center;'>";
				if( sizeof($valor) == 2 ) {
					echo "<input type='button' value='".$valor[0]."' style='width: ".$anchoBoton."px;' onclick=\"javascript: ".$valor[1]."\">";
				} else if( sizeof($valor) == 4 ) {
					echo "<input type='button' value='".$valor[0]."' style='width: ".$anchoBoton."px;' onclick=\"window.open( '".$valor[1]."?id=".$id."', 'wDet".md5($valor[1])."', 'width=".$valor[2].",height=".$valor[3]."resizable=yes,scrollbars=yes,statusbar=yes' );\">";
				}
				echo "</td>";
				echo "</tr>";
			}
		} 
	} 
	echo "<tr>";
	echo "<th colspan='2' class='ui-pg-button ui-corner-all'>";
	echo "<div style='float: left;'>";
	if( $botonesDef["Editar"] == true ) { 
		$botonEditar = "<td class='ui-pg-button ui-corner-all' title='Editar el registro seleccionado' style='cursor: pointer;' onclick=\"javascript: window.location.replace('".$url."?edkey=".$id."'); \">
											<div class='ui-pg-div'><span class='ui-icon ui-icon-pencil'></span></div>
										</td>";
	}
	if( $botonesDef["Eliminar"] == true ) { 
		$botonEliminar = "<td class='ui-pg-button ui-corner-all' title='Eliminar el registro seleccionado' style='cursor: pointer;' onclick=\"javascript: window.location.replace('".$url."?delkey=".$id."');\">
												<div class='ui-pg-div'><span class='ui-icon ui-icon-trash'></span></div>
											</td>";
	}
	echo "<table cellspacing='0' cellpadding='0' border='0' style='float: left; table-layout: auto;' class='ui-pg-table navtable'>
					<tbody>
						<tr>
							".$botonEditar."   
							<th style='border-bottom: 0;'>&nbsp;</th>
							".$botonEliminar." 
						</tr>
					</tbody>
				</table>";
	echo "</div>";
	echo "<div class='ui-pg-div' style='padding: 1px; float: right; cursor: pointer;' onclick=\"javascript: jQuery('#ex2').jqmHide();\" title='Cerrar la ventana de detalle'>x</div>";
	echo "</th>";
	echo "</tr>";
	echo "</table>";
	



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

/*
window.location.replace('".$PHP_SELF."?delkey='+seleccion); 
window.location.replace('".$PHP_SELF."?edkey='+seleccion); 
*/
?>