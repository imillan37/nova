<?php
/** 
 * LOGIN
 * acceso al sistema s2credit
 * dependencias : index.php , 
 * 
 *index.php 
*/
$noheader=1;
$login=1;
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];

if(file_exists($DOCUMENT_ROOT.'/rutas.php')){
   include($DOCUMENT_ROOT.'/rutas.php');
}else{
    echo "<h1>Revisar la configuracion de el sistema no se puede cargar en este momento</h1>";
}
$theip = $_SERVER["REMOTE_ADDR"];

if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
    $theip .= '('.$_SERVER["HTTP_X_FORWARDED_FOR"].')';
    echo "la ip =".$theip;
}

if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
    $theip .= '('.$_SERVER["HTTP_CLIENT_IP"].')';
    echo "la ip =".$theip;
}

$USR_IP = substr($theip, 0, 250);
// $USR_IP =     (getenv(HTTP_X_FORWARDED_FOR))
//             ?  getenv(HTTP_X_FORWARDED_FOR)
//             :  getenv(REMOTE_ADDR);
$LOGINT = date("Y-m-d H:i:s");
$ID_EMP=1;         
$Pos = strpos($_SERVER["HTTP_REFERER"],$_SERVER["HTTP_HOST"]);
// echo $_SERVER["HTTP_REFERER"]."<br>";
// echo $_SERVER["HTTP_HOST"]."<br>";
if ($Pos === false) {

    error_msg( "Se detecto un intento de acceso ilegal." );
    echo "<center><input name='button' type='button' onclick='javascript:history.back(1)' value='Cerrar' /></center>";


    die("</BODY></HTML>");
}

/*******************************************************************
Array ( [PHPSESSID] => 860am6d95eei7r78dq4hno719a )
***************************************************************/
foreach($_COOKIE as $key => $value) 
{
        setcookie($key,$value,time()-10000);
}

if(isset($none) AND $none == 1)
{
    echo  "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.0 Transitional//EN'>\n";
    "<HTML>\n";
    "<HEAD>\n";
    "<TITLE>&nbsp;</TITLE>\n";
    "<META http-equiv=Content-Type content='text/html; charset=iso-8859-1'></HEAD>\n";
    "<BODY>\n";
    "</BODY>\n";
    "</HTML>\n";
    die();
}
$db = NewADOConnection(SERVIDOR);
$db->Connect(IP,USER,PASSWORD,NUCLEO);
$db->port = 4406;

$sql="DELETE FROM sessions where (FROM_UNIXTIME(UNIX_TIMESTAMP()) > FROM_UNIXTIME(expiry) ) ";

$db->Execute($sql);


/** */
$br="<BR><BR><BR><BR><BR><BR><BR>\n";
/** */

