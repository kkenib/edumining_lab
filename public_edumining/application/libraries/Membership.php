<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Point class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 회원 이용권 기간을 체크하여 유료페이지 접근을 막는 class입니다.
 */
class Membership extends CI_Controller
{

    private $CI;

    function __construct()
    {
        $this->CI = & get_instance();
        $this->CI->load->helper( array('array'));
    }


    /**
     * 유료회원 기간 체크
     **/
    public function membership_expired_chk($service_type)
    {
        // 나중에 이용권 제한시 사용
        return;
        $today = date('Y-m-d');
        $mem_id = $this->CI->member->item('mem_id');
        $this->CI->load->model('Membership_status_model');

        if( $this->CI->member->is_admin() ) {
            // 관리자
        } else {
            $result = $this->CI->Membership_status_model->membership_expired_chk($today, $mem_id);
            if($result['mbs_service_type_name']) {
                $service_scale = $this->get_membership_service_scale($result['mbs_service_type_name']);

                if(!$result['membership_edate']) {
                    alert('현재 입장한 서비스는 유료페이지입니다. 이용권을 구매해주세요.');
                } else {
                    if( in_array($service_type, $service_scale) ) {
                        // 이용권 입장 허용
                    } else {
                        alert('현재 사용중이신 이용권은 현재 페이지를 이용할 수 없는 이용권입니다');
                    }
                }

            } else {
                alert('현재 입장한 서비스는 유료페이지입니다. 이용권을 구매해주세요.');
            }
        }
    }

    // 이용권 범위
    public function get_membership_service_scale($mbs_service_type_name) {
        $service_scale = $this->config->config['membership'][$result['mbs_service_type_name']]['service'];
        return $service_scale;
    }


    /**
     * 포인트를 추가합니다
     */
    public function insert_point($mem_id = 0, $point = 0, $content = '', $poi_type = '', $poi_related_id = '', $poi_action = '')
    {
        // 포인트 사용을 하지 않는다면 return
        if ( ! $this->CI->cbconfig->item('use_point')) {
            return false;
        }

        // 포인트가 없다면 업데이트 할 필요 없음
        $point = (int) $point;
        if (empty($point)) {
            return false;
        }

        // 회원아이디가 없다면 업데이트 할 필요 없음
        $mem_id = (int) $mem_id;
        if (empty($mem_id) OR $mem_id < 1) {
            return false;
        }

        if (empty($content)) {
            return false;
        }

        if (empty($poi_type) && empty($poi_related_id) && empty($poi_action)) {
            return false;
        }

        $member = $this->CI->Member_model->get_by_memid($mem_id, 'mem_id');

        if ( ! element('mem_id', $member)) {
            return false;
        }

        $this->CI->load->model('Point_model');

        // 이미 등록된 내역이라면 건너뜀
        if ($poi_type OR $poi_related_id OR $poi_action) {
            $where = array(
                'mem_id' => $mem_id,
                'poi_type' => $poi_type,
                'poi_related_id' => $poi_related_id,
                'poi_action' => $poi_action,
            );
            $cnt = $this->CI->Point_model->count_by($where);

            if ($cnt > 0) {
                return false;
            }
        }

        $insertdata = array(
            'mem_id' => $mem_id,
            'poi_datetime' => cdate('Y-m-d H:i:s'),
            'poi_content' => $content,
            'poi_point' => $point,
            'poi_type' => $poi_type,
            'poi_related_id' => $poi_related_id,
            'poi_action' => $poi_action,
        );
        $this->CI->Point_model->insert($insertdata);

        $sum = $this->CI->Point_model->get_point_sum($mem_id);

        $updatedata = array(
            'mem_point' => $sum,
        );
        $this->CI->Member_model->update($mem_id, $updatedata);

        return $sum;
    }
}