<?

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
// Clase Madre de los componentes de la forma THTMLForm. No se deben generar instancias 
// directas de esta clase !!!
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
class TInputType  
{
        
        //Private

        var $value        = array(); //Arreglo de String: valor(es) predefinido(s)
        var $varname;         //String: nombre de la variable
        var $script;           //Contenido del script   
        var $id_style;
        var $type="TInputType";
        var $state;
        var $align = "center";
        var $tab = "\t\t\t\t";
        var $ro;
        var $note;
        var $event = "";

        
        function TInputType( $sVarname ) //Constructor
        {
                $this->varname  = $sVarname;
        }
        
        function AddValue( $aValue )
        {
                $this->value=$aValue;   
        }       

        function AddEvent( $sEvt )     // Lo mismo que AddEventLisener
        {
                $this->event=$sEvt;     
        }       
        
        function AddEventLisener( $sEvt )
        {
                $this->event=$sEvt;     
        }       

        function SetEnabled()
        {
                $this->state=0; 
                
        }       

        function SetDisabled()
        {
                $this->state=1; 
        }               
                
        function SetStyle($aStyle)
        {
                        $this->id_style=$aStyle;
        }

        function SetAlign($sTxt)
        {
                        $this->align=$sTxt;
        }

        function SetReadOnly()
        {
                        $this->ro=1;
        
        }
        function GetScript()
        {
                $this->script="";       
                return($this->script);
        }
        
        function GetType() 
        {
                return($this->type);
        }       

}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
// Componentes que se pueden agregar a la clase THTMLForm todos
// decienden de la clase TInputType.
//
//      Lista : TInputTypeRadio
//                      TInputTypeCheckBox
//                      TInputTypeTextArea
//                      TInputTypeText
//                      TInputTypePassword
//                      TInputTypeSelect
//                      TInputTypeHidden
//                      TInputTypeSubmit
//                      TInputTypeImage
//                      TInputTypeButton
//                      TInputTypeCustom
//                      TMixChkBoxTxt
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
class TInputTypeRadio extends TInputType
{
        
        //Private
        var $cols;                                      // Como se ditribuyen en el arreglo
        var $options = array();         //Titulo de cada opción
        var $precheck;                          // Integer : indice de la opción que deberá ser pre elegida.
        var $type="TInputTypeRadio";
        
        function SetDistribution( $nCols )
        {
                $this->cols = $nCols;
        }
        
        function SetOptions( $aOptions )
        {
                $this->options=$aOptions;       
        }
                
        function SetCheckOption( $nOpt )
        {
                $this->precheck=$nOpt;  
        }
        
        function GetScript()
        {

                $colcounter=0;  
                $ended = 0;
                if(empty($this->cols)){$this->cols=99;}
                $style=(!empty($this->id_style))?" ID='".$this->id_style."' ":"";
                $script="$this->tab <TABLE $style CELLPADING=0 CELLSPACING=0 >\n";
                $script.="$this->tab <TR> \n";
            for($i=0; $i<count($this->options); $i++)
            {
                $chk = ( $i == $this->precheck )?(" CHECKED "):("");            
                $enabled=($this->state)?" DISABLED ":"";                
                
                
                if(empty($this->value[$i])){$this->value[$i]=$i;}
                
                $script.="$this->tab \t <TD>". $this->options[$i]."</TD><TD> <INPUT TYPE='radio' $style $enabled name='".$this->varname."' value='".$this->value[$i]."' $chk ".$this->event."></TD> \n";
                        $colcounter++;
                        if($colcounter == $this->cols)
                        {
                                $colcounter = 0;
                                $script.="$this->tab </TR> \n";
                                
                                if( ($i+1) == count($this->options) )
                                {                                       
                                        $ended++;
                                        break;
                                }
                                else
                                {
                                        $script.="$this->tab <TR> \n\t";
                                }
                                
                        }
                
                }               
        
        $script.="$this->tab </TABLE>\n";
        $this->script=$script;
        return($this->script);
        
        }
        
}//End Class TInputTypeRadio

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
class TInputTypeCheckBox extends TInputType
{
        
        //Private
        var $options  = array(); //Titulo de cada opción
        var $precheck = array(); // opciónes que deberán ser aparecer premarcadas.
        var $type="TInputTypeCheckBox";
        var $checkall=false;
        var $checkallopt="";
        
        function SetDistribution( $nCols )
        {
                $this->cols = $nCols;
        }
        
        function SetOptions( $aOptions )
        {
                $this->options=$aOptions;       
        }
                
        function SetCheckOptions( $aOpt )
        {
                $this->precheck=$aOpt;  
        }

        function SetCheckAllOption( $jmethod,$label )
        {
                $this->checkall=true;   
                $this->checkall_fcall= $jmethod;
                $this->checkall_labl = $label;          
        }


        function GetScript()
        {
                $colcounter=0;  
                $ended = 0;
                if(empty($this->cols)){$this->cols=99;}
                $style=(!empty($this->id_style))?" ID='".$this->id_style."' ":"";               
                $script="$this->tab <TABLE $style CELLPADING=0 CELLSPACING=0 >\n";
                $script.="$this->tab <TR> \n\t";

                if($this->checkall)
                {

        
                $enabled=($this->state)?" DISABLED ":"";
                
                $script.="$this->tab <TD> <INPUT TYPE='checkbox'   onclick='".$this->checkall_fcall."'></TD><TD>".$this->checkall_labl ."</TD> \n";
                        $colcounter++;
                        if($colcounter == $this->cols)
                        {
                                $colcounter = 0;
                                $script.="$this->tab </TR> \n\t";
                                
                                if( ($i+1) == count($this->options) )
                                {
                                        $ended++;
                                        break;                                  
                                }
                                else
                                {
                                        $script.="$this->tab <TR> \n\t";
                                }
                                
                        }
                
                
                }
                
                
            for($i=0; $i<count($this->options); $i++)
            {
                $chk = ( 1 == $this->precheck[$i] )?(" CHECKED "):("");
                if(empty($this->value[$i])){$this->value[$i]=$i;}
                $enabled=($this->state)?" DISABLED ":"";
                
                $script.="$this->tab <TD> <INPUT TYPE='checkbox' $style $enabled name='".$this->varname[$i]."' value='".$this->value[$i]."' $chk ".$this->event."></TD><TD>". $this->options[$i]."</TD> \n";
                        $colcounter++;
                        if($colcounter == $this->cols)
                        {
                                $colcounter = 0;
                                $script.="$this->tab </TR> \n\t";
                                
                                if( ($i+1) == count($this->options) )
                                {
                                        $ended++;
                                        break;                                  
                                }
                                else
                                {
                                        $script.="$this->tab <TR> \n\t";
                                }
                                
                        }
                
                }               

        $script.="$this->tab </TABLE>\n";
        
        $this->script=$script;
        return($this->script);
        
        }

        
}//End Class TInputTypeCheckBox
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//








//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
class TMixChkBoxTxt extends TInputType
{
        
        //Private
        var $options  = array(); //Titulo de cada opción
        var $precheck = array(); // opciónes que deberán ser aparecer premarcadas.
        var $type="TMixChkBoxTxt";
        var $checkall=false;
        var $checkallopt="";
        var $checkvarnames              = array();
        var $checkvalues                = array();      
        var $cargo                              = array();       
 
 
 
 
 
        var $headernames                = array();

        // Text
        var $txtsize;
        var $txtstyle;  
        var $textvarnames               = array();      
        var $extras                     = array();
        var $txtvalues                  = array();
        var $note;
        var $ro;
        
        function SetReadOnly()
        {
                $this->ro=1;    
        }       


   //---------------------------------------------------------  

        function SetCargo( $aOptions )
        {
                $this->cargo=$aOptions; 
        }


        function SetOptions( $aOptions )
        {
                $this->options=$aOptions;       
        }
                
