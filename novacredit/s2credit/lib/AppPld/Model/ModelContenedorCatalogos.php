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
 *  @ Cargamos Alta PPE Model  
 */ 


if( !empty($_FileContenct) ){
//***********modificacion por RLJ para el curp**********//
		$noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
        
        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);	
        
        ini_set('auto_detect_line_endings', true);
	
		//VERIFICAR EXTENSIÓN
		$Ext_file = substr($_FILES["FileContenct"]["name"],(strlen($_FILES["FileContenct"]["name"])-4));

        if($TipoPersonaFile == "PPE")
        {
            $tabla    = "pld_cat_nombres_ppe";
            $campo_id = "ID_Nmb_ppe";
            $Tipo     = "PPE";
        }elseif($TipoPersonaFile == "LP")
        {
            $tabla    = "pld_cat_nombres_lp";
            $campo_id = "ID_Nmb_lp";
            $Tipo     = "LP";
        }elseif($TipoPersonaFile == "LC")
        {
            $tabla    = "pld_cat_nombres_lc";
            $campo_id = "ID_Nmb_lc";
            $Tipo     = "LC";
        }elseif($TipoPersonaFile == "SAT")
        {
            $tabla    = "pld_cat_nombres_sat";
            $campo_id = "ID_Nmb_sat";
            $Tipo     = "SAT";
        }


		if( $Ext_file != ".txt" ){
			echo "EXTENCION NO VALIDA";
		  die();
		} // fin if

		if (is_uploaded_file($_FILES['FileContenct']['tmp_name'])) {
			
					
			//---> Archivo a array
			$arrLienas = file($_FILES['FileContenct']['tmp_name']);
			
			//---> Revisamos los heders
			
			$linea = str_replace("\n"," ",$arrLienas[0]);
			$linea = str_replace("\t"," ",$arrLienas[0]);
			
			if ( trim($linea) == 'NOMBRE PATERNO MATERNO RFC CURP' ){
				$Valida='VALIDO';
			}else{
				 echo 'EL CONTENIDO NO ES VALIDO';
				die();
			}
			
			//---> Accion
            $Query = "SELECT max(ID_carga) as Maximo FROM pld_cat_listas_negras_log ";

            $rs_max = $db->Execute($Query);
            $ID_max = $rs_max->fields["Maximo"] + 1;
            $total_registros = 0;
			
			foreach( $arrLienas as $linea ){
				
			  	$arrLienasDentro = explode("\t",$linea);
						
					$NOMBRE  = $arrLienasDentro[0]; // GRUPO
					$PATERNO = $arrLienasDentro[1]; // ID_PROMOTOR
					$MATERNO = $arrLienasDentro[2]; // ID_PROMOTOR
					$RFC     = $arrLienasDentro[3]; // ID_PROMOTOR
                    $CURP    = $arrLienasDentro[4]; // ID_PROMOTOR
						
					//---->
					// QUITAMOA TODO LO QUE NO NECESITAMOS QUE PUEDA CAUDAR UN ERROR SQL
					
						$NOMBRE  = str_replace("'","",$NOMBRE);
						$PATERNO = str_replace("'","",$PATERNO);
						$MATERNO = str_replace("'","",$MATERNO);
						$RFC     = str_replace("'","",$RFC);
                        $CURP    = str_replace("'","",$CURP);
						
						$NOMBRE  = str_replace('"',"",$NOMBRE);
						$PATERNO = str_replace('"',"",$PATERNO);
						$MATERNO = str_replace('"',"",$MATERNO);
						$RFC     = str_replace('"',"",$RFC);
                        $CURP    = str_replace("'","",$CURP);
						
						$NOMBRE  = str_replace(",","",$NOMBRE);
						$PATERNO = str_replace(",","",$PATERNO);
						$MATERNO = str_replace(",","",$MATERNO);
						$RFC     = str_replace(",","",$RFC);
                        $CURP    = str_replace("'","",$CURP);
					
					if($NOMBRE != "NOMBRE" and $PATERNO != "PATERNO" and $MATERNO != "MATERNO" and $RFC != "RFC" and $CURP != "CURP")
					{
						
						$Query = "SELECT ".$campo_id." FROM ".$tabla."
									WHERE Nombre_I 	 = '".$NOMBRE."' 
									AND   Ap_paterno = '".$PATERNO."' 
									AND   Ap_materno = '".$MATERNO."'  ";
									
						$rs_cons = $db->Execute($Query);
						$ID_Nmb = $rs_cons->fields[$campo_id];
						
						if( empty($ID_Nmb) )
						{
							$Query = "INSERT INTO ".$tabla." (ID_Usr,Nombre_I,Ap_paterno,Ap_materno,RFC,CURP)
	 					  			   VALUES
	 					  		   		  (".$ID_USR.", '".mb_strtoupper($NOMBRE)."', '".mb_strtoupper($PATERNO)."', '".mb_strtoupper($MATERNO)."', '".mb_strtoupper($RFC)."', '".mb_strtoupper($CURP)."')";
							
							$db->Execute($Query); // debug($Query);
                            $id_insert = $db->_insertid();

                            $Query = "INSERT INTO pld_cat_listas_negras_log (ID_persona,ID_Usr,Tipo,Accion,ID_carga)
	 					  			   VALUES
	 					  		   		  (".$id_insert.", ".$ID_USR.", '".$Tipo."', 'INSERT',".$ID_max.")";

                            $db->Execute($Query); // debug($Query);
                            $total_registros++;

						}else
						{
							$Query = "UPDATE ".$tabla."
										SET ID_Usr = ".$ID_USR.",
										Nombre_I = '".mb_strtoupper($NOMBRE)."',
										Ap_paterno = '".mb_strtoupper($PATERNO)."',
										Ap_materno = '".mb_strtoupper($MATERNO)."',
										RFC = '".mb_strtoupper($RFC)."',
										CURP = '".mb_strtoupper($CURP)."'  WHERE  ".$campo_id." = '".$ID_Nmb."' ";
							
							$db->Execute($Query);  //debug($Query);

                            $Query = "INSERT INTO pld_cat_listas_negras_log (ID_persona,ID_Usr,Tipo,Accion,ID_carga)
	 					  			   VALUES
	 					  		   		  (".$ID_Nmb.", ".$ID_USR.", '".$Tipo."', 'UPDATE',".$ID_max.")";

                            $db->Execute($Query); // debug($Query);
                            $total_registros++;
						}
						  
					}
						
			} // fin foreach


            $Query = "UPDATE pld_cat_listas_negras_log SET sentencia = '".$total_registros."'
                        WHERE pld_cat_listas_negras_log.ID_carga =  ".$ID_max." ";

            $db->Execute($Query); // debug($Query);


			echo "LISTO";
		   die();
			
		} else {
		
			echo "ERROR TRASFERENCIA";
		   die();	
		
		}
		
	die();
		
} // fin if

