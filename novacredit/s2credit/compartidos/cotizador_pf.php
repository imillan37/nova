<?
/*
    _________________________________________________________________________________________
   |  Titulo: Asignación de línea de crédito
   |
 # |  Autor : Enrique Godoy Calderón
## |
## |  Fecha : Tuesday, October 28, 2008
## |
## |  Descripción  : Asigna número de cliente y línea de crédito a solicitudes nuevas.
## |
## |  Dependencias : menú principal
## |
## |  Nombre original del archivo : [asignar_linea.php]
## |
## |
##  ¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯
########################################################################################
######################################################################################
*/
$exit=1;
header("Cache-Control: no-cache, must-revalidate");
include($DOCUMENT_ROOT."/rutas.php");
require($class_path."lib_credit.php");
require($class_path."lib_nuevo_credito.php");


$db = &ADONewConnection(SERVIDOR);
$db->PConnect(IP,USER,PASSWORD,NUCLEO);

/*if(empty($id))
{
        error_msg("Error : parámetros incompletos.");
        die("</BODY></HTML>");
}*/

//verflujo();
//die();


$sql = "SELECT Nombre, TasaMensual, Plazo_Minimo, Vencimiento, Capital_Min, Metodo, IVA_Interes, IVA_Comision, Comision_Apertura, Plazo_Maximo
                FROM cat_productosfinancieros
                WHERE ID_Producto = '".$id."' ";

$rs = $db->Execute($sql);
$IVA_Comision      = $rs->fields[7];
$Comision_Apertura = $rs->fields[8];



echo "<H1 Align='center' ID='big'><U>Producto financiero : ".strtoupper($rs->fields[0])."</U></H1> <BR>";

echo "<FORM METHOD='POST' ACTION='".$PHP_SELF."' NAME='cotiza' >\n";
echo "<INPUT TYPE='HIDDEN'      NAME='num_cliente'       VALUE='".$num_cliente."'       > \n";




echo "<TABLE ALIGN='center' BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH='95%' ID='small'> \n";







echo "  <TR ALIGN='left' VALIGN='middle' >\n";
echo "          <TH Width='35%' ALIGN='right'> Fecha de inicio  :  </TH>\n";

                                $diax  =empty($diax )?($D):($diax  );
                                $mesx=empty($mesx)?($M):($mesx);
                                $aniox=empty($aniox)?($Y):($aniox);


                                $diax   =(strlen($diax)<2)?("0".$diax):($diax);
                                $mesx   =(strlen($mesx)<2)?("0".$mesx):($mesx);


        echo "          <TH>&nbsp;&nbsp;\n";




                                $diax=(!isset($diax))?($D):($diax);
                                echo "<SELECT NAME='diax' ID='S4'>\n";
                                for($x=1; $x<=28; $x++)
                                {
                                   $chk = ($diax == $x)?("SELECTED"):("");
                                   $xx = ($x<=9)?("0".$x):($x);

                                   echo "\t <OPTION VALUE='".$x."' ".$chk."> ".$xx." </OPTION>\n";
                                }
                                echo "</SELECT>\n";

                                $mesx=(!isset($mesx))?($M):($mesx);
                                echo "<SELECT NAME='mesx' ID='S4'>\n";
                                for($y=1; $y<=12; $y++)
                                {
                                   $chk = ($mesx == $y)?("SELECTED"):("");
                                   echo "\t <OPTION VALUE='".$y."' ".$chk."> ".$mes[($y*1)]." </OPTION>\n";
                                }
                                echo "</SELECT>\n";

                                $aniox=(!isset($aniox))?($Y):($aniox);
                                echo "<SELECT NAME='aniox' ID='S4'>\n";
                                for($z=($Y-10); $z<=($Y+10); $z++)
                                {
                                   $chk = ($aniox == $z)?("SELECTED"):("");
                                   echo "\t <OPTION VALUE='".$z."' ".$chk."> ".$z." </OPTION>\n";
                                }
                                echo "</SELECT>\n";


        echo "</TH>\n";

echo "  </TR>\n";

echo "  <TR ALIGN='left' VALIGN='middle' >\n";

if(empty($calcular) and empty($capital) )
  $capital= $rs->fields[4];