        function SetCheckOption( $aOpt )
        {
                $this->precheck=$aOpt;  
        }

        function SetCheckAllOption( $jmethod,$label )
        {
                $this->checkall=true;   
                $this->checkall_fcall= $jmethod;
                $this->checkall_labl = $label;
                
        }

        function SetCheckValues( $aText )
        {
                $this->checkvalues=$aText;      
        }       
        




        //----------------------------------------------
        // Text 

        function SetTextSize( $nSize )
        {
                $this->txtsize=$nSize;  
        }
        function SetTextStyle( $sText )
        {
                $this->txtstyle=$sText; 
        }

        function SetTextValues( $aText )
        {
                $this->txtvalues=$aText;        
        }       
        
        function SetTextExtraParams( $aText )
        {
                $this->extras=$aText;   
        }       

        function SetTextNotes( $sNote )
        {
                $this->notes=$sNote;    
        }       
        


        //----------------------------------------------




        function GetScript()
        {
                $colcounter=0;  
                $ended = 0;
                if(empty($this->cols)){$this->cols=99;}
                $style  =(!empty($this->id_style))?" ID='".$this->id_style."' ":"";             
                $script ="$this->tab <TABLE $style BORDER=0  CELLPADING=0 CELLSPACING=0 >\n";
                $script.="$this->tab <TR> \n\t";
                
                $ro=($this->ro)?" READONLY ":"";

                $this->checkvarnames    = $this->varname[0];
                $this->textvarnames     = $this->varname[1];
                $this->headernames              = $this->varname[2];








                if( count($this->headernames))
                {
                
                
                        $script.="$this->tab <TR STYLE='color:navy; decoration:underline;' ALIGN='center'  > \n\t";
                $script.="$this->tab <TH COLSPAN='2' ><U>".$this->headernames[0]."</U></TH>"
                                                   ."<TH><U>".$this->headernames[1] ."</U></TH> \n";
                $script.="$this->tab </TR> \n\t";
                
                
                }
                

                if($this->checkall)
                {

                        $script.="$this->tab <TR> \n\t";
                $script.="$this->tab <TD> <INPUT TYPE='checkbox'   onclick='".$this->checkall_fcall."' $ro></TD>"
                                                   ."<TD COLSPAN='2'>".$this->checkall_labl ."</TD> \n";
                $script.="$this->tab </TR> \n\t";
                }
                

                
                $enabled=($this->state)?" DISABLED ":"";
                
                
                
                $lastCargo ="";
                
            for($i=0; $i<count($this->options); $i++)
            {
                $chk = "";
                $bchk = " false;";
                $val = "";

                        if($this->precheck[($this->checkvalues[$i])] )
                        {
                                $chk = " CHECKED ";
                                $bchk = " true; ";
                                $val = $this->precheck[($this->checkvalues[$i])];                                               
                        }
                        
                        $rox =($ro)?(" onClick='this.checked=".$bchk.";' "):("");
                        
                        
                        
                        if($this->cargo)  //Agrupar las celdas con su identificador.
                        {
                                if(($lastCargo != $this->cargo[$i]))
                                {
                                        
                                        if($lastCargo !="") 
                                                $script.= "\n</TBODY>\n";
                                        
                                        $script.= "\n<TBODY ".$this->cargo[$i].">\n";   
                                        $lastCargo = $this->cargo[$i];
                                         
                                }
                        }
                        $script.="$this->tab <TR   > \n\t".
                        
                "<TD Align='center'>&nbsp;".
                        "&nbsp;<INPUT TYPE='checkbox' $style $enabled name='".$this->checkvarnames[$i]."' value='".$this->checkvalues[$i]."' $chk ".$this->event." $rox>&nbsp;".
                "</TD>".
                "<TD Align='left'>". 
                        $this->options[$i].
                "</TD>".
                "<TD> &nbsp;&nbsp;&nbsp;". 
                        "<INPUT TYPE='TEXT' ".$this->txtstyle."  $enabled  $ro  NAME='".$this->textvarnames[$i]."' VALUE='".$val."' SIZE='".$this->txtsize."' $ro > ".$this->notes."\n\n ";     
                "</TD> \n";

                        $script.="$this->tab </TR> \n\t";

                
                }               

        $script.="$this->tab </TABLE>\n";
        
        $this->script=$script;
        return($this->script);
        
        }

        
}//End Class TMixChkBoxTxt
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//






class TInputTypeTextArea  extends TInputType
{
        
        //Private
        var $cols;                              //Int: Columnas 
        var $rows;                      //Int: Renglones
        var $title;                             //STR: Titulo   
        var $text;                              // Texto preescrito
        var $type="TInputTypeTextArea";
        var $cargo = "";
        
        function SetCols( $nCols)
        {
                $this->cols=$nCols;     
        }
        
        function SetRows( $nRows )
        {
                $this->rows=$nRows;     
        }
        function SetTitle( $sTiltle )
        {
                $this->title=$sTiltle;  
        }
        function SetText( $sText )
        {
                $this->text=$sText;     
        }       
        

        
        
        function SetReadOnly()
        {
                $this->ro=1;    
        }       
        
        function SetCargo($txt)
        {
                $this->cargo = $txt;
        }
        
        
        function GetScript()
        {
                $enabled=($this->state)?" DISABLED ":"";
                $readonly = ($this->ro)?("READONLY "):("");
                
                $style=(!empty($this->id_style))?" ID='".$this->id_style."' ":"";               
                $script="$this->tab <TABLE $style CELLPADING=0 CELLSPACING=0 >\n";
                $script.="$this->tab <TR> \n\t";
            $script.="$this->tab <TD Align='right'> \n";
            $script.= $this->tab . $this->title ;           
            $script.="$this->tab </TD> \n";     
        
            $script.="$this->tab <TD Align='right'> \n\t";
            
           
            
            
            $script.="$this->tab <TEXTAREA $style ".$enabled." ".$this->cargo." name='".$this->varname."' cols='".$this->cols."' rows='".$this->rows."' ".$this->event." ".$readonly.">";       
            $script.= $this->text;
            $script.="</TEXTAREA>\n";
            $script.="$this->tab </TD> \n";     
                $script.="$this->tab </TR> \n\t";
                $script.="$this->tab </TABLE>\n";


        
        $this->script=$script;
        return($this->script);
        
        }
        
}//End Class TInputTypeTextArea
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//

class TInputTypeRFC extends TInputType
{
        
        //Private
        var $size;                              //Int: tamaño
        var $maximun;                   //Int: tamaño máximo del texto
        var $title;                             //STR: Titulo   
        var $ro = 0;
        
        var $value1="";
        var $value2=""; 
        
        var $type="TInputTypeText";
        
        function AddValue( $sValue1,$sValue2 ) // Overried
        {
                $this->value1=$sValue1;
                $this->value2=$sValue2;
                
        }       
                
        function SetSize( $nSize)
        {
                $this->size=$nSize;     
        }
        
        function SetMax( $nMaximun )    
        {
                $this->maximun=$nMaximun;       
        }
        
        function SetTitle( $sTiltle )
        {
                $this->title=$sTiltle;  
        }       

        function SetReadOnly()
        {
                $this->ro=1;    
        }       

        function SetText( $sText )
        {
                $this->text=$sText;     
        }       
        
        function SetNote( $sText )
        {
                $this->note=$sText;     
        }       
        
