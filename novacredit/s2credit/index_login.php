<?php
$noheader   =   1;
$no_session_start = 1;

$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];

if(file_exists($DOCUMENT_ROOT)){
   include($DOCUMENT_ROOT.'/rutas.php');
}else{
    echo "<h1>Revisar la configuracion de el sistema no se puede cargar en este momento</h1>";
}

//echo $_SERVER['REMOTE_ADDR'];
if(chk_abuse_log($_SERVER['REMOTE_ADDR'])){
    
    echo '<!DOCTYPE html>';
    echo '<html lang="es">';
    echo '<head>';
    echo '<meta charset="UTF-8">';
    echo '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo "<link href='".$style_path."sistema.css' rel='stylesheet' type='text/css'>\n";
    echo '<title>S2 credit index login</title>';
    echo '</head>';
    echo '<body>';

    echo "<H1 Align='center' STYLE='color:red;'><U>Su IP se encuentra en lista negra.</U></H1>\n";

   die('</body></html>');    
    
    

}else{
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="favicon.ico">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <link rel="stylesheet" href="http://s2credit.net/novacredit/public/css/light-bootstrap-dashboard.css">
    <link rel="stylesheet" href="http://s2credit.net/novacredit/public/css/demo.css">
    <link rel="stylesheet" href="http://s2credit.net/novacredit/public/css/pe-icon-7-stroke.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>S2credit index login</title>
</head>
<body>
<div class="wrapper wrapper-full-page">
    <div class="full-page login-page" data-color="black">   
        
    <!--   you can change the color of the filter page using: data-color="blue | azure | green | orange | red | purple" -->
        <div class="content">
            <div class="container">
                <div class="row">                   
                    <div class="col-md-4 col-sm-6 col-md-offset-4 col-sm-offset-3">
                        <form class="form-signin form-signin-accion" id="form-signin-accion" method="post" action="<?='http://s2credit.net/'.$sys_path?>login.php">
                        <!--   if you want to have the card without animation please remove the ".card-hidden" class   -->
                            <div class="card card-hidden">
                                <div class="header text-center">Login</div>
                                <div class="content">
                                    <div class="form-group">
                                        <label>Usuarios</label>
                                        <input type="text" placeholder="Usuarios" autocomplete="off" autofocus name="user" required class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Contraseña</label>
                                        <input type="password" placeholder="Contraseña" autocomplete="off" name="passwd" required class="form-control">
                                    </div>                                    
                                </div>
                                <div class="footer text-center">
                                    <button type="submit" class="btn btn-fill btn-block btn-sm btn-primary btn-wd submit" disabled><span id="dataSource">Login ( Loading... )</span></button>
                                </div>
                            </div>
                        </form>
                    </div>                    
                </div>
            </div>
        </div>
    	
    </div>                             
</div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
     <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/0.2.0/js/bootstrap-notify.min.js" integrity="sha512-vCgNjt5lPWUyLz/tC5GbiUanXtLX1tlPXVFaX5KAQrUHjwPcCwwPOLn34YBFqws7a7+62h7FRvQ1T0i/yFqANA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="http://s2credit.net/novacredit/public/js/light-bootstrap-dashboard.js"></script>
    <script src="http://s2credit.net/novacredit/public/js/demo.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>

</body>
    
    <script type="text/javascript">
        $(function() {
  // Document is ready
           setTimeout(function(){
            $('.card').removeClass('card-hidden');
           },100)
           $('.submit').removeAttr('disabled');
           $('#dataSource').html('Login');
            });
    </script>
</html>

<?php
}
?>