if(chk_abuse_log($_SERVER['REMOTE_ADDR']))
{
        echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 3.2 Final//EN\">\n";
        echo "<HTML>\n";
        echo "<HEAD>\n";
        echo "<title>S2Credit</title>\n";
        echo "<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>\n";
        echo "<link href='".$style_path."sistema.css' rel='stylesheet' type='text/css'>\n";
        echo "</HEAD>\n";


        echo "<BODY><BR><BR><BR><BR>\n";
        echo "<H1 Align='center' STYLE='color:red;'><U>Su IP se encuentra en lista negra.</U></H1>\n";
        die("</BODY></HTML>");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <link rel="icon" type="image/png" href="favicon.ico">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS -->
    <link rel="stylesheet" href="<?="http://".$_SERVER['SERVER_NAME'].$style_path."sistema.css";?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <link rel="stylesheet" href="http://s2credit.net/novacredit/public/css/light-bootstrap-dashboard.css">
    <link rel="stylesheet" href="http://s2credit.net/novacredit/public/css/demo.css">
    <link rel="stylesheet" href="http://s2credit.net/novacredit/public/css/pe-icon-7-stroke.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
    <title>S2credit login</title>
</head>

   <?php  
   
   /* *********************************************/
        $detalle = array();
        
        if(isset($_POST['MULTI']) AND $_POST['MULTI'] == 1)
        {
            echo "entra al multi ok";
            
            $ID_GRP=$_SESSION['ID_GRP'];
            $ID_SUC=$_SESSION['ID_SUC'];
            //$MSG_ENBLD = $_SESSION['MSG_ENBLD'];
            
            $aryID=str_split(",",$_SESSION['ID_USR']);

            print_r($aryID);
           // list($ID_GRP,$ID_SUC) = $aryID;
    
            if(empty($ID_GRP) || empty($ID_SUC))
            {
                    echo $br;
                    error_msg( " No se encontró el grupo del usuario. Imposible continuar." );
                    die("</BODY></HTML>");
            }
        $MSG_ENBLD = system_const( "MENSAJES_ENTRE_USUARIOS" ,$db );
             /*sessiones   
        session_register(LOGINT);
        session_register(USR_IP);
        session_register(ID_EMP);
        session_register(ID_GRP);
        session_register(ID_USR);
        session_register(ID_SUC);
        session_register(NOM_USR);
        session_register(DB_EMP);
        session_register(MSG_ENBLD);
           
        $_SESSION['LOGINT'] =   LOGINT;  
        $_SESSION['USR_IP'] =   USR_IP;   
        $_SESSION['ID_EMP'] =   ID_EMP;   
        $_SESSION['ID_GRP'] =   ID_GRP;   
        $_SESSION['ID_USR'] =   ID_USR;   
        $_SESSION['ID_SUC'] =   ID_SUC;   
        $_SESSION['NOM_USR'] =  NOM_USR;   
        $_SESSION['DB_EMP'] =   DB_EMP;
        $_SESSION['MSG_ENBLD'] = MSG_ENBLD;      

        $DB_EMP= NUCLEO;

            /*Guardar en sesion datos usuario
        *
        */
        $sql = "SELECT Nombre FROM sucursales WHERE   ID_Sucursal= '".$ID_SUC."' ";
        $rset=$db->Execute($sql);
        $SUC_NOM=$rset->fields[0];
        $sql = "SELECT Nombre FROM empresas WHERE   ID_Empresa='1' ";
        $rset=$db->Execute($sql);
        $EMP_NOM=$rset->fields[0];
        
        $_SESSION['SUC_NOM'] = $SUC_NOM ;
        $_SESSION['EMP_NOM'] = $EMP_NOM ;

        /*
        session_register(SUC_NOM);
        session_register(EMP_NOM);
        */
        $hostname = ($_SERVER['REMOTE_ADDR']);
        log_access($_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'], $_SERVER['REQUEST_METHOD'], $_SESSION['ID_USR'] , $hostname, $db );

        echo "<SCRIPT>\n";
        echo "$(document).ready(function(){ \n ";
        echo "$.cookie('SELECTED_MENU', '', {expires: 0}); \n";
        echo "$.cookie('MENU_OPEN', '', {expires: 0});     \n";
        echo "location.replace('./menu/entrada.php');           \n";
        echo "}); \n";
        echo "</SCRIPT> \n";

        die("</BODY></HTML>");

        }//end if MULTI
      
   ?>
   <BODY BGCOLOR="#FFFFFF" TEXT="#080000" bgproperties="fixed"  LINK="#333399" ALINK="#9A2873" VLINK="#6666CC" > 
   <?php
    if(isset($_POST['user'])){
        $user   = $_POST['user'];
    }else{
        $user = $_SESSION['NOM_USR'];
    }
    if(isset($_POST['passwd'])){
        $passwd = md5($_POST['passwd']);
    }
    
        /** */
            $sql=" SELECT  a.ID_User,
            a.Login,
            CONCAT(a.Nombre,' ',a.AP_Paterno,' ',a.AP_Materno) as Usuario,
            b.ID_grupo,
            b.Nombre as grupo,
            d.ID_Sucursal,
            c.Nombre as Sucursal,
            a.Stat,
            a.RestriccionHorario

            FROM usuarios a,
            grupo b,
            grupo_usuarios d

            LEFT JOIN  sucursales c ON  d.ID_Sucursal = c.ID_Sucursal

            WHERE c.ID_Sucursal IS NOT NULL      and
            c.ID_Sucursal<>0                     and
            a.ID_User  = d.ID_User               and
            b.ID_grupo = d.ID_grupo              and
            a.Login    = '".addslashes($user)."' and
            a.Password = '".addslashes($passwd)."'";
        /** */  
        $rset = $db->Execute($sql);
        //-------------------------------------------------------------------
        //Comprobar si el usuario esta dentro de su rango de acceso de entrada
        $user = $rset->fields[0];
        //echo $user;
        $NOM_USR=$rset->fields['Usuario'];
       /******************************************************* */
       /******************************************************* */
        $sql="SELECT hora_start, hora_exit FROM usuarios WHERE ID_User = '".addslashes($user)."' ";
        $rs_hora = $db->Execute($sql);
        $tiempo_fuera=false;
        $tiempo=time();
        $hora  =    strftime("%H:%M",$tiempo);

        $MSG_ENBLD = system_const( "MENSAJES_ENTRE_USUARIOS" ,$db );
        //echo $MSG_ENBLD;
        /**---------------------------------------------------------------- */
        //Verificar si el usuario esta dentro del rango de entrada de lo contrario habilitamos la bandera $tiempo_fuera
        if($rset->fields[0])
        {
            
            $ID_USR = $rset->fields[0];
            $sql = "DELETE FROM sessions WHERE data LIKE '%ID_USR______________".$ID_USR."______%'  ";
            $db->Execute($sql);

            /** */
            if($rset->fields['RestriccionHorario']){
                if(($rs_hora->fields[0 > $hora]) or ($rs_hora->fields[1] < $hora ))
                {
                    $tiempo_fuera = true;
                    echo $tiempo_fuera;
                }
            }



        } // fin de el if $rset->fields[0]
        /****************************************************************** */
        /**---------------------------------------------------------------- */
        if ($rset->fields[7]=='Activo' and (!$tiempo_fuera)) 
        {
            $ID_USR    =  $rset->fields[0];
            $ID_GRP    =  $rset->fields[3];
            $NOM_USR   =  $rset->fields[2];
            $ID_SUC    =  $rset->fields[5];
            $NOM_EMP   =  $rset->fields[6];
            $DB_EMP    =  NUCLEO;
            $LOG_ACCESS  =  "False"; 
           
           // $_SESSION['USR_IP'] =   USR_IP;   
            
            $_SESSION['ID_GRP']     =   $ID_GRP ;   
            $_SESSION['ID_USR']     =   $ID_USR;   
            $_SESSION['ID_SUC']     =   $ID_SUC;   
            $_SESSION['NOM_USR']    =   $NOM_USR ;   
            $_SESSION['DB_EMP']     =   $DB_EMP;
            $_SESSION['MSG_ENBLD']  =   $MSG_ENBLD; 
            
             /*Guardar en sesion datos usuario**/
            $sql = "SELECT Nombre FROM sucursales WHERE   ID_Sucursal= '".$ID_SUC."' ";
            $rset_aux=$db->Execute($sql);
            $SUC_NOM=$rset_aux->fields[0];
            $sql = "SELECT Nombre FROM empresas WHERE   ID_Empresa='1' ";
            $rset_aux=$db->Execute($sql);
            $EMP_NOM=$rset_aux->fields[0];
            $_SESSION['SUC_NOM']    =  $SUC_NOM ;
            $_SESSION['EMP_NOM']    =  $EMP_NOM  ;

            print_r($_SESSION);

        }else{//end $rset->fields[7]=='Activo' and (!$tiempo_fuera)
            
            #echo "entra else";
            if ($tiempo_fuera == True) 
            {
                echo $br;
                log_error($REMOTE_ADDR, $HTTP_USER_AGENT, $REQUEST_METHOD, ($_POST['user']) ,'[Fuera de horario]', $db,1 );
                error_msg( "Inicio de sesion fuera de horario." );

            }else{ //$tiempo_fuera == True

                echo $br;
                log_error($_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'], $_SERVER['REQUEST_METHOD'], ($_POST['user']) ,addslashes($_POST['passwd']), $db,2 );
                //--------------------
                    if (($rset->_numOfRows > 0 ) and ( $rset->fields['Stat'] != 'Activo')) {

                        error_msg( "La cuenta de usuario <U>".$user."</U> ha sido temporalmente desactivada." );
                    
                    } else{  #End  else $rset->_numOfRows > 0 ) and ( $rset->fields['Stat'] != 'Activo'
                                $sql="  SELECT      a.Password, b.ID_grupo, a.Tipo, c.ID_Sucursal, a.ID_User
                                FROM       usuarios a,
                                grupo b,
                                grupo_usuarios d

                                LEFT JOIN  sucursales c ON  d.ID_Sucursal = c.ID_Sucursal

                                WHERE
                                a.ID_User  = d.ID_User               and
                                b.ID_grupo = d.ID_grupo              and
                                a.Login    = '".addslashes($_POST['user'])."' and
                                a.Password = MD5('".addslashes($_POST['passwd'])."') ";

                                $rset=$db->Execute($sql);

                                if((!$rset->_numOfRows))
                                {

                                    // error_msg( "El usuario ".addslashes($_POST['user'])." no está registrado en el sistema." );
                                        error_msg( "Contraseña incorrecta. (1) " );

                                }
                                else
                                if($rset->fields[0] != md5($_POST['passwd']))
                                {

                                        //error_msg( "El usuario ".addslashes($_POST['user'])." no está registrado o su clave es incorrecta." );
                                        error_msg( " Contraseña incorrecta. (2) " );

                                }
                                else
                                if(empty($rset->fields[1]))
                                {

                                        error_msg( "No hay grupos de trabajo definidos para el usuario ".addslashes($_POST['user'])."." );

                                }
                                else
                                if(empty($rset->fields[2]))
                                {

                                        error_msg( "No hay manera de dicernir a de que tipo de usuario se trata ".addslashes($_POST['user'])."." );

                                }
                                else
                                if(empty($rset->fields[3]))
                                {

                                        error_msg( "No hay ninguna sucursal definida para el prefil del usuario ".addslashes($_POST['user'])."." );

                                }
                                else
                                {
                                        error_msg( "Existe un error permanente en la cuenta del usuario.<BR> Solicite una revisión de su cuenta al administrador del sistema." );

                                }

                    }#End  else $rset->_numOfRows > 0 ) and ( $rset->fields['Stat'] != 'Activo'
                //--------------------
            }// end else tiempo_fuera
session_destroy();

echo "<CENTER> <BUTTON onClick='location.replace(\"".$sys_path."index.html\")' ID='S2'> Regresar </BUTTON> </CENTER>";


die("</BODY></HTML>");
        
        }
        
/****************************************************************** */
/**---------------------------------------------------------------- */
/****************************************************************** */

if ($rset->_numOfRows == 1) {
    
    
    $_SESSION['ID_SUC']     =   $ID_SUC; 
    $_SESSION['ID_GRP']     =   $ID_GRP ; 
    $_SESSION['DB_EMP']     =   $DB_EMP;  
     //Para eliminar las otras sesiones, y solo dejar la nueva sesion  
        if($ID_USR)
        {
                $sql="delete from sessions where data like '%ID_USR______________".$ID_USR."______%' 
                        AND (FROM_UNIXTIME(UNIX_TIMESTAMP()) < FROM_UNIXTIME(expiry) ) ";
                $db->Execute($sql);
        }  
        
        /*-------------------------*/
        $hostname = ($_SERVER['REMOTE_ADDR']);

        log_access($REMOTE_ADDR, $HTTP_USER_AGENT, $REQUEST_METHOD, $ID_USR , $hostname, $db );
        
        echo "<script language=\"JavaScript1.2\">\n";
        echo "jQuery.cookie('SELECTED_MENU', '', {expires: 0}); \n";
        echo "jQuery.cookie('MENU_OPEN', '', {expires: 0});     \n";
        
        echo "\t location.replace(\"./menu/entrada.php?login=1\"); \n";
        echo "</script>\n\n";
        die("</BODY></HTML>");    

}
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
<link rel="stylesheet" href="http://s2credit.net/novacredit/public/css/light-bootstrap-dashboard.css">
<link rel="stylesheet" href="http://s2credit.net/novacredit/public/css/demo.css">
<link rel="stylesheet" href="http://s2credit.net/novacredit/public/css/pe-icon-7-stroke.css">

<div class="main-panel" style="width:100%;">
<br>
    <div class="card" style="width:95%; margin-right: auto; margin-left: auto;">
		<div class="header">
		    <h4 class="title"><span id="login_entrada_titulo">Bienvenido</span></h4>
		</div>
		<div class="content content-full-width">
		    <ul role="tablist" class="nav nav-tabs">
                <li role="presentation" class="active">
                    <a href="#agency" data-toggle="tab">Información del usuario</span></a>
                </li>
            </ul>
			<div class="tab-content" style="height:70%;">
				<div id="agency" class="tab-pane active">
				
				    <div class="col-md-5">
                        <div class="card card-user">
                            <div class="content">
                                <p class="description text-center"> 
                                    <p><font color="#000000"> Usuario: <?=$NOM_USR?> </font></p>
                                    <p align="center"><center>

<?php

if($ID_USR)
{

        $sql="DELETE FROM sessions where data like '%ID_USR______________".$ID_USR."______%'  AND (FROM_UNIXTIME(UNIX_TIMESTAMP()) < FROM_UNIXTIME(expiry) ) ";
        $db->Execute($sql);
}
/*

Para validar que el usuario no tenga otra sesion activa
$sql="select count(*) from sessions where data like '%ID_USR______________".$ID_USR."______%' 
        AND (FROM_UNIXTIME(UNIX_TIMESTAMP()) < FROM_UNIXTIME(expiry) ) ";

$rsExp=$db->Execute($sql);
$active_sessions=0;
if(!$rsExp->EOF) $active_sessions=$rsExp->fields[0];
if ($active_sessions> 0)
{
  ADODB_Session::destroy(session_id());
  
  session_destroy();
  error_msg( "El usuario ya cuenta con  una session activa en el sistema" );
  die();
}
*/


                      
echo "<FORM name='grp' METHOD='POST' ACTION='http://".$_SERVER['SERVER_NAME'].$_SERVER["PHP_SELF"]."'>";

echo "  <BR><BR><BR>";
echo "  Seleccione el perfil con el que desea ingresar :     &nbsp;
</TD>
<TD>";
echo "<INPUT type='hidden' name='MULTI'   VALUE='1' >";
echo "<INPUT type='hidden' name='ID_USR'  VALUE='".$ID_USR."' >";
echo "<INPUT type='hidden' name='NOM_USR' VALUE='".$NOM_USR."' >";
echo "<SELECT name='ID'  ID='S2'>";


        // Ultima sesión realizada
        $sql = "SELECT ID_Grupo FROM access_log Where User= '".$ID_USR."' ORDER BY Fecha DESC, Hora DESC LIMIT 0,1"; 
        $rs = $db->Execute($sql); 

        $Pre_ID_Grupo = $rs->fields['ID_Grupo'];

        //debug("Pre_ID_Grupo $Pre_ID_Grupo");
        //debug($sql);  
$i=0;
while(!$rset->EOF)
{
        $sel = ($rset->fields['ID_grupo'] == $Pre_ID_Grupo)?("SELECTED"):("");
        echo "<OPTION  VALUE='". $rset->fields["ID_grupo"].",".$rset->fields["ID_Sucursal"]."' ".$sel." >".$rset->fields[6]." &nbsp;&nbsp;: &nbsp;&nbsp;".$rset->fields[4]."</OPTION>     ";
        $rset->MoveNext();
        $i++;
}
echo " </SELECT></center></p>";
echo "<p><center><INPUT name='entrar' type='image' src='images/btn_entrar.png' border='0'></center></p>";
echo "</FORM> ";
                                            
                                           ?> 
                            </div>
                            <hr>
                        </div>
                    </div>
				    <div class="col-md-7">
					    <div id="callout-navbar-role" class="bs-callout bs-callout-warning">
						    
							<?php
                           /* $db = NewADOConnection(SERVIDOR);
                            $db->Connect(IP,USER,PASSWORD,NUCLEO);
								$db = &ADONewConnection(SERVIDOR);
								$db->PConnect(IP,USER,PASSWORD,NUCLEO);*/
							
								$sql = "SELECT Logo FROM empresas ";
								$rs = $db->Execute($sql);
								$logoempresa = $rs->fields[0];
							
								if( file_exists($DOCUMENT_ROOT.$img_path.$logoempresa)  and (!empty($logoempresa)))
								$logo = $img_path.$logoempresa;
								else
								$logo = $img_path.'s2credit.png';
							
							//debug($DOCUMENT_ROOT.$img_path.$logo);
							
							?>
						</div>	
					<p><img src="<?=$logo?>" alt="..." width="100%" height="100%" class="img-thumbnail"></p>
				</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
die("</BODY></HTML>");
?>

</body>
</html>

