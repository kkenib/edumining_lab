<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once __DIR__ . '/CB/CB_array_helper.php';

// �迭 �� ã��
if ( ! function_exists('array_search_key'))
{
    function array_search_key($array, $key, $sel_val) {
        foreach($array as $val) {
            if($sel_val == $val[$key]) return true;
        }
        return false;
    }
}

// �迭 �� ã��
if ( ! function_exists('array_search_key_index')) 
{
    function array_search_key_index($array, $key, $sel_val) {
        foreach($array as $k=>$val) {
            if($sel_val == $val[$key]) return $k;
        }
        return false;
    }
}

// ���߹迭�� Ư��Ű�� �籸��
if ( ! function_exists('array_convert_key')) 
{
    function array_convert_key($array, $key) {
        $r_array = array();
        foreach($array as $val) {
            $r_array[$val[$key]] = $val;
        }
        return $r_array;
    }
}


// ���߹迭�� Ư��Ű�� �׷�ȭ
if ( ! function_exists('array_convert_group_key')) 
{
    function array_convert_group_key($array, $key) {
		$r_array = array();
		foreach($array as $val) {
			$r_array[$val[$key]][] = $val;
		}
		return $r_array;
	}
}

// ���߹迭 Ư������ ����
if ( ! function_exists('array_get_value')) 
{
    function array_get_value($array, $key) {
        $r_array = array();
        foreach($array as $val) {
            $r_array[] = $val[$key];
        }
        return $r_array;
    }
}