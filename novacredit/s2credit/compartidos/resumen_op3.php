<?/*                                                          
    _________________________________________________________________________________________
   |  Titulo: Estado de cuenta detallado             
   |                                                        
 # |  Autor : Enrique Godoy Calderón                        
## |                                                        
## |  Fecha : Tuesday, October 28, 2008                     
## |                                                        
## |  Descripción  : Estado de cuenta detallado con Intereses Devengados No Vencidos  
## |                                                        
## |  Dependencias : facturacion_mensual                      
## |                                                        
## |  Nombre original del archivo : [resumen_op3.php]     
## |                                                        
## |                                                                                        
##  ¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯
########################################################################################
######################################################################################
*/


header("Cache-Control: no-cache, must-revalidate"); 
include($DOCUMENT_ROOT."/rutas.php");
require($class_path."lib_credit.php");



$db = ADONewConnection(SERVIDOR);  # create a connection
$db->Connect(IP,USER,PASSWORD,NUCLEO);


//----------------------------------------------------------------------------------------------
if(isset($fecha))
{
        list($anio_sx, $mes_sx, $dia_sx ) =  split("-",$fecha);
}
else
{
        if( empty($dia_sx )) {$dia_sx = $D;}
        if( empty($mes_sx) ) {$mes_sx = $M;}
        if( empty($anio_sx)) {$anio_sx= $Y;}
}

if( strlen($dia_sx)==1 ) {$dia_sx = "0".$dia_sx;}
if( strlen($mes_sx)==1 ) {$mes_sx = "0".$mes_sx;}

if( ! checkdate($mes_sx, $dia_sx, $anio_sx))
{
    
        $tmp = date("Y-m-d",mktime(0, 0, 0, $mes_sx, $dia_sx, $anio_sx));
        $dia_sx = fdia($tmp );
        $mes_sx = fmes($tmp );
        $anio_sx= fanio($tmp );
        $fecha_hoy = $tmp;
}

$nueva_fecha =mktime(0,0,0,$mes_sx,$dia_sx,$anio_sx);
$fecha_hoy = $anio_sx ."-".$mes_sx."-".$dia_sx;

//----------------------------------------------------------------------------------------------
//
//----------------------------------------------------------------------------------------------
echo "<FORM NAME='edocta' ACTION='$PHP_SELF' METHOD='POST' >";

   echo " <BR><H3 Align='center'><U>Estado de cuenta</U></H3>";


echo "<TABLE ALIGN= 'center' BORDER=0  WIDTH='95%' ID='S2'> <TR BGCOLOR='white'> <TD> <FIELDSET>";

echo "<TABLE ALIGN= 'center' BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH='100%' ID='small'> ";
    echo "  <TR VALIGN='top' ID='encabezado'> ";
  
if(! isset($tipo ))
   $tipo =1;
   

   
   
   
    
   echo "  <TD ALIGN= 'center' > ";

        
        echo "<BR><B> Cambio de fecha : </B>";      
            echo "  <SELECT  NAME='dia_sx' ID='small' onChange='document.edocta.submit();'>";
            for($_dia_sx=0;$_dia_sx<=31;$_dia_sx++)
                {
                    $sel=($_dia_sx==$dia_sx)?("SELECTED"):("");
                    echo "<OPTION VALUE='$_dia_sx' $sel> $_dia_sx </OPTION>\n";
                 }
            echo "  </SELECT>/";
        
        
            echo "  <SELECT  NAME='mes_sx' ID='small' onChange='document.edocta.submit();' >";
            for($_mes_sx=1;$_mes_sx<=12;$_mes_sx++)
                {
                    $sel=($_mes_sx==$mes_sx)?("SELECTED"):("");
                    echo "<OPTION VALUE='$_mes_sx' $sel> ".substr($mes[$_mes_sx],0,3)."</OPTION>\n";
                 }
            echo "  </SELECT>/";

        
            echo "<SELECT  NAME='anio_sx' ID='small' onChange='document.edocta.submit();' >";
            for($_anio_sx=($anio_sx-10); ($_anio_sx<=$anio_sx+2); $_anio_sx++)
                {
                    $sel=($_anio_sx==$anio_sx)?("SELECTED"):("");
                    echo "<OPTION VALUE='$_anio_sx' $sel> $_anio_sx </OPTION>\n";
                 }
            echo "</SELECT>\n";         

        echo "  <INPUT TYPE='hidden' NAME='exit'        VALUE='$exit' > \n";                         
        echo "  <INPUT TYPE='hidden' NAME='factura'     VALUE='$factura'> \n"; 

        echo "  <INPUT TYPE='hidden' NAME='numcliente'  VALUE='".$numcliente    ."'> \n";         
        echo "  <INPUT TYPE='hidden' NAME='factura'     VALUE='".$factura       ."'> \n";



