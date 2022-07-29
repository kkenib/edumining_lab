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
class Project extends CB_Controller
{

    /**
     * 관리자 페이지 상의 현재 디렉토리입니다
     * 페이지 이동시 필요한 정보입니다
     */
    public $pagedir = 'project/project';

    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Member_nickname', 'Member_userid', 'Word');

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

        $page = (((int)$this->input->get('page')) > 0) ? ((int)$this->input->get('page')) : 1;
        /*
        $view['view']['sort'] = array(
            'idx' => $param->sort('idx', 'asc'),
            'keyword' => $param->sort('keyword', 'asc'),
            'objname' => $param->sort('objname', 'asc'),
            'summary' => $param->sort('summary', 'asc'),
            'state' => $param->sort('state', 'asc'),
            'regdate' => $param->sort('regdate', 'asc'),
        );
        */
        $view['view']['sort'] = array(
            'no' => $param->sort('no', 'asc'),
            'userid' => $param->sort('mem_userid', 'asc'),
            'lecture_name' => $param->sort('lecture_name', 'asc'),
            'school_name' => $param->sort('mem_school_name', 'asc'),
            'title' => $param->sort('title', 'asc'),
            'permit_state' => $param->sort('permit_state', 'asc'),
            'update_date' => $param->sort('update_date', 'asc')
        );

        $forder = $this->input->get('forder', null, 'desc');
        $sfield = $this->input->get('sfield', null, '');
        $skeyword = $this->input->get('skeyword', null, '');

        $per_page = admin_listnum();
        $offset = ($page - 1) * $per_page;

        /**
         * 게시판 목록에 필요한 정보를 가져옵니다.
         */

        $this->{$this->modelname}->allow_search_field = array('mem_userid', 'lecture_name', 'mem_school_name', 'title'); // 검색이 가능한 필드
        $this->{$this->modelname}->search_field_equal = array('permit_state'); // 검색중 like 가 아닌 = 검색을 하는 필드
        $this->{$this->modelname}->allow_order_field = array('mem_userid', 'lecture_name', 'school_name', 'title', 'permit_state'); // 정렬이 가능한 필드

        // TODO: 승인 프로세스
        $where = 'permit_state != 0';
        if ($tmp_key == 'acc') {
            // 승인목록
            $where = 'permit_state = 2';
        } else if ($tmp_key == 'std') {
            // 반려목록
            $where = 'permit_state = 1';
        } else if ($tmp_key == 'rej') {
            // 대기목록
            $where = 'permit_state = 3';
        }

        $findex = 'no';

        $join = array(
            '0' => array(
                'table' => 't_member',
                'on' => 'edu_report.user_no = t_member.mem_id'
            ),
            '1' => array(
                'table' => 'edu_lecture',
                'on' => 'edu_report.lecture_no = edu_lecture.no'
            )
        );
        $select = 'edu_report.no, t_member.mem_userid, edu_lecture.lecture_name, t_member.mem_school_name as school_name, edu_report.title, edu_report.permit_state, edu_report.update_date';
        $result = $this->{$this->modelname}->get_report_list($select, $join, $per_page, $offset, $where, '', $findex, $forder, $sfield, $skeyword);

        $list_num = $result['total_rows'] - ($page - 1) * $per_page;

        if (element('list', $result)) {
            foreach (element('list', $result) as $key => $val) {
                $result['list'][$key]['display_name'] = display_username(
                    element('mem_userid', $val),
                    element('lecture_name', $val),
                    element('mem_school_name', $val),
                    element('title', $val)
                );
                $result['list'][$key]['num'] = $list_num--;
            }
        }

        $view['view']['data'] = $result;

        /**
         * primary key 정보를 저장합니다
         */
        $view['view']['primary_key'] = $this->{$this->modelname}->report_key;

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

        $search_option = array('mem_userid' => '아이디', 'lecture_name' => '수업명', 'mem_school_name' => '학교', 'title' => '제목', 'state' => '승인여부');
        $view['view']['skeyword'] = ($sfield && array_key_exists($sfield, $search_option)) ? $skeyword : '';
        $view['view']['search_option'] = search_option($search_option, $sfield);

        // TODO: 버튼 기능 작성
        //$view['view']['download_member_url'] = admin_url($this->pagedir . '/download');
        $view['view']['listall_url'] = admin_url($this->pagedir . '?key=all');
        $view['view']['listall_url_acc'] = admin_url($this->pagedir . '?key=acc');
        $view['view']['listall_url_std'] = admin_url($this->pagedir . '?key=std');
        $view['view']['listall_url_rej'] = admin_url($this->pagedir . '?key=rej');

        //$view['view']['list_delete_url'] = admin_url($this->pagedir . '/listdelete/?' . $param->output());

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
        //$this->load->model('Word_model');
        //$this->load->model('Keyword_dic_model');
        if ($this->input->post('chk') && is_array($this->input->post('chk'))) {
            foreach ($this->input->post('chk') as $val) {
                if ($val) {
                    $updatedata = array(
                        'permit_state' => '2',
                    );
                    $this->db->set_dbprefix('');
                    $this->db->where('no', $val);
                    $this->db->update('edu_report', $updatedata);
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
        //$this->load->model('Word_model');
        if ($this->input->post('chk') && is_array($this->input->post('chk'))) {
            foreach ($this->input->post('chk') as $val) {
                if ($val) {
                    $updatedata = array(
                        'permit_state' => '3',
                    );
                    $this->db->set_dbprefix('');
                    $this->db->where('no', $val);
                    $this->db->update('edu_report', $updatedata);
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
}