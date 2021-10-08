<?
/****************************************/
/*Fecha: 30/Noviembre/2011
/*Autor: Tonathiu Cárdenas
/*Descripción: GENERA LA TABLA DE SOLICITUDES
/*Dependencias: originacion_credito.php
/****************************************/

$exit = 0;
$noheader =1;
include($DOCUMENT_ROOT."/rutas.php");							//CORE CONSTANTES S2CREDIT
require($class_path."lib_nuevo_credito.php");					//LIBRERÍA ENRIQUE OBJETO TCUENTA
require($class_path."promocion/lib_procesos_alertas.php");		//OBJETO ALERTAS

//Inicio conexión
$db = ADONewConnection(SERVIDOR);
$db->Connect(IP,USER,PASSWORD,NUCLEO);
//Fin Conexión

/*********FUNCTIONS**************/
function get_estatus_valida($ID_SOLICITUD,$ID_Tipo_regimen,$VALIDATE)
{
	global $db;
				  $SQL_CONS="SELECT
									COUNT(ID_Solicitud_Valida) AS CUANTOS
								FROM	solicitud_valida_procesos
								WHERE
										ID_Solicitud 		= '".$ID_SOLICITUD."'
									AND ID_Tipo_regimen		= '".$ID_Tipo_regimen."'
									AND Validacion			= '".$VALIDATE."' ";
				  $rs_cons=$db->Execute($SQL_CONS);

	return $rs_cons->fields["CUANTOS"];

}

function get_tipo_credito($ID_Tipo_regimen)
{
	global $db;

				  $SQL_CONS="SELECT
										ID_Tipocredito AS TIPO_CREDIT
								FROM
										cat_tipo_credito_regimen
								WHERE
										ID_Tipo_regimen ='".$ID_Tipo_regimen."' ";
				  $rs_cons=$db->Execute($SQL_CONS);
	
	return $rs_cons->fields["TIPO_CREDIT"];
}

function get_estatus_solicitud($ID_SOLICITUD,$ID_Tipo_regimen)
{
	global $db;
	$TP_CREDIT		= get_tipo_credito($ID_Tipo_regimen);
	$TBL_DESTINO	=	($TP_CREDIT < 4)?('solicitud'):('solicitud_pmoral');

				  $SQL_CONS="SELECT
									Status		 AS STAT
								FROM	".$TBL_DESTINO."
								WHERE
										ID_Solicitud 		= '".$ID_SOLICITUD."'
									AND ID_Tipo_regimen		= '".$ID_Tipo_regimen."'";
				  $rs_cons=$db->Execute($SQL_CONS);

	return $rs_cons->fields["STAT"];
	
}
/*******************************/


