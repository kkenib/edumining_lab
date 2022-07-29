<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Textomi Init hook class
 *
 * Copyright (c) The IMC
 */
class _Textomi_Init {
	// pre system
	function ajax_post_csrf() {
	//	global $_POST;

		if($_SERVER['HTTP_X_ACTION_CALL_TYPE'] == "AJAX" && $_SERVER['REQUEST_METHOD'] == 'POST') {
			$csrf_token = trim($_SERVER['HTTP_X_CSRF_TOKEN']);
			if($csrf_token != '') {
				$_POST['csrf_test_name'] = $csrf_token;
			}
		}
	}
}