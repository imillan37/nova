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
ini_set('max_execution_time', 300);

class ControllerServiciosWeb
{

    public $db;

    public function __construct($db)
    {
        $this->db = $db;
    }


//********************************************************//
//---------  esta funcion actualizara alqaida  -----------//
//********************************************************//

	 public function ActualizaALQAIDA($FILENAME, $ID_USR)
     {

         error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);
         ini_set('display_errors', '1');
         ini_set(" memory_limit "," 192M ");
         ini_set("default_socket_timeout", 200);
         set_time_limit(0);
         //@Listas
		
		 //OFAC
         $this->clean_catalogo('OFAC');
        
         $SQL_INS = "INSERT INTO pld_importacion_catalogos(ID_Usr,MD5,IP_owner,Tipo )  
						VALUES('" . $ID_USR . "','CARGA INCOMPLETA','','OFAC') ";

         $this->db->Execute($SQL_INS);
         $id_importacion = $this->db->_insertid();
         $msg = '';

         if ($id_importacion > 0) {
             $uri = 'http://162.242.142.135:6082/s2credit';
             $token = 'KEcdphaL&fMxCxzPudFsy=1F>Q3OlC?h)3K5';

             $fecha_verificacion = date('Y-m-d');
             $uri = $uri . '/ofac';

             $post = array(
                 "nombre_sistema" => SISTEMA,
                 "fecha_verificacion" => $fecha_verificacion,
                 "id_bitacora_catalogo" => 0
             );
             $respuesta = $this->getCurl($uri, $post, $token);
             $consulta = $respuesta['value'];
             if ($consulta['http'] == 200) {
                 foreach ($consulta['valor_lista'] AS $key => $row) {
                     //return $key;
                     $_POST["id_registro_parent"] = $row['id_ofac_parent'];
                     $_POST["id_importacion"] = "";
                     $_POST["id_lista_negra"] = "";
                     $_POST["nombre_razon_social"] = str_replace('"','',$row['nombre_razon_social']);
                     $_POST["nombre_adicional"] = "";
                     $_POST["apellido_paterno"] = str_replace('"','',$row['apellido_paterno']);
                     $_POST["apellido_materno"] = "";
                     $_POST["nombre_completo"] = "";
                     $_POST["nombre_razon_social_soundex"] = "";
                     $_POST["nombre_adicional_soundex"] = "";
                     $_POST["apellido_paterno_soundex"] = "";
                     $_POST["apellido_materno_soundex"] = "";
                     $_POST["nombre_completo_soundex"] = "";
                     $_POST["md5"] = "";
                     $_POST["fecha_nacimiento"] = $row['fecha_nacimiento'];
                     $_POST["rfc"] = "";
                     $_POST["curp"] = "";
                     $_POST["lugar_nacimiento"] = $row['nacionalidad'];
                     $_POST["direccion"] = "";
                     $_POST["observaciones"] = $row['observaciones'];
                     $_POST["DATAID"] = $row['id_ofac_parent'];
                     $_POST["UN_LIST_TYPE"] = 'OFAC';

                     $id_bitacora_rest = $row['id_bitacora_catalogo'];

                     if (!empty($_POST["nombre_razon_social"])) {

                         $SQL_INS_DTL = "INSERT INTO pld_importacion_catalogos_dtl(ID_Importacion,Nombre_I,Nombre_II,Tipo,DATAID,UN_LIST_TYPE)  
											 VALUES('" . $id_importacion . "',UCASE(\"" . $_POST["nombre_razon_social"] . "\"),UCASE(\"" . $_POST["apellido_paterno"] . "\"),'Importacion','" . $_POST["DATAID"] . "','" . $_POST["UN_LIST_TYPE"] . "') ";
                         $this->db->Execute($SQL_INS_DTL);
                         //return $SQL_INS_DTL ;
                         $COUNT_INS++;

                     } //@end


                 }

                 if ($COUNT_INS > 0) {
                     //$md5_file = md5_file($uri);
                     $md5_file = md5($uri);

                     $Query = "UPDATE
								   pld_importacion_catalogos    
							 SET   MD5             = '" . $md5_file . "' 
						  WHERE		ID_Importacion  = '" . $id_importacion . "' ";
                     $this->db->Execute($Query);
                 }

                 $msg = "ACTUALIZADO";
             }else{
                 $msg = "ERROR";
             }

         }else{
             $msg = "ERROR";
         }


		// ONU
         $COUNT_INS = 0;
         $id_importacion = 0;
         $this->clean_catalogo('ALQAIDA');

         $SQL_INS = "INSERT INTO pld_importacion_catalogos(ID_Usr,MD5,IP_owner,Tipo )  
						VALUES('" . $ID_USR . "','CARGA INCOMPLETA','','ALQAIDA') ";
         $this->db->Execute($SQL_INS);
         $id_importacion = $this->db->_insertid();

         if ($id_importacion > 0) {
             $uri = 'http://162.242.142.135:6082/s2credit';
             $token = 'KEcdphaL&fMxCxzPudFsy=1F>Q3OlC?h)3K5';

             $fecha_verificacion = date('Y-m-d');
             $uri = $uri . '/onu';

             $post = array(
                 "nombre_sistema" => SISTEMA,
                 "fecha_verificacion" => $fecha_verificacion,
                 "id_bitacora_catalogo" => 0
             );
             $respuesta = $this->getCurl($uri, $post, $token);
             $consulta = $respuesta['value'];
             if ($consulta['http'] == 200) {
                 foreach ($consulta['valor_lista'] AS $key => $row) {
                     $_POST["id_registro_parent"] = $row['id_onu_parent'];
                     $_POST["id_importacion"] = "";
                     $_POST["id_lista_negra"] = "";
                     $_POST["nombre_razon_social"] = str_replace('"','',$row['nombre_razon_social']);
                     $_POST["nombre_adicional"] = "";
                     $_POST["apellido_paterno"] = str_replace('"','',$row['apellido_paterno']);
                     $_POST["apellido_materno"] = "";
                     $_POST["nombre_completo"] = "";
                     $_POST["nombre_razon_social_soundex"] = "";
                     $_POST["nombre_adicional_soundex"] = "";
                     $_POST["apellido_paterno_soundex"] = "";
                     $_POST["apellido_materno_soundex"] = "";
                     $_POST["nombre_completo_soundex"] = "";
                     $_POST["md5"] = "";
                     $_POST["fecha_nacimiento"] = $row['fecha_nacimiento'];
                     $_POST["rfc"] = "";
                     $_POST["curp"] = "";
                     $_POST["lugar_nacimiento"] = $row['nacionalidad'];
                     $_POST["direccion"] = "";
                     $_POST["observaciones"] = $row['observaciones'];
                     $_POST["DATAID"] = $row['id_onu_parent'];
                     $_POST["UN_LIST_TYPE"] = $row['lista_onu'];

                     $id_bitacora_rest = $row['id_bitacora_catalogo'];

                     if (!empty($_POST["nombre_razon_social"])) {

                         $SQL_INS_DTL = "INSERT INTO pld_importacion_catalogos_dtl(ID_Importacion,Nombre_I,Nombre_II,Tipo,DATAID,UN_LIST_TYPE)  
											 VALUES('" . $id_importacion . "',UCASE(\"" . $_POST["nombre_razon_social"] . "\"),UCASE(\"" . $_POST["apellido_paterno"] . "\"),'Importacion','" . $_POST["DATAID"] . "','" . $_POST["UN_LIST_TYPE"] . "') ";
                         $this->db->Execute($SQL_INS_DTL);
                         $COUNT_INS++;

                     } //@end


                 }

                 if ($COUNT_INS > 0) {
                     //$md5_file = md5_file($uri);
                     $md5_file = md5($uri);

                     $Query = "UPDATE
								   pld_importacion_catalogos    
							 SET   MD5             = '" . $md5_file . "' 
						  WHERE		ID_Importacion  = '" . $id_importacion . "' ";
                     $this->db->Execute($Query);
                 }

                 $msg = "ACTUALIZADO";
             }else{
                 $msg = "ERROR";
             }

         }else{
             $msg = "ERROR";
         }

         return $msg;
     }

//********************************************************//
//---------  esta funcion actualizara OFAC  -----------//
//********************************************************//
    public function ActualizaOFAC($FILENAME, $ID_USR)
    {

        $this->clean_catalogo('OFAC');

        $SQL_INS = "INSERT INTO pld_importacion_catalogos(ID_Usr,MD5,IP_owner,Tipo )  
							VALUES('" . $ID_USR . "','CARGA INCOMPLETA','','OFAC') ";

        $this->db->Execute($SQL_INS);
        $id_importacion = $this->db->_insertid();

        if ($id_importacion > 0) {
            $xml = simplexml_load_file($FILENAME);

            $COUNT_INS = 0;
            foreach ($xml->sdnEntry as $value) {
                //echo	$value->FIRST_NAME."<BR />";
                if ((!empty($value->firstName)) && ($value->sdnType == 'Individual')) {
                    $Nombre_I = str_replace("'", " ", $value->firstName);
                    $Nombre_II = str_replace("'", " ", $value->lastName);

                    if (!empty($Nombre_I)) {

                        $SQL_INS_DTL = "INSERT INTO pld_importacion_catalogos_dtl(ID_Importacion,Nombre_I,Nombre_II,Tipo)  
										VALUES('" . $id_importacion . "',UCASE('" . $Nombre_I . "'),UCASE('" . $Nombre_II . "'),'Importaci�n') ";

                        $this->db->Execute($SQL_INS_DTL);

                    } //@end if

                    $COUNT_INS++;
                }
            }


            if ($COUNT_INS > 0) {
                $md5_file = md5_file($FILENAME);

                $Query = "UPDATE
								   pld_importacion_catalogos    
							 SET   MD5             = '" . $md5_file . "' 
						  WHERE		ID_Importacion  = '" . $id_importacion . "' ";
                $this->db->Execute($Query);
            }

            unlink($filename);
            return "ACTUALIZADO";
        } else {
            return "ERROR";
        }

    }


    public function ActualizaTerrorista($ID_USR)
    {
        

        $filename =  'http://162.242.142.135:6082/s2credit';
        $Respuesta = $this->ActualizaALQAIDA($filename, $ID_USR);


        //$Respuesta = "ACTUALIZADO";

        return $Respuesta;


    } // fin ActualizaTerrorista

    //********************
    //********limpia CAT TERRORISTAS *******************************//
    public function clean_catalogo($TIPO_CATALOG)
    {


        $SQL_VERIF = "SELECT 
								ID_Importacion	AS ID
						FROM pld_importacion_catalogos
							WHERE MD5 != 'HISTORICO'
							AND Tipo = '" . $TIPO_CATALOG . "'";
        $rs_id = $this->db->Execute($SQL_VERIF);
        //echo $SQL_VERIF;

        while (!$rs_id->EOF) {
            $SQL_DTL = "DELETE FROM  pld_importacion_catalogos_dtl
									WHERE ID_Importacion = '" . $rs_id->fields["ID"] . "'";

            $this->db->Execute($SQL_DTL);

            $rs_id->MoveNext();
        }

        $SQL_DELETE = "UPDATE pld_importacion_catalogos SET MD5='HISTORICO'
									WHERE Tipo = '" . $TIPO_CATALOG . "' ";
        $this->db->Execute($SQL_DELETE);

    }

    //********Muestra CAT TERRORISTAS *******************************//
    public function VistaTerroristas($Pagina, $Evento, $Filtro)
    {

        $muestra = 12;
        $condicion = "";
        if ($Filtro != "") {
            $condicion = "AND CONCAT(pld_importacion_catalogos_dtl.Nombre_I,' ',pld_importacion_catalogos_dtl.Nombre_II) LIKE '%" . $Filtro . "%'";
        }

        $Query_count = "SELECT count(*) as Total				
									FROM pld_importacion_catalogos_dtl
									INNER JOIN pld_importacion_catalogos on pld_importacion_catalogos.ID_Importacion = pld_importacion_catalogos_dtl.ID_Importacion
									WHERE pld_importacion_catalogos.MD5 != 'HISTORICO'
									$condicion";

        $Query_act = "Select max(pld_importacion_catalogos.Registro) as fecha_actualizacion from pld_importacion_catalogos";

        $RESPUESTA2 = $this->db->Execute($Query_act); //debug($Query);	
        $fecha_actualizacion = $RESPUESTA2->fields["fecha_actualizacion"];


        $RESPUESTA = $this->db->Execute($Query_count);
        $total_registros = $RESPUESTA->fields["Total"];
        $total_paginas = $total_registros / $muestra;
        $total_paginas = ceil($total_paginas);

        if ($Pagina > $total_paginas) {
            $Pagina = 1;
        }

        if (!$Pagina or $Pagina == 0) {
            $limite_ini = 0;
            $Pagina = 1;
        } else {
            if ($Evento == "Prev") {
                $Pagina--;
                if ($Pagina == 0) {
                    $Pagina = 1;
                }
            } elseif ($Evento == "Next") {
                $Pagina++;
                if ($Pagina > $total_paginas) {
                    $Pagina--;
                }
            } elseif ($Evento == "First") {
                $Pagina = 1;
            } elseif ($Evento == "Last") {
                $Pagina = $total_paginas;
            } else {
                $limite_ini = ($Pagina) * $muestra;
            }

            $limite_ini = ($Pagina - 1) * $muestra;

        }

        $limite = "LIMIT " . $limite_ini . ", " . $muestra;


        $Query = "SELECT 
								pld_importacion_catalogos_dtl.Nombre_I as Nombre,
								pld_importacion_catalogos_dtl.Nombre_II as Apellido,
								pld_importacion_catalogos.Tipo as Tipo,
								date(pld_importacion_catalogos.Registro) as Fecha,
								pld_importacion_catalogos_dtl.UN_LIST_TYPE,
                                pld_alias_listas_negras.alias
								
								FROM pld_importacion_catalogos_dtl
								INNER JOIN pld_importacion_catalogos on pld_importacion_catalogos.ID_Importacion = pld_importacion_catalogos_dtl.ID_Importacion
								LEFT JOIN pld_alias_listas_negras ON pld_alias_listas_negras.UN_LIST_TYPE = pld_importacion_catalogos_dtl.UN_LIST_TYPE
								WHERE pld_importacion_catalogos.MD5 != 'HISTORICO' AND pld_importacion_catalogos_dtl.Nombre_I != ''
								$condicion
								order by pld_importacion_catalogos_dtl.Nombre_I
								$limite
								";

        $RESPUESTA = $this->db->Execute($Query);  //debug($Query);


        //echo $total_registros."<-->".$total_paginas."<-->".$Pagina."<-->".$limite_ini."<-->".$Query;
        //return;
        while (!$RESPUESTA->EOF) {

            $Nombre = $RESPUESTA->fields["Nombre"];
            $Apellido = $RESPUESTA->fields["Apellido"];
            $Tipo = $RESPUESTA->fields["alias"];
            $Fecha = $RESPUESTA->fields["Fecha"];

            $htmlTbody .= '
			  
							<tr>
								<td>' . strtoupper($Nombre) . '</td>
								<td>' . strtoupper($Apellido) . '</td>
								<td>' . strtoupper(ffecha($Fecha)) . '</td>
								<td>' . strtoupper($Tipo) . '</td>					
							</tr>
						  
						  ';
            $RESPUESTA->MoveNext();
        } // fin while( !$RESPUESTA->EOF ) {		

        $html = ' 
	  				<h3>Fecha de �ltima Actualizaci�n ' . $fecha_actualizacion . '</h3>
				  <table align="center" width="110%" class="ui table segment">
					<thead>
						<tr>
							<th width="30%">NOMBRE(S)</th>
							<th width="25%">APELLIDO(S)</th>
							<th width="15%">FECHA DE ALTA</th>
							<th width="30%">TIPO</th>

						</tr>
					</thead>
					' . $htmlTbody . '
				</table>';

        $html .= "<div class='ui'>
							<i class='big step backward icon' onclick='ActualizaListado(\"First\")'></i>
							<i class='big backward icon' onclick='ActualizaListado(\"Prev\")'></i>
							<div class='ui small icon input'>
							    <input size='4' type='text' id='paginacion' onchange='CambiaPaginaListado(this)' value='" . $Pagina . "'>
							</div>
							<i class='big forward icon' onclick='ActualizaListado(\"Next\")'></i>
							<i class='big step forward icon' onclick='ActualizaListado(\"Last\")'></i>
							
					    </div>
						
						";

        echo $html;


    } // fin public function VistaPersonas(){


    //********ACTUALIZA CAT unidades *******************************//
    public function ActualizaUnidades($tipo, $ID_USR)
    {
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);
        ini_set('display_errors', '1');
        ini_set(" memory_limit "," 192M ");
        ini_set("default_socket_timeout", 200);
        set_time_limit(0);

        $carga_exitoso = false;
        try {

            $uri = 'http://162.242.142.135:6082/s2credit';
            $token = 'KEcdphaL&fMxCxzPudFsy=1F>Q3OlC?h)3K5';

            if ($tipo == "USD") {
                $uri = $uri . '/dolares';
                $Tabla = "pld_importacion_tipocambio";
                $id_link = "2";
                $Query = "SELECT 
								if( max(pld_importacion_tipocambio.Dia) is null , '2010-01-01', max(pld_importacion_tipocambio.Dia) ) as Fecha_Min,
								CURDATE() as Fecha_Max
							FROM
							pld_importacion_tipocambio";

                $RESPUESTA = $this->db->Execute($Query);  //debug($Query);

                $fecha_inicio_verificacion = $RESPUESTA->fields["Fecha_Min"];
                $fecha_termino_verificacion = $RESPUESTA->fields["Fecha_Max"];

                $post = array(
                    "nombre_sistema" => SISTEMA,
                    "fecha_inicio_verificacion" => $fecha_inicio_verificacion,
                    "fecha_termino_verificacion" => $fecha_termino_verificacion
                );
                $respuesta = $this->getCurl($uri, $post, $token);
            } elseif ($tipo == "UDIS") {
                $uri = $uri . '/udis';
                $Tabla = "pld_importacion_indicadores";
                $id_link = "1";
                $Query = "SELECT 
								if( max(pld_importacion_indicadores.Dia) is null , '2010-01-01', max(pld_importacion_indicadores.Dia) ) as Fecha_Min,
								CURDATE() as Fecha_Max
							FROM
							pld_importacion_indicadores";

                $RESPUESTA = $this->db->Execute($Query);  //debug($Query);
                $fecha_inicio_verificacion = $RESPUESTA->fields["Fecha_Min"];
                $fecha_termino_verificacion = $RESPUESTA->fields["Fecha_Max"];

                $post = array(
                    "nombre_sistema" => SISTEMA,
                    "fecha_inicio_verificacion" => $fecha_inicio_verificacion,
                    "fecha_termino_verificacion" => $fecha_termino_verificacion
                );

                $respuesta = $this->getCurl($uri, $post, $token);
            }

            $msg = "Error";
            if (!empty($respuesta)) {

                if ($respuesta['resp']) {

                    //obtener datos del rest
                    $consulta = $respuesta['value'];

                    if ($consulta['http'] == 200) {

                        if ($tipo == "USD") {
                            $datos = $consulta['valor_dolar'];
                            $campo = 'dolar_dof';
                        } elseif ($tipo == "UDIS") {
                            $datos = $consulta['valor_udis'];
                            $campo = 'valor_udi';
                        }

                        foreach ($datos AS $key => $row) {
                            $fecha = $row['fecha_valor'];
                            $valor_resp = $row[$campo];

                            if (is_numeric($valor_resp)) {
                                $ID_IMPORTA = $this->get_check_registro($fecha, $Tabla);
                                if (empty($ID_IMPORTA)) {
                                    $this->set_insert_new($valor_resp, $fecha, $id_link, $Tabla, $ID_USR);
                                    $msg = "Listo";
                                } else {
                                    $this->set_update($ID_IMPORTA, $valor_resp, $fecha, $id_link, $Tabla, $ID_USR);
                                    $msg = "Listo";
                                }
                            }


                        }

                    }
                }

            }

            return $msg;

        } catch (Exception $exception) {
            $html = '<strong>�Error</strong> No se hay comunicaci�n con el servidor. XXXX!' . $exception->getMessage();
            return $html;
        } //@try

    }


    //********************************************************//
    //---------	 Verifica si ya hay registro   	-----------//
    //********************************************************//

    public function get_check_registro($FECHA, $TABLA_DEST)
    {
        $TABL_DTL = ($TABLA_DEST == 'pld_importacion_indicadores') ? ("pld_importacion_indicadores_dtl") : ("pld_importacion_tipocambio_dtl");

        $SQL_CONS = "SELECT 
						" . $TABLA_DEST . ".ID_Importacion	AS ID
						FROM 
							" . $TABLA_DEST . "
						INNER JOIN " . $TABL_DTL . " ON
						" . $TABLA_DEST . ".ID_Importacion = 
						" . $TABL_DTL . ".ID_Importacion 
						WHERE DATE(Dia) BETWEEN '" . $FECHA . "' AND '" . $FECHA . "'";

        $rs_cons = $this->db->Execute($SQL_CONS);

        return $rs_cons->fields["ID"];


    }


    //********************************************************//
    //---------	 Inserta registros   	-----------//
    //********************************************************//

    public function set_insert_new($VALOR, $FECHA, $ID_LINK, $TABLA_DEST, $ID_USR)
    {
        $TABL_DTL = ($TABLA_DEST == 'pld_importacion_indicadores') ? ("pld_importacion_indicadores_dtl") : ("pld_importacion_tipocambio_dtl");

        $CMP_TIPO = ($TABLA_DEST == 'pld_importacion_indicadores') ? ("Indicador") : ("Divisa");
        $CMP_VAL = ($TABLA_DEST == 'pld_importacion_indicadores') ? ("UDIS") : ("USD");

        $CMP_DTL = ($TABLA_DEST == 'pld_importacion_indicadores') ? ("Valor") : ("Valor_venta");

        //$IP_CLIENT=getRealIP();
        ################################


        $SQL_INS = "INSERT INTO " . $TABLA_DEST . " (ID_Usr,MD5,IP_owner,Tipo,Dia,ID_link," . $CMP_TIPO . ")  
						VALUES('" . $ID_USR . "','CARGA AUTOMATICA','" . $this->getRealIP() . "','RSS','" . $FECHA . "','" . $ID_LINK . "','" . $CMP_VAL . "') ";

        $this->db->Execute($SQL_INS);
        $id_importacion = $this->db->_insertid();

        $SQL_INS_DTL = "INSERT INTO " . $TABL_DTL . " (ID_Importacion,Tipo," . $CMP_DTL . ")  
		  			    VALUES('" . $id_importacion . "','Importaci�n','" . $VALOR . "') ";
        $this->db->Execute($SQL_INS_DTL);


    }


    //********************************************************//
    //---------	 update registros   	-----------//
    //********************************************************//

    public function set_update($ID_IMPORTA, $VALOR, $FECHA, $ID_LINK, $TABLA_DEST, $ID_USR)
    {
        ##############################
        $TABL_DTL = ($TABLA_DEST == 'pld_importacion_indicadores') ? ("pld_importacion_indicadores_dtl") : ("pld_importacion_tipocambio_dtl");

        $CMP_DTL = ($TABLA_DEST == 'pld_importacion_indicadores') ? ("Valor") : ("Valor_venta");

        //$IP_CLIENT=getRealIP();
        ################################

        $SQL_UPDATE = "UPDATE " . $TABLA_DEST . "
							SET ID_link = '" . $ID_LINK . "'
						 WHERE  ID_Importacion = '" . $ID_IMPORTA . "' ";
        $this->db->Execute($SQL_UPDATE);

        $SQL_UPDATE = "UPDATE " . $TABL_DTL . "
							SET Evento			= 'Edici�n',
							ID_Usr_edit 		= '" . $ID_USR . "',
							IP_owner_edit	 	= '" . $this->getRealIP() . "',
							Registro_edit		= NOW(),
							" . $CMP_DTL . "		= '" . $VALOR . "'
						 WHERE  ID_Importacion = '" . $ID_IMPORTA . "' ";
        $this->db->Execute($SQL_UPDATE);

        return $SQL_UPDATE;
    }


//********Muestra CAT TERRORISTAS *******************************//
    public function VistaUnidades($Tipo, $Pagina, $Evento, $Fecha_inicial, $Fecha_final)
    {

        $muestra = 15;
        $condicion = "";


        if ($Tipo == "USD") {
            if ($Fecha_inicial != "" and $Fecha_final != "") {
                $condicion = "AND pld_importacion_tipocambio.Dia between '" . $Fecha_inicial . "' and '" . $Fecha_final . "' ";
            }


            $Query_count = "SELECT count(*) as Total				
									FROM
						
							pld_importacion_tipocambio_dtl
							INNER JOIN pld_importacion_tipocambio ON pld_importacion_tipocambio.ID_Importacion = pld_importacion_tipocambio_dtl.ID_Importacion
							WHERE pld_importacion_tipocambio.Divisa = 'USD'
							$condicion";


            $Query = "SELECT  
							pld_importacion_tipocambio_dtl.Valor_venta as Valor,
							pld_importacion_tipocambio.Dia as Fecha,
							#pld_importacion_tipocambio.Divisa as Indicador
							'PESOS' as Indicador
						FROM
						
							pld_importacion_tipocambio_dtl
							INNER JOIN pld_importacion_tipocambio ON pld_importacion_tipocambio.ID_Importacion = pld_importacion_tipocambio_dtl.ID_Importacion
							WHERE pld_importacion_tipocambio.Divisa = 'USD'
							$condicion
							ORDER BY 2 desc 							
							";
            $Query_act = "Select max(pld_importacion_tipocambio.Registro) as fecha_actualizacion from pld_importacion_tipocambio";


        } else {

            if ($Fecha_inicial != "" and $Fecha_final != "") {
                $condicion = "AND pld_importacion_indicadores.Dia between '" . $Fecha_inicial . "' and '" . $Fecha_final . "' ";
            }


            $Query_count = "SELECT count(*) as Total				
									FROM
						
							pld_importacion_indicadores_dtl
							INNER JOIN pld_importacion_indicadores ON pld_importacion_indicadores.ID_Importacion = pld_importacion_indicadores_dtl.ID_Importacion
							WHERE pld_importacion_indicadores.Indicador = 'UDIS'
							$condicion";


            $Query = "SELECT  
							pld_importacion_indicadores_dtl.Valor as Valor,
							pld_importacion_indicadores.Dia as Fecha,
							pld_importacion_indicadores.Indicador as Indicador
						FROM
						
							pld_importacion_indicadores_dtl
							INNER JOIN pld_importacion_indicadores ON pld_importacion_indicadores.ID_Importacion = pld_importacion_indicadores_dtl.ID_Importacion
							WHERE pld_importacion_indicadores.Indicador = 'UDIS'
							$condicion
							ORDER BY 2 desc 
							";

            $Query_act = "Select max(pld_importacion_indicadores.Registro) as fecha_actualizacion from pld_importacion_indicadores";

        }

        $RESPUESTA = $this->db->Execute($Query_count);

        $total_registros = $RESPUESTA->fields["Total"];
        $total_paginas = $total_registros / $muestra;
        $total_paginas = ceil($total_paginas);

        if ($Pagina > $total_paginas) {
            $Pagina = 1;
        }

        if (!$Pagina or $Pagina == 0) {
            $limite_ini = 0;
            $Pagina = 1;
        } else {
            if ($Evento == "Prev") {
                $Pagina--;
                if ($Pagina == 0) {
                    $Pagina = 1;
                }
            } elseif ($Evento == "Next") {
                $Pagina++;
                if ($Pagina > $total_paginas) {
                    $Pagina--;
                }
            } elseif ($Evento == "First") {
                $Pagina = 1;
            } elseif ($Evento == "Last") {
                $Pagina = $total_paginas;
            } else {
                $limite_ini = ($Pagina) * $muestra;
            }

            $limite_ini = ($Pagina - 1) * $muestra;

        }

        $limite = "LIMIT " . $limite_ini . ", " . $muestra;

        $Query .= $limite;

        $RESPUESTA = $this->db->Execute($Query);  //debug($Query);


        $RESPUESTA2 = $this->db->Execute($Query_act); //debug($Query);	
        $fecha_actualizacion = $RESPUESTA2->fields["fecha_actualizacion"];


        //echo $total_registros."<-->".$total_paginas."<-->".$Pagina."<-->".$limite_ini."<-->".$Query;
        //return;
        while (!$RESPUESTA->EOF) {

            $Valor = $RESPUESTA->fields["Valor"];
            $Fecha = $RESPUESTA->fields["Fecha"];
            $Indicador = $RESPUESTA->fields["Indicador"];


            $htmlTbody .= '
			  
							<tr>
								<td>' . strtoupper($Fecha) . '</td>
								<td>' . strtoupper($Valor) . '</td>
								<td>' . strtoupper($Indicador) . '</td>
							</tr>
						  
						  ';
            $RESPUESTA->MoveNext();
        } // fin while( !$RESPUESTA->EOF ) {		

        $html = ' 
				<h3>Fecha de �ltima Actualizaci�n ' . $fecha_actualizacion . '</h3>
				  <table align="center" width="100%" class="ui table segment">
					<thead>
						<tr>
							<th width="40%">FECHA</th>
							<th width="40%">VALOR</th>
							<th width="40%">TIPO</th>

						</tr>
					</thead>
					' . $htmlTbody . '
				</table>';


        $html .= "<div class='ui'>
							<i class='big step backward icon' onclick='ActualizaListadoUnidades(\"First\")'></i>
							<i class='big backward icon' onclick='ActualizaListadoUnidades(\"Prev\")'></i>
							<div class='ui small icon input'>
							    <input type='text' maxlength='4' size='4' id='paginacionUni' onchange='CambiaPaginaListadoUnidades(this)' value='" . $Pagina . "'>
							</div>

							<i class='big forward icon' onclick='ActualizaListadoUnidades(\"Next\")'></i>
							<i class='big step forward icon' onclick='ActualizaListadoUnidades(\"Last\")'></i>
							
					    </div>
						
						";

        echo $html;


    } // fin public function VistaPersonas(){


    public function getRealIP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
            return $_SERVER['HTTP_CLIENT_IP'];

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            return $_SERVER['HTTP_X_FORWARDED_FOR'];

        return $_SERVER['REMOTE_ADDR'];
    }

//************** RLJ ************************//

//********************************************************//

    public function getCurl($url, $post, $token = '')
    {
        try {
            $respuesta = array(
                "resp" => true,
                "value" => "hola"
            );

            $post = json_encode($post);
            $header = array('Content-Type: application/json', 'Content-Length: ' . strlen($post));

            if (!empty($token)) {
                $header[] = 'Token: ' . $token;
            }

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

            $result = curl_exec($ch);
            //return  json_decode($result, true);
            //return $result;
            if ($result === false) {
                //return  json_decode($result, true);
                $respuesta = array(
                    "resp" => false,
                    "value" => $result
                );
            } else {
                //return  json_decode($result, true);
                $respuesta = array(
                    "resp" => true,
                    "value" => json_decode($result, true)
                    //"value" => $result
                    //"value" => json_decode($result)
                );
            }

            curl_close($ch);

        } catch (Exception $ex) {
            $respuesta = array(
                "resp" => false,
                "value" => $ex->getMessage()
            );
        }
        return $respuesta;
    }


}



?>
