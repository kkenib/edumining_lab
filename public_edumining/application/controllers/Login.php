<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Login class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 로그인 페이지와 관련된 controller 입니다.
 */
class Login extends IMC_Controller
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

    }

        /**
     * 로그인 페이지입니다
     */
//     public function index()
//     {

//         // 이벤트 라이브러리를 로딩합니다
//         $eventname = 'event_login_index';
//         $this->load->event($eventname);
		
//         if ($this->member->is_member() !== false && ! ($this->member->is_admin() === 'super' && $this->uri->segment(1) === config_item('uri_segment_admin'))) {
//             redirect();
//         }

//         $view = array();
//         $view['view'] = array();

//         // 이벤트가 존재하면 실행합니다
//         $view['view']['event']['before'] = Events::trigger('before', $eventname);

//         $this->load->library(array('form_validation'));

//          if ( ! function_exists('password_hash')) {
//             $this->load->helper('password');
//         }

//         $use_login_account = $this->cbconfig->item('use_login_account');

//         /**
//          * 전송된 데이터의 유효성을 체크합니다
//          */
//         if ($use_login_account === 'both') {
//             $config[] = array(
//                 'field' => 'mem_userid',
//                 'label' => '아이디 또는 이메일',
//                 'rules' => 'trim|required',
//             );
//             $view['view']['userid_label_text'] = '아이디 또는 이메일';
//         } elseif ($use_login_account === 'email') {
//             $config[] = array(
//                 'field' => 'mem_userid',
//                 'label' => '이메일',
//                 'rules' => 'trim|required|valid_email',
//             );
//             $view['view']['userid_label_text'] = '이메일';
//         } else {
//             $config[] = array(
//                 'field' => 'mem_userid',
//                 'label' => '아이디',
//                 'rules' => 'trim|required|alphanumunder|min_length[3]|max_length[20]',
//             );
//             $view['view']['userid_label_text'] = '아이디';
//         }
//         $config[] = array(
//             'field' => 'mem_password',
//             'label' => '패스워드',
//             'rules' => 'trim|required|min_length[4]|callback__check_id_pw[' . $this->input->post('mem_userid', TRUE) . ']',
//         );

//         $this->form_validation->set_rules($config);
//         /**
//          * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
//          * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
//          */
        
//         if ($this->form_validation->run() === false) {
//             // 이벤트가 존재하면 실행합니다
//             $view['view']['event']['formrunfalse'] = Events::trigger('formrunfalse', $eventname);

//             if ($this->input->post('returnurl')) {
//                 if (validation_errors('<div class="alert alert-warning" role="alert">', '</div>')) {
//                     $this->session->set_flashdata(
//                         'loginvalidationmessage',
//                         validation_errors('<div class="alert alert-warning" role="alert">', '</div>')
//                     );
//                 }
//                 $this->session->set_flashdata(
//                     'loginuserid',
//                     $this->input->post('mem_userid')
//                 );
//                 redirect(urldecode($this->input->post('returnurl')));
//             }

//             $view['view']['canonical'] = site_url('login');

//             // 이벤트가 존재하면 실행합니다
//             $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

//             /**
//              * 레이아웃을 정의합니다
//              */
//             $page_title = $this->cbconfig->item('site_meta_title_login');
//             $meta_description = $this->cbconfig->item('site_meta_description_login');
//             $meta_keywords = $this->cbconfig->item('site_meta_keywords_login');
//             $meta_author = $this->cbconfig->item('site_meta_author_login');
//             $page_name = $this->cbconfig->item('site_page_name_login');
//             log_message('ERROR', json_encode($this->cbconfig->item('layout_login')));

