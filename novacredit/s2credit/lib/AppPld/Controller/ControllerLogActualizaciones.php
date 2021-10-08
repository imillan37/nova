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

 
class ControllerLogActualizaciones {
 
 	public $db;
 	 
 		public function __construct($db){
	 		$this->db = $db; 
	 	}
 		

	 	public function consulta_dolares(){
	 			
	 			$Query = "SELECT
                                a.ID_Importacion,
                                a.Registro,
                                a.ID_Usr
                           FROM pld_importacion_tipocambio a
                          WHERE a.Divisa = 'USD'
                       ORDER BY a.ID_Importacion DESC
                          LIMIT 1";
	 			
	 			$rs = $this->db->Execute($Query);

	 			$fecha = $rs->fields["Registro"];
	 			$User  = $rs->fields["ID_Usr"];

	 			$sql ="SELECT CONCAT(Nombre,' ',AP_Paterno,' ',AP_Materno) AS Nombre FROM usuarios WHERE ID_User =  '".$User."' ";
	
				$rs2=$this->db->Execute($sql);
	
	    		$nombre = mb_strtoupper($rs2->fields["Nombre"]);

	    		//$fecha="2014-09-16 00:00:00";
	    		//$fecha="2014-09-21 00:00:00";
	    		$segundos=strtotime('now') - strtotime($fecha);
				$diferencia_dias=intval($segundos/60/60/24);

				if($diferencia_dias>2 && $diferencia_dias<8)
					$clasecolor = "warning";
				else if($diferencia_dias<3)
					$clasecolor = "positive";
				else
					$clasecolor = "error";
				$Datos["fila"][] = utf8_encode("<td width='25%'>".$fecha."</td><td width='20%' style='text-align:left;'>".$nombre."</td>");
				$Datos["clase"][] = $clasecolor;
	 			
	 			//return utf8_encode($Datos);
	 			return $Datos;

	 			die();

	 	}


	 	public function consulta_unidades(){
	 			
	 			$Query = "SELECT
                                a.ID_Importacion,
                                a.Registro,
                                a.ID_Usr
                           FROM pld_importacion_indicadores a
                          WHERE a.Indicador = 'UDIS'
                       ORDER BY a.ID_Importacion DESC
                          LIMIT 1";
	 			
	 			$rs = $this->db->Execute($Query);

	 			$fechau = $rs->fields["Registro"];
	 			$Useru  = $rs->fields["ID_Usr"];

	 			$sql ="	SELECT CONCAT(Nombre,' ',AP_Paterno,' ',AP_Materno) AS Nombre
						FROM ".NUCLEO.".usuarios
						WHERE ID_User = '".$Useru."' ";
	
				$rs2=$this->db->Execute($sql);
	
	    		$nombreu = mb_strtoupper($rs2->fields["Nombre"]);

	    		//$fechau="2014-09-16 00:00:00";
	    		//$fechau="2014-09-21 00:00:00";
	 			$segundos=strtotime('now') - strtotime($fechau);
				$diferencia_dias=intval($segundos/60/60/24);

				if($diferencia_dias>2 && $diferencia_dias<8)
					$clasecolor = "warning";
				else if($diferencia_dias<3)
					$clasecolor = "positive";
				else
					$clasecolor = "error";

				$Datos["fila"][] = utf8_encode("<td>".$fechau."</td><td style='text-align:left;'>".$nombreu."</td>");
				$Datos["clase"][] = $clasecolor;
	 			
	 			//return utf8_encode($Datos);
	 			return $Datos;

	 			die();
	 	}

	 	public function verterroristas(){
	 			
	 			$Query = "SELECT       
                                a.ID_Importacion,
                                a.Registro,
                                a.ID_Usr
                           FROM pld_importacion_catalogos a	
                       ORDER BY a.ID_Importacion DESC		
                          LIMIT 1";
	 			
	 			$rs = $this->db->Execute($Query);

	 			$fecha = $rs->fields["Registro"];
	 			$User  = $rs->fields["ID_Usr"];

	 			$sql ="	SELECT CONCAT(Nombre,' ',AP_Paterno,' ',AP_Materno) AS Nombre
						FROM ".NUCLEO.".usuarios
						WHERE ID_User = '".$User."' ";
	
				$rs2=$this->db->Execute($sql);
	
	    		$nombre = mb_strtoupper($rs2->fields["Nombre"]);
	    		
	 			//$fecha="2014-09-16 00:00:00";
	    		//$fecha="2014-09-21 00:00:00";
	 			$segundos=strtotime('now') - strtotime($fecha);
				$diferencia_dias=intval($segundos/60/60/24);

				if($diferencia_dias>2 && $diferencia_dias<8)
					$clasecolor = "warning";
				else if($diferencia_dias<3)
					$clasecolor = "positive";
				else
					$clasecolor = "error";

				$Datos["fila"][] = utf8_encode("<td>".$fecha."</td><td style='text-align:left;'>".$nombre."</td>");
				$Datos["clase"][] = $clasecolor;
	 			
	 			//return utf8_encode($Datos);
	 			return $Datos;
	 	}

        public function verListasPropias(){
            
            $Query = "SELECT pld_cat_listas_negras_log.Fecha_sistema as fecha_actualizacion, 
			                 pld_cat_listas_negras_log.ID_Usr AS 
			                 ID_Usr,
			                 pld_cat_listas_negras_log.Accion,
			                 pld_cat_listas_negras_log.sentencia,
			                 pld_cat_listas_negras_log.Fecha_sistema AS fecha_actualizacion
			            from pld_cat_listas_negras_log
                       WHERE Tipo = 'LP'
                    GROUP BY Fecha_sistema
                    ORDER BY pld_cat_listas_negras_log.Fecha_sistema desc
                       LIMIT 1";
            
            $rs = $this->db->Execute($Query);

            $fecha = $rs->fields["fecha_actualizacion"];
            $User  = $rs->fields["ID_Usr"];

            $sql ="	SELECT CONCAT(Nombre,' ',AP_Paterno,' ',AP_Materno) AS Nombre
                            FROM ".NUCLEO.".usuarios
                            WHERE ID_User = '".$User."' ";

            $rs2=$this->db->Execute($sql);

            $nombre = mb_strtoupper($rs2->fields["Nombre"]);

            //$fecha="2014-09-16 00:00:00";
            //$fecha="2014-09-21 00:00:00";
            $segundos=strtotime('now') - strtotime($fecha);
            $diferencia_dias=intval($segundos/60/60/24);

            if($diferencia_dias>2 && $diferencia_dias<8)
                $clasecolor = "warning";
            else if($diferencia_dias<3)
                $clasecolor = "positive";
            else
                $clasecolor = "error";

            $Datos["fila"][] = utf8_encode("<td>".$fecha."</td><td style='text-align:left;'>".$nombre."</td>");
            $Datos["clase"][] = $clasecolor;

            //return utf8_encode($Datos);
            return $Datos;
        }

