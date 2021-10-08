<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<HTML>
<HEAD>
<TITLE></TITLE>
<META NAME="Generator" CONTENT="TextPad 4.0">
<META NAME="Author" CONTENT="?">
<META NAME="Keywords" CONTENT="?">
<META NAME="Description" CONTENT="?">
</HEAD>

<BODY BGCOLOR="#FFFFFF" TEXT="#000000" LINK="#FF0000" VLINK="#800000" ALINK="#FF00FF" BACKGROUND="?">

<?

$cadena = "27/09/2004  0700  CONCENTRACION DE PAGOS          003               0.00             343.17            5417.34             
      PAGO DETALLE 011898 0000000000000000000000000000000000215990";
$tok = strtok ($cadena," ");
while ($tok) 
{
    echo "Palabra=$tok<br>";
    $tok = strtok (" ");
}
   
?>

</BODY>
</HTML>