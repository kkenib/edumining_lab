<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Register class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 회원 가입과 관련된 controller 입니다.
 */
class Register extends CB_Controller
{
    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Member_nickname', 'Member_auth_email', 'Member_userid');

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
        $this->load->library(array('pagination', 'IMC_Pagination', 'querystring', 'form_validation', 'email'));

        if ( ! function_exists('password_hash')) {
            $this->load->helper('password');
        }
    }


    /**
     * 회원 약관 동의시 작동하는 함수입니다
     */
    public function index()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_register_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        if ($this->member->is_member()
            && ! ($this->member->is_admin() === 'super' && $this->uri->segment(1) === config_item('uri_segment_admin'))) {
            redirect();
        }

        if ($this->cbconfig->item('use_register_block')) {
            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['before_block_layout'] = Events::trigger('before_block_layout', $eventname);

            /**
             * 레이아웃을 정의합니다
             */
            $page_title = $this->cbconfig->item('site_meta_title_register');
            $meta_description = $this->cbconfig->item('site_meta_description_register');
            $meta_keywords = $this->cbconfig->item('site_meta_keywords_register');
            $meta_author = $this->cbconfig->item('site_meta_author_register');
            $page_name = $this->cbconfig->item('site_page_name_register');

            $skin_name = 'register_block';

            $layoutconfig = array(
                'path' => 'register',
                'layout' => 'layout_test',
                'skin' => $skin_name,
                'layout_dir' => $this->cbconfig->item('layout_register'),
                'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_register'),
                'use_sidebar' => $this->cbconfig->item('sidebar_register'),
                'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_register'),
                'skin_dir' => $this->cbconfig->item('skin_register'),
                'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_register'),
                'page_title' => $page_title,
                'meta_description' => $meta_description,
                'meta_keywords' => $meta_keywords,
                'meta_author' => $meta_author,
                'page_name' => $page_name,
            );
            $view['layout'] = $this->managelayout->front($layoutconfig, ''/*$this->cbconfig->get_device_view_type()*/);
            $this->data = $view;
            $this->layout = element('layout_skin_file', element('layout', $view)); // 기본  layout 사용
            $this->view = element('view_skin_file', element('layout', $view));

            return false;
        }

        /**
         * 전송된 데이터의 유효성을 체크합니다
         */
        $config = array(
            array(
                'field' => 'agree',
                'label' => '회원가입약관',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'agree2',
                'label' => '개인정보취급방침',
                'rules' => 'trim|required',
            ),
//			array(
//                'field' => 'agree3',
//                'label' => '개인정보취급방침',
//                'rules' => 'trim|required',
//            ),
        );
        $this->form_validation->set_rules($config);

        /**
         * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
         * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
         */
        if ($this->form_validation->run() === false) {
            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['formrunfalse'] = Events::trigger('formrunfalse', $eventname);

            $this->session->set_userdata('registeragree', '');

            $view['view']['member_register_policy1'] = $this->cbconfig->item('member_register_policy1');
            $view['view']['member_register_policy2'] = $this->cbconfig->item('member_register_policy2');
            $view['view']['canonical'] = site_url('register');

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

            /**
             * 레이아웃을 정의합니다
             */
            $page_title = $this->cbconfig->item('site_meta_title_register');
            $meta_description = $this->cbconfig->item('site_meta_description_register');
            $meta_keywords = $this->cbconfig->item('site_meta_keywords_register');
            $meta_author = $this->cbconfig->item('site_meta_author_register');
            $page_name = $this->cbconfig->item('site_page_name_register');

            $skin_name = 'register';

            $layoutconfig = array(
                'path' => 'register',
                'layout' => 'layout',
                'skin' => 'register',
                'layout_dir' => '../register',
                'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_register'),
                'use_sidebar' => $this->cbconfig->item('sidebar_register'),
                'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_register'),
                'skin_dir' => $this->cbconfig->item('skin_register'),
                'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_register'),
                'page_title' => $page_title,
                'meta_description' => $meta_description,
                'meta_keywords' => $meta_keywords,
                'meta_author' => $meta_author,
                'page_name' => $page_name,
            );
            $view['layout'] = $this->managelayout->front($layoutconfig, ''/*$this->cbconfig->get_device_view_type()*/);
            $this->data = $view;
            $this->layout = element('layout_skin_file', element('layout', $view)); // 기본  layout 사용
            $this->view = element('view_skin_file', element('layout', $view));

        } else {
            /**
             * 유효성 검사를 통과한 경우입니다.
             * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
             */

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

            $this->session->set_userdata('registeragree', '1');

			redirect('register/form');
        }
    }


    /**
     * 회원가입 폼 페이지입니다
     */
    public function form()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_register_form';
        $this->load->event($eventname);

        if ($this->member->is_member() && ! ($this->member->is_admin() === 'super' && $this->uri->segment(1) === config_item('uri_segment_admin'))) {
            redirect();
        }

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        if ($this->cbconfig->item('use_register_block')) {
            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['before_block_layout'] = Events::trigger('before_block_layout', $eventname);

            /**
             * 레이아웃을 정의합니다
             */
            $page_title = $this->cbconfig->item('site_meta_title_register_form');
            $meta_description = $this->cbconfig->item('site_meta_description_register_form');
            $meta_keywords = $this->cbconfig->item('site_meta_keywords_register_form');
            $meta_author = $this->cbconfig->item('site_meta_author_register_form');
            $page_name = $this->cbconfig->item('site_page_name_register_form');

            $layoutconfig = array(
                'path' => 'register',
                'layout' => 'layout',
                'skin' => 'register_block',
                'layout_dir' => '../register',
                'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_register'),
                'use_sidebar' => $this->cbconfig->item('sidebar_register'),
                'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_register'),
                'skin_dir' => $this->cbconfig->item('skin_register'),
                'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_register'),
                'page_title' => $page_title,
                'meta_description' => $meta_description,
                'meta_keywords' => $meta_keywords,
                'meta_author' => $meta_author,
                'page_name' => $page_name,
            );
            $view['layout'] = $this->managelayout->front($layoutconfig, ''/*$this->cbconfig->get_device_view_type()*/);
            $this->data = $view;
            $this->layout = element('layout_skin_file', element('layout', $view)); // 기본  layout 사용
            $this->view = element('view_skin_file', element('layout', $view));
            return false;
        }

        $password_length = $this->cbconfig->item('password_length');
        $email_description = '';
        if ($this->cbconfig->item('use_register_email_auth')) {
            $email_description = '회원가입 후 인증메일이 발송됩니다. 인증메일을 확인하신 후에 사이트 이용이 가능합니다';
        }

        $configbasic = array();

        $nickname_description = '';
        if ($this->cbconfig->item('change_nickname_date')) {
            $nickname_description = '<br />닉네임을 입력하시면 앞으로 '
                . $this->cbconfig->item('change_nickname_date') . '일 이내에는 변경할 수 없습니다';
        }

        $configbasic['mem_userid'] = array(
            'field' => 'mem_userid',
            'label' => '아이디',
            'rules' => 'trim|required|alphanumunder|min_length[3]|max_length[20]|is_unique[member_userid.mem_userid]|callback__mem_userid_check',
            'description' => '영문, 숫자 포함 최소 3자 이상 입력해주세요',
        );

        $configbasic['mem_password'] = array(
            'field' => 'mem_password',
            'label' => '패스워드',
            'rules' => 'trim|required|min_length[' . $password_length . ']|max_length[12]|callback__mem_password_check',
            'description' => '영문, 숫자를 조합하여 6~12자이내로 입력하여 주세요',
        );
        
        $configbasic['mem_password_re'] = array(
            'field' => 'mem_password_re',
            'label' => '패스워드 확인',
            'rules' => 'trim|required|min_length[' . $password_length . ']|max_length[12]|matches[mem_password]',
        );
        
        $configbasic['mem_username'] = array(
            'field' => 'mem_username',
            'label' => '이름',
            'rules' => 'trim|min_length[2]|max_length[20]',
        );
        
        $configbasic['mem_nickname'] = array(
            'field' => 'mem_nickname',
            'label' => '닉네임',
            'rules' => 'trim|required|min_length[2]|max_length[20]|callback__mem_nickname_check',
            //'description' => '공백없이 한글, 영문, 숫자만 입력 가능 2글자 이상' . $nickname_description,
            //'description' => '한글, 영문, 숫자 포함 최소 2자 이상 입력해주세요',
            'description' => '최소 2자 이상 입력해주세요(특수문자 제외)',
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
        
        $configbasic['mem_email'] = array(
            'field' => 'mem_email',
            'label' => '이메일',
            'rules' => 'trim|required|valid_email|max_length[50]|is_unique[member.mem_email]|callback__mem_email_check',
            'description' => $email_description,
        );
        
//        $configbasic['mem_phone'] = array(
//            'field' => 'mem_phone',
//            'label' => '전화번호',
//            'rules' => 'trim|valid_phone',
//            'description' => '\'-\'를 포함하여 입력해주세요',
//        );
        
        $configbasic['mem_birthday'] = array(
            'field' => 'mem_birthday',
            'label' => '생년월일',
            'rules' => 'trim|exact_length[10]',
        );

        $configbasic['mem_job_phone'] = array(
            'field' => 'mem_job_phone',
            'label' => '회사/학교 전화번호',
            'rules' => 'trim|valid_phone',
            'description' => '\'-\'를 포함하여 입력해주세요',
        );
        
        $configbasic['mem_school_name'] = array(
        	'field' => 'mem_school_name',
        	'label' => '학교',
        	'rules' => 'trim|min_length[2]|max_length[30]',
        );
        
        $configbasic['mem_division_nm'] = array(
        		'field' => 'mem_division_nm',
        		'label' => '학년',
        		'rules' => 'trim|min_length[1]',
        );
        
        if ($this->member->is_admin() === false && ! $this->session->userdata('registeragree')) {
            $this->session->set_flashdata(
                'message',
                '회원가입약관동의와 개인정보취급방침동의후 회원가입이 가능합니다'
            );
            redirect('register');
        }

        $registerform = $this->cbconfig->item('registerform');
        $form = json_decode($registerform, true);

        $config = array();
        if ($form && is_array($form)) {
            foreach ($form as $key => $value) {
                if ( ! element('use', $value)) {
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
                        if ($key === 'mem_password') {
                            $config[] = $configbasic['mem_password_re'];
                        }
                    }
                } else {
                    $required = element('required', $value) ? '|required' : '';
                    if (element('field_type', $value) === 'checkbox') {
                        $config[] = array(
                            'field' => element('field_name', $value) . '[]',
                            'label' => element('display_name', $value),
                            'rules' => 'trim' . $required,
                        );
                    } else {
                        $config[] = array(
                            'field' => element('field_name', $value),
                            'label' => element('display_name', $value),
                            'rules' => 'trim' . $required,
                        );
                    }
                }
            }
        }

        // if ($this->cbconfig->item('use_recaptcha')) {
        //     $config[] = array(
        //         'field' => 'g-recaptcha-response',
        //         'label' => '자동등록방지문자',
        //         'rules' => 'trim|required|callback__check_recaptcha',
        //     );
        // } else {
        //     $config[] = array(
        //         'field' => 'captcha_key',
        //         'label' => '자동등록방지문자',
        //         'rules' => 'trim|required|callback__check_captcha',
        //     );
        // }
        $this->form_validation->set_rules($config);

        $form_validation = $this->form_validation->run();
        $file_error = '';
        $file_error2 = '';

        /**
         * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
         * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
         */
        if ($form_validation === false OR $file_error !== '' OR $file_error2 !== '') {
            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['formrunfalse'] = Events::trigger('formrunfalse', $eventname);

            $html_content = array();

            $k = 0;
            if ($form && is_array($form)) {

                foreach ($form as $key => $value) {
                    if ( ! element('use', $value)) {
                        continue;
                    }

					if (element('field_name', $value) === 'mem_nickname') {
						continue;
					}

                    $required = element('required', $value) ? 'required' : '';
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
                            $html_content[$k]['input'] .= '<input type="text" id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="'.$input_tag_classes.' datepicker" value="' . set_value(element('field_name', $value)) . '" readonly="readonly" ' . $required . ' />';
                        } elseif (element('field_type', $value) === 'phone') {
                            $html_content[$k]['input'] .= '<input type="text" id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="'.$input_tag_classes.' validphone" value="' . set_value(element('field_name', $value)) . '" ' . $required . ' />';
                        } else if(element('field_type', $value) === 'email') {
                            $email_tail_list = $this->email->get_email_list();
                            foreach($email_tail_list as $row) {
                                $option_email_type .= '<option value="'.$row.'">'.$row.'</option>';
                            }
                            $html_content[$k]['input'] .= '<input type="' . element('field_type', $value) . '" id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="'.$input_tag_classes.'" value="' . set_value(element('field_name', $value)) . '" ' . $required . '/> <select id="email_address_type" name="email_address_type">
                            <option value="direct">직접입력</option>
                            '.$option_email_type.'
                            </select>';
                        } else {
                            $html_content[$k]['input'] .= '<input type="' . element('field_type', $value) . '" id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="'.$input_tag_classes.'" value="' . set_value(element('field_name', $value)) . '" ' . $required . '/>';
                        }
                    } elseif (element('field_type', $value) === 'textarea') {
                        $html_content[$k]['input'] .= '<textarea id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="'.$input_tag_classes.'" ' . $required . '>' . set_value(element('field_name', $value)) . '</textarea>';
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
                                $radiovalue = (element('field_name', $value) === 'mem_sex') ? $okey : $oval;
                                $html_content[$k]['input'] .= '<label for="' . element('field_name', $value) . '_' . $i . '"><input type="radio" name="' . element('field_name', $value) . '" id="' . element('field_name', $value) . '_' . $i . '" value="' . $radiovalue . '" ' . set_radio(element('field_name', $value), $radiovalue) . ' /> ' . $oval . ' </label> ';
                                $i++;
                            }
                        }
                        $html_content[$k]['input'] .= '</div>';
                    } elseif (element('field_type', $value) === 'checkbox') {
                        $html_content[$k]['input'] .= '<div class="checkbox">';
                        $options = explode("\n", element('options', $value));
                        $i =1;
                        if ($options) {
                            foreach ($options as $okey => $oval) {
                                $html_content[$k]['input'] .= '<label for="' . element('field_name', $value) . '_' . $i . '"><input type="checkbox" name="' . element('field_name', $value) . '[]" id="' . element('field_name', $value) . '_' . $i . '" value="' . $oval . '" ' . set_checkbox(element('field_name', $value), $oval) . ' /> ' . $oval . ' </label> ';
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
                                $html_content[$k]['input'] .= '<option value="' . $oval . '" ' . set_select(element('field_name', $value), $oval) . ' >' . $oval . '</option> ';
                            }
                        }
                        $html_content[$k]['input'] .= '</select>';
                        $html_content[$k]['input'] .= '</div>';
                    } elseif (element('field_name', $value) === 'mem_address') {
                        $html_content[$k]['input'] .= '
                                    <button type="button" class="btn_Sgray mb_5" style="margin-top:0px;" onclick="win_zip(\'fregisterform\', \'mem_zipcode\', \'mem_address1\', \'mem_address2\', \'mem_address3\', \'mem_address4\');">주소검색</button>
                                    <input type="text" name="mem_zipcode" value="' . set_value('mem_zipcode') . '" id="mem_zipcode" size="7" maxlength="7" class="input_box w_150 mb_5" ' . $required . '/>
                                    <div class="addr-line mt10">
                                    <input type="text" name="mem_address" value="' . set_value('mem_address') . '" id="mem_address" class="input_box mb_5" style="width:90%" placeholder="기본주소" ' . $required . ' />
                                    </div>
                            ';
                    } elseif (element('field_name', $value) === 'mem_password') {
                        $html_content[$k]['input'] .= '<input type="' . element('field_type', $value) . '" id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="'.$input_tag_classes.'" minlength="' . $password_length . '" />';
                    }

                    $html_content[$k]['description'] = '';
                    if (isset($configbasic[$value['field_name']]['description']) && $configbasic[$value['field_name']]['description']) {
                        $html_content[$k]['description'] = $configbasic[$value['field_name']]['description'];
                    }
                    if (element('field_name', $value) === 'mem_password') {
                        $k++;
                        $html_content[$k]['field_name'] = 'mem_password_re';
                        $html_content[$k]['display_name'] = '비밀번호 확인';
                        $html_content[$k]['input'] = '<input type="password" id="mem_password_re" name="mem_password_re" class="'.$input_tag_classes.'" minlength="' . $password_length . '" />';
                    }

					if(element('field_name', $value) === 'mem_username') {
						$html_content[$k]['input'] .= '<input type="hidden" id="mem_nickname" name="mem_nickname"/>';
					}

                    $k++;
                }
            }

            $view['view']['html_content'] = $html_content;
            $view['view']['canonical'] = site_url('register/form');

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

            /**
             * 레이아웃을 정의합니다
             */
            $page_title = $this->cbconfig->item('site_meta_title_register_form');
            $meta_description = $this->cbconfig->item('site_meta_description_register_form');
            $meta_keywords = $this->cbconfig->item('site_meta_keywords_register_form');
            $meta_author = $this->cbconfig->item('site_meta_author_register_form');
            $page_name = $this->cbconfig->item('site_page_name_register_form');

            $skin_name = 'register_form';

            $layoutconfig = array(
                'path' => 'register',
                'layout' => 'layout',
                'skin' => $skin_name,
                'layout_dir' => '../register',
                'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_register'),
                'use_sidebar' => $this->cbconfig->item('sidebar_register'),
                'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_register'),
                'skin_dir' => $this->cbconfig->item('skin_register'),
                'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_register'),
                'page_title' => $page_title,
                'meta_description' => $meta_description,
                'meta_keywords' => $meta_keywords,
                'meta_author' => $meta_author,
                'page_name' => $page_name,
            );
            $view['layout'] = $this->managelayout->front($layoutconfig, ''/*$this->cbconfig->get_device_view_type()*/);
            $this->data = $view;
            $this->layout = element('layout_skin_file', element('layout', $view)); // 기본  layout 사용
            $this->view = element('view_skin_file', element('layout', $view));

        } else {

            /**
             * 유효성 검사를 통과한 경우입니다.
             * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
             */
            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

            $mem_level = (int) $this->cbconfig->item('register_level');
            $insertdata = array();

            $insertdata['mem_userid'] = $this->input->post('mem_userid');
            $insertdata['mem_email'] = $this->input->post('mem_email');
            $insertdata['mem_password'] = password_hash($this->input->post('mem_password'), PASSWORD_BCRYPT);
            $insertdata['mem_nickname'] = $this->input->post('mem_nickname');
            $insertdata['mem_level'] = $mem_level;
//            $insertdata['mem_agency'] = $this->input->post('mem_agency');
            $insertdata['mem_school_name'] = $this->input->post('mem_school_name');
            $insertdata['mem_school_code'] = $this->input->post('mem_school_code');
            $insertdata['mem_division_cd'] = $this->input->post('mem_division_nm');
            $insertdata['mem_division_nm'] = $this->input->post('mem_division_nm');
            $insertdata['mem_std_count'] = $this->input->post('mem_std_count');
			$insertdata['mem_agree'] = 1;
            $insertdata['mem_std_count'] = 0;

			$uploads_dir = 'uploads/images';

			if(isset($_FILES['mem_photo'])) {
				$name = $_FILES['mem_photo']['name'];
    			$type = $_FILES['mem_photo']['type'];
    			$size = $_FILES['mem_photo']['size'];
				$ext = substr(strrchr($name, '.'), 1); 
				$today = date("YmdHis").rand();
    			move_uploaded_file($_FILES['mem_photo']['tmp_name'], "$uploads_dir/$today"."."."$ext");
				$insertdata['mem_photo'] = "$name";
				$insertdata['mem_photo_tmp'] = "$uploads_dir/$today"."."."$ext";
			}
			
            if (isset($form['mem_username']['use']) && $form['mem_username']['use']) {
                $insertdata['mem_username'] = $this->input->post('mem_username', null, '');
            }
//            if (isset($form['mem_phone']['use']) && $form['mem_phone']['use']) {
//                $insertdata['mem_phone'] = $this->input->post('mem_phone', null, '');
//            }
            if (isset($form['mem_birthday']['use']) && $form['mem_birthday']['use']) {
                $insertdata['mem_birthday'] = $this->input->post('mem_birthday', null, '');
            }
            if (isset($form['mem_sex']['use']) && $form['mem_sex']['use']) {
                $insertdata['mem_sex'] = $this->input->post('mem_sex', null, '');
            }
            if (isset($form['mem_address']['use']) && $form['mem_address']['use']) {
                $insertdata['mem_zipcode'] = $this->input->post('mem_zipcode', null, '');
                $insertdata['mem_address'] = $this->input->post('mem_address', null, '');
            }
            $insertdata['mem_register_datetime'] = cdate('Y-m-d H:i:s');
            $insertdata['mem_register_ip'] = $this->input->ip_address();
            
            if ($this->cbconfig->item('use_register_email_auth')) {
                $insertdata['mem_email_cert'] = 0;
            } else {
                $insertdata['mem_email_cert'] = 1;
            }

            $mem_id = $this->Member_model->insert($insertdata);
			
            $useridinsertdata = array(
                'mem_id' => $mem_id,
                'mem_userid' => $this->input->post('mem_userid'),
            );
            $this->Member_userid_model->insert($useridinsertdata);

            $nickinsert = array(
                'mem_id' => $mem_id,
                'mni_nickname' => $this->input->post('mem_nickname'),
                'mni_start_datetime' => cdate('Y-m-d H:i:s'),
            );
            $this->Member_nickname_model->insert($nickinsert);

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
            $mem_userid = $this->input->post('mem_userid', null, '');
            $mem_nickname = $this->input->post('mem_nickname', null, '');
            $mem_username = $this->input->post('mem_username', null, '');
            $mem_email = $this->input->post('mem_email', null, '');
           
            $replaceconfig = array(
                $this->cbconfig->item('site_title'),
                $this->cbconfig->item('company_name'),
                site_url(),
                $mem_userid,
                $mem_nickname,
                $mem_username,
                $mem_email,
                $this->input->ip_address(),
            );
            $replaceconfig_escape = array(
                html_escape($this->cbconfig->item('site_title')),
                html_escape($this->cbconfig->item('company_name')),
                site_url(),
                html_escape($mem_userid),
                html_escape($mem_nickname),
                html_escape($mem_username),
                html_escape($mem_email),
                $this->input->ip_address(),
            );

            if ( ! $this->cbconfig->item('use_register_email_auth')) {
                if (($this->cbconfig->item('send_email_register_user') && $this->input->post('mem_receive_email'))
                    OR $this->cbconfig->item('send_email_register_alluser')) {
                    $title = str_replace(
                        $searchconfig,
                        $replaceconfig,
                        $this->cbconfig->item('send_email_register_user_title')
                    );
                    $content = str_replace(
                        $searchconfig,
                        $replaceconfig_escape,
                        $this->cbconfig->item('send_email_register_user_content')
                    );
                    $this->email->from($this->cbconfig->item('webmaster_email'), $this->cbconfig->item('webmaster_name'));
                    $this->email->to($this->input->post('mem_email'));
                    $this->email->subject($title);
                    $this->email->message($content);
                    $this->email->send();
                }
            } else {
                $vericode = array('$', '/', '.');
                $verificationcode = str_replace(
                    $vericode,
                    '',
                    password_hash($mem_id . '-' . $this->input->post('mem_email') . '-' . random_string('alnum', 10), PASSWORD_BCRYPT)
                );

                $beforeauthdata = array(
                    'mem_id' => $mem_id,
                    'mae_type' => 1,
                );
                $this->Member_auth_email_model->delete_where($beforeauthdata);
                $authdata = array(
                    'mem_id' => $mem_id,
                    'mae_key' => $verificationcode,
                    'mae_type' => 1,
                    'mae_generate_datetime' => cdate('Y-m-d H:i:s'),
                );
                $this->Member_auth_email_model->insert($authdata);

                $verify_url = site_url('verify/confirmemail?user=' . $this->input->post('mem_userid') . '&code=' . $verificationcode);

                $title = str_replace(
                    $searchconfig,
                    $replaceconfig,
                    $this->cbconfig->item('send_email_register_user_verifytitle')
                );
                $content = str_replace(
                    $searchconfig,
                    $replaceconfig_escape,
                    $this->cbconfig->item('send_email_register_user_verifycontent')
                );

                $title = str_replace('{메일인증주소}', $verify_url, $title);
                $content = str_replace('{메일인증주소}', $verify_url, $content);

                $this->email->from($this->cbconfig->item('webmaster_email'), $this->cbconfig->item('webmaster_name'));
                $this->email->to($this->input->post('mem_email'));
                $this->email->subject($title);
                $this->email->message($content);
                $this->email->send();

                $email_auth_message = $this->input->post('mem_email') . '로 인증메일이 발송되었습니다. <br />발송된 인증메일을 확인하신 후에 사이트 이용이 가능합니다';
                $this->session->set_flashdata(
                    'email_auth_message',
                    $email_auth_message
                );
            }

            $emailsendlistadmin = array();

            $superadminlist = '';
            if ($this->cbconfig->item('send_email_register_admin')
                OR $this->cbconfig->item('send_note_register_admin')) {
                $mselect = 'mem_id, mem_email, mem_nickname, mem_phone';
                $superadminlist = $this->Member_model->get_superadmin_list($mselect);
            }

            if ($this->cbconfig->item('send_email_register_admin') && $superadminlist) {
                foreach ($superadminlist as $key => $value) {
                    $emailsendlistadmin[$value['mem_id']] = $value;
                }
            }

            if ($emailsendlistadmin) {
                $title = str_replace(
                    $searchconfig,
                    $replaceconfig,
                    $this->cbconfig->item('send_email_register_admin_title')
                );
                $content = str_replace(
                    $searchconfig,
                    $replaceconfig_escape,
                    $this->cbconfig->item('send_email_register_admin_content')
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
           
		   /*
            $this->session->set_flashdata(
                'nickname',
                $this->input->post('mem_nickname')
            );

            if ( ! $this->cbconfig->item('use_register_email_auth')) {
                $this->session->set_userdata(
                    'mem_id',
                    $mem_id
                );
            }
			*/
            redirect('register/result');
        }
    }

    /**
     * 회원가입 결과 페이지입니다
     */
    public function result()
    {    	
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_register_result';
        $this->load->event($eventname);
        
        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        //$this->session->keep_flashdata('nickname');
        //$this->session->keep_flashdata('email_auth_message');

        //if ( ! $this->session->flashdata('nickname')) {
            // redirect();
        //}

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_register_result');
        $meta_description = $this->cbconfig->item('site_meta_description_register_result');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_register_result');
        $meta_author = $this->cbconfig->item('site_meta_author_register_result');
        $page_name = $this->cbconfig->item('site_page_name_register_result');

        $skin_name = 'register_result';

        $layoutconfig = array(
            'path' => 'register',
            'layout' => 'layout',
            'skin' => $skin_name,
            'layout_dir' => '../register',
            'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_register'),
            'use_sidebar' => $this->cbconfig->item('sidebar_register'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_register'),
            'skin_dir' => $this->cbconfig->item('skin_register'),
            'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_register'),
            'page_title' => $page_title,
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'meta_author' => $meta_author,
            'page_name' => $page_name,
        );
        $view['layout'] = $this->managelayout->front($layoutconfig, ''/*$this->cbconfig->get_device_view_type()*/);
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view)); // 기본  layout 사용
        $this->view = element('view_skin_file', element('layout', $view));
    }

	/**
     * 동의서 받기위한 페이지
     */
    public function register_chk()
    {    	
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_register_chk';
        $this->load->event($eventname);
        
        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_register_result');
        $meta_description = $this->cbconfig->item('site_meta_description_register_result');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_register_result');
        $meta_author = $this->cbconfig->item('site_meta_author_register_result');
        $page_name = $this->cbconfig->item('site_page_name_register_result');

        $skin_name = 'register_chk';

        $layoutconfig = array(
            'path' => 'register',
            'layout' => 'layout',
            'skin' => $skin_name,
            'layout_dir' => '../register',
            'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_register'),
            'use_sidebar' => $this->cbconfig->item('sidebar_register'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_register'),
            'skin_dir' => $this->cbconfig->item('skin_register'),
            'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_register'),
            'page_title' => $page_title,
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'meta_author' => $meta_author,
            'page_name' => $page_name,
        );
        $view['layout'] = $this->managelayout->front($layoutconfig, ''/*$this->cbconfig->get_device_view_type()*/);
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view)); // 기본  layout 사용
        $this->view = element('view_skin_file', element('layout', $view));
    }


    public function ajax_userid_check()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_register_ajax_userid_check';
        $this->load->event($eventname);

        $result = array();
        $this->output->set_content_type('application/json');

        // 이벤트가 존재하면 실행합니다
        Events::trigger('before', $eventname);

        $userid = trim($this->input->post('userid'));
        if (empty($userid)) {
            $result = array(
                'result' => 'no',
                'reason' => '아이디값이 넘어오지 않았습니다',
            );
            exit(json_encode($result));
        }

        if ( ! preg_match("/^([a-z0-9_])+$/i", $userid)) {
            $result = array(
                'result' => 'no',
                'reason' => '아이디는 숫자, 알파벳, _ 만 입력가능합니다',
            );
            exit(json_encode($result));
        }

        $where = array(
            'mem_userid' => $userid,
        );
        $count = $this->Member_userid_model->count_by($where);
        if ($count > 0) {
            $result = array(
                'result' => 'no',
                'reason' => '이미 사용중인 아이디입니다',
            );
            exit(json_encode($result));
        }

        if ($this->_mem_userid_check($userid) === false) {
            $result = array(
                'result' => 'no',
                'reason' => $userid . '은(는) 예약어로 사용하실 수 없는 회원아이디입니다',
            );
            exit(json_encode($result));
        }

        // 이벤트가 존재하면 실행합니다
        Events::trigger('after', $eventname);

        $result = array(
            'result' => 'available',
            'reason' => '사용 가능한 아이디입니다',
        );
        exit(json_encode($result));
    }


    public function ajax_email_check()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_register_ajax_email_check';
        $this->load->event($eventname);

        $result = array();
        $this->output->set_content_type('application/json');

        // 이벤트가 존재하면 실행합니다
        Events::trigger('before', $eventname);

        $email = trim($this->input->post('email'));
        if (empty($email)) {
            $result = array(
                'result' => 'no',
                'reason' => '이메일값이 넘어오지 않았습니다',
            );
            exit(json_encode($result));
        }

        if ($this->member->item('mem_email')
            && $this->member->item('mem_email') === $email) {
            $result = array(
                'result' => 'available',
                'reason' => '사용 가능한 이메일입니다',
            );
            exit(json_encode($result));
        }

        $where = array(
            'mem_email' => $email,
        );
        $count = $this->Member_model->count_by($where);
        if ($count > 0) {
            $result = array(
                'result' => 'no',
                'reason' => '이미 사용중인 이메일입니다',
            );
            exit(json_encode($result));
        }

        if ($this->_mem_email_check($email) === false) {
            $result = array(
                'result' => 'no',
                'reason' => $email . '은(는) 예약어로 사용하실 수 없는 이메일입니다',
            );
            exit(json_encode($result));
        }

        // 이벤트가 존재하면 실행합니다
        Events::trigger('before', $eventname);

        $result = array(
            'result' => 'available',
            'reason' => '사용 가능한 이메일입니다',
        );
        exit(json_encode($result));
    }


    public function ajax_password_check()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_register_ajax_password_check';
        $this->load->event($eventname);

        $result = array();
        $this->output->set_content_type('application/json');

        // 이벤트가 존재하면 실행합니다
        Events::trigger('before', $eventname);

        $password = trim($this->input->post('password'));
        if (empty($password)) {
            $result = array(
                'result' => 'no',
                'reason' => '패스워드값이 넘어오지 않았습니다',
            );
            exit(json_encode($result));
        }

        if ($this->_mem_password_check($password) === false) {
            $result = array(
                'result' => 'no',
                'reason' => '패스워드는 최소 1개 이상의 숫자를 포함해야 합니다',
            );
            exit(json_encode($result));
        }

        $result = array(
            'result' => 'available',
            'reason' => '사용 가능한 패스워드입니다',
        );
        exit(json_encode($result));
    }


    public function ajax_nickname_check()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_register_ajax_nickname_check';
        $this->load->event($eventname);

        $result = array();
        $this->output->set_content_type('application/json');

        // 이벤트가 존재하면 실행합니다
        Events::trigger('before', $eventname);

        $nickname = trim($this->input->post('nickname'));
        if (empty($nickname)) {
            $result = array(
                'result' => 'no',
                'reason' => '닉네임값이 넘어오지 않았습니다',
            );
            exit(json_encode($result));
        }

        if ($this->member->item('mem_nickname')
            && $this->member->item('mem_nickname') === $nickname) {
            $result = array(
                'result' => 'available',
                'reason' => '사용 가능한 닉네임입니다',
            );
            exit(json_encode($result));
        }

        $where = array(
            'mem_nickname' => $nickname,
        );
        $count = $this->Member_model->count_by($where);
        if ($count > 0) {
            $result = array(
                'result' => 'no',
                'reason' => '이미 사용중인 닉네임입니다',
            );
            exit(json_encode($result));
        }

        if ($this->_mem_nickname_check($nickname) === false) {
            $result = array(
                'result' => 'no',
                'reason' => '이미 사용중인 닉네임입니다',
            );
            exit(json_encode($result));
        }

        $result = array(
            'result' => 'available',
            'reason' => '사용 가능한 닉네임입니다',
        );
        exit(json_encode($result));
    }


    /**
     * 회원가입시 회원아이디를 체크하는 함수입니다
     */
    public function _mem_userid_check($str)
    {
        if (preg_match("/[\,]?{$str}/i", $this->cbconfig->item('denied_userid_list'))) {
            $this->form_validation->set_message(
                '_mem_userid_check',
                $str . ' 은(는) 예약어로 사용하실 수 없는 회원아이디입니다'
            );
            return false;
        }

        return true;
    }


    /**
     * 회원가입시 닉네임을 체크하는 함수입니다
     */
    public function _mem_nickname_check($str)
    {
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
     * 회원가입시 이메일을 체크하는 함수입니다
     */
    public function _mem_email_check($str)
    {
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

    
    /**
     * 회원가입시 captcha 체크하는 함수입니다
     */
    public function _check_captcha($str)
    {
        $captcha = $this->session->userdata('captcha');
        if ( ! is_array($captcha) OR ! element('word', $captcha) OR strtolower(element('word', $captcha)) !== strtolower($str)) {
            $this->session->unset_userdata('captcha');
            $this->form_validation->set_message(
                '_check_captcha',
                '자동등록방지코드가 잘못되었습니다'
            );
            return false;
        }
        return true;
    }


    /**
     * 회원가입시 recaptcha 체크하는 함수입니다
     */
    public function _check_recaptcha($str)
    {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array(
            'secret' => $this->cbconfig->item('recaptcha_secret'),
            'response' => $str,
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, sizeof($data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        $obj = json_decode($result);

        if ((string) $obj->success !== '1') {
            $this->form_validation->set_message(
                '_check_recaptcha',
                '자동등록방지코드가 잘못되었습니다'
            );
            return false;
        }

        return true;
    }


    /**
     * 회원가입시 패스워드가 올바른 규약에 의해 입력되었는지를 체크하는 함수입니다
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

    public function mem_userid_check()
    {
		$str = $this->input->get('id');

        $this->load->helper('chkstring');

        $status = 'success';
        $code = '';
        
        // E01 아이디는 최소 3글자 이상 가능합니다
        if (strlen($str) < 3) {
        	$status = 'error';
        	$code =  'E01';
        	$message = "아이디는 최소 3글자 이상 가능합니다.";
        }
        
        // E02 아이디는 공백없이 영문, 숫자, 언더바(_)만 입력 가능합니다
        if (chkstring($str, _ALPHABETIC_ + _NUMERIC_) === false) {
        	$status = 'error';
        	$code =  'E02';
        	$message = "아이디는 공백없이  영문, 숫자, 언더바(_)만 입력 가능합니다.";
        }
        
		// E03 예약어로 사용하실 수 없는 아이디입니다
        if (preg_match("/[\,]?{$str}/i", $this->cbconfig->item('denied_userid_list'))) {
        	$status = 'error';
			$code = 'E03';
			$message = "예약어로 사용할 수 없는 아이디입니다.";
        }

		// E03 다른 회원이 사용하고 있는 닉네임입니다
        $countwhere = array(
            'mem_userid' => $str,
        );

        $row = $this->Member_model->count_by($countwhere);

        if ($code === '') {
	        if ($row > 0) {
	        	$status = 'error';
				$code = 'E04';
				$message = "이미 생성된 아이디입니다.";
	        } else {
	        	$status = 'success';
				$code = 'S00';
				$message = "사용 가능한 아이디입니다.";
			}
        }
        
		$result = array(
			'status' => $status,
			'code' => $code,
			'message' => $message
		);

		echo json_encode($result);
    }


	public function policy1()
    {
		$page_title = $this->cbconfig->item('site_meta_title_register');
		$meta_description = $this->cbconfig->item('site_meta_description_register');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_register');
		$meta_author = $this->cbconfig->item('site_meta_author_register');
		$page_name = $this->cbconfig->item('site_page_name_register');

		$skin_name = 'policy1';

		$data = array();
        $data['title_name'] = "이용약관";
		$view['view']['data'] = $data;

		$layoutconfig = array(
			'path' => 'register',
			'layout' => 'no_layout',
			'skin' => $skin_name,
			'layout_dir' => $this->cbconfig->item('layout_main'),
            'mobile_layout_dir' => $this->cbconfig->item('layout_main'),
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => $this->cbconfig->item('skin_main'),
            'mobile_skin_dir' => $this->cbconfig->item('skin_main'),
            'page_title' => $page_title,
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'meta_author' => $meta_author,
            'page_name' => $page_name,
		);
		$view['layout'] = $this->managelayout->front($layoutconfig, ''/*$this->cbconfig->get_device_view_type()*/);
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view)); // 기본  layout 사용
		$this->view = element('view_skin_file', element('layout', $view));
	}

	public function policy2()
    {
		$page_title = $this->cbconfig->item('site_meta_title_register');
		$meta_description = $this->cbconfig->item('site_meta_description_register');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_register');
		$meta_author = $this->cbconfig->item('site_meta_author_register');
		$page_name = $this->cbconfig->item('site_page_name_register');

		$skin_name = 'policy2';

        $page_title = "텍스트 마이닝 - 데이터 관리";

		$data = array();
        $data['title_name'] = "개인정보 취급방침";
		$view['view']['data'] = $data;

		$layoutconfig = array(
			'path' => 'register',
			'layout' => 'no_layout',
			'skin' => $skin_name,
			'layout_dir' => $this->cbconfig->item('layout_main'),
            'mobile_layout_dir' => $this->cbconfig->item('layout_main'),
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => $this->cbconfig->item('skin_main'),
            'mobile_skin_dir' => $this->cbconfig->item('skin_main'),
            'page_title' => $page_title,
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'meta_author' => $meta_author,
            'page_name' => $page_name,
		);
		$view['layout'] = $this->managelayout->front($layoutconfig, ''/*$this->cbconfig->get_device_view_type()*/);
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view)); // 기본  layout 사용
		$this->view = element('view_skin_file', element('layout', $view));
	}

	public function policy3()
    {
		$page_title = $this->cbconfig->item('site_meta_title_register');
		$meta_description = $this->cbconfig->item('site_meta_description_register');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_register');
		$meta_author = $this->cbconfig->item('site_meta_author_register');
		$page_name = $this->cbconfig->item('site_page_name_register');

		$skin_name = 'policy3';

		$data = array();
        $data['title_name'] = "데이터 수집방침";
		$view['view']['data'] = $data;

		$layoutconfig = array(
			'path' => 'register',
			'layout' => 'no_layout',
			'skin' => $skin_name,
			'layout_dir' => $this->cbconfig->item('layout_main'),
            'mobile_layout_dir' => $this->cbconfig->item('layout_main'),
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => $this->cbconfig->item('skin_main'),
            'mobile_skin_dir' => $this->cbconfig->item('skin_main'),
            'page_title' => $page_title,
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'meta_author' => $meta_author,
            'page_name' => $page_name,
		);
		$view['layout'] = $this->managelayout->front($layoutconfig, ''/*$this->cbconfig->get_device_view_type()*/);
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view)); // 기본  layout 사용
		$this->view = element('view_skin_file', element('layout', $view));
	}

	public function policy1_pop()
    {
		$page_title = $this->cbconfig->item('site_meta_title_register');
		$meta_description = $this->cbconfig->item('site_meta_description_register');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_register');
		$meta_author = $this->cbconfig->item('site_meta_author_register');
		$page_name = $this->cbconfig->item('site_page_name_register');

		$skin_name = 'policy1';

		$data = array();
        $data['title_name'] = "이용약관";
		$view['view']['data'] = $data;

		$layoutconfig = array(
			'path' => 'register',
			'layout' => 'layout_popup',
			'skin' => $skin_name,
			'layout_dir' => $this->cbconfig->item('layout_main'),
            'mobile_layout_dir' => $this->cbconfig->item('layout_main'),
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => $this->cbconfig->item('skin_main'),
            'mobile_skin_dir' => $this->cbconfig->item('skin_main'),
            'page_title' => $page_title,
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'meta_author' => $meta_author,
            'page_name' => $page_name,
		);
		$view['layout'] = $this->managelayout->front($layoutconfig, ''/*$this->cbconfig->get_device_view_type()*/);
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view)); // 기본  layout 사용
		$this->view = element('view_skin_file', element('layout', $view));
	}

	public function policy2_pop()
    {
		$page_title = $this->cbconfig->item('site_meta_title_register');
		$meta_description = $this->cbconfig->item('site_meta_description_register');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_register');
		$meta_author = $this->cbconfig->item('site_meta_author_register');
		$page_name = $this->cbconfig->item('site_page_name_register');

		$skin_name = 'policy2';

		$data = array();
        $data['title_name'] = "개인정보 취급방침";
		$view['view']['data'] = $data;

		$layoutconfig = array(
			'path' => 'register',
			'layout' => 'layout_popup',
			'skin' => $skin_name,
			'layout_dir' => $this->cbconfig->item('layout_main'),
            'mobile_layout_dir' => $this->cbconfig->item('layout_main'),
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => $this->cbconfig->item('skin_main'),
            'mobile_skin_dir' => $this->cbconfig->item('skin_main'),
            'page_title' => $page_title,
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'meta_author' => $meta_author,
            'page_name' => $page_name,
		);
		$view['layout'] = $this->managelayout->front($layoutconfig, ''/*$this->cbconfig->get_device_view_type()*/);
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view)); // 기본  layout 사용
		$this->view = element('view_skin_file', element('layout', $view));
	}

	public function policy3_pop()
    {
		$page_title = $this->cbconfig->item('site_meta_title_register');
		$meta_description = $this->cbconfig->item('site_meta_description_register');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_register');
		$meta_author = $this->cbconfig->item('site_meta_author_register');
		$page_name = $this->cbconfig->item('site_page_name_register');

		$skin_name = 'policy3';

		$data = array();
        $data['title_name'] = "데이터 수집방침";
		$view['view']['data'] = $data;

		$layoutconfig = array(
			'path' => 'register',
			'layout' => 'layout_popup',
			'skin' => $skin_name,
			'layout_dir' => $this->cbconfig->item('layout_main'),
            'mobile_layout_dir' => $this->cbconfig->item('layout_main'),
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => $this->cbconfig->item('skin_main'),
            'mobile_skin_dir' => $this->cbconfig->item('skin_main'),
            'page_title' => $page_title,
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'meta_author' => $meta_author,
            'page_name' => $page_name,
		);
		$view['layout'] = $this->managelayout->front($layoutconfig, ''/*$this->cbconfig->get_device_view_type()*/);
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view)); // 기본  layout 사용
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
     * 그룹에 사용자 추가 및 삭제 함수입니다
     */
    public function register_update_agree()
    {
		required_user_login();
		$updatedata = array(
					'mem_agree' => 1,
				);
		$this->Member_model->update($this->member->item('mem_id'), $updatedata);

        echo json_encode("성공");
    }

    public function search_school() {
        $searchKeyword = $this->input->post_get('search_keyword');
        $pageNum = !$this->input->post_get('page') ? 1 : $this->input->post_get('page');
        $perPage = 10;
        $param =& $this->querystring;
        $config['base_url'] = site_url() . 'register/search_school?' . $param->replace('page');
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
        $offsetNum = ($pageNum - 1) * $perPage;

        $query  = "SELECT * FROM edu_school_details ";
        $query .= "WHERE school_name LIKE '%".$searchKeyword."%' AND delete_status = 'N' ";
        $query .= "LIMIT $perPage OFFSET $offsetNum";
        $schoolList = $this->db->query($query)->result_array();

        $query  = "SELECT count(*) AS totalCount FROM edu_school_details ";
        $query .= "WHERE school_name LIKE '%".$searchKeyword."%' AND delete_status = 'N' ";
        $totalCount = $this->db->query($query)->row()->totalCount;

        $config['total_rows'] = $totalCount;
        $config['per_page'] = $perPage;

        $this->pagination->initialize($config);
        $page_link = $this->pagination->create_links();

        $result = array(
            "data" => $schoolList,
            "page_link" => $page_link,
            "page" => $pageNum
        );
        echo json_encode($result);
    }
}
