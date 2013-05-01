<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('generate_slug')) {
	function generate_slug($string) {
		//Unwanted:  {UPPERCASE} ; / ? : @ & = + $ , . ! ~ * ' ( )
        $string = strtolower($string);
        //Strip any unwanted characters
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        //Clean multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", " ", $string);
        //Convert whitespaces and underscore to dash
        $string = preg_replace("/[\s_]/", "-", $string);
		
        return $string;
	}
	
	function get_full_name($string) {		
		$string = preg_replace('/[\s-_]+/', ' ', $string);
		
		return ucwords($string);
	}
}