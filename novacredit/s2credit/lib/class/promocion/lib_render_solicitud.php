<?
/****************************************/
/*Fecha: 02/09/2011
/*Autor: Tonathiu Cárdenas
/*Descripcíón: Se genera el render de la solicitud por tipo de crédito 
/*Dependencias:captura_solicitud_????.php
/*Tablas asociadas: cat_tipo_credito_campos, cat_tipo_credito_secciones 
/*Versión S2credit: 4.5
/****************************************/
require($class_path."promocion/lib_informacion_basica.php");   //INFO SUCURSAL Y USUARIO
require($class_path."promocion/lib_campos_captura.php");       //FORMA LOS COMBOS

class TNuevaSolicitud
{
  var $db;
  var $ID_Tipocredito;
  var $ID_Tiposolicitud;
  var $TBL_SOLI_DEST;
  var $HTML;
  var $HTML_CSS;
  var $ID_SUC;
  var $ID_USR;
  var $DESIGN;
  var $COLUMNS;
  var $Design_Tag;
  var $CMP_REQ;
  var $Count_REQ;
  var $Input_Tag;
  var $CMP_TABLA;
  var $PHP_SCRIPT;
  var $NUM_CLIENTE;
  var $ID_SOLICITUD;
  var $TIPO_REGIMEN;
  var $SOLICITUD_CAMPOS;
  var $INFO_CREDIT;
  var $DIV_INFO_CREDIT;
  var $REFERENCIAS_OBLIG;
  var $REFERENCIAS_BANCARIAS_OBLIG;
  var $REFERENCIAS_COMERCIALES_OBLIG;
  var $REFERENCIAS_PROVEEDORES_OBLIG; 
  var $REFERENCIAS_FUNCIONARIOS_OBLIG;
  var $REFERENCIAS_FUNCIONARIOS_AUT_OBLIG;
  var $REFERENCIAS_ACCIONISTAS_OBLIG;
  var $REFERENCIAS_CHEQUES_OBLIG;
  var $CAMPOS_REFERENCIAS;
  var $CAMPOS_REFERENCIAS_BANCARIAS;
  var $CAMPOS_REFERENCIAS_COMERCIALES;
  var $CAMPOS_REFERENCIAS_PROVEEDORES;
  var $CAMPOS_REFERENCIAS_FUNCIONARIOS;
  var $CAMPOS_REFERENCIAS_FUNCIONARIOS_AUT;
  var $CAMPOS_REFERENCIAS_ACCIONISTAS;
  var $CAMPOS_REFERENCIAS_CHEQUES;
  var $REFERENCIAS_CAPTURADAS;
  var $REFERENCIAS_CAPTURADAS_BANCARIAS;
  var $REFERENCIAS_CAPTURADAS_COMERCIALES;
  var $REFERENCIAS_CAPTURADAS_PROVEEDORES;
  var $REFERENCIAS_CAPTURADAS_FUNCIONARIOS;
  var $REFERENCIAS_CAPTURADAS_FUNCIONARIOS_AUT;
  var $REFERENCIAS_CAPTURADAS_ACCIONISTAS;
  var $REFERENCIAS_CAPTURADAS_CHEQUES;
  var $INDEX_REFERENCIAS = 1;
  var $INDEX_REFERENCIAS_BANCARIAS = 1;
  var $INDEX_REFERENCIAS_COMERCIALES = 1;
  var $INDEX_REFERENCIAS_PROVEEDORES = 1;
  var $INDEX_REFERENCIAS_FUNCIONARIOS = 1;
  var $INDEX_REFERENCIAS_FUNCIONARIOS_AUT = 1;
  var $INDEX_REFERENCIAS_ACCIONISTAS = 1;
  var $INDEX_REFERENCIAS_CHEQUES = 1;
  var $IMG;
  var $ID_GPO;
  var $NMB_GPO;
  var $PROMO_GPO;
  var $ID_PROMO;
  var $INTG_GPO;
  var $INTG_GPO_MIN;
  var $INTG_GPO_MAX;
  var $ID_VALIDATION;
  var $VALIDATION_NOMINA = 'FALSE';
  var $NOMINA_RFC;
  var $NOMINA_ID_PROD;
  var $NOMINA_PROD_FINAN;
  var $NOMINA_DESC_PROD;
  var $NOMINA_PLAZO;
  var $NOMINA_VENCIMIENTO;
  var $NOMINA_MONTO;
  var $CAMPOS_NOMINA;
  var $NOMINA_TOTAL;
  var $NOMINA_LIQUID;
  var $NOMINA_ID_EMP;
  var $NOMINA_EMPRESA;
  var $NOMINA_FECHA_ING;
  var $NOMINA_ANTIGD_EMP;
  var $NOMINA_ID_SOLI_IMP;
  var $VIEW_ID_PROD;
  var $VIEW_PLAZO;
  var $VIEW_MONTO;
  var $VIEW_ID_EMP;
  var $EDIT_PRIVILEGE;
  var $GENERA_RFC;
  var $GENERA_HCLAVE;
  var $GENERA_CURP;
  var $CURP_DESGLOSAR;
  var $IMPRIMR_PDF_SOLI;

 
    function    TNuevaSolicitud ($id_tipo_credito,$id_tiposolicitud,&$db,$id_sucursal,$id_usr,$num_cliente=0,$php_self,$id_gpo=0,$id_empresa=0)//Constructor
    {
       $this->db             		= $db;
       $this->ID_Tipocredito 		= $id_tipo_credito;
	   $this->ID_Tiposolicitud		= $id_tiposolicitud;
       $this->ID_SUC		 		= $id_sucursal;
       $this->ID_USR		 		= $id_usr;
       $this->NUM_CLIENTE	 		= $num_cliente;
	   $this->PHP_SCRIPT	 		= $php_self;
	   $this->ID_GPO	 	 		= $id_gpo;
	   $this->TBL_SOLI_DEST			= ($this->ID_Tipocredito < 4)?('solicitud'):('solicitud_pmoral');

	   $Pos = strpos(getenv("SCRIPT_NAME"), "s2credit");
	   $this->IMG			 = substr(getenv("SCRIPT_NAME"),0,$Pos+9)."images/";
	   
		//DISEÑO SOLICITUD
		$Sql_design="
					SELECT
						   VALOR  AS DESIGN
					 FROM constantes
					 WHERE NOMBRE ='DISENO_SOLICITUD'  ";
	    $rs_design=$this->db->Execute($Sql_design);

	   $this->DESIGN=$rs_design->fields["DESIGN"];

		//COLUMNAS SOLICITUD
		$Sql_design="
					SELECT
						   VALOR  AS COLUMNS
					 FROM constantes
					 WHERE NOMBRE ='COLUMNAS_SOLICITUD'  ";
	    $rs_design=$this->db->Execute($Sql_design);

	   $this->COLUMNS=$rs_design->fields["COLUMNS"];

	   //REQUIERE COTIZADOR
	   $Tipo_Cotizador=($this->ID_Tipocredito=='1')?("INFORME_CREDITO_INDIV"):("INFORME_CREDITO_SOLIDARIO");
	   $Tipo_Cotizador=($this->ID_Tipocredito=='3')?("INFORME_CREDITO_NOMINA"):($Tipo_Cotizador);
	   $Tipo_Cotizador=($this->ID_Tipocredito > 3)?(0):($Tipo_Cotizador);
	   	   
		$Sql_cotz="
					SELECT
						   VALOR  AS INFO_CREDIT
					 FROM constantes
					 WHERE NOMBRE ='".$Tipo_Cotizador."'  ";
	    $rs_cotz=$this->db->Execute($Sql_cotz);

		if($this->ID_Tipocredito == 3)
		{
				$SQL_CONS_EMP="SELECT
									ID_empresa		AS ID,
									Empresa			AS EMP
								FROM
									cat_convenio_empresas
									WHERE ID_empresa ='".$id_empresa."' ";
				$rs_cons_emp=$this->db->Execute($SQL_CONS_EMP);

			$this->NOMINA_ID_EMP=$rs_cons_emp->fields["ID"];
			$this->NOMINA_EMPRESA=$rs_cons_emp->fields["EMP"];
			
		}

	   $this->INFO_CREDIT=$rs_cotz->fields["INFO_CREDIT"];   

	   //REFERENCIAS OBLIGATORIAS
	   $Tipo_refrencias=($this->ID_Tipocredito=='1')?("REFERENCIAS_INDIVIDUAL"):("REFERENCIAS_SOLIDARIO");
	   $Tipo_refrencias=($this->ID_Tipocredito=='3')?("REFERENCIAS_NOMINA"):($Tipo_refrencias);
	   
		$Sql_refer="
					SELECT
						   VALOR  AS REF_OBLG
					 FROM constantes
					 WHERE NOMBRE ='".$Tipo_refrencias."'  ";
	    $rs_refer=$this->db->Execute($Sql_refer);

	   $this->REFERENCIAS_OBLIG=$rs_refer->fields["REF_OBLG"];

		$Sql_refer="
					SELECT
						   VALOR  AS REF_OBLG
					 FROM constantes
					 WHERE NOMBRE ='REFERENCIAS_CHEQUES'  ";
	    $rs_refer=$this->db->Execute($Sql_refer);

	   $this->REFERENCIAS_CHEQUES_OBLIG=$rs_refer->fields["REF_OBLG"];
	   
		if(!empty($this->ID_GPO))
		{
			$Sql_cons="
						SELECT
							   UCASE(grupo_solidario.Nombre)							AS NMB,
							   UCASE(promotores.Nombre)									AS PROMO,
							   grupo_solidario.ID_Promotor								AS ID_PROMO,
							   grupo_solidario.Status_grupo								AS STAT,
							   COUNT(grupo_solidario_integrantes.ID_grupo_soli)			AS INTEG
						 FROM grupo_solidario
							LEFT JOIN promotores 					    ON grupo_solidario.ID_Promotor 		    = promotores.Num_promo
							LEFT JOIN grupo_solidario_integrantes		ON grupo_solidario.ID_grupo_soli		= grupo_solidario_integrantes.ID_grupo_soli
																			AND grupo_solidario.Ciclo_gpo       = grupo_solidario_integrantes.Ciclo_gpo
																			AND grupo_solidario_integrantes.Ciclo_renovado ='N'
																			AND grupo_solidario_integrantes.Status='Activo'
						 WHERE grupo_solidario.ID_grupo_soli ='".$this->ID_GPO."'
						 GROUP BY grupo_solidario.ID_grupo_soli ";
			$rs_cons=$this->db->Execute($Sql_cons);

			$this->NMB_GPO 		= $rs_cons->fields["NMB"];
			$this->PROMO_GPO 	= $rs_cons->fields["PROMO"];
			$this->ID_PROMO 	= $rs_cons->fields["ID_PROMO"];
			$this->STATUS    	= $rs_cons->fields["STAT"];
			$this->INTG_GPO		= $rs_cons->fields["INTEG"];

			$Sql_cons="SELECT
							Integrantes_min	AS INTG_MIN,
							Integrantes_max	AS INTG_MAX
						FROM cat_params_grupo
						";
			$rs_cons=$this->db->Execute($Sql_cons);

			$this->INTG_GPO_MIN	= $rs_cons->fields["INTG_MIN"];
			$this->INTG_GPO_MAX	= $rs_cons->fields["INTG_MAX"];
		}

		//REFERENCIAS OBLIGATORIAS DE TIPO DE CRÉDITO P.MORAL
		if($this->ID_Tipocredito == 4 || $this->ID_Tipocredito == 5 )
		{
				$Sql_refer="
							SELECT
								   VALOR  AS REF_OBLG
							 FROM constantes
							 WHERE NOMBRE ='REFERENCIAS_BANCARIAS'  ";
				$rs_refer=$this->db->Execute($Sql_refer);

			   $this->REFERENCIAS_BANCARIAS_OBLIG=$rs_refer->fields["REF_OBLG"];

				$Sql_refer="
							SELECT
								   VALOR  AS REF_OBLG
							 FROM constantes
							 WHERE NOMBRE ='REFERENCIAS_COMERCIALES'  ";
				$rs_refer=$this->db->Execute($Sql_refer);

			   $this->REFERENCIAS_COMERCIALES_OBLIG=$rs_refer->fields["REF_OBLG"];

				$Sql_refer="
							SELECT
								   VALOR  AS REF_OBLG
							 FROM constantes
							 WHERE NOMBRE ='REFERENCIAS_PROVEEDORES'  ";
				$rs_refer=$this->db->Execute($Sql_refer);

			   $this->REFERENCIAS_PROVEEDORES_OBLIG=$rs_refer->fields["REF_OBLG"];


				$Sql_refer="
							SELECT
								   VALOR  AS REF_OBLG
							 FROM constantes
							 WHERE NOMBRE ='REFERENCIAS_FUNCIONARIOS'  ";
				$rs_refer=$this->db->Execute($Sql_refer);

			   $this->REFERENCIAS_FUNCIONARIOS_OBLIG=$rs_refer->fields["REF_OBLG"];


				$Sql_refer="
							SELECT
								   VALOR  AS REF_OBLG
							 FROM constantes
							 WHERE NOMBRE ='REFERENCIAS_FUNCIONARIOS_AUT'  ";
				$rs_refer=$this->db->Execute($Sql_refer);

			   $this->REFERENCIAS_FUNCIONARIOS_AUT_OBLIG=$rs_refer->fields["REF_OBLG"];


				$Sql_refer="
							SELECT
								   VALOR  AS REF_OBLG
							 FROM constantes
							 WHERE NOMBRE ='REFERENCIAS_ACCIONISTAS'  ";
				$rs_refer=$this->db->Execute($Sql_refer);

			   $this->REFERENCIAS_ACCIONISTAS_OBLIG=$rs_refer->fields["REF_OBLG"];
			   
		}//FIN REFERENCIAS TIPO P. MORAL

		####################TIPO DE RÉGIMEN################################
				$Sql_reg="SELECT
									ID_Regimen	AS REG
							FROM
									cat_tipo_credito_regimen
							WHERE ID_Tipo_regimen = '".$this->ID_Tiposolicitud."' ";
				$rs_reg=$this->db->Execute($Sql_reg);

				$this->TIPO_REGIMEN=$rs_reg->fields["REG"];
				

		####################CONSTANTES###############################################
  
 				$Sql_const="
							SELECT
								   VALOR  AS RFC_OBLG
							 FROM constantes
							 WHERE NOMBRE ='GENERAR_RFC'  ";
				$rs_const=$this->db->Execute($Sql_const);

			   $this->GENERA_RFC=strtoupper($rs_const->fields["RFC_OBLG"]);


 				$Sql_const="
							SELECT
								   VALOR  AS HCVE_OBLG
							 FROM constantes
							 WHERE NOMBRE ='GENERAR_HOMO_CLAVE'  ";
				$rs_const=$this->db->Execute($Sql_const);

			   $this->GENERA_HCLAVE=strtoupper($rs_const->fields["HCVE_OBLG"]);


 				$Sql_const="
							SELECT
								   VALOR  AS CURP_OBLG
							 FROM constantes
							 WHERE NOMBRE ='GENERAR_CURP'  ";
				$rs_const=$this->db->Execute($Sql_const);

			   $this->GENERA_CURP=strtoupper($rs_const->fields["CURP_OBLG"]);

 				$Sql_const="
							SELECT
								   VALOR  AS CURP_DESGLOSAR
							 FROM constantes
							 WHERE NOMBRE ='CURP_DESGLOSAR_CAMPOS'  ";
				$rs_const=$this->db->Execute($Sql_const);

			   $this->CURP_DESGLOSAR=strtoupper($rs_const->fields["CURP_DESGLOSAR"]);

 				$Sql_const="
							SELECT
								   VALOR  AS BTN_SOLI
							 FROM constantes
							 WHERE NOMBRE ='BOTON_IMPRIMIR_SOLICITUD'  ";
				$rs_const=$this->db->Execute($Sql_const);

			   $this->IMPRIMR_PDF_SOLI=$rs_const->fields["BTN_SOLI"];
			   
	}//FIN CONSTRUCT


	function check_nomina_especial()
	{
		$SQL_SEARCH="SELECT
								Tipocredito_especial	AS TCESP,
						  CONCAT(RFC,Hclave)			AS RFC_CMPL
						FROM 
							solicitud
						WHERE ID_Solicitud = '".$this->ID_SOLICITUD."'";
		$rs_search=$this->db->Execute($SQL_SEARCH);

		if($rs_search->fields["TCESP"] == 'TRUE' )
		{
				$SQL_ID="	SELECT
									MAX(ID_cotizador)		AS MAX_ID
								FROM	
										cotizador_cliente
								WHERE RFC = '".$rs_search->fields["RFC_CMPL"]."' ";
				$rs_id=$this->db->Execute($SQL_ID);

			return $rs_id->fields["MAX_ID"];
		}
		return "FALSE";
	}

	function get_datos_credito_view()
	{

		$SQL_SEARCH="SELECT
						ID_Producto			AS ID_PROD,
						Monto				AS MNT,
						Plazo				AS PLZ,
						ID_empresa			AS ID_EMP
					FROM	solicitud
					WHERE ID_Solicitud ='".$this->ID_SOLICITUD."'";
		$rs_search=$this->db->Execute($SQL_SEARCH);

		  $this->VIEW_ID_PROD	=$rs_search->fields["ID_PROD"];
		  $this->VIEW_PLAZO		=$rs_search->fields["PLZ"];
		  $this->VIEW_MONTO		=$rs_search->fields["MNT"];
		  $this->VIEW_ID_EMP	=$rs_search->fields["ID_EMP"];

	}

	function set_validation_nomina($ID_VALIDATION)
	{
		 $this->ID_VALIDATION 		= $ID_VALIDATION;
		 $this->VALIDATION_NOMINA   = (!empty($ID_VALIDATION))?('TRUE'):('FALSE') ;
		/*
		$SQL_PROSPECT="SELECT
							cotizador_cliente.RFC								AS RFC,
							cotizador_cliente.ID_Producto						AS ID_PROD,
							cotizador_cliente.Monto								AS MNT,
							cotizador_cliente.Plazo								AS PLZ,
							cotizador_cliente.Liquido							AS LIQ,
							cotizador_cliente.Total								AS TOT,
							cat_productosfinancieros.Nombre						AS NMB_PROD,
							cat_productosfinancieros.Vencimiento				AS VENCE,
							cotizador_cliente.ID_Solicitud_importacion			AS ID_SOLI_IMP
						FROM cotizador_cliente
							LEFT JOIN cat_productosfinancieros ON cotizador_cliente.ID_Producto = cat_productosfinancieros.ID_Producto
							WHERE ID_cotizador = '".$this->ID_VALIDATION."' ";
		$rs_prospect=$this->db->Execute($SQL_PROSPECT);

		 $this->NOMINA_MONTO 	 	= $rs_prospect->fields["MNT"];
		 $this->NOMINA_PLAZO 	 	= $rs_prospect->fields["PLZ"];
		 $this->NOMINA_ID_PROD   	= $rs_prospect->fields["ID_PROD"];
		 $this->NOMINA_PROD_FINAN	= $rs_prospect->fields["NMB_PROD"];
		 $this->NOMINA_RFC   	 	= $rs_prospect->fields["RFC"];
		 $this->NOMINA_TOTAL   	 	= $rs_prospect->fields["TOT"];
		 $this->NOMINA_LIQUID	 	= $rs_prospect->fields["LIQ"];
		 $this->NOMINA_VENCIMIENTO	= $rs_prospect->fields["VENCE"];
		 $this->NOMINA_ID_SOLI_IMP	= $rs_prospect->fields["ID_SOLI_IMP"];

		if(empty($this->NOMINA_ID_EMP))
		{
				$SQL_PROSPECT="SELECT
									ID_empresa		AS ID,
									Empresa			AS EMP
								FROM
									cat_convenio_empresas
									WHERE Nomina_especial ='Y' ";
				$rs_prospect=$this->db->Execute($SQL_PROSPECT);
	   }
	   else
	   {
				$SQL_PROSPECT="SELECT
									ID_empresa		AS ID,
									Empresa			AS EMP
								FROM
									cat_convenio_empresas
									WHERE ID_empresa ='".$this->NOMINA_ID_EMP."' ";
				$rs_prospect=$this->db->Execute($SQL_PROSPECT);

	   }

		$this->NOMINA_ID_EMP	= $rs_prospect->fields["ID"];
		$this->NOMINA_EMPRESA	= $rs_prospect->fields["EMP"];

		$SQL_FECH="SELECT
							FECHA_INTG		AS FECH_INTG
						FROM
							solicitud_prospectos_log
							WHERE RFC ='".$this->NOMINA_RFC."'
								LIMIT 1";
		$rs_fech=$this->db->Execute($SQL_FECH);



		$FECH_BD						= substr($rs_fech->fields["FECH_INTG"],0,-2);
		$Year							= date("Y");
		$Month							= date("m");
		$Day							= date("d");
		
		$DIFF_YEAR						= floor($Year - $FECH_BD);
		$this->NOMINA_FECHA_ING			= $Day.'/'.$Month.'/'.$FECH_BD;


		$this->NOMINA_ANTIGD_EMP = gfecha($this->NOMINA_FECHA_ING);
		list($Y,$m,$d) = explode("-",$this->NOMINA_ANTIGD_EMP);
		$this->NOMINA_ANTIGD_EMP = ( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );

		
		$RFC  =	substr($this->NOMINA_RFC,0,-3);
		$HCVE = substr($this->NOMINA_RFC,-3);
		*/
		//ARRAY
		
		$this->CAMPOS_NOMINA = array('Monto'=>$this->NOMINA_MONTO,'ID_Producto'=>$this->NOMINA_ID_PROD,'NMB_PROD'=>$this->NOMINA_PROD_FINAN,'Plazo'=>$this->NOMINA_PLAZO,'Vencimiento'=> $this->NOMINA_VENCIMIENTO,'Empresa_soli'=>$this->NOMINA_ID_EMP,'NMB_EMP'=>$this->NOMINA_EMPRESA,'Deducciones_otros'=>$this->NOMINA_LIQUID,'Ingresos_soli'=>$this->NOMINA_TOTAL,'RFC'=>$RFC,'Hclave'=>$HCVE);
		
	}



