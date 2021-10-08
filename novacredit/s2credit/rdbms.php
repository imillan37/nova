<?php
//echo $DOCUMENT_ROOT.'/db-const.php';
error_reporting(E_ALL);
ini_set('display_errors','1');
// /**
// *  Nombre    : rdbms.php
// * Contenido : Contiene la definición de las constantes para la conexion con MySQL y algunas
// * funciones sueltas para manejo de fechas y depuración.
// * */ 
//require_once($DOCUMENT_ROOT.'/db-const.php');
if(!file_exists($DOCUMENT_ROOT.$sys_path.'rdbms.php'))
die("No se encontro la libreria : ".$DOCUMENT_ROOT.$sys_path_src.'db-const.php'.". Podria deberse a un problema de permisos. <br>");

require_once($DOCUMENT_ROOT.$sys_path_src.'db-const.php');
//echo $DOCUMENT_ROOT.$sys_path_src.'db-const.php';
define ("ERROR1",'<BR> <EM> <B> <CENTER>EL SERVIDOR DE BASES DE DATOS NO RESPONDE. COMUNIQUESE CON SU ADMINISTRADOR.</CENTER> </B> </EM>');

global $sys_path;
/* --
    /novacredit/s2credit/
*/
//echo 'sys path = '.$sys_path;
$usuarios = $sys_path."images/manypeople.gif";
$usuario  = $sys_path."images/people.gif";              //      'ASIGNAR USUARIOS'
$new_usr  = $sys_path."images/new_people.png";          //      'NUEVO USUARIO'
$usr_key  = $sys_path."images/keyone.gif";              //      'DESIGNAR USUARIOS'
$usrs_key = $sys_path."images/keygrp.gif";              //      'ASIGNAR USUARIOS'
$view_usr = $sys_path."images/view_people.png";         //      'EXAMINAR USUARIO'
$permisos = $sys_path."images/authority.gif";           //      'ASIGNAR PERMISOS'
$editar   = $sys_path."images/edit.gif";                //      'EDITAR'
$eliminar = $sys_path."images/trash.gif";               //      'ELIMINAR'
$error_img= $sys_path."images/error.gif";
$imprimir = $sys_path."images/btn_imprimir.gif";
$modulos  = $sys_path."images/blocks.gif";
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
//Fechas
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//

$hoy = time();

$dia[0] = "Domingo";
$dia[1] = "Lunes";
$dia[2] = "Martes";
$dia[3] = "Mi&eacute;rcoles";
$dia[4] = "Jueves";
$dia[5] = "Viernes";
$dia[6] = "S&aacute;bado";

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
$Y=strftime("%Y",$hoy);

$fecha_hoy=strftime("%Y-%m-%d",$hoy);
$fecha_extendida = "$dia[$W] $D de $mes[$M] de $Y";


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
// Movimientos al inventario Fijos
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//


$styles = "
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
                                FONT-SIZE: 8;
                                FONT-STYLE: normal;
                                FONT-FAMILY:  Verdana,Geneva,Tahoma, Arial, Helvetica, sans-serif;
                        }
                        #verysmall
                        {
                                FONT-SIZE: 7;
                                FONT-STYLE: normal;
                                FONT-FAMILY:  Verdana,Geneva,Tahoma, Arial, Helvetica, sans-serif;
                        }
                        #encabezado
                        {
                                FONT-SIZE: 10pt;
                                FONT-STYLE: normal;
                                FONT-FAMILY:  Geneva, Verdana,Tahoma, Arial, Helvetica, sans-serif;
                        }

</STYLE> ";

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
//      Retorna valores de las constantes del sistema.
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//

