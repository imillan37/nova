<?php
	
/**
 *
 * @author MarsVoltoso (CFA)
 * @category Views
 * @created Mon Sep 15, 2014
 * @version 1.0
 */	


include($DOCUMENT_ROOT."/rutas.php");

$db = &ADONewConnection(SERVIDOR);
$db->PConnect(IP,USER,PASSWORD,NUCLEO);


if($_POST['dispach'] == 1)
{

	$QueryPM = "SELECT puntos_minimos from pld_matriz_riesgo_clasificacion where pld_matriz_riesgo_clasificacion.ID_Matriz_Riesgo_Clasificacion = 1";
	$pmm = $db->Execute($QueryPM);
	$pmv = $pmm->fields["puntos_minimos"];


	$sql = " UPDATE pld_matriz_riesgo_clasificacion
                 SET    pld_matriz_riesgo_clasificacion.ID_Matriz_Riesgo_Clasificacion = pld_matriz_riesgo_clasificacion.ID_Matriz_Riesgo_Clasificacion + 1 ";
	$db->Execute($sql);
	

	$sql = " REPLACE INTO pld_matriz_riesgo_clasificacion
			(ID_Matriz_Riesgo_Clasificacion, ID_Usuario, Puntos_Minimos)
		VALUES 
			(1, '".$_SESSION['ID_USR']."', '".$puntos_minimos."') ";
	$db->Execute($sql); //debug($sql);
	
	$Identificador = $db->Insert_ID();
	

	$Query = "INSERT INTO pld_matriz_riesgo_dtl (Identificador,ID_Riesgo,Determinante,Campo_Relacionado,Descripcion,Ponderacion,puntos_minimos)
	(SELECT '".$Identificador."',ID_Riesgo,Determinante,Campo_Relacionado,Descripcion,Ponderacion,'".$pmv."' FROM pld_matriz_riesgo) ";
	
	$db->Execute($Query);

     	
	
        if( count($riesgo) > 0)
        {
		foreach($riesgo AS $key => $_id_riesgo)
		{

			$_ponderacion = $ponderacion[$key];

			$sql ="	UPDATE  pld_matriz_riesgo
				SET 	Ponderacion = '".($_ponderacion)."' 
				WHERE 	ID_Riesgo   = '".($_id_riesgo   )."' ";

			$db->Execute($sql); //debug($sql);

		}
	}

}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sql ="	SELECT  ID_Riesgo,   
                Descripcion,  
                Ponderacion          
        FROM pld_matriz_riesgo         
        WHERE pld_matriz_riesgo.Determinante != 'Y'        
        ORDER BY ID_Riesgo ";

 		$rs=$db->Execute($sql);
		$i=0;
		$_ponderacion = 0;

		if($rs->_numOfRows)
   			while(! $rs->EOF)
   			{	

       			$color=($color=='white')?('lavender'):('white');
        		$html .= "<TR  onmouseover=\"this.style.cursor='pointer'; \" onmouseout=\"javascript:  this.style.backgroundColor='' \" class='positive'  > \n" ;
		   		$html .= "<td ALIGN='center'>".(++$i).") </td> \n";		   
                $html .= "<td ALIGN='left' NOWRAP>&nbsp;".mb_strtoupper($rs->fields['Descripcion'])."&nbsp;</td>\n";                   
                $html .= "<td ALIGN='right'><INPUT TYPE='TEXT' NAME='ponderacion[]'  VALUE='".($rs->fields['Ponderacion'])."' STYLE='text-align:right; width:100%; border: none;'    onblur='compara_maximos(this);     actualiza_sumas();'    onkeypress='return SoloEnteros(event);' AUTOCOMPLETE=OFF></td> \n";
				  $_ponderacion += $rs->fields['Ponderacion'];
    		    $html .= "</TR>\n";        
        		$html .= "<INPUT TYPE='HIDDEN' NAME='riesgo[]' VALUE='".($rs->fields['ID_Riesgo'])."' > \n";

     		$rs->MoveNext();
   			}
			$html .= "<TR ALIGN='center' BGCOLOR='steelblue'  STYLE='font-size: 14px; font-family:tahoma; color:white;'  NOWRAP>\n";
	   		$html .= "<TH>".($i)."</TH>\n";
	        $html .= "<TH ALIGN='left' >  Total de elementos de evaluaci&oacute;n   </TH>\n";
            $html .= "<TH ALIGN='right' ID='TotalPonderacion'>".number_format($_ponderacion ,0)."</TH> \n";

	//verflujo();
		
?>

