<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Analysis class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 * 
 * 박가은 작업 파일(추후 삭제)
 * 
 */

/**
 * 
 */
class Analysis_remove extends IMC_Controller
{
    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('form', 'array');

    function __construct()
    {
        parent::__construct();

        /**
         * 라이브러리를 로딩합니다
         */
        $this->load->library(array('querystring'));

		$this->load->model('edumining/Analysis_park_model','AM',TRUE);

    }
	
    /**
     * 데이터 선택 리스트 가져오기
     */
    public function get_rawdata_list() {

        $data_type = $this->input->post_get('data_type');
        $search_keyword = $this->input->post_get('search_keyword');
        $page_num = $this->input->post_get('page');
        $user_no = $_SESSION['mem_id'];
        
        if(!$page_num){
            $page_num = 1;
        }
        
        // 데이터 유형 별 리스트
        $list = $this->AM->get_rawdata_list($user_no, $data_type, $search_keyword, $page_num);
        
        $total_cnt = $this->AM->get_rawdata_list_count($user_no, $data_type, $search_keyword)->cnt;

        $param =& $this->querystring;
        $per_page = 10;
        $config['base_url'] = site_url() . '/edumining/Analysis_park/get_rawdata_list?' . $param->replace('page');
        $config['num_links'] = 5;
        $config['full_tag_open'] = '<div class="wr_page">';
        $config['full_tag_close'] = '</div>';
        $config['first_tag_open'] = '';
        $config['first_tag_close'] = '';
        $config['last_tag_open'] = '';
        $config['last_tag_close'] = '';
        $config['cur_tag_open'] = '<strong>';
        $config['cur_tag_close'] = '</strong>';
        $config['next_tag_open'] = '';
        $config['next_tag_close'] = '';
        $config['prev_tag_open'] = '';
        $config['prev_tag_close'] = '';
        $config['num_tag_open'] = '';
        $config['num_tag_close'] = '';
        $config['total_rows'] = $total_cnt;
        $config['per_page'] = $per_page;
        $config["cur_page"] = $page_num;
        $this->pagination->initialize($config);
        $paging = $this->pagination->create_links();

        $view['view']['data'] = array(
            'result' => $error ? 'fail' : 'success',
            'data' => $list,
            'page' => $page_num,
            'paging' => $paging,
            'error' => $error
        );

        $this->_json_layout($view);
	}
	
	/**
	 * 특정 데이터 정보 가져오기
	 */
	public function get_rawdata_one() {

	    $data_no = $this->input->post_get('data_no');
	    $data_origin = $this->AM->get_rawdata_one($data_no);
	    
	    $data_origin->collection_state = $data_origin->collection_state == 1 ? "수집 완료" : "수집 중";
	    $data_type = $data_origin->data_type == 0 ? "제공 데이터" : ($data_origin->data_type == 1 ? "수집 데이터" : "보유 데이터");
	    $data_origin->data_type = $data_type;
	    $data_origin->update_date = str_replace("-", ".", $data_origin->update_date);

	    $view['view']['data'] = array(
	        'result' => $error ? 'fail' : 'success',
	        'data' => $data_origin,
	        'error' => $error
	    );
	    
	    $this->_json_layout($view);
	    
	}
	
	/**
	 * 데이터 정제 - 원문 데이터 및 추천단어 가져오기
	 */
	public function get_rawdata_text() {
	    
	    $data_no = $this->input->post_get('data_no');
	    $user_id = $this->member->item('mem_userid');
        
	    // 원문 데이터 가져오기
	    $this->load->library('elasticsearch');

	    $query = array();
	    $query = ['query' => ['bool' => ['must' => null]]];
	    $must = array();
	    $must[0] = ['query_string' => ['default_field' => 'idx', 'query' => $data_no]];
	    // $must[1] = ['query_string' => ['default_field' => 'chapter', 'query' => '1']];
	    
	    $query['query']['bool']['must'] = $must;
	    $query['sort'] = [array(
	           "chapter" => array("order" => "ASC")
	    )];

	    $CI =& get_instance();
	    $this->elasticsearch->index = $CI->config->item('elastic_index');
	    $this->elasticsearch->type = "";
	    
	    $result = $this->elasticsearch->query_json(json_encode($query, JSON_UNESCAPED_UNICODE));
	    $hits = $result->hits->hits;
	    
	    $data = "";
	    foreach ($hits as $row) {
	        $text = $row->_source->text;
	        $data .= $text;
	    }

	    $view['view']['data'] = array(
	        'result' => $error ? 'fail' : 'success',
	        'data' => $data,
	        'error' => $error
	    );
	    
	    $this->_json_layout($view);
	}
	
