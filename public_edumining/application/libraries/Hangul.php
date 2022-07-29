<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hangul {

	public function _utf8_strlen($str) { 
		return mb_strlen($str, 'UTF-8'); 
	}

	public function _utf8_charAt($str, $num) { 
		return mb_substr($str, $num, 1, 'UTF-8'); 
	}

	public function _utf8_ord($ch) {
		$len = strlen($ch);
		if($len <= 0) {
			return false;
		}

		$h = ord($ch{0});
		if ($h <= 0x7F) {
			return $h;
		}

		if ($h < 0xC2) {
			return false;
		}

		if ($h <= 0xDF && $len>1) {
			return ($h & 0x1F) <<  6 | (ord($ch{1}) & 0x3F);
		}

		if ($h <= 0xEF && $len>2) {
			return ($h & 0x0F) << 12 | (ord($ch{1}) & 0x3F) << 6 | (ord($ch{2}) & 0x3F);          
		}

		if ($h <= 0xF4 && $len>3) {
			return ($h & 0x0F) << 18 | (ord($ch{1}) & 0x3F) << 12 | (ord($ch{2}) & 0x3F) << 6 | (ord($ch{3}) & 0x3F);
		}

		return false;

	}

	// 한글 자음 초성 분리
	public function cho_hangul($str) {
	  $cho = array("ㄱ","ㄲ","ㄴ","ㄷ","ㄸ","ㄹ","ㅁ","ㅂ","ㅃ","ㅅ","ㅆ","ㅇ","ㅈ","ㅉ","ㅊ","ㅋ","ㅌ","ㅍ","ㅎ");
	  $result = "";
	  for ($i=0; $i<$this->_utf8_strlen($str); $i++) {
		$code = $this->_utf8_ord($this->_utf8_charAt($str, $i)) - 44032;
		if ($code > -1 && $code < 11172) {
		  $cho_idx = $code / 588;      
		  $result .= $cho[$cho_idx];
		}
	  }
	  return $result;
	}

	public function init_hangul_arr() {
		$initial_hangul_list = array("ㄱ","ㄴ","ㄷ","ㄹ","ㅁ","ㅂ","ㅅ","ㅇ","ㅈ","ㅊ","ㅋ","ㅌ","ㅍ","ㅎ");
		$arr = array();

		for($i=0; $i<count($initial_hangul_list); $i++) {

			$arr[$i]['name'] = $initial_hangul_list[$i];
			$arr[$i]['value'] = 0;
		}
		
		$idx = count($initial_hangul_list);

		for($j=$idx; $j<($idx+26); $j++) {
			$arr[$j]['name'] = chr(65+($j - $idx));
			$arr[$j]['value'] = 0;
		}
		
		return $arr;
	}

}

