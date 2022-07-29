<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Word Count model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Word_model extends CB_Model
{

    /**
     * 테이블명
     */
    public $_table = 'w_keyword_append_list';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'idx'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }

	public function get_admin_word_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
		$this->db->set_dbprefix('');
		$this->primary_key = 'idx';
		$this->_table = 'w_keyword_append_list';
        $result = $this->_get_list_common($select = '', $join='', $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
		$this->db->set_dbprefix('t_');
        return $result;
    }

	public function get_keyword_data($str = '')
    {
        if (empty($str)) {
            return false;
        }
        $this->db->from($this->_table);
        $this->db->where('idx', $str);
        $result = $this->db->get();

        return $result->row_array();
    }


}
