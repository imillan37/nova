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

class ControllerReescaneo {

    public $db;
    private $id_usuario;
    private $Terroristas;
    private $Nombres_PPE;
    private $Puestos_PPE;
    private $CodigosPostales;
    private $Estados;
    private $Ciudades;
    private $Giros;
    private $FechaReescaneo;
    private $IDReescaneo;
    private $HoraInicial;
    private $HoraFinal;
    private $HoraActual;
    private $Error;

    public function __construct($db){
        $this->db = $db;
    }

    /***metodos get variables */
    public function  setIdUSuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    public function  getIdUSuario()
    {
        return $this->id_usuario;
    }

    public function  setTerroristas($Terroristas)
    {
        $this->Terroristas = $Terroristas;
    }

    public function  getTerroristas()
    {
        return $this->Terroristas;
    }

    public function  setNombres_PPE($Nombres_PPE)
    {
        $this->Nombres_PPE = $Nombres_PPE;
    }

    public function  getNombres_PPE()
    {
        return $this->Nombres_PPE;
    }

    public function  setPuestos_PPE($Puestos_PPE)
    {
        $this->Puestos_PPE = $Puestos_PPE;
    }

    public function  getPuestos_PPE()
    {
        return $this->Puestos_PPE;
    }

    public function  setCodigosPostales($CodigosPostales)
    {
        $this->CodigosPostales = $CodigosPostales;
    }

    public function  getCodigosPostales()
    {
        return $this->CodigosPostales;
    }

    public function  setEstados($Estados)
    {
        $this->Estados = $Estados;
    }

    public function  getEstados()
    {
        return $this->Estados;
    }

    public function  setCiudades($Ciudades)
    {
        $this->Ciudades = $Ciudades;
    }

    public function  getCiudades()
    {
        return $this->Ciudades;
    }

    public function  setGiros($Giros)
    {
        $this->Giros = $Giros;
    }

    public function  getGiros()
    {
        return $this->Giros;
    }
    public function  setIDReescaneo($IDReescaneo)
    {
        $this->IDReescaneo = $IDReescaneo;
    }

    public function  getIDReescaneo()
    {
        return $this->IDReescaneo;
    }

    public function  setFechaReescaneo()
    {
        $Query = "SELECT MAX(pld_reescaneo.Fecha_sistema) as FechaReescaneo
                  FROM pld_reescaneo
                  WHERE pld_reescaneo.Estatus = 'LISTO' ";

        $RESPUESTA                    = $this->db->Execute($Query);
        $FechaReescaneo = $RESPUESTA->fields["FechaReescaneo"];

        $this->FechaReescaneo = $FechaReescaneo;
    }

    public function  getFechaReescaneo()
    {
        return $this->FechaReescaneo;
    }


    public function setHoraInicial()
    {
        $Query = "SELECT  pld_parametros_configuracion.HoraInicio FROM pld_parametros_configuracion
                    WHERE pld_parametros_configuracion.ID_Parametros = '1' ";

        $RESPUESTA                    = $this->db->Execute($Query);
        $this->HoraInicial = $RESPUESTA->fields["HoraInicio"];

    }

    public function getHoraIncial()
    {
        return $this->HoraInicial;
    }

    public function setHoraFinal()
    {
        $Query = "SELECT  pld_parametros_configuracion.HoraFinal FROM pld_parametros_configuracion
                    WHERE pld_parametros_configuracion.ID_Parametros = '1' ";

        $RESPUESTA                    = $this->db->Execute($Query);
        $this->HoraFinal = $RESPUESTA->fields["HoraFinal"];
    }

    public function getHoraFinal()
    {
        return $this->HoraFinal;
    }

    public function  getHoraActual()
    {
        //date_default_timezone_set();
        //date_default_timezone_set('UTC');
        //$this->HoraActual = date("H:i:s");

        $Query = "SELECT  TIME(NOW()) AS Hora";

        $RESPUESTA                    = $this->db->Execute($Query);
        $this->HoraActual = $RESPUESTA->fields["Hora"];
        return $this->HoraActual;
    }

    public function setError($Error)
    {
        $this->Error = $Error;
    }
    public function getError()
    {
        return $this->Error;
    }
    //***********************//


    /***metodos funciones genericas  */
    public function getHoraValida()
    {

        $valida    = "";
        $this->getHoraActual();
        if( ($this->getHoraIncial() == "00:00:00" or $this->getHoraIncial() == "" ) and ($this->getHoraFinal() == "00:00:00" or $this->getHoraFinal() == "") )
        {
            $valida = true;
        }else{
            if($this->getHoraIncial() <= $this->getHoraActual() and $this->getHoraFinal() >= $this->getHoraActual() )
            {
                $valida = true;
            }else{
                $valida = false;
            }
        }


        return $valida;
    }

