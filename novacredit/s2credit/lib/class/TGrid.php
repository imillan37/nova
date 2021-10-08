<?
class TColumn
{
        var $title;                     //Encabezados
        var $titlebgcolor;

        var $align;                     //Alineación, color de fondo y estilo
        var $valign;
        var $bgcolor;
        var $styleid;
        
        var $colorsaldonegativo ='RED';

        var $content;           //Contenido

        var $onmouseover;       //Eventos
        var $onmouseout;
        var $onclick;

//      var $transfer = "strtoupper('*')";
        var $transfer = "";



        function TColumn($txt, $color)
        {
                $this->title=$txt;
                $this->titlebgcolor=$color;
        }

        function SetTransfer($txt)
        {
                $this->transfer=$txt;
        }

        function SetAlign($txt)
        {
                $this->align=$txt;
        }

        function SetValign($txt)
        {
                $this->valign=$txt;
        }

        function SetBgColor($txt)
        {
                $this->bgcolor=$txt;
        }

        function SetStyle($txt)
        {
                $this->styleid=$txt;
        }

        function SetContent($txt)
        {
                $this->content=$txt;
        }
        
        function OnMouseOver($action)
        {
                $this->onmouseover=$action;
        }

        function OnMouseOut($action)
        {
                $this->onmouseout=$action;
        }

        function OnClick($action)
        {
                $this->onclick=$action;
        }
        
        function SetSaldoNegativeNumberColor($color)
        {
                $this->colorsaldonegativo=$color;
    }
        
        function ShowTitles()
        {
                $script="\t\t<TH ALIGN='center' ";

                if(!empty($this->titlebgcolor))
                {
                        $script.=" BGCOLOR='".$this->titlebgcolor."' ";
                }
                $script.=">".$this->title."</TH>\n";
                return($script);
        }

        function Show()
        {
                        $this->script="";


                        $script="\t\t<TD ";

                        //Atributos visuales.

                        if(!empty($this->align   )){ $script.=" ALIGN='".       $this->align   ."' ";   }
                        if(!empty($this->valign  )){ $script.=" VALIGN='".      $this->valign  ."' ";   }
                        if(!empty($this->bgcolor )){ $script.=" BGCOLOR='".     $this->bgcolor ."' ";   }
                        if(!empty($this->styleid )){ $script.=" ID='".          $this->styleid ."' ";   }

                        //Eventos

                        if(!empty($this->onmouseover)){ $script.=" onmouseover='javascript:".$this->onmouseover ."' ";  }
                        if(!empty($this->onmouseout     )){ $script.=" onmouseout='javascript:" .$this->onmouseout ."' ";       }
                        if(!empty($this->onclick        )){ $script.=" onclick='javascript: ".$this->onclick ."' ";     }

                        $script.=">";
                        if(empty($this->transfer))
                                $script.=$this->content;
                        else
                        {
                                str_replace("'", "´",$this->transfer);
                                $_src_code = '$script.= '.str_replace('*', $this->content,$this->transfer).'; ';
                                eval($_src_code);
                        }
                        
                        $script.="</TD>\n";
                        return($script);
        }

}//EndClass

//¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯//
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ TGrid ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
//_____________________________________________________________________________________________//
class TGrid
{
        var $name;                      //Nombre del objeto
        var $db;                    // Referencia al obj ADO;
        var $sql;                       // SQL
        var $custom_sql;    //
        var $c_sql;
        var $table;                     // Tabla (para EDIT / ADD / DEL MODE)
        var $rows_page=15;      // Renglones por pagina;
        var $lastrow;           //Ultimo renglon de la pagina
        var $actual_register;
        var $order;
        var $pags = 1;
        var $reg_num = 0;
        
        var $method = 'POST';
        
        var $fields;
        
        var $filterfields;
        var $filtertitles;
        
        var $filter;
        var $autofilter;
        var $debug=false;

        var $pkey = "";
        var $pkey_fields = array();
        var $pkey_order  = array();
        var $pkey_values = array();

        var $detail = true;
        var $detail_fields      = array();
        var $detail_titles      = array();
        var $detail_align       = array();
        var $detail_width;      
        var $detail_width_titles;
        

        var $default_start_page = 'first'; //[first, last]
        
        
        
        var $aTitles                    =array();       //Titulos
        var $aColums                    =array();
        var $aColAlign                  =array();  // (C/R/L)
        var $aColFormat                 =array();  
        var $aCondColumnColor   =array(); 
        var $aColTransfer               =array();  
        
        var $aDetFormat=array();  
        var $aDetTransfer=array();  
        
        var $del  = false;
        var $edit = false;
        var $add  = false;
        var $serch = true;
        var $clasificacion = false;
        
        var $clasificacion_js_script; // Clasificacion  
        var $clasificacion_lista_ids_registros;
        var $clasificacion_field_registros;

        var $numered = true;
        var $align="center";
        var $valign="middle";
        var $border="0";
        var $width="100%";
        var $height;

        var $cellpading  = "0";
        var $cellspacing = "0";

        var $title;
        var $title_id;

        var $bgcolor;
        var $styleid;
        var $color1;
        var $color2;
        var $extborder;
        var $generalcolor = "Silver";
        var $titlebgcolor = "#CACAFF";
        var $lastrowcolor;


        var $onmouseover;                               //Eventos
        var $onmouseout;
        var $onclick;
        var $addkey;


    var $del_img;                                       //iconos
    var $edit_img;
    var $add_img;
    var $forward_img;
    var $backward_img;
    var $last_img;
    var $last_alt;
    var $first_img;
    var $first_alt;
    var $serch_img;
    var $unserch_img;
    var $serch_alt;
    var $close_detail;

    var $order_asc_img;
    var $order_dsc_img;
    var $order_asc_alt;
    var $order_dsc_alt;

    var $del_alt;                                       //iconos
    var $edit_alt;
    var $add_alt;
    var $forward_alt;
    var $backward_alt;

    var $target;
    var $controlaction;
    var $parameters  = array();
    var $paramvalues = array();
    var $top = false;

        var $aFooter;
        var $aFooterDEC;
        var $aResults;
        var $ModifyRowsPerPage=true;

        // Funcion Saldo
        var $ColumnaSaldo; 
        var $ColumnasSigno;
       
    var $SIN_ORDER = false;   
        
        
        
        
        function GetAlias($field)
        {

                        $field=str_replace("'","`", $field);
                        $alias=strtolower($this->custom_sql);
                        $_field=strtolower($field);
                        $npos = strpos($alias,strtolower($field));
                        
                        
                        
                        //echo "GetAlias : field = <B>".$field."</B><HR>";
                        //echo "GetAlias : custom_sql = <B>".nl2br($alias)."</B><HR>";
                        //echo "GetAlias : nposl = <B>".$npos."</B><HR>";
                        
                        
                        if(!$npos)
                        {       
                                //echo ( "GetAlias : No está el campo <B> ".$field."</B> en <B>".$alias."</B>" );
                                return($field);
                        }

                        
                        
                        
                        $alias = substr($alias,($npos+strlen($field)));         
                        //echo "GetAlias : field = ".nl2br($field)."<HR>";
                        //echo "GetAlias : alias = ".nl2br($alias)."<HR>";
                        $n_pos = strpos($alias,'from');                 
                        $m_pos = strpos($alias,',');                    
                        
                        if($m_pos === false)$m_pos = 999999;
                        
                        
                        $min=min($n_pos,$m_pos);


                        $alias= substr($alias,0,$min); 
                        
                        
                        //echo "GetAlias : n_pos =$n_pos  ,m_pos = $m_pos, min = $min = ".nl2br($alias)."<HR>";
                        //die();
                        
                        
                        /*
                        $npos = strpos($alias,',');                     
                        if($npos) $alias= substr($alias,0,$npos); 
                        
                        */
                        
                        $npos=strpos(strtolower($alias)," as ");
                        if(!($npos===false))
                                $alias=($npos)?(substr($alias,$npos+3)):("");
                        else
                                return($field);
                        
                        
                        
                        $npos=strpos(strtolower($alias),"," );
                        
                        $alias=($npos)?(substr($alias,0,$npos)):($alias);
                        $alias=trim($alias);
                
                        //echo "GetAlias :  alias(2) =  &raquo;".$alias."&laquo;<HR>";
                        //die();

                return($alias);
        }


        function TGrid(&$odb,$fields,$table,$aTitles,$filter,$lr,$ar,$ob)
        {
                global $addkey, $HTTP_USER_AGENT;

                global $_s1;
                global $_s2;
                global $_s3;
                global $_clasificacion1;                
                
                
               $this->SIN_ORDER = false;
                $this->addkey = $addkey;

                if( empty($odb)  )               {              die("No se econtró la conexión a la base de datos.");   }
                if( empty($table)   )    {              die("No se econtró la sentencia la tabla.");                    }
                if( count($fields)==0   ){              die("No se econtráron los campos de la tabla.");                }
                $this->db  = &$odb;
                
                $this->fields = $fields;
                
                $this->filterfields = $this->fields;
                
                
                $this->table = $table;
                $this->filter = str_replace ("`", "'", trim($filter) );

                if ( (!empty($_s1)) and (!empty($_s2)) and (!empty($_s3)) )
                {

                        
                        if( (strpos($_s3,"/") == 2) and (strlen($_s3) == 10) ) //Es una fecha
                        {
                                $_s3 = gfecha($_s3);
                        }
                                



                        $npos=strpos(strtolower($_s1)," as ");
                        if($npos)
                                {
                                        //$_s4 = substr($_s1,$npos+3); // Con el alias
                                        $_s1 = substr($_s1,0, $npos);  // Con el campo  (Si no se usa GROUP funciona con el campo)
                                }

                        $_s3=str_replace("*", "%", $_s3);


                        $autofilter ="(".$_s1." ".$_s2." '".trim($_s3)."')";
                        $autofilter = str_replace("`", "'", $autofilter);

                                        
                        
                        $this->autofilter = $autofilter;

                         //echo "<HR> <B>AUTOFILTER KEY :</B> _s1 = $_s1 ".$this->autofilter." <HR> <BR>";
                         //echo "<HR> <B>AUTOFILTER KEY :</B> _s4 = $_s4 ".$this->autofilter_alias." <HR> <BR>";
                }

                //SQL Básico sin customSQL
                
                $sql = "SeLeCT ";

                for($i=0; $i<count($fields);$i++)
                {
                        $sql .= $fields[$i];
                        $sql .= ($i<(count($fields)-1))?(", "):(" ");
                }

                $sql .= " FROM ".$this->table." ";


                if(!empty($this->filter))
                {
                        $sql.=  " WHERE ".$this->filter. " ";


                        if(!empty($autofilter))
                        {
                                $sql.=  " and ".$autofilter;
                        }


                }
                else
                {
                  if(!empty($autofilter))
                        {
                                $sql.=  " WHERE ".$autofilter. " " ;     //  Modif : Viernes, 05 de Septiembre de 2003  
                        }
                }
                
        
                $this->sql = $sql;
                $this->aTitles = $aTitles;
                $this->filtertitles = $aTitles;
                if(count($this->detail_titles)==0){$this->detail_titles=$aTitles;}


                $this->lastrow             = (empty($lr))?('0'):($lr);
                $this->actual_register = (empty($ar))?('0'):($ar);
                $this->order = $ob;

                //debug($this->sql);
                return(1);
        }

        function setSinOrder(){
	        
	        $this->SIN_ORDER = true;
	        
        }

        function SetFieldFormat($aMask)
        {
                $this->aColFormat = $aMask;
        }

        function SetDetailFormat($aMask)
        {
                $this->aDetFormat = $aMask;
        }

        function SetFooterFormat($aMask)
        {
                $this->aFooterFormat = $aMask;
        }
        
        function SetHTTPMethod($txt)
        {
                $this->method=$txt;
        }




