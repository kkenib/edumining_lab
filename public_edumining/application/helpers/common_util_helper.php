<?php
/**
 * 주에 해당하는 시작, 종료날짜를 Y-m-d~Y-m-d로 리턴
 * date      null or date
 * addweek   thisweek+n, thisweek-n
 * firstday  mon ~ sun
 */
function date_of_week($date = null, $addweek = 0, $firstday = 'mon', $format = 'Y-m-d', $sp = '~') {
	$daynames = array('sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat');
	if(!is_numeric($baseday)) {
		$firstday = array_search($firstday, $daynames);
	}

	if($firstday < 0 || $firstday > 6) $firstday = 0;

	if(!$date) {
		$time = time();
	} else if(is_numeric($date) && (int)$date > 1000000000) {
		$time = $date;
	} else {
		$time = strtotime($date);
	}

	$dn = date('w', $time);
	$t = (-6 - $dn + $firstday - 1) % 7;
	$t += $addweek * 7;

	$stime = $time + 86400 * $t;
	$etime = $stime + 518400;

	return date($format, $stime) . $sp . date($format, $etime);
}

/**
 * 주에 해당하는 날짜를 배열로 리턴
 * date      null or date
 * addweek   thisweek+n, thisweek-n
 * firstday  mon ~ sun
 */
function dates_of_week($date = null, $addweek = 0, $firstday = 'mon', $format = 'Y-m-d') {
	$ret = array('', '', '', '', '', '', '');
	$term = date_of_week($date, $addweek, $firstday, $format, '~');

	list($sdate, $edate) = explode('~', $term);

	$stime = strtotime($sdate);
	$etime = strtotime($edate);

	for($i = 0, $time = $stime; $i < 7; $i++, $time += 86400) {
		$ret[$i] = date($format, $time);
	}

	return $ret;
}
/**
 * 주에 해당하는 시작날짜를 리턴
 * date      null or date
 * addweek   thisweek+n, thisweek-n
 * firstday  mon ~ sun
 */
function startdate_of_week($date = null, $addweek = 0, $firstday = 'mon', $format = 'Y-m-d') {
	$term = date_of_week($date, $addweek, $firstday, $format, '~');
	list($sdate, $edate) = explode('~', $term);

	return $sdate;
}



function date_of_month($date = null, $addmonth = 0, $format = 'Y-m-d', $sp = '~') {
	if(!$date) {
		$time = time();
	} else if(is_numeric($date) && (int)$date > 1000000000) {
		$time = $date;
	} else {
		$time = strtotime($date);
	}

	if($addmonth > 0) $addmonth = '+' . abs($addmonth);

	$stime = strtotime(date('Y-m-d', $date) . ' ' . $addmonth . ' month');
	$etime = $date;

	return date($format, $stime) . $sp . date($format, $etime);

}


/**
 * search_keyword ㄱ ~ ㅎ, A ~ B
 *
 */
function trans_search_keyword($keyword = '') {
    $kr_arr = array(
        'ㄱ'=>'가',
        'ㄴ'=>'나',
        'ㄷ'=>'다',
        'ㄹ'=>'라',
        'ㅁ'=>'마',
        'ㅂ'=>'바',
        'ㅅ'=>'사',
        'ㅇ'=>'아',
        'ㅈ'=>'자',
        'ㅊ'=>'차',
        'ㅋ'=>'카',
        'ㅌ'=>'타',
        'ㅍ'=>'파',
        'ㅎ'=>'하'
    );
    
    if(keyword) {
        if( preg_match('/[ㄱ-ㅎ]/', $keyword) ) {
            return $kr_arr[$keyword];
        } else {
            // 영문.숫자
            return $keyword;
        }
        
    } else {
        return '';
    }
}


/*
 * url_contents 관련 함수
 */
function url_contents_parse_header($url, $param, $conetntsType = 'x-www-form-urlencoded') {
    $conetntsType = strtolower($conetntsType);
    $ret = ['url' => '', 'url_info' => [], 'header'=> [], 'data' => ''];

    $url_info = parse_url($url);
    $url = $url_info['scheme'] . '://' . $url_info['host'] .':'. $url_info['port'] . $url_info['path'];

    $header = [];
    switch($conetntsType) {
        case 'json':
        case 'application/json':
            if($param == null) $param = array();
            if(is_string($param) && $param != '') {
                parse_str($param, $out);
                $param = $out;
            }
            if($url_info['query']) {
                parse_str($url_info['query'], $out);					
                $param = array_merge($param, $out);
            }
            $data = json_encode($param, true);
            $size = strlen($data);

            $header[] = 'Content-type: application/json';
            $header[] = "Content-length: {$size}";

            break;

        case 'multipart':
        case 'form-data':
        case 'formdata':
        case 'multipart/form-data':
            $boundary = '--'.uniqid("php_");
            $header[] = 'Content-type: multipart/form-data; boundary={$boundary}';
            $data = '';
            foreach($param as $k=>$p) {
                $data .= "--{$boundary}\r\n";
                $data .= "Content-Disposition: form-data; name=\"{$k}\"\r\n\r\n";
                $data .= trim($p)."\r\n\r\n";
            }

            $data .= "--{$boundary}--\r\n";
            break;

        case 'raw':
            $data = $param;
            $size = strlen($data);

            $header[] = "Content-length: {$size}";

            break;

        default:
            if($param == null) $param = array();
            if(is_string($param) && $param != '') {
                parse_str($param, $out);
                $param = $out;
            }
            if($url_info['query']) {
                parse_str($url_info['query'], $out);					
                $param = array_merge($param, $out);
            }
            $data = http_build_query($param);
            $size = strlen($data);

            $header[] = 'Content-type: application/x-www-form-urlencoded';
            $header[] = "Content-length: {$size}";

            break;
    }

    $ret['url'] = $url;
    $ret['url_info'] = parse_url($url);
    $ret['header'] = $header;
    $ret['data'] = $data;

    return $ret;
}