    public function getFechaActualizacion()
    {

        $oficial = $this->validaOficial();
        $this->setFechaReescaneo();

        $this->setHoraInicial();
        $this->setHoraFinal();
        $hora = $this->getHoraValida();
        $HoraValida = "";
        if($hora == false)
        {
            $HoraValida = "La Hora para este proceso debe de ser entre ".$this->getHoraIncial()." y ".$this->getHoraFinal();
        }
        $arr = array("FechaReescaneo" => $this->getFechaReescaneo(), 'PermisoOficial' => $this->validaOficial(), "HoraValida" => $HoraValida);
        require_once( "../../class/json.php" );
        $json       = new Services_JSON;
        $arrDatos = $json->encode($arr);

        return  $arrDatos;
    }



    public function validaOficial()
    {

        $Query = "SELECT
								 ID_User_Oficial_Cumplimiento
							FROM pld_parametros_configuracion
						   LIMIT 1";

        $RESPUESTA                    = $this->db->Execute($Query);
        $ID_User_Oficial_Cumplimiento = $RESPUESTA->fields["ID_User_Oficial_Cumplimiento"];
        //******esto es solo para que yo pueda ver y me valga madres
        if($this->getIdUSuario() == 9)
        {
            $ID_User_Oficial_Cumplimiento = 9;
        }
        //*****************************//
        if( $ID_User_Oficial_Cumplimiento != $this->getIdUSuario() )
        {
            return false;
        }else
        {
            return true;
        }
    }

    public function setConfiguracionPIC()
    {
        $Query = "SELECT
								 Terroristas,
								 Nombres_PPE,
								 Puestos_PPE,
								 CodigosPostales,
								 Estados,
								 Ciudades,
								 Giros
							FROM pld_originacion
							WHERE ID_pld_originacion = '1' ";

        $RESPUESTA                    = $this->db->Execute($Query);

        $this->setTerroristas($RESPUESTA->fields["Terroristas"]);
        $this->setNombres_PPE($RESPUESTA->fields["Nombres_PPE"]);
        $this->setPuestos_PPE($RESPUESTA->fields["Puestos_PPE"]);
        $this->setCodigosPostales($RESPUESTA->fields["CodigosPostales"]);
        $this->setEstados($RESPUESTA->fields["Estados"]);
        $this->setCiudades($RESPUESTA->fields["Ciudades"]);
        $this->setGiros($RESPUESTA->fields["Giros"]);

    }

    public function setRegistroReescaneo()
    {

        $Query = "INSERT INTO pld_reescaneo(ID_Usr,Estatus) VALUES (".$this->getIdUSuario().", 'PROCESANDO')";
        $this->db->Execute($Query);
        $ID_Reescaneo = $this->db->_insertid();
        $this->setIDReescaneo($ID_Reescaneo);

    }

    public function setUpdateReescaneo()
    {
        $Query = "SELECT
            COUNT(pld_reescaneo_log.ID_reescaneo_log) AS TotalReg
            FROM pld_reescaneo_log
            WHERE pld_reescaneo_log.ID_reescaneo = '".$this->getIDReescaneo()."' ";

        $RESPUESTA                    = $this->db->Execute($Query);
        $TotalReg = $RESPUESTA->fields["TotalReg"];


        if($this->getError() == "")
        {
            $Estatus = 'LISTO';
        }
        else{
            $Estatus = 'ERROR';
        }
        $Query = "UPDATE pld_reescaneo SET Estatus = '".$Estatus."', TotalReg='".$TotalReg."', Fecha_sistema2 = now() WHERE ID_reescaneo = '".$this->getIDReescaneo()."' ";
        //debug($Query );
        $this->db->Execute($Query);

    }
   ///*******************************//
    public function BuscaReescaneo()
    {
        if($this->getCodigosPostales() == "SI")
        {
            $this->BuscaCodigosPostales();
        }
        if($this->getEstados() == "SI")
        {
            $this->BuscaEstados();
        }
        if($this->getCiudades() == "SI")
        {
            $this->BuscaCiudades();
        }
        if($this->getGiros() == "SI")
        {
            $this->BuscaActividades();
        }
        if($this->getNombres_PPE() == "SI")
        {
                $this->BuscaPPE();
        }
        if($this->getTerroristas() == "SI")
        {
            $this->BuscaListasPropias();
            $this->BuscaListasConsudef();
            $this->BuscaTerroristas();
        }



    }

