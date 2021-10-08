<?php
/*
 * Funciones utiles para sanitizar las entradas de datos del usuario.
 * Elimina JavaScript, Estilos CSS, HTML y SQL Injection.
 * Extraido de CSS-Tricks.
 * Junio.2012
 */


/*
 * Elimina Javascript,HTML,CSS y comentarios multilinea.
 */
function cleanInput($input) {
 
        $search = array(
            '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
            '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
            '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
            '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
        );

            $output = preg_replace($search, '', $input);
            return $output;
  }


/*
 * Usa la funcion anterior y ademas elimina lo potencialmente peligroso para MySQL.
 */ 
function sanitize($input) {
        if (is_array($input)) {
            foreach($input as $var=>$val) {
                $output[$var] = sanitize($val);
            }
        }
        else {
            if (get_magic_quotes_gpc()) {
                $input = stripslashes($input);
            }
            $input  = cleanInput($input);
            $output = mysql_real_escape_string($input);
        }
        return $output;
}




?>