/**
 *
 *  @ Cargamos Alta/Edicion Model
 */


if( $__cmd == "setAgregaCatalogos" ){
//***********modificacion por RLJ para el curp**********//
		
		$noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerContenedorCatalogos.php");
		setlocale(LC_CTYPE, 'es');
        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);

        $Nombre     = utf8_decode($Nombre);
        $ApPaterno  = utf8_decode($ApPaterno);
        $ApMaterno  = utf8_decode($ApMaterno);
        $RFC        = utf8_decode($RFC);
        $CURP       = utf8_decode($CURP);

        $Nombre     = mb_strtoupper($Nombre);
        $ApPaterno  = mb_strtoupper($ApPaterno);
        $ApMaterno  = mb_strtoupper($ApMaterno);
        $RFC        = mb_strtoupper($RFC);
        $CURP       = mb_strtoupper($CURP);
    






        if($TipoPersona == "PPE")
        {
            if( $action == "INSERT" ){

                $arrDatos = array($Nombre,$ApPaterno,$ApMaterno,$RFC,$CURP);
                $Objeto = new ControllerContenedorCatalogos($db);
                echo $Objeto->AddPersonas($arrDatos,$ID_USR);

            }

            if( $action == "UPDATE" ){

                $arrDatos = array($Nombre,$ApPaterno,$ApMaterno,$RFC,$CURP);
                $Objeto = new ControllerContenedorCatalogos($db);
                echo $Objeto->EditionPersonas($arrDatos,$ID_USR,$id);

            }
        }
        if($TipoPersona == "LP")
        {
            if( $action == "INSERT" ){

                $arrDatos = array($Nombre,$ApPaterno,$ApMaterno,$RFC,$CURP);
                $Objeto = new ControllerContenedorCatalogos($db);
                echo $Objeto->AddListasPropias($arrDatos,$ID_USR);

            }

            if( $action == "UPDATE" ){

                $arrDatos = array($Nombre,$ApPaterno,$ApMaterno,$RFC,$CURP);
                $Objeto = new ControllerContenedorCatalogos($db);
                echo $Objeto->EditionListasPropias($arrDatos,$ID_USR,$id);

            }
        }
        if($TipoPersona == "SAT")
        {
            if( $action == "INSERT" ){

                $arrDatos = array($Nombre,$ApPaterno,$ApMaterno,$RFC,$CURP);
                $Objeto = new ControllerContenedorCatalogos($db);
                echo $Objeto->AddSAT($arrDatos,$ID_USR);

            }

            if( $action == "UPDATE" ){

                $arrDatos = array($Nombre,$ApPaterno,$ApMaterno,$RFC,$CURP);
                $Objeto = new ControllerContenedorCatalogos($db);
                echo $Objeto->EditionSAT($arrDatos,$ID_USR,$id);

            }
        }
        if($TipoPersona == "LC")
        {
            if( $action == "INSERT" ){

                $arrDatos = array($Nombre,$ApPaterno,$ApMaterno,$RFC,$CURP);
                $Objeto = new ControllerContenedorCatalogos($db);
                echo $Objeto->AddListasCondusef($arrDatos,$ID_USR);

            }

            if( $action == "UPDATE" ){

                $arrDatos = array($Nombre,$ApPaterno,$ApMaterno,$RFC,$CURP);
                $Objeto = new ControllerContenedorCatalogos($db);
                echo $Objeto->EditionListasCondusef($arrDatos,$ID_USR,$id);

            }
        }
        
        
        	 
}