        public function verListasCondusef(){
			
			$Query = "SELECT pld_cat_listas_negras_log.Fecha_sistema as fecha_actualizacion, 
			                 pld_cat_listas_negras_log.ID_Usr AS 
			                 ID_Usr,
			                 pld_cat_listas_negras_log.Accion,
			                 pld_cat_listas_negras_log.sentencia,
			                 pld_cat_listas_negras_log.Fecha_sistema AS fecha_actualizacion
			            from pld_cat_listas_negras_log
                       WHERE Tipo = 'LC'
                    GROUP BY Fecha_sistema
                    ORDER BY pld_cat_listas_negras_log.Fecha_sistema desc
                       LIMIT 1";
			
            $rs = $this->db->Execute($Query);

            $fecha = $rs->fields["fecha_actualizacion"];
            $User  = $rs->fields["ID_Usr"];

            $sql ="	SELECT CONCAT(Nombre,' ',AP_Paterno,' ',AP_Materno) AS Nombre
                                FROM ".NUCLEO.".usuarios
                                WHERE ID_User = '".$User."' ";

            $rs2=$this->db->Execute($sql);

            $nombre = mb_strtoupper($rs2->fields["Nombre"]);

            //$fecha="2014-09-16 00:00:00";
            //$fecha="2014-09-21 00:00:00";
            $segundos=strtotime('now') - strtotime($fecha);
            $diferencia_dias=intval($segundos/60/60/24);

            if($diferencia_dias>2 && $diferencia_dias<8)
                $clasecolor = "warning";
            else if($diferencia_dias<3)
                $clasecolor = "positive";
            else
                $clasecolor = "error";

            $Datos["fila"][] = utf8_encode("<td>".$fecha."</td><td style='text-align:left;'>".$nombre."</td>");
            $Datos["clase"][] = $clasecolor;

            //return utf8_encode($Datos);
            return $Datos;
        }


	 	public function verPPE(){
	 			
	 			$Query = "SELECT pld_cat_listas_negras_log.Fecha_sistema as fecha_actualizacion, 
			                 pld_cat_listas_negras_log.ID_Usr AS 
			                 ID_Usr,
			                 pld_cat_listas_negras_log.Accion,
			                 pld_cat_listas_negras_log.sentencia,
			                 pld_cat_listas_negras_log.Fecha_sistema AS fecha_actualizacion
			            from pld_cat_listas_negras_log
                       WHERE Tipo = 'PPE'
                    GROUP BY Fecha_sistema
                    ORDER BY pld_cat_listas_negras_log.Fecha_sistema desc
                       LIMIT 1";
	 			
	 			$rs = $this->db->Execute($Query);

	 			$fecha = $rs->fields["fecha_actualizacion"];
	 			$User  = $rs->fields["ID_Usr"];

	 			$sql ="	SELECT CONCAT(Nombre,' ',AP_Paterno,' ',AP_Materno) AS Nombre,
					   			Login
						FROM ".NUCLEO.".usuarios
						WHERE ID_User = '".$User."' ";
	
				$rs2=$this->db->Execute($sql);
	
	    		$nombre = mb_strtoupper($rs2->fields["Nombre"]);
	    		
	 			//$fecha="2014-09-16 00:00:00";
	    		//$fecha="2014-09-21 00:00:00";
	 			$segundos=strtotime('now') - strtotime($fecha);
				$diferencia_dias=intval($segundos/60/60/24);

				if($diferencia_dias>2 && $diferencia_dias<8)
					$clasecolor = "warning";
				else if($diferencia_dias<3)
					$clasecolor = "positive";
				else
					$clasecolor = "error";

				$Datos["fila"][] = utf8_encode("<td>".$fecha."</td><td style='text-align:left;'>".$nombre."</td><td><input id='' class='ui mini blue button' type='BUTTON'  value='Detalle' onCLick='verinfo(0,\"\",\"PPE\")'></td>");
				$Datos["clase"][] = $clasecolor;
	 			
	 			//return utf8_encode($Datos);
	 			return $Datos;
	 	}


	 	public function verpuestosPPE(){
	 			
	 			$Query = "SELECT pld_cat_listas_negras_log.Fecha_sistema as fecha_actualizacion, 
			                 pld_cat_listas_negras_log.ID_Usr AS 
			                 ID_Usr,
			                 pld_cat_listas_negras_log.Accion,
			                 pld_cat_listas_negras_log.sentencia,
			                 pld_cat_listas_negras_log.Fecha_sistema AS fecha_actualizacion
			            from pld_cat_listas_negras_log
                       WHERE Tipo = 'PUESTOS'
                    GROUP BY Fecha_sistema
                    ORDER BY pld_cat_listas_negras_log.Fecha_sistema desc
                       LIMIT 1";
	 			
	 			$rs = $this->db->Execute($Query);

	 			$fecha = $rs->fields["fecha_actualizacion"];
	 			$User  = $rs->fields["ID_Usr"];

	 			$sql ="	SELECT CONCAT(Nombre,' ',AP_Paterno,' ',AP_Materno) AS Nombre,
					   			Login
						FROM ".NUCLEO.".usuarios
						WHERE ID_User = '".$User."' ";
						
				$rs2=$this->db->Execute($sql);
	
	    		$nombre = mb_strtoupper($rs2->fields["Nombre"]);
	    		
	 			//$fecha="2014-09-16 00:00:00";
	    		//$fecha="2014-09-21 00:00:00";
	 			$segundos=strtotime('now') - strtotime($fecha);
				$diferencia_dias=intval($segundos/60/60/24);

				if($diferencia_dias>2 && $diferencia_dias<8)
					$clasecolor = "warning";
				else if($diferencia_dias<3)
					$clasecolor = "positive";
				else
					$clasecolor = "error";

				$Datos["fila"][] = utf8_encode("<td>".$fecha."</td><td style='text-align:left;'>".$nombre."</td><td><input id='' class='ui mini blue button' type='BUTTON'  value='Detalle' onCLick='verinfo(0,\"\",\"PUESTOS\")'></td>");
				$Datos["clase"][] = $clasecolor;

				return $Datos;

	 	}
	 	
	 	public function verSAT(){
	 			
	 			$Query = "SELECT pld_cat_listas_negras_log.Fecha_sistema as fecha_actualizacion, 
			                 pld_cat_listas_negras_log.ID_Usr AS 
			                 ID_Usr,
			                 pld_cat_listas_negras_log.Accion,
			                 pld_cat_listas_negras_log.sentencia,
			                 pld_cat_listas_negras_log.Fecha_sistema AS fecha_actualizacion
			            from pld_cat_listas_negras_log
                       WHERE Tipo = 'SAT'
                    GROUP BY Fecha_sistema
                    ORDER BY pld_cat_listas_negras_log.Fecha_sistema desc
                       LIMIT 1";	 			
	 			
	 			$rs = $this->db->Execute($Query);

	 			$fecha = $rs->fields["fecha_actualizacion"];
	 			$User  = $rs->fields["ID_Usr"];

	 			$sql ="	SELECT CONCAT(Nombre,' ',AP_Paterno,' ',AP_Materno) AS Nombre,
					   			Login
						FROM ".NUCLEO.".usuarios
						WHERE ID_User = '".$User."' ";
	
				$rs2=$this->db->Execute($sql);
	
	    		$nombre = mb_strtoupper($rs2->fields["Nombre"]);
	    		
	 			//$fecha="2014-09-16 00:00:00";
	    		//$fecha="2014-09-21 00:00:00";
	 			$segundos=strtotime('now') - strtotime($fecha);
				$diferencia_dias=intval($segundos/60/60/24);

				if($diferencia_dias>2 && $diferencia_dias<8)
					$clasecolor = "warning";
				else if($diferencia_dias<3)
					$clasecolor = "positive";
				else
					$clasecolor = "error";

				$Datos["fila"][] = utf8_encode("<td>".$fecha."</td><td style='text-align:left;'>".$nombre."</td><td><input id='' class='ui mini blue button' type='BUTTON'  value='Detalle' onCLick='verinfo(0,\"\",\"SAT\")'></td>");
				$Datos["clase"][] = $clasecolor;

				return $Datos;

	 	}

