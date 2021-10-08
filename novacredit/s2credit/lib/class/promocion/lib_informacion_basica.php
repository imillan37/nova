<?
function sucursal_datos($id_sucursal,$db)
{

         $sql_suc = "SELECT Nombre, Direccion, Colonia, CP, Ciudad, Estado, Telefonos, FAX 
                     FROM sucursales 
                     WHERE   ID_Sucursal= '".$id_sucursal."' ";
	 $rs_suc=$db->Execute($sql_suc);

	 $sucursal=$rs_suc->fields["Nombre"];
	 $direccionsuc = $rs_suc->fields["Direccion"]." Col. ".$rs_suc->fields["Colonia"]." C.P. ".$rs_suc->fields["CP"]." ".$rs_suc->fields["Ciudad"]." ".$rs_suc->fields["Estado"];
	 
	 $telsuc = "Teléfono(s)".$rs_suc->fields["Telefonos"];
         $telsuc.=(!empty($rs_suc->fields["FAX"]))?(" Fax. ".$rs_suc->fields["FAX"] ):("");
	 

  	 $arr_sucursal= array("Sucursal"=>$sucursal,"Direccion"=>$direccionsuc,"Telefono"=>$telsuc);
	 
    return  $arr_sucursal;
}


function usuario_nombre($id_usuario,$db)
{
	$sql_cons ="SELECT CONCAT(Nombre,' ',AP_Paterno,' ',AP_Materno) AS USUARIO
	             FROM usuarios WHERE ID_User= '$id_usuario'";
	$rs_cons = $db->Execute($sql_cons);
	
   return $rs_cons->fields["USUARIO"];
}

function cliente_nombre($id_soli,$db)
{
	$sql_cons ="SELECT
						CONCAT(Nombre,' ',NombreI,' ',AP_Paterno,' ',AP_Materno) AS CTE
	             FROM solicitud
					WHERE ID_Solicitud ='".$id_soli."' ";
	$rs_cons = $db->Execute($sql_cons);
	
   return $rs_cons->fields["CTE"];
}

function dtl_credito($Tipo_credito,$ID_Tiposolicitud,$db)
{

 $Sql_cons="SELECT
                   Descripcion AS DESCP
            FROM cat_tipo_credito_regimen
            WHERE ID_Tipocredito 		= '".$Tipo_credito."'
				AND ID_Tipo_regimen  	= '".$ID_Tiposolicitud."' ";
  $rs_cons=$db->Execute($Sql_cons);

 return $rs_cons->fields["DESCP"];
}

function traducefecha($fecha,$formato)
   {
	$fecha= strtotime($fecha); 
	$diasemana=date("w", $fecha);
	 switch ($diasemana)
	 {
	    case "0":
		 $diasemana="domingo";
		 break;
	    case "1":
		 $diasemana="lunes";
		 break;
	    case "2":
		 $diasemana="martes";
		 break;
	    case "3":
		 $diasemana="miércoles";
		 break;
	    case "4":
		 $diasemana="jueves";
		 break;
	    case "5":
		 $diasemana="viernes";
		 break;
	    case "6":
		 $diasemana="sábado";
		 break;
	 }
	 $dia=date("d",$fecha); 
	 $mes=date("m",$fecha); 
	 switch($mes)
	 {
	    case "01":
		 $mes="Enero";
		 break;
	    case "02":
		 $mes="Febrero";
		 break;
	    case "03":
		 $mes="Marzo";
		 break;
	    case "04":
		 $mes="Abril";
		 break;
	    case "05":
		 $mes="Mayo";
		 break;
	    case "06":
		 $mes="Junio";
		 break;
	    case "07":
		 $mes="Julio";
		 break;
	    case "08":
		 $mes="Agosto";
		 break;
	    case "09":
		 $mes="Septiembre";
		 break;
	    case "10":
		 $mes="Octubre";
		 break;
	    case "11":
		 $mes="Noviembre";
		 break;
	    case "12":
		 $mes="Diciembre";
		 break;
	 }
	$ano=date("Y",$fecha); 
	$fecha2=$dia." de ".$mes." de ".$ano;  
	$fecha= $diasemana."  ".$dia." de ".$mes." de ".$ano;  
	
	switch($formato)
	 {
	    case "DIA":
		 return $dia;
		 break;
	    case "MES":
		 return $mes;
		 break;
	    case "AÑO":
		 return $ano;
		 break;
	    case "DIA_SEMANA":
		 return $diasemana;
		 break;
	    case "COMPLETA":
		 return $fecha2;
		 break;
	    case "FECHA_COMPLETA":
	    	 return $fecha;
		 break;
	 }
}
?>
