<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('reorder_multiple_upload')) {
    function reorder_multiple_upload($files) {
        $_files = array();
        
        if (is_array($files)) {
            foreach($files as $key => $parts) {
                foreach($parts as $index => $value) {
                    $_files[$index][$key] = $value;
                }
            }
        }
        
        return $_files;
    }
}

