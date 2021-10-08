<?


class TCirculoReporte
{

 var $fecha_cierre;
 var $fecha_ini;
 
 var $fecha_extraccion;
 
 var $xml_header;
 
 var $clave_otorgante;

 var $nombre_otorgante;
 
 var $nota_otorgante;

 var $error_num = 0;
 
 var $reporte_xml = "";
 
 var $error_msg = array();


//==========================================================================


function TCirculoReporte(&$db )
{

	$this->db = $db; 

}

//==========================================================================

function Actulizar_Datos_Reporte()
{

	$this->verificar_datos_cabecera();
	
	$this->limpiar_datos_existentes();

	$this->agregar_nuevos_registros();
	
	$this->actualizar_fechas_cierre();
	
	$this->actualizar_cifras_control();
	
	if($this->error_num >0 ) return($this->error_num);
	
}
//==========================================================================

function verificar_datos_cabecera()
{

	$sql = " SELECT buro_circulo_encabezado.ClaveOtorgante,
			buro_circulo_encabezado.NombreOtorgante,
			buro_circulo_encabezado.IdentificadorDeMedio,
			buro_circulo_encabezado.FechaExtraccion,
			buro_circulo_encabezado.NotaOtorgante

		 FROM buro_circulo_encabezado

		 LIMIT 1 ";

	 $rs=$this->db->Execute($sql);
	 
	 $this->clave_otorgante 	= $rs->fields['ClaveOtorgante'];
	 $this->nombre_otorgante 	= $rs->fields['NombreOtorgante'];
	 $this->IdentificadorDeMedio 	= $rs->fields['IdentificadorDeMedio'];
	 $this->fecha_cierre 	        = $rs->fields['FechaExtraccion'];
	 $this->nota_otorgante 		= $rs->fields['NotaOtorgante'];
	 
	 
	 if(strlen($this->clave_otorgante) < 10)
	 {
	 	$this->error_num++;
	 	$this->error_msg[] = " La clave del otorgante debe tener 10 posiciones.";
	 }
	 else
	 {
	 	$clave_otorgante_1 = substr($this->clave_otorgante, 0, 5);
	 
	 	$clave_otorgante_2 = substr($this->clave_otorgante, 6, 9);
	 	
	 	if(! is_numeric($clave_otorgante_1) )
	 	{
			$this->error_num++;
			$this->error_msg[] = " Los primeras 6 posiciones de la clave del otorgante deben ser numéricas.";
	 	}
	 
	 }
	 

	list($yy, $mm, $dd) = explode("-",$this->fecha_cierre);
	
	if(! checkdate( $mm, $dd, $yy) )
	{
	
	 	$this->error_num++;
	 	$this->error_msg[] = " Fecha inválida. ";
	
	}
	else
	{
		$this->fecha_extraccion = date("Ymd",mktime(0,0,0,$mm,$dd,$yy));
	
	}
	
	 
	 
	 
	return; 
	 
	 
	 
}
//==========================================================================
function limpiar_datos_existentes()
{

//	$sql = "##DELETE FROM buro_circulo_encabezado;


	$sql = "DELETE FROM buro_circulo_base ";
	$this->db->Execute($sql);
	
	$sql = "DELETE FROM buro_circulo_persona_cuenta ";
	$this->db->Execute($sql);
	
	$sql = "DELETE FROM buro_circulo_persona_domicilio ";
	$this->db->Execute($sql);
	
	$sql = "DELETE FROM buro_circulo_persona_empleo ";
	$this->db->Execute($sql);
	
	$sql = "DELETE FROM buro_circulo_persona_nombre ";
	$this->db->Execute($sql);
	
	$sql = "DELETE FROM buro_circulo_cifras_control ";
	$this->db->Execute($sql);
}
//==========================================================================

function agregar_nuevos_registros()
{





	$sql = "INSERT IGNORE buro_circulo_seleccion
		(ID_Credito, Num_Cliente, ID_Grupo, Ciclo, Activo, Registro, Usuario_Agrega)
		(
			SELECT fact_cliente.id_factura AS ID_Credito,
			       clientes.Num_cliente    AS Num_Cliente,
			       grupo_solidario_integrantes.ID_grupo_soli AS ID_Grupo,
			       grupo_solidario_integrantes.Ciclo_gpo     AS Ciclo,
			       'Si' AS Activo,
			       now() AS Registro,
			       'NOM_USR' AS Usuario_Agrega


			FROM fact_cliente
			INNER JOIN clientes ON clientes.Num_cliente = fact_cliente.num_cliente
			 LEFT JOIN grupo_solidario_integrantes ON grupo_solidario_integrantes.id_factura = fact_cliente.id_factura



			WHERE clientes.Regimen = 'PF' AND
			      fact_cliente.Fecha_Inicio <= '".$this->fecha_cierre."'

			ORDER BY fact_cliente.id_factura
		) ";
	
	
	$this->db->Execute($sql);















	if($this->error_num >0 ) return($this->error_num);


	$sql = "DROP TEMPORARY TABLE IF EXISTS tmp_circulo_nuevos_registros ";
	$this->db->Execute($sql);



	$sql = "CREATE TEMPORARY TABLE tmp_circulo_nuevos_registros 
		(
			ID_Credito 	INT(10) UNSIGNED NOT NULL,
			Num_Cliente 	INT(10) UNSIGNED NOT NULL,
			INDEX ID_Credito  (ID_Credito  ),
			INDEX Num_Cliente (Num_Cliente )
		)
		ENGINE=MyISAM";		
	$this->db->Execute($sql);
	
	
	list($AA, $MM, $DD) = explode("-",$this->fecha_cierre);
	
	$this->fecha_ini = date("Y-m-d",mktime(0,0,0,$MM,($DD-7),$AA));
	
	
	
	$sql =" INSERT INTO tmp_circulo_nuevos_registros (ID_Credito, Num_Cliente)
		(
			SELECT fact_cliente.id_factura,
			       fact_cliente.num_cliente 

			FROM fact_cliente

			LEFT JOIN  buro_circulo_base		ON buro_circulo_base.ID_Credito = fact_cliente.id_factura
			
			INNER JOIN cierre_contable_log		ON cierre_contable_log.Fecha_Cierre BETWEEN '".($this->fecha_ini)."' and '".($this->fecha_cierre)."'
									
			INNER JOIN cierre_contable_saldos	ON  cierre_contable_saldos.ID_Cierre  = cierre_contable_log.ID_Cierre
			                                        AND cierre_contable_saldos.ID_Factura = fact_cliente.ID_Factura

			INNER JOIN buro_circulo_seleccion 	ON  buro_circulo_seleccion.ID_Credito = fact_cliente.ID_Factura 
                       						AND buro_circulo_seleccion.Activo     = 'Si'
			                     
			WHERE  buro_circulo_base.Registro IS NULL 
			
			
                 	GROUP BY  cierre_contable_saldos.ID_Factura 		    
			      
		)  "; 
//debug($sql);
	$this->db->Execute($sql);
	
	
	
	$sql = "INSERT INTO buro_circulo_base (ID_Credito, Primera_Aparicion)
		(
			SELECT   tmp_circulo_nuevos_registros.ID_Credito	AS ID_Credito,
				 '".$this->fecha_cierre."' 			AS Primera_Aparicion
			FROM     tmp_circulo_nuevos_registros
		) ";
	
	$this->db->Execute($sql);	
	
	
	
//die();	

	$sql = "REPLACE INTO buro_circulo_persona_nombre
		(
			SELECT	buro_circulo_base.ID_Credito,
				clientes_datos.Num_cliente,
				clientes_datos.Ap_paterno,
				IF((clientes_datos.Ap_materno =''),'NO PROPORCIONADO',clientes_datos.Ap_materno         ) AS Ap_materno,
				NULL AS ApellidoAdicional,
				CONCAT(clientes_datos.Nombre,' ',clientes_datos.NombreI ) AS Nombres,
				clientes_datos.Fecha_nacimiento,
				clientes_datos.RFC,
				NULL AS CURP,
				'MX' AS Nacionalidad,
				NULL AS Residencia,
				NULL AS NumeroLicenciaConducir,

				'S'  AS EstadoCivil,

				CASE clientes_datos.SEXO
					WHEN 'Femenino'  THEN 'F'
					WHEN 'Masculino' THEN 'M'
					ELSE clientes_datos.SEXO
				END AS Sexo,

				NULL ClaveElectorIFE,

				0 AS NumeroDependientes,

				NULL AS FechaDefuncion,
				NULL AS IndicadorDefuncion,
				'PF' AS TipoPersona 



			FROM buro_circulo_base



			INNER JOIN fact_cliente   ON fact_cliente.id_factura    = buro_circulo_base.ID_Credito 
			INNER JOIN clientes_datos ON clientes_datos.Num_cliente = fact_cliente.num_cliente


			WHERE buro_circulo_base.Primera_Aparicion = '".$this->fecha_cierre."'

			ORDER BY buro_circulo_base.ID_Credito 

		)	";
	$this->db->Execute($sql);			
		

	
	$sql =" REPLACE INTO buro_circulo_persona_domicilio 
		(
			SELECT  	buro_circulo_base.ID_Credito				AS ID_Credito,
					clientes_datos.Num_cliente				AS Num_Cliente,
					CONCAT(clientes_datos.Calle,' ',clientes_datos.Num)	AS Direccion,
					clientes_datos.Colonia   				AS ColoniaPoblacion,
					clientes_datos.Poblacion 				AS DelegacionMunicipio,
					clientes_datos.Ciudad    				AS Ciudad,
					buro_circulo_cat_estados.Clave    			AS Estado,
					clientes_datos.CP        				AS CP,
					NULL 							AS FechaResidencia,
					clientes_datos.Telefono  				AS NumeroTelefono,
					NULL 							AS TipoDomicilio,
					NULL 							AS TipoAsentamiento

			FROM buro_circulo_base

			INNER JOIN fact_cliente   		ON fact_cliente.id_factura    			= buro_circulo_base.ID_Credito 

			INNER JOIN clientes_datos 		ON clientes_datos.Num_cliente 			= fact_cliente.num_cliente

			LEFT  JOIN buro_circulo_cat_estados 	ON buro_circulo_cat_estados.Estado_Republica	= clientes_datos.Estado

			WHERE buro_circulo_base.Primera_Aparicion = '".$this->fecha_cierre."'


			ORDER BY buro_circulo_base.ID_Credito 

		) ";
	$this->db->Execute($sql);	




	$sql =" 
	
	REPLACE buro_circulo_persona_cuenta
	(
	
		SELECT  buro_circulo_base.ID_Credito,
			fact_cliente.Num_Cliente,
			buro_circulo_encabezado.ClaveOtorgante,

			buro_circulo_encabezado.NombreOtorgante,

			buro_circulo_base.ID_Credito,

			'I'	AS TipoResponsabilidad,

			'F'	AS TipoCuenta,

			IF(fact_cliente.ID_Tipocredito =2,'GS','PP') AS TipoContrato,

			'MX'	AS ClaveUnidadMonetaria,

			NULL AS ValorActivoValuacion,

			fact_cliente.plazo AS NumeroPagos,

			CASE(fact_cliente.vencimiento)
				WHEN 'Semanas'    THEN 'S'
				WHEN 'Quincenas'  THEN 'Q'                                          
				WHEN 'Meses'      THEN 'M'            
				WHEN 'Anios'      THEN 'A'
				WHEN 'Catorcenal' THEN 'C'
				WHEN 'Dias'	  THEN 'U'                                          
			END  AS FrecuenciaPagos,


			 IF(factura_cliente_liquidacion.Fecha IS NULL,
				TRUNCATE(IF( cierre_contable_saldos.Adeudo_Total  < 1, 
					0, 
					LEAST(cierre_contable_saldos.Adeudo_Total,fact_cliente.Renta)),0), 
			 	0)										AS MontoPagar,

			fact_cliente.fecha_exp 									AS FechaAperturaCuenta,

			MAX(pagos.Fecha)       									AS FechaUltimoPago,

			fact_cliente.fecha_exp 									AS FechaUltimaCompra,

			date(factura_cliente_liquidacion.Fecha) 						AS FechaCierreCuenta,

			cierre_contable_log.Fecha_Cierre 							AS FechaCorte,

			NULL 											AS Garantia,

			TRUNCATE(fact_cliente.Capital,0)							AS CreditoMaximo,

			 IF(factura_cliente_liquidacion.Fecha IS NULL,
				TRUNCATE((cierre_contable_saldos.Adeudo_Total) ,0     ),
			 	0)										AS SaldoActual,

			TRUNCATE(fact_cliente.Capital,0)							AS  LimiteCredito,
			 
			 IF(factura_cliente_liquidacion.Fecha IS NULL,
				TRUNCATE(cierre_contable_saldos.Saldo_Total_Vencido,0),
			 	0)										AS SaldoVencido,

			 IF(factura_cliente_liquidacion.Fecha IS NULL,
				IF(TRUNCATE(cierre_contable_saldos.Saldo_Total_Vencido,0)=0,
					0,
					cierre_contable_saldos.Num_Cuotas_Vencidas),
			 	0)										AS  NumeroPagosVencidos,

			 
			 IF(factura_cliente_liquidacion.Fecha IS NOT NULL,' V',
				IF((TRUNCATE(cierre_contable_saldos.Saldo_Total_Vencido,0)= 0),' V',
				 IF( cierre_contable_saldos.Num_Cuotas_Vencidas <= 9,
				    CONCAT('0',cierre_contable_saldos.Num_Cuotas_Vencidas),
				    cierre_contable_saldos.Num_Cuotas_Vencidas
				    ))) 									AS PagoActual,



			NULL 											AS HistoricoPagos,

			'' 											AS ClavePrevencion,

			COUNT(pagos.ID_Pago) 									AS TotalPagosReportados,

			NULL 											AS ClaveAnteriorOtorgante,

			NULL 											AS NombreAnteriorOtorgante,

			NULL 											AS NumeroCuentaAnterior			
			

		FROM    fact_cliente 

		INNER JOIN buro_circulo_base	ON	fact_cliente.id_factura			= buro_circulo_base.ID_Credito


		LEFT JOIN cierre_contable_log	ON	cierre_contable_log.Fecha_Cierre = '".$this->fecha_cierre."'

		LEFT JOIN   cierre_contable_saldos 	ON	fact_cliente.id_factura			= cierre_contable_saldos.id_factura 
                                   			AND	cierre_contable_saldos.ID_Cierre  	= cierre_contable_log.ID_Cierre

		LEFT JOIN factura_cliente_liquidacion ON factura_cliente_liquidacion.ID_Factura = fact_cliente.id_factura and
							       date(factura_cliente_liquidacion.Fecha) <= cierre_contable_log.Fecha_Cierre 



		LEFT JOIN  buro_circulo_encabezado  	ON	buro_circulo_encabezado.ID_Credito 	= 1			                           

		LEFT JOIN  pagos 			ON	pagos.Num_compra = fact_cliente.num_compra 
							AND	pagos.activo = 'S' 
							AND	pagos.Fecha <= cierre_contable_log.Fecha_Cierre

		GROUP BY fact_cliente.id_factura

		ORDER BY fact_cliente.id_factura 

	) ";
	


	
	$this->db->Execute($sql);		



//===========================================================================================
/*

Hola Enrique

Buenas tardes

Te comento que Conequity me indica que el personal de Circulo de crédito les pidió que en su reporte se hicieran los siguientes cambios:

1.	En Monto Individual de cada integrante debe de ir el Monto grupal 
2.	En tipo de responsabilidad cambiar a la letra O (obligado solidario)

Podríamos realizar los cambios, o ellos deben de hacer algún cambio en el sistema?

Annel Valle <annel.valle@s2credit.com>
*/
//===========================================================================================

$sql =" DROP   TABLE IF EXISTS _buro_circulo_persona_cuenta_solidario ";
$this->db->Execute($sql);

//------------------------------------------------------------------------------


$sql =" DROP  TEMPORARY   TABLE IF EXISTS _buro_circulo_persona_cuenta_solidario ";
$this->db->Execute($sql);

//------------------------------------------------------------------------------

$sql =" CREATE TEMPORARY TABLE _buro_circulo_persona_cuenta_solidario 
       (
		ID_Grupo 		INT(10) UNSIGNED NOT NULL DEFAULT '0',
		Ciclo 			INT(10) UNSIGNED NOT NULL DEFAULT '0',
		CreditoMaximo 		DOUBLE(17,0) NULL DEFAULT NULL,
		SaldoActual 		DOUBLE(17,0) NULL DEFAULT NULL,
		LimiteCredito 		DOUBLE(17,0) NULL DEFAULT NULL,
		SaldoVencido 		DOUBLE(17,0) NULL DEFAULT NULL,
		NumeroPagosVencidos 	DECIMAL(20,0) NULL DEFAULT NULL,
		PagoActual 		CHAR(2) NULL DEFAULT NULL,
		INDEX ID_grupo_soli (ID_Grupo),
		INDEX Ciclo_gpo (Ciclo)
	)";
$this->db->Execute($sql);

//------------------------------------------------------------------------------

$sql ="INSERT INTO _buro_circulo_persona_cuenta_solidario
       (
	SELECT   grupo_solidario_integrantes.ID_grupo_soli			AS ID_Grupo, 
		 grupo_solidario_integrantes.Ciclo_gpo				AS Ciclo,

		TRUNCATE(SUM(fact_cliente.Capital),0)				AS CreditoMaximo,

		TRUNCATE(SUM(cierre_contable_saldos.Adeudo_Total) ,0     ) 	AS SaldoActual,

		TRUNCATE(SUM(fact_cliente.Capital),0)				AS  LimiteCredito,

		TRUNCATE(SUM(cierre_contable_saldos.Saldo_Total_Vencido),0)	AS SaldoVencido,

		IF(TRUNCATE(SUM(cierre_contable_saldos.Saldo_Total_Vencido),0)=0,
		   0,
		   MAX(cierre_contable_saldos.Num_Cuotas_Vencidas)) 		AS  NumeroPagosVencidos,


		IF((TRUNCATE(SUM(cierre_contable_saldos.Saldo_Total_Vencido),0)= 0),' V',
		 IF( MAX(cierre_contable_saldos.Num_Cuotas_Vencidas) <= 9,
		    CONCAT('0',(IF(MAX(cierre_contable_saldos.Num_Cuotas_Vencidas)=0,'1',MAX(cierre_contable_saldos.Num_Cuotas_Vencidas)))),
		    MAX(cierre_contable_saldos.Num_Cuotas_Vencidas)
		    ))	AS PagoActual


	FROM buro_circulo_persona_cuenta 

	INNER JOIN cierre_contable_log ON cierre_contable_log.Fecha_Cierre='".$this->fecha_cierre."'

	INNER JOIN cierre_contable_saldos ON  cierre_contable_saldos.ID_Cierre  = cierre_contable_log.ID_Cierre 
					  AND cierre_contable_saldos.ID_Factura = buro_circulo_persona_cuenta.ID_Credito

	INNER JOIN grupo_solidario_integrantes ON grupo_solidario_integrantes.id_factura = buro_circulo_persona_cuenta.ID_Credito
	INNER JOIN fact_cliente                ON fact_cliente.id_factura = buro_circulo_persona_cuenta.ID_Credito



	WHERE   fact_cliente.ID_Tipocredito = 2 
		

	GROUP BY grupo_solidario_integrantes.ID_grupo_soli, 
		 grupo_solidario_integrantes.Ciclo_gpo
	)";
$this->db->Execute($sql);


//------------------------------------------------------------------------------


$sql ="UPDATE buro_circulo_persona_cuenta

	INNER JOIN grupo_solidario_integrantes ON grupo_solidario_integrantes.id_factura = buro_circulo_persona_cuenta.ID_Credito
	INNER JOIN _buro_circulo_persona_cuenta_solidario ON _buro_circulo_persona_cuenta_solidario.ID_Grupo = grupo_solidario_integrantes.ID_grupo_soli AND
							    _buro_circulo_persona_cuenta_solidario.Ciclo = grupo_solidario_integrantes.Ciclo_gpo


	SET   buro_circulo_persona_cuenta.CreditoMaximo		=	_buro_circulo_persona_cuenta_solidario.CreditoMaximo,		
	      buro_circulo_persona_cuenta.SaldoActual		=	_buro_circulo_persona_cuenta_solidario.SaldoActual,				
	      buro_circulo_persona_cuenta.LimiteCredito		=	_buro_circulo_persona_cuenta_solidario.LimiteCredito,		 		
	      buro_circulo_persona_cuenta.SaldoVencido		=	_buro_circulo_persona_cuenta_solidario.SaldoVencido,		 		
	      buro_circulo_persona_cuenta.NumeroPagosVencidos	=	_buro_circulo_persona_cuenta_solidario.NumeroPagosVencidos,	 	
	      buro_circulo_persona_cuenta.PagoActual		=	_buro_circulo_persona_cuenta_solidario.PagoActual";
$this->db->Execute($sql);


//===================================================================================================================





































}

//==========================================================================

function actualizar_fechas_cierre()
{
	//===============================================================
	// Inicializar Fechas Cierre
	//===============================================================

	$sql ="UPDATE buro_circulo_persona_cuenta
		SET buro_circulo_persona_cuenta.FechaCierreCuenta = NULL ";
		
	$this->db->Execute($sql);


	//===============================================================
	// Cuentas saldadas
	//===============================================================
	$sql ="UPDATE buro_circulo_persona_cuenta

		INNER JOIN factura_cliente_liquidacion ON factura_cliente_liquidacion.ID_Factura = buro_circulo_persona_cuenta.ID_Credito

		SET    buro_circulo_persona_cuenta.FechaCierreCuenta =  DATE(factura_cliente_liquidacion.Fecha),

		       buro_circulo_persona_cuenta.ClavePrevencion = 'CC',

		       buro_circulo_persona_cuenta.SaldoActual  = 0,

		       buro_circulo_persona_cuenta.SaldoVencido = 0,

		       buro_circulo_persona_cuenta.MontoPagar   = 0,

		       buro_circulo_persona_cuenta.PagoActual = ' V' 
		       
		WHERE DATE(factura_cliente_liquidacion.Fecha) <= '".$this->fecha_cierre."' ";

	$this->db->Execute($sql);


	//===============================================================
	// Cuentas canceladas
	//===============================================================



	$sql ="UPDATE buro_circulo_persona_cuenta

	       INNER JOIN  fact_cliente_cancelacion  ON fact_cliente_cancelacion.ID_Factura = buro_circulo_persona_cuenta.ID_Credito

	       SET     buro_circulo_persona_cuenta.FechaCierreCuenta =  DATE(fact_cliente_cancelacion.Fecha_cancelacion),

		       buro_circulo_persona_cuenta.ClavePrevencion = 'CC',

		       buro_circulo_persona_cuenta.SaldoActual  = 0,

		       buro_circulo_persona_cuenta.SaldoVencido = 0,

		       buro_circulo_persona_cuenta.MontoPagar   = 0,

		       buro_circulo_persona_cuenta.PagoActual = ' V' 

		WHERE DATE(fact_cliente_cancelacion.Fecha_cancelacion) <= '".$this->fecha_cierre."' ";		       

	$this->db->Execute($sql);


	//===============================================================
	// Cuentas solidarias en mora
	//===============================================================
	$sql ="UPDATE buro_circulo_persona_cuenta

		INNER JOIN fact_cliente ON fact_cliente.id_factura = buro_circulo_persona_cuenta.ID_Credito

		SET    buro_circulo_persona_cuenta.ClavePrevencion = 'IM'
		       		       
		WHERE fact_cliente.ID_Tipocredito = 2		   AND
		
		      buro_circulo_persona_cuenta.SaldoActual  > 0 AND 
		
		      buro_circulo_persona_cuenta.SaldoVencido > 0 AND
		
		      buro_circulo_persona_cuenta.MontoPagar   > 0 AND
		
		      buro_circulo_persona_cuenta.PagoActual != ' V' ";

	$this->db->Execute($sql);


	//===============================================================
	// Cuentas a reportar
	//===============================================================

	$sql =" UPDATE buro_circulo_base 
		SET buro_circulo_base.Reportar = 'Si' ";
	$this->db->Execute($sql);
	
	//--------------------------------------------------------------
	

	$sql =" UPDATE buro_circulo_base
		INNER JOIN buro_circulo_persona_cuenta ON buro_circulo_persona_cuenta.ID_Credito = buro_circulo_base.ID_Credito

		SET buro_circulo_base.Reportar = 'No'

		WHERE      buro_circulo_persona_cuenta.FechaCierreCuenta IS NOT NULL 
		       AND buro_circulo_persona_cuenta.FechaCierreCuenta < '".($this->fecha_ini)."' ";
	$this->db->Execute($sql);       
	
	//--------------------------------------------------------------
	
	$sql =" UPDATE buro_circulo_base

		SET    buro_circulo_base.Ultima_Aparicion = '".$this->fecha_cierre."' 

		WHERE  buro_circulo_base.Reportar = 'Si'";
	$this->db->Execute($sql);  
	
	//--------------------------------------------------------------
	
 
}

//==========================================================================

function actualizar_cifras_control()
{

	$sql =" DELETE FROM buro_circulo_cifras_control ";
	$this->db->Execute($sql);	
	
	$sql =" INSERT INTO buro_circulo_cifras_control
		(
			SELECT 	SUM(buro_circulo_persona_cuenta.SaldoActual) 	AS TotalSaldosActuales,
				SUM(buro_circulo_persona_cuenta.SaldoVencido) 	AS TotalSaldosVencidos,
				COUNT(buro_circulo_persona_cuenta.ID_Credito ) AS TotalElementosNombreReportados,
				COUNT(buro_circulo_persona_cuenta.ID_Credito ) AS TotalElementosDireccionReportados,
				0                                              AS TotalElementosEmpleoReportados,
				COUNT(buro_circulo_persona_cuenta.ID_Credito ) AS TotalElementosCuentaReportados,
				buro_circulo_encabezado.NombreOtorgante        AS NombreOtorgante,
				
				CONCAT(empresas.Direccion,', Col. ', 
				       empresas.Colonia,', Municipio ', 
				       empresas.Poblacion,', ',
				       empresas.Estado,', CP ',empresas.CP) 	AS DomicilioDevolucion




			FROM  buro_circulo_persona_cuenta
			
			LEFT  JOIN  buro_circulo_encabezado  	ON  buro_circulo_encabezado.ID_Credito	= 1
			LEFT  JOIN  empresas			ON  empresas.ID_Empresa > 0
			INNER JOIN  buro_circulo_base 		ON  buro_circulo_base.ID_Credito = buro_circulo_persona_cuenta.ID_Credito 
								AND buro_circulo_base.Reportar = 'Si'     

			GROUP BY buro_circulo_base.Reportar         
		)  ";                  				
	$this->db->Execute($sql);

}
//==========================================================================


function Genera_Formato_XML()
{
	$this->reporte_xml  = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";

	$this->reporte_xml .= "<Carga xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"/Carga.xsd\">\n";

	$this->reporte_xml .= "\n\n\n";
	
	$this->reporte_xml .= "<Encabezado>\n";
	$this->reporte_xml .= "\t<ClaveOtorgante>".$this->clave_otorgante."</ClaveOtorgante>\n";
	$this->reporte_xml .= "\t<NombreOtorgante>".$this->nombre_otorgante."</NombreOtorgante>\n";
	$this->reporte_xml .= "\t<IdentificadorDeMedio>1</IdentificadorDeMedio>\n";
	$this->reporte_xml .= "\t<FechaExtraccion>".$this->fecha_extraccion."</FechaExtraccion>\n";
	$this->reporte_xml .= "\t<NotaOtorgante>".$this->nota_otorgante."</NotaOtorgante>\n";
	$this->reporte_xml .= "</Encabezado>\n\n";

	$this->reporte_xml .= "\n\n\n";

	$this->reporte_xml .= "<Personas>\n";


	$this->get_xml_personas();



	$this->reporte_xml .= "</Personas>\n";
	
	$this->reporte_xml .= "\n\n\n";
	
	
	$this->get_xml_cifras_control();
	
	
	$this->reporte_xml .= "</Carga>\n";
	$this->reporte_xml .= "\n\n\n";	
	
	return;

}

//==========================================================================

function get_xml_personas()
{

	$sql = "SELECT 	 buro_circulo_base.ID_Credito

		FROM	 buro_circulo_base

		WHERE  	 buro_circulo_base.Reportar = 'Si'
		ORDER BY buro_circulo_base.ID_Credito ";
		
        $rs=$this->db->Execute($sql);		

	if($rs->_numOfRows)
	   while(! $rs->EOF)
	   {

		$this->reporte_xml .= "\t<Persona>\n";


		$id = $rs->fields['ID_Credito'];
		
		$this->get_xml_personas_nombre($id);
		$this->get_xml_personas_domicilios($id);
		$this->get_xml_personas_empleos($id);
		$this->get_xml_personas_cuenta($id);

		$this->reporte_xml .= "\t</Persona>\n";
		$this->reporte_xml .= "\n\n";

	        $rs->MoveNext();
	   }


	return;


}

//==========================================================================

function get_xml_personas_nombre($id)
{

	$sql = " SELECT ApellidoPaterno, 
			ApellidoMaterno, 
			ApellidoAdicional,
			Nombres, 
			FechaNacimiento,
			RFC, 
			CURP, 
			Nacionalidad,
			Residencia, 
			NumeroLicenciaConducir, 
			EstadoCivil, 
			Sexo, 
			ClaveElectorIFE, 
			NumeroDependientes, 
			FechaDefuncion, 
			IndicadorDefuncion, 
			TipoPersona
			
		FROM 	buro_circulo_persona_nombre  

		WHERE   buro_circulo_persona_nombre.ID_Credito = '".$id."' ";
	
	 $rs=$this->db->Execute($sql);

	$this->reporte_xml .= "\t\t<Nombre>\n";
	$this->reporte_xml .= "\t\t	<ApellidoPaterno>".$rs->fields['ApellidoPaterno']."</ApellidoPaterno>\n";
	$this->reporte_xml .= "\t\t	<ApellidoMaterno>".$rs->fields['ApellidoMaterno']."</ApellidoMaterno>\n";
	$this->reporte_xml .= "\t\t	<ApellidoAdicional>".$rs->fields['ApellidoAdicional']."</ApellidoAdicional>\n";
	$this->reporte_xml .= "\t\t	<Nombres>".$rs->fields['Nombres']."</Nombres>\n";
	
	$FechaNacimiento = str_replace("-","",$rs->fields['FechaNacimiento']);
	$this->reporte_xml .= "\t\t	<FechaNacimiento>".$FechaNacimiento."</FechaNacimiento>\n";
	
	
	$this->reporte_xml .= "\t\t	<RFC>".$rs->fields['RFC']."</RFC>\n";
	$this->reporte_xml .= "\t\t	<CURP>".$rs->fields['CURP']."</CURP>\n";
	$this->reporte_xml .= "\t\t	<Nacionalidad>".$rs->fields['Nacionalidad']."</Nacionalidad>\n";
	$this->reporte_xml .= "\t\t	<Residencia>".$rs->fields['Residencia']."</Residencia>\n";
	$this->reporte_xml .= "\t\t	<NumeroLicenciaConducir>".$rs->fields['NumeroLicenciaConducir']."</NumeroLicenciaConducir>\n";
	$this->reporte_xml .= "\t\t	<EstadoCivil>".$rs->fields['EstadoCivil']."</EstadoCivil>\n";
	$this->reporte_xml .= "\t\t	<Sexo>".$rs->fields['Sexo']."</Sexo>\n";
	$this->reporte_xml .= "\t\t	<ClaveElectorIFE>".$rs->fields['ClaveElectorIFE']."</ClaveElectorIFE>\n";
	$this->reporte_xml .= "\t\t	<NumeroDependientes>".$rs->fields['NumeroDependientes']."</NumeroDependientes>\n";
	$this->reporte_xml .= "\t\t	<FechaDefuncion/>\n";
	$this->reporte_xml .= "\t\t	<IndicadorDefuncion/>\n";
	$this->reporte_xml .= "\t\t	<TipoPersona>".$rs->fields['TipoPersona']."</TipoPersona>\n";
	$this->reporte_xml .= "\t\t</Nombre>\n\n";


	return;


}

//==========================================================================

function get_xml_personas_domicilios($id)
{


	$sql = " SELECT Direccion, 
			ColoniaPoblacion, 
			DelegacionMunicipio, 
			Ciudad, 
			Estado, 
			CP, 
			FechaResidencia, 
			NumeroTelefono, 
			TipoDomicilio, 
			TipoAsentamiento

		FROM 	buro_circulo_persona_domicilio  

		WHERE   buro_circulo_persona_domicilio.ID_Credito = '".$id."' ";


	$rs=$this->db->Execute($sql);

	if($rs->_numOfRows)
	{
	   $this->reporte_xml .= "\t\t<Domicilios>\n";	
	   while(! $rs->EOF)
	   {

		$FechaResidencia = str_replace("-","",$rs->fields['FechaResidencia']);		


		$this->reporte_xml .= "\t\t	<Domicilio>\n";
		$this->reporte_xml .= "\t\t		<Direccion>".$rs->fields['Direccion']."</Direccion>\n";
		$this->reporte_xml .= "\t\t		<ColoniaPoblacion>".$rs->fields['ColoniaPoblacion']."</ColoniaPoblacion>\n";
		$this->reporte_xml .= "\t\t		<DelegacionMunicipio>".$rs->fields['DelegacionMunicipio']."</DelegacionMunicipio>\n";
		$this->reporte_xml .= "\t\t		<Ciudad>".$rs->fields['Ciudad']."</Ciudad>\n";
		$this->reporte_xml .= "\t\t		<Estado>".$rs->fields['Estado']."</Estado>\n";
		$this->reporte_xml .= "\t\t		<CP>".$rs->fields['CP']."</CP>\n";
		
		$this->reporte_xml .= "\t\t		<FechaResidencia>".$FechaResidencia."</FechaResidencia>\n";
		
		$this->reporte_xml .= "\t\t		<NumeroTelefono>".$rs->fields['NumeroTelefono']."</NumeroTelefono>\n";
		$this->reporte_xml .= "\t\t		<TipoDomicilio>".$rs->fields['TipoDomicilio']."</TipoDomicilio>\n";
		$this->reporte_xml .= "\t\t		<TipoAsentamiento>".$rs->fields['TipoAsentamiento']."</TipoAsentamiento>\n";
		$this->reporte_xml .= "\t\t	</Domicilio>\n";
	

	     $rs->MoveNext();
	   }
	   
	   $this->reporte_xml .= "\t\t</Domicilios>\n\n";

	}

	return;

}


//==========================================================================

function get_xml_personas_empleos($id)
{
	   $this->reporte_xml .= "\t\t";
	   $this->reporte_xml .= "<Empleos>";
	   $this->reporte_xml .= "</Empleos>\n\n";

 	   return;
}

//==========================================================================

function get_xml_personas_cuenta($id)
{

	$sql = " SELECT ClaveActualOtorgante, 
			NombreOtorgante, 
			CuentaActual, 
			TipoResponsabilidad, 
			TipoCuenta, 
			TipoContrato,
			ClaveUnidadMonetaria, 
			ValorActivoValuacion, 
			NumeroPagos, 
			FrecuenciaPagos, 
			MontoPagar, 
			FechaAperturaCuenta, 
			FechaUltimoPago, 
			FechaUltimaCompra, 
			FechaCierreCuenta, 
			FechaCorte, 
			Garantia, 
			CreditoMaximo, 
			SaldoActual, 
			LimiteCredito, 
			SaldoVencido, 
			NumeroPagosVencidos, 
			PagoActual, 
			HistoricoPagos, 
			ClavePrevencion, 
			TotalPagosReportados, 
			ClaveAnteriorOtorgante, 
			NombreAnteriorOtorgante, 
			NumeroCuentaAnterior

		FROM 	buro_circulo_persona_cuenta    

		WHERE   buro_circulo_persona_cuenta.ID_Credito = '".$id."' ";

	$rs=$this->db->Execute($sql);




	$this->reporte_xml .= "\t\t<Cuenta>\n";
	$this->reporte_xml .= "\t\t	<ClaveActualOtorgante>".$rs->fields['ClaveActualOtorgante']."</ClaveActualOtorgante>\n";
	$this->reporte_xml .= "\t\t	<NombreOtorgante>".$rs->fields['NombreOtorgante']."</NombreOtorgante>\n";
	$this->reporte_xml .= "\t\t	<CuentaActual>".$rs->fields['CuentaActual']."</CuentaActual>\n";
	$this->reporte_xml .= "\t\t	<TipoResponsabilidad>".$rs->fields['TipoResponsabilidad']."</TipoResponsabilidad>\n";
	$this->reporte_xml .= "\t\t	<TipoCuenta>".$rs->fields['TipoCuenta']."</TipoCuenta>\n";
	$this->reporte_xml .= "\t\t	<TipoContrato>".$rs->fields['TipoContrato']."</TipoContrato>\n";
	$this->reporte_xml .= "\t\t	<ClaveUnidadMonetaria>".$rs->fields['ClaveUnidadMonetaria']."</ClaveUnidadMonetaria>\n";
	$this->reporte_xml .= "\t\t	<ValorActivoValuacion>".$rs->fields['ValorActivoValuacion']."</ValorActivoValuacion>\n";
	$this->reporte_xml .= "\t\t	<NumeroPagos>".$rs->fields['NumeroPagos']."</NumeroPagos>\n";
	$this->reporte_xml .= "\t\t	<FrecuenciaPagos>".$rs->fields['FrecuenciaPagos']."</FrecuenciaPagos>\n";
	$this->reporte_xml .= "\t\t	<MontoPagar>".$rs->fields['MontoPagar']."</MontoPagar>\n";
		
	$this->reporte_xml .= "\t\t	<FechaAperturaCuenta>".$this->fecha_numerica($rs->fields['FechaAperturaCuenta'])."</FechaAperturaCuenta>\n";
	$this->reporte_xml .= "\t\t	<FechaUltimoPago>".$this->fecha_numerica($rs->fields['FechaUltimoPago'])."</FechaUltimoPago>\n";
	$this->reporte_xml .= "\t\t	<FechaUltimaCompra>".$this->fecha_numerica($rs->fields['FechaUltimaCompra'])."</FechaUltimaCompra>\n";
	$this->reporte_xml .= "\t\t	<FechaCierreCuenta>".$this->fecha_numerica($rs->fields['FechaCierreCuenta'])."</FechaCierreCuenta>\n";
	$this->reporte_xml .= "\t\t	<FechaCorte>".$this->fecha_numerica($rs->fields['FechaCorte'])."</FechaCorte>\n";
		
	$this->reporte_xml .= "\t\t	<Garantia>".$rs->fields['Garantia']."</Garantia>\n";
	$this->reporte_xml .= "\t\t	<CreditoMaximo>".$rs->fields['CreditoMaximo']."</CreditoMaximo>\n";
	$this->reporte_xml .= "\t\t	<SaldoActual>".$rs->fields['SaldoActual']."</SaldoActual>\n";
	$this->reporte_xml .= "\t\t	<LimiteCredito>".$rs->fields['LimiteCredito']."</LimiteCredito>\n";
	$this->reporte_xml .= "\t\t	<SaldoVencido>".$rs->fields['SaldoVencido']."</SaldoVencido>\n";
	$this->reporte_xml .= "\t\t	<NumeroPagosVencidos>".$rs->fields['NumeroPagosVencidos']."</NumeroPagosVencidos>\n";
	$this->reporte_xml .= "\t\t	<PagoActual>".$rs->fields['PagoActual']."</PagoActual>\n";
	$this->reporte_xml .= "\t\t	<HistoricoPagos>".$rs->fields['HistoricoPagos']."</HistoricoPagos>\n";
	$this->reporte_xml .= "\t\t	<ClavePrevencion>".$rs->fields['ClavePrevencion']."</ClavePrevencion>\n";
	$this->reporte_xml .= "\t\t	<TotalPagosReportados>".$rs->fields['TotalPagosReportados']."</TotalPagosReportados>\n";
	$this->reporte_xml .= "\t\t	<ClaveAnteriorOtorgante>".$rs->fields['ClaveAnteriorOtorgante']."</ClaveAnteriorOtorgante>\n";
	$this->reporte_xml .= "\t\t	<NombreAnteriorOtorgante>".$rs->fields['NombreAnteriorOtorgante']."</NombreAnteriorOtorgante>\n";
	$this->reporte_xml .= "\t\t	<NumeroCuentaAnterior>".$rs->fields['NumeroCuentaAnterior']."</NumeroCuentaAnterior>\n";
	$this->reporte_xml .= "\t\t</Cuenta>\n\n";


 	return;
}
//==========================================================================

function fecha_numerica($fecha)
{

	$_fecha = str_replace("-","",$fecha);
	
	return($_fecha);
}


//==========================================================================

function get_xml_cifras_control()
{

	$sql = "SELECT  TotalSaldosActuales, 
			TotalSaldosVencidos, 
			TotalElementosNombreReportados, 
			TotalElementosDireccionReportados, 
			TotalElementosEmpleoReportados, 
			TotalElementosCuentaReportados, 
			NombreOtorgante, 
			DomicilioDevolucion

		FROM 	buro_circulo_cifras_control ";

	$rs=$this->db->Execute($sql);

	$this->reporte_xml .= "<CifrasControl>\n";
	$this->reporte_xml .= "\t<TotalSaldosActuales>".$rs->fields['TotalSaldosActuales']."</TotalSaldosActuales>\n";
	$this->reporte_xml .= "\t<TotalSaldosVencidos>".$rs->fields['TotalSaldosVencidos']."</TotalSaldosVencidos>\n";
	$this->reporte_xml .= "\t<TotalElementosNombreReportados>".$rs->fields['TotalElementosNombreReportados']."</TotalElementosNombreReportados>\n";
	$this->reporte_xml .= "\t<TotalElementosDireccionReportados>".$rs->fields['TotalElementosDireccionReportados']."</TotalElementosDireccionReportados>\n";
	$this->reporte_xml .= "\t<TotalElementosEmpleoReportados>".$rs->fields['TotalElementosEmpleoReportados']."</TotalElementosEmpleoReportados>\n";
	$this->reporte_xml .= "\t<TotalElementosCuentaReportados>".$rs->fields['TotalElementosCuentaReportados']."</TotalElementosCuentaReportados>\n";
	$this->reporte_xml .= "\t<NombreOtorgante>".$rs->fields['NombreOtorgante']."</NombreOtorgante>\n";
	$this->reporte_xml .= "\t<DomicilioDevolucion>".$rs->fields['DomicilioDevolucion']."</DomicilioDevolucion>\n";
	$this->reporte_xml .= "</CifrasControl>\n\n\n";
			       
}



//==========================================================================



};

?>