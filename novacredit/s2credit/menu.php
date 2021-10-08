<?php
/*
Israel Millan 
s2credit V2.0
para empresa novacredit
actualizacion de jquery MENU
08/09/2021
*/
$noheader=1;
$noSessionValidation=1;
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];

if(file_exists($DOCUMENT_ROOT.'/rutas.php')){
   include($DOCUMENT_ROOT.'/rutas.php');
}else{
    echo "<h1>Revisar la configuracion de el sistema no se puede cargar en este momento</h1>";
}

if(empty($_SESSION['ID_USR'])) die();
$odb = ADONewConnection(SERVIDOR);	# create a connection
$odb->Connect(IP,USER,PASSWORD,NUCLEO);
$SELECTED_MENU="";
$MuestraMenusLigados=true;
/**
 * Crea el menu desde un arreglo
 * 
 */
 function CreateMenuFromArray($aryOpciones)
 {


		global $_COOKIE,$MENU_OPCION_SINPERMISO,$SELECTED_MENU;
		global $sys_path;

		$html="";
		$bL=chr(10);
		$tab="\t\t";
		$selected_link=$SELECTED_MENU;
		$selected_link=str_replace("a_", "",$selected_link);
		$selected_link=str_replace("li_","",$selected_link);
		$aryIDS=explode("_",$selected_link);
		//print_r($aryIDS);
		//print("SEL=".$SELECTED_MENU);
		
		if(empty($aryOpciones)) return '';
		
		// print_r($aryOpciones);
		// echo "<br>";
		// echo "<br>";
		// echo "<br>";
		foreach($aryOpciones as $key=>$value)
		{
			//var_dump($value['children']);
			//var_dump($key['children']);
			//echo $key;
								//----------------------------------------------------------------------------------------------------------------------------------------------
								//Modificado por Enrique Godoy: Miércoles, 26 de Agosto de 2009
								//----------------------------------------------------------------------------------------------------------------------------------------------								
								// Notas : Aparentemente la opción de VISIBILITY:HIDE a través de CSS que dejó el buen Mario Alberto Moreno funcionaba muy bien para las opciones 
								//				 del menú que no fueran las principales, y en las principales simpre mostraba la opción tubiera o no permisios. Esta modificación
								//				 pasa por alto todas las ociones que no tengan permiso si el default es : $MENU_OPCION_SINPERMISO=="HIDE" por lo que no se encontrarán 
								//				 dichas opciones no solo no se desplegarán como es de esperarse sino que no existirán en el código fuente HTML logrando así adicionalmente 
								//				 al problema solucionado, un poco menos de trafico y mayor seguridad ya que no dependemos del soporte de CSS del navegador para desplegar 
								//				 unicamente las opciones habilitadas.
					 
							
								if(( $_SESSION['ID_GRP'] == -1)	 and ( $_SESSION['ID_SUC'] == -1))
								{
									 $MENU_OPCION_SINPERMISO="";
									 $value["permiso"] = 1;
									 echo "entra if";
								}
								if(($MENU_OPCION_SINPERMISO=="HIDE") and (! $value["permiso"]))
								{
												continue;
								}
								else
								{
									
									$class_selected="";
									//Estilos predefinidos
									$li_Class="";
									$a_Class=" file ";
									$opened="	 ";
									//echo "<HR> COUNT=" . count($value["children"]);
									//print_r($value["children"]);
									foreach($value['children'] as $key=>$value){
										echo $value['nombre'];
										echo "<br>";
									}
									echo $aryIDS[0];
									echo "<br>";
									echo $aryIDS[1];
									echo "<br>";
									echo $aryIDS[2];
									echo "<br>";
									echo $aryIDS[3];
									echo "<br>";
									//$str_children = CreateMenuFromArray($value["children"]);
									//echo "str children = ".$str_children;
								
								}//fin segundo ELSE
								echo "fuera de foreach END";
		
		
	}
		
 } 