/**
 *
 *  @ Cargamos Alta/Edicion Model
 */


if( $__cmd == "setConsultaCatalogos" ){

	    $noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerContenedorCatalogos.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);
        $Objeto = new ControllerContenedorCatalogos($db);
   		echo $Objeto->VistaPersonas(utf8_decode($BuscaRFC),$Evento,$Pagina);

}

if( $__cmd == "setConsultaCatalogos" ){

	    $noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerContenedorCatalogos.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);
        $Objeto = new ControllerContenedorCatalogos($db);
   		echo $Objeto->VistaPersonas(utf8_decode($BuscaRFC),$Evento,$Pagina);

}

/**
 *
 *  @ Cargamos Alta/Edicion Model RLJ
 */

if( $__cmd == "setConsultaCatalogosSAT" ){
	
	$noheader=1;
    include($DOCUMENT_ROOT."/rutas.php");
    require_once("../Controller/ControllerContenedorCatalogos.php");
	
	$db = &ADONewConnection(SERVIDOR);
    $db->PConnect(IP,USER,PASSWORD,NUCLEO);

    $Objeto = new ControllerContenedorCatalogos($db);
    echo $Objeto->VistaSAT(utf8_decode($BuscaRFC),$Evento,$Pagina);

}

if( $__cmd == "setConsultaCatalogosLP" ){

    $noheader=1;
    include($DOCUMENT_ROOT."/rutas.php");
    require_once("../Controller/ControllerContenedorCatalogos.php");

    $db = &ADONewConnection(SERVIDOR);
    $db->PConnect(IP,USER,PASSWORD,NUCLEO);

    $Objeto = new ControllerContenedorCatalogos($db);
    echo $Objeto->VistaListasPropias(utf8_decode($BuscaRFC),$Evento,$Pagina);

}

if( $__cmd == "setConsultaCatalogosLC" ){

    $noheader=1;
    include($DOCUMENT_ROOT."/rutas.php");
    require_once("../Controller/ControllerContenedorCatalogos.php");

    $db = &ADONewConnection(SERVIDOR);
    $db->PConnect(IP,USER,PASSWORD,NUCLEO);

    $Objeto = new ControllerContenedorCatalogos($db);
    echo $Objeto->VistaListasCondusef(utf8_decode($BuscaRFC),$Evento,$Pagina);

}

/**
 *
 *  @ Cargamos Alta/Edicion Model
 */



if( $__cmd == "setEliminarCatalogos" ){
		
	    $noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerContenedorCatalogos.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
        $Objeto = new ControllerContenedorCatalogos($db);
        echo $Objeto->DeletePersonas($id,$ID_USR);
    		 
}

/**
 *
 *  @ Cargamos Alta/Edicion Model
 */



if( $__cmd == "setEliminarCatalogosSAT" ){
		
	    $noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerContenedorCatalogos.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
        $Objeto = new ControllerContenedorCatalogos($db);
        echo $Objeto->DeleteSAT($id,$ID_USR);
    		 
}


/**
 *
 *  @ Cargamos Alta/Edicion Model  RLJ
 */



if( $__cmd == "setEliminarCatalogosLP" ){

    $noheader=1;
    include($DOCUMENT_ROOT."/rutas.php");
    require_once("../Controller/ControllerContenedorCatalogos.php");

    $db = &ADONewConnection(SERVIDOR);
    $db->PConnect(IP,USER,PASSWORD,NUCLEO);

    $Objeto = new ControllerContenedorCatalogos($db);
    echo $Objeto->DeleteListasPropias($id,$ID_USR);

}


