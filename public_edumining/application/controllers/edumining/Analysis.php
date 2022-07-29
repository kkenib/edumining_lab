	<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Analysis class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 
 */
class Analysis extends IMC_Controller
{
    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('form', 'array','file');

    function __construct()
    {
        parent::__construct();

        /**
         * 라이브러리를 로딩합니다
         */
        $this->load->library(array('querystring'));

		$this->load->model('edumining/Analysis_model','AM',TRUE);
    }

    public function get_cleaning_list() {

        $pageNo = $this->input->post_get('page_no', TRUE);
        $data_type = $this->input->post_get('data_type', TRUE);
        $search_keyword = $this->input->post_get('search_keyword', TRUE);
        $page_num = is_int($pageNo) ? $pageNo : 1;
        $user_no = $_SESSION['mem_id'];

        //        // 데이터 유형 별 리스트
        $rawlist = $this->AM->get_rawdata_list($user_no, $data_type, $search_keyword, $page_num);
        $total_cnt = $this->AM->get_rawdata_list_count($user_no, $data_type, $search_keyword)->cnt;
        $list = array();
        for ($i = 0; $i < count($rawlist); $i++) {
            $raw = $rawlist[$i];
            $item = array(
                "no" => $raw["no"],
                "item_no" => $total_cnt - ($page_num - 1) * 10 - $i,
                "user_no" => $raw["user_no"],
                "data_type" => $raw["data_type"],
                "data_name" => $raw["data_name"],
                "author" => $raw["author"],
                "genre" => $raw["genre"],
                "update_date" => $raw["update_date"],
                "collection_start_date" => $raw["collection_start_date"],
                "collection_end_date" => $raw["collection_end_date"],
                "collection_state" => $raw["collection_state"],
                "chapter_count" => $raw["chapter_count"],
                "text_count" => $raw["text_count"],
                "data_size" => $raw["data_size"],
                "collection_keyword" => $raw["collection_keyword"],
                "collection_unit" => $raw["collection_unit"],
                "edit_step" => $raw["edit_step"]);
            array_push($list, $item);
        }


        if ($total_cnt <= 10) {
            $pageCount = 1;
        } else {
            $pageCount = intval($total_cnt / 10) + ($total_cnt % 10 == 0 ? 0 : 1);
        }

        $result = array(
            "current_page_no" => $page_num,
            "list" => $list,
            "total_item_count" => $total_cnt,
            "total_page_count" => $pageCount
        );
        echo json_encode($result);
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
        $config['base_url'] = site_url() . '/edumining/Analysis/get_rawdata_list?' . $param->replace('page');
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
//    public function get_rawdata_text() {
//
//        $data_no = $this->input->post_get('data_no');
//        $user_id = $this->member->item('mem_userid');
//
//        // 원문 데이터 가져오기
//        $this->load->library('elasticsearch');
//
//        $query = array();
//        $query = ['query' => ['bool' => ['must' => null]]];
//        $must = array();
//        $must[0] = ['query_string' => ['default_field' => 'idx', 'query' => $data_no]];
//        // $must[1] = ['query_string' => ['default_field' => 'chapter', 'query' => '1']];
//
//        $query['query']['bool']['must'] = $must;
//        $query['sort'] = [array(
//            "chapter" => array("order" => "ASC")
//        )];
//
//        $CI =& get_instance();
//        $this->elasticsearch->index = $CI->config->item('elastic_index');
//        $this->elasticsearch->type = "";
//
//        $result = $this->elasticsearch->query_json(json_encode($query, JSON_UNESCAPED_UNICODE));
//        $hits = $result->hits->hits;
//
//        $data = "";
//        foreach ($hits as $row) {
//            $text = $row->_source->text;
//            $data .= $text;
//        }
//
//        $view['view']['data'] = array(
//            'result' => $error ? 'fail' : 'success',
//            'data' => $data,
//            'error' => $error
//        );
//
//        $this->_json_layout($view);
//    }
    
    /**
     * 데이터 정제 - 추천단어 가져오기
     */
//    public function get_recommend_list() {
//
//        $data_no = $this->input->post_get('data_no');
//        $user_id = $this->member->item('mem_userid');
//
//        $file_path = "/home/theimc/edu_mining_flask/service_data/analysis/".$user_id."/".$data_no."/pos";
//        $recommend = $file_path."/".$data_no."_recommend.txt";
//
//        $rfile = fopen($recommend, "r");
//        $re_list = array();
//
//        while(!feof($rfile)) {
//
//            $line = explode("\t", fgets($rfile));
//            $re_list[] = array(
//                'before' => $line[0],
//                'after' => $line[1],
//                'count' => str_replace("\n", "", $line[2]),
//            );
//        }
//        fclose($rfile);
//
//        $view['view']['data'] = array(
//            'result' => $error ? 'fail' : 'success',
//            'data' => $re_list,
//            'error' => $error
//        );
//
//        $this->_json_layout($view);
//    }
    
    /**
     *  데이터 정제 - 내가 정제한 데이터 가져오기
     */
//    public function get_cleaning_data() {
//
//        $data_no = $this->input->post_get('data_no');
//        $user_id = $this->member->item('mem_userid');
//
//        $file_path = "/home/theimc/edu_mining_flask/service_data/analysis/".$user_id."/".$data_no."/pos";
//
//        $raw_words = $file_path."/".$data_no."_pos.txt";
//        $replaced = $file_path."/".$data_no."_replace.txt";
//
//        // 가정제 데이터
//        $rfile = fopen($raw_words, "r");
//        $raw_words = "";
//        while(!feof($rfile)) {
//            $raw_words .= fgets($rfile);
//        }
//
//        fclose($rfile);
//
//        // 편집된 단어 리스트들
//        $rfile = fopen($replaced, "r");
//        $data = "";
//        while(!feof($rfile)) {
//            $data .= fgets($rfile);
//        }
//
//        fclose($rfile);
//
//        $view['view']['data'] = array(
//            'result' => $error ? 'fail' : 'success',
//            'raw_words' => $raw_words,
//            'replace' => $data,
//            'error' => $error
//        );
//
//        $this->_json_layout($view);
//    }
    
    /**
     * 데이터 편집 저장하기
     */
    public function save_cleaning_words() {
        
        $param = $this->input->get(array('data_no', 'edit_step', 'chapter_count', 'replace_word_dict'));
        $param['user_no'] = $_SESSION['mem_id'];
        $param['user_id'] = $this->member->item('mem_userid');
        
//        // Flask로 편집 정보 보내기
//        $url = 'http://edumining.textom.co.kr:2407/replace'."?".http_build_query($param);
//
//        $curl = curl_init($url);
//        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
//        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
//            'Content-Type: application/json',
//            'Content-Length: ' . strlen($data_string))
//            );
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//
//        $result = curl_exec($curl);
//
//        $data = json_decode($result, true);
//        $data = $data['data'];
//
//        curl_close($curl);
        
        // 편집 상태 저장하기
        $data = $this->AM->save_edit_step($param);
        
        $view['view']['data'] = array(
//            'result' => $error ? 'fail' : 'success',
            'data' => $data,
//            'error' => $error
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

    public function encode_mychart_data() {

        $param = $this->input->post(array('title', 'data', 'lecture_no', 'origin_no', 'chapter', 'center_word', 'window_size', 'anal_type', 'text_color', 'bg_color'));
        $param['user_no'] = $_SESSION['mem_id'];
        $count = $this->AM->get_visual_list_count($param['user_no'])->cnt;

        if ($count > 20) {
            $data['result']= "등록 가능한 데이터 시각화 목록은 최대 20개 입니다.";
            echo json_encode($data);
            return;
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
                $text .= $line['word'].','.$line['count'].','.$line['positive'].PHP_EOL;
            }
        }

        $view['view']['data'] = array(
            'data' => $text,
            'file_name' => $file_name
        );
        $this->_json_layout($view);
    }
    
    /**
     * 데이터 시각화 등록
     */
    public function save_mychart_data() {
        
        $param = $this->input->post(array('title', 'data', 'lecture_no', 'origin_no', 'chapter', 'center_word', 'window_size', 'anal_type', 'text_color', 'bg_color', "file_name"));
        $param['user_no'] = $_SESSION['mem_id'];
        $user_id = $this->member->item('mem_userid');
        $count = $this->AM->get_visual_list_count($param['user_no'])->cnt;
        $param["file_path"] = "/home/theimc/edu_mining_flask/service_data/visual/".$user_id."/".$param["file_name"];
        $data = $this->AM->save_my_chart_info($param);
        $view['view']['data'] = array(
            'result' => $error ? 'fail' : 'success',
            'error' => $error
        );
        $this->_json_layout($view);
    }

        /**
         * 데이터 시각화 등록
         */
//        public function save_mychart_data() {
//
//            $param = $this->input->post(array('title', 'data', 'lecture_no', 'origin_no', 'chapter', 'center_word', 'window_size', 'anal_type', 'text_color', 'bg_color'));
//            $param['user_no'] = $_SESSION['mem_id'];
//            $user_id = $this->member->item('mem_userid');
//            $count = $this->AM->get_visual_list_count($param['user_no'])->cnt;
//
//            if ($count > 20) {
//                $data['result']= "등록 가능한 데이터 시각화 목록은 최대 20개 입니다.";
//                echo json_encode($data);
//                return;
//            }
//
////        if (!file_exists($file_path)) {
////            mkdir($file_path, 0777, true);
////            chmod($file_path, 0777);
////        }
//
//            // 시각화 유형 - 0: 빈도분석, 1: 연관어분석, 2: 연결망분석, 3: 추이분석 4: 감성분석
//            $anal_type = $param['anal_type'];
//            $text = "";
//
//            if ($anal_type == 0) {
//                $file_name = "freq_".$this->random_filename().".txt";
//                $data = $param['data'];
//
//                foreach ($data as $key => $line) {
//                    $text .= $line['word'].','.$line['count'].PHP_EOL;
//                }
//
//            } else if ($anal_type == 1) {
//                $file_name = "ego_".$this->random_filename().".txt";
//                $data = $param['data'];
//
//                foreach ($data as $key => $line) {
//                    $text .= $line['word'].','.$line['count'].PHP_EOL;
//                }
//
//            } else if ($anal_type == 2) {
//                $file_name = "network_".$this->random_filename().".txt";
//                $data = json_decode($param['data']);
//
//                for ($i = 0; $i < count($data); $i++) {
//                    $line = $data[$i];
//                    $text .= $line->word.','.$line->word2.','.$line->count.PHP_EOL;
//                }
//
//            } else if ($anal_type == 3) {
//                $file_name = "trend_".$this->random_filename().".txt";
//                $data = json_decode($param['data']);
//
//                $column = array();
//                // 컬럼 만들기
//                for ($i = 0; $i <= 0; $i++) {
//                    $line = $data[$i];
//
//                    foreach ($line as $key => $value) {
//                        $column[] = $key;
//                    }
//                }
//
//                for ($i = 0; $i < count($data); $i++) {
//                    $line = $data[$i];
//                    $text_tmp = array();
//
//                    // 컬럼 순서대로 text 만들기
//                    for ($j = 0; $j < count($column); $j++) {
//                        $text_tmp[] = $line->$column[$j];
//                    }
//
//                    $text .= join(",", $text_tmp).PHP_EOL;
//                }
//
//                $column_str = join(",", $column).PHP_EOL;
//                $text = $column_str.$text;
//
//            } else  if ($anal_type == 4) {
//                $file_name = "sentiment_".$this->random_filename().".txt";
//                $data = $param['data'];
//
//                foreach ($data as $key => $line) {
//                    $text .= $line['word'].','.$line['count'].','.$line['positive'].PHP_EOL;
//                }
//            }
//
//            $file_path = "/home/theimc/edu_mining_flask/service_data/visual/".$user_id."/".$file_name;
////        $file_path .= "/".$file_name;
////            $wfile = fopen($file_path, "w");
////            fwrite($wfile, $text);
////            fclose($wfile);
//
//            $param["file_path"] = $file_path;
//            $data = $this->AM->save_my_chart_info($param);
//
//            $view['view']['data'] = array(
//                'result' => $error ? 'fail' : 'success',
//                'data' => $data,
//                'error' => $error
//            );
//
//            $this->_json_layout($view);
//
//        }
    
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
            $data = $this->input->post('chart_data');
            $chart = $this->AM->get_visual_one($no);

            $anal_type = $chart['anal_type'];
            $filepath = $chart['file_path'];

            $data_list = array();

            if ($anal_type == 0 || $anal_type == 1) {   // 빈도, 연관어, 감성

                for ($i=0; $i<count($data); $i++) {
                    $line = explode(",", $data[$i]);
                    if ($line[0] == "") {
                        continue;
                    }
                    $data_list[] = array('word' => $line[0], 'count' =>  $line[1]);
                }

            } else if ($anal_type == 2) {      // 연결망
                for ($i=0; $i<count($data); $i++) {
                    $line = explode(",", $data[$i]);

                    if ($line[0] == "") {
                        continue;
                    }

                    $data_list[] = array(
                        'word' => $line[0],
                        'word2' => $line[1],
                        'count' =>  $line[2]
                    );
                }

            } else if ($anal_type == 3) {      // 추이
                $idx = 0;
                $keyword_list = array();

                for ($i=0; $i<count($data); $i++) {
                    $line = explode(",", $data[$i]);

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
            } else if ($anal_type == 4) {      // 추이
                for ($i=0; $i<count($data); $i++) {
                    $line = explode(",", $data[$i]);

                    if ($line[0] == "") {
                        continue;
                    }

                    $data_list[] = array(
                        'word' => $line[0],
                        'count' =>  $line[1],
                        'positive' => $line[2],
                    );
                }
            }

            $view['view']['data'] = array(
                'result' => $error ? 'fail' : 'success',
                'data' => $data_list,
                'chart_info' => $chart,
                'error' => $error
            );

            $this->_json_layout($view);
        }

//    function get_visual_one() {
//
//        $no = $this->input->post('no');
//
//        $chart = $this->AM->get_visual_one($no);
//
//        $anal_type = $chart['anal_type'];
//        $filepath = $chart['file_path'];
//
//        $rfile = fopen($filepath, "r");
//        $data_list = array();
//
//        if ($anal_type == 0 || $anal_type == 1) {   // 빈도, 연관어, 감성
//            while(!feof($rfile)) {
//                $line = explode(",", fgets($rfile));
//
//                if ($line[0] == "") {
//                    continue;
//                }
//
//                $data_list[] = array(
//                    'word' => $line[0],
//                    'count' =>  str_replace("\n", "", $line[1])
//                );
//            }
//
//        } else if ($anal_type == 2) {      // 연결망
//            while(!feof($rfile)) {
//                $line = explode(",", fgets($rfile));
//
//                if ($line[0] == "") {
//                    continue;
//                }
//
//                $data_list[] = array(
//                    'word' => $line[0],
//                    'word2' => $line[1],
//                    'count' =>  str_replace("\n", "", $line[2])
//                );
//            }
//
//        } else if ($anal_type == 3) {      // 추이
//            $idx = 0;
//            $keyword_list = array();
//
//            while(!feof($rfile)) {
//                $line = explode(",", fgets($rfile));
//
//                if ($line[0] == "") {
//                    continue;
//                }
//
//                if ($idx == 0) {
//                    $keyword_list = $line;
//                    $idx ++;
//                    continue;
//                }
//
//                $tmp_array = array();
//                for ($i = 0; $i < count($keyword_list); $i++) {
//                    $tmp_array[$keyword_list[$i]] = $line[$i];
//                }
//
//                $data_list[] = $tmp_array;
//                $idx ++;
//            }
//        } else if ($anal_type == 4) {      // 추이
//            while(!feof($rfile)) {
//                $line = explode(",", fgets($rfile));
//
//                if ($line[0] == "") {
//                    continue;
//                }
//
//                $data_list[] = array(
//                    'word' => $line[0],
//                    'count' =>  $line[1],
//                    'positive' => str_replace("\n", "", $line[2]),
//                );
//            }
//        }
//
//        fclose($rfile);
//
//        $view['view']['data'] = array(
//            'result' => $error ? 'fail' : 'success',
//            'data' => $data_list,
//            'chart_info' => $chart,
//            'error' => $error
//        );
//
//        $this->_json_layout($view);
//    }
    
    /**
     * 데이터 시각화 삭제
     */
    function delete_visual_one() {
        
        $user_no = $_SESSION['mem_id'];
        $no = $this->input->post('no');
        
//        $file_path = $this->AM->get_visual_one($no);
//        $file_path = $file_path['file_path'];
        
        // 해당 파일 삭제
//        unlink($file_path);
        
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
        $user_parent = $this->member->item('mem_parent');
        $user_no = $this->member->item('mem_id');

        if(!$page_num){
            $page_num = 1;
        }

        $list = $this->AM->getArticleList(0, $search_keyword, $page_num, $user_no, $user_parent);
        $total_cnt = $this->AM->getArticleListCount(0, $search_keyword, $user_no, $user_parent)->cnt;

        $param =& $this->querystring;
        $per_page = 10;
        $config['base_url'] = site_url() . '/edumining/analysis/get_notice_list?' . $param->replace('page');
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

        $totalPageCount = intval(ceil($total_cnt / $per_page));
        $result = array(
            'msg' => 'success',
            'data' => $list,
            'page' => $page_num,
            'paging' => $paging,
            'total_count' => $total_cnt,
            'total_page_count' => $totalPageCount
        );

        echo json_encode($result);
    }

//        function xss_clean($data){ // html_decode 함수의 일종
//            // 출처 : https://stackoverflow.com/questions/1336776/xss-filtering-function-in-php
//            // Fix &entity\n;
//            $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
//            $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
//            $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
//            $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');
//
//            // Remove any attribute starting with "on" or xmlns
//            $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);
//
//            // Remove javascript: and vbscript: protocols
//            $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
//            $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
//            $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);
//
//            // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
//            $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
//            $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
//            $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);
//
//            // Remove namespaced elements (we do not need them)
//            $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);
//            do
//            {
//                // Remove really unwanted tags
//                $old_data = $data;
//                $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
//            }
//            while ($old_data !== $data);
//
//            $data = preg_replace("/[ #\/\\\:;,'\"`<>()]/i", "", $data);
//            // we are done...
//            return $data;
//        }
        /**
         * 공지사항 리스트 가져오기
         */
        public function get_case_list() {
            $pageNo = $this->input->post_get('page', true);
            $search_keyword = $this->input->post_get('search_keyword', true);
            $page_num = is_int($pageNo) ? $pageNo : 1;

            if(!$page_num){
                $page_num = 1;
            }

            $list = $this->AM->getArticleList(1, $search_keyword, $page_num);
            $total_cnt = $this->AM->getArticleListCount(1, $search_keyword)->cnt;

            $param =& $this->querystring;
            $per_page = 10;
            $config['base_url'] = site_url() . '/edumining/analysis/get_case_list?' . $param->replace('page');
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

            $totalPageCount = intval(ceil($total_cnt / $per_page));
            $result = array(
                'msg' => 'success',
                'data' => $list,
                'page' => $page_num,
                'paging' => $paging,
                'total_count' => $total_cnt,
                'total_page_count' => $totalPageCount
            );

            echo json_encode($result);
        }

    /**
	*	리포트 목록 데이터 가져오기
	*/
	public function reportListData(){
		$user_no = $_SESSION['mem_id'];

		$search_report = $this->input->post("search_report");

		$data = $this->AM->getReportList($search_report,$user_no);

		$view = array();
		$view['view'] = array();
		$error = '';

		$view['view']['data'] = array(
				'result' => $error ? 'fail' : 'success',
				'data' => $data,
				'error' => $error
		);
		
		$this->_json_layout($view);
	}

  /**
	*	리포트 제출하기
	*/
	public function submitReportData(){
		$view = array();
		$view['view'] = array();
		$error = '';

		$no = $this->input->post("no");
        $userNo = $this->input->post("user_no");
		$date = $this->input->post("date");
		$this->AM->submitReport($no, $userNo, $date);
		$data = array(
			"result" => "제출 완료하였습니다."
		);

		$view['view']['data'] = array(
            'result'        => $error ? 'fail' : 'success',
            'data'			=> $data,
            'error'         => $error
		);
			
		$this->_json_layout($view);
	}

  /**
	*	리포트에 시각화 추가하기
	*/
	public function getReportObject(){
		
		$view = array();
		$view['view'] = array();
		$error = '';

		$no = $this->input->post('no');

		$data = $this->AM->getReportObject($no);
	
		$view['view']['data'] = array(
            'result'        => $error ? 'fail' : 'success',
            'data'			=> $data,
            'error'         => $error
		);
			
		$this->_json_layout($view);
	}

  /**
	*	리포트 작성 및 수정
	*/
	public function insertOrUpdateReport(){

		$view = array();
		$view['view'] = array();
		$error = '';
		
		$user_no = $_SESSION['mem_id'];
		$date = $this->input->post('date');
		$have_no = $this->input->post('have_no');
		$title = $this->input->post('title');
		$artifact_no = $this->input->post('artifact_no');
		$contents = $this->input->post('contents');
		$unit_type = $this->input->post('unit_type');
		$unit_order = $this->input->post('unit_order');

		$this->AM->insertOrUpdateReport($date,$title,$artifact_no,$contents,$unit_type,$unit_order,$user_no,$have_no);
		$data = array(
			"result" => "저장이 완료되었습니다."
		);
		$view['view']['data'] = array(
            'result'        => $error ? 'fail' : 'success',
            'data'			=> $data,
            'error'         => $error
		);

		$this->_json_layout($view);
	}

  /**
	*	리포트 내용 불러오기
	*/
	public function getReport(){
		$view = array();
		$view['view'] = array();
		$error = '';

		$no = $this->input->post('no');
		$data = $this->AM->getReport($no);

		$view['view']['data'] = array(
            'result'        => $error ? 'fail' : 'success',
            'data'			=> $data,
            'error'         => $error
		);

		$this->_json_layout($view);
	}

  /**
	*	리포트 차트 불러오기
	*/
	public function getChart(){
		$view = array();
		$view['view'] = array();
		$error = '';
		
		$no = $this->input->post('no');
		$chartInfo = $this->AM->getChart($no);
		
		$filepath = $chartInfo[0]['file_path'];
		$string = read_file($filepath);
		
		$data = array(
			'data_list'		=> $string,
			'chart_info'	=> $chartInfo
		);

		$res = $chartInfo[0]['file_path']; //테스트 결과보는중
		$view['view']['data'] = array(
            'result'        => $error ? 'fail' : 'success',
            'data'			=> $data,
            'error'         => $error
		);

		$this->_json_layout($view);
	}

    public function getGreatList()
    {
        $pageNo = $this->input->post('page_no', TRUE);
        $searchKeyword = $this->input->post('search_keyword', TRUE);
        $searchKeyword = preg_replace("/[ #\&\+%@=\/\\\:;,\.'\"\^`~|\!\?\*$#<>()\[\]\{\}]/i", "", $searchKeyword);
        $queryCondition = array(
            "page_no" => is_int($pageNo) ? $pageNo : 1,
            "search_keyword" => $searchKeyword
        );
        $result = $this->AM->getGreatList($queryCondition);
        $totalItemCount = $result["total_item_count"];
        echo json_encode($result);
    }

    public function uploadFile($userNo, $file) {
        $insertedFileNo = 0;
        $orgFileName = $file["name"];
        if ($orgFileName != null) {
            $times = mktime();
            $currentYear = date("Y", $times);
            $currentMonth = date("m", $times);

            $fromFileUrl = $file["tmp_name"];
            $dirUrl = "uploads/files/" . $currentYear . "/" . $currentMonth . "/" . md5(time());
            $toFileUrl = $dirUrl . "/" . $orgFileName;

            if(!is_dir($dirUrl)) {
                umask(0);
                if (!mkdir($dirUrl, 0777, true)) {
                    return $insertedFileNo;
                }
            }

            $result = array("result" => "failed");
            $uploadResult = move_uploaded_file($fromFileUrl, $toFileUrl);
            if ($uploadResult) {
                $queryCondition = array(
                    "file_name" => $orgFileName,
                    "file_url" => '/' . $toFileUrl,
                    "file_order" => 0,
                    "user_no" => $userNo
                );
                $insertedFileNo = $this->AM->insertFile($queryCondition);
            }
        }
        return $insertedFileNo;
    }

    public function uploadImage(){
        $filename = $_FILES['file']['name'];
        $filesize = $_FILES['file']['size'];
        $fromFilePath = $_FILES['file']['tmp_name'];

        $fileExt = '';
        if($filename == null) {
            echo json_encode(array("file_url" => ''));
        } else {
            $splitFilenames = explode('.', $filename);
            $nameCount = count($splitFilenames);
            $fileExt = $nameCount > 0 ? $splitFilenames[$nameCount-1] : '';

            $filename = time() . '.' . $fileExt;
            $prefixPath = $_SERVER["HTTP_HOST"] == "localhost" ? "D:/__programming/public_edumining/" : '';
            $suffixPath = "uploads/images/" . $filename;
            $toFilePath = $prefixPath . $suffixPath;
            $result = move_uploaded_file($fromFilePath, $toFilePath);

            $fileUrl = '';
            if($result) {
                $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https' : 'http';
                $hostUrl = $protocol . "://" . $_SERVER["HTTP_HOST"];
                $fileUrl = $hostUrl . '/' . $suffixPath;
            }
            echo json_encode(array("file_url" => $fileUrl));
        }
    }

    private function getPostValue($param) {
        return $this->input->post($param, TRUE);
    }

    public function saveArticle(){
        $queryCondition = array(
            "article_no"    => intval($this->getPostValue("article_no")),
            "article_type"  => intval($this->getPostValue("article_type")),
            "user_no"       => intval($this->getPostValue("user_no")),
            "title"         => $this->getPostValue("title"),
            "contents"      => $this->getPostValue("contents"),
            "file_no"       => $this->getPostValue("file_no"),
            "notice_status" => $this->getPostValue("notice_status")
        );

        $articleNo = $queryCondition["article_no"];
        $userNo    = $queryCondition["user_no"];
        $file      = $_FILES["file"];
        $doQuery   = ($file == null);
        if (!$doQuery) {
            $insertedFileNo = $this->uploadFile($userNo, $file);
            if ($insertedFileNo > 0) {
                $queryCondition["file_no"] = $insertedFileNo;
                $doQuery = true;
            }
        }

        $result = array("msg" => "failed");
        if ($doQuery) {
            $model = $this->AM;
            $result = ($articleNo > 0) ? ($model->updateArticle($queryCondition))
                                       : ($model->insertArticle($queryCondition));
        }

        echo json_encode($result);
    }

    /**
     * 공지사항 상세보기
     */
    public function viewArticle()
    {
        $queryCondition = array("article_no" => $this->input->post('article_no', TRUE));
        $result = $this->AM->viewArticle($queryCondition);
        echo json_encode($result);
    }

    public function removeArticle()
    {
        $queryCondition = array("article_no" => $this->input->post('article_no', TRUE));
        $result = $this->AM->removeArticle($queryCondition);
        echo json_encode($result);
    }

    public function getWaitingReportCount () {
        $result = $this->AM->getWaitingReportCount();
        echo json_encode($result);
    }

    public function updateNetworkAnalysisSchedule () {
        $queryCondition = array(
            "user_id" => $this->input->post('user_id'),
            "data_no" => $this->input->post('data_no'),
            "chapter" => $this->input->post('chapter'),
            "window_size" => $this->input->post('window_size'),
            "current_state" => $this->input->post('current_state'));
        $result = $this->AM->updateNetworkAnalysisSchedule($queryCondition);
        echo json_encode($result);
    }

    public function getNetworkAnalysisCurrentState () {
        $queryCondition = array(
            "user_id" => $this->input->post('user_id'),
            "data_no" => $this->input->post('data_no'));
        $result = $this->AM->getNetworkAnalysisCurrentState($queryCondition);
        echo json_encode($result);
    }

//    public function getSpeechFile() {
//        $dataNo = $this->input->post("data_no");
//        $userId = $this->input->post("user_id");
//        $filePath  = "/home/theimc/edu_mining_flask/service_data/analysis/";
//        $filePath .= $userId.'/'.$dataNo."/pos/".$dataNo."_speech.txt";
//        $fileName  = $dataNo."_speech.txt";
//        $result = file_exists($filePath);
//        $speech = '';
//        if($result) {
//            $fp = fopen($filePath, "rb");
//            while (!feof($fp))
//                $speech .= fread($fp, 1024);
//            fclose($fp);
//        }
//
//        echo json_encode(array(
//            "exist" => $result,
//            "file_size" => filesize($filePath),
//            "speech" => $speech
//        ));
//    }

    public function removeRawData() {
        $queryCondition = array("data_no" => $this->input->post('data_no'));
        $result = $this->AM->removeRawData($queryCondition);
        echo json_encode($result);
    }

    public function count() {
        $data = array(
            "user_id" => $this->input->post('user_id'),
            "data_no" => $this->input->post('data_no'),
            "chapter" => $this->input->post('chapter')
        );
        $result = $this->curl_post("/count", $data);
        echo json_encode($result);
    }

    public function transition() {
        $data = array(
            "user_id" => $this->input->post('user_id'),
            "data_no" => $this->input->post('data_no')
        );
        $result = $this->curl_post("/transition", $data);
        echo json_encode($result);
    }

    public function associate() {
        $data = array(
            "user_id" => $this->input->post('user_id'),
            "data_no" => $this->input->post('data_no'),
            "chapter" => $this->input->post('chapter'),
            "main_word" => $this->input->post('main_word'),
            "window_size" => $this->input->post('window_size')
        );
        $result = $this->curl_post("/associate", $data);
        echo json_encode($result);
    }

    public function connect() {
        $data = array(
            "user_id" => $this->input->post('user_id'),
            "data_no" => $this->input->post('data_no'),
            "chapter" => $this->input->post('chapter'),
            "window_size" => $this->input->post('window_size')
        );
        $result = $this->curl_post("/connect", $data);
        echo json_encode($result);
    }

    public function sentiment() {
        $data = array(
            "user_id" => $this->input->post('user_id'),
            "data_no" => $this->input->post('data_no'),
            "chapter" => $this->input->post('chapter')
        );
        $result = $this->curl_post("/sentiment", $data);
        echo json_encode($result);
    }

    public function getSpeechFile() {
        $data = array(
            "user_id" => $this->input->post('user_id'),
            "data_no" => $this->input->post('data_no')
        );
        $result = $this->curl_post("/getSpeechFile", $data);
        echo json_encode($result);
    }

    public function getCleaningData() {
        $data = array(
            "user_id" => $this->input->post('user_id'),
            "data_no" => $this->input->post('data_no')
        );
        $result = $this->curl_post("/getCleaningData", $data);
        echo json_encode($result);
    }

    public function getRawText() {
        $data = array(
            "data_no" => $this->input->post('data_no')
        );
        $result = $this->curl_post("/getRawText", $data);
        echo json_encode($result);
    }

    public function replace() {
        $data = array(
            "data_no" => $this->input->post('data_no'),
            "user_id" => $this->input->post('user_id'),
            "replace_word_dict" => $this->input->post('replace_word_dict'),
            "chapter_count" => $this->input->post('chapter_count')
        );
        $result = $this->curl_post("/replace", $data);
        echo json_encode($result);
    }

    public function copyToChildren() {
        $data = array(
            "data_no" => $this->input->post('data_no'),
            "user_id" => $this->input->post('user_id')
        );
        $result = $this->curl_post("/copyToChildren", $data);
        echo json_encode($result);
    }

    public function pos() {
        $data = array(
            "user_id" => $this->input->post('user_id'),
            "data_no" => $this->input->post('data_no'),
            "chapter_count" => $this->input->post('chapter_count'),
            "tag_n_flag" => $this->input->post('tag_n_flag'),
            "tag_a_flag" => $this->input->post('tag_a_flag'),
            "tag_v_flag" => $this->input->post('tag_v_flag')
        );
        $result = $this->curl_post("/pos", $data);
        echo json_encode($result);
    }

    public function getChartData() {
        $data = array("no" => $this->input->post('no'));
        $result = $this->curl_post("/getChartData", $data);
        echo json_encode($result);
    }

    public function getVisualData() {
        $data = array("no" => $this->input->post('no'));
        $result = $this->curl_post("/getVisualData", $data);
        echo json_encode($result);
    }

    public function deleteVisualData() {
        $data = array("no" => $this->input->post('no'));
        $result = $this->curl_post("/deleteVisualData", $data);
        echo json_encode($result);
    }

    public function saveChartData() {
        $data = array(
            "user_id" => $this->input->post('user_id'),
            "chart_data" => $this->input->post('chart_data'),
            "file_name" => $this->input->post('file_name')
        );
        $result = $this->curl_post("/saveChartData", $data);
        echo json_encode($result);
    }

}