function getTiposCreditoActivos($db) {
	$getTiposCreditoActivos = "''";
	$Query = "SELECT	ID_Tipocredito 
						FROM		cat_tipo_credito 
						WHERE		Status = 'Activo' 
						"; 
	$rs = $db->Execute($Query);
	while( !$rs->EOF ) {
		$arrTipos[] = $rs->fields["ID_Tipocredito"];
		$rs->MoveNext(); 
	} 
	if( sizeof($arrTipos) > 0 ) { 
		$getTiposCreditoActivos = implode( ",", $arrTipos ); 
	}
	return $getTiposCreditoActivos; 
}

/**
 * Obtene un arreglo con la informacion de todas las opciones, rutas y permisos
 */
 function getMenuLevelData($ID_GRP,$id_menu)
 {
		global $odb;

	$listaCreditos = getTiposCreditoActivos($odb);

		$chr_separator="_";
		$sql = "SELECT a.Nombre, a.Modulo, a.Opcion, a.ID_Sub, a.ID_Sub2, a.ID_Sub3, IF(b.Permiso IS NULL,0,b.Permiso) AS Permiso, c.ruta,a.ID_Menu_ligado,a.ID_Menu, a.Orden
																FROM (menu_dtl a, rutas c )
																LEFT JOIN permisos b ON (
																	a.ID_Sub = b.ID_Sub and
																	a.ID_Sub2 = b.ID_Sub2 and
																	a.ID_Sub3 = b.ID_Sub3 and
																	b.ID_Menu= a.ID_Menu and
																	b.ID_grupo = '$ID_GRP'
																)	 
																WHERE a.ID_Menu='".$id_menu."' and a.id_ruta = c.id_ruta
																
																AND ( a.ID_Tipocredito <= 0 OR a.ID_Tipocredito IN (".$listaCreditos.") )
																
																AND a.Estado = 'Activo'
																##NIVEL##
																ORDER BY a.Orden ";
																
		$condicion1=" and a.ID_Sub!=0 AND a.ID_Sub2=0 AND a.ID_Sub3=0 ";														
		
//		debug($sql);
//		die();
		
		$sql_nivel1=str_replace("##NIVEL##",$condicion1,$sql);
	 // echo "<hr>".$sql_nivel1;
	 // echo "<hr>";
		$rs = $odb->Execute($sql_nivel1);
		$output=array();	
		$iNivel1=0;
		$iNivel2=0;
		$iNivel3=0;



		while (!$rs->EOF)
		{



			$id_menu_link=$rs->fields["ID_Menu"] . $chr_separator . $rs->fields["ID_Sub"]
					. $chr_separator . $rs->fields["ID_Sub2"]
					. $chr_separator . $rs->fields["ID_Sub3"];



			$ID_Menu	=$rs->fields["ID_Menu"];
			$ID_Sub		=$rs->fields["ID_Sub"];
			$ID_Sub2	=$rs->fields["ID_Sub2"];
			$ID_Sub3	=$rs->fields["ID_Sub3"];
					
					
			
					
					//$output[$ID_Menu][$ID_Sub][$ID_Sub2][$ID_Sub3]=array(
					 $output[$id_menu_link]=array(
																						"id"=>$id_menu_link,
																						"ID_Menu" => $rs->fields["ID_Menu"],
																						"ID_Sub" => $rs->fields["ID_Sub"],
																						"ID_Sub2" => $rs->fields["ID_Sub2"],
																						"ID_Sub3" => $rs->fields["ID_Sub3"],
																						"nombre" => $rs->fields["Nombre"],
																						"modulo" => $rs->fields["Modulo"],
																						"opcion" => $rs->fields["Opcion"],
																						"permiso" => $rs->fields["Permiso"],
																						"ruta" => $rs->fields["ruta"],
																						"orden" => $rs->fields["Orden"],
																						"children"=>array()
																				) ;
				 
				 
				 if($rs->fields["ID_Menu_ligado"])
								{
									$children=getMenuLevelData($ID_GRP,$rs->fields["ID_Menu_ligado"]);
									$output[$id_menu_link]["children"]=$children;
								}





			//----OPCION NIVEL 2-------------------------------------------//
			if( $rs->fields["Opcion"] > 0 && $rs->fields["Opcion"] < 3 ) // MENU INTERNO 
			{




				$condicion2=" and a.ID_Sub='".$rs->fields["ID_Sub"]."' AND a.ID_Sub2!=0 AND a.ID_Sub3=0 ";
				$sql_nivel2=str_replace("##NIVEL##",$condicion2,$sql);
				
				//debug($sql_nivel2);
				
				$rs2 = $odb->Execute($sql_nivel2);
					
				while (!$rs2->EOF)
				{
					$id_menu_link2				=				$rs2->fields["ID_Menu"]		. $chr_separator . $rs2->fields["ID_Sub"]
																																	. $chr_separator . $rs2->fields["ID_Sub2"]
																																	. $chr_separator . $rs2->fields["ID_Sub3"];

					$output[$id_menu_link]["children"][$id_menu_link2]=array(
																																					"id"=>$id_menu_link2,
																																					"ID_Menu" => $rs2->fields["ID_Menu"],
																																					"ID_Sub" => $rs2->fields["ID_Sub"],
																																					"ID_Sub2" => $rs2->fields["ID_Sub2"],
																																					"ID_Sub3" => $rs2->fields["ID_Sub3"],
																																					"nombre" => $rs2->fields["Nombre"],
																																					"modulo" => $rs2->fields["Modulo"],
																																					"opcion" => $rs2->fields["Opcion"],
																																					"permiso" => $rs2->fields["Permiso"],
																																					"ruta" => $rs2->fields["ruta"],
																																					"orden" => $rs2->fields["Orden"]
																																) ;


						 if($rs2->fields["ID_Menu_ligado"])
						 {
									$children=getMenuLevelData($ID_GRP,$rs2->fields["ID_Menu_ligado"]);
									$output[$id_menu_link]["children"][$id_menu_link2]["children"]=$children;
						 }

						//------------------------opcion nivel3--------------
						if( $rs2->fields["Opcion"] > 0 && $rs2->fields["Opcion"] < 3 ) // MENU INTERNO 
						{
							$condicion3=" and a.ID_Sub='".$rs2->fields["ID_Sub"]."' AND a.ID_Sub2='".$rs2->fields["ID_Sub2"]."' AND a.ID_Sub3!=0 ";
							$sql_nivel3=str_replace("##NIVEL##",$condicion3,$sql);
							$rs3 = $odb->Execute($sql_nivel3);

									 while (!$rs3->EOF)
											{
												$id_menu_link3=$rs3->fields["ID_Menu"]	. $chr_separator . $rs3->fields["ID_Sub"]
																																. $chr_separator . $rs3->fields["ID_Sub2"]
																																. $chr_separator . $rs3->fields["ID_Sub3"];

															$output[$id_menu_link]["children"][$id_menu_link2]["children"][$id_menu_link3]=array(
																																																												"id"=>$id_menu_link3,
																																																												"ID_Menu" => $rs3->fields["ID_Menu"],
																																																												"ID_Sub" => $rs3->fields["ID_Sub"],
																																																												"ID_Sub2" => $rs3->fields["ID_Sub2"],
																																																												"ID_Sub3" => $rs3->fields["ID_Sub3"],
																																																												"nombre" => $rs3->fields["Nombre"],
																																																												"modulo" => $rs3->fields["Modulo"],
																																																												"opcion" => $rs3->fields["Opcion"],
																																																												"permiso" => $rs3->fields["Permiso"],
																																																												"ruta" => $rs3->fields["ruta"],
																																																												"orden" => $rs3->fields["Orden"]
																																																								 ) ;




												$rs3->MoveNext();
												$iNivel3++;
											}

							
							
						}
						//------------------------------------------------------------
						
					$rs2->MoveNext();
					$iNivel2++;
				}
			}
			//----OPCION NIVEL 2-------------------------------------------//
			
			 
			$rs->MoveNext();
			$iNivel1++;
		}				
		if($rs)$rs->Close();

		return $output;
 }






				 //$temp=11;
				 //$sql = " SELECT	Tipo	FROM usuarios WHERE ID_User = '".$ID_USR."' ";
				 $sql = " SELECT	Tipo,ID_Menu	FROM grupo WHERE ID_Grupo = '".$_SESSION['ID_GRP']."' ";
				 $rs=$odb->Execute($sql);
				 $id_menu=$rs->fields[1];
			 
				 //$MENU_TYPE="HORIZONTAL";
				 //Se deja fijo a menu tipo arbol
				 $MENU_TYPE="TREE";

				 $sql = " SELECT	Valor FROM constantes WHERE Nombre = 'TIPO_MENU_PRINCIPAL' ";
				 $rs=$odb->Execute($sql);
				 if($rs->fields[0] == 'TREE') $MENU_TYPE="TREE"; 
				 
				 $MENU_OPCION_SINPERMISO="BLOCK";
				 $sql = " SELECT	Valor FROM constantes WHERE Nombre = 'MENU_OPCION_SIN_PERMISO' ";
				 
				 //debug($sql);
				 
				 $rs=$odb->Execute($sql);
				 if($rs->fields[0] == 'HIDE') $MENU_OPCION_SINPERMISO="HIDE"; 
				 
				 