	 	public function verCP(){
	 			
	 			$Query = "SELECT pld_cat_listas_negras_log.Fecha_sistema as fecha_actualizacion, 
			                 pld_cat_listas_negras_log.ID_Usr AS 
			                 ID_Usr,
			                 pld_cat_listas_negras_log.Accion,
			                 pld_cat_listas_negras_log.sentencia,
			                 pld_cat_listas_negras_log.Fecha_sistema AS fecha_actualizacion
			            from pld_cat_listas_negras_log
                       WHERE Tipo = 'CP'
                    GROUP BY Fecha_sistema
                    ORDER BY pld_cat_listas_negras_log.Fecha_sistema desc
                       LIMIT 1";
	 			
	 			$rs = $this->db->Execute($Query);

	 			$fecha = $rs->fields["fecha_actualizacion"];
	 			$User  = $rs->fields["ID_Usr"];

	 			$sql ="	SELECT CONCAT(Nombre,' ',AP_Paterno,' ',AP_Materno) AS Nombre FROM usuarios WHERE ID_User =  '".$User."' ";
	
				$rs2=$this->db->Execute($sql);
	
	    		$nombre = mb_strtoupper($rs2->fields["Nombre"]);
	    		
	 			//$fecha="2014-09-16 00:00:00";
	    		//$fecha="2014-09-21 00:00:00";
	 			$segundos=strtotime('now') - strtotime($fecha);
				$diferencia_dias=intval($segundos/60/60/24);

                /*****  quitamos los colores porque no es un catalogo que se este actualizando a cada rato
				if($diferencia_dias>2 && $diferencia_dias<8)
					$clasecolor = "warning";
				else if($diferencia_dias<3)
					$clasecolor = "positive";
				else
					$clasecolor = "error";
                */
				$Datos["fila"][] = utf8_encode("<td>".$fecha."</td><td style='text-align:left;'>".$nombre."</td><td><input id='' class='ui mini blue button' type='BUTTON'  value='Detalle' onCLick='verinfo(0,\"\",\"CP\")'></td>");
				$Datos["clase"][] = $clasecolor;

				return $Datos;
	 	}


	 	public function verEstado(){

	 			$Query = "SELECT a.ID_Usr AS ID_Usr,
	 			                 a.Fecha_sistema AS Fecha_sistema 
	 					    FROM pld_cat_listas_negras_log a
	 					   WHERE (SELECT max(pld_catalogos_log.Fecha_sistema) from pld_catalogos_log)
							 AND Tipo = 'ESTADOS' 
	 				    ORDER BY Fecha_sistema DESC
	 					   LIMIT 1";


	 			$rs = $this->db->Execute($Query);

	 			$fecha = $rs->fields["Fecha_sistema"];
	 			$User  = $rs->fields["ID_Usr"];

	 			$sql ="	SELECT CONCAT(Nombre,' ',AP_Paterno,' ',AP_Materno) AS Nombre,
					   			Login
						FROM ".NUCLEO.".usuarios
						WHERE ID_User = '".$User."' ";
	
				$rs2=$this->db->Execute($sql);
	
	    		$nombre = mb_strtoupper($rs2->fields["Nombre"]);
	    		
	 			//$fecha="2014-09-16 00:00:00";
	    		//$fecha="2014-09-21 00:00:00";
	 			$segundos=strtotime('now') - strtotime($fecha);
				$diferencia_dias=intval($segundos/60/60/24);

                /*****  quitamos los colores porque no es un catalogo que se este actualizando a cada rato
				if($diferencia_dias>2 && $diferencia_dias<8)
					$clasecolor = "warning";
				else if($diferencia_dias<3)
					$clasecolor = "positive";
				else
					$clasecolor = "error";
                 * */

				$Datos["fila"][] = utf8_encode("<td>".$fecha."</td><td style='text-align:left;'>".$nombre."</td><td><input id='' class='ui mini blue button' type='BUTTON'  value='Detalle' onCLick='verinfo(0,\"\",\"ESTADOS\")'></td>");
				$Datos["clase"][] = $clasecolor;

				return $Datos;
	 	}


	 	public function verCiudades(){
	 			
	 			$Query = "SELECT a.ID_Usr AS ID_Usr,
	 			                 a.Fecha_sistema AS Fecha_sistema 
	 					    FROM pld_cat_listas_negras_log a
	 					   WHERE (SELECT max(pld_catalogos_log.Fecha_sistema) from pld_catalogos_log)
							 AND Tipo = 'CIUDADES' 
	 				    ORDER BY Fecha_sistema DESC
	 					   LIMIT 1";
	 			
	 			$rs = $this->db->Execute($Query);

	 			$fecha = $rs->fields["Fecha_sistema"];
	 			$User  = $rs->fields["ID_Usr"];

	 			$sql ="	SELECT CONCAT(Nombre,' ',AP_Paterno,' ',AP_Materno) AS Nombre,
					   			Login
						FROM ".NUCLEO.".usuarios
						WHERE ID_User = '".$User."' ";
	
				$rs2=$this->db->Execute($sql);
	
	    		$nombre = mb_strtoupper($rs2->fields["Nombre"]);
	    		
	 			//$fecha="2014-09-16 00:00:00";
	    		//$fecha="2014-09-21 00:00:00";
	 			$segundos=strtotime('now') - strtotime($fecha);
				$diferencia_dias=intval($segundos/60/60/24);

                /*****  quitamos los colores porque no es un catalogo que se este actualizando a cada rato
				if($diferencia_dias>2 && $diferencia_dias<8)
					$clasecolor = "warning";
				else if($diferencia_dias<3)
					$clasecolor = "positive";
				else
					$clasecolor = "error";
                 * */

				$Datos["fila"][] = utf8_encode("<td>".$fecha."</td><td style='text-align:left;'>".$nombre."</td><td><input id='' class='ui mini blue button' type='BUTTON'  value='Detalle' onCLick='verinfo(0,\"\",\"CIUDADES\")'></td>");
				$Datos["clase"][] = $clasecolor;

				return $Datos;
	 	}