        function SetExtra( $sText )
        {
                $this->extra=$sText;    
        }               
        
        
        function GetScript()
        {
                $style=(!empty($this->id_style))?" ID='".$this->id_style."' ":"";               
                $script="$this->tab <TABLE $style CELLPADING=0 CELLSPACING=0 >\n";
                $script.="$this->tab <TR> \n\t";
            $script.="$this->tab <TD Align='right'> \n";
            $script.="$this->tab  ".$this->title ;          
            $script.="$this->tab </TD> \n";     
                
                $enabled=($this->state)?" DISABLED ":"";
                
                $ro=($this->ro)?" READONLY ":"";
            $required=($this->required)?" required ":"";
            
            $script.="$this->tab <TD Align='right'> \n\t";
            $script.="$this->tab <Input type='text' $style  $enabled  $ro $required  ".$this->extra." name='".$this->varname."' value='".$this->value1."' size='10' maxlength='10' ".$this->event."> - <Input type='text' $style  $enabled  $ro $required  ".$this->extra." name='".$this->varname."homo' value='".$this->value2."' size='3' maxlength='3' ".$this->event.">\n\n ";     
            $script.= $sText. "\n\n";
            $script.="$this->tab </TD> \n";     
                $script.="$this->tab </TR> \n\t";
                $script.="$this->tab </TABLE>\n";


        
                $this->script=$script;
                return($this->script);
        }
        
}//End Class TInputTypeText

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//

class TInputTypeText extends TInputType
{
        
        //Private
        var $size;                              //Int: tamaño
        var $maximun;                   //Int: tamaño máximo del texto
        var $title;                             //STR: Titulo   
        var $ro = 0;
        var $type="TInputTypeText";
        
                
        function SetSize( $nSize)
        {
                $this->size=$nSize;     
        }
        
        function SetMax( $nMaximun )    
        {
                $this->maximun=$nMaximun;       
        }
        
        function SetTitle( $sTiltle )
        {
                $this->title=$sTiltle;  
        }       

        function SetReadOnly()
        {
                $this->ro=1;    
        }       

        function SetText( $sText )
        {
                $this->text=$sText;     
        }       
        
        function SetNote( $sText )
        {
                $this->note=$sText;     
        }       
        
        function SetExtra( $sText )
        {
                $this->extra=$sText;    
        }               
        
        
        function GetScript()
        {
                $style=(!empty($this->id_style))?" ID='".$this->id_style."' ":"";               
                $script="$this->tab <TABLE $style CELLPADING=0 CELLSPACING=0 >\n";
                $script.="$this->tab <TR> \n\t";
            $script.="$this->tab <TD Align='right'> \n";
            $script.="$this->tab  ".$this->title ;          
            $script.="$this->tab </TD> \n";     
                
                $enabled=($this->state)?" DISABLED ":"";
                
                $ro=($this->ro)?" READONLY ":"";
            $required=($this->required)?" required ":"";
            
            $script.="$this->tab <TD Align='right'> \n\t";
            $script.="$this->tab <Input type='text' $style  $enabled  $ro $required  ".$this->extra." name='".$this->varname."' value='".$this->value."' size='".$this->size."' maxlength='".$this->maximun."' ".$this->event.">\n\n "; 
            $script.= $this->note. "\n\n";
            $script.="$this->tab </TD> \n";     
                $script.="$this->tab </TR> \n\t";
                $script.="$this->tab </TABLE>\n";


        
                $this->script=$script;
                return($this->script);
        }
        
}//End Class TInputTypeText
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//

class TInputTypePassword extends TInputType
{
        
        //Private
        var $size;                              //Int: tamaño
        var $maximun;                   //Int: tamaño máximo del texto
        var $title;                             //STR: Titulo   
        var $ro = 0;
        var $type="TInputTypePassword";
        
                
        function SetSize( $nSize)
        {
                $this->size=$nSize;     
        }
        
        function SetMax( $nMaximun )    
        {
                $this->maximun=$nMaximun;       
        }
        
        function SetTitle( $sTiltle )
        {
                $this->title=$sTiltle;  
        }       

        function SetReadOnly()
        {
                $this->ro=1;    
        }       

        function SetText( $sText )
        {
                $this->text=$sText;     
        }       
        
        function SetNote( $sText )
        {
                $this->note=$sText;     
        }       
        
        function SetExtra( $sText )
        {
                $this->extra=$sText;    
        }               
        
        
        function GetScript()
        {
                $style=(!empty($this->id_style))?" ID='".$this->id_style."' ":"";               
                $script="$this->tab <TABLE $style CELLPADING=0 CELLSPACING=0 >\n";
                $script.="$this->tab <TR> \n\t";
            $script.="$this->tab <TD Align='right'> \n";
            $script.="$this->tab  ".$this->title ;          
            $script.="$this->tab </TD> \n";     
                
                $enabled=($this->state)?" DISABLED ":"";
                
                $ro=($this->ro)?" READONLY ":"";
            $required=($this->required)?" required ":"";
            
            $script.="$this->tab <TD Align='right'> \n\t";
            $script.="$this->tab <Input type='password' $style  $enabled  $ro $required  ".$this->extra." name='".$this->varname."' value='".$this->value."' size='".$this->size."' maxlength='".$this->maximun."' ".$this->event.">\n\n ";     
            $script.= $sText. "\n\n";
            $script.="$this->tab </TD> \n";     
                $script.="$this->tab </TR> \n\t";
                $script.="$this->tab </TABLE>\n";


        
                $this->script=$script;
                return($this->script);
        }
        
}//End Class TInputTypePassword
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//

class TInputTypeSelect extends TInputType
{
        
        //Private

        var $size;                              //Int: tamaño
        var $checkopt;                  //STR: Nombre de la(s) opción(es) que deverá(n) ser preseleccionada(s)
        var $selectype;                 //INT: (0/1) (Single / Multiple) select
        var $title;                             //STR: Titulo   
        var $options;                   //STR: opcion que deberán desplegarse seccionada
        var $type="TInputTypeSelect";
        var $event;
        var $ro = '';
        var $nstate;


        function SetNote( $sText )
        {
                $this->note=$sText;     
        }

        function SetSize( $nSize)                       
        {
                $this->size=$nSize;     
        }
        
        function SetReadOnly()          
        {
                $this->ro=1;    
        }
        
        function Enable()
        {
                $this->nstate=0;        
                $this->state=0;
        }       

        function Disable()
        {
                $this->nstate=1;
                $this->state=1;
        }       
        
        function SetCheckOption( $sOpt )
        {
                $this->checkopt=$sOpt;  
        }       
        function SetTitle( $sTiltle )
        {
                $this->title=$sTiltle;  
        }       
        function SetMultiSelect()
        {
                $this->selectype=1;     
        }               
        function SetSingleSelect()
        {
                $this->selectype=0;     
        }               
        function SetOptions( $aOpt )
        {
                $this->options=$aOpt;   
        }       
        function AddOption( $sValue,$sOpt )
        {
                $opdex = count($this->options);                         
                $this->options[$opdex]=$sOpt;   
                $this->value[$opdex]=$sValue;
        }       


        function GetScript()
        {
                $selectype = ($this->selectype)?" MULTIPLE ":" SINGLE ";
                $this->size = ($this->size==0)?1:$this->size;
                $enabled=($this->state)?" DISABLED ":"";
                $style=(!empty($this->id_style))?" ID='".$this->id_style."' ":"";               
                $script="$this->tab <TABLE $style CELLPADING=0 CELLSPACING=0 >\n";
                $script.="$this->tab <TR> \n";
            $script.="$this->tab \t<TD Align='right'> \n";
            $script.="$this->tab \t\t ".$this->title ;      
            $script.="$this->tab \t</TD> \n";   
        
            $script.="<TD Align='right'> \n\t";
                $enabled=($this->nstate OR $this->state)?" DISABLED ":"";
                $event=($this->event)?($this->event):("");
                if(!$this->ro)      
                {
                                $script.="\n$this->tab <SELECT   ".$style." ".$enabled." name='".$this->varname."' ".$selectype." size='".$this->size."' ".$this->event." >\n ";        

                                for($i=0; $i<count($this->value); $i++ )
                                {
                                        if(is_array($this->checkopt))
                                        {
                                                $check = (in_array($this->value[$i],$this->checkopt) or in_array($this->options[$i],$this->checkopt))?" SELECTED ":"";
                                        }
                                        else
                                        {
                                                $check = ($this->checkopt==$this->value[$i] or $this->checkopt==$this->options[$i] )?" SELECTED ":"";
                                        }
                                        $script.="$this->tab \t<OPTION value='".$this->value[$i]."' $check > ".$this->options[$i]." </OPTION> \n";
                                }

                                $script.="$this->tab </SELECT>\n ";
                        $script.="<SMALL><Font Color='#000000'>".$this->note."</Font></SMALL>\n ";
                }
                else
                {           
                        $_size=min(70,(strlen($this->checkopt)+5));                     
                        $script.="\n<INPUT TYPE='TEXT' $style  SIZE='".$_size."' READONLY VALUE='".$this->checkopt."' NAME='".$this->varname."' >";
            
            }
            
            
            
            $script.="$this->tab \t</TD> \n";   
                $script.="$this->tab </TR> \n";
                $script.="$this->tab </TABLE>\n";

            
                $this->script=$script;
                return($this->script);
        }
        
}//End Class TInputTypeSelect

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//

