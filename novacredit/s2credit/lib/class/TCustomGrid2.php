<?
global $class_path;
require($class_path."TGrid2.php");


class TCustomGrid
{
	var $titles 	= array();
	var $colalign	= array();
	var $db;
    

	//Estado
	var $pkey;

	var $oGrid;
	var $script;
	var $style="
		<STYLE TYPE='text/css'>
			#big
			{
				FONT-SIZE: 16;
				FONT-STYLE: normal;
				FONT-FAMILY:  Verdana,Geneva,Tahoma, Arial, Helvetica, sans-serif;
			}
			#medium
			{
				FONT-SIZE: 14;
				FONT-STYLE: normal;
				FONT-FAMILY:  Verdana,Geneva,Tahoma, Arial, Helvetica, sans-serif;
				font-weight: bold;
			}
			#small
			{
				FONT-SIZE: 12;
				FONT-STYLE: normal;
				FONT-FAMILY:  Verdana,Geneva,Tahoma, Arial, Helvetica, sans-serif;
				color: '#000000';
			}
			#mini
			{
				FONT-SIZE: 10;
				FONT-STYLE: normal;
				FONT-FAMILY:  Verdana,Geneva,Tahoma, Arial, Helvetica, sans-serif;
				color: '#000000';
			}			
		</STYLE>\n";

	//-------------------------------------------------------------------------
	//	Constructor
	//-------------------------------------------------------------------------
	function TCustomGrid($table,$fields,$titles,$intersec,$colalign,$order,$condicion,$db,$pkey)
	{
		global $_s1;
		global $_s2;
		global $_s3;
		global $addkey;
		global $class_img_path;
		global $_clasificacion1;
//echo $titles;
 /*        echo "tabla=".$table."<br>";
         echo "campos=".$fields."<br>";
         echo "titulos=".$titles."<br>";
	     echo "filtro=".$filter."<br>";
         echo "alineamiento=".$colalign."<br>";
         echo "base=".$db."<br>";
         echo "liga=".$lr."<br>";
         echo "ar=".$ar."<br>";
         echo "ob=".$ob."<br>";
         echo "pkey=".$pkey."<br>";*/
         
 /*           $this->lr=$lr;
			$this->ar=$ar;
			$this->ob=$ob;
			$this->pkey = $pkey;

			$this->db=$db;

			$this->fields	= $fields;
			$this->titles 	= $titles;
			$this->colalign	= $colalign;
			$this->table 	= $table;*/

			$this->oGrid = new TGrid($db,$table,$fields,$titles,$intersec,$colalign,$order,$condicion,$pkey);  //Constructor


				// Configuraci�n General
				

				$this->oGrid->SetAlign('CENTER');			//Alineaci�n general
				$this->oGrid->SetColumnAlign($colalign);  //Alineaci�n de las columnas
				$this->oGrid->SetBorder('0');				//Bordes internos
				$this->oGrid->SetWidth('90%');			//Ancho  general
				$this->oGrid->SetDetail($pkey,'30%'); 	//LLave primaria, Ancho de la columna de detalle,
				$this->oGrid->SetRowsPerPage(10);			//Renglones por p�gina

				//Eventos

				//$this->oGrid->OnMouseOver("this.style.backgroundColor='aqua'; this.style.cursor='hand'; ");
				$this->oGrid->OnMouseOut ("this.style.backgroundColor=''");

				//Estilo

				$this->oGrid->SetStyle('small');								//Estilo general
				$this->oGrid->SwitchColors("aliceblue","lightsteelblue");		//Colores del contenido
				$this->oGrid->SetTitleColor("lightgrey");						//Color Titu�o
				$this->oGrid->SetLastRowColor("deepskyblue");
				$this->oGrid->SetExtBorder(2);								//Borde externo
				
				
				$this->oGrid->SetCellSP("0");				
				$this->oGrid->SetCellPG("0");
				


				$this->oGrid->SetEditMode(true);
				$this->oGrid->SetDelMode(true);
				$this->oGrid->SetAddMode(true);


				$this->oGrid->SetEditImg($class_img_path."edit.png","Editar este registro.");
				$this->oGrid->SetDelImg($class_img_path."del.png","Borrar este registro.");
				$this->oGrid->SetAddImg($class_img_path."add.png","Agregar nuevo registro.");
//				$this->oGrid->SetSerchImg($class_img_path."serch.png",$class_img_path."noserch.png","Establecer filtro.");
				$this->oGrid->SetOrderImg($class_img_path."desc_order.gif",$class_img_path."asc_order.gif","Ordenar en forma descendente.","Ordenar en forma ascendente.");
				$this->oGrid->SetSerchClasificacionImg($class_img_path."clasificacion.png",$class_img_path."no_clasificacion.png","Buscar por clasificaci�n.");


				/**/
//				$this->oGrid->SetFirstImg($class_img_path."arrow_first-blue.gif","Primera p�gina.");
//				$this->oGrid->SetBackwardImg($class_img_path."arrow_left-blue.gif","Retroceder 10 p�ginas.");
//				$this->oGrid->SetForwardImg($class_img_path."arrow_right-blue.gif","Avanzar 10 p�ginas.");
//				$this->oGrid->SetLastImg($class_img_path."arrow_last-blue.gif","�ltima p�gina.");
//				$this->oGrid->SetCloseDtlImg($class_img_path."close_detail.png","Cerrar detalle");

	}

	function SetName($txt)
	{
		$this->oGrid->SetName($txt);
	}

	function SetParameters($aVars, $aValues)
	{
		$this->oGrid->SetParameters($aVars, $aValues);
	}

	function SetTitle($cTit,$cId)
	{
		$this->oGrid->SetTitle($cTit,$cId);
	}

	function GetScript()
	{
		//$this->script = $this->style;
		$this->script .= $this->oGrid->GetScript();

		return($this->script);
	}

}

?>