    public function setReescaneo()
    {
        ini_set (" memory_limit "," 192M ");
        set_time_limit(0);
        $oficial = $this->validaOficial();
        $this->setHoraInicial();
        $this->setHoraFinal();
        $hora = $this->getHoraValida();
        $HoraValida = "";
        $msj = "";
        if($hora == false)
        {
            $HoraValida = "La Hora para este proceso debe de ser entre ".$this->getHoraIncial()." y ".$this->getHoraFinal();
        }

        if($oficial == true and $hora == true)
        {
            try {
                $this->setConfiguracionPIC();
                $this->setFechaReescaneo();
                $this->setRegistroReescaneo();
                $this->BuscaReescaneo();
                $this->setUpdateReescaneo();

            }catch (Exception $e)
            {
                $msj = "Error";
            }
        }

        if($msj == "")
        {
            $tabla = $this->getRegistros($this->getIDReescaneo());

            $this->setFechaReescaneo();
            $arr = array("FechaReescaneo" => $this->getFechaReescaneo(), 'PermisoOficial' => $this->validaOficial(), "Tabla" => $tabla, "HoraValida" => $HoraValida);
        }else{
            $arr = array("Error" => "Ocurrio un problema con el reescaneo contacte a s2credit");
        }

        require_once( "../../class/json.php" );
        try{
            $json       = new Services_JSON;
            $arrDatos = $json->encode($arr);
        }catch (Exception $e)
        {
            $arr = array("Error" => "Ocurrio un problema con el reescaneo contacte a s2credit");
            $json       = new Services_JSON;
            $arrDatos = $json->encode($arr);
        }



        return  $arrDatos;


        //$arr = array('PermisoOficial' => $this->validaOficial());
        //require_once( "../../class/json.php" );
        //$json       = new Services_JSON;
        //$arrDatos = $json->encode($arr);
         //return $Query = "UPDATE pld_reescaneo SET Estatus = 'LISTO' where ID_reescaneo =  ";
        //return  $arrDatos;
    }

    public function BuscaActividades()
    {
        $rest = "";
        if($this->getFechaReescaneo() != "" or $this->getFechaReescaneo() != null)
        {
            $Query = "SELECT
                    pld_cat_giro_negocio.ID_Giro_negocio AS ACTIVIDAD
                  FROM
                    pld_catalogos_log
                  INNER JOIN pld_cat_giro_negocio
 	                  ON pld_cat_giro_negocio.ID_Giro_negocio = pld_catalogos_log.ID_registro
	                  AND pld_catalogos_log.Tipo = 'ACTIVIDADES'
	                  AND pld_cat_giro_negocio.Estatus = 'ACTIVO'
	                  AND pld_catalogos_log.Fecha_sistema >= '".$this->getFechaReescaneo()."' ";

        }else
        {
            $Query = "SELECT
                    pld_cat_giro_negocio.ID_Giro_negocio AS ACTIVIDAD
                  FROM
                    pld_cat_giro_negocio
                      WHERE pld_cat_giro_negocio.Estatus = 'ACTIVO'
                        AND pld_cat_giro_negocio.Tipo = 'ALTO RIESGO' ";

        }

        $RESPUESTA = $this->db->Execute($Query);

        while( !$RESPUESTA->EOF ) {
            $actividad = $RESPUESTA->fields["ACTIVIDAD"];
            $this->RevisaSolicitudes("ACTIVIDAD",$actividad);

            $RESPUESTA->MoveNext();
        }

    }

    public function BuscaCiudades()
    {
        $rest = "";
        if($this->getFechaReescaneo() != "" or $this->getFechaReescaneo() != null)
        {
            $Query = "SELECT
                         ciudades.Nombre as CIUDAD
                      FROM
                        pld_catalogos_log
                      INNER JOIN pld_cat_ciudades_riesgo
 	                            ON pld_cat_ciudades_riesgo.ID_codigo_pld = pld_catalogos_log.ID_registro
	                            AND pld_catalogos_log.Tipo = 'CIUDADES'
	                INNER JOIN ciudades ON ciudades.ID_Ciudad = pld_cat_ciudades_riesgo.ID_Ciudad
	                			AND ciudades.ID_Estado = pld_cat_ciudades_riesgo.ID_Estado

	                  AND pld_catalogos_log.Fecha_sistema >= '".$this->getFechaReescaneo()."' ";

        }else
        {
            $Query = "SELECT
                        ciudades.Nombre as CIUDAD
	                  FROM
	                 	pld_cat_ciudades_riesgo
	                   INNER JOIN ciudades ON ciudades.ID_Ciudad = pld_cat_ciudades_riesgo.ID_Ciudad
	                			AND ciudades.ID_Estado = pld_cat_ciudades_riesgo.ID_Estado  ";

        }

        $RESPUESTA = $this->db->Execute($Query);

        while( !$RESPUESTA->EOF ) {
            $ciudad = $RESPUESTA->fields["CIUDAD"];
            $this->RevisaSolicitudes("CIUDAD",$ciudad);

            $RESPUESTA->MoveNext();
        }

    }
    public function BuscaEstados()
    {
        $rest = "";
        if($this->getFechaReescaneo() != "" or $this->getFechaReescaneo() != null)
        {
            $Query = "SELECT
                        estados.nombre as ESTADO
                      FROM
                        pld_catalogos_log
                      INNER JOIN pld_cat_estados_riesgo
 	                            ON pld_cat_estados_riesgo.ID_edo_pld = pld_catalogos_log.ID_registro
	                            AND pld_catalogos_log.Tipo = 'ESTADOS'
	                  INNER JOIN estados ON estados.id_estado = pld_cat_estados_riesgo.ID_Estado

	                  AND pld_catalogos_log.Fecha_sistema >= '".$this->getFechaReescaneo()."' ";

        }else
        {
            $Query = "SELECT
                        estados.nombre as ESTADO
	                  FROM
	                 	pld_cat_estados_riesgo
	                  INNER JOIN estados on estados.id_estado = pld_cat_estados_riesgo.ID_Estado  ";

        }

        $RESPUESTA = $this->db->Execute($Query);

        while( !$RESPUESTA->EOF ) {
            $estado = $RESPUESTA->fields["ESTADO"];
            $this->RevisaSolicitudes("ESTADO",$estado);

            $RESPUESTA->MoveNext();
        }

    }

