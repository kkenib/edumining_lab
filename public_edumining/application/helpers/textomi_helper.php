<?php
function region_domain($region = '') {
    $CI =& get_instance();
    
    $domain_prefix = $CI->config->item('_domain_prefix');
    $replace_domain = $CI->config->item('_replace_domain');
    
    array_walk($domain_prefix, function(&$item, $key, $domain) {
        $item = trim(is_array($item) ? $item[0] : $item);
        $item = $item == '' ? $domain : $item.'.'.$domain;
    }, $replace_domain);
        
        if($region) {
            return $domain_prefix[$region];
        } else {
            return $domain_prefix;
        }
}

function region($region = '') {
    $CI =& get_instance();
    
    $_region = $CI->config->item('_region');
    
    if($region) {
        return $_region[$region];
    } else {
        return $_region;
    }
}

function location_html($men_id, $layout, $title='') {
    function _location($men_id, $layout, $title) {
        $nav = array();
        $menu = element(0, element('menu', $layout));
        foreach($menu as $key => $mval) {
            if($skey == $men_id) {
                $nav[] = element('men_name', $mval);
                return $nav;
            }
            
            if(element($key, element('menu', $layout))) {
                $smenu = element($key, element('menu', $layout));
                foreach($smenu as $skey => $sval) {
                    if($skey == $men_id) {
                        $nav[] = element('men_name', $mval);
                        $nav[] = element('men_name', $sval);
                        return $nav;
                    }
                    
                    if(element($skey, element('menu', $layout))) {
                        $smenu2 = element($skey, element('menu', $layout));
                        
                        foreach($smenu2 as $skey2 => $sval2) {
                            if($skey2 == $men_id) {
                                $nav[] = element('men_name', $mval);
                                $nav[] = element('men_name', $sval);
                                $nav[] = element('men_name', $sval2);
                                return $nav;
                            }
                        }
                    }
                }
            }
        }
        
        $nav[] = $title;
        return $nav;
    }
    
    $CI =& get_instance();
    $CI->load->view(element('layout_skin_path', $layout) . '/box/location', array('nav' => _location($men_id, $layout, $title)));
}


/**
 * 엘라스틱서치 쿼리 스트링 (연산자포함)
 */
function es_query_string($base_keyword, $match_keyword='', $include_keyword='', $exclude_keyword='', $sp=',') {
    // $base_keyword or $match_keyword 둘중 하나 반드시 있어야함
    $base_keyword = trim(preg_replace('/[ ]+/', ' ', preg_replace('/[\'"%()]/', '', $base_keyword)));
    $match_keyword = trim(preg_replace('/[ ]+/', ' ', preg_replace('/[\'"%()]+/', '', $match_keyword)));
    $include_keyword = trim(preg_replace('/[ ]+/', ' ', preg_replace('/[\'"%()]+/', '', $include_keyword)));
    $exclude_keyword = trim(preg_replace('/[ ]+/', ' ', preg_replace('/[\'"%()]+/', '', $exclude_keyword)));
    
    $fts_keyword = '';
    $guide_keyword = '';
    
    $k_t = 0;
 
    if($base_keyword) {
        $k_i = 0;
        $fts_keyword_ = '';
        $base_keyword_ = explode($sp, $base_keyword);
        foreach($base_keyword_ as $val) {
            $val = trim($val);
            if(!$val) continue;
            
            $fts_keyword_ .= ($fts_keyword || $fts_keyword_ ? ' OR ' : ' ') . '"'.$val.'"';            
            $guide_keyword .= ' '.$val.'';
        }
        
        $fts_keyword_ = ' ('.trim($fts_keyword_).')';
        $fts_keyword .= $fts_keyword_;
        
        $fts_keyword = trim($fts_keyword);
        $guide_keyword = '<b>\'' . trim($guide_keyword) . '\'</b>에 대한 검색결과 중';
        $k_t += $k_i;
    }
    
    // 포함 (+)
    if($include_keyword) {
        $k_i = 0;
        $include_keyword_ = explode($sp, $include_keyword);
        foreach($include_keyword_ as $val) {
            $val = trim($val);
            if(!$val) continue;
            
            $fts_keyword .= ($fts_keyword || $fts_keyword_ ? ' AND ' : ' ').$val;
            $guide_keyword .= ' <b>\''.$val.'\'</b>';
        }
        
        $fts_keyword = trim($fts_keyword);
        $guide_keyword .= '(을)를 포함하고';
        $k_t += $k_i;
    }
    
    // 제외 (-)
    if($exclude_keyword) {
        $k_i = 0;
        $exclude_keyword_ = explode($sp, $exclude_keyword);
        foreach($exclude_keyword_ as $val) {
            $val = trim($val);
            if(!$val) continue;
            
            $fts_keyword .= ' -'.$val.'';
            $guide_keyword .= ' <b>\''.$val.'\'</b>';
        }
        
        $fts_keyword = trim($fts_keyword);
        $guide_keyword .= '(을)를 제외한';
        $k_t += $k_i;
    }
    
    //echo $fts_keyword;
    
    $guide_keyword = preg_replace('/검색결과 중$/', '', $guide_keyword);
    $guide_keyword = preg_replace('/일치하고$/', '일치하는', $guide_keyword);
    $guide_keyword = preg_replace('/포함하고$/', '포함한', $guide_keyword);
    $guide_keyword .= ' 검색 결과입니다.';
    
    return array($fts_keyword, $guide_keyword, $k_t);
}

