<?
/*                                                          
    _________________________________________________________________________________________
   |  Titulo: Calendario de cierres.        
   |                                                        
 # |  Autor : Enrique Godoy Calderón                        
## |                                                        
## |  Fecha : Martes, 22 de Julio de 2009                    
## |                                                        
## |  Descripción  : Permite seleccionar fechas 
## |                                                                               
## |                                                        
## |  Nombre original del archivo : [calendario.php]     
## |                                                        
## |                                                                                        
##  ¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯
########################################################################################
######################################################################################
*/


$noheader=1;
$noSessionValidation =1;
header("Cache-Control: no-cache, must-revalidate"); 

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<HTML>
<HEAD>

<TITLE>Seleccione una fecha</TITLE>

<STYLE TYPE='text/css'> 
                        #head
                        {
                                FONT-SIZE: 16pt;
                                FONT-STYLE: normal;
                                FONT-FAMILY:  Geneva, Verdana,Tahoma, Arial, Helvetica, sans-serif;
                        }                       
                        
                        #nombre
                        {
                                FONT-SIZE: 8pt;
                                FONT-STYLE: normal;
                                FONT-FAMILY:  Geneva, Verdana,Tahoma, Arial, Helvetica, sans-serif;
                        }
                        #strong
                        {
                                FONT-SIZE: 12pt;
                                FONT-STYLE: bold;
                                FONT-FAMILY:   Verdana,Geneva,Tahoma, Arial, Helvetica, sans-serif;
                        }                               
                        #small
                        {
                                FONT-SIZE: 10;
                                FONT-STYLE: normal;
                                FONT-FAMILY:  Verdana,Geneva,Tahoma, Arial, Helvetica, sans-serif;
                        }       
                        #smallbold
                        {
                                FONT-SIZE: 10;
                                FONT-STYLE: bold;
                                FONT-FAMILY:  Verdana,Geneva,Tahoma, Arial, Helvetica, sans-serif;
                        }                       
                        #verysmall
                        {
                                FONT-SIZE: 9;
                                FONT-STYLE: normal;
                                FONT-FAMILY:  Verdana,Geneva,Tahoma, Arial, Helvetica, sans-serif;
                                color: oxford;
                        }                       
                        #encabezado
                        {
                                FONT-SIZE: 10pt;
                                FONT-STYLE: normal;
                                FONT-FAMILY:  Geneva, Verdana,Tahoma, Arial, Helvetica, sans-serif;
                        }       
                        
</STYLE> 

<SCRIPT> 

function dispatch(fechasel)
{

<?php
        $index = '';
        $rev   = strrev (trim($campo) );
        
        
        $pos = strpos($rev, "[");
        $_campo = $campo;
        
        if($pos>0)
        {
                $index = "[".substr($rev , 1,($pos-1) )."]";
                
                if($index == "[]")
                {
                        $index ="";
                }
                else
                {
                        $_campo = substr($campo , 0, (strlen($campo) - strlen($index)));
                }
        }



        echo "       opener.document.forms['".$forma."'].elements['".$_campo."']".$index.".value = fechasel; \n ";
                
        
				
				if( $callback != "" ) {
				
        echo " opener.".$callback."(fechasel); ";
				
				}
        
?>


       
       window.close();

    return;

}
</SCRIPT> 

</HEAD>

<BODY BGCOLOR="lightsteelblue" TEXT="#000000" LINK="#FF0000" VLINK="#800000" ALINK="#FF00FF" BACKGROUND="?" onload="this.focus();">
<?
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// USO: URL: calendario.php?<PARAMETROS>
// Enrique Godoy
//-----------------------------------------------------------------------
// PARAMETROS 
//-----------------------------------------------------------------------
// PARAMETO     : "campo"
// TIPO                 : OBLIGATORIO 
// FORMATO              : TEXTO LIBRE
// DESCRIPCION  : Campo de forma en donde se debe acpturar la fecha.
//-----------------------------------------------------------------------
// PARAMETO     : "forma"
// TIPO                 : OBLIGATORIO 
// FORMATO              : TEXTO LIBRE
// DESCRIPCION  : Nombre de la forma que contiene al campo en donde se debe acpturar la fecha.
//-----------------------------------------------------------------------
// PARAMETO     : "presetdate"
// TIPO                 : OPCIONAL
// FORMATO              : <DD/MM/AAAA>
// DESCRIPCION  : Fecha que deseamos que aprezca premarcada     
//-----------------------------------------------------------------------
// PARAMETO     : future
// TIPO                 : OPCIONAL 
// FORMATO              : <1|0>
// DESCRIPCION  : Se deberá pasar con valor 1 y obligará a que solo re puedan seleccionar las fechas del 
//                                dia de [presetdate/HOY] hacia adelante
//-----------------------------------------------------------------------
// PARAMETO     : past
// TIPO                 : OPCIONAL      
// FORMATO              : <1|0> 
// DESCRIPCION  : Se deberá pasar con valor 1 y obligará a que solo se puedan seleccionar las fechas
//                                del dia de [presetdate/HOY] a hacia atrás.
//-----------------------------------------------------------------------

// PARAMETO     : rango
// TIPO         : OPCIONAL      
// FORMATO      : DD/MM/AAAA-DD/MM/AAAA
// DESCRIPCION  : Dos fechas en formato ANSI concatenadas por el signo "-" que coforman un único rango selccionable