echo "  </TD></TR>";
echo "</TABLE>";

echo "</FIELDSET></TD></TR></TABLE><BR>";


$fecha_corte= $fecha_hoy;

    $op = new TCUENTA($factura, $fecha_corte);

    $ready =true;

    $op->ver_cabeceras = true;    
    
    $op->ver_desglose_calculos  = false;


    $op->ver_desglose_cargos    = false;
    $op->ver_desglose_abonos    = false;
    $op->ver_saldo_desglosado   = false;
    $op->ver_saldos_vencer      = false;
    $op->ver_saldo_general      = false;

$op->publica($tipo);    

        
        
if($ready)
{
        echo "<TABLE ALIGN= 'center' BORDER=0  WIDTH='95%' ID='S2'> <TR BGCOLOR='white'> <TD> <FIELDSET>";
        echo "<TABLE ALIGN= 'center' BORDER=0  CELLSPACING=0 CELLPADDING=0 WIDTH='100%' ID='S2'> ";

                echo "  <TR VALIGN='top' > ";


                echo "<TD ALIGN= 'center' Width='25%'> ";

                            $chk = ($ver_lista== 7)?("CHECKED"):("");
                            echo " <INPUT TYPE='RADIO' NAME='ver_lista'         VALUE='7' $chk  OnClick='document.edocta.submit();'>Antigüedad de saldos <br>";

                echo "</TD>";





                echo "<TD ALIGN= 'center' Width='25%'> ";

                          $chk = ($ver_lista == 2)?("CHECKED"):("");
                          echo " <INPUT TYPE='RADIO' NAME='ver_lista'           VALUE='2' $chk  OnClick='document.edocta.submit();'> Listado de abonos <br>";

                echo "</TD>";

                echo "<TD ALIGN= 'center' Width='25%'> ";

                            $chk = ($ver_lista== 5)?("CHECKED"):("");
                            echo " <INPUT TYPE='RADIO' NAME='ver_lista'         VALUE='5' $chk  OnClick='document.edocta.submit();'>Detalle de movimientos <br>";

                echo "</TD>";



                echo "<TD ALIGN= 'center' Width='25%'> ";

                            $chk = ($ver_lista== 6)?("CHECKED"):("");
                            echo " <INPUT TYPE='RADIO' NAME='ver_lista'         VALUE='6' $chk  OnClick='document.edocta.submit();'>Saldos por cuota <br>";

                echo "</TD>";




                echo "</TR>";







        echo "</TABLE>";
        echo "</FIELDSET></TD></TR></TABLE>";
}
echo "</FORM>";

if(!$ready)
  die("</BODY></HTML>");



if($ver_lista == 1)
{

  $sql = "SELECT ID_Cargo, Fecha_vencimiento, Monto, Concepto
          FROM cargos

          WHERE Num_compra = '". $op->numcompra."'and
                Fecha_vencimiento<= '".$op->fecha_corte."'
          ORDER BY Fecha_vencimiento   ";
    $rs =  $db->Execute($sql);
    $_suma_monto = 0;

    if($rs)
    {
                echo "<TABLE ALIGN= 'center' BORDER=0  WIDTH='95%' BGCOLOR='black' CELLSPACING=1 CELLPADDING=0 ID='small'>";



                echo "<TR ALIGN='center' BGCOLOR='lightsteelblue' STYLE='color:black;'>";

                echo "  <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >Num.  </TH>
                        <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >Fecha </TH>
                        <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >Monto </TH>
                        <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >Concepto  </TH>";

                echo "</TR>\n";

                   while(! $rs->EOF)
                   {


                        echo "<TR ALIGN='center' BGCOLOR='white' STYLE='color:black;'>";

                        echo "  <TD ALIGN='right'   >".$rs->fields[0]." )&nbsp;</TD>
                                <TD ALIGN='center'  >".ffecha($rs->fields[1])."</TD>
                                <TD ALIGN='right'   >".number_format($rs->fields[2],2)."&nbsp;</TD>
                                <TD ALIGN='left'    >&nbsp;".$rs->fields[3]."</TD>";

                        echo "</TR>\n";
                        $_suma_monto +=$rs->fields[2];



                     $rs->MoveNext();
                   }

                echo "<TR ALIGN='center' BGCOLOR='lightsteelblue' STYLE='color:black;'>";

                echo "  <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' ></TH>
                        <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' > </TH>
                        <TH ALIGN='right'   BGCOLOR='steelblue' STYLE='color:white;' >".number_format($_suma_monto,2)."&nbsp;</TH>
                        <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' ></TH>";
                echo "</TR>";
        echo "</TD>";
        echo "</TR>";
        echo "</TABLE>";





        }
}