<script src="../Site_media/Jquery/jquery-2.1.1.min.js"></script>
<script src="../Site_media/Jquery/jquery.form.js"></script>
<script src="../Site_media/packaged/javascript/semantic.js"></script>
<script src="../Site_media/Jquery/jquery.address.js"></script>
<script src="../JavaScipt/coreFunction.js"></script>
<script src="../JavaScipt/ViewsContenedorMatrizRiesgo.js"></script>


<link rel="stylesheet" type="text/css" class="ui" href="../Site_media/packaged/css/semantic.min.css">



<div class="ui ignored info message"><h2>Matriz de Riesgo  </h2></div>


  <div class="ui bottom attached blue segment active tab segment" data-tab="standard">
	
			<h4 align="center"> Establecer los valores de la Matriz de Riesgo para </h4>
			<h4 align="center"><b><u> Prevención de Lavado de Dinero. </u></b></h4>
	
			<br><br>
			<FORM NAME='matriz' METHOD='POST' ACTION='ViewsContenedorMatrizRiesgo.php'>
			<table BORDER='0' style="color:#fff;" ALIGN='center' CELLSPACING=1 CELLPADDING=3  ID='small' class="ui table segment" width='60%'>
				<tr ALIGN='center' class="positive" >
           			<td ALIGN='left' nowrap> &nbsp;&nbsp;  <? echo mb_strtoupper("Valor mínimo ponderado para considerar un cliente como ALTO RIESGO"); ?> </td>
           			<td ALIGN='right' BGCOLOR='white' >
           				<INPUT TYPE='TEXT' NAME='puntos_minimos' id="puntos_minimos"  onkeypress="return SoloEnteros(event);" VALUE=''  STYLE='text-align:right; width:100%; border: none; background-color:white;'  >
           			</td> 
				</tr>

			</table>
	</div>

		<br>

	
  <div class="ui bottom attached blue segment active tab segment" data-tab="standard">
		<TABLE BORDER='0' ALIGN='left' CELLSPACING=1 CELLPADDING=3  ID='small'  width='100%'>
			<TR ALIGN='center' STYLE='font-size: 14px; font-family:tahoma; color:white;' >
  				<TH >    </TH>
				<!--<Td><h4 align="center"> Elementos de evaluación de riesgo determinantes </h4> </Td>-->
			</TR>
		</TABLE>
			
		<TABLE BORDER='0' BGCOLOR='black' ALIGN='center' CELLSPACING=1 CELLPADDING=3 class ="ui table segment" ID='small' width='50%'>
			<TR   ALIGN='center' STYLE='font-size: 14px; font-family:tahoma; color:white;' id="tabla_elementos_eva">
          		
            </TR>

           
		</TABLE>
</div>

		<br>

  <div class="ui bottom attached blue segment active tab segment" data-tab="standard">
  	
		<TABLE BORDER='0' ALIGN='left' CELLSPACING=1 CELLPADDING=3  ID='small'  width='100%'>
			<TR ALIGN='center' STYLE='font-size: 14px; font-family:tahoma; color:white;' >
  				<TH width="40%">    </TH>
				<Td width="40%"><h4 align='left'><!--Elementos de evaluación de riesgo--></h4> </Td>
				<Td><h4 align='left'>Ponderación </h4>	      </Td>
			</TR>
		</TABLE>
		<TABLE BORDER='0' BGCOLOR='black' ALIGN='center' CELLSPACING=1 CELLPADDING=3  ID='small' class="ui table segment" width='50%'>
			<TR ALIGN='center' STYLE='font-size: 14px; font-family:tahoma; color:white;'  > <!--  id="tabla_elementos_eva2" -->
  			<?php echo $html; ?>
			</TR>
		</TABLE>
		
<CENTER>

<INPUT TYPE='hidden' NAME='dispach'  >
<INPUT TYPE='BUTTON' NAME='makesubmit' id='makesubmit' VALUE='Actualizar Valores' STYLE='width:300px' class="ui mini blue button">
</CENTER>
</div> <!-- fin matriz -->
</div>


<div class="ui mini modal" id="decide">
	<div class="content">
    	<div>
			<b>Está Ud por cambiar la ponderación de la matriz de riesgo, ¿Desea continiar?</b>
		</div>
	</div>
	  <div class="actions">
	    <div class="ui mini buttons">
			<div class="ui button Manual" id="Aceptar">SI</div>
			<div class="or"></div>
			<div class="ui button Archivo" id="Cancelar">NO</div>
		</div>
	  </div>
</div>
<br><br>