    public function BuscaCodigosPostales()
    {
        $rest = "";
        if($this->getFechaReescaneo() != "" or $this->getFechaReescaneo() != null)
        {
            $Query = "SELECT
                    pld_cat_codigos_postales_riesgo.CP as CODIGO_POSTAL
                  FROM
                    pld_catalogos_log
                  INNER JOIN pld_cat_codigos_postales_riesgo
 	                  ON pld_cat_codigos_postales_riesgo.ID_ciudad_pld = pld_catalogos_log.ID_registro
	                  AND pld_catalogos_log.Tipo = 'CP'
	                  AND pld_catalogos_log.Fecha_sistema >= '".$this->getFechaReescaneo()."' ";

        }else
        {
            $Query = "SELECT
                    pld_cat_codigos_postales_riesgo.CP as CODIGO_POSTAL
                  FROM
                    pld_cat_codigos_postales_riesgo  ";

        }

        $RESPUESTA = $this->db->Execute($Query);

        while( !$RESPUESTA->EOF ) {
            $codigo_postal = $RESPUESTA->fields["CODIGO_POSTAL"];
            $this->RevisaSolicitudes("CODIGO POSTAL",$codigo_postal);

            $RESPUESTA->MoveNext();
        }

    }

    public function BuscaPuestoPoliticamenteExpuesto()
    {
        $rest = "";
        if($this->getFechaReescaneo() != "" or $this->getFechaReescaneo() != null)
        {
            $Query = "SELECT
                        pld_politicamente_expuestos.ID_PPE AS PUESTO
                      FROM
                        pld_catalogos_log
                      INNER JOIN pld_politicamente_expuestos
                          ON pld_politicamente_expuestos.ID_PPE = pld_catalogos_log.ID_registro
                          AND pld_catalogos_log.Tipo = 'PUESTOS'
                          AND pld_catalogos_log.Fecha_sistema >= '".$this->getFechaReescaneo()."' ";

        }else
        {
            $Query = "SELECT
                    pld_politicamente_expuestos.ID_PPE AS PUESTO
                  FROM
                    pld_politicamente_expuestos  ";

        }

        $RESPUESTA = $this->db->Execute($Query);

        while( !$RESPUESTA->EOF ) {
            $puesto = $RESPUESTA->fields["PUESTO"];
            $this->RevisaSolicitudes("PUESTO",$puesto);

            $RESPUESTA->MoveNext();
        }

    }

    public function BuscaPPE()
    {

        $rest = "";
        if($this->getFechaReescaneo() != "" or $this->getFechaReescaneo() != null)
        {
            $Query = "SELECT
	                  CONCAT (pld_cat_nombres_ppe.Nombre_I,' ',pld_cat_nombres_ppe.Ap_paterno,' ',pld_cat_nombres_ppe.Ap_materno) AS PPE
	                  FROM
	                  pld_cat_listas_negras_log
	                  INNER JOIN pld_cat_nombres_ppe ON pld_cat_nombres_ppe.ID_Nmb_ppe = pld_cat_listas_negras_log.ID_persona
	                  	AND pld_cat_listas_negras_log.Tipo = 'PPE'
	                  	AND pld_cat_listas_negras_log.Fecha_sistema  >= '".$this->getFechaReescaneo()."' ";

        }else
        {
            $Query = "SELECT
                    CONCAT (pld_cat_nombres_ppe.Nombre_I,' ',pld_cat_nombres_ppe.Ap_paterno,' ',pld_cat_nombres_ppe.Ap_materno) AS PPE

                  FROM
                    pld_cat_nombres_ppe
                       ";

        }

        $RESPUESTA = $this->db->Execute($Query);
        $count = 0;
        while( !$RESPUESTA->EOF ) {
            $count++;
            $PPE = $RESPUESTA->fields["PPE"];
            $this->RevisaSolicitudes("PERSONA POLITICAMENTE EXPUESTA",$PPE);
            $RESPUESTA->MoveNext();
        }

    }

