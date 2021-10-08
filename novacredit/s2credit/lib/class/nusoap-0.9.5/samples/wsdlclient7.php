<?php
/*
 *	$Id: wsdlclient7.php,v 1.2 2007/11/06 14:49:10 snichol Exp $
 *
 *	WSDL client sample.
 *
 *	Service: WSDL
 *	Payload: document/literal
 *	Transport: http
 *	Authentication: digest
 */
require_once('../lib/nusoap.php');
$proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
$proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
$proxyusername = "PROFINOMEX"; // isset($_POST['proxyusername']) ? $_POST['proxyusername'] : 'PROFINOMEX';
$proxypassword = "Z.PRO23x"; //isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';
$useCURL = isset($_POST['usecurl']) ? $_POST['usecurl'] : '0';
//echo 'You must set your username and password in the source';
//exit();
$client = new nusoap_client("http://alphacredit.zell.mx/service/zellFactoring.asmx?op=zfpApp&xApp=".getXML(), 'wsdl',
						$proxyhost, $proxyport, $proxyusername, $proxypassword);
$err = $client->getError();
if ($err) {
	echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
}
$client->setUseCurl($useCURL);
$client->loadWSDL();
$client->setCredentials($username, $password, 'digest');


 

$result = $client->call( "zfpApp", array( 'xApp' => getXML() ) );





// Check for a fault
if ($client->fault) {
	echo '<h2>Fault</h2><pre>';
	print_r($result);
	echo '</pre>';
} else {
	// Check for errors
	$err = $client->getError();
	if ($err) {
		// Display the error
		echo '<h2>Error</h2><pre>' . $err . '</pre>';
	} else {
		// Display the result
		echo '<h2>Result</h2><pre>';
		print_r($result);
		echo '</pre>';
	}
}
echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';


