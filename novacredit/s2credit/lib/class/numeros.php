<?


class Numeros
{

var $nGrupo = 0;
var $nNumero= 0;
var $aGrupos = Array(); //[1..5] of String;
var $cLetras ="";

var $cGenero  = "";

var $cNumStr  = "";
var $cUnidad  = "";
var $cDecena  = "";
var $cCentena = "";

var $Y =                'y ';
var $F =                'as ';
var $M =                'os ';
var $MIL =              'mil ';
var $MILLON =   'millón ';
var $MILLONES = 'millones ';
var $BILLON =   'billón ';
var $BILLONES  = 'billones ';

//Métodos
/*
 function numeros($cantidad,$gen)
 function Letras();
 function aConector(num);
 function aCentena(num);
 function aDecena(num1,num2);
 function aUnidad(num);
*/



//===========================================================================================================//
function Numeros($cantidad,$gen,$unidad) //Creator
{

//debug("cantidad : ".$cantidad);


 
$inter = (int) $cantidad;
$sufix = number_format( $cantidad-$inter ,2);
$sufix *= 100;


if($sufix == 0) $sufix ='00';


$sufix .= "/100 ";
/**/


 if ($gen=='Femenino') 
      $this->cGenero='F';
  else
      $this->cGenero='M';
  
  $this->nNumero=$inter;
  $this->cLetras = $this->Letras();
  $this->cLetras =ucfirst($this->cLetras). $unidad.". "; //. $sufix;
          
      
  
}
//===========================================================================================================//
function Letras()
{

 $I=0; $B=0; $C=0; 

  // Conversión a carácter del número, justificando con 0 a la izquierda
  
  $this->cNumStr = (string) $this->nNumero;
  $B= 15 - strlen($this->cNumStr);

  for ($I=1; $B>=$I; $I++)
  { 
        $this->cNumStr = "0" . $this->cNumStr; 
  }


  // Creación de los Grupos (unidades, decenas ... millones)
  for ($C=1;$C<=5;$C++)
  {
      $this->aGrupos[5 - $C ] =  substr($this->cNumStr, ( $C - 1 )*3, 3);     //Copy(cNumStr, ( $C - 1 ) * 3+1, 3);
  }

  $this->nGrupo=0;
  $cEnLetra = '';
 
 
 for ($C=6;$C >=0;$C--)   // Extraer cada una de las 3 cifras del Grupo en curso
  {
    $this->nGrupo = $C;
    $this->cUnidad  = substr($this->aGrupos[$this->nGrupo],2,1);
    $this->cDecena  = substr($this->aGrupos[$this->nGrupo],1,1);
    $this->cCentena = substr($this->aGrupos[$this->nGrupo],0,1);


                                
                                // Componer la cifra en letra del Grupo en curso


   
    $cEnletra .=   $this->aCentena(  $this->cCentena +1) .    
                                                $this->aDecena( $this->cDecena+1,1)  .
                                                $this->aUnidad( $this->cUnidad +1) .
                                                $this->aConector( $this->nGrupo ) ;
   
   }
        
  
return($cEnletra);
}
//===========================================================================================================//
function  aUnidad($num)
{
        $a_Unidad = "";
        switch($num) 
        {

                  case 1 :  if(( $this->nNumero == 0) and ($this->nGrupo == 0)) { $a_Unidad = 'cero';} else {$a_Unidad= '';} break;
                  case 2 :      
                                                if($this->cDecena>='3')  
                                                { 
                                                        $a_Unidad=' y un ';     
                                                }
                                                else
                                                {
                                                        if( $this->cDecena == '1') 
                                                        {       
                                                                $a_Unidad = $this->aDecena(2, $this->cUnidad + 1);      
                                                        }
                                                        /*else 

                                                        if( ( $this->aGrupos[$this->nGrupo] == "001" ) and (($this->nGrupo == 1) or ($this->nGrupo == 3)) )
                                                        { 
                                                                $a_Unidad= '';
                                                        }
                                                        */
                                                        else if( $this->nGrupo > 0 ) 
                                                        {
                                                                $a_Unidad= 'un ';
                                                        }
                                                        else 
                                                        if( $this->cGenero == 'F' )
                                                        {
                                                                $a_Unidad='una ';
                                                        }
                                                        else 
                                                        {
                                                                $a_Unidad='uno ';
                                                        } 
                                                }
                                                
                                                break;

                  case 3 :  if( $this->cDecena=='1') { $a_Unidad = $this->aDecena(2, $this->cUnidad + 1); } else if( $this->cDecena>='3')  { $a_Unidad='y dos ';        } else  { $a_Unidad='dos ';   } break;
                  case 4 :  if( $this->cDecena=='1') { $a_Unidad = $this->aDecena(2, $this->cUnidad + 1); } else if( $this->cDecena>='3')  { $a_Unidad='y tres ';       } else  { $a_Unidad='tres ';  } break;
                  case 5 :  if( $this->cDecena=='1') { $a_Unidad = $this->aDecena(2, $this->cUnidad + 1); } else if( $this->cDecena>='3')  { $a_Unidad='y cuatro '; } else  { $a_Unidad='cuatro ';} break;
                  case 6 :  if( $this->cDecena=='1') { $a_Unidad = $this->aDecena(2, $this->cUnidad + 1); } else if( $this->cDecena>='3')  { $a_Unidad='y cinco ';      } else  { $a_Unidad='cinco '; } break;
                  case 7 :  if( $this->cDecena=='1') { $a_Unidad = $this->aDecena(2, $this->cUnidad + 1); } else if( $this->cDecena>='3')  { $a_Unidad='y seis ';       } else  { $a_Unidad='seis ';  } break;
                  case 8 :  if( $this->cDecena=='1') { $a_Unidad = $this->aDecena(2, $this->cUnidad + 1); } else if( $this->cDecena>='3')  { $a_Unidad='y siete ';      } else  { $a_Unidad='siete '; } break;
                  case 9 :  if( $this->cDecena=='1') { $a_Unidad = $this->aDecena(2, $this->cUnidad + 1); } else if( $this->cDecena>='3')  { $a_Unidad='y ocho ';       } else  { $a_Unidad='ocho ';  } break;
                  case 10:  if( $this->cDecena=='1') { $a_Unidad = $this->aDecena(2, $this->cUnidad + 1); } else if( $this->cDecena>='3')  { $a_Unidad='y nueve ';      } else  { $a_Unidad='nueve '; } break;





                  default :  $a_Unidad=' ';
         }
return($a_Unidad);
}
//===========================================================================================================//
function aDecena($num1,$num2)
{
  $a_Decena= '';
  $cTerminacion='';

  if( $cUnidad != '0') 
     $cTerminacion = $Y;
  else
     $cTerminacion ='';

  if (($num1==1) and ($num2==1)) { $a_Decena= '';}
  if (($num1==2) and ($num2==1))   if( $this->cUnidad == '0') { $a_Decena = 'diez ';} else {$a_Decena =''; } 
  if (($num1==2) and ($num2==2)) { $a_Decena= 'once ';          }
  if (($num1==2) and ($num2==3)) { $a_Decena= 'doce ';          }
  if (($num1==2) and ($num2==4)) { $a_Decena= 'trece ';         }
  if (($num1==2) and ($num2==5)) { $a_Decena= 'catorce ';       }
  if (($num1==2) and ($num2==6)) { $a_Decena= 'quince ';        }
  if (($num1==2) and ($num2==7)) { $a_Decena= 'dieciseis ';     }
  if (($num1==2) and ($num2==8)) { $a_Decena= 'diecisiete ';}
  if (($num1==2) and ($num2==9)) { $a_Decena= 'dieciocho ';     }
  if (($num1==2) and ($num2==10)){ $a_Decena= 'diecinueve ';}
  if (($num1==3) and ($num2==1))   if( $this->cUnidad== '0') { $a_Decena='veinte '; } else {$a_Decena='veinti' ;} 
  if (($num1==4) and ($num2==1)) { $a_Decena= 'treinta '  . $cTerminacion; }
  if (($num1==5) and ($num2==1)) { $a_Decena= 'cuarenta ' . $cTerminacion; }
  if (($num1==6) and ($num2==1)) { $a_Decena= 'cincuenta '. $cTerminacion; }
  if (($num1==7) and ($num2==1)) { $a_Decena= 'sesenta '  . $cTerminacion; }
  if (($num1==8) and ($num2==1)) { $a_Decena= 'setenta '  . $cTerminacion; }
  if (($num1==9) and ($num2==1)) { $a_Decena= 'ochenta '  . $cTerminacion; }
  if (($num1==10) and ($num2==1)){ $a_Decena= 'noventa '  . $cTerminacion; }

return($a_Decena);
}
//===========================================================================================================//
function aCentena($num)
{
  $cTerminacion ="";

  if(($this->nGrupo < 3) and ($this->cGenero == 'F')) 
     $cTerminacion = $this->F;
   else
     $cTerminacion = $this->M ;

         switch($num)
         {
                case 1 :  $aCentena= ''; break;
                case 2 :        if( $this->cDecena . $this->cUnidad == '00') { $aCentena='cien ';} else {$aCentena= 'ciento ';}  break;
                case 3 :  $aCentena= 'doscient' . $cTerminacion;  break;
                case 4 :  $aCentena= 'trescient' .$cTerminacion;  break;
                case 5 :  $aCentena= 'cuatrocient'.$cTerminacion;  break;
                case 6 :  $aCentena= 'quinient'  .$cTerminacion; break;
                case 7 :  $aCentena= 'seiscient' .$cTerminacion; break;
                case 8 :  $aCentena= 'setecient' .$cTerminacion; break;
                case 9 :  $aCentena= 'ochocient' .$cTerminacion; break;
                case 10 : $aCentena= 'novecient' .$cTerminacion; break;
                default : $aCentena= '';
        }
 return($aCentena);
}
//===========================================================================================================//
function aConector($num)
{

         switch($num)
         {
                case 0 : $aConector= ''; break;
                
                case 1 :  if( $this->aGrupos[1] > '000') {$aConector = $this->MIL;} else {$aConector= '';} break;
                
                case 2 : if(( $this->aGrupos[2] > '000') or  ($this->aGrupos[3] > '000' ))
                                 {
                                        if( $this->aGrupos[2] == '001')  
                                          { $aConector= $this->MILLON;} 
                                    else
                                          { $aConector=$this->MILLONES; }
                                 }
                                 else 
                                 {
                                        $aConector='';
                                 }
                                 break;
                                                
                case 3 : if( $this->aGrupos[3] > '000') { $aConector= $this->MIL;} else {$aConector= ''; } break;
                
                case 4 : if( $this->aGrupos[4] > '000') 
                                   if( $this->aGrupos[4] == '001')  { $aConector =  $this->BILLON;}  else {$aConector = $this->BILLONES;}
                                   else $this->aConector=  ''; break;
                
                default :  $aConector=  ''; break;
        }
return($aConector);
}

function debug()
{
echo "class Numeros() : <DIR><BR>
{
                var nGrupo  = ".   $this->nGrupo   ."<BR>
                var nNumero = ".   $this->nNumero  ."<BR>
                var aGrupos = ".   $this->aGrupos  ."<BR>";
                 
                 for($i=0;$i<count($this->aGrupos);$i++)
                 {
                        echo "$sps var aGrupos[$i] = ".$this->aGrupos[$i]."<BR>";
                 }
                
  echo "var cLetras =   ".   $this->cLetras  ."<BR>
                var cGenero  =  ". $this->cGenero  ."<BR>
                var cEnLetra =  ".  $this->cEnLetra ."<BR>
                var cNumStr  =  ". $this->cNumStr  ."<BR>
                var cUnidad  =  ". $this->cUnidad  ."<BR>
                var cDecena  =  ". $this->cDecena  ."<BR>
                var cCentena =  ". $this->cCentena ."<BR><BR>

                var Y =                 ". $this->Y ."<BR>              
                var F =                 ". $this->F ."<BR>              
                var M =                 ". $this->M ."<BR>              
                var MIL =               ". $this->MIL ."<BR>
                var MILLON =    ". $this->MILLON ."<BR>
                var MILLONES =  ". $this->MILLONES ."<BR>
                var BILLON =    ". $this->BILLON ."<BR>
                var BILLONES  = ". $this->BILLONES ."<BR></DIR>}<BR>";


}

//===========================================================================================================//

}//EndClass
