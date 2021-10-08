<?

require($DOCUMENT_ROOT."/rutas.php");

$db = ADONewConnection(SERVIDOR);  # create a connection
$db->Connect(IP,USER,PASSWORD,NUCLEO);

  /*********QUERY**************/
   $sql="SELECT  
	       ID_empresa,
	       Empresa,
	       cat_convenio_empresas.Direccion,
	       Telefono,
	       Extension,
	       cat_convenio_empresas.Status,
	       promotores.Nombre,
	       Num_empleados,
	       Rep_legal,
	       Contacto_Bueno,
	       Contacto_Cobranza,
	       Limite_saldo,
	       Fecha_alta,
	       Forma_pago,
	       Num_empresa
	 FROM    cat_convenio_empresas
	    LEFT  JOIN promotores ON cat_convenio_empresas.Num_promo = promotores.Num_promo
	 WHERE Convenio='1'  AND Lista_negra ='No' AND $Param1='".$Param2."' ";

   $rs=$db->Execute($sql);
   
   list($id_empresa,$nombre_empresa,$direccion_empresa,$telefono_empresa,$extension_empresa,$status_empresa,$promotor,$num_empleados,$rep_legal,$contacto_bueno,$contacto_cobra,$limite_saldo,$fech_convenio,$forma_pago,$Num_empresa)=$rs->fields;

  
   /*********************************/
   
   $id_empresa=($id_empresa=='')?('--- ---'):($id_empresa);
   $nombre_empresa=($nombre_empresa=='')?('--- ---'):($nombre_empresa);
   $direccion_empresa=($direccion_empresa=='')?('--- ---'):($direccion_empresa);
   $telefono_empresa=($telefono_empresa=='')?('--- ---'):($telefono_empresa);
   $extension_empresa=($extension_empresa=='')?('--- ---'):($extension_empresa);
   $status_empresa=($status_empresa=='')?('--- ---'):($status_empresa);
   $num_empleados=($num_empleados=='')?('--- ---'):($num_empleados);
   $promotor=($promotor=='')?('--- ---'):($promotor);
   $limite_saldo=number_format($limite_saldo,2);
   //$Sucursal=($sucursal=='')?('--- ---'):($sucursal);
   $fech_convenio=ffecha($fech_convenio);
   
   
   $sql_suc="SELECT Nombre AS NOMBSUC
             FROM  cat_convenio_empresas_suc 
               INNER JOIN cat_convenio_empresas ON cat_convenio_empresas_suc.ID_empresa = cat_convenio_empresas.ID_empresa
               INNER JOIN sucursales            ON cat_convenio_empresas_suc.ID_Sucursal = sucursales.ID_Sucursal
                WHERE cat_convenio_empresas_suc.ID_empresa = '".$id_empresa."'  ";
   $rs_suc =$db->Execute($sql_suc);
   
      while(! $rs_suc->EOF)
      {
   	$Sucursal.=$rs_suc->fields["NOMBSUC"];
	$rs_suc->MoveNext();
	if(! $rs_suc->EOF)
	$Sucursal.=',  ';
        $rs_suc->MoveNext();
      }
   
   