function curl_contents($url, $param, $method='POST', $conetntsType = 'x-www-form-urlencoded') {
    $req = url_contents_parse_header($url, $param, $conetntsType);
    $url = $req['url'];
    $url_info = $req['url_info'];
    $req_header = $req['header'];
    $req_data = $req['data'];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $req_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $req_header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $res = curl_exec($ch);

    if(curl_error($ch)) {
        $res = '{"error":TRUE, "errstr":' . curl_error($ch) . "}";
    }
    curl_close($ch);

    return $res;
}

function url_contents($url, $param, $method='POST', $conetntsType = 'x-www-form-urlencoded', $is_debug=false) {
    $method = strtoupper($method);
    if($method == 'GET') $conetntsType = 'x-www-form-urlencoded';

    $req = url_contents_parse_header($url, $param, $conetntsType);
    $url = $req['url'];
    $url_info = $req['url_info'];
    $req_header = $req['header'];
    $req_data = $req['data'];

    $uri = $url_info['path'];
    if($method == 'GET' && $req_data) {
        $uri .= '?'.$req_data;
        $req_data = '';
    }

    $res_data = [];
    $res_data['error'] = FALSE;
    $data = '';

    $host = $url_info['host'];
    if(!$host) {
        $host = $_SERVER['SERVER_NAME'];
    }
    $port = $url_info['port'];
    if(!$port) {
        if(strtolower($url_info['scheme'] == 'https')) $port = 443;
        else $port = 80;
    }
    $fp = fsockopen($url_info['host'], $port, $errno, $errstr, 5);
    if ($fp) {
        fputs($fp, "POST {$url_info['path']} HTTP/1.1\r\n");
        fputs($fp, "Host: {$host}\r\n");
        if($method != 'GET') foreach($req_header as $h) fputs($fp,$h."\r\n");
        fputs($fp, "Connection: Close\r\n\r\n");
        if($req_data) fputs($fp, $req_data."\r\n");
        fputs($fp, "\r\n");

        $header = array();
        while (!feof($fp)) $data .= fgets($fp,4096);
        fclose ($fp);

        if($is_debug == true) {
            $res_data['debug_data'] = $data;
        }

        if($data != '') {
            $i = strpos($data, "\r\n\r\n");
            
            $header_ = array();
            $r_header = '';
            if($i > 0) {
                $header = trim(substr($data, 0, $i));
                $data = trim(substr($data, $i));
                
                $header = explode("\r\n", $header);
                foreach($header as $val) {
                    if(preg_match('#HTTP/1[.]1#i', $val)) {
                        list($protocol, $http_code, ) = explode(' ', $val);
                        $header_['protocol'] = trim($protocol);
                        $header_['status'] = trim($http_code);
                        $r_header = $val;
                    } else if(preg_match('#Content-Type[ ]*:([^;]*);[ ]*charset[ ]*=[ ]*([a-z0-9_-]+)#i', $val, $match)) {
                        $header_['charset'] = strtolower(trim($match[2]));
                        $header_['content-type'] = strtolower(trim($match[1]));
                    } else {
                        list($key, $val) = explode(':', $val);
                        $header_[strtolower(trim($key))] = trim($val);
                    }
                }
            }
            $header = $header_;
            
            if(isset($header['charset']) && $header['charset'] != '' && $header['charset'] != 'utf-8') {
                $data = iconv($header['charset'], 'utf-8', $data);
                $header['charset'] = 'utf-8';
            }
        }

        $res_data['header'] =$header;
        $res_data['data'] = $data;

        if($header['status'] != 200) {
            $res_data['error'] = TRUE;
            $errno = $header['status'];
            $errstr = preg_replace('#HTTP/1[.]1[ ]+[0-9]+[ ]*#i', '', $r_header);
        }
    } else {
        $res_data['error'] = TRUE;
    }
    
    $res_data['errno'] = $errno;
    $res_data['errstr'] = $errstr;

    
    return $res_data;
}

function json_post_contents($url, $param, $type='json', $is_debug = false) {
    $ret = url_contents($url, $param, 'POST', $type, $is_debug);

    if($ret['error']) return $ret;

    if($ret['data']) $ret['data'] = json_decode($ret['data'], true);
    else $ret['data'] = array();

    $err = json_last_error();
    if($err) {
        $ret['error'] = TRUE;
        $ret['errno'] = $err;
        $ret['errstr'] = 'json decode: ' . json_last_error_msg();
    }	

    return $ret;
}

function json_get_contents($url, $param, $is_debug = false) {
    $ret = url_contents($url, $param, 'GET', 'x-www-form-urlencoded', $is_debug);

    if($ret['error']) return $ret;

    if($ret['data']) $ret['data'] = json_decode($ret['data'], true);
    else $ret['data'] = array();

    $err = json_last_error();
    if($err) {
        $ret['error'] = TRUE;
        $ret['errno'] = $err;
        $ret['errstr'] = 'json decode: ' . json_last_error_msg();
    }

    return $ret;
}

