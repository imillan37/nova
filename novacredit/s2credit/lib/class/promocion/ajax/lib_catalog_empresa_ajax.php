<?
/****************************************/
/*Fecha: 13/Septiembre/2011
/*Autor: Tonathiu Cárdenas
/*Descripción: GENERA LOS VALORES PARA LOS CAMPOS DE DETALLE EMPRESA
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

if(isset($Detalle_cmp_empresa) && !empty($Detalle_cmp_empresa) && isset($id_empresa) && !empty($id_empresa) && isset($tipo_campo) && !empty($tipo_campo))
{
  $Sql_select="SELECT
						".$tipo_campo."				AS VAL_CMP
				FROM
					cat_convenio_empresas
				WHERE  ID_empresa ='".$id_empresa."' ";
  $rs_select= $db->Execute($Sql_select);

  echo $rs_select->fields["VAL_CMP"];
}


if(isset($Detalle_cmp_empresa) && !empty($Detalle_cmp_empresa) && isset($id_empresa) && !empty($id_empresa) && isset($promotor_empresa) && !empty($promotor_empresa))
{
  $Sql_select="SELECT
						promotores.Nombre	AS PROMO
				FROM
						cat_convenio_empresas	
					INNER JOIN	promotores ON cat_convenio_empresas.Num_promo = promotores.Num_promo
				WHERE  ID_empresa ='".$id_empresa."' ";
  $rs_select= $db->Execute($Sql_select);

  echo $rs_select->fields["PROMO"];
}
?>