echo "  <TR ALIGN='left' VALIGN='middle' >\n";
echo "          <TH Width='35%' ALIGN='right' ID='msg' >Indique el monto del capital de crédito : </TH>\n";
echo "          <TH >&nbsp;&nbsp;&nbsp;<INPUT TYPE='TEXT'  NAME='capital'  VALUE='".$capital."' ID='S4' OnBlur='submit();'></TH>\n";
echo "  </TR>\n";



$plazo_min=$rs->fields['Plazo_Minimo'];
$plazo_max=$rs->fields['Plazo_Maximo'];



       $sql = " SELECT  ID_Producto, Nombre, TasaMensual, Plazo_Minimo, Plazo_Maximo, Vencimiento
                                FROM    cat_productosfinancieros
                                WHERE   '".number_format($capital,2,".","")."'
                                BETWEEN Capital_Min AND Capital_Max    ";



                 $rs=$db->Execute($sql);

echo "  <TR ALIGN='left' VALIGN='middle' >\n";
echo "          <TH Width='35%' ALIGN='right' ID='msg' >Indique producto financiero : </TH>\n";
echo "          <TH >&nbsp;&nbsp;&nbsp;";


                 echo "<SELECT NAME='id'  OnChange='submit();' ID='small'>\n";
                 if($rs->_numOfRows)
                 {
                        if(empty($id))
                           $id= $rs->fields[0];

                        while(! $rs->EOF)
                        {
                                $sel ="";
                                if($id == $rs->fields[0])
                                {
                                   $sel = "SELECTED";

                                }


                                echo "<OPTION value='".$rs->fields[0]."' ".$sel." >".strtoupper($rs->fields[1])." </OPTION>\n";
                                $rs->MoveNext();
                        }
                   }
                   else
                   {
                           echo "<OPTION value='0'  >Debe especificar un monto de capital </OPTION>\n";
                           echo "</SELECT> ";
                            echo "</FORM> ";
                            echo "</DIR> ";
                            echo "</BODY> </HTML>";

                   }
                    echo "</SELECT> ";

echo "          </TH>\n";
echo "  </TR>\n";





       $sql = " SELECT  Plazo_Minimo, Plazo_Maximo, Vencimiento
                                FROM    cat_productosfinancieros
                                WHERE   ID_Producto = '".$id."'";
       $rs=$db->Execute($sql);



        $plazo_min=$rs->fields['Plazo_Minimo'];
        $plazo_max=$rs->fields['Plazo_Maximo'];



echo "  <TR ALIGN='left' VALIGN='middle' >\n";
echo "          <TH Width='35%' ALIGN='right' ID='msg' >Plazo : </TH>\n";
echo "          <TH >&nbsp;&nbsp;&nbsp;";


                 echo "<SELECT NAME='vencimientos'  OnChange='submit();' ID='small'>\n";


                for($j=$plazo_min; $j<= $plazo_max; $j++)
                {
                    $sel = ($j==$vencimientos )?("Selected"):("");
                    echo "<OPTION value='".$j."' ".$sel." >".$j." </OPTION>\n";


                }

                echo "</SELECT> ".$rs->fields['Vencimiento'].". &nbsp;&nbsp;&nbsp;";





echo "         </TH>\n";
echo "  </TR>\n";








//====================================================================================================================//






echo "  <TR ALIGN='center' VALIGN='middle'>\n";
echo "          <TH COLSPAN='2'>&nbsp;<BR></TH>\n";
echo "  </TR>\n";



echo "  <TR ALIGN='center' VALIGN='middle'>\n";
echo "          <TH COLSPAN='2'><BR><BR><INPUT TYPE='Submit'  NAME='calcular'  VALUE='Calcular' ID='S2'></TH>\n";
echo "  </TR>\n";






echo "</TABLE> \n";
echo "</FORM>\n";

if( empty($vencimientos) or  empty($capital)  )
        die("\n</BODY>\n</HTML>\n");


///////////////////////////////////////////////////////////////////////////////////////////////

