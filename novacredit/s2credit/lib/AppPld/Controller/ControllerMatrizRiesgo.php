<?php

/**
 *
 * @author MarsVoltoso (CFA)
 * @category Contoller
 * @created Mon Sep 15, 2014
 * @version 1.0
 */	
 
 
/**
 *
 *  @ Cargamos Vista  
 */ 

 
class ControllerMatrizRiesgo {
 
 	public $db;
 	 
 		public function __construct($db){
	 		$this->db = $db; 
	 	}
 		

 		public function fundispach($BuscaPuesto){
 			$sql = " UPDATE pld_matriz_riesgo_clasificacion
                 SET    pld_matriz_riesgo_clasificacion.ID_Matriz_Riesgo_Clasificacion = pld_matriz_riesgo_clasificacion.ID_Matriz_Riesgo_Clasificacion + 1 ";
			$this->db->Execute($sql);
			 //echo "holaaa". $this->db->Insert_ID();
	
			$sql = " REPLACE INTO pld_matriz_riesgo_clasificacion
						(ID_Matriz_Riesgo_Clasificacion, ID_Usuario, Puntos_Minimos)
					VALUES 
						(1, '".$_SESSION['ID_USR']."', '".$puntos_minimos."') ";
			

	
        	if( count($riesgo) > 0)
        	{
				foreach($riesgo AS $key => $_id_riesgo)
				{

					$_ponderacion = $ponderacion[$key];

					$sql ="	UPDATE  pld_matriz_riesgo
						SET 	Ponderacion = '".($_ponderacion)."' 
						WHERE 	ID_Riesgo   = '".($_id_riesgo   )."' ";
	
					$this->db->Execute($sql);

				}// fin foreach
			} //fin if
	 	} // fin funcion	



	 	public function puntosminimos(){
	 		
	 		$noheader=1;
	 		
	 		$sql ="	SELECT IFNULL(pld_matriz_riesgo_clasificacion.Puntos_Minimos,0) AS Puntos_Minimos
					FROM   pld_matriz_riesgo_clasificacion
					WHERE  pld_matriz_riesgo_clasificacion.ID_Matriz_Riesgo_Clasificacion = 1 ";
			$rs = $this->db->Execute($sql);
 
				$puntos_minimos = $rs->fields['Puntos_Minimos'];

			echo $puntos_minimos;

			die();

	 	}

	 public function  elementoseva_determinantes(){

	 	$noheader=1;

	 	$sql ="	SELECT  ID_Riesgo,   
                Descripcion,  
                Ponderacion         
        		FROM pld_matriz_riesgo         
       			WHERE pld_matriz_riesgo.Determinante = 'Y'        
        		ORDER BY ID_Riesgo ";

 		$rs=$this->db->Execute($sql);

		$i=0;
		if($rs->_numOfRows)
   			while(! $rs->EOF)
  			 {
   
       			$color=($color=='white')?('#E6E6FA'):('white');
        		$html .= "<TR  onmouseover=\"this.style.cursor='pointer'; \" onmouseout=\"javascript:  this.style.backgroundColor='' \" class='positive' > \n" ;
		  		$html .= "<td ALIGN='center'>".(++$i).") </td> \n";
                $html .= "<td ALIGN='left' NOWRAP>&nbsp;".mb_strtoupper($rs->fields['Descripcion'])."&nbsp;</td>\n";
		        $html .= "</TR>\n";
        
	   		 $rs->MoveNext();
	   		}
			$html .= "<TR ALIGN='center' BGCOLOR=''   STYLE='font-size: 14px; font-family:tahoma; color:white;'  NOWRAP>\n";
	   		$html .= "<TH></TH>\n";
           	$html .= "<TH ALIGN='left' >    </TH>\n";
			$html .= "</TR>\n";
   		
   		echo $html;
	 }


	 public function  elementoseva(){

	 	$noheader=1;

	 	$sql ="	SELECT  ID_Riesgo,   
                Descripcion,  
                Ponderacion          
        FROM pld_matriz_riesgo         
        WHERE pld_matriz_riesgo.Determinante != 'Y'        
        ORDER BY ID_Riesgo ";

 		$rs=$this->db->Execute($sql);
		$i=0;
		$_ponderacion = 0;

		if($rs->_numOfRows)
   			while(! $rs->EOF)
   			{	

       			$color=($color=='white')?('lavender'):('white');
        		$html .= "<TR  onmouseover=\"this.style.cursor='pointer'; \" onmouseout=\"javascript:  this.style.backgroundColor='' \" class='positive'  > \n" ;
		   		$html .= "<td ALIGN='center'>".(++$i).") </td> \n";		   
                $html .= "<td ALIGN='left' NOWRAP>&nbsp;".mb_strtoupper($rs->fields['Descripcion'], 'UTF-8')."&nbsp;</td>\n";                   
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
   		
   		echo $html;
	 }

}//fin clase
?>