<?
global $class_path;
require($class_path."TGrid.php");


class TCustomGrid
{
	var $fields		= array();
	var $titles 		= array();
	var $colalign	= array();
	var $table 		= '';
	var $db;

	//Estado
	var $lr;
	var $ar;
	var $ob;
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
			#micro
			{
				FONT-SIZE: 8;
				FONT-STYLE: normal;
				FONT-FAMILY:  Verdana,Geneva,Tahoma, Arial, Helvetica, sans-serif;
				color: '#000000';
			}				
		</STYLE>\n";

	//-------------------------------------------------------------------------
	//	Constructor
	//-------------------------------------------------------------------------
	function TCustomGrid($table,$fields,$titles,$filter,$colalign,$db,$lr,$ar,$ob,$pkey)
	{
		global $_s1;
		global $_s2;
		global $_s3;
		global $_rpp;		
		global $addkey;
		global $class_img_path;
		global $_clasificacion1;


			$this->lr=$lr;
			$this->ar=$ar;
			$this->ob=$ob;
			$this->pkey = $pkey;

			$this->db=$db;

			$this->fields	= $fields;
			$this->titles 	= $titles;
			$this->colalign	= $colalign;
			$this->table 	= $table;
			
			
			
			if( empty($lr)  and ($lr != '0'))
			{
				$lr = -1;
				//debug("first time");
			}
			

			$this->oGrid = new TGrid($db,$fields,$table,$titles,$filter,$lr,$ar,$ob,$pkey);  //Constructor


			// Configuración General
				

				$this->oGrid->SetAlign('CENTER');			//Alineación general
				$this->oGrid->SetColumnAlign($colalign);  	//Alineación de las columnas
				$this->oGrid->SetBorder('0');				//Bordes internos
				$this->oGrid->SetWidth('95%');				//Ancho  general
				$this->oGrid->SetDetail($pkey,'30%'); 		//LLave primaria, Ancho de la columna de detalle,
				/*
				if($_rpp>=1 )
					$this->oGrid->SetRowsPerPage($_rpp);
				else*/
					
					
				$ctes=false;	
				$rs=$db->execute("SHOW tables");
				
				if($rs)
				  while(! $rs->EOF)
					{
						if( $rs->fields[0]=='constantes' )
						{
							$ctes=true;
							break;
						}
						
						$rs->MoveNext();
					}
				
				
				
				
				if($ctes)
				{
					$sql = "SELECT Valor FROM constantes WHERE Nombre ='GRID_RENGLONES_POR_PAGINA' ";
					$rs=$db->Execute($sql);
					
					$def_rpp=($rs->fields[0]>0)?($rs->fields[0]):(10);
				}
				else
				  $def_rpp=10;
		
				$this->oGrid->SetRowsPerPage($def_rpp);			//Renglones por página default
				
				
				//$this->oGrid->default_start_page = 'last';
				
				
				

				//Eventos

				//$this->oGrid->OnMouseOver("this.style.backgroundColor='aqua'; this.style.cursor='hand'; ");
				$this->oGrid->OnMouseOut ("this.style.backgroundColor=''");
				//Estilo

				$this->oGrid->SetStyle('small');								//Estilo general
				$this->oGrid->SwitchColors("aliceblue","lightsteelblue");		//Colores del contenido
				$this->oGrid->SetTitleColor("lightgrey");						//Color Tituño
				$this->oGrid->SetLastRowColor("steelblue");
				$this->oGrid->SetExtBorder(2);								//Borde externo
				
				
				$this->oGrid->SetCellSP("1");				
				$this->oGrid->SetCellPG("1");
				


				$this->oGrid->SetEditMode(true);
				$this->oGrid->SetDelMode(true);
				$this->oGrid->SetAddMode(true);


				$this->oGrid->SetEditImg($class_img_path."edit.png","Editar este registro.");
				$this->oGrid->SetDelImg($class_img_path."del.png","Borrar este registro.");
				$this->oGrid->SetAddImg($class_img_path."add.png","Agregar nuevo registro.");
				$this->oGrid->SetSerchImg($class_img_path."serch.png",$class_img_path."noserch.png","Establecer filtro.");
				$this->oGrid->SetOrderImg($class_img_path."desc_order.gif",$class_img_path."asc_order.gif","Ordenar en forma descendente.","Ordenar en forma ascendente.");
				$this->oGrid->SetSerchClasificacionImg($class_img_path."clasificacion.png",$class_img_path."no_clasificacion.png","Buscar por clasificación.");


				/**/
				$this->oGrid->SetFirstImg($class_img_path."arrow_first-blue.gif","Primera página.");
				$this->oGrid->SetBackwardImg($class_img_path."arrow_left-blue.gif","Retroceder 10 páginas.");
				$this->oGrid->SetForwardImg($class_img_path."arrow_right-blue.gif","Avanzar 10 páginas.");
				$this->oGrid->SetLastImg($class_img_path."arrow_last-blue.gif","Última página.");
				$this->oGrid->SetCloseDtlImg($class_img_path."close_detail.png","Cerrar detalle");

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
		$this->script = $this->style;
		$this->script .= $this->oGrid->GetScript();
		return($this->script);
	}

}

?>