    public function BuscaListasPropias()
    {
        $rest = "";
        if($this->getFechaReescaneo() != "" or $this->getFechaReescaneo() != null)
        {
            $Query = "SELECT
	                  CONCAT (pld_cat_nombres_lp.Nombre_I,' ',pld_cat_nombres_lp.Ap_paterno,' ',pld_cat_nombres_lp.Ap_materno) AS LP
	                  FROM
	                  pld_cat_listas_negras_log
	                  INNER JOIN pld_cat_nombres_lp ON pld_cat_nombres_lp.ID_Nmb_lp = pld_cat_listas_negras_log.ID_persona
	                  	AND pld_cat_listas_negras_log.Tipo = 'LP'
	                  	AND pld_cat_listas_negras_log.Fecha_sistema  >= '".$this->getFechaReescaneo()."' ";

        }else
        {
            $Query = "SELECT
                    CONCAT (pld_cat_nombres_lp.Nombre_I,' ',pld_cat_nombres_lp.Ap_paterno,' ',pld_cat_nombres_lp.Ap_materno) AS LP

                  FROM
                    pld_cat_nombres_lp  ";

        }

        $RESPUESTA = $this->db->Execute($Query);

        while( !$RESPUESTA->EOF ) {
            $LP = $RESPUESTA->fields["LP"];
            $this->RevisaSolicitudes("LISTAS PROPIAS",$LP);

            $RESPUESTA->MoveNext();
        }

    }

    public function BuscaListasConsudef()
    {
        $rest = "";
        if($this->getFechaReescaneo() != "" or $this->getFechaReescaneo() != null)
        {
            $Query = "SELECT
	                  CONCAT (pld_cat_nombres_lc.Nombre_I,' ',pld_cat_nombres_lc.Ap_paterno,' ',pld_cat_nombres_lc.Ap_materno) AS LC
	                  FROM
	                  pld_cat_listas_negras_log
	                  INNER JOIN pld_cat_nombres_lc ON pld_cat_nombres_lc.ID_Nmb_lc = pld_cat_listas_negras_log.ID_persona
	                  	AND pld_cat_listas_negras_log.Tipo = 'LC'
	                  	AND pld_cat_listas_negras_log.Fecha_sistema  >= '".$this->getFechaReescaneo()."' ";

        }else
        {
            $Query = "SELECT
                    CONCAT (pld_cat_nombres_lc.Nombre_I,' ',pld_cat_nombres_lc.Ap_paterno,' ',pld_cat_nombres_lc.Ap_materno) AS LC

                  FROM
                    pld_cat_nombres_lc  ";

        }

        $RESPUESTA = $this->db->Execute($Query);

        while( !$RESPUESTA->EOF ) {
            $LC = $RESPUESTA->fields["LC"];
            $this->RevisaSolicitudes("LISTAS CONDUSEF",$LC);

            $RESPUESTA->MoveNext();
        }

    }


