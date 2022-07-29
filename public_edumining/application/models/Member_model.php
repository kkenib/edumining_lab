<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Member model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Member_model extends CB_Model
{

    /**
     * 테이블명
     */
    public $_table = 'member';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'mem_id'; // 사용되는 테이블의 프라이머리키
    public $report_key = 'no';

    public $search_sfield = '';

    function __construct()
    {
        parent::__construct();
    }


    public function get_by_memid($memid = 0, $select = '')
    {
        $memid = (int) $memid;
        if (empty($memid) OR $memid < 1) {
            return false;
        }
        $where = array('mem_id' => $memid);
        return $this->get_one('', $select, $where);
    }


    public function get_by_userid($userid = '', $select = '')
    {
        if (empty($userid)) {
            return false;
        }
		
        $where = array('mem_userid' => $userid, 'mem_level >'=> 0);
//log_message("info", json_encode($where));
        return $this->get_one('', $select, $where);
    }

    public function get_by_username($username = '', $select = '')
    {
    	if (empty($username)) {
    		return false;
    	}
    	$where = array('mem_username' => $username);
    	return $this->get_one('', $select, $where);
    }
    
    public function get_by_email($email = '', $select = '')
    {
        if (empty($email)) {
            return false;
        }
        $where = array('mem_email' => $email);
        return $this->get_one('', $select, $where);
    }

	public function get_by_email_name($email = '',$name='', $select = '')
    {
        if (empty($email)) {
            return false;
        }
		if (empty($email)) {
            return false;
        }
        $where = array('mem_email' => $email);
        return $this->get_one('', $select, $where);
    }

    public function get_by_selected($type = '', $str = '', $select = '')
    {
    	if (empty($str)) {
    		return false;
    	}
    	
    	if($type === 'mem_id') {
    		$where = array('mem_id' => $str);
    	} else if($type === 'mem_userid') {
    		$where = array('mem_userid' => $str);
    	} else if($type === 'mem_username') {
    		$where = array('mem_username' => $str);
    	} else if($type === 'mem_email') {
    		$where = array('mem_email' => $str);
    	}
    	
    	return $this->get_one('', $select, $where);
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
        $this->db->where('mem_userid', $str);
        $this->db->or_where('mem_email', $str);
        $result = $this->db->get();
        return $result->row_array();
    }


    public function get_superadmin_list($select='')
    {
        $where = array(
            'mem_is_admin' => 1
        );
        $result = $this->get('', $select, $where);

        return $result;
    }


    public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $result = $this->_get_list_common($select = '', $join='', $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
        return $result;
    }


    public function get_register_count($type = 'd', $start_date = '', $end_date = '', $orderby = 'asc')
    {
        if (empty($start_date) OR empty($end_date)) {
            return false;
        }
        $left = ($type === 'y') ? 4 : ($type === 'm' ? 7 : 10);
        if (strtolower($orderby) !== 'desc') $orderby = 'asc';

        $this->db->select('count(*) as cnt, left(mem_register_datetime, ' . $left . ') as day ', false);
        $this->db->where('left(mem_register_datetime, 10) >=', $start_date);
        $this->db->where('left(mem_register_datetime, 10) <=', $end_date);
        $this->db->group_by('day');
        $this->db->order_by('mem_register_datetime', $orderby);
        $qry = $this->db->get($this->_table);
        $result = $qry->result_array();

        return $result;
    }

	public function get_group_member_list($str ='', $str1='')
    {
        if (empty($str)) {
            return false;
        }

		$this->db->from($this->_table);
        $this->db->where('mem_parent', $str);
		$this->db->where('mem_group_voice', $str1);
        $result = $this->db->get();
		
        return $result->result_array();
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

    public function get_report_list($select = '', $join = '', $limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR'){
        $this->db->set_dbprefix('');
        $this->primary_key = 'edu_report.no';
        $this->_table = 'edu_report';
        $result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
        $this->db->set_dbprefix('t_');
        return $result;
        return $result;
    }


	public function get_create_user_id_count($str ='', $str1=null)
    {
        if (empty($str)) {
            return false;
        }
		$this->db->from($this->_table);
        $this->db->like('mem_userid', $str, 'after');
		$this->db->order_by('mem_id', 'DESC');
        $result = $this->db->get();
        return $result->result_array();
    }

	public function get_member_group_top($str ='', $str1='')
    {
        if (empty($str)) {
            return false;
        }

		$this->db->from($this->_table);
        $this->db->where('mem_parent', $str);
		$this->db->where('mem_group_voice', $str1);
		$this->db->order_by('mem_id','asc');
        $result = $this->db->get();
		
        return $result->row_array();
    }

    public function getMemberByNameEmail($queryCondition) {
        $name = $queryCondition["name"];
        $email = $queryCondition["email"];
        $qry = "SELECT mem_userid FROM t_member
                WHERE mem_username = '$name' AND mem_email = '$email'";
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    public function updatePassword($queryCondition) {
        $userId = $queryCondition["user_id"];
        $password = $queryCondition["password"];
        $qry = "UPDATE t_member SET mem_password='$password' WHERE mem_userid='$userId'";
        $result = $this->db->query($qry);
        return $result;
    }
	
}