	function get_campos_requeridos()
	{
				$Sql_campos="SELECT 
								   cat_tipo_credito_campos.ID_campo                 AS ID_CMP,
								   cat_tipo_credito_campos.Etiqueta					AS ETQ,
								   cat_tipo_credito_campos.Obligatorio				AS OBLG,
								   cat_tipo_credito_campos.Orden					AS ORDN,
								   cat_tipo_credito_campos.ID_seccion     			AS ID_SECC,
								   cat_tipo_credito_campos.Nombre_campo				AS NMB_CMP,
								   cat_tipo_credito_campos.class					AS CLASE
								   
							FROM
									cat_tipo_credito_campos
								LEFT JOIN cat_tipo_credito_secciones ON cat_tipo_credito_campos.ID_seccion = cat_tipo_credito_secciones.ID_seccion
									AND cat_tipo_credito_campos.ID_seccion IS NOT NULL
							WHERE cat_tipo_credito_campos.ID_Tipocredito 	 = '".$this->ID_Tipocredito."'
								AND cat_tipo_credito_campos.ID_Tipo_regimen  = '".$this->ID_Tiposolicitud."'
								AND cat_tipo_credito_campos.Visibilidad ='Y'
								AND cat_tipo_credito_campos.Obligatorio ='Y'
							 ORDER BY ID_SECC,ORDN ";
			   $rs_campos=$this->db->Execute($Sql_campos);


					//$CHECK_SOLI = $this->check_nomina_especial();
					//if(!empty($CHECK_SOLI) && ($CHECK_SOLI != 'FALSE') )
						if($this->ID_Tipocredito == 3)
							$this->set_validation_nomina($CHECK_SOLI);
					
					$Lista="<BR />
							<UL STYLE='text-align:left;' ID='LIST_CMP_REQ'>";
					$this->Count_REQ=0;
					While(!$rs_campos->EOF)
						{
									$Search		= strpos($rs_campos->fields["CLASE"], 'REFERENCIA');
									$Referencia=( $Search !== false )?(" / REF. 1"):("");

									$Search		= strpos($rs_campos->fields["CLASE"], 'REFERENCIA_CHEQUES');
									$Referencia=( $Search !== false )?(" / REF CHQ. 1"):("");
									
									$Search		= strpos($rs_campos->fields["CLASE"], 'REFERENCIA_ACCIONISTA');
									$Referencia=( $Search !== false )?(" / REF ACC. 1"):($Referencia);

									$Search		= strpos($rs_campos->fields["CLASE"], 'REFERENCIA_FUNCIONARIO');
									$Referencia=( $Search !== false )?(" / REF FUNC. 1"):($Referencia);

									$Search		= strpos($rs_campos->fields["CLASE"], 'REFERENCIA_AUTORIZADO_FUNC');
									$Referencia=( $Search !== false )?(" / REF FUNC AUT. 1"):($Referencia);

									$Search		= strpos($rs_campos->fields["CLASE"], 'REFERENCIA_AUTORIZADO_FUNC');
									$Referencia=( $Search !== false )?(" / REF FUNC AUT. 1"):($Referencia);
									
									$Search		= strpos($rs_campos->fields["CLASE"], 'REFERENCIA_PROVEEDOR');
									$Referencia=( $Search !== false )?(" / REF PROV. 1"):($Referencia);

									$Search		= strpos($rs_campos->fields["CLASE"], 'REFERENCIA_COMERCIAL');
									$Referencia=( $Search !== false )?(" / REF COM. 1"):($Referencia);

									$Search		= strpos($rs_campos->fields["CLASE"], 'REFERENCIA_BANCARIA');
									$Referencia=( $Search !== false )?(" / REF BANC. 1"):($Referencia);


									if(!isset($this->CAMPOS_NOMINA))
										$this->CAMPOS_NOMINA=array('');
									
									$VALID_NOMINA = array_key_exists($rs_campos->fields["NMB_CMP"],$this->CAMPOS_NOMINA);

									if($VALID_NOMINA === 'FALSE')
									{
										$Lista.="<LI  STYLE='color:#3B240B; font-weight:bold; font-size:110%; height:19px; cursor:pointer; ' ID='CMP_".$rs_campos->fields["NMB_CMP"]."'  CLASS='".$rs_campos->fields["ID_SECC"]."' >&nbsp;&nbsp;&nbsp;&nbsp;<U>[ ".$rs_campos->fields["ETQ"]." ".$Referencia."]</U></LI>";
										$this->Count_REQ++;
									}


							
						$rs_campos->MoveNext();
						}
					$Lista.="</UL>
							<BR />";
					
					$this->CMP_REQ=$Lista;
	}//FIN get_campos_requeridos()

	
	function get_encabezado_soli($TIPO_SOLI)
	{
				$arr_sucursal 			=sucursal_datos($this->ID_SUC,$this->db);
				$capturista   			=usuario_nombre($this->ID_USR,$this->db);
				$fecha_captura			=traducefecha(date("Y/m/d"),'FECHA_COMPLETA');
				$Nmb_credito			=dtl_credito($this->ID_Tipocredito,$this->ID_Tiposolicitud,$this->db);

				$Nombre_cte				=cliente_nombre($this->ID_SOLICITUD,$this->db);

				$Nmb_credito			.=($TIPO_SOLI!='CAPTURA')?("&nbsp;&nbsp;&nbsp; # ".$this->ID_SOLICITUD.""):("");
				
				$this->get_campos_requeridos();
				$IMG_IMPR_SOLI = ( !empty($this->IMPRIMR_PDF_SOLI) && $TIPO_SOLI=='VISTA'  )?("<IMG SRC='".$this->IMG."print2.png' ALT='IMPRIMIR SOLICITUD' STYLE='HEIGHT:25px;  WIDTH:25px; CURSOR:POINTER; PADDING-RIGHT:20px;' TITLE='IMPRIMIR SOLICITUD...'   ONCLICK=' WindowImprSoli=window.open(\"../sucursal/clientes/impresiones/".$this->IMPRIMR_PDF_SOLI."?d_solicitud=".$this->ID_SOLICITUD."\",\"\",\"width=600,height=600\"); WindowImprSoli.focus();'     ID='IMPR_SOLI'   />"):("");
				
				$Header ="
				<DIV CLASS='demo'  ALIGN='center'>
					<DIV CLASS='portlet'>
							<DIV CLASS='portlet-header' >SOLICITUD CONFIDENCIAL DE ".$Nmb_credito."</DIV>
							<DIV CLASS='portlet-content'>
								<TABLE  CELLPADDING='2' CELLSPACING='1' BORDER='0px' WIDTH='90%'>
									<TR>
										<TD  WIDTH='33%' ALIGN='CENTER' >
										<DIV  CLASS='ui-widget-content ui-corner-all'  STYLE='height:65px;'>
										
											<H3 CLASS='ui-widget-header ui-corner-all' STYLE='background:#DDDDDD;  color:black; margin-top:0px; margin-bottom:3%;'>".$arr_sucursal["Sucursal"]."</H3>
											
											<SPAN  STYLE='vertical-align:50%;' ><I>".$arr_sucursal["Direccion"]."</I></SPAN>
										</DIV>
										
										</TD>

										<TD  WIDTH='33%' ALIGN='CENTER'>
											<DIV  CLASS='ui-widget-content ui-corner-all'  STYLE='height:65px;'> 
												<H3 CLASS='ui-widget-header ui-corner-all' STYLE='background:#DDDDDD;  color:black; margin-top:0px; margin-bottom:3%;'>FECHA</H3>
												
												<SPAN  STYLE='vertical-align:middle;' ><I>".strtoupper($fecha_captura)."</I></SPAN>
											</DIV>											  
										</TD>

										<TD  WIDTH='33%' ALIGN='CENTER'>
											<DIV  CLASS='ui-widget-content ui-corner-all'  STYLE='height:65px;'> 
												<H3 CLASS='ui-widget-header ui-corner-all' STYLE='background:#DDDDDD;  color:black; margin-top:0px; margin-bottom:3%;'>CAPTURISTA</H3>
												
												<SPAN  STYLE='vertical-align:middle;' ><I>".strtoupper($capturista)."</I></SPAN>
												<SPAN  STYLE='vertical-align:middle; float:right;' >".$IMG_IMPR_SOLI."</SPAN>
											</DIV>
																						  
										</TD>

									</TR>
								</TABLE>";

								if(!empty($this->NMB_GPO))
									$Header.="
												<H3 CLASS='ui-widget-header' STYLE='font-size:small; width:50%; -moz-border-radius-topleft:  6px; -moz-border-radius-topright: 6px;  margin-top:1%; margin-bottom:1%;'>".$this->NMB_GPO."</H3>
												<INPUT TYPE='HIDDEN' ID='ID_grupo_soli' NAME='ID_grupo_soli'				VALUE='".$this->ID_GPO."' /> ";
				
							if($TIPO_SOLI=='CAPTURA' || $TIPO_SOLI=='EDITA')
							{
							  $Header.="
									  <DIV  ALIGN='LEFT'  >
											<TABLE WIDTH='90%' BORDER='0px' ALIGN='CENTER'>
												<TR STYLE='cursor:pointer;' >
													<TH ALIGN='CENTER' CLASS='SHOW_CMP_REQ'  WIDTH='33%'>
														<DIV ID='DIV_PRTLT_REQ' CLASS='ui-widget-content ui-corner-all' STYLE='height:65px;'> 
															 <H3 CLASS='ui-widget-header ui-corner-all' STYLE='margin-top:0px; margin-bottom:3%;'>CAMPOS REQUERIDOS</H3> 
															<SPAN ID='DIV_PRTLT_REQ_CONTENT'  STYLE='color:black; font-weight:bold; font-size:120%; '>
																	
																	[<LABEL ID='CONT_CMP_REQ' LANG='".$this->Count_REQ."' >0</LABEL>/<LABEL ID='CONT_CMP_TOT' >".$this->Count_REQ."</LABEL>]
																	<IMG SRC='".$this->IMG."directional_down.png' ALT='DESPLEGAR LISTA DE CAMPOS REQUERIDOS' STYLE='HEIGHT:12px;  WIDTH:11; cursor:pointer;' TITLE='DESPLEGAR CAMPOS REQUERIDOS...'        ID='Arrow_Cmp_Req'   />
															 </SPAN>
													     </DIV>  


													</TH>
													<TH STYLE='text-align:center; width:33%;' ALIGN='CENTER'  WIDTH='33%'>
														<DIV ID='DIV_SAVE_SOLI' CLASS='ui-widget-content ui-corner-all' STYLE='height:65px;'> 
															 <H3 CLASS='ui-widget-header ui-corner-all' STYLE='margin-top:0px; margin-bottom:3%;'>SOLICITUD DE CRÉDITO</H3> 
													         <SPAN ID='DIV_SAVE_SOLI_CONTENT'>
													             <BUTTON ID='GUARDAR_SOLICITUD' STYLE='cursor:pointer;' >GUARDAR</BUTTON>
													         </SPAN>
													     </DIV>    
													 </TH>
													<TH STYLE='text-align:center; width:33%;' ALIGN='CENTER'  WIDTH='33%' CLASS='SHOW_ALERT'>

														<DIV ID='effect' CLASS='ui-widget-content ui-corner-all' STYLE='height:65px;'> 
															<H3 CLASS='ui-widget-header ui-corner-all' STYLE='margin-top:0px; margin-bottom:3%;'>ALERTAS S2CREDIT</H3> 
															<SPAN ID='effect_content' STYLE='color:black; font-weight:bold; font-size:120%; width:33%;'>
															
															  	[<LABEL ID='CONT_ALERT' > 0 </LABEL>] 
																<IMG SRC='".$this->IMG."directional_down.png' ALT='DESPLEGAR LISTA DE ALERTAS' STYLE='HEIGHT:12px;  WIDTH:11; cursor:pointer;' TITLE='DESPLEGAR LISTA DE ALERTAS...'        ID='Arrow_alert'   />
							                                   <DIV  STYLE='opacity:0.90; filter:alpha(opacity=90); display:none; width:auto; height:auto; background:#fff1a0;' ALIGN='CENTER' ID='DIV_ALERT' >
																		<BR />
																		<UL STYLE='text-align:left;' ID='LIST_ALERT'>
																		<LI  STYLE='color:#3B240B; font-weight:bold; font-size:80%; height:19px; cursor:pointer; ' ID='ALERT_CLEAN'  >&nbsp;&nbsp;&nbsp;&nbsp;<IMG  BORDER=0 SRC='".$this->IMG."tick-circle-frame.png'  ALT='editando'  ALIGN='center' HEIGHT='20px' WIDTH='20px' STYLE='vertical-align:middle;' />&nbsp;&nbsp;SIN SUCESOS</LI>
																		</UL>
																		<BR />
																</DIV>
															</SPAN>
														</DIV>
														 
													</TH>
												</TR>
											</TABLE>
								</DIV>";

								 $Header.="
												<DIV  STYLE='opacity:0.90; filter:alpha(opacity=90); display:none; width:auto; background:#f4f0ec;' ALIGN='CENTER' ID='DIV_CMP_REQ' >				
												".$this->CMP_REQ."
												</DIV>
													
												</DIV>";
							}

							if($TIPO_SOLI=='VISTA' && $this->ID_Tipocredito=='2' && ($this->STATUS=='PROCESO INTEGRACION - RENOVACION' || $this->STATUS=='PROCESO INTEGRACION') )
							{
								$Header.="
									  <DIV  ALIGN='LEFT'  >
											<TABLE WIDTH='90%' BORDER='0px' ALIGN='CENTER'>
												<TR STYLE='cursor:pointer;' >
													<TH ALIGN='CENTER' CLASS='SHOW_CMP_REQ'  WIDTH='33%'>
														<DIV ID='DIV_PRTLT_REQ' CLASS='ui-widget-content ui-corner-all' STYLE='height:65px;'> 
															 <H3 CLASS='ui-widget-header ui-corner-all' STYLE='margin-top:0px; margin-bottom:3%;'>PROMOTOR</H3> 
															<SPAN ID='DIV_PROMO'  STYLE='color:black; font-weight:bold; font-size:120%; '>
																	
																	".$this->PROMO_GPO."
															 </SPAN>
													     </DIV>  
													</TH>";
								
								if($this->INTG_GPO < $this->INTG_GPO_MAX)
								   $Header.="
													<TH STYLE='text-align:center; width:33%;' ALIGN='CENTER'  WIDTH='33%'>
														<DIV ID='DIV_SAVE_SOLI' CLASS='ui-widget-content ui-corner-all' STYLE='height:65px;'> 
															 <H3 CLASS='ui-widget-header ui-corner-all' STYLE='margin-top:0px; margin-bottom:3%;'> NUEVA SOLICITUD DE CRÉDITO</H3> 
													         <SPAN ID='DIV_ESTATUS' STYLE='color:black; font-weight:bold; font-size:120%; '>
																	<BUTTON ID='NEW_SOLICITUD' STYLE='cursor:pointer;' >SIGUIENTE</BUTTON>
													         </SPAN>
													     </DIV>    
													 </TH>";
								 else
								   $Header.="
													<TH STYLE='text-align:center; width:33%;' ALIGN='CENTER'  WIDTH='33%'>
														<DIV ID='DIV_SAVE_SOLI' CLASS='ui-widget-content ui-corner-all' STYLE='height:65px;'> 
															 <H3 CLASS='ui-widget-header ui-corner-all' STYLE='margin-top:0px; margin-bottom:3%;'> AVISO S2CREDIT</H3> 
													         <SPAN ID='DIV_ESTATUS' STYLE='color:black; font-weight:bold; font-size:110%; '>
																	SE HAN ALCANZADO EL NÚEMRO MÁXIMO DE INTEGRANTES.
													         </SPAN>
													     </DIV>    
													 </TH>";
													 
								 
									$Header.="<TH STYLE='text-align:center; width:33%;' ALIGN='CENTER'  WIDTH='33%' CLASS='SHOW_ALERT'>

														<DIV ID='effect' CLASS='ui-widget-content ui-corner-all' STYLE='height:65px;'> 
															<H3 CLASS='ui-widget-header ui-corner-all' STYLE='margin-top:0px; margin-bottom:3%;'>ESTATUS</H3> 
													         <SPAN ID='DIV_NUM_GPO' STYLE='color:black; font-weight:bold; font-size:120%; '>
																	".$this->STATUS."
													         </SPAN>
														</DIV>
														 
													</TH>
												</TR>
											</TABLE>
								</DIV>";
							}

							
							if($TIPO_SOLI=='PREVIEW_SOLI' || $TIPO_SOLI=='HISTORIAL_SOLI')
							{
								$Button=($TIPO_SOLI=='PREVIEW_SOLI')?("<BUTTON ID='REGRESAR_PANEL' STYLE='cursor:pointer;' TITLE='".$this->ID_Tipocredito."' >REGRESAR...</BUTTON>"):("");
								
								$Header.="
										  <DIV  ALIGN='LEFT'  >
												<TABLE WIDTH='90%' BORDER='0px' ALIGN='CENTER'>
													<TR STYLE='cursor:pointer;' >
														<TH ALIGN='LEFT' STYLE='color:black; font-weight:bold; font-size:120%; width:33%;' CLASS='SHOW_CMP_REQ' >&nbsp;</TH>
														
														<TH ALIGN='CENTER' STYLE='width:33%;'>".$Button."</TH>

														<TH ALIGN='CENTER' STYLE='width:33%;'>&nbsp;</TH>

														
													</TR>
												</TABLE><BR />
									</DIV>";
							}
							
							//MOSTRAR O NO COTIZADOR
							if( ( $this->INFO_CREDIT == '1' ) && ( $TIPO_SOLI !='HISTORIAL_SOLI' ) && ($this->EDIT_PRIVILEGE != 'FALSE' ) )
								{
									$this->get_datos_credito();
									$Header.=$this->DIV_INFO_CREDIT;
								}
						
			$Header.="	</DIV>
					</DIV>";


							$ID_FORM=($TIPO_SOLI=='CAPTURA')?('CAPTURA_SOLICITUD'):('EDITA_SOLICITUD');
							$this->HTML_CSS=$Header;

		   if($TIPO_SOLI !='HISTORIAL_SOLI')
			{
							
							$this->HTML_CSS.="<FORM Method='POST' ACTION='".$this->PHP_SCRIPT."' ID='".$ID_FORM."' accept-charset='utf-8' > <BR />";//VERIFICAR DESCUADRA ACORDION

							if($this->DESIGN=='TABS')//SOLICITUD CON TABS
							{
								$EDIT_PRIVILEGE = (!empty($this->EDIT_PRIVILEGE))?(" AND cat_tipo_credito_secciones.Privilege_edit		= 'N' "):("");
								
								$Sql_secc="
										SELECT DISTINCT
											   cat_tipo_credito_campos.ID_seccion     			AS ID_SECC,
											   cat_tipo_credito_secciones.Nombre				AS NMB_SECC,
											   cat_tipo_credito_secciones.Orden					AS ORD_SECC
										FROM
												cat_tipo_credito_campos
											LEFT JOIN cat_tipo_credito_secciones ON cat_tipo_credito_campos.ID_seccion = cat_tipo_credito_secciones.ID_seccion
												AND cat_tipo_credito_campos.ID_seccion IS NOT NULL
												AND cat_tipo_credito_campos.Visibilidad ='Y'
										WHERE cat_tipo_credito_campos.ID_Tipocredito 		= '".$this->ID_Tipocredito."'
											AND cat_tipo_credito_campos.ID_Tipo_regimen 	= '".$this->ID_Tiposolicitud."'
											AND cat_tipo_credito_secciones.Nombre IS NOT NULL
											".$EDIT_PRIVILEGE."
										 ORDER BY ORD_SECC";
								$rs_secc=$this->db->Execute($Sql_secc);

								$Tabs="<DIV ALIGN='CENTER'>
										 <DIV ID='tabs' STYLE='WIDTH:98%;'>
											<UL>";
								While(!$rs_secc->EOF)
									{
										$Tabs.="<LI><A HREF='#TAB_".$rs_secc->fields["ID_SECC"]."' ID='".$rs_secc->fields["ID_SECC"]."' STYLE='FONT-SIZE:12PX; COLOR:BLACK; TEXT-ALIGN:LEFT;'>".$rs_secc->fields["NMB_SECC"]."</A></LI>";
									$rs_secc->MoveNext();
									}

								$Tabs.="</UL>";
								$this->HTML_CSS.=$Tabs;
								
							}//FIN IF TABS
							elseif($this->DESIGN=='ACORDION')//SOLICITUD CON ACORDIÓN
							{
								$this->HTML_CSS.="<DIV ALIGN='CENTER'>
												<DIV ID='accordion' STYLE='width:98%; align:center;'>";
							}//FIN ELSEIF ACORDIÓN

			}
   }//FIN get_encabezado_soli

	//COTIZADOR ASOCIADO AL CRÉDITO
	function get_datos_credito()
	{
		$this->DIV_INFO_CREDIT="

			<DIV ID='DIV_PRTLT_CREDIT'CLASS='ui-widget-content ui-corner-all' STYLE='height:auto; width:75%; '> 
				<H3 CLASS='ui-widget-header ui-corner-all'  STYLE='font-size:small; TEXT-ALIGN:CENTER; margin-top:0px;   ' >
				    INFORMACIÓN ASOCIADA AL CRÉDITO 
				 </H3> 
					<SPAN>
						<TABLE WIDTH='100%' CELLPADDING='1' CELLSPACING='1' BORDER='0px'>

							<TR STYLE='BACKGROUND-COLOR:#e8eef4;'>
								<TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:25%; FONT-SIZE:12PX; FONT-WEIGHT:normal;'>Producto:</TH>
								<TD STYLE='WIDTH:25%; TEXT-ALIGN:LEFT; font-weight:bold;'>&nbsp;<LABEL ID='LBL_PRODUCTO' > </LABEL></TD>

								<TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:25%; FONT-SIZE:12PX; FONT-WEIGHT:normal;'>Plazo:</TH>
								<TD STYLE='WIDTH:25%; TEXT-ALIGN:LEFT; font-weight:bold;'>&nbsp;<LABEL ID='LBL_PLAZO' > </LABEL></TD>
							</TR>
							
							<TR STYLE='BACKGROUND-COLOR:#e8eef4;'>
								<TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:25%; FONT-SIZE:12PX; FONT-WEIGHT:normal;'>Vencimiento:</TH>
								<TD STYLE='WIDTH:25%; TEXT-ALIGN:LEFT; font-weight:bold;'>&nbsp;<LABEL ID='VENCIMIENTO' > </LABEL></TD>

								<TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:25%; FONT-SIZE:12PX; FONT-WEIGHT:normal;'>Descuento:</TH>
								<TD STYLE='WIDTH:25%; TEXT-ALIGN:LEFT; font-weight:bold;'>&nbsp;<LABEL ID='PERCENT_DESC' > </LABEL></TD>
							</TR>
							
							<TR STYLE='BACKGROUND-COLOR:#e8eef4;'>
								<TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:25%; FONT-SIZE:12PX; FONT-WEIGHT:normal;'>Ingresos Brutos (mensuales):</TH>
								<TD STYLE='WIDTH:25%; TEXT-ALIGN:LEFT; font-weight:bold;'><IMG  BORDER=0 SRC='".$this->IMG."money.png'  ALT='editando'  STYLE='height:13px; width:13px;' ALIGN='left' />&nbsp;<LABEL ID='INGR_BRT' > </LABEL><DIV ID='CHK_INGR_BRT' style='float:right;'></DIV></TD>

								<TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:25%; FONT-SIZE:12PX; FONT-WEIGHT:normal;'>Ingresos Netos (mensuales):</TH>
								<TD STYLE='WIDTH:25%; TEXT-ALIGN:LEFT; font-weight:bold;'><IMG  BORDER=0 SRC='".$this->IMG."money.png'  ALT='editando'  STYLE='height:13px; width:13px;' ALIGN='left' />&nbsp;<LABEL ID='INGR_NET' > </LABEL><DIV ID='CHK_INGR_NET' style='float:right;'></DIV></TD>
								
							</TR>
							<TR STYLE='BACKGROUND-COLOR:#e8eef4;'>
								<TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:25%; FONT-SIZE:12PX; FONT-WEIGHT:normal;'>Capacidad de pago:</TH>
								<TD STYLE='WIDTH:25%; TEXT-ALIGN:LEFT; font-weight:bold;'><IMG  BORDER=0 SRC='".$this->IMG."money.png'  ALT='editando'  STYLE='height:13px; width:13px;' ALIGN='left' />&nbsp;<LABEL ID='CAP_PAGO' > </LABEL></TD>
								
								<TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:25%; FONT-SIZE:12PX; FONT-WEIGHT:normal;'>&nbsp;</TH>
								<TD STYLE='WIDTH:25%; TEXT-ALIGN:LEFT; font-weight:bold;'>&nbsp;</TD>
							</TR>
							<TR STYLE='BACKGROUND-COLOR:#e8eef4;'>
								<TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:25%; FONT-SIZE:12PX; FONT-WEIGHT:normal;'>Monto máximo a autorizar:</TH>
								<TD STYLE='WIDTH:25%; TEXT-ALIGN:LEFT; font-weight:bold;'><IMG  BORDER=0 SRC='".$this->IMG."money.png'  ALT='editando'  STYLE='height:13px; width:13px;' ALIGN='left'/>&nbsp;<LABEL ID='MNT_MAX' > </LABEL></TD>
								
								<TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:25%; FONT-SIZE:12PX; FONT-WEIGHT:normal;'>Pago(s)/Monto máximo a autorizar:</TH>
								<TD STYLE='WIDTH:25%; TEXT-ALIGN:LEFT; font-weight:bold;'><IMG  BORDER=0 SRC='".$this->IMG."money.png'  ALT='editando'  STYLE='height:13px; width:13px;' ALIGN='left' />&nbsp;<LABEL ID='RENTA_MAX' > </LABEL></TD>
								
							</TR>
							<TR STYLE='BACKGROUND-COLOR:#e8eef4;'>
								<TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:25%; FONT-SIZE:12PX; FONT-WEIGHT:normal;'>Monto solicitado:</TH>
								<TD STYLE='WIDTH:25%; TEXT-ALIGN:LEFT; font-weight:bold;'><IMG  BORDER=0 SRC='".$this->IMG."money.png'  ALT='editando'  STYLE='height:13px; width:13px;' ALIGN='left' />&nbsp;<LABEL ID='MONTO_SOLICITADO' > </LABEL></TD>
								
								<TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:25%; FONT-SIZE:12PX; FONT-WEIGHT:normal;'>Pago s/Monto solicitado:</TH>
								<TD STYLE='WIDTH:25%; TEXT-ALIGN:LEFT; font-weight:bold;'><IMG  BORDER=0 SRC='".$this->IMG."money.png'  ALT='editando'  STYLE='height:13px; width:13px;' ALIGN='left'/>&nbsp;<LABEL ID='RENTA_MONTO_SOLI' > </LABEL></TD>
								
							</TR>
							<TR STYLE='BACKGROUND-COLOR:#e8eef4;'>
								<TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:25%; FONT-SIZE:12PX; FONT-WEIGHT:normal;'></TH>
								<TD STYLE='WIDTH:25%;'></TD>
								<TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:25%; FONT-SIZE:12PX; FONT-WEIGHT:normal;'>Diferencia (CP - PMS ):</TH>
								<TD STYLE='WIDTH:25%; TEXT-ALIGN:LEFT; font-weight:bold;'><IMG  BORDER=0 SRC='".$this->IMG."money.png'  ALT='editando'  STYLE='height:13px; width:13px;' ALIGN='left'/>&nbsp;<LABEL ID='DIFERENCIA' > </LABEL></TD>
							</TR>
							<TR STYLE='BACKGROUND-COLOR:#e8eef4;'>
								<TD COLSPAN='4' STYLE='TEXT-ALIGN:CENTER; '><DIV ID='MSG_CREDIT'  CLASS='ui-widget-content ui-corner-all' STYLE='width:auto; height:auto; BACKGROUND-COLOR:#e7eef6;'></DIV></TD>
							</TR>
						</TABLE>
					 </SPAN>
					 <INPUT TYPE='HIDDEN' ID='COTIZADOR' 				VALUE='TRUE' />
					 <INPUT TYPE='HIDDEN' ID='VALIDAR_COTIZADOR' 		VALUE='' />
			</DIV>"; 

	}//FIN COTIZADOR