	 	public function verGiros(){
	 			
	 			$Query = "SELECT a.ID_Usr AS ID_Usr,
	 			                 a.Fecha_sistema AS Fecha_sistema 
	 					    FROM pld_cat_listas_negras_log a
	 					   WHERE (SELECT max(pld_catalogos_log.Fecha_sistema) from pld_catalogos_log)
							 AND Tipo = 'ACTIVIDADES' 
	 				    ORDER BY Fecha_sistema DESC
	 					   LIMIT 1";

	 			
	 			$rs = $this->db->Execute($Query);

	 			$fecha = $rs->fields["Fecha_sistema"];
	 			$User  = $rs->fields["ID_Usr"];

	 			$sql ="	SELECT CONCAT(Nombre,' ',AP_Paterno,' ',AP_Materno) AS Nombre,
					   			Login
						FROM ".NUCLEO.".usuarios
						WHERE ID_User = '".$User."' ";
	
				$rs2=$this->db->Execute($sql);
	
	    		$nombre = mb_strtoupper($rs2->fields["Nombre"]);
	    		
	 			//$fecha="2014-09-16 00:00:00";
	    		//$fecha="2014-09-21 00:00:00";
	 			$segundos=strtotime('now') - strtotime($fecha);
				$diferencia_dias=intval($segundos/60/60/24);

                /*****  quitamos los colores porque no es un catalogo que se este actualizando a cada rato
				if($diferencia_dias>2 && $diferencia_dias<8)
					$clasecolor = "warning";
				else if($diferencia_dias<3)
					$clasecolor = "positive";
				else
					$clasecolor = "error";
                 * */

				$Datos["fila"][] = utf8_encode("<td>".$fecha."</td><td style='text-align:left;'>".$nombre."</td><td><input id='' class='ui mini blue button' type='BUTTON'  value='Detalle' onCLick='verinfo(0,\"\",\"ACTIVIDADES\")'></td>");
				$Datos["clase"][] = $clasecolor;

				return $Datos;
	 	}
	 	
	 	public function verPais(){
	 			
	 			$Query = "SELECT a.ID_Usr AS ID_Usr,
	 			                 a.Fecha_sistema AS Fecha_sistema 
	 					    FROM pld_cat_listas_negras_log a
	 					   WHERE (SELECT max(pld_catalogos_log.Fecha_sistema) from pld_catalogos_log)
							 AND Tipo = 'PAIS_RIESGO' 
	 				    ORDER BY Fecha_sistema DESC
	 					   LIMIT 1";

	 			
	 			$rs = $this->db->Execute($Query);

	 			$fecha = $rs->fields["Fecha_sistema"];
	 			$User  = $rs->fields["ID_Usr"];

	 			$sql ="	SELECT CONCAT(Nombre,' ',AP_Paterno,' ',AP_Materno) AS Nombre,
					   			Login
						FROM ".NUCLEO.".usuarios
						WHERE ID_User = '".$User."' ";
	
				$rs2=$this->db->Execute($sql);
	
	    		$nombre = mb_strtoupper($rs2->fields["Nombre"]);
	    		
	 			//$fecha="2014-09-16 00:00:00";
	    		//$fecha="2014-09-21 00:00:00";
	 			$segundos=strtotime('now') - strtotime($fecha);
				$diferencia_dias=intval($segundos/60/60/24);

                /*****  quitamos los colores porque no es un catalogo que se este actualizando a cada rato
				if($diferencia_dias>2 && $diferencia_dias<8)
					$clasecolor = "warning";
				else if($diferencia_dias<3)
					$clasecolor = "positive";
				else
					$clasecolor = "error";
                 * */

				$Datos["fila"][] = utf8_encode("<td>".$fecha."</td><td style='text-align:left;'>".$nombre."</td><td><input id='' class='ui mini blue button' type='BUTTON'  value='Detalle' onCLick='verinfo(0,\"\",\"PAIS_RIESGO\")'></td>");
				$Datos["clase"][] = $clasecolor;

				return $Datos;
	 	}




/////////////////////////////////////////////////////////////////////////

	 	public function VistaDolares($Tipo,$Pagina,$Evento,$Fecha_inicial,$Fecha_final){
			
				$muestra = 16;
				$condicion = "";
								

				if($Tipo == "DOLARES")
				{
					if($Fecha_inicial != "" and $Fecha_final != "")
					{
						$condicion = "AND pld_importacion_tipocambio.Dia between '".$Fecha_inicial."' and '".$Fecha_final."' ";
					}
					
					// 
					$Query_count = "SELECT count(distinct Registro) AS fecha_actualizacion FROM pld_importacion_tipocambio";
									
						// GROUP BY Registro
					
					$Query = "SELECT pld_importacion_tipocambio.Registro as fecha_actualizacion, pld_importacion_tipocambio.ID_Usr AS ID_Usr  from pld_importacion_tipocambio GROUP BY Registro
							  ORDER BY pld_importacion_tipocambio.Registro desc
							";
							$Query_act = "SELECT max(pld_importacion_tipocambio.Registro) as fecha_actualizacion from pld_importacion_tipocambio";
							

				}
				else 
				{
					
					if($Fecha_inicial != "" and $Fecha_final != "")
					{
						$condicion = "AND pld_importacion_indicadores.Dia between '".$Fecha_inicial."' and '".$Fecha_final."' ";
					}
					
					
					
					
					$Query_count = "SELECT count(distinct Registro) AS fecha_actualizacion FROM pld_importacion_tipocambio";
									
						// GROUP BY Registro
					
					$Query = "SELECT pld_importacion_tipocambio.Registro as fecha_actualizacion, pld_importacion_tipocambio.ID_Usr AS ID_Usr  from pld_importacion_tipocambio GROUP BY Registro
							  ORDER BY pld_importacion_tipocambio.Registro desc
							";
							$Query_act = "SELECT max(pld_importacion_tipocambio.Registro) as fecha_actualizacion from pld_importacion_tipocambio";
					
			  	}
				
				 $RESPUESTA      = $this->db->Execute($Query_count); 

				 $total_registros = $RESPUESTA->fields["fecha_actualizacion"];
				 $total_paginas = number_format($total_registros/$muestra,0);
				 
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
						 $limite_ini = ($Pagina+1) * $muestra;
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
				 $Query .= $limite;		
				 				
				 $RESPUESTA      = $this->db->Execute($Query);  //debug($Query);
				  
				  
		  		 $RESPUESTA2      = $this->db->Execute($Query_act); //debug($Query);	
				 $fecha_actualizacion = $RESPUESTA2->fields["fecha_actualizacion"];	
					
					
				 //echo $total_registros."<-->".$total_paginas."<-->".$Pagina."<-->".$limite_ini."<-->".$Query;
				 //return;
				  while( !$RESPUESTA->EOF ) { 

				  	$sql ="SELECT CONCAT(Nombre,' ',AP_Paterno,' ',AP_Materno) AS Nombre FROM usuarios WHERE ID_User =  '".$RESPUESTA->fields["ID_Usr"]."' ";
					$rs2=$this->db->Execute($sql);
	    			$nombre = mb_strtoupper($rs2->fields["Nombre"]);
						
					 $Valor     = $nombre; 
					 $Fecha   	= $RESPUESTA->fields["fecha_actualizacion"];
					 $Indicador = $RESPUESTA->fields["Indicador"];

					 
					 $htmlTbody .= '
			  
							<tr>
								<td>'.strtoupper($Fecha).'</td>
								<td>'.strtoupper($Valor).'</td>
							</tr>
						  
						  ';
					 $RESPUESTA->MoveNext(); 
				  } // fin while( !$RESPUESTA->EOF ) {		

				$html = ' 
				<h3>Fecha de última Actualización '.$fecha_actualizacion.'</h3>
				  <table align="center" width="100%" class="ui table segment">
					<thead>
						<tr>
							<th width="50%" align="center">FECHA</th>
							<th width="40%">USUARIO</th>

						</tr>
					</thead>
					'.$htmlTbody.'
				</table>';
				
				
				$html .= "<div class='ui'>
							<i class='big step backward icon' onclick='ActualizaListadoDolares(\"First\")'></i>
							<i class='big backward icon' onclick='ActualizaListadoDolares(\"Prev\")'></i>
							<input type='text' maxlength='4' size='4' id='paginacionUni' onchange='CambiaPaginaListadoDolares(this)' value='".$Pagina."'>
							<i class='big forward icon' onclick='ActualizaListadoDolares(\"Next\")'></i>
							<i class='big step forward icon' onclick='ActualizaListadoDolares(\"Last\")'></i>
							
					    </div>
						
						"; 
							
				echo  $html;
	 	
        	 
 		} // fin public function VistaPersonas(){