//========================================================================================================================================================
if($ver_lista == 2)
{
  $sql = " (    SELECT 1, 
                      pagos.Fecha, 
                      pagos.Monto*(-1), 
                      pagos.Forma, 
                      pagos.Activo, 
                      pagos.ID_Caja_pagos,
                      CONCAT(usuarios.Nombre,' ',usuarios.AP_Paterno,' ',usuarios.AP_Materno) AS Cajero,
                      cat_caja.caja_numero,
                      time(caja_pagos.fecha_pago) AS HoraRegistro,
                      sucursales.Nombre           AS Sucursal
                
                FROM pagos

                LEFT JOIN caja_pagos    ON caja_pagos.id_caja_pagos = pagos.id_caja_pagos
                LEFT JOIN caja_apertura ON caja_apertura.id_caja_apertura = caja_pagos.id_caja_apertura
                LEFT JOIN cat_caja      ON cat_caja.id_caja = caja_apertura.id_caja 
                LEFT JOIN usuarios      ON usuarios.ID_User = cat_caja.ID_User
                LEFT JOIN sucursales    ON sucursales.ID_Sucursal = cat_caja.ID_Sucursal

		  WHERE Num_compra = '". $op->numcompra."' and
			Fecha<= '".$op->fecha_corte."'

 		)
          ORDER BY Fecha  ";




          
          
    //debug($sql);      
    $rs =  $db->Execute($sql);
    $_suma_monto = 0;
    $num = $rs->fields[0];
    
    echo "<TABLE ALIGN= 'center' BORDER=0  WIDTH='95%'  BGCOLOR='black'  CELLSPACING=1 CELLPADDING=0 ID='small'> ";

    if($rs)
    {
                echo "<TR ALIGN='center' BGCOLOR='lightsteelblue' STYLE='color:black;'>";

                echo "  <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >Folio     </TH>
                        <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >Fecha     </TH>
                        <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >Monto     </TH>


                        <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >Sucursal     </TH>

                        <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >Cajero     </TH>
                        <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >Caja No.   </TH>
                        <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >Hora de registro  </TH>
                        



                        <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >Concepto  </TH>";

                echo "</TR>\n";

                   while(! $rs->EOF)
                   {



                        $bgcolor    = ($rs->fields[4]=='S')?('white'):('silver');
                        $font_color =($rs->fields[4]=='S')?('black'):('dimgray');
                        $font2_color =($rs->fields[4]=='S')?('blue'):('dimgray');

                        echo "<TR ALIGN='center' BGCOLOR='".$bgcolor."' STYLE='color:".$font_color.";'>";


                        $folio_caja=($rs->fields[5])?(($rs->fields[5])." )&nbsp;"):("---");

                        echo "  <TD ALIGN='right'   >".$folio_caja."</TD>
                                <TD ALIGN='center'  >".ffecha($rs->fields[1])."</TD>
                                <TD ALIGN='right'   STYLE='color:".$font2_color.";' >".number_format($rs->fields[2],2)."&nbsp;</TD>\n";


                                echo "  <TD ALIGN='left'    >&nbsp;".$rs->fields['Sucursal']."</TD>";

                                echo "  <TD ALIGN='left'    >&nbsp;".$rs->fields['Cajero']."</TD>";
                                echo "  <TD ALIGN='center'  >&nbsp;".$rs->fields['caja_numero']."</TD>";
                               
                               if(! empty($rs->fields['HoraRegistro']))  $rs->fields['HoraRegistro'].= " hrs ";
                               
                                echo "  <TD ALIGN='center'  >&nbsp;".$rs->fields['HoraRegistro']." </TD>";


                                echo "   <TD ALIGN='left'    >&nbsp;".$rs->fields[3]."</TD>";

                        echo "</TR>\n";

                        if($rs->fields[4]=='S')
                           $_suma_monto +=$rs->fields[2];



                     $rs->MoveNext();
                   }

                echo "<TR ALIGN='center' BGCOLOR='lightsteelblue' STYLE='color:blue;'>";

                echo "  <TH ALIGN='right'  COLSPAN='2' > SubTotal : &nbsp;</TH>
                        <TH ALIGN='right'    >".number_format($_suma_monto,2)."&nbsp;</TH>
                        <TH ALIGN='center'   COLSPAN='5'></TH>";


        	echo "</TR>";


        }
        
        
  $sql = " (

				SELECT 
						1,
						notas_credito.Fecha,
						notas_credito.Monto*(-1),
						CONCAT('Nota crédito renovación ', notas_credito.subtipo) AS Forma,
						'S' AS Activo,
						NULL AS ID_Caja_pagos,
						TRIM(SUBSTRING(notas_credito.Usuario,45)) AS Cajero,
						NULL AS caja_numero,
						TIME(LEFT(SUBSTRING(notas_credito.Usuario,19),20)) AS HoraRegistro,

						sucursales.Nombre AS Sucursal
						FROM notas_credito

						LEFT JOIN fact_cliente ON fact_cliente.num_compra = notas_credito.num_compra
						LEFT JOIN sucursales ON fact_cliente.ID_Sucursal = sucursales.ID_Sucursal

				WHERE notas_credito.Num_compra = '". $op->numcompra."' and
				      notas_credito.ID_Concepto IN (-20,-21,-22) and 
				      notas_credito.Fecha<= '".$op->fecha_corte."' 
		)
 
		UNION
		(

				SELECT 
						1,
						notas_credito.Fecha,
						notas_credito.Monto*(-1),
						CONCAT('Nota crédito reestructura ', notas_credito.subtipo) AS Forma,
						'S' AS Activo,
						NULL AS ID_Caja_pagos,
						TRIM(SUBSTRING(notas_credito.Usuario,45)) AS Cajero,
						NULL AS caja_numero,
						TIME(LEFT(SUBSTRING(notas_credito.Usuario,19),20)) AS HoraRegistro,

						sucursales.Nombre AS Sucursal
						FROM notas_credito

						LEFT JOIN fact_cliente ON fact_cliente.num_compra = notas_credito.num_compra
						LEFT JOIN sucursales ON fact_cliente.ID_Sucursal = sucursales.ID_Sucursal

				WHERE notas_credito.Num_compra = '". $op->numcompra."' and
				      notas_credito.ID_Concepto IN (-30,-31,-32,-33,-34) and 
				      notas_credito.Fecha<= '".$op->fecha_corte."' 
		)
 
          ORDER BY Fecha  ";
        
        //debug($sql);
    $rs =  $db->Execute($sql);





    if($rs->_numOfRows>0)
    {
    		$_suma_monto1 = $_suma_monto;
    		$_suma_monto = 0;
    
    
                echo "<TR ALIGN='center' BGCOLOR='lightsteelblue' STYLE='color:black;'>";

                echo "  <TH ALIGN='center'  BGCOLOR='gray' STYLE='color:white;' COLSPAN='8'> Renovación de Crédito     </TH>";

                echo "</TR>\n";

                echo "<TR ALIGN='center' BGCOLOR='lightsteelblue' STYLE='color:black;'>";

                echo "  <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >Folio     </TH>
                        <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >Fecha     </TH>
                        <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >Monto     </TH>


                        <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >Sucursal     </TH>

                        <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >Usuario     </TH>
                        <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >   </TH>
                        <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >Hora de registro  </TH>
                        



                        <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >Concepto  </TH>";

                echo "</TR>\n";

                   while(! $rs->EOF)
                   {



                        $bgcolor    = ($rs->fields[4]=='S')?('white'):('silver');
                        $font_color =($rs->fields[4]=='S')?('black'):('dimgray');
                        $font2_color =($rs->fields[4]=='S')?('blue'):('dimgray');

                        echo "<TR ALIGN='center' BGCOLOR='".$bgcolor."' STYLE='color:".$font_color.";'>";


                        $folio_caja=($rs->fields[5])?(($rs->fields[5])." )&nbsp;"):("---");

                        echo "  <TD ALIGN='center'   > -- </TD>
                                <TD ALIGN='center'  >".ffecha($rs->fields[1])."</TD>
                                <TD ALIGN='right'   STYLE='color:".$font2_color.";' >".number_format($rs->fields[2],2)."&nbsp;</TD>\n";


                                echo "  <TD ALIGN='left'    >&nbsp;".$rs->fields['Sucursal']."</TD>";

                                echo "  <TD ALIGN='left'    >&nbsp;".$rs->fields['Cajero']."</TD>";
                                echo "  <TD ALIGN='center'  >&nbsp;".$rs->fields['caja_numero']."</TD>";
                               
                               if(! empty($rs->fields['HoraRegistro']))  $rs->fields['HoraRegistro'].= " hrs ";
                               
                                echo "  <TD ALIGN='center'  >&nbsp;".$rs->fields['HoraRegistro']." </TD>";


                                echo "   <TD ALIGN='left'    >&nbsp;".$rs->fields[3]."</TD>";

                        echo "</TR>\n";

                        if($rs->fields[4]=='S')
                           $_suma_monto +=$rs->fields[2];



                     $rs->MoveNext();
                   }

                echo "<TR ALIGN='center' BGCOLOR='lightsteelblue' STYLE='color:blue;'>";

                echo "  <TH ALIGN='right'  COLSPAN='2' > SubTotal : &nbsp;</TH>
                        <TH ALIGN='right'    >".number_format($_suma_monto,2)."&nbsp;</TH>
                        <TH ALIGN='center'   COLSPAN='5'></TH>";


        	echo "</TR>";


                echo "<TR ALIGN='center' BGCOLOR='gray' STYLE='color:white;'>";

                echo "  <TH ALIGN='right'  COLSPAN='2'> Gran Total : &nbsp;</TH>
                        <TH ALIGN='right'   >".number_format(($_suma_monto + $_suma_monto1) ,2)."&nbsp;</TH>
                        <TH ALIGN='center'  COLSPAN='5'></TH>";


        	echo "</TR>";


        }




        
    

        echo "</TABLE>";




        
}