	function Body_soli($ID_DIV,$NMB_SECC)
	{

		  if($this->DESIGN == 'TABS')
		  {
			$this->Design_Tag="<DIV ID='TAB_".$ID_DIV."'>";
		  }
		  elseif($this->DESIGN == 'ACORDION')
		  {
			$this->Design_Tag="	<H3><A HREF='#' STYLE='FONT-SIZE:12PX; COLOR:BLACK; TEXT-ALIGN:LEFT;' ID='".$ID_DIV."'>".$NMB_SECC."</A></H3>
								<DIV >";
		  }

	}//FIN Body_soli

	function get_input_tag($ETQ,$TIPO_CMP,$NMB_CMP,$CLASS,$EVNT,$STYLE,$ID_SECC,$SQL,$READONLY,$HTML_ASOCC,$CMP_ASOC,$TABLA_DEST,$Title,$Asterisk)
	{
		if(!empty($this->ID_SOLICITUD))
		{

						
						if(($TABLA_DEST == 'solicitud' || $TABLA_DEST == 'solicitud_pmoral') && ($TIPO_CMP!='LABEL'))
							$Campo_value = $this->SOLICITUD_CAMPOS[$NMB_CMP];

						if(!empty($HTML_ASOCC))
						{
							$Pos_id_first 	= strpos(strtoupper($HTML_ASOCC), 'ID="');
							$Str_tmp		= substr($HTML_ASOCC,$Pos_id_first+4);
							$Pos_id_last 	= strpos($Str_tmp, '"');
							$ID_CMP			= substr($HTML_ASOCC,$Pos_id_first+4,$Pos_id_last);

							$Value_asocc    = $this->SOLICITUD_CAMPOS[$ID_CMP];

							if(stripos($HTML_ASOCC, 'input') > 0)
								$HTML_ASOCC		= str_replace('value=""',"value='".$Value_asocc."'",$HTML_ASOCC);

							if( (stripos($HTML_ASOCC, 'select') > 0) && (!empty($Value_asocc)) )
								$HTML_ASOCC		= str_replace("value=\"".$Value_asocc."\"","value='".$Value_asocc."' SELECTED",$HTML_ASOCC);
							
						}

						if(!empty($CMP_ASOC))
							$CMP_ASOC_VALUE = $this->SOLICITUD_CAMPOS[$CMP_ASOC];


						$VALUE_CATALOG="SELECCIONE UNA OPCIÓN DEL CATÁLOGO...";
						if( ($TIPO_CMP=='CATALOG') && (!empty($CMP_ASOC)) && (!empty($CMP_ASOC_VALUE)) )
						{
							$Sql_consql="
										SELECT
											   Sql_consulta  AS SQL_CONS
										 FROM cat_tipo_credito_campos
										 WHERE Nombre_campo ='".$NMB_CMP."'
											AND ID_Tipocredito 		=	'".$this->ID_Tipocredito."'
											AND ID_Tipo_regimen 	= 	'".$this->ID_Tiposolicitud."'";
							$rs_consql=$this->db->Execute($Sql_consql);

							$SQL_CATALOG =str_replace("[VALOR]",$CMP_ASOC_VALUE,$rs_consql->fields["SQL_CONS"]);

							$Sql_consql=$SQL_CATALOG;
							$rs_consql=$this->db->Execute($Sql_consql);
							$VALUE_CATALOG=$rs_consql->fields["DESCP"];
						 
						}


						if(($TABLA_DEST == 'referencias') && ($TIPO_CMP!='LABEL'))
						{
							$Pos		=strrpos($NMB_CMP,'_referencia');
							$CMP_REF	=substr($NMB_CMP,0,$Pos);

							$Pos		 =strrpos($NMB_CMP,'_');
							$ORDEN  	 =substr($NMB_CMP,$Pos+1,strlen($NMB_CMP));
							$Campo_value = $this->get_consulta_referencia($CMP_REF,$this->INDEX_REFERENCIAS);
						}

						if(($TABLA_DEST == 'referencias_cheques') && ($TIPO_CMP!='LABEL'))
						{
							$Pos		=strrpos($NMB_CMP,'_referencias_cheques');
							$CMP_REF	=substr($NMB_CMP,0,$Pos);

							$Pos		 =strrpos($NMB_CMP,'_');
							$ORDEN  	 =substr($NMB_CMP,$Pos+1,strlen($NMB_CMP));
							$Campo_value = $this->get_consulta_referencia_cheques($CMP_REF,$this->INDEX_REFERENCIAS_CHEQUES);
						}
						
						if(($TABLA_DEST == 'referencias_bancarias') && ($TIPO_CMP!='LABEL'))
						{
							$Pos		=strrpos($NMB_CMP,'_referencias_bancarias');
							$CMP_REF	=substr($NMB_CMP,0,$Pos);

							$Pos		 =strrpos($NMB_CMP,'_');
							$ORDEN  	 =substr($NMB_CMP,$Pos+1,strlen($NMB_CMP));
							$Campo_value = $this->get_consulta_referencia_bancaria($CMP_REF,$this->INDEX_REFERENCIAS_BANCARIAS);
						}


						if(($TABLA_DEST == 'referencias_comerciales') && ($TIPO_CMP!='LABEL'))
						{
							$Pos		=strrpos($NMB_CMP,'_referencias_comerciales');
							$CMP_REF	=substr($NMB_CMP,0,$Pos);

							$Pos		 =strrpos($NMB_CMP,'_');
							$ORDEN  	 =substr($NMB_CMP,$Pos+1,strlen($NMB_CMP));
							$Campo_value = $this->get_consulta_referencia_comerciales($CMP_REF,$this->INDEX_REFERENCIAS_COMERCIALES);
						}

						if(($TABLA_DEST == 'proveedores') && ($TIPO_CMP!='LABEL'))
						{
							$Pos		=strrpos($NMB_CMP,'_proveedores');
							$CMP_REF	=substr($NMB_CMP,0,$Pos);

							$Pos		 =strrpos($NMB_CMP,'_');
							$ORDEN  	 =substr($NMB_CMP,$Pos+1,strlen($NMB_CMP));
							$Campo_value = $this->get_consulta_proveedores($CMP_REF,$this->INDEX_REFERENCIAS_PROVEEDORES);
						}

						if(($TABLA_DEST == 'funcionarios') && ($TIPO_CMP!='LABEL'))
						{
							$Pos		=strrpos($NMB_CMP,'_funcionario');
							$CMP_REF	=substr($NMB_CMP,0,$Pos);

							$Pos		 =strrpos($NMB_CMP,'_');
							$ORDEN  	 =substr($NMB_CMP,$Pos+1,strlen($NMB_CMP));
							$Campo_value = $this->get_consulta_funcionarios($CMP_REF,$this->INDEX_REFERENCIAS_FUNCIONARIOS);
						}

						if(($TABLA_DEST == 'funcionarios_autorizados') && ($TIPO_CMP!='LABEL'))
						{
							$Pos		=strrpos($NMB_CMP,'_funcionario_autorizado');
							$CMP_REF	=substr($NMB_CMP,0,$Pos);

							$Pos		 =strrpos($NMB_CMP,'_');
							$ORDEN  	 =substr($NMB_CMP,$Pos+1,strlen($NMB_CMP));
							$Campo_value = $this->get_consulta_funcionarios_autorizados($CMP_REF,$this->INDEX_REFERENCIAS_FUNCIONARIOS_AUT);
						}

						if(($TABLA_DEST == 'accionistas') && ($TIPO_CMP!='LABEL'))
						{
							$Pos		=strrpos($NMB_CMP,'_accionista');
							$CMP_REF	=substr($NMB_CMP,0,$Pos);

							$Pos		 =strrpos($NMB_CMP,'_');
							$ORDEN  	 =substr($NMB_CMP,$Pos+1,strlen($NMB_CMP));
							$Campo_value = $this->get_consulta_accionistas($CMP_REF,$this->INDEX_REFERENCIAS_ACCIONISTAS);
						}

						if($TIPO_CMP == 'SELECT_ARRAY' && $TABLA_DEST =='solicitud_campos_especiales')
							$Campo_value = $this->SOLICITUD_CAMPOS[$NMB_CMP];



						if($TIPO_CMP == 'SELECT' && !empty($SQL) && $TABLA_DEST !='solicitud_campos_especiales' )
						{
							$Campo_value 	= $this->SOLICITUD_CAMPOS[$NMB_CMP];
							$Sql_value 		=str_replace("[VALOR]",$Campo_value,$SQL);

						}

					//CAMPO AGREGADOS ESPECIALES TABLA solicitud_campos_especiales
					if($TABLA_DEST =='solicitud_campos_especiales')
					{
						$Campo_value = $this->get_consulta_campos_especiales($NMB_CMP);
						if($TIPO_CMP == 'SELECT' && !empty($SQL) ) 
						{
							$Sql_value 		=str_replace("[VALOR]",$Campo_value,$SQL);
							//$rs_val			=$this->db->Execute($Sql_value);
							//$Campo_value	=$rs_val->fields["DESCP"];
						}
					}
					//FIN CMP ESPECIALES

						if( (strstr($CLASS,'datepicker')) != FALSE)
							$Campo_value=ffecha($Campo_value);
		}

		$SRC_REFERENCIA="";
		if($TABLA_DEST == 'referencias' || $TABLA_DEST == 'referencias_bancarias' || $TABLA_DEST == 'referencias_comerciales' || $TABLA_DEST == 'proveedores' || $TABLA_DEST == 'funcionarios' || $TABLA_DEST == 'funcionarios_autorizados' || $TABLA_DEST == 'accionistas' || $TABLA_DEST == 'referencias_cheques' )

		
		switch ($TABLA_DEST)
		{
			case 'referencias':
				$SRC_REFERENCIA=" / REF. 1";
			break;

			case 'referencias_cheques':
				$SRC_REFERENCIA=" / CHQ. 1";
			break;
			
			case 'referencias_bancarias':
				$SRC_REFERENCIA=" / REF BANC. 1";
			break;

			case 'referencias_comerciales':
				$SRC_REFERENCIA=" / REF COM. 1";
			break;

			case 'proveedores':
				$SRC_REFERENCIA=" / REF PROV. 1";
			break;

			case 'funcionarios':
				$SRC_REFERENCIA=" / REF FUNC. 1";
			break;

			case 'funcionarios_autorizados':
				$SRC_REFERENCIA=" / REF FUNC AUT. 1";
			break;

			case 'accionistas':
				$SRC_REFERENCIA=" / REF ACC. 1";
			break;
	    }
		
		if($TIPO_CMP=='TEXT')
		{
				$VALID_NOMINA = array_key_exists($NMB_CMP,$this->CAMPOS_NOMINA);

				if($VALID_NOMINA && $this->VALIDATION_NOMINA == 'TRUE')
				{
					  if($NMB_CMP == 'RFC')
					  {
						$HID_TEXTII="<INPUT TYPE='HIDDEN' ID='Hclave'	VALUE='".$this->CAMPOS_NOMINA["Hclave"]."' />";
						$LBL_HCVE ="<LABEL>".$this->CAMPOS_NOMINA["Hclave"]."</LABEL>";
					  }
					  
					  $this->Input_Tag="    <TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:20%;'>        ".$ETQ." : </TH>
											   <TD STYLE='TEXT-ALIGN:LEFT;'>
												  <INPUT TYPE='HIDDEN' ID='".$NMB_CMP."' NAME='".$NMB_CMP."'	CLASS='".$CLASS."'	".$Title."	VALUE='".$this->CAMPOS_NOMINA[$NMB_CMP]."' />
												  ".$HID_TEXTII."
												  <LABEL>".$this->CAMPOS_NOMINA[$NMB_CMP]."</LABEL>
												  ".$LBL_HCVE."
											   </TD>";
				}
				else
				{

					  $Campo_value = ($this->VALIDATION_NOMINA == 'TRUE' && $NMB_CMP == 'Fecha_ingreso_empresa')?($this->NOMINA_FECHA_ING):($Campo_value);
					  $Campo_value = ($this->VALIDATION_NOMINA == 'TRUE' && $NMB_CMP == 'Tiempo_trabajoI')?($this->NOMINA_ANTIGD_EMP):($Campo_value);
					  				  

					  $this->Input_Tag="    <TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:20%;'>        ".$ETQ." : </TH>
											   <TD STYLE='TEXT-ALIGN:LEFT;'>
													  <INPUT TYPE='".$TIPO_CMP."' ID='".$NMB_CMP."' CLASS='".$CLASS."' NAME='".$NMB_CMP."' STYLE='".$STYLE."'    ".$EVNT." ".$Title."  SRC='".$ID_SECC."_".$ETQ."".$SRC_REFERENCIA."' LANG='".$CMP_ASOC."' ".$READONLY." VALUE='".$Campo_value."' /> ".$HTML_ASOCC." ".$Asterisk." 
											   </TD>";
				}
		}

		
		if($TIPO_CMP=='TEXTAREA')
		  $this->Input_Tag="    <TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:20%;'>        ".$ETQ." : </TH>
				                   <TD STYLE='TEXT-ALIGN:LEFT;'>
					                      <TEXTAREA ID='".$NMB_CMP."'  CLASS='".$CLASS."' NAME='".$NMB_CMP."' STYLE='".$STYLE."'    ".$EVNT." ".$Title."  SRC='".$ID_SECC."_".$ETQ."".$SRC_REFERENCIA."'  ".$READONLY." >
											".$Campo_value."
					                      </TEXTAREA>".$HTML_ASOCC." ".$Asterisk."
				                   </TD>";

		if($TIPO_CMP=='SELECT')
		{

				$VALID_NOMINA = array_key_exists($NMB_CMP,$this->CAMPOS_NOMINA);

			 if($VALID_NOMINA && $this->VALIDATION_NOMINA == 'TRUE')
				{
					$LABEL_VALUE =($NMB_CMP=='ID_Producto')?($this->CAMPOS_NOMINA['NMB_PROD']):($this->CAMPOS_NOMINA[$NMB_CMP]);
					$Vencimiento =($NMB_CMP=='Plazo')?($this->NOMINA_VENCIMIENTO):('');
					  $this->Input_Tag="    <TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:20%;'>        ".$ETQ." : </TH>
											   <TD STYLE='TEXT-ALIGN:LEFT;'>
												  <INPUT TYPE='HIDDEN' ID='".$NMB_CMP."' NAME='".$NMB_CMP."'	".$Title." VALUE='".$this->CAMPOS_NOMINA[$NMB_CMP]."' />
												  <LABEL>".$LABEL_VALUE." &nbsp;".$Vencimiento."</LABEL>
												  ".$HTML_ASOCC."
											   </TD>";
				}
				else
				{
					 $Combo=select_custom($NMB_CMP,$SQL,$Campo_value,$CLASS,$STYLE,$EVNT,$Title,$ID_SECC,$ETQ,$SRC_REFERENCIA, $this->ID_Tipocredito,$this->ID_SUC,$this->NOMINA_ID_EMP,$this->db);
						 $this->Input_Tag="    <TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:20%;'>        ".$ETQ." : </TH>
												   <TD STYLE='TEXT-ALIGN:LEFT;'>
														  ".$Combo." ".$Asterisk." ".$HTML_ASOCC."
												   </TD>";
				}
		}

		if($TIPO_CMP=='SELECT_ARRAY')
		{
				
				$Combo=select_custom_array($NMB_CMP,$SQL,$Campo_value,$CLASS,$STYLE,$EVNT,$Title,$ID_SECC,$ETQ,$SRC_REFERENCIA, $this->ID_Tipocredito,$this->db);
				$this->Input_Tag="    <TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:20%;'>        ".$ETQ." : </TH>
									   <TD STYLE='TEXT-ALIGN:LEFT;'>
											  ".$Combo." ".$Asterisk." ".$HTML_ASOCC."
									   </TD>";
		}		

		if($TIPO_CMP=='CATALOG')
		{
				$VALID_NOMINA = array_key_exists($NMB_CMP,$this->CAMPOS_NOMINA);

				//if($VALID_NOMINA && $this->VALIDATION_NOMINA == 'TRUE')
				//VALIDACIÖN ESPECIAL SIEMPRE EFECTIVO

				if( ($this->ID_Tipocredito == 3) && ($this->EDIT_PRIVILEGE != 'FALSE') )
				{
					$LABEL_VALUE =($NMB_CMP=='Empresa_soli')?($this->NOMINA_EMPRESA):($this->CAMPOS_NOMINA[$NMB_CMP]);
					
					  $this->Input_Tag="    <TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:20%;'>        ".$ETQ." : </TH>
											   <TD STYLE='TEXT-ALIGN:LEFT;'>
												  <INPUT TYPE='HIDDEN' ID='ID_empresa'   NAME='ID_empresa'	                VALUE='".$this->NOMINA_ID_EMP."' />
												  <INPUT TYPE='HIDDEN' ID='Empresa_soli' NAME='Empresa_soli'	".$Title."  VALUE='".$this->NOMINA_EMPRESA."' />
												  <LABEL>".$LABEL_VALUE."</LABEL>
												  
											   </TD>";
				}
				else 
				  $this->Input_Tag="    <TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:20%;'>        ".$ETQ." : </TH>
										   <TD STYLE='TEXT-ALIGN:LEFT;'>
												  <INPUT TYPE='TEXT' ID='".$NMB_CMP."' CLASS='".$CLASS."' NAME='".$NMB_CMP."' STYLE='".$STYLE."'    ".$EVNT." ".$Title."  SRC='".$ID_SECC."_".$ETQ."' LANG='".$CMP_ASOC."' ".$READONLY." VALUE='".$VALUE_CATALOG."' /> ".$Asterisk." ".$HTML_ASOCC."
												  <INPUT TYPE='HIDDEN' ID='".$CMP_ASOC."' VALUE='".$CMP_ASOC_VALUE."'/>
										   </TD>";
		}

		
		if($TIPO_CMP=='LABEL')
		  $this->Input_Tag="    <TD STYLE='".$STYLE."' COLSPAN='4'><LABEL ID='LABEL_".$CLASS."' CLASS='".$CLASS."'> ".$ETQ." </LABEL>  </TD>";

		if($TIPO_CMP=='BUTTON' )
		  $this->Input_Tag="    
				                   <TD STYLE='".$STYLE."' COLSPAN='4'>
					                      <BUTTON TYPE='".$TIPO_CMP."' ID='".$NMB_CMP."' CLASS='".$CLASS."' NAME='".$NMB_CMP."'     ".$EVNT." ".$Title."  SRC='".$ID_SECC."_".$ETQ."' LANG='".$CMP_ASOC."' ".$READONLY." VALUE='".$Campo_value."' /> ".$ETQ." </BUTTON> ".$HTML_ASOCC." 
				                   </TD>";
        
				if($NMB_CMP == 'ID_Promotor' && $this->ID_Tipocredito=='2')
					{
						$this->Input_Tag="    <TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:20%;'>        ".$ETQ." : </TH>
											   <TD STYLE='TEXT-ALIGN:LEFT;'>
													  ".$this->PROMO_GPO."
													  <INPUT TYPE='HIDDEN' ID='ID_Promotor' NAME='ID_Promotor'				VALUE='".$this->ID_PROMO."' />
											   </TD>";
					}
	}//FIN get_input_tag

	function get_labels_referencias($TMP_DNMCO)
	{
			switch($TMP_DNMCO)
			{

				case 'REFERENCIA':
					$ID_TABLE="REFERENCIAS_1"; $CLASS_REFER="TABLE_REFERENCIA"; $IMG_REFER="REFERENCIA";
				break;

				case 'REFERENCIA_CHEQUES':
					$ID_TABLE="CHEQUES_1"; $CLASS_REFER="TABLE_REFCHEQUES"; $IMG_REFER="REFERENCIA_CHEQUES";
				break;
				
				case 'REFERENCIA_ACCIONISTA':
					$ID_TABLE="ACCIONISTAS_1"; $CLASS_REFER="TABLE_ACCIONISTAS"; $IMG_REFER="REFERENCIA_ACCIONISTA";
				break;

				case 'REFERENCIA_FUNCIONARIO' :
					$ID_TABLE="FUNCIONARIOS_1"; $CLASS_REFER="TABLE_FUNCIONARIOS"; $IMG_REFER="REFERENCIA_FUNCIONARIO";
				break;

				case 'REFERENCIA_AUTORIZADO_FUNC' :
					$ID_TABLE="FUNCIONARIOSAUT_1"; $CLASS_REFER="TABLE_FUNCIONARIOSAUT"; $IMG_REFER="REFERENCIA_AUTORIZADO_FUNC";
				break;

				case 'REFERENCIA_PROVEEDOR' :
					$ID_TABLE="PROVEEDORES_1"; $CLASS_REFER="TABLE_PROVEEDORES"; $IMG_REFER="REFERENCIA_PROVEEDOR";
				break;

				case 'REFERENCIA_COMERCIAL' :
					$ID_TABLE="REFCOMERCIALES_1"; $CLASS_REFER="TABLE_REFCOMERCIALES"; $IMG_REFER="REFERENCIA_COMERCIAL";
				break;

				case 'REFERENCIA_BANCARIA' :
					$ID_TABLE="REFBANCARIAS_1"; $CLASS_REFER="TABLE_REFBANCARIAS"; $IMG_REFER="REFERENCIA_BANCARIA";
				break;
			}

		return $RES_ARR = array('ID_TABLE'=>$ID_TABLE,'CLASS_REFER'=>$CLASS_REFER,'IMG_REFER'=>$IMG_REFER);
		
	}