//             $layoutconfig = array(
//                 'path' => 'login',
//                 'layout' => 'layout_test',
//                 'skin' => 'login',
//                 'layout_dir' => '../login',
//                 'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_login'),
//                 'use_sidebar' => $this->cbconfig->item('sidebar_login'),
//                 'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_login'),
//                 'skin_dir' => $this->cbconfig->item('skin_login'),
//                 'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_login'),
//                 'page_title' => $page_title,
//                 'meta_description' => $meta_description,
//                 'meta_keywords' => $meta_keywords,
//                 'meta_author' => $meta_author,
//                 'page_name' => $page_name,
//             );
//             log_message('ERROR', json_encode($layoutconfig));
//             $view['layout'] = $this->managelayout->front($layoutconfig, '');
//             $this->data = $view;
//             $this->layout = element('layout_skin_file', element('layout', $view)); // 기본  layout 사용
//             $this->view = element('view_skin_file', element('layout', $view));
//         } else {
//             /**
//              * 유효성 검사를 통과한 경우입니다.
//              * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
//              */
        	
//             // 이벤트가 존재하면 실행합니다
//             $view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

//             if ($use_login_account === 'both') {
//                 $userinfo = $this->Member_model->get_by_both($this->input->post('mem_userid', TRUE), 'mem_id, mem_userid');
//             } elseif ($use_login_account === 'email') {
//                 $userinfo = $this->Member_model->get_by_email($this->input->post('mem_userid', TRUE), 'mem_id, mem_userid');
//             } else {
//                 $userinfo = $this->Member_model->get_by_userid($this->input->post('mem_userid', TRUE), 'mem_id, mem_userid');
//             }
            
//             $this->member->update_login_log(element('mem_id', $userinfo), $this->input->post('mem_userid', TRUE), 1, '로그인 성공');
//             $this->session->set_userdata(
//                 'mem_id',
//                 element('mem_id', $userinfo)
//             );
// //log_message('ERROR', json_encode($userinfo));

//             $change_password_date = $this->cbconfig->item('change_password_date');
//             $site_title = $this->cbconfig->item('site_title');
//             if ($change_password_date) {
//                 $meta_change_pw_datetime = $this->member->item('meta_change_pw_datetime');
//                 if ( ctimestamp() - strtotime($meta_change_pw_datetime) > $change_password_date * 86400) {
//                     $this->session->set_userdata(
//                         'membermodify',
//                         '1'
//                     );
//                     $this->session->set_flashdata(
//                         'message',
//                         html_escape($site_title) . ' 은(는) 회원님의 비밀번호를 주기적으로 변경하도록 권장합니다.
//                         <br /> 오래된 비밀번호를 사용중인 회원님께서는 안전한 서비스 이용을 위해 비밀번호 변경을 권장합니다'
//                     );
//                     redirect('membermodify/month_password_modify');
//                 }
//             }



//             $url_after_login = $this->cbconfig->item('url_after_login');
            
//             if ($url_after_login) {
//                 $url_after_login = site_url($url_after_login);
//             }

// 			$url_after_login = $this->input->get_post('url') ? urldecode($this->input->get_post('url')) : site_url($url_after_login);
//             log_message('ERROR', json_encode($url_after_login));
			
            
//             // 이벤트가 존재하면 실행합니다
//             Events::trigger('after', $eventname);
// //log_message('ERROR', json_encode($url_after_login));
// 			if(strpos($url_after_login, "vmobile") !== false) {  
// 				$url_after_login = "/voice/main/vmobile";
// 			}
// 			/*
// 			else{
// 				$url_after_login = "/main";
// 			}
// 			*/

// 			//if($url_after_login == "http://".$_SERVER["HTTP_HOST"]."/main" || $url_after_login == "http://".$_SERVER["HTTP_HOST"]."/"){
// 			//	$url_after_login = "/main/main";
// 			//}

