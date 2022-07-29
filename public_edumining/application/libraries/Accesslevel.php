<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Accesslevel class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 권한이 있는지 없는지 판단하는 class 입니다.
 */
class Accesslevel extends CI_Controller
{

    private $CI;

    function __construct()
    {
		// 1 ~ 100 : 100 : 최고관리자
        $this->CI = & get_instance();
    }


    /**
     * 접근권한이 있는지를 판단합니다
     */
    public function is_accessable($access_type = '', $level = '', $group = '', $check = array())
    {
        $access_type = (string) $access_type;
        if (empty($access_type)) { // 모든 사용자
            return true;
        } elseif ($access_type === '1') { // 로그인 사용자
            if ($this->CI->member->is_member() === false) {
                return false;
            }
            return true;
        } elseif ($access_type === '100') { // 관리자
            if ($this->CI->member->is_admin($check) === false) {
                return false;
            }
            return true;

        } elseif ($access_type === '3') { // 특정레벨이상인자
            if ($this->CI->member->is_admin($check) !== false) {
                return true;
            }
            if ($this->CI->member->is_member() === false) {
                return false;
            }
            if ($this->CI->member->item('mem_level') < $level) {
                return false;
            }
            return true;
        } elseif ($access_type === '4') { // 특정그룹소속인자
            if ($this->CI->member->is_admin($check) !== false) {
                return true;
            }
            if ($this->CI->member->is_member() === false) {
                return false;
            }
            if ($this->CI->member->item('mem_group') !== $group) {
                return false;
            }
            if ($this->CI->member->item('mem_level') < $level) {
                return false;
            }
            return true;
		}
    }


    /**
     * 접근권한이 없으면 alert 를 띄웁니다
	 * alertmessage === FALSE 메시지 표시하지 않음
     */
    public function check($access_type = '', $level = '', $group = '', $alertmessage = '', $check = array())
    {
        if (empty($alertmessage) && $alertmessage !== FALSE) {
			$alertmessage = $this->CI->member->is_member() ? '접근 권한이 없습니다' : '로그인 후 이용가능합니다.';
        }
        $accessable = $this->is_accessable($access_type, $level, $group, $check);
		
        if ($accessable) {
            return true;
        } else {
			if($this->CI->member->is_member()) {
				alert($alertmessage);
			} else {
                $this->CI->session->set_flashdata(
                    'message',
                    $alertmessage
                );
                redirect('login?url=' . urlencode(current_full_url()));
			}
            return false;
        }
    }

    /**
     * 접근권한이 없으면 alert 를 띄웁니다
	 * alertmessage === FALSE 메시지 표시하지 않음
     */
    public function check_json($access_type = '', $level = '', $group = '', $alertmessage = '', $check = array())
    {
        if (empty($alertmessage) && $alertmessage !== FALSE) {
			$alertmessage = $this->CI->member->is_member() ? '접근 권한이 없습니다' : '로그인 후 이용가능합니다.';
        }
        $accessable = $this->is_accessable($access_type, $level, $group, $check);

        if ($accessable) {
            return true;
        } else {
			header('content-type: text/json');
			header('cache-control: no-cache, must-revalidate');
			header('pragma: no-cache');

			$data = array();
			$data['result'] = 'afterlogin';
			$data['data'] = [];
			$data['error'] = $alertmessage;

			if($this->CI->member->is_member()) {
				$data['result'] = 'accesslevel';
			}

			echo json_encode($data);
			exit;
        }
    }
}
