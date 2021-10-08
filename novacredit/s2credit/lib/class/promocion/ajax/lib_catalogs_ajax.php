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


if(isset($CAMPO) && !empty($CAMPO) && isset($ID_CREDIT) && !empty($ID_CREDIT) && isset($TIPO) && !empty($TIPO) && ($TIPO=='CATALOGO') )
{
  $Sql_select="SELECT
						cat_tipo_credito_campos.Sql 				AS VAL_SQL,
						cat_tipo_credito_campos.List_cmp_asoc		AS CMP_HIDE
				FROM
					cat_tipo_credito_campos
				WHERE Nombre_campo ='".$CAMPO."'
					AND ID_Tipocredito ='".$ID_CREDIT."' ";
  $rs_select= $db->Execute($Sql_select);

  if (!empty($rs_select->fields["VAL_SQL"]))
  {
	$DISCRIMINANTE=urldecode($DISCRIMINANTE);
	
	$Discriminante =(!empty($DISCRIMINANTE))?(" HAVING DESCP LIKE '%".$DISCRIMINANTE."%' "):("");
    $Sql_cons = $rs_select->fields["VAL_SQL"] . $Discriminante;
    $rs_cons= $db->Execute($Sql_cons);
    $html="<DIV ID='CATALOGO' >
			<TABLE  cellspacing='2' STYLE='border:0px dashed white;' align='center' width='100%'> 
			<TR ALIGN='center' VALIGN='middle' >
               <TD STYLE='text-align:left;' ><B> <FONT size='2' COLOR='black'>Coincidir con :</FONT>
                  <INPUT TYPE='TEXT' STYLE='width:200px; height:15px;' ID='Txt_filtro' VALUE='".$DISCRIMINANTE."'/>

				  <IMG SRC='".$img_path."magnifier-zoom-actual.png' ALT='Filtrar' TITLE='Filtro' STYLE='cursor:pointer;'  CLASS='FILTRO_CATALOG' />
                  <INPUT TYPE='HIDDEN' ID='CAMPO_CAT' VALUE='".$CAMPO."' />
               </TD>
            </TR>
            </TABLE>
            <BR/>
            
             <TABLE  WIDTH='100%' ALIGN='center' BORDER='0px' BGCOLOR='#FFFFFF' CELLSPACING='2' CELLPADDING='2'>";
  
		while(! $rs_cons->EOF )
	   {
		 $html.="<TR ONMOUSEOVER=\"javascript:this.style.backgroundColor='#FBFAAE'; this.style.cursor='hand'; \"
                     ONMOUSEOUT =\"javascript:this.style.backgroundColor='' \" >
					<TH STYLE='text-align:left; color:gray;'>".$rs_cons->fields["DESCP"]."</TH>
					<TD valign='middle'>
						<IMG SRC='".$img_path."plus-circle.png' ALT='".$CAMPO."' TITLE='Asignar opción.' LANG='".$rs_cons->fields["DESCP"]."' STYLE='cursor:pointer;' ID='".$rs_cons->fields["ID"]."_".$rs_select->fields["CMP_HIDE"]."' CLASS='BTN_CATALOG' />
					</TD>
		         </TR>";
		
		$rs_cons->MoveNext();
		}

	 $html.=" </DIV>
	          </TABLE>";
	echo $html;
  }
  else
  {

   echo "<P>NO SE ENCUENTRAN DATOS EN EL CATÁLOGO.</P>";
  }
  

}

if(isset($CAMPO) && !empty($CAMPO) && isset($ID_CREDIT) && !empty($ID_CREDIT) && isset($TIPO) && !empty($TIPO) && ($TIPO=='RESTAURAR') )
{
  $Sql_select="SELECT
						cat_tipo_credito_campos.Sql 				AS VAL_SQL,
						cat_tipo_credito_campos.List_cmp_asoc		AS CMP_HIDE
				FROM
					cat_tipo_credito_campos
				WHERE Nombre_campo ='".$CAMPO."'
					AND ID_Tipocredito ='".$ID_CREDIT."' ";
  $rs_select= $db->Execute($Sql_select);

  echo $rs_select->fields["CMP_HIDE"];
}
?>
