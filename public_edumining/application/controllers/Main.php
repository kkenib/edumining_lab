<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Main class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 메인 페이지를 담당하는 controller 입니다.
 */
class Main extends IMC_Controller
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

    /**
     * 전체 메인 페이지입니다
	 * 기본 메인 페이지
     */
    public function index()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_main_index';
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
        $data['title_name'] = "에듀마이닝 메인";
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
            'skin' => 'main',
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
