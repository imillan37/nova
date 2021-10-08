<?
require_once('html2fpdf.php'); 
include_once("../../../../modules/config.php");
include_once("../mysql.class.php");
include_once("../../project/auth.php");


if(!$disableSiteSecurity) security();


@mysql_connect(DB_HOST,DB_LOGIN,DB_PASSWORD)or die('<script>alert("'.mysql_error().'")</script>');
@mysql_select_db(DB_DATABASE)or die('<script>alert("'.mysql_error().'")</script>');


$archivo = "";

if(isset($_GET["efcurso_id"])){
	$efcurso_id = base64_decode($_GET["efcurso_id"]);
}

$query = "SELECT 
			efusuario.efusuario_numEmpleado, 
			efusuario.efusuario_nombre, 
			efusuario.efusuario_apPaterno,
			efusuario.efusuario_apMaterno,
			efcurso.efcurso_nombre
		FROM efcurso 
			INNER JOIN efexamen ON efcurso.efcurso_id = efexamen.efexamen_foreign_id
	 		INNER JOIN eftablon ON eftablon.eftablon_efexamen_id = efexamen.efexamen_id
	 		INNER JOIN efusuario ON eftablon.eftablon_efusuario_id = efusuario.efusuario_id
		WHERE
			efcurso_id = ".$efcurso_id." 
		AND
			efusuario_id = ".$_SESSION["theUser"];
			
$m = new mysql($query);


$CURSO_NAME     = $m->fetchField(0,"efcurso_nombre");
$NUM_EMPLEADO   = $m->fetchField(0,"efusuario_numEmpleado");
$NOMBRE_USUARIO = $m->fetchField(0,"efusuario_nombre")." ".$m->fetchField(0,"efusuario_apPaterno")." ".$m->fetchField(0,"efusuario_apMaterno");

$theFolder = "diploma/";

if(is_dir($theFolder)){
	$handle = opendir($theFolder);
	for(;false !== ($file = readdir($handle));)if($file != "." && $file != "..")if(file_exists($theFolder.$file))if(validType($file))$archivo = $theFolder.$file;
	closedir($handle);
}

$archivoSTR = file_get_contents($archivo);


$archivoSTR = str_replace("%nombre%",$NOMBRE_USUARIO,$archivoSTR);
$archivoSTR = str_replace("%numero%",$NUM_EMPLEADO,$archivoSTR);
$archivoSTR = str_replace("%curso%",$CURSO_NAME,$archivoSTR);
$archivoSTR = str_replace('src="','src="diploma/',$archivoSTR);
$archivoSTR = str_replace('background="','background="diploma/',$archivoSTR);

// Instanciamos la Clase
$pdf = new HTML2FPDF();
$pdf->DisplayPreferences('HideWindowUI');
$pdf->AddPage();
// Le Entregmos la Variable
$pdf->WriteHTML($archivoSTR);
// Mandamos el Fichero
$pdf->Output(''.$CURSO_NAME .'_diploma.pdf','I');


/*function validType($fileName){
	$extentions = array("html","htm","HTML","HTM");
	$e = explode(".",$fileName);
	$e = array_reverse($e);
	return in_array($e[0],$extentions);
}*/
?>