/**
 * 엘라스틱서치 기본 쿼리문 작성
 */
function es_basic_queryinfo($doctype, $keyword, $nospam = true, $def_op='AND', $terms=null, $should=null) {
	$CI =& get_instance();
	$index = $CI->config->item('elastic_index');
	
    if ($doctype == "total") {
        $type = $CI->config->item('elastic_doctype');
    }else {
        $type = $doctype;
    }
    
    $type_field = null;
    $_must = array();
    $_should = array();
    $query = ['query' => ['bool' => ['must' => null, 'should' => null]]];
    
    if($nospam === true) {
    	$_must[0] = ['query_string' => ['default_field' => 'spam', 'query' => 'N']];
    }
    
	if($type_field != null) {
		$_must[] = ['terms' => ['type' => $type_field]];
	}
        
	if($keyword !== null) {
		$_must_keyword = ['query_string' => [
                			'fields' => ['title','body'],
                			'query' => $keyword]];
            
		if($def_op != null) {
			$_must_keyword['query_string']['default_operator'] = $def_op;
		}
            
		$_must[] = $_must_keyword;
	}
	
	// 수집 채널 별 쿼리 필요 시 추가
	$should_term = array();
	if($terms != null) {
		$_query = ['bool' => ['should' => null]];
		foreach ($terms as $key => $value) {
			$should_term[] = ['term' => [ 'channel' => $value]];
		}
		$_query['bool']['should'] = $should_term;
		$_must[] = $_query;
	}
	
	$query['query']['bool']['must'] = $_must;
        
	// 한개 이상 매치
	if($should != null && count($should) > 0) {
		$_should = [];
		foreach($should as $row) {
			switch($row['type']) {
				case 'keyword' :
					$_should[] = [ "query_string" => [
                        			"fields" => ["title", "body"],
                        			"query" => $row['value'],
                        			"default_operator" => (isset($row['operator']) ? $row['operator'] : "AND")
                        		]];
					break;
				case 'userid' :
					$_should[] = ["term" => ["user_id" => $row['value']]];
					break;
			}
		}
            
		$query['query']['bool']['minimum_should_match'] = 1;
	}
	$query['query']['bool']['should'] = $_should;
        
	return [
		'index' => $index,
		'type' => $type,
		'query' => $query
	];
}

/**
 * 엘라스틱 서치에서 원문 데이터 조회시
 */
