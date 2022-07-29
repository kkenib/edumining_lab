<?php
/**
 * 분석 결과 파일 경로
 * data 변환시킬 변수 배열
 */
function sta_path($type, $data) {
	$CI =& get_instance();
	$sta_data_path = $CI->config->item('sta_data_path');
	$path = element($type, $sta_data_path);

	if(!$path) return FALSE;
	
	$data['domainname'] = DOMAIN_NAME;
	if(preg_match_all('/\\{([a-z0-9_-]+)\\}/i', $path, $matches)) {
		$matches = array_unique($matches[1]);
		foreach($matches as $key) {
			$val = trim($data[$key]);
			if(preg_match('/[.\/]+/i', $val)) {
				return FALSE;
			}
			$path = str_replace('{'.$key.'}', $val, $path);
		}
	}

	$path = preg_replace('/[\/]{2,}/', '/', $path);

	return $path;
}

/**
 * 워드클라우드 데이터 로드
 * file_path : array or string
 * array(
 *   array("keyword"=>"k1", "frequency"=>0, "distance"=>0),
 *   array("keyword"=>"k2", "frequency"=>0, "distance"=>0),
 *   array("keyword"=>"k3", "frequency"=>0, "distance"=>0)
 * ),
 * array(0=>"k1", 1=>"k2", 2=>"k3") // 인덱스
 */
function load_wordcloud($file_path, $limit_line = 0) {
	$data = array();
	$sort_word = array();
	$sort_freq = array();
	$file_path_arr = is_array($file_path) ? $file_path : array($file_path);
	foreach($file_path_arr as $k => $file_path) {

		if(!file_exists($file_path)) continue;

		$fp = fopen($file_path, 'r');
		if($fp) {
			$lcnt = 0;
			while(($line = fgetcsv($fp, 1000, "\t")) !== false) {
				if($limit_line > 0 && $lcnt > $limit_line) break;
				$lcnt++;

				list($keyword, $freq, $dist) = $line;
				if(trim($keyword) == "") continue;

				if($k != 0 && ($_idx = array_search($keyword, $sort_word)) !== false) {
					$sort_freq[$_idx] += (int)$freq;
					$data[$_idx]['frequency'] += (int)$freq;
					$data[$_idx]['distance'] += $dist;
				} else {
					$sort_word[] = $keyword;
					$sort_freq[] = (int)$freq;
					$data[] = array(
						'keyword' => $keyword,
						'frequency' => (int)$freq,
						'distance' => $dist,
						'extravar' => count($line) > 3 ? array_slice($line, 3) : null
					);
				}
			}
			fclose($fp);
		}
	}

	if(count($sort_word) > 0) {
		array_multisort($sort_freq, SORT_DESC, $sort_word, SORT_ASC, $data);
	}
	
	if($sort_freq == null) {
		array_push($sort_freq, 0);
	}
	
	$max = max($sort_freq);

	return array($data, array_unique($sort_word), $max);
}

/**
 * 연관키워드 데이터 로드
 * file_path : array or string
 * array(
 *   // k1에 대한 연관어
 *   array("keyword1"=>"k1", "keyword2"=>"", "frequency"=>0),
 *   array("keyword1"=>"k1", "keyword2"=>"", "frequency"=>0),
 *   array("keyword1"=>"k1", "keyword2"=>"", "frequency"=>0),
 *   // k2에 대한 연관어
 *   array("keyword1"=>"k2", "keyword2"=>"", "frequency"=>0),
 *   array("keyword1"=>"k2", "keyword2"=>"", "frequency"=>0),
 *   array("keyword1"=>"k2", "keyword2"=>"", "frequency"=>0)
 * ),
 * array(0=>"k1", 3=>"k2") // 인덱스
 */
