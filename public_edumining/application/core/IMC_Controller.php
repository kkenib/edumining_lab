<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once __DIR__ . '/CB/CB_Controller.php';

/**
 *
 * @copyright Copyright (c) 2017, The IMC
 */

class IMC_Controller extends CB_Controller
{

    /*
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('form', 'array');
	protected $dateset = array();
	protected $active_menu = array();
	protected $layout_value = array();
    protected $remoteHost = "https://edumining.textom.co.kr:2407";
    protected $localHost = "https://edumining.textom.co.kr";
	public function __construct()
    {
        parent::__construct();

        /**
         * 라이브러리를 로딩합니다
         */
		$this->load->library(array('pagination', 'querystring', 'accesslevel'));

        /**
         * 데이터 DB 커넥션
         */
        $this->datadb = $this->load->database(SERVICE_NAME, true);        
        $this->benchmark->mark('code_start');
    }

    protected function curl_post($suffix, $data)
    {
        $url = $this->remoteHost.$suffix;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($curl);
        return json_decode($response);
    }
	
	protected function active_menu()
	{
		$this->active_menu = func_get_args();
	}

	protected function layout_value($key, $val)
	{
		$this->layout_value[$key] = $val;
	}

	/**
	 * 레이아웃 처리 함수
	 */
    protected function _ext_layout($page_name, $view_path, $skin, $view = array(), $layout_popup=false, $skin_dir='', $layout_dir='')
    {
        $page_title = $this->cbconfig->item('site_meta_title_document');

        $searchconfig = array( '{문서제목}' );
        $replaceconfig = array( $page_name );

        $page_title = str_replace($searchconfig, $replaceconfig, $page_title);
        $layout_dir = $layout_dir == '' ? $this->cbconfig->item('layout_default') : $layout_dir;
        $mobile_layout_dir = $this->cbconfig->item('mobile_layout_default');
        $use_sidebar = $this->cbconfig->item('sidebar_default');
        $use_mobile_sidebar = $this->cbconfig->item('mobile_sidebar_default');
        $skin_dir =  $skin_dir == '' ? $this->cbconfig->item('skin_default') : $skin_dir;
        $mobile_skin_dir = $this->cbconfig->item('mobile_skin_default');
        $layoutconfig = array(
            'path' => $view_path,
            'layout' => ($layout_popup == false)? 'layout' : 'layout_popup',
            'skin' => $skin,
            'layout_dir' => $layout_dir,
            'mobile_layout_dir' => $mobile_layout_dir,
            'use_sidebar' => $use_sidebar,
            'use_mobile_sidebar' => $use_mobile_sidebar,
            'skin_dir' => $skin_dir,
            'mobile_skin_dir' => $mobile_skin_dir,
            'page_title' => $page_title,
            'page_name' => $page_name,
        );

        $view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
		$view['active_menu'] = $this->active_menu;
		$view['view']['dateset'] = $this->dateset;

		foreach($this->layout_value as $key => $val) $view['layout'][$key] = $val;

        $this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
       
        $this->benchmark->mark('code_end');
    }
    
	/**
	 * view 데이터를 json형태로 출력하는 레이아웃 처리
	 */
    protected function _json_layout($view)
    {
        header('content-type: application/json');
        header('cache-control: no-cache, must-revalidate');
        header('pragma: no-cache');

		$data =& $view['view']['data'];
		if(!array_key_exists('result', $data)) {
			$data['result'] = 'success';
		}
		if(!array_key_exists('data', $data)) {
			$data['data'] = [];
		}
		if(!array_key_exists('error', $data)) {
			$data['error'] = '';
		}

        $this->data = $view;
        $skin = $this->cbconfig->item('skin_default');

        $this->view = 'helptool/' . $skin . '/json';

        $this->benchmark->mark('code_end');
    }
}
