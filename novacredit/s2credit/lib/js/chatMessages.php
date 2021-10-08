<?php

	/*********************************************************************
	*                                     SI HAY MENSAJES, LOS REGRESA   *
	*                                                                    *
	*                                                                    *
	*********************************************************************/
	

	if( $__cmd == "getMessages" ) { 
		$noheader = true;
		include($DOCUMENT_ROOT."/rutas.php");   
		$db = &ADONewConnection(SERVIDOR);      
		$db->PConnect(IP,USER,PASSWORD,NUCLEO); 
		
		
		//sleep(1);
		
		$Query = "SELECT		GROUP_CONCAT( chat.Mensaje SEPARATOR '<br>' ) AS Mensaje,
												chat.ID_Usuario_Origen,
												usuarios.Nombre AS Usuario
							FROM			chat
							LEFT JOIN	usuarios ON usuarios.ID_User = chat.ID_Usuario_Origen
							WHERE			chat.ID_Usuario_Destino = '".$_SESSION["ID_USR"]."'
							AND				chat.Hora_Leido IS NULL 
							GROUP BY	chat.ID_Usuario_Origen" ;
		$rs = $db->Execute($Query);
		if( !$rs->EOF ) {
			while( !$rs->EOF ) {
				$arrEdicion["Mensaje"][]           = "<span style='font-weight: bold;'>".htmlentities($rs->fields["Usuario"])."</span>: ".htmlentities($rs->fields["Mensaje"]);
				$arrEdicion["ID_Usuario_Origen"][] = $rs->fields["ID_Usuario_Origen"];
				$arrEdicion["Usuario"][]           = $rs->fields["Usuario"];
				$rs->MoveNext();
			}
			require_once( $class_path."json.php" ); 
			$json = new Services_JSON; 
			$getMessages = $json->encode($arrEdicion); 
			$Query = "UPDATE	chat
								SET			Hora_Leido = '".date("Y-m-d H:i:s")."'				
								WHERE		ID_Usuario_Destino = '".$_SESSION["ID_USR"]."'
								AND			Hora_Leido IS NULL " ;
			$db->Execute($Query);	
		}
		
	  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
	  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
	  header("Cache-Control: no-store, no-cache, must-revalidate"); 
	  header("Cache-Control: post-check=0, pre-check=0", false); 
	  header("Pragma: no-cache"); 
	  header('Content-Type: text/html'); 
		echo $getMessages;
	}
	
	
	/*********************************************************************
	*                                        REGISTRA UN NUEVO MENSAJE   *
	*                                                                    *
	*                                                                    *
	*********************************************************************/
	
	
	if( $__cmd == "sendMessage" ) { 
		$noheader = true;
		include($DOCUMENT_ROOT."/rutas.php");   
		$db = &ADONewConnection(SERVIDOR);      
		$db->PConnect(IP,USER,PASSWORD,NUCLEO); 
		

		$Query = "INSERT INTO chat( ID_Usuario_Origen,
																ID_Usuario_Destino,
																Mensaje ) 
							VALUES(	'".$_SESSION["ID_USR"]."',
											'".$ID_Usuario_Destino."', 
											'".$Mensaje."' )"; 
		$db->Execute($Query);
		
	  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
	  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
	  header("Cache-Control: no-store, no-cache, must-revalidate"); 
	  header("Cache-Control: post-check=0, pre-check=0", false); 
	  header("Pragma: no-cache"); 
	  header('Content-Type: text/html'); 
		echo $sendMessage;
	}

?>