/**
 *
 *  @ Cargamos Alta/Edicion Model
 */



if( $__cmd == "setEliminarCatalogosLC" ){

    $noheader=1;
    include($DOCUMENT_ROOT."/rutas.php");
    require_once("../Controller/ControllerContenedorCatalogos.php");

    $db = &ADONewConnection(SERVIDOR);
    $db->PConnect(IP,USER,PASSWORD,NUCLEO);

    $Objeto = new ControllerContenedorCatalogos($db);
    echo $Objeto->DeleteListasCondusef($id,$ID_USR);

}

if( $__cmd == "setAgregaCatalogosPais" ){
	
	$noheader=1;
    include($DOCUMENT_ROOT."/rutas.php");
   
    $db = &ADONewConnection(SERVIDOR);
    $db->PConnect(IP,USER,PASSWORD,NUCLEO);
	
	if( !empty($_POST["ID_pais"]) and !empty($_POST["motivo"]) ){
		
		$query = "INSERT INTO pld_cat_paraisos_fiscales(
		                      ID_Pais, 
		                      motivo, 
		                      ID_User, 
		                      Registro)
                      VALUES( '".$_POST["ID_pais"]."', 
                              '".$_POST["motivo"]."', 
                              '".$_SESSION["ID_USR"]."', 
                              NOW()) ";
                              
                              $db->Execute($query);
                              $id_insert = $db->_insertid();
                              
                              ECHO "LISTO";
		
		$query = "INSERT INTO pld_cat_listas_negras_log (ID_persona,ID_Usr,Tipo,Accion,ID_carga)
	 				   VALUES (".$_POST["ID_pais"].", ".$ID_USR.", 'PAIS_RIESGO', 'INSERT','".$ID_max."')";
	 				   
	 		$db->Execute($query);
		
	}else{
		echo "DATOS FALTANTES";
	}
	
}

if( $__cmd == "setEliminaCatalogosPais" ){
	
	$noheader=1;
    include($DOCUMENT_ROOT."/rutas.php");
   
    $db = &ADONewConnection(SERVIDOR);
    $db->PConnect(IP,USER,PASSWORD,NUCLEO);
	
	if( !empty($_POST["ID_pais"]) ){
		
		$query = "DELETE FROM pld_cat_paraisos_fiscales 
		                WHERE ID_Pais IN ('".$_POST["ID_pais"]."') ";
		
		                      $db->Execute($query);
                              
                              ECHO "LISTO";
		
		$query = "INSERT INTO pld_cat_listas_negras_log (ID_persona,ID_Usr,Tipo,Accion,ID_carga)
	 				   VALUES (".$_POST["ID_pais"].", ".$ID_USR.", 'PAIS_RIESGO', 'DELETE','".$ID_max."')";
	 				   
	 		$db->Execute($query);
		
	}else{
		echo "DATOS FALTANTES";
	}
	
}

/**
 *
 *  @ Cargamos Alta/Edicion Model LP ]****RLJ
 */

if( $__cmd == "setEditarCatalogosLP" ){

    $noheader=1;
    include($DOCUMENT_ROOT."/rutas.php");
    require_once("../Controller/ControllerContenedorCatalogos.php");

    $db = &ADONewConnection(SERVIDOR);
    $db->PConnect(IP,USER,PASSWORD,NUCLEO);

    $Query = "SELECT
        					 Nombre_I,
							 Ap_paterno,
							 Ap_materno,
							 RFC,
							 CURP
						FROM pld_cat_nombres_lp
					   WHERE pld_cat_nombres_lp.ID_Nmb_lp = '".$id."'";

    $RESPUESTA   = $db->Execute($Query);
    $Nombre_I    = $RESPUESTA->fields["Nombre_I"];
    $Ap_paterno  = $RESPUESTA->fields["Ap_paterno"];
    $Ap_materno  = $RESPUESTA->fields["Ap_materno"];
    $RFC         = $RESPUESTA->fields["RFC"];
    $CURP        = $RESPUESTA->fields["CURP"];

    $arrEdicion["Nombre"][]    = utf8_encode($Nombre_I);
    $arrEdicion["ApPaterno"][] = utf8_encode($Ap_paterno);
    $arrEdicion["ApMaterno"][] = utf8_encode($Ap_materno);
    $arrEdicion["RFC"][]       = utf8_encode($RFC);
    $arrEdicion["CURP"][]      = utf8_encode($CURP);


    require_once( $class_path."json.php" );
    $json       = new Services_JSON;
    $formulario = $json->encode($arrEdicion);

    echo $formulario;

}