?>
<HTML>
<HEAD>
<link href="<?=$style_path."sistema.css";?>" rel="stylesheet" type="text/css">
<script src="<?=$shared_scripts?>jquery/jquery-1.3.2.js"></script>
<script src="<?=$shared_scripts?>jquery/jquery.cookie.js"></script>
<?
 
	$ulClassMenu="sf-menu menu";
	if($MENU_TYPE=="HORIZONTAL"){
?>
<!--JS & STYLES FOR HORIZONTAL MENU-->

<link href="<?=$sys_path."menu/menu-horizontal.css";?>" rel="stylesheet" type="text/css">

<script src="<?=$shared_scripts?>jquery/menu/js/hoverIntent.js"></script>
<script src="<?=$shared_scripts?>jquery/menu/js/superfish.js"></script>
<script type="text/javascript"> 
 
	 $(document).ready(function() { 
					 try{
								$('ul.sf-menu').superfish(
								{
												speed:'fast',
												delay:1000,
												dropShadows: true,
												disableHI:		 true,
												 minWidth:		12,		// minimum width of sub-menus in em units 
										 maxWidth:		27,		// maximum width of sub-menus in em units 
										 extraWidth:	1			// extra width can ensure lines don't sometimes turn over 
																		 
								}
								);
					 }catch(e){
												//alert(e.message);
					 }		 
		}); 
	
</script>

<?
}
else
{
	 $ulClassMenu="filetree treeview";
		
?>
<!--JS & STYLES FOR TREE MENU-->
<link href="<?=$shared_scripts."jquery/treemenu/jquery.treeview.css";?>" rel="stylesheet" type="text/css">
<style>
	body
	{
		margin:0px;
		padding:0px;
		

	}
	#browser{
		
		float:left;
		position:relative;
		top:-1px;
		left:0px;
		background:transparent url(<?=$sys_path?>menu/fondo_bloque.gif) no-repeat scroll left top;
		width:248px;
		z-index:1000px;
		font-family:Arial, Helvetica, sans-serif;	 
		font-size:11px;

 

	}
	
	#divShowMenu{
		background:#FFFFFF url(<?=$sys_path?>menu/fondo_bloque.gif) no-repeat scroll left top;
		top:0px;
		left:0px;
		padding-left:10px;
		float:left;
		

		position:relative;
	}
	#divShowMenu a
	{
		font-size:20px;
		text-decoration:none;
		font-weight:bold;
		color:#FFFFFF;
		padding-left:5px;
		padding-right:5px;
		padding-bottom:10px;
		text-align:right; 
	}
	#browser a{
		color:#666666;
		
	}
		
	#browser .title
	{
		color:#FFFFFF;
		font-size:13px;
		font-weight:bold;
		padding-top:5px;
		padding-bottom:10px;
	}
	#browser .title a
	{
		text-decoration:none;
		font-size:15px;
		font-weight:bold;
		color:#FFFFFF;
		padding-right:5px;
		padding-bottom:10px;
		text-align:right;		 
	}

	#browser ul{
		background:transparent;
	}
	#browser .selected{
		color:#FF0000;
	}
	
	#browser .block{
	color:#CFCFCF;
}
#browser * .hide{
		display:none;
		visibility:hidden;
}
#browser ul li ul li a{
		font-size:11px;
}
#browser ul li a{
		font-size:11px;
}