class TInputTypeHidden extends TInputType
{
        var $type="TInputTypeHidden";                   
        function GetScript()
        {
            $script="\n\n $this->tab <Input  type='hidden'  name='".$this->varname."' value='".$this->value."' >\n ";   
                $this->script=$script;
                return($this->script);
        }
        
}//End Class TInputTypeHidden
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//


class TInputTypeNone extends TInputType
{
        var $type="TInputTypeNone";                     
        function GetScript()
        {       
                $style = ($this->id_style)?(" ID='".$this->id_style."' "):("");
            $script="\n\n $this->tab <B  $style >". $this->value ."</B>\n ";    
                $this->script=$script;
                return($this->script);
        }
        
}//End Class TInputTypeHidden
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
class TInputTypeSubmitCancel extends TInputType
{
        var $type="TInputTypeSubmitCancel";                     
        
        var $extra_params="";
        var $oncancel="";
        var $event="";
        var $sscript ="";

        function AddEventListener( $txt )
        {
                $this->event = $txt;
                return;
        }

        function AddScript( $txt )
        {
                $this->sscript = $txt;
                return;
        }


        function OnCancelEvent( $txt )
        {
                $this->oncancel = $txt;
                return;
        }


        function AddCancelParams( $txt )
        {
                $this->extra_params = $txt;
                return;
        }
        
        function GetScript()
        {
                        $enabled=($this->state)?" DISABLED ":"";
                        $style=(!empty($this->id_style))?" ID='".$this->id_style."' ":"";               
                        $script="$this->tab <TABLE $style width='100%' CELLPADING=0 CELLSPACING=0 BGCOLOR='".$this->tablestyle->column_bg_color[0]."' BORDER=0>\n";
                        $script.="$this->tab <TR> \n\t";
                                $script.="$this->tab <TD Align='center'> \n";

                                        $script.="$this->tab <Input  type='submit' $style $enabled name='".$this->varname."' value='".$this->value."'  ".$this->event.">\n ";

                                $script.="$this->tab &nbsp;&nbsp;&nbsp;</TD> \n";       
                    
                                $script.="$this->tab <TD Align='center'> \n";
                                        
                                        
                                        $script.="$this->tab <Input  type='button' $style $enabled name='cancelar' value='Cancelar' ";
                                        
                                        
                                        
                                        
                                        if(empty($this->oncancel))
                                        {
                                                        $script.="onclick=\"javascript: location.replace('#";
                                        
                                                        if(!empty($this->extra_params)){$script.="&".$this->extra_params;}
                                                        $script.="') \"";
                                        
                                        }
                                        else
                                        {
                                        
                                                        $script.= " ".$this->oncancel." ";
                                        
                                        }
                                        
                                        $script.="  >\n ";

                                $script.="$this->tab </TD> \n";                     
                    
                        $script.="$this->tab </TR> \n\t";
                        $script.="$this->tab </TABLE>\n";




        if($this->sscript)
        {       
        
                $script.= "\n <SCRIPT> \n  ";
                $script.= "<!-- Script agregado por la clase SubmitCancel --> \n";              
                $script.= $this->sscript;
                $script.= "\n </SCRIPT> \n  ";
        
        
        
        }

                
                $this->script=$script;  
                return($this->script);
        }
        
}//End Class TInputTypeSubmitCancel
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//


class TInputTypeSubmit extends TInputType
{
        var $type="TInputTypeSubmit";                   
        
        function GetScript()
        {


                        $enabled=($this->state)?" DISABLED ":"";
                        $style=(!empty($this->id_style))?" ID='".$this->id_style."' ":"";               
                        $script ="$this->tab <TABLE $style CELLPADING=0 CELLSPACING=0 COLSPAN=2>\n";
                        $script.="$this->tab <TR> \n\t";
                    $script.="$this->tab <TD Align='center'> \n";
            
                        $script.="$this->tab <Input  type='submit' $style $enabled name='".$this->varname."' value='".$this->value."' >\n ";
                        
                    $script.="$this->tab </TD> \n";     
                        $script.="$this->tab </TR> \n\t";
                        $script.="$this->tab </TABLE>\n";

                
                $this->script=$script;  
                return($this->script);
        }
        
}//End Class TInputTypeSubmit 
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
class TInputTypeImage extends TInputType
{
        var $type="TInputTypeImage";
        var $sourcefile;
        var $hint;
        
        function SetSourceFile($sFile)
        {
                $this->sourcefile=$sFile;
        }
        
        function SetHint($sTxt)
        {
                $this->hint=$sTxt;
        }
        
        
        function GetScript()
        {
                        $enabled=($this->state)?" DISABLED ":"";
                        $style=(!empty($this->id_style))?" ID='".$this->id_style."' ":"";               
                        
                        $script="$this->tab <TABLE CELLPADING=0 CELLSPACING=0 >\n";
                        $script.="$this->tab <TR> \n\t";
                    $script.="$this->tab <TD Align='center'> \n\t\t";
            
                        $script.="$this->tab <Input type='image' $style alt='".$this->hint."' src='".$this->sourcefile."' $enabled name='".$this->varname."' ".$this->event." >\n\t ";
                        
                    $script.="$this->tab </TD> \n";     
                        $script.="$this->tab </TR> \n\t";
                        $script.="$this->tab </TABLE>\n";

                
                $this->script=$script;  
                return($this->script);
        }
        
}//End Class TInputTypeImage
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//

class TInputTypeButton extends TInputType  //
{
        var $type="TInputTypeButton";
        var $event_onclick;

        function SetEvent_OnClick($sTxt)
        {
                $this->event_onclick=$sTxt;
        }
        
        function SetNote( $sTxt )
        {
                $this->note=$sTxt ;     
        }       
        
        function GetScript()
        {
                        $enabled=($this->state)?" DISABLED ":"";
                        $style=(!empty($this->id_style))?" ID='".$this->id_style."' ":"";               
                        
                        $script="$this->tab <TABLE $style CELLPADING=0 CELLSPACING=0 >\n";
                        $script.="$this->tab <TR> \n\t";
                    $script.="$this->tab <TD Align='center'> \n";
                
                $this->event_onclick=($this->event_onclick=="")?"javascript:void(0);":$this->event_onclick;
                
                        $script.="$this->tab <Input  TYPE='button' $style $enabled name='".$this->varname."' value='".$this->value."'  onClick=\"".$this->event_onclick."\">  <SMALL><Font Color='#000000'>".$this->note."</Font></SMALL>\n ";
                
                        
                    $script.="$this->tab </TD> \n";     
                        $script.="$this->tab </TR> \n\t";
                        $script.="$this->tab </TABLE>\n";

                
                $this->script=$script;  
                return($this->script);
        }
        
}//End Class TInputTypeButton
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
class TInputTypeCustom extends TInputType
{
        