//========================================================================================================================================================

if($ver_lista == 3)
{


                $count =  count($op->aplicacion) -1;
                if($count<0)
                  $count=0;
                $idx=0;
                for($k=$count; $k>=0; $k--)
                {

                        if($op->aplicacion[$k]['SaldoParcial'] <= 0.009)
                        {
                          $idx=($k+1);
                          break;
                          debug("idx : ".$idx);
                        }
                }



                echo "<TABLE ALIGN= 'center' BORDER=0  WIDTH='95%'  BGCOLOR='black'  CELLSPACING=1 CELLPADDING=0 ID='small'> ";



                echo "<TR ALIGN='center' BGCOLOR='lightsteelblue' STYLE='color:black;'>";

                echo "  <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >Num.  </TH>
                        <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >Fecha </TH>
                        <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >Concepto  </TH>
                        <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >Días vencidos      </TH>
                        <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >Saldo Cuota       </TH>
                        <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >Saldo Global      </TH>";

                echo "</TR>\n";
                $num=1;
                for($j=$idx; $j<count($op->aplicacion); $j++ )
                {
                  $row= $op->aplicacion[$j];
                  if(!empty($row['Fecha']))
                  {





                         if ($row['Tipo']=='Cargo')
                                 $primer_cargo=true;

                        $color=($color=='white')?('lavender'):('white');

                        if (($row['Tipo']=='Saldo') )
                        {
                                $color = 'lime';

                        }

                        echo "<TR  BGCOLOR='".$color."'  ID='small'>      \n";

                        echo "<TD ALIGN='right'   >".($num++)." )&nbsp;</TD> \n";

                        echo "<TD ALIGN='center'  >".ffecha($row['Fecha'])."</TD>     \n";


                        echo "<TD ALIGN='left'    > ".$row['Concepto']."</TD>     \n";

                        if(!$row['DiasAtraso'])
                            echo "<TH  ALIGN='right' >0</TH>     \n";
                        else
                            echo "<TH  ALIGN='right' >".$row['DiasAtraso']."</TH>     \n";


                       echo "<TH ALIGN='right'       >".number_format($row['SaldoParcial'],2)." </TH>     \n";


                       $style = ($row['SALDO_General']<0)?(" STYLE='color:blue;' "):("");
                       echo "<TH ALIGN='right'  ".$style.">".number_format($row['SALDO_General'],2). "</TH>     \n";





                        echo "</TR>\n";



                    // $rs->MoveNext();
                   }
                  }

                echo "<TR ALIGN='center' BGCOLOR='lightsteelblue' STYLE='color:black;'>";

                echo "  <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >&nbsp;</TH>
                        <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >&nbsp;</TH>
                        <TH ALIGN='right'   BGCOLOR='steelblue' STYLE='color:white;'  >&nbsp;</TH>
                        <TH ALIGN='right'   BGCOLOR='steelblue' STYLE='color:white;'  >".$op->dias_mora."</TH>
                        <TH ALIGN='right'   BGCOLOR='steelblue' STYLE='color:white;'  >".number_format($row['SaldoParcial'],2)."</TH>
                        <TH ALIGN='right'   BGCOLOR='steelblue' STYLE='color:white;'  >".number_format($row['SALDO_General'],2). "</TH>";

                echo "</TR>";
        echo "</TD>";
        echo "</TR>";
        echo "</TABLE>";


        echo "</TABLE><BR><BR>";

}

