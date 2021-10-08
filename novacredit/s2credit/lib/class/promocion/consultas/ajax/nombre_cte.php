<?php
/****************************************/
/*Fecha: 30/Noviembre/2011
/*Autor: Tonathiu Cárdenas
/*Descripción: NOMBRE DEL CLIENTE
/*Dependencias: interface.js
/****************************************/

$exit = 0;
$noheader =1;
include($DOCUMENT_ROOT."/rutas.php");			//CORE CONSTANTES S2CREDIT

//Inicio conexión
$db = ADONewConnection(SERVIDOR);
$db->Connect(IP,USER,PASSWORD,NUCLEO);
//Fin Conexión

/*********FUNCTIONS**************/
function cliente_nombre($id_soli,$db)
{
	$sql_cons ="SELECT
						CONCAT(Nombre,' ',NombreI,' ',AP_Paterno,' ',AP_Materno) AS CTE
	             FROM solicitud
					WHERE ID_Solicitud ='".$id_soli."' ";
	$rs_cons = $db->Execute($sql_cons);

   return $rs_cons->fields["CTE"];
}


if(isset($id_soli) && !empty($id_soli))
{

   $NMB_CTE = cliente_nombre($id_soli,$db);

	echo $NMB_CTE;
}
?>
