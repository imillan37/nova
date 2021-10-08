<?                                                               
/*                                                          
    _________________________________________________________________________________________
   |  Titulo: Aplicación de abonos a estado de cuenta solidario             
   |                                                        
## |  Fecha : Viernes 17 de Julio de 2009
## | 
## |  Autor : Enrique Godoy Calderón                        
## |                                                        
## |  Nombre original del archivo : [aplicacion_pagos_solidarios.php]     
## |                                                        
## |                                                                                        
##  ¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯
########################################################################################
######################################################################################
*/

header("Cache-Control: no-cache, must-revalidate"); 
include($DOCUMENT_ROOT."/rutas.php");

require($class_path."lib_credit.php");

//debug(ffecha(date("Y-m-d H:i:s")));
$db = ADONewConnection(SERVIDOR);  # create a connection
$db->Connect(IP,USER,PASSWORD,NUCLEO);


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//

if( empty($dia_sx )) {$dia_sx = $D;}
if( empty($mes_sx) ) {$mes_sx = $M;}
if( empty($anio_sx)) {$anio_sx= $Y;}

if( strlen($dia_sx)==1 ) {$dia_sx = "0".$dia_sx;}
if( strlen($mes_sx)==1 ) {$mes_sx = "0".$mes_sx;}

if( ! checkdate($mes_sx, $dia_sx, $anio_sx))
{
    
        $tmp = date("Y-m-d",mktime(0, 0, 0, $mes_sx, $dia_sx, $anio_sx));
        $dia_sx = fdia($tmp );
        $mes_sx = fmes($tmp );
        $anio_sx= fanio($tmp);
        $fecha_corte = $tmp;
}


$fecha_corte = $anio_sx ."-".$mes_sx."-".$dia_sx;
?>
<SCRIPT>
        function verestado(id)
        {
           
           url = "<?=$shared_scripts ?>resumen_op4.php?exit=1&numcliente=<?=$num_cliente ?>&factura="+id+"&mes_sx=<?=$mes_sx ?>&dia_sx=<?=$dia_sx ?>&anio_sx=<?=$anio_sx ?>";
           window.open(url,'edocta','width=800,height=500,menubar=1,toolbar=0,resizable=1,scrollbars=1');

           return;
        }

    function trapEnter()
    {
        //IE 6 code
        //window.status= "Key pressed has code = '" + event.keyCode + "'";
        if(event.keyCode == 13)//space key
        {
            //call do post back
            // __doPostBack('doNothing','');
            
          
                
            document.gpo.fields['id_factura'].value=0;
                    document.gpo.submit();

                    
            
            
            event.returnValue = false;
            
        }
        else
        {
            event.returnValue = event.keyCode;
            //false;//use up the event, rather cancel the event, so that no key is written in the text box
        }
        //IE 6 code ends
    }

        function CheckAll(obj) 
        {

          for (var i=0;i<document.gpo.elements['Cargo[]'].length;i++) 
          {
            var e = document.gpo.elements['Cargo[]'][i];

                e.checked = obj.checked;

          }

        }


        function verinfo(num)
        {
                
                
                var url="<?=$shared_scripts ?>resumen_op3.php?exit=1&factura="+num+"&anio_sx=<?=$anio_sx ?>&mes_sx=<?=$mes_sx ?>&dia_sx=<?=$dia_sx ?>&fecha1=<?=$anio_sy ?>-<?=$mes_sy ?>-<?=$dia_sy ?>&fecha2=<?=$anio_sx ?>-<?=$mes_sx ?>-<?=$dia_sx ?>";
                window.open(url,"verinfo","width=700,height=450,menubar=0,toolbar=0,resizable=1,scrollbars=1");
                return;
        }
        





</SCRIPT>
<?

echo "<BR>";
echo "<BR>";
echo "<CENTER>\n";
echo "<DIV      STYLE='background-color :white;  
                       width:95%;
                       height:auto; 
                       margin-left:2.5%; 
                       margin-right:2.5%;
                       text-align:left;   
                       display:block;
                       padding:15px;
                       border: 1px solid #000000;
                       padding: 2px 2px 2px 8px;'> ";


echo "<BR>\n";
echo "\n<TABLE ALIGN='center' BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH='100%' STYLE='color:blue; text-align:left; FONT-SIZE:10pt; font-weight:bold; FONT-FAMILY: Verdana,Geneva,Tahoma, Arial, Helvetica, sans-serif;'>
                <TR>
                <TD ALIGN='left' > 
                &nbsp;&nbsp;&nbsp;Estado de cuenta de grupos solidarios.
                </TD>
                </TR>
         </TABLE>\n";

echo "<HR>";
echo "<FORM METHOD='POST' ACTION='".$PHP_SELF."' NAME='gpo'>\n";

echo "<TABLE ALIGN='center' BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH='100%' STYLE='color:blue; text-align:left; FONT-SIZE:10pt; font-weight:bold; FONT-FAMILY: Verdana,Geneva,Tahoma, Arial, Helvetica, sans-serif;'>
<TR>
<TD ALIGN='left' > ";

echo "<EM ID='small' ><B> Número de grupo solidario : </B></EM>";

echo "<INPUT TYPE=TEXT          NAME='id_grupo' SIZE='10' MAXLENGTH='10' VALUE='$id_grupo' ID='small' onkeydown='trapEnter()'; >";
echo "<SPAN ID='busca'>".buscagrupo('gpo','id_grupo')."</SPAN>";
echo "<INPUT TYPE='SUBMIT' ID='S2' STYLE='font-weight:bold; height:21px;' VALUE='&raquo;' />";

