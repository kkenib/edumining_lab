<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Words class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>회원설정>회원관리 controller 입니다.
 */
class Words extends CB_Controller
{

    /**
     * 관리자 페이지 상의 현재 디렉토리입니다
     * 페이지 이동시 필요한 정보입니다
     */
    public $pagedir = 'word/words';

    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Member_nickname', 'Member_userid','Word');

    /**
     * 이 컨트롤러의 메인 모델 이름입니다
     */
    protected $modelname = 'Member_model';

    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('form', 'array', 'chkstring');

    function __construct()
    {
        parent::__construct();

        /**
         * 라이브러리를 로딩합니다
         */
        $this->load->library(array('pagination', 'querystring', 'PHPExcel'));
    }

    /**
     * 목록을 가져오는 메소드입니다
     */
    public function index()
    {

        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_admin_member_word_index';
        $this->load->event($eventname);

		$tmp_key = $this->input->get('key', null, 'all');

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        /**
         * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
         */
        $param =& $this->querystring;

        $page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;
        $view['view']['sort'] = array(
            'idx' => $param->sort('idx', 'asc'),
            'keyword' => $param->sort('keyword', 'asc'),
            'objname' => $param->sort('objname', 'asc'),
            'summary' => $param->sort('summary', 'asc'),
            'state' => $param->sort('state', 'asc'),
            'regdate' => $param->sort('regdate', 'asc'),
        );
        $forder = $this->input->get('forder', null, 'desc');
        $sfield = $this->input->get('sfield', null, '');
        $skeyword = $this->input->get('skeyword', null, '');

        $per_page = admin_listnum();
        $offset = ($page - 1) * $per_page;

        /**
         * 게시판 목록에 필요한 정보를 가져옵니다.
         */
        $this->{$this->modelname}->allow_search_field = array('keyword', 'objname', 'summary'); // 검색이 가능한 필드
        $this->{$this->modelname}->search_field_equal = array('state'); // 검색중 like 가 아닌 = 검색을 하는 필드
        $this->{$this->modelname}->allow_order_field = array('keyword', 'objname', 'summary', 'state'); // 정렬이 가능한 필드

        $where = array();
		if($tmp_key =='acc'){
			$where['state'] = 'Y';
		}else if($tmp_key =='std'){
			$where['state'] = 'N';
		}else if($tmp_key =='rej'){
			$where['state'] = 'R';
		}
		$findex = 'idx';
        $result = $this->{$this->modelname}
            ->get_admin_word_list($per_page, $offset, $where, '', $findex, $forder, $sfield, $skeyword);
        $list_num = $result['total_rows'] - ($page - 1) * $per_page;

        if (element('list', $result)) {
            foreach (element('list', $result) as $key => $val) {

                $result['list'][$key]['display_name'] = display_username(
                    element('keyword', $val),
                    element('objname', $val),
					element('summary', $val)
                );
                $result['list'][$key]['num'] = $list_num--;
            }
        }

        $view['view']['data'] = $result;

        /**
         * primary key 정보를 저장합니다
         */
        $view['view']['primary_key'] = $this->{$this->modelname}->primary_key;

        /**
         * 페이지네이션을 생성합니다
         */
        $config['base_url'] = admin_url($this->pagedir) . '?' . $param->replace('page');
        $config['total_rows'] = $result['total_rows'];
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $view['view']['paging'] = $this->pagination->create_links();
        $view['view']['page'] = $page;

        /**
         * 쓰기 주소, 삭제 주소등 필요한 주소를 구합니다
         */
        $search_option = array('keyword' => '등록단어', 'objname' => '개체명', 'summary' => '뜻', 'state' => '승인여부');
        $view['view']['skeyword'] = ($sfield && array_key_exists($sfield, $search_option)) ? $skeyword : '';
        $view['view']['search_option'] = search_option($search_option, $sfield);
		$view['view']['download_member_url'] = admin_url($this->pagedir . '/download');
        $view['view']['listall_url'] = admin_url($this->pagedir. '?key=all');
		$view['view']['listall_url_acc'] = admin_url($this->pagedir . '?key=acc');
		$view['view']['listall_url_std'] = admin_url($this->pagedir . '?key=std');
		$view['view']['listall_url_rej'] = admin_url($this->pagedir . '?key=rej');

        $view['view']['list_delete_url'] = admin_url($this->pagedir . '/listdelete/?' . $param->output());

		$view['view']['list_acc_url'] = admin_url($this->pagedir . '/listaccept/?' . $param->output());
		$view['view']['list_rej_url'] = admin_url($this->pagedir . '/listreject/?' . $param->output());

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        /**
         * 어드민 레이아웃을 정의합니다
         */
        $layoutconfig = array('layout' => 'layout', 'skin' => 'index');
        $view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
    }


	/**
     * 목록 페이지에서 선택승인을 하는 경우 실행되는 메소드입니다
     */
    public function listaccept()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_admin_member_members_listaccept';
        $this->load->event($eventname);

        // 이벤트가 존재하면 실행합니다
        Events::trigger('before', $eventname);

