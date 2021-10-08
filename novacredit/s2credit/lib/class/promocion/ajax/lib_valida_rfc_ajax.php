<?
/****************************************/
/*Fecha: 07/Octubre/2011
/*Autor: Tonathiu Cárdenas
/*Descripción: GENERA LOS VALIDACIÓN DEL RFC
/*Dependencias: valida_captura.js
/****************************************/

$exit = 0;
$noheader =1;
include($DOCUMENT_ROOT."/rutas.php");			//CORE CONSTANTES S2CREDIT

//Inicio conexión
$db = ADONewConnection(SERVIDOR);
$db->Connect(IP,USER,PASSWORD,NUCLEO);
//Fin Conexión

/***********VALIDAMOS QUE EL RFC Y HOMOCLAVE NO SE ENCUENTREN EN LA BD**************/
function get_solicitud($rfc,$homoclave)
{
	global $db;

	$SQL_SOLI= "SELECT
						ID_Solicitud 	AS ID_SOLI,
						Cte_moroso		AS MOROSO
	                   FROM
							solicitud
	                   WHERE solicitud.RFC     = '".$rfc."'
	                     AND solicitud.Hclave  = '".$homoclave."'  ";
	$rs_soli=$db->Execute($SQL_SOLI);

	While(!$rs_soli->EOF)
	{
		if($rs_soli->fields["MOROSO"] == 'Y')
		{
			$ID_SOLICITUD = $rs_soli->fields["ID_SOLI"];
			$CTE_MOROSO	  = $rs_soli->fields["MOROSO"];
		}
		
		$rs_soli->MoveNext();
	}

	return $ID_SOLICITUD;
}


function get_solicitud_morosa($ID_SOLI_VERIF)
{
	global $db;

	$SQL_SOLI= "SELECT
						COUNT(ID_Solicitud) AS CUANTOS
	                   FROM
							solicitud_pfisica_morosa
	                   WHERE
								ID_Solicitud = '".$ID_SOLI_VERIF."' ";
	$rs_soli=$db->Execute($SQL_SOLI);

	return $rs_soli->fields["CUANTOS"];

}

function get_solicitud_cliente($tbl_cliente,$num_cliente)
{
	global $db;
	
	$SQL_SOLI= "SELECT
						ID_Solicitud AS ID_SOLI
	                   FROM
							".$tbl_cliente."
	                   WHERE
								".$tbl_cliente.".Num_cliente = '".$num_cliente."' ";
	$rs_soli=$db->Execute($SQL_SOLI);

	return $rs_soli->fields["ID_SOLI"];
}

function valida_rfc_solicitante($rfc,$homoclave,$tbl_soli,$tbl_cliente,$id_soli,$accion_solicitud,$num_cliente)
{
 global $db;
	
		$RFC_SOLI=($tbl_soli=='solicitud')?('RFC'):('RFC_pfae');
		$HCLAVE_SOLI=($tbl_soli=='solicitud')?('Hclave'):('Hclave_pfae');
		if($accion_solicitud == 'CAPTURAR')
		{
				 if( (!empty($rfc) && !empty($homoclave)) && ( strlen($rfc)==10 && strlen($homoclave)==3  )   )
				 {

					$msg="";
					$DICRIMINA_CTE=( !empty($num_cliente) && ( $num_cliente > 0 ) )?("	AND ".$tbl_cliente.".Num_cliente <> '".$num_cliente."' "):("");
					//IDENTIFICAR SI ES CLIENTE
					/****************************************/
					$sql_valida= "SELECT COUNT(*) AS CONT
									   FROM ".$tbl_cliente."
									   WHERE ".$tbl_cliente.".".$RFC_SOLI."  		= '".$rfc."'
										 AND ".$tbl_cliente.".".$HCLAVE_SOLI."		='".$homoclave."'
										 ".$DICRIMINA_CTE." ";
					$rs_valida=$db->Execute($sql_valida);
					if($rs_valida->fields["CONT"] > '0')
					{
						$sql_cons= "SELECT Num_cliente AS NUMCTE
								   FROM ".$tbl_cliente."
								   WHERE ".$tbl_cliente.".".$RFC_SOLI."    = '".$rfc."'
									 AND ".$tbl_cliente.".".$HCLAVE_SOLI." ='".$homoclave."' 
													 #AND ".$tbl_cliente.".ID_Solicitud <>'".$id_soli."'   ";
						$rs_cons=$db->Execute($sql_cons);
						$Num_cliente=$rs_cons->fields["NUMCTE"];

						$msg="EL SOLICITANTE YA CUENTA CON UN NÚMERO DE CLIENTE: ".$Num_cliente.", FAVOR DE UTILIZAR EL MÓDULO DE RENOVACIONES.";

					}
					else
					{
										if(( !empty($num_cliente) && ( $num_cliente > 0 ) ))
											$ID_SOLICITUD = get_solicitud_cliente($tbl_cliente,$num_cliente);
										
										$DICRIMINA_CTE =( !empty($num_cliente) && ( $num_cliente > 0 ) )?("	AND ".$tbl_soli.".ID_Solicitud <> '".$ID_SOLICITUD."' "):("");
										
										/***************************************/
										//IDENTIFICAR SI TIENE UNA SOLICITUD EN PROCESO

										$discriminante=(!empty($id_soli))?(" AND ".$tbl_soli.".ID_Solicitud <> '".$id_soli."' "):("");

										$sql_valida= "SELECT COUNT(*) AS CONT
														   FROM ".$tbl_soli."
														   WHERE ".$tbl_soli.".".$RFC_SOLI."    = '".$rfc."'
															 AND ".$tbl_soli.".".$HCLAVE_SOLI." ='".$homoclave."'
															 ".$discriminante."
															 ".$DICRIMINA_CTE." ";
															 
										$rs_valida=$db->Execute($sql_valida);
										if($rs_valida->fields["CONT"] > '0')
										{
											$sql_cons= "SELECT ID_Solicitud AS ID
													   FROM ".$tbl_soli."
													   WHERE ".$tbl_soli.".".$RFC_SOLI."     = '".$rfc."'
														 AND ".$tbl_soli.".".$HCLAVE_SOLI."  ='".$homoclave."'
														 ".$discriminante." ";
														 
											$rs_cons=$db->Execute($sql_cons);
											$Id_solicitud=$rs_cons->fields["ID"];

											$msg="RFC PRESENTE EN LA SOLICITUD [ FOLIO: #".$Id_solicitud." ] ";
										}
						}

						//VERIFICAR SI ES CLIENTE MOROSO
						$ID_SOLI_VERIF = get_solicitud($rfc,$homoclave);
						if(!empty($ID_SOLI_VERIF) && ($ID_SOLI_VERIF > 0) )
						{
							$SOLI_MOROSA = get_solicitud_morosa($ID_SOLI_VERIF);
							if(!empty($SOLI_MOROSA) && ($SOLI_MOROSA > 0) )
								$msg="CLIENTE MOROSO  [ SOLICITUD FOLIO: #".$SOLI_MOROSA." ] ";

						}
						
						//$Notificacion = array("NUM_CLENTE"=>$Num_cliente,"SOLICITUD"=>$Id_solicitud,"MSG"=>$msg);
						echo $msg;
					}
		}//FIN CAPTURA

		if($accion_solicitud == 'EDITAR')
		{
										/***************************************/
										//IDENTIFICAR SI TIENE UNA SOLICITUD EN PROCESO

										$discriminante=(!empty($id_soli))?(" AND ID_Solicitud <> '".$id_soli."' "):("");

										$sql_valida= "SELECT COUNT(*) AS CONT
														   FROM ".$tbl_soli."
														   WHERE ".$tbl_soli.".RFC  = '".$rfc."'
															 AND ".$tbl_soli.".Hclave='".$homoclave."'
															 #AND Status_solicitud <> 'ALTA CLIENTE'
															 ".$discriminante." ";
										$rs_valida=$db->Execute($sql_valida);
										if($rs_valida->fields["CONT"] > '0')
										{
											$sql_cons= "SELECT ID_Solicitud AS ID
													   FROM ".$tbl_soli."
													   WHERE ".$tbl_soli.".RFC  = '".$rfc."'
														 AND ".$tbl_soli.".Hclave='".$homoclave."'
														 ".$discriminante." ";
											$rs_cons=$db->Execute($sql_cons);
											$Id_solicitud=$rs_cons->fields["ID"];

											$msg="RFC PRESENTE EN LA SOLICITUD [ FOLIO: #".$Id_solicitud." ] ";
										}
				echo $msg;
		}
		
}


