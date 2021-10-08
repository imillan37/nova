<?php
/**
 * DOMPDF - PHP5 HTML to PDF renderer
 *
 * File: $RCSfile: page_cache.cls.php,v $
 * Created on: 2004-07-23
 *
 * Copyright (c) 2004 - Benj Carson <benjcarson@digitaljunkies.ca>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this library in the file LICENSE.LGPL; if not, write to the
 * Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 * 02111-1307 USA
 *
 * Alternatively, you may distribute this software under the terms of the
 * PHP License, version 3.0 or later.  A copy of this license should have
 * been distributed with this file in the file LICENSE.PHP .  If this is not
 * the case, you can obtain a copy at http://www.php.net/license/3_0.txt.
 *
 * The latest version of DOMPDF might be available at:
 * http://www.dompdf.com/
 *
 * @link http://www.dompdf.com/
 * @copyright 2004 Benj Carson
 * @author Benj Carson <benjcarson@digitaljunkies.ca>
 * @package dompdf

 */

/* $Id: page_cache.cls.php 216 2010-03-11 22:49:18Z ryan.masten $ */

/**
 * Caches individual rendered PDF pages
 *
 * Not totally implmented yet.  Use at your own risk ;)
 * 
 * @access private
 * @package dompdf
 * @static
 */
class Page_Cache {

  const DB_USER = "dompdf_page_cache";
  const DB_PASS = "some meaningful password";
  const DB_NAME = "dompdf_page_cache";
  
  static private $__connection = null;
  






static $diff = array (
130 => 'quotesinglbase',
131 => 'florin',
132 => 'quotedblright',
133 => 'ellipsis',
134 => 'dagger',
135 => 'daggerdbl',
136 => 'circumflex',
137 => 'perthousand',
// 138 => '{Underscore}',
139 => 'guilsinglleft',
140 => 'OE',
145 => 'quoteleft',
146 => 'quoteright',
147 => 'quotedblleft',
148 => 'quotedblright',
149 => 'bullet',
150 => 'endash',
151 => 'emdash',
152 => 'tilde',
153 => 'trademark',
// 154 => '{Underscore}',
155 => 'guilsinglright',
156 => 'oe',
159 => 'Ydieresis',
// 160 => '{Nonbreaking space}',
161 => 'exclamdown',
162 => 'cent',
163 => 'sterling',
164 => 'currency',
165 => 'yen',
166 => 'brokenbar',
167 => 'section',
168 => 'dieresis',
169 => 'copyright',
170 => 'ordfeminine',
171 => 'guillemotleft',
172 => 'logicalnot',
// 173 => '{Soft hyphen}',
174 => 'registered',
175 => 'macron',
176 => 'degree',
177 => 'plusminus',
178 => 'twosuperior',
179 => 'threesuperior',
180 => 'acute',
181 => 'mu',
182 => 'paragraph',
183 => 'periodcentered',
184 => 'cedilla',
185 => 'onesuperior',
186 => 'ordmasculine',
187 => 'guillemotright',
188 => 'onequarter',
189 => 'onehalf',
190 => 'threequarters',
191 => 'questiondown',
192 => 'Agrave',
193 => 'Aacute',
194 => 'Acircumflex',
195 => 'Atilde',
196 => 'Adieresis',
197 => 'Aring',
198 => 'AE',
199 => 'Ccedilla',
200 => 'Egrave',
201 => 'Eacute',
202 => 'Ecircumflex',
203 => 'Edieresis',
204 => 'Igrave',
205 => 'Iacute',
206 => 'Icircumflex',
207 => 'Idieresis',
208 => 'Eth',
209 => 'Ntilde',
210 => 'Ograve',
211 => 'Oacute',
212 => 'Ocircumflex',
213 => 'Otilde',
214 => 'Odieresis',
215 => 'multiply',
216 => 'Oslash',
217 => 'Ugrave',
218 => 'Uacute',
219 => 'Ucircumflex',
220 => 'Udieresis',
221 => 'Yacute',
222 => 'Thorn',
223 => 'germandbls',
224 => 'agrave',
225 => 'aacute',
226 => 'acircumflex',
227 => 'atilde',
228 => 'adieresis',
229 => 'aring',
230 => 'ae',
231 => 'ccedilla',
232 => 'egrave',
233 => 'eacute',
234 => 'ecircumflex',
235 => 'edieresis',
236 => 'igrave',
237 => 'iacute',
238 => 'icircumflex',
239 => 'idieresis',
240 => 'eth',
241 => 'ntilde',
242 => 'ograve',
243 => 'oacute',
244 => 'ocircumflex',
245 => 'otilde',
246 => 'odieresis',
247 => 'divide',
248 => 'oslash',
249 => 'ugrave',
250 => 'uacute',
251 => 'ucircumflex',
252 => 'udieresis',
253 => 'yacute',
254 => 'thorn',
255 => 'ydieresis'
);