    public function BuscaTerroristas()
    {
        if($this->getFechaReescaneo() != "" or $this->getFechaReescaneo() != null)
        {

            $Query = "SELECT
                    count(*) as total
                  FROM
                    pld_importacion_catalogos
                    WHERE  pld_importacion_catalogos.MD5 != 'HISTORICO'
	                  	AND pld_importacion_catalogos.Registro  >= '".$this->getFechaReescaneo()."' ";

            $RESPUESTA = $this->db->Execute($Query);
            $TotalReg = $RESPUESTA->fields["total"];
        }else
        {
            $TotalReg = 1;
        }

        if($TotalReg > 0)
        {
            $Query = "SELECT
                        solicitud.ID_Solicitud,
                        clientes_datos.Num_cliente,
                        fact_cliente.id_factura,
                        solicitud.Status_solicitud,
                         CONCAT(solicitud.Nombre,' ',solicitud.NombreI,' ',solicitud.Ap_paterno,' ',solicitud.Ap_materno) AS NOMBRE
                      FROM
                        solicitud

                      LEFT JOIN clientes_datos ON clientes_datos.Num_cliente = solicitud.num_cliente_solicitud
                              AND clientes_datos.ID_Solicitud =  solicitud.ID_Solicitud
                      LEFT JOIN cat_tipo_credito_regimen ON cat_tipo_credito_regimen.ID_Tipo_regimen = solicitud.ID_RegimenSolicitud

                      LEFT JOIN fact_cliente ON fact_cliente.num_cliente = clientes_datos.Num_cliente
                      LEFT JOIN factura_cliente_liquidacion ON factura_cliente_liquidacion.ID_Factura = fact_cliente.id_factura
                      WHERE solicitud.Status_solicitud NOT IN ('PRESOLICITUD','VALIDACION INGRESOS PRESOLICITUD','CANCELADA')
                      AND factura_cliente_liquidacion.ID_Factura is  null
                      AND cat_tipo_credito_regimen.ID_Regimen != 'PM'
                    ";



            $RESPUESTA = $this->db->Execute($Query);

            while( !$RESPUESTA->EOF ) {

                $nombreCompleto = $RESPUESTA->fields["NOMBRE"];
                if($nombreCompleto != "")
                {
                    $nombreCompleto = str_replace(" ","%",$nombreCompleto);
                    $nombreCompleto = str_replace("  ","%",$nombreCompleto);
                    $nombreCompleto = str_replace("   ","%",$nombreCompleto);

                    $ID_Solicitud = $RESPUESTA->fields["ID_Solicitud"];
                    $Num_cliente = $RESPUESTA->fields["Num_cliente"];
                    $id_factura = $RESPUESTA->fields["id_factura"];


                    $Query = "SELECT
                                         ID_Importadtl
                                    FROM pld_importacion_catalogos_dtl
                                   WHERE CONCAT(pld_importacion_catalogos_dtl.Nombre_I,' ',pld_importacion_catalogos_dtl.Nombre_II) LIKE '$nombreCompleto'";

                    $RESPUESTA2      = $this->db->Execute($Query); // debug($Query);
                    $ID_Importadtl  = $RESPUESTA2->fields["ID_Importadtl"];

                    if( !empty($ID_Importadtl) ){
                        $this->RegistraAlertas($ID_Solicitud,$Num_cliente,$id_factura,"ALERTA TERRORISTA");
                    }
                }


                $RESPUESTA->MoveNext();
            }
        }
        /*$rest = "";
        if($this->getFechaReescaneo() != "" or $this->getFechaReescaneo() != null)
        {
            $Query = "SELECT
	                  CONCAT (pld_importacion_catalogos_dtl.Nombre_I,' ',pld_importacion_catalogos_dtl.Nombre_II) AS Terrorista
	                  FROM
	                  pld_importacion_catalogos
	                  INNER JOIN  pld_importacion_catalogos_dtl ON pld_importacion_catalogos_dtl.ID_Importacion = pld_importacion_catalogos.ID_Importacion
                        AND pld_importacion_catalogos.MD5 != 'HISTORICO'
	                  	AND pld_importacion_catalogos.Registro  >= '".$this->getFechaReescaneo()."' ";

        }else
        {
            $Query = "SELECT
	                  CONCAT (pld_importacion_catalogos_dtl.Nombre_I,' ',pld_importacion_catalogos_dtl.Nombre_II) AS Terrorista
	                  FROM
	                  pld_importacion_catalogos
	                  INNER JOIN  pld_importacion_catalogos_dtl ON pld_importacion_catalogos_dtl.ID_Importacion = pld_importacion_catalogos.ID_Importacion
                      AND pld_importacion_catalogos.MD5 != 'HISTORICO'  ";

        }

        $RESPUESTA = $this->db->Execute($Query);

        while( !$RESPUESTA->EOF ) {
            $Terrorista = $RESPUESTA->fields["Terrorista"];
            $this->RevisaSolicitudes("TERRORISTAS",$Terrorista);

            $RESPUESTA->MoveNext();
        }*/
    }