	/**
	 * 데이터 정제 - 추천단어 가져오기
	 */
	public function get_recommend_list() {
	    
	    $data_no = $this->input->post_get('data_no');
	    $user_id = $this->member->item('mem_userid');

        $file_path = "/home/theimc/edu_mining_flask/service_data/analysis/".$user_id."/".$data_no."/pos";
        $recommend = $file_path."/".$data_no."_recommend.txt";
        
        $rfile = fopen($recommend, "r");
        $re_list = array();
        
        while(!feof($rfile)) {

            $line = explode("\t", fgets($rfile));
            $re_list[] = array(
                'before' => $line[0],
                'after' => $line[1],
                'count' => str_replace("\n", "", $line[2]),
            );
        }
        fclose($rfile);

	    $view['view']['data'] = array(
	        'result' => $error ? 'fail' : 'success',
	        'data' => $re_list,
	        'error' => $error
	    );
	    
	    $this->_json_layout($view);
	}
	
	/**
	 *  데이터 정제 - 내가 정제한 데이터 가져오기
	 */
	public function get_cleaning_data() {
	    
	    $data_no = $this->input->post_get('data_no');
	    $user_id = $this->member->item('mem_userid');
	    
	    $file_path = "/home/theimc/edu_mining_flask/service_data/analysis/".$user_id."/".$data_no."/pos";
	    
	    $raw_words = $file_path."/".$data_no."_pos.txt";
	    $replaced = $file_path."/".$data_no."_replace.txt";
	    
	    // 가정제 데이터
	    $rfile = fopen($raw_words, "r");
	    $raw_words = "";
	    while(!feof($rfile)) {
	        $raw_words .= fgets($rfile);
	    }
	    
	    fclose($rfile);
	    
	    // 편집된 단어 리스트들
	    $rfile = fopen($replaced, "r");
	    $data = "";
	    while(!feof($rfile)) {
	        $data .= fgets($rfile);
	    }

	    fclose($rfile);
	    
	    $view['view']['data'] = array(
	        'result' => $error ? 'fail' : 'success',
	        'raw_words' => $raw_words,
            'replace' => $data,
	        'error' => $error
	    );
	    
	    $this->_json_layout($view);
	}
	