if((empty($id)) or  (empty($vencimientos)))
{

        die("</BODY></HTML>");
}
$fecha_de_inicio = $aniox."-".$mesx."-".$diax;

 $id_sucursal = $_SESSION['ID_SUC'];


         $genera = new TNuevoCredito($num_cliente, $fecha_hoy, $fecha_de_inicio , $capital, $id, $vencimientos, $db, $id_sucursal );
// debug(" genera = new TNuevoCredito($num_cliente, $fecha_hoy, $fecha_de_inicio , $capital, $id, $vencimientos,  db, $id_sucursal );");


echo "  <BR><BR><BR>\n";

                                echo "<H2 Align='center'><U> Cotización de crédito </U></H2>";

                                echo "<TABLE ALIGN='center' CELLSPACING=0 CELLPADDING=0 ID='small'  WIDTH='800px'>   \n";
                                echo "<TR>    \n";
                                echo "<TD bgcolor='lightsteelblue' >    \n";
                                echo "<FIELDSET>    \n";
                                echo "<TABLE ALIGN='center' CELLSPACING=1 CELLPADDING=2 ID='small' bgcolor='white' WIDTH='100%'>   \n";
                                if(!empty($genera->nombre_cliente))
                                {
                                        echo "<TR>    \n";
                                        echo "<TH Align='right' Width='170px'>Nombre del acreditado : </TH><TD>  ".$genera->nombre_cliente."</TD>\n";
                                        echo "</TR>    \n";
                                }
                                echo "<TR>";
                                echo "<TH Align='right' Width='170px'> Producto Financiero :</TH><TD> ".strtoupper($genera->producto_financiero)."</TD>\n";


                                echo "<TR></TR>    \n";
                                echo "<TH Align='right' Width='170px'>Plazo : </TH><TD>".$genera->plazo ." ".$genera->periodo   ."</TD>\n";

                                echo "<TR></TR>    \n";
                                echo "<TH Align='right' Width='170px'> Capital :</TH><TD> ".number_format(($genera->capital),2)."</TD>\n";

                                echo "<TR></TR>    \n";
                                echo "<TH Align='right' Width='170px'> Pago fijo :</TH><TD> ".number_format(($genera->renta),2)."</TD>\n";

                                if($genera->esquema == 1)
                                {
                                        echo "<TR></TR>    \n";
                                        echo "<TH Align='right' Width='170px'> Vencimientos :</TH><TD> ".number_format(($genera->num_vencimientos),0)."</TD>\n";
                                }

                                if($genera->metodo == 'Saldos Insolutos')
                                {

                                                if($genera->tipo_plazox == 'mensual')
                                                {
                                                        echo "<TR></TR>    \n";
                                                        echo "<TH Align='right' Width='170px'> Tasa ".$genera->tipo_plazox." :</TH><TD> ".number_format(($genera->tasa_periodo_ssi* 100),16)."%</TD>\n";
                                                }
                                                else
                                                {

                                                        echo "<TR></TR>    \n";
                                                        echo "<TH Align='right' Width='170px'> Tasa mensual : </TH><TD> ".number_format(($genera->tasa_mensual_ssi* 100),12)."%</TD>\n";

                                                        echo "<TR></TR>    \n";
                                                        echo "<TH Align='right' Width='170px'> Tasa ".$genera->tipo_plazox." : </TH><TD> ".number_format(($genera->tasa_periodo_ssi* 100),12)."%</TD>\n";

                                                }
                                }
                                else
                                {

                                                if($genera->tipo_plazox != 'mensual')
                                                {
                                                        echo "<TR></TR>    \n";
                                                        echo "<TH Align='right' Width='170px'> Tasa ".$genera->tipo_plazox." : </TH><TD> ".number_format(($genera->tasa_periodo_ssol* 100),8)."%</TD>\n";
                                                }
                                                else
                                                {
                                                        echo "<TR></TR>    \n";
                                                        echo "<TH Align='right' Width='170px'> Tasa mensual :</TH><TD> ".number_format(($genera->tasa_mensual_ssol* 100),8)."%</TD>\n";

                                                        echo "<TR></TR>    \n";
                                                        echo "<TH Align='right' Width='170px'> Tasa ".$genera->tipo_plazox." : </TH><TD> ".number_format(($genera->tasa_periodo_ssol* 100),8)."%</TD>\n";


                                                }



                                }

