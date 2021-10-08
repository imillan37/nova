<?php
global $HTTP_SESSION_VARS;
$sys_path = "/novacredit/s2credit/";
if(!isset($DOCUMENT_ROOT)) {
    $DOCUMENT_ROOT = getcwd();
}
if( (!empty($_POST['no_session_start'])) or  (!empty($_GET['no_session_start']))  ) 
	{
	 	 $_GET['no_session_start'] =NULL;
	 	 $_POST['no_session_start']=NULL;
	 	 unset($no_session_start);
	 	 unset($DB_EMP);
		 unset($login);
        
	}
if( (!empty($_POST['noSessionValidation'])) or  (!empty($_GET['noSessionValidation']))  ) 
	{	 	
	 	$_GET['noSessionValidation'] =NULL;
	 	$_POST['noSessionValidation']=NULL;
	 	unset($noSessionValidation);
	 	unset($DB_EMP);
		unset($login);		
	} 

	$sys_upload_path                = $DOCUMENT_ROOT.$sys_path."tmp/";
	$style_path                     = $sys_path."lib/css/";
	$frm_calendar_path              = $sys_path."lib/class/";
	$class_img_path                 = $sys_path."lib/class/img/";
	$class_xls_path                 = $sys_path."lib/excell/viewexcell.php";
	$img_path                       = $sys_path."images/";
	$error_log_path                 = $sys_path."error_log/";
	$rico_path                      = $sys_path."lib/class/";
	$sys_path_src = $sys_path;
	$ado_path                       = $DOCUMENT_ROOT.$sys_path_src."lib/ADO/";
	$ado_sesion_path                = $ado_path."session/";
	$class_path                     = $DOCUMENT_ROOT.$sys_path_src."lib/class/";
	$lib_calendar_path              = $DOCUMENT_ROOT.$sys_path_src."lib/calendar/";
	$error_log_path                 = $DOCUMENT_ROOT.$sys_path."error_log/";
	$mailer_path                    = $DOCUMENT_ROOT.$sys_path_src."lib/phpmailer/";
	$photo_path                     = $sys_path."photos/";
	$docs_jpg                       = "../sucursal/promocion/solicitudes/upload/";
	$docs_indiv_jpg					        = "../sucursal/promocion/solicitudes_prev/solicitudes/upload/";
	$docs_GS_jpg                    = "../sucursal/promocion/solicitudes_gs_prev/solicitudes/upload/";
	$default_respaldos              = $sys_path ."sys/admon";
	$shared_scripts                 = $sys_path ."compartidos/";
	$script_coacreditados           = "../coacreditado/";
	$script_obligados               = "../obligados/";
	$script_socios                  = "../socios/";
	$script_obligados_credit        = "../promocion/obligados/";
	$respaldos                      = $DOCUMENT_ROOT. $sys_path_src . "sys/admon";
	$photofile_path                 = $DOCUMENT_ROOT.$photo_path;
	$rfcscript                      = $sys_path . "lib/class/rfclib.js";
	$tabscript                      = $sys_path . "lib/class/";
	$tabstyle                       = $sys_path."lib/css/";
	$class_path_web                 = $sys_path."lib/class/";
	$js_path                        = $sys_path."lib/js/";
	$tinymce_url                    = $sys_path."lib/tiny_mce/";
	$dompdf_path                    = $DOCUMENT_ROOT.$sys_path."lib/dompdf/";
	$jqgrid_url                     = $sys_path."lib/TNewGrid/";
	$jqgrid_path                    = $DOCUMENT_ROOT.$sys_path."lib/TNewGrid/";	
/**Datos conexion al servidor */
 if(!file_exists($DOCUMENT_ROOT.$sys_path.'rdbms.php'))
 	die("No se encontro la libreria : ".$DOCUMENT_ROOT.$sys_path_src.'rdbms.php'.". Podria deberse a un problema de permisos. <br>");

	require_once($DOCUMENT_ROOT.$sys_path_src.'rdbms.php');
//----------------------------------------------------------------------------------
//ADODB
if (!file_exists($ado_path."adodb.inc.php"))
	die( "No se encontró la librería : ".$ado_path."adodb.inc.php".". Podría deberse a un problema de permisos.<BR>");

//echo $ado_path.'adodb.inc.php';	
$ADODB_SESSION_DRIVER='mysql';
$ADODB_SESSION_CONNECT=IP;
$ADODB_SESSION_DB = ADODB_SESS_DATABASE_NAME;

require($ado_path."adodb.inc.php");

if(!isset($no_session_start)){
	
	//require($ado_sesion_path.'adodb-session.php');
	
	include_once($ado_sesion_path.'adodb-session2.php');
	if (empty($nologin)) {
		
		session_start();
	}
	if(!isset($_SESSION['DB_EMP']) && !isset($login) && empty($noSessionValidation)){
		
		$sec	=	5;
		$url	=	$sys_path.'index.php';
			echo "<HTML><HEAD><meta http-equiv=\"Refresh\" content=\"".$sec."; url=".$url."\">";
			echo "<link href='".$style_path."sistema.css' rel='stylesheet' type='text/css'>\n";
			echo "</H3></HEAD>";
			echo "<BODY> <BR><BR><BR><BR>";
			error_msg("El tiempo de la sesion ha concluido, ---deberá reingresar al sistema de nuevo.");
			echo '<script>setTimeout("parent.location.href=\''.$url.'\'",1500)</script>';	
			echo "</BODY> ";
			echo "</HTML>";
			session_unset();
			session_destroy();
			die();

	}
}#end if no_session start


