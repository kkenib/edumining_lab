<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once __DIR__ . '/Point.php';
/**
 * IMC_Point class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 포인트 추가 및 삭제를 관리하는 class 입니다.
 */
//class Payment extends CI_Controller
class IMC_Point extends Point
{
    //private $CI;
    public function __construct() {
        //$this->CI = & get_instance();
        parent::__construct();
    }

    public function get_membership_date_term($day_num, $hypn='-') {
		$todaytime = time();
		$membership_date = date("Y".$hypn."m".$hypn."d", strtotime("+".($day_num+1)." day", $todaytime));

		return $membership_date;
    }


    // 유료회원 기간 시작일, 종료일 기간 체크
	public function chk_membership_start_end_date($sdate, $edate) {
		$sdate = intval(str_replace("-", "", $sdate));
		$edate = intval(str_replace("-", "", $edate));
		
		if($sdate > $edate) {
			return 1;
		} else {
			return 0;
		}
	}


    // 주문번호 생성
	public function create_order_number() {
		$time = "MISP_".str_replace(".", "", microtime(true));
		return $time;
	}
	
	// VAT 10% ADD
	public function get_pay_price_vat($pay_price) {
		$vat_price = intval($pay_price) * 0.1;
		$sum_price = intval($pay_price) + $vat_price;
		
		return $sum_price;
	}
	
	public function vat_state_str($pay_vat) {
		if($pay_vat == 1) {
			return "(VAT 10% 포함)";
		} else {
			return "";			
		}
	}

}
?>