function getXML() {

$xml = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <zfpApp xmlns="http://tempuri.org/">
      <xApp>
        <ErrorCode>entero</ErrorCode>
        <ErrorDescription>cadena</ErrorDescription>
        <UserId>PROFINOMEX</UserId>
        <Password>Z.PRO23x</Password>
        <AppId>cadena</AppId>
        <FirstName>cadena</FirstName>
        <SecondName>cadena</SecondName>
        <FLastName>cadena</FLastName>
        <SLastName>cadena</SLastName>
        <OpIdType>entero</OpIdType>
        <IdNumber>cadena</IdNumber>
        <TreasuryId>cadena</TreasuryId>
        <StateId>cadena</StateId>
        <LegalId>cadena</LegalId>
        <OpSex>short</OpSex>
        <OpNationality>short</OpNationality>
        <OpMaritalStatus>short</OpMaritalStatus>
        <OpMaritalRegime>short</OpMaritalRegime>
        <BirthDate>cadena</BirthDate>
        <EMail>cadena</EMail>
        <Dependents>entero</Dependents>
        <OpEducation>entero</OpEducation>
        <SpFirstName>cadena</SpFirstName>
        <SpSecondName>cadena</SpSecondName>
        <SpFLastName>cadena</SpFLastName>
        <SpSLastName>cadena</SpSLastName>
        <Mobile>cadena</Mobile>
        <OpPropTypeAd>entero</OpPropTypeAd>
        <StreetAd>cadena</StreetAd>
        <ExtNumberAd>cadena</ExtNumberAd>
        <enteroNumberAd>cadena</enteroNumberAd>
        <CornerAd>cadena</CornerAd>
        <NeighborhoodAd>cadena</NeighborhoodAd>
        <TownshipAd>cadena</TownshipAd>
        <CountryIdAd>cadena</CountryIdAd>
        <CityAd>cadena</CityAd>
        <StateIdAd>cadena</StateIdAd>
        <ZipAd>entero</ZipAd>
        <PhoneNumberAd>cadena</PhoneNumberAd>
        <YearsAd>entero</YearsAd>
        <MonthsAd>entero</MonthsAd>
        <CompanyJo>cadena</CompanyJo>
        <PayJo>doble</PayJo>
        <PhoneNumberJo>cadena</PhoneNumberJo>
        <ExtensionNumberJo>cadena</ExtensionNumberJo>
        <SectorIdJo>cadena</SectorIdJo>
        <SectorActivityIdJo>cadena</SectorActivityIdJo>
        <BossJo>cadena</BossJo>
        <PositionJo>cadena</PositionJo>
        <YearsJo>entero</YearsJo>
        <MonthsJo>entero</MonthsJo>
        <OpEmployeeTypeJo>entero</OpEmployeeTypeJo>
        <StreetJo>cadena</StreetJo>
        <ExtNumberJo>cadena</ExtNumberJo>
        <enteroNumberJo>cadena</enteroNumberJo>
        <CornerJo>cadena</CornerJo>
        <NeighborhoodJo>cadena</NeighborhoodJo>
        <TownshipJo>cadena</TownshipJo>
        <CountryIdJo>cadena</CountryIdJo>
        <CityJo>cadena</CityJo>
        <StateIdJo>cadena</StateIdJo>
        <ZipJo>entero</ZipJo>
        <OpTypeJo>entero</OpTypeJo>
        <Reference>cadena</Reference>
        <AfiliateId>cadena</AfiliateId>
        <DelegationId>cadena</DelegationId>
        <ModuleId>cadena</ModuleId>
        <Afiliation>cadena</Afiliation>
        <BranchId>cadena</BranchId>
        <EmployeeId>cadena</EmployeeId>
        <FinancingId>cadena</FinancingId>
        <Payments>entero</Payments>
        <Solicited>doble</Solicited>
        <CampaignId>cadena</CampaignId>
        <OpDisposition>cadena</OpDisposition>
        <Bank>cadena</Bank>
        <AccountNumber>cadena</AccountNumber>
        <OpRelationType1>entero</OpRelationType1>
        <FirstNameRe1>cadena</FirstNameRe1>
        <SecondNameRe1>cadena</SecondNameRe1>
        <FLastNameRe1>cadena</FLastNameRe1>
        <SLastNameRe1>cadena</SLastNameRe1>
        <PhoneNumberRe1>cadena</PhoneNumberRe1>
        <YearsRe1>entero</YearsRe1>
        <MonthsRe1>entero</MonthsRe1>
        <StreetRe1>cadena</StreetRe1>
        <ExtNumberRe1>cadena</ExtNumberRe1>
        <enteroNumberRe1>cadena</enteroNumberRe1>
        <CornerRe1>cadena</CornerRe1>
        <NeighborhoodRe1>cadena</NeighborhoodRe1>
        <TownshipRe1>cadena</TownshipRe1>
        <CityRe1>cadena</CityRe1>
        <StateIdRe1>cadena</StateIdRe1>
        <CountryIdRe1>cadena</CountryIdRe1>
        <ZipRe1>entero</ZipRe1>
        <OpRelationType2>entero</OpRelationType2>
        <FirstNameRe2>cadena</FirstNameRe2>
        <SecondNameRe2>cadena</SecondNameRe2>
        <FLastNameRe2>cadena</FLastNameRe2>
        <SLastNameRe2>cadena</SLastNameRe2>
        <PhoneNumberRe2>cadena</PhoneNumberRe2>
        <YearsRe2>entero</YearsRe2>
        <MonthsRe2>entero</MonthsRe2>
        <StreetRe2>cadena</StreetRe2>
        <ExtNumberRe2>cadena</ExtNumberRe2>
        <enteroNumberRe2>cadena</enteroNumberRe2>
        <CornerRe2>cadena</CornerRe2>
        <NeighborhoodRe2>cadena</NeighborhoodRe2>
        <TownshipRe2>cadena</TownshipRe2>
        <CityRe2>cadena</CityRe2>
        <StateIdRe2>cadena</StateIdRe2>
        <CountryIdRe2>cadena</CountryIdRe2>
        <ZipRe2>entero</ZipRe2>
        <OpRelationType3>entero</OpRelationType3>
        <FirstNameRe3>cadena</FirstNameRe3>
        <SecondNameRe3>cadena</SecondNameRe3>
        <FLastNameRe3>cadena</FLastNameRe3>
        <SLastNameRe3>cadena</SLastNameRe3>
        <PhoneNumberRe3>cadena</PhoneNumberRe3>
        <YearsRe3>entero</YearsRe3>
        <MonthsRe3>entero</MonthsRe3>
        <StreetRe3>cadena</StreetRe3>
        <ExtNumberRe3>cadena</ExtNumberRe3>
        <enteroNumberRe3>cadena</enteroNumberRe3>
        <CornerRe3>cadena</CornerRe3>
        <NeighborhoodRe3>cadena</NeighborhoodRe3>
        <TownshipRe3>cadena</TownshipRe3>
        <CityRe3>cadena</CityRe3>
        <StateIdRe3>cadena</StateIdRe3>
        <CountryIdRe3>cadena</CountryIdRe3>
        <ZipRe3>entero</ZipRe3>
        <OpRelationType4>entero</OpRelationType4>
        <FirstNameRe4>cadena</FirstNameRe4>
        <SecondNameRe4>cadena</SecondNameRe4>
        <FLastNameRe4>cadena</FLastNameRe4>
        <SLastNameRe4>cadena</SLastNameRe4>
        <PhoneNumberRe4>cadena</PhoneNumberRe4>
        <YearsRe4>entero</YearsRe4>
        <MonthsRe4>entero</MonthsRe4>
        <StreetRe4>cadena</StreetRe4>
        <ExtNumberRe4>cadena</ExtNumberRe4>
        <enteroNumberRe4>cadena</enteroNumberRe4>
        <CornerRe4>cadena</CornerRe4>
        <NeighborhoodRe4>cadena</NeighborhoodRe4>
        <TownshipRe4>cadena</TownshipRe4>
        <CityRe4>cadena</CityRe4>
        <StateIdRe4>cadena</StateIdRe4>
        <CountryIdRe4>cadena</CountryIdRe4>
        <ZipRe4>entero</ZipRe4>
        <OpCommType1>entero</OpCommType1>
        <CompanyCom1>cadena</CompanyCom1>
        <ReferenceCom1>cadena</ReferenceCom1>
        <AmountCom1>doble</AmountCom1>
        <BalanceCom1>doble</BalanceCom1>
        <YearsCom1>entero</YearsCom1>
        <MonthsCom1>entero</MonthsCom1>
        <OpCommType2>entero</OpCommType2>
        <CompanyCom2>cadena</CompanyCom2>
        <ReferenceCom2>cadena</ReferenceCom2>
        <AmountCom2>doble</AmountCom2>
        <BalanceCom2>doble</BalanceCom2>
        <YearsCom2>entero</YearsCom2>
        <MonthsCom2>entero</MonthsCom2>
        <OpCommType3>entero</OpCommType3>
        <CompanyCom3>cadena</CompanyCom3>
        <ReferenceCom3>cadena</ReferenceCom3>
        <AmountCom3>doble</AmountCom3>
        <BalanceCom3>doble</BalanceCom3>
        <YearsCom3>entero</YearsCom3>
        <MonthsCom3>entero</MonthsCom3>
        <OpCommType4>entero</OpCommType4>
        <CompanyCom4>cadena</CompanyCom4>
        <ReferenceCom4>cadena</ReferenceCom4>
        <AmountCom4>doble</AmountCom4>
        <BalanceCom4>doble</BalanceCom4>
        <YearsCom4>entero</YearsCom4>
        <MonthsCom4>entero</MonthsCom4>
        <Income0>doble</Income0>
        <Income1>doble</Income1>
        <Income2>doble</Income2>
        <Income3>doble</Income3>
        <Income4>doble</Income4>
        <Income5>doble</Income5>
        <Income6>doble</Income6>
        <Income7>doble</Income7>
        <Income8>doble</Income8>
        <Income9>doble</Income9>
        <Expense0>doble</Expense0>
        <Expense1>doble</Expense1>
        <Expense2>doble</Expense2>
        <Expense3>doble</Expense3>
        <Expense4>doble</Expense4>
        <Expense5>doble</Expense5>
        <Expense6>doble</Expense6>
        <Expense7>doble</Expense7>
        <Expense8>doble</Expense8>
        <Expense9>doble</Expense9>
        <Dato0>cadena</Dato0>
        <Dato1>cadena</Dato1>
        <Dato2>cadena</Dato2>
        <Dato3>cadena</Dato3>
        <Dato4>cadena</Dato4>
        <Dato5>cadena</Dato5>
        <Dato6>cadena</Dato6>
        <Dato7>cadena</Dato7>
        <Dato8>cadena</Dato8>
        <Dato9>cadena</Dato9>
      </xApp>
    </zfpApp>
  </soap:Body>
</soap:Envelope>
XML;

	 return $xml;
 }

?>