  function init() {
    if ( is_null(self::$__connection) ) {
      $con_str = "host=" . DB_HOST .
        " dbname=" . self::DB_NAME .
        " user=" . self::DB_USER .
        " password=" . self::DB_PASS;
      
      if ( !self::$__connection = pg_connect($con_str) )
        throw new Exception("Database connection failed.");
    }
  }
  
  function __construct() { throw new Exception("Can not create instance of Page_Class.  Class is static."); }

  private static function __query($sql) {
    if ( !($res = pg_query(self::$__connection, $sql)) )
      throw new Exception(pg_last_error(self::$__connection));
    return $res;
  }
  
  static function store_page($id, $page_num, $data) {
    $where = "WHERE id='" . pg_escape_string($id) . "' AND ".
      "page_num=". pg_escape_string($page_num);

    $res = self::__query("SELECT timestamp FROM page_cache ". $where);

    $row = pg_fetch_assoc($res);
    
    if ( $row ) 
      self::__query("UPDATE page_cache SET data='" . pg_escape_string($data) . "' " . $where);
    else 
      self::__query("INSERT INTO page_cache (id, page_num, data) VALUES ('" . pg_escape_string($id) . "', ".
                     pg_escape_string($page_num) . ", ".
                     "'". pg_escape_string($data) . "')");

  }

  static function store_fonts($id, $fonts) {
    self::__query("BEGIN");
    // Update the font information
    self::__query("DELETE FROM page_fonts WHERE id='" . pg_escape_string($id) . "'");

    foreach (array_keys($fonts) as $font)
      self::__query("INSERT INTO page_fonts (id, font_name) VALUES ('" .
                    pg_escape_string($id) . "', '" . pg_escape_string($font) . "')");
    self::__query("COMMIT");
  }
  
//   static function retrieve_page($id, $page_num) {

//     $res = self::__query("SELECT data FROM page_cache WHERE id='" . pg_escape_string($id) . "' AND ".
//                           "page_num=". pg_escape_string($page_num));

//     $row = pg_fetch_assoc($res);

//     return pg_unescape_bytea($row["data"]);
    
//   }

  static function get_page_timestamp($id, $page_num) {
    $res = self::__query("SELECT timestamp FROM page_cache WHERE id='" . pg_escape_string($id) . "' AND ".
                          "page_num=". pg_escape_string($page_num));

    $row = pg_fetch_assoc($res);

    return $row["timestamp"];
    
  }

  // Adds the cached document referenced by $id to the provided pdf
  static function insert_cached_document(CPDF_Adapter $pdf, $id, $new_page = true) {
    $res = self::__query("SELECT font_name FROM page_fonts WHERE id='" . pg_escape_string($id) . "'");

    // Ensure that the fonts needed by the cached document are loaded into
    // the pdf
    while ($row = pg_fetch_assoc($res)) 
      //$pdf->get_cpdf()->selectFont($row["font_name"]);
			$pdf->get_cpdf()->selectFont($row["font_name"],array('encoding'=>'WinAnsiEncoding','differences'=>self::$diff));
    
    $res = self::__query("SELECT data FROM page_cache WHERE id='" . pg_escape_string($id) . "'");

    if ( $new_page )
      $pdf->new_page();

    $first = true;
    while ($row = pg_fetch_assoc($res)) {

      if ( !$first ) 
        $pdf->new_page();
      else 
        $first = false;        
      
      $page = $pdf->reopen_serialized_object($row["data"]);
      //$pdf->close_object();
      $pdf->add_object($page, "add");

    }
      
  }



}

Page_Cache::init();
