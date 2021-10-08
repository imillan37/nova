<?php

/**
 * classSoundex
 *
 * @author   Enrique Godoy <egodoyc@s2credit.com>
 */


class classSoundex 
{


    private  $phrase; 
    private  $lang; 
    private  $fmap;

    public  $error = 0;
    public  $error_msg = array();       

//-----------------------------------------------------------
public function __construct($lang='es')
{
    $this->lang = $lang;
    $this->getMap();

}
//-----------------------------------------------------------

public function get_soundex($phrase)
{
    $this->phrase = $this->strip($phrase) ;    
    $this->debug = false;
    
    
      
      
    $i = strlen($this->phrase); 
    $char     = "";
    $token    = "";
    $chunck2  = "";    
    $chunck3  = "";        
    $soundex   = "";
    
    for($j=0; $j<$i; $j++)
    {
        

        $char = $this->phrase[$j];
        
        if($j+2<=$i){
          $chunck2 = substr($this->phrase,$j,2); 
        } else {
          $chunck2 = $char;        
        }
        
        if($j+3<=$i){        
           $chunck3 = substr($this->phrase,$j,3);
        }  else {
           $chunck3 = $char;           
        }
        
        if($char == " "){
           $chunck2 = $char;        
           $chunck3 = $char;         
        }
        
        //echo "<pre>[".$char."]->[".$chunck2."]-[".$chunck3."]</pre><br>";  


        if((trim(strlen($chunck3)) == 3) and (!empty($this->fmap->map[$chunck3]))){
        
              if($this->debug)
                $soundex.="[".$this->fmap->map[$chunck3]."]";
              else
                $soundex.= $this->fmap->map[$chunck3];
              
              $j+=2;
         
        
        } else  if((trim(strlen($chunck2)) == 2) and (!empty($this->fmap->map[$chunck2]))){
        
        
              if($this->debug)
                $soundex.="[".$this->fmap->map[$chunck2]."]";
              else
                $soundex.= $this->fmap->map[$chunck2];
              $j++;
                 
        
        } else {
        
             if($this->debug)
              $soundex.="[".$char."]"; 
             else
              $soundex.= $char;              
        }

        
        
    }
    
    
    $soundex = $this->postprocesamiento($soundex);
    return($soundex);   

}
//-----------------------------------------------------------

public function compare_soundex($phrase1, $phrase2)
{

        $soundex1 = $this->get_soundex($phrase1);
        $soundex2 = $this->get_soundex($phrase2);  
        
        //echo "<pre>[".$soundex1."] = [".$soundex2."]</pre>";
        
        return($soundex1 == $soundex2);
        

}
//-----------------------------------------------------------

private function strip($phrase)
{     

    // Acetos letaras Ñ y caracteres especiales
    $phrase = str_replace(array('á','à','â','ã','ª','ä'),"a",$phrase);
    $phrase = str_replace(array('Á','À','Â','Ã','Ä'),"A",$phrase);
    $phrase = str_replace(array('Í','Ì','Î','Ï'),"I",$phrase);
    $phrase = str_ireplace(array('í','ì','î','ï'),"i",$phrase);
    $phrase = str_replace(array('é','è','ê','ë'),"e",$phrase);
    $phrase = str_replace(array('É','È','Ê','Ë'),"E",$phrase);
    $phrase = str_replace(array('ó','ò','ô','õ','ö','º'),"o",$phrase);
    $phrase = str_replace(array('Ó','Ò','Ô','Õ','Ö'),"O",$phrase);
    $phrase = str_replace(array('ú','ù','û','ü'),"u",$phrase);
    $phrase = str_replace(array('Ú','Ù','Û','Ü'),"U",$phrase);
    $phrase = str_replace(array("^","+","-","_",",",";",".","#","|","'","$","%","&","(",")","[","]","*","´","`","^","¨","~",'"',"/","°","?","¿","!","¡","'","•"),"",$phrase);
    $phrase = str_replace("ñ","n",$phrase);
    $phrase = str_replace("Ñ","N",$phrase);
    $phrase = str_replace("Ý","Y",$phrase);
    $phrase = str_replace("ý","y",$phrase);
    $phrase = str_replace("ç","c",$phrase);
    $phrase = str_replace("Ç","C",$phrase);
    

    // Números
    $phrase = str_replace(array("0","1","2","3","4","5","6","7","8","9"),"",$phrase);


    // Códificaciones HTML    
    $phrase = str_replace("&aacute;","a",$phrase);
    $phrase = str_replace("&Aacute;","A",$phrase);
    $phrase = str_replace("&eacute;","e",$phrase);
    $phrase = str_replace("&Eacute;","E",$phrase);
    $phrase = str_replace("&iacute;","i",$phrase);
    $phrase = str_replace("&Iacute;","I",$phrase);
    $phrase = str_replace("&oacute;","o",$phrase);
    $phrase = str_replace("&Oacute;","O",$phrase);
    $phrase = str_replace("&uacute;","u",$phrase);
    $phrase = str_replace("&Uacute;","U",$phrase);
    
    //Limpieza de extremos y todo a mayusculas
    $phrase = trim(strtoupper($phrase));    
    
    $phrase = $this->preprocesamiento($phrase);   
    
    
    $i = strlen($phrase); 
    $char = "";
    $aux  = "";
    for($j=0; $j<$i; $j++)
    {
      
        //Remover caracteres repetidos menos L por el fonéma LL
        if(($phrase[$j] != $char) or ($char=="L")){        
                //Remover letas "H" que no sean antecedidas por "C" o por "P"
                if($phrase[$j] == "H"){ 
                        if(($char=="C") or ($char=="P")) {
                            $aux.= $phrase[$j];  
                         
                        } 
                } else {
                
                $aux.= $phrase[$j];               
                
                }
        } 

                
        $char = $phrase[$j];        
    }
    
    
     $phrase = $aux;
     
     //Excepciones
    

    return $phrase;
    
}
//-----------------------------------------------------------

private function preprocesamiento($phrase)
{


    


     $phrase = str_replace("Z","S",$phrase);
     $phrase = str_replace("V","B",$phrase);
     $phrase = str_replace("LL","Y",$phrase);

     $phrase = str_replace("CI","SI",$phrase);
     $phrase = str_replace("CE","SE",$phrase);

     $phrase = str_replace("GE","JE",$phrase);
     $phrase = str_replace("GI","JI",$phrase);     
     
     $phrase = str_replace("HUE","WE",$phrase);    
     $phrase = str_replace("HUI","WI",$phrase);     

     $phrase = str_replace("QUE","KE",$phrase);
     $phrase = str_replace("QUI","KI",$phrase);    

     $phrase = str_replace("XOCH","SOCH",$phrase);
     $phrase = str_replace("HERMAN","JERMAN",$phrase);


 
     $phrase = str_replace("MOHAMMED","MOHAMED",$phrase);
     $phrase = str_replace("MUHAMMED","MOHAMED",$phrase);
     $phrase = str_replace("MUHAMED", "MOHAMED",$phrase);
     $phrase = str_replace("MUHAMMAD","MOHAMED",$phrase);
     $phrase = str_replace("MUHAMAD", "MOHAMED",$phrase);
     $phrase = str_replace("MOHAMAD", "MOHAMED",$phrase);
     $phrase = str_replace("MOHAMMAD","MOHAMED",$phrase);

     $phrase = str_replace("MOJAMED", "MOHAMED",$phrase);
     $phrase = str_replace("MOJAMMED","MOHAMED",$phrase);
     $phrase = str_replace("MUJAMMED","MOHAMED",$phrase);
     $phrase = str_replace("MUJAMED", "MOHAMED",$phrase);
     $phrase = str_replace("MUJAMMAD","MOHAMED",$phrase);
     $phrase = str_replace("MUJAMAD", "MOHAMED",$phrase);
     $phrase = str_replace("MOJAMAD", "MOHAMED",$phrase);
     $phrase = str_replace("MOJAMMAD","MOHAMED",$phrase);








   $i = strlen($phrase);     
   $char = $phrase[$i-1];
   if($char=="Y") $phrase[$i-1]="I";



 return $phrase;

}



private function postprocesamiento($phrase)
{
     $phrase = str_replace("C","K",$phrase);
     $phrase = str_replace("Q","K",$phrase);
     $phrase = str_replace("B","V",$phrase);
     $phrase = str_replace("G","W",$phrase);     
     $phrase = str_replace("Z","S",$phrase);     


     $phrase = $this->eliminar_caracteres_repetidos($phrase);


     
      return $phrase;
}


private function eliminar_caracteres_repetidos($phrase)
{

      $i = strlen($phrase);


      $aux  = "";
      $out  = "";
      $lastchar = "";
      $input = strrev($phrase);

      for($j=0; $j<$i; $j++)
      {        
              $aux = $input[$j];        
              if($aux != $lastchar){
                 $out.=$aux;
              }        
              $lastchar = $aux;
      }

      $phrase = strrev($out); 

      return $phrase;
}




private function getMap()
{
    $this->fmap = new classSoundexMap($this->lang);
}
//-----------------------------------------------------------


}

