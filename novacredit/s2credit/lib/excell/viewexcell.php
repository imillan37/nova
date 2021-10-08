<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head>
<script type="text/javascript">


<!--

function removeClassName (elem, className) 
{
	elem.className = elem.className.replace(className, "").trim();
}

function addCSSClass (elem, className) 
{
	removeClassName (elem, className);
	elem.className = (elem.className + " " + className).trim();
}

String.prototype.trim = function() 
{
	return this.replace( /^\s+|\s+$/, "" );
}

function stripedTable() 
{
	if (document.getElementById && document.getElementsByTagName) 
	{  
		var allTables = document.getElementsByTagName('table');
		if (!allTables) { return; }

		for (var i = 0; i < allTables.length; i++) 
		{
			if (allTables[i].className.match(/[\w\s ]*scrollTable[\w\s ]*/)) 
			{
				var trs = allTables[i].getElementsByTagName("tr");
				
				for (var j = 0; j < trs.length; j++) 
				{
					removeClassName(trs[j], 'alternateRow');
					addCSSClass(trs[j], 'normalRow');
				}
				
				for (var k = 0; k < trs.length; k += 2) 
				{
					removeClassName(trs[k], 'normalRow');
					addCSSClass(trs[k], 'alternateRow');
				}
			}
		}
	}
}

window.onload = function() { stripedTable(); }
-->
</script>
<style type="text/css">
<!--
/* Terence Ordona, portal[AT]imaputz[DOT]com         */
/* http://creativecommons.org/licenses/by-sa/2.0/    */

/* begin some basic styling here                     */
body 
{
	background: #FFF;
	color: #000;
	font: normal normal 12px Verdana, Geneva, Arial, Helvetica, sans-serif;
	margin: 10px;
	padding: 0
}

table, td, a {
	color: #000;
	font: normal normal 12px Verdana, Geneva, Arial, Helvetica, sans-serif
}

h1 {
	font: normal normal 18px Verdana, Geneva, Arial, Helvetica, sans-serif;
	margin: 0 0 5px 0
}

h2 {
	font: normal normal 16px Verdana, Geneva, Arial, Helvetica, sans-serif;
	margin: 0 0 5px 0
}

h3 {
	font: normal normal 13px Verdana, Geneva, Arial, Helvetica, sans-serif;
	color: #008000;
	margin: 0 0 15px 0
}
/* end basic styling                                 */

/* define height and width of scrollable area. Add 16px to width for scrollbar          */
div.tableContainer {
	clear: both;
	border: 1px solid #963;
	height: 500px;
	overflow: auto;
	width: window.width;
}

/* Reset overflow value to hidden for all non-IE browsers. */
html>body div.tableContainer {
	overflow: hidden;
	width: window.width - 16;
}

/* define width of table. IE browsers only                 */
div.tableContainer table {
	float: left;
	width: window.width;
}

/* define width of table. Add 16px to width for scrollbar.           */
/* All other non-IE browsers.                                        */
html>body div.tableContainer table {
	width: 105%
}

/* set table header to a fixed position. WinIE 6.x only                                       */
/* In WinIE 6.x, any element with a position property set to relative and is a child of       */
/* an element that has an overflow property set, the relative value translates into fixed.    */
/* Ex: parent element DIV with a class of tableContainer has an overflow property set to auto */
/*position: relative; */

thead.fixedHeader tr {
	position: auto
}

/* set THEAD element to have block level attributes. All other non-IE browsers            */
/* this enables overflow to work on TBODY element. All other non-IE, non-Mozilla browsers */
html>body thead.fixedHeader tr {
	display: block
}

/* make the TH elements pretty */
thead.fixedHeader th {
	background: silver;
	font-weight: bold;
	padding: 4px 3px;
	text-align: center
}

/* make the A elements pretty. makes for nice clickable headers                */
thead.fixedHeader a, thead.fixedHeader a:link, thead.fixedHeader a:visited {
	color: #FFF;
	display: block;
	text-decoration: none;
	width: 100%
}

/* make the A elements pretty. makes for nice clickable headers                */
/* WARNING: swapping the background on hover may cause problems in WinIE 6.x   */
thead.fixedHeader a:hover {
	color: #FFF;
	display: block;
	text-decoration: underline;
	width: 100%
}

/* define the table content to be scrollable                                              */
/* set TBODY element to have block level attributes. All other non-IE browsers            */
/* this enables overflow to work on TBODY element. All other non-IE, non-Mozilla browsers */
/* induced side effect is that child TDs no longer accept width: auto                     */
html>body tbody.scrollContent {
	display: block;
	height: 262px;
	overflow: auto;
	width: 100%
}

/* make TD elements pretty. Provide alternating classes for striping the table */
/* http://www.alistapart.com/articles/zebratables/                             */
tbody.scrollContent td, tbody.scrollContent tr.normalRow td {
	background: #FFF;
	border-bottom: none;
	border-left: none;
	border-right: 1px solid #CCC;
	border-top: 1px solid #DDD;
	padding: 2px 3px 3px 4px
}