if($ver_lista == 4)
{


                $count =  count($op->aplicacion) -1;
                if($count<0)
                  $count=0;
                $idx=0;
                for($k=$count; $k>=0; $k--)
                {

                        if($op->aplicacion[$k]['SaldoParcial'] <= 0.009)
                        {
                          $idx=($k+1);
                          break;
                          debug("idx : ".$idx);
                        }
                }



                echo "<TABLE ALIGN= 'center' BORDER=0  WIDTH='95%'  BGCOLOR='black'  CELLSPACING=1 CELLPADDING=0 ID='small'> ";



                echo "<TR ALIGN='center' BGCOLOR='lightsteelblue' STYLE='color:black;'>";

                echo "  <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >Num.  </TH>
                        <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >Fecha </TH>
                        <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >Concepto  </TH>
                        <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >Días vencidos      </TH>
                        <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >Saldo Cuota       </TH>
                        <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >Saldo Global      </TH>";

                echo "</TR>\n";
                $num=1;
                for($j=$idx; $j<count($op->aplicacion); $j++ )
                {
                  $row= $op->aplicacion[$j];
                  if(!empty($row['Fecha']))
                  {





                         if ($row['Tipo']=='Cargo')
                                 $primer_cargo=true;

                        $color=($color=='white')?('lavender'):('white');

                        if (($row['Tipo']=='Saldo') )
                        {
                                $color = 'lime';

                        }

                        echo "<TR  BGCOLOR='".$color."'  ID='small'>      \n";

                        echo "<TD ALIGN='right'   >".($num++)." )&nbsp;</TD> \n";

                        echo "<TD ALIGN='center'  >".ffecha($row['Fecha'])."</TD>     \n";


                        echo "<TD ALIGN='left'    > ".$row['Concepto']."</TD>     \n";

                        if(!$row['DiasAtraso'])
                            echo "<TH  ALIGN='right' >0</TH>     \n";
                        else
                            echo "<TH  ALIGN='right' >".$row['DiasAtraso']."</TH>     \n";


                       echo "<TH ALIGN='right'       >".number_format($row['SaldoParcial'],2)." </TH>     \n";


                       $style = ($row['SALDO_General']<0)?(" STYLE='color:blue;' "):("");
                       echo "<TH ALIGN='right'  ".$style.">".number_format($row['SALDO_General'],2). "</TH>     \n";





                        echo "</TR>\n";


                   }
                  }

                  $x=count($op->aplicacion)-1;
                  $saldo_general = $op->aplicacion[$x]['SALDO_General'];



                 $sql = "(       SELECT

                                     pagos.fecha                                                        AS Fecha,
                                     pagos.Monto * (-1)                                                 AS Monto,
                                     pagos.Id_pago                                                      AS ID,
                                     IF(conceptos.Descripcion IS NULL,'!',conceptos.Descripcion)        AS Descripcion,
                                     pagos.ID_Concepto                                                  AS ID_Concepto,
                                     1                                                                  AS ORD


                                 FROM pagos
                                 LEFT JOIN conceptos ON pagos.id_concepto = conceptos.id_concepto

                                 WHERE num_compra        = '".$op->numcompra."'      and
                                       Fecha            > '".$op->fecha_corte."'    and
                                       Activo            = 'S'    and
                                       conceptos.Forma = 'Efectivo'\n";


                if(count($op->abonos_desde))
                {
                        $lista = implode("','",$op->abonos_desde);
                        $sql .= " and pagos.Id_pago    NOT IN  ('".$lista."')  ";

                }


                $sql .= "    ORDER BY pagos.Fecha, pagos.ID_Pago )

                                UNION
                                (SELECT             cargos.Fecha_vencimiento                          AS Fecha,
                                                    cargos.Monto                                      AS Monto,
                                                    cargos.ID_Cargo                                   AS ID,
                                                    Concepto                                          AS Concepto,
                                                    cargos.ID_Concepto                                AS ID_Concepto,
                                                     0                                                AS ORD

                                         FROM cargos
                                         WHERE num_compra = '".$op->numcompra."' and
                                               cargos.Fecha_vencimiento  > '".$op->fecha_corte."'        and
                                               cargos.Activo='Si' )

                                        ORDER BY Fecha, ORD  ";

                 //debug($sql);


                 // $sql = "SELECT Fecha_vencimiento, Monto, Concepto, ID_Cargo FROM cargos WHERE Fecha_vencimiento > '".$fecha_corte."' and num_compra = '".$op->numcompra."' and id_concepto=-3";


                  $rs=$db->Execute($sql);

                if($rs)
                   while(! $rs->EOF)
                   {
                        $color=($color=='white')?('lavender'):('white');
                        echo "<TR  BGCOLOR='".$color."'  ID='small'>      \n";

                        echo "<TD ALIGN='right'   >".($num++)." )&nbsp;</TD> \n";
                        echo "<TD ALIGN='center'  >".ffecha($rs->fields[0])."</TD>     \n";
                        echo "<TD ALIGN='left'    >".$rs->fields[3]."</TD>     \n";
                        echo "<TH  ALIGN='right' >0</TH>     \n";


                        $fntcolor=($rs->fields[1]<0)?('blue'):('black');
                        echo "<TH ALIGN='right'         STYLE='color:".$fntcolor.";'     >".number_format($rs->fields[1],2)." </TH>     \n";


                       $saldo_general += $rs->fields[1];
                       $style = ($saldo_general<0)?(" STYLE='color:blue;' "):("");

                       echo "<TH ALIGN='right'  ".$style.">".number_format($saldo_general,2). "</TH>     \n";





                        echo "</TR>\n";


                        if($rs->fields['ID_Concepto'] == -3)
                        {

                                $zql = "SELECT  notas_credito.fecha                                                             AS Fecha,
                                                notas_credito.Monto * (-1)                                                      AS Monto,
                                                notas_credito.ID_Nota                                                           AS ID,
                                                IF(conceptos.Descripcion IS NULL,'!',conceptos.Descripcion)                     AS Concepto,
                                                notas_credito.ID_Concepto                                                       AS ID_Concepto


                                        FROM notas_credito
                                        LEFT JOIN conceptos ON notas_credito.id_concepto = conceptos.id_concepto

                                        WHERE   num_compra       = '".$op->numcompra."'         and
                                                Fecha           > '".$op->fecha_corte."'        and
                                                ID_Cargo         = '".$rs->fields['ID']."'      ";


                                $rz=$db->Execute($zql);
                                if($rz->_numOfRows)
                                   while(! $rz->EOF)
                                   {
                                        $color=($color=='white')?('lavender'):('white');
                                        echo "<TR  BGCOLOR='azure'  ID='small'>      \n";

                                        echo "<TD ALIGN='right'   >".($num++)." )&nbsp;</TD> \n";
                                        echo "<TD ALIGN='center'  >".ffecha($rz->fields[0])."</TD>     \n";
                                        echo "<TD ALIGN='left'    >".$rz->fields[3]."</TD>     \n";
                                        echo "<TH  ALIGN='right' >0</TH>     \n";
                                        $fntcolor=($rz->fields[1]<0)?('blue'):('black');
                                        echo "<TH ALIGN='right'         STYLE='color:".$fntcolor.";'     >".number_format($rz->fields[1],2)." </TH>     \n";


                                        $saldo_general += $rz->fields[1];
                                        $style = ($saldo_general<0)?(" STYLE='color:blue;' "):("");

                                        echo "<TH ALIGN='right'  ".$style.">".number_format($saldo_general,2). "</TH>     \n";

                                        echo "</TR>\n";
                                        $rz->MoveNext();


                                }
                        }


                     $rs->MoveNext();
                   }


                echo "<TR ALIGN='center' BGCOLOR='lightsteelblue' STYLE='color:black;'>";

                echo "  <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >&nbsp;</TH>
                        <TH ALIGN='center'  BGCOLOR='steelblue' STYLE='color:white;' >&nbsp;</TH>
                        <TH ALIGN='right'   BGCOLOR='steelblue' STYLE='color:white;'  >&nbsp;</TH>
                        <TH ALIGN='right'   BGCOLOR='steelblue' STYLE='color:white;'  >".$op->dias_mora."</TH>
                        <TH ALIGN='right'   BGCOLOR='steelblue' STYLE='color:white;'  ></TH>
                        <TH ALIGN='right'   BGCOLOR='steelblue' STYLE='color:white;'  >".number_format($saldo_general,2). "</TH>";

                echo "</TR>";
        echo "</TD>";
        echo "</TR>";
        echo "</TABLE>";


        echo "</TABLE><BR><BR>";

        //debug($sql);

}

