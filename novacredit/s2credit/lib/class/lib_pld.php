<?


class TRIESGO
{


    protected  $db;

    public  $num_cliente;
    
    public  $id_cliente;
    
    public  $matriz_riesgo      = array();
    
    public  $parametro_minimo_riesgo_alto;
    
    
    public  $vectores_evaluacion = array();  // De cada indicador tiene el valor (1=Verdadero, -1=Falso, 0=No Evaluado);
    
    public  $riesgo_ponderado = 0; // Sumatoria de los ponderados 
    
    public  $riesgo_tipo  = ""; // ALTO BAJO

//==========================================================================================================================================
//
//==========================================================================================================================================


function __construct(&$db, $num_cliente=0)
{ 

    $this->TRIESGO($db, $num_cliente);

}

//==========================================================================================================================================
//
//==========================================================================================================================================


function TRIESGO(&$db, $num_cliente=0)
{
        $this->db = $db;
        $this->num_cliente = $num_cliente;        

        $this->inicializa();  // Obtenemos saldos iniciales

}
//==========================================================================================================================================
// Inicializa :  Obtiene los vales ponderados para la matriz de riesgo
//==========================================================================================================================================

function inicializa()
{

	$sql = "SELECT ID_Riesgo, 
	               Campo_Relacionado, 
	               Descripcion, 
	               Ponderacion
	
		FROM pld_matriz_riesgo

		ORDER BY ID_Riesgo ";
		
	$rs = $this->db->Execute($sql);	
	
	$this->matriz_riesgo		= array();
	$this->vectores_evaluacion	= array();
	
	if($rs->_numOfRows)
	   while(! $rs->EOF)
	   {

	     $_id_riesgo = $rs->fields['ID_Riesgo'];
	     
	     
	     $this->vectores_evaluacion[$_id_riesgo]	= 0;


	     $this->matriz_riesgo[$_id_riesgo] 		= array();
	     
	     $this->matriz_riesgo[$_id_riesgo]['Campo_Relacionado']  = $rs->fields['Campo_Relacionado'];
	     $this->matriz_riesgo[$_id_riesgo]['Descripcion']        = $rs->fields['Descripcion']       ;
	     $this->matriz_riesgo[$_id_riesgo]['Ponderacion']        = $rs->fields['Ponderacion']      ;
	     
	     $rs->MoveNext();
	   }	
	

	$this->parametro_minimo_riesgo_alto = 0;
	
	$sql = "SELECT pld_matriz_riesgo_clasificacion.Puntos_Minimos
		FROM   pld_matriz_riesgo_clasificacion
		WHERE  pld_matriz_riesgo_clasificacion.ID_Matriz_Riesgo_Clasificacion = 1 ";
		
	$rs = $this->db->Execute($sql);	
	
	$this->parametro_minimo_riesgo_alto = $rs->fields['Puntos_Minimos'];	
	
	
	
	if($this->num_cliente > 0)
	{
		$this->set_cliente($this->num_cliente);
	}
	

}

//==========================================================================================================================================
//
//==========================================================================================================================================

function set_cliente($num_cliente)
{
	$this->num_cliente    = $num_cliente;
	
	$sql =" SELECT clientes.ID_Cliente
		FROM  clientes
		WHERE clientes.num_cliente = '".$this->num_cliente."' ";
        //debug($sql);
	$rs=$this->db->Execute($sql);
	
	$this->id_cliente = $rs->fields[0];	
}



//==========================================================================================================================================
// Evalua riesgo
//==========================================================================================================================================


function evalua_estado_vectores()
{


	$this->riesgo_tipo ="";
	$this->riesgo_ponderado = 0;
	$this->vectores_evaluacion = array();
	
	
	$this->vectores_evaluacion[1 ] = $this->verifica_beneficiario_terceros();
	$this->vectores_evaluacion[2 ] = $this->expediente_digital_incompleto();			
	$this->vectores_evaluacion[3 ] = $this->datos_cliente_incompletos();				
	$this->vectores_evaluacion[4 ] = $this->persona_politicamente_expuesta();			
	$this->vectores_evaluacion[5 ] = $this->vinculado_terrorismo_u_org_criminales();	
	$this->vectores_evaluacion[6 ] = $this->vinculado_paraisos_fiscales();		
	$this->vectores_evaluacion[7 ] = $this->credito_solidario();			
	$this->vectores_evaluacion[8 ] = $this->motiva_sospechas_act_ilicitas();	
	$this->vectores_evaluacion[9 ] = $this->actividad_economica_alto_riesgo();		
	$this->vectores_evaluacion[10] = $this->extranjero();				



	$this->vectores_evaluacion[11] = $this->politicamente_expuesta_extranjera();
	$this->vectores_evaluacion[12] = $this->estado_de_la_republica_de_alto_riesgo();
	$this->vectores_evaluacion[13] = $this->ciudad_alto_riesgo();
	$this->vectores_evaluacion[14] = $this->codigo_postal_alto_riesgo();
	$this->vectores_evaluacion[15] = $this->giro_de_negocio_con_alto_riesgo();
	$this->vectores_evaluacion[16] = $this->ingresos_provenientes_de_un_tercero();


	//--------------------------------------------------------------------------
	// Suma de cada uno de los valores ponderados de riesgo para los reactivos 
	// en los cuales si existe causal de riesgo
	foreach ($this->vectores_evaluacion AS $key => $value)
	{
	       $valor = ($this->vectores_evaluacion[$key] <= 0)?(0):($this->matriz_riesgo[$key]['Ponderacion']);	
	       
	       $this->riesgo_ponderado += $valor;
	}
	

	//--------------------------------------------------------------------------
	// Ponderación final del tipo de riesgo
	if($this->riesgo_ponderado >= $this->parametro_minimo_riesgo_alto )
	{
		$this->riesgo_tipo = "ALTO";
	}
	else
	{
		$this->riesgo_tipo = "BAJO";	
	}
	


}

//------------------------------------------------------------------
// La respuesta debería ser siempre NO , debido a que el contrato dice 
// que la persona manifiesta que los recursos que solicita no serán usados
// para el beneficio de terceros, ni para actividades relacionadas con el 
// crimen o con el terrorismo.
//------------------------------------------------------------------
function verifica_beneficiario_terceros()
{

	$sql =" SELECT solicitud_plvd.Grado_riesgo

		FROM   solicitud_plvd, clientes

		WHERE      solicitud_plvd.ID_Solicitud = clientes.ID_Solicitud  
		       AND clientes.Num_cliente   = '".$this->num_cliente."' ";


	 $rs=$this->db->Execute($sql);

	 if( empty($rs->fields[0]) )
	 {
	 	return(0);
	 }

	 
	 if(trim($rs->fields[0]) == "ALTO")
	 {
	 	return(1);
	 }
	 else
	 {
		return(-1); 
	 }


}
//------------------------------------------------------------------
function expediente_digital_incompleto()
{
		
/*
     $sql ="SELECT      cat_documentos.ID_Documento		AS ID_DOC,
			cat_documentos.Descripcion		AS DESCP,
			clientes.Regimen			AS Regimen,


			CONCAT(IFNULL(solicitud.Nacionalidad_soli,''),IFNULL(solicitud_pmoral.Nacionalidad_soli,'')) AS Nacionalidad,

			IF(cat_documentos.ID_Documento NOT IN (-7, -8),

				cat_documentos.Obligatorio_plvd,

				CASE (clientes.Regimen) 
					WHEN 'PM' THEN IF( solicitud_pmoral.Nacionalidad_soli = 'MEXICANA','N','Y')
					ELSE           IF(        solicitud.Nacionalidad_soli = 'MEXICANA','N','Y')
				END 

			) AS OBLG_PLD,

			IFNULL(solicitud_documentos.Entregado,'N')	AS EXIST

	FROM  cat_documentos  

	INNER JOIN clientes		ON  clientes.Num_cliente = '".$this->num_cliente."'    

	LEFT JOIN solicitud_documentos	ON  solicitud_documentos.ID_Solicitud  = clientes.ID_Solicitud	
					AND solicitud_documentos.ID_Documentos = cat_documentos.ID_Documento 
					AND cat_documentos.ID_Documento < 0

	LEFT JOIN solicitud        ON solicitud.ID_Solicitud        = clientes.ID_Solicitud
	LEFT JOIN solicitud_pmoral ON solicitud_pmoral.ID_Solicitud = clientes.ID_Solicitud                        

	WHERE cat_documentos.Obligatorio_plvd = 'Y' or 
	      cat_documentos.ID_Documento  IN (-7,-8) 
	
		
	 HAVING OBLG_PLD = 'Y' ";

*/



   $sql ="SELECT    	cat_documentos.ID_Documento			AS ID_DOC,
			cat_documentos.Descripcion			AS Descripcion,
			clientes.Regimen				AS Regimen,
			solicitud_plvd.Nacionalidad 			AS Nacionalidad,

			IF(cat_documentos.ID_Documento NOT IN (-7, -8),

				cat_documentos.Obligatorio_plvd,

				IF( solicitud_plvd.Nacionalidad = 'MEXICANA','N','Y')

			) 						AS OBLG_PLD,

			IFNULL(solicitud_documentos.Entregado,'N')	AS EXIST

	FROM  cat_documentos  

	INNER JOIN clientes		ON  clientes.Num_cliente = '".$this->num_cliente."'     

	LEFT JOIN solicitud_documentos	ON  solicitud_documentos.ID_Solicitud  = clientes.ID_Solicitud	
					AND solicitud_documentos.ID_Documentos = cat_documentos.ID_Documento 
					AND cat_documentos.ID_Documento < 0

	LEFT JOIN solicitud_plvd   ON solicitud_plvd.ID_Solicitud        = clientes.ID_Solicitud
                      

	WHERE cat_documentos.Obligatorio_plvd = 'Y' or 
	      cat_documentos.ID_Documento  IN (-7,-8) 
	
	 HAVING OBLG_PLD = 'Y' ";



	 $rs=$this->db->Execute($sql);
	 
	 if($rs->_numOfRows == 0)
	 {
	 	return(0);
	 }
	
	
	 $num_incompletos=0;
	
	if($rs->_numOfRows)
	   while(! $rs->EOF)
	   {
	  	if( $rs->fields['EXIST'] != 'Y')
	 	{
	 		$num_incompletos += 1;	
	 	}
	 
	      $rs->MoveNext();
	    }
	
	
	if($num_incompletos > 0)
	{
		return(1); // Existen documentos no digitalizados 
	}
	else
	{
		return(-1); // Todos los datos están completos
	}
	


	return 0;
}
//------------------------------------------------------------------
function datos_cliente_incompletos()
{
        $num_datos_obligatorios = 15;		
	
	$sql = "SELECT  solicitud_plvd.Nombre,
			solicitud_plvd.Ap_paterno,
			solicitud_plvd.Calle,
			solicitud_plvd.Colonia,
			solicitud_plvd.CP,
			solicitud_plvd.CURP,
			solicitud_plvd.RFC,
			solicitud_plvd.Fecha_nacimiento,
			solicitud_plvd.Estado,
			solicitud_plvd.Nombre,
			solicitud_plvd.ID_Actividad_Economica,
			solicitud_plvd.Puesto_politico,
			solicitud_plvd.ID_Pais,
			solicitud_plvd.Poblacion,
			solicitud_plvd.Grado_riesgo

		FROM solicitud_plvd,
		     clientes

		WHERE clientes.ID_Solicitud = solicitud_plvd.ID_Solicitud AND
		      clientes.Num_cliente  = '".$this->num_cliente."' ";


	
	 $rs=$this->db->Execute($sql);
	 
	 if($rs->_numOfRows == 0)
	 {
	 	return(0);
	 }
	
	
	 $num_incompletos=0;
	
	 for($i=0; $i < $num_datos_obligatorios; $i++)
	 {
	 	if( empty($rs->fields[$i]))
	 	{
	 		$num_incompletos += 1;	
	 	}
	 }
	
	
	if($num_incompletos > 0)
	{
		return(1); // Existen datos incopmpletos
	}
	else
	{
		return(-1); // Todos los datos están completos
	}
	
}

//------------------------------------------------------------------

function persona_politicamente_expuesta()
{

//------------------------------------------------------------------------------------------------------------------------------------------
//  http://www.hacienda.gob.mx/LASHCP/MarcoJuridico/InteligenciaFinanciera/disposiciones/personas_politicamente_expuestas_nacionales.pdf
//------------------------------------------------------------------------------------------------------------------------------------------


	$sql = "SELECT  solicitud_plvd.Puesto_politico
		FROM  solicitud_plvd,
		      clientes

		WHERE   solicitud_plvd.ID_Solicitud = clientes.ID_Solicitud 
		AND     clientes.Num_cliente = '".$this->num_cliente."' ";
        
        $rs = $this->db->Execute($sql);
        
        if(empty($rs->fields[0]))
        {
        	return(0);
	}



	$sql = "SELECT  solicitud_plvd.Puesto_politico,
			pld_politicamente_expuestos.ID_PPE,      
			pld_politicamente_expuestos.Puesto

		FROM  solicitud_plvd,
		      clientes,
		      pld_politicamente_expuestos

		WHERE 
		    solicitud_plvd.ID_Solicitud = clientes.ID_Solicitud 
		AND clientes.Num_cliente = '".$this->num_cliente."' 

		AND pld_politicamente_expuestos.Puesto LIKE CONCAT(TRIM(solicitud_plvd.Puesto_politico),'%') ";

	//debug($sql);
	
	 $rs=$this->db->Execute($sql);
	 
	 if($rs->_numOfRows > 0)
	 {
	 	return(1);
	 }
	 else
	 {
		return(-1); 
	 }

}

//------------------------------------------------------------------

function vinculado_terrorismo_u_org_criminales()
{


	$sql = "SELECT solicitud_plvd.Nombre,
		       solicitud_plvd.NombreI,
		       solicitud_plvd.Ap_paterno,
		       solicitud_plvd.Ap_materno


		FROM  solicitud_plvd,
		      clientes

		WHERE 
			    solicitud_plvd.ID_Solicitud = clientes.ID_Solicitud 
			AND clientes.Num_cliente = '".$this->num_cliente."' ";
		
		
	 $rs=$this->db->Execute($sql);
	 
	 
	if($rs->_numOfRows == 0)
	{
		return(0);
	}
	 

	list($nombre1, $nombre2, $ap_paterno, $ap_materno) = $rs->fields;
	

	$nombre_completo  = trim($nombre1);



	$nombre2 = trim($nombre2);
	
	$nombre_completo .= (strlen($nombre2   )>0)?(" ".$nombre2   ):("");
	


	$ap_paterno = trim($ap_paterno);
	
	$nombre_completo .= (strlen($ap_paterno)>0)?(" ".$ap_paterno):("");



	$ap_materno = trim($ap_materno);
	
	$nombre_completo .= (strlen($ap_materno)>0)?(" ".$ap_materno):("");





	 $sql = "SELECT pld_importacion_catalogos_dtl.ID_Importadtl

		 FROM   pld_importacion_catalogos_dtl

		 WHERE CONCAT(TRIM(pld_importacion_catalogos_dtl.Nombre_I),' ',TRIM(pld_importacion_catalogos_dtl.Nombre_II)) =  '".$nombre_completo."' ";

	 $rs = $this->db->Execute($sql);


	 if($rs->fields[0] > 0)
	 {
	 	return(1);
	 }
	 else
	 {
	 	return(-1);
	 }

}

//------------------------------------------------------------------

function vinculado_paraisos_fiscales()
{


	$sql ="SELECT pld_cat_paraisos_fiscales.ID_Pais

		FROM  solicitud_plvd,
		      clientes,
		      pld_cat_paraisos_fiscales

		WHERE    solicitud_plvd.ID_Solicitud = clientes.ID_Solicitud
		     AND solicitud_plvd.ID_Pais = pld_cat_paraisos_fiscales.ID_Pais
		     AND clientes.Num_cliente   = '".$this->num_cliente."' ";


	 $rs=$this->db->Execute($sql);
	 
	 
	 if( !empty($rs->fields['ID_Pais'] ))
	 {
	 	return(1);
	 }
	 else
	 {
	 	return(-1);
	 }
	 
}

//------------------------------------------------------------------

function credito_solidario()
{

	$sql =" SELECT clientes.ID_Tipocredito
		FROM   clientes
		WHERE  clientes.Num_cliente = '".$this->num_cliente."' ";

	$rs=$this->db->Execute($sql);
	
	if($rs->fields[0] == 2 )
	{
		return(1);
	}
	else
	{
		return(-1);
	}
	
}

//------------------------------------------------------------------

function motiva_sospechas_act_ilicitas()
{
	$sql =" SELECT pld_perfil_transaccional.Motiva_Sospechas_Act_Ilicitas


		FROM pld_perfil_transaccional,
		     clientes

		WHERE     pld_perfil_transaccional.ID_Cliente = clientes.ID_Cliente 
		      AND clientes.Num_cliente = '".$this->num_cliente."' ";


	$rs=$this->db->Execute($sql);
	
	if($rs->fields[0] == 'Si' )
	{
		return(1);
	}
	else
	{
		return(-1);
	}
	
}



//------------------------------------------------------------------

function actividad_economica_alto_riesgo()
{

	$sql =" SELECT pld_cat_actividades_economicas_riesgo.ID_Actividad

		FROM (solicitud_plvd, clientes)

		LEFT JOIN pld_cat_actividades_economicas_riesgo ON pld_cat_actividades_economicas_riesgo.ID_Actividad = solicitud_plvd.ID_Actividad_Economica

		WHERE      solicitud_plvd.ID_Solicitud = clientes.ID_Solicitud  
		       AND clientes.Num_cliente   = '".$this->num_cliente."' ";



 	$rs=$this->db->Execute($sql);
 	
 	
 	
 	if($rs->fields[0] > 0)
 	{
 		return(1);
 	}
 	else
 	{
 		return(-1);
 	}
 	 

}


//------------------------------------------------------------------

function extranjero()
{
/*
	$sql =" SELECT cat_paises.ID_pais
		FROM (solicitud_plvd, clientes)
		INNER JOIN cat_paises ON  solicitud_plvd.ID_pais     = cat_paises.ID_pais
		WHERE      solicitud_plvd.ID_Solicitud = clientes.ID_Solicitud  
		       AND clientes.Num_cliente   = '".$this->num_cliente."' ";
		       
 	$rs=$this->db->Execute($sql);
 	 	
 	if(empty($rs->fields[0]))
 	{
 		return(0);
 	}
 	
 	if($rs->fields[0] == "MX")
 	{
 		return(-1);
 	}
 	else
 	{
 		return(1);
 	}
*/ 	 


	$sql =" SELECT solicitud_plvd.Nacionalidad

		FROM solicitud_plvd, clientes

		WHERE      solicitud_plvd.ID_Solicitud = clientes.ID_Solicitud  
		       AND clientes.Num_cliente   = '".$this->num_cliente."' ";
		       
 	$rs=$this->db->Execute($sql);
 	
 	 	
 	if(empty($rs->fields[0]))
 	{
 		return(0);
 	}

 	
 	if(($rs->fields[0] == "MEXICANA") or ($rs->fields[0] == "MEXICANO"))
 	{
 		return(-1);
 	}
 	else
 	{
 		return(1);
 	}

}

//------------------------------------------------------------------

function politicamente_expuesta_extranjera()  
{


	$sql =" SELECT solicitud_plvd.Puesto_publico_extrj

		FROM   solicitud_plvd,
		       clientes

		WHERE solicitud_plvd.ID_Solicitud = clientes.ID_Solicitud
		      and clientes.Num_cliente = '".$this->num_cliente."' ";



 	$rs=$this->db->Execute($sql);
 	
 	
 	if($rs->fields[0] == "SI")
 	{
 		return(1);
 	}
 	else
 	{
 		return(-1);
 	}



	 return 0;
}

//------------------------------------------------------------------

function estado_de_la_republica_de_alto_riesgo()  
{


$sql =" SELECT solicitud_plvd.Estado,
	       estados.cve_estado,
	       pld_cat_estados_riesgo.ID_Estado

	FROM   solicitud_plvd
	INNER JOIN clientes              ON solicitud_plvd.ID_Solicitud         = clientes.ID_Solicitud
	LEFT JOIN estados                ON estados.Nombre                      = solicitud_plvd.Estado
	LEFT JOIN pld_cat_estados_riesgo ON estados.cve_estado                  = pld_cat_estados_riesgo.cve_estado

	WHERE clientes.Num_cliente =  '".$this->num_cliente."' ";



 	$rs=$this->db->Execute($sql);
 	
 	
 	if(empty($rs->fields['cve_estado']))
 	{
 		return(0);
 	}
 	
 	if($rs->fields['ID_Estado'] > 0)
 	{
 		return(1);
 	}
 	else
 	{
 		return(-1);
 	}
	 return 0;
}

//------------------------------------------------------------------

function ciudad_alto_riesgo()  
{

$sql =" SELECT estados.cve_estado,
	       estados.ID_Estado,
	       ciudades.ID_Ciudad,
	       ciudades.Nombre AS Ciudad,
	       pld_cat_ciudades_riesgo.ID_codigo_pld

	FROM   solicitud_plvd
	
	INNER JOIN clientes              ON solicitud_plvd.ID_Solicitud         = clientes.ID_Solicitud
	LEFT  JOIN estados               ON estados.Nombre                      = solicitud_plvd.Estado

	LEFT JOIN ciudades               ON estados.ID_Estado                   = ciudades.ID_Estado
					AND solicitud_plvd.Ciudad               = ciudades.Nombre

	LEFT JOIN pld_cat_ciudades_riesgo ON pld_cat_ciudades_riesgo.ID_Ciudad  = ciudades.ID_Ciudad

	WHERE clientes.Num_cliente =  '".$this->num_cliente."' ";


 	$rs=$this->db->Execute($sql);
 	
 	
 	if(empty($rs->fields['ID_Ciudad']))
 	{
 		return(0);
 	}
 	
 	if($rs->fields['ID_codigo_pld'] > 0)
 	{
 		return(1);
 	}
 	else
 	{
 		return(-1);
 	}
	 return 0;



}

//------------------------------------------------------------------

function codigo_postal_alto_riesgo()  
{

	$sql =" SELECT solicitud_plvd.CP,
		       pld_cat_codigos_postales_riesgo.ID_ciudad_pld

		FROM   solicitud_plvd
		INNER JOIN clientes                          ON solicitud_plvd.ID_Solicitud         = clientes.ID_Solicitud
		LEFT  JOIN pld_cat_codigos_postales_riesgo   ON pld_cat_codigos_postales_riesgo.CP  = solicitud_plvd.CP

		WHERE clientes.Num_cliente  = '".$this->num_cliente."' ";    

	$rs=$this->db->Execute($sql);
 	
 	
 	if(empty($rs->fields['CP']))
 	{
 		return(0);
 	}
 	
 	if($rs->fields['ID_ciudad_pld'] > 0)
 	{
 		return(1);
 	}
 	else
 	{
 		return(-1);
 	}

	 return 0;






	 return 0;
}
//------------------------------------------------------------------


function giro_de_negocio_con_alto_riesgo()  
{
	$sql =" SELECT solicitud_plvd.ID_Giro_negocio,
		       pld_cat_giro_negocio.Giro,
		       pld_cat_giro_negocio.Tipo

		FROM   solicitud_plvd
		INNER JOIN clientes             ON solicitud_plvd.ID_Solicitud          = clientes.ID_Solicitud
		LEFT  JOIN pld_cat_giro_negocio ON pld_cat_giro_negocio.ID_Giro_negocio = solicitud_plvd.ID_Giro_negocio         

		WHERE clientes.Num_cliente ='".$this->num_cliente."' ";    

	$rs=$this->db->Execute($sql);
 	
 	
 	if(empty($rs->fields[0]))
 	{
 		return(0);
 	}
 	
 	if($rs->fields['Tipo'] == 'ALTO RIESGO')
 	{
 		return(1);
 	}
 	else
 	{
 		return(-1);
 	}

	 return 0;
	 
}

//------------------------------------------------------------------

function ingresos_provenientes_de_un_tercero()  
{
	
	$sql =" SELECT solicitud_plvd.Origen_recursos  
		FROM   solicitud_plvd,
		       clientes

		WHERE solicitud_plvd.ID_Solicitud = clientes.ID_Solicitud
		      and clientes.Num_cliente = '".$this->num_cliente."' ";    

	$rs=$this->db->Execute($sql);
 	
 	
 	if(empty($rs->fields[0]))
 	{
 		return(0);
 	}
 	
 	if($rs->fields[0] == "RECURSOS DE TERCEROS")
 	{
 		return(1);
 	}
 	else
 	{
 		return(-1);
 	}



	 return 0;
}
//------------------------------------------------------------------






//==========================================================================================================================================
//	Actualiza Perfil Transaccional
//==========================================================================================================================================

function actualiza_perfil_transaccional()
{
	



	$sql =" SELECT MAX(fact_cliente.id_factura) AS ID_Credito
		FROM  fact_cliente
		WHERE fact_cliente.num_cliente = '".$this->num_cliente."' ";

	$rs=$this->db->Execute($sql);
	
	$id_credito = $rs->fields[0];



	$beneficiario_recursos			=($this->vectores_evaluacion[1] >0)?('Tercero'   ):('Propio'  );		
	$expediente_digital			=($this->vectores_evaluacion[2] >0)?('Incompleto'):('Completo');	
	$datos_cliente				=($this->vectores_evaluacion[3] >0)?('Incompleto'):('Completo');
	$persona_pol_expuesta			=($this->vectores_evaluacion[4] >0)?('Si'):('No');
	$vinculado_terrorismo_u_org_criminales	=($this->vectores_evaluacion[5] >0)?('Si'):('No');
	$vinculado_paraisos_fiscales		=($this->vectores_evaluacion[6] >0)?('Si'):('No');	
	$credito_solidario			=($this->vectores_evaluacion[7] >0)?('Si'):('No');	
	$motiva_sospechas_act_ilicitas		=($this->vectores_evaluacion[8] >0)?('Si'):('No');
	$actividad_economica_alto_riesgo	=($this->vectores_evaluacion[9] >0)?('Si'):('No');	
	$extranjero				=($this->vectores_evaluacion[10]>0)?('Si'):('No');	
	
	
	$politicamente_expuesta_extranjera	=($this->vectores_evaluacion[11]>0)?('Si'):('No');	
	$estado_de_la_republica_de_alto_riesgo	=($this->vectores_evaluacion[12]>0)?('Si'):('No');	
	$ciudad_alto_riesgo			=($this->vectores_evaluacion[13]>0)?('Si'):('No');	
	$codigo_postal_alto_riesgo		=($this->vectores_evaluacion[14]>0)?('Si'):('No');	
	$giro_de_negocio_con_alto_riesgo	=($this->vectores_evaluacion[15]>0)?('Si'):('No');;	
	$ingresos_provenientes_de_un_tercero	=($this->vectores_evaluacion[16]>0)?('Si'):('No');	



	//-----------------------------------------------------------------------------------------
	// Backup del perfil anterior
	//-----------------------------------------------------------------------------------------


	$sql = " UPDATE pld_perfil_transaccional_log 
		 SET 	pld_perfil_transaccional_log.Historic_ord = (pld_perfil_transaccional_log.Historic_ord + 1 ) 
		 WHERE	pld_perfil_transaccional_log.ID_Cliente = '".$this->id_cliente."'  ";

	$this->db->Execute($sql);

	//-----------------------------------------------------------------------------------------


	$sql =" INSERT IGNORE INTO pld_perfil_transaccional_log
		(
			 ID_Perfil,
			 Historic_ord,
			 
			 ID_Cliente,
			 ID_Credito,
			 Ultima_Actualizacion,
			 ID_User,
			 Usuario_Nombre,
			 ID_Promotor,
			 ID_Sucursal,
			 ID_Tipocredito,
			 ID_Tipo_regimen,
			 ID_Actividad,
			 ID_Giro_negocio,
			 Monto_Solicitado,
			 Ingresos_Netos_Mes,
			 Num_Operaciones_Mes,
			 Oper_Efectivo_MN,
			 Oper_Efectivo_ME,
			 Oper_TCredito,
			 Oper_Cheques_Transfer,
			 Origen_recursos,
			 Beneficiario_Recursos,
			 Expediente_Digital,
			 Datos_Cliente,
			 Vinculado_Terrorismo_u_Org_Criminales,
			 Vinculado_Paraisos_Fiscales,
			 Credito_Solidario,
			 Actividad_Economica_Alto_Riesgo,
			 Extrangero,
			 Persona_Pol_Expuesta,
			 Giro_Alto_Riesgo,
			 Estado_Republica_Alto_Riesgo,
			 Ciudad_Alto_Riesgo,
			 CP_Alto_Riesgo,
			 Persona_Pol_Expuesta_Extranjera,
			 Motiva_Sospechas_Act_Ilicitas,
			 ID_Tipo_Operacion_Inusual,
			 Telefono,
			 Num_celular,
			 CP,
			 Colonia,
			 Estado,
			 Ciudad,
			 Poblacion,
			 Calle,
			 Num,
			 Interior,
			 Notas_Sobre_Cliente


		)
		(
			SELECT	 NULL AS ID_Perfil,
				 1    AS Historic_ord,
				 ID_Cliente,
				 ID_Credito,
				 Ultima_Actualizacion,
				 ID_User,
				 Usuario_Nombre,
				 ID_Promotor,
				 ID_Sucursal,
				 ID_Tipocredito,
				 ID_Tipo_regimen,
				 ID_Actividad,
				 ID_Giro_negocio,
				 Monto_Solicitado,
				 Ingresos_Netos_Mes,
				 Num_Operaciones_Mes,
				 Oper_Efectivo_MN,
				 Oper_Efectivo_ME,
				 Oper_TCredito,
				 Oper_Cheques_Transfer,
				 Origen_recursos,
				 Beneficiario_Recursos,
				 Expediente_Digital,
				 Datos_Cliente,
				 Vinculado_Terrorismo_u_Org_Criminales,
				 Vinculado_Paraisos_Fiscales,
				 Credito_Solidario,
				 Actividad_Economica_Alto_Riesgo,
				 Extrangero,
				 Persona_Pol_Expuesta,
				 Giro_Alto_Riesgo,
				 Estado_Republica_Alto_Riesgo,
				 Ciudad_Alto_Riesgo,
				 CP_Alto_Riesgo,
				 Persona_Pol_Expuesta_Extranjera,
				 Motiva_Sospechas_Act_Ilicitas,
				 ID_Tipo_Operacion_Inusual,
				 Telefono,
				 Num_celular,
				 CP,
				 Colonia,
				 Estado,
				 Ciudad,
				 Poblacion,
				 Calle,
				 Num,
				 Interior,
				 Notas_Sobre_Cliente				
				
				
			FROM pld_perfil_transaccional


			WHERE ID_Cliente = '".$this->id_cliente."'  
		) ";

	$rs=$this->db->Execute($sql);



	//-----------------------------------------------------------------------------------------
	// Nuevo perfil 
	//-----------------------------------------------------------------------------------------


	$existe_actualmente_un_perfil = 0;
	
	$sql =" SELECT COUNT(*)
		FROM  pld_perfil_transaccional
		WHERE pld_perfil_transaccional.ID_Cliente = '".$this->num_cliente."'  ";
		
	$rs=$this->db->Execute($sql);
	
	$existe_actualmente_un_perfil = $rs->fields[0];
	
	
	
	
	if($existe_actualmente_un_perfil>0)
	{
	
		$sql =" UPDATE  pld_perfil_transaccional AS PF

			INNER JOIN clientes       ON PF.ID_Cliente 		 = clientes.ID_Cliente				
			INNER JOIN solicitud_plvd ON solicitud_plvd.ID_Solicitud = clientes.ID_Solicitud
			INNER JOIN fact_cliente   ON fact_cliente.num_cliente    = clientes.Num_cliente AND fact_cliente.id_factura = '".$id_credito."'
			LEFT JOIN  promo_ventas   ON promo_ventas.Num_compra     = fact_cliente.num_compra 

		
			SET				
				PF.ID_Credito					= '".$id_credito."',			
				PF.ID_User					= '".$_SESSION['ID_USR']."', 
				PF.Usuario_Nombre				= '".$_SESSION['NOM_USR']."',	
				PF.ID_Promotor					= promo_ventas.ID_Promo,	
				PF.ID_Sucursal					= fact_cliente.ID_Sucursal,	
				PF.ID_Tipocredito				= fact_cliente.ID_Tipocredito,					
				PF.ID_Tipo_regimen				= clientes.Regimen, 					 			
				PF.ID_Actividad					= solicitud_plvd.ID_actividad_economica, 			 
				PF.ID_Giro_negocio				= IFNULL(solicitud_plvd.ID_Giro_negocio,0),		 
				PF.Monto_Solicitado				= fact_cliente.Capital, 					 
				PF.Ingresos_Netos_Mes				= IFNULL(solicitud_plvd.Ingresos_netos,0),			 
				PF.Num_Operaciones_Mes				= IFNULL(solicitud_plvd.Num_oper_ventas,0),		 
				PF.Oper_Efectivo_MN				= IFNULL(solicitud_plvd.Efectivo_mnd_nac,'No'),     	 
				PF.Oper_Efectivo_ME				= IFNULL(solicitud_plvd.Efectivo_mnd_extrj,'No'),   	 
				PF.Oper_TCredito				= IFNULL(solicitud_plvd.Tarjeta_credito,'No'),      	 
				PF.Oper_Cheques_Transfer			= IFNULL(solicitud_plvd.Cheque_transferencia,'No'), 	 
				PF.Origen_recursos				= solicitud_plvd.Origen_recursos,      			 
				PF.Beneficiario_Recursos			= '".$beneficiario_recursos			."',	 
				PF.Expediente_Digital				= '".$expediente_digital			."',	 
				PF.Datos_Cliente				= '".$datos_cliente				."',	 
				PF.Persona_Pol_Expuesta				= '".$persona_pol_expuesta			."',	 
				PF.Vinculado_Terrorismo_u_Org_Criminales	= '".$vinculado_terrorismo_u_org_criminales	."',	 
				PF.Vinculado_Paraisos_Fiscales			= '".$vinculado_paraisos_fiscales		."',	 
				PF.Credito_Solidario				= '".$credito_solidario				."',	 
				PF.Extrangero					= '".$extranjero				."',	 
				PF.Actividad_Economica_Alto_Riesgo		= '".$actividad_economica_alto_riesgo		."',	 
				PF.Ciudad_Alto_Riesgo				= '".$ciudad_alto_riesgo			."',				   
				PF.CP_Alto_Riesgo				= '".$codigo_postal_alto_riesgo			."',
				PF.Persona_Pol_Expuesta_Extranjera		= '".$politicamente_expuesta_extranjera		."',
				PF.Estado_Republica_Alto_Riesgo			= '".$estado_de_la_republica_de_alto_riesgo	."',
				PF.Giro_Alto_Riesgo				= '".$giro_de_negocio_con_alto_riesgo		."',
				PF.Telefono					= solicitud_plvd.Telefono, 				
				PF.CP						= solicitud_plvd.CP,      					
				PF.Colonia					= solicitud_plvd.Colonia, 					
				PF.Estado					= solicitud_plvd.Estado,  					
				PF.Ciudad					= solicitud_plvd.Ciudad,  					
				PF.Poblacion					= solicitud_plvd.Poblacion, 				
				PF.Calle					= solicitud_plvd.Calle,     				
				PF.Num						= solicitud_plvd.Num,       				
				PF.Interior					= solicitud_plvd.Interior  				
			
			
			
			WHERE PF.ID_Cliente = '".$this->id_cliente."'  ";
				
		  
			$rs=$this->db->Execute($sql);


	}
	else
	{
	
		$sql =" REPLACE INTO pld_perfil_transaccional
		(
				ID_Cliente,				
				ID_Credito,							
				Ultima_Actualizacion,			
				ID_User,				
				Usuario_Nombre,				
				ID_Promotor,				
				ID_Sucursal,				
				ID_Tipocredito,				
				ID_Tipo_regimen,			
				ID_Actividad,				
				ID_Giro_negocio,			
				Monto_Solicitado,			
				Ingresos_Netos_Mes,			
				Num_Operaciones_Mes,			
				Oper_Efectivo_MN,			
				Oper_Efectivo_ME,			
				Oper_TCredito,				
				Oper_Cheques_Transfer,			
				Origen_recursos,			
				Beneficiario_Recursos,			
				Expediente_Digital,			
				Datos_Cliente,				
				Persona_Pol_Expuesta,			
				Vinculado_Terrorismo_u_Org_Criminales,
				Vinculado_Paraisos_Fiscales,		
				Credito_Solidario,			
				Motiva_Sospechas_Act_Ilicitas,		
				Extrangero,				
				Actividad_Economica_Alto_Riesgo,	
				ID_Tipo_Operacion_Inusual,		
				Ciudad_Alto_Riesgo,			
				CP_Alto_Riesgo,				
				Persona_Pol_Expuesta_Extranjera,	
				Estado_Republica_Alto_Riesgo,		
				Giro_Alto_Riesgo,			
				Telefono,				
				CP,					
				Colonia,				
				Estado,					
				Ciudad,					
				Poblacion,				
				Calle,					
				Num,					
				Interior
		   )
		   (
		    SELECT 		clientes.ID_Cliente					AS ID_Cliente,			 			    
					fact_cliente.id_factura					AS ID_Credito,			 			    
						now() 						AS Ultima_Actualizacion,				    
					'".$_SESSION['ID_USR']."' 				AS ID_User,			 				    
					'".$_SESSION['NOM_USR']."' 				AS Usuario_Nombre,			 				    
					promo_ventas.ID_Promo 					AS ID_Promotor,			 			    
					fact_cliente.ID_Sucursal				AS ID_Sucursal,			 				    
					fact_cliente.ID_Tipocredito				AS ID_Tipocredito,			 				    
					clientes.Regimen 					AS ID_Tipo_regimen,			 			    
					solicitud_plvd.ID_actividad_economica 			AS ID_Actividad,			 				    
					IFNULL(solicitud_plvd.ID_Giro_negocio,0)		AS ID_Giro_negocio,			 				    
					fact_cliente.Capital 					AS Monto_Solicitado,			 			    
					IFNULL(solicitud_plvd.Ingresos_netos,0)			AS Ingresos_netos,							    
					IFNULL(solicitud_plvd.Num_oper_ventas,0)		AS Num_oper_ventas,							    
					IFNULL(solicitud_plvd.Efectivo_mnd_nac,'No')     	AS Oper_Efectivo_MN,							    
					IFNULL(solicitud_plvd.Efectivo_mnd_extrj,'No')   	AS Oper_Efectivo_ME,							    
					IFNULL(solicitud_plvd.Tarjeta_credito,'No')      	AS Oper_TCredito,							    
					IFNULL(solicitud_plvd.Cheque_transferencia,'No') 	AS Oper_Cheques_Transfer,						    
					solicitud_plvd.Origen_recursos      			AS Origen_recursos,							    
					'".$beneficiario_recursos			."'	AS Beneficiario_Recursos,						    
					'".$expediente_digital				."'	AS Expediente_Digital,			 			    
					'".$datos_cliente				."'	AS Datos_Cliente,			 			    
					'".$persona_pol_expuesta			."'	AS Persona_Pol_Expuesta,						    
					'".$vinculado_terrorismo_u_org_criminales	."'	AS Vinculado_Terrorismo_u_Org_Criminales,				    
					'".$vinculado_paraisos_fiscales			."'	AS Vinculado_Paraisos_Fiscales,						    
					'".$credito_solidario				."'	AS Credito_Solidario,			 			    
					'".$motiva_sospechas_act_ilicitas		."'	AS Motiva_Sospechas_Act_Ilicitas,					    
					'".$extranjero					."'	AS Extrangero,			 		    
					'".$actividad_economica_alto_riesgo		."'	AS Actividad_Economica_Alto_Riesgo,					    
					0							AS ID_Tipo_Operacion_Inusual,	
					
					
					'".$ciudad_alto_riesgo				."'	AS Ciudad_Alto_Riesgo,									    
					'".$codigo_postal_alto_riesgo			."'	AS CP_Alto_Riesgo,										    
					'".$politicamente_expuesta_extranjera		."'	AS Persona_Pol_Expuesta_Extranjera,					 		    
					'".$estado_de_la_republica_de_alto_riesgo	."'	AS Estado_Republica_Alto_Riesgo,					 			    
					'".$giro_de_negocio_con_alto_riesgo		."'	AS Giro_Alto_Riesgo,
					solicitud_plvd.Telefono 				AS Telefono, 			 	 			    
					solicitud_plvd.CP      					AS CP,							    
					solicitud_plvd.Colonia 					AS Colonia,				 			    
					solicitud_plvd.Estado  					AS Estado,							    
					solicitud_plvd.Ciudad  					AS Ciudad,		 					    
					solicitud_plvd.Poblacion 				AS Poblacion,			 						
					solicitud_plvd.Calle     				AS Calle,			 						
					solicitud_plvd.Num       				AS Num,			 						
					solicitud_plvd.Interior  				AS Interior									
				
				

			FROM clientes

			INNER JOIN solicitud_plvd ON solicitud_plvd.ID_Solicitud = clientes.ID_Solicitud
			INNER JOIN fact_cliente   ON fact_cliente.num_cliente    = clientes.Num_cliente AND fact_cliente.id_factura = '".$id_credito."'
			LEFT JOIN  promo_ventas   ON promo_ventas.Num_compra     = fact_cliente.num_compra 

		
		  ) ";
		  
		  
	$rs=$this->db->Execute($sql);
	
	}
	

	$this->actualiza_evaluacion_riesgo();

}
//==========================================================================================================================================
//	Actualiza Perfil de Riesgo
//==========================================================================================================================================

function actualiza_evaluacion_riesgo()
{
	$this->evalua_estado_vectores();

	$sql =" REPLACE INTO pld_clasificacion_cliente 
			(ID_Cliente, 
			 Riesgo, 			
     			 Puntos_Alcanzados,
     			 Parametro_Evaluacion,			
			 Fuente_Riesgo) 
		VALUES
			('".$this->num_cliente."',
			 '".$this->riesgo_tipo."',   			 
			 '".$this->riesgo_ponderado."', 
			 '".$this->parametro_minimo_riesgo_alto."', 			 
			 'Perfil Transaccional') ";
	$rs=$this->db->Execute($sql);
}
//=====================================================









}

?>