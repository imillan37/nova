<?php
/****************************************/
/*Fecha: 22/Junio/2012
/*Autor: Tonathiu Cárdenas
/*Descripción: NOMBRE DEL CLIENTE
/*Dependencias: panel_control_configuracion.php
/****************************************/

$exit = 0;
$noheader =1;
include($DOCUMENT_ROOT."/rutas.php");			//CORE CONSTANTES S2CREDIT

//Inicio conexión
$db = ADONewConnection(SERVIDOR);
$db->Connect(IP,USER,PASSWORD,NUCLEO);
//Fin Conexión

/*********FUNCTIONS**************/
/********************************/

if(isset($GET_PARAMETROS) && !empty($GET_PARAMETROS) && isset($ID_TIPO_CREDIT) && !empty($ID_TIPO_CREDIT) )
{

	  $SQL_PARAM="SELECT
							cat_tipo_credito_parametros.ID_Parametro					AS ID_PARAM,
							cat_tipo_credito_parametros.Descripcion						AS DESCP,
							cat_tipo_credito_parametros.Script_asociado					AS SCRIPT,
							cat_tipo_credito_parametros_secciones.Descripcion			AS SECCION,
							cat_tipo_credito_parametros_secciones.ID_Seccion			AS ID_SECC
					FROM
						cat_tipo_credito_parametros
					INNER JOIN	cat_tipo_credito_parametros_secciones	ON cat_tipo_credito_parametros.ID_Seccion = cat_tipo_credito_parametros_secciones.ID_Seccion
																			AND cat_tipo_credito_parametros_secciones.ID_Tipocredito = '".$ID_TIPO_CREDIT."'
					WHERE 
							cat_tipo_credito_parametros.ID_Tipocredito = '".$ID_TIPO_CREDIT."'
						AND cat_tipo_credito_parametros.Status = 'Activo'
					ORDER BY cat_tipo_credito_parametros.ID_Seccion ";
		$rs_param	= $db->Execute($SQL_PARAM);

		$str_vinculos ="<INPUT TYPE='HIDDEN'  ID='ID_Tipocredito'  VALUE='".$ID_TIPO_CREDIT."'   />
						 <DIV CLASS='section' ID='".$rs_param->fields["ID_SECC"]."' >".$rs_param->fields["SECCION"]."</DIV> <UL>";

		$ID_SECC = $rs_param->fields["ID_SECC"];
		
		while(! $rs_param->EOF )
	     {
			
			$str_vinculos.=  ( $ID_SECC != $rs_param->fields["ID_SECC"] )?("</UL> <DIV CLASS='section' ID='".$rs_param->fields["ID_SECC"]."' >".$rs_param->fields["SECCION"]."</DIV> <UL>"):("");
			$Cont 		  =  ( $ID_SECC != $rs_param->fields["ID_SECC"] )?(1):($Cont + 1);
			
			$ID_SECC 	  = $rs_param->fields["ID_SECC"];
			$str_vinculos.="
							<LI CLASS='PARAM_DTL' LANG='".$rs_param->fields["SCRIPT"]."' >
								".$rs_param->fields["DESCP"]."
							</LI>";
			
		   $rs_param->MoveNext();
		   
		 }

	$str_vinculos .="";

	echo $str_vinculos;
}
?>
