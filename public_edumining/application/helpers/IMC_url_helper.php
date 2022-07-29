<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once __DIR__ . '/CB/CB_url_helper.php';

if(!function_exists("current_page_name")) {
    function current_page_name() {
        $uri_arr = explode("/", uri_string());
        $active_page_length = sizeof($uri_arr);
        /*
        if($_SERVER['REMOTE_ADDR'] == '119.201.16.4') {
            print_r($uri_arr);
        }
        */

        //return ($active_page_length > 1) ? $uri_arr[$active_page_length-1] : '';
        return $uri_arr[$active_page_length-1];
    }
}