function valida_rfc_prospecto($RFC_CHECK_PROSPECT,$HCLAVE,$RFC_PROSPECT)
{
 global $db;


	$RFC_FOUR__PROSPECT		=	substr($RFC_PROSPECT,0,-11);
	$RFC_FOUR_CHECK			=	substr($RFC_CHECK_PROSPECT,0,-8);

	$FECHA_PROSPECT			=	substr($RFC_PROSPECT,4,-3);
	$FECHA_CHECK			=	substr($RFC_CHECK_PROSPECT,4);
	
	//$HCVE_PROSPECTO =	substr($RFC_PROSPECT,-3);

	$msg="";
	if( ($RFC_FOUR__PROSPECT == $RFC_FOUR_CHECK)  && ($FECHA_PROSPECT == $FECHA_CHECK) )
			$msg="";
		else
			$msg="VERIFICAR EL RFC DEL PROSPECTO.";
 echo $msg;
}

function get_edad_cte( $FECHA_NACIMIENTO )
{
	$FECHA_NACIMIENTO = gfecha($FECHA_NACIMIENTO);
    list($Y,$m,$d) = explode("-",$FECHA_NACIMIENTO);
    $Edad_cte = ( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );

    echo "<FONT  size='1' color='black' face='verdana'>".$Edad_cte." AÑOS</FONT>";
}




if(isset($RFC) && !empty($RFC) && isset($HCLAVE) && !empty($HCLAVE) && isset($TIPO_CREDITO) && !empty($TIPO_CREDITO) && isset($ACCION_SOLICITUD) && !empty($ACCION_SOLICITUD) && isset($NUM_CLIENTE) )
{

  $Tabla_solicitud		=	($TIPO_CREDITO < 4)?('solicitud'):('solicitud_pmoral');
  $Tabla_clientes_datos	=	($TIPO_CREDITO < 4)?('clientes_datos'):('clientes_datos_pmoral');

 valida_rfc_solicitante($RFC,$HCLAVE,$Tabla_solicitud,$Tabla_clientes_datos,$ID_SOLICITUD,$ACCION_SOLICITUD,$NUM_CLIENTE);
}

if(isset($RFC_CHECK_PROSPECT) && !empty($RFC_CHECK_PROSPECT) && isset($HCLAVE) && !empty($HCLAVE) && isset($RFC_PROSPECT) && !empty($RFC_PROSPECT) )
 valida_rfc_prospecto($RFC_CHECK_PROSPECT,$HCLAVE,$RFC_PROSPECT);


if(isset($CALC_FECHA_NAC) && !empty($CALC_FECHA_NAC) && isset($FECHA_NACIMIENTO) && !empty($FECHA_NACIMIENTO))
 get_edad_cte($FECHA_NACIMIENTO);



?>
