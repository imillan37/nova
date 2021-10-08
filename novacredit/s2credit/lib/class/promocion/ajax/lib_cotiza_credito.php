<?
/****************************************/
/*Fecha: 13/Septiembre/2011
/*Autor: Tonathiu Cárdenas
/*Descripción: GENERA LOS VALORES PARA EL AUTOCOMPLET
/*Dependencias: captura????.php edicion????.php 
/****************************************/

$exit = 0;
$noheader =1;
include($DOCUMENT_ROOT."/rutas.php");			//CORE CONSTANTES S2CREDIT
require($class_path."json.php");   				//LIBRERÍA JSON

//Inicio conexión
$db = ADONewConnection(SERVIDOR);
$db->Connect(IP,USER,PASSWORD,NUCLEO);
//Fin Conexión

if( isset($ID_CREDIT) && !empty($ID_CREDIT) && isset($ID_PROD) && !empty($ID_PROD) && isset($TIPO) && !empty($TIPO) && $TIPO=='PLAZO' )
{
	$Sql_plz="SELECT
				Plazo_Minimo AS PLZ_MIN,
				Plazo_Maximo AS PLZ_MAX
			  FROM
						cat_productosfinancieros
			   WHERE        ID_Producto ='".$ID_PROD."'
						AND ID_Tipocredito ='".$ID_CREDIT."' ";
	
	$rs_plz=$db->Execute($Sql_plz);

	$Options="<OPTION VALUE='' SELECTED>SELECCIONAR UN PLAZO</OPTION>
			  <OPTION VALUE='' DISABLED>-------------------------------------------------------</OPTION>";

	$Plz_min= $rs_plz->fields["PLZ_MIN"];
	$Plz_max= $rs_plz->fields["PLZ_MAX"];
	
	for($Index=$Plz_min;($Index <= $Plz_max);$Index++)
	{
			$Selected =($PLAZO==$Index)?("SELECTED"):("");
			$Options.="<OPTION VALUE='".$Index."' ".$Selected."> ".$Index."</OPTION>";
	}

	echo $Options;

}

?>