tbody.scrollContent tr.alternateRow td {
	background: #EEE;
	border-bottom: none;
	border-left: none;
	border-right: 1px solid #CCC;
	border-top: 1px solid #DDD;
	padding: 2px 3px 3px 4px
}

/* define width of TH elements: 1st, 2nd, and 3rd respectively.          */
/* Add 16px to last TH for scrollbar padding. All other non-IE browsers. */
/* http://www.w3.org/TR/REC-CSS2/selector.html#adjacent-selectors        */
html>body thead.fixedHeader th {
	width: 200px
}

html>body thead.fixedHeader th + th {
	width: 240px
}

html>body thead.fixedHeader th + th + th {
	width: 316px
}

/* define width of TD elements: 1st, 2nd, and 3rd respectively.          */
/* All other non-IE browsers.                                            */
/* http://www.w3.org/TR/REC-CSS2/selector.html#adjacent-selectors        */
html>body tbody.scrollContent td {
	width: 200px
}

html>body tbody.scrollContent td + td {
	width: 240px
}

html>body tbody.scrollContent td + td + td {
	width: 300px
}
-->
</style>

<title>Archivo de Excell</TITLE>
</head>
<body onload='this.focus();'>

<?php


if( ! file_exists($xlsfile_name))
{

	echo "<BR><BR><BR><H2 Align='center' style='color: red'> No se encontró el archivo especificado. : (".$xlsfile_name.")</H2>";
	die("</body></html>");
}



require_once  'reader.php';

$data  =  new  Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
$data->setDefaultFormat('%.2f');

$data->read($xlsfile_name);



$cols=  array(" ","&nbsp;","A",     "B",   "C",   "D",    "E",    "F",    "G",    "H",    "I",    "J",    "K",    "L",    "M",    "N",    "O",    "P",    "Q",    "R",    "S",    "T",    "U",    "W",    "X",    "Y",    "Z",  
				"AA",   "AB",   "AC",   "AD",   "AE",   "AF",   "AG",   "AH",   "AI",   "AJ",   "AK",   "AL",   "AM",   "AN",   "AO",   "AP",   "AQ",   "AR",   "AS",   "AT",   "AU",   "AW",   "AX",   "AY",   "AZ", 
				"BA",   "BB",   "BC",   "BD",   "BE",   "BF",   "BG",   "BH",   "BI",   "BJ",   "BK",   "BL",   "BM",   "BN",   "BO",   "BP",   "BQ",   "BR",   "BS",   "BT",   "BU",   "BW",   "BX",   "BY",   "BZ", 
				"CA",   "CB",   "CC",   "CD",   "CE",   "CF",   "CG",   "CH",   "CI",   "CJ",   "CK",   "CL",   "CM",   "CN",   "CO",   "CP",   "CQ",   "CR",   "CS",   "CT",   "CU",   "CW",   "CX",   "CY",   "CZ");




//echo  "<DIV id='tableContainer' class='tableContainer'>\n";
echo  "<TABLE  ALIGN='center'  BORDER=1  CELLSPACING=0  CELLPADDING=0   >\n";
//------------------------------------------------------------------------------
echo  "<thead class='fixedHeader'>\n".
	  " <TR ALIGN='center' BGCOLOR='silver'>\n";
for  ($j  =  1;  $j  <=  $data->sheets[0]['numCols']+1;  $j++)  
{
	echo  "\t\t<TH>".$cols[$j]  ."</TH>\n";
}
echo  " </TR>\n".
	  "</thead>\n";
//------------------------------------------------------------------------------
echo "<tbody class='scrollContent' >\n";
for  ($i  =  1;  $i  <=  $data->sheets[0]['numRows'];  $i++)  
{
	
	

	echo  "<TR>\n\t\t<TH  BGCOLOR='silver'  ALIGN='center'>".$i."</TH>\n";
	for  ($j  =  1;  $j  <=  $data->sheets[0]['numCols'];  $j++)  
	{
		
		$datacell  =  $data->sheets[0]['cells'][$i][$j];
		$datacell  =  (empty($datacell))?("&nbsp;"):($datacell);
		
		$cell_info  =  $data->sheets[0]['cellsInfo'][$i][$j];
		$info ="";
		if(is_array( $cell_info  ))
		{
			$align = " ALIGN='LEFT' ";
			
			//if ( is_numeric($datacell))   $align = " ALIGN='RIGHT' ";
			
			if ($cell_info['type'] == 'date') $align = " ALIGN='CENTER' ";
		
		}
		
		
		echo  "\t\t<TD ".$align.">".$datacell  ." <small>".$info."</small></TD>\n";
	}
	echo  "</TR>\n\n";
	echo  "\n";

}
echo  "</tbody>\n";
echo  "</TABLE>\n";
//echo  "</DIV>\n";

echo  "</body>";
echo  "</html>";

?>