echo "</TD>";


echo "<TD ALIGN='center' > ";


if($id_grupo)
{


         $sql = "SELECT Ciclo_gpo 
                 FROM grupo_solidario 
                 WHERE grupo_solidario.Alta_cliente='Y' and 
                       grupo_solidario.Alta_credito='Y' and 
                       ID_grupo_soli='".$id_grupo."' 
                 ORDER BY Ciclo_gpo DESC ";        

 $rs=$db->Execute($sql);
 if($rs->_numOfRows)
 {

    echo "Ciclo :  <SELECT  NAME='id_ciclo' ID='small' onChange='document.gpo.submit();' >";

    while(! $rs->EOF)
    {
      $sel=($id_ciclo==$rs->fields[0])?("SELECTED"):("");
      echo "<OPTION VALUE='".$rs->fields[0]."' $sel> ".$rs->fields[0]."</OPTION>\n"; 
      $rs->MoveNext();
    }

    echo "  </SELECT> \n";

}






}



echo "</TD>";

 echo "  <TD ALIGN= 'right'> ";
    echo "<EM ID='small' ><B>Fecha de corte : </EM>";
            echo "  <SELECT  NAME='dia_sx' ID='small' onChange='document.gpo.submit();'>";
            for($_dia_sx=0;$_dia_sx<=31;$_dia_sx++)
                {
                    $sel=($_dia_sx==$dia_sx)?("SELECTED"):("");
                    echo "<OPTION VALUE='$_dia_sx' $sel> $_dia_sx </OPTION>\n";
                 }
            echo "  </SELECT>/";
        
        
            echo "  <SELECT  NAME='mes_sx' ID='small' onChange='document.gpo.submit();' >";
            for($_mes_sx=1;$_mes_sx<=12;$_mes_sx++)
                {
                    $sel=($_mes_sx==$mes_sx)?("SELECTED"):("");
                    echo "<OPTION VALUE='$_mes_sx' $sel> ".substr($mes[$_mes_sx],0,3)."</OPTION>\n";
                 }
            echo "  </SELECT>/";

        
            echo "<SELECT  NAME='anio_sx' ID='small' onChange='document.gpo.submit();' >";
            for($_anio_sx=($anio_sx-10); ($_anio_sx<=$anio_sx+2); $_anio_sx++)
                {
                    $sel=($_anio_sx==$anio_sx)?("SELECTED"):("");
                    echo "<OPTION VALUE='$_anio_sx' $sel> $_anio_sx </OPTION>\n";
                 }
            echo "</SELECT>\n";         

echo "<INPUT TYPE='SUBMIT' ID='S2' STYLE='font-weight:bold; height:21px;' VALUE='&raquo;' />&nbsp;&nbsp;&nbsp;&nbsp;";