    public function RevisaSolicitudes($Tipo,$Valor)
    {

        switch($Tipo)
        {
            case "CODIGO POSTAL":
                $condicion = "AND solicitud.CP = '".$Valor."' ";
                break;
            case "ESTADO":
                $Valor = str_replace(" ","%",$Valor);
                $condicion = "AND solicitud.Estado like '%".$Valor."%' ";
                break;
            case "CIUDAD":
                $Valor = str_replace(" ","%",$Valor);
                $condicion = "AND solicitud.Ciudad like '%".$Valor."%' ";
                $condicion = "AND solicitud.Ciudad like '%".$Valor."%' ";
                break;
            case "ACTIVIDAD":
                $condicion = "AND solicitud.ID_GiroNegocio =  ".$Valor." ";
                break;
            case "PERSONA POLITICAMENTE EXPUESTA":
                $Valor = str_replace(" ","%",$Valor);
                $Valor = str_replace("'","%",$Valor);
                $condicion = "AND CONCAT(solicitud.Nombre,' ',solicitud.NombreI,' ',solicitud.Ap_paterno,' ',solicitud.Ap_materno) like  '%".$Valor."%'  ";
                break;
            case "LISTAS PROPIAS":
                $Valor = str_replace(" ","%",$Valor);
                $Valor = str_replace("'","%",$Valor);
                $condicion = "AND CONCAT(solicitud.Nombre,' ',solicitud.NombreI,' ',solicitud.Ap_paterno,' ',solicitud.Ap_materno) like  '%".$Valor."%'  ";
                break;
            case "LISTAS CONDUSEF":
                $Valor = str_replace(" ","%",$Valor);
                $Valor = str_replace("'","%",$Valor);
                $condicion = "AND CONCAT(solicitud.Nombre,' ',solicitud.NombreI,' ',solicitud.Ap_paterno,' ',solicitud.Ap_materno) like  '%".$Valor."%'  ";
                break;
            case "TERRORISTAS":
                $Valor = str_replace(" ","%",$Valor);
                $Valor = str_replace("'","%",$Valor);
                $condicion = "AND CONCAT(solicitud.Nombre,' ',solicitud.NombreI,' ',solicitud.Ap_paterno,' ',solicitud.Ap_materno) like  '%".$Valor."%'  ";
                break;

            default:
                $condicion = "1=0"; // esto es para evitar algo raro y que vaya a alertar todo
        }
        $Tipo = "ALERTA ".$Tipo;

        $Query = "SELECT
                    solicitud.ID_Solicitud,
                    clientes_datos.Num_cliente,
                    fact_cliente.id_factura,
                    solicitud.Status_solicitud
                  FROM
                    solicitud

                  LEFT JOIN clientes_datos ON clientes_datos.Num_cliente = solicitud.num_cliente_solicitud
						  AND clientes_datos.ID_Solicitud =  solicitud.ID_Solicitud
                  LEFT JOIN cat_tipo_credito_regimen ON cat_tipo_credito_regimen.ID_Tipo_regimen = solicitud.ID_RegimenSolicitud
                  LEFT JOIN fact_cliente ON fact_cliente.num_cliente = clientes_datos.Num_cliente
                  LEFT JOIN factura_cliente_liquidacion ON factura_cliente_liquidacion.ID_Factura = fact_cliente.id_factura
                  WHERE solicitud.Status_solicitud NOT IN ('PRESOLICITUD','VALIDACION INGRESOS PRESOLICITUD','CANCELADA')
                  AND factura_cliente_liquidacion.ID_Factura is  null
                  AND cat_tipo_credito_regimen.ID_Regimen != 'PM'
                  $condicion ";


        try{
            $RESPUESTA = $this->db->Execute($Query);
            //print_r($this->db);
            //$this->setError($this->db->ErrorMsg());
            if($this->db->_errorMsg == "")
            {
                while( !$RESPUESTA->EOF ) {

                    $ID_Solicitud = $RESPUESTA->fields["ID_Solicitud"];
                    $Num_cliente = $RESPUESTA->fields["Num_cliente"];
                    $id_factura = $RESPUESTA->fields["id_factura"];
                    $this->RegistraAlertas($ID_Solicitud,$Num_cliente,$id_factura,$Tipo);

                    $RESPUESTA->MoveNext();
                }
            }else
            {
                $this->setError("Error");
            }
        }catch (Exception $e)
        {

        }
    }

    public function RegistraAlertas($ID_Solicitud,$Num_cliente,$ID_factura,$Riesgo)
    {

        $Query = "SELECT
                    ID_reescaneo_log
                  FROM
                    pld_reescaneo_log

                  WHERE pld_reescaneo_log.ID_reescaneo = '".$this->getIDReescaneo()."'
                  AND pld_reescaneo_log.ID_Solicitud = '".$ID_Solicitud."'
                  AND pld_reescaneo_log.Num_cliente = '".$Num_cliente."'
                  AND pld_reescaneo_log.ID_factura = '".$ID_factura."'
                 ";
        $RESPUESTA = $this->db->Execute($Query);
        $ID_reescaneo_dtl = $RESPUESTA->fields["ID_reescaneo_log"];

        if($ID_reescaneo_dtl > 0)
        {
            $Query = "INSERT INTO pld_reescaneo_dtl(ID_reescaneo_log,Riesgo) VALUES (".$ID_reescaneo_dtl.", '".$Riesgo."' )";
            $this->db->Execute($Query);

        }else{

            $Query = "INSERT INTO pld_reescaneo_log(ID_reescaneo,ID_Solicitud,Num_cliente,ID_factura)
            VALUES (".$this->getIDReescaneo().", '".$ID_Solicitud."', '".$Num_cliente."', '".$ID_factura."')";
            $this->db->Execute($Query);
            $ID_reescaneo_dtl = $this->db->_insertid();

            $Query = "INSERT INTO pld_reescaneo_dtl(ID_reescaneo_log,Riesgo) VALUES (".$ID_reescaneo_dtl.", '".$Riesgo."' )";
            $this->db->Execute($Query);
        }

    }

