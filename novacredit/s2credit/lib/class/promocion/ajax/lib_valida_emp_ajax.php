<?
/****************************************/
/*Fecha: 07/Octubre/2011
/*Autor: Tonathiu Cárdenas
/*Descripción: GENERA LOS VALIDACIÓN DE LA EMPRESA PARA TIPO CRÉDITO NÓMINA
/*Dependencias: valida_captura.js
/****************************************/

$exit = 0;
$noheader =1;
include($DOCUMENT_ROOT."/rutas.php");			//CORE CONSTANTES S2CREDIT

//Inicio conexión
$db = ADONewConnection(SERVIDOR);
$db->Connect(IP,USER,PASSWORD,NUCLEO);
//Fin Conexión

/***********VALIDAMOS PORCENTAJE DE DESCUENTO EMPRESA**************/

if(isset($ID_Empresa) && !empty($ID_Empresa))
{
	$Sql_cons="SELECT
					Porcentaje_Descuento		AS PRCT_DESC,
					Porcentaje_Descuento_Tipo	AS PRCT_DESC_TP
				FROM cat_convenio_empresas
				WHERE ID_empresa ='".$ID_Empresa."' ";
	$rs_valida=$db->Execute($Sql_cons);

	if(empty($rs_valida->fields["PRCT_DESC"]))
		echo "EMPRESA SIN % DESCUENTO ASIGNADO";

}

if(isset($VALIDA_FECHA_ING) && !empty($VALIDA_FECHA_ING) && isset($FECHA_ING) && !empty($FECHA_ING))
{
	$SQL_CONSTANTE="SELECT
							Valor  AS VALOR
					FROM constantes
					WHERE Nombre = 'MINIMO_MESES_NOMINA' ";
	$rs_constante=$db->Execute($SQL_CONSTANTE);


    $FECHA_ING = gfecha($FECHA_ING);
    $HOY=date("Y-m-d");
    $Antiguedad_soli = ((strtotime($HOY)-strtotime($FECHA_ING))/86400);

    $MESES_DIAS = $rs_constante->fields["VALOR"]*30;


   if($Antiguedad_soli < $MESES_DIAS)
	echo "FALSE";
      else
	echo "TRUE";

}


?>

