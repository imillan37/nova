<?php
/** 
 * LOGIN
 * acceso al sistema s2credit
 * dependencias : index.php , 
 * 
 *index.php 
*/

$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];

if(file_exists($DOCUMENT_ROOT.'/rutas.php')){
    echo "ok entra";
   include($DOCUMENT_ROOT.'/rutas.php');
}else{
    echo "<h1>Revisar la configuracion de el sistema no se puede cargar en este momento</h1>";
}

/**--------------------------------------------------------------------------------------------------------
 * -----------------
 --------------------------------------------------------------------------------------------------------*/
 if( empty($_SESSION['ID_GRP']) )
{
        error_msg( "No se encontró el grupo del usuario." );
        closepage();
        die();
}
$db = ADONewConnection(SERVIDOR);	# create a connection
$db->Connect(IP,USER,PASSWORD,NUCLEO);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu principal</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
     <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/0.2.0/js/bootstrap-notify.min.js" integrity="sha512-vCgNjt5lPWUyLz/tC5GbiUanXtLX1tlPXVFaX5KAQrUHjwPcCwwPOLn34YBFqws7a7+62h7FRvQ1T0i/yFqANA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="http://s2credit.net/novacredit/public/js/light-bootstrap-dashboard.js"></script>
    <script src="http://s2credit.net/novacredit/public/js/demo.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
   
</head>
<script>
     $(document).ready(function() 
        {
    //Limpia cookies 
    //$.cookie('SELECTED_MENU','',{ path: '/', expires: -3600 });
    //$.cookie('MENU_OPEN','',{ path: '/', expires: -3600 });
    //jQuery.cookie('SELECTED_MENU', '', {expires: 0}); 
    //jQuery.cookie('MENU_OPEN', '', {expires: 0});
      try{
        var f=parent.document;
        var fM=f.getElementById('mainFrame');
        fM.cols = "255,*";
         window.parent.frames["frmMenu"].location.reload(0);
      }catch(e){}
    });
    var iCount=top.frames.length;
    if(iCount!=2)
    {
      window.parent.document.location="<?=$sys_path?>/index.php";
    }
</script>
<body>

<?php
    echo "<BR><BR><BR><BR><BR>";
   $ID_SUC= $_SESSION['ID_SUC'];
   $sql = "SELECT Nombre, Direccion, Colonia, CP, Estado, Ciudad, Telefonos, FAX FROM sucursales WHERE   ID_Sucursal= '$ID_SUC' ";
    $rset=$db->Execute($sql);
    $sucursal=$rset->fields[0];
    $direccionsuc = $rset->fields[1]." Col. ".$rset->fields[2]." C.P. ".$rset->fields[3]." ".$rset->fields[4]." ".$rset->fields[5];
    $telsuc = $rset->fields[6];
    
    if( $rset->fields[7])
    {
    $telsuc .= "  Fax. ".$rset->fields[6] ;
    
    }



    echo "<CENTER> <DIV style=' font-size: 20px; font-family:  Verdana;AvantGarde;'>
                    <B> Bienvenido : </B> ".$_SESSION['NOM_USR']." <BR><BR><BR>
                    <U><B><FONT STYLE='color:black;' SIZE='5'>".$sucursal."</FONT></B></U> <BR>
                    <B><FONT STYLE='color:black;' SIZE='1'>".$direccionsuc."<BR> Tels.".$telsuc ."</FONT></B> <BR>
                
                
                
                
        </DIV></CENTER><BR><BR>";

if (isset($LOG_ACCESS)) 
{
    echo "entra if";
    echo $LOG_ACCESS;
}else{
    echo "entra else";
    print_r($_GET);
}

?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
     <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/0.2.0/js/bootstrap-notify.min.js" integrity="sha512-vCgNjt5lPWUyLz/tC5GbiUanXtLX1tlPXVFaX5KAQrUHjwPcCwwPOLn34YBFqws7a7+62h7FRvQ1T0i/yFqANA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="http://s2credit.net/novacredit/public/js/light-bootstrap-dashboard.js"></script>
    <script src="http://s2credit.net/novacredit/public/js/demo.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
   
</body>
</html>