	function get_html_new_referencias($TMP_DNMCO,$TIPO_VISTA_REF=NULL)
	{


			switch($TMP_DNMCO)
			{

				case 'REFERENCIA':
					$HTML_REFER=( ($this->INDEX_REFERENCIAS == $this->REFERENCIAS_CAPTURADAS) || ($this->REFERENCIAS_CAPTURADAS == '0' )  )?("
								<TABLE WIDTH='100%' CELLPADDING='3' CELLSPACING='2' BORDER='0px'>
									<TR>
										<TD>
											<DIV ID='DIV_REFERE' ></DIV>
											<INPUT TYPE='HIDDEN' ID='REFERENCIAS_OBLIG' 		VALUE='".$this->REFERENCIAS_OBLIG."' />
										</TD>
									</TR>
								</TABLE>
																	
								<TABLE WIDTH='100%' CELLPADDING='3' CELLSPACING='2' BORDER='0px'>
									<TR STYLE='text-align:center;'>
										<TD COLSPAN='4'><BUTTON TYPE='BUTTON' ID='NEW_REFERENCIA'>NUEVA REFERENCIA</BUTTON></TD>
									</TR>
								</TABLE>"):("");
				break;

				case 'REFERENCIA_CHEQUES':
					$HTML_REFER=( ($this->INDEX_REFERENCIAS_CHEQUES == $this->REFERENCIAS_CAPTURADAS_CHEQUES) || ($this->REFERENCIAS_CAPTURADAS_CHEQUES == '0' &&  $TIPO_VISTA_REF == NULL )  )?("
								<TABLE WIDTH='100%' CELLPADDING='3' CELLSPACING='2' BORDER='0px'>
									<TR>
										<TD>
											<DIV ID='DIV_REFERE_CHEQ' ></DIV>
											<INPUT TYPE='HIDDEN' ID='REFERENCIAS_CHEQUES_OBLIG' 		VALUE='".$this->REFERENCIAS_CHEQUES_OBLIG."' />
										</TD>
									</TR>
								</TABLE>
																	
								<TABLE WIDTH='100%' CELLPADDING='3' CELLSPACING='2' BORDER='0px'>
									<TR STYLE='text-align:center;'>
										<TD COLSPAN='4'><LABEL ID='TOTAL_LIQUIDO' STYLE='font-weight:bold; font-style:italic;' >TOTAL LÍQUIDO:</LABEL></TD>
									</TR>
									<TR STYLE='text-align:center;'>
										<TD COLSPAN='4'><BUTTON TYPE='BUTTON' ID='NEW_REFERENCIA_CHEQUE'>NUEVO TALÓN.</BUTTON></TD>
									</TR>
								</TABLE>"):("");

					$HTML_REFER=( ($this->INDEX_REFERENCIAS_CHEQUES == $this->REFERENCIAS_CAPTURADAS_CHEQUES) || ($this->REFERENCIAS_CAPTURADAS_CHEQUES == '0' )  &&  $TIPO_VISTA_REF != NULL )?("
								<TABLE WIDTH='100%' CELLPADDING='3' CELLSPACING='2' BORDER='0px'>
									<TR STYLE='text-align:center;'>
										<TD COLSPAN='4'><LABEL ID='TOTAL_LIQUIDO' STYLE='font-weight:bold; font-style:italic;' >TOTAL LÍQUIDO:</LABEL></TD>
									</TR>
									<DIV ID='DIV_REFERE_CHEQ' ></DIV>
									<TR STYLE='text-align:center;'>
										<TD COLSPAN='4'><BUTTON TYPE='BUTTON' ID='NEW_REFERENCIA_CHEQUE'>NUEVO TALÓN.</BUTTON></TD>
									</TR>
								</TABLE>"):($HTML_REFER);


				break;
				
				case 'REFERENCIA_ACCIONISTA':
					$HTML_REFER=( ($this->INDEX_REFERENCIAS_ACCIONISTAS == $this->REFERENCIAS_CAPTURADAS_ACCIONISTAS) || ($this->REFERENCIAS_CAPTURADAS_ACCIONISTAS == '0' )  )?("
								<TABLE WIDTH='100%' CELLPADDING='3' CELLSPACING='2' BORDER='0px'>
									<TR>
										<TD>
											<DIV ID='DIV_REFERE_ACCIONISTAS' ></DIV>
											<INPUT TYPE='HIDDEN' ID='REFERENCIAS_ACCIONISTAS_OBLIG' 		VALUE='".$this->REFERENCIAS_ACCIONISTAS_OBLIG."' />
										</TD>
									</TR>
								</TABLE>
																	
								<TABLE WIDTH='100%' CELLPADDING='3' CELLSPACING='2' BORDER='0px'>
									<TR STYLE='text-align:center;'>
										<TD COLSPAN='4'><BUTTON TYPE='BUTTON' ID='NEW_REFERENCIA_ACCION'>NUEVO ACCIONISTA</BUTTON></TD>
									</TR>
								</TABLE>"):("");
				
				break;

				case 'REFERENCIA_FUNCIONARIO' :
					$HTML_REFER=( ($this->INDEX_REFERENCIAS_FUNCIONARIOS == $this->REFERENCIAS_CAPTURADAS_FUNCIONARIOS) || ($this->REFERENCIAS_CAPTURADAS_FUNCIONARIOS == '0' )  )?("
								<TABLE WIDTH='100%' CELLPADDING='3' CELLSPACING='2' BORDER='0px'>
									<TR>
										<TD>
											<DIV ID='DIV_REFERE_FUNCIONARIOS' ></DIV>
											<INPUT TYPE='HIDDEN' ID='REFERENCIAS_FUNCIONARIOS_OBLIG' 		VALUE='".$this->REFERENCIAS_FUNCIONARIOS_OBLIG."' />
										</TD>
									</TR>
								</TABLE>
																	
								<TABLE WIDTH='100%' CELLPADDING='3' CELLSPACING='2' BORDER='0px'>
									<TR STYLE='text-align:center;'>
										<TD COLSPAN='4'><BUTTON TYPE='BUTTON' ID='NEW_REFERENCIA_FUNCION'>NUEVO FUNCIONARIO</BUTTON></TD>
									</TR>
								</TABLE>"):("");
				break;

				case 'REFERENCIA_AUTORIZADO_FUNC' :
					$HTML_REFER=( ($this->INDEX_REFERENCIAS_FUNCIONARIOS_AUT == $this->REFERENCIAS_CAPTURADAS_FUNCIONARIOS_AUT) || ($this->REFERENCIAS_CAPTURADAS_FUNCIONARIOS_AUT == '0' )  )?("
								<TABLE WIDTH='100%' CELLPADDING='3' CELLSPACING='2' BORDER='0px'>
									<TR>
										<TD>
											<DIV ID='DIV_REFERE_FUNCIONARIOSAUTO' ></DIV>
											<INPUT TYPE='HIDDEN' ID='REFERENCIAS_FUNCIONARIOS_AUT_OBLIG' 		VALUE='".$this->REFERENCIAS_FUNCIONARIOS_AUT_OBLIG."' />
										</TD>
									</TR>
								</TABLE>
																	
								<TABLE WIDTH='100%' CELLPADDING='3' CELLSPACING='2' BORDER='0px'>
									<TR STYLE='text-align:center;'>
										<TD COLSPAN='4'><BUTTON TYPE='BUTTON' ID='NEW_REFERENCIA_FUNCIONAUTO'>NUEVO FUNCIONARIO AUTORIZADO</BUTTON></TD>
									</TR>
								</TABLE>"):("");
				break;

				case 'REFERENCIA_PROVEEDOR' :
					$HTML_REFER=( ($this->INDEX_REFERENCIAS_PROVEEDORES == $this->REFERENCIAS_CAPTURADAS_PROVEEDORES) || ($this->REFERENCIAS_CAPTURADAS_PROVEEDORES == '0' )  )?("
								<TABLE WIDTH='100%' CELLPADDING='3' CELLSPACING='2' BORDER='0px'>
									<TR>
										<TD>
											<DIV ID='DIV_REFERE_PROVEEDORES' ></DIV>
											<INPUT TYPE='HIDDEN' ID='REFERENCIAS_PROVEEDORES_OBLIG' 		VALUE='".$this->REFERENCIAS_PROVEEDORES_OBLIG."' />
										</TD>
									</TR>
									</TABLE>
																		
								<TABLE WIDTH='100%' CELLPADDING='3' CELLSPACING='2' BORDER='0px'>
									<TR STYLE='text-align:center;'>
										<TD COLSPAN='4'><BUTTON TYPE='BUTTON' ID='NEW_REFERENCIA_PROVEEDORES'>NUEVO PROVEEDOR</BUTTON></TD>
									</TR>
							  </TABLE>"):("");
				break;

				case 'REFERENCIA_COMERCIAL' :
					$HTML_REFER=( ($this->INDEX_REFERENCIAS_COMERCIALES == $this->REFERENCIAS_CAPTURADAS_COMERCIALES) || ($this->REFERENCIAS_CAPTURADAS_COMERCIALES == '0' )  )?("
								<TABLE WIDTH='100%' CELLPADDING='3' CELLSPACING='2' BORDER='0px'>
										<TR>
											<TD>
												<DIV ID='DIV_REFERE_COMERCIAL' ></DIV>
												<INPUT TYPE='HIDDEN' ID='REFERENCIAS_COMERCIALES_OBLIG' 		VALUE='".$this->REFERENCIAS_COMERCIALES_OBLIG."' />
											</TD>
										</TR>
									</TABLE>
																
									<TABLE WIDTH='100%' CELLPADDING='3' CELLSPACING='2' BORDER='0px'>
										<TR STYLE='text-align:center;'>
											<TD COLSPAN='4'><BUTTON TYPE='BUTTON' ID='NEW_REFERENCIA_COMERCIAL'>NUEVA REFERENCIA COMERCIAL</BUTTON></TD>
										</TR>
								</TABLE>"):("");
				break;

				case 'REFERENCIA_BANCARIA' :
					$HTML_REFER=( ($this->INDEX_REFERENCIAS_BANCARIAS == $this->REFERENCIAS_CAPTURADAS_BANCARIAS) || ($this->REFERENCIAS_CAPTURADAS_BANCARIAS == '0' )  )?("
								<TABLE WIDTH='100%' CELLPADDING='3' CELLSPACING='2' BORDER='0px'>
									<TR>
										<TD>
											<DIV ID='DIV_REFERE_BANCARIA' ></DIV>
											<INPUT TYPE='HIDDEN' ID='REFERENCIAS_BANCARIAS_OBLIG' 		VALUE='".$this->REFERENCIAS_BANCARIAS_OBLIG."' />
										</TD>
									</TR>
								</TABLE>
																	
								<TABLE WIDTH='100%' CELLPADDING='3' CELLSPACING='2' BORDER='0px'>
									<TR STYLE='text-align:center;'>
										<TD COLSPAN='4'><BUTTON TYPE='BUTTON' ID='NEW_REFERENCIA_BANCARIA'>NUEVA REFERENCIA BANCARIA</BUTTON></TD>
									</TR>
								</TABLE>"):("");
				break;
			}

		return $HTML_REFER;
	}

	function get_soli_renovacion()
	{
		$TBL_CTE_DEST			= ($this->ID_Tipocredito < 4)?('clientes_datos'):('clientes_datos_pmoral');

		$SQL_SEARCH="SELECT
							ID_Solicitud		AS ID_SOLI
						FROM 
							".$TBL_CTE_DEST."
						WHERE
								Num_cliente = '".$this->NUM_CLIENTE."' ";
		$rs_search=$this->db->Execute($SQL_SEARCH);


		$this->ID_SOLICITUD = $rs_search->fields["ID_SOLI"];

	}

	function Render_captura()
	{

			   $this->get_encabezado_soli('CAPTURA');//ENCABEZADO DE LA SOLICITUD FECHA,SUCURSAL, CAPTURISTA ETC..

				if(!empty($this->NUM_CLIENTE))
				{
					 $this->get_soli_renovacion();
					 $this->get_consulta_soli();
				}


				$Sql_campos="SELECT 
								   cat_tipo_credito_campos.ID_campo                 AS ID_CMP,
								   cat_tipo_credito_campos.ID_seccion     			AS ID_SECC,
								   cat_tipo_credito_secciones.Orden					AS ORD_SECC,
								   cat_tipo_credito_secciones.Nombre				AS NMB_SECC,
								   cat_tipo_credito_campos.Etiqueta					AS ETQ,
								   cat_tipo_credito_campos.Obligatorio				AS OBLG,
								   cat_tipo_credito_campos.Obligatorio_sistema		AS OBLG_SIST,
								   cat_tipo_credito_campos.Visibilidad				AS VSBL,
								   cat_tipo_credito_campos.Orden					AS ORDN,
								   cat_tipo_credito_campos.Nombre_campo				AS NMB_CMP,
								   cat_tipo_credito_campos.Tipo						AS TIPO_CMP,
								   cat_tipo_credito_campos.Style					AS STYLE,
								   cat_tipo_credito_campos.Evento					AS EVNT,
								   cat_tipo_credito_campos.Class					AS CLASS,
								   cat_tipo_credito_campos.Sql						AS SQL_,
								   cat_tipo_credito_campos.Readonly					AS READON,
								   cat_tipo_credito_campos.Html						AS HTML,
								   cat_tipo_credito_campos.List_cmp_asoc			AS CMP_ASOC,
								   cat_tipo_credito_campos.Tabla_destino			AS TABLA_DEST,
								   cat_tipo_credito_secciones.Datos_dinamicos		AS CMP_DINAMICOS,
								   cat_tipo_credito_secciones.Tipo_dinamico			AS TMP_DNMCO 
							FROM
									cat_tipo_credito_campos
								LEFT JOIN cat_tipo_credito_secciones ON cat_tipo_credito_campos.ID_seccion = cat_tipo_credito_secciones.ID_seccion
									AND cat_tipo_credito_campos.ID_seccion IS NOT NULL
							WHERE   cat_tipo_credito_campos.ID_Tipocredito 		=	'".$this->ID_Tipocredito."'
								AND cat_tipo_credito_campos.ID_Tipo_regimen 	=	'".$this->ID_Tiposolicitud."'
								AND cat_tipo_credito_campos.Visibilidad ='Y'
							 ORDER BY ORD_SECC,ORDN ";
			   $rs_campos=$this->db->Execute($Sql_campos);       

							$Comp_secc="";
							$Row_count=0;
							$this->get_campos_referencia();
							$this->get_referencias_capturadas();
							
							While(!$rs_campos->EOF)
							{


									  if($Comp_secc != $rs_campos->fields["ID_SECC"])
										{

												  $this->Body_soli($rs_campos->fields["ID_SECC"],$rs_campos->fields["NMB_SECC"]);
												  $this->HTML.=($Comp_secc=="")?(""):("</TABLE><BR /></DIV>");//FIN DIV DEL TAB O ACORDIÓN SECCIÓN
												  $this->HTML.=$this->Design_Tag; //TAG ACORDION Ó TABS 



												if($rs_campos->fields["CMP_DINAMICOS"] == 'N')
													{$ID_TABLE="";$CLASS_REFER=""; $IMG_REFER="";}
												else
													{
														$ARR_ID_REFERES = $this->get_labels_referencias($rs_campos->fields["TMP_DNMCO"]);
														$ID_TABLE       = $ARR_ID_REFERES["ID_TABLE"];$CLASS_REFER=$ARR_ID_REFERES["CLASS_REFER"]; $IMG_REFER=$ARR_ID_REFERES["IMG_REFER"];
													}


												  $Comp_secc=$rs_campos->fields["ID_SECC"];
												   $this->HTML.="<BR />
																 <TABLE  CELLPADDING='3' CELLSPACING='2' BORDER='0px' WIDTH='100%' CLASS='".$CLASS_REFER."' ID='".$ID_TABLE."'>";
										}

							 if($this->COLUMNS == '2')//HA DOBLE COLUMNA
							 {

											if( ($rs_campos->fields["TIPO_CMP"] != 'LABEL') && ($rs_campos->fields["TIPO_CMP"] != 'BUTTON') )
											{
													  $Title     =($rs_campos->fields["OBLG"]=='Y')?("TITLE='CAMPO REQUERIDO'"):("");
													  $Asterisk  =($rs_campos->fields["OBLG"]=='Y')?("&nbsp;<IMG ID='IMG_".$rs_campos->fields["NMB_CMP"]."' CLASS='".$IMG_REFER."' BORDER=0 SRC='".$this->IMG."asterisk.png'  ALT='editando'  STYLE='height:13px; width:13px; align:top;' />"):("");
													  $this->get_input_tag($rs_campos->fields["ETQ"],$rs_campos->fields["TIPO_CMP"],$rs_campos->fields["NMB_CMP"],$rs_campos->fields["CLASS"],$rs_campos->fields["EVNT"],$rs_campos->fields["STYLE"],$rs_campos->fields["ID_SECC"],$rs_campos->fields["SQL_"],$rs_campos->fields["READON"],$rs_campos->fields["HTML"],$rs_campos->fields["CMP_ASOC"],$rs_campos->fields["TABLA_DEST"],$Title,$Asterisk);
													  $this->HTML.="<TR STYLE='BACKGROUND-COLOR:#e7eef6;'>";
													  $this->HTML.=$this->Input_Tag;

													//HA DOBLE COLUMNA
													$rs_campos->MoveNext();
													$Row_count++;
													$Bandera_secc=($Comp_secc != $rs_campos->fields["ID_SECC"])?("FALSE"):("TRUE");
													
													
													 if( (!$rs_campos->EOF) && ($Bandera_secc=='TRUE') && ($rs_campos->fields["TIPO_CMP"] != 'LABEL') && ($rs_campos->fields["TIPO_CMP"] != 'BUTTON'))
													 {
															$Title=($rs_campos->fields["OBLG"]=='Y')?("TITLE='CAMPO REQUERIDO'"):("");
															$Asterisk  =($rs_campos->fields["OBLG"]=='Y')?("&nbsp;<IMG ID='IMG_".$rs_campos->fields["NMB_CMP"]."' CLASS='".$IMG_REFER."'  BORDER=0 SRC='".$this->IMG."asterisk.png'  ALT='editando'  STYLE='height:13px; width:13px; align:top;' />"):("");
															$this->get_input_tag($rs_campos->fields["ETQ"],$rs_campos->fields["TIPO_CMP"],$rs_campos->fields["NMB_CMP"],$rs_campos->fields["CLASS"],$rs_campos->fields["EVNT"],$rs_campos->fields["STYLE"],$rs_campos->fields["ID_SECC"],$rs_campos->fields["SQL_"],$rs_campos->fields["READON"],$rs_campos->fields["HTML"],$rs_campos->fields["CMP_ASOC"],$rs_campos->fields["TABLA_DEST"],$Title,$Asterisk);
													  
													  $this->HTML.=$this->Input_Tag ."</TR>";

													  

													 }
													 else
													 {
															if(($rs_campos->fields["TIPO_CMP"] == 'LABEL' || $rs_campos->fields["TIPO_CMP"] == 'BUTTON') && ($Bandera_secc=='TRUE'))
															{
																 $this->HTML.=" 
																			   <TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:20%;'>       &nbsp; </TH>
																			   <TD STYLE='TEXT-ALIGN:LEFT;'>                   &nbsp; </TD>
																			 </TR>";

																$this->HTML.="<TR STYLE='BACKGROUND-COLOR:#f2f5f7;'>";
																$this->get_input_tag($rs_campos->fields["ETQ"],$rs_campos->fields["TIPO_CMP"],'',$rs_campos->fields["CLASS"],'',$rs_campos->fields["STYLE"],'','','',$rs_campos->fields["HTML"],'','','','');
																$this->HTML.=$this->Input_Tag ."</TR>";

															}
															elseif($Bandera_secc=='FALSE')
															{
															 $this->HTML.=" 
																			   <TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:20%;'>       &nbsp; </TH>
																			   <TD STYLE='TEXT-ALIGN:LEFT;'>                   &nbsp;</TD>
																			 </TR>";

														     }
    
													}
											}
											else
											{
													$this->HTML.="<TR STYLE='BACKGROUND-COLOR:#f2f5f7;'>";
													$this->get_input_tag($rs_campos->fields["ETQ"],$rs_campos->fields["TIPO_CMP"],'',$rs_campos->fields["CLASS"],'',$rs_campos->fields["STYLE"],'','','',$rs_campos->fields["HTML"],'','','','');
													$this->HTML.=$this->Input_Tag ."</TR>";

													$Bandera_secc="FALSE";
													$Row_count++;

											}

											 if($Bandera_secc=='FALSE')
												$rs_campos->Move(($Row_count-1));
											 else
											   $Row_count++;

								}

								 if($this->COLUMNS == '1')//HA DOBLE COLUMNA
								 {
													  $Title     =($rs_campos->fields["OBLG"]=='Y')?("TITLE='CAMPO REQUERIDO'"):("");
													  $Asterisk  =($rs_campos->fields["OBLG"]=='Y')?("&nbsp;<IMG ID='IMG_".$rs_campos->fields["NMB_CMP"]."' CLASS='".$IMG_REFER."' BORDER=0 SRC='".$this->IMG."asterisk.png'  ALT='editando'  STYLE='height:13px; width:13px; align:top;' />"):("");
													  $this->get_input_tag($rs_campos->fields["ETQ"],$rs_campos->fields["TIPO_CMP"],$rs_campos->fields["NMB_CMP"],$rs_campos->fields["CLASS"],$rs_campos->fields["EVNT"],$rs_campos->fields["STYLE"],$rs_campos->fields["ID_SECC"],$rs_campos->fields["SQL_"],$rs_campos->fields["READON"],$rs_campos->fields["HTML"],$rs_campos->fields["CMP_ASOC"],$rs_campos->fields["TABLA_DEST"],$Title,$Asterisk);
													  $this->HTML.="<TR STYLE='BACKGROUND-COLOR:#e7eef6;'>";
													  $this->HTML.=$this->Input_Tag;

								 }

													$CMP_DINAMICOS		=	$rs_campos->fields["CMP_DINAMICOS"];
													$TMP_DNMCO			=	$rs_campos->fields["TMP_DNMCO"];
							$rs_campos->MoveNext();


							//VALIDAR REFERENCIAS DINÁMICAS
							if($Comp_secc != $rs_campos->fields["ID_SECC"] &&  $CMP_DINAMICOS	== 'Y')	
								{
											     if( ($TMP_DNMCO == 'REFERENCIA_ACCIONISTA')    )
											     {
														if($this->INDEX_REFERENCIAS_ACCIONISTAS < $this->REFERENCIAS_CAPTURADAS_ACCIONISTAS)
														{
																$this->HTML.="</TABLE>";//FIN TABLE REFERENCIA
																$this->HTML.="<TABLE  CELLPADDING='3' CELLSPACING='2' BORDER='0px' WIDTH='100%' >";//INICIO TABLE REFERENCIA
																$Row_count		= $Row_count - $this->CAMPOS_REFERENCIAS_ACCIONISTAS;
																$rs_campos->Move(($Row_count));
														}
														else
														{
																//AGREGAR REFERENCIAS
																$this->HTML.=$this->get_html_new_referencias($TMP_DNMCO);
														}
														$this->INDEX_REFERENCIAS_ACCIONISTAS ++; 


												 }
											 else if( ($TMP_DNMCO == 'REFERENCIA_FUNCIONARIO')   )
											     {

														if($this->INDEX_REFERENCIAS_FUNCIONARIOS < $this->REFERENCIAS_CAPTURADAS_FUNCIONARIOS)
														{
																$this->HTML.="</TABLE>";//FIN TABLE REFERENCIA
																$this->HTML.="<TABLE  CELLPADDING='3' CELLSPACING='2' BORDER='0px' WIDTH='100%' >";//INICIO TABLE REFERENCIA
																$Row_count		= $Row_count - $this->CAMPOS_REFERENCIAS_FUNCIONARIOS;
																$rs_campos->Move(($Row_count));
														}
														else
														{
																//AGREGAR REFERENCIAS
																$this->HTML.=$this->get_html_new_referencias($TMP_DNMCO);
														}
														$this->INDEX_REFERENCIAS_FUNCIONARIOS ++; 

												 }
											 else if( ($TMP_DNMCO == 'REFERENCIA_AUTORIZADO_FUNC'  )  )
											     {
														if($this->INDEX_REFERENCIAS_FUNCIONARIOS_AUT < $this->REFERENCIAS_CAPTURADAS_FUNCIONARIOS_AUT)
														{
																$this->HTML.="</TABLE>";//FIN TABLE REFERENCIA
																$this->HTML.="<TABLE  CELLPADDING='3' CELLSPACING='2' BORDER='0px' WIDTH='100%' >";//INICIO TABLE REFERENCIA
																$Row_count		= $Row_count - $this->CAMPOS_REFERENCIAS_FUNCIONARIOS_AUT;
																$rs_campos->Move(($Row_count));
														}
														else
														{
																//AGREGAR REFERENCIAS
																$this->HTML.=$this->get_html_new_referencias($TMP_DNMCO);
														}
														$this->INDEX_REFERENCIAS_FUNCIONARIOS_AUT ++; 


												 }
											 else if( ($TMP_DNMCO == 'REFERENCIA_PROVEEDOR'  )   )
											     {
														if($this->INDEX_REFERENCIAS_PROVEEDORES < $this->REFERENCIAS_CAPTURADAS_PROVEEDORES)
														{
																$this->HTML.="</TABLE>";//FIN TABLE REFERENCIA
																$this->HTML.="<TABLE  CELLPADDING='3' CELLSPACING='2' BORDER='0px' WIDTH='100%' >";//INICIO TABLE REFERENCIA
																$Row_count		= $Row_count - $this->CAMPOS_REFERENCIAS_PROVEEDORES;
																$rs_campos->Move(($Row_count));
														}
														else
														{
																//AGREGAR REFERENCIAS
																$this->HTML.=$this->get_html_new_referencias($TMP_DNMCO);
														}
														$this->INDEX_REFERENCIAS_PROVEEDORES ++; 


												 }
											 else if( ($TMP_DNMCO == 'REFERENCIA_BANCARIA'  )   )
											     {

														if($this->INDEX_REFERENCIAS_BANCARIAS < $this->REFERENCIAS_CAPTURADAS_BANCARIAS)
														{
																$this->HTML.="</TABLE>";//FIN TABLE REFERENCIA
																$this->HTML.="<TABLE  CELLPADDING='3' CELLSPACING='2' BORDER='0px' WIDTH='100%' >";//INICIO TABLE REFERENCIA
																$Row_count		= $Row_count - $this->CAMPOS_REFERENCIAS_BANCARIAS;
																$rs_campos->Move(($Row_count));
														}
														else
														{
																//AGREGAR REFERENCIAS
																$this->HTML.=$this->get_html_new_referencias($TMP_DNMCO);
														}
														$this->INDEX_REFERENCIAS_BANCARIAS ++; 

												 }
											 else if( ($TMP_DNMCO == 'REFERENCIA_COMERCIAL'  ) )
											     {
													 
														if($this->INDEX_REFERENCIAS_COMERCIALES < $this->REFERENCIAS_CAPTURADAS_COMERCIALES)
														{
																$this->HTML.="</TABLE>";//FIN TABLE REFERENCIA
																$this->HTML.="<TABLE  CELLPADDING='3' CELLSPACING='2' BORDER='0px' WIDTH='100%' >";//INICIO TABLE REFERENCIA
																$Row_count		= $Row_count - $this->CAMPOS_REFERENCIAS_COMERCIALES;
																$rs_campos->Move(($Row_count));
														}
														else
														{
																//AGREGAR REFERENCIAS
																$this->HTML.=$this->get_html_new_referencias($TMP_DNMCO);
														}
														$this->INDEX_REFERENCIAS_COMERCIALES ++; 


												 }
											 else if( ($TMP_DNMCO == 'REFERENCIA_CHEQUES'  ) )
											     {
													 
														if($this->INDEX_REFERENCIAS_CHEQUES < $this->REFERENCIAS_CAPTURADAS_CHEQUES)
														{
																$this->HTML.="</TABLE>";//FIN TABLE REFERENCIA
																$this->HTML.="<TABLE  CELLPADDING='3' CELLSPACING='2' BORDER='0px' WIDTH='100%' >";//INICIO TABLE REFERENCIA
																$Row_count		= $Row_count - $this->CAMPOS_REFERENCIAS_CHEQUES;
																$rs_campos->Move(($Row_count));
														}
														else
														{
																//AGREGAR REFERENCIAS
																$this->HTML.=$this->get_html_new_referencias($TMP_DNMCO);
														}
														$this->INDEX_REFERENCIAS_CHEQUES ++; 


												 }
											 else if( ($TMP_DNMCO == 'REFERENCIA'   )  )
											     {
													 
														if (($this->INDEX_REFERENCIAS < $this->REFERENCIAS_CAPTURADAS) )
														{
																$this->HTML.="</TABLE>";//FIN TABLE REFERENCIA
																$this->HTML.="<TABLE  CELLPADDING='3' CELLSPACING='2' BORDER='0px' WIDTH='100%' >";//INICIO TABLE REFERENCIA
																$Row_count		= $Row_count - $this->CAMPOS_REFERENCIAS;
																$rs_campos->Move(($Row_count));
																														
														}
														else
														{
																//AGREGAR REFERENCIAS
																$this->HTML.=$this->get_html_new_referencias($TMP_DNMCO);
														}
														$this->INDEX_REFERENCIAS ++; 
														
														

												 }

												 
								}//FIN REFERENCIAS DINÁMICAS
														
													
													  
							}

				 $this->HTML.="</DIV>
				                  </DIV>";//FIN DIV ACORDIÓN O TABS CONTENEDOR //FIN DIV PRINCIPAL
				 $this->HTML.="<DIV ID='dialog-catalog' TITLE='AVISO S2CREDIT.'  STYLE='DISPLAY:NONE;'>
							   </DIV> ";
				 $this->HTML.="<DIV ID='dialog-searchcp' TITLE='BUSCAR CÓDIGO POSTAL.'  STYLE='DISPLAY:NONE;'>
							   </DIV> ";
							   
				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='TIPO_CREDITO' 				VALUE='".$this->ID_Tipocredito."' 		/>";
				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='TIPO_SOLICITUD' 			VALUE='".$this->ID_Tiposolicitud."'		/>";
				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='TIPO_REGIMEN' 				VALUE='".$this->TIPO_REGIMEN."'			/>";
				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='ACCION_SOLICITUD' 			VALUE='CAPTURAR' />";
				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='NOMINA_ESPECIAL' 			VALUE='".$this->VALIDATION_NOMINA."'	/>";
				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='NOMINA_ID_SOLI_IMP' 		VALUE='".$this->NOMINA_ID_SOLI_IMP."' 	/>";
				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='NOMINA_RFC' 				VALUE='".$this->NOMINA_RFC."'			/>";
				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='INGRESOS_NOM_ESP'			VALUE='".$this->NOMINA_TOTAL."'			/>";
				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='EGRESOS_NOM_ESP' 			VALUE='".$this->NOMINA_LIQUID."'		/>";
				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='GENERA_RFC' 				VALUE='".$this->GENERA_RFC."'			/>";
 				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='GENERA_HCLAVE' 				VALUE='".$this->GENERA_HCLAVE."'		/>";
 				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='GENERA_CURP' 				VALUE='".$this->GENERA_CURP."'			/>";
 				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='CURP_DESGLOSAR' 			VALUE='".$this->CURP_DESGLOSAR."'		/>";
				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='NUM_CLIENTE' 				VALUE='".$this->NUM_CLIENTE."'			/>";

				 $this->HTML.="</FORM>";//FIN FORMULARIO

	}//FIN Render_captura

   /****************CAPTURA DE SOLICITUDES***********************/

   //CAMPOS DE LA REFERENCIA VISIBLES

	function get_campos_referencia()
	{

		$ARR_REFERENCIAS=array('REFERENCIA_ACCIONISTA','REFERENCIA_FUNCIONARIO','REFERENCIA_AUTORIZADO_FUNC','REFERENCIA_PROVEEDOR','REFERENCIA_COMERCIAL','REFERENCIA_BANCARIA','REFERENCIA','REFERENCIA_CHEQUES');

		foreach($ARR_REFERENCIAS AS &$TIPO_REF)
			{

										switch($TIPO_REF)
										{
											case 'REFERENCIA_ACCIONISTA':
												$TABLE_INS		= 'accionistas';
											break; 

											case 'REFERENCIA_FUNCIONARIO':
												$TABLE_INS	    = 'funcionarios';
											break; 

											case 'REFERENCIA_AUTORIZADO_FUNC':
												$TABLE_INS	    = 'funcionarios_autorizados';
											break;

											case 'REFERENCIA_PROVEEDOR':
												$TABLE_INS	    = 'proveedores';
											break;

											case 'REFERENCIA_COMERCIAL':
												$TABLE_INS	    = 'referencias_comerciales';
											break;

											case 'REFERENCIA_BANCARIA':
												$TABLE_INS		= 'referencias_bancarias';
											break;
											
											case 'REFERENCIA_CHEQUES':
												$TABLE_INS		= 'referencias_cheques';
											break;
											
											default:
												$TABLE_INS	    = 'referencias';
											break;
										}


									$Sql_campos="SELECT 
														COUNT(ID_campo) AS CUANTOS
												FROM cat_tipo_credito_campos
													WHERE ID_Tipocredito 		= '".$this->ID_Tipocredito."'
														  AND ID_Tipo_regimen   = '".$this->ID_Tiposolicitud."'
														  AND Tabla_destino ='".$TABLE_INS."'
														  AND Visibilidad ='Y'
														  AND ID_seccion IS NOT NULL";
								   $rs_campos=$this->db->Execute($Sql_campos); 


										switch($TIPO_REF)
										{
											case 'REFERENCIA_ACCIONISTA':
												$this->CAMPOS_REFERENCIAS_ACCIONISTAS =  $rs_campos->fields["CUANTOS"];
											break; 

											case 'REFERENCIA_FUNCIONARIO':
												$this->CAMPOS_REFERENCIAS_FUNCIONARIOS =  $rs_campos->fields["CUANTOS"];
											break; 

											case 'REFERENCIA_AUTORIZADO_FUNC':
												$this->CAMPOS_REFERENCIAS_FUNCIONARIOS_AUT =  $rs_campos->fields["CUANTOS"];
											break;

											case 'REFERENCIA_PROVEEDOR':
												$this->CAMPOS_REFERENCIAS_PROVEEDORES =  $rs_campos->fields["CUANTOS"];
											break;

											case 'REFERENCIA_COMERCIAL':
												$this->CAMPOS_REFERENCIAS_COMERCIALES =  $rs_campos->fields["CUANTOS"];
											break;

											case 'REFERENCIA_BANCARIA':
												$this->CAMPOS_REFERENCIAS_BANCARIAS =  $rs_campos->fields["CUANTOS"];
											break;

											case 'REFERENCIA_CHEQUES':
												$this->CAMPOS_REFERENCIAS_CHEQUES =  $rs_campos->fields["CUANTOS"];
											break;
											
											default:
												$this->CAMPOS_REFERENCIAS =  $rs_campos->fields["CUANTOS"];
											break;
										}
					}//FOREACH
	}

   //REFERENCIAS CAPTURADAS
   function get_referencias_capturadas()
   {
		$ARR_REFERENCIAS=array('REFERENCIA_ACCIONISTA','REFERENCIA_FUNCIONARIO','REFERENCIA_AUTORIZADO_FUNC','REFERENCIA_PROVEEDOR','REFERENCIA_COMERCIAL','REFERENCIA_BANCARIA','REFERENCIA','REFERENCIA_CHEQUES');

		foreach($ARR_REFERENCIAS AS &$TIPO_REF)
			{
									switch($TIPO_REF)
									{
										case 'REFERENCIA_ACCIONISTA':
											$TABLE_REF   	= 'solicitud_accionista';
											$ID_TABLE		= 'ID_Accionista';
										break; 

										case 'REFERENCIA_FUNCIONARIO':
											$TABLE_REF	    = 'solicitud_funcionarios';
											$ID_TABLE		= 'ID_Funcionario';
										break; 

										case 'REFERENCIA_AUTORIZADO_FUNC':
											$TABLE_REF	    = 'solicitud_funcionarios_autorizados';
											$ID_TABLE		= 'ID_Funcionario_Autorizado';
										break;

										case 'REFERENCIA_PROVEEDOR':
											$TABLE_REF	    = 'solicitud_proveedores';
											$ID_TABLE		= 'ID_Proveedor';
										break;

										case 'REFERENCIA_COMERCIAL':
											$TABLE_REF	    = 'solicitud_referencias_comerciales';
											$ID_TABLE		= 'ID_Referencia_Comercial';
										break;

										case 'REFERENCIA_BANCARIA':
											$TABLE_REF	    = 'solicitud_referencias_bancarias';
											$ID_TABLE		= 'ID_Referencia_Banco';
										break;

										case 'REFERENCIA_CHEQUES':
											$TABLE_REF	    = 'solicitud_referencias_cheques';
											$ID_TABLE		= 'ID_Referencia_Cheque';
										break;
										
										default:
											$TABLE_REF	    = 'solicitud_referencias';
											$ID_TABLE		= 'ID_Referencia';
										break;
									}




								$Sql_campos="
											SELECT 
													COUNT(".$ID_TABLE.") AS CUANTOS		
												FROM ".$TABLE_REF."
											WHERE ID_Solicitud = '".$this->ID_SOLICITUD."' ";
							   $rs_campos=$this->db->Execute($Sql_campos); 


									switch($TIPO_REF)
									{
										case 'REFERENCIA_ACCIONISTA':
											$this->REFERENCIAS_CAPTURADAS_ACCIONISTAS 		=  $rs_campos->fields["CUANTOS"];
										break; 

										case 'REFERENCIA_FUNCIONARIO':
											$this->REFERENCIAS_CAPTURADAS_FUNCIONARIOS 		=  $rs_campos->fields["CUANTOS"];
										break; 

										case 'REFERENCIA_AUTORIZADO_FUNC':
											$this->REFERENCIAS_CAPTURADAS_FUNCIONARIOS_AUT 	=  $rs_campos->fields["CUANTOS"];
										break;

										case 'REFERENCIA_PROVEEDOR':
											$this->REFERENCIAS_CAPTURADAS_PROVEEDORES 		=  $rs_campos->fields["CUANTOS"];
										break;

										case 'REFERENCIA_COMERCIAL':
											$this->REFERENCIAS_CAPTURADAS_COMERCIALES 		=  $rs_campos->fields["CUANTOS"];
										break;

										case 'REFERENCIA_BANCARIA':
											$this->REFERENCIAS_CAPTURADAS_BANCARIAS 		=  $rs_campos->fields["CUANTOS"];
										break;

										case 'REFERENCIA_CHEQUES':
											$this->REFERENCIAS_CAPTURADAS_CHEQUES 			=  $rs_campos->fields["CUANTOS"];
										break;
										
										default:
											$this->REFERENCIAS_CAPTURADAS 					=  $rs_campos->fields["CUANTOS"];
										break;
									}
					
			}//FIN FOR EACH
			
   }

   /*****************VISTA DE SOLICITUDES***********************/
    function get_consulta_soli()
    {
				$Sql_campos="SELECT 
								   cat_tipo_credito_campos.Nombre_tabla             AS NMB_TBL,
								   cat_tipo_credito_campos.Nombre_campo				AS NMB_CMP,
								   cat_tipo_credito_campos.ID_seccion     			AS ID_SECC,
								   cat_tipo_credito_campos.Orden					AS ORDN
							FROM
									cat_tipo_credito_campos
								LEFT JOIN cat_tipo_credito_secciones ON cat_tipo_credito_campos.ID_seccion = cat_tipo_credito_secciones.ID_seccion
									AND cat_tipo_credito_campos.ID_seccion IS NOT NULL
							WHERE cat_tipo_credito_campos.ID_Tipocredito 		= '".$this->ID_Tipocredito."'
								 AND cat_tipo_credito_campos.ID_Tipo_regimen    = '".$this->ID_Tiposolicitud."'
								#AND cat_tipo_credito_campos.Visibilidad 		='Y'
								 AND cat_tipo_credito_campos.Tipo <> 'LABEL'
								 AND (cat_tipo_credito_campos.Tabla_destino		='".$this->TBL_SOLI_DEST."' )
							 ORDER BY ID_SECC,ORDN ";
			   $rs_campos=$this->db->Execute($Sql_campos); 

                $Query="SELECT
                              ";
		 while(! $rs_campos->EOF )
			 {
				  $Query.=$rs_campos->fields["NMB_TBL"]." AS ".$rs_campos->fields["NMB_TBL"]."";
				$rs_campos->MoveNext();
				  $Query.=(! $rs_campos->EOF)?(","):("");
			  }

			  //AGREGAR CAMPOS QUE APARECEN COMO ASOCIADOS ***MEJORAR ESTA PARTE****
			  if($this->ID_Tipocredito < 4)
			  $Query .= ",Hclave AS Hclave,Hclave_conyuge AS Hclave_conyuge,Tipo_tiempo_domicilio AS Tipo_tiempo_domicilio,Tipo_tiempo_trabajo AS Tipo_tiempo_trabajo,Tipo_tiempo_trabajo_anterior AS Tipo_tiempo_trabajo_anterior, Telefono";

			  /****************************************/

				$Query.="
				         FROM
				             ".$this->TBL_SOLI_DEST."
				         WHERE ID_Solicitud = '".$this->ID_SOLICITUD."'    ";
				$rs_solicitud=$this->db->Execute($Query);

			$rs_campos->MoveFirst();
		 while(! $rs_campos->EOF )
			 {
				$Solicitud_campo[$rs_campos->fields["NMB_CMP"]]=$rs_solicitud->fields[$rs_campos->fields["NMB_TBL"]];
				$rs_campos->MoveNext();
			  }

		$this->SOLICITUD_CAMPOS=  $Solicitud_campo;
	}//FIN GET CONSULTA SOLICITUD
	
	function get_consulta_referencia($Campo_ref,$Orden)
	{
		$Sql_ref="SELECT
						".$Campo_ref." AS VALUE
					FROM 
						referencias
					INNER JOIN solicitud_referencias ON referencias.ID_Referencia = solicitud_referencias.ID_Referencia
															AND solicitud_referencias.ID_Solicitud ='".$this->ID_SOLICITUD."'
					WHERE referencias.Orden ='".$Orden."' ";
		$rs_referencia=$this->db->Execute($Sql_ref);

		return $rs_referencia->fields["VALUE"];
	}//FIN GET CONSULTA REFERENCIA PERSONAL

	function get_consulta_referencia_cheques($Campo_ref,$Orden)
	{
		$Sql_ref="SELECT
						".$Campo_ref." AS VALUE
					FROM 
						referencias_cheques
					INNER JOIN solicitud_referencias_cheques ON referencias_cheques.ID_Referencia_Cheque = solicitud_referencias_cheques.ID_Referencia_Cheque
															AND solicitud_referencias_cheques.ID_Solicitud ='".$this->ID_SOLICITUD."'
					WHERE referencias_cheques.Orden ='".$Orden."' ";
		$rs_referencia=$this->db->Execute($Sql_ref);

		return $rs_referencia->fields["VALUE"];
		return $rs_referencia->fields["VALUE"];
	}//FIN GET CONSULTA REFERENCIA CHEQUES
	
	function get_consulta_referencia_bancaria($Campo_ref,$Orden)
	{
		$Sql_ref="SELECT
						".$Campo_ref." AS VALUE
					FROM 
						referencias_bancarias
					INNER JOIN solicitud_referencias_bancarias ON referencias_bancarias.ID_Referencia_Banco = solicitud_referencias_bancarias.ID_Referencia_Banco
															AND solicitud_referencias_bancarias.ID_Solicitud ='".$this->ID_SOLICITUD."'
					WHERE referencias_bancarias.Orden ='".$Orden."' ";
		$rs_referencia=$this->db->Execute($Sql_ref);
			
		return $rs_referencia->fields["VALUE"];
	}//FIN GET CONSULTA REFERENCIA BANCARIA


	function get_consulta_referencia_comerciales($Campo_ref,$Orden)
	{
		$Sql_ref="SELECT
						".$Campo_ref." AS VALUE
					FROM 
						referencias_comerciales
					INNER JOIN solicitud_referencias_comerciales ON referencias_comerciales.ID_Referencia_Comercial = solicitud_referencias_comerciales.ID_Referencia_Comercial
															AND solicitud_referencias_comerciales.ID_Solicitud ='".$this->ID_SOLICITUD."'
					WHERE referencias_comerciales.Orden ='".$Orden."' ";
		$rs_referencia=$this->db->Execute($Sql_ref);
			
		return $rs_referencia->fields["VALUE"];
	}

	function get_consulta_proveedores($Campo_ref,$Orden)
	{
		$Sql_ref="SELECT
						".$Campo_ref." AS VALUE
					FROM 
						proveedores
					INNER JOIN solicitud_proveedores ON proveedores.ID_Proveedor = solicitud_proveedores.ID_Proveedor
															AND solicitud_proveedores.ID_Solicitud ='".$this->ID_SOLICITUD."'
					WHERE proveedores.Orden ='".$Orden."' ";
		$rs_referencia=$this->db->Execute($Sql_ref);
			
		return $rs_referencia->fields["VALUE"];
	}

	function get_consulta_funcionarios($Campo_ref,$Orden)
	{
		$Sql_ref="SELECT
						".$Campo_ref." AS VALUE
					FROM 
						funcionarios
					INNER JOIN solicitud_funcionarios ON funcionarios.ID_Funcionario = solicitud_funcionarios.ID_Funcionario
															AND solicitud_funcionarios.ID_Solicitud ='".$this->ID_SOLICITUD."'
					WHERE funcionarios.Orden ='".$Orden."' ";
		$rs_referencia=$this->db->Execute($Sql_ref);
			
		return $rs_referencia->fields["VALUE"];
	}

	function get_consulta_funcionarios_autorizados($Campo_ref,$Orden)
	{
		$Sql_ref="SELECT
						".$Campo_ref." AS VALUE
					FROM 
						funcionarios_autorizados
					INNER JOIN solicitud_funcionarios_autorizados ON funcionarios_autorizados.ID_Funcionario_Autorizado = solicitud_funcionarios_autorizados.ID_Funcionario_Autorizado
															      AND solicitud_funcionarios_autorizados.ID_Solicitud ='".$this->ID_SOLICITUD."'
					WHERE funcionarios_autorizados.Orden ='".$Orden."' ";
		$rs_referencia=$this->db->Execute($Sql_ref);
			
		return $rs_referencia->fields["VALUE"];
	}

	function get_consulta_accionistas($Campo_ref,$Orden)
	{
		$Sql_ref="SELECT
						".$Campo_ref." AS VALUE
					FROM 
						accionistas
					INNER JOIN solicitud_accionista				 ON accionistas.ID_Accionista = solicitud_accionista.ID_Accionista
															      AND solicitud_accionista.ID_Solicitud ='".$this->ID_SOLICITUD."'
					WHERE accionistas.Orden ='".$Orden."' ";
		$rs_referencia=$this->db->Execute($Sql_ref);
			
		return $rs_referencia->fields["VALUE"];

	}

	function get_campo_especial($NMB_CMP_ESP)
	{
				$Sql_campos_esp="SELECT 
								   cat_tipo_credito_campos.ID_campo					AS ID_CMP,
								   cat_tipo_credito_campos.Tipo_dato				AS TP_CMP
							FROM
									cat_tipo_credito_campos
							WHERE cat_tipo_credito_campos.ID_Tipocredito 		= '".$this->ID_Tipocredito."'
								 AND cat_tipo_credito_campos.ID_Tipo_regimen    = '".$this->ID_Tiposolicitud."'
								 AND cat_tipo_credito_campos.Tabla_destino		= 'solicitud_campos_especiales'
								 AND cat_tipo_credito_campos.Nombre_tabla		= '".$NMB_CMP_ESP."' ";
			   $rs_campos_esp=$this->db->Execute($Sql_campos_esp);

		$RESULT_CMP_ESP = Array("ID_CMP"=>$rs_campos_esp->fields["ID_CMP"],"TP_CMP"=>$rs_campos_esp->fields["TP_CMP"]);

	 return $RESULT_CMP_ESP;
	 
	}
	
	function get_consulta_campos_especiales($Campo_especial)
	{
		$CMP_ESP_ARR = $this->get_campo_especial($Campo_especial);
		
		$NMB_CMP_ESP =( $CMP_ESP_ARR['TP_CMP'] == 'INT')?('Valor_int'):('Valor_varchar_large');
		$NMB_CMP_ESP =( $CMP_ESP_ARR['TP_CMP'] == 'FLOAT')?('Valor_float'):($NMB_CMP_ESP);
		$NMB_CMP_ESP =( $CMP_ESP_ARR['TP_CMP'] == 'VARCHAR_SHORT')?('Valor_varchar_short'):($NMB_CMP_ESP);
		$NMB_CMP_ESP =( $CMP_ESP_ARR['TP_CMP'] == 'TEXT')?('Valor_text'):($NMB_CMP_ESP);
		$NMB_CMP_ESP =( $CMP_ESP_ARR['TP_CMP'] == 'DATE')?('Valor_date'):($NMB_CMP_ESP);
				
		$Sql_cons="SELECT
						".$NMB_CMP_ESP." AS VALUE
					FROM 
						solicitud_campos_especiales
					WHERE ID_campo ='".$CMP_ESP_ARR['ID_CMP']."'
						AND ID_Solicitud ='".$this->ID_SOLICITUD."'
						AND ID_Tipo_regimen = '".$this->ID_Tiposolicitud."' ";
		$rs_cons=$this->db->Execute($Sql_cons);

		return $rs_cons->fields["VALUE"];
	}//FIN GET CONSULTA CAMPOS ESPECIALES

	function get_consulta_rfc_hclave($NMB_CMP)
	{
		$TIPO_RFC = ($this->TIPO_REGIMEN == 'PM' )?("RFC AS RFC_CTE"):("CONCAT(RFC,'-',Hclave)	AS RFC_CTE");
		$TIPO_RFC = ($NMB_CMP == 'RFC_conyuge' )?("CONCAT(RFC_conyuge,'-',Hclave_conyuge)	AS RFC_CTE"):($TIPO_RFC);
		$TIPO_RFC = ($this->TIPO_REGIMEN == 'PFAE' )?(" CONCAT(RFC_pfae,'-',Hclave_pfae)	AS RFC_CTE"):($TIPO_RFC);
		


		
		$SQL_SEARCH="SELECT
							".$TIPO_RFC."
						FROM 
							".$this->TBL_SOLI_DEST."
						WHERE
								ID_Solicitud = '".$this->ID_SOLICITUD."'
						AND ID_Tipo_regimen 	 = '".$this->ID_Tiposolicitud."' ";
		$rs_search=$this->db->Execute($SQL_SEARCH);

	return $rs_search->fields['RFC_CTE'];
	}
	
	function get_consulta_telefono($NMB_CMP)
	{
		$tipoTelefono = '';
		
		switch($NMB_CMP){
			case 'lada_casa':
			   $tipoTelefono = "CONCAT(lada_casa,'-',Telefono)	AS Telefono";
			   break;
			case 'lada_celular':
			   $tipoTelefono = "CONCAT(lada_celular,'-',Num_celular_parentesco)	AS Telefono";
			   break;
			case 'lada_telefono_laboral':
			   $tipoTelefono = "CONCAT(lada_telefono_laboral,'-',Telefono_empresa)	AS Telefono";
			   break;
		}
	
		
		$SQL_SEARCH="SELECT
							".$tipoTelefono."
						FROM 
							".$this->TBL_SOLI_DEST."
						WHERE
								ID_Solicitud = '".$this->ID_SOLICITUD."'
						AND ID_Tipo_regimen 	 = '".$this->ID_Tiposolicitud."' ";
		$rs_search=$this->db->Execute($SQL_SEARCH);

	    return $rs_search->fields['Telefono'];
	}
	
	
	
	function get_consulta_tipo_domicilio($NMB_CMP)
	{
	
		
	    $tipoDomicilio = "CONCAT(Tiempo_domicilio,' ',Tipo_tiempo_domicilio)	AS domicilio";
		
		$SQL_SEARCH="SELECT
							".$tipoDomicilio."
						FROM 
							".$this->TBL_SOLI_DEST."
						WHERE
								ID_Solicitud = '".$this->ID_SOLICITUD."'
						AND ID_Tipo_regimen 	 = '".$this->ID_Tiposolicitud."' ";
		$rs_search=$this->db->Execute($SQL_SEARCH);

	    return $rs_search->fields['domicilio'];
	}
	
    function get_vista_input_tag($ETQ,$TIPO_CMP,$NMB_CMP,$STYLE,$SQL,$CLASS,$TABLA_DEST)
    {

		//ALL CMP
		if(($TABLA_DEST == $this->TBL_SOLI_DEST) && ($TIPO_CMP!='LABEL' && $TABLA_DEST != 'solicitud_campos_especiales') )
			$Campo_value = $this->SOLICITUD_CAMPOS[$NMB_CMP];

		if($TIPO_CMP == 'SELECT_ARRAY' && ($TABLA_DEST == 'solicitud' || $TABLA_DEST == 'solicitud_pmoral') )
			$Campo_value = $this->SOLICITUD_CAMPOS[$NMB_CMP];


		if($TIPO_CMP == 'SELECT' && !empty($SQL) && $TABLA_DEST != 'solicitud_campos_especiales') 
		{
			$Campo_value 	= $this->SOLICITUD_CAMPOS[$NMB_CMP];
			$Sql_value 		=str_replace("[VALOR]",$Campo_value,$SQL);
			$rs_val			=$this->db->Execute($Sql_value);
			$Campo_value	=$rs_val->fields["DESCP"];
		}

		//REFERENCIAS
		if(($TABLA_DEST == 'referencias') && ($TIPO_CMP!='LABEL'))
		{
			$Pos		=strrpos($NMB_CMP,'_referencia');
			$CMP_REF	=substr($NMB_CMP,0,$Pos);

			$Campo_value = $this->get_consulta_referencia($CMP_REF,$this->INDEX_REFERENCIAS);
		}
		elseif(($TABLA_DEST == 'referencias') && ($TIPO_CMP =='LABEL'))
		{
			$Pos		 =  strrpos($ETQ,'# 1');
			$ETQ		 =  substr($ETQ,0,$Pos + 2);
			$ETQ		.= $this->INDEX_REFERENCIAS;
		}

		//REFERENCIAS CHEQUES
		if(($TABLA_DEST == 'referencias_cheques') && ($TIPO_CMP!='LABEL'))
		{
			$Pos		=strrpos($NMB_CMP,'_referencias_cheques');
			$CMP_REF	=substr($NMB_CMP,0,$Pos);

			$Campo_value = $this->get_consulta_referencia_cheques($CMP_REF,$this->INDEX_REFERENCIAS_CHEQUES);
		}
		elseif(($TABLA_DEST == 'referencias_cheques') && ($TIPO_CMP =='LABEL'))
		{
			$Pos		 =  strrpos($ETQ,'# 1');
			$ETQ		 =  substr($ETQ,0,$Pos + 2);
			$ETQ		.= $this->INDEX_REFERENCIAS_CHEQUES;
		}
		
		//REF ACCIONISTAS
		if(($TABLA_DEST == 'accionistas') && ($TIPO_CMP!='LABEL'))
		{
			$Pos		=strrpos($NMB_CMP,'_accionista');
			$CMP_REF	=substr($NMB_CMP,0,$Pos);

			//$Pos		 =strrpos($NMB_CMP,'_');
			//$ORDEN  	 =substr($NMB_CMP,$Pos+1,strlen($NMB_CMP));
			$Campo_value = $this->get_consulta_accionistas($CMP_REF,$this->INDEX_REFERENCIAS_ACCIONISTAS);
		}
		elseif(($TABLA_DEST == 'accionistas') && ($TIPO_CMP =='LABEL'))
		{
			$Pos		 =  strrpos($ETQ,'# 1');
			$ETQ		 =  substr($ETQ,0,$Pos + 2);
			$ETQ		.= $this->INDEX_REFERENCIAS_ACCIONISTAS;
		}

		//REF FUNCIONARIOS
		if(($TABLA_DEST == 'funcionarios') && ($TIPO_CMP!='LABEL'))
		{
			$Pos		=strrpos($NMB_CMP,'_funcionario');
			$CMP_REF	=substr($NMB_CMP,0,$Pos);

			//$Pos		 =strrpos($NMB_CMP,'_');
			//$ORDEN  	 =substr($NMB_CMP,$Pos+1,strlen($NMB_CMP));
			$Campo_value = $this->get_consulta_funcionarios($CMP_REF,$this->INDEX_REFERENCIAS_FUNCIONARIOS);
		}
		elseif(($TABLA_DEST == 'funcionarios') && ($TIPO_CMP =='LABEL'))
		{
			$Pos		 =  strrpos($ETQ,'# 1');
			$ETQ		 =  substr($ETQ,0,$Pos + 2);
			$ETQ		.= $this->INDEX_REFERENCIAS_FUNCIONARIOS;
		}


		//REF FUNCIONARIOS AUT
		if(($TABLA_DEST == 'funcionarios_autorizados') && ($TIPO_CMP!='LABEL'))
		{
			$Pos		=strrpos($NMB_CMP,'_funcionario_autorizado');
			$CMP_REF	=substr($NMB_CMP,0,$Pos);

			//$Pos		 =strrpos($NMB_CMP,'_');
			//$ORDEN  	 =substr($NMB_CMP,$Pos+1,strlen($NMB_CMP));
			$Campo_value = $this->get_consulta_funcionarios_autorizados($CMP_REF,$this->INDEX_REFERENCIAS_FUNCIONARIOS_AUT);
		}
		elseif(($TABLA_DEST == 'funcionarios_autorizados') && ($TIPO_CMP =='LABEL'))
		{
			$Pos		 =  strrpos($ETQ,'# 1');
			$ETQ		 =  substr($ETQ,0,$Pos + 2);
			$ETQ		.= $this->INDEX_REFERENCIAS_FUNCIONARIOS_AUT;
		}

		//REF PROVEEDORES
		if(($TABLA_DEST == 'proveedores') && ($TIPO_CMP!='LABEL'))
		{
			$Pos		=strrpos($NMB_CMP,'_proveedores');
			$CMP_REF	=substr($NMB_CMP,0,$Pos);

			//$Pos		 =strrpos($NMB_CMP,'_');
			//$ORDEN  	 =substr($NMB_CMP,$Pos+1,strlen($NMB_CMP));
			$Campo_value = $this->get_consulta_proveedores($CMP_REF,$this->INDEX_REFERENCIAS_PROVEEDORES);
		}
		elseif(($TABLA_DEST == 'proveedores') && ($TIPO_CMP =='LABEL'))
		{
			$Pos		 =  strrpos($ETQ,'# 1');
			$ETQ		 =  substr($ETQ,0,$Pos + 2);
			$ETQ		.= $this->INDEX_REFERENCIAS_PROVEEDORES;
		}
		

		//REF COMERCIALES
		if(($TABLA_DEST == 'referencias_comerciales') && ($TIPO_CMP!='LABEL'))
		{
			$Pos		=strrpos($NMB_CMP,'_referencias_comerciales');
			$CMP_REF	=substr($NMB_CMP,0,$Pos);

			//$Pos		 =strrpos($NMB_CMP,'_');
			//$ORDEN  	 =substr($NMB_CMP,$Pos+1,strlen($NMB_CMP));
			$Campo_value = $this->get_consulta_referencia_comerciales($CMP_REF,$this->INDEX_REFERENCIAS_COMERCIALES);
		}
		elseif(($TABLA_DEST == 'referencias_comerciales') && ($TIPO_CMP =='LABEL'))
		{
			$Pos		 =  strrpos($ETQ,'# 1');
			$ETQ		 =  substr($ETQ,0,$Pos + 2);
			$ETQ		.= $this->INDEX_REFERENCIAS_COMERCIALES;
		}


		//REF BANCARIAS
		if(($TABLA_DEST == 'referencias_bancarias') && ($TIPO_CMP!='LABEL' && $TIPO_CMP !='SELECT') )
		{
			$Pos		=strrpos($NMB_CMP,'_referencias_bancarias');
			$CMP_REF	=substr($NMB_CMP,0,$Pos);

			//$Pos		 =strrpos($NMB_CMP,'_');
			//$ORDEN  	 =substr($NMB_CMP,$Pos+1,strlen($NMB_CMP));
			$Campo_value = $this->get_consulta_referencia_bancaria($CMP_REF,$this->INDEX_REFERENCIAS_BANCARIAS);
		}
		elseif(($TABLA_DEST == 'referencias_bancarias') && ($TIPO_CMP =='LABEL'))
		{
			$Pos		 =  strrpos($ETQ,'# 1');
			$ETQ		 =  substr($ETQ,0,$Pos + 2);
			$ETQ		.= $this->INDEX_REFERENCIAS_BANCARIAS;
		}
		elseif(($TABLA_DEST == 'referencias_bancarias') && ($TIPO_CMP =='SELECT'))
		{

			$Pos		=strrpos($NMB_CMP,'_referencia');
			$CMP_REF	=substr($NMB_CMP,0,$Pos);
			
			$Campo_value 	= $NMB_CMP;
			$Sql_value 		=str_replace("[VALOR]",$Pos,$SQL);
			$rs_val			=$this->db->Execute($Sql_value);
			$Campo_value	=$rs_val->fields["DESCP"];
		}
		//FIN REFERENCIAS

		//CAMPO AGREGADOS ESPECIALES TABLA solicitud_campos_especiales
		if($TABLA_DEST =='solicitud_campos_especiales')
		{
			$Campo_value = $this->get_consulta_campos_especiales($NMB_CMP);
			if($TIPO_CMP == 'SELECT' && !empty($SQL) ) 
			{
				$Sql_value 		=str_replace("[VALOR]",$Campo_value,$SQL);
				$rs_val			=$this->db->Execute($Sql_value);
				$Campo_value	=$rs_val->fields["DESCP"];
			}
		}
		//FIN CMP ESPECIALES


		

        if($CLASS =='datepicker')
			$Campo_value=ffecha($Campo_value);

		//VISTA

		//RFC VALIDATION
		if($NMB_CMP == 'RFC' || $NMB_CMP == 'RFC_pfae' || $NMB_CMP == 'RFC_conyuge' )
			$Campo_value = $this->get_consulta_rfc_hclave($NMB_CMP);
		
		if($NMB_CMP == 'lada_casa'  || $NMB_CMP == 'lada_celular' || $NMB_CMP == 'lada_telefono_laboral' )
			$Campo_value = $this->get_consulta_telefono($NMB_CMP);
		
		if($NMB_CMP == 'Tiempo_domicilio'  )
		   $Campo_value = $this->get_consulta_tipo_domicilio($NMB_CMP);
		
		if($TIPO_CMP=='TEXT')
		  $this->Input_Tag="    <TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:20%;'>        ".$ETQ." : </TH>
				                   <TD STYLE='TEXT-ALIGN:LEFT; WIDTH:30%;'>
											<LABEL ID='".$NMB_CMP."' CLASS='".$CLASS."' >".$Campo_value."</LABEL>
				                   </TD>";

		if($TIPO_CMP=='TEXTAREA')
		  $this->Input_Tag="    <TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:20%;'>        ".$ETQ." : </TH>
				                   <TD STYLE='TEXT-ALIGN:LEFT; WIDTH:30%;'>
					                      <TEXTAREA  STYLE='".$STYLE."'  READONLY  >
											".$Campo_value."
					                      </TEXTAREA>
				                   </TD>";

		if($TIPO_CMP=='SELECT' || $TIPO_CMP=='SELECT_ARRAY')
		{
		  $HTML_ASOCC=($NMB_CMP=='Plazo')?('<SPAN ID="Label_Plazo"></SPAN>'):('');
		 $this->Input_Tag="    <TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:20%;'>        ".$ETQ." : </TH>
				                   <TD STYLE='TEXT-ALIGN:LEFT; WIDTH:30%;'>
					                     ".$Campo_value."&nbsp; ".$HTML_ASOCC."
				                   </TD>";
		}

		if($TIPO_CMP=='CATALOG')
		  $this->Input_Tag="    <TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:20%;'>        ".$ETQ." : </TH>
				                   <TD STYLE='TEXT-ALIGN:LEFT; WIDTH:30%;'>
					                   ".$Campo_value."  
				                   </TD>";

		if($TIPO_CMP=='LABEL')
		  $this->Input_Tag="    <TD STYLE='".$STYLE."' COLSPAN='4'>        ".$ETQ."   </TD>";
	}//FIN get_vista_input_tag
	

	//ID_SOLICITUD ES PARÁMETRO Y VIENE DE vista_solicitudes_credito.php
	function get_vista_solicitud($ID_SOLICITUD)
	{
		
		
			   $this->ID_SOLICITUD = $ID_SOLICITUD;
			   //$CHECK_SOLI = $this->check_nomina_especial();

				//if(!empty($CHECK_SOLI) && ($CHECK_SOLI != 'FALSE') )
				if($this->ID_Tipocredito == 3 )
					$this->set_validation_nomina($CHECK_SOLI);
			   
			   $this->get_consulta_soli();
			   $this->get_encabezado_soli('VISTA');//ENCABEZADO DE LA SOLICITUD FECHA,SUCURSAL, CAPTURISTA ETC..
										
				$Sql_campos="SELECT 
								   cat_tipo_credito_campos.ID_campo                 AS ID_CMP,
								   cat_tipo_credito_campos.ID_seccion     			AS ID_SECC,
								   cat_tipo_credito_secciones.Nombre				AS NMB_SECC,
								   cat_tipo_credito_campos.Etiqueta					AS ETQ,
								   cat_tipo_credito_campos.Visibilidad				AS VSBL,
								   cat_tipo_credito_campos.Orden					AS ORDN,
								   cat_tipo_credito_campos.Nombre_campo				AS NMB_CMP,
								   cat_tipo_credito_campos.Tipo						AS TIPO_CMP,
								   cat_tipo_credito_campos.Style					AS STYLE,
								   cat_tipo_credito_campos.Sql_consulta				AS SQL_,
								   cat_tipo_credito_campos.Tabla_destino			AS TABLA_DEST,
								   cat_tipo_credito_campos.Class					AS CLASS,
								   cat_tipo_credito_secciones.Orden					AS ORD_SECC,
								   cat_tipo_credito_secciones.Datos_dinamicos		AS CMP_DINAMICOS,
								   cat_tipo_credito_secciones.Tipo_dinamico			AS TMP_DNMCO
							FROM
									cat_tipo_credito_campos
								LEFT JOIN cat_tipo_credito_secciones ON cat_tipo_credito_campos.ID_seccion = cat_tipo_credito_secciones.ID_seccion
									AND cat_tipo_credito_campos.ID_seccion IS NOT NULL
							WHERE cat_tipo_credito_campos.ID_Tipocredito 		= '".$this->ID_Tipocredito."'
								AND cat_tipo_credito_campos.ID_Tipo_regimen     = '".$this->ID_Tiposolicitud."'
								AND cat_tipo_credito_campos.Visibilidad ='Y'
							 ORDER BY ORD_SECC,ORDN ";
			   $rs_campos=$this->db->Execute($Sql_campos);       

							$Comp_secc		= "";
							$Row_count		= 0;
							$Refer_count    = 0;
							$this->get_campos_referencia();
							$this->get_referencias_capturadas();
							
							While(!$rs_campos->EOF)
							{

										 if($Comp_secc != $rs_campos->fields["ID_SECC"])
										{
													  $this->Body_soli($rs_campos->fields["ID_SECC"],$rs_campos->fields["NMB_SECC"]);
													  $this->HTML.=($Comp_secc=="")?(""):("</TABLE><BR /></DIV>");//FIN DIV DEL TAB O ACORDIÓN SECCIÓN
													  $this->HTML.=$this->Design_Tag; //TAG ACORDION Ó TABS 
													  $Comp_secc=$rs_campos->fields["ID_SECC"];
													   $this->HTML.="<BR />
																	 <TABLE  CELLPADDING='3' CELLSPACING='2' BORDER='0px' WIDTH='100%' >";
										}//FIN REFEENCIAS


										
								 if($this->COLUMNS == '2')//HA DOBLE COLUMNA
								 {
															if($rs_campos->fields["TIPO_CMP"] != 'LABEL')
															{


																	  $this->get_vista_input_tag($rs_campos->fields["ETQ"],$rs_campos->fields["TIPO_CMP"],$rs_campos->fields["NMB_CMP"],$rs_campos->fields["STYLE"],$rs_campos->fields["SQL_"],$rs_campos->fields["CLASS"],$rs_campos->fields["TABLA_DEST"]);
																	  $this->HTML.="<TR STYLE='BACKGROUND-COLOR:#e7eef6;'>";
																	  $this->HTML.=$this->Input_Tag;

																	//HA DOBLE COLUMNA
																	$rs_campos->MoveNext();
																	$Row_count++;
																	$Bandera_secc=($Comp_secc != $rs_campos->fields["ID_SECC"])?("FALSE"):("TRUE");
																	
																	 if( (!$rs_campos->EOF) && ($Bandera_secc=='TRUE') && ($rs_campos->fields["TIPO_CMP"] != 'LABEL') )
																	 {
																	  $this->get_vista_input_tag($rs_campos->fields["ETQ"],$rs_campos->fields["TIPO_CMP"],$rs_campos->fields["NMB_CMP"],$rs_campos->fields["STYLE"],$rs_campos->fields["SQL_"],$rs_campos->fields["CLASS"],$rs_campos->fields["TABLA_DEST"]);
																	  
																	  $this->HTML.=$this->Input_Tag ."</TR>";
																	 }
																	 else
																	 {

																			if(($rs_campos->fields["TIPO_CMP"] == 'LABEL') && ($Bandera_secc=='TRUE'))
																			{
																				$this->HTML.=" 
																							   <TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:20%;'>       &nbsp; </TH>
																							   <TD STYLE='TEXT-ALIGN:LEFT;'>                   &nbsp; </TD>
																							 </TR>";
																							 
																				$this->HTML.="<TR STYLE='BACKGROUND-COLOR:#f2f5f7;'>";
																				$this->get_vista_input_tag($rs_campos->fields["ETQ"],$rs_campos->fields["TIPO_CMP"],$rs_campos->fields["NMB_CMP"],$rs_campos->fields["STYLE"],$rs_campos->fields["SQL_"],$rs_campos->fields["CLASS"],$rs_campos->fields["TABLA_DEST"]);
																				$this->HTML.=$this->Input_Tag ."</TR>";
																			}
																			else
																				 $this->HTML.=" 
																							   <TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:20%;'>       &nbsp; </TH>
																							   <TD STYLE='TEXT-ALIGN:LEFT;'>                   &nbsp; </TD>
																							 </TR>";
																	}

															}
															else
															{
																	$this->HTML.="<TR STYLE='BACKGROUND-COLOR:#f2f5f7;'>";
																	 $this->get_vista_input_tag($rs_campos->fields["ETQ"],$rs_campos->fields["TIPO_CMP"],$rs_campos->fields["NMB_CMP"],$rs_campos->fields["STYLE"],$rs_campos->fields["SQL_"],$rs_campos->fields["CLASS"],$rs_campos->fields["TABLA_DEST"]);
																	$this->HTML.=$this->Input_Tag ."</TR>";

																	$Bandera_secc="FALSE";
																	$Row_count++;

															}

															 if($Bandera_secc=='FALSE')
																$rs_campos->Move(($Row_count-1));
															 else
															   $Row_count++;
									}

								 if($this->COLUMNS == '1')//HA UNA COLUMNA
								 {
												
													  $this->get_vista_input_tag($rs_campos->fields["ETQ"],$rs_campos->fields["TIPO_CMP"],$rs_campos->fields["NMB_CMP"],$rs_campos->fields["STYLE"],$rs_campos->fields["SQL_"],$rs_campos->fields["CLASS"],$rs_campos->fields["TABLA_DEST"]);
													  $this->HTML.="<TR STYLE='BACKGROUND-COLOR:#e7eef6;'>";
													  $this->HTML.=$this->Input_Tag;

															 if($Bandera_secc=='FALSE')
																$rs_campos->Move(($Row_count-1));
															 else
															   $Row_count++;
								  }


													$CMP_DINAMICOS		=	$rs_campos->fields["CMP_DINAMICOS"];
													$TMP_DNMCO			=	$rs_campos->fields["TMP_DNMCO"];
							$rs_campos->MoveNext();


							//VALIDAR REFERENCIAS DINÁMICAS
							if($Comp_secc != $rs_campos->fields["ID_SECC"] &&  $CMP_DINAMICOS	== 'Y')	
								{
											     if( ($TMP_DNMCO == 'REFERENCIA_ACCIONISTA')    )
											     {
														if($this->INDEX_REFERENCIAS_ACCIONISTAS < $this->REFERENCIAS_CAPTURADAS_ACCIONISTAS)
														{
																$Row_count		= $Row_count - $this->CAMPOS_REFERENCIAS_ACCIONISTAS;
																$rs_campos->Move(($Row_count));
														}
														$this->INDEX_REFERENCIAS_ACCIONISTAS ++; 


												 }
											 else if( ($TMP_DNMCO == 'REFERENCIA_FUNCIONARIO')   )
											     {

														if($this->INDEX_REFERENCIAS_FUNCIONARIOS < $this->REFERENCIAS_CAPTURADAS_FUNCIONARIOS)
														{
																$Row_count		= $Row_count - $this->CAMPOS_REFERENCIAS_FUNCIONARIOS;
																$rs_campos->Move(($Row_count));
														}
														$this->INDEX_REFERENCIAS_FUNCIONARIOS ++; 

												 }
											 else if( ($TMP_DNMCO == 'REFERENCIA_AUTORIZADO_FUNC'  )  )
											     {
														if($this->INDEX_REFERENCIAS_FUNCIONARIOS_AUT < $this->REFERENCIAS_CAPTURADAS_FUNCIONARIOS_AUT)
														{
																$Row_count		= $Row_count - $this->CAMPOS_REFERENCIAS_FUNCIONARIOS_AUT;
																$rs_campos->Move(($Row_count));
														}
														$this->INDEX_REFERENCIAS_FUNCIONARIOS_AUT ++; 


												 }
											 else if( ($TMP_DNMCO == 'REFERENCIA_PROVEEDOR'  )   )
											     {
														if($this->INDEX_REFERENCIAS_PROVEEDORES < $this->REFERENCIAS_CAPTURADAS_PROVEEDORES)
														{
																$Row_count		= $Row_count - $this->CAMPOS_REFERENCIAS_PROVEEDORES;
																$rs_campos->Move(($Row_count));
														}
														$this->INDEX_REFERENCIAS_PROVEEDORES ++; 


												 }
											 else if( ($TMP_DNMCO == 'REFERENCIA_BANCARIA'  )   )
											     {

														if($this->INDEX_REFERENCIAS_BANCARIAS < $this->REFERENCIAS_CAPTURADAS_BANCARIAS)
														{
																$Row_count		= $Row_count - $this->CAMPOS_REFERENCIAS_BANCARIAS;
																$rs_campos->Move(($Row_count));
														}
														$this->INDEX_REFERENCIAS_BANCARIAS ++; 

												 }
											 else if( ($TMP_DNMCO == 'REFERENCIA_COMERCIAL'  ) )
											     {
													 
														if($this->INDEX_REFERENCIAS_COMERCIALES < $this->REFERENCIAS_CAPTURADAS_COMERCIALES)
														{
																$Row_count		= $Row_count - $this->CAMPOS_REFERENCIAS_COMERCIALES;
																$rs_campos->Move(($Row_count));
														}
														$this->INDEX_REFERENCIAS_COMERCIALES ++; 


												 }
											 else if( ($TMP_DNMCO == 'REFERENCIA_CHEQUES'  ) )
											     {
													 
														if($this->INDEX_REFERENCIAS_CHEQUES < $this->REFERENCIAS_CAPTURADAS_CHEQUES)
														{
																$Row_count		= $Row_count - $this->CAMPOS_REFERENCIAS_CHEQUES;
																$rs_campos->Move(($Row_count));
														}
														else
														{
																//AGREGAR REFERENCIAS
																$this->HTML.=$this->get_html_new_referencias($TMP_DNMCO,'VISTA');
														}														
														$this->INDEX_REFERENCIAS_CHEQUES ++; 


												 }
											 else if( ($TMP_DNMCO == 'REFERENCIA'   )  )
											     {
													 
														if (($this->INDEX_REFERENCIAS < $this->REFERENCIAS_CAPTURADAS) )
														{
																$Row_count		= $Row_count - $this->CAMPOS_REFERENCIAS;
																$rs_campos->Move(($Row_count));
																														
														}
														$this->INDEX_REFERENCIAS ++; 
														
														

												 }
								}//FIN REFERENCIAS DINÁMICAS
								
								 



					}//FIN WHILE

				 $this->HTML.="</DIV>
				                  </DIV>";//FIN DIV ACORDIÓN O TABS CONTENEDOR //FIN DIV PRINCIPAL
				 //$this->HTML.="<DIV ID='dialog-catalog' TITLE='AVISO S2CREDIT.'  STYLE='DISPLAY:NONE;'>
				 //			   </DIV> ";
				 //$this->HTML.="<DIV ID='dialog-searchcp' TITLE='BUSCAR CÓDIGO POSTAL.'  STYLE='DISPLAY:NONE;'>
				//		   </DIV> ";
							   
				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='TIPO_CREDITO' 			    VALUE='".$this->ID_Tipocredito."' />";
				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='TIPO_SOLICITUD' 		    VALUE='".$this->ID_Tiposolicitud."' />";
				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='TIPO_REGIMEN' 				VALUE='". $this->TIPO_REGIMEN."' />";
				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='NOMINA_ESPECIAL' 			VALUE='".$this->VALIDATION_NOMINA."' />";
				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='NOMINA_RFC' 				VALUE='".$this->NOMINA_RFC."' />";
				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='INGRESOS_NOM_ESP'			VALUE='".$this->NOMINA_TOTAL."' />";
				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='EGRESOS_NOM_ESP' 			VALUE='".$this->NOMINA_LIQUID."' />";



	 			$this->get_datos_credito_view();

				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='ID_empresa' 				VALUE='".$this->VIEW_ID_EMP."' />";
				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='ID_Producto' 				VALUE='".$this->VIEW_ID_PROD."' />";
				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='Plazo' 						VALUE='".$this->VIEW_PLAZO."' />";
				 
				 $this->HTML.="</FORM>";//FIN FORMULARIO
	} //FIN get_vista_solicitud

	


	function Render_preview_soli()
	{

			   $this->get_encabezado_soli('PREVIEW_SOLI');//ENCABEZADO DE LA SOLICITUD FECHA,SUCURSAL, CAPTURISTA ETC..

				$Sql_campos="SELECT 
								   cat_tipo_credito_campos.ID_campo                 AS ID_CMP,
								   cat_tipo_credito_campos.ID_seccion     			AS ID_SECC,
								   cat_tipo_credito_secciones.Orden					AS ORD_SECC,
								   cat_tipo_credito_secciones.Nombre				AS NMB_SECC,
								   cat_tipo_credito_campos.Etiqueta					AS ETQ,
								   cat_tipo_credito_campos.Obligatorio				AS OBLG,
								   cat_tipo_credito_campos.Obligatorio_sistema		AS OBLG_SIST,
								   cat_tipo_credito_campos.Visibilidad				AS VSBL,
								   cat_tipo_credito_campos.Orden					AS ORDN,
								   cat_tipo_credito_campos.Nombre_campo				AS NMB_CMP,
								   cat_tipo_credito_campos.Tipo						AS TIPO_CMP,
								   cat_tipo_credito_campos.Style					AS STYLE,
								   cat_tipo_credito_campos.Evento					AS EVNT,
								   cat_tipo_credito_campos.Class					AS CLASS,
								   cat_tipo_credito_campos.Sql						AS SQL_,
								   cat_tipo_credito_campos.Readonly					AS READON,
								   cat_tipo_credito_campos.Html						AS HTML,
								   cat_tipo_credito_campos.List_cmp_asoc			AS CMP_ASOC,
								   cat_tipo_credito_campos.Tabla_destino			AS TABLA_DEST,
								   cat_tipo_credito_secciones.Tipo_dinamico			AS TMP_DNMCO,
								   cat_tipo_credito_secciones.Datos_dinamicos		AS CMP_DINAMICOS 
							FROM
									cat_tipo_credito_campos
								LEFT JOIN cat_tipo_credito_secciones ON cat_tipo_credito_campos.ID_seccion = cat_tipo_credito_secciones.ID_seccion
									AND cat_tipo_credito_campos.ID_seccion IS NOT NULL
							WHERE   cat_tipo_credito_campos.ID_Tipocredito 		=	'".$this->ID_Tipocredito."'
								AND cat_tipo_credito_campos.ID_Tipo_regimen 	=	'".$this->ID_Tiposolicitud."'
								AND cat_tipo_credito_campos.Visibilidad ='Y'
							 ORDER BY ORD_SECC,ORDN ";
			   $rs_campos=$this->db->Execute($Sql_campos);       

							$Comp_secc="";
							$Row_count=0;

							
							While(!$rs_campos->EOF)
							{
									  if($Comp_secc != $rs_campos->fields["ID_SECC"])
										{

												  $this->Body_soli($rs_campos->fields["ID_SECC"],$rs_campos->fields["NMB_SECC"]);
												  $this->HTML.=($Comp_secc=="")?(""):("</TABLE><BR /></DIV>");//FIN DIV DEL TAB O ACORDIÓN SECCIÓN
												  $this->HTML.=$this->Design_Tag; //TAG ACORDION Ó TABS 

												if($rs_campos->fields["CMP_DINAMICOS"] == 'N')
													{$ID_TABLE="";$CLASS_REFER=""; $IMG_REFER="";}
												else
													{
														$ARR_ID_REFERES = $this->get_labels_referencias($rs_campos->fields["TMP_DNMCO"]);
														$ID_TABLE       = $ARR_ID_REFERES["ID_TABLE"];$CLASS_REFER=$ARR_ID_REFERES["CLASS_REFER"]; $IMG_REFER=$ARR_ID_REFERES["IMG_REFER"];
													}
													
												  $Comp_secc=$rs_campos->fields["ID_SECC"];
												   $this->HTML.="<BR />
																 <TABLE  CELLPADDING='3' CELLSPACING='2' BORDER='0px' WIDTH='100%' CLASS='".$CLASS_REFER."' ID='".$ID_TABLE."'>";
										}

							 if($this->COLUMNS == '2')//HA DOBLE COLUMNA
							 {

											if( ($rs_campos->fields["TIPO_CMP"] != 'LABEL') && ($rs_campos->fields["TIPO_CMP"] != 'BUTTON') )
											{
													  $Title     =($rs_campos->fields["OBLG"]=='Y')?("TITLE='CAMPO REQUERIDO'"):("");
													  $Asterisk  =($rs_campos->fields["OBLG"]=='Y')?("&nbsp;<IMG ID='IMG_".$rs_campos->fields["NMB_CMP"]."' CLASS='".$IMG_REFER."' BORDER=0 SRC='".$this->IMG."asterisk.png'  ALT='editando'  STYLE='height:13px; width:13px; align:top;' />"):("");
													  $this->get_input_tag($rs_campos->fields["ETQ"],$rs_campos->fields["TIPO_CMP"],$rs_campos->fields["NMB_CMP"],$rs_campos->fields["CLASS"],$rs_campos->fields["EVNT"],$rs_campos->fields["STYLE"],$rs_campos->fields["ID_SECC"],$rs_campos->fields["SQL_"],$rs_campos->fields["READON"],$rs_campos->fields["HTML"],$rs_campos->fields["CMP_ASOC"],$rs_campos->fields["TABLA_DEST"],$Title,$Asterisk);
													  $this->HTML.="<TR STYLE='BACKGROUND-COLOR:#e7eef6;'>";
													  $this->HTML.=$this->Input_Tag;

													//HA DOBLE COLUMNA
													$rs_campos->MoveNext();
													$Row_count++;
													$Bandera_secc=($Comp_secc != $rs_campos->fields["ID_SECC"])?("FALSE"):("TRUE");
													
													
													 if( (!$rs_campos->EOF) && ($Bandera_secc=='TRUE') && ($rs_campos->fields["TIPO_CMP"] != 'LABEL') && ($rs_campos->fields["TIPO_CMP"] != 'BUTTON'))
													 {
															$Title=($rs_campos->fields["OBLG"]=='Y')?("TITLE='CAMPO REQUERIDO'"):("");
															$Asterisk  =($rs_campos->fields["OBLG"]=='Y')?("&nbsp;<IMG ID='IMG_".$rs_campos->fields["NMB_CMP"]."' CLASS='".$IMG_REFER."'  BORDER=0 SRC='".$this->IMG."asterisk.png'  ALT='editando'  STYLE='height:13px; width:13px; align:top;' />"):("");
															$this->get_input_tag($rs_campos->fields["ETQ"],$rs_campos->fields["TIPO_CMP"],$rs_campos->fields["NMB_CMP"],$rs_campos->fields["CLASS"],$rs_campos->fields["EVNT"],$rs_campos->fields["STYLE"],$rs_campos->fields["ID_SECC"],$rs_campos->fields["SQL_"],$rs_campos->fields["READON"],$rs_campos->fields["HTML"],$rs_campos->fields["CMP_ASOC"],$rs_campos->fields["TABLA_DEST"],$Title,$Asterisk);
													  
													  $this->HTML.=$this->Input_Tag ."</TR>";

													  

													 }
													 else
													 {
															if(($rs_campos->fields["TIPO_CMP"] == 'LABEL' || $rs_campos->fields["TIPO_CMP"] == 'BUTTON') && ($Bandera_secc=='TRUE'))
															{
																 $this->HTML.=" 
																			   <TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:20%;'>       &nbsp; </TH>
																			   <TD STYLE='TEXT-ALIGN:LEFT;'>                   &nbsp; </TD>
																			 </TR>";

																$this->HTML.="<TR STYLE='BACKGROUND-COLOR:#f2f5f7;'>";
																$this->get_input_tag($rs_campos->fields["ETQ"],$rs_campos->fields["TIPO_CMP"],'',$rs_campos->fields["CLASS"],'',$rs_campos->fields["STYLE"],'','','',$rs_campos->fields["HTML"],'','','','');
																$this->HTML.=$this->Input_Tag ."</TR>";

															}
															elseif($Bandera_secc=='FALSE')
															{
															 $this->HTML.=" 
																			   <TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:20%;'>       &nbsp; </TH>
																			   <TD STYLE='TEXT-ALIGN:LEFT;'>                   &nbsp;</TD>
																			 </TR>";

														     }
    
													}
											}
											else
											{
													$this->HTML.="<TR STYLE='BACKGROUND-COLOR:#f2f5f7;'>";
													$this->get_input_tag($rs_campos->fields["ETQ"],$rs_campos->fields["TIPO_CMP"],'',$rs_campos->fields["CLASS"],'',$rs_campos->fields["STYLE"],'','','',$rs_campos->fields["HTML"],'','','','');
													$this->HTML.=$this->Input_Tag ."</TR>";

													$Bandera_secc="FALSE";
													$Row_count++;

											}

											 if($Bandera_secc=='FALSE')
												$rs_campos->Move(($Row_count-1));
											 else
											   $Row_count++;

								}

								 if($this->COLUMNS == '1')//HA DOBLE COLUMNA
								 {
													  $Title     =($rs_campos->fields["OBLG"]=='Y')?("TITLE='CAMPO REQUERIDO'"):("");
													  $Asterisk  =($rs_campos->fields["OBLG"]=='Y')?("&nbsp;<IMG ID='IMG_".$rs_campos->fields["NMB_CMP"]."' CLASS='".$CLASS_REFER."' BORDER=0 SRC='".$this->IMG."asterisk.png'  ALT='editando'  STYLE='height:13px; width:13px; align:top;' />"):("");
													  $this->get_input_tag($rs_campos->fields["ETQ"],$rs_campos->fields["TIPO_CMP"],$rs_campos->fields["NMB_CMP"],$rs_campos->fields["CLASS"],$rs_campos->fields["EVNT"],$rs_campos->fields["STYLE"],$rs_campos->fields["ID_SECC"],$rs_campos->fields["SQL_"],$rs_campos->fields["READON"],$rs_campos->fields["HTML"],$rs_campos->fields["CMP_ASOC"],$rs_campos->fields["TABLA_DEST"],$Title,$Asterisk);
													  $this->HTML.="<TR STYLE='BACKGROUND-COLOR:#e7eef6;'>";
													  $this->HTML.=$this->Input_Tag;

								 }
											   
							$rs_campos->MoveNext();
													$Bandera_secc=($Comp_secc != $rs_campos->fields["ID_SECC"])?("FALSE"):("TRUE");

													              
							}

				 $this->HTML.="</FORM>";//FIN FORMULARIO

	}//FIN RENDER PREVIEW

	function get_status_valido_update()
	{

		   if($this->ID_Tipocredito != 2)
			{
						 $TABLA_DEST = ($this->ID_Tipocredito < 4)?('solicitud'):('solicitud_pmoral');
						 
						$Sql_status="SELECT
											Status_solicitud		AS STAT
										FROM ".$TABLA_DEST."
										WHERE ID_Solicitud ='".$this->ID_SOLICITUD."' ";
						$rs_stat=$this->db->Execute($Sql_status);
						
						$STATUS_SOLI = $rs_stat->fields["STAT"];
						
						 if ( ($STATUS_SOLI != 'DISPOSICION - CREDITO') && ($STATUS_SOLI != 'IMPRESION/DIGITALIZACION') && ($STATUS_SOLI != 'ALTA CREDITO') )
							$EDIT_PRIVILEGE = "TRUE";
						else
							$EDIT_PRIVILEGE = "FALSE";

					//INVESTIGAR SUCESOS
					$TABLA_DEST = ($this->ID_Tipocredito < 4)?('solicitud_sucesos'):('solicitud_pmoral_sucesos');
					$SQL_COMIT="SELECT
										COUNT(Status)			AS STAT
									FROM ".$TABLA_DEST."
									WHERE ID_Solicitud ='".$this->ID_SOLICITUD."'
										AND Status = 'COMITE APROBADO' ";
					$rs_comit=$this->db->Execute($SQL_COMIT);
					
					
					 if ( $rs_comit->fields["STAT"] > 0 )
						$EDIT_PRIVILEGE = "FALSE";
						
			}
			else
			{
					$SQL_CONS="SELECT
									ID_grupo_soli		AS GPO_SOLI
								FROM solicitud
								WHERE ID_Solicitud ='".$this->ID_SOLICITUD."' ";
					$rs_gpo=$this->db->Execute($SQL_CONS);
						
					$SQL_GPO="SELECT
										Status_grupo	as ESTAT
								FROM
										grupo_solidario
								WHERE 
										ID_grupo_soli ='".$rs_gpo->fields["GPO_SOLI"]."' ";
					$rs_gpo=$this->db->Execute($SQL_GPO);

					if( ($rs_gpo->fields["ESTAT"] == 'PROCESO INTEGRACION') || ($rs_gpo->fields["ESTAT"] == 'PROCESO INTEGRACION - RENOVACION') )
						$EDIT_PRIVILEGE = "TRUE";
					else
						$EDIT_PRIVILEGE = "FALSE";
			}

		if($EDIT_PRIVILEGE == "FALSE")
			$this->Set_edita_privilege("FALSE");
		
	}

   function Set_edita_privilege($EDIT_ALL)
   {
		$this->EDIT_PRIVILEGE=$EDIT_ALL;
	}

	function Render_edita($ID_SOLICITUD)
	{
			   $this->ID_SOLICITUD = $ID_SOLICITUD;
			   $this->get_consulta_soli();


			   $EDIT_PRIVILEGE = $this->get_status_valido_update();
			   $EDIT_PRIVILEGE = ( $this->EDIT_PRIVILEGE == 'FALSE' )?(" AND cat_tipo_credito_secciones.Privilege_edit		= 'N' "):("");

			   $this->get_encabezado_soli('EDITA');//ENCABEZADO DE LA SOLICITUD FECHA,SUCURSAL, CAPTURISTA ETC.

				$Sql_campos="SELECT 
								   cat_tipo_credito_campos.ID_campo                 AS ID_CMP,
								   cat_tipo_credito_campos.ID_seccion     			AS ID_SECC,
								   cat_tipo_credito_secciones.Orden					AS ORD_SECC,
								   cat_tipo_credito_secciones.Nombre				AS NMB_SECC,
								   cat_tipo_credito_campos.Etiqueta					AS ETQ,
								   cat_tipo_credito_campos.Obligatorio				AS OBLG,
								   cat_tipo_credito_campos.Obligatorio_sistema		AS OBLG_SIST,
								   cat_tipo_credito_campos.Visibilidad				AS VSBL,
								   cat_tipo_credito_campos.Orden					AS ORDN,
								   cat_tipo_credito_campos.Nombre_campo				AS NMB_CMP,
								   cat_tipo_credito_campos.Tipo						AS TIPO_CMP,
								   cat_tipo_credito_campos.Style					AS STYLE,
								   cat_tipo_credito_campos.Evento					AS EVNT,
								   cat_tipo_credito_campos.Class					AS CLASS,
								   cat_tipo_credito_campos.Sql						AS SQL_,
								   cat_tipo_credito_campos.Readonly					AS READON,
								   cat_tipo_credito_campos.Html						AS HTML,
								   cat_tipo_credito_campos.List_cmp_asoc			AS CMP_ASOC,
								   cat_tipo_credito_campos.Tabla_destino			AS TABLA_DEST,
								   cat_tipo_credito_secciones.Tipo_dinamico			AS TMP_DNMCO,
								   cat_tipo_credito_secciones.Datos_dinamicos		AS CMP_DINAMICOS
							FROM
									cat_tipo_credito_campos
								LEFT JOIN cat_tipo_credito_secciones ON cat_tipo_credito_campos.ID_seccion = cat_tipo_credito_secciones.ID_seccion
									AND cat_tipo_credito_campos.ID_seccion IS NOT NULL
									
							WHERE cat_tipo_credito_campos.ID_Tipocredito 		= '".$this->ID_Tipocredito."'
								AND cat_tipo_credito_campos.ID_Tipo_regimen 	= '".$this->ID_Tiposolicitud."'
								AND cat_tipo_credito_campos.Visibilidad 		= 'Y'
								".$EDIT_PRIVILEGE."
							 ORDER BY ORD_SECC,ORDN ";
			   $rs_campos=$this->db->Execute($Sql_campos);      

							$Comp_secc		="";
							$Row_count		=0;
							$this->get_campos_referencia();
							$this->get_referencias_capturadas();
							
							While(!$rs_campos->EOF)
							{

									if($Comp_secc != $rs_campos->fields["ID_SECC"])
										{
														  $this->Body_soli($rs_campos->fields["ID_SECC"],$rs_campos->fields["NMB_SECC"]);
														  $this->HTML.=($Comp_secc=="")?(""):("</TABLE><BR /></DIV>");//FIN DIV DEL TAB O ACORDIÓN SECCIÓN
														  $this->HTML.=$this->Design_Tag; //TAG ACORDION Ó TABS 

															if($rs_campos->fields["CMP_DINAMICOS"] == 'N')
																{$ID_TABLE="";$CLASS_REFER=""; $IMG_REFER="";}
															else
																{
																	$ARR_ID_REFERES = $this->get_labels_referencias($rs_campos->fields["TMP_DNMCO"]);
																	$ID_TABLE       = $ARR_ID_REFERES["ID_TABLE"];$CLASS_REFER=$ARR_ID_REFERES["CLASS_REFER"]; $IMG_REFER=$ARR_ID_REFERES["IMG_REFER"];
																}
															
														  $Comp_secc=$rs_campos->fields["ID_SECC"];
														   $this->HTML.="<BR />
																		 <TABLE  CELLPADDING='3' CELLSPACING='2' BORDER='0px' WIDTH='100%' CLASS='".$CLASS_REFER."' ID='".$ID_TABLE."'>";

										}

							 if($this->COLUMNS == '2')//HA DOBLE COLUMNA
							 {

											if( ($rs_campos->fields["TIPO_CMP"] != 'LABEL') && ($rs_campos->fields["TIPO_CMP"] != 'BUTTON') )
											{
													  $Title     =($rs_campos->fields["OBLG"]=='Y')?("TITLE='CAMPO REQUERIDO'"):("");
													  $Asterisk  =($rs_campos->fields["OBLG"]=='Y')?("&nbsp;<IMG ID='IMG_".$rs_campos->fields["NMB_CMP"]."' CLASS='".$IMG_REFER."' BORDER=0 SRC='".$this->IMG."asterisk.png'  ALT='editando'  STYLE='height:13px; width:13px; align:top;' />"):("");
													  $this->get_input_tag($rs_campos->fields["ETQ"],$rs_campos->fields["TIPO_CMP"],$rs_campos->fields["NMB_CMP"],$rs_campos->fields["CLASS"],$rs_campos->fields["EVNT"],$rs_campos->fields["STYLE"],$rs_campos->fields["ID_SECC"],$rs_campos->fields["SQL_"],$rs_campos->fields["READON"],$rs_campos->fields["HTML"],$rs_campos->fields["CMP_ASOC"],$rs_campos->fields["TABLA_DEST"],$Title,$Asterisk);
													  $this->HTML.="<TR STYLE='BACKGROUND-COLOR:#e7eef6;'>";
													  $this->HTML.=$this->Input_Tag;

													//HA DOBLE COLUMNA
													$rs_campos->MoveNext();
													$Row_count++;
													$Bandera_secc=($Comp_secc != $rs_campos->fields["ID_SECC"])?("FALSE"):("TRUE");
													
													
													 if( (!$rs_campos->EOF) && ($Bandera_secc=='TRUE') && ($rs_campos->fields["TIPO_CMP"] != 'LABEL') && ($rs_campos->fields["TIPO_CMP"] != 'BUTTON'))
													 {
															$Title=($rs_campos->fields["OBLG"]=='Y')?("TITLE='CAMPO REQUERIDO'"):("");
															$Asterisk  =($rs_campos->fields["OBLG"]=='Y')?("&nbsp;<IMG ID='IMG_".$rs_campos->fields["NMB_CMP"]."' CLASS='".$IMG_REFER."'  BORDER=0 SRC='".$this->IMG."asterisk.png'  ALT='editando'  STYLE='height:13px; width:13px; align:top;' />"):("");
															$this->get_input_tag($rs_campos->fields["ETQ"],$rs_campos->fields["TIPO_CMP"],$rs_campos->fields["NMB_CMP"],$rs_campos->fields["CLASS"],$rs_campos->fields["EVNT"],$rs_campos->fields["STYLE"],$rs_campos->fields["ID_SECC"],$rs_campos->fields["SQL_"],$rs_campos->fields["READON"],$rs_campos->fields["HTML"],$rs_campos->fields["CMP_ASOC"],$rs_campos->fields["TABLA_DEST"],$Title,$Asterisk);
													  
													  $this->HTML.=$this->Input_Tag ."</TR>";

													  

													 }
													 else
													 {
															if(($rs_campos->fields["TIPO_CMP"] == 'LABEL' || $rs_campos->fields["TIPO_CMP"] == 'BUTTON') && ($Bandera_secc=='TRUE'))
															{
																 $this->HTML.=" 
																			   <TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:20%;'>       &nbsp; </TH>
																			   <TD STYLE='TEXT-ALIGN:LEFT;'>                   &nbsp; </TD>
																			 </TR>";

																$this->HTML.="<TR STYLE='BACKGROUND-COLOR:#f2f5f7;'>";
																$this->get_input_tag($rs_campos->fields["ETQ"],$rs_campos->fields["TIPO_CMP"],'',$rs_campos->fields["CLASS"],'',$rs_campos->fields["STYLE"],'','','',$rs_campos->fields["HTML"],'','','','');
																$this->HTML.=$this->Input_Tag ."</TR>";

															}
															elseif($Bandera_secc=='FALSE')
															{
															 $this->HTML.=" 
																			   <TH STYLE='TEXT-ALIGN:RIGHT; WIDTH:20%;'>       &nbsp; </TH>
																			   <TD STYLE='TEXT-ALIGN:LEFT;'>                   &nbsp;</TD>
																			 </TR>";

														     }
    
													}
											}
											else
											{
													$this->HTML.="<TR STYLE='BACKGROUND-COLOR:#f2f5f7;'>";
													$this->get_input_tag($rs_campos->fields["ETQ"],$rs_campos->fields["TIPO_CMP"],'',$rs_campos->fields["CLASS"],'',$rs_campos->fields["STYLE"],'','','',$rs_campos->fields["HTML"],'','','','');
													$this->HTML.=$this->Input_Tag ."</TR>";

													$Bandera_secc="FALSE";
													$Row_count++;

											}

											 if($Bandera_secc=='FALSE')
												$rs_campos->Move(($Row_count-1));
											 else
											   $Row_count++;

								}

								 if($this->COLUMNS == '1')//HA UNA COLUMNA
								 {
									 if (($Bandera_secc=='FALSE') &&  ($rs_campos->fields["CMP_DINAMICOS"] == 'Y') )
												$rs_campos->Move(($Row_count-1));
											 else
											   $Row_count++;
											   
													  $Title     =($rs_campos->fields["OBLG"]=='Y')?("TITLE='CAMPO REQUERIDO'"):("");
													  $Asterisk  =($rs_campos->fields["OBLG"]=='Y')?("&nbsp;<IMG ID='IMG_".$rs_campos->fields["NMB_CMP"]."' CLASS='".$CLASS_REFER."' BORDER=0 SRC='".$this->IMG."asterisk.png'  ALT='editando'  STYLE='height:13px; width:13px; align:top;' />"):("");
													  $this->get_input_tag($rs_campos->fields["ETQ"],$rs_campos->fields["TIPO_CMP"],$rs_campos->fields["NMB_CMP"],$rs_campos->fields["CLASS"],$rs_campos->fields["EVNT"],$rs_campos->fields["STYLE"],$rs_campos->fields["ID_SECC"],$rs_campos->fields["SQL_"],$rs_campos->fields["READON"],$rs_campos->fields["HTML"],$rs_campos->fields["CMP_ASOC"],$rs_campos->fields["TABLA_DEST"],$Title,$Asterisk);
													  $this->HTML.="<TR STYLE='BACKGROUND-COLOR:#e7eef6;'>";
													  $this->HTML.=$this->Input_Tag;



								 }
								 
								 

													$CMP_DINAMICOS		=	$rs_campos->fields["CMP_DINAMICOS"];
													$TMP_DNMCO			=	$rs_campos->fields["TMP_DNMCO"];
							$rs_campos->MoveNext();


							//VALIDAR REFERENCIAS DINÁMICAS
							if($Comp_secc != $rs_campos->fields["ID_SECC"] &&  $CMP_DINAMICOS	== 'Y')	
								{
											     if( ($TMP_DNMCO == 'REFERENCIA_ACCIONISTA')    )
											     {
														if($this->INDEX_REFERENCIAS_ACCIONISTAS < $this->REFERENCIAS_CAPTURADAS_ACCIONISTAS)
														{
																$this->HTML.="</TABLE>";//FIN TABLE REFERENCIA
																$this->HTML.="<TABLE  CELLPADDING='3' CELLSPACING='2' BORDER='0px' WIDTH='100%' >";//INICIO TABLE REFERENCIA
																$Row_count		= $Row_count - $this->CAMPOS_REFERENCIAS_ACCIONISTAS;
																$rs_campos->Move(($Row_count));
														}
														else
														{
																//AGREGAR REFERENCIAS
																$this->HTML.=$this->get_html_new_referencias($TMP_DNMCO);
														}
														$this->INDEX_REFERENCIAS_ACCIONISTAS ++; 


												 }
											 else if( ($TMP_DNMCO == 'REFERENCIA_FUNCIONARIO')   )
											     {

														if($this->INDEX_REFERENCIAS_FUNCIONARIOS < $this->REFERENCIAS_CAPTURADAS_FUNCIONARIOS)
														{
																$this->HTML.="</TABLE>";//FIN TABLE REFERENCIA
																$this->HTML.="<TABLE  CELLPADDING='3' CELLSPACING='2' BORDER='0px' WIDTH='100%' >";//INICIO TABLE REFERENCIA
																$Row_count		= $Row_count - $this->CAMPOS_REFERENCIAS_FUNCIONARIOS;
																$rs_campos->Move(($Row_count));
														}
														else
														{
																//AGREGAR REFERENCIAS
																$this->HTML.=$this->get_html_new_referencias($TMP_DNMCO);
														}
														$this->INDEX_REFERENCIAS_FUNCIONARIOS ++; 

												 }
											 else if( ($TMP_DNMCO == 'REFERENCIA_AUTORIZADO_FUNC'  )  )
											     {
														if($this->INDEX_REFERENCIAS_FUNCIONARIOS_AUT < $this->REFERENCIAS_CAPTURADAS_FUNCIONARIOS_AUT)
														{
																$this->HTML.="</TABLE>";//FIN TABLE REFERENCIA
																$this->HTML.="<TABLE  CELLPADDING='3' CELLSPACING='2' BORDER='0px' WIDTH='100%' >";//INICIO TABLE REFERENCIA
																$Row_count		= $Row_count - $this->CAMPOS_REFERENCIAS_FUNCIONARIOS_AUT;
																$rs_campos->Move(($Row_count));
														}
														else
														{
																//AGREGAR REFERENCIAS
																$this->HTML.=$this->get_html_new_referencias($TMP_DNMCO);
														}
														$this->INDEX_REFERENCIAS_FUNCIONARIOS_AUT ++; 


												 }
											 else if( ($TMP_DNMCO == 'REFERENCIA_PROVEEDOR'  )   )
											     {
														if($this->INDEX_REFERENCIAS_PROVEEDORES < $this->REFERENCIAS_CAPTURADAS_PROVEEDORES)
														{
																$this->HTML.="</TABLE>";//FIN TABLE REFERENCIA
																$this->HTML.="<TABLE  CELLPADDING='3' CELLSPACING='2' BORDER='0px' WIDTH='100%' >";//INICIO TABLE REFERENCIA
																$Row_count		= $Row_count - $this->CAMPOS_REFERENCIAS_PROVEEDORES;
																$rs_campos->Move(($Row_count));
														}
														else
														{
																//AGREGAR REFERENCIAS
																$this->HTML.=$this->get_html_new_referencias($TMP_DNMCO);
														}
														$this->INDEX_REFERENCIAS_PROVEEDORES ++; 


												 }
											 else if( ($TMP_DNMCO == 'REFERENCIA_BANCARIA'  )   )
											     {

														if($this->INDEX_REFERENCIAS_BANCARIAS < $this->REFERENCIAS_CAPTURADAS_BANCARIAS)
														{
																$this->HTML.="</TABLE>";//FIN TABLE REFERENCIA
																$this->HTML.="<TABLE  CELLPADDING='3' CELLSPACING='2' BORDER='0px' WIDTH='100%' >";//INICIO TABLE REFERENCIA
																$Row_count		= $Row_count - $this->CAMPOS_REFERENCIAS_BANCARIAS;
																$rs_campos->Move(($Row_count));
														}
														else
														{
																//AGREGAR REFERENCIAS
																$this->HTML.=$this->get_html_new_referencias($TMP_DNMCO);
														}
														$this->INDEX_REFERENCIAS_BANCARIAS ++; 

												 }
											 else if( ($TMP_DNMCO == 'REFERENCIA_COMERCIAL'  ) )
											     {
													 
														if($this->INDEX_REFERENCIAS_COMERCIALES < $this->REFERENCIAS_CAPTURADAS_COMERCIALES)
														{
																$this->HTML.="</TABLE>";//FIN TABLE REFERENCIA
																$this->HTML.="<TABLE  CELLPADDING='3' CELLSPACING='2' BORDER='0px' WIDTH='100%' >";//INICIO TABLE REFERENCIA
																$Row_count		= $Row_count - $this->CAMPOS_REFERENCIAS_COMERCIALES;
																$rs_campos->Move(($Row_count));
														}
														else
														{
																//AGREGAR REFERENCIAS
																$this->HTML.=$this->get_html_new_referencias($TMP_DNMCO);
														}
														$this->INDEX_REFERENCIAS_COMERCIALES ++; 


												 }
											 else if( ($TMP_DNMCO == 'REFERENCIA_CHEQUES'  ) )
											     {
													 
														if($this->INDEX_REFERENCIAS_CHEQUES < $this->REFERENCIAS_CAPTURADAS_CHEQUES)
														{
																$this->HTML.="</TABLE>";//FIN TABLE REFERENCIA
																$this->HTML.="<TABLE  CELLPADDING='3' CELLSPACING='2' BORDER='0px' WIDTH='100%' >";//INICIO TABLE REFERENCIA
																$Row_count		= $Row_count - $this->CAMPOS_REFERENCIAS_CHEQUES;
																$rs_campos->Move(($Row_count));
														}
														else
														{
																//AGREGAR REFERENCIAS
																$this->HTML.=$this->get_html_new_referencias($TMP_DNMCO);
														}
														$this->INDEX_REFERENCIAS_CHEQUES ++; 


												 }
											 else if( ($TMP_DNMCO == 'REFERENCIA'   )  )
											     {
													 
														if (($this->INDEX_REFERENCIAS < $this->REFERENCIAS_CAPTURADAS) )
														{
																$this->HTML.="</TABLE>";//FIN TABLE REFERENCIA
																$this->HTML.="<TABLE  CELLPADDING='3' CELLSPACING='2' BORDER='0px' WIDTH='100%' >";//INICIO TABLE REFERENCIA
																$Row_count		= $Row_count - $this->CAMPOS_REFERENCIAS;
																$rs_campos->Move(($Row_count));
																														
														}
														else
														{
																//AGREGAR REFERENCIAS
																$this->HTML.=$this->get_html_new_referencias($TMP_DNMCO);
														}
														$this->INDEX_REFERENCIAS ++; 
														
														

												 }

												 
								}//FIN REFERENCIAS DINÁMICAS

					}//FIN WHILE

				 $this->HTML.="</DIV>
				                  </DIV>";//FIN DIV ACORDIÓN O TABS CONTENEDOR //FIN DIV PRINCIPAL
				 $this->HTML.="<DIV ID='dialog-catalog' TITLE='AVISO S2CREDIT.'  STYLE='DISPLAY:NONE;'>
							   </DIV> ";
				 $this->HTML.="<DIV ID='dialog-searchcp' TITLE='BUSCAR CÓDIGO POSTAL.'  STYLE='DISPLAY:NONE;'>
							   </DIV> ";
							   
				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='TIPO_CREDITO' 				VALUE='".$this->ID_Tipocredito."' 		/>";
				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='TIPO_SOLICITUD' 			VALUE='".$this->ID_Tiposolicitud."'		/>";
				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='TIPO_REGIMEN' 				VALUE='".$this->TIPO_REGIMEN."'			/>";
				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='NOMINA_ESPECIAL' 			VALUE='".$this->VALIDATION_NOMINA."'	/>";
				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='Param1' 					VALUE='ID_Solicitud'					/>";
				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='Param2' 					VALUE='".$this->ID_SOLICITUD."'			/>";
				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='ACCION_SOLICITUD' 			VALUE='EDITAR'							/>";
				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='GENERA_RFC' 				VALUE='".$this->GENERA_RFC."'			/>";
 				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='GENERA_HCLAVE' 				VALUE='".$this->GENERA_HCLAVE."'		/>";
 				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='GENERA_CURP' 				VALUE='".$this->GENERA_CURP."'			/>";
 				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='CURP_DESGLOSAR' 			VALUE='".$this->CURP_DESGLOSAR."'		/>";
 				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='NUM_CLIENTE' 				VALUE='".$this->NUM_CLIENTE."'			/>";
				 $this->HTML.="<INPUT TYPE='HIDDEN' ID='EDIT_PRIVILEGE' 			VALUE='".$this->EDIT_PRIVILEGE."'		/>";

				 $this->HTML.="</FORM>";//FIN FORMULARIO

	}//FIN Render_edita

   /****************EDICIÓN DE SOLICITUDES***********************/

   function get_historico_solicitud($ID_SOLICITUD)
   {
			   $this->ID_SOLICITUD = $ID_SOLICITUD;
			   $this->get_encabezado_soli('HISTORIAL_SOLI');//ENCABEZADO DE LA SOLICITUD FECHA,SUCURSAL, CAPTURISTA ETC..
			   $Tabla_solicitus_sucesos=($this->ID_Tipocredito < 4)?('solicitud_sucesos'):('solicitud_pmoral_sucesos');
				$SQL_SUC="SELECT
							Fecha			AS FECH,
							Atendio			AS RESP,
							Status			AS STAT,
							Suceso			AS SUC
						FROM ".$Tabla_solicitus_sucesos."
					WHERE ID_Solicitud ='".$this->ID_SOLICITUD."' ";
				$rs_cons=$this->db->Execute($SQL_SUC);
				
			$Table_grid="<TABLE  CELLSPACING='0' STYLE='' ALIGN='CENTER' BORDER='0px' WIDTH='99%'>

					<TR>
						<TH  ALIGN='CENTER' COLSPAN='8'  STYLE='-moz-border-radius-topleft:  6px; -moz-border-radius-topright: 6px;  background-color : #6fa7d1;'>
							<B> <FONT SIZE='2' COLOR='WHITE'>SUCESOS ASOCIADOS A  LA SOLICITUD DE CRÉDITO.</FONT></B>
						</TH>
					</TR>
					
					<TR ALIGN='center' VALIGN='middle'  BGCOLOR='#6fa7d1' STYLE='height:30px;'>
						   <TH STYLE='font-size:small;  color:white; text-decoration:underline;'  WIDTH='10%' >     			SUCESO         </TH>
						   <TH STYLE='font-size:small;  color:white; text-decoration:underline;'  WIDTH='30%' >     			FECHA          </TH>
						   <TH STYLE='font-size:small;  color:white; text-decoration:underline;'  WIDTH='30%' >     			RESPONSABLE    </TH>
						   <TH STYLE='font-size:small;  color:white; text-decoration:underline;'  WIDTH='30%' >     			STATUS         </TH>
					 </TR>";

					$cont=1;
					While(!$rs_cons->EOF)
					{
					  /**************/
					  $row_color       =(($cont % 2) == 0 )?('#FDFEFF'):('#E7EEF6');
					  /**************/
					  $Table_grid .="<TR ALIGN='center' VALIGN='middle' BgCOLOR='".$row_color ."' >
											<TH STYLE='font-size:small;  text-align:center; color:gray;' 	WIDTH='10%' >
												".$cont."
												<IMG SRC='".$this->IMG."toggle-small-expand.png' STYLE='vertical-align:middle; cursor:pointer;' ID='IMG_SUC_SOLI_".$cont."' CLASS='SHOW_DETAIL_SUC' TITLE='DESPLEGAR DETALLES...' />
											</TH>

											
											<TH STYLE='font-size:small;  text-align:left;   ' 				WIDTH='30%' >    ".ffecha($rs_cons->fields["FECH"])."		</TH>
											<TH STYLE='font-size:small;  text-align:center; color:navy;' 	WIDTH='30%' >    ".strtoupper($rs_cons->fields["RESP"])."	</TH>
											<TH STYLE='font-size:small;  text-align:center; ' 				WIDTH='30%' >    ".strtoupper($rs_cons->fields["STAT"])."		 	</TH>

									 </TR>";

 
					 $Table_grid .="<TR VALIGN='middle' BgCOLOR='".$row_color ."' STYLE='display:none;' ID='ROW_SUC_".$cont."'>
											<TD COLSPAN='8' >
												<DIV  CLASS='ui-widget-content ui-corner-all'  STYLE='height:auto; width:85%; position:relative; left:10%; '>
													<TABLE CELLSPACING='0' STYLE='' ALIGN='CENTER' BORDER='0px' WIDTH='99%'>

														<TR STYLE='font-size:small;'>
															<TH STYLE='text-align:right; width:10%;' >Descripción :</TH>
															<TD STYLE='text-align:left;  width:90%;'>".$rs_cons->fields["SUC"]."</TD>
														</TR>
														
													</TABLE>
												</DIV>
											</TD>
									 </TR>";
					  $cont++;
					  $rs_cons->MoveNext();
					}

					if($cont==1)
					{
					  $Table_grid .="<TR ALIGN='center' VALIGN='middle' BgCOLOR='white' >
											<TH STYLE='font-size:small;  text-align:center; color:gray; height:50px;' COLSPAN='8' >
												<IMG SRC='".$this->IMG."exclamation.png'  STYLE='height:15px; cursor:pointer;' /> &nbsp; NO SE ENCONTRARON SUCESOS ASOCIADOS A LA SOLICITUD.
											</TH>
									 </TR>";
					}
					
	$Table_grid.="</TABLE>";



			   $this->HTML.=$Table_grid;
   }//FIN HISTORIAL SOLI

	function get_vista_solicitud_datos($ID_SOLICITUD,$MODULO)
	{
		$this->ID_SOLICITUD = $ID_SOLICITUD;
		$this->get_encabezado_soli('HISTORIAL_SOLI');//ENCABEZADO DE LA SOLICITUD FECHA,SUCURSAL, CAPTURISTA ETC..

	}

}//FIN CLASE
?>