if( $__cmd == "setEditarCatalogosSAT" ){

    $noheader=1;
    include($DOCUMENT_ROOT."/rutas.php");
    require_once("../Controller/ControllerContenedorCatalogos.php");

    $db = &ADONewConnection(SERVIDOR);
    $db->PConnect(IP,USER,PASSWORD,NUCLEO);

    $Query = "SELECT
					Nombre_I,
					Ap_paterno,
					Ap_materno,
					RFC,
					CURP
			   FROM pld_cat_nombres_sat
			  WHERE pld_cat_nombres_sat.ID_Nmb_sat = '".$id."'";

    $RESPUESTA   = $db->Execute($Query);
    $Nombre_I    = $RESPUESTA->fields["Nombre_I"];
    $Ap_paterno  = $RESPUESTA->fields["Ap_paterno"];
    $Ap_materno  = $RESPUESTA->fields["Ap_materno"];
    $RFC         = $RESPUESTA->fields["RFC"];
    $CURP        = $RESPUESTA->fields["CURP"];

    $arrEdicion["Nombre"][]    = utf8_encode($Nombre_I);
    $arrEdicion["ApPaterno"][] = utf8_encode($Ap_paterno);
    $arrEdicion["ApMaterno"][] = utf8_encode($Ap_materno);
    $arrEdicion["RFC"][]       = utf8_encode($RFC);
    $arrEdicion["CURP"][]      = utf8_encode($CURP);


    require_once( $class_path."json.php" );
    $json       = new Services_JSON;
    $formulario = $json->encode($arrEdicion);

    echo $formulario;

} //@end if


/**
 *
 *  @ Cargamos Alta/Edicion Model LC ]****RLJ
 */

if( $__cmd == "setEditarCatalogosLC" ){

    $noheader=1;
    include($DOCUMENT_ROOT."/rutas.php");
    require_once("../Controller/ControllerContenedorCatalogos.php");

    $db = &ADONewConnection(SERVIDOR);
    $db->PConnect(IP,USER,PASSWORD,NUCLEO);

    $Query = "SELECT
        					 Nombre_I,
							 Ap_paterno,
							 Ap_materno,
							 RFC,
							 CURP
						FROM pld_cat_nombres_lc
					   WHERE pld_cat_nombres_lc.ID_Nmb_lc = '".$id."'";

    $RESPUESTA   = $db->Execute($Query);
    $Nombre_I    = $RESPUESTA->fields["Nombre_I"];
    $Ap_paterno  = $RESPUESTA->fields["Ap_paterno"];
    $Ap_materno  = $RESPUESTA->fields["Ap_materno"];
    $RFC         = $RESPUESTA->fields["RFC"];
    $CURP        = $RESPUESTA->fields["CURP"];

    $arrEdicion["Nombre"][]    = utf8_encode($Nombre_I);
    $arrEdicion["ApPaterno"][] = utf8_encode($Ap_paterno);
    $arrEdicion["ApMaterno"][] = utf8_encode($Ap_materno);
    $arrEdicion["RFC"][]       = utf8_encode($RFC);
    $arrEdicion["CURP"][]      = utf8_encode($CURP);


    require_once( $class_path."json.php" );
    $json       = new Services_JSON;
    $formulario = $json->encode($arrEdicion);

    echo $formulario;



}

/**
 *
 *  @ Cargamos Alta/Edicion Model
 */



if( $__cmd == "setEditarCatalogos" ){
		
	    $noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerContenedorCatalogos.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
        	$Query = "SELECT 
        					 Nombre_I,
							 Ap_paterno,
							 Ap_materno,
							 RFC,
							 CURP
						FROM pld_cat_nombres_ppe
					   WHERE pld_cat_nombres_ppe.ID_Nmb_ppe = '".$id."'";
					   
					    $RESPUESTA   = $db->Execute($Query);
					    $Nombre_I    = $RESPUESTA->fields["Nombre_I"];
					    $Ap_paterno  = $RESPUESTA->fields["Ap_paterno"];
					    $Ap_materno  = $RESPUESTA->fields["Ap_materno"];
					    $RFC         = $RESPUESTA->fields["RFC"];
                        $CURP        = $RESPUESTA->fields["CURP"];
        
						$arrEdicion["Nombre"][]    = utf8_encode($Nombre_I);
						$arrEdicion["ApPaterno"][] = utf8_encode($Ap_paterno);
						$arrEdicion["ApMaterno"][] = utf8_encode($Ap_materno);
						$arrEdicion["RFC"][]       = utf8_encode($RFC);
                        $arrEdicion["CURP"][]      = utf8_encode($CURP);
		
					
						require_once( $class_path."json.php" ); 
						$json       = new Services_JSON; 
						$formulario = $json->encode($arrEdicion); 
						
						echo $formulario;
        
        
    		 
}

