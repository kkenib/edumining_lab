<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Connect to Stibee API class
 * Copyright (c) The IMC <www.theimc.co.kr>
 * @author The IMC
 */

/**
 * 스티비 (뉴스레터 전송 플랫폼) API 연동을 위한 컨트롤러
 */
class Stibee
{
	private $API_KEY = '997da5a8113641bd2f0496a1dffeceb438b58a8455375b789b2edd790bd790c2054e2a9ab182ae05a9a250b5f539f34442d11a31175d8523a4673f9c0989653a';
	private $EVENT_OCCURED_BY = 'MANUAL';
	private $CONFIRM_EMAIL_YN = 'N';
	private $END_POINT_URL = 'https://api.stibee.com/v1';
	private $LIST_ID = '15185';

	function __construct() {
		$this->CI =& get_instance();
    }
	
	// 구독 등록
     public function subscribers($member) {
		$url_format = "%s/lists/%s/subscribers";
		$url = sprintf($url_format, $this->END_POINT_URL, $this->LIST_ID);
		
		$subscribers = array();
		for($i=0; $i<count($member); $i++) {
			$subscribers[$i]['email'] = $member[$i]['email'];
			$subscribers[$i]['name'] = $member[$i]['name'];
			$subscribers[$i]['mem_userid'] = (string)$member[$i]['mem_userid'];
		}

		$param = array(
			"eventOccuredBy" => $this->EVENT_OCCURED_BY,
			"confirmEmailYN" => $this->CONFIRM_EMAIL_YN,
			"subscribers" => $subscribers
		);
		
		$body = json_encode($param);
		$this->call_api("POST", $url, $body);
    }
	
	// 구독 해제
	public function unsubscribers($member) {
		$url_format = "%s/lists/%s/subscribers";
		$url = sprintf($url_format, $this->END_POINT_URL, $this->LIST_ID);

		$subscribers = array();
		for($i=0; $i<count($member); $i++) {
			$subscribers[$i] = $member[$i]['email'];
		}

		$body = json_encode($subscribers);
		$this->call_api("DELETE", $url, $body);
	}

	// API 전송
	public function call_api($method, $url, $body) {

		$curl = curl_init($url);
		$header = array("AccessToken : " .$this->API_KEY, "Content-Type : application/json");

		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		switch($method) {
			case "GET":
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
				break;

			case "POST":
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
				break;

			case "PUT":
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
				break;

			case "DELETE":
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
				break;
		}

		$res = curl_exec($curl);

		log_message('debug', json_encode($res));
		log_message('debug', var_dump($res));

		curl_close($curl);

	}
}
