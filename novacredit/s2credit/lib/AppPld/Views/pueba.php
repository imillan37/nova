<?
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//ini_set('allow_url_fopen','on');


// Crea un nuevo recurso cURL
$ch = curl_init();

// Establece la URL y otras opciones apropiadas
curl_setopt($ch, CURLOPT_URL, "http://www.un.org/sc/committees/1267/AQList.xml");


// Captura la URL y la envía al navegador
$yya = curl_exec($ch);

$xml		= simplexml_load_file($yya);
foreach( $xml->INDIVIDUALS->INDIVIDUAL as  $value) 
{

echo $value->FIRST_NAME." <-->".$value->SECOND_NAME;
}

//echo "<pre>";
//print_r($xml);
//echo "</pre>";
// Cierrar el recurso cURLy libera recursos del sistema
//curl_close($ch);

?>