function es_preview_queryinfo($doctype, $keyword, $sdate='', $edate='', $nospam = true, $def_op='AND', $terms=null, $should=null) {
    // $index, $type, $query
    $result = es_basic_queryinfo($doctype, $keyword, $nospam, $def_op, $terms, $should);
    extract($result);
    
    $CI =& get_instance();
    $index = $CI->config->item('elastic_index');
    
    // size, from 수정하여 사용
    $query['size'] = 10;
    // $query['from'] = 100;
    $query['sort'] = [
        ['_score' => ['order' => 'desc']],
        ['date' => ['order' => 'desc']]
    ];
    
    /*
     $query['post_filter'] = [
     'range' => ['date' => [
     'gte' => +date('Ymd', $stime),
     'lte' => +date('Ymd', $etime)
     ]]
     ];
     */
    $must_range = [];
    if($sdate) {
        $stime = strtotime($sdate);
        $must_range['range']['date']['gte'] = +date('Ymd', $stime);
    }
    if($edate) {
        $etime = strtotime($edate);
        $must_range['range']['date']['lte'] = +date('Ymd', $etime);
    }
    if(count($must_range) > 0) {
        $query['query']['bool']['must'][] = $must_range;
    }
    
    return [
        'index' => $index,
        'type' => $type,
        'query' => $query
    ];
}

function es_parser_preview_query($result) {
    $r = (object) array('total' => 0, 'hists' => array());
    if(!isset($result->hits) || !isset($result->hits->hits)) return $r;
    
    foreach($result->hits->hits as $k=>$row) {
        $row2 = $row->_source;
        $row2->_num = $k + 1;
        $row2->_score = $row->_score;
        $row2->_index = preg_replace('/^(kr|cn|us)_/', '', $row->_index);
        $row2->_type = $row->_type;
        $lists[] = $row2;
    }
    
    $lists = array();
    foreach($result->hits->hits as $row) {
        $lists[] = get_object_vars($row->_source);
    }
    
    $r->total = $result->hits->total;
    $r->hits = $lists;
    
    return $r;
}

/**
 * 엘라스틱 서치에서 일별 카운트 조회시
 */
function es_count_queryinfo($channel, $keyword, $sdate, $edate, $nospam = true, $def_op='AND', $should=null) {
    
    $stime = strtotime($sdate);
	$etime = strtotime($edate . " +1 day");
    //$size = 1000; //ceil(($etime - $stime) / 86400) + 1;
    $size = ceil(($etime - $stime) / 86400) + 1;
    
    // $index, $type, $query
    $result = es_basic_queryinfo($channel, $keyword, $nospam, $def_op, $should);
    extract($result);
    
    // 기간 그룹
    $aggs_terms = [
        'field' => 'date',
        // script
        'order' => ['_term' => 'asc'],
        'size' => $size
    ];
    
    // 쿼리
    $query['size'] = 0;
    $query['aggs'] = [
        '_group_by' => [
            'range' => [
                'field' => 'date',
                'ranges' => [[
                    'from' => date('Ymd', $stime),
                    'to' => date('Ymd', $etime)
                ]]
            ],
            'aggs' => ['_group_by_date' => ['terms' => $aggs_terms]]
        ]
    ];

    return [
        'index' => $index,
        'type' => $type,
        'query' => $query
    ];
}

/**
 * 엘라스틱 서치에서 일별 카운트 조회 후 데이터 파싱
 */
function es_parser_count_query($result) {
    // _group_by, _group_by_date 는 es_count_queryinfo에서 지정해준 이름대로
    if(!isset($result->aggregations) || !isset($result->aggregations->_group_by)) return array();
    
    return $result->aggregations->_group_by->buckets[0]->_group_by_date->buckets;
}

/**
 * 엘라스틱 서치에서 날짜 카운트 전체 조회 후 데이터 파싱
 */
function es_parser_allcount_query($result) {
    // _group_by, _group_by_date 는 es_count_queryinfo에서 지정해준 이름대로
    if(!isset($result->aggregations) || !isset($result->aggregations->_group_by)) return array();
    
    return $result->aggregations->_group_by->buckets[0]->doc_count;
}


/**
 * 주/월/일 단위 기준 날짜로 날짜 x생성
 */
