<?

	class TNewGrid {

		var $fields		      = array();
		var $headers 	      = array();
		var $colalign	      = array();
		var $anchos         = array();
		var $botones        = array();
		var $botonesDefault = array();
		var $defaultConf    = array();
		var $query          = '';
		var $db;

		function TNewGrid( $fields, $headers, $mascara, $colalign, $query, $anchos, $botones, $ocultos, $botonesDefault, $defaultConf, $ID_DIV="list2", $PAGINA='pager2' ) {
			$this->fields         = $fields;
			$this->headers        = $headers;
			$this->mascara        = $mascara;
			$this->colalign       = $colalign;
			$this->query          = $query;
			$this->anchos         = $anchos;
			$this->botones        = $botones;
			$this->botonesDefault = $botonesDefault;
			$this->defaultConf    = $defaultConf;
			$this->ID_DIV         = $ID_DIV;
			$this->PAGINA         = $PAGINA;
		}
		function GetScript() {
			global $PHP_SELF;
			global $jqgrid_url;
			      
			
			//$script  = "<link rel='stylesheet' type='text/css' media='screen' href='".$sys_path."lib/class/jqgrid/themes/redmond/jquery-ui.css' />";
			//$script .= "<link rel='stylesheet' type='text/css' media='screen' href='".$sys_path."lib/class/jqgrid/css/ui.jqgrid.css' />";
			//$script .= "<script src='".$sys_path."lib/class/jqgrid/js/jquery-1.3.2.min.js' type='text/javascript'></script>\n";
			//$script .= "<script src='".$sys_path."lib/class/jqgrid/js/i18n/grid.locale-sp.js' type='text/javascript'></script>\n";
			//$script .= "<script src='".$sys_path."lib/class/jqgrid/js/jquery.jqGrid.min.js' type='text/javascript'></script>\n";
			$script .= "<style>\n";
			$script .= " body { text-align: center; } ";
			if( $this->defaultConf["headerHeight"] ) {
				$script .= ".ui-jqgrid-sortable{ height: ".$this->defaultConf["headerHeight"]."px !important; padding-top: 3px !important; } ";
			}
			$script .= " table#".$this->ID_DIV." td { font-size: 10px !important; margin: 0 auto; } ";
			$script .= " div#".$this->PAGINA." { font-size: 5px !important; margin: 0 auto; margin: 0 auto; } ";
			$script .= " th, td { font-size: 12px !important; color: #336699 !important; } ";
			$script .= " div, table {  margin: 0 auto;  } ";
			$script .= " div#".$this->PAGINA." input { height: 17px; font-size: 10px; border: 1px #6699CC solid; } ";
			$script .= " div#".$this->PAGINA." select { height: 17px; font-size: 10px; border: 1px #6699CC solid; } ";
			$script .= ".tblDetalle { background-color: #A6C9E2; width: 100%;   } ";
			$script .= ".tblDetalle td { background-color: #FFFFFF; border-bottom: 1px #6699CC solid; } ";
			$script .= ".tblDetalle th { background-color: #6FA7D1; color: #FFFFFF !important; border-bottom: 1px #FFFFFF solid; } ";
/*
			$script .= ".jqmWindow{	display: block;
													    position: fixed;
													    top: 5%;
													    left: 60%;
													    margin-left: -300px;
													    width: 400px;
													    background-color: transparent;
													    color: #333;
													    padding: 0;
*/
			$script .= ".jqmWindow{	display: block;
													    position: fixed;
													    left: 20%;
													    top: 5%;
													    width: 400px;
													    background-color: transparent;
													    color: #333;
													    padding: 0;
													}
									.jqmOverlay { background-color: #FFF; }
									* html .jqmWindow {
									     position: absolute;
									     top: expression((document.documentElement.scrollTop || document.body.scrollTop) + Math.round(17 * (document.documentElement.offsetHeight || document.body.clientHeight) / 100) + 'px');
									}";
			$script .= "</style>";
			$script .= "<table id='".$this->ID_DIV."'></table><div id='".$this->PAGINA."'></div> ";
			//////////////////////////////////
			$urlFields     = serialize($this->fields);
			$urlFields     = urlencode($urlFields);
			$urlHeaders    = serialize($this->headers);
			$urlHeaders    = urlencode($urlHeaders);
			$urlAnchos     = serialize($this->anchos);
			$urlAnchos     = urlencode($urlAnchos);
			$urlColalign   = serialize($this->colalign);
			$urlColalign   = urlencode($urlColalign);
			$urlMascara    = serialize($this->mascara);
			$urlMascara    = urlencode($urlMascara);
			$urlBotones    = serialize($this->botones);
			$urlBotones    = urlencode($urlBotones);
			$urlBotonesDef = serialize($this->botonesDefault);
			$urlBotonesDef = urlencode($urlBotonesDef);
			$urlQuery      = urlencode($this->query);
			$urlURL        = urlencode($PHP_SELF);
			//////////////////////////////////


			$script .= "<script>";
			$script .= "var _selNewGrid_ = 0;";
			$script .= "jQuery('#".$this->ID_DIV."').jqGrid( { ";
			$script .= "url:'".$jqgrid_url."TNewGridServer.php?q=1', ";
			$script .= "datatype: 'json', ";
			$script .= "colNames:[ ";
			for( $x = 0; $x < sizeof($this->headers); $x++ ) {
				$script .= " '".$this->headers[$x]."' ";
				if( $x < ( sizeof($this->headers) - 1 ) ) {
					$script .= ",";
				}
			}
			$script .= " ], ";
			$script .= "colModel:[";
			for( $x = 0; $x < sizeof($this->fields); $x++ ) { // formatter: 'link'
				$script .= "{ name:'".$this->fields[$x]."', index:'".$this->fields[$x]."', width: '".$this->anchos[$x]."'";
				if( $this->anchos[$x] == 0 ) {
					$script .= " ,hidden:true ";
				} else {
					$script .= " ,hidden:false ";
				}
				switch( $this->colalign[$x]) {
	    		case "L":
	        	$script .= " ,align: 'left' ";
		        break;
	    		case "R":
	      	  $script .= " ,align: 'right' ";
	        	break;
	    		case "C":
	      	  $script .= " ,align: 'center' ";
	      	  break;
			    default:
	    	    $script .= " ,align: 'left' ";
	      	  break;
				}
				if( $this->mascara[$x] == "D" ) {
					$script .= " ,formatter: 'date' "; // http://www.trirand.com/jqgridwiki/doku.php?id=wiki:predefined_formatter
				} else if( $this->mascara[$x] == "N" ) {
					$script .= " ,formatter: 'number' "; // http://www.trirand.com/jqgridwiki/doku.php?id=wiki:predefined_formatter
				} else {
					$script .= " ,formatter: '' ";
				}
				$script .= " ,resizable:true }  ";
				if( $x < ( sizeof($this->fields) - 1 ) ) {
					$script .= ",";
				}
			}
			$script .= " ], ";
			$script .= "rowNum:15, ";

			if( $this->defaultConf["numeracion"] == true ) {
				$script .= "rownumbers: true, ";
			} else {
				$script .= "rownumbers: false, ";
			}
			if( $this->defaultConf["gridOculto"] == true ) {
				$script .= "hiddengrid: true, ";
			} else {
				$script .= "hiddengrid: false, ";
			}
			if( $this->defaultConf["width"] > 0 ) {
				$script .= "width: ".$this->defaultConf["width"].", ";
			}
			if( $this->defaultConf["autowidth"] == true ) {
				$script .= "autowidth: true, ";
			} else {
				$script .= "autowidth: false, ";
			}
			if( $this->defaultConf["rowlist"] ) {
				$script .= "rowList:[".$this->defaultConf["rowlist"]."], ";
			} else {
				$script .= "rowList:[10,20,30,40,50], ";
			}
			$script .= "pager: '#".$this->PAGINA."', ";


			if( $this->defaultConf["sortname"] ) {
				$script .= "sortname: '".$this->defaultConf["sortname"]."',  ";
			} else {
				$script .= "sortname: '".$this->fields[0]."',  ";
			}

			if( $this->defaultConf["sortorder"] ) {
				$script .= "sortorder: '".$this->defaultConf["sortorder"]."', ";
			} else {
				$script .= "sortorder: 'asc', ";
			}

			if( $this->defaultConf["height"] > 0 ) {
				$script .= "height: '".$this->defaultConf["height"]."', ";
			} else {
				$script .= "height: '221', ";
			}
			$script .= "postData:{ ";
			$script .= "query:'".$this->query."',";
			$listaCampos = implode( ",", $this->fields );
			$script .= "fields:'".$listaCampos."'";
			$script .= "}, ";
			$script .= "viewrecords: true, ";
			if( $this->defaultConf["toolbar"] ) {
				$script .= "toolbar : [true,'top'], ";
			}
			$script .= "caption:'".$this->defaultConf["titulo"]."', ";

$script .= <<<EOD
					gridComplete: function() {
									  _selNewGrid_ = 0; 
									                                      
									  		$('td').each(function() {
									  			var isFound = $(this).attr('title').search(/IMG/i);
									  				if(isFound == '0'){
									  						var id = $(this).attr('title');
									  						   $(this).html("<img src='../../images/fail.gif' style='cursor: pointer;'  onclick=\"BorrarRow('"+id+"','$this->ID_DIV');\">");
									  						} // end if(isFound == '0'){ 
									  	      }); // end $('td').each(function() {
			                         }, 
EOD;
		
			if($this->defaultConf["dobleclick"]) {
				$script .= "ondblClickRow: function(id) { ".$this->defaultConf["dobleclick"]."(id); }, ";
			} else {
				$script .= "ondblClickRow: function(id) {
																		_selNewGrid_ = id;
																		document.getElementById('iElementoSeleccionado').value = _selNewGrid_; ";
				if($this->defaultConf["detail"]) {
					$script .= "jQuery('#ex2').jqm({ajax: '".$this->defaultConf["detail"]."?id=' + _selNewGrid_ , trigger: 'a.ex2trigger'}); ";
				} else {
					$script .= "jQuery('#ex2').jqm({ajax: '".$jqgrid_url."TNewGridDetail.php?id=' + _selNewGrid_ + '&fields=".$urlFields."&headers=".$urlHeaders."&anchos=".$urlAnchos."&mascara=".$urlMascara."&colalign=".$urlColalign."&botones=".$urlBotones."&query=".$urlQuery."&url=".$urlURL."&botonesDef=".$urlBotonesDef."', trigger: 'a.ex2trigger'}); ";
				}
				$script .= "$('#ex2').jqmShow(); }, ";
			}
			$script .= "onSelectRow: 	function(id){
																	_selNewGrid_ = id;
																}";
			$script .= "}); ";
			$script .= "jQuery('#t_".$this->ID_DIV."').height(25).hide().jqGrid('filterGrid','".$this->ID_DIV."',{gridModel:true,gridToolbar:true}); ";
			$script .= "jQuery('#".$this->ID_DIV."').jqGrid('navGrid','#".$this->PAGINA."',{ ";

			$script .= "edit:false, ";
			$script .= "add:false,  ";
			$script .= "del:false,  ";
			$script .= "search:false, ";
			$script .= "nav:false, ";
			$script .= "refresh:false  ";
			$script .= "}";
 			$script .= ")";


 			

 			
 			
 			
			if( $this->botonesDefault["Buscar"] == true ) {
			$script .= ".navButtonAdd('#".$this->PAGINA."',{	caption:'Busqueda',
																						title:'BUSQUEDA ACTIVA!',
																						onClickButton:function(){
																							if(jQuery('#t_".$this->ID_DIV."').css('display')=='none') {
																								jQuery('#t_".$this->ID_DIV."').css('display','');
																							} else {
																								jQuery('#t_".$this->ID_DIV."').css('display','none');
																							}
																						}
																					})";
			}
			if( $this->botonesDefault["Eliminar"] == true ) {
				$script .= ".navButtonAdd('#".$this->PAGINA."',{
											title: 'Eliminar el registro seleccionado',
											caption:'',
										  buttonicon:'ui-icon-trash',
										  onClickButton: function(){
											if( _selNewGrid_ == 0 ) {
												alert('Primero seleccione un registro');
											} else {
												jQuery.ajax( { type: 'POST',
																			 url:  '".$PHP_SELF."',
	   																	 data: 'delkey=' + _selNewGrid_,
																			 success: function() { },
																			 error: function()   { },
																			 complete: function(RESPUESTA) {
																			 		eliminarNewGrid(RESPUESTA);
																			 }
																		 } );
											}
										 },
										 position:'first'
										})";
			}
			if( $this->botonesDefault["Editar"] == true ) {
				$script .= ".navButtonAdd('#".$this->PAGINA."',{
											title: 'Editar el registro seleccionado',
											caption:'',
										  buttonicon:'ui-icon-pencil',
										  onClickButton: function(){
											if( _selNewGrid_ == 0 ) {
												alert('Primero seleccione un registro');
											} else {

												jQuery.ajax( { type: 'POST',
																			 url:  '".$PHP_SELF."',
	   																	 data: 'edkey=' + _selNewGrid_,
																			 success: function() { },
																			 error: function()   { },
																			 complete: function(RESPUESTA) {
																			 		edicionNewGrid(RESPUESTA);
																			 }
																		 } );
											}
										 },
										 position:'first'
										})";
			}
			if( $this->botonesDefault["Nuevo"] == true ) {
				$script .= ".navButtonAdd('#".$this->PAGINA."',{
											title: 'Agregar un nuevo registro',
											caption:'',
										  buttonicon:'ui-icon-plus',
										  onClickButton: function() {
												nuevoNewGrid();
										  },
											position:'first'
										})";
			}
			$script .= ";</script>	";
			
			return($script);
		}
	}
?>