        function FieldFormat($data,$mask)
        {
                
                if(is_array($mask))
                {                               
                        $result = $mask[($data)][0].$data.$mask[($data)][1];                            
                }
                                
                
                
                if(is_string($mask))
                {
                                $mask2='';      
                                //------------------------------------------------------------
                                // Detección de la formato para flotantes
                                $pos=strpos(strtoupper($mask),'F');
                                if ( ($pos === 0) and strlen($mask)<=3 )                        
                                {
                                                $DECIMALS = substr($mask,1);
                                                $mask='F';
                                }

                                //------------------------------------------------------------                          
                                // Colorear determinados tokens con un cierto color cadauno
                                // COLORIZE(<TOKEN1.COLOR1>,<TOKEN2.COLOR2>,...)
                                // Ej. COLORIZE( aceptado.blue, cancelado.red,...)
                                
                                $pos=strpos(strtoupper($mask),'COLORIZE');
                                if (!($pos === false) )                 
                                {
                                                $ARGS = trim(substr($mask,8));                                          
                                                $ARGS = str_replace("(","",$ARGS);
                                                $ARGS = str_replace(")","",$ARGS);
                                                                                                
                                                $color_hash = array();
                                                $color_list = explode(',', $ARGS);

                                                foreach($color_list as $tok)
                                                {                                                 
                                                    list($key,$val)=explode('.', $tok);
                                                    $color_hash[$key] = $val;                                                                                                                                      
                                                }
                                
                                                $premask='COLORIZE';
                                }


                                //------------------------------------------------------------                          
                                // Conversión a la primera letra mayuscula y con estilo CSS
                                // UCFIRSTX(<FOMATO CSS>)
                                $pos=strpos(strtoupper($mask),'UCFIRSTX');
                                if (!($pos === false) )                 
                                {
                                                $ARGS = trim(substr($mask,8));                                          
                                                $ARGS = str_replace("(","",$ARGS);
                                                $ARGS = str_replace(")","",$ARGS);
                                                
                                                list($next_mask, $CHARS)=explode(",",$ARGS);
                                                $mask = trim($next_mask);
                                                $CHARS = 1 * $CHARS;
                                                $premask='UCFIRSTX';
                                }
                                
        
        
        
        
        
                                //------------------------------------------------------------                          
                                // Conversión a la primera letra mayuscula simple
                                // UCFIRST()
                                $pos=strpos(strtoupper($mask),'UCFIRST');
                                if (!($pos === false) )                 
                                {
                                                $ARGS = trim(substr($mask,7));                                          
                                                $ARGS = str_replace("(","",$ARGS);
                                                $ARGS = str_replace(")","",$ARGS);
                                                
                                                list($next_mask, $CHARS)=explode(",",$ARGS);
                                                $mask = trim($next_mask);
                                                $CHARS = 1 * $CHARS;
                                                $premask='UCFIRST';
                                }



                                //------------------------------------------------------------                          
                                // Conversión a la primera letra de cada palabra mayuscula simple
                                // UCWORDS()
                                $pos=strpos(strtoupper($mask),'UCWORDS');
                                if (!($pos === false) )                 
                                {
                                                $ARGS = trim(substr($mask,7));                                          
                                                $ARGS = str_replace("(","",$ARGS);
                                                $ARGS = str_replace(")","",$ARGS);
                                                
                                                list($next_mask, $CHARS)=explode(",",$ARGS);
                                                $mask = trim($next_mask);
                                                $CHARS = 1 * $CHARS;
                                                $premask='UCWORDS';
                                }


                                //------------------------------------------------------------                          
                                // Deteccion de formato para truncar cadenas
                                // TRUNC(<FOMATO>,<nCARACTERES>)
                                $pos=strpos(strtoupper($mask),'TRUNC');
                                if (!($pos === false) )                 
                                {
                                                $ARGS = trim(substr($mask,5));                                          
                                                $ARGS = str_replace("(","",$ARGS);
                                                $ARGS = str_replace(")","",$ARGS);
                                                
                                                list($next_mask, $CHARS)=explode(",",$ARGS);
                                                $mask = trim($next_mask);
                                                $CHARS = 1 * $CHARS;
                                                $premask='TRUNC';
                                }                               
                                //------------------------------------------------------------                          
                                // Deteccion de formato para ocultar ultimos caracteres
                                // HIDELAST([FOMATO],[MASKCHAR],[nCARACTERES], <SPILTCHAR>, <SPILTNUM>)
                                $pos=strpos(strtoupper($mask),'SHOWLAST');
                                if (!($pos === false) )                 
                                {
                                                $ARGS = trim(substr($mask,8));                                          
                                                $ARGS = str_replace("(","",$ARGS);
                                                $ARGS = str_replace(")","",$ARGS);
                                                
                                                list($next_mask, $SPCHAR, $CHARS, $SPLITCHAR, $SPLITNUM) = explode(",",$ARGS);
                                                $mask = trim($next_mask);
                                                $CHARS = 1 * $CHARS;
                                                $premask='SHOWLAST';
                                }                                       
                                //------------------------------------------------------------                          
                                // Deteccion de formato para ocultar primeros caracteres
                                // SHOWFIRST([FOMATO],[MASKCHAR],[nCARACTERES], <SPILTCHAR>, <SPILTNUM>)                                
                                $pos=strpos(strtoupper($mask),'SHOWFIRST');
                                if (!($pos === false) )                 
                                {
                                                $ARGS = trim(substr($mask,9));                                          
                                                $ARGS = str_replace("(","",$ARGS);
                                                $ARGS = str_replace(")","",$ARGS);
                                                
                                                list($next_mask, $SPCHAR, $CHARS, $SPLITCHAR, $SPLITNUM) = explode(",",$ARGS);
                                                $mask = trim($next_mask);
                                                $CHARS = 1 * $CHARS;
                                                $premask='SHOWFIRST';
                                }                                       
                                //------------------------------------------------------------
                                // Deteccion de formato para subdividir la cadena en grupos
                                // SPLIT([FOMATO],[nCARACTERES], [SPILTCHAR])                                                           
                                
                                $pos=strpos(strtoupper($mask),'SPLIT');
                                if (!($pos === false) )                 
                                {

                                                $ARGS = trim(substr($mask,5));
                                                $ARGS = str_replace("(","",$ARGS);
                                                $ARGS = str_replace(")","",$ARGS);
                                                
                                                list($next_mask, $SPLITCHAR, $SPLITNUM) = explode(",",$ARGS);
                                                $mask = trim($next_mask);
                                                $CHARS = 1 * $CHARS;
                                                $premask='SPLIT';
                                                
                                }                               
                                //------------------------------------------------------------                          
                                // Deteccion de formato para reemplazar los caracteres de la cadena
                                // REPLACE([FOMATO],[FINDCHR],[REPLCECHR])                              
                                $pos=strpos(strtoupper($mask),'REPLACE');
                                if (!($pos === false) )                 
                                {

                                                $ARGS = trim(substr($mask,7));
                                                $ARGS = str_replace("(","",$ARGS);
                                                $ARGS = str_replace(")","",$ARGS);
                                                
                                                list($next_mask, $FINDCHR, $REPLCECHR) = explode(",",$ARGS);
                                                $mask = trim($next_mask);
                                                $premask='REPLACE';
                                                
                                }                               
                                
                                
                        }       
                                
                                
                                
                        //------------------------------------------------------------                          
                        // Formatos preprocesados
                                
                        if(!empty($premask))
                        {
                                
                                        switch($premask)
                                        {
                                                case 'TRUNC'    : $data = substr($data,0,$CHARS); break;
                                                case 'REPLACE'  : $data = str_replace($FINDCHR, $REPLCECHR, $data); break;


                                                case 'COLORIZE' : $data = "<SPAN STYLE=' color : ".$color_hash[$data].";' ><B>".ucfirst($data)."<B></SPAN>"; break;


                                                case 'UCFIRST'  : $data = ucfirst($data); break;


                                                case 'UCFIRSTX'  : $data = "<SPAN STYLE='".$ARGS."' >".ucfirst($data)."</SPAN>"; break;


                                                case 'UCWORDS'  : $data = ucwords(strtolower($data)); break;

                                                                
                                                case 'SHOWLAST' : {
                                                                                        $slen = strlen($data);
                                                                                        $from = ($slen-$CHARS <0)?(0):($slen-$CHARS);
                                                                                        
                                                                                        if($from) 
                                                                                                $data = str_repeat($SPCHAR,($from-1)).substr($data,$from,$slen); 
                                                                                        
                                                                                        $spldata = "";
                                                                                        if(!empty($SPLITCHAR) and ($SPLITNUM>0))
                                                                                        {
                                                                                                for($i=0; $i<$slen; $i+=$SPLITNUM)                                                                                              
                                                                                                        $spldata .= substr($data,$i,$SPLITNUM).$SPLITCHAR; 

                                                                                                $spldata = substr($spldata,0,(strlen($spldata)-1));
                                                                                                $data = $spldata;
                                                                                        }
                                                                                                                                                                                
                                                                                        break;
                                
                                                                                 }
                                                
                                                case 'SHOWFIRST' : { 
                                                                                        $slen = strlen($data);
                                                                                        
                                                                                        
                                                                                        if($CHARS) 
                                                                                                $data= substr($data,0,$CHARS ).str_repeat($SPCHAR,($slen-$CHARS)); 

                                                                                        $spldata = "";
                                                                                        if(!empty($SPLITCHAR) and ($SPLITNUM>0))
                                                                                        {
                                                                                                for($i=0; $i<$slen; $i+=$SPLITNUM)                                                                                              
                                                                                                        $spldata .= substr($data,$i,$SPLITNUM).$SPLITCHAR; 

                                                                                                $spldata = substr($spldata,0,(strlen($spldata)-1));
                                                                                                $data =$spldata;
                                                                                        }

                                                                                                                                                                        
                                                                                        break;
                                
                                                                                 }              
                                                                                 
                                                case 'SPLIT' : {        $slen = strlen($data);
                                                                                        $spldata = "";
                                                                                        if(!empty($SPLITCHAR) and ($SPLITNUM>0))
                                                                                        {
                                                                                                for($i=0; $i<$slen; $i+=$SPLITNUM)                                                                                              
                                                                                                        $spldata .= substr($data,$i,$SPLITNUM).$SPLITCHAR; 

                                                                                                $spldata = substr($spldata,0,(strlen($spldata)-1));
                                                                                                $data = $spldata;
                                                                                        }
                                                                                                                                                                                
                                                                                        break;
                                                                                 }      
                                                                                 

                                                                                         
                                                                                 
                                
                                        }
                                
                }                               
                                
                                
                                // Formatos finales
                if(is_string($mask))
                {                               
                                switch(strtoupper($mask))
                                {
                                    case 'F' : $result= number_format($data,$DECIMALS); break;
                                                                                                    
                                        case 'N' : $result= ucwords(strtolower($data)); break;  
                                        case 'NB' : $result= "<B>".ucwords(strtolower($data))."</B>"; break;    
                                    
                                        case 'L' : $result= strtolower($data); break;   
                                        case 'LB' : $result= "<B>".strtolower($data)."</B>"; break;                                         
                                    
                                        case 'U' : $result= strtoupper($data); break;   
                                        case 'UB' : $result= "<B>".strtoupper($data)."</B>"; break;     

                                    case 'PIF'  : $result= number_format($data,2)."%"; break;
                                    
                                    case 'PIBF' : $result= "<B>".number_format($data,2)."%</B>"; break;
                                    
                                    case 'PI'  : $result= number_format($data,0)."%"; break;
                                    
                                    case 'PIB' : $result= "<B>".number_format($data,0)."%</B>"; break;
                                    
                                    case 'M'  : $result= " $".number_format($data,2); break;
                                    
                                    case 'MB' : $result= "<B> $".number_format($data,2)."</B>"; break;
                                    
                                    case 'MX' : if($data<0)
                                                {
                                                                $result= "<FONT COLOR='RED'> $".number_format($data,2)."</FONT>"; break;
                                                }
                                                else
                                                {
                                                        $result= " $".number_format($data,2); break;
        
                                                }       
                                    case 'MBX' : if($data<0)
                                                        {
                                                                $result= "<B><FONT COLOR='RED'> $".number_format($data,2)."</FONT></B>"; break;
                                                        }
                                                        else
                                                        {
                                                                $result= "<B> $".number_format($data,2)."</B>"; break;
        
                                                        }                                                               
                                    case 'I' : $result= number_format($data,0); break;

                                    case 'IB' : $result= "<B>".number_format($data,0)."</B>"; break;



                                    case 'D' :  { 
                                    
                                                         if( strlen($data) >= 10)
                                                                   {
                                                                                $anio=substr($data,0,4);         

                                                                                $mes=substr($data,5,2);
                                                                                if(strpos ($mes,"-"))   $mes=substr($data,5,1);

                                                                                $dia=substr($data,8,2);
                                                                                if(strpos ($dia,"-"))           $dia=substr($dia,0,1); 

                                                                                $result = $dia."/".$mes."/".$anio;
                                                                        }
                                                                        else
                                                                        $result = "";
                                                                        
                                                                        break;
                                                                                
                                                         }                                  
                                    case 'DT':  {       
                                                                
                                                                         if( (strlen(trim($data)) >= 10 ) and (strlen(trim($data)) <= 19 ) )
                                                                         {
                                                                                        $anio=substr($data,0,4);         

                                                                                                $mes=substr($data,5,2);                                                         
                                                                                        if(strpos ($mes,"-"))   $mes=substr($data,5,1);

                                                                                        $dia=substr($data,8,2);
                                                                                        if(strpos ($dia,"-"))           $dia=substr($dia,0,1); 

                                                                                        $hrs = substr($data,10,6)." hrs.";


                                                                                           $result =  $dia."/".$mes."/".$anio." ".$hrs;
                                                                                           //" data[".strlen(trim($data))."] = [$data] ";
                                                                          }
                                                                          else
                                                                                $result = "";
                                                                        
                        
                                                                        break;
                                                                        
                                                         }





                                    case 'DE':  {       
                                                                
                                                                         if( (strlen(trim($data)) >= 10 ) and (strlen(trim($data)) <= 19 ) )
                                                                         {
                                                                                        list($ganio,$gmes,$gdia)= split("-",substr($data,0,10));
                                                                                        
                                                                                        $adia[0] = "Domingo";
                                                                                        $adia[1] = "Lunes";
                                                                                        $adia[2] = "Martes";
                                                                                        $adia[3] = "Mi&eacute;rcoles";
                                                                                        $adia[4] = "Jueves";
                                                                                        $adia[5] = "Viernes";
                                                                                        $adia[6] = "S&aacute;bado";

                                                                                        $ames[1] = "Enero";
                                                                                        $ames[2] = "Febrero";
                                                                                        $ames[3] = "Marzo";
                                                                                        $ames[4] = "Abril";
                                                                                        $ames[5] = "Mayo";
                                                                                        $ames[6] = "Junio";
                                                                                        $ames[7] = "Julio";
                                                                                        $ames[8] = "Agosto";
                                                                                        $ames[9] = "Septiembre";
                                                                                        $ames[10]= "Octubre";
                                                                                        $ames[11]= "Noviembre";
                                                                                        $ames[12]= "Diciembre";









                                                                        
                                                                                        $hrs = (substr($data,10,6)!="")?(substr($data,10,6)." hrs."):("");
                                
                                                                                        
                                                                                        $gW= strftime("%w",mktime(0,0,0,$gmes,$gdia,$ganio));   // Dia de la semana
                                                                                        $fecha_ext = $adia[$gW]." ".$gdia." de ".$ames[($gmes*1)]." de ".$ganio.$hrs;

                                                                                        return($fecha_ext);
                                                                          }
                                                                          else
                                                                                $result = "";
                                                                        
                        
                                                                        break;
                                                                        
                                                         }






                                                         
                                   //String Normal 
                                    case 'SN':   $result=$data;  break;
                                                        
                                   //String Normal Red
                                    case 'SNR':  $result= "<FONT COLOR='Red'>".$data."</FONT>";  break;                     
                                        
                                    //String Normal Blue
                                    case 'SNB':  $result= "<FONT COLOR='Blue'>".$data."</FONT>";         break; 
                                                                    
                                     //String Bold 
                                    case 'SB':   $result="<B>".$data."</B>";     break;

                                    //String Bold Red
                                    case 'SBR':  $result="<B> <FONT COLOR='Red'> ".$data." </FONT> </B>";        break; 
                                    
                                    //String Bold Blue
                                    case 'SBB':  $result="<B> <FONT COLOR='Blue'>".$data." </FONT>  </B>";       break;                             

                                    //String Lower
                                    case 'SL':  $result= strtolower($data);      break;                                     
                                   
                                    //String Lower Bold
                                    case 'SLB':  $result= "<B>".strtolower($data)."</B>";        break; 
                                    
                                    //String Upper              
                                    case 'SU':  $result= strtoupper($data);      break;

                                    //String Upper      Bold
                                    case 'SUB':   $result="<B>".strtoupper($data)."</B>";        break;

                                    case '%':   $result="<B>".number_format($data,2)."%</B>";    break;
                                    

                                    default:       $result= $data;       break;
                                }
                                
                                
                                

                                
                                
                                
                                        
                                
                                        /*                              
                                                                        F#              //FLOAT #=number of decimals
                                                                        I                       //INTEGER
                                                                        M                       // Money ($+Float+2 decimals)

                                                                        D                       //DATE BRITISH  "DD/MM/AAAA"
                                                                        DT                      //

                                                                        SN              // String Normal
                                                                        SB              // String BOLD
                                                                        SU              // String Upper Case
                                                                        SL              // String Lower Case
                                                                        
                                                                        SUB             //      SU+SB
                                                                        SLB             //      SL+SB
                                                                        %                       Prrcentage Bold 2 decimales

                                        */

                }

        return($result);
        }