        //Private
        var $title;                             //STR: Titulo   
        var $type ="TInputTypeCustom";
        var $mask;
        var $filter;
        var $custom_type;
        var $note;
        var $required = 0;
        var $ro;
        var $txtstyle ="";
        
        function SetSize( $nSize)
        {
                $this->size=$nSize;     
        }
        
        function SetMax( $nMaximun )    
        {
                $this->maximun=$nMaximun;       
        }

        function SetRequiered()
        {
                $this->required=1;      
        }       
        
        function SetUnRequiered()
        {
                $this->required=0;      
        }       
                
        function SetFilter( $sTxt)
        {
                $this->filter=$sTxt;    
        }

        function SetCustomType( $sTxt )                 
        {
                $this->custom_type=$sTxt;       
                
                // Alinear numeros a la derecha =)
                if($sTxt=='integer' OR $sTxt=='float') $this->SetTextStyle(" STYLE='text-align:right;' ");
                
        }
                
        function SetMask( $sTxt )
        {
                $this->mask=$sTxt;      
        }       

        function SetTitle( $sTiltle )
        {
                $this->title=$sTiltle;  
        }       
        
        function SetNote( $sTxt )
        {
                $this->note=$sTxt ;     
        }               
        
        function SetReadOnly()
        {
                $this->ro=1;    
        }       
        
        function SetTextStyle( $sText )
        {
                $this->txtstyle=$sText; 
        }       
        
        
        function GetScript()
        {
                $style  =" ID='".$this->id_style."' ";          
                $script=" $this->tab <TABLE $style CELLPADING=0 CELLSPACING=0 >\n ";
                $script.="$this->tab <TR> \n\t";
            $script.="$this->tab <TD Align='right'> \n";
            $script.="$this->tab  ".$this->title ;          
            $script.="$this->tab </TD> \n";     
                
            $enabled=($this->state >= 1)?" DISABLED STYLE='background-color:silver;' ":" ";
            $required=($this->required)?" required ":"";
            
            $script.="$this->tab <TD Align='right'> \n\t";
            
            $filter=(!empty($this->filter))?" filter=\"$this->filter\" ":" ";   
            $custom_type = (!empty($this->custom_type))?$this->custom_type:" ";
            $mask=(!empty($this->mask))?"mask=\"".$this->mask."\"":" ";
          
            
            $script.=$this->tab." <INPUT ".$custom_type." ".$this->txtstyle." ".$style." ".$enabled." ".$required." name='".$this->varname."' ";
            if($this->size)      $script.=" size='".$this->size."' ";
            if($this->maximun)   $script.="  maxlength='".$this->maximun."' ";
            
            $script.=" value='".$this->value."' $mask  $filter  ".$this->event;
            
            if($this->ro)  $script.=" READONLY ";
            
            $script.="  >  <SMALL><Font Color='#000000'>".$this->note."</Font></SMALL> \n\n";   
            $script.= $this->tab . $this->text . "\n\n";
            $script.="$this->tab </TD> \n";     
                $script.="$this->tab </TR> \n\t";
                $script.="$this->tab </TABLE>\n";


        
                $this->script=$script;
                return($this->script);
        }
        
}//End Class TInputTypeCustom

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
class TInputTypeDate extends TInputType
{
        
        
        
        //Private
        var $size = 15;                         //Int: tamaño
        var $maximun=10;
        var $title;                             //STR: Titulo   
        var $type="TInputTypeDate";
        var $clean_up=false;
        var $timetype="";
        var $OnChangeEvent="";
        var $presetdate="";
        
        function SetTitle( $sTiltle )
        {
                $this->title=$sTiltle;  
        }       
        
        
        function OnChangeEvent( $sfunction )
        {
                $this->OnChangeEvent= $sfunction;
                
        }


        function SetPrestetDate( $pdate )
        {
                $this->presetdate=$pdate;       
        } 

        
        
        function OnBlur( $sfunction )
        {
                $this->OnBlur= $sfunction;
                
        }       
        
        function AddDate( $val )
        {
                $this->value=$val;      
        }       

        function SetOnlyFuture()
        {
                $this->timetype="&future=1";    
        }       

        function SetText( $sText )
        {
                $this->text=$sText;     
        } 
        
        function SetOnlyPast()
        {
                $this->timetype="&past=1";      
        }

        function SetAnyTime()
        {
                $this->timetype="";     
        }
                
        
        function GetScript()
        {
                global $frm_calendar_path;
                
                $on_change_event = ($this->OnChangeEvent)?(" onChange='".$this->OnChangeEvent."' "):("");
                
                $on_blur_event = ($this->OnBlur)?(" onBlur='".$this->OnBlur."' "):("");
                
                
                $style=(!empty($this->id_style))?" ID='".$this->id_style."' ":"";               
                $script="$this->tab <TABLE $style CELLPADING=0 CELLSPACING=0 >\n";
                $script.="$this->tab <TR> \n\t";
            $script.="$this->tab <TD Align='right'> \n";
            $script.="$this->tab  ".$this->title ;          
            $script.="$this->tab </TD> \n";     
                
                $enabled=($this->state)?" DISABLED ":"";
            $required=($this->required)?" required ":"";
            
            $script.="$this->tab <TD Align='right'> \n\t";
            $this->size+=10;
           
           $enabled=($this->state)?" DISABLED ":"";             
           
           
           $ronly = ($this->ro)?("READONLY"):("");
           

           $pivot=($this->pivot)?("&pivot=".$this->pivot):("");
           
           if($this->presetdate =="" )
              $presetdate=($this->value)?("&presetdate=".$this->value):("");
           else
              $presetdate="&presetdate=".$this->presetdate;
           
           $script.="$this->tab <INPUT type='text' $style  $enabled $required name='".$this->varname."' value='".$this->value."' size='".$this->size."' maxlength='".$this->maximun."'  ".$on_change_event." ".$on_blur_event."  ".$enabled."  ".$ronly."> \n\n ";      
           
            
            $name=time();
            if(!$this->ro)
            {
                        $script.="<INPUT TYPE='BUTTON'  ".$style." onClick='window.open(\"".$frm_calendar_path."calendario.php?".$this->timetype."&campo=".$this->varname."&forma=X#X#X".$presetdate."\",\"selfecha\",\"width=220,height=220,menubar=0,toolbar=0,resizable=1,scrollbars=0,status=1\");' VALUE='...'> ";

                        if($this->clean_up)
                        $script.="<INPUT TYPE='BUTTON'  ".$style."  ID='S2'  onClick=\"document.X#X#X['".$this->varname."'].value='Limpiar';\"  VALUE='Limpiar'>";
            }
            
            
            $script.=$this->text . "\n\n";
            $script.="$this->tab </TD> \n";     
                $script.="$this->tab </TR> \n\t";
                $script.="$this->tab </TABLE>\n";


        
        $this->script=$script;
        return($this->script);
        }
        
}//End Class TInputTypeDate

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
class TInputTypeDateTime extends TInputType
{
        
        
        
        //Private
        var $size = 15;                 //Int: tamaño
        var $maximun=10;
        var $title;                             //STR: Titulo   
        var $type="TInputTypeDateTime";
        var $clean_up=false;
        var $timetype="";
        var $OnChangeEvent="";
        var $value_hh = "00";
        var $value_mm = "00";
        var $value_ss = "00";
        

        function TInputTypeDateTime( $sVarname, $sVarname_hour, $sVarname_minute, $sVarname_second  ) //Constructor
        {
                $this->varname  = $sVarname;
                $this->value_hh_name = $sVarname_hour; 
                $this->value_mm_name = $sVarname_minute;
                $this->value_ss_name = $sVarname_second;                
        }


        function SetTitle( $sTiltle )
        {
                $this->title=$sTiltle;  
        }       

        function SetText( $sText )
        {
                $this->text=$sText;     
        }       
        
        function OnChangeEvent( $sfunction )
        {
                $this->OnChangeEvent= $sfunction;
                
        }       
        