if($ver_lista == 5)
{
    $op->ver_cabeceras = false;


    $op->ver_desglose_calculos  = true;


    if($ver_lista_abonos == 1)
    $op->ver_desglose_abonos    = true;
    else
    $op->ver_desglose_abonos    = false;


   if($ver_lista_vencidos == 1)
   {
            $op->ver_desglose_cargos    = true;
            $op->ver_saldo_desglosado   = true;
   }
   else
   {
            $op->ver_desglose_cargos    =  false;
            $op->ver_saldo_desglosado   =  false;


   }

 if($ver_lista_por_vencer== 1)
 {

    $op->ver_saldos_vencer      = true;
    $op->ver_saldo_general      = true;
 }
 else
 {
    $op->ver_saldos_vencer      = false;
    $op->ver_saldo_general      = false;

 }


        $op->publica($tipo, 0);


        echo "<BR><BR>";

}


if($ver_lista == 6)
{
    $op->ver_cabeceras          = false;
    $op->ver_desglose_calculos  = false;
    $op->ver_saldo_desglosado   = false;
    $op->ver_saldos_vencer      = false;
    $op->ver_saldo_general      = false;
    $op->ver_saldos_vencer      = false;


    $op->ver_saldos_por_cuota    = true;



        $op->publica($tipo, 0);


        echo "<BR><BR>";

}



