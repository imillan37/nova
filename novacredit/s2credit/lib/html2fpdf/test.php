<?
require_once('html2fpdf.php');
// activate Output-Buffer:
ob_start();
?>
AQUI SE ESCRIBE
<h1>poca maaa guey</h1>
<?
// Output-Buffer in variable:
$html=ob_get_contents();
// delete Output-Buffer
ob_end_clean();
$pdf = new HTML2FPDF();
$pdf->DisplayPreferences('HideWindowUI');
$pdf->AddPage();
$pdf->WriteHTML($html);
$pdf->Output('doc.pdf','I');
?>