echo"<BR>";

   echo " <TABLE   CELLSPACING=4 CELLPADDING=4 align='center' width='80%'  STYLE='border:2px dotted #6699cc;'>
        <TR ALIGN='left' VALIGN='middle' BGCOLOR='#d6e9ff'>
	  <TH ALIGN='center' ID='big' COLSPAN='2'><FONT size='2' > <IMG SRC='".$sys_path."images/buildings.png' BORDER=0> DATOS DE LA EMPRESA <BR> \"".$nombre_empresa."\" </FONT></TH>
	</TR>
	<TR ALIGN='left' VALIGN='middle' BGCOLOR='white'>
	  <TH ALIGN='right' Bgcolor='#e7eef6' width='200px'><FONT size='2'>Número:&nbsp;</FONT></TH>
	  <TD ALIGN='left'><FONT size='2'>".$Num_empresa."</FONT> </TD> 
	</TR>
	<TR ALIGN='left' VALIGN='middle' BGCOLOR='white'>
	  <TH ALIGN='right' Bgcolor='#e7eef6' width='200px'><FONT size='2'>Empresa:&nbsp;</FONT></TH>
	  <TD ALIGN='left'><FONT size='2'>".$nombre_empresa."</FONT> </TD> 
	</TR>
	<TR ALIGN='left' VALIGN='middle' BGCOLOR='white'>
	  <TH ALIGN='right' Bgcolor='#e7eef6'><FONT size='2'>Fecha de convenio:&nbsp;</FONT></TH>
	  <TD ALIGN='left'><FONT size='2'>".$fech_convenio."</FONT> </TD> 
	</TR>
	<TR ALIGN='left' VALIGN='middle' BGCOLOR='white'>
	  <TH ALIGN='right' Bgcolor='#e7eef6'><FONT size='2'>Dirección:&nbsp;</FONT></TH>
	  <TD ALIGN='left'> <FONT size='2'>".$direccion_empresa."</FONT></TD> 
	</TR>
	<TR ALIGN='left' VALIGN='middle' BGCOLOR='white'>
          <TH ALIGN='right' Bgcolor='#e7eef6'><FONT size='2'>Teléfono:&nbsp;</FONT></TH>
          <TD ALIGN='left'> <FONT size='2'>".$telefono_empresa."</FONT></TD> 
        </TR>
	<TR ALIGN='left' VALIGN='middle' BGCOLOR='white'>
          <TH ALIGN='right' Bgcolor='#e7eef6'><FONT size='2'>Extensión:&nbsp;</FONT></TH>
          <TD ALIGN='left'> <FONT size='2'>".$extension_empresa."</FONT></TD>
        </TR>
        <TR ALIGN='left' VALIGN='middle' BGCOLOR='white'>
	  <TH ALIGN='right' Bgcolor='#e7eef6'><FONT size='2'>Status:&nbsp;</FONT></TH>
          <TD ALIGN='left'><FONT size='2'>".$status_empresa."</FONT></TD>
        </TR>
	<TR ALIGN='left' VALIGN='middle' BGCOLOR='white'>
	  <TH ALIGN='right' Bgcolor='#e7eef6'><FONT size='2'>Número de empleados :&nbsp;</FONT></TH>
          <TD ALIGN='left'><FONT size='2'>".$num_empleados."</FONT></TD>
	</TR>
	<TR ALIGN='left' VALIGN='middle' BGCOLOR='white'>
	  <TH ALIGN='right' Bgcolor='#e7eef6'><FONT size='2'>Promotor:&nbsp;</FONT> </TH>
	  <TD ALIGN='left'><FONT size='2'>".$promotor."</FONT></TD>
        </TR>
        
        <TR ALIGN='left' VALIGN='middle' BGCOLOR='white'>
  	  <TH ALIGN='right' Bgcolor='#e7eef6'><FONT size='2'>Representante legal:&nbsp;</FONT> </TH>
	  <TD ALIGN='left'><FONT size='2'>".$rep_legal."</FONT></TD>
        </TR>
        <TR ALIGN='left' VALIGN='middle' BGCOLOR='white'>
	  <TH ALIGN='right' Bgcolor='#e7eef6'><FONT size='2'>Contacto visto bueno:&nbsp;</FONT> </TH>
	  <TD ALIGN='left'><FONT size='2'>".$contacto_bueno."</FONT></TD>
        </TR>
        <TR ALIGN='left' VALIGN='middle' BGCOLOR='white'>
	  <TH ALIGN='right' Bgcolor='#e7eef6'><FONT size='2'>Contacto de cobranza:&nbsp;</FONT> </TH>
	  <TD ALIGN='left'><FONT size='2'>".$contacto_cobra."</FONT></TD>
        </TR>
        <TR ALIGN='left' VALIGN='middle' BGCOLOR='white'>
           <TH ALIGN='right' Bgcolor='#e7eef6'><FONT size='2'>Límite de Saldo Capital:&nbsp;</FONT> </TH>
	   <TD ALIGN='left'><FONT size='2'>".$limite_saldo."</FONT></TD>
        </TR>
        <TR ALIGN='left' VALIGN='middle' BGCOLOR='white'>
	  <TH ALIGN='right' Bgcolor='#e7eef6'><FONT size='2'>Sucursal:&nbsp;</FONT> </TH>
	  <TD ALIGN='left'><FONT size='2'>".$Sucursal."</FONT></TD>
        </TR>
        <TR ALIGN='left' VALIGN='middle' BGCOLOR='white'>
          <TH ALIGN='right' Bgcolor='#e7eef6'><FONT size='2'>Forma de págo:&nbsp;</FONT> </TH>
	  <TD ALIGN='left'><FONT size='2'>".$forma_pago."</FONT></TD>
        </TR>
      </TABLE>";
?>