//-----------------------------------------------------------------------
// PARAMETO     : maxdate
// TIPO         : OPCIONAL      
// FORMATO      : AAAA-MM-DD
// DESCRIPCION  : Fecha máxima hasta la cual permitiremos que se seleccionen fechas

//-----------------------------------------------------------------------


//=======================================================================
// PENDIENTES :
//=======================================================================


// 
// SelectType = (Deny/Access) POR omisión (Access)
//                              [Deny] :   Si las fechas del calendario son inaccesibles a excepción de las que las 
//                                             listas especiofiquen
//                              [Access] : Si las fechas del calendario son accesibles a excepción de las que las 
//                                             listas especiofiquen
//----------------------------------------------------------
// LISTAS : DEPENDEN DELPARAMETRO "SelectType"

// DateList     = 'Lista de fechas absolutas (selccionalbles /no seleccionables), separadas por coma'.
// WeekDayList  = 'Lista de dias de las semana  (selccionalbles /no seleccionables), separados por coma'.
// MonthDayList = 'Lista de dias del mes  (selccionalbles /no seleccionables), separados por coma'.
//----------------------------------------------------------
// LISTAS DE MARCAS
// MarkDateList         = 'Lista de fechas absolutas que aparecerán resaltadas, separadas por coma'.
// MarkWeekDayList      = 'Lista de dias de las semana  que aparecerán resaltados, separados por coma'.
// MarkMonthDayList     = 'Lista de dias del mes que aparecerán resaltados, separados por coma'.






        $hoy=time();


        $mes[1] = "Enero";
        $mes[2] = "Febrero";
        $mes[3] = "Marzo";
        $mes[4] = "Abril";
        $mes[5] = "Mayo";
        $mes[6] = "Junio";
        $mes[7] = "Julio";
        $mes[8] = "Agosto";
        $mes[9] = "Septiembre";
        $mes[10]= "Octubre";
        $mes[11]= "Noviembre";
        $mes[12]= "Diciembre";  

        $W= strftime("%w",$hoy);   // Dia de la semana
        $M= (int) strftime("%m",$hoy);
        $D= (int) strftime("%d",$hoy);
        $Y= strftime("%Y",$hoy);

        $fecha_hoy=strftime("%d/%m/%Y",$hoy);
        
        $_fecha_hoy=strftime("%Y%m%d",$hoy);

        $fecha_extendida = "$dia[$W] $D de ".substr($mes[$M],0,3)." $Y";

        //echo "setmonth =$setmonth -- setyear = $setyear <BR>";

        $setyear  = (empty($setyear ))?($Y):($setyear );
        $setmonth = (empty($setmonth))?($M):($setmonth);
        

        $atime = mktime(0,0,0,$setmonth,1,$setyear ) ;
        
        
        
        
        
        if(!empty($rango))
        {
                
                list($r1,$r2)     = split("-",$rango);
                
                list($d1,$m1,$y1) = split("/",$r1);
                list($d2,$m2,$y2) = split("/",$r2);
                
                
                if((checkdate($m1,$d1,$y1) ) and (checkdate($m2,$d2,$y2) ))
                {

                        $_R1 = $y1."-".$m1."-".$d1;
                        $_R2 = $y2."-".$m2."-".$d2;

                        $rmax = max($_R1,$_R2);        
                        $rmin = min($_R1,$_R2);  
                }
                else
                {
                        unset($rango);
                }
        }

      
       if(!$pos=strpos($presetdate, '/')) unset($presetdate);
        
        if(empty($presetdate))
        {
                        $setyear  = date( "Y",$atime);
                        $setmonth = date( "m",$atime);
                        $setmday  = date( "d",$atime);
                        $setwday  = date( "w",$atime );
                        
                        if(! isset($pivot))
                        {
                           $pivot = $_fecha_hoy;
                           $pvt=$hoy;
                        }
        }
        else
        {
                $markdate =$presetdate;
                $pos=strpos($presetdate, '/') ;
                
                $resto =    substr($presetdate,($pos+1));
                $pos=strpos($resto, '/') ;              

                $setmonth = substr($resto, 0,($pos));                   
                $setyear  = substr($resto,($pos+1));
                
                $pivoday=substr($presetdate, 0,2);
               
                
                
                
                
                $preset = mktime(0,0,0,$setmonth,1,$setyear );
                
                        $setyear  = date( "Y",$preset);
                        $setmonth = date( "m",$preset);
                        $setmday  = date( "d",$preset);
                        $setwday  = date( "w",$preset);        
                        
        
               $pvt = mktime(0,0,0,$setmonth,$pivoday,$setyear );
               $pivot = strftime("%Y%m%d", $pvt);
        
        

        }


        $month = $setmonth;
        


        $tablebgcolor           =       "white";
        $captioncolor           =       "steelblue";
        $hedingcolor            =       "white";
        $businesdaycolor        =       "gainsboro";
        $weekenddaycolor        =       "lightskyblue";
        $presetcolor            =       "fuchsia";
        

        
        $DOM = array();
        $LUN = array();
        $MAR = array();
        $MIE = array();
        $JUE = array();
        $VIE = array();
        $SAB = array();

        while($setmonth == $month)
        {

                if($setmday == 1)
                {
                        switch($setwday)
                        {
                                
                                case 1 : $LUN[0]=$setmday; break;
                                case 2 : $MAR[0]=$setmday; $LUN[0]="";break;
                                case 3 : $MIE[0]=$setmday; $MAR[0]=""; $LUN[0]="";break;
                                case 4 : $JUE[0]=$setmday; $MIE[0]=""; $MAR[0]=""; $LUN[0]="";break;
                                case 5 : $VIE[0]=$setmday; $JUE[0]=""; $MIE[0]=""; $MAR[0]=""; $LUN[0]="";break;
                                case 6 : $SAB[0]=$setmday; $VIE[0]=""; $JUE[0]=""; $MIE[0]=""; $MAR[0]=""; $LUN[0]="";break;
                                case 0 : $DOM[0]=$setmday; $SAB[0]=""; $VIE[0]=""; $JUE[0]=""; $MIE[0]=""; $MAR[0]=""; $LUN[0]=""; break;
                        }
                }
                else
                {
                  if( strlen($setmday) < 2) $setmday="0".$setmday;
                  
                  switch($setwday)
                        {
                                case 0 : $DOM[count($DOM)]=$setmday; break;
                                case 1 : $LUN[count($LUN)]=$setmday; break;
                                case 2 : $MAR[count($MAR)]=$setmday; break;
                                case 3 : $MIE[count($MIE)]=$setmday; break;
                                case 4 : $JUE[count($JUE)]=$setmday; break;
                                case 5 : $VIE[count($VIE)]=$setmday; break;
                                case 6 : $SAB[count($SAB)]=$setmday; break;
                        }
                }
                
                $setmday++;

                $atime = mktime(0,0,0,$setmonth,$setmday,$setyear ) ;
                
                if( date( "m",$atime) == $setmonth)
                {
                        $setmonth = date( "m",$atime);          
                        $setyear  = date( "Y",$atime);
                        $setwday  = date( "w",$atime ); 
                }
                else break;
                
                
        }


        $rows = max(count($DOM), count($LUN), count($MAR), count($MIE), count($JUE), count($VIE), count($SAB) );
        $script =" onmouseover=\"javascript: this.style.backgroundColor='yellow'; this.style.cursor='hand'; \" onmouseout=\"javascript:  this.style.backgroundColor=''; \" ";
        //$script.=" onclick=\"javascript: this.style.backgroundColor='red';\" ";
        
        echo "\n\n <FORM ACTION='calendario.php' METHOD='POST' name='mycalendar' >\n";
        
        echo "<INPUT TYPE='HIDDEN' NAME='callback'                 VALUE='$callback' >";
        echo "<INPUT TYPE='HIDDEN' NAME='campo'                 VALUE='$campo' >";
        echo "<INPUT TYPE='HIDDEN' NAME='forma'                 VALUE='$forma' >";
        echo "<INPUT TYPE='HIDDEN' NAME='markdate'              VALUE='$markdate' >";
        
        echo "<INPUT TYPE='HIDDEN' NAME='pivot'                 VALUE='$pivot' >";
        echo "<INPUT TYPE='HIDDEN' NAME='pvt'                   VALUE='$pvt' >";
        
        echo "<INPUT TYPE='HIDDEN' NAME='past'                  VALUE='$past' >";
        echo "<INPUT TYPE='HIDDEN' NAME='future'                VALUE='$future' >";     
        
        echo "<INPUT TYPE='HIDDEN' NAME='rango'                 VALUE='$rango' >";     

        echo "<INPUT TYPE='HIDDEN' NAME='maxdate'               VALUE='$maxdate' >";     


        //past = $past | future = $future | forma = $forma
        echo "
        
        <TABLE ALIGN='center' BORDER=1 CELLSPACING=0 CELLPADDING=0 >
        <TR><TD>
        
                        <TABLE ALIGN='center' BORDER=0 CELLSPACING=0 CELLPADDING=0 BGCOLOR='$captioncolor' WIDTH='100%'>
                        <TR BGCOLOR='$captioncolor'   ID='encabezado'>
                                <TH COLSPAN='3' HEIGHT='5'></TH>        
                        </TR>";
                        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ Encabezado ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//

                        echo "  
                                <TR ALIGN='center' VALIGN='middle' BGCOLOR='$captioncolor'  ID='encabezado'>
                                <TH></TH>
                                <TH ALIGN='center'>";

                                        $dis=($month==1)?("DISABLED"):("");
                                        echo " <BUTTON $dis onclick=\"javascript: document.mycalendar.setmonth.value ='".($month-1)."'; document.mycalendar.submit(); \" STYLE='width:20px;'>&lt;</BUTTON> &nbsp; \n";
                                        echo "<SELECT name='setmonth'  onChange='document.mycalendar.submit();'>\n";                    
                                        for($i=1;$i<13;$i++)
                                        {
                                                $chk=($i == ($month * 1))?"SELECTED":"";
                                                echo"<OPTION VALUE='".$i."' ".$chk." >".substr($mes[$i],0,3)."</OPTION>\n";
                                        }
                                        echo "</SELECT>\n";

                                        echo "<SELECT name='setyear' onChange='document.mycalendar.submit();' >\n";                     
                                                                                
                                     if($past)
                                        {
                                                $yini   =1902;
                                                $yend =max(strftime("%Y",$hoy),strftime("%Y",$pvt));
                                        }
                                        else
                                           if($future)
                                                {                                       
                                                        $yini   = min(strftime("%Y",$hoy),strftime("%Y",$pvt));
                                                        $yend = 2037;
                                                }
                                                else
                                                 {
                                                        $yini   = 1940;  
                                                        $yend = 2037;                                            
                                                 }                                      
                                        
                                        
                                        
                                        for($i=($yini) ;$i<=($yend);$i++)
                                        {
                                                $chk=($i == $setyear)?"SELECTED":"";
                                                echo"<OPTION VALUE='".$i."' ".$chk." >".$i."</OPTION>\n";
                                        }                                       
                                        

                                        echo "</SELECT> &nbsp;";        

                                        $dis=($month==12)?("DISABLED"):("");
                                        echo " <BUTTON $dis onclick=\"javascript: document.mycalendar.setmonth.value ='".($month+1)."'; document.mycalendar.submit(); \" STYLE='width:20px;'>&gt;</BUTTON> &nbsp; \n";                      

                        echo " </TH>";

                        echo "<TH></TH>
                        </TR>"; 
                        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
                        echo "  
                        <TR BGCOLOR='$captioncolor'   ID='encabezado'>  
                                <TH COLSPAN='3' HEIGHT='5' ></TH>
                        </TR>
                        <TR ALIGN='center' VALIGN='middle' BGCOLOR='$captioncolor'   ID='encabezado'>

                                <TD> &nbsp;</TD>

                                <TD>

                                                <TABLE ALIGN='center' BORDER=0 CELLSPACING=1 CELLPADDING=1 BGCOLOR='$tablebgcolor'>

                                                <TR ALIGN='left' VALIGN='middle' BGCOLOR='$hedingcolor' ID='verysmall'>
                                                        <TH WIDTH='14.285%'>Lun</TH>
                                                        <TH WIDTH='14.285%'>Mar</TH>
                                                        <TH WIDTH='14.285%'>Mie</TH>
                                                        <TH WIDTH='14.285%'>Jue</TH>
                                                        <TH WIDTH='14.285%'>Vie</TH>
                                                        <TH WIDTH='14.285%'>Sab</TH>
                                                        <TH WIDTH='14.285%'>Dom</TH>
                                                </TR>\n";
                                                
                                                ////javascript: opener.document.".$forma.".".$campo.".value
                                                
                                                for( $i=0; $i<$rows; $i++)
                                                {

                                                        echo "<TR ALIGN='center'  VALIGN='middle' BGCOLOR='$businesdaycolor'  ID='encabezado'>\n";

                                                        //~~~~ Lunes ~~~~//             >:C
                                                        
                                                        $LU=($LUN[$i] > 0)?("".$LUN[$i].""):("&nbsp;");
                                                        $LUX=$LUN[$i]."/".$month."/".$setyear;  
                                                        $_fechadia = $setyear."-".$month."-".$LUN[$i];
                                                                                                                
                                                        if(empty($forma))                                                       
                                                                
                                                                
                                                                if(($rango) and (($_fechadia>$rmax) or ($_fechadia<$rmin)))
                                                                   {    $click = "";    $fgcolor=" COLOR='dimgray'      "; }                                                                   
                                                                elseif( ($past) and ($pivot < ($setyear.$month.$LUN[$i])) )
                                                                   {    $click = "";    $fgcolor=" COLOR='dimgray'      "; }                                            
                                                                elseif( ($future) and ($pivot > ($setyear.$month.$LUN[$i])) )
                                                                   {    $click = "";    $fgcolor=" COLOR='dimgray'      "; }
                                                                else
                                                                   {    
                                                                        $click =($LU>0 and !empty($campo) )?" onclick=\"javascript:dispatch('".$LUX."'); \" ":"";
                                                                        $fgcolor=" COLOR='black'        "; 
                                                                   }                                                                                                                    
                                                        else                                                    
                                                                
                                                                if(($rango) and (($_fechadia>$rmax) or ($_fechadia<$rmin)))
                                                                   {    $click = "";    $fgcolor=" COLOR='dimgray'      "; }                                                                   
                                                                elseif( ($past) and ($pivot < ($setyear.$month.$LUN[$i])) )
                                                                   { $click = ""; $fgcolor=" COLOR='dimgray'    "; }                                                                 
                                                                elseif( ($future) and ($pivot > ($setyear.$month.$LUN[$i])) )
                                                                   {    $click = ""; $fgcolor=" COLOR='dimgray' "; }    
                                                                else
                                                                   {    $click =($LU>0 and !empty($campo) )?" onclick=\"javascript:dispatch('".$LUX."'); \" ":"";
                                                                        $fgcolor=" COLOR='black'        "; 
                                                                   }
                                                                                                                        
                                                        if(!empty($maxdate))
                                                        {
                                                                if($_fechadia > $maxdate ) {$click = ""; $fgcolor=" COLOR='dimgray' ";}
                                                        }
                                                        
                                                        
                                                        $LU=($LUX == $fecha_hoy)?("<SPAN style='background-color: white;' $click ><FONT $fgcolor ><B>".$LUN[$i] ."</B></FONT></SPAN>"):($LUN[$i]);                                                                                                                              
                                                        $bg=($LUX == $fecha_hoy)?(" BGCOLOR='red' "):("");
                                                        
                                                        
                                                        $LU=($LUX == $markdate)?("<SPAN style='background-color: white;' $click ><FONT $fgcolor ><B>".$LUN[$i] ."</B></FONT></SPAN>"):($LU);                                                                                                                            
                                                        $bg=($LUX == $markdate)?(" BGCOLOR='$presetcolor' "):($bg);
                                                        
                                                        
                                                        
                                                        
                                                        echo "\t\t\t<TD $bg $script $click> <FONT $fgcolor ><B>".$LU." </B></FONT></TD>\n";




                                                        //~~~~ Martes ~~~~//    >:(
                                                        
                                                        $MA=($MAR[$i] > 0)?( $MAR[$i] ):("&nbsp;");
                                                        $MAX=$MAR[$i]."/".$month."/".$setyear;
                                                        $_fechadia = $setyear."-".$month."-".$MAR[$i];
                                                        
                                                        if(empty($forma))                                                       
                                                                if(($rango) and (($_fechadia>$rmax) or ($_fechadia<$rmin)))
                                                                   {    $click = "";    $fgcolor=" COLOR='dimgray'      "; }                                                                   
                                                                elseif( ($past) and ($pivot < ($setyear.$month.$MAR[$i])) )
                                                                   {    $click = "";    $fgcolor=" COLOR='dimgray'      "; }                                            
                                                                elseif( ($future) and ($pivot > ($setyear.$month.$MAR[$i])) )
                                                                   {    $click = "";    $fgcolor=" COLOR='dimgray'      "; }
                                                                else
                                                                   {    $click =($MA>0 and !empty($campo) )?" onclick=\"javascript:dispatch('".$MAX."'); \" ":"";
                                                                        $fgcolor=" COLOR='black'        "; 
                                                                   }                                                                                                                    
                                                        else                                                    
                                                                if(($rango) and (($_fechadia>$rmax) or ($_fechadia<$rmin)))
                                                                   {    $click = "";    $fgcolor=" COLOR='dimgray'      "; }                                                                   
                                                                elseif( ($past) and ($pivot < ($setyear.$month.$MAR[$i])) )
                                                                   { $click = ""; $fgcolor=" COLOR='dimgray'    "; }                                                                 
                                                                elseif( ($future) and ($pivot > ($setyear.$month.$MAR[$i])) )
                                                                   {    $click = ""; $fgcolor=" COLOR='dimgray' "; }    
                                                                else
                                                                   {    $click =($MA>0 and !empty($campo) )?" onclick=\"javascript: dispatch('".$MAX."'); \" ":"";
                                                                        $fgcolor=" COLOR='black'        "; 
                                                                   }  
                                                                   
                                                                   
                                                        if(!empty($maxdate))
                                                        {
                                                                if($_fechadia > $maxdate ) {$click = ""; $fgcolor=" COLOR='dimgray' ";}
                                                        }

                                                        $MA=($MAX== $fecha_hoy)?("<SPAN style='background-color: white;' $click ><FONT $fgcolor ><B>".$MAR[$i]."</B></FONT></SPAN>"):($MAR[$i]);                                                                                                                        
                                                        $bg=($MAX== $fecha_hoy)?(" BGCOLOR='red' "):("");

                                                        $MA=($MAX== $markdate)?("<SPAN style='background-color: white;' $click ><FONT $fgcolor ><B>".$MAR[$i]."</B></FONT></SPAN>"):($MA);                                                                                                                      
                                                        $bg=($MAX== $markdate)?(" BGCOLOR='$presetcolor' "):($bg);
                                                        
                                                        echo "\t\t\t<TD $bg $script $click><FONT $fgcolor ><B> ".$MA."</B></FONT></TD>\n";



                                                        //~~~~ Miércoles ~~~~//         :(

                                                        $MI=($MIE[$i] > 0)?("".$MIE[$i].""):("&nbsp;");
                                                        $MIX=$MIE[$i]."/".$month."/".$setyear;
                                                        $_fechadia = $setyear."-".$month."-".$MIE[$i];

                                                        if(empty($forma))                                                       
                                                                if(($rango) and (($_fechadia>$rmax) or ($_fechadia<$rmin)))
                                                                   {    $click = "";    $fgcolor=" COLOR='dimgray'      "; }                                                                   
                                                                elseif( ($past) and ($pivot < ($setyear.$month.$MIE[$i])) )
                                                                   {    $click = "";    $fgcolor=" COLOR='dimgray'      "; }                                            
                                                                elseif( ($future) and ($pivot > ($setyear.$month.$MIE[$i])) )
                                                                   {    $click = "";    $fgcolor=" COLOR='dimgray'      "; }
                                                                else
                                                                   {    $click =($MI>0 and !empty($campo) )?" onclick=\"javascript:dispatch('".$MIX."'); \" ":"";
                                                                        $fgcolor=" COLOR='black'        "; 
                                                                   }                                                                                                                    
                                                        else                                                    
                                                                if(($rango) and (($_fechadia>$rmax) or ($_fechadia<$rmin)))
                                                                   {    $click = "";    $fgcolor=" COLOR='dimgray'      "; }                                                                   
                                                                elseif( ($past) and ($pivot < ($setyear.$month.$MIE[$i])) )
                                                                   {    $click = "";    $fgcolor=" COLOR='dimgray'    "; }                                                                 
                                                                elseif( ($future) and ($pivot > ($setyear.$month.$MIE[$i])) )
                                                                   {    $click = ""; $fgcolor=" COLOR='dimgray' "; }    
                                                                else
                                                                   {    $click =($MI>0 and !empty($campo) )?" onclick=\"javascript: dispatch('".$MIX."'); \" ":"";
                                                                        $fgcolor=" COLOR='black'        "; 
                                                                   }                                                            
                                                                   
                                                        if(!empty($maxdate))
                                                        {
                                                                if($_fechadia > $maxdate ) {$click = ""; $fgcolor=" COLOR='dimgray' ";}
                                                        }

                                                        $MI=($MIX== $fecha_hoy)?("<SPAN style='background-color: white;' $click ><FONT $fgcolor ><B>".$MIE[$i]."</B></FONT></SPAN>"):($MIE[$i]);
                                                        $bg=($MIX== $fecha_hoy)?(" BGCOLOR='red' "):("");

                                                        $MI=($MIX== $markdate)?("<SPAN style='background-color: white;' $click ><FONT $fgcolor ><B>".$MIE[$i]."</B></FONT></SPAN>"):($MI);
                                                        $bg=($MIX== $markdate)?(" BGCOLOR='$presetcolor' "):($bg);
                                                        
                                                        echo "\t\t\t<TD $bg $script $click><FONT $fgcolor ><B> ".$MI." </B></FONT></TD>\n";


                                                        
                                                        //~~~~ Jueves ~~~~//            :|
                                                        
                                                        $JU=($JUE[$i] > 0)?("".$JUE[$i].""):("&nbsp;");
                                                        $JUX=$JUE[$i]."/".$month."/".$setyear;
                                                        $_fechadia = $setyear."-".$month."-".$JUE[$i];
                                                        
                                                        if(empty($forma))                                                       
                                                                if(($rango) and (($_fechadia>$rmax) or ($_fechadia<$rmin)))
                                                                   {    $click = "";    $fgcolor=" COLOR='dimgray'      "; }                                                                   
                                                                elseif( ($past) and ($pivot < ($setyear.$month.$JUE[$i])) )
                                                                   {    $click = "";    $fgcolor=" COLOR='dimgray'      "; }                                            
                                                                elseif( ($future) and ($pivot > ($setyear.$month.$JUE[$i])) )
                                                                   {    $click = "";    $fgcolor=" COLOR='dimgray'      "; }
                                                                else
                                                                   {    $click =($JU>0 and !empty($campo) )?" onclick=\"javascript:dispatch('".$JUX."'); \" ":"";
                                                                        $fgcolor=" COLOR='black'        "; 
                                                                   }                                                                                                                    
                                                        else                                                    
                                                                if(($rango) and (($_fechadia>$rmax) or ($_fechadia<$rmin)))
                                                                   {    $click = "";    $fgcolor=" COLOR='dimgray'      "; }                                                                   
                                                                elseif( ($past) and ($pivot < ($setyear.$month.$JUE[$i])) )
                                                                   { $click = ""; $fgcolor=" COLOR='dimgray'    "; }                                                                 
                                                                elseif( ($future) and ($pivot >($setyear.$month.$JUE[$i])) )
                                                                   {    $click = ""; $fgcolor=" COLOR='dimgray' "; }    
                                                                else
                                                                   {    $click =($JU>0 and !empty($campo) )?" onclick=\"javascript:dispatch('".$JUX."'); \" ":"";
                                                                        $fgcolor=" COLOR='black'        "; 
                                                                   }                                                            
                                                                   
                                                        if(!empty($maxdate))
                                                        {
                                                                if($_fechadia > $maxdate ) {$click = ""; $fgcolor=" COLOR='dimgray' ";}
                                                        }
                                                                   
                                                        $JU=($JUX == $fecha_hoy)?("<SPAN style='background-color: white;' $click ><FONT $fgcolor ><B>".$JUE[$i]."</B></FONT></SPAN>"):($JUE[$i]);                                                                                                               
                                                        $bg=($JUX == $fecha_hoy)?(" BGCOLOR='red' "):("");
                                                        
                                                        $JU=($JUX == $markdate)?("<SPAN style='background-color: white;' $click ><FONT $fgcolor ><B>".$JUE[$i]."</B></FONT></SPAN>"):($JU);                                                                                                             
                                                        $bg=($JUX == $markdate)?(" BGCOLOR='$presetcolor' "):($bg);
                                                        
                                                        echo "\t\t\t<TD $bg $script $click><FONT $fgcolor ><B> ".$JU." </B></FONT></TD>\n";     


                                                        
                                                        //~~~~ Viernes ~~~~//           :)
                                                        $VI=($VIE[$i] > 0)?("".$VIE[$i].""):("&nbsp;");
                                                        $VIX=$VIE[$i]."/".$month."/".$setyear;
                                                        $_fechadia = $setyear."-".$month."-".$VIE[$i];
                                                                                                                
                                                        if(empty($forma))                                                       
                                                                if(($rango) and (($_fechadia>$rmax) or ($_fechadia<$rmin)))
                                                                   {    $click = "";    $fgcolor=" COLOR='dimgray'      "; }                                                                   
                                                                elseif( ($past) and ($pivot < ($setyear.$month.$VIE[$i])) )
                                                                   {    $click = "";    $fgcolor=" COLOR='dimgray' "; }                                         
                                                                elseif( ($future) and ($pivot > ($setyear.$month.$VIE[$i])) )
                                                                   {    $click = "";    $fgcolor=" COLOR='dimgray'      "; }
                                                                else
                                                                   {    $click =($VI>0 and !empty($campo) )?" onclick=\"javascript:dispatch('".$VIX."'); \" ":"";
                                                                        $fgcolor=" COLOR='black'        "; 
                                                                   }                                                                                                                    
                                                        else                                                    
                                                                if(($rango) and (($_fechadia>$rmax) or ($_fechadia<$rmin)))
                                                                   {    $click = "";    $fgcolor=" COLOR='dimgray'      "; }                                                                   
                                                                elseif( ($past) and ($pivot < ($setyear.$month.$VIE[$i])) )
                                                                   { $click = ""; $fgcolor=" COLOR='dimgray' "; }                                                                    
                                                                elseif( ($future) and ($pivot > ($setyear.$month.$VIE[$i])) )
                                                                   {    $click = ""; $fgcolor=" COLOR='dimgray' "; }    
                                                                else
                                                                   {    $click =($VI>0 and !empty($campo) )?" onclick=\"javascript:dispatch('".$VIX."'); \" ":"";
                                                                        $fgcolor=" COLOR='black'        "; 
                                                                   }                                                            
                                                                   
                                                        if(!empty($maxdate))
                                                        {
                                                                if($_fechadia > $maxdate ) {$click = ""; $fgcolor=" COLOR='dimgray' ";}
                                                        }
                                                        
                                                        $VI=($VIX== $fecha_hoy)?("<SPAN style='background-color: white;' $click ><FONT $fgcolor ><B>".$VIE[$i]."</B></FONT></SPAN>"):($VIE[$i]);                                                                                                                
                                                        $bg=($VIX== $fecha_hoy)?(" BGCOLOR='red' "):("");

                                                        $VI=($VIX== $markdate)?("<SPAN style='background-color: white;' $click ><FONT $fgcolor ><B>".$VIE[$i]."</B></FONT></SPAN>"):($VI);                                                                                                              
                                                        $bg=($VIX== $markdate)?(" BGCOLOR='$presetcolor' "):($bg);

                                                        echo "\t\t\t<TD $bg $script $click><FONT $fgcolor ><B> ".$VI." </B></FONT></TD>\n";
                                                        


                                                        //~~~~ Sábado ~~~~//            :D
                                                        $SA=($SAB[$i] > 0)?("".$SAB[$i].""):("&nbsp;");
                                                        $SAX=$SAB[$i]."/".$month."/".$setyear;
                                                        $_fechadia = $setyear."-".$month."-".$SAB[$i];
                                                                                                                                                                        
                                                        if(empty($forma))                                                       
                                                                if(($rango) and (($_fechadia>$rmax) or ($_fechadia<$rmin)))
                                                                   {    $click = "";    $fgcolor=" COLOR='dimgray'      "; }                                                                   
                                                                elseif( ($past) and ($pivot < ($setyear.$month.$SAB[$i])) )
                                                                   {    $click = "";    $fgcolor=" COLOR='dimgray' "; }                                         
                                                                elseif( ($future) and ($pivot > ($setyear.$month.$SAB[$i])) )
                                                                   {    $click = "";    $fgcolor=" COLOR='dimgray'      "; }
                                                                else
                                                                   {    $click =($SA>0 and !empty($campo) )?" onclick=\"javascript:dispatch('".$SAX."'); \" ":"";
                                                                        $fgcolor=" COLOR='black'        "; 
                                                                   }                                                                                                                    
                                                        else                                                    
                                                                if(($rango) and (($_fechadia>$rmax) or ($_fechadia<$rmin)))
                                                                   {    $click = "";    $fgcolor=" COLOR='dimgray'      "; }                                                                   
                                                                elseif( ($past) and ($pivot < ($setyear.$month.$SAB[$i])) )
                                                                   { $click = ""; $fgcolor=" COLOR='dimgray' "; }                                                                    
                                                                elseif( ($future) and ($pivot > ($setyear.$month.$SAB[$i])) )
                                                                   {    $click = ""; $fgcolor=" COLOR='dimgray' "; }    
                                                                else
                                                                   {    $click =($SA>0 and !empty($campo) )?" onclick=\"javascript: dispatch('".$SAX."');  \" ":"";
                                                                        $fgcolor=" COLOR='black'        "; 
                                                                   }                                                            
                                                                   
                                                        if(!empty($maxdate))
                                                        {
                                                                if($_fechadia > $maxdate ) {$click = ""; $fgcolor=" COLOR='dimgray' ";}
                                                        }
                                                                                                                
                                                        
                                                        $SA=($SAX == $fecha_hoy)?("<SPAN style='background-color: white;' $click ><FONT $fgcolor ><B>".$SAB[$i]."</B></FONT></SPAN>"):($SAB[$i]);                                                                                                               
                                                        $bg=($SAX == $fecha_hoy)?(" BGCOLOR='red' "):(" BGCOLOR='$weekenddaycolor' ");
                                                        
                                                        $SA=($SAX == $markdate)?("<SPAN style='background-color: white;' $click ><FONT $fgcolor ><B>".$SAB[$i]."</B></FONT></SPAN>"):($SA);                                                                                                             
                                                        $bg=($SAX == $markdate)?(" BGCOLOR='$presetcolor' "):($bg);
                                                        
                                                        echo "\t\t\t<TD $bg $script $click ><FONT $fgcolor ><B>".$SA." </B></FONT></TD>\n";     


                                                        
                                                        //~~~~ Domingo ~~~~//           ;D

                                                        $DO=($DOM[$i] > 0)?("".$DOM[$i].""):("&nbsp;");
                                                        $DOX=$DOM[$i]."/".$month."/".$setyear;  
                                                        $_fechadia = $setyear."-".$month."-".$DOM[$i];

                                                        if(empty($forma))                                                       
                                                                if(($rango) and (($_fechadia>$rmax) or ($_fechadia<$rmin)))
                                                                   {    $click = "";    $fgcolor=" COLOR='dimgray'      "; }                                                                   
                                                                elseif( ($past) and ($pivot < ($setyear.$month.$DOM[$i])) )
                                                                   {    $click = "";    $fgcolor=" COLOR='dimgray' "; }                                         
                                                                elseif( ($future) and ($pivot > ($setyear.$month.$DOM[$i])) )
                                                                   {    $click = "";    $fgcolor=" COLOR='dimgray'      "; }
                                                                else
                                                                   {    $click =($DO>0 and !empty($campo) )?" onclick=\"javascript:dispatch('".$DOX."');  \" ":"";
                                                                        $fgcolor=" COLOR='black'        "; 
                                                                   }                                                                                                                    
                                                        else                                                    
                                                                if(($rango) and (($_fechadia>$rmax) or ($_fechadia<$rmin)))
                                                                   {    $click = "";    $fgcolor=" COLOR='dimgray'      "; }                                                                   
                                                                elseif( ($past) and ($pivot < ($setyear.$month.$DOM[$i])) )
                                                                   { $click = ""; $fgcolor=" COLOR='dimgray' "; }                                                                    
                                                                elseif( ($future) and ($pivot > ($setyear.$month.$DOM[$i])) )
                                                                   {    $click = ""; $fgcolor=" COLOR='dimgray' "; }    
                                                                else
                                                                   {    $click =($DO>0 and !empty($campo) )?" onclick=\"javascript:dispatch('".$DOX."'); \" ":"";
                                                                        $fgcolor=" COLOR='black'        "; 
                                                                   }    
                                                                   
                                                        if(!empty($maxdate))
                                                        {
                                                                if($_fechadia > $maxdate ) {$click = ""; $fgcolor=" COLOR='dimgray' ";}
                                                        }

                                                                                                                
                                                        $DO=($DOX == $fecha_hoy)?("<SPAN style='background-color: white;' $click ><B>".$DOM[$i]."</B></SPAN>"):($DOM[$i]);
                                                        $bg=($DOX == $fecha_hoy)?(" BGCOLOR='red' "):(" BGCOLOR='$weekenddaycolor' ");
                                                        
                                                        $DO=($DOX == $markdate)?("<SPAN style='background-color: white;' $click ><B>".$DOM[$i]."</B></SPAN>"):($DO);
                                                        $bg=($DOX == $markdate)?(" BGCOLOR='$presetcolor' "):($bg);
                                                        
                                                        echo "\t\t\t<TD $bg $script $click ><FONT $fgcolor ><B> ".$DO." </B></FONT></TD>\n";            
                                                        echo "</TR> \n\n";

                                                }

                                                echo "</TABLE>";

                                echo "  
                                </TD>

                                <TD> &nbsp;</TD>

                        </TR>


                        <TR ALIGN='center' VALIGN='middle' BGCOLOR='$captioncolor'  ID='encabezado'>
                                <TD COLSPAN=3 HEIGHT='5'></TD>
                        </TR>   


                        <TR ALIGN='center' VALIGN='middle' BGCOLOR='$captioncolor'  ID='encabezado'>
                                <TD COLSPAN=3>
                                         <B><FONT COLOR='white' >";
                        
                                                
                        echo " <BUTTON ID='smallbold' onclick=\"javascript: document.mycalendar.setmonth.value ='".($M)."'; document.mycalendar.setyear.value ='".($Y)."'; document.mycalendar.submit(); \">Hoy</BUTTON> ";
 
 
 
 
 
                        echo " : $fecha_extendida</FONT></B>
                                </TD>
                        </TR>           

                        <TR ALIGN='center' VALIGN='middle' BGCOLOR='$captioncolor'  ID='encabezado'>
                                <TD COLSPAN=3 HEIGHT='5'></TD>
                        </TR>           

                </TABLE>
</TD>
</TR>           
</TABLE>";
        
echo "</FORM>\n";       
        
?>      

</BODY>
</HTML> 
        