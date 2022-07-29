<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once __DIR__ . '/CB/CB_Model.php';

/**
 * Model Class
 */
class IMC_Model extends CB_Model {

	public function __construct() {
        parent::__construct();
    }

	protected function _sql_stmt_value($parameters) {
		if(!is_array($parameters)) $parameters = array($parameters);
		if(count($parameters) == 0) return '';

		return implode(array_fill(0, count($parameters), '?'), ',');
	}

	protected function _sql_stmt_where($parameters) {
		if(!is_array($parameters)) return '';
		if(count($parameters) == 0) return '';

		return implode(array_map(function($k) { return $k . " = ?"; }, array_keys($parameters)), ' and ');
	}

	protected function _sql_limit(&$sql, &$parameter, $page, $list_size = 0, $limit = 10) {
		if($list_size < 1) $list_size = isset($this->pagination->per_page) ? $this->pagination->per_page : $limit;

		$start = ($page <= 1 ? 0 : $page - 1) * $list_size;
		$sql .= ' limit ?, ?';

		$parameter[] = $start;
		$parameter[] = $list_size;
	}
}
