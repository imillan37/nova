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

 
class ControllerConfiguradorPLD {
 
 	public $db;
    public $muestra_global = 10;
 	 
 		public function __construct($db){
	 		$this->db = $db; 
	 	} 	 		


//********************************************************//
//---------  esta funcion actualizara alqaida  -----------//
//********************************************************//


		public function setConfiguracion($Campos,$ID_USR)
		{

			require_once( "../../class/json.php" );
            $json       = new Services_JSON;
            $Campos = $json->decode($Campos);
            $cont = 0;
            
            foreach ($Campos as $key)
            {

                $campo = $key->campo;
                $valor = $key->valor;

                if($valor)
                {
                    $valor1 = "SI";
                }else{
                    $valor1 = "NO";
                }

                if($cont == 0)
                {
                    $campos_update .= $campo." = '".$valor1."'";
                }else
                {
                    $campos_update .= ", ".$campo." = '".$valor1."'";
                }

                $cont++;


            }

            $UPDATE = "UPDATE pld_originacion SET $campos_update WHERE ID_pld_originacion = 1 ";
            $this->db->Execute($UPDATE); //debug( $UPDATE );


            $QRY = "INSERT INTO pld_originacion_log (Terroristas, Nombres_PPE, Puestos_PPE, CodigosPostales, Estados, Ciudades, Giros, ID_Usr, PaisesRiesgosos) SELECT Terroristas, Nombres_PPE, Puestos_PPE, CodigosPostales, Estados, Ciudades, Giros, ".$ID_USR.", PaisesRiesgosos FROM pld_originacion ";
            $this->db->Execute($QRY);

            return "OK";
					
		}

		public function getConfiguracion()
		{
		
			$SQL = "SELECT Terroristas, Nombres_PPE, Puestos_PPE, CodigosPostales, Estados, Ciudades, Giros, PaisesRiesgosos
					FROM pld_originacion 
					WHERE ID_pld_originacion = 1
							"; 
					
				 $RESPUESTA  = $this->db->Execute($SQL);


            require_once( "../../class/json.php" );
            $json       = new Services_JSON;
            $arrDatos = $json->encode($RESPUESTA->fields);

            return  $arrDatos;
					
		}

    public function getHistorico($Filtro,$Evento,$Pagina)
    {
        $muestra = $this->muestra_global;

        $Query_count = "SELECT count(*) as Total
									FROM pld_originacion_log ";

        $RESPUESTA      = $this->db->Execute($Query_count);
        $total_registros = $RESPUESTA->fields["Total"];
        $total_paginas = $total_registros/$muestra;
        $total_paginas = ceil($total_paginas);

        if($Pagina>$total_paginas)
        {
            $Pagina = 1;
        }

        if (!$Pagina or $Pagina == 0) {
            $limite_ini = 0;
            $Pagina = 1;
        }
        else {
            if($Evento == "Prev"){
                $Pagina--;
                if($Pagina == 0)
                {
                    $Pagina = 1;
                }
            }elseif($Evento == "Next")
            {
                $Pagina++;
                if($Pagina>$total_paginas)
                {
                    $Pagina--;
                }
            }elseif($Evento == "First")
            {
                $Pagina = 1;
            }elseif($Evento == "Last")
            {
                $Pagina = $total_paginas;
            }else
            {
                $limite_ini = ($Pagina) * $muestra;
            }

            $limite_ini = ($Pagina-1) * $muestra;
	    if($limite_ini < 0)
	    {
		$limite_ini = 0;
            }

        }

        $limite = "LIMIT ".$limite_ini.", ".$muestra;

        $SQL = "SELECT ID_pld_originacion_log, Fecha_sistema , CONCAT(usuarios.Nombre,' ',usuarios.AP_Paterno,' ',usuarios.AP_Materno) as Nombre_usuario
				FROM pld_originacion_log

				INNER JOIN usuarios ON usuarios.ID_User = pld_originacion_log.ID_Usr
				$limite ";

        $RESPUESTA  = $this->db->Execute($SQL);

        while( !$RESPUESTA->EOF ) {

            $Nombre_usuario         = $RESPUESTA->fields["Nombre_usuario"];
            $Fecha_sistema          = $RESPUESTA->fields["Fecha_sistema"];
            $ID_pld_originacion_log = $RESPUESTA->fields["ID_pld_originacion_log"];


            $htmlTbody .= '

			  	<tr>
					<td align="left">'.strtoupper($Nombre_usuario).'</td>
					<td align="left">'.strtoupper($Fecha_sistema).'</td>
					<td align="center"><a class="ui mini blue button" onclick="DetalleHistorico('.$ID_pld_originacion_log.');">DETALLE</a></td>

				</tr>

			  ';


            $RESPUESTA->MoveNext();
        } // fin while( !$RESPUESTA->EOF ) {


        $html = '

        	  <table align="center" width="100%" class="ui table segment">
				<thead>
					<tr>
						<th>USUARIO QUE ACTUALIZO</th>
						<th>FECHA DE ACTUALIZACION</th>
						<th></th>
					</tr>
				</thead>
				'.$htmlTbody.'
			</table>';

        $html .= "
            <div class='ui'>
                <i class='big step backward icon' onclick='ActualizaLista(\"First\")'></i>
                <i class='big backward icon' onclick='ActualizaLista(\"Prev\")'></i>

                <div class='ui small icon input'>
                    <input size='4' type='text' id='Paginacion' onchange='CambiaPagina(this)' value='".$Pagina."'>
                </div>

                <i class='big forward icon' onclick='ActualizaLista(\"Next\")'></i>
                <i class='big step forward icon' onclick='ActualizaLista(\"Last\")'></i>
            </div>";

        echo  $html;

    }

    function getDetalle($ID_pld_originacion_log)
    {
        $SQL = "SELECT Terroristas, Nombres_PPE, Puestos_PPE, CodigosPostales, Estados, Ciudades, Giros, Fecha_sistema , CONCAT(usuarios.Nombre,' ',usuarios.AP_Paterno,' ',usuarios.AP_Materno) as Nombre_usuario
					FROM pld_originacion_log
					INNER JOIN usuarios ON usuarios.ID_User = pld_originacion_log.ID_Usr
					WHERE ID_pld_originacion_log = $ID_pld_originacion_log
							";

        $RESPUESTA  = $this->db->Execute($SQL); //debug($SQL);


        require_once( "../../class/json.php" );
        $json       = new Services_JSON;
        $arrDatos = $json->encode($RESPUESTA->fields);

        return  $arrDatos;

    }

 
 }



?>