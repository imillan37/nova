<?php

/**
 *
 * @author MarsVoltoso (CFA)
 * @category Model
 * @created Mon Sep 15, 2014
 * @version 1.0
 */	
 
function __autoload( $class ){

	$WWW_ROOT = dirname(__FILE__);
	$DS       = DIRECTORY_SEPARATOR;
	
	
		$class = $WWW_ROOT.$DS.str_replace("\\",$DS,$class).".php"; 
	
	echo 	$class;
	
		if( !file_exists($class)  ){
			throw new Exception(" El Archivo '{$class}' no existe ");
		}
	
	require_once($class);
		
} 


?>