//                              echo "<TH Align='right' Width='170px'> C.A.T. informativo : </TH><TD> ".number_format(($genera->cat * 100),2)   ."%</TD>\n";
                                echo "</TR> \n";
                                echo "</TABLE> \n";
                                echo "</FIELDSET>    \n";
                                echo "</TD>    \n";
                                echo "</TR>    \n";

                                echo "</TABLE><BR> \n";






                                echo "<TABLE ALIGN='center' CELLSPACING=0 CELLPADDING=0 ID='small'  WIDTH='800px'>   \n";
                                echo "<TR>    \n";
                                echo "<TD bgcolor='lightsteelblue' >    \n";
                                echo "<FIELDSET>    \n";
                                echo "<TABLE ALIGN='center' CELLSPACING=1 CELLPADDING=2 ID='small' bgcolor='lightsteelblue' WIDTH='100%'>   \n";
/*
                                echo "<TR  bgcolor='lightsteelblue'>  \n";
                                echo "<TH  ALIGN='left' COLSPAN='9'>";

                                $msj = ($default_redondeo != $genera->redondeocifras)?("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<SPAN STYLE='color:red;'> -- ATENCIÓN : Ésta vista no representa la opción predeterminada.</SPAN>"):("");

                                if($genera->redondeocifras)
                                {
                                        echo "<INPUT TYPE='CHECKBOX'  onClick='document.simulafin.verconredondeo.value=\"-1\"; document.simulafin.genera.value=\"1\";    document.simulafin.submit();'> Visualizar tabla de amortización <SPAN STYLE='color:blue;'>sin</SPAN> redondeo de cifras. ".$msj;
                                }
                                else
                                {
                                        echo "<INPUT TYPE='CHECKBOX'  onClick='document.simulafin.verconredondeo.value=\"+1\"; document.simulafin.genera.value=\"1\";    document.simulafin.submit();'> Visualizar tabla de amortización <SPAN STYLE='color:blue;'>con</SPAN> redondeo de cifras.".$msj;

                                }
                                echo "</TH>  \n";

                                echo "</TR>  \n";
*/
                                echo "<TR  bgcolor='gray'>  \n";
                                echo "<TH  ALIGN='left' COLSPAN='9' STYLE='height:2px;'></TH>  \n";
                                echo "</TR>  \n";

                                echo "<TR  bgcolor='lightsteelblue'>  \n";
                                echo "        <TH>No. Vencimiento     </TH>  \n";
                                echo "        <TH>Fecha de Vencimiento</TH>  \n";

                                echo "        <TH>Capital     </TH>  \n";

                                echo "        <TH>Comisiones    </TH>  \n";
                                echo "        <TH>IVA Comisiones     </TH>  \n";



                                echo "        <TH>Intereses     </TH>  \n";
                                echo "        <TH>IVA  Intereses     </TH>  \n";

                                echo "        <TH>Pago fijo           </TH>  \n";
                                echo "        <TH>Saldo de Capital    </TH>  \n";
                                echo "</TR>  \n";

                                foreach($genera->tabla_amortizacion AS $i=>$row)
                                {

                                                                echo "  <TR Align='right'  BGCOLOR='white'>
                                                                                                <TH Align='center'> ".$i.")    </TH>
                                                                                                <TD Align='center'> ".$row['FECHA']."</TD>\n";
                                                                echo "                          <TD>".number_format($row['ABONO_CAPITAL']  ,4)."</TD>";
                                                                echo "                          <TD>".number_format($row['COMISION']       ,4)."</TD>\n
                                                                                                <TD>".number_format($row['ABONO_IVA_COMISION']     ,4)."</TD>

                                                                                                <TD>".number_format($row['ABONO_INTERES']          ,4)."</TD>
                                                                                                <TD>".number_format($row['ABONO_IVA_INTERES']      ,4)."</TD>
                                                                                                <TD>".number_format($row['RENTA']                  ,4)."</TD>
                                                                                                <TD>".number_format($row['SALDO_CAPITAL']          ,4)."</TD>
                                                                                </TR>\n";
                                }


                                echo "  <TR ALIGN='right'>";
                                echo "  <TD COLSPAN='2'></TD>";
                                echo "  <TD>".number_format($genera->tot_abono_capital,  4)."</TD>";

                                echo "  <TD>".number_format($genera->tot_abono_comision, 4)."</TD>";
                                echo "  <TD>".number_format($genera->tot_abono_iva_comision,      4)."</TD>";

                                echo "  <TD>".number_format($genera->tot_abono_interes,  4)."</TD>";
                                echo "  <TD>".number_format($genera->tot_abono_iva_interes,      4)."</TD>";
                                echo "  <TD>".number_format($genera->tot_abonos,         4)."</TD>";
                                echo "  </TR>";



                                echo "</TABLE> \n";
                                echo "</FIELDSET>    \n";
                                echo "</TD>    \n";
                                echo "</TR>    \n";

                        echo "</TABLE> \n";