        function AddDate( $val )
        {
                $this->value=$val;      
        }       

        function AddHour( $val )
        {
                $this->value_hh=$val;   
        }

        function AddMinute( $val )
        {
                $this->value_mm=$val;   
        }

        function AddSecond( $val )
        {
                $this->value_ss=$val;   
        }


        function SetOnlyFuture()
        {
                $this->timetype="&future=1";    
        }       


        function SetOnlyPast()
        {
                $this->timetype="&past=1";      
        }

        function SetAnyTime()
        {
                $this->timetype="";     
        }
                
        
        function GetScript()
        {
                global $frm_calendar_path;
                
                $on_change_event = ($this->OnChangeEvent)?(" onChange='".$this->OnChangeEvent."' "):("");
                $style=(!empty($this->id_style))?" ID='".$this->id_style."' ":"";               
                $script="$this->tab <TABLE $style CELLPADING=0 CELLSPACING=0 >\n";
                $script.="$this->tab <TR> \n\t";
            $script.="$this->tab <TD Align='right'> \n";
            $script.="$this->tab  ".$this->title ;          
            $script.="$this->tab </TD> \n";     
                
                $enabled=($this->state)?" DISABLED ":"";
            $required=($this->required)?" required ":"";
            
            $script.="$this->tab <TD Align='right'> \n\t";
            $this->size+=10;
           
           $enabled=($this->state)?" DISABLED ":"";             
           
           $script.="$this->tab <Input type='text' $style  $enabled $required name='".$this->varname."' value='".$this->value."' size='12'   ".$on_change_event." ".$enabled." READONLY> \n\n ";        
           
            
            $name=time();

                if(!$this->ro)      
                {
                        $script.="<INPUT TYPE='BUTTON'  ".$style." onClick='window.open(\"".$frm_calendar_path."calendario.php?".$this->timetype."&campo=".$this->varname."&forma=X#X#X".$presetdate."\",\"selfecha\",\"width=220,height=220,menubar=0,toolbar=0,resizable=1,scrollbars=0,status=1\");' VALUE='...'> ";

                         if($this->clean_up)
                        $script.="<INPUT TYPE='BUTTON'  ".$style."  ID='S2'  onClick=\"document.X#X#X['".$this->varname."'].value='Limpiar';\"  VALUE='Limpiar'>";
                }           
                //-----------------------------------------------
                // Horas            

                if(!$this->ro)      
                {                          
              $script.="\n<SELECT  $style  name='".$this->value_hh_name."'  >\n";
              for($h=0;$h<24;$h++) 
              {
                $hh=($h<=9)?("0".$h):($h);
                $sel=($h==$this->value_hh)?("SELECTED"):("");
                $script.="\t<OPTION VALUE='".$hh."' ".$sel." >".$hh."</OPTION>\n";
              }
              $script.=" </SELECT>";
              $script.="<B> : </B>";    
                }
                else
                {
                        $script.="\n<INPUT TYPE='TEXT' $style SIZE='2' READONLY VALUE='".$this->value_hh."' NAME='".$this->value_hh_name."' > <B>:</B>";
                
                }
                //-----------------------------------------------
                // Minutos
                if(!$this->ro)      
                {                       
              $script.="\n<SELECT  $style  name='".$this->value_mm_name."'  >\n";
              for($m=0;$m<60;$m++) 
              {
                $mm=($m<=9)?("0".$m):($m);
                $sel=($m==$this->value_mm)?("SELECTED"):("");
                $script.="\t<OPTION VALUE='".$mm."' ".$sel." >".$mm."</OPTION>\n";
              }
              $script.=" </SELECT>";
              $script.="<B> : </B>";          
                }
                else
                {
                        $script.="\n<INPUT TYPE='TEXT' $style SIZE='2' READONLY VALUE='".$this->value_mm."' NAME='".$this->value_mm_name."' > <B>:</B>";
                
                }
                //-----------------------------------------------
                 //Segundos 
                if(!$this->ro)      
                {                       
                 
              $script.="\n<SELECT  $style  name='".$this->value_ss_name."'  >\n";
              for($s=0;$s<60;$s++) 
              {
                $ss=($s<=9)?("0".$s):($s);
                $sel=($s==$this->value_ss)?("SELECTED"):("");
                $script.="\t<OPTION VALUE='".$ss."' ".$sel." >".$ss."</OPTION>\n";
              }
              $script.=" </SELECT>";
            }
                else
                {
                        $script.="\n<INPUT TYPE='TEXT' $style SIZE='2' READONLY VALUE='".$this->value_ss."' NAME='".$this->value_ss_name."' >\n";
                
                }

/**/
            
            $script.=$this->text . "\n\n";
            $script.="$this->tab </TD> \n";     
                $script.="$this->tab </TR> \n\t";
                $script.="$this->tab </TABLE>\n";


        
        $this->script=$script;
        return($this->script);
        }
        
}//End Class TInputTypeDateTime
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
//      Clase TInputTypeTime
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//


class TInputTypeTime extends TInputType
{
        
        
        
        //Private
        var $size = 15;                 //Int: tamaño
        var $maximun=10;
        var $title;                             //STR: Titulo   
        var $type="TInputTypeTime";
        var $clean_up=false;
        var $timetype="";
        var $OnChangeEvent="";
        var $value_hh = "00";
        var $value_mm = "00";
        var $value_ss = "00";
        

        function TInputTypeTime($sVarname_hour,$sVarname_minute,$sVarname_second) //Constructor
        {                
                $this->value_hh_name = $sVarname_hour; 
                $this->value_mm_name = $sVarname_minute;
                $this->value_ss_name = $sVarname_second;                
        }


        function SetTitle( $sTiltle )
        {
                $this->title=$sTiltle;  
        }       

        function SetText( $sText )
        {
                $this->text=$sText;     
        }       
        
        function OnChangeEvent( $sfunction )
        {
                $this->OnChangeEvent= $sfunction;
                
        }       
        

        function AddHour( $val )
        {
                $this->value_hh=$val;   
        }

        function AddMinute( $val )
        {
                $this->value_mm=$val;   
        }

        function AddSecond( $val )
        {
                $this->value_ss=$val;   
        }

        function SetAnyTime()
        {
                $this->timetype="";     
        }
                
        
        function GetScript()
        {
                
                $on_change_event = ($this->OnChangeEvent)?(" onChange='".$this->OnChangeEvent."' "):("");
                $style=(!empty($this->id_style))?" ID='".$this->id_style."' ":"";               
                $script="$this->tab <TABLE $style CELLPADING=0 CELLSPACING=0 >\n";
                $script.="$this->tab <TR> \n\t";
            $script.="$this->tab <TD Align='right'> \n";
            $script.="$this->tab  ".$this->title ;          
            $script.="$this->tab </TD> \n";     
                
                $enabled=($this->state)?" DISABLED ":"";
            $required=($this->required)?" required ":"";
            
            $script.="$this->tab <TD Align='right'> \n\t";
            $this->size+=10;
           
           $enabled=($this->state)?" DISABLED ":"";             
           
                  
                
            $name=time();

                //-----------------------------------------------
                // Horas            

                      $script.="\n<SELECT  $style  name='".$this->value_hh_name."'  >\n";
                      for($h=0;$h<24;$h++) 
                      {
                        $hh=($h<=9)?("0".$h):($h);
                        $sel=($h==$this->value_hh)?("SELECTED"):("");
                        $script.="\t<OPTION VALUE='".$hh."' ".$sel." >".$hh."</OPTION>\n";
                      }
                      $script.=" </SELECT>";
                      $script.="<B> : </B>";    

                //-----------------------------------------------
                // Minutos
                      $script.="\n<SELECT  $style  name='".$this->value_mm_name."'  >\n";
                      for($m=0;$m<60;$m++) 
                      {
                        $mm=($m<=9)?("0".$m):($m);
                        $sel=($m==$this->value_mm)?("SELECTED"):("");
                        $script.="\t<OPTION VALUE='".$mm."' ".$sel." >".$mm."</OPTION>\n";
                      }
                      $script.=" </SELECT>";
                      $script.="<B> : </B>";          

                //-----------------------------------------------
                 //Segundos 

                      $script.="\n<SELECT  $style  name='".$this->value_ss_name."'  >\n";
                      for($s=0;$s<60;$s++) 
                      {
                        $ss=($s<=9)?("0".$s):($s);
                        $sel=($s==$this->value_ss)?("SELECTED"):("");
                        $script.="\t<OPTION VALUE='".$ss."' ".$sel." >".$ss."</OPTION>\n";
                      }
                      $script.=" </SELECT>";

/**/
            
            $script.=$this->text . "\n\n";
            $script.="$this->tab </TD> \n";     
                $script.="$this->tab </TR> \n\t";
                $script.="$this->tab </TABLE>\n";


        
        $this->script=$script;
        return($this->script);
        }
        
}//End Class TInputTypeTime


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
//      Clase TInputTypeFile
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//