/**
 *
 * @author MarsVoltoso (CFA)
 * @category JavaScript
 * @created Mon Sep 15, 2014
 * @version 1.0
 * @Puestos
 */	
 
 
 
/**
 *
 *  @ Cargamos Alta/Edicion PUESTOS  
 */


if( $__cmd == "setAgregaCatalogosPuestos" ){
	
		$noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerContenedorCatalogos.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
        if( $accion == "INSERT" ){
	    
        $arrDatos = array(utf8_decode($Puesto));

        $Objeto = new ControllerContenedorCatalogos($db);
        echo $Objeto->AddPuestosDatosPersonas($arrDatos,$ID_USR);
	
		}
		
		
		if( $accion == "UPDATE" ){	
		
		$arrDatos = array(utf8_decode($Puesto));
        $Objeto = new ControllerContenedorCatalogos($db);
        echo $Objeto->EditPuestosDatosPersonas($arrDatos,$ID_USR,$id);
		
		
		}
	
} // if


/**
 *
 *  @ Cargamos Consulta  
 */ 

if( $__cmd == "setConsultaPuestosCatalogos" ){
		
		$noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerContenedorCatalogos.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
        $Objeto = new ControllerContenedorCatalogos($db);
        echo $Objeto->VistaPersonasPuestos(utf8_decode($BuscaPuesto),$Evento,$Pagina);
	
} // if

/** 
 *
 *  @ Eliminar Puesto
 */	

if( $__cmd == "setEliminarPuestoCatalogos" ){
	
		$noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerContenedorCatalogos.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
        $Objeto = new ControllerContenedorCatalogos($db);
        echo $Objeto->DeletePersonasPuestos($id,$ID_USR);
        
}

/**
 *
 *  @ Cargamos Alta/Edicion Model
 */

if( $__cmd == "setEditarCatalogosPuestos" ){
		
	    $noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerContenedorCatalogos.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
        	$Query = "SELECT 
        					 Puesto
						FROM pld_politicamente_expuestos
					   WHERE pld_politicamente_expuestos.ID_PPE = '".$id."'";
					   
					    $RESPUESTA   = $db->Execute($Query);
					    $Puesto      = $RESPUESTA->fields["Puesto"];
					    
						$arrEdicion["Puesto"][]       = $Puesto;


						require_once( $class_path."json.php" ); 
						$json       = new Services_JSON; 
						$formulario = $json->encode($arrEdicion); 
						
						echo $formulario;
        
        
    		 
}


/**
 *
 * @author MarsVoltoso (CFA)
 * @category Model
 * @created Mon Sep 15, 2014
 * @version 1.0
 * @Codigos Postales 	
 */	

/**
 *
 *  @ Agregamos Codigo Postal  
 */

if( $__cmd == "setAgregaCatalogosCp" ){
	
		$noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerContenedorCatalogos.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
        $Objeto = new ControllerContenedorCatalogos($db);
        echo $Objeto->AddCpDatosPersonas($CodigoPostal,$ID_USR);
	
} // fin if

/**
 *
 *  @  Consulta Codigo Postal 
 */

if( $__cmd == "setConsultaCpCatalogos" ){
		
		
		
		$noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerContenedorCatalogos.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
        $Objeto = new ControllerContenedorCatalogos($db);
        echo $Objeto->ConsultaCpDatosPersonas($Cp,$Evento,$Pagina);
            //debug($Cp."<-->".$Evento."<-->".$Pagina);
	
} // if

/**
 *
 *  @  Consulta Codigo Postal 
 */

if( $__cmd == "setConsultaCpCatalogos" ){
		
		error_reporting(E_WARNING);
		ini_set('display_errors', '1');
		
		$noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerContenedorCatalogos.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
        $Objeto = new ControllerContenedorCatalogos($db);
        echo $Objeto->ConsultaCpDatosPersonas($Cp,$Evento,$Pagina);
            //debug($Cp."<-->".$Evento."<-->".$Pagina);
	
} // if

/**
 *
 *  @  Eliminar Codigo Postal 
 */

if( $__cmd == "setConsultaPaisesCatalogos" ){
	
		$noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerContenedorCatalogos.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
        $Objeto = new ControllerContenedorCatalogos($db);
        echo $Objeto->DeletePaiseDatosPersonas($Pais,$Evento,$Pagina);
        
} // fin if


