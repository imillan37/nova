<?
/****************************************/
/*Fecha: 13/Septiembre/2011
/*Autor: Tonathiu Cárdenas
/*Descripción: LLENA LOS COMBOS ASOCIADOS A UN DETERMINADO CÓDIGO POSTAL
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


if(isset($Codigo_Postal) && !empty($Codigo_Postal) && isset($Campo) && !empty($Campo) && ($Campo == 'CODIGO_POSTAL' ) )
{
	 $Sql_cp="
				 SELECT  COUNT(CP) AS CONT
					   FROM    codigos_postales
					   WHERE   cp = '".$Codigo_Postal."' ";
	 $rs_cp= $db->Execute($Sql_cp);

     $Bandera =($rs_cp->fields["CONT"] == 0)?("FALSE"):("TRUE");
     


		$json=new Services_JSON();
		$json_cad=$json->encode($Bandera);
	echo $json_cad;
}

if(isset($Codigo_Postal) && !empty($Codigo_Postal) && isset($Campo) && !empty($Campo) && ($Campo == 'COLONIA' ) )
{
	 $Sql_col="
				 SELECT  colonia AS COL
					   FROM    codigos_postales
					   WHERE   cp = '".$Codigo_Postal."' ";
	 $rs_col= $db->Execute($Sql_col);

     $Arr_col=array();
     
	  while(! $rs_col->EOF )
	   {
		 $Arr_col[]=utf8_encode($rs_col->fields["COL"]);
		
		$rs_col->MoveNext();
		}

		$json=new Services_JSON();
		$json_cad=$json->encode($Arr_col);
	echo $json_cad;
}

if(isset($Codigo_Postal) && !empty($Codigo_Postal) && isset($Campo) && !empty($Campo) && ($Campo == 'ESTADO' ) )
{
	 $Sql_edo="
			  SELECT  nombre AS EDO
			 FROM   estados
			 WHERE (rango1 <= '".$Codigo_Postal."' and rango2 >= '".$Codigo_Postal."' )  ";
			 
	 $rs_edo= $db->Execute($Sql_edo);

     $Arr_edo=array();
     
	  while(! $rs_edo->EOF )
	   {
		 $Arr_edo[]=utf8_encode($rs_edo->fields["EDO"]);
		
		$rs_edo->MoveNext();
		}

		$json=new Services_JSON();
		$json_cad=$json->encode($Arr_edo);
	echo $json_cad;
}

if(isset($Codigo_Postal) && !empty($Codigo_Postal) && isset($Campo) && !empty($Campo) && ($Campo == 'CIUDAD' ) )
{
	 $Sql_ciud="
			SELECT   nombre AS CIUD
					FROM   ciudades
				WHERE   (rango1 <= '".$Codigo_Postal."' and rango2 >= '".$Codigo_Postal."' )
					 or      (rango3 <= '".$Codigo_Postal."' and rango4 >= '".$Codigo_Postal."' )  ";
			 
	 $rs_ciud= $db->Execute($Sql_ciud);

     $Arr_ciud=array();
     
	  while(! $rs_ciud->EOF )
	   {
		 $Arr_ciud[]=utf8_encode($rs_ciud->fields["CIUD"]);
		
		$rs_ciud->MoveNext();
		}

		$json=new Services_JSON();
		$json_cad=$json->encode($Arr_ciud);
	echo $json_cad;
}

if(isset($Codigo_Postal) && !empty($Codigo_Postal) && isset($Campo) && !empty($Campo) && ($Campo == 'MUNICIPIO' ) )
{
	 $Sql_mun="
					SELECT  nombre AS MUN
			 FROM            municipios
			 WHERE
				(rango1 <= '".$Codigo_Postal."' and rango2 >= '".$Codigo_Postal."' )
				 or
				(rango3 <= '".$Codigo_Postal."' and rango4 >= '".$Codigo_Postal."' )
				 or
				(rango5 <= '".$Codigo_Postal."' and rango6 >= '".$Codigo_Postal."' )
				 or
				(rango7 <= '".$Codigo_Postal."' and rango8 >= '".$Codigo_Postal."' ) ";
			 
	 $rs_mun= $db->Execute($Sql_mun);

     $Arr_mun=array();
     
	  while(! $rs_mun->EOF )
	   {
		 $Arr_mun[]=utf8_encode($rs_mun->fields["MUN"]);
		
		$rs_mun->MoveNext();
		}

		$json=new Services_JSON();
		$json_cad=$json->encode($Arr_mun);
	echo $json_cad;
}


if(isset($SEARCH_CP) && !empty($SEARCH_CP)  && isset($CAMPO_CP_SOLI) && !empty($CAMPO_CP_SOLI) )
{
	$SQL_EDO = "SELECT
					ID_Estado	AS ID,
					Nombre		AS NMB
					FROM   estados
					ORDER BY Nombre ";
    $rs_edo=$db->Execute($SQL_EDO);

    $combo_edo = "<SELECT ID='find_edo' >";
	$combo_edo.= "<OPTION VALUE='' SELECTED  >SELECCIONAR OPCIÓN</OPTION> \n";
	$combo_edo.= "<OPTION VALUE='' DISABLED>-------------------------------------------------------</OPTION>";

while(! $rs_edo->EOF )
{
        $combo_edo.= "<OPTION VALUE='".$rs_edo->fields[0]."' >".$rs_edo->fields[1]." </OPTION>";
        $rs_edo->MoveNext();
}

     $combo_edo. "</SELECT>\n";

    $combo_ciudad = "<SELECT ID='find_ciudad' 		STYLE='width:20px;'>";
	$combo_ciudad. "</SELECT>\n";

    $combo_poblacion = "<SELECT ID='find_poblacion' STYLE='width:20px;' >";
	$combo_poblacion. "</SELECT>\n";

    $combo_colonia   = "<SELECT ID='find_colonia' STYLE='width:20px;' >";
	$combo_colonia  . "</SELECT>\n";
	
	$html="
			<INPUT TYPE='HIDDEN' ID='CAMPO_ASOCIADO_CP' VALUE='".$CAMPO_CP_SOLI."' />
			<TABLE WIDTH='100%' CELLPADDING='3' CELLSPACING='2' BORDER='0px'>
				<TR BGCOLOR='#E7EEF6'>
					<TH STYLE='TEXT-ALIGN:RIGHT;'>ESTÁDO DE LA REPÚBLICA:</TH>
					<TD STYLE='TEXT-ALIGN:LEFT;'>
						".$combo_edo."
					</TD>
				</TR>
				<TR>
					<TH STYLE='TEXT-ALIGN:RIGHT;'>CIUDAD <LABEL STYLE='FONT-SIZE:XX-SMALL'>(SÓLO SI ES EL CASO)<LABEL/>:</TH>
					<TD STYLE='TEXT-ALIGN:LEFT;' >
						".$combo_ciudad."
					</TD>
				</TR>
				<TR	BGCOLOR='#E7EEF6'>
					<TH STYLE='TEXT-ALIGN:RIGHT;'>DELEGACIÓN / MUNICIPIO / POBLACIÓN:</TH>
					<TD STYLE='TEXT-ALIGN:LEFT;' >
						".$combo_poblacion."
					</TD>
				</TR>
				<TR>
					<TH STYLE='TEXT-ALIGN:RIGHT;'>COLONIA:</TH>
					<TD STYLE='TEXT-ALIGN:LEFT;' >
						".$combo_colonia."
					</TD>
				</TR>
				<TR	BGCOLOR='#E7EEF6'>
					<TH Colspan='2' STYLE='TEXT-ALIGN:CENTER;'><DIV ID='CP_SEARCHING' ></DIV></TH>
				</TR>
			</TABLE>";

echo $html;
			
}


if(isset($SEARCH_CIUDAD) && !empty($SEARCH_CIUDAD) && isset($EDO_SOLI) && !empty($EDO_SOLI))
{
	
$SQL_CONS = "SELECT
				ID_Ciudad	AS ID,
				Nombre		AS NMB
        FROM ciudades
        WHERE  ID_Ciudad != 0
			AND ID_Estado = '".$EDO_SOLI."'
        ORDER BY Nombre ";    

$rs_ciudad=$db->Execute($SQL_CONS);



    $Arr_ciudad=array();
     
	  while(! $rs_ciudad->EOF )
	   {
		 $Arr_ciudad[$rs_ciudad->fields["ID"]]=utf8_encode($rs_ciudad->fields["NMB"]);
		
		$rs_ciudad->MoveNext();
		}

		$json=new Services_JSON();
		$json_cad=$json->encode($Arr_ciudad);
	echo $json_cad;
}

if(isset($SEARCH_POBLACION) && !empty($SEARCH_POBLACION) && isset($EDO_SOLI) && !empty($EDO_SOLI))
{


$SQL_CONS = "SELECT
				ID_Municipio	AS ID,
				nombre			AS NMB
        FROM municipios
        WHERE ID_Estado = '".$EDO_SOLI."'
        ORDER BY Nombre "; 

$rs_mun=$db->Execute($SQL_CONS);

    $Arr_mun=array();
     
	  while(! $rs_mun->EOF )
	   {
		 $Arr_mun[$rs_mun->fields["ID"]]=utf8_encode($rs_mun->fields["NMB"]);
		
		$rs_mun->MoveNext();
		}

		$json=new Services_JSON();
		$json_cad=$json->encode($Arr_mun);
	echo $json_cad;
}

if(isset($SEARCH_COLONIA) && !empty($SEARCH_COLONIA) && isset($EDO_SOLI) && !empty($EDO_SOLI) && isset($POBL_SOLI) && !empty($POBL_SOLI))
{
$SQL_CONS = "SELECT
				ID_Colonia		AS ID,
				Colonia			AS COL
        FROM codigos_postales
        WHERE   ID_Estado='".$EDO_SOLI."' and                 
                ID_Municipio='".$POBL_SOLI."'
         ORDER BY Colonia ";
        
$rs_colonia=$db->Execute($SQL_CONS); 

    $Arr_colonia=array();
     
	  while(! $rs_colonia->EOF )
	   {
		 $Arr_colonia[$rs_colonia->fields["ID"]]=utf8_encode($rs_colonia->fields["COL"]);
		
		$rs_colonia->MoveNext();
		}

		$json=new Services_JSON();
		$json_cad=$json->encode($Arr_colonia);
	echo $json_cad;
}

if(isset($SEARCH_CPOSTAL) && !empty($SEARCH_CPOSTAL) && isset($EDO_SOLI) && !empty($EDO_SOLI) && isset($POBL_SOLI) && !empty($POBL_SOLI)	&& isset($COL_SOLI) && !empty($COL_SOLI))
{
	
$SQL_CONS = "SELECT
					CP		AS FOUND
        FROM codigos_postales
        WHERE ID_Estado    = '".$EDO_SOLI."' and 
              ID_Municipio = '".$POBL_SOLI."'  and
              ID_Colonia   = '".$COL_SOLI."' 
         ORDER BY Colonia ";
$rs_cp=$db->Execute($SQL_CONS);

echo "<BUTTON ID='FOUND_CP' TYPE='BUTTON' VALUE='".$rs_cp->fields['FOUND']."' >".$rs_cp->fields['FOUND']."</BUTTON>"; 

}



?>