//             redirect($url_after_login);
//         }
//     }

    /**
     * 로그인 페이지입니다
     */
    public function index()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_login_index';
        $this->load->event($eventname);
		
        if ($this->member->is_member() !== false && ! ($this->member->is_admin() === 'super' && $this->uri->segment(1) === config_item('uri_segment_admin'))) {
            redirect();
        }

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $this->load->library(array('form_validation'));

         if ( ! function_exists('password_hash')) {
            $this->load->helper('password');
        }

        $use_login_account = $this->cbconfig->item('use_login_account');

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['formrunfalse'] = Events::trigger('formrunfalse', $eventname);

        if ($this->input->post('returnurl')) {
            if (validation_errors('<div class="alert alert-warning" role="alert">', '</div>')) {
                $this->session->set_flashdata(
                    'loginvalidationmessage',
                    validation_errors('<div class="alert alert-warning" role="alert">', '</div>')
                );
            }
            $this->session->set_flashdata(
                'loginuserid',
                $this->input->post('mem_userid')
            );
            redirect(urldecode($this->input->post('returnurl')));
        }

        $view['view']['canonical'] = site_url('login');

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->cbconfig->item('site_meta_title_login');
        $meta_description = $this->cbconfig->item('site_meta_description_login');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_login');
        $meta_author = $this->cbconfig->item('site_meta_author_login');
        $page_name = $this->cbconfig->item('site_page_name_login');
        log_message('ERROR', json_encode($this->cbconfig->item('layout_login')));

        $layoutconfig = array(
            'path' => 'login',
            'layout' => 'layout_test',
            'skin' => 'login',
            'layout_dir' => '../login',
            'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_login'),
            'use_sidebar' => $this->cbconfig->item('sidebar_login'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_login'),
            'skin_dir' => $this->cbconfig->item('skin_login'),
            'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_login'),
            'page_title' => $page_title,
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'meta_author' => $meta_author,
            'page_name' => $page_name,
        );
        log_message('ERROR', json_encode($layoutconfig));
        $view['layout'] = $this->managelayout->front($layoutconfig, '');
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view)); // 기본  layout 사용
        $this->view = element('view_skin_file', element('layout', $view));
    }
    
    /**
     * 로그인 페이지입니다
     */
    public function login_action()
    {
        $memUserId = $this->input->post('mem_userid', TRUE);
        $memUserId = preg_replace("/[ #\&\+%@=\/\\\:;,\.'\"\^`~|\!\?\*$#<>()\[\]\{\}]/i", "", $memUserId);
        $memUserId = htmlspecialchars($memUserId);
        if(strlen($memUserId) == 0) {
            return;
        }


        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_login_index';
        $this->load->event($eventname);
		
        if ($this->member->is_member() !== false && ! ($this->member->is_admin() === 'super' && $this->uri->segment(1) === config_item('uri_segment_admin'))) {
            redirect();
        }

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $this->load->library(array('form_validation'));

         if ( ! function_exists('password_hash')) {
            $this->load->helper('password');
        }

        $use_login_account = $this->cbconfig->item('use_login_account');

        /**
         * 전송된 데이터의 유효성을 체크합니다
         */
        if ($use_login_account === 'both') {
            $config[] = array(
                'field' => 'mem_userid',
                'label' => '아이디 또는 이메일',
                'rules' => 'trim|required',
            );
            $view['view']['userid_label_text'] = '아이디 또는 이메일';
        } elseif ($use_login_account === 'email') {
            $config[] = array(
                'field' => 'mem_userid',
                'label' => '이메일',
                'rules' => 'trim|required|valid_email',
            );
            $view['view']['userid_label_text'] = '이메일';
        } else {
            $config[] = array(
                'field' => 'mem_userid',
                'label' => '아이디',
                'rules' => 'trim|required|alphanumunder|min_length[3]|max_length[20]',
            );
            $view['view']['userid_label_text'] = '아이디';
        }
        $config[] = array(
            'field' => 'mem_password',
            'label' => '패스워드',
            'rules' => 'trim|required|min_length[4]|callback__check_id_pw[' . $this->input->post('mem_userid', TRUE) . ']',
        );

        $this->form_validation->set_rules($config);
        /**
         * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
         * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
         */
        
        if ($this->form_validation->run() === true) {
            /**
             * 유효성 검사를 통과한 경우입니다.
             * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
             */
        	
            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

            if ($use_login_account === 'both') {
                $userinfo = $this->Member_model->get_by_both($this->input->post('mem_userid', TRUE), 'mem_id, mem_userid');
            } elseif ($use_login_account === 'email') {
                $userinfo = $this->Member_model->get_by_email($this->input->post('mem_userid', TRUE), 'mem_id, mem_userid');
            } else {
                $userinfo = $this->Member_model->get_by_userid($this->input->post('mem_userid', TRUE), 'mem_id, mem_userid');
            }
            
            $this->member->update_login_log(element('mem_id', $userinfo), $this->input->post('mem_userid', TRUE), 1, '로그인 성공');
            $this->session->set_userdata(
                'mem_id',
                element('mem_id', $userinfo)
            );
//log_message('ERROR', json_encode($userinfo));

            $change_password_date = $this->cbconfig->item('change_password_date');
            $site_title = $this->cbconfig->item('site_title');
            if ($change_password_date) {
                $meta_change_pw_datetime = $this->member->item('meta_change_pw_datetime');
                if ( ctimestamp() - strtotime($meta_change_pw_datetime) > $change_password_date * 86400) {
                    $this->session->set_userdata(
                        'membermodify',
                        '1'
                    );
                    $this->session->set_flashdata(
                        'message',
                        html_escape($site_title) . ' 은(는) 회원님의 비밀번호를 주기적으로 변경하도록 권장합니다.
                        <br /> 오래된 비밀번호를 사용중인 회원님께서는 안전한 서비스 이용을 위해 비밀번호 변경을 권장합니다'
                    );
                    redirect('membermodify/month_password_modify');
                }
            }



            $url_after_login = $this->cbconfig->item('url_after_login');
            
            if ($url_after_login) {
                $url_after_login = site_url($url_after_login);
            }

			$url_after_login = $this->input->get_post('url') ? urldecode($this->input->get_post('url')) : site_url($url_after_login);
            log_message('ERROR', json_encode($url_after_login));
			
            
            // 이벤트가 존재하면 실행합니다
            Events::trigger('after', $eventname);
//log_message('ERROR', json_encode($url_after_login));
			if(strpos($url_after_login, "vmobile") !== false) {  
				$url_after_login = "/voice/main/vmobile";
			}
			/*
			else{
				$url_after_login = "/main";
			}
			*/

			//if($url_after_login == "http://".$_SERVER["HTTP_HOST"]."/main" || $url_after_login == "http://".$_SERVER["HTTP_HOST"]."/"){
			//	$url_after_login = "/main/main";
			//}

            redirect($url_after_login);
        } else {

            

            // $this->session->set_flashdata(
                // 'message',
                // "ㅎㅎㅎ"
            // );
            // redirect("/login");

            redirect("/login?error=fail");
        }
    }


    /**
     * 로그인시 아이디와 패스워드가 일치하는지 체크합니다
     */
    public function _check_id_pw($password, $userid)
    {
        if ( ! function_exists('password_hash')) {
            $this->load->helper('password');
        }

        $max_login_try_count = (int) $this->cbconfig->item('max_login_try_count');
        $max_login_try_limit_second = (int) $this->cbconfig->item('max_login_try_limit_second');

        $loginfailnum = 0;
        $loginfailmessage = '';
        if ($max_login_try_count && $max_login_try_limit_second) {
            $select = 'mll_id, mll_success, mem_id, mll_ip, mll_datetime';
            $where = array(
                'mll_ip' => $this->input->ip_address(),
                'mll_datetime > ' => strtotime(ctimestamp() - 86400 * 30),
            );
            $this->load->model('Member_login_log_model');
            $logindata = $this->Member_login_log_model
                ->get('', $select, $where, '', '', 'mll_id', 'DESC');

            if ($logindata && is_array($logindata)) {
                foreach ($logindata as $key => $val) {
                    if ((int) $val['mll_success'] === 0) {
                        $loginfailnum++;
                    } elseif ((int) $val['mll_success'] === 1) {
                        break;
                    }
                }
            }
            if ($loginfailnum > 0 && $loginfailnum % $max_login_try_count === 0) {
                $lastlogintrydatetime = $logindata[0]['mll_datetime'];
                $next_login = strtotime($lastlogintrydatetime)
                    + $max_login_try_limit_second
                    - ctimestamp();
                if ($next_login > 0) {
                    $this->form_validation->set_message(
                        '_check_id_pw',
                        '회원님은 패스워드를 연속으로 ' . $loginfailnum . '회 잘못 입력하셨기 때문에 '
                        . $next_login . '초 후에 다시 로그인 시도가 가능합니다'
                    );
                    return false;
                }
            }
            $loginfailmessage = '<br />회원님은 ' . ($loginfailnum + 1)
                . '회 연속으로 패스워드를 잘못입력하셨습니다. ';
        }

        $use_login_account = $this->cbconfig->item('use_login_account');

        $userselect = 'mem_id, mem_password, mem_email_cert, mem_is_admin';
        if ($use_login_account === 'both') {
            $userinfo = $this->Member_model->get_by_both($userid, $userselect);
        } elseif ($use_login_account === 'email') {
            $userinfo = $this->Member_model->get_by_email($userid, $userselect);
        } else {
            $userinfo = $this->Member_model->get_by_userid($userid, $userselect);
        }

        $mem_denied = $this->Member_model->get_by_userid($this->input->post('mem_userid', TRUE), 'mem_denied');

        $hash = password_hash($password, PASSWORD_BCRYPT);

        if ( ! element('mem_id', $userinfo) OR ! element('mem_password', $userinfo) OR $mem_denied["mem_denied"] == 1) {
            $this->form_validation->set_message(
                '_check_id_pw',
                '회원 아이디가 존재하지 않거나 승인되지 않았습니다' . $loginfailmessage
            );
            $this->member->update_login_log(0, $userid, 0, '회원 아이디가 존재하지 않거나 승인되지 않았습니다');
            return false;
        } elseif ( ! password_verify($password, element('mem_password', $userinfo))) {
            log_message('ERROR', $password . "\t" . password_verify($password, element('mem_password', $userinfo)));
            $this->form_validation->set_message(
                '_check_id_pw',
                '패스워드가 올바르지 않습니다' . $loginfailmessage
            );
            $this->member->update_login_log(element('mem_id', $userinfo), $userid, 0, '패스워드가 올바르지 않습니다');
            return false;
        } elseif ($this->cbconfig->item('use_register_email_auth') && ! element('mem_email_cert', $userinfo)) {
            $this->form_validation->set_message(
                '_check_id_pw',
                '회원님은 아직 이메일 인증을 받지 않으셨습니다'
            );
            $this->member->update_login_log(element('mem_id', $userinfo), $userid, 0, '이메일 인증을 받지 않은 회원아이디입니다');
            return false;
        } elseif (element('mem_is_admin', $userinfo) && $this->input->post('autologin')) {
            $this->form_validation->set_message(
                '_check_id_pw',
                '최고관리자는 자동로그인 기능을 사용할 수 없습니다'
            );
            return false;
        }
		
        return true;
    }


    /**
     * 로그아웃합니다
     */
    public function logout()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_logout_index';
        $this->load->event($eventname);

        // 이벤트가 존재하면 실행합니다
        Events::trigger('before', $eventname);

        if ($this->member->is_member() === false) {
            redirect();
        }

        $where = array(
            'mem_id' => $this->member->item('mem_id'),
        );
        
        $this->session->sess_destroy();
        
        $url_after_logout = $this->cbconfig->item('url_after_logout');
        if ($url_after_logout) {
            $url_after_logout = site_url($url_after_logout);
        }
        if (empty($url_after_logout)) {
            $url_after_logout = $this->input->get_post('url') ? $this->input->get_post('url') : site_url();
        }
		
		$uri_arr = explode("/", $url_after_logout);
		$active_page_length = sizeof($uri_arr);
		$current_page = $uri_arr[$active_page_length-2] . '/' . $uri_arr[$active_page_length-1];
		
		$current_page = explode("?", $current_page);
		
		// 가입 성공 화면에서 바로 로그아웃 할 경우 로그인 화면으로 전환
		if ($current_page[0] === 'register/result') {
			$url_after_logout = 'login?url=' . urlencode($url_after_logout);
		}
		
        // 이벤트가 존재하면 실행합니다
        Events::trigger('after', $eventname);

        redirect($url_after_logout, 'refresh');
    }
    
    /**
     * 카카오 로그인 callback
     * API 사용 운영정책
     * 요청 길이(Request Length): 30초 이내 – Hard
     * MAU: 50만명 초과 – Hard
     * 일일 총 API호출 1000만 건 초과 - Hard
     * 네트워크 대역폭(Network Bandwidth): 2TB/Month - Soft
     */
    public function kakao_login_callback()
    {
    	if(isset($_GET['code'])) {
    		// state 확인  Cross-site Request Forgery 공격을 보호하기 위해 활용 가능.
    		if(!isset($_SESSION['kakao_state']) OR !isset($_GET['state']) OR $_SESSION['kakao_state'] != $_GET['state']) {
    			unset($_SESSION['kakao_state']);
    			return;
    		}
    		
    		// access token 발급받기
    		$ch = curl_init();
    		curl_setopt($ch, CURLOPT_URL, 'https://kauth.kakao.com/oauth/token');
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    		curl_setopt($ch, CURLOPT_POST, TRUE);
    		$param = 'grant_type=authorization_code';
    		$param .= '&client_id=' . KAKAO_RESTAPI_KEY;
    		$param .= '&redirect_uri=' . urlencode(site_url(KAKAO_REDIRECT_URI));
    		$param .= '&code=' . $_GET['code'];
    		$param .= '&state=' . $_GET['state'];
    		curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
    		$response = curl_exec($ch);
    		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    		curl_close($ch);
    		unset($_SESSION['kakao_state']);
    		
    		// status code 확인
    		if($status_code != 200) {
    			alert('카카오 로그인 토큰 발급에 실패 하였습니다.\\r\\n다시 시도해 주시기 바랍니다.\\r\\n(오류 코드 : '.$status_code.')', site_url('login?url=' . urlencode('/textomi/insight/gyeonggidoEconomy')));
    			return;
    		}
    		
    		// access token 저장
    		//$_SESSION['kakao_access_token'] = json_decode($response, TRUE);
    		//$_SESSION['kakao_access_token']['created'] = time();
    		$access_token = json_decode($response)->access_token;
    		
    		// 기본 사용자 정보 요청
    		$ch = curl_init();
    		curl_setopt($ch, CURLOPT_URL, 'https://kapi.kakao.com/v2/user/me');
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    		curl_setopt($ch, CURLOPT_POST, TRUE);
    		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
    		//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $_SESSION['kakao_access_token']['access_token']));
    		$response = curl_exec($ch);
    		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    		curl_close($ch);
    		
    		// status code 확인
    		if($status_code != 200) {
    			alert('카카오 사용자 정보 요청에 실패 하였습니다.\\r\\n다시 시도해 주시기 바랍니다.\\r\\n(오류 코드 : '.$status_code.')', site_url('login?url=' . urlencode('/textomi/insight/gyeonggidoEconomy')));
    			return;
    		}
    		$response = json_decode($response, TRUE);
    		
    		// 기본 사용자 정보 저장
    		$userid = $response['id'];
    		$username = $response['properties']['nickname'];
    		$has_email = $response['kakao_account']['has_email'];
    		$email = '';
    		if($has_email){
    			$email = $response['kakao_account']['email'];
    		}
    		
    		// 회원 가입 및 로그인
    		$this->sns_account_register_login('kakao', $userid, $username, $email);
    	}
    	else {
    		alert('카카오 로그인 토큰 발급을 위한 code 값이 설정되지 않았습니다.\\r\\n다시 시도해 주시기 바랍니다.', site_url('login?url=' . urlencode('/textomi/insight/gyeonggidoEconomy')));
    	}
    }
    
    /**
     * 네이버 로그인 callback
     */
    public function naver_login_callback()
    {
    	if(isset($_GET['code'])) {
    		// state 확인  Cross-site Request Forgery 공격을 보호하기 위해 활용 가능.
    		if(!isset($_SESSION['naver_state']) OR !isset($_GET['state']) OR $_SESSION['naver_state'] != $_GET['state']) {
    			unset($_SESSION['naver_state']);
    			return;
    		}
    		
    		// access token 발급받기
    		$ch = curl_init();
    		curl_setopt($ch, CURLOPT_URL, 'https://nid.naver.com/oauth2.0/token');
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    		curl_setopt($ch, CURLOPT_POST, TRUE);
    		$param = 'grant_type=authorization_code';
    		$param .= '&client_id=' . NAVER_CLIENT_ID;
    		$param .= '&client_secret=' . NAVER_CLIENT_SECRET;
    		$param .= '&redirect_uri=' . urlencode(site_url(NAVER_REDIRECT_URI));
    		$param .= '&code=' . $_GET['code'];
    		$param .= '&state=' . $_GET['state'];
    		curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
    		$response = curl_exec($ch);
    		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    		curl_close($ch);
    		unset($_SESSION['naver_state']);
    		
    		// status code 확인
    		if($status_code != 200) {
    			alert('네이버 로그인 토큰 발급에 실패 하였습니다.\\r\\n다시 시도해 주시기 바랍니다.\\r\\n(오류 코드 : '.$status_code.')', site_url('login?url=' . urlencode('/textomi/insight/gyeonggidoEconomy')));
    			return;
    		}
    		
    		// access token 저장
    		//$_SESSION['naver_access_token'] = json_decode($response, TRUE);
    		//$_SESSION['naver_access_token']['created'] = time();
    		$access_token = json_decode($response)->access_token;
    		
    		// 기본 사용자 정보 요청
    		$ch = curl_init();
    		curl_setopt($ch, CURLOPT_URL, 'https://openapi.naver.com/v1/nid/me');
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    		curl_setopt($ch, CURLOPT_POST, TRUE);
    		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
    		//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $_SESSION['naver_access_token']['access_token']));
    		$response = curl_exec($ch);
    		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    		curl_close($ch);
    		
    		// status code 확인
    		if($status_code != 200) {
    			alert('네이버 사용자 정보 요청에 실패 하였습니다.\\r\\n다시 시도해 주시기 바랍니다.\\r\\n(오류 코드 : '.$status_code.')', site_url('login?url=' . urlencode('/textomi/insight/gyeonggidoEconomy')));
    			return;
    		}
    		
    		$response = json_decode($response, TRUE);
    		
    		// 기본 사용자 정보 저장
    		$userid = $response['response']['id'];
    		$username = $response['response']['name'];
    		$email = $response['response']['email'];
    		
    		// 회원 가입 및 로그인
    		$this->sns_account_register_login('naver', $userid, $username, $email);
    	}
    	else {
    		alert('네이바 로그인 토큰 발급을 위한 code 값이 설정되지 않았습니다.\\r\\n다시 시도해 주시기 바랍니다.', site_url('login?url=' . urlencode('/textomi/insight/gyeonggidoEconomy')));
    	}
    }
    
    /**
     * SNS 연동 계정 회원 가입 및 로그인 진행 함수
     */
    public function sns_account_register_login($group, $userid, $username, $email)
    {
    	// 가입 여부 체크
    	$use_login_account = $this->cbconfig->item('use_login_account');
    	$userselect = 'mem_id, mem_email_cert, mem_is_admin';
    	
    	if ($use_login_account === 'both') {
    		$userinfo = $this->Member_model->get_by_both($userid, $userselect);
    	} elseif ($use_login_account === 'email') {
    		$userinfo = $this->Member_model->get_by_email($userid, $userselect);
    	} else {
    		$userinfo = $this->Member_model->get_by_userid($userid, $userselect);
    	}
    	
    	// 멤버 등록
    	if(!element('mem_id', $userinfo)) { // 해당 기기에 로그인 이력이 없는 경우 최초 회원 등록 진행
    		$mem_level = (int) $this->cbconfig->item('register_level');
    		
    		$insertdata = array();
    		//$metadata = array();
    		 
    		$insertdata['mem_userid'] = $userid;
    		$insertdata['mem_email'] = $email;
    		$insertdata['mem_nickname'] = $username;
    		//$metadata['meta_nickname_datetime'] = cdate('Y-m-d H:i:s');
    		$insertdata['mem_level'] = $mem_level;
    		$insertdata['mem_group'] = $group;
    		 
    		$insertdata['mem_username'] = $username;
    		 
    		//$metadata['meta_open_profile_datetime'] = cdate('Y-m-d H:i:s');
    		$insertdata['mem_register_datetime'] = cdate('Y-m-d H:i:s');
    		$insertdata['mem_register_ip'] = $this->input->ip_address();
    		//$metadata['meta_change_pw_datetime'] = cdate('Y-m-d H:i:s');
    		 
    		if ($this->cbconfig->item('use_register_email_auth')) {
    			$insertdata['mem_email_cert'] = 0;
    			//$metadata['meta_email_cert_datetime'] = '';
    		} else {
    			$insertdata['mem_email_cert'] = 1;
    			//$metadata['meta_email_cert_datetime'] = cdate('Y-m-d H:i:s');
    		}
    		 
    		/* 휴대폰 본인 인증 시 사용 */
    		$insertdata['mem_id_name'] = $username;
    		//$insertdata['mem_id_num'] = $mem_id_num;
    		$insertdata['mem_id_num'] = "NEED_TO_AUTH";
    		 
    		$mem_id = $this->Member_model->insert($insertdata);
    	
    		$useridinsertdata = array(
    				'mem_id' => $mem_id,
    				'mem_userid' => $userid,
    		);
    		 
    		$this->Member_userid_model->insert($useridinsertdata);
    	}
    	
    	// 로그인 진행
    	$userselect = 'mem_id, mem_userid';
    	if ($use_login_account === 'both') {
    		$userinfo = $this->Member_model->get_by_both($userid, $userselect);
    	} elseif ($use_login_account === 'email') {
    		$userinfo = $this->Member_model->get_by_email($userid, $userselect);
    	} else {
    		$userinfo = $this->Member_model->get_by_userid($userid, $userselect);
    	}
    	
    	$this->member->update_login_log(element('mem_id', $userinfo), $userid, 1, '로그인 성공');
    	$this->session->set_userdata(
    			'mem_id',
    			element('mem_id', $userinfo)
    			);
    	
    	$url_after_login = $this->cbconfig->item('url_after_login');
    	
    	if ($url_after_login) {
    		$url_after_login = site_url($url_after_login);
    	}
    	if (empty($url_after_login)) {
    		$url_after_login = $this->input->get_post('url') ? urldecode($this->input->get_post('url')) : site_url();
    	}
    	
    	$uri_arr = explode("/", $this->input->get_post('url'));
    	$active_page_length = sizeof($uri_arr);
    	$current_page = $uri_arr[$active_page_length-2] . '/' . $uri_arr[$active_page_length-1];
    	
    	// 맞춤분석 화면에서 로그인 시 해당 화면으로 이동
    	if ($current_page === 'customAnalysis/reportList' OR $current_page === 'customAnalysis/keywordAnalysis') {
    		$url_after_login = '/textomi/'.$current_page;
    	}
    	
    	redirect($url_after_login);
    }
}
