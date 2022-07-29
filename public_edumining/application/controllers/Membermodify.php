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
class Membermodify extends CB_Controller
{

    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Member_nickname', 'Member_auth_email');

    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('form', 'array', 'string');

    function __construct()
    {
        parent::__construct();

        /**
         * 라이브러리를 로딩합니다
         */
        $this->load->library(array('querystring', 'form_validation', 'email'));
    }


    /**
     * 회원정보 수정 페이지입니다
     */
    public function index()
    {

        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_membermodify_index';
        $this->load->event($eventname);

        /**
         * 로그인이 필요한 페이지입니다
         */
        required_user_login();

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $mem_id = (int) $this->member->item('mem_id');
        
        // sns 로그인 체크
        $mem_group = $this->member->item('mem_group');
        if($mem_group === 'kakao' OR $mem_group === 'naver') {
        	// 이벤트가 존재하면 실행합니다
        	$view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);
        	
        	$this->session->set_userdata(
        			'membermodify',
        			'1'
        			);
        	redirect('membermodify/modify');
        }
        
        $this->load->library(array('form_validation'));

        if ( ! function_exists('password_hash')) {
            $this->load->helper('password');
        }

        $login_fail = false;
        $valid_fail = false;

        /**
         * 전송된 데이터의 유효성을 체크합니다
         */
        $config = array(
            array(
                'field' => 'mem_password',
                'label' => '패스워드',
                'rules' => 'trim|required|min_length[4]|callback__cur_password_check',
            ),
        );
        $this->form_validation->set_rules($config);
        /**
         * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
         * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
         */
        if ($this->form_validation->run() === false) {

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['formrunfalse'] = Events::trigger('formrunfalse', $eventname);

            $skin = 'member_password';

            $view['view']['canonical'] = site_url('membermodify');

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

            /**
             * 레이아웃을 정의합니다
             */
            $page_title = $this->cbconfig->item('site_meta_title_membermodify');
            $meta_description = $this->cbconfig->item('site_meta_description_membermodify');
            $meta_keywords = $this->cbconfig->item('site_meta_keywords_membermodify');
            $meta_author = $this->cbconfig->item('site_meta_author_membermodify');
            $page_name = $this->cbconfig->item('site_page_name_membermodify');

            $layoutconfig = array(
                'path' => 'mypage',
                'layout' => 'layout',
                'skin' => $skin,
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
        } else {
            /**
             * 유효성 검사를 통과한 경우입니다.
             * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
             */

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

            $this->session->set_userdata(
                'membermodify',
                '1'
            );
            redirect('membermodify/modify');
        }
    }



    public function modifyFirstUser() {
        required_user_login();
    }