/*
echo "  <TABLE ALIGN='center' CELLSPACING=0 CELLPADDING=0 ID='small'  WIDTH='800px'> ";
echo "  <TR>";
echo "  <TD bgcolor='lightsteelblue' >";
echo "  <FIELDSET>";
echo "  <TABLE ALIGN='center' CELLSPACING=1 CELLPADDING=2 ID='small' bgcolor='lightsteelblue' WIDTH='100%'>

        <TR  bgcolor='lightsteelblue'>
                <TH>No. Vencimiento     </TH>
                <TH>Fecha de Vencimiento</TH>
                <TH>Abono a comisión     </TH>
                <TH>Abono a capital     </TH>
                <TH>Abono a interés    </TH>
                <TH>Abono IVA           </TH>
                <TH>Pago fijo           </TH>
                <TH>Saldo de Capital    </TH>
        </TR>\n";



$_renta = 0;

$fecha   = $diax."/".$mesx."/".$aniox;

for ($i=0; $i<=$vencimientos_eq; $i++)
{


        if($i)
        {
                $abono_interes = $saldo_capital * $tasaeq ;
                $abono_iva     = $abono_interes * $iva;
                $abono_capital = $renta - ($abono_interes + $abono_iva);
                $abono_capital = ($abono_capital <0 )?(0):($abono_capital);



                $fecha  = fechavencimiento($fecha, $frecuencia);



                $_renta = $renta;

                $tot_abono_capital +=    $abono_capital ;
                $tot_abono_interes +=    $abono_interes ;
                $tot_abono_iva     +=    $abono_iva    ;
                $tot_abonos        +=    $_renta;


        }
        else
        {
                           $_renta = $Comision_Apertura * (1+$IVA_Comision/100);
                           $abono_iva             +=  $Comision_Apertura  *  $IVA_Comision/100;
                           $tot_abono_iva     +=  $Comision_Apertura  *  $IVA_Comision/100;
                           $tot_abono_comision+=$Comision_Apertura;
                           $tot_abonos        +=    $_renta;
        }
        $saldo_capital = $saldo_capital - $abono_capital;



        if($saldo_capital <0.01)
           $saldo_capital = 0;

        echo "  <TR Align='right'  BGCOLOR='white'>
                        <TH Align='center'> $i)    </TH>
                        <TD Align='center'> $fecha </TD>\n";


        if(!$i)
                echo "          <TD>".number_format($Comision_Apertura,2)."</TD>\n";
        else
                echo "          <TD></TD>\n";

        echo "          <TD>".number_format($abono_capital,2)."</TD>
                        <TD>".number_format($abono_interes,2)."</TD>
                        <TD>".number_format($abono_iva,2    )."</TD>
                        <TD>".number_format($_renta,2        )."</TD>
                        <TD>".number_format($saldo_capital,2)."</TD>
                </TR>\n";


}

        echo "  <TR Align='right'  bgcolor='lightsteelblue'>
                        <TD Align='center'> -  </TD>
                        <TD Align='center'> - </TD>
                        <TH>".number_format($tot_abono_comision,2)."</TH>
                        <TH>".number_format($tot_abono_capital,2)."</TH>
                        <TH>".number_format($tot_abono_interes,2)."</TH>
                        <TH>".number_format($tot_abono_iva    ,2)."</TH>
                        <TH>".number_format($tot_abonos       ,2)."</TH>
                        <TD Align='center'> - </TD>
                </TR>\n";

echo "  </TABLE>\n";


echo "  </FIELDSET>\n";
echo "  </TD>\n";
echo "  </TR>\n";
echo "  </TABLE>\n";
*/