 public function VistaUnidades($Tipo,$Pagina,$Evento,$Fecha_inicial,$Fecha_final){
			
				$muestra = 16;
				$condicion = "";
								

				if($Tipo == "DOLARES")
				{
					if($Fecha_inicial != "" and $Fecha_final != "")
					{
						$condicion = "AND pld_importacion_tipocambio.Dia between '".$Fecha_inicial."' and '".$Fecha_final."' ";
					}
					
					// 
					$Query_count = "SELECT count(distinct Registro) AS fecha_actualizacion FROM pld_importacion_indicadores";
								
						// GROUP BY Registro
					
					$Query = "SELECT pld_importacion_indicadores.Registro as fecha_actualizacion,pld_importacion_indicadores.ID_Usr AS ID_Usr  from pld_importacion_indicadores GROUP BY Registro
							  ORDER BY pld_importacion_indicadores.Registro desc 							
							";
							$Query_act = "SELECT max(pld_importacion_indicadores.Registro) as fecha_actualizacion from pld_importacion_indicadores";
							

				}
				else 
				{
					
					if($Fecha_inicial != "" and $Fecha_final != "")
					{
						$condicion = "AND pld_importacion_indicadores.Dia between '".$Fecha_inicial."' and '".$Fecha_final."' ";
					}
					
					
					
					
					$Query_count = "SELECT count(distinct Registro) AS fecha_actualizacion FROM pld_importacion_indicadores";
								
						// GROUP BY Registro
					
					$Query = "SELECT pld_importacion_indicadores.Registro as fecha_actualizacion,pld_importacion_indicadores.ID_Usr AS ID_Usr  from pld_importacion_indicadores GROUP BY  Registro
							  ORDER BY pld_importacion_indicadores.Registro desc 							
							";
							$Query_act = "SELECT max(pld_importacion_indicadores.Registro) as fecha_actualizacion from pld_importacion_indicadores";
					
			  	}
				
				 $RESPUESTA      = $this->db->Execute($Query_count); 

				 $total_registros = $RESPUESTA->fields["fecha_actualizacion"];
				 $total_paginas = number_format($total_registros/$muestra,0);
				 
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
						 $limite_ini = ($Pagina+1) * $muestra;
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
				 $Query .= $limite;		
				 				
				 $RESPUESTA      = $this->db->Execute($Query);  //debug($Query);
				  
				  
		  		 $RESPUESTA2      = $this->db->Execute($Query_act); //debug($Query);	
				 $fecha_actualizacion = $RESPUESTA2->fields["fecha_actualizacion"];	
					
					
				 //echo $total_registros."<-->".$total_paginas."<-->".$Pagina."<-->".$limite_ini."<-->".$Query;
				 //return;
				  while( !$RESPUESTA->EOF ) { 

				  	$sql ="SELECT CONCAT(Nombre,' ',AP_Paterno,' ',AP_Materno) AS Nombre FROM usuarios WHERE ID_User =  '".$RESPUESTA->fields["ID_Usr"]."' ";
					$rs2=$this->db->Execute($sql);
	    			$nombre = mb_strtoupper($rs2->fields["Nombre"]);
						
					 $Valor     = $nombre; 
					 $Fecha   	= $RESPUESTA->fields["fecha_actualizacion"];
					 $Indicador = $RESPUESTA->fields["Indicador"];

					 
					 $htmlTbody .= '
			  
							<tr>
								<td>'.strtoupper($Fecha).'</td>
								<td>'.strtoupper($Valor).'</td>
							</tr>
						  
						  ';
					 $RESPUESTA->MoveNext(); 
				  } // fin while( !$RESPUESTA->EOF ) {		

				$html = ' 
				<h3>Fecha de última Actualización '.$fecha_actualizacion.'</h3>
				  <table align="center" width="100%" class="ui table segment">
					<thead>
						<tr>
							<th width="50%" align="center">FECHA</th>
							<th width="40%">USUARIO</th>

						</tr>
					</thead>
					'.$htmlTbody.'
				</table>';
				
				
				$html .= "<div class='ui'>
							<i class='big step backward icon' onclick='ActualizaListadoUnidades(\"First\")'></i>
							<i class='big backward icon' onclick='ActualizaListadoUnidades(\"Prev\")'></i>
							<input type='text' maxlength='4' size='4' id='paginacionUni' onchange='CambiaPaginaListadoUnidades(this)' value='".$Pagina."'>
							<i class='big forward icon' onclick='ActualizaListadoUnidades(\"Next\")'></i>
							<i class='big step forward icon' onclick='ActualizaListadoUnidades(\"Last\")'></i>
							
					    </div>
						
						"; 
							
				echo  $html;
	 	
        	 
 		} // fin public function VistaPersonas(){