</style>
<script src="<?=$shared_scripts?>jquery/treemenu/jquery.treeview.js"></script>	
<script type="text/javascript">

var f=parent.document;
var fM=f.getElementById('mainFrame');


function ToggleMenu(bOpen)
{


		var f=parent.document;
		var fM=f.getElementById('mainFrame');
				 
				 
				 
	 if(bOpen)
	 {
					 fM.cols = "24,*";
					 $('#divMenu').css('display','none');
					 $('#divShowMenu').css('display','');
					 $('#infoUser').css('display','none');
					 
					// $('#divContent').css('margin-left','10');
					 // $('#divContent').css('width','95%');
			 $('#browser').hide();
 
				$.cookie('menu_open',false,{path:'/', expires: 1 });
			}
			else
			{
				 fM.cols = "255,*"; 
				 $('#divMenu').css('display','');
						 $('#divShowMenu').css('display','none');
						// $('#divContent').css('margin-left','240');
					 //	 $('#divContent').css('width','75%');
						 $('#infoUser').css('display','');
				 $('#browser').show();
			
				$.cookie('menu_open',true,{path:'/', expires: 1 });

			}		 
					 
	}

	var LastlinkID="";			

 $(document).ready(function() 
 { 
 
	 try{
			$("#browser").treeview({
			// persist: "cookie",
			// cookieId: "navigationtree",
			// prerendered: true,
			 unique: true
							
									});
									
									$("#closeMenu").click(function(){
										//alert("CLOSE");
										ToggleMenu(true);
			});
			$("#openMenu").click(function(){
			//alert("OPEN");
										ToggleMenu(false);
			});
			
									$("#browser li").click(function()
									{
										var options = { path: '/', expires: 1 };
												$.cookie('SELECTED_MENU',null,{ expires: 0 }); 
												jQuery.cookie('SELECTED_MENU', '', {expires: 0});	 
										
												if(this.firstChild.href!=undefined)
												{
																	 // alert("" + this.firstChild.id);
																		//alert($.cookie('SELECTED_MENU'));
																		 $.cookie('SELECTED_MENU',this.firstChild.id,{ expires: 1 });
																		 return false;
												}	
										 
			});
			
			 $("#browser a").click(function()
			 {
				// alert("" + this.id);
				//	alert($.cookie('SELECTED_MENU'));
				 $.cookie('SELECTED_MENU',null,{	expires: 0 });
				 jQuery.cookie('SELECTED_MENU', '', {expires: 0});
								
										 $.cookie('SELECTED_MENU',this.id,{	 expires: 1 });
										 //alert($.cookie('SELECTED_MENU'));
										 //document.location=this.href;
										// alert($("#frmContent").document.location)
										//alert($("#"+this.id).attr("class"));
				sClass=$("#"+this.id).attr("class") ;
				
				$("#browser a").each(function(){
						lObj=$(this);
						$(this).css('color', '#666666');
				});
				$("#closeMenu").css('color', '#FFF');
				sClass=$(this).attr("class");
				if(sClass.indexOf("file")>=0){
											$(this).css('color', '#F00');
										}
 
				
										window.parent.frames["frmContent"].location=this.href;
										 //$("#frmContent").attr("src",this.href);
										 return false;
										 
			});
			
			
				 }catch(e){
				 }
									
									
				
				});

				 
				 
				
				</script>