class TInputTypeFile extends TInputType
{
        var $type="TInputTypeFile";
        var $note ="";  
        var $accept="";
        var $size="";
        
        
        function SetNote( $sTxt )
        {
                $this->note=$sTxt ;     
        }       

        function SetSize($sTxt )
        {
                $this->size=" size='".$sTxt."' " ;      
        }       
        
        
        function SetAccept( $sTxt )
        {
                $this->accept=" accept='".$sTxt."' " ;  
        }       
        
        
        
        function GetScript()
        {
                $style=(!empty($this->id_style))?" ID='".$this->id_style."' ":"";               
                $script.= $this->tab." <TR> \n\t";
            $script.= $this->tab." <TD Align='right'> \n";
            $script.= $this->tab." ".$this->title ;         
            $script.= $this->tab." </TD> \n";   
                
                $enabled=($this->state)?" DISABLED ":"";

                        $script=$this->tab."&nbsp;<INPUT  type='file' ".$this->size." ".$this->accept."  name='".$this->varname."' $enabled >\n ".$this->note;  
                                
            $script.= $this->tab."</TD> \n";    
                $script.= $this->tab." </TR> \n";
                                
                $this->script = $script;
                
                return($this->script);
        }
        
}//End Class TInputTypeFile


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
//      Attributos de la tabla que contiene la forma de la clase THtmlForm
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//

class TTableStyle
{
        var $align="center";
        var $valign="middle";
        var $border="0";
        var $width="100%";
        var $bgcolor="#FFFFFF";
        var $column_bg_color =array("lemonchiffon","beige");
        var $column_valign = array("middle","middle");
        var $column_align  = array("center","center");
        var $column_width  = array("30%","70%");
        var $type="TTableStyle";
}


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
// Clase contendora de objetos que descienden en segundo término de la clase TInputType
// genera una forma de captura HTML.
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//

class THtmlForm
{
        var $url;
        var $method;
        var $form_name;
        var $target;
        var $titles               = array();    // Titulos.
        var $values               = array();    // Valores.
        var $varnames   = array();    // Variables.
        var $oItems                = array();   // Arreglo de Objetos
        var $col_id_style = array();
        var $index;                       
        var $script;
        var $tablestyle;
        var $extborder="0";
        var $type="THtmlForm";
        
                
        function THtmlForm($sUrl, $sMethod="POST")
        {
                $this->url = $sUrl;
                $this->method = $sMethod;
                $this->index = 0; //Indice de los campos gregados a $this->oItem
        }
        
        function SetTableStyle(&$table)
        {
                $this->tablestyle=$table;
        }

        function SetExtBorder($nBr)
        {
                $this->extborder=$nBr;
        }       
                
        function SetColumnIDStyles($aID)
        {
                $this->col_id_style = $aID;
        }

        function SetFormName($sFname)
        {
                $this->form_name = $sFname;
        }
        
        function SetTarget($sTarname)
        {
                $this->$target = $sTarname;
        }
        
        function SetTitles( $aTitles)
        {
                $this->titles = $aTitles;
        }

        function SetValues( $aValues )
        {
                $this->values = $aValues;
        }       
        
        function AddItem( &$oItem )
        {
                $this->oItems[$this->index++]=&$oItem;  //Agrega referencias a los objetos que descienden de "TInputType"
        }

        function AddTitle( $sTxT )
        {
                $this->titles[count($this->titles)]=$sTxT;
        }

        function SetEnabled()
        {               
                for($i=0; $i<$this->index; $i++) 
                        if($this->oItems[$i]) $this->oItems[$i]->SetEnabled();                  
        }       
        
        function SetReadOnly()
        {               
                for($i=0; $i<$this->index; $i++) 
                        if($this->oItems[$i]) $this->oItems[$i]->SetReadOnly();                 
        }       
        
        function SetDisabled()
        {
                for($i=0; $i<$this->index; $i++) $this->oItems[$i]->SetDisabled();                      
        }
        