 		public function VistaTerroristas($Tipo,$Pagina,$Evento,$Fecha_inicial,$Fecha_final){//Erick
			
				$muestra = 15;
				$condicion = "";
								

				if($Tipo == "TERRORISTAS")
				{
					if($Fecha_inicial != "" and $Fecha_final != "")
					{
						$condicion = "AND pld_importacion_tipocambio.Dia between '".$Fecha_inicial."' and '".$Fecha_final."' ";
					}
					
					// 
					$Query_count = "SELECT count(distinct Registro) AS fecha_actualizacion FROM pld_importacion_catalogos";
									
						// GROUP BY Registro
					
					$Query = "SELECT pld_importacion_catalogos.Registro as fecha_actualizacion , pld_importacion_catalogos.ID_Usr AS ID_Usr from pld_importacion_catalogos GROUP BY  Registro
							  ORDER BY pld_importacion_catalogos.Registro desc
							";
							$Query_act = "SELECT max(pld_importacion_catalogos.Registro) as fecha_actualizacion from pld_importacion_catalogos";
							

				}
				else 
				{
					
					if($Fecha_inicial != "" and $Fecha_final != "")
					{
						$condicion = "AND pld_importacion_indicadores.Dia between '".$Fecha_inicial."' and '".$Fecha_final."' ";
					}
					
					
					
					
					$Query_count = "SELECT count(distinct Registro) AS fecha_actualizacion FROM pld_importacion_catalogos";
									
						// GROUP BY Registro
					
					$Query = "SELECT pld_importacion_catalogos.Registro as fecha_actualizacion , pld_importacion_catalogos.ID_Usr AS ID_Usr from pld_importacion_catalogos GROUP BY  Registro
							  ORDER BY pld_importacion_catalogos.Registro desc
							";
							$Query_act = "SELECT max(pld_importacion_catalogos.Registro) as fecha_actualizacion from pld_importacion_catalogos";
					
			  	}
				
				 $RESPUESTA      = $this->db->Execute($Query_count); 

				 $total_registros = $RESPUESTA->fields["fecha_actualizacion"];
				 $total_paginas = number_format($total_registros/$muestra,0);
				 
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
						 $limite_ini = ($Pagina+1) * $muestra;
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
				 $Query .= $limite;		
				 				
				 $RESPUESTA      = $this->db->Execute($Query);  //debug($Query);
				  
				  
		  		 $RESPUESTA2      = $this->db->Execute($Query_act); //debug($Query);	
				 $fecha_actualizacion = $RESPUESTA2->fields["fecha_actualizacion"];	
					
					
				 //echo $total_registros."<-->".$total_paginas."<-->".$Pagina."<-->".$limite_ini."<-->".$Query;
				 //return;
				  while( !$RESPUESTA->EOF ) { 

				  	$sql ="SELECT CONCAT(Nombre,' ',AP_Paterno,' ',AP_Materno) AS Nombre FROM usuarios WHERE ID_User =  '".$RESPUESTA->fields["ID_Usr"]."' ";
					$rs2=$this->db->Execute($sql);
	    			$nombre = mb_strtoupper($rs2->fields["Nombre"]);
						
					 $Valor     = $nombre; 
					 $Fecha   	= $RESPUESTA->fields["fecha_actualizacion"];
					 $Indicador = $RESPUESTA->fields["Indicador"];

					 
					 $htmlTbody .= '
			  
							<tr>
								<td>'.strtoupper($Fecha).'</td>
								<td>'.strtoupper($Valor).'</td>
							</tr>
						  
						  ';
					 $RESPUESTA->MoveNext(); 
				  } // fin while( !$RESPUESTA->EOF ) {		

				$html = ' 
				<h3>Fecha de última Actualización '.$fecha_actualizacion.'</h3>
				  <table align="center" width="100%" class="ui table segment">
					<thead>
						<tr>
							<th width="50%" align="center">FECHA</th>
							<th width="40%">USUARIO</th>

						</tr>
					</thead>
					'.$htmlTbody.'
				</table>';
				
				
				$html .= "<div class='ui'>
							<i class='big step backward icon' onclick='ActualizaListadoTerroristas(\"First\")'></i>
							<i class='big backward icon' onclick='ActualizaListadoTerroristas(\"Prev\")'></i>
							<input type='text' maxlength='4' size='4' id='paginacionUni' onchange='CambiaPaginaListadoTerroristas(this)' value='".$Pagina."'>
							<i class='big forward icon' onclick='ActualizaListadoTerroristas(\"Next\")'></i>
							<i class='big step forward icon' onclick='ActualizaListadoTerroristas(\"Last\")'></i>
							
					    </div>
						
						"; 
							
				echo  $html;
	 	
        	 
 		} // fin public function VistaPersonas(){

    public function VistaListaCondusef($Tipo,$Pagina,$Evento,$Fecha_inicial,$Fecha_final){//Erick

        $muestra = 15;
        $condicion = "";


        if($Fecha_inicial != "" and $Fecha_final != "")
        {
            //$condicion = "AND pld_importacion_tipocambio.Dia between '".$Fecha_inicial."' and '".$Fecha_final."' ";
        }

        //
        $Query_count = "SELECT count(distinct Fecha_sistema) AS fecha_actualizacion FROM pld_cat_listas_negras_log WHERE Tipo = 'LC' ";

        // GROUP BY Registro

        $Query = "SELECT pld_cat_listas_negras_log.Fecha_sistema as fecha_actualizacion , pld_cat_listas_negras_log.ID_Usr AS ID_Usr, pld_cat_listas_negras_log.Accion,pld_cat_listas_negras_log.sentencia from pld_cat_listas_negras_log
                          WHERE Tipo = 'LC' GROUP BY  Fecha_sistema
                          ORDER BY pld_cat_listas_negras_log.Fecha_sistema desc
                        ";
        $Query_act = "SELECT max(pld_cat_listas_negras_log.Fecha_sistema) as fecha_actualizacion from pld_cat_listas_negras_log WHERE Tipo = 'LC' ";




        $RESPUESTA      = $this->db->Execute($Query_count);

        $total_registros = $RESPUESTA->fields["fecha_actualizacion"];
        $total_paginas = number_format($total_registros/$muestra,0);

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
                $limite_ini = ($Pagina+1) * $muestra;
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
        $Query .= $limite;

        $RESPUESTA      = $this->db->Execute($Query);  //debug($Query);


        $RESPUESTA2      = $this->db->Execute($Query_act); //debug($Query);
        $fecha_actualizacion = $RESPUESTA2->fields["fecha_actualizacion"];


        //echo $total_registros."<-->".$total_paginas."<-->".$Pagina."<-->".$limite_ini."<-->".$Query;
        //return;
        while( !$RESPUESTA->EOF ) {

            $sql ="SELECT CONCAT(Nombre,' ',AP_Paterno,' ',AP_Materno) AS Nombre FROM usuarios WHERE ID_User =  '".$RESPUESTA->fields["ID_Usr"]."' ";
            $rs2=$this->db->Execute($sql);
            $nombre = mb_strtoupper($rs2->fields["Nombre"]);

            $Valor     = $nombre;
            $Fecha   	= $RESPUESTA->fields["fecha_actualizacion"];
            $Indicador = $RESPUESTA->fields["Indicador"];
            $accion  	= $RESPUESTA->fields["Accion"];

            if($accion == "INSERT")
            	$accion2 = "AGREGO REGISTRO";
             if($accion == "DELETE")
            	$accion2 = "ELIMINO REGISTRO";
             if($accion == "UPDATE")
            	$accion2 = "ACTUALIZO REGISTRO";
            if($accion == "UPLOAD")
            	$accion2 = "SE CARGARON ".$RESPUESTA->fields["sentencia"]." REGISTROS";

            $htmlTbody .= '

							<tr>
								<td>'.strtoupper($Fecha).'</td>
								<td>'.strtoupper($Valor).'</td>
								<td>'.strtoupper($accion2).'</td>
							</tr>

						  ';
            $RESPUESTA->MoveNext();
        } // fin while( !$RESPUESTA->EOF ) {

        $html = '
				<h3>Fecha de última Actualización '.$fecha_actualizacion.'</h3>
				  <table align="center" width="100%" class="ui table segment">
					<thead>
						<tr>
							<th width="30%" align="center">FECHA</th>
							<th width="40%">USUARIO</th>
							<th width="30%">ACTIVIDAD</th>

						</tr>
					</thead>
					'.$htmlTbody.'
				</table>';


        $html .= "<div class='ui'>
							<i class='big step backward icon' onclick='ActualizaListasCondusef(\"First\")'></i>
							<i class='big backward icon' onclick='ActualizaListasCondusef(\"Prev\")'></i>
							<input type='text' maxlength='4' size='4' id='paginacionUni' onchange='CambiaPaginaListasCondusef(this)' value='".$Pagina."'>
							<i class='big forward icon' onclick='ActualizaListasCondusef(\"Next\")'></i>
							<i class='big step forward icon' onclick='ActualizaListasCondusef(\"Last\")'></i>

					    </div>

						";

        echo  $html;


    } // fin public function VistaPersonas(){