        /**
         * 체크한 게시물의 삭제를 실행합니다
         */
		$this->load->model('Word_model');
		$this->load->model('Keyword_dic_model');
        if ($this->input->post('chk') && is_array($this->input->post('chk'))) {
            foreach ($this->input->post('chk') as $val) {
                if ($val) {
					$updatedata = array(
                		'state' => 'Y',
					);
					$this->db->set_dbprefix('');
					$this->Word_model->update($val, $updatedata);

					$result = $this->Word_model->get_keyword_data($val);

					$insertdata = array();
					$insertdata['keyword'] = $result['keyword'];
					$insertdata['category1'] = "판별사전";
					$insertdata['category2'] = $result['objname'];
					$this->Keyword_dic_model->insert($insertdata);
					$this->db->set_dbprefix('t_');
                }
            }
        }

        // 이벤트가 존재하면 실행합니다
        Events::trigger('after', $eventname);

        /**
         * 삭제가 끝난 후 목록페이지로 이동합니다
         */
        $this->session->set_flashdata(
            'message',
            '정상적으로 승인되었습니다'
        );
        $param =& $this->querystring;
        $redirecturl = admin_url($this->pagedir . '?' . $param->output());

        redirect($redirecturl);
    }

	/**
     * 목록 페이지에서 선택승인을 하는 경우 실행되는 메소드입니다
     */
    public function listreject()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_admin_member_members_listreject';
        $this->load->event($eventname);

        // 이벤트가 존재하면 실행합니다
        Events::trigger('before', $eventname);

        /**
         * 체크한 게시물의 삭제를 실행합니다
         */
		$this->load->model('Word_model');
        if ($this->input->post('chk') && is_array($this->input->post('chk'))) {
            foreach ($this->input->post('chk') as $val) {
                if ($val) {
					$updatedata = array(
                		'state' => 'R',
					);
					$this->db->set_dbprefix('');
					$this->Word_model->update($val, $updatedata);
					$this->db->set_dbprefix('t_');
                }
            }
        }

        // 이벤트가 존재하면 실행합니다
        Events::trigger('after', $eventname);

        /**
         * 삭제가 끝난 후 목록페이지로 이동합니다
         */
        $this->session->set_flashdata(
            'message',
            '정상적으로 거절되었습니다'
        );
        $param =& $this->querystring;
        $redirecturl = admin_url($this->pagedir . '?' . $param->output());

        redirect($redirecturl);
    }



	public function download() {
		$member_list = $this->Member_model->get_admin_word_list();
		$headers = array(
			'회원코드', '등록단어', '개체명',
			'뜻', '승인여부', '등록일'
		);

		$excel_member_list = array();

		foreach($member_list['list'] as $member) {
			$tmp_text = "승인완료";
			if($member['state']=="R"){
				$tmp_text = "거절";
			}else if($member['state']=="N"){
				$tmp_text = "승인대기";
			}
			$excel_member_list[] = array(
					$member['id_code'], 
					$member['keyword'],
					$member['objname'],
					$member['summary'],
					$tmp_text,
					$member['regdate']
			);
		}

		$data = array_merge(array($headers), $excel_member_list);

		$excel = new PHPExcel();

		// 스타일 지정
		$widths = array(
			17.14, 25.00, 34.71, 
			19.00, 19.00, 9.57, 
			9.57, 14.29, 12.00, 9.57
		);

		$header_bgcolor = 'FFABCDEF';
		$last_char = $excel->column_char( count($headers) - 1 );
 
		$excel->setActiveSheetIndex(0)->getStyle( "A1:${last_char}1" )->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($header_bgcolor);
		$excel->setActiveSheetIndex(0)->getStyle( "A:$last_char" )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true);
		foreach($widths as $i => $w) $excel->setActiveSheetIndex(0)->getColumnDimension( $excel->column_char($i) )->setWidth($w);
		$excel->getActiveSheet()->fromArray($data,NULL,'A1');
		$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="(MISP)회원목록.xlsx"');
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
	}


	/**
     * 학생회원 자동 추가 체크함수입니다
     */
    public function memCreateId()
    {
		$create_id = $this->input->post('create_id', null, '');
		$mem_std_count = $this->input->post('mem_std_count', null, '');
		$mem_division_nm = $this->input->post('mem_division_nm', null, '');
		$mem_agency = $this->input->post('mem_agency', null, '');
		$mem_parent = $this->input->post('mem_parent', null, '');

		$member_list = $this->Member_model->getCreateUserIdCount($create_id);
		$total_cnt = (int)$member_list[0]['mem_id'];

		for($i=0; $i<(int)$mem_std_count; $i++){
			$idx = $i + $total_cnt +1;
			$user_id = $create_id."_".$idx;
			$insertdata = array();
            $insertdata['mem_userid'] = $user_id;
            $insertdata['mem_email'] = $user_id."@naver.com";
            $insertdata['mem_password'] = password_hash("theimc123", PASSWORD_BCRYPT);
            $insertdata['mem_nickname'] = $user_id;
            $insertdata['mem_level'] = 1;
            $insertdata['mem_agency'] = $mem_agency;
            $insertdata['mem_division_cd'] = $mem_division_nm;
            $insertdata['mem_division_nm'] = $mem_division_nm;
			$insertdata['mem_username'] = $user_id;
			$insertdata['mem_parent'] = $mem_parent;
			$insertdata['mem_email_cert'] = 1;
			$insertdata['mem_register_datetime'] = cdate('Y-m-d H:i:s');
            $insertdata['mem_register_ip'] = $this->input->ip_address();

			$mem_id = $this->Member_model->insert($insertdata);
		}
        if ($mem_id ==True ) {
            echo json_encode("계정 생성 성공");
        }else{
	        echo json_encode("계정 생성 실패");
		}
    }


}