        function SetCustomSQL($cSql)
        {
                $this->custom_sql= $cSql;

                $_posfrom = strpos(strtoupper($cSql), "FROM");
                $_zql = "SELECT COUNT(*) ".substr($cSql, $_posfrom, (strlen($cSql)-1));
                
                $_poshvng = strpos(strtoupper($_zql), "HAVING");
                if($_poshvng)
                $_zql = substr($_zql,0,$_poshvng);
                

                $this->db->Execute($this->custom_sql);
                $_zql="SELECT FOUND_ROWS();";
        


                $rs = $this->db->Execute($_zql);
                if($rs)
                {
                        $this->reg_num = max($rs->fields[0],$rs->_numOfRows);
                
                // Si tiene GROUP, podría anular el efecto deseado de la suma.
                }

                if($this->rows_page) $this->pags =  ceil($this->reg_num / $this->rows_page);

        }


        function SetGeneralColor($cColor)
        {
                $this->generalcolor = $cColor;
        }

        function SetLastRowColor($cColor)
        {
                $this->lastrowcolor = $cColor;
        }





        function GetKeyFields()
        {
                        if(count($this->pkey_fields)==0)  //Obtener campos llave
                        {
                                $i=0;
                                $keysql = "SHOW KEYS FROM ".$this->table;

                                //debug("[keysql (939)] : ".$keysql);
                                //die();
                                

                                $rs=$this->db->Execute($keysql);
                                if($rs)
                                {
                                        while(! $rs->EOF )
                                        {
                                                if($rs->fields[2] == 'PRIMARY' )
                                                {
                                                        $this->pkey_fields[$i] = $rs->fields[4];
                                                        $this->pkey_order[$i]  = ($rs->fields[3]-1);
                                                        $i++;

                                                }

                                                //echo "<HR>".print_r($this->pkey_fields). "<HR>";

                                                $rs->MoveNext();

                                        }
                                }
                        }

        }


        function SetEditMode($nBOOL)
        {

                $this->GetKeyFields();

                if(count($this->pkey_fields)>0)         //Solo si hay llave primaria
                {
                        if(!empty($this->table))                //Solo si se ha establecido la tabla
                        {
                                if($nBOOL)
                                        $this->edit=true;
                                else
                                        $this->edit=false;
                        }
                        else
                                $this->edit=false;
                }


        }

        function SetDelMode($nBOOL)
        {
                $this->GetKeyFields();

                if(count($this->pkey_fields)>0)     //Solo si hay llave primaria
                {
                        if(!empty($this->table))                //Solo si se ha establecido la tabla
                        {
                                if($nBOOL)
                                        $this->del=true;
                                else
                                        $this->del=false;
                        }
                        else
                                $this->del=false;
                }
                
                //error_alert("Advertencia: Eliminara permanentemente el usuario");
                
                
                
                
        }

        function SetAddMode($nBOOL)
        {
                $this->add = $nBOOL;
        }

        function SetSearchMode($nBOOL)
        {
                $this->serch = $nBOOL;
        }



        function SetSerchMode($nBOOL)
        {
                $this->serch = $nBOOL;
        }

        function SetClasificacionMode($nBOOL,$js_script="",$lista_ids_registros="",$field_registros="")
        {
                $this->clasificacion=$nBOOL;
                $this->clasificacion_js_script=$js_script;
                $this->clasificacion_lista_ids_registros=$lista_ids_registros;
                $this->clasificacion_field_registros=$field_registros;
        }

        function SetTarget($ctxt)
        {
                $this->target = $ctxt;
        }

        function SetControlAction($cfile)
        {
                $this->controlaction = $cfile;
        }

        function SetNumeration($BOOL)
        {
                $this->numered = $BOOL;
        }

        function SetParameters($aVars, $aValues)
        {
                $this->parameters  = $aVars;
                $this->paramvalues = $aValues;
        }

        function SetDetail($pkey, $cWidth)
        {
                $this->detail_width = $cWidth;                          //Ancho
                $this->pkey = $pkey;                                            // LLave primaria
                if(count($this->detail_fields)==0) {$this->detail_fields = $this->fields;}      //Campos
        }


        function SetDetailFields($afields, $atitles )
        {
                $this->detail_fields = $afields;        //Campos
                $this->detail_titles = $atitles;
        }
        
        
        //--------------------------------------------------------------------------------|
        // Poner para el filtro, campos distintos a los que por default son
        // los del arreglo de campos que se usan para la clausula ORDER BY
        // Wednesday, June 01, 2005     
        function SetAutoFilterFields($afields)
        {
                $this->filterfields = $afields;
        }
        
        function SetAutoFilterTitles($afields)
        {
                $this->filtertitles= $afields;
        }
        //--------------------------------------------------------------------------------


        function SetDetailAlign($aAlign)
        {
                $this->detail_align=$aAlign;
        }


        function SetRowsPerPage($rpp)
        {
                global $_rpp;
                
                if( ! is_int($_rpp))  $_rpp=(int) $_rpp;
                
                if( ! is_int($rpp))    $rpp=(int) $rpp;
                
                if( $this->ModifyRowsPerPage and $_rpp)
                        $this->rows_page=$_rpp;
                else
                        $this->rows_page=$rpp;
                        
                if($this->rows_page)
                        $this->pags =  ceil($this->reg_num / $this->rows_page);
        }

        function SetDelImg($cImg,$cAlt)
        {
        $this->del_img = $cImg;                                 //icono borrar
        $this->del_alt = $cAlt;
    }


        function SetAddImg($cImg,$cAlt)
        {
        $this->add_img = $cImg;                                 //icono agregar
        $this->add_alt = $cAlt;
    }


        function SetEditImg($cImg,$cAlt)
        {
        $this->edit_img = $cImg;                                //icono editar
        $this->edit_alt = $cAlt;
    }

        function SetSerchImg($cImg,$cImg2,$cAlt)
        {
        $this->serch_img = $cImg;       //icono borrar
        $this->unserch_img = $cImg2;
        $this->serch_alt = $cAlt;
    }

    function SetSerchClasificacionImg($cImg,$cImg2,$cAlt)
        {
                $this->serch_clasificacion_img = $cImg; //icono clasificacion
                $this->unserch_clasificacion_img = $cImg2;
                $this->serch_clasificacion_alt = $cAlt;
    }
        
        //iconos para indicar ordenamiento de columna
        function SetOrderImg($cImg1,$cImg2,$cAlt1,$cAlt2)
        {
        $this->order_asc_img = $cImg1;  
        $this->order_dsc_img = $cImg2;
        $this->order_asc_alt = $cAlt1;
                $this->order_dsc_alt = $cAlt2;
    }

        //-----------------------------------------------------------------------------------------------
        // Aplica funciones de transformación por columna o por celda de detalle.
        // Ejemplo : 
        // $obj->SetColTransfer(array("","strtolower('*')")); 
        // A la col :0 no le hace nada;
        // a la col :1 la transforma en minúsculas.
        // '*' simboliza el contenido de la columna y se puede usar cualquier función válida en PHP
        function SetColTransfer($arr)
        {
                $this->aColTransfer=$arr;
        }

        function SetDetTransfer($arr)
        {
                $this->aDetTransfer=$arr;
        }




    function SetForwardImg($cImg,$cAlt)
        {
            $this->forward_img = $cImg;                         //icono avance
            $this->forward_alt = $cAlt;
    }

    function SetBackwardImg($cImg,$cAlt)
        {
            $this->backward_img = $cImg;                        //icono avance
            $this->backward_alt = $cAlt;
    }

    function SetLastImg ($cImg,$cAlt)
        {
            $this->last_img = $cImg;                    //icono avance
            $this->last_alt = $cAlt;
    }

    function SetFirstImg ($cImg,$cAlt)
        {
            $this->first_img = $cImg;                   //icono avance
            $this->first_alt = $cAlt;
    }

    function SetCloseDtlImg ($cImg,$cAlt)
    {
                $this->close_detail_img=$cImg;
                $this->close_detail_alt=$cAlt;
        }

        function SetTitle($cTit,$cId)
        {
                $this->title = $cTit;
                $this->title_id = $cId;
        }

        function SetCellSP($txt)
        {
                $this->cellspacing = $txt;
        }

        function SetName($txt)
        {
                $this->name = $txt;
        }

        function SetCellPG($txt)
        {
                $this->cellpading = $txt;
        }

        function SetAlign($txt)
        {
                $this->align=$txt;
        }

        function SetValign($txt)
        {
                $this->valign=$txt;
        }

        function SetBgColor($txt)
        {
                $this->bgcolor=$txt;
        }


        function SetStyle($txt)
        {
                $this->styleid=$txt;
        }

        function SetColumnAlign($atxt)
        {
                $this->aColAlign=$atxt;
        }

        function SetBorder($nBorder)
        {
                $this->border=$nBorder;
        }

        function SetExtBorder($nBr)
        {
                $this->extborder=$nBr;
        }

        function OnMouseOver($action)
        {
                $this->onmouseover=$action;
        }

        function OnMouseOut($action)
        {
                $this->onmouseout=$action;
        }

        function OnClick($action)
        {
                $this->onclick=$action;
        }

        function SwitchColors($color1, $color2)
        {
                $this->color1 = $color1;
                $this->color2 = $color2;
        }

        function SetWidth($nW)
        {
                $this->width=$nW;
        }

        function SetHeight($nH)
        {
                $this->height=$nH;
        }

        function SetTitleColor($color)
        {
                        $this->titlebgcolor=$color;
        }


        function SetFooter($arr)
        {
                $this->aFooter = $arr;
                $this->aResults = array();
        }
        

        function SetFooterDecimals($arr)
        {
                $this->aFooterDEC = $arr;               
        }


//    function SetSaldo($ColumnaSaldo,$aColSaldo)    
    function SetSaldo($ColumnaSaldo,$aColSaldo, $aValSaldo = 0)
    {
         $this->ColumnaSaldo  =  $ColumnaSaldo;
         $this->ColumnasSigno = $aColSaldo;
//         $this->Saldo=0;
         $this->Saldo=$aValSaldo;
    }