//===========================================================================================
//      Mapa de fonogramas
//===========================================================================================


class classSoundexMap
{

    public  $num_keys; 
    public  $map; 
    public  $error;
    public  $error_msg;    
//-----------------------------------------------------------

public function __construct($lang)
{
       
       $this->error = 0;
       $this->error_msg= array(); 
       $this->map = array(); 
       
       switch($lang) 
       {
                case 'es' : $this->load_map_es();
       }
       
       
       $this->num_keys = count($this->map);
       
       if($this->num_keys == 0){
          $this->error++;
          $this->error_msg[] = "El mapa de fonético no pudo ser cargado";
       }
       
       
       
}
//-----------------------------------------------------------

function load_map_es()
{

$this->map = array(); 

// fonema => mapeo

//---------------
// B-V


     $this->map['BA']='BA';    
     $this->map['AB']='AB';    
     $this->map['BE']='BE';    
     $this->map['EB']='EB';    
     $this->map['BI']='BI';    
     $this->map['IB']='IB';    
     $this->map['BO']='BO';    
     $this->map['OB']='OB';    
     $this->map['BU']='BU';    
     $this->map['UB']='UB';    


     $this->map['VA']='BA';
     $this->map['AV']='AB';
     $this->map['VE']='BE';
     $this->map['EV']='EB';
     $this->map['VI']='BI';     $this->map['BY']='BI';  $this->map['VY']='BI';
     $this->map['IV']='IB';
     $this->map['VO']='BO';
     $this->map['OV']='OB';
     $this->map['VU']='BU';
     $this->map['UV']='UB';

//---------------
// K-C-Q

     $this->map['KA']='KA';
     $this->map['AK']='AK';
     $this->map['KE']='KE';
     $this->map['EK']='EK';
     $this->map['KI']='KI';     $this->map['KY']='KI';
     $this->map['IK']='IK';
     $this->map['KO']='KO';
     $this->map['OK']='OK';
     $this->map['KU']='KU';
     $this->map['UK']='UK';                  


     $this->map['CA']='KA';     
     $this->map['AC']='AK';         
     $this->map['EC']='EK';         
     $this->map['IC']='IK';     
     $this->map['CO']='KO';     
     $this->map['OC']='OK';     
     $this->map['CU']='KU';     
     $this->map['UC']='UK';       


     $this->map['QA']='KA';                      
     $this->map['AQ']='AK';                      
     $this->map['QE']='KE';      $this->map['QUE']='KE';      $this->map['QÜE']='KE';     
     $this->map['EQ']='EK';                      
     $this->map['QI']='KI';      $this->map['QUI']='KI';      $this->map['QÜI']='KI'; 
     $this->map['IQ']='IK';                      
     $this->map['QO']='KO';                      
     $this->map['OQ']='OK'; 
     $this->map['UQ']='UK';                      

//---------------
// F-PH


     $this->map['FA']='FA';
     $this->map['AF']='AF';
     $this->map['FE']='FE';
     $this->map['EF']='EF';
     $this->map['FI']='FI';
     $this->map['IF']='IF';
     $this->map['FO']='FO';
     $this->map['OF']='OF';
     $this->map['FU']='FU';
     $this->map['UF']='UF';

     $this->map['PHA']='FA';     
     $this->map['APH']='AF';     
     $this->map['PHE']='FE';     
     $this->map['EPH']='EF';     
     $this->map['PHI']='FI';     $this->map['FY']='FI';
     $this->map['IPH']='IF';     
     $this->map['PHO']='FO';     
     $this->map['OPH']='OF';     
     $this->map['PHU']='FU';     
     $this->map['UPH']='UF';     


//---------------
// H-J-X-G

/*
     $this->map['HA']='HA';
     $this->map['AH']='AH';
     $this->map['HE']='HE';
     $this->map['EH']='EH';
     $this->map['HI']='HI';
     $this->map['IH']='IH';
     $this->map['HO']='HO';
     $this->map['OH']='OH';
     $this->map['HU']='HU';
     $this->map['UH']='UH';
*/

     $this->map['JA']='JA';     
     $this->map['AJ']='AJ';     
     $this->map['JE']='JE';     
     $this->map['EJ']='EJ';     
     $this->map['JI']='JI';     
     $this->map['IJ']='IJ';     
     $this->map['JO']='JO';     
     $this->map['OJ']='OJ';     
     $this->map['JU']='JU';     
     $this->map['UJ']='UJ';     


     $this->map['XA']='JA';                              
     $this->map['AX']='AJ';                              
     $this->map['XE']='JE';      $this->map['GE']='JE';                      
     $this->map['EX']='EJ';                              
     $this->map['XI']='JI';      $this->map['GI']='JI';      $this->map['GY']='JI';       $this->map['JY']='JI';       $this->map['XY']='JI'; 
     $this->map['IX']='IJ';                              
     $this->map['XO']='JO';                              
     $this->map['OX']='OJ';                              
     $this->map['XU']='JU';                              
     $this->map['UX']='UJ';                              


//---------------
// Y-LL


     $this->map['YA']='YA'; 
     $this->map['AY']='AY'; 
     $this->map['YE']='YE'; 
     $this->map['EY']='EY'; 
     $this->map['YI']='YI'; 
     $this->map['IY']='IY'; 
     $this->map['YO']='YO'; 
     $this->map['OY']='OY'; 
     $this->map['YU']='YU'; 
     $this->map['UY']='UY'; 


     $this->map['LLA']='YA';     
     $this->map['ALL']='AY';     
     $this->map['LLE']='YE';     
     $this->map['ELL']='EY';     
     $this->map['LLI']='YI';     $this->map['LLY']='YI';
     $this->map['ILL']='IY';     
     $this->map['LLO']='YO';     
     $this->map['OLL']='OY';     
     $this->map['LLU']='YU';     
     $this->map['ULL']='UY';     

//---------------
// S-Z-C

     $this->map['SA']='SA';
     $this->map['AS']='AS';
     $this->map['SE']='SE';
     $this->map['ES']='ES';
     $this->map['SI']='SI';
     $this->map['IS']='IS';
     $this->map['SO']='SO';
     $this->map['OS']='OS';
     $this->map['SU']='SU';
     $this->map['US']='US';


     $this->map['ZA']='SA';                      
     $this->map['AZ']='AS';                      
     $this->map['ZE']='SE';      $this->map['CE']='SE';              
     $this->map['EZ']='ES';                      
     $this->map['ZI']='SI';      $this->map['CI']='SI';        $this->map['SY']='SI';        $this->map['ZY']='SI';        
     $this->map['IZ']='IS';                      
     $this->map['ZO']='SO';                      
     $this->map['OZ']='OS';                      
     $this->map['ZU']='SU';                      
     $this->map['UZ']='US';                      


//---------------
// W-G
                                
     $this->map['WA']='WA';      
     $this->map['AW']='AW';      
     $this->map['WE']='WE';      
     $this->map['EW']='EW';      
     $this->map['WI']='WI';     $this->map['WY']='WI';  
     $this->map['IW']='IW';      
     $this->map['WO']='WO';      
     $this->map['OW']='OW';      
     $this->map['WU']='WU';      
     $this->map['UW']='UW';      


     $this->map['GA'] ='WA';    $this->map['GUA']='WA';             
     $this->map['AG'] ='AW';                      
     $this->map['GUE']='WE';    $this->map['GÜE']='WE';     
     $this->map['EG'] ='EW';                      
     $this->map['GUI']='WI';    $this->map['GÜI']='WI'; $this->map['GÜY']='WI'; $this->map['GUI']='WI'; $this->map['GUY']='WI'; 
     $this->map['IG'] ='IW';                      
     $this->map['GO'] ='WO';    $this->map['GUO']='WO';
     $this->map['OG'] ='OW';                      
     $this->map['GU'] ='WU';                      
     $this->map['UG'] ='UW';                      


//---------------
// I-Y                       
     $this->map['DI']  ='DI'; 
     $this->map['CHI'] ='CHI';
     $this->map['HI'] = 'HI';     
     $this->map['LI']  ='LI'; 
     $this->map['MI']  ='MI';                         
     $this->map['NI']  ='NI'; 
     $this->map['PI']  ='PI'; 
     $this->map['RI']  ='RI';      
     $this->map['TI']  ='TI'; 
                        
                        
     $this->map['DY']  ='DI';              
     $this->map['CHY'] ='CHI';
     $this->map['HY'] = 'HI';     
     $this->map['LY']  ='LI';              
     $this->map['MY']  ='MI';                                                    
     $this->map['NY']  ='NI';      
     $this->map['PY']  ='PI';    
     $this->map['RY']  ='RI';       
     $this->map['TY']  ='TI';              


//---------------
// I-Y                       
     $this->map['AI']  ='AI'; 
     $this->map['EI']  ='EI';
     $this->map['OI']  ='OI';                         
     $this->map['UI']  ='UI'; 

     $this->map['AY']  ='AI'; 
     $this->map['EY']  ='EI';
     $this->map['OY']  ='OI';                         
     $this->map['UY']  ='UI'; 
  
}
//-----------------------------------------------------------


}