/**
 *
 * @author MarsVoltoso (CFA)
 * @category Model
 * @created Mon Sep 15, 2014
 * @version 1.0
 * @Estados Riesgosos 	
 */	


/**
 *
 *  @ Consulta el ya dado de alta Estado Riesgoso  
 */

if( $__cmd == "setConsultaYaConsultadoCatalogos" ){

		$noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerContenedorCatalogos.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
        	$Query = "SELECT 
        					 pld_cat_estados_riesgo.ID_edo_pld,
							 pld_cat_estados_riesgo.ID_Usr,
							 pld_cat_estados_riesgo.Fecha_sistema,
							 pld_cat_estados_riesgo.Fecha,
							 pld_cat_estados_riesgo.ID_Estado,
							 pld_cat_estados_riesgo.cve_estado
						FROM
							 pld_cat_estados_riesgo";
					   
			$RESPUESTA   = $db->Execute($Query);
			$arrEdicion["EstadoRiesgo_100"][]       = $ID_Estado;
			
			 while( !$RESPUESTA->EOF ) { 
			 
			  	$ID_Estado = $RESPUESTA->fields["ID_Estado"];
			  	$arrEdicion["EstadoRiesgo_$ID_Estado"][]       = $ID_Estado;
			  
			  $RESPUESTA->MoveNext(); 
			 } // fin while( !$RESPUESTA->EOF ) {
			
			require_once( $class_path."json.php" ); 
			$json       = new Services_JSON; 
			$formulario = $json->encode($arrEdicion); 
			
			echo $formulario;


}


/**
 *
 *  @ Agregamos Estado 
 */

if( $__cmd == "setAgregaCatalogosEstadoRiesgo" ){
	
		$noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerContenedorCatalogos.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
        $Objeto = new ControllerContenedorCatalogos($db);
        echo $Objeto->AddEstadosRiesgososDatosPersonas($EstadosRiesgos,$ID_USR);
	
} // fin if


/**
 *
 *  @ Consultamos Estado 
 */

if( $__cmd == "setConsultaEstadoRiesgoCatalogos" ){
		
	    $noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerContenedorCatalogos.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
        $Objeto = new ControllerContenedorCatalogos($db);
        echo $Objeto->ConsultaEstadoRiesgo($EstadoRiesgo,$Evento,$Pagina);
    		 
}

/**
 *
 *  @ Eliminamos Estado 
 */

if( $__cmd == "setEliminaCatalogosEstadoRiesgoso" ){
		
	    $noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerContenedorCatalogos.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
        $Objeto = new ControllerContenedorCatalogos($db);
        echo $Objeto->EliminarEstadoRiesgo($id,$ID_USR);
    		 
}


/**
 *
 * @author MarsVoltoso (CFA)
 * @category Model
 * @created Mon Sep 15, 2014
 * @version 1.0
 * @Estados Ciudades 	
 */	

if( $__cmd == "setConsultaCiudadRiesgoCatalogos" ){
	
		$noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		
        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
        $Query = "SELECT
						 ciudades.ID_Estado,
						 ciudades.ID_Ciudad,
						 ciudades.Nombre
					FROM ciudades
					LEFT JOIN pld_cat_ciudades_riesgo ON pld_cat_ciudades_riesgo.ID_Ciudad = ciudades.ID_Ciudad
				   WHERE ciudades.ID_Estado = '".$EstadoRiesgo."'
				     AND  ciudades.ID_Ciudad NOT IN ( SELECT pld_cat_ciudades_riesgo.ID_Ciudad FROM pld_cat_ciudades_riesgo WHERE pld_cat_ciudades_riesgo.ID_Estado = '".$EstadoRiesgo."' )
				     AND ciudades.ID_Ciudad > 0";
        
      		$RESPUESTA   = $db->Execute($Query);
			
			 while( !$RESPUESTA->EOF ) { 
			 	
			 	$ID_Ciudad = $RESPUESTA->fields["ID_Ciudad"];
			 	$Nombre    = $RESPUESTA->fields["Nombre"];
			 	$html .= '<div id="Dos_CiudadRiesgo_'.$ID_Ciudad.'" class="item" data-value="'.$ID_Ciudad.'">'.$Nombre.'</div>'; 
			  
			  
			  $RESPUESTA->MoveNext(); 
			 } // fin while( !$RESPUESTA->EOF ) {
			
			echo $html;

} // fin if

/**
 *
 *  @ Insertamos Ciudad 
 */