        function GetScript()
        {
                global $PHP_SELF;
                global $_s1;
                global $_s2;
                global $_s3;
                global $_clasificacion1;
                global $HTTP_USER_AGENT;


                //$sql=$this->sql;

                if(empty($this->custom_sql))
                {
                        $sql = $this->sql;              
                        
                        $_posfrom = strpos(strtoupper($sql), "FROM");
                        $_zql = "SELECT COUNT(*) ".substr($sql, $_posfrom, (strlen($sql)-1));

                        $_poshvng = strpos(strtoupper($_zql), "HAVING");
                        if($_poshvng)
                        $_zql = substr($_zql,0,$_poshvng);


                         //debug("[zql (1343)] : ".$_zql);
                        // die();

                        $this->db->Execute($this->sql);
                        $_zql="SELECT FOUND_ROWS();";



                        $rs = $this->db->Execute($_zql);
                        if($rs)
                        {
                                $this->reg_num = max($rs->fields[0],$rs->_numOfRows);

                        }

                        


                        if($this->rows_page)    $this->pags =  ceil($this->reg_num / $this->rows_page);                 
                        
                        
                }
                else
                {
                        //----------------------------------------------------                                                          
                        // Pseudo análisis de SQL-QUERY, 
                        $sql = $this->custom_sql;
                        
                        if(!empty($this->autofilter))  //Recuperar el autofiltro
                        {


                                //--------------------------------------------------------------------------
                                // Funciones que agrupan
                                //$grp_func=array("AVG(","COUNT(","MAX(","MIN(",        "STD(","STDDEV(","SUM(");

                                
                                $gbp =  strpos(strtoupper($sql),"GROUP BY");

                                /*
                                
                                Modificación : Enrique Godoy Wednesday, December 08, 2004
                                Descripción :para que use "HAVING" tenga o no cláusula "GROUP"
                                
                                
                                La situación es que la lista de campos que se usa para ORDER BY y 
                                para hacer Autofiltros es la misma, pero ORDER BY siempre responde a los
                                ALIAS de las columas y la única forma de que el autofiltro haga 
                                lo mismo es usando HAVING.
                                                                
                                //if($gbp)
                                //{
                                */      
                                                        //¿Está el campo en la lista de columnas ?
                                                        
                                                        $pos_field_in_list  =  strpos(strtoupper($sql),"FROM");
                                                        $str_field_in_list  =  substr(strtoupper($sql),  0, $pos_field_in_list );               
                                                        $field_in_list      =  strpos(strtoupper($sql),strtoupper($_s1));
                                                        
                                                        //tiene alias                                                   
                                                        $alias = $this->GetAlias($_s1);
                                                        
                                                        $dot = strpos($alias,".");
                                                        
                                                        
                                                        // debug( " <LI>alias  = ".$alias."</LI>");
                                                        // debug( " <LI>_s1  = ".$_s1."</LI>");
                                                        // debug( " <LI>_s2  = ".$_s2."</LI>");
                                                        // debug( " <LI>_s3  = ".$_s3."</LI>");
                                                        
                                                        
                                                        $_valor=trim($_s3);
        
                
                                                        
                                                        $autofilter_having =($alias)?("(".$alias." ".$_s2." '".$_valor."')"):("(".$_s1." ".$_s2." '".$_valor."')");
                                                        
                                                        //debug( " <LI>autofilter_having  = ".$autofilter_having."</LI>");
                                                        
                                                        $sql_before_group    =  substr($sql,  0, $gbp ); 
                                                        $sql_after_group     =  substr($sql,  $gbp ); 

                                                        $gbw = strpos(strtoupper($sql),"WHERE");
                                                        
                                                        
                                                        
                                                        //debug( " <HR>alias = ".$alias."<HR> dot : $dot");
                                                        
                                                        if($field_in_list)
                                                        {
                                                                        
                                                                        $hbp =  strpos(strtoupper($sql),"HAVING");
                                                                        if($hbp)
                                                                        {
                                                                                
                                                                                        $sql_before_having    =  substr($sql,  0, $hbp );
                                                                                        $sql_after_having     =  substr($sql,  $hbp ); 
                                                                        
                                                                                        // Si ya tiene cláusula HAVING y un alias válido añadimos la condición al final.
                                                                                        if(( $alias ) and ( $dot === false) )
                                                                                        {
                                                                                                $sql.=  " and ".$autofilter_having."\n";
                                                                                            //debug($sql)       ;
                                                                                        }
                                                                                        else
                                                                                        {               // Tiene HAVING pero no tiene un alias válido 
                                                                                                        if($gbw) // Tiene cláusula WHERE 
                                                                                                        {
                                                                                                                        if($gbp) // Si tiene group
                                                                                                                        {
                                                                                                                                $sql =  $sql_before_group." and ".$this->autofilter."\n".$sql_after_group ;
                                                                                                                        }
                                                                                                                        else
                                                                                                                        {
                                                                                                                                $sql =  $sql_before_having." and ".$this->autofilter."\n".$sql_after_having;                                                                                                                    
                                                                                                                        }
                                                                                                                        
                                                                                                                //debug($sql)   ;
                                                                                                        }
                                                                                                        else //No tiene Clausula WHERE
                                                                                                        {
                                                                                                                        //$sql =        $sql_before_where."\n"." WHERE ".$this->autofilter."\n".$sql_after_where ;
                                                                                                                if($gbp) // Si tiene group
                                                                                                                {
                                                                                                                        $sql_before_group  =  substr($sql,  0, $gbp ); 
                                                                                                                        $sql_after_group     =  substr($sql,  $gbp ); 
                                                                                                                        $sql =  $sql_before_group." WHERE ".$this->autofilter."\n".$sql_after_group ;
                                                                                                                }
                                                                                                                else
                                                                                                                {
                                                                                                                        $sql =  $sql_before_having." WHERE  ".$this->autofilter."\n".$sql_after_having;

                                                                                                                }                                                                                                                       
                                                                                                        }                                                                                       
                                                                                        }
                                                                        }
                                                                        else
                                                                        {               //¿Tiene alias ? SI ->Si no tiene HAVING se lo ponemos.                         
                                                                                        
                                                                        
                                                                                        //debug("alias : ".$alias."* _s1 : ".$_s1);
                                                                        
                                                                        
                                                                                        
                                                                                        //( trim($alias) == trim($_s1) )
                                                                                         if( ($alias) and ( $dot === false) )
                                                                                          {
                                                                                            $sql .= "\n  HAVING ".$autofilter_having."\n";
                                                                                            
                                                                                           
                                                                                          }
                                                                                          else
                                                                                          {
                                                                                                        // Tiene WHERE                                                                                                  
                                                                                                        if($gbw) // Tiene cláusula WHERE 
                                                                                                        {
                                                                                                                        
                                                                                                                        if($gbp) // Si tiene group
                                                                                                                        {
                                                                                                                                $sql =  $sql_before_group." and ".$this->autofilter."\n".$sql_after_group ;
                                                                                                                        }
                                                                                                                        else
                                                                                                                        {
                                                                                                                                $sql =  $sql." and ".$this->autofilter."\n";
                                                                                                                        
                                                                                                                        }
                                                                                                        }
                                                                                                        else //No tiene Clausula WHERE
                                                                                                        {
                                                                                                                        //$sql =        $sql_before_where."\n"." WHERE ".$this->autofilter."\n".$sql_after_where ;


                                                                                                                
                                                                                                                if($gbp) // Si tiene group
                                                                                                                {
                                                                                                                        $sql_before_group  =  substr($sql,  0, $gbp ); 
                                                                                                                        $sql_after_group     =  substr($sql,  $gbp ); 
                                                                                                                        //$sql =        $sql_before_group." WHERE ".$this->autofilter."\n".$sql_after_group ;
                                                                                                                        $sql =  $sql_before_group." WHERE ".$autofilter_having."\n".$sql_after_group ;
                                                                                                                        
                                                                                                                        
                                                                                                                }
                                                                                                                else
                                                                                                                {
                                                                                                                        //$sql =        $sql." WHERE  ".$this->autofilter."\n";
                                                                                                                        $sql =  $sql." WHERE  ".$autofilter_having."\n";

                                                                                                                }
                                                                                                                        
                                                                                                        }
                                                                                          
                                                                                          }
                                                                        }
                                                                        
                                                                        
                                                                        //debug("Debug Autofiltro : ($gbp) ".$sql);
                                                                        
                                                        
                                                        
                                                        }
                                                        else
                                                        {
                                                        
                                                          
                                                                                                                                   
                                                                if( $gbw ) // Tiene cláusula WHERE 
                                                                {
                                                                                if( strpos(strtoupper($sql),"GROUP BY "))
                                                                                {
                                                                                        $sql =  $sql_before_group." and ".$this->autofilter."\n".$sql_after_group ;

                                                                                }
                                                                                else
                                                                                {
                                                                                        
                                                                                  $sql =        $sql_after_group." and ".$this->autofilter."\n".$sql_before_group ;                                                             
                                                                                }

                                                                }
                                                                else
                                                                {
                                                                                // $sql =       $sql_before_group."\n"." WHERE ".$this->autofilter."\n".$sql_after_group ;
                                                                                $sql =  $sql_after_group."\n"." WHERE ".$this->autofilter."\n".$sql_before_group ;
                                                                                
                                                                                
                                                                }
                                                                                                
                                                 
                                                           //echo "<HR>".nl2br($sql)."<HR>";

                                                        }
                                /*
                                //}
                                //else
                                //{
                                //      if( strpos(strtoupper($sql),"WHERE") )
                                //              $sql.=  " and ".$this->autofilter."\n";
                                //        else
                                //               $sql.= " WHERE ".$this->autofilter."\n";
                                //}
                           */

                                //Repaginar
                                
                                
                                

                        $_posfrom = strpos(strtoupper($sql), "FROM");
                        $_zql = "SELECT COUNT(*) ".substr($sql, $_posfrom, (strlen($sql)-1));
                        
                        $_poshvng = strpos(strtoupper($_zql), "HAVING");
                        if($_poshvng)
                        $_zql = substr($_zql,0,$_poshvng);
                        
                        

                        //debug("[zql (1582)] : ".$_zql);
                        //die();


                        $this->db->Execute($sql);
                        $_zql="SELECT FOUND_ROWS();";



                                $rs = $this->db->Execute($_zql);
                                if($rs)
                                {
                                        $this->reg_num = max($rs->fields[0],$rs->_numOfRows);
                                        if($this->rows_page)
                                           $this->pags =  ceil($this->reg_num / $this->rows_page);


                                }
                                else
                                {

                                        $this->reg_num = 1;
                                        $this->pags = 1;
                                        
                                }



                        }


                }

                //echo "AUTOFILTRO : [".$this->autofilter."] SQL : <BR> ".nl2br($sql)." <HR>";

                $db =&$this->db;
                


                                /*~~~~~~~~~~~~~~~~~~~FORMA DE USO~~~~~~~~~~~~~~~~~~~*/


                $script="";

                $script.="<SCRIPT>\n";
                $script.="function Launch( lr,ar,ob,pkey,addkey,edkey,delkey)\n";
                $script.="      {\n";
                $script.="              window.document.".$this->name.".elements['lr'].value=lr;                        \n";
                $script.="              window.document.".$this->name.".elements['ar'].value=ar;                        \n";
                $script.="              window.document.".$this->name.".elements['ob'].value=ob;                        \n";
                $script.="              window.document.".$this->name.".elements['pkey'].value=pkey;            \n";
                $script.="              window.document.".$this->name.".elements['addkey'].value=addkey;        \n";
                $script.="              window.document.".$this->name.".elements['edkey'].value=edkey;  \n";
                $script.="              window.document.".$this->name.".elements['delkey'].value=delkey;        \n";

                $script.="              window.document.".$this->name.".action = ''; \n";
                $script.="              window.document.".$this->name.".target = ''; \n";

                $script.="              window.document.".$this->name.".submit(); \n";
                $script.="      }\n";
                $script.="      </SCRIPT>\n\n";


                $script.="<SCRIPT>\n";
                $script.="function LaunchControl( lr,ar,ob,pkey,addkey,edkey,delkey,newaction, newtarget)\n";
                $script.="      {\n";
                $script.="              window.document.".$this->name.".elements['lr'].value=lr;                        \n";
                $script.="              window.document.".$this->name.".elements['ar'].value=ar;                        \n";
                $script.="              window.document.".$this->name.".elements['ob'].value=ob;                        \n";
                $script.="              window.document.".$this->name.".elements['pkey'].value=pkey;            \n";
                $script.="              window.document.".$this->name.".elements['addkey'].value=addkey;        \n";
                $script.="              window.document.".$this->name.".elements['edkey'].value=edkey;  \n";
                $script.="              window.document.".$this->name.".elements['delkey'].value=delkey;        \n";

                $script.="              window.document.".$this->name.".action = newaction; \n";
                $script.="              window.document.".$this->name.".target = newtarget; \n";

                $script.="              window.document.".$this->name.".submit(); \n";
                $script.="      }\n";
                $script.="      </SCRIPT>\n\n";

                $script.="\t\t\n\n\n";

                $script.="<FORM NAME='".$this->name."' ".
                                 " METHOD='".$this->method."' ".
                                 " ACTION='".$PHP_SELF."' ";
                //$script.=(!empty($this->target))?(" TARGET='".$this->target."' "):(" ");

                $script.=">\n";

                $script.="\t\t\t<INPUT TYPE='hidden' name='lr'          value='' >\n";
                $script.="\t\t\t<INPUT TYPE='hidden' name='ar'          value='' >\n";
                $script.="\t\t\t<INPUT TYPE='hidden' name='ob'          value='' >\n";
                $script.="\t\t\t<INPUT TYPE='hidden' name='pkey'        value='' >\n";
                $script.="\t\t\t<INPUT TYPE='hidden' name='addkey'      value='' >\n";
                $script.="\t\t\t<INPUT TYPE='hidden' name='edkey'       value='' >\n";
                $script.="\t\t\t<INPUT TYPE='hidden' name='delkey'      value='' >\n";
                $script.="\t\t\t<INPUT TYPE='hidden' name='_s1'         value='$_s1' >\n";
                $script.="\t\t\t<INPUT TYPE='hidden' name='_s2'         value='$_s2' >\n";
                $script.="\t\t\t<INPUT TYPE='hidden' name='_s3'         value='$_s3' >\n";
                $script.="\t\t\t<INPUT TYPE='hidden' name='_rpp'        value='".$this->rows_page."' >\n";
                $script.="\t\t\t<INPUT TYPE='hidden' name='_clasificacion1'     value='$_clasificacion1' >\n";

                $script.="\n\n\t\t\t<!-- Parámetros extra -->\n\n";


                if(!empty($this->parameters))
                        for($s=0;$s<count($this->parameters);$s++)
                                $script.="\t\t\t<INPUT TYPE='hidden' name='".$this->parameters[$s]."' value='".$this->paramvalues[$s]."' >\n";

                $script.="\t\t\n";
                $script.="</FORM>\n\n\n";


                                /*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/



        if(strpos($HTTP_USER_AGENT,"MSIE"))
        {

                        $script.="\n<TABLE ";
                        if(!empty($this->align))                { $script.=" ALIGN='".  $this->align   ."' ";   }
                        if(!empty($this->width))                { $script.=" WIDTH='".  $this->width   ."' ";   }
                        if(!empty($this->height))               { $script.=" HEIGHT='". $this->height  ."' ";   }
                        if(!empty($this->bgcolor))              { $script.=" BGCOLOR='".$this->bgcolor ."' ";   }
                        else
                                {
                                        if(!empty($this->color1)) $script.=" BGCOLOR='".$this->color1 ."' ";
                                }

                        $script.=" VALIGN='TOP' ";
                        if(!empty($this->extborder)){$script.=" RULES='rows' ";}
                        $script.=" CELLPADING='0' ";
                        $script.=" CELLSPACING='1' >";
        }
        else
        {

                        $script.="\n<TABLE ";
                        if(!empty($this->align))                { $script.=" ALIGN='".  $this->align   ."' ";   }
                        if(!empty($this->width))                { $script.=" WIDTH='".  $this->width   ."' ";   }
                        if(!empty($this->height))               { $script.=" HEIGHT='". $this->height  ."' ";   }
                        if(!empty($this->bgcolor))              { $script.=" BGCOLOR='".$this->bgcolor ."' ";   }
                        else
                                {
                                        if(!empty($this->color1)) $script.=" BGCOLOR='".$this->color1 ."' ";
                                }

                        $script.=" VALIGN='TOP' ";
                        $script.=" BORDER='1' ";
                        $script.=" CELLPADING='0' ";
                        $script.=" CELLSPACING='0' > <TR><TD>";


                        $script.="\n<TABLE ";
                        if(!empty($this->align))                { $script.=" ALIGN='".  $this->align   ."' ";   }
                        if(!empty($this->width))                { $script.=" WIDTH='100%' ";    }
                        if(!empty($this->bgcolor))              { $script.=" BGCOLOR='".$this->bgcolor ."' ";   }
                        else
                                {
                                        if(!empty($this->color1)) $script.=" BGCOLOR='".$this->color1 ."' ";
                                }

                        $script.=" VALIGN='TOP' ";
                        if(!empty($this->extborder)){$script.=" RULES='rows' ";}
                        $script.=" CELLPADING='0' ";
                        $script.=" CELLSPACING='0' >";
        
        }

                        // Herremientas operativas

                        $script.="\n\t<TR  BGCOLOR='".$this->generalcolor."' >" ;
                        if(count($this->detail_fields)>0)
                        {
                                $script.="\n\t\t<TD COLSPAN=2 ALIGN='left'>" ;
                        }
                        else
                        {
                                $script.="\n\t\t<TD ALIGN='left' VALIGN='top'>" ;
                        }

                    $script.="\n\n\t\t<!-- Barra de titulo -->\n\n\t\t";
                        $script.="<TABLE BGCOLOR='".$this->generalcolor."'  BORDER=0 CELLSPACING=0 WIDTH='100%' CELLPADING=0 ><TR>";//<TD ALIGN='left'>" ;
                        //$script.="<TR>";
                        
if( $this->ModifyRowsPerPage)
{
        

        if(strpos($HTTP_USER_AGENT,"MSIE"))
        {       

                $script.="<TD ALIGN='LEFT' VALIGN='MIDDLE'  style='width:80px;' nowrap>";
                
                $script.="<INPUT TYPE='TEXT' STYLE=' FONT-SIZE: 10;
                            FONT-STYLE: normal;
                            FONT-FAMILY:  Verdana,Geneva,Tahoma, Arial, Helvetica, sans-serif; text-align:right; ' 
                            NAME='rpp' 
                            VALUE='".$this->rows_page."' 
                            SIZE='3' 
                            MAXLENGTH='3' 
                            
                            onkeydown=' 
                            
                            if (event.keyCode == 13) 
                            { 
                               document.".$this->name.".elements[\"_rpp\"].value = document.all[\"rpp\"].value; 
                               document.".$this->name.".submit(); 
                            } ' >";
                                                                     
                $script.=       "<A HREF=\"javascript:document.".$this->name.".elements['_rpp'].value = document.all['rpp'].value; document.".$this->name.".submit(); \" >";
                if(!empty($this->forward_img))
                {
                        $script.=       "<SPAN STYLE='position: relative; top:8'><IMG SRC='".$this->forward_img."' ALT='".$this->forward_alt."' BORDER='0'/></SPAN>";
                }
                else
                {
                        $script.=       "<Font Color='#FF0000'><B>(&gt;)</B></Font>";
                }
                $script.=       "</A>";
        }
        else
        {
                $script.="\n<TD ALIGN='LEFT' VALIGN='MIDDLE'  style='width:80px;' nowrap>\n\t";
                $script.="<FORM NAME='X".$this->name."' >\n\t";
                $script.="<INPUT TYPE='TEXT' STYLE=' FONT-SIZE: 10;
                                                     FONT-STYLE: normal;
                                                     FONT-FAMILY:  Verdana,Geneva,Tahoma, Arial, Helvetica, sans-serif; text-align:right; ' 
                                                     NAME='rpp' 
                                                     VALUE='".$this->rows_page."' 
                                                     SIZE='3' MAXLENGTH='3' >\n\t";
                                                                     
                             
                                                                  
                $script.=       "<A HREF=\"javascript:document.".$this->name.".elements['_rpp'].value = document.X".$this->name.".elements['rpp'].value; document.".$this->name.".submit(); \" >";

                if(!empty($this->forward_img))

                {
                        $script.=       "<SPAN STYLE='position: relative; top: 8'><IMG SRC='".$this->forward_img."' ALT='".$this->forward_alt."' BORDER='0'></SPAN>";
                }
                else
                {
                        $script.=       "<Font Color='#FF0000'><B>(&gt;)</B></Font>";
                }
                $script.=       "</A>";
                $script.="</FORM>";             
        
        
        }

        ///<BUTTON  style=' text-size:5;' onClick=\"javascript:document.".$this->name.".elements['_rpp'].value = document.all['rpp'].value; document.".$this->name.".submit();\" >&gt;</BUTTON>
        $script.="</TD>";
}       


        $script.="<TD COLSPAN=2 ALIGN='CENTER'  VALIGN='middle' ";
        $script.=(!empty($this->title_id))?(" ID='".$this->title_id."' >"):(">");

        $script.= $this->title;

        $script.=" </TD>";
        $script.="</TR>";
        $script.="</TABLE>\n\n";
        
        
        $script.="\t\t<!-- Fin de barra de titulo -->\n\n\t\t";


        $script.="\n\t</TD>\n</TR>\n<TR>\n\t<TD VALIGN='TOP'>\n" ;




                // Contenido de la tabla
        
        
        $script.="\n\n\t  <!-- Tabla de contenidos -->\n\n";

        $script.="\n\t<TABLE ";

                                                        //Atributos visuales.

                                                        if(!empty($this->align))                { $script.=" ALIGN='".  $this->align   ."' ";   }
                                                        if(!empty($this->valign))               { $script.=" VALIGN='". $this->valign  ."' ";   }
                                                        if(!empty($this->bgcolor))              { $script.=" BGCOLOR='".$this->bgcolor ."' ";   }
                                                        else
                                                                {
                                                                        if(!empty($this->color1)) $script.=" BGCOLOR='".$this->color1 ."' ";
                                                                }
                                                        if($this->border)               { $script.=" BORDER='".    $this->border  ."' ";   }
                                                        if(!empty($this->styleid))              { $script.=" ID='".             $this->styleid ."' ";   }


                                                        $script.=" WIDTH='100%' ";
                                                        $script.=" HEIGHT='100%' ";
                                                        $script.=" VALIGN='TOP' ";


                                                        $script.=" CELLPADING='".  $this->cellspacing ."' ";
                                                        $script.=" CELLSPACING='". $this->cellpading  ."' ";


                                                $script.=">\n";
                                                $script.="\t<TR> ";


                                                //Titulos (contienen funcionalidad de ordenamiento)

                                                if($this->numered)
                                                {
                                                        $script.="\t\t<TH ALIGN='center' ";
                                                        if(!empty($this->titlebgcolor))
                                                        {
                                                                $script.=" BGCOLOR='".$this->titlebgcolor."' ";
                                                        }
                                                        $script.=">&nbsp;</TH>\n";
                                                }

                                                for($i=0; $i < (count($this->aTitles)); $i++)
                                                {
                                                        $act_ord =((abs($this->order)-1)<0)?(0):(abs($this->order)-1);

                                                        $sgn =abs($this->order)?(($this->order)/abs($this->order)):(1);

                                                        if($this->order == 0) $this->order++;

                                                        if( $act_ord == $i)
                                                        {

                                                                $order_asc_img=(empty($this->order_asc_img))?("(^)"):("<IMG SRC='".$this->order_asc_img."' ALT='".$this->order_asc_alt."' BORDER=0>&nbsp;");
                                                                $order_dsc_img=(empty($this->order_dsc_img))?("(v)"):("<IMG SRC='".$this->order_dsc_img."' ALT='".$this->order_dsc_alt."' BORDER=0>&nbsp;");

                                                                $actual=($this->order>0)?($order_asc_img):($order_dsc_img);
                                                                $new_ord = (($i+1)*(-1)*$sgn);
                                                        }
                                                        else
                                                        {
                                                                $actual="";
                                                                $new_ord = ($i+1);
                                                        }

                                                        //$_this_title_[$i] = ( empty($this->aTitles[$i]) )?(''):($this->aTitles[$i]);
                                                        //$this_title = "<A HREF=\"javascript:Launch('".$this->lastrow."','".$this->actual_register."','".$new_ord."','','','','')\"> ".$actual.$_this_title_[$i]."</A>";
                                                        $this_title = "<A HREF=\"javascript:Launch('".$this->lastrow."','".$this->actual_register."','".$new_ord."','','','','')\"> ".$actual.$this->aTitles[$i]."</A>";


                                                        $this->aColums[$i]= new TColumn($this_title,$this->titlebgcolor);

                                                        $script.=$this->aColums[$i]->ShowTitles();
                                                }

                                                $script.="\n\t</TR>";

                                                if($this->lastrow == 0)
                                                        $this->top=true;
                                                else
                                                        $this->top=false;

                                                $sql_keys="";

                                                $num_keys=count($this->pkey_fields);

                                                for ($e=0; $e<$num_keys; $e++)
                                                {
                                                        $sql_keys .= " ".$this->table.".".$this->pkey_fields[$e];

                                                        if( $e < ($num_keys-1) )
                                                        {
                                                                $sql_keys .= ", ";
                                                        }
                                                        else
                                                        {
                                                         $sql_keys .= " ";
                                                        }
                                                }

                                                $listfields = $sql_keys;

                                                $_distinct = (strpos(strtoupper($sql), 'DISTINCT'))?(" DISTINCT "):("");


                                                $sql_keys=" SELECT ".$_distinct.$sql_keys;


                                                //$pos = strpos(strtoupper($sql),"FROM");
                                                //

                                                // Corrección Tuesday, April 15, 2003
                                                // Para corregir el problema de la falta  de PKEY cuando se subtituyen los campos


                                                $pos = strpos(strtoupper($sql),"SELECT");
                                                $pos += 6;

                                                if( $pos )
                                                {

                                                                        if($distinct = strpos(strtoupper($sql), 'DISTINCT'))
                                                                        {
                                                                                $distinct+=8;
                                                                                $_fields = substr($sql,$distinct);                                      
                                                                        }
                                                                        else
                                                                                $_fields = substr($sql,$pos);


                                                                        $posF = strpos(strtoupper($_fields),"FROM");

                                                                        $_f = substr($_fields,0,$posF);
                                                                        $_r = substr($_fields,$posF);


                                                                        if($this->debug) debug("_f : ".$_f);
                                                                        if($this->debug) debug("_r : ".$_r);

                                                                        $sql_keys .=", ".$_f  ;
                                                                        $sql_keys .=" ". $_r." ";


                                                }
                                                


                                                if($this->debug) debug($sql);

                                                if(abs($this->order))
                                                {
                                                        
                                                        
                                                        
                                                        $ordfield = $this->fields[ abs($this->order) -1 ];
                                                        $npos=strpos(strtolower($ordfield)," as ");
                                                        if($npos)
                                                        {
                                                                $ordfield = substr($ordfield,$npos+3 );
                                                        }

                                                        if(!empty($ordfield))
                                                                $sort = ($this->order < 0)?(" DESC "):(" ");
                                                        else
                                                                $sort = " ";

                                                        if( $this->SIN_ORDER ){
	                                                    //$sql      .= " ORDER BY  ".$ordfield.$sort." " ;
                                                        //$sql_keys .= " ORDER BY  ".$ordfield.$sort." " ;
                                                       	}else{
	                                                    $sql      .= " ORDER BY  ".$ordfield.$sort." " ;
                                                        $sql_keys .= " ORDER BY  ".$ordfield.$sort." " ;  
	                                                           	

                                                        


                                                        
                                                        if($listfields)
                                                        {

                                                                if(!empty($ordfield)) 
                                                                        {
                                                                                $sql .= ", ".$listfields;
                                                                                $sql_keys .= ", ".$listfields;
                                                                        }
                                                                else
                                                                        {
                                                                                $sql .= $listfields;
                                                                                $sql_keys .= $listfields;
                                                                        }
                                                        }
                                                        
                                                        }
                                                        
                                                }
                                                else
                                                {

                                                        $ordfield = $this->fields[0];
                                                        if($npos=strpos(strtolower($ordfield)," as "))
                                                        {
                                                                $ordfield = substr($ordfield,$npos+3 );
                                                                //$ordfieldk = substr($ordfield,0,$npos);
                                                        }


                                                        $sql .= " ORDER BY  ";

                                                        if(!empty($ordfield)) $sql .= $ordfield.", " ;                  
                                                        if($listfields) $sql .= " ".$listfields;

                                                }


                                                if($this->rows_page)
                                                {                               
                                                                        if ($this->lastrow == -1 ) 
                                                                        {
                                                                                /*
                                                                                        Si entramos al grid por primera vez i.e. Sin haber interactuado 
                                                                                        con él previamente, entonces $this->lastrow == -1
                                                                                        y dependiendo del valor de defaulta page, nos vamos a la 
                                                                                        primera o a la última página.                                           
                                                                                */

                                                                                if($this->default_start_page == 'last')
                                                                                {
                                                                                        $this->lastrow =  ($this->pags *  $this->rows_page) - ($this->rows_page);

                                                                                        //debug("last row : ".$this->lastrow."=(($this->pags *  $this->rows_page) - $this->rows_page)");
                                                                                }
                                                                                else
                                                                                {
                                                                                        $this->lastrow =  0;
                                                                                }
                                                                        }
                                                /**/    
                                                                        $_lr = ($this->lastrow < 0)?(0):($this->lastrow);
                                                                        $sql .= " LIMIT ".$_lr.", ".$this->rows_page;
                                                                        $sql_keys .= " LIMIT ".$_lr.", ".$this->rows_page;
                                                }


                                                if($this->debug) echo "<HR><B> CLASS SQL :     </B><BR> ".nl2br($sql).     "<HR>";
                                                $this->c_sql=$sql;
                                                if($this->debug) echo "<HR><B> CLASS SQL_KEYS :</B><BR> ".nl2br($sql_keys)."<HR><BR>";


                                //debug("[sql (2069) ] : ".$sql);
                                //die();
        

                                                $rs=$db->Execute($sql);




                                //debug("[sql_keys (2078) ] : ".$sql_keys);
                                //die();
                                                $key_rs=$db->Execute($sql_keys);

                                                //echo " sql_keys  : ".nl2br($sql_keys)."<HR>";

                                                $control = 0;
                                                $rownum=0;
                                                $lr = $this->lastrow;


                                                if($rs)
                                                        while(!$rs->EOF)
                                                        {
                                                                $this->lastrow++;

                                                                // This row's primary key //
                                                                //
                                                                $pkey = "";
                                                                if(($key_rs) and (!$key_rs->EOF ))
                                                                {

                                                                        for($k=0; $k<count($this->pkey_fields); $k++)
                                                                        {
                                                                                $pkey .= $this->table.".".$this->pkey_fields[$k]." = `".$key_rs->fields[$k]."` ";
                                                                                if($k < (count($this->pkey_fields)-1) )
                                                                                {
                                                                                        $pkey .= " and ";
                                                                                }
                                                                        }
                                                                        $key_rs->MoveNext();
                                                                }

                                                                if($this->debug) echo "<HR> <B>pkey : </B>$pkey <HR>";

                                                                $script.="\n\t<TR ";

                                                                $last_row_color=(empty($this->lastrowcolor))?('red'):($this->lastrowcolor);
                                                                if($this->pkey == $pkey and !empty($this->pkey))  {$script.=" BGCOLOR='".$last_row_color."' ";} //Ultimo seleccionado


                                                                if(!empty($this->onmouseover)) { $script.=" onmouseover=\"javascript:".$this->onmouseover."\""; }
                                                                                                                else   { $script.=" onmouseover=\"javascript: this.style.backgroundColor='yellow'; this.style.cursor='hand'; \" ";      }
                                                                if(!empty($this->onmouseout))  { $script.=" onmouseout=\"javascript:".$this->onmouseout."\" ";}
                                                                                                                else   { $script.=" onmouseout=\"javascript:  this.style.backgroundColor='' \" ";       }
                                                                if(!empty($this->onclick))     { $script.=" onmouseout=\"javascript:".$this->onclick."\" ";}
                                                                else
                                                                   if(!empty($this->detail_fields) )
                                                                                { $script.=" onclick=\"javascript:Launch( '".$lr."','".$this->lastrow."','".$this->order."','".$pkey."','','',''); \" ";        }


                                                                if(!empty($this->color1))
                                                                {
                                                                        if($control)
                                                                        {
                                                                                $script.=" BGCOLOR='".$this->color1."' ";
                                                                        }
                                                                        else
                                                                        {
                                                                                $script.=" BGCOLOR='".$this->color2."' ";
                                                                        }
                                                                        $control = ~$control;

                                                                }


                                                                $script.=" > ";
                                                                $rownum++;
                                                                $cini=0;

                                                                if($this->numered)
                                                                {
                                                                 $script.="\n\t\t<TD ALIGN='RIGHT'>".number_format($this->lastrow,0).") </TD>\n";
                                                                }



                                                                for($i=0; $i<(count($this->aTitles)+$cini); $i++) //cada columna
                                                                {

                                                                        if(!empty($rs->fields[$i]))
                                                                        {
                                                                                $numero = str_replace("$","",$rs->fields[$i]);
                                                                                $numero = (float) str_replace(",","",$numero );
                                                                        }
                                                                        else
                                                                        {                                               
                                                                                $numero = 0;                                            
                                                                                if($i==$this->ColumnaSaldo)  $numero = $this->Saldo;                                            
                                                                        }

                                                                        if($rs->fields[$i])
                                                                        {



                                                                                if(count($this->ColumnasSigno)>0)
                                                                                {
                                                                                        foreach($this->ColumnasSigno as $Indice => $valor)
                                                                                        {
                                                                                          if(abs($valor)==$i)
                                                                                          {
                                                                                                $this->Saldo+=($valor<0)?$numero*(-1):$numero;
                                                                                          }
                                                                                        }                                               
                                                                                }

                                                                                //-----------------------------------------------------------
                                                                                //Máscaras de formato           (Booo! :0)

                                                                                if($this->aColFormat[$i])       
                                                                                {
                                                                                                $ColumContent=$this->FieldFormat($rs->fields[$i],$this->aColFormat[$i]);

                                                                                }
                                                                                else
                                                                                                $ColumContent=$rs->fields[$i];

                                                                                if($this->aColTransfer[$i])     $this->aColums[$i+$cini]->SetTransfer($this->aColTransfer[$i]);

                                                                                $this->aColums[$i+$cini]->SetContent($ColumContent);

                                                                                switch( $this->aColAlign[$i+$cini] )
                                                                                {
                                                                                        case "R":
                                                                                                        $this->aColums[$i+$cini]->SetAlign('RIGHT');
                                                                                                        break;

                                                                                        case "C" :
                                                                                                        $this->aColums[$i+$cini]->SetAlign('CENTER');
                                                                                                        break;

                                                                                        case "L" :
                                                                                                        $this->aColums[$i+$cini]->SetAlign('LEFT');
                                                                                                        break;
                                                                                        default:
                                                                                                        $this->aColums[$i+$cini]->SetAlign('LEFT');
                                                                                }

                                                                        }
                                                                        else
                                                                        {
                                                                                // Cuando no se trata de un campo, sino de un valor calculado (Saldo)

                                                                                 if($i==$this->ColumnaSaldo)
                                                                                 { 

                                                                                   $ColumContent=$this->FieldFormat($this->Saldo,$this->aColFormat[$i]);

                                                                                   if($this->Saldo<0)
                                                                                   {
                                                                                         $ColumContent= "<FONT COLOR='".$this->aColums[$i+$cini]->colorsaldonegativo."'>".$ColumContent."</FONT>";
                                                                                   } 

                                                                                   $this->aColums[$i+$cini]->SetContent($ColumContent);
                                                                                   $this->aColums[$i+$cini]->SetAlign('RIGHT');
                                                                                 } 
                                                                                 else 
                                                                                 {                                                      
                                                                                        $this->aColums[$i+$cini]->SetContent('---');
                                                                                        $this->aColums[$i+$cini]->SetAlign('CENTER');
                                                                                 }
                                                                        }


                                                                                if($this->aFooter[$i] )
                                                                                {                                                       
                                                                                        switch(strtoupper($this->aFooter[$i])) 
                                                                                        {
                                                                                                case "SUM()":   $this->aResults[$i]+=   $numero;                break;

                                                                                                case "AVG()":   $this->aResults[$i]+=   ($rs->RecordCount())?($numero/$rs->RecordCount()):(0);  break;

                                                                                                case "CNT()":   $this->aResults[$i]++;   break;

                                                                                                case "LAST()":  $this->aResults[$i]=     $numero;;       break;

                                                                                                default :               $this->aResults[$i]="";
                                                                                        }

                                                                                }

                                                                        $script.= $this->aColums[$i+$cini]->Show();
                                                                }
                                                                $rs->MoveNext();
                                                                $script.="\n\t</TR>" ;

                                                        }


                                        //---------------------------------------------------------------------------------------------------------------------------------------------------------------
                                        // Totalizador
                                        //---------------------------------------------------------------------------------------------------------------------------------------------------------------

                                                        if($this->aFooter)
                                                                {
                                                                                        $colspan=array();
                                                                                        $script.="\n\t<TR  BGCOLOR='".$this->generalcolor."' >" ;

                                                                                                                if($this->numered) $script.= "<TD>&nbsp;</TD>"; 


                                                                                                                for($v=0; $v<(count($this->aFooter)); $v++)
                                                                                                                {
                                                                                                                                $script.= "<TD ALIGN='right'>"; 

                                                                                                                                $decimals = ( strlen($this->aFooterDEC[$v])>0 )?($this->aFooterDEC[$v]):(2);                                                                                            



                                                                                                                                if( !empty($this->aFooterFormat[$v]))
                                                                                                                                {

                                                                                                                                        //$script.= ($this->aResults[$v])?($this->FieldFormat($this->aResults[$v],$this->aFooterFormat[$v])):("&nbsp;");
                                                                                                                                        // BGM SI EL TOTAL EN EL PIE ES NEGATIVO LO PINTA DE ROJO
                                                                                                                                        if( $this->aResults[$v] ) 
                                                                                                                                        {
                                                                                                                                                if( $this->aResults[$v] < 0 ) 
                                                                                                                                                        $script .= "<span style='COLOR: Red'>".$this->FieldFormat($this->aResults[$v],$this->aFooterFormat[$v])."</span>";
                                                                                                                                                else
                                                                                                                                                        $script .= $this->FieldFormat($this->aResults[$v],$this->aFooterFormat[$v]);
                                                                                                                                        } else {
                                                                                                                                                $script .= "&nbsp;";
                                                                                                                                        }

                                                                                                                                }
                                                                                                                                else
                                                                                                                                {                                                                                               
                                                                                                                                        $script.= ($this->aResults[$v])?("<B>".number_format($this->aResults[$v],$decimals)."</B>"):("&nbsp;");
                                                                                                                                        $script.= "</TD> ";
                                                                                                                                }

                                                                                                                }                               

                                                                                        $script.="\n</TR>\n ";  
                                                                }                       

                                                $script.="\n\t</TABLE>\n\n\t  <!-- Fin Tabla de contenidos -->\n\n";

                if(!empty($this->detail))  // SI HAY DETALLE
                {


                        if(count($this->detail_align)==0){$this->detail_align=$this->aColAlign;}
                        
                        //---------------------------------
                        //Pongamos el .... DETALLE !            ("--Que detalle!! No te hubieras molestado [...]")
                        
                        if(!empty($this->detail_fields) and !empty($this->pkey) )
                        {
                                        $script.="\n\n</TD><TD  VALIGN='TOP' ID='d1' BGCOLOR='".$this->titlebgcolor."' ";
                                        if(!empty($this->detail_width))
                                                {$script.="WIDTH='".$this->detail_width."'"; }



                                        
                                        $script.=">\n\n\t<!-- Detalle -->";

                                        if( !empty($this->actual_register) and !empty($this->table) )
                                        {
                                                        $detail_sql = "SELECT ".$_distinct;
                                                        $detail_sql .= implode(",\n ",$this->detail_fields);

                                                        // $detail_sql .=" FROM ".$this->table;

                                                        $pos1 = strpos(strtoupper($sql),"FROM" );
                                                        
                                                        
                                                        
                                                    if($pos2 = strpos(strtoupper($sql),"WHERE" ))
                                                          true; 
                                                        else
                                                                if($pos2 = strpos(strtoupper($sql),"GROUP BY" ))
                                                                  true; 
                                                                else
                                                                        if($pos2 = strpos(strtoupper($sql),"HAVING" ))
                                                                          true; 
                                                                        else                                                    
                                                                                if($pos2 = strpos(strtoupper($sql),"ORDER" ))
                                                                                  true; 
                                                                                else
                                                                                  $pos2 = strlen($sql);
                                                        
                                                        
                                                        
                                                        $detail_sql .= " ".substr($sql,$pos1,($pos2-$pos1));
                                                        
                                                        //---------------------------------------------                                                 
                                                        // Si existía algún WHERE previamente en él.
                                                        if(strpos(strtoupper($sql),"WHERE" )>0)
                                                        {
                                                            $pos2 = 0;
                                                                $pos1 = strpos(strtoupper($sql),"WHERE" );                                                              
                                                                $posx = strpos(strtoupper($sql),"GROUP BY" );
                                                                
                                                                if( strpos(strtoupper($sql),"ORDER" ))
                                                                {
                                                                  $pos2 = strpos(strtoupper($sql),"ORDER" );
                                                                }
                                                                else
                                                                        if(strpos(strtoupper($sql),"GROUP BY" ))
                                                                        {
                                                                          $pos2 = strpos(strtoupper($sql),"GROUP BY" );
                                                                        }
                                                                        else
                                                                                if(strpos(strtoupper($sql),"HAVING" ))
                                                                                {
                                                                                        $pos2 = strpos(strtoupper($sql),"HAVING" );
                                                                                }
                                                                                else
                                                                                {
                                                                                        $pos2 = strlen($sql);
                                                                                }
                                                                
                                                                //debug(" TGRID (test) : <BR> $pos1 $pos2 $posx" );
                                                                //debug(" TGRID (1) : <BR> $detail_sql " );
                                                                
                                                                $detail_sql .=  substr($sql,$pos1,($pos2-$pos1));
                                                                
                                                                //$detail_sql .=        " and ".str_replace ("`", "'", $this->pkey)." ";
                                                                
                                                                                                                                
                                                        }
                                                        //debug(" TGRID (1) : <BR> $detail_sql " );
                                                        
                                                
                                                        if( strpos(strtoupper($detail_sql),"WHERE")  )
                                                        {
                                                                
                                                                if( $gbp =  strpos(strtoupper($detail_sql),"GROUP BY") )
                                                                {
                                                                        //debug($detail_sql);
                                                                        $tmp_ds1 = substr($detail_sql,0,($gbp-1));
                                                                        $tmp_ds2 = substr($detail_sql,$gbp);
                                                                        $detail_sql =$tmp_ds1." and ".str_replace ("`", "'", $this->pkey)." ".$tmp_ds2;                                                                 
                                                                }
                                                                else
                                                                {                                                                       
                                                                        if($hvg =  strpos(strtoupper($detail_sql),"HAVING"))
                                                                        {
                                                                                $tmp_ds1 = substr($detail_sql,0,($hvg-1));
                                                                                $tmp_ds2 = substr($detail_sql,$hvg);
                                                                                $detail_sql =$tmp_ds1." and ".str_replace ("`", "'", $this->pkey)." ".$tmp_ds2;
                                                                        }
                                                                        else
                                                                        {                                                                                               
                                                                                $detail_sql .=" and ".str_replace ("`", "'", $this->pkey);
                                                                        }
                                                                }

                                                        }
                                                        else
                                                        {

                                                                if( $pos_gb= strpos(strtoupper($sql),"GROUP BY") )
                                                                {
                                                                        $wc = " WHERE ".str_replace("`", "'", $this->pkey);                                                                     
                                                                        $detail_sql .= $wc. " ". substr($sql,$pos_gb);
                                                                        //      $detail_sql = str_replace("GROUP BY",$wc." GROUP BY",$detail_sql);
                                                                                        
                                                                }
                                                                else
                                                                {
                                                                        if($hvg =  strpos(strtoupper($detail_sql),"HAVING"))
                                                                        {
                                                                                $tmp_ds1 = substr($detail_sql,0,($hvg-1));
                                                                                $tmp_ds2 = substr($detail_sql,$hvg);
                                                                                $detail_sql =$tmp_ds1." and ".str_replace ("`", "'", $this->pkey)." ".$tmp_ds2;
                                                                        }
                                                                        else
                                                                        {                                                       
                                                                                $detail_sql .=" WHERE ".str_replace ("`", "'", $this->pkey);
                                                                        }
                                                                }
                                                        }
                                        }
                                        else
                                        {
                                                        $detail_sql = $this->sql ." WHERE ".str_replace ("`", "'", $this->pkey);
                                        }
                                        //-------------------------------------------------------------
                                        //Si hay clausula limit...  La quitamos !!    (¿Cómo que no? Si !!)                                     
                                        
                                        //debug(" TGRID (LIM 1) : <BR> $detail_sql " ); 
                                        $plim = 0;
                                        if($plim = strpos($detail_sql," LIMIT ") )
                                        {                                       
                                                $detail_sql = substr($detail_sql,0,$plim);
                                        }
                                        
                                        if($this->debug)        echo "\n\n\n<HR> <B>DETAIL SQL :</B>  ".nl2br($detail_sql)."<HR>\n\n\n";
                                        
                                 //debug("[detail_sql (2471) ] : ".$detail_sql);
                                // die();

                                        $rs=$db->Execute($detail_sql);

                                        if($rs)
                                        {


                                                $script.="\n\t\t\t<TABLE ID='".$this->styleid."'   ALIGN='CENTER' WIDTH='100%' HEIGHT='100%' VALIGN='MIDDLE' CELLPADING='1' CELLSPACING='1'  bgcolor='darkgray'>\r";
                                                $script.="\n\t\t\t\t<TR>";
                                                $script.="\n\t\t\t\t\t<TD COLSPAN='2'  VALIGN='top' >";

                                                $close_detail=($this->close_detail_img)?("<IMG SRC='".$this->close_detail_img."' BORDER=0 ALT='".$this->close_detail_alt."'/>"):("X");










                                                $script.="\t\t\t\t<TABLE   ID='".$this->styleid."' BGCOLOR='".$this->lastrowcolor."' ALIGN='center' BORDER=0 CELLSPACING=0 CELLPADDING=1 WIDTH='100%'>\n";
                                                $script.="\t\t\t\t<TR ALIGN='left' VALIGN='middle'>\n";
                                                $script.="\t\t\t\t      <TH WIDTH='99%' ALIGN='CENTER'>Detalle </TH>\n";
                                                $script.="\t\t\t\t      <TH ALIGN='right' ><A HREF='javascript:hi();' >".$close_detail."</A></TH>\n";
                                                $script.="\t\t\t\t</TR>\n";
                                                $script.="\t\t\t\t</TABLE>\n";

                                                $script.="</TD>";
                                                $script.="\n\t\t\t\t</TR>\n";

                                                $titulos = $this->detail_titles;

                                                for($i=0; $i<count($rs->fields); $i++)
                                                {
                                                  if(!empty($rs->fields[$i]) or !empty($titulos[$i]))
                                                         {
                                                                 if(empty($rs->fields[$i])){$rs->fields[$i]="<CENTER> --- </CENTER>";}
                                                                 $script.="\n\t\t\t\t<TR BGCOLOR='".$this->lastrowcolor."'>";

                                                                
                                                                $wtx=(!empty($this->detail_width_titles))?("WIDTH='".$this->detail_width_titles."' "):("");
                                                                
                                                                $script.="\n\t\t\t\t\t<TH align='LEFT' ".$wtx." >".$titulos[$i] ."</TH>";

                                                                 $script.="\n\t\t\t\t\t<TD BGCOLOR='".$this->color1."' ";

                                                                 switch( $this->detail_align[$i] )
                                                                        {
                                                                                case "R":
                                                                                                $script.=" Align='RIGHT' ";
                                                                                                break;

                                                                                case "C" :
                                                                                                $script.=" Align='CENTER' ";
                                                                                                break;

                                                                                case  "L" :
                                                                                                $script.=" Align='LEFT' ";
                                                                                                break;
                                                                                default:
                                                                                                $script.=" Align='LEFT' ";
                                                                        }

                                                                        //------------------------------
                                                                        // FORMATO PARA EL DETALLE.                                                                                                                     
                                                                        if($this->aDetFormat[$i])        $rs->fields[$i]=$this->FieldFormat($rs->fields[$i],$this->aDetFormat[$i]);
                                                                        
                                                                        //------------------------------
                                                                        //Función de transferencia para el detalle.
                                                                        if($this->aDetTransfer[$i])
                                                                                {
                                                                                                $script.=">";
                                                                                                str_replace("'", "´",$rs->fields[$i]);
                                                                                                $_src_code = '$script.=  '.str_replace('*', $rs->fields[$i] ,$this->aDetTransfer[$i]).'; ';                                                                                             
                                                                                                eval($_src_code);
                                                                                                $script.="</TD>";
                                                                                }
                                                                                else
                                                                                                $script.=">".$rs->fields[$i]."</TD>";

                                                                $script.="\n\t\t\t\t</TR>\n";
                                                         }
                                                }


                                                if( $this->del or $this->edit )
                                                {

                                                                $script.="\n\t\t\t\t<TR> ";
                                                                $script.="\n\t\t\t\t\t<TH COLSPAN=2  BGCOLOR='".$this->lastrowcolor."' > ";
                                                                $script.="\n\n\t\t\t\t   <!-- Editar & Borrar -->\n";


                                                                $script.="\n\t\t\t\t<TABLE  ID='".$this->styleid ."' BGCOLOR='".$this->lastrowcolor."' ALIGN='CENTER' WIDTH='100%' HEIGHT='100%' VALIGN='MIDDLE' CELLPADING='0' CELLSPACING='1' >\n";
                                                                $script.="\n\t\t\t\t\t<TR>";
                                                                //--------------------------
                                                                // Botón para borrar !!
                                                                if( $this->del )  
                                                                {

                                                                        if(empty($this->del_img))
                                                                                {
                                                                                        
                                                                                        $push_del = "<BUTTON ID='".$this->styleid."' ";
                                                                                        $push_del .= " onclick=\"javascript:LaunchControl( '".$lr."','".$this->lastrow."','".$this->order."','','','','".$this->pkey."','".$this->controlaction."','".$this->target."'); \" ";
                                                                                        $push_del .= "> Eliminar </BUTTON>";
                                                                                }
                                                                        else
                                                                                {
                                                                                        $push_del = "<A href=\"javascript:LaunchControl( '".$lr."','".$this->lastrow."','".$this->order."','','','','".$this->pkey."','".$this->controlaction."','".$this->target."'); \">";
                                                                                        $push_del .= "<IMG SRC='".$this->del_img."' ALT='".$this->del_alt."' BORDER=0 ></A>";
                                                                                }


                                                                        $script.="\n\t\t\t\t\t\t<TD  ALIGN='LEFT' >";
                                                                        $script.="\n\t\t\t\t\t\t\t".$push_del;
                                                                        $script.="\n\t\t\t\t\t\t</TD>";
                                                                }

                                                                if( $this->edit )  // boton para editar
                                                                {
                                                                        if(empty($this->edit_img))
                                                                                {
                                                                                        $push_ed = "<BUTTON ID='".$this->styleid."' ";
                                                                                        $push_ed .=" onclick=\"javascript:LaunchControl( '".$lr."','".$this->lastrow."','".$this->order."','".$this->pkey."','','".$this->pkey."','','".$this->controlaction."','".$this->target."'); \" ";
                                                                                        $push_ed .=" > Editar </BUTTON>";
                                                                                }
                                                                                else
                                                                                {
                                                                                    $push_ed = "<A HREF=\"javascript:LaunchControl('".$lr."','".$this->lastrow."','".$this->order."','".$this->pkey."','','".$this->pkey."','','".$this->controlaction."','".$this->target."')\"><IMG SRC='".$this->edit_img."' ALT='".$this->edit_alt."' BORDER=0 ></A>";
                                                                                }

                                                                        $script.="\n\t\t\t\t\t\t<TD VALIGN='middle' BGCOLOR='".$this->lastrowcolor."'  ALIGN='RIGHT'> ";
                                                                        $script.="\n\t\t\t\t\t\t\t".$push_ed;
                                                                        $script.="\n\t\t\t\t\t\t</TD>";


                                                                }
                                                                $script.="\n\t\t\t\t\t</TR>\n";
                                                                $script.="\n\t\t\t\t</TABLE>\n\n\t\t\t\t<!-- Fin Editar & Borrar -->\n\n";

                                                                $script.="\n\t\t\t\t</TH></TR>\n";
                                                }

                                                $script.="\n\t\t\t\t</TABLE>\n";
                                        }
                        }
                }
        //--------------------------------------------------------------------------------------------
        // Paginación & Navegación.
        if($this->rows_page)  
                {
                        $script.="\n\t</TD></TR><TR  BGCOLOR='".$this->generalcolor."' >" ;
                        if(count($this->detail_fields)>0)
                        {
                                $script.="\n\t\t<TD COLSPAN=2 ALIGN='left'>" ;
                        }
                        else
                        {
                                $script.="\n\t\t<TD ALIGN='left'>" ;
                        }

                        $script.="\n\n\t\t<!-- Pie de la tabla -->\n\n\t\t";
                        $script.="<TABLE BGCOLOR='".$this->generalcolor."' BORDER=0 CELLSPACING=0 WIDTH='100%' CELLPADING=0><TR><TD ALIGN='left'>" ;

                        $script.="<SPAN ";
                        $script.=(!empty($this->styleid))?(" ID='".$this->styleid."' "):("");

                        $pag_actual = ($this->pags)? ceil($this->lastrow/$this->rows_page):0;

                        $script.="> Pág : ( ".$pag_actual." / ".$this->pags.")  :   ";


                        // Avance por página

                        if( ! $this->top)
                        {
                                $script.="<A HREF=\"javascript:Launch( '0','','".$this->order."','','','',''); \">";

                                if(!empty($this->first_img))
                                {
                                        $script.=       "<SPAN STYLE='position: relative; top: 8'><IMG SRC='".$this->first_img."' ALT='".$this->first_alt."' BORDER='0'/></SPAN>";
                                }
                                else
                                {
                                        $script.="<Font Color='#FF0000'><B>(|&lt;)</B></Font>";
                                }

                                $script.="</A>";
                        }


                        $i=($pag_actual-6);     //-- < Avance rápido hacia atrás.
                        if($i > 0)
                        {
                                $i=$pag_actual;
                                $dif=$pag_actual-11;

                                if($dif<=10)
                                        {$i = 1;}
                                else
                                        {$i -= 10;}

                                $script.=       "<A HREF=\"javascript:Launch( '".(($i*$this->rows_page)- $this->rows_page) ."','','".$this->order."','','','',''); \" >";


                                if(!empty($this->backward_img))
                                {
                                        $script.=       "<SPAN STYLE='position: relative; top: 8'><IMG SRC='".$this->backward_img."' ALT='".$this->backward_alt."' BORDER='0'/></SPAN>";
                                }
                                else
                                {
                                        $script.=       "<Font Color='#FF0000'><B>(&lt;)</B></Font>";
                                }
                                $script.=       "</A>&nbsp;";


                        }

                        for($i=($pag_actual-5); $i<$pag_actual; $i++) //--  < Avance de uno en uno hacia atrás.
                        {

                          if( $i > 0)
                          {
                                  $script.=     "<A HREF=\"javascript:Launch( '".(($i*$this->rows_page)- $this->rows_page) ."','','".$this->order."','','','',''); \" >";
                                  $script.=     " $i ";
                                  $script.=     "</A>&nbsp;";

                          }
                        }



                        for($j=0,$i=$pag_actual; ($i<=$this->pags) and ($j<=5); $j++, $i++ ) //-- > Avance de uno en uno hacia adelante.
                        {
                                $script.=       "<A HREF=\"javascript:Launch('".(($i*$this->rows_page)- $this->rows_page) ."','','".$this->order."','','','',''); \" >";
                            $script.=   ($i==$pag_actual)?("<B><FONT COLOR='#00A000'>[ $i ]</FONT></B>"):(" $i ");
                            $script.=   "</A>&nbsp;";

                        }

                        if($i<$this->pags) //--->> Avance rápido hacia adelante.
                        {
                                $i=$pag_actual;
                                $dif=abs($this->pags-$i);
                                $i+= ($dif<10)?($dif):(10);

                            $script.=   "<A HREF=\"javascript:Launch('".(($i*$this->rows_page)- $this->rows_page) ."','','".$this->order."','','','',''); \" >";

                                if(!empty($this->forward_img))
                                {
                                        $script.=       "<SPAN STYLE='position: relative; top: 8'><IMG SRC='".$this->forward_img."' ALT='".$this->forward_alt."' BORDER='0'/></SPAN>";
                                }
                                else
                                {
                                $script.=       "<Font Color='#FF0000'><B>(&gt;)</B></Font>";
                            }

                          $script.=     "</A>&nbsp;";

                        }


                        if($pag_actual != $this->pags)
                        {
                        //      $script.="<A HREF=\"javascript:Launch( '".$this->lastrow."','','".$this->order."','','','',''); \">"  .$this->next      ."</A>";

                                $end_page = (($this->reg_num - $this->rows_page)<0)?0:($this->reg_num - $this->rows_page);
                                $script.="<A HREF=\"javascript:Launch( '".$end_page."','','".$this->order."','','','',''); \">";

                                if(!empty($this->last_img))
                                {
                                        $script.=       "<SPAN STYLE='position: relative; top: 8'><IMG SRC='".$this->last_img."' ALT='".$this->last_alt."' BORDER='0'/></SPAN>";
                                }
                                else
                                {
                                        $script.="<Font Color='#FF0000'><B>(&gt;|)</B></Font>";
                                }

                                $script.= "</A>";
                        }

                        $script.=" </SPAN>";

                        $script.="</TD>" ;
                        $script.="<TD ALIGN='right'>" ;

                        $atras=( $this->lastrow - 2*$this->rows_page);
                        $atras=($atras < 0)?0:$atras;

                        if($rownum != $this->rows_page)
                        {
                                $dif = abs($rownum -$this->rows_page);
                                $atras=( $this->lastrow + $dif  - 2*$this->rows_page);
                        }


                        //$script.="<TD ALIGN='left' > ";

                        /*Boton de clasificacion*/

                        if($this->clasificacion)
                        {
                                if(empty($_clasificacion1) || empty($this->clasificacion_lista_ids_registros))
                                {
                                        $clasificacion_search="";
                                        $clasificacion_search .= "<A href=\"javascript:".$this->clasificacion_js_script."; document.".$this->name.".elements['_clasificacion1'].value=1; document.".$this->name.".submit()\">";
                                        $clasificacion_search .= "<IMG SRC='".$this->serch_clasificacion_img."' ALT='".$this->serch_clasificacion_alt."' BORDER=0 ></A>";
                                }
                                else
                                {
                                        $clasificacion_search .= "<A href=\"javascript: document.".$this->name.".elements['".$this->clasificacion_field_registros."'].value='';document.".$this->name.".elements['_clasificacion1'].value=''; LaunchControl( '".$lr."','".$this->lastrow."','".$this->order."','".$this->pkey."','','','','',''); \">";
                                        $clasificacion_search .= "<IMG SRC='".$this->unserch_clasificacion_img."' ALT='Remover clasificación' BORDER=0 ></A>";

                                }//if clasificacione
                                $script.= $clasificacion_search."&nbsp;";
                        }//if($this->clasificacion)

                        if($this->serch)
                        {
                                if(empty($_s1))
                                {

                                        if(empty($this->serch_img))             //Poner Autofiltro
                                                {
                                                        $push_serch = "<BUTTON ID='".$this->styleid."' ";
                                                        $push_serch .= " onclick=\"javascript:LaunchControl( '".$lr."','".$this->lastrow."','".$this->order."','".$this->pkey."','2','','','',''); \" ";
                                                        $push_serch .= " > Filtrar </BUTTON>";
                                                }
                                        else
                                                {
                                                        $push_serch .= "<A href=\"javascript:LaunchControl( '".$lr."','".$this->lastrow."','".$this->order."','".$this->pkey."','2','','','',''); \">";
                                                        $push_serch .= "<IMG SRC='".$this->serch_img."' ALT='".$this->serch_alt."' BORDER=0 ></A>";
                                                }
                                }
                                else
                                {
                                        if(empty($this->unserch_img))           //Remover Autofiltro
                                                {
                                                        $push_serch = "<BUTTON ID='".$this->styleid."' ";
                                                        $push_serch .= " onclick=\"javascript:LaunchControl( '".$lr."','".$this->lastrow."','".$this->order."','".$this->pkey."','','','','',''); \" ";
                                                        $push_serch .= " > Remover Filtro </BUTTON>";
                                                }
                                        else
                                                {
                                                        $push_serch .= "<A href=\"javascript: document.".$this->name.".elements['_s1'].value=''; document.".$this->name.".elements['_s2'].value='';  document.".$this->name.".elements['_s3'].value=''; LaunchControl( '".$lr."','".$this->lastrow."','".$this->order."','".$this->pkey."','','','','',''); \">";
                                                        $push_serch .= "<IMG SRC='".$this->unserch_img."' ALT='Remover filtro' BORDER=0 ></A>";
                                                }

                                }

                                $script.= $push_serch."&nbsp;";
                        }

                        if($this->add)
                        {
                                if(empty($this->del_img))
                                        {
                                                $push_add = "<BUTTON ID='".$this->styleid."' ";
                                                $push_add .= " onclick=\"javascript:LaunchControl( '".$lr."','".$this->lastrow."','".$this->order."','".$this->pkey."','1','','','".$this->controlaction."','".$this->target."'); \" ";
                                                $push_add .= " > Nuevo Registro </BUTTON>";
                                        }
                                else
                                        {
                                                $push_add .= "<A href=\"javascript:LaunchControl( '".$lr."','".$this->lastrow."','".$this->order."','".$this->pkey."','1','','','".$this->controlaction."','".$this->target."'); \">";
                                                $push_add .= "<IMG SRC='".$this->add_img."' ALT='".$this->add_alt."' BORDER=0 ></A>";
                                        }

                                $script.= $push_add."&nbsp;";
                        }

                        $script.="</TD>" ;
                        $script.="</TR>" ;
                        //--------------------------------------------------------------------------------
                        if($this->addkey==2)    //Auto filtro
                        {
                                $script.="\n\t<TR>" ;
                                $script.="\n\t\t<TD ALIGN='center' COLSPAN=2>\n\n" ;

                                $script.="\t\t\t<!-- Filtro de información-->\n\n";

                                $script.="\t\t\t<FORM ACTION='$PHP_SELF' METHOD='".$this->method."' ID='".$this->styleid."'>\n";
                                $script.="\t\t\t<INPUT TYPE='hidden' name='lr'          value='".$this->lr."' >\n";
                                $script.="\t\t\t<INPUT TYPE='hidden' name='ar'          value='".$this->ar."' >\n";
                                $script.="\t\t\t<INPUT TYPE='hidden' name='ob'          value='".$this->ob."' >\n";
                                $script.="\t\t\t<INPUT TYPE='hidden' name='_rpp'        value='".$this->rows_page."' >\n";

                                //Parámetros extra
                                if(!empty($this->parameters))
                                {
                                $script.="\n\t\t\t<!-- Parámetros extra -->\n\n";
                                        for($s=0;$s<count($this->parameters);$s++)
                                                $script.="\t\t\t<INPUT TYPE='hidden' name='".$this->parameters[$s]."' value='".$this->paramvalues[$s]."' >\n";
                                }


                                $script.="\t\t\t<B>Autofiltro :</B>  Columna : <SELECT name='_s1' ID='".$this->styleid."'>\n";

                                for($i=0; $i<count($this->filterfields); $i++)
                                {
                                        $script.="\t\t\t\t<OPTION VALUE='".str_replace("'", "`", $this->filterfields[$i])."'>".$this->filtertitles[$i]."</OPTION>\n";
                                }

                                $script.="\t\t\t</SELECT>\n &nbsp;";

                                $script.="\t\t\t Operador : <SELECT name='_s2' ID='".$this->styleid."'>\n";
                                /*
                                        $script.="\t\t\t\t<OPTION VALUE=' LIKE '> ~             </OPTION>\n";
                                        $script.="\t\t\t\t<OPTION VALUE='='     > =             </OPTION>\n";
                                        $script.="\t\t\t\t<OPTION VALUE='>'     > &gt;          </OPTION>\n";
                                        $script.="\t\t\t\t<OPTION VALUE='>='    > &gt;=         </OPTION>\n";
                                        $script.="\t\t\t\t<OPTION VALUE='<'     > &lt;          </OPTION>\n";
                                        $script.="\t\t\t\t<OPTION VALUE='<='    > &lt;=         </OPTION>\n";
                                        $script.="\t\t\t\t<OPTION VALUE='<>'    > &lt;&gt;      </OPTION>\n";
                                */      
                                
                                
                                //      Es mas amigable, y funciona bien pero ocupa mucho espacaio en Grids pequeños.
                                $script.="\t\t\t\t<OPTION VALUE=' LIKE '> parecido              </OPTION>\n";
                                $script.="\t\t\t\t<OPTION VALUE='=' > exactamente igual </OPTION>\n";
                                $script.="\t\t\t\t<OPTION VALUE='>' > mayor que                 </OPTION>\n";
                                $script.="\t\t\t\t<OPTION VALUE='>='> mayor igual               </OPTION>\n";
                                $script.="\t\t\t\t<OPTION VALUE='<' > menor que                 </OPTION>\n";
                                $script.="\t\t\t\t<OPTION VALUE='<='> menor igual               </OPTION>\n";
                                $script.="\t\t\t\t<OPTION VALUE='<>'> diferente                 </OPTION>\n";
                                

                                $script.="\t\t\t</SELECT>\n&nbsp;";
                                $script.="\t\t\tValor : <INPUT ID='".$this->styleid."' TYPE='TEXT' NAME='_s3' SIZE='25'>\n";
                                $script.="\t\t\t&nbsp;&nbsp;<INPUT ID='".$this->styleid."' TYPE='SUBMIT' NAME='_aplica' VALUE='Aplicar' >\n";

                                $script.="\t\t\t</FORM>\n\n";                           
                                
                                
                                // Con este pequeño HELP subsanamos la dificultad para el usuario de la 
                                // sustutución de palabras por símbolos. 
                /*              
                                $script.="      <TABLE BGCOLOR='black' CELLSPACE='1' ALIGN='center' STYLE='color:black; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 9px;' >
                                                        <TR BGCOLOR='lightgrey' ALIGN='center'> 
                                                                                <TH>&nbsp;&nbsp; Parecido                       &nbsp;&nbsp;</TH>
                                                                                <TH>&nbsp;&nbsp; Igual                          &nbsp;&nbsp;</TH>       
                                                                                <TH>&nbsp;&nbsp; Mayor que                      &nbsp;&nbsp;</TH>       

                                                                                <TH>&nbsp;&nbsp; Mayor igual            &nbsp;&nbsp;</TH>       
                                                                                <TH>&nbsp;&nbsp; Menor que              &nbsp;&nbsp;</TH>       

                                                                                <TH>&nbsp;&nbsp; Menor igual            &nbsp;&nbsp;</TH>       
                                                                                <TH>&nbsp;&nbsp; Diferente                      &nbsp;&nbsp;</TH>       
                                                        </TR>                                    
                                                        <TR BGCOLOR='".$this->generalcolor."' ALIGN='center' STYLE='font-size: 12px;'>                                                                                  
                                                                                <TH> ~            </TH>
                                                                                <TH> =            </TH>
                                                                                <TH> &gt;         </TH>

                                                                                <TH> &gt;=        </TH>
                                                                                <TH> &lt;         </TH>

                                                                                <TH> &lt;=        </TH>
                                                                                <TH> &lt;&gt; </TH>
                                                        </TR>
                                                        </TABLE > \n";
                        */      
                                $script.="\t\t\t<!-- Fin filtro de información-->\n\n";

                                $script.="\n\t\t</TD>" ;
                                $script.="\n\t</TR>" ;

                        }
                        $script.="</TABLE>\n\n" ;
                        $script.="\t\t<!-- Fin del pie de la tabla -->\n\t\t";
                        $script.="\n\t\t</TD>" ;
                        $script.="\n\t</TR>" ;
                }

                $script.="\n</TD></TR></TABLE>\n ";             
                // Tabla exterior.
                if(strpos($HTTP_USER_AGENT,"MSIE"))
                {
                        $script.="\n\t\t\t</TABLE>\n\n\t <!--Fin Detalle -->\n\n";
                }
                else
                {
                        $script.="\n\t\t\t</TABLE>\n\t\n<!--Fin Detalle -->";
                        $script.="\n</TD></TR></TABLE>\n\n\t \n\n";                     
                }

            $script.="<script>          \n";
                $script.="              function hi()\n";
                $script.="              {\n";
                
                if(strpos($HTTP_USER_AGENT,"MSIE"))
                        $script.="                              document.all.d1.style.display='none';\n";
                else
                        $script.="                              document.".$this->name.".submit(); \n";
                
                $script.="              }\n";
                $script.="</script>\n";

                return($script);
        }
} // Fin de la clase  TGrid 