    /**
     * 회원정보 수정 페이지입니다
     */
    public function modify()
    {

        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_membermodify_modify';
        $this->load->event($eventname);

        if ( ! $this->session->userdata('membermodify')) {
            redirect('membermodify');
        }

        /**
         * 로그인이 필요한 페이지입니다
         */
        required_user_login();

        $mem_id = (int) $this->member->item('mem_id');

         if ( ! function_exists('password_hash')) {
            $this->load->helper('password');
        }

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $email_description = '';
        if ($this->cbconfig->item('use_register_email_auth')) {
            $email_description = '이메일을 변경하시면 메일 인증 후에 계속 사용이 가능합니다';
        }

        $configbasic = array();

        $nickname_description = '';
        if ($this->cbconfig->item('change_nickname_date')) {
            if ($can_update_nickname === false) {
                /*
                $nickname_description = '<br />닉네임을 변경하시면 ' . $this->cbconfig->item('change_nickname_date')
                    . '일 이내에는 변경할 수 없습니다<br>회원님은 ' . $when_can_update_nickname
                    . ' 이후에 닉네임 변경이 가능합니다';
                */
                $nickname_description = '회원님은 '.$when_can_update_nickname.' 이후에 닉네임 변경이 가능합니다.('.$this->cbconfig->item('change_nickname_date').'일 이내 변경 불가)';
            } else {
                $nickname_description = '회원님은 '.$when_can_update_nickname.' 이후에 닉네임 변경이 가능합니다.('.$this->cbconfig->item('change_nickname_date').'일 이내 변경 불가)';
                //$nickname_description = '회원님은 ' . $this->cbconfig->item('change_nickname_date') . '일 이내에는 변경할 수 없습니다';
            }
        }

        $configbasic['mem_username'] = array(
            'field' => 'mem_username',
            'label' => '성명',
            'rules' => 'trim|min_length[2]|max_length[20]',
        );
        $configbasic['mem_nickname'] = array(
            'field' => 'mem_nickname',
            'label' => '닉네임',
            'rules' => 'trim|required|min_length[2]|max_length[20]|callback__mem_nickname_check',
            'description' => $nickname_description,
        );
        $configbasic['mem_email'] = array(
            'field' => 'mem_email',
            'label' => '이메일',
        	'rules' => 'trim|required|valid_email|max_length[50]|callback__mem_email_check',        		
            //'rules' => 'trim|required|valid_email|max_length[50]|is_unique[member.mem_email.mem_id.' . $mem_id . ']|callback__mem_email_check',
            'description' => $email_description,
        );
        $configbasic['mem_phone'] = array(
            'field' => 'mem_phone',
            'label' => '전화번호',
            'rules' => 'trim|valid_phone',
        );
        $configbasic['mem_birthday'] = array(
            'field' => 'mem_birthday',
            'label' => '생년월일',
            'rules' => 'trim|exact_length[10]',
        );
        $configbasic['mem_sex'] = array(
            'field' => 'mem_sex',
            'label' => '성별',
            'rules' => 'trim|exact_length[1]',
        );
        $configbasic['mem_zipcode'] = array(
            'field' => 'mem_zipcode',
            'label' => '우편번호',
            'rules' => 'trim|min_length[5]|max_length[7]',
        );
        $configbasic['mem_address'] = array(
            'field' => 'mem_address',
            'label' => '기본주소',
            'rules' => 'trim',
        );
        $configbasic['mem_agency'] = array(
        		'field' => 'mem_agency',
        		'label' => '소속학교',
        		'rules' => 'trim|min_length[2]|max_length[30]',
        );
        
        $configbasic['mem_division_nm'] = array(
        		'field' => 'mem_division_nm',
        		'label' => '학년',
        		'rules' => 'trim|min_length[1]',
        );

		$configbasic['mem_std_count'] = array(
        		'field' => 'mem_std_count',
        		'label' => '담당 학생 수',
        		'rules' => 'trim|min_length[1]',
        );

        $this->load->library(array('form_validation'));
        $login_fail = false;
        $valid_fail = false;

        $registerform = $this->cbconfig->item('registerform');

        $form = json_decode($registerform, true);
        $form["mem_username"]["display_name"] = "성명";

        $config = array();
        if ($form && is_array($form)) {
            foreach ($form as $key => $value) {
                if ( ! element('use', $value)) {
                    continue;
                }
                if ($key === 'mem_userid' OR $key === 'mem_password' OR $key === 'mem_phone') {
                    continue;
                }
                if (element('func', $value) === 'basic') {
                    if ($key === 'mem_address') {
                        if (element('required', $value) === '1') {
                            $configbasic['mem_zipcode']['rules'] = $configbasic['mem_zipcode']['rules'] . '|required';
                        }
                        $config[] = $configbasic['mem_zipcode'];
                        if (element('required', $value) === '1') {
                            $configbasic['mem_address']['rules'] = $configbasic['mem_address']['rules'] . '|required';
                        }
                    } else {
                        if (element('required', $value) === '1') {
                            $configbasic[$value['field_name']]['rules'] = $configbasic[$value['field_name']]['rules'] . '|required';
                        }
                        if (element('field_type', $value) === 'phone') {
                            $configbasic[$value['field_name']]['rules'] = $configbasic[$value['field_name']]['rules'] . '|valid_phone';
                        }
                        $config[] = $configbasic[$value['field_name']];
                    }
                } else {
                    $required = element('required', $value) ? '|required' : '';
                    if (element('field_type', $value) === 'checkbox') {
                        $config[] = array(
                            'field' => element('field_name', $value) . '[]',
                            'label' => $value['display_name'],
                            'rules' => 'trim' . $required,
                        );
                    } else {
                        $config[] = array(
                            'field' => element('field_name', $value),
                            'label' => $value['display_name'],
                            'rules' => 'trim' . $required,
                        );
                    }
                }
            }
        }

        $this->form_validation->set_rules($config);
        $form_validation = $this->form_validation->run();
        $file_error = '';
        $file_error2 = '';

        /**
         * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
         * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
         */
        if ($form_validation === false OR  $file_error !== '' OR $file_error2 !== '') {

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['formrunfalse'] = Events::trigger('formrunfalse', $eventname);

            $view['view']['message'] = $file_error . $file_error2;

            $html_content = '';
            $k = 0;
            if ($form && is_array($form)) {
                foreach ($form as $key => $value) {
                    if ( ! element('use', $value)) {
                        continue;
                    }
                    if ($key === 'mem_userid' OR $key === 'mem_password' OR $key === 'mem_phone') {
                        continue;
                    }
                    $required = element('required', $value) ? 'required' : '';

                    $item = $this->member->item(element('field_name', $value));
                    $html_content[$k]['field_name'] = element('field_name', $value);
                    $html_content[$k]['display_name'] = element('display_name', $value);
                    $html_content[$k]['input'] = '';
					
                	switch( element('field_type', $value)) {
                        case 'url':
                            $input_tag_classes = 'input_box w_300';
                            break;

                        case 'email':
                            $input_tag_classes = 'input_box w_200';
                            break;

                        case 'phone':
                            $input_tag_classes = 'input_box w_150';
                            break;

                        case 'date':
                            $input_tag_classes = 'input_box w_120';
                            break;

                        case 'textarea':
                            $input_tag_classes = 'input_box w_300';
                            break;

                        case 'mem_password':
                            $input_tag_classes = 'input_box w_120';
                            break;

                        default :
                            $input_tag_classes = 'input_box w_120';
                    }

                    if(element('field_name', $value) == 'mem_com_name' 
                            || element('field_name', $value) == 'mem_job_major'
                            || element('field_name', $value) == 'mem_job_position'
                            || element('field_name', $value) == 'mem_job_phone'
                            || element('field_name', $value) == 'mem_job_fax') {   
                        $input_tag_classes = 'input_box w_300';
                    }

                    //field_type : text, url, email, phone, textarea, radio, select, checkbox, date
                    if (element('field_type', $value) === 'text'
                        OR element('field_type', $value) === 'url'
                        OR element('field_type', $value) === 'email'
                        OR element('field_type', $value) === 'phone'
                        OR element('field_type', $value) === 'date') {
                        if (element('field_type', $value) === 'date') {
                            $html_content[$k]['input'] .= '<input type="text" id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="'.$input_tag_classes.' datepicker" value="' . set_value(element('field_name', $value), $item) . '" readonly="readonly" ' . $required . ' />';
                        } elseif (element('field_type', $value) === 'phone') {
                            $html_content[$k]['input'] .= '<input type="text" id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="'.$input_tag_classes.' validphone" value="' . set_value(element('field_name', $value), $item) . '" ' . $required . ' />';
                        } else {
                            $readonly = '';
                            if (element('field_name', $value) === 'mem_nickname' && $can_update_nickname === false) {
                                $readonly = 'readonly="readonly"';
                            }

							//if (element('field_name', $value) === 'mem_username') {
							//	$readonly = 'readonly="readonly" style="cursor: default;"';
							//}	
							
                            $html_content[$k]['input'] .= '<input type="' . element('field_type', $value) . '" id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="'.$input_tag_classes.'" value="' . set_value(element('field_name', $value), $item) . '" ' . $readonly . ' ' . $required . ' />';
                        }
                    } elseif (element('field_type', $value) === 'textarea') {
                        $html_content[$k]['input'] .= '<textarea id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="'.$input_tag_classes.'" ' . $required . ' >' . set_value(element('field_name', $value), $item) . '</textarea>';
                    } elseif (element('field_type', $value) === 'radio') {
                        $html_content[$k]['input'] .= '<div class="checkbox">';
                        if (element('field_name', $value) === 'mem_sex') {
                            $options = array(
                                '1' => '남성',
                                '2' => '여성',
                            );
                        } else {
                            $options = explode("\n", element('options', $value));
                        }
                        $i =1;
                        if ($options) {
                            foreach ($options as $okey => $oval) {
                                $oval = trim($oval);
                                $radiovalue = (element('field_name', $value) === 'mem_sex') ? $okey : $oval;
                                $html_content[$k]['input'] .= '<label for="' . element('field_name', $value) . '_' . $i . '"><input type="radio" name="' . element('field_name', $value) . '" id="' . element('field_name', $value) . '_' . $i . '" value="' . $radiovalue . '" ' . set_radio(element('field_name', $value), $radiovalue, ($item === strval($radiovalue) ? true : false)) . ' /> ' . $oval . ' </label> ';
                            $i++;
                            }
                        }
                        $html_content[$k]['input'] .= '</div>';
                    } elseif (element('field_type', $value) === 'checkbox') {
                        $html_content[$k]['input'] .= '<div class="checkbox">';
                        $options = explode("\n", element('options', $value));
                        $item = json_decode($item, true);
                        $i =1;
                        if ($options) {
                            foreach ($options as $okey => $oval) {
                                $oval = trim($oval);
                                $chkvalue = is_array($item) && in_array($oval, $item) ? $oval : '';
                                $html_content[$k]['input'] .= '<label for="' . element('field_name', $value) . '_' . $i . '"><input type="checkbox" name="' . element('field_name', $value) . '[]" id="' . element('field_name', $value) . '_' . $i . '" value="' . $oval . '" ' . set_checkbox(element('field_name', $value), $oval, ($chkvalue === $oval ? true : false)) . ' /> ' . $oval . ' </label> ';
                            $i++;
                            }
                        }
                        $html_content[$k]['input'] .= '</div>';
                    } elseif (element('field_type', $value) === 'select') {
                        $html_content[$k]['input'] .= '<div class="input-group">';
                        $html_content[$k]['input'] .= '<select name="' . element('field_name', $value) . '" class="form-control input" ' . $required . '>';
                        $html_content[$k]['input'] .= '<option value="" >선택하세요</option> ';
                        $options = explode("\n", element('options', $value));
                        if ($options) {
                            foreach ($options as $okey => $oval) {
                                $oval = trim($oval);
                                $html_content[$k]['input'] .= '<option value="' . $oval . '" ' . set_select(element('field_name', $value), $oval, ($item === $oval ? true : false)) . ' >' . $oval . '</option> ';
                            }
                        }
                        $html_content[$k]['input'] .= '</select>';
                        $html_content[$k]['input'] .= '</div>';
                    } elseif (element('field_name', $value) === 'mem_address') {
                        $html_content[$k]['input'] .= '
                                    <button type="button" class="btn_Sgray mb_5" style="margin-top:0px;" onclick="win_zip(\'fregisterform\', \'mem_zipcode\', \'mem_address1\', \'mem_address2\', \'mem_address3\', \'mem_address4\');">주소검색</button>
                                    <input type="text" name="mem_zipcode" value="' . set_value('mem_zipcode', $this->member->item('mem_zipcode')) . '" id="mem_zipcode" size="7" maxlength="7" class="input_box w_150 mb_5" ' . $required . '/>
                                    <div class="addr-line mt10">
                                    <input type="text" name="mem_address" value="' . set_value('mem_address', $this->member->item('mem_address')) . '" id="mem_address" class="input_box mb_5" style="width:90%" placeholder="기본주소" ' . $required . ' />
                                    </div>
                            ';
                    }

                    $html_content[$k]['description'] = '';
                    if (isset($configbasic[$value['field_name']]['description']) && $configbasic[$value['field_name']]['description']) {
                        $html_content[$k]['description'] = $configbasic[$value['field_name']]['description'];
                    }
                    $k++;
                }
            }

            $view['view']['html_content'] = $html_content;
            $view['view']['canonical'] = site_url('membermodify/modify');

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

            /**
             * 레이아웃을 정의합니다
             */
            $page_title = $this->cbconfig->item('site_meta_title_membermodify');
            $meta_description = $this->cbconfig->item('site_meta_description_membermodify');
            $meta_keywords = $this->cbconfig->item('site_meta_keywords_membermodify');
            $meta_author = $this->cbconfig->item('site_meta_author_membermodify');
            $page_name = $this->cbconfig->item('site_page_name_membermodify');
            
            $skin_name = 'member_modify';

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

        } else {
            /**
             * 유효성 검사를 통과한 경우입니다.
             * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
             */

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

            $updatedata = array();
            $metadata = array();
            $updatedata['mem_email'] = $this->input->post('mem_email');
            if ($this->member->item('mem_email') !== $this->input->post('mem_email')) {
                $updatedata['mem_email_cert'] = 0;
                $metadata['meta_email_cert_datetime'] = '';
            }
            if ($this->member->item('mem_nickname') !== $this->input->post('mem_nickname')) {
                $updatedata['mem_nickname'] = $this->input->post('mem_nickname');
                $metadata['meta_nickname_datetime'] = cdate('Y-m-d H:i:s');

                $upnick = array(
                    'mni_end_datetime' => cdate('Y-m-d H:i:s'),
                );
                $nickwhere = array(
                    'mem_id' => $mem_id,
                    'mni_nickname' => $this->member->item('mem_nickname'),
                );
                $this->Member_nickname_model->update('', $upnick, $nickwhere);

                $nickinsert = array(
                    'mem_id' => $mem_id,
                    'mni_nickname' => $this->input->post('mem_nickname'),
                    'mni_start_datetime' => cdate('Y-m-d H:i:s'),
                );
                $this->Member_nickname_model->insert($nickinsert);
            }

            if (isset($form['mem_username']['use']) && $form['mem_username']['use']) {
                $updatedata['mem_username'] = $this->input->post('mem_username', null, '');

				//$this->load->model('voice/Voice_upload_time_model');
				//$this->load->model('voice/Voice_time_list_model');
				
				$this->db->set_dbprefix('');
				$updatetimefile = array();
				$updatetimefile['mem_username'] = $this->input->post('mem_username', null, '');
				$wheretime = array('mem_userid' =>  $this->member->item('mem_userid'));
				//$this->Voice_upload_time_model->update('', $updatetimefile,$wheretime);
				//$this->Voice_time_list_model->update('', $updatetimefile,$wheretime);
				$this->db->set_dbprefix('t_');

            }
            if (isset($form['mem_phone']['use']) && $form['mem_phone']['use']) {
                $updatedata['mem_phone'] = $this->input->post('mem_phone', null, '');
            }
            if (isset($form['mem_birthday']['use']) && $form['mem_birthday']['use']) {
                $updatedata['mem_birthday'] = $this->input->post('mem_birthday', null, '');
            }
            if (isset($form['mem_sex']['use']) && $form['mem_sex']['use']) {
                $updatedata['mem_sex'] = $this->input->post('mem_sex', null, '');
            }
            if (isset($form['mem_address']['use']) && $form['mem_address']['use']) {
                $updatedata['mem_zipcode'] = $this->input->post('mem_zipcode', null, '');
                $updatedata['mem_address'] = $this->input->post('mem_address', null, '');
            }
            if (isset($form['mem_agency']['use']) && $form['mem_agency']['use']) {
            	$updatedata['mem_agency'] = $this->input->post('mem_agency', null, '');
            }
            
            $this->Member_model->update($mem_id, $updatedata);

			



            if ($this->cbconfig->item('use_register_email_auth')
                && $this->member->item('mem_email') !== $this->input->post('mem_email')) {

                $vericode = array('$', '/', '.');
                $verificationcode = str_replace(
                    $vericode,
                    '',
                    password_hash($mem_id . '-' . $this->input->post('mem_email') . '-' . random_string('alnum', 10), PASSWORD_BCRYPT)
                );

                $beforeauthdata = array(
                    'mem_id' => $mem_id,
                    'mae_type' => 2,
                );
                $this->Member_auth_email_model->delete_where($beforeauthdata);
                $authdata = array(
                    'mem_id' => $mem_id,
                    'mae_key' => $verificationcode,
                    'mae_type' => 2,
                    'mae_generate_datetime' => cdate('Y-m-d H:i:s'),
                );
                $this->Member_auth_email_model->insert($authdata);

                $verify_url = site_url('verify/confirmemail?user=' . $this->member->item('mem_userid') . '&code=' . $verificationcode);

                $searchconfig = array(
                    '{홈페이지명}',
                    '{회사명}',
                    '{홈페이지주소}',
                    '{회원아이디}',
                    '{회원닉네임}',
                    '{회원실명}',
                    '{회원이메일}',
                    '{변경전이메일}',
                    '{회원아이피}',
                    '{메일인증주소}',
                );
                
                $replaceconfig = array(
                    $this->cbconfig->item('site_title'),
                    $this->cbconfig->item('company_name'),
                    site_url(),
                    $this->member->item('mem_userid'),
                    $this->member->item('mem_nickname'),
                    $this->member->item('mem_username'),
                    $this->input->post('mem_email'),
                    $this->member->item('mem_email'),
                    $this->input->ip_address(),
                    $verify_url,
                );

                $replaceconfig_escape = array(
                    html_escape($this->cbconfig->item('site_title')),
                    html_escape($this->cbconfig->item('company_name')),
                    site_url(),
                    $this->member->item('mem_userid'),
                    html_escape($this->member->item('mem_nickname')),
                    html_escape($this->member->item('mem_username')),
                    html_escape($this->input->post('mem_email')),
                    html_escape($this->member->item('mem_email')),
                    $this->input->ip_address(),
                    $verify_url,
                );

                $title = str_replace(
                    $searchconfig,
                    $replaceconfig,
                    $this->cbconfig->item('send_email_changeemail_user_title')
                );
                $content = str_replace(
                    $searchconfig,
                    $replaceconfig_escape,
                    $this->cbconfig->item('send_email_changeemail_user_content')
                );

                $this->email->clear(true);
                $this->email->from($this->cbconfig->item('webmaster_email'), $this->cbconfig->item('webmaster_name'));
                $this->email->to($this->input->post('mem_email'));
                $this->email->subject($title);
                $this->email->message($content);
                $this->email->send();

                $view['view']['result_message'] = $this->input->post('mem_email') . '로 인증메일이 발송되었습니다. <br />발송된 인증메일을 확인하신 후에 사이트 이용이 가능합니다';

                $this->session->sess_destroy();

            } else {
                $view['view']['result_message'] = '회원님의 개인정보가 변경되었습니다.';
            }

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['before_result_layout'] = Events::trigger('before_result_layout', $eventname);

            $page_title = $this->cbconfig->item('site_meta_title_membermodify');
            $meta_description = $this->cbconfig->item('site_meta_description_membermodify');
            $meta_keywords = $this->cbconfig->item('site_meta_keywords_membermodify');
            $meta_author = $this->cbconfig->item('site_meta_author_membermodify');
            $page_name = $this->cbconfig->item('site_page_name_membermodify');

            $skin_name = 'member_modify_result';

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
    }


    /**
     * 회원정보 수정중 패스워드 변경 페이지입니다
     */
    public function password_modify()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_membermodify_password_modify';
        $this->load->event($eventname);

        /**
         * 로그인이 필요한 페이지입니다
         */
        required_user_login();

        $mem_id = (int) $this->member->item('mem_id');

        if ( ! $this->session->userdata('membermodify')) {
            redirect('membermodify');
        }

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        /**
         * Validation 라이브러리를 가져옵니다
         */
        $this->load->library('form_validation');

         if ( ! function_exists('password_hash')) {
            $this->load->helper('password');
        }

        /**
         * 전송된 데이터의 유효성을 체크합니다
         */

        $password_length = $this->cbconfig->item('password_length');
        $view['view']['password_length'] = $password_length;

        $config = array(
            array(
                'field' => 'cur_password',
                'label' => '현재패스워드',
                'rules' => 'trim|required|callback__cur_password_check',
            ),
            array(
                'field' => 'new_password',
                'label' => '새로운패스워드',
                'rules' => 'trim|required|min_length[' . $password_length . ']|max_length[20]|callback__mem_password_check',
            ),
            array(
                'field' => 'new_password_re',
                'label' => '새로운패스워드',
                'rules' => 'trim|required|min_length[' . $password_length . ']|max_length[20]|matches[new_password]',
            ),
        );
        $this->form_validation->set_rules($config);

        /**
         * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
         * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
         */
        if ($this->form_validation->run() === false) {

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['formrunfalse'] = Events::trigger('formrunfalse', $eventname);

            $view['view']['canonical'] = site_url('membermodify/password_modify');

            //$password_description = '비밀번호는 ' . $password_length . '자리 이상이어야 ';
            //$password_description = '영문, 숫자, 특수문자를 반드시 포함하여 '.$password_length.'자 이상 입력해주세요';
            $password_description = '영문, 숫자를 조합하여 6~12자이내로 입력하여 주세요';

            $view['view']['info'] = $password_description;

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

            /**
             * 레이아웃을 정의합니다
             */
            $page_title = $this->cbconfig->item('site_meta_title_membermodify');
            $meta_description = $this->cbconfig->item('site_meta_description_membermodify');
            $meta_keywords = $this->cbconfig->item('site_meta_keywords_membermodify');
            $meta_author = $this->cbconfig->item('site_meta_author_membermodify');
            $page_name = $this->cbconfig->item('site_page_name_membermodify');

            $layoutconfig = array(
                'path' => 'mypage',
                'layout' => 'layout',
                'skin' => 'password_modify',
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

        } else {

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

            $hash = password_hash($this->input->post('new_password'), PASSWORD_BCRYPT);

            $updatedata = array(
                'mem_password' => $hash,
            );
            $this->Member_model->update($mem_id, $updatedata);
            $metadata = array(
                'meta_change_pw_datetime' => cdate('Y-m-d H:i:s'),
            );


            $emailsendlistadmin = array();
            $emailsendlistuser = array();

            $superadminlist = '';
            if ($this->cbconfig->item('send_email_changepw_admin')
                OR $this->cbconfig->item('send_note_changepw_admin')) {
                $mselect = 'mem_id, mem_email, mem_nickname, mem_phone';
                $superadminlist = $this->Member_model->get_superadmin_list($mselect);
            }
            if ($this->cbconfig->item('send_email_changepw_admin') && $superadminlist) {
                foreach ($superadminlist as $key => $value) {
                    $emailsendlistadmin[$value['mem_id']] = $value;
                }
            }
            if (($this->cbconfig->item('send_email_changepw_user') && $this->member->item('mem_receive_email'))
                OR $this->cbconfig->item('send_email_changepw_alluser')) {
                $emailsendlistuser['mem_email'] = $this->member->item('mem_email');
            }
            
            $searchconfig = array(
                '{홈페이지명}',
                '{회사명}',
                '{홈페이지주소}',
                '{회원아이디}',
                '{회원닉네임}',
                '{회원실명}',
                '{회원이메일}',
                '{회원아이피}',
            );
            
            $replaceconfig = array(
                $this->cbconfig->item('site_title'),
                $this->cbconfig->item('company_name'),
                site_url(),
                $this->member->item('mem_userid'),
                $this->member->item('mem_nickname'),
                $this->member->item('mem_username'),
                $this->member->item('mem_email'),
                $this->input->ip_address(),
            );
            $replaceconfig_escape = array(
                html_escape($this->cbconfig->item('site_title')),
                html_escape($this->cbconfig->item('company_name')),
                site_url(),
                html_escape($this->member->item('mem_userid')),
                html_escape($this->member->item('mem_nickname')),
                html_escape($this->member->item('mem_username')),
                html_escape($this->member->item('mem_email')),
                $this->input->ip_address(),
            );
            if ($emailsendlistadmin) {
                $title = str_replace(
                    $searchconfig,
                    $replaceconfig,
                    $this->cbconfig->item('send_email_changepw_admin_title')
                );
                $content = str_replace(
                    $searchconfig,
                    $replaceconfig_escape,
                    $this->cbconfig->item('send_email_changepw_admin_content')
                );
                foreach ($emailsendlistadmin as $akey => $aval) {
                    $this->email->clear(true);
                    $this->email->from($this->cbconfig->item('webmaster_email'), $this->cbconfig->item('webmaster_name'));
                    $this->email->to(element('mem_email', $aval));
                    $this->email->subject($title);
                    $this->email->message($content);
                    $this->email->send();
                }
            }
            if ($emailsendlistuser) {
                $title = str_replace(
                    $searchconfig,
                    $replaceconfig,
                    $this->cbconfig->item('send_email_changepw_user_title')
                );
                $content = str_replace(
                    $searchconfig,
                    $replaceconfig_escape,
                    $this->cbconfig->item('send_email_changepw_user_content')
                );
                $this->email->clear(true);
                $this->email->from($this->cbconfig->item('webmaster_email'), $this->cbconfig->item('webmaster_name'));
                $this->email->to(element('mem_email', $emailsendlistuser));
                $this->email->subject($title);
                $this->email->message($content);
                $this->email->send();
            }

            $view['view']['result_message'] = '회원님의 패스워드가 변경되었습니다';

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['before_result_layout'] = Events::trigger('before_result_layout', $eventname);

            /**
             * 레이아웃을 정의합니다
             */
            $page_title = $this->cbconfig->item('site_meta_title_membermodify');
            $meta_description = $this->cbconfig->item('site_meta_description_membermodify');
            $meta_keywords = $this->cbconfig->item('site_meta_keywords_membermodify');
            $meta_author = $this->cbconfig->item('site_meta_author_membermodify');
            $page_name = $this->cbconfig->item('site_page_name_membermodify');

            $layoutconfig = array(
                'path' => 'mypage',
                'layout' => 'layout',
                'skin' => 'member_modify_result',
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
    }


    /**
     * 회원탈퇴 페이지입니다
     */
    public function memberleave()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_membermodify_memberleave';
        $this->load->event($eventname);

        /**
         * 로그인이 필요한 페이지입니다
         */
        required_user_login();

        $mem_id = (int) $this->member->item('mem_id');

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $this->load->library(array('form_validation'));
        $login_fail = false;
        $valid_fail = false;

        /**
         * 전송된 데이터의 유효성을 체크합니다
         */
        $config = array(
            array(
                'field' => 'mem_password',
                'label' => '패스워드',
                'rules' => 'trim|required|min_length[4]|callback__cur_password_check',
            ),
        );
        $this->form_validation->set_rules($config);
        /**
         * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
         * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
         */
        if ($this->form_validation->run() === false) {

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['formrunfalse'] = Events::trigger('formrunfalse', $eventname);

            /**
             * 레이아웃을 정의합니다
             */
            $page_title = $this->cbconfig->item('site_meta_title_membermodify_memberleave');
            $meta_description = $this->cbconfig->item('site_meta_description_membermodify_memberleave');
            $meta_keywords = $this->cbconfig->item('site_meta_keywords_membermodify_memberleave');
            $meta_author = $this->cbconfig->item('site_meta_author_membermodify_memberleave');
            $page_name = $this->cbconfig->item('site_page_name_membermodify_memberleave');

            if ($this->member->is_admin() === 'super') {
                $skin = 'member_admin';
            } else {
            	$skin = 'memberleave_password';
            }

            $layoutconfig = array(
                'path' => 'mypage',
                'layout' => 'layout',
                'skin' => $skin,
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

        } else {
            /**
             * 유효성 검사를 통과한 경우입니다.
             * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
             */

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

            $emailsendlistadmin = array();
            $notesendlistadmin = array();
            $emailsendlistuser = array();
            $notesendlistuser = array();

            $superadminlist = '';
            if ($this->cbconfig->item('send_email_memberleave_admin')
                OR $this->cbconfig->item('send_note_memberleave_admin')) {
                $mselect = 'mem_id, mem_email, mem_nickname, mem_phone';
                $superadminlist = $this->Member_model->get($mselect);
            }

            if ($this->cbconfig->item('send_email_memberleave_admin') && $superadminlist) {
                foreach ($superadminlist as $key => $value) {
                    $emailsendlistadmin[$value['mem_id']] = $value;
                }
            }
            if (($this->cbconfig->item('send_email_memberleave_user') && $this->member->item('mem_receive_email'))
                OR $this->cbconfig->item('send_email_memberleave_alluser')) {
                $emailsendlistuser['mem_email'] = $this->member->item('mem_email');
            }
            if ($this->cbconfig->item('send_note_memberleave_admin') && $superadminlist) {
                foreach ($superadminlist as $key => $value) {
                    $notesendlistadmin[$value['mem_id']] = $value;
                }
            }

            $searchconfig = array(
                '{홈페이지명}',
                '{회사명}',
                '{홈페이지주소}',
                '{회원아이디}',
                '{회원닉네임}',
                '{회원실명}',
                '{회원이메일}',
                '{회원아이피}',
            );
            
            $replaceconfig = array(
                $this->cbconfig->item('site_title'),
                $this->cbconfig->item('company_name'),
                site_url(),
                $this->member->item('mem_userid'),
                $this->member->item('mem_nickname'),
                $this->member->item('mem_username'),
                $this->member->item('mem_email'),
                $this->input->ip_address(),
            );
            $replaceconfig_escape = array(
                html_escape($this->cbconfig->item('site_title')),
                html_escape($this->cbconfig->item('company_name')),
                site_url(),
                html_escape($this->member->item('mem_userid')),
                html_escape($this->member->item('mem_nickname')),
                html_escape($this->member->item('mem_username')),
                html_escape($this->member->item('mem_email')),
                $this->input->ip_address(),
            );
            if ($emailsendlistadmin) {
                $title = str_replace(
                    $searchconfig,
                    $replaceconfig,
                    $this->cbconfig->item('send_email_memberleave_admin_title')
                );
                $content = str_replace(
                    $searchconfig,
                    $replaceconfig_escape,
                    $this->cbconfig->item('send_email_memberleave_admin_content')
                );
                foreach ($emailsendlistadmin as $akey => $aval) {
                    $this->email->clear(true);
                    $this->email->from($this->cbconfig->item('webmaster_email'), $this->cbconfig->item('webmaster_name'));
                    $this->email->to(element('mem_email', $aval));
                    $this->email->subject($title);
                    $this->email->message($content);
                    $this->email->send();
                }
            }
            if ($emailsendlistuser) {
                $title = str_replace(
                    $searchconfig,
                    $replaceconfig,
                    $this->cbconfig->item('send_email_memberleave_user_title')
                );
                $content = str_replace(
                    $searchconfig,
                    $replaceconfig_escape,
                    $this->cbconfig->item('send_email_memberleave_user_content')
                );
                $this->email->clear(true);
                $this->email->from($this->cbconfig->item('webmaster_email'), $this->cbconfig->item('webmaster_name'));
                $this->email->to(element('mem_email', $emailsendlistuser));
                $this->email->subject($title);
                $this->email->message($content);
                $this->email->send();
            }
            if ($notesendlistadmin) {
                $title = str_replace(
                    $searchconfig,
                    $replaceconfig,
                    $this->cbconfig->item('send_note_memberleave_admin_title')
                );
                $content = str_replace(
                    $searchconfig,
                    $replaceconfig_escape,
                    $this->cbconfig->item('send_note_memberleave_admin_content')
                );
                foreach ($notesendlistadmin as $akey => $aval) {
                    $note_result = $this->notelib->send_note(
                        $sender = 0,
                        $receiver = element('mem_id', $aval),
                        $title,
                        $content,
                        1
                    );
                }
            }

            $this->member->delete_member($mem_id);
            $this->session->sess_destroy();

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

            /**
             * 레이아웃을 정의합니다
             */
            $page_title = $this->cbconfig->item('site_meta_title_membermodify_memberleave');
            $meta_description = $this->cbconfig->item('site_meta_description_membermodify_memberleave');
            $meta_keywords = $this->cbconfig->item('site_meta_keywords_membermodify_memberleave');
            $meta_author = $this->cbconfig->item('site_meta_author_membermodify_memberleave');
            $page_name = $this->cbconfig->item('site_page_name_membermodify_memberleave');

            $skin = 'memberleave';

            $layoutconfig = array(
                'path' => 'mypage',
                'layout' => 'layout',
                'skin' => $skin,
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
    }

    /**
     * 패스워드 변경 후 3개월이 지났을 때, 변경 페이지 출력 일자만 3개월 뒤로 설정함
     */
	public function skip_password_modify() {
		

		// 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_membermodify_password_modify';
        $this->load->event($eventname);

        /**
         * 로그인이 필요한 페이지입니다
         */
        required_user_login();

        $mem_id = (int) $this->member->item('mem_id');

        if ( ! $this->session->userdata('membermodify')) {
            redirect('membermodify');
        }

		$change_date = date('Y-m-d H:i:s', strtotime('+3 month', time()));

		$url_after_login = $this->cbconfig->item('url_after_login');
        
		if ($url_after_login) {
            $url_after_login = site_url($url_after_login);
        }
        if (empty($url_after_login)) {
			$url_after_login = $this->input->get_post('url') ? urldecode($this->input->get_post('url')) : site_url();
        }

        // 이벤트가 존재하면 실행합니다
        Events::trigger('after', $eventname);

        redirect($url_after_login);
	}


    /**
     * 회원정보 수정중 패스워드 변경 페이지입니다
     */
    public function month_password_modify()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_membermodify_month_password_modify';
        $this->load->event($eventname);

        /**
         * 로그인이 필요한 페이지입니다
         */
        required_user_login();

        $mem_id = (int) $this->member->item('mem_id');

        if ( ! $this->session->userdata('membermodify')) {
            redirect('membermodify');
        }

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        /**
         * Validation 라이브러리를 가져옵니다
         */
        $this->load->library('form_validation');

         if ( ! function_exists('password_hash')) {
            $this->load->helper('password');
        }

        /**
         * 전송된 데이터의 유효성을 체크합니다
         */

        $password_length = $this->cbconfig->item('password_length');
        $view['view']['password_length'] = $password_length;

        $config = array(
            array(
                'field' => 'cur_password',
                'label' => '현재패스워드',
                'rules' => 'trim|required|callback__cur_password_check',
            ),
            array(
                'field' => 'new_password',
                'label' => '새로운패스워드',
                'rules' => 'trim|required|min_length[' . $password_length . ']|callback__mem_password_check',
            ),
            array(
                'field' => 'new_password_re',
                'label' => '새로운패스워드',
                'rules' => 'trim|required|min_length[' . $password_length . ']|matches[new_password]',
            ),
        );
        $this->form_validation->set_rules($config);

        /**
         * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
         * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
         */
        if ($this->form_validation->run() === false) {

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['formrunfalse'] = Events::trigger('formrunfalse', $eventname);

            $view['view']['canonical'] = site_url('membermodify/month_password_modify');

            //$password_description = '비밀번호는 ' . $password_length . '자리 이상이어야 ';
            //$password_description = '영문, 숫자, 특수문자를 반드시 포함하여 '.$password_length.'자 이상 입력해주세요';
            $password_description = '영문, 숫자를 조합하여 6~12자이내로 입력하여 주세요';
            
            $view['view']['info'] = $password_description;

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

            /**
             * 레이아웃을 정의합니다
             */
            $page_title = $this->cbconfig->item('site_meta_title_membermodify');
            $meta_description = $this->cbconfig->item('site_meta_description_membermodify');
            $meta_keywords = $this->cbconfig->item('site_meta_keywords_membermodify');
            $meta_author = $this->cbconfig->item('site_meta_author_membermodify');
            $page_name = $this->cbconfig->item('site_page_name_membermodify');

            $layoutconfig = array(
                'path' => 'mypage',
                'layout' => 'layout',
                'skin' => 'month_password_modify',
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

        } else {

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

            $hash = password_hash($this->input->post('new_password'), PASSWORD_BCRYPT);

            $updatedata = array(
                'mem_password' => $hash,
            );
            $this->Member_model->update($mem_id, $updatedata);
           
            $emailsendlistadmin = array();
            $notesendlistadmin = array();
            $emailsendlistuser = array();
            $notesendlistuser = array();

            $superadminlist = '';
            if ($this->cbconfig->item('send_email_changepw_admin')
                OR $this->cbconfig->item('send_note_changepw_admin')) {
                $mselect = 'mem_id, mem_email, mem_nickname, mem_phone';
                $superadminlist = $this->Member_model->get_superadmin_list($mselect);
            }
            if ($this->cbconfig->item('send_email_changepw_admin') && $superadminlist) {
                foreach ($superadminlist as $key => $value) {
                    $emailsendlistadmin[$value['mem_id']] = $value;
                }
            }
            if (($this->cbconfig->item('send_email_changepw_user') && $this->member->item('mem_receive_email'))
                OR $this->cbconfig->item('send_email_changepw_alluser')) {
                $emailsendlistuser['mem_email'] = $this->member->item('mem_email');
            }
            if ($this->cbconfig->item('send_note_changepw_admin') && $superadminlist) {
                foreach ($superadminlist as $key => $value) {
                    $notesendlistadmin[$value['mem_id']] = $value;
                }
            }
            if ($this->cbconfig->item('send_note_changepw_user')
                && $this->member->item('mem_use_note')) {
                $notesendlistuser['mem_id'] = $mem_id;
            }

            $searchconfig = array(
                '{홈페이지명}',
                '{회사명}',
                '{홈페이지주소}',
                '{회원아이디}',
                '{회원닉네임}',
                '{회원실명}',
                '{회원이메일}',
                '{회원아이피}',
            );
            
            $replaceconfig = array(
                $this->cbconfig->item('site_title'),
                $this->cbconfig->item('company_name'),
                site_url(),
                $this->member->item('mem_userid'),
                $this->member->item('mem_nickname'),
                $this->member->item('mem_username'),
                $this->member->item('mem_email'),
                $this->input->ip_address(),
            );
            $replaceconfig_escape = array(
                html_escape($this->cbconfig->item('site_title')),
                html_escape($this->cbconfig->item('company_name')),
                site_url(),
                html_escape($this->member->item('mem_userid')),
                html_escape($this->member->item('mem_nickname')),
                html_escape($this->member->item('mem_username')),
                html_escape($this->member->item('mem_email')),
                $this->input->ip_address(),
            );
            if ($emailsendlistadmin) {
                $title = str_replace(
                    $searchconfig,
                    $replaceconfig,
                    $this->cbconfig->item('send_email_changepw_admin_title')
                );
                $content = str_replace(
                    $searchconfig,
                    $replaceconfig_escape,
                    $this->cbconfig->item('send_email_changepw_admin_content')
                );
                foreach ($emailsendlistadmin as $akey => $aval) {
                    $this->email->clear(true);
                    $this->email->from($this->cbconfig->item('webmaster_email'), $this->cbconfig->item('webmaster_name'));
                    $this->email->to(element('mem_email', $aval));
                    $this->email->subject($title);
                    $this->email->message($content);
                    $this->email->send();
                }
            }
            if ($emailsendlistuser) {
                $title = str_replace(
                    $searchconfig,
                    $replaceconfig,
                    $this->cbconfig->item('send_email_changepw_user_title')
                );
                $content = str_replace(
                    $searchconfig,
                    $replaceconfig_escape,
                    $this->cbconfig->item('send_email_changepw_user_content')
                );
                $this->email->clear(true);
                $this->email->from($this->cbconfig->item('webmaster_email'), $this->cbconfig->item('webmaster_name'));
                $this->email->to(element('mem_email', $emailsendlistuser));
                $this->email->subject($title);
                $this->email->message($content);
                $this->email->send();
            }
            if ($notesendlistadmin) {
                $title = str_replace(
                    $searchconfig,
                    $replaceconfig,
                    $this->cbconfig->item('send_note_changepw_admin_title')
                );
                $content = str_replace(
                    $searchconfig,
                    $replaceconfig_escape,
                    $this->cbconfig->item('send_note_changepw_admin_content')
                );
                foreach ($notesendlistadmin as $akey => $aval) {
                    $note_result = $this->notelib->send_note(
                        $sender = 0,
                        $receiver = element('mem_id', $aval),
                        $title,
                        $content,
                        1
                    );
                }
            }
            if ($notesendlistuser && element('mem_id', $notesendlistuser)) {
                $title = str_replace(
                    $searchconfig,
                    $replaceconfig,
                    $this->cbconfig->item('send_note_changepw_user_title')
                );
                $content = str_replace(
                    $searchconfig,
                    $replaceconfig_escape,
                    $this->cbconfig->item('send_note_changepw_user_content')
                );
                $note_result = $this->notelib->send_note(
                    $sender = 0,
                    $receiver = element('mem_id', $notesendlistuser),
                    $title,
                    $content,
                    1
                );
            }

            $view['view']['result_message'] = '회원님의 패스워드가 변경되었습니다';

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['before_result_layout'] = Events::trigger('before_result_layout', $eventname);

            /**
             * 레이아웃을 정의합니다
             */
            $page_title = $this->cbconfig->item('site_meta_title_membermodify');
            $meta_description = $this->cbconfig->item('site_meta_description_membermodify');
            $meta_keywords = $this->cbconfig->item('site_meta_keywords_membermodify');
            $meta_author = $this->cbconfig->item('site_meta_author_membermodify');
            $page_name = $this->cbconfig->item('site_page_name_membermodify');

            $layoutconfig = array(
                'path' => 'mypage',
                'layout' => 'layout',
                'skin' => 'member_modify_result',
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
    }




    /**
     * 닉네임체크 함수입니다
     */
    public function _mem_nickname_check($str)
    {
        if ($str === $this->member->item('mem_nickname')) {
            return true;
        }

        $this->load->helper('chkstring');
        if (chkstring($str, _HANGUL_ + _ALPHABETIC_ + _NUMERIC_) === false) {
            $this->form_validation->set_message(
                '_mem_nickname_check',
                '닉네임은 공백없이 한글, 영문, 숫자만 입력 가능합니다'
            );
            return false;
        }

        if (preg_match("/[\,]?{$str}/i", $this->cbconfig->item('denied_nickname_list'))) {
            $this->form_validation->set_message(
                '_mem_nickname_check',
                $str . ' 은(는) 예약어로 사용하실 수 없는 닉네임입니다'
            );
            return false;
        }
        $countwhere = array(
            'mem_nickname' => $str,
        );
        $row = $this->Member_model->count_by($countwhere);

        if ($row > 0) {
            $this->form_validation->set_message(
                '_mem_nickname_check',
                $str . ' 는 이미 다른 회원이 사용하고 있는 닉네임입니다'
            );
            return false;
        }

        $countwhere = array(
            'mni_nickname' => $str,
        );
        $row = $this->Member_nickname_model->count_by($countwhere);

        if ($row > 0) {
            $this->form_validation->set_message(
                '_mem_nickname_check',
                $str . ' 는 이미 다른 회원이 사용하고 있는 닉네임입니다'
            );
            return false;
        }
        return true;
    }


    /**
     * 이메일 체크 함수입니다
     */
    public function _mem_email_check($str)
    {
    	if ($str === $this->member->item('mem_email')) {
    		return true;
    	}
    	
    	// sns 로그인 체크
    	$mem_group = $this->member->item('mem_group');
    	if (empty($mem_group)) {
	    	$countwhere = array(
	    			'mem_email' => $str
	    			//'mem_group' => null,
	    	);
	    	$row = $this->Member_model->count_by($countwhere);
	    	
	    	if ($row > 0) {
	    		$this->form_validation->set_message(
	    				'_mem_email_check',
	    				$str . ' 는 이미 다른 회원이 사용하고 있는 이메일입니다'
	    				);
	    		return false;
	    	}
    	}
    	
        list($emailid, $emaildomain) = explode('@', $str);
        $denied_list = explode(',', $this->cbconfig->item('denied_email_list'));
        $emaildomain = trim($emaildomain);
        $denied_list = array_map('trim', $denied_list);
        if (in_array($emaildomain, $denied_list)) {
            $this->form_validation->set_message(
                '_mem_email_check',
                $emaildomain . ' 은(는) 사용하실 수 없는 이메일입니다'
            );
            return false;
        }
        return true;
    }

    public function check_password() {
        $password = $this->input->post('password', TRUE);
        $result = $this->_cur_password_check($password);
        echo json_encode($result);
    }

    /**
     * 현재 패스워드가 맞는지 체크합니다
     */
    public function _cur_password_check($str)
    {
         if ( ! function_exists('password_hash')) {
            $this->load->helper('password');
        }

        if ( ! $this->member->item('mem_id') OR ! $this->member->item('mem_password')) {
            $this->form_validation->set_message(
                '_cur_password_check',
                '패스워드가 맞지 않습니다'
            );
            return false;
        } elseif ( ! password_verify($str, $this->member->item('mem_password'))) {
            $this->form_validation->set_message(
                '_cur_password_check',
                '패스워드가 맞지 않습니다'
            );
            return false;
        }
        return true;
    }


    /**
     * 새로운 패스워드가 환경설정에 정한 글자수를 채웠는지를 체크합니다
     */
    public function _mem_password_check($str)
    {
        $uppercase = $this->cbconfig->item('password_uppercase_length');
        $lowercase = $this->cbconfig->item('password_lowercase_length');
        $number = $this->cbconfig->item('password_numbers_length');
        $specialchar = $this->cbconfig->item('password_specialchars_length');

        $this->load->helper('chkstring');
        $str_uc = count_uppercase($str);
        $str_lc = count_lowercase($str);
        $str_num = count_numbers($str);
        $str_spc = count_specialchars($str);

    if (($str_uc < $uppercase AND $str_lc < $lowercase) OR $str_num < $number OR $str_spc < $specialchar) {
            $description = '비밀번호는 ';
            if ($str_uc < $uppercase AND $str_lc < $lowercase) {
                $description .= ' ' . $uppercase . '개 이상의 대/소문자';
            }
            
            if ($str_num < $number) {
            	$description .= ' ' . $number . '개 이상의 숫자';
            }
            
            if ($str_spc < $specialchar) {
                $description .= ' ' . $specialchar . '개 이상의 특수문자';
            }
            $description .= '를 포함해야 합니다';

            $this->form_validation->set_message(
                '_mem_password_check',
                $description
            );
            return false;

        }
        
        return true;
    }







    // 개인정보 변경 완료 테스트 페이지
    public function update(){
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_result_layout'] = Events::trigger('before_result_layout', $eventname);

        $page_title = $this->cbconfig->item('site_meta_title_membermodify');
        $meta_description = $this->cbconfig->item('site_meta_description_membermodify');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_membermodify');
        $meta_author = $this->cbconfig->item('site_meta_author_membermodify');
        $page_name = $this->cbconfig->item('site_page_name_membermodify');

        $skin_name = 'member_modify_result';

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


    // 회원탈퇴 테스트 페이지
    public function leaveUser(){
        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_result_layout'] = Events::trigger('before_result_layout', $eventname);

        $page_title = $this->cbconfig->item('site_meta_title_membermodify');
        $meta_description = $this->cbconfig->item('site_meta_description_membermodify');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_membermodify');
        $meta_author = $this->cbconfig->item('site_meta_author_membermodify');
        $page_name = $this->cbconfig->item('site_page_name_membermodify');

        $skin_name = 'memberleave.php';

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
}