    public function VistaListaPropias($Tipo,$Pagina,$Evento,$Fecha_inicial,$Fecha_final){//Erick

        $muestra = 15;
        $condicion = "";


        if($Fecha_inicial != "" and $Fecha_final != "")
        {
            //$condicion = "AND pld_importacion_tipocambio.Dia between '".$Fecha_inicial."' and '".$Fecha_final."' ";
        }

        //
        $Query_count = "SELECT count(distinct Fecha_sistema) AS fecha_actualizacion FROM pld_cat_listas_negras_log WHERE Tipo = 'LP' ";

        // GROUP BY Registro

        $Query = "SELECT pld_cat_listas_negras_log.Fecha_sistema as fecha_actualizacion , pld_cat_listas_negras_log.ID_Usr AS ID_Usr, pld_cat_listas_negras_log.Accion,pld_cat_listas_negras_log.sentencia from pld_cat_listas_negras_log
                          WHERE Tipo = 'LP' GROUP BY  Fecha_sistema
                          ORDER BY pld_cat_listas_negras_log.Fecha_sistema desc
                        ";
        $Query_act = "SELECT max(pld_cat_listas_negras_log.Fecha_sistema) as fecha_actualizacion from pld_cat_listas_negras_log WHERE Tipo = 'LP' ";

        $RESPUESTA      = $this->db->Execute($Query_count);

        $total_registros = $RESPUESTA->fields["fecha_actualizacion"];
        $total_paginas = number_format($total_registros/$muestra,0);

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
                $limite_ini = ($Pagina+1) * $muestra;
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
        $Query .= $limite;

        $RESPUESTA      = $this->db->Execute($Query);  //debug($Query);


        $RESPUESTA2      = $this->db->Execute($Query_act); //debug($Query);
        $fecha_actualizacion = $RESPUESTA2->fields["fecha_actualizacion"];


        //echo $total_registros."<-->".$total_paginas."<-->".$Pagina."<-->".$limite_ini."<-->".$Query;
        //return;
        while( !$RESPUESTA->EOF ) {

            $sql ="SELECT CONCAT(Nombre,' ',AP_Paterno,' ',AP_Materno) AS Nombre FROM usuarios WHERE ID_User =  '".$RESPUESTA->fields["ID_Usr"]."' ";
            $rs2=$this->db->Execute($sql);
            $nombre = mb_strtoupper($rs2->fields["Nombre"]);


            $Valor     = $nombre;
            $Fecha   	= $RESPUESTA->fields["fecha_actualizacion"];
            $Indicador = $RESPUESTA->fields["Indicador"];
            $accion  	= $RESPUESTA->fields["Accion"];

            if($accion == "INSERT")
            	$accion2 = "AGREGO REGISTRO";
             if($accion == "DELETE")
            	$accion2 = "ELIMINO REGISTRO";
             if($accion == "UPDATE")
            	$accion2 = "ACTUALIZO REGISTRO";
             if($accion == "UPLOAD")
            	$accion2 = "Se cargaron ".$RESPUESTA->fields["sentencia"]." Registros";


            $htmlTbody .= '

							<tr>
								<td>'.strtoupper($Fecha).'</td>
								<td>'.strtoupper($Valor).'</td>
								<td>'.strtoupper($accion2).'</td>
							</tr>

						  ';
            $RESPUESTA->MoveNext();
        } // fin while( !$RESPUESTA->EOF ) {

        $html = '
				<h3>Fecha de última Actualización '.$fecha_actualizacion.'</h3>
				  <table align="center" width="100%" class="ui table segment">
					<thead>
						<tr>
							<th width="30%" align="center">FECHA</th>
							<th width="40%">USUARIO</th>
							<th width="30%">ACTIVIDAD</th>

						</tr>
					</thead>
					'.$htmlTbody.'
				</table>';


        $html .= "<div class='ui'>
							<i class='big step backward icon' onclick='ActualizaListasPropias(\"First\")'></i>
							<i class='big backward icon' onclick='ActualizaListasPropias(\"Prev\")'></i>
							<input type='text' maxlength='4' size='4' id='paginacionUni' onchange='CambiaPaginaListasPropias(this)' value='".$Pagina."'>
							<i class='big forward icon' onclick='ActualizaListasPropias(\"Next\")'></i>
							<i class='big step forward icon' onclick='ActualizaListasPropias(\"Last\")'></i>

					    </div>

						";

        echo  $html;


    } // fin public function VistaPersonas(){


