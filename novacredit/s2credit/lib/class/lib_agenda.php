<?
/*
ALTER TABLE `grupo_solidario_control_asistencia`
        ADD COLUMN `FechaReagendado` DATE NULL DEFAULT NULL AFTER `FechaAgenda`



*/

class TAGENDA
{

var $hoy;
var $mes = array();
var $dia = array();

var $fecha_hoy; 
var $fecha_extendida;



var  $setyear  ;
var  $setmonth ;


var $table_bg_color     = "black";
var $caption_color      = "steelblue";
var $caption_text_color = "white";

var $day_caption_color  = "lightsteelblue";
var $heading_color      = "white";

var $business_day_color      = "white";
var $none_business_day_color = "#FFFF80";


var $weekend_day_color  = "lightskyblue";


var $today_color        = "blue";
var $setdate_color      = "magenta";

var $week_row_color     = "white";
var $weeks_in_month     = 4;
var $none_color         = "gray";

 
var $inicio_solidario_color = "blue";
var $vence_cuota_solidario_color = "green";

var $hint_support = 0;

var $despliega_inicio_solidarios       = false;
var $despliega_vencimientos_solidarios = false;
var $listado_sucursales                = array();

var $schedule_inicio_solidarios_monthly = array();
var $schedule_vencimientos_monthly      = array();



var $despliega_agenda_personal         = false;

var $filtrar_sucursales = 0;


function TAGENDA($startdate, &$db)
{

   
   $this->db = $db;
   
   list($_y,$_m,$_d) = explode("-",$startdate);
   
   if(checkdate($_m,$_d,$_y))
      $this->hoy=mktime(0,0,0,$_m,$_d,$_y);
   else
      $this->hoy=mktime(0,0,0,date("m"),date("d"),date("Y"));


   $this->startdate = strftime("%Y-%m-%d",$this->hoy); 
   $this->fecha_hoy = date("Y-m-d");


   $this->dia[0] = "Domingo";
   $this->dia[1] = "Lunes";
   $this->dia[2] = "Martes";
   $this->dia[3] = "Mi&eacute;rcoles";
   $this->dia[4] = "Jueves";
   $this->dia[5] = "Viernes";
   $this->dia[6] = "S&aacute;bado";



   $this->mes[1] = "Enero";
   $this->mes[2] = "Febrero";
   $this->mes[3] = "Marzo";
   $this->mes[4] = "Abril";
   $this->mes[5] = "Mayo";
   $this->mes[6] = "Junio";
   $this->mes[7] = "Julio";
   $this->mes[8] = "Agosto";
   $this->mes[9] = "Septiembre";
   $this->mes[10]= "Octubre";
   $this->mes[11]= "Noviembre";
   $this->mes[12]= "Diciembre";  

   $this->W= strftime("%w",$this->hoy);   // Dia de la semana
   $this->M= (int) strftime("%m",$this->hoy);
   $this->D= (int) strftime("%d",$this->hoy);
   $this->Y= strftime("%Y",$this->hoy);

   
   
   
   
   $this->fecha_extendida = $this->dia[$this->W]." ".$this->D." de ".substr($this->mes[$this->M],0,3)." ".$this->Y;





   $this->setyear  = (empty($setyear ))?($this->Y):($this->setyear );
   $this->setmonth = (empty($setmonth))?($this->M):($this->setmonth);


   $atime = mktime(0,0,0,$this->setmonth,1,$this->setyear ) ;        
   $setmday  = date( "d",$atime);
   $setwday  = date( "w",$atime);

        
    $this->DOM = array();
    $this->LUN = array();
    $this->MAR = array();
    $this->MIE = array();
    $this->JUE = array();
    $this->VIE = array();
    $this->SAB = array();
        
                
    $fist_day=1;
    $last_day=date("t",$atime);
    
    
    
    $week_num = 0;
    
    for($i= $fist_day; $i<=$last_day; $i++)
    {
    
        $_day   = ($i<10)?("0".$i):($i);
        $_month = ($this->setmonth<10)?("0".$this->setmonth):($this->setmonth);
    
        $tmp_date = $this->setyear."-".$_month."-".$_day ;
        
        
        
        $tmp_w_day = date("w",mktime(0,0,0,$this->setmonth,$i,$this->setyear));
        
    
        switch($tmp_w_day)
        {
                case 0 :$this->DOM[$week_num]=  $tmp_date; $week_num++;break;
                case 1 :$this->LUN[$week_num]=  $tmp_date; break;
                case 2 :$this->MAR[$week_num]=  $tmp_date; break;
                case 3 :$this->MIE[$week_num]=  $tmp_date; break;
                case 4 :$this->JUE[$week_num]=  $tmp_date; break;
                case 5 :$this->VIE[$week_num]=  $tmp_date; break;
                case 6 :$this->SAB[$week_num]=  $tmp_date; break;
        
        }
    
        if($tmp_date == $this->startdate)
        {

                $this->week_num_of_startdate = $week_num;
        }
    
    
    
    }
    
    
   //Si el último día del mes cae en domingo el numero de semanas en el mes es de $week_num, si no, es $week_num+1 Dado que comenzamos la cuenta en ZERO
   
   if($tmp_w_day == 0 ) 
      $last_week_of_month = $week_num;
   else
      $last_week_of_month = $week_num+1;


   $this->weeks_in_month = $last_week_of_month;
   

//   Contar el maximo de días de la semana en el mes no es una solución general para saber cuantas semanas hay el el mes, ej mayo del 2010 debe tener 6 semanas.
//   $this->weeks_in_month = max(count($this->DOM), count($this->LUN), count($this->MAR), count($this->MIE), count($this->JUE), count($this->VIE), count($this->SAB) );  



        
   $this->month_squema = array();
   $this->month_squema['LUN'] = & $this->LUN ;
   $this->month_squema['MAR'] = & $this->MAR ;
   $this->month_squema['MIE'] = & $this->MIE ;
   $this->month_squema['JUE'] = & $this->JUE ;
   $this->month_squema['VIE'] = & $this->VIE ;
   $this->month_squema['SAB'] = & $this->SAB ;
   $this->month_squema['DOM'] = & $this->DOM ;
   
}

function VerInicioSolidarios($state)
{
   $this->despliega_inicio_solidarios       = ($state > 0);

}

function VerVencimientosSolidarios($state)
{

   $this->despliega_vencimientos_solidarios  = ($state > 0);

}


function VerUnicamentePromotor($id_promo)
{
      $this->filtrar_promotores = 1;

   $this->id_promotor  = $id_promo;
}



function VerUnicamenteSucursales($alistado)
{
      $this->filtrar_sucursales=1;
      
      
      if(count($alistado))
        $this->listado_sucursales = $alistado;

      $this->listado_sucursales[] = 0;
}




function show_squema_by_week_day_schedule($fecha,$heading)
{

        $output="";
        $concepto  = "";

        list($_y,$_m,$_d) = explode("-",$fecha);

        
        
        

        $msg_hint_display = "Imprimir fichas de depósito de los grupos que <br>presentan vencimiento a ésta fecha. ";
        
        $evento= "";
        
        if($this->day_pop_support == 1 )
        $evento= " onClick=\"show_day_window('".$fecha."')\" ";
        
        //onMouseover=\"showhint('".$msg_hint_display."', this, event, '300px');\"


        $output.="\t<TABLE STYLE='height:5px'; ALIGN='center' BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH='100%'  >";
        $output.="\n<TR STYLE='color:black; background-color:".$this->day_caption_color .";'>";
        $output.="\n    <TH ALIGN='right' VALIGN='middle' ID='small'><A ID='".$_d."' STYLE='cursor:pointer;' ".$evento." >".$_d."</A></TH></TR>";
        $output.="\n</TABLE>\n";

        $output.="\n<DIV STYLE='text-align:left;  overflow:auto; width:100%; height:88%; ' >\n";
        
       
        $output.=$heading;




        if($this->despliega_vencimientos_solidarios)
        {
        
                $sch2 = $this->getSchedule_Vencimientos_Solidarios($fecha);
                foreach($sch2 AS $key=>$row)
                {
                
                  $msg = $row[8]." ".$row[1].". vence cuota ".$row[0];
                
                  $hint_msg="";
                  if($this->hint_support==1)
                  {

                     if((!empty($row[6])) and ($row[6] != $row[5] ))
                     {
                             $msg_display = "Vence la cuota # : <b>".$row[0]."</b> el dia <b>".ffecha($row[5])."</b><br>";
                             $msg_display.= "<center><b><U>La junta se reagendó para el ".ffecha($row[6])." </U></b></center><br>";
                             
                             $stybg = " background-color:yellow; ";
                     }
                     else
                     {

                             $msg_display = "Vence la cuota # : <b>".$row[0]."</b><br>";

                             $stybg = " ";

                     }
                     
                  }
                
                
                
                
                
                
                
                  $output.="<SPAN onClick='javascript:ListaAsistencia(\"".$row[7]."\",\"".$row[5]."\")'    STYLE='color: ".$this->vence_cuota_solidario_color."; ".$stybg." cursor:pointer; font-size:9px; font-style: bold; font-family: Arial, Verdana, Helvetica, sans-serif;'  onmouseover=\"javascript: this.style.cursor='pointer'; ".$hint_msg." \" onmouseout=\"this.style.cursor=''; \" >&nbsp;".$msg."</SPAN><br/>";
                }
        
        }





        $output.="</DIV>";
        
        return($output);

}



function show_squema_by_month_day_schedule($fecha,$heading)
{

        $output="";
        $concepto  = "";

        list($_y,$_m,$_d) = explode("-",$fecha);

        
        
        

        $msg_hint_display = "Imprimir fichas de depósito de los grupos que <br>presentan vencimiento a ésta fecha. ";
        
        $evento= "";
        
        if($this->day_pop_support == 1 )
        $evento= " onClick=\"show_day_window('".$fecha."')\" ";
        
        //onMouseover=\"showhint('".$msg_hint_display."', this, event, '300px');\"


        $output.="\t<TABLE STYLE='height:5px'; ALIGN='center' BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH='100%'  >\n";
        $output.="\n<TR STYLE='color:black; background-color:".$this->day_caption_color .";'>\n";
        $output.="\n    <TH ALIGN='right' VALIGN='middle' ID='small'><A ID='".$_d."' STYLE='cursor:pointer;' ".$evento." >".$_d."</A></TH></TR>\n";
        $output.="\n</TABLE>\n";

        $output.="\n<DIV STYLE='text-align:left;  overflow:auto; width:100%; height:88%; ' >\n";
        
       
        $output.=$heading;


        if($this->despliega_inicio_solidarios)
        {
        
                $sch1 = $this->getSchedule_Inico_Solidarios($fecha);
                
                foreach($sch1 AS $row)
                {
                  
                  $msg = "Inicia ".$row[0]." ciclo ".$row[1];
                  
                  $hint_msg="";
                  if($this->hint_support==1)
                  {

                     $msg_display = "Inicia el grupo : <b>".$row[0]."</b>, Ciclo <b># ".$row[1]."</b><br>\n".
                                    "Monto : <b>$".number_format($row[3],2)."</b><br>\n".
                                    "Promotor : <b>".ucwords(strtolower($row[4]))."</b><br>\n".                                    
                                    "Sucursal : <b>".$row[2]."</b>\n";
                     $hint_msg=" showhint('".$msg_display."', this, event, '300px');\n ";
                  }
                  
                  
                  $output.="<SPAN STYLE='color: ".$this->inicio_solidario_color."; cursor:pointer; font-size:9px; font-style: bold; font-family: Arial, Verdana, Helvetica, sans-serif;' onmouseover=\"javascript:this.style.backgroundColor=''; this.style.cursor='hand'; ".$hint_msg."\" onmouseout=\"javascript:  this.style.backgroundColor='' \" >&nbsp;".$msg."</SPAN><br/>";
                }
        
        }


        if($this->despliega_vencimientos_solidarios)
        {
        
                $sch2 = $this->getSchedule_Vencimientos_Solidarios($fecha);
                foreach($sch2 AS $key=>$row)
                {
                
                  $msg = "Cuota # ".$row[0]." de ".$row[1];
                
                  $hint_msg="";
                  if($this->hint_support==1)
                  {

                     if((!empty($row[6])) and ($row[6] != $row[5] ))
                     {
                             $msg_display = "Vence la cuota # : <b>".$row[0]."</b> el dia <b>".ffecha($row[5])."</b><br>";
                             $msg_display.= "<center><b><U>La junta se reagendó para el ".ffecha($row[6])." </U></b></center><br>";
                             
                             $stybg = " background-color:yellow; ";
                     }
                     else
                     {

                             $msg_display = "Vence la cuota # : <b>".$row[0]."</b><br>";

                             $stybg = " ";

                     }
                     
                     $msg_display.= "Del grupo <b>".$row[1]."</b><br>".
                                    "Monto : <b>$".number_format($row[3],2)."</b><br>".
                                    "Promotor : <b>".ucwords(strtolower($row[4]))."</b><br>".                                    
                                    "Sucursal : <b>".$row[2]."</b>";

                     $hint_msg=" showhint('".$msg_display."', this, event, '300px'); ";
                  }
                
                
                
                
                
                
                
                  $output.="<SPAN onClick='javascript:ListaAsistencia(\"".$row[7]."\",\"".$row[5]."\")'    STYLE='color: ".$this->vence_cuota_solidario_color."; ".$stybg." cursor:pointer; font-size:9px; font-style: bold; font-family: Arial, Verdana, Helvetica, sans-serif;'  onmouseover=\"javascript: this.style.cursor='pointer'; ".$hint_msg." \" onmouseout=\"this.style.cursor=''; \" >&nbsp;".$msg."</SPAN><br/>";
                }
        
        }





        $output.="</DIV>\n\n\n";
        
        return($output);

}






function getSchedule_Inicio_Solidarios_Monthly()
{

 //  debug($this->y);
 //  $this->y++;


        $this->schedule_inicio_solidarios_monthly = array();

        $ini_date =  date("Y-m-d",mktime(0,0,0,$this->M,1,$this->Y ));
        
        $fin_date =  date("Y-m-t",mktime(0,0,0,$this->M,1,$this->Y ));
/*
           
           $sql="SELECT grupo_solidario.Nombre, 
                        grupo_solidario.Ciclo_gpo,
                        sucursales.Nombre AS Sucursal,
                        SUM(fact_cliente.Capital) AS Capital,
                        promotores.Nombre AS Promotor,
                        fact_cliente.Fecha_Inicio AS Fecha


                 FROM grupo_solidario,
                      grupo_solidario_integrantes,
                      fact_cliente

                 LEFT JOIN sucursales   ON      fact_cliente.ID_Sucursal  = sucursales.ID_Sucursal

                 LEFT JOIN grupo_solidario_promotor ON grupo_solidario_promotor.ID_grupo_soli = grupo_solidario.ID_grupo_soli and grupo_solidario_promotor.Ciclo_gpo = grupo_solidario_integrantes.Ciclo_gpo  

                 LEFT JOIN promotores ON promotores.Num_promo = grupo_solidario_promotor.ID_Promotor




                 WHERE  fact_cliente.id_factura = grupo_solidario_integrantes.id_factura and
                        grupo_solidario_integrantes.ID_grupo_soli = grupo_solidario.ID_grupo_soli and 
                        fact_cliente.Fecha_Inicio BETWEEN '".$ini_date."' and '".$fin_date."' ";




                if($this->filtrar_sucursales==1) 
                {

                    $sql.=" and fact_cliente.ID_Sucursal IN (".implode(",",$this->listado_sucursales).") ";
                }



                if($this->filtrar_promotores==1) 
                {

                        $sql .= " and promotores.Num_promo     = '".$this->id_promotor."'  ";


                }


            $sql.="\n GROUP BY  grupo_solidario_integrantes.ID_grupo_soli  ORDER BY Fecha, Sucursal, Nombre, Ciclo_gpo ";
*/
       // debug($sql);
       // die();



           $sql="SELECT	grupo_solidario.Nombre,
			grupo_solidario.Ciclo_gpo,
			sucursales.Nombre AS Sucursal,
			SUM(fact_cliente.Capital) AS Capital,
			promotores.Nombre AS Promotor,
			fact_cliente.Fecha_Inicio AS Fecha


		FROM grupo_solidario

		INNER JOIN grupo_solidario_integrantes ON grupo_solidario_integrantes.ID_grupo_soli = grupo_solidario.ID_grupo_soli 
		INNER JOIN fact_cliente                ON fact_cliente.id_factura                   = grupo_solidario_integrantes.id_factura 
						    

		LEFT JOIN sucursales ON fact_cliente.ID_Sucursal = sucursales.ID_Sucursal

		LEFT JOIN grupo_solidario_promotor ON grupo_solidario_promotor.ID_grupo_soli = grupo_solidario.ID_grupo_soli 
						  AND grupo_solidario_promotor.Ciclo_gpo     = grupo_solidario_integrantes.Ciclo_gpo

		LEFT JOIN promotores ON promotores.Num_promo = grupo_solidario_promotor.ID_Promotor


		WHERE  fact_cliente.Fecha_Inicio BETWEEN '".$ini_date."' and '".$fin_date."' \n";




                if($this->filtrar_sucursales==1) 
                {

                    $sql.="\n and fact_cliente.ID_Sucursal IN (".implode(",",$this->listado_sucursales).") \n";
                }



                if($this->filtrar_promotores==1) 
                {

                        $sql .= "\n and promotores.Num_promo     = '".$this->id_promotor."'  \n";


                }


       $sql.="\n GROUP BY grupo_solidario_integrantes.ID_grupo_soli

		ORDER BY fact_cliente.Fecha_Inicio, sucursales.Nombre, grupo_solidario.Nombre, grupo_solidario.Ciclo_gpo ";









            $rs=$this->db->Execute($sql);


            $j=0;
            if($rs->_numOfRows)
               while(! $rs->EOF)
               {

                 $i =$rs->fields['Fecha'];

                // $this->schedule_inicio_solidarios_monthly[$i] = array();

                 $this->schedule_inicio_solidarios_monthly[$i][$j]['Nombre']   =$rs->fields['Nombre']     ;
                 $this->schedule_inicio_solidarios_monthly[$i][$j]['Ciclo_gpo']=$rs->fields['Ciclo_gpo']  ;
                 $this->schedule_inicio_solidarios_monthly[$i][$j]['Sucursal'] =$rs->fields['Sucursal']   ;
                 $this->schedule_inicio_solidarios_monthly[$i][$j]['Capital']  =$rs->fields['Capital']    ;
                 $this->schedule_inicio_solidarios_monthly[$i][$j]['Promotor'] =$rs->fields['Promotor']   ;

                ++$j;

                 $rs->MoveNext();
                 
                 if($rs->fields['Fecha'] != $i) $j=0;
               }


          //  debug(print_r($this->schedule_inicio_solidarios_monthly,1));
          //  die();

            return;


}


function getSchedule_Inico_Solidarios($fecha)
{

    $return = array();
    $i=0;
    if(count($this->schedule_inicio_solidarios_monthly[$fecha])> 0)
       foreach($this->schedule_inicio_solidarios_monthly[$fecha] AS $key => $row)
       {

        //debug("< Fecha : ".$fecha."> ::".print_r($row,1) );
        
        
         $return[$i]['0']=$row['Nombre']     ;
         $return[$i]['1']=$row['Ciclo_gpo']  ;
         $return[$i]['2']=$row['Sucursal']   ;
         $return[$i]['3']=$row['Capital']    ;
         $return[$i]['4']=$row['Promotor']   ;
        
         $i++;
    
       }
    
    
    
    
    return($return);
    

}


function getSchedule_Vencimientos_Monthly()
{
   
   if( $this->x > 0) return;
   
   $this->schedule_vencimientos_monthly= array();
   
   
   $ini_date =  date("Y-m-d",mktime(0,0,0,$this->M,1,$this->Y ));
   
   $fin_date =  date("Y-m-t",mktime(0,0,0,$this->M,1,$this->Y ));

  // debug($this->x);
  // $this->x++;
   
   

   $sql="SELECT cargos.ID_Cargo                                         AS ID_Cargo,
                grupo_solidario.Nombre                                  AS Nombre,
                sucursales.Nombre                                       AS Sucursal,
                SUM(cargos.Monto)                                       AS Cuota,
                promotores.Nombre                                       AS Promotor,
                 cargos.Fecha_vencimiento                               AS FechaOriginal,
                 grupo_solidario_control_asistencia.FechaReagendado     AS FechaReagendado,
                 grupo_solidario.ID_grupo_soli                          AS ID_Grupo_Soli,
               IF(grupo_solidario_control_asistencia.FechaReagendado IS NULL, cargos.Fecha_vencimiento, grupo_solidario_control_asistencia.FechaReagendado) AS FechaAgenda,
               IF(grupo_solidario_control_asistencia.FechaReagendado IS NULL, '00:00:00', grupo_solidario_control_asistencia.Hora_Reunion) AS Hora_Reunion
                
         
         FROM   fact_cliente 
              


         INNER JOIN  cargos                      ON cargos.Num_compra =  fact_cliente.num_compra  and cargos.Activo='Si'
         INNER JOIN  grupo_solidario_integrantes ON fact_cliente.id_factura = grupo_solidario_integrantes.id_factura 
         INNER JOIN  grupo_solidario             ON grupo_solidario_integrantes.ID_grupo_soli = grupo_solidario.ID_grupo_soli

         LEFT JOIN sucursales                    ON fact_cliente.ID_Sucursal  = sucursales.ID_Sucursal

         LEFT JOIN grupo_solidario_promotor      ON grupo_solidario_promotor.ID_grupo_soli = grupo_solidario.ID_grupo_soli and grupo_solidario_promotor.Ciclo_gpo = grupo_solidario_integrantes.Ciclo_gpo  
        
         LEFT JOIN promotores ON promotores.Num_promo = grupo_solidario_promotor.ID_Promotor


         LEFT JOIN grupo_solidario_control_asistencia USE INDEX (Unicidad) ON grupo_solidario_control_asistencia.ID_grupo_soli = grupo_solidario.ID_grupo_soli and
                                                        grupo_solidario_control_asistencia.FechaAgenda   = cargos.Fecha_vencimiento




         WHERE   cargos.ID_Concepto=-3           and
                fact_cliente.ID_Tipocredito = 2 and

                 ((grupo_solidario_control_asistencia.FechaReagendado IS NULL and cargos.Fecha_vencimiento  BETWEEN '".$ini_date."' and '".$fin_date."' ) or                  
                  (grupo_solidario_control_asistencia.FechaReagendado  BETWEEN '".$ini_date."' and '".$fin_date."')) \n";



        if($this->filtrar_sucursales==1)
        {
        
            $sql.="\n and fact_cliente.ID_Sucursal IN (".implode(",",$this->listado_sucursales).") \n";
        }

        if($this->filtrar_promotores==1) 
        {
        
                $sql .= "\n and promotores.Num_promo     = '".$this->id_promotor."' \n";


        }


        $sql.="\n GROUP BY  grupo_solidario_integrantes.ID_grupo_soli,  cargos.Fecha_vencimiento
                  ORDER BY FechaAgenda,  Hora_Reunion, Nombre ";

    
        

        $rs=$this->db->Execute($sql);
    
    


            $j=0;
            if($rs->_numOfRows)
               while(! $rs->EOF)
               {

                 $i =$rs->fields['FechaAgenda'];


                 $this->schedule_vencimientos_monthly[$i][$j]['ID_Cargo']       = $rs->fields['ID_Cargo'];
                 $this->schedule_vencimientos_monthly[$i][$j]['Nombre']         = $rs->fields['Nombre'];
                 $this->schedule_vencimientos_monthly[$i][$j]['Sucursal']       = $rs->fields['Sucursal'];
                 $this->schedule_vencimientos_monthly[$i][$j]['Cuota']          = $rs->fields['Cuota'];
                 $this->schedule_vencimientos_monthly[$i][$j]['Promotor']       = $rs->fields['Promotor'];
                 $this->schedule_vencimientos_monthly[$i][$j]['FechaOriginal']  = $rs->fields['FechaOriginal'];
                 $this->schedule_vencimientos_monthly[$i][$j]['FechaReagendado']= $rs->fields['FechaReagendado'];
                 $this->schedule_vencimientos_monthly[$i][$j]['ID_Grupo_Soli']  = $rs->fields['ID_Grupo_Soli'];
                 $this->schedule_vencimientos_monthly[$i][$j]['Hora_Reunion']   = $rs->fields['Hora_Reunion'];

                ++$j;

                 $rs->MoveNext();
                 
                 if($rs->fields['FechaAgenda'] != $i) $j=0;
               }


            //debug(print_r($this->schedule_vencimientos_monthly,1));
            //die();

            return;
   
    
    
    
    



}






function getSchedule_Vencimientos_Weekly()
{
   
   if( $this->x > 0) return;
   
   $this->schedule_vencimientos_monthly= array();
   
   


   $w =  date("w",mktime(0,0,0,$this->M,$this->D,$this->Y ));
   
   
   
   
   if($w == 0)
   {
       $dw_ini = -6;
       $dw_fin = 0;
   }
   else
   {
   
       $dw_ini = ($w-1)* -1;
       
       $dw_fin = 6-$w;
   }
   
   
   


   $ini_date =  date("Y-m-d",mktime(0,0,0,$this->M,($this->D + $dw_ini),$this->Y ));
   
   $fin_date =  date("Y-m-d",mktime(0,0,0,$this->M,($this->D + $dw_fin) ,$this->Y ));

   
   

   $sql="SELECT cargos.ID_Cargo                                         AS ID_Cargo,
                grupo_solidario.Nombre                                  AS Nombre,
                sucursales.Nombre                                       AS Sucursal,
                SUM(cargos.Monto)                                       AS Cuota,
                promotores.Nombre                                       AS Promotor,
                cargos.Fecha_vencimiento                               AS FechaOriginal,
                grupo_solidario_control_asistencia.FechaReagendado     AS FechaReagendado,
                grupo_solidario.ID_grupo_soli                          AS ID_Grupo_Soli,
               IF(grupo_solidario_control_asistencia.FechaReagendado IS NULL, cargos.Fecha_vencimiento, grupo_solidario_control_asistencia.FechaReagendado) AS FechaAgenda,
               IF(grupo_solidario_control_asistencia.FechaReagendado IS NULL, '00:00:00', grupo_solidario_control_asistencia.Hora_Reunion) AS Hora_Reunion
                
         
         FROM   fact_cliente 
              


         INNER JOIN  cargos                      ON cargos.Num_compra =  fact_cliente.num_compra  and cargos.Activo='Si'
         INNER JOIN  grupo_solidario_integrantes ON fact_cliente.id_factura = grupo_solidario_integrantes.id_factura 
         INNER JOIN  grupo_solidario             ON grupo_solidario_integrantes.ID_grupo_soli = grupo_solidario.ID_grupo_soli

         LEFT JOIN sucursales                    ON fact_cliente.ID_Sucursal  = sucursales.ID_Sucursal

         LEFT JOIN grupo_solidario_promotor      ON grupo_solidario_promotor.ID_grupo_soli = grupo_solidario.ID_grupo_soli and grupo_solidario_promotor.Ciclo_gpo = grupo_solidario_integrantes.Ciclo_gpo  
        
         LEFT JOIN promotores ON promotores.Num_promo = grupo_solidario_promotor.ID_Promotor


         LEFT JOIN grupo_solidario_control_asistencia USE INDEX (Unicidad) ON grupo_solidario_control_asistencia.ID_grupo_soli = grupo_solidario.ID_grupo_soli and
                                                        grupo_solidario_control_asistencia.FechaAgenda   = cargos.Fecha_vencimiento




         WHERE   cargos.ID_Concepto=-3           and
                fact_cliente.ID_Tipocredito = 2 and

                 ((grupo_solidario_control_asistencia.FechaReagendado IS NULL and cargos.Fecha_vencimiento  BETWEEN '".$ini_date."' and '".$fin_date."' ) or                  
                  (grupo_solidario_control_asistencia.FechaReagendado  BETWEEN '".$ini_date."' and '".$fin_date."')) ";



        if($this->filtrar_sucursales==1)
        {
        
            $sql.=" and fact_cliente.ID_Sucursal IN (".implode(",",$this->listado_sucursales).") ";
        }

        if($this->filtrar_promotores==1) 
        {
        
                $sql .= " and promotores.Num_promo     = '".$this->id_promotor."' ";


        }


        $sql.="GROUP BY  grupo_solidario_integrantes.ID_grupo_soli,  cargos.Fecha_vencimiento
               ORDER BY FechaAgenda,  Hora_Reunion, Nombre ";

    
        

        $rs=$this->db->Execute($sql);
    


            $j=0;
            if($rs->_numOfRows)
               while(! $rs->EOF)
               {

                 $i =$rs->fields['FechaAgenda'];


                 $this->schedule_vencimientos_monthly[$i][$j]['ID_Cargo']       = $rs->fields['ID_Cargo'];
                 $this->schedule_vencimientos_monthly[$i][$j]['Nombre']         = $rs->fields['Nombre'];
                 $this->schedule_vencimientos_monthly[$i][$j]['Sucursal']       = $rs->fields['Sucursal'];
                 $this->schedule_vencimientos_monthly[$i][$j]['Cuota']          = $rs->fields['Cuota'];
                 $this->schedule_vencimientos_monthly[$i][$j]['Promotor']       = $rs->fields['Promotor'];
                 $this->schedule_vencimientos_monthly[$i][$j]['FechaOriginal']  = $rs->fields['FechaOriginal'];
                 $this->schedule_vencimientos_monthly[$i][$j]['FechaReagendado']= $rs->fields['FechaReagendado'];
                 $this->schedule_vencimientos_monthly[$i][$j]['ID_Grupo_Soli']  = $rs->fields['ID_Grupo_Soli'];
                 $this->schedule_vencimientos_monthly[$i][$j]['Hora_Reunion']   = $rs->fields['Hora_Reunion'];

                ++$j;

                 $rs->MoveNext();
                 
                 if($rs->fields['FechaAgenda'] != $i) $j=0;
               }


            //debug(print_r($this->schedule_vencimientos_monthly,1));
            //die();

            return;
   
    
    
    
    



}













































function getSchedule_Vencimientos_Solidarios($fecha)
{



    $return = array();
    $i=0;
    if(count($this->schedule_vencimientos_monthly[$fecha])> 0)
       foreach($this->schedule_vencimientos_monthly[$fecha] AS $key => $row)
       {

         $return[$i][0]=$row['ID_Cargo'];
         $return[$i][1]=$row['Nombre'];
         $return[$i][2]=$row['Sucursal'];
         $return[$i][3]=$row['Cuota'];
         $return[$i][4]=$row['Promotor'];
         $return[$i][5]=$row['FechaOriginal'];
         $return[$i][6]=$row['FechaReagendado'];
         $return[$i][7]=$row['ID_Grupo_Soli'];
         $return[$i][8]=$row['Hora_Reunion'];
         


         $i++;
    
       }
    
    
    
    
    return($return);

    

}
/**/
/*
function getSchedule_Vencimientos_Solidarios($fecha)
{

   $sql="SELECT cargos.ID_Cargo                                         AS ID_Cargo,
                grupo_solidario.Nombre                                  AS Nombre,
                sucursales.Nombre                                       AS Sucursal,
                SUM(cargos.Monto)                                       AS Cuota,
                promotores.Nombre                                       AS Promotor,
                 cargos.Fecha_vencimiento                               AS FechaOriginal,
                 grupo_solidario_control_asistencia.FechaReagendado     AS FechaReagendado,
                 grupo_solidario.ID_grupo_soli                          AS ID_Grupo_Soli
                
         
         FROM   fact_cliente
              


        INNER JOIN  cargos                      ON cargos.Num_compra =  fact_cliente.num_compra  and cargos.Activo='Si'
        INNER JOIN  grupo_solidario_integrantes ON fact_cliente.id_factura = grupo_solidario_integrantes.id_factura 
        INNER JOIN  grupo_solidario             ON grupo_solidario_integrantes.ID_grupo_soli = grupo_solidario.ID_grupo_soli

        LEFT JOIN sucursales   ON      fact_cliente.ID_Sucursal  = sucursales.ID_Sucursal

        LEFT JOIN promo_ventas ON     fact_cliente.num_compra = promo_ventas.Num_compra    
        
        LEFT JOIN promotores   ON     promotores.Num_promo    = promo_ventas.ID_Promo   

        LEFT JOIN grupo_solidario_control_asistencia ON grupo_solidario_control_asistencia.ID_grupo_soli = grupo_solidario.ID_grupo_soli and
                                                        grupo_solidario_control_asistencia.FechaAgenda   = cargos.Fecha_vencimiento




        WHERE   cargos.ID_Concepto=-3           and
                fact_cliente.ID_Tipocredito = 2 and

                 ((grupo_solidario_control_asistencia.FechaReagendado IS NULL and cargos.Fecha_vencimiento  = '".$fecha."') or                  
                  (grupo_solidario_control_asistencia.FechaReagendado  = '".$fecha."')) ";



        if($this->filtrar_sucursales==1)
        {
        
            $sql.=" and fact_cliente.ID_Sucursal IN (".implode(",",$this->listado_sucursales).") ";
        }

        if($this->filtrar_promotores==1) 
        {
        
                $sql .= " and promotores.Num_promo     = '".$this->id_promotor."' ";


        }


        $sql.="GROUP BY  grupo_solidario_integrantes.ID_grupo_soli  ";

    
    
//debug($sql);
//die();
    


    $rs=$this->db->Execute($sql);


    $return = array();
    $i=0;
    if($rs->_numOfRows)
       while(! $rs->EOF)
       {

         $return[$i][0]=$rs->fields['ID_Cargo'];
         $return[$i][1]=$rs->fields['Nombre'];
         $return[$i][2]=$rs->fields['Sucursal'];
         $return[$i][3]=$rs->fields['Cuota'];
         $return[$i][4]=$rs->fields['Promotor'];
         $return[$i][5]=$rs->fields['FechaOriginal'];
         $return[$i][6]=$rs->fields['FechaReagendado'];
         $return[$i][7]=$rs->fields['ID_Grupo_Soli'];



         $i++;
    
         $rs->MoveNext();
       }
    
    
    
    
    return($return);

    

}

*/


function show_squema_by_week()
{

        $this->getSchedule_Vencimientos_Weekly();
        
        
        
       $this->html_output="";


      
        
        $this->html_output.= "<TABLE ALIGN='center' BGCOLOR='black' BORDER=1 CELLSPACING=0 CELLPADDING=0 WIDTH='100%' HEIGHT='100%'  >\n";
        $this->html_output.= "<TR>";
        $this->html_output.= "<TD>";
        $this->html_output.= "<TABLE ALIGN='center' BORDER=0 CELLSPACING=0 CELLPADDING=0 BGCOLOR='".$this->caption_color."' WIDTH='100%' HEIGHT='100%' >";
                        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ Encabezado ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//


                        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
              
        $this->html_output.="<TR BGCOLOR='".$this->caption_color."'   ID='encabezado'>  
                                <TH  STYLE='height:5px; color:".$this->caption_text_color.";'>".$this->fecha_extendida ."</TH>
                             </TR>

                             <TR ALIGN='center'  BGCOLOR='".$this->caption_color."'   ID='encabezado'>

                                <TD>

                                 <TABLE ALIGN='center' BORDER=0 CELLSPACING=1 CELLPADDING=0 BGCOLOR='".$this->table_bg_color."' WIDTH='100%' HEIGHT='100%' >";



                                 $this->html_output.="<TBODY ID='weekday_header'>\n\n";
                                 
                                 $keys = array_keys($this->month_squema);

                                 $this->html_output.="<TR STYLE=' height:15px; color:white;' ALIGN='center' VALIGN='middle' BGCOLOR='lightslategray' ID='small'>\n";
                                 
                                 foreach( $keys AS $weekday)
                                        $this->html_output.="<TH STYLE=' border-width:1px; padding:1px; border-style: solid; border-color:black;   border-collapse: collapse; ' >".$weekday."</TH>\n";

                                 $this->html_output.="</TR>\n\n";
                                                  
                                 $this->html_output.="</TBODY>\n\n";


     
      //STYLE=' height:".(98/$this->weeks_in_month)."%; '
     
     $i = $this->week_num_of_startdate;     
     {
        $this->html_output.="<TR ALIGN='center'  VALIGN='top' BGCOLOR='".$this->week_row_color."'  STYLE=''>\n";
     
     
             foreach($this->month_squema AS $WD=>$renglon)
             {
                   $concepto ="";
                   
                   
                   if(empty($renglon[$i]))
                        $this->html_output.="<TD ID='".$renglon[$i]."'  STYLE='  width:14.2%;' BGCOLOR='".$this->none_color."'>&nbsp;</TD>\n";
                   else
                   {
                        $style_cell_add = " border-width:1px; padding:1px; border-style: solid; border-color:black;   border-collapse: collapse; ";
                        


                        if($renglon[$i] == $this->fecha_hoy ) 
                            $style_cell_add  = " border-width:medium; border-style: inset; border-color: ".$this->today_color.";   ";
                        else
                        if($renglon[$i] == $this->startdate ) 
                             $style_cell_add = " border-width:medium; border-style: inset; border-color: ".$this->setdate_color.";  ";
                           
                        
     
     
                                list($_y,$_m,$_d) = explode("-",$renglon[$i]);
        
                                $time = mktime(0,0,0,$_m,$_d,$_y);
                                $wday = strftime("%w",$time);

                                if($wday == 0)
                                {
                                     $day_color = $this->none_business_day_color;

                                }
                                else
                                {

                                    $sql = "SELECT Fecha_Inhabil AS inhabil, 
                                                   Concepto
                                             FROM `cat_fechas_inhabiles` 
                                             WHERE cat_fechas_inhabiles.Fecha_Inhabil = '".$renglon[$i]."' ";

                                    $rs  = $this->db->Execute($sql);


                                    if($rs->fields['inhabil']>0)
                                    {
                                       $day_color = $this->none_business_day_color;
                                       $concepto  = "<center><em><b>".$rs->fields['Concepto']."</b></em></center>";
                                    }
                                    else
                                       $day_color = $this->business_day_color;

                                }

                        $this->html_output.="<TD ID='".$renglon[$i]."'  STYLE='width:14.2%; background-color:".$day_color."; ".$style_cell_add ." ' >".$this->show_squema_by_week_day_schedule($renglon[$i],$concepto)."</TD>\n";


                   } 

             }


        $this->html_output.="</TR>";

     }
     
    
    $this->html_output.="</TABLE>";


    $this->html_output.="</TABLE>";
    $this->html_output.="</TD>";
    $this->html_output.="</TR>";           
    $this->html_output.="</TABLE>";
        
   return($this->html_output);
     
}







































function show_squema_by_month()
{

         if($this->despliega_inicio_solidarios == true)       
            $this->getSchedule_Inicio_Solidarios_Monthly();

         if($this->despliega_vencimientos_solidarios  == true) 
            $this->getSchedule_Vencimientos_Monthly();


       $this->html_output="";


      
        
        $this->html_output.= "<TABLE ALIGN='center' BGCOLOR='black' BORDER=1 CELLSPACING=0 CELLPADDING=0 WIDTH='100%' HEIGHT='100%'  >\n";
        $this->html_output.= "<TR>";
        $this->html_output.= "<TD>";
        $this->html_output.= "<TABLE ALIGN='center' BORDER=0 CELLSPACING=0 CELLPADDING=0 BGCOLOR='".$this->caption_color."' WIDTH='100%' HEIGHT='100%' >";
                        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ Encabezado ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//


                        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
              
        $this->html_output.="<TR BGCOLOR='".$this->caption_color."'   ID='encabezado'>  
                                <TH  STYLE='height:5px; color:".$this->caption_text_color.";'>".$this->fecha_extendida ."</TH>
                             </TR>

                             <TR ALIGN='center'  BGCOLOR='".$this->caption_color."'   ID='encabezado'>

                                <TD>

                                 <TABLE ALIGN='center' BORDER=0 CELLSPACING=1 CELLPADDING=0 BGCOLOR='".$this->table_bg_color."' WIDTH='100%' HEIGHT='100%' >";



                                 
                                 $keys = array_keys($this->month_squema);

                                 $this->html_output.="<TR STYLE=' height:5px; color:white;' ALIGN='center' VALIGN='middle' BGCOLOR='lightslategray' ID='small'>\n";
                                 
                                 foreach( $keys AS $weekday)
                                        $this->html_output.="<TH STYLE=' border-width:1px; padding:1px; border-style: solid; border-color:black;   border-collapse: collapse; ' >".$weekday."</TH>\n";

                                 $this->html_output.="</TR>\n";
                                                  


     
      //STYLE=' height:".(98/$this->weeks_in_month)."%; '
     
     for($i=0; $i< $this->weeks_in_month ; $i++)
     {
        $this->html_output.="<TR ALIGN='center'  VALIGN='top' BGCOLOR='".$this->week_row_color."'  STYLE=' height:".(100/$this->weeks_in_month)."%; '>\n";
     
     
             foreach($this->month_squema AS $WD=>$renglon)
             {
                   $concepto ="";
                   
                   
                   if(empty($renglon[$i]))
                        $this->html_output.="<TD ID='".$renglon[$i]."'  STYLE='  width:14.2%;  ' BGCOLOR='".$this->none_color."'>&nbsp;</TD>\n";
                   else
                   {
                        $style_cell_add = " border-width:1px; padding:1px; border-style: solid; border-color:black;   border-collapse: collapse; ";
                        
                        if($renglon[$i] == $this->fecha_hoy ) 
                            $style_cell_add  = " border-width:medium; border-style: inset; border-color: ".$this->today_color.";   ";
                        else
                        if($renglon[$i] == $this->startdate ) 
                             $style_cell_add = " border-width:medium; border-style: inset; border-color: ".$this->setdate_color.";  ";
                           
                        
     
     
                                list($_y,$_m,$_d) = explode("-",$renglon[$i]);
        
                                $time = mktime(0,0,0,$_m,$_d,$_y);
                                $wday = strftime("%w",$time);

                                if($wday == 0)
                                {
                                     $day_color = $this->none_business_day_color;

                                }
                                else
                                {

                                    $sql = "SELECT Fecha_Inhabil AS inhabil, 
                                                   Concepto
                                             FROM `cat_fechas_inhabiles` 
                                             WHERE cat_fechas_inhabiles.Fecha_Inhabil = '".$renglon[$i]."' ";

                                    $rs  = $this->db->Execute($sql);


                                    if($rs->fields['inhabil']>0)
                                    {
                                       $day_color = $this->none_business_day_color;
                                       $concepto  = "<center><em><b>".$rs->fields['Concepto']."</b></em></center>";
                                    }
                                    else
                                       $day_color = $this->business_day_color;

                                }

                        $this->html_output.="<TD ID='".$renglon[$i]."'  STYLE='width:14.2%; height:50px; background-color:".$day_color."; ".$style_cell_add ." ' >".$this->show_squema_by_month_day_schedule($renglon[$i],$concepto)."</TD>\n";


                        //$this->html_output.="<TD ID='".$renglon[$i]."'  STYLE='width:14.27%; height:50px; background-color:".$day_color."; ".$style_cell_add ." ' >".$renglon[$i]."</TD>\n";
                   } 

             }


        $this->html_output.="</TR>";

     }
     
    
    $this->html_output.="</TABLE>";


    $this->html_output.="</TABLE>";
    $this->html_output.="</TD>";
    $this->html_output.="</TR>";           
    $this->html_output.="</TABLE>";
        
   return($this->html_output);
     
}












function getDayPopSupport($response_script)
{

$this->day_pop_support = 1;

echo <<<EOD

<script type="text/javascript">
function show_day_window(fecha)
{
           var url = "about:blank";
           
           
           var frm = document.forms['agenda_empresarial'];
           
           frm.target = 'fechadeldia';
           frm.action = '$response_script';
           frm.elements['fecha_procesar'].value = fecha;
           
          
           var wnd = window.open(url,'fechadeldia','width=1100,height=500,menubar=1,toolbar=0,resizable=1,scrollbars=1');
           
           frm.submit();
           frm.target    = '';
           frm.action    = '';
           
           

           return;
}
</script>

<FORM METHOD='POST' NAME='agenda_empresarial'>

EOD;
echo "<INPUT TYPE='HIDDEN' NAME='fecha_procesar' >\n";
echo "<INPUT TYPE='HIDDEN' NAME='filtrar_promotores' VALUE='".$this->filtrar_promotores."'>\n";
echo "<INPUT TYPE='HIDDEN' NAME='id_promotor'        VALUE='".$this->id_promotor."'>\n";

echo "<INPUT TYPE='HIDDEN' NAME='despliega_inicio_solidarios'           VALUE='".$this->despliega_inicio_solidarios."'>\n";
echo "<INPUT TYPE='HIDDEN' NAME='despliega_vencimientos_solidarios'     VALUE='".$this->despliega_vencimientos_solidarios."'>\n";





echo "<INPUT TYPE='HIDDEN' NAME='filtrar_sucursales' VALUE='".$this->filtrar_sucursales."'>\n";

if(count($this->listado_sucursales))
        foreach($this->listado_sucursales AS $value)
        {
                echo "<INPUT TYPE='HIDDEN' NAME='listado_sucursales[]' VALUE='".$value."'>\n";
        }

echo "</FORM>\n";

}





function getHintSupport()
{

        $this->hint_support=1;





echo <<<EOD
<style type="text/css">

#hintbox{ /*CSS for pop up hint box */
position:absolute;
top: 0;
background-color: lightyellow;
width: 150px; /*Default width of hint.*/ 
padding: 3px;
border:1px solid black;
font:normal 11px Verdana;
line-height:18px;
z-index:100;
border-right: 3px solid black;
border-bottom: 3px solid black;
visibility: hidden;
}

.hintanchor{ /*CSS for link that shows hint onmouseover*/
font-weight: bold;
color: navy;
margin: 3px 8px;
}

</style>

<script type="text/javascript">
                
var horizontal_offset="9px";
var vertical_offset="0";
var ie=document.all;
var ns6=document.getElementById&&!document.all;
var mouse_x = 0;
var mouse_y = 0;

function ListaAsistencia(idgpo,fechaCargo)
{

      var url="lista_de_asitencia.php?id_grupo="+idgpo+"&fecha="+fechaCargo;

      window.open(url,"fichas","width=800,height=600,toolbar=yes,status=yes,,scrollbars=yes");
}


function getposOffset(what, offsettype)
{
        var totaloffset=(offsettype=="left")? what.offsetLeft : what.offsetTop;
        var parentEl=what.offsetParent;
        while (parentEl!=null)
        {
                totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
                parentEl=parentEl.offsetParent;
        }

return totaloffset;
}

function iecompattest()
{
        return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function clearbrowseredge(obj, whichedge)
{
        var edgeoffset=(whichedge=="rightedge")? parseInt(horizontal_offset)*-1 : parseInt(vertical_offset)*-1
        
        if (whichedge=="rightedge")
        {
                var windowedge=ie && !window.opera? iecompattest().scrollLeft+iecompattest().clientWidth-30 : window.pageXOffset+window.innerWidth-40

                dropmenuobj.contentmeasure=dropmenuobj.offsetWidth;

                if (windowedge-dropmenuobj.x < dropmenuobj.contentmeasure)
                        edgeoffset=dropmenuobj.contentmeasure+obj.offsetWidth+parseInt(horizontal_offset);
        }
        else
        {
                var windowedge=ie && !window.opera? iecompattest().scrollTop+iecompattest().clientHeight-15 : window.pageYOffset+window.innerHeight-18

                dropmenuobj.contentmeasure=dropmenuobj.offsetHeight;

                if (windowedge-dropmenuobj.y < dropmenuobj.contentmeasure)
                        edgeoffset=dropmenuobj.contentmeasure-obj.offsetHeight;
        }
        return edgeoffset
}

function showhint(menucontents, obj, e, tipwidth)
{
        if ((ie||ns6) && document.getElementById("hintbox"))
        {
                dropmenuobj=document.getElementById("hintbox")
                dropmenuobj.innerHTML=menucontents; //+"</br>X :"+mouse_x+" Y : "+mouse_y;
                
                dropmenuobj.style.left=dropmenuobj.style.top=-500
                if (tipwidth!="")
                {
                        dropmenuobj.widthobj=dropmenuobj.style
                        dropmenuobj.widthobj.width=tipwidth
                }
        //dropmenuobj.x=getposOffset(obj, "left")
        //dropmenuobj.y=getposOffset(obj, "top")
        
        dropmenuobj.x = mouse_x -100;
        dropmenuobj.y = mouse_y;
        
        
        dropmenuobj.style.left=dropmenuobj.x-clearbrowseredge(obj, "rightedge")+obj.offsetWidth+"px"
        dropmenuobj.style.top=dropmenuobj.y-clearbrowseredge(obj, "bottomedge")+"px"
        dropmenuobj.style.visibility="visible"
        obj.onmouseout=hidetip
}
}

function hidetip(e)
{
        dropmenuobj.style.visibility="hidden"
        dropmenuobj.style.left="-500px"
}

function createhintbox()
{
        var divblock=document.createElement("div")
        divblock.setAttribute("id", "hintbox")
        document.body.appendChild(divblock);
        init();
}


function init() 
{

  if (window.Event)
  {
    document.captureEvents(Event.MOUSEMOVE);
  }
  document.onmousemove = getXY;
 
}

function getXY(e) 
{

  mouse_x  = (window.Event)?( e.pageX ):( event.clientX);
  mouse_y  = (window.Event)?( e.pageY ):( event.clientY);

}





var mouse_x;
var mouse_y;

if (window.addEventListener)
{

        window.addEventListener("load", createhintbox, false);
}
else if (window.attachEvent)
{
        window.attachEvent("onload", createhintbox)
}
else if (document.getElementById)




window.onload=createhintbox;

</script>
EOD;


}





};







?>