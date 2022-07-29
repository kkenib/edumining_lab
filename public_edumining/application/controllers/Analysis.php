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
 * 분석 페이지를 담당하는 controller 입니다.
 */
class Analysis extends IMC_Controller
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

    }

    public function data_manage()
    {

        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'data_management';
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
        $data['title_name'] = "텍스트 마이닝 - 데이터 관리";
        $view['view']['data'] = $data;

        /**
         * 레이아웃을 정의합니다
         */
        $page_title = "텍스트 마이닝 - 데이터 관리";
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'service_menu',
            'layout' => 'layout',
            'skin' => 'sub_data_manage',
            'layout_dir' => 'analysis',
            'mobile_layout_dir' => 'analysis',
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => 'analysis',
            'mobile_skin_dir' => 'analysis',
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
     * 텍스트 마이닝 - 데이터 선택
     */
    public function select_rawdata()
    {
        
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'select_rawdata';
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
        $data['title_name'] = "텍스트 마이닝 - 데이터 선택";
        $view['view']['data'] = $data;
        
        /**
         * 레이아웃을 정의합니다
         */
        $page_title = "텍스트 마이닝 - 데이터 선택";
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        
        $layoutconfig = array(
            'path' => 'service_menu',
            'layout' => 'layout',
            'skin' => 'sub_cleaning_list',
            'layout_dir' => 'analysis',
            'mobile_layout_dir' => 'analysis',
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => 'analysis',
            'mobile_skin_dir' => 'analysis',
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
     * 텍스트 마이닝 - 데이터 정제
     */
    public function cleaning_rawdata()
    {
        
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'cleaning_rawdata';
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
        $data['title_name'] = "텍스트 마이닝 - 데이터 정제";
        $view['view']['data'] = $data;
        
        /**
         * 레이아웃을 정의합니다
         */
        $page_title = "텍스트 마이닝 - 데이터 정제";
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        
        $layoutconfig = array(
            'path' => 'service_menu',
            'layout' => 'layout',
            'skin' => 'sub_cleaning',
            'layout_dir' => 'analysis',
            'mobile_layout_dir' => 'analysis',
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => 'analysis',
            'mobile_skin_dir' => 'analysis',
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
     * 텍스트 마이닝 - 데이터 분석(빈도분석)
     */
    public function sub_analy01()
    {
        
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'sub_analy01';
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
        $data['title_name'] = "텍스트 마이닝 - 데이터 분석(빈도분석)".$this->input->get('data_no', TRUE);;
        $view['view']['data'] = $data;
        
        /**
         * 레이아웃을 정의합니다
         */
        $page_title = "텍스트 마이닝 - 데이터 분석(빈도분석)";
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        
        $layoutconfig = array(
            'path' => 'service_menu',
            'layout' => 'layout',
            'skin' => 'sub_analy01',
            'layout_dir' => 'analysis',
            'mobile_layout_dir' => 'analysis',
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => 'analysis',
            'mobile_skin_dir' => 'analysis',
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
     * 텍스트 마이닝 - 데이터 분석(연관어분석)
     */
    public function sub_analy02()
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
        $data['title_name'] = "텍스트 마이닝 - 데이터 분석(연관어분석)";
        $view['view']['data'] = $data;
        
        /**
         * 레이아웃을 정의합니다
         */
        $page_title = "텍스트 마이닝 - 데이터 분석(연관어분석)";
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        
        $layoutconfig = array(
            'path' => 'service_menu',
            'layout' => 'layout',
            'skin' => 'sub_analy02',
            'layout_dir' => 'analysis',
            'mobile_layout_dir' => 'analysis',
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => 'analysis',
            'mobile_skin_dir' => 'analysis',
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
     * 텍스트 마이닝 - 데이터 분석(연결망분석)
     */
    public function sub_analy03()
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
        $data['title_name'] = "텍스트 마이닝 - 데이터 분석(연결망분석)";
        $view['view']['data'] = $data;
        
        /**
         * 레이아웃을 정의합니다
         */
        $page_title = "텍스트 마이닝 - 데이터 분석(연결망분석)";
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        
        $layoutconfig = array(
            'path' => 'service_menu',
            'layout' => 'layout',
            'skin' => 'sub_analy03',
            'layout_dir' => 'analysis',
            'mobile_layout_dir' => 'analysis',
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => 'analysis',
            'mobile_skin_dir' => 'analysis',
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
     * 텍스트 마이닝 - 데이터 분석(추이분석)
     */
    public function sub_analy04()
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
        $data['title_name'] = "텍스트 마이닝 - 데이터 분석(추이분석)";
        $view['view']['data'] = $data;
        
        /**
         * 레이아웃을 정의합니다
         */
        $page_title = "텍스트 마이닝 - 데이터 분석(추이분석)";
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        
        $layoutconfig = array(
            'path' => 'service_menu',
            'layout' => 'layout',
            'skin' => 'sub_analy04',
            'layout_dir' => 'analysis',
            'mobile_layout_dir' => 'analysis',
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => 'analysis',
            'mobile_skin_dir' => 'analysis',
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
     * 텍스트 마이닝 - 데이터 분석(감성분석)
     */
    public function sub_analy05()
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
        $data['title_name'] = "텍스트 마이닝 - 데이터 분석(감성분석)";
        $view['view']['data'] = $data;
        
        /**
         * 레이아웃을 정의합니다
         */
        $page_title = "텍스트 마이닝 - 데이터 분석(감성분석)";
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        
        $layoutconfig = array(
            'path' => 'service_menu',
            'layout' => 'layout',
            'skin' => 'sub_analy05',
            'layout_dir' => 'analysis',
            'mobile_layout_dir' => 'analysis',
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => 'analysis',
            'mobile_skin_dir' => 'analysis',
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
	 * 리포트 - 리포트 작성
     */
    public function sub_report()
    {

        // 이벤트 라이브러리를 로딩합니다
		$this->accesslevel->check(ACCESS_AFTER_LOGIN, 1);
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
        $data['title_name'] = "리포트 - 리포트 작성";
        $view['view']['data'] = $data;
        
        /**
         * 레이아웃을 정의합니다
         */
        $page_title = "리포트 - 리포트 작성";
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'service_menu',
            'layout' => 'layout',
            'skin' => 'sub_report',
            'layout_dir' => 'analysis',
            'mobile_layout_dir' => 'analysis',
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => 'analysis',
            'mobile_skin_dir' => 'analysis',
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
	 * 리포트 - 리포트 목록
     */
    public function sub_report_list()
    {
		$this->accesslevel->check(ACCESS_AFTER_LOGIN, 1);
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
        $data['title_name'] = "리포트 - 리포트 목록";
        $view['view']['data'] = $data;
        
        /**
         * 레이아웃을 정의합니다
         */
        $page_title = "리포트 - 리포트 목록";
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'service_menu',
            'layout' => 'layout',
            'skin' => 'sub_report_list',
            'layout_dir' => 'analysis',
            'mobile_layout_dir' => 'analysis',
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => 'analysis',
            'mobile_skin_dir' => 'analysis',
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


    public function sub_proc()
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
        $data['title_name'] = "프로젝트 - 활동과정";
        $view['view']['data'] = $data;

        /**
         * 레이아웃을 정의합니다
         */
        $page_title = "프로젝트 - 활동과정";
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'service_menu',
            'layout' => 'layout',
            'skin' => 'sub_proc',
            'layout_dir' => 'analysis',
            'mobile_layout_dir' => 'analysis',
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => 'analysis',
            'mobile_skin_dir' => 'analysis',
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
     * 안씀
     */
    public function sub_analysis()
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
        $data['title_name'] = "시내버스 안전 모니터링 서비스";
        $view['view']['data'] = $data;
        
        /**
         * 레이아웃을 정의합니다
         */
        $page_title = "시내버스 안전 모니터링 서비스";
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        
        $layoutconfig = array(
            'path' => 'service_menu',
            'layout' => 'layout',
            'skin' => 'sub_analysis',
            'layout_dir' => 'analysis',
            'mobile_layout_dir' => 'analysis',
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => 'analysis',
            'mobile_skin_dir' => 'analysis',
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
     * 게시판 - 공지사항
     */
	public function sub_notice()
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
        $data['title_name'] = "게시판 - 공지사항";
        $view['view']['data'] = $data;
        
        /**
         * 레이아웃을 정의합니다
         */
        $page_title = "게시판 - 공지사항";
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        
        $layoutconfig = array(
            'path' => 'service_menu',
            'layout' => 'layout',
            'skin' => 'sub_notice',
            'layout_dir' => 'analysis',
            'mobile_layout_dir' => 'analysis',
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => 'analysis',
            'mobile_skin_dir' => 'analysis',
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
     * 게시판 - 뽐내기
     */
	public function sub_great()
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
        $data['title_name'] = "게시판 - 뽐내기";
        $view['view']['data'] = $data;
        
        /**
         * 레이아웃을 정의합니다
         */
        $page_title = "게시판 - 뽐내기";
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        
        $layoutconfig = array(
            'path' => 'service_menu',
            'layout' => 'layout',
            'skin' => 'sub_great',
            'layout_dir' => 'analysis',
            'mobile_layout_dir' => 'analysis',
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => 'analysis',
            'mobile_skin_dir' => 'analysis',
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
     * 게시판 - 수업사례
     */
    public function sub_case()
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
        $data['title_name'] = "게시판 - 수업사례";
        $view['view']['data'] = $data;

        /**
         * 레이아웃을 정의합니다
         */
        $page_title = "게시판 - 수업사례";
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'service_menu',
            'layout' => 'layout',
            'skin' => 'sub_case',
            'layout_dir' => 'analysis',
            'mobile_layout_dir' => 'analysis',
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => 'analysis',
            'mobile_skin_dir' => 'analysis',
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
     * 게시판 - 수업사례(글쓰기)
     */
    public function sub_case_write()
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
        $data['title_name'] = "게시판 - 수업사례(글쓰기)";
        $view['view']['data'] = $data;

        /**
         * 레이아웃을 정의합니다
         */
        $page_title = "게시판 - 수업사례(글쓰기)";
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'service_menu',
            'layout' => 'layout',
            'skin' => 'sub_case_write',
            'layout_dir' => 'analysis',
            'mobile_layout_dir' => 'analysis',
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => 'analysis',
            'mobile_skin_dir' => 'analysis',
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
     * 게시판 - 수업사례(상세보기)
     */
    public function sub_case_detail()
    {
        $articleNo = $_GET["no"];

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
        $data['title_name'] = "게시판 - 수업사례(상세보기)";
        $view['view']['data'] = $data;

        /**
         * 레이아웃을 정의합니다
         */
        $page_title = "게시판 - 수업사례(글쓰기)";
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'service_menu',
            'layout' => 'layout',
            'skin' => 'sub_case_detail',
            'layout_dir' => 'analysis',
            'mobile_layout_dir' => 'analysis',
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => 'analysis',
            'mobile_skin_dir' => 'analysis',
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
     * 게시판 - 공지사항(글쓰기)
     */
		public function sub_notice_write()
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
        $data['title_name'] = "게시판 - 공지사항(글쓰기)";
        $view['view']['data'] = $data;
        
        /**
         * 레이아웃을 정의합니다
         */
        $page_title = "게시판 - 공지사항(글쓰기)";
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        
        $layoutconfig = array(
            'path' => 'service_menu',
            'layout' => 'layout',
            'skin' => 'sub_notice_write',
            'layout_dir' => 'analysis',
            'mobile_layout_dir' => 'analysis',
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => 'analysis',
            'mobile_skin_dir' => 'analysis',
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
     * 게시판 - 공지사항(상세보기)
     */
    public function sub_notice_detail()
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
        $data['title_name'] = "게시판 - 공지사항(상세보기)";
        $view['view']['data'] = $data;
        
        /**
         * 레이아웃을 정의합니다
         */
        $page_title = "게시판 - 공지사항(글쓰기)";
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        
        $layoutconfig = array(
            'path' => 'service_menu',
            'layout' => 'layout',
            'skin' => 'sub_notice_detail',
            'layout_dir' => 'analysis',
            'mobile_layout_dir' => 'analysis',
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => 'analysis',
            'mobile_skin_dir' => 'analysis',
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
     * 게시판 - 뽐내기(상세보기)
     */
	public function sub_great_detail()
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
        $data['title_name'] = "게시판 - 뽐내기(상세보기)";
        $view['view']['data'] = $data;
        
        /**
         * 레이아웃을 정의합니다
         */
        $page_title = "게시판 - 뽐내기(상세보기)";
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');
        
        $layoutconfig = array(
            'path' => 'service_menu',
            'layout' => 'layout',
            'skin' => 'sub_great_detail',
            'layout_dir' => 'analysis',
            'mobile_layout_dir' => 'analysis',
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => 'analysis',
            'mobile_skin_dir' => 'analysis',
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

}