if($ver_lista == 7)
{

  
           $row = $op->obtener_antiguedad_saldos();
           echo "<TABLE ALIGN='center'  BORDER=0 BGCOLOR='black' ID='small' CELLSPACING=1 CELLPADDING=1 ID='small'>\n";
  
             echo "<TR STYLE='color:white;' >
                <TH BGCOLOR='steelblue'  WIDTH='300px;'>        Morosidad </TH>
                <TH BGCOLOR='steelblue'  WIDTH='300px;'>        Saldo     </TH>
                <TH BGCOLOR='steelblue'  WIDTH='300px;'>        Acumulado </TH>
                
                </TR>\n";

  
           echo "<TR><TH ALIGN='left'   BGCOLOR='steelblue'>    0 días                  </TH><TH ALIGN='right'  BGCOLOR='white'>".number_format($row[0],2)."</TH><TH ALIGN='right'  BGCOLOR='white'>".number_format(($row[0]+$row[1]+$row[2]+$row[3]+$row[4]+$row[5]+$row[6]),2)."</TH></TR>\n";
           echo "<TR><TH ALIGN='left'   BGCOLOR='steelblue'>    1 a 7 días              </TH><TH ALIGN='right'  BGCOLOR='white'>".number_format($row[1],2)."</TH><TH ALIGN='right'  BGCOLOR='white'>".number_format(($row[1]+$row[2]+$row[3]+$row[4]+$row[5]+$row[6]),2)."</TH></TR>\n";
           echo "<TR><TH ALIGN='left'   BGCOLOR='steelblue'>    8 a 30 días             </TH><TH ALIGN='right'  BGCOLOR='white'>".number_format($row[2],2)."</TH><TH ALIGN='right'  BGCOLOR='white'>".number_format(($row[2]+$row[3]+$row[4]+$row[5]+$row[6]),2)."</TH></TR>\n";
           echo "<TR><TH ALIGN='left'   BGCOLOR='steelblue'>    31 a 60 días            </TH><TH ALIGN='right'  BGCOLOR='white'>".number_format($row[3],2)."</TH><TH ALIGN='right'  BGCOLOR='white'>".number_format(($row[3]+$row[4]+$row[5]+$row[6]),2)."</TH></TR>\n";
           echo "<TR><TH ALIGN='left'   BGCOLOR='steelblue'>    61 a 90 días            </TH><TH ALIGN='right'  BGCOLOR='white'>".number_format($row[4],2)."</TH><TH ALIGN='right'  BGCOLOR='white'>".number_format(($row[4]+$row[5]+$row[6]),2)."</TH></TR>\n";
           echo "<TR><TH ALIGN='left'   BGCOLOR='steelblue'>    91 a 120 días           </TH><TH ALIGN='right'  BGCOLOR='white'>".number_format($row[5],2)."</TH><TH ALIGN='right'  BGCOLOR='white'>".number_format(($row[5]+$row[6]),2)."</TH></TR>\n";
           echo "<TR><TH ALIGN='left'   BGCOLOR='steelblue'>    121 ó más días          </TH><TH ALIGN='right'  BGCOLOR='white'>".number_format($row[6],2)."</TH><TH ALIGN='right'  BGCOLOR='white'>".number_format($row[6],2)."</TH></TR>\n";

           echo "<TR STYLE='color:white;'   >
                 <TH BGCOLOR='steelblue'>       Saldo general vencido   </TH>
                 <TH ALIGN='right'  BGCOLOR='steelblue'>".number_format(($row[0]+$row[1]+$row[2]+$row[3]+$row[4]+$row[5]+$row[6]),2)."</TH>
                 <TH ALIGN='right'  BGCOLOR='steelblue'>".number_format(($row[0]+$row[1]+$row[2]+$row[3]+$row[4]+$row[5]+$row[6]),2)."</TH></TR>\n";

           echo "</TABLE>";      

        echo "<BR><BR>";


}

























?>
</BODY>
</HTML>
