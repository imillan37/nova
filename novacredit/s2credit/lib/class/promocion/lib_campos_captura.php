<?
/****************************************/
/*Fecha: 07/09/2011
/*Autor: Tonathiu Cárdenas
/*Descripción:COMBOS UTILIZADOS DURANTE FLUJO DE PROMOCIÓN
/*Dependencias:
/****************************************/
$CMP_WITH_COUNT = array('ID_Giro_negocio','Actividad_soli');

function select_custom($nomb_cmp,$sql,$option_select,$class,$style,$evnt,$title,$id_secc,$etq,$src_referencia,$tipo_credit,$id_suc,$id_empresa,$db)
  {
	global $CMP_WITH_COUNT;
	  
    $Sql=explode("|",$sql);

	/***QUERY*****/
	//COMBO FORMADO POR SÓLO UNA CONSULTA SQL
	if((!empty($sql)) && (count($Sql)==1) )
	{
	  //Validar para Producto financiero
	  $SQL =str_replace('$tipo_credit',$tipo_credit,$Sql[0]);
	  $SQL =str_replace('$tipo_producto','nuevo',$SQL);
	  //Validar grupo solidario
	  $SQL =str_replace('$id_suc',$id_suc,$SQL);

	 //VALIDAR CRÉDITO NÓMINA PRODUCTOS EMPRESA
	 if($tipo_credit == 3 && $nomb_cmp == 'ID_Producto')
		{
				 $SQL_CONS_EMP="SELECT 
									ID_Producto		AS ID
							FROM	cat_convenio_empresas_prod_financiero
							WHERE ID_empresa ='".$id_empresa."' ";
				$rs_cons_emp=$db->Execute($SQL_CONS_EMP);

				While(!$rs_cons_emp->EOF)
					{
						$STR_ID_PROD.="'".$rs_cons_emp->fields["ID"]."'";

						$rs_cons_emp->MoveNext();
						
						$STR_ID_PROD.=(! $rs_cons_emp->EOF)?(","):("");

					}

				 $SQL =str_replace('$str_id_productos',$STR_ID_PROD,$SQL);
				
		}
									
	  $sql_cons = $SQL;
	  $rs_cons=$db->Execute($sql_cons);
	  
     }
     else
     {
		//COMBO FORMADO POR VARIAS CONSULTAS SQL
        $Cont  = 0;
        $INDEX = 1;
        
		foreach($Sql as $value)
		{
			if(!empty($value))
			{
			  $sql_cons = $value;
			  $rs_cons=$db->Execute($sql_cons);
		    }

		     if(empty($Cont))
		        $Tmp_table= trim(strstr($value, '_'));
		        
		 $Cont	++;
		 $INDEX ++;  
		}

		//ELIMINA TABLA TEMPORAL
        if(!empty($Tmp_table))
        {
			$Sql_del="DROP TABLE IF EXISTS ".$Tmp_table." ";
			$db->Execute($Sql_del);
		}
	 }
	/***FIN QUERY*****/
	//VALIDAR SI SE NECESITA ENLISTAR LOS VALORES
	$IS_COUNT =( in_array($nomb_cmp,$CMP_WITH_COUNT) )?("TRUE"):("FALSE");

	$combo.="<SELECT ID='".$nomb_cmp."'  NAME='".$nomb_cmp."'  CLASS='".$class."' STYLE='".$style."' ".$evnt." ".$title." LANG='".$id_secc."_".$etq."".$src_referencia."' > \n";
	$combo.= "<OPTION VALUE='' SELECTED  >SELECCIONAR OPCIÓN</OPTION> \n";
	$combo.= "<OPTION VALUE='' DISABLED>-------------------------------------------------------</OPTION>";
	if(!empty($sql))
	{
		$INDEX = 1;
	  while(! $rs_cons->EOF )
	   {
	     $sel 		   = ($rs_cons->fields["ID"] == $option_select )?("SELECTED"):("");
	     $INDEX_COUNT  = ($IS_COUNT == 'TRUE' )?($INDEX.".- "):("");
	     $combo.= "<OPTION VALUE='".$rs_cons->fields["ID"]."' ".$sel." >".$INDEX_COUNT."".$rs_cons->fields["DESCP"]."</OPTION> \n";

	     $INDEX ++;
	     
	    $rs_cons->MoveNext();
	   }//Fin while
	}

	if(!empty($option_select) && empty($sql) )
		$combo.= "<OPTION VALUE='".$option_select."' SELECTED >".$option_select."</OPTION> \n";
	
	$combo.="</SELECT>\n";

   return $combo;
  }


function select_custom_array($nomb_cmp,$sql,$option_select,$class,$style,$evnt,$title,$id_secc,$etq,$src_referencia,$tipo_credit,$db)
  {
	  global $CMP_WITH_COUNT;
	  
    $Options=explode("|",$sql);

	$combo.="<SELECT ID='".$nomb_cmp."'  NAME='".$nomb_cmp."'  CLASS='".$class."' STYLE='".$style."' ".$evnt." ".$title." LANG='".$id_secc."_".$etq."".$src_referencia."' > \n";
	$combo.= "<OPTION VALUE='' SELECTED  >SELECCIONAR OPCIÓN</OPTION> \n";
	$combo.= "<OPTION VALUE='' DISABLED>-------------------------------------------------------</OPTION>";

		foreach($Options as $value)
		{
	     $sel = ($value == $option_select )?("SELECTED"):("");
	     $combo.= "<OPTION VALUE='".$value."' ".$sel." >".$value."</OPTION> \n";
		}//Fin for

	$combo.="</SELECT>\n";

   return $combo;
  }    
?>
