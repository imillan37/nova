<?php
$noheader =1;
include_once $DOCUMENT_ROOT."/rutas.php";
include_once $DOCUMENT_ROOT.$avalespath."/jsonwrapper/jsonwrapper.php";
include_once $ado_path."adodb.inc.php";

function refrescar($ID_Solicitud,$idForm){
if($ID_Solicitud){
            if($idForm ==1)
                    {
                        $ID_AVAL            =    get_id_aval($ID_Solicitud,'AVAL');
                        //echo "alert('id conseguido: ".$ID_AVAL."');";
                        $Pag_captura        =   ($idForm ==1)?("../../../../sucursal/promocion/avales/captura_aval.php?noheader=1&ID_Solicitud=".$ID_SOLI.""):("");
                        $Pag_editar         =   ($idForm ==1)?("../../../../sucursal/promocion/avales/captura_aval.php?noheader=1&id=".$ID_AVAL):("");
                        $Pag_vista          =   ($idForm ==1)?("../../../../sucursal/promocion/avales/ver_aval.php?noheader=1&id=".$ID_AVAL):("");

                        $CLASS_VINCULO_CAPTURA      =   ( empty($ID_AVAL))?(''):('NO_PERMIT_CAPT_AVAL');
                        $CLASS_VINCULO_EDITA        =   (!empty($ID_AVAL))?(''):('NO_PERMIT_EDIT_VIEW_AVAL');
                        $ID_CAPTURA        = 'AVAL_CAPTURA';
                        $ID_EDITA          = 'AVAL_EDITA';
                        $ID_VISTA          = 'AVAL_VISTA';
                    }
                   

                              
            elseif($idForm==2)
                {
                    $ID_COSOL           =    get_id_aval($ID_Solicitud,'COSOL');
                    $Pag_captura        =   ($idForm ==2)?("../../../../sucursal/promocion/cosolicitantes/captura_cosolicitante.php?noheader=1&ID_Solicitud=".$ID_SOLI.""):("");
                    $Pag_editar         =   ($idForm ==2)?("../../../../sucursal/promocion/cosolicitantes/captura_cosolicitante.php?noheader=1&id=".$ID_COSOL):("");
                    $Pag_vista          =   ($idForm ==2)?("../../../../sucursal/promocion/cosolicitantes/ver_cosolicitante.php?noheader=1&id=".$ID_COSOL):("");

                    $CLASS_VINCULO_CAPTURA      =   ( empty($ID_COSOL))?(''):('NO_PERMIT_CAPT_COSOL');
                    $CLASS_VINCULO_EDITA        =   (!empty($ID_COSOL))?(''):('NO_PERMIT_EDIT_VIEW_COSOL');

                    $ID_CAPTURA        = 'COSOL_CAPTURA';
                    $ID_EDITA          = 'COSOL_EDITA';
                    $ID_VISTA          = 'COSOL_VISTA';

                }


                		$valores['classCaptura']=$CLASS_VINCULO_CAPTURA;
                        	$valores['classEdicion']=$CLASS_VINCULO_EDITA;
                        	$valores['classVista']=$CLASS_VINCULO_EDITA;

                        	$valores['langCaptura']=$Pag_captura;
                        	$valores['langEdicion']=$Pag_editar;
                        	$valores['langVista']=$Pag_vista;
     return json_encode($valores);
}



   // $str_vinculos="<TABLE CELLSPACING='0' STYLE='display:none;' ID='OPTION_".$rs_subproc->fields["ID_SUB"]."' ALIGN='RIGHT' BORDER='0px' WIDTH='90%'>
   //                                                  <TR>
   //                                                      <TD STYLE='text-align:left;'>
   //                                                          <LI ID='".$ID_CAPTURA."' lang='".$Pag_captura."' class='OPTIONS_".$rs_subproc->fields["ID_SUB"]."  ".$CLASS_VINCULO_CAPTURA."   ' STYLE='color:black;'>
                                                            
   //                                                          » CAPTURAR</LI>

   //                                                          <LI ID='".$ID_EDITA."' lang='".$Pag_editar."' class='OPTIONS_".$rs_subproc->fields["ID_SUB"]."   ".$CLASS_VINCULO_EDITA."   ' STYLE='color:black;'>
                                                            
   //                                                          » EDITAR</LI>

   //                                                          <LI ID='".$ID_VISTA."' lang='".$Pag_vista."' class='OPTIONS_".$rs_subproc->fields["ID_SUB"]."    ".$CLASS_VINCULO_EDITA."   ' STYLE='color:black;'>
                                                            
   //                                                          » CONSULTAR</LI>
                                                            
   //                                                      </TD>
   //                                                  </TR>
   //                                                  </TABLE>";
}//end function



function get_id_aval($ID_SOLI,$TIPO_PERSO)
{
    
	 $DB = &ADONewConnection(SERVIDOR); $DB->PConnect(IP,USER,PASSWORD,$DB_EMP);

    $Sql_cons="SELECT
                        ID_Persona      AS ID_PERSO
                FROM
                        solicitud_aval_cosol
                WHERE
                        ID_Solicitud  = ".$ID_SOLI."
                    AND Tipo_relacion = '".$TIPO_PERSO."' ";
    return $rs_cons=$DB->GetOne($Sql_cons);

    
}


?>
