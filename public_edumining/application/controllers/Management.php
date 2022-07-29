<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Management class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * LMS 페이지를 담당하는 controller 입니다.
 */
class Management extends IMC_Controller
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
        $this->load->model('edumining/Class_manage_model');
        $this->load->model('edumining/Data_manage_model');
    }


    /**
     * 전체 메인 페이지입니다
	 * 기본 메인 페이지
     */
    public function index()
    {
		//required_user_login();
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_predict_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['view']['canonical'] = site_url();
        $view['view']['dateset'] = $this->dateset;
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        $view['view']['event']['before_nokeyword_layout'] = Events::trigger('before_nokeyword_layout', $eventname);
        
        // 상단 타이틀 설정
        $data = array();
        //$data['title_name'] = "시내버스 안전 모니터링 서비스";
        $view['view']['data'] = $data;
        
        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'service_menu',
            'layout' => 'layout',
            'skin' => 'search',
            'layout_dir' => 'flagship',
            'mobile_layout_dir' => 'flagship',
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => 'flagship',
            'mobile_skin_dir' => 'flagship',
            'page_title' => $page_title,
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'meta_author' => $meta_author,
            'page_name' => $page_name,
        );
        $view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
        $this->data = $view;

        $this->layout = element('layout_skin_file', element('layout', $view)); // 기본  layout 사용
        $this->view = element('view_skin_file', element('layout', $view));
    }

	/**
	 * 
     */
    public function class_edit()
    {

        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_Popjudgment_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['view']['canonical'] = site_url();
        $view['view']['dateset'] = $this->dateset;
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        $view['view']['event']['before_nokeyword_layout'] = Events::trigger('before_nokeyword_layout', $eventname);

        // 상단 타이틀 설정
        $data = array();
        //$data['title_name'] = "시내버스 안전 모니터링 서비스";
        $view['view']['data'] = $data;
        
        /**
         * 레이아웃을 정의합니다
         */
        //$page_title = "시내버스 안전 모니터링 서비스";
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'service_menu',
            'layout' => 'layout',
            'skin' => 'class_edit',
            'layout_dir' => 'management',
            'mobile_layout_dir' => 'management',
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => 'management',
            'mobile_skin_dir' => 'management',
            'page_title' => $page_title,
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'meta_author' => $meta_author,
            'page_name' => $page_name,
        );

        $view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
        $this->data = $view;
		
        $this->layout = element('layout_skin_file', element('layout', $view)); // 기본  layout 사용
        $this->view = element('view_skin_file', element('layout', $view));
    }

	/**
	 * 
     */
    public function class_list()
    {

        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_Popjudgment_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['view']['canonical'] = site_url();
        $view['view']['dateset'] = $this->dateset;
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        $view['view']['event']['before_nokeyword_layout'] = Events::trigger('before_nokeyword_layout', $eventname);

        // 상단 타이틀 설정
        $data = array();
        //$data['title_name'] = "시내버스 안전 모니터링 서비스";
        $view['view']['data'] = $data;
        
        /**
         * 레이아웃을 정의합니다
         */
        //$page_title = "시내버스 안전 모니터링 서비스";
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'service_menu',
            'layout' => 'layout',
            'skin' => 'class_list',
            'layout_dir' => 'management',
            'mobile_layout_dir' => 'management',
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => 'management',
            'mobile_skin_dir' => 'management',
            'page_title' => $page_title,
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'meta_author' => $meta_author,
            'page_name' => $page_name,
        );

        $view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
        $this->data = $view;
		
        $this->layout = element('layout_skin_file', element('layout', $view)); // 기본  layout 사용
        $this->view = element('view_skin_file', element('layout', $view));
    }

	/**
	 * 
     */
    public function class_add()
    {

        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_Popjudgment_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['view']['canonical'] = site_url();
        $view['view']['dateset'] = $this->dateset;
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        $view['view']['event']['before_nokeyword_layout'] = Events::trigger('before_nokeyword_layout', $eventname);

        // 상단 타이틀 설정
        $data = array();
        //$data['title_name'] = "시내버스 안전 모니터링 서비스";
        $view['view']['data'] = $data;
        
        /**
         * 레이아웃을 정의합니다
         */
        //$page_title = "시내버스 안전 모니터링 서비스";
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'service_menu',
            'layout' => 'layout',
            'skin' => 'class_add',
            'layout_dir' => 'management',
            'mobile_layout_dir' => 'management',
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => 'management',
            'mobile_skin_dir' => 'management',
            'page_title' => $page_title,
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'meta_author' => $meta_author,
            'page_name' => $page_name,
        );

        $view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
        $this->data = $view;
		
        $this->layout = element('layout_skin_file', element('layout', $view)); // 기본  layout 사용
        $this->view = element('view_skin_file', element('layout', $view));
    }


	/**
	 * 
     */
    public function class_manage()
    {
        required_user_login();
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_Popjudgment_index';
        $this->load->event($eventname);
        $user_id = $this->member->item('mem_userid');
        $user_no = $this->member->item('mem_id');
        $mem_level = $this->member->item('mem_level');

        if($user_id == ""){
            $data = array();
            $data['msg'] = "로그인을 해 주세요.";
            $data['url'] = "/login";
            $this->load->view('service_menu/management/alert_redirect', $data);
        }

        // 학생 계정 접근 제한
        if($mem_level == 1){
            $data = array();
            $data['msg'] = "접근이 불가능합니다.";
            $data['url'] = "/";
            $this->load->view('service_menu/management/alert_redirect', $data);
        }

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['view']['canonical'] = site_url();
        $view['view']['dateset'] = $this->dateset;
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        $view['view']['event']['before_nokeyword_layout'] = Events::trigger('before_nokeyword_layout', $eventname);

        // 상단 타이틀 설정
        $data = array();
        //$data['title_name'] = "시내버스 안전 모니터링 서비스";
        $data['mem_userid'] = $user_id;
        $data['mem_id'] = $user_no;

        $view['view']['data'] = $data;
        
        /**
         * 레이아웃을 정의합니다
         */
        //$page_title = "시내버스 안전 모니터링 서비스";
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'service_menu',
            'layout' => 'layout',
            'skin' => 'class_manage',
            'layout_dir' => 'management',
            'mobile_layout_dir' => 'management',
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => 'management',
            'mobile_skin_dir' => 'management',
            'page_title' => $page_title,
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'meta_author' => $meta_author,
            'page_name' => $page_name,
        );

        $view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
        $this->data = $view;
		
        $this->layout = element('layout_skin_file', element('layout', $view)); // 기본  layout 사용
        $this->view = element('view_skin_file', element('layout', $view));
    }

	
	/**
	 * 
     */
    public function data_collect()
    {

        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_Popjudgment_index';
        $this->load->event($eventname);
        $user_id = $this->member->item('mem_userid');
        if($user_id == ""){
            $data = array();
            $data['msg'] = "로그인을 해 주세요.";
            $data['url'] = "/login";
            $this->load->view('service_menu/management/alert_redirect', $data);
        }

        // 학생 계정 접근 제한
        if($mem_level == 1){
            $data = array();
            $data['msg'] = "접근이 불가능합니다.";
            $data['url'] = "/";
            $this->load->view('service_menu/management/alert_redirect', $data);
        }

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['view']['canonical'] = site_url();
        $view['view']['dateset'] = $this->dateset;
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        $view['view']['event']['before_nokeyword_layout'] = Events::trigger('before_nokeyword_layout', $eventname);

        // 상단 타이틀 설정
        $data = array();
        //$data['title_name'] = "시내버스 안전 모니터링 서비스";
        $view['view']['data'] = $data;
        
        /**
         * 레이아웃을 정의합니다
         */
        //$page_title = "시내버스 안전 모니터링 서비스";
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'service_menu',
            'layout' => 'layout',
            'skin' => 'data_collect',
            'layout_dir' => 'management',
            'mobile_layout_dir' => 'management',
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => 'management',
            'mobile_skin_dir' => 'management',
            'page_title' => $page_title,
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'meta_author' => $meta_author,
            'page_name' => $page_name,
        );

        $view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
        $this->data = $view;
		
        $this->layout = element('layout_skin_file', element('layout', $view)); // 기본  layout 사용
        $this->view = element('view_skin_file', element('layout', $view));
    }

	/**
	 * 
     */
    public function project_manage()
    {
        required_user_login();
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_Popjudgment_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['view']['canonical'] = site_url();
        $view['view']['dateset'] = $this->dateset;
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        $view['view']['event']['before_nokeyword_layout'] = Events::trigger('before_nokeyword_layout', $eventname);

        // 상단 타이틀 설정
        $data = array();
        //$data['title_name'] = "시내버스 안전 모니터링 서비스";
        $view['view']['data'] = $data;
        
        /**
         * 레이아웃을 정의합니다
         */
        //$page_title = "시내버스 안전 모니터링 서비스";
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'service_menu',
            'layout' => 'layout',
            'skin' => 'project_manage',
            'layout_dir' => 'management',
            'mobile_layout_dir' => 'management',
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => 'management',
            'mobile_skin_dir' => 'management',
            'page_title' => $page_title,
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'meta_author' => $meta_author,
            'page_name' => $page_name,
        );

        $view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
        $this->data = $view;
		
        $this->layout = element('layout_skin_file', element('layout', $view)); // 기본  layout 사용
        $this->view = element('view_skin_file', element('layout', $view));
    }

    public function popup_view_report()
    {
        required_user_login();
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_Popjudgment_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['view']['canonical'] = site_url();
        $view['view']['dateset'] = $this->dateset;

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        $view['view']['event']['before_nokeyword_layout'] = Events::trigger('before_nokeyword_layout', $eventname);

        // 상단 타이틀 설정
        $data = array();
        //$data['title_name'] = "시내버스 안전 모니터링 서비스";
        $view['view']['data'] = $data;

        /**
         * 레이아웃을 정의합니다
         */
        $page_title = "과제 상세보기";
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'service_menu',
            'layout' => 'layout_popup',
            'skin' => 'popup_view_report',
            'layout_dir' => 'management',
            'mobile_layout_dir' => 'management',
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => 'management',
            'mobile_skin_dir' => 'management',
            'page_title' => $page_title,
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'meta_author' => $meta_author,
            'page_name' => $page_name,
        );

        $view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
        $this->data = $view;

        $this->layout = element('layout_skin_file', element('layout', $view)); // 기본  layout 사용
        $this->view = element('view_skin_file', element('layout', $view));
    }

    public function popup_student_list()
    {
        required_user_login();
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_Popjudgment_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['view']['canonical'] = site_url();
        $view['view']['dateset'] = $this->dateset;

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        $view['view']['event']['before_nokeyword_layout'] = Events::trigger('before_nokeyword_layout', $eventname);

        // 상단 타이틀 설정
        $data = array();
        //$data['title_name'] = "시내버스 안전 모니터링 서비스";
        $view['view']['data'] = $data;

        /**
         * 레이아웃을 정의합니다
         */
        $page_title = "학생 리스트";
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'service_menu',
            'layout' => 'layout_popup',
            'skin' => 'popup_student_list',
            'layout_dir' => 'management',
            'mobile_layout_dir' => 'management',
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => 'management',
            'mobile_skin_dir' => 'management',
            'page_title' => $page_title,
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'meta_author' => $meta_author,
            'page_name' => $page_name,
        );

        $view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
        $this->data = $view;

        $this->layout = element('layout_skin_file', element('layout', $view)); // 기본  layout 사용
        $this->view = element('view_skin_file', element('layout', $view));
    }
	

	/**
	 * 
     */
    public function student_submit()
    {

        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_Popjudgment_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['view']['canonical'] = site_url();
        $view['view']['dateset'] = $this->dateset;
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        $view['view']['event']['before_nokeyword_layout'] = Events::trigger('before_nokeyword_layout', $eventname);

        // 상단 타이틀 설정
        $data = array();
        //$data['title_name'] = "시내버스 안전 모니터링 서비스";
        $view['view']['data'] = $data;
        
        /**
         * 레이아웃을 정의합니다
         */
        //$page_title = "시내버스 안전 모니터링 서비스";
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'service_menu',
            'layout' => 'layout',
            'skin' => 'student_submit',
            'layout_dir' => 'management',
            'mobile_layout_dir' => 'management',
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => 'management',
            'mobile_skin_dir' => 'management',
            'page_title' => $page_title,
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'meta_author' => $meta_author,
            'page_name' => $page_name,
        );

        $view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
        $this->data = $view;
		
        $this->layout = element('layout_skin_file', element('layout', $view)); // 기본  layout 사용
        $this->view = element('view_skin_file', element('layout', $view));
    }


	/**
	 * 
     */
    public function task_manage()
    {

        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_Popjudgment_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['view']['canonical'] = site_url();
        $view['view']['dateset'] = $this->dateset;
        
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        $view['view']['event']['before_nokeyword_layout'] = Events::trigger('before_nokeyword_layout', $eventname);

        // 상단 타이틀 설정
        $data = array();
        //$data['title_name'] = "시내버스 안전 모니터링 서비스";
        $view['view']['data'] = $data;
        
        /**
         * 레이아웃을 정의합니다
         */
        //$page_title = "시내버스 안전 모니터링 서비스";
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'service_menu',
            'layout' => 'layout',
            'skin' => 'task_manage',
            'layout_dir' => 'management',
            'mobile_layout_dir' => 'management',
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => 'management',
            'mobile_skin_dir' => 'management',
            'page_title' => $page_title,
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'meta_author' => $meta_author,
            'page_name' => $page_name,
        );

        $view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
        $this->data = $view;
		
        $this->layout = element('layout_skin_file', element('layout', $view)); // 기본  layout 사용
        $this->view = element('view_skin_file', element('layout', $view));
    }



    public function pop_view_crawl($idx)
    {

        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_Popjudgment_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['view']['canonical'] = site_url();
        $view['view']['dateset'] = $this->dateset;

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);
        $view['view']['event']['before_nokeyword_layout'] = Events::trigger('before_nokeyword_layout', $eventname);

        // 상단 타이틀 설정
        $data = array();
        $crawlData = $this->Data_manage_model->getCrawlData($idx);

        $data['filePath'] = $crawlData['file_path'];
        $data['keyword'] = $crawlData['collection_keyword'];
        $data['updateDate'] = $crawlData['update_date'];
        $data['fileSize'] = $crawlData['data_size'];

        //$data['title_name'] = "시내버스 안전 모니터링 서비스";
        $view['view']['data'] = $data;

        /**
         * 레이아웃을 정의합니다
         */
        //$page_title = "시내버스 안전 모니터링 서비스";
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'service_menu',
            'layout' => 'layout_popup',
            'skin' => 'pop_view_crawl',
            'layout_dir' => 'management',
            'mobile_layout_dir' => 'management',
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => 'management',
            'mobile_skin_dir' => 'management',
            'page_title' => "수집 데이터 미리보기",
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'meta_author' => $meta_author,
            'page_name' => $page_name,
        );

        $view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
        $this->data = $view;

        $this->layout = element('layout_skin_file', element('layout', $view)); // 기본  layout 사용
        $this->view = element('view_skin_file', element('layout', $view));
    }
}