echo "</TD></TR></TABLE>";
echo "</FORM>";
if(empty($id_grupo))
{
        echo "</TABLE>\n";
        echo "</FORM>";
        
        echo "<BR><BR>\n";

        die("</BODY></HTML>");


}



         $sql = "SELECT Nombre, Ciclo_gpo 
                 FROM grupo_solidario 
                 WHERE grupo_solidario.Alta_cliente='Y' and 
                       grupo_solidario.Alta_credito='Y' and 
                       grupo_solidario.Ciclo_gpo ='".$id_ciclo."' and 
                       ID_grupo_soli='".$id_grupo."' 
                 ORDER BY Ciclo_gpo DESC ";        
         
         
         
         $rs=$db->Execute($sql);
        

         if(! $rs->_numOfRows)
         {
                        echo "<BR><CENTER><B STYLE=' color:red;'> No se encontró a ningún grupo solidario activo con el número ".$id_grupo.". </B></CENTER><BR>\n";
                        die();
         }
        
        echo "<BR>";
       
       $nombre_grupo_solidario = $rs->fields[0];
       $ciclo =$rs->fields[1];
       

        $sql =  "SELECT  COUNT(grupo_solidario_integrantes.id_factura), 
                         group_concat( grupo_solidario_integrantes.id_factura), 
                         grupo_solidario.ID_Suc
                 
                 FROM    grupo_solidario_integrantes
                         LEFT JOIN solicitud            ON solicitud.ID_Solicitud                       = grupo_solidario_integrantes.ID_Solicitud
                         LEFT JOIN grupo_solidario      ON grupo_solidario_integrantes.ID_grupo_soli    = grupo_solidario.ID_grupo_soli
                         LEFT JOIN promotores           ON grupo_solidario.ID_Promotor                  = promotores.Num_promo
                         LEFT JOIN sucursales           ON grupo_solidario.ID_Suc                       = sucursales.ID_Sucursal

                 WHERE   grupo_solidario_integrantes.ID_grupo_soli = '".$id_grupo."'    and
                         grupo_solidario_integrantes.Status='Activo' and 
                         grupo_solidario.Alta_credito='Y' and
                         grupo_solidario_integrantes.Cliente='Y' and
                         grupo_solidario_integrantes.Conformidad='Y' 
                         
                 GROUP BY grupo_solidario_integrantes.ID_grupo_soli                     ";

        $rs=$db->Execute($sql);
        


        echo "<h1 align='center'> GRUPO  :&nbsp;".$nombre_grupo_solidario ."</H1>";

        
         if(! $rs->fields[0])
         {
                        echo "<BR><CENTER><B STYLE=' color:red;'> No se hay clientes confirmados aun para éste grupo. </B></CENTER><BR>\n";
                        die("</BODY></HTML>");
         }
        
        echo "<BR>";    
        
        $creditos_asociados = $rs->fields[0];

        $list_id_factura   = $rs->fields[1];
        
        $id_suc  = $rs->fields[2]; 


        $array_id_factura = explode (",", $list_id_factura);

        $margen="&nbsp;&nbsp;&nbsp;&nbsp;";


        $sql = "SELECT  max(cargos.Fecha_Vencimiento) AS Fecha_Termino
                
                FROM    grupo_solidario, grupo_solidario_integrantes, fact_cliente, cargos



                WHERE fact_cliente.id_factura = grupo_solidario_integrantes.id_factura and 
                      grupo_solidario_integrantes.ID_grupo_soli='".$id_grupo."'    and
                      grupo_solidario_integrantes.Status='Activo' and 
                      grupo_solidario.ID_grupo_soli = grupo_solidario_integrantes.ID_grupo_soli and
                      grupo_solidario.Alta_credito='Y' and
                      grupo_solidario_integrantes.Cliente='Y' and
                      grupo_solidario_integrantes.Conformidad='Y' and
                      cargos.Num_Compra = fact_cliente.Num_Compra
         

                GROUP BY  grupo_solidario_integrantes.ID_grupo_soli ";


        $rs=$db->Execute($sql);

        $Fecha_Termino = $rs->fields['Fecha_Termino'];











        $sql = "SELECT  fact_cliente.Nombre_Producto, 
                        SUM(grupo_solidario_integrantes.Monto_asignado) AS Capital, 
                        COUNT(grupo_solidario_integrantes.id_factura) AS Integrantes, 
                        sucursales.Nombre AS sucursal,
                        fact_cliente.Fecha_inicio,
                        sucursales.Nombre AS sucursal ,
                        promotores.Nombre AS Promotor,
                        sum(fact_cliente.renta) AS CuotaSolidaria
                
                FROM    grupo_solidario_integrantes, fact_cliente 

                LEFT JOIN grupo_solidario ON  grupo_solidario.ID_grupo_soli = grupo_solidario_integrantes.ID_grupo_soli
                LEFT JOIN sucursales      ON sucursales.ID_Sucursal         = grupo_solidario.ID_Suc
                LEFT JOIN promo_ventas ON promo_ventas.Num_compra = fact_cliente.num_compra
                LEFT JOIN promotores ON promotores.Num_promo = promo_ventas.ID_Promo            


                WHERE fact_cliente.id_factura = grupo_solidario_integrantes.id_factura and 
                      grupo_solidario_integrantes.ID_grupo_soli='".$id_grupo."'    and
                      grupo_solidario_integrantes.Status='Activo' and 
                      grupo_solidario.Alta_credito='Y' and
                      grupo_solidario_integrantes.Cliente='Y' and
                      grupo_solidario_integrantes.Conformidad='Y'
         

                GROUP BY  grupo_solidario_integrantes.ID_grupo_soli ";

        $rs=$db->Execute($sql);
        
        
        
        
        echo "<TABLE WIDTH='95%' CELLPADDDING=1 CELLSPACING=1 BORDER=0  ALIGN='center' BGCOLOR='black' ID='small'>\n";

        echo "<TR>\n";
        echo "          <TH  ALIGN='center'  COLSPAN='4' BGCOLOR='steelblue' STYLE='color:white;'> DATOS DEL CREDITO. </TH>\n";
        echo "</TR>\n";


        echo "<TR BGCOLOR='white'  ALIGN='left'><Th>".$margen."Producto financiero      :       </Th><TD>".$margen.$rs->fields['Nombre_Producto']."                       </TD>";
        echo "                                  <Th>".$margen."Número de Miembros       :       </Th><TD ALIGN='center'>".$rs->fields['Integrantes']."</TD></TR>";



        echo "<TR  BGCOLOR='white' ALIGN='left'><TH>".$margen."Sucursal                 :       </TH><TD ALIGN='left' >".$margen.$rs->fields['sucursal']."</TD>
                                                <TH>".$margen."Promotor                 :       </TH><TD ALIGN='left' > ".$margen.$rs->fields['Promotor']." </TD></TR>";



        echo "<TR  BGCOLOR='white' ALIGN='left'><TH>".$margen."Fecha de inicio          :       </TH><TD ALIGN='center'>".ffecha($rs->fields['Fecha_inicio'])."                 </TD>
                                                <TH>".$margen."Monto otorgado           :       </TH><TD ALIGN='right'>".number_format($rs->fields['Capital'],2)."</TD></TR>";



        



        echo "<TR  BGCOLOR='white' ALIGN='left'><TH>".$margen."Fecha de término         :       </TH><TD ALIGN='center'>".ffecha($Fecha_Termino)."      </TD>
                                                <TH>".$margen."Valor cuota :                    </TH><TD ALIGN='right'>".number_format($rs->fields['CuotaSolidaria'],2)."</TD></TR>";

        $oInsolutos  = new TCUENTA($array_id_factura[0],    $fecha_corte);
        $plazo = $oInsolutos->plazo;
        $tipovencimiento = $oInsolutos->tipovencimiento;
        echo "<TR  BGCOLOR='white' ALIGN='left'><TH>".$margen."Plazo                    :       </TH><TD ALIGN='center'>".$plazo." ".$tipovencimiento."</TD>
                                                <TH>".$margen."Avance                   :       </TH><TD ALIGN='right' >".number_format($oInsolutos->avance,2)."%</TD></TR>";




        $SumaPagoParaEstarAlCorriente = 0;
        $SaldoGeneralVigente = 0;
        $SaldoGeneralVencido = 0;
        $SumaAbonos = 0;
        $CargosVencidos_No_Pagados = 0;
        $Dias_Mora_MAX = 0;
        
        $SumaMonto=0;
        $SaldoTotal =0;

        unset($oInsolutos);
        $oInsolutos= array();
        $num_cargosvencidos_pagados = array();

        $first=1;
        
        foreach($array_id_factura AS $id_factura)
        {
 


                $oInsolutos[$id_factura]  = new TCUENTA($id_factura,    $fecha_corte);
                
                $oInsolutos[$id_factura]->calificacion_mora_promedio();
                
                
                $Dias_Mora_MAX = max($Dias_Mora_MAX,$oInsolutos[$id_factura]->dias_mora);


                                        
                $SaldoGeneralVencido += $oInsolutos[$id_factura]->SaldoGeneralVencido;
                $SaldoGeneralVigente += round(($oInsolutos[$id_factura]->SaldoCapitalPorVencer + 
                                              ($oInsolutos[$id_factura]->SaldoInteresPorVencer  + $oInsolutos[$id_factura]->Saldo_IVA_InteresPorVencer)  + 
                                              ($oInsolutos[$id_factura]->SaldoComisionPorVencer + $oInsolutos[$id_factura]->Saldo_IVA_ComisionPorVencer)));
                $SumaAbonos += $oInsolutos[$id_factura]->SumaAbonos;
                $CargosVencidos_No_Pagados = max($CargosVencidos_No_Pagados,$oInsolutos[$id_factura]->numcargosvencidos_no_pagados);


                $SumaMonto      +=$oInsolutos[$id_factura]->SumaCargos;
                $SaldoTotal     +=($oInsolutos[$id_factura]->SaldoGeneralVencido + 
                                round(($oInsolutos[$id_factura]->SaldoCapitalPorVencer + 
                                      ($oInsolutos[$id_factura]->SaldoInteresPorVencer  + $oInsolutos[$id_factura]->Saldo_IVA_InteresPorVencer )  + 
                                      ($oInsolutos[$id_factura]->SaldoComisionPorVencer + $oInsolutos[$id_factura]->Saldo_IVA_ComisionPorVencer)),2  )) ;
                
                
                $SumaPagoParaEstarAlCorriente += $oInsolutos[$id_factura]->saldo_vencido;
                
                $numcargosvencidos              = max($oInsolutos[$id_factura]->numcargosvencidos               ,$numcargosvencidos             );
                $num_cargosvencidos_pagados[]   = $oInsolutos[$id_factura]->numcargosvencidos_pagados;
                $numcargosvencidos_no_pagados   = max($oInsolutos[$id_factura]->numcargosvencidos_no_pagados    ,$numcargosvencidos_no_pagados  );
        }
        
        $numcargosvencidos_pagados = min($num_cargosvencidos_pagados);
        
        

        $sql="  SELECT  MOP
                        FROM cat_formas_pago
                WHERE Dias_vencidos_hasta >= '".$Dias_Mora_MAX."'
                        ORDER BY Dias_vencidos_hasta  ";


        $rs = $db->Execute($sql);

        $clasificacion_puntualidad    = $rs->fields[0];

        $status = (($SaldoGeneralVencido + $SaldoGeneralVigente) >= 0.01)?("Crédito con saldo"):("Crédito con saldado");


        echo "<TR  BGCOLOR='white' ALIGN='left'><TH>".$margen."MOP                      :       </TH><TD ALIGN='center'>".$clasificacion_puntualidad."</TD>
                                                <TH>".$margen."Dias Morosidad           :       </TH><TD ALIGN='right' >".number_format($Dias_Mora_MAX,0)."</TD></TR>";


        echo "<TR  BGCOLOR='white' ALIGN='left'><TH>".$margen."Status                   :       </TH><TD ALIGN='center' >". $status."</TD>
                                                <TH>".$margen."Saldo para estar al corriente  : </TH><TD ALIGN='right' >".number_format(ceil($SumaPagoParaEstarAlCorriente),2)."</TD></TR>";

        echo "</TABLE>\n";
        echo "<BR><BR>\n";




        echo "<TABLE ALIGN='center' WIDTH='95%' CELLPADDDING=1 CELLSPACING=1 BORDER=0   BGCOLOR='black' ID='small'>\n";

        echo "<TR ALIGN='center' BGCOLOR='steelblue' ' STYLE='color:white;'>\n";
        echo "          <TH COLSPAN='5'> SALDO EN BASE A CUOTAS ";


        echo "</TH>\n";

        echo "</TR>\n";
        echo "<TR ALIGN='center' BGCOLOR='steelblue' ' STYLE='color:white;'>\n";

        echo "          <TH> Cuotas contratadas         </TH>\n";
        echo "          <TH> Cuotas devengadas          </TH>\n";
        echo "          <TH> Cuotas pagadas             </TH>\n";
        echo "          <TH> Cuotas vencidas    </TH>\n";
        echo "          <TH> SALDO A PAGAR              </TH>\n";
        echo "</TR>\n";

        echo "<TR ALIGN='CENTER'  BGCOLOR='white'  STYLE='color:black;'>\n";
        echo "  <TH                     >".$plazo."</TD>";

        echo "  <TH                     >".number_format( $numcargosvencidos                    ,0)."</TH>";
        echo "  <TH                     >".number_format(($numcargosvencidos_pagados            ),0)."</TH>";
        echo "  <TH                     >".number_format(($numcargosvencidos_no_pagados         ),0)."</TH>";
        echo "  <TH ALIGN='right'       >".number_format(($SaldoGeneralVencido                  ),2)."</TH>";

        echo "</TR>\n";

        echo "</TABLE>\n";


        echo "<BR><BR>\n";








        echo "<TABLE ALIGN='center' BORDER=0 BGCOLOR='black' WIDTH='95%' CELLSPACING=1 CELLPADDING=2  STYLE=' text-align:left; FONT-SIZE:12pt;  FONT-FAMILY: Verdana,Geneva,Tahoma, Arial, Helvetica, sans-serif;'>";

        echo "<TR>\n";
        echo "          <TH  ALIGN='center'  COLSPAN='12' BGCOLOR='steelblue' STYLE='color:white;' ID='small'> RESUMEN DE SALDOS </TH>\n";
        echo "</TR>\n";

        echo "<TR ALIGN='center' BGCOLOR='steelblue' STYLE='color:white;' ID='small'>\n";
        echo "<TH> Num Cliente          </TH>\n";

        echo "<TH> ID Crédito           </TH>\n";
        echo "<TH> Nombre               </TH>\n";
        echo "<TH> Cuotas vencidas      </TH>\n";


        echo "<TH> Dias Mora            </TH>\n";
        echo "<TH> Cargos               </TH>\n";

        echo "<TH> Abonos               </TH>\n";
        echo "<TH> Saldo vencido </TH>\n";
        echo "<TH> Saldo vigente </TH>\n";
        echo "<TH> Saldo Total </TH>\n";

        echo "</TR>";

        foreach($array_id_factura AS $id_factura)
        {


              $color=($color=='white')?("aliceblue"):("white");


                                        
                                        echo "<TR ALIGN='left'  STYLE='font-size:9px;' onClick='verinfo(\"".$id_factura."\")'  VALIGN='middle' onmouseover=\"javascript:this.style.backgroundColor='yellow'; this.style.cursor='hand'; \" onmouseout=\"javascript:  this.style.backgroundColor='' \" BGCOLOR='".$color."' > \n";

                                        echo "<Td ALIGN='right' >".$oInsolutos[$id_factura]->numcliente."</Td>\n";

                                        echo "<Td ALIGN='right' >".$id_factura."</Td>\n";
                                        echo "<Td ALIGN='left'  >".$oInsolutos[$id_factura]->nombrecliente."</Td>\n";                                   
                                        echo "<Td ALIGN='right' >".number_format($oInsolutos[$id_factura]->numcargosvencidos_no_pagados,0)."</Td>\n";
                                        
                                        
                                        
                                        echo "<Td ALIGN='right' >".number_format($oInsolutos[$id_factura]->dias_mora,0)."</Td>\n";
                                        
                                        echo "<Td ALIGN='right' STYLE='color:black;'>".number_format($oInsolutos[$id_factura]->SumaCargos,2)."</Td>\n";


                                        echo "<Td ALIGN='right' STYLE='color:blue;'>".number_format($oInsolutos[$id_factura]->SumaAbonos,2)."</Td>\n";

                                        echo "<Td ALIGN='right' >".number_format($oInsolutos[$id_factura]->SaldoGeneralVencido,2)."</Td>\n";
                                        
                                        echo "<Th ALIGN='right' >".number_format(round(($oInsolutos[$id_factura]->SaldoCapitalPorVencer + 
                                                                                       ($oInsolutos[$id_factura]->SaldoInteresPorVencer  + $oInsolutos[$id_factura]->Saldo_IVA_InteresPorVencer )  + 
                                                                                       ($oInsolutos[$id_factura]->SaldoComisionPorVencer + $oInsolutos[$id_factura]->Saldo_IVA_ComisionPorVencer)),2  )  ,2)."</Th>\n";

                                        echo "<Th ALIGN='right' >".number_format( $oInsolutos[$id_factura]->SaldoGeneralVencido + 
                                                                           round(($oInsolutos[$id_factura]->SaldoCapitalPorVencer + 
                                                                                 ($oInsolutos[$id_factura]->SaldoInteresPorVencer  + $oInsolutos[$id_factura]->Saldo_IVA_InteresPorVencer )  + 
                                                                                 ($oInsolutos[$id_factura]->SaldoComisionPorVencer + $oInsolutos[$id_factura]->Saldo_IVA_ComisionPorVencer)    ),2  )  ,2)."</Th>\n";
                                        
                                        echo "</TR>";

                                       //


        }
        
        
        echo "<TR BGCOLOR='steelblue' STYLE='color:white;' ID='small'>\n";
        echo "<TH></TH>\n";
        echo "<TH></TH>\n";
        echo "<TH></TH>\n";     
        echo "<Td ALIGN='right' >".number_format($CargosVencidos_No_Pagados,0)."</Td>\n";


        echo "<Td ALIGN='right' >".number_format($Dias_Mora_MAX ,0)."</Td>\n";

        echo "<Td ALIGN='right' >".number_format($SumaMonto,2)."</Td>\n";


        echo "<Td ALIGN='right' >".number_format($SumaAbonos,2)."</Td>\n";

        echo "<Td ALIGN='right' >".number_format($SaldoGeneralVencido,2)."</Td>\n";
        
        echo "<Th ALIGN='right' >".number_format($SaldoGeneralVigente ,2)."</Th>\n";
        
        echo "<Th ALIGN='right' >".number_format($SaldoTotal ,2)."</Th>\n";


        echo "</TR>";

        echo "</TR>";



        echo "</TABLE>\n";

        
        echo "<BR><BR>\n";



        $Capital                = 0;
        $ComisionApertura       = 0;
        $Interes                = 0;
        $Extemporaneos          = 0;
        $Moratorio              = 0;

        echo "<TABLE ALIGN='center' BORDER=0 BGCOLOR='black' WIDTH='95%' CELLSPACING=1 CELLPADDING=2  STYLE=' text-align:left; FONT-SIZE:12pt;  FONT-FAMILY: Verdana,Geneva,Tahoma, Arial, Helvetica, sans-serif;'>";

        echo "<TR>\n";
        echo "          <TH  ALIGN='center'  COLSPAN='8' BGCOLOR='steelblue' STYLE='color:white;' ID='small'> SALDOS VENCIDOS</TH>\n";
        echo "</TR>\n";

        echo "<TR ALIGN='center' BGCOLOR='steelblue' STYLE='color:white;' ID='small'>\n";
        echo "<TH> Num Cliente          </TH>\n";

        echo "<TH> ID Crédito           </TH>\n";
        echo "<TH> Nombre               </TH>\n";
        echo "<TH> Capital                      </TH>\n";
        echo "<TH> Comisión Apertura            </TH>\n";
        echo "<TH> Interés normal               </TH>\n";
        echo "<TH> Extemporáneos                </TH>\n";
        echo "<TH> Interés moratorio            </TH>\n";

        echo "</TR>";


        foreach($array_id_factura AS $id_factura)
        {


              $color=($color=='white')?("aliceblue"):("white");



                                        $Capital                +=       $oInsolutos[$id_factura]->SaldoCapital;        
                                        $ComisionApertura       +=      ($oInsolutos[$id_factura]->SaldoComision +$oInsolutos[$id_factura]->SaldoIVAComision );
                                        $Interes                +=      ($oInsolutos[$id_factura]->SaldoInteres  +$oInsolutos[$id_factura]->SaldoIVAInteres  );         
                                        $Extemporaneos          +=      ($oInsolutos[$id_factura]->SaldoOtros    +$oInsolutos[$id_factura]->SaldoIVAOtros    );
                                        $Moratorio              +=      ($oInsolutos[$id_factura]->SaldoIM       +$oInsolutos[$id_factura]->SaldoIVAIM       );
                                                        
                                        
                                        
                                        echo "<TR ALIGN='left'  STYLE='font-size:9px;' onClick='verinfo(\"".$id_factura."\")'  VALIGN='middle' onmouseover=\"javascript:this.style.backgroundColor='yellow'; this.style.cursor='hand'; \" onmouseout=\"javascript:  this.style.backgroundColor='' \" BGCOLOR='".$color."' > \n";
                                        echo "<Td ALIGN='right' >".$oInsolutos[$id_factura]->numcliente."</Td>\n";

                                        echo "<Td ALIGN='right' >".$id_factura."</Td>\n";
                                        echo "<Td ALIGN='left'  >".$oInsolutos[$id_factura]->nombrecliente."</Td>\n";                                   
                                        echo "<Td ALIGN='right' >".number_format($oInsolutos[$id_factura]->SaldoCapital                                                        ,2)."</Td>\n";
                                        echo "<Td ALIGN='right' >".number_format(($oInsolutos[$id_factura]->SaldoComision +$oInsolutos[$id_factura]->SaldoIVAComision      )   ,2)."</Td>\n";
                                        echo "<Td ALIGN='right' >".number_format(($oInsolutos[$id_factura]->SaldoInteres  +$oInsolutos[$id_factura]->SaldoIVAInteres       )   ,2)."</Td>\n";
                                        echo "<Td ALIGN='right' >".number_format(($oInsolutos[$id_factura]->SaldoOtros    +$oInsolutos[$id_factura]->SaldoIVAOtros         )   ,2)."</Td>\n";
                                        echo "<Td ALIGN='right' >".number_format(($oInsolutos[$id_factura]->SaldoIM       +$oInsolutos[$id_factura]->SaldoIVAIM            )   ,2)."</Td>\n";





                                        
                                        echo "</TR>";

                                       // unset($oInsolutos[$id_factura]);


        }

        echo "<TR BGCOLOR='steelblue' STYLE='color:white;' ID='small'>\n";
        echo "<TH></TH>\n";
        echo "<TH></TH>\n";
        echo "<TH></TH>\n";     

        echo "<Th ALIGN='right' >".number_format($Capital                               ,2)."</Th>\n";
        echo "<Th ALIGN='right' >".number_format($ComisionApertura                      ,2)."</Th>\n";
        echo "<Th ALIGN='right' >".number_format($Interes                               ,2)."</Th>\n";
        echo "<Th ALIGN='right' >".number_format($Extemporaneos                         ,2)."</Th>\n";
        echo "<Th ALIGN='right' >".number_format($Moratorio                             ,2)."</Th>\n";

        echo "</TR>";



        echo "</TABLE>\n";

        
        echo "<BR><BR>\n";




        $Capital                = 0;
        $ComisionApertura       = 0;
        $Interes                = 0;


        echo "<TABLE ALIGN='center' BORDER=0 BGCOLOR='black' WIDTH='95%' CELLSPACING=1 CELLPADDING=2  STYLE=' text-align:left; FONT-SIZE:12pt;  FONT-FAMILY: Verdana,Geneva,Tahoma, Arial, Helvetica, sans-serif;'>";

        echo "<TR>\n";
        echo "          <TH  ALIGN='center'  COLSPAN='6' BGCOLOR='steelblue' STYLE='color:white;' ID='small'> SALDOS VIGENTES</TH>\n";
        echo "</TR>\n";

        echo "<TR ALIGN='center' BGCOLOR='steelblue' STYLE='color:white;' ID='small'>\n";
        echo "<TH> Num Cliente          </TH>\n";

        echo "<TH> ID Crédito           </TH>\n";
        echo "<TH> Nombre               </TH>\n";
        echo "<TH> Capital                      </TH>\n";
        echo "<TH> Comisión Apertura            </TH>\n";
        echo "<TH> Interés normal (Proyectado)          </TH>\n";

        echo "</TR>";


        foreach($array_id_factura AS $id_factura)
        {


              $color=($color=='white')?("aliceblue"):("white");



                                        $Capital                +=      $oInsolutos[$id_factura]->SaldoCapitalPorVencer;        
                                        $ComisionApertura       +=      ($oInsolutos[$id_factura]->SaldoComisionPorVencer + $oInsolutos[$id_factura]->Saldo_IVA_ComisionPorVencer);
                                        $Interes                +=      ($oInsolutos[$id_factura]->SaldoInteresPorVencer  + $oInsolutos[$id_factura]->Saldo_IVA_InteresPorVencer );              
                                                        
                                        
                                        
                                        echo "<TR ALIGN='left'  STYLE='font-size:9px;' onClick='verinfo(\"".$id_factura."\")'  VALIGN='middle' onmouseover=\"javascript:this.style.backgroundColor='yellow'; this.style.cursor='hand'; \" onmouseout=\"javascript:  this.style.backgroundColor='' \" BGCOLOR='".$color."' > \n";
                                        echo "<Td ALIGN='right' >".$oInsolutos[$id_factura]->numcliente."</Td>\n";

                                        echo "<Td ALIGN='right' >".$id_factura."</Td>\n";
                                        echo "<Td ALIGN='left'  >".$oInsolutos[$id_factura]->nombrecliente."</Td>\n";                                   
                                        echo "<Td ALIGN='right' >".number_format($oInsolutos[$id_factura]->SaldoCapitalPorVencer                                                ,2)."</Td>\n";
                                        echo "<Td ALIGN='right' >".number_format(($oInsolutos[$id_factura]->SaldoComisionPorVencer + $oInsolutos[$id_factura]->Saldo_IVA_ComisionPorVencer)    ,2)."</Td>\n";
                                        echo "<Td ALIGN='right' >".number_format(($oInsolutos[$id_factura]->SaldoInteresPorVencer  + $oInsolutos[$id_factura]->Saldo_IVA_InteresPorVencer )    ,2)."</Td>\n";





                                        
                                        echo "</TR>";



        }

        echo "<TR BGCOLOR='steelblue' STYLE='color:white;' ID='small'>\n";
        echo "<TH></TH>\n";
        echo "<TH></TH>\n";
        echo "<TH></TH>\n";     

        echo "<Th ALIGN='right' >".number_format($Capital                               ,2)."</Th>\n";
        echo "<Th ALIGN='right' >".number_format($ComisionApertura                      ,2)."</Th>\n";
        echo "<Th ALIGN='right' >".number_format($Interes                               ,2)."</Th>\n";

        echo "</TR>";



        echo "</TABLE>\n";

        
        echo "<BR><BR>\n";


        echo "<TABLE ALIGN='center' BORDER=0 BGCOLOR='black' WIDTH='95%' CELLSPACING=1 CELLPADDING=2  STYLE=' text-align:left; FONT-SIZE:12pt;  FONT-FAMILY: Verdana,Geneva,Tahoma, Arial, Helvetica, sans-serif;'>";

        echo "<TR>\n";
        echo "          <TH  ALIGN='center'  COLSPAN='12' BGCOLOR='steelblue' STYLE='color:white;' ID='small'> RESUMEN DE SALDOS POR CUOTA </TH>\n";
        echo "</TR>\n";

        echo "  <TR  BGCOLOR='steelblue'  STYLE='color:white;' ID='small' ALIGN='center'>   \n";
        
        echo "  <TH >   Fecha Mov                           </TH>\n";
        echo "  <TH >   Fecha Aplicación                    </TH>\n";
        



        echo "  <TH >   Concepto                            </TH>\n";



        echo "  <TH >   Cargos            </TH>\n";
        echo "  <TH >   Abonos            </TH>\n";


        echo "  <TH >   Pagos en el periodo               </TH>\n";

        echo "  <TH >   Días vencidos                     </TH>\n";
        echo "  <TH >   Fecha Ultimo Mov                          </TH>\n";


        echo "  <TH >   Fecha Ultimo Abono                </TH>\n";
        echo "  <TH >   Fecha Ultimo Cargo                </TH>\n";




        echo "  <TH >   Saldo parcial                   </TH>\n";
        echo "  <TH >   Saldo global                    </TH>\n";
        

        echo "</TR>";

        $saldos_cuota = array();
        foreach($array_id_factura AS $id_factura)
        {

                foreach($oInsolutos[$id_factura]->saldos_cuota AS $cuota => $row)
                {

                                $saldos_cuota[$cuota]['Fecha_Mov']                      = max($saldos_cuota[$cuota]['Fecha_Mov'], $row['Fecha_Mov']);

                                $saldos_cuota[$cuota]['Fecha']                          = max($saldos_cuota[$cuota]['Fecha'], $row['Fecha']);

                                $saldos_cuota[$cuota]['DiasAtrasoAcum']                 = max($saldos_cuota[$cuota]['DiasAtrasoAcum'], $row['DiasAtrasoAcum']);

                                $saldos_cuota[$cuota]['Fecha_Ultimo_Mov']               = max($saldos_cuota[$cuota]['Fecha_Ultimo_Mov'], $row['Fecha_Ultimo_Mov']);

                                $saldos_cuota[$cuota]['Fecha_Ultimo_Abono']             = max($saldos_cuota[$cuota]['Fecha_Ultimo_Abono'], $row['Fecha_Ultimo_Abono']);

                                $saldos_cuota[$cuota]['Fecha_Ultimo_Cargo']             = max($saldos_cuota[$cuota]['Fecha_Ultimo_Cargo'], $row['Fecha_Ultimo_Cargo']);





                                $saldos_cuota[$cuota]['CARGOS']                         += $row['CARGOS'];
                                $saldos_cuota[$cuota]['ABONOS']                         += $row['ABONOS'];

                                $saldos_cuota[$cuota]['PAGOS']                          += $row['PAGOS'];






                                $SALDO_MOV_General = ($row['SALDO_MOV_General']<0)?(0):($row['SALDO_MOV_General']);
                                //$SALDO_MOV_General = ($row['SALDO_MOV_General']);
                                
                                $saldos_cuota[$cuota]['SALDO_MOV_General']              += $SALDO_MOV_General;

                                $saldos_cuota[$cuota]['SaldoParcial']                   += $row['SaldoParcial'];

                
                }
        }


        unset($cuota);
        
        unset($row);





        $Suma_Saldo_Individuales = 0;

        foreach($saldos_cuota  AS $cuota => $row)
        {


                if(($cuota>0 ) and ($cuota <= $numcargosvencidos))
                {

                $color=($color=='white')?('lavender'):('white');





                                echo "<TR  BGCOLOR='".$color."'  ID='small'>      \n";
                                echo "<TH  ALIGN='center' >".ffecha($row['Fecha_Mov'])."</TH>     \n";
                                echo "<TH  ALIGN='center' >".ffecha($row['Fecha'])."</TH>     \n";

                                echo "<TH ALIGN='center' > Cuota # ".$cuota."</TD>     \n";



                                echo "<TH  ALIGN='right' Width='100px;'                         >".number_format($row['CARGOS'], 2)."</TH>     \n";
                                echo "<TH  ALIGN='right' Width='100px;' STYLE='color:blue;'     >".number_format($row['ABONOS'], 2)."</TH>     \n";

                                echo "<TH  ALIGN='right' Width='100px;' STYLE='color:navy;'     >".number_format($row['PAGOS'], 2)."</TH>     \n";



                                if($row['DiasAtrasoAcum'])
                                echo "<TH  ALIGN='right' >".$row['DiasAtrasoAcum']."</TH>     \n";
                                else
                                echo "<TH  ALIGN='right' >0</TH>     \n";


                                echo "<TH  ALIGN='center' >".ffecha($row['Fecha_Ultimo_Mov'])."</TH>     \n";
                                echo "<TH  ALIGN='center' >".ffecha($row['Fecha_Ultimo_Abono'])."</TH>     \n";
                                echo "<TH  ALIGN='center' >".ffecha($row['Fecha_Ultimo_Cargo'])."</TH>     \n";




                                echo "<TH ALIGN='right' Width='100px;' >".number_format($row['SaldoParcial'], 2)."</TH>     \n";

                                $SALDO_MOV_General = ($row['SALDO_MOV_General']<0)?(0):($row['SALDO_MOV_General']);
                                //$SALDO_MOV_General = $row['SALDO_MOV_General'];
                                //$STYLE = ($SALDO_MOV_General <0)?(" STYLE=' color:blue;' "):(" ");
                                
                                echo "<TH ALIGN='right' Width='100px;' ".$STYLE.">".number_format($row['SALDO_MOV_General'],2). "  </TH>     \n";


                                echo "</TR>";
                }

        }

        echo "  <TR  BGCOLOR='steelblue'  STYLE='color:white;' ID='small' ALIGN='center'>   \n";
        
        echo "  <TH ></TH>\n";
        echo "  <TH ></TH>\n";
        
        echo "  <TH ></TH>\n";
        echo "  <TH ></TH>\n";
        echo "  <TH ></TH>\n";
        echo "  <TH ></TH>\n";

        echo "  <TH ></TH>\n";
        echo "  <TH ></TH>\n";

        echo "  <TH ></TH>\n";
        echo "  <TH ></TH>\n";
        
        echo "<TD COLSPAN='2'>&nbsp;</TD>\n";

        //echo "<TH ALIGN='right' >".number_format($row['SaldoParcial'],        $dec)."</TH>     \n";

        //echo "<TH ALIGN='right' >".number_format($SALDO_MOV_General,  $dec). "</TH>     \n";
        
        echo "</TR>";

        echo "</TABLE>\n";

        
        echo "<BR><BR>\n";


















































        unset($oInsolutos);

        die("</BODY></HTML>");