if(isset($MODULO_VALIDATE) && !empty($MODULO_VALIDATE) && isset($ID_SOLICITUD) && !empty($ID_SOLICITUD)  && isset($ID_Tipo_regimen) && !empty($ID_Tipo_regimen) )
{
	$MSG_ACTION="";
	
	switch($MODULO_VALIDATE)
	{
		case'CHECK_LIST':
				$STATUS =	get_estatus_valida($ID_SOLICITUD,$ID_Tipo_regimen,'CHECK LIST');
				if($STATUS == 0)
				{
				  $SQL_INS="INSERT INTO solicitud_valida_procesos (ID_Solicitud,ID_Tipo_regimen,Fecha,ID_User,Validacion,Observaciones) VALUES ('".$ID_SOLICITUD."','".$ID_Tipo_regimen."',NOW(),'".$ID_USR."','CHECK LIST','".$OBSERVACION."') ";
				  $rs_ins=$db->Execute($SQL_INS);

				  $MSG_ACTION="<BR /><IMG  BORDER=0 SRC='".$img_path."tick-circle-frame.png'  ALT='editando'  STYLE='height:20px; width:20px; vertical-align:middle;'  />&nbsp;&nbsp;<FONT SIZE='2' COLOR='BLACK'><B>EL CHECK LIST A LA SOLICITUD SE ENCUENTRA COMPLETO.</B></FONT>";

					 /**************ALERTAS ****************/
					 $TP_CREDIT		= get_tipo_credito($ID_Tipo_regimen);
					 $Genera_alertas = new  TNuevaAlerta ($ID_SOLICITUD,$TP_CREDIT,$ID_Tipo_regimen,'CHECK LIST','',$db,$ID_SUC,$ID_USR);
					 $Genera_alertas->set_notifica_proceso('EMAIL','CHECK LIST - NUM. CLIENTE','COMPLETO');
					 /*************************************/
				  
				}
				else
				  $MSG_ACTION="<BR /><IMG  BORDER=0 SRC='".$img_path."exclamation-diamond-frame.png'  ALT='editando'  STYLE='height:20px; width:20px; vertical-align:middle;'  />&nbsp;&nbsp;<FONT SIZE='2' COLOR='BLUE'><B>LA SOLICITUD  YA CUENTA CON SU CHECK LIST ASOCIADO.</B></FONT>";
		break;
		case'INACTIVAR':
					$STAT_SOLI 		= get_estatus_solicitud($ID_SOLICITUD,$ID_Tipo_regimen);

					if($STAT_SOLI == 'Activa' )
					{
							$TP_CREDIT		= get_tipo_credito($ID_Tipo_regimen);
							$TBL_DESTINO	=	($TP_CREDIT < 4)?('solicitud'):('solicitud_pmoral');

							  $SQL_UPD="UPDATE ".$TBL_DESTINO."
											SET Status_solicitud = 'DIGITALIZACION' 
											WHERE
													ID_Solicitud      = '".$ID_SOLICITUD."'
												AND ID_Tipo_regimen   = '".$ID_Tipo_regimen."' ";
							  $db->Execute($SQL_UPD);

							  $SQL_DELETE="DELETE FROM  solicitud_sucesos
												WHERE
													ID_Solicitud      = '".$ID_SOLICITUD."'
													AND Status 		  = 'VALIDADO' ";
							  $db->Execute($SQL_DELETE);

							  $SQL_DELETE="DELETE FROM  solicitud_sucesos
												WHERE
													ID_Solicitud      = '".$ID_SOLICITUD."'
													AND Status 		  = 'COMITE APROBADO' ";
							  $db->Execute($SQL_DELETE);

							  $SQL_DELETE="DELETE FROM  solicitud_sucesos
												WHERE
													ID_Solicitud      = '".$ID_SOLICITUD."'
													AND Status 		  = 'Autoriza crédito' ";
							  $db->Execute($SQL_DELETE);
							  
							$MSG_ACTION="<BR /><IMG  BORDER=0 SRC='".$img_path."exclamation-diamond-frame.png'  ALT='editando'  STYLE='height:20px; width:20px; vertical-align:middle;'  />&nbsp;&nbsp;<FONT SIZE='2' COLOR='BLUE'><B>LA SOLICITUD  SE ENCUENTRA RECHAZADA .</B></FONT>";

					 /**************ALERTAS ****************/
					 $TP_CREDIT		= get_tipo_credito($ID_Tipo_regimen);
					 $Genera_alertas = new  TNuevaAlerta ($ID_SOLICITUD,$TP_CREDIT,$ID_Tipo_regimen,'CHECK LIST','',$db,$ID_SUC,$ID_USR);
					 $Genera_alertas->set_notifica_proceso('EMAIL','CHECK LIST SOLICITUD','RECHAZADO');
					 /*************************************/
					}
					else
						$MSG_ACTION="<BR /><IMG  BORDER=0 SRC='".$img_path."exclamation-diamond-frame.png'  ALT='editando'  STYLE='height:20px; width:20px; vertical-align:middle;'  />&nbsp;&nbsp;<FONT SIZE='2' COLOR='BLUE'><B>LA SOLICITUD  YA SE ENCUENTRA INACTIVA.</B></FONT>";
		break;
		case'VALIDAR_STATUS':
				$STATUS_CHECK 		=	get_estatus_valida($ID_SOLICITUD,$ID_Tipo_regimen,'CHECK LIST');
				$STATUS_SOLI 		=   get_estatus_solicitud($ID_SOLICITUD,$ID_Tipo_regimen);

				if($STATUS_CHECK > 0)
				{
					  $MSG_ACTION="<BR /><IMG  BORDER=0 SRC='".$img_path."exclamation-diamond-frame.png'  ALT='editando'  STYLE='height:20px; width:20px; vertical-align:middle;'  />&nbsp;&nbsp;<FONT SIZE='2' COLOR='BLUE'><B>LA SOLICITUD  YA CUENTA CON SU CHECK LIST ASOCIADO.</B></FONT>";
				}
				elseif($STATUS_SOLI == 'Inactiva' )
				{
						$MSG_ACTION="<BR /><IMG  BORDER=0 SRC='".$img_path."exclamation-diamond-frame.png'  ALT='editando'  STYLE='height:20px; width:20px; vertical-align:middle;'  />&nbsp;&nbsp;<FONT SIZE='2' COLOR='BLUE'><B>LA SOLICITUD  YA SE ENCUENTRA INACTIVA.</B></FONT>";
				}
				else
						$MSG_ACTION="TRUE";
		break;
	}

	echo $MSG_ACTION;
}


?>


