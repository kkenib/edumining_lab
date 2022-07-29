<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Mypage class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 마이페이지와 관련된 controller 입니다.
 */
class Mypage extends CB_Controller
{

    /**
     * 모델을 로딩합니다
     */
    protected $models = array();

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
        $this->load->library(array('pagination', 'querystring'));
    }


    /**
     * 마이페이지 - 회원정보 페이지 입니다
     */
    public function index()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_mypage_index';
        $this->load->event($eventname);

        /**
         * 로그인이 필요한 페이지입니다
         */
        required_user_login();

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $registerform = $this->cbconfig->item('registerform');
        $view['view']['memberform'] = json_decode($registerform, true);

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

        $skin_name = 'main';

        $layoutconfig = array(
            'path' => 'mypage',
            'layout' => 'layout',
            'skin' => $skin_name,
            'layout_dir' => '../mypage',
            'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_mypage'),
            'use_sidebar' => $this->cbconfig->item('sidebar_mypage'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_mypage'),
            'skin_dir' => $this->cbconfig->item('skin_mypage'),
            'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_mypage'),
            'page_title' => $page_title,
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'meta_author' => $meta_author,
            'page_name' => $page_name,
        );
        $view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
    }

	/**
     * 마이페이지 - 회원정보 페이지 입니다
     */
    public function group()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_mypage_group';
        $this->load->event($eventname);

        /**
         * 로그인이 필요한 페이지입니다
         */
        required_user_login();

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $registerform = $this->cbconfig->item('registerform');
        $view['view']['memberform'] = json_decode($registerform, true);

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

        $skin_name = 'member_group';

        $layoutconfig = array(
            'path' => 'mypage',
            'layout' => 'layout',
            'skin' => $skin_name,
            'layout_dir' => '../mypage',
            'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_mypage'),
            'use_sidebar' => $this->cbconfig->item('sidebar_mypage'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_mypage'),
            'skin_dir' => $this->cbconfig->item('skin_mypage'),
            'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_mypage'),
            'page_title' => $page_title,
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'meta_author' => $meta_author,
            'page_name' => $page_name,
        );
        $view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
    }


	/**
     * 그룹에 사용할 사용자리스트 함수입니다
     */
    public function memListUser()
    {
		required_user_login();

		//log_message("error",$this->Member_model->get_by_username('theimc12')['mem_id']);

		$result = $this->Member_model->get_group_member_list($this->member->item('mem_userid'),'');

        echo json_encode($result);
    }

	/**
     * 특정 그룹 사용자리스트 함수입니다
     */
    public function memGroupList()
    {
		required_user_login();

		$group_name = $this->input->post('group_name', null, '');

		$result = $this->Member_model->get_group_member_list($this->member->item('mem_userid'),$group_name);

        echo json_encode($result);
    }

	/**
     * 그룹에 사용자 추가 및 삭제 함수입니다
     */
    public function memGroupAdd()
    {
		required_user_login();
		$this->load->model('voice/Voice_upload_time_model');
		$group_name = $this->input->post('group_name', null, '');
		$group_idx = $this->input->post('group_idx', null, 0);
		$checkboxValues = $this->input->post('checkboxValues', null, '');
		$chk_stat = $this->input->post('chk_stat', null, '');

		if($chk_stat =="del"){
			for($i=0; $i<count($checkboxValues); $i++){
				$updatedata = array(
						'mem_group_voice' => '',
						'mem_group_top' => 0,
				);
				$updatedata_tmp = array(
						'group_name' => '',
						'group_idx' => 0,
				);

				$where = array(
						'mem_id' => $checkboxValues[$i],
				);

				$this->Member_model->update($checkboxValues[$i], $updatedata);
				
				$this->db->set_dbprefix('');
				$this->Voice_upload_time_model->update('',$updatedata_tmp,$where);
				$this->db->set_dbprefix('t_');
			}
		}else{
			for($i=0; $i<count($checkboxValues); $i++){
				$updatedata = array(
						'mem_group_voice' => $group_name,
				);
				$updatedata_tmp = array(
						'group_name' => $group_name,
						'group_idx' => $group_idx,
				);
				$where = array(
							'mem_id' => $checkboxValues[$i],
					);
				//log_message("error","count = ".json_encode($updatedata));
				$this->Member_model->update($checkboxValues[$i], $updatedata);
				
				$this->db->set_dbprefix('');
				$this->Voice_upload_time_model->update('',$updatedata_tmp,$where);
				$this->db->set_dbprefix('t_');
			}

		}

		$result = $this->Member_model->get_member_group_top($this->member->item('mem_userid'),$group_name);
		$top_user = $result['mem_id'];
		$where = array(
				'mem_group_voice' => $group_name,
				'mem_parent' => $this->member->item('mem_userid'),
		);
		$updatedata = array(
						'mem_group_top' => $top_user,
				);
		$this->Member_model->update('', $updatedata,$where);
		
        echo json_encode("성공");
    }

	/**
     * 그룹 삭제 함수입니다
     */
    public function memGroupDelete()
    {
		required_user_login();
		$this->load->model('voice/Voice_upload_time_model');
		$group_name = $this->input->post('group_name', null, '');
		$group_idx = $this->input->post('group_idx', null, '');
		$result = $this->Member_model->get_group_member_list($this->member->item('mem_userid'),$group_name);

		foreach($result as $key => $value){
			$updatedata = array(
					'mem_group_voice' => '',
			);	
			$updatedata_tmp = array(
					'group_name' => '',
			);
			$where = array(
					'mem_id' => $value['mem_id'],
			);
			$this->Member_model->update($value['mem_id'], $updatedata);

			$this->db->set_dbprefix('');

			$this->Voice_upload_time_model->update('',$updatedata_tmp,$where);
			$this->db->set_dbprefix('t_');
		}
		$this->load->model('Member_group_model');
		$result = $this->Member_group_model->delete($group_idx);
        echo json_encode("성공");
    }

	

	/**
     * 그룹리스트 함수입니다
     */
    public function memListGroup()
    {
		required_user_login();

		$this->load->model('Member_group_model');

		$result = $this->Member_group_model->get_by_both($this->member->item('mem_id'));

        echo json_encode($result);
    }

	/**
     * 그룹체크 함수입니다
     */
    public function memGroupChk()
    {
		required_user_login();

		$this->load->model('Member_group_model');

		$group_name = $this->input->post('group_name', null, '');

		$insertdata = array();
		$insertdata['mem_id'] = $this->member->item('mem_id');
		$insertdata['group_name'] = $group_name;

		$result = $this->Member_group_model->get_group_name($this->member->item('mem_id'),$group_name);

        echo json_encode($result);
    }

	/**
     * 그룹추가 함수입니다
     */
    public function memCreateGroup()
    {
		required_user_login();

		$this->load->model('Member_group_model');

		$group_name = $this->input->post('group_name', null, '');
		$tmp_key = array();
		for($i=0;$i<5;$i++){
			$j = rand(1,15);
			if(in_array($j,$tmp_key)){
				$i--;
				continue;
			}
			$tmp_key[]=$j; 
		}
		sort($tmp_key);

		$insertdata = array();
		$insertdata['mem_id'] = $this->member->item('mem_id');
		$insertdata['mem_userid'] = $this->member->item('mem_userid');
		$insertdata['group_name'] = $group_name;
		$insertdata['keyword_list'] = implode(", ",array_values($tmp_key));

		$mem_id = $this->Member_group_model->insert($insertdata);

		$result = $this->Member_group_model->get_by_both($this->member->item('mem_id'));

        echo json_encode($result);
    }

}
