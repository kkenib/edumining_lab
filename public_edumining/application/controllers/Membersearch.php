<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Membermodify class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 회원 정보 수정시 담당하는 controller 입니다.
 */
class Membersearch extends CB_Controller
{

    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Member');

    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('form', 'array', 'string');

    /**
     * 이 컨트롤러의 메인 모델 이름입니다
     */
    protected $modelname = 'Member_model';

    function __construct()
    {
        parent::__construct();

        /**
         * 라이브러리를 로딩합니다
         */
        $this->load->library(array('querystring', 'form_validation', 'email'));
    }

    
    public function memberlist() {

        /**
         * 로그인이 필요한 페이지입니다
         */
        required_user_login();

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_popup_membersearch_index';
        $this->load->event($eventname);

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
            'mem_id' => $param->sort('mem_id', 'asc'),
            'mem_userid' => $param->sort('mem_userid', 'asc'),
            'mem_username' => $param->sort('mem_username', 'asc'),
            'mem_nickname' => $param->sort('mem_nickname', 'asc'),
            'mem_email' => $param->sort('mem_email', 'asc'),
            'mem_register_datetime' => $param->sort('mem_register_datetime', 'asc'),
            'mem_lastlogin_datetime' => $param->sort('mem_lastlogin_datetime', 'asc'),
            'mem_level' => $param->sort('mem_level', 'asc'),
        );
        $findex = $this->input->get('findex', null, 'member.mem_id');
        $forder = $this->input->get('forder', null, 'desc');
        $sfield = $this->input->get('sfield', null, '');
        $skeyword = $this->input->get('skeyword', null, '');

        $per_page = admin_listnum();
        $offset = ($page - 1) * $per_page;

        /**
         * 게시판 목록에 필요한 정보를 가져옵니다.
         */
        //$this->{$this->modelname}->allow_search_field = array('mem_id', 'mem_userid', 'mem_email', 'mem_username', 'mem_nickname', 'mem_level', 'mem_homepage', 'mem_register_datetime', 'mem_register_ip', 'mem_lastlogin_datetime', 'mem_lastlogin_ip', 'mem_is_admin'); // 검색이 가능한 필드
        $this->{$this->modelname}->search_field_equal = array('mem_id', 'mem_level', 'mem_is_admin'); // 검색중 like 가 아닌 = 검색을 하는 필드
        $this->{$this->modelname}->allow_order_field = array('member.mem_id', 'mem_userid', 'mem_username', 'mem_nickname', 'mem_email', 'mem_register_datetime', 'mem_lastlogin_datetime', 'mem_level'); // 정렬이 가능한 필드

        $where = array();
        if ($this->input->get('mem_is_admin')) {
            $where['mem_is_admin'] = 1;
        }
       
        if ($mgr_id = (int) $this->input->get('mgr_id')) {
            if ($mgr_id > 0) {
                $where['mgr_id'] = $mgr_id;
            }
        }
        $result = $this->{$this->modelname}
            ->get_admin_list($per_page, $offset, $where, '', $findex, $forder, $sfield, $skeyword);
        $list_num = $result['total_rows'] - ($page - 1) * $per_page;

        if (element('list', $result)) {
            foreach (element('list', $result) as $key => $val) {

                $result['list'][$key]['display_name'] = display_username(
                    element('mem_userid', $val),
                    element('mem_nickname', $val)
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
        $config['base_url'] = site_url('membersearch/index') . '?' . $param->replace('page');
        $config['total_rows'] = $result['total_rows'];
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $view['view']['paging'] = $this->pagination->create_links();
        $view['view']['page'] = $page;

        /**
         * 쓰기 주소, 삭제 주소등 필요한 주소를 구합니다
         */
        $search_option = array('mem_userid' => '회원아이디', 'mem_email' => '이메일', 'mem_username' => '회원명', 'mem_nickname' => '닉네임', 'mem_level' => '회원레벨', 'mem_homepage' => '홈페이지', 'mem_register_datetime' => '회원가입날짜', 'mem_register_ip' => '회원가입IP', 'mem_lastlogin_datetime' => '최종로그인날짜', 'mem_lastlogin_ip' => '최종로그인IP', 'mem_adminmemo' => '관리자메모');
        $view['view']['skeyword'] = ($sfield && array_key_exists($sfield, $search_option)) ? $skeyword : '';
        $view['view']['search_option'] = search_option($search_option, $sfield);
        $view['view']['listall_url'] = admin_url($this->pagedir);
        $view['view']['write_url'] = admin_url($this->pagedir . '/write');
        $view['view']['list_delete_url'] = admin_url($this->pagedir . '/listdelete/?' . $param->output());

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_mypage');
        $meta_description = $this->cbconfig->item('site_meta_description_mypage');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_mypage');
        $meta_author = $this->cbconfig->item('site_meta_author_mypage');
        $page_name = $this->cbconfig->item('site_page_name_mypage');

        $skin_name = 'membersearch';

        $layoutconfig = array(
            'path' => 'popup',
            'layout' => 'layout_popup',
            'skin' => $skin_name,
            'layout_dir' => $this->cbconfig->item('layout_mypage'),
            'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_mypage'),
            'use_sidebar' => $this->cbconfig->item('sidebar_mypage'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_mypage'),
            'skin_dir' => $this->cbconfig->item('skin_mypage'),
            'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_mypage'),
            'page_title' => $page_title,
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'meta_author' => $meta_author,
            'page_name' => $page_name
        );
        $view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));

    }
}
?>