    	 public function VistaListaPPE($Tipo,$Pagina,$Evento,$Evento2,$Fecha_final){//Erick

        $muestra = 15;
        $condicion = "";
        
        if($Fecha_inicial != "" and $Fecha_final != "")
        {
            //$condicion = "AND pld_importacion_tipocambio.Dia between '".$Fecha_inicial."' and '".$Fecha_final."' ";
        }

        //
        $Query_count = "SELECT count(distinct Fecha_sistema) AS fecha_actualizacion FROM pld_cat_listas_negras_log WHERE Tipo = '".$_POST["Evento2"]."' ";

        // GROUP BY Registro

        $Query = "SELECT pld_cat_listas_negras_log.Fecha_sistema as fecha_actualizacion , pld_cat_listas_negras_log.ID_Usr AS ID_Usr, pld_cat_listas_negras_log.Accion,pld_cat_listas_negras_log.sentencia from pld_cat_listas_negras_log
                          WHERE Tipo = '".$_POST["Evento2"]."' GROUP BY  Fecha_sistema
                          ORDER BY pld_cat_listas_negras_log.Fecha_sistema desc
                        ";
        $Query_act = "SELECT max(pld_cat_listas_negras_log.Fecha_sistema) as fecha_actualizacion from pld_cat_listas_negras_log WHERE Tipo = '".$_POST["Evento2"]."' ";




        $RESPUESTA      = $this->db->Execute($Query_count); //debug($Query);

        $total_registros = $RESPUESTA->fields["fecha_actualizacion"];
        $total_paginas = number_format($total_registros/$muestra,0);

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
                $limite_ini = ($Pagina+1) * $muestra;
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
        $Query .= $limite;

        $RESPUESTA      = $this->db->Execute($Query);  //debug($Query);


        $RESPUESTA2      = $this->db->Execute($Query_act); //debug($Query);
        $fecha_actualizacion = $RESPUESTA2->fields["fecha_actualizacion"];


        //echo $total_registros."<-->".$total_paginas."<-->".$Pagina."<-->".$limite_ini."<-->".$Query;
        //return;
        while( !$RESPUESTA->EOF ) {

            $sql ="SELECT CONCAT(Nombre,' ',AP_Paterno,' ',AP_Materno) AS Nombre FROM usuarios WHERE ID_User =  '".$RESPUESTA->fields["ID_Usr"]."' ";
            $rs2=$this->db->Execute($sql);
            $nombre = mb_strtoupper($rs2->fields["Nombre"]);


            $Valor     = $nombre;
            $Fecha   	= $RESPUESTA->fields["fecha_actualizacion"];
            $Indicador = $RESPUESTA->fields["Indicador"];
            $accion  	= $RESPUESTA->fields["Accion"];

            if($accion == "INSERT")
            	$accion2 = "AGREGO REGISTRO";
             if($accion == "DELETE")
            	$accion2 = "ELIMINO REGISTRO";
             if($accion == "UPDATE")
            	$accion2 = "ACTUALIZO REGISTRO";
             if($accion == "UPLOAD")
            	$accion2 = "Se cargaron ".$RESPUESTA->fields["sentencia"]." Registros";



            $htmlTbody .= '

							<tr>
								<td>'.strtoupper($Fecha).'</td>
								<td>'.strtoupper($Valor).'</td>
								<td>'.strtoupper($accion2).'</td>
							</tr>

						  ';
            $RESPUESTA->MoveNext();
        } // fin while( !$RESPUESTA->EOF ) {

        $html = '
				<h3>Fecha de última Actualización '.$fecha_actualizacion.'</h3>
				  <table align="center" width="100%" class="ui table segment">
					<thead>
						<tr>
							<th width="30%" align="center">FECHA</th>
							<th width="40%">USUARIO</th>
							<th width="30%">ACTIVIDAD</th>

						</tr>
					</thead>
					'.$htmlTbody.'
				</table>';


        $html .= "<div class='ui'>
							<i class='big step backward icon' onclick='ActualizaListasPPE(\"First\")'></i>
							<i class='big backward icon' onclick='ActualizaListasPPE(\"Prev\")'></i>
							<input type='text' maxlength='4' size='4' id='paginacionUni' onchange='CambiaPaginaListasPPE(this)' value='".$Pagina."'>
							<i class='big forward icon' onclick='ActualizaListasPPE(\"Next\")'></i>
							<i class='big step forward icon' onclick='ActualizaListasPPE(\"Last\")'></i>

					    </div>

						";

        echo  $html;


    } // fin public function VistaPersonas(){

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////77
    public function VistasCatalogos($Pagina,$Evento,$Evento2){//Erick

        $muestra = 10;
        

        //
        $Query_count = "SELECT count(distinct Fecha_sistema) AS fecha_actualizacion FROM pld_catalogos_log WHERE Tipo = '".$Evento2."' ";


        // GROUP BY Registro

        $Query = "SELECT pld_catalogos_log.Fecha_sistema as fecha_actualizacion , 
        				 pld_catalogos_log.ID_Usr AS ID_Usr, pld_catalogos_log.Accion,
        				 pld_catalogos_log.sentencia 

        				 from pld_catalogos_log
                         
                          WHERE Tipo = '".$Evento2."'  GROUP BY  Fecha_sistema
                         
                          ORDER BY pld_catalogos_log.Fecha_sistema desc
                        ";
        
        $Query_act = "SELECT max(pld_catalogos_log.Fecha_sistema) as fecha_actualizacion from pld_catalogos_log WHERE Tipo = '".$Evento2."' ";




        $RESPUESTA      = $this->db->Execute($Query_count);

        $total_registros = $RESPUESTA->fields["fecha_actualizacion"];
        $total_paginas = number_format($total_registros/$muestra,2);
        $total_paginas =  round($total_paginas, 0, PHP_ROUND_HALF_UP); 
				 //echo $total_paginas;

				 //if($tot>0){
				 //$total_paginas = $total_paginas+1;	
				 //}
				//verflujo();
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
        $Query .= $limite;
       // debug($Query);
        $RESPUESTA      = $this->db->Execute($Query);  //debug($Query);


        $RESPUESTA2      = $this->db->Execute($Query_act); //debug($Query);
        $fecha_actualizacion = $RESPUESTA2->fields["fecha_actualizacion"];


        //echo $total_registros."<-->".$total_paginas."<-->".$Pagina."<-->".$limite_ini."<-->".$Query;
        //return;
        while( !$RESPUESTA->EOF ) {

            $sql ="SELECT CONCAT(Nombre,' ',AP_Paterno,' ',AP_Materno) AS Nombre FROM usuarios WHERE ID_User =  '".$RESPUESTA->fields["ID_Usr"]."' ";
            $rs2=$this->db->Execute($sql);
            $nombre = mb_strtoupper($rs2->fields["Nombre"]);


            $Valor     = $nombre;
            $Fecha   	= $RESPUESTA->fields["fecha_actualizacion"];
            $Indicador = $RESPUESTA->fields["Indicador"];
            $accion  	= $RESPUESTA->fields["Accion"];

            if($accion == "INSERT")
            	$accion2 = "AGREGÓ REGISTRO";
             if($accion == "DELETE")
            	$accion2 = "ELIMINÓ REGISTRO";
             if($accion == "UPDATE")
            	$accion2 = "ACTUALIZÓ REGISTRO";
             if($accion == "UPLOAD")
            	$accion2 = "Se cargaron ".$RESPUESTA->fields["sentencia"]." Registros";



            $htmlTbody .= '

							<tr>
								<td>'.strtoupper($Fecha).'</td>
								<td>'.strtoupper($Valor).'</td>
								<td>'.strtoupper($accion2).'</td>
							</tr>

						  ';
            $RESPUESTA->MoveNext();
        } // fin while( !$RESPUESTA->EOF ) {

        $html = '
				<h3>Fecha de última Actualización '.$fecha_actualizacion.'</h3>
				  <table align="center" width="100%" class="ui table segment">
					<thead>
						<tr>
							<th width="30%" align="center">FECHA</th>
							<th width="40%">USUARIO</th>
							<th width="30%">ACTIVIDAD</th>

						</tr>
					</thead>
					'.$htmlTbody.'
				</table>';


        $html .= "<div class='ui'>
							<i class='big step backward icon' onclick='ActualizaListasInfo(\"First\",\"".$Evento2."\")'></i>
							<i class='big backward icon' onclick='ActualizaListasInfo(\"Prev\",\"".$Evento2."\")'></i>
							<input type='text' maxlength='4' size='4' id='paginacionUni' onchange='CambiaPaginaListasInfo(this,\"".$Evento2."\")' value='".$Pagina."'>
							<i class='big forward icon' onclick='ActualizaListasInfo(\"Next\",\"".$Evento2."\")'></i>
							<i class='big step forward icon' onclick='ActualizaListasInfo(\"Last\",\"".$Evento2."\")'></i>

					    </div>

						";

        echo  $html;


    } // fin public function VistaPersonas(){


}//fin clase




?>