<?
}
?>

</HEAD>
<BODY>

<?php
		
				function print_menu2()
				{

									global $id_menu,$ulClassMenu,$ID_GRP,$NOM_USR,$_COOKIE,$sys_path,$MENU_TYPE;
									global $SELECTED_MENU;


								 $opcioneS= getMenuData($ID_GRP,$id_menu);



								 print_r($opcioneS);



									$output='';
									$breakLine=chr(10);
									$CierreMenu='';
									
								 //*** if($_COOKIE["menu_open"]=="false" && $MENU_TYPE=="TREE") $hideMenu=" ;display:none;";



									$output.= $breakLine . '<ul id="browser" class="'.$ulClassMenu. '" style="'.$hideMenu.'">'.$breakLine;
									
									if($MENU_TYPE=="TREE")
									{
										$output.= '<li	class="title"><div style="float:left">MENU PRINCIPAL</div><div align="right"><a href="javascript:alert(\'--\');ToggleMenu(true);" >&laquo;</a></div></li>' .$breakLine;
									}
									else
									{
										//$output.= '<li	class="title"><img src="'.$sys_path.'menu/menubg0.png"></li>' .$breakLine;
										$CierreMenu='<li><img src="'.$sys_path.'menu/menubg3.png"/></li>';						
									}
									
									$aryMenu=CreateMenu($ID_GRP,$id_menu);
									
									$output.=$aryMenu["output"];
									$output.= '<li><a class="file" href="'.$sys_path.'logout.php">Salir</a></li>' . $CierreMenu.$breakLine;

									$output.='</ul>'.$breakLine;
									echo $output;
									//echo $SELECTED_MENU;
				}
				
 //======================================================================================================================================//				
 //
 //======================================================================================================================================//				
				
				function print_menu()
				{
													global $id_menu,$ulClassMenu,$ID_GRP,$NOM_USR,$_COOKIE,$sys_path,$MENU_TYPE;
													global $SELECTED_MENU,$odb;

												

													$output='';
													$breakLine=chr(10);
													$CierreMenu='';
													$hideMenu="";
													$strMenu ="";
													$output.= $breakLine . '<ul id="browser" class="'.$ulClassMenu. '" style="'.$hideMenu.'">'.$breakLine;
										
												 if($MENU_TYPE=="TREE")
													{
														$output.= '<li	class="title"><div style="float:left">MENU PRINCIPAL</div><div align="right"	

														><a href="javascript:;" id="closeMenu" >&laquo;</a></div></li>' .$breakLine;
													}
													else
													{
														
														$CierreMenu='<li><img src="'.$sys_path.'menu/menubg3.png"/></li>';						
													}
													



													
													if( $id_menu == 0 ) { 
														//echo "entra if";
														$Query = "SELECT		grupo_menu.ID_Menu, 
																								menu.Nombre 
																			FROM			grupo_menu, 
																								menu				
																			WHERE			grupo_menu.ID_Menu	 = menu.ID_Menu	 
																			AND				grupo_menu.ID_Grupo = '".$_SESSION['ID_GRP']."' 
																			AND				menu.Estado = 'Activo' 
																			ORDER BY	menu.Orden";					 
														$rsMENUS = $odb->Execute($Query); 
														while( !$rsMENUS->EOF ) { 
															$strMenu .= '<li class="liMenus" onclick=\'javascript: 
																var closeOpen = true; 
																if( jQuery("li[alt=alt'.$rsMENUS->fields["ID_Menu"].']").length ) { 
																	if( jQuery("li[alt=alt'.$rsMENUS->fields["ID_Menu"].']").css("display") == "none" ) { 
																			closeOpen = false;
																	} 
																}
																jQuery(".expandable").css("display","none"); 
																jQuery(".collapsable").css("display","none"); 
																jQuery(".block").css("display","none"); 
																jQuery(".liMenus").css("background-color","#FFFFFF");
																if( !jQuery("li[alt=alt'.$rsMENUS->fields["ID_Menu"].']").length ) { 
																	alert("Modulo '.$rsMENUS->fields["Nombre"].': SIN PRIVILEGIOS");
																} else { 
																	if(!closeOpen) { 
																			jQuery("li[alt=alt'.$rsMENUS->fields["ID_Menu"].']").css("display","block"); 
																			jQuery(this).css("background-color","#D1D9DF"); 
																	} else {
																			jQuery("li[alt=alt'.$rsMENUS->fields["ID_Menu"].']").css("display","none"); 
																			
																	}
																}
															\' style="cursor: pointer; color: #274C6A; font-weight: bold;" 
															onmouseover=\'javascript: 
																	if( jQuery("li[alt=alt'.$rsMENUS->fields["ID_Menu"].']").css("display") == "none" ) { 
																		jQuery(this).css("background-color","#D1D9DF"); 
																	} else { 
																		jQuery(this).css("background-color","#98AAB8"); // 
																	} 
																 
															\' 
															onmouseout=\'javascript: 
																if( jQuery("li[alt=alt'.$rsMENUS->fields["ID_Menu"].']").length ) { 
																	if( jQuery("li[alt=alt'.$rsMENUS->fields["ID_Menu"].']").css("display") == "none" ) { 
																		jQuery(this).css("background-color","#FFFFFF"); 
																	} else {
																		jQuery(this).css("background-color","#D1D9DF"); 
																	}
																} else {
																	jQuery(this).css("background-color","#FFFFFF"); 
																}
															\'
															>'.($rsMENUS->fields["Nombre"]).'</li>' .$breakLine;
															$opcionesMenu = getMenuLevelData( $_SESSION['ID_GRP'], $rsMENUS->fields["ID_Menu"] );
															$strMenu .= CreateMenuFromArray($opcionesMenu);
															$rsMENUS->MoveNext(); 
														}
													} else {
														$opcionesMenu= getMenuLevelData($ID_GRP,$id_menu);
														$strMenu=CreateMenuFromArray($opcionesMenu);
													}
												
												
												$output.=$strMenu;
												
												
											
													$output.= '<li><a class="file" target="frmContent" id="lSalir" href="'.$sys_path.'logout.php">Salir</a></li>' . $CierreMenu.$breakLine;

													$output.='</ul>'.$breakLine;
													echo $output;
													
												}

										$openTagMenu = "display:none;";

										$hideMenu="";
									
										$date = fechaNatural(date("Y-m-d"));				
										
echo "<div id='divShowMenu' style='".$openTagMenu."position:absolute;z-index:100;left:0px;float:left;width:20px;padding:0px 0 0px 0px;top:0px;	height: 26px;'><a href='javascript:;' id='openMenu'>&raquo;</a></div>";			 

					
echo "<div class='module' style='z-index:10;left:0px;float:left;width:248px;padding:0px 0 0px 0px;margin-left: 0px; ;-moz-box-shadow:4px 0px 4px gray; -webkit-box-shadow: 4px 0px 4px gray; box-shadow:0px 0px 4px gray;border-bottom-right-radius: 6px;".$hideMenu."' id='divMenu'> ";
	print_menu();
echo "</div>\n";	
										
										
?>




</BODY>
</HTML>