        function GetScript()
        {
                global $class_img_path;
                global $sesion;

                $script = "<STYLE>\n\t";
                $script.= ".required {background-image:url(required.gif); background-position:top right; background-repeat:no-repeat;}\n";
                $script.= "</STYLE>\n\n";

                $script.= "<FORM " ;
                if(!empty($this->form_name)){$script.= "name='".$this->form_name."' ";}
                
                
                
                
                for($i=0; $i<$this->index; $i++)                                                
                  if($this->oItems[$i])
                        if($this->oItems[$i]->GetType() == "TInputTypeFile" )
                           {
                                        //$script.= " enctype='multipart/form-data' " ;
                                          $script.= " enctype=\"multipart/form-data\" ";
                                        break;
                            }
                        
                
                $script.= "method='".$this->method."' ";
                $script.= "action='".$this->url."' ";
                $script.= "validate=\"onchange\" invalidColor=\"yellow\" lang=\"es\" mark year4>\n\n";
                if(!empty($this->$target))              { $script.=" TARGET='".$this->$target."'";}             
                //Target debe contener el nombre del frame
         
         
         //Borde exterior
         if($this->extborder) 
         {
                $script.="<TABLE CELLPADING=0 CELLSPACING=0 BORDER=".$this->extborder." ";
                if(!empty($this->tablestyle->align))                    { $script.=" align='".$this->tablestyle->align          ."' "; }else{ $script.=" align='center' "; }
                if(!empty($this->tablestyle->valign))                   { $script.=" valign='".$this->tablestyle->valign        ."' "; }else{ $script.=" valign='bottom' "; }
                if(!empty($this->tablestyle->width))                    { $script.=" width='".$this->tablestyle->width          ."' "; }else{ $script.=" "; }
                if(!empty($this->tablestyle->bgcolor))          { $script.=" bgcolor='".$this->tablestyle->bgcolor      ."' "; }else{ $script.=" bgcolor='#FFFFFF' "; }         

                
                $script.="><TR><TD>\n\n\n";      
         }
         
         
         
                
                
                $script.="<TABLE CELLPADING=0 CELLSPACING=0 ";
                if(!empty($this->tablestyle->align))            { $script.=" align='".$this->tablestyle->align  ."' "; }else{ $script.=" align='center' "; }
                if(!empty($this->tablestyle->valign))           { $script.=" valign='".$this->tablestyle->valign."' "; }else{ $script.=" valign='middle' "; }
                if(!empty($this->tablestyle->border))           { $script.=" border='".$this->tablestyle->border."' "; }else{ $script.=" border='0' "; }
                if($this->extborder)
                {
                        $script.=" width='100%'";                       
                }
                else
                {
                        if(!empty($this->tablestyle->width))    { $script.=" width='".$this->tablestyle->width  ."' "; }else{ $script.=" "; }
                }
                if(!empty($this->tablestyle->bgcolor))          { $script.=" bgcolor='".$this->tablestyle->bgcolor      ."' "; }else{ $script.=" bgcolor='#FFFFFF' "; }
                $script.=">\n";
        
                
                $color1 = ( !empty($this->tablestyle->column_bg_color[0]) )?" bgcolor='".$this->tablestyle->column_bg_color[0]."'":" ";
                $color2 = ( !empty($this->tablestyle->column_bg_color[1]) )?" bgcolor='".$this->tablestyle->column_bg_color[1]."'":" ";
                
                $valign1=( !empty($this->tablestyle->column_valign[0]) )?" valign='".$this->tablestyle->column_valign[0]."'":" ";
                $valign2=( !empty($this->tablestyle->column_valign[1]) )?" valign='".$this->tablestyle->column_valign[1]."'":" ";

                $align1=( !empty($this->tablestyle->column_align[0]) )?" align='".$this->tablestyle->column_align[0]."'":" ";
                $align2=( !empty($this->tablestyle->column_align[1]) )?" align='".$this->tablestyle->column_align[1]."'":" ";
                


                $width1=( !empty($this->tablestyle->column_width[0]) )?" width='".$this->tablestyle->column_width[0]."'":" ";
                $width2=( !empty($this->tablestyle->column_width[1]) )?" width='".$this->tablestyle->column_width[1]."'":" ";




                $id1=( !empty($this->col_id_style[0]))?" ID='".$this->col_id_style[0]."' ":"";
                $id2=( !empty($this->col_id_style[1]))?" ID='".$this->col_id_style[1]."' ":"";
                //TInputTypeDate
                for($i=0; $i<$this->index; $i++)
                {               
                        
                  if($this->oItems[$i])
                        switch($this->oItems[$i]->GetType())
                        {
                                case "TInputTypeHidden" :               $script.= " ".$this->oItems[$i]->GetScript();
                                
                                                                                                break;
                                                                  
                                                                                  
                                
                                
                                case "TInputTypeSubmitCancel" : $script.="<TR BGCOLOR='".$this->tablestyle->column_bg_color[0]."' > \n";
                                                                                                $script.="\t<TD Align='center' VALIGN='middle' $id1 COLSPAN='2'> \n";
                                                                                                $this->oItems[$i]->SetStyle($this->col_id_style[1]);
                                                                                                $tag = "".$this->oItems[$i]->GetScript();
                                                                                                $target = $this->url."?sesion=".$sesion;                                        
                                                                                                
                                                                                                $newtag=str_replace("#",$target,$tag);                                                                          
                                                                                                
                                                                                                $script.= $newtag;                                      
                                                                                                $script.="\t</TD> \n";  
                                                                                                $script.="</TR> \n\t";
                                                                                                
                                                                                                break;                          

                                case "TInputTypeSubmit" :               $script.="<TR BGCOLOR='".$this->tablestyle->column_bg_color[0]."' > \n";
                                                                                                $script.="\t<TD Align='center' VALIGN='middle' $id1 COLSPAN='2'> \n";
                                                                                                $this->oItems[$i]->SetStyle($this->col_id_style[1]);
                                                                                                $tag = "".$this->oItems[$i]->GetScript();
                                                                                                $target = $this->url."?sesion=".$sesion;                                        
                                                                                                
                                                                                                $newtag=str_replace("#",$target,$tag);                                                                          
                                                                                                
                                                                                                $script.= $newtag;                                      
                                                                                                $script.="\t</TD> \n";  
                                                                                                $script.="</TR> \n\t";
                                                                                                
                                                                                                break;                                  
                                
                                case    "TInputTypeDate" or "TInputTypeDateTime":               
                                
                                                                                                $script.="<TR> \n";
                                                                                                $script.="\t<TD $align1 $valign1 $color1 $id1 $width1> \n";
                                                                                                $script.= "".$this->titles[$i];
                                                                                                $script.="\t</TD> \n";  
                                                                                                $script.="\t<TD $align2 $valign2 $color2 $id2 $width2>  \n";
                                                                                                $this->oItems[$i]->SetStyle($this->col_id_style[1]);
                                                                                                
                                                                                                $script.= str_replace("X#X#X",$this->form_name,$this->oItems[$i]->GetScript());                         
                                                                                                
                                                                                                $script.="\t</TD> \n";  
                                                                                                $script.="</TR> \n\t";
                                                                                                break;
                                
                                                                default :               $script.="<TR> \n";
                                                                                                $script.="\t<TD $align1 $valign1 $color1 $id1 $width1> \n";
                                                                                                $script.= "".$this->titles[$i];
                                                                                                $script.="\t</TD> \n";  
                                                                                                $script.="\t<TD $align2 $valign2 $color2 $id2 $width2> \n";
                                                                                                
                                                                                                if(empty($this->oItems[$i]->id_style))
                                                                                                  $this->oItems[$i]->SetStyle($this->col_id_style[1]);
                                                                                                
                                                                                                
                                                                                                
                                                                                                
                                                                                                $script.= "".$this->oItems[$i]->GetScript();
                                                                                                $script.="\t</TD> \n";  
                                                                                                $script.="</TR> \n\t";

                        }
                        
                        
                        
                        /*
                        
                        if($this->oItems[$i]->GetType() == "TInputTypeHidden")  
                        {
                                $script.= " ".$this->oItems[$i]->GetScript();
                        }
                        else
                        
                        {
                        
                                if($this->oItems[$i]->GetType() == "TInputTypeSubmitCancel")
                                {
                                        $script.="<TR BGCOLOR='silver' > \n";
                                        $script.="\t<TD Align='center' VALIGN='middle' $id1 COLSPAN='2'> \n";


                                        $this->oItems[$i]->SetStyle($this->col_id_style[1]);
                                        
                                        $tag = "".$this->oItems[$i]->GetScript();
                                        $target = $this->url."?sesion=".$sesion;                                        
                                        $newtag=str_replace("#",$target,$tag);                                                                          
                                        $script.= $newtag;                                      
                                        $script.="\t</TD> \n";  
                                        $script.="</TR> \n\t";

                                }
                                else
                                {
                                        $script.="<TR> \n";
                                        $script.="\t<TD $align1 $valign1 $color1 $id1> \n";

                                        $script.= "".$this->titles[$i];

                                        $script.="\t</TD> \n";  
                                        $script.="\t<TD $align2 $valign2 $color2 $id2> \n";

                                        $this->oItems[$i]->SetStyle($this->col_id_style[1]);
                                        $script.= "".$this->oItems[$i]->GetScript();

                                        $script.="\t</TD> \n";  
                                        $script.="</TR> \n\t";
                                }
                        }
                        */
                        
                }
                $script.="\n</TABLE>  <!-- Fin Forma de Captura-->\n\n\n";              
                
                
                

                
                 if($this->extborder)
                 {              
                        $script.=" </TD></TR></TABLE>  <!-- Fin Marco Externo -->\n\n\n";        
                 }              
                
                $script.="</FORM>\n\n";
                
                
                $not_wanted = array("TInputTypeHidden", "TInputTypeSubmitCancel", "TInputTypeSubmit","");
                $first_field = "";
                                for($i=0; $i<$this->index; $i++)                                                
                                  if($this->oItems[$i])
                                        if(  ! in_array($this->oItems[$i]->GetType(),$not_wanted)           )
                                           {

                                                        $first_field = $this->oItems[$i]->varname;  
                                                        break;
                                        }
                
                
                
                
                if(!empty($first_field))
                $script.="<SCRIPT>       if(document.".$this->form_name.".elements['".$first_field."']) { document.".$this->form_name.".elements['".$first_field."'].focus(); } </SCRIPT>\n";
                $script.="<SCRIPT src=\"".$class_img_path."validation.js\" language=\"JScript\"></SCRIPT> \n";
                
                
                $this->script=$script;
                return($this->script);          
                
                        
        }       



}


?>