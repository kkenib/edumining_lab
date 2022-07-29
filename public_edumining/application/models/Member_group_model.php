<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Member group model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Member_group_model extends CB_Model
{

    /**
     * 테이블명
     */
    public $_table = 'group_list';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'group_idx'; // 사용되는 테이블의 프라이머리키

    public $search_sfield = '';

    function __construct()
    {
        parent::__construct();
    }

	public function get_group_name($str = '', $str1 = '')
    {
        if (empty($str) or empty($str1)) {
            return false;
        }
        $this->db->from($this->_table);
        $this->db->where('mem_id', $str);
		$this->db->where('group_name', $str1);
        $result = $this->db->get();

        return $result->result_array();
    }

	public function get_by_both($str = '', $select = '')
    {
        if (empty($str)) {
            return false;
        }
        if ($select) {
            $this->db->select($select);
        }
        $this->db->from($this->_table);
        $this->db->where('mem_id', $str);

        $result = $this->db->get();

        return $result->result_array();
    }

	public function get_group_name_id($str = '', $str1 = '')
    {
        if (empty($str) or empty($str1)) {
            return false;
        }
        $this->db->from($this->_table);
        $this->db->where('mem_userid', $str);
		$this->db->where('group_name', $str1);
        $result = $this->db->get();

        return $result->row_array();
    }

}
