<?php 

$soap_client = new SoapClient("http://alphacredit.zell.mx");

$Uid='PROFINOMEX';
$Pwd='Z.PRO23x';
$ns = "http://tempuri.org/";

//Body of the Soap Header.
$headerbody = array('UserName' => $Uid,
                'Password' => $Pwd
                );
//Create Soap Header.       
$header = new SOAPHeader($ns, 'AuthHeader', $headerbody);       

//set the Headers of Soap Client.
$soap_client->__setSoapHeaders($header);
$par="<Wallet><SPName>AuthenticateMerchantWebVending</SPName><Parameters>&lt;Parameter&gt;&lt;Name&gt;@Account&lt;/Name&gt;&lt;Size&gt;50&lt;/Size&gt;&lt;Value&gt;1135600016&lt;/Value&gt;&lt;Type&gt;varchar&lt;/Type&gt;&lt;/Parameter&gt;&lt;Parameter&gt;&lt;Name&gt;@Password&lt;/Name&gt;&lt;Size&gt;20&lt;/Size&gt;&lt;Value&gt;0OgknrdonyM=&lt;/Value&gt;&lt;Type&gt;varchar&lt;/Type&gt;&lt;/Parameter&gt;</Parameters><ParameterCount>2</ParameterCount><DataBase>1</DataBase></Wallet>";
$param=array('xmlString'=>$par);

$result=$soap_client->__SoapCall('WS_GetData',$param);

print_r ($result);

?>