if( $__cmd == "setAgregaCatalogosCiudadRiesgo" ){
	
		$noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerContenedorCatalogos.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
        $arrDatos = array( $CiudadRiesgos, $Dos_EstadosRiesgos );
        $Objeto = new ControllerContenedorCatalogos($db);
        echo $Objeto->AddCiudadRiesgososDatosPersonas($arrDatos,$ID_USR);
	
} // fin if

/**
 *
 *  @ Consultamos Ciudad 
 */

if( $__cmd == "setConsultaCiudadShowRiesgoCatalogos" ){
		
	    $noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerContenedorCatalogos.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
        $Objeto = new ControllerContenedorCatalogos($db);
        echo $Objeto->ConsultaCiudadRiesgoShow($CiudadRiesgo,$Evento,$Pagina);
    		 
}

/**
 *
 *  @ Eliminamos Ciudad 
 */

if( $__cmd == "setEliminaCatalogosCiudadRiesgoso" ){
		
	    $noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerContenedorCatalogos.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
         $Objeto = new ControllerContenedorCatalogos($db);
         echo $Objeto->EliminarCiudadRiesgo($id,$ID_USR);
    		 
}

/**
 *
 * @author MarsVoltoso (CFA)
 * @category Model
 * @created Mon Sep 15, 2014
 * @version 1.0
 * @Giro Negocio 	
 */	
 
 
 /**
 *
 *  @ Insertamos Giro 
 */

if( $__cmd == "setAgregaCatalogosGiroRiesgo" ){
	
		$noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerContenedorCatalogos.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
        $arrDatos = array( utf8_decode($GiroNegocio), $TipoRiesgos, $EstatusRiesgo,$id );
        $Objeto = new ControllerContenedorCatalogos($db);
        echo trim($Objeto->AddGiroRiesgososDatosPersonas($arrDatos,$ID_USR));

	
} // fin if

/**
 *
 *  @ Consultamos Ciudad 
 */

if( $__cmd == "setConsultaGiroRiesgoCatalogos" ){
		
	    $noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerContenedorCatalogos.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
        $Objeto = new ControllerContenedorCatalogos($db);
        echo $Objeto->ConsultaGiroRiesgoShow(utf8_decode($GiroRiesgo),$Evento,$Pagina);
    		 
}

/**
 *
 *  @ Eliminamos actividad
 */

if( $__cmd == "setEliminarCpCatalogos" ){
		
	    $noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerContenedorCatalogos.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
         $Objeto = new ControllerContenedorCatalogos($db);
         echo $Objeto->EliminarCP($id,$ID_USR);
    		 
}

/**
 *
 *  @ Eliminamos actividad
 */

if( $__cmd == "setEliminaCatalogosRiesgoRiesgoso" ){
		
	    $noheader=1;
        include($DOCUMENT_ROOT."/rutas.php");
		require_once("../Controller/ControllerContenedorCatalogos.php");

        $db = &ADONewConnection(SERVIDOR);
        $db->PConnect(IP,USER,PASSWORD,NUCLEO);		
        
         $Objeto = new ControllerContenedorCatalogos($db);
         echo $Objeto->EliminarGiroRiesgo($id,$ID_USR);
    		 
}

/**
 *
 *  @ Eliminamos Ciudad
 */

if( $__cmd == "setEditaCatalogosRiesgoRiesgoso" ){

    $noheader=1;
    include($DOCUMENT_ROOT."/rutas.php");
    require_once("../Controller/ControllerContenedorCatalogos.php");

    $db = &ADONewConnection(SERVIDOR);
    $db->PConnect(IP,USER,PASSWORD,NUCLEO);

    //$Objeto = new ControllerContenedorCatalogos($db);
    //echo $Objeto->EditarGiroRiesgo($id);

    $Query = "SELECT
        					pld_cat_giro_negocio.Giro,
        					pld_cat_giro_negocio.Tipo,
        					pld_cat_giro_negocio.Estatus
						FROM pld_cat_giro_negocio
					   WHERE pld_cat_giro_negocio.ID_Giro_negocio = '".$id."'";

    $RESPUESTA   = $db->Execute($Query);
    $Giro    = $RESPUESTA->fields["Giro"];
    $Tipo    = $RESPUESTA->fields["Tipo"];
    $Estatus = $RESPUESTA->fields["Estatus"];


    $arrEdicion["Giro"][]       = $Giro;
    $arrEdicion["Tipo"][]       = $Tipo;
    $arrEdicion["Estatus"][]    = $Estatus;


    require_once( $class_path."json.php" );
    $json       = new Services_JSON;
    $formulario = $json->encode($arrEdicion);

    echo $formulario;

}

?>