if(!function_exists('system_const')){
    function system_const($Const,$db)
	{
	        $sql = "SELECT Valor FROM constantes WHERE Nombre ='".$Const."' ";
	
	        $rs=$db->execute($sql);
	        return($rs->fields[0]);
            
	}
}
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
/*
  -- $shared_scripts --

    /novacredit/s2credit/compartidos/
*/
if(!function_exists('buscanombre')) {

	function buscanombre($forma,$campo)
	{
	        global $shared_scripts;
	
	
	        $event  =  "<INPUT TYPE='BUTTON' ID='small' STYLE='font-weight:bold; height:21px;' OnClick='";
	        $event .= "window.open(\"".$shared_scripts."buscanombre.php?campo=".$campo."&forma=".$forma."\",\"buscanombre\",\"width=600,height=400,menubar=0,toolbar=0,resizable=1,scrollbars=0\"); ";
	        $event .= "' VALUE='?' />";
	
	        return($event);
	}
	
}
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
if(!function_exists('buscarazonsocial')) {

	function buscarazonsocial($forma,$campo)
	{
	        global $shared_scripts;
	
	
	        $event  =  "<INPUT TYPE='BUTTON' ID='small' STYLE='font-weight:bold; height:21px;' OnClick='";
	        $event .= "window.open(\"".$shared_scripts."buscarazonsocial.php?campo=".$campo."&forma=".$forma."\",\"buscanombre\",\"width=600,height=400,menubar=0,toolbar=0,resizable=1,scrollbars=0\"); ";
	        $event .= "' VALUE='?' />";
	
	        return($event);
	}
	
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
if(!function_exists('buscasoli')) {

	function buscasoli($forma,$campo)
	{
	        global $shared_scripts;
	
	
	        $event  =  "<INPUT TYPE='BUTTON' ID='small' STYLE='font-weight:bold; height:21px;' OnClick='";
	        $event .= "window.open(\"".$shared_scripts."busca_soli.php?campo=".$campo."&forma=".$forma."\",\"buscanombre\",\"width=600,height=400,menubar=0,toolbar=0,resizable=1,scrollbars=0\"); ";
	        $event .= "' VALUE='?' />";
	
	        return($event);
	}
	
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
if(!function_exists('buscaempresa')) {

	function buscaempresa($forma,$campo,$campo_tel,$campo_direc,$extension)
	{
	        global $shared_scripts;
	
	
	        $event =  "<INPUT TYPE='BUTTON' ID='small' STYLE='font-weight:bold; height:21px;' OnClick='";
	        $event .= "window.open(\"".$shared_scripts."buscaempresa.php?campo=".$campo."&campo_tel=".$campo_tel."&campo_ext=".$extension."&campo_direc=".$campo_direc."&forma=".$forma."\",\"buscaempresa\",\"width=600,height=400,menubar=0,toolbar=0,resizable=1,scrollbars=0\"); ";
	        $event .= "' VALUE='?' />";
	
	        return($event);
	}
	
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
if(!function_exists('buscagrupo')) {

	function buscagrupo($forma,$campo)
	{
	        global $shared_scripts;
	        
	        
	        $event =  "<INPUT TYPE='BUTTON' ID='small' STYLE='font-weight:bold; height:21px;' OnClick='";
	        $event .= "window.open(\"".$shared_scripts."buscagrupo.php?campo=".$campo."&forma=".$forma."\",\"buscagrupo\",\"width=600,height=400,menubar=0,toolbar=0,resizable=1,scrollbars=0\"); ";                            
	        $event .= "' VALUE='?' />";
	        
	        return($event);
	}
	
}


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
if(!function_exists('debug')) {

	function debug($sql)
        {
                echo "<TABLE ALIGN='center' BGCOLOR='yellow'><TR><TD ID='S2'><DIR>".nl2br($sql)."</DIR></TD></TR></TABLE>";
                return;
    }
	
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
if(!function_exists('backindex')) {

	function backindex()
    {
        echo " \n <FORM> <CENTER><input type=\"button\" value=\"Regresar\" OnClick=\"parent.location.replace('index.html');\"></CENTER> </FORM> \n";
        return;
        }
	
}


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
if(!function_exists('back')) {

	
    function back()
        {
            echo " \n <FORM> <CENTER><input type=\"button\" ID='nombre' value=\"Regresar\" OnClick=\"window.history.back();\"></CENTER> </FORM> \n";
            return;
        }
        
}


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
if(!function_exists('backurl')) {

	
    function backurl($url)
        {
            echo " \n <FORM> <CENTER><input type=\"button\" value=\"Regresar\" OnClick=\"parent.location.replace('$url');\"></CENTER> </FORM> \n";
            return;
            }
        
}



//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
if(!function_exists('closepage')) {

	
    function closepage()
        {
            echo " \n <FORM> <CENTER><input ID='S2' type=\"button\" value=\"Regresar\" OnClick=\"window.close();\"></CENTER> </FORM> \n";
            return;
    }
        
}


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
if(!function_exists('error_msg')) {

	function error_msg( $msg )
	{
	        global $sys_path;
	
	        echo "<BR><TABLE ALIGN='center' BORDER=5 CELLSPACING=2 CELLPADDING=2  BGCOLOR='#C0C0C0'><TR><TD>\n\n";
	           echo "<TABLE ALIGN='center' BORDER=0 CELLSPACING=2 CELLPADDING=2  BGCOLOR='White'>\n";
	           echo "<TR ALIGN='left' VALIGN='middle' ID='S2'>\n";
	           echo "       <TD ALIGN='center'>\n";
	           echo "               <IMG SRC='".$sys_path."images/error.jpg' width='50%'  BORDER=0>\n";
	           echo "       </TD>\n";
	           echo "       <TD>\n";
	           echo "               <FONT  COLOR='#FF0000' FACE='Verdana,Tahoma,Helvetica,sans-serif'>  $msg </FONT>\n";
	           echo "       </TD>\n";
	           echo "</TR>\n";
	           echo "</TABLE>\n\n";
	           echo "</TD></TR>\n</TABLE><BR>\n";
	
	        return;
	
	}
	
}


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
if(!function_exists('info_msg')) {

	function info_msg( $msg )
	{
	        global $sys_path;
	
	        echo "<BR><TABLE ALIGN='center' BORDER=5 CELLSPACING=2 CELLPADDING=2  BGCOLOR='#C0C0C0'><TR><TD>\n\n";
	           echo "<TABLE ALIGN='center' BORDER=0 CELLSPACING=2 CELLPADDING=2  BGCOLOR='White'>\n";
	           echo "<TR ALIGN='left' VALIGN='middle' ID='S2'>\n";
	           echo "       <TD ALIGN='center'>\n";
	           echo "               <IMG SRC='".$sys_path."images/info.gif'   BORDER=0>\n";
	           echo "       </TD>\n";
	           echo "       <TD>\n";
	           echo "               <FONT  COLOR='Black' FACE='Verdana,Tahoma,Helvetica,sans-serif'>  $msg </FONT>\n";
	           echo "       </TD>\n";
	           echo "</TR>\n";
	           echo "</TABLE>\n\n";
	           echo "</TD></TR>\n</TABLE><BR>\n";
	
	        return;
	
	}
	
}


if(!function_exists('error_alert')) {

	function error_alert( $msg )
	{
	
	        alert_msg( $msg );
	}
	
}


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
if(!function_exists('alert_msg')) {

	function alert_msg( $msg )
	{
	        global $sys_path;
	
	        echo "\n<SCRIPT>\n\n";
	           echo "alert('". $msg."')\n";
	        echo "\n</SCRIPT>\n\n";
	
	        return;
	
	}
	
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
// Redondeo
if(!function_exists('plus')) {

	function plus($n)
	{
	
	
	   $n = str_replace(",","",$n);
	
	   $na =  floor($n) *1000;
	   $nb =  floor($n * 1000);
	
	   $nr = ($nb-$na);
	   $nr++;
	
	   return(floor($n)+($nr/1000));
	
	}
	
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
// Si no está presente el Módulo GMP
if(!function_exists('gmp_mod')) 
{
  function gmp_mod($n, $d) 
  {
    return ($n % $d);
  }
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
// 
if(!function_exists('trunc')) 
{

  function trunc($n, $d=0) 
  {
        $shift = pow(10, $d);
        return ((floor($n * $shift)) / $shift);
  
  }

}


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
// Seguridad :
// Monday, February 14, 2005 (Enrique Godoy)
// * Se agrega la ruta del moulo para evitar problemas cuando hay mas de un modulo con el mismo
// * nombre.
//
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//


function seguridad($arch, $db, $grp)
{
 global $sys_path;

    if(strpos($arch, "/")===false)
    {
              $modulo = $arch;
                  $sql = "      SELECT  b.Permiso
                                        FROM    ".NUCLEO.".menu_dtl a,
                                                ".NUCLEO.".permisos b
                                        WHERE
                                                        a.ID_Sub = b.ID_Sub  and
                                                        a.ID_Sub2 =b.ID_Sub2 and
                                                        a.ID_Sub3 =b.ID_Sub3 and
                                                        b.ID_Menu = a.ID_Menu and /* bgm */
                                                        b.ID_grupo = $grp and
                                                        a.Modulo = '".$modulo."' ";

    }
    else
    {
                 $modulo = substr (strrchr($arch, "/"), 1);
                 $ruta = substr($arch,0, (strlen($arch)-strlen($modulo))  );
                 $ruta = str_replace($sys_path,'../',$ruta);

                 $sql = "               SELECT  b.Permiso
                                        FROM      ".NUCLEO.".menu_dtl a,
                                                          ".NUCLEO.".permisos b,
                                                          ".NUCLEO.".rutas    c
                                        WHERE
                                                        a.ID_Sub = b.ID_Sub  and
                                                        a.ID_Sub2 =b.ID_Sub2 and
                                                        a.ID_Sub3 =b.ID_Sub3 and
                                                        b.ID_Menu = a.ID_Menu and /* bgm */
                                                        b.ID_grupo = $grp and
                                                        a.Modulo = '".$modulo."'  and

                                                        a.ID_Ruta = c.id_ruta and
                                                        c.ruta = '".$ruta."' ";


    }


// debug($sql);
//if( $_SESSION["ID_USR"] == 1 ) debug($sql);
 $RS = $db->Execute($sql);
 $permiso = $RS->fields[0];


 return ($permiso);
}

//----------------------------------------------------------------------------------------------------- //
// Ingresa las IP de gente que no se pueda logear en 15 intentos consecutivos el mismo dia en la lista negra
//----------------------------------------------------------------------------------------------------- //
// Se guarda en la sesión el ultimo grupo seleccionado, para que al caducar la sesión y mostrar
// el cuadro de dialogo para loguearse tome este ultimo y no pregunte el grupo
function log_access($ip, $user_agent, $method, $usr,  $host_name, $db)
{
    global $ID_GRP;
    global $ID_SUC;
    
//      $ipclass = substr($ip, 0, 6);
//      if($ipclass != '192.168')
//      {
                        $tiempo = time();
                        $fecha =   strftime("%Y-%m-%d",$tiempo);
                        $hora  =    strftime("%H:%M:%S",$tiempo);
                        $hostname = ($ip);
                        $sql= "INSERT INTO access_log \n
                                 (id_access, ip, hora, fecha, user_agent, method,  user, host_name,ID_Grupo, ID_Sucursal)
                               VALUES \n
                                 (NULL, '$ip', '$hora', '$fecha', '$user_agent', '$method', '$usr', '$host_name','".$ID_GRP."', '".$ID_SUC."') ";
                        $db->Execute($sql);


//      }
        return ;
}

//----------------------------------------------------------------------------------------------------- //
//
//----------------------------------------------------------------------------------------------------- //

function log_error($ip, $user_agent, $method, $usr, $passwd, $db, $dbug=0)
{
        global $sys_upload_path;

        $tiempo = time();
        $fecha =   strftime("%Y-%m-%d",$tiempo);
        

        $hora  =    strftime("%H:%M:%S",$tiempo);
        $hostname = ($ip);
        $sql= "INSERT INTO error_log
               (ID_Error, ip, hora, fecha, user_agent, method,  user, passwd, host_name)
               VALUES
               (NULL, '$ip', '$hora', '$fecha', '$user_agent', '$method', '$usr', '$passwd', '$host_name') ";

        $db->Execute($sql);



         $sql=" SELECT COUNT(*) FROM error_log WHERE fecha='".$fecha."' and ip='".$ip."' ";
         $rs=$db->Execute($sql);

         $err_count = $rs->fields[0];


         if(($err_count) and ($err_count%60 == 0) )
         {

                 $filename = $sys_upload_path."abuse".strftime("%Y%m%d",$tiempo).".txt";

                $fp = fopen($filename, 'a');

                 if($fp)
                 {
                        $ip=$ip."\n";
                        fwrite($fp, $ip);
                        fclose($fp);
                 }

         }

         return ;

}
//------------------------------------------------------------------------------------------------- //
//      Checha si el usuario esta lista negra
//------------------------------------------------------------------------------------------------- //

function chk_abuse_log($ip)
{
        /*/opt/lampp/htdocs/s2credit.net/novacredit/s2credit/tmp/*/  
      return 0;
     
     
     
         global $sys_upload_path;
    
         $response = 0;
         $filename = $sys_upload_path."abuse".strftime("%Y%m%d",time()).".txt";

    if(file_exists($filename))
     {


                         $fp = fopen($filename, 'r');
                         if($fp)
                                  {
                                         while (!feof($fp))
                                          {
                                            $buffer = fgets($fp, 4096);
                                                 if( chop($buffer) ==  $ip)
                                                 {
                                                                 $response= 1;
                                                                 break;
                                                 }

                                         }
                                         fclose($fp);
                                 }

      }





         //echo " no hay ".$filename;

         return  $response;
}
//------------------------------------------------------------------------------------------------- //

if(!function_exists('str_split')) 
{
  function str_split($string, $split_length = 1) {
    $array = explode("\r\n", chunk_split($string, $split_length));
    array_pop($array);
    return $array;
  }
}


//---------------------------- Funciones Libres para manejo de Fechas -------------------------------//

//------------------------------------------------------------------------------------------------- //
// Retorna la cuenta en dias de una fecha Gregoriana

if (!function_exists('GregorianToJD'))
{

function GregorianToJD ($month,$day,$year) // Retorna la cuenta en dias de una fecha Gregoriana
{
        if ($month > 2)
        {
                $month = $month - 3;
        }
        else
        {
                $month = $month + 9;
                $year = $year - 1;
        }
        $c = floor($year / 100);
        $ya = $year - (100 * $c);
        $j = floor((146097 * $c) / 4);
        $j += floor((1461 * $ya)/4);
        $j += floor(((153 * $month) + 2) / 5);
        $j += $day + 1721119;
        return $j;
}
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//

if (!is_callable('getLastDayOfMonth'))
{
        function getLastDayOfMonth($month, $year)
        {
                return idate('d', mktime(0, 0, 0, ($month + 1), 0, $year));
        }
}

if (!is_callable('idate')) {
    function idate($char, $ts = false) {
        if ($ts === false) {
            $ts = time();
        } else if (!is_numeric($ts)) {
            return false;
        }
        //$char = $char{0};
        $char = $char[0];
        if ($char == 'B') {
            // Swatch time ignores the $ts argument.
            return ((int) ((gmdate('U') + 3600) * (1000 / 86400))) % 1000;
        } else {
            return (int) date($char, $ts); // Drop leading zeroes by casting into an integer.
        }
    }
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

$time_start = microtime_float();
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//


function forma_fecha_ext($fecha) // Entra en formato MySQL  ANIO-MES-DIA.
{

        global $mes, $dia;

        $M= (int) fmes($fecha);
        $D= (int) fdia($fecha);
        $Y= (int) fanio($fecha);
        $this_time=mktime( 0,0,0,$M,$D,$Y );

        $W= strftime("%w",$this_time);   // Dia de la semana
        $fecha_ext = "$dia[$W] $D de $mes[$M] de $Y";

        return($fecha_ext);
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//

function ffdias($fecha1,$fecha2)   //Retorna la diferencia en dias entre dos fechas en formato MySQL i.e. A?-MES-DIA.
{

 $jd1 = GregorianToJD(fmes($fecha1),fdia($fecha1),fanio($fecha1));
 $jd2 = GregorianToJD(fmes($fecha2),fdia($fecha2),fanio($fecha2));

 return( abs( $jd2-$jd1 ) );

}
//--------------------------------------------------------------------------------------------------
function fdifdias($fecha1,$fecha2)   //Retorna la diferencia en dias entre dos fechas en formato MySQL i.e. A?-MES-DIA.
{

 $jd1 = GregorianToJD(fmes($fecha1),fdia($fecha1),fanio($fecha1));
 $jd2 = GregorianToJD(fmes($fecha2),fdia($fecha2),fanio($fecha2));

 return(  $jd2-$jd1  );

}
//--------------------------------------------------------------------------------------------------

function fposdias($fecha2,$fecha1)   //Retorna la diferencia en dias entre dos fechas en formato MySQL i.e. A?-MES-DIA.
{

 $jd1 = GregorianToJD(fmes($fecha1),fdia($fecha1),fanio($fecha1));
 $jd2 = GregorianToJD(fmes($fecha2),fdia($fecha2),fanio($fecha2));

 $dias=((  $jd2-$jd1  )>0)?( $jd2-$jd1 ):(0);

 return(  $dias  );

}



//------------------------------------------------------------------------------------------------- //

function usfecha($fecha)                // Retorna una fecha en formato MES/DIA/A? de una fecha en formato MySQL i.e. A?-MES-DIA
{

  $new_fecha = fmes($fecha)."/".fdia($fecha)."/".fanio($fecha);
 return($new_fecha);

}
//------------------------------------------------------------------------------------------------- //

function nusfecha($fecha)               // Retorna una fecha en formato MySQL de una fecha en formato (us) MES/DIA/A? i.e. A?-MES-DIA
{

  $new_fecha = substr($fecha,6,4)."-".substr($fecha,0,2)."-".substr($fecha,3,2);
  return($new_fecha);

}
//------------------------------------------------------------------------------------------------- //

//------------------------------------------------------------------------------------------------- //

function ffecha($fecha)         // Retorna una fecha en formato DIA/MES/A? de de una fecha en formato MySQL i.e. A?-MES-DIA
{
        if(empty($fecha))
                return ("");

         if (strlen($fecha) >= 10)
         {

                $sufix = substr($fecha,10);
                $sufix = substr($sufix ,0,6);
                $fecha = substr($fecha,0,10);

         }


 $new_fecha = fdia($fecha)."/".fmes($fecha)."/".fanio($fecha). $sufix ;



 return($new_fecha);

}

//------------------------------------------------------------------------------------------------- //

function ffechayhora($fecha)            // Retorna una fecha en formato DIA/MES/A? de de una fecha en formato MySQL i.e. A?-MES-DIA
{

 $hora="";

         if (strlen($fecha) >= 10)
         {
                $hora  = substr($fecha,10);
                $fecha = substr($fecha,0,10);

         }


 $new_fecha = fdia($fecha)."/".fmes($fecha)."/".fanio($fecha).$hora;



 return($new_fecha);

}





//------------------------------------------------------------------------------------------------- //
function gfecha($fecha)         // Retorna una fecha en formato MySQL(A?-MES-DIA)  en formato  de DIA/MES/A?
{

        if(empty($fecha))
                return ("");


        $sufix = substr($fecha,10);
        $fecha = substr($fecha,0,10);


        list($dia,$mes,$anio)=split("/", $fecha);


         $new_fecha = $anio.'-'.$mes.'-'.$dia.$sufix;
 return($new_fecha);


}
//------------------------------------------------------------------------------------------------- //


function gxfecha($fecha,$cadena)                // Retorna una fecha en formato MySQL(Año [cadena] MES [cadena] DIA)  en formato  de DIA/MES/A?
{

$xd=0;
$dia=substr($fecha,0,2);
        if(strpos ($dia,"/"))
        {
                $dia=substr($dia,0,1);
                $xd=1;

        }
$mes=substr($fecha,(3-$xd),2);
        if($xm=strpos ($mes,"/"))
        {
                $mes=substr($mes,$xm-1,1);

        }


 $new_fecha = substr($fecha,(strlen($fecha)-4),4).'-'.$mes.'-'.$dia;
 return($new_fecha);
 //return($mes);

}


//------------------------------------------------------------------------------------------------- //

function fanio($fecha)      // Retrorna el anio de una fecha en formato MySQL i.e. A?-MES-DIA
{

         if ($fecha == "Info. no disponible")
         {
                return ("");
         }

         $anio=substr($fecha,0,4);
         return($anio);

}
//------------------------------------------------------------------------------------------------- //
function fmes($fecha)           // Retrorna el mes de una fecha en formato MySQL i.e. A?-MES-DIA
{

         if ($fecha == "Info. no disponible")
         {
                return ("");
         }

         $mes=substr($fecha,strpos($fecha,"-")+1,2);
         $mes=str_replace("-","",$mes);
         //$mes=substr($fecha,5,2);



         return($mes);

}
//------------------------------------------------------------------------------------------------- //
function fdia($fecha)                   // Retrorna el dia de una fecha en formato MySQL i.e. A?-MES-DIA
{

         if ($fecha == "Info. no disponible")
         {
                return ("");
         }

         $dia=substr($fecha,strlen($fecha)-2,2);        // Ultimos 2 caracteres
         $dia=str_replace("-","",$dia);


         return($dia);

}

//------------------------------------------------------------------------------------------------- //
function fcmp($f1,$f2)  //Compara 2 fechas en formato AAAA-MM-DD Retorna 0 : si son iguales;
{                                               //                                                                                               1 : si la primera es mayor
                                                //                                                                                               -1: si la segunda es mayor
        $f_1 = fanio($f1).fmes($f1).fdia($f1);
        $f_2 = fanio($f2).fmes($f2).fdia($f2);

        if($f_1 == $f_2){ return(0); }
        if($f_1 > $f_2) { return(1); }
        if($f_1 < $f_2) { return(-1);}

}

//------------------------------------------------------------------------------------------------- //
//      DEVUELVE LA FECHA EN FORMATO "Lunes 21, Enero 2005", a partir de un formato MYSQL
//------------------------------------------------------------------------------------------------- //

function fechaNatural($fecha_natural)
{
        $fecha_natural_dia = substr( $fecha_natural, 8, 2 );
        $fecha_natural_mes = substr( $fecha_natural, 5, 2 );
        $fecha_natural_ano = substr( $fecha_natural, 0, 4 );
        $fecha_natural_dia_semana = date( "w", mktime( 0, 0, 0, $fecha_natural_mes, $fecha_natural_dia, $fecha_natural_ano ) );
        $diasSemana    = array( "Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado" );
        $mesesAno      = array( "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" );
        $fechaNatural = $diasSemana[$fecha_natural_dia_semana]. " ".$fecha_natural_dia.", ".$mesesAno[$fecha_natural_mes-1]." ".$fecha_natural_ano;
        return $fechaNatural;
}

/**************************************************************************************************/
// Genera Bitácora de errores
/**************************************************************************************************/

function bitacora_errores($error_dtl)
{
        global $hoy, $error_log_path, $_SERVER, $PHP_SELF, $NOM_USR, $ID_GRP ;
        $logname= strftime("%Y%m%d",$hoy)."err.log";


        $fp = fopen($error_log_path.$logname, "a+");

        if($fp)
        {

         $time           =      "Hora : ".strftime("%H:%M:%S",time)."\n";
         $usr            =      "Usuario : ".$NOM_USR."   ID GRUPO : ".$ID_GRP ."\n ";
         $ip             =      "IP : ". $_SERVER['REMOTE_ADDR']."\n";
         $navegador      =      "Navegador :  ".$_SERVER['HTTP_USER_AGENT']."\n";
         $script         =      "Script  : ".$PHP_SELF."\n";
         $error_dtl      =      "Detalle : ".$error_dtl."\n";
         $separador  = "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\n";

         fwrite($fp,"\n");
         fwrite($fp,$time);
         fwrite($fp,$usr);
         fwrite($fp,$ip);
         fwrite($fp,$navegador);
         fwrite($fp,$script);
         fwrite($fp,$error_dtl);
         fwrite($fp,$separador);

         fclose($fp);
         $bit= true;

        }
        else $bit= false;



return($bit);
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
//      Permite ver las variables y sus valores que entran por metodo GET, POST y las de SESION
//      Autor : Enrique Godoy
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//

function verflujo()
{
        global $_POST, $_GET, $_SESSION;

        echo "<TABLE ALIGN='center' WIDTH='95%' BORDER=0 CELLSPACING=1 CELLPADDING=2 ID='small' BGCOLOR='black'>\n";
        echo "<TR ALIGN='center' VALIGN='middle' BGCOLOR='lightsteelblue'>
                                <TH >   Variable </TH>
                                <TH >   Valor    </TH>
                        </TR>";
                //---------------------------------Metodo POST ----------------------------------------//
                if(count($_POST))
                {
                        echo "
                        <TR ALIGN='center' VALIGN='middle' BGCOLOR='steelblue' >
                                        <TH COLSPAN='2'> METODO : POST </TH>
                        </TR>   ";
                }

                foreach($_POST AS $key => $value)
                {

                        if(is_array($value))
                        {
                                        echo "
                                                <TR ALIGN='left' VALIGN='middle' ID='small' CELLSPACING=0 CELLPADDING=0>
                                                        <TH BGCOLOR='lightsteelblue' ALIGN='center'>$key</TH>
                                                        <TH>";
                                        echo "  <TABLE ALIGN='center' BORDER=0 CELLSPACING=1 CELLPADDING=2 ID='small' WIDTH='100%' BGCOLOR='black'>
                                                        <TR ALIGN='center' VALIGN='middle' ID='small' BGCOLOR='lightsteelblue' >
                                                                <TH COLSPAN='2'> Array : ".$key. "[] </TH>
                                                        </TR>\n";
                                        echo "
                                                        <TR ALIGN='center' VALIGN='middle' ID='small' BGCOLOR='lightsteelblue'>
                                                                <TH >Indice </TH>
                                                                <TH >Contenido</TH>
                                                        </TR>\n";

                                                        foreach($value AS $xkey => $xvalue )
                                                        {
                                                                echo "
                                                                        <TR ALIGN='left' VALIGN='middle' ID='small'>
                                                                                <TH ALIGN='right' BGCOLOR='lightsteelblue'>".$key."[".$xkey."]&nbsp;</TH>
                                                                                <TH BGCOLOR='White'>$xvalue</TH>
                                                                        </TR>\n";
                                                        }
                                        echo " </TABLE> ";
                                        echo " </TH> </TR>";
                        }
                        else
                        {

                                echo "
                                        <TR ALIGN='left' VALIGN='middle' ID='small'>
                                                <TH BGCOLOR='lightsteelblue'>$key</TH>
                                                <TH BGCOLOR='White'>$value</TH>
                                        </TR>\n";
                        }
                }
        //---------------------------------Metodo GET ----------------------------------------//

        if(count($_GET))
                {
                        echo "
                                <TR ALIGN='center' VALIGN='middle' BGCOLOR='steelblue' >
                                                <TH COLSPAN='2'> METODO : GET </TH>
                                </TR>\n";
                }

                foreach($_GET AS $key => $value)
                {
                                if(is_array($value))
                                {

                                        echo "
                                                <TR ALIGN='left' VALIGN='middle' ID='small' CELLSPACING=0 CELLPADDING=0>
                                                        <TH BGCOLOR='lightsteelblue' ALIGN='center'>$key</TH>
                                                        <TH >";
                                        echo "  <TABLE ALIGN='center' BORDER=0 CELLSPACING=1 CELLPADDING=2 ID='small' WIDTH='100%' BGCOLOR='black'>
                                                        <TR ALIGN='center' VALIGN='middle' ID='small' BGCOLOR='lightsteelblue' >
                                                                <TH COLSPAN='2'> Array : ".$key. "[] </TH>
                                                        </TR>\n";
                                        echo "  <TR ALIGN='center' VALIGN='middle' ID='small' BGCOLOR='lightsteelblue'>
                                                                <TH >Indice </TH>
                                                                <TH >Contenido</TH>
                                                        </TR>\n";

                                                        foreach($value AS $xkey => $xvalue )
                                                        {
                                                                echo "
                                                                        <TR ALIGN='left' VALIGN='middle' ID='small'>
                                                                                <TH ALIGN='right' BGCOLOR='lightsteelblue'>".$key."[".$xkey."]&nbsp;</TH>
                                                                                <TH BGCOLOR='White'>$xvalue</TH>
                                                                        </TR>\n";
                                                        }

                                        echo " </TABLE> ";
                                        echo " </TH> </TR>";

                                }
                                else
                                        echo "
                                                        <TR ALIGN='left' VALIGN='middle' ID='small'>
                                                                <TH BGCOLOR='lightsteelblue'>$key</TH>
                                                                <TH BGCOLOR='White'>$value</TH>
                                                        </TR>\n";

                }




        //---------------------------------Estado de la Sesion ----------------------------------------//




        if(count($_SESSION ))
        {
                        echo "
                                <TR ALIGN='center' VALIGN='middle' BGCOLOR='steelblue' >
                                                <TH COLSPAN='2'> SESSION : $session.name </TH>
                                </TR>\n";


                foreach($_SESSION  AS $key => $value)
                {

                                if(is_array($value))
                                {

                                        echo "
                                                <TR ALIGN='left' VALIGN='middle' ID='small'>
                                                        <TH BGCOLOR='lightsteelblue' ALIGN='center'>$key</TH>
                                                        <TH>";


                                        echo "  <TABLE ALIGN='center' BORDER=1 CELLSPACING=0 CELLPADDING=0 ID=SMALL WIDTH='100%'>
                                                        <TR ALIGN='center' VALIGN='middle' ID='small' BGCOLOR='lightsteelblue' >
                                                                <TH COLSPAN='2'> Array : ".$key. "[] </TH>
                                                        </TR>\n";

                                        echo "  <TR ALIGN='center' VALIGN='middle' ID='small' BGCOLOR='lightsteelblue'>
                                                                <TH >Indice </TH>
                                                                <TH >Contenido</TH>
                                                        </TR>\n";



                                                        foreach($value AS $xkey => $xvalue )
                                                        {
                                                                echo "
                                                                        <TR ALIGN='left' VALIGN='middle' ID='small'>
                                                                                <TH ALIGN='right' BGCOLOR='lightsteelblue'>".$key."[".$xkey."]&nbsp;</TH>
                                                                                <TH BGCOLOR='White'>$xvalue</TH>
                                                                        </TR>\n";
                                                        }

                                        echo " </TABLE> ";
                                        echo " </TH> </TR>";



                                }
                                else
                                {
                                        echo "
                                                                <TR ALIGN='left' VALIGN='middle' ID='small'>
                                                                        <TH BGCOLOR='lightsteelblue'>$key</TH>
                                                                        <TH BGCOLOR='White'>$value</TH>
                                                                </TR>\n";
                                }
                }



        echo "</TABLE> ";

        }



}
//-----------------------------------------------------------------------------------------------
function navegador()
{
 
 global$_SERVER;
 $browser ="";
 
        if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Gecko') )
        {
           
           if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Safari'))
           {
             $browser = 'Safari';
           }       
           if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Netscape') )
           {
             $browser = 'Netscape';
           }
           else if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') )
           {
             $browser = 'Firefox';
           }
           else
           {
             $browser = 'Mozilla';
           }
        }
        else if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') )
        {
           if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') )
           {
             $browser = 'Opera';
           }
           else
           {
             $browser = 'MSIE';
           }
        }
        else
        {
           $browser = 'Others browsers';
        }


        return $browser;
}
//-----------------------------------------------------------------------------------------------
/**************************************************************************************************/
// Visibilidad de informacion para el grupo
/**************************************************************************************************/
function visibilidadGrupo($db,$id_grupo)
{
    global $sys_path,$ID_SUC;

    $sql = "SELECT * FROM grupo WHERE ID_Grupo='".$id_grupo."'";
    $RS = $db->Execute($sql);
    $Vista_informacion = $RS->fields["Vista_informacion"];
    //$ID_Sucursal=$RS->fields["ID_Sucursal"];


    switch($Vista_informacion)
    {
      case "global":
        return -1;
       break;
       case "propia":
        return $ID_SUC;
       break;
       case "lista":
            $lista=array($ID_SUC);
              $sql = "SELECT ID_Sucursal FROM grupo_visibilidad_sucursales WHERE ID_Grupo='".$id_grupo."'";
              $RS_LISTA = $db->Execute($sql);
              $rows=$RS_LISTA->GetArray();
              if(!empty($rows))
              {
                foreach($rows as $key => $value)
                {
                  $lista[]=$value[0] ;
                }
              }
              if(!empty($lista)){
                  return join(",",$lista);
              }
              return 0;
              //print_r(($rows));
       break;

    }
    return 0;

}

/**************************************************************************************************/
// SI LA SUCURSAL ESTA CERRADA
/**************************************************************************************************/

function getsucursalStatus($sucursal)
{

                //global $_SERVER['SCRIPT_FILENAME'];

        $message =  '<h1 id="big" align="center">La Sucursal ha cerrado operaciones <br /> Para realizar cualquier operaci&oacute;n, tendr&aacute; que esperar hasta el d&iacute;a siguiente <br /> Para una explicaci&oacute;n mas detallada consulte con su Administrador</h1>';
        
        global $db;
        
        $q = "SELECT Max(`suc_cierre`.`suc_cierre_id`) as suc_cierre_id FROM `suc_cierre` WHERE `suc_cierre`.`ID_Sucursal` = '".$sucursal."' ";
        
        $rs = $db->Execute($q);
        
        /*debug($q);*/ 
        
        if($rs->fields["suc_cierre_id"] != ""){
        
                $suc_cierre = $rs->fields["suc_cierre_id"];
                
                $q = "SELECT 
                                `suc_cierre`.`suc_cierre_tipo`
                        FROM `suc_cierre` 
                        WHERE `suc_cierre`.`suc_cierre_id` = '".$suc_cierre."' ";
                        
                $rs = $db->Execute($q);
                
                $tipo  = $rs->fields["suc_cierre_tipo"];  
                
                if($tipo == 'Cierre'){
                
                        $q = "SELECT  `suc_cierre`.`suc_cierre_id` 
                                  FROM `suc_cierre`  
                                  WHERE `suc_cierre`.`ID_Sucursal` = '".$sucursal."' 
                                  AND `suc_cierre`.`suc_cierre_fechaOperacion` = '".date('Y-m-d')."'
                                  AND suc_cierre.suc_cierre_tipo = 'Apertura'";
                                        
                        $rs = $db->Execute($q);
                        
                        //debug($q);
                                                
                        if($rs->_numOfRows){
                                echo $message;
                                exit;
                        }else{
                                $q = "INSERT INTO suc_cierre (ID_Sucursal,suc_cierre_fechaOperacion,suc_cierre_hrs,suc_cierre_usrName,suc_cierre_tipo) 
                                        VALUES ('".$sucursal."','".date('Y-m-d')."','".date('H:i:s')."','".$_SESSION["NOM_USR"]."','Apertura')";
                                $db->Execute($q);
                                                                
                                                                $q = "INSERT INTO suc_cierre_log (ID_Sucursal,suc_cierre_log_fechaOperacion,suc_cierre_log_hrs,suc_cierre_log_usrName,suc_cierre_log_scriptInvocate,suc_cierre_log_tipo) 
                                        VALUES ('".$sucursal."','".date('Y-m-d')."','".date('H:i:s')."','".$_SESSION["NOM_USR"]."','".$_SERVER['SCRIPT_FILENAME']."','Apertura')";
                                $db->Execute($q);
                        }
                }
        }else{
                
                $q = "INSERT INTO suc_cierre (ID_Sucursal,suc_cierre_fechaOperacion,suc_cierre_hrs,suc_cierre_usrName,suc_cierre_tipo) 
                      VALUES ('".$sucursal."','".date('Y-m-d')."','".date('H:i:s')."','".$_SESSION["NOM_USR"]."','Apertura')";
                $db->Execute($q);
                                
                                $q = "INSERT INTO suc_cierre_log (ID_Sucursal,suc_cierre_log_fechaOperacion,suc_cierre_log_hrs,suc_cierre_log_usrName,suc_cierre_log_scriptInvocate,suc_cierre_log_tipo) 
                      VALUES ('".$sucursal."','".date('Y-m-d')."','".date('H:i:s')."','".$_SESSION["NOM_USR"]."','".$_SERVER['SCRIPT_FILENAME']."','Apertura')";
                $db->Execute($q);
        }
}

/**************************************************************************************************/
// Status de cada sucursal
/**************************************************************************************************/

function getStatusBySuc($sucursal)
{

        global $db;
        
        $q = "SELECT Max(`suc_cierre`.`suc_cierre_id`) as suc_cierre_id FROM `suc_cierre` WHERE `suc_cierre`.`ID_Sucursal` = '".$sucursal."' ";
        
        $rs = $db->Execute($q);
                
        if($rs->fields["suc_cierre_id"] != "")
        {
        
                $suc_cierre = $rs->fields["suc_cierre_id"];
                
                $q = "SELECT 
                             `suc_cierre`.`suc_cierre_tipo`
                        FROM `suc_cierre` 
                        WHERE `suc_cierre`.`suc_cierre_id` = '".$suc_cierre."' ";
                        
                $rs = $db->Execute($q);
                
                $tipo  = $rs->fields["suc_cierre_tipo"];  
                
                if($tipo == 'Cierre')
                {
                
                        $q = "SELECT  `suc_cierre`.`suc_cierre_id` FROM `suc_cierre`  WHERE `suc_cierre`.`suc_cierre_fechaOperacion` = '".date('Y-m-d')."' ";   
                        $rs = $db->Execute($q);
                        
                        if($rs->_numOfRows)
                        {
                                $status = true; 
                                
                        }
                        else
                        {
                                $status = false;
                        }
                }
                else
                {
                        $status = false;
                }
        }
        else
        {
                $status = true;
        }
        
        return $status;
}
