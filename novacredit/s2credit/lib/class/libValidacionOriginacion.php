<?php
	
/**
 *
 * @author MarsVoltoso (CFA)
 * @category Views
 * @created Mon Sep 15, 2014
 * @version 1.0
 */	
 
require_once( "classSoundex.php" ); 
 
class LibValidacionOriginacion{
	
	public function __construct($db,$idSolicitud,$ID_USR){
	
		$this->db = $db;
		$this->idSolicitud = $idSolicitud;
		$this->ID_USR = $ID_USR;
		$this->MySoundex = new classSoundex('es');
		    
	} // fin public
	
	public function validacionTerroristas(){
		 
		 //----------------->
		 // CONFIGURACION
		 
		 $Query = "SELECT * 
					 FROM pld_originacion";
							   	
			$RESPUESTA   = $this->db->Execute($Query); 
			$Terroristas = $RESPUESTA->fields["Terroristas"];
					
					
		//------------------->
		// SOLICITUD
		
		$Query = "SELECT 
						 CONCAT( solicitud.Nombre,' ',solicitud.NombreI,' ',solicitud.Ap_paterno,' ',solicitud.Ap_materno ) AS nombreCompleto,
						 solicitud.Nombre,
						 solicitud.NombreI,
						 solicitud.Ap_paterno,
						 solicitud.Ap_materno
				    FROM solicitud
				   WHERE solicitud.ID_Solicitud ='".$this->idSolicitud."' ";
				   
			$RESPUESTA            = $this->db->Execute($Query); 
			$nombreCompleto       = $RESPUESTA->fields["nombreCompleto"];
			$arrNombreCompleto[0] = $RESPUESTA->fields["Nombre"];
			$arrNombreCompleto[1] = $RESPUESTA->fields["NombreI"];
			$arrNombreCompleto[2] = $RESPUESTA->fields["Ap_paterno"];
			$arrNombreCompleto[3] = $RESPUESTA->fields["Ap_materno"];
            
            $nombreCompleto2      = $RESPUESTA->fields["Ap_paterno"]." ".$RESPUESTA->fields["Ap_materno"]." ".$RESPUESTA->fields["Nombre"]." ".$RESPUESTA->fields["NombreI"];
            
            $nombre_razon_social_soundex  = $this->MySoundex->get_soundex($RESPUESTA->fields["Nombre"]);
		    $nombre_adicional_soundex     = $this->MySoundex->get_soundex($RESPUESTA->fields["NombreI"]);
		    $apellido_paterno_soundex     = $this->MySoundex->get_soundex($RESPUESTA->fields["Ap_paterno"]);
		    $apellido_materno_soundex     = $this->MySoundex->get_soundex($RESPUESTA->fields["Ap_materno"]);
	   		
	   		$nombreCompleto3 = $nombre_razon_social_soundex." ".$nombre_adicional_soundex." ".$apellido_paterno_soundex." ".$apellido_materno_soundex;
            
            if( empty($nombreCompleto) ){
	            return 0;
            }
          
            $nombreCompleto = str_replace(" ","%",$nombreCompleto);
            $nombreCompleto = str_replace("  ","%",$nombreCompleto);
			$nombreCompleto = str_replace("   ","%",$nombreCompleto);
			
			$nombreCompleto2 = str_replace(" ","%",$nombreCompleto2);
            $nombreCompleto2 = str_replace("  ","%",$nombreCompleto2);
			$nombreCompleto2 = str_replace("   ","%",$nombreCompleto2);
					
		//-------------------->		
		// MODULO
					
			if( $Terroristas == "SI" ){
								   	
		   		$Query = "SELECT 
		   						 ID_Importadtl
		   					FROM pld_importacion_catalogos_dtl
		   				   WHERE CONCAT(pld_importacion_catalogos_dtl.Nombre_I,' ',pld_importacion_catalogos_dtl.Nombre_II) LIKE '%$nombreCompleto%'";
		   		
		   				    $RESPUESTA      = $this->db->Execute($Query); // debug($Query);
		   					$ID_Importadtl  = $RESPUESTA->fields["ID_Importadtl"];	
								   		
		   		if( !empty($ID_Importadtl) ){
			   		$this->actualizaResultadoConfiguracionYes("validacionTerroristas");
			   		$this->listasNegras( $this->idSolicitud, $arrNombreCompleto );
			   		return "ALERTA TERRORISTA";
			   	}else{
				   	$this->actualizaResultadoConfiguracionNo("validacionTerroristas");
			   	} // fin if
		   		
	   		} // fin terroristas
	   		
	   		if( $Terroristas == "SI" ){
				
				$arrNombreCompleto2[2] = $RESPUESTA->fields["Nombre"];
			    $arrNombreCompleto2[3] = $RESPUESTA->fields["NombreI"];
			    $arrNombreCompleto2[0] = $RESPUESTA->fields["Ap_paterno"];
			    $arrNombreCompleto2[1] = $RESPUESTA->fields["Ap_materno"];
								   	
		   		$Query = "SELECT 
		   						 ID_Importadtl
		   					FROM pld_importacion_catalogos_dtl
		   				   WHERE CONCAT(pld_importacion_catalogos_dtl.Nombre_I,' ',pld_importacion_catalogos_dtl.Nombre_II) LIKE '%$nombreCompleto2%'";
		   		
		   				    $RESPUESTA      = $this->db->Execute($Query); // debug($Query);
		   					$ID_Importadtl  = $RESPUESTA->fields["ID_Importadtl"];	
								   		
		   		if( !empty($ID_Importadtl) ){
			   		$this->actualizaResultadoConfiguracionYes("validacionTerroristas");
			   		$this->listasNegras( $this->idSolicitud, $arrNombreCompleto2 );
			   		return "ALERTA TERRORISTA";
			   	}else{
				   	$this->actualizaResultadoConfiguracionNo("validacionTerroristas");
			   	} // fin if
		   		
	   		} // fin terroristas			
	   		
	   		if( $Terroristas == "SI" ){
		   		
		   		$nombreCompleto3 = str_replace(" ","%",$nombreCompleto3);
	            $nombreCompleto3 = str_replace("  ","%",$nombreCompleto3);
				$nombreCompleto3 = str_replace("   ","%",$nombreCompleto3);
		   		
		   		$Query = "SELECT 
		   						 ID_Importadtl
		   					FROM pld_importacion_catalogos_dtl
		   				   WHERE CONCAT(pld_importacion_catalogos_dtl.Nombre_I_soundex,' ',pld_importacion_catalogos_dtl.Nombre_II_soundex) LIKE '%$nombreCompleto3%'";
		   		
		   				    $RESPUESTA      = $this->db->Execute($Query); // debug($Query);
		   					$ID_Importadtl  = $RESPUESTA->fields["ID_Importadtl"];	
								   		
		   		if( !empty($ID_Importadtl) ){
			   		$this->actualizaResultadoConfiguracionYes("validacionTerroristas");
			   		$this->listasNegras( $this->idSolicitud, $arrNombreCompleto3 );
			   		return "ALERTA TERRORISTA";
			   	}else{
				   	$this->actualizaResultadoConfiguracionNo("validacionTerroristas");
			   	} // fin if
			
			} //@end
	   		
	} // fin function
	