echo "  <BR><BR><BR>";



?>

</BODY>
</HTML>


<?
//------------------------------------------------------------------------------------------------- //
//Obtener el interes equivalente semanal/quincenal/anual  dado un mensual. SALDOS INSOLUTOS
//------------------------------------------------------------------------------------------------- //
/*
function tasa($capital, $renta, $num_periodos )
{

    $diferencial = 1;
    $setpoint = $capital/$renta;
    $tasa = ($renta * $num_periodos - $capital)/($capital * $num_periodos);
    $iteracion = 0;
    $MAXINT=1;
    $MININT=0;



    While( abs($diferencial) > 0.00000001)
    {

                    $thispoint =   ( 1 - pow((1+$tasa),(-1 * $num_periodos)))/$tasa  ;


                    $diferencial =$setpoint - $thispoint ;
                    $skip=0;


                    if($diferencial > 0)
                    {
                        $MAXINT = $tasa;
                        $tasa -= (abs($tasa - $MININT)/ 2);
                        $oper = "<B>-</B>";
                        $skip=1;

                    }

                    if(!$skip)
                        if($diferencial  < 0)
                        {
                            $MININT = $tasa;
                            $tasa += (abs($tasa - $MAXINT)/ 2);
                            $oper = "<B>+</B>";
                        }

                    $iteracion++;



                    if($iteracion > 1000 )
                    break;

    }

    return($tasa);

}



function fechavencimiento($fecha_anterior, $tipo_vencimiento)
{


        list($dia, $mes, $anio) = split( "/",$fecha_anterior);




        switch ($tipo_vencimiento )
        {
          // Anual
          case 1 : $fecha = $dia."/".$mes."/".( ++$anio);

                   break;
          // Semestral
          case 2 :
                   $mes+=6;
                   if($mes>12)
                   {
                     $mes-=12;
                     $anio++;
                   }
                   $dia=($dia>28)?(28):($dia);
                   $mes = ($mes<10)?("0".$mes):($mes);

                   $fecha = $dia."/".$mes."/".$anio;
                   break;
         // Trimestral
          case 3 : $mes+=3;
                   if($mes>12)
                   {
                     $mes-=12;
                     $anio++;
                   }
                   $dia=($dia>28)?(28):($dia);
                   $mes = ($mes<10)?("0".$mes):($mes);

                   $fecha = $dia."/".$mes."/".$anio;
                   break;
         // Bimestral
          case 4 : $mes+=2;
                   if($mes>12)
                   {
                     $mes-=12;
                     $anio++;
                   }
                   $dia=($dia>28)?(28):($dia);
                   $mes = ($mes<10)?("0".$mes):($mes);

                   $fecha = $dia."/".$mes."/".$anio;
                   break;
          // Mensual
          case 5 : $mes++;
                   if($mes>12)
                   {
                     $mes-=12;
                     $anio++;
                   }
                   $dia=($dia>28)?(28):($dia);
                   $mes = ($mes<10)?("0".$mes):($mes);
                   $fecha = $dia."/".$mes."/".$anio;
                   break;


          //Quincenal
          case 6 :


                   if($dia>15)
                   {
                     $dia -= 15;
                     $mes++;
                   }
                   else
                     $dia+=15;




                   if($mes>12)
                     {
                       $mes-=12;
                       $anio++;
                     }

                   $mes = ($mes<10)?("0".(1*$mes)):($mes);
                   $dia = ($dia<10)?("0".(1*$dia)):($dia);
                   $fecha = $dia."/".$mes."/".$anio;
                   break;

          //Semanal
          case 7 : $dia+=7;
                   $fecha =strftime("%d/%m/%Y",mktime(0,0,0,$mes, $dia ,$anio));
                   break;



          //Diaria

          case 8 : $dia+=1;
                   $fecha =strftime("%d/%m/%Y",mktime(0,0,0,$mes, $dia ,$anio));
                   break;
        };




        return($fecha);

}

*/
?>