function es_create_date_x($sdate, $edate, $datetype='W') {
    $stime = strtotime($sdate);
    $etime = strtotime($edate);
    
    switch($datetype) {
        case 'M' :
            $stime = strtotime(date('Y-m-01', $stime));
            if(date('d', $etime) < date('t', $etime)) {
                $etime = $etime - date('d', $etime) * 86400;
            }
            
            $sdate = date('Y-m-d', $stime);
            $edate = date('Y-m-d', $etime);
            break;
        case 'W' :
            $stime = $stime - date('N', $stime) * 86400 + 86400; // 월
            $etime = $etime - date('w', $etime) * 86400; // 일
            
            $sdate = date('Y-m-d', $stime);
            $edate = date('Y-m-d', $etime);
            break;
    }
    
    $dates = array();
    $index = array();
    
    $i = 0;
    while($stime <= $etime) {
        switch($datetype) {
            case 'M' :
                $stime = strtotime(date('Y-m-01', $stime));
                $dates[] = date('Y-m-d', $stime);
                $index[+(date('Ymd', $stime))] = $i++;
                $stime = strtotime(date('Y-m-01', $stime) . ' +1month');
                break;
            case 'W' :
                // 01~07 -> 01로 표시
                $stime = $stime - date('N', $stime) * 86400 + 86400;
                // 01~07 -> 08로 표시
                //$stime = $stime + (7 - date('N', $stime)) * 84600 + 86400;
                $dates[] = date('Y-m-d', $stime);
                $index[+(date('Ymd', $stime))] = $i++;
                //$stime += 86400;
                $stime += 86400 * 7;
                break;
            default :
                $dates[] = date('Y-m-d', $stime);
                $index[+(date('Ymd', $stime))] = $i++;
                $stime += 86400;
                break;
        }
    }
    
    return [
        'sdate' => $sdate,
        'edate' => $edate,
        'dates' => $dates,
        'index' => $index
    ];
}

/**
 * 주/월/일 단위 기준 날짜 변경
 */
function es_date_x($date, $datetype='W') {
    $_time = strtotime(substr($date, 0, 4).'-'.substr($date, 4, 2).'-'.substr($date, 6, 2));
    
    switch($datetype) {
        case 'M' : $_date = date('Ym01', $_time); break;
        case 'W' : $_date = date('Ymd', $_time - date('N', $_time) * 86400 + 86400); break;
        //case 'W' : $_date = date('Ymd', $_time + (7 - date('N', $_time)) * 86400 + 86400); break;
        default : $_date = date('Ymd', $_time); break;
    }
    
    return $_date;
}


// 전주, 전월 날짜 가져옴
function returnArrayPrevWeekDate($sdate, $edate, $datetype="W") {
	$sdate = str_replace(".", "-", $sdate);
	$edate = str_replace(".", "-", $edate);
	if($datetype == "W") {
		$prev_sdate = date("Y-m-d", strtotime($sdate. " -1 week"));
		$prev_edate = date("Y-m-d", strtotime($edate. " -1 week"));
	} else {
		$prev_sdate = date("Y-m-d", strtotime($sdate." first day of -1 month"));
		$day_count = date("t", strtotime($prev_sdate));
		$date_array = explode("-", $prev_sdate);
		$date_array[2] = $day_count;
		$prev_edate = join("-", $date_array);
	}
	
	$array = array($prev_sdate, $prev_edate);
	return $array;
}

// 증감률 문자열 반환
// $a(비교대상), $b(지난주,월) 빈도수
function reteOfIncreaseStr($a, $b) {
	$str = "";
	if( intval($a) > intval($b) ) {
		$str = "UP";
	} else if( intval($a) < intval($b) ) {
		$str = "DOWN";
	} else if( intval($a) == intval($b) ) {
		$str = "SAME";
	}
	return $str;
}

/**
 * 증감률 %계산
 * @param unknown $a
 * @param unknown $b
 * @param string $round
 * @param number $digit
 * @return number
 */
function rateOfIncreaseCalc($a, $b, $round = "round", $digit=2) {
	# 이태환 팀장 요청 증감률 계산식 ((a - b) / a) * 100 로 변경
	# $a(비교대상:금년,금월), $b(기준:작년,전월)
	# $result = (($a - $b) / $a) * 100;
	
    if($b > 0) {
    	if(intval($a) > (intval($b)))
    		$result = (((intval($a) - intval($b))) / intval($a)) * 100;
    	else
    		$result = (((intval($a) - intval($b))) / intval($b)) * 100;
    } else {
		$result = 0;
    }
    
	switch($round) {
		case "round":
			$result = round($result, $digit);
		break;
		case "ceil":
			ceil($value);
			$result = ceil($result);
		break;
		case "floor":
			$result = floor($result);
		break;
	}
	
	return $result;
}