	public function listasNegras( $idSolicitud, $arrNombreCompleto )
	{
		
		$Query = "INSERT INTO pld_coincidencias_listas_negras( 
		                      id_solicitud,  
		                      id_figura, 
		                      id_registro_figura, 
		                      id_lista_negra, 
		                      nombre_razon_social, 
		                      nombre_adicional, 
		                      apellido_paterno, 
		                      apellido_materno,
		                      id_user, 
		                      fecha_registro)
                       VALUES('".$idSolicitud."', 
                              1,
                              1,
                              1,
                              '".$arrNombreCompleto[0]."',
                              '".$arrNombreCompleto[1]."',
                              '".$arrNombreCompleto[2]."',
                              '".$arrNombreCompleto[3]."',
                              '".$_SESSION["ID_USR"]."',
                              NOW()) ";
                              
            $this->db->Execute($Query); // debug($Query);
			$id = $this->db->Insert_ID();
		
		if( !empty($id) ){
			
			$Query = "INSERT INTO pld_alerta_24hrs_sofom( 
			                      id_coincidencias, 
			                      fecha_operacion, 
			                      status_reporte)
                          VALUES( '".$id."', 
                                  NOW(), 
                                  'Pendiente') ";
                                  
                $this->db->Execute($Query); // debug($Query);
			
		} //@end if
		
	} //@end
	
    public function validacionListaCondusef(){

        //----------------->
        // CONFIGURACION

        $Query = "SELECT *
					 FROM pld_originacion";

        $RESPUESTA   = $this->db->Execute($Query);
        $Terroristas = $RESPUESTA->fields["Terroristas"];


        //------------------->
        // SOLICITUD

        $Query = "SELECT
						 CONCAT( solicitud.Nombre,' ',solicitud.NombreI,' ',solicitud.Ap_paterno,' ',solicitud.Ap_materno ) AS nombreCompleto,
						 Ap_paterno,
						 Ap_materno,
						 Nombre,
						 NombreI
				    FROM solicitud
				   WHERE solicitud.ID_Solicitud ='".$this->idSolicitud."' ";

        $RESPUESTA      = $this->db->Execute($Query); //debug($Query);
        $nombreCompleto = $RESPUESTA->fields["nombreCompleto"];
        
        $nombreCompleto2      = $RESPUESTA->fields["Ap_paterno"]." ".$RESPUESTA->fields["Ap_materno"]." ".$RESPUESTA->fields["Nombre"]." ".$RESPUESTA->fields["NombreI"];
            
        $nombre_razon_social_soundex  = $this->MySoundex->get_soundex($RESPUESTA->fields["Nombre"]);
	    $nombre_adicional_soundex     = $this->MySoundex->get_soundex($RESPUESTA->fields["NombreI"]);
	    $apellido_paterno_soundex     = $this->MySoundex->get_soundex($RESPUESTA->fields["Ap_paterno"]);
	    $apellido_materno_soundex     = $this->MySoundex->get_soundex($RESPUESTA->fields["Ap_materno"]);
   		
   		$nombreCompleto3 = $nombre_razon_social_soundex." ".$nombre_adicional_soundex." ".$apellido_paterno_soundex." ".$apellido_materno_soundex;    
            
            if( empty($nombreCompleto) ){
	            return 0;
            }
          
            $nombreCompleto = str_replace(" ","%",$nombreCompleto);
            $nombreCompleto = str_replace("  ","%",$nombreCompleto);
			$nombreCompleto = str_replace("   ","%",$nombreCompleto);
			
			$nombreCompleto2 = str_replace(" ","%",$nombreCompleto2);
            $nombreCompleto2 = str_replace("  ","%",$nombreCompleto2);
			$nombreCompleto2 = str_replace("   ","%",$nombreCompleto2);

        //-------------------->
        // MODULO

        if( $Terroristas == "SI" ){

            $Query = "SELECT
		   						 ID_Nmb_lc
		   					FROM pld_cat_nombres_lc
		   				   WHERE CONCAT(pld_cat_nombres_lc.Nombre_I,' ',pld_cat_nombres_lc.Ap_paterno,' ',pld_cat_nombres_lc.Ap_materno) LIKE '%$nombreCompleto%'";

            $RESPUESTA      = $this->db->Execute($Query); // debug($Query);
            $ID_Importadtl  = $RESPUESTA->fields["ID_Nmb_lc"];

            if( !empty($ID_Importadtl) ){
                $this->actualizaResultadoConfiguracionYes("validacionListaCondusef");
                $this->listasNegras( $this->idSolicitud, $arrNombreCompleto );
                return "ALERTA LISTA PERSONAS BLOQUEADAS";
            }else{
                $this->actualizaResultadoConfiguracionNo("validacionListaCondusef");
            } // fin if

        } // fin terroristas
        
        if( $Terroristas == "SI" ){
			
			$arrNombreCompleto2[2] = $RESPUESTA->fields["Nombre"];
			$arrNombreCompleto2[3] = $RESPUESTA->fields["NombreI"];
			$arrNombreCompleto2[0] = $RESPUESTA->fields["Ap_paterno"];
			$arrNombreCompleto2[1] = $RESPUESTA->fields["Ap_materno"];
			
            $Query = "SELECT
		   						 ID_Nmb_lc
		   					FROM pld_cat_nombres_lc
		   				   WHERE CONCAT(pld_cat_nombres_lc.Nombre_I,' ',pld_cat_nombres_lc.Ap_paterno,' ',pld_cat_nombres_lc.Ap_materno) LIKE '%$nombreCompleto2%'";

            $RESPUESTA      = $this->db->Execute($Query); // debug($Query);
            $ID_Importadtl  = $RESPUESTA->fields["ID_Nmb_lc"];

            if( !empty($ID_Importadtl) ){
                $this->actualizaResultadoConfiguracionYes("validacionListaCondusef");
                $this->listasNegras( $this->idSolicitud, $arrNombreCompleto2 );
                return "ALERTA LISTA PERSONAS BLOQUEADAS";
            }else{
                $this->actualizaResultadoConfiguracionNo("validacionListaCondusef");
            } // fin if

        } // fin terroristas
        
        if( $Terroristas == "SI" ){
		   		
		   		$nombreCompleto3 = str_replace(" ","%",$nombreCompleto3);
	            $nombreCompleto3 = str_replace("  ","%",$nombreCompleto3);
				$nombreCompleto3 = str_replace("   ","%",$nombreCompleto3);
		   		
		   		$Query = "SELECT
				   						 ID_Nmb_lc
				   					FROM pld_cat_nombres_lc
				   				   WHERE CONCAT(pld_cat_nombres_lc.Nombre_I_soundex,' ',pld_cat_nombres_lc.Ap_paterno_soundex,' ',pld_cat_nombres_lc.Ap_materno_soundex) LIKE '%$nombreCompleto3%'";
		
		            $RESPUESTA      = $this->db->Execute($Query); // debug($Query);
		            $ID_Importadtl  = $RESPUESTA->fields["ID_Nmb_lc"];
		
		            if( !empty($ID_Importadtl) ){
		                $this->actualizaResultadoConfiguracionYes("validacionListaCondusef");
		                $this->listasNegras( $this->idSolicitud, $nombreCompleto3 );
		                return "ALERTA LISTA PERSONAS BLOQUEADAS";
		            }else{
		                $this->actualizaResultadoConfiguracionNo("validacionListaCondusef");
		            } // fin if

			
			} //@end

    } // fin function

    public function validacionListaPropia(){

        //----------------->
        // CONFIGURACION

        $Query = "SELECT *
					 FROM pld_originacion";

        $RESPUESTA   = $this->db->Execute($Query);
        $Terroristas = $RESPUESTA->fields["Terroristas"];


        //------------------->
        // SOLICITUD

        $Query = "SELECT
						 CONCAT( solicitud.Nombre,' ',solicitud.NombreI,' ',solicitud.Ap_paterno,' ',solicitud.Ap_materno ) AS nombreCompleto,
						 Nombre,
						 NombreI,
						 Ap_paterno,
						 Ap_materno
				    FROM solicitud
				   WHERE solicitud.ID_Solicitud ='".$this->idSolicitud."' ";

        $RESPUESTA      = $this->db->Execute($Query);
        $nombreCompleto = $RESPUESTA->fields["nombreCompleto"];
        
        $nombre_razon_social_soundex  = $this->MySoundex->get_soundex($RESPUESTA->fields["Nombre"]);
	    $nombre_adicional_soundex     = $this->MySoundex->get_soundex($RESPUESTA->fields["NombreI"]);
	    $apellido_paterno_soundex     = $this->MySoundex->get_soundex($RESPUESTA->fields["Ap_paterno"]);
	    $apellido_materno_soundex     = $this->MySoundex->get_soundex($RESPUESTA->fields["Ap_materno"]);
   		
   		$nombreCompleto3 = $nombre_razon_social_soundex." ".$nombre_adicional_soundex." ".$apellido_paterno_soundex." ".$apellido_materno_soundex;    

        
            if( empty($nombreCompleto) ){
	            return 0;
            }
        
        
        $nombreCompleto = str_replace(" ","%",$nombreCompleto);
        $nombreCompleto = str_replace("  ","%",$nombreCompleto);
        $nombreCompleto = str_replace("   ","%",$nombreCompleto);

        //-------------------->
        // MODULO

        if( $Terroristas == "SI" ){

            $Query = "SELECT
		   						 ID_Nmb_lp
		   					FROM pld_cat_nombres_lp
		   				   WHERE CONCAT(pld_cat_nombres_lp.Nombre_I,' ',pld_cat_nombres_lp.Ap_paterno,' ',pld_cat_nombres_lp.Ap_materno) LIKE '%$nombreCompleto%'";

            $RESPUESTA      = $this->db->Execute($Query); // debug($Query);
            $ID_Importadtl  = $RESPUESTA->fields["ID_Nmb_lp"];

            if( !empty($ID_Importadtl) ){
                $this->actualizaResultadoConfiguracionYes("validacionListaPropia");
                return "ALERTA LISTA PROPIA";
            }else{
                $this->actualizaResultadoConfiguracionNo("validacionListaPropia");
            } // fin if

        } // fin terroristas
        
        if( $Terroristas == "SI" ){
			
            $Query = "SELECT
		   						 ID_Nmb_lp
		   					FROM pld_cat_nombres_lp
		   				   WHERE CONCAT(pld_cat_nombres_lp.Nombre_I,' ',pld_cat_nombres_lp.Ap_paterno,' ',pld_cat_nombres_lp.Ap_materno) LIKE '%$nombreCompleto%'";

            $RESPUESTA      = $this->db->Execute($Query); // debug($Query);
            $ID_Importadtl  = $RESPUESTA->fields["ID_Nmb_lp"];

            if( !empty($ID_Importadtl) ){
                $this->actualizaResultadoConfiguracionYes("validacionListaPropia");
                return "ALERTA LISTA PROPIA";
            }else{
                $this->actualizaResultadoConfiguracionNo("validacionListaPropia");
            } // fin if

        } // fin terroristas
        
        if( $Terroristas == "SI" ){
			
			$nombreCompleto3 = str_replace(" ","%",$nombreCompleto3);
            $nombreCompleto3 = str_replace("  ","%",$nombreCompleto3);
			$nombreCompleto3 = str_replace("   ","%",$nombreCompleto3);

			$Query = "SELECT
		   						 ID_Nmb_lp
		   					FROM pld_cat_nombres_lp
		   				   WHERE CONCAT(pld_cat_nombres_lp.Nombre_I_soundex,' ',pld_cat_nombres_lp.Ap_paterno_soundex,' ',pld_cat_nombres_lp.Ap_materno_soundex) LIKE '%$nombreCompleto3%'";

            $RESPUESTA      = $this->db->Execute($Query); // debug($Query);
            $ID_Importadtl  = $RESPUESTA->fields["ID_Nmb_lp"];

            if( !empty($ID_Importadtl) ){
                $this->actualizaResultadoConfiguracionYes("validacionListaPropia");
                return "ALERTA LISTA PROPIA";
            }else{
                $this->actualizaResultadoConfiguracionNo("validacionListaPropia");
            } // fin if

        } // fin terroristas

    } // fin function
    
    public function validacionListaSat(){

        //----------------->
        // CONFIGURACION

        $Query = "SELECT *
					 FROM pld_originacion";

        $RESPUESTA = $this->db->Execute($Query);
        $SAT       = $RESPUESTA->fields["SAT"];


        //------------------->
        // SOLICITUD

        $Query = "SELECT
						 CONCAT( solicitud.Nombre,' ',solicitud.NombreI,' ',solicitud.Ap_paterno,' ',solicitud.Ap_materno ) AS nombreCompleto,
						 Ap_paterno,
						 Ap_materno,
						 Nombre,
						 NombreI
				    FROM solicitud
				   WHERE solicitud.ID_Solicitud ='".$this->idSolicitud."' ";

        $RESPUESTA      = $this->db->Execute($Query);
        $nombreCompleto = $RESPUESTA->fields["nombreCompleto"];
        
        $nombreCompleto2 = $RESPUESTA->fields["Ap_paterno"]." ".$RESPUESTA->fields["Ap_materno"]." ".$RESPUESTA->fields["Nombre"]." ".$RESPUESTA->fields["NombreI"];
        
        $nombre_razon_social_soundex  = $this->MySoundex->get_soundex($RESPUESTA->fields["Nombre"]);
	    $nombre_adicional_soundex     = $this->MySoundex->get_soundex($RESPUESTA->fields["NombreI"]);
	    $apellido_paterno_soundex     = $this->MySoundex->get_soundex($RESPUESTA->fields["Ap_paterno"]);
	    $apellido_materno_soundex     = $this->MySoundex->get_soundex($RESPUESTA->fields["Ap_materno"]);
   		
   		$nombreCompleto3 = $nombre_razon_social_soundex." ".$nombre_adicional_soundex." ".$apellido_paterno_soundex." ".$apellido_materno_soundex;    

        
            if( empty($nombreCompleto) ){
	            return 0;
            }
        
        
        $nombreCompleto = str_replace(" ","%",$nombreCompleto);
        $nombreCompleto = str_replace("  ","%",$nombreCompleto);
        $nombreCompleto = str_replace("   ","%",$nombreCompleto);
        
        $nombreCompleto2 = str_replace(" ","%",$nombreCompleto2);
        $nombreCompleto2 = str_replace("  ","%",$nombreCompleto2);
        $nombreCompleto2 = str_replace("   ","%",$nombreCompleto2);
		
		$nombreCompleto3 = str_replace(" ","%",$nombreCompleto3);
        $nombreCompleto3 = str_replace("  ","%",$nombreCompleto3);
		$nombreCompleto3 = str_replace("   ","%",$nombreCompleto3);

        //-------------------->
        // MODULO

        if( $SAT == "SI" ){

            $Query = "SELECT
		   						 ID_Nmb_sat
		   					FROM pld_cat_nombres_sat
		   				   WHERE CONCAT(pld_cat_nombres_sat.Nombre_I,' ',pld_cat_nombres_sat.Ap_paterno,' ',pld_cat_nombres_sat.Ap_materno) LIKE '%$nombreCompleto%'";

            $RESPUESTA      = $this->db->Execute($Query); //debug($Query);
            $ID_Importadtl  = $RESPUESTA->fields["ID_Nmb_sat"];

            if( !empty($ID_Importadtl) ){
                $this->actualizaResultadoConfiguracionYes("validacionListaSat");
                $this->listasNegras( $this->idSolicitud, $arrNombreCompleto );
                return "ALERTA LISTA SAT";
            }else{
                $this->actualizaResultadoConfiguracionNo("validacionListaSat");
            } // fin if

        } // fin terroristas
        
        if( $SAT == "SI" ){
			
			$arrNombreCompleto2[2] = $RESPUESTA->fields["Nombre"];
			$arrNombreCompleto2[3] = $RESPUESTA->fields["NombreI"];
			$arrNombreCompleto2[0] = $RESPUESTA->fields["Ap_paterno"];
			$arrNombreCompleto2[1] = $RESPUESTA->fields["Ap_materno"];
			
            $Query = "SELECT
		   						 ID_Nmb_sat
		   					FROM pld_cat_nombres_sat
		   				   WHERE CONCAT(pld_cat_nombres_sat.Nombre_I,' ',pld_cat_nombres_sat.Ap_paterno,' ',pld_cat_nombres_sat.Ap_materno) LIKE '%$nombreCompleto2%'";

            $RESPUESTA      = $this->db->Execute($Query); // debug($Query);
            $ID_Importadtl  = $RESPUESTA->fields["ID_Nmb_sat"];

            if( !empty($ID_Importadtl) ){
                $this->actualizaResultadoConfiguracionYes("validacionListaSat");
                $this->listasNegras( $this->idSolicitud, $arrNombreCompleto2 );
                return "ALERTA LISTA SAT";
            }else{
                $this->actualizaResultadoConfiguracionNo("validacionListaSat");
            } // fin if

        } // fin terroristas
        
        if( $SAT == "SI" ){
			
			$Query = "SELECT
		   						 ID_Nmb_sat
		   					FROM pld_cat_nombres_sat
		   				   WHERE CONCAT(pld_cat_nombres_sat.Nombre_I_soundex,' ',pld_cat_nombres_sat.Ap_paterno_soundex,' ',pld_cat_nombres_sat.Ap_materno_soundex) LIKE '%$nombreCompleto3%'";

            $RESPUESTA      = $this->db->Execute($Query);  //debug($Query);
            $ID_Importadtl  = $RESPUESTA->fields["ID_Nmb_sat"];

            if( !empty($ID_Importadtl) ){
                $this->actualizaResultadoConfiguracionYes("validacionListaSat");
                $this->listasNegras( $this->idSolicitud, $arrNombreCompleto2 );
                return "ALERTA LISTA SAT";
            }else{
                $this->actualizaResultadoConfiguracionNo("validacionListaSat");
            } // fin if

        } // fin terroristas
		
		
    } // fin function
	
	public function validacionPuestosPoliticamente(){
		
		 //----------------->
		 // CONFIGURACION
		 
		 $Query = "SELECT * 
					 FROM pld_originacion";
							   	
			$RESPUESTA   = $this->db->Execute($Query); 
			$Puestos_PPE = $RESPUESTA->fields["Puestos_PPE"];
			
		//------------------->
		// SOLICITUD
		
		$Query = "SELECT 
						 solicitud.TienePuestoPoliticamenteEspuesto
				    FROM solicitud
				   WHERE solicitud.ID_Solicitud ='".$this->idSolicitud."' ";// debug($Query);
				   
			$RESPUESTA      = $this->db->Execute($Query); 
			$TienePuestoPoliticamenteEspuesto = $RESPUESTA->fields["TienePuestoPoliticamenteEspuesto"];	  
		
		//-------------------->		
		// MODULO
			
			if( $Puestos_PPE == "SI" ){
				if( $TienePuestoPoliticamenteEspuesto == "SI" ){
					$this->actualizaResultadoConfiguracionYes("validacionPuestosPoliticamente");
					return "ALERTA PUESTO POLITICAMENTE EXPUESTO";
				}else{
					$this->actualizaResultadoConfiguracionNo("validacionPuestosPoliticamente");
				} // FIN IF	
			} // FIN IF
		
	} // fin function
	
	public function validacionPersonasPoliticamente(){
		
		//----------------->
		 // CONFIGURACION
		 
		 $Query = "SELECT * 
					 FROM pld_originacion";
							   	
			$RESPUESTA    = $this->db->Execute($Query); 
			$Nombres_PPE  = $RESPUESTA->fields["Nombres_PPE"];
			
		//------------------->
		// SOLICITUD
		
		$Query = "SELECT 
						 CONCAT( solicitud.Nombre,' ',solicitud.NombreI,' ',solicitud.Ap_paterno,' ',solicitud.Ap_materno ) AS nombreCompleto,
						 Ap_paterno,
						 Ap_materno,
						 Nombre,
						 NombreI
				    FROM solicitud
				   WHERE solicitud.ID_Solicitud ='".$this->idSolicitud."' ";
				   
			$RESPUESTA      = $this->db->Execute($Query); 
			$nombreCompleto = $RESPUESTA->fields["nombreCompleto"];
            $nombreCompleto2 = $RESPUESTA->fields["Ap_paterno"]." ".$RESPUESTA->fields["Ap_materno"]." ".$RESPUESTA->fields["Nombre"]." ".$RESPUESTA->fields["NombreI"];
			
			$nombre_razon_social_soundex  = $this->MySoundex->get_soundex($RESPUESTA->fields["Nombre"]);
		    $nombre_adicional_soundex     = $this->MySoundex->get_soundex($RESPUESTA->fields["NombreI"]);
		    $apellido_paterno_soundex     = $this->MySoundex->get_soundex($RESPUESTA->fields["Ap_paterno"]);
		    $apellido_materno_soundex     = $this->MySoundex->get_soundex($RESPUESTA->fields["Ap_materno"]);
	   		
	   		$nombreCompleto3 = $nombre_razon_social_soundex." ".$nombre_adicional_soundex." ".$apellido_paterno_soundex." ".$apellido_materno_soundex;    
			
			
            if( empty($nombreCompleto) ){
	            return 0;
            }
        
			$nombreCompleto = str_replace(" ","%",$nombreCompleto);
	        $nombreCompleto = str_replace("  ","%",$nombreCompleto);
	        $nombreCompleto = str_replace("   ","%",$nombreCompleto);
	        
	        $nombreCompleto2 = str_replace(" ","%",$nombreCompleto2);
	        $nombreCompleto2 = str_replace("  ","%",$nombreCompleto2);
	        $nombreCompleto2 = str_replace("   ","%",$nombreCompleto2);	
	        
	        $nombreCompleto3 = str_replace(" ","%",$nombreCompleto3);
	        $nombreCompleto3 = str_replace("  ","%",$nombreCompleto3);
	        $nombreCompleto3 = str_replace("   ","%",$nombreCompleto3);		
            
		//-------------------->	
		// MODUL
			
			if( $Nombres_PPE == "SI" ){
				
				$Query = "SELECT 
		   	   				    ID_Nmb_ppe
		   	   			   FROM pld_cat_nombres_ppe
		   	   			  WHERE CONCAT(pld_cat_nombres_ppe.Nombre_I,' ',pld_cat_nombres_ppe.Ap_paterno,' ',pld_cat_nombres_ppe.Ap_materno) LIKE '%$nombreCompleto%'";
								   	   
		   	     $RESPUESTA  =  $this->db->Execute($Query);  //echo ($Query);
		   		 $ID_Nmb_ppe = $RESPUESTA->fields["ID_Nmb_ppe"];	
								   		
		   		if( !empty($ID_Nmb_ppe) ){
			   		$this->actualizaResultadoConfiguracionYes("validacionPersonasPoliticamente");
			   		return "ALERTA PERSONA POLITICAMENTE EXPUESTA";
			   	}else{
				   	$this->actualizaResultadoConfiguracionNo("validacionPersonasPoliticamente");
			   	} // fin if
				
		    } // FI IF
		    
		    if( $Nombres_PPE == "SI" ){
			
				$arrNombreCompleto2[2] = $RESPUESTA->fields["Nombre"];
				$arrNombreCompleto2[3] = $RESPUESTA->fields["NombreI"];
				$arrNombreCompleto2[0] = $RESPUESTA->fields["Ap_paterno"];
				$arrNombreCompleto2[1] = $RESPUESTA->fields["Ap_materno"];
				
	            $Query = "SELECT
			   						 ID_Nmb_ppe
			   					FROM pld_cat_nombres_ppe
			   				   WHERE CONCAT(pld_cat_nombres_ppe.Nombre_I,' ',pld_cat_nombres_ppe.Ap_paterno,' ',pld_cat_nombres_ppe.Ap_materno) LIKE '%$nombreCompleto2%'";
	
	            $RESPUESTA      = $this->db->Execute($Query);  //debug($Query);
	            $ID_Importadtl  = $RESPUESTA->fields["ID_Nmb_ppe"];
	
	            if( !empty($ID_Importadtl) ){
	                $this->actualizaResultadoConfiguracionYes("validacionPersonasPoliticamente");
	                return "ALERTA PERSONA POLITICAMENTE EXPUESTA";
	            }else{
	                $this->actualizaResultadoConfiguracionNo("validacionPersonasPoliticamente");
	            } // fin if
	
	        } // fin terroristas
	        
	        if( $Nombres_PPE == "SI" ){
				
				$Query = "SELECT
			   						 ID_Nmb_ppe
			   					FROM pld_cat_nombres_ppe
			   				   WHERE CONCAT(pld_cat_nombres_ppe.Nombre_I_soundex,' ',pld_cat_nombres_ppe.Ap_paterno_soundex,' ',pld_cat_nombres_ppe.Ap_materno_soundex) LIKE '%$nombreCompleto3%'";
	
	            $RESPUESTA      = $this->db->Execute($Query);  //debug($Query);
	            $ID_Importadtl  = $RESPUESTA->fields["ID_Nmb_ppe"];
	
	            if( !empty($ID_Importadtl) ){
	                $this->actualizaResultadoConfiguracionYes("validacionPersonasPoliticamente");
	                return "ALERTA PERSONA POLITICAMENTE EXPUESTA";
	            }else{
	                $this->actualizaResultadoConfiguracionNo("validacionPersonasPoliticamente");
	            } // fin if
	
	        } // fin terroristas
		
	} // fin function
	
	public function validacioncodigosPostales(){
		
		//----------------->
		 // CONFIGURACION
		 
		 $Query = "SELECT * 
					 FROM pld_originacion";
							   	
			$RESPUESTA          = $this->db->Execute($Query); 
			 $CodigosPostales  = $RESPUESTA->fields["CodigosPostales"];
			
		//------------------->
		// SOLICITUD
		
		$Query = "SELECT 
						 solicitud.CP 
				    FROM solicitud
				   WHERE solicitud.ID_Solicitud ='".$this->idSolicitud."' ";
				   
			$RESPUESTA = $this->db->Execute($Query); 
			$CP        = $RESPUESTA->fields["CP"];	  
		
		//-------------------->	
		// MODUL

			if( $CodigosPostales == "SI" ){
				
				$Query = "SELECT 
		   	   					pld_cat_codigos_postales_riesgo.ID_ciudad_pld
		   	   			   FROM pld_cat_codigos_postales_riesgo
		   	   			  WHERE pld_cat_codigos_postales_riesgo.CP = '".$CP."' ";
								   	   			  
					$RESPUESTA     =  $this->db->Execute($Query); // debug($Query);
					$ID_ciudad_pld = $RESPUESTA->fields["ID_ciudad_pld"];	
								   		
			   		if( !empty($ID_ciudad_pld) ){
				   		$this->actualizaResultadoConfiguracionYes("validacioncodigosPostales");
				   		return "ALERTA CODIGO POSTAL RIESGOSO";
				   	}else{
					   	$this->actualizaResultadoConfiguracionNo("validacioncodigosPostales");
				   	} // fin if
				
			} // FIN If
		
	} // fin function
	
	public function validaEstadosRiesgosos(){
		
		 //----------------->
		 // CONFIGURACION
		 
		 $Query = "SELECT * 
					 FROM pld_originacion";
							   	
			 $RESPUESTA   = $this->db->Execute($Query); 
			 $EstadosConf = $RESPUESTA->fields["Estados"];
			
		//------------------->
		// SOLICITUD
		
		$Query = "SELECT 
						 solicitud.Estado 
				    FROM solicitud
				   WHERE solicitud.ID_Solicitud ='".$this->idSolicitud."' ";
				   
			$RESPUESTA       = $this->db->Execute($Query); 
			$EstadoSolicitud = $RESPUESTA->fields["Estado"];	  
		
		//-------------------->	
		// MODUL
			
			if( $EstadosConf == "SI" ){
				
				$Query = "SELECT 
		   	   				    estados.id_estado 
		   	   			   FROM estados
		   	   			  WHERE estados.nombre LIKE ('%".$EstadoSolicitud."%')";

				  		 $RESPUESTA  =  $this->db->Execute($Query); // debug($Query);
				  		 $id_estado  = $RESPUESTA->fields["id_estado"];
				  		 
				   $Query = "SELECT 
		        					pld_cat_estados_riesgo.ID_edo_pld
							   FROM pld_cat_estados_riesgo
						   	  WHERE pld_cat_estados_riesgo.ID_Estado IN ('$id_estado')";
						   	  
						   	 $RESPUESTA   =  $this->db->Execute($Query); // debug($Query);
					  		 $ID_edo_pld  = $RESPUESTA->fields["ID_edo_pld"];
						   	  
						 if( !empty($ID_edo_pld) ){
							 $this->actualizaResultadoConfiguracionYes("validaEstadosRiesgosos");
							 return "ALERTA ESTADO RIESGOSO";
						 }else{
							 $this->actualizaResultadoConfiguracionNo("validaEstadosRiesgosos");
						 }  // FIN IF 	 
				
		  } // FIN IF

	} // fin function
	
	public function validaCiudadRiesgosos(){
		
		 //----------------->
		 // CONFIGURACION
		 
		 $Query = "SELECT * 
					 FROM pld_originacion";
							   	
			 $RESPUESTA    = $this->db->Execute($Query); 
			 $CiudadesConf = $RESPUESTA->fields["Ciudades"];
			
		//------------------->
		// SOLICITUD
		
		$Query = "SELECT 
						 solicitud.Ciudad 
				    FROM solicitud
				   WHERE solicitud.ID_Solicitud ='".$this->idSolicitud."' ";
				   
			$RESPUESTA       = $this->db->Execute($Query); 
			$CiudadSolicitud = $RESPUESTA->fields["Ciudad"];	  
		
		//-------------------->	
		// MODUL

			if( $CiudadesConf == "SI" ){
				
				   $Query = "SELECT 
				  				   ciudades.ID_Estado,
				  				   ciudades.ID_Ciudad
				  			  FROM ciudades
				  			 WHERE ciudades.nombre LIKE ('%".$CiudadSolicitud."%')";
				  			 
				  			 	 $RESPUESTA   =  $this->db->Execute($Query); // debug($Query);
						  		 $ID_Estado   = $RESPUESTA->fields["ID_Estado"];
						  		 $ID_Ciudad   = $RESPUESTA->fields["ID_Ciudad"];
					
					   $Query = "SELECT 
				   	   					pld_cat_ciudades_riesgo.ID_codigo_pld
				   	   			   FROM pld_cat_ciudades_riesgo
				   	   			  WHERE pld_cat_ciudades_riesgo.ID_Ciudad = '".$ID_Ciudad."'
				   	   			  AND pld_cat_ciudades_riesgo.ID_Estado = '".$ID_Estado."'  ";
								   	   			  
			   	   		     $RESPUESTA      =  $this->db->Execute($Query); // debug($Query);
					  		 $ID_codigo_pld  = $RESPUESTA->fields["ID_codigo_pld"];
											   	  
						 if( !empty($ID_codigo_pld) ){
						     $this->actualizaResultadoConfiguracionYes("validaCiudadRiesgosos");
							 return "ALERTA CIUDAD RIESGOSO";
						 }else{
							 $this->actualizaResultadoConfiguracionNo("validaCiudadRiesgosos");
						 }  // FIN IF 		 
				
				
			} // FI IF
		
	} // fin public
	
	public function validaGiroRiesgosos(){
		
		//----------------->
		 // CONFIGURACION
		 
		 $Query = "SELECT * 
					 FROM pld_originacion";
							   	
			 $RESPUESTA    = $this->db->Execute($Query); 
			 $Giros        = $RESPUESTA->fields["Giros"];	
			
		//------------------->
		// SOLICITUD
		
		$Query = "SELECT 
						 solicitud.ID_GiroNegocio 
				    FROM solicitud
				   WHERE solicitud.ID_Solicitud ='".$this->idSolicitud."' ";
				   
			$RESPUESTA      = $this->db->Execute($Query); 
			$ID_GiroNegocio = $RESPUESTA->fields["ID_GiroNegocio"];	  
		
		//-------------------->	
		// MODUL

		if( $Giros == "SI" ){
			
					$Query = "SELECT 
				   					pld_cat_giro_negocio.ID_Giro_negocio
				   			   FROM pld_cat_giro_negocio
				   			  WHERE pld_cat_giro_negocio.ID_Giro_negocio = '".$ID_GiroNegocio."'
				   			    AND pld_cat_giro_negocio.Tipo = 'ALTO RIESGO'";
				   			    
				   	$RESPUESTA       = $this->db->Execute($Query); //debug($Query); 
				   	$ID_Giro_negocio = $RESPUESTA->fields["ID_Giro_negocio"];
				   	
			if( !empty($ID_Giro_negocio) ){
				$this->actualizaResultadoConfiguracionYes("validaGiroRiesgosos");
				return  "ALERTA ACTIVIDA DE ALTO RIESGO!";
			}else{
				$this->actualizaResultadoConfiguracionNo("validaGiroRiesgosos");
			} // fin if			    
			
		} // FIN IF
		
	} // fin function
	
	public function validaPaisRiesgosos()
	{
		
		//----------------->
		 // CONFIGURACION
		 
		 $Query = "SELECT * 
					 FROM pld_originacion";
							   	
			 $RESPUESTA       = $this->db->Execute($Query); 
			 $PaisesRiesgosos = $RESPUESTA->fields["PaisesRiesgosos"];	
			
		//------------------->
		// SOLICITUD
		
		$Query = "SELECT 
						 solicitud.ID_pais 
				    FROM solicitud
				   WHERE solicitud.ID_Solicitud ='".$this->idSolicitud."' ";
				   
			$RESPUESTA = $this->db->Execute($Query); 
			$ID_pais   = $RESPUESTA->fields["ID_pais"];	  
		
		//-------------------->	
		// MODUL

		if( $PaisesRiesgosos == "SI" ){
			         
			        $Query = "SELECT
                                    ID_Pais
                               FROM pld_cat_paraisos_fiscales
                              WHERE ID_Pais = '".$ID_pais."' ";
			        
					$RESPUESTA  = $this->db->Execute($Query); //debug($Query); 
				   	$ID_Pais_AN = $RESPUESTA->fields["ID_Pais"];
				   	
			if( !empty($ID_Pais_AN) ){
				$this->actualizaResultadoConfiguracionYes("validaPaisRiesgosos");
				return  "ALERTA PAIS DE ALTO RIESGO";
			}else{
				$this->actualizaResultadoConfiguracionNo("validaPaisRiesgosos");
			} // fin if			    
			
		} // FIN IF
		
	} //@end
	
	public function resultadosConfiguracion(){
		
		$arrAlertas = array();
		
		$arrAlertas[] = $this->validacionTerroristas();
        $arrAlertas[] = $this->validacionListaCondusef();
        $arrAlertas[] = $this->validacionListaPropia();
        $arrAlertas[] = $this->validacionPuestosPoliticamente();
		$arrAlertas[] = $this->validacionPersonasPoliticamente();
		$arrAlertas[] = $this->validacioncodigosPostales();
		$arrAlertas[] = $this->validaEstadosRiesgosos();
		$arrAlertas[] = $this->validaCiudadRiesgosos();
		$arrAlertas[] = $this->validaGiroRiesgosos();
		$arrAlertas[] = $this->validaPaisRiesgosos();
		$arrAlertas[] = $this->validacionListaSat();

		
	   return $arrAlertas; 	
		
	} // fin public
	
	public function actualizaResultadoConfiguracionYes($Regla){
		
		$Query = "SELECT 
						 pld_oficial_cumplimiento_log.id_OficialCumplimiento
					FROM pld_oficial_cumplimiento_log
				   WHERE pld_oficial_cumplimiento_log.ID_Solicitud = '".$this->idSolicitud."' ";
				   
				    $RESPUESTA              = $this->db->Execute($Query); // debug($Query);
				   	$id_OficialCumplimiento = $RESPUESTA->fields["id_OficialCumplimiento"];
		
		if( empty($id_OficialCumplimiento) ){
		
			 $Query = "UPDATE solicitud SET SolicitudRevisionPld = 'SI' WHERE solicitud.ID_Solicitud = '".$this->idSolicitud."' ";
			 $this->db->Execute($Query); // debug($Query);
			
			 $Query = "INSERT INTO pld_cambios_reglas (ID_pld_cambios_reglas, id_usr, id_solicitud, RespuestaOficial, ReglaEnCurso) VALUES (NULL, '".$this->ID_USR."', '".$this->idSolicitud."', 'SI', '".$Regla."')";
			 $this->db->Execute($Query); // debug($Query);
		}	 
		
	} // fin function
	
	public function actualizaResultadoConfiguracionNo($Regla){
		
		 	$Query = "SELECT 
						 pld_oficial_cumplimiento_log.id_OficialCumplimiento
					FROM pld_oficial_cumplimiento_log
				   WHERE pld_oficial_cumplimiento_log.ID_Solicitud = '".$this->idSolicitud."' ";
				   
				    $RESPUESTA              = $this->db->Execute($Query); //debug($Query); 
				   	$id_OficialCumplimiento = $RESPUESTA->fields["id_OficialCumplimiento"];
		
		if( empty($id_OficialCumplimiento) ){
		 
		 $Query = "UPDATE solicitud SET SolicitudRevisionPld = 'NO' WHERE solicitud.ID_Solicitud = '".$this->idSolicitud."' ";
		 //$this->db->Execute($Query); // debug($Query);
		 
		 $Query = "INSERT INTO pld_cambios_reglas (ID_pld_cambios_reglas, id_usr, id_solicitud, RespuestaOficial, ReglaEnCurso) VALUES (NULL, '".$this->ID_USR."', '".$this->idSolicitud."', 'NO', '".$Regla."')";
		 $this->db->Execute($Query); // debug($Query);
		 
		 }

	}
	
} // fin class

?>