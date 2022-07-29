<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mobile_namechk extends CB_Controller
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
        $this->load->library(array('querystring', 'form_validation', 'email'));

        if ( ! function_exists('password_hash')) {
            $this->load->helper('password');
        }
    }


    /*
     * 휴대폰 본인인증 검사
     */
    public function mobile_self() {
        // 이벤트 라이브러리를 로딩합니다.
        $eventname = 'event_mobile_self';
        $this->load->event($eventname);

        if ($this->member->is_member() && ! ($this->member->is_admin() === 'super' && $this->uri->segment(1) === config_item('uri_segment_admin'))) {
            redirect();
        }

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다.
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $configbasic = array();
        
        
        /*
         * 본인인증 설정 값
         */
        $sitecode = $this->config->config['checkplus']['sitecode'];
        $sitepasswd = $this->config->config['checkplus']['sitepw'];

        $authtype = "M";        // 없으면 기본 선택화면, X : 공인인증서, M : 핸드폰, C: 카드

        $popgubun = "N";        // Y : 취소버튼 있음 / N : 취소버튼 없음
        $customize = "";        // 없으면 기본 페이지 / Mobile : 모바일 페이지

        $reqseq = "REQ_0123456789";     // 요청 번호, 이는 성공/실패후에 같은 값으로 되돌려주게 되므로

        // 업체에서 적절하게 변경하여 쓰거나, 아래와 같이 생성한다.
        //if (extension_loaded($module)) {// 동적으로 모듈 로드 했을경우
        if(function_exists('get_cprequest_no')) {
            $reqseq = get_cprequest_no($sitecode);
        }


        // CheckPlus(본인인증) 처리 후, 결과 데이타를 리턴 받기위해 다음예제와 같이 http부터 입력합니다.
        $returnurl = site_url('mobile_namechk/mobile_self_proc?result=success');                     // 성공시 이동될 URL
        $errorurl = site_url('mobile_namechk/mobile_self_proc?result=fail');                      // 실패시 이동될 URL


        $_SESSION['REQ_SEQ'] = $reqseq;

        // 입력될 plain 데이타를 만든다.
        $plaindata =  "7:REQ_SEQ" . strlen($reqseq) . ":" . $reqseq .
        "8:SITECODE" . strlen($sitecode) . ":" . $sitecode .
        "9:AUTH_TYPE" . strlen($authtype) . ":". $authtype .
        "7:RTN_URL" . strlen($returnurl) . ":" . $returnurl .
        "7:ERR_URL" . strlen($errorurl) . ":" . $errorurl .
        "11:POPUP_GUBUN" . strlen($popgubun) . ":" . $popgubun .
        "9:CUSTOMIZE" . strlen($customize) . ":" . $customize ;

        if(function_exists('get_encode_data')) {
            $enc_data = get_encode_data($sitecode, $sitepasswd, $plaindata);
        }

        if( $enc_data == -1 )
        {
            $returnMsg = "암/복호화 시스템 오류입니다.";
            $enc_data = "";
        }
        else if( $enc_data== -2 )
        {
            $returnMsg = "암호화 처리 오류입니다.";
            $enc_data = "";
        }
        else if( $enc_data== -3 )
        {
            $returnMsg = "암호화 데이터 오류 입니다.";
            $enc_data = "";
        }
        else if( $enc_data== -9 )
        {
            $returnMsg = "입력값 오류 입니다.";
            $enc_data = "";
        }

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        $view['view']['enc_data'] = $enc_data;

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
            'path' => 'mobile_namechk',
            'layout' => 'layout',
            'skin' => 'mobile_self',
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
        $view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));

    }


    // 휴대폰 본인인증 성공
    private function mobile_self_success() {

        $sitecode = $this->config->config['checkplus']['sitecode'];
        $sitepasswd = $this->config->config['checkplus']['sitepw'];

        $enc_data = $this->input->post('EncodeData');		// 암호화된 결과 데이타
        $sReserved1 = $this->input->post('param_r1');
        $sReserved2 = $this->input->post('param_r2');
        $sReserved3 = $this->input->post('param_r3');

        $mobile_success_flag = 0;

        //////////////////////////////////////////////// 문자열 점검///////////////////////////////////////////////
        if(preg_match('~[^0-9a-zA-Z+/=]~', $enc_data, $match)) {echo "입력 값 확인이 필요합니다 : ".$match[0]; exit;} // 문자열 점검 추가.
        if(base64_encode(base64_decode($enc_data))!=$enc_data) {echo "입력 값 확인이 필요합니다"; exit;}

        if(preg_match("/[#\&\\+\-%@=\/\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $sReserved1, $match)) {echo "문자열 점검 : ".$match[0]; exit;}
        if(preg_match("/[#\&\\+\-%@=\/\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $sReserved2, $match)) {echo "문자열 점검 : ".$match[0]; exit;}
        if(preg_match("/[#\&\\+\-%@=\/\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $sReserved3, $match)) {echo "문자열 점검 : ".$match[0]; exit;}
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////

        if ($enc_data != "") {

            $plaindata = get_decode_data($sitecode, $sitepasswd, $enc_data);// 암호화된 결과 데이터의 복호화

            if ($plaindata == -1){
                $returnMsg  = "암/복호화 시스템 오류";
            }else if ($plaindata == -4){
                $returnMsg  = "복호화 처리 오류";
            }else if ($plaindata == -5){
                $returnMsg  = "HASH값 불일치 - 복호화 데이터는 리턴됨";
            }else if ($plaindata == -6){
                $returnMsg  = "복호화 데이터 오류";
            }else if ($plaindata == -9){
                $returnMsg  = "입력값 오류";
            }else if ($plaindata == -12){
                $returnMsg  = "사이트 비밀번호 오류";
            }else{
                // 복호화가 정상적일 경우 데이터를 파싱합니다.		
                $requestnumber = $this->GetValue($plaindata, "REQ_SEQ");
                $responsenumber = $this->GetValue($plaindata , "RES_SEQ");
                $authtype = $this->GetValue($plaindata , "AUTH_TYPE");
                $name = iconv("euc-kr", "utf-8", $this->GetValue($plaindata , "NAME"));
                //$name = GetValue($plaindata , "UTF8_NAME");	// utf8일경우
                $birthdate = $this->GetValue($plaindata , "BIRTHDATE");
                $gender = $this->GetValue($plaindata , "GENDER");
                $nationalinfo = $this->GetValue($plaindata , "NATIONALINFO");	//내/외국인정보(사용자 매뉴얼 참조)
                $dupinfo = $this->GetValue($plaindata , "DI");
                $conninfo = $this->GetValue($plaindata , "CI");
                
                if(strcmp($_SESSION["REQ_SEQ"], $requestnumber) != 0)
                {
                    //echo "세션값이 다릅니다. 올바른 경로로 접근하시기 바랍니다.<br>";
                    $mobile_success_flag = 0;
                    $requestnumber = "";
                    $responsenumber = "";
                    $authtype = "";
                    $name = "";
                    $birthdate = "";
                    $gender = "";
                    $nationalinfo = "";
                    $dupinfo = "";
                    $conninfo = "";
                } else {
                    $mobile_success_flag = 1;	// 인증 성공
                }
            }
        }

        if($plaindata == "" || $plaindata < 0) {
            return "";
        } else {
            $info = array();

            $info['mobile_success_flag'] = $mobile_success_flag;
            $info['requestnumber'] = $requestnumber;
            $info['responsenumber'] = $responsenumber;
            $info['authtype'] = $authtype;
            $info['name'] = $name;
            $info['birthdate'] = $birthdate;
            $info['gender'] = $gender;
            $info['nationalinfo'] = $nationalinfo;
            $info['dupinfo'] = $dupinfo;
            $info['conninfo'] = $conninfo;
            $info['sReserved1'] = $sReserved1;
            $info['sReserved2'] = $sReserved2;
            $info['sReserved3'] = $sReserved3;

            return $info;
        }

    }


    // 휴대폰 본인인증 실패
    private function mobile_self_fail() {

    }


    public function mobile_self_proc() {

        $result_value = $this->input->get('result');

        if($result_value == 'success') {
            $info = $this->mobile_self_success();

            if( is_array($info) ) {
                // 다음단계로 이동
                if($info['name'] && $info['dupinfo']) {
                    /*

                    $submit_form = "
                    frm.chkplus_flag.value=\"Y\";
                    frm.mem_id_name.value = \"".$info['name']."\";
                    frm.mem_id_num.value = \"".$info['dupinfo']."\";		// DI
                    frm.sReserved1.value = \"".$info['sReserved1']."\";		// 사용유형 타입 JOIN : 회원 신규 가입
                    frm.action = \"".site_url('register/form')."\";
                    ";

                    $script_html = "
                    <script type=\"text/javascript\">
                    $(document).ready(function() {
                        var frm = opener.document.return_form_chk;
                        ".$submit_form."
                        frm.submit();
                        window.close();
                    });
                    </script>
                    ";
                    */
                    // mem_id_name, mem_id_num을 session에 저장후 form페이지로 이동
                    $_SESSION['mem_id_name'] = $info['name'];
                    $_SESSION['mem_id_num'] = $info['dupinfo'];

                    $script_html = "
                    <script type=\"text/javascript\">
                    $(document).ready(function() {
                        opener.location.href='".site_url('register/form')."';
                        window.close();
                    });
                    </script>
                    ";
                } else {
                    $script_html = "
                    <script type=\"text/javascript\">
                    alert(\"실명인증값을 정상적으로 호출하지 못하였습니다. 다시 시도해 주세요\");
                    self.close();
                    </script>
                    ";
                }

            } else {

                $script_html = "
                <script type=\"text/javascript\">
                alert(\"세션값이 다릅니다. 올바른 경로로 접근하시기 바랍니다.\");
                self.close();
                </script>
                ";
            }

        } else {

        }


        // 이벤트 라이브러리를 로딩합니다.
        $eventname = 'event_mobile_self_proc';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다.
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['view']['script_html'] = $script_html;

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


        $layoutconfig = array(
            'path' => 'mobile_namechk',
            'layout' => 'layout_popup',
            'skin' => 'mobile_self_proc',
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
        $view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));


    }


    private function GetValue($str , $name)
    {
        $pos1 = 0;  //length의 시작 위치
        $pos2 = 0;  //:의 위치	
        
        while( $pos1 <= strlen($str) )
        {
            $pos2 = strpos( $str , ":" , $pos1);
            $len = substr($str , $pos1 , $pos2 - $pos1);
            $key = @substr($str , $pos2 + 1 , $len);
            $pos1 = $pos2 + $len + 1;
            if( $key == $name )
            {
                $pos2 = strpos( $str , ":" , $pos1);
                $len = substr($str , $pos1 , $pos2 - $pos1);
                $value = substr($str , $pos2 + 1 , $len);
                return $value;
            }
            else
            {
                // 다르면 스킵한다.
                $pos2 = strpos( $str , ":" , $pos1);
                $len = substr($str , $pos1 , $pos2 - $pos1);
                $pos1 = $pos2 + $len + 1;
            }
        }
    }

}