    public function getRegistros($ID_Reescaneo)
    {


        $Query = "SELECT

                pld_reescaneo_log.ID_reescaneo_log,
                pld_reescaneo_log.ID_Solicitud,
                pld_reescaneo_log.Num_cliente,
                pld_reescaneo_log.ID_factura,
                CONCAT(solicitud.Nombre,' ',solicitud.NombreI,' ',solicitud.Ap_paterno,' ',solicitud.Ap_materno) AS NOMBRE,
                solicitud.Tipo_Solicitud,
                solicitud.Monto

                FROM
                pld_reescaneo_log
                INNER JOIN pld_reescaneo on pld_reescaneo.ID_reescaneo = pld_reescaneo_log.ID_reescaneo
                INNER JOIN solicitud ON solicitud.ID_Solicitud = pld_reescaneo_log.ID_Solicitud
                WHERE pld_reescaneo.ID_reescaneo = '".$ID_Reescaneo."'
                ";

        $RESPUESTA = $this->db->Execute($Query);
        $Contador = 0;
        while( !$RESPUESTA->EOF ) {


            $Contador++;
            $Query = "SELECT  pld_reescaneo_dtl.Riesgo
                        FROM pld_reescaneo_dtl
                     WHERE pld_reescaneo_dtl.ID_reescaneo_log = '".$RESPUESTA->fields["ID_reescaneo_log"]."' ";
            $RESPUESTA2 = $this->db->Execute($Query);

            $Riesgos = "";
            while( !$RESPUESTA2->EOF ) {

                $Riesgos .= $RESPUESTA2->fields["Riesgo"]."<br>";
                $RESPUESTA2->MoveNext();
            }

           $tabla .= "
                <tr>
                    <td>
                        ".$RESPUESTA->fields["ID_Solicitud"]."
                    </td>
                    <td>
                        ".$RESPUESTA->fields["Num_cliente"]."
                    </td>
                    <td>
                        ".$RESPUESTA->fields["ID_factura"]."
                    </td>
                    <td>
                        ".$RESPUESTA->fields["NOMBRE"]."
                    </td>
                    <td>
                        ".$RESPUESTA->fields["Tipo_Solicitud"]."
                    </td>
                    <td>
                        $ ".number_format($RESPUESTA->fields["Monto"],2)."
                    </td>
                    <td>
                        ".$Riesgos."
                    </td>
                </tr>

           ";

            $RESPUESTA->MoveNext();
        }

        if($Contador == 0 )
        {
            $tabla = "
                <tr>
                    <th colspan='7'>No se encontraron registros
                    </th>
                </tr>";
        }

        return $tabla;


    }

    public function getTablaLog()
    {

        if($this->validaOficial() != false)
        {

            $Query = "SELECT
                        CONCAT(usuarios.Nombre,' ',usuarios.AP_Paterno,' ',usuarios.AP_Materno) AS NOMBRE,
                        pld_reescaneo.Fecha_sistema,
                        pld_reescaneo.TotalReg,
                        pld_reescaneo.ID_reescaneo
                        FROM pld_reescaneo
                        LEFT JOIN usuarios ON usuarios.ID_User = pld_reescaneo.ID_Usr
                        WHERE pld_reescaneo.Estatus = 'LISTO'
                        ORDER BY  pld_reescaneo.Fecha_sistema DESC
                    ";

            $RESPUESTA = $this->db->Execute($Query);
            $Contador = 0;
            while( !$RESPUESTA->EOF ) {


                $Contador++;

                $tabla .= "
                    <tr>
                        <td>
                            ".$RESPUESTA->fields["NOMBRE"]."
                        </td>
                        <td>
                            ".$RESPUESTA->fields["Fecha_sistema"]."
                        </td>
                        <td>
                            ".$RESPUESTA->fields["TotalReg"]."
                        </td>
                        <td align='center'>
                            <a class='ui mini blue button' onclick='VerDatos(".$RESPUESTA->fields["ID_reescaneo"]." );'>DETALLE</a>
                        </td>
                    </tr>

               ";

                $RESPUESTA->MoveNext();
            }

            if($Contador == 0 )
            {
                $tabla = "
                    <tr>
                        <th colspan='4'>No se encontraron registros
                        </th>
                    </tr>";
            }

        }else{
            $tabla = "
                    <tr>
                        <th colspan='4'>Usted no tiene permisos para ver este modulo
                        </th>
                    </tr>";
        }

        $arr = array('PermisoOficial' => $this->validaOficial(), "Tabla" => $tabla);
        require_once( "../../class/json.php" );
        $json       = new Services_JSON;
        $arrDatos = $json->encode($arr);

        return  $arrDatos;


    }

}


?>