function load_ngram($file_path, $limit_line = 0) {
	$data = array();
	$sort_word = array();
	$sort_freq = array();
	$search_word = array();
    
	$file_path_arr = is_array($file_path) ? $file_path : array($file_path);
	foreach($file_path_arr as $k => $file_path) {
	    // 임시 처리
	    // if(!file_exists($file_path)) continue;
		$fp = fopen($file_path, 'r');
		if($fp) {
			$lcnt = 0;
			while(($line = fgetcsv($fp, 1000, "\t")) !== false) {
				if($limit_line > 0 && $lcnt > $limit_line) break;
				$lcnt++;

				list($keyword, $tkeyword, $freq) = $line;
				if(trim($keyword) == "") continue;
				$skey = $keyword + "/" + $tkeyword;

				if($k != 0 && ($_idx = array_search($skey, $search_word)) !== false) {
					$sort_freq[$_idx] += (int)$freq;
					$data[$_idx]['frequency'] += (int)$freq;
				} else {
					$search_word[] = $skey;
					$sort_word[] = $keyword;
					$sort_freq[] = (int)$freq;
					$data[] = array(
						'keyword1' => $keyword,
						'keyword2' => $tkeyword,
						'frequency' => (int)$freq,
						'extravar' => count($line) > 3 ? array_slice($line, 3) : null
					);
				}
			}
			fclose($fp);
		}
	}

	if(count($sort_word) > 0) {
		array_multisort($sort_word, SORT_ASC, $sort_freq, SORT_DESC, $data);
		$sort_word = array_unique($sort_word);
	}

	return array($data, $sort_word);
}


function load_ngram_freq($file_path, $limit_line = 0) {
	$data = array();
	$sort_word = array();
	$sort_freq = array();
	$search_word = array();

	$file_path_arr = is_array($file_path) ? $file_path : array($file_path);
	foreach($file_path_arr as $k => $file_path) {
		if(!file_exists($file_path)) continue;

		$fp = fopen($file_path, 'r');
		if($fp) {
			$lcnt = 0;
			while(($line = fgetcsv($fp, 1000, "\t")) !== false) {
				if($limit_line > 0 && $lcnt > $limit_line) break;
				$lcnt++;

				list($keyword, $tkeyword, $freq) = $line;
				if(trim($keyword) == "") continue;
				$skey = $keyword + "/" + $tkeyword;

				if($k != 0 && ($_idx = array_search($skey, $search_word)) !== false) {
					$sort_freq[$_idx] += (int)$freq;
					$data[$_idx]['frequency'] += (int)$freq;
				} else {
					$search_word[] = $skey;
					$sort_word[] = $keyword;
					$sort_freq[] = (int)$freq;
					$data[] = array(
						'keyword1' => $keyword,
						'keyword2' => $tkeyword,
						'frequency' => (int)$freq,
						'extravar' => count($line) > 3 ? array_slice($line, 3) : null
					);
				}
			}
			fclose($fp);
		}
	}

	if(count($sort_word) > 0) {
		array_multisort($sort_freq, SORT_DESC, $sort_word, SORT_ASC, $data);
		$sort_word = array_unique($sort_word);
	}

	return array($data, $sort_word);
}


/**
 * 연관키워드 데이터 로드
 * file_path : array or string
 * array(
 *   // k1에 대한 연관어
 *   array(
 *     array("keyword1"=>"k1", "keyword2"=>"", "frequency"=>0),
 *     array("keyword1"=>"k1", "keyword2"=>"", "frequency"=>0),
 *     array("keyword1"=>"k1", "keyword2"=>"", "frequency"=>0)
 *   ),
 *   // k2에 대한 연관어
 *   array(
 *     array("keyword1"=>"k2", "keyword2"=>"", "frequency"=>0),
 *     array("keyword1"=>"k2", "keyword2"=>"", "frequency"=>0),
 *     array("keyword1"=>"k2", "keyword2"=>"", "frequency"=>0)
 *   ),
 * ),
 * array(0=>"k1", 1=>"k2") // 인덱스
 */
function load_ngram_multi($file_path, $limit_line = 0) {
	list($data, $sort_word) = load_ngram($file_path, $limit_line);
	$re_sort_word = array();
	$re_data = array();
	
	while ($word = current($sort_word)) {
		$start = key($sort_word);
		$has_next = next($sort_word);

		if($has_next === false) {
			$last = count($data);
		} else {
			$last = key($sort_word);
		}
		
		$re_sort_word[] = $word;
		$re_data[] = array_slice($data, $start, $last - $start);
	}

	return array($re_data, $re_sort_word);
}

function toWeekNum($timestamp) {
    $w = date('w', mktime(0,0,0, date('n',$timestamp), 1, date('Y',$timestamp)));
    return ceil(($w + date('j',$timestamp) -1) / 7);
}