	/**
	 * 데이터 편집 저장하기
	 */
	public function save_cleaning_words() {
	    
	    $param = $this->input->get(array('data_no', 'edit_step', 'chapter_count', 'replace_word_dict'));
	    $param['user_no'] = $_SESSION['mem_id'];
	    $param['user_id'] = $this->member->item('mem_userid');
        
	    // Flask로 편집 정보 보내기
	    $url = 'http://edumining.textom.co.kr:2407/replace'."?".http_build_query($param);

	    $curl = curl_init($url);
	    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
	    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
	        'Content-Type: application/json',
	        'Content-Length: ' . strlen($data_string))
	        );
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    
	    $result = curl_exec($curl);
	    
	    $data = json_decode($result, true);
	    $data = $data['data'];

	    curl_close($curl);

        // 편집 상태 저장하기
	    $data = $this->AM->save_edit_step($param);
	    
	    $view['view']['data'] = array(
	        'result' => $error ? 'fail' : 'success',
	        'data' => $data,
	        'error' => $error
	    );
	    
	    $this->_json_layout($view);
	}
	
	/**
	 * 데이터 편집 상태 불러오기
	 */
	public function get_edit_status() {
	    
	    $param = $this->input->get('data_no');
	    $user_no = $_SESSION['mem_id'];

	    $data = $this->AM->get_edit_status($param, $user_no);
	    $status = $data->edit_step;
	    
	    $view['view']['data'] = array(
	        'result' => $error ? 'fail' : 'success',
	        'data' => $status,
	        'error' => $error
	    );
	    
	    $this->_json_layout($view);
	}
	
	/**
	 * 데이터 시각화 등록
	 */
	public function save_mychart_data() {
	    
	    $param = $this->input->post(array('title', 'data', 'lecture_no', 'origin_no', 'chapter', 'center_word', 'window_size', 'anal_type', 'text_color', 'bg_color'));
	    $param['user_no'] = $_SESSION['mem_id'];
	    $user_id = $this->member->item('mem_userid');
	    
	    $file_path = "/home/theimc/edu_mining_flask/service_data/visual/".$user_id;

	    $count = $this->AM->get_visual_list_count($param['user_no'])->cnt;

	    if ($count > 20) {
	        $data['result']= "등록 가능한 데이터 시각화 목록은 최대 20개 입니다.";
	        echo json_encode($data);
	        return;
	    }
	    
	    if (!file_exists($file_path)) {
	        mkdir($file_path, 0777, true);
	        chmod($file_path, 0777);
	    }
	    
	    // 시각화 유형 - 0: 빈도분석, 1: 연관어분석, 2: 연결망분석, 3: 추이분석 4: 감성분석
	    $anal_type = $param['anal_type'];
	    $text = "";
        
	    if ($anal_type == 0) {
	        $file_name = "freq_".$this->random_filename().".txt";
	        $data = $param['data'];
	        
	        foreach ($data as $key => $line) {
	            $text .= $line['word'].','.$line['count'].PHP_EOL;
	        }
	        
	    } else if ($anal_type == 1) {
	        $file_name = "ego_".$this->random_filename().".txt";
	        $data = $param['data'];
	        
	        foreach ($data as $key => $line) {
	            $text .= $line['word'].','.$line['count'].PHP_EOL;
	        }
	        
	    } else if ($anal_type == 2) {
	        $file_name = "network_".$this->random_filename().".txt";
	        $data = json_decode($param['data']);
	        
	        for ($i = 0; $i < count($data); $i++) {
	            $line = $data[$i];
	            $text .= $line->word.','.$line->word2.','.$line->count.PHP_EOL;
	        }
	        
	    } else if ($anal_type == 3) {
	        $file_name = "trend_".$this->random_filename().".txt";
	        $data = json_decode($param['data']);
	        
            $column = array();
            // 컬럼 만들기
            for ($i = 0; $i <= 0; $i++) {
                $line = $data[$i];
                
                foreach ($line as $key => $value) {
                    $column[] = $key;
                }
            }

	        for ($i = 0; $i < count($data); $i++) {
	            $line = $data[$i];
	            $text_tmp = array();
	            
	            // 컬럼 순서대로 text 만들기
	            for ($j = 0; $j < count($column); $j++) {
                    $text_tmp[] = $line->$column[$j];
                }
                
                $text .= join(",", $text_tmp).PHP_EOL;
	        } 
	        
	        $column_str = join(",", $column).PHP_EOL;
	        $text = $column_str.$text;
	        
	    } else  if ($anal_type == 4) {
	        $file_name = "sentiment_".$this->random_filename().".txt";
	        $data = $param['data'];
	        
	        foreach ($data as $key => $line) {
	            $text .= $line['word'].','.$line['count'].PHP_EOL;
	        }
	    }
        
	    $file_path .= "/".$file_name;
	        
        $wfile = fopen($file_path, "w");
        fwrite($wfile, $text);
        fclose($wfile); 
	    
	    $param["file_path"] = $file_path;
	    $data = $this->AM->save_my_chart_info($param);
	    
	    $view['view']['data'] = array(
	        'result' => $error ? 'fail' : 'success',
	        'data' => $data,
	        'error' => $error
	    );
	    
	    $this->_json_layout($view);
	    
	}
	
	/**
	 * 데이터 시각화 목록
	 */
	function get_visual_list() {
	    
	    $user_no = $_SESSION['mem_id'];
	    
	    $data = $this->AM->get_visual_list($user_no);
	    
	    
	    $view['view']['data'] = array(
	        'result' => $error ? 'fail' : 'success',
	        'data' => $data,
	        'error' => $error
	    );
	    
	    $this->_json_layout($view);
	}
	
	/**
	 * 데이터 시각화 가져오기
	 */
	function get_visual_one() {
	    
	    $no = $this->input->post('no');
	    
	    $chart = $this->AM->get_visual_one($no);

	    $anal_type = $chart['anal_type'];
	    $filepath = $chart['file_path'];
        
	    $rfile = fopen($filepath, "r");
	    $data_list = array();
	    
	    if ($anal_type == 0 || $anal_type == 1 || $anal_type == 4) {   // 빈도, 연관어, 감성
	        while(!feof($rfile)) {
	            $line = explode(",", fgets($rfile));
	            
	            if ($line[0] == "") {
	                continue;
	            }
	            
	            $data_list[] = array(
	                'word' => $line[0],
	                'count' =>  str_replace("\n", "", $line[1])
	            );
	        }

	    } else if ($anal_type == 2) {      // 연결망
	        while(!feof($rfile)) {
	            $line = explode(",", fgets($rfile));
	            
	            if ($line[0] == "") {
	                continue;
	            }
	            
	            $data_list[] = array(
	                'word' => $line[0],
	                'word2' => $line[1],
	                'count' =>  str_replace("\n", "", $line[2])
	            );
	        }
	        
	    } else if ($anal_type == 3) {      // 추이
	        $idx = 0;
	        $keyword_list = array();
	        
	        while(!feof($rfile)) {
	            $line = explode(",", fgets($rfile));
	            
	            if ($line[0] == "") {
	                continue;
	            }
	            
	            if ($idx == 0) {
	                $keyword_list = $line;
	                $idx ++;
	                continue;
	            }
	            
	            $tmp_array = array();
	            for ($i = 0; $i < count($keyword_list); $i++) {
	                $tmp_array[$keyword_list[$i]] = $line[$i];
	            }

	            $data_list[] = $tmp_array;
	            $idx ++;
	        }
	    }
	    
	    fclose($rfile);
	    
	    $view['view']['data'] = array(
	        'result' => $error ? 'fail' : 'success',
	        'data' => $data_list,
	        'chart_info' => $chart,
	        'error' => $error
	    );
	    
	    $this->_json_layout($view);
	}
	
	/**
	 * 데이터 시각화 삭제
	 */
	function delete_visual_one() {
	    
	    $user_no = $_SESSION['mem_id'];
	    $no = $this->input->post('no');
	    
	    $file_path = $this->AM->get_visual_one($no);
	    $file_path = $file_path['file_path'];
	    
	    // 해당 파일 삭제
	    unlink($file_path);
	    
	    $data = $this->AM->delete_visual_one($user_no, $no);

	    $view['view']['data'] = array(
	        'result' => $error ? 'fail' : 'success',
	        'data' => $data,
	        'error' => $error
	    );
	    
	    $this->_json_layout($view);
	}
	
	/**
	 * 랜덤 이름 생성
	 */
	public function random_filename() {
	    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	    $size = 5;
	    
	    $var_size = strlen($chars);
	    $text = "";
	    
	    for($i=0; $i < $size; $i++ ) { 
	        $text .= $chars[rand(0, $var_size - 1)];
	    }
	    
	    return $text;
	}
	
	/**
	 * 공지사항 리스트 가져오기
	 */
	public function get_notice_list() {
	    $search_keyword = $this->input->post_get('search_keyword');
	    $page_num = $this->input->post_get('page');

	    if(!$page_num){
	        $page_num = 1;
	    }
	    
	    $list = $this->AM->get_notice_list($search_keyword, $page_num);
	    
	    $total_cnt = $this->AM->get_notice_list_count($search_keyword)->cnt;
	    
	    $param =& $this->querystring;
	    $per_page = 10;
	    $config['base_url'] = site_url() . '/edumining/Analysis_park/get_rawdata_list?' . $param->replace('page');
	    $config['num_links'] = 5;
	    $config['full_tag_open'] = '<div class="wr_page">';
	    $config['full_tag_close'] = '</div>';
	    $config['first_tag_open'] = '';
	    $config['first_tag_close'] = '';
	    $config['last_tag_open'] = '';
	    $config['last_tag_close'] = '';
	    $config['cur_tag_open'] = '<strong>';
	    $config['cur_tag_close'] = '</strong>';
	    $config['next_tag_open'] = '';
	    $config['next_tag_close'] = '';
	    $config['prev_tag_open'] = '';
	    $config['prev_tag_close'] = '';
	    $config['num_tag_open'] = '';
	    $config['num_tag_close'] = '';
	    $config['total_rows'] = $total_cnt;
	    $config['per_page'] = $per_page;
	    $config["cur_page"] = $page_num;
	    $this->pagination->initialize($config);
	    $paging = $this->pagination->create_links();
	    
	    $view['view']['data'] = array(
	        'result' => $error ? 'fail' : 'success',
	        'data' => $list,
	        'page' => $page_num,
	        'paging' => $paging,
	        'total_count' => $total_cnt,
	        'error' => $error
	    );
	    
	    $this->_json_layout($view);
	}
	
	
	
}
?>